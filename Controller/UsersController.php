<?php
//namespace CakeCms\Controller;

App::uses('CakeCmsAppController', 'CakeCms.Controller');
/**
 * Users Controller
 *
 */
class UsersController extends CakeCmsAppController
{
	public $name = 'Users';

	public function beforeFilter()
	{
    parent::beforeFilter();
//    $this->Auth->allow();
    $this->Auth->allow(array('login', 'logout', 'reset_password', 'recover_username', 'sign_in', 'sign_out', 'sign_up'));
    $this->Security->unlockedActions = array('login', 'sign_in');
	}


	private function _writeToSessionAuthPermissions($user)
	{
		$aro = $this->Acl->Aro->find('first', array(
				'conditions' => array(
						'Aro.model' => 'Group',
						'Aro.foreign_key' => $user['group_id'],
				),
		));
		$acos = $this->Acl->Aco->children();
		$aco_ids = array();
		foreach ($acos as $aco) {
			$aco_ids[] = $aco['Aco']['id'];
		}
		$permissions = $this->Acl->Aro->Permission->find('all', array(
				'conditions' => array(
						'Permission.aro_id'	=> $aro['Aro']['id'],
						'Permission.aco_id'	=> $aco_ids,
						'OR'	=> array(
							'Permission._create'	=> 1,
							'Permission._read'	=> 1,
							'Permission._update'	=> 1,
							'Permission._delete'	=> 1
						)
				),
		));
		foreach ($permissions as $permission) {
			if (isset($permission['Permission']['id'])) {
				if (!empty($permission['Aco']['parent_id'])) {
					$parentAco = $this->Acl->Aco->find('first', array('conditions' => array('id' => $permission['Aco']['parent_id'])));
					if ('controllers' !== $parentAco['Aco']['alias']) {
						if (!is_array($this->Session->read('Auth.Permissions.' . $parentAco['Aco']['alias']))) {
							$this->Session->write('Auth.Permissions.' . $parentAco['Aco']['alias'], array());
						}
						$this->Session->write('Auth.Permissions.' . $parentAco['Aco']['alias'] . '.' . $permission['Aco']['alias'], true);
					}
					else if (!empty($parentAco['Aco']['parent_id'])) {
						$grandParentAco = $this->Acl->Aco->find('first', array('conditions' => array('id' => $parentAco['Aco']['parent_id'])));
						if ('controllers' !== $grandParentAco['Aco']['alias']) {
							if (!is_array($this->Session->read('Auth.Permissions.' . $grandParentAco['Aco']['alias'] . '.' . $parentAco['Aco']['alias']))) {
								$this->Session->write('Auth.Permissions.' . $grandParentAco['Aco']['alias'] . '.' . $parentAco['Aco']['alias'], array());
							}
							$this->Session->write('Auth.Permissions.' . $grandParentAco['Aco']['alias'] . '.' . $parentAco['Aco']['alias'] .'.'. $permission['Aco']['alias'], true);
						}
					}
				}
				else {
					$this->Session->write('Auth.Permissions.' . $permission['Aco']['alias'], true);
				}
			}
		}
	}

	public function add()
	{
		if (!empty($this->data)) {
			$this->User->create();

			if ($this->User->save($this->data)) {
				$this->setCakeCmsFlash(__('The user has been saved'), 'success');
				$this->redirect(array('action' => 'index'));
			}

			$this->setCakeCmsFlash(__('The user could not be saved. Please, try again.'));
		}

		$roles = $this->User->roles;

		$Group = ClassRegistry::init('Group');
		$groups = $Group->find('list');

		$this->set(compact('roles','groups'));
	}

	public function delete($id = null)
	{
		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		if ($this->User->delete($id)) {
			$this->setCakeCmsFlash(__('User deleted.'), 'success');
			$this->redirect(array('action'=>'index'));
		}

		$this->setCakeCmsFlash(__('User was not deleted.'));
		$this->redirect(array('action' => 'index'));
	}

	public function edit($id = null)
	{
		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if(empty($this->data['User']['password'])){
				unset($this->data['User']['password']);
			}

			if ($this->User->save($this->request->data)) {
				$this->setCakeCmsFlash(__('The user has been saved.'), 'success');
				$this->redirect(array('action' => 'index'));
			}

			$this->setCakeCmsFlash(__('The user could not be saved. Please, try again.'));
		}
		else {
			$this->request->data = $this->User->read(null, $id);
			unset($this->request->data['User']['password']);
		}

		$roles = $this->User->roles;

		$Group = ClassRegistry::init('Group');
		$groups = $Group->find('list');

		$this->set(compact('roles','groups'));
	}

	public function index()
	{
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());

		$roles = $this->User->roles;

		$Group = ClassRegistry::init('Group');
		$groups = $Group->find('list');

		$this->set(compact('roles', 'groups'));
	}

	public function login()
	{
		//Auth Magic done in auth component login();
		if ($this->request->is('post')) {
			if (!$this->Auth->login()) {
 				$this->setCakeCmsFlash(__('Your username or password was incorrect.'));
			}
		}

		if (($user = $this->Auth->user())) {
			$this->_writeToSessionAuthPermissions($user);
			$this->redirect($this->Auth->redirect(array('plugin' => 'cake_cms', 'controller' => 'admins')));
		}

		$this->render('CakeCms/admins/home');
	}

	public function logout()
	{
		$this->Session->delete('Auth.Permissions');
		$this->Session->setFlash(__('Good-Bye'));
		$this->redirect($this->Auth->logout());
	}

	public function profile()
	{
		if (($user = $this->Auth->user()) === false) {
      $this->redirect('/');
		}

    $key = $this->_reset_password_key($user['username']);

		$this->set(compact('user', 'key'));
	}

	public function reset_password()
	{
		if (!$this->request->is('post') and !empty($this->request->query['key'])) {
			$this->_reset_password_allow_password_update($this->request->query['key']);
		}
		elseif ($this->request->is('post')) {
			if (!empty($this->request->data['User']['key']) and
							!empty($this->request->data['User']['password']) and
							!empty($this->request->data['User']['password_confirm'])) {
				$this->_reset_password_update_password($this->request->data['User']['key'], $this->request->data['User']['password'], $this->request->data['User']['password_confirm']);
			}
			else if (!empty($this->request->data['User']['username'])) {
				$this->_reset_password_send_email($this->data['User']['username']);
			}
		}
	}

	private function _reset_password_allow_password_update($key)
	{
		if (!empty($key)) {
			$cached_data	= json_decode(Cache::read($key, 'password_reset'));
			$username	= $cached_data->username;
			$request_ts	= $cached_data->request_ts;
			$current_ts   	= time();

			if (($current_ts - $request_ts)/60 > 30) {
				$this->setCakeCmsFlash(__('This link has expired. Please request password reset again.'));
				return;
			}
			else {
				$this->set(compact('key'));
				$this->render('Users/reset_password_update');
			}
		}
	}

  private function _reset_password_key($username)
  {
    $request_ts = time();
    $key = md5($username.$request_ts);

    Cache::write($key,
                json_encode(array('username'		=> $username,
                                  'request_ts'	=> $request_ts)),
                'password_reset');

    return $key;
  }

	private function _reset_password_send_email($username)
	{
		$result = $this->User->find('first', array('conditions' => array('User.username' => $username)));

		if (!empty($result)) {
			$email_to = $result['User']['email'];
      $key =  $this->_reset_password_key($username);

			App::uses('CakeEmail', 'Network/Email');
			$Email = new CakeEmail();
			$Email->template('CakeCms.reset_password_key', 'CakeCms.default');
			$Email->emailFormat('html');
			$Email->viewVars(array(
				'host'		=> $_SERVER['HTTP_HOST'],
				'username'	=> $result['User']['username'],
				'first_name'	=> $result['User']['first_name'],
				'last_name'	=> $result['User']['last_name'],
				'key'		=> $key));
			$Email->from(array(Configure::read('CakeCms.noreply_email_address') => Configure::read('CakeCms.noreply_name')))
							->to($email_to)
							->subject(__('Reset Password Key'));

			if ($Email->send()) {
				$this->setCakeCmsFlash(__('Your account has been found and an email with password reset instructions has been sent to the email address, %s', $result['User']['email']), 'success');
				$this->redirect('/reset_password');
			}
			else {
				$this->setCakeCmsFlash(__('Internal server error, please try again later.'));
				$this->redirect('/reset_password');
			}
		}
		else {
			$this->setCakeCmsFlash(__('The username does not match any accounts in our system, please try again later.'));
		}

		return false;
	}

	private function _reset_password_update_password($key, $password, $password_confirm)
	{
		if (!empty($key)) {
			$cached_data	= json_decode(Cache::read($key, 'password_reset'));
			$username	= $cached_data->username;
			$request_ts	= $cached_data->request_ts;
			$current_ts   	= time();

			if (($current_ts - $request_ts)/60 > 30) {
				$this->setCakeCmsFlash(__('This link has expired. Please request password reset again.'));
				return;
			}
			else {
				$data = $this->User->find('first', array('conditions' => array('User.username' => $username)));

				if (empty($data)) {
					$this->setCakeCmsFlash(__('We could not find the associated username in our system.'));
					$this->redirect('/');
				}

				$data['User']['password'] = $password;
				$data['User']['password_confirm'] = $password_confirm;

				if ($this->User->save($data)) {
					Cache::delete($key, 'password_reset');
					$this->setCakeCmsFlash(__('Your password has been successfully reset.'), 'success');
					$this->redirect('/');
				}
				else {
					$this->setCakeCmsFlash(__('Your password was not reset. Please try again.'));
				}
			}
		}
	}

	public function recover_username()
	{
		if ($this->request->is('post') and !empty($this->request->data['User']['email'])) {
			$result = $this->User->find('first', array('conditions' => array('User.email' => $this->request->data['User']['email'])));

			if (!empty($result)) {

				$email_to = $result['User']['email'];

				App::uses('CakeEmail', 'Network/Email');
				$Email = new CakeEmail();
				$Email->template('CakeCms.recovered_username', 'CakeCms.default');
				$Email->emailFormat('html');
				$Email->viewVars(array(
					'username'		=> $result['User']['username'],
					'first_name'	=> $result['User']['first_name'],
					'last_name'		=> $result['User']['last_name']));
				$Email->from(array(Configure::read('CakeCms.noreply_email_address') => Configure::read('CakeCms.noreply_name')))
								->to($email_to)
								->subject(__('Username Recovered'));

				if ($Email->send()) {
					$this->setCakeCmsFlash(__('Your account has been found and an email with your username has been sent to the email address, %s', $this->data['User']['email']), 'success');
					$this->redirect('/recover_username');
				}
				else {
					$this->setCakeCmsFlash(__('Internal server error, please try again later.'));
					$this->redirect('/recover_username');
				}
			}
			else {
				$this->setCakeCmsFlash(__('The email address does not match any accounts in our system, please try again later.'));
			}
		}
	}

	public function sign_in()
	{
		//Auth Magic done in auth component login();
		if ($this->request->is('post')) {
			if (!$this->Auth->login()) {
 				$this->setCakeCmsFlash(__('Your username or password was incorrect.'));
			}
		}

		if (($user = $this->Auth->user())) {
			$this->_writeToSessionAuthPermissions($user);
			$this->redirect($this->Auth->redirect($this->referer()));
		}
	}

	public function sign_out()
	{
		$this->Session->delete('Auth.Permissions');
		$this->Session->setFlash(__('Good-Bye'));
		$this->redirect($this->Auth->logout());
	}

	public function sign_up()
	{
		if (!empty($this->request->data)) {
			$request_data = $this->request->data;
			$request_data['User']['role'] = 'user';
			$request_data['User']['group_id'] = 3; // User Group

			$exists = $this->User->find('count', array('conditions' => array('User.username' => $request_data['User']['username'])));

			if (!$exists) {
				$this->User->create();

				if ($this->User->save($request_data)) {
					App::uses('CakeEmail', 'Network/Email');
					$Email = new CakeEmail();
					$Email->template('CakeCms.sign_up_confirmation', 'CakeCms.default');
					$Email->emailFormat('html');
					$Email->viewVars(array(
						'username'		=> $request_data['User']['username'],
						'first_name'	=> $request_data['User']['first_name'],
						'last_name'		=> $request_data['User']['last_name']));
					$Email->from(array(Configure::read('CakeCms.noreply_email_address') => Configure::read('CakeCms.noreply_name')))
									->to($request_data['User']['email'])
									->subject(__('Sign Up Confirmation'));

					$email_msg = '';
					if ($Email->send()) {
						$email_msg = __('A confirmation email is being sent to the address you provided.');
					}

					$this->setCakeCmsFlash(__('Your account has been created. %s', $email_msg), 'success');
					$this->redirect('/');
				}

				$this->setCakeCmsFlash(__('Your account could not be created. Please, try again.'));
			}
			else {
				$this->setCakeCmsFlash(__('An account can not be created for that username. Please, try again.'));
			}
		}
	}

	public function view($id = null)
	{
		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$this->set('user', $this->User->read(null, $id));
	}
}

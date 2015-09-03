<?php
//namespace CakeCms\Controller;

App::uses('CakeCmsAppController', 'CakeCms.Controller');
class GroupsController extends CakeCmsAppController
{
	public $name = 'Groups';

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow();
	}

	public function add()
	{
		App::import('Component', 'Acl');

		$Acl = new AclComponent(); // allow/check/deny

		if (!empty($this->data)) {
			if (!empty($this->data['Acls'])) $acls = $this->data['Acls'];

			// first save the group name
			$this->Group->create();
			if ($this->Group->save($this->data)) {
				// second save the group acls
				if (!empty($acls)) {
					$this->_set_acls($acls);
				}
				$this->Session->setFlash(__('The group has been saved.', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
			}
		}

		$acls = $this->_get_acls_array(true);

		$this->set('acls', $acls);
	}

	public function delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for group', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Group->delete($id)) {
			$this->Session->setFlash(__('Group deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Group was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	private function _get_acls_array($defaultFalse = false)
	{
		App::import('Component', 'Acl');
		App::import('Core', 'File');

		$Acl						 = new AclComponent(); // allow/check/deny
		$controllers		 = App::objects('controller');
		$acls						= array();
		$exclude_ctrls	 = array('App', 'Acls', 'Acos', 'Groups', 'Pages');
		$exclude_actions = get_class_methods('AppController');
		foreach ($controllers as $a => $controller) {
			if (!in_array($controller, $exclude_ctrls)) {
				App::import('Controller', $controller);
				$ctrlclass				 = $controller . 'Controller';
				$actions					 = get_class_methods($ctrlclass);
				$acls[$controller] = array();
				foreach ($actions as $b => $action) {
					if (in_array($action, $exclude_actions)) {
						continue;
					}
					elseif ($this->_is_class_method('public', $action, $controller.'Controller')) {
						if ($defaultFalse) {
							$acls[$controller][$action] = false;
						}
						else {
							$acoNodePath = $Acl->Aco->node("controllers/$controller/$action");
							if (!empty($acoNodePath)) {
								$acls[$controller][$action] = $Acl->check($this->Group, "controllers/$controller/$action");
							}
							else {
								$acls[$controller][$action] = false;
							}
						}
					}
				}
			}
		}
		ksort($acls);

		return $acls;
	}

	public function edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'index'));
		}

		if (!empty($this->data)) {
			if (!empty($this->data['Acls'])) $acls = $this->data['Acls'];

			// first save the group name
			if ($this->Group->save($this->data)) {
				// second save the group acls
				if (!empty($acls)) {
					$this->_set_acls($acls);
				}
				$this->Session->setFlash(__('The group has been saved.', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
			}
		}
		else {
			$this->data = $this->Group->read(null, $id);
		}

		$acls = $this->_get_acls_array();

		$this->set('acls', $acls);
	}

	public function index()
	{
		$this->Group->recursive = 0;
		$groups = $this->paginate();

		$this->set(compact('groups'));
	}

	private function _is_class_method($type="public", $method, $class)
	{
		$refl = new ReflectionMethod($class, $method);
		switch($type) {
			case "static":
				return $refl->isStatic();
				break;
			case "public":
				return $refl->isPublic();
				break;
			case "private":
				return $refl->isPrivate();
				break;
			case "protected":
				return $refl->isProtected();
				break;
		}
	}

	private function _set_acls($acls)
	{
		App::import('Component', 'Acl');

		$Acl = new AclComponent(); // allow/check/deny

		foreach ($acls as $controller => $actions) {
			foreach ($actions as $action => $checked) {
				$Aco =& $Acl->Aco;
				$acoNodePath = $Aco->node("controllers/$controller/$action");
				if (empty($acoNodePath)) {
					$root = $Aco->node('controllers');
					$controllerNode = $Aco->node("controllers/$controller");
					if (empty($controllerNode)){
						$Aco->create(array('parent_id' => $root[0]['Aco']['id'], 'model' => null, 'alias' => $controller));
						$controllerNode = $Aco->save();
						$controllerNode['Aco']['id'] = $Aco->id;
						$Acl->allow($this->Group, "controllers/$controller");
					}
					else {
						$controllerNode = $controllerNode[0];
					}
					$Aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $action));
					$Aco->save();
					$Acl->deny($this->Group, "controllers/$controller/$action");
				}
				if ($checked != $Acl->check($this->Group, "controllers/$controller/$action")) {
					// The current setting doesn't match the submitted setting, change accordingly.
					if ($checked) {
						$Acl->allow($this->Group, "controllers/$controller/$action");
					}
					else {
						$Acl->deny($this->Group, "controllers/$controller/$action");
					}
				}
			}
		}
	}

	public function view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('group', $this->Group->read(null, $id));

		$this->set('users', $this->paginate('User', array('User.group_id' => $id)));
	}
}

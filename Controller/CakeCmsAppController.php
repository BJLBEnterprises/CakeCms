<?php
//namespace CakeCms\Controller;

function advDebug($var = false, $showHtml = false, $showFrom = true) {
  if ($showHtml = 'tablize') {
    if ($showFrom) {
      $calledFrom = debug_backtrace();
      echo '<strong>' . substr(str_replace(ROOT, '', $calledFrom[0]['file']), 1) . '</strong>';
      echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
    }
    echo "\n<pre class=\"cake-debug\">\n";
    tablize($var);
    echo $var . "\n</pre>\n";
  }
  else {
    debug($var, $showHtml, $showFrom);
  }
}

function tablize($array) {
  if(is_object($array)){
    $class_name = get_class($array);
    $new_array[$class_name] = get_class_vars( get_class($array) );
    $array = $new_array;
  }
?>
<table>
  <thead>
    <tr>
      <td>Key</td>
      <td>Value</td>
    </tr>
  </thead>
  <tbody>
    <?php foreach($array as $key => $value): ?>
    <tr>
      <td><?php echo $key; ?></td>
      <td><?php
      if(is_array($value) || is_object($value)){
        tablize($value);
      }
      else{
        echo $value;
      }
      ?></td>
    </td>
    <?php endforeach; ?>
  </tbody>
</table>
<?php
}

App::uses('AppController', 'Controller');

class CakeCmsAppController extends AppController
{
  public $components	= array('Acl',
															'Auth' => array(
																'authorize' => array(
																	'Actions' => array('actionPath' => 'controllers/')
																)
															),
															'RequestHandler',
															'Security',
															'Session');

  public $helpers = array('Form',
													'Html',
													'Number',
													//'Permissions',
													'Session',
													'Js' => array('Jquery'));

	public function afterFilter()
	{
		parent::afterFilter();

		if (Configure::read('debug') > 1) {
			$this->end_time = time();
			CakeLog::write('slow_action',$this->params['controller'].'/'.$this->params['action'].' ('.__LINE__.') : ended='. date('Y-m-d H:i:s',$this->end_time) .' ('. ($this->end_time-$this->start_time) .' seconds)');
		}
	}


	public function beforeFilter()
	{
		if (Configure::read('debug') > 1) {
			$this->start_time = time();
			CakeLog::write('slow_action',$this->params['controller'].'/'.$this->params['action'].' ('.__LINE__.') : started='. date('Y-m-d H:i:s',$this->start_time));
		}

		$r_plugin = $this->request->params['plugin'];
		$r_controller = $this->request->params['controller'];
		$r_action = $this->request->params['action'];
		//Configure AuthComponent
		$this->Auth->loginAction = array(
				'plugin' => 'cake_cms',
				'controller' => 'admins'
		);
		$this->Auth->logoutRedirect = array(
				'plugin' => '',
				'controller' => 'pages'
		);
		$this->Auth->loginRedirect = array(
				'plugin' => 'cake_cms',
				'controller' => 'admins'
		);
		$this->Auth->actionPath = 'controllers/';
		$this->Auth->allowedActions = array('display');

		if($this->Session->read('Auth.User.group_id') == 1){
			$this->Auth->allow(array('build_acl', 'initDB'));
		}
  }


  public function beforeRender()
	{
    $humanizedViewPath = Inflector::humanize($this->viewPath);

		if(empty($this->viewVars['title_for_layout']) and !empty( $humanizedViewPath )){
      $this->viewVars['title_for_layout'] = $humanizedViewPath;
    }
  }


	protected function isAuthorized($user)
	{
		// Admin can access every action
		if (isset($user['role']) && $user['role'] === 'admin') {
				return true;
		}

		// Default deny
		return false;
	}

	protected function setCakeCmsFlash($msg, $msg_class = 'default')
	{
		$this->Session->setFlash($msg, 'cake_cms_flash', array('class' => $msg_class, 'plugin' => 'CakeCms'));
	}
}

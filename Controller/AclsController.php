<?php
//namespace CakeCms\Controller;

/**
 * @todo Remove if nolonger needed.
 */
App::uses('CakeCmsAppController', 'CakeCms.Controller');
class AclsController extends CakeCmsAppController
{
	public $name = 'Acls';

	public $uses = array();

	public function beforeFilter() {
		parent::beforeFilter();
		if($this->Session->read('Auth.User.group_id') == 1){
			$this->Auth->allow(array('*'));
		}
	}
}
?>

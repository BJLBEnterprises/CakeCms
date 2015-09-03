<?php
//namespace CakeCms\Controller;

App::uses('CakeCmsAppController', 'CakeCms.Controller');

class AdminsController extends CakeCmsAppController
{
	public $name = 'Admins';

	/**
	 * Default helper
	 *
	 * @var array
	 * @access public
	 */
	public $helpers = array('Html', 'Session');

	public function beforeFilter()
	{
		parent::beforeFilter();
		if ($this->Session->read('Auth.User.role') === 'admin') {
			$this->Auth->allow();
		}
	}

	/**
	 * Displays a view
	 *
	 * @param mixed What page to display
	 * @access public
	 */
	public function display()
	{
		$path = func_get_args();

		$count = count($path);

		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) $page = $path[0];

		if (!empty($path[1])) $subpage = $path[1];

		if (!empty($path[$count - 1])) $title_for_layout = Inflector::humanize($path[$count - 1]);

		$this->set(compact('page', 'subpage', 'title_for_layout'));

		try {
			$this->render(implode('/', $path));
		} catch (MissingViewException $e) {
			if (Configure::read('debug')) {
				throw $e;
			}
			throw new NotFoundException();
		}
	}


	public function index()
	{

	}
}

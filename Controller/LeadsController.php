<?php
//namespace CakeCms\Controller;

App::uses('CakeCmsAppController', 'CakeCms.Controller');
/**
 * Leads Controller
 *
 */
class LeadsController extends CakeCmsAppController
{
	public $name = "Leads";


	public function index()
	{
		$this->Lead->recursive = 0;
		$leads = $this->paginate();

		$this->set(compact('leads'));
	}

	public function add()
	{

	}

	public function delete($id)
	{

	}

	public function edit($id)
	{

	}

	public function view($id)
	{

	}
}

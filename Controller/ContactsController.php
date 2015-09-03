<?php
//namespace CakeCms\Controller;

App::uses('CakeCmsAppController', 'CakeCms.Controller');
/**
 * Contacts Controller
 *
 */
class ContactsController extends CakeCmsAppController
{
	public $name = "Contacts";


	public function index()
	{
		$this->Contact->recursive = 0;
		$contacts = $this->paginate();

		$this->set(compact('contacts'));
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

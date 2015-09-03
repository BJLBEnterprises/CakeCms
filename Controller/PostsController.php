<?php
//namespace CakeCms\Controller;

App::uses('CakeCmsAppController', 'CakeCms.Controller');
/**
 * Posts Controller
 *
 */
class PostsController extends CakeCmsAppController
{
	public $name = "Posts";


	public function index()
	{
		$this->Post->recursive = 0;
		$posts = $this->paginate();

		$this->set(compact('posts'));
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

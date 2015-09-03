<?php
//namespace CakeCms\Lib;

App::uses('Controller', 'Controller');
App::uses('Model', 'Model');
App::uses('Core', 'Folder');

class CakeCmsExtras extends Object
{
	/**
	 * Contains instance of Folder
	 *
	 * @var Folder
	 * $access public
	 */
	public $Folder;

	/**
	 * Contains arguments parsed from the command line.
	 *
	 * @var array
	 * @access public
	 */
	public $args;

  /**
   * Contains database source to use
   *
   * @var string
   * @access public
   */
	public $dataSource = 'default';


	public function startup()
	{
		$this->Folder  = new Folder();
	}


  public function getControllerList()
	{
    $this->Folder->cd(APP.'controllers');

		$files = $this->Folder->read();

		if(is_array($files) && count($files) == 2){
      $files = $files[1];
    }

		$controllers = array();

		foreach($files as $file){
      $controller = Inflector::camelize( substr($file, 0, strpos($file, '_controller.php')) );

			$controllers[$controller] = $controller;
    }

		return $controllers;
  }


  public function getModelList()
	{
    $this->Folder->cd(APP.'models');

		$files = $this->Folder->read();

		if(is_array($files) && count($files) == 2){
      $files = $files[1];
    }

		$models = array();

    foreach($files as $file){
      $model = Inflector::camelize( substr($file, 0, strpos($file, '.php')) );
      $models[ $model ] = $model;
    }
    return $models;
  }

	public function getObjectMethods($object_name)
	{
		$Object = new $object_name();

		return get_class_methods($object_name);
	}
}
?>

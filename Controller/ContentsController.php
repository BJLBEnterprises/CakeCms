<?php
//namespace CakeCms\Controller;

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @link http://book.cakephp.org/view/958/The-Pages-Controller
 */
App::uses('CakeCmsAppController', 'CakeCms.Controller');
class ContentsController extends CakeCmsAppController
{

	/**
	 * Controller name
	 *
	 * @var string
	 * @access public
	 */
	public $name = 'Contents';

	/**
	 * This controller does use model
	 *
	 * @var array
	 * @access public
	 */
	public $uses = array('CakeCms.Page');

	public function beforeFilter()
	{
		parent::beforeFilter();

    if ($this->Session->read('Auth.User.role') === 'admin') {
			$this->Auth->allow();
		}
//    $this->Security->unlockedActions = array();
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
		$this->Page->recursive = 0;
		$pages = $this->paginate();

		$this->set(compact('pages'));
	}

	public function add()
	{
		if ($this->request->is('post') or $this->request->is('put')) {
      $this->request->data['Content']['version'] = 1;

      $save_page_results = $this->_savePage(array('Page' => $this->request->data['Content']));

			if (!empty($save_page_results)) {
        $publish_msg = '';

        if (!isset($this->request->data['draft']) or !$this->request->data['draft']) { // Publish
          if ($this->_publish($save_page_results)) {
            $publish_msg = __('Page was published successfully.');
          }
          else {
            $publish_msg = __('Page was not published.');
          }
        }

        $this->setCakeCmsFlash(__('Page saved successfully. %1s', $publish_msg), 'success');
        $this->redirect(array('plugin' => 'cake_cms', 'controller' => 'contents', 'action' => 'index'));
      }
      else {
        $this->setCakeCmsFlash(__('Page was not saved.'));
      }
		}
	}

	public function delete($id)
	{

	}

	public function edit($id)
	{
    if ($this->request->is('post') or $this->request->is('put')) {
      $this->request->data['Content']['id'] = $id;
      $this->request->data['Content']['version']++;

      $save_page_results = $this->_savePage(array('Page' => $this->request->data['Content']));

			if (!empty($save_page_results)) {
        $publish_msg = '';

        if (!isset($this->request->data['draft']) or !$this->request->data['draft']) { // Publish
          if ($this->_publish($save_page_results)) {
            $publish_msg = __('%1s page was published successfully.', $save_page_results['Page']['title']);
          }
          else {
            $publish_msg = __('%1s page was not published.', $save_page_results['Page']['title']);
          }
        }

        $this->setCakeCmsFlash(__('Page saved successfully. %1s', $publish_msg), 'success');
      }
      else {
        $this->setCakeCmsFlash(__('Page was not saved.'));
      }
		}

    $this->request->data = $page = $this->Page->findById($id);

    $this->set(compact('page'));
	}

  public function post($id) {
    $page_data = $this->Page->findById($id);

    if ($this->_publish($page_data)) {
      $this->setCakeCmsFlash(__('%1s page was published successfully.', $page_data['Page']['title']), 'success');
    }
    else {
      $this->setCakeCmsFlash(__('%1s page was not published.', $page_data['Page']['title']));
    }

    $this->redirect(array('plugin' => 'cake_cms', 'controller' => 'contents', 'action' => 'index'));
  }

  private function _publish($page_data) {
    App::uses('Folder', 'Utility');
    App::uses('File', 'Utility');

    $dir = new Folder(APP.'View'.DS.'Pages');
    $files = $dir->find('.*\.ctp');

    $contents = '<?php
$pageTitle = __(\''. $page_data['Page']['title'] .'\');
$this->Html->meta(\'keywords\', \''. $page_data['Page']['keywords'] .'\', array(\'inline\' => false));
$this->Html->meta(\'description\', \''. $page_data['Page']['description'] .'\', array(\'inline\' => false));
$this->Html->addCrumb($pageTitle);
?>
<div class="row">
  <div class="col-md-12">
    '. $page_data['Page']['content'] .'
  </div>
</div>
';

    $file = new File($dir->pwd().DS.$page_data['Page']['action'].'.ctp', true, 0644);

    if ($file->write($contents)) {
      $this->Page->id = $page_data['Page']['id'];
      $this->Page->save(array('Page' => array('published' => date('Y-m-d H:m:s'))));

      return true;
    }

    return false;
  }

  private function _savePage($page_data) {
    if (empty($page_data['Page']['id'])) $this->Page->create();

    return $this->Page->save($page_data);
  }


  public function unpost($id) {
    $page_data = $this->Page->findById($id);

    if (empty($page_data)) {
      $this->setCakeCmsFlash(__('Page # %s was not found.', $id));
    }
    else {
      $this->_unpublish($page_data);
    }

    $this->redirect(array('plugin' => 'cake_cms', 'controller' => 'contents', 'action' => 'index'));
  }


  private function _unpublish($page_data) {
    $this->Page->id = $page_data['Page']['id'];

    if (!$this->Page->save(array('Page' => array('published' => null)))) {
      $this->setCakeCmsFlash(__('%1s page was not unpublished.', $page_data['Page']['title']));
      return false;
    }

    $this->setCakeCmsFlash(__('%1s page was unpublished.', $page_data['Page']['title']));
    return true;
  }


	public function view($id) {
    $page = $this->request->data = $this->Page->findById($id);

    $this->set(compact('page'));
	}
}

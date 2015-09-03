<?php
//namespace CakeCms\Model;

App::uses('CakeCmsAppModel', 'CakeCms.Model');
/**
 * Page Model
 *
 */
class Page extends CakeCmsAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'action' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'title' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'version' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);


  public function listPublishedPages() {
    return $this->find('list', array('conditions' => array('Page.published NOT' => null),
                                      'fields' => array('Page.id', 'Page.action')));
  }
}

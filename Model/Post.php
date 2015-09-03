<?php
//namespace CakeCms\Model;

App::uses('CakeCmsAppModel', 'CakeCms.Model');
/**
 * Post Model
 *
 * @property Author $Author
 */
class Post extends CakeCmsAppModel
{
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Author' => array(
			'className' => 'User',
			'foreignKey' => 'author_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

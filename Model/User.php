<?php
//namespace CakeCms\Model;

App::uses('CakeCmsAppModel', 'CakeCms.Model');
App::uses('AuthComponent', 'Controller/Component');
/**
 * User Model
 *
 * @property Group $Group
 */
class User extends CakeCmsAppModel
{
	public $name = 'User';

	public $actsAs = array(
		'Acl' => array('type' => 'requester')
	);

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'username' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'unique'	=> array(
				'rule'				=> 'isUnique',
				'required'		=> 'create',
				'message'			=> 'Username must be unique'
			),
			'alphanumeric'	=> array(
				'rule'		=> 'alphanumeric',
				'message'	=> 'Username must be alphanumeric'
			),
			'minLength'	=> array(
				'rule'		=> array('minLength', 4),
				'message' => 'Username must have a minimum length of 4 characters',
			)
		),
		'password' => array(
			'minLength'	=> array(
				'rule'			=> array('minLength', 4),
				'required'	=> array('create', 'update'),
				'message'		=> 'Password must have a minimum length of 4 characters',
			)
		),
		'password_confirm' => array(
			'minLength'	=> array(
				'rule'			=> array('confirmPassword'),
				'required'	=> array('create', 'update'),
				'message'		=> 'Passwords must match',
			)
		),
		'first_name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'First name is not allowed to be empty',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'last_name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Last name is not allowed to be left empty.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'rule' => array('email', true),
			'message' => 'Please supply a valid email address.'
    ),
		'role' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'group_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
//			'conditions' => '',
//			'fields' => '',
//			'order' => ''
		)
	);

	public $virtualFields = array(
		'name' => "User.first_name || ' ' || User.last_name"
	);


	public $roles = array(
			'admin'		=> 'Admin',
			'manager'	=> 'Manager',
			'author'	=> 'Author',
			'user'		=> 'User'
	);


	public function beforeSave($options = array())
	{
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password(
					$this->data[$this->alias]['password']
			);
		}
		return true;
	}


	public function bindNode($user)
	{
		return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
	}

	public function confirmPassword()
	{
		return $this->data['User']['password'] == $this->data['User']['password_confirm'];
	}


	public function parentNode()
	{
		if (!$this->id && empty($this->data)) {
			return null;
		}
		if (isset($this->data['User']['group_id'])) {
			$groupId = $this->data['User']['group_id'];
		} else {
			$groupId = $this->field('group_id');
		}
		if (!$groupId) {
			return null;
		} else {
			return array('Group' => array('id' => $groupId));
		}
	}
}

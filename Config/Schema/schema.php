<?php
//namespace CakeCms\Config\Schema;

class CakeCmSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $accounts = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'alias' => array('type' => 'string', 'length' => 255, 'null' => false),
		'number' => array('type' => 'integer', 'null' => false),
		'type' => array('type' => 'string', 'length' => 255, 'null' => false),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
      'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array(
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    )
	);

	public $acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'model' => array('type' => 'string', 'length' => 255, 'null' => true),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'alias' => array('type' => 'string', 'length' => 255, 'null' => true),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'indexes' => array(
      'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array(
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    )
	);

	public $aros = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'model' => array('type' => 'string', 'length' => 255, 'null' => true),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => null),
		'alias' => array('type' => 'string', 'length' => 255, 'null' => true),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null),
		'indexes' => array(
      'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array(
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    )
	);

	public $aros_acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'aro_id' => array('type' => 'integer', 'null' => false),
		'aco_id' => array('type' => 'integer', 'null' => false),
		'_create' => array('type' => 'integer', 'null' => false),
		'_read' => array('type' => 'integer', 'null' => false),
		'_update' => array('type' => 'integer', 'null' => false),
		'_delete' => array('type' => 'integer', 'null' => false),
		'indexes' => array(
      'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array(
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    )
	);

	public $classifications = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'model' => array('type' => 'string', 'length' => 255, 'null' => true),
		'foreign_key' => array('type' => 'integer', 'null' => true),
		'alias' => array('type' => 'string', 'length' => 255, 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
      'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array(
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    )
	);

	public $contacts = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'first_name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'last_name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'email' => array('type' => 'string', 'length' => 255, 'null' => false),
		'phone_1' => array('type' => 'string', 'length' => 255, 'null' => true),
		'phone_1_type' => array('type' => 'string', 'length' => 255, 'null' => true),
		'phone_2' => array('type' => 'string', 'length' => 255, 'null' => true),
		'phone_2_type' => array('type' => 'string', 'length' => 255, 'null' => true),
		'phone_3' => array('type' => 'string', 'length' => 255, 'null' => true),
		'phone_3_type' => array('type' => 'string', 'length' => 255, 'null' => true),
		'social_media_1' => array('type' => 'string', 'length' => 255, 'null' => true),
		'social_media_1_type' => array('type' => 'string', 'length' => 255, 'null' => true),
		'social_media_2' => array('type' => 'string', 'length' => 255, 'null' => true),
		'social_media_2_type' => array('type' => 'string', 'length' => 255, 'null' => true),
		'social_media_3' => array('type' => 'string', 'length' => 255, 'null' => true),
		'social_media_3_type' => array('type' => 'string', 'length' => 255, 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
      'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array(
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    )
	);

	public $groups = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
      'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array(
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    )
	);

	public $i18n = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'locale' => array('type' => 'string', 'length' => 255, 'null' => false),
		'model' => array('type' => 'string', 'length' => 255, 'null' => false),
		'foreign_key' => array('type' => 'integer', 'null' => false),
		'field' => array('type' => 'string', 'length' => 255, 'null' => false),
		'content' => array('type' => 'text', 'null' => true),
		'indexes' => array(
      'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'field' => array('column' => 'field', 'unique' => 1),
			'row_id' => array('column' => 'foreign_key', 'unique' => 1),
			'model' => array('column' => 'model', 'unique' => 1),
			'local' => array('column' => 'locale', 'unique' => 1)
		),
		'tableParameters' => array(
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    )
	);

	public $leads = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'first_name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'last_name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'email' => array('type' => 'string', 'length' => 255, 'null' => false),
		'phone_1' => array('type' => 'string', 'length' => 255, 'null' => true),
		'phone_1_type' => array('type' => 'string', 'length' => 255, 'null' => true),
		'phone_2' => array('type' => 'string', 'length' => 255, 'null' => true),
		'phone_2_type' => array('type' => 'string', 'length' => 255, 'null' => true),
		'phone_3' => array('type' => 'string', 'length' => 255, 'null' => true),
		'phone_3_type' => array('type' => 'string', 'length' => 255, 'null' => true),
		'social_media_1' => array('type' => 'string', 'length' => 255, 'null' => true),
		'social_media_1_type' => array('type' => 'string', 'length' => 255, 'null' => true),
		'social_media_2' => array('type' => 'string', 'length' => 255, 'null' => true),
		'social_media_2_type' => array('type' => 'string', 'length' => 255, 'null' => true),
		'social_media_3' => array('type' => 'string', 'length' => 255, 'null' => true),
		'social_media_3_type' => array('type' => 'string', 'length' => 255, 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
      'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array(
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    )
	);

	public $pages = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'action' => array('type' => 'string', 'length' => 255, 'null' => false),
		'title' => array('type' => 'string', 'length' => 255, 'null' => false),
		'content' => array('type' => 'text', 'null' => true),
		'description' => array('type' => 'string', 'length' => 255, 'null' => true),
		'keywords' => array('type' => 'string', 'length' => 255, 'null' => true),
		'version' => array('type' => 'integer', 'null' => false),
		'published' => array('type' => 'datetime', 'null' => true),
		'purge' => array('type' => 'integer', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
      'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array(
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    )
	);

	public $posts = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'author_id' => array('type' => 'integer', 'length' => 11, 'null' => true),
		'title' => array('type' => 'string', 'length' => 255, 'null' => true),
		'body' => array('type' => 'string', 'length' => 255, 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
      'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array(
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    )
	);

	public $transactions = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'date' => array('type' => 'datetime', 'null' => true),
		'description' => array('type' => 'string', 'length' => 255, 'null' => true),
		'account_id' => array('type' => 'integer', 'null' => false),
		'classification_id' => array('type' => 'integer', 'null' => false),
		'debit' => array('type' => 'float', 'length' => 11, 'null' => true),
		'credit' => array('type' => 'float', 'length' => 11, 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
      'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array(
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    )
	);

	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'username' => array('type' => 'string', 'length' => 255, 'null' => false),
		'password' => array('type' => 'string', 'length' => 255, 'null' => false),
		'first_name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'last_name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'email' => array('type' => 'string', 'length' => 255, 'null' => false),
		'role' => array('type' => 'string', 'length' => 255, 'null' => false),
		'group_id' => array('type' => 'integer', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
      'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array(
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    )
	);

}

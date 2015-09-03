<?php
//namespace CakeCms\Console\Command\Task;

App::uses('AppShell', 'Console/Command');
App::uses('AclExtras', 'CakeCms.Lib');

class AclTask extends AppShell
{
	public $uses = array('Aco', 'CakeCms.Group', 'CakeCms.User');

	public function execute()
	{
		$args = func_get_args();
		$subTaskToRun = (isset($args[0])) ? $args[0] : null;
		switch ($subTaskToRun) {
			case 'create':
				$this->_create();
				break;
			case 'initDb':
				$this->_initDb();
				break;
			case 'recover':
				$this->_recover();
				break;
			case 'update':
				$this->_update();
				break;
			case 'verify':
				$this->_verify();
				break;
			default:
				break;
		}
	}


	private function _create()
	{
		$Aco = new Aco();
		$aco_count = $Aco->find('count');
		if ($aco_count) {
			$this->out(__d('cake_console', __("$aco_count acos already created. Use the update option.")));
		}
		else {
			$this->out(__d('cake_console', __("Creating acos.")));
			$this->dispatchShell('cakeCms.acl_extras aco_sync');
			$aco_count = $Aco->find('count');
			$this->out(__d('cake_console', __("<success>$aco_count records created.</success>")));
		}

		$task = strtoupper($this->in(__d('cake_console', __("Would you like to create AROs (Access Request Objects)?")), array('Y', 'N')));
		switch ($task) {
			case 'Y':
				$this->_createAros();
				break;
			case 'N':
			default:
				break;
		}
	}

	private function _createAros()
	{
		$this->out(__d('cake_console', __("Creating AROs.")));

		$groups_list = $this->Group->find('list');

		// If we have the default 3 groups 'administrator', 'manager', and 'user'
		if (count($groups_list) == 3 &&
						in_array('administrator', array_values($groups_list)) &&
						in_array('manager', array_values($groups_list)) &&
						in_array('user', array_values($groups_list))) {
			// Ask if the default AROs are okay to generate.
			$task = strtoupper($this->in(__d('cake_console', __("Are the 'default' AROs okay to generate?")), array('Y', 'N')));
			if ($task == 'Y') {
				// Generate on 'Y'.
				$this->_createDefaultAros($groups_list);
			}
			else {
				$this->_createCustomAros($groups_list);
			}
		}
			// Else loop through each group.
		else {
			$this->_createCustomAros($groups_list);
		}
	}


	private function _createCustomAros($groups_list)
	{
		$AclExtras = new AclExtras();
		$AclExtras->startup();
		$AclExtras->Shell = $this;
		$Acl = $AclExtras->Acl;
		foreach ($groups_list as $group_id => $group_name) {
			$this->Group->create();
			$this->Group->id = $group_id;
			$Group = $this->Group->read();
			// While looping, ask if the group should have full access.
			$full_access = strtoupper($this->in(__d('cake_console', __("Should the '%s' group be granted full access?", array($group_name)), array('Y', 'N'))));
			if ($full_access === 'Y') {
				// If yes, full access is to be given and then move on to the next group.
				$Acl->allow($Group, 'controllers');
			}
			else {
				$Acl->deny($Group, 'controllers');
				// If not, loop through each controller.
				foreach ($controllers as $controller) {
					// While looping, ask if the group should have full access to the controller.
					$full_access = strtoupper($this->in(__d('cake_console', __("Should the '%s' group be granted full access to the '%s' controller?", array($group_name, ucfirst($controller))), array('Y', 'N'))));
					if ($full_access === 'Y') {
						// If yes, full access is to be given and then move on to the next controller.
						$Acl->allow($Group, 'controllers/'. ucfirst($controller));
					}
					else {
						// If not, loop through each action.
						foreach ($actions as $action) {
							// While looping, ask if the group should have access to the action.
							$full_access = strtoupper($this->in(__d('cake_console', __("Should the '%s' group be granted access to the '%s' controller action?", array($group_name, ucfirst($controller).'::'.$action)), array('Y', 'N'))));
							if ($full_access === 'Y') {
								// If yes, access is to be given.
								$Acl->allow($Group, 'controllers/'. ucfirst($controller) .'/'. $action);
							}
						}

						$Acl->allow($Group, 'controllers/Users/logout');
					}
				}
			}
		}
	}


	private function _createCustomGroups()
	{
		$group_name = strtolower($this->in(__d('cake_console', __("What would you like to named the group?"))));

		$proceed = strtoupper($this->in(__d('cake_console', __("Creating '%s' group. Is this correct?", array($group_name))), array('Y', 'N', 'Q')));
		switch ($proceed) {
			case 'Y':
				$group = array('Group' => array('name' => $group_name));

				$this->Group->create();
				$response = $this->Group->save($group);

				if ($response) {
					$this->out(__d('cake_console', __("<success>Group '%s' has been created.</success>", array($group_name))));
				}
				else {
					$this->out(__d('cake_console', __("<failure>Group '%s' was not created.</failure>", array($group_name))));
				}
				break;
			case 'N':
				$this->_createCustomGroups();
				break;
			case 'Q':
				$this->out(__d('cake_console', __("You have choosen to quit.")));
				return $this->_stop();
		}

		$proceed = strtoupper($this->in(__d('cake_console', __("Would you like to create another group?")), array('Y', 'N', 'Q')));
		switch ($proceed) {
			case 'Y':
				$this->_createCustomGroups();
				break;
			case 'N':
				$this->out('cake_console', __("You have choosen not to create another group."));
				break;
			case 'Q':
				$this->out('cake_console', __("You have choosen to quit."));
				return $this->_stop();
		}
	}


	private function _createCustomUsers()
	{
		$this->out(__d('cake_console', __METHOD__));
	}


	private function _createDefaultAros($groups_list)
	{
		$AclExtras = new AclExtras();
		$AclExtras->startup();
		$AclExtras->Shell = $this;

		$Acl = $AclExtras->Acl;

		$controller_names = array();

		$controllers = $AclExtras->getControllerList();

		if (count($controllers)) {
			foreach ($controllers as $controller) {
				if ($controller === 'AppController') {
					continue;
				}

				$controller_names[] = rtrim($controller, 'Controller');
			}
		}

		$plugins = CakePlugin::loaded();

		if (count($plugins)) {
			foreach ($plugins as $plugin) {
				$include = strtoupper($this->in(__d('cake_console', __("Do you want to create AROs for the '%s' plugin?", array($plugin))), array('Y', 'N')));

				if ($include === 'Y') {
					$p_controllers = $AclExtras->getControllerList($plugin);

					if (count($p_controllers)) {
						foreach ($p_controllers as $p_controller) {
							if (stristr($p_controller, 'AppController')) {
								continue;
							}

							$controller_names[] = $plugin .'/' . rtrim($p_controller, 'Controller');
						}
					}
				}
			}
		}

		foreach ($groups_list as $group_id => $group_name) {
			$this->Group->create();
			$this->Group->id = $group_id;
			if ($group_name === 'administrator') {
				if (!$Acl->allow($this->Group, 'controllers')) {
					$this->out(__d('cake_console', __("<error>Missing ACL link for %s to %s</error>", array($group_name, 'controllers'))));
				}
				else {
					$this->out(__d('cake_console', __("<success>Created ARO for %s to %s </success>", array($group_name, 'controllers'))));
				}
			}

			if ($group_name === 'manager' or $group_name === 'user') {
				$Acl->deny($this->Group, 'controllers');

				if (count($controller_names)) {
					foreach ($controller_names as $controller) {
						if ($group_name === 'manager') {
							if (!$Acl->allow($this->Group, 'controllers/'. $controller)) {
								$this->out(__d('cake_console', __("<error>Missing ACL link for %s to %s</error>", array($group_name, 'controllers/'. $controller))));
							}
							else {
								$this->out(__d('cake_console', __("<success>Created ARO for %s to %s </success>", array($group_name, 'controllers/'. $controller))));
							}
						}

						if ($group_name === 'user') {
							App::import('Controller', str_replace('/', '.', $controller));
							$actions = get_class_methods(str_replace(substr($controller, 0, strpos($controller, '/')+1), '', $controller).'Controller');

							if (count($actions)) {
								foreach ($actions as $action) {
									if (in_array($action, array('add', 'edit', 'view'))) {
										if (!$Acl->allow($this->Group, 'controllers/'. $controller .'/'. $action)) {
											$this->out(__d('cake_console', __("<error>Missing ACL link for %s to %s</error>", array($group_name, 'controllers/'. $controller .'/'. $action))));
										}
										else {
											$this->out(__d('cake_console', __("<success>Created ARO for %s to %s </success>", array($group_name, 'controllers/'. $controller .'/'. $action))));
										}
									}
								}
							}
						}
					}
				}
			}

			$allow_users = array('login', 'logout', 'reset_password', 'recover_username', 'sign_up', 'view');
			if (!$Acl->allow($this->Group, 'controllers/CakeCms/Users/logout')) {
				$this->out(__d('cake_console', __("<error>Missing ACL link for %s => %s</error>", array($group_name, 'controllers/CakeCms/Users/logout'))));
			}
		}
	}


	private function _createDefaultGroups()
	{
		$default_groups = array(array('Group' => array('id' => 1,
								'name' => 'administrator')),
					array('Group' => array('id' => 2,
								'name' => 'manager')),
					array('Group' => array('id' => 3,
								'name' => 'user')));

		foreach ($default_groups as $group) {
			$this->Group->create();
			$response = $this->Group->save($group);

			if ($response) {
				$this->out(__d('cake_console', __("<success>Group '%s' created.</success>", array($group['Group']['name']))));
			}
			else {
				$this->out(__d('cake_console', __("<failure>Group '%s' was not created.</failure>", array($group['Group']['name']))));
			}
		}

		$taskToRun = strtoupper($this->in(__d('cake_console', __("Would you like to create the 'default' users?")), array('Y', 'N')));
		switch ($taskToRun) {
			case 'Y':
				$this->_createDefaultUsers();
				break;
			case 'N':
				$subTaskToRun = strtoupper($this->in(__d('cake_console', __("Would you like to create 'customer' users?")), array('Y', 'N')));
				switch ($subTaskToRun) {
					case 'Y':
						$this->_createCustomUsers();
						break;
					case 'N':
						$this->out('cake_console', __("No users are being created at this time."));
						return $this->_stop();
				}
		}
	}


	private function _createDefaultUsers()
	{
		$default_users = array(array('User' => array('id' => 1,
								'username' => 'admin',
								'password' => '',
								'first_name' => 'Admin',
								'last_name' => 'Account',
								'email' => 'admin@email.com',
								'role' => 'admin',
								'group_id' => 1)),
					array('User' => array('id' => 2,
								'username' => 'manager',
								'password' => '',
								'first_name' => 'Manager',
								'last_name' => 'Account',
								'email' => 'manager@email.com',
								'role' => 'manager',
								'group_id' => 2)),
					array('User' => array('id' => 3,
								'username' => 'user',
								'password' => '',
								'first_name' => 'User',
								'last_name' => 'Account',
								'email' => 'user@email.com',
								'role' => 'user',
								'group_id' => 3)));

		foreach ($default_users as $user) {
			$password = $this->in(__d('cake_console', __("Enter a password for the 'default' username: %s", array($user['User']['username']))));

			$user['User']['password'] = $user['User']['password_confirm'] = $password;

			$this->User->create();
			$response = $this->User->save($user);

			if ($response) {
				$this->out(__d('cake_console', __("<success>User '%s' has been created.</success>", array($user['User']['username']))));
			}
			else {
				$this->out(__d('cake_console', __("<failure>User '%s' was not created.</failure>", array($user['User']['username']))));
				$this->out(__d('cake_console', json_encode($user)));
				foreach ($this->User->validationErrors as $field => $error) {
					$this->out(__d('cake_console', __("<error>Error on '%s': %s", array($field, $error[0]))));
				}
			}
		}
	}


	private function _initDb()
	{
		// Check if creation of groups table is necessary.
		try {
			$groups = $this->Group->find('list');
			$users_count = $this->User->find('count');
			$this->out(__d('cake_console', __("There are currently %s groups.", array(count($groups)))));
		}
		catch (Exception $e) {
			// If creation is necessary, report and exit.
			$this->out(__d('cake_console', $e->getMessage()));
			$this->out(__d('cake_console', __("Run `cake schema update --plugin CakeCms`")));
			exit;
		}

		// Else, go through the process of creating the groups.
		if (isset($groups) and count($groups) > 0) {
			$subTaskToRun = strtoupper($this->in(__d('cake_console', __("Would you like to create additional groups?")), array('Y', 'N')));
			switch ($subTaskToRun) {
				case 'Y':
					$this->_createCustomGroups();
					break;
				case 'N':
					if (count($groups) === 3 &&
									$groups[1] === 'administrator' &&
									$groups[2] === 'manager' &&
									$groups[3] === 'user' &&
									$users_count === 0) {
						$_subTaskToRun = strtoupper($this->in(__d('cake_console', __("It appears you have the 'default' groups, but no users. Would you like to create the 'default' users?")), array('Y', 'N')));
						switch ($_subTaskToRun) {
							case 'Y':
								$this->_createDefaultUsers();
								break;
							case 'N':
								$__subTaskToRun = strtoupper($this->in(__d('cake_console', __("Would you like to create 'customer' users?")), array('Y', 'N')));
								switch ($__subTaskToRun) {
									case 'Y':
										$this->_createCustomUsers();
										break;
									case 'N':
										$this->out('cake_console', __("No users are being created at this time."));
										return $this->_stop();
								}
								break;
						}
					}
					return $this->_stop();
				default:
					$this->out(__d('cake_console', __("You have made an invalid selection. Please choose either 'Y' or 'N'.")));
					break;
			}
		}
		else {
			$subTaskToRun = strtoupper($this->in(__d('cake_console', __("Would you like to create the 'default' groups?")), array('Y', 'N', 'Q')));
			switch ($subTaskToRun) {
				case 'Y':
					$this->out(__d('cake_console', __("Creating the 'default' groups")));
					$this->_createDefaultGroups();
					break;
				case 'N':
					$_subTaskToRun = strtoupper($this->in(__d('cake_console', __("Would you like to create 'custom' groups?")), array('Y', 'N', 'Q')));
					if ($_subTaskToRun == 'Y') {
						$this->_createCustomGroups();
						break;
					}
				case 'Q':
					return $this->_stop();
				default:
					$this->out(__d('cake_console', 'You have made an invalid selection. Please choose a task to run by entering Y, N, or Q.'));
					break;
			}
		}
	}


	private function _recover()
	{
		$this->out(__d('cake_console', __METHOD__));
		$Aco = new Aco();
		$aco_count = $Aco->find('count');
		if ($aco_count) {
			$this->out(__d('cake_console', __("Recovering ACOs.")));
			$this->dispatchShell('cakeCms.acl_extras recover aco');
			$this->out(__d('cake_console', __("Recovering AROs.")));
			$this->dispatchShell('cakeCms.acl_extras recover aro');
		}
		else {
			$this->out(__d('cake_console', __("<failure>Can not recover until you create ACOs first.</failure>")));
		}
	}


	private function _update()
	{
		$Aco = new Aco();
		$aco_count = $Aco->find('count');
		if ($aco_count) {
			$this->out(__d('cake_console', __("Updating ACOs.")));
			$this->dispatchShell('cakeCms.acl_extras aco_update');
			$aco_count = $Aco->find('count');
			$this->out(__d('cake_console', __("<success>$aco_count ACOs updated.</success>")));

			$task = strtoupper($this->in(__d('cake_console', __("Would you like to update AROs (Access Request Objects)?")), array('Y', 'N')));
			switch ($task) {
				case 'Y':
					$this->_createAros();
					break;
				case 'N':
				default:
					break;
			}
		}
		else {
			$this->out(__d('cake_console', __("$aco_count ACOs created. Use the create option.")));
		}
	}

	private function _verify()
	{
		$this->out(__d('cake_console', __METHOD__));
		$Aco = new Aco();
		$aco_count = $Aco->find('count');
		if ($aco_count) {
			$this->out(__d('cake_console', __("Verifing ACOs.")));
			$this->dispatchShell('cakeCms.acl_extras verify aco');
			$this->out(__d('cake_console', __("Verifing AROs.")));
			$this->dispatchShell('cakeCms.acl_extras verify aro');
		}
		else {
			$this->out(__d('cake_console', __("<failure>Can not verify until you create ACOs first.</failure>")));
		}
	}
}

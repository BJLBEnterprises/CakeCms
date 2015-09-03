<?php
//namespace CakeCms\Console\Command;
Configure::write('Cache.disable', true);
Configure::write('debug', 2);

App::uses('AppShell', 'Console/Command');
class ConsoleShell extends AppShell
{
	public $tasks = array('DbConfig', 'Project', 'CakeCms.Acl');

	public function main()
	{
		if (!is_dir($this->DbConfig->path)) {
			$path = $this->Project->execute();
			if (!empty($path)) {
				$this->DbConfig->path = $path . 'Config' . DS;
			} else {
				return false;
			}
		}

		$this->stdout->styles('success', array('text' => 'green'));
		$this->stdout->styles('failure', array('text' => 'red'));

    $password_reset_dir = APP.'tmp'.DS.'cache'.DS.'password_reset';
    $password_reset_perms = 0744;

    if (!is_dir($password_reset_dir)) {
      if (mkdir($password_reset_dir, $password_reset_perms)) {
        $this->out(__d('cake_console', __('Password reset cache directory created.')));
      }
      else {
        $this->out(__d('cake_console', __("Password reset cache directory was not created. You will need to manually execute 'mkdir -m %1s %2s'.", array($password_reset_perms, $password_reset_dir))));
      }
    }

		if (!config('database')) {
			$this->out(__d('cake_console', __('Your database configuration was not found. Take a moment to create one.')));
			$this->args = null;
			return $this->DbConfig->execute();
		}

    $aclDatabase = $this->DbConfig->getConfig();
    Configure::write('Acl.database', $aclDatabase);

		$this->out(__d('cake_console', __('CakeCms Console Shell')));
		$this->hr();
    $this->out(__d('cake_console', __('Db Config: %s', Configure::read('Acl.database'))));
		$this->hr();
		$this->out(__d('cake_console', __('[I]nitialize the Db ACL tables')));
		$this->out(__d('cake_console', __('[C]reate ACOs (Access Control Objects)')));
		$this->out(__d('cake_console', __('[U]pdate ACOs (Access Control Objects)')));
		$this->out(__d('cake_console', __('[R]ecover ACOs (Access Control Objects)')));
		$this->out(__d('cake_console', __('[V]erify ACOs (Access Control Objects)')));
		$this->out(__d('cake_console', __('[Q]uit')));

		$taskToRun = strtoupper($this->in(__d('cake_console', __('What task would you like to run?')), array('I','C', 'U', 'R', 'V', 'Q')));

		switch ($taskToRun) {
			case 'C':
				$this->Acl->execute('create');
				break;
			case 'I':
				$this->Acl->execute('initDb');
				break;
			case 'R':
				$this->Acl->execute('recover');
				break;
			case 'U':
				$this->Acl->execute('update');
				break;
			case 'V':
				$this->Acl->execute('verify');
				break;
			case 'Q':
				return $this->_stop();
			default:
				$this->out(__d('cake_console', __('You have made an invalid selection. Please choose a task to run by entering I, C, U, or Q.')));
		}

		$this->hr();
		$this->main();
	}
}

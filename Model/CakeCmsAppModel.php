<?php
//namespace CakeCms\Model;

App::uses('AppModel', 'Model');
class CakeCmsAppModel extends AppModel
{
	public function getLastLog()
	{
		$dbo = $this->getDatasource();
		$logs = $dbo->getLog();
		return end($logs['log']);
	}
}

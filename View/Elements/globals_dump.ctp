<?php
/**
 * Globals Dump element. Dumps out global variable information 
 *
 * PHP versions 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2012, Brandon Bernal (http://bjlbernal.com)
 * @link          http://bjlbernal.com Brandon J L Bernal
 * @package       N/A
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (!class_exists('ConnectionManager') || Configure::read('debug') < 3) {
	return false;
}
// Sorted in order of most frequently needed.
$the_globals = array('_GET' => $_GET, '_POST' => $_POST, '_COOKIE' => $_COOKIE, '$this->params' => $this->params, '$this->viewVars' => $this->viewVars, '_SESSION' => $_SESSION, '_SERVER' => $_SERVER, '_ENV' => $_ENV);

foreach($the_globals as $label => $global){
  echo '<h4 class="color-white">'.$label.'</h4>';
  tablize($global);
}
?>

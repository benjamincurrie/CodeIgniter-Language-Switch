<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$hook['pre_system'] = array(
	'class'    => 'Language',
	'function' => 'route',
	'filename' => 'Language.php',
	'filepath' => 'hooks'
);

$hook['pre_controller'] = array(
	'class'    => 'Language',
	'function' => 'set',
	'filename' => 'Language.php',
	'filepath' => 'hooks'
);
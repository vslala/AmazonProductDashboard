<?php 
// composer autoloader for required packages and dependencies
require_once('lib/autoload.php');

/** @var \Base $f3 */
$f3 = \Base::instance();

$f3->set('DEBUG',1);
if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');

// F3 autoloader for application business code
$f3->set('AUTOLOAD', 'app/');

$f3->config('config.ini');
$f3->config('routes.ini');


$f3->run();
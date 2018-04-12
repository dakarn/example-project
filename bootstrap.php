<?php

session_start();

define('PATH_SYSTEM', __DIR__ . '/system/');
define('PATH_APP', __DIR__  . '/app/');

include(PATH_SYSTEM .'System/DefineSetup.php');
include(LOADER_CLASS);
include(APP_EVENT);
include(APP_KERNEL);

$application = null;
$runCommand  = null;

$loader = new System\Kernel\LoaderClass();
$loader->loader();

$event = new \AppEvent();
$event = $event->installEvents(new \System\EventListener\EventManager());

\System\Database\DB::setConfigure(new \System\Database\DatabaseConfigure(\System\Config::get('common', 'mysql')));

$appKernel = new \AppKernel();

switch(true) {
	case IS_WEB:
		include_once PATH_SYSTEM . 'web.php';
		break;
	case IS_CLI:
		include_once PATH_SYSTEM . 'cli.php';
		break;
	default:
		throw \Exception\KernelException::unknownEnvironment();
}

$application = $applicationInstance
	->setEnvironment('DEV')
	->setAppEvent($event)
	->setAppKernel($appKernel);

set_exception_handler(function($e) use($application) {
	$application->outputException($e);
});

register_shutdown_function(function() use($application) {
	System\Kernel\ShutdownScript::run();
});

$application->run($runCommand);
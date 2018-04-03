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

switch(PHP_SAPI) {
	default:
		$applicationTypes = new System\TypesApp\WebApp();

		$appKernel->installMiddlewares()->installProviders();
		$runCommand = System\Router\Routing::findRoute(\System\Config::get('router_maps'), System\Kernel\GETParam::getPath());
		break;
	case 'cli':
		$applicationTypes = new System\TypesApp\ConsoleApp();
		break;
}

$application = $applicationTypes
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
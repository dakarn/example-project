<?php

define('PATH_SYSTEM', __DIR__ . '/system/');
define('PATH_APP', __DIR__  . '/app/');
define('PSR_4', true);
define('CUSTOM_LOADER', false);

include_once PATH_SYSTEM . 'Helper/Util.php';

$application = null;
$runCommand  = null;

\Helper\Util::selectLoaderClass();

$event = new App\AppEvent();
$event = $event->installEvents(new \System\EventListener\EventManager());

\System\Database\DB::setConfigure(new \System\Database\DatabaseConfigure(\System\Config::get('common', 'mysql')));

$appKernel = new App\AppKernel();

switch(true) {
	case IS_WEB:
		include_once PATH_SYSTEM . 'web.php';
		break;
	case IS_CLI:
		include_once PATH_SYSTEM . 'cli.php';
		break;
	default:
		throw Exception\KernelException::unknownEnvironment();

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
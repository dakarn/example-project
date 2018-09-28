<?php
//Test
define('PATH_SYSTEM', __DIR__ . '/system/');
define('PATH_APP', __DIR__  . '/app/');
define('PSR_4', true);

include_once PATH_SYSTEM . 'Helper/Util.php';

\Helper\Util::selectLoaderClass();

$event = new App\AppEvent();
$event = $event->installEvents(new \System\EventListener\EventManager());

$appKernel = new App\AppKernel();
$appKernel->installProviders();

$application = (new \System\Kernel\TypesApp\ConsoleApp())
	->setEnvironment('DEV')
	->setAppEvent($event)
	->setAppKernel($appKernel)
	->setApplicationType('Console');

set_exception_handler(function($e) use($application) {
	$application->outputException($e);
});

register_shutdown_function(function() use($application) {
	System\Kernel\ShutdownScript::run();
});

$application->run();

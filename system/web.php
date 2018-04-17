<?php

$applicationInstance = new \System\Kernel\TypesApp\WebApp();
$applicationInstance->setApplicationType('Web');

$appKernel
	->installMiddlewares()
	->installProviders();

$runCommand = System\Router\Routing::findRoute(\System\Config::getRouters(), System\Kernel\GETParam::getPath());
<?php

$applicationInstance = new System\TypesApp\WebApp();

$appKernel
	->installMiddlewares()
	->installProviders();

$runCommand = System\Router\Routing::findRoute(\System\Config::getRouters(), System\Kernel\GETParam::getPath());
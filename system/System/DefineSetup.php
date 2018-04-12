<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.03.2018
 * Time: 2:17
 */

define('TEMPLATE', PATH_APP . 'Templates');

if (isset($_SERVER['HTTP_HOST'])) {
	define('IS_DOMAIN', true);

	if (IS_DOMAIN) {
		define('URL', 'http://' . $_SERVER['HTTP_HOST'].'/');
	} else {
		define('URL', 'http://' . $_SERVER['HTTP_HOST'] .'/elasticsearch/');
	}
}

define('LOADER_CLASS', PATH_SYSTEM . 'System/Kernel/LoaderClass.php');
define('CONFIG_APP_PATH', PATH_APP . 'Config/');
define('APP_EVENT', PATH_APP . 'AppEvent.php');
define('APP_KERNEL', PATH_APP . 'AppKernel.php');
define('IS_CLI', PHP_SAPI === 'cli');
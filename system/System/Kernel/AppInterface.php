<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.03.2018
 * Time: 19:21
 */

namespace System\Kernel;

use System\Router\Router;

interface AppInterface
{
	public function run(Router $runCommand);

	public function runController(Router $runCommand);

	public function runMiddleware(Router $runCommand);

	public function isRepeatRun();
}
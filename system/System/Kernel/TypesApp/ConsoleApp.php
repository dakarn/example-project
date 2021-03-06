<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.04.2018
 * Time: 14:55
 */

namespace System\Kernel\TypesApp;

use System\Database\DB;

final class ConsoleApp extends AbstractApplication
{
	public function run()
	{
        $this->runInternal();
	}

	public function terminate()
	{
		DB::disconnect();
	}
}
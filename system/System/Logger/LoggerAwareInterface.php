<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.03.2018
 * Time: 15:21
 */

namespace System\Logger;

interface LoggerAwareInterface
{
	/**
	 * @param LoggerInterface $logger
	 * @return mixed
	 */
	public static function setLogger(LoggerInterface $logger);
}
<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31.03.2018
 * Time: 17:20
 */

namespace Exception;

class ControllerException extends \Exception
{
	public static function notFound(array $arguments = []): self
	{
		return new self('');
	}

	public static function invalidArguments(array $arguments = []): self
	{
		return new self('');
	}

	public static function beforeNoReturnBool(array $arguments = []): self
	{
		return new self('');
	}
}
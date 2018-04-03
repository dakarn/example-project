<?php

namespace System\Kernel;

class GETParam
{
	private static $paramForController = [];

	public static function setParamForController(array $nameParams, array $values)
	{
		array_shift($values);

		foreach ($values as $index => $value) {
			self::$paramForController[$nameParams[$index]] = $value;
			$_GET[$nameParams[$index]] = $value;
		}
	}

	public static function getParamForController()
	{
		return self::$paramForController;
	}

	public static function getPath(): string
	{
		return str_replace(basename(__DIR__), '', self::options());
	}

	public static function options(): string
	{
		if (!isset($_GET['options'])) {
			$_GET['options'] = '';
		}

		return $_GET['options'];
	}
}
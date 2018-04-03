<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.03.2018
 * Time: 18:13
 */

namespace Middleware;

class StorageMiddleware
{
	private static $list = [];

	private static $currPos = 0;

	public static function add(array $middlewares)
	{
		self::$list    = [];
		self::$currPos = 0;

		foreach ($middlewares as $middleware) {
			if ($middleware['autoStart'] === true) {
				self::$list[] = $middleware;
			}
		}
	}

	public static function addOne(array $middleware)
	{
		self::$list[] = $middleware;
	}

	public static function currPosition(): int
	{
		return self::$currPos;
	}

	public static function nextPosition()
	{
		self::$currPos++;
	}

	public static function count(): int
	{
		return count(self::$list);
	}

	public static function get(): array
	{
		return self::$list;
	}
}
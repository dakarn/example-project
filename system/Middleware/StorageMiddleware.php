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
	/**
	 * @var array
	 */
	private static $middlewareList = [];

	/**
	 * @var int
	 */
	private static $currPos = 0;

	/**
	 * @param array $middlewares
	 */
	public static function add(array $middlewares): void
	{
		self::$middlewareList = [];
		self::$currPos        = 0;

		foreach ($middlewares as $middleware) {
			if ($middleware['autoStart'] === true) {
				self::$middlewareList[] = $middleware;
			}
		}
	}

	/**
	 * @return array
	 */
	public static function deleteFirstItem(): array
    {
        return array_shift(self::$middlewareList);
    }

	/**
	 * @return array
	 */
    public static function deleteEndItem(): array
    {
        return array_pop(self::$middlewareList);
    }

	/**
	 * @param array $middleware
	 */
	public static function addOne(array $middleware)
	{
		self::$middlewareList[] = $middleware;
	}

	/**
	 * @return int
	 */
	public static function currPosition(): int
	{
		return self::$currPos;
	}

	/**
	 * @var void
	 */
	public static function nextPosition(): void
	{
		self::$currPos++;
	}

	/**
	 * @return int
	 */
	public static function count(): int
	{
		return count(self::$middlewareList);
	}

	/**
	 * @return array
	 */
	public static function get(): array
	{
		return self::$middlewareList;
	}
}
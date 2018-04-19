<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.03.2018
 * Time: 18:13
 */

namespace Providers;

class StorageProviders
{
	/**
	 * @var array
	 */
	private static $list = [];

	/**
	 * @var int
	 */
	private static $currPos = 0;

	/**
	 * @param array $providers
	 */
	public static function add(array $providers): void
	{
		foreach ($providers as $provider) {
			if ($provider['autoStart'] === true) {
				self::$list[] = $provider;
			}
		}
	}

	/**
	 * @param array $provider
	 */
	public static function addOne(array $provider): void
	{
		self::$list[] = $provider;
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
		return count(self::$list);
	}

	/**
	 * @return array
	 */
	public static function get(): array
	{
		return self::$list;
	}
}
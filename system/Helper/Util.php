<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.03.2018
 * Time: 22:29
 */

namespace Helper;

class Util
{
	const DICTIONARY       = 'qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM';

	const DICTIONARY_DIGIT = '1234567890';

	/**
	 * @return string
	 */
	public static function filterRegex(): string
	{
		return '';
	}

	/**
	 * @param int $length
	 * @return string
	 */
	public static function generateCSRFToken(int $length = 30): string
	{
		return self::generateRandom($length);
	}

	/**
	 * @param int $length
	 * @return string
	 */
	public static function generateCookieToken(int $length = 20): string
	{
		return self::generateRandom($length);
	}

	/**
	 * @param int $length
	 * @return string
	 */
	private static function generateRandom(int $length): string
	{
		$i        = 0;
		$response = '';
		$count    = strlen(self::DICTIONARY) - 1;

		while ($i <= $length) {
			$response .= self::DICTIONARY[random_int(0, $count)];
			++$i;
		}

		return $response;
	}

	/**
	 * @var void
	 */
	public static function selectLoaderClass(): void
	{
		switch (true) {
			case PSR_4:
				include_once PATH_SYSTEM . '/../vendor/autoload.php';
				break;
			default:
				include_once PATH_SYSTEM . 'autoload.php';
				break;
		}
	}
}
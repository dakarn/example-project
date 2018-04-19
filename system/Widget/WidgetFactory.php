<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.04.2018
 * Time: 2:09
 */

namespace Widget;

use Exception\WidgetException;
use System\Config;

class WidgetFactory
{
	/**
	 * @param string $widgetName
	 * @return WidgetInterface
	 * @throws WidgetException
	 */
	public static function run(string $widgetName): WidgetInterface
	{
		$classWidget = Config::get('widgets', $widgetName);

		if (empty($classWidget)) {
			throw WidgetException::notFound([$widgetName]);
		}

		return new $classWidget['class']();
	}
}
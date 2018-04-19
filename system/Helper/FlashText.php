<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.03.2018
 * Time: 16:16
 */

namespace Helper;

use Http\Request\Request;

class FlashText
{
	/**
	 * @param string $type
	 * @param string $text
	 */
	public static function add(string $type, string $text): void
	{
		$session = Request::create()->getSession();
		$flash   = $session->getAsArray('flashText');

		$flash[count($flash)] = [
			'type' => $type,
			'text' => $text
		];

		$session->set('flashText', $flash);
	}

	/**
	 * @return bool
	 */
	public static function has(): bool
	{
		if (!empty(Request::create()->getSession()->has('flashText'))) {
			return false;
		}

		return false;
	}

	/**
	 * @param string $type
	 * @return bool
	 */
	public static function hasByType(string $type): bool
	{
		$sessions = Request::create()->getSession()->getAsArray('flashText');

		foreach ($sessions as $session) {
			if ($session['type'] === $type) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $type
	 * @return array
	 */
	public static function get(string $type): array
	{
		$response = [];
		$sessions = Request::create()->getSession()->getAsArray('flashText');

		foreach ($sessions as $session) {
			if ($session['type'] === $type) {
				$response[] = $session['text'];
			}
		}

		return $response;
	}

	/**
	 * @var void
	 */
	public static function render()
	{
		$session = Request::create()->getSession();
		$data    = $session->getAsArray('flashText');
		$session->delete('flashText');

		FlashTextRender::render($data);
	}

	/**
	 * @return bool
	 */
	public static function remove(): bool
	{
		Request::create()
			->getSession()
			->delete('flashText');

	    return true;
	}
}
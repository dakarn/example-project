<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.03.2018
 * Time: 23:17
 */

namespace Helper;

use Traits\SingletonTrait;
use Http\Request\Request;

class CSRFToken
{
	use SingletonTrait;

	/**
	 * @var bool
	 */
	private $isValid = false;

	/**
	 * @var string
	 */
	private $token;

	/**
	 * @param string $tokenFromCookie
	 * @param string $tokenFromPost
	 * @return CSRFToken
	 */
	public function setValidationData(string $tokenFromCookie, string $tokenFromPost): CSRFToken
	{
		$this->isValid = $tokenFromCookie === $tokenFromPost;
		return $this;
	}

	/**
	 * @var void
	 */
	public function makeToken(): void
	{
		$this->token = Util::generateCSRFToken();

		Request::create()
			->getCookie()
			->set('CSRFToken', $this->token);
	}

	/**
	 * @return string
	 */
	public function getToken(): string
	{
		return $this->token;
	}

	/**
	 * @return bool
	 */
	public function isValid(): bool
	{
		return $this->isValid;
	}
}
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
		if (empty($tokenFromPost) || empty($tokenFromCookie)) {
			$this->isValid = false;
			return $this;
		}

		$this->isValid = $tokenFromCookie === $tokenFromPost;
		return $this;
	}

	/**
	 * @return void
	 */
	public function makeToken(): void
	{
		$token = Cookie::create()->get('CSRFToken');

		if (!empty($token)) {
			$this->token = $token;
			return;
		}

		$this->token = Util::generateCSRFToken();
		Cookie::create()->set('CSRFToken', $this->token);
	}

	/**
	 * @return string
	 */
	public function returnForForm(): string
	{
		$this->makeToken();
		return $this->token;
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
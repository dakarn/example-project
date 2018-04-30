<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.03.2018
 * Time: 23:17
 */

namespace Helper;

use Configs\Config;
use Traits\SingletonTrait;
use Http\Cookie;

class CSRFTokenManager
{
	use SingletonTrait;

    /**
     * @var string
     */
	const TOKEN_NAME = 'CSRFToken';

	/**
	 * @var bool
	 */
	private $isValid = false;

    /**
     * @var CSRFToken
     */
	private $token;

	/**
	 * @var bool
	 */
	private $isUserToken = false;

    /**
     * @return void
     */
	public function start(): void
    {
        if (!$this->token instanceof CSRFToken) {
            $this->token = new CSRFToken();
        }

	    $this->isUserToken = Config::get('common', 'useCSRFToken');

    }

	/**
	 * @param string $tokenFromCookie
	 * @param string $tokenFromPost
	 * @return CSRFTokenManager
	 */
	public function setValidationData(string $tokenFromCookie, string $tokenFromPost): CSRFTokenManager
	{
		if (!$this->isUserToken) {
			$this->isValid = true;
			return $this;
		}

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
        $this->start();

		$this->token->setToken(Util::generateCSRFToken());
		Cookie::create()->set(self::TOKEN_NAME, $this->token->getToken());
	}

    /**
     * @return void
     */
	public function refreshToken(): void
    {
	    $this->start();

        $this->token->setToken(Util::generateCSRFToken());
        Cookie::create()->set(self::TOKEN_NAME, $this->token->getToken());
    }

    /**
     * @return void
     */
    public function removeToken(): void
    {
        Cookie::create()->remove(self::TOKEN_NAME);
    }

	/**
	 * @return string
	 */
	public function returnForForm(): string
	{
		$this->start();
		return $this->token->getToken();
	}

	/**
	 * @return string
	 */
	public function getToken(): string
	{
		return $this->token->getToken();
	}

	/**
	 * @return bool
	 */
	public function isValid(): bool
	{
		return $this->isValid;
	}
}
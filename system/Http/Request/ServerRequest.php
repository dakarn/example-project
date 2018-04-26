<?php
/**
 * Created by PhpStorm.
 * User: v.konovalov
 * Date: 20.04.2018
 * Time: 13:12
 */

namespace Http\Request;

use Exception\MiddlewareException;
use Helper\Cookie;
use Helper\Session;
use Http\Middleware\StorageMiddleware;
use Http\Response\Response;
use Http\Middleware\RequestHandler;

class ServerRequest
{
    /**
     * @var  Response
     */
    private $response;

	/**
	 * @var ServerRequest
	 */
    private static $requestGlobal;

    /**
     * @var ServerRequest
     */
    private static $request;

	/**
	 * @var array
	 */
    private $headers = [];

	/**
	 * @return ServerRequest
	 * @throws MiddlewareException
	 */
    public function handle(): ServerRequest
    {
        if (StorageMiddleware::count() === 0) {
            throw MiddlewareException::needMinOne();
        }

        $runHandler = new RequestHandler(new Response());
        $this->response = $runHandler->handle($this, $runHandler);

        return $this;
    }

	/**
	 * @return ServerRequest
	 */
	public static function create(): ServerRequest
	{
		if (!self::$request instanceof ServerRequest) {
			self::$request = new self();
		}

		return self::$request;
	}

	/**
	 * @return ServerRequest
	 */
	public static function fromGlobal(): ServerRequest
	{
		if (!self::$requestGlobal instanceof ServerRequest) {
			self::$requestGlobal = new ServerRequest();
		}

		return self::$requestGlobal;
	}

    /**
     * @param string $param
     * @return string
     */
	public function getServerParam(string $param): string
    {
        return $_SERVER[$param] ?? '';
    }

    /**
     * @return Response
     */
    public function resultHandle(): Response
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $_SERVER;
    }

    /**
     * @param string $item
     * @return string
     */
    public function getHeader(string $item): string
    {
        return $_SERVER[$item] ?? '';
    }

    /**
     * @return string
     */
    public function getUserIp(): string
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->getProperty('host', 'HTTP_HOST');
    }

    /**
     * @return string
     */
    public function getReferer(): string
    {
        return $this->getProperty('referer', 'HTTP_REFERER');
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->getProperty('userAgent', 'HTTP_USER_AGENT');
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->getProperty('method', 'REQUEST_METHOD');
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getProxy(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getAuthorization(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getQueryString(): string
    {
        return $_SERVER['QUERY_STRING'];
    }

    /**
     * @param $key
     * @return string
     */
    public function takePost($key): string
    {
        return $_POST[$key] ?? '';
    }

    /**
     * @param $key
     * @return string
     */
    public function takeAny($key): string
    {
        return ($this->takePost($key)) ?: ($this->takeGet($key) ?: '');
    }

    /**
     * @param $key
     * @return string
     */
    public function takeGet($key): string
    {
        return $_GET[$key] ?? '';
    }

    /**
     * @return bool
     */
    public function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }

    /**
     * @return string
     */
    public function getRequestUri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * @return Cookie
     */
    public function getCookie(): Cookie
    {
        return Cookie::create();
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return Session::create();
    }

	/**
	 * @param string $property
	 * @param string $default
	 * @return array|string
	 */
	protected function getProperty(string $property, string $default = '')
	{
		return !empty($this->{$property}) ? $this->headers: ($_SERVER[$default] ?? '');
	}
}
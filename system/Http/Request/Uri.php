<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.04.2018
 * Time: 20:48
 */

namespace Http\Request;

class Uri implements UriInterface
{
    /**
     * @var string
     */
	private $uri;

    /**
     * Uri constructor.
     * @param string $uri
     */
	public function __construct(string $uri)
	{
		$this->uri = $uri;
	}

    /**
     * @return string
     */
	public function getUri(): string
	{
		return $this->uri;
	}

    /**
     * @param string $scheme
     * @return UriInterface
     */
	public function withScheme(string $scheme): UriInterface
	{
	    $this->uri = $scheme;
        return $this;
	}

    /**
     * @param string $queryString
     * @return UriInterface
     */
	public function withQueryString(string $queryString): UriInterface
    {
        $this->uri = $queryString;
        return $this;
    }

    /**
     * @param string $path
     * @return UriInterface
     */
	public function withPath(string $path): UriInterface
	{
        $this->uri = $path;
        return $this;
	}

    /**
     * @param string $port
     * @return UriInterface
     */
	public function withPort(string $port): UriInterface
	{
        $this->uri = $port;
        return $this;
	}

    /**
     * @param string $uri
     * @return UriInterface
     */
	public function withUri(string $uri): UriInterface
	{
        $this->uri = $uri;
        return $this;
	}

    /**
     * @return string
     */
	public function __toString()
    {
       return $this->uri;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.04.2018
 * Time: 20:50
 */

namespace Http\Request;

interface UriInterface
{
    /**
     * @return string
     */
    public function getUri(): string;

    /**
     * @param string $scheme
     * @return UriInterface
     */
    public function withScheme(string $scheme): UriInterface;

    /**
     * @param string $path
     * @return UriInterface
     */
    public function withPath(string $path): UriInterface;

    /**
     * @param string $port
     * @return UriInterface
     */
    public function withPort(string $port): UriInterface;

    /**
     * @param string $uri
     * @return UriInterface
     */
    public function withUri(string $uri): UriInterface;

    /**
     * @return mixed
     */
    public function __toString();

    /**
     * @param string $queryString
     * @return UriInterface
     */
    public function withQueryString(string $queryString): UriInterface;

}
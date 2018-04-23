<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.04.2018
 * Time: 20:28
 */

namespace Http\Request;

class RequestBuilder implements RequestBuilderInterface
{
    /**
     * @var resource
     */
    private $ch;

    /**
     * RequestBuilder constructor.
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
       $this->build($request);
    }

    /**
     * @return resource
     */
    public function getBuilderData()
    {
        return $this->ch;
    }

    /**
     * @param RequestInterface $request
     */
    private function build(RequestInterface $request)
    {
        $this->ch = curl_init($request->getHost());

        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);

        if ($request->getMethod() === Request::POST) {
            curl_setopt($this->ch, CURLOPT_POST, true);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $request->getBody());
        } else if ($request->getMethod() !== Request::GET) {
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        }

        if (!empty($request->getReferer())) {
            curl_setopt($this->ch, CURLOPT_REFERER, $request->getReferer());
        }

        if (!empty($request->getUserAgent())) {
            curl_setopt($this->ch, CURLOPT_REFERER, $request->getUserAgent());
        }

        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
    }
}
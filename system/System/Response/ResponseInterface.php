<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.03.2018
 * Time: 2:04
 */

namespace System\Response;

interface ResponseInterface
{
	public function render($data, array $param);
}
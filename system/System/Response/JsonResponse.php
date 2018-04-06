<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.03.2018
 * Time: 0:54
 */

namespace System\Response;

class JsonResponse implements ResponseInterface
{
	public function render($data, array $param)
	{
		echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}
}
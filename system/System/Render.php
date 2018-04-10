<?php

namespace System;

class Render
{
	private $path = TEMPLATE . '/';

	public function __construct($template, array $params = [])
	{
		if (!file_exists($this->path . $template)) {
			include_once($this->path . Config::get('common','errors')['404']);
		} else {
			extract($params);
			include($this->path . $template);
		}
	}
}
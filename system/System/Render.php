<?php

namespace System;

class Render
{
	private $path = TEMPLATE . '/';

	public function __construct($template, array $params = [])
	{
		if (!file_exists($this->path . $template)) {
			return include_once($this->path . Config::get('errors')['404']);
		}

		extract($params);
		include($this->path . $template);
	}
}
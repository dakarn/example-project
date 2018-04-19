<?php

namespace System;

class Render
{
	const PATH = TEMPLATE . '/';

	private $template = '';

	private $params = [];

	public function __construct($template, array $params = [])
	{
		if (!file_exists(self::PATH . $template)) {
			$this->template = self::PATH . Config::get('common','errors')['404'];
		} else {
			$this->params   = $params;
			$this->template = self::PATH . $template;
		}
	}

	public function render(): string
    {
        extract($this->params);
        ob_start();
        include $this->template;
        return ob_get_clean();
	}
}
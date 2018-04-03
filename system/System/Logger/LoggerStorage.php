<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.03.2018
 * Time: 15:30
 */

namespace System\Logger;

class LoggerStorage
{
	private static $instance;

	private $log = [];

	public static function create()
	{
		if (!self::$instance instanceof LoggerStorage) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function addLog(string $level, string $message)
	{
		$this->log[] = [
			'time'    => date('d.m.y H:i:s', time()),
			'level'   => $level,
			'message' => $message
		];

		return $this;
	}

	public function getLog(): array
	{
		return $this->log;
	}

	public function releaseLog()
	{
		foreach ($this->log as $log) {
			error_log('Log' . $log['level'] . ' - ' . $log['time'] . ' - ' . $log['message']);
		}
	}
}
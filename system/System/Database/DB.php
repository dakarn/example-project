<?php

namespace System\Database;

class DB
{
    /**
     * @var \mysqli
     */
	private static $connect;

	/**
	 * @var DatabaseConfigure
	 */
	private static $configure;

    /**
     * DB constructor.
     */
	private function __construct()
	{
	}

    /**
     * @var void
     */
	private function __clone()
	{
	}

    /**
     * @param DatabaseConfigure $configure
     */
	public static function setConfigure(DatabaseConfigure $configure)
	{
		self::$configure = $configure;
	}

    /**
     * @return \mysqli
     */
	public static function create(): \mysqli
	{
		if (!self::$connect instanceof \mysqli) {
			self::$connect = new \mysqli(
				self::$configure->getHost(),
				self::$configure->getUser(),
				self::$configure->getPassword(),
				self::$configure->getDatabase()
			);
			self::$connect->set_charset(self::$configure->getCharset());
		}

		return self::$connect;
	}


}
<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.03.2018
 * Time: 1:06
 */

namespace System\EventListener;

class EventTypes
{
	const DEFAULT_TEMPLATE   = 'defaultTemplate';

	const BEFORE_CONTROLLER  = 'beforeController';

	const AFTER_CONTROLLER   = 'afterController';

	const BEFORE_ACTION      = 'beforeAction';

	const AFTER_ACTION       = 'afterAction';

	const BEFORE_ROUTING     = 'beforeRouting';

	const AFTER_ROUTING      = 'afterRouting';

	const BEFORE_DB_CONNECT  = 'beforeDBConnect';

	const APP_SHUT_DOWN      = 'appShutdown';

	const THROW_EXCEPTION    = 'throwException';

	const AFTER_DB_CONNECT   = 'afterDBConnect';

	const SUCCESS_DB_CONNECT = 'successDBConnect';

	const FAILED_DB_CONNECT  = 'failedDBConnect';
}
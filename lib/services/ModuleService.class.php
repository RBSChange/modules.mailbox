<?php
/**
 * @package modules.mailbox.lib.services
 */
class mailbox_ModuleService extends ModuleBaseService
{
	/**
	 * Singleton
	 * @var mailbox_ModuleService
	 */
	private static $instance = null;

	/**
	 * @return mailbox_ModuleService
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}
}
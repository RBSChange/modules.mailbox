<?php
class mailbox_Setup extends object_InitDataSetup
{

	public function install()
	{
		try
		{
			//$scriptReader = import_ScriptReader::getInstance();
			//$scriptReader->executeModuleScript('mailbox', 'init.xml');
		}
		catch (Exception $e)
		{
			echo "ERROR: " . $e->getMessage() . "\n";
			Framework::exception($e);
		}
	}
}
<?php
/**
 * mailbox_persistentdocument_message
 * @package mailbox
 */
class mailbox_persistentdocument_message extends mailbox_persistentdocument_messagebase 
{	
	private function useCompression()
	{
		try 
		{
			return Framework::getConfiguration('modules/mailbox/use_compression') == "true";
		} 
		catch (Exception $e) 
		{
			return true;
		}	
	}
	
	/**
	 * @param String $content
	 * @return void
	 */
	public function setContent($content)
	{
		if($this->useCompression())
		{
			$gzContent = gzcompress($content, 9);
			parent::setContent($gzContent);
		}
		else
		{
			parent::setContent($content);
		}
	}
	
	/**
	 * @return String
	 */
	public function getUngzContent()
	{
		if($this->useCompression())
		{		
			return gzuncompress($this->getContent());
		}
		else
		{
			return $this->getContent();
		}
	}	
	
	public function getModulename()
	{
		return ModuleService::getInstance()->getUILocalizedModuleLabel(parent::getModulename());
	}

}
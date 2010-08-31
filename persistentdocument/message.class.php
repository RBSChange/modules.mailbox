<?php
/**
 * mailbox_persistentdocument_message
 * @package mailbox
 */
class mailbox_persistentdocument_message extends mailbox_persistentdocument_messagebase 
{
	/**
	 * @return boolean
	 */
	private function useCompression()
	{
		return Framework::getConfigurationValue('modules/mailbox/use_compression', 'true') == 'true';
	}
	
	/**
	 * @param string $content
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
	 * @return string
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
	
	/**
	 * @return string
	 */
	public function getModulename()
	{
		return ModuleService::getInstance()->getUILocalizedModuleLabel(parent::getModulename());
	}
}
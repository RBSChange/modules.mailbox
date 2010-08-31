<?php
class mailbox_MessageService extends f_persistentdocument_DocumentService
{
	/**
	 * @var mailbox_MessageService
	 */
	private static $instance;

	/**
	 * @var generic_FolderService
	 */
	private $folderService = null;

	/**
	 * @return mailbox_MessageService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	/**
	 * @return mailbox_persistentdocument_message
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_mailbox/message');
	}

	/**
	 * Create a query based on 'modules_mailbox/message' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_mailbox/message');
	}

	/**
	 * @param mailbox_persistentdocument_message $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId)
	{
		$document->setLabel($document->getSubject());
		if (f_util_StringUtils::isEmpty($document->getReceiver()))
		{
			$document->setReceiver('unknown');
		}
	}

	/**
	 * @param mailbox_persistentdocument_message $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function postInsert($document, $parentNodeId)
	{
		$this->getFolderOfDay();
	}

	/**
	 * Send the mail message and after if the message is correctly sended, save it in mail box
	 *
	 * @param MailMessage $mailMessage
	 * @return boolean Return true if the mail is correctly sended and saved
	 */
	public function send(MailMessage $mailMessage)
	{
		if ( $mailMessage->send() === true )
		{
			// Create a new message in the mailBox
			$mailboxMessage = $this->getNewDocumentInstance();
			$mailboxMessage->setSubject($mailMessage->getSubject());
			$mailboxMessage->setReceiver($mailMessage->getReceiver());
			$mailboxMessage->setSender($mailMessage->getSender());
			$mailboxMessage->setContent($mailMessage->getHtmlContent());
			$mailboxMessage->setModulename($mailMessage->getModulename());
			$mailboxMessage->save();
			return true;
		}
		return false;
	}

	/**
	 * Return an object of MailMessage see too MailService::getNewMailMessage()
	 *
	 * @return MailMessage
	 */
	public function getNewMailMessage()
	{
		return MailService::getInstance()->getNewMailMessage();
	}

	/**
	 * Return the of folder where the message must be saved
	 *
	 * @return generic_persistentdocument_folder
	 */
	public function getFolderOfDay()
	{
		return TreeService::getInstance()->getFolderOfDate(ModuleService::getInstance()->getRootFolderId('mailbox'), null);;
	}
	
	/**
	 * @see f_persistentdocument_DocumentService::getResume()
	 *
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param string $forModuleName
	 * @param array $allowedSections
	 * @return array
	 */
	public function getResume($document, $forModuleName, $allowedSections = null)
	{
		$data = parent::getResume($document, $forModuleName, $allowedSections);
		$link = LinkHelper::getUIActionLink('mailbox', 'ViewMessage');
		$link->setQueryParameter(K::COMPONENT_ID_ACCESSOR, $document->getId());
		$data['content']['iframeurl'] = $link->getUrl();
	    $data['properties']['modulename'] = $document->getModulename();
	    $data['properties']['sender'] = $document->getSender();
	    $data['properties']['receiver'] = $document->getReceiver();   
		return $data;
	}
}
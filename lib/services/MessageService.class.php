<?php
class mailbox_MessageService extends f_persistentdocument_DocumentService
{
	/**
	 * @var mailbox_MessageService
	 */
	private static $instance;

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
		throw new Exception('Deprecated document');
	}

}
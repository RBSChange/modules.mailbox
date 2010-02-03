<?php
class mailbox_MessageScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return mailbox_persistentdocument_message
     */
    protected function initPersistentDocument()
    {
    	return mailbox_MessageService::getInstance()->getNewDocumentInstance();
    }
}
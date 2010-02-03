<?php
class modules_mailbox_tests_MessageServiceTest extends f_tests_AbstractBaseTest
{
	
	protected function prepareTestCase()
	{
		$this->truncateAllTables();

		RequestContext::getInstance()->setLang(RequestContext::getInstance()->getDefaultLang());
	}
	
	protected function endTestCase()
	{
		$this->truncateAllTables();
	}
	
	public function testAddfolderInTreeBeforeInsertOfMessage()
	{

		$messageService = mailbox_MessageService::getInstance();
		
		$message = $messageService->getNewDocumentInstance();
		
		$message->setSubject('Objet de mon message');
		$message->setReceiver('adresse email du receiver');
		$message->setSender('adresse email du sender');
		$message->setContent('Mon message');
		$message->setModulename('test');
		
		$message->save();
		
		// Test year folder
		$pp = f_persistentdocument_PersistentProvider::getInstance();
		
		$folderYear = $pp->createQuery('modules_generic/folder')
						->add(Restrictions::eq('label', date('Y')))
						->findUnique();
		$this->assertNotNull($folderYear, 'Folder Year not created');
		
		$folderMonth = $pp->createQuery('modules_generic/folder')
						->add(Restrictions::childOf($folderYear->getId()))
						->add(Restrictions::eq('label', date('m')))
						->findUnique();
		$this->assertNotNull($folderMonth, 'Folder Month not created');
		
		$folderDay = $pp->createQuery('modules_generic/folder')
						->add(Restrictions::childOf($folderMonth->getId()))
						->add(Restrictions::eq('label', date('d')))
						->findUnique();
		$this->assertNotNull($folderDay, 'Folder Day not created');
						
	}

	public function testSendMailAndSaveIt()
	{
		
		$messageService = mailbox_MessageService::getInstance();
		
		$mailMessage = $messageService->getNewMailMessage();
		$mailMessage->setSubject('Test save in mailbox');
		$mailMessage->setSender('support@rbs.fr');
		$mailMessage->setReceiver('support@rbs.fr');
		$mailMessage->setModuleName('test');
		$mailMessage->setHtmlAndTextBody('<body><h1>Test HTML</h1>My html test<br/>with my text test </body>', "Test\n My test in text format");
		
		$this->assertTrue($messageService->send($mailMessage));
		
	}
	
	public function testGetFolderDay()
	{
		$messageMailboxService = mailbox_MessageService::getInstance();

		$fodlerDayByService = $messageMailboxService->getFolderOfDay();
		
		// Test year folder
		$pp = f_persistentdocument_PersistentProvider::getInstance();
		
		$folderYear = $pp->createQuery('modules_generic/folder')
						->add(Restrictions::eq('label', date('Y')))
						->findUnique();
		$this->assertNotNull($folderYear, 'Folder Year not created');
		
		$folderMonth = $pp->createQuery('modules_generic/folder')
						->add(Restrictions::childOf($folderYear->getId()))
						->add(Restrictions::eq('label', date('m')))
						->findUnique();
		$this->assertNotNull($folderMonth, 'Folder Month not created');
		
		$folderDay = $pp->createQuery('modules_generic/folder')
						->add(Restrictions::childOf($folderMonth->getId()))
						->add(Restrictions::eq('label', date('d')))
						->findUnique();
		$this->assertNotNull($folderDay, 'Folder Day not created');
		
		$this->assertEquals($fodlerDayByService, $folderDay->getId(), 'The folder of day found in databases by persistent provider ('.$folderDay->getId().') is not the same that the folder found by method getFolderOfDay() on mailbox_MessageService ('.$fodlerDayByService.')');
		
	}
	
	public function testGetNewMessage()
	{
		$this->assertType('MailMessage', mailbox_MessageService::getInstance()->getNewMailMessage(), 'The type of object return by method getNewMailMessage is not of type of MailMessage');
	}
	
	public function testgetNewDocumentInstance()
	{
		$this->assertType('mailbox_persistentdocument_message', mailbox_MessageService::getInstance()->getNewDocumentInstance(), 'The type of object return by method getNewDocumentInstance is not of type of mailbox_persistentdocument_message');
	}
		
}
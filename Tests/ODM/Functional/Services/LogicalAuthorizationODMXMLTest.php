<?php

namespace Ordermind\LogicalAuthorizationBundle\Tests\ODM\Functional\Services;

class LogicalAuthorizationODMXMLTest extends LogicalAuthorizationODMBase
{
  /**
   * This method is run before each public test method
   */
  protected function setUp() {
    $this->load_services = array(
      'testDocumentRoleAuthorRepositoryManager' => 'repository_manager.test_document_roleauthor_xml',
      'testDocumentHasAccountNoInterfaceRepositoryManager' => 'repository_manager.test_document_hasaccount_xml',
      'testDocumentNoBypassRepositoryManager' => 'repository_manager.test_document_nobypass_xml',
    );

    parent::setUp();
  }
}
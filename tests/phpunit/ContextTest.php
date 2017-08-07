<?php

namespace ProofreadPage;

use ProofreadPage\Index\CustomIndexFieldsParser;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Context
 */
class ContextTest extends ProofreadPageTestCase {

	public function testGetPageNamespaceId() {
		$context = new Context( 42, 44, new FileProviderMock( [] ), new CustomIndexFieldsParser() );
		$this->assertEquals( 42, $context->getPageNamespaceId() );
	}

	public function testGetIndexNamespaceId() {
		$context = new Context( 42, 44, new FileProviderMock( [] ), new CustomIndexFieldsParser() );
		$this->assertEquals( 44, $context->getIndexNamespaceId() );
	}

	public function testGetFileProvider() {
		$context = new Context( 42, 44, new FileProviderMock( [] ), new CustomIndexFieldsParser() );
		$this->assertInstanceOf( '\ProofreadPage\FileProvider', $context->getFileProvider() );
	}

	public function testGetPaginationFactory() {
		$context = new Context( 42, 44, new FileProviderMock( [] ), new CustomIndexFieldsParser() );
		$this->assertInstanceOf(
			'\ProofreadPage\Pagination\PaginationFactory', $context->getPaginationFactory()
		);
	}

	public function testCustomIndexFieldsParser() {
		$context = new Context( 42, 44, new FileProviderMock( [] ), new CustomIndexFieldsParser() );
		$this->assertInstanceOf(
			'\ProofreadPage\Index\CustomIndexFieldsParser', $context->getCustomIndexFieldsParser()
		);
	}
}

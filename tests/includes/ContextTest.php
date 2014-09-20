<?php

namespace ProofreadPage;

use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Context
 */
class ContextTest extends ProofreadPageTestCase {

	public function testGetPageNamespaceId() {
		$context = new Context( 42, 44, new FileProviderMock( array() ) );
		$this->assertEquals( 42, $context->getPageNamespaceId() );
	}

	public function testGetIndexNamespaceId() {
		$context = new Context( 42, 44, new FileProviderMock( array() ) );
		$this->assertEquals( 44, $context->getIndexNamespaceId() );
	}

	public function testGetFileProvider() {
		$context = new Context( 42, 44, new FileProviderMock( array() ) );
		$this->assertInstanceOf( '\ProofreadPage\FileProvider', $context->getFileProvider() );
	}

	public function testGetPaginationFactory() {
		$context = new Context( 42, 44, new FileProviderMock( array() ) );
		$this->assertInstanceOf( '\ProofreadPage\Pagination\PaginationFactory', $context->getPaginationFactory() );
	}
}
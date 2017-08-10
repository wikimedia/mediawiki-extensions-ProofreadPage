<?php

namespace ProofreadPage;

use ProofreadPage\Index\CustomIndexFieldsParser;
use ProofreadPage\Index\IndexContentLookupMock;
use ProofreadPage\Page\IndexForPageLookupMock;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Context
 */
class ContextTest extends ProofreadPageTestCase {

	public function testGetPageNamespaceId() {
		$this->assertEquals( 42, $this->buildDummyContext()->getPageNamespaceId() );
	}

	public function testGetIndexNamespaceId() {
		$this->assertEquals( 44, $this->buildDummyContext()->getIndexNamespaceId() );
	}

	public function testGetFileProvider() {
		$this->assertInstanceOf(
			'\ProofreadPage\FileProvider',
			$this->buildDummyContext()->getFileProvider()
		);
	}

	public function testGetPaginationFactory() {
		$this->assertInstanceOf(
			'\ProofreadPage\Pagination\PaginationFactory',
			$this->buildDummyContext()->getPaginationFactory()
		);
	}

	public function testCustomIndexFieldsParser() {
		$this->assertInstanceOf(
			'\ProofreadPage\Index\CustomIndexFieldsParser',
			$this->buildDummyContext()->getCustomIndexFieldsParser()
		);
	}

	public function testIndexForPageLookup() {
		$this->assertInstanceOf(
			'\ProofreadPage\Page\IndexForPageLookup',
			$this->buildDummyContext()->getIndexForPageLookup()
		);
	}

	private function buildDummyContext() {
		return new Context( 42, 44,
			new FileProviderMock( [] ), new CustomIndexFieldsParser(), new IndexForPageLookupMock( [] ),
			new IndexContentLookupMock( [] )
		);
	}
}

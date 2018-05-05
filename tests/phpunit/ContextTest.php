<?php

namespace ProofreadPage;

use Config;
use ProofreadPage\Index\CustomIndexFieldsParser;
use ProofreadPage\Index\IndexContentLookupMock;
use ProofreadPage\Page\IndexForPageLookupMock;
use ProofreadPage\Pagination\PaginationFactory;
use ProofreadPage\Page\IndexForPageLookup;
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

	public function testGetCOnfig() {
		$this->assertInstanceOf(
			Config::class,
			$this->buildDummyContext()->getConfig()
		);
	}

	public function testGetFileProvider() {
		$this->assertInstanceOf(
			FileProvider::class,
			$this->buildDummyContext()->getFileProvider()
		);
	}

	public function testGetPaginationFactory() {
		$this->assertInstanceOf(
			PaginationFactory::class,
			$this->buildDummyContext()->getPaginationFactory()
		);
	}

	public function testCustomIndexFieldsParser() {
		$this->assertInstanceOf(
			CustomIndexFieldsParser::class,
			$this->buildDummyContext()->getCustomIndexFieldsParser()
		);
	}

	public function testIndexForPageLookup() {
		$this->assertInstanceOf(
			IndexForPageLookup::class,
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

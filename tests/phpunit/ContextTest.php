<?php

namespace ProofreadPage;

use MediaWiki\Config\Config;
use ProofreadPage\Index\CustomIndexFieldsParser;
use ProofreadPage\Index\IndexContentLookup;
use ProofreadPage\Index\IndexContentLookupMock;
use ProofreadPage\Index\IndexQualityStatsLookup;
use ProofreadPage\Page\IndexForPageLookup;
use ProofreadPage\Page\IndexForPageLookupMock;
use ProofreadPage\Page\PageQualityLevelLookup;
use ProofreadPage\Page\PageQualityLevelLookupMock;
use ProofreadPage\Pagination\PaginationFactory;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Context
 */
class ContextTest extends ProofreadPageTestCase {

	public function testGetPageNamespaceId() {
		$this->assertSame( 42, $this->buildDummyContext()->getPageNamespaceId() );
	}

	public function testGetIndexNamespaceId() {
		$this->assertSame( 44, $this->buildDummyContext()->getIndexNamespaceId() );
	}

	public function testGetConfig() {
		$this->assertInstanceOf(
			Config::class,
			$this->buildDummyContext()->getConfig()
		);
	}

	public function testGetFileProviderFunction() {
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

	public function testGetCustomIndexFieldsParser() {
		$this->assertInstanceOf(
			CustomIndexFieldsParser::class,
			$this->buildDummyContext()->getCustomIndexFieldsParser()
		);
	}

	public function testGetIndexForPageLookup() {
		$this->assertInstanceOf(
			IndexForPageLookup::class,
			$this->buildDummyContext()->getIndexForPageLookup()
		);
	}

	public function testGetIndexContentLookup() {
		$this->assertInstanceOf(
			IndexContentLookup::class,
			$this->buildDummyContext()->getIndexContentLookup()
		);
	}

	public function testGetPageQualityLevelLookup() {
		$this->assertInstanceOf(
			PageQualityLevelLookup::class,
			$this->buildDummyContext()->getPageQualityLevelLookup()
		);
	}

	public function testGetIndexQualityStatsLookup() {
		$this->assertInstanceOf(
			IndexQualityStatsLookup::class,
			$this->buildDummyContext()->getIndexQualityStatsLookup()
		);
	}

	private function buildDummyContext() {
		return new Context( 42, 44,
			new FileProviderMock( [] ), new CustomIndexFieldsParser(),
			new IndexForPageLookupMock( [] ), new IndexContentLookupMock( [] ),
			new PageQualityLevelLookupMock( [] ),
			$this->createMock( IndexQualityStatsLookup::class )
		);
	}
}

<?php

namespace ProofreadPage\Scribunto;

use Content;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use ProofreadPage\Index\IndexContent;
use ProofreadPage\Page\PageContent;
use ProofreadPage\Page\PageLevel;
use RuntimeException;
use Scribunto_LuaEngineTestBase;
use WikitextContent;

/**
 * Tests the Lua library Page and Index objects.
 *
 * These are done together because they use the exact same data setup, because
 * the Index object might need pages to exist and the Page objects might need
 * the Index to exist.
 *
 * @group Database
 * @covers \ProofreadPage\ProofreadPageLuaLibrary
 */
class IndexAndPageLibraryTest extends Scribunto_LuaEngineTestBase {
	/** @inheritDoc */
	protected static $moduleName = 'IndexAndPageLibraryTests';

	/**
	 * Get a string list of links to pages (simulating an image-based index)
	 * @param string $pattern the pattern for the filenames (should have a single %d in it)
	 * @param int $count how many links you want
	 * @param int $numberingOffset any page numbering offset
	 * @return string the wikicode result
	 */
	private function getPageLinkList( string $pattern, int $count, int $numberingOffset ): string {
		$s = '';
		for ( $i = 1; $i <= $count; $i++ ) {
			$s .= sprintf( '[[' . $pattern . '|%d]]', $i, $i + $numberingOffset );
		}
		return $s;
	}

	/**
	 * Add a Page NS page to the DB
	 * @param string $title the page title (no NS)
	 * @param int $quality the page quality
	 */
	private function addPage( string $title, int $quality ): void {
		$this->addArticle(
			'Page:' . $title,
			new PageContent(
				new WikitextContent( 'header' ),
				new WikitextContent( 'body' ),
				new WikitextContent( 'footer' ),
				new PageLevel( $quality, $this->getTestSysop()->getUser() )
			)
		);
	}

	private function addArticle( string $title, Content $newContent ): void {
		$page = $this->getServiceContainer()
			->getWikiPageFactory()
			->newFromTitle( Title::newFromText( $title ) );

		if ( $page->exists() ) {
			$content = $page->getContent( RevisionRecord::RAW );
			if ( $newContent->equals( $content ) ) {
				return;
			}
			throw new RuntimeException( "duplicate article '$title' with different content" );
		}

		$status = $page->doUserEditContent(
			$newContent,
			$this->getTestSysop()->getUser(),
			'',
			EDIT_NEW | EDIT_SUPPRESS_RC | EDIT_INTERNAL
		);
		if ( !$status->isOK() ) {
			throw new RuntimeException( $status->__toString() );
		}
	}

	/** @before */
	protected function articlesSetUp(): void {
		$this->tablesUsed[] = 'pr_index';
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'pagelinks';
		$this->tablesUsed[] = 'page_props';
		$this->tablesUsed[] = 'pr_index';

		$this->addArticle(
			'MediaWiki:Proofreadpage index template',
			new WikitextContent( '{{{Pages}}}' )
		);

		// Page for getContent test
		$this->addArticle(
			'Index:Foobar',
			new IndexContent(
				[
					'title' => new WikitextContent( '[[Foo Bar]]' ),
					'Pages' => new WikitextContent( $this->getPageLinkList(
						'Page:Test %d.jpg', 6, 1 ) )
				],
				[]
			)
		);
		$this->addPage( 'Test 1.jpg', PageLevel::WITHOUT_TEXT );
		$this->addPage( 'Test 2.jpg', PageLevel::NOT_PROOFREAD );
		$this->addPage( 'Test 3.jpg', PageLevel::PROBLEMATIC );
		$this->addPage( 'Test 4.jpg', PageLevel::PROOFREAD );
		$this->addPage( 'Test 5.jpg', PageLevel::PROOFREAD );
		// page 6 is missing
		$this->addPage( 'Unconnected page.jpg', PageLevel::PROBLEMATIC );
	}

	/** @inheritDoc */
	protected function getTestModules() {
		return parent::getTestModules() + [
			'IndexAndPageLibraryTests' => __DIR__ . '/IndexAndPageLibraryTests.lua',
		];
	}
}

<?php

use ProofreadPage\Index\IndexContent;

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ProofreadPage\Api\ApiQueryPagesInIndex
 */
class ApiQueryProofreadPagesInIndexTest extends ApiTestCase {

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

	private function addArticle( string $title, $newContent ) {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( $title ) );
		$status = $page->doUserEditContent(
			$newContent,
			$this->getTestSysop()->getUser(),
			'',
			EDIT_NEW | EDIT_SUPPRESS_RC | EDIT_INTERNAL
		);
	}

	private function articlesSetUp(): void {
		// Dummy image-based index
		$this->addArticle(
			'Index:Foobar',
			new IndexContent(
				[
					'title' => new WikitextContent( '[[Foo Bar]]' ),
					'Pages' => new WikitextContent( $this->getPageLinkList(
						'Page:Test %d.jpg', 3, 1 ) )
				],
				[]
			)
		);
	}

	protected function setUp(): void {
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'pr_index';

		parent::setUp();
		$this->articlesSetUp();
	}

	/**
	 * @group medium
	 */
	public function testMissingIndex() {
		$pageName = 'Index:ThisDoesNotExist.djvu';

		$apiResult = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'proofreadpagesinindex',
			'prppiititle' => $pageName,
		] );

		$this->assertArrayHasKey( 'query', $apiResult[ 0 ] );
		$this->assertArrayHasKey( 'proofreadpagesinindex', $apiResult[ 0 ][ 'query' ] );

		$this->assertCount( 0, $apiResult[ 0 ][ 'query' ][ 'proofreadpagesinindex' ] );
	}

	/**
	 * @group medium
	 */
	public function testBadIndex() {
		$pageName = 'Page:ThisIsNotAnIndex.djvu';

		try {
			$apiResult = $this->doApiRequest( [
				'action' => 'query',
				'list' => 'proofreadpagesinindex',
				'prppiititle' => $pageName,
			] );
			$this->fail( "API did not return an error when given an invalid index title" );
		} catch ( ApiUsageException $ex ) {
			$this->assertTrue( ApiTestCase::apiExceptionHasCode(
				$ex, 'proofreadpage-invalidindex' ) );
		}
	}

	/**
	 * Test the default invocation
	 *
	 * @group medium
	 */
	public function testExistingIndexDefault() {
		$pageName = 'Index:Foobar';

		$apiResult = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'proofreadpagesinindex',
			'prppiititle' => $pageName,
		] );

		$this->assertArrayHasKey( 'query', $apiResult[ 0 ] );
		$this->assertArrayHasKey( 'proofreadpagesinindex', $apiResult[ 0 ][ 'query' ] );

		$expected = [
			[
				'pageoffset' => 1,
				'title' => 'Page:Test 1.jpg',
				'pageid' => 0
			],
			[
				'pageoffset' => 2,
				'title' => 'Page:Test 2.jpg',
				'pageid' => 0
			],
			[
				'pageoffset' => 3,
				'title' => 'Page:Test 3.jpg',
				'pageid' => 0
			],
		];

		$this->assertEquals( $expected, $apiResult[ 0 ][ 'query' ][ 'proofreadpagesinindex' ] );
	}
}

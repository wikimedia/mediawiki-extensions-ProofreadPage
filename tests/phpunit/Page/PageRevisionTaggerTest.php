<?php

namespace ProofreadPage\Page;

use ChangeTags;
use ProofreadPage\Tags;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @group Database
 * @covers \ProofreadPage\Page\PageRevisionTagger
 */
class PageRevisionTaggerTest extends ProofreadPageTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->tablesUsed[] = 'change_tag';
		$this->tablesUsed[] = 'revision';

		// don't care about user permissions here
		$this->mergeMwGlobalArrayValue(
			'wgGroupPermissions',
			[
				'*' => [ 'pagequality' => true ],
			]
		);
		$this->setMwGlobals( 'wgProofreadPageUseStatusChangeTags', true );
	}

	private function doEditAtLevel( $page, $text, $level ) {
		return $this->editPage( $page,
			$this->buildPageContent( 'head', $text, 'foot', $level, 'John' )
		);
	}

	private function assertEditTags( $status, $expectedTags ) {
		$revisionId = $status->value['revision-record']->getId();
		$realTags = ChangeTags::getTags( $this->db, null, $revisionId );
		$this->assertEquals( $expectedTags, $realTags );
	}

	public function testAddingPageLevelTags() {
		$page = 'Page:Test.djvu/123';

		// create the page gets initial level
		$status = $this->doEditAtLevel( $page, 'body1', PageLevel::NOT_PROOFREAD );
		$this->assertEditTags( $status, [ Tags::NOT_PROOFREAD_TAG ] );

		// didn't change status, so no tag
		$status = $this->doEditAtLevel( $page, 'body2', PageLevel::NOT_PROOFREAD );
		$this->assertEditTags( $status, [] );

		// change the status - a new tag should be generated
		$status = $this->doEditAtLevel( $page, 'body3', PageLevel::PROOFREAD );
		$this->assertEditTags( $status, [ Tags::PROOFREAD_TAG ] );
	}
}

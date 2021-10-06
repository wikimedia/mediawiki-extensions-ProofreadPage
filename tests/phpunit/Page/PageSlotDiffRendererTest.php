<?php

namespace ProofreadPage\Page;

use ProofreadPageTestCase;
use RequestContext;
use WikitextContent;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Page\PageSlotDiffRenderer
 */
class PageSlotDiffRendererTest extends ProofreadPageTestCase {

	public function getDiffProvider() {
		return [
			[
				$this->newContent( 'header', 'body', 'footer', 2 ),
				$this->newContent( 'header', 'body', 'footer', 2 ),
				''
			],
			[
				null,
				$this->newContent( 'header', 'body2', 'footer', 2 ),
				'Page statusPage status-Not proofread+Problematic' .
				'Header (noinclude):Header (noinclude): + header ' .
				'Page body (to be transcluded):Page body (to be transcluded): + body2 ' .
				'Footer (noinclude):Footer (noinclude): + footer '
			],
			[
				$this->newContent( 'header', 'body', 'footer', 2 ),
				null,
				'Page statusPage status-Problematic+Not proofread' .
				'Header (noinclude):Header (noinclude): - header ' .
				'Page body (to be transcluded):Page body (to be transcluded): - body ' .
				'Footer (noinclude):Footer (noinclude): - footer '
			]
		];
	}

	/**
	 * @dataProvider getDiffProvider
	 */
	public function testGetDiff( $oldContent, $newContent, $expected ) {
		if ( phpversion( 'wikidiff2' ) === false ) {
			$this->markTestSkipped( 'Skip test, since wikidiff2 is not installed' );
		}

		$this->markTestSkipped( 'Skip test, since wikidiff2 now works differently; see T292676' );
		// FIXME: Re-write this with assertMatchesRegularExpression() instead
		$diffRender = new PageSlotDiffRenderer( RequestContext::getMain() );
		$this->assertSame(
			$expected,
			$this->getPlainDiff( $diffRender->getDiff( $oldContent, $newContent ) )
		);
	}

	/**
	 * Convert a HTML diff to a human-readable format and hopefully make the test less fragile.
	 *
	 * Copied from TextSlotDiffRendererTest
	 *
	 * @param string $diff
	 * @return string
	 */
	private function getPlainDiff( $diff ) {
		$replacements = [
			html_entity_decode( '&nbsp;' ) => ' ',
			'&#160;' => ' ',
			html_entity_decode( '&minus;' ) => '-'
		];
		$diff = strip_tags( $diff );
		$diff = str_replace( array_keys( $replacements ), array_values( $replacements ), $diff );
		return preg_replace( '/\s+/', ' ', $diff );
	}

	private function newContent(
		$header = '', $body = '', $footer = '', $level = 1, $proofreader = null
	) {
		return new PageContent(
			new WikitextContent( $header ), new WikitextContent( $body ), new WikitextContent( $footer ),
			new PageLevel( $level, PageLevel::getUserFromUserName( $proofreader ) )
		);
	}
}

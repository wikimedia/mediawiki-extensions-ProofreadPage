<?php

namespace ProofreadPage\Index;

use ProofreadPageTestCase;
use Title;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Index\IndexTemplateStyles
 */
class IndexTemplateStylesTest extends ProofreadPageTestCase {

	public function indexStylesProvider() {
		$nsIndex = $this->getIndexNamespaceId();
		return [
			// A normal index should point to its style subpage
			[
				Title::makeTitle( $nsIndex, 'Foo.djvu' ),
				Title::makeTitle( $nsIndex, 'Foo.djvu/styles.css' ),
				Title::makeTitle( $nsIndex, 'Foo.djvu' ),
			],
			// check that a subpage doesn't point to it's own subpage
			[
				Title::makeTitle( $nsIndex, 'Foo.djvu/styles.css' ),
				Title::makeTitle( $nsIndex, 'Foo.djvu/styles.css' ),
				Title::makeTitle( $nsIndex, 'Foo.djvu' ),
			],
			// check a subpage that isn't the styles page
			[
				Title::makeTitle( $nsIndex, 'Foo.djvu/data.json' ),
				Title::makeTitle( $nsIndex, 'Foo.djvu/styles.css' ),
				Title::makeTitle( $nsIndex, 'Foo.djvu' ),
			],
		];
	}

	/**
	 * @dataProvider indexStylesProvider
	 */
	public function testStylesPageTitles( $indexTitle, $expStylesTitle, $expAssociatedIndex ) {
		$indexTs = new IndexTemplateStyles( $indexTitle );

		$stylesTitle = $indexTs->getTemplateStylesPage();

		if ( $indexTs->hasStylesSupport() ) {
			$associatedIndex = $indexTs->getAssociatedIndexPage();

			$this->assertTrue( $expStylesTitle->equals( $stylesTitle ) );
			$this->assertTrue( $associatedIndex->equals( $expAssociatedIndex ) );
		} else {
			// no support, so check expected null
			$this->assertSame( null, $stylesTitle );
		}
	}
}

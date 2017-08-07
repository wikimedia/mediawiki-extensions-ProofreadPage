<?php

namespace ProofreadPage\Page;

use ProofreadIndexPageTest;
use ProofreadPagePageTest;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers ProofreadPagePage
 */
class PageDisplayHandlerTest extends ProofreadPageTestCase {

	public function testGetImageWidth() {
		$handler = new PageDisplayHandler( $this->getContext() );

		$index = ProofreadIndexPageTest::newIndexPage( 'Test', "{{\n|width= 500 \n}}" );
		$page = ProofreadPagePageTest::newPagePage( 'Test.jpg', $index );
		$this->assertEquals( 500, $handler->getImageWidth( $page ) );

		$index = ProofreadIndexPageTest::newIndexPage( 'Test', "{{\n|title=500\n}}" );
		$page = ProofreadPagePageTest::newPagePage( 'Test.jpg', $index );
		$this->assertEquals( PageDisplayHandler::DEFAULT_IMAGE_WIDTH, $handler->getImageWidth( $page ) );
	}

	public function testGetCustomCss() {
		$handler = new PageDisplayHandler( $this->getContext() );

		$index = ProofreadIndexPageTest::newIndexPage( 'Test', "{{\n|CSS= width:300px; \n}}" );
		$page = ProofreadPagePageTest::newPagePage( 'Test.jpg', $index );
		$this->assertEquals( 'width:300px;', $handler->getCustomCss( $page ) );

		$index = ProofreadIndexPageTest::newIndexPage(
			'Test', "{{\n|CSS= background: url('/my-bad-url.jpg');\n}}"
		);
		$page = ProofreadPagePageTest::newPagePage( 'Test.jpg', $index );
		$this->assertEquals( '/* insecure input */', $handler->getCustomCss( $page ) );

		$index = ProofreadIndexPageTest::newIndexPage(
			'Test', "{{\n|CSS= width:300px;<style> \n}}"
		);
		$page = ProofreadPagePageTest::newPagePage( 'Test.jpg', $index );
		$this->assertEquals( 'width:300px;&lt;style&gt;', $handler->getCustomCss( $page ) );
	}
}

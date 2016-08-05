<?php

namespace ProofreadPage\Pagination;

use MediaHandler;
use ProofreadIndexPageTest;
use ProofreadPagePage;
use ProofreadPageTestCase;
use Title;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\PaginationFactory
 */
class PaginationFactoryTest extends ProofreadPageTestCase {

	public function testGetPaginationWithPagelist() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$page = ProofreadIndexPageTest::newIndexPage( 'LoremIpsum.djvu', "{{\n|Pages=<pagelist 1to2=-/> <pagelist 3=1 4to5=roman />\n|Author=[[Author:Me]]\n}}" );
		$pageList = new PageList( [ '1to2' => '-', '3' => '1', '4to5' => 'roman' ] );
		$pagination = new FilePagination(
			$page,
			$pageList,
			$this->getContext()->getFileProvider()->getFileFromTitle( Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' ) ),
			$this->getContext()
		);
		$this->assertEquals( $pagination, $this->getContext()->getPaginationFactory()->getPaginationForIndexPage( $page ) );
	}

	public function testGetPaginationWithoutPagelist() {
		$page = ProofreadIndexPageTest::newIndexPage( 'Test', "{{\n|Pages=[[Page:Test 1.jpg|TOC]] [[Page:Test 2.tiff|1]] [[Page:Test:3.png|2]]\n|Author=[[Author:Me]]\n}}" );
		$pagination = new PagePagination(
			$page,
			[
				new ProofreadPagePage( Title::newFromText( 'Page:Test 1.jpg' ), $page ),
				new ProofreadPagePage( Title::newFromText( 'Page:Test 2.tiff' ), $page ),
				new ProofreadPagePage( Title::newFromText( 'Page:Test:3.png' ), $page )
			],
			[
				new PageNumber( 'TOC' ),
				new PageNumber( '1' ),
				new PageNumber( '2' )
			]
		);
		$this->assertEquals( $pagination, $this->getContext()->getPaginationFactory()->getPaginationForIndexPage( $page ) );
	}
}

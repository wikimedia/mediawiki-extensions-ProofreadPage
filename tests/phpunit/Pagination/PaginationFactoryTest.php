<?php

namespace ProofreadPage\Pagination;

use MediaHandler;
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
		$page = $this->newIndexPage(
			'LoremIpsum.djvu',
			"{{\n|Pages=<pagelist 1to2=-/> <pagelist 3=1 4to5=roman />\n|Author=[[Author:Me]]\n}}"
		);
		$pageList = new PageList( [ '1to2' => '-', '3' => '1', '4to5' => 'roman' ] );
		$pagination = new FilePagination(
			$page,
			$pageList,
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);
		$this->assertEquals(
			$pagination,
			$this->getContext()->getPaginationFactory()->getPaginationForIndexPage( $page )
		);
	}

	public function testGetPaginationWithoutPagelist() {
		$index = $this->newIndexPage(
			'Test',
			"{{\n|Pages=[[Page:Test 1.jpg|TOC]] [[Page:Test 2.tiff|1]] " .
			"[[Page:Test:3.png|2]]\n|Author=[[Author:Me]]\n}}"
		);
		$pagination = new PagePagination(
			$index,
			[
				$this->newPagePage( Title::newFromText( 'Page:Test 1.jpg' ) ),
				$this->newPagePage( Title::newFromText( 'Page:Test 2.tiff' ) ),
				$this->newPagePage( Title::newFromText( 'Page:Test:3.png' ) )
			],
			[
				new PageNumber( 'TOC' ),
				new PageNumber( '1' ),
				new PageNumber( '2' )
			]
		);
		$this->assertEquals(
			$pagination,
			$this->getContext( [
				'Page:Test_1.jpg' => $index,
				'Page:Test_2.tiff' => $index,
				'Page:Test:3.png' => $index,
			] )->getPaginationFactory()->getPaginationForIndexPage( $index )
		);
	}
}

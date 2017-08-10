<?php

namespace ProofreadPage\Pagination;

use MediaHandler;
use ProofreadPage\Index\IndexContent;
use ProofreadPageTestCase;
use Title;
use WikitextContent;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\PaginationFactory
 */
class PaginationFactoryTest extends ProofreadPageTestCase {

	public function testGetPaginationWithPagelist() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$context = $this->getContext( [], [
			'LoremIpsum.djvu' => new IndexContent( [
				'Pages' => new WikitextContent( '<pagelist 1to2=-/> <pagelist 3=1 4to5=roman />' ),
				'Author' => new WikitextContent( '[[Author:Me]]' )
			] )
		] );
		$pageList = new PageList( [ '1to2' => '-', '3' => '1', '4to5' => 'roman' ] );
		$pagination = new FilePagination(
			$this->newIndexPage( 'LoremIpsum.djvu' ),
			$pageList,
			$context->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$context
		);
		$this->assertEquals(
			$pagination,
			$context->getPaginationFactory()->getPaginationForIndexPage(
				$this->newIndexPage( 'LoremIpsum.djvu' )
			)
		);
	}

	public function testGetPaginationWithoutPagelist() {
		$index = $this->newIndexPage( 'Test' );
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
			$this->getContext( [], [
				'Test' => new IndexContent( [
					'Pages' => new WikitextContent(
						'[[Page:Test 1.jpg|TOC]] [[Page:Test 2.tiff|1]][[Page:Test:3.png|2]]'
					),
					'Author' => new WikitextContent( '[[Author:Me]]' )
				] )
			] )->getPaginationFactory()->getPaginationForIndexPage( $index )
		);
	}
}

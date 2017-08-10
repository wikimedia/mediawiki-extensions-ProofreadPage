<?php

namespace ProofreadPage\Page;

use ProofreadPage\Index\IndexContent;
use ProofreadPageTestCase;
use WikitextContent;

/**
 * @group ProofreadPage
 * @covers ProofreadPagePage
 */
class PageDisplayHandlerTest extends ProofreadPageTestCase {

	public function testGetImageWidth() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => $this->newIndexPage( 'Test' )
		], [
			'Test' => new IndexContent( [ 'width' => new WikitextContent( '500' ) ] )
		] ) );
		$this->assertEquals( 500, $handler->getImageWidth( $this->newPagePage( 'Test.jpg' ) ) );
	}

	public function testGetImageWidthWithDefault() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => $this->newIndexPage( 'Test' )
		], [
			'Test' => new IndexContent( [ 'title' => new WikitextContent( '500' ) ] )
		] ) );
		$this->assertEquals(
			PageDisplayHandler::DEFAULT_IMAGE_WIDTH,
			$handler->getImageWidth( $this->newPagePage( 'Test.jpg' ) )
		);
	}

	public function testGetCustomCss() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => $this->newIndexPage( 'Test' )
		], [
			'Test' => new IndexContent( [
				'CSS' => new WikitextContent( 'width:300px;' )
			] )
		] ) );
		$this->assertEquals(
			'width:300px;',
			$handler->getCustomCss( $this->newPagePage( 'Test.jpg' ) )
		);
	}

	public function testGetCustomCssWithInsecureInput() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => $this->newIndexPage( 'Test' )
		], [
			'Test' => new IndexContent( [
				'CSS' => new WikitextContent( 'background: url(\'/my-bad-url.jpg\');' )
			] )
		] ) );
		$this->assertEquals(
			'/* insecure input */',
			$handler->getCustomCss( $this->newPagePage( 'Test.jpg' ) )
		);
	}

	public function testGetCustomCssWithEscaping() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => $this->newIndexPage( 'Test' )
		], [
			'Test' => new IndexContent( [
				'CSS' => new WikitextContent( 'width:300px;<style>' )
			] )
		] ) );
		$this->assertEquals(
			'width:300px;&lt;style&gt;',
			$handler->getCustomCss( $this->newPagePage( 'Test.jpg' ) )
		);
	}
}

<?php

namespace ProofreadPage\Page;

use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers ProofreadPagePage
 */
class PageDisplayHandlerTest extends ProofreadPageTestCase {

	public function testGetImageWidth() {
		$index = $this->newIndexPage( 'Test', "{{\n|width= 500 \n}}" );
		$page = $this->newPagePage( 'Test.jpg' );
		$handler = new PageDisplayHandler( $this->getContext( [
			$page->getTitle()->getDBkey() => $index
		] ) );
		$this->assertEquals( 500, $handler->getImageWidth( $page ) );
	}

	public function testGetImageWidthWithDefault() {
		$index = $this->newIndexPage( 'Test', "{{\n|title=500\n}}" );
		$page = $this->newPagePage( 'Test.jpg' );
		$handler = new PageDisplayHandler( $this->getContext( [
			$page->getTitle()->getDBkey() => $index
		] ) );
		$this->assertEquals( PageDisplayHandler::DEFAULT_IMAGE_WIDTH, $handler->getImageWidth( $page ) );
	}

	public function testGetCustomCss() {
		$index = $this->newIndexPage( 'Test', "{{\n|CSS= width:300px; \n}}" );
		$page = $this->newPagePage( 'Test.jpg' );
		$handler = new PageDisplayHandler( $this->getContext( [
			$page->getTitle()->getDBkey() => $index
		] ) );
		$this->assertEquals( 'width:300px;', $handler->getCustomCss( $page ) );
	}

	public function testGetCustomCssWithInsecureInput() {
		$index = $this->newIndexPage(
			'Test', "{{\n|CSS= background: url('/my-bad-url.jpg');\n}}"
		);
		$page = $this->newPagePage( 'Test.jpg' );
		$handler = new PageDisplayHandler( $this->getContext( [
			$page->getTitle()->getDBkey() => $index
		] ) );
		$this->assertEquals( '/* insecure input */', $handler->getCustomCss( $page ) );
	}

	public function testGetCustomCssWithEscaping() {
		$index = $this->newIndexPage(
			'Test', "{{\n|CSS= width:300px;<style> \n}}"
		);
		$page = $this->newPagePage( 'Test.jpg' );
		$handler = new PageDisplayHandler( $this->getContext( [
			$page->getTitle()->getDBkey() => $index
		] ) );
		$this->assertEquals( 'width:300px;&lt;style&gt;', $handler->getCustomCss( $page ) );
	}
}

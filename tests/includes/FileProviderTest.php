<?php

namespace ProofreadPage;

use File;
use ProofreadIndexPage;
use ProofreadPagePage;
use ProofreadPageTestCase;
use Title;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\FileProvider
 */
class FileProviderTest extends ProofreadPageTestCase {

	/**
	 * @var FileProvider
	 */
	protected $fileProvider;

	protected function setUp() {
		parent::setUp();

		$this->fileProvider = new FileProviderMock( array(
			$this->getFileFromName( 'LoremIpsum.djvu' ),
			$this->getFileFromName( 'Test.jpg' )
		) );
	}

	private function getFileFromName( $fileName ) {
		return $this->getContext()->getFileProvider()->getFileFromTitle( Title::makeTitle( NS_MEDIA, $fileName ) );
	}

	/**
	 * @dataProvider indexFileProvider
	 */
	public function testGetForIndexPage( ProofreadIndexPage $index, File $file ) {
		$this->assertEquals(
			$file,
			$this->fileProvider->getForIndexPage( $index )
		);
	}

	public function indexFileProvider() {
		return array(
			array(
				ProofreadIndexPage::newFromTitle( Title::makeTitle( 252, 'LoremIpsum.djvu' ) ),
				$this->getFileFromName( 'LoremIpsum.djvu' )
			),
		);
	}

	/**
	 * @expectedException \ProofreadPage\FileNotFoundException
	 * @dataProvider indexFileNotFoundProvider
	 */
	public function testGetForIndexPageWithFileNotFound( ProofreadIndexPage  $index ) {
		$this->fileProvider->getForIndexPage( $index );
	}

	public function indexFileNotFoundProvider() {
		return array(
			array(
				ProofreadIndexPage::newFromTitle( Title::makeTitle( 252, 'LoremIpsum2.djvu' ) )
			),
			array(
				ProofreadIndexPage::newFromTitle( Title::makeTitle( 252, 'Test' ) )
			),
		);
	}

	/**
	 * @dataProvider pageFileProvider
	 */
	public function testGetForPagePage( ProofreadPagePage $page, File $file ) {
		$this->assertEquals(
			$file,
			$this->fileProvider->getForPagePage( $page )
		);
	}

	public function pageFileProvider() {
		return array(
			array(
				ProofreadPagePage::newFromTitle( Title::makeTitle( 250, 'LoremIpsum.djvu/4' ) ),
				$this->getFileFromName( 'LoremIpsum.djvu' )
			),
			array(
				ProofreadPagePage::newFromTitle( Title::makeTitle( 250, 'LoremIpsum.djvu/djvu/1' ) ),
				$this->getFileFromName( 'LoremIpsum.djvu' )
			),
			array(
				ProofreadPagePage::newFromTitle( Title::makeTitle( 250, 'LoremIpsum.djvu' ) ),
				$this->getFileFromName( 'LoremIpsum.djvu' )
			),
			array(
				ProofreadPagePage::newFromTitle( Title::makeTitle( 250, 'Test.jpg' ) ),
				$this->getFileFromName( 'Test.jpg' )
			),
		);
	}

	/**
	 * @expectedException \ProofreadPage\FileNotFoundException
	 * @dataProvider pageFileNotFoundProvider
	 */
	public function testGetForPagePageWithFileNotFound( ProofreadPagePage  $page ) {
		$this->fileProvider->getForPagePage( $page );
	}

	public function pageFileNotFoundProvider() {
		return array(
			array(
				ProofreadPagePage::newFromTitle( Title::makeTitle( 252, 'LoremIpsum2.djvu/4' ) )
			),
			array(
				ProofreadPagePage::newFromTitle( Title::makeTitle( 252, 'Test' ) )
			),
		);
	}
}
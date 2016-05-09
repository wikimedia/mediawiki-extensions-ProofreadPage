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

		$this->fileProvider = new FileProviderMock( [
			$this->getFileFromName( 'LoremIpsum.djvu' ),
			$this->getFileFromName( 'Test.jpg' )
		] );
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
		return [
			[
				ProofreadIndexPage::newFromTitle( Title::makeTitle( 252, 'LoremIpsum.djvu' ) ),
				$this->getFileFromName( 'LoremIpsum.djvu' )
			],
		];
	}

	/**
	 * @expectedException \ProofreadPage\FileNotFoundException
	 * @dataProvider indexFileNotFoundProvider
	 */
	public function testGetForIndexPageWithFileNotFound( ProofreadIndexPage  $index ) {
		$this->fileProvider->getForIndexPage( $index );
	}

	public function indexFileNotFoundProvider() {
		return [
			[
				ProofreadIndexPage::newFromTitle( Title::makeTitle( 252, 'LoremIpsum2.djvu' ) )
			],
			[
				ProofreadIndexPage::newFromTitle( Title::makeTitle( 252, 'Test' ) )
			],
		];
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
		return [
			[
				ProofreadPagePage::newFromTitle( Title::makeTitle( 250, 'LoremIpsum.djvu/4' ) ),
				$this->getFileFromName( 'LoremIpsum.djvu' )
			],
			[
				ProofreadPagePage::newFromTitle( Title::makeTitle( 250, 'LoremIpsum.djvu/djvu/1' ) ),
				$this->getFileFromName( 'LoremIpsum.djvu' )
			],
			[
				ProofreadPagePage::newFromTitle( Title::makeTitle( 250, 'LoremIpsum.djvu' ) ),
				$this->getFileFromName( 'LoremIpsum.djvu' )
			],
			[
				ProofreadPagePage::newFromTitle( Title::makeTitle( 250, 'Test.jpg' ) ),
				$this->getFileFromName( 'Test.jpg' )
			],
		];
	}

	/**
	 * @expectedException \ProofreadPage\FileNotFoundException
	 * @dataProvider pageFileNotFoundProvider
	 */
	public function testGetForPagePageWithFileNotFound( ProofreadPagePage  $page ) {
		$this->fileProvider->getForPagePage( $page );
	}

	public function pageFileNotFoundProvider() {
		return [
			[
				ProofreadPagePage::newFromTitle( Title::makeTitle( 252, 'LoremIpsum2.djvu/4' ) )
			],
			[
				ProofreadPagePage::newFromTitle( Title::makeTitle( 252, 'Test' ) )
			],
		];
	}
}

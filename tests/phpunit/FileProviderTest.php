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

	private function getFileFromName( $fileName ) {
		return $this->getContext()->getFileProvider()->getFileFromTitle(
			Title::makeTitle( NS_MEDIA, $fileName )
		);
	}

	/**
	 * @dataProvider indexFileProvider
	 */
	public function testGetForIndexPage(
		ProofreadIndexPage $index, File $file, FileProvider $fileProvider
	) {
		$this->assertEquals( $file, $fileProvider->getForIndexPage( $index ) );
	}

	public function indexFileProvider() {
		$fileProvider = new FileProviderMock( [
			$this->getFileFromName( 'LoremIpsum.djvu' ),
			$this->getFileFromName( 'Test.jpg' )
		] );

		return [
			[
				$this->newIndexPage( 'LoremIpsum.djvu' ),
				$this->getFileFromName( 'LoremIpsum.djvu' ),
				$fileProvider
			],
		];
	}

	/**
	 * @expectedException \ProofreadPage\FileNotFoundException
	 * @dataProvider indexFileNotFoundProvider
	 */
	public function testGetForIndexPageWithFileNotFound(
		ProofreadIndexPage $index, FileProvider $fileProvider
	) {
		$fileProvider->getForIndexPage( $index );
	}

	public function indexFileNotFoundProvider() {
		$fileProvider = new FileProviderMock( [
			$this->getFileFromName( 'LoremIpsum.djvu' ),
			$this->getFileFromName( 'Test.jpg' )
		] );

		return [
			[
				$this->newIndexPage( 'LoremIpsum2.djvu' ),
				$fileProvider
			],
			[
				$this->newIndexPage( 'Test' ),
				$fileProvider
			],
		];
	}

	/**
	 * @dataProvider pageFileProvider
	 */
	public function testGetForPagePage(
		ProofreadPagePage $page, File $file, FileProvider $fileProvider
	) {
		$this->assertEquals( $file, $fileProvider->getForPagePage( $page ) );
	}

	public function pageFileProvider() {
		$fileProvider = new FileProviderMock( [
			$this->getFileFromName( 'LoremIpsum.djvu' ),
			$this->getFileFromName( 'Test.jpg' )
		] );

		return [
			[
				$this->newPagePage( 'LoremIpsum.djvu/4' ),
				$this->getFileFromName( 'LoremIpsum.djvu' ),
				$fileProvider
			],
			[
				$this->newPagePage( 'LoremIpsum.djvu/djvu/1' ),
				$this->getFileFromName( 'LoremIpsum.djvu' ),
				$fileProvider
			],
			[
				$this->newPagePage( 'LoremIpsum.djvu' ),
				$this->getFileFromName( 'LoremIpsum.djvu' ),
				$fileProvider
			],
			[
				$this->newPagePage( 'Test.jpg' ),
				$this->getFileFromName( 'Test.jpg' ),
				$fileProvider
			],
		];
	}

	/**
	 * @expectedException \ProofreadPage\FileNotFoundException
	 * @dataProvider pageFileNotFoundProvider
	 */
	public function testGetForPagePageWithFileNotFound(
		ProofreadPagePage $page, FileProvider $fileProvider
	) {
		$fileProvider->getForPagePage( $page );
	}

	public function pageFileNotFoundProvider() {
		$fileProvider = new FileProviderMock( [
			$this->getFileFromName( 'LoremIpsum.djvu' ),
			$this->getFileFromName( 'Test.jpg' )
		] );

		return [
			[
				$this->newPagePage( 'LoremIpsum2.djvu/4' ),
				$fileProvider
			],
			[
				$this->newPagePage( 'Test' ),
				$fileProvider
			],
		];
	}

	public function testGetPageNumberForPagePage() {
		$fileProvider = new FileProviderMock( [] );
		$this->assertEquals( 1, $fileProvider->getPageNumberForPagePage(
			$this->newPagePage( 'Test.djvu/1' )
		) );
	}

	/**
	 * @expectedException \ProofreadPage\PageNumberNotFoundException
	 */
	public function testGetPageNumberForPageNumberNotFound() {
		$fileProvider = new FileProviderMock( [] );
		$fileProvider->getPageNumberForPagePage( $this->newPagePage( 'Test.djvu' ) );
	}

	/**
	 * @expectedException \ProofreadPage\PageNumberNotFoundException
	 */
	public function testGetPageNumberForPageNotANumber() {
		$fileProvider = new FileProviderMock( [] );
		$fileProvider->getPageNumberForPagePage( $this->newPagePage( 'Test.djvu/foo' ) );
	}

	/**
	 * @expectedException \ProofreadPage\PageNumberNotFoundException
	 */
	public function testGetPageNumberForPageBadNumber() {
		$fileProvider = new FileProviderMock( [] );
		$fileProvider->getPageNumberForPagePage( $this->newPagePage( 'Test.djvu/-1' ) );
	}
}

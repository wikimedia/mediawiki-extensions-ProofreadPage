<?php

namespace ProofreadPage;

use MediaWiki\FileRepo\File\File;
use MediaWiki\Title\Title;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\FileProvider
 */
class FileProviderTest extends ProofreadPageTestCase {

	private static function getFileFromName( $fileName ) {
		return self::getContext()->getFileProvider()->getFileFromTitle(
			Title::makeTitle( NS_MEDIA, $fileName )
		);
	}

	/**
	 * @dataProvider indexFileProvider
	 */
	public function testGetFileForIndexTitle(
		Title $indexTitle, File $file, FileProvider $fileProvider
	) {
		$this->assertSame( $file, $fileProvider->getFileForIndexTitle( $indexTitle ) );
	}

	public static function indexFileProvider() {
		$fileProvider = new FileProviderMock( [
			self::getFileFromName( 'LoremIpsum.djvu' ),
			self::getFileFromName( 'Test.jpg' )
		] );

		return [
			[
				Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.djvu' ),
				self::getFileFromName( 'LoremIpsum.djvu' ),
				$fileProvider
			],
		];
	}

	/**
	 * @dataProvider indexFileNotFoundProvider
	 */
	public function testGetFileForIndexPageWithFileNotFound(
		Title $indexTitle, FileProvider $fileProvider
	) {
		$this->expectException( FileNotFoundException::class );
		$fileProvider->getFileForIndexTitle( $indexTitle );
	}

	public static function indexFileNotFoundProvider() {
		$fileProvider = new FileProviderMock( [
			self::getFileFromName( 'LoremIpsum.djvu' ),
			self::getFileFromName( 'Test.jpg' )
		] );

		return [
			[
				Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum2.djvu' ),
				$fileProvider
			],
			[
				Title::makeTitle( self::getIndexNamespaceId(), 'Test' ),
				$fileProvider
			],
		];
	}

	/**
	 * @dataProvider pageFileProvider
	 */
	public function testFileGetForPageTitle(
		Title $pageTitle, File $file, FileProvider $fileProvider
	) {
		$this->assertSame( $file, $fileProvider->getFileForPageTitle( $pageTitle ) );
	}

	public static function pageFileProvider() {
		$fileProvider = new FileProviderMock( [
			self::getFileFromName( 'LoremIpsum.djvu' ),
			self::getFileFromName( 'Test.jpg' )
		] );

		return [
			[
				Title::makeTitle( self::getPageNamespaceId(), 'LoremIpsum.djvu/4' ),
				self::getFileFromName( 'LoremIpsum.djvu' ),
				$fileProvider
			],
			[
				Title::makeTitle( self::getPageNamespaceId(), 'LoremIpsum.djvu/djvu/1' ),
				self::getFileFromName( 'LoremIpsum.djvu' ),
				$fileProvider
			],
			[
				Title::makeTitle( self::getPageNamespaceId(), 'LoremIpsum.djvu' ),
				self::getFileFromName( 'LoremIpsum.djvu' ),
				$fileProvider
			],
			[
				Title::makeTitle( self::getPageNamespaceId(), 'Test.jpg' ),
				self::getFileFromName( 'Test.jpg' ),
				$fileProvider
			],
		];
	}

	/**
	 * @dataProvider pageFileNotFoundProvider
	 */
	public function testGetForPagePageWithFileNotFound(
		Title $pageTitle, FileProvider $fileProvider
	) {
		$this->expectException( FileNotFoundException::class );
		$fileProvider->getFileForPageTitle( $pageTitle );
	}

	public static function pageFileNotFoundProvider() {
		$fileProvider = new FileProviderMock( [
			self::getFileFromName( 'LoremIpsum.djvu' ),
			self::getFileFromName( 'Test.jpg' )
		] );

		return [
			[
				Title::makeTitle( self::getPageNamespaceId(), 'LoremIpsum2.djvu/4' ),
				$fileProvider
			],
			[
				Title::makeTitle( self::getPageNamespaceId(), 'Test' ),
				$fileProvider
			],
		];
	}

	public function testGetPageNumberForPageTitle() {
		$fileProvider = new FileProviderMock( [] );
		$this->assertSame( 1, $fileProvider->getPageNumberForPageTitle(
			$this->makeEnglishPagePageTitle( 'Test.djvu/1' )
		) );
	}

	public function testGetPageNumberForPageNumberNotFound() {
		$fileProvider = new FileProviderMock( [] );
		$this->expectException( PageNumberNotFoundException::class );
		$fileProvider->getPageNumberForPageTitle(
			Title::makeTitle( self::getPageNamespaceId(), 'Test.djvu' )
		);
	}

	public function testGetPageNumberForPageNotANumber() {
		$fileProvider = new FileProviderMock( [] );
		$this->expectException( PageNumberNotFoundException::class );
		$fileProvider->getPageNumberForPageTitle(
			$this->makeEnglishPagePageTitle( 'Test.djvu/foo' )
		);
	}

	public function testGetPageNumberForPageBadNumber() {
		$fileProvider = new FileProviderMock( [] );
		$this->expectException( PageNumberNotFoundException::class );
		$fileProvider->getPageNumberForPageTitle(
			$this->makeEnglishPagePageTitle( 'Test.djvu/-1' )
		);
	}
}

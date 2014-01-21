<?php

namespace ProofreadPage;

use File;
use ProofreadIndexPage;
use ProofreadPagePage;
use ProofreadPageTestCase;
use Title;
use UnregisteredLocalFile;

/**
 * @group ProofreadPage
 * @covers FileProvider
 */
class FileProviderTest extends ProofreadPageTestCase {

	/**
	 * @var FileProvider
	 */
	protected $fileProvider;

	protected function setUp() {
		parent::setUp();

		$this->fileProvider = new FileProviderMock( array(
			$this->getDataFile( 'LoremIpsum.djvu', 'image/x.djvu' ),
			$this->getDataFile( 'Test.jpg', 'image/jpg' )
		) );
	}

	protected function getDataFile( $name, $type ) {
		return new UnregisteredLocalFile(
			false,
			null,
			'mwstore://localtesting/data/' . $name,
			$type
		);
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
				$this->getDataFile( 'LoremIpsum.djvu', 'image/x.djvu' )
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
				$this->getDataFile( 'LoremIpsum.djvu', 'image/x.djvu' )
			),
			array(
				ProofreadPagePage::newFromTitle( Title::makeTitle( 250, 'LoremIpsum.djvu/djvu/1' ) ),
				$this->getDataFile( 'LoremIpsum.djvu', 'image/x.djvu' )
			),
			array(
				ProofreadPagePage::newFromTitle( Title::makeTitle( 250, 'LoremIpsum.djvu' ) ),
				$this->getDataFile( 'LoremIpsum.djvu', 'image/x.djvu' )
			),
			array(
				ProofreadPagePage::newFromTitle( Title::makeTitle( 250, 'Test.jpg' ) ),
				$this->getDataFile( 'Test.jpg', 'image/jpg' )
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
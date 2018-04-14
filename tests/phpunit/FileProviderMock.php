<?php

namespace ProofreadPage;

use File;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Provide a FileProvider mock based on a list of files
 */
class FileProviderMock extends FileProvider {

	/**
	 * @var File[]
	 */
	private $files = [];

	/**
	 * @param File[] $files
	 */
	public function __construct( array $files ) {
		foreach ( $files as $file ) {
			$this->files[$file->getTitle()->getDBkey()] = $file;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getFileFromTitle( Title $title ) {
		$key = $title->getDBkey();

		if ( !array_key_exists( $key, $this->files ) ) {
			throw new FileNotFoundException();
		}
		return $this->files[$key];
	}
}

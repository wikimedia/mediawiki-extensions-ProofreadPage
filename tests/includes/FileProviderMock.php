<?php

namespace ProofreadPage;

use File;
use Title;

/**
 * @licence GNU GPL v2+
 *
 * Provide a FileProvider mock based on a list of files
 */
class FileProviderMock extends FileProvider {

	/**
	 * @var File[]
	 */
	private $files = array();

	/**
	 * @param File[] $files
	 */
	public function __construct( array $files ) {
		foreach( $files as $file ) {
			$this->files[$file->getTitle()->getDBkey()] = $file;
		}
	}

	/**
	 * @see FileProvider::getFileFromTitle
	 */
	public function getFileFromTitle( Title $title ) {
		$key = $title->getDBkey();

		if ( !array_key_exists( $key, $this->files ) ) {
			throw new FileNotFoundException();
		}
		return $this->files[$key];
	}
}
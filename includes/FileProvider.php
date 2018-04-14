<?php

namespace ProofreadPage;

use File;
use RepoGroup;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Provide related file for various kind of pages
 */
class FileProvider {

	/**
	 * @var RepoGroup the repositories to use
	 */
	private $repoGroup;

	/**
	 * @param RepoGroup $repoGroup the repositories to use
	 */
	public function __construct( RepoGroup $repoGroup ) {
		$this->repoGroup = $repoGroup;
	}

	/**
	 * @param Title $title
	 * @return File
	 * @throws FileNotFoundException
	 */
	public function getFileFromTitle( Title $title ) {
		$result = $this->repoGroup->findFile( $title );
		if ( $result === false ) {
			throw new FileNotFoundException();
		}
		return $result;
	}

	/**
	 * @param Title $indexTitle
	 * @return File
	 * @throws FileNotFoundException
	 */
	public function getFileForIndexTitle( Title $indexTitle ) {
		return $this->getFileFromTitle(
			Title::makeTitle( NS_FILE, $indexTitle->getText() )
		);
	}

	/**
	 * @param Title $pageTitle
	 * @return File
	 * @throws FileNotFoundException
	 */
	public function getFileForPageTitle( Title $pageTitle ) {
		// try to get an image with the same name as the file
		return $this->getFileFromTitle(
		// use the base name as file name
			Title::makeTitle( NS_FILE, strtok( $pageTitle->getText(), '/' ) )
		);
	}

	/**
	 * @param Title $pageTitle
	 * @return int
	 * @throws PageNumberNotFoundException
	 */
	public function getPageNumberForPageTitle( Title $pageTitle ) {
		$parts = explode( '/', $pageTitle->getText() );
		if ( count( $parts ) === 1 ) {
			throw new PageNumberNotFoundException(
				$pageTitle->getFullText() . ' does not provide a page number.'
			);
		}
		$number = $pageTitle->getPageLanguage()->parseFormattedNumber( end( $parts ) );
		if ( $number > 0 ) {
			// Valid page numbers are integer > 0.
			return (int)$number;
		}
		throw new PageNumberNotFoundException(
			$pageTitle->getFullText() . ' does not provide a valid page number.'
		);
	}
}

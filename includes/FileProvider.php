<?php

namespace ProofreadPage;

use File;
use ProofreadIndexPage;
use ProofreadPagePage;
use RepoGroup;
use Title;

/**
 * @licence GNU GPL v2+
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
	 * @param ProofreadIndexPage $page
	 * @return File
	 * @throws FileNotFoundException
	 */
	public function getForIndexPage( ProofreadIndexPage $page ) {
		return $this->getFileFromTitle(
			Title::makeTitle( NS_FILE, $page->getTitle()->getText() )
		);
	}

	/**
	 * @param ProofreadPagePage $page
	 * @return File
	 * @throws FileNotFoundException
	 */
	public function getForPagePage( ProofreadPagePage $page ) {
		// try to get an image with the same name as the file
		return $this->getFileFromTitle(
			// use the base name as file name
			Title::makeTitle( NS_FILE, strtok( $page->getTitle()->getText(), '/' ) )
		);
	}

	/**
	 * @param ProofreadPagePage $page
	 * @return int
	 * @throws PageNumberNotFoundException
	 */
	public function getPageNumberForPagePage( ProofreadPagePage $page ) {
		$parts = explode( '/', $page->getTitle()->getText() );
		if ( count( $parts ) === 1 ) {
			throw new PageNumberNotFoundException(
				$page->getTitle()->getFullText() . ' does not provide a page number.'
			);
		}
		$number = $page->getTitle()->getPageLanguage()->parseFormattedNumber( end( $parts ) );
		if ( $number > 0 ) {
			// Valid page numbers are integer > 0.
			return (int)$number;
		}
		throw new PageNumberNotFoundException(
			$page->getTitle()->getFullText() . ' does not provide a valid page number.'
		);
	}
}

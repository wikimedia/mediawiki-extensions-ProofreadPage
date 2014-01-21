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
	 * @param $repoGroup $repoGroup the repositories to use
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
			Title::makeTitle( NS_IMAGE, $page->getTitle()->getText() )
		);
	}

	/**
	 * @param ProofreadPagePage $page
	 * @return File
	 * @throws FileNotFoundException
	 */
	public function getForPagePage( ProofreadPagePage $page ) {
		//try to get an image with the same name as the file
		return $this->getFileFromTitle(
			Title::makeTitle( NS_IMAGE, strtok( $page->getTitle()->getText(), '/' ) ) //use the base name as file name
		);
	}
}
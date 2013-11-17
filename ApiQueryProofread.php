<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup API
 */

class ApiQueryProofread extends ApiQueryBase {
	public function execute() {
		$pageSet = $this->getPageSet();
		$pages = $pageSet->getGoodTitles();
		if ( !count( $pages ) ) {
			return;
		}

		$pageNamespaceId = ProofreadPage::getPageNamespaceId();
		$pageIds = array();
		foreach ( $pages as $pageId => $title ) {
			if ( $title->inNamespace( $pageNamespaceId ) ) {
				$pageIds[] = $pageId;
			}
		}

		if ( !count( $pageIds ) ) {
			return;
		}

		// Determine the categories defined in MediaWiki: pages
		$qualityCategories = $qualityText = array();
		for ( $i = 0; $i < 5; $i++ ) {
			$cat = Title::makeTitleSafe(
				NS_CATEGORY,
				$this->msg( "proofreadpage_quality{$i}_category" )->inContentLanguage()->text()
			);
			if ( $cat ) {
				$qualityCategories[$i] = $cat->getPrefixedText();
				$qualityText[$i] = $cat->getText();
			}
		}
		$qualityLevels = array_flip( $qualityCategories );

		// <Reedy> johnduhart, it'd seem sane rather than duplicating the functionality
		$params = new FauxRequest( array(
			'action' => 'query',
			'prop' => 'categories',
			'pageids' => implode( '|', $pageIds ),
			'clcategories' => implode( '|', $qualityCategories ),
			'cllimit' => 'max'
		) );

		$api = new ApiMain($params);
		$api->execute();
		$data = $api->getResultData();
		unset( $api );

		if ( array_key_exists( 'error', $data ) ) {
			$this->dieUsageMsg( $data['error'] );
		}

		$result = $this->getResult();
		foreach ( $data['query']['pages'] as $pageid => $data) {
			if ( !array_key_exists( 'categories', $data ) ) {
				continue;
			}
			$title = $data['categories'][0]['title'];

			if ( !array_key_exists( $title, $qualityLevels ) ) {
				continue;
			}
			$pageQuality = $qualityLevels[ $title ];

			$val = array(
				'quality' => $pageQuality,
				'quality_text' => $qualityText[ $pageQuality ]
			);
			$result->addValue( array( 'query', 'pages', $pageid ), 'proofread', $val );
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return array();
	}

	public function getDescription() {
		return 'Returns information about the current proofread status of the given pages.';
	}

	public function getExamples() {
		return array(
			'api.php?action=query&generator=allpages&gapnamespace=' . ProofreadPage::getPageNamespaceId() . '&prop=proofread'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}

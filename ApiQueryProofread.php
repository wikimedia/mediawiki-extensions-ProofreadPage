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
		$params = new DerivativeRequest( $this->getRequest(), array(
			'action' => 'query',
			'prop' => 'categories',
			'pageids' => implode( '|', $pageIds ),
			'clcategories' => implode( '|', $qualityCategories ),
			'cllimit' => 'max'
		) );

		$api = new ApiMain($params);
		$api->execute();
		if ( defined( 'ApiResult::META_CONTENT' ) ) {
			$data = $api->getResult()->getResultData();
			$pages = ApiResult::stripMetadataNonRecursive(
				(array)$data['query']['pages']
			);
		} else {
			$data = $api->getResultData();
			$pages = $data['query']['pages'];
		}
		unset( $api );

		if ( array_key_exists( 'error', $data ) ) {
			$this->dieUsageMsg( $data['error'] );
		}

		$result = $this->getResult();
		foreach ( $pages as $pageid => $data) {
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

	/**
	 * @deprecated since MediaWiki core 1.25
	 */
	public function getDescription() {
		return 'Returns information about the current proofread status of the given pages.';
	}

	/**
	 * @deprecated since MediaWiki core 1.25
	 */
	public function getExamples() {
		return array(
			'api.php?action=query&generator=allpages&gapnamespace=' . ProofreadPage::getPageNamespaceId() . '&prop=proofread'
		);
	}

	/**
	 * @see ApiBase::getExamplesMessages()
	 */
	protected function getExamplesMessages() {
		return array(
			'action=query&generator=allpages&gapnamespace=250&prop=proofread'
				=> 'apihelp-query+proofread-example-1',
		);
	}
}

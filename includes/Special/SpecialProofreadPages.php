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
 * @ingroup SpecialPage
 */

namespace ProofreadPage\Special;

use HTMLForm;
use ISearchResultSet;
use MediaWiki\MediaWikiServices;
use ProofreadPage\Context;
use QueryPage;
use SearchResult;
use Title;

class SpecialProofreadPages extends QueryPage {
	/** @var string|null */
	protected $searchTerm;
	/** @var string[]|null */
	protected $searchList;
	/** @var bool|null */
	protected $suppressSqlOffset;
	/** @var string|null */
	protected $queryFilter;
	/** @var string|null */
	protected $queryOrder;
	/** @var bool|null */
	protected $sortAscending;
	/** @var true|null */
	protected $addOne;

	/**
	 * @param string $name
	 */
	public function __construct( $name = 'IndexPages' ) {
		parent::__construct( $name );
		$this->mIncludable = true;
	}

	/**
	 * @inheritDoc
	 */
	public function execute( $parameters ) {
		$this->setHeaders();
		if ( $this->limit == 0 && $this->offset == 0 ) {
			list( $this->limit, $this->offset ) = $this->getRequest()
				->getLimitOffsetForUser( $this->getUser() );
		}
		$output = $this->getOutput();
		$request = $this->getRequest();
		$output->addModules( 'ext.proofreadpage.special.indexpages' );
		$output->addWikiTextAsContent(
			$this->msg( 'proofreadpage_specialpage_text' )->inContentLanguage()->plain()
		);

		$this->searchList = null;
		$this->searchTerm = $request->getText( 'key' );
		$this->queryFilter = $request->getText( 'filter' );
		$this->queryOrder = $request->getText( 'order' );
		$this->sortAscending = $request->getBool( 'sortascending' );
		$this->suppressSqlOffset = false;

		// don't show navigation if included in another page
		$this->shownavigation = !$this->including();

		if ( !$this->getConfig()->get( 'DisableTextSearch' ) ) {
			if ( !$this->including() ) {
				// Only show the search form when not including in another page.
				$this->displaySearchForm();
			}

			if ( $this->searchTerm ) {
				$indexNamespaceId = Context::getDefaultContext()->getIndexNamespaceId();
				$searchEngine = MediaWikiServices::getInstance()->getSearchEngineFactory()->create();
				$searchEngine->setLimitOffset( $this->limit + 1, $this->offset );
				$searchEngine->setNamespaces( [ $indexNamespaceId ] );
				$status = $searchEngine->searchText( $this->searchTerm );
				if ( $status instanceof ISearchResultSet ) {
					$textMatches = $status;
					$status = null;
				} elseif ( $status->isOK() ) {
					$textMatches = $status->getValue();
				} else {
					$textMatches = null;
				}
				if ( !( $textMatches instanceof ISearchResultSet ) ) {
					// TODO: $searchEngine->searchText() can return status objects
					// Might want to extract some information from them
					$output->showErrorPage(
						'proofreadpage_specialpage_searcherror',
						'proofreadpage_specialpage_searcherrortext'
					);
				} else {
					$this->searchList = [];
					/** @var SearchResult $result */
					foreach ( $textMatches as $result ) {
						$title = $result->getTitle();
						if ( $title->inNamespace( $indexNamespaceId ) ) {
							array_push( $this->searchList, $title->getDBkey() );
						}
					}
					$this->suppressSqlOffset = true;
				}
			}
		}

		parent::execute( $parameters );
	}

	/**
	 * Wrapper function for parent function in QueryPage class
	 * @param int|false $limit
	 * @param int|false $offset
	 * @return \Wikimedia\Rdbms\IResultWrapper
	 */
	public function reallyDoQuery( $limit, $offset = false ) {
		if ( $this->searchList && count( $this->searchList ) > $this->limit ) {
			// Delete the last item to avoid the sort done by reallyDoQuery move it
			// to another position than the last
			$this->addOne = true;
			array_pop( $this->searchList );
		}
		if ( $this->suppressSqlOffset ) {
			// Bug #27678: Do not use offset here, because it was already used in
			// search performed by execute method
			return parent::reallyDoQuery( $limit, false );
		}
		return parent::reallyDoQuery( $limit, $offset );
	}

	/**
	 * Increments $numRows if the last item of the result has been deleted
	 * @param \Wikimedia\Rdbms\IDatabase $dbr [optional] (unused parameter)
	 * @param \Wikimedia\Rdbms\IResultWrapper $res [optional] (unused parameter)
	 */
	public function preprocessResults( $dbr, $res ) {
		if ( $this->addOne !== null ) {
			// there is a deleted item
			$this->numRows++;
		}
	}

	public function isExpensive() {
		// FIXME: the query does filesort, so we're kinda lying here right now
		return false;
	}

	public function isSyndicated() {
		return false;
	}

	public function isCacheable() {
		// The page is not cacheable due to its search capabilities
		return false;
	}

	public function linkParameters() {
		return [
			'key' => $this->searchTerm,
			'filter' => $this->queryFilter,
			'order' => $this->queryOrder,
			'sortascending' => $this->sortAscending
		];
	}

	/**
	 * Display the HTMLForm of the search form.
	 */
	protected function displaySearchForm() {
		$formDescriptor = [
			'key' => [
				'type' => 'text',
				'name' => 'key',
				'id' => 'key',
				'label-message' => 'proofreadpage_specialpage_label_key',
				'size' => 50,
				'default' => $this->searchTerm,
			],
			'filter' => [
				'type' => 'select',
				'name' => 'filter',
				'id' => 'filter',
				'label-message' => 'proofreadpage_specialpage_label_filterby',
				'default' => $this->queryFilter,
				'options' => [
					$this->msg( 'proofreadpage_specialpage_filterby_all' )->text() => 'all',
					$this->msg( 'proofreadpage_specialpage_filterby_incomplete' )->text() => 'incomplete',
					$this->msg( 'proofreadpage_specialpage_filterby_proofread' )->text() => 'proofread',
					$this->msg( 'proofreadpage_specialpage_filterby_proofread_or_validated' )->text() =>
						'proofreadOrValidated',
					$this->msg( 'proofreadpage_specialpage_filterby_validated' )->text() => 'validated'
				]
			],
			'order' => [
				'type' => 'select',
				'name' => 'order',
				'id' => 'order',
				'label-message' => 'proofreadpage_specialpage_label_orderby',
				'default' => $this->queryOrder,
				'options' => [
					$this->msg( 'proofreadpage_index_status' )->text() => 'quality',
					$this->msg( 'proofreadpage_index_size' )->text() => 'size',
					$this->msg( 'proofreadpage_pages_to_validate' )->text() => 'toValidate',
					$this->msg( 'proofreadpage_pages_to_proofread_or_validate' )->text() =>
						'toProofreadOrValidate',
					$this->msg( 'proofreadpage_alphabeticalorder' )->text() => 'alpha',
				]
			],
			'sortascending' => [
				'type' => 'check',
				'name' => 'sortascending',
				'id' => 'sortascending',
				'label-message' => 'proofreadpage_specialpage_label_sortascending',
				'selected' => $this->sortAscending,
			]
		];

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$htmlForm
			->addHiddenField( 'limit', $this->limit )
			->setMethod( 'get' )
			->setSubmitTextMsg( 'ilsubmit' )
			->setWrapperLegendMsg( 'proofreadpage_specialpage_legend' )
			->prepareForm()
			->displayForm( false );
	}

	/**
	 * @return mixed[]
	 */
	public function getQueryInfo() {
		$conds = [];
		if ( $this->searchTerm ) {
			if ( $this->searchList !== null ) {
				$conds = [ 'page_namespace' => Context::getDefaultContext()->getIndexNamespaceId() ];
				if ( $this->searchList ) {
					$conds['page_title'] = $this->searchList;
				} else {
					// If not pages were found do not return results
					$conds[] = '1=0';
				}
			}
		}

		switch ( $this->queryFilter ) {
			case 'incomplete':
				$conds[] = 'pr_count - pr_q0 - pr_q3 - pr_q4 > 0';
				break;
			case 'proofread':
				$conds[] = 'pr_count > 0 and pr_q3 > 0 and pr_count - pr_q0 - pr_q3 - pr_q4 = 0';
				break;
			case 'proofreadOrValidated':
				$conds[] = 'pr_count > 0 and pr_count - pr_q0 - pr_q3 - pr_q4 = 0';
				break;
			case 'validated':
				$conds[] = 'pr_count > 0 and pr_count - pr_q0 - pr_q4 = 0';
				break;
		}

		return [
			'tables' => [ 'pr_index', 'page' ],
			'fields' => [
				'page_namespace AS namespace',
				'page_title AS title',
				$this->buildValueField() . ' AS value',
				'pr_count', 'pr_q0', 'pr_q1', 'pr_q2', 'pr_q3', 'pr_q4'
			],
			'conds' => $conds,
			'options' => [],
			'join_conds' => [ 'page' => [ 'INNER JOIN', 'page_id=pr_page_id' ] ]
		];
	}

	private function buildValueField() {
		switch ( $this->queryOrder ) {
			case 'size':
				return 'pr_count';
			case 'alpha':
				return 'page_title';
			case 'toValidate':
				return 'pr_q3';
			case 'toProofreadOrValidate':
				return 'pr_count - pr_q4 - pr_q0';
			default:
				return '2 * pr_q4 + pr_q3';
		}
	}

	public function sortDescending() {
		return !$this->sortAscending;
	}

	/**
	 * @param \Skin $skin
	 * @param \stdClass $result Result row
	 * @return string|false false to skip the row
	 */
	public function formatResult( $skin, $result ) {
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
			return '<!-- Invalid title ' .
				htmlspecialchars( "{$result->namespace}:{$result->title}" ) . '-->';
		}
		$plink = $this->isCached()
			? $this->getLinkRenderer()->makeLink( $title, $title->getText() )
			: $this->getLinkRenderer()->makeKnownLink( $title, $title->getText() );

		if ( !$title->exists() ) {
			return "<del>{$plink}</del>";
		}

		$size = $result->pr_count;
		$q0 = $result->pr_q0;
		$q1 = $result->pr_q1;
		$q2 = $result->pr_q2;
		$q3 = $result->pr_q3;
		$q4 = $result->pr_q4;
		$num_void = $size - $q1 - $q2 - $q3 - $q4 - $q0;
		$void_cell = $num_void
			? '<td class="qualitye" style="width:' . $num_void . 'px;"></td>'
			: '';
		$textualAlternative = $this->msg( 'proofreadpage-indexquality-alt', $q4, $q3, $q1 )->escaped();
		$qualityOutput = '<table class="pr_quality" title="' . $textualAlternative . '">
<tr>
<td class="quality4" style="width:' . $q4 . 'px;"></td>
<td class="quality3" style="width:' . $q3 . 'px;"></td>
<td class="quality2" style="width:' . $q2 . 'px;"></td>
<td class="quality1" style="width:' . $q1 . 'px;"></td>
<td class="quality0" style="width:' . $q0 . 'px;"></td>
' . $void_cell . '
</tr>
</table>';

		$dirmark = $this->getLanguage()->getDirMark();
		$pages = $this->msg( 'proofreadpage_pages', $size )->numParams( $size )->text();

		return "<div class=\"prp-indexpages-row\"><span>{$plink} {$dirmark}[$pages]</span>" .
			"<div>{$qualityOutput}</div></div>";
	}

	/**
	 * @return string
	 */
	protected function getGroupName() {
		return 'pages';
	}
}

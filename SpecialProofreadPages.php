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

class SpecialProofreadPages extends QueryPage {
	protected $searchTerm, $searchList, $suppressSqlOffset, $queryOrder, $sortAscending, $addOne;

	public function __construct( $name = 'IndexPages' ) {
		parent::__construct( $name );
		$this->mIncludable = true;
	}

	public function execute( $parameters ) {
		global $wgDisableTextSearch;

		$this->setHeaders();
		if ( $this->limit == 0 && $this->offset == 0 ) {
			list( $this->limit, $this->offset ) = $this->getRequest()->getLimitOffset();
		}
		$output = $this->getOutput();
		$request = $this->getRequest();
		$output->addModules( 'ext.proofreadpage.special.indexpages' );
		$output->addWikiText(
			$this->msg( 'proofreadpage_specialpage_text' )->inContentLanguage()->plain()
		);

		$this->searchList = null;
		$this->searchTerm = $request->getText( 'key' );
		$this->queryOrder = $request->getText( 'order' );
		$this->sortAscending = $request->getBool( 'sortascending' );
		$this->suppressSqlOffset = false;

		if ( !$wgDisableTextSearch ) {
			if ( !$this->including() ) {
				// Only show the search form when not including in another page.
				$output->addHTML( $this->getSearchForm() );
			}

			if ( $this->searchTerm ) {
				$indexNamespaceId = ProofreadPage::getIndexNamespaceId();
				$searchEngine = SearchEngine::create();
				$searchEngine->setLimitOffset( $this->limit + 1, $this->offset );
				$searchEngine->setNamespaces( [ $indexNamespaceId ] );
				$searchEngine->showRedirects = false;
				$status = $searchEngine->searchText( $this->searchTerm );
				if ( $status instanceof SearchResultSet ) {
					$textMatches = $status;
					$status = null;
				} elseif ( $status->isOK() ) {
					$textMatches = $status->getValue();
				} else {
					$textMatches = null;
				}
				if ( !( $textMatches instanceof SearchResultSet ) ) {
					// TODO: $searchEngine->searchText() can return status objects
					// Might want to extract some information from them
					global $wgOut;
					$wgOut->showErrorPage(
						'proofreadpage_specialpage_searcherror',
						'proofreadpage_specialpage_searcherrortext'
					);
				} else {
					$this->searchList = [];
					while ( $result = $textMatches->next() ) {
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

	public function reallyDoQuery( $limit, $offset = false ) {
		$count = count( $this->searchList );
		if ( $count > $this->limit ) {
			// Delete the last item to avoid the sort done by reallyDoQuery move it
			// to another position than the last
			$this->addOne = true;
			unset( $this->searchList[ $count - 1 ] );
		}
		if ( $this->suppressSqlOffset ) {
			// Bug #27678: Do not use offset here, because it was already used in
			// search perfomed by execute method
			return parent::reallyDoQuery( $limit, false );
		}
		return parent::reallyDoQuery( $limit, $offset );
	}

	public function preprocessResults( $dbr, $res ) {
		if ( $this->addOne !== null ) {
			$this->numRows++; // there is a deleted item
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
			'order' => $this->queryOrder,
			'sortascending' => $this->sortAscending
		];
	}

	/**
	 * Get the HTML of the search form.
	 * @return string The HTML, with the <form> as the outermost element.
	 */
	protected function getSearchForm() {
		$config = RequestContext::getMain()->getConfig();

		$orderSelect = new XmlSelect( 'order', 'order', $this->queryOrder );
		$orderSelect->addOption( $this->msg( 'proofreadpage_index_status' )->text(), 'quality' );
		$orderSelect->addOption( $this->msg( 'proofreadpage_index_size' )->text(), 'size' );
		$orderSelect->addOption( $this->msg( 'proofreadpage_alphabeticalorder' )->text(), 'alpha' );

		$searchForm = Html::openElement( 'form', [ 'action' => $config->get( 'Script' ) ] ) .
			Html::hidden( 'title', $this->getPageTitle()->getPrefixedText() ) .
			Html::input( 'limit', $this->limit, 'hidden', [] ) .
			Html::openElement( 'fieldset', [] ) .
			Html::element(
				'legend', null, $this->msg( 'proofreadpage_specialpage_legend' )->text()
			) .
			Html::openElement( 'p' ) .
			Html::element( 'label', [ 'for' => 'key' ],
				$this->msg( 'proofreadpage_specialpage_label_key' )->text()
			)  . ' ' .
			Html::input( 'key', $this->searchTerm, 'search', [ 'id' => 'key', 'size' => '50' ] ) .
			Html::closeElement( 'p' ) .
			Html::openElement( 'p' ) .
			Html::element( 'label', [ 'for' => 'order' ],
				$this->msg( 'proofreadpage_specialpage_label_orderby' )->text()
			) . ' ' . $orderSelect->getHtml() . ' ' .
			Xml::checkLabel(
				$this->msg( 'proofreadpage_specialpage_label_sortascending' )->text(),
				'sortascending', 'sortascending', $this->sortAscending
			) . ' ' .
			Xml::submitButton( $this->msg( 'ilsubmit' )->text() ) .
			Html::closeElement( 'p' ) .
			Html::closeElement( 'fieldset' ) .
			Html::closeElement( 'form' );
		return $searchForm;
	}

	public function getQueryInfo() {
		$conds = [];
		if ( $this->searchTerm ) {
			if ( $this->searchList !== null ) {
				$conds = [ 'page_namespace' => ProofreadPage::getIndexNamespaceId() ];
				if ( $this->searchList ) {
					$conds['page_title'] = $this->searchList;
				} else {
					// If not pages were found do not return results
					$conds[] = 'false';
				}
			} else {
				$conds = null;
			}
		}

		return [
			'tables' => [ 'pr_index', 'page' ],
			'fields' => [ 'page_namespace AS namespace', 'page_title AS title',
			'2*pr_q4+pr_q3 AS value', 'pr_count', 'pr_q0', 'pr_q1', 'pr_q2', 'pr_q3', 'pr_q4' ],
			'conds' => $conds,
			'options' => [],
			'join_conds' => [ 'page' => [ 'INNER JOIN', 'page_id=pr_page_id' ] ]
		];
	}

	public function getOrderFields() {
		switch ( $this->queryOrder ) {
			case 'size':
				return [ 'pr_count' ];
			case 'alpha':
				return [ 'page_title' ];
			default:
				return [ 'value' ];
		}
	}

	public function sortDescending() {
		return !$this->sortAscending;
	}

	public function formatResult( $skin, $result ) {
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
			return '<!-- Invalid title ' .
				htmlspecialchars( "{$result->namespace}:{$result->title}" ) . '-->';
		}
		$plink = $this->isCached()
			? Linker::link( $title, htmlspecialchars( $title->getText() ) )
			: Linker::linkKnown( $title, htmlspecialchars( $title->getText() ) );

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
		$textualAlternative = $this->msg( 'proofreadpage-indexquality-alt', $q4, $q3, $q1 );
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

	protected function getGroupName() {
		return 'pages';
	}
}

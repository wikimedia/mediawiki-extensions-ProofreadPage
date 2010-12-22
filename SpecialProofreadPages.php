<?php
/**
 * @file
 * @ingroup SpecialPage
 */

class ProofreadPages extends QueryPage {
	protected $index_namespace, $searchTerm;
	
	public function __construct( $name = 'IndexPages' ) {
		parent::__construct( $name );
		$this->index_namespace = wfMsgForContent( 'proofreadpage_index_namespace' );
	}

	public function execute( $parameters ) {
		global $wgOut, $wgRequest, $wgDisableTextSearch;

		$this->setHeaders();
		list( $limit, $offset ) = wfCheckLimits();
		$wgOut->addWikiText( wfMsgForContentNoTrans( 'proofreadpage_specialpage_text' ) );
		$searchList = array();
		$this->searchTerm = $wgRequest->getText( 'key' );
		if( !$wgDisableTextSearch ) {
			$wgOut->addHTML(
				Xml::openElement( 'form' ) .
				Xml::openElement( 'fieldset' ) .
				Xml::element( 'legend', null, wfMsg( 'proofreadpage_specialpage_legend' ) ) .
				Xml::input( 'key', 20, $this->searchTerm ) . ' ' .
				Xml::submitButton( wfMsg( 'ilsubmit' ) ) .
				Xml::closeElement( 'fieldset' ) .
				Xml::closeElement( 'form' )
			);
			if( $this->searchTerm ) {
				$index_namespace = $this->index_namespace;
				$index_ns_index = MWNamespace::getCanonicalIndex( strtolower( $index_namespace ) );
				$searchEngine = SearchEngine::create();
				$searchEngine->setLimitOffset( $limit, $offset );
				$searchEngine->setNamespaces( array( $index_ns_index ) );
				$searchEngine->showRedirects = false;
				$textMatches = $searchEngine->searchText( $this->searchTerm );
				$escIndex = preg_quote( $index_namespace, '/' );
				while( $result = $textMatches->next() ) {
					if ( preg_match( "/^$escIndex:(.*)$/", $result->getTitle(), $m ) ) {
						array_push( $searchList, str_replace( ' ' , '_' , $m[1] ) );
					}
				}
			}
		}
		parent::execute( $parameters );
	}

	function isExpensive() {
		// FIXME: the query does filesort, so we're kinda lying here right now
		return false;
	}

	function isSyndicated() {
		return false;
	}

	function linkParameters() {
		return array( 'key' => $this->searchTerm );
	}
	
	public function getQueryInfo() {
		$conds = array();
		if ( $this->searchTerm ) {
			if ( $this->searchList ) {
				$index_namespace = pr_index_ns();
				$index_ns_index = MWNamespace::getCanonicalIndex( strtolower( $index_namespace ) );
				$conds = array( 'page_namespace' => $index_ns_index, 'page_title' => $this->searchList );
			} else {
				$conds = null;
			}
		}
		return array(
			'tables' => array( 'pr_index', 'page' ),
			'fields' => array( 'page_title AS title', '2*pr_q4+pr_q3 AS value', 'pr_count',
			'pr_q0', 'pr_q1', 'pr_q2' ,'pr_q3', 'pr_q4' ),
			'conds' => $conds,
			'options' => array(),
			'join_conds' => array( 'page' => array( 'LEFT JOIN', 'page_id=pr_page_id' ) )
		);
	}

	function sortDescending() {
		return true;
	}

	function formatResult( $skin, $result ) {
		global $wgLang;

		$title = Title::newFromText( $this->index_namespace . ':' . $result->title );

		if ( !$title ) {
			return '<!-- Invalid title ' .  htmlspecialchars( $this->index_namespace . ':' . $result->title ) . '-->';
		}
		$plink = $this->isCached()
			? $skin->link( $title , htmlspecialchars( $title->getText() ) )
			: $skin->linkKnown( $title , htmlspecialchars( $title->getText() ) );

		if ( !$title->exists() ) {
			return "<del>{$plink}</del>";
		}

		$size = $result->pr_count;
		$q0 = $result->pr_q0;
		$q1 = $result->pr_q1;
		$q2 = $result->pr_q2;
		$q3 = $result->pr_q3;
		$q4 = $result->pr_q4;
		$num_void = $size-$q1-$q2-$q3-$q4-$q0;
		$void_cell = $num_void ? "<td align=center style='border-style:dotted;background:#ffffff;border-width:1px;' width=\"{$num_void}\"></td>" : '';

		// FIXME: consider using $size in 'proofreadpage_pages' instead of glueing it together in $output
		$pages = wfMsgExt( 'proofreadpage_pages', 'parsemag', $size );
		$size = $wgLang->formatNum( $size );

		$output = "<table style=\"line-height:70%;\" border=0 cellpadding=5 cellspacing=0 >
<tr valign=\"bottom\">
<td style=\"white-space:nowrap;overflow:hidden;\">{$plink} [$size $pages]</td>
<td>
<table style=\"line-height:70%;\" border=0 cellpadding=0 cellspacing=0 >
<tr>
<td width=\"2\">&#160;</td>
<td align=center class='quality4' width=\"$q4\"></td>
<td align=center class='quality3' width=\"$q3\"></td>
<td align=center class='quality2' width=\"$q2\"></td>
<td align=center class='quality1' width=\"$q1\"></td>
<td align=center class='quality0' width=\"$q0\"></td>
$void_cell
</tr></table>
</td>
</tr></table>";

		return $output;
	}
}

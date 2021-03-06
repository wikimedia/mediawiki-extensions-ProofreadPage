<?php

namespace ProofreadPage\Parser;

use Html;
use IContextSource;
use MediaWiki\Linker\LinkRenderer;
use OutputPage;
use ParserOutput;
use ProofreadPage\Index\IndexQualityStatsLookup;
use ProofreadPage\Index\PagesQualityStats;
use ProofreadPage\Page\IndexForPageLookup;
use ProofreadPage\Page\PageQualityLevelLookup;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Modifies the UI of pages that transcludes Page: pages
 */
class TranslusionPagesModifier {

	/** @var PageQualityLevelLookup */
	private $pageQualityLevelLookup;

	/** @var IndexQualityStatsLookup */
	private $indexQualityStatsLookup;

	/** @var IndexForPageLookup */
	private $indexForPageLookup;

	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var int */
	private $pageNamespaceId;

	/**
	 * @param PageQualityLevelLookup $pageQualityLevelLookup
	 * @param IndexQualityStatsLookup $indexQualityStatsLookup
	 * @param IndexForPageLookup $indexForPageLookup
	 * @param LinkRenderer $linkRenderer
	 * @param int $pageNamespaceId
	 */
	public function __construct(
		PageQualityLevelLookup $pageQualityLevelLookup,
		IndexQualityStatsLookup $indexQualityStatsLookup,
		IndexForPageLookup $indexForPageLookup,
		LinkRenderer $linkRenderer,
		int $pageNamespaceId
	) {
		$this->pageQualityLevelLookup = $pageQualityLevelLookup;
		$this->indexQualityStatsLookup = $indexQualityStatsLookup;
		$this->indexForPageLookup = $indexForPageLookup;
		$this->linkRenderer = $linkRenderer;
		$this->pageNamespaceId = $pageNamespaceId;
	}

	/**
	 * @param ParserOutput $parserOutput
	 * @param OutputPage $outputPage
	 */
	public function modifyPage( ParserOutput $parserOutput, OutputPage $outputPage ) {
		$transcludedPages = $this->getIncludedPagePagesTitles( $parserOutput );
		$indexTitle = $this->getIndexTitleForPages( $transcludedPages );

		if ( $parserOutput->getExtensionData( 'proofreadpage_is_toc' ) && $indexTitle !== null ) {
			$qualityStats = $this->indexQualityStatsLookup->getStatsForIndexTitle( $indexTitle );
		} else {
			$qualityStats = $this->getQualityStatsForPages( $transcludedPages );
		}

		if ( $indexTitle !== null ) {
			$indexLink = $this->linkRenderer->makeLink(
				$indexTitle,
				$outputPage->msg( 'proofreadpage_source' )->text(),
				[
					'title' => $outputPage->msg( 'proofreadpage_source_message' )->text()
				]
			);
			$outputPage->addJsConfigVars( 'proofreadpage_source_href', $indexLink );
			$outputPage->addModules( 'ext.proofreadpage.article' );
		}

		if ( $qualityStats->getNumberOfPages() !== 0 ) {
			$outputPage->setSubtitle(
				$outputPage->getSubtitle() .
				$this->buildQualityStatsBar( $qualityStats, $outputPage )
			);
		}
	}

	/**
	 * @param ParserOutput $parserOutput
	 * @return Title[]
	 */
	private function getIncludedPagePagesTitles( ParserOutput $parserOutput ) : array {
		$templates = $parserOutput->getTemplates();

		if ( !array_key_exists( $this->pageNamespaceId, $templates ) ) {
			return [];
		}

		$titles = [];
		foreach ( $templates[ $this->pageNamespaceId ] as $dbKey => $pageId ) {
			$titles[] = Title::makeTitle( $this->pageNamespaceId, $dbKey );
		}
		return $titles;
	}

	/**
	 * @param array $pages
	 * @return Title|null
	 */
	private function getIndexTitleForPages( array $pages ) : ?Title {
		foreach ( $pages as $page ) {
			$indexTitle = $this->indexForPageLookup->getIndexForPageTitle( $page );
			if ( $indexTitle !== null ) {
				return $indexTitle;
			}
		}
		return null;
	}

	/**
	 * @param Title[] $pages
	 * @return PagesQualityStats
	 */
	private function getQualityStatsForPages( array $pages ) : PagesQualityStats {
		$this->pageQualityLevelLookup->prefetchQualityLevelForTitles( $pages );

		$numberOfPagesByLevel = [];
		foreach ( $pages as $pageTitle ) {
			$pageQualityLevel = $this->pageQualityLevelLookup->getQualityLevelForPageTitle( $pageTitle );
			if ( array_key_exists( $pageQualityLevel, $numberOfPagesByLevel ) ) {
				$numberOfPagesByLevel[$pageQualityLevel]++;
			} else {
				$numberOfPagesByLevel[$pageQualityLevel] = 1;
			}
		}

		return new PagesQualityStats( count( $pages ), $numberOfPagesByLevel );
	}

	/**
	 * @param PagesQualityStats $qualityStats
	 * @param IContextSource $contextSource
	 * @return string
	 * @suppress SecurityCheck-DoubleEscaped phan does not like HTML tags concatenation
	 */
	private function buildQualityStatsBar(
		PagesQualityStats $qualityStats,
		IContextSource $contextSource
	) : string {
		$totalPages = $qualityStats->getNumberOfPages();
		$percentages = [];
		for ( $i = 4; $i >= 0; $i-- ) {
			$pagesAtLevel = $qualityStats->getNumberOfPagesForQualityLevel( $i );
			$percentages[$i] = $pagesAtLevel * 100 / $totalPages;
		}
		$percentages['e'] = 100 - array_sum( $percentages );

		$output = '';
		foreach ( $percentages as $key => $value ) {
			// The following classes are used here: quality0, quality1, quality2, quality3, quality4, qualitye
			$output .= Html::element( 'td', [
				'class' => "quality$key",
				'style' => "width: $value%;"
			] );
		}

		return Html::rawElement(
			'table',
			[
				'class' => 'pr_quality',
				'title' => $contextSource->msg(
					'proofreadpage-indexquality-alt',
					$qualityStats->getNumberOfPagesForQualityLevel( 4 ),
					$qualityStats->getNumberOfPagesForQualityLevel( 3 ),
					$qualityStats->getNumberOfPagesForQualityLevel( 1 )
				)
			],
			Html::rawElement( 'tr', [], $output )
		);
	}
}

<?php

namespace ProofreadPage\Index;

use Content;
use IContextSource;
use ProofreadPage\DiffFormatterUtils;
use SlotDiffRenderer;
use Title;
use WikitextContent;

/**
 * SlotDiffRenderer for Index: pages content model
 */
class IndexSlotDiffRenderer extends SlotDiffRenderer {

	/**
	 * @var IContextSource
	 */
	private $context;

	/**
	 * @var DiffFormatterUtils
	 */
	private $diffFormatterUtils;

	/**
	 * @var CustomIndexFieldsParser
	 */
	private $customIndexFieldsParser;

	/**
	 * @var SlotDiffRenderer
	 */
	private $wikitextSlotDiffRenderer;

	/**
	 * @inheritDoc
	 */
	public function __construct(
		IContextSource $contextSource,
		CustomIndexFieldsParser $customIndexFieldsParser,
		SlotDiffRenderer $wikitextSlotDiffRenderer
	) {
		$this->context = $contextSource;
		$this->diffFormatterUtils = new DiffFormatterUtils();
		$this->customIndexFieldsParser = $customIndexFieldsParser;
		$this->wikitextSlotDiffRenderer = $wikitextSlotDiffRenderer;
	}

	/** @inheritDoc */
	public function getExtraCacheKeys() {
		return [
			// required because the diff view contains localized strings such
			// as the section headers
			$this->context->getLanguage()->getCode()
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getDiff( Content $oldContent = null, Content $newContent = null ) {
		$this->normalizeContents(
			$oldContent, $newContent,
			[ IndexContent::class, IndexRedirectContent::class ]
		);

		$diff = '';
		if (
			$oldContent instanceof IndexRedirectContent ||
			$newContent instanceof IndexRedirectContent
		) {
			$oldRedirectTitle = ( $oldContent instanceof IndexRedirectContent )
				? $oldContent->getRedirectTarget()
				: null;
			$newRedirectTitle = ( $newContent instanceof IndexRedirectContent )
				? $newContent->getRedirectTarget()
				: null;
			$diff .= $this->createRedirectionDiff( $oldRedirectTitle, $newRedirectTitle );
		}
		if ( $oldContent instanceof IndexContent || $newContent instanceof IndexContent ) {
			$oldContent = ( $oldContent instanceof IndexContent ) ? $oldContent : new IndexContent( [] );
			$newContent = ( $newContent instanceof IndexContent ) ? $newContent : new IndexContent( [] );
			$diff .= $this->createIndexContentDiff( $oldContent, $newContent );
		}
		return $diff;
	}

	/**
	 * @param Title|null $oldTitle
	 * @param Title|null $newTitle
	 * @return string
	 */
	private function createRedirectionDiff( Title $oldTitle = null, Title $newTitle = null ) {
		$old = ( $oldTitle === null ) ? '' : $oldTitle->getFullText();
		$new = ( $newTitle === null ) ? '' : $newTitle->getFullText();
		return $this->createTextDiffOutput( $old, $new,
			$this->context->msg( 'isredirect' )->escaped()
		);
	}

	/**
	 * @param IndexContent $old
	 * @param IndexContent $new
	 * @return string
	 */
	private function createIndexContentDiff( IndexContent $old, IndexContent $new ) {
		$oldCustomFields = $this->customIndexFieldsParser->parseCustomIndexFields( $old );
		$newCustomFields = $this->customIndexFieldsParser->parseCustomIndexFields( $new );
		$diff = '';

		foreach ( $oldCustomFields as $oldField ) {
			$diff .= $this->createTextDiffOutput(
				$oldField->getStringValue(),
				$newCustomFields[$oldField->getKey()]->getStringValue(),
				$oldField->getLabel()
			);
		}

		$diff .= $this->createTextDiffOutput(
			implode( "\n", $old->getCategories() ),
			implode( "\n", $new->getCategories() ),
			$this->context->msg( 'proofreadpage-index-field-category-label' )->escaped()
		);

		return $diff;
	}

	/**
	 * @param string $oldText
	 * @param string $newText
	 * @param string $header
	 * @return string
	 */
	private function createTextDiffOutput( $oldText, $newText, $header ) {
		$diff = $this->wikitextSlotDiffRenderer->getDiff(
			new WikitextContent( $oldText ),
			new WikitextContent( $newText )
		);
		if ( $diff === '' ) {
			return '';
		}
		return $this->diffFormatterUtils->createHeader( $header ) . $diff;
	}
}

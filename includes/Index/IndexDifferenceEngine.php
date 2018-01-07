<?php

namespace ProofreadPage\Index;

use Content;
use DifferenceEngine;
use InvalidArgumentException;
use ProofreadPage\Context;
use ProofreadPage\DiffFormatterUtils;
use Title;

/**
 * DifferenceEngine for Index: pages
 */
class IndexDifferenceEngine extends DifferenceEngine {

	/**
	 * @var DiffFormatterUtils
	 */
	private $diffFormatterUtils;

	/**
	 * @var CustomIndexFieldsParser
	 */
	private $customIndexFieldsParser;

	/**
	 * @inheritDoc
	 */
	public function __construct(
		$context = null, $old = 0, $new = 0, $rcid = 0, $refreshCache = false, $unhide = false,
		Context $extContext = null
	) {
		parent::__construct( $context, $old, $new, $rcid, $refreshCache, $unhide );

		$this->diffFormatterUtils = new DiffFormatterUtils();
		$extContext = ( $extContext === null ) ? Context::getDefaultContext() : $extContext;
		$this->customIndexFieldsParser = $extContext->getCustomIndexFieldsParser();
	}

	/**
	 * @inheritDoc
	 */
	public function generateContentDiffBody( Content $old, Content $new ) {
		if ( $old instanceof IndexRedirectContent ) {
			if ( $new instanceof IndexRedirectContent ) {
				return $this->createRedirectionDiff( $old->getRedirectTarget(), $new->getRedirectTarget() );
			} elseif ( $new instanceof IndexContent ) {
				return $this->createRedirectionDiff( $old->getRedirectTarget(), null ) .
					$this->createIndexContentDiff( null, $new );
			}
		} elseif ( $old instanceof IndexContent ) {
			if ( $new instanceof IndexRedirectContent ) {
				return $this->createRedirectionDiff( null, $new->getRedirectTarget() ) .
					$this->createIndexContentDiff( $old, null );
			} elseif ( $new instanceof IndexContent ) {
				return $this->createIndexContentDiff( $old, $new );
			}
		}
		throw new InvalidArgumentException(
			'IndexDifferenceEngine is only able to output diffs between IndexContents'
		);
	}

	private function createRedirectionDiff( Title $old = null, Title $new = null ) {
		$old = ( $old === null ) ? '' : $old->getFullText();
		$new = ( $new === null ) ? '' : $new->getFullText();
		return $this->createTextDiffOutput( $old, $new,
			$this->msg( 'isredirect' )->escaped()
		);
	}

	private function createIndexContentDiff( IndexContent $old = null, IndexContent $new = null ) {
		$oldCustomFields = ( $old === null )
			? []
			: $this->customIndexFieldsParser->parseCustomIndexFields( $old );
		$newCustomFields = ( $new === null )
			? []
			: $this->customIndexFieldsParser->parseCustomIndexFields( $new );
		$diff = '';
		foreach ( $oldCustomFields as $oldField ) {
			$diff .= $this->createTextDiffOutput(
				$oldField->getStringValue(),
				$newCustomFields[$oldField->getKey()]->getStringValue(),
				$oldField->getLabel()
			);
		}
		return $diff;
	}

	private function createTextDiffOutput( $old, $new, $header ) {
		$diff = parent::generateTextDiffBody( $old, $new );
		if ( $diff === '' ) {
			return '';
		}
		return $this->diffFormatterUtils->createHeader( $header ) . $diff;
	}
}

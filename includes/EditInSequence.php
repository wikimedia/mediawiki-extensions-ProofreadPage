<?php

namespace ProofreadPage;

use MediaWiki\Context\IContextSource;
use MediaWiki\Extension\BetaFeatures\BetaFeatures;
use MediaWiki\Registration\ExtensionRegistry;

class EditInSequence {
	/** url parameter for edit-in-sequence */
	public const URLPARAMNAME = 'prp_editinsequence';
	public const TAGNAME = 'proofreadpage-editinsequence';
	public const BETA_FEATURE_NAME = 'proofreadpage-editinsequence';
	public const USER_AGENT_NAME = 'EditInSequence';

	/**
	 * Check if we are edit-in-sequence is enabled for a particular wiki
	 * @param IContextSource $context
	 * @return bool
	 */
	public static function isEnabled( IContextSource $context ): bool {
		// By default if beta features is not present, enable by default,
		// else respect beta feature setting
		$isBetaFeatureEnabled = true;
		if ( ExtensionRegistry::getInstance()->isLoaded( 'BetaFeatures' ) ) {
			$isBetaFeatureEnabled = BetaFeatures::isFeatureEnabled(
				$context->getUser(),
				'proofreadpage-editinsequence'
			);
		}
		return $context->getConfig()->get( 'ProofreadPageEnableEditInSequence' ) && $isBetaFeatureEnabled;
	}

	/**
	 * Make sure we are supposed to load edit-in-sequence (i.e. edit-in-sequence is enabled
	 * for the wiki and the user is in edit-in-sequence mode)
	 * @param IContextSource $context
	 * @return bool
	 */
	public static function shouldLoadEditInSequence( IContextSource $context ): bool {
		return $context->getConfig()->get( 'ProofreadPageEnableEditInSequence' )
			&& $context->getRequest()->getBool( self::URLPARAMNAME );
	}

	/**
	 * Check if we are saving a edit made using the editinsequence mode
	 * @param IContextSource $context
	 * @return bool
	 */
	public static function isEditInSequenceEdit( IContextSource $context ): bool {
		return self::isEnabled( $context ) &&
			$context->getRequest()->getHeader( 'X-USER-AGENT' ) === self::USER_AGENT_NAME;
	}
}

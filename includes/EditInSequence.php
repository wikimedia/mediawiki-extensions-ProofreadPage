<?php

namespace ProofreadPage;

use IContextSource;

class EditInSequence {
	/** url parameter for edit-in-sequence */
	public const URLPARAMNAME = 'prp_editinsequence';

	/**
	 * Check if we are edit-in-sequence is enabled for a particular wiki
	 * @param IContextSource $context
	 * @return bool
	 */
	public static function isEnabled( IContextSource $context ): bool {
		return $context->getConfig()->get( 'ProofreadPageEnableEditInSequence' );
	}

	/**
	 * Make sure we are supposed to load edit-in-sequence (i.e. edit-in-sequence is enabled
	 * for the wiki and the user is in edit-in-sequence mode)
	 * @param IContextSource $context
	 * @return bool
	 */
	public static function shouldLoadEditInSequence( IContextSource $context ): bool {
		return self::isEnabled( $context ) &&
			$context->getRequest()->getBool( self::URLPARAMNAME );
	}
}

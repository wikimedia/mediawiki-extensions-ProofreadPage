<?php
declare( strict_types = 1 );

namespace ProofreadPage;

use MediaWiki\Extension\CodeMirror\Hooks\CodeMirrorGetModeHook;
use MediaWiki\Title\Title;

/**
 * Load CodeMirror for the proofread-page content model.
 */
class CodeMirrorHooks implements CodeMirrorGetModeHook {
	/**
	 * @inheritDoc
	 */
	public function onCodeMirrorGetMode( Title $title, ?string &$mode, string $model ) {
		if ( $model === 'proofread-page' ) {
			$mode = 'mediawiki';
			return false;
		}

		return true;
	}
}

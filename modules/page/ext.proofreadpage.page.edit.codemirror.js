const { EditorView, Prec } = require( 'ext.CodeMirror.v6.lib' );
const CodeMirror = require( 'ext.CodeMirror.v6' );
const mediawikiLang = require( 'ext.CodeMirror.v6.mode.mediawiki' );

/**
 * CodeMirror integration for the header and footer of the page.
 * This is identical to a normal CodeMirror instance, except it does not have its own search.
 * Additionally, any preference changes are synced with the main instance.
 *
 * @see https://doc.wikimedia.org/CodeMirror/master/js/js/CodeMirror.html
 */
class CodeMirrorProofreadPage extends CodeMirror {
	/**
	 * @param {HTMLTextAreaElement} textarea
	 * @param {CodeMirror} mainInstance
	 * @override
	 */
	constructor( textarea, mainInstance ) {
		super( textarea, mediawikiLang() );

		/**
		 * The main CodeMirror instance.
		 *
		 * @type {CodeMirror}
		 */
		this.mainInstance = mainInstance;
	}

	/**
	 * @inheritDoc
	 */
	get domEventHandlersExtension() {
		return [
			super.domEventHandlersExtension,

			// Change the WikiEditor context to this textarea when this instance is focused.
			// The idea is to route jQuery.textSelection to the correct textarea, which then
			// gets forwarded to the corresponding CodeMirror instance.
			// HACK: Even though it works, WikiEditor wasn't designed for this.
			EditorView.domEventHandlers( {
				blur: () => {
					this.mainInstance.context.$textarea = $( this.mainInstance.textarea );
				},
				focus: () => {
					this.mainInstance.context.$textarea = $( this.textarea );
				}
			} )
		];
	}

	/**
	 * Disable searching.
	 *
	 * @override
	 */
	get searchExtension() {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	initialize() {
		super.initialize();

		// Register the preferences keymap for this instance to route to the main instance.
		this.keymap.registerKeyBinding(
			Object.assign( this.keymap.keymapHelpRegistry.other.preferences, {
				prec: Prec.highest,
				run: () => {
					this.mainInstance.preferences.toggle( this.mainInstance.view, true );
					return true;
				}
			} ),
			this.view
		);

		// Toggle this CodeMirror instance when the main instance is toggled.
		mw.hook( 'ext.CodeMirror.toggle' ).add( ( enabled, _cm, textarea ) => {
			if ( textarea !== this.textarea && enabled !== this.isActive ) {
				this.toggle( enabled );
			}
		} );

		// Sync preferences between this instance and the main instance.
		mw.hook( 'ext.CodeMirror.preferences.apply' ).add( ( prefName, enabled ) => {
			if ( enabled !== this.preferences.getPreference( prefName ) ) {
				this.extensionRegistry.toggle( prefName, this.view, enabled );
				this.preferences.setPreference( prefName, enabled );
			}
		} );
	}

	/**
	 * @override
	 */
	logEditFeature() {}

	/**
	 * Only log feature usage on main CM instance.
	 *
	 * @override
	 */
	setupFeatureLogging() {}
}

module.exports = CodeMirrorProofreadPage;

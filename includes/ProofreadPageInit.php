<?php

namespace ProofreadPage;

use MediaWiki\Config\Config;
use MediaWiki\Config\ConfigException;
use MediaWiki\Hook\SetupAfterCacheHook;

/**
 * @license GPL-2.0-or-later
 *
 * Class that contain init system of the ProofreadPage extension
 */
class ProofreadPageInit implements SetupAfterCacheHook {

	/** @var Config */
	private $config;

	/**
	 * @param Config $config
	 */
	public function __construct( Config $config ) {
		$this->config = $config;
	}

	/**
	 * @var int[] the default namespace id for each namespaces
	 * Called by the SetupAfterCache hook
	 */
	protected static $defaultNamespaceIds = [
		'page' => 250,
		'index' => 252
	];

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/SetupAfterCache
	 */
	public function onSetupAfterCache() {
		global $wgTemplateStylesNamespaces;
		self::initNamespace( 'page' );
		self::initNamespace( 'index' );

		$proofreadPageNamespaceIds = $this->config->get( 'ProofreadPageNamespaceIds' );
		if ( \ExtensionRegistry::getInstance()->isLoaded( 'TemplateStyles' ) ) {
			// Also Add Index NS to the TemplateStyles auto-CSS list
			// so that /styles.css can be created
			$templateStylesNamespaces = $this->config->get( 'TemplateStylesNamespaces' );
			$templateStylesNamespaces[ $proofreadPageNamespaceIds[ 'index' ] ] = true;
			$wgTemplateStylesNamespaces = $templateStylesNamespaces;
		}
	}

	/**
	 * Create a namespace and his discussion one
	 * @param string $key the key of the namespace in the i18n file
	 */
	protected static function initNamespace( $key ) {
		global $wgContentNamespaces, $wgExtraNamespaces, $wgProofreadPageNamespaceIds;

		if ( isset( $wgProofreadPageNamespaceIds[$key] ) ) {
			if ( !is_numeric( $wgProofreadPageNamespaceIds[$key] ) ) {
				throw new ConfigException(
					'$wgProofreadPageNamespaceIds[' . $key . '] must be a number.'
				);
			}

			if ( !isset( $wgExtraNamespaces[$wgProofreadPageNamespaceIds[$key]] ) ) {
				self::createNamespace( $wgProofreadPageNamespaceIds[$key], $key );
			}
		} else {
			 // try to find if a namespace with a known name is set (for backward compatibility)
			$id = self::getNamespaceIdForDefaultName( $key );
			if ( $id !== false ) {
				$wgProofreadPageNamespaceIds[$key] = $id;
			} else {

				if ( self::createNamespace( self::$defaultNamespaceIds[$key], $key ) ) {
					$wgProofreadPageNamespaceIds[$key] = self::$defaultNamespaceIds[$key];
				}
				// else: the relevant error message is output by getNamespaceId
			}
		}

		// Also Add Page/Index namespace to $wgContentNamespaces
		$wgContentNamespaces[] = $wgProofreadPageNamespaceIds[$key];
	}

	/**
	 * Find if a namespace with the default name is already set (for backward compatibility) and
	 * return his id
	 * @param string $key the key of the namespace in the i18n file
	 * @return int|false the id of the namespace or false if it doesn't exist
	 */
	protected static function getNamespaceIdForDefaultName( $key ) {
		global $wgExtraNamespaces;

		$xNamespaces = [];
		foreach ( $wgExtraNamespaces as $i => $text ) {
			$xNamespaces[strtolower( $text )] = $i;
		}

		$name = strtolower( self::getNamespaceName( $key ) );

		return array_key_exists( $name, $xNamespaces ) ? $xNamespaces[$name] : false;
	}

	/**
	 * Create a namespace and his discussion one
	 * @param int $id the namespace id
	 * @param string $key the key of the namespace in the i18n file
	 * @return bool false if there is an error, true if not
	 */
	protected static function createNamespace( $id, $key ) {
		global $wgCanonicalNamespaceNames, $wgExtraNamespaces, $wgNamespacesWithSubpages;

		if ( isset( $wgExtraNamespaces[$id] ) || isset( $wgExtraNamespaces[$id + 1] ) ) {
			return false;
		}

		$talkKey = $key . '_talk';

		$wgExtraNamespaces[$id] = self::getNamespaceName( $key );
		$wgExtraNamespaces[$id + 1] = self::getNamespaceName( $talkKey );

		// Very hugly but needed because initNamespaces() is called after the add of
		// $wgExtraNamespaces into $wgCanonicalNamespaceNames
		$wgCanonicalNamespaceNames[$id] = $wgExtraNamespaces[$id];
		$wgCanonicalNamespaceNames[$id + 1] = $wgExtraNamespaces[$id + 1];

		// ProofreadPage's namespaces should have subpages - T256410
		$wgNamespacesWithSubpages[$id] = true;
		$wgNamespacesWithSubpages[$id + 1] = true;

		self::createNamespaceAliases( $key, $id );
		self::createNamespaceAliases( $talkKey, $id + 1 );

		return true;
	}

	/**
	 * @param string $key
	 * @param int $id
	 */
	private static function createNamespaceAliases( $key, $id ) {
		global $wgNamespaceAliases;

		$aliases = self::getNamespaceAliases( $key );
		foreach ( $aliases as $alias ) {
			$wgNamespaceAliases[$alias] = $id;
		}
	}

	/**
	 * @param string $key
	 *
	 * @return string[]
	 */
	private static function getNamespaceAliases( $key ) {
		global $proofreadPageNamespaceAliases, $wgLanguageCode;

		if ( !isset( $proofreadPageNamespaceAliases[$wgLanguageCode][$key] ) ) {
			return [];
		}

		return $proofreadPageNamespaceAliases[$wgLanguageCode][$key];
	}

	/**
	 * Return the internationalized name of a namespace as set in proofreadPageNamespaceNames.
	 * The english language is used as fallback.
	 * @param string $key namespace key in the array
	 * @param string $lang language code by default the wiki language
	 * @return string
	 */
	protected static function getNamespaceName( $key, $lang = '' ) {
		global $proofreadPageNamespaceNames, $wgLanguageCode;

		if ( $lang === '' ) {
			$lang = $wgLanguageCode;
		}

		return $proofreadPageNamespaceNames[$lang][$key]
			?? $proofreadPageNamespaceNames['en'][$key];
	}

	/**
	 * Get the id of the namespace. Required that Mediawiki is loaded and
	 * ProofreadPageInit::initNamespace has been executed for the relevant namespace.
	 * Warning: It's not the function you search. If you want to know the index or page namespace
	 * id use ProofreadPage::getIndexNamespaceId() or ProofreadPage::getPageNamespaceId()
	 * @param string $key the key of the namespace in the i18n file
	 * @return int
	 */
	public static function getNamespaceId( $key ) {
		global $wgProofreadPageNamespaceIds;

		if ( !isset( $wgProofreadPageNamespaceIds[$key] ) ) {
			// The only case where $wgProofreadPageNamespaceIds is not set is
			// when a namespace with the default id already exist
			// and is not a prp namespace.
			throw new ConfigException( 'Namespace with id ' . self::$defaultNamespaceIds[$key] .
				' is already set ! ProofreadPage can\'t use his id in order to create ' .
				self::getNamespaceName( $key, 'en' ) .
				' namespace. Update your LocalSettings.php adding $wgProofreadPageNamespaceIds[' .
				$key . '] = /* NUMERICAL ID OF THE ' . self::getNamespaceName( $key, 'en' ) .
				' NAMESPACE */; AFTER the inclusion of Proofread Page' );
		}

		return $wgProofreadPageNamespaceIds[$key];
	}
}

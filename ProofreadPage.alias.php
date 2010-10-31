<?php
/**
 * Aliases for special pages
 *
 * @file
 * @ingroup Extensions
 */

$specialPageAliases = array();

/** English (English) */
$specialPageAliases['en'] = array(
	'IndexPages' => array( 'IndexPages' ),
	'PagesWithoutScans' => array( 'PagesWithoutScans' ),
);

/** Japanese (日本語) */
$specialPageAliases['ja'] = array(
	'IndexPages' => array( '索引ページ' ),
	'PagesWithoutScans' => array( 'スキャンのないページ' ),
);

/**
 * For backwards compatibility with MediaWiki 1.15 and earlier.
 */
$aliases =& $specialPageAliases;
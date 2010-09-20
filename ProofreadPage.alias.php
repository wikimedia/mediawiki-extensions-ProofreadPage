<?php
/**
 * Aliases for special pages
 *
 * @file
 * @ingroup Extensions
 */

$specialPageAliases = array();

/** English */
$specialPageAliases['en'] = array(
	'IndexPages'   => array( 'IndexPages' ),
	'PagesWithoutScans' => array( 'PagesWithoutScans' ),
);

/**
 * For backwards compatibility with MediaWiki 1.15 and earlier.
 */
$aliases =& $specialPageAliases;

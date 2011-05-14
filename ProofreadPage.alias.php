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

/** Arabic (العربية) */
$specialPageAliases['ar'] = array(
	'IndexPages' => array( 'صفحات_الفهرس' ),
	'PagesWithoutScans' => array( 'صفحات_بدون_فحص' ),
);

/** Breton (Brezhoneg) */
$specialPageAliases['br'] = array(
	'IndexPages' => array( 'PajennoùMeneger' ),
);

/** Haitian (Kreyòl ayisyen) */
$specialPageAliases['ht'] = array(
	'IndexPages' => array( 'PajEndèks' ),
);

/** Japanese (日本語) */
$specialPageAliases['ja'] = array(
	'IndexPages' => array( '索引ページ' ),
	'PagesWithoutScans' => array( 'スキャンのないページ' ),
);

/** Luxembourgish (Lëtzebuergesch) */
$specialPageAliases['lb'] = array(
	'PagesWithoutScans' => array( 'Säiten_ouni_Scan' ),
);

/** Macedonian (Македонски) */
$specialPageAliases['mk'] = array(
	'IndexPages' => array( 'ИндексираниСтраници' ),
);

/** Malayalam (മലയാളം) */
$specialPageAliases['ml'] = array(
	'IndexPages' => array( 'സൂചികാതാളുകൾ' ),
);

/** Dutch (Nederlands) */
$specialPageAliases['nl'] = array(
	'IndexPages' => array( 'Indexpaginas', 'Indexpagina\'s' ),
	'PagesWithoutScans' => array( 'PaginasZonderScans', 'Pagina\'sZonderScans' ),
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬) */
$specialPageAliases['no'] = array(
	'IndexPages' => array( 'Indekssider' ),
	'PagesWithoutScans' => array( 'Sider_uten_skanninger' ),
);

/** Turkish (Türkçe) */
$specialPageAliases['tr'] = array(
	'IndexPages' => array( 'SayfaEndeksle' ),
	'PagesWithoutScans' => array( 'TaramasızSayfalar' ),
);

/** Vèneto (Vèneto) */
$specialPageAliases['vec'] = array(
	'IndexPages' => array( 'PagineDeIndice' ),
	'PagesWithoutScans' => array( 'PagineSensaScansion' ),
);

/**
 * For backwards compatibility with MediaWiki 1.15 and earlier.
 */
$aliases =& $specialPageAliases;
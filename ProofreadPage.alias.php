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

/** Danish (Dansk) */
$specialPageAliases['da'] = array(
	'IndexPages' => array( 'Indekssider' ),
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
	'PagesWithoutScans' => array( 'СтранициБезПроверки' ),
);

/** Malayalam (മലയാളം) */
$specialPageAliases['ml'] = array(
	'IndexPages' => array( 'സൂചികാതാളുകൾ' ),
);

/** Nedersaksisch (Nedersaksisch) */
$specialPageAliases['nds-nl'] = array(
	'IndexPages' => array( 'Indexpagina\'s' ),
	'PagesWithoutScans' => array( 'Pagina\'s_zonder_deurlochting' ),
);

/** Dutch (Nederlands) */
$specialPageAliases['nl'] = array(
	'IndexPages' => array( 'Indexpaginas', 'Indexpagina\'s' ),
	'PagesWithoutScans' => array( 'PaginasZonderScans', 'Pagina\'sZonderScans' ),
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬) */
$specialPageAliases['nb'] = array(
	'IndexPages' => array( 'Indekssider' ),
	'PagesWithoutScans' => array( 'Sider_uten_skanninger' ),
);

/** Swedish (Svenska) */
$specialPageAliases['sv'] = array(
	'IndexPages' => array( 'Indexsidor' ),
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
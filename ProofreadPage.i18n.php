<?php
/**
 * Internationalisation file for extension ProofreadPage
 *
 * @file
 * @ingroup Extensions
 */

$messages = array();

$messages['en'] = array(
	'indexpages'                      => 'List of index pages',
	'pageswithoutscans'               => 'Pages without scans',
	'proofreadpage_desc'              => 'Allow easy comparison of text to the original scan',
	'proofreadpage_image'             => 'Image',
	'proofreadpage_index'             => 'Index',
	'proofreadpage_index_expected'    => 'Error: Index expected',
	'proofreadpage_nosuch_index'      => 'Error: No such index',
	'proofreadpage_nosuch_file'       => 'Error: No such file',
	'proofreadpage_badpage'           => 'Wrong format',
	'proofreadpage_badpagetext'       => 'The format of the page you attempted to save is incorrect.',
	'proofreadpage_indexdupe'         => 'Duplicate link',
	'proofreadpage_indexdupetext'     => 'Pages cannot be listed more than once on an index page.',
	'proofreadpage_nologin'           => 'Not logged in',
	'proofreadpage_nologintext'       => 'You must be [[Special:UserLogin|logged in]] to modify the proofreading status of pages.',
	'proofreadpage_notallowed'        => 'Change not allowed',
	'proofreadpage_notallowedtext'    => 'You are not allowed to change the proofreading status of this page.',
	'proofreadpage_dataconfig_badformatted' => 'Bug in data configuration',
	'proofreadpage_dataconfig_badformattedtext' => 'The page [[Mediawiki:Proofreadpage index data config]] is not in well-formatted JSON.',
	'proofreadpage_number_expected'   => 'Error: Numeric value expected',
	'proofreadpage_interval_too_large'=> 'Error: Interval too large',
	'proofreadpage_invalid_interval'  => 'Error: Invalid interval',
	'proofreadpage_nextpage'          => 'Next page',
	'proofreadpage_prevpage'          => 'Previous page',
	'proofreadpage_header'            => 'Header (noinclude):',
	'proofreadpage_body'              => 'Page body (to be transcluded):',
	'proofreadpage_footer'            => 'Footer (noinclude):',
	'proofreadpage_toggleheaders'     => 'toggle noinclude sections visibility',
	'proofreadpage_quality0_category' => 'Without text',
	'proofreadpage_quality1_category' => 'Not proofread',
	'proofreadpage_quality2_category' => 'Problematic',
	'proofreadpage_quality3_category' => 'Proofread',
	'proofreadpage_quality4_category' => 'Validated',
	'proofreadpage_quality0_message'  => 'This page does not need to be proofread',
	'proofreadpage_quality1_message'  => 'This page has not been proofread',
	'proofreadpage_quality2_message'  => 'There was a problem when proofreading this page',
	'proofreadpage_quality3_message'  => 'This page has been proofread',
	'proofreadpage_quality4_message'  => 'This page has been validated',
	'proofreadpage_index_status' => 'Index status',
	'proofreadpage_index_size' => 'Number of pages',
	'proofreadpage_specialpage_label_orderby' => 'Order by:',
	'proofreadpage_specialpage_label_key' => 'Search:',
	'proofreadpage_specialpage_label_sortascending' => 'Sort ascending',
	'proofreadpage_alphabeticalorder' => 'Alphabetical order',
	'proofreadpage_index_listofpages' => 'List of pages',
	'proofreadpage_image_message'     => 'Link to the index page',
	'proofreadpage_page_status'       => 'Page status',
	'proofreadpage_js_attributes'     => 'Author Title Year Publisher',
	'proofreadpage_index_attributes'  => 'Author
Title
Year|Year of publication
Publisher
Source
Image|Cover image
Pages||20
Remarks||10',
	'proofreadpage_default_header'        => '',
	'proofreadpage_default_footer'        => '<references/>',
	'proofreadpage_pages'        => "$2 {{PLURAL:$1|page|pages}}",
	'proofreadpage_specialpage_text'       => '',
	'proofreadpage_specialpage_legend'     => 'Search index pages',
	'proofreadpage_specialpage_searcherror' => 'Error in the search engine',
	'proofreadpage_specialpage_searcherrortext' => 'The search engine does not work. Sorry for the inconvenience.',
	'proofreadpage_source'         => 'Source',
	'proofreadpage_source_message' => 'Scanned edition used to establish this text',
	'right-pagequality'            => 'Modify page quality flag',
	'proofreadpage-section-tools'                  => 'Proofread tools',
	'proofreadpage-group-zoom'                     => 'Zoom',
	'proofreadpage-group-other'                    => 'Other',
	'proofreadpage-button-toggle-visibility-label' => 'Show/hide this page\'s header and footer',
	'proofreadpage-button-zoom-out-label'          => 'Zoom out',
	'proofreadpage-button-reset-zoom-label'        => 'Original size',
	'proofreadpage-button-zoom-in-label'           => 'Zoom in',
	'proofreadpage-button-toggle-layout-label'     => 'Vertical/horizontal layout',
	'proofreadpage-preferences-showheaders-label'  => 'Show header and footer fields when editing in the {{ns:page}} namespace',
	'proofreadpage-preferences-horizontal-layout-label'  => 'Use horizontal layout when editing in the {{ns:page}} namespace',
	'proofreadpage-indexoai-repositoryName' => 'Metadata of books from {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-url' => '',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadata of books managed by ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Schema not found',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'The $1 schema have not been found.',
	'proofreadpage-disambiguationspage' => 'Template:disambig',
);

/** Message documentation (Message documentation)
 * @author Aleator
 * @author EugeneZelenko
 * @author IAlex
 * @author Johnduhart
 * @author Jon Harald Søby
 * @author Kaajawa
 * @author Lloffiwr
 * @author McDutchie
 * @author Minh Nguyen
 * @author Mormegil
 * @author Nike
 * @author Octahedron80
 * @author Purodha
 * @author Rahuldeshmukh101
 * @author SPQRobin
 * @author Shirayuki
 * @author Siebrand
 * @author The Evil IP address
 * @author Umherirrender
 * @author Yknok29
 */
$messages['qqq'] = array(
	'indexpages' => '{{doc-special|IndexPages}}',
	'pageswithoutscans' => '{{doc-special|PagesWithoutScans}}
The special page lists texts without scans; that is, the texts that have not been transcluded into any other page.',
	'proofreadpage_desc' => '{{desc|name=Proofread Page|url=http://www.mediawiki.org/wiki/Extension:Proofread_Page}}',
	'proofreadpage_image' => '{{Identical|Image}}',
	'proofreadpage_index' => '{{Identical|Index}}',
	'proofreadpage_indexdupe' => 'Meaning: "This is a duplicate link"',
	'proofreadpage_nologin' => '{{Identical|Not logged in}}',
	'proofreadpage_notallowed' => 'Used as error title.

The body for this title is {{msg-mw|Proofreadpage notallowedtext}}.

Translate this as "Changing the proofreading status is not allowed".',
	'proofreadpage_notallowedtext' => 'Used as error message.

The title for this error is {{msg-mw|Proofreadpage notallowed}}.',
	'proofreadpage_dataconfig_badformatted' => 'Title of the error page when [[MediaWiki:Proofreadpage index data config]] is not in well-formatted JSON',
	'proofreadpage_dataconfig_badformattedtext' => 'Content of the error page when [[MediaWiki:Proofreadpage index data config]] is not in well-formatted JSON',
	'proofreadpage_number_expected' => 'The place where the data entry should be in numeric form',
	'proofreadpage_interval_too_large' => 'Error message displayed in content language when the "step" interval is too large (number of pages/step > 1000). See also:
* {{msg-mw|Proofreadpage invalid interval}}',
	'proofreadpage_invalid_interval' => 'See also:
* {{msg-mw|Proofreadpage interval too large}}',
	'proofreadpage_nextpage' => '{{Identical|Next page}}',
	'proofreadpage_prevpage' => '{{Identical|Previous page}}',
	'proofreadpage_toggleheaders' => 'Tooltip at right "+" button, at Wikisources, at namespace "Page".',
	'proofreadpage_quality0_category' => '{{Identical|Empty}}',
	'proofreadpage_quality1_category' => 'Category name where quality level 1 pages are added to',
	'proofreadpage_quality2_category' => 'Category name where quality level 2 pages are added to',
	'proofreadpage_quality3_category' => 'Category name where quality level 3 pages are added to. Read as in "proofRED" (past participle).
{{Identical|Proofread}}',
	'proofreadpage_quality4_category' => 'Category name where quality level 4 pages are added to',
	'proofreadpage_quality0_message' => 'Description of pages marked as a level 0 quality',
	'proofreadpage_quality1_message' => 'Description of pages marked as a level 1 quality',
	'proofreadpage_quality2_message' => 'Description of pages marked as a level 2 quality',
	'proofreadpage_quality3_message' => 'Description of pages marked as a level 3 quality',
	'proofreadpage_quality4_message' => 'Description of pages marked as a level 4 quality',
	'proofreadpage_index_status' => 'One of the possible sorts in [[Special:IndexPages]] : number of pages proofread and validated in a book.',
	'proofreadpage_index_size' => 'One of the possible sorts in [[Special:IndexPages]] : number of pages of a book.',
	'proofreadpage_specialpage_label_orderby' => 'Label of the order select in [[Special:IndexPages]]',
	'proofreadpage_specialpage_label_key' => 'Label of the search input in [https://en.wikisource.org/wiki/Special:IndexPages Special:IndexPages].
{{Identical|Search}}',
	'proofreadpage_specialpage_label_sortascending' => 'Label of a checkbox : sort the list of pages return by [[Special:IndexPages]] in ascending order or not.',
	'proofreadpage_alphabeticalorder' => 'One of the possible sorts in [[Special:IndexPages]]',
	'proofreadpage_image_message' => 'Used as link text. The link points to the image file page.',
	'proofreadpage_js_attributes' => 'Names of the variables on index pages, separated by spaces.',
	'proofreadpage_default_header' => '{{notranslate}}',
	'proofreadpage_default_footer' => '{{notranslate}}',
	'proofreadpage_pages' => 'Parameters:
* $1 - number of pages for use with PLURAL
* $2 - localised number of pages
{{Identical|Page}}',
	'proofreadpage_specialpage_text' => '{{notranslate}}',
	'proofreadpage_specialpage_searcherror' => 'Title of the error page when the search engine does not work',
	'proofreadpage_specialpage_searcherrortext' => 'Content of the error page when the search engine does not work',
	'proofreadpage_source' => '{{Identical|Source}}',
	'right-pagequality' => "{{doc-right|pagequality}}
This phrase is confusing: Modify 'page quality flag' or 'Modify page quality' flag ?",
	'proofreadpage-group-zoom' => '{{Identical|Zoom}}',
	'proofreadpage-group-other' => 'This is a group header in the Proofread Page extension preferences panel for "miscellaneous" settings.
{{Identical|Other}}',
	'proofreadpage-button-toggle-visibility-label' => 'Tooltip text in button for include and noinclude edit boxes toggle, only visible in edit mode.',
	'proofreadpage-button-zoom-out-label' => 'Tooltip text in button for zoom out, only visible in edit mode.
{{Identical|Zoom out}}',
	'proofreadpage-button-zoom-in-label' => 'Tooltip text in button for zoom in, only visible in edit mode.
{{Identical|Zoom in}}',
	'proofreadpage-button-toggle-layout-label' => 'Tooltip text in button for horizontal or vertical layout toggle, only visible in edit mode.',
	'proofreadpage-preferences-showheaders-label' => 'Description of the checkbox preference to show/hide the header and footer fields in the edit form of the Page namespace.',
	'proofreadpage-preferences-horizontal-layout-label' => 'Description of the checkbox preference to turn on horizontal layout in the edit form of the Page namespace.',
	'proofreadpage-indexoai-repositoryName' => 'Name of the OAI-PMH API.',
	'proofreadpage-indexoai-eprint-content-url' => '{{notranslate}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Short description of the OAI-PMH API.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Title of the error when a requested XML Schema does not exist.',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Text of the error when a requested XML schema does not exist. Parameters:
* $1 is name of the schema.',
	'proofreadpage-disambiguationspage' => 'This message is the name of the template used for marking disambiguation pages. It is used to find all pages which link to disambiguation pages.

{{doc-important|Don\'t translate the "Template:" part!}}
{{Identical|Template:disambig}}',
);

/** Achinese (Acèh)
 * @author Si Gam Acèh
 */
$messages['ace'] = array(
	'proofreadpage_specialpage_label_key' => 'Mita:',
);

/** Tunisian Spoken Arabic (   زَوُن)
 * @author Csisc
 */
$messages['aeb'] = array(
	'indexpages' => 'قائمة صفحات الفهرس',
	'pageswithoutscans' => 'صفحات من دون تفحص',
	'proofreadpage_desc' => 'يسمح بمقارنة سهلة للنص مع المسح الأصلي',
	'proofreadpage_image' => 'صورة',
	'proofreadpage_index' => 'فهرس',
	'proofreadpage_index_expected' => 'خطأ: فهرس تم توقعه',
	'proofreadpage_nosuch_index' => 'خطأ: لا فهرس كهذا',
	'proofreadpage_nosuch_file' => 'خطأ: لا ملف كهذا',
	'proofreadpage_badpage' => 'تنسيق خاطئ',
	'proofreadpage_badpagetext' => 'تنسيق الصفحة التي تحاول حفظها غير صحيح.',
	'proofreadpage_indexdupe' => 'رابط نظير',
	'proofreadpage_indexdupetext' => 'لا يمكن سرد الصفحة أكثر من في صفحة الفهرس.',
	'proofreadpage_nologin' => 'غير مسجل الدخول',
	'proofreadpage_nologintext' => 'يجب أن تكون [[Special:UserLogin|مُسجلًا الدخول]] لتعدّل حالة تدقيق الصفحات.',
	'proofreadpage_notallowed' => 'التغيير غير مسموح به',
	'proofreadpage_notallowedtext' => 'لا يسمح لك بتغيير حالة تدقيق هذه الصفحة.',
	'proofreadpage_number_expected' => 'خطأ: قيمة عددية تم توقعها',
	'proofreadpage_interval_too_large' => 'خطأ: الفترة كبيرة جدا',
	'proofreadpage_invalid_interval' => 'خطأ: فترة غير صحيحة',
	'proofreadpage_nextpage' => 'الصفحة التالية',
	'proofreadpage_prevpage' => 'الصفحة السابقة',
	'proofreadpage_header' => 'العنوان (غير مضمن):',
	'proofreadpage_body' => 'جسم الصفحة (للتضمين):',
	'proofreadpage_footer' => 'ذيل (غير مضمن):',
	'proofreadpage_toggleheaders' => 'تغيير رؤية أقسام noinclude',
	'proofreadpage_quality0_category' => 'بدون نص',
	'proofreadpage_quality1_category' => 'ليست مُدقّقة',
	'proofreadpage_quality2_category' => 'به مشاكل',
	'proofreadpage_quality3_category' => 'مُدقّقة',
	'proofreadpage_quality4_category' => 'مُصحّحة',
	'proofreadpage_quality0_message' => 'لا تحتاج هذه الصفحة إلى تدقيق',
	'proofreadpage_quality1_message' => 'لم تدقّق هذه الصفحة',
	'proofreadpage_quality2_message' => 'ثمة مشكلة عند تدقيق هذه الصفحة',
	'proofreadpage_quality3_message' => 'دُقّقت هذه الصفحة',
	'proofreadpage_quality4_message' => 'صُحّحت هذه الصفحة',
	'proofreadpage_index_listofpages' => 'قائمة الصفحات',
	'proofreadpage_image_message' => 'وصلة إلى صفحة الفهرس',
	'proofreadpage_page_status' => 'حالة الصفحة',
	'proofreadpage_js_attributes' => 'المؤلف العنوان السنة الناشر',
	'proofreadpage_index_attributes' => 'المؤلف
العنوان
السنة|سنة النشر
الناشر
المصدر
الصورة|صورة الغلاف
الصفحات||20
الملاحظات||10',
	'proofreadpage_pages' => "$2 {{PLURAL:$1|ss'af7a|ss'afa7at}}",
	'proofreadpage_specialpage_legend' => 'بحث صفحات الفهرس',
	'proofreadpage_source' => 'المصدر',
	'proofreadpage_source_message' => 'الإصدارة المفحوصة المستخدمة لإنشاء هذا النص',
	'right-pagequality' => 'عدل علامة جودة الصفحة',
	'proofreadpage-section-tools' => 'أدوات تدقيق',
	'proofreadpage-group-zoom' => 'kabber',
	'proofreadpage-group-other' => 'غير ذلك',
	'proofreadpage-button-toggle-visibility-label' => 'أظهر أو أخف ترويسة الصفحة وتذييلتها',
	'proofreadpage-button-zoom-out-label' => 'تصغير',
	'proofreadpage-button-reset-zoom-label' => 'رد التكبير',
	'proofreadpage-button-zoom-in-label' => 'تكبير',
	'proofreadpage-button-toggle-layout-label' => 'تخطيط أفقي أو رأسي',
	'proofreadpage-preferences-showheaders-label' => "warri plaiss' el entête wel pied de page wa9t il edition fil faragh mte3 el page {{ns:page}}",
	'proofreadpage-preferences-horizontal-layout-label' => "ista3mel i5raj ofo9i wa9t tekteb fil misse7a mte3 ess'af7a {{ns:page}}",
);

/** Afrikaans (Afrikaans)
 * @author Naudefj
 * @author SPQRobin
 * @author පසිඳු කාවින්ද
 */
$messages['af'] = array(
	'indexpages' => 'Lys van indeks-bladsye',
	'pageswithoutscans' => 'Bladsye sonder skanderings',
	'proofreadpage_desc' => 'Maak dit moontlik om teks maklik met die oorspronklike skandering te vergelyk',
	'proofreadpage_image' => 'Beeld',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => 'Fout: indeks verwag',
	'proofreadpage_nosuch_index' => 'Fout: die indeks bestaan nie',
	'proofreadpage_nosuch_file' => 'Fout: die lêer bestaan nie',
	'proofreadpage_badpage' => 'Verkeerde formaat',
	'proofreadpage_badpagetext' => 'Die formaat van die bladsy wat u probeer stoor is verkeerd.',
	'proofreadpage_indexdupe' => 'Dubbele skakel',
	'proofreadpage_indexdupetext' => "Bladsye kan nie meer as een keer op 'n indeksbladsy gelys word nie.",
	'proofreadpage_nologin' => 'Nie aangeteken nie',
	'proofreadpage_nologintext' => 'U moet [[Special:UserLogin|aanmeld]] om die proeflees-status van bladsye te kan wysig.',
	'proofreadpage_notallowed' => 'Wysiging is nie toegelaat nie',
	'proofreadpage_notallowedtext' => 'U mag nie die proeflees-status van hierdie bladsy wysig nie.',
	'proofreadpage_number_expected' => 'Fout: numeriese waarde verwag',
	'proofreadpage_interval_too_large' => 'Fout: die interval is te groot',
	'proofreadpage_invalid_interval' => 'Fout: die interval is ongeldig',
	'proofreadpage_nextpage' => 'Volgende bladsy',
	'proofreadpage_prevpage' => 'Vorige bladsy',
	'proofreadpage_header' => 'Opskrif (geen inklusie):',
	'proofreadpage_body' => 'Bladsyteks (vir transklusie):',
	'proofreadpage_footer' => 'Voetteks (geen inklusie):',
	'proofreadpage_toggleheaders' => 'wysig sigbaarheid van afdelings sonder transklusie',
	'proofreadpage_quality0_category' => 'Geen teks nie',
	'proofreadpage_quality1_category' => 'Nie geproeflees nie',
	'proofreadpage_quality2_category' => 'Onvolledig',
	'proofreadpage_quality3_category' => 'Proeflees',
	'proofreadpage_quality4_category' => 'Gekontroleer',
	'proofreadpage_quality0_message' => 'Hierdie bladsy hoef nie geproeflees te word nie',
	'proofreadpage_quality1_message' => 'Hierdie bladsy is nie geproeflees nie',
	'proofreadpage_quality2_message' => "Daar was 'n probleem tydens die proeflees van hierdie bladsy",
	'proofreadpage_quality3_message' => 'Hierdie bladsy is geproeflees',
	'proofreadpage_quality4_message' => 'Hierdie bladsy is gekontroleer',
	'proofreadpage_index_listofpages' => 'Lys van bladsye',
	'proofreadpage_image_message' => 'Skakel na die indeksblad',
	'proofreadpage_page_status' => 'Bladsystatus',
	'proofreadpage_js_attributes' => 'Outeur Titel Jaar Uitgewer',
	'proofreadpage_index_attributes' => 'Outeur
Titel
Jaar|Jaar van publikasie
Uitgewer
Bron
Beeld|Omslag
Bladsye||20
Opmerkings||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|bladsy|bladsye}}',
	'proofreadpage_specialpage_legend' => 'Deursoek indeks-bladsye',
	'proofreadpage_source' => 'Bron',
	'proofreadpage_source_message' => 'Geskandeerde uitgawe waarop hierdie teks gebaseer is',
	'right-pagequality' => 'Verander bladsy kwaliteit vlag',
	'proofreadpage-section-tools' => 'proeflees gereedskap',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Ander',
	'proofreadpage-button-toggle-visibility-label' => 'Wys / verberg hierdie bladsy se kop-en voet',
	'proofreadpage-button-zoom-out-label' => 'Uitzoom',
	'proofreadpage-button-reset-zoom-label' => 'Herstel zoom',
	'proofreadpage-button-zoom-in-label' => 'Inzoom',
	'proofreadpage-button-toggle-layout-label' => 'Vertikale/horisontale uitleg',
);

/** Amharic (አማርኛ)
 * @author Codex Sinaiticus
 */
$messages['am'] = array(
	'proofreadpage_nextpage' => 'የሚቀጥለው ገጽ',
);

/** Aragonese (aragonés)
 * @author Juanpabl
 */
$messages['an'] = array(
	'indexpages' => 'Lista de pachinas indexadas',
	'pageswithoutscans' => 'Pachinas sin escaneyos',
	'proofreadpage_desc' => 'Premite contimparar de trazas simples o testo con o escaneyo orichinal',
	'proofreadpage_image' => 'Imachen',
	'proofreadpage_index' => 'Endice',
	'proofreadpage_index_expected' => "Error: s'asperaba un indiz",
	'proofreadpage_nosuch_index' => 'Error: no i hai garra indiz',
	'proofreadpage_nosuch_file' => 'Error: no i hai garra fichero',
	'proofreadpage_badpage' => 'Formato erronio',
	'proofreadpage_badpagetext' => "O formato d'a pachina que miró de gravar ye incorrecto.",
	'proofreadpage_indexdupe' => 'Vinclo duplicau',
	'proofreadpage_indexdupetext' => "As pachinas no se pueden listar mas d'una vegada en una pachina indiz.",
	'proofreadpage_nologin' => 'No ha encetau a sesión',
	'proofreadpage_nologintext' => "Ha d'haber [[Special:UserLogin|encetau una sesión]] ta modificar o status de corrección d'as pachinas.",
	'proofreadpage_notallowed' => 'Cambeo no permitiu',
	'proofreadpage_notallowedtext' => "No se permite de cambiar o estatus de corrección d'ista pachina.",
	'proofreadpage_number_expected' => "Error: s'asperaba una valura numerica",
	'proofreadpage_interval_too_large' => 'Error: intervalo masiau gran',
	'proofreadpage_invalid_interval' => 'Error: intervalo invalido',
	'proofreadpage_nextpage' => 'Pachina siguient',
	'proofreadpage_prevpage' => 'Pachina anterior',
	'proofreadpage_header' => 'Cabecera (noinclude):',
	'proofreadpage_body' => "Cuerpo d'a pachina (to be transcluded):",
	'proofreadpage_footer' => 'Piet de pachina (noinclude):',
	'proofreadpage_toggleheaders' => "cambiar a bisibilidat d'as seccions noinclude",
	'proofreadpage_quality0_category' => 'Sin texto',
	'proofreadpage_quality1_category' => 'Pachina no correchita',
	'proofreadpage_quality2_category' => 'Pachina problematica',
	'proofreadpage_quality3_category' => 'Pachina correchita',
	'proofreadpage_quality4_category' => 'Validata',
	'proofreadpage_quality0_message' => "Ista pachina no precisa d'estar correchida",
	'proofreadpage_quality1_message' => "Ista pachina no s'ha correchiu",
	'proofreadpage_quality2_message' => 'I habió un problema entre que se correchiba ista pachina',
	'proofreadpage_quality3_message' => "Ista pachina s'ha correchiu",
	'proofreadpage_quality4_message' => "Ista pachina s'ha validau",
	'proofreadpage_index_listofpages' => 'Lista de pachinas',
	'proofreadpage_image_message' => "Vinclo t'a pachina d'endice",
	'proofreadpage_page_status' => "Estau d'a pachina",
	'proofreadpage_js_attributes' => 'Autor Títol Anyo Editorial',
	'proofreadpage_index_attributes' => 'Autor
Títol
Anyo|Anyo de publicación
Editorial
Fuent
Imachen|Imachen de portalada
Pachinas||20
Notas||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pachina|pachinas}}',
	'proofreadpage_specialpage_legend' => "Mirar en as pachinas d'indiz",
	'proofreadpage_source' => 'Fuent',
	'proofreadpage_source_message' => 'Edición escaneyada usada ta establir iste texto',
	'right-pagequality' => "Modificar a marca de calidat d'a pachina",
	'proofreadpage-section-tools' => 'Ferramientas de corrección',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Atros',
	'proofreadpage-button-toggle-visibility-label' => "Amostrar/amagar o encabezamiento y o piet d'ista pachina",
	'proofreadpage-button-zoom-out-label' => 'Zoom out (aluenyar)',
	'proofreadpage-button-reset-zoom-label' => 'Grandaria orichinal',
	'proofreadpage-button-zoom-in-label' => 'Zoom in (amanar)',
	'proofreadpage-button-toggle-layout-label' => 'Disposición vertical/horizontal',
);

/** Arabic (العربية)
 * @author DRIHEM
 * @author Meno25
 * @author Orango
 * @author OsamaK
 * @author زكريا
 */
$messages['ar'] = array(
	'indexpages' => 'قائمة صفحات الفهرس',
	'pageswithoutscans' => 'صفحات من دون تفحص',
	'proofreadpage_desc' => 'يسمح بمقارنة سهلة للنص مع المسح الأصلي',
	'proofreadpage_image' => 'صورة',
	'proofreadpage_index' => 'فهرس',
	'proofreadpage_index_expected' => 'خطأ: فهرس تم توقعه',
	'proofreadpage_nosuch_index' => 'خطأ: لا فهرس كهذا',
	'proofreadpage_nosuch_file' => 'خطأ: لا ملف كهذا',
	'proofreadpage_badpage' => 'تنسيق خاطئ',
	'proofreadpage_badpagetext' => 'تنسيق الصفحة التي تحاول حفظها غير صحيح.',
	'proofreadpage_indexdupe' => 'رابط نظير',
	'proofreadpage_indexdupetext' => 'لا يمكن سرد الصفحة أكثر من في صفحة الفهرس.',
	'proofreadpage_nologin' => 'غير مسجل الدخول',
	'proofreadpage_nologintext' => 'يجب أن تكون [[Special:UserLogin|مُسجلًا الدخول]] لتعدّل حالة تدقيق الصفحات.',
	'proofreadpage_notallowed' => 'التغيير غير مسموح به',
	'proofreadpage_notallowedtext' => 'لا يسمح لك بتغيير حالة تدقيق هذه الصفحة.',
	'proofreadpage_number_expected' => 'خطأ: قيمة عددية تم توقعها',
	'proofreadpage_interval_too_large' => 'خطأ: الفترة كبيرة جدا',
	'proofreadpage_invalid_interval' => 'خطأ: فترة غير صحيحة',
	'proofreadpage_nextpage' => 'الصفحة التالية',
	'proofreadpage_prevpage' => 'الصفحة السابقة',
	'proofreadpage_header' => 'العنوان (غير مضمن):',
	'proofreadpage_body' => 'جسم الصفحة (للتضمين):',
	'proofreadpage_footer' => 'ذيل (غير مضمن):',
	'proofreadpage_toggleheaders' => 'تغيير رؤية أقسام noinclude',
	'proofreadpage_quality0_category' => 'بدون نص',
	'proofreadpage_quality1_category' => 'ليست مُدقّقة',
	'proofreadpage_quality2_category' => 'به مشاكل',
	'proofreadpage_quality3_category' => 'مُدقّقة',
	'proofreadpage_quality4_category' => 'مُصحّحة',
	'proofreadpage_quality0_message' => 'لا تحتاج هذه الصفحة إلى تدقيق',
	'proofreadpage_quality1_message' => 'لم تدقّق هذه الصفحة',
	'proofreadpage_quality2_message' => 'ثمة مشكلة عند تدقيق هذه الصفحة',
	'proofreadpage_quality3_message' => 'دُقّقت هذه الصفحة',
	'proofreadpage_quality4_message' => 'صُحّحت هذه الصفحة',
	'proofreadpage_index_listofpages' => 'قائمة الصفحات',
	'proofreadpage_image_message' => 'وصلة إلى صفحة الفهرس',
	'proofreadpage_page_status' => 'حالة الصفحة',
	'proofreadpage_js_attributes' => 'المؤلف العنوان السنة الناشر',
	'proofreadpage_index_attributes' => 'المؤلف
العنوان
السنة|سنة النشر
الناشر
المصدر
الصورة|صورة الغلاف
الصفحات||20
الملاحظات||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|صفحة|صفحات}}',
	'proofreadpage_specialpage_legend' => 'بحث صفحات الفهرس',
	'proofreadpage_source' => 'المصدر',
	'proofreadpage_source_message' => 'الإصدارة المفحوصة المستخدمة لإنشاء هذا النص',
	'right-pagequality' => 'عدل علامة جودة الصفحة',
	'proofreadpage-section-tools' => 'أدوات تدقيق',
	'proofreadpage-group-zoom' => 'تكبير وتصغير',
	'proofreadpage-group-other' => 'غير ذلك',
	'proofreadpage-button-toggle-visibility-label' => 'أظهر أو أخف ترويسة الصفحة وتذييلتها',
	'proofreadpage-button-zoom-out-label' => 'تصغير',
	'proofreadpage-button-reset-zoom-label' => 'حجم أصلي',
	'proofreadpage-button-zoom-in-label' => 'تكبير',
	'proofreadpage-button-toggle-layout-label' => 'تخطيط أفقي أو رأسي',
	'proofreadpage-preferences-showheaders-label' => 'أظهر ترويس الصفحة وتذييلها عند التحرير في نطاق {{ns:page}}',
);

/** Aramaic (ܐܪܡܝܐ)
 * @author Basharh
 * @author Michaelovic
 */
$messages['arc'] = array(
	'proofreadpage_image' => 'ܨܘܪܬܐ',
	'proofreadpage_index' => 'ܩܘܕܝܟܘܣ',
	'proofreadpage_indexdupe' => 'ܐܣܘܪܐ ܥܦܝܦܐ',
	'proofreadpage_nologin' => 'ܠܐ ܥܠܝܠܐ',
	'proofreadpage_nextpage' => 'ܦܐܬܐ ܕܒܬܪ',
	'proofreadpage_prevpage' => 'ܦܐܬܐ ܕܩܕܡ',
	'proofreadpage_quality0_category' => 'ܕܠܐ ܟܬܒܬܐ',
	'proofreadpage_quality1_category' => 'ܟܬܒܬܐ ܠܐ ܢܘܩܕܬܐ',
	'proofreadpage_quality3_category' => 'ܟܬܒܬܐ ܢܘܩܕܬܐ',
	'proofreadpage_index_listofpages' => 'ܡܟܬܒܘܬܐ ܕܦܐܬܬ̈ܐ',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|ܦܐܬܐ|ܦܐܬܬܐ}}',
	'proofreadpage_source' => 'ܡܒܘܥܐ',
	'proofreadpage-section-tools' => 'ܡܐܢ̈ܐ ܕܢܩܕܘܬܐ',
	'proofreadpage-group-zoom' => 'ܡܩܪܒ/ܡܙܥܪ',
	'proofreadpage-group-other' => 'ܐܚܪܢܐ',
	'proofreadpage-button-zoom-out-label' => 'ܡܙܥܪ',
	'proofreadpage-button-reset-zoom-label' => 'ܥܓܪܐ ܫܪܫܝܐ',
	'proofreadpage-button-zoom-in-label' => 'ܡܩܪܒ',
);

/** Mapuche (mapudungun)
 * @author Remember the dot
 */
$messages['arn'] = array(
	'proofreadpage_namespace' => 'Pakina',
);

/** Egyptian Spoken Arabic (مصرى)
 * @author Ghaly
 * @author Meno25
 * @author Ramsis II
 */
$messages['arz'] = array(
	'proofreadpage_desc' => 'بيسمح بمقارنة سهلة للنص مع المسح الأصلي',
	'proofreadpage_image' => 'صوره',
	'proofreadpage_index' => 'فهرس',
	'proofreadpage_nextpage' => 'الصفحة الجاية',
	'proofreadpage_prevpage' => 'الصفحة اللى فاتت',
	'proofreadpage_header' => 'الراس(مش متضمن):',
	'proofreadpage_body' => 'جسم الصفحة (للتضمين):',
	'proofreadpage_footer' => 'ديل(مش متضمن):',
	'proofreadpage_toggleheaders' => 'تغيير رؤية أقسام noinclude',
	'proofreadpage_quality1_category' => 'مش مثبت قراية',
	'proofreadpage_quality2_category' => 'بيعمل مشاكل',
	'proofreadpage_quality3_category' => 'مثبت قراية',
	'proofreadpage_quality4_category' => 'متصحح',
	'proofreadpage_index_listofpages' => 'لستة الصفحات',
	'proofreadpage_image_message' => 'لينك لصفحة الفهرس',
	'proofreadpage_page_status' => 'حالة الصفحة',
	'proofreadpage_js_attributes' => 'المؤلف العنوان السنة الناشر',
	'proofreadpage_index_attributes' => 'المؤلف
العنوان
السنة|سنة النشر
الناشر
المصدر
الصورة|صورة الغلاف
الصفحات||20
الملاحظات||10',
);

/** Assamese (অসমীয়া)
 * @author Bishnu Saikia
 * @author Gitartha.bordoloi
 */
$messages['as'] = array(
	'indexpages' => 'সূচীৰ পৃষ্ঠাৰ তালিকা',
	'pageswithoutscans' => 'স্কেন নকৰা পৃষ্ঠাসমূহ',
	'proofreadpage_desc' => 'মূল স্কেনৰ লগত পাঠ্যৰ সহজ তুলনা অনুমোদন কৰক',
	'proofreadpage_image' => 'চিত্ৰ',
	'proofreadpage_index' => 'সূচী',
	'proofreadpage_index_expected' => 'ত্ৰুটি: সূচী আশা কৰা হৈছে',
	'proofreadpage_nosuch_index' => 'ত্ৰুটি: তেনে কোনো সূচী নাই',
	'proofreadpage_nosuch_file' => 'ত্ৰুটি: তেনে কোনো ফাইল নাই',
	'proofreadpage_badpage' => 'ভুল ফৰ্মেট',
	'proofreadpage_badpagetext' => 'আপুনি সাঁচিবলৈ বিচৰা পৃষ্ঠাৰ ফৰ্মেট অশুদ্ধ।',
	'proofreadpage_indexdupe' => 'প্ৰতিলিপি সংযোগ',
	'proofreadpage_indexdupetext' => 'সূচীত পৃষ্ঠাসমূহ এবাৰতকৈ বেছি তালিকাভুক্ত কৰিব নোৱাৰি',
	'proofreadpage_nologin' => 'প্ৰৱেশ কৰা নাই',
	'proofreadpage_nologintext' => 'পৃষ্ঠাখনৰ প্ৰুফৰিডিং অৱস্থা সলাবলৈ আপুনি [[Special:UserLogin|লগ্‌ ইন]] কৰিব লাগিব।',
	'proofreadpage_notallowed' => 'পৰিৱৰ্তনৰ অনুমতি নাই',
	'proofreadpage_notallowedtext' => 'এই পৃষ্ঠাৰ প্ৰুফৰিডিং অৱস্থা সলাবলৈ আপোনাৰ অনুমতি নাই।',
	'proofreadpage_number_expected' => 'ত্ৰুটি: সাংখ্যিক মূল্য আশা কৰা হৈছে',
	'proofreadpage_interval_too_large' => 'ত্ৰুটি: ব্যৱধান অতি বেছি',
	'proofreadpage_invalid_interval' => 'ত্ৰুটি: অবৈধ ব্যৱধান',
	'proofreadpage_nextpage' => 'পৰৱৰ্তী পৃষ্ঠা',
	'proofreadpage_prevpage' => 'পূৰ্ববৰ্তী পৃষ্ঠা',
	'proofreadpage_header' => 'শিৰোনামা (noinclude):',
	'proofreadpage_body' => 'পৃষ্ঠাৰ প্ৰধান অংশ (to be transcluded):',
	'proofreadpage_footer' => 'পাদটীকা (noinclude):',
	'proofreadpage_toggleheaders' => 'noinclude অনুচ্ছেদৰ প্ৰত্যক্ষতা সলাওক',
	'proofreadpage_quality0_category' => 'পাঠ্য নথকা',
	'proofreadpage_quality1_category' => 'মূদ্ৰণ সংশোধন কৰা হোৱা নাই',
	'proofreadpage_quality2_category' => 'সমস্যাজৰ্জৰ',
	'proofreadpage_quality3_category' => 'মুদ্ৰণ সংশোধন',
	'proofreadpage_quality4_category' => 'বৈধকৰণ',
	'proofreadpage_quality0_message' => 'এই পৃষ্ঠাখন প্ৰুফ সংশোধনৰ প্ৰয়োজন নাই',
	'proofreadpage_quality1_message' => 'এই পৃষ্ঠাখনৰ প্ৰুফ সংশোধন কৰা হোৱা নাই',
	'proofreadpage_quality2_message' => 'এই পৃষ্ঠাখনৰ প্ৰুফৰিডিঙত এটা সমস্যা হৈছে',
	'proofreadpage_quality3_message' => 'এই পৃষ্ঠাখনৰ মূদ্ৰণ সংশোধন কৰা হৈছে',
	'proofreadpage_quality4_message' => 'এই পৃষ্ঠাখনৰ বৈধকৰণ কৰা হৈছে',
	'proofreadpage_index_status' => 'সূচী স্থিতি',
	'proofreadpage_index_size' => 'পৃষ্ঠাৰ সংখ্যা',
	'proofreadpage_specialpage_label_orderby' => 'ক্ৰমবদ্ধ কৰক:',
	'proofreadpage_specialpage_label_key' => 'অনুসন্ধান:',
	'proofreadpage_specialpage_label_sortascending' => 'ক্ৰমবৰ্দ্ধমান ভাৱে ক্ৰমবদ্ধ কৰক',
	'proofreadpage_alphabeticalorder' => 'বৰ্ণানুক্ৰমিক স্থিতি',
	'proofreadpage_index_listofpages' => 'পৃষ্ঠাসমূহৰ তালিকা',
	'proofreadpage_image_message' => 'সূচী পৃষ্ঠালৈ সংযোগ',
	'proofreadpage_page_status' => 'পৃষ্ঠাৰ স্থিতি',
	'proofreadpage_js_attributes' => 'লেখক শিৰোনামা বছৰ প্ৰকাশক',
	'proofreadpage_index_attributes' => 'লেখক
শিৰোনামা
বছৰ|প্ৰকাশৰ বছৰ
প্ৰকাশক
উৎস
চিত্ৰ|প্ৰচ্ছদ
পৃষ্ঠা||20
মন্তব্য||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|খন পৃষ্ঠা| খন পৃষ্ঠা}}',
	'proofreadpage_specialpage_legend' => 'সূচী পৃষ্ঠাসমূহ অনুসন্ধান কৰক',
	'proofreadpage_specialpage_searcherror' => 'অনুসন্ধান যন্ত্ৰত ত্ৰুটী',
	'proofreadpage_specialpage_searcherrortext' => 'অনুসন্ধান যন্ত্ৰই কাম কৰা নাই। অসুবিধাৰ বাবে দুঃখিত।',
	'proofreadpage_source' => 'উৎস',
	'proofreadpage_source_message' => 'এই পাঠ্য প্ৰতিষ্ঠা কৰিবলৈ ব্যৱহৃত স্কেন কৰা সংস্কৰণ',
	'right-pagequality' => 'পৃষ্ঠা গুণাগুণ নিচান পৰিৱৰ্তন কৰক',
	'proofreadpage-section-tools' => 'মূদ্ৰন সংশোধনৰ সঁজুলি',
	'proofreadpage-group-zoom' => 'ডাঙৰ কৰক',
	'proofreadpage-group-other' => 'অন্য',
	'proofreadpage-button-toggle-visibility-label' => 'এই পৃষ্ঠাৰ শিৰোনামা আৰু পাদটীকা দেখুৱাওক/লুকুৱাওক',
	'proofreadpage-button-zoom-out-label' => 'সৰু কৰক',
	'proofreadpage-button-reset-zoom-label' => 'মূল আকাৰ',
	'proofreadpage-button-zoom-in-label' => 'ডাঙৰ কৰক',
	'proofreadpage-button-toggle-layout-label' => 'থিয়/পথালি সজ্জা',
	'proofreadpage-preferences-showheaders-label' => 'পৃষ্ঠা নামস্থানত সম্পাদনা কৰোঁতে শিৰোনামা আৰু পাদটীকা স্থান দেখুৱাওক',
	'proofreadpage-preferences-horizontal-layout-label' => "{{ns:page}} নামস্থানত সম্পাদনা কৰোঁতে আনুভূমিক লে'আউট ব্যৱহাৰ কৰক।",
);

/** Asturian (asturianu)
 * @author Esbardu
 * @author Xuacu
 */
$messages['ast'] = array(
	'indexpages' => 'Llista de páxines índiz',
	'pageswithoutscans' => 'Páxines ensin escaneos',
	'proofreadpage_desc' => 'Permite una comparanza cenciella del testu col escaniáu orixinal',
	'proofreadpage_image' => 'Imaxe',
	'proofreadpage_index' => 'Índiz',
	'proofreadpage_index_expected' => 'Error: esperábase un índiz',
	'proofreadpage_nosuch_index' => 'Error: nun esiste esi índiz',
	'proofreadpage_nosuch_file' => 'Error: nun esiste esi ficheru',
	'proofreadpage_badpage' => 'Formatu incorreutu',
	'proofreadpage_badpagetext' => "El formatu de la páxina qu'intentó guardar ye incorreutu.",
	'proofreadpage_indexdupe' => 'Enllaz duplicáu',
	'proofreadpage_indexdupetext' => "Nun se puen llistar les páxines más d'una vez n'una páxina d'índiz.",
	'proofreadpage_nologin' => 'Nun anició sesión',
	'proofreadpage_nologintext' => "Tien d'[[Special:UserLogin|aniciar sesión]] pa camudar l'estáu de correición de les páxines.",
	'proofreadpage_notallowed' => 'Cambiu nun permitíu',
	'proofreadpage_notallowedtext' => "Nun ta autorizáu a camudar l'estáu de revisión d'esta páxina.",
	'proofreadpage_dataconfig_badformatted' => 'Error na configuración de los datos',
	'proofreadpage_dataconfig_badformattedtext' => 'La páxina "[[Mediawiki:Proofreadpage index data config]]" nun tien un formatu JSON correctu.',
	'proofreadpage_number_expected' => 'Error: esperabase un valor numbéricu',
	'proofreadpage_interval_too_large' => 'Error: intervalu demasiao grande',
	'proofreadpage_invalid_interval' => 'Error: intervalu inválidu',
	'proofreadpage_nextpage' => 'Páxina siguiente',
	'proofreadpage_prevpage' => 'Páxina anterior',
	'proofreadpage_header' => 'Cabecera (noinclude):',
	'proofreadpage_body' => 'Cuerpu de la páxina (pa trescluyir):',
	'proofreadpage_footer' => 'Pie de páxina (noinclude):',
	'proofreadpage_toggleheaders' => 'activar/desactivar la visibilidá de les seiciones noinclude',
	'proofreadpage_quality0_category' => 'Ensin testu',
	'proofreadpage_quality1_category' => 'Non correxida',
	'proofreadpage_quality2_category' => 'Problemática',
	'proofreadpage_quality3_category' => 'Correxida',
	'proofreadpage_quality4_category' => 'Validada',
	'proofreadpage_quality0_message' => 'Esta páxina nun necesita correición',
	'proofreadpage_quality1_message' => 'Esta páxina nun se corrixó',
	'proofreadpage_quality2_message' => 'Hubo un problema al correxir esta páxina',
	'proofreadpage_quality3_message' => 'Esta páxina ta correxida',
	'proofreadpage_quality4_message' => 'Esta páxina ta validada',
	'proofreadpage_index_status' => 'Estáu del índiz',
	'proofreadpage_index_size' => 'Númberu de páxines',
	'proofreadpage_specialpage_label_orderby' => 'Ordenar por:',
	'proofreadpage_specialpage_label_key' => 'Guetar:',
	'proofreadpage_specialpage_label_sortascending' => 'Orde ascendente',
	'proofreadpage_alphabeticalorder' => 'Orde alfabéticu',
	'proofreadpage_index_listofpages' => 'Llista de páxines',
	'proofreadpage_image_message' => 'Enllaciar a la páxina índiz',
	'proofreadpage_page_status' => 'Estatus de la páxina',
	'proofreadpage_js_attributes' => 'Autor Títulu Añu Editor',
	'proofreadpage_index_attributes' => 'Autor
Títulu
Añu|Añu de publicación
Editor
Fonte
Imaxe|Imaxe de la cubierta
Páxines||20
Comentarios||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|páxina|páxines}}',
	'proofreadpage_specialpage_legend' => "Buscar nes páxines d'índiz",
	'proofreadpage_specialpage_searcherror' => 'Error nel motor de gueta',
	'proofreadpage_specialpage_searcherrortext' => 'El motor de gueta nun funciona. Disculpe les molesties.',
	'proofreadpage_source' => 'Orixe',
	'proofreadpage_source_message' => 'Edición escaneada usada pa establecer esti testu',
	'right-pagequality' => 'Cambiar la marca de calidá de la páxina',
	'proofreadpage-section-tools' => 'Ferramientes de revisión',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Otros',
	'proofreadpage-button-toggle-visibility-label' => "Amosar/tapecer l'encabezáu ya'l pie d'esta páxina",
	'proofreadpage-button-zoom-out-label' => 'Amenorgar',
	'proofreadpage-button-reset-zoom-label' => 'Tamañu orixinal',
	'proofreadpage-button-zoom-in-label' => 'Ampliar',
	'proofreadpage-button-toggle-layout-label' => 'Disposición vertical/horizontal',
	'proofreadpage-preferences-showheaders-label' => "Amosar los campos d'encabezáu y pie de páxina al editar nel espaciu de nomes {{ns:page}}",
	'proofreadpage-preferences-horizontal-layout-label' => 'Usar la disposición horizontal al editar nel espaciu de nomes {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Metadatos de los llibros de {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadatos de los llibros xestionaos por ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Esquema no encontráu',
	'proofreadpage-indexoai-error-schemanotfound-text' => "Nun s'alcontró l'esquema $1.",
	'proofreadpage-disambiguationspage' => 'Template:dixebra',
);

/** Kotava (Kotava)
 * @author Sab
 */
$messages['avk'] = array(
	'proofreadpage_image' => 'ewava',
	'proofreadpage_nextpage' => 'Radimebu',
	'proofreadpage_prevpage' => 'Abduebu',
	'proofreadpage_header' => 'Kroj (noinclude) :',
);

/** Azerbaijani (azərbaycanca)
 * @author Cekli829
 */
$messages['az'] = array(
	'proofreadpage_image' => 'Şəkil',
	'proofreadpage_index' => 'İndeks',
	'proofreadpage_nextpage' => 'Növbəti səhifə',
	'proofreadpage_source' => 'Mənbə',
	'proofreadpage-button-reset-zoom-label' => 'Orijinal ölçü',
);

/** South Azerbaijani (تورکجه)
 * @author Amir a57
 * @author E THP
 * @author Ebrahimi-amir
 * @author Mousa
 */
$messages['azb'] = array(
	'indexpages' => 'ایندکس صحیفه‌لری‌نین لیستی',
	'pageswithoutscans' => 'اسکن المایان صحیفه‌لر',
	'proofreadpage_desc' => 'اورژینال تارامايلا متنین آسانلیقلا موقايیسه ایجازه وئریر',
	'proofreadpage_image' => 'شکیل',
	'proofreadpage_index' => 'ایندکس',
	'proofreadpage_index_expected' => 'خطا: بئله بیر شخاخیص تاپیلمادی',
	'proofreadpage_nosuch_index' => 'خطا: بئله بیر شاخیص تاپیلمادی',
	'proofreadpage_nosuch_file' => 'خطا: بئله بیر فایل تاپیلمادی',
	'proofreadpage_badpage' => 'یانلیش فرمت',
	'proofreadpage_badpagetext' => 'قئيد ائتمه‌يه چالیشدیغینیز صحیفه‌‌نین فورمتی یانلیش دیر.',
	'proofreadpage_indexdupe' => 'دابلیکات باغلانتی',
	'proofreadpage_indexdupetext' => 'بیر سیلسیله/سئریالین صحیفه‌‌سینده، صحیفه‌‌لر بیردن چوخ سادالانا بیلمز.',
	'proofreadpage_nologin' => 'گیریش ائدیلمه‌میش',
	'proofreadpage_nologintext' => 'صحیفه‌لرین دوزلتمه وضعیتینی دییش‌دیرمک اوچون [[Special:UserLogin|گیریش ائده سیز]] اولمالیسینیز.',
	'proofreadpage_notallowed' => 'دییشیک لیگی ایزین عوض لمه اجازه سی یوخ',
	'proofreadpage_notallowedtext' => 'بو صحیفه‌نین دوزلتمه وضعیتینی دییشدیرمیینیزه ایجازه وئریلمیر.',
	'proofreadpage_dataconfig_badformatted' => 'بیلگی ياپیلاندیرماسیندا خطا',
	'proofreadpage_dataconfig_badformattedtext' => 'بو صحیفه‌‌ [[Mediawiki:Proofreadpage index data config]]-ده بیچیملئندیریلمیش جسون دئيیل.',
	'proofreadpage_number_expected' => 'خطا: عددی دیر گؤزلنیلیردی بئله انتظار یوخ',
	'proofreadpage_interval_too_large' => 'خطا:بازاسی چوخ بویوک',
	'proofreadpage_invalid_interval' => 'خطا: اعتبار سیز بازا',
	'proofreadpage_nextpage' => 'سونراکی صفحه',
	'proofreadpage_prevpage' => 'قاباغکی صحیفه',
	'proofreadpage_header' => 'موضوع (ایچئرمئ):',
	'proofreadpage_body' => 'صحیفه گؤوده‌سی (چارپاز علاوه اولونا‌جاق):',
	'proofreadpage_footer' => 'آلت بیلگی(noinclude):',
	'proofreadpage_toggleheaders' => 'ایچئریلمئيئن بؤلوملئری‌نین گؤرونورلوغونو دَییشتیر',
	'proofreadpage_quality0_category' => 'متن‌سیز',
	'proofreadpage_quality1_category' => 'دوزلدیلمه‌میش',
	'proofreadpage_quality2_category' => 'سورونلو',
	'proofreadpage_quality3_category' => 'یئنی‌دن باخیش',
	'proofreadpage_quality4_category' => 'تصدیقلن‌میش',
	'proofreadpage_quality0_message' => 'بو صحیفه‌ده دوزلیش ائدیلمه‌سی لازیم دئییل',
	'proofreadpage_quality1_message' => 'بو صحیفه‌ده دوزلیش ائدیلمه‌دی',
	'proofreadpage_quality2_message' => 'بو صحیفه‌ده دوزلیش ائدیلرکن بیر پروبلئم میدانا گلدی',
	'proofreadpage_quality3_message' => 'بو صحیفه‌‌ده دوزلتمه ائدیلدی',
	'proofreadpage_quality4_message' => 'بو صحیفه‌‌ تسدیقلنمیش',
	'proofreadpage_index_status' => 'ایندکس دورومو',
	'proofreadpage_index_size' => 'صحیفه‌لرین سایی',
	'proofreadpage_specialpage_label_orderby' => 'سیفاریش (قايدا):',
	'proofreadpage_specialpage_label_key' => 'آختار:',
	'proofreadpage_specialpage_label_sortascending' => 'چوخالان سیرالاماق',
	'proofreadpage_alphabeticalorder' => 'الیفبا سیراسی',
	'proofreadpage_index_listofpages' => 'صحیفه‌لرین لیستی',
	'proofreadpage_image_message' => 'ایندکس صحیفه‌سینه باغلا',
	'proofreadpage_page_status' => 'صحیفه دورومو',
	'proofreadpage_js_attributes' => 'یارادان باشلیق ایل یایین ائوی',
	'proofreadpage_index_attributes' => 'یارادان
باشلیق
ایل|یایین ایلی
یایین ائوی
قایناق
شکیل|قابیق شکیلی
صحیفه‌لر||20
آچیقلامالار||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|صحیفه|صحیفه‌لر}}',
	'proofreadpage_specialpage_legend' => 'ایندکس صحیفه‌لرینده آختار',
	'proofreadpage_specialpage_searcherror' => 'آرما موهرریکی خاطاسی',
	'proofreadpage_specialpage_searcherrortext' => 'آختاریش موهرریکی ایشلمیر. وئردیگیمیز ناراهات‌لیق‌دان اؤتری اوزر دیلییریک.',
	'proofreadpage_source' => 'قایناق',
	'proofreadpage_source_message' => 'بو متنی میدانا گتیرمک اوچون ایستیفاده ائدیلن دارانمیش نوسخه سی',
	'right-pagequality' => 'صفحه نین کیفیت بایراغین دییشدیر',
	'proofreadpage-section-tools' => 'دَییشدیرمه آراجلاری',
	'proofreadpage-group-zoom' => 'زوم',
	'proofreadpage-group-other' => 'آیری',
	'proofreadpage-button-toggle-visibility-label' => 'بو فايلین اوست بیلگیلری و آلت معلوماتینی گؤستر / گیزلت',
	'proofreadpage-button-zoom-out-label' => 'زومون کیچیلد',
	'proofreadpage-button-reset-zoom-label' => 'اوریجینال اؤلچوسو',
	'proofreadpage-button-zoom-in-label' => 'زوم ائت',
	'proofreadpage-button-toggle-layout-label' => 'اوفوقی / شاقولی طرحی',
	'proofreadpage-preferences-showheaders-label' => '{{Ns:page}} آد آلانیندا دوزنلرکن اوست بیلگی و آلت بیلگی آلان‌لارینی گؤستر',
	'proofreadpage-preferences-horizontal-layout-label' => '{{Ns:page}} آد ساحه‌سینده قایدایا اوفوقی نیزام ناویقاسیا:',
	'proofreadpage-indexoai-repositoryName' => 'کیتاب مئتا دئیتا {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'کیتابلار مئتا دئیتا يازیم طرفیندن بیلمیشدیر.',
	'proofreadpage-indexoai-error-schemanotfound' => 'سئچمه تاپمادی',
	'proofreadpage-indexoai-error-schemanotfound-text' => '$1 شئماسی آشکار ائدیلمه‌میشدیر.',
);

/** Bashkir (башҡортса)
 * @author Assele
 * @author Comp1089
 * @author Ebe123
 * @author ҒатаУлла
 */
$messages['ba'] = array(
	'indexpages' => 'Индекс биттәренең исемлеге',
	'pageswithoutscans' => 'Сканһыҙ биттәр',
	'proofreadpage_desc' => 'Текстты төп нөхсәһенең сканы менән еңел сағыштырыу мөмкинлеге бирә',
	'proofreadpage_image' => 'Рәсем',
	'proofreadpage_index' => 'Индекс',
	'proofreadpage_index_expected' => 'Хата: индекс көтөлә',
	'proofreadpage_nosuch_index' => 'Хата: бындай индекс юҡ',
	'proofreadpage_nosuch_file' => 'Хата: бындай файл юҡ',
	'proofreadpage_badpage' => 'Формат дөрөҫ түгел',
	'proofreadpage_badpagetext' => 'Һеҙ һаҡларға теләгән биттең форматы дөрөҫ түгел.',
	'proofreadpage_indexdupe' => 'Ҡабатланған һылтанма',
	'proofreadpage_indexdupetext' => 'Биттәр индекс битендә бер тапҡыр ғына осрарға тейеш.',
	'proofreadpage_nologin' => 'Танылмағанһығыҙ',
	'proofreadpage_nologintext' => 'Биттәрҙең корректураһын уҡыу торошон үҙгәртеү өсөн, һеҙ [[Special:UserLogin|танылырға]] тейешһегеҙ.',
	'proofreadpage_notallowed' => 'Үҙгәртеү рөхсәт ителмәй',
	'proofreadpage_notallowedtext' => 'Һеҙгә биттәрҙең корректураһын уҡыу торошон үҙгәртеү рөхсәт ителмәй.',
	'proofreadpage_number_expected' => 'Хата: һан көтөлә',
	'proofreadpage_interval_too_large' => 'Хата: бигерәк ҙур арауыҡ',
	'proofreadpage_invalid_interval' => 'Хата: арауыҡ дөрөҫ түгел',
	'proofreadpage_nextpage' => 'Киләһе бит',
	'proofreadpage_prevpage' => 'Алдағы бит',
	'proofreadpage_header' => 'Исем (индерелмәй):',
	'proofreadpage_body' => 'Биттең эстәлеге (индерелә):',
	'proofreadpage_footer' => 'Аҫҡы колонтитул (индерелмәй):',
	'proofreadpage_toggleheaders' => 'индерелмәгән бүлектәрҙе күрһәтергә',
	'proofreadpage_quality0_category' => 'Текстһыҙ',
	'proofreadpage_quality1_category' => 'Корректураһы уҡылмаған',
	'proofreadpage_quality2_category' => 'Икеләндерә',
	'proofreadpage_quality3_category' => 'Корректураһы уҡылған',
	'proofreadpage_quality4_category' => 'Тикшерелгән',
	'proofreadpage_quality0_message' => 'Был бит корректураһын уҡыуҙы талап итмәй',
	'proofreadpage_quality1_message' => 'Был биттең корректураһы уҡылмаған',
	'proofreadpage_quality2_message' => 'Был биттең корректураһын уҡығанда ҡыйынлыҡтар тыуҙы',
	'proofreadpage_quality3_message' => 'Был биттең корректураһы уҡылған',
	'proofreadpage_quality4_message' => 'Был бит тикшерелгән',
	'proofreadpage_index_listofpages' => 'Биттәр исемлеге',
	'proofreadpage_image_message' => 'Индекс битенә һылтанма',
	'proofreadpage_page_status' => 'Биттең торошо',
	'proofreadpage_js_attributes' => 'Автор Исем Йыл Нәшриәт',
	'proofreadpage_index_attributes' => 'Автор
Исем
Йыл|Баҫтырыу йылы
Нәшриәт
Сығанаҡ
Рәсем|Тышлығының рәсеме
Биттәр||20
Иҫкәрмәләр||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|бит}}',
	'proofreadpage_specialpage_legend' => 'Индекс биттәрен эҙләү',
	'proofreadpage_source' => 'Сығанаҡ',
	'proofreadpage_source_message' => 'Был текстты булдырыу өсөн сканланған материалдар ҡулланылған',
	'right-pagequality' => 'Биттең сифаты билдәһен үҙгәртеү',
	'proofreadpage-section-tools' => 'Төҙәтеүсе ҡоралдары',
	'proofreadpage-group-zoom' => 'Ҙурайтыу',
	'proofreadpage-group-other' => 'Икенсе',
	'proofreadpage-button-toggle-visibility-label' => 'Биттең өҫкө һәм аҫҡы яғын күрһәтергә/йәшерергә',
	'proofreadpage-button-zoom-out-label' => 'Йырағайтырға',
	'proofreadpage-button-reset-zoom-label' => 'Сығанаҡ ҙурлыҡ',
	'proofreadpage-button-zoom-in-label' => 'Яҡынайтырға',
	'proofreadpage-button-toggle-layout-label' => 'Текә/арҡыры билдә',
);

/** Balinese (ᬩᬲᬩᬮᬶ)
 * @author NoiX180
 */
$messages['ban'] = array(
	'indexpages' => 'Daptar kaca indèks',
);

/** Southern Balochi (بلوچی مکرانی)
 * @author Mostafadaneshvar
 */
$messages['bcc'] = array(
	'proofreadpage_desc' => 'اجازه دن مقایسه متن گون اصلی اسکن',
	'proofreadpage_image' => 'عکس',
	'proofreadpage_index' => 'ایندکس',
	'proofreadpage_nextpage' => 'صفحه بعدی',
	'proofreadpage_prevpage' => 'پیشگین صفحه',
	'proofreadpage_header' => 'سرتاک(شامل نه):',
	'proofreadpage_body' => 'بدنه صفحه (به ):',
	'proofreadpage_footer' => 'جهل نوشت (شامل نه):',
	'proofreadpage_toggleheaders' => 'عوض کن ظاهربیگ بخشانی که هور نهنت',
	'proofreadpage_quality1_category' => 'آزمایش نه بیتت',
	'proofreadpage_quality2_category' => 'مشکل دار',
	'proofreadpage_quality3_category' => 'آماده آزمایش',
	'proofreadpage_quality4_category' => 'معتبر',
	'proofreadpage_index_listofpages' => 'لیست صفحات',
	'proofreadpage_image_message' => 'لینک په صفحه اول',
	'proofreadpage_page_status' => 'وضعیت صفحه',
	'proofreadpage_js_attributes' => 'نویسوک عنوان سال ناشر کنوک',
	'proofreadpage_index_attributes' => 'نویسوک
عنوان
سال|سال انتشار
نشار
منبع
عکس|عکس پوش
صفحات||20
نشانان||',
);

/** Bikol Central (Bikol Central)
 * @author Filipinayzd
 */
$messages['bcl'] = array(
	'proofreadpage_namespace' => 'Pahina',
	'proofreadpage_index_namespace' => 'Indeks',
);

/** Belarusian (беларуская)
 * @author Wizardist
 * @author Хомелка
 */
$messages['be'] = array(
	'indexpages' => 'Спіс індэксных старонак',
	'pageswithoutscans' => 'Старонкі без сканаў',
	'proofreadpage_desc' => 'Дазваляе ў зручным выглядзе параўноўваць тэкст і адсканаваны арыгінал',
	'proofreadpage_image' => 'Выява',
	'proofreadpage_index' => 'Індэкс',
	'proofreadpage_index_expected' => 'Памылка: чакаецца індэкс',
	'proofreadpage_nosuch_index' => 'Памылка: няма такога індэксу',
	'proofreadpage_nosuch_file' => 'Памылка: няма такога файла',
	'proofreadpage_badpage' => 'Няслушны фармат',
	'proofreadpage_badpagetext' => 'Няслушны фармат старонкі, якую Вы спрабуеце захаваць.',
	'proofreadpage_indexdupe' => 'Спасылка-дублікат',
	'proofreadpage_indexdupetext' => 'Старонкі не могуць быць у спісе на індэкснай старонцы болей аднаго разу.',
	'proofreadpage_nologin' => 'Вы не ўвайшлі ў сістэму',
	'proofreadpage_nologintext' => 'Вы павінны [[Special:UserLogin|ўвайсці ў сістэму]], каб змяняць статус праверкі старонкі.',
	'proofreadpage_notallowed' => 'Змена не дазволеная',
	'proofreadpage_notallowedtext' => 'Вам не дазволена змяняць статус праверкі гэтай старонкі.',
	'proofreadpage_number_expected' => 'Памылка: чакаецца лічбавае значэнне',
	'proofreadpage_interval_too_large' => 'Памылка: занадта вялікі інтэрвал',
	'proofreadpage_invalid_interval' => 'Памылка: няслушны інтэрвал',
	'proofreadpage_nextpage' => 'Наступная старонка',
	'proofreadpage_prevpage' => 'Папярэдняя старонка',
	'proofreadpage_header' => 'Загаловак (не ўключаецца):',
	'proofreadpage_body' => 'Змест старонкі (уключаецца):',
	'proofreadpage_footer' => 'Ніжні калантытул (не ўключаецца):',
	'proofreadpage_toggleheaders' => 'змяніць бачнасць не ўключаных секцый',
	'proofreadpage_quality0_category' => 'Без тэксту',
	'proofreadpage_quality1_category' => 'Не правераная',
	'proofreadpage_quality2_category' => 'Праблематычная',
	'proofreadpage_quality3_category' => 'Вычытаная',
	'proofreadpage_quality4_category' => 'Правераная',
	'proofreadpage_quality0_message' => 'Гэта старонка не патрабуе вычыткі',
	'proofreadpage_quality1_message' => 'Гэта старонка не была вычытаная',
	'proofreadpage_quality2_message' => 'Узнікла праблема ў вычытцы гэтай старонкі',
	'proofreadpage_quality3_message' => 'Гэта старонка была вычытаная',
	'proofreadpage_quality4_message' => 'Гэта старонка была правераная',
	'proofreadpage_index_listofpages' => 'Спіс старонак',
	'proofreadpage_image_message' => 'Спасылка на старонку індэксу',
	'proofreadpage_page_status' => 'Статус старонкі',
	'proofreadpage_js_attributes' => 'Аўтар Назва Год Выдавецтва',
	'proofreadpage_index_attributes' => 'Аўтар
Назва
Год|Год выдання
Выдавецтва
Крыніца
Выява|Выява вокладкі
Старонкі||20
Заўвагі||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|старонка|старонкі|старонак}}',
	'proofreadpage_specialpage_legend' => 'Пошук індэксных старонак',
	'proofreadpage_source' => 'Крыніца',
	'proofreadpage_source_message' => 'Сканаваная версія, якая выкарыстоўвалася для стварэння гэтага тэксту',
	'right-pagequality' => 'змяненне сцяжка якасці старонкі',
	'proofreadpage-section-tools' => 'Інструменты рэдактара',
	'proofreadpage-group-zoom' => 'Маштаб',
	'proofreadpage-group-other' => 'Іншае',
	'proofreadpage-button-toggle-visibility-label' => 'Паказаць/схаваць калантытулы гэтай старонкі',
	'proofreadpage-button-zoom-out-label' => 'Паменшыць',
	'proofreadpage-button-reset-zoom-label' => 'Арыгінальны памер',
	'proofreadpage-button-zoom-in-label' => 'Павялічыць',
	'proofreadpage-button-toggle-layout-label' => 'Вертыкальная/гарызантальная разметка',
);

/** Belarusian (Taraškievica orthography) (беларуская (тарашкевіца)‎)
 * @author EugeneZelenko
 * @author Jim-by
 * @author Red Winged Duck
 * @author Renessaince
 * @author Wizardist
 */
$messages['be-tarask'] = array(
	'indexpages' => 'Сьпіс індэксных старонак',
	'pageswithoutscans' => 'Старонкі бяз сканаў',
	'proofreadpage_desc' => 'Дазваляе ў зручным выглядзе параўноўваць тэкст і адсканаваны арыгінал',
	'proofreadpage_image' => 'выява',
	'proofreadpage_index' => 'Індэкс',
	'proofreadpage_index_expected' => 'Памылка: чакаецца індэкс',
	'proofreadpage_nosuch_index' => 'Памылка: няма такога індэксу',
	'proofreadpage_nosuch_file' => 'Памылка: няма такога файла',
	'proofreadpage_badpage' => 'Няслушны фармат',
	'proofreadpage_badpagetext' => 'Няслушны фармат старонкі, якую Вы спрабуеце захаваць.',
	'proofreadpage_indexdupe' => 'Спасылка-дублікат',
	'proofreadpage_indexdupetext' => 'Старонкі ня могуць трапляць на індэксную старонку болей аднаго разу.',
	'proofreadpage_nologin' => 'Вы не ўвайшлі ў сыстэму',
	'proofreadpage_nologintext' => 'Вы павінны [[Special:UserLogin|ўвайсьці ў сыстэму]], каб зьмяняць статус праверкі старонкі.',
	'proofreadpage_notallowed' => 'Зьмена не дазволеная',
	'proofreadpage_notallowedtext' => 'Вам не дазволена зьмяняць статус праверкі гэтай старонкі.',
	'proofreadpage_dataconfig_badformatted' => 'Хіба ў канфігурацыі зьвестак',
	'proofreadpage_dataconfig_badformattedtext' => 'Старонка [[Mediawiki:Proofreadpage index data config]] зьмяшчае блага фарматаваны JSON.',
	'proofreadpage_number_expected' => 'Памылка: чакаецца лічбавае значэньне',
	'proofreadpage_interval_too_large' => 'Памылка: занадта вялікі інтэрвал',
	'proofreadpage_invalid_interval' => 'Памылка: няслушны інтэрвал',
	'proofreadpage_nextpage' => 'Наступная старонка',
	'proofreadpage_prevpage' => 'Папярэдняя старонка',
	'proofreadpage_header' => 'Верхні калянтытул (не ўключаецца):',
	'proofreadpage_body' => 'Зьмест старонкі (уключаецца):',
	'proofreadpage_footer' => 'Ніжні калянтытул (не ўключаецца):',
	'proofreadpage_toggleheaders' => 'зьмяніць бачнасьць ня ўключаных сэкцыяў',
	'proofreadpage_quality0_category' => 'Бяз тэксту',
	'proofreadpage_quality1_category' => 'Не правераная',
	'proofreadpage_quality2_category' => 'Праблематычная',
	'proofreadpage_quality3_category' => 'Вычытаная',
	'proofreadpage_quality4_category' => 'Правераная',
	'proofreadpage_quality0_message' => 'Гэта старонка не патрабуе вычыткі',
	'proofreadpage_quality1_message' => 'Гэта старонка не была вычытаная',
	'proofreadpage_quality2_message' => 'Узьнікла праблема ў вычытцы гэтай старонкі',
	'proofreadpage_quality3_message' => 'Гэта старонка была вычытаная',
	'proofreadpage_quality4_message' => 'Гэта старонка была правераная',
	'proofreadpage_index_status' => 'стану індэкса',
	'proofreadpage_index_size' => 'колькасьці старонак',
	'proofreadpage_specialpage_label_orderby' => 'Адсартаваць паводле:',
	'proofreadpage_specialpage_label_key' => 'Шукаць:',
	'proofreadpage_specialpage_label_sortascending' => 'Наўпростая сартыроўка',
	'proofreadpage_alphabeticalorder' => 'альфабэту',
	'proofreadpage_index_listofpages' => 'Сьпіс старонак',
	'proofreadpage_image_message' => 'Спасылка на старонку індэксу',
	'proofreadpage_page_status' => 'Статус старонкі',
	'proofreadpage_js_attributes' => 'Аўтар Назва Год Выдавецтва',
	'proofreadpage_index_attributes' => 'Аўтар
Назва
Год|Год выданьня
Выдавецтва
Крыніца
Выява|Выява вокладкі
Старонак||20
Заўвагаў||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|старонка|старонкі|старонак}}',
	'proofreadpage_specialpage_legend' => 'Пошук індэксных старонак',
	'proofreadpage_specialpage_searcherror' => 'Памылка ў пошукавай сыстэме',
	'proofreadpage_specialpage_searcherrortext' => 'Пошукавая сыстэма не працуе. Выбачайце за клопаты.',
	'proofreadpage_source' => 'Крыніца',
	'proofreadpage_source_message' => 'Сканаваная вэрсія, якая выкарыстоўвалася для стварэньня гэтага тэксту',
	'right-pagequality' => 'зьмяненьне сьцяжка якасьці старонкі',
	'proofreadpage-section-tools' => 'Інструмэнты рэдактара',
	'proofreadpage-group-zoom' => 'Маштаб',
	'proofreadpage-group-other' => 'Іншае',
	'proofreadpage-button-toggle-visibility-label' => 'Паказаць/схаваць калянтытулы гэтай старонкі',
	'proofreadpage-button-zoom-out-label' => 'Паменшыць',
	'proofreadpage-button-reset-zoom-label' => 'Зыходны памер',
	'proofreadpage-button-zoom-in-label' => 'Павялічыць',
	'proofreadpage-button-toggle-layout-label' => 'Вэртыкальная/гарызантальная разьметка',
	'proofreadpage-preferences-showheaders-label' => 'Паказваць палі для верхняга і ніжняга калянтытулаў пры рэдагаваньні ў прасторы назваў {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Выкарыстоўваць гарызантальную разьметку для рэдагаваньня ў прасторы назваў {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Мэтазьвесткі кнігі з {{GRAMMAR:родны|{{SITENAME}}}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Мэтазьвесткі для кніг паводле ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Схема ня знойдзеная',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Схема «$1» ня знойдзеная.',
);

/** Bulgarian (български)
 * @author Borislav
 * @author DCLXVI
 * @author Spiritia
 * @author Stanqo
 * @author Turin
 */
$messages['bg'] = array(
	'indexpages' => 'Списък на индексните страници',
	'pageswithoutscans' => 'Страници без сканирани изображения',
	'proofreadpage_desc' => 'Позволява лесно сравнение на текст с оригинален сканиран документ',
	'proofreadpage_image' => 'Изображение',
	'proofreadpage_index' => 'Индекс',
	'proofreadpage_index_expected' => 'Грешка: очаква се индекс',
	'proofreadpage_nosuch_index' => 'Грешка: няма такъв индекс',
	'proofreadpage_nosuch_file' => 'Грешка: няма такъв файл',
	'proofreadpage_badpage' => 'Неправилен формат',
	'proofreadpage_badpagetext' => 'Форматът на страницата, която опитвате да запазите, е неправилен.',
	'proofreadpage_indexdupe' => 'Повтаряща се препратка',
	'proofreadpage_indexdupetext' => 'Страниците не могат да се изписват повече от веднъж на индексната страница.',
	'proofreadpage_nologin' => 'Не сте влезли',
	'proofreadpage_nologintext' => 'Трябва да [[Special:UserLogin|влезете]], за да може да променяте състоянието на корекция на страниците.',
	'proofreadpage_notallowed' => 'Промяната не е позволена',
	'proofreadpage_notallowedtext' => 'Не ви е позволено да променяте състоянието на корекция на страницата.',
	'proofreadpage_dataconfig_badformatted' => 'Грешка в конфигурацията на данни',
	'proofreadpage_dataconfig_badformattedtext' => 'Страницата [[Mediawiki:Proofreadpage index data config]] не съдържа правилно структуриран JSON.',
	'proofreadpage_number_expected' => 'Грешка: очаква се цифрова стойност',
	'proofreadpage_interval_too_large' => 'Грешка: обхватът е твърде голям',
	'proofreadpage_invalid_interval' => 'Грешка: недопустим интервал',
	'proofreadpage_nextpage' => 'Следваща страница',
	'proofreadpage_prevpage' => 'Предишна страница',
	'proofreadpage_header' => 'Горен колонтитул (не се включва):',
	'proofreadpage_body' => 'Тяло на страницата (за вграждане):',
	'proofreadpage_footer' => 'Долен колонтитул (не се включва):',
	'proofreadpage_toggleheaders' => 'превключване на видимостта на разделите с „noinclude“',
	'proofreadpage_quality0_category' => 'Без текст',
	'proofreadpage_quality1_category' => 'Некоригирана',
	'proofreadpage_quality2_category' => 'Проблематична',
	'proofreadpage_quality3_category' => 'Коригирана',
	'proofreadpage_quality4_category' => 'Одобрена',
	'proofreadpage_quality0_message' => 'Страницата няма нужда от корекция',
	'proofreadpage_quality1_message' => 'Страницата не е коригирана',
	'proofreadpage_quality2_message' => 'Имало е проблем при корекцията на страницата',
	'proofreadpage_quality3_message' => 'Страницата е коригирана',
	'proofreadpage_quality4_message' => 'Корекцията на страницата е одобрена',
	'proofreadpage_index_status' => 'Индекс статус',
	'proofreadpage_index_size' => 'Брой страници',
	'proofreadpage_specialpage_label_orderby' => 'Подредба по:',
	'proofreadpage_specialpage_label_key' => 'Търсене:',
	'proofreadpage_specialpage_label_sortascending' => 'Възходящо сортиране',
	'proofreadpage_alphabeticalorder' => 'Азбучен ред',
	'proofreadpage_index_listofpages' => 'Списък на страниците',
	'proofreadpage_image_message' => 'Към индексната страница',
	'proofreadpage_page_status' => 'Състояние на страницата',
	'proofreadpage_js_attributes' => 'Автор Заглавие Година Издател',
	'proofreadpage_index_attributes' => 'Автор
Заглавие
Година|Година на публикация
Издател
Източник
Корица|Страница с корица
Страници||20
Забележки||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|страница|страници}}',
	'proofreadpage_specialpage_legend' => 'Търсене в индексните страници',
	'proofreadpage_specialpage_searcherror' => 'Грешка в търсачката',
	'proofreadpage_specialpage_searcherrortext' => 'Търсачката не работи. Извинения за неудобството.',
	'proofreadpage_source' => 'Източник',
	'proofreadpage_source_message' => 'Текстът е от сканирано издание',
	'right-pagequality' => 'Промяна на флага за качество на страницата',
	'proofreadpage-section-tools' => 'Инструменти за корекцията',
	'proofreadpage-group-zoom' => 'Мащабиране',
	'proofreadpage-group-other' => 'Други',
	'proofreadpage-button-toggle-visibility-label' => 'Показване/скриване на тази страница на горен и долен колонтитул',
	'proofreadpage-button-zoom-out-label' => 'Отдалечаване',
	'proofreadpage-button-reset-zoom-label' => 'Оригинален размер',
	'proofreadpage-button-zoom-in-label' => 'Приближаване',
	'proofreadpage-button-toggle-layout-label' => 'Вертикално/хоризонтално оформление',
	'proofreadpage-preferences-showheaders-label' => 'Покажи горен и долен колонтитул при редактиране в именно пространство {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Ползване на хоризонтално оформление при редактиране в именното пространство {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Метаданни на книги от {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Метаданни на книги, управлявани от ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Схемата не беше открита',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Схемата $1 не беше открита.',
);

/** Bengali (বাংলা)
 * @author Bellayet
 */
$messages['bn'] = array(
	'indexpages' => 'নির্ঘণ্ট পাতার তালিকা',
	'pageswithoutscans' => 'স্ক্যান ছাড়া পাতাসমূহ',
	'proofreadpage_desc' => 'মূল স্ক্যানের সাথে লেখার সহজ তুলনা অনুমোদন করো',
	'proofreadpage_image' => 'চিত্র',
	'proofreadpage_index' => 'নির্ঘন্ট',
	'proofreadpage_nosuch_index' => 'ত্রুটি: এমন নির্ঘণ্ট নাই',
	'proofreadpage_nosuch_file' => 'ত্রুটি: এমন ফাইল নাই',
	'proofreadpage_badpage' => 'ভুল ফরমেট',
	'proofreadpage_indexdupe' => 'সদৃশ লিঙ্ক',
	'proofreadpage_nologin' => 'লগইন করা হয়নি',
	'proofreadpage_notallowed' => 'পরিবর্তনের অনুমতি নাই',
	'proofreadpage_notallowedtext' => 'আপনার এই পাতার প্রুফরিডিং অবস্থা পরিবর্তনের অনুমতি নাই।',
	'proofreadpage_nextpage' => 'পরবর্তী পাতা',
	'proofreadpage_prevpage' => 'পূর্ববর্তী পাতা',
	'proofreadpage_header' => 'শিরোনাম (noinclude):',
	'proofreadpage_body' => 'পাতার প্রধান অংশ (to be transcluded):',
	'proofreadpage_footer' => 'পাদটীকা (noinclude):',
	'proofreadpage_quality0_category' => 'লেখাবিহীন',
	'proofreadpage_quality1_category' => 'মুদ্রণ সংশোধন করা হয়নি',
	'proofreadpage_quality2_category' => 'সমস্যাসঙ্কুল',
	'proofreadpage_quality3_category' => 'প্রুফরিড',
	'proofreadpage_quality4_category' => 'বৈধকরণ',
	'proofreadpage_quality0_message' => 'এই পাতার প্রুফরিডের প্রয়োজন নাই',
	'proofreadpage_quality1_message' => 'এই পাতার প্রুফরিড হয়নি',
	'proofreadpage_quality2_message' => 'এই পাতার প্রুফরিডের সময় কোন সমস্যা ছিল',
	'proofreadpage_quality3_message' => 'এই পাতার প্রুফরিড সম্পন্ন হয়েছে',
	'proofreadpage_quality4_message' => 'এই পাতা বৈধ হয়েছে',
	'proofreadpage_specialpage_label_key' => 'অনুসন্ধান:',
	'proofreadpage_alphabeticalorder' => 'বর্ণানুক্রম',
	'proofreadpage_index_listofpages' => 'পাতাসমূহের তালিকা',
	'proofreadpage_image_message' => 'নির্ঘণ্ট পাতায় লিঙ্ক করো',
	'proofreadpage_page_status' => 'পাতার অবস্থা',
	'proofreadpage_js_attributes' => 'লেখক শিরোনাম বছর প্রকাশক',
	'proofreadpage_index_attributes' => 'ধরন
শিরোনাম
খণ্ড
লেখক
অনুবাদক
সম্পাদক
অলঙ্করণ
স্কুল
প্রকাশক
ঠিকানা|স্থান
বছর|প্রকাশের বছর
Key|Sort key
উৎস|স্ক্যান
চিত্র|প্রচ্ছদ
অগ্রগতি
পাতাসমূহ||15
খণ্ডসমূহ||5
মন্তব্য|সূচী|15
প্রস্থ|সম্পাদনা মোডে স্ক্যান রেজুলুশন
সিএসএস(Css)
হেডার
পাদটীকা',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|পাতাট|পাতাগুলো}}',
	'proofreadpage_specialpage_legend' => 'নির্ঘণ্ট পাতাসমূহ অনুসন্ধান',
	'proofreadpage_source' => 'উৎস',
	'proofreadpage-section-tools' => 'প্রুফরিড টুলসমূহ',
	'proofreadpage-group-zoom' => 'বড় করো',
	'proofreadpage-group-other' => 'অন্য',
	'proofreadpage-button-toggle-visibility-label' => 'এই পাতার শিরোনাম এবং পাদটীকা দেখাও/লুখাও',
	'proofreadpage-button-zoom-out-label' => 'আরও ছোট',
	'proofreadpage-button-reset-zoom-label' => 'মূল আকার',
	'proofreadpage-button-zoom-in-label' => 'আরও বড়',
	'proofreadpage-button-toggle-layout-label' => 'উল্লম্ব/অনুভূমিক বিন্যাস',
	'proofreadpage-preferences-showheaders-label' => 'পাতা {{ns:page}} সম্পাদনার সময় শিরোনাম এবং পাদটীকা ফিল্ড দেখাও',
);

/** Breton (brezhoneg)
 * @author Fohanno
 * @author Fulup
 * @author Gwendal
 * @author VIGNERON
 * @author Y-M D
 */
$messages['br'] = array(
	'indexpages' => 'Roll ar pajennoù meneger',
	'pageswithoutscans' => "Pajennoù ha n'int ket skannet",
	'proofreadpage_desc' => "Aotreañ a ra ur c'heñveriadur aes etre an destenn hag he nivereladur orin",
	'proofreadpage_image' => 'Skeudenn',
	'proofreadpage_index' => 'Meneger',
	'proofreadpage_index_expected' => 'Fazi : ur meneger a oa gortozet',
	'proofreadpage_nosuch_index' => "Fazi : n'eus ket eus ar meneger-se",
	'proofreadpage_nosuch_file' => "Fazi : n'eus restr ebet evel-se",
	'proofreadpage_badpage' => 'Furmad fall',
	'proofreadpage_badpagetext' => "N'eo ket reizh furmad ar bajenn ho peus klasket embann.",
	'proofreadpage_indexdupe' => 'Liamm e doubl',
	'proofreadpage_indexdupetext' => "Ne c'hell ket ar pajennoù bezañ listennet muioc'h evit ur wech war ur bajenn meneger.",
	'proofreadpage_nologin' => 'Digevreet',
	'proofreadpage_nologintext' => 'Rankout a rit bezañ [[Special:UserLogin|kevreet]] evit kemmañ statud reizhañ ar pajennoù.',
	'proofreadpage_notallowed' => "N'eo ket aotreet ar c'hemm-mañ",
	'proofreadpage_notallowedtext' => "Noc'h ket aotreet da gemmañ ar statud reizhañ ar bajenn-mañ.",
	'proofreadpage_number_expected' => 'Fazi : gortozet e vez un dalvoud niverel',
	'proofreadpage_interval_too_large' => 'Fazi : re vras eo an esaouenn',
	'proofreadpage_invalid_interval' => "Fazi : n'eo ket mat an esaouenn",
	'proofreadpage_nextpage' => "Pajenn war-lerc'h",
	'proofreadpage_prevpage' => 'Pajenn a-raok',
	'proofreadpage_header' => "Talbenn (n'emañ ket e-barzh) :",
	'proofreadpage_body' => 'Danvez (dre dreuzklozadur) :',
	'proofreadpage_footer' => "Traoñ pajenn (n'emañ ket e-barzh) :",
	'proofreadpage_toggleheaders' => 'kuzhat/diskouez ar rannoù noinclude',
	'proofreadpage_quality0_category' => 'Hep testenn',
	'proofreadpage_quality1_category' => 'Da wiriañ',
	'proofreadpage_quality2_category' => 'Kudennek',
	'proofreadpage_quality3_category' => 'Reizhet',
	'proofreadpage_quality4_category' => 'Kadarnaet',
	'proofreadpage_quality0_message' => "Ar bajenn-mañ n'he deus ket ezhomm da vezañ adlennet",
	'proofreadpage_quality1_message' => "Ar bajenn-mañ n'eo ket bet adlennet",
	'proofreadpage_quality2_message' => 'Ur gudenn zo bet e-ser reizhañ ar bajenn',
	'proofreadpage_quality3_message' => 'Adlennet eo bet ar bajenn-mañ',
	'proofreadpage_quality4_message' => 'Gwiriekaet eo bet ar bajenn-mañ',
	'proofreadpage_index_status' => 'Stad ar meneger',
	'proofreadpage_index_size' => 'Niver a bajennoù',
	'proofreadpage_specialpage_label_orderby' => 'Renkañ dre :',
	'proofreadpage_specialpage_label_key' => 'Klask :',
	'proofreadpage_specialpage_label_sortascending' => 'Urzhiañ war-laez',
	'proofreadpage_alphabeticalorder' => 'Dre urzh al lizherenneg',
	'proofreadpage_index_listofpages' => 'Roll ar pajennoù',
	'proofreadpage_image_message' => 'Liamm war-du ar meneger',
	'proofreadpage_page_status' => 'Statud ar bajenn',
	'proofreadpage_js_attributes' => 'Aozer Titl Bloaz Embanner',
	'proofreadpage_index_attributes' => 'Type|Doare
Title|Titl
Author|Oberour
Translator|Troer
Editor|Aozer
School|Skol
Year|Bloavezh embann
Publisher|Embanner
Address|Chomlec’h
Key|Alc’hwez diforc’hañ
Source|Mammenn
Image|Skeudenn
Progress|Araokaat
Volumes|Levrennoù|5
Pages|Pajennoù|20
Remarks|Notennoù|10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pajenn}}',
	'proofreadpage_specialpage_legend' => 'Klask e pajennoù ar merdeer',
	'proofreadpage_specialpage_searcherror' => 'Fazi el lusker enklask',
	'proofreadpage_source' => 'Mammenn',
	'proofreadpage_source_message' => 'Embannadurioù bet niverelaet implijet evit sevel an destenn-mañ',
	'right-pagequality' => 'Kemm banniel perzhded ar bajennoù',
	'proofreadpage-section-tools' => 'Ostilhoù adlenn',
	'proofreadpage-group-zoom' => 'Zoum',
	'proofreadpage-group-other' => 'All',
	'proofreadpage-button-toggle-visibility-label' => 'Diskouez/kuzhat an talbenn ha traoñ ar bajenn',
	'proofreadpage-button-zoom-out-label' => 'Dizoumañ',
	'proofreadpage-button-reset-zoom-label' => 'Ment orin',
	'proofreadpage-button-zoom-in-label' => 'Zoumañ',
	'proofreadpage-button-toggle-layout-label' => 'Kinnig a-sav/a-led',
	'proofreadpage-preferences-showheaders-label' => 'Diskouez maeziennoù talbenn ha traoñ pajenn pa aozer pajennoù e mod Pajenn', # Fuzzy
	'proofreadpage-indexoai-error-schemanotfound' => "N'eo ket bet kavet ar brastres",
	'proofreadpage-indexoai-error-schemanotfound-text' => "N'eo ket bet kavet ar brastres $1.",
);

/** Bosnian (bosanski)
 * @author CERminator
 */
$messages['bs'] = array(
	'indexpages' => 'Spisak stranica indeksa',
	'pageswithoutscans' => 'Stranice bez skeniranja',
	'proofreadpage_desc' => 'Omogućuje jednostavnu usporedbu teksta sa originalnim',
	'proofreadpage_image' => 'Slika',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => 'Greška: očekivan indeks',
	'proofreadpage_nosuch_index' => 'Greška: nema takvog indeksa',
	'proofreadpage_nosuch_file' => 'Greška: nema takve datoteke',
	'proofreadpage_badpage' => 'Pogrešan Format',
	'proofreadpage_badpagetext' => 'Format stranice koju pokušavate spremiti nije validan.',
	'proofreadpage_indexdupe' => 'Duplicirani link',
	'proofreadpage_indexdupetext' => 'Stranice ne mogu biti prikazane više od jednog puta na stranici indeksa.',
	'proofreadpage_nologin' => 'Niste prijavljeni',
	'proofreadpage_nologintext' => 'Morate biti [[Special:UserLogin|prijavljeni]] da biste mogli mijenati status lektorisanja stranica.',
	'proofreadpage_notallowed' => 'Izmjene nisu dopuštene',
	'proofreadpage_notallowedtext' => 'Nije Vam dopušteno da mijenjate status lektorisanja ove stranice.',
	'proofreadpage_number_expected' => 'Greška: očekivana brojna vrijednost',
	'proofreadpage_interval_too_large' => 'Greška: interval je prevelik',
	'proofreadpage_invalid_interval' => 'Greška: nevaljan interval',
	'proofreadpage_nextpage' => 'Slijedeća stranica',
	'proofreadpage_prevpage' => 'Prethodna stranica',
	'proofreadpage_header' => 'Zaglavlje (bez uključivanja):',
	'proofreadpage_body' => 'Tijelo stranice (koje će biti uključeno):',
	'proofreadpage_footer' => 'Podnožje (neuključuje):',
	'proofreadpage_toggleheaders' => 'pokaži/sakrij vidljivost sekcija koje se ne uključuju',
	'proofreadpage_quality0_category' => 'Bez teksta',
	'proofreadpage_quality1_category' => 'Nije provjerena',
	'proofreadpage_quality2_category' => 'Problematično',
	'proofreadpage_quality3_category' => 'Provjereno',
	'proofreadpage_quality4_category' => 'Provjereno',
	'proofreadpage_quality0_message' => 'Ova stranica ne treba biti lektorisana',
	'proofreadpage_quality1_message' => 'Ova stranica nije bila lektorisana',
	'proofreadpage_quality2_message' => 'Dogodio se problem pri lektorisanju ove stranice',
	'proofreadpage_quality3_message' => 'Ova stranice je bila lektorisana',
	'proofreadpage_quality4_message' => 'Ova stranice je bila provjerena',
	'proofreadpage_index_listofpages' => 'Spisak stranica',
	'proofreadpage_image_message' => 'Link na stranicu indeksa',
	'proofreadpage_page_status' => 'Status stranice',
	'proofreadpage_js_attributes' => 'Autor Naslov Godina Izdavač',
	'proofreadpage_index_attributes' => 'Autor
Naslov
Godina|Godina izdavanja
Izdavač
Izvor
Slika|Naslovna slika
Stranica||20
Napomene||10',
	'proofreadpage_pages' => '{{PLURAL:$1|stranica|stranice|stranica}}', # Fuzzy
	'proofreadpage_specialpage_legend' => 'Ptretraga indeksnih stranica',
	'proofreadpage_source' => 'Izvor',
	'proofreadpage_source_message' => 'Skenirana varijanta korištena za nastanak ovog teksta',
	'right-pagequality' => 'Izmijeni zastavu kvalitete stranice',
);

/** Catalan (català)
 * @author Aleator
 * @author Jordi Roqué
 * @author Paucabot
 * @author Qllach
 * @author SMP
 * @author Solde
 */
$messages['ca'] = array(
	'indexpages' => "Llista de pàgines d'índex",
	'pageswithoutscans' => 'Pàgines sense escanejos',
	'proofreadpage_desc' => "Permetre una fàcil comparació d'un text amb l'escanejat original",
	'proofreadpage_image' => 'Imatge',
	'proofreadpage_index' => 'Índex',
	'proofreadpage_index_expected' => "Error: s'esperava un índex",
	'proofreadpage_nosuch_index' => "Error: no existeix l'índex",
	'proofreadpage_nosuch_file' => 'Error: no existeix el fitxer',
	'proofreadpage_badpage' => 'Format erroni',
	'proofreadpage_badpagetext' => 'El format de la pàgina que heu intentat desar és incorrecte.',
	'proofreadpage_indexdupe' => 'Enllaç duplicat',
	'proofreadpage_indexdupetext' => "Les pàgines no es poden llistar més d'una vegada a una pàgina d'índex.",
	'proofreadpage_nologin' => 'No heu iniciat la sessió',
	'proofreadpage_nologintext' => "Heu d'estar [[Special:UserLogin|registrat]] per a modificar l'estat de revisió de les pàgines.",
	'proofreadpage_notallowed' => 'Canvi no permès',
	'proofreadpage_notallowedtext' => "No esteu autoritzat per a canviar l'estat de revisió d'aquesta pàgina.",
	'proofreadpage_number_expected' => "Error: s'esperava un valor numèric",
	'proofreadpage_interval_too_large' => 'Error: interval massa ampli',
	'proofreadpage_invalid_interval' => 'Error: interval no vàlid',
	'proofreadpage_nextpage' => 'Pàgina següent',
	'proofreadpage_prevpage' => 'Pàgina anterior',
	'proofreadpage_header' => 'Capçalera (noinclude):',
	'proofreadpage_body' => 'Cos de la pàgina (per a ser transclós):',
	'proofreadpage_footer' => 'Peu de pàgina (noinclude):',
	'proofreadpage_toggleheaders' => "Visualitzar seccions ''noinclude''",
	'proofreadpage_quality0_category' => 'Sense text',
	'proofreadpage_quality1_category' => 'Sense revisar',
	'proofreadpage_quality2_category' => 'Problemàtica',
	'proofreadpage_quality3_category' => 'Revisada',
	'proofreadpage_quality4_category' => 'Validada',
	'proofreadpage_quality0_message' => 'Aquesta pàgina no necessita ser revisada.',
	'proofreadpage_quality1_message' => "Aquesta pàgina no s'ha revisat",
	'proofreadpage_quality2_message' => "Hi ha un problema amb la revisió d'aquesta pàgina.",
	'proofreadpage_quality3_message' => 'Aquesta pàgina ha estat revisada.',
	'proofreadpage_quality4_message' => 'Aquesta pàgina ha estat validada',
	'proofreadpage_index_status' => 'Estat del llibre',
	'proofreadpage_index_size' => 'Nombre de pàgines',
	'proofreadpage_specialpage_label_orderby' => 'Ordenar per:',
	'proofreadpage_specialpage_label_key' => 'Cerca:',
	'proofreadpage_specialpage_label_sortascending' => 'Ordenació ascendent',
	'proofreadpage_alphabeticalorder' => 'Ordre alfabètic',
	'proofreadpage_index_listofpages' => 'Llista de pàgines',
	'proofreadpage_image_message' => "Enllaç a la pàgina d'índex",
	'proofreadpage_page_status' => 'Estat de la pàgina',
	'proofreadpage_js_attributes' => 'Autor Títol Any Editorial',
	'proofreadpage_index_attributes' => "Títol
Autor
Editor
Lloc|Lloc d'edició
Any|Any de publicació
Clau
Font|Facsímils
Imatge
Pàgines||20
Sumari||15",
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pàgina|pàgines}}',
	'proofreadpage_specialpage_legend' => "Cerca a les pàgines d'índex",
	'proofreadpage_specialpage_searcherror' => 'Error en el motor de cerca',
	'proofreadpage_specialpage_searcherrortext' => 'El motor de cerca no funciona. Disculpeu les molèsties.',
	'proofreadpage_source' => 'Font',
	'proofreadpage_source_message' => "Edició digitalitzada d'on s'ha extret aquest text",
	'right-pagequality' => "Modificar l'indicador de qualitat de la pàgina",
	'proofreadpage-section-tools' => 'Eines de correcció',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Altres',
	'proofreadpage-button-toggle-visibility-label' => "Mostra/oculta capçalera i peu de pàgina d'aquesta pàgina",
	'proofreadpage-button-zoom-out-label' => 'Allunya',
	'proofreadpage-button-reset-zoom-label' => 'Restablir zoom',
	'proofreadpage-button-zoom-in-label' => 'Amplia',
	'proofreadpage-button-toggle-layout-label' => 'Presentació vertical/horitzontal',
	'proofreadpage-preferences-showheaders-label' => "Mostra camps de capçalera i peu en editar en el nom d'espai {{ns:page}}",
	'proofreadpage-preferences-horizontal-layout-label' => "Utilitza la presentació horitzontal en editar en el nom d'espai {{ns:page}}",
);

/** Chechen (нохчийн)
 * @author Умар
 */
$messages['ce'] = array(
	'proofreadpage_header' => 'Корта (юкъаяло цатарло):',
	'proofreadpage_source' => 'Хьост',
	'proofreadpage-group-zoom' => 'Барам',
	'proofreadpage-group-other' => 'Кхин',
);

/** Cebuano (Cebuano)
 * @author Abastillas
 */
$messages['ceb'] = array(
	'proofreadpage_nextpage' => 'Sunod nga panid',
	'proofreadpage_prevpage' => 'Miaging panid',
);

/** Sorani Kurdish (کوردی)
 * @author Calak
 * @author Muhammed taha
 */
$messages['ckb'] = array(
	'proofreadpage_image' => 'وێنە',
	'proofreadpage_index' => 'پێڕست',
	'proofreadpage_nologin' => 'لەژوورەوە نیت',
	'proofreadpage_nextpage' => 'پەڕەی دواتر',
	'proofreadpage_prevpage' => 'پەڕەی پێشوو',
	'proofreadpage_index_status' => 'چۆنێتیی پێرست',
	'proofreadpage_index_size' => 'ژمارەی پەڕەکان',
	'proofreadpage_specialpage_label_key' => 'گەڕان',
	'proofreadpage_index_listofpages' => 'پێڕستی پەڕەکان',
	'proofreadpage_page_status' => 'دۆخی پەڕە',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|پەڕە|پەڕەکان}}',
	'proofreadpage_source' => 'سەرچاوە',
	'proofreadpage-group-other' => 'دیکە',
	'proofreadpage-button-zoom-out-label' => 'بچووککردنەوە',
	'proofreadpage-button-reset-zoom-label' => 'قەبارەی بنەڕەتی',
	'proofreadpage-button-zoom-in-label' => 'گەورەکردنەوە',
);

/** Czech (česky)
 * @author Jkjk
 * @author Matěj Grabovský
 * @author Mormegil
 */
$messages['cs'] = array(
	'indexpages' => 'Seznam indexových stránek',
	'pageswithoutscans' => 'Stránky bez skenů',
	'proofreadpage_desc' => 'Umožňuje jednoduché porovnání textu s předlohou',
	'proofreadpage_image' => 'Soubor',
	'proofreadpage_index' => 'Index',
	'proofreadpage_index_expected' => 'Chyba: očekáván index',
	'proofreadpage_nosuch_index' => 'Chyba: takový index neexistuje',
	'proofreadpage_nosuch_file' => 'Chyba: takový soubor neexistuje',
	'proofreadpage_badpage' => 'Nesprávný formát',
	'proofreadpage_badpagetext' => 'Formát stránky, kterou jste se pokusili uložit, není správný.',
	'proofreadpage_indexdupe' => 'Duplicitní odkaz',
	'proofreadpage_indexdupetext' => 'Stránky mohou být v indexu uvedeny maximálně jednou.',
	'proofreadpage_nologin' => 'Nejste přihlášeni',
	'proofreadpage_nologintext' => 'Pokud chcete změnit stav zkontrolování stránky, musíte se [[Special:UserLogin|přihlásit]].',
	'proofreadpage_notallowed' => 'Změna není povolena',
	'proofreadpage_notallowedtext' => 'Nemáte povoleno měnit stav zkontrolování této stránky.',
	'proofreadpage_number_expected' => 'Chyba: očekávána číselná hodnota',
	'proofreadpage_interval_too_large' => 'Chyba: příliš velký interval',
	'proofreadpage_invalid_interval' => 'Chyba: nesprávný interval',
	'proofreadpage_nextpage' => 'Další stránka',
	'proofreadpage_prevpage' => 'Předchozí stránka',
	'proofreadpage_header' => 'Hlavička (noinclude):',
	'proofreadpage_body' => 'Tělo stránky (pro transkluzi):',
	'proofreadpage_footer' => 'Patička (noinclude):',
	'proofreadpage_toggleheaders' => 'přepnout viditelnost sekcí noinclude',
	'proofreadpage_quality0_category' => 'Bez textu',
	'proofreadpage_quality1_category' => 'Nebylo zkontrolováno',
	'proofreadpage_quality2_category' => 'Problematické',
	'proofreadpage_quality3_category' => 'Zkontrolováno',
	'proofreadpage_quality4_category' => 'Ověřeno',
	'proofreadpage_quality0_message' => 'Tuto stránku není potřeba kontrolovat',
	'proofreadpage_quality1_message' => 'Tato stránka nebyla zkontrolována',
	'proofreadpage_quality2_message' => 'Při kontrole této stránky se objevil problém',
	'proofreadpage_quality3_message' => 'Tato stránka byla zkontrolována',
	'proofreadpage_quality4_message' => 'Tato stránka byla ověřena',
	'proofreadpage_index_size' => 'Počet stránek',
	'proofreadpage_specialpage_label_orderby' => 'Řadit podle:',
	'proofreadpage_specialpage_label_key' => 'Hledat:',
	'proofreadpage_specialpage_label_sortascending' => 'Seřadit vzestupně',
	'proofreadpage_alphabeticalorder' => 'Abecední pořadí',
	'proofreadpage_index_listofpages' => 'Seznam stránek',
	'proofreadpage_image_message' => 'Odkaz na úvodní stránku',
	'proofreadpage_page_status' => 'Stav stránky',
	'proofreadpage_js_attributes' => 'Autor Název Rok Vydavatel',
	'proofreadpage_index_attributes' => 'Autor
Název
Rok|Rok vydání
Vydavatelství
Obrázek|Obálka
Stran||20
Poznámky||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|stránka|stránky|stránek}}',
	'proofreadpage_specialpage_legend' => 'Hledat na indexových stránkách',
	'proofreadpage_specialpage_searcherror' => 'Chyba ve vyhledávacím systému',
	'proofreadpage_specialpage_searcherrortext' => 'Vyhledávací systém nefunguje. Omlouváme se za případné potíže.',
	'proofreadpage_source' => 'Zdroj',
	'proofreadpage_source_message' => 'Naskenovaná verze použitá k vypracování tohoto textu',
	'right-pagequality' => 'Upravování příznaku kvality stránky',
	'proofreadpage-section-tools' => 'Nástroje pro korekturu',
	'proofreadpage-group-zoom' => 'Přiblížení',
	'proofreadpage-group-other' => 'Jiné',
	'proofreadpage-button-toggle-visibility-label' => 'Zobrazit/skrýt záhlaví a zápatí této stránky',
	'proofreadpage-button-zoom-out-label' => 'Oddálit',
	'proofreadpage-button-reset-zoom-label' => 'Původní velikost',
	'proofreadpage-button-zoom-in-label' => 'Přiblížit',
	'proofreadpage-button-toggle-layout-label' => 'Vertikální/horizontální uspořádání',
	'proofreadpage-preferences-showheaders-label' => 'Při editaci ve jmenném prostoru {{ns:page}} zobrazovat hlavičku a patičku',
	'proofreadpage-preferences-horizontal-layout-label' => 'Při editaci ve jmenném prostoru {{ns:page}} používat vodorovné rozložení',
);

/** Church Slavic (словѣ́ньскъ / ⰔⰎⰑⰂⰡⰐⰠⰔⰍⰟ)
 * @author ОйЛ
 */
$messages['cu'] = array(
	'proofreadpage_specialpage_label_key' => 'исканиѥ :',
);

/** Welsh (Cymraeg)
 * @author Lloffiwr
 * @author Robin Owain
 */
$messages['cy'] = array(
	'indexpages' => 'Rhestr y mynegeion',
	'pageswithoutscans' => 'Tudalennau heb eu sganio',
	'proofreadpage_desc' => "Yn hwyluso cymharu testun gyda'r sgan gwreiddiol",
	'proofreadpage_image' => 'Delwedd',
	'proofreadpage_index' => 'Mynegai',
	'proofreadpage_index_expected' => 'Gwall: disgwylid mynegai',
	'proofreadpage_nosuch_index' => "Gwall: ni chafwyd hyd i'r mynegai",
	'proofreadpage_nosuch_file' => "Gwall: ni chafwyd hyd i'r ffeil",
	'proofreadpage_badpage' => 'Y Fformat Yn Anghywir',
	'proofreadpage_badpagetext' => 'Mae fformat y dudalen y ceisiasoch ei chadw yn anghywir.',
	'proofreadpage_indexdupe' => 'Cyswllt dyblyg',
	'proofreadpage_indexdupetext' => 'Ni ellir rhestri tudalennau mwy nag unwaith ar dudalen mynegeio.',
	'proofreadpage_nologin' => 'Nid ydych wedi mewngofnodi',
	'proofreadpage_nologintext' => 'Rhaid eich bod wedi [[Special:UserLogin|mewngofnodi]] i newid statws prawfddarllen y tudalennau.',
	'proofreadpage_notallowed' => 'Ddim yn cael newid y statws',
	'proofreadpage_notallowedtext' => 'Ni chewch newid statws prawfddarllen y dudalen hon.',
	'proofreadpage_dataconfig_badformatted' => 'Mae byg i gael yn ffurfweddiad y data',
	'proofreadpage_dataconfig_badformattedtext' => 'Nid yw fformat JSON y dudalen  [[Mediawiki:Proofreadpage index data config]] yn gywir.',
	'proofreadpage_number_expected' => 'Gwall: disgwylid gwerth rhifol',
	'proofreadpage_nextpage' => "I'r dudalen nesaf",
	'proofreadpage_prevpage' => "I'r dudalen gynt",
	'proofreadpage_header' => "Pennyn (ddim i'w drawsgynnwys):",
	'proofreadpage_body' => "Testun y dudalen (i'w drawsgynnwys):",
	'proofreadpage_footer' => "Troedyn (ddim i'w drawsgynnwys):",
	'proofreadpage_toggleheaders' => "newid rhwng datguddio a chuddio'r adrannau nad ydynt i'w trawsgynnwys",
	'proofreadpage_quality0_category' => 'Heb y testun',
	'proofreadpage_quality1_category' => 'Heb ei brawfddarllen eto',
	'proofreadpage_quality2_category' => 'Gwallus',
	'proofreadpage_quality3_category' => 'Darllenwyd y proflenni',
	'proofreadpage_quality4_category' => 'Gwirwyd',
	'proofreadpage_quality0_message' => 'Nid oes angen prawfddarllen y dudalen hon',
	'proofreadpage_quality1_message' => 'Ni brawfddarllenwyd y dudalen hon eto',
	'proofreadpage_quality2_message' => 'Roedd gwall wrth brawfddarllen y dudalen hon',
	'proofreadpage_quality3_message' => 'Prawfddarllenwyd y dudalen hon',
	'proofreadpage_quality4_message' => 'Gwirwyd y dudalen hon',
	'proofreadpage_index_status' => 'Statws y mynegai',
	'proofreadpage_index_size' => 'Nifer y tudalennau',
	'proofreadpage_specialpage_label_orderby' => 'Trefnu yn ôl:',
	'proofreadpage_specialpage_label_key' => 'Chwilio am:',
	'proofreadpage_specialpage_label_sortascending' => 'Trefnu gan esgyn',
	'proofreadpage_alphabeticalorder' => 'Yn nhrefn yr wyddor',
	'proofreadpage_index_listofpages' => 'Rhestr y tudalennau',
	'proofreadpage_image_message' => 'Dolen i dudalen y mynegai',
	'proofreadpage_page_status' => 'Statws y dudalen',
	'proofreadpage_js_attributes' => 'Awdur Teitl Blwyddyn Cyhoeddwr',
	'proofreadpage_index_attributes' => 'Awdur
Teitl
Blwyddyn|Blwyddyn cyhoeddi
Cyhoeddwr
Ffynhonnell
Delwedd|Delwedd y clawr
Tudalennau||20
Sylwadau||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|tudalennau|dudalen|dudalen|tudalen|thudalen|tudalen}}',
	'proofreadpage_specialpage_legend' => "Chwilio drwy'r tudalennau mynegai",
	'proofreadpage_specialpage_searcherror' => 'Gwall yn y porwr',
	'proofreadpage_specialpage_searcherrortext' => "Dyw'r porwr ddim yn gweithio. Ymddiheurwn am hyn.",
	'proofreadpage_source' => 'Ffynhonnell',
	'proofreadpage_source_message' => "Y rhifyn a sganiwyd fel sail i'r testun hwn",
	'right-pagequality' => "Addasu baner safoni'r dudalen",
	'proofreadpage-section-tools' => 'Cyfarpar prawfddarllen',
	'proofreadpage-group-zoom' => 'Chwyddo',
	'proofreadpage-group-other' => 'Eraill',
	'proofreadpage-button-toggle-visibility-label' => 'Dangos/cuddio pennyn a throedyn y dudalen',
	'proofreadpage-button-zoom-out-label' => 'Lleihau',
	'proofreadpage-button-reset-zoom-label' => 'Y maint gwreiddiol',
	'proofreadpage-button-zoom-in-label' => 'Chwyddo',
	'proofreadpage-button-toggle-layout-label' => 'Gosod am lan/ar draws',
	'proofreadpage-preferences-showheaders-label' => 'Dangos y maesydd pennyn a throedyn wrth olygu yn y parth {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'defnyddio gosodiad ar draws pan yn golygu yn y parth {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => "Metadata'r llyfrau o {{SITENAME}}",
	'proofreadpage-indexoai-eprint-content-text' => "Metadata'r llyfrau a drefnir gan ProofreadPage.",
	'proofreadpage-indexoai-error-schemanotfound' => "Ni chafwyd hyd i'r sgema",
	'proofreadpage-indexoai-error-schemanotfound-text' => "Ni chafwyd hyd i'r sgema $1.",
	'proofreadpage-disambiguationspage' => 'Template:gwahaniaethu',
);

/** Danish (dansk)
 * @author Christian List
 * @author Dferg
 * @author Jon Harald Søby
 * @author Peter Alberti
 * @author Sarrus
 */
$messages['da'] = array(
	'indexpages' => 'Liste over indekssider',
	'pageswithoutscans' => 'Sider uden indskannede billeder',
	'proofreadpage_desc' => 'Muliggør nem sammenligning af tekst med den indscannede original',
	'proofreadpage_image' => 'Billede',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => 'Fejl: indeks forventet',
	'proofreadpage_nosuch_index' => 'Fejl: intet indeks med det navn',
	'proofreadpage_nosuch_file' => 'Fejl: ingen fil med det navn',
	'proofreadpage_badpage' => 'Forkert format',
	'proofreadpage_badpagetext' => 'Formatet på den side, du forsøgte at gemme, er forkert.',
	'proofreadpage_indexdupe' => 'Linket er en duplet',
	'proofreadpage_indexdupetext' => 'Sider kan ikke vises mere end én gang på en indeksside.',
	'proofreadpage_nologin' => 'Ikke logget på',
	'proofreadpage_nologintext' => 'Du skal være [[Special:UserLogin|logget på]] for at ændre en sides korrekturlæsningsstatus.',
	'proofreadpage_notallowed' => 'Ændringer er ikke tilladt',
	'proofreadpage_notallowedtext' => 'Du har ikke rettigheder til at ændre korrekturlæsningen på denne side.',
	'proofreadpage_dataconfig_badformatted' => 'Fejl i dataopsætning',
	'proofreadpage_dataconfig_badformattedtext' => 'Siden [[Mediawiki:Proofreadpage index data config]] er ikke i velformateret JSON.',
	'proofreadpage_number_expected' => 'Fejl: talværdi forventet',
	'proofreadpage_interval_too_large' => 'Fejl: for stort interval',
	'proofreadpage_invalid_interval' => 'Fejl: ugyldigt interval',
	'proofreadpage_nextpage' => 'Næste side',
	'proofreadpage_prevpage' => 'Forrige side',
	'proofreadpage_header' => 'Sidehoved (inkluderes ikke)',
	'proofreadpage_body' => 'Sidens indhold (som skal inkluderes)',
	'proofreadpage_footer' => 'Sidefod (inkluderes ikke)',
	'proofreadpage_toggleheaders' => 'Slå synligheden af sidehoved og -fod til og fra',
	'proofreadpage_quality0_category' => 'Uden tekst',
	'proofreadpage_quality1_category' => 'Ikke korrekturlæst',
	'proofreadpage_quality2_category' => 'Problematisk',
	'proofreadpage_quality3_category' => 'Korrekturlæst',
	'proofreadpage_quality4_category' => 'Valideret',
	'proofreadpage_quality0_message' => 'Denne side behøver ikke korrekturlæsning',
	'proofreadpage_quality1_message' => 'Denne side er ikke blevet korrekturlæst',
	'proofreadpage_quality2_message' => 'Der opstod et problem under korrekturlæsningen af denne side',
	'proofreadpage_quality3_message' => 'Denne side er blevet korrekturlæst',
	'proofreadpage_quality4_message' => 'Denne side er valideret',
	'proofreadpage_index_status' => 'Indeksstatus',
	'proofreadpage_index_size' => 'Antal sider',
	'proofreadpage_specialpage_label_orderby' => 'Sorter efter:',
	'proofreadpage_specialpage_label_key' => 'Søg:',
	'proofreadpage_specialpage_label_sortascending' => 'Sorter stigende',
	'proofreadpage_alphabeticalorder' => 'Alfabetisk orden',
	'proofreadpage_index_listofpages' => 'Liste over sider',
	'proofreadpage_image_message' => 'Link til indekssiden',
	'proofreadpage_page_status' => 'Sidestatus',
	'proofreadpage_js_attributes' => 'Forfatter Titel År Udgiver',
	'proofreadpage_index_attributes' => 'Forfatter
Titel
År|Udgivelsesår
Udgiver
Kilde
Billede|Titelblad
Sider||20
Bemærkninger||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|side|sider}}',
	'proofreadpage_specialpage_legend' => 'Søg i indekssider',
	'proofreadpage_specialpage_searcherror' => 'Fejl i søgemaskinen',
	'proofreadpage_specialpage_searcherrortext' => 'Søgemaskinen virker ikke. Vi beklager ulejligheden.',
	'proofreadpage_source' => 'Kilde',
	'proofreadpage_source_message' => 'Indscannet original, der blev brugt som grundlag for denne tekst',
	'right-pagequality' => 'Ændre en sides kvalititetsflag',
	'proofreadpage-section-tools' => 'Korrekturlæsning',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Øvrigt',
	'proofreadpage-button-toggle-visibility-label' => 'Vis/skjul denne sides sidehoved og sidefod',
	'proofreadpage-button-zoom-out-label' => 'Zoom ud',
	'proofreadpage-button-reset-zoom-label' => 'Oprindelig størrelse',
	'proofreadpage-button-zoom-in-label' => 'Zoom ind',
	'proofreadpage-button-toggle-layout-label' => 'Lodret/vandret opsætning',
	'proofreadpage-preferences-showheaders-label' => 'Åbn automatisk felterne for sidehoved og sidefod under redigering i {{ns:page}}navnerummet',
	'proofreadpage-preferences-horizontal-layout-label' => 'Brug vandret opsætning, når du redigerer i {{ns:page}}-navnerummet',
	'proofreadpage-indexoai-repositoryName' => 'Metadata for bøger fra {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadata for bøger, der forvaltes af ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Skemaet blev ikke fundet',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Skemaet $1 er ikke blevet fundet.',
);

/** German (Deutsch)
 * @author Imre
 * @author Kghbln
 * @author Metalhead64
 * @author Raimond Spekking
 * @author Tbleher
 * @author ThomasV
 */
$messages['de'] = array(
	'indexpages' => 'Liste von Indexseiten',
	'pageswithoutscans' => 'Seiten ohne Scans',
	'proofreadpage_desc' => 'Ermöglicht das bequeme Vergleichen von Text mit dem Originalscan',
	'proofreadpage_image' => 'Scan',
	'proofreadpage_index' => 'Index',
	'proofreadpage_index_expected' => 'Fehler: Index erwartet',
	'proofreadpage_nosuch_index' => 'Fehler: Kein entsprechender Index',
	'proofreadpage_nosuch_file' => 'Fehler: Keine entsprechende Datei',
	'proofreadpage_badpage' => 'Falsches Format',
	'proofreadpage_badpagetext' => 'Das Format der Seite, die du versuchst zu speichern, ist falsch.',
	'proofreadpage_indexdupe' => 'Doppelter Link',
	'proofreadpage_indexdupetext' => 'Seiten können nicht mehr als einmal auf einer Indexseite aufgelistet werden.',
	'proofreadpage_nologin' => 'Nicht angemeldet',
	'proofreadpage_nologintext' => 'Du musst [[Special:UserLogin|angemeldet sein]], um den Status des Korrekturlesens von Seiten ändern zu können.',
	'proofreadpage_notallowed' => 'Änderung nicht erlaubt',
	'proofreadpage_notallowedtext' => 'Du bist nicht berechtigt, den Status des Korrekturlesens dieser Seite zu ändern.',
	'proofreadpage_dataconfig_badformatted' => 'Fehler in der Datenkonfiguration',
	'proofreadpage_dataconfig_badformattedtext' => 'Die Seite [[Mediawiki:Proofreadpage index data config]] ist nicht in wohlgeformtem JSON.',
	'proofreadpage_number_expected' => 'Fehler: Numerischer Wert erwartet',
	'proofreadpage_interval_too_large' => 'Fehler: Intervall zu groß',
	'proofreadpage_invalid_interval' => 'Fehler: ungültiges Intervall',
	'proofreadpage_nextpage' => 'Nächste Seite',
	'proofreadpage_prevpage' => 'Vorherige Seite',
	'proofreadpage_header' => 'Kopfzeile (nicht einzufügen):',
	'proofreadpage_body' => 'Textkörper (einzufügen):',
	'proofreadpage_footer' => 'Fußzeile (nicht einzufügen):',
	'proofreadpage_toggleheaders' => 'Nicht einzufügende Abschnitte ein-/ausblenden',
	'proofreadpage_quality0_category' => 'Ohne Text',
	'proofreadpage_quality1_category' => 'Unkorrigiert',
	'proofreadpage_quality2_category' => 'Korrekturproblem',
	'proofreadpage_quality3_category' => 'Korrigiert',
	'proofreadpage_quality4_category' => 'Fertig',
	'proofreadpage_quality0_message' => 'Diese Seite muss nicht korrekturgelesen werden.',
	'proofreadpage_quality1_message' => 'Diese Seite wurde noch nicht korrekturgelesen.',
	'proofreadpage_quality2_message' => 'Dieser Text wurde korrekturgelesen, enthält aber noch Problemfälle. Nähere Informationen zu den Problemen finden sich möglicherweise auf der Diskussionsseite.',
	'proofreadpage_quality3_message' => 'Dieser Text wurde anhand der angegebenen Quelle einmal korrekturgelesen. Die Schreibweise sollte dem Originaltext folgen. Es ist noch ein weiterer Korrekturdurchgang nötig.',
	'proofreadpage_quality4_message' => 'Fertig. Dieser Text wurde zweimal anhand der Quelle korrekturgelesen. Die Schreibweise folgt dem Originaltext.',
	'proofreadpage_index_status' => 'Indexstatus',
	'proofreadpage_index_size' => 'Anzahl der Seiten',
	'proofreadpage_specialpage_label_orderby' => 'Sortieren nach:',
	'proofreadpage_specialpage_label_key' => 'Suchen:',
	'proofreadpage_specialpage_label_sortascending' => 'Aufsteigend sortieren',
	'proofreadpage_alphabeticalorder' => 'Alphabetische Reihenfolge',
	'proofreadpage_index_listofpages' => 'Seitenliste',
	'proofreadpage_image_message' => 'Link zur Indexseite',
	'proofreadpage_page_status' => 'Seitenstatus',
	'proofreadpage_js_attributes' => 'Autor Titel Jahr Verlag',
	'proofreadpage_index_attributes' => 'Autor
Titel
Jahr|Erscheinungsjahr
Verlag
Quelle
Bild|Titelbild
Seiten||20
Bemerkungen||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|Seite|Seiten}}',
	'proofreadpage_specialpage_legend' => 'Indexseiten durchsuchen',
	'proofreadpage_specialpage_searcherror' => 'Fehler bei der Suchmaschine',
	'proofreadpage_specialpage_searcherrortext' => 'Die Suchmaschine funktioniert leider nicht. Entschuldige die Unannehmlichkeiten.',
	'proofreadpage_source' => 'Quelle',
	'proofreadpage_source_message' => 'Zur Erstellung dieses Texts wurde die gescannte Ausgabe benutzt.',
	'right-pagequality' => 'Seitenqualität ändern',
	'proofreadpage-section-tools' => 'Hilfsmittel zum Korrekturlesen',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Anderes',
	'proofreadpage-button-toggle-visibility-label' => 'Kopf- und Fußzeile dieser Seite ein-/ausblenden',
	'proofreadpage-button-zoom-out-label' => 'Verkleinern',
	'proofreadpage-button-reset-zoom-label' => 'Zoom zurücksetzen',
	'proofreadpage-button-zoom-in-label' => 'Vergrößern',
	'proofreadpage-button-toggle-layout-label' => 'Vertikale/horizontale Ausrichtung',
	'proofreadpage-preferences-showheaders-label' => 'Beim Bearbeiten von Seiten im Namensraum {{ns:page}} die Felder für die Kopf- und die Fußzeile anzeigen',
	'proofreadpage-preferences-horizontal-layout-label' => 'Beim Bearbeiten von Seiten im Namensraum {{ns:page}} ein horizontales Layout verwenden',
	'proofreadpage-indexoai-repositoryName' => 'Buchmetadaten von {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Buchmetadaten, die von der Erweiterung „ProofreadPage“ verwaltet werden',
	'proofreadpage-indexoai-error-schemanotfound' => 'Schema nicht gefunden',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Das Schema „$1“ wurde nicht gefunden',
	'proofreadpage-disambiguationspage' => 'Template:Begriffsklärung',
);

/** German (formal address) (Deutsch (Sie-Form)‎)
 * @author Imre
 * @author Kghbln
 */
$messages['de-formal'] = array(
	'proofreadpage_badpagetext' => 'Das Format der Seite, die Sie versuchen zu speichern, ist falsch.',
	'proofreadpage_nologintext' => 'Sie müssen [[Special:UserLogin|angemeldet sein]], um den Status des Korrekturlesens von Seiten ändern zu können.',
	'proofreadpage_notallowedtext' => 'Sie sind nicht berechtigt, den Status des Korrekturlesens dieser Seite zu ändern.',
	'proofreadpage_specialpage_searcherrortext' => 'Die Suchmaschine funktioniert leider nicht. Entschuldigen Sie die Unannehmlichkeiten.',
);

/** Zazaki (Zazaki)
 * @author Aspar
 * @author Erdemaslancan
 * @author Gorizon
 * @author Mirzali
 */
$messages['diq'] = array(
	'indexpages' => 'Lista pelanê zerreki',
	'pageswithoutscans' => 'Pelê ke geyrayışê cı çıniyo',
	'proofreadpage_desc' => 'Destur bıde wa nuşte pê cıgerayışê oricinali rehet u asan têver şaniyo',
	'proofreadpage_image' => 'Resım',
	'proofreadpage_index' => 'Zerrek',
	'proofreadpage_index_expected' => 'Xeta: zerrek pawiyeno',
	'proofreadpage_nosuch_index' => 'Xeta: zerreko wıni çıniyo',
	'proofreadpage_nosuch_file' => 'Xeta: dosya wınasiye çıniya.',
	'proofreadpage_badpage' => 'Formato Xırabın',
	'proofreadpage_badpagetext' => 'Formatê pela ke şıma wazenê qeyd kerê ğeleto.',
	'proofreadpage_indexdupe' => 'Gıre beno zêde',
	'proofreadpage_indexdupetext' => 'Peli zerreyê pela zerreki de yew ra zêde liste nêbenê.',
	'proofreadpage_nologin' => 'Şıma cıkewtış nêvıraşto',
	'proofreadpage_nologintext' => 'qey vurnayişê halê raştkerdışê pelan gani şıma [[Special:UserLogin|cı kewiyi]].',
	'proofreadpage_notallowed' => 'vurnayiş re destur çino',
	'proofreadpage_notallowedtext' => 'vurnayişê halê raştkerdışê peli re destur nêdano',
	'proofreadpage_dataconfig_badformatted' => 'Vıraştışê melumati de xeta',
	'proofreadpage_dataconfig_badformattedtext' => 'Pela [[Mediawiki:Proofreadpage index data config]] formatê JSONiê rındi de niya.',
	'proofreadpage_number_expected' => 'Xeta:Amarin weziyet pawéno',
	'proofreadpage_interval_too_large' => 'xeta: benate/mabên zaf hêrayo',
	'proofreadpage_invalid_interval' => 'xeta: benateyo nemeqbul',
	'proofreadpage_nextpage' => 'Pela peyêne',
	'proofreadpage_prevpage' => 'pelo ke pey de mend',
	'proofreadpage_header' => 'sername (ihtiwa)',
	'proofreadpage_body' => 'miyaneyê peli (çepraşt têarê beno):',
	'proofreadpage_footer' => 'Footer (ihtiwa):',
	'proofreadpage_toggleheaders' => 'asayişê qısmi yê ke ihtiwa nıbeni bıvurn',
	'proofreadpage_quality0_category' => 'metn tede çino',
	'proofreadpage_quality1_category' => 'raşt nıbiyo',
	'proofreadpage_quality2_category' => 'problemın',
	'proofreadpage_quality3_category' => 'raşt ker',
	'proofreadpage_quality4_category' => 'Biya araşt',
	'proofreadpage_quality0_message' => 'no pel re raştkerdış luzûm nıkeno',
	'proofreadpage_quality1_message' => 'no pel de reaştkerdış nıbı',
	'proofreadpage_quality2_message' => 'wexta no pel de raştkerdış bêne xeta vıraziya',
	'proofreadpage_quality3_message' => 'no pel de raştkerdış bı',
	'proofreadpage_quality4_message' => 'Na pela araşt nêbiya',
	'proofreadpage_index_status' => 'Weziyetê ratnayışi',
	'proofreadpage_index_size' => 'Amariya pelan',
	'proofreadpage_specialpage_label_orderby' => 'Ratnen:',
	'proofreadpage_specialpage_label_key' => 'Cı geyre:',
	'proofreadpage_specialpage_label_sortascending' => 'Ratnayışo zeydnayış',
	'proofreadpage_alphabeticalorder' => 'Alfabetik ratnayış',
	'proofreadpage_index_listofpages' => 'listeya pelan',
	'proofreadpage_image_message' => 'gıreyo ke erziyayo pelê endeksi',
	'proofreadpage_page_status' => 'halê peli',
	'proofreadpage_js_attributes' => 'nuştox/e sername serre weşanger',
	'proofreadpage_index_attributes' => 'nuştox/e
sername
serre|serrê weşanayişi/neşri
weşanger
çıme
Resım|resmê qapaxi
peli||20
beyanati||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pele|peli}}',
	'proofreadpage_specialpage_legend' => 'bıgêr pelê indeksan',
	'proofreadpage_specialpage_searcherror' => 'Motorê cıgeyrayışi de xırabin',
	'proofreadpage_specialpage_searcherrortext' => 'Motorê cıgeyrayışi nêguriyeno. Qandê coy qusır de mewnirên.',
	'proofreadpage_source' => 'Çıme',
	'proofreadpage_source_message' => 'Versiyono kopyakerde gurêna ke nê meqaley rono',
	'right-pagequality' => 'Vurnayışté pela ré desmal çek',
	'proofreadpage-section-tools' => 'Hacetê raştkerdışê ğeletan',
	'proofreadpage-group-zoom' => 'Nêzdikerdış',
	'proofreadpage-group-other' => 'Sewbi',
	'proofreadpage-button-toggle-visibility-label' => 'Ena pelaya bımocne/bınımni  wanena u asınena',
	'proofreadpage-button-zoom-out-label' => 'Duri fi',
	'proofreadpage-button-reset-zoom-label' => 'Ebado oricinal',
	'proofreadpage-button-zoom-in-label' => 'Nêzdi ke',
	'proofreadpage-button-toggle-layout-label' => 'Kewtey/tikey  asayış',
	'proofreadpage-preferences-showheaders-label' => 'Nameye {{ns:page}} çı vurneyeno heqa cı wendış u asayışi bımocne.',
	'proofreadpage-preferences-horizontal-layout-label' => '{{ns:page}} bındı timar kerdış dı serte hali karkerdış',
	'proofreadpage-indexoai-repositoryName' => '{{SITENAME}} ra metamelumatê kıtaban',
	'proofreadpage-indexoai-eprint-content-text' => 'Metamelumatê kıtaban terefê pela ke ğeletan kena raşt, idare beno.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Şema nêvineyê',
	'proofreadpage-indexoai-error-schemanotfound-text' => '$1 şema nêvineyaya.',
	'proofreadpage-disambiguationspage' => 'Şablon:Maneyo bin',
);

/** Lower Sorbian (dolnoserbski)
 * @author Michawiki
 */
$messages['dsb'] = array(
	'indexpages' => 'Lisćina indeksowych bokow',
	'pageswithoutscans' => 'Boki bźez skanowanjow',
	'proofreadpage_desc' => 'Zmóžnja lažke pśirownowanje teksta z originalnym skanom',
	'proofreadpage_image' => 'Wobraz',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => 'Zmólka: indeks wócakowany',
	'proofreadpage_nosuch_index' => 'Zmólka: taki indeks njejo',
	'proofreadpage_nosuch_file' => 'Zmólka: taka dataja njejo',
	'proofreadpage_badpage' => 'Wopacny format',
	'proofreadpage_badpagetext' => 'Format boka, kótaryž sy wopytał składowaś, jo wopaki.',
	'proofreadpage_indexdupe' => 'Dwójny wótkaz',
	'proofreadpage_indexdupetext' => 'Boki njedaju se wěcej ako jaden raz na indeksowem boku nalicyś.',
	'proofreadpage_nologin' => 'Njejsy se pśizjawił',
	'proofreadpage_nologintext' => 'Musyš [[Special:UserLogin|pśizjawjony]] byś, aby status kontrolnego cytanja bokow změnił.',
	'proofreadpage_notallowed' => 'Změna njedowólona',
	'proofreadpage_notallowedtext' => 'Njesmějoš status kontrolnego cytanja toś togo boka změniś.',
	'proofreadpage_number_expected' => 'Zmólka: numeriska gódnota wócakowana',
	'proofreadpage_interval_too_large' => 'Zmólka: interwal pśewjeliki',
	'proofreadpage_invalid_interval' => 'Zmólka: njepłaśiwy interwal',
	'proofreadpage_nextpage' => 'Pśiducy bok',
	'proofreadpage_prevpage' => 'Slědny bok',
	'proofreadpage_header' => 'Głowowa smužka (noinclude)',
	'proofreadpage_body' => 'Tekstowe śěło',
	'proofreadpage_footer' => 'Nogowa smužka (noinclude):',
	'proofreadpage_toggleheaders' => 'wótrězki noinclude pokazaś/schowaś',
	'proofreadpage_quality0_category' => 'Bźez teksta',
	'proofreadpage_quality1_category' => 'Njekontrolěrowany',
	'proofreadpage_quality2_category' => 'Problematiski',
	'proofreadpage_quality3_category' => 'Pśekontrolěrowany',
	'proofreadpage_quality4_category' => 'Wobwěsćony',
	'proofreadpage_quality0_message' => 'Toś ten bok jo se skorigěrował',
	'proofreadpage_quality1_message' => 'Toś ten bok njejo se skorigěrował',
	'proofreadpage_quality2_message' => 'Pśi korigěrowanju toś togo boka jo se problem nastał',
	'proofreadpage_quality3_message' => 'Toś ten bok jo se skorigěrował',
	'proofreadpage_quality4_message' => 'Toś ten bok jo se pśekontrolěrował',
	'proofreadpage_index_status' => 'Indeksowy status',
	'proofreadpage_index_size' => 'Licba bokow',
	'proofreadpage_specialpage_label_orderby' => 'Sortěrowaś pó:',
	'proofreadpage_specialpage_label_key' => 'Pytaś:',
	'proofreadpage_specialpage_label_sortascending' => 'Stupujucy sortěrowaś',
	'proofreadpage_alphabeticalorder' => 'Alfabetiski pórěd',
	'proofreadpage_index_listofpages' => 'Lisćina bokow',
	'proofreadpage_image_message' => 'Wótkaz k indeksowemu bokoju',
	'proofreadpage_page_status' => 'Bokowy status',
	'proofreadpage_js_attributes' => 'Awtor Titel Lěto Wudawaŕ',
	'proofreadpage_index_attributes' => 'Awtor
Titel
Lěto|Lěto wózjawjenja
Wudawaŕ
Žrědło
Wobraz|Titelowy wobraz
Boki||20
Pśispomnjeśa||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|bok|boka|boki|bokow}}',
	'proofreadpage_specialpage_legend' => 'Indeksowe boki pśepytaś',
	'proofreadpage_specialpage_searcherror' => 'Zmólka w pytawje',
	'proofreadpage_specialpage_searcherrortext' => 'Pytawa njefunkcioněrujo. Wódaj pšosym wobuznosći.',
	'proofreadpage_source' => 'Žrědło',
	'proofreadpage_source_message' => 'Skanowane wudaśe wužyte za napóranje toś togo teksta',
	'right-pagequality' => 'Kawlitu boka změniś',
	'proofreadpage-section-tools' => 'Rědy za korigěrowanje',
	'proofreadpage-group-zoom' => 'Skalěrowanje',
	'proofreadpage-group-other' => 'Druge',
	'proofreadpage-button-toggle-visibility-label' => 'Głowu a nogu toś togo boka pokazaś/schowaś',
	'proofreadpage-button-zoom-out-label' => 'Pómjeńšyś',
	'proofreadpage-button-reset-zoom-label' => 'Spócetna wjelikosć',
	'proofreadpage-button-zoom-in-label' => 'Pówětšyś',
	'proofreadpage-button-toggle-layout-label' => 'Padorowny/Wódorowny layout',
	'proofreadpage-preferences-showheaders-label' => 'Głowowe a nogowe póla pokazaś, gaž wobźěłujo se w mjenjowem rumje {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Horicontalne wugótowanje wužywaś, gaž se w mjenjowem rumje {{ns:page}} wobźěłujo',
);

/** Ewe (eʋegbe) */
$messages['ee'] = array(
	'proofreadpage_namespace' => 'Nuŋɔŋlɔ',
);

/** Greek (Ελληνικά)
 * @author AndreasJS
 * @author Consta
 * @author Crazymadlover
 * @author Dead3y3
 * @author FocalPoint
 * @author Glavkos
 * @author Konsnos
 * @author Omnipaedista
 * @author ZaDiak
 */
$messages['el'] = array(
	'indexpages' => 'Κατάλογος σελίδων ευρετηρίου',
	'pageswithoutscans' => 'Σελίδες χωρίς σάρωση',
	'proofreadpage_desc' => 'Επίτρεψε εύκολη σύγκριση κειμένου με την πρωτότυπη σάρωση',
	'proofreadpage_image' => 'εικόνα',
	'proofreadpage_index' => 'Ευρετήριο',
	'proofreadpage_index_expected' => 'Σφάλμα: αναμενόταν δείκτης',
	'proofreadpage_nosuch_index' => 'Σφάλμα: δεν υπάρχει αυτός ο δείκτης',
	'proofreadpage_nosuch_file' => 'Σφάλμα: δεν υπάρχει αυτό το αρχείο',
	'proofreadpage_badpage' => 'Λανθασμένη μορφοποίηση',
	'proofreadpage_badpagetext' => 'Η μορφοποίηση της σελίδας που προσπαθήσατε να αποθηκεύσετε είναι λανθασμένη.',
	'proofreadpage_indexdupe' => 'Διπλότυπος σύνδεσμος',
	'proofreadpage_indexdupetext' => 'Οι σελίδες δεν μπορούν περιλαμβάνονται στο ευρετήριο περισσότερες από μία φορές.',
	'proofreadpage_nologin' => 'Δεν έχετε συνδεθεί',
	'proofreadpage_nologintext' => 'Πρέπει να είστε [[Special:UserLogin|συνδεδεμένος]] για να αλλάξετε την κατάσταση επαλήθευσης σελίδων.',
	'proofreadpage_notallowed' => 'Αλλαγή δεν επιτρέπεται',
	'proofreadpage_notallowedtext' => 'Δεν επιτρέπεται να αλλάξετε την κατάσταση διόρθωσης κειμένου αυτής της σελίδας.',
	'proofreadpage_number_expected' => 'Σφάλμα: αναμενόταν αριθμητικό μέγεθος',
	'proofreadpage_interval_too_large' => 'Σφάλμα: υπερβολικά μεγάλο διάστημα',
	'proofreadpage_invalid_interval' => 'Σφάλμα: άκυρο διάστημα',
	'proofreadpage_nextpage' => 'Επόμενη σελίδα',
	'proofreadpage_prevpage' => 'Προηγούμενη σελίδα',
	'proofreadpage_header' => 'Επικεφαλίδα (noinclude):',
	'proofreadpage_body' => 'Σώμα σελίδας (προς εσωκλεισμό):',
	'proofreadpage_footer' => 'Κατακλείδα (noinclude):',
	'proofreadpage_toggleheaders' => 'ενάλλαξε την ορατότητα των τμημάτων noinclude',
	'proofreadpage_quality0_category' => 'Χωρίς κείμενο',
	'proofreadpage_quality1_category' => 'Δεν έχει γίνει proofreading',
	'proofreadpage_quality2_category' => 'Προβληματική',
	'proofreadpage_quality3_category' => 'Έχει γίνει proofreading',
	'proofreadpage_quality4_category' => 'Εγκρίθηκε',
	'proofreadpage_quality0_message' => 'Αυτή η σελίδα δεν χρειάζεται να ελεγχθεί για πιθανά λάθη',
	'proofreadpage_quality1_message' => 'Αυτή η σελίδα δεν έχει ελεγχθεί ακόμη για πιθανά λάθη.',
	'proofreadpage_quality2_message' => 'Υπήρξε ένα πρόβλημα στον έλεγχο για πιθανά λάθη αυτής της σελίδας',
	'proofreadpage_quality3_message' => 'Η σελίδα αυτή έχει ελεγθεί για πιθανά λάθη',
	'proofreadpage_quality4_message' => 'Αυτή η σελίδα έχει εγκριθεί',
	'proofreadpage_index_size' => 'Αριθμός σελίδων',
	'proofreadpage_specialpage_label_orderby' => 'Ταξινόμηση με:',
	'proofreadpage_specialpage_label_key' => 'Αναζήτηση:',
	'proofreadpage_specialpage_label_sortascending' => 'Αύξουσα ταξινόμηση',
	'proofreadpage_alphabeticalorder' => 'Αλφαβητική σειρά',
	'proofreadpage_index_listofpages' => 'Κατάλογος σελίδων',
	'proofreadpage_image_message' => 'Σύνδεσμος προς τη σελίδα ευρετηρίου',
	'proofreadpage_page_status' => 'Κατάσταση σελίδας',
	'proofreadpage_js_attributes' => 'Συγγραφέας Τίτλος Έτος Εκδότης',
	'proofreadpage_index_attributes' => 'Συγγραφέας

Τίτλος

Έτος|Έτος έκδοσης

Εκδότης

Πηγή

Εικόνα|Εξώφυλλο

Σελίδες||20

Σχόλια||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|Σελίδα|Σελίδες}}',
	'proofreadpage_specialpage_legend' => 'Αναζήτηση σελίδων ευρετηρίου',
	'proofreadpage_specialpage_searcherror' => 'Σφάλμα στη μηχανή αναζήτησης',
	'proofreadpage_specialpage_searcherrortext' => 'Η μηχανή αναζήτησης δεν λειτουργεί. Συγγνώμη για την ενόχληση.',
	'proofreadpage_source' => 'Πηγή',
	'proofreadpage_source_message' => 'Αυτό το κείμενο προέκυψε από σκαναρισμένη έκδοση',
	'proofreadpage-section-tools' => 'Εργαλεία διόρθωσης κειμένου',
	'proofreadpage-group-zoom' => 'Εστίαση',
	'proofreadpage-group-other' => 'Άλλο',
	'proofreadpage-button-toggle-visibility-label' => 'Εμφάνιση / απόκρυψη κεφαλίδας και υποσέλιδου αυτής της σελίδας',
	'proofreadpage-button-zoom-out-label' => 'Σμίκρυνση',
	'proofreadpage-button-reset-zoom-label' => 'Επαναφορά ζουμ',
	'proofreadpage-button-zoom-in-label' => 'Μεγέθυνση',
	'proofreadpage-button-toggle-layout-label' => 'Κάθετη / οριζόντια διάταξη',
);

/** Esperanto (Esperanto)
 * @author Michawiki
 * @author Yekrats
 * @author Zhang212
 */
$messages['eo'] = array(
	'indexpages' => 'Listo de indeksaj paĝoj',
	'pageswithoutscans' => 'Paĝoj sen skanaĵoj',
	'proofreadpage_desc' => 'Permesas facilan komparon de teksto al la originala skanitaĵo.',
	'proofreadpage_image' => 'Bildo',
	'proofreadpage_index' => 'Indekso',
	'proofreadpage_index_expected' => 'Eraro: indekso atentita',
	'proofreadpage_nosuch_index' => 'Eraro: nenia indekso',
	'proofreadpage_nosuch_file' => 'Eraro: nenia dosiero',
	'proofreadpage_badpage' => 'Malbona Formato',
	'proofreadpage_badpagetext' => 'La formato de la paĝo kiun vi provis konservi estas malĝusta.',
	'proofreadpage_indexdupe' => 'Duplikata ligilo',
	'proofreadpage_indexdupetext' => 'Paĝoj ne povas esti listigataj pli ol unu fojo sur indekspaĝo.',
	'proofreadpage_nologin' => 'Ne ensalutita',
	'proofreadpage_nologintext' => 'Vi devas [[Special:UserLogin|ensaluti]] por modifi la provlegan statuson de paĝojn.',
	'proofreadpage_notallowed' => 'Ŝanĝo ne permesiĝis',
	'proofreadpage_notallowedtext' => 'Vi ne estas permesata ŝanĝi la pruvlegadan statuson de ĉi tiu paĝo.',
	'proofreadpage_dataconfig_badformatted' => 'Insekto en datumoj agordo',
	'proofreadpage_dataconfig_badformattedtext' => 'La paĝo [[MediaWiki: Proofreadpage indekso datumoj config]] ne estas en ĝust-formatan JSON', # Fuzzy
	'proofreadpage_number_expected' => 'Eraro: numera valoro atentita',
	'proofreadpage_interval_too_large' => 'Eraro: intervalo tro granda',
	'proofreadpage_invalid_interval' => 'Eraro: malvalida intervalo',
	'proofreadpage_nextpage' => 'Sekva paĝo',
	'proofreadpage_prevpage' => 'Antaŭa paĝo',
	'proofreadpage_header' => 'Supra titolo (ne inkluzivu):',
	'proofreadpage_body' => 'Paĝa korpo (esti transinkluzivita):',
	'proofreadpage_footer' => 'Suba paĝtitolo (neinkluzive):',
	'proofreadpage_toggleheaders' => 'baskulo neinkluzivu sekcioj videbleco',
	'proofreadpage_quality0_category' => 'Sen teksto',
	'proofreadpage_quality1_category' => 'Ne provlegita',
	'proofreadpage_quality2_category' => 'Problema',
	'proofreadpage_quality3_category' => 'Provlegita',
	'proofreadpage_quality4_category' => 'Validigita',
	'proofreadpage_quality0_message' => 'La paĝo ne bezonas esti provlegata',
	'proofreadpage_quality1_message' => 'Ĉi tiu paĝo ne estis pruvlegita',
	'proofreadpage_quality2_message' => 'Estis problemo pruvlegante ĉi tiun paĝon',
	'proofreadpage_quality3_message' => 'Ĉi tiu paĝo estis pruvlegita',
	'proofreadpage_quality4_message' => 'Ĉi tiu paĝo estis validigita',
	'proofreadpage_index_status' => 'Indekso statuso',
	'proofreadpage_index_size' => 'Nombro de paĝoj',
	'proofreadpage_specialpage_label_orderby' => 'Ordo Por',
	'proofreadpage_specialpage_label_key' => 'Serĉi',
	'proofreadpage_specialpage_label_sortascending' => 'Ordigi kreskante',
	'proofreadpage_alphabeticalorder' => 'alfabeta ordo',
	'proofreadpage_index_listofpages' => 'Listo de paĝoj',
	'proofreadpage_image_message' => 'Ligilo al la indekspaĝo',
	'proofreadpage_page_status' => 'Statuso de paĝo',
	'proofreadpage_js_attributes' => 'Aŭtoro Titolo Jaro Eldonejo',
	'proofreadpage_index_attributes' => 'Aŭtoro
Titolo
Jaro|Jaro de eldonado
Eldonejo
Fonto
Bildo|Bildo de kovrilo
Paĝoj||20
Rimarkoj||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|paĝo|paĝoj}}',
	'proofreadpage_specialpage_legend' => 'Serĉi indeksajn paĝojn',
	'proofreadpage_specialpage_searcherror' => 'Eraro en la serĉilon',
	'proofreadpage_specialpage_searcherrortext' => 'La serĉilo ne funkcias. Pardonu pro la maloportunaĵo',
	'proofreadpage_source' => 'Fonto',
	'proofreadpage_source_message' => 'Skanita eldono uzata establi ĉi tiu teksto',
	'right-pagequality' => 'Modifi flagon de paĝa kvalito',
	'proofreadpage-section-tools' => 'Iloj por provlegado',
	'proofreadpage-group-zoom' => 'Zomi',
	'proofreadpage-group-other' => 'Alia',
	'proofreadpage-button-toggle-visibility-label' => 'Montri/kaŝi la kaplinion kaj piedlinion de ĉi tiu paĝo.',
	'proofreadpage-button-zoom-out-label' => 'Malzomi',
	'proofreadpage-button-reset-zoom-label' => 'Refreŝi zomnivelon',
	'proofreadpage-button-zoom-in-label' => 'Zomi',
	'proofreadpage-button-toggle-layout-label' => 'Vertikala/horizonta aspekto',
	'proofreadpage-preferences-showheaders-label' => 'Montru header kaj footer kampoj dum redaktado en la {{ns: paĝo}} nomspaco',
	'proofreadpage-preferences-horizontal-layout-label' => 'Uzu horizontala aranĝo dum redaktado en la {{ns: paĝo}} nomspaco',
	'proofreadpage-indexoai-repositoryName' => 'Metadatumoj de libroj de {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadatumoj de libroj administrata de ProofreadPage',
	'proofreadpage-indexoai-error-schemanotfound' => 'Schema ne trovita',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'La $ 1 skemo ne estis trovitaj.', # Fuzzy
	'proofreadpage-disambiguationspage' => 'Template:Apartigilo',
);

/** Spanish (español)
 * @author Aleator
 * @author Armando-Martin
 * @author Barcex
 * @author Crazymadlover
 * @author Fitoschido
 * @author Imre
 * @author Locos epraix
 * @author Ralgis
 * @author Remember the dot
 * @author Sanbec
 * @author Translationista
 */
$messages['es'] = array(
	'indexpages' => 'Lista de páginas indexadas',
	'pageswithoutscans' => 'Páginas sin exploraciones',
	'proofreadpage_desc' => 'Permitir una fácil comparación de un texto con el escaneado original',
	'proofreadpage_image' => 'Imagen',
	'proofreadpage_index' => 'Índice',
	'proofreadpage_index_expected' => 'Error: se esperaba un índice',
	'proofreadpage_nosuch_index' => 'Error: no hay tal índice',
	'proofreadpage_nosuch_file' => 'Error: no existe el archivo',
	'proofreadpage_badpage' => 'Formato erróneo',
	'proofreadpage_badpagetext' => 'El formato de la página que intestaste grabar es incorrecto.',
	'proofreadpage_indexdupe' => 'Vínculo duplicado',
	'proofreadpage_indexdupetext' => 'Las páginas no pueden ser listadas más de una vez en una página índice.',
	'proofreadpage_nologin' => 'No ha iniciado sesión',
	'proofreadpage_nologintext' => 'Debes haber [[Special:UserLogin|iniciado sesión]] para modificar el estado de corrección de las páginas.',
	'proofreadpage_notallowed' => 'Cambio no permitido',
	'proofreadpage_notallowedtext' => 'No estás permitido de cambiar el estatus corregido de esta página.',
	'proofreadpage_dataconfig_badformatted' => 'Error en la configuración de datos',
	'proofreadpage_dataconfig_badformattedtext' => 'El formato de JSON de la página [[Mediawiki:Proofreadpage index data config]] no es válido.',
	'proofreadpage_number_expected' => 'Error: se esperaba un valor numérico',
	'proofreadpage_interval_too_large' => 'Error: intervalo demasiado grande',
	'proofreadpage_invalid_interval' => 'Error: intervalo inválido',
	'proofreadpage_nextpage' => 'Página siguiente',
	'proofreadpage_prevpage' => 'Página anterior',
	'proofreadpage_header' => 'Encabezado (noinclude):',
	'proofreadpage_body' => 'Cuerpo de la página (para ser transcluido):',
	'proofreadpage_footer' => 'Pie de página (noinclude):',
	'proofreadpage_toggleheaders' => 'cambiar la visibilidad de las secciones noinclude',
	'proofreadpage_quality0_category' => 'Sin texto',
	'proofreadpage_quality1_category' => 'No corregido',
	'proofreadpage_quality2_category' => 'Problemática',
	'proofreadpage_quality3_category' => 'Corregido',
	'proofreadpage_quality4_category' => 'Validada',
	'proofreadpage_quality0_message' => 'Esta página no necesita ser corregida',
	'proofreadpage_quality1_message' => 'Esta página no ha sido corregida',
	'proofreadpage_quality2_message' => 'Hubo un problema cuando se corregía esta página',
	'proofreadpage_quality3_message' => 'Esta página ha sido corregida',
	'proofreadpage_quality4_message' => 'Esta página ha sido validada',
	'proofreadpage_index_status' => 'Estado del índice',
	'proofreadpage_index_size' => 'Número de páginas',
	'proofreadpage_specialpage_label_orderby' => 'Ordenar por:',
	'proofreadpage_specialpage_label_key' => 'Buscar:',
	'proofreadpage_specialpage_label_sortascending' => 'Orden ascendente',
	'proofreadpage_alphabeticalorder' => 'Orden alfabético',
	'proofreadpage_index_listofpages' => 'Lista de páginas',
	'proofreadpage_image_message' => 'Enlace a la página de índice',
	'proofreadpage_page_status' => 'Estatus de página',
	'proofreadpage_js_attributes' => 'Autor Título Año Editor',
	'proofreadpage_index_attributes' => 'Autor
Título
Año|Año de publicación
Editor
Fuente
Imagen|Imagen de cubierta
Páginas||20
Comentarios||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|página|páginas}}',
	'proofreadpage_specialpage_legend' => 'Buscar en páginas de índice',
	'proofreadpage_specialpage_searcherror' => 'Error en el motor de búsqueda',
	'proofreadpage_specialpage_searcherrortext' => 'No funciona el motor de búsqueda. Disculpen las molestias.',
	'proofreadpage_source' => 'Fuente',
	'proofreadpage_source_message' => 'Edición escaneada usada para establecer este texto',
	'right-pagequality' => 'Modificar la marca de calidad de la página',
	'proofreadpage-section-tools' => 'Coregir herramientas',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Otro',
	'proofreadpage-button-toggle-visibility-label' => 'Mostrar/ ocultar el encabezado y el pie de esta página',
	'proofreadpage-button-zoom-out-label' => 'Alejar',
	'proofreadpage-button-reset-zoom-label' => 'Tamaño original',
	'proofreadpage-button-zoom-in-label' => 'Aumentar',
	'proofreadpage-button-toggle-layout-label' => 'Disposición vertical/horizontal',
	'proofreadpage-preferences-showheaders-label' => 'Mostrar campos de encabezado y pie de página cuando se edita en el espacio de nombres {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Usar diseño horizontal cuando se edita en el espacio de nombres {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Metadatos de libros de {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadatos de libros gestionados por ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Esquema no encontrado',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'El esquema $1 no ha sido encontrado.',
);

/** Estonian (eesti)
 * @author Avjoska
 * @author Pikne
 * @author WikedKentaur
 */
$messages['et'] = array(
	'indexpages' => 'Registrilehtede loetelu',
	'pageswithoutscans' => 'Skannimata tekstidega leheküljed',
	'proofreadpage_desc' => 'Võimaldab teksti kõrvutada skannitud lehega.',
	'proofreadpage_image' => 'Pilt',
	'proofreadpage_index' => 'Register',
	'proofreadpage_index_expected' => 'Tõrge: register puudub',
	'proofreadpage_nosuch_index' => 'Tõrge: küsitud registrit pole',
	'proofreadpage_nosuch_file' => 'Tõrge: faili ei leitud',
	'proofreadpage_badpage' => 'Vale vorming',
	'proofreadpage_badpagetext' => 'Salvestatava lehe vorming on vigane.',
	'proofreadpage_indexdupe' => 'Kahekordne link',
	'proofreadpage_indexdupetext' => 'Lehekülge saab registris loetleda vaid ühe korra.',
	'proofreadpage_nologin' => 'Ei ole sisse logitud',
	'proofreadpage_nologintext' => 'Pead lehekülje tõendusoleku muutmiseks [[Special:UserLogin|sisse logima]].',
	'proofreadpage_notallowed' => 'Muudatus ei ole lubatud',
	'proofreadpage_notallowedtext' => 'Sul pole lubatud lehekülje tõendusolekut muuta.',
	'proofreadpage_number_expected' => 'Tõrge: sisesta arv',
	'proofreadpage_interval_too_large' => 'Tõrge: liiga suur vahemik',
	'proofreadpage_invalid_interval' => 'Tõrge: vigane vahemik',
	'proofreadpage_nextpage' => 'Järgmine lehekülg',
	'proofreadpage_prevpage' => 'Eelmine lehekülg',
	'proofreadpage_header' => 'Päis (ei sisaldu):',
	'proofreadpage_body' => 'Tekstiosa (sisaldub):',
	'proofreadpage_footer' => 'Jalus (ei sisaldu):',
	'proofreadpage_toggleheaders' => 'Näita või peida sisaldamata osad',
	'proofreadpage_quality0_category' => 'Ilma tekstita',
	'proofreadpage_quality1_category' => 'Õigsus tõendamata',
	'proofreadpage_quality2_category' => 'Probleemne',
	'proofreadpage_quality3_category' => 'Õigsus tõendatud',
	'proofreadpage_quality4_category' => 'Heakskiidetud',
	'proofreadpage_quality0_message' => 'Selle lehekülje õigsus ei vaja tõendamist.',
	'proofreadpage_quality1_message' => 'Selle lehekülje õigsus on tõendamata.',
	'proofreadpage_quality2_message' => 'Selle lehekülje õigsuse tõendamisel ilmnes probleem.',
	'proofreadpage_quality3_message' => 'Selle lehekülje õigsus on tõendatud.',
	'proofreadpage_quality4_message' => 'See lehekülg on heaks kiidetud.',
	'proofreadpage_index_status' => 'Olek',
	'proofreadpage_index_size' => 'Lehekülgede arv',
	'proofreadpage_specialpage_label_orderby' => 'Järjestusalus:',
	'proofreadpage_specialpage_label_key' => 'Otsitav:',
	'proofreadpage_specialpage_label_sortascending' => 'Järjesta kasvavalt',
	'proofreadpage_alphabeticalorder' => 'Tähestikuline',
	'proofreadpage_index_listofpages' => 'Lehekülgede loend',
	'proofreadpage_image_message' => 'Link registrilehele',
	'proofreadpage_page_status' => 'Lehekülje olek',
	'proofreadpage_js_attributes' => 'Autor Pealkiri Aasta Väljaandja',
	'proofreadpage_index_attributes' => 'Autor
Pealkiri
Aasta|Väljaandmise aasta
Väljaandja
Päritolu
Pilt|Kaanepilt
Leheküljed||20
Märkused||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|lehekülg|lehekülge}}',
	'proofreadpage_specialpage_legend' => 'Registrilehekülgede otsimine',
	'proofreadpage_specialpage_searcherror' => 'Tõrge otsimootoris',
	'proofreadpage_specialpage_searcherrortext' => 'Otsimootor ei tööta. Palume vabandust.',
	'proofreadpage_source' => 'Allikas',
	'proofreadpage_source_message' => 'Selle teksti aluseks olev skannitud versioon',
	'right-pagequality' => 'Muuta lehekülje tõendusolekut',
	'proofreadpage-section-tools' => 'Tõendusriistad',
	'proofreadpage-group-zoom' => 'Suurendus',
	'proofreadpage-group-other' => 'Muu',
	'proofreadpage-button-toggle-visibility-label' => 'Näita selle lehekülje päist ja jalust või peida need',
	'proofreadpage-button-zoom-out-label' => 'Vähenda',
	'proofreadpage-button-reset-zoom-label' => 'Algsuurus',
	'proofreadpage-button-zoom-in-label' => 'Suurenda',
	'proofreadpage-button-toggle-layout-label' => 'Püst- või rõhtpaigutus',
	'proofreadpage-preferences-showheaders-label' => 'Nimeruumis {{ns:page}} redigeerides näita päise- ja jalusevälja',
	'proofreadpage-preferences-horizontal-layout-label' => 'Nimeruumis {{ns:page}} redigeerides kasuta rõhtpaigutust',
	'proofreadpage-disambiguationspage' => 'Template:täpsustus',
);

/** Basque (euskara)
 * @author An13sa
 * @author Joxemai
 * @author Unai Fdz. de Betoño
 * @author පසිඳු කාවින්ද
 */
$messages['eu'] = array(
	'proofreadpage_image' => 'Irudi',
	'proofreadpage_index' => 'Aurkibidea',
	'proofreadpage_badpage' => 'Formatu Okerra',
	'proofreadpage_indexdupe' => 'Bikoiztutako lotura',
	'proofreadpage_notallowed' => 'Aldaketa ez baimendua',
	'proofreadpage_nextpage' => 'Hurrengo orria',
	'proofreadpage_prevpage' => 'Aurreko orria',
	'proofreadpage_quality0_category' => 'Testurik gabe',
	'proofreadpage_quality2_category' => 'Arazoak dakartza',
	'proofreadpage_quality4_category' => 'Balioztatua.',
	'proofreadpage_quality4_message' => 'Balioztatu egin da orri hau',
	'proofreadpage_specialpage_label_key' => 'Bilatu:',
	'proofreadpage_index_listofpages' => 'Orri zerrenda',
	'proofreadpage_image_message' => 'Aurkibide orrira lotu',
	'proofreadpage_page_status' => 'Orriaren egoera',
	'proofreadpage_js_attributes' => 'Egilea Izenburua Urtea Argitaratzailea',
	'proofreadpage_index_attributes' => 'Egilea
Izenburua
Urtea|Argitalpen urtea
Argitaratzailea
Iturria
Irudia|estalki irudia
Orriak||20
Oharrak||10',
	'proofreadpage_source' => 'Jatorria',
	'proofreadpage-group-other' => 'Bestelakoa',
);

/** Persian (فارسی)
 * @author E THP
 * @author Ebraminio
 * @author Huji
 * @author Ladsgroup
 * @author Mardetanha
 * @author Mjbmr
 * @author Reza1615
 * @author Wayiran
 * @author ZxxZxxZ
 * @author جواد
 */
$messages['fa'] = array(
	'indexpages' => 'فهرست صفحه‌های نمایه',
	'pageswithoutscans' => 'صفحه‌های بدون پویش',
	'proofreadpage_desc' => 'امکان مقایسهٔ آسان متن با نسخهٔ اصلی پویش شده را فراهم می‌آورد',
	'proofreadpage_image' => 'تصویر',
	'proofreadpage_index' => 'اندیس',
	'proofreadpage_index_expected' => 'خطا: وجود شاخص پیش‌بینی‌شده است',
	'proofreadpage_nosuch_index' => 'خطا: چنین شاخصی پیدا نشد.',
	'proofreadpage_nosuch_file' => 'خطا: چنین پرونده‌ای پیدا نشد.',
	'proofreadpage_badpage' => 'فرمت اشتباه',
	'proofreadpage_badpagetext' => 'فرمت صفحه‌ای که قصد ذخیره‌اش را دارید، نادرست است.',
	'proofreadpage_indexdupe' => 'پیوند المثنی',
	'proofreadpage_indexdupetext' => 'صفحه‌ها نمی‌توانند بیش از یک بار در یک صفحهٔ نمایه فهرست شوند.',
	'proofreadpage_nologin' => 'وارد نشده',
	'proofreadpage_nologintext' => 'به منظور تغییر وضعیت نمونه‌خوانی صفحات، باید [[Special:UserLogin|وارد شده باشید]].',
	'proofreadpage_notallowed' => 'تغییر مجاز نیست',
	'proofreadpage_notallowedtext' => 'شما مجاز به تغییر وضعیت نمونه‌خوانی این صفحه نیستید.',
	'proofreadpage_dataconfig_badformatted' => 'اشکال در پیکربندی داده‌ها',
	'proofreadpage_dataconfig_badformattedtext' => 'صفحهٔ [[Mediawiki:Proofreadpage index data config]] به صورت درست در فرمت جیسون تعریف نشده است.',
	'proofreadpage_number_expected' => 'خطا:مقدار عددی مورد انتظار است.',
	'proofreadpage_interval_too_large' => 'خطا:بازهٔ بسیار بزرگ',
	'proofreadpage_invalid_interval' => 'خطا: بازهٔ نامعتبر',
	'proofreadpage_nextpage' => 'برگه بعدی',
	'proofreadpage_prevpage' => 'برگه قبلی',
	'proofreadpage_header' => 'عنوان (noinclude):',
	'proofreadpage_body' => 'متن صفحه (برای گنجانده شدن):',
	'proofreadpage_footer' => 'پانویس (noinclude):',
	'proofreadpage_toggleheaders' => 'تغییر پدیداری بخش‌های noinclude:',
	'proofreadpage_quality0_category' => 'بدون متن',
	'proofreadpage_quality1_category' => 'بازبینی‌نشده',
	'proofreadpage_quality2_category' => 'مشکل‌دار',
	'proofreadpage_quality3_category' => 'بازبینی‌شده',
	'proofreadpage_quality4_category' => 'تاییدشده',
	'proofreadpage_quality0_message' => 'این صفحه نیازی به نمونه‌خوانی شدن ندارد',
	'proofreadpage_quality1_message' => 'این صفحه بازخوانی نشده است',
	'proofreadpage_quality2_message' => 'هنگام بازخوانی این صفحه مشکلی وجود داشت',
	'proofreadpage_quality3_message' => 'این صفحه نمونه‌خوانی شده است',
	'proofreadpage_quality4_message' => 'این صفحه اعتباردهی شده است',
	'proofreadpage_index_status' => 'وضعیت فهرست',
	'proofreadpage_index_size' => 'تعداد صفحه‌ها',
	'proofreadpage_specialpage_label_orderby' => 'ترتیب بر اساس:',
	'proofreadpage_specialpage_label_key' => 'جستجو:',
	'proofreadpage_specialpage_label_sortascending' => 'مرتب‌سازی صعودی',
	'proofreadpage_alphabeticalorder' => 'به ترتیب حروف الفبا',
	'proofreadpage_index_listofpages' => 'فهرست برگه‌ها',
	'proofreadpage_image_message' => 'پیوند به صفحهٔ اندیس',
	'proofreadpage_page_status' => 'وضعیت صفحه',
	'proofreadpage_js_attributes' => 'نویسنده عنوان سال ناشر',
	'proofreadpage_index_attributes' => 'نویسنده
عنوان
سال|سال انتشار
ناشر
منبع
تصویر|تصویر روی جلد
صفحه||20
ملاحظات||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|برگه|برگه}}',
	'proofreadpage_specialpage_legend' => 'جستجو در صفحه‌های شاخص',
	'proofreadpage_specialpage_searcherror' => 'خطا در موتور جستجو',
	'proofreadpage_specialpage_searcherrortext' => 'موتور جستجو کار نمی کند. پوزش می طلبیم.',
	'proofreadpage_source' => 'منبع',
	'proofreadpage_source_message' => 'برای ایجاد این متن از ویرایش پویش‌شده (اسکن‌شده) استفاده شده',
	'right-pagequality' => 'تغییر پرچم کیفیت صفحه',
	'proofreadpage-section-tools' => 'ابزارهای ویرایش',
	'proofreadpage-group-zoom' => 'اندازه‌نمایی',
	'proofreadpage-group-other' => 'دیگر',
	'proofreadpage-button-toggle-visibility-label' => 'نمایش/نهفتن سرصفحه و پاورقی این صفحه',
	'proofreadpage-button-zoom-out-label' => 'کوچک‌نمایی',
	'proofreadpage-button-reset-zoom-label' => 'بازنشانی اندازه‌نمایی',
	'proofreadpage-button-zoom-in-label' => 'بزرگ‌نمایی',
	'proofreadpage-button-toggle-layout-label' => 'طرح عمودی/افقی',
	'proofreadpage-preferences-showheaders-label' => 'در زمان ویرایش در فضای نام {{ns:page}} سرصفحه و پانویس زمینه را نشان بده',
	'proofreadpage-preferences-horizontal-layout-label' => 'استفاده از چیدمان افقی در هنگام ویرایش در فضای نام {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'فراداده کتاب از {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'فراداده کتاب‌ها مدیریت‌شده توسط ابزار بازبینی صفحات.',
	'proofreadpage-indexoai-error-schemanotfound' => 'طرح کلی یافت نشد',
	'proofreadpage-indexoai-error-schemanotfound-text' => ' $1  طرح یافت نشد.',
);

/** Finnish (suomi)
 * @author Agony
 * @author Beluga
 * @author Cimon Avaro
 * @author Crt
 * @author Harriv
 * @author Jaakonam
 * @author Kulmalukko
 * @author Nike
 * @author Olli
 * @author Str4nd
 * @author VezonThunder
 * @author ZeiP
 */
$messages['fi'] = array(
	'indexpages' => 'Luettelo hakemistosivuista',
	'pageswithoutscans' => 'Sivut ilman skannauksia',
	'proofreadpage_desc' => 'Mahdollistaa helpon vertailun tekstin ja alkuperäisen skannauksen välillä.',
	'proofreadpage_image' => 'Kuva',
	'proofreadpage_index' => 'Hakemisto',
	'proofreadpage_index_expected' => 'Virhe: täsmennysosiota odotetaan',
	'proofreadpage_nosuch_index' => 'Virhe: Kyseistä indeksiä ei ole',
	'proofreadpage_nosuch_file' => 'Virhe: tiedostoa ei löydy',
	'proofreadpage_badpage' => 'Väärä muoto',
	'proofreadpage_badpagetext' => 'Sivu, jota yritit tallentaa on virheellisessä muodossa.',
	'proofreadpage_indexdupe' => 'Kaksoiskappalelinkki',
	'proofreadpage_indexdupetext' => 'Sivuja ei voida listata useammin kuin kerran hakemistosivulla.',
	'proofreadpage_nologin' => 'Et ole kirjautunut sisään',
	'proofreadpage_nologintext' => 'Sinun täytyy olla [[Special:UserLogin|kirjautunut sisään]] muuttaaksesi sivun oikolukutilaa.',
	'proofreadpage_notallowed' => 'Muutos ei ole sallittu',
	'proofreadpage_notallowedtext' => 'Sinulla ei ole oikeuksia muuttaa tämän sivun oikoluku-tilaa.',
	'proofreadpage_number_expected' => 'Virhe: odotettiin numeerista arvoa',
	'proofreadpage_interval_too_large' => 'Virhe: Väli liian suuri',
	'proofreadpage_invalid_interval' => 'Virhe: Väli ei toimi',
	'proofreadpage_nextpage' => 'Seuraava sivu',
	'proofreadpage_prevpage' => 'Edellinen sivu',
	'proofreadpage_header' => 'Ylätunniste (ei sisällytetä):',
	'proofreadpage_body' => 'Sivun runko (sisällytetään):',
	'proofreadpage_footer' => 'Alatunniste (ei sisällytetä):',
	'proofreadpage_toggleheaders' => 'vaihtaa sisällyttämättömien osioiden näkyvyyttä',
	'proofreadpage_quality0_category' => 'Ilman tekstiä',
	'proofreadpage_quality1_category' => 'Oikolukematta',
	'proofreadpage_quality2_category' => 'Ongelmallinen',
	'proofreadpage_quality3_category' => 'Oikoluettu',
	'proofreadpage_quality4_category' => 'Hyväksytty',
	'proofreadpage_quality0_message' => 'Tätä sivua ei tarvitse oikolukea',
	'proofreadpage_quality1_message' => 'Tätä sivua ei ole oikoluettu',
	'proofreadpage_quality2_message' => 'Tämän sivun oikoluvussa oli ongelmia',
	'proofreadpage_quality3_message' => 'Tämä sivu on oikoluettu',
	'proofreadpage_quality4_message' => 'Tämä sivu on vahvistettu',
	'proofreadpage_index_size' => 'Sivumäärä',
	'proofreadpage_specialpage_label_orderby' => 'Järjestä:',
	'proofreadpage_specialpage_label_key' => 'Etsi:',
	'proofreadpage_specialpage_label_sortascending' => 'Lajittele nousevassa järjestyksessä',
	'proofreadpage_alphabeticalorder' => 'Aakkosjärjestys',
	'proofreadpage_index_listofpages' => 'Sivuluettelo',
	'proofreadpage_image_message' => 'Linkki hakemistosivuun',
	'proofreadpage_page_status' => 'Sivun tila',
	'proofreadpage_js_attributes' => 'Tekijä Nimike Vuosi Julkaisija',
	'proofreadpage_index_attributes' => 'Tekijä
Nimike
Vuosi|Julkaisuvuosi
Julkaisija
Lähde
Kuva|Kansikuva
Sivuja||20
Huomautuksia||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|sivu|sivua}}',
	'proofreadpage_specialpage_legend' => 'Hae indeksisivuilta',
	'proofreadpage_specialpage_searcherror' => 'Hakukone on rikki',
	'proofreadpage_specialpage_searcherrortext' => 'Hakukone ei toimi. Pahoittelemme häiriötä.',
	'proofreadpage_source' => 'Lähde',
	'proofreadpage_source_message' => 'Skannattua versiota on käytetty tämän tekstin muodostamiseen',
	'right-pagequality' => 'Muuttaa sivun laatumerkintää',
	'proofreadpage-section-tools' => 'Oikolukutyökalut',
	'proofreadpage-group-zoom' => 'Zoomaus',
	'proofreadpage-group-other' => 'Muu',
	'proofreadpage-button-toggle-visibility-label' => 'Näytä/piilota tämän sivun yläosa ja alaosa',
	'proofreadpage-button-zoom-out-label' => 'Loitonna',
	'proofreadpage-button-reset-zoom-label' => 'Alkuperäinen koko',
	'proofreadpage-button-zoom-in-label' => 'Lähennä',
	'proofreadpage-button-toggle-layout-label' => 'Pystysuora/vaakasuora ulkoasu',
);

/** French (français)
 * @author Brunoperel
 * @author Crochet.david
 * @author Dereckson
 * @author Gomoko
 * @author Grondin
 * @author Hello71
 * @author IAlex
 * @author Jean-Frédéric
 * @author John Vandenberg
 * @author Seb35
 * @author Tpt
 * @author Urhixidur
 * @author VIGNERON
 * @author Verdy p
 * @author Wyz
 */
$messages['fr'] = array(
	'indexpages' => "Liste des pages d'index",
	'pageswithoutscans' => 'Pages sans fac-similés',
	'proofreadpage_desc' => 'Permet une comparaison facile entre le texte et sa numérisation originale',
	'proofreadpage_image' => 'Fichier',
	'proofreadpage_index' => 'Livre',
	'proofreadpage_index_expected' => 'Erreur : un index est attendu',
	'proofreadpage_nosuch_index' => "Erreur : l'index n'a pas été trouvé",
	'proofreadpage_nosuch_file' => "Erreur : le fichier n'a pas été trouvé",
	'proofreadpage_badpage' => 'Mauvais format',
	'proofreadpage_badpagetext' => 'Le format de la page que vous essayez de publier est incorrect.',
	'proofreadpage_indexdupe' => 'Lien en double',
	'proofreadpage_indexdupetext' => "Les pages ne peuvent pas être listées plus d'une fois sur une page d'index.",
	'proofreadpage_nologin' => 'Non connecté',
	'proofreadpage_nologintext' => 'Vous devez être [[Special:UserLogin|connecté]] pour modifier le statut de correction des pages.',
	'proofreadpage_notallowed' => 'Modification non autorisée',
	'proofreadpage_notallowedtext' => "Vous n'êtes pas autorisé à modifier le statut de correction de cette page.",
	'proofreadpage_dataconfig_badformatted' => 'Bogue dans la configuration des données',
	'proofreadpage_dataconfig_badformattedtext' => "La page [[Mediawiki:Proofreadpage index data config]] n'est pas correctement mise en forme JSON.",
	'proofreadpage_number_expected' => 'Erreur : une valeur numérique est attendue',
	'proofreadpage_interval_too_large' => 'Erreur : intervalle trop grand',
	'proofreadpage_invalid_interval' => 'Erreur : intervalle invalide',
	'proofreadpage_nextpage' => 'Page suivante',
	'proofreadpage_prevpage' => 'Page précédente',
	'proofreadpage_header' => 'En-tête (noinclude) :',
	'proofreadpage_body' => 'Contenu (par transclusion) :',
	'proofreadpage_footer' => 'Pied de page (noinclude) :',
	'proofreadpage_toggleheaders' => 'masquer/montrer les sections noinclude',
	'proofreadpage_quality0_category' => 'Sans texte',
	'proofreadpage_quality1_category' => 'Non corrigée',
	'proofreadpage_quality2_category' => 'Problématique',
	'proofreadpage_quality3_category' => 'Corrigée',
	'proofreadpage_quality4_category' => 'Validée',
	'proofreadpage_quality0_message' => 'Cette page n’est pas destinée à être corrigée.',
	'proofreadpage_quality1_message' => 'Cette page n’a pas encore été corrigée.',
	'proofreadpage_quality2_message' => 'Cette page n’a pas pu être corrigée, à cause d’un problème décrit en page de discussion.',
	'proofreadpage_quality3_message' => 'Cette page a été corrigée et est conforme au fac-similé.',
	'proofreadpage_quality4_message' => 'Cette page a été validée par deux contributeurs.',
	'proofreadpage_index_status' => 'État de l’index',
	'proofreadpage_index_size' => 'Nombre de pages',
	'proofreadpage_specialpage_label_orderby' => 'Trier par :',
	'proofreadpage_specialpage_label_key' => 'Recherche :',
	'proofreadpage_specialpage_label_sortascending' => 'Tri croissant.',
	'proofreadpage_alphabeticalorder' => 'Ordre alphabétique',
	'proofreadpage_index_listofpages' => 'Liste des pages',
	'proofreadpage_image_message' => 'Lien vers la page d’index',
	'proofreadpage_page_status' => 'État de la page',
	'proofreadpage_js_attributes' => 'Auteur Titre Année Éditeur',
	'proofreadpage_index_attributes' => 'Auteur
Titre
Année|Année de publication
Éditeur
Source
Image|Image en couverture
Pages||20
Remarques||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|page|pages}}',
	'proofreadpage_specialpage_legend' => 'Rechercher dans les pages d’index',
	'proofreadpage_specialpage_searcherror' => 'Erreur dans le moteur de recherche',
	'proofreadpage_specialpage_searcherrortext' => 'Le moteur de recherche ne fonctionne pas. Désolé pour les inconvénients.',
	'proofreadpage_source' => 'Source',
	'proofreadpage_source_message' => 'Édition numérisée dont est issu ce texte',
	'right-pagequality' => 'Modifier le drapeau de qualité de la page',
	'proofreadpage-section-tools' => 'Outils d’aide à la relecture',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Autre',
	'proofreadpage-button-toggle-visibility-label' => 'Afficher/cacher l’en-tête et le pied de page de cette page',
	'proofreadpage-button-zoom-out-label' => 'Dézoomer',
	'proofreadpage-button-reset-zoom-label' => 'Réinitialiser le zoom',
	'proofreadpage-button-zoom-in-label' => 'Zoomer',
	'proofreadpage-button-toggle-layout-label' => 'Disposition verticale/horizontale',
	'proofreadpage-preferences-showheaders-label' => "Afficher des champs d'en-tête et de pied de page lors de l'édition dans l'espace de nommage {{ns:page}}",
	'proofreadpage-preferences-horizontal-layout-label' => "Utiliser une disposition horizontale lors d'une modification dans l'espace de noms {{ns:page}}",
	'proofreadpage-indexoai-repositoryName' => 'Métadonnées des livres de {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Métadonnées des livres gérés par ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Schéma introuvable',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Le schéma $1 est introuvable.',
	'proofreadpage-disambiguationspage' => 'Template:disambig',
);

/** Franco-Provençal (arpetan)
 * @author ChrisPtDe
 */
$messages['frp'] = array(
	'indexpages' => 'Lista de les pâges d’endèxe',
	'pageswithoutscans' => 'Pâges sen numerisacions',
	'proofreadpage_desc' => 'Pèrmèt una comparèson ésiê entre lo tèxto et sa numerisacion originâla.',
	'proofreadpage_image' => 'Émâge',
	'proofreadpage_index' => 'Endèxe',
	'proofreadpage_index_expected' => 'Èrror : un endèxe est atendu',
	'proofreadpage_nosuch_index' => 'Èrror : l’endèxe at pas étâ trovâ',
	'proofreadpage_nosuch_file' => 'Èrror : lo fichiér at pas étâ trovâ',
	'proofreadpage_badpage' => 'Crouyo format',
	'proofreadpage_badpagetext' => 'Lo format de la pâge que vos tâchiéd de sôvar est fôx.',
	'proofreadpage_indexdupe' => 'Lim en doblo',
	'proofreadpage_indexdupetext' => 'Les pâges pôvont pas étre listâs més d’un côp sur una pâge d’endèxe.',
	'proofreadpage_nologin' => 'Pas branchiê',
	'proofreadpage_nologintext' => 'Vos dête étre [[Special:UserLogin|branchiê]] por changiér lo statut de corrèccion de les pâges.',
	'proofreadpage_notallowed' => 'Changement pas ôtorisâ',
	'proofreadpage_notallowedtext' => 'Vos éte pas ôtorisâ a changiér lo statut de corrèccion de ceta pâge.',
	'proofreadpage_number_expected' => 'Èrror : una valor numerica est atendua',
	'proofreadpage_interval_too_large' => 'Èrror : entèrvalo trop grant',
	'proofreadpage_invalid_interval' => 'Èrror : entèrvalo envalido',
	'proofreadpage_nextpage' => 'Pâge aprés',
	'proofreadpage_prevpage' => 'Pâge devant',
	'proofreadpage_header' => 'En-téta (noinclude) :',
	'proofreadpage_body' => 'Contegnu (per transcllusion) :',
	'proofreadpage_footer' => 'Pied de pâge (noinclude) :',
	'proofreadpage_toggleheaders' => 'fâre vêre / cachiér les sèccions noinclude',
	'proofreadpage_quality0_category' => 'Sen tèxto',
	'proofreadpage_quality1_category' => 'Pas corregiê',
	'proofreadpage_quality2_category' => 'Pas de sûr',
	'proofreadpage_quality3_category' => 'Corregiê',
	'proofreadpage_quality4_category' => 'Validâ',
	'proofreadpage_quality0_message' => 'Ceta pâge at pas fôta d’étre corregiê.',
	'proofreadpage_quality1_message' => 'Ceta pâge at p’oncor étâ corregiê.',
	'proofreadpage_quality2_message' => 'Y at avu un problèmo pendent la corrèccion de ceta pâge.',
	'proofreadpage_quality3_message' => 'Ceta pâge at étâ corregiê.',
	'proofreadpage_quality4_message' => 'Ceta pâge at étâ validâ.',
	'proofreadpage_index_listofpages' => 'Lista de les pâges',
	'proofreadpage_image_message' => 'Lim de vers la pâge d’endèxe',
	'proofreadpage_page_status' => 'Ètat de la pâge',
	'proofreadpage_js_attributes' => 'Ôtor Titro An Èditor',
	'proofreadpage_index_attributes' => 'Ôtor
Titro
An|An de publecacion
Èditor
Sôrsa
Émâge|Émâge en cuvèrta
Pâges||20
Comentèros||10',
	'proofreadpage_pages' => '$2 pâge{{PLURAL:$1||s}}',
	'proofreadpage_specialpage_legend' => 'Rechèrchiér dens les pâges d’endèxe',
	'proofreadpage_source' => 'Sôrsa',
	'proofreadpage_source_message' => 'Èdicion scanâ que vint de cél tèxto',
	'right-pagequality' => 'Changiér lo segnalement de qualitât de la pâge',
	'proofreadpage-section-tools' => 'Outils d’éde a la rèvision',
	'proofreadpage-group-zoom' => 'Zoome',
	'proofreadpage-group-other' => 'Ôtra',
	'proofreadpage-button-toggle-visibility-label' => 'Fâre vêre / cachiér l’en-téta et lo pied de pâge de ceta pâge',
	'proofreadpage-button-zoom-out-label' => 'Rèduire',
	'proofreadpage-button-reset-zoom-label' => 'Tornar inicialisar lo zoome',
	'proofreadpage-button-zoom-in-label' => 'Agrantir',
	'proofreadpage-button-toggle-layout-label' => 'Misa en pâge drêta / plana',
);

/** Northern Frisian (Nordfriisk)
 * @author Murma174
 */
$messages['frr'] = array(
	'indexpages' => 'Index sidjen',
	'pageswithoutscans' => 'Sidjen saner scans',
	'proofreadpage_desc' => 'Diarmä könst dü di tekst an di scan mäenööder ferglik',
	'proofreadpage_image' => 'Scan',
	'proofreadpage_index' => 'Index',
	'proofreadpage_index_expected' => 'Ferkiard: diar hiart en Index hen',
	'proofreadpage_nosuch_index' => 'Ferkiard: son Index jaft at ei',
	'proofreadpage_nosuch_file' => 'Ferkiard: son Datei jaft at ei',
	'proofreadpage_badpage' => 'Ferkiard formaat',
	'proofreadpage_badpagetext' => 'Det formaat faan det sidj, wat dü seekre wel, as ferkiard.',
	'proofreadpage_indexdupe' => 'Dobelt ferwisang',
	'proofreadpage_indexdupetext' => 'Sidjen kön man iansis üüb en Index sidj iindraanj wurd.',
	'proofreadpage_nologin' => 'Ei uunmeldet',
	'proofreadpage_nologintext' => 'Dü skel [[Special:UserLogin|uunmeldet]] wees, ööders könst dü di staatus faan korektuuren ei feranre.',
	'proofreadpage_notallowed' => 'Feranrang ei mögelk',
	'proofreadpage_notallowedtext' => 'Dü mutst di korektuurstaatus faan detdiar sidj ei feranre.',
	'proofreadpage_dataconfig_badformatted' => 'Diar as wat skiaf lepen mä a dooten konfiguratsjuun',
	'proofreadpage_dataconfig_badformattedtext' => 'Det sidj [[Mediawiki:Proofreadpage index data config]] as ei rocht uun JSON formatiaret.',
	'proofreadpage_number_expected' => 'Ferkiard: Diar hiart en taal hen',
	'proofreadpage_interval_too_large' => 'Ferkiard: interwal tu grat',
	'proofreadpage_invalid_interval' => 'Ferkiard: interwal ei mögelk',
	'proofreadpage_nextpage' => 'Naist sidj',
	'proofreadpage_prevpage' => 'Leetst sidj (turag)',
	'proofreadpage_header' => 'Hoodrä (woort ei iinsaat)',
	'proofreadpage_body' => 'Tekstdial (woort iinsaat)',
	'proofreadpage_footer' => 'Futrä (woort ei iinsaat)',
	'proofreadpage_toggleheaders' => 'Dialen, diar ei iinsaat wurd, uunwise of ei uunwise',
	'proofreadpage_quality0_category' => 'Saner tekst',
	'proofreadpage_quality1_category' => 'Ei efterluket',
	'proofreadpage_quality2_category' => 'Diar stemet wat ei',
	'proofreadpage_quality3_category' => 'Efterluket',
	'proofreadpage_quality4_category' => 'Klaar',
	'proofreadpage_quality0_message' => 'Detdiar sidj säär ei efterluket wurd.',
	'proofreadpage_quality1_message' => 'Detdiar sidj as noch ei efterluket wurden.',
	'proofreadpage_quality2_message' => "Bi't efterlukin as noch ei ales klaar wurden.",
	'proofreadpage_quality3_message' => 'Detdiar sidj as efterluket wurden.',
	'proofreadpage_quality4_message' => 'Detdiar sidj as klaar.',
	'proofreadpage_index_status' => 'Indeks staatus',
	'proofreadpage_index_size' => 'Taal faan sidjen',
	'proofreadpage_specialpage_label_orderby' => 'Sortiare efter:',
	'proofreadpage_specialpage_label_key' => 'Schük:',
	'proofreadpage_specialpage_label_sortascending' => 'Sortiare faan onern tu boowen',
	'proofreadpage_alphabeticalorder' => "Efter't alfabeet sortiaret",
	'proofreadpage_index_listofpages' => 'Sidjen',
	'proofreadpage_image_message' => "Ferwisang tu't Index sidj",
	'proofreadpage_page_status' => "Staatus faan't sidj",
	'proofreadpage_js_attributes' => 'Skriiwer Tiitel Juar Ferlach',
	'proofreadpage_index_attributes' => 'Skriiwer
Tiitel
Juar|Ütjkimen
Ferlach
Kwel
Bil|Tiitelbil
Sidjen||20
Komentaaren||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|sidj|sidjen}}',
	'proofreadpage_specialpage_legend' => 'Schük üüb index-sidjen',
	'proofreadpage_specialpage_searcherror' => 'Mä det schükmaskiin as wat skiaf gingen',
	'proofreadpage_specialpage_searcherrortext' => 'Det schükmaskiin wal ei rocht. At dää mi iarag.',
	'proofreadpage_source' => 'Kwel',
	'proofreadpage_source_message' => 'För didiar tekst as en scan brükt wurden.',
	'right-pagequality' => 'Wäärs faan det sidj feranre',
	'proofreadpage-section-tools' => "Halep bi't korektuur",
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Ööders wat',
	'proofreadpage-button-toggle-visibility-label' => 'Hoodrä an futrä uunwise of ei uunwise',
	'proofreadpage-button-zoom-out-label' => 'Letjer maage',
	'proofreadpage-button-reset-zoom-label' => 'Normool grate',
	'proofreadpage-button-zoom-in-label' => 'Grater maage',
	'proofreadpage-button-toggle-layout-label' => 'Loongs an swäärs ütjracht',
	'proofreadpage-preferences-showheaders-label' => "Hoodrä an futrä bi't bewerkin faan sidjen uun a nöömrüm {{ns:page}} uunwise",
	'proofreadpage-preferences-horizontal-layout-label' => "Bi't bewerkin faan sidjen uun a nöömrüm {{ns:page}} en waagrocht layout brük",
	'proofreadpage-indexoai-repositoryName' => 'Metadooten för buken faan {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadooten för buken, diar faan „ProofreadPage“ ferwaltet wurd.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Skemsidj ei fünjen',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Det skemsidj „$1“ as ei fünjen wurden.',
	'proofreadpage-disambiguationspage' => 'Template:Muardüüdag artiikel',
);

/** Friulian (furlan)
 * @author Klenje
 */
$messages['fur'] = array(
	'proofreadpage_index_listofpages' => 'Liste des pagjinis',
);

/** Western Frisian (Frysk)
 * @author Snakesteuben
 */
$messages['fy'] = array(
	'proofreadpage_nextpage' => 'Folgjende side',
);

/** Irish (Gaeilge)
 * @author Alison
 * @author පසිඳු කාවින්ද
 */
$messages['ga'] = array(
	'proofreadpage_specialpage_label_key' => 'Cuardaigh:',
	'proofreadpage_index_attributes' => 'Údar
Teideal
Blian|Blian foilseacháin
Foilsitheoir
Foinse
Íomhá|Íomhá clúdaigh
Leathanaigh||20
Nótaí',
	'proofreadpage_source' => 'Foinse',
	'proofreadpage-group-other' => 'Eile',
);

/** Galician (galego)
 * @author Alma
 * @author Toliño
 * @author Xosé
 */
$messages['gl'] = array(
	'indexpages' => 'Lista de páxinas índice',
	'pageswithoutscans' => 'Páxinas sen exames',
	'proofreadpage_desc' => 'Permite a comparación sinxela do texto coa dixitalización orixinal',
	'proofreadpage_image' => 'Imaxe',
	'proofreadpage_index' => 'Índice',
	'proofreadpage_index_expected' => 'Erro: Agardábase un índice',
	'proofreadpage_nosuch_index' => 'Erro: Non existe tal índice',
	'proofreadpage_nosuch_file' => 'Erro: Non existe tal ficheiro',
	'proofreadpage_badpage' => 'Formato incorrecto',
	'proofreadpage_badpagetext' => 'O formato da páxina que intentou gardar é incorrecto.',
	'proofreadpage_indexdupe' => 'Ligazón duplicada',
	'proofreadpage_indexdupetext' => 'Non se poden listar as páxinas máis dunha vez nunha páxina índice.',
	'proofreadpage_nologin' => 'Non accedeu ao sistema',
	'proofreadpage_nologintext' => 'Debe [[Special:UserLogin|acceder ao sistema]] para modificar o estado de corrección das páxinas.',
	'proofreadpage_notallowed' => 'Cambio non autorizado',
	'proofreadpage_notallowedtext' => 'Non ten os permisos necesarios para cambiar o estado de corrección desta páxina.',
	'proofreadpage_dataconfig_badformatted' => 'Erro na configuración dos datos',
	'proofreadpage_dataconfig_badformattedtext' => 'A páxina "[[Mediawiki:Proofreadpage index data config]]" non ten un formato JSON correcto.',
	'proofreadpage_number_expected' => 'Erro: Agardábase un valor numérico',
	'proofreadpage_interval_too_large' => 'Erro: Intervalo moi grande',
	'proofreadpage_invalid_interval' => 'Erro: Intervalo inválido',
	'proofreadpage_nextpage' => 'Páxina seguinte',
	'proofreadpage_prevpage' => 'Páxina anterior',
	'proofreadpage_header' => 'Cabeceira (noinclude):',
	'proofreadpage_body' => 'Corpo da páxina (para ser transcluído)',
	'proofreadpage_footer' => 'Pé de páxina (noinclude):',
	'proofreadpage_toggleheaders' => 'alternar a visibilidade das seccións "noinclude"',
	'proofreadpage_quality0_category' => 'Sen texto',
	'proofreadpage_quality1_category' => 'Non corrixido',
	'proofreadpage_quality2_category' => 'Problemático',
	'proofreadpage_quality3_category' => 'Corrixido',
	'proofreadpage_quality4_category' => 'Validado',
	'proofreadpage_quality0_message' => 'Esta páxina non necesita corrección',
	'proofreadpage_quality1_message' => 'Esta páxina non foi corrixida',
	'proofreadpage_quality2_message' => 'Houbo un problema ao corrixir esta páxina',
	'proofreadpage_quality3_message' => 'Esta páxina foi corrixida',
	'proofreadpage_quality4_message' => 'Esta páxina foi validada',
	'proofreadpage_index_status' => 'Estado do índice',
	'proofreadpage_index_size' => 'Número de páxinas',
	'proofreadpage_specialpage_label_orderby' => 'Ordenar por:',
	'proofreadpage_specialpage_label_key' => 'Procurar:',
	'proofreadpage_specialpage_label_sortascending' => 'Orde ascendente',
	'proofreadpage_alphabeticalorder' => 'Orde alfabética',
	'proofreadpage_index_listofpages' => 'Lista de páxinas',
	'proofreadpage_image_message' => 'Ligazón á páxina índice',
	'proofreadpage_page_status' => 'Estado da páxina',
	'proofreadpage_js_attributes' => 'Autor Título Ano Editor',
	'proofreadpage_index_attributes' => 'Autor
Título
Ano|Ano de publicación
Editor
Orixe
Imaxe|Imaxe da cuberta
Páxinas||20
Comentarios||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|páxina|páxinas}}',
	'proofreadpage_specialpage_legend' => 'Procurar nas páxinas de índice',
	'proofreadpage_specialpage_searcherror' => 'Erro no motor de procuras',
	'proofreadpage_specialpage_searcherrortext' => 'O motor de procuras non funciona. Desculpen as molestias.',
	'proofreadpage_source' => 'Orixe',
	'proofreadpage_source_message' => 'Edición dixitalizada utilizada para establecer este texto',
	'right-pagequality' => 'Modificar a marca de calidade da páxina',
	'proofreadpage-section-tools' => 'Ferramentas de revisión',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Outro',
	'proofreadpage-button-toggle-visibility-label' => 'Mostrar ou agochar a cabeceira e pé desta páxina',
	'proofreadpage-button-zoom-out-label' => 'Reducir',
	'proofreadpage-button-reset-zoom-label' => 'Restablecer o zoom',
	'proofreadpage-button-zoom-in-label' => 'Ampliar',
	'proofreadpage-button-toggle-layout-label' => 'Disposición vertical ou horizontal',
	'proofreadpage-preferences-showheaders-label' => 'Mostrar os campos da cabeceira e do pé de páxina ao editar no espazo de nomes {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Utilizar a disposición horizontal ao editar no espazo de nomes {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Metadatos de libros de {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadatos de libros xestionados por ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Non se atopou o esquema',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Non se atopou o esquema "$1".',
	'proofreadpage-disambiguationspage' => 'Template:Homónimos',
);

/** Ancient Greek (Ἀρχαία ἑλληνικὴ)
 * @author Crazymadlover
 * @author Omnipaedista
 */
$messages['grc'] = array(
	'proofreadpage_image' => 'εἰκών',
	'proofreadpage_index' => 'Δείκτης',
	'proofreadpage_nextpage' => 'ἡ δέλτος ἡ ἑπομένη',
	'proofreadpage_prevpage' => 'ἡ δέλτος ἡ προτέρα',
	'proofreadpage_quality1_category' => 'Μὴ ἠλεγμένη',
	'proofreadpage_quality2_category' => 'Προβληματική',
	'proofreadpage_index_listofpages' => 'Καταλογὴ δέλτων',
	'proofreadpage_page_status' => 'Κατάστασις δέλτου',
	'proofreadpage_pages' => '{{PLURAL:$1|δέλτος|δέλτοι}}', # Fuzzy
	'proofreadpage_source' => 'Πηγή',
);

/** Swiss German (Alemannisch)
 * @author Als-Chlämens
 * @author Als-Holder
 * @author J. 'mach' wust
 */
$messages['gsw'] = array(
	'indexpages' => 'Lischte vu Indexsyte',
	'pageswithoutscans' => 'Syte ohni Scans',
	'proofreadpage_desc' => 'Macht e eifache Verglyych vu Täxt mit em Originalscan megli',
	'proofreadpage_image' => 'Scan',
	'proofreadpage_index' => 'Index',
	'proofreadpage_index_expected' => 'Fähler: Index erwartet',
	'proofreadpage_nosuch_index' => 'Fähler: Kei sonige Index',
	'proofreadpage_nosuch_file' => 'Fähler: Kei sonigi Datei',
	'proofreadpage_badpage' => 'Falsch Format',
	'proofreadpage_badpagetext' => 'S Format vu dr Syte, wu du versuecht hesch z spychere, isch falsch.',
	'proofreadpage_indexdupe' => 'Link dupliziere',
	'proofreadpage_indexdupetext' => 'Syte chenne nit meh wie eimol ufglischtet wäre uf ere Indexsyte',
	'proofreadpage_nologin' => 'Nit aagmäldet',
	'proofreadpage_nologintext' => 'Du muesch [[Special:UserLogin|aagmäldet syy]] go dr Korrekturläsigs-Status vu Syte ändere.',
	'proofreadpage_notallowed' => 'Änderig nit erlaubt',
	'proofreadpage_notallowedtext' => 'Du derfsch dr Korrektur-Läsigs-Status vu däre Syte nit ändere.',
	'proofreadpage_number_expected' => 'Fähler: Numerische Wärt erwartet',
	'proofreadpage_interval_too_large' => 'Fähler: Intervall z groß',
	'proofreadpage_invalid_interval' => 'Fähler: nit giltig Intervall',
	'proofreadpage_nextpage' => 'Negschti Syte',
	'proofreadpage_prevpage' => 'Vorderi Syte',
	'proofreadpage_header' => 'Chopfzyylete (noinclude):',
	'proofreadpage_body' => 'Täxtlyyb (Transklusion):',
	'proofreadpage_footer' => 'Fueßzyylete (noinclude):',
	'proofreadpage_toggleheaders' => 'noinclude-Abschnit yy-/uusblände',
	'proofreadpage_quality0_category' => 'Ohni Tekscht',
	'proofreadpage_quality1_category' => 'Nit korrigiert',
	'proofreadpage_quality2_category' => 'Korrekturprobläm',
	'proofreadpage_quality3_category' => 'Korrigiert',
	'proofreadpage_quality4_category' => 'Fertig',
	'proofreadpage_quality0_message' => 'Die Syte brucht nit Korrektur gläse wäre.',
	'proofreadpage_quality1_message' => 'Die Syte isch nit Korrektur gläse wore',
	'proofreadpage_quality2_message' => 'S het e Probläm gee bim Korrektur läse vu däre Syte',
	'proofreadpage_quality3_message' => 'Die Syte isch Korrektur gläse wore',
	'proofreadpage_quality4_message' => 'Die Syte isch validiert wore',
	'proofreadpage_index_listofpages' => 'Sytelischt',
	'proofreadpage_image_message' => 'Link zue dr Indexsyte',
	'proofreadpage_page_status' => 'Sytestatus',
	'proofreadpage_js_attributes' => 'Autor Titel Johr Verlag',
	'proofreadpage_index_attributes' => 'Autor
Titel
Johr|Johr vu dr Vereffetlichung
Verlag
Quälle
Bild|Titelbild
Syte||20
Aamerkige||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|Syte|Syte}}',
	'proofreadpage_specialpage_legend' => 'Indexsyte dursueche',
	'proofreadpage_source' => 'Quälle',
	'proofreadpage_source_message' => 'Gscannti Uusgab, wu brucht wird go dää Text erarbeite',
	'right-pagequality' => 'Qualitetsmarkierig vu dr Syte ändere',
	'proofreadpage-section-tools' => 'Hilfsmittel zum Korrekturläse',
	'proofreadpage-group-zoom' => 'Zoome',
	'proofreadpage-group-other' => 'Anders',
	'proofreadpage-button-toggle-visibility-label' => 'Chopf- un Fuesszyyle vo derre Syte yy-/ussblände',
	'proofreadpage-button-zoom-out-label' => 'Chleiner mache',
	'proofreadpage-button-reset-zoom-label' => 'Orginalgrößi',
	'proofreadpage-button-zoom-in-label' => 'Dryy suume',
	'proofreadpage-button-toggle-layout-label' => 'Vertikali/horizontali Ussrichtig',
	'proofreadpage-preferences-showheaders-label' => 'Bim Bearbeite vo Syte im Sytenamensruum d Fälder für d Chopf- un Fuesszyyle aazeige.', # Fuzzy
);

/** Gujarati (ગુજરાતી)
 * @author Dsvyas
 * @author Sushant savla
 */
$messages['gu'] = array(
	'indexpages' => 'સૂચિ પૃષ્ઠોની યાદી',
	'pageswithoutscans' => 'સ્કેન વગરના પાના',
	'proofreadpage_desc' => 'મૂળ સ્કેન સાથે સરળ સરખામણીની રજા આપો',
	'proofreadpage_image' => 'ચિત્ર',
	'proofreadpage_index' => 'અનુક્રમણિકા',
	'proofreadpage_index_expected' => 'ત્રુટિ:સૂચિ અપેક્ષિત',
	'proofreadpage_nosuch_index' => 'ત્રુટિ:આવી કોઈ સૂચિ નથી',
	'proofreadpage_nosuch_file' => 'ત્રુટિ:આવી કોઇ ફાઇલ નથી',
	'proofreadpage_badpage' => 'ખોટી શૈલી',
	'proofreadpage_badpagetext' => 'તમે જે શૈલીમાં પાનું સાચવવા માંગો છો તે અયોગ્ય છે.',
	'proofreadpage_indexdupe' => 'પ્રતિકૃતિરૂપ કડી',
	'proofreadpage_indexdupetext' => 'સૂચિ પૃષ્ઠ પર પાનાં એક કરતાં વધુ વખત ના વર્ણવી શકાય.',
	'proofreadpage_nologin' => 'પ્રવેશ કરેલ નથી',
	'proofreadpage_nologintext' => 'પાનાંનું પ્રુફરીડિંગ સ્તર બદલવા માટે આપનું [[Special:UserLogin|પ્રવેશ કરવું]] આવશ્યક છે .',
	'proofreadpage_notallowed' => 'બદલાવની પરવાનગી નથી',
	'proofreadpage_notallowedtext' => 'તમને આ પાનાનો પ્રુફ રીડિંગ દરજ્જો બદલવાની પરવાનગી નથી.',
	'proofreadpage_number_expected' => 'ત્રુટિ: આંકાડાકીય માહિતી અપેક્ષિત',
	'proofreadpage_interval_too_large' => 'ત્રુટિ: ખૂબ મોટો વિરામ ગાળો',
	'proofreadpage_invalid_interval' => 'ત્રુટિ: અનુચિત વિરામ ગાળો',
	'proofreadpage_nextpage' => 'પછીનું પાનું',
	'proofreadpage_prevpage' => 'પહેલાંનું પાનું',
	'proofreadpage_header' => 'મથાળું (અસમાવિષ્ટ):',
	'proofreadpage_body' => 'પાનું (આંતરિક ઉમેરણ સહિત):',
	'proofreadpage_footer' => 'પૃષ્ઠ અંત (અસમાવિષ્ટ):',
	'proofreadpage_toggleheaders' => 'અસમાવિષ્ટ વિભાગની દૃશ્યતા પલટાવો',
	'proofreadpage_quality0_category' => 'લખાણ રહિત',
	'proofreadpage_quality1_category' => 'ભૂલશુદ્ધિ બાકી',
	'proofreadpage_quality2_category' => 'સમસ્યારૂપ',
	'proofreadpage_quality3_category' => 'ભૂલશુદ્ધિ પૂર્ણ',
	'proofreadpage_quality4_category' => 'પ્રમાણિત',
	'proofreadpage_quality0_message' => 'આ પાનાનું પ્રુફરીડ જરૂરી નથી.',
	'proofreadpage_quality1_message' => 'આ પાનાનું પ્રુફરીડિંગ બાકી છે',
	'proofreadpage_quality2_message' => 'આ પાનાનું પ્રુફરીડ કરતા તકલીફ આવી હતી.',
	'proofreadpage_quality3_message' => 'આ પાનાનું પ્રુફરીડિંગ થઈ ગયું છે',
	'proofreadpage_quality4_message' => 'આ પાનું પ્રમાણિત થઈ ગયું છે.',
	'proofreadpage_index_listofpages' => 'પાનાની યાદી',
	'proofreadpage_image_message' => 'સૂચિ પૃષ્ઠ સાથે જોડો',
	'proofreadpage_page_status' => 'પાનાની સ્થિતી',
	'proofreadpage_js_attributes' => 'લેખક શીર્ષક વર્ષ પ્રકાશક',
	'proofreadpage_index_attributes' => 'સર્જક
શીર્ષક
વર્ષ|પ્રકાશનનું વર્ષ
પ્રકાશક
સ્રોત
ચિત્ર|મુખ પૃષ્ઠ
પાનાં||20
નોંધ||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|પાનું|પાના}}',
	'proofreadpage_specialpage_legend' => 'સૂચિ પૃષ્ઠોમાં શોધો',
	'proofreadpage_source' => 'સ્રોત',
	'proofreadpage_source_message' => 'આ લખાણની માહિતી દૃઢ કરવા માટે સ્કેન આવૃત્તિ વપરાઈ છે.',
	'right-pagequality' => 'પાનાનો ગુણવત્તા દર્શક બદલો',
	'proofreadpage-section-tools' => 'પ્રુફરીડ સાધનો',
	'proofreadpage-group-zoom' => 'ઝૂમ',
	'proofreadpage-group-other' => 'અન્ય',
	'proofreadpage-button-toggle-visibility-label' => 'આ પાનાંનું મથાળું અને અંત બતાવો/છુપાવો',
	'proofreadpage-button-zoom-out-label' => 'ઝૂમ આઉટ',
	'proofreadpage-button-reset-zoom-label' => 'મૂળ માપ',
	'proofreadpage-button-zoom-in-label' => 'ઝૂમ ઇન',
	'proofreadpage-button-toggle-layout-label' => 'પાનાંની આડી/ઉભી ગોઠવણ',
);

/** Manx (Gaelg)
 * @author MacTire02
 */
$messages['gv'] = array(
	'proofreadpage_nextpage' => 'Yn chied duillag elley',
	'proofreadpage_prevpage' => 'Yn duillag roish shen',
	'proofreadpage_index_listofpages' => 'Rolley duillagyn',
);

/** Hausa (Hausa) */
$messages['ha'] = array(
	'proofreadpage_namespace' => 'Shafi',
);

/** Hawaiian (Hawai`i)
 * @author Kalani
 * @author Singularity
 */
$messages['haw'] = array(
	'proofreadpage_nextpage' => 'Mea aʻe',
	'proofreadpage_prevpage' => 'Mea ma mua aʻe',
);

/** Hebrew (עברית)
 * @author Amire80
 * @author Rotem Liss
 * @author Rotemliss
 * @author YaronSh
 * @author ערן
 */
$messages['he'] = array(
	'indexpages' => 'רשימת דפי מפתח',
	'pageswithoutscans' => 'דפים ללא סריקות',
	'proofreadpage_desc' => 'השוואה קלה של טקסט לסריקה המקורית שלו',
	'proofreadpage_image' => 'תמונה',
	'proofreadpage_index' => 'מפתח',
	'proofreadpage_index_expected' => 'שגיאה: נדרש מפתח',
	'proofreadpage_nosuch_index' => 'שגיאה: אין מפתח כזה',
	'proofreadpage_nosuch_file' => 'שגיאה: אין קובץ כזה',
	'proofreadpage_badpage' => 'תסדיר שגוי',
	'proofreadpage_badpagetext' => 'תסדיר הדף שניסיתם לשמור אינו נכון.',
	'proofreadpage_indexdupe' => 'קישור כפול',
	'proofreadpage_indexdupetext' => 'לא ניתן להציג את הדפים יותר מפעם אחת בדף מפתח.',
	'proofreadpage_nologin' => 'לא נכנסתם לחשבון',
	'proofreadpage_nologintext' => 'עליכם [[Special:UserLogin|להיכנס לחשבון]] כדי לשנות את מצב ההגהה של דפים.',
	'proofreadpage_notallowed' => 'לא ניתן לבצע את השינוי',
	'proofreadpage_notallowedtext' => 'אינכם מורשים לשנות את מצב ההגהה של דף זה.',
	'proofreadpage_dataconfig_badformatted' => 'באג בהגדרות נתונים',
	'proofreadpage_dataconfig_badformattedtext' => 'הדף [[Mediawiki:Proofreadpage index data config]] אינו כתוב בתסדיר JSON תקין.',
	'proofreadpage_number_expected' => 'שגיאה: נדרש ערך מספרי',
	'proofreadpage_interval_too_large' => 'שגיאה: המרווח גדול מדי',
	'proofreadpage_invalid_interval' => 'שגיאה: מרווח בלתי־תקין',
	'proofreadpage_nextpage' => 'הדף הבא',
	'proofreadpage_prevpage' => 'הדף הקודם',
	'proofreadpage_header' => 'כותרת (לא להכללה):',
	'proofreadpage_body' => 'גוף הדף (להכללה):',
	'proofreadpage_footer' => 'כותרת תחתונה (לא להכללה):',
	'proofreadpage_toggleheaders' => 'הצגה או הסתרה של החלקים שאינם להכללה',
	'proofreadpage_quality0_category' => 'ללא טקסט',
	'proofreadpage_quality1_category' => 'לא בוצעה הגהה',
	'proofreadpage_quality2_category' => 'בעייתי',
	'proofreadpage_quality3_category' => 'בוצעה הגהה',
	'proofreadpage_quality4_category' => 'מאומת',
	'proofreadpage_quality0_message' => 'לדף זה לא נדרשת הגהה',
	'proofreadpage_quality1_message' => 'דף זה לא עבר הגהה',
	'proofreadpage_quality2_message' => 'הייתה בעיה בעת ביצוע הגהה לדף זה',
	'proofreadpage_quality3_message' => 'דף זה עבר הגהה',
	'proofreadpage_quality4_message' => 'דף זה עבר אימות',
	'proofreadpage_index_status' => 'מצב המפתח',
	'proofreadpage_index_size' => 'מספר הדפים',
	'proofreadpage_specialpage_label_orderby' => 'סדר לפי:',
	'proofreadpage_specialpage_label_key' => 'חיפוש:',
	'proofreadpage_specialpage_label_sortascending' => 'מיון בסדר עולה',
	'proofreadpage_alphabeticalorder' => 'סדר אלפביתי',
	'proofreadpage_index_listofpages' => 'רשימת דפים',
	'proofreadpage_image_message' => 'קישור לדף המפתח',
	'proofreadpage_page_status' => 'מצב הדף',
	'proofreadpage_js_attributes' => 'מחבר כותרת שנה מוציא לאור',
	'proofreadpage_index_attributes' => 'מחבר
כותרת
שנה|שנת פרסום
מוציא לאור
מקור
תמונה|תמונת עטיפה
דפים||20
הערות||10',
	'proofreadpage_pages' => '{{PLURAL:$1|דף אחד|$2 דפים}}',
	'proofreadpage_specialpage_legend' => 'חיפוש בדפי המפתח',
	'proofreadpage_specialpage_searcherror' => 'שגיאה במנוע חיפוש',
	'proofreadpage_specialpage_searcherrortext' => 'מנוע החיפוש אינו עובד. אנו מצטערים על האי־נוחות.',
	'proofreadpage_source' => 'מקור',
	'proofreadpage_source_message' => 'הגרסה הסרוקה ששימשה להכנת טקסט זה',
	'right-pagequality' => 'החלפת דגל האיכות של הדף',
	'proofreadpage-section-tools' => 'כלי הגהה',
	'proofreadpage-group-zoom' => 'תקריב',
	'proofreadpage-group-other' => 'אחר',
	'proofreadpage-button-toggle-visibility-label' => 'להציג או להסתיר את הכותרת העליונה והתחתונה של הדף הזה',
	'proofreadpage-button-zoom-out-label' => 'התרחקות',
	'proofreadpage-button-reset-zoom-label' => 'גודל מקורי',
	'proofreadpage-button-zoom-in-label' => 'תקריב',
	'proofreadpage-button-toggle-layout-label' => 'פריסה אופקית או אנכית',
	'proofreadpage-preferences-showheaders-label' => 'הצגת כותרת עליונה וכותרת תחתונה בעת עריכה במרחב השמות "{{ns:page}}".',
	'proofreadpage-preferences-horizontal-layout-label' => 'להשתמש בתצוגה אופקית בעת עריכה ב מרחב השם {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'מטא־נתונים של ספרים מאתר {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'מטא־נתונים של ספרים שמנהלת ההרחבה ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'לא נמצאה סכֵמה.',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'לא נמצאה הסכֵמה $1.',
);

/** Hindi (हिन्दी)
 * @author Ansumang
 * @author Kaustubh
 * @author Siddhartha Ghai
 */
$messages['hi'] = array(
	'proofreadpage_desc' => 'मूल पाठ और सद्य पाठ में फर्क आसानी से दर्शाती हैं',
	'proofreadpage_image' => 'चित्र',
	'proofreadpage_index' => 'अनुक्रम',
	'proofreadpage_badpage' => 'गलत फ़ारमैट',
	'proofreadpage_indexdupe' => 'नकली लिंक',
	'proofreadpage_nologin' => 'लॉग इन नहीं किया है',
	'proofreadpage_nextpage' => 'अगला पन्ना',
	'proofreadpage_prevpage' => 'पिछला पन्ना',
	'proofreadpage_header' => 'पन्ने का उपरी पाठ (noinclude):',
	'proofreadpage_body' => 'पन्ने का मुख्य पाठ (जो इस्तेमाल में आयेगा):',
	'proofreadpage_footer' => 'पन्ने का निचला पाठ (noinclude):',
	'proofreadpage_toggleheaders' => 'noinclude विभांगोंका दृष्य स्तर बदलें',
	'proofreadpage_quality0_category' => 'लेख के बिना',
	'proofreadpage_quality1_category' => 'परिक्षण हुआ नहीं',
	'proofreadpage_quality2_category' => 'समस्याकारक',
	'proofreadpage_quality3_category' => 'परिक्षण करें',
	'proofreadpage_quality4_category' => 'प्रमाणित',
	'proofreadpage_index_listofpages' => 'पन्नों की सूची',
	'proofreadpage_image_message' => 'अनुक्रम पन्ने के लिये कड़ी',
	'proofreadpage_page_status' => 'पन्नेकी स्थिती',
	'proofreadpage_js_attributes' => 'लेखक शीर्षक वर्ष प्रकाशक',
	'proofreadpage_index_attributes' => 'लेखक
शीर्षक
वर्ष|प्रकाशन वर्ष
प्रकाशक
स्रोत
चित्र|मुखपृष्ठ चित्र
पन्ने||२०
टिप्पणी||१०',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|पृष्ठ|पृष्ठ}}',
	'proofreadpage_specialpage_legend' => 'इंडेक्स पृष्ठ खोजें',
	'proofreadpage_source' => 'स्रोत',
	'proofreadpage-group-zoom' => 'ज़ूम',
	'proofreadpage-group-other' => 'अन्य',
	'proofreadpage-button-reset-zoom-label' => 'मूल आकार',
);

/** Fiji Hindi (Latin script) (Fiji Hindi)
 * @author AndySingh
 * @author Thakurji
 */
$messages['hif-latn'] = array(
	'indexpages' => 'Index panna ke suchi',
	'pageswithoutscans' => 'Bina scan waala panna',
	'proofreadpage_desc' => 'Text aur original scan ke easy se comapre kare do',
	'proofreadpage_image' => 'Chhapa',
	'proofreadpage_index' => 'Index',
	'proofreadpage_index_expected' => 'Wrong: index chaahat rahaa',
	'proofreadpage_nosuch_index' => 'Wrong: ii rakam ke koi index nai hae',
	'proofreadpage_nosuch_file' => 'Wrong: ii rakam ke koi file nai hae',
	'proofreadpage_badpage' => 'Wrong Format',
	'proofreadpage_badpagetext' => 'Jon panna ke aap bachae mangta hae ke format right nai hae.',
	'proofreadpage_indexdupe' => 'Dugna link',
	'proofreadpage_indexdupetext' => 'Index panna me ek panna ke ek time se jaada dafe nai list karaa jaae sake hae.',
	'proofreadpage_nologin' => 'Aap abhi logged in nai hai',
	'proofreadpage_nologintext' => 'Aap ke chaahi ki aap panna ke proofreading status ke badle ke khatir [[Special:UserLogin|logged in]]  hae.',
	'proofreadpage_notallowed' => 'Badle le allowed nai hae',
	'proofreadpage_notallowedtext' => 'Aap ke ii panna ke proofreading status ke badle ke ijaajat nai hae.',
	'proofreadpage_dataconfig_badformatted' => 'Data configuration me bug hae',
	'proofreadpage_dataconfig_badformattedtext' => 'Panna [[Mediawiki:Proofreadpage index data config]] achchhaa se formatted JSON nai hae.',
	'proofreadpage_number_expected' => 'Wrong: number ke jaruri rahaa',
	'proofreadpage_interval_too_large' => 'Wrong: interval bahut barraa hae.',
	'proofreadpage_invalid_interval' => 'Wrong: interval valid nai hae',
	'proofreadpage_nextpage' => 'Aage waala panna',
	'proofreadpage_prevpage' => 'Pahile waala panna',
	'proofreadpage_header' => 'Header (noinclude):',
	'proofreadpage_body' => 'Panna ke body (to be transcluded):',
	'proofreadpage_footer' => 'Footer (noinclude):',
	'proofreadpage_toggleheaders' => 'toggle noinclude sections visibility',
	'proofreadpage_quality0_category' => 'Bina text ke',
	'proofreadpage_quality1_category' => 'Proofread nai karaa gais hae',
	'proofreadpage_quality2_category' => 'Problem hae',
	'proofreadpage_quality3_category' => 'Proofread kar dewa gais',
	'proofreadpage_quality4_category' => 'Validate kar dewa gais',
	'proofreadpage_quality0_message' => 'II panna ke proofread kare ke jaruri nai hae',
	'proofreadpage_quality1_message' => 'Ii panna ke proofread nai karaa gais hae',
	'proofreadpage_quality2_message' => 'Proofread kare ke time kuchh karrbarri bhais',
	'proofreadpage_quality3_message' => 'Ii panna ke proofread kar dewa gais hae',
	'proofreadpage_quality4_message' => 'Ii panna ke validate kar dewa gais hae',
	'proofreadpage_index_status' => 'Index ke status',
	'proofreadpage_index_size' => 'Ketna panna hae',
	'proofreadpage_specialpage_label_orderby' => 'Order me karo:',
	'proofreadpage_specialpage_label_key' => 'Khojo',
	'proofreadpage_specialpage_label_sortascending' => 'Chhota se barraa karo',
	'proofreadpage_alphabeticalorder' => 'Alphabetical order me karo',
	'proofreadpage_index_listofpages' => 'Panna ke suchi',
	'proofreadpage_image_message' => 'Index panna se jorro',
	'proofreadpage_page_status' => 'Panna ke status',
	'proofreadpage_js_attributes' => 'Author Title Saal Publisher',
	'proofreadpage_index_attributes' => 'Likhe waala
Title
Saal| Publication ke saal
Publisher
Source
Chaapa|Cover ke chhapa
Panna||20
Remarks||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|panna|panna}}',
	'proofreadpage_specialpage_legend' => 'Index panna me khojo',
	'proofreadpage_specialpage_searcherror' => 'Search engine me kuchh garrbarri hae',
	'proofreadpage_specialpage_searcherrortext' => 'Search engine kaam nai kare hae. Iske khatir maafi hae.',
	'proofreadpage_source' => 'File ke source',
	'proofreadpage_source_message' => 'Scanned edition used to establish this text',
	'right-pagequality' => 'Panna ke quality jhanda ke badlo',
	'proofreadpage-section-tools' => 'Proofread kare waala tool',
	'proofreadpage-group-zoom' => 'Barraa karo',
	'proofreadpage-group-other' => 'Duusra',
	'proofreadpage-button-toggle-visibility-label' => 'Ii panna ke header aur footer ke dekhao/lukao',
	'proofreadpage-button-zoom-out-label' => 'Chhota karo',
	'proofreadpage-button-reset-zoom-label' => 'Pahile ke size',
	'proofreadpage-button-zoom-in-label' => 'Barraa karo',
	'proofreadpage-button-toggle-layout-label' => 'Vertical/horizontal layout',
	'proofreadpage-preferences-showheaders-label' => 'Panna ke {{ns:page}} namespace me badle ke time header aur footer ke dekhao',
	'proofreadpage-preferences-horizontal-layout-label' => '{{ns:page}} namespace me badle ke khatir horizontal layout ke kaam me laao.',
	'proofreadpage-indexoai-repositoryName' => '{{SITENAME}} se book ke metadata',
	'proofreadpage-indexoai-eprint-content-text' => 'Book ke metadata jiske ProofreadPanna manage kare hae.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Schema nai milaa',
	'proofreadpage-indexoai-error-schemanotfound-text' => '$1 schema nai mila hae.',
);

/** Croatian (hrvatski)
 * @author Dalibor Bosits
 * @author Dnik
 * @author Ex13
 * @author Roberta F.
 * @author SpeedyGonsales
 */
$messages['hr'] = array(
	'indexpages' => 'Popis sadržaja stranica',
	'pageswithoutscans' => 'Stranice bez skeniranih slika',
	'proofreadpage_desc' => 'Omogućava jednostavnu usporedbu teksta i izvornog skena',
	'proofreadpage_image' => 'Slika',
	'proofreadpage_index' => 'Sadržaj',
	'proofreadpage_index_expected' => 'Progreška: očekivan je sadržaj',
	'proofreadpage_nosuch_index' => 'Pogreška: nema takvog sadržaja',
	'proofreadpage_nosuch_file' => 'Pogreška: nema takve datoteke',
	'proofreadpage_badpage' => 'Pogrešan format',
	'proofreadpage_badpagetext' => 'Format stranice koju ste pokušali spremiti je neispravan.',
	'proofreadpage_indexdupe' => 'Duplicirana poveznica',
	'proofreadpage_indexdupetext' => 'Stranice ne mogu biti iszlistane više od jednom na stranici sadržaja.',
	'proofreadpage_nologin' => 'Niste prijavljeni',
	'proofreadpage_nologintext' => 'Morate biti [[Special:UserLogin|prijavljeni]] za izmjenu statusa provjerenosti na stranicama.',
	'proofreadpage_notallowed' => 'Izmjena nije dozvoljena',
	'proofreadpage_notallowedtext' => 'Nije Vam dozvoljeno mijenjati status ispravljenosti ove stranice.',
	'proofreadpage_number_expected' => 'Pogreška: očekivana je brojčana vrijednost',
	'proofreadpage_interval_too_large' => 'Pogreška: interval je prevelik',
	'proofreadpage_invalid_interval' => 'Pogreška: interval nije valjan',
	'proofreadpage_nextpage' => 'Sljedeća stranica',
	'proofreadpage_prevpage' => 'Prethodna stranica',
	'proofreadpage_header' => "Zaglavlje (''noinclude''):",
	'proofreadpage_body' => 'Tijelo stranice (bit će uključeno):',
	'proofreadpage_footer' => "Podnožje (''footer noinclude''):",
	'proofreadpage_toggleheaders' => "promijeni vidljivost ''noinclude'' odlomaka",
	'proofreadpage_quality0_category' => 'Bez teksta',
	'proofreadpage_quality1_category' => 'Nije ispravljeno',
	'proofreadpage_quality2_category' => 'Problematično',
	'proofreadpage_quality3_category' => 'Ispravljeno',
	'proofreadpage_quality4_category' => 'Provjereno',
	'proofreadpage_quality0_message' => 'Ovu stranicu nije potrebno ispravljati',
	'proofreadpage_quality1_message' => 'Ova stranica nije ispravljena',
	'proofreadpage_quality2_message' => 'Došlo je do problema prilikom ispravljanja ove stranice',
	'proofreadpage_quality3_message' => 'Ova stranica je ispravljena',
	'proofreadpage_quality4_message' => 'Ova stranica je potvrđena',
	'proofreadpage_index_listofpages' => 'Popis stranica',
	'proofreadpage_image_message' => 'Poveznica na stranicu sa sadržajem',
	'proofreadpage_page_status' => 'Status stranice',
	'proofreadpage_js_attributes' => 'Autor Naslov Godina Izdavač',
	'proofreadpage_index_attributes' => 'Autor
Naslov
Godina|Godina izdavanja
Izdavač
Izvor
Slika|Naslovnica
Stranica||20
Napomene||10',
	'proofreadpage_pages' => '{{PLURAL:$1|stranica|stranice}}', # Fuzzy
	'proofreadpage_specialpage_legend' => 'Pretraživanje stranica kataloga',
	'proofreadpage_source' => 'Izvor',
	'proofreadpage_source_message' => 'Skenirana inačica rabljena za ovaj tekst',
	'right-pagequality' => 'Izmijeni zastavicu kvalitete stranice',
);

/** Upper Sorbian (hornjoserbsce)
 * @author Dundak
 * @author Michawiki
 */
$messages['hsb'] = array(
	'indexpages' => 'Lisćina indeksowych stronow',
	'pageswithoutscans' => 'Strony bjez skanow',
	'proofreadpage_desc' => 'Lochke přirunanje teksta z originalnym skanom dowolić',
	'proofreadpage_image' => 'Wobraz',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => 'Zmylk: indeks wočakowany',
	'proofreadpage_nosuch_index' => 'Zmylk: tajki indeks njeje',
	'proofreadpage_nosuch_file' => 'Zmylk: tajka dataja njeje',
	'proofreadpage_badpage' => 'Wopačny format',
	'proofreadpage_badpagetext' => 'Format strony, kotruž sy spytał składować, je wopak.',
	'proofreadpage_indexdupe' => 'Dwójny wotkaz',
	'proofreadpage_indexdupetext' => 'Strony njedadźa so wjace hač jedyn raz na indeksowej stronje nalistować.',
	'proofreadpage_nologin' => 'Njejsy so přizjewił',
	'proofreadpage_nologintext' => 'Dyrbiš [[Special:UserLogin|přizjewjeny]] być, zo by status kontrolneho čitanja stronow změnił.',
	'proofreadpage_notallowed' => 'Změna njedowolena',
	'proofreadpage_notallowedtext' => 'Njesměš status kontrolneho čitanja tutej strony změnić.',
	'proofreadpage_dataconfig_badformatted' => 'Zmylk w konfiguraciji datow',
	'proofreadpage_dataconfig_badformattedtext' => 'Strona [[Mediawiki:Proofreadpage index data config]] w derje sformowanym JSON njeje.',
	'proofreadpage_number_expected' => 'Zmylk: numeriska hódnota wočakowana',
	'proofreadpage_interval_too_large' => 'Zmylk: interwal přewulki',
	'proofreadpage_invalid_interval' => 'Zmylk: njepłaćiwy interwal',
	'proofreadpage_nextpage' => 'Přichodna strona',
	'proofreadpage_prevpage' => 'Předchadna strona',
	'proofreadpage_header' => 'Hłowowa linka (noinclude)',
	'proofreadpage_body' => 'Tekstowy ćěleso (transkluzija):',
	'proofreadpage_footer' => 'Nohowa linka (noinclude):',
	'proofreadpage_toggleheaders' => 'wotrězki noinclude pokazać/schować',
	'proofreadpage_quality0_category' => 'Bjez teksta',
	'proofreadpage_quality1_category' => 'Njeskorigowany',
	'proofreadpage_quality2_category' => 'Njedospołny',
	'proofreadpage_quality3_category' => 'Skorigowany',
	'proofreadpage_quality4_category' => 'Hotowy',
	'proofreadpage_quality0_message' => 'Tuta strona njetrjeba so skorigować',
	'proofreadpage_quality1_message' => 'Tut strona njeje so skorigowała',
	'proofreadpage_quality2_message' => 'Při korigowanju tuteje strony je problem wustupił',
	'proofreadpage_quality3_message' => 'Tuta strona je so skorigowała',
	'proofreadpage_quality4_message' => 'Tuta strona je so přepruwowała',
	'proofreadpage_index_status' => 'Indeksowy status',
	'proofreadpage_index_size' => 'Ličba stronow',
	'proofreadpage_specialpage_label_orderby' => 'Sortěrować po:',
	'proofreadpage_specialpage_label_key' => 'Pytać:',
	'proofreadpage_specialpage_label_sortascending' => 'Postupowacy sortěrować',
	'proofreadpage_alphabeticalorder' => 'Alfabetiski porjad',
	'proofreadpage_index_listofpages' => 'Lisćina stronow',
	'proofreadpage_image_message' => 'Wotkaz k indeksowej stronje',
	'proofreadpage_page_status' => 'Status strony',
	'proofreadpage_js_attributes' => 'Awtor Titul Lěto Wudawaćel',
	'proofreadpage_index_attributes' => 'Awtor
Titul
Lěto|Lěto publikowanja
Wudawaćel
Žórło
Wobraz|Wobraz titloweje strony
Strony||20
Přispomnjenki||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|strona|stronje|strony|stronow}}',
	'proofreadpage_specialpage_legend' => 'Indeksowe strony přepytać',
	'proofreadpage_specialpage_searcherror' => 'Zmylk w pytawje',
	'proofreadpage_specialpage_searcherrortext' => 'Pytawa njefunguje. Wodaj njepřijomnosće.',
	'proofreadpage_source' => 'Žórło',
	'proofreadpage_source_message' => 'Skanowane wudaće wužite za wutworjenje tutoho teksta',
	'right-pagequality' => 'Kajkosć strony změnić',
	'proofreadpage-section-tools' => 'Nastroje za korigowanje',
	'proofreadpage-group-zoom' => 'Skalowanje',
	'proofreadpage-group-other' => 'Druhe',
	'proofreadpage-button-toggle-visibility-label' => 'Hłowu a nohu tuteje strony pokazać/schować',
	'proofreadpage-button-zoom-out-label' => 'Pomjeńšić',
	'proofreadpage-button-reset-zoom-label' => 'Prěnjotna wulkosć',
	'proofreadpage-button-zoom-in-label' => 'Powjetšić',
	'proofreadpage-button-toggle-layout-label' => 'Padorune/Wodorune wuhotowanje',
	'proofreadpage-preferences-showheaders-label' => 'Hłowowe a nohowe pola pokazać, hdyž so w mjenowym rumje {{ns:page}} wobdźěłuje',
	'proofreadpage-preferences-horizontal-layout-label' => 'Horicontalne wuhotowanje wužiwać, hdyž so w mjenowym rumje {{ns:page}} wobdźěłuje',
	'proofreadpage-indexoai-repositoryName' => 'Knižne metadaty z {{GRAMMAR:genitiw|{{SITENAME}}}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Knižne metadaty zrjadowane přez ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Šema njeje so namakał',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Šema $1 njeje so namakał.',
);

/** Hungarian (magyar)
 * @author Dani
 * @author Dj
 * @author Dorgan
 * @author Glanthor Reviol
 * @author KossuthRad
 */
$messages['hu'] = array(
	'indexpages' => 'Indexlapok listája',
	'pageswithoutscans' => 'Vizsgálatlan lapok',
	'proofreadpage_desc' => 'Lehetővé teszi a szöveg és az eredeti szkennelt változat egyszerű összehasonlítását',
	'proofreadpage_image' => 'Kép',
	'proofreadpage_index' => 'Index',
	'proofreadpage_index_expected' => 'Hiba: indexet vártam',
	'proofreadpage_nosuch_index' => 'Hiba: nincs ilyen index',
	'proofreadpage_nosuch_file' => 'Hiba: nincs ilyen fájl',
	'proofreadpage_badpage' => 'Hibás formátum',
	'proofreadpage_badpagetext' => 'A lap formátuma, amit menteni próbáltál rossz.',
	'proofreadpage_indexdupe' => 'Hivatkozás megkettőzése',
	'proofreadpage_indexdupetext' => 'A lapok nem szerepelhetnek egynél többször egy indexlapon.',
	'proofreadpage_nologin' => 'Nem vagy bejelentkezve',
	'proofreadpage_nologintext' => '[[Special:UserLogin|Be kell jelentkezned]], hogy módosítani tudd a lapok korrektúrázási állapotát.',
	'proofreadpage_notallowed' => 'A változtatás nincs engedélyezve',
	'proofreadpage_notallowedtext' => 'Nincs jogosultságod megváltoztatni a lap korrektúrázási állapotát.',
	'proofreadpage_number_expected' => 'Hiba: numerikus értéket vártam',
	'proofreadpage_interval_too_large' => 'Hiba: az intervallum túl nagy',
	'proofreadpage_invalid_interval' => 'Hiba: érvénytelen intervallum',
	'proofreadpage_nextpage' => 'Következő oldal',
	'proofreadpage_prevpage' => 'Előző oldal',
	'proofreadpage_header' => 'Fejléc (noinclude):',
	'proofreadpage_body' => 'Oldal (be lesz illesztve):',
	'proofreadpage_footer' => 'Lábléc (noinclude):',
	'proofreadpage_toggleheaders' => 'noinclude részek láthatóságának váltása',
	'proofreadpage_quality0_category' => 'Szöveg nélkül',
	'proofreadpage_quality1_category' => 'Nincs korrektúrázva',
	'proofreadpage_quality2_category' => 'Problematikus',
	'proofreadpage_quality3_category' => 'Korrektúrázva',
	'proofreadpage_quality4_category' => 'Jóváhagyva',
	'proofreadpage_quality0_message' => 'A lapot nem szükséges korrektúrázni',
	'proofreadpage_quality1_message' => 'A lap nincsen korrektúrázva',
	'proofreadpage_quality2_message' => 'Probléma történt a lap korrektúrázása közben',
	'proofreadpage_quality3_message' => 'A lap korrektúrázva van',
	'proofreadpage_quality4_message' => 'A lap jóváhagyva',
	'proofreadpage_index_listofpages' => 'Oldalak listája',
	'proofreadpage_image_message' => 'Csatolni az index oldalhoz',
	'proofreadpage_page_status' => 'Oldal állapota',
	'proofreadpage_js_attributes' => 'Szerző Cím Év Kiadó',
	'proofreadpage_index_attributes' => 'Szerző
Cím
Év|Kiadás éve
Kiadó
Forrás
Kép|Borító
Oldalak||20
Megjegyzések||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|lap|lap}}',
	'proofreadpage_specialpage_legend' => 'Indexlapok keresése',
	'proofreadpage_source' => 'Forrás',
	'proofreadpage_source_message' => 'A szkennelt változat amin a szöveg alapszik',
	'right-pagequality' => 'lapok minőség szerinti értékelésének módosítása',
);

/** Armenian (Հայերեն)
 * @author Chaojoker
 * @author Teak
 * @author Xelgen
 */
$messages['hy'] = array(
	'indexpages' => 'Ինդեքս էջերի ցանկ',
	'pageswithoutscans' => 'Էջեր առանց տեսածրած բնօրինակի',
	'proofreadpage_desc' => 'Թույլ է տալիս տեքստի և բնօրինակի տեսածրված պատկերի հեշտ համեմատում',
	'proofreadpage_image' => 'պատկեր',
	'proofreadpage_index' => 'Ինդեքս',
	'proofreadpage_index_expected' => 'Սխալ. ինդեքս չհայտնաբերվեց',
	'proofreadpage_nosuch_index' => 'Սխալ. այդպիսի ինդեքս չկա',
	'proofreadpage_nosuch_file' => 'Սխալ. այդպիսի նիշք չկա',
	'proofreadpage_badpage' => 'Սխալ ֆորմատ',
	'proofreadpage_badpagetext' => 'Հիշվող էջի սխալ ֆորմատ։',
	'proofreadpage_indexdupe' => 'Կրկնակի հղում',
	'proofreadpage_indexdupetext' => 'Էջերը չեն կարող ներառվել ինդեքս էջում մեկից ավել անգամ։',
	'proofreadpage_nologin' => 'Չեք մտել համակարգ',
	'proofreadpage_nologintext' => 'Էջերի սրբագրման կարգավիճակը փոխելու համար անհրաժեշտ է [[Special:UserLogin|մտնել համակարգ]]։',
	'proofreadpage_notallowed' => 'Փոփոխությունը չի թույլատրվում',
	'proofreadpage_notallowedtext' => 'Դուք չեք կարող փոխել այս էջի սրբագրման կարգավիճակը։',
	'proofreadpage_number_expected' => 'Սխալ. սպասվում է թվային արժեք',
	'proofreadpage_interval_too_large' => 'Սխալ. չափից մեծ միջակայք',
	'proofreadpage_invalid_interval' => 'Սխալ. անվավեր միջակայք',
	'proofreadpage_nextpage' => 'Հաջորդ էջ',
	'proofreadpage_prevpage' => 'Նախորդ էջ',
	'proofreadpage_header' => 'Վերնագիր (չի ներառվում).',
	'proofreadpage_body' => 'Էջի մարմին (ներառվելու է).',
	'proofreadpage_footer' => 'Ստորագիր (չի ներառվում)',
	'proofreadpage_toggleheaders' => 'ցուցադրել չներառվող բաժինները',
	'proofreadpage_quality0_category' => 'Առանց տեքստ',
	'proofreadpage_quality1_category' => 'Չսրբագրված',
	'proofreadpage_quality2_category' => 'Խնդրահարույց',
	'proofreadpage_quality3_category' => 'Սրբագրված',
	'proofreadpage_quality4_category' => 'Հաստատված',
	'proofreadpage_quality0_message' => 'Այս էջը սրբագրման կարիք չունի',
	'proofreadpage_quality1_message' => 'Այս էջը սրբագրված չէ',
	'proofreadpage_quality2_message' => 'Սխալ առաջացավ էջը սրբագրելիս',
	'proofreadpage_quality3_message' => 'Այս էջը սրբագրված է',
	'proofreadpage_quality4_message' => 'Այս էջը հաստատված է',
	'proofreadpage_index_listofpages' => 'Էջերի ցանկ',
	'proofreadpage_image_message' => 'Հղում ինդեքսի էջին',
	'proofreadpage_page_status' => 'Էջի կարգավիճակ',
	'proofreadpage_js_attributes' => 'Հեղինակ Անվանում Տարի Հրատարակություն',
	'proofreadpage_index_attributes' => 'Author|Հեղինակ
Title|Անվանում
Year|Հրատարակման տարեթիվ
Publisher|Հրատարակություն
Source|Աղբյուր
Image|Կազմի պատկեր
Pages|Էջեր|20
Remarks|Նշումներ|10',
	'proofreadpage_pages' => '{{PLURAL:$1|էջ|էջ}}', # Fuzzy
	'proofreadpage_specialpage_legend' => 'Որոնել ինդեքս էջեր',
	'proofreadpage_source' => 'Աղբյուր',
	'proofreadpage_source_message' => 'Այս տեքստը ստեղծելու համար օգտագործված նյութեր',
	'right-pagequality' => 'Էջի որակի փոփոխման դրոշակ',
	'proofreadpage-section-tools' => 'Սրբագրագործիքներ',
	'proofreadpage-group-zoom' => 'Խոշորացում',
	'proofreadpage-group-other' => 'Այլ',
	'proofreadpage-button-toggle-visibility-label' => 'Ցուցադրել/թաքցնել էջի էջագլուխն և էջատակը',
	'proofreadpage-button-zoom-out-label' => 'Հեռվացնել',
	'proofreadpage-button-reset-zoom-label' => 'Բնօրինակ չափը',
	'proofreadpage-button-zoom-in-label' => 'Խոշորացնել',
	'proofreadpage-button-toggle-layout-label' => 'Պատկերը կողքից/վերևից',
);

/** Interlingua (interlingua)
 * @author Malafaya
 * @author McDutchie
 */
$messages['ia'] = array(
	'indexpages' => 'Lista de paginas de indice',
	'pageswithoutscans' => 'Paginas non transcludite',
	'proofreadpage_desc' => 'Facilita le comparation inter un texto e su scan original',
	'proofreadpage_image' => 'Imagine',
	'proofreadpage_index' => 'Indice',
	'proofreadpage_index_expected' => 'Error: indice expectate',
	'proofreadpage_nosuch_index' => 'Error: non existe tal indice',
	'proofreadpage_nosuch_file' => 'Error: non existe tal file',
	'proofreadpage_badpage' => 'Formato incorrecte',
	'proofreadpage_badpagetext' => 'Le formato del pagina que tu tentava publicar es incorrecte.',
	'proofreadpage_indexdupe' => 'Ligamine duplicate',
	'proofreadpage_indexdupetext' => 'Paginas non pote figurar plus de un vice in un pagina de indice.',
	'proofreadpage_nologin' => 'Tu non ha aperite un session',
	'proofreadpage_nologintext' => 'Tu debe [[Special:UserLogin|aperir un session]] pro modificar le stato de correction de paginas.',
	'proofreadpage_notallowed' => 'Cambio non permittite',
	'proofreadpage_notallowedtext' => 'Tu non ha le permission de cambiar le stato de correction de iste pagina.',
	'proofreadpage_number_expected' => 'Error: valor numeric expectate',
	'proofreadpage_interval_too_large' => 'Error: intervallo troppo grande',
	'proofreadpage_invalid_interval' => 'Error: intervallo invalide',
	'proofreadpage_nextpage' => 'Pagina sequente',
	'proofreadpage_prevpage' => 'Pagina precedente',
	'proofreadpage_header' => 'Capite (noinclude):',
	'proofreadpage_body' => 'Texto del pagina (pro esser transcludite):',
	'proofreadpage_footer' => 'Pede (noinclude):',
	'proofreadpage_toggleheaders' => 'cambiar le visibilitate del sectiones noinclude',
	'proofreadpage_quality0_category' => 'Sin texto',
	'proofreadpage_quality1_category' => 'Non corrigite',
	'proofreadpage_quality2_category' => 'Problematic',
	'proofreadpage_quality3_category' => 'Corrigite',
	'proofreadpage_quality4_category' => 'Validate',
	'proofreadpage_quality0_message' => 'Iste pagina non ha besonio de esser corrigite',
	'proofreadpage_quality1_message' => 'Iste pagina non ha essite corrigite',
	'proofreadpage_quality2_message' => 'Il habeva un problema durante le correction de iste pagina',
	'proofreadpage_quality3_message' => 'Iste pagina ha essite corrigite',
	'proofreadpage_quality4_message' => 'Iste pagina ha essite validate',
	'proofreadpage_index_status' => 'Stato del indice',
	'proofreadpage_index_size' => 'Numero de paginas',
	'proofreadpage_specialpage_label_orderby' => 'Ordinar per:',
	'proofreadpage_specialpage_label_key' => 'Cercar:',
	'proofreadpage_specialpage_label_sortascending' => 'Ordine ascendente',
	'proofreadpage_alphabeticalorder' => 'Ordine alphabetic',
	'proofreadpage_index_listofpages' => 'Lista de paginas',
	'proofreadpage_image_message' => 'Ligamine verso le pagina de indice',
	'proofreadpage_page_status' => 'Stato del pagina',
	'proofreadpage_js_attributes' => 'Autor Titulo Anno Editor',
	'proofreadpage_index_attributes' => 'Autor
Titulo
Anno|Anno de publication
Editor
Origine
Imagine|Imagine de copertura
Paginas||20
Notas||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pagina|paginas}}',
	'proofreadpage_specialpage_legend' => 'Cercar in paginas de indice',
	'proofreadpage_specialpage_searcherror' => 'Error in le motor de recerca',
	'proofreadpage_specialpage_searcherrortext' => 'Le motor de recerca non functiona. Nos regretta le inconvenientes.',
	'proofreadpage_source' => 'Fonte',
	'proofreadpage_source_message' => 'Le original scannate usate pro crear iste texto',
	'right-pagequality' => 'Modificar le marca de qualitate del pagina',
	'proofreadpage-section-tools' => 'Instrumentos pro correction de probas',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Altere',
	'proofreadpage-button-toggle-visibility-label' => 'Monstrar/celar le capite e le pede de iste pagina',
	'proofreadpage-button-zoom-out-label' => 'Diminuer',
	'proofreadpage-button-reset-zoom-label' => 'Dimension original',
	'proofreadpage-button-zoom-in-label' => 'Aggrandir',
	'proofreadpage-button-toggle-layout-label' => 'Disposition vertical/horizontal',
	'proofreadpage-preferences-showheaders-label' => 'Monstrar campos de capite e pede quando modificar in le spatio de nomines "{{ns:page}}"',
	'proofreadpage-preferences-horizontal-layout-label' => 'Usar le disposition horizontal quando modificar in le spatio de nomines {{ns:page}}',
);

/** Indonesian (Bahasa Indonesia)
 * @author -iNu-
 * @author Bennylin
 * @author Farras
 * @author Irwangatot
 * @author IvanLanin
 * @author Iwan Novirion
 */
$messages['id'] = array(
	'indexpages' => 'Daftar halaman indeks',
	'pageswithoutscans' => 'Halaman tanpa pindaian',
	'proofreadpage_desc' => 'Menyediakan perbandingan antara naskah dengan pindaian asli secara mudah',
	'proofreadpage_image' => 'Gambar',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => 'Kesalahan: diperlukan indeks',
	'proofreadpage_nosuch_index' => 'Kesalahan: tidak ada indeks',
	'proofreadpage_nosuch_file' => 'Kesalahan: tidak ada berkas',
	'proofreadpage_badpage' => 'Kesalahan Format',
	'proofreadpage_badpagetext' => 'Format halaman yang akan anda simpan, salah.',
	'proofreadpage_indexdupe' => 'Gandakan pranala',
	'proofreadpage_indexdupetext' => 'Halaman tidak dapat didaftarkan lebih dari sekali di halaman indeks.',
	'proofreadpage_nologin' => 'Belum masuk log',
	'proofreadpage_nologintext' => 'Anda harus [[Special:UserLogin|masuk log]] untuk mengubah status koreksi halaman.',
	'proofreadpage_notallowed' => 'Perubahan tidak diperbolehkan',
	'proofreadpage_notallowedtext' => 'Anda tidak diperbolehkan untuk mengubah status uji-baca halaman ini.',
	'proofreadpage_dataconfig_badformatted' => 'Galat dalam data konfigurasi',
	'proofreadpage_dataconfig_badformattedtext' => 'Halaman [[Mediawiki:Proofreadpage index data config]] tidak dalam format JSON yang benar.',
	'proofreadpage_number_expected' => 'Kesalahan: isi dengan angka',
	'proofreadpage_interval_too_large' => 'Kesalahan: interval terlalu besar',
	'proofreadpage_invalid_interval' => 'Kesalahan: interval tidak sah',
	'proofreadpage_nextpage' => 'Halaman selanjutnya',
	'proofreadpage_prevpage' => 'Halaman sebelumnya',
	'proofreadpage_header' => 'Kepala (noinclude):',
	'proofreadpage_body' => 'Badan halaman (untuk ditransklusikan):',
	'proofreadpage_footer' => 'Kaki (noinclude):',
	'proofreadpage_toggleheaders' => 'ganti keterlihatan bagian noinclude',
	'proofreadpage_quality0_category' => 'Halaman tanpa naskah',
	'proofreadpage_quality1_category' => 'Halaman yang belum diuji-baca',
	'proofreadpage_quality2_category' => 'Halaman bermasalah',
	'proofreadpage_quality3_category' => 'Halaman yang telah diuji-baca',
	'proofreadpage_quality4_category' => 'Halaman yang telah divalidasi',
	'proofreadpage_quality0_message' => 'Halaman ini tidak perlu diuji-baca',
	'proofreadpage_quality1_message' => 'Halaman ini belum diuji-baca',
	'proofreadpage_quality2_message' => 'Ada masalah ketika menguji-baca halaman ini',
	'proofreadpage_quality3_message' => 'Halaman ini telah diuji-baca',
	'proofreadpage_quality4_message' => 'Halaman ini telah divalidasi',
	'proofreadpage_index_status' => '|Satus indeks',
	'proofreadpage_index_size' => 'Jumlah halaman',
	'proofreadpage_specialpage_label_orderby' => 'Urut berdasarkan:',
	'proofreadpage_specialpage_label_key' => 'Cari:',
	'proofreadpage_specialpage_label_sortascending' => 'Urut naik',
	'proofreadpage_alphabeticalorder' => 'Urutan abjad',
	'proofreadpage_index_listofpages' => 'Daftar halaman',
	'proofreadpage_image_message' => 'Pranala ke halaman indeks',
	'proofreadpage_page_status' => 'Status halaman',
	'proofreadpage_js_attributes' => 'Pengarang Judul Tahun Penerbit',
	'proofreadpage_index_attributes' => 'Pengarang
Judul
Tahun|Tahun penerbitan
Penerbit
Sumber
Gambar|Gambar sampul
Halaman||20
Catatan||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|halaman|halaman}}',
	'proofreadpage_specialpage_legend' => 'Cari halaman indeks',
	'proofreadpage_specialpage_searcherror' => 'Kesalahan dalam mesin pencari',
	'proofreadpage_specialpage_searcherrortext' => 'Mesin pencari tidak bekerja. Maaf atas ketidaknyamanan ini.',
	'proofreadpage_source' => 'Sumber',
	'proofreadpage_source_message' => 'Versi pindai yang digunakan untuk membuat naskah ini',
	'right-pagequality' => 'Memodifikasi tanda kualitas halaman',
	'proofreadpage-section-tools' => 'Peralatan uji-baca',
	'proofreadpage-group-zoom' => 'Ukuran',
	'proofreadpage-group-other' => 'Lain-lain',
	'proofreadpage-button-toggle-visibility-label' => 'Tampilkan/sembunyikan header dan footer halaman ini',
	'proofreadpage-button-zoom-out-label' => 'Perkecil',
	'proofreadpage-button-reset-zoom-label' => 'Ukuran asli',
	'proofreadpage-button-zoom-in-label' => 'Perbesar',
	'proofreadpage-button-toggle-layout-label' => 'Tata letak vertikal/horizontal',
	'proofreadpage-preferences-showheaders-label' => 'Menunjukkan bidang header dan footer saat menyunting dalam ruangnama {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Menggunakan tampilan horizontal saat menyunting dalam ruangnama {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Metadata buku dari {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadata dari buku-buku yang dikelola oleh ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Skema tidak ditemukan',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Skema $1 tidak ditemukan.',
);

/** Igbo (Igbo)
 * @author Ukabia
 */
$messages['ig'] = array(
	'proofreadpage_image' => 'Nhuunuche',
	'proofreadpage_nextpage' => 'Ihü sò',
	'proofreadpage_prevpage' => 'Ihü na àzú',
	'proofreadpage_index_listofpages' => 'Ndétu ihü',
	'proofreadpage_source' => 'Mkpọlọ́gwụ̀',
);

/** Ido (Ido)
 * @author Malafaya
 */
$messages['io'] = array(
	'proofreadpage_image' => 'Imajo',
	'proofreadpage_index' => 'Indexo',
	'proofreadpage_nextpage' => 'Sequanta pagino',
	'proofreadpage_prevpage' => 'Antea pagino',
	'proofreadpage_index_listofpages' => 'Pagino-listo',
	'proofreadpage_page_status' => 'Stando di pagino',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pagino|pagini}}',
);

/** Icelandic (íslenska)
 * @author Bjarki S
 * @author S.Örvarr.S
 * @author Snævar
 */
$messages['is'] = array(
	'indexpages' => 'Listi yfir frumritasíður',
	'pageswithoutscans' => 'Síður án skönnunar',
	'proofreadpage_desc' => 'Leyfa einfaldan samanburð á texta við upphaflega skönnun',
	'proofreadpage_image' => 'Mynd',
	'proofreadpage_index' => 'Frumrit',
	'proofreadpage_index_expected' => 'Villa: frumrit vantar',
	'proofreadpage_nosuch_index' => 'Villa: frumrit óþekkt',
	'proofreadpage_nosuch_file' => 'Villa: Skráin er ekki til',
	'proofreadpage_badpage' => 'Rangt skráarsnið',
	'proofreadpage_badpagetext' => 'Skráarsnið síðunnar sem þú reyndir að vista er rangt.',
	'proofreadpage_indexdupe' => 'Endurtekinn tengill',
	'proofreadpage_indexdupetext' => 'Blaðsíður geta ekki komið fyrir oftar en einu sinni á frumritasíðu.',
	'proofreadpage_nologin' => 'Óinnskráð(ur)',
	'proofreadpage_nologintext' => 'Þú þarft að vera [[Special:UserLogin|skráð(ur) inn]] til þess að breyta prófarkarstöðu síðna.',
	'proofreadpage_notallowed' => 'Breyting óheimil',
	'proofreadpage_notallowedtext' => 'Þér er ekki óheimilt að breyta prófarkarlestursstöðu síðunnar.',
	'proofreadpage_dataconfig_badformatted' => 'Villa í gagnaupplýsingum',
	'proofreadpage_dataconfig_badformattedtext' => 'Síðan [[Mediawiki:Proofreadpage index data config]] er ekki á vel formuðu JSON sniði.',
	'proofreadpage_number_expected' => 'Villa: Bjóst við tölu en fann hana ekki',
	'proofreadpage_interval_too_large' => 'Villa: bil of stórt',
	'proofreadpage_invalid_interval' => 'Villa: ógilt bil',
	'proofreadpage_nextpage' => 'Næsta síða',
	'proofreadpage_prevpage' => 'Fyrri síða',
	'proofreadpage_header' => 'Haus (ekki innifalið):',
	'proofreadpage_body' => 'Meginmál (verður innfellt):',
	'proofreadpage_footer' => 'Fótur (ekki innifalið):',
	'proofreadpage_toggleheaders' => 'fela hluti sem ekki verða innfelldir',
	'proofreadpage_quality0_category' => 'Án texta',
	'proofreadpage_quality1_category' => 'Ekki villulesnar síður',
	'proofreadpage_quality2_category' => 'Vandræðasíður',
	'proofreadpage_quality3_category' => 'Villulesnar síður',
	'proofreadpage_quality4_category' => 'Staðfestar síður',
	'proofreadpage_quality0_message' => 'Ekki þarf að villulesa þessa síðu',
	'proofreadpage_quality1_message' => 'Þessi síða hefur ekki verið villulesin',
	'proofreadpage_quality2_message' => 'Ekki var hægt að villulesa síðuna vegna vandamála',
	'proofreadpage_quality3_message' => 'Þessi síða hefur verið villulesin',
	'proofreadpage_quality4_message' => 'Þessi síða hefur verið staðfest',
	'proofreadpage_index_status' => 'Staða frumrits',
	'proofreadpage_index_size' => 'Fjöldi blaðsíðna',
	'proofreadpage_specialpage_label_orderby' => 'Raða eftir:',
	'proofreadpage_specialpage_label_key' => 'Leita:',
	'proofreadpage_specialpage_label_sortascending' => 'Raða í hækkandi röð',
	'proofreadpage_alphabeticalorder' => 'Stafrófsröð',
	'proofreadpage_index_listofpages' => 'Listi yfir síður',
	'proofreadpage_image_message' => 'Tengill á frumritssíðu',
	'proofreadpage_page_status' => 'Staða síðu',
	'proofreadpage_js_attributes' => 'Höfundur Titill Ár Útgefandi',
	'proofreadpage_index_attributes' => 'Höfundur
Titill
Ár|Útgáfuár
Útgefandi
Heimild
Myndir|Forsíðumynd
Síður||20
Tileinkanir||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|síða|síður}}',
	'proofreadpage_specialpage_legend' => 'Leita á frumritssíðum',
	'proofreadpage_specialpage_searcherror' => 'Villa í leitarvél',
	'proofreadpage_specialpage_searcherrortext' => 'Leitarvélin virkar ekki í augnablikinu. Afsakið.',
	'proofreadpage_source' => 'Frumrit',
	'proofreadpage_source_message' => 'Skannað frumrit sem þessi texti byggir á',
	'right-pagequality' => 'Breyta gæðamarkinu',
	'proofreadpage-section-tools' => 'Prófarkarlestursverkfæri',
	'proofreadpage-group-zoom' => 'Þysja',
	'proofreadpage-group-other' => 'Annað',
	'proofreadpage-button-toggle-visibility-label' => 'Sýna/fela haus og fót þessarar síðu',
	'proofreadpage-button-zoom-out-label' => 'þysja út',
	'proofreadpage-button-reset-zoom-label' => 'Upphafleg stærð',
	'proofreadpage-button-zoom-in-label' => 'Þysja inn',
	'proofreadpage-button-toggle-layout-label' => 'Lóðrétt/lárétt útlit',
	'proofreadpage-preferences-showheaders-label' => 'Sýna textabox fyrir haus og fót þegar unnið er í {{ns:page}}-nafnarýminu.',
	'proofreadpage-preferences-horizontal-layout-label' => 'Nota lóðrétt útlit þegar unnið er í {{ns:page}}-nafnarýminu',
	'proofreadpage-indexoai-repositoryName' => 'Lýsigögn bóka frá {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Lýsigögn bóka sem stjórnað er með ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Fann enga gerðarlýsingu.',
	'proofreadpage-indexoai-error-schemanotfound-text' => '$1-gerðarlýsingin fannst ekki.',
);

/** Italian (italiano)
 * @author Beta16
 * @author BrokenArrow
 * @author Candalua
 * @author Civvì
 * @author Darth Kule
 * @author F. Cosoleto
 * @author Gianfranco
 * @author Stefano-c
 */
$messages['it'] = array(
	'indexpages' => 'Elenco delle pagine di indice',
	'pageswithoutscans' => 'Pagine senza scansioni',
	'proofreadpage_desc' => 'Consente un facile confronto tra un testo e la sua scansione originale',
	'proofreadpage_image' => 'Immagine',
	'proofreadpage_index' => 'Indice',
	'proofreadpage_index_expected' => 'Errore: previsto indice',
	'proofreadpage_nosuch_index' => 'Errore: indice non presente',
	'proofreadpage_nosuch_file' => 'Errore: file non presente',
	'proofreadpage_badpage' => 'Formato errato',
	'proofreadpage_badpagetext' => 'Il formato della pagina che si è tentato di salvare non è corretto.',
	'proofreadpage_indexdupe' => 'Collegamento duplicato',
	'proofreadpage_indexdupetext' => 'Le pagine non possono essere elencate più di una volta su una pagina di indice.',
	'proofreadpage_nologin' => 'Accesso non effettuato',
	'proofreadpage_nologintext' => "Per modificare lo stato di verifica delle pagine, è necessario aver effettuato [[Special:UserLogin|l'accesso]].",
	'proofreadpage_notallowed' => 'Modifica non consentita',
	'proofreadpage_notallowedtext' => 'Non sei autorizzato a modificare lo stato di verifica di questa pagina.',
	'proofreadpage_dataconfig_badformatted' => 'Problema nella configurazione dei dati',
	'proofreadpage_dataconfig_badformattedtext' => 'La pagina [[Mediawiki:Proofreadpage index data config]] non è in un formato JSON corretto.',
	'proofreadpage_number_expected' => 'Errore: previsto valore numerico',
	'proofreadpage_interval_too_large' => 'Errore: intervallo troppo ampio',
	'proofreadpage_invalid_interval' => 'Errore: intervallo non valido',
	'proofreadpage_nextpage' => 'Pagina successiva',
	'proofreadpage_prevpage' => 'Pagina precedente',
	'proofreadpage_header' => 'Intestazione (non inclusa):',
	'proofreadpage_body' => 'Corpo della pagina (da includere):',
	'proofreadpage_footer' => 'Piè di pagina (non incluso)',
	'proofreadpage_toggleheaders' => 'attiva/disattiva la visibilità delle sezioni non incluse',
	'proofreadpage_quality0_category' => 'Senza testo',
	'proofreadpage_quality1_category' => 'Da correggere',
	'proofreadpage_quality2_category' => 'Da rivedere',
	'proofreadpage_quality3_category' => 'Corretta',
	'proofreadpage_quality4_category' => 'Verificata',
	'proofreadpage_quality0_message' => 'Questa pagina non necessita di essere corretta',
	'proofreadpage_quality1_message' => 'Questa pagina non è stata corretta',
	'proofreadpage_quality2_message' => "C'è stato un problema nella correzione di questa pagina",
	'proofreadpage_quality3_message' => 'Questa pagina è stata corretta',
	'proofreadpage_quality4_message' => 'Questa pagina è stata convalidata',
	'proofreadpage_index_status' => 'Stato avanzamento',
	'proofreadpage_index_size' => 'Numero di pagine',
	'proofreadpage_specialpage_label_orderby' => 'Ordina per:',
	'proofreadpage_specialpage_label_key' => 'Ricerca:',
	'proofreadpage_specialpage_label_sortascending' => 'Ordinamento crescente',
	'proofreadpage_alphabeticalorder' => 'Ordine alfabetico',
	'proofreadpage_index_listofpages' => 'Lista delle pagine',
	'proofreadpage_image_message' => 'Collegamento alla pagina indice',
	'proofreadpage_page_status' => 'Status della pagina',
	'proofreadpage_js_attributes' => 'Autore Titolo Anno Editore',
	'proofreadpage_index_attributes' => 'Autore
Titolo
Anno|Anno di pubblicazione
Editore
Fonte
Immagine|Immagine di copertina
Pagine||20
Note||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pagina|pagine}}',
	'proofreadpage_specialpage_legend' => 'Cerca tra le pagine indice',
	'proofreadpage_specialpage_searcherror' => 'Errore nel motore di ricerca',
	'proofreadpage_specialpage_searcherrortext' => "Il motore di ricerca non funziona. Ci scusiamo per l'inconveniente.",
	'proofreadpage_source' => 'Fonte',
	'proofreadpage_source_message' => 'Edizione scansionata utilizzata per ricavare questo testo',
	'right-pagequality' => 'Modificare la qualità della pagina',
	'proofreadpage-section-tools' => 'Strumenti proofread',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Altro',
	'proofreadpage-button-toggle-visibility-label' => 'Mostra/Nascondi intestazione e piè di pagina',
	'proofreadpage-button-zoom-out-label' => 'Zoom indietro',
	'proofreadpage-button-reset-zoom-label' => 'Ripristina zoom',
	'proofreadpage-button-zoom-in-label' => 'Zoom avanti',
	'proofreadpage-button-toggle-layout-label' => 'Layout verticale/orizzontale',
	'proofreadpage-preferences-showheaders-label' => "Mostra l'intestazione ed il piè di pagina durante la modifica nel namespace {{ns:page}}",
	'proofreadpage-preferences-horizontal-layout-label' => 'Usa il layout orizzontale durante la modifica nel namespace {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Metadati dei libri da {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadati dei libri gestiti da ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Schema non trovato',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Lo schema $1 non è stato trovato.',
	'proofreadpage-disambiguationspage' => 'Template:Disambigua',
);

/** Japanese (日本語)
 * @author Fryed-peach
 * @author JtFuruhata
 * @author Likibp
 * @author Schu
 * @author Shirayuki
 * @author 青子守歌
 */
$messages['ja'] = array(
	'indexpages' => '書誌情報ページの一覧',
	'pageswithoutscans' => 'スキャン画像と関連付けていないページ',
	'proofreadpage_desc' => '底本のスキャン画像と写本の本文の比較を容易にさせる',
	'proofreadpage_image' => 'スキャン画像',
	'proofreadpage_index' => '書誌情報',
	'proofreadpage_index_expected' => 'エラー: 書誌情報を入力してください',
	'proofreadpage_nosuch_index' => 'エラー: そのような書誌情報はありません',
	'proofreadpage_nosuch_file' => 'エラー: そのようなファイルはありません',
	'proofreadpage_badpage' => '間違ったフォーマット',
	'proofreadpage_badpagetext' => '保存しようとしたページのフォーマットが正しくありません。',
	'proofreadpage_indexdupe' => '重複したリンク',
	'proofreadpage_indexdupetext' => 'ページ上に複数の書誌情報ページを載せることはできません。',
	'proofreadpage_nologin' => 'ログインしていません',
	'proofreadpage_nologintext' => 'ページの校正ステータスを変更するには[[Special:UserLogin|ログイン]]する必要があります。',
	'proofreadpage_notallowed' => '変更が許可されていません',
	'proofreadpage_notallowedtext' => 'このページの校正ステータスを変更することはできません。',
	'proofreadpage_dataconfig_badformatted' => 'データの構成のエラー',
	'proofreadpage_dataconfig_badformattedtext' => 'ページ [[Mediawiki:Proofreadpage index data config]] は正しい形式の JSON ではありません。',
	'proofreadpage_number_expected' => 'エラー: 半角数字を入力してください',
	'proofreadpage_interval_too_large' => 'エラー: 間隔が大きすぎます',
	'proofreadpage_invalid_interval' => 'エラー: 間隔が無効です',
	'proofreadpage_nextpage' => '次のページ',
	'proofreadpage_prevpage' => '前のページ',
	'proofreadpage_header' => 'ヘッダー (参照読み込みなし):',
	'proofreadpage_body' => 'ページ本体 (参照読み込みあり):',
	'proofreadpage_footer' => 'フッター (参照読み込みなし):',
	'proofreadpage_toggleheaders' => '読み込みしない部分の表示の切り替え',
	'proofreadpage_quality0_category' => '未入力',
	'proofreadpage_quality1_category' => '未校正',
	'proofreadpage_quality2_category' => '問題有',
	'proofreadpage_quality3_category' => '校正済',
	'proofreadpage_quality4_category' => '検証済',
	'proofreadpage_quality0_message' => 'このページを校正する必要はありません。',
	'proofreadpage_quality1_message' => 'このページはまだ校正されていません',
	'proofreadpage_quality2_message' => 'このページを校正する際に問題がありました',
	'proofreadpage_quality3_message' => 'このページは校正済みです',
	'proofreadpage_quality4_message' => 'このページは検証済みです',
	'proofreadpage_index_size' => 'ページ数',
	'proofreadpage_specialpage_label_key' => '検索:',
	'proofreadpage_specialpage_label_sortascending' => '昇順に並べ替え',
	'proofreadpage_alphabeticalorder' => '辞書順',
	'proofreadpage_index_listofpages' => 'ページの一覧',
	'proofreadpage_image_message' => '書誌情報ページへのリンク',
	'proofreadpage_page_status' => '校正の状態',
	'proofreadpage_js_attributes' => '著者 書名 年 出版者',
	'proofreadpage_index_attributes' => '著者
書名
年|出版年
出版者
底本
画像|表紙の画像
ページ||20
注釈||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|ページ}}',
	'proofreadpage_specialpage_legend' => '書誌情報ページの検索',
	'proofreadpage_specialpage_searcherror' => '検索エンジンでのエラー',
	'proofreadpage_source' => '底本',
	'proofreadpage_source_message' => '底本となった出版物等のスキャンデータ',
	'right-pagequality' => 'ページ品質フラグの変更',
	'proofreadpage-section-tools' => '校正ツール',
	'proofreadpage-group-zoom' => 'ズーム',
	'proofreadpage-group-other' => 'その他',
	'proofreadpage-button-toggle-visibility-label' => 'このページのヘッダー/フッターの表示/非表示',
	'proofreadpage-button-zoom-out-label' => '縮小',
	'proofreadpage-button-reset-zoom-label' => '元の大きさ',
	'proofreadpage-button-zoom-in-label' => '拡大',
	'proofreadpage-button-toggle-layout-label' => '垂直方向/水平方向のレイアウト',
	'proofreadpage-preferences-showheaders-label' => '{{ns:Page}}名前空間での編集中にヘッダー/フッター フィールドを表示',
	'proofreadpage-preferences-horizontal-layout-label' => '{{ns:page}}名前空間の編集の際に、水平レイアウトを使用',
	'proofreadpage-indexoai-repositoryName' => '{{SITENAME}}からの書籍のメタデータ',
	'proofreadpage-indexoai-eprint-content-text' => 'ProofreadPage で管理されている書籍のメタデータ',
	'proofreadpage-indexoai-error-schemanotfound' => 'XML スキーマが見つかりません',
	'proofreadpage-indexoai-error-schemanotfound-text' => '$1 スキーマが見つかりませんでした。',
	'proofreadpage-disambiguationspage' => 'Template:Aimai',
);

/** Jutish (jysk)
 * @author Huslåke
 * @author Ælsån
 */
$messages['jut'] = array(
	'proofreadpage_desc' => 'Kan semple ándrenger der tekst til æ original sken',
	'proofreadpage_image' => 'Billet',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_nextpage' => 'Følgende pæge',
	'proofreadpage_prevpage' => 'Førge pæge',
	'proofreadpage_header' => 'Åverskreft (noinclude):',
	'proofreadpage_body' => 'Pæge kåm (til være transkluded):',
	'proofreadpage_footer' => 'Fåt (noinclude):',
	'proofreadpage_toggleheaders' => 'toggle noinclude seksje sænhvårdeghed',
	'proofreadpage_quality1_category' => 'Ekke sæn',
	'proofreadpage_quality2_category' => 'Pråblæmåtisk',
	'proofreadpage_quality3_category' => 'Sæn',
	'proofreadpage_quality4_category' => 'Vålidærn',
	'proofreadpage_index_listofpages' => 'Liste der pæger',
	'proofreadpage_image_message' => 'Link til æ indeks pæge',
	'proofreadpage_page_status' => 'Pægeståt',
	'proofreadpage_js_attributes' => 'Skrever Titel År Udgæver',
	'proofreadpage_index_attributes' => 'Skrever
Titel
År|År der publikåsje
Udgæver
Sårs
Billet|Førkåntsbillet
Strøk||20
Anmarker||10',
);

/** Javanese (Basa Jawa)
 * @author Bennylin
 * @author Meursault2004
 * @author NoiX180
 */
$messages['jv'] = array(
	'indexpages' => 'Daptar kaca indèks',
	'pageswithoutscans' => 'Kaca tanpa pindéan',
	'proofreadpage_desc' => "Supaya prabandhingan karo asliné sing di-''scan'' luwih gampang",
	'proofreadpage_image' => 'Gambar',
	'proofreadpage_index' => 'Indèks',
	'proofreadpage_index_expected' => 'Kasalahan: mbutuhaké indèks',
	'proofreadpage_nosuch_index' => 'Kasalahan: ora ana indèks',
	'proofreadpage_nosuch_file' => 'Kasalahan: ora ana berkas',
	'proofreadpage_badpage' => 'Format Salah',
	'proofreadpage_badpagetext' => 'Format kaca sing arep Sampéyan simpen ora bener.',
	'proofreadpage_indexdupe' => 'Gandhakaké pranala',
	'proofreadpage_indexdupetext' => 'Kaca ora bisa didaptar punjul saka siji nèng kaca indeks.',
	'proofreadpage_nologin' => 'Durung mlebu log',
	'proofreadpage_nologintext' => 'Sampéyan kudu [[Special:UserLogin|mlebu log]] kanggo ngowah status korèksi kaca.',
	'proofreadpage_notallowed' => 'Owahan ora dililakaké',
	'proofreadpage_notallowedtext' => 'Sampéyan ora dililakaké ngowah status korèksi kaca iki.',
	'proofreadpage_dataconfig_badformatted' => 'Ana kesalahan ing konfigurasi data',
	'proofreadpage_dataconfig_badformattedtext' => 'Kaca [[Mediawiki:Proofreadpage index data config]] ora diformat nganggo JSON.',
	'proofreadpage_number_expected' => 'Kasalahan: isi mawa angka',
	'proofreadpage_interval_too_large' => 'Kasalahan slisih kegedhèn',
	'proofreadpage_invalid_interval' => 'Kasalah interval ora sah',
	'proofreadpage_nextpage' => 'Kaca sabanjuré',
	'proofreadpage_prevpage' => 'Kaca sadurungé',
	'proofreadpage_header' => 'Sesirah (noinclude):',
	'proofreadpage_body' => 'Awak kaca (kanggo transklusi):',
	'proofreadpage_footer' => 'Tulisan sikil (noinclude):',
	'proofreadpage_toggleheaders' => 'ganti visibilitas (kakatonan) bagéyan noinclude',
	'proofreadpage_quality0_category' => 'Tanpa tèks',
	'proofreadpage_quality1_category' => 'Durung dikorèksi tulisané',
	'proofreadpage_quality2_category' => 'Problématis',
	'proofreadpage_quality3_category' => 'Korèksi tulisan',
	'proofreadpage_quality4_category' => 'Diabsahaké',
	'proofreadpage_quality0_message' => 'Kaca iki ora butuh dikorèksi',
	'proofreadpage_quality1_message' => 'Kaca iki durung dikorèksi',
	'proofreadpage_quality2_message' => 'Ana masalah nalika ngorèksi kaca iki',
	'proofreadpage_quality3_message' => 'Kaca iki wis dikorèksi',
	'proofreadpage_quality4_message' => 'Kaca iki wis divalidasi',
	'proofreadpage_index_status' => 'Status indeks',
	'proofreadpage_index_size' => 'Cacahing kacak',
	'proofreadpage_specialpage_label_orderby' => 'Urutaké miturut:',
	'proofreadpage_specialpage_label_key' => 'Golèk',
	'proofreadpage_specialpage_label_sortascending' => 'Urutaké munggah',
	'proofreadpage_alphabeticalorder' => 'Urutan alfabetis',
	'proofreadpage_index_listofpages' => 'Daftar kaca',
	'proofreadpage_image_message' => 'Pranala menyang kaca indèks',
	'proofreadpage_page_status' => 'Status kaca',
	'proofreadpage_js_attributes' => 'Pangripta Irah-irahan Taun Panerbit',
	'proofreadpage_index_attributes' => 'Pangripta
Irah-irahan
Taun|Taun olèhe mbabar
Panerbit
Sumber
Gambar|Gambar samak
Kaca||20
Cathetan||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$2|kaca|kaca}}', # Fuzzy
	'proofreadpage_specialpage_legend' => 'Golèk kaca indeks',
	'proofreadpage_specialpage_searcherror' => 'Kasalahan nèng mesin panggolèk',
	'proofreadpage_specialpage_searcherrortext' => 'Mesin panggolèk ora mlaku. Ngapunten sanget.',
	'proofreadpage_source' => 'Sumber',
	'proofreadpage_source_message' => 'Vèrsi kapindé sing dianggo kanggo nggawé tèks iki',
	'right-pagequality' => 'Owah tandha mutu kaca',
	'proofreadpage-section-tools' => 'Piranti koreksi',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Liya',
	'proofreadpage-button-toggle-visibility-label' => 'Tuduhaké/dhelikaké irah-irahan lan sikil kaca iki',
	'proofreadpage-button-zoom-out-label' => 'Cilikaké',
	'proofreadpage-button-reset-zoom-label' => 'Gedhé asli',
	'proofreadpage-button-zoom-in-label' => 'Gedhèkaké',
	'proofreadpage-button-toggle-layout-label' => 'Tata sèlèh vertikal/horizontal',
	'proofreadpage-preferences-showheaders-label' => 'Tuduhaké bidhang irah-irahan lan sikil nalika nyunting nèng bilik jeneng {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Anggo tata sèlèh horizontal nalika nyunting nèng bilik jeneng {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Metadata saka buku saka {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadata buku-buku nganggo ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Skema ora ketemu',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Skema $1 ora ketemu.',
);

/** Georgian (ქართული)
 * @author BRUTE
 * @author David1010
 * @author Dawid Deutschland
 * @author ITshnik
 * @author Malafaya
 * @author Sopho
 * @author გიორგიმელა
 */
$messages['ka'] = array(
	'indexpages' => 'მთავარი გვერდების სია',
	'pageswithoutscans' => 'გვერდები სკანების გარეშე',
	'proofreadpage_desc' => 'საშუალებას იძლევა კომფორტულად შეადაროთ ტექსტი ორიგინალის დასკანერებული სურათი',
	'proofreadpage_image' => 'სურათი',
	'proofreadpage_index' => 'ინდექსი',
	'proofreadpage_index_expected' => 'შეცდომა: ინდექსი არ არის ნაპოვნი',
	'proofreadpage_nosuch_index' => 'შეცდომა: ასეთი ინდექსი არ არის ნაპოვნი',
	'proofreadpage_nosuch_file' => 'შეცდომა:ასეთი ფაილი არ არის ნაპოვნი',
	'proofreadpage_badpage' => 'არასწორი ფორმატი',
	'proofreadpage_badpagetext' => 'ფორმატი გვერდისა, რომლის შენახვაც თქვენ ცადეთ, არასწორია.',
	'proofreadpage_indexdupe' => 'დუბლიკატი ბმული',
	'proofreadpage_indexdupetext' => 'ინდექსის გვერდზე არ შეიძლება გვერდები ჩამოთვლილი იყოს ერთზე მეტჯერ.',
	'proofreadpage_nologin' => 'შესვლა არ მომხდარა',
	'proofreadpage_nologintext' => 'თქვენ უნდა [[Special:UserLogin|შეხვიდეთ სისტემაში]] სტატიების კორექტურის სტატუსის შესაცვლელად.',
	'proofreadpage_notallowed' => 'ცვლილებები არაა დაშვებული',
	'proofreadpage_notallowedtext' => 'თქვენ არ შეგიძლიათ ამ გვერდის კორექტურის სტატუსის შეცვლა.',
	'proofreadpage_dataconfig_badformatted' => 'შეცდომა კონფიგურაციის მონაცემებში',
	'proofreadpage_dataconfig_badformattedtext' => 'გვერდი [[Mediawiki:Proofreadpage index data config]] არ არის კარგად ჩამოყალიბებული JSON.',
	'proofreadpage_number_expected' => 'შეცდომა: რიცხვითი მნიშვნელობის ლოდინი',
	'proofreadpage_interval_too_large' => 'შეცდომა: ინტერვალი ძალიან დიდია',
	'proofreadpage_invalid_interval' => 'შეცდომა: არასწორი ინტერვალი',
	'proofreadpage_nextpage' => 'შემდეგი გვერდი',
	'proofreadpage_prevpage' => 'წინა გვერდი',
	'proofreadpage_header' => 'სათაური (არ შეიცავს):',
	'proofreadpage_body' => 'გვერდის სხეული (მოიცავს):',
	'proofreadpage_footer' => 'ქვედა (არ შეიცავს):',
	'proofreadpage_toggleheaders' => 'მოუცველი განყოფილებების ჩვენება',
	'proofreadpage_quality0_category' => 'ტექსტის გარეშე',
	'proofreadpage_quality1_category' => 'არაკორექტირებული',
	'proofreadpage_quality2_category' => 'პრობლემატური',
	'proofreadpage_quality3_category' => 'შესწორდა',
	'proofreadpage_quality4_category' => 'შემოწმებული',
	'proofreadpage_quality0_message' => 'ეს გვერდი არ საჭიროებს კორექტურას',
	'proofreadpage_quality1_message' => 'ეს გვერდი არ იყო კორექტირებული',
	'proofreadpage_quality2_message' => 'ამ გვერდის კორექტირებისას პრობლემებია',
	'proofreadpage_quality3_message' => 'ეს გვერდი კორექტირებულია',
	'proofreadpage_quality4_message' => 'ეს გვერდი დამოწმებულია',
	'proofreadpage_index_status' => 'სტატუსის ინდექსი',
	'proofreadpage_index_size' => 'გვერდების რაოდენობა',
	'proofreadpage_specialpage_label_orderby' => 'სორტირება:',
	'proofreadpage_specialpage_label_key' => 'ძიება:',
	'proofreadpage_specialpage_label_sortascending' => 'ზრდის მიხედვით დალაგება',
	'proofreadpage_alphabeticalorder' => 'ანბანური თანმიმდევრობა',
	'proofreadpage_index_listofpages' => 'გვერდების სია',
	'proofreadpage_image_message' => 'ბმული ინდექსის გვერდზე',
	'proofreadpage_page_status' => 'გვერდის სტატუსი',
	'proofreadpage_js_attributes' => 'ავტორი სათაური წელი გამომცემელი',
	'proofreadpage_index_attributes' => 'ავტორი
სათაური
წელი|გამოცემის წელი
გამომცემელი
წყარო
გამოსახულება|ყდის გამოსახულება
გვერდები||20
შენიშვნები||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|გვერდი|გვერდები}}',
	'proofreadpage_specialpage_legend' => 'ინდექსური გვერდების ძიება',
	'proofreadpage_specialpage_searcherror' => 'შეცდომა საძიებო სისტემაში',
	'proofreadpage_specialpage_searcherrortext' => 'ეს საძიებო სისტემა არ მუშაობს. ბოდიშს გიხდით შექმნილი უხერხულობისათვის.',
	'proofreadpage_source' => 'წყარო',
	'proofreadpage_source_message' => 'ტექსტის ელექტრონული ვერსიის შესაქმნელად გამოყენებულია დასკანერებული მასალები',
	'right-pagequality' => 'გვერდის ხარისხის დროშის შეცვლა',
	'proofreadpage-section-tools' => 'კორექტურის ხელსაწყოები',
	'proofreadpage-group-zoom' => 'გადიდება',
	'proofreadpage-group-other' => 'სხვა',
	'proofreadpage-button-toggle-visibility-label' => 'ამ გვერდის ზედა და ქვედა ნაწილების ჩვენება/დამალვა',
	'proofreadpage-button-zoom-out-label' => 'დაპატარავება',
	'proofreadpage-button-reset-zoom-label' => 'თავდაპირველი ზომა',
	'proofreadpage-button-zoom-in-label' => 'გადიდება',
	'proofreadpage-button-toggle-layout-label' => 'ვერტიკალური/ჰორიზონტალური განლაგება',
	'proofreadpage-preferences-showheaders-label' => 'სახელთა სივრცის {{ns:გვერდის}} რედაქტირებისას აჩვენე ზედა და ქვედა ველები.',
	'proofreadpage-preferences-horizontal-layout-label' => 'ჰორიზონტალური განლაგების გამოყენება {{ns:page}} სახელთა სივრცის რედაქტირებისას',
	'proofreadpage-indexoai-repositoryName' => 'წიგნების მეტამონაცემები საიტისათვის {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'წიგნების მეტამონაცემები დალაგებულია წაკითხვის მიხედვით.',
	'proofreadpage-indexoai-error-schemanotfound' => 'სქემა ვერ მოიძებნა',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'სქემა „$1“ ვერ მოიძებნა.',
);

/** Khowar (کھوار)
 * @author Rachitrali
 */
$messages['khw'] = array(
	'indexpages' => 'صفحاتن لسٹ',
	'pageswithoutscans' => 'اسکین نو کاردو صفحات',
	'proofreadpage_image' => 'ھوٹو',
	'proofreadpage_index' => 'فھرست',
	'proofreadpage_badpage' => 'غلطو شکل',
	'proofreadpage_indexdupe' => 'ڈپلیکیٹ لنک',
	'proofreadpage_nologin' => 'لاگ ان نو',
	'proofreadpage_nextpage' => 'نوغ صفحہ',
	'proofreadpage_prevpage' => 'سابقہ صفحہ',
	'proofreadpage_quality0_category' => 'ٹیکسٹو سار غیر',
	'proofreadpage_quality1_category' => 'پروف ریڈنگ نو کاردو',
	'proofreadpage_quality3_category' => 'پروف خوانی',
	'proofreadpage_index_size' => 'فی صفحہ لمبار:',
	'proofreadpage_specialpage_label_key' => 'Search/تلاش',
	'proofreadpage_specialpage_legend' => 'فھرست صفحات تلاش کورے',
	'proofreadpage-group-zoom' => 'زووم',
	'proofreadpage-group-other' => 'دیگر/خور',
	'proofreadpage-button-zoom-out-label' => 'زوم آوٹ/Zoom out',
	'proofreadpage-button-reset-zoom-label' => 'اصل سایز',
	'proofreadpage-button-zoom-in-label' => 'زوم ان/Zoom in',
	'proofreadpage-button-toggle-layout-label' => 'تھروسکی لے آوٹ',
);

/** Kirmanjki (Kırmancki)
 * @author Erdemaslancan
 * @author Mirzali
 */
$messages['kiu'] = array(
	'indexpages' => 'Lista pelunê zerreki',
	'pageswithoutscans' => 'Pelê ke fetelayisê cı çino',
	'proofreadpage_desc' => 'Destur bıde wa nuşte pê fetelayisê oricinali asan têver saniyo',
	'proofreadpage_image' => 'Vêne',
	'proofreadpage_index' => 'Zerrek',
	'proofreadpage_index_expected' => 'Xeta: zerrek piyino',
	'proofreadpage_nosuch_index' => 'Xeta: zerreko nianên çino',
	'proofreadpage_nosuch_file' => 'Xeta: dosya nianêne çina',
	'proofreadpage_badpage' => 'Formato Xırabın',
	'proofreadpage_badpagetext' => 'Formatê pela ke sıma wazenê qeyd kerê ğeleto.',
	'proofreadpage_indexdupe' => 'Gire beno jêde',
	'proofreadpage_indexdupetext' => 'Peli zerrê pela zerreki de jü ra jêde rêz nêbenê.',
	'proofreadpage_nologin' => 'Cı nêkota',
	'proofreadpage_nologintext' => 'Qey vurnayişê halê raştkerdışê pelan gani şıma [[Special:UserLogin|cı kewiyi]].',
	'proofreadpage_notallowed' => 'Vurnayiş re destur çino',
	'proofreadpage_notallowedtext' => 'Vurnayişê halê raştkerdışê peli re destur nêdano',
	'proofreadpage_number_expected' => 'Xeta:Amarin weziyet pawéno',
	'proofreadpage_interval_too_large' => 'Xeta: benate/mabên zaf hêrayo',
	'proofreadpage_invalid_interval' => 'Xeta: benateyo nemeqbul',
	'proofreadpage_nextpage' => 'Pela badê cû',
	'proofreadpage_prevpage' => 'Pelo ke pey de mend',
	'proofreadpage_header' => 'Sername (ihtiwa):',
	'proofreadpage_body' => 'Miyaneyê peli (çepraşt têarê beno):',
	'proofreadpage_footer' => 'Bın zanışe (ihtiwa):',
	'proofreadpage_toggleheaders' => 'asayişê qısmi yê ke ihtiwa nıbeni bıvurn',
	'proofreadpage_quality0_category' => 'Metn tede çino',
	'proofreadpage_quality1_category' => 'Raşt nıbiyo',
	'proofreadpage_quality2_category' => 'Problemın',
	'proofreadpage_quality3_category' => 'Timar ke',
	'proofreadpage_quality4_category' => 'Raşt/tesdiq biyo',
	'proofreadpage_quality0_message' => 'No pel re raştkerdış luzûm nıkeno',
	'proofreadpage_quality1_message' => 'No pel de reaştkerdış nıbı',
	'proofreadpage_quality2_message' => 'Wexta no pel de raştkerdış bêne xeta vıraziya',
	'proofreadpage_quality3_message' => 'No pel de raştkerdış bı',
	'proofreadpage_quality4_message' => 'No pel raşt/tesdiq biyo',
	'proofreadpage_index_size' => 'Amariya pelan',
	'proofreadpage_index_listofpages' => 'Listeya pelan',
	'proofreadpage_image_message' => 'Gıreyo ke erziyayo pelê endeksi',
	'proofreadpage_page_status' => 'halê peli',
	'proofreadpage_js_attributes' => 'Nuştox/e sername serre weşanger',
	'proofreadpage_index_attributes' => 'nuştox/e
sername
serre|serrê weşanayişi/neşri
weşanger
çıme
Resım|resmê qapaxi
peli||20
beyanati||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pele|peli}}',
	'proofreadpage_specialpage_legend' => 'Bıgêr pelê indeksan',
	'proofreadpage_source' => 'Çıme',
	'proofreadpage_source_message' => 'Versiyono kopyakerde gurêna ke nê meqaley rono',
	'right-pagequality' => 'Vurnayışté pela ré desmal çek',
	'proofreadpage-section-tools' => 'Hacetê rastkerdena ğeletu',
	'proofreadpage-group-zoom' => 'Nêjdikerdene',
	'proofreadpage-group-other' => 'Zobi',
	'proofreadpage-button-toggle-visibility-label' => 'Ena pelaya bımocne/bınımni  wanena u asınena',
	'proofreadpage-button-zoom-out-label' => 'Düri ke',
	'proofreadpage-button-reset-zoom-label' => 'Ebado oricinal',
	'proofreadpage-button-zoom-in-label' => 'Nêjdi ke',
	'proofreadpage-button-toggle-layout-label' => 'Kewtey/tikey  asayış',
	'proofreadpage-preferences-showheaders-label' => 'Nameye pela çı vurneyeno heqa cı wendış u asayışi bımocne.', # Fuzzy
);

/** Khmer (ភាសាខ្មែរ)
 * @author Chhorran
 * @author Lovekhmer
 * @author Thearith
 * @author គីមស៊្រុន
 * @author វ័ណថារិទ្ធ
 */
$messages['km'] = array(
	'proofreadpage_image' => 'រូបភាព',
	'proofreadpage_index' => 'លិបិក្រម',
	'proofreadpage_badpage' => 'ទម្រង់​/ប្រភេទ មិនត្រឹមត្រូវ​​',
	'proofreadpage_indexdupe' => 'ចម្លងស្ទួន តំណ​ភ្ជាប់',
	'proofreadpage_nextpage' => 'ទំព័របន្ទាប់',
	'proofreadpage_prevpage' => 'ទំព័រមុន',
	'proofreadpage_header' => 'បឋមកថា(មិនរួមបញ្ចូល)៖',
	'proofreadpage_footer' => 'បាតកថា(មិនរួមបញ្ចូល)៖',
	'proofreadpage_quality0_category' => 'ដោយ​មិន​មាន​អក្សរ​',
	'proofreadpage_quality1_category' => 'មិន​មើលកែ',
	'proofreadpage_quality2_category' => 'មានបញ្ហា',
	'proofreadpage_quality3_category' => 'មើលកែ',
	'proofreadpage_quality4_category' => 'បាន​ធ្វើឱ្យមានសុពលភាព',
	'proofreadpage_index_listofpages' => 'បញ្ជីទំព័រ',
	'proofreadpage_image_message' => 'ភ្ជាប់ទៅទំព័រលិបិក្រម',
	'proofreadpage_page_status' => 'ស្ថានភាព ទំព័រ',
	'proofreadpage_js_attributes' => 'អ្នកនិពន្ធ ចំណងជើង ឆ្នាំបោះពុម្ព រោងពុម្ព',
	'proofreadpage_index_attributes' => 'អ្នកនិពន្ឋ
ចំណងជើង
ឆ្នាំ|ឆ្នាំបោះពុម្ព
គ្រឹះស្ថានបោះពុម្ព
ប្រភព
រូបភាព|រូបភាពលើក្រប
ទំព័រ||២០
កំណត់សម្គាល់||១០',
);

/** Kannada (ಕನ್ನಡ)
 * @author Nayvik
 * @author Omshivaprakash
 */
$messages['kn'] = array(
	'proofreadpage_image' => 'ಚಿತ್ರ',
	'proofreadpage_index' => 'Index',
	'proofreadpage_nextpage' => 'ಮುಂದಿನ ಪುಟ',
	'proofreadpage_prevpage' => 'ಹಿಂದಿನ ಪುಟ',
	'proofreadpage_pages' => '{{PLURAL:$1|ಪುಟ|ಪುಟಗಳು}}', # Fuzzy
);

/** Korean (한국어)
 * @author Ilovesabbath
 * @author Klutzy
 * @author Kwj2772
 * @author Pakman
 * @author ToePeu
 * @author Yknok29
 * @author 아라
 */
$messages['ko'] = array(
	'indexpages' => '목차 문서의 목록',
	'pageswithoutscans' => '스캔본이 없는 문서',
	'proofreadpage_desc' => '운래 스캔과 텍스트를 쉽게 비교할 수 있습니다',
	'proofreadpage_image' => '그림',
	'proofreadpage_index' => '목차',
	'proofreadpage_index_expected' => '오류: 목차가 있어야 합니다.',
	'proofreadpage_nosuch_index' => '오류: 해당 목차가 없습니다.',
	'proofreadpage_nosuch_file' => '오류: 해당 파일이 없습니다.',
	'proofreadpage_badpage' => '잘못된 형식',
	'proofreadpage_badpagetext' => '저장하려 한 문서의 포맷이 올바르지 않습니다.',
	'proofreadpage_indexdupe' => '중복된 링크',
	'proofreadpage_indexdupetext' => '문서가 목차 문서에 한 번 이상 올라올 수 없습니다.',
	'proofreadpage_nologin' => '로그인하지 않음',
	'proofreadpage_nologintext' => '문서의 교정 상태를 수정하려면 [[Special:UserLogin|로그인]]해야 합니다.',
	'proofreadpage_notallowed' => '이 문서는 바꾸기가 불가능합니다.',
	'proofreadpage_notallowedtext' => '이 문서의 교정 상태를 바꿀 수 없습니다.',
	'proofreadpage_dataconfig_badformatted' => '데이터 구성의 버그',
	'proofreadpage_dataconfig_badformattedtext' => '[[Mediawiki:Proofreadpage index data config]] 문서는 올바른 형식의 JSON이 없습니다.',
	'proofreadpage_number_expected' => '오류: 숫자 값을 입력해야 합니다.',
	'proofreadpage_interval_too_large' => '오류: 간격이 너무 큽니다.',
	'proofreadpage_invalid_interval' => '오류: 간격이 잘못되었습니다.',
	'proofreadpage_nextpage' => '다음 문서',
	'proofreadpage_prevpage' => '이전 문서',
	'proofreadpage_header' => '머리말 (표시안함):',
	'proofreadpage_body' => '본문 (트랜스클루전):',
	'proofreadpage_footer' => '꼬리말 (표시안함):',
	'proofreadpage_toggleheaders' => '표시안함 부분의 표시 여부 선택',
	'proofreadpage_quality0_category' => '비었음',
	'proofreadpage_quality1_category' => '교정 안됨',
	'proofreadpage_quality2_category' => '문제 있음',
	'proofreadpage_quality3_category' => '교정',
	'proofreadpage_quality4_category' => '확인됨',
	'proofreadpage_quality0_message' => '이 문서는 교정할 필요가 없습니다.',
	'proofreadpage_quality1_message' => '이 문서는 아직 교정을 보지 않았습니다.',
	'proofreadpage_quality2_message' => '이 문서를 교정하는 중 문제가 있었습니다.',
	'proofreadpage_quality3_message' => '이 문서는 교정 작업을 거쳤습니다.',
	'proofreadpage_quality4_message' => '이 문서는 검증되었습니다.',
	'proofreadpage_index_status' => '색인 상테',
	'proofreadpage_index_size' => '문서 수',
	'proofreadpage_specialpage_label_orderby' => '정렬 기준:',
	'proofreadpage_specialpage_label_key' => '찾기:',
	'proofreadpage_specialpage_label_sortascending' => '오름차순 정렬',
	'proofreadpage_alphabeticalorder' => '알파벳순 정렬',
	'proofreadpage_index_listofpages' => '문서 목록',
	'proofreadpage_image_message' => '목차 페이지로',
	'proofreadpage_page_status' => '문서 상태',
	'proofreadpage_js_attributes' => '저자 제목 출판년도 출판사',
	'proofreadpage_index_attributes' => '저자
제목
연도|출판년도
출판사
출처
그림|표지 그림
쪽수||20
주석||10',
	'proofreadpage_pages' => '{{PLURAL:$1|문서}} $2개',
	'proofreadpage_specialpage_legend' => '목차 문서 찾기',
	'proofreadpage_specialpage_searcherror' => '검색 엔진 오류',
	'proofreadpage_specialpage_searcherrortext' => '검색 엔진이 작동하지 않습니다. 불편을 끼쳐 죄송합니다.',
	'proofreadpage_source' => '출처',
	'proofreadpage_source_message' => '이 글을 작성할 때 사용된 스캔본',
	'right-pagequality' => '문서 품질 태그 수정하기',
	'proofreadpage-section-tools' => '교정 도구',
	'proofreadpage-group-zoom' => '확대/축소',
	'proofreadpage-group-other' => '기타',
	'proofreadpage-button-toggle-visibility-label' => '이 문서의 머리말과 꼬리말을 보이기/숨기기',
	'proofreadpage-button-zoom-out-label' => '축소',
	'proofreadpage-button-reset-zoom-label' => '원본 크기',
	'proofreadpage-button-zoom-in-label' => '확대',
	'proofreadpage-button-toggle-layout-label' => '수직/수평 레이아웃',
	'proofreadpage-preferences-showheaders-label' => '{{ns:page}} 이름공간에서 편집할 때 머릿글 및 바닥글 필드 보이기',
	'proofreadpage-preferences-horizontal-layout-label' => '{{ns:page}} 이름공간에서 편집할 때 수평 레이아웃 사용',
	'proofreadpage-indexoai-repositoryName' => '{{SITENAME}} 책의 메타데이터',
	'proofreadpage-indexoai-eprint-content-text' => 'PoofreadPage가 관리하는 책의 메타데이터입니다.',
	'proofreadpage-indexoai-error-schemanotfound' => '스키마를 찾을 수 없음',
	'proofreadpage-indexoai-error-schemanotfound-text' => '$1 스키마를 찾지 못했습니다.',
	'proofreadpage-disambiguationspage' => 'Template:동음이의',
);

/** Kinaray-a (Kinaray-a)
 * @author Jose77
 */
$messages['krj'] = array(
	'proofreadpage_namespace' => 'Pahina',
);

/** Colognian (Ripoarisch)
 * @author Purodha
 */
$messages['ksh'] = array(
	'indexpages' => 'Leß met de Verzeischneß_Sigge',
	'pageswithoutscans' => 'Sigge ohne Belder',
	'proofreadpage_desc' => 'Määd et möjjelesch, bequem der Täx mem enjeskännte Ojinaal ze verjliische.',
	'proofreadpage_image' => 'Beld',
	'proofreadpage_index' => 'Verzeischneß',
	'proofreadpage_index_expected' => 'Fähler: En Enndraachsnummer (ene Indäx) weet jebruch',
	'proofreadpage_nosuch_index' => 'Fähler: Esu en Enndraachsnummer (esu ene Indäx) jidd_et nit',
	'proofreadpage_nosuch_file' => 'Fähler: esu en Dattei ham_mer nit',
	'proofreadpage_badpage' => 'Verkiehrt Fommaat',
	'proofreadpage_badpagetext' => 'Dat Fommaat vun dä Sigg, di De jrahdt afzeshpeischere versöhk häß, eß verkiehert.',
	'proofreadpage_indexdupe' => 'Dubbelte Lengk',
	'proofreadpage_indexdupetext' => 'Sigge künne nit mieh wi eijmohl en en Verzeischneß_Sigg opdouche.',
	'proofreadpage_nologin' => 'Nit enjelogg',
	'proofreadpage_nologintext' => 'Do möötß ald [[Special:UserLogin|enjelogg]] sin, öm dä {{int:proofreadpage_page_status}} hee ze ändere.',
	'proofreadpage_notallowed' => 'Dat Ändere es nit zohjelohße',
	'proofreadpage_notallowedtext' => 'Do häs nit et Rääsch, heh dä {{int:proofreadpage_page_status}} ze ändere.',
	'proofreadpage_number_expected' => 'Fähler: En Zahl weet jebruch',
	'proofreadpage_interval_too_large' => 'Fähler: Dä Affschtand es zoh jruuß',
	'proofreadpage_invalid_interval' => 'Fähler: Dä Afshtand es nit jöltesch',
	'proofreadpage_nextpage' => 'Näx Sigg',
	'proofreadpage_prevpage' => 'Vörije Sigg',
	'proofreadpage_header' => 'Sigge-Kopp (<i lang="en">noinclude</i>):',
	'proofreadpage_body' => 'Tex op dä Sigg (för enzfööje):',
	'proofreadpage_footer' => 'Sigge-Fohß (<i lang="en">noinclude</i>):',
	'proofreadpage_toggleheaders' => '<i lang="en">Noinclude</i>-Afschnedde en- un ußblende',
	'proofreadpage_quality0_category' => 'Leddisch',
	'proofreadpage_quality1_category' => 'Unjeprööf',
	'proofreadpage_quality2_category' => 'Problemscher',
	'proofreadpage_quality3_category' => 'Nohjelässe',
	'proofreadpage_quality4_category' => 'Fäädesch jepröhf',
	'proofreadpage_quality0_message' => 'Heh di Sigg moß nit jeääjejelässe wääde',
	'proofreadpage_quality1_message' => 'Heh di Sigg woodt nit jeääjejelässe',
	'proofreadpage_quality2_message' => 'Beim Jeääjelässe för heh di Sigg eß jät opjevalle',
	'proofreadpage_quality3_message' => 'Heh di Sigg woodt jeääjejelässe',
	'proofreadpage_quality4_message' => 'Heh di Sigg es jeääjejelässe un joot',
	'proofreadpage_index_listofpages' => 'SiggeLeß',
	'proofreadpage_image_message' => 'Lengk op en Verzeischneß_Sigg',
	'proofreadpage_page_status' => 'Siggestattus',
	'proofreadpage_js_attributes' => 'Schriver Tittel Johr Verlaach',
	'proofreadpage_index_attributes' => 'Schriver
Tittel
Johr|ÄscheinungsJohr
Verlaach
Quell
Beld|Beld om Ömschlach
Sigge||20
Aanmerkunge||10',
	'proofreadpage_pages' => '{{PLURAL:$2|Ei&nbsp;Sigg|$1&nbsp;Sigge|Kei&nbsp;Sigg}}',
	'proofreadpage_specialpage_legend' => 'Op dä Verzeischneßsigg söhke',
	'proofreadpage_source' => 'Quell',
	'proofreadpage_source_message' => 'För heh dä Täx ze schriive, wood dat Beld vum Täx jenumme.',
	'right-pagequality' => 'De Qualiteit vun Sigge ändere',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Söns jät',
	'proofreadpage-button-reset-zoom-label' => 'Ojinal-Enschtällong',
);

/** Kurdish (Latin script) (Kurdî (latînî)‎)
 * @author George Animal
 */
$messages['ku-latn'] = array(
	'proofreadpage_image' => 'Wêne',
);

/** Cornish (kernowek)
 * @author Kw-Moon
 */
$messages['kw'] = array(
	'proofreadpage_namespace' => 'Folen',
);

/** Kirghiz (Кыргызча)
 * @author Growingup
 */
$messages['ky'] = array(
	'proofreadpage_nextpage' => 'кийинки барак',
	'proofreadpage_prevpage' => 'мурунку барак',
);

/** Latin (Latina)
 * @author Candalua
 * @author John Vandenberg
 * @author SPQRobin
 * @author UV
 */
$messages['la'] = array(
	'proofreadpage_image' => 'Fasciculus',
	'proofreadpage_index' => 'Liber',
	'proofreadpage_quality0_category' => 'Vacuus',
	'proofreadpage_quality1_category' => 'Nondum emendata',
	'proofreadpage_quality2_category' => 'Emendatio difficilis',
	'proofreadpage_quality3_category' => 'Emendata',
	'proofreadpage_quality4_category' => 'Bis lecta',
	'proofreadpage_quality0_message' => 'Haec pagina emendanda non est',
	'proofreadpage_quality1_message' => 'Haec pagina nondum emendata est',
	'proofreadpage_quality2_message' => 'Emendatio difficilis',
	'proofreadpage_quality3_message' => 'Haec pagina emendata est',
	'proofreadpage_quality4_message' => 'Haec pagina emendata et bis lecta est',
	'proofreadpage_page_status' => 'Paginae status',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pagina|paginae}}',
	'proofreadpage_source' => 'Fons',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'indexpages' => 'Lëscht vun Index-Säiten',
	'pageswithoutscans' => 'Säiten ouni Scan',
	'proofreadpage_desc' => 'Erlaabt et op eng einfach Manéier den Text mat der Originalscan ze vergLäichen',
	'proofreadpage_image' => 'Bild',
	'proofreadpage_index' => 'Index',
	'proofreadpage_index_expected' => 'Feeler: Index erwaart',
	'proofreadpage_nosuch_index' => 'Feeler: et gëtt keen esou een Index',
	'proofreadpage_nosuch_file' => 'Feeler: de Fichier gëtt et net',
	'proofreadpage_badpage' => 'Falsche Format',
	'proofreadpage_badpagetext' => "De Format vun der Säit déi Dir versicht hutt z'änneren ass net korrekt.",
	'proofreadpage_indexdupe' => 'Duebele Link',
	'proofreadpage_indexdupetext' => 'Säite kënnen net méi wéi eemol op eng Index-Säit gesat ginn.',
	'proofreadpage_nologin' => 'Net ageloggt',
	'proofreadpage_nologintext' => "Dir musst [[Special:UserLogin|ageloggt]] si fir de Status vum Iwwerliese vu Säiten z'änneren.",
	'proofreadpage_notallowed' => 'Ännerung net erlaabt',
	'proofreadpage_notallowedtext' => "Dir sidd net berechtigt de Status vum Iwwerliese vun dëser Säit z'änneren.",
	'proofreadpage_dataconfig_badformatted' => 'Feeler an der Datekonfiguratioun',
	'proofreadpage_dataconfig_badformattedtext' => "D'Säit [[Mediawiki:Proofreadpage index data config]] ass net gutt JSON formatéiert.",
	'proofreadpage_number_expected' => 'Feeler: et gouf en numeresche Wäert erwaart',
	'proofreadpage_interval_too_large' => 'Feeler: Intervall ze ze grouss',
	'proofreadpage_invalid_interval' => 'Feeler: net valabelen Intervall',
	'proofreadpage_nextpage' => 'Nächst Säit',
	'proofreadpage_prevpage' => 'Vireg Säit',
	'proofreadpage_header' => 'Entête (noinclude):',
	'proofreadpage_body' => 'Inhalt vun der Säit (Transklusioun):',
	'proofreadpage_footer' => 'Foussnote (noinclude):',
	'proofreadpage_toggleheaders' => 'noinclude-Abschnitter an- resp. ausblenden',
	'proofreadpage_quality0_category' => 'Ouni Text',
	'proofreadpage_quality1_category' => 'Net verbessert',
	'proofreadpage_quality2_category' => 'Problematesch',
	'proofreadpage_quality3_category' => 'Verbessert',
	'proofreadpage_quality4_category' => 'Validéiert',
	'proofreadpage_quality0_message' => 'Dës Säit brauch net iwwerliest ze ginn',
	'proofreadpage_quality1_message' => 'Dës Säit gouf net iwwerliest',
	'proofreadpage_quality2_message' => 'Et gouf e Problem beim iwwereliese vun dëser Säit',
	'proofreadpage_quality3_message' => 'Dës Säit gouf iwwerliest',
	'proofreadpage_quality4_message' => 'Dës Säit gouf validéiert',
	'proofreadpage_index_size' => 'Zuel vun de Säiten',
	'proofreadpage_specialpage_label_orderby' => 'Zortéieren no:',
	'proofreadpage_specialpage_label_key' => 'Sichen:',
	'proofreadpage_alphabeticalorder' => 'Alphabetesch Reiefolleg',
	'proofreadpage_index_listofpages' => 'Säitelëscht',
	'proofreadpage_image_message' => "Link op d'Indexsäit",
	'proofreadpage_page_status' => 'Status vun der Säit',
	'proofreadpage_js_attributes' => 'Auteur Titel Joer Editeur',
	'proofreadpage_index_attributes' => 'Auteur
Titel
Joer|Joer vun der Publikatioun
Eiteur
Quell
Bild|Titelbild
Säiten||20
Bemierkungen||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|Säit|Säiten}}',
	'proofreadpage_specialpage_legend' => 'An den Index-Säite sichen',
	'proofreadpage_specialpage_searcherror' => 'Feeler an der Sich-Maschine',
	'proofreadpage_specialpage_searcherrortext' => "D'Sichmaschinn fonctionnéiert net. Entschëllegt w.e.g. d'Ëmstänn.",
	'proofreadpage_source' => 'Quell',
	'proofreadpage_source_message' => 'Gescannten Editioun déi benotzt gouf fir dësen Text ze schreiwen',
	'right-pagequality' => 'Qualitéitsindice vun der Säit änneren',
	'proofreadpage-section-tools' => "Geschirkëscht fir z'iwwerliesen",
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Aner',
	'proofreadpage-button-toggle-visibility-label' => "D'Entête an de Fouss vun dëser Säit weisen/verstoppen",
	'proofreadpage-button-zoom-out-label' => ' Verklengeren',
	'proofreadpage-button-reset-zoom-label' => 'Zoom zerécksetzen',
	'proofreadpage-button-zoom-in-label' => 'Vergréisseren',
	'proofreadpage-button-toggle-layout-label' => 'Vertikalen/horizontale Layout',
	'proofreadpage-preferences-showheaders-label' => "D'Entête an de Pied de page weise beim Ännerungen am {{ns:page}}-Nummraum",
	'proofreadpage-preferences-horizontal-layout-label' => 'Benotzt en horizontale Layout wann Dir am {{ns:Page}}-Nummraum ännert',
	'proofreadpage-indexoai-repositoryName' => 'Meta-Donnéeë vu Bicher vu(n) {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadate vu Bicher déi vu ProofreadPage geréiert ginn.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Schema net fonnt',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'De Schema "$1" gouf net fonnt.',
	'proofreadpage-disambiguationspage' => 'Template:Homonymie',
);

/** Lingua Franca Nova (Lingua Franca Nova)
 * @author Malafaya
 */
$messages['lfn'] = array(
	'proofreadpage_image' => 'Imaje',
);

/** Limburgish (Limburgs)
 * @author Aelske
 * @author Ooswesthoesbes
 */
$messages['li'] = array(
	'indexpages' => 'Indexpaginalies',
	'pageswithoutscans' => "Pagina's zónger scans",
	'proofreadpage_desc' => "Maak 't meugelik teks eenvoudig te vergelieke mit de oorsjpronkelike scan",
	'proofreadpage_image' => 'Aafbeilding',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => "Fout: d'r woort 'nen index verwach",
	'proofreadpage_nosuch_index' => "Fout: d'n index besteit neet",
	'proofreadpage_nosuch_file' => "Fout: 't aangegaeve bestandj besteit neet",
	'proofreadpage_badpage' => 'Verkieërdj formaat',
	'proofreadpage_badpagetext' => "'t Formaat van de pagina die se perbeers óp te slaon is neet zjuus.",
	'proofreadpage_indexdupe' => 'Dóbbel verwiezing',
	'proofreadpage_indexdupetext' => "Pagina's kinne neet mieër es eine kieër óp 'n indexpagina getuundj waere.",
	'proofreadpage_nologin' => 'Neet aangemeld',
	'proofreadpage_nologintext' => "De mós tich [[Special:UserLogin|aanmelden]] óm de proeflaesstatus van pagina's te kinne wiezige.",
	'proofreadpage_notallowed' => 'Kèns neet verangere',
	'proofreadpage_notallowedtext' => 'De moogs de proeflaesstatus van dees pagina neet wiezige.',
	'proofreadpage_number_expected' => "Fout: d'r woort 'n numerieke waerd verwach",
	'proofreadpage_interval_too_large' => "Fout: d'n interval is te groeat",
	'proofreadpage_invalid_interval' => "Fout: d'r is 'nen óngeljigen interval ópgegaeve",
	'proofreadpage_nextpage' => 'Volgendje pazjena',
	'proofreadpage_prevpage' => 'Vörge pazjena',
	'proofreadpage_header' => 'Kopteks (gein inclusie):',
	'proofreadpage_body' => 'Broeadteks (veur transclusie):',
	'proofreadpage_footer' => 'Vootteks (gein inclusie):',
	'proofreadpage_toggleheaders' => 'zichbaarheid elemente zónger transclusie wiezige',
	'proofreadpage_quality0_category' => 'Teksloeas',
	'proofreadpage_quality1_category' => 'Ónbewèrk',
	'proofreadpage_quality2_category' => 'Ónvolledig',
	'proofreadpage_quality3_category' => 'Proofgelaeze',
	'proofreadpage_quality4_category' => 'Gekonterleerdj',
	'proofreadpage_quality0_message' => 'Dees paasj hoof neet proofgelaeze te waere.',
	'proofreadpage_quality1_message' => 'De paasj is neet proofgelaeze',
	'proofreadpage_quality2_message' => 'Der waar e perbleem bie t prooflaeze van dees paasj',
	'proofreadpage_quality3_message' => 'Dees paasj isproofgelaeze',
	'proofreadpage_quality4_message' => 'Dees paasj is gecontroleerd',
	'proofreadpage_index_listofpages' => "Lies van pazjena's",
	'proofreadpage_image_message' => 'Verwieziging nao de indekspaasj',
	'proofreadpage_page_status' => 'Pazjenastatus',
	'proofreadpage_js_attributes' => 'Auteur Titel Jaor Oetgaever',
	'proofreadpage_index_attributes' => "Auteur
Titel
Jaor|Jaor van publicatie
Oetgaever
Brón
Aafbeilding|Ómslaag
Pazjena's||20
Opmèrkinge||10",
	'proofreadpage_pages' => "$2 {{PLURAL:$1|pazjena|pazjena's}}",
	'proofreadpage_specialpage_legend' => "Doorzeuk indexpagina's.",
	'proofreadpage_source' => 'Brón',
	'proofreadpage_source_message' => 'Gescande versie worop dees teks is gebaseerd.',
	'right-pagequality' => 'Veranger de kwaliteitsmarkering van dees paasj',
	'proofreadpage-section-tools' => 'Prooflaeshölpmiddele',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Anges',
	'proofreadpage-button-toggle-visibility-label' => 'Tuun/verberg de kop- en voottèks van dees pagina.',
	'proofreadpage-button-zoom-out-label' => 'Zoom oet',
	'proofreadpage-button-reset-zoom-label' => 'Origineel gruuedje',
	'proofreadpage-button-zoom-in-label' => 'Zoom in',
	'proofreadpage-button-toggle-layout-label' => 'Verticale/horinzontale lay-out',
);

/** lumbaart (lumbaart)
 * @author Dakrismeno
 */
$messages['lmo'] = array(
	'proofreadpage_nextpage' => 'Pagina inanz',
	'proofreadpage_prevpage' => 'Pagina indree',
	'proofreadpage_header' => 'Intestazion (minga inclüsa)',
);

/** Lithuanian (lietuvių)
 * @author Eitvys200
 * @author Matasg
 */
$messages['lt'] = array(
	'indexpages' => 'Indeksuotų puslapių sąrašas',
	'proofreadpage_desc' => 'Galima lengvai palyginti tekstą su originaliu',
	'proofreadpage_image' => 'Paveikslėlis',
	'proofreadpage_index' => 'Indeksas',
	'proofreadpage_index_expected' => 'Klaida: indeksas laukiamas',
	'proofreadpage_nosuch_index' => 'Klaida: nėra tokio indekso',
	'proofreadpage_nosuch_file' => 'Klaida: nėra tokio failo',
	'proofreadpage_badpage' => 'Neteisingas formatas',
	'proofreadpage_badpagetext' => 'Puslapio, kurį bandėte išsaugoti, formatas yra neteisingas.',
	'proofreadpage_indexdupe' => 'Dublikuoti nuorodą',
	'proofreadpage_indexdupetext' => 'Puslapiai negali būti pateikiami daugiau kaip kartą pagrindiniame puslapyje.',
	'proofreadpage_nologin' => 'Neprisijungta',
	'proofreadpage_nologintext' => 'Jūs turite būti [[Special:UserLogin|prisijungęs]], norėdamas keisti puslapių statusą.',
	'proofreadpage_notallowed' => 'Keisti neleidžiama',
	'proofreadpage_notallowedtext' => 'Jums neleidžiama pakeisti šio puslapio statuso.',
	'proofreadpage_number_expected' => 'Klaida: tikėtasi skaitinės vertės',
	'proofreadpage_interval_too_large' => 'Klaida: intervalas per didelis',
	'proofreadpage_invalid_interval' => 'Klaida: neteisingas intervalas',
	'proofreadpage_nextpage' => 'Kitas puslapis',
	'proofreadpage_prevpage' => 'Ankstesnis puslapis',
	'proofreadpage_header' => 'Antraštė (neįskaitoma):',
	'proofreadpage_body' => 'Puslapio pagrindas (perkeliamas):',
	'proofreadpage_footer' => 'Poraštė (neįskaitoma):',
	'proofreadpage_toggleheaders' => 'įjungti neįskaitytų sekcijų matomumą',
	'proofreadpage_quality0_category' => 'Be teksto',
	'proofreadpage_quality1_category' => 'Neperžiūrėtas',
	'proofreadpage_quality2_category' => 'Problemiškas',
	'proofreadpage_quality3_category' => 'Peržiūrėtas',
	'proofreadpage_quality4_category' => 'Patvirtintas',
	'proofreadpage_quality0_message' => 'Šis puslapis neturi būti peržiūrėtas',
	'proofreadpage_quality1_message' => 'Šis puslapis nebuvo peržiūrėtas',
	'proofreadpage_quality2_message' => 'Iškilo problema kai buvo peržiūrimas šis puslapis',
	'proofreadpage_quality3_message' => 'Šis puslapis buvo peržiūrėtas',
	'proofreadpage_quality4_message' => 'Šis puslapis buvo patvirtintas',
	'proofreadpage_index_listofpages' => 'Puslapių sąrašas',
	'proofreadpage_image_message' => 'Nuoroda į pagrindinį puslapį',
	'proofreadpage_page_status' => 'Puslapio statusas',
	'proofreadpage_js_attributes' => 'Autorius Pavadinimas Metai Publikuotojas',
	'proofreadpage_index_attributes' => 'Autorius
Pavadinimas
Metai|Išleidimo metai
Leidėjas
Šaltinis
Paveikslėlis|Viršelis
Puslapiai||20
Pastabos||10',
	'proofreadpage_source' => 'Šaltinis',
	'proofreadpage-group-zoom' => 'Padidinti',
	'proofreadpage-group-other' => 'Kita',
	'proofreadpage-button-toggle-visibility-label' => 'Rodyti/slėpti šio puslapio antraštes ir poraštes',
	'proofreadpage-button-zoom-out-label' => 'Nutolinti',
	'proofreadpage-button-reset-zoom-label' => 'Perkrauti priartinimą',
	'proofreadpage-button-zoom-in-label' => 'Priartinti',
	'proofreadpage-button-toggle-layout-label' => 'Vertikalus/horizontalus išdėstymas',
);

/** Latvian (latviešu)
 * @author Papuass
 * @author Xil
 * @author Yyy
 */
$messages['lv'] = array(
	'proofreadpage_image' => 'Attēls',
	'proofreadpage_index' => 'Saturs',
	'proofreadpage_nextpage' => 'Nākamā lapa',
	'proofreadpage_prevpage' => 'Iepriekšējā lapa',
	'proofreadpage_quality0_category' => 'Bez teksta',
	'proofreadpage_quality1_category' => 'Nav pārlasīts',
	'proofreadpage_quality2_category' => 'Problemātisks',
	'proofreadpage_quality3_category' => 'Pārlasīts',
	'proofreadpage_index_listofpages' => 'Lapu saraksts',
	'proofreadpage_page_status' => 'Lapas statuss',
	'proofreadpage_js_attributes' => 'Autors Nosaukums Gads Izdevējs',
	'proofreadpage_index_attributes' => 'Autors
Nosaukums
Gads|Publikācijas gads
Izdevējs
Avots
Attēls|Vāka attēls
Lapas||20
Piezīmes||10',
	'proofreadpage_source' => 'Avots',
);

/** Moksha (мокшень)
 * @author Numulunj pilgae
 */
$messages['mdf'] = array(
	'indexpages' => 'индекс лопатнень лувомась',
	'pageswithoutscans' => 'Сканфтома лопат',
	'proofreadpage_desc' => 'Мярьгомс сканть текстонь ваксс путомась',
	'proofreadpage_image' => 'Няйфкс',
	'proofreadpage_index' => 'Индекс',
	'proofreadpage_index_expected' => 'Эльбятькс: учеви индекс',
	'proofreadpage_nosuch_index' => 'Эльбятькс: стама индекс аш',
	'proofreadpage_nosuch_file' => 'Эльбятькс: стама файл аш',
	'proofreadpage_badpage' => 'Аф кондясти форматсь',
	'proofreadpage_badpagetext' => 'Лопать форматоц, конань тяряфтоть ванфтомс аф кондясти.',
	'proofreadpage_indexdupe' => 'Кафтонь сюлмафкс',
	'proofreadpage_indexdupetext' => 'Аш кода путомс лопат лувомас ламоксть.',
	'proofreadpage_nologin' => 'Апак сувак',
	'proofreadpage_nologintext' => 'Эряви [[Special:UserLogin|сувамс]] видептемань лопатнень статусснон полафтоманди.',
	'proofreadpage_notallowed' => 'Полафтомат аф мярьговихть',
	'proofreadpage_notallowedtext' => 'Тя лопать видептемань статусонц тейть полафтомс аф мярьгови.',
	'proofreadpage_dataconfig_badformatted' => 'Унжаня содамошинь конфигурациеса',
	'proofreadpage_dataconfig_badformattedtext' => '[[Mediawiki:Proofreadpage index data config]] лопась аф лац-форматыяфтф JSON.',
	'proofreadpage_number_expected' => 'Эльбятькс: лувтяшкс учеви',
	'proofreadpage_interval_too_large' => 'Эльбятькс: ёткась пяк кувака',
	'proofreadpage_invalid_interval' => 'Эльбятькс: аф кондясти ёткась',
	'proofreadpage_nextpage' => 'Сямольдень лопа',
	'proofreadpage_prevpage' => 'Сядынгольдень лопа',
	'proofreadpage_header' => 'Лопать вазец (апак нолдак)',
	'proofreadpage_body' => 'Лопать потмоц (сюлмафксонь вельде):',
	'proofreadpage_footer' => 'Лопать алксоц (апак нолдак)',
	'proofreadpage_toggleheaders' => 'полафнемс апак нолдак пялькснень няевомасна',
	'proofreadpage_quality0_category' => 'текстфтома',
	'proofreadpage_quality1_category' => 'Апак видептьк',
	'proofreadpage_quality2_category' => 'Прябалань',
	'proofreadpage_quality3_category' => 'Видептема',
	'proofreadpage_quality4_category' => 'Кемокстаф',
	'proofreadpage_quality0_message' => 'Тя лопать аф эряви видептемс',
	'proofreadpage_quality1_message' => 'Тя лопать изезь видепте',
	'proofreadpage_quality2_message' => 'Лиссь прябалась тя лопать видептембачк',
	'proofreadpage_quality3_message' => 'Тя лопась апак видептьк',
	'proofreadpage_quality4_message' => 'Тя лопась кемокстаф',
	'proofreadpage_index_status' => 'Индекс статус',
	'proofreadpage_index_size' => 'Мзяра лопада',
	'proofreadpage_specialpage_label_orderby' => 'Кинь азфкясь:',
	'proofreadpage_specialpage_label_key' => 'Вешема:',
	'proofreadpage_specialpage_label_sortascending' => 'Арафнемс инголишит',
	'proofreadpage_alphabeticalorder' => 'Алфавитонь коряс',
	'proofreadpage_index_listofpages' => 'Лопатнень лувома',
	'proofreadpage_image_message' => 'Индекс лопать сюлмафксоц',
	'proofreadpage_page_status' => 'Лопать статусоц',
	'proofreadpage_js_attributes' => 'Сёрмадысь Конякс Киза Нолдай',
	'proofreadpage_index_attributes' => 'Сёрмадыец
Коняксоц
Киза|Нолдама кизоц
Нолдаец
Лисьмоц
Няйфкс|Лангонь няйфксоц
Лопат||20
Мяльполаткст||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|лопа|лопат}}',
	'proofreadpage_specialpage_legend' => 'Вешемс индекс лопать эзга',
	'proofreadpage_specialpage_searcherror' => 'Эльбятькс вешемань программаса',
	'proofreadpage_specialpage_searcherrortext' => 'Вешема программась аф якай. Ужялькс, тя лиссь апак учсек.',
	'proofreadpage_source' => 'Лисьмась',
	'proofreadpage_source_message' => 'Сканияфтф нолдамась сявф тя текстти',
	'right-pagequality' => 'Полафтомс лопать паронц котфть',
	'proofreadpage-section-tools' => 'Видептема кядьёнкст',
	'proofreadpage-group-zoom' => 'Ламолгафтомс',
	'proofreadpage-group-other' => 'Иля',
	'proofreadpage-button-toggle-visibility-label' => 'Няфтемс/кяшемс тя лопать вазец эди алксоц',
	'proofreadpage-button-zoom-out-label' => 'Ёмлалгафтомс',
	'proofreadpage-button-reset-zoom-label' => 'Эсь ункстамац',
	'proofreadpage-button-zoom-in-label' => 'Маласькофтомс',
	'proofreadpage-button-toggle-layout-label' => 'Серенц турксонц коряс арафнемась',
	'proofreadpage-preferences-showheaders-label' => 'Няфтемс лопать вазенц эди алксоц {{ns:page}} лемботмоса видептембачк',
	'proofreadpage-preferences-horizontal-layout-label' => 'Нолдак тевс турксонц коряс арафнемать {{ns:page}} лемботмоса видептембачк',
);

/** Eastern Mari (олык марий)
 * @author Сай
 */
$messages['mhr'] = array(
	'proofreadpage_nextpage' => 'Вес лаштык',
);

/** Minangkabau (Baso Minangkabau)
 * @author Gombang
 * @author Naval Scene
 * @author VoteITP
 */
$messages['min'] = array(
	'indexpages' => 'Daftar laman indeks',
	'pageswithoutscans' => 'Laman indak ado pindaian',
	'proofreadpage_desc' => 'Maijinan pabandiangan mudah antaro naskah jo asia pindaian nan asali',
	'proofreadpage_image' => 'Gamba',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => 'Kasalahan: diparaluan indeks',
	'proofreadpage_nosuch_index' => 'Kasalahan: indak ado indeks',
	'proofreadpage_nosuch_file' => 'Kasalahan: indak ado berkas',
	'proofreadpage_badpage' => 'Kasalahan Format',
	'proofreadpage_badpagetext' => 'Format laman nan handak sudaro simpan indak bana',
	'proofreadpage_indexdupe' => 'Manyalin pautan',
	'proofreadpage_indexdupetext' => 'Laman-laman anyo dapek dimuek satu kali sajo di laman indeks.',
	'proofreadpage_nologin' => 'Alun masuak log',
	'proofreadpage_nologintext' => 'Sudaro aruih [[Special:UserLogin|masuak log]] untuak mampaeloki status koreksi laman.',
	'proofreadpage_notallowed' => 'Paubahan indak dibuliahan',
	'proofreadpage_notallowedtext' => 'Sudaro indak dibuliahan untuak maubah status kalimaik nan tatulih di laman iko.',
	'proofreadpage_dataconfig_badformatted' => '↓Bug dalam konfigurasi data',
	'proofreadpage_dataconfig_badformattedtext' => '↓Laman [[Mediawiki:Proofreadpage index data config]] indak diformat JSON nan bana.',
	'proofreadpage_number_expected' => '↓Kasalahan: isi jo angko',
	'proofreadpage_interval_too_large' => '↓Kasalahan: interval talalu gadang',
	'proofreadpage_invalid_interval' => '↓Kasalahan: interval indak sah',
	'proofreadpage_nextpage' => 'Laman salanjuiknyo',
	'proofreadpage_prevpage' => 'Laman sabalunnyo',
	'proofreadpage_header' => '↓Kapalo (noinclude)',
	'proofreadpage_body' => '↓Badan laman (untuak ditransklusi):',
	'proofreadpage_footer' => '↓Kaki (noinclude):',
	'proofreadpage_toggleheaders' => '↓toggle tacelak bagian noinclude',
	'proofreadpage_quality0_category' => '↓Teks indak ado',
	'proofreadpage_quality1_category' => '↓Alun diujibaco',
	'proofreadpage_quality2_category' => '↓Bamasalah',
	'proofreadpage_quality3_category' => '↓Pangujian baco',
	'proofreadpage_quality4_category' => '↓Sah',
	'proofreadpage_quality0_message' => '↓Laman ko indak paralu diujibaco',
	'proofreadpage_quality1_message' => '↓Laman ko alun diujibaco',
	'proofreadpage_quality2_message' => '↓Ado masalah katiko laman ko diujibaco',
	'proofreadpage_quality3_message' => '↓Laman ko alah diujibaco',
	'proofreadpage_quality4_message' => '↓Laman ko alah sah',
	'proofreadpage_index_status' => '↓Status indeks',
	'proofreadpage_index_size' => '↓Jumlah laman',
	'proofreadpage_specialpage_label_orderby' => '↓Dek parintah:',
	'proofreadpage_specialpage_label_key' => 'Cari',
	'proofreadpage_specialpage_label_sortascending' => '↓Uruik manaiak',
	'proofreadpage_alphabeticalorder' => '↓Parintah Abjad',
	'proofreadpage_index_listofpages' => '↓Daftar laman',
	'proofreadpage_image_message' => '↓Tauik ka laman indeks',
	'proofreadpage_page_status' => '↓Status laman',
	'proofreadpage_js_attributes' => '↓Pangarang Judul Tahun Panabik',
	'proofreadpage_index_attributes' => 'Pangarang
Judul
Taun|Taun tabik
Panabik
Sumber
Gamba|Gamba muko
Alaman||20
Catatan||10',
	'proofreadpage_pages' => '↓$2 {{PLURAL:$1|laman|laman}}', # Fuzzy
	'proofreadpage_specialpage_legend' => 'Cari alaman indeks',
	'proofreadpage_specialpage_searcherror' => '↓Kasalahan di masin pancari',
	'proofreadpage_specialpage_searcherrortext' => '↓Masin pancari indak bakarajo. Maaf dek indak saleso ko.',
	'proofreadpage_source' => 'Sumber',
	'proofreadpage_source_message' => '↓Edisi scan nan digunoan untuak mambuek teks ko',
	'right-pagequality' => '↓Maubahsuai tando kualitas laman',
	'proofreadpage-section-tools' => '↓Pakakeh pangujian baco',
	'proofreadpage-group-zoom' => '↓Pajaleh',
	'proofreadpage-group-other' => 'Lain-lain',
	'proofreadpage-button-toggle-visibility-label' => '↓Tampak/tasuruak kapalo jo kaki laman ko',
	'proofreadpage-button-zoom-out-label' => 'Ketekkan',
	'proofreadpage-button-reset-zoom-label' => 'Ukuran asali',
	'proofreadpage-button-zoom-in-label' => 'Gadangkan',
	'proofreadpage-button-toggle-layout-label' => '↓Tata letak managak/mandata',
	'proofreadpage-preferences-showheaders-label' => '↓Tayang bidang kapalo jo kaki katiko manyuntiang {{ns:page}} ruangnamo',
	'proofreadpage-preferences-horizontal-layout-label' => '↓Gunoan tata letak managak katiko manyuntiang {{ns:page}} ruangnamo',
	'proofreadpage-indexoai-repositoryName' => '↓Metadata buku dari {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => '↓Metadata buku diupayoan dek laman pangujian baco.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Skema indak ditamui',
	'proofreadpage-indexoai-error-schemanotfound-text' => '↓Skema $1 alun basobok.',
);

/** Macedonian (македонски)
 * @author Bjankuloski06
 * @author Brest
 */
$messages['mk'] = array(
	'indexpages' => 'Список на индексни страници',
	'pageswithoutscans' => 'Страници без скенови',
	'proofreadpage_desc' => 'Овозможува едноставна споредба на текстот со скенираниот оригинал',
	'proofreadpage_image' => 'Слика',
	'proofreadpage_index' => 'Индекс',
	'proofreadpage_index_expected' => 'Грешка: се очекува индекс',
	'proofreadpage_nosuch_index' => 'Грешка: нема таков индекс',
	'proofreadpage_nosuch_file' => 'Грешка: нема таква податотека',
	'proofreadpage_badpage' => 'Погрешен формат',
	'proofreadpage_badpagetext' => 'Форматот на страницата што сакате да ја зачувате е погрешен.',
	'proofreadpage_indexdupe' => 'Дупликат врска',
	'proofreadpage_indexdupetext' => 'Страниците не можат да се наведуваат на индексот повеќе од еднаш по страница',
	'proofreadpage_nologin' => 'Не сте најавени',
	'proofreadpage_nologintext' => 'Морате да [[Special:UserLogin|се најавите]] за да можете да го менувате статусот на коректурата на страници.',
	'proofreadpage_notallowed' => 'Менувањето не е дозволено',
	'proofreadpage_notallowedtext' => 'Не ви е дозволено да го менувате статусот на коректурата на оваа страница.',
	'proofreadpage_dataconfig_badformatted' => 'Грешка во поставеноста на податоците',
	'proofreadpage_dataconfig_badformattedtext' => 'Страницата [[Mediawiki:Proofreadpage index data config]] не е добро форматирана како JSON.',
	'proofreadpage_number_expected' => 'Грешка: се очекува бројчена вредност',
	'proofreadpage_interval_too_large' => 'Грешка: растојанието е преголемо',
	'proofreadpage_invalid_interval' => 'Грешка: погрешно растојание',
	'proofreadpage_nextpage' => 'Следна страница',
	'proofreadpage_prevpage' => 'Претходна страница',
	'proofreadpage_header' => 'Заглавие (не се вклучува):',
	'proofreadpage_body' => 'Содржина на страница (за превметнување):',
	'proofreadpage_footer' => 'Подножје (не се вклучува):',
	'proofreadpage_toggleheaders' => 'промена на видливоста на пасусите со „noinclude“',
	'proofreadpage_quality0_category' => 'Без текст',
	'proofreadpage_quality1_category' => 'Непрегледана',
	'proofreadpage_quality2_category' => 'Проблематично',
	'proofreadpage_quality3_category' => 'Прегледано',
	'proofreadpage_quality4_category' => 'Потврдено',
	'proofreadpage_quality0_message' => 'Оваа страница нема потреба од преглед',
	'proofreadpage_quality1_message' => 'Оваа страница е непрегледана',
	'proofreadpage_quality2_message' => 'Се јави проблем при прегледувањето на оваа страница',
	'proofreadpage_quality3_message' => 'Оваа страница е прегледана',
	'proofreadpage_quality4_message' => 'Оваа страница е потврдена',
	'proofreadpage_index_status' => 'Индексирај статус',
	'proofreadpage_index_size' => 'Број на страници',
	'proofreadpage_specialpage_label_orderby' => 'Подреди по:',
	'proofreadpage_specialpage_label_key' => 'Пребарај:',
	'proofreadpage_specialpage_label_sortascending' => 'Подреди нагорно',
	'proofreadpage_alphabeticalorder' => 'Азбучен редослед',
	'proofreadpage_index_listofpages' => 'Список на страници',
	'proofreadpage_image_message' => 'Врска до индекс страницата',
	'proofreadpage_page_status' => 'Статус на страница',
	'proofreadpage_js_attributes' => 'Автор Наслов Година Издавач',
	'proofreadpage_index_attributes' => 'Автор
Наслов
Година|Година на издавање
Издавач
Извор
Слика|Корица
Страници||20
Белешки||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|страница|страници}}',
	'proofreadpage_specialpage_legend' => 'Пребарување на индексни страници',
	'proofreadpage_specialpage_searcherror' => 'Грешка во пребарувачот',
	'proofreadpage_specialpage_searcherrortext' => 'Пребарувачот не работи. Се извинуваме за непријатноста.',
	'proofreadpage_source' => 'Извор',
	'proofreadpage_source_message' => 'Отсликано издание што се користи за востановување на овој текст',
	'right-pagequality' => 'Измени ознака за квалитет на страницата',
	'proofreadpage-section-tools' => 'Лекторски алатки',
	'proofreadpage-group-zoom' => 'Размер',
	'proofreadpage-group-other' => 'Други',
	'proofreadpage-button-toggle-visibility-label' => 'Прикажи / скриј го заглавието и подножјето на страницава',
	'proofreadpage-button-zoom-out-label' => 'Оддалечи',
	'proofreadpage-button-reset-zoom-label' => 'Врати размер',
	'proofreadpage-button-zoom-in-label' => 'Приближи',
	'proofreadpage-button-toggle-layout-label' => 'Вертикален/хоризонтален распоред',
	'proofreadpage-preferences-showheaders-label' => 'Прикажувај заглавје и подножне при уредување на именскиот простор „{{ns:page}}“',
	'proofreadpage-preferences-horizontal-layout-label' => 'Користи хоризонтален распоред при уредување во именскиот простор „{{ns:page}}“',
	'proofreadpage-indexoai-repositoryName' => 'Метаподатоци за книги од {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Метаподатоци за книги под раководство на ЛектураНаСтраници.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Шемата не е пронајдена',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Шемите $1 не се пронајдени.',
	'proofreadpage-disambiguationspage' => 'Template:Појаснување',
);

/** Malayalam (മലയാളം)
 * @author Hrishikesh.kb
 * @author Praveenp
 * @author Shijualex
 * @author Vssun
 */
$messages['ml'] = array(
	'indexpages' => 'സൂചികാ താളുകളുടെ പട്ടിക',
	'pageswithoutscans' => 'സ്കാനുകൾ ഇല്ലാത്ത താളുകൾ',
	'proofreadpage_desc' => 'യഥാർത്ഥ സ്കാനും എഴുത്തും തമ്മിലുള്ള ലളിതമായ ഒത്തുനോക്കൽ അനുവദിക്കുക',
	'proofreadpage_image' => 'ചിത്രം',
	'proofreadpage_index' => 'സൂചിക',
	'proofreadpage_index_expected' => 'പിഴവ്: സൂചിക വേണം',
	'proofreadpage_nosuch_index' => 'പിഴവ്: അത്തരത്തിലൊരു സൂചിക ഇല്ല',
	'proofreadpage_nosuch_file' => 'പിഴവ്: അത്തരത്തിലൊരു പ്രമാണം ഇല്ല',
	'proofreadpage_badpage' => 'തെറ്റായ തരം',
	'proofreadpage_badpagetext' => 'താങ്കൾ സേവ് ചെയ്യാൻ ശ്രമിച്ച താളിന്റെ തരം (ഫോർമാറ്റ്) ശരിയല്ല.',
	'proofreadpage_indexdupe' => 'കണ്ണിയുടെ പകർപ്പ്',
	'proofreadpage_indexdupetext' => 'ഒരു സൂചികാ താളിൽ ഒന്നിലധികം പ്രാവശ്യം ഒരു താൾ തന്നെ ചേർക്കാൻ കഴിയില്ല.',
	'proofreadpage_nologin' => 'ലോഗിൻ ചെയ്തിട്ടില്ല',
	'proofreadpage_nologintext' => 'താളുകളുടെ തെറ്റുതിരുത്തൽ വായനയുടെ സ്ഥിതിയിൽ മാറ്റം വരുത്താൻ താങ്കൾ [[Special:UserLogin|പ്രവേശിച്ചിരിക്കേണ്ടതാണ്]].',
	'proofreadpage_notallowed' => 'മാറ്റം വരുത്താൻ അനുവാദമില്ല',
	'proofreadpage_notallowedtext' => 'ഈ താളിന്റെ തെറ്റുതിരുത്തൽ വായനയുടെ സ്ഥിതിയിൽ മാറ്റം വരുത്താൻ താങ്കൾക്ക് അനുമതിയില്ല.',
	'proofreadpage_number_expected' => 'പിഴവ്: സംഖ്യയായുള്ള മൂല്യമാണ് പ്രതീക്ഷിക്കുന്നത്',
	'proofreadpage_interval_too_large' => 'പിഴവ്: വളരെ വലിയ ഇടവേള',
	'proofreadpage_invalid_interval' => 'പിഴവ്: അസാധുവായ ഇടവേള',
	'proofreadpage_nextpage' => 'അടുത്ത താൾ',
	'proofreadpage_prevpage' => 'മുൻപത്തെ താൾ',
	'proofreadpage_header' => 'തലക്കെട്ട് (ഉൾപ്പെടുത്തില്ല):',
	'proofreadpage_body' => 'താളിന്റെ ഉള്ളടക്കം (ട്രാൻസ്‌ക്ലൂഡ് ചെയ്യാനുള്ളത്):',
	'proofreadpage_footer' => 'പാദവാചകം (ഉൾപ്പെടുത്തില്ല):',
	'proofreadpage_toggleheaders' => 'ഉൾപ്പെടുത്തില്ലാത്ത വിഭാഗങ്ങൾ പ്രദർശിപ്പിക്കുക / മറയ്ക്കുക',
	'proofreadpage_quality0_category' => 'എഴുത്ത് ഇല്ലാത്തവ',
	'proofreadpage_quality1_category' => 'തെറ്റുതിരുത്തൽ വായന നടന്നിട്ടില്ലാത്തവ',
	'proofreadpage_quality2_category' => 'പ്രശ്നമുള്ളവ',
	'proofreadpage_quality3_category' => 'തെറ്റുതിരുത്തൽ വായന കഴിഞ്ഞവ',
	'proofreadpage_quality4_category' => 'സാധൂകരിച്ചവ',
	'proofreadpage_quality0_message' => 'ഈ താളിൽ തെറ്റുതിരുത്തൽ വായന ആവശ്യമില്ല',
	'proofreadpage_quality1_message' => 'ഈ താളിൽ തെറ്റുതിരുത്തൽ വായന ഉണ്ടായിട്ടില്ല',
	'proofreadpage_quality2_message' => 'ഈ താളിന്റെ തെറ്റുതിരുത്തൽ വായനയിൽ പിഴവ് കാണാനായി',
	'proofreadpage_quality3_message' => 'ഈ താളിൽ തെറ്റുതിരുത്തൽ വായന നടന്നിരിക്കുന്നു',
	'proofreadpage_quality4_message' => 'ഈ താളിന്റെ സാധുത തെളിയിക്കപ്പെട്ടതാണ്',
	'proofreadpage_index_status' => 'സൂചികയുടെ സ്ഥിതി',
	'proofreadpage_index_size' => 'താളുകളുടെ എണ്ണം',
	'proofreadpage_specialpage_label_orderby' => 'ക്രമപ്പെടുത്തൽ രീതി:',
	'proofreadpage_specialpage_label_key' => 'തിരയുക:',
	'proofreadpage_specialpage_label_sortascending' => 'ആരോഹണമായി ക്രമപ്പെടുത്തുക',
	'proofreadpage_alphabeticalorder' => 'അക്ഷരമാലാ ക്രമം',
	'proofreadpage_index_listofpages' => 'താളുകളുടെ പട്ടിക',
	'proofreadpage_image_message' => 'സൂചിക താളിലേക്കുള്ള കണ്ണി',
	'proofreadpage_page_status' => 'താളിന്റെ തൽസ്ഥിതി',
	'proofreadpage_js_attributes' => 'ലേഖകൻ കൃതിയുടെപേര്‌ വർഷം പ്രസാധകർ',
	'proofreadpage_index_attributes' => 'ലേഖകൻ
കൃതിയുടെപേര്‌
വർഷം|പ്രസിദ്ധീകരിച്ച വർഷം
പ്രസാധകർ
ഉറവിടം
ചിത്രം|മുഖച്ചിത്രം
താളുകൾ||20
കുറിപ്പുകൾ||10',
	'proofreadpage_pages' => '{{PLURAL:$1|ഒരു താൾ|$2 താളുകൾ}}',
	'proofreadpage_specialpage_legend' => 'സൂചികാ താളുകൾ തിരയുക',
	'proofreadpage_specialpage_searcherror' => 'തിരച്ചിൽ സൗകര്യത്തിൽ പിഴവുണ്ടായി',
	'proofreadpage_specialpage_searcherrortext' => 'തിരച്ചിൽ സൗകര്യം പ്രവർത്തിക്കുന്നില്ല. അസൗകര്യമുണ്ടായതിൽ ഖേദിക്കുന്നു.',
	'proofreadpage_source' => 'സ്രോതസ്സ്',
	'proofreadpage_source_message' => 'സ്കാൻ ചെയ്തെടുത്ത പ്രസിദ്ധീകരണം ഉപയോഗിച്ചാണ് ഈ എഴുത്തുകൾ സ്ഥിരീകരിച്ചത്',
	'right-pagequality' => 'താളിന്റെ ഗുണമേന്മാ പതാകയിൽ മാറ്റം വരുത്തുക',
	'proofreadpage-section-tools' => 'തെറ്റുതിരുത്തൽ വായനോപകരണങ്ങൾ',
	'proofreadpage-group-zoom' => 'വലുതാക്കി കാട്ടുക',
	'proofreadpage-group-other' => 'മറ്റുള്ളവ',
	'proofreadpage-button-toggle-visibility-label' => 'താളിന്റെ തലക്കുറിയും അടിക്കുറിപ്പും പ്രദർശിപ്പിക്കുക/മറയ്ക്കുക',
	'proofreadpage-button-zoom-out-label' => 'ചെറുതാക്കി കാട്ടുക',
	'proofreadpage-button-reset-zoom-label' => 'യഥാർത്ഥ വലിപ്പം',
	'proofreadpage-button-zoom-in-label' => 'വലുതാക്കുക',
	'proofreadpage-button-toggle-layout-label' => 'തിരശ്ചീന/ലംബ രൂപകല്പന',
	'proofreadpage-preferences-showheaders-label' => '{{ns:page}} നാമമേഖല തിരുത്തുമ്പോൾ തലക്കുറിപ്പ്, അടിക്കുറിപ്പ് മണ്ഡലങ്ങൾ പ്രദർശിപ്പിക്കുക',
	'proofreadpage-preferences-horizontal-layout-label' => '{{ns:page}} നാമമേഖല തിരുത്തുമ്പോൾ തിരശ്ചീന രൂപകല്പന ഉപയോഗിക്കുക',
);

/** Mongolian (монгол)
 * @author Chinneeb
 */
$messages['mn'] = array(
	'proofreadpage_pages' => '{{PLURAL:$1|хуудас}}', # Fuzzy
);

/** Marathi (मराठी)
 * @author Kaajawa
 * @author Kaustubh
 * @author Mvkulkarni23
 * @author Rahuldeshmukh101
 * @author Sankalpdravid
 * @author Shantanoo
 * @author Vanandf1
 * @author संतोष दहिवळ
 */
$messages['mr'] = array(
	'indexpages' => 'अनुक्रमणिका पानांची यादी',
	'pageswithoutscans' => 'छाननी न केलेली पाने',
	'proofreadpage_desc' => 'मूळ प्रतीशी मजकूराची छाननी करण्याची सोपी पद्धत',
	'proofreadpage_image' => 'चित्र',
	'proofreadpage_index' => 'अनुक्रमणिका',
	'proofreadpage_index_expected' => 'त्रुटी: अनुक्रमणिका अपेक्षित',
	'proofreadpage_nosuch_index' => 'त्रुटी: अशी कोणतीही अनुक्रमणिका नाही',
	'proofreadpage_nosuch_file' => 'त्रुटी: अशी कोणतीही फाइल नाही',
	'proofreadpage_badpage' => 'चुकीचा फॉरमॅट',
	'proofreadpage_badpagetext' => 'आपण ज्या स्वरुपात  पान जतन करण्याचा प्रयत्न करीत आहात ते  स्वरुप चुकीचे आहे.',
	'proofreadpage_indexdupe' => 'पुनरावृत्ती झालेला दुवा',
	'proofreadpage_indexdupetext' => 'पाने अनुक्रमणिकेत एकापेक्षा जास्त वेळेस येऊ शकत नाहीत.',
	'proofreadpage_nologin' => 'प्रवेश केलेला नाही',
	'proofreadpage_nologintext' => 'पानाच्या प्रामाणिकरणाची   स्थिती बदलवण्यासाठी आपणास  [[Special:UserLogin|प्रवेश करणे ]] आवश्यक आहे.',
	'proofreadpage_notallowed' => 'बदल करण्यास परवानगी नाही',
	'proofreadpage_notallowedtext' => 'ह्या पानाच्या प्रामाणिकरणाची स्थिती बदलवण्याचे आपणास परवानगी नाही',
	'proofreadpage_dataconfig_badformattedtext' => '[[Mediawiki:Proofreadpage index data config]] हे पान JSON  या योग्य प्रकारात नाही.',
	'proofreadpage_number_expected' => 'त्रुटि: आकडी संख्या अपेक्षित आहे',
	'proofreadpage_interval_too_large' => 'त्रुटी: अतिदीर्घ अंतराळ',
	'proofreadpage_invalid_interval' => 'त्रुटि: अवैध अंतराळ',
	'proofreadpage_nextpage' => 'पुढील पान',
	'proofreadpage_prevpage' => 'मागील पान',
	'proofreadpage_header' => 'पानाच्या वरील मजकूर (noinclude):',
	'proofreadpage_body' => 'पानाचा मुख्य मजकूर (जो वापरायचा आहे):',
	'proofreadpage_footer' => 'पानाच्या खालील मजकूर (noinclude):',
	'proofreadpage_toggleheaders' => 'noinclude विभागांची दृष्य पातळी बदला',
	'proofreadpage_quality0_category' => 'मजकुराविना',
	'proofreadpage_quality1_category' => 'तपासणी करायचे साहित्य',
	'proofreadpage_quality2_category' => 'समस्यादायक',
	'proofreadpage_quality3_category' => 'परीक्षण',
	'proofreadpage_quality4_category' => 'प्रमाणित',
	'proofreadpage_quality0_message' => 'या पानाचे परीक्षण करण्याची गरज नाही',
	'proofreadpage_quality1_message' => 'या पानाचे परीक्षण झालेले नाही',
	'proofreadpage_quality2_message' => 'या पानाचे परीक्षण करतांना काही समस्या उद्भवल्या आहेत',
	'proofreadpage_quality3_message' => 'या पानाचे परीक्षण झाले आहे',
	'proofreadpage_quality4_message' => 'हे पान प्रमाणित केलेले आहे.',
	'proofreadpage_index_status' => 'अनुक्रम स्थिती',
	'proofreadpage_index_size' => 'पानांची संख्या',
	'proofreadpage_specialpage_label_orderby' => 'यानुसार क्रम:',
	'proofreadpage_specialpage_label_key' => 'शोधा',
	'proofreadpage_specialpage_label_sortascending' => 'चढत्या क्रमाने लावा',
	'proofreadpage_alphabeticalorder' => 'वर्णानुक्रमे',
	'proofreadpage_index_listofpages' => 'पानांची यादी',
	'proofreadpage_image_message' => 'अनुक्रमणिका असणाऱ्या पानाशी दुवा द्या',
	'proofreadpage_page_status' => 'पानाची स्थिती',
	'proofreadpage_js_attributes' => 'लेखक शीर्षक वर्ष प्रकाशक',
	'proofreadpage_index_attributes' => 'लेखक
शीर्षक
वर्ष|प्रकाशन वर्ष
प्रकाशक
स्रोत
चित्र|मुखपृष्ठ चित्र
पाने||२०
शेरा||१०',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|पान|पाने}}',
	'proofreadpage_specialpage_legend' => 'अनुक्रमणिकेत शोधा',
	'proofreadpage_specialpage_searcherror' => 'शोधयंत्रात त्रूटी',
	'proofreadpage_specialpage_searcherrortext' => 'शोधयंत्र काम करीत नाही. आपल्याला झालेल्या त्रासाबद्दल खेद आहे.',
	'proofreadpage_source' => 'स्रोत',
	'proofreadpage_source_message' => 'ह्या मजकुरास प्रस्थापित करण्यासाठी स्कॅन आवृत्तीचा वापर करण्यात आलेला आहे',
	'right-pagequality' => 'पृष्ठ गुणवत्ता निशाणास बदला',
	'proofreadpage-section-tools' => 'परीक्षणाची साधने',
	'proofreadpage-group-zoom' => 'मोठे करा',
	'proofreadpage-group-other' => 'इतर',
	'proofreadpage-button-toggle-visibility-label' => 'ह्या पानाची शीर्षणी आणि तळटीप दाखवा/लपवा',
	'proofreadpage-button-zoom-out-label' => 'मोठे करा',
	'proofreadpage-button-reset-zoom-label' => 'मूळ आकार',
	'proofreadpage-button-zoom-in-label' => 'छोटे करा',
	'proofreadpage-button-toggle-layout-label' => 'उभा/आडवा आराखडा',
	'proofreadpage-preferences-showheaders-label' => '{{ns:page}} या नामविश्वात संपादन करताना हेडर आणि फूटर दाखवा',
	'proofreadpage-indexoai-repositoryName' => 'पुस्तकाचा मेटाडाटा {{SITENAME}} पासून',
	'proofreadpage-indexoai-error-schemanotfound' => 'स्किमा सापडत नाही',
	'proofreadpage-indexoai-error-schemanotfound-text' => '$1 स्किमा सापडत नाही.',
);

/** Malay (Bahasa Melayu)
 * @author Anakmalaysia
 * @author Aurora
 * @author Aviator
 */
$messages['ms'] = array(
	'indexpages' => 'Senarai laman indeks',
	'pageswithoutscans' => 'Laman yang tidak diimbas',
	'proofreadpage_desc' => 'Membolehkan perbandingan mudah bagi teks dengan imbasan asal',
	'proofreadpage_image' => 'Imej',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => 'Ralat: indeks diperlukan',
	'proofreadpage_nosuch_index' => 'Ralat: indeks ini tidak wujud',
	'proofreadpage_nosuch_file' => 'Ralat: fail ini tidak wujud',
	'proofreadpage_badpage' => 'Format salah',
	'proofreadpage_badpagetext' => 'Format laman yang anda cuba simpan ini adalah tidak betul.',
	'proofreadpage_indexdupe' => 'Pautan pendua',
	'proofreadpage_indexdupetext' => 'Sesuatu laman tidak boleh disenaraikan lebih daripada sekali dalam laman indeks.',
	'proofreadpage_nologin' => 'Belum log masuk',
	'proofreadpage_nologintext' => 'Anda mesti [[Special:UserLogin|log masuk]] untuk mengubah suai status baca pruf laman.',
	'proofreadpage_notallowed' => 'Pengubahan tidak dibenarkan',
	'proofreadpage_notallowedtext' => 'Anda tidak dibenarkan mengubah status baca pruf laman ini.',
	'proofreadpage_dataconfig_badformatted' => 'Pepijat dalam konfigurasi data',
	'proofreadpage_dataconfig_badformattedtext' => 'Halaman [[Mediawiki:Proofreadpage index data config]] bukan dalam JSON yang diformatkan dengan betul.',
	'proofreadpage_number_expected' => 'Ralat: nilai angka dijangka',
	'proofreadpage_interval_too_large' => 'Ralat: selang terlalu besar',
	'proofreadpage_invalid_interval' => 'Ralat: selang tidak sah',
	'proofreadpage_nextpage' => 'Halaman berikutnya',
	'proofreadpage_prevpage' => 'Halaman sebelumnya',
	'proofreadpage_header' => 'Pengatas (tidak dimasukkan):',
	'proofreadpage_body' => 'Isi halaman (untuk dimasukkan):',
	'proofreadpage_footer' => 'Pembawah (tidak dimasukkan):',
	'proofreadpage_toggleheaders' => 'tukar kebolehnampakan bahagian yang tidak dimasukkan',
	'proofreadpage_quality0_category' => 'Tanpa teks',
	'proofreadpage_quality1_category' => 'Belum dibaca pruf',
	'proofreadpage_quality2_category' => 'Bermasalah',
	'proofreadpage_quality3_category' => 'Disemak',
	'proofreadpage_quality4_category' => 'Disahkan',
	'proofreadpage_quality0_message' => 'Laman ini tidak perlu dibaca pruf',
	'proofreadpage_quality1_message' => 'Laman ini belum dibaca pruf',
	'proofreadpage_quality2_message' => 'Masalah timbul ketika membaca pruf laman ini',
	'proofreadpage_quality3_message' => 'Laman ini telah dibaca pruf',
	'proofreadpage_quality4_message' => 'Laman ini telah disahkan',
	'proofreadpage_index_status' => 'Status indeks',
	'proofreadpage_index_size' => 'Bilangan halaman',
	'proofreadpage_specialpage_label_orderby' => 'Isih mengikut:',
	'proofreadpage_specialpage_label_key' => 'Cari:',
	'proofreadpage_specialpage_label_sortascending' => 'Isih tertib menaik',
	'proofreadpage_alphabeticalorder' => 'Susunan abjad',
	'proofreadpage_index_listofpages' => 'Senarai halaman',
	'proofreadpage_image_message' => 'Pautan ke halaman indeks',
	'proofreadpage_page_status' => 'Status halaman',
	'proofreadpage_js_attributes' => 'Pengarang Judul Tahun Penerbit',
	'proofreadpage_index_attributes' => 'Pengarang
Judul
Tahun|Tahun diterbitkan
Penerbit
Sumber
Imej|Imej kulit
Jumlah halaman||20
Catatan||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|laman|laman}}',
	'proofreadpage_specialpage_legend' => 'Cari laman indeks',
	'proofreadpage_specialpage_searcherror' => 'Ralat dalam enjin pencarian',
	'proofreadpage_specialpage_searcherrortext' => 'Enjin pencarian tidak berfungsi. Maaf atas sebarang kesulitan.',
	'proofreadpage_source' => 'Sumber',
	'proofreadpage_source_message' => 'Edisi imbasan yang digunakan untuk membuktikan teks ini',
	'right-pagequality' => 'Mengubahsuai bendera mutu laman',
	'proofreadpage-section-tools' => 'Alatan baca pruf',
	'proofreadpage-group-zoom' => 'Zum',
	'proofreadpage-group-other' => 'Lain-lain',
	'proofreadpage-button-toggle-visibility-label' => 'Tunjukkan/sorokkan pengatas dan pembawah laman ini',
	'proofreadpage-button-zoom-out-label' => 'Zum jauh',
	'proofreadpage-button-reset-zoom-label' => 'Set semula zum',
	'proofreadpage-button-zoom-in-label' => 'Zum dekat',
	'proofreadpage-button-toggle-layout-label' => 'Susun atur menegak/melintang',
	'proofreadpage-preferences-showheaders-label' => 'Tunjukkan ruangan pengatas dan pembawah ketika menyunting dalam ruang nama {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Gunakan susun atur melintang ketika menyunting dalam ruang nama {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Metadata buku-buku dari {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadata buku-buku yang diuruskan oleh ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Skema tidak dijumpai',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Skema $1 tidak dijumpai.',
	'proofreadpage-disambiguationspage' => 'Template:nyahkekaburan',
);

/** Mirandese (Mirandés)
 * @author Malafaya
 */
$messages['mwl'] = array(
	'proofreadpage_namespace' => 'Páigina',
);

/** Erzya (эрзянь)
 * @author Amdf
 * @author Botuzhaleny-sodamo
 */
$messages['myv'] = array(
	'proofreadpage_nextpage' => 'Седе тов ве лопа',
	'proofreadpage_index_attributes' => 'Сёрмадыцясь
Конаксось
Иесь|Нолдавкс иесь
Нолдыцясь
Лисьмапрясь
Неевтесь|Лангаксонь неевтесь
Лопатне||20
Мельть-арьсемат||10',
);

/** Nahuatl (Nāhuatl)
 * @author Fluence
 */
$messages['nah'] = array(
	'proofreadpage_image' => 'īxiptli',
	'proofreadpage_nextpage' => 'Niman zāzanilli',
	'proofreadpage_prevpage' => 'Achto zāzanilli',
);

/** Neapolitan (Nnapulitano)
 * @author Chelin
 */
$messages['nap'] = array(
	'indexpages' => "Elenco dde paggene 'e énnece",
	'pageswithoutscans' => 'Paggene senza scans',
	'proofreadpage_desc' => "Consente 'nu facile cunfrunto tra 'nu testo e a soja scansione originale",
	'proofreadpage_image' => 'Fiùra',
	'proofreadpage_index' => 'Ennece',
	'proofreadpage_index_expected' => 'Errore: previsto énnece',
	'proofreadpage_nosuch_index' => 'Errore: énnece nun presente',
	'proofreadpage_nosuch_file' => 'Errore: file nun presiente',
	'proofreadpage_badpage' => 'Formato errato',
	'proofreadpage_badpagetext' => "'O formato dda paggena che se è tentato 'e salva nun è corrette.",
	'proofreadpage_indexdupe' => 'Cullegamento duplicato',
	'proofreadpage_indexdupetext' => "E paggene nun possono essere elencate cchiù 'e na vota su 'na paggena 'e énnece.",
	'proofreadpage_nologin' => 'Acciesso nun affettuato',
	'proofreadpage_nologintext' => "Ppe càgna 'o stato 'e verifica dde paggene, dev essere [[Special:UserLogin|connetto]].",
	'proofreadpage_notallowed' => 'Càgnamento nun cunsentito',
	'proofreadpage_notallowedtext' => "Nun si autorizzato a càgna 'o stato 'e verifica 'e chista paggena.",
	'proofreadpage_dataconfig_badformatted' => "Prublema dint'â cunfigurazzione dde date",
	'proofreadpage_dataconfig_badformattedtext' => "'A paggena [[Mediawiki:Proofreadpage index data config]] nun è dint'ô 'nu formato JSON corrette.",
	'proofreadpage_number_expected' => 'Errore: previsto valore nummerico',
	'proofreadpage_interval_too_large' => 'Errore: intervallo tropo ampio',
	'proofreadpage_invalid_interval' => 'Errore: intervallo nun valido',
	'proofreadpage_nextpage' => 'Paggena successiva',
	'proofreadpage_prevpage' => 'Paggena precedente',
	'proofreadpage_header' => 'Testata (nun inclusa):',
	'proofreadpage_body' => 'Cuorpo dda paggena (a include):',
	'proofreadpage_footer' => "Piè 'e paggena (nun incluse)",
	'proofreadpage_toggleheaders' => 'attiva/disattiva a visibilità dde sezzione nun incluse',
	'proofreadpage_quality0_category' => 'Senza testo',
	'proofreadpage_quality1_category' => 'A corregge',
	'proofreadpage_quality2_category' => 'Prublemateco',
	'proofreadpage_quality3_category' => 'Corrette',
	'proofreadpage_quality4_category' => 'Verificate',
	'proofreadpage_quality0_message' => "Chista paggena nun necessita 'e essere corrette",
	'proofreadpage_quality1_message' => 'Chista paggena nun è stata corrette',
	'proofreadpage_quality2_message' => "C'è stato 'nu prublema dint'â correzzione 'e chista paggena",
	'proofreadpage_quality3_message' => 'Chista paggena è stata corrette',
	'proofreadpage_quality4_message' => 'Chista paggena è stata cunvalidate',
	'proofreadpage_index_status' => 'Stato avanzzamiento',
	'proofreadpage_index_size' => "Nummero 'e paggene",
	'proofreadpage_specialpage_label_orderby' => 'Ordina ppe:',
	'proofreadpage_specialpage_label_key' => 'Circa:',
	'proofreadpage_specialpage_label_sortascending' => 'Ordinamento crescente',
	'proofreadpage_alphabeticalorder' => 'Ordene alfabetico',
	'proofreadpage_index_listofpages' => 'Lista dde paggene',
	'proofreadpage_image_message' => "Cullegamento dint'â paggena énnece",
	'proofreadpage_page_status' => 'Status dda paggena',
	'proofreadpage_js_attributes' => 'Autore Titolo Anno Editore',
	'proofreadpage_index_attributes' => "Autore
Titolo
Anno|Anno 'e pubblicazzione
Editore
Funte
Fiùre|Fiùre 'e coppertina
Paggene||20
Note||10",
	'proofreadpage_pages' => '$2 {{PLURAL:$1|paggena|paggene}}',
	'proofreadpage_specialpage_legend' => 'Circa tra e paggene énnece',
	'proofreadpage_specialpage_searcherror' => "Errore dint'ô motore 'e ricerca",
	'proofreadpage_specialpage_searcherrortext' => "'O motore 'e ricerca nun funzziona. Ci scusiamo ppe 'o disaggio.",
	'proofreadpage_source' => 'Funte',
	'proofreadpage_source_message' => 'Edizzione scanusciuta utilizzata ppe ricava chisto testo',
	'right-pagequality' => 'Càgna a qualità dda paggena',
	'proofreadpage-section-tools' => 'Strumiente proofread',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Atro',
	'proofreadpage-button-toggle-visibility-label' => 'Vere/nasconde testata e piè dda paggena',
	'proofreadpage-button-zoom-out-label' => 'Zoom a vascio',
	'proofreadpage-button-reset-zoom-label' => 'Ripristina zoom',
	'proofreadpage-button-zoom-in-label' => 'Zoom avanzato',
	'proofreadpage-button-toggle-layout-label' => 'Layout verticale/orizzuntale',
	'proofreadpage-preferences-showheaders-label' => "Vere a testata e 'o piè dda paggena durante o' càgnamento ddo namespace {{ns:page}}",
	'proofreadpage-preferences-horizontal-layout-label' => "Usa 'o layout orrizzontale durante 'o càgnamento dint'ô namespace {{ns:page}}",
	'proofreadpage-indexoai-repositoryName' => "Metadati dde libbri 'e {{SITENAME}}",
	'proofreadpage-indexoai-eprint-content-text' => "Metadati dde libbri 'e ProofreadPage.",
	'proofreadpage-indexoai-error-schemanotfound' => 'Schema nun truvato',
	'proofreadpage-indexoai-error-schemanotfound-text' => "'O schema $1 nun è stato truovato.",
);

/** Norwegian Bokmål (norsk bokmål)
 * @author Laaknor
 * @author Nghtwlkr
 * @author Simny
 */
$messages['nb'] = array(
	'indexpages' => 'Liste over innholdsfortegnelser',
	'pageswithoutscans' => 'Sider uten skanninger',
	'proofreadpage_desc' => 'Tillat lett sammenligning av tekst med originalskanningen',
	'proofreadpage_image' => 'Bilde',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => 'Feil: Indeks forventet',
	'proofreadpage_nosuch_index' => 'Feil: ingen slik indeks',
	'proofreadpage_nosuch_file' => 'Feil: ingen slik fil',
	'proofreadpage_badpage' => 'Feil format',
	'proofreadpage_badpagetext' => 'Siden du prøver å lagre har galt format.',
	'proofreadpage_indexdupe' => 'Duplikat lenke',
	'proofreadpage_indexdupetext' => 'Sider kan ikke listes mer enn en gang på en indeksside.',
	'proofreadpage_nologin' => 'Ikke innlogget',
	'proofreadpage_nologintext' => 'Du må være [[Special:UserLogin|innlogget]] for å kunne forandre status på korrekturlesningen på sider.',
	'proofreadpage_notallowed' => 'Å gjøre en forandring er ikke lov',
	'proofreadpage_notallowedtext' => 'Du har ikke rettigheter til å endre korrekturlesningen på denne siden.',
	'proofreadpage_number_expected' => 'Feil: Numerisk verdi forventet',
	'proofreadpage_interval_too_large' => 'Feil: Intervall for stort',
	'proofreadpage_invalid_interval' => 'Feil: ugyldig intervall',
	'proofreadpage_nextpage' => 'Neste side',
	'proofreadpage_prevpage' => 'Forrige side',
	'proofreadpage_header' => 'Hodeseksjon (inkluderes ikke):',
	'proofreadpage_body' => 'Hoveddel (skal inkluderes):',
	'proofreadpage_footer' => 'Fotseksjon (inkluderes ikke):',
	'proofreadpage_toggleheaders' => 'slå av/på synlighet for ikke-inkluderte seksjoner',
	'proofreadpage_quality0_category' => 'Uten tekst',
	'proofreadpage_quality1_category' => 'Rå',
	'proofreadpage_quality2_category' => 'Ufullstendig',
	'proofreadpage_quality3_category' => 'Korrekturlest',
	'proofreadpage_quality4_category' => 'Validert',
	'proofreadpage_quality0_message' => 'Denne siden trenger ikke korrekturleses',
	'proofreadpage_quality1_message' => 'Denne siden er ikke korrekturlest',
	'proofreadpage_quality2_message' => 'Det oppsto et problem når denne siden skulle korrekturleses',
	'proofreadpage_quality3_message' => 'Denne siden er korrekturlest',
	'proofreadpage_quality4_message' => 'Denne siden er godkjent',
	'proofreadpage_index_listofpages' => 'Liste over sider',
	'proofreadpage_image_message' => 'Lenke til indekssiden',
	'proofreadpage_page_status' => 'Sidestatus',
	'proofreadpage_js_attributes' => 'Forfatter Tittel År Utgiver',
	'proofreadpage_index_attributes' => 'Forfatter
Tittel
År|Utgivelsesår
Utgiver
Kilde
Bilde|Omslagsbilde
Sider||20
Merknader||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|side|sider}}',
	'proofreadpage_specialpage_legend' => 'Søk i indekssider',
	'proofreadpage_source' => 'Kilde',
	'proofreadpage_source_message' => 'Scannet utgave brukt for å etablere denne teksten',
	'right-pagequality' => 'Endre sidens kvalitetsflagg',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Annet',
	'proofreadpage-button-zoom-out-label' => 'Zoom ut',
	'proofreadpage-button-reset-zoom-label' => 'Tilbakestill zoom',
	'proofreadpage-button-zoom-in-label' => 'Zoom inn',
);

/** Low German (Plattdüütsch)
 * @author Slomox
 */
$messages['nds'] = array(
	'proofreadpage_desc' => 'Verlöövt dat bequeme Verglieken vun Text mit’n Original-Scan',
	'proofreadpage_image' => 'Bild',
	'proofreadpage_index' => 'Index',
	'proofreadpage_nextpage' => 'Nächste Siet',
	'proofreadpage_prevpage' => 'Vörige Siet',
	'proofreadpage_header' => 'Koppreeg (noinclude):',
	'proofreadpage_body' => 'Hööfttext (warrt inbunnen):',
	'proofreadpage_footer' => 'Footreeg (noinclude):',
	'proofreadpage_toggleheaders' => 'noinclude-Afsneed in-/utblennen',
	'proofreadpage_quality0_category' => 'Ahn Text',
	'proofreadpage_quality1_category' => 'nich korrekturleest',
	'proofreadpage_quality2_category' => 'problemaatsch',
	'proofreadpage_quality3_category' => 'korrekturleest',
	'proofreadpage_quality4_category' => 'Fertig',
	'proofreadpage_index_listofpages' => 'Siedenlist',
	'proofreadpage_image_message' => 'Lenk na de Indexsiet',
	'proofreadpage_page_status' => 'Siedenstatus',
	'proofreadpage_js_attributes' => 'Schriever Titel Johr Verlag',
	'proofreadpage_index_attributes' => 'Schriever
Titel
Johr|Johr, dat dat rutkamen is
Verlag
Born
Bild|Titelbild
Sieden||20
Anmarkungen||10',
);

/** Low Saxon (Netherlands) (Nedersaksies)
 * @author Servien
 */
$messages['nds-nl'] = array(
	'proofreadpage_index_attributes' => 'Auteur
Titel
Jaor|Jaar van publicatie
Uutgever
Bron
Aofbeelding|Umslag
Ziejen||20
Opmarkingen||10',
);

/** Dutch (Nederlands)
 * @author Donarreiskoffer
 * @author McDutchie
 * @author SPQRobin
 * @author Siebrand
 */
$messages['nl'] = array(
	'indexpages' => "Lijst van index-pagina's",
	'pageswithoutscans' => "Pagina's zonder scans",
	'proofreadpage_desc' => 'Maakt het mogelijk teksten eenvoudig te vergelijken met de oorspronkelijke scan',
	'proofreadpage_image' => 'Afbeelding',
	'proofreadpage_index' => 'Index',
	'proofreadpage_index_expected' => 'Fout: er werd een index verwacht',
	'proofreadpage_nosuch_index' => 'Fout: de index bestaat niet',
	'proofreadpage_nosuch_file' => 'Fout: het opgegeven bestand bestaat niet',
	'proofreadpage_badpage' => 'Verkeerde formaat',
	'proofreadpage_badpagetext' => 'Het formaat van de pagina die u probeerde op te slaan is onjuist.',
	'proofreadpage_indexdupe' => 'Dubbele koppeling',
	'proofreadpage_indexdupetext' => "Pagina's kunnen niet meer dan één keer op een indexpagina weergegeven worden.",
	'proofreadpage_nologin' => 'Niet aangemeld',
	'proofreadpage_nologintext' => "U moet [[Special:UserLogin|aanmelden]] om de proefleesstatus van pagina's te kunnen wijzigen.",
	'proofreadpage_notallowed' => 'Wijzigen is niet toegestaan',
	'proofreadpage_notallowedtext' => 'U mag de proefleesstatus van deze pagina niet wijzigen.',
	'proofreadpage_dataconfig_badformatted' => 'Probleem met de gegevensinstellingen',
	'proofreadpage_dataconfig_badformattedtext' => 'De pagina "[[Mediawiki:Proofreadpage index data config]]" bevat ongeldige JSON-gegevens.',
	'proofreadpage_number_expected' => 'Fout: er werd een numerieke waarde verwacht',
	'proofreadpage_interval_too_large' => 'Fout: het interval is te groot',
	'proofreadpage_invalid_interval' => 'Fout: er is een ongeldige interval opgegeven',
	'proofreadpage_nextpage' => 'Volgende pagina',
	'proofreadpage_prevpage' => 'Vorige pagina',
	'proofreadpage_header' => 'Koptekst (geen inclusie):',
	'proofreadpage_body' => 'Broodtekst (voor transclusie):',
	'proofreadpage_footer' => 'Voettekst (geen inclusie):',
	'proofreadpage_toggleheaders' => 'zichtbaarheid elementen zonder transclusie wijzigen',
	'proofreadpage_quality0_category' => 'Geen tekst',
	'proofreadpage_quality1_category' => 'Onbewerkt',
	'proofreadpage_quality2_category' => 'Onvolledig',
	'proofreadpage_quality3_category' => 'Proefgelezen',
	'proofreadpage_quality4_category' => 'Gecontroleerd',
	'proofreadpage_quality0_message' => 'Deze pagina hoeft niet te worden proefgelezen',
	'proofreadpage_quality1_message' => 'Deze pagina is niet proefgelezen',
	'proofreadpage_quality2_message' => 'Er was een probleem tijdens het controleren van deze pagina',
	'proofreadpage_quality3_message' => 'Deze pagina is proefgelezen',
	'proofreadpage_quality4_message' => 'Deze pagina is gecontroleerd',
	'proofreadpage_index_status' => 'Indexstatus',
	'proofreadpage_index_size' => "Aantal pagina's",
	'proofreadpage_specialpage_label_orderby' => 'Sorteren op:',
	'proofreadpage_specialpage_label_key' => 'Zoeken:',
	'proofreadpage_specialpage_label_sortascending' => 'Oplopend sorteren',
	'proofreadpage_alphabeticalorder' => 'Alfabetische volgorde',
	'proofreadpage_index_listofpages' => 'Paginalijst',
	'proofreadpage_image_message' => 'Verwijziging naar de indexpagina',
	'proofreadpage_page_status' => 'Paginastatus',
	'proofreadpage_js_attributes' => 'Auteur Titel Jaar Uitgever',
	'proofreadpage_index_attributes' => "Auteur
Titel
Jaar|Jaar van publicatie
Uitgever
Bron
Afbeelding|Omslag
Pagina's||20
Opmerkingen||10",
	'proofreadpage_pages' => "$2 {{PLURAL:$1|pagina|pagina's}}",
	'proofreadpage_specialpage_legend' => "Indexpagina's doorzoeken",
	'proofreadpage_specialpage_searcherror' => 'Fout in de zoekmachine',
	'proofreadpage_specialpage_searcherrortext' => 'De zoekmachine werkt niet. Excuses voor het ongemak.',
	'proofreadpage_source' => 'Bron',
	'proofreadpage_source_message' => 'Gescande versie waarop deze tekst is gebaseerd',
	'right-pagequality' => 'Kwaliteitsmarkering voor de pagina wijzigen',
	'proofreadpage-section-tools' => 'Hulpmiddelen voor controleren',
	'proofreadpage-group-zoom' => 'Zoomen',
	'proofreadpage-group-other' => 'Anders',
	'proofreadpage-button-toggle-visibility-label' => 'De kop- en voettekst van deze pagina weergeven of verbergen',
	'proofreadpage-button-zoom-out-label' => 'Uitzoomen',
	'proofreadpage-button-reset-zoom-label' => 'Zoomniveau herinitialiseren',
	'proofreadpage-button-zoom-in-label' => 'Inzoomen',
	'proofreadpage-button-toggle-layout-label' => 'Verticale/horizontale vormgeving',
	'proofreadpage-preferences-showheaders-label' => 'Kop- en voettekst velden weergeven als in de naamruimte {{ns:page}} wordt bewerkt',
	'proofreadpage-preferences-horizontal-layout-label' => 'Horizontale vormgeving gebruiken tijdens het bewerken in de naamruimte {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Metadata voor boeken van {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadata voor boeken beheerd via ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Schema niet gevonden',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Het schema $1 is niet aangetroffen.',
);

/** Norwegian Nynorsk (norsk nynorsk)
 * @author Diupwijk
 * @author Gunnernett
 * @author Harald Khan
 * @author Jon Harald Søby
 * @author Njardarlogar
 */
$messages['nn'] = array(
	'proofreadpage_desc' => 'Tillèt enkel samanlikning av tekst med originalskanning.',
	'proofreadpage_image' => 'Bilete',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => 'Feil: Indeks forventa',
	'proofreadpage_nosuch_index' => 'Feil: ingen slik indeks',
	'proofreadpage_nosuch_file' => 'Feil: inga slik fil',
	'proofreadpage_nologin' => 'Ikkje innlogga',
	'proofreadpage_number_expected' => 'Feil: Talverdi forventa',
	'proofreadpage_interval_too_large' => 'Feil: for stort intervall',
	'proofreadpage_invalid_interval' => 'Feil: ugyldig intervall',
	'proofreadpage_nextpage' => 'Neste sida',
	'proofreadpage_prevpage' => 'Førre sida',
	'proofreadpage_header' => 'Hovudseksjon (ikkje inkludert):',
	'proofreadpage_body' => 'Hovuddel (inkludert):',
	'proofreadpage_footer' => 'Fotseksjon (ikkje inludert):',
	'proofreadpage_toggleheaders' => 'syna/ikkje syna seksjonar ikkje inkluderte på sida',
	'proofreadpage_quality0_category' => 'Utan tekst',
	'proofreadpage_quality1_category' => 'Ikkje korrekturlest',
	'proofreadpage_quality2_category' => 'Problematisk',
	'proofreadpage_quality3_category' => 'Korrekturlest',
	'proofreadpage_quality4_category' => 'Validert',
	'proofreadpage_index_listofpages' => 'Lista over sider',
	'proofreadpage_image_message' => 'Lenkja til indekssida',
	'proofreadpage_page_status' => 'Sidestatus',
	'proofreadpage_js_attributes' => 'Forfattar Tittel År Utgjevar',
	'proofreadpage_index_attributes' => 'Forfattar
Tittel
År|Utgjeve år
Utgjevar
Kjelde
Bilete|Omslagsbilete
Sider||20
Merknader||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|side|sider}}',
	'proofreadpage_source' => 'Kjelde',
);

/** Northern Sotho (Sesotho sa Leboa)
 * @author Mohau
 */
$messages['nso'] = array(
	'proofreadpage_nextpage' => 'Letlakala lago latela',
	'proofreadpage_prevpage' => 'Letlaka lago feta',
);

/** Occitan (occitan)
 * @author Cedric31
 */
$messages['oc'] = array(
	'indexpages' => "Lista de las paginas d'indèx",
	'proofreadpage_desc' => 'Permet una comparason aisida entre lo tèxte e la numerizacion originala',
	'proofreadpage_image' => 'Imatge',
	'proofreadpage_index' => 'Indèx',
	'proofreadpage_index_expected' => 'Error : un indèx es esperat',
	'proofreadpage_nosuch_index' => "Error : l'indèx es pas estat trobat",
	'proofreadpage_nosuch_file' => 'Error : lo fichièr es pas estat trobat',
	'proofreadpage_badpage' => 'Format marrit',
	'proofreadpage_badpagetext' => "Lo format de la pagina qu'ensajatz de publicar es incorrècte.",
	'proofreadpage_indexdupe' => 'Ligam en doble',
	'proofreadpage_indexdupetext' => "Las paginas pòdon pas èsser listadas mai d'un còp sus una pagina d'indèx.",
	'proofreadpage_nologin' => 'Pas connectat',
	'proofreadpage_nologintext' => "Vos cal èsser [[Special:UserLogin|connectat]] per modificar l'estatut de correccion de las paginas.",
	'proofreadpage_notallowed' => 'Cambiament pas autorizat.',
	'proofreadpage_notallowedtext' => "Sètz pas autorizat(ada) a modificar l'estatut de correccion d'aquesta pagina.",
	'proofreadpage_number_expected' => 'Error : una valor numerica es esperada',
	'proofreadpage_interval_too_large' => 'Error : interval tròp grand',
	'proofreadpage_invalid_interval' => 'Error : interval invalid',
	'proofreadpage_nextpage' => 'Pagina seguenta',
	'proofreadpage_prevpage' => 'Pagina precedenta',
	'proofreadpage_header' => 'Entèsta (noinclude) :',
	'proofreadpage_body' => 'Contengut (transclusion) :',
	'proofreadpage_footer' => 'Pè de pagina (noinclude) :',
	'proofreadpage_toggleheaders' => 'amagar/far veire las seccions noinclude',
	'proofreadpage_quality0_category' => 'Sens tèxte',
	'proofreadpage_quality1_category' => 'Pagina pas corregida',
	'proofreadpage_quality2_category' => 'Pagina amb problèma',
	'proofreadpage_quality3_category' => 'Pagina corregida',
	'proofreadpage_quality4_category' => 'Pagina validada',
	'proofreadpage_quality0_message' => 'Aquesta pagina a pas besonh d’èsser relegida',
	'proofreadpage_quality1_message' => 'Aquesta pagina es pas estada relegida',
	'proofreadpage_quality2_message' => "I a agut un problèma al moment de la relectura d'aquesta pagina",
	'proofreadpage_quality3_message' => 'Aquesta pagina es estada relegida',
	'proofreadpage_quality4_message' => 'Aquesta pagina es estada validada',
	'proofreadpage_index_listofpages' => 'Lista de las paginas',
	'proofreadpage_image_message' => "Ligam cap a l'indèx",
	'proofreadpage_page_status' => 'Estat de la pagina',
	'proofreadpage_js_attributes' => 'Autor Títol Annada Editor',
	'proofreadpage_index_attributes' => 'Autor
Títol
Annada|Annada de publicacion
Editor
Font
Imatge|Imatge en cobertura
Paginas||20
Comentaris||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pagina|paginas}}',
	'proofreadpage_specialpage_legend' => 'Recercar dins las paginas d’indèx',
	'proofreadpage_source' => 'Font',
	'proofreadpage_source_message' => "Edicion numerizada d'ont es eissit aqueste tèxte",
);

/** Oriya (ଓଡ଼ିଆ)
 * @author Ansumang
 * @author Jnanaranjan Sahu
 * @author Odisha1
 * @author Psubhashish
 */
$messages['or'] = array(
	'indexpages' => 'ସୂଚୀପତ୍ର ହୋଇଥିବା ପୃଷ୍ଠାଗୁଡିକ',
	'pageswithoutscans' => 'ସ୍କାନ ନଥିବା ପୃଷ୍ଟା',
	'proofreadpage_desc' => 'ପ୍ରକୃତ ଛବିର ଏହି ଲେଖା ସହ ଏକ ସରଳ  ପରଖ କରିବେ',
	'proofreadpage_image' => 'ପ୍ରତିକୃତି',
	'proofreadpage_index' => 'ସୂଚୀ',
	'proofreadpage_index_expected' => 'ଅସୁବିଧା: ସୂଚୀ ଦରକାର',
	'proofreadpage_nosuch_index' => 'ଅସୁବିଧା: ଏହିପରି ସୂଚୀ ନାହିଁ',
	'proofreadpage_nosuch_file' => 'ଅସୁବିଧା: ଏହିପରି କିଛି ଫାଇଲ ନାହିଁ',
	'proofreadpage_badpage' => 'ଭୁଲ ପ୍ରକାର',
	'proofreadpage_badpagetext' => 'ଆପଣ ସାଇତିବାକୁ ଚେଷ୍ଟା କରୁଥିବା ପୃଷ୍ଠାଟିର ପ୍ରକାର ଅଲଗା ଅଛି ।',
	'proofreadpage_indexdupe' => 'ନକଲି ଲିଙ୍କ',
	'proofreadpage_indexdupetext' => 'ସୂଚୀପତ୍ରରେ ଗୋଟିଏ ପୃଷ୍ଠା ଏକାଧିକ ଥର ରହି ପାରିବ ନାହିଁ ।',
	'proofreadpage_nologin' => 'ଲଗ‌‌ ଇନ କରିନାହାନ୍ତି',
	'proofreadpage_nologintext' => 'ଏହି ପୃଷ୍ଠାର ପ୍ରମାଣପତ୍ର ବଦଳେଇବା ପାଇଁ ଆପଣ [[Special:UserLogin|logged in]] ହୋଇଥିବା ଦରକାର ।',
	'proofreadpage_notallowed' => 'ବଦଳର ଅନୁମତି ନାହିଁ',
	'proofreadpage_notallowedtext' => 'ଆପଣଙ୍କର ଏହି ପୃଷ୍ଠାର ପ୍ରମାଣପତ୍ର ସ୍ଥିତି ବଦଳାଇବାର ଅଧିକାର ନାହିଁ ।',
	'proofreadpage_dataconfig_badformatted' => 'ଡାଟା ସଜାଣିରେ ଅସୁବିଧା ଅଛି',
	'proofreadpage_dataconfig_badformattedtext' => '[[Mediawiki:Proofreadpage index data config]]ପୃଷ୍ଠାଟି ଠିକ-ପ୍ରକାର JSONରେ  ନାହିଁ ।',
	'proofreadpage_number_expected' => 'ଅସୁବିଧା: ସଂଖ୍ୟା ଆସିବାକଥା',
	'proofreadpage_interval_too_large' => 'ଅସୁବିଧା: ଅତ୍ୟଧିକ ବିରତି',
	'proofreadpage_invalid_interval' => 'ଅସୁବିଧା: ଅବୈଧ ବିରତି',
	'proofreadpage_nextpage' => 'ପର ପୃଷ୍ଠା',
	'proofreadpage_prevpage' => 'ଆଗ ପୃଷ୍ଠା',
	'proofreadpage_header' => 'ଶୀର୍ଷକ (noinclude):',
	'proofreadpage_body' => 'ପୃଷ୍ଠା ଲେଖା(ଯୋଡିବାକୁ ଥିବା)',
	'proofreadpage_footer' => 'ମୂଳ(ଅଲଗାଥିବା)',
	'proofreadpage_toggleheaders' => 'ଅଲଗାଥିବା ଭାଗର ଦେଖଣାଟିକୁ ବଦଳାଇବେ',
	'proofreadpage_quality0_category' => 'ଲେଖାନଥିବା',
	'proofreadpage_quality1_category' => 'ପ୍ରମାଣିତ ହୋଇନଥିବା',
	'proofreadpage_quality2_category' => 'ଅସୁବିଧାଥିବା',
	'proofreadpage_quality3_category' => 'Proofread',
	'proofreadpage_quality4_category' => 'ବୈଧ',
	'proofreadpage_quality0_message' => 'ଏହି ପୃଷ୍ଠା ପ୍ରମାଣିତ ହେବା ଦରକାର ନାହିଁ',
	'proofreadpage_quality1_message' => 'ଏହି ପୃଷ୍ଠାଟି ପ୍ରମାଣିତ ହୋଇନାହିଁ',
	'proofreadpage_quality2_message' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ପ୍ରମାଣିତ କରିବାବେଳେ ଅସୁବିଧା ହେଲା',
	'proofreadpage_quality3_message' => 'ଏହି ପୃଷ୍ଠାଟି ପ୍ରମାଣିତ ହୋଇସାରିଛି',
	'proofreadpage_quality4_message' => 'ଏହି ପୃଷ୍ଠାଟି ବୈଧ ହୋଇସାରିଛି',
	'proofreadpage_index_status' => 'ସୂଚୀପତ୍ର ସ୍ଥିତି',
	'proofreadpage_index_size' => 'ପୃଷ୍ଠା ସଂଖ୍ୟା',
	'proofreadpage_specialpage_label_orderby' => 'ଅନୁସାରେ ରଖିବେ:',
	'proofreadpage_specialpage_label_key' => 'ଖୋଜିବା:',
	'proofreadpage_specialpage_label_sortascending' => 'ସାନରୁ ବଡ଼ କ୍ରମେ ସଜାନ୍ତୁ',
	'proofreadpage_alphabeticalorder' => 'ଆକ୍ଷରିକ',
	'proofreadpage_index_listofpages' => 'ପୃଷ୍ଠାମାନଙ୍କର ତାଲିକା',
	'proofreadpage_image_message' => 'ସୂଚୀ ପୃଷ୍ଠାକୁ ଲିଙ୍କ କରିବେ',
	'proofreadpage_page_status' => 'ପୃଷ୍ଠାର ସ୍ଥିତି',
	'proofreadpage_js_attributes' => 'ଲେଖକ ଶୀର୍ଷକ ବର୍ଷ ପ୍ରକାଶକ',
	'proofreadpage_index_attributes' => 'ଲେଖକ
ଶୀର୍ଷକ
ବର୍ଷ|ପ୍ରକାଶନ ବର୍ଷ
ପ୍ରକାଶକ
ଛବି|ମଲାଟ ଛବି
ପୃଷ୍ଠା| | ୨୦
ଟିପ୍ପଣୀ| | ୧୦',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|ପୃଷ୍ଠା|ପୃଷ୍ଠାସବୁ}}',
	'proofreadpage_specialpage_legend' => 'ସୂଚୀପତ୍ର ପୃଷ୍ଠାଗୁଡିକୁ ଖୋଜିବେ',
	'proofreadpage_specialpage_searcherror' => 'ଖୋଜିବାରେ ଅସୁବିଧା',
	'proofreadpage_specialpage_searcherrortext' => 'ଏବେ ଖୋଜି ହେଉନାହିଁ, ଅସୁବିଧା ଯୋଗୁ ଦୁଖିତଃ ।',
	'proofreadpage_source' => 'ମୂଳାଧାର',
	'proofreadpage_source_message' => 'ଏହି ଲେଖାଗୁଡ଼ିକ ସ୍ଥାୟୀ କରିବା ପାଇଁ ସ୍କାନ ହୋଇଥିବା ସଂସ୍କରଣ',
	'right-pagequality' => 'ପୃଷ୍ଠାର କିସମ ଘୋଷଣାନାମାଟିକୁ ବଦଳାଇବେ',
	'proofreadpage-section-tools' => 'ପ୍ରମାଣପତ୍ର ଉପକରଣ',
	'proofreadpage-group-zoom' => 'ବଡ଼କରି ଦେଖାଇବେ',
	'proofreadpage-group-other' => 'ବାକି',
	'proofreadpage-button-toggle-visibility-label' => 'ଏହି ପୃଷ୍ଠାର ଶୀର୍ଷକ ଏବଂ ମୂଳଟିକୁ ଦେଖାଇବେ/ଲୁଚାଇବେ',
	'proofreadpage-button-zoom-out-label' => 'ସାନ କରନ୍ତୁ',
	'proofreadpage-button-reset-zoom-label' => 'ମୂଳ ଆକାର',
	'proofreadpage-button-zoom-in-label' => 'ବଡ କରନ୍ତୁ',
	'proofreadpage-button-toggle-layout-label' => 'ଭୁଲମ୍ବ/ସମାନ୍ତରାଳ ସଜାଣି',
	'proofreadpage-preferences-showheaders-label' => '{{ns:page}} ନେମସ୍ପେସରେ ବଦଳବେଳେ ଶୀର୍ଷକ ଏବଂ ମୂଳ ଦେଖାଇବେ',
	'proofreadpage-preferences-horizontal-layout-label' => '{{ns:page}} ନେମସ୍ପେସରେ ବଦଳ ବେଳେ ସମନ୍ତରାଳ ସଜାଣି ବ୍ୟବହାର କରିବେ',
	'proofreadpage-indexoai-repositoryName' => '{{SITENAME}}ରେ ଥିବା ବହିଗୁଡିକର ମେଟାଡାଟା',
	'proofreadpage-indexoai-eprint-content-text' => 'ପ୍ରମାଣପତ୍ର ପୃଷ୍ଠାଗୁଡିକଦ୍ଵାରା ପରିଚାଳିତ ହୋଇଥିବା ବହିଗୁଡିକର ମେଟାଡାଟା ।',
	'proofreadpage-indexoai-error-schemanotfound' => 'ସ୍କିମା ମିଳିଲା ନାହିଁ',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'ଏହି $1 ସ୍କିମା ମିଳିଲା ନାହିଁ ।',
);

/** Ossetic (Ирон)
 * @author Amikeco
 */
$messages['os'] = array(
	'proofreadpage_image' => 'ныв',
	'proofreadpage_nextpage' => 'Фæдылдзог фарс',
	'proofreadpage_prevpage' => 'Раздæры фарс',
);

/** Deitsch (Deitsch)
 * @author Xqt
 */
$messages['pdc'] = array(
	'proofreadpage_image' => 'Bild',
	'proofreadpage_nextpage' => 'Neegschtes Blatt',
	'proofreadpage_prevpage' => 'Letscht Blatt',
	'proofreadpage_index_listofpages' => 'Lischt vun Bledder',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|Blatt|Bledder}}',
	'proofreadpage-group-other' => 'Anneres',
);

/** Pälzisch (Pälzisch)
 * @author Manuae
 */
$messages['pfl'] = array(
	'proofreadpage_nextpage' => 'Negschd Said',
);

/** Polish (polski)
 * @author Beau
 * @author BeginaFelicysym
 * @author Olgak85
 * @author Przemub
 * @author Sp5uhe
 */
$messages['pl'] = array(
	'indexpages' => 'Spis stron indeksów',
	'pageswithoutscans' => 'Strony bez skanów',
	'proofreadpage_desc' => 'Umożliwia łatwe porównanie treści ze skanem oryginału',
	'proofreadpage_image' => 'Grafika',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => 'Błąd – oczekiwano indeksu',
	'proofreadpage_nosuch_index' => 'Błąd – nie ma takiego indeksu',
	'proofreadpage_nosuch_file' => 'Błąd – nie ma takiego pliku',
	'proofreadpage_badpage' => 'Zły format',
	'proofreadpage_badpagetext' => 'Format strony którą próbujesz zapisać jest nieprawidłowy.',
	'proofreadpage_indexdupe' => 'Zdublowany link',
	'proofreadpage_indexdupetext' => 'Strony nie mogą być wymienione więcej niż jeden raz na stronie indeksu.',
	'proofreadpage_nologin' => 'Niezalogowany',
	'proofreadpage_nologintext' => 'Musisz [[Special:UserLogin|zalogować się]], aby zmienić status proofreading strony.',
	'proofreadpage_notallowed' => 'Zmiana niedozwolona',
	'proofreadpage_notallowedtext' => 'Zmiana statusu proofreading tej strony przez Ciebie jest niedozwolona.',
	'proofreadpage_number_expected' => 'Błąd – oczekiwano liczby',
	'proofreadpage_interval_too_large' => 'Błąd – zbyt duży odstęp',
	'proofreadpage_invalid_interval' => 'Błąd – nieprawidłowy odstęp',
	'proofreadpage_nextpage' => 'Następna strona',
	'proofreadpage_prevpage' => 'Poprzednia strona',
	'proofreadpage_header' => 'Nagłówek (noinclude):',
	'proofreadpage_body' => 'Treść strony (załączany fragment):',
	'proofreadpage_footer' => 'Stopka (noinclude):',
	'proofreadpage_toggleheaders' => 'zmień widoczność sekcji noinclude',
	'proofreadpage_quality0_category' => 'Bez treści',
	'proofreadpage_quality1_category' => 'Nieskorygowana',
	'proofreadpage_quality2_category' => 'Problemy',
	'proofreadpage_quality3_category' => 'Skorygowana',
	'proofreadpage_quality4_category' => 'Uwierzytelniona',
	'proofreadpage_quality0_message' => 'Ta strona nie wymaga korekty',
	'proofreadpage_quality1_message' => 'Ta strona nie została skorygowana',
	'proofreadpage_quality2_message' => 'Wystąpił problem przy korekcie tej stronie',
	'proofreadpage_quality3_message' => 'Ta strona została skorygowana',
	'proofreadpage_quality4_message' => 'Ta strona została zatwierdzona',
	'proofreadpage_index_listofpages' => 'Spis stron',
	'proofreadpage_image_message' => 'Link do strony indeksowej',
	'proofreadpage_page_status' => 'Status strony',
	'proofreadpage_js_attributes' => 'Autor Tytuł Rok Wydawca',
	'proofreadpage_index_attributes' => 'Autor
Tytuł
Rok|Rok publikacji
Wydawca
Źródło
Ilustracja|Okładka
Strony||20
Uwagi||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|strona|strony|stron}}',
	'proofreadpage_specialpage_legend' => 'Szukaj stron indeksowych',
	'proofreadpage_specialpage_searcherror' => 'Błąd w wyszukiwarce',
	'proofreadpage_specialpage_searcherrortext' => 'Wyszukiwarka nie działa. Przepraszamy za kłopot.',
	'proofreadpage_source' => 'Źródło',
	'proofreadpage_source_message' => 'Zeskanowane wydanie wykorzystane do przygotowania tego tekstu',
	'right-pagequality' => 'Zmienianie statusu uwierzytelnienia strony',
	'proofreadpage-section-tools' => 'Narzędzia proofread',
	'proofreadpage-group-zoom' => 'Powiększenie',
	'proofreadpage-group-other' => 'Pozostałe',
	'proofreadpage-button-toggle-visibility-label' => 'Pokaż lub ukryj nagłówek i stopkę strony',
	'proofreadpage-button-zoom-out-label' => 'Pomniejsz',
	'proofreadpage-button-reset-zoom-label' => 'Powiększenie domyślne',
	'proofreadpage-button-zoom-in-label' => 'Powiększ',
	'proofreadpage-button-toggle-layout-label' => 'Zmień układ na poziomy lub pionowy',
	'proofreadpage-preferences-showheaders-label' => 'Pokaż pola nagłówka i stopki podczas edycji w przestrzeni nazw {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Użyj poziomego układu podczas edycji w przestrzeni nazw {{ns:page}}',
);

/** Piedmontese (Piemontèis)
 * @author Borichèt
 * @author Bèrto 'd Sèra
 * @author Dragonòt
 * @author පසිඳු කාවින්ද
 * @author 555
 */
$messages['pms'] = array(
	'indexpages' => 'Lista dle pàgine ëd tàula',
	'pageswithoutscans' => 'Pàgine sensa scansion',
	'proofreadpage_desc' => 'A rend bel fé confronté ëd test con la scansion original',
	'proofreadpage_image' => 'Figura',
	'proofreadpage_index' => 'Ìndess',
	'proofreadpage_index_expected' => 'Eror: a së spetava na tàula',
	'proofreadpage_nosuch_index' => 'Eror: tàula pa esistenta',
	'proofreadpage_nosuch_file' => "Eror: l'archivi a-i é pa",
	'proofreadpage_badpage' => 'Formà pa bon',
	'proofreadpage_badpagetext' => "Ël formà dla pàgina ch'a ha sërcà ëd salvé a l'é pa bon.",
	'proofreadpage_indexdupe' => 'Colegament duplicà',
	'proofreadpage_indexdupetext' => "Le pàgine a peulo pa esse listà pi 'd na vòta an sna pàgina ëd tàula.",
	'proofreadpage_nologin' => 'Pa rintrà ant ël sistema',
	'proofreadpage_nologintext' => 'It deve [[Special:UserLogin|intré ant ël sistema]] për modifiché lë stat ëd verifìca ëd le pàgine.',
	'proofreadpage_notallowed' => 'Cangiament pa possìbil',
	'proofreadpage_notallowedtext' => 'It peule pa cambié lë stat ëd verìfica dë sta pàgina-sì.',
	'proofreadpage_dataconfig_badformatted' => 'Eror ant la configurassion dij dat',
	'proofreadpage_dataconfig_badformattedtext' => "La pàgina [[Mediawiki:Proofreadpage index data config]] a l'é nen ëstàita butà për da bin an forma JSON.",
	'proofreadpage_number_expected' => 'Eror: valor numérich spetà',
	'proofreadpage_interval_too_large' => 'Eror: antërval tròp largh',
	'proofreadpage_invalid_interval' => 'Eror: antërval pa bon',
	'proofreadpage_nextpage' => 'Pàgina anans',
	'proofreadpage_prevpage' => 'Pàgina andré',
	'proofreadpage_header' => 'Testà (da nen anclude):',
	'proofreadpage_body' => 'Còrp dla pàgina (da transclude):',
	'proofreadpage_footer' => 'Pè (da nen anclude)',
	'proofreadpage_toggleheaders' => 'smon/stërma le part da nen anclude',
	'proofreadpage_quality0_category' => 'Sensa test',
	'proofreadpage_quality1_category' => 'Pa passà an verìfica',
	'proofreadpage_quality2_category' => 'Problemàtich',
	'proofreadpage_quality3_category' => 'Verìfica',
	'proofreadpage_quality4_category' => 'Validà',
	'proofreadpage_quality0_message' => "Sta pàgina-sì a l'ha pa dabzògn ëd la revision",
	'proofreadpage_quality1_message' => "Sta pàgina-sì a l'é pa stàita revisionà",
	'proofreadpage_quality2_message' => 'A-i é stàje un problema an revisionand sta pàgina-sì',
	'proofreadpage_quality3_message' => "Sta pàgina-sì a l'é stàita revisionà",
	'proofreadpage_quality4_message' => "Sta pàgina-sì a l'é stàita validà",
	'proofreadpage_index_status' => "Stat ëd l'ìndes",
	'proofreadpage_index_size' => 'Nùmer ëd pàgine',
	'proofreadpage_specialpage_label_orderby' => 'Ordiné për:',
	'proofreadpage_specialpage_label_key' => 'Serca:',
	'proofreadpage_specialpage_label_sortascending' => 'Órdina an chërsend',
	'proofreadpage_alphabeticalorder' => 'Órdin alfabétich',
	'proofreadpage_index_listofpages' => 'Lista ëd le pàgine',
	'proofreadpage_image_message' => 'Colegament a la pàgina ëd tàula',
	'proofreadpage_page_status' => 'Stat ëd la pàgina',
	'proofreadpage_js_attributes' => 'Autor Tìtol Ann Editor',
	'proofreadpage_index_attributes' => 'Autor
Tìtol
Ann|Ann ëd publicassion
Editor
Sorgiss
Figura|Figura ëd coertin-a
Pàgine||20
Nòte||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pàgina|pàgine}}',
	'proofreadpage_specialpage_legend' => 'Sërca ant le pàgine ëd tàula',
	'proofreadpage_specialpage_searcherror' => "Eror ant ël motor d'arserca",
	'proofreadpage_specialpage_searcherrortext' => "Ël motor d'arserca a marcia nen. An dëspias për l'inconvenient.",
	'proofreadpage_source' => 'Sorgiss',
	'proofreadpage_source_message' => 'Edission digitalisà dovrà për stabilì sto test-sì',
	'right-pagequality' => 'Modifiché ël drapò ëd qualità dla pàgina',
	'proofreadpage-section-tools' => "Utiss d'agiut për riletura",
	'proofreadpage-group-zoom' => 'Angrandiment',
	'proofreadpage-group-other' => 'Àutr',
	'proofreadpage-button-toggle-visibility-label' => "Smon-e/stërmé l'antestassion e ël pé 'd pàgina ëd costa pàgina",
	'proofreadpage-button-zoom-out-label' => 'Diminuì',
	'proofreadpage-button-reset-zoom-label' => "Amposté torna l'angrandiment",
	'proofreadpage-button-zoom-in-label' => 'Angrandì',
	'proofreadpage-button-toggle-layout-label' => 'Disposission vertical/orisontal',
	'proofreadpage-preferences-showheaders-label' => "Smon-e dij camp d'antestassion e ëd pé ëd pàgina quand as modìfica ant lë spassi nominal dla {{ns:page}}",
	'proofreadpage-preferences-horizontal-layout-label' => 'Dovré na disposission orisontal quand as modìfica ant lë spassi nominal {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Metadat dij lìber ëd {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadat dij lìber gestì da ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Schema nen trovà',
	'proofreadpage-indexoai-error-schemanotfound-text' => "Lë schema $1 a l'é pa stàit trovà.",
);

/** Pashto (پښتو)
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 */
$messages['ps'] = array(
	'indexpages' => 'د ليکلړ مخونو لړليک',
	'proofreadpage_image' => 'انځور',
	'proofreadpage_index' => 'ليکلړ',
	'proofreadpage_badpage' => 'ناسمه بڼه',
	'proofreadpage_notallowed' => 'د بدلون پرېښه نشته',
	'proofreadpage_nextpage' => 'بل مخ',
	'proofreadpage_prevpage' => 'تېر مخ',
	'proofreadpage_quality0_category' => 'بې متنه',
	'proofreadpage_specialpage_label_key' => 'پلټل:',
	'proofreadpage_index_listofpages' => 'د مخونو لړليک',
	'proofreadpage_image_message' => 'د ليکلړ مخ ته تړنه',
	'proofreadpage_page_status' => 'د مخ دريځ',
	'proofreadpage_js_attributes' => 'ليکوال سرليک کال خپرونکی',
	'proofreadpage_index_attributes' => 'ليکوال
سرليک
کال|د خپرېدو کال
خپرونکی
سرچينه
انځور|د پښتۍ انځور
مخونه||20
تبصرې||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|مخ|مخونه}}',
	'proofreadpage_specialpage_legend' => 'ليکلړ مخونه پلټل',
	'proofreadpage_source' => 'سرچينه',
	'proofreadpage-group-other' => 'بل',
	'proofreadpage-button-reset-zoom-label' => 'آر کچه',
);

/** Portuguese (português)
 * @author Giro720
 * @author Hamilton Abreu
 * @author Lijealso
 * @author Luckas
 * @author Malafaya
 * @author Waldir
 * @author 555
 */
$messages['pt'] = array(
	'indexpages' => 'Lista de páginas de índice',
	'pageswithoutscans' => 'Páginas não transcluídas',
	'proofreadpage_desc' => 'Permite a comparação fácil de um texto com a sua digitalização original',
	'proofreadpage_image' => 'Imagem',
	'proofreadpage_index' => 'Índice',
	'proofreadpage_index_expected' => 'Erro: índice esperado',
	'proofreadpage_nosuch_index' => 'Erro: índice não existe',
	'proofreadpage_nosuch_file' => 'Erro: ficheiro não existe',
	'proofreadpage_badpage' => 'Formato Errado',
	'proofreadpage_badpagetext' => 'O formato da página que tentou gravar é incorreto.',
	'proofreadpage_indexdupe' => 'Link duplicado',
	'proofreadpage_indexdupetext' => 'As páginas não podem ser listadas mais do que uma vez numa página de índice.',
	'proofreadpage_nologin' => 'Não está autenticado',
	'proofreadpage_nologintext' => 'Precisa de estar [[Special:UserLogin|autenticado]] para alterar o estado de revisão das páginas.',
	'proofreadpage_notallowed' => 'Mudança não permitida',
	'proofreadpage_notallowedtext' => 'Não lhe é permitido alterar o estado de revisão desta página.',
	'proofreadpage_dataconfig_badformatted' => 'Erro nos dados de configuração',
	'proofreadpage_dataconfig_badformattedtext' => 'A página [[Mediawiki:Proofreadpage index data config]] não se encontra corretamente formatada como JSON',
	'proofreadpage_number_expected' => 'Erro: valor numérico esperado',
	'proofreadpage_interval_too_large' => 'Erro: intervalo demasiado grande',
	'proofreadpage_invalid_interval' => 'Erro: intervalo inválido',
	'proofreadpage_nextpage' => 'Página seguinte',
	'proofreadpage_prevpage' => 'Página anterior',
	'proofreadpage_header' => "Cabeçalho (em modo ''noinclude''):",
	'proofreadpage_body' => 'Corpo de página (em modo de transclusão):',
	'proofreadpage_footer' => "Rodapé (em modo ''noinclude''):",
	'proofreadpage_toggleheaders' => "inverter a visibilidade das seções ''noinclude''",
	'proofreadpage_quality0_category' => 'Sem texto',
	'proofreadpage_quality1_category' => 'Não revistas',
	'proofreadpage_quality2_category' => 'Problemáticas',
	'proofreadpage_quality3_category' => 'Revistas e corrigidas',
	'proofreadpage_quality4_category' => 'Validadas',
	'proofreadpage_quality0_message' => 'Esta página não necessita de ser revista',
	'proofreadpage_quality1_message' => 'Esta página não foi ainda revista',
	'proofreadpage_quality2_message' => 'Ocorreu um problema ao fazer a revisão desta página',
	'proofreadpage_quality3_message' => 'Esta página foi revista',
	'proofreadpage_quality4_message' => 'Esta página foi validada',
	'proofreadpage_index_status' => 'Status do índice',
	'proofreadpage_index_size' => 'Número de páginas',
	'proofreadpage_specialpage_label_orderby' => 'Ordenar por:',
	'proofreadpage_specialpage_label_key' => 'Pesquisar:',
	'proofreadpage_specialpage_label_sortascending' => 'Classificação crescente',
	'proofreadpage_alphabeticalorder' => 'Ordem alfabética',
	'proofreadpage_index_listofpages' => 'Lista de páginas',
	'proofreadpage_image_message' => 'Link para a página de índice',
	'proofreadpage_page_status' => 'Estado da página',
	'proofreadpage_js_attributes' => 'Autor Título Ano Editora',
	'proofreadpage_index_attributes' => 'Autor
Título
Ano|Ano de publicação
Editora
Fonte
Imagem|Imagem de capa
Páginas||20
Notas||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|página|páginas}}',
	'proofreadpage_specialpage_legend' => 'Pesquisar nas páginas de índice',
	'proofreadpage_specialpage_searcherror' => 'Erro no motor de busca',
	'proofreadpage_specialpage_searcherrortext' => 'O motor de busca não funciona. Lamentamos o inconveniente.',
	'proofreadpage_source' => 'Fonte',
	'proofreadpage_source_message' => 'Edição digitalizada usada para criar este texto',
	'right-pagequality' => 'Modificar o indicador da qualidade da página',
	'proofreadpage-section-tools' => 'Instrumentos de revisão',
	'proofreadpage-group-zoom' => 'Ampliar',
	'proofreadpage-group-other' => 'Outros',
	'proofreadpage-button-toggle-visibility-label' => 'Mostrar ou ocultar o cabeçalho e o rodapé desta página',
	'proofreadpage-button-zoom-out-label' => 'Reduzir ampliação',
	'proofreadpage-button-reset-zoom-label' => 'Reiniciar ampliação',
	'proofreadpage-button-zoom-in-label' => 'Aumentar ampliação',
	'proofreadpage-button-toggle-layout-label' => 'Orientação vertical ou horizontal',
	'proofreadpage-preferences-showheaders-label' => 'Mostrar campos de cabeçalho e rodapé ao editar o espaço nominal {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Usar "layout" horizontal ao editar o espaço nominal {{ns:page}}',
);

/** Brazilian Portuguese (português do Brasil)
 * @author Eduardo.mps
 * @author Giro720
 * @author Luckas
 * @author Luckas Blade
 * @author MetalBrasil
 * @author 555
 */
$messages['pt-br'] = array(
	'indexpages' => 'Lista de páginas de índice',
	'pageswithoutscans' => 'Páginas sem imagens',
	'proofreadpage_desc' => 'Permite uma fácil comparação de textos e suas digitalizações originais',
	'proofreadpage_image' => 'Imagem',
	'proofreadpage_index' => 'Índice',
	'proofreadpage_index_expected' => 'Erro: era esperado um índice',
	'proofreadpage_nosuch_index' => 'Erro: índice inexistente',
	'proofreadpage_nosuch_file' => 'Erro: arquivo inexistente',
	'proofreadpage_badpage' => 'Formato errôneo',
	'proofreadpage_badpagetext' => 'Você tentou salvar em um formato incorreto.',
	'proofreadpage_indexdupe' => 'Link duplicado',
	'proofreadpage_indexdupetext' => 'As páginas não podem ser listadas mais de uma vez em uma página de índice.',
	'proofreadpage_nologin' => 'Você não está autenticado',
	'proofreadpage_nologintext' => 'É necessário estar [[Special:UserLogin|autenticado]] para poder alterar o status de revisão das páginas.',
	'proofreadpage_notallowed' => 'Alteração não permitida',
	'proofreadpage_notallowedtext' => 'Você não está autorizado a alterar o status de revisão desta página.',
	'proofreadpage_dataconfig_badformatted' => 'Erro nos dados de configuração',
	'proofreadpage_dataconfig_badformattedtext' => 'A página [[Mediawiki:Proofreadpage index data config]] não se encontra corretamente formatada como JSON',
	'proofreadpage_number_expected' => 'Erro: era esperado um valor numérico',
	'proofreadpage_interval_too_large' => 'Erro: intervalo muito longo',
	'proofreadpage_invalid_interval' => 'Erro: intervalo inválido',
	'proofreadpage_nextpage' => 'Próxima página',
	'proofreadpage_prevpage' => 'Página anterior',
	'proofreadpage_header' => 'Cabeçalho (em modo noinclude):',
	'proofreadpage_body' => 'Corpo de página (em modo de transclusão):',
	'proofreadpage_footer' => 'Rodapé (em modo noinclude):',
	'proofreadpage_toggleheaders' => 'tornar as seções noinclude visíveis',
	'proofreadpage_quality0_category' => 'Sem texto',
	'proofreadpage_quality1_category' => 'Não revisadas',
	'proofreadpage_quality2_category' => 'Problemáticas',
	'proofreadpage_quality3_category' => 'Revisadas e corrigidas',
	'proofreadpage_quality4_category' => 'Validadas',
	'proofreadpage_quality0_message' => 'Esta página não precisa ser revisada',
	'proofreadpage_quality1_message' => 'Esta página ainda não foi revisada',
	'proofreadpage_quality2_message' => 'Ocorreu um erro ao revisar esta página',
	'proofreadpage_quality3_message' => 'Esta página foi revisada',
	'proofreadpage_quality4_message' => 'Esta página foi validada',
	'proofreadpage_index_status' => 'Status do índice',
	'proofreadpage_index_size' => 'Número de páginas',
	'proofreadpage_specialpage_label_orderby' => 'Ordenar por:',
	'proofreadpage_specialpage_label_key' => 'Pesquisar:',
	'proofreadpage_specialpage_label_sortascending' => 'Classificação crescente',
	'proofreadpage_alphabeticalorder' => 'Ordem alfabética',
	'proofreadpage_index_listofpages' => 'Lista de páginas',
	'proofreadpage_image_message' => 'Link para a página de índice',
	'proofreadpage_page_status' => 'Estado da página',
	'proofreadpage_js_attributes' => 'Autor Título Ano Editora',
	'proofreadpage_index_attributes' => 'Autor
Título
Ano|Ano de publicação
Editora
Fonte
Imagem|Imagem de capa
Páginas||20
Notas||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|página|páginas}}',
	'proofreadpage_specialpage_legend' => 'Pesquisar nas páginas de índice',
	'proofreadpage_specialpage_searcherror' => 'Erro na ferramenta de busca',
	'proofreadpage_specialpage_searcherrortext' => 'A ferramenta de busca não está funcionando. Sentimos muito.',
	'proofreadpage_source' => 'Fonte',
	'proofreadpage_source_message' => 'Edição digitalizada utilizada para estabelecer este texto',
	'right-pagequality' => 'Modificar o indicador da qualidade da página',
	'proofreadpage-section-tools' => 'Ferramentas de revisão',
	'proofreadpage-group-zoom' => 'Ampliar',
	'proofreadpage-group-other' => 'Outro',
	'proofreadpage-button-toggle-visibility-label' => 'Mostrar/ocultar o topo e o rodapé desta página',
	'proofreadpage-button-zoom-out-label' => 'Afastar',
	'proofreadpage-button-reset-zoom-label' => 'Redefinir ampliação',
	'proofreadpage-button-zoom-in-label' => 'Aumentar ampliação',
	'proofreadpage-button-toggle-layout-label' => 'Disposição vertical ou horizontal',
	'proofreadpage-preferences-showheaders-label' => 'Mostrar campos de cabeçalho e rodapé ao editar o espaço nominal {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Usar layout horizontal ao editar o espaço nominal {{ns:page}}',
);

/** Quechua (Runa Simi)
 * @author AlimanRuna
 */
$messages['qu'] = array(
	'proofreadpage_image' => 'Rikcha',
	'proofreadpage_index' => 'Yuyarina',
	'proofreadpage_nextpage' => "Qatiq p'anqa",
	'proofreadpage_prevpage' => "Ñawpaq p'anqa",
	'proofreadpage_header' => "Uma siq'i (mana ch'aqtana):",
	'proofreadpage_body' => "P'anqa kurku (ch'aqtanapaq):",
	'proofreadpage_footer' => "Chaki siq'i (mana ch'aqtana):",
	'proofreadpage_index_attributes' => "Qillqaq
Qillqa suti
Wata|Liwruchasqap watan
Liwruchaq
Pukyu
Rikcha|Qata rikcha
P'anqakuna||20
Willapusqakuna||10",
);

/** Romanian (română)
 * @author AdiJapan
 * @author Cin
 * @author Firilacroco
 * @author KlaudiuMihaila
 * @author Mihai
 * @author Minisarm
 * @author Stelistcristi
 */
$messages['ro'] = array(
	'indexpages' => 'Lista paginilor de index',
	'pageswithoutscans' => 'Pagini fără scanări',
	'proofreadpage_desc' => 'Permite compararea facilă a textului față de scanarea originală',
	'proofreadpage_image' => 'Imagine',
	'proofreadpage_index' => 'Index',
	'proofreadpage_index_expected' => 'Eroare: index așteptat',
	'proofreadpage_nosuch_index' => 'Eroare: index inexistent',
	'proofreadpage_nosuch_file' => 'Eroare: fișier inexistent',
	'proofreadpage_badpage' => 'Format greșit',
	'proofreadpage_badpagetext' => 'Formatul paginii în care se dorește salvarea este incorect.',
	'proofreadpage_indexdupe' => 'Legătură duplicat',
	'proofreadpage_indexdupetext' => 'Paginile nu pot fi afișate de mai multe ori într-o pagină index.',
	'proofreadpage_nologin' => 'Nu sunteți autentificat',
	'proofreadpage_nologintext' => 'Trebuie să fiți [[Special:UserLogin|autentificat]] pentru a modifica statutul de verificare a paginilor.',
	'proofreadpage_notallowed' => 'Schimbare nepermisă',
	'proofreadpage_notallowedtext' => 'Nu vi se permite să schimbați statutul de verificare al acestei pagini.',
	'proofreadpage_number_expected' => 'Eroare: valoare numerică așteptată',
	'proofreadpage_interval_too_large' => 'Eroare: interval prea mare',
	'proofreadpage_invalid_interval' => 'Eroare: interval incorect',
	'proofreadpage_nextpage' => 'Pagina următoare',
	'proofreadpage_prevpage' => 'Pagina anterioară',
	'proofreadpage_header' => 'Antet (nu include):',
	'proofreadpage_body' => 'Corp-mesaj (pentru a fi introdus):',
	'proofreadpage_footer' => "Notă de subsol (''noinclude''):",
	'proofreadpage_toggleheaders' => "arată/ascunde secțiunile ''noinclude''",
	'proofreadpage_quality0_category' => 'Fără text',
	'proofreadpage_quality1_category' => 'Neverificat',
	'proofreadpage_quality2_category' => 'Problematic',
	'proofreadpage_quality3_category' => 'Verificat',
	'proofreadpage_quality4_category' => 'Validat',
	'proofreadpage_quality0_message' => 'Această pagină nu necesită să fie verificată',
	'proofreadpage_quality1_message' => 'Această pagină n-a fost verificată',
	'proofreadpage_quality2_message' => 'Am întâmpinat o problemă la verificarea acestei pagini',
	'proofreadpage_quality3_message' => 'Această pagină a fost verificată',
	'proofreadpage_quality4_message' => 'Această pagină a fost validată',
	'proofreadpage_index_status' => 'Statutul indexului',
	'proofreadpage_index_size' => 'Număr de pagini',
	'proofreadpage_specialpage_label_orderby' => 'Ordonare după:',
	'proofreadpage_specialpage_label_key' => 'Căutare:',
	'proofreadpage_specialpage_label_sortascending' => 'Sotare ascendentă',
	'proofreadpage_alphabeticalorder' => 'Ordine alfabetică',
	'proofreadpage_index_listofpages' => 'Lista paginilor',
	'proofreadpage_image_message' => 'Legătură către pagina index',
	'proofreadpage_page_status' => 'Starea paginii',
	'proofreadpage_js_attributes' => 'Autor Titlu An Editor',
	'proofreadpage_index_attributes' => 'Autor
Titlu
An|Anul publicării
Editură
Sursă
Imagine|Imagine copertă
Pagini||20
Comentarii||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pagină|pagini|de pagini}}',
	'proofreadpage_specialpage_legend' => 'Caută paginile de index',
	'proofreadpage_specialpage_searcherror' => 'Eroare în motorul de căutare.',
	'proofreadpage_specialpage_searcherrortext' => 'Motorul de căutare nu funcționează. Ne pare rău pentru inconveniență.',
	'proofreadpage_source' => 'Sursă',
	'proofreadpage_source_message' => 'Pentru a confirma acest text s-au utilizat ediția scanată',
	'right-pagequality' => 'Modifică indicatorul de calitate a paginii',
	'proofreadpage-section-tools' => 'Instrumente pentru revizuire',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Altele',
	'proofreadpage-button-toggle-visibility-label' => 'Arată/ascunde antetul și subsolul acestei pagini',
	'proofreadpage-button-zoom-out-label' => 'Depărtare',
	'proofreadpage-button-reset-zoom-label' => 'Reinițializare zoom',
	'proofreadpage-button-zoom-in-label' => 'Apropiere',
	'proofreadpage-button-toggle-layout-label' => 'Aspect vertical/orizontal',
	'proofreadpage-preferences-showheaders-label' => 'Arată câmpurile de antet și subsol când se modifică în spațiul de nume {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Utilizează aspect orizontal când se modifică în spațiul de nume {{ns:page}}',
);

/** tarandíne (tarandíne)
 * @author Joetaras
 * @author Reder
 */
$messages['roa-tara'] = array(
	'indexpages' => 'Elenghe de le pàggene de indice',
	'pageswithoutscans' => 'Pàggene senze scansiune',
	'proofreadpage_desc' => "Permette combronde facele de teste cu 'a scanzione origgenale",
	'proofreadpage_image' => 'Immaggine',
	'proofreadpage_index' => 'Indice',
	'proofreadpage_index_expected' => 'Errore: previste indice',
	'proofreadpage_nosuch_index' => 'Errore: nisciune indice',
	'proofreadpage_nosuch_file' => 'Errore: nisciune file',
	'proofreadpage_badpage' => 'Formate sbagliate',
	'proofreadpage_badpagetext' => "'U formate d'a pàgene ca tu ste pruève a reggistrà non g'è corrette.",
	'proofreadpage_indexdupe' => 'Collegamende duplicate',
	'proofreadpage_indexdupetext' => "Le pàggene non ge ponne essere elengate cchiù de 'na vote jndr'à 'na pàgene de indice.",
	'proofreadpage_nologin' => 'Non ge sì collegate',
	'proofreadpage_nologintext' => "Tu a essere [[Special:UserLogin|collegate]] pe cangià 'u state de verifiche de le pàggene.",
	'proofreadpage_notallowed' => 'Cangiamende none consendite',
	'proofreadpage_notallowedtext' => "Non ge t'è permesse cangià 'u state de verifiche de sta pàgene.",
	'proofreadpage_dataconfig_badformatted' => "Errore jndr'à configurazione de le date",
	'proofreadpage_dataconfig_badformattedtext' => "'A pàgene [[Mediawiki:Proofreadpage index data config]] non g'è ben formate pe JSON.",
	'proofreadpage_number_expected' => "Errore: aspettamme 'nu valore numereche",
	'proofreadpage_interval_too_large' => 'Errore: indervalle troppe larije',
	'proofreadpage_invalid_interval' => 'Errore: indervalle invalide',
	'proofreadpage_nextpage' => 'Pàgena successive',
	'proofreadpage_prevpage' => 'Pàgena precedende',
	'proofreadpage_header' => 'Testate (none ingluse):',
	'proofreadpage_body' => "Cuerpe d'a pàgene (da ingludere):",
	'proofreadpage_footer' => "Fine d'a pàgene (none ingluse):",
	'proofreadpage_toggleheaders' => "abbilite/disabbilite 'a visibbeletà de le seziune none ingluse",
	'proofreadpage_quality0_category' => 'Senza teste',
	'proofreadpage_quality1_category' => 'Da correggere',
	'proofreadpage_quality2_category' => 'Probblemateche',
	'proofreadpage_quality3_category' => 'Corrette',
	'proofreadpage_quality4_category' => 'Validate',
	'proofreadpage_quality0_message' => "Sta pàgene none g'abbesogne de essere corrette",
	'proofreadpage_quality1_message' => "Sta pàgene none g'à state corrette",
	'proofreadpage_quality2_message' => 'Ha state quacche probbleme quanne è corrette sta pàgene',
	'proofreadpage_quality3_message' => 'Sta pàgene ha state corrette',
	'proofreadpage_quality4_message' => 'Sta pàgene ha state validate',
	'proofreadpage_index_status' => "State de l'indice",
	'proofreadpage_index_size' => 'Numere pe pàggene',
	'proofreadpage_specialpage_label_orderby' => 'Ordine pe:',
	'proofreadpage_specialpage_label_key' => 'Cirche:',
	'proofreadpage_specialpage_label_sortascending' => "Ordinamente ca 'nghiane",
	'proofreadpage_alphabeticalorder' => 'Ordene alfabbetiche',
	'proofreadpage_index_listofpages' => 'Elenghe de le pàggene',
	'proofreadpage_image_message' => "Colleghe a 'a pàgene de indice",
	'proofreadpage_page_status' => "State d'a pàgene",
	'proofreadpage_js_attributes' => 'Autore Titele Anne Pubblicatore',
	'proofreadpage_index_attributes' => "Autore
Titele
Anne|Anne de pubblicazione
Pubblicatore
Sorgende
Immaggine|Immaggine d'a coprtine
Paggène||20
Note||10",
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pàgene|pàggene}}',
	'proofreadpage_specialpage_legend' => 'Cirche le pàggene de indice',
	'proofreadpage_specialpage_searcherror' => "Errore jndr'à 'u motore de ricerche",
	'proofreadpage_specialpage_searcherrortext' => "'U motore de ricerche non ge fatìe. Ne dispiace pe l'ingonveniende.",
	'proofreadpage_source' => 'Sorgende',
	'proofreadpage_source_message' => 'Edizione scanzionate ausate pe definì stu teste',
	'right-pagequality' => "Cange 'a bandiere d'a qualità d'a pàgene",
	'proofreadpage-section-tools' => 'Struminde de revisione',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Otre',
	'proofreadpage-button-toggle-visibility-label' => "Fà vedè/scunne 'a testate e 'u piè de pàgene de sta pàgene",
	'proofreadpage-button-zoom-out-label' => 'Cchiù peccinne',
	'proofreadpage-button-reset-zoom-label' => 'Dimenzione origgenale',
	'proofreadpage-button-zoom-in-label' => 'Cchiù granne',
	'proofreadpage-button-toggle-layout-label' => 'Disposizione verticale/orizzondale',
	'proofreadpage-preferences-showheaders-label' => "Fà vedè testate e piè pàgene quanne cange jndr'à 'u namespace {{ns:page}}",
	'proofreadpage-preferences-horizontal-layout-label' => "Ause 'a disposizione orizzondale quanne cange jndr'à 'u namespace {{ns:page}}",
	'proofreadpage-indexoai-repositoryName' => 'Metadate de libbre da {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadate de libbre gestite da ProofredPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Scheme non acchiate',
	'proofreadpage-indexoai-error-schemanotfound-text' => "'U scheme $1 non g'ha state acchiate.",
	'proofreadpage-disambiguationspage' => 'Template:disambig',
);

/** Russian (русский)
 * @author Ferrer
 * @author Innv
 * @author Lockal
 * @author Sergey kudryavtsev
 * @author Александр Сигачёв
 */
$messages['ru'] = array(
	'indexpages' => 'Список индексных страниц',
	'pageswithoutscans' => 'Страницы без сканов',
	'proofreadpage_desc' => 'Позволяет в удобном виде сравнивать текст и отсканированное изображение оригинала',
	'proofreadpage_image' => 'изображение',
	'proofreadpage_index' => 'индекс',
	'proofreadpage_index_expected' => 'Ошибка. Индекс не обнаружен.',
	'proofreadpage_nosuch_index' => 'Ошибка. Нет такого индекса.',
	'proofreadpage_nosuch_file' => 'Ошибка: нет такого файла',
	'proofreadpage_badpage' => 'Неправильный формат',
	'proofreadpage_badpagetext' => 'Ошибочный формат записываемой страницы.',
	'proofreadpage_indexdupe' => 'Ссылка-дубликат',
	'proofreadpage_indexdupetext' => 'Страницы не могут быть перечислены на индексной странице более одного раза.',
	'proofreadpage_nologin' => 'Не выполнен вход',
	'proofreadpage_nologintext' => 'Вы должны [[Special:UserLogin|представиться системе]] для изменения статуса вычитки страниц.',
	'proofreadpage_notallowed' => 'Изменение не допускается',
	'proofreadpage_notallowedtext' => 'Вы не можете изменить статус вычитки этой страницы.',
	'proofreadpage_number_expected' => 'Ошибка. Ожидается числовое значение.',
	'proofreadpage_interval_too_large' => 'Ошибка. Слишком большой промежуток.',
	'proofreadpage_invalid_interval' => 'Ошибка: неправильный интервал',
	'proofreadpage_nextpage' => 'следующая страница',
	'proofreadpage_prevpage' => 'предыдущая страница',
	'proofreadpage_header' => 'Заголовок (не включается):',
	'proofreadpage_body' => 'Тело страницы (будет включаться):',
	'proofreadpage_footer' => 'Нижний колонтитул (не включается):',
	'proofreadpage_toggleheaders' => 'показывать невключаемые разделы',
	'proofreadpage_quality0_category' => 'Без текста',
	'proofreadpage_quality1_category' => 'Не вычитана',
	'proofreadpage_quality2_category' => 'Проблемная',
	'proofreadpage_quality3_category' => 'Вычитана',
	'proofreadpage_quality4_category' => 'Проверена',
	'proofreadpage_quality0_message' => 'Эта страница не требует вычитки',
	'proofreadpage_quality1_message' => 'Эта страница не была вычитана',
	'proofreadpage_quality2_message' => 'Есть проблемы при вычитке этой страницы',
	'proofreadpage_quality3_message' => 'Эта страница была вычитана',
	'proofreadpage_quality4_message' => 'Эта страница выверена',
	'proofreadpage_index_listofpages' => 'Список страниц',
	'proofreadpage_image_message' => 'Ссылка на страницу индекса',
	'proofreadpage_page_status' => 'Статус страницы',
	'proofreadpage_js_attributes' => 'Автор Название Год Издательство',
	'proofreadpage_index_attributes' => 'Автор
Заголовок
Год|Год публикации
Издатель
Источник
Изображение|Изображение обложки
Страниц||20
Примечания||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|страница|страницы|страниц}}',
	'proofreadpage_specialpage_legend' => 'Поиск индексных страниц',
	'proofreadpage_specialpage_searcherror' => 'Ошибка в поисковой системе',
	'proofreadpage_source' => 'Источник',
	'proofreadpage_source_message' => 'Для создания электронной версии текста использовались отсканированные материалы',
	'right-pagequality' => 'изменять флаг качества страницы',
	'proofreadpage-section-tools' => 'Инструменты корректора',
	'proofreadpage-group-zoom' => 'Увеличение',
	'proofreadpage-group-other' => 'Иное',
	'proofreadpage-button-toggle-visibility-label' => 'Показать/скрыть верхнюю и нижнюю часть этой страницы',
	'proofreadpage-button-zoom-out-label' => 'Отдалить',
	'proofreadpage-button-reset-zoom-label' => 'Сбросить увеличение',
	'proofreadpage-button-zoom-in-label' => 'Приблизить',
	'proofreadpage-button-toggle-layout-label' => 'Вертикальная/горизонтальная разметка',
	'proofreadpage-preferences-showheaders-label' => 'Показывать поля верхнего и нижнего колонтитулов при редактировании в пространстве имен {{ns:page}}.',
	'proofreadpage-preferences-horizontal-layout-label' => 'Использовать горизонтальную раскладку при редактировании в пространстве имен {{ns:page}}.',
);

/** Rusyn (русиньскый)
 * @author Gazeb
 */
$messages['rue'] = array(
	'indexpages' => 'Список індексовых сторінок',
	'pageswithoutscans' => 'Сторінкы без скеновань',
	'proofreadpage_desc' => 'Доволює просте порівнаня тексту з оріґіналом',
	'proofreadpage_image' => 'Образок',
	'proofreadpage_index' => 'Індекс',
	'proofreadpage_index_expected' => 'Хыба: очекаваный індекс',
	'proofreadpage_nosuch_index' => 'Хыба: такый індекс не єствує',
	'proofreadpage_nosuch_file' => 'Хыба: такый файл не єствує',
	'proofreadpage_badpage' => 'Неправилный формат',
	'proofreadpage_badpagetext' => 'Формат сторінкы, котру сьте пробовали уложыти, неправилный.',
	'proofreadpage_indexdupe' => 'Дупліцітный одказ',
	'proofreadpage_indexdupetext' => 'Сторінкы можуть быти в індексї уведены максімално раз.',
	'proofreadpage_nologin' => 'Не сьте приголошеный(а)',
	'proofreadpage_nologintext' => 'Кідь хочете мінити статус контролёваня сторінкы, мусите ся [[Special:UserLogin|приголосити]].',
	'proofreadpage_notallowed' => 'Зміна не є доволена',
	'proofreadpage_notallowedtext' => 'Не мате права мінити статус сконтролёваня той сторінкы.',
	'proofreadpage_number_expected' => 'Хыба: очекаване чіслове значіня',
	'proofreadpage_interval_too_large' => 'Хыба: дуже великый інтервал',
	'proofreadpage_invalid_interval' => 'Хыба: неправилны інтервал',
	'proofreadpage_nextpage' => 'Далша сторінка',
	'proofreadpage_prevpage' => 'Попередня сторінка',
	'proofreadpage_header' => 'Головка (noinclude):',
	'proofreadpage_body' => 'Тїло сторінкы (буде ся включати):',
	'proofreadpage_footer' => 'Пятка (noinclude):',
	'proofreadpage_toggleheaders' => 'перепнути видиность секції noinclude',
	'proofreadpage_quality0_category' => 'Без тексту',
	'proofreadpage_quality1_category' => 'Не было сконтролёване',
	'proofreadpage_quality2_category' => 'Проблематічна',
	'proofreadpage_quality3_category' => 'Сконтролёване',
	'proofreadpage_quality4_category' => 'Перевірена',
	'proofreadpage_quality0_message' => 'Тота сторінка не потребує коректуры',
	'proofreadpage_quality1_message' => 'Тота сторінка не была сконтролёвана',
	'proofreadpage_quality2_message' => 'Почас контролї той сторінкы ся обявив проблем',
	'proofreadpage_quality3_message' => 'Тота сторінка была сконтролёвана',
	'proofreadpage_quality4_message' => 'Тота сторінка была овірена',
	'proofreadpage_index_listofpages' => 'Список сторінок',
	'proofreadpage_image_message' => 'Одказ на сторінку індексу',
	'proofreadpage_page_status' => 'Статус сторінкы',
	'proofreadpage_js_attributes' => 'Автор Назва Рік Выдавательство',
	'proofreadpage_index_attributes' => 'Автор
Назва
Рік|Рік выданя
Выдавательство
Жрідло
Образок|Обалка
Сторінок||20
Позначок||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|сторінка|сторінкы|сторінок}}',
	'proofreadpage_specialpage_legend' => 'Глядати на індексовых сторінках',
	'proofreadpage_source' => 'Жрідло',
	'proofreadpage_source_message' => 'Наскенована верзія хоснована про выпрацованя того тексту',
	'right-pagequality' => 'Позмінёвати флаґ кваліты сторінкы',
	'proofreadpage-section-tools' => 'Інштрументы коректуры',
	'proofreadpage-group-zoom' => 'Зоом',
	'proofreadpage-group-other' => 'Інше',
	'proofreadpage-button-toggle-visibility-label' => 'Указати/скрыти верьхнї і нижнї тітулы той сторінкы',
	'proofreadpage-button-zoom-out-label' => 'Зменшыти',
	'proofreadpage-button-reset-zoom-label' => 'Ресет звекшыня',
	'proofreadpage-button-zoom-in-label' => 'Звекшыти',
	'proofreadpage-button-toggle-layout-label' => 'Вертікалне / горізонтално розложіня',
);

/** Sanskrit (संस्कृतम्)
 * @author Abhirama
 * @author Ansumang
 * @author Shreekant Hegde
 * @author Shubha
 */
$messages['sa'] = array(
	'indexpages' => 'अनुक्रमणिका पुटावली',
	'pageswithoutscans' => 'अगतिकपुटानि',
	'proofreadpage_desc' => 'मूलगतिकलेखानां सरलतुलनावकाशः',
	'proofreadpage_image' => 'चित्रम्',
	'proofreadpage_index' => 'अनुक्रमणिका',
	'proofreadpage_index_expected' => 'दोषः :  निरीक्षितानुक्रमणिका',
	'proofreadpage_nosuch_index' => 'दोषः : तदृशी अनुक्रमणिका नास्ति',
	'proofreadpage_nosuch_file' => 'दोषः : तादृशी सञ्चिका नास्ति',
	'proofreadpage_badpage' => 'असमीचीनप्रारूपम्',
	'proofreadpage_badpagetext' => ' संरक्षितुं यतमानस्य पुटस्य प्रारूपम् असमीचीनम्',
	'proofreadpage_indexdupe' => 'द्वितकानुबन्धः',
	'proofreadpage_indexdupetext' => 'अनुक्रमणिकायाम् अनेकवारं पुटानाम् आवलीकरणं न शक्यते ।',
	'proofreadpage_nologin' => 'न प्रविष्टम्',
	'proofreadpage_nologintext' => 'पुटपरिशीलनस्थितेः परिवर्तनाय भवता प्रवेष्टव्यम् [[Special:UserLogin|logged in]]',
	'proofreadpage_notallowed' => 'परिवर्तनम् अननुमतम्',
	'proofreadpage_notallowedtext' => 'पुटपरिशीलनस्थितिं परिवर्तयितुम् अनुमतिः नास्ति ।',
	'proofreadpage_number_expected' => 'दोशः : सङ्ख्यामौल्यं निरीक्षितम्',
	'proofreadpage_interval_too_large' => 'दोषः : मध्यावकाशः सुदीर्घः',
	'proofreadpage_invalid_interval' => 'दोषः :  अपुष्टः मध्यावकाशः',
	'proofreadpage_nextpage' => 'अग्रिमं पृष्ठम्',
	'proofreadpage_prevpage' => 'पूर्वतनं पृष्ठम्',
	'proofreadpage_header' => 'पुटाग्रः(अव्यचितम्) :',
	'proofreadpage_body' => 'पुटाङ्गम् (उपयोगार्थम्) :',
	'proofreadpage_footer' => 'पुटतलम् (अव्यचितम्) :',
	'proofreadpage_toggleheaders' => 'अव्यचितविभागानां दृश्यस्तरं परिवर्तयतु',
	'proofreadpage_quality0_category' => 'लेखरहितम्',
	'proofreadpage_quality1_category' => 'अपरिष्कृतम्',
	'proofreadpage_quality2_category' => 'समस्यात्मकः',
	'proofreadpage_quality3_category' => 'परिष्कृतम्',
	'proofreadpage_quality4_category' => 'पुष्टितम्',
	'proofreadpage_quality0_message' => 'अस्य पुटस्य पुटपरिशीलनं न आवश्यकम् ।',
	'proofreadpage_quality1_message' => 'एतत् पृष्ठम् अपरिष्कृतम् अस्ति',
	'proofreadpage_quality2_message' => 'पुटपरिशीलयितुं काचित् समस्या अस्ति',
	'proofreadpage_quality3_message' => 'एतत् पृष्ठम् परिष्कृतम् अस्ति',
	'proofreadpage_quality4_message' => 'पुटमेतत् सुपुष्टितम्',
	'proofreadpage_index_listofpages' => 'पृष्ठानाम् आवली',
	'proofreadpage_image_message' => 'अनुक्रमणिकापुटस्य अनुबन्धः',
	'proofreadpage_page_status' => 'पुटस्थितिः',
	'proofreadpage_js_attributes' => 'ग्रन्थकर्ता शिर्षिका वर्षम् प्रकाशकः',
	'proofreadpage_index_attributes' => 'ग्रन्थकर्ता
शीर्षिका
वर्षम् | प्रकाशनवर्षम्
प्रकाशकः
स्रोतः
चित्रम् | रक्षापुटचित्रम्
पुटानि || २०
टीका || १०',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|पुटम्|पुटानि}}',
	'proofreadpage_specialpage_legend' => 'अनुक्रमणिकापुटशोधः',
	'proofreadpage_source' => 'स्रोतः',
	'proofreadpage_source_message' => 'लेखं प्रतिष्ठापयितुं प्रयुक्तं गतिकसम्पादनम्',
	'right-pagequality' => 'पुटप्रवेशावकाशस्य समुन्नतिः',
	'proofreadpage-section-tools' => 'पुटपरिशीलनस्य उपकरणानि',
	'proofreadpage-group-zoom' => 'वीक्षणम्',
	'proofreadpage-group-other' => 'अन्यत्',
	'proofreadpage-button-toggle-visibility-label' => 'पुटाग्रस्य पुटतलस्य वा दर्शनं/गोपनम्',
	'proofreadpage-button-zoom-out-label' => 'परिवीक्षणम्',
	'proofreadpage-button-reset-zoom-label' => 'मूलापरिमाणम्',
	'proofreadpage-button-zoom-in-label' => 'उपवीक्षणम्',
	'proofreadpage-button-toggle-layout-label' => 'लम्बः/तिर्यक् लुटविन्यासः',
	'proofreadpage-preferences-showheaders-label' => '{{ns:page}} नामावकाशे सम्पादनावसरे शीर्षिका अधोटिप्पणी च दर्श्यताम् ।',
	'proofreadpage-preferences-horizontal-layout-label' => '{{ns:page}} नामावकाशे सम्पादनावसरे समतलप्राकारः उपयुज्यताम् ।',
);

/** Sakha (саха тыла)
 * @author HalanTul
 */
$messages['sah'] = array(
	'indexpages' => 'Индекс сирэйдэрин тиһигэ',
	'pageswithoutscans' => 'Скаана суох сирэйдэр',
	'proofreadpage_desc' => 'Оригинаалы уонна скаанердаммыт ойууну тэҥнээн көрөр кыаҕы биэрэр',
	'proofreadpage_image' => 'ойуу',
	'proofreadpage_index' => 'индекс',
	'proofreadpage_index_expected' => 'Алҕас: Индекс көстүбэтэ',
	'proofreadpage_nosuch_index' => 'Алҕас: Маннык индекс суох',
	'proofreadpage_nosuch_file' => 'Алҕас: маннык билэ суох',
	'proofreadpage_badpage' => 'Сыыһа формаат',
	'proofreadpage_badpagetext' => 'Суруллар сирэй атын формааттаах.',
	'proofreadpage_indexdupe' => 'Хос сигэ',
	'proofreadpage_indexdupetext' => 'Сирэй индекс сирэйигэр хаста да суруллубат.',
	'proofreadpage_nologin' => 'Киирии сатаммата (сатамматах)',
	'proofreadpage_nologintext' => 'Сирэйи бэрэбиэркэлээһин туругун уларытарга [[Special:UserLogin|бэлиэтэммит ааккын этиэхтээххин]].',
	'proofreadpage_notallowed' => 'Уларытар сатаммат',
	'proofreadpage_notallowedtext' => 'Бу сирэйи бэрэбиэркэлээһин туругун уларытар кыаҕыҥ суох.',
	'proofreadpage_number_expected' => 'Алҕас: Чыыһыла наада',
	'proofreadpage_interval_too_large' => 'Алҕас: наһаа улахан кээмэйи эппиккин',
	'proofreadpage_invalid_interval' => 'Алҕас: сыыһа интервал',
	'proofreadpage_nextpage' => 'Аныгыскы сирэй',
	'proofreadpage_prevpage' => 'Иннинээҕи сирэй',
	'proofreadpage_header' => 'Аата (киллэриллибэт):',
	'proofreadpage_body' => 'Сирэй иһэ (холбонуо):',
	'proofreadpage_footer' => 'Аллараа колонтитул (киллэриллибэт):',
	'proofreadpage_toggleheaders' => 'киллэриллибэт разделлары көрдөр',
	'proofreadpage_quality0_category' => 'Кураанах',
	'proofreadpage_quality1_category' => 'Ааҕыллыбатах',
	'proofreadpage_quality2_category' => 'Моһоллоох',
	'proofreadpage_quality3_category' => 'Ааҕыллыбыт',
	'proofreadpage_quality4_category' => 'Бэрэбиэркэлэммит',
	'proofreadpage_quality0_message' => 'Бу сирэй бэрэбиэркэлэнэрэ ирдэммэт',
	'proofreadpage_quality1_message' => 'Бу сирэй тургутуллубатах',
	'proofreadpage_quality2_message' => 'Бу сирэйи тургутарга туох эрэ моһол үөскээбит',
	'proofreadpage_quality3_message' => 'Бу сирэй тургутуллубут',
	'proofreadpage_quality4_message' => 'Бу сирэй бэрэбиэкэлэммит (выверка)',
	'proofreadpage_index_listofpages' => 'Сирэйдэр испииһэктэрэ',
	'proofreadpage_image_message' => 'Индекс сирэйигэр ыйынньык',
	'proofreadpage_page_status' => 'Сирэй статуһа',
	'proofreadpage_js_attributes' => 'Ааптар Айымньы Сыла Кыһата',
	'proofreadpage_index_attributes' => 'Ааптар
Айымньы аата
Сыла|Бэчээттэммит сыла
Кыһа аата
Источник
Ойуу|Таһын ойуута
Сирэйин ахсаана||20
Хос быһаарыылара||10',
	'proofreadpage_pages' => '{{PLURAL:$1|сирэй|сирэйдээх}}', # Fuzzy
	'proofreadpage_specialpage_legend' => 'Индекстаммыт сирэйдэри көрдөөһүн',
	'proofreadpage_source' => 'Хантан ылыллыбыта',
	'proofreadpage_source_message' => 'Тиэкис электрон барылын оҥорорго скааннаммыт матырыйааллар туһаныллыбыттар',
	'right-pagequality' => 'Сирэй туругун бэлиэтин уларытыы',
);

/** Sardinian (sardu)
 * @author Andria
 * @author Marzedu
 */
$messages['sc'] = array(
	'proofreadpage_image' => 'Immàgine',
	'proofreadpage_index_listofpages' => 'Lista de is pàginas',
	'proofreadpage_pages' => '{{PLURAL:$1|pàgina|pàginas}}', # Fuzzy
);

/** Sicilian (sicilianu)
 * @author Aushulz
 * @author Gmelfi
 */
$messages['scn'] = array(
	'proofreadpage_image' => 'Immaggini',
	'proofreadpage_nextpage' => 'Pàggina appressu',
	'proofreadpage_prevpage' => "Pàggina d'antura",
	'proofreadpage_header' => 'Ntistazzioni (nun inclusa)',
);

/** Sinhala (සිංහල)
 * @author Singhalawap
 * @author පසිඳු කාවින්ද
 * @author බිඟුවා
 */
$messages['si'] = array(
	'indexpages' => 'සුචි පිටු ලැයිස්තුව',
	'pageswithoutscans' => 'පරිලෝකන රහිත පිටු',
	'proofreadpage_image' => 'පිංතූරය',
	'proofreadpage_index' => 'සුචිය',
	'proofreadpage_index_expected' => 'දෝෂය: සුචිය අපේක්ෂිතයි',
	'proofreadpage_nosuch_index' => 'දෝෂය: සත්‍ය සුචියක් නොමැත',
	'proofreadpage_nosuch_file' => 'දෝෂය: සත්‍ය ගොනුවක් නොමැත',
	'proofreadpage_badpage' => 'වැරදි ආකෘතිය',
	'proofreadpage_badpagetext' => 'ඔබ සුරැකීමට තැත්කළ පිටුවේ ආකෘතිය වැරදිය.',
	'proofreadpage_indexdupe' => 'අනුපිටපත් සබැඳිය',
	'proofreadpage_nologin' => 'ප්‍රවිෂ්ට වී නොමැත',
	'proofreadpage_notallowed' => 'වෙනස්කමට ඉඩ ලබා නොදේ',
	'proofreadpage_notallowedtext' => 'මෙම පිටුවේ සෝදුපත් බැලීම් තත්වය වෙනස් කිරීමට ඔබට ඉඩ ලබා නොදේ.',
	'proofreadpage_number_expected' => 'දෝෂය: නාමික අගය අපේක්ෂිතයි',
	'proofreadpage_interval_too_large' => 'දෝෂය: විවේකය දීර්ඝ වැඩියි',
	'proofreadpage_invalid_interval' => 'දෝෂය: වලංගු නොවන විවේකය',
	'proofreadpage_nextpage' => 'ඊළඟ පිටුව',
	'proofreadpage_prevpage' => 'පෙර පිටුව',
	'proofreadpage_header' => 'ශීර්ෂකය (අඩංගුනොකරන්න):',
	'proofreadpage_body' => 'පිටුවේ ඇතුලත (උත්තරීතර වීමට තිබෙන):',
	'proofreadpage_footer' => 'පාද තලය (අඩංගුනැත):',
	'proofreadpage_quality0_category' => 'පාඨයෙන් තොරයි',
	'proofreadpage_quality1_category' => 'සෝදුපත් බලා නොමැත',
	'proofreadpage_quality2_category' => 'ගැටලුසහගත',
	'proofreadpage_quality3_category' => 'සෝදුපත් බැලීම',
	'proofreadpage_quality4_category' => 'වලංගු කරන ලදී',
	'proofreadpage_quality0_message' => 'මෙම පිටුවේ සෝදුපත් බැලීමට අවශ්‍ය නැත',
	'proofreadpage_quality1_message' => 'මෙම පිටුවේ සෝදුපත් බලා නොමැත',
	'proofreadpage_quality2_message' => 'මෙම පිටුවේ සෝදුපත් බැලීමේදී දෝෂයක් හට ගැනුණි',
	'proofreadpage_quality3_message' => 'මෙම පිටුව සෝදුපත් බලා ඇත',
	'proofreadpage_quality4_message' => 'මෙම පිටුව වලංගු කර ඇත',
	'proofreadpage_index_status' => 'සුචි තත්වය',
	'proofreadpage_index_size' => 'පිටු ගණන',
	'proofreadpage_specialpage_label_orderby' => 'අනුපිළිවෙළ:',
	'proofreadpage_specialpage_label_key' => 'සොයන්න:',
	'proofreadpage_specialpage_label_sortascending' => 'ආරෝහණ වර්ගය',
	'proofreadpage_alphabeticalorder' => 'අකාරාදි පිළිවෙළ',
	'proofreadpage_index_listofpages' => 'පිටු ලැයිස්තුව',
	'proofreadpage_image_message' => 'සුචිගත පිටුවට සබැඳිගත කරන්න',
	'proofreadpage_page_status' => 'පිටුවේ තත්වය',
	'proofreadpage_js_attributes' => 'කර්තෘ මාතෘකාව වසර ප්‍රකාශක',
	'proofreadpage_pages' => '{{PLURAL:$1|පිටු|පිටු}} $2 ක්',
	'proofreadpage_specialpage_legend' => 'සුචිකරණය කල පිටු සොයන්න',
	'proofreadpage_specialpage_searcherror' => 'සෙවුම් එන්ජිමේ දෝෂයක්',
	'proofreadpage_source' => ' මූලාශ්‍රය',
	'right-pagequality' => 'පිටුවේ ගුණාත්මක ධජය වෙනස් කරන්න',
	'proofreadpage-section-tools' => 'සෝදුපත් බැලීමේ මෙවලම්',
	'proofreadpage-group-zoom' => 'විශාලනය',
	'proofreadpage-group-other' => 'වෙනත්',
	'proofreadpage-button-toggle-visibility-label' => 'මෙම පිටුවේ ශීර්ෂකය සහ පාදතලය පෙන්වන්න/සඟවන්න',
	'proofreadpage-button-zoom-out-label' => 'විශාලනයෙන් ඉවත් වෙන්න',
	'proofreadpage-button-reset-zoom-label' => 'නියම ප්‍රමාණය',
	'proofreadpage-button-zoom-in-label' => 'විශාලනය කරන්න',
	'proofreadpage-button-toggle-layout-label' => 'සිරස්/තිරස් සැලැස්ම',
	'proofreadpage-indexoai-error-schemanotfound' => 'සංක්ෂිප්ත නිරූපණය හමු නොවුණි',
	'proofreadpage-indexoai-error-schemanotfound-text' => '$1 සංක්ෂිප්ත නිරූපණය හමු නොවුණි.',
);

/** Slovak (slovenčina)
 * @author Helix84
 */
$messages['sk'] = array(
	'indexpages' => 'Zoznam indexových stránok',
	'pageswithoutscans' => 'Stránky bez prehliadnutia',
	'proofreadpage_desc' => 'Umožňuje jednoduché porovnanie textu s originálnym skenom',
	'proofreadpage_image' => 'Obrázok',
	'proofreadpage_index' => 'Index',
	'proofreadpage_index_expected' => 'Chyba: očakával sa index',
	'proofreadpage_nosuch_index' => 'Chyba: taký index neexistuje',
	'proofreadpage_nosuch_file' => 'Chyba: Taký súbor neexistuje',
	'proofreadpage_badpage' => 'Nesprávny formát',
	'proofreadpage_badpagetext' => 'Formát stránky, ktorú ste sa pokúsili uložiť nie je správny.',
	'proofreadpage_indexdupe' => 'Duplicitný odkaz',
	'proofreadpage_indexdupetext' => 'Stránky nemožno na indexovej stránke uviesť viac ako raz.',
	'proofreadpage_nologin' => 'Nie ste prihlásený',
	'proofreadpage_nologintext' => 'Ak chcete meniť stav skontrolovania stránky, musíte sa [[Special:UserLogin|prihlásiť]].',
	'proofreadpage_notallowed' => 'Zmena nie je dovolená',
	'proofreadpage_notallowedtext' => 'Nemáte dovolené zmeniť stav skontrolovania tejto stránky.',
	'proofreadpage_number_expected' => 'Chyba: očakávala sa číselná hodnota',
	'proofreadpage_interval_too_large' => 'Chyba: interval je príliš veľký',
	'proofreadpage_invalid_interval' => 'Chyba: neplatný interval',
	'proofreadpage_nextpage' => 'Ďalšia stránka',
	'proofreadpage_prevpage' => 'Predošlá stránka',
	'proofreadpage_header' => 'Hlavička (noinclude):',
	'proofreadpage_body' => 'Telo stránky (pre transklúziu):',
	'proofreadpage_footer' => 'Pätka (noinclude):',
	'proofreadpage_toggleheaders' => 'prepnúť viditeľnosť sekcií noinclude',
	'proofreadpage_quality0_category' => 'Bez textu',
	'proofreadpage_quality1_category' => 'Nebolo skontrolované',
	'proofreadpage_quality2_category' => 'Problematické',
	'proofreadpage_quality3_category' => 'Skontrolované',
	'proofreadpage_quality4_category' => 'Overené',
	'proofreadpage_quality0_message' => 'Túto stránku netreba kontrolovať',
	'proofreadpage_quality1_message' => 'Táto stránka nebola skontrolovaná',
	'proofreadpage_quality2_message' => 'Nastal problém pri kontrolovaní tejto stránky',
	'proofreadpage_quality3_message' => 'Táto stránka bola skontrolovaná',
	'proofreadpage_quality4_message' => 'Táto stránka bola overená',
	'proofreadpage_index_listofpages' => 'Zoznam stránok',
	'proofreadpage_image_message' => 'Odkaz na stránku index',
	'proofreadpage_page_status' => 'Stav stránky',
	'proofreadpage_js_attributes' => 'Autor Názov Rok Vydavateľ',
	'proofreadpage_index_attributes' => 'Autor
Názov
Rok|Rok vydania
Vydavateľstvo
Zdroj
Obrázok|Obálka
Strán||20
Poznámky||10',
	'proofreadpage_pages' => '{{PLURAL:$1|stránka|stránky|stránok}}', # Fuzzy
	'proofreadpage_specialpage_legend' => 'Hľadať v stránkach indexu',
	'proofreadpage_source' => 'Zdroj',
	'proofreadpage_source_message' => 'Naskenované vydanie použité pri vzniku tohto textu',
	'right-pagequality' => 'Zmeniť príznak kvality stránky',
);

/** Slovenian (slovenščina)
 * @author Dbc334
 */
$messages['sl'] = array(
	'indexpages' => 'Seznam kazalnih strani',
	'pageswithoutscans' => 'Strani brez skeniranj',
	'proofreadpage_desc' => 'Omogočajo enostavno primerjavo besedila z izvirno preslikavo',
	'proofreadpage_image' => 'Slika',
	'proofreadpage_index' => 'Kazalo',
	'proofreadpage_index_expected' => 'Napaka: pričakovano kazalo',
	'proofreadpage_nosuch_index' => 'Napaka: ni takšnega kazala',
	'proofreadpage_nosuch_file' => 'Napaka: ni takšne datoteke',
	'proofreadpage_badpage' => 'Napačna oblika',
	'proofreadpage_badpagetext' => 'Oblika strani, ki ste jo poskušali shraniti, je nepravilna.',
	'proofreadpage_indexdupe' => 'Podvojena povezava',
	'proofreadpage_indexdupetext' => 'Strani na kazalu ni mogoče navesti več kot enkrat.',
	'proofreadpage_nologin' => 'Niste prijavljeni',
	'proofreadpage_nologintext' => 'Morate biti [[Special:UserLogin|prijavljeni]] za spreminjanje stanja lekture strani.',
	'proofreadpage_notallowed' => 'Sprememba ni dovoljena',
	'proofreadpage_notallowedtext' => 'Niste pooblaščeni za spreminjanje stanja lekture te strani.',
	'proofreadpage_number_expected' => 'Napaka: pričakovana številčna vrednost',
	'proofreadpage_interval_too_large' => 'Napaka: preveliko obdobje',
	'proofreadpage_invalid_interval' => 'Napaka: neveljavno obdobje',
	'proofreadpage_nextpage' => 'Naslednja stran',
	'proofreadpage_prevpage' => 'Prejšnja stran',
	'proofreadpage_header' => 'Glava (noinclude):',
	'proofreadpage_body' => 'Telo strani (ki bo vključeno):',
	'proofreadpage_footer' => 'Noga (noinclude):',
	'proofreadpage_toggleheaders' => 'preklopi vidnost razdelkov noinclude',
	'proofreadpage_quality0_category' => 'Brez besedila',
	'proofreadpage_quality1_category' => 'Nekorigirano',
	'proofreadpage_quality2_category' => 'Problematične strani',
	'proofreadpage_quality3_category' => 'Korigirano',
	'proofreadpage_quality4_category' => 'Potrjeno',
	'proofreadpage_quality0_message' => 'Te strani ni potrebno lektorirati',
	'proofreadpage_quality1_message' => 'Stran ni lektorirana',
	'proofreadpage_quality2_message' => 'Med lektoriranjem strani je prišlo do težave',
	'proofreadpage_quality3_message' => 'Stran je bila lektorirana',
	'proofreadpage_quality4_message' => 'Stran je bila potrjena',
	'proofreadpage_index_listofpages' => 'Seznam strani',
	'proofreadpage_image_message' => 'Povezava do kazala',
	'proofreadpage_page_status' => 'Stanje strani',
	'proofreadpage_js_attributes' => 'Avtor Naslov Leto Založnik',
	'proofreadpage_index_attributes' => 'Avtor
Naslov
Leto|Leto izida
Založba
Vir
Slika|Naslovnica
Strani||20
Pripombe||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|stran|strani}}',
	'proofreadpage_specialpage_legend' => 'Iskanje kazalnih strani',
	'proofreadpage_source' => 'Vir',
	'proofreadpage_source_message' => 'Preslikana izdaja, uporabljena za nastanek tega besedila',
	'right-pagequality' => 'Spremeni označbo kakovosti strani',
	'proofreadpage-section-tools' => 'Orodja za pregled',
	'proofreadpage-group-zoom' => 'Povečava',
	'proofreadpage-group-other' => 'Drugo',
	'proofreadpage-button-toggle-visibility-label' => 'Pokaži/skrij glavo in nogo strani',
	'proofreadpage-button-zoom-out-label' => 'Pomanjšaj',
	'proofreadpage-button-reset-zoom-label' => 'Ponastavi povečavo',
	'proofreadpage-button-zoom-in-label' => 'Povečaj',
	'proofreadpage-button-toggle-layout-label' => 'Navpična/vodoravna postavitev',
);

/** Serbian (Cyrillic script) (српски (ћирилица)‎)
 * @author Millosh
 * @author Rancher
 * @author Sasa Stefanovic
 * @author Жељко Тодоровић
 * @author Михајло Анђелковић
 */
$messages['sr-ec'] = array(
	'proofreadpage_desc' => 'Омогући лако упоређивање текста и оригиналног скена.',
	'proofreadpage_image' => 'слика',
	'proofreadpage_index' => 'индекс',
	'proofreadpage_badpage' => 'Погрешан Формат',
	'proofreadpage_notallowed' => 'Промена није дозвољена',
	'proofreadpage_nextpage' => 'Следећа страница',
	'proofreadpage_prevpage' => 'Претходна страница',
	'proofreadpage_header' => 'Заглавље (без укључивања):',
	'proofreadpage_body' => 'Тело стране (за укључивање):',
	'proofreadpage_footer' => 'Подножје (без укључивања):',
	'proofreadpage_toggleheaders' => 'управљање видљивошћу делова који се не укључују',
	'proofreadpage_quality0_category' => 'Без текста',
	'proofreadpage_quality1_category' => 'Непрегледано',
	'proofreadpage_quality2_category' => 'Проблематично',
	'proofreadpage_quality3_category' => 'Прегледано',
	'proofreadpage_quality4_category' => 'Оверено',
	'proofreadpage_index_listofpages' => 'Списак страница',
	'proofreadpage_image_message' => 'Веза ка индексу стране.',
	'proofreadpage_page_status' => 'Статус стране',
	'proofreadpage_js_attributes' => 'аутор наслов година издавач',
	'proofreadpage_index_attributes' => 'аутор
наслов
година|година публикације
издавач
извор
слика|насловна страна
страна||20
примедбе||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|страница|странице|страница}}',
	'proofreadpage_source' => 'Извор',
	'proofreadpage-section-tools' => 'Лекторске алатке',
	'proofreadpage-group-zoom' => 'Размера',
	'proofreadpage-group-other' => 'Друго',
);

/** Serbian (Latin script) (srpski (latinica)‎)
 * @author Michaello
 * @author Жељко Тодоровић
 */
$messages['sr-el'] = array(
	'proofreadpage_desc' => 'Omogući lako upoređivanje teksta i originalnog skena.',
	'proofreadpage_image' => 'Slika',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_badpage' => 'Pogrešan Format',
	'proofreadpage_notallowed' => 'Promena nije dozvoljena',
	'proofreadpage_nextpage' => 'Sledeća strana',
	'proofreadpage_prevpage' => 'Prethodna strana',
	'proofreadpage_header' => 'Zaglavlje (bez uključivanja):',
	'proofreadpage_body' => 'Telo strane (za uključivanje):',
	'proofreadpage_footer' => 'Podnožje (bez uključivanja):',
	'proofreadpage_toggleheaders' => 'upravljanje vidljivošću delova koji se ne uključuju',
	'proofreadpage_quality0_category' => 'Bez teksta',
	'proofreadpage_quality1_category' => 'Nepregledano',
	'proofreadpage_quality2_category' => 'Problematično',
	'proofreadpage_quality3_category' => 'Pregledano',
	'proofreadpage_quality4_category' => 'Overeno',
	'proofreadpage_index_listofpages' => 'Spisak strana',
	'proofreadpage_image_message' => 'Veza ka indeksu strane.',
	'proofreadpage_page_status' => 'Status strane',
	'proofreadpage_js_attributes' => 'autor naslov godina izdavač',
	'proofreadpage_index_attributes' => 'autor
naslov
godina|godina publikacije
izdavač
izvor
slika|naslovna strana
strana||20
primedbe||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|stranica|stranice|stranica}}', # Fuzzy
	'proofreadpage_source' => 'Izvor',
	'proofreadpage-section-tools' => 'Lektorske alatke',
	'proofreadpage-group-zoom' => 'Razmera',
	'proofreadpage-group-other' => 'Drugo',
);

/** Seeltersk (Seeltersk)
 * @author Pyt
 */
$messages['stq'] = array(
	'proofreadpage_desc' => 'Moaket dät mäkkelk Ferglieken muugelk fon Text mäd dän Originoalscan',
	'proofreadpage_image' => 'Scan',
	'proofreadpage_index' => 'Index',
	'proofreadpage_nextpage' => 'Naiste Siede',
	'proofreadpage_prevpage' => 'Foarige Siede',
	'proofreadpage_header' => 'Kopriege (noinclude):',
	'proofreadpage_body' => 'Textköärper (Transklusion):',
	'proofreadpage_footer' => 'Foutriege (noinclude):',
	'proofreadpage_toggleheaders' => 'noinclude-Ousnitte ien-/uutbländje',
	'proofreadpage_quality1_category' => 'Uunkorrigierd',
	'proofreadpage_quality2_category' => 'Nit fulboodich',
	'proofreadpage_quality3_category' => 'Korrigierd',
	'proofreadpage_quality4_category' => 'Kloor',
	'proofreadpage_index_listofpages' => 'Siedenlieste',
	'proofreadpage_image_message' => 'Ferbiendenge tou ju Indexsiede',
	'proofreadpage_page_status' => 'Siedenstoatus',
	'proofreadpage_js_attributes' => 'Autor Tittel Jier Ferlaach',
	'proofreadpage_index_attributes' => 'Autor
Tittel
Jier|Ärskienengsjier
Ferlaach
Wälle
Bielde|Tittelbielde
Sieden||20
Bemäärkengen||10',
);

/** Sundanese (Basa Sunda)
 * @author Kandar
 */
$messages['su'] = array(
	'proofreadpage_image' => 'Gambar',
	'proofreadpage_index' => 'Béréndélan',
	'proofreadpage_nextpage' => 'Kaca salajengna',
	'proofreadpage_prevpage' => 'Kaca saméméhna',
	'proofreadpage_index_listofpages' => 'Béréndélan kaca',
	'proofreadpage_image_message' => 'Tumbu ka kaca béréndélan',
	'proofreadpage_page_status' => 'Status kaca',
	'proofreadpage_js_attributes' => 'Pangarang Judul Taun Pamedal',
	'proofreadpage_index_attributes' => 'Pangarang
Judul
Taun|Taun medal
Pamedal
Sumber
Gambar|Gambar jilid
Kaca||20
Catetan||10',
);

/** Swedish (svenska)
 * @author Cohan
 * @author Diupwijk
 * @author Fluff
 * @author Jopparn
 * @author Lejonel
 * @author Lokal Profil
 * @author M.M.S.
 * @author Najami
 * @author Per
 * @author Rotsee
 * @author Thurs
 * @author WikiPhoenix
 */
$messages['sv'] = array(
	'indexpages' => 'Lista över indexsidor',
	'pageswithoutscans' => 'Sidor utan scanningar.',
	'proofreadpage_desc' => 'Ger möjlighet att korrekturläsa texter mot scannade original',
	'proofreadpage_image' => 'Bild',
	'proofreadpage_index' => 'Indexsida',
	'proofreadpage_index_expected' => 'Fel: index förväntades',
	'proofreadpage_nosuch_index' => 'Fel: index saknas',
	'proofreadpage_nosuch_file' => 'Fel: fil saknas',
	'proofreadpage_badpage' => 'Fel format',
	'proofreadpage_badpagetext' => 'Sidan du försöker spara har ett felaktigt format.',
	'proofreadpage_indexdupe' => 'Dubblett av länk',
	'proofreadpage_indexdupetext' => 'Sidor kan inte listas mer än en gång på en index-sida.',
	'proofreadpage_nologin' => 'Ej inloggad',
	'proofreadpage_nologintext' => 'Du måste vara [[Special:UserLogin|inloggad]] för att förändra status på korrekturläsningen av sidor.',
	'proofreadpage_notallowed' => 'Förändring är inte tillåten',
	'proofreadpage_notallowedtext' => 'Du har inte rättigheter att ändra status på korrekturläsningen av den här sidan.',
	'proofreadpage_dataconfig_badformatted' => 'Bugg i datakonfiguration',
	'proofreadpage_dataconfig_badformattedtext' => 'Sidan [[Mediawiki:Proofreadpage index data config]] är inte i välformaterad JSON.',
	'proofreadpage_number_expected' => 'Fel: ett numeriskt värde förväntades',
	'proofreadpage_interval_too_large' => 'Fel: ett för stort intervall',
	'proofreadpage_invalid_interval' => 'Fel: ogiltigt intervall',
	'proofreadpage_nextpage' => 'Nästa sida',
	'proofreadpage_prevpage' => 'Föregående sida',
	'proofreadpage_header' => 'Sidhuvud (inkluderas ej):',
	'proofreadpage_body' => 'Sidinnehåll (som ska inkluderas):',
	'proofreadpage_footer' => 'Sidfot (inkluderas ej):',
	'proofreadpage_toggleheaders' => 'visa/dölj sidhuvud',
	'proofreadpage_quality0_category' => 'Utan text',
	'proofreadpage_quality1_category' => 'Ej korrekturläst',
	'proofreadpage_quality2_category' => 'Ofullständigt',
	'proofreadpage_quality3_category' => 'Korrekturläst',
	'proofreadpage_quality4_category' => 'Validerat',
	'proofreadpage_quality0_message' => 'Den här sidan behöver inte korrekturläsas',
	'proofreadpage_quality1_message' => 'Den här sidan har inte korrekturlästs',
	'proofreadpage_quality2_message' => 'Ett problem uppstod när den här sidan skulle korrekturläsas',
	'proofreadpage_quality3_message' => 'Den här sidan har korrekturlästs',
	'proofreadpage_quality4_message' => 'Den här sidan har godkänts',
	'proofreadpage_index_status' => 'Indexstatus',
	'proofreadpage_index_size' => 'Antal sidor',
	'proofreadpage_specialpage_label_orderby' => 'Sortera efter:',
	'proofreadpage_specialpage_label_key' => 'Sök:',
	'proofreadpage_specialpage_label_sortascending' => 'Sortera stigande',
	'proofreadpage_alphabeticalorder' => 'Alfabetisk ordning',
	'proofreadpage_index_listofpages' => 'Lista över sidor',
	'proofreadpage_image_message' => 'Länk till indexsidan',
	'proofreadpage_page_status' => 'Sidans status',
	'proofreadpage_js_attributes' => 'Författare Titel År Utgivare',
	'proofreadpage_index_attributes' => 'Upphovsman
Titel
År|Utgivningsår
Utgivare
Källa
Bild|Omslagsbild
Sidor||20
Anmärkningar||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|sida|sidor}}',
	'proofreadpage_specialpage_legend' => 'Sök i indexsidorna',
	'proofreadpage_specialpage_searcherror' => 'Fel i sökmotorn',
	'proofreadpage_specialpage_searcherrortext' => 'Sökmotorn fungerar inte. Vi beklagar för olägenheten.',
	'proofreadpage_source' => 'Källa',
	'proofreadpage_source_message' => 'Scannat original använt för att skapa denna text',
	'right-pagequality' => 'Ändra sidans kvalitetsflagga',
	'proofreadpage-section-tools' => 'Korrekturläsningsverktyg',
	'proofreadpage-group-zoom' => 'Zooma',
	'proofreadpage-group-other' => 'Övrigt',
	'proofreadpage-button-toggle-visibility-label' => 'Visa/dölj denna sidas sidhuvud och sidfot',
	'proofreadpage-button-zoom-out-label' => 'Zooma ut',
	'proofreadpage-button-reset-zoom-label' => 'Återställ zoom',
	'proofreadpage-button-zoom-in-label' => 'Zooma in',
	'proofreadpage-button-toggle-layout-label' => 'Vertikal/horisontell uppsättning',
	'proofreadpage-preferences-showheaders-label' => 'Visa fälten för sidhuvud och sidfot vid redigering i namnrymden {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Använd vågrät layout vid redigering i namnrymden {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Metadata för böcker från {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadata för böcker som hanteras av ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Schemat hittades inte',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Schemat $1 har inte hittats.',
);

/** Swahili (Kiswahili)
 * @author Ikiwaner
 * @author Stephenwanjau
 */
$messages['sw'] = array(
	'proofreadpage_image' => 'Picha',
	'proofreadpage_specialpage_label_key' => 'Tafuta:',
	'proofreadpage_source' => 'Chanzo',
);

/** Silesian (ślůnski)
 * @author Herr Kriss
 */
$messages['szl'] = array(
	'proofreadpage_image' => 'Uobrozek',
	'proofreadpage_nextpage' => 'Nostympno zajta',
	'proofreadpage_prevpage' => 'Popředńo zajta',
);

/** Tamil (தமிழ்)
 * @author Balajijagadesh
 * @author Karthi.dr
 * @author Logicwiki
 * @author Shanmugamp7
 * @author TRYPPN
 * @author மதனாஹரன்
 */
$messages['ta'] = array(
	'indexpages' => 'அட்டவணை பக்கங்கிளின் பட்டியல்',
	'pageswithoutscans' => 'வருடி இல்லாத பக்கங்கள்',
	'proofreadpage_image' => 'படம்',
	'proofreadpage_index' => 'அட்டவணை',
	'proofreadpage_nosuch_index' => 'பிழை:அப்படிப்பட்ட அட்டவணை இல்லை',
	'proofreadpage_nosuch_file' => 'பிழை: இப்படி ஒரு கோப்பே இல்லை',
	'proofreadpage_badpage' => 'தவறான வடிவம்',
	'proofreadpage_indexdupe' => 'இணைப்பை நகலெடு',
	'proofreadpage_nologin' => 'புகுபதிகை செய்யப்படவில்லை',
	'proofreadpage_notallowed' => 'மாற்றத்திற்கு அனுமதி இல்லை.',
	'proofreadpage_notallowedtext' => 'இந்த பக்கத்தின் மெய்ப்பு பார்க்கும் நிலையை மாற்ற தங்களுக்கு உரிமை இல்லை',
	'proofreadpage_nextpage' => 'அடுத்த பக்கம்',
	'proofreadpage_prevpage' => 'முந்தைய பக்கம்',
	'proofreadpage_quality0_category' => 'உரை இல்லாமல்',
	'proofreadpage_quality1_category' => 'மெய்ப்பு பார்க்கப்படாதவை',
	'proofreadpage_quality2_category' => 'சிக்கலானவை',
	'proofreadpage_quality3_category' => 'மெய்ப்புப் பார்க்கப்பட்டவை',
	'proofreadpage_quality4_category' => 'சரிபார்க்கப்பட்டவை',
	'proofreadpage_quality0_message' => 'இந்த பக்கத்தை மெய்ப்பு பார்க்க தேவை இல்லை',
	'proofreadpage_quality1_message' => 'இந்த பக்கம் மெய்ப்பு பார்க்கப்படவில்லை',
	'proofreadpage_quality2_message' => 'மெய்ப்பு பார்க்கும் பொழுது ஏதோ ஒரு பிரச்சனை',
	'proofreadpage_quality3_message' => 'இந்த பக்கம் மெய்ப்பு பார்க்கப்பட்டுள்ளது',
	'proofreadpage_quality4_message' => 'இந்தப் பக்கம் பரிசீலிக்கப்பட்டது.',
	'proofreadpage_index_status' => 'அட்டவணை நிலைமை',
	'proofreadpage_index_size' => 'பக்கங்களின் எண்ணிக்கை',
	'proofreadpage_specialpage_label_orderby' => 'வரிசை படுத்து:',
	'proofreadpage_specialpage_label_key' => 'தேடு:',
	'proofreadpage_specialpage_label_sortascending' => 'ஏறுவரிசையில் ஒழுங்குபடுத்தவும்',
	'proofreadpage_alphabeticalorder' => 'அகரவரிசை ஒழுங்கு',
	'proofreadpage_index_listofpages' => 'பக்கங்களின் பட்டியல்',
	'proofreadpage_image_message' => 'அட்டவணை பக்கத்திற்கு இணை',
	'proofreadpage_page_status' => 'பக்கத்தின் நிலைமை',
	'proofreadpage_js_attributes' => 'எழுத்தாளர் தலைப்பு ஆண்டு பதிப்பகம்',
	'proofreadpage_index_attributes' => 'எழுத்தாளர்
தலைப்பு
ஆண்டு|வெளியிட்ட ஆண்டு
பதிப்பகம்
மூலம்
படம்|அட்டைப் படம்
பக்கங்கள்||20
கருத்துக்கள்||10',
	'proofreadpage_pages' => '$1 {{PLURAL:$1|பக்கம்|பக்கங்கள்}}', # Fuzzy
	'proofreadpage_specialpage_legend' => 'அட்டவணை பக்கங்களை தேடவும்',
	'proofreadpage_specialpage_searcherror' => 'தேடு பொறியில் பிழை',
	'proofreadpage_specialpage_searcherrortext' => 'தேடு பொறி இயங்கவில்லை. வசதிக்குறைவுக்கு மன்னிக்கவும்.',
	'proofreadpage_source' => 'மூலம்',
	'proofreadpage-section-tools' => 'மெய்ப்பு பார்க்கும் கருவிகள்',
	'proofreadpage-group-zoom' => 'பெரிதாக்கு',
	'proofreadpage-group-other' => 'மற்றவை',
	'proofreadpage-button-zoom-out-label' => 'உருவ அளவு சிறிதாக்கு',
	'proofreadpage-button-reset-zoom-label' => 'மூல அளவு',
	'proofreadpage-button-zoom-in-label' => 'உருவ அளவு பெரிதாக்கு',
	'proofreadpage-button-toggle-layout-label' => 'கிடை/செங்குத்து வடிவமைப்பு',
	'proofreadpage-indexoai-error-schemanotfound' => 'கருத்தேற்ற முறைமை காணப்படவில்லை',
);

/** Telugu (తెలుగు)
 * @author Kiranmayee
 * @author Mpradeep
 * @author Veeven
 * @author రాకేశ్వర
 */
$messages['te'] = array(
	'indexpages' => 'సూచిక పుటల జాబితా',
	'proofreadpage_desc' => 'గద్యానికీ అసలు బొమ్మకు (స్కాన్) మధ్యన తేలికగా పోల్చిచూపడాన్ని అనుమతించు',
	'proofreadpage_image' => 'బొమ్మ',
	'proofreadpage_index' => 'సూచిక',
	'proofreadpage_index_expected' => 'పొరపాటు: సూచిక వుండవలసినది',
	'proofreadpage_nosuch_index' => 'పొరపాటు: అటువంటి సూచిక లేదు',
	'proofreadpage_nosuch_file' => 'పొరపాటు: అటువంటి దస్త్రం లేదు',
	'proofreadpage_badpage' => 'తప్పుడు రూపము(format)',
	'proofreadpage_badpagetext' => 'మీరు భద్రపరచడానికి ప్రయత్నించిన పుట యొక్క రూపం చెల్లదు.',
	'proofreadpage_indexdupe' => 'నకిలీ లంకె',
	'proofreadpage_indexdupetext' => 'ఒక సూచికలో ఒక పుటను ఒక్క సారి కంటే ఎక్కువ ఎక్కించరాదు.',
	'proofreadpage_nologin' => 'లోనికి ప్రవేశించిలేరు',
	'proofreadpage_nologintext' => 'పుట అచ్చుదిద్దుస్థితి మార్చడానికి మీరు [[ప్రత్యేక:వాడుకరిప్రవేశం|లోనికి ప్రవేశించి]] వుండాలి.', # Fuzzy
	'proofreadpage_notallowed' => 'మార్పడానికి అనుమతి లేదు',
	'proofreadpage_notallowedtext' => 'ఈ పుటయొక్క అచ్చుదిద్దుస్థితిని మార్చడానికి మీరు తగరు.',
	'proofreadpage_number_expected' => 'పొరబాటు: సంఖ్య వుండవలెను',
	'proofreadpage_interval_too_large' => 'పొరబాటు: గడువు మఱీ ఎక్కువగా వున్నది',
	'proofreadpage_invalid_interval' => 'పొరబాటు: గడువు చెల్లదు',
	'proofreadpage_nextpage' => 'తరువాతి పుట',
	'proofreadpage_prevpage' => 'క్రిత పుట',
	'proofreadpage_header' => 'శీర్షిక (కలుపకు):',
	'proofreadpage_body' => 'పుటావస్తువు (పుట నుండి లాక్కోబడవలసిన వస్తువు):',
	'proofreadpage_footer' => 'పాదము (కలుపకు):',
	'proofreadpage_toggleheaders' => 'చూపించకూడని భాగాలను(noinclude sections) చూపించడం లేదా చూపించకపోవడాన్ని మార్చండి',
	'proofreadpage_quality0_category' => 'పాఠ్యం లేనివి',
	'proofreadpage_quality1_category' => 'అచ్చుదిద్దబడలేదు.',
	'proofreadpage_quality2_category' => 'అచ్చుదిద్దుడు సమస్యాత్మకం',
	'proofreadpage_quality3_category' => 'అచ్చుదిద్దబడినవి',
	'proofreadpage_quality4_category' => 'ఆమోదించబడ్డవి',
	'proofreadpage_quality0_message' => 'ఈ పుటను అచ్చుదిద్దనక్కరలేదు',
	'proofreadpage_quality1_message' => 'ఈ పుట అచ్చుదిద్దబడలేదు',
	'proofreadpage_quality2_message' => 'ఈ పుటను అచ్చుదిద్దుతున్నప్పుడు తెలియని సమస్య ఎదురైనది',
	'proofreadpage_quality3_message' => 'ఈ పుట అచ్చుదిద్దబడ్డది',
	'proofreadpage_quality4_message' => 'ఈ పుట ఆమోదించబడ్డది',
	'proofreadpage_index_size' => 'పేజీల సంఖ్య',
	'proofreadpage_index_listofpages' => 'పుటల జాబితా',
	'proofreadpage_image_message' => 'సూచిక పుటకు లంకె',
	'proofreadpage_page_status' => 'పుట స్థితి',
	'proofreadpage_js_attributes' => 'రచయిత శీర్షిక సంవత్సరం ప్రచురణకర్త',
	'proofreadpage_index_attributes' => 'రచయిత
శీర్షిక
సంవత్సరం|ప్రచురణ సంవత్సరం
ప్రచురణకర్త
మూలం
బొమ్మ|ముఖచిత్రం
పుటలు||20
వ్యాఖ్యలు||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|పేజీ|పేజీలు}}',
	'proofreadpage_specialpage_legend' => 'సూచీపుటలు వెదకు',
	'proofreadpage_source' => 'మూలము',
	'proofreadpage_source_message' => 'ఈ పాఠ్య నిర్ధారణకు ఛాయాచిత్రసంగ్రహణకూర్పు(scanned edition) వాడబడ్డది.',
	'proofreadpage-group-other' => 'ఇతర',
	'proofreadpage-button-reset-zoom-label' => 'అసలు పరిమాణం',
);

/** Tetum (tetun)
 * @author MF-Warburg
 */
$messages['tet'] = array(
	'proofreadpage_nextpage' => 'Pájina oinmai',
	'proofreadpage_prevpage' => 'Pájina molok',
	'proofreadpage_pages' => '{{PLURAL:$1|pájina ida|pájina $2}}',
);

/** Tajik (Cyrillic script) (тоҷикӣ)
 * @author Ibrahim
 */
$messages['tg-cyrl'] = array(
	'proofreadpage_desc' => 'Имкони муқоисаи осони матн бо нусхаи аслии пӯйишшударо фароҳам меоварад',
	'proofreadpage_image' => 'акс',
	'proofreadpage_index' => 'Индекс',
	'proofreadpage_nextpage' => 'Саҳифаи баъдӣ',
	'proofreadpage_prevpage' => 'Саҳифаи қаблӣ',
	'proofreadpage_header' => 'Унвон (noinclude):',
	'proofreadpage_body' => 'Тани саҳифа (барои ғунҷонида шудан):',
	'proofreadpage_footer' => 'Понавис (noinclude):',
	'proofreadpage_toggleheaders' => 'тағйири намоёнии бахшҳои noinclude',
	'proofreadpage_quality1_category' => 'Бозбинӣ нашуда',
	'proofreadpage_quality2_category' => 'Мушкилдор',
	'proofreadpage_quality3_category' => 'Бозбинишуда',
	'proofreadpage_quality4_category' => 'Таъйидшуда',
	'proofreadpage_index_listofpages' => 'Феҳристи саҳифаҳо',
	'proofreadpage_image_message' => 'Пайванд ба саҳифаи индекс',
	'proofreadpage_page_status' => 'Вазъияти саҳифа',
	'proofreadpage_js_attributes' => 'Муаллиф Унвон Сол Нашриёт',
	'proofreadpage_index_attributes' => 'Муаллиф
Унвон
Сол|Соли интишор
Нашриёт
Манбаъ
Акс|Акси рӯи ҷилд
Саҳифаҳо||20
Мулоҳизот||10',
);

/** Tajik (Latin script) (tojikī)
 * @author Liangent
 */
$messages['tg-latn'] = array(
	'proofreadpage_desc' => 'Imkoni muqoisai osoni matn bo nusxai asliji pūjişşudaro faroham meovarad',
	'proofreadpage_image' => 'Aks',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_nextpage' => "Sahifai ba'dī",
	'proofreadpage_prevpage' => 'Sahifai qablī',
	'proofreadpage_header' => 'Unvon (noinclude):',
	'proofreadpage_body' => 'Tani sahifa (baroi ƣunçonida şudan):',
	'proofreadpage_footer' => 'Ponavis (noinclude):',
	'proofreadpage_toggleheaders' => 'taƣjiri namojoniji baxşhoi noinclude',
	'proofreadpage_quality1_category' => 'Bozbinī naşuda',
	'proofreadpage_quality2_category' => 'Muşkildor',
	'proofreadpage_quality3_category' => 'Bozbinişuda',
	'proofreadpage_quality4_category' => "Ta'jidşuda",
	'proofreadpage_index_listofpages' => 'Fehristi sahifaho',
	'proofreadpage_image_message' => 'Pajvand ba sahifai indeks',
	'proofreadpage_page_status' => "Vaz'ijati sahifa",
	'proofreadpage_js_attributes' => 'Muallif Unvon Sol Naşrijot',
	'proofreadpage_index_attributes' => "Muallif
Unvon
Sol|Soli intişor
Naşrijot
Manba'
Aks|Aksi rūi çild
Sahifaho||20
Mulohizot||10",
);

/** Thai (ไทย)
 * @author Horus
 * @author Nullzero
 * @author Octahedron80
 * @author Passawuth
 */
$messages['th'] = array(
	'indexpages' => 'รายการหน้าดัชนี',
	'pageswithoutscans' => 'หน้าที่ไม่ได้สแกน',
	'proofreadpage_desc' => 'อนุญาตให้เปรียบเทียบระหว่างข้อความกับต้นฉบับสแกนอย่างง่าย',
	'proofreadpage_image' => 'ภาพ',
	'proofreadpage_index' => 'ดัชนี',
	'proofreadpage_index_expected' => 'ข้อผิดพลาด: คาดหวังเป็นดัชนี',
	'proofreadpage_nosuch_index' => 'ข้อผิดพลาด: ไม่มีดัชนีเช่นนั้น',
	'proofreadpage_nosuch_file' => 'ข้อผิดพลาด: ไม่มีแฟ้มเช่นนั้น',
	'proofreadpage_badpage' => 'รูปแบบไม่ถูกต้อง',
	'proofreadpage_badpagetext' => 'รูปแบบของหน้าที่คุณพยายามบันทึกไม่ถูกต้อง',
	'proofreadpage_indexdupe' => 'ลิงก์ซ้ำ',
	'proofreadpage_indexdupetext' => 'หน้าต่าง ๆ ไม่สามารถแสดงรายการได้เกินหนึ่งครั้งในหน้าดัชนี',
	'proofreadpage_nologin' => 'ไม่ได้ล็อกอิน',
	'proofreadpage_nologintext' => 'คุณต้อง[[Special:UserLogin|ล็อกอิน]]เพื่อเปลี่ยนสถานะการพิสูจน์อักษรของหน้าต่าง ๆ',
	'proofreadpage_notallowed' => 'ไม่อนุญาตให้เปลี่ยนแปลง',
	'proofreadpage_notallowedtext' => 'คุณไม่สามารถเปลี่ยนสถานะการพิสูจน์อักษรของหน้านี้',
	'proofreadpage_dataconfig_badformatted' => 'ข้อบกพร่องในการกำหนดค่าข้อมูล',
	'proofreadpage_dataconfig_badformattedtext' => 'หน้า [[Mediawiki:Proofreadpage index data config]] ไม่อยู่ในรูปแบบเจซันที่เหมาะสม',
	'proofreadpage_number_expected' => 'ข้อผิดพลาด: คาดหวังเป็นค่าตัวเลข',
	'proofreadpage_interval_too_large' => 'ข้อผิดพลาด: ช่วงกว้างเกินไป',
	'proofreadpage_invalid_interval' => 'ข้อผิดพลาด: ช่วงไม่ถูกต้อง',
	'proofreadpage_nextpage' => 'หน้าถัดไป',
	'proofreadpage_prevpage' => 'หน้าก่อนหน้า',
	'proofreadpage_header' => 'หัวเรื่อง (ไม่ถูกรวม) :',
	'proofreadpage_body' => 'เนื้อหาของหน้า (จะถูกรวม):',
	'proofreadpage_footer' => 'ท้ายเรื่อง (ไม่ถูกรวม):',
	'proofreadpage_toggleheaders' => 'เปิด/ปิดส่วนแสดงผลที่ไม่ถูกรวม',
	'proofreadpage_quality0_category' => 'ไม่มีข้อความ',
	'proofreadpage_quality1_category' => 'ยังไม่พิสูจน์อักษร',
	'proofreadpage_quality2_category' => 'เป็นปัญหา',
	'proofreadpage_quality3_category' => 'พิสูจน์อักษร',
	'proofreadpage_quality4_category' => 'ตรวจสอบแล้ว',
	'proofreadpage_quality0_message' => 'หน้านี้ไม่จำเป็นต้องพิสูจน์อักษร',
	'proofreadpage_quality1_message' => 'หน้านี้ยังไม่ได้พิสูจน์อักษร',
	'proofreadpage_quality2_message' => 'มีปัญหาเกิดขึ้นขณะพิสูจน์อักษรหน้านี้',
	'proofreadpage_quality3_message' => 'หน้านี้ได้พิสูจน์อักษรแล้ว',
	'proofreadpage_quality4_message' => 'หน้านี้ได้ตรวจสอบแล้ว',
	'proofreadpage_index_status' => 'สถานะดัชนี',
	'proofreadpage_index_size' => 'จำนวนหน้า',
	'proofreadpage_specialpage_label_orderby' => 'เรียงลำดับตาม:',
	'proofreadpage_specialpage_label_key' => 'ค้นหา:',
	'proofreadpage_specialpage_label_sortascending' => 'เรียงจากน้อยไปมาก',
	'proofreadpage_alphabeticalorder' => 'ลำดับตัวอักษร',
	'proofreadpage_index_listofpages' => 'รายชื่อหน้า',
	'proofreadpage_image_message' => 'ลิงก์ไปยังหน้าดัชนี',
	'proofreadpage_page_status' => 'สถานะของหน้า',
	'proofreadpage_js_attributes' => 'ผู้แต่ง ชื่อเรื่อง ปี สำนักพิมพ์',
	'proofreadpage_index_attributes' => 'ผู้แต่ง
ชื่อเรื่อง
ปี|ปีที่พิมพ์
สำนักพิมพ์
แหล่งที่มา
ภาพ|ภาพหน้าปก
หน้า||20
หมายเหตุ||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|หน้า|หน้า}}',
	'proofreadpage_specialpage_legend' => 'ค้นหาหน้าดัชนี',
	'proofreadpage_specialpage_searcherror' => 'ข้อผิดพลาดในเสิร์ชเอนจิน',
	'proofreadpage_specialpage_searcherrortext' => 'เสิร์ชเอนจินไม่ทำงาน ขออภัยในความไม่สะดวก',
	'proofreadpage_source' => 'แหล่งที่มา',
	'proofreadpage_source_message' => 'รุ่นสแกนที่ใช้สร้างข้อความนี้',
	'right-pagequality' => 'เปลี่ยนแปลงตัวบ่งชี้คุณภาพหน้า',
	'proofreadpage-section-tools' => 'เครื่องมือพิสูจน์อักษร',
	'proofreadpage-group-zoom' => 'ซูม',
	'proofreadpage-group-other' => 'อื่น ๆ',
	'proofreadpage-button-toggle-visibility-label' => 'แสดง/ซ่อนหัวเรื่องและท้ายเรื่องของหน้านี้',
	'proofreadpage-button-zoom-out-label' => 'ซูมออก',
	'proofreadpage-button-reset-zoom-label' => 'ขนาดเดิม',
	'proofreadpage-button-zoom-in-label' => 'ซูมเข้า',
	'proofreadpage-button-toggle-layout-label' => 'เค้าโครงแนวตั้ง/แนวนอน',
	'proofreadpage-preferences-showheaders-label' => 'แสดงเขตข้อมูลหัวเรื่องและท้ายเรื่องเมื่อแก้ไขในเนมสเปซ {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'ใช้เค้าโครงแนวนอนเมื่อแก้ไขในเนมสเปซ {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'เมทาเดตาของหนังสือจาก {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'เมทาเดตาของหนังสือที่จัดการโดย ProofreadPage',
	'proofreadpage-indexoai-error-schemanotfound' => 'ไม่พบเค้าร่าง',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'ไม่พบเค้าร่าง $1',
);

/** Turkmen (Türkmençe)
 * @author Hanberke
 */
$messages['tk'] = array(
	'indexpages' => 'Indeks sahypalarynyň sanawy',
	'pageswithoutscans' => 'Skansyz sahypalar',
	'proofreadpage_desc' => 'Original skanirleme bilen tekstiň aňsat deňedirilmegine rugsat berýär',
	'proofreadpage_image' => 'Surat',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => 'Säwlik: indekse garaşylýardy',
	'proofreadpage_nosuch_index' => 'Säwlik: beýle indeks ýok',
	'proofreadpage_nosuch_file' => 'Säwlik: beýle faýl ýok',
	'proofreadpage_badpage' => 'Ýalňyş format',
	'proofreadpage_badpagetext' => 'Ýazdyrjak bolan sahypaňyzyň formaty nädogry',
	'proofreadpage_indexdupe' => 'Dublikat çykgyt',
	'proofreadpage_indexdupetext' => 'Sahypalar bir indeks sahypasynda birden artykmaç sanawlanyp bilmeýär.',
	'proofreadpage_nologin' => 'Sessiýa açylmadyk',
	'proofreadpage_nologintext' => 'Sahypalryň okap düzetmek statusyny üýtgetmek üçin [[Special:UserLogin|sessiýa açmaly]].',
	'proofreadpage_notallowed' => 'Üýtgeşmä rugsat berilmeýär',
	'proofreadpage_notallowedtext' => 'Bu sahypanyň okap görmek statusyny üýtgetmäge rugsadyňyz ýok.',
	'proofreadpage_number_expected' => 'Säwlik: san bahasyna garaşylýar',
	'proofreadpage_interval_too_large' => 'Säwlik: aralyk örän giň',
	'proofreadpage_invalid_interval' => 'Säwlik: nädogry aralyk',
	'proofreadpage_nextpage' => 'Indiki sahypa',
	'proofreadpage_prevpage' => 'Öňki sahypa',
	'proofreadpage_header' => 'At (degişli däl):',
	'proofreadpage_body' => 'Sahypa göwresi (atanaklaýyn girizilmeli):',
	'proofreadpage_footer' => 'Futer (goşma):',
	'proofreadpage_toggleheaders' => 'degişli däl bölümleriň görkezilişini üýtget',
	'proofreadpage_quality0_category' => 'Tekstsiz',
	'proofreadpage_quality1_category' => 'Okalyp barlanmadyk',
	'proofreadpage_quality2_category' => 'Problemaly',
	'proofreadpage_quality3_category' => 'Okap barla',
	'proofreadpage_quality4_category' => 'Barlanan',
	'proofreadpage_quality0_message' => 'Bu sahypany okap barlamak gerek däl',
	'proofreadpage_quality1_message' => 'Bu sahypa okalyp barlanylmandyr',
	'proofreadpage_quality2_message' => 'Bu sahypa okalyp barlananda bir problema çykdy',
	'proofreadpage_quality3_message' => 'Bu sahypa okalyp barlandy',
	'proofreadpage_quality4_message' => 'Bu sahypa barlanan',
	'proofreadpage_index_listofpages' => 'Sahypalaryň sanawy',
	'proofreadpage_image_message' => 'Indeks sahypasyna çykgyt',
	'proofreadpage_page_status' => 'Sahypanyň statusy',
	'proofreadpage_js_attributes' => 'Awtor At Ýyl Neşirýat',
	'proofreadpage_index_attributes' => 'Awtor
At
Ýyl|Neşir edilen ýyly
Neşirýat
Çeşme
Surat|Sahap suraty
Sahypa||20
Bellikler||10',
	'proofreadpage_pages' => '{{PLURAL:$1|sahypa|sahypa}}', # Fuzzy
	'proofreadpage_specialpage_legend' => 'Indeks sahypalaryny gözle',
	'proofreadpage_source' => 'Çeşme',
	'proofreadpage_source_message' => 'Bu teksti döretmek üçin ulanylan skanirlenen wersiýa',
	'right-pagequality' => 'Sahypanyň hil baýdagyny üýtget',
);

/** Tagalog (Tagalog)
 * @author AnakngAraw
 * @author Sky Harbor
 */
$messages['tl'] = array(
	'indexpages' => 'Talaan ng mga pahina ng talatuntunan',
	'pageswithoutscans' => 'Mga pahinang walang mga saliksik',
	'proofreadpage_desc' => 'Pahintulutan ang madaling paghahambing ng teksto sa orihinal na kuha (iskan) ng larawan',
	'proofreadpage_image' => 'Larawan',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_index_expected' => 'Kamalian: inaasahan ang talatuntunan',
	'proofreadpage_nosuch_index' => 'Kamalian: walang ganyang talatuntunan',
	'proofreadpage_nosuch_file' => 'Kamalian: walang ganyang talaksan',
	'proofreadpage_badpage' => 'Maling Anyo',
	'proofreadpage_badpagetext' => 'Mali ang anyo ng pahinang sinubok mong sagipin.',
	'proofreadpage_indexdupe' => 'Katulad na kawing',
	'proofreadpage_indexdupetext' => 'Hindi maaaring itala ang mga pahina nang higit sa isa sa ibabaw ng pahina ng talatuntunan.',
	'proofreadpage_nologin' => 'Hindi nakalagda',
	'proofreadpage_nologintext' => 'Dapat kang [[Special:UserLogin|nakalagda]] upang mabago ang katayuan ng pagwawasto ng mga pahina.',
	'proofreadpage_notallowed' => 'Hindi pinapayagan ang pagbabago',
	'proofreadpage_notallowedtext' => 'Hindi ka pinahihintulutang magbago ng katayuan ng pagwawasto ng pahinang ito.',
	'proofreadpage_dataconfig_badformatted' => 'Kamalian sa kaayusan ng datos.',
	'proofreadpage_number_expected' => 'Kamalian: inaasahan ang halagang maka-bilang',
	'proofreadpage_interval_too_large' => 'Kamalian: napakalaki ng agwat',
	'proofreadpage_invalid_interval' => 'Kamalian: hindi tanggap na agwat',
	'proofreadpage_nextpage' => 'Susunod na pahina',
	'proofreadpage_prevpage' => 'Sinundang pahina',
	'proofreadpage_header' => 'Paulo (huwagisama):',
	'proofreadpage_body' => 'Katawan ng pahina (ililipat-sama):',
	'proofreadpage_footer' => 'Talababa (huwagisama):',
	'proofreadpage_toggleheaders' => 'pindutin-palitan huwagibilang mga seksyon antas ng pagkanatatanaw',
	'proofreadpage_quality0_category' => 'Walang teksto',
	'proofreadpage_quality1_category' => 'Hindi pa nababasa, napaghahambing, at naiwawasto ang mga mali',
	'proofreadpage_quality2_category' => 'May suliranin',
	'proofreadpage_quality3_category' => 'Basahin, paghambingin, at magwasto ng kamalian',
	'proofreadpage_quality4_category' => 'Napatotohanan na',
	'proofreadpage_quality0_message' => 'Hindi kailangang basahin at iwasto ang pahinang ito',
	'proofreadpage_quality1_message' => 'Hindi pa nababasa at naiwawasto ang pahinang ito',
	'proofreadpage_quality2_message' => 'Nagkaroon ng isang sularin habang iwinawasto ang pahinang ito',
	'proofreadpage_quality3_message' => 'Nabasa at naiwasto na ang pahinang ito',
	'proofreadpage_quality4_message' => 'Napatunayan na ang pahinang ito',
	'proofreadpage_index_status' => 'Katayuan ng talatuntunan',
	'proofreadpage_index_size' => 'Bilang ng mga pahina',
	'proofreadpage_specialpage_label_orderby' => 'Pagkakaayos ayon sa:',
	'proofreadpage_specialpage_label_key' => 'Maghanap:',
	'proofreadpage_specialpage_label_sortascending' => 'Pagsunud-sunurin na tumataas',
	'proofreadpage_alphabeticalorder' => 'Pagkakaayos na pang-alpabeto',
	'proofreadpage_index_listofpages' => 'Talaan ng mga pahina',
	'proofreadpage_image_message' => 'Kawing patungo sa pahina ng pagpapaksa (indeks)',
	'proofreadpage_page_status' => 'Kalagayan ng pahina',
	'proofreadpage_js_attributes' => 'May-akda Pamagat Taon Tapaglathala',
	'proofreadpage_index_attributes' => 'May-akda
Pamagat
Taon|Taon ng paglalathala
Tagapaglathala
Pinagmulan
Larawan|Pabalat na larawan
Mga pahina||20
Mga puna||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pahina|mga pahina}}',
	'proofreadpage_specialpage_legend' => 'Maghanap sa mga pahina ng talatuntunan',
	'proofreadpage_specialpage_searcherror' => 'Kamalian sa loob ng makinang panghanap',
	'proofreadpage_specialpage_searcherrortext' => 'Hindi gumagana ang makinang panghanap. Pasensiya na sa abala.',
	'proofreadpage_source' => 'Pinagmulan',
	'proofreadpage_source_message' => 'Edisyong nasiyasat na ginamit upang maitatag ang tekstong ito',
	'right-pagequality' => 'Baguhin ang watawat na pangkalidad ng pahina',
	'proofreadpage-section-tools' => 'Mga kasangkapang pangwasto',
	'proofreadpage-group-zoom' => 'Lumapit',
	'proofreadpage-group-other' => 'Iba pa',
	'proofreadpage-button-toggle-visibility-label' => 'Ipakita/itago ang paulo at pampaa ng pahinang ito',
	'proofreadpage-button-zoom-out-label' => 'Lumayong paatras',
	'proofreadpage-button-reset-zoom-label' => 'Orihinal na sukat',
	'proofreadpage-button-zoom-in-label' => 'Lumapit at tumutok',
	'proofreadpage-button-toggle-layout-label' => 'Kalatagang patindig/pahalang',
	'proofreadpage-preferences-showheaders-label' => 'Ipakita ang mga kahanayan ng paulo at pampaanan kapag namamatnugot sa loob ng puwang ng pangalan ng {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Gamitin ang kalatagang pahalang kapag namamatnugot sa loob ng puwang ng pangalan ng {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Metadatos ng mga aklat mula sa {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadatos ng mga aklat na pinamamahala ng ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Hindi mahanap ang eskema',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Hindi mahanap ang mga eskemang $1.',
);

/** толышә зывон (толышә зывон)
 * @author Erdemaslancan
 */
$messages['tly'] = array(
	'proofreadpage_image' => 'Шикил',
	'proofreadpage_index' => 'Индекс',
	'proofreadpage_nextpage' => 'Думотоно шә сәһифә',
	'proofreadpage_specialpage_label_key' => 'Нәве',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|бајт|бајтон}}',
	'proofreadpage_source' => 'Сәвон',
	'proofreadpage-group-zoom' => 'Мигјос',
	'proofreadpage-group-other' => 'ҹо',
	'proofreadpage-button-reset-zoom-label' => 'Сыфтәнә памјә',
);

/** Turkish (Türkçe)
 * @author Erdemaslancan
 * @author Erkan Yilmaz
 * @author Joseph
 * @author Mach
 * @author Runningfridgesrule
 * @author Suelnur
 * @author Vito Genovese
 */
$messages['tr'] = array(
	'indexpages' => 'Endeks sayfalarının listesi',
	'pageswithoutscans' => 'Sayfa içinde arama',
	'proofreadpage_desc' => 'Orijinal taramayla metnin kolayca karşılaştırılmasına izin verir',
	'proofreadpage_image' => 'Resim',
	'proofreadpage_index' => 'Dizin',
	'proofreadpage_index_expected' => 'Hata: dizin bekleniyordu',
	'proofreadpage_nosuch_index' => 'Hata: böyle bir dizin yok',
	'proofreadpage_nosuch_file' => 'Hata: Böyle bir dosya yok',
	'proofreadpage_badpage' => 'Yanlış Biçim',
	'proofreadpage_badpagetext' => 'Kaydetmeye çalıştığınız sayfanın biçimi yanlış.',
	'proofreadpage_indexdupe' => 'Yinelenen bağlantı',
	'proofreadpage_indexdupetext' => 'Bir dizin sayfasında, sayfalar birden fazla listelenemez.',
	'proofreadpage_nologin' => 'Giriş yapılmamış',
	'proofreadpage_nologintext' => 'Sayfaların düzeltme durumunu değiştirmek için [[Special:UserLogin|giriş yapmış]] olmalısınız.',
	'proofreadpage_notallowed' => 'Değişikliğe izin verilmiyor',
	'proofreadpage_notallowedtext' => 'Bu sayfanın düzeltme durumunu değiştirmenize izin verilmiyor.',
	'proofreadpage_number_expected' => 'Hata: sayısal değer bekleniyordu',
	'proofreadpage_interval_too_large' => 'Hata: aralık çok büyük',
	'proofreadpage_invalid_interval' => 'Hata: geçersiz aralık',
	'proofreadpage_nextpage' => 'Gelecek sayfa',
	'proofreadpage_prevpage' => 'Önceki sayfa',
	'proofreadpage_header' => 'Başlık (içerme):',
	'proofreadpage_body' => 'Sayfa gövdesi (çapraz eklenecek):',
	'proofreadpage_footer' => 'Alt bilgi (içerme):',
	'proofreadpage_toggleheaders' => 'içerilmeyen bölümlerinin görünürlüğünü değiştir',
	'proofreadpage_quality0_category' => 'Metinsiz',
	'proofreadpage_quality1_category' => 'Düzeltilmemiş',
	'proofreadpage_quality2_category' => 'Sorunlu',
	'proofreadpage_quality3_category' => 'Düzelt',
	'proofreadpage_quality4_category' => 'Doğrulanmış',
	'proofreadpage_quality0_message' => 'Bu sayfada düzeltme yapılması gerekmez',
	'proofreadpage_quality1_message' => 'Bu sayfada düzeltme yapılmadı',
	'proofreadpage_quality2_message' => 'Bu sayfada düzeltme yapılırken bir sorun oluştu',
	'proofreadpage_quality3_message' => 'Bu sayfada düzeltme yapıldı',
	'proofreadpage_quality4_message' => 'Bu sayfa doğrulanmış',
	'proofreadpage_index_status' => 'Dizin durumu',
	'proofreadpage_index_size' => 'Sayfa sayısı',
	'proofreadpage_specialpage_label_key' => 'Ara:',
	'proofreadpage_specialpage_label_sortascending' => 'Artan sıralama',
	'proofreadpage_alphabeticalorder' => 'Alfabetik sıraya göre',
	'proofreadpage_index_listofpages' => 'Sayfalar listesi',
	'proofreadpage_image_message' => 'Endeks sayfasına bağlantı',
	'proofreadpage_page_status' => 'Sayfa durumu',
	'proofreadpage_js_attributes' => 'Yazar Başlık Yıl Yayımcı',
	'proofreadpage_index_attributes' => 'Yazar
Başlık
Yıl|Yayım yılı
Yayımcı
Kaynak
Resim|Kapak resmi
Sayfalar||20
Açıklamalar||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|sayfa|sayfa}}',
	'proofreadpage_specialpage_legend' => 'Dizin sayfalarını ara',
	'proofreadpage_specialpage_searcherror' => 'Arma motoru hatası',
	'proofreadpage_specialpage_searcherrortext' => 'Arama motoru çalışmıyor. Verdiğimiz rahatsızlıktan dolayı özür dileriz.',
	'proofreadpage_source' => 'Kaynak',
	'proofreadpage_source_message' => 'Bu metni oluşturmak için kullanılan taranmış sürüm',
	'proofreadpage-section-tools' => 'Redaksiyon araçları',
	'proofreadpage-group-zoom' => 'Yakınlaştır',
	'proofreadpage-group-other' => 'Diğer',
	'proofreadpage-button-toggle-visibility-label' => 'Bu sayfanın üstbilgisii ve altbilgisini göster/gizle',
	'proofreadpage-button-zoom-out-label' => 'Uzaklaştır',
	'proofreadpage-button-reset-zoom-label' => 'Özgün boyut',
	'proofreadpage-button-zoom-in-label' => 'Yakınlaştır',
	'proofreadpage-button-toggle-layout-label' => 'Yatay/dikey düzen',
	'proofreadpage-preferences-showheaders-label' => '{{Ns:page}} ad alanında düzenlerken üstbilgi ve altbilgi alanlarını göster',
	'proofreadpage-preferences-horizontal-layout-label' => '{{Ns:page}} ad alanında düzenlerken yatay düzen kullan:',
);

/** Tsonga (Xitsonga)
 * @author Thuvack
 */
$messages['ts'] = array(
	'proofreadpage_namespace' => 'Tluka',
	'proofreadpage_index_namespace' => 'Nxaxamelo',
);

/** Tatar (Cyrillic script) (татарча)
 * @author Timming
 */
$messages['tt-cyrl'] = array(
	'proofreadpage_nextpage' => 'алдагы бит',
);

/** Tuvinian (тыва дыл)
 * @author Agilight
 * @author Sborsody
 */
$messages['tyv'] = array(
	'proofreadpage_image' => 'Чурумал',
	'proofreadpage_index' => 'Индекс',
	'proofreadpage_badpage' => 'Меге формат',
	'proofreadpage_nextpage' => 'Соонда арын',
	'proofreadpage_prevpage' => 'Бурунгу арын',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|арын|арын}}',
);

/** Central Atlas Tamazight (ⵜⴰⵎⴰⵣⵉⵖⵜ)
 * @author Tifinaghes
 */
$messages['tzm'] = array(
	'proofreadpage_image' => 'ⵜⴰⵡⵍⴰⴼⵜ',
	'proofreadpage_page_status' => 'ⴰⴷⴷⴰⴷ ⵏ ⵜⴰⵙⵏⴰ',
	'proofreadpage_source' => 'ⴰⵖⴱⴰⵍⵓ',
	'proofreadpage-section-tools' => 'ⵉⵎⴰⵙⵙⵏ ⵓⵙⵉⵙⴷⵉⴷ',
);

/** Uyghur (Arabic script) (ئۇيغۇرچە)
 * @author Sahran
 */
$messages['ug-arab'] = array(
	'proofreadpage_image' => 'سۈرەت',
	'proofreadpage_index' => 'مۇندەرىجە',
	'proofreadpage_nologin' => 'تىزىمغا كىرمىدى',
	'proofreadpage_nextpage' => 'كېيىنكى بەت',
	'proofreadpage_prevpage' => 'ئالدىنقى بەت',
	'proofreadpage_quality3_category' => 'تۈزەت',
	'proofreadpage_specialpage_label_orderby' => 'تەرتىپى:',
	'proofreadpage_specialpage_label_key' => 'ئىزدە:',
	'proofreadpage_specialpage_label_sortascending' => 'ئۆسكۈچى تەرتىپ',
	'proofreadpage_source' => 'مەنبە',
	'proofreadpage-group-zoom' => 'كېڭەيت-تارايت',
	'proofreadpage-group-other' => 'باشقا',
	'proofreadpage-button-zoom-out-label' => 'كىچىكلەت',
	'proofreadpage-button-reset-zoom-label' => 'ئەسلى چوڭلۇق',
	'proofreadpage-button-zoom-in-label' => 'چوڭايت',
);

/** Uyghur (Latin script) (Uyghurche)
 * @author Jose77
 */
$messages['ug-latn'] = array(
	'proofreadpage_nextpage' => 'Kéyinki bet',
	'proofreadpage_prevpage' => 'Aldinqi bet',
);

/** Ukrainian (українська)
 * @author AS
 * @author Ahonc
 * @author Andriykopanytsia
 * @author AtUkr
 * @author Base
 * @author Dim Grits
 * @author DixonD
 * @author Prima klasy4na
 * @author Steve.rusyn
 * @author SteveR
 * @author Тест
 */
$messages['uk'] = array(
	'indexpages' => 'Список індексових сторінок',
	'pageswithoutscans' => 'Сторінки без сканувань',
	'proofreadpage_desc' => 'Дозволяє легко порівнювати текст і відскановане зображення оригіналу',
	'proofreadpage_image' => 'зображення',
	'proofreadpage_index' => 'Індекс',
	'proofreadpage_index_expected' => 'Помилка: очікувано індексу.',
	'proofreadpage_nosuch_index' => 'Помилка: нема такого індексу',
	'proofreadpage_nosuch_file' => 'Помилка: нема такого файлу',
	'proofreadpage_badpage' => 'Неправильний формат',
	'proofreadpage_badpagetext' => 'Формат сторінки, яку ви хочете зберегти, неправильний.',
	'proofreadpage_indexdupe' => 'Посилання-дублікат',
	'proofreadpage_indexdupetext' => 'Сторінки не можуть бути перелічені в списку на сторінці індексації більше одного разу.',
	'proofreadpage_nologin' => 'Не виконаний вхід',
	'proofreadpage_nologintext' => 'Ви повинні [[Special:UserLogin|увійти в систему]], щоб змінити статус коректури сторінок.',
	'proofreadpage_notallowed' => 'Зміна не дозволена',
	'proofreadpage_notallowedtext' => 'Ви не можете змінити статус коректури цієї сторінки.',
	'proofreadpage_dataconfig_badformatted' => 'Помилка в конфігураціях даних',
	'proofreadpage_dataconfig_badformattedtext' => 'Сторінка [[Mediawiki:Proofreadpage index data config]] не добре відформатована у JSON.',
	'proofreadpage_number_expected' => 'Помилка: потрібне числове значення',
	'proofreadpage_interval_too_large' => 'Помилка: інтервал занадто великий',
	'proofreadpage_invalid_interval' => 'Помилка: неправильній інтервал',
	'proofreadpage_nextpage' => 'Наступна сторінка',
	'proofreadpage_prevpage' => 'Попередня сторінка',
	'proofreadpage_header' => 'Заголовок (не включається):',
	'proofreadpage_body' => 'Тіло сторінки (буде включатися):',
	'proofreadpage_footer' => 'Нижній колонтитул (не включається):',
	'proofreadpage_toggleheaders' => 'показувати невключені розділи',
	'proofreadpage_quality0_category' => 'Без тексту',
	'proofreadpage_quality1_category' => 'Не вичитано',
	'proofreadpage_quality2_category' => 'Проблематична',
	'proofreadpage_quality3_category' => 'Вичитана',
	'proofreadpage_quality4_category' => 'Перевірена',
	'proofreadpage_quality0_message' => 'Ця сторінка не потребує коректури',
	'proofreadpage_quality1_message' => 'Ця сторінка ще не пройшла коректури',
	'proofreadpage_quality2_message' => 'Виникла проблема з коректурою цієї сторінки',
	'proofreadpage_quality3_message' => 'Ця сторінка пройшла коректуру',
	'proofreadpage_quality4_message' => 'Ця сторінка була затверджена',
	'proofreadpage_index_status' => 'Статус індексу',
	'proofreadpage_index_size' => 'Число сторінок',
	'proofreadpage_specialpage_label_orderby' => 'Сортувати за:',
	'proofreadpage_specialpage_label_key' => 'Пошук:',
	'proofreadpage_specialpage_label_sortascending' => 'Сортувати за зростанням',
	'proofreadpage_alphabeticalorder' => 'За алфавітом',
	'proofreadpage_index_listofpages' => 'Список сторінок',
	'proofreadpage_image_message' => 'Посилання на сторінку індексу',
	'proofreadpage_page_status' => 'Стан сторінки',
	'proofreadpage_js_attributes' => 'Автор Назва Рік Видавництво',
	'proofreadpage_index_attributes' => 'Автор
Назва
Рік|Рік видання
Видавництво
Джерело
Зображення|Зображення обкладинки
Сторінок||20
Приміток||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|сторінка|сторінки|сторінок}}',
	'proofreadpage_specialpage_legend' => 'Пошук сторінок індексації',
	'proofreadpage_specialpage_searcherror' => 'Помилка в пошуковій системі',
	'proofreadpage_specialpage_searcherrortext' => 'Пошукова система не працює. Вибачте за незручності.',
	'proofreadpage_source' => 'Джерело',
	'proofreadpage_source_message' => 'Для створення цього тексту використані відскановані видання',
	'right-pagequality' => 'Змінювати статус якості сторінки',
	'proofreadpage-section-tools' => 'Інструменти коректури',
	'proofreadpage-group-zoom' => 'Масштаб',
	'proofreadpage-group-other' => 'Інше',
	'proofreadpage-button-toggle-visibility-label' => 'Показати / сховати верхні та нижні колонтитули цієї сторінки',
	'proofreadpage-button-zoom-out-label' => 'Зменшити',
	'proofreadpage-button-reset-zoom-label' => 'Скинути збільшення',
	'proofreadpage-button-zoom-in-label' => 'Збільшити',
	'proofreadpage-button-toggle-layout-label' => 'Вертикальна / горизонтальна розмітка',
	'proofreadpage-preferences-showheaders-label' => 'Показувати поля верхнього і нижнього колонтитулів при редагуванні в просторі імен {{ns:page}}.',
	'proofreadpage-preferences-horizontal-layout-label' => 'Використовувати горизонтальну розкладку при редагуванні в просторі імен {{ns:page}}.',
	'proofreadpage-indexoai-repositoryName' => 'Метадані книг з {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Метадані книг, підтримуваних ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Схеми не знайдено',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Схему $1 не знайдено.',
	'proofreadpage-disambiguationspage' => 'Template:неоднозначність',
);

/** Urdu (اردو)
 * @author පසිඳු කාවින්ද
 */
$messages['ur'] = array(
	'indexpages' => 'فہرست صفحات کی فہرست',
	'proofreadpage_image' => 'تصویر',
	'proofreadpage_index' => 'فہرست',
	'proofreadpage_badpage' => 'غلط کی شکل',
	'proofreadpage_nologin' => 'لاگ ان نہیں',
	'proofreadpage_nextpage' => 'اگلا صفحہ',
	'proofreadpage_prevpage' => 'سابق صفحہ',
	'proofreadpage_quality0_category' => 'ٹیکسٹ کے بغیر',
	'proofreadpage_quality4_category' => 'پاتی',
	'proofreadpage_specialpage_label_key' => 'ڈھونڈو:',
	'proofreadpage_specialpage_legend' => 'فہرست صفحات تلاش کریں',
	'proofreadpage_source' => 'ماخذ',
	'proofreadpage-group-zoom' => 'زوم',
	'proofreadpage-group-other' => 'دیگر',
	'proofreadpage-button-reset-zoom-label' => 'اصل سائز',
);

/** Uzbek (oʻzbekcha)
 * @author CoderSI
 */
$messages['uz'] = array(
	'proofreadpage_quality1_category' => 'Tuzatilmadi',
	'proofreadpage_quality3_category' => "Ko'zdan kechirildi",
	'proofreadpage-section-tools' => 'Tuzatish asboblari',
	'proofreadpage-group-other' => 'Boshqa',
);

/** vèneto (vèneto)
 * @author Candalua
 * @author GatoSelvadego
 * @author Vajotwo
 */
$messages['vec'] = array(
	'indexpages' => 'Elenco de le pagine de indice',
	'pageswithoutscans' => 'Pagine sensa scansion',
	'proofreadpage_desc' => 'El parméte un fasile confronto tra un testo e la so scansion original',
	'proofreadpage_image' => 'Imagine',
	'proofreadpage_index' => 'Indice',
	'proofreadpage_index_expected' => 'Eròr: indice mancante',
	'proofreadpage_nosuch_index' => "Eròr: sto indice no'l xe presente",
	'proofreadpage_nosuch_file' => 'Eròr: file mia catà',
	'proofreadpage_badpage' => 'Formato sbalià',
	'proofreadpage_badpagetext' => "El formato de la pagina che te ghe sercà de salvar no'l xe giusto.",
	'proofreadpage_indexdupe' => 'Colegamento dopio',
	'proofreadpage_indexdupetext' => 'Le pagine no se pol elencarle pi de na olta su na pagina de indice.',
	'proofreadpage_nologin' => 'Acesso mia efetuà',
	'proofreadpage_nologintext' => 'Te ghè da èssar [[Special:UserLogin|autenticà]] par canbiar el stato de revision de le pagine.',
	'proofreadpage_notallowed' => 'Canbiamento mia parmesso',
	'proofreadpage_notallowedtext' => 'No te ghè el parmesso de canbiar el stato de revision de le pagine.',
	'proofreadpage_dataconfig_badformatted' => 'Problema inte ła configurasion de i dati',
	'proofreadpage_dataconfig_badformattedtext' => 'Ła pàjina [[Mediawiki:Proofreadpage index data config]] nó ła xe inte un formà JSON coreto.',
	'proofreadpage_number_expected' => 'Eròr: me spetavo un valor numerico',
	'proofreadpage_interval_too_large' => 'Eròr: intervalo massa grando',
	'proofreadpage_invalid_interval' => 'Eròr: intervalo mia valido',
	'proofreadpage_nextpage' => 'Pagina sucessiva',
	'proofreadpage_prevpage' => 'Pagina precedente',
	'proofreadpage_header' => 'Intestazion (mìa inclusa):',
	'proofreadpage_body' => 'Corpo de la pagina (da inclùdar):',
	'proofreadpage_footer' => 'Pié de pagina (mìa incluso)',
	'proofreadpage_toggleheaders' => 'ativa/disativa la visibilità de le sezioni mìa incluse',
	'proofreadpage_quality0_category' => 'Pagine sensa testo',
	'proofreadpage_quality1_category' => 'Pagine da trascrivar',
	'proofreadpage_quality2_category' => 'Pagine da sistemar',
	'proofreadpage_quality3_category' => 'Pagine trascrite',
	'proofreadpage_quality4_category' => 'Pagine rilete',
	'proofreadpage_quality0_message' => 'Sta pagina no ghe xe bisogno de trascrìvarla',
	'proofreadpage_quality1_message' => "Sta pagina no la xe stà gnancora trascrita da l'originale",
	'proofreadpage_quality2_message' => 'Sta pagina la xe stà trascrita, ma no la xe gnancora a posto del tuto',
	'proofreadpage_quality3_message' => "Sta pagina la xe stà trascrita da l'originale",
	'proofreadpage_quality4_message' => 'Sta pagina la xe stà verificà da almanco do utenti',
	'proofreadpage_index_status' => 'Stato avansamento',
	'proofreadpage_index_size' => 'Nùmaro de pàjine',
	'proofreadpage_specialpage_label_orderby' => 'Ordena par:',
	'proofreadpage_specialpage_label_key' => 'Riserca:',
	'proofreadpage_specialpage_label_sortascending' => 'Ordinamento cresente',
	'proofreadpage_alphabeticalorder' => 'Ordine alfabetego',
	'proofreadpage_index_listofpages' => 'Lista de le pagine',
	'proofreadpage_image_message' => 'Colegamento a la pagina indice',
	'proofreadpage_page_status' => 'Qualità de la pagina',
	'proofreadpage_js_attributes' => 'Autor Titolo Ano Editor',
	'proofreadpage_index_attributes' => 'Autor
Titolo
Ano|Ano de pubblicazion
Editor
Fonte
Imagine|Imagine de copertina
Pagine||20
Note||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|pagina|pagine}}',
	'proofreadpage_specialpage_legend' => 'Serca in te le pagine de indice',
	'proofreadpage_specialpage_searcherror' => "Eror inte'l motor de riserca",
	'proofreadpage_specialpage_searcherrortext' => "El motor de riserca nó 'l funsiona. Se scuxemo pa'l incoveniente.",
	'proofreadpage_source' => 'Fonte',
	'proofreadpage_source_message' => 'Edission scanerizà doparà par inserir sto testo',
	'right-pagequality' => "Canbiar l'indicador de qualità de la pagina",
	'proofreadpage-section-tools' => 'Strumenti de riletura',
	'proofreadpage-group-zoom' => 'Zoom',
	'proofreadpage-group-other' => 'Altro',
	'proofreadpage-button-toggle-visibility-label' => 'Mostra/scondi intestazion e piè de pagina',
	'proofreadpage-button-zoom-out-label' => 'Zoom indrìo',
	'proofreadpage-button-reset-zoom-label' => 'Dimension orixenal',
	'proofreadpage-button-zoom-in-label' => 'Zoom avanti',
	'proofreadpage-button-toggle-layout-label' => 'Layout verticale/orizontale',
	'proofreadpage-preferences-showheaders-label' => "Mostra l'intestasion e 'l pie de pàjina durante ła modifega inte'l namespace {{ns:page}}",
	'proofreadpage-preferences-horizontal-layout-label' => "Dopara el layout orixontałe có te modifeghi inte'l namespace {{ns:page}}",
	'proofreadpage-indexoai-repositoryName' => 'Metadati de i libri da {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Metadati de i libri gestìi da ProofreadPage.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Schema mia catà',
	'proofreadpage-indexoai-error-schemanotfound-text' => "El schema $1 no'l xe stà catà.",
	'proofreadpage-disambiguationspage' => 'Template:Disambigua',
);

/** Veps (vepsän kel’)
 * @author Triple-ADHD-AS
 * @author Игорь Бродский
 */
$messages['vep'] = array(
	'proofreadpage_image' => 'Kuva',
	'proofreadpage_index' => 'Indeks',
	'proofreadpage_nosuch_index' => 'Petuz: ei ole mugošt indeksad',
	'proofreadpage_nosuch_file' => 'Petuz: ei ole mugošt failad',
	'proofreadpage_badpage' => 'Vär format',
	'proofreadpage_indexdupe' => 'Kaksitadud kosketuz',
	'proofreadpage_invalid_interval' => 'Petuz: vär interval',
	'proofreadpage_nextpage' => "Jäl'ghine lehtpol'",
	'proofreadpage_prevpage' => "Edeline lehtpol'",
	'proofreadpage_header' => 'Pälkirjutez (ei ele mülütadud)',
	'proofreadpage_body' => 'Lehtpolen tüvi (mülütadas):',
	'proofreadpage_quality0_category' => 'Tekstata',
	'proofreadpage_quality1_category' => 'Ei ole lugetud kodvaks',
	'proofreadpage_quality2_category' => 'Problematine',
	'proofreadpage_quality3_category' => 'Om lugetud kodvaks',
	'proofreadpage_quality4_category' => 'Kodvdud da hüvästadud',
	'proofreadpage_index_listofpages' => 'Lehtpoliden nimikirjutez',
	'proofreadpage_page_status' => 'Lehtpolen status',
	'proofreadpage_index_attributes' => "Avtor
Pälkirjutez
Voz'|Pästandvoz'
Pästai
Purde
Kuva|Kirjankoren kuva
Lehtpol't||20
Homaičendad||10",
);

/** Vietnamese (Tiếng Việt)
 * @author Minh Nguyen
 * @author Vinhtantran
 */
$messages['vi'] = array(
	'indexpages' => 'Danh sách các trang mục lục',
	'pageswithoutscans' => 'Trang không có hình quét',
	'proofreadpage_desc' => 'Cho phép dễ dàng so sánh văn bản với hình quét gốc',
	'proofreadpage_image' => 'Hình',
	'proofreadpage_index' => 'Mục lục',
	'proofreadpage_index_expected' => 'Lỗi: cần mục lục',
	'proofreadpage_nosuch_index' => 'Lỗi: không có mục lục như vậy',
	'proofreadpage_nosuch_file' => 'Lỗi: không có tập tin như vậy',
	'proofreadpage_badpage' => 'Định dạng sai',
	'proofreadpage_badpagetext' => 'Định dạng của trang bạn đang cố lưu là không đúng.',
	'proofreadpage_indexdupe' => 'Liên kết lặp lại',
	'proofreadpage_indexdupetext' => 'Không thể liệt kê trang quá một lần tại một trang mục lục.',
	'proofreadpage_nologin' => 'Chưa đăng nhập',
	'proofreadpage_nologintext' => 'Bạn phải [[Special:UserLogin|đăng nhập]] để sửa đổi tình trạng hiệu đính của trang.',
	'proofreadpage_notallowed' => 'Không được phép thay đổi',
	'proofreadpage_notallowedtext' => 'Bạn không được phép thay đổi tình trạng hiệu đính của trang này.',
	'proofreadpage_dataconfig_badformatted' => 'Lỗi trong cấu hình dữ liệu',
	'proofreadpage_dataconfig_badformattedtext' => 'Trang [[Mediawiki:Proofreadpage index data config]] không tuân theo định dạng JSON.',
	'proofreadpage_number_expected' => 'Lỗi: cần giá trị số',
	'proofreadpage_interval_too_large' => 'Lỗi: khoảng thời gian quá lớn',
	'proofreadpage_invalid_interval' => 'Lỗi: khoảng thời gian không hợp lệ',
	'proofreadpage_nextpage' => 'Trang sau',
	'proofreadpage_prevpage' => 'Trang trước',
	'proofreadpage_header' => 'Tiêu đề (noinclude):',
	'proofreadpage_body' => 'Nội dung trang (sẽ được nhúng vào):',
	'proofreadpage_footer' => 'Phần cuối (noinclude):',
	'proofreadpage_toggleheaders' => 'thay đổi độ khả kiến của đề mục noinclude',
	'proofreadpage_quality0_category' => 'Không có nội dung',
	'proofreadpage_quality1_category' => 'Chưa hiệu đính',
	'proofreadpage_quality2_category' => 'Có vấn đề',
	'proofreadpage_quality3_category' => 'Hiệu đính rồi',
	'proofreadpage_quality4_category' => 'Đã phê chuẩn',
	'proofreadpage_quality0_message' => 'Trang này không cần phải hiệu đính',
	'proofreadpage_quality1_message' => 'Trang này chưa được hiệu đính',
	'proofreadpage_quality2_message' => 'Có vấn đề khi hiệu đính trang này',
	'proofreadpage_quality3_message' => 'Trang này đã được duyệt lại',
	'proofreadpage_quality4_message' => 'Trang này đã được phê chuẩn',
	'proofreadpage_index_status' => 'Trạng thái chỉ mục',
	'proofreadpage_index_size' => 'Số trang',
	'proofreadpage_specialpage_label_orderby' => 'Sắp xếp theo:',
	'proofreadpage_specialpage_label_key' => 'Tìm kiếm:',
	'proofreadpage_specialpage_label_sortascending' => 'Sắp xếp tăng dần',
	'proofreadpage_alphabeticalorder' => 'Thứ tự chữ cái',
	'proofreadpage_index_listofpages' => 'Danh sách các trang',
	'proofreadpage_image_message' => 'Liên kết trang mục lục',
	'proofreadpage_page_status' => 'Tình trạng của trang',
	'proofreadpage_js_attributes' => 'Tác giả Tựa đề Năm Nhà xuất bản',
	'proofreadpage_index_attributes' => 'Tác giả
Tựa đề
Năm|Năm xuất bản
Nhà xuất bản
Nguồn
Hình|Hình bìa
Các trang||20
Ghi chú||10',
	'proofreadpage_pages' => '$2 {{PLURAL:$1}}trang',
	'proofreadpage_specialpage_legend' => 'Tìm kiếm trong các trang mục lục',
	'proofreadpage_specialpage_searcherror' => 'Lỗi trong công cụ tìm kiếm',
	'proofreadpage_specialpage_searcherrortext' => 'Công cụ tìm kiếm không hoạt động. Xin lỗi vì sự bất tiện này.',
	'proofreadpage_source' => 'Nguồn',
	'proofreadpage_source_message' => 'Bản quét được dùng để tạo ra văn bản này',
	'right-pagequality' => 'Sửa đổi chất lượng trang',
	'proofreadpage-section-tools' => 'Hiệu đính',
	'proofreadpage-group-zoom' => 'Thu phóng',
	'proofreadpage-group-other' => 'Khác',
	'proofreadpage-button-toggle-visibility-label' => 'Hiện/ẩn đầu và chân của trang này',
	'proofreadpage-button-zoom-out-label' => 'Thu nhỏ',
	'proofreadpage-button-reset-zoom-label' => 'Cỡ bình thường',
	'proofreadpage-button-zoom-in-label' => 'Phóng to',
	'proofreadpage-button-toggle-layout-label' => 'Đứng thẳng/ngang',
	'proofreadpage-preferences-showheaders-label' => 'Hiện các hộp đầu/chân khi sửa đổi trong không gian tên {{ns:page}}',
	'proofreadpage-preferences-horizontal-layout-label' => 'Bố trí ngang các trang sửa đổi trong không gian tên {{ns:page}}',
	'proofreadpage-indexoai-repositoryName' => 'Siêu dữ liệu sách {{SITENAME}}',
	'proofreadpage-indexoai-eprint-content-text' => 'Siêu dữ liệu các sách do ProofreadPage quản lý.',
	'proofreadpage-indexoai-error-schemanotfound' => 'Không tìm thấy lược đồ',
	'proofreadpage-indexoai-error-schemanotfound-text' => 'Không tìm thấy lược đồ $1.',
	'proofreadpage-disambiguationspage' => 'Template:Trang_định_hướng',
);

/** Volapük (Volapük)
 * @author Malafaya
 * @author Smeira
 */
$messages['vo'] = array(
	'proofreadpage_image' => 'Magod',
	'proofreadpage_nextpage' => 'Pad sököl',
	'proofreadpage_prevpage' => 'Pad büik',
	'proofreadpage_quality0_category' => 'Nen vödem',
	'proofreadpage_quality2_category' => 'Säkädik',
	'proofreadpage_index_listofpages' => 'Padalised',
	'proofreadpage_page_status' => 'Stad pada',
	'proofreadpage_js_attributes' => 'Lautan Tiäd Yel Püban',
	'proofreadpage_index_attributes' => 'Lautan
Tiäd
Yel|Pübayel
Püban
Fonät
Magod|Magod tegoda
Pads|20
Küpets|10',
	'proofreadpage_pages' => '{{PLURAL:$1|pad|pads}}', # Fuzzy
);

/** Yiddish (ייִדיש)
 * @author Imre
 * @author פוילישער
 */
$messages['yi'] = array(
	'proofreadpage_image' => 'בילד',
	'proofreadpage_index' => 'אינדעקס',
	'proofreadpage_nologin' => 'נישט אַרײַנלאגירט',
	'proofreadpage_nextpage' => 'קומענדיגער בלאַט',
	'proofreadpage_prevpage' => 'פֿריערדיגער בלאַט',
	'proofreadpage_quality0_category' => 'אן טעקסט',
	'proofreadpage_quality1_category' => 'קארעקטור נישט געליינט',
	'proofreadpage_quality2_category' => 'פראבלעמאטיש',
	'proofreadpage_quality3_category' => 'קארעקטור געליינט',
	'proofreadpage_pages' => '$2 {{PLURAL:$1|בלאַט|בלעטער}}',
	'proofreadpage_specialpage_legend' => 'זוכן אינדעקס־זײַטן',
	'proofreadpage_source' => 'מקור',
	'proofreadpage-group-zoom' => 'זום',
	'proofreadpage-group-other' => 'אַנדער',
	'proofreadpage-button-reset-zoom-label' => 'אריגינעלע גרייס',
);

/** Cantonese (粵語)
 */
$messages['yue'] = array(
	'proofreadpage_desc' => '容許簡易噉去比較原掃瞄同埋文字',
	'proofreadpage_image' => '圖像',
	'proofreadpage_index' => '索引',
	'proofreadpage_nextpage' => '下一版',
	'proofreadpage_prevpage' => '上一版',
	'proofreadpage_header' => '頭 (唔包含):',
	'proofreadpage_body' => '頁身 (去包含):',
	'proofreadpage_footer' => '尾 (唔包含):',
	'proofreadpage_toggleheaders' => '較唔包含小節可見性',
	'proofreadpage_quality1_category' => '未校對',
	'proofreadpage_quality2_category' => '有問題',
	'proofreadpage_quality3_category' => '已校對',
	'proofreadpage_quality4_category' => '已認證',
	'proofreadpage_index_listofpages' => '頁一覽',
	'proofreadpage_image_message' => '連到索引頁嘅連結',
	'proofreadpage_page_status' => '頁狀態',
	'proofreadpage_js_attributes' => '作者 標題 年份 出版者',
	'proofreadpage_index_attributes' => '作者
標題
年份|出版年份
出版者
來源
圖像|封面照
頁數||20
備註||10',
);

/** Simplified Chinese (中文（简体）‎)
 * @author Anakmalaysia
 * @author Gaoxuewei
 * @author Hydra
 * @author Jimmy xu wrk
 * @author Liangent
 * @author Mark85296341
 * @author PhiLiP
 * @author Shirayuki
 * @author Xiaomingyan
 * @author Yfdyh000
 * @author Zhuyifei1999
 */
$messages['zh-hans'] = array(
	'indexpages' => '索引页列表',
	'pageswithoutscans' => '没有扫描的页面',
	'proofreadpage_desc' => '允许简易地比较原始扫描稿和识别文本',
	'proofreadpage_image' => '图像',
	'proofreadpage_index' => '索引',
	'proofreadpage_index_expected' => '错误：需要索引',
	'proofreadpage_nosuch_index' => '错误：没有此类索引',
	'proofreadpage_nosuch_file' => '错误：没有这种文件',
	'proofreadpage_badpage' => '错误的格式',
	'proofreadpage_badpagetext' => '您试图保存的页面的格式不正确。',
	'proofreadpage_indexdupe' => '重复链接',
	'proofreadpage_indexdupetext' => '在索引页中，页面不会被重复列出。',
	'proofreadpage_nologin' => '未登录',
	'proofreadpage_nologintext' => '您必须[[Special:UserLogin|先登录]]才能修改页面的校对状态。',
	'proofreadpage_notallowed' => '不允许修改',
	'proofreadpage_notallowedtext' => '您没有获得修改这个页面校对状态的许可。',
	'proofreadpage_dataconfig_badformatted' => '数据配置错误',
	'proofreadpage_dataconfig_badformattedtext' => '[[Mediawiki:Proofreadpage index data config]]页面不是标准的JSON格式。',
	'proofreadpage_number_expected' => '错误：不为数值',
	'proofreadpage_interval_too_large' => '错误：间隔过大',
	'proofreadpage_invalid_interval' => '错误：无法识别间隔',
	'proofreadpage_nextpage' => '下一页',
	'proofreadpage_prevpage' => '上一页',
	'proofreadpage_header' => '首（不包含）：',
	'proofreadpage_body' => '页身 （包含）:',
	'proofreadpage_footer' => '尾 （不包含）:',
	'proofreadpage_toggleheaders' => '调整不包含段落之可见性',
	'proofreadpage_quality0_category' => '没有文字',
	'proofreadpage_quality1_category' => '未校对',
	'proofreadpage_quality2_category' => '有问题',
	'proofreadpage_quality3_category' => '已校对',
	'proofreadpage_quality4_category' => '已认证',
	'proofreadpage_quality0_message' => '本页面不需要校对',
	'proofreadpage_quality1_message' => '本页面还没有被校对',
	'proofreadpage_quality2_message' => '校对本页时出现了一个问题',
	'proofreadpage_quality3_message' => '本页已经被校对',
	'proofreadpage_quality4_message' => '本页已经被认证',
	'proofreadpage_index_status' => '索引状态',
	'proofreadpage_index_size' => '页数',
	'proofreadpage_specialpage_label_orderby' => '排序方式：',
	'proofreadpage_specialpage_label_key' => '搜索：',
	'proofreadpage_specialpage_label_sortascending' => '升序',
	'proofreadpage_alphabeticalorder' => '按字母顺序',
	'proofreadpage_index_listofpages' => '页面列表',
	'proofreadpage_image_message' => '连到索引页的链接',
	'proofreadpage_page_status' => '页面状态',
	'proofreadpage_js_attributes' => '作者 标题 年份 出版者',
	'proofreadpage_index_attributes' => '作者
标题
年份|出版年份
出版
来源
图像|封面图像
页数||20
评论||10',
	'proofreadpage_pages' => '$2个{{PLURAL:$1|页面|页面}}',
	'proofreadpage_specialpage_legend' => '搜索索引页',
	'proofreadpage_specialpage_searcherror' => '搜索引擎错误',
	'proofreadpage_specialpage_searcherrortext' => '搜索引擎无法工作。很抱歉给您带来的不便。',
	'proofreadpage_source' => '来源',
	'proofreadpage_source_message' => '扫描版用来建立这个文本',
	'right-pagequality' => '修改页面质量标志',
	'proofreadpage-section-tools' => '校对工具',
	'proofreadpage-group-zoom' => '缩放',
	'proofreadpage-group-other' => '其他',
	'proofreadpage-button-toggle-visibility-label' => '显示／隐藏此页的页眉和页脚',
	'proofreadpage-button-zoom-out-label' => '缩小',
	'proofreadpage-button-reset-zoom-label' => '重置显示比例',
	'proofreadpage-button-zoom-in-label' => '放大',
	'proofreadpage-button-toggle-layout-label' => '垂直／水平布局',
	'proofreadpage-preferences-showheaders-label' => '在{{ns:page}}命名空间编辑时显示页眉和页脚字段',
	'proofreadpage-preferences-horizontal-layout-label' => '在{{ns:page}}命名空间编辑时使用水平线',
	'proofreadpage-indexoai-repositoryName' => '来自{{SITENAME}}的书籍元数据',
	'proofreadpage-indexoai-eprint-content-text' => '由ProofreadPage管理书籍元数据。',
	'proofreadpage-indexoai-error-schemanotfound' => 'Schema未找到',
	'proofreadpage-indexoai-error-schemanotfound-text' => '$1架构未找到。',
	'proofreadpage-disambiguationspage' => 'Template:disambig',
);

/** Traditional Chinese (中文（繁體）‎)
 * @author Anakmalaysia
 * @author Gaoxuewei
 * @author Horacewai2
 * @author Liangent
 * @author Mark85296341
 * @author Shirayuki
 * @author TianyinLee
 * @author Wrightbus
 */
$messages['zh-hant'] = array(
	'indexpages' => '索引頁列表',
	'pageswithoutscans' => '沒有掃瞄的頁面',
	'proofreadpage_desc' => '容許簡易地去比較原掃瞄和文字',
	'proofreadpage_image' => '圖片',
	'proofreadpage_index' => '索引',
	'proofreadpage_index_expected' => '錯誤：需要索引',
	'proofreadpage_nosuch_index' => '錯誤：沒有此類索引',
	'proofreadpage_nosuch_file' => '錯誤：沒有這種文件',
	'proofreadpage_badpage' => '格式錯誤',
	'proofreadpage_badpagetext' => '您試圖儲存的頁面的格式不正確。',
	'proofreadpage_indexdupe' => '重複連結',
	'proofreadpage_indexdupetext' => '在索引頁中，頁面不會被重複列出。',
	'proofreadpage_nologin' => '未登入',
	'proofreadpage_nologintext' => '您必須[[Special:UserLogin|先登入]]才能修改頁面的校對狀態。',
	'proofreadpage_notallowed' => '不允許修改',
	'proofreadpage_notallowedtext' => '您沒有獲得修改這個頁面校對狀態的許可。',
	'proofreadpage_dataconfig_badformatted' => '設定數據中的Bug',
	'proofreadpage_number_expected' => '錯誤：不為數值',
	'proofreadpage_interval_too_large' => '錯誤：間隔過大',
	'proofreadpage_invalid_interval' => '錯誤：無法識別間隔',
	'proofreadpage_nextpage' => '下一頁',
	'proofreadpage_prevpage' => '上一頁',
	'proofreadpage_header' => '首（不包含）：',
	'proofreadpage_body' => '頁身（包含）：',
	'proofreadpage_footer' => '尾（不包含）：',
	'proofreadpage_toggleheaders' => '調整不包含段落之可見性',
	'proofreadpage_quality0_category' => '沒有文字',
	'proofreadpage_quality1_category' => '未校對',
	'proofreadpage_quality2_category' => '有問題',
	'proofreadpage_quality3_category' => '已校對',
	'proofreadpage_quality4_category' => '已認證',
	'proofreadpage_quality0_message' => '本頁不需要校對',
	'proofreadpage_quality1_message' => '本頁面尚未進行校對',
	'proofreadpage_quality2_message' => '校對本頁時出現了一個問題',
	'proofreadpage_quality3_message' => '本頁已經被校對',
	'proofreadpage_quality4_message' => '本頁已經被認證',
	'proofreadpage_specialpage_label_key' => '搜尋：',
	'proofreadpage_index_listofpages' => '頁面清單',
	'proofreadpage_image_message' => '連到索引頁的連結',
	'proofreadpage_page_status' => '頁面狀態',
	'proofreadpage_js_attributes' => '作者 標題 年份 出版者',
	'proofreadpage_index_attributes' => '作者
標題
年份|出版年份
出版者
來源
圖片|封面照
頁數||20
備註||10',
	'proofreadpage_pages' => '$2個{{PLURAL:$1|頁面|頁面}}',
	'proofreadpage_specialpage_legend' => '搜尋索引頁',
	'proofreadpage_source' => '來源',
	'proofreadpage_source_message' => '掃描版用來建立這個文字',
	'right-pagequality' => '修改頁面質量標誌',
	'proofreadpage-section-tools' => '校對工具',
	'proofreadpage-group-zoom' => '縮放',
	'proofreadpage-group-other' => '其他',
	'proofreadpage-button-toggle-visibility-label' => '顯示／隱藏此頁面的頁眉及頁腳',
	'proofreadpage-button-zoom-out-label' => '縮小',
	'proofreadpage-button-reset-zoom-label' => '原本大小',
	'proofreadpage-button-zoom-in-label' => '放大',
	'proofreadpage-button-toggle-layout-label' => '垂直／水平佈局',
);

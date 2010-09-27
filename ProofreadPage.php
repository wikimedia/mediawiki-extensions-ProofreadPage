<?php

# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "ProofreadPage extension\n" );
}

$wgExtensionFunctions[] = 'wfProofreadPage';

$dir = dirname( __FILE__ ) . '/';
$wgExtensionMessagesFiles['ProofreadPage'] = $dir . 'ProofreadPage.i18n.php';
$wgExtensionAliasesFiles['ProofreadPage'] = $dir . 'ProofreadPage.alias.php';

$wgAutoloadClasses['ProofreadPage'] = $dir . 'ProofreadPage_body.php';

$wgExtensionCredits['other'][] = array(
	'path'           => __FILE__,
	'name'           => 'ProofreadPage',
	'author'         => 'ThomasV',
	'version'        => '2010-09-17',
	'url'            => 'http://www.mediawiki.org/wiki/Extension:Proofread_Page',
	'descriptionmsg' => 'proofreadpage_desc',
);

# special page
$wgAutoloadClasses['ProofreadPages'] = $dir . 'SpecialProofreadPages.php';
$wgSpecialPages['IndexPages'] = 'ProofreadPages';
$wgSpecialPageGroups['IndexPages'] = 'pages';

# special page
$wgAutoloadClasses['PagesWithoutScans'] = $dir . 'SpecialPagesWithoutScans.php';
$wgSpecialPages['PagesWithoutScans'] = 'PagesWithoutScans';
$wgSpecialPageGroups['PagesWithoutScans'] = 'maintenance';

# Group allowed to modify pagequality
$wgGroupPermissions['user']['pagequality'] = true;

function wfProofreadPage() {
	new ProofreadPage;
	return true;
}

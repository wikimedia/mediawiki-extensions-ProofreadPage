<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 */

class ProofreadPageParser {

	/**
	 * Try to parse a page.
	 * Return quality status of the page and username of the proofreader
	 * Return -1 if the page cannot be parsed
	 */
	public static function parsePage( $text, $title ) {
		global $wgUser;

		$username = $wgUser->getName();
		$page_regexp = "/^<noinclude>(.*?)<\/noinclude>(.*?)<noinclude>(.*?)<\/noinclude>$/s";
		if( !preg_match( $page_regexp, $text, $m ) ) {
			ProofreadPage::loadIndex( $title );
			if ( $title->prpIndexPage !== null ) {
				list( $header, $footer, $css, $editWidth ) = $title->prpIndexPage->getIndexDataForPage( $title );
			} else {
				$header = '';
				$footer = '';
			}
			$new_text = "<noinclude><pagequality level=\"1\" user=\"$username\" /><div class=\"pagetext\">"
				."$header\n\n\n</noinclude>$text<noinclude>\n$footer</div></noinclude>";
			return array( -1, null, $new_text );
		}

		$header = $m[1];
		$body = $m[2];
		$footer = $m[3];

		$header_regexp = "/^<pagequality level=\"(0|1|2|3|4)\" user=\"(.*?)\" \/>/";
		if( preg_match( $header_regexp, $header, $m2 ) ) {
			return array( intval($m2[1]), $m2[2], null );
		}

		$old_header_regexp = "/^\{\{PageQuality\|(0|1|2|3|4)(|\|(.*?))\}\}/is";
		if( preg_match( $old_header_regexp, $header, $m3 ) ) {
			return array( intval($m3[1]), $m3[3], null );
		}

		$new_text = "<noinclude><pagequality level=\"1\" user=\"$username\" />"
			. "$header\n\n\n</noinclude>$body<noinclude>\n$footer</noinclude>";
		return array( -1, null, $new_text );
	}
}
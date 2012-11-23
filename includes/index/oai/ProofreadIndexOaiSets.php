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
 * The content of this file use some code from OAIRepo Mediawiki extension.
 *
 * @file
 */


/**
 * Manage sets-related features
 */
class ProofreadIndexOaiSets {

	/**
	 * @return array an associative array containing the sets with as key their identifier.
	 * This array is like:
	 * array(
	 * 	'test' => array(
	 *		'spec' => 'test', //The set spec
	 *		'name' => 'Test', //The set name
	 *		'category' => 'tests_list', //The category to use, without the "Category:" prefix
	 *		'description' => 'A test set.' //Description of the set, optional
	 *		)
	 * );
	 */
	public static function getSetsBySpec() {
		static $setsBySpec = null;
		if ( $setsBySpec !== null ) {
			return $setsBySpec;
		}

		$data = wfMessage( 'proofreadpage_index_oai_sets' )->inContentLanguage();
		if ( !$data->exists() || $data->plain() == '' ) {
			$setsBySpec = array();
			return $setsBySpec;
		}
		$config = FormatJson::decode( $data->plain(), true );
		if ( $config === null ) {
			$setsBySpec = array();
			return $setsBySpec;
		}
		$setsBySpec = array();
		foreach( $config as $spec => $set ) {
			if ( !isset( $set['category'] ) || strstr( $spec, ':' ) !== false ) {
				continue;
			}
			if ( !isset( $set['name'] ) ) {
				$set['name'] = $set['category'];
			}
			$set['category'] = str_replace( ' ', '_', $set['category'] );
			$set['spec'] = $spec;
			$setsBySpec[$spec] = $set;
		}
		return $setsBySpec;
	}

	/**
	 * @return array an associative array containing the sets with as key the name of the category used.
	 * For the array structure see getSetsBySpec()
	 */
	public static function getSetsByCategory() {
		static $setsByCategory = null;
		if ( $setsByCategory !== null ) {
			return $setsByCategory;
		}

		$setsBySpec = self::getSetsBySpec();
		$setsByCategory = array();
		foreach( $setsBySpec as $set ) {
			$setsByCategory[$set['category']] = $set;
		}
		return $setsByCategory;
	}

	/**
	 * Returns if this repository has sets or not
	 * @return bool
	 */
	public static function withSets() {
		return self::getSetsBySpec() !== array();
	}

	/**
	 * Returns the list of the specs of sets that contain a page
	 * @param Title $title
	 * @return array
	 */
	public static function getSetSpecsForTitle( Title $title ) {
		if ( !self::withSets() ) {
			return array();
		}
		$sets = self::getSetsByCategory();
		$list = array();

		$db = wfGetDB( DB_SLAVE );
		$results = $db->select(
			array( 'page', 'categorylinks' ),
			array( 'cl_to' ),
			$title->pageCond() + array( 'cl_from = page_id' ),
			__METHOD__
		);

		foreach( $results as $result ) {
			if ( isset( $sets[$result->cl_to] ) ) {
				$list[] = $sets[$result->cl_to]['spec'];
			}
		}
		return $list;
	}

	/**
	 * Returns the name of the category for a set or null if the set does not exist.
	 * @param string $spec the set spec
	 * @return string|null
	 */
	public static function getCategoryForSpec( $spec ) {
		$sets = self::getSetsBySpec();
		if ( isset( $sets[$spec] ) ) {
			return $sets[$spec]['category'];
		} else {
			return null;
		}
	}
}

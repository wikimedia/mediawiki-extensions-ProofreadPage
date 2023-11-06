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
 * @file
 * @ingroup ProofreadPage
 */

namespace ProofreadPage;

use MediaWiki\Extension\Scribunto\Hooks\ScribuntoExternalLibrariesHook;
use MediaWiki\Extension\Scribunto\Hooks\ScribuntoExternalLibraryPathsHook;

/**
 * All hooks from the Scribunto extension which is optional to use with this extension.
 */
class ScribuntoHooks implements
	ScribuntoExternalLibrariesHook,
	ScribuntoExternalLibraryPathsHook
{
	/**
	 * Register our extra Lua libraries
	 * @param string $engine
	 * @param array &$extraLibraries the array to add to
	 */
	public function onScribuntoExternalLibraries( string $engine, array &$extraLibraries ): void {
		$extraLibraries['mw.ext.proofreadPage'] = ProofreadPageLuaLibrary::class;
	}

	/**
	 * Register the Scribunto external libraries for ProofreadPage
	 * @param string $engine the Lua engine
	 * @param array &$extraLibraryPaths An array of strings representing the
	 *                                   filesystem paths to library files.
	 *                                   Will be merged with core library paths.
	 */
	public function onScribuntoExternalLibraryPaths( string $engine, array &$extraLibraryPaths ): void {
		if ( $engine === 'lua' ) {
			// Path containing pure Lua libraries that don't need to interact with PHP
			$extraLibraryPaths[] = __DIR__ . '/lualib';
		}
	}

}

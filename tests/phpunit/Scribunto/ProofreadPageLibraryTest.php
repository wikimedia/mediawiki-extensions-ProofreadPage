<?php

namespace ProofreadPage\Scribunto;

use MediaWiki\Extension\Scribunto\Tests\Engines\LuaCommon\LuaEngineTestBase;

if ( !class_exists( LuaEngineTestBase::class ) ) {
	return;
}

/**
 * Tests for the genetic aspects of the ProofreadPage Lua libs
 *
 * @covers \ProofreadPage\ProofreadPageLuaLibrary
 */
class ProofreadPageLibraryTest extends LuaEngineTestBase {
	/** @inheritDoc */
	protected static $moduleName = 'ProofreadPageLibraryTests';

	/** @inheritDoc */
	protected function getTestModules() {
		return parent::getTestModules() + [
			'ProofreadPageLibraryTests' => __DIR__ . '/ProofreadPageLibraryTests.lua',
		];
	}
}

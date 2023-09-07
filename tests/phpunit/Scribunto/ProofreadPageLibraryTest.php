<?php

namespace ProofreadPage\Scribunto;

use Scribunto_LuaEngineTestBase;

/**
 * Tests for the genetic aspects of the ProofreadPage Lua libs
 *
 * @covers \ProofreadPage\ProofreadPageLuaLibrary
 */
class ProofreadPageLibraryTest extends Scribunto_LuaEngineTestBase {
	/** @inheritDoc */
	protected static $moduleName = 'ProofreadPageLibraryTests';

	/** @inheritDoc */
	protected function getTestModules() {
		return parent::getTestModules() + [
			'ProofreadPageLibraryTests' => __DIR__ . '/ProofreadPageLibraryTests.lua',
		];
	}
}

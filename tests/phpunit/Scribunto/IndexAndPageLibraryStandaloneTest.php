<?php

namespace ProofreadPage\Scribunto;

if ( !class_exists( IndexAndPageLibraryTestBase::class ) ) {
	return;
}

/**
 * @covers \ProofreadPage\ProofreadPageLuaLibrary
 * @group Database
 * @group Lua
 * @group LuaStandalone
 * @group Standalone
 */
class IndexAndPageLibraryStandaloneTest extends IndexAndPageLibraryTestBase {
	protected function getEngineName(): string {
		return 'LuaStandalone';
	}
}

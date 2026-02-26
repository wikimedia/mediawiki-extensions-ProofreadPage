<?php

namespace ProofreadPage\Scribunto;

if ( !class_exists( ProofreadPageLibraryTestBase::class ) ) {
	return;
}

/**
 * @covers \ProofreadPage\ProofreadPageLuaLibrary
 * @group Lua
 * @group LuaStandalone
 */
class ProofreadPageLibraryStandaloneTest extends ProofreadPageLibraryTestBase {
	protected function getEngineName(): string {
		return 'LuaStandalone';
	}
}

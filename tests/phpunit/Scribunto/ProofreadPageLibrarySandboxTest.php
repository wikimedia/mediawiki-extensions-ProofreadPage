<?php

namespace ProofreadPage\Scribunto;

if ( !class_exists( ProofreadPageLibraryTestBase::class ) ) {
	return;
}

/**
 * @covers \ProofreadPage\ProofreadPageLuaLibrary
 * @group Lua
 * @group LuaSandbox
 */
class ProofreadPageLibrarySandboxTest extends ProofreadPageLibraryTestBase {
	protected function getEngineName(): string {
		return 'LuaSandbox';
	}
}

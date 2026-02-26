<?php

namespace ProofreadPage\Scribunto;

if ( !class_exists( IndexAndPageLibraryTestBase::class ) ) {
	return;
}

/**
 * @covers \ProofreadPage\ProofreadPageLuaLibrary
 * @group Database
 * @group Lua
 * @group LuaSandbox
 */
class IndexAndPageLibrarySandboxTest extends IndexAndPageLibraryTestBase {
	protected function getEngineName(): string {
		return 'LuaSandbox';
	}
}

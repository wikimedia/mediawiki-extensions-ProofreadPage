<?php

namespace ProofreadPage;

use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\DiffFormatterUtils
 */
class DiffFormatterUtilsTest extends ProofreadPageTestCase {

	/**
	 * @var DiffFormatterUtils
	 */
	protected $diffFormatterUtils;

	public function setUp() : void {
		parent::setUp();

		$this->diffFormatterUtils = new DiffFormatterUtils();
	}

	public function testCreateHeader() {
		$this->assertSame(
			'<tr><td colspan="2" class="diff-lineno">Test</td>' .
				'<td colspan="2" class="diff-lineno">Test</td></tr>',
			$this->diffFormatterUtils->createHeader( 'Test' )
		);
	}

	public function testCreateAddedLine() {
		$this->assertSame(
			'<td class="diff-marker">+</td><td class="diff-addedline"><div>' .
				'<ins class="diffchange diffchange-inline">Test</ins></div></td>',
			$this->diffFormatterUtils->createAddedLine( 'Test' )
		);
	}

	public function testCreateDeletedLine() {
		$this->assertSame(
			'<td class="diff-marker">-</td><td class="diff-deletedline"><div>' .
				'<del class="diffchange diffchange-inline">Test</del></div></td>',
			$this->diffFormatterUtils->createDeletedLine( 'Test' )
		);
	}
}

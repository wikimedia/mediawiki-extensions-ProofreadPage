<?php

/**
 * @group ProofreadPage
 * @covers ProofreadDiffFormatterUtils
 */
class ProofreadDiffFormatterUtilsTest extends ProofreadPageTestCase {

	/**
	 * @var ProofreadDiffFormatterUtils
	 */
	protected $diffFormatterUtils;

	public function setUp() {
		parent::setUp();

		$this->diffFormatterUtils = new ProofreadDiffFormatterUtils();
	}

	public function testCreateHeader() {
		$this->assertEquals(
			'<tr><td colspan="2" class="diff-lineno">Test</td><td colspan="2" class="diff-lineno">Test</td></tr>',
			$this->diffFormatterUtils->createHeader( 'Test' )
		);
	}

	public function testCreateAddedLine() {
		$this->assertEquals(
			'<td class="diff-marker">+</td><td class="diff-addedline"><div><ins class="diffchange diffchange-inline">Test</ins></div></td>',
			$this->diffFormatterUtils->createAddedLine( 'Test' )
		);
	}

	public function testCreateDeletedLine() {
		$this->assertEquals(
			'<td class="diff-marker">-</td><td class="diff-deletedline"><div><del class="diffchange diffchange-inline">Test</del></div></td>',
			$this->diffFormatterUtils->createDeletedLine( 'Test' )
		);
	}
}
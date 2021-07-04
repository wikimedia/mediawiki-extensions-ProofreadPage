<?php

namespace ProofreadPage\Index;

use PHPUnit\Framework\TestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Index\PagesQualityStats
 */
class PagesQualityStatsTest extends TestCase {

	public function testGetNumberOfPages() {
		$stats = new PagesQualityStats( 10, [] );
		$this->assertSame( 10, $stats->getNumberOfPages() );
	}

	public function testGetNumberOfPagesForLevel() {
		$stats = new PagesQualityStats( 10, [ 0, 3, 1 ] );
		$this->assertSame( 3, $stats->getNumberOfPagesForQualityLevel( 1 ) );
		$this->assertSame( 1, $stats->getNumberOfPagesForQualityLevel( 2 ) );
		$this->assertSame( 0, $stats->getNumberOfPagesForQualityLevel( 3 ) );
	}

	public function testGetNumberOfPagesForLevelDefault() {
		$stats = new PagesQualityStats( 10, [] );
		$this->assertSame( 0, $stats->getNumberOfPagesForQualityLevel( 1 ) );
	}

	public function testGetNumberOfPagesWithoutLevel() {
		$stats = new PagesQualityStats( 10, [ 0, 1, 3 ] );
		$this->assertSame( 6, $stats->getNumberOfPagesWithoutQualityLevel() );
	}

	public function testWithLevelChange() {
		$stats = new PagesQualityStats( 10, [ 1, 2, 3 ] );
		$this->assertEquals(
			new PagesQualityStats( 10, [ 1, 3, 2 ] ),
			$stats->withLevelChange( 2, 1 )
		);

		// Immutability
		$this->assertEquals(
			new PagesQualityStats( 10, [ 1, 2, 3 ] ),
			$stats
		);
	}

	public function testWithPageCreation() {
		$stats = new PagesQualityStats( 10, [ 1, 2, 3 ] );
		$this->assertEquals(
			new PagesQualityStats( 10, [ 1, 3, 3 ] ),
			$stats->withPageCreation( 1 )
		);

		// Immutability
		$this->assertEquals(
			new PagesQualityStats( 10, [ 1, 2, 3 ] ),
			$stats
		);
	}

	public function testWithPageDeletion() {
		$stats = new PagesQualityStats( 10, [ 1, 2, 3 ] );
		$this->assertEquals(
			new PagesQualityStats( 10, [ 1, 1, 3 ] ),
			$stats->withPageDeletion( 1 )
		);

		// Immutability
		$this->assertEquals(
			new PagesQualityStats( 10, [ 1, 2, 3 ] ),
			$stats
		);
	}
}

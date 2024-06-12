<?php

namespace ProofreadPage\Index;

use MediaWiki\Context\RequestContext;
use MediaWiki\Title\Title;
use ProofreadPageTestCase;
use TextSlotDiffRenderer;
use WikitextContent;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Index\IndexSlotDiffRenderer
 */
class IndexSlotDiffRendererTest extends ProofreadPageTestCase {

	public static function getDiffProvider() {
		return [
			[
				new IndexContent( [
					'Title' => new WikitextContent( 'bar' )
				] ),
				new IndexContent( [
					'Title' => new WikitextContent( 'bar' )
				] ),
				''
			],
			[
				new IndexContent( [
					'Title' => new WikitextContent( 'bar' )
				] ),
				null,
				'TitleTitle -bar+ '
			],
			[
				null,
				new IndexContent( [
					'Title' => new WikitextContent( 'bar' )
				] ),
				'TitleTitle - +bar '
			],
			[
				new IndexRedirectContent( Title::newFromText( 'Index:Foo' ) ),
				null,
				'redirect pageredirect page -Index:Foo+ '
			],
			[
				null,
				new IndexRedirectContent( Title::newFromText( 'Index:Foo' ) ),
				'redirect pageredirect page - +Index:Foo '
			],
			[
				new IndexContent( [
					'Title' => new WikitextContent( 'bar' )
				] ),
				new IndexRedirectContent( Title::newFromText( 'Index:Foo' ) ),
				'redirect pageredirect page - +Index:Foo TitleTitle -bar+ '
			],
			[
				new IndexRedirectContent( Title::newFromText( 'Index:Foo' ) ),
				new IndexContent( [
					'Title' => new WikitextContent( 'bar' )
				] ),
				'redirect pageredirect page -Index:Foo+ TitleTitle - +bar '
			],
		];
	}

	/**
	 * @dataProvider getDiffProvider
	 */
	public function testGetDiff( $oldContent, $newContent, $result ) {
		$context = RequestContext::getMain();
		$textDiffRenderer = $this->getServiceContainer()
			->getContentHandlerFactory()
			->getContentHandler( CONTENT_MODEL_TEXT )
			->getSlotDiffRenderer( $context );
		$textDiffRenderer->setEngine( TextSlotDiffRenderer::ENGINE_PHP );

		$diffRender = new IndexSlotDiffRenderer(
			$context,
			$this->getContext()->getCustomIndexFieldsParser(),
			$textDiffRenderer
		);
		$this->assertSame(
			$result,
			$this->getPlainDiff( $diffRender->getDiff( $oldContent, $newContent ) )
		);
	}

	/**
	 * Convert a HTML diff to a human-readable format and hopefully make the test less fragile.
	 *
	 * Copied from TextSlotDiffRendererTest
	 *
	 * @param string $diff
	 * @return string
	 */
	private function getPlainDiff( $diff ) {
		$replacements = [
			html_entity_decode( '&nbsp;' ) => ' ',
			'&#160;' => ' ',
			html_entity_decode( '&minus;' ) => '-'
		];
		// Preserve markers when stripping tags
		$diff = str_replace( '<td class="diff-marker"></td>', ' ', $diff );
		$diff = str_replace( '<td colspan="2"></td>', ' ', $diff );
		$diff = preg_replace( '/data-marker="([^"]*)">/', '>$1', $diff );
		$diff = strip_tags( $diff );
		$diff = str_replace( array_keys( $replacements ), array_values( $replacements ), $diff );
		return preg_replace( '/\s+/', ' ', $diff );
	}
}

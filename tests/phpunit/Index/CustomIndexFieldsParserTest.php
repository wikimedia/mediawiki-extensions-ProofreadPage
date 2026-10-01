<?php

namespace ProofreadPage\Index;

use OutOfBoundsException;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Index\CustomIndexFieldsParser
 */
class CustomIndexFieldsParserTest extends ProofreadPageTestCase {
	/**
	 * @param string $content
	 * @return IndexContent
	 */
	private function buildContent( $content ) {
		return $this->getServiceContainer()
			->getContentHandlerFactory()
			->getContentHandler( CONTENT_MODEL_PROOFREAD_INDEX )
			->unserializeContent( $content );
	}

	public function testParseCustomIndexFields() {
		$content = $this->buildContent(
			"{{\n|Title=Test book\n|Author=[[Author:Me]]\n|Year=2012 or 2013\n|Header={{{Title}}}" .
				"\n|Pages=<pagelist />\n|TOC=* [[Test/Chapter 1|Chapter 1]]" .
				"\n* [[Test/Chapter 2|Chapter 2]]\n}}"
		);
		$entries = [
			'Title' => new CustomIndexField(
				'Title', 'Test book', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Title']
			),
			'Author' => new CustomIndexField(
				'Author', '[[Author:Me]]', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Author']
			),
			'Year' => new CustomIndexField(
				'Year', '2012 or 2013', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Year']
			),
			'Pages' => new CustomIndexField(
				'Pages', '<pagelist />', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Pages']
			),
			'Header' => new CustomIndexField(
				'Header', '{{{Title}}}', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Header']
			),
			'Footer' => new CustomIndexField(
				'Footer', '', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Footer']
			),
			'TOC' => new CustomIndexField(
				'TOC',
				"* [[Test/Chapter 1|Chapter 1]]\n* [[Test/Chapter 2|Chapter 2]]",
				self::CUSTOM_INDEX_FIELDS_CONFIGURATION['TOC']
			),
			'Comment' => new CustomIndexField(
				'Comment', '', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Comment']
			),
			'width' => new CustomIndexField(
				'width', '', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['width']
			),
			'CSS' => new CustomIndexField(
				'CSS', '', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['CSS']
			)
		];
		$this->assertEquals(
			$entries, self::getContext()->getCustomIndexFieldsParser()->parseCustomIndexFields( $content )
		);
	}

	public function testParseCustomIndexFieldsForHeader() {
		$content = $this->buildContent(
			"{{\n|Title=Test book\n|Author=[[Author:Me]]\n|Year=2012 or 2013\n|Pages=<pagelist />" .
				"\n|TOC=* [[Test/Chapter 1|Chapter 1]]\n* [[Test/Chapter 2|Chapter 2]]\n}}"
		);
		$entries = [
			'Title' => new CustomIndexField(
				'Title', 'Test book', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Title']
			),
			'Author' => new CustomIndexField(
				'Author', '[[Author:Me]]', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Author']
			),
			'Comment' => new CustomIndexField(
				'Comment', '', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Comment']
			),
			'Header' => new CustomIndexField(
				'Header', '', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Header']
			),
			'Footer' => new CustomIndexField(
				'Footer', '', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Footer']
			),
			'width' => new CustomIndexField(
				'width', '', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['width']
			),
			'CSS' => new CustomIndexField(
				'CSS', '', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['CSS']
			)
		];
		$this->assertEquals(
			$entries,
			self::getContext()->getCustomIndexFieldsParser()->parseCustomIndexFieldsForHeader( $content )
		);
	}

	public function testParseCustomIndexFieldsForJS() {
		$content = $this->buildContent(
			"{{\n|Title=Test book\n|Author=[[Author:Me]]\n|Year=2012 or 2013\n|Pages=<pagelist />" .
				"\n|width=500}}"
		);
		$entries = [
			'Author' => new CustomIndexField(
				'Author', '[[Author:Me]]', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Author']
			),
			'width' => new CustomIndexField(
				'width', '500', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['width']
			),
			'CSS' => new CustomIndexField(
				'CSS', '', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['CSS']
			)
		];
		$this->assertEquals(
			$entries,
			self::getContext()->getCustomIndexFieldsParser()->parseCustomIndexFieldsForJS( $content )
		);
	}

	public function testParseCustomIndexField() {
		$content = $this->buildContent( "{{\n|Year=2012 or 2013\n}}" );
		$parser = self::getContext()->getCustomIndexFieldsParser();
		$entry = new CustomIndexField(
			'Year', '2012 or 2013', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Year']
		);
		$this->assertEquals( $entry, $parser->parseCustomIndexField( $content, 'year' ) );
	}

	public function testGetCustomIndexFieldForDataKey() {
		$content = $this->buildContent( "{{\n|Pages=2012 or 2013\n}}" );
		$parser = self::getContext()->getCustomIndexFieldsParser();
		$entry = new CustomIndexField(
			'Pages', '2012 or 2013', self::CUSTOM_INDEX_FIELDS_CONFIGURATION['Pages']
		);
		$this->assertEquals( $entry, $parser->getCustomIndexFieldForDataKey( $content, 'pagelist' ) );

		$this->expectException( OutOfBoundsException::class );
		$parser->getCustomIndexFieldForDataKey( $content, 'pages' );
	}

	public function testParseCustomIndexFieldThatDoesNotExist() {
		$content = $this->buildContent( "{{\n|Year=2012 or 2013\n}}" );
		$parser = self::getContext()->getCustomIndexFieldsParser();
		$this->expectException( OutOfBoundsException::class );
		$parser->parseCustomIndexField( $content, 'years' );
	}

	public static function replaceVariablesWithIndexEntriesProvider() {
		return [
			[
				"{{\n|Title=Test book\n|Header={{{title}}}\n}}",
				'Test book',
				'header',
				[]
			],
			[
				"{{\n|Title=Test book\n|Header={{{ Pagenum }}}\n}}",
				'22',
				'header',
				[ 'pagenum' => 22 ]
			],
			[
				"{{\n|Title=Test book\n|Header={{{authors}}}\n}}",
				'{{{authors}}}',
				'header',
				[]
			],
			[
				"{{\n|Title=Test book\n|Header={{{authors |a}}}\n}}",
				'a',
				'header',
				[]
			],
			[
				"{{\n|Title=Test book\n|Header={{template|a=b}}\n}}",
				'{{template|a=b}}',
				'header',
				[]
			],
			[
				"{{\n|Title=Test book\n|Header={{template|a={{{Title |}}}}}\n}}",
				'{{template|a=Test book}}',
				'header',
				[]
			],
			[
				"{{\n|Title=Test book\n|Header=<references/>\n}}",
				'<references/>',
				'header',
				[]
			],
		];
	}

	/**
	 * @dataProvider replaceVariablesWithIndexEntriesProvider
	 */
	public function testReplaceVariablesWithIndexEntries(
		$pageContent, $result, $entry, $extraparams
	) {
		$content = $this->buildContent( $pageContent );
		$this->assertSame(
			$result,
			self::getContext()->getCustomIndexFieldsParser()
				->parseCustomIndexFieldWithVariablesReplacedWithIndexEntries( $content, $entry, $extraparams )
		);
	}

	public function testReplaceVariablesWithIndexEntriesThatDoesNotExist() {
		$content = $this->buildContent( "{{\n|Title=Test book\n|Header={{{Pagenum}}}\n}}" );
		$this->expectException( OutOfBoundsException::class );
		self::getContext()->getCustomIndexFieldsParser()
			->parseCustomIndexFieldWithVariablesReplacedWithIndexEntries( $content, 'headers', [] );
	}
}

<?php

namespace ProofreadPage\Index;

use ContentHandler;
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
	private static function buildContent( $content ) {
		return ContentHandler::getForModelID( CONTENT_MODEL_PROOFREAD_INDEX )
				->unserializeContent( $content );
	}

	public function testParseCustomIndexFields() {
		$content = self::buildContent(
			"{{\n|Title=Test book\n|Author=[[Author:Me]]\n|Year=2012 or 2013\n|Header={{{Title}}}" .
				"\n|Pages=<pagelist />\n|TOC=* [[Test/Chapter 1|Chapter 1]]" .
				"\n* [[Test/Chapter 2|Chapter 2]]\n}}"
		);
		$entries = [
			'Title' => new CustomIndexField(
				'Title', 'Test book', self::$customIndexFieldsConfiguration['Title']
			),
			'Author' => new CustomIndexField(
				'Author', '[[Author:Me]]', self::$customIndexFieldsConfiguration['Author']
			),
			'Year' => new CustomIndexField(
				'Year', '2012 or 2013', self::$customIndexFieldsConfiguration['Year']
			),
			'Pages' => new CustomIndexField(
				'Pages', '<pagelist />', self::$customIndexFieldsConfiguration['Pages']
			),
			'Header' => new CustomIndexField(
				'Header', '{{{Title}}}', self::$customIndexFieldsConfiguration['Header']
			),
			'Footer' => new CustomIndexField(
				'Footer', '', self::$customIndexFieldsConfiguration['Footer']
			),
			'TOC' => new CustomIndexField(
				'TOC',
				"* [[Test/Chapter 1|Chapter 1]]\n* [[Test/Chapter 2|Chapter 2]]",
				self::$customIndexFieldsConfiguration['TOC']
			),
			'Comment' => new CustomIndexField(
				'Comment', '', self::$customIndexFieldsConfiguration['Comment']
			),
			'width' => new CustomIndexField(
				'width', '', self::$customIndexFieldsConfiguration['width']
			),
			'CSS' => new CustomIndexField(
				'CSS', '', self::$customIndexFieldsConfiguration['CSS']
			)
		];
		$this->assertEquals(
			$entries, $this->getContext()->getCustomIndexFieldsParser()->parseCustomIndexFields( $content )
		);
	}

	public function testParseCustomIndexFieldsForHeader() {
		$content = self::buildContent(
			"{{\n|Title=Test book\n|Author=[[Author:Me]]\n|Year=2012 or 2013\n|Pages=<pagelist />" .
				"\n|TOC=* [[Test/Chapter 1|Chapter 1]]\n* [[Test/Chapter 2|Chapter 2]]\n}}"
		);
		$entries = [
			'Title' => new CustomIndexField(
				'Title', 'Test book', self::$customIndexFieldsConfiguration['Title']
			),
			'Author' => new CustomIndexField(
				'Author', '[[Author:Me]]', self::$customIndexFieldsConfiguration['Author']
			),
			'Comment' => new CustomIndexField(
				'Comment', '', self::$customIndexFieldsConfiguration['Comment']
			),
			'Header' => new CustomIndexField(
				'Header', '', self::$customIndexFieldsConfiguration['Header']
			),
			'Footer' => new CustomIndexField(
				'Footer', '', self::$customIndexFieldsConfiguration['Footer']
			),
			'width' => new CustomIndexField(
				'width', '', self::$customIndexFieldsConfiguration['width']
			),
			'CSS' => new CustomIndexField(
				'CSS', '', self::$customIndexFieldsConfiguration['CSS']
			)
		];
		$this->assertEquals(
			$entries,
			$this->getContext()->getCustomIndexFieldsParser()->parseCustomIndexFieldsForHeader( $content )
		);
	}

	public function testParseCustomIndexField() {
		$content = self::buildContent( "{{\n|Year=2012 or 2013\n}}" );
		$parser = $this->getContext()->getCustomIndexFieldsParser();
		$entry = new CustomIndexField(
			'Year', '2012 or 2013', self::$customIndexFieldsConfiguration['Year']
		);
		$this->assertEquals( $entry, $parser->parseCustomIndexField( $content, 'year' ) );
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testParseCustomIndexFieldThatDoesNotExist() {
		$content = self::buildContent( "{{\n|Year=2012 or 2013\n}}" );
		$parser = $this->getContext()->getCustomIndexFieldsParser();
		$parser->parseCustomIndexField( $content, 'years' );
	}

	public function replaceVariablesWithIndexEntriesProvider() {
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
		$content = self::buildContent( $pageContent );
		$this->assertEquals(
			$result,
			$this->getContext()->getCustomIndexFieldsParser()
				->parseCustomIndexFieldWithVariablesReplacedWithIndexEntries( $content, $entry, $extraparams )
		);
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testReplaceVariablesWithIndexEntriesThatDoesNotExist() {
		$content = self::buildContent( "{{\n|Title=Test book\n|Header={{{Pagenum}}}\n}}" );
		$this->getContext()->getCustomIndexFieldsParser()
			->parseCustomIndexFieldWithVariablesReplacedWithIndexEntries( $content, 'headers', [] );
	}
}

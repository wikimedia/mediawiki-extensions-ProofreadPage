<?php

/**
 * @group ProofreadPage
 */
class ProofreadPageContentTest extends ProofreadPageTestCase {

	public function pageProvider() {
		return array(
			array( 'Experimental header', 'Experimental body', 'Experimental footer', 2, 'Woot' ),
			array( 'Experimental header', 'Experimental body', '', 2, 'Woot')
		);
	}

	public function stringProvider( ) {
		return array(
			array( 'Experimental header', 'Experimental body', 'Experimental footer', 2, 'Woot', '<noinclude>{{PageQuality|2|Woot}}<div class="pagetext">Experimental header' . "\n\n\n" . '</noinclude>Experimental body<noinclude>Experimental footer</div></noinclude>' ),
			array( 'Experimental header', 'Experimental body', '', 2, 'Woot', '<noinclude>{{PageQuality|2|Woot}}<div>Experimental header' . "\n\n\n" . '</noinclude>Experimental body</div>'),
			array( 'Experimental header', 'Experimental body', 'Experimental footer', 2, 'Woot', '<noinclude><pagequality level="2" user="Woot" /><div class="pagetext">Experimental header' . "\n\n\n" . '</noinclude>Experimental body<noinclude>Experimental footer</div></noinclude>' ),
			array( 'Experimental header', 'Experimental body', '', 2, 'Woot', '<noinclude><pagequality level="2" user="Woot" /><div>Experimental header' . "\n\n\n" . '</noinclude>Experimental body</div>' )
		);
	}

	public function nameProvider() {
		return array(
			array( 'WikiUser' ),
			array( '' ),
			array( '172.16.254.7' ),
			array( '2001:odb8:ac10:fe10:00:00:00:00' )
		);
	}

	public function testGetHeader() {
		$header = "testString";
		$pageContent = new ProofreadPageContent( $header );
		$this->assertEquals( $header, $pageContent->getHeader() );
	}

	public function testGetFooter() {
		$footer = "testString";
		$pageContent = new ProofreadPageContent( '', '', $footer );
		$this->assertEquals( $footer, $pageContent->getFooter() );
	}

	public function testGetBody() {
		$body = "testString";
		$pageContent = new ProofreadPageContent( '', $body );
		$this->assertEquals( $body, $pageContent->getBody() );
	}

	public function testGetLevel() {
		$level = 2;
		$pageContent = new ProofreadPageContent( '', '', '', $level );
		$this->assertEquals( $level, $pageContent->getProofreadingLevel() );
	}

	public function testSetHeader() {
		$header = "testString";
		$pageContent = new ProofreadPageContent();
		$pageContent->setHeader( $header );
		$this->assertEquals( $header, $pageContent->getHeader() );
	}

	public function testSetFooter() {
		$footer = "testString";
		$pageContent = new ProofreadPageContent();
		$pageContent->setFooter( $footer );
		$this->assertEquals( $footer, $pageContent->getFooter() );
	}

	public function testSetBody() {
		$body = "testString";
		$pageContent = new ProofreadPageContent();
		$pageContent->setBody( $body);
		$this->assertEquals( $body, $pageContent->getBody() );
	}

	public function testSetLevel() {
		$level = 3;
		$pageContent = new ProofreadPageContent();
		$pageContent->setLevel( $level );
		$this->assertEquals( $level, $pageContent->getProofreadingLevel() );
	}

	/**
	 * @dataProvider nameProvider
	 */
	public function testSetProofreaderFromName( $name ) {
		$pageContent = new ProofreadPageContent();
		$pageContent->setProofreaderFromName( $name );
		$this->assertEquals( $name, $pageContent->getProofreader() );
	}

	/**
	 * @expectedException Exception
	 */
	public function testSetProofreaderFromNameException() {
		$name = null;
		$pageContent = new ProofreadPageContent();
		$pageContent->setProofreaderFromName( $name );
		$this->setExpectedException( 'Name is an invalid username.' );
	}

	/**
	 * @dataProvider pageProvider
	 */
	public function testSerialize( $header, $body, $footer, $level, $proofreader ) {
		$pageContent = new ProofreadPageContent( $header, $body, $footer, $level, $proofreader );
		$serializedString = '<noinclude><pagequality level="' . $level . '" user="';
		$serializedString .= $proofreader;
		$serializedString .= '" /><div class="pagetext">' . $header ."\n\n\n" . '</noinclude>';
		$serializedString .= $body;
		$serializedString .= '<noinclude>' . $footer . '</div></noinclude>';
		$this->assertEquals( $serializedString, $pageContent->serialize() );
	}

	/**
	 * @dataProvider stringProvider
	 */
	public function testUnserializeOldModel( $header, $body, $footer, $level, $proofreader, $serializedString ) {
		$refPageContent = new ProofreadPageContent( $header, $body, $footer, $level, $proofreader );
		$pageContent = new ProofreadPageContent();
		$pageContent->unserialize( $serializedString );
		$this->assertEquals( $pageContent, $refPageContent );
	}

	/**
	 * @dataProvider pageProvider
	 */
	public function testUnserializeNewModel( $header, $body, $footer, $level, $proofreader ) {
		$refPageContent = new ProofreadPageContent( $header, $body, $footer, $level, $proofreader );
		$text = $refPageContent->serialize();
		$pageContent = new ProofreadPageContent();
		$pageContent->unserialize( $text );
		$this->assertEquals( $pageContent, $refPageContent );
	}

	public function testNewFromWikiText() {
		$text = '<noinclude>{{PageQuality|2|Woot}}<div>Experimental header' . "\n\n\n" . '</noinclude>Experimental body<noinclude></div></noinclude>';
		$pageContent = ProofreadPageContent::newFromWikitext(  $text );
		$this->assertInstanceOf( 'ProofreadPageContent', $pageContent );
	}
}
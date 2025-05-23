<?php

namespace ProofreadPage;

use MediaWiki\Config\ConfigException;
use MediaWiki\Config\HashConfig;
use MediaWiki\MediaWikiServices;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\ProofreadPageInit
 */
class ProofreadPageInitTest extends ProofreadPageTestCase {

	public function testInitNamespaceThrowsExceptionWhenNamespaceValueIsNotNumeric() {
		$this->overrideConfigValue( 'ProofreadPageNamespaceIds', [ 'page' => 'quux' ] );
		$this->expectException( ConfigException::class );
		$config = new HashConfig( [
			'ProofreadPageNamespaceIds' => [
				'page' => 'quux'
			],
			'TemplateStylesNamespaces' => [
				'10' => true
			]
		] );
		$mockServiceContainer = $this->createNoOpMock( MediaWikiServices::class, [ 'getMainConfig' ] );
		$mockServiceContainer->method( 'getMainConfig' )->willReturn( $config );
		$proofreadPageInit = new ProofreadPageInit();
		$proofreadPageInit->onMediaWikiServices( $mockServiceContainer );
	}

	public function testGetNamespaceIdThrowsExceptionWhenKeyDoesNotExist() {
		$this->overrideConfigValue( 'ProofreadPageNamespaceIds', [] );
		$this->expectException( ConfigException::class );
		ProofreadPageInit::getNamespaceId( 'page' );
	}

}

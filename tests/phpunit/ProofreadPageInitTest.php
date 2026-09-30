<?php

namespace ProofreadPage;

use MediaWiki\Config\ConfigException;
use MediaWiki\Config\HashConfig;
use MediaWiki\MediaWikiServices;
use MediaWiki\Registration\ExtensionRegistry;
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
		$mockServiceContainer = $this->createNoOpMock( MediaWikiServices::class, [
			'getExtensionRegistry',
			'getMainConfig',
		] );
		$registry = $this->createMock( ExtensionRegistry::class );
		$mockServiceContainer->method( 'getExtensionRegistry' )->willReturn( $registry );
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

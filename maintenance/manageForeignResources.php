<?php

namespace MediaWiki\Extension\ProofreadPage\Maintenance;

use Exception;
use ForeignResourceManager;
use Maintenance;

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__ . '/../../..';
}
require_once "$IP/maintenance/Maintenance.php";

class ManageForeignResources extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->requireExtension( 'ProofreadPage' );
		$this->addArg( 'action', 'One of "update", "verify" or "make-sri"', true );
	}

	public function execute() {
		$frm = new ForeignResourceManager(
			__DIR__ . '/../modules/foreign-resources.yaml',
			__DIR__ . '/../modules/lib',
			function ( $text ) {
				$this->output( $text );
			},
			function ( $text ) {
				$this->error( $text );
			},
			function ( $text ) {
				$this->output( $text );
			}
		);
		try {
			return $frm->run( $this->getArg( 0 ), 'all' );
		} catch ( Exception $e ) {
			$this->fatalError( "Error: {$e->getMessage()}" );
		}
	}
}

$maintClass = ManageForeignResources::class;
require_once RUN_MAINTENANCE_IF_MAIN;

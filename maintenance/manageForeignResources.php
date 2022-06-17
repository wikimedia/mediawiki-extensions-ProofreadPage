<?php

namespace MediaWiki\Extension\ProofreadPage\Maintenance;

use Exception;
use ForeignResourceManager;
use Maintenance;

// Security: Disable all stream wrappers and reenable individually as needed
foreach ( stream_get_wrappers() as $wrapper ) {
	stream_wrapper_unregister( $wrapper );
}
stream_wrapper_restore( 'file' );
stream_wrapper_restore( 'php' );

$basePath = getenv( 'MW_INSTALL_PATH' );
if ( $basePath ) {
	if ( !is_dir( $basePath )
		|| strpos( $basePath, '.' ) !== false
		|| strpos( $basePath, '~' ) !== false
	) {
		die( "Bad MediaWiki install path: $basePath\n" );
	}
} else {
	$basePath = __DIR__ . '/../../..';
}
require_once "$basePath/maintenance/Maintenance.php";

class ManageForeignResources extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->requireExtension( 'ProofreadPage' );
		$this->addArg( 'action', 'One of "update", "verify" or "make-sri"', true );
	}

	public function execute() {
		$frm = new ForeignResourceManager(
			__DIR__ . '/../modules/foreign-resources.yaml',
			__DIR__ . '/../modules/foreign',
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

$doMaintenancePath = RUN_MAINTENANCE_IF_MAIN;
if ( !( file_exists( $doMaintenancePath ) &&
	realpath( $doMaintenancePath ) === realpath( "$basePath/maintenance/doMaintenance.php" ) ) ) {
	die( "Bad maintenance script location: $basePath\n" );
}

require_once RUN_MAINTENANCE_IF_MAIN;

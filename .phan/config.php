<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['file_list'] = array_merge(
	$cfg['file_list'],
	[
		'ProofreadPage.body.php',
		'ProofreadPage.namespaces.php',
	]
);

return $cfg;

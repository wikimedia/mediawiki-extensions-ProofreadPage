<?php
$wgEnableUploads = true;
$wgFileExtensions = [ 'png', 'gif', 'jpg', 'jpeg', 'doc',
	'xls', 'mpp', 'pdf', 'ppt', 'tiff', 'bmp', 'docx', 'xlsx',
	'pptx', 'ps', 'odt', 'ods', 'odp', 'odg'
];
$wgFileExtensions[] = 'djvu';
$wgDjvuDump = 'djvudump';
$wgDjvuRenderer = 'ddjvu';
$wgDjvuTxt = 'djvutxt';
$wgDjvuPostProcessor = 'pnmtojpeg';
$wgDjvuOutputExtension = 'jpg';
$wgProofreadPageEnableEditInSequence = true;

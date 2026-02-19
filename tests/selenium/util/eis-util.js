import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

// eslint-disable-next-line no-underscore-dangle
const __dirname = path.dirname( fileURLToPath( import.meta.url ) );

async function importTemplates( api ) {
	const token = await api.getEditToken();
	const filePath = path.join( __dirname, '../../data/en-wikisource-2021-template-dump.xml' );
	// eslint-disable-next-line n/no-unsupported-features/node-builtins
	const formData = new FormData();
	formData.append( 'action', 'import' );
	// eslint-disable-next-line n/no-unsupported-features/node-builtins, security/detect-non-literal-fs-filename
	formData.append( 'xml', new Blob( [ fs.readFileSync( filePath ) ] ), path.basename( filePath ) );
	formData.append( 'interwikiprefix', 'enws' );
	formData.append( 'token', token );
	// eslint-disable-next-line n/no-unsupported-features/node-builtins
	await fetch( `${ browser.options.baseUrl }/api.php?format=json`, {
		method: 'POST',
		headers: { Cookie: api.cookies.toHeader() },
		body: formData
	} );
}

async function uploadFile( api, filename, filePath, summary ) {
	const token = await api.getEditToken();
	const fileTitle = filename.startsWith( 'File:' ) ? filename.slice( 5 ) : filename;
	// eslint-disable-next-line n/no-unsupported-features/node-builtins
	const formData = new FormData();
	formData.append( 'action', 'upload' );
	formData.append( 'filename', fileTitle );
	formData.append( 'comment', summary );
	formData.append( 'ignorewarnings', '1' );
	formData.append( 'token', token );
	// eslint-disable-next-line n/no-unsupported-features/node-builtins, security/detect-non-literal-fs-filename
	formData.append( 'file', new Blob( [ fs.readFileSync( filePath ) ] ), fileTitle );
	// eslint-disable-next-line n/no-unsupported-features/node-builtins
	await fetch( `${ browser.options.baseUrl }/api.php?format=json`, {
		method: 'POST',
		headers: { Cookie: api.cookies.toHeader() },
		body: formData
	} );
}

async function setupPrpTemplates( api ) {
	await importTemplates( api );
}

// Check if a element is enabled or disabled via OOUI
// (the aria-disabled attribute will be set to true)
async function isEnabledInOOUI( element ) {
	return await element.getAttribute( 'aria-disabled' ) === null;
}

export default {
	setupPrpTemplates,
	uploadFile,
	isEnabledInOOUI
};

<?php

namespace ProofreadPage\OOUI;

use OOUI\MultilineTextInputWidget;

/**
 * @license GPL-2.0-or-later
 *
 * A custom widget to help in inpu of Pagelists
 */
class PagelistInputWidget extends \OOUI\Widget {

	/**
	 * @var string
	 */
	protected $templateParameter;
	/**
	 * @var MultilineTextInputWidget
	 */
	protected $textInputWidget;

	/**
	 * @inheritDoc
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( array_merge( [
			'infusable' => true
		], $config ) );

		$this->templateParameter = $config['templateParameter'];

		$this->textInputWidget = new MultilineTextInputWidget( $config );
		$this->textInputWidget->addClasses( [ 'prp-pagelist-input-textarea' ] );
		$this->appendContent( $this->textInputWidget );

		$this->addClasses( [ 'prp-pagelistInputWidget' ] );
	}

	/**
	 * @inheritDoc
	 */
	protected function getJavaScriptClassName() {
		return 'mw.proofreadpage.PagelistInputWidget';
	}

	/**
	 * @inheritDoc
	 */
	public function getConfig( &$config ) {
		$config['templateParameter'] = $this->templateParameter;
		$config['textInputWidget'] = $this->textInputWidget;
		return parent::getConfig( $config );
	}
}

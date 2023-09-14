<?php

namespace ProofreadPage\OOUI;

use OOUI\RadioInputWidget;
use OOUI\RadioSelectInputWidget;

class PageQualityInputWidget extends RadioSelectInputWidget {

	/**
	 * @inheritDoc
	 */
	public function __construct( array $config = [] ) {
		$options = [];
		foreach ( $config['levels'] as $level ) {
			$msg = 'proofreadpage_quality' . $level . '_category';
			$options[] = [ 'data' => $level, 'label' => wfMessage( $msg ) ];
		}
		parent::__construct( array_merge( [
			'infusable' => true,
			'classes' => [ 'prp-pageQualityInputWidget' ],
			'name' => 'wpQuality',
			'options' => $options,
		], $config ) );

		foreach ( $this->fields as $field ) {
			// @var RadioInputWidget $widget
			$widget = $field->getField();
			'@phan-var RadioInputWidget $widget';
			$widget->addClasses( [ 'prp-quality-radio', 'quality' . $widget->getValue() ] );
			$widget->setInfusable( true );
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function getJavaScriptClassName() {
		return 'mw.proofreadpage.PageQualityInputWidget';
	}
}

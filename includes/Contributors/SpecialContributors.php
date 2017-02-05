<?php

namespace Contributors;
use Html;
use Xml;

class SpecialContributors extends \SpecialCachedPage {

	// this holds the target page we are analysing
	protected $target;

	function __construct() {
		parent::__construct( 'Contributors' );
	}


	public function execute( $target ) {
		wfProfileIn( __METHOD__ );
		global $wgOut, $wgRequest;

		$this->startCache( 1800, true );
		$this->setHeaders();
		//$this->determineTarget( $wgRequest, $target );
		$this->getOutput()->setPageTitle("Contributors");

		$wgOut->addHTML( $this->makeForm() );
		if ( is_object( $this->target ) ) {
			$this->showNormal();
		}

		// Build an informative top for the page
		$this->getOutput()->addHTML( '<ol>' );
		//$vec = $this->lsv($target);
		$vec = array();
		foreach( $vec as $lsh) {
			$this->getOutput()->addHTML( '<li>' . $lsh . '</li>' );
		}
		$this->getOutput()->addHTML( '</ol>' );

		// Get the previous analysis or make a new one
		// This is cached for each page we analyse
		//$anotherValue = $this->getCachedValue( function() {
		//	return $anotherValue;
		//} );

		// Generate pretty formatted output
		// This is cached per page and language
		//$result = "dingeling";
		//$this->addCachedHTML( function() use ( $result ) {
		//	return '<b>Some expensive result: [' . $result . ']</b>';
		//} );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Given the web request, and a possible override from a subpage, work
	 * out which we want to use
	 *
	 * @param $request WebRequest we're serving
	 * @param $override Possible subpage override
	 * @return string
	 */
	private function determineTarget( &$request, $override ) {
		$target = $request->getText( 'target', $override );
		$this->target = Title::newFromURL( $target );
	}

	/**
	 * Make a nice little form so the user can enter a title and so forth
	 * in normal output mode
	 *
	 * @return string
	 */
	private function makeForm() {
		global $wgScript;
		$self = parent::getTitleFor( 'Contributors' );
		$target = is_object( $this->target ) ? $this->target->getPrefixedText() : '';
		$form  = Html::openElement( 'form', array( 'method' => "get", 'action' => htmlspecialchars( $wgScript ) ) );
		$form .=   Html::Hidden( 'title', $self->getPrefixedText() );
		$form .=   Html::openElement( 'fieldset' );
		$form .=     Html::openElement( 'legend' );
		$form .=       wfMessage( 'contributors-legend' )->text();
		$form .=     Html::closeElement( 'legend');
		$form .=     Html::openElement( 'table' );
		$form .=       Html::openElement( 'tr' );
		$form .=         Html::openElement( 'td' );
		$form .=           Html::element( 'label', array( 'for' => 'target' ), wfMessage( 'contributors-target' )->text() );
		$form .=         Html::closeElement( 'td' );
		$form .=         Html::openElement( 'td' );
		$form .=           Xml::input( 'target', 40, $target, array( 'id' => 'target' ) );
		$form .=         Html::closeElement( 'td' );
		$form .=       Html::closeElement( 'tr' );
		$form .=       Html::openElement( 'tr' );
		$form .=         Html::element( 'td', array(), '&nbsp;' );
		$form .=         Html::openElement( 'td' );
		$form .=           Xml::submitButton( wfMessage( 'contributors-submit' )->text() );
		$form .=         Html::closeElement( 'td' );
		$form .=       Html::closeElement( 'tr' );
		$form .=     Html::closeElement( 'table' );
		$form .=   Html::closeElement( 'fieldset' );
		$form .= Html::closeElement( 'form' );
		return $form;
	}

}
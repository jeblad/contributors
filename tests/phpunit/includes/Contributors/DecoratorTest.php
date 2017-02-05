<?php

namespace Contributors\Test;

use Contributors\Decorator;

/**
 * Test Contributors\Decorator.
 *
 * @file
 * @since 0.1
 *
 * @ingroup ContribtorsTest
 * @ingroup Test
 *
 * @group Contributors
 *
 * @licence GNU GPL v2+
 * @author John Erling Blad < jeblad@gmail.com >
 *
 */
class DecoratorTest extends \MediaWikiTestCase {

	function newUserStub( $id, $name, $realName ) {
		$stub = $this->getMock('User');
		$stub->expects( $this->any() )->method( 'getUserPage' )->will( $this->returnValue( \Title::newFromId( $id ) ) );
		$stub->expects( $this->any() )->method( 'getName' )->will( $this->returnValue( $name ) );
		$stub->expects( $this->any() )->method( 'getRealName' )->will( $this->returnValue( $realName ) );
		$stub->expects( $this->any() )->method( 'isAnon' )->will( $this->returnValue( !( \User::isIP( $name ) ) ) );

		return $stub;
	}

	/**
	 * @dataProvider provideFormatRow
	 */
	function testFormatRow( $data, $expected ) {
		$decorator = new Decorator();
		$html = $decorator->formatRow( $data );
		$xml = new \SimpleXMLElement($html);
		$found = $xml->xpath('/tr/th/a/text()|/tr/td');
		while ( !empty( $expected ) ) {
			$this->assertEquals( array_shift( $expected ), preg_replace( '/<.*?>/', '', array_shift( $found )->asXML() ) );
		}
	}

	function provideFormatRow() {
		return array(
			array(
				array( $this->newUserStub( 1, '127.0.0.1', null ), 1, 2, 3, 4 ),
				array( '127.0.0.1', 1, 2, 3, 4 )
			),
			array(
				array( $this->newUserStub( 1, 'User0', null ), 1, 2, 3, 4 ),
				array( 'User0', 1, 2, 3, 4 )
			),
			array(
				array( $this->newUserStub( 1, 'User0', 'Joe Doe' ), 1, 2, 3, 4 ),
				array( 'Joe Doe', 1, 2, 3, 4 )
			),
		);
	}

	/**
	 * @dataProvider provideFormatBody
	 */
	function testFormatBody( $data, $expected ) {
		$decorator = new Decorator();
		$html = $decorator->formatBody( $data );
		//print_r($html);
		$xml = new \SimpleXMLElement($html);
		$found = $xml->xpath('/tbody/tr/th/a');
		while ( !empty( $expected ) ) {
			$this->assertEquals( array_shift( $expected ), preg_replace( '/<.*?>/', '', array_shift( $found )->asXML() ) );
		}
	}

	function provideFormatBody() {
		return array(
			array(
				array(
					array( $this->newUserStub( 1, '127.0.0.1', null ), 1, 2, 3, 4 ),
					array( $this->newUserStub( 1, 'User0', null ), 1, 2, 3, 4 ),
					array( $this->newUserStub( 1, 'User0', 'Joe Doe' ), 1, 2, 3, 4 )
				),
				array( '127.0.0.1', 'User0', 'Joe Doe' )
			),
		);
	}
}


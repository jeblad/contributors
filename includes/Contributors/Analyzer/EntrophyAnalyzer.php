<?php

namespace Contributors\Analyzer;

/**
 * Analyzer for texts using ownership and entrophy
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 0.1
 *
 * @file
 * @ingroup Contributors
 *
 * @licence GNU GPL v2+
 * @author John Erling Blad < jeblad@gmail.com >
 */
class EntrophyAnalyzer implements IAnalyzer {

	/**
	 * @var array
	 */
	private $state;

	/**
	 * @var array
	 */
	private $entrophy;

	/**
	 * @var string
	 */
	private $glyphs;

	/**
	 * Constructor
	 *
	 * @param array $opts
	 */
	function __construct( array $opts = array() ) {
		if ( array_key_exists( 'state', $opts ) ) {
			$this->state = $opts['state'];
		}
		if ( array_key_exists( 'entrophy', $opts ) ) {
			$this->state = $opts['entrophy'];
		}
		$this->glyphs = array_key_exists( 'glyphs', $opts )
			? '/[' . preg_quote( $opts['glyphs'] ) . ']+/'
			: '/\w+/';
	}

	/**
	 * This is an implementation of a unique word extraction as a feature vector
	 *
	 * @param string $str the string to be hashed
	 *
	 * @return the unique words as a feature vector
	 */
	public function buildDescriptor( /* string */ $str ) {
		return array_unique( preg_split( '/' .$opts['glyphs']. '/', $str ) );
	}

	public function measureDistance( $a, $b ) {
		
	}

	public function measureDistance( $a, $b ) {
		$combined = array( $a, $b );
		array_unshift( $combined, null);
		$transposed = call_user_func_array( 'array_map', $combined );
		return sqrt( array_reduce(
			$transposed,
			function( &$result, $item ) {
				return $result + pow( $item[1]-$item[0], 2 );
			},
			0
		) );
	}

	/**
	 * @see IAnalyzer::describe
	 */
	public function describe( /* string */ $text ) {
		return $this->buildDescriptor( $text );
	}

	/**
	 * @see IAnalyzer::initState
	 */
	public function initState( array $last = array() ) {
		$
		$this->state = $last;
	}

	/**
	 * @see IAnalyzer::reduce
	 */
	public function reduce( array $test ) {
		$result = $this->measureDistance( $test, $this->state );
		$this->state = $test;
		return $result;
	}
}

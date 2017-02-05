<?php

namespace Contributors\Analyzer;

/**
 * Analyzer for texts using tuplets
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
class TupletAnalyzer implements IAnalyzer {

	/**
	 * @var array
	 */
	private $permutations;

	/**
	 * @var int
	 */
	private $size;

	/**
	 * @var any
	 */
	private $state;

	/**
	 * Constructor
	 *
	 * @param array $opts
	 */
	function __construct( array $opts = array() ) {
		$this->size = array_key_exists( 'size', $opts )
			? $opts['size']
			: 3;
		$this->permutations = array_key_exists( 'permutations', $opts )
			? $opts['permutations']
			: array(
				1, 14, 110, 25, 97, 174, 132, 119, 138, 170, 125, 118, 27, 233, 140, 51,
				87, 197, 177, 107, 234, 169, 56, 68, 30, 7, 173, 73, 188, 40, 36, 65,
				49, 213, 104, 190, 57, 211, 148, 223, 48, 115, 15, 2, 67, 186, 210, 28,
				12, 181, 103, 70, 22, 58, 75, 78, 183, 167, 238, 157, 124, 147, 172, 144,
				176, 161, 141, 86, 60, 66, 128, 83, 156, 241, 79, 46, 168, 198, 41, 254,
				178, 85, 253, 237, 250, 154, 133, 88, 35, 206, 95, 116, 252, 192, 54, 221,
				102, 218, 255, 240, 82, 106, 158, 201, 61, 3, 89, 9, 42, 155, 159, 93,
				166, 80, 50, 34, 175, 195, 100, 99, 26, 150, 16, 145, 4, 33, 8, 189,
				121, 64, 77, 72, 208, 245, 130, 122, 143, 55, 105, 134, 29, 164, 185, 194,
				193, 239, 101, 242, 5, 171, 126, 11, 74, 59, 137, 228, 108, 191, 232, 139,
				6, 24, 81, 20, 127, 17, 91, 92, 251, 151, 225, 207, 21, 98, 113, 112,
				84, 226, 18, 214, 199, 187, 13, 32, 94, 220, 224, 212, 247, 204, 196, 43,
				249, 236, 45, 244, 111, 182, 153, 136, 129, 90, 217, 202, 19, 165, 231, 71,
				230, 142, 96, 227, 62, 179, 246, 114, 162, 53, 160, 215, 205, 180, 47, 109,
				44, 38, 31, 149, 135, 0, 216, 52, 63, 23, 37, 69, 39, 117, 146, 184,
				163, 200, 222, 235, 248, 243, 219, 10, 152, 131, 123, 229, 203, 76, 120, 209
			);
	}

	/**
	 * Build the descriptor
	 *
	 * This is an implementation of a locality sensitive feature vector
	 *
	 * The hashing is a sliding window, with the Pearson algorithm as its core
	 * hashing function.
	 *
	 * @param string $str the string to be hashed
	 *
	 * @return the hash as a feature vector
	 */
	public function buildDescriptor( /* string */ $str ) {
		$arr = array_fill( 0, 0x100, 0 );
		$l = strlen( $str ) - $this->size + 1;
		for ( $i = 0; $i < $l; $i++) {
			$hash = $this->permutations[ ord($str[$i]) & 0x0ff ];
			for ( $j = 1; $j < $this->size; $j++) {
				$hash = $this->permutations[ ($hash ^ ord($str[$i+$j])) & 0x0ff ];
			}
			$arr[$hash & 0x0ff]++;
		}
		return $arr;
	}


	/**
	 * Measure the distance
	 *
	 * @param any $a
	 * @param any $b
	 *
	 * @return the distance between the descriptors
	 */
	public function measureDistance( $a, $b ) {
		$combined = array( $a, $b );
		array_unshift( $combined, null);
		$transposed = call_user_func_array( 'array_map', $combined );
		return array_reduce(
			$transposed,
			function( &$result, $item ) {
				return $result + abs( $item[1]-$item[0] );
			},
			0
		);
	}

	/**
	 * @see IAnalyzer::describe
	 */
	public function describe( /* string */ $text ) {
		return $this->buildDescriptor( $text );
	}

	/**
	 * @see IAnalyzer::initState
	 *
	 * A call to this function will be neglected.
	 */
	public function initState( array $last = array() ) {
		$this->state = array_fill( 0, 0x100, 0 );
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

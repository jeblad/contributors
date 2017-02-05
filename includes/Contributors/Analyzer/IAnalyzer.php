<?php

namespace Contributors\Analyzer;

/**
 * Interface for analysers that transforms a string.
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
interface IAnalyzer {

	/**
	 * Describe the $text as a feature vector (a one dimmensional array)
	 *
	 * @since 0.1
	 *
	 * @param any $text
	 *
	 * @return array
	 */
	public function describe( /* string */ $text );

	/**
	 * Save the initial state
	 *
	 * @since 0.1
	 *
	 * @param array $last
	 *
	 * @return array
	 */
	public function initState( array $last = array() );

	/**
	 * Diff the vectors
	 *
	 * @since 0.1
	 *
	 * @param array $test
	 *
	 * @return number
	 */
	public function reduce( array $test );

}

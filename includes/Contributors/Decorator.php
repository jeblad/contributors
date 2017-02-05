<?php

namespace Contributors;
use Html;

/**
 * Decorator object for formatting results.
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
class Decorator {

	/**
	 * Take a data argument and properly format a whole table.
	 *
	 * @since 01
	 *
	 * @param array $results
	 * @param array $attribs
	 *
	 * @return a properly formated xml string
	 */
	public function format( array $data ) {
		$table = array();

		$head = array_shift( $data );

		$table[] = $this->formatHead( $head );
		$table[] = $this->formatBody( $data );

		if ( !empty( $table ) ) {
			return \Html::element( 'table', null, implode( "\n", $table ) );
		}

		return '';
	}

	/**
	 * Take a data argument and properly format a html table body.
	 *
	 * @since 01
	 *
	 * @param array $results
	 * @param array $attribs
	 *
	 * @return a properly formated xml string
	 */
	public function formatBody( array $data ) {
		$body = array();

		foreach ( $data as $row ) {
			$body[] = $this->formatRow( $row );
		}
//print_r($body);
		if ( !empty( $body ) ) {
			return \Html::rawElement( 'tbody', null, implode( "\n", $body ) );
		}

		return '';
	}

	/**
	 * Take a data argument and properly format a html table row.
	 *
	 * @since 01
	 *
	 * @param array $resultRow
	 * @param array $opts
	 *
	 * @return a properly formated xml string
	 */
	public function formatRow( array $data ) {
		$row = array();

		$user = array_shift( $data );
		$link = \Linker::link( $user->getUserPage(), htmlspecialchars( $user->getRealName() ? $user->getRealName() : $user->getName() ) );
		$row[] = \Html::rawElement( 'th', null, $link );

		foreach ( $data as $item ) {
			$row[] = \Html::rawElement( 'td', null, htmlspecialchars( $item ) );
		}

		if ( !empty( $row ) ) {
			return \Html::rawElement( 'tr', null, implode( "", $row ) );
		}

		return '';
	}

}

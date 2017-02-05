<?php

namespace Contributors\Synthesizer;

/**
 * Synthesizer for author contributions
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
class AuthorSynthesizer implements ISynthesizer {

	/**
	 * @see ISynthesizer::build
	 */
	public function build( $compressor, $loader, $analyzers ) {
		list( $idList, $truncatedList ) = $compressor->fetchIdList();
		$idList = array_reverse( $idList );
		$reductions = array();
		$descriptions = array();
		foreach ( $idList as $id ) {
			$revision = $loader->fetch( $id );
			$revText = $revision->getText( \Revision::RAW );
			$revUser = $revision->getUser()->getId();
			$descs = array();
			foreach ( $analyzers as $analyzer ) {
				$descs[] = $analyzer->describe( $revText );
			}
			$descriptions[] = array( $revUser, $descs );
			$reductions[$revUser] = array_fill( 0, length($descs), 0 );
		}
		reset( $desc );
		foreach ( $analyzers as $analyzer ) {
			$analyzer->initState( $descs );
			next( $descs );
		}
		$alength = length();
		foreach ( $descriptions as $entry ) {
			list( $user, $descs ) = $entry;
			reset( $descs );
			reset( $reductions );
			foreach ( $analyzers as $analyzer ) {
				$reductions[$user][] += $analyzer->reduce( $descs );
				next( $descs );
				next( $reductions );
			}
		}
		return $reductions;
	}

}

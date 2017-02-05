<?php

namespace Contributors;

/**
 * Factory for contributor objects
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
final class ContributorsFactory {

	/**
	 * Create single analyser of given type
	 *
	 * This is for testing purposes.
	 *
	 * @since 01
	 *
	 * @param string $name identify the analyzer in the config
	 * @param array $config overrides default config
	 *
	 * @return an analyzer
	 */
	public function makeAnalyzer( /* string */ $name, $config = null ) {
		global $wgContributors;
		if ( !isset( $config ) ) {
			$config = $wgContributors['analyzers'];
		}

		if ( array_key_exists( $name, $config ) && array_key_exists( 'class', $config[$name] )) {
			return new $config[$name]['class']( $config[$name] );
		}

		return null;
	}

	/**
	 * Create all configured analysers
	 *
	 * @since 01
	 *
	 * @param array $config overrides default config
	 *
	 * @return an array of analyzers
	 */
	public function makeAllAnalyzers( $config = null ) {
		global $wgContributors;
		if ( !isset( $config ) ) {
			$config = $wgContributors['analyzers'];
		}

		$analyzers = array();

		foreach ( $config as $conf ) {
			if ( array_key_exists( 'class', $conf )) {
				$analyzers[] = new $conf['class']( $conf );
			}
		}

		return $analyzers;
	}

	/**
	 * Create single synthesizer
	 *
	 * @since 01
	 *
	 * @param array $config overrides default config
	 *
	 * @return a synthesizer
	 */
	public function makeSynthesizer( $config = null ) {
		global $wgContributors;
		if ( !isset( $config ) ) {
			$config = $wgContributors['synthesizer'];
		}

		if ( array_key_exists( 'class', $config )) {
			return new $config[$name]['class']( $config );
		}

		return null;
	}

	/**
	 * Create single filter
	 *
	 * @since 01
	 *
	 * @param array $config overrides default config
	 *
	 * @return a filter
	 */
	public function makeFilter( $config = null ) {
		global $wgContributors;
		if ( !isset( $config ) ) {
			$config = $wgContributors['filter'];
		}

		if ( array_key_exists( 'class', $config )) {
			return new $config[$name]['class']( $config );
		}

		return null;
	}

	/**
	 * Create single history loader
	 *
	 * @since 01
	 *
	 * @param array $config overrides default config
	 *
	 * @return a history loader
	 */
	public function makeHistoryLoader( $config = null ) {
		global $wgContributors;
		if ( !isset( $config ) ) {
			$config = $wgContributors['history-loader'];
		}

		if ( array_key_exists( 'class', $config )) {
			return new $config[$name]['class']( $config );
		}

		return null;
	}

	/**
	 * Create single revision loader
	 *
	 * @since 01
	 *
	 * @param array $config overrides default config
	 *
	 * @return a list loader
	 */
	public function makeRevisionLoader( $config = null ) {
		global $wgContributors;
		if ( !isset( $config ) ) {
			$config = $wgContributors['revision-loader'];
		}

		if ( array_key_exists( 'class', $config )) {
			return new $config[$name]['class']( $config );
		}

		return null;
	}

}

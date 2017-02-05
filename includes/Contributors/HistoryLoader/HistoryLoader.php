<?php

namespace Contributors\HistoryLoader;

/**
 * Loader for a complete hiustory
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
class HistoryLoader implements IHistoryLoader {
	protected $dbr;

	public function __construct() {
		$dbr = wfGetDB( DB_SLAVE );
	}

	/**
	 * @see ILoader::fetch
	 */
	public function fetch( /* integer */ $id ) {
		return \Revision::loadFromId( $this->dbr, intval( $id ) );
	}

}

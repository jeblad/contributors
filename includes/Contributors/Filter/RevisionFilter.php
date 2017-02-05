<?php

namespace Contributors\Filter;

/**
 * Store for an articles history entries
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
class RevisionFilter extends \ContextSource {
	protected $revisionList;
	protected $limit;

	public function __construct( \IContextSource $context = null ) {
		if ( $context ) {
			$this->setContext( $context );
		}
		$request = $this->getRequest();
		$this->limit = $request->getInt( 'limit', 20 );
	}

	/**
	 * Set the revision list
	 *
	 * This method is mainly for testing purposes
	 *
	 * @since 0.1
	 *
	 * @param \RevisionList $revisionList
	 */
	public function setRevisionList( \RevisionList $revisionList ) {
		$this->revisionList = $revisionList;
	}

	/**
	 * Get the revision list
	 *
	 * This method will set up the revision list, but will not request
	 * the data before the list is actually used.
	 *
	 * @since 0.1
	 *
	 * @return \RevisionList
	 */
	public function getRevisionList() {
		if ( !isset( $this->revisionList ) ) {
			$this->revisionList = new RevisionList( $this->getContext(), $this->getTitle() );
		}
		return $this->revisionList;
	}

	/**
	 * Make a digest list from a revision list
	 *
	 * @since 0.1
	 *
	 * @param \RevisionList $revisionList
	 *
	 * @return array
	 */
	public static function makeDigestList( \RevisionList $revisionList ) {
		$digestList = array();
		$revisionList->reset();
		while ( $item = $revisionList->current() ) {
			// always point to the oldest
			//if ( !array_key_exists( $item->getSha1(), $digestList ) ) {
				$digestList[$item->getSha1()] = $item;
			//}
			$revisionList->next();
		}
		return $digestList;
	}

	/**
	 * Make a plookup list from a revision list
	 *
	 * @since 0.1
	 *
	 * @param \RevisionList $revisionList
	 *
	 * @return array
	 */
	public static function makeLookupList( \RevisionList $revisionList ) {
		$lookupList = array();
		$revisionList->reset();
		while ( $item = $revisionList->current() ) {
			$lookupList[$item->getId()] = $item;
			$revisionList->next();
		}
		return $lookupList;
	}

	/**
	 * Make a condensed list from a revision list
	 *
	 * @since 0.1
	 *
	 * @param \RevisionList $revisionList
	 * @param array $digestList
	 *
	 * @return array
	 */
	public static function makeCondensedList( \RevisionList $revisionList, &$digestList = null ) {
		$digestList = isset( $digestList ) ? $digestList : self::makeDigestList( $revisionList );
		$revisionList->reset();
		$condensedList = array();
		if ( $revisionList->current() !== false ) {
			$condensedList[] = $revisionList->current();
			$revisionList->next();
		}
		while ( $item = $revisionList->current() ) {
			if ( array_key_exists( $item->getSha1(), $digestList ) ) {
				$stop = $digestList[$item->getSha1()];
				while ( $stop !== $revisionList->current() ) {
					$item = $revisionList->next();
				}
			}
			if ( isset( $item ) ) {
				$condensedList[] = $item;
				$revisionList->next();
			}
		}
		return $condensedList;
	}

	/**
	 * Make a prospect list from a revision list
	 *
	 * @since 0.1
	 *
	 * @param \RevisionList $revisionList
	 *
	 * @return array
	 */
	public static function makeProspectList( \RevisionList $revisionList, &$digestList = null, &$lookupList = null, &$condensedList = null) {
		$lookupList = isset( $lookupList ) ? $lookupList : self::makeLookupList( $revisionList );
		$condensedList = isset( $condensedList ) ? $condensedList : self::makeCondensedList( $revisionList, $digestList );
		$revisionList->reset();
		$amountList = array();
		foreach ( $condensedList as $item ) {
			if ( !array_key_exists( $item->getAuthorId(), $amountList ) ) {
				$amountList[$item->getAuthorId()] = 0;
			}
			if ( array_key_exists( $item->getParentId(), $lookupList ) ) {
				$amountList[$item->getAuthorId()] +=
					abs( $item->getLength() - $lookupList[$item->getParentId()]->getLength() );
			}
			else {
				$amountList[$item->getAuthorId()] +=
					abs( $item->getLength() );
			}
		}
		uasort(
			$amountList,
			function( $a, $b ) {
				return $a < $b;
			}
		);
		return array_keys( $amountList );
	}

	/**
	 * Make a id list from a revision list
	 *
	 * @since 0.1
	 *
	 * @param \RevisionList $revisionList
	 *
	 * @return array
	 */
	public static function makeIdList( \RevisionList $revisionList, &$digestList = null, &$lookupList = null, &$prospectList = null, &$condensedList = null ) {
		$condensedList = isset( $condensedList ) ? $condensedList : self::makeCondensedList( $revisionList, $digestList );
		$prospectList = isset( $prospectList ) ? $prospectList : self::makeProspectList( $revisionList, $digestList, $lookupList, $condensedList );
		$flippedProspects = array_flip( $prospectList );
		$revisionList->reset();
		$idList = array();
		$lastItem = null;
		foreach ( $condensedList as $item ) {
			if ( !isset( $lastItem ) ) {
				$idList[] = $item->getId();
			}
			elseif ( array_key_exists( $item->getAuthorId(), $flippedProspects ) ) {
				if ( $lastItem->getAuthorId() !== $item->getAuthorId() ) {
					$idList[] = $item->getId();
				}
			}
			else {
				if ( array_key_exists( $lastItem->getAuthorId(), $flippedProspects ) ) {
					$idList[] = $item->getId();
				}
			}
			$lastItem = $item;
		}
		return $idList;
	}

	/**
	 * Fetch the id list from a revision list
	 *
	 * @since 0.1
	 *
	 * @param \RevisionList $revisionList
	 *
	 * @return array
	 */
	public function fetchIdLists() {
		$revisionList = $this->getRevisionList();
		$lookupList = self::makeLookupList(
			$revisionList
		);
		$digestList = self::makeDigestList(
			$revisionList
		);
		$condensedList = self::makeCondensedList(
			$revisionList,
			$digestList
		);
		$prospectList = self::makeProspectList(
			$revisionList,
			$digestList,
			$lookupList,
			$condensedList
		);
		$truncatedList = array_slice( $prospectList, 0, $this->limit );
		$idList = self::makeIdList(
			$revisionList,
			$digestList,
			$lookupList,
			$truncatedList,
			$condensedList
		);
		return array( $idList, $truncatedList );
	}

	public function doAnalyzeRevisions( array $analyzers ) {
		list( $idList, $truncatedList ) = $this->fetchIdList();
		$idList = array_reverse( $idList );
		$result = array();
		$dbr = wfGetDB( DB_SLAVE );
		$lastAnalysis = null;
		foreach ( $idList as $id ) {
			$revision = \Revision::loadFromId( $dbr, intval( $id ) );
			$text = $revision->getText( \Revision::RAW );
			$reductions = array();
			// TODO: missing stuff here
			foreach ( $analyzers as $analyzer ) {
				$reductions = $analyzer->reduce( $text );
			}
		}
	}
}
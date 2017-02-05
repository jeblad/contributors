<?php

namespace Contributors\Test;

use Contributors\Filter\RevisionFilter;

/**
 * Test Contributors\RevisionFilter.
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
class RevisionFilterTest extends \MediaWikiTestCase {

	/**
	 * @dataProvider provideCreate
	 */
	public function testCreate( $data ) {
		$facade = new RevisionFilter();
		$facade->setRevisionList( new MockRevisionList( $data ) );
		$revisionList = $facade->getRevisionList();
		$this->assertInstanceOf( 'Contributors\Test\MockRevisionList', $revisionList );
		$revisionList->reset();
		while ( $item = $revisionList->current() ) {
			$this->assertInstanceOf( 'Contributors\Test\MockRevisionItem', $item );
			$revisionList->next();
		}
	}

	function provideCreate() {
		$dataList = array();
		foreach ( $this->mockData() as $data ) {
			$dataList[] = array( $data );
		}
		return $dataList;
	}

	/**
	 * @dataProvider provideDigest
	 */
	public function testDigest( $data, $expected ) {
		$mockRevisions = new MockRevisionList( $data );
		$digestList = array_map(
			function( $a ) { return $a->getSha1(); },
			RevisionFilter::makeDigestList( $mockRevisions )
		);
		$this->assertEquals( $expected, array_values( $digestList ) );
	}

	function provideDigest() {
		$dataList = array();
		foreach ( $this->mockData() as $data ) {
			$tmp = array();
			foreach ( $data as $args) {
				$tmp[] = $args['sha1'];
			}
			$dataList[] = array( $data, array_values( array_unique( $tmp ) ) );
		}
		return $dataList;
	}

	/**
	 * @dataProvider provideLookupId
	 */
	public function testLookupId( $data, $expected ) {
		$mockRevisions = new MockRevisionList( $data );
		$lookupList = array_map(
			function( $a ) { return $a->getId(); },
			RevisionFilter::makeLookupList( $mockRevisions )
		);
		$this->assertEquals( $expected, array_values( $lookupList ) );
	}

	function provideLookupId() {
		$dataList = array();
		foreach ( $this->mockData() as $data ) {
			$tmp = array();
			foreach ( $data as $args) {
				$tmp[] = $args['id'];
			}
			$dataList[] = array( $data, $tmp );
		}
		return $dataList;
	}

	/**
	 * @dataProvider provideCondensed
	 */
	public function testCondensed( $data, $expected ) {
		$mockRevisions = new MockRevisionList( $data );
		$condensedList = array_map(
			function( $a ) { return isset( $a ) ? $a->getId() : null; },
			RevisionFilter::makeCondensedList( $mockRevisions )
		);
		$this->assertEquals( $expected, array_values( $condensedList ) );
	}

	function provideCondensed() {
		$extList = array(
			array( // #0
			),
			array( // #1
				1
			),
			array( // #2
				2, 1
			),
			array( // #3
				4, 1
			),
			array( // #4
				5, 2, 1
			),
			array( // #5
				7, 6, 5, 4, 3, 2, 1
			),
			array( // #6
				5160, 5159, 4078, 3375, 1
			),
		);
		$dataList = array();
		$idx = 0;
		foreach ( $this->mockData() as $data ) {
			$dataList[] = array( $data, $extList[$idx++] );
		}
		return $dataList;
	}

	/**
	 * @dataProvider provideProspect
	 */
	public function testProspect( $data, $expected ) {
		$mockRevisions = new MockRevisionList( $data );
		$prospectList = RevisionFilter::makeProspectList( $mockRevisions );
		$this->assertEquals( $expected, array_values( $prospectList ) );
	}

	function provideProspect() {
		$extList = array(
			array( // #0
			),
			array( // #1
				1
			),
			array( // #2
				1
			),
			array( // #3
				1
			),
			array( // #4
				1
			),
			array( // #5
				3, 2, 1, 0
			),
			array( // #6
				0, 1
			),
		);
		$dataList = array();
		$idx = 0;
		foreach ( $this->mockData() as $data ) {
			$dataList[] = array( $data, $extList[$idx++] );
		}
		return $dataList;
	}

	/**
	 * @dataProvider provideId
	 */
	public function testId( $data, $expected ) {
		$mockRevisions = new MockRevisionList( $data );
		$idList = RevisionFilter::makeIdList( $mockRevisions );
		$this->assertEquals( $expected, array_values( $idList ) );
	}

	function provideId() {
		$extList = array(
			array( // #0
			),
			array( // #1
				1
			),
			array( // #2
				2
			),
			array( // #3
				4
			),
			array( // #4
				5
			),
			array( // #5
				7, 5, 3, 1
			),
			array( // #6
				5160, 4078, 1
			),
		);
		$dataList = array();
		$idx = 0;
		foreach ( $this->mockData() as $data ) {
			$dataList[] = array( $data, $extList[$idx++] );
		}
		return $dataList;
	}

	public function mockData() {
		return array(
			array( // #0
			),
			array( // #1
				array( 'id' => 1, 'author-id' => 1, 'sha1' => 'abc', 'parent-id' => null, 'length' => 0 )
			),
			array( // #2
				array( 'id' => 2, 'author-id' => 1, 'sha1' => 'def', 'parent-id' => 1, 'length' => 20 ),
				array( 'id' => 1, 'author-id' => 1, 'sha1' => 'abc', 'parent-id' => null, 'length' => 10 )
			),
			array( // #3
				array( 'id' => 4, 'author-id' => 1, 'sha1' => 'ghi', 'parent-id' => 3, 'length' => 15 ),
				array( 'id' => 3, 'author-id' => 1, 'sha1' => 'abc', 'parent-id' => 2, 'length' => 10 ), // revert
				array( 'id' => 2, 'author-id' => 1, 'sha1' => 'def', 'parent-id' => 1, 'length' => 20 ),
				array( 'id' => 1, 'author-id' => 1, 'sha1' => 'abc', 'parent-id' => null, 'length' => 10 )
			),
			array( // #4
				array( 'id' => 5, 'author-id' => 1, 'sha1' => 'jkl', 'parent-id' => 4, 'length' => 25 ),
				array( 'id' => 4, 'author-id' => 1, 'sha1' => 'def', 'parent-id' => 3, 'length' => 20 ), // revert
				array( 'id' => 3, 'author-id' => 1, 'sha1' => 'ghi', 'parent-id' => 2, 'length' => 30 ),
				array( 'id' => 2, 'author-id' => 1, 'sha1' => 'def', 'parent-id' => 1, 'length' => 20 ),
				array( 'id' => 1, 'author-id' => 1, 'sha1' => 'abc', 'parent-id' => null, 'length' => 10 )
			),
			array( // #5
				array( 'id' => 7, 'author-id' => 3, 'sha1' => 'stu', 'parent-id' => 6, 'length' => 80 ),
				array( 'id' => 6, 'author-id' => 3, 'sha1' => 'pqr', 'parent-id' => 5, 'length' => 60 ),
				array( 'id' => 5, 'author-id' => 1, 'sha1' => 'mno', 'parent-id' => 4, 'length' => 45 ),
				array( 'id' => 4, 'author-id' => 1, 'sha1' => 'jkl', 'parent-id' => 3, 'length' => 35 ),
				array( 'id' => 3, 'author-id' => 2, 'sha1' => 'ghi', 'parent-id' => 2, 'length' => 30 ),
				array( 'id' => 2, 'author-id' => 2, 'sha1' => 'def', 'parent-id' => 1, 'length' => 20 ),
				array( 'id' => 1, 'author-id' => 0, 'sha1' => 'abc', 'parent-id' => null, 'length' => 10 )
			),
			array( // #6
				array( 'id' => 5160,'parent-id' => 5159,'author-id' => 0,'sha1' => 'kfw4fg2spa2q5t9kwplk422mhnms6i5', 'length' => 50 ),
				array( 'id' => 5159,'parent-id' => 4078,'author-id' => 0,'sha1' => '41tj24xceijhemn2pkbsfw3fj10yviv', 'length' => 40 ),
				array( 'id' => 4078,'parent-id' => 3375,'author-id' => 1,'sha1' => '9g7oc0w5dqno0jzi5sdd70x82xx23mo', 'length' => 30 ),
				array( 'id' => 3375,'parent-id' => 1,'author-id' => 1,'sha1' => 'jcvqrhl37ngjb1at6tlej2ji51mmdj8', 'length' => 20 ),
				array( 'id' => 1,'author-id' => 0,'sha1' => 'c3gchhvaaxh8pbro7ta0bvbj2slrbyl', 'length' => 10 ), // note parent id is missing
			),
		);
	}

}

class MockRevisionItem extends \RevisionItem {
	protected $data;
	function __construct( array $data  ) {
		$this->data = $data;
	}
	public function getId() {
		return $this->data['id'];
	}
	public function getAuthorId() {
		return $this->data['author-id'];
	}
	public function getSha1() {
		return $this->data['sha1'];
	}
	public function getParentId() {
		return array_key_exists( 'parent-id', $this->data ) ? $this->data['parent-id'] : null;
	}
	public function getLength() {
		return $this->data['length'];
	}
}

class MockRevisionList extends \RevisionList {
	protected $data;
	function __construct( array $data  ) {
		$this->data = array();
		foreach ( $data as $item) {
			$this->data[] = new MockRevisionItem( $item );
		}
	}
	public function reset() {
		return reset($this->data);
	}
	public function current() {
		return current($this->data);
	}
	public function next() {
		return next($this->data);
	}
}
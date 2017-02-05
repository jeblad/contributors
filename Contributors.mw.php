<?php

/**
 * MediaWiki setup for the Contributors extension.
 * The extension should be included via the main entry point, Contributors.php.
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

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

global $wgExtensionCredits, $wgExtensionMessagesFiles, $wgAutoloadClasses, $wgHooks;

$wgExtensionCredits['other'][] = include( __DIR__ . '/Contributors.credits.php' );

$wgExtensionMessagesFiles['ContributorsExtension'] = __DIR__ . '/Contributors.i18n.php';
//$wgExtensionMessagesFiles['ContributorsExtensionMagic'] = __DIR__ . '/Contributors.i18n.magic.php';


// Autoloading
foreach ( include( __DIR__ . '/Contributors.classes.php' ) as $class => $file ) {
	$wgAutoloadClasses[$class] = __DIR__ . '/' . $file;
}

if ( defined( 'MW_PHPUNIT_TEST' ) ) {
	$wgAutoloadClasses['Contributors\Tests\ContributorsTestCase']
		= __DIR__ . '/tests/phpunit/ContributorsTestCase.php';
}

// Register the parser function.
/*
$wgHooks['ParserFirstCallInit'][] = function ( &$parser ) {
	$parser->setFunctionHook( 'quote', '\Contributors\Quote::handler', SFH_NO_HASH );
	return true;
};
*/

// Register the magic word.
/*
$wgHooks['MagicWordwgVariableIDs'][] = function ( &$aCustomVariableIds ) {
	$aCustomVariableIds[] = 'quote';
	return true;
};
*/

// Apply the magic word.'
/*
$wgHooks['ParserGetVariableValueSwitch'][] = function ( &$parser, &$cache, &$magicWordId, &$ret ) {
	if( $magicWordId == 'quote' ) {
		ParserFunction::quoteHandler( $parser, '*' );
	}
	return true;
};
*/
// The key is your job identifier (from the Job constructor), the value is your class name
//$wgJobClasses['Validation'] = 'Contributors\ValidationJob';
//$wgJobClasses['ChangeNotification'] = 'Wikibase\ChangeNotificationJob';

/**
 * Hook to add PHPUnit test cases.
 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList
 *
 * @since 0.1
 *
 * @param array $files
 *
 * @return boolean
 */
$wgHooks['UnitTestsList'][]	= function( array &$files ) {
	// @codeCoverageIgnoreStart
	$testFiles = array(
		'Contributors/Analyzer/TupletAnalyzer',
		'Contributors/Filter/RevisionFilter',
		'Contributors/Decorator',
	);

	foreach ( $testFiles as $file ) {
		$files[] = __DIR__ . '/tests/phpunit/includes/' . $file . 'Test.php';
	}

	return true;
	// @codeCoverageIgnoreEnd
};

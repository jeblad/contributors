<?php
/**
 * Initialization file for the Contributors library.
 *
 * Documentation:	 		https://www.mediawiki.org/wiki/Extension:Contributors
 * Support					https://www.mediawiki.org/wiki/Extension_talk:Contributors
 * Source code:				https://gerrit.wikimedia.org/r/gitweb?p=mediawiki/extensions/Contributors.git
 *
 * @file
 * @ingroup Contributors
 *
 * @licence GNU GPL v2+
 * @author John Erling Blad < jeblad@gmail.com >
 */

$wgContributors['analyzers'] = array(
	'triplets' => array(
		'class' => '\Contributors\Analyzer\TupletAnalyzer',
		'size'=> 3
	),
);

/**
 * This documentation group collects source code files belonging to Contributors.
 *
 * @defgroup Contributors Contributors
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( version_compare( $wgVersion, '1.20c', '<' ) ) { // Needs to be 1.20c because version_compare() works in confusing ways.
	die( '<b>Error:</b> Wikibase requires MediaWiki 1.20 or above.' );
}

$dir = dirname(__FILE__) . '/';

$wgAutoloadClasses['Contributors\ContributorsFactory'] = $dir . 'Contributors.factory.php';
$wgAutoloadClasses['Contributors\ContributorsHooks'] = $dir . 'Contributors.hooks.php';
$wgExtensionMessagesFiles['Contributors'] = $dir . 'Contributors.i18n.php';
$wgExtensionMessagesFiles['ContributorsAlias'] = $dir . 'Contributors.i18n.alias.php';
$wgSpecialPages['Contributors'] = 'Contributors\SpecialContributors';
$wgSpecialPageGroups['Contributors'] = 'pages';

//$wgHooks['SkinTemplateBuildNavUrlsNav_urlsAfterPermalink'][] = '\Contributors\ContributorsHooks::onSkinTemplateBuildNavUrls';
//$wgHooks['SkinTemplateToolboxEnd'][] = '\Contributors\ContributorsHooks::onSkinTemplateToolboxEnd';
$wgHooks['BaseTemplateToolbox'][] = '\Contributors\ContributorsHooks::onBaseTemplateToolbox';

/**
 * Tests part of the Contributors extension.
 *
 * @defgroup ContributorsTests ContributorsTest
 * @ingroup Contributors
 * @ingroup Test
 */

define( 'Contributors_VERSION', '0.1 alpha' );

// @codeCoverageIgnoreStart
call_user_func( function() {
	require_once __DIR__ . '/Contributors.mw.php';
} );
// @codeCoverageIgnoreEnd


<?php

/**
 * Class registration file for the Contributors extension.
 *
 * @since 0.1
 *
 * @file
 * @ingroup Contributors
 *
 * @licence GNU GPL v2+
 * @author John Erling Blad < jeblad@gmail.com >
 */
return call_user_func( function() {

	// PSR-0 compliant :)

	$classes = array(
		'Contributors\Analyzer\IAnalyzer',
		'Contributors\Analyzer\TupletAnalyzer',
		'Contributors\Filter\RevisionFilter',
		'Contributors\Decorator',
		'Contributors\SpecialContributors',
	);

	$paths = array();

	foreach ( $classes as $class ) {
		$path = 'includes/' . str_replace( '\\', '/', $class ) . '.php';

		$paths[$class] = $path;
	}

	return $paths;

} );


<?php
namespace Contributors;

//use Contributors;

/**
 * File defining the hook handlers for the Contributors extension.
 *
 * @since 0.1
 *
 * @file
 * @ingroup Contributors
 *
 * @licence GNU GPL v2+
 *
 * @author John Erling Blad < jeblad@gmail.com >
 */

final class ContributorsHooks {

	/**
	 * Add a linkt to the special page in the toolbox
	 *
	 * @since 0.4
	 *
	 * @param SkinTemplate &$sk
	 * @param array &$toolbox
	 *
	 * @return boolean
	 */
	public static function onBaseTemplateToolbox( &$sk, &$toolbox ) {
		if ( $sk->getSkin()->getTitle()->getNamespace() === NS_MAIN ) {
			$toolbox['wikibase'] = array(
				'text' => $sk->getMsg( 'contributors-toolbox' )->text(),
				'href' => $sk->getSkin()->makeSpecialUrl( 'Contributors', "target=" . wfUrlEncode( $sk->getSkin()->thispage ) ),
				'id' => 't-contributors'
			);
		}
		return true;
	}
}
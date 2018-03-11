<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( SSC_MODS_PLUGIN_DIR . '/classes/SSCModsFactory.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/display/FullEventsTable.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/display/EventsPage.php' );

/**
 * Display sailing programme in place of post content for sailing content type.
 *
 * @todo simplify and harmonise with code used in the programme CLI
 *
 * @param $content
 *
 * @return string
 */
function ssc_mods_display_sailing_programme( $content ) {

	/**
	 * @post WP_Post
	 */
	global $post;

	$out = '';

	if ( is_singular() && in_array( get_post_type( $post ), array( 'sailing-programme' ) ) ) {

		try {

			$contentParser = \SSCMods\SSCModsFactory::getContentParser( );

			$contentParser->init(
				$post->post_content,
				\SSCMods\SSCModsFactory::getSailType(),
				\SSCMods\SSCModsFactory::getRaceSeries(),
				\SSCMods\SSCModsFactory::getSafetyTeams()
			);

			$eventsData = $contentParser->getData( new \SSCMods\NullFilter() );

		} catch ( Exception $e ) {
			return '<strong>Exception: ' . $e->getMessage() . ', Line: ' . $e->getLine() . '</strong><br/>';
		}


		$out .= \SSCMods\EventsPage::getPageHead();
		$out .= \SSCMods\EventsPage::displayErrors( $eventsData['errors'] );
		$out .= \SSCMods\FullEventsTable::getCSS();

		if ( $eventsData['data'] ) {
			$out .= \SSCMods\FullEventsTable::getOpenTableTag();
			$out .= \SSCMods\FullEventsTable::getHeader();

			foreach ( $eventsData['data'] as $date => $DTOArray ) {
				$day = new \SSCMods\Day();
				foreach ( $DTOArray as $DTO ) {
					$day->addEvent( $DTO );
				}
				$out .= \SSCMods\FullEventsTable::getRow( $day );
			}

			$out .= \SSCMods\FullEventsTable::getClosingTag();
		} else {
			//$out .= EventsPage::displayNoResultsMessage();
		}
		$out .= \SSCMods\EventsPage::getPageFooter();

		if ( ! empty( $eventsData['errors'] ) ) {

			$out .= \SSCMods\EventsPage::displayErrors( $eventsData['errors'] );

		}

		return $out;
	}

	return $content;
}

add_filter( 'the_content', 'ssc_mods_display_sailing_programme' );
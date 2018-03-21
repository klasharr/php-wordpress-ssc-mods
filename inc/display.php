<?php

use SSCMods\SSCModsFactory;
use SSCMods\NullFilter;
use SSCMods\EventsPage;
use SSCMods\FullEventsTable;
use SSCMods\Day;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( SSC_MODS_PLUGIN_DIR . '/classes/SSCModsFactory.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/display/FullEventsTable.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/display/EventsPage.php' );

/**
 * @param $content
 *
 * @return string
 */
function ssc_mods_display_sailing_programme( $content ) {

	/**
	 * @post WP_Post
	 */
	global $post;

	if ( is_singular() && in_array( get_post_type( $post ), array( 'sailing-programme' ) ) ) {

		try {

			if ( $post_meta_fields = get_post_meta( $post->ID, 'fields', true ) ) {
				$post->field_settings = parse_ini_string( $post_meta_fields, true );
			}

			$contentParser = SSCModsFactory::getContentParser();

			$contentParser->init(
				$post,
				SSCModsFactory::getSailType(),
				SSCModsFactory::getRaceSeries(),
				SSCModsFactory::getSafetyTeams()
			);

			$eventsData = $contentParser->getData( new NullFilter() );

		} catch ( Exception $e ) {
			return 'There has been an error: ' . $e->getMessage() . ', Line: ' . $e->getLine() . '<br/>';
		}


		$out = EventsPage::getPageHead();
		if ( ! empty( $eventsData['errors'] ) ) {
			$out .= EventsPage::displayErrors( $eventsData['errors'] );
		}
		$out .= FullEventsTable::getCSS();

		if ( $eventsData['data'] ) {
			$out .= FullEventsTable::getOpenTableTag();
			$out .= FullEventsTable::getHeader();

			$line = 0;
			foreach ( $eventsData['data'] as $date => $DTOArray ) {
				$day = new Day();
				foreach ( $DTOArray as $DTO ) {
					$day->addEvent( $DTO );
				}
				$out .= FullEventsTable::getRow( $day, $line, $eventsData['errors'] );
				$line ++;
			}

			$out .= FullEventsTable::getClosingTag();
		} else {
			//$out .= EventsPage::displayNoResultsMessage();
		}
		$out .= EventsPage::getPageFooter();

		return $out;
	}

	return $content;

}

add_filter( 'the_content', 'ssc_mods_display_sailing_programme' );
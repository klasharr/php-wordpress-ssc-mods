<?php

function ssc_mods_display_sailing_programme( $content ) {

	/**
	 * @post WP_Post
	 */
	global $post;

	$out = '';

	if( is_singular() && in_array( get_post_type( $post ), array( 'sailing-programme' ) ) ) {

		include_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/EventDTO.php' );
		include_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/Day.php' );
		include_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/display/FullEventsTable.php' );
		include_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/display/EventsPage.php' );
		include_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/mappers/SailType.php' );
		include_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/mappers/RaceSeries.php' );
		include_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/SailingEventForm.php' );
		include_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/ContentParser.php' );
		include_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/SailTypeFilter.php' );

		try {

			$form = new SailingEventForm( new safetyTeams, new SailType );

			$safetyTeams = new SafetyTeams();
			$sailType    = new SailType();

			$sailFilter = new SailTypeFilter( $safetyTeams, $sailType, $form->getSailEventTypesSelected(),
				$form->getTeamsSelected()
			);

			$contentParser  = new ContentParser( $content, $sailType, new RaceSeries, $safetyTeams );
			$eventsData = $contentParser->getData( $sailFilter );

			// add another validation step here.

		} catch ( Exception $e ) {
			return  '<strong>Exception: ' . $e->getMessage() . ' File:' . $e->getFile() . ', Line: ' . $e->getLine() . '</strong><br/>';
		}



		$out .= EventsPage::getPageHead();
		$out .= EventsPage::displayErrors( $eventsData['errors'] );
		//$out .= EventsPage::getForm( $form );
		$out .= FullEventsTable::getCSS();

		if ( $eventsData['data'] ) {
			$out .= FullEventsTable::getOpenTableTag();
			$out .= FullEventsTable::getHeader();

			foreach ( $eventsData['data'] as $date => $DTOArray ) {
				$day = new day();
				foreach ( $DTOArray as $DTO ) {
					$day->addEvent( $DTO );
				}
				$out .= FullEventsTable::getRow( $day );
			}

			$out .= FullEventsTable::getClosingTag();
		} else {
			//$out .= EventsPage::displayNoResultsMessage();
		}
		$out .= EventsPage::getPageFooter();

		if(!empty($eventsData['errors'])){

			$out .= EventsPage::displayErrors( $eventsData['errors'] );

		}
		return $out;
	}

	return $content;
}

add_filter('the_content', 'ssc_mods_display_sailing_programme');
<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \SSCMods\SSCModsFactory;
use \SSCMods\FullEventsTable;
use \SSCMods\NullFilter;
use \SSCMods\EventsPage;
use \SSCMods\Day;
use \SSCMods\TrainingFilter;

require_once( 'SSCModsBase.php' );

require_once( SSC_MODS_PLUGIN_DIR . '/classes/SSCModsFactory.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/display/FullEventsTable.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/display/EventsPage.php' );

class SSCProgramme extends SSCModsBase {

	public function __construct() {
	}

	/**
	 * @param $args
	 * @param null $content
	 */
	function displayShortCode( $args, $content = null ) {

		$atts = shortcode_atts( array(
			'id' => null
		), $args );

		$id = $atts['id'];

		if ( empty( $id ) || ! is_numeric( $id ) ) {
			return 'Error : no ID value passed to shortcode';
		}

		try {

			$post = get_post($id);

			if(empty($post)){
				throw new Exception('invalid post');
			}

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

			$eventsData = $contentParser->getData( new TrainingFilter() );

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



		/**
		$this->display( 'sailing-programme-full.php',
			array(
				'a' => $a,
			),
			'templates/'
		);
		 * */
	}
}
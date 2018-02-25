<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( SSC_MODS_PLUGIN_DIR . 'classes/SSCResults.php' );
include_once( SSC_MODS_PLUGIN_DIR . 'classes/SSCProgramme.php' );

add_shortcode( 'ssc_results', array( new SSCResults, 'displayShortCode' ) );
add_shortcode( 'ssc_programme', array( new SSCProgramme, 'displayShortCode' ) );
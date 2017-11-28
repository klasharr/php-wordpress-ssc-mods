<?php

include_once( SSC_MODS_PLUGIN_DIR . 'classes/SSCResults.php' );

add_shortcode( 'ssc_results', array( new SSCResults, 'getShortCode' ) );

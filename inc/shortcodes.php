<?php

include_once( SSC_MODS_PLUGIN_DIR . 'classes/SSCResults.php' );

/**
 * @param $atts
 * @param null $content
 *
 * [ssc_race_results url="http://results.swanagesailingclub.org.uk/list/" count=10 format="full']
 *
 * @return string
 */
function ssc_mods_race_results( $args, $content = null ) {

	$atts = shortcode_atts( array(
		'url'    => null,
		'count'  => SSC_MODS_RESULTS_DEFAULT_COUNT,
		'format' => 'concise',
	), $args );

	$url = $atts['url'];

	if ( empty( $url ) ) {
		return;
	}

	$count = $atts['count'];

	/**
	 * @todo different formats for main page content and sidebar, or just make a widget
	 */
	//$format = $atts['format'];

	/**
	 * var $race_results SSCResults
	 */
	$race_results = new SSCResults( $url );
	return $race_results->getOutput();
}
// @todo, move the function body above into the class too.
add_shortcode( 'ssc_results', 'ssc_mods_race_results' );

<?php
/*
 Plugin Name: SSC Mods
 Plugin URI: TBD
 Description:
 Author: Klaus Harris
 Version: -
 Author URI: https://klaus.blog
 Text Domain: ssc-mods
 */

define( SSC_MODS_RESULTS_DEFAULT_COUNT, 5 );

/**
 * @param $atts
 * @param null $content
 *
 * [ssc_race_results url="http://results.swanagesailingclub.org.uk/list/" count=10 format="full']
 *
 * @return string
 */
function ssc_mods_race_results( $args, $content = null ) {

	$atts =  shortcode_atts( array(
		'url' => null,
		'count' => SSC_MODS_RESULTS_DEFAULT_COUNT,
		'format' => 'concise',
	), $args );

	$url = $atts['url'];

	if(empty($url)){
		return;
	}

	$count = $atts['count'];
	//$format = $atts['format'];

	return ssc_mods_get_results($url, $count);
}
add_shortcode('ssc_results', 'ssc_mods_race_results');


/**
 * @param $url
 * @param $count
 *
 * @return string
 */
function ssc_mods_get_results($url, $count = SSC_MODS_RESULTS_DEFAULT_COUNT){

	// Not adding caching here yet as the pages are full page cached with long durations
	// @todo
	$response = wp_remote_get( $url );
	if($response instanceof WP_Error ){
		return $response->get_error_message();
	}

	if ( is_array( $response ) ) {
		$body = $response['body'];
		if(empty($body)){
			return 'Empty body';
		}
	}

	$data = json_decode($body);
	if(!empty($data->error)){
		return 'Invalid data [0]';
	}

	if($data->error == 1 ){
		return 'Error: '. $data->data;
	}

	if(!is_array($data->data)){
		return 'Invalid data [1]';
	}

	if(empty($data->data)){
		return 'No results';
	}

	$i = 0;
	$out = '<h3>Recent Results</h3><ol>';
	foreach($data->data as $item){
		if( $i === $count) break;
		$out.= sprintf('<li><a href="%s">%s</a></li>',
			esc_url($item->link),
			esc_html($item->friendly_file_name)
		);
		$i ++;
	}
	$out .= '</ol>';

	return $out;
}
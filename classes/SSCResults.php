<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( 'SSCModsBase.php' );

class SSCResults extends SSCModsBase {

	const RESULTS_DEFAULT_COUNT = 5;
	const RESULTS_WP_ERROR_CODE = 239;

	private static $results;

	private $more_link;
	private $more_text;

	public function __construct() {
	}

	/**
	 * @todo different formats for main page content and sidebar, or just make a widget
	 *
	 * @param $args
	 * @param null $content
	 */
	function displayShortCode( $args, $content = null ) {

		$atts = shortcode_atts( array(
			'url'       => null,
			'count'     => self::RESULTS_DEFAULT_COUNT,
			'format'    => 'concise',
			'more_link' => null,
			'more_text' => 'See all results',
		), $args );

		$url             = $atts['url'];
		$count           = (int) $atts['count'];
		$format          = $atts['format'];
		$this->more_link = $atts['more_link'];
		$this->more_text = $atts['more_text'];

		if ( empty( $url ) || ! is_string( $url ) ) {
			return 'Error : no url value passed to shortcode';
		}

		if ( empty( $count ) || $count < 0 ) {
			return 'Error : the value of count passed to the shortcode must be > 0';
		}

		if ( ! in_array( $format, array( 'concise', 'full' ) ) ) {
			return 'Error : the value of format passed to the shortcode must be concise or full';
		}

		$data = $this->get( $url, $count );

		if ( $data instanceof WP_Error ) {
			return sprintf( 'Error : %s', $data->get_error_message() );
		}

		$this->display( 'results-list-full.php',
			array(
				'results'   => self::$results,
				'more_link' => $this->more_link,
				'more_text' => $this->more_text
			),
			'templates/'
		);
	}


	/**
	 * @param $url
	 *
	 * @todo check for existence of cache plugins and if absent, cache here.
	 *
	 */
	private function get( $url, $count ) {

		if ( ! empty( self::$results ) ) {
			return self::$results;
		}

		// @todo optionally cache here
		$response = $this->validateResponse(
			wp_remote_get( $url )
		);

		if ( $response instanceof WP_Error ) {
			return $response;
		}

		self::$results = array_slice( $response, 0, $count );

		return true;
	}


	private function validateResponse( $raw_response ) {

		if ( $raw_response instanceof WP_Error ) {
			return $raw_response;
		}

		// Possibly allow a 301
		if ( isset( $raw_response['response']['code'] ) && ! in_array( $raw_response['response']['code'], array( 200 ) ) ) {
			return new WP_Error(
				self::RESULTS_WP_ERROR_CODE,
				sprintf( 'error code %d returned from request', $raw_response['response']['code'] ) );
		}

		if ( is_array( $raw_response ) ) {
			$body = $raw_response['body'];
			if ( empty( $body ) ) {
				return new WP_Error( self::RESULTS_WP_ERROR_CODE, 'empty response' );
			}
		}

		$response = json_decode( $body );

		if ( ! empty( $response->error ) || ! in_array( (int) $response->error, array( 0, 1 ) ) ) {
			return new WP_Error( self::RESULTS_WP_ERROR_CODE, 'invalid response format' );
		}

		if ( 1 === (int) $response->error ) {
			return new WP_Error( self::RESULTS_WP_ERROR_CODE, $response->data );
		}

		if ( ! is_array( $response->data ) ) {
			return new WP_Error( self::RESULTS_WP_ERROR_CODE, 'invalid response format' );
		}

		return $response->data;
	}

}
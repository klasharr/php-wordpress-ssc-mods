<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;	
} 

include_once( 'SSCModsBase.php' );

class SSCProgramme extends SSCModsBase {

	public function __construct() {
	}

	/**
	 * @param $args
	 * @param null $content
	 */
	function displayShortCode( $args, $content = null ) {

		$atts = shortcode_atts( array(
			'a'    => null
		), $args );

		$a    = $atts['a'];

		if ( empty( $a ) || ! is_string( $a ) ) {
			return 'Error : no a value passed to shortcode';
		}

		/**
		$data = $this->get();

		if ( $data instanceof WP_Error ) {
			return sprintf( 'Error : %s', $data->get_error_message() );
		}
		 **/

		$this->display( 'sailing-programme-full.php',
			array(
				'a' => $a,
			),
			'templates/'
		);
	}
}
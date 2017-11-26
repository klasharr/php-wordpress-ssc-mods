<?php

class SSCResults {

	const RESULTS_DEFAULT_COUNT = 5;
	const RESULTS_WP_ERROR_CODE_CONSTRUCTOR_ERROR = 240;
	const RESULTS_WP_ERROR_CODE = 239;

	private $results;

	/**
	 * SSCResults constructor.
	 *
	 * @param $url
	 */
	public function __construct( $url ) {

		if ( empty( $url ) || ! is_string( $url ) ) {
			return new WP_Error( self::RESULTS_WP_ERROR_CODE_CONSTRUCTOR_ERROR, '$url must be called with a valid URL' );
		}

		return $this->get( $url );
	}

	/**
	 * @param $url
	 *
	 * @todo check for existence of cache plugins and if absent, cache here.
	 *
	 */
	private function get( $url ) {

		if ( ! empty( $this->results ) ) {
			return $this->results;
		}

		// @todo cache here
		$response = $this->validateResponse(
			wp_remote_get( $url )
		);

		if ( 1 === $response['error'] ) {
			$this->results = new WP_Error( self::RESULTS_WP_ERROR_CODE, $response['data'] );
		}

		$this->results = array_slice( $response['data'], 0, self::RESULTS_DEFAULT_COUNT );

		return $this->results;
	}

	/**
	 * @param $error string
	 */
	private function error( $message ) {
		return array(
			'error' => 1,
			'data'  => $message
		);
	}


	private function validateResponse( $raw_response ) {

		if ( $raw_response instanceof WP_Error ) {
			return $this->error(
				sprintf( 'Error: %s', $raw_response->get_error_message() )
			);
		}

		if ( is_array( $raw_response ) ) {
			$body = $raw_response['body'];
			if ( empty( $body ) ) {
				return $this->error( 'Error 2 : empty response' );
			}
		}

		$response = json_decode( $body );
		if ( ! empty( $response->error ) || ! in_array( (int) $response->error, array( 0, 1 ) ) ) {
			return $this->error( 'Error 3 : invalid response format' );
		}

		if ( 1 === (int) $response->error ) {
			return $this->error(
				sprintf( 'Error 4: %s', $response->data )
			);
		}

		if ( ! is_array( $response->data ) ) {
			return $this->error( 'Error 5 : invalid response format' );
		}

		return array(
			'error' => 0,
			'data'  => $response->data
		);
	}

	function getOutput() {

		if ( ! is_array( $this->results ) ) {
			return $this->error( 'Error 6 : incorrect' )
		}

		$out = '<ol>';
		foreach ( $this->results as $item ) {
			$out .= sprintf( '<li><a href="%s">%s</a></li>',
				esc_url( $item->link ),
				esc_html( $item->friendly_path )
			);
		}
		$out .= '</ol>';

		return $out;
	}
	
}
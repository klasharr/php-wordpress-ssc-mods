<?php

namespace SSCMods\Fields;

use WP_CLI;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once( 'BaseField.php' );
include_once( SSC_MODS_PLUGIN_DIR . '/interfaces/FieldValidator.php' );

class DateField extends BaseField implements FieldValidator {

	public function __construct( $data ) {
		parent::__construct( $data );
	}

	public function validate( $value ) {

		if ( empty( trim( $value ) ) ) {
			throw new ValidatorException( 'Date field validation failed, no data' );
		}

		$parts = explode('/', $value);

		if ( ! checkdate( $parts[0], $parts[1], $parts[2] ) ) {
			throw new ValidatorException('Date field validation failed, format is valid, invalid date. Got: ' . $value );
		}
		
		if( ! $d = \DateTime::createFromFormat( $this->data['format'], $value ) ) {
			throw new ValidatorException('Date field validation failed, expected format: '. $this->data['format'] . ', value is: ' . $value );
		}

	}

}
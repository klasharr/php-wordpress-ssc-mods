<?php

namespace SSCMods\Fields;

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
		parent::_validate( $value );
	}

}
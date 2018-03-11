<?php

namespace SSCMods\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

abstract class BaseField {

	/**
	 * @var $data array
	 */
	protected $data;

	/**
	 * @var $options array
	 */
	protected $options = array();

	/**
	 * @var $errorMessage string|bool
	 */
	private $errorMessage = false;

	/**
	 * @var bool
	 */
	private $required = false;

	public function __construct($data) {

		$this->data = $data;

		if(isset( $this->data['options' ] ) && !array( $this->data['options' ] ) ) {
			throw new \Exception('options is not an array' );
		}



		if( isset($this->data['options' ] ) ){
			$this->options = explode(',', $this->data['options' ]);
		}

		$this->type = $data['type'];

		if( !empty($this->data['required']) ){
			$this->required = true;
		}
	}

	protected function _validate( $value ) {

		if( $this->isRequired() && empty($value)){
			throw new \Exception('Data error in field '. $this->data['field_name'] . ' requires value' );
		}

		$this->stringHasValidLength( $value );
		$this->hasValidOption( $value );
	}

	private function hasValidOption( $value ){

		if( $this->hasOptions() && !empty( $value ) ) {
			if( !$this->isValidOption($value)){
				throw new \Exception('Data error in field '. $this->data['field_name'] .' if present, expected one of "' . $this->getOptions( true ) .'" got "'. $value . '"');
			}
		}
	}

	public function getType(){
		return $this->type;
	}

	/**
	 * @param $value
	 *
	 * @return bool
	 */
	abstract function validate($value);

	protected function isValidOption( $value ){

		if(empty($this->options)){
			throw new \Exception('Data error in field '. $this->data['field_name'] .'is missing options.' );
		}

		return in_array( $value, $this->options );

	}

	/**
	 * For the case where a field as a number of options e.g.
	 *
	 * [options] => A,B,C,D,E,F,G,H,1,2,3,4,5,6,7,8,9
	 *
	 * @return bool
	 */
	public function hasOptions(){
		return count($this->options) > 0 ? true: false;
	}

	/**
	 * Return defined options as a string or array
	 *
	 * @param bool $string
	 *
	 * @return array|string
	 */
	public function getOptions($string = false ){

		if(empty($this->options)){
			throw new \Exception('There are no options for defined for this field type');
		}

		if($string){
			return implode(',' , $this->options);
		} else {
			return $this->options;
		}
	}

	/**
	 * @return string\null
	 */
	public function getMessage(){
		return $this->errorMessage;
	}

	protected function isValidInt( $value ){

		return is_numeric( $value ) ? true : false;

	}


	public function isRequired(){
		return $this->required;
	}

	protected function stringHasValidLength( $value ){
		if( isset($this->data[ 'max-length' ]) && !empty( $value ) && strlen( $value ) >  $this->data[ 'max-length' ] ){
			throw new \Exception('Data error in field ' . $this->data['field_name']  . ' value too long, a max length of  ' . $this->data[ 'max-length' ] . ' is expected. ' . strlen( $value ) . ' given. Value: "' . $value . '"');
		}
	}
}
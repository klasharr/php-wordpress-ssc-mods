<?php

namespace SSCMods\Fields;

Use WP_CLI;
Use WP_Post;
Use Exception;

class FieldValidatorManager {

	/**
	 * @var $post WP_Post
	 */
	private $post;

	/**
	 * @var array
	 */
	private $fields = array();

	/**
	 * FieldValidator constructor
	 *
	 * @param \WP_Post $post
	 */
	public function __construct( WP_Post $post ) {

		$this->post = $post;

		if ( empty( $this->post->field_settings ) ) {
			return;
		}

		foreach ( $this->post->field_settings as $field => $rules ) {

			if ( empty( $rules['type'] ) ) {
				throw new Exception( 'Field has no type' );
			}

			$rules['field_name'] = $field;

			$className              = ucwords( $rules['type'] ) . 'Field';
			$this->fields[ $field ] = \SSCMods\SSCModsFactory::getField( $className, $rules );
		}


	}

	public function hasValidators() {
		return ! empty( $this->fields ) ? true : false;
	}

	public function getValidator( $key ) {

		return isset( $this->fields[ $key ] ) ? $this->fields[ $key ] : false;

	}


}
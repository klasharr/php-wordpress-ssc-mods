<?php

namespace SSCMods;

Use WP_CLI;
Use Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once( SSC_MODS_PLUGIN_DIR . 'classes/SSCModsFactory.php' );

class SafetyTeamsList {

	/**
	 * @var array
	 */
	private $header = array();

	/**
	 * @var int|null
	 */
	private $headerCount = null;


	/**
	 * @var SafetyTeams
	 */
	private $safetyTeams;


	/**
	 * @var int
	 */
	private $post_id;

	/**
	 * @var array
	 */
	private $rows;

	/**
	 * @var $fieldValidatorManager fieldValidatorManager
	 */
	private $fieldValidatorManager;


	/**
	 * @param Filter $filter
	 *
	 * @throws Exception
	 */
	public function get( $post_id, $filter = false ) {

		$this->post_id = $post_id;

		if ( $filter !== false && ! ( $filter instanceof Filter ) ) {
			throw new Exception ( 'If an arg is present, execute must be passed a filter.' );
		} elseif ( $filter === false ) {
			$filter = new NullFilter();
		}

		$post     = $this->getPost( $this->post_id );
		$postMeta = null;

		if ( ! is_a( $post, 'WP_Post' ) ) {
			throw new Exception ( '$this->post_id does not return a post object.' );
		}

		if ( $s = get_post_meta( $this->post_id, 'fields', true ) ) {
			$post->field_settings = parse_ini_string( $s, true );
		}

		$this->fieldValidatorManager = SSCModsFactory::getFieldValidatorManager( $post );

		$this->rows = $this->getRows( $post, $filter );

		return $this->rows;

	}


	/**
	 * @param $post_id
	 *
	 * @return array|null|WP_Post
	 * @throws Exception
	 */
	protected function getPost( $post_id ) {

		$post = get_post( $post_id );

		if ( false === $post instanceof \WP_Post ) {
			throw new Exception( 'Post with ID %d does not exist.', $post_id );
		}

		if ( $post->post_type != 'sailing-programme' ) {
			throw new Exception( 'The post ID passed must be a post type: sailing-programme' );
		}

		if ( empty( $post->post_content ) ) {
			throw new Exception( sprintf( 'Sailing Programme with Post ID %d has no content.', $post_id ) );
		}

		return $post;

	}

	/**
	 * @param WP_Post $post
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getRows( \WP_Post $post, $filter = false ) {


		$out = array();

		$dataArray = explode( "\n", $post->post_content );

		$line = 0;
		foreach ( $dataArray as $dataLine ) {

			if ( $line == 0 ) {
				$this->setHeader( $dataLine );
				$this->getHeaderCount();
				$line ++;
				continue;
			}

			if ( empty( trim( $dataLine ) ) ) {
				break;
			}

			$tmpData = explode( ",", $dataLine );

			if ( count( $tmpData ) != $this->headerCount ) {
				throw new Exception( 'Line ' . $line . ' column count mismatch, expected ' . $this->getHeaderCount() . ' columns, got ' . count( $tmpData ) . '. ' . $dataLine );
			}

			$i = 0;
			foreach ( $tmpData as $i => $field ) {
				$data[ trim( $this->header[ $i ] ) ] = trim( $field );
			}

			// @todo better exception handling so this works for web and CLI

			try {
				$this->validateData( $data );
			} catch ( Exception $e ) {
				WP_CLI::log( 'Line ' . $line . ' ' . $e->getMessage() );
			}

			try {
				$out['rows']                     = $data;
				$out['teams'][ $data['Team'] ][] = $data;
			} catch ( Exception $e ) {
				WP_CLI::error( $e->getMessage() );
			}

			$line ++;
		}

		return $out;

	}

	/**
	 *
	 * @param $data array
	 *
	 * For example where Day will map to a validator object.
	 *
	 * array(
	 *   Day => Sun
	 *   Date => 12/09/18
	 *   Team => A
	 * )
	 */
	private function validateData( $data ) {

		foreach ( $data as $fieldName => $value ) {

			/** @var $validator FieldValidator */
			$validator = $this->fieldValidatorManager->getValidator( $fieldName );

			if ( ! $validator ) {

				WP_CLI::log( 'A validator for ' . $fieldName . ' does not exist, check the field name and field settings to see that they match.' );
				continue;
			}

			$validator->validate( $value );
		}

	}

	/**
	 * @param $csvLine string
	 */
	private function setHeader( $csvLine ) {
		$this->header      = explode( ",", $csvLine );
		$this->headerCount = count( $this->header );
	}

	/**
	 * @return array
	 */
	private function getHeader() {
		return $this->header;
	}

	/**
	 * @return int|null
	 */
	private function getHeaderCount() {
		return $this->headerCount;
	}

}
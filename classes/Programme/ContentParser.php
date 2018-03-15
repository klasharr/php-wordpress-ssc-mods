<?php

namespace SSCMods;

use WP_CLI;
use Exception;

/**
 * Idea, pass in a column scheme plan with validation rules for each column.
 *
 * Class ContentParser
 */
class ContentParser {

	/**
	 * @var string
	 */
	private $content;

	/**
	 * @var string
	 */
	private $sailEventFilter;

	/**
	 * @var SailType
	 */
	private $sailType;

	/**
	 * @var RaceSeries
	 */
	private $raceSeries;


	/**
	 * @var SafetyTeams
	 */
	private $safetyTeams;


	/**
	 * @var array
	 */
	private $header = array();

	/**
	 * @var int|null
	 */
	private $headerCount = null;

	/**
	 * @var $fieldValidator FieldValidator
	 */
	private $fieldValidator;


	public function __construct() {

	}

	/**
	 * @param $content
	 * @param SailType $sailType
	 * @param RaceSeries $raceSeries
	 * @param SafetyTeams $safetyTeams
	 */
	public function init( \WP_Post $post, SailType $sailType, RaceSeries $raceSeries, SafetyTeams $safetyTeams ) {

		if ( empty( trim( $post->post_content ) ) ) {
			throw new Exception( '$post->post_content is empty' );
		}

		$this->fieldValidator = SSCModsFactory::getFieldValidatorManager( $post );

		$this->content     = $post->post_content;
		$this->sailType    = $sailType;
		$this->raceSeries  = $raceSeries;
		$this->safetyTeams = $safetyTeams;
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

	/**
	 * @return mixed
	 * @throws Exception
	 */
	public function getData( Filter $filter ) {

		$out = array(
			'data'   => array(),
			'errors' => array(),
		);

		$dataArray = explode( "\n", $this->content );

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
				throw new Exception( 'Line ' . $line . ' column count mismatch, expected ' . $this->getHeaderCount() . ' columns. ' . $dataLine );
			}

			$i = 0;
			foreach ( $tmpData as $i => $field ) {
				$data[ trim( $this->header[ $i ] ) ] = trim( $field );
			}

			try {

				// @todo better exception handling so this works for web and CLI

				try {
					$this->validateData( $data );
				} catch ( Exception $e ) {
					WP_CLI::log( $e->getMessage() );
				}

				try {


					/** @var $dto EventDTO */
					$dto = new EventDTO( $line, $data, $this->sailType, $this->raceSeries, $this->safetyTeams );

				} catch ( Exception $e ) {
					WP_CLI::error( $e->getMessage() );
				}

				if ( ! $filter->filter( $dto ) ) {
					continue;
				}

				$out['data'][ $dto->getDate() ][] = $dto;

			} catch ( Exception $e ) {
				$out['errors'][] = sprintf( 'Error line: %d %s', $line, $e->getMessage() );
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
			$validator = $this->fieldValidator->getValidator( $fieldName );

			if ( ! $validator ) {

				WP_CLI::log( 'A validator for ' . $fieldName . ' does not exist, check the field name and field settings to see that they match.' );
				continue;
			}

			$validator->validate( $value );
		}

	}
}
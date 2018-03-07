<?php

namespace SSCMods;

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


	public function __construct() {

	}

	/**
	 * @param $content
	 * @param SailType $sailType
	 * @param RaceSeries $raceSeries
	 * @param SafetyTeams $safetyTeams
	 */
	public function init( $content, SailType $sailType, RaceSeries $raceSeries, SafetyTeams $safetyTeams ) {

		if ( empty( trim( $content ) ) ) {
			throw new \Exception( '$content is empty' );
		}

		$this->content     = $content;
		$this->sailType    = $sailType;
		$this->raceSeries  = $raceSeries;
		$this->safetyTeams = $safetyTeams;
	}


	/**
	 * @param $csvLine string
	 */
	private function setHeader($csvLine){
		$this->header = explode( ",", $csvLine );
		$this->headerCount = count($this->header);
	}

	/**
	 * @return array
	 */
	private function getHeader(){
		return $this->header;
	}

	/**
	 * @return int|null
	 */
	private function getHeaderCount(){
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

			if($line == 0 ){
				$this->setHeader($dataLine);
				$this->getHeaderCount();
			}

			if ( empty( trim( $dataLine ) ) ) {
				break;
			}

			$data = explode( ",", $dataLine );

			if(count($data) !== (int) $this->headerCount ){
				throw new \Exception('Line ' . $line . ' column count mismatch, expected ' . $this->getHeaderCount() . ' columns. ' );
			}

			try {

				/** @var $dto EventDTO */
				$dto = new EventDTO( $line, $data, $this->sailType, $this->raceSeries, $this->safetyTeams );

				if( !$filter->filter( $dto ) ) continue;

				$out['data'][ $dto->getDate() ][] = $dto;

			} catch ( \Exception $e ) {
				$out['errors'][] = sprintf('Error line: %d %s', $line, $e->getMessage());
			}
			$line ++;
		}

		return $out;
	}
}
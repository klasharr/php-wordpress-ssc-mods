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

			if ( empty( trim( $dataLine ) ) ) {
				break;
			}

			$data = explode( ",", $dataLine );

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
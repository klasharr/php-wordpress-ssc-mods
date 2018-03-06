<?php

namespace SSCMods;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/ProgrammeBase.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/SafetyTeamFilter.php' );

Class SafetyDuties extends ProgrammeBase {

	public function __invoke( $args ) {

		try {
			parent::__invoke( $args );
		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}

		$this->execute( new SafetyTeamFilter() );

		foreach ( $this->flattenedEvents as $event ) {
			\WP_CLI::log( $event );
		}

		\WP_CLI::success( 'Success!!' );
	}


	function getHouseDuties( EventDTO $dto ) {

		$s = "If you are sailing please start as soon as you can. Swap on Dutyman if you can't make this duty and in case of problems your team lead is R...";

		//1
		$duty                      = $this->getCsvRow( $dto );
		$duty['Duty Type']         = 'Galley';
		$duty['Duty Instructions'] = $s;
		$this->allduties[]         = $duty;

		//2
		$duty                      = $this->getCsvRow( $dto );
		$duty['Duty Type']         = 'Galley';
		$duty['Duty Instructions'] = $s;
		$this->allduties[]         = $duty;

		//3
		$duty                      = $this->getCsvRow( $dto );
		$duty['Duty Type']         = 'Bar';
		$duty['Duty Instructions'] = $s;
		$this->allduties[]         = $duty;

		//4
		$duty                      = $this->getCsvRow( $dto );
		$duty['Duty Type']         = 'Bar';
		$duty['Duty Instructions'] = $s;
		$this->allduties[]         = $duty;

	}


	private function getCsvRow( EventDTO $dto ) {

		$a = $this->getColHeadings();

		$a['Duty Date'] = $dto->getDate();
		$a['Event']     = $dto->getEvent();
		$a['Duty Time'] = $this->getDutyTime( $dto );

		return $a;
	}


	private function getDutyTime( EventDTO $dto ) {

		switch ( $dto->getTime() ) {
			case '1830';
				return '1930';
				break;

			case '1900';
				return '2000';
				break;

			case '1030':
			case '1100':
				return '1145';
				break;
			default:
				\WP_CLI::warning( 'Invalid time ' . $dto );
		}
	}

	private function getColHeadings() {

		return array(
			'Duty Date'         => '', // Yes dd/mm/yy
			'Duty Time'         => '',
			'Event'             => '', // Yes A description of what is taking place
			'Duty Type'         => '', // Yes A brief description of the duty, for example Race Officer, Results, Bar
			'Swappable'         => '',
			'Reminders'         => '',
			'Confirmed'         => '',
			'Duty Notify'       => '',
			'Duty Instructions' => '',
			'Duty DBID'         => '',
			'First Name'        => '',
			'Last Name'         => '',
			'Member Name'       => '',
			'Alloc'             => '',
			'Notes'             => ''
		);
	}


	function getCSVHeaderRow( $a ) {
		return implode( ',', array_keys( $a ) ) . "\n";
	}

	function getRow( $a ) {
		return implode( ',', array_values( $a ) ) . "\n";

	}


	private function getDuties() {


		foreach ( $events as $EventDTO ) {

			/**
			 * if( ONLY_HOUSE_DUTY && !$EventDTO->isEventForHouseDuty() ) {
			 * continue;
			 * }
			 **/

			$linesForCSV[] = array(
				'date'  => $EventDTO->getDate(),
				'day'   => $EventDTO->weekday,
				'event' => $EventDTO->getEvent(),
				'team'  => $EventDTO->getTeam(),
				'type'  => $EventDTO->getTypeName(),
				'time'  => $EventDTO->getTime(),
			);

			$this->getHouseDuties( $EventDTO );

			$EventDTO    = false;
			$linesForCSV = array();

		}

	}


}

//echo getCSVHeaderRow(getColHeadings());

//foreach ($allduties as $duty) {
//echo getRow($duty);
//}

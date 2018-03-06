<?php

namespace SSCMods;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/ProgrammeBase.php' );

Class SafetyDuties extends ProgrammeBase {

	public function __invoke( $args ) {

		try {
			parent::__invoke( $args );
			$this->execute( \SSCMods\SSCProgrammeFactory::getSafetyTeamFilter() );

		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}

		/**
		 * @var $event EventDTO
		 */
		foreach ( $this->flattenedEvents as $event ) {

			$a = $this->getSingleSafetyDuty($event);

			\WP_CLI::log( $this->getRow($this->getSingleSafetyDuty($event)));

			//\WP_CLI::log( $event );
		}

		\WP_CLI::success( 'Success!!' );
	}


	/**
	 * @param EventDTO $dto
	 *
	 * @return array
	 */
	private function getSingleSafetyDuty( EventDTO $dto ) {

		return array(
			'Duty Date'         => $dto->getDate(),
			'Duty Time'         => $this->getDutyTime( $dto ),
			'Event'             => $dto->getEvent(),
			'Duty Type'         => 'Safety', // Yes A brief description of the duty, for example Race Officer, Results, Bar
			'Swappable'         => '',
			'Reminders'         => '',
			'Confirmed'         => '',
			'Duty Notify'       => '',
			'Duty Instructions' => "",
			'Duty DBID'         => '',
			'First Name'        => '',
			'Last Name'         => '',
			'Member Name'       => '',
			'Alloc'             => '',
			'Notes'             => ''
		);

	}


	/**
	 * @param EventDTO $dto
	 *
	 * @return int|string
	 */
	private function getDutyTime( EventDTO $dto ) {

		switch ( $dto->getTime() ) {
			case '1830';
				return '1700';
				break;

			case '1900';
				return '1730';
				break;
			case '1030':
				return '0900';
				break;
			case '1100':
				return '0930';
				break;
			case '1400':
				return 1230;
				break;
			default:
				\WP_CLI::error( 'Invalid time ' . $dto );
		}
	}

	private function getCSVHeaderRow( $a ) {
		return implode( ',', array_keys( $a ) );
	}

	private function getRow( $a ) {
		return implode( ',', array_values( $a ) );

	}

	/**
	private function getDuties() {


		foreach ( $events as $EventDTO ) {


			 * if( ONLY_HOUSE_DUTY && !$EventDTO->isEventForHouseDuty() ) {
			 * continue;
			 * }


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
	 ***/


}

//echo getCSVHeaderRow(getColHeadings());

//foreach ($allduties as $duty) {
//echo getRow($duty);
//}

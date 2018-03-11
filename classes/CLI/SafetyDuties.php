<?php

namespace SSCMods;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/ProgrammeBase.php' );

Class SafetyDuties extends ProgrammeBase {

	private $safetyTeamsData;

	private $safety_teams_list_id;


	public function __invoke( $args ) {

		try {
			parent::__invoke( $args );

			if(empty($args[1])){
				throw new \Exception("You need a second argument");
			}

			$this->safety_teams_list_id = $args[1];

			$this->getSafetyTeams($this->safety_teams_list_id );

			$this->execute( \SSCMods\SSCModsFactory::getSafetyTeamFilter() );

		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}




		/**
		 * @var $event EventDTO
		 */
		foreach ( $this->flattenedEvents as $event ) {

			$team = $event->getTeam();

			if(empty($this->safetyTeamsData['teams'][$team])){
				throw new \Exception( 'Team data is missing' );
			}

			foreach($this->safetyTeamsData['teams'][$team] as $member){
				print_r($member);

			}
die();

			\WP_CLI::log( $this->getRow($this->getSingleSafetyDuty($event)));

		}

		\WP_CLI::success( 'Success!!' );
	}

	private function getSafetyTeams( $post_id ){

		$o = SSCModsFactory::getSafetyTeamsList();

		$this->safetyTeamsData = $o->get( $post_id);

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
			'Swappable'         => 'Yes',
			'Reminders'         => 'Yes',
			'Confirmed'         => '',
			'Duty Notify'       => '',
			'Duty Instructions' => 'Please only swap like for like duties. If you have any questions please contact your Team Leader.',
			'Duty DBID'         => '',
			'First Name'        => '',
			'Last Name'         => '',
			'Member Name'       => '',
			'Alloc'             => '',
			'Notes'             => ''
		);

	}

	private function getDutymanHeaders(){

		$a = array(
			'Duty Date'         => '',
			'Duty Time'         => '',
			'Event'             => '',
			'Duty Type'         => '', // Yes A brief description of the duty, for example Race Officer, Results, Bar
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

		return implode( ',', array_keys( $a ) );
	}


	/**
	 * @param EventDTO $dto
	 *
	 * @return int|string
	 */
	private function getDutyTime( EventDTO $dto ) {

		switch ( $dto->getTime() ) {
			case '1830';
				return '17:00';
				break;

			case '1900';
				return '17:30';
				break;
			case '1030':
				return '09:00';
				break;
			case '1100':
				return '09:30';
				break;
			case '1400':
				return '12:30';
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



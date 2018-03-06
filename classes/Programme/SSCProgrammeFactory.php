<?php

namespace SSCMods;

require_once( 'mappers/SailType.php' );
require_once( 'SafetyTeams.php' );
require_once( 'SailTypeFilter.php' );
require_once( 'mappers/RaceSeries.php' );
require_once( 'ContentParser.php' );
require_once( 'Filter.php' );
require_once( 'NullFilter.php' );
require_once( 'EventDTO.php' );
require_once( 'Day.php' );
require_once( 'SafetyTeamFilter.php' );

class SSCProgrammeFactory {

	/**
	 * @return SafetyTeams
	 */
	public static function getSafetyTeams() {
		return new \SSCMods\SafetyTeams();
	}

	/**
	 * @return SailType
	 */
	public static function getSailType() {
		return new \SSCMods\SailType();
	}

	/**
	 * @return SailTypeFilter
	 */
	public static function getSailTypeFilter() {
		return new \SSCMods\SailTypeFilter( new \SSCMods\SafetyTeams(), new \SSCMods\SailType(), array(), array() );
	}

	/**
	 * @return RaceSeries
	 */
	public static function getRaceSeries() {
		return new \SSCMods\RaceSeries;
	}

	public static function getContentParser(){
		return new \SSCMods\ContentParser;
	}

	public static function getSafetyTeamFilter(){
		return new \SSCMods\SafetyTeamFilter;
	}
}
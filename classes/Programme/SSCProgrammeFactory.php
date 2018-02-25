<?php

require_once( 'mappers/SailType.php' );
require_once( 'SafetyTeams.php' );
require_once( 'SailTypeFilter.php' );
require_once( 'mappers/RaceSeries.php' );
require_once( 'ContentParser.php' );

class SSCProgrammeFactory {

	/**
	 * @return SafetyTeams
	 */
	public static function getSafetyTeams() {
		return new SafetyTeams();
	}

	/**
	 * @return SailType
	 */
	public static function getSailType() {
		return new SailType();
	}

	/**
	 * @return SailTypeFilter
	 */
	public static function getSailTypeFilter() {
		return new SailTypeFilter( new SafetyTeams(), new SailType(), array(), array() );
	}

	/**
	 * @return RaceSeries
	 */
	public static function getRaceSeries() {
		return new RaceSeries;
	}

	public static function getContentParser(){
		return new ContentParser;
	}
}
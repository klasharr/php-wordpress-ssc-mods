<?php

include_once( SSC_MODS_PLUGIN_DIR.'/classes/Programme/mappers/SailType.php' );
include_once( SSC_MODS_PLUGIN_DIR.'/classes/Programme/SafetyTeams.php' );
include_once( SSC_MODS_PLUGIN_DIR.'/classes/Programme/SailTypeFilter.php' );
include_once( SSC_MODS_PLUGIN_DIR.'/classes/Programme/mappers/RaceSeries.php' );

class SSCProgrammeFactory{

	/**
	 * @return SafetyTeams
	 */
	public static function getSafetyTeams(){
		return new SafetyTeams();
	}

	/**
	 * @return SailType
	 */
	public static function getSailType(){
		return new SailType();
	}

	/**
	 * @return SailTypeFilter
	 */
	public static function getSailTypeFilter(){
		return new SailTypeFilter( new SafetyTeams(), new SailType(), array(), array() );
	}

	/**
	 * @return RaceSeries
	 */
	public static function getRaceSeries(){
		return new RaceSeries;
	}
}
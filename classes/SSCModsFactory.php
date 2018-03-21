<?php

namespace SSCMods;

require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/mappers/SailType.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/SafetyTeams.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/SailTypeFilter.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/mappers/RaceSeries.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/ContentParser.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/NullFilter.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/EventDTO.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/Day.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/SafetyTeamFilter.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/TrainingFilter.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/FieldValidatorManager.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/interfaces/FieldValidator.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/exceptions/ValidatorException.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/SafetyTeamsList.php' );
require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/SailingEventForm.php' );

class SSCModsFactory {

	/**
	 * @return SafetyTeams
	 */
	public static function getSafetyTeams() {
		return new \SSCMods\SafetyTeams();
	}

	/**
	 * @return SafetyTeams
	 */
	public static function getSafetyEventForm( SafetyTeams $safetyTeams, SailType $sailType ) {
		return new \SSCMods\SailingEventForm( $safetyTeams, $sailType) ;
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

	public static function getContentParser() {
		return new \SSCMods\ContentParser;
	}

	public static function getSafetyTeamFilter() {
		return new \SSCMods\SafetyTeamFilter;
	}

	public static function getTrainingFilter() {
		return new \SSCMods\TrainingFilter;
	}

	public static function getFieldValidatorManager( \WP_Post $post ) {
		return new \SSCMods\Fields\FieldValidatorManager( $post );
	}

	public static function getField( $className, $rules ) {

		$file = SSC_MODS_PLUGIN_DIR . 'classes/Fields/' . ucwords( $rules['type'] ) . 'Field.php';

		if ( ! file_exists( $file ) ) {
			throw new Exception( $file . ' does not exist' );
		}

		require_once( $file );

		$className = '\SSCMods\Fields\\' . $className;

		return new $className( $rules );
	}

	public static function getSafetyTeamsList() {
		return new \SSCMods\SafetyTeamsList();
	}
}
<?php

namespace SSCMods;

require_once( SSC_MODS_PLUGIN_DIR . '/interfaces/Filter.php' );

class SafetyTeamFilter implements \SSCMods\Filter {

	/**
	 * @param EventDTO $eventDTO
	 *
	 * @return bool
	 */
	public function filter( EventDTO $eventDTO ) {

		return $eventDTO->getTeam() !== false ? true : false;

	}

}
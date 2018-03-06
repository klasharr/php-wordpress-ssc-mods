<?php

namespace SSCMods;

require_once('Filter.php');

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
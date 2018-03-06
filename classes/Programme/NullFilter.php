<?php

namespace SSCMods;

require_once('Filter.php');

class NullFilter implements \SSCMods\Filter {

	/**
	 * @param EventDTO $eventDTO
	 *
	 * @return bool
	 */
	public function filter( EventDTO $eventDTO ) {

		return true;

	}

}
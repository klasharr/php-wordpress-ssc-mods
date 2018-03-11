<?php

namespace SSCMods;

interface Filter {

	/**
	 * @param EventDTO $eventDTO
	 *
	 * @return bool
	 */
	public function filter(EventDTO $eventDTO);

}
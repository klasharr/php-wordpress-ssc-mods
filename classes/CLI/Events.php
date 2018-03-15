<?php

namespace SSCMods;

Use WP_CLI;
Use Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once( SSC_MODS_PLUGIN_DIR . '/classes/Programme/ProgrammeBase.php' );

Class Events extends ProgrammeBase {

	public function __invoke( $args ) {

		try {
			parent::__invoke( $args );

			$this->execute();

		} catch ( Exception $e ) {
			WP_CLI::error( $e->getMessage() );
		}

		if ( empty( $this->flattenedEvents ) ) {
			WP_CLI::error( '$this->flattenedEvents is empty, most likely ->execute wasn\'t called' );
		}

		// We've validated the data, no do something with it.

		/**
		 * @var $event EventDTO
		 */
		foreach ( $this->flattenedEvents as $event ) {

			//WP_CLI::log( $event );
		}

		WP_CLI::success( 'Success!!' );
	}

}



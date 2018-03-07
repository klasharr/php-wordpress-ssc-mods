<?php

namespace SSCMods;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

include_once( SSC_MODS_PLUGIN_DIR . '/classes/Duties/Importer.php' );
define( 'MEMBERS_FILE', SSC_MODS_PLUGIN_DIR . 'classes/2018_safety_teams.csv' );

class Members {

	public function __invoke( $args ) {

		try{

			\WP_CLI::debug(MEMBERS_FILE);

			$o = new \SSCMod\Duties\Importer( MEMBERS_FILE );


			print_r($o->setTeamMembers());

		} catch ( \Exception $e ) {

			\WP_CLI::error( $e->getMessage() );

		}

		\WP_CLI::success( 'Success!!' );
	}



}
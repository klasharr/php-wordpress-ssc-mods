<?php

namespace SSCMods;

use WP_CLI;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once( SSC_MODS_PLUGIN_DIR . 'classes/SSCModsFactory.php' );

class SafetyTeamsUtil {


	/**
	 * @var int
	 */
	private $post_id;

	/**
	 * @var array
	 */
	private $rows;

	public function __construct() {

	}

	public function __invoke( $args ) {

		if ( empty( $args[0] ) || (int) $args[0] === 0 ) {
			throw new Exception( 'The first argument must be a non zero integer value' );
		}

		$this->post_id = $args[0];


		try {

			$o = SSCModsFactory::getSafetyTeamsList();

			$rows = $o->get( $this->post_id );

			//print_r($rows);

		} catch ( Exception $e ) {

			WP_CLI::error( $e->getMessage() );

		}

		WP_CLI::success( 'Success!!' );

	}

}
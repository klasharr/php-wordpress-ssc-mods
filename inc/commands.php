<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( SSC_MODS_PLUGIN_DIR . '/classes/CLI/HouseDuties.php' );

$instance = new HouseDuties();

WP_CLI::add_command( 'duties', $instance );


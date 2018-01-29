<?php

/*
 Plugin Name: SSC Mods
 Plugin URI: TBD
 Description:
 Author: Klaus Harris
 Version: -
 Author URI: https://klaus.blog
 Text Domain: ssc-mods
 */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SSC_MODS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SSC_MODS_PLUGIN_FILE', __FILE__ );

include_once( SSC_MODS_PLUGIN_DIR . 'inc/shortcodes.php' );
include_once( SSC_MODS_PLUGIN_DIR . 'inc/post-types.php' );
include_once( SSC_MODS_PLUGIN_DIR . 'inc/display.php' );
include_once( SSC_MODS_PLUGIN_DIR . 'inc/taxonomy.php' );

function prr($args){
	echo "<pre>";
	print_r($args);
	echo "</pre>";
}






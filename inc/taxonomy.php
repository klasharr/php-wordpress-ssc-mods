<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ssc_mods_event_taxonomy() {

	register_taxonomy(
		'event-types',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
		'event',        //post type name
		array(
			'hierarchical' => false,
			'label'        => 'Event types',  //Display name
			'query_var'    => true,
			'rewrite'      => array(
				'slug'       => 'event-types', // This controls the base slug that will display before each term
				'with_front' => false // Don't display the category base before
			)
		)
	);
}

add_action( 'init', 'ssc_mods_event_taxonomy' );

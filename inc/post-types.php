<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Notes
 * https://github.com/jeremyfelt/Hooks-in-Jetpack/blob/master/filter-list.txt
 */

define( 'SSC_MODS_EVENT_PROGRAMME', 'sailing-programme' );
define( 'SSC_MODS_DUTIES_LIST', 'duties-list' );

function ssc_mods_post_types_init() {

	$args = array(
		'label'               => esc_html__( 'Sailing Programme' ),
		'public'              => true,
		'show_ui'             => true,
		'exclude_from_search' => true,
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'rewrite'             => array( 'slug' => SSC_MODS_EVENT_PROGRAMME ),
		'query_var'           => true,
		'menu_icon'           => 'dashicons-admin-page',
		'show_in_nav_menus'   => true,
		'show_in_rest'        => false,
		'menu_position'       => 30,
		'supports'            => array(
			'title',
			'editor',
			'revisions',
		)
	);
	register_post_type( 'sailing-programme', $args );
}

add_action( 'init', 'ssc_mods_post_types_init' );

function ssc_mods_disable_wysiwyg( $default ) {

	global $post;

	if ( in_array( get_post_type( $post ), array(
		'sailing-programme',
	) ) ) {
		return false;
	}

	return $default;
}

add_filter( 'user_can_richedit', 'ssc_mods_disable_wysiwyg' );


function ssc_mods_add_post_type_event_date_fields() {

	$fm = new Fieldmanager_Group( array(
		'name'     => 'Dates',
		'children' => array(
			'event_start_date' => new Fieldmanager_Datepicker( array(
				'name'     => 'event_start_date',
				'use_time' => true,
				'label'    => 'Start',
			) ),
			'event_end_date'   => new Fieldmanager_Datepicker( array(
				'name'     => 'event_end_date',
				'use_time' => true,
				'label'    => 'End',
			) ),
		),
	) );
	$fm->add_meta_box( 'Dates', array( 'event' ) );

}

// Turn of jetpack sharing for all posts
function ssc_mods_disable_sharing( $services ) {
	return false;
}

add_filter( 'sharing_show', 'ssc_mods_disable_sharing' );
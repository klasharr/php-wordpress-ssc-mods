<?php

define( 'SSC_MODS_EVENT_PROGRAMME', 'sailing-programme' );
//define( 'SSC_MODS_EVENT_PROGRAMME_TYPE', 'sailing-programme-event-type' );

define( 'SSC_MODS_EVENT', 'events' );
define( 'SSC_MODS_EVENT_TYPE', 'event-type' );


/**
 * Set up the sailing programme event type
 */
function ssc_mods_programme_init() {

	$args = array(
		'label'             => esc_html__('Sailing Programme'),
		'public'            => true,
		'show_ui'           => true,
		'exclude_from_search' => true,
		'capability_type'   => 'post',
		'hierarchical'      => false,
		'rewrite'           => array( 'slug' => SSC_MODS_EVENT_PROGRAMME),
		'query_var'         => true,
		'menu_icon'         => 'dashicons-admin-page',
		'show_in_nav_menus' => true,
		'show_in_rest'      => false,
		'menu_position'     => 30,
		'supports'          => array(
			'title',
			'editor',
			'revisions',
		)
	);

	register_post_type( 'sailing-programme', $args );


	/*
	$args = array(
		'label'             => esc_html__('Event'),
		'public'            => true,
		'show_ui'           => true,
		'exclude_from_search' => true,
		'capability_type'   => 'post',
		'hierarchical'      => false,
		'rewrite'           => array( 'slug' => SSC_MODS_EVENT_PROGRAMME_TYPE),
		'query_var'         => true,
		'menu_icon'         => 'dashicons-admin-page',
		'show_in_nav_menus' => true,
		'show_in_rest'      => false,
		'menu_position'     => 30,
		'supports'          => array(
			'title',
			'editor',
			'revisions',
		),
		'show_in_menu' => 'edit.php?post_type=sailing-programme'
	);

	register_post_type( 'sail-prog-event', $args );


	$args = array(
		'label'             => esc_html__('Event'),
		'labels' => array(
			'name' => __( 'Event' ),
			'singular_name' => __( 'Events' )
		),
		'show_ui'           => true,
		'exclude_from_search' => true,
		'capability_type'   => 'post',
		'hierarchical'      => false,
		'rewrite'           => array( 'slug' => SSC_MODS_EVENT),
		'query_var'         => true,
		'menu_icon'         => 'dashicons-admin-page',
		'show_in_nav_menus' => true,
		'show_in_rest'      => false,
		'menu_position'     => 30,
		'public' => true,
		'has_archive' => true,
		'supports'          => array(
									'title',
									'editor',
									'revisions',
									'thumbnail'
								)
	);

	//register_post_type( 'event', $args );

	*/
}

add_action( 'init', 'ssc_mods_programme_init' );

function ssc_mods_disable_wysiwyg( $default ) {

	global $post;

	if ( in_array( get_post_type( $post ), array( 'sailing-programme', 'sail-prog-event-type' ) ) ) {
		return false;
	}

	return $default;
}

add_filter( 'user_can_richedit', 'ssc_mods_disable_wysiwyg' );


function ssc_mods_add_post_type_event_date_fields(){

	$fm = new Fieldmanager_Group( array(
		'name' => 'Dates',
		'children' => array(
			'event_start_date' => new Fieldmanager_Datepicker( array(
				'name' => 'event_start_date',
				'use_time' => true,
				'label' => 'Start',
				) ),
			'event_end_date' => new Fieldmanager_Datepicker( array(
				'name' => 'event_end_date',
				'use_time' => true,
				'label' => 'End',
			) ),
		),
	) );
	$fm->add_meta_box( 'Dates', array( 'event' ) );
	
}

//add_action( 'fm_post_event', 'ssc_mods_add_post_type_event_date_fields' );

// Turn of jetpack sharing for all posts
function ssc_mods_disable_sharing ( $services ) {
	return false;
}
add_filter( 'sharing_show', 'ssc_mods_disable_sharing' );

function my_post_image_html( $html, $post_id, $post_image_id ) {
	if(is_single()) {
		return $html;
	} else {
		return '';
	}
}


//https://github.com/jeremyfelt/Hooks-in-Jetpack/blob/master/filter-list.txt
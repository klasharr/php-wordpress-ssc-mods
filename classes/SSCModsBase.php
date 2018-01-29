<?php

class SSCModsBase {

	protected function display( $template, $args, $tempate_path = '', $default_path = ''  ){

		$this->getTemplate( $template, $args, $tempate_path = '', $default_path = '');

	}

	/**
	 * @param string 	$template_name			Template to load.
	 * @param array 	$args					Args passed for the template file.
	 * @param string 	$string $template_path	Path to templates.
	 * @param string	$default_path			Default path to template files.
	 */
	private function getTemplate( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {

		if ( is_array( $args ) && count( $args ) > 0) {
			extract( $args );
		}
		$template_file = $this->locateTemplate( $template_name, $tempate_path, $default_path );

		if ( ! file_exists( $template_file ) ) {
			trigger_error(sprintf( '%s <code>%s</code> does not exist.', __FUNCTION__, $template_file ) );
			return;
		}

		include( $template_file );
	}


	/**
	 * Load order
	 * to follow
	 *
	 * @param 	string 	$template_name			Template to load.
	 * @param 	string 	$string $template_path	Path to templates.
	 * @param 	string	$default_path			Default path to template files.
	 * @return 	string 							Path to the template file.
	 */
	private function locateTemplate( $template_name, $template_path = 'templates/', $default_path = '' ) {

		if ( ! $default_path ) {
			$default_path = SSC_MODS_PLUGIN_DIR . 'templates/';
		}

		$template = locate_template( array(
			$template_path . $template_name,
			$template_name
		) );

		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		return apply_filters( 'ssc_mods_locate_template', $template, $template_name, $template_path, $default_path );
	}

}



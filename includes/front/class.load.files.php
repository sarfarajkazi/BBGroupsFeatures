<?php
/*
  **
 * BBGF_LOAD_FILES CLASS
 *
 * Handles text-domain and load files
 *
 * @since 1.0.0
 */

class BBGF_LOAD_FILES {
	/**
	 * Initial class
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'bbgf_localization' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'bbgf_frontend_styles' ) );
	}

	// Localization
	function bbgf_localization() {
		load_plugin_textdomain( 'BB-Groups-Feature', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Enqueue styles.
	 */
	public function bbgf_frontend_styles() {
		$APath      = str_replace( trailingslashit( site_url() ), ABSPATH, BBGF_ASSETS_CSS );
		$cssVersion = filemtime( $APath . 'bbgf_front_css.css' );
		wp_enqueue_style( 'bbgf_custom-style', BBGF_ASSETS_CSS . 'bbgf_front_css.css', array(), $cssVersion );
	}
}
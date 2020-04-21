<?php

/**
 * BBGF class.
 *
 * Initialize all hooks
 *
 * @since 1.0.0
 */
class BBGF_LOADER {

	/**
	 * @var object
	 *
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Create class object
	 *
	 * Checks for an existing BBGF_LOADER() instance
	 * and if it doesn't find one, creates it.
	 *
	 * @return BBGF_LOADER|object
	 * @since 0.1.1
	 *
	 */
	public static function init() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new BBGF_LOADER();
			/**
			 * Check components Dependence's
			 * Checks whether BuddyBoss Groups Components is active or not.
			 *
			 */
			if ( bp_is_active( 'groups' ) ) {
				self::$instance->bbgf_setup();
			} else {
				add_action( 'admin_notices', function () {
					include_once( dirname( BBGF_FILE ) . '/includes/admin/templates/dependency-error.php' );
				} );
			}
		}

		return self::$instance;
	}

	/**
	 * Setup the plugin
	 *
	 * Sets up all the appropriate hooks and actions within our plugin.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 *
	 */
	private function bbgf_setup() {
		// Define constants
		$this->bbgf_define_constants();
		// Include required files
		$this->bbgf_includes();
		// instantiate classes
		$this->bbgf_instantiate();
	}

	/**
	 * Define the plugin constants
	 *
	 * @return void
	 */
	private function bbgf_define_constants() {
		define( 'BBGF_PATH', trailingslashit( dirname( BBGF_FILE ) ) );
		define( 'BBGF_URL', trailingslashit( plugin_dir_url( BBGF_FILE ) ) );
		define( 'BBGF_ASSETS', trailingslashit( BBGF_URL . 'assets' ) );
		define( 'BBGF_ASSETS_CSS', trailingslashit( BBGF_ASSETS . 'css' ) );
		define( 'BBGF_CLASSES', trailingslashit( BBGF_PATH . 'classes' ) );
		define( 'BBGF_INCLUDES', trailingslashit( BBGF_PATH . 'includes' ) );
		define( 'BBGF_INCLUDES_FRONT', trailingslashit( BBGF_INCLUDES . 'front' ) );
	}

	/**
	 * Include the required files
	 *
	 * @return void
	 */
	private function bbgf_includes() {
		require_once BBGF_CLASSES . 'helper.php';
		require_once BBGF_INCLUDES_FRONT . 'class.filters.groups.php';
		require_once BBGF_INCLUDES_FRONT . 'class.load.files.php';
		require_once BBGF_INCLUDES_FRONT . 'class.posts.template.php';
	}

	/**
	 * Instantiate classes
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	private function bbgf_instantiate() {
		if ( $this->bbgf_is_request( 'frontend' ) ) {
			new BBGF_LOAD_FILES();
			new BBGF_GROUPS();
			new BBGF_POSTS();
		}
	}

	/**
	 * What type of request is this?
	 *
	 * @param string $type admin or frontend.
	 *
	 * @return bool
	 */
	private function bbgf_is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return ( is_admin() || defined( 'DOING_AJAX' ) );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) );
		}

		return ( ! is_admin() || defined( 'DOING_AJAX' ) );
	}
}

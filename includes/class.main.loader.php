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
    private static $instance=null;

    /**
     * Create class object
     *
     * Checks for an existing BBGF() instance
     * and if it doesn't find one, creates it.
     *
     * @return BBGF_LOADER|object
     * @since 0.1.1
     *
     */
    public static function init() {

	    if (is_null(self::$instance)) {
		    self::$instance = new BBGF_LOADER();
		    if (self::$instance->check_BBGroupModule()) {
					self::$instance->bbgf_setup();
		    } else {
			    add_action('admin_notices', function () {
				    include_once( dirname(BBGF_FILE) . '/includes/admin/templates/dependency-error.php' );
			    });
		    }
	    }
	    return self::$instance;
    }

    /**
     * Setup the plugin
     *
     * Sets up all the appropriate hooks and actions within our plugin.
     *
     * @since 1.0.0
     *
     * @return void
     *
     */
    private function bbgf_setup() {

        // Define constants
        $this->bbgf_define_constants();
        // Include required files
        $this->bbgf_includes();
        // instantiate classes
        $this->bbgf_instantiate();
        // Initialize the action hooks
        $this->bbgf_init_actions();
    }
    /**
     * Define the plugin constants
     *
     * @return void
     */
    private function bbgf_define_constants() {
	    define('BBGF_PATH', trailingslashit(dirname(BBGF_FILE)));
        define('BBGF_URL', trailingslashit(plugin_dir_url(BBGF_FILE)));
        define('BBGF_ASSETS',trailingslashit( BBGF_URL . 'assets'));
	    define('BBGF_ASSETS_JS',trailingslashit( BBGF_ASSETS . 'js'));
	    define('BBGF_ASSETS_CSS',trailingslashit( BBGF_ASSETS . 'css'));
	    define('BBGF_ASSETS_WEBFONTS',trailingslashit( BBGF_ASSETS . 'webfonts'));
        define('BBGF_CLASSES', trailingslashit(BBGF_PATH . 'classes'));
        define('BBGF_INCLUDES',trailingslashit( BBGF_PATH . 'includes'));
	    define('BBGF_INCLUDES_ADMIN', trailingslashit(BBGF_INCLUDES . 'admin'));
	    define('BBGF_INCLUDES_FRONT', trailingslashit(BBGF_INCLUDES . 'front'));
        define('BBGF_ADMIN_TEMPLATES',trailingslashit( BBGF_PATH . 'includes/admin/templates'));
        define('BBGF_FRONT_TEMPLATES', BBGF_PATH . trailingslashit('includes/front/templates'));
        define('TEXT_DOMAIN', "BBGroups-Feature");
    }

    /**
     * Include the required files
     *
     * @return void
     */
    private function bbgf_includes() {
	    require_once BBGF_CLASSES.'helper.php';
	    require_once BBGF_CLASSES.'class.install.php';
	    require_once BBGF_INCLUDES_FRONT.'class.filters.groups.php';
    }

    /**
     * Instantiate classes
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function bbgf_instantiate() {
        if (is_admin()) {
            new BBGF_INSTALL();
        }
        new BBGF_GROUPS();
    }

    /**
     * Instantiate hooks
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function bbgf_init_actions() {
    }


	/**
	 * Check components Dependence's
	 * checks whether BuddyBoss Groups Components is active or not.
	 *
	 * @since 1.0.0
	 *
	 * @see __construct relied on
	 * @return boolean if true then it loads the file else it throws Components error.
	 */
	function check_BBGroupModule(){
		if( bp_is_active( 'groups' ) ){
			return true;
		}
		return false;
	}
}

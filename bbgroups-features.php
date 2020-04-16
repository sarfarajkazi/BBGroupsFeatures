<?php
/*
Plugin Name: BBGroups Features
Plugin URI: http://wordpress.org
Description: Adds additional features to BuddyBoss Platform groups.
Version: 1.0
Tags: buddypress
Author: Sarfaraj Kazi
Author URI: http://wordpress.org
Text Domain: BBGroups-Feature
*/
/* don't call the file directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BBGF_FILE', __FILE__ );
define( 'BBGF_MAIN_PLUGIN', "buddyboss-platform/bp-loader.php" );
define( 'BBGF_NAME', 'BBGroups Features' );
/**
 * Initial plugin
 */
/* Only load code that needs BuddyPress to run once BP is loaded and initialized. */
function check_plugin_with_bb() {
	require_once 'includes/class.main.loader.php';
	BBGF_LOADER::init();
}

if ( BBGFValidateDependencies( BBGF_MAIN_PLUGIN ) ) {
	add_action( 'bp_include', 'check_plugin_with_bb' );
} else {
	add_action( 'admin_notices', function () {
		include_once( dirname( BBGF_FILE ) . '/includes/admin/templates/dependency-error.php' );
	} );
}
/**
 * Validate Dependence's
 * checks whether BuddyBoss is active or not.
 *
 * @since 1.0.0
 *
 * @see __construct relied on
 * @return boolean if true then it loads the file else it throws dependency error.
 */
function BBGFValidateDependencies($name) {
	$arrActivePlugins = (array) get_option( 'active_plugins', array() );
	if ( is_multisite() ) {
		$arrActivePlugins = array_merge( $arrActivePlugins, get_site_option( 'active_sitewide_plugins',array() ) );
	}
	return in_array($name, $arrActivePlugins) || array_key_exists( BBGF_MAIN_PLUGIN, $arrActivePlugins );
}


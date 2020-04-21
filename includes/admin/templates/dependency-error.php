<?php
/**
 * Displays the message of dependency.
 *
 * @since 1.0
 */

echo '<div class="error"><p>';
if ( ! in_array( BBGF_MAIN_PLUGIN, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	?>
    <strong><?php echo BBGF_NAME; ?></strong> - <?php _e( 'plugin requires', 'BB-Groups-Feature' ); ?>
    <a href="https://www.buddyboss.com/platform/"
       target="_blank">BuddyBoss Platform</a> <?php _e( 'to be installed & activated!', 'wcsm' ); ?>
	<?php
}
if ( function_exists( 'bp_is_active' ) ) {
	if ( ! bp_is_active( 'groups' ) ) {
		printf(
			__( '<strong>' . BBGF_NAME . ' </strong> - BuddyBoss Courseware dependency error: <a href="%1$s">%2$s has to be activated</a>!', 'BB-Groups-Feature' ),
			admin_url( 'admin.php?page=bp-components' ),
			'groups'
		);
	}
}

echo '</p></div>';
?>

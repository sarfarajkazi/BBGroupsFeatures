<?php

/**
 * BBGF_GROUPS CLASS
 *
 * Manage Filters
 *
 * @since 1.0.0
 */
class BBGF_GROUPS {

	/**
	 * Initial class
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'bp_group_member_query_group_member_ids', array( $this, 'check_and_remove_admins' ), 99 );
		add_filter( 'bp_get_group_member_count', array( $this, 'bp_get_group_member_count_callback' ), 99 );
		add_filter( 'bp_nouveau_get_nav_count', array( $this, 'bp_get_group_total_members_callback' ), 99, 3 );
	}

	/**
	 * Filters the member IDs for the current group member query.
	 *
	 *
	 * @param array $member_ids Array of associated member IDs.
	 * @param BP_Group_Member_Query $object Current BP_Group_Member_Query instance.
	 *
	 * @return array $member_ids User IDs of relevant group member ids.
	 */
	function check_and_remove_admins( $member_ids ) {
		if ( empty( $member_ids ) ) {
			return $member_ids;
		}
		$admin_user = $this->get_admins_list();
		// Remove admin IDS from member_ids array
		$member_ids = array_diff( $member_ids, $admin_user );

		return $member_ids;
	}

	/*  Querying for a Get user_ids whose value is 'is_admin'
	 *
	 * @param string $group_id The Unique id for a group.
	 *
	 * @return array $admin_user User IDs of relevant group member ids.
	 */
	function get_admins_list( $group_id = false ) {
		global $wpdb;
		$bp            = buddypress();
		$current_group = isset( $bp->groups->current_group )
			? $bp->groups->current_group
			: false;
		if ( empty( $group_id ) ) {
			$group_id = $current_group->id;
		}
		$admin_user        = array();
		$sql               = [
			'select' => "SELECT user_id FROM {$bp->groups->table_name_members}",
			'where'  => [],
		];
		$sql['where'][]    = "group_id={$group_id}";
		$sql['where'][]    = "( is_admin = 1 OR is_mod = 1 )";
		$sql['where'][]    = "is_banned = 0";
		$sql['where']      = ! empty( $sql['where'] ) ? 'WHERE ' . implode( ' AND ', $sql['where'] ) : '';
		$admin_users_array = $wpdb->get_results( "{$sql['select']} {$sql['where']}" );
		if ( ! empty( $admin_users_array ) ) {
			foreach ( $admin_users_array as $single_admin_user ) {
				$admin_user[] = $single_admin_user->user_id;
			}
		}

		return $admin_user;
	}

	/**
	 * Filters the "x members" count string for a group.
	 *
	 * @param string $count_string The "x members" count string for a group.
	 *
	 * @return string $count_string The "x members" count string for a group except admins.
	 */
	function bp_get_group_member_count_callback( $count_string ) {
		$admin_counter = $this->get_admins_list();
		if ( ! empty( $admin_counter ) ) {
			return $count_string . ' ( ' . sizeof( $admin_counter ) . ' Admin )';
		}

		return $count_string;
	}

	/**
	 * Filter to edit the count attribute for the nav item.
	 *
	 * @param int $count The count attribute for the nav item.
	 * @param object $nav_item The current nav item object.
	 * @param string $bp_nouveau The current nav in use (eg: 'directory', 'groups', 'personal', etc..).
	 *
	 * @return int $count The count attribute for the nav item except admin.
	 *
	 */
	function bp_get_group_total_members_callback( $count, $nav_item, $bp_nouveau ) {
		if ( $bp_nouveau == 'groups' ) {
			$old_counts  = $count;
			$admin_count = $this->get_admins_list();
			$count       = $old_counts - sizeof( $admin_count );
		}

		return $count;
	}
}

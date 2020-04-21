<?php

/**
 * BBGF_POSTS
 *
 * Display latest posts added by group admin
 * Extends BP_Group_Extension for using core functions
 *
 * @since 1.0.0
 */
class BBGF_POSTS extends BP_Group_Extension {
	/**
	 * Initial class
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		$args = array(
			'slug'              => 'latest-posts',
			'name'              => 'Latest Posts',
			'nav_item_position' => 5,
			'screens'           => array(
				'edit'   => array(
					'name'        => BBGF_NAME,
					'submit_text' => 'Posts',
				),
				'create' => array(
					'position' => 10,
				),
			),
		);
		parent::init( $args );
	}

	function display( $group_id = null ) {
		global $groups_template;
		$group     =& $groups_template->group;
		$admin_ids = bp_group_admin_ids( $group, '' );
		$args      = array(
			'author__in'     => $admin_ids,
			'posts_per_page' => 5,
			'post_status'    => 'publish',
			'post_type'      => 'post',
			'meta_query'     => [
				'relation' => 'OR',
				[ 'key' => 'votes', 'compare' => 'NOT EXISTS' ],
				[ 'key' => 'votes', 'compare' => 'EXISTS' ],
			],
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC'
		);
		$query     = new WP_Query( $args );
		while ( $query->have_posts() ):
			$query->the_post();
			$votes     = get_post_meta( get_the_ID(), 'votes', true );
			$author_id = $query->post_author;
			$author    = ucwords( str_replace( '-', ' ', get_the_author_meta( 'user_nicename', $author_id ) ) );
			?>
			<div class="single-post">
				<h1><a href="<?php echo get_the_permalink() ?>"><?php the_title(); ?></a></h1>

				<div class="left-side">
					<label><strong><?php esc_html_e( "Votes", 'BB-Groups-Feature' ) ?>
							: </strong><?php echo ! empty( $votes ) ? $votes : 0 ?></label>
					<label><strong><?php esc_html_e( "Author", 'BB-Groups-Feature' ) ?>
							: </strong><?php echo $author; ?></label>

				</div>
				<div class="right-side">
					<?php
					$get_parent_cats = array(
						'taxonomy' => 'category',
						'fields'   => 'all',
						'parent'   => '0'
					);
					$cat_array       = wp_get_post_categories( get_the_ID(), $get_parent_cats );
					foreach ( $cat_array as $single_category ) {
						echo '<div class="cat-wrapper">';
						$catID = $single_category->term_id;
						echo '<label class="parent-cat">' . $single_category->name . ':&nbsp;</label>';
						$get_children_cats = array(
							'child_of' => $catID,
							'fields'   => 'all',
						);
						$child_cats        = wp_get_post_categories( get_the_ID(), $get_children_cats );
						$child_names       = array();
						foreach ( $child_cats as $child_cat ) {
							$child_names[] = $child_cat->name;
						}
						echo '<label class="child-cat">' . implode( ', ', $child_names ) . '</label>';
						echo '</div>';
					}
					?>
				</div>
				<div class="clearfix"></div>
			</div>
		<?php
		endwhile;
		wp_reset_postdata();
	}
}

bp_register_group_extension( 'BBGF_POSTS' );
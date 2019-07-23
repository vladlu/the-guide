<?php
/**
 * The Guide Uninstall
 *
 * Deletes The Guide options, posts (tours).
 *
 * @package The Guide
 * @version 2.3.0
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


/*
 * Deletes 'the-guide-settings' option.
 */
delete_option( 'the-guide-settings' );


/*
 * Deletes all posts with the type 'the-guide'.
 */
$query = new WP_Query(
	[
		'post_type'      => 'the-guide',
		'posts_per_page' => -1,
	]
);
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();

		wp_delete_post( get_the_ID(), true );
	}
	wp_reset_postdata();
}

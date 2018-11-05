<?php

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}



delete_option('the-guide-settings');


// Deletes all posts with type 'the-guide'
$query = new WP_Query( [ 'post_type' => 'the-guide', 'posts_per_page' => -1 ] );
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();

		wp_delete_post( get_the_ID(), true );
	}
	wp_reset_postdata();
}
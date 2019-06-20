<?php
/**
 * A file that contains different functions
 *
 * @package The Guide
 * @since 0.1.3
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Duplicates a post by its id.
 *
 * @since 0.1.3
 *
 * @param  int  $post_id The id of the post to duplicate.
 * @global wpdb $wpdb    WordPress database abstraction object.
 */
function the_guide_duplicate_post( $post_id ) {
	global $wpdb;


	$post = get_post( $post_id );



	/* Verifications */

	if ( ! isset( $post ) || $post == null) {
		return;
	}



	/*
	 * New post data array
	 */
	$args = [
		'comment_status' => $post->comment_status,
		'ping_status'    => $post->ping_status,
		'post_author'    => wp_get_current_user()->ID,
		'post_content'   => $post->post_content,
		'post_excerpt'   => $post->post_excerpt,
		'post_name'      => $post->post_name,
		'post_parent'    => $post->post_parent,
		'post_password'  => $post->post_password,
		'post_status'    => 'publish',
		'post_title'     => $post->post_title,
		'post_type'      => $post->post_type,
		'to_ping'        => $post->to_ping,
		'menu_order'     => $post->menu_order
	];

	/*
	 * Insert the post by wp_insert_post() function
	 */
	$new_post_id = wp_insert_post( $args );

	/*
	 * Get all current post terms ad set them to the new post draft
	 */
	$taxonomies = get_object_taxonomies( $post->post_type ); // Returns array of taxonomy names for post type, ex array("category", "post_tag");
	foreach ( $taxonomies as $taxonomy ) {
		$post_terms = wp_get_object_terms( $post_id, $taxonomy, [ 'fields' => 'slugs' ] );
		wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
	}

	/*
	 * Duplicate all post meta just in two SQL queries
	 */
	$post_meta_infos = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id" );
	if ( count( $post_meta_infos ) != 0 ) {
		$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";

		foreach ( $post_meta_infos as $meta_info ) {
			$meta_key = $meta_info->meta_key;

			if ( $meta_key == '_wp_old_slug' ) {
				continue;
			}

			$meta_value      = addslashes( $meta_info->meta_value );
			$sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
		}

		$sql_query .= implode( " UNION ALL ", $sql_query_sel );
		$wpdb->query( $sql_query );
	}
}

<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class The_Guide_Prioritize {


	public function __construct() {
		add_filter( 'views_edit-the-guide', [ $this, 'tours_views' ] );
		add_action( 'pre_get_posts',        [ $this, 'change_sorting' ] );
	}



	/**
	 * Change views on the edit tours screen (Adds a "Prioritize" button).
	 *
	 * @param  array $views Array of views.
	 * @return array
	 */
	public function tours_views( $views ) {
		// Add a prioritize link.
		if ( current_user_can( 'edit_others_pages' ) ) {
			$arg_name  = "the-guide-sorting";
			$arg_value = "true";


			$class               = ( isset( $_REQUEST[ $arg_name ] ) && $arg_value === $_REQUEST[ $arg_name ] ) ? 'current' : '';
			$prioritize_url      = add_query_arg( $arg_name, rawurlencode( $arg_value ) );
			$views['prioritize'] = '<a href="' . $prioritize_url . '" class="' . esc_attr( $class ) . '">' . __( 'Prioritize', 'the-guide' ) . '</a>';
		}

		return $views;
	}



	/**
	 * Changes the sorting of the tours for List Table.
	 *
	 * @param WP_Query $query The current WP_Query instance.
	 * @return array
	 */
	public function change_sorting( $query) {
		$screen = get_current_screen();
		if ( 'edit'      == $screen->base      &&
			 'the-guide' == $screen->post_type &&
		     ! isset( $_GET['orderby'] )
		) {
			$query->set( 'orderby', 'menu_order title' );
			$query->set( 'order',   'ASC' );
		}
	}
}
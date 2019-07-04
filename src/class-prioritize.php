<?php
/**
 * Tours prioritizing
 *
 * - Change views, adding the prioritizing button;
 * - Change tours sorting based on their priority using "pre_get_posts".
 *
 * @package The Guide
 * @since 0.1.3
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Tours prioritizing.
 *
 * @since 0.1.3
 */
class The_Guide_Prioritize {


	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_filter( 'views_edit-the-guide', [ $this, 'tours_views' ] );
		add_action( 'pre_get_posts',        [ $this, 'change_sorting' ] );
	}



	/**
	 * Change views, adding the prioritizing button.
	 *
	 * @since 0.1.0
	 *
	 * @param  array $views Array of views.
	 * @return array        Array of views.
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
	 * Change tours sorting based on their priority using "pre_get_posts".
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Query $query The current WP_Query instance.
	 */
	public function change_sorting( $query ) {
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
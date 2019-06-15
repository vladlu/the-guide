<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class The_Guide_Ajax {

	/**
	 * Settings object.
	 *
	 * @since 0.1.0
	 * @var object The_Guide_Settings.
	 */
	private $settings;



	public function __construct( The_Guide_Settings $settings_inst ) {
		$this->settings = $settings_inst;

		add_action(        'wp_ajax_the_guide_public_init', [ $this, 'public_init' ] );
		add_action( 'wp_ajax_nopriv_the_guide_public_init', [ $this, 'public_init' ] );
		add_action(        'wp_ajax_the_guide_public_get_tour_data_by_id', [ $this, 'public_get_tour_data_by_id' ] );
		add_action( 'wp_ajax_nopriv_the_guide_public_get_tour_data_by_id', [ $this, 'public_get_tour_data_by_id' ] );
		add_action(        'wp_ajax_the_guide_public_get_custom_css', [ $this, 'public_get_custom_css' ] );
		add_action( 'wp_ajax_nopriv_the_guide_public_get_custom_css', [ $this, 'public_get_custom_css' ] );
		if ( current_user_can( 'list_users' ) ) {
			add_action( 'wp_ajax_the_guide_reorder_tours',    [ $this, 'reorder_tours' ] );
			add_action( 'wp_ajax_the_guide_menu_customize',   [ $this, 'menu_customize' ] );
		}
	}



	/*
	 * Returns the tour data by the URL (if there no shortcodes –
	 * otherwise returns the tour data from the shortcode).
	 *
	 * Accepts: $_POST['url']
	 *
	 * No nonce check
	 */
	public function public_init() {

		/* Verifications */

		if ( ! isset( $_POST['url'] ) ) {
			wp_die();
		}



		/**
		 * URL
		 *
		 * @since 0.1.0
		 * @var string URL.
		 */
		$current_url = $_POST['url'];

		$all_enabled_tours_for_this_url     = [];
		$first_enabled_tour_id_for_this_url = null;
		$is_there_an_enabled_tour           = false;


		$all_enabled_tours = new WP_Query( [
			'post_type'      => 'the-guide',
			'posts_per_page' => -1,
			'meta_query'     => [
				[
					'key'     => 'the-guide-is-enabled',
					'value'   => 1,
					'compare' => '=',
				]
			],
			'orderby' => 'menu_order title',
			'order'   => 'ASC'
		] );

		if ( $all_enabled_tours->have_posts() ) {
			while ( $all_enabled_tours->have_posts() ) {
				$all_enabled_tours->the_post();

				$tour_id  = get_the_ID();
				$tour_url = get_post_meta( $tour_id, 'the-guide-url', true );

				if (
					get_post_status( $tour_id ) === 'publish' && // Only published tours
					// removes protocols
					preg_replace("(^https?://)", "", $current_url ) === $tour_url  // that match the URL
				) {
					if ( ! $first_enabled_tour_id_for_this_url ) {
						$first_enabled_tour_id_for_this_url = $tour_id;
					}

					array_push( $all_enabled_tours_for_this_url, (int) $tour_id );

					$is_there_an_enabled_tour = true;
				}
			}
			wp_reset_postdata();
		}


		// Shortcode: the-guide-launch
		if (
			defined( 'THE_GUIDE_DOING_SHORTCODE_LAUNCH' ) &&
			defined( 'THE_GUIDE_SHORTCODE_LAUNCH_ID' ) &&
			defined( 'THE_GUIDE_SHORTCODE_LAUNCH_STEP' )
		) {
			$the_guide_data = [
				'allEnabledToursForThisURL' => $all_enabled_tours_for_this_url,
				'TourID'                    => THE_GUIDE_SHORTCODE_LAUNCH_ID,
				'elemIndex'                 => THE_GUIDE_SHORTCODE_GO_STEP
			];
		// Shortcode: the-guide-go
		} elseif (
			defined( 'THE_GUIDE_DOING_SHORTCODE_GO' ) &&
			defined( 'THE_GUIDE_SHORTCODE_GO_STEP' )
		) {
			$the_guide_data = [
				'allEnabledToursForThisURL' => $all_enabled_tours_for_this_url,
				'TourID'                    => $first_enabled_tour_id_for_this_url,
				'elemIndex'                 => THE_GUIDE_SHORTCODE_GO_STEP
			];
		// Without shortcodes
		} elseif (
		$is_there_an_enabled_tour
		) {
			$the_guide_data = [
				'allEnabledToursForThisURL' => $all_enabled_tours_for_this_url,
				'TourID'                    => $first_enabled_tour_id_for_this_url,
				'elemIndex'                 => 0
			];
		}


		if ( isset( $the_guide_data ) ) {

			/*
			 * Adds nonce
			 */
			$the_guide_data = array_merge($the_guide_data, [
				'nonceTokenGetTourDataByID' => wp_create_nonce( 'the-guide-get-tour-data-by-id' ),
				'nonceTokenGetCustomCSS'    => wp_create_nonce( 'the-guide-get-custom-css' ),
			]);

			/*
			 * Returns
			 */
			echo json_encode( $the_guide_data );
		}



		wp_die();
	}



	/*
	 * Accepts: $_POST['nonceToken']
	 *          $_POST['id']
	 */
	public function public_get_tour_data_by_id() {

		/* Nonce Check */
		check_ajax_referer( 'the-guide-get-tour-data-by-id', 'nonceToken' );



		$the_tour_data = [];

		$the_tour_data['steps']                      = get_post_meta( $_POST['id'], 'the-guide-steps', true );
		$the_tour_data['stepsContent']               = get_post_meta( $_POST['id'], 'the-guide-steps-content', true );
		$the_tour_data['activationMethodAndItsData'] = get_post_meta( $_POST['id'], 'the-guide-activation-method-and-its-data', true );
		$the_tour_data['controllerMethodAndItsData'] = get_post_meta( $_POST['id'], 'the-guide-controller-method-and-its-data', true );

		echo json_encode( $the_tour_data );



		wp_die();
	}



	/*
     * Accepts: $_POST['nonceToken']
     */
	public function public_get_custom_css() {

		/* Nonce Check */
		check_ajax_referer( 'the-guide-get-custom-css', 'nonceToken' );



		$custom_css = $this->settings->get_plugin_setting( 'custom-css' );
		echo $custom_css;



		wp_die();
	}



	/*
	 * Accepts: $_POST['nonceToken']
	 *          $_POST['customCSS']
	 */
	public function menu_customize() {

		/* Nonce Check */
		check_ajax_referer( 'the-guide-menu-customize', 'nonceToken' );



		$this->settings->save_plugin_setting( 'custom-css', stripslashes( $_POST['customCSS'] ) );



		wp_die();
	}



	/**
	 * Ajax request handling for tours ordering.
	 *
	 * Based on WooCommerce sorting
	 * includes/class-wc-ajax.php
	 *
	 * Accepts: $_POST['nonceToken']
	 *          $_POST['id']
	 *          $_POST['previd']
	 *          $_POST['nextid']
	 */
	public function reorder_tours() {
		global $wpdb;



		/* Verifications */

		check_ajax_referer( 'the-guide-reorder-tours', 'nonceToken' );

		if ( empty( $_POST['id'] ) ) {
			wp_die( -1 );
		}



		$sorting_id  = absint( $_POST['id'] );
		$previd      = absint( isset( $_POST['previd'] ) ? $_POST['previd'] : 0 );
		$nextid      = absint( isset( $_POST['nextid'] ) ? $_POST['nextid'] : 0 );
		$menu_orders = wp_list_pluck( $wpdb->get_results( "SELECT ID, menu_order FROM {$wpdb->posts} WHERE post_type = 'the-guide' ORDER BY menu_order ASC, post_title ASC" ), 'menu_order', 'ID' );
		$index       = 0;

		foreach ( $menu_orders as $id => $menu_order ) {
			$id = absint( $id );

			if ( $sorting_id === $id ) {
				continue;
			}
			if ( $nextid === $id ) {
				$index ++;
			}
			$index ++;
			$menu_orders[ $id ] = $index;
			$wpdb->update( $wpdb->posts, array( 'menu_order' => $index ), array( 'ID' => $id ) );

			/**
			 * When a single tour has gotten it's ordering updated.
			 * $id The product ID
			 * $index The new menu order
			 */
			do_action( 'the-guide_after_single_tour_ordering', $id, $index );
		}

		if ( isset( $menu_orders[ $previd ] ) ) {
			$menu_orders[ $sorting_id ] = $menu_orders[ $previd ] + 1;
		} elseif ( isset( $menu_orders[ $nextid ] ) ) {
			$menu_orders[ $sorting_id ] = $menu_orders[ $nextid ] - 1;
		} else {
			$menu_orders[ $sorting_id ] = 0;
		}

		$wpdb->update( $wpdb->posts, array( 'menu_order' => $menu_orders[ $sorting_id ] ), array( 'ID' => $sorting_id ) );

		do_action( 'the-guide_after_tour_ordering', $sorting_id, $menu_orders );
		wp_send_json( $menu_orders );
	}
}

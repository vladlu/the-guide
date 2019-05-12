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
			add_action( 'wp_ajax_the_guide_customize_menu', [ $this, 'customize_menu' ] );
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
		if ( isset( $_POST['url'] ) ) {
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
				]
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
					'nonceGetTourDataByID' => wp_create_nonce( 'the-guide-get-tour-data-by-id' ),
					'nonceGetCustomCSS'    => wp_create_nonce( 'the-guide-get-custom-css' ),
				]);

				/*
				 * Returns
				 */
				echo json_encode( $the_guide_data );
			}
		}
		wp_die();
	}



	/*
	 * Accepts: $_POST['token']
	 *          $_POST['id']
	 */
	public function public_get_tour_data_by_id() {
		if ( wp_verify_nonce( $_POST['token'], 'the-guide-get-tour-data-by-id' ) ) {
			$the_tour_data = [];

			$the_tour_data['steps']                      = get_post_meta( $_POST['id'], 'the-guide-steps', true );
			$the_tour_data['stepsContent']               = get_post_meta( $_POST['id'], 'the-guide-steps-content', true );
			$the_tour_data['activationMethodAndItsData'] = get_post_meta( $_POST['id'], 'the-guide-activation-method-and-its-data', true );
			$the_tour_data['controllerMethodAndItsData'] = get_post_meta( $_POST['id'], 'the-guide-controller-method-and-its-data', true );

			echo json_encode( $the_tour_data );
		}
		wp_die();
	}



	/*
     * Accepts: $_POST['token']
     */
	public function public_get_custom_css() {
		if ( wp_verify_nonce( $_POST['token'], 'the-guide-get-custom-css' ) ) {
			$custom_css = $this->settings->get_plugin_setting( 'custom-css' );
			echo $custom_css;
		}
		wp_die();
	}



	/*
	 * Accepts: $_POST['token']
	 *          $_POST['customCSS']
	 */
	public function customize_menu() {
		if ( wp_verify_nonce( $_POST['token'], 'the-guide-customize-menu' ) ) {
			$this->settings->save_plugin_setting( 'custom-css', stripslashes( $_POST['customCSS'] ) );
		}
		wp_die();
	}
}

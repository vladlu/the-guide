<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Manages public assets.
 *
 * @since 0.1.0
 */
class The_Guide_Public_Assets {

	/**
	 * Settings object.
	 *
	 * @since 0.1.0
	 * @var The_Guide_Settings
	 */
	private $settings;


	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param The_Guide_Settings $settings_inst Settings object
	 */
	public function __construct( The_Guide_Settings $settings_inst ) {
		$this->settings = $settings_inst;

		add_action( 'wp_enqueue_scripts', [ $this, 'public_assets_controller' ] );
	}


	/**
	 * Controller of public assets. Main method in this class.
	 *
	 * @since 0.1.0
	 */
	public function public_assets_controller() {

		$current_url = ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


		$first_enabled_tour_id_for_this_url = null;
		$all_enabled_tours_for_this_url     = [];
		$is_there_enabled_tour              = false;


		$all_enabled_tours = $this->settings->get_plugin_setting( 'enabled-tours' );
		// Search current tour by its url
		if ( $all_enabled_tours ) {
			foreach ( $all_enabled_tours as $tour_id ) {

				$pattern = '~' . preg_quote( get_post_meta( $tour_id, 'the-guide-url', true ) ) . '~' . 'u';
				if ( preg_match( $pattern, $current_url ) ) {
					if ( ! $first_enabled_tour_id_for_this_url ) {
						$first_enabled_tour_id_for_this_url = $tour_id;
					}

					array_push( $all_enabled_tours_for_this_url, (int) $tour_id );

					$is_there_enabled_tour = true;
				}
			}
		}


		// Shortcode [the-guide-launch]
		if ( defined( 'THE_GUIDE_DOING_SHORTCODE_LAUNCH' ) ) {
			if ( defined( 'THE_GUIDE_SHORTCODE_LAUNCH_ID' ) && defined( 'THE_GUIDE_SHORTCODE_LAUNCH_STEP' ) ) {
				$this->load_public_assets( $all_enabled_tours_for_this_url, THE_GUIDE_SHORTCODE_LAUNCH_ID, THE_GUIDE_SHORTCODE_LAUNCH_STEP );
			}
		// Shortcode [the-guide-go]
		} elseif ( defined( 'THE_GUIDE_DOING_SHORTCODE_GO' ) ) {
			if ( $is_there_enabled_tour ) {
				if ( defined( 'THE_GUIDE_SHORTCODE_GO_STEP' ) ) {
					$this->load_public_assets( $all_enabled_tours_for_this_url, $first_enabled_tour_id_for_this_url, THE_GUIDE_SHORTCODE_GO_STEP );
				}
			}
		// Without shortcodes
		} else {
			if ( $is_there_enabled_tour ) {
				$this->load_public_assets( $all_enabled_tours_for_this_url, $first_enabled_tour_id_for_this_url, 0 );
			}
		}
	}


	/**
	 * Loads public assets.
	 *
	 * Possible Environment Influence:
	 *  -
	 *
	 * @since 0.1.0
	 *
	 * @see The_Guide_Public_Assets::public_assets_controller()
	 *
	 * @param string[] $all_enabled_tours_for_this_url
	 * @param string $tour_id ID of the current post with type 'the-guide'
	 * @param int $first_tour_step The step number with which the tour will appear
	 */
	private function load_public_assets( $all_enabled_tours_for_this_url, $tour_id, $first_tour_step ) {

		/**
		 * ID of the current post with type 'the-guide'.
		 *
		 * @var int
		 */
		$tour_id = (int) $tour_id;


		if ( ! $this->is_current_user_watched_this_tour( $tour_id ) ) {

			/**
			 *
			 *
			 * @var
			 */
			$custom_css = $this->settings->get_plugin_setting( 'custom-css' );


			/**
			 * loads Babel polyfill
			 */
			wp_enqueue_script(
				'the-guide-script-babel-polyfill',
				THE_GUIDE_URL . 'libs/babel-polyfill/babel-polyfill.js',
				[],
				THE_GUIDE_VERSION
			);


			/**
			 * loads HTML
			 */
			require_once( THE_GUIDE_DIR . 'src/templates/the-guide.php' );

			/**
			 * loads CSS
			 */
			wp_enqueue_style(
				'the-guide-style-public',
				THE_GUIDE_URL . 'public/styles/the-guide.css',
				[],
				THE_GUIDE_VERSION
			);

			/**
			 * loads JS
			 */
			wp_enqueue_script(
				'the-guide-script-public',
				THE_GUIDE_URL . 'public/scripts/the-guide.js',
				[ 'jquery-ui-draggable' ],
				THE_GUIDE_VERSION
			);

			/**
			 * data to JS
			 */
			wp_localize_script( 'the-guide-script-public', 'theGuide', [
				'TourID'                    => $tour_id,
				'elemIndex'                 => $first_tour_step,
				'allEnabledToursForThisURL' => $all_enabled_tours_for_this_url,

				'translates' => [
					'next'   => __( 'Next', 'the-guide' ),
					'finish' => __( 'Finish', 'the-guide' )
				],

				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'token'   => wp_create_nonce( 'the-guide-public' ),
			] );

			/**
			 * Custom public CSS
			 */
			wp_add_inline_style( 'the-guide-style-public', $custom_css );
		}
	}


	/**
	 * Checks if the current user has watched this tour. Updates this to true, if not yet.
	 *
	 * Possible Environment Influence:
	 *  - post meta 'the-guide-who-watched'
	 *
	 * @since 0.1.0
	 *
	 * @see The_Guide_Public_Assets::load_public_assets
	 *
	 * @param int $tour_id ID of the current post with type 'the-guide'.
	 *
	 * @return bool Is current user watched this tour
	 */
	private function is_current_user_watched_this_tour( $tour_id ) {

		/**
		 * ID of the current user.
		 *
		 * @var int
		 */
		$current_user_id = get_current_user_id();

		/**
		 * List of all users who watched this tour, or empty string.
		 *
		 * @var int[]|string
		 */
		$all_users_who_watched = get_post_meta( $tour_id, 'the-guide-who-watched', true );


		if ( '' === $all_users_who_watched ) {
			$all_users_who_watched = [];
		}


		if ( DEV_MODE ) {
			return false;
		} else {
			if ( in_array( $current_user_id, $all_users_who_watched ) ) {
				return true;
			} else {
				$all_users_who_watched[] = $current_user_id;
				update_post_meta( $tour_id, 'the-guide-who-watched', $all_users_who_watched );

				return false;
			}
		}
	}
}
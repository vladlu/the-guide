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

		add_action( 'wp_enqueue_scripts', [ $this, 'load_public_assets' ] );
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
	public function load_public_assets() {

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
//			'TourID'                    => $tour_id,
//			'elemIndex'                 => $first_tour_step,
//			'allEnabledToursForThisURL' => $all_enabled_tours_for_this_url,
//
			'translates' => [
				'start'     => __( 'Start the tour', 'the-guide' ),
				'previous'  => __( 'Previous',       'the-guide' ),
				'next'      => __( 'Next',           'the-guide' ),
				'finish'    => __( 'Finish',         'the-guide' )
			],

			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'token'   => wp_create_nonce( 'the-guide-public' ),
		] );



//		/**
//		 * Custom CSS
//		 *
//		 * @var $custom_css
//		 */
//		$custom_css = $this->settings->get_plugin_setting( 'custom-css' );
//
//		/**
//		 * Custom public CSS
//		 */
//		wp_add_inline_style( 'the-guide-style-public', $custom_css );
	}
}

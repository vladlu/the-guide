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
	 * Suffix for assets.
	 * Either empty string or ".min"
	 *
	 * @var string
	 */
	public $assets_suffix;



	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param The_Guide_Settings $settings_inst Settings object
	 */
	public function __construct( The_Guide_Settings $settings_inst ) {
		$this->settings = $settings_inst;
		$this->assets_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

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
			THE_GUIDE_URL . 'libs/babel-polyfill/babel-polyfill' . $this->assets_suffix . '.js',
			[],
			THE_GUIDE_VERSION
		);


		/**
		 * loads CSS
		 */
		wp_enqueue_style(
			'the-guide-style-public-main',
			THE_GUIDE_URL . 'public/styles/main' . $this->assets_suffix . '.css',
			[],
			THE_GUIDE_VERSION
		);
		wp_enqueue_style(
			'the-guide-style-public',
			THE_GUIDE_URL . 'public/styles/the-guide' . $this->assets_suffix . '.css',
			[ 'the-guide-style-public-main' ],
			THE_GUIDE_VERSION
		);

		/**
		 * loads JS
		 */
		wp_enqueue_script(
			'the-guide-script-public',
			THE_GUIDE_URL . 'public/scripts/the-guide' . $this->assets_suffix . '.js',
			[ 'jquery-ui-draggable' ],
			THE_GUIDE_VERSION
		);
		wp_enqueue_script(
			'the-guide-script-custom-css',
			THE_GUIDE_URL . 'public/scripts/custom-css' . $this->assets_suffix . '.js',
			[ 'the-guide-script-public', 'jquery' ],
			THE_GUIDE_VERSION
		);


		/**
		 * data to JS
		 */
		wp_localize_script( 'the-guide-script-public', 'theGuide', [
			'translates' => [
				'start'     => __( 'Start the tour', 'the-guide' ),
				'previous'  => __( 'Previous',       'the-guide' ),
				'next'      => __( 'Next',           'the-guide' ),
				'finish'    => __( 'Finish',         'the-guide' )
			],
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		] );
	}
}

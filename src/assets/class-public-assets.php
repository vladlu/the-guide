<?php
/**
 * Public assets (JS, CSS) files
 *
 * The assets for the frontend of the site:
 * Loads them, adds some data for them etc.
 *
 * @package The Guide
 * @since 0.1.3
 */


// Exit if accessed directly.
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
	 * @var The_Guide_Settings $settings
	 */
	private $settings;


	/**
	 * Suffix for assets.
	 *
	 * Either empty string or ".min".
	 *
	 * @since 0.1.0
	 * @var string $assets_suffix
	 */
	public $assets_suffix;



	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param The_Guide_Settings $settings_inst Settings Object.
	 */
	public function __construct( The_Guide_Settings $settings_inst ) {
		$this->settings = $settings_inst;
		$this->assets_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		add_action( 'wp_enqueue_scripts', [ $this, 'load_public_assets' ] );
	}



	/**
	 * Loads public assets.
	 *
	 * @since 0.1.0
	 */
	public function load_public_assets() {


		/**
		 * CSS.
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
		 * JS.
		 */
		wp_enqueue_script(
			'the-guide-script-public',
			THE_GUIDE_URL . 'public/scripts/the-guide' . $this->assets_suffix . '.js',
			[ 'jquery-ui-draggable' ],
			THE_GUIDE_VERSION
		);


		/**
		 * jQuery UI Touch Punch.
		 */
		wp_enqueue_script(
			'the-guide-script-jquery.ui.touch-punch',
			THE_GUIDE_URL . 'libs/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js',
			[ 'jquery-ui-draggable', 'the-guide-script-public' ],
			THE_GUIDE_VERSION
		);


		/**
		 * Data to JS.
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

<?php
/**
 * Admin assets (JS, CSS) files
 *
 * The assets for the dashboard:
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
 * Manages admin assets.
 *
 * @since 0.1.0
 */
class The_Guide_Admin_Assets {

	
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
	 */
	public function __construct() {
		$this->assets_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		$this->load_admin_assets();
	}



	/**
	 * The entrypoint to load admin assets.
	 *
	 * @since 0.1.0
	 */
	private function load_admin_assets() {

		/**
		 * General.
		 */
		add_action( 'admin_enqueue_scripts', [ $this, 'load_general_assets' ] );


		/**
		 * Customize menu.
		 */
		add_action( 'load-the-guide_page_the-guide-customize', [
			$this, 'load_menu_customize_assets'
		] );
	}



	/**
	 * Loads general assets.
	 *
	 * Loads assets that are not specific to some pages,
	 * so these assets reside on all dashboard pages.
	 *
	 * @since 0.1.0
	 */
	public function load_general_assets() {


		/**
		 * Tours List Table
		 *
		 *      CSS
		 */
		wp_enqueue_style(
			'the-guide-style-admin-tours-list-table',
			THE_GUIDE_URL . 'admin/styles/tours-list-table' . $this->assets_suffix . '.css',
			[],
			THE_GUIDE_VERSION
		);


		/**
		 *      JS
		 */
		wp_enqueue_script(
			'the-guide-script-admin-tours-list-table',
			THE_GUIDE_URL . 'admin/scripts/tours-list-table' . $this->assets_suffix . '.js',
			[ 'jquery-ui-sortable' ],
			THE_GUIDE_VERSION
		);


		/**
		 *      Data to JS
		 */
		wp_localize_script( 'the-guide-script-admin-tours-list-table', 'theGuide', [
			'nonceTokenReorderTours' => wp_create_nonce( 'the-guide-reorder-tours' ),
		] );



		/**
		 * Add/Edit Tour
		 *
		 *      CSS
		 */
		wp_enqueue_style(
			'the-guide-style-admin-meta-boxes',
			THE_GUIDE_URL . 'admin/styles/meta-boxes' . $this->assets_suffix . '.css',
			[],
			THE_GUIDE_VERSION
		);


		/*
		 *      JS
		 */
		wp_enqueue_script(
			'the-guide-script-admin-meta-boxes',
			THE_GUIDE_URL . 'admin/scripts/meta-boxes' . $this->assets_suffix . '.js',
			[],
			THE_GUIDE_VERSION
		);
	}



	/**
	 * Loads assets for Customize menu.
	 *
	 * @since 0.1.0
	 */
	public function load_menu_customize_assets() {


		/**
		 * CSS
		 */
		wp_enqueue_style(
			'the-guide-style-admin-menu-customize',
			THE_GUIDE_URL . 'admin/styles/menu-customize' . $this->assets_suffix .  '.css',
			[],
			THE_GUIDE_VERSION
		);


		/**
		 * JS
		 */
		wp_enqueue_script(
			'the-guide-script-admin-menu-customize',
			THE_GUIDE_URL . 'admin/scripts/menu-customize' . $this->assets_suffix . '.js',
			[ 'jquery' ],
			THE_GUIDE_VERSION
		);


		/**
		 * Codemirror
		 */
		wp_enqueue_script(
			'the-guide-script-admin-customize-codemirror-main',
			THE_GUIDE_URL . 'libs/codemirror/codemirror.js',
			[],
			THE_GUIDE_VERSION
		);

		wp_enqueue_script(
			'the-guide-script-admin-customize-codemirror-css',
			THE_GUIDE_URL . 'libs/codemirror/css.js',
			[],
			THE_GUIDE_VERSION
		);

		wp_enqueue_style(
			'the-guide-style-admin-menu-customize-codemirror',
			THE_GUIDE_URL . 'libs/codemirror/codemirror.css',
			[],
			THE_GUIDE_VERSION
		);


		/**
		 * Data to JS
		 */
		wp_localize_script( 'the-guide-script-admin-menu-customize', 'theGuide', [
			'nonceToken' => wp_create_nonce( 'the-guide-menu-customize' ),
		] );
	}
}

<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class The_Guide_Admin_Assets {

	
	/**
	 * Suffix for assets.
	 * Either empty string or ".min"
	 *
	 * @var string
	 */
	public $assets_suffix;



	public function __construct() {
		$this->assets_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		$this->load_admin_assets();
	}



	private function load_admin_assets() {

		/**
		 * - General
		 *
		 * - All Tours menu
		 */
		add_action( 'admin_enqueue_scripts', [ $this, 'load_general_assets' ] );


		/**
		 * Customize menu
		 */
		add_action( 'load-the-guide_page_the-guide-customize', [
			$this, 'load_menu_customize_assets'
		] );
	}



	public function load_general_assets() {


		/**
		 * GENERAL
		 *
		 *      Babel Polyfill
		 */
		wp_enqueue_script(
			'the-guide-script-babel-polyfill',
			THE_GUIDE_URL . 'libs/babel-polyfill/babel-polyfill' . $this->assets_suffix . '.js',
			[],
			THE_GUIDE_VERSION
		);



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
			'tokenReorderTours' => wp_create_nonce( 'the-guide-reorder-tours' ),
		] );


		/**
		 * Add/Edit Tour
		 *
		 *      CSS
		 */
		wp_enqueue_style(
			'the-guide-style-admin-add-edit-tour',
			THE_GUIDE_URL . 'admin/styles/add-edit-tour' . $this->assets_suffix . '.css',
			[],
			THE_GUIDE_VERSION
		);


		/*
		 *      JS
		 */
		wp_enqueue_script(
			'the-guide-script-admin-add-edit-tour',
			THE_GUIDE_URL . 'admin/scripts/add-edit-tour' . $this->assets_suffix . '.js',
			[],
			THE_GUIDE_VERSION
		);
	}



	public function load_menu_customize_assets() {


		/**
		 * Codemirror
		 */
		wp_enqueue_script(
			'the-guide-script-admin-customize-codemirror-main',
			THE_GUIDE_URL . 'libs/codemirror/codemirror' . $this->assets_suffix . '.js',
			[],
			THE_GUIDE_VERSION
		);

		wp_enqueue_script(
			'the-guide-script-admin-customize-codemirror-css',
			THE_GUIDE_URL . 'libs/codemirror/css' . $this->assets_suffix . '.js',
			[],
			THE_GUIDE_VERSION
		);

		wp_enqueue_style(
			'the-guide-style-admin-menu-customize-codemirror',
			THE_GUIDE_URL . 'libs/codemirror/codemirror' . $this->assets_suffix . '.css',
			[],
			THE_GUIDE_VERSION
		);


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
		 * Data to JS
		 */
		wp_localize_script( 'the-guide-script-admin-menu-customize', 'theGuide', [
			'token' => wp_create_nonce( 'the-guide-menu-customize' ),
		] );
	}
}

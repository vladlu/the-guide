<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class The_Guide_Admin_Assets {


	public function __construct() {
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
			$this, 'load_customize_menu_assets'
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
			THE_GUIDE_URL . 'libs/babel-polyfill/babel-polyfill.js',
			[],
			THE_GUIDE_VERSION
		);



		/**
		 * All Tours menu
		 *
		 *      JS
		 */
		wp_enqueue_script(
			'the-guide-script-admin-all-tours-menu',
			THE_GUIDE_URL . 'admin/scripts/dashboard-all-tours.js',
			[],
			THE_GUIDE_VERSION
		);


		/**
		 *      CSS
		 */
		wp_enqueue_style(
			'the-guide-style-admin-all-tours-menu',
			THE_GUIDE_URL . 'admin/styles/dashboard-all-tours.css',
			[],
			THE_GUIDE_VERSION
		);



		/**
		 * Add/Edit Tour menu
		 *
		 *      JS
		 */
		wp_enqueue_script(
			'the-guide-script-admin-add-edit-tour-menu',
			THE_GUIDE_URL . 'admin/scripts/dashboard-add-edit-tour.js',
			[],
			THE_GUIDE_VERSION
		);


		/**
		 *      CSS
		 */
		wp_enqueue_style(
			'the-guide-style-admin-add-edit-tour-menu',
			THE_GUIDE_URL . 'admin/styles/dashboard-add-edit-tour.css',
			[],
			THE_GUIDE_VERSION
		);
	}



	public function load_customize_menu_assets() {


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
			'the-guide-style-admin-customize-menu-codemirror',
			THE_GUIDE_URL . 'libs/codemirror/codemirror.css',
			[],
			THE_GUIDE_VERSION
		);


		/**
		 * loads CSS
		 */
		wp_enqueue_style(
			'the-guide-style-admin-customize-menu',
			THE_GUIDE_URL . 'admin/styles/dashboard-menu-customize.css',
			[],
			THE_GUIDE_VERSION
		);


		/**
		 * loads JS
		 */
		wp_enqueue_script(
			'the-guide-script-admin-customize-menu',
			THE_GUIDE_URL . 'admin/scripts/dashboard-menu-customize.js',
			[ 'jquery' ],
			THE_GUIDE_VERSION
		);


		/**
		 * data to JS
		 */
		wp_localize_script( 'the-guide-script-admin-customize-menu', 'theGuide', [
			'token' => wp_create_nonce( 'the-guide-customize-menu' ),
		] );
	}
}

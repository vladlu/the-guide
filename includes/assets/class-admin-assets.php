<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class The_Guide_Admin_Assets {

	/**
	 * Settings object.
	 *
	 * @since 0.1.0
	 * @var object The_Guide_Settings
	 */
	private $settings;



	private function get_admin_js_data() {
		$all_posts_data = [];

		$query = new WP_Query( [ 'post_type' => 'the-guide', 'posts_per_page' => -1 ] );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();


				$the_post_data = [];
				$the_post_ID = get_the_ID();

				$the_post_data['id'] =           $the_post_ID;
				$the_post_data['name'] =         get_the_title();
				$the_post_data['url'] =          get_post_meta( $the_post_ID, 'the-guide-url', true );
				$the_post_data['steps'] =        get_post_meta( $the_post_ID, 'the-guide-steps', true );
				$the_post_data['stepsContent'] = get_post_meta( $the_post_ID, 'the-guide-steps-content', true );
				$the_post_data['activationMethodAndItsData'] = get_post_meta( $the_post_ID, 'the-guide-activation-method-and-its-data', true );
				$the_post_data['controllerMethodAndItsData'] = get_post_meta( $the_post_ID, 'the-guide-controller-method-and-its-data', true );


				array_push( $all_posts_data, $the_post_data );
			}
			wp_reset_postdata();
		}
		return $all_posts_data;
	}


	public function load_controller_menu_assets() {

		// Babel polyfill
		wp_enqueue_script(
			'the-guide-script-babel-polyfill',
			THE_GUIDE_URL . 'assets/general/lib/babel-polyfill/babel-polyfill.js',
			[],
			THE_GUIDE_VERSION
		);



		// loads CSS
		wp_enqueue_style(
			'the-guide-style-admin-controller-menu',
			THE_GUIDE_URL . 'assets/admin/css/dashboard-controller-menu.css',
			[],
			THE_GUIDE_VERSION
		);

		// loads JS
		wp_enqueue_script(
			'the-guide-script-admin-controller-menu',
			THE_GUIDE_URL . 'assets/admin/js/dashboard-controller-menu.js',
			[ 'jquery-ui-sortable' ],
			THE_GUIDE_VERSION
		);



		// data to JS
		wp_localize_script( 'the-guide-script-admin-controller-menu',  'theGuide', [
			'positions' => $this->settings->get_plugin_setting( 'positions' ),

			'token'     => wp_create_nonce( '5MBn1s3cLrcK' ),
		] );
	}


	public function load_settings_menu_assets() {

		// Babel polyfill
		wp_enqueue_script(
			'the-guide-script-babel-polyfill',
			THE_GUIDE_URL . 'assets/general/lib/babel-polyfill/babel-polyfill.js',
			[],
			THE_GUIDE_VERSION
		);



		// loads CSS
		wp_enqueue_style(
			'the-guide-style-admin-settings-menu',
			THE_GUIDE_URL . 'assets/admin/css/dashboard-settings-menu.css',
			[],
			THE_GUIDE_VERSION
		);

		// loads JS
		wp_enqueue_script(
			'the-guide-script-admin-settings-menu',
			THE_GUIDE_URL . 'assets/admin/js/dashboard-settings-menu.js',
			[ 'jquery' ],
			THE_GUIDE_VERSION
		);



		// data to JS
		wp_localize_script( 'the-guide-script-admin-settings-menu',  'theGuide', [
			'postsData' => $this->get_admin_js_data(),
			'positions' => $this->settings->get_plugin_setting( 'positions' ),

			'token'     => wp_create_nonce( 'kv155ztWAlFQ' ),
		] );
	}


	public function load_customize_menu_assets() {

		// Babel polyfill
		wp_enqueue_script(
			'the-guide-script-babel-polyfill',
			THE_GUIDE_URL . 'assets/general/lib/babel-polyfill/babel-polyfill.js',
			[],
			THE_GUIDE_VERSION
		);



		// Codemirror

		wp_enqueue_script(
			'the-guide-script-admin-customize-codemirror-main',
			THE_GUIDE_URL . 'assets/admin/lib/codemirror/codemirror.js',
			[],
			THE_GUIDE_VERSION
		);

		wp_enqueue_script(
			'the-guide-script-admin-customize-codemirror-css',
			THE_GUIDE_URL . 'assets/admin/lib/codemirror/css.js',
			[],
			THE_GUIDE_VERSION
		);

		wp_enqueue_style(
			'the-guide-style-admin-customize-menu-codemirror',
			THE_GUIDE_URL . 'assets/admin/lib/codemirror/codemirror.css',
			[],
			THE_GUIDE_VERSION
		);



		// loads CSS
		wp_enqueue_style(
			'the-guide-style-admin-customize-menu',
			THE_GUIDE_URL . 'assets/admin/css/dashboard-customize-menu.css',
			[],
			THE_GUIDE_VERSION
		);

		// loads JS
		wp_enqueue_script(
			'the-guide-script-admin-customize-menu',
			THE_GUIDE_URL . 'assets/admin/js/dashboard-customize-menu.js',
			[ 'jquery' ],
			THE_GUIDE_VERSION
		);



		// data to JS
		wp_localize_script( 'the-guide-script-admin-customize-menu',  'theGuide', [
			'token' => wp_create_nonce( 'NzbrOyxcQOb6' ),
		] );
	}



	public function __construct( The_Guide_Settings $settings_inst ) {
		$this->settings = $settings_inst;
	}
}
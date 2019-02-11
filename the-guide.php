<?php
/*
Plugin Name: The Guide
Description: Explains to your site visitors how to use it.
Version:     0.1.3
Plugin URI:  https://github.com/vladlu/the-guide
Author:      Vladislav Luzan
Author URI:  https://vlad.lu/
Text Domain: the-guide
License:     MIT
*/


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


final class The_Guide {


	/**
	 * Settings object.
	 *
	 * @since 0.1.0
	 * @var object The_Guide_Settings
	 */
	private $settings;


	public function __construct() {
		$this->define_constants();
		$this->import_files();

		add_action( 'init', function () {
			$this->settings = new The_Guide_Settings;


			$this->load_plugin_textdomain();
			$this->register_post_type();

			if ( wp_doing_ajax() ) {
				new The_Guide_Ajax( $this->settings );
			}

			if ( is_admin() ) {
				new The_Guide_Admin_Assets( $this->settings );
				new The_Guide_Menus       ( $this->settings );
				new The_Guide_Columns     ( $this->settings );
				new The_Guide_Quick_Edit  ( $this->settings );
			} else {
				new The_Guide_Public_Assets( $this->settings );
			}

			new The_Guide_Shortcodes();
		} );
	}


	private function define_constants() {
		/**
		 * Don't touch this constant. Use the script in /dev instead.
		 *
		 * When DEV_MODE is TRUE:
		 *
		 * - The_Guide_Public_Assets->is_current_user_watched_this_tour() always returns FALSE so you can watch any tour.
		 * - There's no caching in browsers because the version of the plugin used for assets' URLs is equal to the time.
		 */
		define( 'DEV_MODE', true );
		define( 'THE_GUIDE_VERSION', DEV_MODE ? time() : '0.1.3' );

		define( 'THE_GUIDE_URL', plugin_dir_url( __FILE__ ) );
		define( 'THE_GUIDE_DIR', plugin_dir_path( __FILE__ ) );
	}


	private function import_files() {
		require_once( THE_GUIDE_DIR . 'src/class-ajax.php' );
		require_once( THE_GUIDE_DIR . 'src/class-columns.php' );
		require_once( THE_GUIDE_DIR . 'src/class-menus.php' );
		require_once( THE_GUIDE_DIR . 'src/class-quick-edit.php' );
		require_once( THE_GUIDE_DIR . 'src/class-settings.php' );
		require_once( THE_GUIDE_DIR . 'src/class-shortcodes.php' );
		require_once( THE_GUIDE_DIR . 'src/assets/class-admin-assets.php' );
		require_once( THE_GUIDE_DIR . 'src/assets/class-public-assets.php' );
	}


	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'the-guide' );
	}


	private function register_post_type() {
		register_post_type( 'the-guide', [
			'labels' => [
				'name'          => __( 'Tours',        'the-guide' ),
				'singular_name' => __( 'Tour',         'the-guide' ),

				'add_new_item'  => __( 'Add New Tour', 'the-guide' ),
				'edit_item'     => __( 'Edit Tour',    'the-guide' ),
				'view_item'     => __( 'View Tour',    'the-guide' ),
				'search_items'  => __( 'Search Tours', 'the-guide' ),

				'item_updated'             => __( 'Tour updated',             'the-guide' ),
				'item_published'           => __( 'Tour published',           'the-guide' ),
				'item_published_privately' => __( 'Tour published privately', 'the-guide' ),
				'item_reverted_to_draft'   => __( 'Tour reverted to draft',   'the-guide' ),
				'item_scheduled'           => __( 'Tour scheduled',           'the-guide' ),
			],
			'show_ui' => true,
			'show_in_menu' => 'the-guide-menu'
		] );
	}
}

new The_Guide;

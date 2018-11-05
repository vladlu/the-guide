<?php
/*
Plugin Name: The Guide
Description: A page tour plugin for WordPress
Version: 0.1
Author: Vladislav Luzan
Text Domain: the-guide
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


final class The_Guide {

	private function define_constants() {
		/**
		 * Don't touch this constant. Use the script in /dev instead.
		 *
		 * When DEV_MODE is TRUE:
		 * 
		 * The_Guide_Public_Assets->is_current_user_watched_this_tour() always returns FALSE so you can watch any tour.
		 */
		define( 'DEV_MODE', true );
		// Used for assets version (to avoid browser cache)
		define( 'THE_GUIDE_VERSION', DEV_MODE ? time() : 0.1 );

		define( 'THE_GUIDE_URL', plugin_dir_url( __FILE__ ) );
		define( 'THE_GUIDE_DIR', plugin_dir_path( __FILE__ ) );
		define( 'THE_GUIDE_FILE', __FILE__ );
 	}


	private function import_files() {
		require_once( THE_GUIDE_DIR . 'includes/class-ajax.php' );
		require_once( THE_GUIDE_DIR . 'includes/class-menus.php' );
		require_once( THE_GUIDE_DIR . 'includes/class-settings.php' );
		require_once( THE_GUIDE_DIR . 'includes/class-shortcodes.php' );
		require_once( THE_GUIDE_DIR . 'includes/assets/class-admin-assets.php' );
		require_once( THE_GUIDE_DIR . 'includes/assets/class-public-assets.php' );
	}


	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'the-guide' );
	}


	private function register_post_type() {
		register_post_type( 'the-guide', [
			'public' => false
		] );
	}


	public function __construct() {
		$this->define_constants();
		$this->import_files();
		add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain' ] );
		add_action( 'init', function() {
			$settings_inst = new The_Guide_Settings;

			$this->register_post_type();

			if ( wp_doing_ajax() ) {
				new The_Guide_Ajax( $settings_inst );
			}

			if ( is_admin() ) {
				new The_Guide_Menus( $settings_inst );
			} else {
				new The_Guide_Public_Assets( $settings_inst );
			}

			new The_Guide_Shortcodes();
		} );
	}
}

new The_Guide;

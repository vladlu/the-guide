<?php
/**
 * Plugin Name: The Guide
 * Description: Explains to your site visitors how to use it.
 * Version:     0.1.3
 * Plugin URI:  https://github.com/vladlu/the-guide
 * Author:      Vladislav Luzan
 * Author URI:  https://vlad.lu/
 * Text Domain: the-guide
 * License:     MIT
 *
 * @package The Guide
 * @author  Vladislav Luzan <hey@vlad.lu>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Main The Guide class.
 *
 * @since 0.1.0
 */
final class The_Guide {


	/**
	 * Settings object.
	 *
	 * @since 0.1.0
	 * @var The_Guide_Settings $settings
	 */
	private $settings;


	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		$this->define_constants();
		$this->import_files();
		add_action(
			'init',
			function () {
				$this->settings = new The_Guide_Settings();

				new The_Guide_Misc( $this->settings );
				new The_Guide_Shortcodes();
				if ( wp_doing_ajax() ) {
					new The_Guide_Ajax( $this->settings );
				}
				if ( is_admin() ) {
					new The_Guide_Admin_Assets();
					new The_Guide_Menus       ( $this->settings );
					new The_Guide_Meta_Boxes  ( $this->settings );
					new The_Guide_Columns     ( $this->settings );
					new The_Guide_Quick_Edit  ( $this->settings );
					new The_Guide_Prioritize();
				} else {
					new The_Guide_Public_Assets( $this->settings );
				}
			}
		);
	}


	/**
	 * Defines constants.
	 *
	 * @since 0.1.0
	 */
	private function define_constants() {

		/**
		 * The URL to the plugin.
		 *
		 * @since 0.1.0
		 * @var string THE_GUIDE_URL
		 */
		define( 'THE_GUIDE_URL', plugin_dir_url( __FILE__ ) );

		/**
		 * The filesystem directory path to the plugin.
		 *
		 * @since 0.1.0
		 * @var string THE_GUIDE_DIR
		 */
		define( 'THE_GUIDE_DIR', plugin_dir_path( __FILE__ ) );

		/**
		 * The version of the plugin.
		 *
		 * @since 0.1.0
		 * @var string THE_GUIDE_URL
		 */
		define( 'THE_GUIDE_VERSION', get_file_data( __FILE__, [ 'Version' ] )[0] );
	}


	/**
	 * Imports files.
	 *
	 * @since 0.1.0
	 */
	private function import_files() {
		require_once THE_GUIDE_DIR . 'src/functions.php';
		require_once THE_GUIDE_DIR . 'src/class-ajax.php';
		require_once THE_GUIDE_DIR . 'src/class-columns.php';
		require_once THE_GUIDE_DIR . 'src/class-menus.php';
		require_once THE_GUIDE_DIR . 'src/class-meta-boxes.php';
		require_once THE_GUIDE_DIR . 'src/class-misc.php';
		require_once THE_GUIDE_DIR . 'src/class-prioritize.php';
		require_once THE_GUIDE_DIR . 'src/class-quick-edit.php';
		require_once THE_GUIDE_DIR . 'src/class-settings.php';
		require_once THE_GUIDE_DIR . 'src/class-shortcodes.php';
		require_once THE_GUIDE_DIR . 'src/assets/class-admin-assets.php';
		require_once THE_GUIDE_DIR . 'src/assets/class-public-assets.php';
	}
}


new The_Guide();

<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class The_Guide_Menus {

	/**
	 * Settings object.
	 *
	 * @since 0.1.0
	 * @var object The_Guide_Settings
	 */
    private $settings;



	public function __construct( The_Guide_Settings $settings_inst ) {
		$this->settings = $settings_inst;

		// Inits dashboard menus
		add_action( 'admin_menu', [ $this, 'init_dashboard_menus' ] );
	}



	public function init_dashboard_menus() {
		add_menu_page(
			'The Guide',
			'The Guide',
			'manage_options',
			'the-guide-menu',
			null,
			'dashicons-analytics'
		);


		$page = add_submenu_page(
			'the-guide-menu',
			__( 'Controller', 'the-guide' ),
			__( 'Controller', 'the-guide' ),
			'manage_options',
			'the-guide-controller',
			function() {
				require_once( THE_GUIDE_DIR . 'templates/admin/dashboard-controller-menu.php' );
			}
		);

		add_action( 'load-' . $page, [
			new The_Guide_Admin_Assets( $this->settings ), 'load_controller_menu_assets'
		] );


		$page = add_submenu_page(
			'the-guide-menu',
			__( 'Settings' ),
			__( 'Settings' ),
			'manage_options',
			'the-guide-settings',
			function () {
				require_once( THE_GUIDE_DIR . 'templates/admin/dashboard-settings-menu.php' );
			}
		);

		add_action( 'load-' . $page, [
			new The_Guide_Admin_Assets( $this->settings ), 'load_settings_menu_assets'
		] );


		$page = add_submenu_page(
			'the-guide-menu',
			__( 'Custom CSS', 'the-guide' ),
			__( 'Custom CSS', 'the-guide' ),
			'manage_options',
			'the-guide-customize',
			function() {
				require_once( THE_GUIDE_DIR . 'templates/admin/dashboard-customize-menu.php' );
			}
		);

		add_action( 'load-' . $page, [
			new The_Guide_Admin_Assets( $this->settings ), 'load_customize_menu_assets'
		] );


		remove_submenu_page( 'the-guide-menu', 'the-guide-menu' );
	}
}
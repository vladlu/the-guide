<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class The_Guide_Menus {


	public function __construct() {
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


		// Add New (the-guide)

		add_submenu_page(
			'the-guide-menu',
			__( 'Add New' ),
			__( 'Add New' ),
			'manage_options',
			'post-new.php?post_type=the-guide'
		);

		// Customize

		add_submenu_page(
			'the-guide-menu',
			__( 'Custom CSS', 'the-guide' ),
			__( 'Custom CSS', 'the-guide' ),
			'manage_options',
			'the-guide-customize',
			function () {
				require_once THE_GUIDE_DIR . 'src/templates/dashboard-menu-customize.php';
			}
		);
	}
}

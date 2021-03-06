<?php
/**
 * Menus
 *
 * Adds new menus.
 *
 * @package The Guide
 * @since 0.1.3
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Menus.
 *
 * @since 0.1.3
 */
class The_Guide_Menus {

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
	 *
	 * @param The_Guide_Settings $settings_inst Settings Object.
	 */
	public function __construct( The_Guide_Settings $settings_inst ) {
		$this->settings = $settings_inst;

		// Inits dashboard menus.
		add_action( 'admin_menu', [ $this, 'init_dashboard_menus' ] );
	}


	/**
	 * Adds menus.
	 *
	 * @since 0.1.0
	 */
	public function init_dashboard_menus() {
		add_menu_page(
			'The Guide',
			'The Guide',
			'manage_options',
			'the-guide-menu',
			null,
			'dashicons-analytics'
		);

		// Add New (the-guide).

		add_submenu_page(
			'the-guide-menu',
			__( 'Add New' ),
			__( 'Add New' ),
			'manage_options',
			'post-new.php?post_type=the-guide'
		);

		// Customize.

		add_submenu_page(
			'the-guide-menu',
			__( 'Custom CSS', 'the-guide' ),
			__( 'Custom CSS', 'the-guide' ),
			'manage_options',
			'the-guide-customize',
			function () {
				require_once THE_GUIDE_DIR . 'src/templates/menu-customize.php';
			}
		);
	}
}

<?php
/**
 * A helper class that handles plugin's settings
 *
 * @package The Guide
 * @since 0.1.3
 */


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class The_Guide_Settings {


	public function get_plugin_setting( $setting_name ) {
		$all_settings = get_option( 'the-guide-settings' );

		return isset( $all_settings[ $setting_name ] ) ? $all_settings[ $setting_name ] : '';
	}


	public function save_plugin_setting( $setting_name, $data_to_save ) {
		$all_settings                  = get_option( 'the-guide-settings' );
		$all_settings[ $setting_name ] = $data_to_save;
		update_option( 'the-guide-settings', $all_settings );
	}
}

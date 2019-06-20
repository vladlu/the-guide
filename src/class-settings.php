<?php
/**
 * A helper class that handles plugin's settings
 *
 * @package The Guide
 * @since 0.1.3
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * A helper class that handles plugin's settings.
 *
 * @since 0.1.3
 */
class The_Guide_Settings {


	/**
	 * Returns the plugin's setting decomposing it from the array of plugin settings (wp option).
	 *
	 * @since 0.1.0
	 *
	 * @param  string $setting_name The name of the setting to get.
	 * @return mixed                The data of the setting.
	 */
	public function get_plugin_setting( $setting_name ) {
		$all_settings = get_option( 'the-guide-settings' );

		return isset( $all_settings[ $setting_name ] ) ? $all_settings[ $setting_name ] : '';
	}


	/**
	 * Adds the plugin's setting composing it to the array of plugin settings (wp option).
	 *
	 * @since 0.1.0
	 *
	 * @param string $setting_name The name of the setting.
	 * @param mixed  $data_to_save The data to save to the setting.
	 */
	public function save_plugin_setting( $setting_name, $data_to_save ) {
		$all_settings                  = get_option( 'the-guide-settings' );
		$all_settings[ $setting_name ] = $data_to_save;
		update_option( 'the-guide-settings', $all_settings );
	}
}

<?php


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
		$all_settings = get_option( 'the-guide-settings' );
		$all_settings[ $setting_name ] = $data_to_save;
		update_option( 'the-guide-settings', $all_settings );
	}


	public function save_post_meta( $post_id, $meta_key, $meta_value ) {
		if ( get_post_meta( $post_id, $meta_key ) ) {
			update_post_meta( $post_id, $meta_key, $meta_value );
		} else {
			add_post_meta( $post_id, $meta_key, $meta_value, true );
		}
	}
}

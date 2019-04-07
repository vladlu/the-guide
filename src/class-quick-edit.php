<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class The_Guide_Quick_Edit {

	/**
	 * Settings object.
	 *
	 * @since 0.1.0
	 * @var object The_Guide_Settings
	 */
	private $settings;



	public function __construct( The_Guide_Settings $settings_inst ) {
		$this->settings = $settings_inst;

		add_action( 'quick_edit_custom_box', [ $this, 'add' ], 10, 2 );
		add_action( 'save_post',             [ $this, 'save' ] );
	}


	public function add( $column_name, $post_type ) {
		static $first_run = true;
		if ( $first_run ) {
			wp_nonce_field( 'the-guide-quick-edit', 'the-guide_edit_nonce' );

			$first_run = false;
		}


	    if ( 'the-guide' === $post_type ) {
            switch ( $column_name ) {
	            case 'url':
		            ?>
                    <fieldset class="inline-edit-col-right inline-edit-the-guide">
                        <div class="inline-edit-col">
                            <label class="inline-edit-url">
                                <span class="title">
                                    <?php esc_attr_e( 'URL', 'the-guide' ) ?>
                                </span>
                                <span class="input-text-wrap">
                                    <input name="the-guide-url" class="the-guide-url" type="text">
                                </span>
                            </label>
                    <?php

                    break;
                case 'steps':
	                ?>
                        <label class="inline-edit-steps">
                            <span class="title">
                                <?php esc_attr_e( 'Steps', 'the-guide' ) ?>
                            </span>
                            <span class="input-text-wrap">
                                <input name="the-guide-steps" class="the-guide-steps" type="text">
                            </span>
                        </label>
                    <?php

	                break;
	            case 'enabled':
		            ?>
                            <label class="inline-edit-enabled">
                                <span class="title">
                                    <?php esc_attr_e( 'Enabled', 'the-guide' ) ?>
                                </span>
                                <span class="input-text-wrap">
                                    <input name="the-guide-enabled" class="the-guide-enabled" type="checkbox">
                                </span>
                            </label>
                        </div>
                    </fieldset>
		            <?php

		            break;
            }
        }
    }


	public function save( $post_id ) {
		$slug = 'the-guide';

		if ( ! isset( $_POST['post_type'] ) ) {
		    return;
        }
		if ( $slug !== $_POST['post_type'] ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$_POST += array("{$slug}_edit_nonce" => '');
		if ( ! wp_verify_nonce( $_POST["{$slug}_edit_nonce"], 'the-guide-quick-edit' ) ) {
			return;
		}


        /*
         * URL
         */

		if ( isset( $_POST['the-guide-url'] ) ) {
			$this->settings->save_post_meta( $post_id, 'the-guide-url', $_POST['the-guide-url'] );
		}


		/*
		 * Steps
		 */

		if ( isset( $_POST['the-guide-steps'] ) ) {
		    $steps = explode( ',', $_POST['the-guide-steps'] );
			$this->settings->save_post_meta( $post_id, 'the-guide-steps', $steps );
		}


		/*
		 * Enabled (checkbox)
		 */

		if ( isset( $_POST['the-guide-enabled'] ) ) {
            /*
             * Adds a tour to enabled
             */

            $all_enabled_tours = $this->settings->get_plugin_setting( 'enabled-tours' );

			if ( $all_enabled_tours ) {
				if ( ! in_array( $post_id, $all_enabled_tours ) ) {
					$this->settings->save_plugin_setting( 'enabled-tours', array_push( $all_enabled_tours, $post_id ) );
				}
			} else {
				$this->settings->save_plugin_setting( 'enabled-tours', [ $post_id ] );
			}

		} else {
			/*
             * Removes a tour from enabled
             */

			$all_enabled_tours = $this->settings->get_plugin_setting( 'enabled-tours' );

			if ( $all_enabled_tours && in_array( $post_id, $all_enabled_tours ) ) {
			    $post_index = array_search( $post_id, $all_enabled_tours );
				unset( $all_enabled_tours[ $post_index ] );

				$this->settings->save_plugin_setting( 'enabled-tours', $all_enabled_tours );
			}
        }
	}
}

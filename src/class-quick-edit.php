<?php
/**
 * Quick Edit
 *
 * - Adds new quick edit fields;
 * - Handles the saving of quick edit fields.
 *
 * @package The Guide
 * @since 0.1.3
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Quick Edit.
 *
 * @since 0.1.3
 */
class The_Guide_Quick_Edit {


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

		add_action( 'quick_edit_custom_box', [ $this, 'add' ], 10, 2 );
		add_action( 'save_post',             [ $this, 'save' ] );
	}



	/**
	 * Adds quick edit fields (displays their content) based on columns.
	 *
	 * @since 0.1.0
	 *
     * @param The_Guide_Settings $column_name The name of the column (for which quick edit field displayed).
     * @param string             $post_type   Post type.
	 */
	public function add( $column_name, $post_type ) {

	    /* Adds nonce */

		static $first_run = true;
		if ( $first_run ) {
			wp_nonce_field( 'the-guide-quick-edit', 'the-guide_edit_nonce-token' );

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
                                    <input name="the-guide-is-enabled" class="the-guide-is-enabled" type="checkbox">
                                </span>
                            </label>
                        </div>

                    </fieldset>
		            <?php

		            break;
            }
        }
    }



	/**
	 * Handles the saving of quick edit fields.
	 *
	 * @since 0.1.0
	 *
     * @param int $post_id The ID of the post.
	 */
	public function save( $post_id ) {

		/*
		 * Verifications.
		 */

	    $slug = 'the-guide';

		if (
            // post type
            ! isset(  $_POST['post_type'] ) ||
            $slug !== $_POST['post_type']   ||

            // nonce
            ! isset(           $_POST["{$slug}_edit_nonce-token"] )                         ||
            ! wp_verify_nonce( $_POST["{$slug}_edit_nonce-token"], 'the-guide-quick-edit' ) ||

            // user has capabilities
            ! current_user_can( 'edit_post', $post_id )
        ) {
			return;
		}



        /*
         * URL
         */

		if ( isset( $_POST['the-guide-url'] ) ) {
		    $url_with_no_proto = preg_replace("(^https?://)", "", $_POST['the-guide-url'] );
            update_post_meta( $post_id, 'the-guide-url', $url_with_no_proto );
		}


		/*
		 * Steps
		 */

		if ( isset( $_POST['the-guide-steps'] ) ) {
		    $steps = explode( ',', $_POST['the-guide-steps'] );
			update_post_meta( $post_id, 'the-guide-steps', $steps );
		}


		/*
		 * Enabled (checkbox)
		 */

		if ( isset( $_POST['the-guide-is-enabled'] ) ) {
            update_post_meta( $post_id, 'the-guide-is-enabled', 1 );
		} else {
			update_post_meta( $post_id, 'the-guide-is-enabled', 0 );
        }
	}
}

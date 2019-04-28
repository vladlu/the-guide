<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class The_Guide_Meta_Boxes{


	/**
	 * Settings object.
	 *
	 * @since 0.1.0
	 * @var object The_Guide_Settings
	 */
	private $settings;



	public function __construct( The_Guide_Settings $settings_inst ) {
		$this->settings = $settings_inst;

		add_action( 'add_meta_boxes', [ $this, 'add' ] );
		add_action( 'save_post',      [ $this, 'save' ] );
	}



	public function add() {
		add_meta_box( 'the-guide-tour-data', __( 'Tour', 'the-guide' ), [ $this, 'content' ], 'the-guide' );
    }



    public function content( $post ) {
        require_once THE_GUIDE_DIR . 'src/templates/meta-boxes.php';
    }



	public function save( $post_id ) {

		/* Verifications */

		if (
			// nonce
			! isset( $_POST['the-guide_edit_tour'] ) ||
			! wp_verify_nonce( $_POST['the-guide_edit_tour'], 'the-guide-edit-tour' ) ||

			// autosave
			defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ||

			// capabilities
		    ! current_user_can( 'edit_post', $post_id )
		) {
			return;
		}


		/*
         * Enabled (checkbox)
         */

		if ( isset( $_POST['the-guide-is-enabled'] ) ) {
			$this->settings->save_post_meta( $post_id, 'the-guide-is-enabled', true );
		} else {
			$this->settings->save_post_meta( $post_id, 'the-guide-is-enabled', false );
		}


		/*
         * Activation Method
         */

		$this->settings->save_post_meta( $post_id, 'the-guide-activation-method-and-its-data', [
			'method'       => $_POST['the-guide-select-activation-method'],
            'floatingText' => $_POST['the-guide-activation-floating-text'],
            'position'     => [
                'top'    => $_POST['the-guide-activation-position-top'],
                'bottom' => $_POST['the-guide-activation-position-bottom'],
                'left'   => $_POST['the-guide-activation-position-left'],
                'right'  => $_POST['the-guide-activation-position-right']
            ],
			// Translates a comma-separated string into an array
			'selectors'  => array_map( 'trim', explode( ',',  $_POST['the-guide-activation-selectors'] ) )
        ] );


		/*
         * Tour controller position
         */

		$this->settings->save_post_meta( $post_id, 'the-guide-controller-method-and-its-data', [
			'method'       => $_POST['the-guide-select-controller-method'],
			'position'     => [
				'top'    => $_POST['the-guide-controller-position-top'],
				'bottom' => $_POST['the-guide-controller-position-bottom'],
				'left'   => $_POST['the-guide-controller-position-left'],
				'right'  => $_POST['the-guide-controller-position-right']
			],
		] );


		/*
         * Tour URL
         */

		$url_with_no_proto = preg_replace("(^https?://)", "", $_POST['the-guide-url'] );
		$this->settings->save_post_meta( $post_id, 'the-guide-url', $url_with_no_proto );


		/*
         * Selected elements (steps)
         */

		// Translates a comma-separated string into an array
		$steps = array_map( 'trim', explode( ',',  $_POST['the-guide-steps'] ) );
		$this->settings->save_post_meta( $post_id, 'the-guide-steps', $steps );


		/*
		 * Steps content
		 */

		$steps_content = [];
		for ( $i = 0; $i < count( $steps ); ++$i ) {
			$steps_content[] = $_POST["the-guide-step-content-$i"];
		}
		$this->settings->save_post_meta( $post_id, 'the-guide-steps-content', $steps_content );
	}
}

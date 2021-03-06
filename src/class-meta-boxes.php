<?php
/**
 * Metaboxes
 *
 * - Adds new metaboxes;
 * - Handles their saving.
 *
 * @package The Guide
 * @since 0.1.3
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Metaboxes.
 *
 * @since 0.1.3
 */
class The_Guide_Meta_Boxes {

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

		add_action( 'add_meta_boxes', [ $this, 'add' ] );
		add_action( 'save_post',      [ $this, 'save' ] );
	}


	/**
	 * Adds metaboxes.
	 *
	 * @since 0.1.0
	 */
	public function add() {
		add_meta_box( 'the-guide-tour-data', __( 'Tour', 'the-guide' ), [ $this, 'content' ], 'the-guide' );
	}


	/**
	 * Loads metabox content.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Post $post Post object (used in the imported file).
	 */
	public function content( $post ) {
		require_once THE_GUIDE_DIR . 'src/templates/meta-boxes.php';
	}


	/**
	 * Saves data from the metaboxes.
	 *
	 * @since 0.1.0
	 *
	 * @param int $post_id The ID of the post.
	 */
	public function save( $post_id ) {
		/*
		 * Verifications.
		 */

		if (
			// Nonce.
			! isset( $_POST['the-guide_edit_tour_nonce-token'] ) ||
			! wp_verify_nonce( $_POST['the-guide_edit_tour_nonce-token'], 'the-guide-edit-tour' ) ||

			// Autosave.
			defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||

			// Capabilities.
			! current_user_can( 'edit_post', $post_id )
		) {
			return;
		}

		/*
		 * Enabled (checkbox).
		 */

		if ( isset( $_POST['the-guide-is-enabled'] ) ) {
			update_post_meta( $post_id, 'the-guide-is-enabled', 1 );
		} else {
			update_post_meta( $post_id, 'the-guide-is-enabled', 0 );
		}

		/*
		 * Activation Method.
		 */

		update_post_meta(
			$post_id,
			'the-guide-activation-method-and-its-data',
			[
				'method'       => $_POST['the-guide-select-activation-method'],
				'floatingText' => $_POST['the-guide-activation-floating-text'],
				'position'     => [
					'top'    => $_POST['the-guide-activation-position-top'],
					'bottom' => $_POST['the-guide-activation-position-bottom'],
					'left'   => $_POST['the-guide-activation-position-left'],
					'right'  => $_POST['the-guide-activation-position-right'],
				],
				// Translates a comma-separated string into an array.
				'selectors'    => array_map( 'trim', explode( ',', $_POST['the-guide-activation-selectors'] ) ),
			]
		);

		/*
		 * Tour controller position.
		 */

		update_post_meta(
			$post_id,
			'the-guide-controller-method-and-its-data',
			[
				'method'   => $_POST['the-guide-select-controller-method'],
				'position' => [
					'top'    => $_POST['the-guide-controller-position-top'],
					'bottom' => $_POST['the-guide-controller-position-bottom'],
					'left'   => $_POST['the-guide-controller-position-left'],
					'right'  => $_POST['the-guide-controller-position-right'],
				],
			]
		);

		/*
		 * Tour URL.
		 */

		$url_with_no_proto = preg_replace( '(^https?://)', '', $_POST['the-guide-url'] );
		update_post_meta( $post_id, 'the-guide-url', $url_with_no_proto );

		/*
		 * Selected elements (steps).
		 */

		// Translates a comma-separated string into an array.
		$steps = array_map( 'trim', explode( ',', $_POST['the-guide-steps'] ) );
		update_post_meta( $post_id, 'the-guide-steps', $steps );

		/*
		 * Steps content.
		 */

		$steps_content  = [];
		$how_many_steps = count( $steps );
		for ( $i = 0; $i < $how_many_steps; ++$i ) {
			$steps_content[] = $_POST[ "the-guide-step-content-$i" ];
		}
		update_post_meta( $post_id, 'the-guide-steps-content', $steps_content );
	}
}

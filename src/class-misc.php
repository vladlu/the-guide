<?php
/**
 * Miscellaneous functionality
 *
 * - Loads textdomain;
 * - Registers post type "the-guide";
 * - Adds bulk actions;
 * - Changes admin notices for this post type.
 *
 * @package The Guide
 * @since 0.1.3
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Miscellaneous functionality.
 *
 * @since 0.1.3
 */
class The_Guide_Misc {


	/**
	 * Settings object.
	 *
	 * @since 0.1.0
	 * @var object The_Guide_Settings
	 */
	private $settings;



	public function __construct( The_Guide_Settings $settings_inst ) {
		$this->settings = $settings_inst;

		$this->load_plugin_textdomain();
		$this->register_post_type();
		$this->custom_bulk_actions();

		add_filter( 'post_updated_messages', [ $this, 'custom_post_admin_notices' ] );
	}



	private function load_plugin_textdomain() {
		load_plugin_textdomain( 'the-guide' );
	}



	private function register_post_type() {
		register_post_type( 'the-guide', [
			'labels' => [
				'name'          => __( 'Tours',        'the-guide' ),
				'singular_name' => __( 'Tour',         'the-guide' ),

				'add_new_item'  => __( 'Add New Tour', 'the-guide' ),
				'edit_item'     => __( 'Edit Tour',    'the-guide' ),
				'view_item'     => __( 'View Tour',    'the-guide' ),
				'search_items'  => __( 'Search Tours', 'the-guide' ),

				'item_published'           => __( 'Tour published.',           'the-guide' ),
				'item_published_privately' => __( 'Tour published privately.', 'the-guide' ),
				'item_reverted_to_draft'   => __( 'Tour reverted to draft.',   'the-guide' ),
				'item_scheduled'           => __( 'Tour scheduled.',           'the-guide' ),
				'item_updated'             => __( 'Tour updated.',             'the-guide' ),
			],
			'show_ui' => true,
			'show_in_menu' => 'the-guide-menu',
			'supports'=> [
				'title',
				'revisions'
			]
		] );
	}



	private function custom_bulk_actions() {

		/*
		 * Enable
		 */

		add_filter( 'bulk_actions-edit-the-guide', function( $bulk_actions ) {

			/*
             * Adds
             */

			$bulk_actions['enable'] = __( 'Enable', 'the-guide' );


			return $bulk_actions;
		} );


		add_filter( 'handle_bulk_actions-edit-the-guide', function( $redirect_to, $doaction, $post_ids ) {
			if ( $doaction !== 'enable' ) {
				return $redirect_to;
			}
			foreach ( $post_ids as $post_id ) {
				/*
                 * Adds a tour to enabled
                 */
				update_post_meta( $post_id, 'the-guide-is-enabled', 1 );
			}
			$redirect_to = add_query_arg( 'enabled', count( $post_ids ), $redirect_to );
			return $redirect_to;
		}, 10, 3 );


		add_action( 'admin_notices', function() {
			if ( ! empty( $_REQUEST['enabled'] ) ) {
				$enabled_count = $_REQUEST['enabled'];
				printf( '<div id="message" class="notice notice-success is-dismissible"><p>' .
				        _n( '%s tour enabled.',
					        '%s tours enabled.',
					        $enabled_count,
					        'the-guide'
				        ) . '</p></div>', $enabled_count );
			}
		} );

		/*
		 * Disable
		 */

		add_filter( 'bulk_actions-edit-the-guide', function( $bulk_actions ) {

			/*
			 * Adds
			 */

			$bulk_actions['disable'] = __( 'Disable', 'the-guide' );


			return $bulk_actions;
		} );


		add_filter( 'handle_bulk_actions-edit-the-guide', function( $redirect_to, $doaction, $post_ids ) {
			if ( $doaction !== 'disable' ) {
				return $redirect_to;
			}
			foreach ( $post_ids as $post_id ) {
				/*
				 * Removes a tour from enabled
				 */
				update_post_meta( $post_id, 'the-guide-is-enabled', 0 );
			}
			$redirect_to = add_query_arg( 'disabled', count( $post_ids ), $redirect_to );
			return $redirect_to;
		}, 10, 3 );


		add_action( 'admin_notices', function() {
			if ( ! empty( $_REQUEST['disabled'] ) ) {
				$disabled_count = $_REQUEST['disabled'];
				printf( '<div id="message" class="notice notice-success is-dismissible"><p>' .
				        _n( '%s tour disabled.',
					        '%s tours disabled.',
					        $disabled_count,
					        'the-guide'
				        ) . '</p></div>', $disabled_count );
			}
		} );

		/*
         * Duplicate
         */

		add_filter( 'bulk_actions-edit-the-guide', function( $bulk_actions ) {

			/*
			 * Adds
			 */

			$bulk_actions['duplicate'] = __( 'Duplicate', 'the-guide' );


			return $bulk_actions;
		} );


		add_filter( 'handle_bulk_actions-edit-the-guide', function( $redirect_to, $doaction, $post_ids ) {
			if ( $doaction !== 'duplicate' ) {
				return $redirect_to;
			}
			foreach ( $post_ids as $post_id ) {
				/*
				 * Removes a tour from enabled
				 */
				the_guide_duplicate_post( $post_id );
			}
			$redirect_to = add_query_arg( 'duplicated', count( $post_ids ), $redirect_to );
			return $redirect_to;
		}, 10, 3 );


		add_action( 'admin_notices', function() {
			if ( ! empty( $_REQUEST['duplicated'] ) ) {
				$duplicated_count = $_REQUEST['duplicated'];
				printf( '<div id="message" class="notice notice-success is-dismissible"><p>' .
				        _n( '%s tour duplicated.',
					        '%s tours duplicated.',
					        $duplicated_count,
					        'the-guide'
				        ) . '</p></div>', $duplicated_count );
			}
		} );
	}



	public function custom_post_admin_notices( $messages ) {

		$post = get_post();

		$messages['the-guide'] = [
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Tour updated.', 'the-guide' ), // It may contain link "View tour"
			2  => __( 'Custom field updated.' ),
			3  => __( 'Custom field deleted.'),
			4  => __( 'Tour updated.', 'the-guide' ), // It doesn't contain link. Just message, as it is
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Tour restored to revision from %s' ),
				wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Tour published.', 'the-guide' ),
			7  => __( 'Tour saved.',     'the-guide' ),
			8  => __( 'Tour submitted.', 'the-guide' ),
			9  => sprintf(
				__( 'Tour scheduled for: <strong>%1$s</strong>.' ),
				date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Tour draft updated.', 'the-guide' )
		];

		return $messages;
	}
}
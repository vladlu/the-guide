<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


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
	}



	public function load_plugin_textdomain() {
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
				$this->settings->save_post_meta( $post_id, 'the-guide-is-enabled', true );
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

			/*
			 * Moves
			 */

			$edit  = $bulk_actions['edit'];
			$trash = $bulk_actions['trash'];

			unset( $bulk_actions['edit'] );
			unset( $bulk_actions['trash'] );

			$bulk_actions['edit']  = $edit;
			$bulk_actions['trash'] = $trash;


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
				$this->settings->save_post_meta( $post_id, 'the-guide-is-enabled', false );
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
	}
}
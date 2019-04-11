<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class The_Guide_Ajax {

	/**
	 * Settings object.
	 *
	 * @since 0.1.0
	 * @var object The_Guide_Settings
	 */
	private $settings;


	public function __construct( The_Guide_Settings $settings_inst ) {
		$this->settings = $settings_inst;

		add_action( 'wp_ajax_the_guide_public_get_tour_data_by_id',        [ $this, 'public_get_tour_data_by_id' ] );
		add_action( 'wp_ajax_nopriv_the_guide_public_get_tour_data_by_id', [ $this, 'public_get_tour_data_by_id' ] );
		if ( current_user_can( 'list_users' ) ) {

			/*============================== TO BE REMOVED START ==============================*/

			add_action( 'wp_ajax_the_guide_controller_menu',             [ $this, 'controller_menu' ] );
			add_action( 'wp_ajax_the_guide_controller_menu_delete_tour', [ $this, 'controller_menu_delete_tour' ] );
			add_action( 'wp_ajax_the_guide_settings_menu',  [ $this, 'settings_menu' ] );

			/*============================== TO BE REMOVED END ==============================*/

			add_action( 'wp_ajax_the_guide_customize_menu', [ $this, 'customize_menu' ] );
		}
	}


	public function public_get_tour_data_by_id() {
		// Returns tour data only if the tour is enabled
		if ( in_array( $_POST['id'], (array) $this->settings->get_plugin_setting( 'enabled-tours' ) ) ) {
			// And if there is a token in the request
			if ( wp_verify_nonce( $_POST['token'], 'the-guide-public' ) ) {

				$the_tour_data = [];

				$the_tour_data['steps']                      = get_post_meta( $_POST['id'], 'the-guide-steps', true );
				$the_tour_data['stepsContent']               = get_post_meta( $_POST['id'], 'the-guide-steps-content', true );
				$the_tour_data['activationMethodAndItsData'] = get_post_meta( $_POST['id'], 'the-guide-activation-method-and-its-data', true );
				$the_tour_data['controllerMethodAndItsData'] = get_post_meta( $_POST['id'], 'the-guide-controller-method-and-its-data', true );

				echo json_encode( $the_tour_data );

			}
		}
		wp_die();
	}


	public function customize_menu() {
		if ( wp_verify_nonce( $_POST['token'], 'the-guide-customize-menu' ) ) {
			$this->settings->save_plugin_setting( 'custom-css', stripslashes( $_POST['customCSS'] ) );
		}
		wp_die();
	}


	/*============================== TO BE REMOVED START ==============================*/


	public function controller_menu() {
		if ( wp_verify_nonce( $_POST['token'], 'the-guide-controller-menu' ) ) {

			$this->settings->save_plugin_setting(
				'enabled-tours',
				$_POST['enabledTours']
			);

			$this->settings->save_plugin_setting(
				'positions',
				$_POST['positions']
			);

		}
		wp_die();
	}


	public function controller_menu_delete_tour() {
		if ( wp_verify_nonce( $_POST['token'], 'the-guide-controller-menu' ) ) {
			wp_delete_post( (int) $_POST['tour-id'], true );
		}
		wp_die();
	}


	public function settings_menu() {
		if ( wp_verify_nonce( $_POST['token'], 'the-guide-settings-menu' ) ) {

			$data = [
				'select-entity'              => $_POST['select-entity'],
				'name'                       => $_POST['name'],
				'url'                        => $_POST['url'],
				'steps'                      => $_POST['steps'],
				'stepsContent'               => $_POST['stepsContent'],
				'activationMethodAndItsData' => $_POST['activationMethodAndItsData'],
				'controllerMethodAndItsData' => $_POST['controllerMethodAndItsData']
			];


			// post_title (DATA), enabled-tours (DB Option: the-guide-settings)

			if ( $data['select-entity'] === 'add-new-entity' ) {

				$post_id = wp_insert_post( [
					'post_title'  => $data['name'],
					'post_type'   => 'the-guide',
					'post_status' => 'publish'
				] );

				// Adds a new tour to "enabled-tours" option
				$all_enabled_tours = $this->settings->get_plugin_setting( 'enabled-tours' );

				if ( $all_enabled_tours ) {
					if ( ! in_array( $post_id, $all_enabled_tours ) ) {
						$this->settings->save_plugin_setting( 'enabled-tours', array_push( $all_enabled_tours, $post_id ) );
					}
				} else {
					$this->settings->save_plugin_setting( 'enabled-tours', [ $post_id ] );
				}

			} else {

				$post_id = $data['select-entity'];

				if ( get_the_title( (int) $post_id ) !== $data['name'] ) {
					wp_update_post( [
						'ID'         => $post_id,
						'post_title' => $data['name'],
					] );
				}
			}

			// META

			// Tour URL
			$this->settings->save_post_meta(
				$post_id,
				'the-guide-url',
				$data['url']
			);

			// Tour Steps
			$this->settings->save_post_meta(
				$post_id,
				'the-guide-steps',
				$data['steps']
			);

			// Steps Content
			$this->settings->save_post_meta(
				$post_id,
				'the-guide-steps-content',
				$data['stepsContent']
			);

			// Activation Method & Its Data
			$this->settings->save_post_meta(
				$post_id,
				'the-guide-activation-method-and-its-data',
				$data['activationMethodAndItsData']
			);

			// Tour controller position & Its Data
			$this->settings->save_post_meta(
				$post_id,
				'the-guide-controller-method-and-its-data',
				$data['controllerMethodAndItsData']
			);


			// Returns new tour data to the user, if new tour was added
			if ( $data['select-entity'] === 'add-new-entity' ) {
				$the_post_data = [];

				$the_post_data['id']                         = $post_id;
				$the_post_data['name']                       = $data['name'];
				$the_post_data['url']                        = $data['url'];
				$the_post_data['steps']                      = $data['steps'];
				$the_post_data['activationMethodAndItsData'] = $data['activationMethodAndItsData'];
				$the_post_data['controllerMethodAndItsData'] = $data['controllerMethodAndItsData'];


				echo json_encode( $the_post_data );
			}
		}
		wp_die();
	}

	/*============================== TO BE REMOVED END ==============================*/
}

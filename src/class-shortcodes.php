<?php
/**
 * Shortcodes
 *
 * @package The Guide
 * @since 0.1.3
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Shortcodes.
 *
 * @since 0.1.3
 */
class The_Guide_Shortcodes {


	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		// It's impossible to use is_singular() before WP object is initialized.
		add_action( 'wp', [ $this, 'init_shortcodes' ] );
	}



	/**
	 * Inits shortcodes.
	 *
	 * @global WP_Post $post
	 */
	public function init_shortcodes() {
		global $post;

		add_shortcode( 'the-guide-launch', [ $this, 'shortcode_the_guide_launch' ] );
		add_shortcode( 'the-guide-go',     [ $this, 'shortcode_the_guide_go' ] );

		if ( is_singular() ) {
			$content            = $post->post_content;
			$post->post_content = do_shortcode( $content );
		}
	}



	/**
	 * "the-guide-launch" shortcode callback.
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public function shortcode_the_guide_launch( $atts ) {
		// Checks if the shortcode doesn't exist yet.
		if ( ! defined( 'THE_GUIDE_DOING_SHORTCODE_LAUNCH' ) ) {
			define( 'THE_GUIDE_DOING_SHORTCODE_LAUNCH', true );

			if ( isset( $atts['id'] ) ) {
				define( 'THE_GUIDE_SHORTCODE_LAUNCH_ID', (int) $atts['id'] );
			}
			if ( isset( $atts['step'] ) ) {
				// Reduces by 1 to use as index
				define( 'THE_GUIDE_SHORTCODE_LAUNCH_STEP', (int) $atts['step'] - 1 );
			}
		}

	}



	/**
	 * "the-guide-go" shortcode callback.
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public function shortcode_the_guide_go( $atts ) {
		// Checks if the shortcode doesn't exist yet.
		if ( ! defined( 'THE_GUIDE_DOING_SHORTCODE_GO' ) ) {
			define( 'THE_GUIDE_DOING_SHORTCODE_GO', true );

			if ( isset( $atts['step'] ) ) {
				// Reduces by 1 to use as index
				define( 'THE_GUIDE_SHORTCODE_GO_STEP', (int) $atts['step'] - 1 );
			}
		}
	}
}

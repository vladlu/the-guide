<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class The_Guide_Shortcodes {


	public function __construct() {
		// It's impossible to use is_singular() before WP object is initialized.
		add_action( 'wp', [ $this, 'init_shortcodes' ] );
	}


	public function init_shortcodes() {
		add_shortcode( 'the-guide-launch', [ $this, 'shortcode_the_guide_launch' ] );
		add_shortcode( 'the-guide-go',     [ $this, 'shortcode_the_guide_go' ] );

		if ( is_singular() ) {
			$content                       = $GLOBALS['post']->post_content;
			$GLOBALS['post']->post_content = do_shortcode( $content );
		}
	}


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

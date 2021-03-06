<?php
/**
 * Columns
 *
 * Adds new columns, edits/deletes the old columns (WP List Table for the-guide).
 *
 * @package The Guide
 * @since 0.1.3
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Columns.
 *
 * @since 0.1.3
 */
class The_Guide_Columns {

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

		add_filter( 'manage_the-guide_posts_columns',       [ $this, 'columns' ] );
		add_action( 'manage_the-guide_posts_custom_column', [ $this, 'custom_column' ], 10, 2 );
	}


	/**
	 * Adds new columns and rearranges the old columns.
	 *
	 * @since 0.1.0
	 *
	 * @param  array $columns The list of columns.
	 * @return array          The list of columns.
	 */
	public function columns( $columns ) {

		// Removes columns.

		unset( $columns['date'] );

		// Adds columns.

		$new_columns = [
			'url'     => __( 'URL',     'the-guide' ),
			'steps'   => __( 'Steps',   'the-guide' ),
			'enabled' => __( 'Enabled', 'the-guide' ),

			'date'    => __( 'Date' ),  // Moves it back to the end.
		];

		return array_merge( $columns, $new_columns );
	}


	/**
	 * Prints the content of the column.
	 *
	 * @since 0.1.0
	 *
	 * @param string $column  The name of the column.
	 * @param int    $post_id The ID of the post.
	 */
	public function custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'url':
				echo '<span class="the-guide-url">' . esc_html( get_post_meta( $post_id, 'the-guide-url', true ) ) . '</span>';

				break;
			case 'steps':
				?>
					<ol class="the-guide-steps" data-steps="
						<?php
							$steps = get_post_meta( $post_id, 'the-guide-steps', true );
							echo esc_attr( implode( ',', $steps ) );
						?>
					">
						<li><?php echo esc_attr( implode( '</li><li>', $steps ) ); ?></li>
					</ol>
				<?php

				break;
			case 'enabled':
				?>
				<input
					<?php checked( get_post_meta( $post_id, 'the-guide-is-enabled', true ) ); ?>
				disabled class="the-guide-is-enabled" type="checkbox">
				<?php

				break;
		}
	}
}

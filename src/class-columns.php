<?php


// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class The_Guide_Columns {

	/**
	 * Settings object.
	 *
	 * @since 0.1.0
	 * @var object The_Guide_Settings
	 */
	private $settings;



	public function __construct( The_Guide_Settings $settings_inst ) {
		$this->settings = $settings_inst;

		add_filter( 'manage_the-guide_posts_columns',       [ $this, 'columns' ] );
		add_action( 'manage_the-guide_posts_custom_column', [ $this, 'custom_column' ], 10, 2 );
	}



	public function columns( $columns ) {

		// Removes columns

		unset( $columns['date'] );

        // Adds columns

		$new_columns = [
			'url'     =>  __( 'URL',     'the-guide' ),
			'steps'   =>  __( 'Steps',   'the-guide' ),
			'enabled' =>  __( 'Enabled', 'the-guide' ),
            'id'      =>  __( 'ID',      'the-guide' ),

			'date' => 'Date' // Moves it back to the end
		];

		return array_merge( $columns, $new_columns );
	}



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
                            echo implode( ',', $steps );
                        ?>
                    ">
                        <li><?php echo implode( '</li><li>', $steps ) ?></li>
                    </ol>
				<?php

				break;
			case 'enabled':
				?>
                <input
					<?php
					$all_enabled_tours = $this->settings->get_plugin_setting( 'enabled-tours' );

					if ( $all_enabled_tours ) {
						if ( in_array( (string) $post_id, $all_enabled_tours ) ) {
							echo esc_attr( 'checked' );
						}
					}
					?>
                disabled class="the-guide-enabled" type="checkbox">
				<?php

				break;
			case 'id':
				echo '<span class="the-guide-id">' . $post_id . '</span>';

				break;
		}
	}
}

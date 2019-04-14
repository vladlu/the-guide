<?php

// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>


<form class="the-guide-submit-form" method="POST">
    <div class="the-guide-flex-container">

        <div class="the-guide-flex-item-button">
            <!-- Submit button -->
            <div class="the-guide-submit-button">
		        <?php submit_button( esc_attr__( 'Save Positions', 'the-guide' ), 'primary', '', false ) ?>
            </div>
        </div>

        <div class="the-guide-flex-item-table">
            <table>
                <thead class="the-guide-table-header">
                    <tr>
                        <td><b><?php _e( 'Enabled', 'the-guide' ) ?></b></td>
                        <td><b><?php _e( 'Tour Name', 'the-guide' ) ?></b></td>
                        <td><b><?php _e( 'URL', 'the-guide' ) ?></b></td>
                        <td><b><?php _e( 'Steps', 'the-guide' ) ?></b></td>
                        <td><b><?php _e( 'ID', 'the-guide' ) ?></b></td>
                    </tr>
                </thead>
                <tbody class="the-guide-rows">
                    <?php
                    $query = new WP_Query( [ 'post_type' => 'the-guide', 'posts_per_page' => -1 ] );
                    if ( $query->have_posts() ):
	                    while ( $query->have_posts() ): $query->the_post();
                        ?>
                            <tr class="the-guide-row">
                                <td class="the-guide-td-checkbox">
                                    <input
                                        <?php
                                        $tour_id = get_the_ID();

                                        if ( get_post_meta( get_the_ID(), 'the-guide-is-enabled', true ) ) {
	                                        echo esc_attr( 'checked' );
                                        }
                                        ?>
                                            class="the-guide-checkbox" type="checkbox">
                                </td>
                                <td>
                                    <?php the_title() ?>
                                </td>
                                <td>
                                    <?php echo esc_html( get_post_meta( $tour_id, 'the-guide-url', true ) ) ?>
                                </td>
                                <td>
                                    <ol>
                                        <li>
                                            <?php echo implode('</li><li>', get_post_meta( $tour_id, 'the-guide-steps', true ) ) ?>
                                        </li>
                                    </ol>
                                </td>
                                <td class="the-guide-id-and-delete-container">

                                    <input class="the-guide-delete-the-tour-button" type="button"
                                           title="<?php esc_attr_e('Delete this tour', 'the-guide') ?>" value="x">

                                    <div class="the-guide-id"><?php echo esc_html( $tour_id ) ?></div>


                                    <div class="the-guide-delete the-guide-hidden">
                                        <?php esc_attr_e( 'Are you sure you want to delete this tour?', 'the-guide' ) ?>
                                        <div class="the-guide-delete-tour-submit-buttons">
                                            <input class="the-guide-button-delete-this-tour button delete" type="button"
                                                   value="<?php esc_attr_e( 'Yes. Delete', 'the-guide' ) ?>">

                                            <input class="the-guide-button-go-back button" type="button"
                                                   value="<?php esc_attr_e( 'No. Go back', 'the-guide' ) ?>">
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php wp_reset_postdata() ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</form>

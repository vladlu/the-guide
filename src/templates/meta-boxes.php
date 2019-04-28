<?php

// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>



<?php wp_nonce_field( 'the-guide-edit-tour', 'the-guide_edit_tour' ); ?>



<!-- Enabled -->
<div class="the-guide-flex-container">
	<div class="the-guide-flex-title">
		<label class="the-guide-bold" for="the-guide-is-enabled">
			<?php esc_html_e( 'Enabled', 'the-guide' ) ?>
		</label>
	</div>
	<div class="the-guide-flex-input">
		<input class="the-guide-is-enabled" name="the-guide-is-enabled" type="checkbox"
			<?php checked( get_post_meta( $post->ID, 'the-guide-is-enabled', true ) ) ?>
		>
	</div>
</div>



<!-- Activation method -->
<div class="the-guide-tour-activation">

	<!-- Selector -->
	<div class="the-guide-flex-container-primary">

		<div class="the-guide-flex-title">
			<label class="the-guide-bold" for="the-guide-select-activation-method">
				<?php esc_html_e( 'Activation method', 'the-guide' ) ?>
			</label>
		</div>

		<div class="the-guide-flex-input">
			<?php
			$activation_method = get_post_meta( $post->ID, 'the-guide-activation-method-and-its-data', true );
			$activation_method_itself = ( is_array( $activation_method ) && array_key_exists( 'method', $activation_method ) ) ?
				$activation_method['method'] : '';
			?>
			<select name="the-guide-select-activation-method" id="the-guide-select-activation-method">
				<option class="the-guide-activation"
					<?php selected( $activation_method_itself, 'on-load' ) ?>
                    value="on-load"><?php esc_html_e( 'On page load', 'the-guide' ) ?></option>

				<option class="the-guide-activation"
					<?php selected( $activation_method_itself, 'floating' ) ?>
                    value="floating"><?php esc_html_e( 'Floating block', 'the-guide' ) ?></option>

				<option class="the-guide-activation"
					<?php selected( $activation_method_itself, 'on-click' ) ?>
                    value="on-click"><?php esc_html_e( 'On click', 'the-guide' ) ?></option>
			</select>
		</div>

	</div>


	<!-- Floating block -->
	<div class="the-guide-activation-floating the-guide-hidden">

		<!-- Text -->
		<div class="the-guide-flex-container">
			<div class="the-guide-flex-title">
				<label for="the-guide-activation-floating-text">
					<?php esc_html_e( 'Floating block text', 'the-guide' ) ?>
				</label>
			</div>
			<div class="the-guide-flex-input">
				<textarea name="the-guide-activation-floating-text" id="the-guide-activation-floating-text"
                    rows="4"><?php
                    echo ( is_array( $activation_method ) && array_key_exists( 'floatingText', $activation_method ) ) ?
                            $activation_method['floatingText'] : ''
                    ?></textarea>
			</div>
		</div>


		<!-- Position -->
		<div class="the-guide-flex-container-activation-method">

			<div class="the-guide-flex-item-text">
				<?php esc_html_e( 'Position (CSS)', 'the-guide' ) ?>
			</div>

			<div class="the-guide-flex-item-position-title">
				<label class="the-guide-position-title" for="the-guide-activation-position-top">
					<?php esc_html_e( 'Top', 'the-guide' ) ?>
				</label>
				<label class="the-guide-position-title" for="the-guide-activation-position-bottom">
					<?php esc_html_e( 'Bottom', 'the-guide' ) ?>
				</label>
				<label class="the-guide-position-title" for="the-guide-activation-position-left">
					<?php esc_html_e( 'Left', 'the-guide' ) ?>
				</label>
				<label class="the-guide-position-title" for="the-guide-activation-position-right">
					<?php esc_html_e( 'Right', 'the-guide' ) ?>
				</label>
			</div>
			<div class="the-guide-flex-item-position-input">
				<?php $activation_method_position = ( is_array( $activation_method ) && array_key_exists( 'position', $activation_method ) ) ?
					$activation_method['position'] : '' ?>


				<input name="the-guide-activation-position-top" id="the-guide-activation-position-top" type="text"
                    value="<?php
					echo ( is_array( $activation_method_position ) && array_key_exists( 'top', $activation_method_position ) ) ?
						$activation_method_position['top'] : '';
					?>">
				<input name="the-guide-activation-position-bottom" id="the-guide-activation-position-bottom" type="text"
                   value="<?php
					echo ( is_array( $activation_method_position ) && array_key_exists( 'bottom', $activation_method_position ) ) ?
						$activation_method_position['bottom'] : '';
					?>">
				<input name="the-guide-activation-position-left" id="the-guide-activation-position-left" type="text"
                    value="<?php
					echo ( is_array( $activation_method_position ) && array_key_exists( 'left', $activation_method_position ) ) ?
						$activation_method_position['left'] : '';
					?>">
				<input name="the-guide-activation-position-right" id="the-guide-activation-position-right" type="text"
                    value="<?php
					echo ( is_array( $activation_method_position ) && array_key_exists( 'right', $activation_method_position ) ) ?
						$activation_method_position['right'] : '';
					?>">
			</div>
		</div>

	</div>


	<!-- On click -->
	<div class="the-guide-flex-container the-guide-activation-selectors the-guide-hidden">
		<div class="the-guide-flex-title">
			<label for="the-guide-activation-selectors">
				<?php esc_html_e( 'Selectors', 'the-guide' ) ?>
			</label>
		</div>
		<div class="the-guide-flex-input">
			<input name="the-guide-activation-selectors" id="the-guide-activation-selectors" type="text"
                value="<?php
                echo ( is_array( $activation_method )                      &&
                       array_key_exists( 'selectors', $activation_method ) &&
                       is_array( $activation_method['selectors'] ) ) ?
                    implode( ', ', $activation_method['selectors'] ) : '';
                ?>">
		</div>
	</div>

</div>



<!-- Tour controller position -->
<div class="the-guide-controller-position">

    <div class="the-guide-flex-container-primary">
        <div class="the-guide-flex-title">
            <label class="the-guide-bold" for="the-guide-select-controller-method">
				<?php esc_html_e( 'Tour controller position', 'the-guide' ) ?>
            </label>
        </div>
        <div class="the-guide-flex-input">
	        <?php
	        $controller_method = get_post_meta( $post->ID, 'the-guide-controller-method-and-its-data', true );
	        $controller_method_itself = ( is_array( $controller_method ) && array_key_exists( 'method', $controller_method ) ) ?
		        $controller_method['method'] : '';
	        ?>
            <select name="the-guide-select-controller-method" id="the-guide-select-controller-method">
                <option class="the-guide-controller"
	                <?php selected( $controller_method_itself, 'next-to-the-selected-elem' ) ?>
                    value="next-to-the-selected-elem"><?php esc_html_e( 'Next to the selected element', 'the-guide' ) ?></option>
                <option class="the-guide-controller"
	                <?php selected( $controller_method_itself, 'floating' ) ?>
                    value="floating"><?php esc_html_e( 'Floating block', 'the-guide' ) ?></option>
            </select>
        </div>
    </div>


    <!-- Floating -->
    <div class="the-guide-controller-position-floating the-guide-hidden">
        <div class="the-guide-flex-container">

            <div class="the-guide-flex-item-text">
				<?php esc_html_e( 'Position (CSS)', 'the-guide' ) ?>
            </div>

            <div class="the-guide-flex-item-position-title">
                <label class="the-guide-position-title" for="the-guide-controller-position-top">
					<?php esc_html_e( 'Top', 'the-guide' ) ?>
                </label>
                <label class="the-guide-position-title" for="the-guide-controller-position-bottom">
					<?php esc_html_e( 'Bottom', 'the-guide' ) ?>
                </label>
                <label class="the-guide-position-title" for="the-guide-controller-position-left">
					<?php esc_html_e( 'Left', 'the-guide' ) ?>
                </label>
                <label class="the-guide-position-title" for="the-guide-controller-position-right">
					<?php esc_html_e( 'Right', 'the-guide' ) ?>
                </label>
            </div>
            <div class="the-guide-flex-item-position-input">
	            <?php $controller_method_position = ( is_array( $controller_method ) && array_key_exists( 'position', $controller_method ) ) ?
		            $controller_method['position'] : '' ?>
                
                <input name="the-guide-controller-position-top" id="the-guide-controller-position-top" type="text" 
                   value="<?php
                   echo ( is_array( $controller_method_position ) && array_key_exists( 'top', $controller_method_position ) ) ?
	                   $controller_method_position['top'] : '';
                    ?>">
                <input name="the-guide-controller-position-bottom" id="the-guide-controller-position-bottom" type="text"
                   value="<?php
                   echo ( is_array( $controller_method_position ) && array_key_exists( 'bottom', $controller_method_position ) ) ?
	                   $controller_method_position['bottom'] : '';
                   ?>">
                <input name="the-guide-controller-position-left" id="the-guide-controller-position-left" type="text"
                   value="<?php
                   echo ( is_array( $controller_method_position ) && array_key_exists( 'left', $controller_method_position ) ) ?
	                   $controller_method_position['left'] : '';
                   ?>">
                <input name="the-guide-controller-position-right" id="the-guide-controller-position-right" type="text"
                   value="<?php
                   echo ( is_array( $controller_method_position ) && array_key_exists( 'right', $controller_method_position ) ) ?
	                   $controller_method_position['right'] : '';
                   ?>">
            </div>
        </div>
    </div>

</div>



<!-- Tour URL -->
<div class="the-guide-flex-container">
    <div class="the-guide-flex-title">
        <label class="the-guide-bold" for="the-guide-url">
			<?php esc_html_e( 'Tour URL', 'the-guide' ) ?>
        </label>
    </div>
    <div class="the-guide-flex-input">
        <input required name="the-guide-url" id="the-guide-url" type="text"
               value="<?php echo get_post_meta( $post->ID, 'the-guide-url', true ); ?>">
    </div>
</div>



<!-- Selected elements (steps) -->
<div class="the-guide-flex-container">
    <div class="the-guide-flex-title">
        <label class="the-guide-bold" for="the-guide-steps">
			<?php esc_html_e( 'Steps (comma-separated CSS selectors)', 'the-guide' ) ?>
        </label>
    </div>
    <div class="the-guide-flex-input">
        <input required name="the-guide-steps" id="the-guide-steps" type="text"
            value="<?php
            $steps = get_post_meta( $post->ID, 'the-guide-steps', true );
            if ( is_array( $steps ) ) {
                echo implode( ', ', $steps );
            }
        ?>">
    </div>
</div>



<!-- Selected elements (steps) Content -->
<div class="the-guide-steps-content">
	<?php
	if ( is_array( $steps ) ):
        $steps_content = get_post_meta( $post->ID, 'the-guide-steps-content', true );
        $i = 0;
        foreach( $steps as $step ):
        ?>
        <div class="the-guide-flex-container">
            <div class="the-guide-flex-title-content">
                <label for="the-guide-step-content-<?php echo $i ?>">
                    <?php echo $steps[$i] ?>
                </label>
                </div>
            <div class="the-guide-flex-input">
                <textarea class="the-guide-step-content"
                    name="the-guide-step-content-<?php echo $i ?>" id="the-guide-step-content-<?php echo $i ?>" rows="5"
                    ><?php echo $steps_content[$i] ?></textarea>
            </div>
        </div>
        <?php
        $i++;
        endforeach;
    endif;
	?>
</div>

<?php

// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>


<h1 class="the-guide-header"><?php esc_html_e( 'The Guide Settings', 'the-guide' ) ?></h1>
<form class="the-guide-form" method="POST">


    <hr>


    <!-- Select the tour -->
    <div class="the-guide-flex-container">
        <div class="the-guide-flex-title">
            <label class="the-guide-bold" for="the-guide-select-entity">
                <?php esc_html_e( 'Select the tour:', 'the-guide' ) ?>
            </label>
        </div>
        <div class="the-guide-flex-input">
            <select autofocus id="the-guide-select-entity"></select>
        </div>
    </div>



    <!-- Activation method -->
    <div class="the-guide-tour-activation">

        <!-- Selector -->
        <div class="the-guide-flex-container-primary">

            <div class="the-guide-flex-title">
                <label class="the-guide-bold" for="the-guide-select-activation-method">
                    <?php esc_html_e( 'Activation method:', 'the-guide' ) ?>
                </label>
            </div>

            <div class="the-guide-flex-input">
                <select autofocus id="the-guide-select-activation-method">
                    <option class="the-guide-activation" value="on-load"><?php esc_html_e( 'On page load', 'the-guide' ) ?></option>
                    <option class="the-guide-activation" value="floating"><?php esc_html_e( 'Floating block', 'the-guide' ) ?></option>
                    <option class="the-guide-activation" value="on-click"><?php esc_html_e( 'On click', 'the-guide' ) ?></option>
                </select>
            </div>

        </div>


        <!-- Floating block -->
        <div class="the-guide-activation-data-floating the-guide-hidden">

            <!-- Text -->
            <div class="the-guide-flex-container">
                <div class="the-guide-flex-title">
                    <label for="the-guide-activation-data-floating-text">
                        <?php esc_html_e( 'Floating block text:', 'the-guide' ) ?>
                    </label>
                </div>
                <div class="the-guide-flex-input">
                    <textarea id="the-guide-activation-data-floating-text" rows="4"></textarea>
                </div>
            </div>


            <!-- Position -->
            <div class="the-guide-flex-container-activation-method">

                <div class="the-guide-flex-item-text">
                    <?php esc_html_e( 'Position (CSS):', 'the-guide' ) ?>
                </div>

                <div class="the-guide-flex-item-position-title">
                    <label class="the-guide-position-title" for="the-guide-activation-data-position-input-top">
                        <?php esc_html_e( 'Top:', 'the-guide' ) ?>
                    </label>
                    <label class="the-guide-position-title" for="the-guide-activation-data-position-input-bottom">
                        <?php esc_html_e( 'Bottom:', 'the-guide' ) ?>
                    </label>
                    <label class="the-guide-position-title" for="the-guide-activation-data-position-input-left">
                        <?php esc_html_e( 'Left:', 'the-guide' ) ?>
                    </label>
                    <label class="the-guide-position-title" for="the-guide-activation-data-position-input-right">
                        <?php esc_html_e( 'Right:', 'the-guide' ) ?>
                    </label>
                </div>
                <div class="the-guide-flex-item-position-input">
                    <input id="the-guide-activation-data-position-input-top" type="text">
                    <input id="the-guide-activation-data-position-input-bottom" type="text">
                    <input id="the-guide-activation-data-position-input-left" type="text">
                    <input id="the-guide-activation-data-position-input-right" type="text">
                </div>
            </div>

        </div>


        <!-- On click -->
        <div class="the-guide-flex-container the-guide-activation-data-selectors the-guide-hidden">
            <div class="the-guide-flex-title">
                <label for="the-guide-activation-data-selectors-input">
				    <?php esc_html_e( 'Selectors:', 'the-guide' ) ?>
                </label>
            </div>
            <div class="the-guide-flex-input">
                <input id="the-guide-activation-data-selectors-input" type="text">
            </div>
        </div>

    </div>


    <!-- Tour controller position -->
    <div class="the-guide-controller-position">

        <div class="the-guide-flex-container-primary">
            <div class="the-guide-flex-title">
                <label class="the-guide-bold" for="the-guide-select-controller-position">
                    <?php esc_html_e( 'Tour controller position :', 'the-guide' ) ?>
                </label>
            </div>
            <div class="the-guide-flex-input">
                <select autofocus id="the-guide-select-controller-position">
                    <option class="the-guide-controller" value="next-to-the-selected-elem"><?php esc_html_e( 'Next to the selected element', 'the-guide' ) ?></option>
                    <option class="the-guide-controller" value="floating"><?php esc_html_e( 'Floating block', 'the-guide' ) ?></option>
                </select>
            </div>
        </div>


        <div class="the-guide-controller-position-floating the-guide-hidden">
            <div class="the-guide-flex-container">

                <div class="the-guide-flex-item-text">
                    <?php esc_html_e( 'Position (CSS):', 'the-guide' ) ?>
                </div>

                <div class="the-guide-flex-item-position-title">
                    <label class="the-guide-position-title" for="the-guide-controller-position-input-top">
                        <?php esc_html_e( 'Top:', 'the-guide' ) ?>
                    </label>
                    <label class="the-guide-position-title" for="the-guide-controller-position-input-bottom">
                        <?php esc_html_e( 'Bottom:', 'the-guide' ) ?>
                    </label>
                    <label class="the-guide-position-title" for="the-guide-controller-position-input-left">
                        <?php esc_html_e( 'Left:', 'the-guide' ) ?>
                    </label>
                    <label class="the-guide-position-title" for="the-guide-controller-position-input-right">
                        <?php esc_html_e( 'Right:', 'the-guide' ) ?>
                    </label>
                </div>
                <div class="the-guide-flex-item-position-input">
                    <input id="the-guide-controller-position-input-top" type="text">
                    <input id="the-guide-controller-position-input-bottom" type="text">
                    <input id="the-guide-controller-position-input-left" type="text">
                    <input id="the-guide-controller-position-input-right" type="text">
                </div>
            </div>
        </div>

    </div>


    <!-- Tour name -->
    <div class="the-guide-flex-container">
        <div class="the-guide-flex-title">
            <label class="the-guide-bold" for="the-guide-tour-name">
                <?php esc_html_e( 'Tour name:', 'the-guide' ) ?>
            </label>
        </div>
        <div class="the-guide-flex-input">
            <input required id="the-guide-tour-name" type="text">
        </div>
    </div>

    <!-- Tour URL -->
    <div class="the-guide-flex-container">
        <div class="the-guide-flex-title">
            <label class="the-guide-bold" for="the-guide-url">
                <?php esc_html_e( 'Tour URL (regexp):', 'the-guide' ) ?>
            </label>
        </div>
        <div class="the-guide-flex-input">
            <input required id="the-guide-url" type="text">
        </div>
    </div>

    <!-- Selected elements (steps) -->
    <div class="the-guide-flex-container-selected-elems">
        <div class="the-guide-flex-title">
            <label class="the-guide-bold" for="the-guide-steps">
                <?php esc_html_e( 'Selected elements, separated by a comma:', 'the-guide' ) ?>
            </label>
        </div>
        <div class="the-guide-flex-input">
            <input required id="the-guide-steps" type="text">
        </div>
    </div>

    <!-- Selected elements (steps) Content -->
    <div class="the-guide-steps-content">
    </div>


    <!-- Submit button -->
	<input type="submit" class="the-guide-submit-button button button-primary"
	       value="<?php esc_attr_e( 'Save Changes', 'the-guide' ) ?>"
	>

</form>
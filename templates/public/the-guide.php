<?php

// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>


<div class="the-guide-floating-block the-guide-hidden">
    <div class="the-guide-floating-block-container">
        <div class="the-guide-floating-block-content"></div>
        <div class="the-guide-floating-block-button-container">
            <input class="the-guide-floating-block-button" type="button" value="<?php esc_attr_e( 'Start the tour', 'the-guide' ) ?>" >
        </div>
    </div>
</div>


<div class="the-guide-modal the-guide-hidden">
    <div class="the-guide-modal-container">
        <div class="the-guide-modal-current-elem"></div>
        <div class="the-guide-modal-content"></div>
        <div class="the-guide-modal-buttons">
            <input id="the-guide-modal-button-prev" type="button" name="" value="<?php _e( 'Previous', 'the-guide' ) ?>">
            <input id="the-guide-modal-button-next" type="button" name="" value="<?php _e( 'Next', 'the-guide' ) ?>">
        </div>
    </div>
</div>

<div class="the-guide-modal-next-to-the-elem the-guide-hidden">
    <div class="the-guide-modal-next-to-the-elem-container">

        <div class="the-guide-modal-next-to-the-elem-button-prev-container">
            <input id="the-guide-modal-next-to-the-elem-button-prev" type="button" name="" value="<?php _e( 'Previous', 'the-guide' ) ?>">
        </div>

        <div class="the-guide-modal-next-to-the-elem-current-elem"></div>

        <div class="the-guide-modal-next-to-the-elem-content"></div>

        <div class="the-guide-modal-next-to-the-elem-button-next-container">
            <input id="the-guide-modal-next-to-the-elem-button-next" type="button" name="" value="<?php _e( 'Next', 'the-guide' ) ?>">
        </div>

    </div>
</div>


<div class="the-guide-shadow the-guide-hidden"></div>
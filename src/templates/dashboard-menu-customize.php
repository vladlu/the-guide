<?php

// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>


<h1 class="the-guide-header"><?php esc_html_e( 'Custom CSS', 'the-guide' ) ?></h1>
<h2 class="the-guide-header"><?php esc_html_e( 'Customize public view', 'the-guide' ) ?></h2>

<form class="the-guide-submit-form" method="POST">
	<div class="the-guide-flex-container">
		<div class="the-guide-flex-item-input">
			<textarea class="the-guide-customize-input-area"
            ><?php
                $custom_css = $this->settings->get_plugin_setting( 'custom-css' );
                echo esc_textarea( ( $custom_css ? $custom_css :
                    file_get_contents( THE_GUIDE_DIR  . 'public/styles/the-guide.css' ) ) );
             ?></textarea>
		</div>
	</div>

	<div class="the-guide-flex-container">
		<div class="the-guide-flex-item-input-submit">
            <input type="submit" class="the-guide-submit-button button button-primary"
            value="<?php esc_attr_e( 'Save Changes', 'the-guide' ) ?>"
            >
		</div>
	</div>
</form>
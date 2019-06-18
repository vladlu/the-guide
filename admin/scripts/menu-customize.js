'use strict';

jQuery( $ => {

    let cm = CodeMirror.fromTextArea(
        document.querySelector( '.the-guide-customize-input-area' ),
        { lineNumbers: true, lineWrapping: true }
    );


    $( '.the-guide-submit-form' ).submit( (event) => {
        event.preventDefault();

        // Sends
        let data = {
            'action':      'the_guide_save_custom_css',
            'nonceToken':       theGuide.nonceToken,

            'customCSS':  cm.getValue(),
        };
        $.post( ajaxurl, data );
    });
});

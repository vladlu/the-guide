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
            'action':      'the_guide_menu_customize',
            'token':       theGuide.token,

            'customCSS':  cm.getValue(),
        };
        $.post( ajaxurl, data );
    });
});

/**
 * Functionality for Customize menu.
 *
 * @author Vladislav Luzan
 * @since 0.1.0
 */
'use strict';


jQuery( $ => {

    /**
     * Inits CodeMirror for the form.
     *
     * @since 0.1.0
     */
    let cm = CodeMirror.fromTextArea(
        document.querySelector( '.the-guide-customize-input-area' ),
        { lineNumbers: true, lineWrapping: true }
    );


    /**
     * On submit action: sends the data using AjAX.
     *
     * @since 0.1.0
     *
     * @listens .the-guide-submit-form:submit
     *
     * @param {Event} event The event object.
     *
     * @return {void}
     */
    $( '.the-guide-submit-form' ).submit( event => {
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

/**
 * Custom CSS.
 *
 * @author Vladislav Luzan
 * @since 0.1.0
 */
'use strict';


/**
 * Adds custom CSS.
 *
 * Retrieves custom CSS from the server using AJAX and inserts it to the DOM.
 *
 * @since 0.1.0
 *
 * @global
 *
 * @return {void}
 */
function theGuide_loadCustomCSS() {
    let data = {
        'action': 'the_guide_public_get_custom_css',
        'nonceToken':  theGuide.theGuideData.nonceTokenGetCustomCSS
    };

    /**
     * Retrieves custom CSS from the server and adds it to the DOM.
     *
     * @since 0.1.0
     *
     * @param {string} customCSS The custom CSS to add to the DOM.
     */
    jQuery.post( theGuide.ajaxurl, data, customCSS => {
        if ( customCSS ) {
            let css = document.createElement("style");
            css.innerHTML = customCSS;
            document.body.appendChild(css);
        }
    });
}

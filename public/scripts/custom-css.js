'use strict';


function theGuide_loadCustomCSS() {
    /*
     * Gets custom CSS and inserts it into the DOM.
     */
    let data = {
        'action': 'the_guide_public_get_custom_css',
        'nonceToken':  theGuide.theGuideData.nonceTokenGetCustomCSS
    };
    jQuery.post( theGuide.ajaxurl, data, customCSS => {
        if ( customCSS ) {
            var css = document.createElement("style");
            css.type = "text/css";
            css.innerHTML = customCSS;
            document.body.appendChild(css);
        }
    });
}

'use strict';


jQuery( $ => {

    /**
     * Quick Edit
     */

    if ( typeof inlineEditPost !== 'undefined' ) {
        // we create a copy of the WP inline edit post function
        var $wp_inline_edit = inlineEditPost.edit;

        // and then we overwrite the function with our own code
        inlineEditPost.edit = function( id ) {
            // "call" the original WP edit function
            // we don't want to leave WordPress hanging
            $wp_inline_edit.apply( this, arguments );

            // now we take care of our business

            var $post_id = 0;
            if ( typeof( id ) == 'object' ) {
                $post_id = parseInt( this.getId( id ) );
            }

            if ( $post_id > 0 ) {

                /**
                 * Inserts Data
                 */

                let $post_row = $( '#post-' + $post_id ),
                    $edit_row = $( '#edit-' + $post_id ),

                    // Gets the data
                    $enabled = !! $( '.the-guide-enabled', $post_row ).prop( 'checked' ),
                    $url     =    $( '.the-guide-url',     $post_row ).text(),
                    $steps   =    $( '.the-guide-steps',   $post_row ).data( 'steps' ).replace(/\s{2,}/g, ''); // Removes double spaces

                // Populates the data
                $( '.the-guide-enabled', $edit_row ).prop( 'checked', $enabled );
                $( '.the-guide-url',     $edit_row ).val( $url );
                $( '.the-guide-steps',   $edit_row ).val( $steps );

                /**
                 * Rearranges Blocks
                 */

                $( '.inline-edit-the-guide', $edit_row ).detach().insertAfter( $( '.inline-edit-col-left', $edit_row ) );

            }
        };
    }
});

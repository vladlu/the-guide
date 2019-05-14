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
                    $enabled = !! $( '.the-guide-is-enabled', $post_row ).prop( 'checked' ),
                    $url     =    $( '.the-guide-url',     $post_row ).text(),
                    $steps   =    $( '.the-guide-steps',   $post_row ).data( 'steps' ).replace(/\s{2,}/g, ''); // Removes double spaces

                // Populates the data
                $( '.the-guide-is-enabled', $edit_row ).prop( 'checked', $enabled );
                $( '.the-guide-url',     $edit_row ).val( $url );
                $( '.the-guide-steps',   $edit_row ).val( $steps );
            }
        };
    }


    /**
     * Reprioritizing
     *
     * Based on WooCommerce sorting
     * assets/js/admin/product-ordering.js
     */


    let currentURL =  window.location.href,
        sortingText = 'post_type=the-guide&orderby=menu_order+title&order=ASC';

    if ( currentURL.includes( sortingText ) ) {
        $( 'table.widefat tbody th, table.widefat tbody td' ).css( 'cursor', 'move' );

        jQuery( 'table.widefat tbody' ).sortable( {
            items: 'tr:not(.inline-edit-row)',
            cursor: 'move',
            axis: 'y',
            containment: 'table.widefat',
            scrollSensitivity: 40,
            helper: function( event, ui ) {
                ui.each( function() {
                    $( this ).width( $( this ).width() );
                });
                return ui;
            },
            start: function( event, ui ) {
                ui.item.css( 'background-color', '#ffffff' );
                ui.item.children( 'td, th' ).css( 'border-bottom-width', '0' );
                ui.item.css( 'outline', '1px solid #dfdfdf' );
            },
            stop: function( event, ui ) {
                ui.item.removeAttr( 'style' );
                ui.item.children( 'td,th' ).css( 'border-bottom-width', '1px' );
            },
            update: function( event, ui ) {
                $( 'table.widefat tbody th, table.widefat tbody td' ).css( 'cursor', 'default' );
                $( 'table.widefat tbody' ).sortable( 'disable' );

                var postid     = ui.item.find( '.check-column input' ).val();
                var prevpostid = ui.item.prev().find( '.check-column input' ).val();
                var nextpostid = ui.item.next().find( '.check-column input' ).val();

                // Show Spinner
                ui.item.find( '.check-column input' ).hide().after( '<img alt="processing" src="images/wpspin_light.gif" class="waiting" style="margin-left: 6px;" />' );

                // Go do the sorting stuff via ajax
                $.post( ajaxurl,
                        { action: 'the_guide_reorder_tours', token: theGuide.tokenReorderTours, id: postid, previd: prevpostid, nextid: nextpostid },
                        function( response ) {
                    $.each( response, function( key, value ) {
                        $( '#inline_' + key + ' .menu_order' ).html( value );
                    });
                    ui.item.find( '.check-column input' ).show().siblings( 'img' ).remove();
                    $( 'table.widefat tbody th, table.widefat tbody td' ).css( 'cursor', 'move' );
                    $( 'table.widefat tbody' ).sortable( 'enable' );
                });

                // fix cell colors
                $( 'table.widefat tbody tr' ).each( function() {
                    var i = $( 'table.widefat tbody tr' ).index( this );
                    if ( i%2 === 0 ) {
                        $( this ).addClass( 'alternate' );
                    } else {
                        $( this ).removeClass( 'alternate' );
                    }
                });
            }
        } );
    }
});

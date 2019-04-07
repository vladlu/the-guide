'use strict';

jQuery( $ => {

    // Moves elements according to their positions
    (function () {
        if ( theGuide.positions ) {
            theGuide.positions.forEach( id => {
                const $theRow = $( '.the-guide-id:contains(' + id + ')' ).closest( 'tr.the-guide-row' );

                $( 'tbody.the-guide-rows' ).append( $theRow );
            });
        }
    })();



    // Sets the button's container width no more than necessary
    (function() {
        $( '.the-guide-flex-item-button' ).css( 'min-width', $( '.the-guide-submit-button' ).outerWidth() + 20 );
    })();



    // Makes the button fixed only vertically
    (function() {
        const $Button = $( '.the-guide-submit-button' );

        let defaultLeft = parseFloat( $Button.css( 'left' ) ),
            defaultWindowWidth =  $(window).width();


        $( window ).resize( () => { $( window ).scroll() } );


        $( window ).scroll( () => {

            if ( $(window).width() === defaultWindowWidth ) {
                $Button.css( 'left', defaultLeft - parseFloat( $( window ).scrollLeft() ) );
            } else {
                $Button.css( 'left', 'auto' );

                defaultLeft = parseFloat( $Button.css( 'left' ) );
                defaultWindowWidth =  $(window).width();

                $( window ).scroll();
            }
        });
    })();



    // Sets each TD width to the maximum of the column elem size
    (function() {

        const VISIBLE_COLUMNS_QUANTITY = 5;

        let maxWidth = '';


        function getMaxColumnElemWidth(selector) {
            maxWidth = '';

            $( selector ).each(function() {
                if ( $(this).innerWidth() > maxWidth )
                    maxWidth = $(this).innerWidth();
            });
        }


        function setMinWidthForEachColumnElem(selector) {
            $( selector ).each(function() {
                $(this).css('min-width', maxWidth);
                $(this).css('max-width', maxWidth);
            });
        }


        for ( let the_column = 1; the_column - 1 < VISIBLE_COLUMNS_QUANTITY ; ++the_column ) {
            getMaxColumnElemWidth( 'td:nth-child(' + the_column + ')' );
            setMinWidthForEachColumnElem( 'td:nth-child(' + the_column + ')' );
        }
    })();



    // Moves submit button to the center of the table if table
    // height is less than 100% of the screen
    function moveSubmitButtonToCenter() {
        const $table  = $( '.the-guide-flex-item-table' ),
              $button = $( '.the-guide-submit-button' ),
              windowHeight = $(window).height(),

              tableOffsetTop  = parseFloat( $table.offset().top ),
              halfTableHeight = parseFloat( $table.outerHeight() / 2 ),

              halfButtonHeight = parseFloat( $button.outerHeight() / 2 );


        // If table is bigger than screen, then moves button to the center of the screen
        if ( $table.height() >= windowHeight )
            $button.css( 'top', windowHeight / 2 - halfButtonHeight );
        else
            $button.css( 'top', tableOffsetTop + halfTableHeight - halfButtonHeight );
    }
    moveSubmitButtonToCenter();



    // Makes table rows sortable
    $( 'tbody.the-guide-rows' ).sortable();



    // Makes an area around checkbox clickable
    $( '.the-guide-td-checkbox' ).click( function(event) {
        if (event.target !== event.currentTarget) return;
        $(this).children( '.the-guide-checkbox' ).click();
    });



    // Sends enabled tours' IDs to the server & tours position (when submit button is pressed)
    $( '.the-guide-submit-form' ).submit( (event) => {
        event.preventDefault();

        let enabledTours = [];
        // Adds ID of the each tour with checked checkbox to the array
        $('.the-guide-checkbox:checked').each(function() {
            enabledTours.push( $(this).parent().parent().find( '.the-guide-id' ).text() );
        });

        let positionsData = [];
        // Adds IDs of the each tour sequentially
        $( '.the-guide-id' ).each(function() {
            positionsData.push( $( this ).text() );
        });

        // Sends
        let data = {
            'action':               'the_guide_controller_menu',
            'token':                theGuide.token,

            'enabledTours':         enabledTours,
            'positions':            positionsData
        };
        $.post( ajaxurl, data );
    });



    // When user deletes a tour
    $( '.the-guide-delete-the-tour-button' ).click( function() {
        const $closestTD = $( this ).closest( 'td' ),

              $deleteButton = $closestTD.find( '.the-guide-delete-the-tour-button' ),
              $id           = $closestTD.find( '.the-guide-id' ),

              $deleteTour           = $closestTD .find( '.the-guide-delete' ),
              $deleteThisTourButton = $deleteTour.find( '.the-guide-button-delete-this-tour' ),
              $goBackButton         = $deleteTour.find( '.the-guide-button-go-back' ),

              oldMaxWidth = $closestTD.css( 'max-width' );



        $deleteButton.hide();
        $id          .hide();

        $deleteTour.show();


        $closestTD.css( 'max-width', '300px' );
        $closestTD.css( 'width', '300px' );



        function deleteThisTourButtonClickHandler() {
            $closestTD.parent().remove();


            let data = {
                'action':               'the_guide_controller_menu_delete_tour',
                'token':                theGuide.token,

                'tour-id':              $id.text(),
            };

            $.post( ajaxurl, data );

            // Updates positions without a deleted tour
            $( '.the-guide-submit-form' ).submit();

            // Moves button to the center
            moveSubmitButtonToCenter();
        }
        $( $deleteThisTourButton ).off( 'click.theGuide' )
                                   .on( 'click.theGuide', deleteThisTourButtonClickHandler );


        function goBackButtonClickHandler() {
            $deleteTour.hide();

            $deleteButton.show();
            $id          .show();


            $closestTD.css( 'max-width', oldMaxWidth )
        }
        $( $goBackButton ).off( 'click.theGuide' )
                           .on( 'click.theGuide', goBackButtonClickHandler );
    });
});

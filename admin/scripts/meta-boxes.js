'use strict';

/**
 * Functionality for tour meta boxes.
 *
 * @since 0.1.0
 *
 * @return {void}
 */
jQuery( $ => {
    const $selectActivationMethod =   $( '#the-guide-select-activation-method' ),
          $selectControllerMethod =   $( '#the-guide-select-controller-method' );



    $selectActivationMethod.change( () => {
        const $selectedActivationMethod = $( '#the-guide-select-activation-method option:selected' );

        if ( $selectedActivationMethod.val() === 'on-load' ) {
            $( '.the-guide-activation-floating' ) .hide();
            $( '.the-guide-activation-selectors' ).hide();

        } else if ( $selectedActivationMethod.val() === 'floating' ) {
            $( '.the-guide-activation-selectors' ).hide();
            $( '.the-guide-activation-floating' ) .show();


        } else if ( $selectedActivationMethod.val() === 'on-click' ) {
            $( '.the-guide-activation-floating' ) .hide();
            $( '.the-guide-activation-selectors' ).css('display', 'flex');
        }
    });
    $selectActivationMethod.change();



    $selectControllerMethod.change( () => {
        const $selectedControllerPositionMethod = $( '#the-guide-select-controller-method option:selected' );

        if ( $selectedControllerPositionMethod.val() === 'next-to-the-selected-elem' ) {
            $( '.the-guide-controller-position-floating' ).hide();
        } else if ( $selectedControllerPositionMethod.val() === 'floating' ) {
            $( '.the-guide-controller-position-floating' ).show();
        }
    });
    $selectControllerMethod.change();
});

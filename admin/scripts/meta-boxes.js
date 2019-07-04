/**
 * Functionality for the tour meta boxes.
 *
 * @author Vladislav Luzan
 * @since 0.1.0
 */
'use strict';


jQuery( $ => {
    const $selectActivationMethod =  $( '#the-guide-select-activation-method' ),
          $selectControllerMethod =  $( '#the-guide-select-controller-method' );


    /**
     * When tour Activation Method is changed.
     *
     * @since 0.1.0
     *
     * @listens $selectActivationMethod:change
     *
     * @return {void}
     */
    $selectActivationMethod.change( () => {
        const $selectedActivationMethod = $( '#the-guide-select-activation-method option:selected' );

        if ( 'on-load' === $selectedActivationMethod.val() ) {
            $( '.the-guide-activation-floating' ) .hide();
            $( '.the-guide-activation-selectors' ).hide();

        } else if ( 'floating' === $selectedActivationMethod.val() ) {
            $( '.the-guide-activation-selectors' ).hide();
            $( '.the-guide-activation-floating' ) .show();


        } else if ( 'on-click' === $selectedActivationMethod.val() ) {
            $( '.the-guide-activation-floating' ) .hide();
            $( '.the-guide-activation-selectors' ).css('display', 'flex');
        }
    });


    /**
     * When tour Controller Position is changed.
     *
     * @since 0.1.0
     *
     * @listens $selectControllerMethod:change
     *
     * @return {void}
     */
    $selectControllerMethod.change( () => {
        const $selectedControllerPositionMethod = $( '#the-guide-select-controller-method option:selected' );

        if ( 'next-to-the-selected-elem' === $selectedControllerPositionMethod.val() ) {
            $( '.the-guide-controller-position-floating' ).hide();
        } else if ( 'floating' === $selectedControllerPositionMethod.val() ) {
            $( '.the-guide-controller-position-floating' ).show();
        }
    });
});

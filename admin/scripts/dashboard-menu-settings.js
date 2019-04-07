'use strict';

jQuery( $ => {
    const $form =                     $( '.the-guide-form' ),
          $selectTheTour =            $( '#the-guide-select-entity' ),
          $selectActivationMethod =   $( '#the-guide-select-activation-method' ),
          $selectControllerMethod =   $( '#the-guide-select-controller-position' ),
          $tourName =                 $( '#the-guide-tour-name' ),
          $tourUrl =                  $( '#the-guide-url' ),
          $stepsInSelectors =         $( '#the-guide-steps' ),
          $stepsContent =             $( '.the-guide-steps-content' ),

          allToursData = theGuide.postsData;



    function getOptionSelectedTour() {
        return $( '#the-guide-select-entity option:selected' );
    }



    function selectActivationMethod() {
        const $optionSelectedTour = getOptionSelectedTour();

        // Search by ID
        allToursData.forEach( theTour => {
            if ( $optionSelectedTour.val() === String( theTour.id ) ) {

                if ( theTour.activationMethodAndItsData.method )
                    $( 'option.the-guide-activation[value=' +
                        theTour.activationMethodAndItsData.method + ']' ).prop( 'selected', true );

                $selectActivationMethod.change();
            }
        });
    }



    function selectControllerMethod() {
        const $optionSelectedTour = getOptionSelectedTour();

        // Search by ID
        allToursData.forEach( theTour => {
            if ( $optionSelectedTour.val() === String( theTour.id ) ) {

                if ( theTour.controllerMethodAndItsData.method )
                    $( 'option.the-guide-controller[value=' +
                        theTour.controllerMethodAndItsData.method + ']' ).prop( 'selected', true );

                $selectControllerMethod.change();
            }
        });
    }



    function getActivationMethodAndItsData() {
        let obj = {
            'method': $selectActivationMethod.val(),
            'position': {},
        };

        obj.floatingText = $( '#the-guide-activation-data-floating-text' ).val();

        obj.position.top    = $( '#the-guide-activation-data-position-input-top' )   .val();
        obj.position.bottom = $( '#the-guide-activation-data-position-input-bottom' ).val();
        obj.position.left   = $( '#the-guide-activation-data-position-input-left' )  .val();
        obj.position.right  = $( '#the-guide-activation-data-position-input-right' ) .val();

        obj.selectors  = $( '#the-guide-activation-data-selectors-input' ).val().split(',');

        return obj;
    }



    function getControllerMethodAndItsData() {
        let obj = {
            'method': $selectControllerMethod.val(),
            'position': {},
        };

        obj.position.top    = $( '#the-guide-controller-position-input-top' )   .val();
        obj.position.bottom = $( '#the-guide-controller-position-input-bottom' ).val();
        obj.position.left   = $( '#the-guide-controller-position-input-left' )  .val();
        obj.position.right  = $( '#the-guide-controller-position-input-right' ) .val();

        return obj;
    }



    function clearEverythingTourActivation() {
        $( '#the-guide-activation-data-position-input-top' )   .val( '' );
        $( '#the-guide-activation-data-position-input-bottom' ).val( '' );
        $( '#the-guide-activation-data-position-input-left' )  .val( '' );
        $( '#the-guide-activation-data-position-input-right' ) .val( '' );

        $( '#the-guide-activation-data-selectors-input' ).val( '' );

        $( '.the-guide-activation-data-floating' ) .hide();
        $( '.the-guide-activation-data-selectors' ).hide();

        $( '#the-guide-select-activation-method option:selected' ).prop( 'selected', false );
    }



    function selectOptions() {

        // Removes all previous
        $selectTheTour.html('');

        // Appends "Add new tour" option
        $selectTheTour.append('' +
            '<option class="the-guide-add-new-entity" value="add-new-entity">' +
                'Add new' +
            '</option>' +
        '');
        // Appends all tours
        allToursData.forEach( theTour => {
            $selectTheTour.append(
                '<option class="the-guide-the-tour-option" value="' + theTour.id + '">' + theTour.name + '</option>'
            );
        });

        // Sorts options according to tours position
        if ( theGuide.positions ) {
            theGuide.positions.forEach( id => {
                const $theOption = $( '.the-guide-the-tour-option[value=' + id + ']' );

                $( '#the-guide-select-entity' ).append( $theOption );
            });
        }

        // Selects "Add new tour" option
        $( '.the-guide-add-new-entity' ).prop( 'selected', true );
    }



    function addSelectorsContentArea() {
        const $optionSelectedTour = getOptionSelectedTour();

        // Search by ID
        allToursData.forEach( theTour => {
            if ( $optionSelectedTour.val() === String( theTour.id ) ) {

                // Removes all previous
                $stepsContent.html('');

                // Adds content for all steps
                for ( let i = 0; i < theTour.steps.length; ++i ) {

                    let theSelector = theTour.steps[i];
                    let theContent;

                    // There will be no stepsContent in just created tour
                    // It can also be that the tour has content, but it doesn't have it with a certain index
                    // (when step just added)
                    if ( theTour.stepsContent && theTour.stepsContent[i] )
                        theContent = theTour.stepsContent[i];
                    else
                        theContent = '';

                    $stepsContent.append(
                        '<div class="the-guide-flex-container">' +
                            '<div class="the-guide-flex-title-content">' +
                                '<label for="the-guide-step-content-' + i + '">' +
                                    theSelector +
                                '</label>' +
                            '</div>' +
                            '<div class="the-guide-flex-input">' +
                                '<textarea class="the-guide-step-content" id="the-guide-step-content-'+ i +
                                '" rows ="5">' + theContent + '</textarea>' +
                            '</div>' +
                        '</div>'
                    );
                }
            } else if ( $optionSelectedTour.val() === 'add-new-entity' ) {
                $stepsContent.html('');
            }
        });
    }



    // When select option changes
    $selectTheTour.change( () => {
        const $optionSelectedTour = getOptionSelectedTour();


        clearEverythingTourActivation();

        selectActivationMethod();
        selectControllerMethod();

        addSelectorsContentArea();


        allToursData.forEach( theTour => {
            if ( $optionSelectedTour.val() === String( theTour.id ) ) {

                $tourName.        val( theTour.name );
                $tourUrl.         val( theTour.url );
                // Array into string implicitly conversion
                $stepsInSelectors.val( theTour.steps );

            } else if ( $optionSelectedTour.val() === 'add-new-entity' ) {

                $tourName.        val( '' );
                $tourUrl.         val( '' );
                $stepsInSelectors.val( '' );

            }
        });
    });



    $selectActivationMethod.change( () => {
        const $optionSelectedTour = getOptionSelectedTour(),
              $selectedActivationMethod = $( '#the-guide-select-activation-method option:selected' );

        // Search by ID
        allToursData.forEach( theTour => {
            if ( $optionSelectedTour.val() === String( theTour.id ) ) {
                const activationData = theTour.activationMethodAndItsData;

                if ( ! activationData.position )
                    activationData.position = '';
                if ( ! activationData.selectors )
                    activationData.selectors = '';

                if ( $selectedActivationMethod.val() === 'on-load' ) {

                    $( '.the-guide-activation-data-floating' ) .hide();
                    $( '.the-guide-activation-data-selectors' ).hide();

                } else if ( $selectedActivationMethod.val() === 'floating' ) {
                    $( '.the-guide-activation-data-selectors' ).hide();
                    $( '.the-guide-activation-data-floating' ) .show();

                    if ( activationData.floatingText )
                        $( '#the-guide-activation-data-floating-text' )            .val( activationData.floatingText );
                    if ( activationData.position ) {
                        if ( activationData.position.top )
                            $( '#the-guide-activation-data-position-input-top' )   .val( activationData.position.top );
                        if ( activationData.position.bottom )
                            $( '#the-guide-activation-data-position-input-bottom' ).val( activationData.position.bottom );
                        if ( activationData.position.left )
                            $( '#the-guide-activation-data-position-input-left' )  .val( activationData.position.left );
                        if ( activationData.position.right )
                            $( '#the-guide-activation-data-position-input-right' ) .val( activationData.position.right );
                    }
                } else if ( $selectedActivationMethod.val() === 'on-click' ) {
                    $( '.the-guide-activation-data-floating' ) .hide();
                    $( '.the-guide-activation-data-selectors' ).css('display', 'flex');

                    if ( activationData.selectors ) {
                        $( '#the-guide-activation-data-selectors-input' ).val( activationData.selectors );
                    }
                }
            }
        });
    });



    $selectControllerMethod.change( () => {
        const $optionSelectedTour = getOptionSelectedTour(),
              $selectedControllerPositionMethod = $( '#the-guide-select-controller-position option:selected' );

        // Search by ID
        allToursData.forEach( theTour => {
            if ( $optionSelectedTour.val() === String( theTour.id ) ) {
                const controllerPositionData = theTour.controllerMethodAndItsData;

                if ( $selectedControllerPositionMethod.val() === 'next-to-the-selected-elem' ) {
                    $( '.the-guide-controller-position-floating' ).hide();
                } else if ( $selectedControllerPositionMethod.val() === 'floating' ) {
                    $( '.the-guide-controller-position-floating' ).show();

                    if ( controllerPositionData.position ) {
                        if ( controllerPositionData.position.top )
                            $( '#the-guide-controller-position-input-top' )   .val( controllerPositionData.position.top );
                        if ( controllerPositionData.position.bottom )
                            $( '#the-guide-controller-position-input-bottom' ).val( controllerPositionData.position.bottom );
                        if ( controllerPositionData.position.left )
                            $( '#the-guide-controller-position-input-left' )  .val( controllerPositionData.position.left );
                        if ( controllerPositionData.position.right )
                            $( '#the-guide-controller-position-input-right' ) .val( controllerPositionData.position.right );
                    }
                }
            }
        });
    });



    $form.submit( event => {
        event.preventDefault();

        const $optionSelectedTour = getOptionSelectedTour();


        // Returns an array of steps content values
        function getStepsContent() {
            let stepsContent = [];
            $( '.the-guide-step-content' ).each(function() {
                stepsContent.push( $(this).val() );
            });
            return stepsContent;
        }


        let data = {
            'action':              'the_guide_settings_menu',
            'token':               theGuide.token,

            'select-entity':  $selectTheTour.   val(),
            'name':           $tourName.        val(),
            'url':            $tourUrl.         val(),
            'steps':          $stepsInSelectors.val().split(','),
            'stepsContent':   getStepsContent(),
            'activationMethodAndItsData': getActivationMethodAndItsData(),
            'controllerMethodAndItsData': getControllerMethodAndItsData(),

        };

        $.post( ajaxurl, data, newTourData => {
            // Adds a new item to the allToursData if user have added a new tour
            if ( $optionSelectedTour.val() === 'add-new-entity' ) {

                allToursData.unshift( newTourData );

                selectOptions();
                // Selects just created element
                $( 'option[value=' + allToursData[0].id + ']' ).prop( 'selected', true );
                // Updates values because 'selected' property doesn't trigger 'change' event
                $selectTheTour.change();
            }
        }, 'json');


        // If user modified an existing tour
        if ( $optionSelectedTour.val() !== 'add-new-entity' ) {

            // Search by ID
            allToursData.forEach( ( theTour, index, theArray ) => {
                if ( $optionSelectedTour.val() === String( theTour.id ) ) {

                    // Alters allToursData data
                    theArray[index].name         = $tourName.        val();
                    theArray[index].url          = $tourUrl.         val();
                    theArray[index].steps        = $stepsInSelectors.val().split(',');
                    theArray[index].stepsContent = getStepsContent();
                    theArray[index].activationMethodAndItsData = getActivationMethodAndItsData();
                    theArray[index].controllerMethodAndItsData = getControllerMethodAndItsData();
                }
            });

            // Alters option's text to the new tour name
            $( 'option[value=' + $optionSelectedTour.val() + ']' ).text( $tourName.val() );

            addSelectorsContentArea();
        }
    });



    selectOptions();
});

'use strict';

class TheGuide {

    constructor() {
        this._$shadow             = null;
        this._$targetModalWindow  = null;
        this._$targetButtonPrev   = null;
        this._$targetButtonNext   = null;
        this._$targetModalContent = null;
        this._$targetCurrentElem  = null;
        this._$selectedElem       = null;

        this._elemIndex     = parseInt( theGuide.theGuideData.elemIndex, 10 );
        this._showPrelude   = null;
        this._tourData      = null;
        this._observer      = null;
        this._filteredSteps = null;
        this._htmlAdded     = null;

        // API
        this.allTours     = theGuide.theGuideData.allEnabledToursForThisURL;
        this.isActive     = false;
        this.currentTour  = null;
        this.howManySteps = null;
        this.currentStep  = null;
        this.controllerMethod = null;
    }



    go( TourID, showPrelude ) {
        if ( ! this._htmlAdded ) {
            this._addHTML();
            this._$shadow = jQuery( '.the-guide-shadow' );

            this._htmlAdded = true;
        }

        if ( this.isActive === true ) {
            this.hide();
        }

        this.currentTour = parseInt( TourID, 10 );
        this._showPrelude = Boolean( showPrelude );


        // Receives the selected tour's data from the server and handles it
        let data = {
            'action': 'the_guide_public_get_tour_data_by_id',
            'token':  theGuide.theGuideData.nonceGetTourDataByID,

            'id':     this.currentTour,
        };

        return new Promise( resolve => {
            jQuery.post( theGuide.ajaxurl, data, tourData => {

                this._tourData = tourData;

                /*
                 * Filters out all steps, so the only existing elements on the page will be in the array
                 */
                this._filteredSteps = [];
                for ( let i = 0; i < this._tourData.steps.length; ++i ) {
                    if ( jQuery( this._tourData.steps[i] ).length ) {
                        this._filteredSteps.push( this._tourData.steps[i] );
                    }
                }

                /*
                 * Gets the last step from the local storage.
                 */
                let theGuideLocalStorage = localStorage.getItem('theGuide') ? JSON.parse( localStorage.getItem('theGuide') ) : {};
                if ( TourID in theGuideLocalStorage && theGuideLocalStorage[ TourID ] ) {
                    this.currentStep = theGuideLocalStorage[ TourID ];
                } else {
                    this.currentStep = this._elemIndex + 1;
                }

                this.howManySteps = this._filteredSteps.length;


                resolve(0);

            }, 'json');
        });
    }



    show() {
        /*
         * Shows shadow and modal window only if there are selected elements on the page
         */
        if ( this._filteredSteps.length ) {
            if ( this._showPrelude )
                this._useSelectedActivationMethod();
            else
                this._initEverything();
        }
    }



    _addHTML() {
        const html = `
<div class="the-guide-floating-block the-guide-hidden">
    <div class="the-guide-floating-block-container">
        <div class="the-guide-floating-block-content"></div>
        <div class="the-guide-floating-block-button-container">
            <input class="the-guide-floating-block-button" type="button" value="${theGuide.translates.start}" >
        </div>
    </div>
</div>


<div class="the-guide-modal the-guide-hidden">
    <div class="the-guide-modal-container">
        <div class="the-guide-modal-current-elem"></div>
        <div class="the-guide-modal-content"></div>
        <div class="the-guide-modal-buttons">
            <input id="the-guide-modal-button-prev" type="button" name="" value="${theGuide.translates.previous}">
            <input id="the-guide-modal-button-next" type="button" name="" value="${theGuide.translates.next}">
        </div>
    </div>
</div>


<div class="the-guide-modal-next-to-the-elem the-guide-hidden">
    <div class="the-guide-modal-next-to-the-elem-container">

        <div class="the-guide-modal-next-to-the-elem-button-prev-container">
            <input id="the-guide-modal-next-to-the-elem-button-prev" type="button" name="" value="${theGuide.translates.previous}">
        </div>

        <div class="the-guide-modal-next-to-the-elem-current-elem"></div>

        <div class="the-guide-modal-next-to-the-elem-content"></div>

        <div class="the-guide-modal-next-to-the-elem-button-next-container">
            <input id="the-guide-modal-next-to-the-elem-button-next" type="button" name="" value="${theGuide.translates.next}">
        </div>

    </div>
</div>


<div class="the-guide-shadow the-guide-hidden"></div>
    `;

        jQuery( 'body' ).append( html );
    }


    _useSelectedActivationMethod() {
        const that = this;
        let activationData = this._tourData.activationMethodAndItsData;

        if ( activationData.method === 'on-load' ) {

            this._initEverything();

        } else if ( activationData.method === 'floating' ) {

            const $floatingBlock        = jQuery( '.the-guide-floating-block' ),
                  $floatingBlockContent = jQuery( '.the-guide-floating-block-content' );


            let obj = {};

            if ( activationData.position ) {
                if ( activationData.position.top ) {
                    obj.top = activationData.position.top;
                    obj.bottom = 'auto';
                }

                if ( activationData.position.bottom ) {
                    obj.bottom = activationData.position.bottom;
                    obj.top = 'auto';
                }

                if ( activationData.position.left ) {
                    obj.left = activationData.position.left;
                    obj.right = 'auto';
                }

                if ( activationData.position.right ) {
                    obj.right = activationData.position.right;
                    obj.left = 'auto';
                }
            }

            $floatingBlock.css( obj );


            // Prints content
            if ( activationData.floatingText )
                $floatingBlockContent.text( activationData.floatingText );


            $floatingBlock.draggable().show();


            jQuery( '.the-guide-floating-block-button' ).click( (event) => {
                event.stopPropagation();

                this._initEverything();

                $floatingBlock.hide();
            });


        } else if ( activationData.method === 'on-click' ) {
            function handleEvent( event ) {
                event.stopPropagation();
                event.preventDefault();

                // Unfocuses clicked element
                event.target.blur();

                // Removes all handlers when user clicks on 1 of the selected elements
                jQuery( '*' ).off( 'click', handleEvent );

                that._initEverything();
            }
            activationData.selectors.forEach( selector => {
                jQuery( selector ).on( 'click', event, handleEvent );
            });
        }
    }



    _initEverything() {
        this.isActive = true;

        this._initController();
        this._addListeners();
        this._setShadowAndScroll();
    }



    _initController() {
        const controllerMethodAndData = this._tourData.controllerMethodAndItsData;


        if ( controllerMethodAndData.method === 'floating' ) {

            this.controllerMethod = 'floating';

            this._$targetModalWindow  = jQuery( '.the-guide-modal' );
            this._$targetButtonPrev   = jQuery( '#the-guide-modal-button-prev' );
            this._$targetButtonNext   = jQuery( '#the-guide-modal-button-next' );
            this._$targetModalContent = jQuery( '.the-guide-modal-content' );
            this._$targetCurrentElem  = jQuery( '.the-guide-modal-current-elem' );


            let obj = {};

            if ( controllerMethodAndData.position ) {
                if ( controllerMethodAndData.position.top ) {
                    obj.top = controllerMethodAndData.position.top;
                    obj.bottom = 'auto';
                }

                if ( controllerMethodAndData.position.bottom ) {
                    obj.bottom = controllerMethodAndData.position.bottom;
                    obj.top = 'auto';
                }

                if ( controllerMethodAndData.position.left ) {
                    obj.left = controllerMethodAndData.position.left;
                    obj.right = 'auto';
                }

                if ( controllerMethodAndData.position.right ) {
                    obj.right = controllerMethodAndData.position.right;
                    obj.left = 'auto';
                }
            }

            this._$targetModalWindow.css( obj );


            this._$targetModalWindow.draggable().show();

        } else if ( controllerMethodAndData.method === 'next-to-the-selected-elem' ) {

            this.controllerMethod = 'next-to';

            this._$targetModalWindow  = jQuery( '.the-guide-modal-next-to-the-elem' );
            this._$targetButtonPrev   = jQuery( '#the-guide-modal-next-to-the-elem-button-prev' );
            this._$targetButtonNext   = jQuery( '#the-guide-modal-next-to-the-elem-button-next' );
            this._$targetModalContent = jQuery( '.the-guide-modal-next-to-the-elem-content' );
            this._$targetCurrentElem  = jQuery( '.the-guide-modal-next-to-the-elem-current-elem' );

            this._$targetModalWindow.show();
        }
    }



    _addListeners() {
        const that = this;


        this.handleButtonPrev = function() {
            that.goToTheStep( that.currentStep - 1 );
        };
        this._$targetButtonPrev.on( 'click', this.handleButtonPrev );

        this.handleButtonNext = function() {
            that.goToTheStep( that.currentStep + 1 );
        };
        this._$targetButtonNext.on( 'click', this.handleButtonNext );


        /*
         * Detects a click outside of the modal window
         */
        this.handleClickOutsideModal = function( event ) {
            if ( ! jQuery( event.target ).closest( that._$targetModalWindow ).length  ) {
                that.hide();
            }
        };
        jQuery(document).on( 'click', event, this.handleClickOutsideModal );


        /*
         * Keyboard
         */
        this.handleKeydown = function( event ) {
            // Arrow Left
            if ( event.which === 37 ) {
                that._$targetButtonPrev.click();
            // Arrow Right
            } else if ( event.which === 39 ) {
                that._$targetButtonNext.click();
            // Esc
            } else if ( event.which === 27 ) {
                that.hide();
            }
        };
        jQuery(document).on( 'keydown', event, this.handleKeydown );

    }



    _setShadowAndScroll() {
        if ( this.isActive ) {
            this._$selectedElem  = jQuery( this._filteredSteps[ this._elemIndex ] );


            const $elemToAnimate = jQuery( 'html, body' ),

                  that = this;

            let width  = this._$selectedElem.innerWidth(),
                height = this._$selectedElem.innerHeight(),
                offset = this._$selectedElem.offset(),

                elemOldState = 'w' + width + 'h' + height + 'ot' + offset.top + 'ol' + offset.left;


            function makePositioning() {
                // Shows and moves the elem with shadow to the selected elem
                that._$shadow.show().css({
                    width:  width,
                    height: height,
                    left:   offset.left,
                    top:    offset.top,
                });

                that._moveTheTourControllerToTheElement();

                $elemToAnimate.stop();
                // Scrolls to the selected element
                $elemToAnimate.animate( { scrollTop: offset.top - jQuery(window).height() / 2 } );
            }
            makePositioning();


            if ( ! this._observer ) {
                this._observer = new MutationObserver( mutations => {
                    width  = this._$selectedElem.innerWidth();
                    height = this._$selectedElem.innerHeight();
                    offset = this._$selectedElem.offset();

                    const elemCurrentState = 'w' + width + 'h' + height + 'ot' + offset.top + 'ol' + offset.left;

                    if( elemOldState !== elemCurrentState ) {
                        elemOldState = elemCurrentState;

                        makePositioning();
                    }
                });

                this._observer.observe( document.body, {
                    childList: true,
                    attributes: true,
                    characterData: true,
                    subtree: true,
                });
            }


            this._print();
        }
    }



    _moveTheTourControllerToTheElement() {

        if ( this.controllerMethod === 'next-to' ) {
            const $selectedElemOffset = this._$selectedElem.offset(),

                  OFFSET = 10;


            // TOP

            let heightOfTheModalWindow = this._$targetModalWindow.outerHeight(),
                heightOfTheSelectedElem = this._$selectedElem.outerHeight(),

                top  = $selectedElemOffset.top - heightOfTheModalWindow - OFFSET;

            // If there is not enough space for the modal window, then moves it on another side of the elem
            if ( top < heightOfTheModalWindow + OFFSET * 2 ) // Offset must be in both sides ( top and bottom of the modal window )
                top = $selectedElemOffset.top + heightOfTheSelectedElem + OFFSET;


            // LEFT

            // Move to the center of the elem
            let halfOfTheModalWindow = this._$targetModalWindow.outerWidth() / 2,
                halfOfTheSelectedElem = this._$selectedElem.outerWidth() / 2,

                left = $selectedElemOffset.left + halfOfTheSelectedElem - halfOfTheModalWindow,

                widthOfTheModalWindow = this._$targetModalWindow.outerWidth();


            if ( left < widthOfTheModalWindow )
                left = OFFSET;


            // SET UP TOP & LEFT

            this._$targetModalWindow.css( {
                top:  top,
                left: left,
            } );
        }
    }



    _print() {
        // Counter. E.g 3/10
        this._$targetCurrentElem.text( this.currentStep + '/' + this._filteredSteps.length );
        // Text
        this._$targetModalContent.text( this._tourData.stepsContent[ this._elemIndex ] );
    }



    hide() {
        this.isActive = false;

        this._observer.disconnect();
        this._observer = null;
        // Removes all listeners
        this._$targetButtonPrev.off( 'click', this.handleButtonPrev );
        this._$targetButtonNext.off( 'click', this.handleButtonNext );
        jQuery(document).off( 'click', this.handleClickOutsideModal );
        jQuery(document).off( 'keydown', this.handleKeydown );

        this._$shadow.css( { display: 'none' } );
        this._$targetModalWindow.css( { display: 'none' } );
    }


    getStepSelector( stepNumber ) {
        return this._tourData.steps[ stepNumber - 1 ];
    }


    getStepContent( stepNumber ) {
        return this._tourData.stepsContent[ stepNumber - 1 ];
    }


    setStepSelector( stepNumber, selector ) {
        this._filteredSteps[ stepNumber - 1 ] = selector;

        if  ( this.currentStep === stepNumber ) {
            this._setShadowAndScroll();
        }

        return 0;
    }


    setStepContent( stepNumber, content ) {
        this._tourData.stepsContent[ stepNumber - 1 ] = content;

        if  ( this.currentStep === stepNumber ) {
            this._print();
        }

        return 0;
    }


    goToTheStep( stepNumber ) {

        this._elemIndex = stepNumber - 1;
        this.currentStep = stepNumber;


        if ( this.currentStep < 1 ) {
            this._elemIndex = 0;
            this.currentStep = 1;
        }
        if ( this.currentStep === this.howManySteps - 1 ) {
            this._$targetButtonNext.val( theGuide.translates.next );
        }
        if ( this.currentStep === this.howManySteps ) {
            this._$targetButtonNext.val( theGuide.translates.finish );
        }

        if ( this.currentStep > this.howManySteps ) {
            /*
             * Saves the current tour ID to the local storage when finished.
             */
            let tourID = this.currentTour,

                theGuideLocalStorage = localStorage.getItem('theGuide') ? JSON.parse( localStorage.getItem('theGuide') ) : {},
                dataToAdd = { [tourID]: '-1' };

            localStorage.setItem( 'theGuide', JSON.stringify( Object.assign( theGuideLocalStorage, dataToAdd ) ) );


            this.hide();
            return 0;
        } else {
            /*
             * Saves the last step to the local storage.
             */
            let tourID = this.currentTour,

                theGuideLocalStorage = localStorage.getItem('theGuide') ? JSON.parse( localStorage.getItem('theGuide') ) : {},
                dataToAdd = { [tourID]: this.currentStep };

            localStorage.setItem( 'theGuide', JSON.stringify( Object.assign( theGuideLocalStorage, dataToAdd ) ) );
        }


        this._setShadowAndScroll();

        return 0;
    }
}



jQuery(() => {

    /*
     * Gets the init data
     */
    (async function getInitData() {
        const response = await new Promise( resolve => {
            let data = {
                'action': 'the_guide_public_init',
                'url':    window.location.href
            };
            jQuery.post( theGuide.ajaxurl, data, theGuideData => {
                if (theGuideData) {
                    // Populates the init data
                    theGuide.theGuideData = theGuideData;

                    resolve(0);
                }
            }, 'json');
        } );

        if ( response === 0 ) {
            /*
             * Runs the tour only if it has not been finished.
             */
            let tourID = theGuide.theGuideData.TourID,
                theGuideLocalStorage = localStorage.getItem('theGuide') ? JSON.parse( localStorage.getItem('theGuide') ) : {};

            if ( ! ( tourID in theGuideLocalStorage ) || theGuideLocalStorage[tourID] !== '-1' ) {
                window.TheGuideInst = new TheGuide();
                showTheTour( tourID );
                theGuide_loadCustomCSS();
            }
        }
    })();


    /*
     * Inits the tour
     */
    async function showTheTour( tourID ) {
        const response = await window.TheGuideInst.go( tourID, true );

        if ( response === 0 ) {
            window.TheGuideInst.show();
        }
    }
});

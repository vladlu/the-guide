/**
 * Contains TheGuide class.
 *
 * That does the main routine of the plugin on frontend.
 *
 * @author Vladislav Luzan
 * @since 0.1.0
 */
'use strict';


/**
 * Does the main routine of the plugin on frontend.
 *
 * Retrieves the steps of the tour from the server, inits the tour.
 *
 * @since 0.1.0
 *
 * @class
 *
 * @global
 */
class TheGuide {


    /**
     * Constructor.
     *
     * @since 0.1.0
     */
    constructor() {
        this._$shadow             = null;
        this._$targetModalWindow  = null;
        this._$targetButtonPrev   = null;
        this._$targetButtonNext   = null;
        this._$targetModalContent = null;
        this._$targetCurrentElem  = null;
        this._$selectedElem       = null;


        /**
         * Index of the current element of the tour (Equals to the "current step" - 1).
         *
         * @since 0.1.0
         * @private
         *
         * @type {number}
         */
        this._elemIndex = parseInt( theGuide.theGuideData.elemIndex, 10 );

        /**
         * Whether to show the prelude (like "start the tour" button) or start the tour immediately.
         *
         * @since 0.1.0
         * @private
         *
         * @type {null|boolean}
         */
        this._showPrelude = null;

        /**
         * Tour's data.
         *
         * @since 0.1.0
         * @private
         *
         * @type {null|object}
         * @property {array}  steps                      An array of tour's steps (CSS selectors).
         * @property {array}  stepsContent               Content of the steps.
         * @property {object} activationMethodAndItsData Tour's activation method with the settings.
         * @property {object} controllerMethodAndItsData Tour's controller method with the settings.
         */
        this._tourData = null;

        /**
         * Mutation Observer object.
         *
         * @since 0.1.0
         * @private
         *
         * @type {null|MutationObserver}
         */
        this._observer = null;

        /**
         * An array of tour's steps (CSS selectors) containing the elements that only exist on the page.
         *
         * @since 0.1.0
         * @private
         *
         * @type {null|array}
         */
        this._filteredSteps = null;

        /**
         * Whether the HTML with The Guide elements was added to the page.
         *
         * @since 0.1.0
         * @private
         *
         * @type {boolean}
         */
        this._htmlAdded = false;

        /**
         * Whether The Guide's custom CSS was added to the page.
         *
         * @since 0.1.0
         * @private
         *
         * @type {boolean}
         */
        this._customCSSAdded = false;

        /**
         * The last recorded position of the key element on the current step.
         *
         * Has format:
         *
         * 'w' + width + 'h' + height + 'ot' + offset.top + 'ol' + offset.left
         *
         * @since 0.1.0
         * @private
         *
         * @type {null|string}
         */
        this._elemOldPositions = null;


        // API.


        /**
         * An array of IDs of all enabled tours for this URL.
         *
         * @since 0.1.0
         *
         * @type {array}
         */
        this.allTours = theGuide.theGuideData.allEnabledToursForThisURL;

        /**
         * Whether the current tour is active.
         *
         * @since 0.1.0
         *
         * @type {boolean}
         */
        this.isActive = false;

        /**
         * The ID of the current tour.
         *
         * @since 0.1.0
         *
         * @type {null|number}
         */
        this.currentTour = null;

        /**
         * How many steps the current tour has.
         *
         * @since 0.1.0
         *
         * @type {null|number}
         */
        this.howManySteps = null;

        /**
         * What's the current step of the current tour.
         *
         * @since 0.1.0
         *
         * @type {null|number}
         */
        this.currentStep = null;

        /**
         * The controller method of the current tour.
         *
         * Can be either 'floating' or 'next-to'.
         *
         * @since 0.1.0
         *
         * @type {null|string}
         */
        this.controllerMethod = null;
    }


    /**
     * Go to the specific tour.
     *
     * Retrieves the data of the tour using AJAX and loads it; adds the content of the tour to the page.
     *
     * @since 0.1.0
     *
     * @param tourID      The ID of the tour.
     * @param showPrelude Whether to show the prelude (like "start the tour" button) or start the tour immediately.
     *
     * @return {Promise<any>}
     */
    go( tourID, showPrelude ) {
        this.currentTour = parseInt( tourID, 10 );
        this._showPrelude = Boolean( showPrelude );


        /*
         * Stops the current tour if it's active.
         */
        if ( true === this.isActive ) {
            this.stop();
        }


        /*
         * Receives the selected tour's data from the server and handles it.
         */
        let data = {
            'action': 'the_guide_public_get_tour_data_by_id',
            'nonceToken':  theGuide.theGuideData.nonceTokenGetTourDataByID,

            'id':     this.currentTour,
        };


        return new Promise( resolve => {
            /**
             * Receives the selected tour's data from the server and handles it.
             *
             * The data has the following properties:
             *
             * - steps                      (An array of tour's steps (CSS selectors)).
             * - stepsContent               (Content of the steps).
             * - activationMethodAndItsData (Tour's activation method with the settings).
             * - controllerMethodAndItsData (Tour's controller method with the settings).
             *
             * @since 0.1.0
             *
             * @param {object} tourData The data of the tour.
             */
            jQuery.post( theGuide.ajaxurl, data, tourData => {

                this._tourData = tourData;

                /*
                 * Filters out the steps so only elements that exist on the page will be in the array.
                 */
                this._filteredSteps = [];
                for ( let i = 0; i < this._tourData.steps.length; ++i ) {
                    if ( jQuery( this._tourData.steps[i] ).length ) {
                        this._filteredSteps.push( this._tourData.steps[i] );
                    }
                }

                /*
                 * Retrieves the last step from the local storage.
                 */
                let theGuideLocalStorage = localStorage.getItem('theGuide') ? JSON.parse( localStorage.getItem('theGuide') ) : {};
                if ( tourID in theGuideLocalStorage && theGuideLocalStorage[ tourID ] ) {
                    this.currentStep = theGuideLocalStorage[ tourID ];
                } else {
                    this.currentStep = this._elemIndex + 1;
                }

                this.howManySteps = this._filteredSteps.length;


                resolve(0);

            }, 'json');
        });
    }


    /**
     * Inits the tour using previously retrieved data.
     *
     * @since 0.1.0
     *
     * @return {void}
     */
    show() {
        /*
         * Shows shadow and modal window only if there are selected elements on the page.
         */
        if ( this._filteredSteps.length ) {
            if ( this._showPrelude ) {
                this._initActivationMethod();
            } else {
                this._initEverything();
            }
        }
    }


    /**
     * Append HTML of the tour to the DOM.
     *
     * @since 0.1.0
     * @private
     *
     * @return {void}
     */
    _addHTML() {
        const html = `
<div id="the-guide">
    <div class="the-guide-floating-block" style="display: none">
        <div class="the-guide-floating-block-container">
            <div class="the-guide-floating-block-content"></div>
            <div class="the-guide-floating-block-button-container">
                <input class="the-guide-floating-block-button" type="button" value="${theGuide.translates.start}" >
            </div>
        </div>
    </div>
    
    
    <div id="the-guide-modal-floating" class="the-guide-modal" style="display: none">
        <div class="the-guide-modal-floating-container">
            <div class="the-guide-modal-floating-current-elem"></div>
            <div class="the-guide-modal-floating-content"></div>
            <div class="the-guide-modal-floating-buttons">
                <input id="the-guide-modal-floating-button-prev" type="button" name="" value="${theGuide.translates.previous}">
                <input id="the-guide-modal-floating-button-next" type="button" name="" value="${theGuide.translates.next}">
            </div>
        </div>
    </div>
    
    
    <div id="the-guide-modal-next-to-the-elem" class="the-guide-modal" style="display: none">
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
    
    
    <div class="the-guide-shadow" style="display: none"></div>
</div>    
`;

        jQuery( 'body' ).append( html );
    }


    /**
     * Inits the tour activation method.
     *
     * Uses the selected tour activation method.
     *
     * @since 0.1.0
     * @private
     *
     * @return {void}
     */
    _initActivationMethod() {
        const that = this;
        let activationData = this._tourData.activationMethodAndItsData;

        if ( 'on-load' === activationData.method ) {

            this._initEverything();

        } else if ( 'floating' === activationData.method ) {

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


            // Prints the content.
            if ( activationData.floatingText ) {
                $floatingBlockContent.text( activationData.floatingText );
            }


            $floatingBlock.draggable().show();

            /**
             * Inits the tour.
             *
             * @since 0.1.0
             *
             * @param {event} event The event object.
             */
            function eventHandlerFloating( event ) {
                event.stopPropagation();

                $floatingBlock.hide();
                jQuery( '*' ).off( 'click', eventHandlerFloating );

                that._initEverything();
            }
            jQuery( '.the-guide-floating-block-button' ).on( 'click', eventHandlerFloating );

        } else if ( 'on-click' === activationData.method ) {

            /**
             * Inits the tour.
             *
             * @since 0.1.0
             *
             * @param {event} event The event object.
             */
            function eventHandlerOnClick( event ) {
                event.stopPropagation();
                event.preventDefault();

                // Defocuses the clicked element.
                event.target.blur();

                // Removes all handlers when user clicks on one of the elements.
                jQuery( '*' ).off( 'click', eventHandlerOnClick );

                that._initEverything();
            }
            activationData.selectors.forEach( selector => {
                jQuery( selector ).on( 'click', eventHandlerOnClick );
            });
        }
    }


    /**
     * Inits everything.
     *
     * Does the main routine initializing the tour.
     *
     * @since 0.1.0
     * @private
     *
     * @return {void}
     */
    _initEverything() {

        /*
         * Adds HTML.
         */
        if ( ! this._htmlAdded ) {
            this._addHTML();
            this._$shadow = jQuery( '.the-guide-shadow' );

            this._htmlAdded = true;
        }

        /*
         * Adds custom CSS.
         */
        if ( ! this._customCSSAdded ) {
            this._addCustomCSS();

            this._customCSSAdded = true;
        }
        
        this.isActive = true;

        this._initController();
        this._addListeners();
        this.goToTheStep( this.currentStep );
    }


    /**
     * Inits the tour controller.
     *
     * Uses the selected tour controller.
     *
     * @since 0.1.0
     * @private
     *
     * @return {void}
     */
    _initController() {
        const controllerMethodAndData = this._tourData.controllerMethodAndItsData;


        if ( 'floating' === controllerMethodAndData.method ) {

            this.controllerMethod = 'floating';

            this._$targetModalWindow  = jQuery( '#the-guide-modal-floating' );
            this._$targetButtonPrev   = jQuery( '#the-guide-modal-floating-button-prev' );
            this._$targetButtonNext   = jQuery( '#the-guide-modal-floating-button-next' );
            this._$targetModalContent = jQuery( '.the-guide-modal-floating-content' );
            this._$targetCurrentElem  = jQuery( '.the-guide-modal-floating-current-elem' );


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

        } else if ( 'next-to-the-selected-elem' === controllerMethodAndData.method ) {

            this.controllerMethod = 'next-to';

            this._$targetModalWindow  = jQuery( '#the-guide-modal-next-to-the-elem' );
            this._$targetButtonPrev   = jQuery( '#the-guide-modal-next-to-the-elem-button-prev' );
            this._$targetButtonNext   = jQuery( '#the-guide-modal-next-to-the-elem-button-next' );
            this._$targetModalContent = jQuery( '.the-guide-modal-next-to-the-elem-content' );
            this._$targetCurrentElem  = jQuery( '.the-guide-modal-next-to-the-elem-current-elem' );

            this._$targetModalWindow.show();
        }
    }


    /**
     * Adds listeners for events with handlers.
     *
     * @since 0.1.0
     * @private
     *
     * @listens this~_$targetButtonPrev:click
     * @listens this~_$targetButtonNext:click
     * @listens document:click
     * @listens document:keydown
     * @listens window:resize
     *
     * @return {void}
     */
    _addListeners() {
        const that = this;


        /**
         * Handles a click on the "Previous" button.
         *
         * @since 0.1.0
         *
         * @return {void}
         */
        this.handleButtonPrev = function() {
            that.goToTheStep( that.currentStep - 1 );
        };
        this._$targetButtonPrev.on( 'click', this.handleButtonPrev );


        /**
         * Handles a click on the "Next" button.
         *
         * @since 0.1.0
         *
         * @return {void}
         */
        this.handleButtonNext = function() {
            that.goToTheStep( that.currentStep + 1 );
        };
        this._$targetButtonNext.on( 'click', this.handleButtonNext );


        /**
         * Detects a click outside of the modal window.
         *
         * @since 0.1.0
         *
         * @param {Event} event The event object.
         *
         * @return {void}
         */
        this.handleClickOutsideModal = function( event ) {
            if ( ! jQuery( event.target ).closest( that._$targetModalWindow ).length  ) {
                that.stop();
            }
        };
        jQuery(document).on( 'click', this.handleClickOutsideModal );


        /**
         * Handles keyboard events.
         *
         * @param {event} event The event object.
         *
         * @return {void}
         */
        this.handleKeydown = function( event ) {
            // Arrow Left
            if ( 37 === event.which ) {
                that._$targetButtonPrev.click();
            // Arrow Right
            } else if ( 39 === event.which ) {
                that._$targetButtonNext.click();
            // Esc
            } else if ( 27 === event.which ) {
                that.stop();
            }
        };
        jQuery(document).on( 'keydown', this.handleKeydown );


        /**
         * On resize.
         *
         * @since 0.1.0
         *
         * @return {void}
         */
        let timeoutID;
        this.handleResize = function() {
            clearTimeout( timeoutID );
            timeoutID = setTimeout( that._reposIfElemPosChanged.bind( that ), 500 );
        };
        jQuery( window ).on( 'resize', this.handleResize );


        /**
         * MutationObserver.
         *
         * When the content of the document changes.
         *
         * @since 0.1.0
         */
        if ( ! this._observer ) {
            this._observer = new MutationObserver( mutations => {
                this._reposIfElemPosChanged();
            });

            this._observer.observe( document.body, {
                childList: true,
                attributes: true,
                characterData: true,
                subtree: true,
            });
        }
    }


    /**
     * Removes all listeners.
     *
     * @since 0.1.0
     * @private
     *
     * @see this._addListeners
     *
     * @return {void}
     */
    _removeListeners() {

        /*
         * Removes all listeners.
         */
        this._$targetButtonPrev.off( 'click',   this.handleButtonPrev );
        this._$targetButtonNext.off( 'click',   this.handleButtonNext );
        jQuery( document )     .off( 'click',   this.handleClickOutsideModal );
        jQuery( document )     .off( 'keydown', this.handleKeydown );
        jQuery( window )       .off( 'resize',  this.handleResize );

        /*
         * Disables MutationObserver.
         */
        this._observer.disconnect();
        this._observer = null;
    }


    /**
     * Sets a shadow around the element and scrolls to it.
     * 
     * @since 0.1.0
     * @private
     *
     * @return {void}
     */
    _setShadowAndScroll() {
        if ( this.isActive ) {
            this._$selectedElem  = jQuery( this._filteredSteps[ this._elemIndex ] );
            let width  = this._$selectedElem.innerWidth(),
                height = this._$selectedElem.innerHeight(),
                offset = this._$selectedElem.offset();

            this._elemOldPositions = 'w' + width + 'h' + height + 'ot' + offset.top + 'ol' + offset.left;

            this._reposition();
            this._print();
        }
    }


    /**
     * Repositions if the current tour element position has been changed.
     *
     * Repositions tour controller, shadow around the element and scrolls to the element if the position of the element
     * has been changed.
     * 
     * @since 0.1.0
     * @private
     *
     * @return {void}
     */
    _reposIfElemPosChanged() {
        let width  = this._$selectedElem.innerWidth(),
            height = this._$selectedElem.innerHeight(),
            offset = this._$selectedElem.offset();
    
        const elemCurrentPositions = 'w' + width + 'h' + height + 'ot' + offset.top + 'ol' + offset.left;
    
        if( this._elemOldPositions !== elemCurrentPositions ) {
            this._elemOldPositions = elemCurrentPositions;
        
            this._reposition();
        }
    }


    /**
     * Repositions.
     *
     * Repositions tour controller, shadow around the element and scrolls to the element.
     *
     * @since 0.1.0
     * @private
     *
     * @return {void}
     */
    _reposition() {
        const $elemToAnimate = jQuery( 'html, body' );
        let width  = this._$selectedElem.innerWidth(),
            height = this._$selectedElem.innerHeight(),
            offset = this._$selectedElem.offset();
        
        /*
         * Sets the shadow.
         *
         * Shows and moves the elem with shadow to the selected elem.
         */
        this._$shadow.show().css({
            width:  width,
            height: height,
            left:   offset.left,
            top:    offset.top,
        });

        /*
         * Moves tour controller to the element.
         */
        if ( 'next-to' === this.controllerMethod ) {
            // Resets modal window width to the default.
            this._$targetModalWindow.width('');

            this._moveTheTourControllerToTheElement();
        }

        /*
         * Scrolls to the selected element.
         */
        $elemToAnimate.stop();
        $elemToAnimate.animate( { scrollTop: offset.top - jQuery(window).height() / 2 } );
    }


    /**
     * Moves the tour controller to the element.
     *
     * @since 0.1.0
     * @private
     *
     * @return {void}
     */
    _moveTheTourControllerToTheElement() {
        const that = this,
              $selectedElemOffset = this._$selectedElem.offset(),

              OFFSET = 10;  // The distance between the selected elem and the modal window.


        /*
         * Top.
         */


        let heightOfTheModalWindow  = this._$targetModalWindow.outerHeight(),
            heightOfTheSelectedElem = this._$selectedElem.outerHeight(),

            top = $selectedElemOffset.top - heightOfTheModalWindow - OFFSET;


        // If there is not enough space for the modal window, then moves it to another side of the elem.
        if ( top < ( heightOfTheModalWindow + OFFSET * 2 ) ) { // Offset must be on the both sides (top and bottom) of the modal window.
            top = $selectedElemOffset.top + heightOfTheSelectedElem + OFFSET;
        }


        /*
         * Left.
         */


        let windowWidth = jQuery( window ).width(),

            widthOfTheModalWindow      = this._$targetModalWindow.outerWidth(),
            widthOfTheSelectedElem     = this._$selectedElem.outerWidth(),

            halfWidthOfTheModalWindow  = widthOfTheModalWindow  / 2,
            halfWidthOfTheSelectedElem = widthOfTheSelectedElem / 2,

            left = $selectedElemOffset.left + halfWidthOfTheSelectedElem - halfWidthOfTheModalWindow; // Moves to the center of the elem.


        /**
         * Determines whether the modal window fits the document on the left side.
         *
         * @since 0.1.0
         *
         * @return {boolean}
         */
        function doesModalFitDocOnLeft() {
            return ( left > OFFSET );
        }


        /**
         * Determines whether the modal window fits the document on the right side.
         *
         * @since 0.1.0
         *
         * @return {boolean}
         */
        function doesModalFitDocOnRight() {
            widthOfTheModalWindow = that._$targetModalWindow.outerWidth();

            return ( ( left + widthOfTheModalWindow + OFFSET ) < windowWidth );
        }


        /**
         * Returns a center modal window position relative the document (so the modal window will be at the center of the document).
         *
         * @since 0.1.0
         *
         * @return {number} A new "left" position that will center a modal window relative to the document.
         */
        function returnModalWindowCenterPosition() {
            halfWidthOfTheModalWindow = that._$targetModalWindow.outerWidth() / 2;

            return ( windowWidth / 2 ) - halfWidthOfTheModalWindow;
        }


        if ( ! doesModalFitDocOnRight() ) {

            // Moves the right side of the modal window to the right side of the elem.
            left = $selectedElemOffset.left - ( widthOfTheModalWindow - widthOfTheSelectedElem );

            if ( ! doesModalFitDocOnLeft() ) {
                const STEP = 5,
                      MIN_MODAL_WIDTH = 305; // The minimum width of the modal width. If it less then it becomes corrupted.

                left = returnModalWindowCenterPosition();

                // Gradually decreases the width of the modal window until it fits the both sides of the document or reaches its limits.
                while (
                    ( ! doesModalFitDocOnLeft() || ! doesModalFitDocOnRight() ) &&
                    this._$targetModalWindow.width() - STEP > MIN_MODAL_WIDTH // A limit.
                ) {
                    // Decreases the width by the step size.
                    this._$targetModalWindow.width( this._$targetModalWindow.width() - STEP );

                    left = returnModalWindowCenterPosition();
                }
            }
        }


        /*
         * Setups the positions.
         */

        this._$targetModalWindow.css( {
            top:  top,
            left: left,
        } );
    }


    /**
     * Prints the content on the controller.
     *
     * @since 0.1.0
     * @private
     *
     * @return {void}
     */
    _print() {
        // Counter. E.g "3/10".
        this._$targetCurrentElem.text( this.currentStep + '/' + this._filteredSteps.length );
        // Text.
        this._$targetModalContent.text( this._tourData.stepsContent[ this._elemIndex ] );
    }


    /**
     * Adds custom CSS.
     *
     * Retrieves custom CSS from the server using AJAX and inserts it to the DOM.
     *
     * @since 0.1.0
     *
     * @return {void}
     */
     _addCustomCSS() {
        let data = {
            'action':     'the_guide_public_get_custom_css',
            'nonceToken': theGuide.theGuideData.nonceTokenGetCustomCSS
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
                let css       = document.createElement("style");
                css.innerHTML = customCSS;
                document.body.appendChild(css);
            }
        });
    }


    /**
     * Stops the tour.
     *
     * Hides the tour elements and disables all listeners.
     *
     * @since 0.1.0
     *
     * @return {void}
     */
    stop() {
        this.isActive = false;

        /*
         * Hides the tour elements.
         */
        this._$shadow.hide();

        this._$targetModalWindow.hide();
        this._$targetModalWindow = null;

        /*
         * Disables listeners.
         */
        this._removeListeners();
    }


    /**
     * Return the selector of the step.
     *
     * @since 0.1.0
     *
     * @param  {number} stepNumber The number of the step to get a selector.
     *
     * @return {string} Selector of the step.
     */
    getStepSelector( stepNumber ) {
        return this._tourData.steps[ stepNumber - 1 ];
    }


    /**
     * Returns the content of the step.
     *
     * @since 0.1.0
     *
     * @param  {number} stepNumber The number of the step to get a content.
     *
     * @return {string} Content of the step.
     */
    getStepContent( stepNumber ) {
        return this._tourData.stepsContent[ stepNumber - 1 ];
    }


    /**
     * Sets the selector of the step.
     *
     * @since 0.1.0
     *
     * @param {number} stepNumber The number of the step to set a selector for.
     * @param {string} selector   A selector to set for the step.
     *
     * @return {number} Exit status code.
     */
    setStepSelector( stepNumber, selector ) {
        this._filteredSteps[ stepNumber - 1 ] = selector;

        if  ( stepNumber === this.currentStep ) {
            this._setShadowAndScroll();
        }

        return 0;
    }


    /**
     * Sets the content of the step.
     *
     * @since 0.1.0
     *
     * @param {number} stepNumber The number of the step to set a content for.
     * @param {string} content    A content to set for the step.
     *
     * @return {number} Exit status code.
     */
    setStepContent( stepNumber, content ) {
        this._tourData.stepsContent[ stepNumber - 1 ] = content;

        if  ( stepNumber === this.currentStep ) {
            this._print();
        }

        return 0;
    }


    /**
     * Go to the specific step of the active tour.
     *
     * @since 0.1.0
     *
     * @param {number} stepNumber The number of the step to go to.
     *
     * @return {number} Exit status code.
     */
    goToTheStep( stepNumber ) {

        this._elemIndex  = stepNumber - 1;
        this.currentStep = stepNumber;


        if ( this.currentStep < 1 ) {
            this._elemIndex  = 0;
            this.currentStep = 1;
        }
        if ( this.howManySteps - 1 === this.currentStep ) {
            this._$targetButtonNext.val( theGuide.translates.next );
        }
        if ( this.howManySteps === this.currentStep ) {
            this._$targetButtonNext.val( theGuide.translates.finish );
        }

        if ( this.currentStep > this.howManySteps ) {
            /*
             * Saves the finished tour's ID to the local storage to not run it twice.
             */
            let tourID = this.currentTour,

                theGuideLocalStorage = localStorage.getItem('theGuide') ? JSON.parse( localStorage.getItem('theGuide') ) : {},
                dataToAdd = { [tourID]: '-1' };

            localStorage.setItem( 'theGuide', JSON.stringify( Object.assign( theGuideLocalStorage, dataToAdd ) ) );


            this.stop();
        } else {
            /*
             * Saves the last step of the tour to the local storage.
             */
            let tourID = this.currentTour,

                theGuideLocalStorage = localStorage.getItem('theGuide') ? JSON.parse( localStorage.getItem('theGuide') ) : {},
                dataToAdd = { [tourID]: this.currentStep };

            localStorage.setItem( 'theGuide', JSON.stringify( Object.assign( theGuideLocalStorage, dataToAdd ) ) );


            this._setShadowAndScroll();
        }

        return 0;
    }
}



jQuery(() => {

    /**
     * Tour initialization.
     *
     * Retrieves the ID of the tour for the current URL from the servers.
     * And runs the tour if it hasn't been finished.
     *
     * @since 0.1.0
     *
     * @return {void}
     */
    (async function getInitData() {
        const response = await new Promise( resolve => {
            let data = {
                'action': 'the_guide_public_init',
                'url':    window.location.href
            };

            /**
             * Receives the init data from the server and handles it.
             *
             * The data has the following properties:
             *
             * - allEnabledToursForThisURL (The list of IDs of all enabled tours for this URL (but with lower priority
             *                              than the current one (tourID parameter)).
             * - tourID                    (The ID of the tour to initialize).
             * - elemIndex                 (Index to start the tour from (Equals to: step - 1)).
             * - nonceTokenGetTourDataByID (AJAX nonce token to get the tour data by its ID).
             * - nonceTokenGetCustomCSS    (AJAX nonce token to get custom CSS).
             *
             * @since 0.1.0
             *
             * @param {object} tourData The data of the tour.
             */
            jQuery.post( theGuide.ajaxurl, data, theGuideData => {
                if ( theGuideData ) {
                    // Populates the init data.
                    theGuide.theGuideData = theGuideData;

                    resolve(0);
                }
            }, 'json');
        } );

        if ( 0 === response ) {
            /*
             * Runs the tour only if it has not been finished.
             */
            let tourID = theGuide.theGuideData.tourID,
                theGuideLocalStorage = localStorage.getItem('theGuide') ? JSON.parse( localStorage.getItem('theGuide') ) : {};

            if ( '-1' !== ! ( tourID in theGuideLocalStorage ) || theGuideLocalStorage[tourID] ) {
                /**
                 * Main The Guide instance.
                 *
                 * @global
                 *
                 * @type {TheGuide}
                 */
                window.TheGuideInstance = new TheGuide();
                showTheTour( tourID );
            }
        }
    })();


    /**
     * Inits a tour by its ID.
     *
     * @since 0.1.0
     *
     * @param {number} tourID The ID of the tour to init.
     *
     * @return {Promise<void>}
     */
    async function showTheTour( tourID ) {
        const response = await window.TheGuideInstance.go( tourID, true );

        if ( 0 === response ) {
            window.TheGuideInstance.show();
        }
    }
});

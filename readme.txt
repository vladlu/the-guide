
Data Structure



  PHP format                                  JS format                      Parent                 Type


  post_title                                  name                           Settings               Post Data
  the-guide-url                               url                            Settings               Post Meta
  the-guide-steps                             steps                          Settings               Post Meta
  the-guide-steps-content                     stepsContent                   Settings               Post Meta
  the-guide-controller-method-and-its-data    controllerMethodAndItsData     Settings               Post Meta

                                        [ method ]
                                            - next-to-the-selected-elem
                                            - floating
                                                [ position ]
                                                    [ top ]
                                                    [ bottom ]
                                                    [ left ]
                                                    [ right ]

  the-guide-activation-method-and-its-data    activationMethodAndItsData     Settings               Post Meta

                                        [ method ]
                                            - on-load
                                            - floating
                                                [ floatingText ]
                                                [ position ]
                                                    [ top ]
                                                    [ bottom ]
                                                    [ left ]
                                                    [ right ]
                                            - on-click
                                                [ selectors ]

  the-guide-who-watched                                                      Public                 Post Meta
  the-guide-is-enabled                        enabledTours                   Settings|Controller    Post Meta
  positions                                   positions                      Controller             Option (the-guide-settings)
  custom-css                                  customCSS                      Customize              Option (the-guide-settings)

___________________________________________________________________________________________________________


        PHP format: this format is used within the server.


        JS format:  this format is used in SERVER <=> CLIENT communication:

            SERVER => CLIENT: wp_localize_script()
            CLIENT => SERVER: AJAX


========================================================================================================================

JS public API



TheGuideInst    <---- YOU SHOULD USE THIS

    Example:
        TheGuideInst.allTours;
        TheGuideInst.go( TheGuideInst.allTours[1] );
        TheGuideInst.show();



   Properties:                Returns(type)                 Description   <---- DON'T CHANGE ANY OF THESE PROPERTIES !!!
___________________________________________________________________________________________________________
Before "go":
   .allTours            |     array of integers    | All enabled tours for this url

After "go":
   .currentTour         |          int             | ID of the current tour
   .howManySteps        |          int             | Number of steps for the current tour
   .currentStep         |          int             | Number of the current step

After "show":
   .controllerMethod    |         string           | The type of the tour controller: "next-to" or "floating"
   .isActive            |          bool            | Is the current tour active or hidden?
___________________________________________________________________________________________________________


    Methods                     Arguments                           Description                              Returns
_____________________________________________________________________________________________________________________________
    .go             ( (int)TourID, (bool)showPrelude ) | Select the tour to work with           | "Promise" which should return 0 [1]
    .show()                                            | Activate(show) the selected tour       | -
    .hide()                                            | Hide the selected tour                 | -
    .getStepSelector( (int)stepNumber )                | Get the selector of the tour by number |            string
    .getStepContent ( (int)stepNumber )                | Get the content of the tour by number  |            string
    .setStepSelector( (int)stepNumber, (str)selector ) | Set the selector of the tour by number |       0 when finished
    .setStepContent ( (int)stepNumber, (str)content )  | Set the content of the tour by number  |       0 when finished
    .goToTheStep    ( (int)stepNumber )                | Go to the specified step               |       0 when finished
_____________________________________________________________________________________________________________________________

[1] Use async/await. Example:


async function showTheTour(tourID) {
    const response = await theGuideInst.go( tourID, true );

    if ( response === 0 ) {
        theGuideInst.show();
    }
}
showTheTour( theGuideInst.allTours[0] );

========================================================================================================================

Shortcodes


[the-guide-launch id=”tour_id” step=”tour_step_number” ] - launch a new tour. has higher priority than [the-guide-go]
[the-guide-go step=”tour_step_number”] - go to the step of the current tour

========================================================================================================================

Restrictions


* Shortcodes work only on posts and pages
* 1 URL = 1 Tour
* 1 Tour = 1 URL
* ????(future changes) All tours are publicly available, because the user can set HTTP_HOST and REQUEST_URI to any value he wants.

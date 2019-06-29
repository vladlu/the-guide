========================================================================================================================
                                                    DATA STRUCTURE
========================================================================================================================


  PHP format                                  JS format                          Type                           Description

  post_title                                  name                               Post Data                      Tour title.
  the-guide-is-enabled                        enabledTours                       Post Meta                      Whether the tour is enabled.
  the-guide-url                               url                                Post Meta                      A URL on which the tour is intended to work.
  the-guide-steps                             steps                              Post Meta                      An array of tour's steps (CSS selectors).
  the-guide-steps-content                     stepsContent                       Post Meta                      Content of the steps.
  the-guide-activation-method-and-its-data    activationMethodAndItsData         Post Meta                      Tour's activation method with the settings.

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

  the-guide-controller-method-and-its-data    controllerMethodAndItsData         Post Meta                      Tour's controller method with the settings.

                                            [ method ]
                                        - next-to-the-selected-elem
                                        - floating
                                            [ position ]
                                                [ top ]
                                                [ bottom ]
                                                [ left ]
                                                [ right ]

  custom-css                                  customCSS                          Option (the-guide-settings)    Custom CSS that is applied to all tours.

___________________________________________________________________________________________________________

        PHP format: this format is used on the server.
        JS format:  this format is used for communication between the client and the server.

========================================================================================================================
                                                    JS PUBLIC API
========================================================================================================================



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
______________________________________________________________________________________________________________________________________
    .go             ( (int)tourID, (bool)showPrelude ) | Select the tour to work with.          | "Promise" which should return 0 [1]
                                                       |                                        |
                                                       | The second parameter specifies whether |
                                                       | to show the prelude (like "start the   |
                                                       | tour" button) or start the tour        |
                                                       | immediately.                           |
--------------------------------------------------------------------------------------------------------------------------------------
    .show()                                            | Activate(show) the selected tour       | -
--------------------------------------------------------------------------------------------------------------------------------------
    .hide()                                            | Hide the selected tour                 | -
--------------------------------------------------------------------------------------------------------------------------------------
    .getStepSelector( (int)stepNumber )                | Get the selector of the step           |               String
--------------------------------------------------------------------------------------------------------------------------------------
    .getStepContent ( (int)stepNumber )                | Get the content of the step            |               String
--------------------------------------------------------------------------------------------------------------------------------------
    .setStepSelector( (int)stepNumber, (str)selector ) | Set the selector of the step           |    0 when finished successfully
--------------------------------------------------------------------------------------------------------------------------------------
    .setStepContent ( (int)stepNumber, (str)content )  | Set the content of the step            |    0 when finished successfully
--------------------------------------------------------------------------------------------------------------------------------------
    .goToTheStep    ( (int)stepNumber )                | Go to the step                         |    0 when finished successfully
______________________________________________________________________________________________________________________________________

[1] Use async/await. Example:


async function showTheTour(tourID) {
    const response = await theGuideInst.go( tourID, true );

    if ( response === 0 ) {
        theGuideInst.show();
    }
}
showTheTour( theGuideInst.allTours[0] );

========================================================================================================================
                                                    SHORTCODES
========================================================================================================================


[the-guide-launch id=”tour_id” step=”tour_step_number” ] - launch a new tour. has higher priority than [the-guide-go]
[the-guide-go step=”tour_step_number”] - go to the step of the current tour


========================================================================================================================
                                                    ACTIONS
========================================================================================================================


the-guide_after_single_tour_ordering
the-guide_after_tour_ordering


========================================================================================================================
                                                    RESTRICTIONS
========================================================================================================================


* Shortcodes work only on posts and pages
* 1 URL = 1 Tour
* 1 Tour = 1 URL
* ????(future changes) All tours are publicly available, because the user can set HTTP_HOST and REQUEST_URI to any value he wants.

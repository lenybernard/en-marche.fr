@api
Feature:
  In order to see door-to-door campaigns
  As a non logged-in user
  I should be able to access API door-to-door campaigns

  Background:
    Given the following fixtures are loaded:
      | LoadAdherentData            |
      | LoadClientData              |
      | LoadScopeData               |
      | LoadDoorToDoorCampaignData  |

  Scenario Outline: As a non logged-in user I cannot get and manage door-to-door campaigns
    Given I send a "<method>" request to "<url>"
    Then the response status code should be 401
    Examples:
      | method  | url                                                                 |
      | GET     | /api/v3/door_to_door_campaigns/d0fa7f9c-e976-44ad-8a52-2a0a0d8acaf9 |
      | GET     | /api/v3/door_to_door_campaigns                                      |

  Scenario Outline: As a JeMarche App user I can not get not active door-to-door campaigns
    Given I am logged with "luciole1989@spambox.fr" via OAuth client "JeMarche App"
    When I send a "<method>" request to "<url>"
    Then the response status code should be 404
    Examples:
      | method  | url                                                                   |
      | GET     | /api/v3/door_to_door_campaigns/932d67d1-2da6-4695-82f6-42afc20f2e41   |
      | GET     | /api/v3/door_to_door_campaigns/9ba6b743-5018-4358-bdc0-eb2094010beb   |

  Scenario: As a logged-in user I can get active door-to-door campaigns
    Given I am logged with "luciole1989@spambox.fr" via OAuth client "JeMarche App"
    When I send a "GET" request to "/api/v3/door_to_door_campaigns"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    [
        {
            "title": "Campagne de 10 jours suivants",
            "brief": "**Campagne** de 10 jours suivants",
            "goal": 600,
            "finish_at": "@string@.isDateTime()"
        },
        {
            "title": "Campagne de 5 jours suivants",
            "brief": "**Campagne** de 5 jours suivants",
            "goal": 500,
            "finish_at": "@string@.isDateTime()"
        }
    ]
    """

  Scenario: As a logged-in user I can get all door-to-door campaigns
    Given I am logged with "deputy@en-marche-dev.fr" via OAuth client "Data-Corner"
    When I send a "GET" request to "/api/v3/door_to_door_campaigns?scope=door_to_door_national_manager"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    [
        {
            "title": "Campagne de 10 jours suivants",
            "brief": "**Campagne** de 10 jours suivants",
            "goal": 600,
            "finish_at": "@string@.isDateTime()"
        },
        {
            "title": "Campagne de 5 jours suivants",
            "brief": "**Campagne** de 5 jours suivants",
            "goal": 500,
            "finish_at": "@string@.isDateTime()"
        },
        {
            "title": "Campagne dans 10 jours",
            "brief": "### Campagne dans 10 jours",
            "goal": 400,
            "finish_at": "@string@.isDateTime()"
        },
        {
            "title": "Campagne dans 20 jours",
            "brief": "### Campagne dans 20 jours",
            "goal": 400,
            "finish_at": "@string@.isDateTime()"
        },
        {
            "title": "Campagne terminé",
            "brief": null,
            "goal": 100,
            "finish_at": "@string@.isDateTime()"
        }
    ]
    """

  Scenario: As a logged-in user I can get one door-to-door campaign
    Given I am logged with "jacques.picard@en-marche.fr" via OAuth client "JeMarche App"
    When I send a "GET" request to "/api/v3/door_to_door_campaigns/d0fa7f9c-e976-44ad-8a52-2a0a0d8acaf9"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    {
        "title": "Campagne de 10 jours suivants",
        "brief": "**Campagne** de 10 jours suivants",
        "goal": 600,
        "finish_at": "@string@.isDateTime()"
    }
    """

  Scenario: As a logged-in user I can get passed door-to-door campaign
    Given I am logged with "deputy@en-marche-dev.fr" via OAuth client "Data-Corner"
    When I send a "GET" request to "/api/v3/door_to_door_campaigns/9ba6b743-5018-4358-bdc0-eb2094010beb?scope=door_to_door_national_manager"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    {
        "title": "Campagne terminé",
        "brief": null,
        "goal": 100,
        "finish_at": "@string@.isDateTime()"
    }
    """

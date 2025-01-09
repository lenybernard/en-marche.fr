@api
@renaissance
Feature:
    As a logged-in user
    I should be able to retrieve magic link with redirections

    Scenario: As a logged-in user I can retrieve magic links based on a key
        Given I am logged with "carl999@example.fr" via OAuth client "JeMengage Mobile"
        When I send a "GET" request to "/api/v3/app-link/adhesion"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON should be equal to:
            """
            {
                "url": "http://test.renaissance.code/connexion-avec-un-lien-magique?user=carl999@example.fr&@string@&_target_path=%2Fadhesion",
                "expires_at": "@string@.isDateTime()"
            }
            """
        When I send a "GET" request to "/api/v3/app-link/donation?utm_source=app"
        Then the JSON should be equal to:
            """
            {
                "url": "http://test.renaissance.code/connexion-avec-un-lien-magique?user=carl999@example.fr&@string@&_target_path=%2Fdon",
                "expires_at": "@string@.isDateTime()"
            }
            """
        When I send a "GET" request to "/api/v3/app-link/donation?duration=-1"
        Then the JSON should be equal to:
            """
            {
                "url": "http://test.renaissance.code/connexion-avec-un-lien-magique?user=carl999@example.fr&@string@&_target_path=%2Fdon%3Fduration%3D-1",
                "expires_at": "@string@.isDateTime()"
            }
            """
        When I send a "GET" request to "/api/v3/app-link/cadre"
        Then the JSON should be equal to:
            """
            {
                "url": "http://test.renaissance.code/connexion-avec-un-lien-magique?user=carl999@example.fr&@string@&_target_path=%2Fcadre",
                "expires_at": "@string@.isDateTime()"
            }
            """

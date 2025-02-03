@api
@renaissance
Feature:
    In order to get and manipulate events
    As a client of different apps
    I should be able to access events API

    Scenario: As a logged-in Jemarche App user I can get events by department
        Given I am logged with "jacques.picard@en-marche.fr" via OAuth client "J'écoute" with scope "jemarche_app"
        And I send a "GET" request to "/api/v3/events?zone=75"
        Then the response status code should be 200
        And the JSON nodes should match:
            | metadata.total_items | 14 |
        When I send a "GET" request to "/api/v3/events?zone=77"
        Then the response status code should be 200
        And the JSON nodes should match:
            | metadata.total_items | 5 |

    Scenario: As a non logged-in user I can get scheduled and published event
        When I send a "GET" request to "/api/events/0e5f9f02-fa33-4c2c-a700-4235d752315b"
        Then the response status code should be 200

    Scenario: As a non logged-in user I cannot get not published event
        When I send a "GET" request to "/api/events/de7f027c-f6c3-439f-b1dd-bf2b110a0fb0"
        Then the response status code should be 404

    Scenario: As a non logged-in user I cannot get private event
        When I send a "GET" request to "/api/events/47e5a8bf-8be1-4c38-aae8-b41e6908a1b3"
        Then the response status code should be 200
        And the JSON should be equal to:
            """
            {
                "uuid": "@string@",
                "name": "Réunion de réflexion bellifontaine",
                "description": null,
                "time_zone": "Europe/Paris",
                "live_url": null,
                "slug": "@string@",
                "committee": null,
                "visibility": "private",
                "created_at": null,
                "national": false,
                "object_state": "partial",
                "begin_at": "@string@.isDateTime()",
                "finish_at": null,
                "organizer": {
                    "uuid": null,
                    "first_name": "Francis",
                    "last_name": "B",
                    "scope": null,
                    "role": null,
                    "instance": null,
                    "image_url": null,
                    "zone": null
                },
                "participants_count": null,
                "status": "SCHEDULED",
                "capacity": null,
                "post_address": {
                    "address": null,
                    "postal_code": "77300",
                    "city": null,
                    "city_name": "Fontainebleau",
                    "country": "FR",
                    "latitude": null,
                    "longitude": null
                },
                "category": {
                    "event_group_category": {
                        "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                        "name": "événement",
                        "slug": "evenement"
                    },
                    "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                    "name": "Réunion d'équipe",
                    "slug": "reunion-dequipe"
                },
                "visio_url": null,
                "national": false,
                "mode": "meeting",
                "local_begin_at": null,
                "local_finish_at": null,
                "editable": false,
                "image_url": null,
                "image": null
            }
            """

    Scenario: As a non logged-in user I can get events
        When I send a "GET" request to "/api/events"
        Then the response status code should be 200
        And the JSON nodes should match:
            | metadata.total_items | 30 |

    Scenario: As a logged-in user I can get events
        When I am logged as "jacques.picard@en-marche.fr"
        And I send a "GET" request to "/api/events"
        Then the response status code should be 200
        And the JSON nodes should match:
            | metadata.total_items | 30 |

    Scenario Outline: As a (delegated) referent I can get the list of events corresponding to my zones
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "GET" request to "/api/v3/events?scope=<scope>"
        Then the response status code should be 200
        And the JSON should be equal to:
            """
            {
                "metadata": {
                    "total_items": 14,
                    "items_per_page": 2,
                    "count": 2,
                    "current_page": 1,
                    "last_page": 7
                },
                "items": [
                    {
                        "uuid": "113876dd-87d2-426a-a12a-60ffd5107b10",
                        "name": "Grand Meeting de Marseille",
                        "time_zone": "Europe/Paris",
                        "slug": "@string@-grand-meeting-de-marseille",
                        "live_url": null,
                        "visibility": "public",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "2017-02-20T09:30:00+01:00",
                        "finish_at": "2017-02-20T19:00:00+01:00",
                        "organizer": {
                            "uuid": "a046adbe-9c7b-56a9-a676-6151a6785dda",
                            "first_name": "Jacques",
                            "last_name": "Picard",
                            "scope": null,
                            "role": null,
                            "instance": null,
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 1,
                        "status": "SCHEDULED",
                        "capacity": 2000,
                        "post_address": {
                            "address": "2 Place de la Major",
                            "postal_code": "13002",
                            "city": "13002-13202",
                            "city_name": "Marseille 2ème",
                            "country": "FR",
                            "latitude": 43.298492,
                            "longitude": 5.362377
                        },
                        "created_at": "@string@.isDateTime()",
                        "category": {
                            "event_group_category": {
                                "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                                "name": "événement",
                                "slug": "evenement"
                            },
                            "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                            "name": "Atelier du programme",
                            "slug": "atelier-du-programme"
                        },
                        "visio_url": null,
                        "editable": false,
                        "national": false,
                        "mode": null,
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "2017-02-20T19:00:00+01:00",
                        "image_url": null,
                        "image": null
                    },
                    {
                        "uuid": "67e75e81-ad27-4414-bb0b-9e0c6e12b275",
                        "name": "Événements à Fontainebleau 1",
                        "slug": "@string@-evenements-a-fontainebleau-1",
                        "time_zone": "Europe/Paris",
                        "live_url": null,
                        "visibility": "public",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "@string@.isDateTime()",
                        "finish_at": "@string@.isDateTime()",
                        "organizer": {
                            "uuid": "a9fc8d48-6f57-4d89-ae73-50b3f9b586f4",
                            "first_name": "Francis",
                            "last_name": "Brioul",
                            "scope": null,
                            "role": null,
                            "instance": null,
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 0,
                        "status": "SCHEDULED",
                        "capacity": 50,
                        "post_address": {
                            "address": "40 Rue Grande",
                            "postal_code": "77300",
                            "city": "77300-77186",
                            "city_name": "Fontainebleau",
                            "country": "FR",
                            "latitude": 48.404766,
                            "longitude": 2.698759
                        },
                        "created_at": "@string@.isDateTime()",
                        "category": {
                            "event_group_category": {
                                "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                                "name": "événement",
                                "slug": "evenement"
                            },
                            "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                            "name": "Atelier du programme",
                            "slug": "atelier-du-programme"
                        },
                        "visio_url": null,
                        "editable": false,
                        "national": false,
                        "mode": null,
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "@string@.isDateTime()",
                        "image_url": null,
                        "image": null
                    }
                ]
            }
            """

        Examples:
            | user                      | scope                                          |
            | referent@en-marche-dev.fr | president_departmental_assembly                |
            | senateur@en-marche-dev.fr | delegated_08f40730-d807-4975-8773-69d8fae1da74 |

    Scenario Outline: As a referent I can get an ordered list of events corresponding to my zones
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "GET" request to "/api/v3/events?scope=<scope>&page_size=3&order[beginAt]=asc"
        Then the response status code should be 200
        And the JSON should be equal to:
            """
            {
                "metadata": {
                    "total_items": 14,
                    "items_per_page": 3,
                    "count": 3,
                    "current_page": 1,
                    "last_page": 5
                },
                "items": [
                    {
                        "uuid": "113876dd-87d2-426a-a12a-60ffd5107b10",
                        "name": "Grand Meeting de Marseille",
                        "slug": "@string@-grand-meeting-de-marseille",
                        "time_zone": "Europe/Paris",
                        "live_url": null,
                        "visibility": "public",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "@string@.isDateTime()",
                        "finish_at": "@string@.isDateTime()",
                        "organizer": {
                            "uuid": "a046adbe-9c7b-56a9-a676-6151a6785dda",
                            "first_name": "Jacques",
                            "last_name": "Picard",
                            "scope": null,
                            "role": null,
                            "instance": null,
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 1,
                        "status": "SCHEDULED",
                        "capacity": 2000,
                        "post_address": {
                            "address": "2 Place de la Major",
                            "postal_code": "13002",
                            "city": "13002-13202",
                            "city_name": "Marseille 2ème",
                            "country": "FR",
                            "latitude": 43.298492,
                            "longitude": 5.362377
                        },
                        "created_at": "@string@.isDateTime()",
                        "category": {
                            "event_group_category": {
                                "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                                "name": "événement",
                                "slug": "evenement"
                            },
                            "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                            "name": "Atelier du programme",
                            "slug": "atelier-du-programme"
                        },
                        "visio_url": null,
                        "national": false,
                        "mode": null,
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "@string@.isDateTime()",
                        "image_url": null,
                        "image": null,
                        "editable": false
                    },
                    {
                        "uuid": "67e75e81-ad27-4414-bb0b-9e0c6e12b275",
                        "name": "Événements à Fontainebleau 1",
                        "slug": "@string@-evenements-a-fontainebleau-1",
                        "time_zone": "Europe/Paris",
                        "live_url": null,
                        "visibility": "public",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "@string@.isDateTime()",
                        "finish_at": "@string@.isDateTime()",
                        "organizer": {
                            "uuid": "a9fc8d48-6f57-4d89-ae73-50b3f9b586f4",
                            "first_name": "Francis",
                            "last_name": "Brioul",
                            "scope": null,
                            "role": null,
                            "instance": null,
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 0,
                        "status": "SCHEDULED",
                        "capacity": 50,
                        "post_address": {
                            "address": "40 Rue Grande",
                            "postal_code": "77300",
                            "city": "77300-77186",
                            "city_name": "Fontainebleau",
                            "country": "FR",
                            "latitude": 48.404766,
                            "longitude": 2.698759
                        },
                        "created_at": "@string@.isDateTime()",
                        "category": {
                            "event_group_category": {
                                "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                                "name": "événement",
                                "slug": "evenement"
                            },
                            "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                            "name": "Atelier du programme",
                            "slug": "atelier-du-programme"
                        },
                        "visio_url": null,
                        "national": false,
                        "mode": null,
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "@string@.isDateTime()",
                        "image_url": null,
                        "image": null,
                        "editable": false
                    },
                    {
                        "uuid": "65610a6c-5f18-4e9d-b4ab-0e96c0a52d9e",
                        "name": "Événements à Fontainebleau 2",
                        "slug": "@string@-evenements-a-fontainebleau-2",
                        "time_zone": "Europe/Paris",
                        "live_url": null,
                        "visibility": "public",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "@string@.isDateTime()",
                        "finish_at": "@string@.isDateTime()",
                        "organizer": {
                            "uuid": "a9fc8d48-6f57-4d89-ae73-50b3f9b586f4",
                            "first_name": "Francis",
                            "last_name": "Brioul",
                            "scope": null,
                            "role": null,
                            "instance": null,
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 0,
                        "status": "SCHEDULED",
                        "capacity": 50,
                        "post_address": {
                            "address": "40 Rue Grande",
                            "postal_code": "77300",
                            "city": "77300-77186",
                            "city_name": "Fontainebleau",
                            "country": "FR",
                            "latitude": 48.404766,
                            "longitude": 2.698759
                        },
                        "created_at": "@string@.isDateTime()",
                        "category": {
                            "event_group_category": {
                                "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                                "name": "événement",
                                "slug": "evenement"
                            },
                            "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                            "name": "Conférence-débat",
                            "slug": "conference-debat"
                        },
                        "visio_url": null,
                        "national": false,
                        "mode": null,
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "@string@.isDateTime()",
                        "image_url": null,
                        "image": null,
                        "editable": false
                    }
                ]
            }
            """
        When I send a "GET" request to "/api/v3/events?scope=<scope>&page_size=3&order[finishAt]=desc"
        Then the response status code should be 200
        And the JSON should be equal to:
            """
            {
                "metadata": {
                    "total_items": 14,
                    "items_per_page": 3,
                    "count": 3,
                    "current_page": 1,
                    "last_page": 5
                },
                "items": [
                    {
                        "uuid": "e770cda4-b215-4ea2-85e5-03fc3e4423e3",
                        "name": "Un événement de l'assemblée départementale",
                        "slug": "@string@-un-evenement-de-lassemblee-departementale",
                        "time_zone": "Europe/Paris",
                        "live_url": null,
                        "visibility": "public",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "@string@.isDateTime()",
                        "finish_at": "@string@.isDateTime()",
                        "organizer": {
                            "uuid": "b4219d47-3138-5efd-9762-2ef9f9495084",
                            "first_name": "Gisele",
                            "last_name": "Berthoux",
                            "scope": null,
                            "role": null,
                            "instance": null,
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 2,
                        "status": "SCHEDULED",
                        "capacity": 50,
                        "post_address": {
                            "address": "47 rue Martre",
                            "postal_code": "92110",
                            "city": "92110-92024",
                            "city_name": "Clichy",
                            "country": "FR",
                            "latitude": 48.9016,
                            "longitude": 2.305268
                        },
                        "created_at": "@string@.isDateTime()",
                        "category": {
                            "event_group_category": {
                                "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                                "name": "événement",
                                "slug": "evenement"
                            },
                            "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                            "name": "Convivialité",
                            "slug": "convivialite"
                        },
                        "visio_url": "https://parti-renaissance.fr",
                        "national": false,
                        "mode": "online",
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "@string@.isDateTime()",
                        "image_url": null,
                        "image": null,
                        "editable": false
                    },
                    {
                        "uuid": "2b7238f9-10ca-4a39-b8a4-ad7f438aa95f",
                        "name": "Nouvel événement online privé et électoral",
                        "slug": "@string@-nouvel-evenement-online-prive-et-electoral",
                        "time_zone": "Europe/Paris",
                        "live_url": null,
                        "visibility": "adherent",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "@string@.isDateTime()",
                        "finish_at": "@string@.isDateTime()",
                        "organizer": {
                            "uuid": "29461c49-2646-4d89-9c82-50b3f9b586f4",
                            "first_name": "Referent",
                            "last_name": "Referent",
                            "scope": null,
                            "role": null,
                            "instance": null,
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 0,
                        "status": "SCHEDULED",
                        "capacity": 50,
                        "post_address": {
                            "address": "40 Rue Grande",
                            "postal_code": "77300",
                            "city": "77300-77186",
                            "city_name": "Fontainebleau",
                            "country": "FR",
                            "latitude": 48.404766,
                            "longitude": 2.698759
                        },
                        "created_at": "@string@.isDateTime()",
                        "category": null,
                        "visio_url": null,
                        "national": false,
                        "mode": "online",
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "@string@.isDateTime()",
                        "image_url": null,
                        "image": null,
                        "editable": false
                    },
                    {
                        "uuid": "5cab27a7-dbb3-4347-9781-566dad1b9eb5",
                        "name": "Nouvel événement online",
                        "slug": "@string@-nouvel-evenement-online",
                        "time_zone": "Europe/Paris",
                        "live_url": null,
                        "visibility": "public",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "@string@.isDateTime()",
                        "finish_at": "@string@.isDateTime()",
                        "organizer": {
                            "uuid": "29461c49-2646-4d89-9c82-50b3f9b586f4",
                            "first_name": "Referent",
                            "last_name": "Referent",
                            "scope": null,
                            "role": "Président",
                            "instance": "Assemblée départementale",
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 0,
                        "status": "SCHEDULED",
                        "capacity": 50,
                        "post_address": {
                            "address": "47 rue Martre",
                            "postal_code": "92110",
                            "city": "92110-92024",
                            "city_name": "Clichy",
                            "country": "FR",
                            "latitude": 48.9016,
                            "longitude": 2.305268
                        },
                        "created_at": "@string@.isDateTime()",
                        "category": null,
                        "visio_url": null,
                        "national": false,
                        "mode": "online",
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "@string@.isDateTime()",
                        "image_url": null,
                        "image": null,
                        "editable": true,
                        "edit_link": "@string@.isUrl()"
                    }
                ]
            }
            """
        When I send a "GET" request to "/api/v3/events?scope=<scope>&page_size=3&order[finish_at]=asc"
        Then the response status code should be 200
        And the JSON should be equal to:
            """
            {
                "metadata": {
                    "total_items": 14,
                    "items_per_page": 3,
                    "count": 3,
                    "current_page": 1,
                    "last_page": 5
                },
                "items": [
                    {
                        "category": {
                            "event_group_category": {
                                "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                                "name": "événement",
                                "slug": "evenement"
                            },
                            "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                            "name": "Atelier du programme",
                            "slug": "atelier-du-programme"
                        },
                        "uuid": "113876dd-87d2-426a-a12a-60ffd5107b10",
                        "name": "Grand Meeting de Marseille",
                        "slug": "@string@-grand-meeting-de-marseille",
                        "time_zone": "Europe/Paris",
                        "live_url": null,
                        "visibility": "public",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "@string@.isDateTime()",
                        "finish_at": "@string@.isDateTime()",
                        "organizer": {
                            "uuid": "a046adbe-9c7b-56a9-a676-6151a6785dda",
                            "first_name": "Jacques",
                            "last_name": "Picard",
                            "scope": null,
                            "role": null,
                            "instance": null,
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 1,
                        "status": "SCHEDULED",
                        "capacity": 2000,
                        "post_address": {
                            "address": "2 Place de la Major",
                            "postal_code": "13002",
                            "city": "13002-13202",
                            "city_name": "Marseille 2ème",
                            "country": "FR",
                            "latitude": 43.298492,
                            "longitude": 5.362377
                        },
                        "created_at": "@string@.isDateTime()",
                        "visio_url": null,
                        "national": false,
                        "mode": null,
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "@string@.isDateTime()",
                        "image_url": null,
                        "image": null,
                        "editable": false
                    },
                    {
                        "uuid": "5b279c9f-2b1e-4b93-9c34-1669f56e9d64",
                        "name": "Tractage sur le terrain",
                        "slug": "@string@-tractage-sur-le-terrain",
                        "time_zone": "Europe/Paris",
                        "live_url": null,
                        "visibility": "public",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "@string@.isDateTime()",
                        "finish_at": "@string@.isDateTime()",
                        "organizer": {
                            "uuid": "@uuid@",
                            "first_name": "Adherent 56",
                            "last_name": "Fa56ke",
                            "scope": null,
                            "role": null,
                            "instance": null,
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 3,
                        "status": "SCHEDULED",
                        "capacity": 50,
                        "post_address": {
                            "address": "47 rue Martre",
                            "postal_code": "92110",
                            "city": "92110-92024",
                            "city_name": "Clichy",
                            "country": "FR",
                            "latitude": 48.9016,
                            "longitude": 2.305268
                        },
                        "created_at": "@string@.isDateTime()",
                        "category": {
                            "event_group_category": {
                                "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                                "name": "événement",
                                "slug": "evenement"
                            },
                            "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                            "name": "Tractage",
                            "slug": "tractage"
                        },
                        "visio_url": null,
                        "national": false,
                        "mode": null,
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "@string@.isDateTime()",
                        "image_url": null,
                        "image": null,
                        "editable": false
                    },
                    {
                        "category": {
                            "event_group_category": {
                                "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                                "name": "événement",
                                "slug": "evenement"
                            },
                            "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                            "name": "Atelier du programme",
                            "slug": "atelier-du-programme"
                        },
                        "uuid": "67e75e81-ad27-4414-bb0b-9e0c6e12b275",
                        "name": "Événements à Fontainebleau 1",
                        "slug": "@string@-evenements-a-fontainebleau-1",
                        "time_zone": "Europe/Paris",
                        "live_url": null,
                        "visibility": "public",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "@string@.isDateTime()",
                        "finish_at": "@string@.isDateTime()",
                        "organizer": {
                            "uuid": "a9fc8d48-6f57-4d89-ae73-50b3f9b586f4",
                            "first_name": "Francis",
                            "last_name": "Brioul",
                            "scope": null,
                            "role": null,
                            "instance": null,
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 0,
                        "status": "SCHEDULED",
                        "capacity": 50,
                        "post_address": {
                            "address": "40 Rue Grande",
                            "postal_code": "77300",
                            "city": "77300-77186",
                            "city_name": "Fontainebleau",
                            "country": "FR",
                            "latitude": 48.404766,
                            "longitude": 2.698759
                        },
                        "created_at": "@string@.isDateTime()",
                        "visio_url": null,
                        "national": false,
                        "mode": null,
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "@string@.isDateTime()",
                        "image_url": null,
                        "image": null,
                        "editable": false
                    }
                ]
            }
            """

        Examples:
            | user                      | scope                                          |
            | referent@en-marche-dev.fr | president_departmental_assembly                |
            | senateur@en-marche-dev.fr | delegated_08f40730-d807-4975-8773-69d8fae1da74 |

    Scenario Outline: As a (delegated) referent I can get a list of events created by me
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "GET" request to "/api/v3/events?scope=<scope>&only_mine&page_size=10"
        Then the response status code should be 200
        And the JSON should be equal to:
            """
            {
                "metadata": {
                    "total_items": 3,
                    "items_per_page": 10,
                    "count": 3,
                    "current_page": 1,
                    "last_page": 1
                },
                "items": [
                    {
                        "uuid": "4d962b05-68fe-4888-ab6b-53b96bdbe797",
                        "name": "Un événement du référent annulé",
                        "slug": "@string@-un-evenement-du-referent-annule",
                        "time_zone": "Europe/Paris",
                        "live_url": null,
                        "visibility": "public",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "@string@.isDateTime()",
                        "finish_at": "@string@.isDateTime()",
                        "organizer": {
                            "uuid": "29461c49-2646-4d89-9c82-50b3f9b586f4",
                            "first_name": "Referent",
                            "last_name": "Referent",
                            "scope": null,
                            "role": null,
                            "instance": null,
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 0,
                        "status": "CANCELLED",
                        "capacity": 50,
                        "post_address": {
                            "address": "40 Rue Grande",
                            "postal_code": "77300",
                            "city": "77300-77186",
                            "city_name": "Fontainebleau",
                            "country": "FR",
                            "latitude": 48.404766,
                            "longitude": 2.698759
                        },
                        "created_at": "@string@.isDateTime()",
                        "category": null,
                        "visio_url": null,
                        "national": false,
                        "mode": "online",
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "@string@.isDateTime()",
                        "image_url": null,
                        "image": null,
                        "editable": false
                    },
                    {
                        "uuid": "5cab27a7-dbb3-4347-9781-566dad1b9eb5",
                        "name": "Nouvel événement online",
                        "slug": "@string@-nouvel-evenement-online",
                        "time_zone": "Europe/Paris",
                        "live_url": null,
                        "visibility": "public",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "@string@.isDateTime()",
                        "finish_at": "@string@.isDateTime()",
                        "organizer": {
                            "uuid": "29461c49-2646-4d89-9c82-50b3f9b586f4",
                            "first_name": "Referent",
                            "last_name": "Referent",
                            "scope": null,
                            "role": "Président",
                            "instance": "Assemblée départementale",
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 0,
                        "status": "SCHEDULED",
                        "capacity": 50,
                        "post_address": {
                            "address": "47 rue Martre",
                            "postal_code": "92110",
                            "city": "92110-92024",
                            "city_name": "Clichy",
                            "country": "FR",
                            "latitude": 48.9016,
                            "longitude": 2.305268
                        },
                        "created_at": "@string@.isDateTime()",
                        "category": null,
                        "visio_url": null,
                        "national": false,
                        "mode": "online",
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "@string@.isDateTime()",
                        "image_url": null,
                        "image": null,
                        "editable": true,
                        "edit_link": "@string@.isUrl()"
                    },
                    {
                        "uuid": "2b7238f9-10ca-4a39-b8a4-ad7f438aa95f",
                        "name": "Nouvel événement online privé et électoral",
                        "slug": "@string@-nouvel-evenement-online-prive-et-electoral",
                        "time_zone": "Europe/Paris",
                        "live_url": null,
                        "visibility": "adherent",
                        "created_at": "@string@.isDateTime()",
                        "begin_at": "@string@.isDateTime()",
                        "finish_at": "@string@.isDateTime()",
                        "organizer": {
                            "uuid": "29461c49-2646-4d89-9c82-50b3f9b586f4",
                            "first_name": "Referent",
                            "last_name": "Referent",
                            "scope": null,
                            "role": null,
                            "instance": null,
                            "image_url": null,
                            "zone": null
                        },
                        "participants_count": 0,
                        "status": "SCHEDULED",
                        "capacity": 50,
                        "post_address": {
                            "address": "40 Rue Grande",
                            "postal_code": "77300",
                            "city": "77300-77186",
                            "city_name": "Fontainebleau",
                            "country": "FR",
                            "latitude": 48.404766,
                            "longitude": 2.698759
                        },
                        "created_at": "@string@.isDateTime()",
                        "category": null,
                        "visio_url": null,
                        "national": false,
                        "mode": "online",
                        "local_begin_at": "@string@.isDateTime()",
                        "local_finish_at": "@string@.isDateTime()",
                        "image_url": null,
                        "image": null,
                        "editable": false
                    }
                ]
            }
            """

        Examples:
            | user                      | scope                                          |
            | referent@en-marche-dev.fr | president_departmental_assembly                |
            | senateur@en-marche-dev.fr | delegated_08f40730-d807-4975-8773-69d8fae1da74 |

    Scenario: As a referent I can get one event
        Given I am logged with "referent@en-marche-dev.fr" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "GET" request to "/api/v3/events/0e5f9f02-fa33-4c2c-a700-4235d752315b"
        Then the response status code should be 200
        And the JSON should be equal to:
            """
            {
                "committee": {
                    "uuid": "515a56c0-bde8-56ef-b90c-4745b1c93818",
                    "name": "En Marche Paris 8",
                    "slug": "en-marche-paris-8",
                    "created_at": "@string@.isDateTime()"
                },
                "uuid": "0e5f9f02-fa33-4c2c-a700-4235d752315b",
                "name": "Événement de la catégorie masquée",
                "slug": "@string@-evenement-de-la-categorie-masquee",
                "description": "Allons à la rencontre des citoyens.",
                "time_zone": "Europe/Paris",
                "live_url": null,
                "visibility": "public",
                "created_at": "@string@.isDateTime()",
                "begin_at": "@string@.isDateTime()",
                "finish_at": "@string@.isDateTime()",
                "organizer": {
                    "uuid": "a046adbe-9c7b-56a9-a676-6151a6785dda",
                    "first_name": "Jacques",
                    "last_name": "Picard",
                    "scope": null,
                    "role": null,
                    "instance": null,
                    "image_url": null,
                    "zone": null
                },
                "participants_count": 0,
                "status": "SCHEDULED",
                "capacity": 10,
                "post_address": {
                    "address": "60 avenue des Champs-Élysées",
                    "postal_code": "75008",
                    "city": "75008-75108",
                    "city_name": "Paris 8ème",
                    "country": "FR",
                    "latitude": 48.870506,
                    "longitude": 2.313243
                },
                "category": {
                    "event_group_category": {
                        "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                        "name": "événement",
                        "slug": "evenement"
                    },
                    "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                    "name": "Catégorie masquée",
                    "slug": "categorie-masquee"
                },
                "visio_url": null,
                "national": false,
                "mode": null,
                "local_begin_at": "@string@.isDateTime()",
                "local_finish_at": "@string@.isDateTime()",
                "image_url": null,
                "image": null,
                "editable": false
            }
            """

    Scenario: As a connected user I can get one cancelled event
        Given I am logged with "referent@en-marche-dev.fr" via OAuth client "J'écoute" with scope "jemarche_app"
        When I send a "GET" request to "/api/v3/events/4d962b05-68fe-4888-ab6b-53b96bdbe797"
        Then the response status code should be 200
        And the JSON should be equal to:
            """
            {
                "uuid": "4d962b05-68fe-4888-ab6b-53b96bdbe797",
                "name": "Un événement du référent annulé",
                "slug": "@string@-un-evenement-du-referent-annule",
                "description": "Description de l'événement du référent annulé",
                "time_zone": "Europe/Paris",
                "live_url": null,
                "visibility": "public",
                "created_at": "@string@.isDateTime()",
                "begin_at": "@string@.isDateTime()",
                "finish_at": "@string@.isDateTime()",
                "organizer": {
                    "uuid": "29461c49-2646-4d89-9c82-50b3f9b586f4",
                    "first_name": "Referent",
                    "last_name": "Referent",
                    "scope": null,
                    "role": null,
                    "instance": null,
                    "image_url": null,
                    "zone": null
                },
                "participants_count": 0,
                "status": "CANCELLED",
                "capacity": 50,
                "post_address": {
                    "address": "40 Rue Grande",
                    "postal_code": "77300",
                    "city": "77300-77186",
                    "city_name": "Fontainebleau",
                    "country": "FR",
                    "latitude": 48.404766,
                    "longitude": 2.698759
                },
                "category": null,
                "visio_url": null,
                "national": false,
                "mode": "online",
                "local_begin_at": "@string@.isDateTime()",
                "local_finish_at": "@string@.isDateTime()",
                "image_url": null,
                "image": null,
                "user_registered_at": null,
                "national": false,
                "object_state": "full",
                "editable": false
            }
            """
        When I send a "GET" request to "/api/v3/events/:last_response.slug:"
        Then the response status code should be 200
        And the JSON should be equal to:
            """
            {
                "uuid": "4d962b05-68fe-4888-ab6b-53b96bdbe797",
                "name": "Un événement du référent annulé",
                "slug": "@string@-un-evenement-du-referent-annule",
                "description": "Description de l'événement du référent annulé",
                "time_zone": "Europe/Paris",
                "live_url": null,
                "visibility": "public",
                "created_at": "@string@.isDateTime()",
                "begin_at": "@string@.isDateTime()",
                "finish_at": "@string@.isDateTime()",
                "organizer": {
                    "uuid": "29461c49-2646-4d89-9c82-50b3f9b586f4",
                    "first_name": "Referent",
                    "last_name": "Referent",
                    "scope": null,
                    "role": null,
                    "instance": null,
                    "image_url": null,
                    "zone": null
                },
                "participants_count": 0,
                "status": "CANCELLED",
                "capacity": 50,
                "post_address": {
                    "address": "40 Rue Grande",
                    "postal_code": "77300",
                    "city": "77300-77186",
                    "city_name": "Fontainebleau",
                    "country": "FR",
                    "latitude": 48.404766,
                    "longitude": 2.698759
                },
                "category": null,
                "visio_url": null,
                "national": false,
                "mode": "online",
                "local_begin_at": "@string@.isDateTime()",
                "local_finish_at": "@string@.isDateTime()",
                "image_url": null,
                "image": null,
                "user_registered_at": null,
                "national": false,
                "object_state": "full",
                "editable": false
            }
            """

    Scenario Outline: As a (delegated) referent I can get one event with full info
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "GET" request to "/api/v3/events/0e5f9f02-fa33-4c2c-a700-4235d752315b?scope=<scope>"
        Then the response status code should be 200
        And the JSON should be equal to:
            """
            {
                "committee": {
                    "uuid": "515a56c0-bde8-56ef-b90c-4745b1c93818",
                    "name": "En Marche Paris 8",
                    "slug": "en-marche-paris-8",
                    "created_at": "@string@.isDateTime()"
                },
                "uuid": "0e5f9f02-fa33-4c2c-a700-4235d752315b",
                "name": "Événement de la catégorie masquée",
                "slug": "@string@-evenement-de-la-categorie-masquee",
                "description": "Allons à la rencontre des citoyens.",
                "time_zone": "Europe/Paris",
                "live_url": null,
                "visibility": "public",
                "created_at": "@string@.isDateTime()",
                "begin_at": "@string@.isDateTime()",
                "finish_at": "@string@.isDateTime()",
                "organizer": {
                    "uuid": "a046adbe-9c7b-56a9-a676-6151a6785dda",
                    "first_name": "Jacques",
                    "last_name": "Picard",
                    "scope": null,
                    "role": null,
                    "instance": null,
                    "image_url": null,
                    "zone": null
                },
                "participants_count": 0,
                "status": "SCHEDULED",
                "capacity": 10,
                "local_begin_at": "@string@.isDateTime()",
                "local_finish_at": "@string@.isDateTime()",
                "post_address": {
                    "address": "60 avenue des Champs-Élysées",
                    "postal_code": "75008",
                    "city": "75008-75108",
                    "city_name": "Paris 8ème",
                    "country": "FR",
                    "latitude": 48.870506,
                    "longitude": 2.313243
                },
                "category": {
                    "event_group_category": {
                        "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                        "name": "événement",
                        "slug": "evenement"
                    },
                    "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                    "name": "Catégorie masquée",
                    "slug": "categorie-masquee"
                },
                "visio_url": null,
                "national": false,
                "mode": null,
                "image_url": null,
                "image": null,
                "editable": false
            }
            """

        Examples:
            | user                      | scope                                          |
            | referent@en-marche-dev.fr | president_departmental_assembly                |
            | senateur@en-marche-dev.fr | delegated_08f40730-d807-4975-8773-69d8fae1da74 |

    Scenario: As a deputy I cannot create an event with missing or invalid data
        Given I am logged with "deputy@en-marche-dev.fr" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "POST" request to "/api/v3/events?scope=deputy" with body:
            """
            {
                "begin_at": "2018-01-01 10:10:10",
                "finish_at": "2018-01-06 16:30:30",
                "post_address": {
                    "address": "50 rue de la villette",
                    "postal_code": "69003",
                    "city_name": "Lyon 3ème",
                    "country": "FR"
                }
            }
            """
        Then the response status code should be 400
        And the response should be in JSON
        And the JSON should be equal to:
            """
            {
                "detail": "category: Catégorie est requise.\nfinish_at: La date de fin de votre événement ne peut pas dépasser le 4 janv. 2018, 10:10.\nname: Cette valeur ne doit pas être vide.\ncanonical_name: Cette valeur ne doit pas être vide.\ndescription: Cette valeur ne doit pas être vide.",
                "title": "An error occurred",
                "type": "https://tools.ietf.org/html/rfc2616#section-10",
                "violations": [
                    {
                        "code": null,
                        "message": "Catégorie est requise.",
                        "propertyPath": "category"
                    },
                    {
                        "code": null,
                        "message": "La date de fin de votre événement ne peut pas dépasser le 4 janv. 2018, 10:10.",
                        "propertyPath": "finish_at"
                    },
                    {
                        "code": "@uuid@",
                        "message": "Cette valeur ne doit pas être vide.",
                        "propertyPath": "name"
                    },
                    {
                        "code": "@uuid@",
                        "message": "Cette valeur ne doit pas être vide.",
                        "propertyPath": "canonical_name"
                    },
                    {
                        "code": "@uuid@",
                        "message": "Cette valeur ne doit pas être vide.",
                        "propertyPath": "description"
                    }
                ]
            }
            """

    Scenario: As a deputy I can create an event
        Given I am logged with "deputy@en-marche-dev.fr" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "POST" request to "/api/v3/events?scope=deputy" with body:
            """
            {
                "name": "Nouveau événement",
                "category": "kiosque",
                "description": "Une description de l'événement",
                "begin_at": "2023-02-20 16:30:30",
                "finish_at": "2023-01-30 16:30:30",
                "capacity": 100,
                "national": false,
                "mode": "online",
                "visio_url": "https://en-marche.fr/reunions/123",
                "post_address": {
                    "address": "62 avenue des champs-élysées",
                    "postal_code": "75008",
                    "city_name": "Paris 8ème",
                    "country": "FR"
                },
                "time_zone": "Europe/Paris",
                "visibility": "private"
            }
            """
        Then the response status code should be 400
        And the JSON should be equal to:
            """
            {
                "detail": "finish_at: La date de fin de l'événement doit être postérieure à la date de début.",
                "title": "An error occurred",
                "type": "https://tools.ietf.org/html/rfc2616#section-10",
                "violations": [
                    {
                        "code": "@uuid@",
                        "message": "La date de fin de l'événement doit être postérieure à la date de début.",
                        "propertyPath": "finish_at"
                    }
                ]
            }
            """
        When I send a "POST" request to "/api/v3/events?scope=deputy" with body:
            """
            {
                "name": "Nouveau événement",
                "category": "kiosque",
                "description": "Une description de l'événement",
                "begin_at": "2023-01-29 16:30:30",
                "finish_at": "2023-01-30 16:30:30",
                "capacity": 100,
                "national": false,
                "mode": "online",
                "visio_url": "https://en-marche.fr/reunions/123",
                "time_zone": "Europe/Paris",
                "visibility": "private"
            }
            """
        Then the response status code should be 201
        And the response should be in JSON
        And the JSON should be equal to:
            """
            {
                "uuid": "@uuid@",
                "name": "Nouveau événement",
                "slug": "2023-01-29-nouveau-evenement",
                "description": "Une description de l'événement",
                "time_zone": "Europe/Paris",
                "live_url": null,
                "visibility": "private",
                "created_at": "@string@.isDateTime()",
                "begin_at": "2023-01-29T16:30:30+01:00",
                "finish_at": "2023-01-30T16:30:30+01:00",
                "organizer": {
                    "uuid": "918f07e5-676b-49c0-b76d-72ce01cb2404",
                    "first_name": "Député",
                    "last_name": "PARIS I",
                    "scope": "deputy",
                    "role": "Délégué",
                    "instance": "Circonscription",
                    "image_url": null,
                    "zone": "Paris (1) (75-1)"
                },
                "participants_count": 1,
                "status": "SCHEDULED",
                "capacity": 100,
                "post_address": {
                    "address": null,
                    "postal_code": null,
                    "city": null,
                    "city_name": null,
                    "country": null,
                    "latitude": null,
                    "longitude": null
                },
                "category": {
                    "event_group_category": {
                        "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                        "name": "événement",
                        "slug": "evenement"
                    },
                    "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                    "name": "Kiosque",
                    "slug": "kiosque"
                },
                "visio_url": "https://en-marche.fr/reunions/123",
                "national": false,
                "mode": "online",
                "local_begin_at": "@string@.isDateTime()",
                "local_finish_at": "@string@.isDateTime()",
                "image_url": null,
                "image": null,
                "editable": true,
                "edit_link": "@string@.isUrl()"
            }
            """

    Scenario: As a Animator I can create a committee event
        Given I am logged with "adherent-male-55@en-marche-dev.fr" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "POST" request to "/api/v3/events?scope=animator" with body:
            """
            {
                "name": "Nouveau événement",
                "type": "committee",
                "category": "kiosque",
                "description": "Une description de l'événement",
                "begin_at": "2023-01-29 16:30:30",
                "finish_at": "2023-01-30 16:30:30",
                "capacity": 100,
                "committee": "5e00c264-1d4b-43b8-862e-29edc38389b3",
                "national": false,
                "mode": "online",
                "visio_url": "https://en-marche.fr/reunions/123",
                "post_address": {
                    "address": "",
                    "postal_code": "",
                    "city_name": "",
                    "country": ""
                },
                "time_zone": "Europe/Paris",
                "visibility": "public"
            }
            """
        Then the response status code should be 201
        And the response should be in JSON
        And the JSON should be equal to:
            """
            {
                "uuid": "@uuid@",
                "name": "Nouveau événement",
                "slug": "2023-01-29-nouveau-evenement",
                "description": "Une description de l'événement",
                "time_zone": "Europe/Paris",
                "live_url": null,
                "visibility": "public",
                "created_at": "@string@.isDateTime()",
                "begin_at": "2023-01-29T16:30:30+01:00",
                "finish_at": "2023-01-30T16:30:30+01:00",
                "organizer": {
                    "uuid": "@uuid@",
                    "first_name": "Adherent 55",
                    "last_name": "Fa55ke",
                    "scope": "animator",
                    "role": "Responsable",
                    "instance": "Comité local",
                    "image_url": null,
                    "zone": "Comité des 3 communes"
                },
                "committee": {
                    "uuid": "5e00c264-1d4b-43b8-862e-29edc38389b3",
                    "name": "Comité des 3 communes",
                    "slug": "comite-des-3-communes",
                    "created_at": "@string@.isDateTime()"
                },
                "participants_count": 1,
                "status": "SCHEDULED",
                "capacity": 100,
                "post_address": {
                    "address": "",
                    "postal_code": "",
                    "city": null,
                    "city_name": "",
                    "country": "",
                    "latitude": null,
                    "longitude": null
                },
                "category": {
                    "event_group_category": {
                        "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                        "name": "événement",
                        "slug": "evenement"
                    },
                    "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                    "name": "Kiosque",
                    "slug": "kiosque"
                },
                "visio_url": "https://en-marche.fr/reunions/123",
                "national": false,
                "mode": "online",
                "local_begin_at": "@string@.isDateTime()",
                "local_finish_at": "@string@.isDateTime()",
                "image_url": null,
                "image": null,
                "editable": true,
                "edit_link": "@string@.isUrl()"
            }
            """
        And I should have 1 email "RenaissanceEventNotificationMessage" for "@en-marche-dev.fr" with payload:
            """
            {
                "template_name": "renaissance-event-notification",
                "template_content": [],
                "message": {
                    "subject": "29 janvier - 16h30 : Nouvel événement de Comité des 3 communes : Nouveau événement",
                    "from_email": "ne-pas-repondre@parti-renaissance.fr",
                    "html": null,
                    "global_merge_vars": [
                        {
                            "name": "animator_firstname",
                            "content": "Adherent 55"
                        },
                        {
                            "name": "event_name",
                            "content": "Nouveau événement"
                        },
                        {
                            "name": "event_date",
                            "content": "dimanche 29 janvier 2023"
                        },
                        {
                            "name": "event_hour",
                            "content": "16h30"
                        },
                        {
                            "name": "event_address",
                            "content": ""
                        },
                        {
                            "name": "event_slug",
                            "content": "http://test.renaissance.code/app/evenements/2023-01-29-nouveau-evenement"
                        },
                        {
                            "name": "event_description",
                            "content": "Une description de l'événement"
                        },
                        {
                            "name": "committee_name",
                            "content": "Comité des 3 communes"
                        }
                    ],
                    "merge_vars": [
                        {
                            "rcpt": "adherent-male-51@en-marche-dev.fr",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Adherent 51"
                                }
                            ]
                        },
                        {
                            "rcpt": "adherent-female-52@en-marche-dev.fr",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Adherent 52"
                                }
                            ]
                        },
                        {
                            "rcpt": "adherent-male-53@en-marche-dev.fr",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Adherent 53"
                                }
                            ]
                        },
                        {
                            "rcpt": "adherent-female-54@en-marche-dev.fr",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Adherent 54"
                                }
                            ]
                        },
                        {
                            "rcpt": "adherent-male-55@en-marche-dev.fr",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Adherent 55"
                                }
                            ]
                        },
                        {
                            "rcpt": "adherent-female-56@en-marche-dev.fr",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Adherent 56"
                                }
                            ]
                        },
                        {
                            "rcpt": "adherent-male-57@en-marche-dev.fr",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Adherent 57"
                                }
                            ]
                        },
                        {
                            "rcpt": "adherent-female-58@en-marche-dev.fr",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Adherent 58"
                                }
                            ]
                        },
                        {
                            "rcpt": "adherent-male-59@en-marche-dev.fr",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Adherent 59"
                                }
                            ]
                        },
                        {
                            "rcpt": "adherent-female-60@en-marche-dev.fr",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Adherent 60"
                                }
                            ]
                        }
                    ],
                    "headers": {
                        "Reply-To": "adherent-male-55@en-marche-dev.fr"
                    },
                    "from_name": "Renaissance",
                    "to": [
                        {
                            "email": "adherent-male-51@en-marche-dev.fr",
                            "type": "to",
                            "name": "Adherent 51 Fa51ke"
                        },
                        {
                            "email": "adherent-female-52@en-marche-dev.fr",
                            "type": "to",
                            "name": "Adherent 52 Fa52ke"
                        },
                        {
                            "email": "adherent-male-53@en-marche-dev.fr",
                            "type": "to",
                            "name": "Adherent 53 Fa53ke"
                        },
                        {
                            "email": "adherent-female-54@en-marche-dev.fr",
                            "type": "to",
                            "name": "Adherent 54 Fa54ke"
                        },
                        {
                            "email": "adherent-male-55@en-marche-dev.fr",
                            "type": "to",
                            "name": "Adherent 55 Fa55ke"
                        },
                        {
                            "email": "adherent-female-56@en-marche-dev.fr",
                            "type": "to",
                            "name": "Adherent 56 Fa56ke"
                        },
                        {
                            "email": "adherent-male-57@en-marche-dev.fr",
                            "type": "to",
                            "name": "Adherent 57 Fa57ke"
                        },
                        {
                            "email": "adherent-female-58@en-marche-dev.fr",
                            "type": "to",
                            "name": "Adherent 58 Fa58ke"
                        },
                        {
                            "email": "adherent-male-59@en-marche-dev.fr",
                            "type": "to",
                            "name": "Adherent 59 Fa59ke"
                        },
                        {
                            "email": "adherent-female-60@en-marche-dev.fr",
                            "type": "to",
                            "name": "Adherent 60 Fa60ke"
                        }
                    ]
                }
            }
            """
        And I should have 1 notification "CommitteeEventCreatedNotification" with data:
            | key   | value                                                              |
            | data  | {"link":"http://vox.code/evenements/2023-01-29-nouveau-evenement"} |
            | scope | committee:17                                                       |
            | title | Nouvel événement dans votre comité Comité des 3 communes           |
            | body  | Nouveau événement • dimanche 29 janvier 2023 à 16h30               |
        When I save this response
        And I send a "PUT" request to "/api/v3/events/:last_response.uuid:?scope=animator" with body:
            """
            {
                "description": "Nouvelle description",
                "category": "kiosque",
                "begin_at": "2023-01-29T16:30:30+01:00",
                "finish_at": "2023-01-30T16:30:30+01:00",
                "national": false,
                "mode": "online",
                "visio_url": "http://visio.fr",
                "post_address": {
                    "address": "",
                    "postal_code": "",
                    "city_name": "",
                    "country": ""
                },
                "committee": null
            }
            """
        Then the response status code should be 200

    Scenario Outline: As a (delegated) referent I can edit my (delegator's) default event
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "PUT" request to "/api/v3/events/5cab27a7-dbb3-4347-9781-566dad1b9eb5?scope=<scope>" with body:
            """
            {
                "description": "Nouvelle description",
                "category": "kiosque",
                "begin_at": "2022-12-12 10:30:00",
                "finish_at": "2022-12-12 16:30:00",
                "national": false,
                "mode": "online",
                "visio_url": "http://visio.fr",
                "post_address": {
                    "address": "dammarie-les-lys",
                    "postal_code": "77190",
                    "city": "77190-77152",
                    "city_name": "dammarie-les-lys",
                    "country": "FR"
                }
            }
            """
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON should be equal to:
            """
            {
                "uuid": "5cab27a7-dbb3-4347-9781-566dad1b9eb5",
                "name": "Nouvel événement online",
                "slug": "@string@-nouvel-evenement-online",
                "description": "Nouvelle description",
                "time_zone": "Europe/Paris",
                "live_url": null,
                "visibility": "public",
                "created_at": "@string@.isDateTime()",
                "begin_at": "2022-12-12T10:30:00+01:00",
                "finish_at": "2022-12-12T16:30:00+01:00",
                "organizer": {
                    "uuid": "29461c49-2646-4d89-9c82-50b3f9b586f4",
                    "first_name": "Referent",
                    "last_name": "Referent",
                    "scope": null,
                    "role": "Président",
                    "instance": "Assemblée départementale",
                    "image_url": null,
                    "zone": null
                },
                "participants_count": 0,
                "status": "SCHEDULED",
                "capacity": 50,
                "post_address": {
                    "address": "dammarie-les-lys",
                    "postal_code": "77190",
                    "city": "77190-77152",
                    "city_name": "dammarie-les-lys",
                    "country": "FR",
                    "latitude": null,
                    "longitude": null
                },
                "category": {
                    "event_group_category": {
                        "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                        "name": "événement",
                        "slug": "evenement"
                    },
                    "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                    "name": "Kiosque",
                    "slug": "kiosque"
                },
                "visio_url": "http://visio.fr",
                "national": false,
                "mode": "online",
                "local_begin_at": "@string@.isDateTime()",
                "local_finish_at": "@string@.isDateTime()",
                "image_url": null,
                "image": null,
                "editable": true,
                "edit_link": "@string@.isUrl()"
            }
            """
        And I should have 1 email
        And I should have 1 email "EventUpdateMessage" for "francis.brioul@yahoo.com" with payload:
            """
            {
                "template_name": "event-update",
                "template_content": [],
                "message": {
                    "subject": "Un événement a été modifié",
                    "from_email": "ne-pas-repondre@parti-renaissance.fr",
                    "html": null,
                    "global_merge_vars": [
                        {
                            "name": "event_name",
                            "content": "Nouvel événement online"
                        },
                        {
                            "name": "event_url",
                            "content": "http://vox.code/evenements/@string@.isDateTime()-nouvel-evenement-online"
                        },
                        {
                            "name": "event_date",
                            "content": "lundi 12 décembre 2022"
                        },
                        {
                            "name": "event_hour",
                            "content": "10h30"
                        },
                        {
                            "name": "event_address",
                            "content": "dammarie-les-lys, 77190 dammarie-les-lys"
                        }
                    ],
                    "merge_vars": [
                        {
                            "rcpt": "referent@en-marche-dev.fr",
                            "vars": [
                                {
                                    "name": "first_name",
                                    "content": "Referent"
                                }
                            ]
                        },
                        {
                            "rcpt": "francis.brioul@yahoo.com",
                            "vars": [
                                {
                                    "name": "first_name",
                                    "content": "Francis"
                                }
                            ]
                        },
                        {
                            "rcpt": "simple-user@example.ch",
                            "vars": [
                                {
                                    "name": "first_name",
                                    "content": "Simple"
                                }
                            ]
                        },
                        {
                            "rcpt": "marie.claire@test.com",
                            "vars": [
                                {
                                    "name": "first_name",
                                    "content": "Marie"
                                }
                            ]
                        }
                    ],
                    "from_name": "Renaissance",
                    "to": [
                        {
                            "email": "referent@en-marche-dev.fr",
                            "type": "to",
                            "name": "Referent Referent"
                        },
                        {
                            "email": "francis.brioul@yahoo.com",
                            "type": "to",
                            "name": "Francis Brioul"
                        },
                        {
                            "email": "simple-user@example.ch",
                            "type": "to",
                            "name": "Simple User"
                        },
                        {
                            "email": "marie.claire@test.com",
                            "type": "to",
                            "name": "Marie CLAIRE"
                        }
                    ]
                }
            }
            """

        Examples:
            | user                            | scope                                          |
            | president-ad@renaissance-dev.fr | president_departmental_assembly                |
            | senateur@en-marche-dev.fr       | delegated_08f40730-d807-4975-8773-69d8fae1da74 |

    Scenario Outline: As a (delegated) referent I can cancel my (delegator's) default event
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "PUT" request to "/api/v3/events/5cab27a7-dbb3-4347-9781-566dad1b9eb5/cancel?scope=<scope>"
        Then the response status code should be 200
        And I should have 1 email
        And I should have 1 email "EventCancellationMessage" for "francis.brioul@yahoo.com" with payload:
            """
            {
                "template_name": "event-cancellation",
                "template_content": [],
                "message": {
                    "subject": "Événement annulé",
                    "from_email": "ne-pas-repondre@parti-renaissance.fr",
                    "html": null,
                    "global_merge_vars": [
                        {
                            "name": "event_name",
                            "content": "Nouvel événement online"
                        },
                        {
                            "name": "events_link",
                            "content": "http://vox.code/evenements"
                        }
                    ],
                    "merge_vars": [
                        {
                            "rcpt": "referent@en-marche-dev.fr",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Referent"
                                }
                            ]
                        },
                        {
                            "rcpt": "francis.brioul@yahoo.com",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Francis"
                                }
                            ]
                        },
                        {
                            "rcpt": "simple-user@example.ch",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Simple"
                                }
                            ]
                        },
                        {
                            "rcpt": "marie.claire@test.com",
                            "vars": [
                                {
                                    "name": "target_firstname",
                                    "content": "Marie"
                                }
                            ]
                        }
                    ],
                    "from_name": "Renaissance",
                    "to": [
                        {
                            "email": "referent@en-marche-dev.fr",
                            "type": "to",
                            "name": "Referent Referent"
                        },
                        {
                            "email": "francis.brioul@yahoo.com",
                            "type": "to",
                            "name": "Francis Brioul"
                        },
                        {
                            "email": "simple-user@example.ch",
                            "type": "to",
                            "name": "Simple User"
                        },
                        {
                            "email": "marie.claire@test.com",
                            "type": "to",
                            "name": "Marie CLAIRE"
                        }
                    ]
                }
            }
            """

        Examples:
            | user                      | scope                                          |
            | referent@en-marche-dev.fr | president_departmental_assembly                |
            | senateur@en-marche-dev.fr | delegated_08f40730-d807-4975-8773-69d8fae1da74 |

    Scenario Outline: As a (delegated) referent I cannot delete my (delegator's) event with participants
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "DELETE" request to "/api/v3/events/5cab27a7-dbb3-4347-9781-566dad1b9eb5?scope=<scope>"
        Then the response status code should be 403

        Examples:
            | user                      | scope                                          |
            | referent@en-marche-dev.fr | president_departmental_assembly                |
            | senateur@en-marche-dev.fr | delegated_08f40730-d807-4975-8773-69d8fae1da74 |

    Scenario Outline: As a (delegated) referent I can get the list of event participants
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "GET" request to "/api/v3/events/5cab27a7-dbb3-4347-9781-566dad1b9eb5/participants?scope=<scope>&page_size=10"
        Then the response status code should be 200
        And the JSON should be equal to:
            """
            {
                "metadata": {
                    "total_items": 4,
                    "items_per_page": 10,
                    "count": 4,
                    "current_page": 1,
                    "last_page": 1
                },
                "items": [
                    {
                        "uuid": "@uuid@",
                        "created_at": "@string@.isDateTime()",
                        "first_name": "Referent",
                        "last_name": "Referent",
                        "postal_code": "77000",
                        "email_address": "referent@en-marche-dev.fr",
                        "phone": "+33 6 73 65 43 49",
                        "image_url": null,
                        "tags": []
                    },
                    {
                        "uuid": "@uuid@",
                        "created_at": "@string@.isDateTime()",
                        "first_name": "Francis",
                        "last_name": "Brioul",
                        "postal_code": "77000",
                        "email_address": "francis.brioul@yahoo.com",
                        "image_url": null,
                        "phone": "+33 6 73 65 43 49",
                        "tags": []
                    },
                    {
                        "uuid": "@uuid@",
                        "created_at": "@string@.isDateTime()",
                        "first_name": "Simple",
                        "last_name": "User",
                        "postal_code": "8057",
                        "email_address": "simple-user@example.ch",
                        "phone": null,
                        "image_url": null,
                        "tags": []
                    },
                    {
                        "uuid": "@uuid@",
                        "created_at": "@string@.isDateTime()",
                        "first_name": "Marie",
                        "last_name": "CLAIRE",
                        "postal_code": null,
                        "email_address": "marie.claire@test.com",
                        "phone": null,
                        "image_url": null,
                        "tags": []
                    }
                ]
            }
            """

        Examples:
            | user                      | scope                                          |
            | referent@en-marche-dev.fr | president_departmental_assembly                |
            | senateur@en-marche-dev.fr | delegated_08f40730-d807-4975-8773-69d8fae1da74 |

    Scenario Outline: As a (delegated) legislative candidate I can create an event
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "POST" request to "/api/v3/events?scope=<scope>" with body:
            """
            {
                "name": "Nouveau événement",
                "category": "kiosque",
                "description": "Une description de l'événement",
                "begin_at": "2023-01-29 16:30:30",
                "finish_at": "2023-01-30 16:30:30",
                "capacity": 100,
                "national": false,
                "mode": "online",
                "visio_url": "https://en-marche.fr/reunions/123",
                "post_address": {
                    "address": "226 Rue de Rivoli",
                    "postal_code": "75001",
                    "city_name": "Paris 1er",
                    "country": "FR"
                },
                "time_zone": "Europe/Paris",
                "visibility": "public"
            }
            """
        Then the response status code should be 201
        And the response should be in JSON
        And the JSON should be equal to:
            """
            {
                "category": {
                    "event_group_category": {
                        "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                        "name": "événement",
                        "slug": "evenement"
                    },
                    "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                    "name": "Kiosque",
                    "slug": "kiosque"
                },
                "uuid": "@uuid@",
                "name": "Nouveau événement",
                "slug": "2023-01-29-nouveau-evenement",
                "description": "Une description de l'événement",
                "time_zone": "Europe/Paris",
                "live_url": null,
                "visibility": "public",
                "created_at": "@string@.isDateTime()",
                "begin_at": "2023-01-29T16:30:30+01:00",
                "finish_at": "2023-01-30T16:30:30+01:00",
                "organizer": {
                    "uuid": "<uuid>",
                    "first_name": "<first_name>",
                    "last_name": "<last_name>",
                    "scope": "<scope>",
                    "role": "<role>",
                    "instance": "Circonscription",
                    "image_url": null,
                    "zone": "Paris (1) (75-1)"
                },
                "participants_count": 1,
                "status": "SCHEDULED",
                "capacity": 100,
                "post_address": {
                    "address": "226 Rue de Rivoli",
                    "postal_code": "75001",
                    "city": "75001-75101",
                    "city_name": "Paris 1er",
                    "country": "FR",
                    "latitude": 48.8596,
                    "longitude": 2.344967
                },
                "visio_url": "https://en-marche.fr/reunions/123",
                "national": false,
                "mode": "online",
                "local_begin_at": "@string@.isDateTime()",
                "local_finish_at": "@string@.isDateTime()",
                "image_url": null,
                "image": null,
                "editable": true,
                "edit_link": "@string@.isUrl()"
            }
            """
        And I should have 1 notification "DefaultEventCreatedNotification" with data:
            | key   | value                                                                                     |
            | data  | {"link":"http://vox.code/evenements/2023-01-29-nouveau-evenement"}                        |
            | scope | zone:75                                                                                   |
            | title | Paris, nouvel événement                                                                   |
            | body  | Nouveau événement • dimanche 29 janvier 2023 à 16h30 • 226 Rue de Rivoli, 75001 Paris 1er |

        Examples:
            | user                                  | uuid                                 | scope                                          | first_name    | last_name | role                      |
            | senatorial-candidate@en-marche-dev.fr | ab03c939-8f70-40a8-b2cd-d147ec7fd09e | legislative_candidate                          | Jean-Baptiste | Fortin    | Candidat                  |
            | gisele-berthoux@caramail.com          | b4219d47-3138-5efd-9762-2ef9f9495084 | delegated_b24fea43-ecd8-4bf4-b500-6f97886ab77c | Gisele        | Berthoux  | Responsable communication |

    Scenario Outline: As a (delegated) legislative candidate I can edit my (delegator's) event
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "PUT" request to "/api/v3/events/594e7ad0-c289-49ae-8c23-0129275d128b?scope=<scope>" with body:
            """
            {
                "description": "Nouvelle description",
                "category": "kiosque",
                "begin_at": "2022-12-12 10:30:00",
                "finish_at": "2022-12-12 16:30:00",
                "national": false,
                "mode": "online",
                "visio_url": "http://visio.fr",
                "post_address": {
                    "address": "226 Rue de Rivoli",
                    "postal_code": "75001",
                    "city_name": "Paris 1er",
                    "country": "FR"
                }
            }
            """
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON should be equal to:
            """
            {
                "uuid": "594e7ad0-c289-49ae-8c23-0129275d128b",
                "name": "Un événement du candidat aux législatives",
                "slug": "@string@-un-evenement-du-candidat-aux-legislatives",
                "description": "Nouvelle description",
                "time_zone": "Europe/Paris",
                "live_url": null,
                "visibility": "public",
                "created_at": "@string@.isDateTime()",
                "begin_at": "2022-12-12T10:30:00+01:00",
                "finish_at": "2022-12-12T16:30:00+01:00",
                "organizer": {
                    "first_name": "Jean-Baptiste",
                    "last_name": "Fortin",
                    "uuid": "ab03c939-8f70-40a8-b2cd-d147ec7fd09e",
                    "scope": null,
                    "role": "Candidat",
                    "instance": "Circonscription",
                    "image_url": null,
                    "zone": null
                },
                "participants_count": 0,
                "status": "SCHEDULED",
                "capacity": 50,
                "post_address": {
                    "address": "226 Rue de Rivoli",
                    "postal_code": "75001",
                    "city": "75001-75101",
                    "city_name": "Paris 1er",
                    "country": "FR",
                    "latitude": 48.859599,
                    "longitude": 2.344967
                },
                "category": {
                    "event_group_category": {
                        "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                        "name": "événement",
                        "slug": "evenement"
                    },
                    "description": "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression.",
                    "name": "Kiosque",
                    "slug": "kiosque"
                },
                "visio_url": "http://visio.fr",
                "national": false,
                "mode": "online",
                "local_begin_at": "@string@.isDateTime()",
                "local_finish_at": "@string@.isDateTime()",
                "image_url": null,
                "image": null,
                "editable": true,
                "edit_link": "@string@.isUrl()"
            }
            """

        Examples:
            | user                                  | scope                                          |
            | senatorial-candidate@en-marche-dev.fr | legislative_candidate                          |
            | gisele-berthoux@caramail.com          | delegated_b24fea43-ecd8-4bf4-b500-6f97886ab77c |

    Scenario Outline: As a (delegated) legislative candidate I can cancel my (delegator's) event
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "PUT" request to "/api/v3/events/594e7ad0-c289-49ae-8c23-0129275d128b/cancel?scope=<scope>"
        Then the response status code should be 200

        Examples:
            | user                                  | scope                                          |
            | senatorial-candidate@en-marche-dev.fr | legislative_candidate                          |
            | gisele-berthoux@caramail.com          | delegated_b24fea43-ecd8-4bf4-b500-6f97886ab77c |

    Scenario Outline: As a (delegated) legislative candidate I can delete my event with no participants
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "DELETE" request to "/api/v3/events/594e7ad0-c289-49ae-8c23-0129275d128b?scope=<scope>"
        Then the response status code should be 204

        Examples:
            | user                                  | scope                                          |
            | senatorial-candidate@en-marche-dev.fr | legislative_candidate                          |
            | gisele-berthoux@caramail.com          | delegated_b24fea43-ecd8-4bf4-b500-6f97886ab77c |

    Scenario Outline: As a (delegated) legislative candidate I can update the image of my event
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "POST" request to "/api/v3/events/594e7ad0-c289-49ae-8c23-0129275d128b/image?scope=<scope>" with body:
            """
            {
                "content": "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg=="
            }
            """
        Then the response status code should be 200
        And the JSON node "image_url" should match "http://test.renaissance.code/assets/images/events/@string@.png"
        When I send a "DELETE" request to "/api/v3/events/594e7ad0-c289-49ae-8c23-0129275d128b/image?scope=<scope>"
        Then the response status code should be 200
        When I send a "GET" request to "/api/v3/events/594e7ad0-c289-49ae-8c23-0129275d128b?scope=<scope>"
        Then the JSON should be a superset of:
            """
            {
                "image_url": null,
                "image": null
            }
            """

        Examples:
            | user                                  | scope                                          |
            | senatorial-candidate@en-marche-dev.fr | legislative_candidate                          |
            | gisele-berthoux@caramail.com          | delegated_b24fea43-ecd8-4bf4-b500-6f97886ab77c |

    Scenario Outline: As a (delegated) legislative candidate I can manage not my event
        Given I am logged with "<user>" via OAuth client "JeMengage Web" with scope "jemengage_admin"
        When I send a "<method>" request to "/api/v3/events<url>?scope=<scope>"
        Then the response status code should be 403

        Examples:
            | user                                  | scope                                          | method | url                                          |
            | senatorial-candidate@en-marche-dev.fr | legislative_candidate                          | PUT    | /0e5f9f02-fa33-4c2c-a700-4235d752315b        |
            | senatorial-candidate@en-marche-dev.fr | legislative_candidate                          | PUT    | /0e5f9f02-fa33-4c2c-a700-4235d752315b/cancel |
            | senatorial-candidate@en-marche-dev.fr | legislative_candidate                          | DELETE | /0e5f9f02-fa33-4c2c-a700-4235d752315b        |
            | senatorial-candidate@en-marche-dev.fr | legislative_candidate                          | POST   | /0e5f9f02-fa33-4c2c-a700-4235d752315b/image  |
            | senatorial-candidate@en-marche-dev.fr | legislative_candidate                          | DELETE | /0e5f9f02-fa33-4c2c-a700-4235d752315b/image  |
            | gisele-berthoux@caramail.com          | delegated_b24fea43-ecd8-4bf4-b500-6f97886ab77c | PUT    | /0e5f9f02-fa33-4c2c-a700-4235d752315b        |
            | gisele-berthoux@caramail.com          | delegated_b24fea43-ecd8-4bf4-b500-6f97886ab77c | PUT    | /0e5f9f02-fa33-4c2c-a700-4235d752315b/cancel |
            | gisele-berthoux@caramail.com          | delegated_b24fea43-ecd8-4bf4-b500-6f97886ab77c | DELETE | /0e5f9f02-fa33-4c2c-a700-4235d752315b        |
            | gisele-berthoux@caramail.com          | delegated_b24fea43-ecd8-4bf4-b500-6f97886ab77c | POST   | /0e5f9f02-fa33-4c2c-a700-4235d752315b/image  |
            | gisele-berthoux@caramail.com          | delegated_b24fea43-ecd8-4bf4-b500-6f97886ab77c | DELETE | /0e5f9f02-fa33-4c2c-a700-4235d752315b/image  |

    Scenario: As connected user I can subscribe to an event
        Given I am logged with "benjyd@aol.com" via OAuth client "JeMengage Mobile" with scope "jemarche_app"
        When I send a "POST" request to "/api/v3/events/2b7238f9-10ca-4a39-b8a4-ad7f438aa95f/subscribe"
        Then the response status code should be 400
        And the JSON should be equal to:
            """
            { "message": "Cet événement est réservé aux adhérents, adhérez pour y participer." }
            """
        When I send a "POST" request to "/api/v3/events/594e7ad0-c289-49ae-8c23-0129275d128b/subscribe"
        Then the response status code should be 201
        And the JSON should be equal to:
            """
            "OK"
            """
        When I send a "DELETE" request to "/api/v3/events/594e7ad0-c289-49ae-8c23-0129275d128b/subscribe"
        Then the response status code should be 200
        And the JSON should be equal to:
            """
            "OK"
            """

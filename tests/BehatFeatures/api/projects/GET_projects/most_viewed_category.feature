@api @projects
Feature: Get most viewed projects

  Background:
    Given there are users:
      | id | name     | password |
      | 1  | Catrobat | 123456   |
      | 2  | User1    | 123456   |
      | 3  | User2    | 123456   |
      | 4  | User3    | 123456   |
    And there are projects:
      | id | name      | owned by | views | upload time      | FileSize | version | language version | flavor     | upload_language |
      | 1  | project 1 | Catrobat | 10    | 01.08.2014 12:00 | 1048576  | 0.8.5   | 0.999            | pocketcode | en              |
      | 2  | project 2 | User1    | 60    | 02.08.2014 12:00 | 1048576  | 0.8.5   | 0.982            | luna       | fr              |
      | 3  | project 3 | Catrobat | 5     | 03.08.2014 12:00 | 1048576  | 0.8.5   | 0.123            | pocketcode | de              |
      | 4  | project 4 | User2    | 50    | 04.08.2014 12:00 | 1048576  | 0.8.5   | 0.984            | luna       | en              |
      | 5  | project 5 | User1    | 40    | 05.08.2014 12:00 | 1048576  | 0.8.5   | 0.985            | pocketcode | de              |
      | 6  | project 6 | User1    | 20    | 02.08.2014 12:00 | 1048576  | 0.8.5   | 0.985            | luna       | fr              |


  Scenario: Get most viewed projects
    And I have a request header "HTTP_ACCEPT" with value "application/json"
    And I request "GET" "/api/projects/?category=most_viewed"
    Then the response status code should be "200"
    Then the response should have the default projects model structure
    Then the response should contain projects in the following order:
      | Name      |
      | project 2 |
      | project 4 |
      | project 5 |
      | project 6 |
      | project 1 |
      | project 3 |

  Scenario: Get most viewed projects in german and limit = 1
    And I have a request header "HTTP_ACCEPT" with value "application/json"
    And I have a request header "HTTP_ACCEPT_LANGUAGE" with value "de_DE"
    And I request "GET" "/api/projects/?category=most_viewed&limit=1"
    Then the response status code should be "200"
    Then the response should have the default projects model structure
    Then the response should contain projects in the following order:
      | Name      |
      | project 2 |

  Scenario: Get most viewed projects in english with offset = 1
    And I have a request header "HTTP_ACCEPT" with value "application/json"
    And I have a request header "HTTP_ACCEPT_LANGUAGE" with value "en"
    And I request "GET" "/api/projects/?category=most_viewed&offset=1"
    Then the response status code should be "200"
    Then the response should have the default projects model structure
    Then the response should contain projects in the following order:
      | Name      |
      | project 4 |
      | project 5 |
      | project 6 |
      | project 1 |
      | project 3 |

  Scenario: Get most viewed projects in french with max_version = 0.982
    And I have a request header "HTTP_ACCEPT" with value "application/json"
    And I have a request header "HTTP_ACCEPT_LANGUAGE" with value "fr_FR"
    And I request "GET" "/api/projects/?category=most_viewed&max_version=0.982"
    Then the response status code should be "200"
    Then the response should have the default projects model structure
    Then the response should contain projects in the following order:
      | Name      |
      | project 2 |
      | project 3 |

  Scenario: Get most viewed projects with flavor = luna
    And I have a request header "HTTP_ACCEPT" with value "application/json"
    And I request "GET" "/api/projects/?category=most_viewed&flavor=luna"
    Then the response status code should be "200"
    Then the response should have the default projects model structure
    Then the response should contain projects in the following order:
      | Name      |
      | project 2 |
      | project 4 |
      | project 6 |

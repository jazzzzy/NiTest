Feature: we should be able to call a products endpoint

  Scenario: call the invalid /products endpoint which responds not found
    When I send a GET request to "/products"
    Then the response status code should be 200
    And the response should contain "Battery 4"

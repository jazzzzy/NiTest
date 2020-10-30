Feature: we should be able to call a user product endpoint

  Scenario: call the valid /user/products endpoint which responds with 401
    When I send a GET request to "/user/products"
    Then the response should be in JSON
    And the response status code should be 401
    And the response should contain "JWT Token not found"

  Scenario: call the valid /user/products endpoint which responds with a list and with a buggy header handling
    When I send a GET request to "/user/products"
    And I add "Authorization" header equal to "Bearer XYZ"
    Then the header "Authorization" should contain "Bearer XYZ"

# DUE TO THE BUG ABOVE, FURTHER REQUESTS COULD NOT BE TESTED :( PROBABLY DIFFERENT TOOL SHOULD BE USED


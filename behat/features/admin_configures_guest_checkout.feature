
Feature: The Admin User can disable or enable guest checkout
  So the amount of charge backs can be minimized,
  as an Admin User,
  I want to be able to enable or disable the guest checkout.

  Scenario: Admin User disables guest checkout
    Given the Admin User has disabled guest checkout
      And the following products exist:
        | sku   | name           | is_in_stock | qty   |
        | test1 | Test Product 1 | 1           | 99999 |
    When I am not logged in as a customer
      And I add "test1" to the cart
      And I go the the checkout
    Then I am not able to check out as guest
      But I have the option to log in for checking out
      And I have the option to register during checkout

  Scenario: Admin User enables guest checkout
    Given the Admin User has enabled guest checkout
      And the following products exist:
        | sku   | name           | is_in_stock | qty   |
        | test1 | Test Product 1 | 1           | 99999 |
    When I am not logged in as a customer
      And I add "test1" to the cart
      And I go the the checkout
    Then I have the option to checkout as guest
      And I have the option to log in for checking out
      And I have the option to register during checkout

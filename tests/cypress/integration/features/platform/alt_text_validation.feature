
Feature: Alt-Text Validation
  In order to enhance the veteran experience
  As an editor
  I need just-in-time guidance as to best practices surrounding alt-text content

  Scenario: An editor supplies verbose alt-text content
    Given I am logged in as a user with the "administrator" role
    When I create an image with 152 characters of alt-text content
    Then I should see "Alternative text cannot be longer than 150 characters." as an error message

  Scenario: An editor supplies redundant alt-text content
    Given I am logged in as a user with the "administrator" role
    When I create an image with "Image of polygon" as alt-text
    Then I should see "Alternative text cannot contain phrases like “image of”, “photo of”, “graphic of”, “picture of”, etc." as an error message

  Scenario: An editor supplies the name of the image file as alt-text content
    Given I am logged in as a user with the "administrator" role
    When I create an image with "polygon_image.png" as alt-text
    Then I should see "Alternative text cannot contain file names." as an error message

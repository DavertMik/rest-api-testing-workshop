Feature: Post Publishing
  In order to update a website
  As a blog administrator
  I need to be able to publish a post

  Scenario: publish simple blogpost
    Given I am blog administrator
    When I enter admin dashboard
    And I create new post "Game Of Throne Spoilers"
"""
John Snow is not dead (yet)
But everyone others going yo die soon 
"""    
    And I publish it
    Then I should see it on site
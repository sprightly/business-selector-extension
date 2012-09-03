Feature: As a tester I want to be able to write Gherkin LIKE A BOSS.

@javascript     
Scenario: As a tester I want to interact with form elements using business terms.
Given I go to the page "Home Page" 
When I follow the link "Self Link"
And I press the "test" button
And I fill in the "first name box" field with "ben"
And the "first name box" form field should contain "ben"
And the "first name box" form field should not contain "testerson"
And I select "Female" from the "gender" selector
And I select "Volvo" from the "cars" selector
And I additionally select "Audi" from the "cars" selector
And the "adverts" should be checked
And the "terms" should not be checked
And I check the "terms" checkbox
And I uncheck the "adverts" checkbox
And I attach "cat.jpeg" to "file upload input"


@javascript
Scenario: As a tester I want to assert the presence of elements on the page using business terms
Given I go to the page "Element Page"
And I hover over "Widget"
Then the "Page" should contain "Area One Text"
And the "Widget" should contain "Area One Text"
And the "Widget Area Two" should contain "Area Two Nested Text"
And I should see "Widget Area Two" component
And I should not see "Widget Area Three" component
And "Widget" should contain "Widget Area Two"
And "Widget" should not contain "Widget Area Three"



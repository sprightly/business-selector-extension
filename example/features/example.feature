Feature: As a tester I want to be able to write Gherkin like a boss.

@javascript
Scenario: As a tester I want to test Wikipedia
Given I go to the page "Business name" 
When I follow the link "Business name"
When I press the "business name" button
When I fill in the "busines name" field with "value"
When I select "option" from the "business name" selector
When I additionally select "option" from the "business name" selector
When I check the "business name" checkbox
When I uncheck the "business name" checkbox
When I attach the file "value" to "business name"
Then I should see "business name" component
Then I should not see "business name" component
Then the "business name" should contain "text"
Then the "business name" should not contain "text"
Then "business name" should contain "business name"
Then "business name" should not contain "business name"
Then the "business name" form field should contain "value"
Then the "business name" form field should not contain "value"
Then the "business name" should be checked
Then the "business name" should not be checked

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
And I wait

@javascript
Scenario: As a tester I want to assert the presence of elements on the page using business terms
Given I go to the page "Element Page"
Then the "Page" should contain "Area One Text"
And the "Widget" should contain "Area One Text"
And the "Widget Area Two" should contain "Area Two Nested Text"
And I should see "Widget Area Two" component
And I should not see "Widget Area Three" component
And "Widget" should contain "Widget Area Two"
And "Widget" should not contain "Widget Area Three"
And I wait


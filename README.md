# Business Selectors For Behat

## Introduction 

The Mink Extension for Behat is an excellent way to quickly get up and running 
with web acceptance testing. It allows the user to specify HTML elements on the
page using common attributes like 'title', 'alt' and 'value'. 

While this approach is great it has a few draw backs: 

1) The attribute values may not always be the most business friendly and can 
reduce the readability of the Gherkin scenarios.

2) Sometimes it isn't possible to identify an element using the attributes 
the MinkExtension provides. 

3) By using these attributes directly in the Gherkin files, they become tightly 
coupled to the front end implementation. Gherkin should express business      

Business Selectors for Behat allow the user to use steps very similar to the 
ones provided by the MinkExtension to describe elements on the page in business
terms. At the time the test is run the business terms are swapped for a CSS 
selector which is maintained in a yml file.  

### Example

#### Gherkin Written With Steps Provided By MinkExtension



#### Gherkin Written With Steps Provided By BusinessSelectorsExtension

````cucumber
Given I go to the page "Home Page" 
When I follow the link "Add Subscription"
And I press the "test" button
And I fill in the "first name box" field with "ben"
Then the "first name box" form field should contain "ben"
And the "Widget" should contain "Area One Text"
````

## Installation

The best way to install the contexts is by using composer. 

1) For instructions on installing Behat see: [behat.org][http://behat.org/]

2) Add in the following to the composer.json of the project you'd like to test.

````javascript
{   
    "minimum-stability": "dev",
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/orangedigital/business-selector-extension"
        }
    ],
    "require": {
        "behat/behat": ">v2.4@stable",
        "orangedigital/businessselectorextension": "*"
    }
}
````

3) Add the OrangeDigital\BusinessSelectorExtension\Extension section to your
behat.yml file as in the example below.

````yaml

default:
    context:
        parameters:
            javascript_session: selenium
            browser: firefox
            show_cmd: open %s
    extensions:
        OrangeDigital\BusinessSelectorExtension\Extension:
          urlFilePath: urls.yml
          selectorFilePath: selectors.yml
          assetPath: path
          contexts:
            UiBusinessSelector: ~
        Behat\MinkExtension\Extension:
            base_url: "http://local.dev"
            default_session: goutte
            goutte: ~
            selenium: ~
            selenium2: ~

````

4) Amend your FeatureContext file to use the BusinessSelectorContext file. 

````php 
<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext,
    OrangeDigital\BusinessSelectorExtension\Context\BusinessSelectorContext;

class FeatureContext extends BehatContext
{
    public function __construct(array $parameters)
    {
        $this->useContext('mink', new MinkContext($parameters));
        $this->useContext('BusinessSelectors', new BusinessSelectorContext($parameters));
    }
}


````
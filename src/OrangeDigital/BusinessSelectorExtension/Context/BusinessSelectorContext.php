<?php
namespace OrangeDigital\BusinessSelectorExtension\Context;

use Behat\Mink\Element\NodeElement;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Mink\Mink;
use OrangeDigital\BusinessSelectorExtension\Exception\ElementNotFoundException;
use Symfony\Component\Yaml\Parser;

/**
 * This is exposes a number of steps which allow the user to swap business terms
 * specified in a Gherkin file with CSS selectors specified in URL and Selector
 * files. 
 * 
 * @author Ben Waine <ben.waine@orange.com>
 * @author Phill Hicks <phillip.hicks@orange.com>  
 */
class BusinessSelectorContext implements MinkAwareContext
{
    /**
     * Context Parameters
     *
     * @var array
     */
    protected $parameters;

    /**
     * Mink Instance
     *
     * @var Mink
     */
    protected $mink;

    /**
     * Mink Parameters
     *
     * @var array
     */
    protected $minkParameters;

    /**
     * @Given /^I go to the page "([^"]*)"$/
     * @param $pageName
     */
    public function iGoToThePage($pageName)
    {

        $page = $this->getUrlFromString($pageName);

        $url = $this->getUrl($page);

        $this->getSession()->visit($url);
    }

    /**
     * @When /^I follow the link "([^"]*)"$/
     * @When /^I press the "([^"]*)" button$/
     * @param $elementName
     * @throws ElementNotFoundException
     */
    public function iFollowTheLink($elementName)
    {

        $element = $this->findElementWithBusinessSelector($elementName);

        $element->click();
    }

    /**
     * @When /^I fill in the "([^"]*)" field with "([^"]*)"$/
     * @param $elementName
     * @param $value
     * @throws ElementNotFoundException
     */
    public function iFillInTheFieldWith($elementName, $value)
    {
        $element = $this->findElementWithBusinessSelector($elementName);

        $element->setValue($value);
    }

    /**
     * @When /^I select "([^"]*)" from the "([^"]*)" selector$/
     * @param $value
     * @param $elementName
     * @throws ElementNotFoundException
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iSelectFromTheSelector($value, $elementName)
    {
        $element = $this->findElementWithBusinessSelector($elementName);

        $element->selectOption($value);
    }

    /**
     * @When /^I additionally select "([^"]*)" from the "([^"]*)" selector$/
     * @param $value
     * @param $elementName
     * @throws ElementNotFoundException
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iAdditionallySelectFromTheSelector($value, $elementName)
    {
        $element = $this->findElementWithBusinessSelector($elementName);

        $element->selectOption($value, true);
    }

    /**
     * @When /^I check the "([^"]*)" checkbox$/
     * @param $elementName
     * @throws ElementNotFoundException
     */
    public function iCheckTheCheckbox($elementName)
    {
        $element = $this->findElementWithBusinessSelector($elementName);

        if (!$element->isChecked()) {
            $element->check();
        }
    }

    /**
     * @When /^I uncheck the "([^"]*)" checkbox$/
     * @param $elementName
     * @throws ElementNotFoundException
     */
    public function iUnCheckTheCheckbox($elementName)
    {
        $element = $this->findElementWithBusinessSelector($elementName);

        if ($element->isChecked()) {
            $element->uncheck();
        }
    }

    /**
     * @Then /^the "([^"]*)" form field should contain "([^"]*)"$/
     * @param $elementName
     * @param $value
     * @throws ElementNotFoundException
     */
    public function theFormFieldShouldContain($elementName, $value)
    {
        $element = $this->findElementWithBusinessSelector($elementName);

        $text = $element->getValue();

        if ($text != $value) {
            throw new \RuntimeException("'$value' does not match expected '$text'");
        }
    }

    /**
     * @Then /^the "([^"]*)" form field should not contain "([^"]*)"$/
     * @param $elementName
     * @param $value
     * @throws ElementNotFoundException
     */
    public function theFormFieldShouldNotContain($elementName, $value)
    {
        $element = $this->findElementWithBusinessSelector($elementName);

        $text = $element->getValue();

        if ($text == $value) {
            throw new \RuntimeException("'$value' does not match expected '$text'");
        }
    }

    /**
     * @Then /^the "([^"]*)" should be checked$/
     * @param $elementName
     * @throws ElementNotFoundException
     */
    public function theShouldBeChecked($elementName)
    {
        $element = $this->findElementWithBusinessSelector($elementName);

        if (!$element->isChecked()) {
            throw new \RuntimeException("$elementName is not checked");
        }
    }

    /**
     * @Then /^the "([^"]*)" should not be checked$/
     * @param $elementName
     * @throws ElementNotFoundException
     */
    public function theShouldNotBeChecked($elementName)
    {
        $element = $this->findElementWithBusinessSelector($elementName);

        if ($element->isChecked()) {
            throw new \RuntimeException("$elementName is checked");
        }
    }

    /**
     * @Given /^I attach "([^"]*)" to "([^"]*)"$/
     * @param $file
     * @param $elementName
     * @throws ElementNotFoundException
     */
    public function iAttachTo($file, $elementName)
    {
        $element = $this->findElementWithBusinessSelector($elementName);

        $path = $this->parameters['assetPath'] . $file;

        $rPath = realpath($path);

        if (!file_exists($rPath)) {
            throw new \RuntimeException("File: $rPath does not exist");
        }

        $element->attachFile($rPath);

    }

    /**
     * @When /^I hover over "([^"]*)"$/
     * @param $elementName
     * @throws ElementNotFoundException
     */
    public function iHoverOver($elementName)
    {
        $element = $this->findElementWithBusinessSelector($elementName);

        $element->mouseOver();
    }

    /**
     * @When /^I focus on the "([^"]*)" iframe$/
     * @param $elementName
     */
    public function iFocusOnTheIframe($elementName)
    {
        $element = $this->getSelectorFromString($elementName);

        $session = $this->getSession();

        $session->switchToIFrame($element);
    }

    /**
     * @When /^I refocus on the primary page$/
     */
    public function iRefocusOnThePrimaryPage()
    {
        $session = $this->getSession();

        $session->switchToIFrame();
    }


    /**
     * @Then /^I should see "([^"]*)" component$/
     * @param $elementName
     * @throws ElementNotFoundException
     */
    public function iShouldSeeComponent($elementName)
    {
        $this->findElementWithBusinessSelector($elementName);
    }

    /**
     * @Then /^I should not see "([^"]*)" component$/
     * @param $elementName
     */
    public function iShouldNotSeeComponent($elementName)
    {

        try {
            $result = $this->findElementWithBusinessSelector($elementName);

            if (!is_null($result)) {
                throw new \RuntimeException("Component $elementName found");
            }
        } catch (ElementNotFoundException $e) {
            return;
        }
    }

    /**
     * @Then /^the "([^"]*)" should contain "([^"]*)"$/
     * @param $elementName
     * @param $text
     * @throws ElementNotFoundException
     */
    public function theShouldContain($elementName, $text)
    {
        $element = $this->findElementWithBusinessSelector($elementName);

        $actualText = $element->getText();

        if (strpos($actualText, $text) === false) {
            throw new \RuntimeException("'$text' not found in $elementName");
        }
    }

    /**
     * @Then /^the "([^"]*)" should not contain "([^"]*)"$/
     * @param $elementName
     * @param $text
     * @throws ElementNotFoundException
     */
    public function theShouldNotContain($elementName, $text)
    {
        $element = $this->findElementWithBusinessSelector($elementName);

        $actualText = $element->getText();

        if (strpos($actualText, $text) !== false) {
            throw new \RuntimeException("'$text' found in $elementName");
        }
    }

    /**
     * @Then /^"([^"]*)" should contain "([^"]*)"$/
     * @param $elementNameOutter
     * @param $elementNameInner
     * @throws ElementNotFoundException
     */
    public function shouldContain($elementNameOutter, $elementNameInner)
    {
        $scopeElement = $this->findElementWithBusinessSelector($elementNameOutter);
        $this->findElementWithBusinessSelector($elementNameInner, $scopeElement);
    }

    /**
     * @Then /^"([^"]*)" should not contain "([^"]*)"$/
     * @param $elementNameOutter
     * @param $elementNameInner
     * @throws ElementNotFoundException
     */
    public function shouldNotContain($elementNameOutter, $elementNameInner)
    {

        $scopeElement = $this->findElementWithBusinessSelector($elementNameOutter);

        try {
            $result = $this->findElementWithBusinessSelector($elementNameInner, $scopeElement);

            if (!is_null($result)) {
                throw new \RuntimeException("Element $elementNameInner found in $elementNameOutter");
            }
        } catch (ElementNotFoundException $e) {
            return;
        }
    }

    /**
     * Checks in the selector yaml file for a selector which matches the
     * supplied business friendly string.
     *
     * @param $string
     * @return string
     */
    public function getSelectorFromString($string)
    {

        $selectors = $this->getSelectorHash();

        if (!array_key_exists($string, $selectors)) {
            throw new \RuntimeException('Selector: ' . $string . ' not found in selectors file');
        }

        return $selectors[$string];
    }

    /**
     * Checks in the URL yaml file for a URL which matches the supplied
     * bussiness friendly string.
     *
     * @param $string
     * @return string
     */
    public function getUrlFromString($string)
    {

        $urls = $this->getURLHash();

        if (!array_key_exists($string, $urls)) {
            throw new \RuntimeException('URL: ' . $string . ' not found in urls file');
        }

        return $urls[$string];
    }

    /**
     * Returns an array of businesTerm > Selectors.
     * Checks if they have been loaded and loads them if not.
     *
     * @return array
     */
    protected function getSelectorHash()
    {
        if (!isset($this->selectors)) {
            // Load Selectors from file

            $path = $this->parameters['selectorFilePath'];

            if (!$path) {
                throw new \RuntimeException('Value "selectorFilePath not set in config"');
            }

            $this->selectors = $this->loadYaml($path);
        }

        return $this->selectors;
    }

    /**
     * Returns an array of businesTerm > URLs.
     * Checks if they have been loaded and loads them if not.
     *
     * @return array
     */
    protected function getURLHash()
    {

        if (!isset($this->urls)) {
            // Load Selectors from file

            $path = $this->parameters['urlFilePath'];

            if (!$path) {
                throw new \RuntimeException('Value "urlFilePath not set in config"');
            }

            $this->urls = $this->loadYaml($path);
        }

        return $this->urls;
    }

    /**
     * Loads yaml and returns the result
     *
     * @param string $path
     *
     * @return array
     */
    protected function loadYaml($path)
    {
        if (!file_exists($path)) {
            throw new \RuntimeException('File: ' . $path . ' does not exist');
        }

        $parser = new Parser();

        $string = file_get_contents($path);

        $result = $parser->parse($string);

        if (!$result) {
            throw new \RuntimeException('Unable to parse ' . $path);
        }

        return $result;
    }

    /**
     * Gets the current Mink session.
     *
     * @return \Behat\Mink\Session
     */
    protected function getSession()
    {
        return $this->mink->getSession();
    }

    /**
     * Returns a fully qualified URL using the base URL passed to Mink.
     *
     * @param string $frag
     *
     * @return string
     */
    protected function getUrl($frag = null)
    {
        $url = $this->minkParameters['base_url'];

        if (!is_null($frag) && $frag != '/') {
            $url = $url . $frag;
        }

        return $url;
    }

    /**
     * Finds an element from the current page using the supplied business
     * selector.
     *
     * @param string $elementName
     * @param NodeElement $scopeElement If passed search is conducted within the supplied element.
     *
     * @return NodeElement|null
     * @throws ElementNotFoundException
     */
    protected function findElementWithBusinessSelector($elementName, $scopeElement = null)
    {

        $selector = $this->getSelectorFromString($elementName);

        if (is_null($scopeElement)) {
            $session = $this->getSession();
            $scopeElement = $session->getPage();
        }

        $result = $scopeElement->find('css', $selector);

        if (is_null($result)) {
            throw new ElementNotFoundException("Element $elementName not found");
        }

        return $result;
    }

    /**
     * Sets Mink instance.
     *
     * @param Mink $mink Mink session manager
     */
    public function setMink(Mink $mink)
    {
        $this->mink = $mink;
    }

    /**
     * Sets parameters provided for Mink.
     *
     * @param array $parameters
     */
    public function setMinkParameters(array $parameters)
    {
        $this->minkParameters = $parameters;
    }

    /**
     * @param array $parameters
     *
     * @return void
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }
}


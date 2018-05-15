<?php
namespace OrangeDigital\BusinessSelectorExtension; 

use Behat\Behat\Context\Context as ContextInterface;
use Behat\Behat\Context\Initializer\ContextInitializer;
use OrangeDigital\BusinessSelectorExtension\Context\BusinessSelectorContext;


/**
 * Initializer used by Behat to initialise the BusinessSelector contexts.
 * 
 * @author Ben Waine <ben.waine@orange.com>
 * @author Phill Hicks <phillip.hicks@orange.com>  
 */
class Initializer implements ContextInitializer
{
    /**
     * Config Parameters from behat.yml.
     * 
     * @var array
     */
    private $parameters;
    
    /**
     * Initialises an instance of the Initializer class.
     * 
     * @param array $parameters 
     * 
     * @return void
     */
    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Method passes the parameters to the context.
     * 
     * @param ContextInterface $context
     * 
     * @return void 
     */
    public function initializeContext(ContextInterface $context)
    {
        if (!$context instanceof BusinessSelectorContext) {
            return;
        }

        $context->setParameters($this->parameters);
    }
}
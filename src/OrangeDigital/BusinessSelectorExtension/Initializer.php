<?php
namespace OrangeDigital\BusinessSelectorExtension; 

use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Context\Initializer\InitializerInterface;
use OrangeDigital\BusinessSelectorExtension\Context\BusinessSelectorContext;


/**
 * Initializer used by Behat to initialise the BusinessSelector contexts.
 * 
 * @author Ben Waine <ben.waine@orange.com>
 * @author Phill Hicks <phillip.hicks@orange.com>  
 */
class Initializer implements InitializerInterface
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
     * Informs Behat as to whether the supplied context is supported.
     * 
     * @param ContextInterface $context
     * 
     * @return Boolean 
     */
    public function supports(ContextInterface $context)
    {
        return ($context instanceof BusinessSelectorContext);
    }

    /**
     * Method passes the parameters to the context.
     * 
     * @param ContextInterface $context
     * 
     * @return void 
     */
    public function initialize(ContextInterface $context)
    {
        $context->setParameters($this->parameters);
    }
}
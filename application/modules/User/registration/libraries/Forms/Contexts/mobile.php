<?php
/**
 * Default context File
 */
namespace registration\libraries\Forms\Contexts;

/**
 * Default context class
 */ 
class mobile extends AbstractContext
{
    /**
     * Constructor
     */ 
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Apply the context to field settings
     *
     * @param array $fieldSettings
     * @return array
     */ 
    public function apply($fieldSettings)
    {
        unset($fieldSettings['securityCode']);
        return $fieldSettings;
    }
}
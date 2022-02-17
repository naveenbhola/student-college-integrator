<?php
/**
 * Unified context File
 */

namespace registration\libraries\Forms\Contexts;

/**
 * Unified context class
 */
class NonLDBLoggedInUser extends AbstractContext
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
        unset($fieldSettings['name']);
        unset($fieldSettings['email']);
        unset($fieldSettings['securityCode']);
        return $fieldSettings;
    }
}
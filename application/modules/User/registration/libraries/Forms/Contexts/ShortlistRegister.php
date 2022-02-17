<?php
/**
 * Register Free context file
 */ 
namespace registration\libraries\Forms\Contexts;

/**
 * Register Free context class
 */ 
class ShortlistRegister extends AbstractContext
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
    public function apply($fieldSettings) {
        return $fieldSettings;
    }
}
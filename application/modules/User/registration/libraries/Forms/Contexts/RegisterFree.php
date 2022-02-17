<?php
/**
 * Register Free context file
 */ 
namespace registration\libraries\Forms\Contexts;

/**
 * Register Free context class
 */ 
class RegisterFree extends AbstractContext
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
        return $fieldSettings; //for new common registration
        
        $fieldSettings['age'] = array('type' => 'textbox','visible' => 'Yes','mandatory' => 'No');
        $fieldSettings['gender'] = array('type' => 'radio','visible' => 'Yes','mandatory' => 'No');
        return $fieldSettings;
    }
}
<?php
/**
 * Online form context File
 */ 
namespace registration\libraries\Forms\Contexts;

/**
 * Online form context class
 */ 
class OnlineForm extends AbstractContext
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
        $fieldSettings['password'] = array('type' => 'textbox','visible' => 'Yes','mandatory' => 'No');
        $fieldSettings['confirmPassword'] = array('type' => 'textbox','visible' => 'Yes','mandatory' => 'No');
        return $fieldSettings;
    }
}
<?php
/**
 * Short Registration context file
 */
namespace registration\libraries\Forms\Contexts;

/**
 * Short Registration context class
 */ 
class ShortRegistration extends AbstractContext
{
    public static $shortRegistrationFields = array('firstName','lastName','email','mobile','securityCode');
    
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
        $newFieldSettings = array();
        foreach($fieldSettings as $fieldId => $settings) {
            if(in_array($fieldId,self::$shortRegistrationFields)) {
                $newFieldSettings[$fieldId] = $settings;    
            }
        }
        
        return $newFieldSettings;
    }
}
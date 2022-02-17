<?php
/**
 * Find Institute context file
 */
namespace registration\libraries\Forms\Contexts;

/**
 * Find Institute context class
 */ 
class FindInstitute extends AbstractContext
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
        $fields = array('fieldOfInterest','desiredCourse', 'isdCode');
        foreach($fieldSettings as $fieldId => $settings) {
            if(!in_array($fieldId,$fields)) {
                unset($fieldSettings[$fieldId]);
            }
        }
        return $fieldSettings;
    }
}
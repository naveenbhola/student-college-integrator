<?php
/**
 * Abstract context class file
 */ 
namespace registration\libraries\Forms\Contexts;

/**
 * Abstract context class
 */ 
abstract class AbstractContext
{
    protected $CI;
    
    /**
     * Constructor
     */ 
    function __construct()
    {
        $this->CI = & get_instance();
    }
    
    /**
     * Apply the context to field settings
     *
     * @param array $fieldSettings 
     */ 
    abstract public function apply($fieldSettings);
}

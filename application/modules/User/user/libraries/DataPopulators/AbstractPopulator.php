<?php
/**
 * Abstract user data populator
 */ 
namespace user\libraries\DataPopulators;

/**
 * Abstract user data populator
 */ 
class AbstractPopulator
{
    /**
     * @var object CodeIgniter object
     */ 
    protected $CI;
    
    /**
     * @var object User repository \user\Repositories\UserRepository
     */ 
    protected $repository;
    
    /**
     * @var array Data to be populated in
     */ 
    protected $data;
    
    /**
     * @var object Database handle
     */ 
    protected $dbHandle;
    
    /**
     * @var string Mode - new user or update old user (create|update)
     */ 
    protected $mode;
    
    /**
     * Constructor
     *
     * @param string $mode
     */
    function __construct($mode)
    {
        $this->repository = \user\Builders\UserBuilder::getUserRepository();
        $this->CI = & get_instance();
        $this->CI->load->library('dbLibCommon');
        
        $instance = \DbLibCommon::getInstance('User',true);
        $this->dbHandle = $instance->getReadHandle();
        $this->mode = $mode;
    }
    
    /**
     * Check if it's new user creation mode
     *
     * @return bool
     */ 
    protected function isCreation()
    {
        return $this->mode == 'create';
    }
    
    /**
     * Check if it's user updation mode
     *
     * @return bool
     */
    protected function isUpdation()
    {
        return $this->mode == 'update';
    }
    
    /**
     * Set data
     *
     * @param array $data
     */
    protected function setData($data)
    {
        $this->data = $data;
    }
    
    /**
     * Check whether a particular key can be set
     * In "create" mode, all keys can be set
     * In "update" mode, only the keys existing in provided data can be set
     *
     * @param string $key Key in data (represents a column in one of the user tables)
     * @return bool
     */ 
    protected function canSet($key)
    {
        if($this->isUpdation() && !array_key_exists($key,$this->data)) {
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * Check that required fields are present alongwith some value in data provided
     *
     * @param array $requiredFields Required fields
     * @throws Exception when required field is not found or found but has no value
     */ 
    protected function checkRequiredFields($requiredFields)
    {
        foreach($requiredFields as $requiredField) {
            if(!trim($this->data[$requiredField])) {
                throw new \Exception('Missing required field: '.$requiredField);
            }
        }
    }
    
    /**
     * Get value of a field in data provided
     *
     * @param string $key Field
     * @param mixed $default Default value to set when field is not found
     * @return mixed
     */ 
    protected function getValue($key,$default = NULL)
    {
        if(array_key_exists($key,$this->data)) {
            return $this->data[$key];
        }
        else {
            return $default;
        }
    }
}
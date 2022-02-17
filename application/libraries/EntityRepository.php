<?php

class EntityRepository
{
    protected $CI;
    protected $dao;
    protected $cache;
    protected $caching = TRUE;
    protected $model;
    protected $_entity;
    protected $outputFormat;
    
    function __construct($dao = NULL,$cache = NULL,$model = NULL)
    {
        $this->CI          = & get_instance();
        
        $this->dao         = $dao;
        $this->cache       = $cache;
        $this->model       = $model;
    }
    
    protected function fillObjectWithData($object,$data)
    {
        if(is_array($data)) {
            $reflect = new ReflectionClass($object);
        
            foreach($data as $key => $value) {
                if($reflect->hasProperty($key)) {
                    //$reflectionProperty = $reflect->getProperty($key);
                    //$reflectionProperty->setAccessible(true);
                    //$reflectionProperty->setValue($object, $value);
                    $object->$key = $value;
                }
            }
            
            if(is_array($data['entities'])) {
                foreach($data['entities'] as $entityKey => $entity) {
                    if($reflect->hasProperty($entityKey)) {
                        //$reflectionProperty = $reflect->getProperty($entityKey);
                        //$reflectionProperty->setAccessible(true);
                        //$reflectionProperty->setValue($object, $entity);
                        $object->$entityKey = $entity;
                    }
                }
            }
        }
    }
    
    public function setDao($dao)
    {
        $this->dao = $dao;
    }
    
    public function setCache($cache)
    {
        $this->cache = $cache;
    }
    
    public function disableCaching()
    {
        $this->caching = FALSE;
    }
    
    public function enableCaching()
    {
        $this->caching = TRUE;
    }
    
    public function populateErrorObject () {
    	$error_object = new stdClass();
    	$error_object->ERROR_MESSAGE = 'NO_DATA_FOUND';
    	return $error_object;
    }
}

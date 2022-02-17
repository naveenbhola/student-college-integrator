<?php

/**
 * Provides the base repository functionality (such as find, findAll etc.) for Shiksha Listings
 *
 * @version ShikshaRecatV1.0
 * @author Shiksha Listings Team
 *
 */
class ListingBaseRepository extends EntityRepository{
    private $fieldNameToEntityFunctionMapping = array('name' => 'getName', 
                                                      'synonym' => 'getSynonym', 
                                                      'alias' => 'getAlias');
    // public $enableCaching = false;
    private $objectStore = array();
	function __construct($cache,$model){        
        parent::__construct(null,$cache,$model);

        $this->model = $model;
        $this->cache = $cache;
        $this->CI->load->helper('shikshautility_helper');
    }

    public function setEntity($entity){
        $this->_entity = $entity;
    }

    public function getEntity(){
        return $this->_entity;
    }

    public function findMultiple($ids){
        $entity = $this->getEntity();
        if(!is_object($entity)){
            return false;
        }

        $entityName = get_class($entity);        
        Contract::mustBeNonEmptyArrayOfIntegerValues($ids,$entityName);
      
        $result =array();
        $notFoundIds = array();
        foreach ($ids as $id) {
            if(empty($this->objectStore[$entityName.'_'.$id])){
                $notFoundIds[] = $id;
            }
        }

        if(!empty($notFoundIds)){
            $data = array_filter($this->cache->getMultipleData($notFoundIds));
            $missingIds = array_diff($notFoundIds,array_keys($data));
            if(!empty($missingIds)){
                $entityTable        = from_camel_case($entityName);   
                $dataFromDb               = $this->model->getDataFromTable($entityTable,$missingIds);
                $this->cache->setData($dataFromDb);
                foreach ($dataFromDb as $key => $value) {
                    $data[$key] = $value;
                }
            }
            foreach ($notFoundIds as $id) {
                if(!empty($data[$id])){
                    $output = $this->_load(array($data[$id]),$entityName);  
                    $this->objectStore[$entityName.'_'.$id] = $output[0];
                }
            }
        }

        foreach ($ids as $id) {
            if(!empty($this->objectStore[$entityName.'_'.$id])){
                $result[$id] = $this->objectStore[$entityName.'_'.$id];
            }
        }
        
        return $result;
    }   


    public function findAll(){
        $entity = $this->getEntity();
        if(!is_object($entity)){
            return false;
        }
        $entityName = get_class($entity);

        $this->cache->setEntity($entityName);

        $entityTable        = from_camel_case($entityName);   
        $data               = $this->model->getDataFromTable($entityTable);

        $output = $this->_load($data);

        return $output;      
    }

    private function _load($results,$entityName)
    {
        $entitiesObj = array();      
        if(is_array($results) && count($results))
        {
            foreach($results as $result)
            {
                $entityObj = $this->_createEntityObject($result,$entityName);
                $entitiesObj[] = $entityObj;
            }
        }                
        return $entitiesObj;
    }

    private function _createEntityObject($result,$entityName){

        $newObject = clone $this->_entity;
        switch ($entityName) {
            case 'BaseCourses':
                $result['credential'] = array($result['credential_1'],$result['credential_2']);
                $result['credential'] = array_filter($result['credential']);
                break;
        }
        $this->fillObjectWithData($newObject,$result);
        return $newObject;
    }

    /**
     * Find values corresponding to the input field names. If no field is mentioned, the value of all the fields are obtained.
     *
     * @author Ankit Garg <g.ankit@shiksha.com>
     * @date   2016-06-20
     *
     * @param    int|array   $ids
     * @param array $fields
     *
     * @return array An associative array with key as certificate provider
     */
    function getFields($ids, array $fields = array()) {
        if(empty($fields)) {
            return $this->find($ids);
        }
        $data = array();
        $resultsData = $this->find($ids);
        foreach($resultsData as $id => $result) {
            $data[$id] = array();
            foreach($fields as $fieldName) {
                $functionName = $this->fieldNameToEntityFunctionMapping[$fieldName];
                if(!empty($functionName) && is_object($result)) {
                    $data[$id][$fieldName] = $result->$functionName();
                }
            }
        }
        return $data;
    }

}
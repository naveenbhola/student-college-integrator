<?php

class scholarshipRepository extends EntityRepository
{
    private $useCache = true;
    function __construct($dao,$cache){
        parent::__construct($dao,$cache);
        /*
         * Load entities required
         */
        $this->CI->load->entities(array('scholarship'),'scholarshipsDetailPage');	
        $this->CI->load->domainClasses(array('scholarshipEligiblity',
                                            'scholarshipAmount',
                                            'scholarshipApplicationData',
                                            'scholarshipDeadLine',
                                            'scholarshipHierarchy',
                                            'scholarshipSpecialRestriction'),'scholarshipsDetailPage');
        $this->useCache = $this->useCache && $this->caching; // to make a distintion between turning cache off for only scholarship and turning cache off globally.
        $this->CI->load->config('scholarshipsDetailPage/scholarshipConfig');
    }

    /**
     * 
     *
     * @param integer $scholarshipId scholarship id
     * @param array $sections: 1. pass $sections = 'full' for fetching the full object
     *                         2. pass $sections = array('basic'=>array('scholarshipId','name'),
     *                                                   'eligibility'=>array('full')
     *                                                  )
     *                                            If passed fields of a section contain 'full', then all the fields of that sections
     *                                            will be fetched, otherwise passed fields will be fetched.
     * @return Object $scholarshipObj
     */
    function find($scholarshipId,$sections=''){
        
        Contract::mustBeNumericValueGreaterThanZero($scholarshipId,'Scholarship ID');
        $this->_validateSections($sections);
        $fields = $this->_getFieldsBySections($sections);
        if($this->useCache && $scholarshipData = $this->cache->getScholarshipData($scholarshipId,$fields)){
            $scholarshipObj = $this->_populateScholarshipObject($scholarshipData,$fields);
            return $scholarshipObj;
        }
        
        $fields = $this->_getFieldsBySections();
        
        $scholarshipData = $this->dao->getData($scholarshipId);
        if(!is_null($scholarshipData['totalAmountPayout'])){
            
            $abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
            $scholarshipData['convertedTotalAmountPayout'] = round($abroadCommonLib->convertCurrency($scholarshipData['amountCurrency'],1,$scholarshipData['totalAmountPayout'])); //1 is currency code for INR
        }
        //save data in cache
        $scholarshipObj = false;
        if(!empty($scholarshipData['scholarshipId'])){
            $this->cache->storeScholarshipData($scholarshipId,$scholarshipData);
            $scholarshipObj = $this->_populateScholarshipObject($scholarshipData,$fields);
        }
        return $scholarshipObj;
    }
    /**
     * return scholarship objects indexed with scholarship id
     *
     * @param array scholarship id
     * @param $section : for details check comments above find() function
     * @return array of Objects
     */
    public function findMultiple($scholarshipIds,$sections=''){
        Contract::mustBeNonEmptyArrayOfIntegerValues($scholarshipIds,'Scholarship IDs');
        $this->_validateSections($sections);
        $fields = $this->_getFieldsBySections($sections);
        $scholarshipObjs = array();
        if($this->useCache && $multipleScholarshipsData = $this->cache->getMultipleScholarshipsData($scholarshipIds,$fields)){
            $scholarshipNotFoundInCache = array_diff($scholarshipIds,array_keys($multipleScholarshipsData));
            foreach($multipleScholarshipsData as $key=>&$scholarshipData){
                $scholarshipObjs[$scholarshipData['scholarshipId']] = $this->_populateScholarshipObject($scholarshipData,$fields);
                unset($multipleScholarshipsData[$key]);
            }
            if(empty($scholarshipNotFoundInCache)){
                return $scholarshipObjs;
            }
            $scholarshipIds = $scholarshipNotFoundInCache;
        }
        $fields = $this->_getFieldsBySections();
        
        $multipleScholarshipsData = $this->dao->getDataForMultipleScholarships($scholarshipIds);
        $abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
        foreach($multipleScholarshipsData as $key=>&$scholarshipData){
            if(!is_null($scholarshipData['totalAmountPayout'])){
                $scholarshipData['convertedTotalAmountPayout'] = round($abroadCommonLib->convertCurrency($scholarshipData['amountCurrency'],1,$scholarshipData['totalAmountPayout'])); // 1 is currency code for INR
            }
            if(!empty($scholarshipData['scholarshipId'])){
                $scholarshipObjs[$scholarshipData['scholarshipId']] = $this->_populateScholarshipObject($scholarshipData,$fields);
            }
        }
        //save data in cache
        $this->cache->storeMultipleScholarshipsData($scholarshipIds,$multipleScholarshipsData);
        return $scholarshipObjs;
    }


    private function _validateSections(&$sections){

        global $scholarshipSections;

        if($sections == 'full'){
            $sections = $scholarshipSections;
        }
        else if($sections == ''){
            $sections = array('basic'=>array('scholarshipId','name'));	
        }
        
        if(!in_array('basic', array_keys($sections))){
            $sections['basic'] = array('scholarshipId','name');
        }
    }
    
    private function _populateScholarshipObject(&$scholarshipData,&$fields){
        $objectData = array();
        global $scholarshipFieldToSectionMapping;
        foreach($fields as $field){
            switch ($scholarshipFieldToSectionMapping[$field]) {
                case 'eligibility':
                case 'application':
                case 'amount':
                case 'specialRestrictions':
                case 'hierarchy':
                case 'deadline':
                    if(!isset($scholarshipData[$field])){
                        $scholarshipData[$field] = '';    // this is done to make a difference between field not requested from repo and field had no data in redis/db
                    }
                    $this->_addFieldToChild($scholarshipFieldToSectionMapping[$field],$field,$scholarshipData[$field],$objectData);
                    break;
                case 'basic':
                    if(!isset($scholarshipData[$field])){
                        $scholarshipData[$field] = '';   // this is done to make a difference between field not requested from repo and field had no data in redis/db
                    }
                    $objectData[$field] = $scholarshipData[$field];
                    break;
            }
        }
        $objectData['subscriptionType'] = 'free'; //all scholarships are free
        $scholarshipObject = new scholarship();
        $this->fillObjectWithData($scholarshipObject, $objectData);
        
        return $scholarshipObject;
    }
    private function _getFieldsBySections($sections){
        $fields = array();
        global $scholarshipSectionToFieldsMapping;
        if(empty($sections)){
            global $scholarshipSections;
            $sections  = $scholarshipSections;
        }
        foreach($sections as $sectionName=>$sectionFields){
            if(is_array($sectionFields) && !in_array('full', $sectionFields)){
                $fields = array_merge($fields,$sectionFields);
            }
            else{
                $fields = array_merge($fields,$scholarshipSectionToFieldsMapping[$sectionName]);
            }
        }
        if(!in_array('scholarshipId', $fields)){
            $fields[] = 'scholarshipId';
        }
        // 'allCategorizations' is stored as a different enum in DB
        if(!in_array('allCategorizations', $fields) && 
            in_array('courseLevel', $fields) || 
            in_array('parentCategory', $fields) ||
            in_array('subcategory', $fields) ||
            in_array('specialization', $fields)
          ){
            $fields[] = 'allCategorizations';
          }
        return $fields;
    }
    
    private function _addFieldToChild(&$childName,&$childField,&$childFieldData,&$objectData){
        switch ($childName) {
            case 'eligibility':
                if(!is_object($objectData[$childName])){
                    $objectData[$childName] = new scholarshipEligiblity();
                }
                break;
            case 'application':
                if(!is_object($objectData[$childName])){
                    $objectData[$childName] = new scholarshipApplicationData();
                }
                break;
            case 'amount':
                if(!is_object($objectData[$childName])){
                    $objectData[$childName] = new scholarshipAmount();  
                }
                break;
            case 'specialRestrictions':
                if(!is_object($objectData[$childName])){
                    $objectData[$childName] = new scholarshipSpecialRestriction();
                }                
                break;
            case 'hierarchy':
                if(!is_object($objectData[$childName])){
                    $objectData[$childName] = new scholarshipHierarchy();
                }
                break;
            case 'deadline':
                if(!is_object($objectData[$childName])){
                    $objectData[$childName] = new scholarshipDeadLine();
                }
                break;
        }
        //fillObjectFieldWithData
        $objectData[$childName]->$childField = $childFieldData;
    }
    public function disableCaching() {
        $this->useCache = false;
    }
    
    public function deleteCache($scholarshipId){
        $this->cache->deleteCache($scholarshipId);        
    }
    
    public function refreshScholarshipCache($scholarshipId,$rebuildAfterDeletion = true){
        if(empty($scholarshipId)){
            return;
        }
        
        $this->deleteCache($scholarshipId);
        if($rebuildAfterDeletion==true){
            $this->disableCaching();
            global $useMasterForScholarshipData;
            $useMasterForScholarshipData = true;
            $this->find($scholarshipId);
        }
    }
}

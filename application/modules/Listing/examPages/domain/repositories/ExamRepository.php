<?php

class ExamRepository extends EntityRepository
{
    private $examDao;
    private $sectionNames = array('homepage','pattern','syllabus','cutoff','admitcard','answerkey','counselling','slotbooking','results','importantdates','samplepapers','applicationform','preptips','vacancies','callletter','news','sectionname');
 
    function __construct($dao, $cache) {
		parent::__construct($dao, $cache);
        $this->examDao = $dao;
        
        //load required entities
        $this->CI->load->entities(array('Exam', 'Group', 'ExamContent','ExamDate','Wiki','ExamFile'), 'examPages');
        
        //set caching
        $this->caching = true;

        $this->CI->load->library("common/apiservices/APICallerLib");
        $this->CI->load->config('examPages/examPageConfig');
	}
    
    /*
	 * Find exam page using exam id
     input parameter: examId
	 */
	public function find($examId) {
		if(empty($examId)) {
            		return false;
        	}
        	//check in cache
	        if($this->caching) {
			    $cachedExam= $this->cache->getExam($examId);
    			if(!empty($cachedExam[$examId])){
                        $exam = $this->_load(array('examBasicInfo' => $cachedExam));
    		        	return $exam[$examId];
    	   		}
        	}
            $output = $this->CI->apicallerlib->makeAPICall("EXAM","/exam/api/v1/repository/getExams","GET",array("examIds" => "$examId"),"",array(),"");
            $output = json_decode($output['output'], true);
            $exam        = $this->_load(array('examBasicInfo' => $output['data']));

            /*$result      = $this->examDao->getData($examId);
		    $exampagelib = $this->CI->load->library('examPages/ExamPageLib');
	        $examResults = $exampagelib->formatExamContentData($result['examBasicInfo']);
            $this->cache->storeExam($examResults[$examId], $examId);
	        $exam        = $this->_load(array('examBasicInfo' => $examResults));
	        */

            return $exam[$examId];
	}

    /*
     * Find multiple exam page using exam id
     input parameter: array of examIds
     */
    public function findMultiple($examIds,$useCache = true,$useMasterDB=false) {
        if(empty($examIds)) {
            return false;
        }

        $orderOfExamIds = $examIds;
        $examFromCache = array();
        if($this->caching) {
            $examResults =  $this->cache->getMultiExam($examIds);
            $examFromCache   = $this->_loadMultiple(array('examBasicInfo' => $examResults));
            $existInCache  = array_keys($examFromCache);
            $examIds       = array_diff($examIds, $existInCache);
        }
        if(!empty($examIds) && count($examIds) > 0)
        {
            /*$result = $this->examDao->getMultipleData($examIds);    
            $exampagelib = $this->CI->load->library('examPages/ExamPageLib');
            $examResults = $exampagelib->formatExamContentData($result['examBasicInfo']);
            foreach ($examResults as $key => $examObj) {
                $this->cache->storeExam($examObj, $key);
            }
            $examFromDb   = $this->_loadMultiple(array('examBasicInfo' => $examResults));*/

            $useCache = ($useCache == 'true' || $useCache === true ) ? "true" : "false";

            $useMasterDB = ($useMasterDB == 'true' || $useMasterDB === true ) ? "true" : "false";

            $output = $this->CI->apicallerlib->makeAPICall("EXAM","/exam/api/v1/repository/getExams","GET",array("examIds" => implode(",", $examIds),"useCache" => $useCache,"useMasterDB" => $useMasterDB),"",array(),"");
            $output = json_decode($output['output'], true);
            $examFromDb        = $this->_loadMultiple(array('examBasicInfo' => $output['data']));
        }

        $exams = array();
        foreach ($orderOfExamIds as $examId) {
            if(isset($examFromCache[$examId]))
            {
                $exams[$examId] = $examFromCache[$examId];
            }
            if(isset($examFromDb[$examId]))
            {
                $exams[$examId] = $examFromDb[$examId];
            }
        }
        return $exams;
    }

    public function findGroup($groupId){
        if(empty($groupId)) {
            return false;
        }
        if($this->caching) {
    	    $cachedGroup = $this->cache->getGroup($groupId);
            $cachedGroup = $this->_loadChild('groupInfo', $group, $cachedGroup);
    	    if(array_key_exists($groupId, $cachedGroup) && !empty($cachedGroup[$groupId])){
                	return $cachedGroup[$groupId];
    	    }else if(is_object($cachedGroup) && !empty($cachedGroup) && $cachedGroup->getId()==$groupId){
                    return $cachedGroup;
                }
        }
        /*$childResult = $this->examDao->getGroupData($groupId);

        $mappingResult = $this->_getGroupMappingData(array($groupId));

        $groupData = array();
        foreach ($childResult as $key => $value) {
            $groupData[$value['groupId']] = $value;
        }

        foreach ($mappingResult as $groupkey => $gvalue) {
            foreach ($gvalue as $key => $value) {
                $groupData[$groupkey][$key] = $value;    
            }
        }
        foreach ($groupData as $key => $groupObj) {
            $this->cache->storeGroup($groupObj,$key);
        }
        $group = $this->_loadChild('groupInfo', $group, $groupData);*/
        

        $output = $this->CI->apicallerlib->makeAPICall("EXAM","/exam/api/v1/repository/getGroups","GET",array("groupIds" => "$groupId"),"",array(),"");
        $output = json_decode($output['output'], true);
        $group        = $this->_loadChild('groupInfo', $group, $output['data']);
        return $group[$groupId];
    }

    private function _getGroupMappingData($groupIds){
        $mappingResult = array();
        foreach ($groupIds as $gkey => $gvalue) {
            $mappingData = $this->examDao->getMappingData($gvalue);
        $hierarchyIds = array();
        $entitiesMappedToGroup = array();
            foreach ($mappingData as $value) {
                $entitiesMappedToGroup[$value['entityType']][]  = $value['entityId'];
                if($value['entityType']=='primaryHierarchy' || $value['entityType']=='hierarchy'){
                        if($value['entityType']=='primaryHierarchy'){
                                $primaryHierarchyId = $value['entityId'];
                        }
                        $hierarchyIds[] = $value['entityId'];
                }
            }
            $this->CI->load->builder('ListingBaseBuilder','listingBase');
            $builder       = new ListingBaseBuilder();
            $obj           = $builder->getHierarchyRepository();
            $hierarchyInfo = array();
            if(count($hierarchyIds)>0){
                $hierarchyData = $obj->getBaseEntitiesByHierarchyId($hierarchyIds);
                foreach ($hierarchyData as $key => $value) {
                    if($key == $primaryHierarchyId){
                        $hierarchyInfo[$key]['primary_hierarchy'] = '1';
                    }
                    $hierarchyInfo[$key]['stream']         = $value['stream_id'];
                    $hierarchyInfo[$key]['substream']      = $value['substream_id'];
                    $hierarchyInfo[$key]['specialization'] = $value['specialization_id'];
                }
            }
        $mappingResult[$gvalue]['hierarchyData'] = $hierarchyInfo;
        $mappingResult[$gvalue]['entitiesMappedToGroup'] = $entitiesMappedToGroup;   
        }
        return $mappingResult;
    }

    public function findMultipleGroup($groupIds,$useCache = true,$useMasterDB = false){
        if(empty($groupIds)) {
            return false;
        }

        $orderOfGroupIds = $groupIds;
        $groupFromCache = array();

        if($this->caching) {
            $groupFromCache = $this->cache->getMultiGroup($groupIds);
            $existInCache  = array_keys($groupFromCache);
            $groupIds       = array_diff($groupIds, $existInCache);
            $groupFromCache = $this->_loadChild('groupInfo', $group, $groupFromCache);
        }

        if(!empty($groupIds) && count($groupIds) > 0)
        {
            /*$childResult = $this->examDao->getMultipleGroupData($groupIds);

            $fetchMappingGroupIds = array();
            $groupData = array();

            foreach ($childResult as $key => $value) {
                $groupData[$value['groupId']] = $value;
                $fetchMappingGroupIds[] = $value['groupId'];
            }


            $mappingResult = $this->_getGroupMappingData($fetchMappingGroupIds);

            foreach ($mappingResult as $groupkey => $gvalue) {
                foreach ($gvalue as $key => $value) {
                    $groupData[$groupkey][$key] = $value;    
                }
            }
                foreach ($groupData as $key => $groupObj) {
                    $this->cache->storeGroup($groupObj,$key);
                }

            $groupFromDb = $this->_loadChild('groupInfo', $group, $groupData);*/

            $useCache = ($useCache == 'true' || $useCache === true ) ? "true" : "false";

            $useMasterDB = ($useMasterDB == 'true' || $useMasterDB === true ) ? "true" : "false";


            $output = $this->CI->apicallerlib->makeAPICall("EXAM","/exam/api/v1/repository/getGroups","GET",array("groupIds" => implode(",", $groupIds),"useCache" => $useCache,"useMasterDB" => $useMasterDB),"",array(),"");
            $output = json_decode($output['output'], true);
            $groupFromDb  = $this->_loadChild('groupInfo', $group, $output['data']);
        }
        $groups = array();
        foreach ($orderOfGroupIds as $groupId) {
            if(isset($groupFromCache[$groupId]))
            {
                $groups[$groupId] = $groupFromCache[$groupId];
            }
            if(isset($groupFromDb[$groupId]))
            {
                $groups[$groupId] = $groupFromDb[$groupId];
            }
        }
        return $groups;
    }

    public function findContent($groupId, $sectionNames, $ampFlag = false,$useCache = true,$useMasterDB = false){ 
        if(empty($groupId)) {
            return false;
        }

        $examContent   = new ExamContent;
        $exampagelib = $this->CI->load->library('examPages/ExamPageLib');
        if($this->caching) {
            if($sectionNames=='all'){
                $sectionNames =  $this->sectionNames;
            }
            $result = $this->cache->getSectionData($groupId, $sectionNames, $ampFlag);
            
            $this->_loadContent($examContent, $result);
            $result = $exampagelib->formatExamContentObj($examContent);
            $flag =false;
            foreach ($sectionNames as $key => $value) {
                if(!empty($result[$value])){
                    $flag = true;
                    break;
                }
            }
    	    if($flag){
    		  return $result;
    	    }
        }
        /*$childResult    = $this->examDao->getExamContent($groupId, $ampFlag);
        
	    $sectionNameWithOrder    = $this->examDao->getSectionOrder($groupId); 

        $formattedExamContentObj = $exampagelib->generateExamContentObject($childResult, $sectionNameWithOrder,$ampFlag);
            $this->cache->storeContent($groupId, $formattedExamContentObj, $ampFlag);
            $this->_loadContent($examContent, $formattedExamContentObj);
        $formattedExamContentObj = $exampagelib->formatExamContentObj($examContent);

        if($sectionNames!='all'){
            foreach ($sectionNames as $key => $sectionName) {
                if(!empty($formattedExamContentObj[$sectionName]))
                {
                    $returnObj[$sectionName] = $formattedExamContentObj[$sectionName];
                }
            }
            $sectionNameArray = $formattedExamContentObj['sectionname'];
            $formattedExamContentObj = $returnObj;
            $formattedExamContentObj['sectionname'] = $sectionNameArray;
        }*/
        $isAmp = ($ampFlag == 'true' || $ampFlag === true ) ? "true" : "false";

        $useCache = ($useCache == 'true' || $useCache === true ) ? "true" : "false";

        $useMasterDB = ($useMasterDB == 'true' || $useMasterDB === true ) ? "true" : "false";



        $output = $this->CI->apicallerlib->makeAPICall("EXAM","/exam/api/v1/repository/getContent","GET",array("groupId" => "$groupId","sectionNames" => implode(",", $sectionNames),"isAmp" => $isAmp,"useCache" => $useCache,"useMasterDB" => $useMasterDB),"",array(),"");
        $output = json_decode($output['output'], true);
        $this->_loadContent($examContent, $output['data']);
        $formattedExamContentObj = $exampagelib->formatExamContentObj($examContent);
        return $formattedExamContentObj;
    }

    public function findContentFromAPI($groupId, $sectionNames){
        if(empty($groupId)) {
            return false;
        }
        $output = $this->CI->apicallerlib->makeAPICall("EXAM","/exam/api/v1/info/getExamContent","POST",array("groupId" => "$groupId","sectionNames" => implode(",", $sectionNames),"isAmp" => "false"),"",array(),"");
        $output = json_decode($output['output'], true);
        $examContent   = new ExamContent;
        $exampagelib = $this->CI->load->library('examPages/ExamPageLib');
        $this->_loadContent($examContent, $output['data']);
        $formattedExamContentObj = $exampagelib->formatExamContentObj($examContent);
        return $formattedExamContentObj;
    }
    
    /*
     * Returns exampage object (returns array with empty key values if data not found)
     */
    private function _load($result) {
        if(is_array($result) && !empty($result['examBasicInfo'])) {
            $exam = new Exam;
            $examData = $this->_loadChild('basicInfo', $exam, $result);
            //$examPage = $this->_createExam($result);
        }
        return $examData;
    }  

    /*
     * Returns multiple exampage object (returns array with empty key values if data not found)
     */
    private function _loadMultiple($result) {
        $examData = $this->_loadChild('basicInfo', $exam, $result);
        return $examData;
    }    

    /*
     * Load all the children
     */
    private function _loadContent($examContent, $result) {
        foreach($result as $index => $childResult) {
            if(is_array($result[$index]) && count($result[$index]) > 0) {
                    $this->_loadChild($index, $examContent, $childResult);
            }
            else {
                //load empty child
                $this->_loadChild($index, $examContent);
            }
        }
    }
    
    /*
     * Load individual child of exampage
     */
	private function _loadChild($child, $object, $childResult = NULL)
	{
		switch($child) {
            case 'basicInfo':
                $obj = $this->_createExam($object, $childResult);
                return $obj;
                break;
            case 'groupInfo':
                $obj = $this->_loadGroupData($object, $childResult);
                return $obj;
                break;
            case 'dateContent':
                $this->_loadDateData($object, $childResult);
                break;        
            case 'fileContent':
                $this->_loadFileData($object, $childResult);
                break;
            default:
                $this->_loadExamContentData($object, $childResult, $child);
                break;
		}
	}

    /*
     * Create exampage object filled with basic information
     */
    private function _createExam($exam, $result) {
        $examBasicData = (array) $result['examBasicInfo'];
        $examArrObj = array();
        foreach ($examBasicData as $key => $value) {
            $exam  = new Exam;
            $this->fillObjectWithData($exam, $value);
            $exam->setGroupMappedToExam($value);
            $exam->setPrimaryGroup($exam);
            $examArrObj[$key] = $exam;
            
        }
        return $examArrObj;
    }
    
    /*
     * Populate Group data
     */
    private function _loadGroupData($group, $result) {
        $groupArrObj = array(); $primaryHierarchyId = '';$hierarchyIds = array();
        foreach ($result as $row) {
            $group  = new Group;
            $this->fillObjectWithData($group, $row);
            $groupArrObj[$group->getId()] = $group;
        }
        return $groupArrObj;
    }
        /*
     * Populate Date data
     */
    private function _loadDateData($examContentObj, $result)
    {
        foreach ($result as $row) {
            $examDate = new ExamDate;
            $this->fillObjectWithData($examDate, $row);
            //store in exampage object
            $examContentObj->setEventDates($examDate);
        }                
    }

        /*
     * Populate Date data
     */
    private function _loadFileData($examContentObj, $result)
    {  
        foreach ($result as $row) {
            $examFile = new ExamFile;
            $this->fillObjectWithData($examFile, $row);
            //store in exampage object
            $examContentObj->setFileData($examFile);
        }                
    }
    

    /*
     * Populate Exam Content data
     */

    private function _loadExamContentData($examContent, $result, $child)
    {        
        switch($child) {
            case 'homepage':
                $result = $result['wiki'];
                foreach ($result as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setHomeContent($wiki);
                }
                break;
            case 'pattern':
                $result = $result['wiki'];
                foreach ($result as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setPatternContent($wiki);
                }
                break;
            case 'syllabus':
                $result = $result['wiki'];  
                foreach ($result as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setSyllabusContent($wiki);
                }
                break;
            case 'results':
                $wikiContent = $result['wiki'];
                foreach ($wikiContent as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setResultContent($wiki);
                }
                if(!empty($result['date'])){
                    $dateContent = $result['date'];
                    $this->_loadDateData($examContent,$dateContent);
                }
                break;
            case 'admitcard':
                $result = $result['wiki'];
                foreach ($result as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setAdmitCardContent($wiki);
                }                
                break;
            case 'answerkey':
                $result = $result['wiki'];
                foreach ($result as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setAnswerkeyContent($wiki);
                }                
                break;
            case 'applicationform':
                $wikiContent = $result['wiki'];
                foreach ($wikiContent as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setApplicationformContent($wiki);
                }
                if(!empty($result['file'])){
                    $fileContent = $result['file'];
                    foreach ($fileContent as $filekey) {
                        $this->_loadFileData($examContent,$filekey);    
                    }
                }
                
                break;
            case 'counselling':
                $result = $result['wiki'];
                foreach ($result as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setCounsellingContent($wiki);
                }                
                break;
            case 'cutoff':
                $result = $result['wiki'];
                foreach ($result as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setCutoffContent($wiki);
                }               
                break;
            case 'samplepapers':
                $wikiContent = $result['wiki'];
                foreach ($wikiContent as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setSamplepapersContent($wiki);
                }
                if(!empty($result['file'])) {
                    $fileContent = $result['file'];
                    foreach ($fileContent as $filekey) {
                        $this->_loadFileData($examContent,$filekey);   
                    }
                }
            break;
            case 'slotbooking':
                $result = $result['wiki'];
                foreach ($result as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setSlotbookingContent($wiki);
                }                
                break;
            case 'importantdates':
                $wikiContent = $result['wiki'];
                foreach ($wikiContent as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setImportantDateContent($wiki);
                }
                if(!empty($result['date'])){
                    $dateContent = $result['date'];
                    $this->_loadDateData($examContent,$dateContent);
                }                
                break;
            case 'preptips':
                $wikiContent = $result['wiki'];
                foreach ($wikiContent as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setPreptipsContent($wiki);
                }
                if(!empty($result['file'])){
                    $fileContent = $result['file'];
                    foreach ($fileContent as $filekey) {
                        $this->_loadFileData($examContent,$filekey);
                    }
                }
                break;
	    case 'vacancies':
                $result = $result['wiki'];
                foreach ($result as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setVacanciesContent($wiki);
                }
                break;
            case 'callletter':
                $result = $result['wiki'];
                foreach ($result as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setCallletterContent($wiki);
                }
                break;
            case 'news':
                $result = $result['wiki'];
                foreach ($result as $key => $value) {
                    $wiki = new Wiki;
                    $this->fillObjectWithData($wiki, $value);
                    $examContent->setNewsContent($wiki);
                }
                break;
            case 'sectionname':
                $examContent->setSectionOrder($result);
                break;
        }
    }

    /*
     * Populate Important Date
     */
    private function _loadDateContent($examPage, $result)
    {
        foreach ($result['dateContent'] as $row) {
            $examDate = new examDate;
            $this->fillObjectWithData($examDate, $row);
            //store in exampage object
            $examPage->setExamDateMappingWithGroup($examDate);
        }

    }
}

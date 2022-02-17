<?php

/**
 * Class BaseCourseRepository Responsible for handling the repository functionality for the core tables belonging to the Shiksha Listings
 *
 * @version ShikshaRecatV1.0
 * @author Shiksha Listings Team
 *
 */
class BaseCourseRepository extends ListingBaseRepository{
	function __construct($cache,$model)
    {        
        parent::__construct($cache, $model);

        $this->model = $model;
        $this->cache = $cache;
        $this->CI->load->entity('BaseCourses','listingBase');
        $this->setEntity(new BaseCourses());
        $this->cache->setEntity('BaseCourses');
    }

    function _setDependencies($HierarchyRepository) {
    	$this->hierarchyRepo = $HierarchyRepository;
    	return $this;
    }

    function find($id) {
        $data=  $this->findMultiple(array($id));
        return $data[$id];
    }

    function findMultiple($ids) {       
        if(is_array($ids)){
            $data =  parent::findMultiple($ids); 
        }
        return $data;
    }

    /**
     * Function to get all base course ids or objects
     * @param  string $returnType pass return type as object, if to be returned as objects
     * @return array[int]|array[object]             
     */
    public function getAllBaseCourses($outputFormat = 'array', $includeDummy = 0) {
        $baseCoursesFromCache = $this->cache->getAllBaseCourse($includeDummy);
        if(!empty($baseCoursesFromCache)){
           $result = $baseCoursesFromCache;     
                
        }
        else{
            $result = $this->model->getAllBaseCourses($includeDummy);
            $this->cache->setAllBaseCourse($includeDummy,$result);   
        }

        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        
        if($outputFormat == 'object') {
            foreach ($result as $key => $value) {
                $courseIds[] = $value['id'];
            }
            $result = $this->findMultiple($courseIds);
        }

        return $result;
    }

    /**
     * To get base courses by a particular streamId,substreamId,specializationId where streamId is compulsary.
     * By default only ids of base courses are returned. If object is passed as return type, then objects are returned.
     * @param  int  $streamId         streamId
     * @param  int $substreamId      substreamId
     * @param  int $specializationId specializationId
     * @param  string  $returnType       objects or simple Ids to be returned
     * @return array[int]|array[objects]                    returns array of int or objects
     */
    public function getBaseCoursesByBaseEntities($streamId, $substreamId, $specializationId, $getIdNames = 0, $outputFormat = 'array'){
        $baseEntityArr[0]['streamId']            = $streamId;
        $baseEntityArr[0]['substreamId']         = $substreamId;
        $baseEntityArr[0]['specializationId']    = $specializationId;
        return $this->getBaseCoursesByMultipleBaseEntities($baseEntityArr, $getIdNames, $outputFormat);
    }

    /**
     * To get base courses for multiple sets where each set contain keys - streamId, substreamId, specializationId.
     * @param  array $baseEntityArr Array of sets where streamId is mandatory
     * @param  string $returnType   objects or simple Ids to be returned
     * @return (array of string)/(array of objects)/json    returns array/json of base courses or objects of base courses
     */
    public function getBaseCoursesByMultipleBaseEntities($baseEntityArr, $getIdNames = 0, $outputFormat = 'array', $includeDummy = 0) {
        /*
         * Format eg. - 
         * $baseEntityArr[0]['streamId'] = 3;
         * $baseEntityArr[0]['substreamId'] = 'any';
         * $baseEntityArr[0]['specializationId'] = 'none'; // to get base courses mapped to - stream id 3, [any] substream, null specialization
         * -----------------
         * $baseEntityArr[1]['streamId'] = 3;
         * $baseEntityArr[1]['substreamId'] = 'none';
         * $baseEntityArr[1]['specializationId'] = 2; // to get base courses mapped to - 3, null, 2
         */
        if(empty($baseEntityArr)) {
            return;
        }
        $data = $this->model->getBaseCoursesByMultipleBaseEntities($baseEntityArr, $includeDummy);

        foreach ($data as $key => $value) {
            if($getIdNames) {
                $courseObj[$value['course_id']] = $this->find($value['course_id']);
                $result[$value['course_id']] = $courseObj[$value['course_id']]->getName();
            } else {
                $result[] = $value['course_id'];
            }
        }
        if(!$getIdNames) {
            $result = array_unique($result);
        } else {
            asort($result);
        }
        
        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        
        if($outputFormat == 'object') {
            if($getIdNames) {
                $result = $courseObj;
            } else {
                $result = $this->findMultiple($result);
            }
        }

        return $result;
    }

    /**
     * To get base courses for multiple sets where each set contain keys - streamId, substreamId, specializationId.
     * @param  array $baseEntityArr Array of sets where streamId is mandatory
     * @param  string $returnType   objects or simple Ids to be returned
     * @return (array of string)/(array of objects)/json    returns array/json of base courses or objects of base courses
     */
    public function getBasecoursesByHirarchyWithFilter($baseEntityArr, $courseLevel, $credential, $getIdNames = 0, $outputFormat = 'array') {
        /*
         * Format eg. - 
         * $baseEntityArr[0]['streamId'] = 3;
         * $baseEntityArr[0]['substreamId'] = 'any';
         * $baseEntityArr[0]['specializationId'] = 'none'; // to get base courses mapped to - stream id 3, [any] substream, null specialization
         * -----------------
         * $baseEntityArr[1]['streamId'] = 3;
         * $baseEntityArr[1]['substreamId'] = 'none';
         * $baseEntityArr[1]['specializationId'] = 2; // to get base courses mapped to - 3, null, 2
         */
        if(empty($baseEntityArr)) {
            return;
        }
        $data = $this->model->getBasecoursesByHirarchyWithFilter($baseEntityArr, $courseLevel, $credential);

        foreach ($data as $key => $value) {
            if($getIdNames) {
                $courseObj[$value['course_id']] = $this->find($value['course_id']);
                $result[$value['course_id']] = $courseObj[$value['course_id']]->getName();
            } else {
                $result[] = $value['course_id'];
            }
        }
        if(!$getIdNames) {
            $result = array_unique($result);
        } else {
            asort($result);
        }
        
        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        
        if($outputFormat == 'object') {
            if($getIdNames) {
                $result = $courseObj;
            } else {
                $result = $this->findMultiple($result);
            }
        }

        return $result;
    }

    /**
     * To get base courses where multiple entities are passed individually as filters & 'and' is performed.
     * @param  int/array  $streamIds         					int/array of streamIds
     * @param  int/array  $substreamIds      					int/array of substreamIds
     * @param  int/array  $specializationIds 					int/array of specializationIds
     * @param  string $returnType        					    objects or simple id-name to be returned
     * @return (array of string)/(array of objects)/json        returns array of int or objects
     */
    public function getBaseCoursesForAllCombinations($streamIds, $substreamIds, $specializationIds, $getIdNames = 0, $outputFormat = 'array') {
        if(empty($streamIds)) {
            return;
        }
        $streamIds = !is_array($streamIds) ? array($streamIds) : $streamIds;
        if(!empty($substreamIds)) {
            $substreamIds = !is_array($substreamIds) ? array($substreamIds) : $substreamIds;
        }
        if(!empty($specializationIds)) {
            $specializationIds = !is_array($specializationIds) ? array($specializationIds) : $specializationIds;
        }

        $data = $this->model->getBaseCoursesForAllCombinations($streamIds, $substreamIds, $specializationIds);
        
        foreach ($data as $key => $value) {
            if($getIdNames) {
                $courseObj[$value['course_id']] = $this->find($value['course_id']);
                $result[$value['course_id']] = $courseObj[$value['course_id']]->getName();
            } else {
                $result[] = $value['course_id'];
            }
        }

        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        if($outputFormat == 'object') {
            if($getIdNames) {
                $result = $courseObj;
            } else {
                $result = $this->findMultiple($result);
            }
        }
        return $result;
    }

    /**
     * Get ids of base courses mapped to a set of hierarchies
     * @param  array 				$hierarchyIds set of hierarchies
     * @return array               	returns an associative array of hierarchy ids and array of base course ids
     */
    public function getBaseCoursesByHierarchyIds($hierarchyIds, $getIdNames = 0, $outputFormat = 'array') {
        $hierarchyIds = !is_array($hierarchyIds) ? array($hierarchyIds) : $hierarchyIds;
    	
        $data = $this->model->getBaseCoursesByHierarchyIds($hierarchyIds);
        
    	foreach ($data as $key => $value) {
            if($getIdNames) {
                $courseObj[$value['course_id']] = $this->find($value['course_id']);
        		$result[$value['hierarchy_id']][] = array('id'=>$value['course_id'], 'name'=>$courseObj[$value['course_id']]->getName());
            } else {
                $result[] = $value['course_id'];
            }
    	}
        if(!$getIdNames) {
            $result = array_unique($result);
        }

        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        if($outputFormat == 'object') {
            if($getIdNames) {
                $result = $courseObj;
            } else {
                $result = $this->findMultiple($result);
            }
        }
    	return $result;
    }

    /**
     * Get hierarchy ids mapped to a set of courses
     * @param  array 		$baseCourseids base course ids
     * @return array/json        returns an associative array of course ids and array of hierarchy ids
     */
    public function getHierarchyIdsByBaseCourses($baseCourseIds, $outputFormat = 'array') {
        if(empty($baseCourseIds)) {
            return false;
        }
        $baseCourseIds = !is_array($baseCourseIds) ? array($baseCourseIds) : $baseCourseIds;
        
    	$data = $this->model->getHierarchyIdsByBaseCourses($baseCourseIds);

    	foreach ($data as $key => $value) {
    		$result[$value['course_id']][] = $value['hierarchy_id'];
    	}
        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
    	return $result;
    }

    /*
     * Get base entities in tree format by base course ids. Option available to filter by stream ids.
     * 
     */
    public function getBaseEntityTreeByBaseCourseIds($baseCourseIds, $streamIds, $getIdNames = 0, $outputFormat = 'array', $getOrderedData = 0) {
        if(empty($baseCourseIds)) {
            return false;
        }
        $baseCourseIds = !is_array($baseCourseIds) ? array($baseCourseIds) : $baseCourseIds;
        if(!empty($streamIds)) {
            $streamIds = !is_array($streamIds) ? array($streamIds) : $streamIds;
        }
        $data = $this->model->getBaseEntitiesByBaseCourseIds($baseCourseIds, $streamIds);
        $tree = $this->hierarchyRepo->createHierarchyTree($data, $getIdNames, $getOrderedData);

        if($outputFormat == 'json') {
            $tree = json_encode($tree);
        }
        return $tree;
    }

    /**
     * Obtain the hierarchy details such as :
	 * <ul><li><b>streamId</b></li><li><b>substreamId</b></li><li><b>specializationId</b></li></ul>
     *
     * for the base courses identified by $baseCourseIds.
     *
     * @param array $baseCourseIds The base courses
     *
     * @return bool|array The base entities
     */
    public function getBaseEntitiesByBaseCourseIds($baseCourseIds, $streamIds, $specializationIds, $outputFormat = 'array') {
        if(empty($baseCourseIds)) {
            return false;
        }
        $baseCourseIds = !is_array($baseCourseIds) ? array($baseCourseIds) : $baseCourseIds;
        if(!empty($streamIds)) {
            $streamIds = !is_array($streamIds) ? array($streamIds) : $streamIds;
        }
        if(!empty($specializationIds)) {
            $specializationIds = !is_array($specializationIds) ? array($specializationIds) : $specializationIds;
        }
        $flatData = $this->model->getBaseEntitiesByBaseCourseIds($baseCourseIds, $streamIds, $specializationIds);

        if($outputFormat == 'json') {
            $flatData = json_encode($flatData);
        }
        return $flatData;
    }

    public function saveBaseCourse($baseCourseData){

        $dataForDbTable = array(
            'name' => $baseCourseData['basecourseName'],
            'alias' => $baseCourseData['basecourseAlias'],
            'synonym' => $baseCourseData['basecourseSynonym'],
            'credential_2' => $baseCourseData['basecourseCredential2'],
            'is_popular' => intval($baseCourseData['basecourseIsPopular']) , // The db contains bit(1) for the next two as well
            'is_hyperlocal' => intval($baseCourseData['basecourseIsHyperlocal']),
            'is_executive' => intval($baseCourseData['basecourseIsExecutive']),
            'status' => 'live'
        );
        if(!empty($baseCourseData['basecourseLevel'])){
            $dataForDbTable['level'] = $baseCourseData['basecourseLevel'];
        }
        if(!empty($baseCourseData['basecourseCredential1'])){
            $dataForDbTable['credential_1'] = $baseCourseData['basecourseCredential1'];
        }

        if($baseCourseData['mode'] == 'edit'){
            $dataForDbTable['base_course_id'] = $baseCourseData['basecourseId'];
        }

        $insertData['table'] = $dataForDbTable;
        $insertData['userId'] = $baseCourseData['userId'];

        $returnData = $this->model->save($insertData,$baseCourseData['mode']);

        if($returnData['data']['status'] == basecoursemodel::$statusOk){
            $basecourseId = $returnData['data']['base_course_id'];

            $hierarchyData = $this->hierarchyRepo->getHierarchiesByMultipleBaseEntities($baseCourseData['hierarchyArray']);

            $insertData = array();
            foreach($hierarchyData as $hierarchy){
            //    $temp['hierarchy_id'] = $hierarchy['hierarchy_id']; 
                $temp['entity_id'] = $basecourseId;
                $temp['entity_type'] = basecoursemodel::$entityType;
                $temp['stream_id'] = $hierarchy['stream_id'];
                $temp['substream_id'] = $hierarchy['substream_id']; 
                $temp['specialization_id'] = $hierarchy['specialization_id'];  
                $temp['status'] = 'live';
                $temp['created_on'] = date("Y-m-d H:i:s");
                $temp['created_by'] = $baseCourseData['userId'];
                $temp['updated_by'] = $baseCourseData['userId'];
                $insertData[] = $temp;
            }

            $this->model->insertHierarchyMapping($basecourseId,$insertData,$returnData['data']['dbHandle']);

            unset($returnData['data']['dbHandle']);
        }
        return $returnData;

    }

    /**
     * API to filter the input courses on the basis of their mapping with the given stream.
     * @param integer $streamId The primary key id of stream table.
     * @param array $baseCourses Array of base course ids
     * @return array The input courses which are mapped to the provided streamId
     * ???? - Used by LDB - should be done by getBaseCoursesForAllCombinations
     * UNDER REVISION
     */
    public function filterCoursesMappedToStreamAndCourse($streamId, $baseCourses = array()){
        if(empty($baseCourses)){
            return array();
        }  

        return $this->model->filterCoursesMappedToStreamAndCourse($streamId, $baseCourses);
    }

    /* API to which matches the possible combinations of base courses and specializations
     * @Params:  $baseCourseIds (Array) => collection of base course ids
     *           $specializationIds (Array) => Collection of specilization Ids
     *           $streamId (int) => primary column of streams table
     *           $substream (int) => primary column of substreams table
     * @return:  Array having valid combinations of base courses and specialization
     */
    public function getValidCombinationsOfCourseAndSpec($streamId, $substream, $baseCourseIds=array(), $specializationIds=array(), $masterSubStreams=array(), $userSelectedSubStream=array()){
        if(empty($baseCourseIds) && empty($specializationIds)){
            return array();
        }

        if(empty($substream)){

            $dbData = $this->model->getValidCombinationsOfCourseAndSpec($streamId, $baseCourseIds, $specializationIds, $masterSubStreams);

            /*Now removing values of of substream which are selected by the user*/
            $mappings = array();
            foreach ($dbData as $key => $value) {
                if(empty($value['substream_id'])){
                    $mappings[] = array('specialization_id'=>$value['specialization_id'], 'baseCourse'=>$value['baseCourse']);
                }
            }

        }else{
            $tempMappings = $this->model->getValidCombinationsOfCourseSpecAndSubStream($streamId, $substream, $baseCourseIds, $specializationIds);

            /*Code to remove course mapping to sub stream, if substream is not selected by the user */
            $mappings = array();
            foreach($tempMappings as $key => $value) {
                $mappings[$key] = $value;
                if(empty($value['specialization_id']) && !in_array($value['substream_id'], $userSelectedSubStream)){
                    unset($mappings[$key]);
                }else{
                    unset($mappings[$key]['substream_id']);
                }
            }
        }
        
        /* Separating baseField and specializations from the mapping */
        $specializations = array();
        $baseCourses = array();
        foreach($mappings as $key=>$values){
            $specializations[] = $values['specialization_id'];
            $baseCourses[] = $values['baseCourse'];
        }

        /*Now adding the missing specializations in the mapping */
        foreach ($specializationIds as $key => $specializationId) {
           if(!in_array($specializationId, $specializations)){
                $mappings[] = array('specialization_id'=>$specializationId, 'baseCourse'=>null);
           }
        }

        /*Now adding the missing base courses in the mapping */
        foreach ($baseCourseIds as $key => $baseCourseId) {
           if(!in_array($baseCourseId, $baseCourses)){
                $mappings[] = array('specialization_id'=>null, 'baseCourse'=>$baseCourseId);
           }
        }
        return $mappings;
    }

    /**
     * API to Fetch Level id and credential from the base course ids
     *
     * @param array  $baseCourseIds Array of base course ids
     * @return Array The mapped level and credential to the input base course(s)
     * DO NOT USE THIS API - TO BE REMOVED LATER
     */
    public function getLevelCredentailOfBaseCourses($baseCourseIds = array()){
        if(empty($baseCourseIds)){
            return array();
        }

        $levelCredentialMapping = $this->model->getLevelCredentailOfBaseCourses($baseCourseIds);

        $returnData = array();
        foreach ($levelCredentialMapping as $key => $value) {
            $returnData[$value['base_course_id']]['levelId'] = $value['level'];
            $returnData[$value['base_course_id']]['credentialId'] = array($value['credential_1'],$value['credential_2']);
            $returnData[$value['base_course_id']]['credentialId'] = array_filter($returnData[$value['base_course_id']]['credentialId']);
        }

        return $returnData;
    }

    public function getAllPopularCourses($outputFormat = 'array'){
        $result = $this->model->getPopularCourses();

        if($outputFormat == 'object') {
            foreach ($result as $key => $value) {
                $courseIds[] = $value['base_course_id'];
            }
            $result = $this->findMultiple($courseIds);
        }

        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        return $result;
    }

    public function getAllNonPopularCourses($outputFormat = 'array'){
        $result = $this->model->getNonPopularCourses();

        if($outputFormat == 'object') {
            foreach ($result as $key => $value) {
                $courseIds[] = $value['base_course_id'];
            }
            $result = $this->findMultiple($courseIds);
        }

        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        return $result;
    }

    function getSpecializationsByBaseCourseIds($baseCourseIds, $streamIds, $getIdNames = 0, $outputFormat = 'array') {
        $flatData = $this->getBaseEntitiesByBaseCourseIds($baseCourseIds, $streamIds);
        foreach ($flatData as $key => $value) {
            if(!empty($value['specialization_id'])) {
                $specializationIds[] = $value['specialization_id'];
            }
        }
        $specializationIds = array_unique($specializationIds);
        
        if($outputFormat == 'object') {
            $result = $this->hierarchyRepo->findMultipleSpecializations($specializationIds);
            return $result;
        }

        if($getIdNames) {
            $result = $this->hierarchyRepo->findMultipleSpecializations($specializationIds);
            foreach ($specializationIds as $key => $specializationId) {
                $finalResult[$key]['id'] = $specializationId;
                $finalResult[$key]['name'] = $result[$specializationId]->getName();
            }
        } else {
            $finalResult = $specializationIds;
        }

        if($outputFormat == 'json') {
            $finalResult = json_encode($finalResult);
        }

        return $finalResult;
    }

    /* API to get all the dummy courses */
    function getAllDummyBaseCourses(){
      return $this->model->getAllDummyBaseCourses();
    }

    /**
    * Function get the list of base courses for a given credential
    * @param: $credential Integer
    *
    * @return : Array with base_course_id and names
    * Usage : $this->baseCourseRepo->getBaseCoursesByCredential(10);
    * Output : Array
    *        (
    *            [0] => Array
    *                (
    *                    [base_course_id] => 1
    *                    [name] => B.Des
    *                )
    *
    *            [1] => Array
    *                (
    *                    [base_course_id] => 16
    *                    [name] => D.El.Ed
    *                )
    *        )
    */

    public function getBaseCoursesByCredential($credential){
        Contract::mustBeNumericValueGreaterThanZero($credential,'Credential Id');
        $result = $this->model->getBaseCoursesByCredential($credential);
        return $result;
    }
    
}
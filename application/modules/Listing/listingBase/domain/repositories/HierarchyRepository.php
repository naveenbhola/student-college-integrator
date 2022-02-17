<?php

/**
 * Responsible for handling the repository functionality for the hierarchy tables belonging to the Shiksha Listings
 *
 * @version ShikshaRecatV1.0
 * @author Shiksha Listings Team
 *
 */
class HierarchyRepository {
	function __construct($cache, $hierarchymodel) {
        $this->hierarchymodel = $hierarchymodel;
        $this->cache = $cache;
        $this->cache->setEntity('Hierarchies');
    }

    /**
     * Set the repositories to be used in this class
     *
     * @param StreamRepository $streamRepo The stream repository
     * @param SubstreamRepository $substreamRepo The substream repository
     * @param SpecializationRepository $specRepo The specialization repository
     *
     * @return $this A reference to the HierarchyRepository
     *
     * @see StreamRepository
     * @see SubstreamRepository
     * @see SpecializationRepository
     */
    public function _setDependencies($streamRepo, $substreamRepo, $specRepo) {
    	$this->streamRepository	           = $streamRepo;
    	$this->substreamRepository 	       = $substreamRepo;
    	$this->specializationRepository	   = $specRepo;
    	return $this;
    }

    /**
     * Obtain stream information in the form of a Streams object
     *
     * @param  integer $id stream id
     * @return object      The stream object
     *
     * @see Streams
     */
    public function findStream($id){
        return $this->streamRepository->find($id);
    }

    public function findMultipleStreams($ids) {
        return $this->streamRepository->findMultiple($ids);
    }

    /**
     * Obtain sub-stream information in the form of a Substream object based on the sub-stream id
     * @param  integer $id sub-stream id
     * @return object         sub-stream object
     * @see Substreams
     */
    public function findSubstream($id) {
        return $this->substreamRepository->find($id);
    }

    public function findMultipleSubstreams($ids) {
        return $this->substreamRepository->findMultiple($ids);
    }

    /**
     * Obtain specialization information in the form of a Specializations object
     * @param  integer     $id substream id
     * @return object         substream object
     *
     * @see Specializations
     */
    public function findSpecialization($id) {
        return $this->specializationRepository->find($id);
    }

    public function findMultipleSpecializations($ids) {
        return $this->specializationRepository->findMultiple($ids);
    }

    /**
     * Get hierarchy row in the db by hierarchy id; also accepts multiple hierarchy ids.
     *
     * @param          int        /array   $hierarchyIds
     * @param bool|int $getIdName To get name along with id, pass 1. By default, only ids are returned
     * @param string   $outputFormat
     *
     * @return array An array of base entities(stream id(name), substream id(name), specialization id(name))
     */
    function getBaseEntitiesByHierarchyId($hierarchyIds, $getIdName = 0, $outputFormat = 'array') {
        if(!is_array($hierarchyIds)) {
            $hierarchyIds = array($hierarchyIds);
        }
        if (empty($hierarchyIds)){
            return array();
        }
        $data = array_filter($this->cache->getMultipleData($hierarchyIds));
        $notPresentIds = array_diff($hierarchyIds,array_keys($data));
        if(!empty($notPresentIds)){
            $dbData = $this->hierarchymodel->getElementIdsByHierarchyId($notPresentIds);
            $this->cache->setData($dbData);
            foreach ($dbData as $key => $value) {
                $data[$key] = $value;
            }
        }
        
        if($getIdName) {
            $uniqueStreamIds = array();
            $uniqueSubstreamIds = array();
            $uniqueSpecializationIds = array();
            foreach ($data as $key => $value) {
                $uniqueStreamIds[$value['stream_id']] = $value['stream_id'];
                $uniqueSubstreamIds[$value['substream_id']] = $value['substream_id'];
                $uniqueSpecializationIds[$value['specialization_id']] = $value['specialization_id'];
            }
            $uniqueStreamIds = array_filter(array_values($uniqueStreamIds));
            $uniqueSubstreamIds = array_filter(array_values($uniqueSubstreamIds));
            $uniqueSpecializationIds = array_filter(array_values($uniqueSpecializationIds));
            // _p($uniqueSpecializationIds);
            if(!empty($uniqueStreamIds)) {
                $streamObjs = $this->streamRepository->findMultiple($uniqueStreamIds);
            }
            if(!empty($uniqueSubstreamIds)) {
                $substreamObjs = $this->substreamRepository->findMultiple($uniqueSubstreamIds);
            }
            
            if(!empty($uniqueSpecializationIds)) {
                $specializationObjs = $this->specializationRepository->findMultiple($uniqueSpecializationIds);
            }
            
            foreach ($data as $key => $value) {
                $result[$value['hierarchy_id']]['stream']['id'] = $value['stream_id'];
                $streamObj = $streamObjs[$value['stream_id']];
                $streamName = $streamObj->getName();
                $result[$value['hierarchy_id']]['stream']['name'] = $streamName;
                $streamUrlName = $streamObj->getUrlName();
                $result[$value['hierarchy_id']]['stream']['url_name'] = $streamUrlName;

                $result[$value['hierarchy_id']]['substream'] = array();
                if(!empty($value['substream_id'])) {
                    $result[$value['hierarchy_id']]['substream']['id'] = $value['substream_id'];
                    $substreamObj = $substreamObjs[$value['substream_id']];
                    $substreamName = $substreamObj->getName();
                    $result[$value['hierarchy_id']]['substream']['name'] = $substreamName;
                    $substreamUrlName = $substreamObj->getUrlName();
                    $result[$value['hierarchy_id']]['substream']['url_name'] = $substreamUrlName;
                }
                
                $result[$value['hierarchy_id']]['specialization'] = array();
                if(!empty($value['specialization_id'])) {
                    $result[$value['hierarchy_id']]['specialization']['id'] = $value['specialization_id'];
                    $specializationObj = $specializationObjs[$value['specialization_id']];
                    $specializationName = $specializationObj->getName();
                    $result[$value['hierarchy_id']]['specialization']['name'] = $specializationName;
                    $specializationUrlName = $specializationObj->getUrlName();
                    $result[$value['hierarchy_id']]['specialization']['url_name'] = $specializationUrlName;
                }
            }
        } else {
            foreach ($data as $key => $value) {
                $result[$value['hierarchy_id']]['stream_id'] = $value['stream_id'];
                $result[$value['hierarchy_id']]['substream_id'] = $value['substream_id'];
                $result[$value['hierarchy_id']]['specialization_id'] = $value['specialization_id'];
            }
        }

        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        return $result;
    }

    /**
     * Get hierarchy id for a given stream, substream, specialization combination.
     * @param  int $streamId  mandatory
     * @param  string/int $substreamId      'none' or 'any' or id
     * @param  string/int $specializationId 'none' or 'any' or id
     * @return array returns hierarchy ids. When only stream is passed, it will give all hierarchy ids that have that stream.
     */
    public function getHierarchyIdByBaseEntities($streamId, $substreamId, $specializationId, $outputFormat = 'array') {
        if(empty($streamId)) {
            return;
        }
        $data = $this->hierarchymodel->getHierarchyByBaseEntities($streamId, $substreamId, $specializationId);
        foreach ($data as $key => $value) {
            $hierarchyIds[] = $value['hierarchy_id'];
        }

        if($outputFormat == 'json') {
            $hierarchyIds = json_encode($hierarchyIds);
        }
        return $hierarchyIds;
    }

    /**
     * Get hierarchy data for given sets where each set has keys - streamId, substreamId, specializationId.
     *
     * @param        $baseEntityArr
     * @param string $outputFormat
     *
     * @return array returns hierarchy data for each set passed
     * @internal param array $hierarchyArr - pass substreamId/specializationId as 'none' to get direct mappings. By default it is considered 'any'
     */
    public function getHierarchiesByMultipleBaseEntities($baseEntityArr, $outputFormat = 'array') {
        /*
         * Format eg. - 
         * $baseEntityArr[0]['streamId'] = 3;
         * $baseEntityArr[0]['substreamId'] = 'any';
         * $baseEntityArr[0]['specializationId'] = 'none'; // to get rows from hierarchy table with combination - 3, [any], null
         * -----------------
         * $baseEntityArr[1]['streamId'] = 3;
         * $baseEntityArr[1]['substreamId'] = 'none';
         * $baseEntityArr[1]['specializationId'] = 2; // to get rows from hierarchy table with combination - 3, null, 2 (single row will exist in this combo)
         */
        if(empty($baseEntityArr)) {
            return;
        }
        $data = $this->hierarchymodel->getHierarchiesByMultipleBaseEntities($baseEntityArr);

        if($outputFormat == 'json') {
            $data = json_encode($data);
        }
        return $data;
    }

    public function getHierarchyIdsForAllCombinations($streamIds, $substreamIds, $specializationIds, $outputFormat = 'array') {
        if(empty($streamIds)) {
            return;
        }
        if(!is_array($substreamIds) && !empty($substreamIds)) {
            $substreamIds = array($substreamIds);
        }
        if(!is_array($specializationIds) && !empty($specializationIds)) {
            $specializationIds = array($specializationIds);
        }
        $data = $this->hierarchymodel->getHierarchyIdsForAllCombinations($streamIds, $substreamIds, $specializationIds);

        if($outputFormat == 'json') {
            $data = json_encode($data);
        }
        return $data;
    }

    /** 
     * Get tree of substreams and specializations(id and name), for given array of stream ids.
     * @param  array $streamIds mandatory
     * @param  bool $getIdName 0 gives id only, 1 gives name and id. Format remains the same.
     * @param  string $outputFormat 'array' or 'json'
     * @return array              returns hierarchy data for each set passed(tree of substreams and specializations(id and name))
     */
    public function getSubstreamSpecializationByStreamId($streamIds, $getIdName = 0, $outputFormat = 'array', $getOrderedData = 0) {
        if(!is_array($streamIds)) {
            $streamIds = array($streamIds);
        }
        Contract::mustBeNonEmptyArrayOfIntegerValues($streamIds, 'stream');
        $cacheData = array_filter($this->cache->getSubstreamSpecializationByStreamId($streamIds));

        $notPresentIds = array_diff($streamIds,array_keys($cacheData));
        $data = array();
        if(!empty($cacheData)){
            foreach ($cacheData as $key => $value) {
                $data = array_merge($data,$cacheData[$key]);
            }
        }
        $dbData = array();
        if(!empty($notPresentIds)){
            $dbData = $this->hierarchymodel->getSubstreamSpecializationByStream($notPresentIds);
            foreach ($dbData as $key => $value) {
                $substream[$value['stream_id']][] = $value;
            }
            $this->cache->setSubstreamSpecializationByStreamId($substream);
        }
        $data = array_merge($data,$dbData);
        $tree = $this->createHierarchyTree($data, $getIdName, $getOrderedData);
        if($outputFormat == 'json') {
            $tree = json_encode($tree);
        }
        return $tree;
    }

    /** 
     * Get specializations tree, for given combination of stream ids and substream ids.
     * @param  int $streamId
     * @param  int/array of int $substreamIds
     * @param  string $outputFormat 'array' or 'json'
     * @return array              returns specializations tree
     */
    public function getSpecializationTreeByStreamSubstreamId($streamId, $substreamIds, $getIdName = 0, $outputFormat = 'array', $getOrderedData = 0) {
        Contract::mustBeNumericValueGreaterThanZero($streamId);
        if(!is_array($substreamIds)) {
            $substreamIds = array($substreamIds);
        }
        $data = $this->hierarchymodel->getSpecializationByStreamSubstream($streamId, $substreamIds);
        $tree = $this->createHierarchyTree($data, $getIdName, $getOrderedData);

        if($outputFormat == 'json') {
            $tree = json_encode($tree);
        }
        return $tree;
    }

    /** 
     * Get specializations(id and name), for given combination of stream ids and substream ids.
     * @param  int $streamId
     * @param  int/array of int $substreamIds
     * @param  string $outputFormat 'array' or 'json'
     * @return array              returns specializations - id and name
     */
    public function getSpecializationByStreamSubstreamId($streamId, $substreamIds, $getIdName = 0, $outputFormat = 'array') {
        Contract::mustBeNumericValueGreaterThanZero($streamId);
        if(!is_array($substreamIds) && !empty($substreamIds)) {
            $substreamIds = array($substreamIds);
        }
        $data = $this->hierarchymodel->getSpecializationByStreamSubstream($streamId, $substreamIds);
        foreach ($data as $key => $value) {
            $result[] = $value['specialization_id'];
        }
        $result = array_unique($result);

        if($outputFormat == 'object') {
            $result = $this->findMultipleSpecializations($result);
            return $result;
        }

        if($getIdName) {
            foreach ($result as $key => $value) {
                $finalResult[$key]['id'] = $value;
                $finalResult[$key]['name'] = $this->findSpecialization($value)->getName();
            }
            if($outputFormat == 'json') {
                $finalResult = json_encode($finalResult);
            }
            return $finalResult;
        }

        if($outputFormat == 'json') {
            $result = json_encode($result);
        }

        return $result;
    }

    /** 
     * Get all streams
     * @param  string $outputFormat 'array' or 'json' or 'object'
     * @return array/json/object              returns all stream objects or id, names in array
     */
    function getAllStreams($outputFormat = 'array') {
        $result = $this->streamRepository->getAllStreams($outputFormat);
        return $result;
    }

    /**
     * CMS function to insert data into hierarchy table.Use with caution.
     * @param  int $streamId         
     * @param  int $substreamId      
     * @param  int $specializationId 
     * @param  string $scope            national,abroad,both
     * @param  string $courseType       acacemic,testprep,both
     * @return int                   return hierarchyid if already present with criteria or generate on, insert and return that id.
     */
    public function insertIntoHierarchyTable($hierarchyData){
        $streamId           = $hierarchyData['streamId'];
        $substreamId        = $hierarchyData['substreamId'];
        $specializationId   = $hierarchyData['specializationId'];
        
        $substreamId        = empty($substreamId) ? 'none' : $substreamId;
        $specializationId   = empty($specializationId) ? 'none' : $specializationId;
        $scope              = empty($hierarchyData['scope']) ? 'national' : $hierarchyData['scope'];
        $courseType         = empty($hierarchyData['courseType']) ? 'academic' : $hierarchyData['courseType'];
        $hierarchyData['userId'] = empty($hierarchyData['userId']) ? 11 : $hierarchyData['userId'];
        
        $hierarchyId = $this->getHierarchyIdByBaseEntities($streamId,$substreamId,$specializationId);
        if(empty($hierarchyId)){
            $data['stream_id'] = $streamId;

            if($substreamId == 'none'){
                unset($data['substream_id']);
            } else {
                $data['substream_id'] = $substreamId;
            }

            if($specializationId == 'none'){
                unset($data['specialization_id']);
            } else {
                $data['specialization_id'] = $specializationId;
            }
            
            $data['created_on'] = date("Y-m-d H:i:s");
            $data['updated_by'] = $hierarchyData['userId'];
            $data['created_by'] = $hierarchyData['userId'];
            $data['status'] = 'live';

            switch ($scope) {
                case 'national':
                    $data['national'] = 1; break;
                
                case 'abroad':
                    $data['abroad'] = 1; break;

                case 'both':
                    $data['national'] = 1; $data['abroad'] = 1; break;
            }

            switch ($courseType) {
                case 'academic':
                    $data['academic'] = 1; break;
                
                case 'testprep':
                    $data['testprep'] = 1; break;

                case 'both':
                    $data['academic'] = 1; $data['testprep'] = 1; break;
            }

            $hierarchyId = $this->hierarchymodel->insertDataIntoHierarchyTable($data);
            return array('data' => array('status'=>'success','message'=>'Hierarchy added successfully','hierarchy_id'=>$hierarchyId));
        }
        else{
            return array('data' => array('status'=>'fail','message'=>'Hierarchy already exists with that combination','hierarchy_id'=>$hierarchyId));
        }
    }

    /**
     * Construct a hierarchy tree based out of a flat data
     *
     * @param array $data The flat data containing the snapshot of the table data
     * @param int $getIdName Flag indicating if name and alias information are to be pushed in the tree
     * @param int $getOrderedData FLag indicating if the hierarchy data needs to be sorted.
     *
     * @see \HierarchyRepository::sortHierarchyTree for the way sorting has been carried out
     *
     * @see StreamRepository for the <code>find</code>, <code>getName</code> and the <code>getUrlName</code> methods
     * @see SubstreamRepository
     * @see SpecializationRepository
     *
     *
     * @return array Representing the hierarchy tree
     */
    function createHierarchyTree($data, $getIdName = 0, $getOrderedData = 0) {
        if($getOrderedData) {
            $getIdName = 1;
        }
        $streamIds = array();$substreamIds = array();$specializationIds = array();
        foreach ($data as $key => $value) {
            if(!array_key_exists($value['stream_id'], $result)) {
                $result[$value['stream_id']] = array('id'=>$value['stream_id']);
                if(!in_array($value['stream_id'],$streamIds)){
                    $streamIds[] = $value['stream_id'];
                }
                $result[$value['stream_id']]['substreams'] = array();
                $result[$value['stream_id']]['specializations'] = array();
            }
            if($value['substream_id']) {
                if(!array_key_exists($value['substream_id'], $result[$value['stream_id']]['substreams'])) {
                    $result[$value['stream_id']]['substreams'][$value['substream_id']] = array('id' => $value['substream_id']);
                    if(!in_array($value['substream_id'],$substreamIds)){
                        $substreamIds[] = $value['substream_id'];
                    }
                    $result[$value['stream_id']]['substreams'][$value['substream_id']]['specializations'] = array();
                }
            }
            if($value['specialization_id']) {
                if($value['substream_id']) {
                    if(!array_key_exists($value['specialization_id'], $result[$value['stream_id']]['substreams'][$value['substream_id']]['specializations'])) {
                        $result[$value['stream_id']]['substreams'][$value['substream_id']]['specializations'][$value['specialization_id']] = array('id' => $value['specialization_id']);
                        if(!in_array($value['specialization_id'],$specializationIds)){
                            $specializationIds[] = $value['specialization_id'];
                        }
                    }
                } else {
                    if(!array_key_exists($value['specialization_id'], $result[$value['stream_id']]['specializations'])) {
                        $result[$value['stream_id']]['specializations'][$value['specialization_id']] = array('id' => $value['specialization_id']);
                        if(!in_array($value['specialization_id'],$specializationIds)){
                            $specializationIds[] = $value['specialization_id'];
                        }
                    }
                }
            }
        }

        if($getIdName){
            $streamObjs = array();
            if(!empty($streamIds)){
                $streamObjs = $this->streamRepository->findMultiple($streamIds);
            }
            $substreamObjs = array();
            if(!empty($substreamIds)){
                $substreamObjs = $this->substreamRepository->findMultiple($substreamIds);
            }
            $specializationObjs = array();
            if(!empty($specializationIds)){
                $specializationObjs = $this->specializationRepository->findMultiple($specializationIds);
            }
            foreach ($result as $streamId => $value) {
                $streamObj = $streamObjs[$streamId];
                $result[$streamId]['name'] = $streamObj->getName();
                $result[$streamId]['url_name'] = $streamObj->getUrlName();
                foreach ($value['substreams'] as $substreamId => $substreamArr) {
                    $substreamObj = $substreamObjs[$substreamId];
                    $result[$streamId]['substreams'][$substreamId]['name'] = $substreamObj->getName();
                    $result[$streamId]['substreams'][$substreamId]['url_name'] = $substreamObj->getUrlName();
                    $result[$streamId]['substreams'][$substreamId]['order'] = $substreamObj->getDisplayOrder();
                    foreach ($substreamArr['specializations'] as $specializationId => $specializationArr) {
                        $specializationObj = $specializationObjs[$specializationId];
                        $result[$streamId]['substreams'][$substreamId]['specializations'][$specializationId]['name'] = $specializationObj->getName();
                        $result[$streamId]['substreams'][$substreamId]['specializations'][$specializationId]['url_name'] = $specializationObj->getUrlName();
                    }
                }
                foreach ($value['specializations'] as $specializationId => $specializationArr) {
                    $specializationObj = $specializationObjs[$specializationId];
                    $result[$streamId]['specializations'][$specializationId]['name'] = $specializationObj->getName();
                    $result[$streamId]['specializations'][$specializationId]['url_name'] = $specializationObj->getUrlName();
                }
            }
        }
        
        if($getOrderedData) {
            $result = $this->sortHierarchyTree($result);
        }
        
        return $result;
    }

    function sortHierarchyTree($result) {
        foreach ($result as $streamId => $streamData) {
            $result[$streamId]['substreams'] = $this->sortSubstreamsByDisplayOrder($streamData['substreams']);
            $result[$streamId]['specializations'] = $this->sortArrayByColumnValues($streamData['specializations'],name);
            foreach ($streamData['substreams'] as $substreamId => $substreamData) {
                $result[$streamId]['substreams'][$substreamId]['specializations'] = $this->sortArrayByColumnValues($substreamData['specializations'],name);
            }
        }

        return $result;
    }

    
    function sortSubstreamsByDisplayOrder($data) {
        foreach ($data as $ii => $va) {
             $ids[]=$ii;
        }       
        $result= $this->hierarchymodel->getSortedSubstreams($ids);
        foreach ($result as $ii => $va) {
            $ret[$va[substream_id]]=$data[$va[substream_id]];
        }
        $array=$ret; 
        return $array;
    }
    
     function sortArrayByColumnValues ($array, $columnName) {
        $sorter=array();
        $ret=array();
        reset($array);
        foreach ($array as $ii => $va) {
             $sorter[$ii]=strtolower($va[$columnName]);
        }    
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }
        $array=$ret; 
        return $array;
    }

    public function getAllHierarchies(){
        $data = $this->hierarchymodel->getCompleteHierarchyTableData();
        
        foreach ($data as $key => $value) {
            if($value['national'] && !$value['abroad']) {
                $data[$key]['scope'] = 'national';
            }
            elseif(!$value['national'] && $value['abroad']) {
                $data[$key]['scope'] = 'abroad';
            }
            elseif($value['national'] && $value['abroad']) {
                $data[$key]['scope'] = 'both';
            }

            if($value['academic'] && !$value['testprep']) {
                $data[$key]['course_type'] = 'academic';
            }
            elseif(!$value['academic'] && $value['testprep']) {
                $data[$key]['course_type'] = 'testprep';
            }
            elseif($value['academic'] && $value['testprep']) {
                $data[$key]['course_type'] = 'both';
            }
        }
        return $data;
    }

    public function getHierarchyTree($getIdNames = 0,$outputFormat = 'array') {
        $flatData = $this->getAllHierarchies();
        $data = $this->createHierarchyTree($flatData, $getIdNames);
        if($outputFormat === 'array'){
            return $data;
        }
        return json_encode($data);
    }
}
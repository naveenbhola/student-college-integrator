<?php
class ExamMainLib {
	private $CI;
    
    function __construct() {
        $this->CI =& get_instance();
        $this->ExamMainModel = $this->CI->load->model('examPages/exammainmodel');
    }

    public function getExamsList($filter = array(), $returnType = 'array', $dbFlag = true){
    	$values = array();
    	$return = array();
        $result = array();
    	if(!isset($this->ExamMainModel)){
        	$this->ExamMainModel = $this->CI->load->model('examPages/exammainmodel');
    	}
    	$where = ' AND main.status = "live" AND eg.status = "live" ';
    	/*if(isset($filter['examPageExists']) && $filter['examPageExists']=='yes'){
    		$where .= ' AND main.exampageId > 0 ';
    	}else if(isset($filter['examPageExists']) && $filter['examPageExists'] == 'no'){
            $where .= ' AND main.exampageId = 0 ';
        }else if(isset($filter['examPageExists']) && $filter['examPageExists'] > 0){
            $where .= ' AND main.exampageId = ? ';
            $values['exampageId'] = $filter['examPageExists'];
        }*/

        $joinCondition = ' INNER JOIN exampage_groups eg ON ( eg.examId = main.id AND eg.isPrimary = 1 ) ';
        if(isset($filter['examPageExists']) && $filter['examPageExists']=='yes'){
            $joinCondition .= ' INNER JOIN exampage_master epm ON epm.groupId = eg.groupId';
            $where .= ' AND epm.status= "live"';
        }else if(isset($filter['examPageExists']) && $filter['examPageExists'] == 'no'){
            $joinCondition .= ' INNER JOIN exampage_master epm ON epm.groupId = eg.groupId';
            $where .= ' AND epm.status != "live"';
        }else if(isset($filter['examPageExists']) && $filter['examPageExists'] > 0){
            $joinCondition .= ' INNER JOIN exampage_master epm ON (epm.groupId = eg.groupId AND epm.exampage_id = ?)';
            $values['exampage_id'] = $filter['examPageExists'];
        }

    	if($dbFlag){
    		$return = $this->ExamMainModel->getExamList($where, $values,$joinCondition);
            foreach ($return as $key => $value) {
                $result[$value['examId']] = $value['examName'];
            }
    	}else{
    		//from cache - to do
    		$result = array();
    	}
    	if($returnType == 'json'){
    		return json_encode($result);
    	}else{
    		return $result;
    	}
    }

    public function getAllStreamsHavingExams(){
        if(!isset($this->ExamMainModel)){
            $this->ExamMainModel = $this->CI->load->model('examPages/exammainmodel');
        }
        $data = $this->ExamMainModel->getAllStreamsHavingExams();
        uasort($data,function($a,$b){if($a['name'] < $b['name']) {return -1;}else{return 1;}});
        return $data;
    }

    public function getExamPagesByStream($streamId){
        if(!isset($this->ExamMainModel)){
            $this->ExamMainModel = $this->CI->load->model('examPages/exammainmodel');
        }
        $data = $this->ExamMainModel->getExamPagesByStream($streamId);

        $returnData = array();
        foreach ($data as $key => $row) {
            if(empty($returnData[$row['id']])){
                $returnData[$row['id']] = $row;
                $returnData[$row['id']]['url'] = addingDomainNameToUrl(array('url' => $row['url'].'?course='.$row['groupId'], 'domainName' => SHIKSHA_HOME));
            }
        }
        return $returnData;
    }

    public function getAllMainExamsByHierarchyIds($hierarchyIds, $baseCourseIds, $hasExamPages = '', $returnType = 'array', $dbFlag = true){
        $result = array();
        $return = array();
        if(!isset($this->ExamMainModel)){
            $this->ExamMainModel = $this->CI->load->model('examPages/exammainmodel');
        }
        if($dbFlag){
            $filter = array();
            if(!empty($hierarchyIds)){
                $filter['hierarchyIdArr'] = $hierarchyIds;
            }
            if(!empty($baseCourseIds)){
                $filter['baseCourseIdArr'] = $baseCourseIds;
            }
            $filter['examPageExists'] = $hasExamPages;
            $return = $this->ExamMainModel->getExamListByHierarchies($filter);
            foreach ($return as $key => $value) {
                $result[$value['examId']] = $value['examName'];
            }
        }else{
            //from cache - to do
            $result = array();
        }
        if($returnType == 'json'){
            return json_encode($result);
        }else{
            return $result;
        }
    }

    public function getAllMainExamsByBaseEntities($baseEntitiesArr, $hasExamPages = '', $returnType = 'array', $dbFlag = true){
        $result = array();
        $hierarchyData = array();
        if(!empty($baseEntitiesArr)){
            $this->CI->load->builder('listingBase/ListingBaseBuilder');
            $listingBase = new ListingBaseBuilder();
            $hierarchyRepo = $listingBase->getHierarchyRepository();
            $hierarchyData = $hierarchyRepo->getHierarchiesByMultipleBaseEntities($baseEntitiesArr);
        }
        $hierarchyIds = array();
        foreach ($hierarchyData as $hierarchy) {
            $hierarchyIds[] = $hierarchy['hierarchy_id'];
        }
        $result = $this->getAllMainExamsByHierarchyIds($hierarchyIds, array(), $hasExamPages, $returnType, $dbFlag);
        return $result;
    }

    public function getAllMainExamsByAllCombinations_old($streamIds, $substreamIds, $specializationIds, $baseCourses, $hasExamPages = '', $returnType = 'array', $dbFlag = true){
        $streamIds = is_array($streamIds) ? $streamIds : array($streamIds);
        $this->CI->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new ListingBaseBuilder();
        $hierarchyRepo = $listingBase->getHierarchyRepository();
        $hierarchyIds = $hierarchyRepo->getHierarchyIdsForAllCombinations($streamIds, $substreamIds, $specializationIds);
        $hierarchyIdArr = array();
        foreach ($hierarchyIds as $value) {
            $hierarchyIdArr[] = $value['hierarchy_id'];
        }
        $result = $this->getAllMainExamsByHierarchyIds($hierarchyIdArr, $baseCourses, $hasExamPages, $returnType, $dbFlag);
        return $result;
    }

    public function getAllMainExamsByAllCombinations($baseEntityArr, $baseCourses, $hasExamPages = '', $returnType = 'array', $dbFlag = true){
        $baseEntityArr = is_array($baseEntityArr) ? $baseEntityArr : array($baseEntityArr);
        if(!isset($this->ExamMainModel)){
            $this->ExamMainModel = $this->CI->load->model('examPages/exammainmodel');
        }
        
        if(!empty($baseEntityArr))
        {
            $this->CI->load->builder('listingBase/ListingBaseBuilder');
            $listingBase = new ListingBaseBuilder();
            $hierarchyRepo = $listingBase->getHierarchyRepository();
            $baseCourseRepo = $listingBase->getBaseCourseRepository();
            $hierarchyIds = $hierarchyRepo->getHierarchiesByMultipleBaseEntities($baseEntityArr);
            $hierarchyIdArr = array();
            foreach ($hierarchyIds as $value) {
                $hierarchyIdArr[] = $value['hierarchy_id'];
            }
        }
        $finalExamIds = array();
        if(!empty($hierarchyIdArr) && !empty($baseCourses)){
            //$baseCourseAndHierArr = $this->ExamMainModel->getBaseCourseIdsBasedOnHierarchy($hierarchyIdArr, $baseCourses);
            $allBaseCourses = $baseCourseRepo->getBaseCoursesByMultipleBaseEntities($baseEntityArr);
            $filteredBaseCourses = array();
            //Only those base courses are considered which are passed into the API
            foreach ($allBaseCourses as $value) {
                if(in_array($value, $baseCourses))
                $filteredBaseCourses[] = $value;
            }
            $baseCourses = $filteredBaseCourses;
            $where = '';
            if(!empty($baseCourses)){
                $where  = ' and map.entityId in (?) and map.entityType="course" ';
                $examArr = $this->ExamMainModel->getExamsBasedOnFilter($where, $baseCourses);
            }
            foreach ($examArr as $value) {
                $finalExamIds[$value['examId']] = $value['name'];
            }
            return $finalExamIds;
        }else if(!empty($hierarchyIdArr) && empty($baseCourses)){
            $where  = ' and map.entityId in (?) and map.entityType in ("hierarchy", "primaryHierarchy") ';
            $examArr = $this->ExamMainModel->getExamsBasedOnFilter($where, $hierarchyIdArr);
            foreach ($examArr as $value) {
                $finalExamIds[$value['examId']] = $value['name'];
            }
            return $finalExamIds;
        }else if(empty($hierarchyIdArr) && !empty($baseCourses)){
            $where  = ' and map.entityId in (?) and map.entityType="course" ';
            $examArr = $this->ExamMainModel->getExamsBasedOnFilter($where, $baseCourses);
            foreach ($examArr as $value) {
                $finalExamIds[$value['examId']] = $value['name'];
            }
            return $finalExamIds;
        }
        return array();
    }

    /**
    Input : Simple array of exam IDs as intergers.
    */
    public function getExamDetailsByIds($examIdArr){
        $result = array();
        $finalExamIds = $this->_validateAndCleanExamIds($examIdArr);
        if(!isset($this->ExamMainModel)){
            $this->ExamMainModel = $this->CI->load->model('examPages/exammainmodel');
        }
        if(count($finalExamIds) > 0){
            $where  = ' AND main.status = "live" ';
            $where .= ' AND main.id in ('.implode(',', $finalExamIds).')';
            $values = array();
            $return = $this->ExamMainModel->getExamList($where, $values);
            foreach ($return as $value) {
                $result[$value['examId']] = $value;
            }
        }
        return $result;
    }

    /**
    Input : Simple array of exam names as strings.
    */
    public function getExamDetailsByName($examName,$addQuotes=true){
        $result = array();
        if(!is_array($examName) && trim($examName) != ''){
            $examName = array(trim($examName));
        }
        $finalExamNames = $this->_validateAndCleanExamNames($examName,$addQuotes);
        
        if(!isset($this->ExamMainModel)){
            $this->ExamMainModel = $this->CI->load->model('examPages/exammainmodel');
        }
        if(count($finalExamNames) > 0){
            $finalExamNames = implode(',', $finalExamNames);
            $return = $this->ExamMainModel->getExamListByName($finalExamNames);
            $i = 0;
            foreach ($return as $value) {
                $result[$examName[$i]] = $value;
                $i++;
            }
        }
        return $result;
    }

    private function _validateAndCleanExamNames($examNameArr,$addQuotes=true){
        $quotes = '';
        if($addQuotes){
            $quotes='"';
        }
        if(!is_array($examNameArr) && trim($examNameArr) != ''){
            return array(trim($quotes.$examNameArr.$quotes));
        }
        $finalArr = array();
        foreach ($examNameArr as $value) {
            $value = trim($value);
            if(!is_array($value) && $value != ''){
                $finalArr[] = $quotes.$value.$quotes;
            }
        }
        return $finalArr;
    }

    private function _validateAndCleanExamIds($examIdArr){
        if(!is_array($examIdArr) && trim($examIdArr) != ''){
            return array(trim('"'.$examIdArr.'"'));
        }
        $finalArr = array();
        foreach ($examIdArr as $value) {
            $value = trim($value);
            if(!is_array($value) && $value != ''){
                $finalArr[] = '"'.$value.'"';
            }
        }
        return $finalArr;
    }

   public function getExamDataByExamIds($examIdArr){
        if(!isset($this->ExamMainModel)){
            $this->ExamMainModel = $this->CI->load->model('examPages/exammainmodel');
        }

        $nationalExamsData = $this->ExamMainModel->getExamDataByExamIds($examIdArr);

        //separating exams which do not have national exam page
        foreach ($nationalExamsData as $examId => $exam) {
            if(!$exam['isExamPageActive']) {
                $fetchFromAbroad[] = $exam['name'];
            }
        }    

        //fetching the remaining exam page urls from abroad lib
        if(!empty($fetchFromAbroad)) {
            $saContentLib = $this->CI->load->library('blogs/saContentLib');
            $abroadExamData = $saContentLib->getSAExamHomePageURLByExamNames($fetchFromAbroad);
        }

        //merging national & abroad exams result set
        $allExamsData = array();
        foreach ($examIdArr as $examId) {
            $allExamsData[$examId] = $nationalExamsData[$examId];
            $allExamsData[$examId]['scope'] = 'national';
            if(!empty($abroadExamData[$nationalExamsData[$examId]['name']]['contentURL'])) {
                $allExamsData[$examId]['url'] = $abroadExamData[$nationalExamsData[$examId]['name']]['contentURL'];
                $allExamsData[$examId]['scope'] = 'abroad';
            }
            unset($allExamsData[$examId]['isExamPageActive']);
        }

        return $allExamsData;
    }

    public function getAllExamPageUrlByEntity($entityType,$entityId){
        $entityData = array();$hierarchyMappings = array();
        if(!isset($this->ExamMainModel)){
            $this->ExamMainModel = $this->CI->load->model('examPages/exammainmodel');
        }
        $this->nationalIndexingModel = $this->CI->load->model('indexer/nationalindexingmodel');
        if($entityType == 'substream'){
            $streamId = $this->ExamMainModel->getStreamBySubstreamForExam($entityId);
            if(!empty($streamId)){
                $hierarchyMappings = array(array('stream_id'=>$streamId,'substream_id'=>$entityId));
                $entityData['stream'] = $this->nationalIndexingModel->fetchAllEntities('stream',array($streamId));
            }
        }
        $entityData[$entityType] = $this->nationalIndexingModel->fetchAllEntities($entityType,array($entityId));
        return $this->getAllExamPageUrl($hierarchyMappings,$entityData,$entityId,$entityType);
    }

    private function getAllExamPageUrl($hierarchyMappings,$entityData,$entityId,$entityType){
        switch($entityType){
            case 'stream':
                $name = empty($entityData[$entityType][$entityId]['alias']) ? $entityData[$entityType][$entityId]['name'] : $entityData[$entityType][$entityId]['alias'];
                $name = strtolower(seo_url($name, "-", 30));
                $url = '/'.$name.'/exams-st-'.$entityId;
                break;
            case 'base_course':
                $name = empty($entityData[$entityType][$entityId]['alias']) ? $entityData[$entityType][$entityId]['name'] : $entityData[$entityType][$entityId]['alias'];
                $name = strtolower(seo_url($name, "-", 30));
                $url = '/'.$name.'/exams-pc-'.$entityId;
                break;
            case 'substream':
                foreach ($hierarchyMappings as $row) {
                    if($row['substream_id'] == $entityId){
                        $streamName = empty($entityData['stream'][$row['stream_id']]['alias']) ? $entityData['stream'][$row['stream_id']]['name'] : $entityData['stream'][$row['stream_id']]['alias'];
                        $streamName = strtolower(seo_url($streamName, "-", 30));
                        $substreamName = empty($entityData[$entityType][$entityId]['alias']) ? $entityData[$entityType][$entityId]['name'] : $entityData[$entityType][$entityId]['alias'];
                        $substreamName = strtolower(seo_url($substreamName, "-", 30));
                        $url = '/'.$streamName.'/'.$substreamName.'/exams-sb-'.$row['stream_id'].'-'.$entityId;
                    }
                }
                break;
        }
        return $url;
    }
	    function getExamWithExamPagesByBaseCourses($baseCourseIds) {
        if(empty($baseCourseIds)) {
            return;
        }
        $examIds = $this->ExamMainModel->getExamWithExamPagesByBaseCourses($baseCourseIds);

        return $examIds;
    }


    public function formatFeaturedContentData($params){
        $this->ExamMainModel = $this->CI->load->model('examPages/exammainmodel');
        $featuredData = $this->ExamMainModel->getFeaturedInstData($params);
        $result = array();
        foreach($featuredData as $key=>$val){
            $result[$val['courseId']][$val['examId']]['examName'] = htmlentities($val['examName']);
            $result[$val['courseId']][$val['examId']]['groupId'][] = $val['groupId'];
            $result[$val['courseId']][$val['examId']]['start'][$val['start_date']][$val['end_date']]['groups'][] = $val['groupName'];
            $result[$val['courseId']][$val['examId']]['start'][$val['start_date']][$val['end_date']]['selfIds'][] = $val['id'];
            $result[$val['courseId']][$val['examId']]['courseName'] = htmlentities($val['courseName']);
            $result[$val['courseId']][$val['examId']]['start'][$val['start_date']][$val['end_date']]['ctaText'] = htmlentities($val['CTA_text']);
            $result[$val['courseId']][$val['examId']]['start'][$val['start_date']][$val['end_date']]['redirectUrl'] = $val['redirection_url'];
            $result[$val['courseId']][$val['examId']]['start'][$val['start_date']][$val['end_date']]['instName'] = htmlentities($val['instName']);
            $result[$val['courseId']][$val['examId']]['start'][$val['start_date']][$val['end_date']]['instituteId'] = $val['instituteId'];
            $result[$val['courseId']][$val['examId']]['courseId'] = $val['courseId'];
            $result['displayExam'] = htmlentities($val['examName']);   
        }

        foreach($result as $key=>$val){
            foreach($val as $key1=>$val1){
                foreach($val1['start'] as $key2=>$val2){
                        foreach($val2 as $key3=>$val3){
                                $result[$key][$key1]['start'][$key2][$key3]['firstGroup'] = $val3['groups'][0];
                                $result[$key][$key1]['start'][$key2][$key3]['groupString'] = implode(',',$val3['groups']);
                 }
                }  
            }
        }
        
        return $result;
    }

    public function formatFeaturedExamData($params)
    {
        $this->ExamMainModel = $this->CI->load->model('examPages/exammainmodel');
        $featuredData = $this->ExamMainModel->getFeaturedExamData($params);
        $result = array();
        foreach ($featuredData as $key => $val) {
            $result[$val['dest_group_id']][$val['orig_exam_id']]['orig_exam_name'] = htmlentities($val['orig_exam_name']);
            $result[$val['dest_group_id']][$val['orig_exam_id']]['orig_group_id'] = $val['orig_group_id'];
            $result[$val['dest_group_id']][$val['orig_exam_id']]['start'][$val['start_date']][$val['end_date']]['groups'][] = $val['orig_group_name'];
            $result[$val['dest_group_id']][$val['orig_exam_id']]['start'][$val['start_date']][$val['end_date']]['selfIds'][] = $val['id'];
            $result[$val['dest_group_id']][$val['orig_exam_id']]['start'][$val['start_date']][$val['end_date']]['ctaText'] = htmlentities($val['CTA_text']);
            $result[$val['dest_group_id']][$val['orig_exam_id']]['start'][$val['start_date']][$val['end_date']]['redirectUrl'] = $val['redirection_url'];
            $result[$val['dest_group_id']][$val['orig_exam_id']]['dest_exam_id'] = $val['dest_exam_id'];
            $result[$val['dest_group_id']][$val['orig_exam_id']]['dest_exam_name'] = htmlentities($val['dest_exam_name']);
            $result[$val['dest_group_id']][$val['orig_exam_id']]['dest_group_name'] = $val['dest_group_name'];
            $result[$val['dest_group_id']][$val['orig_exam_id']]['dest_group_id'] = $val['dest_group_id'];
            $result['displayExam'] = htmlentities($val['orig_exam_name']);
        }

        foreach ($result as $key => $val) {
            foreach ($val as $key1 => $val1) {
                foreach ($val1['start'] as $key2 => $val2) {
                    foreach ($val2 as $key3 => $val3) {
                        $result[$key][$key1]['start'][$key2][$key3]['firstGroup'] = $val3['groups'][0];
                        $result[$key][$key1]['start'][$key2][$key3]['groupString'] = implode(',', $val3['groups']);
                    }
                }
            }
        }
        return $result;
    }

    public function getESConnectionConfigurations(){
        $ESConnectionLib    = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $this->clientConn   = $ESConnectionLib->getESServerConnection();
        $this->clientConn6  = $ESConnectionLib->getShikshaESServerConnection();
    }

    public function buildESQuery($indexName, $type, $listingType, $listingTypeId, $startDateTime, $endDateTime, $excludeResponseTypes = array()){
        $esQuery = array();
        $esQuery['index']   = $indexName;
        $esQuery['type']    = $type;
        $esQuery['size']    = 250000;
        $esQueryBody = array();
        $esQueryBody['_source'] = array('user_id', 'user_location', 'tracking_id'/*, 'response_action_type', 'response_time'*/);
        $esFilterQuery = array( 'bool'  =>  array(  "must"  =>  array(  array(  "term"  =>  array(  "response_listing_type" =>  $listingType)
                                                                            ),
                                                                        array(  "term"  =>  array(  "listing_type_id" =>  $listingTypeId)
                                                                        ),
                                                                        array(  "range" =>  array(  "latest_response_time" =>  array(   "gte"   =>  $startDateTime,
                                                                                                                                        "lte"   =>  $endDateTime
                                                                                                                                    )
                                                                                                )
                                                                        )
                                                                    ),
                                                    /**"should"    =>  array(  "bool"  =>  array(  "must_not"  =>  array(  array(  "term"  =>  array(  "response_action_type"  =>  "exam_viewed_response")
                                                                                                                            )
                                                                                                                    )
                                                                                            )
                                                                        )*/
                                                )
                            );
        if (is_array($excludeResponseTypes) && !empty($excludeResponseTypes)){
            $esFilterQuery['bool']['must_not']    =   array(  array(  'terms' =>  array(  'response_action_type' =>   $excludeResponseTypes)
                                                                                )
                                                            );
        }
        $esQueryBody['query']['bool']['filter'] = $esFilterQuery;
        /**$esSortQuery    =   array(  "latest_response_time"  =>  array(  "order" =>  "asc")
                                );
        $esQueryBody['sort']    =   $esSortQuery;*/
        $esQuery['body']    = $esQueryBody;
        return $esQuery;
    }

    public function processAutoSubscriptionForLegacyUsers(){
        $this->getESConnectionConfigurations();
        $this->CI->load->config('examPages/autoSubscribeConfig');
        $autoSubscriptionConfigArray = $this->CI->config->item('autoSubscribeConfigData');
        $indexName = LDB_RESPONSE_INDEX_NAME;

        $this->CI->load->library('event/eventCalendarCronLib');
        $currentDateReference = date("Y-m-d\TH:i:s"/*, mktime(0,0,0)*/);
        foreach ($autoSubscriptionConfigArray['groupsData'] as $groupData){
//            $startDate = date("Y-m-d\TH:i:s", strtotime($groupData['days'], strtotime($currentDateReference)));
            $startDate = DateTime::createFromFormat("Y-m-d H:i:s", $groupData['startDate'])->format("Y-m-d\TH:i:s");
            foreach ($groupData['groupIds'] as $groupId){
                $esQuery = $this->buildESQuery($indexName, "response", "exam", $groupId, $startDate, $currentDateReference, $groupData['excludeResponse']);
                $result = $this->clientConn6->search($esQuery);
                $responseData = array();
                foreach ($result['hits']['hits'] as $resultValue){
                    $countOfTrackingKeyIds  = count($resultValue['_source']['tracking_id']);
                    unset($trackingKeyId);
                    if($countOfTrackingKeyIds > 0){
                        $trackingKeyId = $resultValue['_source']['tracking_id'][$countOfTrackingKeyIds - 1];
                    }
                    $temp   =   array(  'userId'            => $resultValue['_source']['user_id'],
                                        'tracking_keyid'    => $trackingKeyId,
                                        'userCity'          => $resultValue['_source']['user_location']
                                    );
                    $responseData[] = $temp;
                }
                if ($groupId == 113){
                    $onlySelfSubscribe = false;
                }else{
                    $onlySelfSubscribe = true;
                }
                if(!empty($responseData)){
                    $this->CI->eventcalendarcronlib->subscribeExamResponsesToOtherExamsAcrossLocations($responseData, $groupData['streamId'], $groupId, $groupData['selfSubscribe'], $onlySelfSubscribe);
                }

            }
        }
        echo "DONE";
    }
}

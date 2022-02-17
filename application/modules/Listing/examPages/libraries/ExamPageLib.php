<?php
class ExamPageLib
{
    private $CI;
    
    function __construct() {
        $this->CI =& get_instance();
        $this->ExamPageCache = $this->CI->load->library('examPages/cache/ExamPageCache');
        $this->exampagemodel = $this->CI->load->model('examPages/exampagemodel');
    }

    function getHierarchiesWithExamNames($disableCache = false) {
        $this->CI->load->library('examPages/ExamPageRequest');
        if($this->ExamPageCache && !$disableCache) {
            $hierarchiesWithExamNames = array();
            $hierarchiesWithExamNames = $this->ExamPageCache->getHierarchiesWithExamNames();
        }
        if(empty($hierarchiesWithExamNames)) {
            $this->ExamPageModel = $this->CI->load->model('examPages/exampagemodel');
            $result = $this->ExamPageModel->getExamHierarchyMappingData();
            $examGroupMapping = array();
            if($result && count($result) > 0){
                $hierarchyIds = array();
                $examIds = array();
                $hierarchyWiseExamData = array();
                foreach ($result as $key => $examHierarchyMapping){
                    if($examHierarchyMapping['entityType'] == 'course'){
                        $hierarchyWiseExamData['course'][$examHierarchyMapping['entityId']][$examHierarchyMapping['groupId']] = array();
                    }else{
                        $hierarchyWiseExamData['hierarchy'][$examHierarchyMapping['entityId']][$examHierarchyMapping['groupId']] = array();
                        $hierarchyIds[] = $examHierarchyMapping['entityId'];
                    }
                    $examIds[] = $examHierarchyMapping['examId'];
                    $examGroupMapping[$examHierarchyMapping['groupId']]  = $examHierarchyMapping['examId'];
                }
                unset($result);
                $hierarchyIds = array_unique($hierarchyIds);
                $examIds = array_unique($examIds);

                //get exam details
                //$examDetailResult = $this->ExamPageModel->getExamDetails($examIds);

                //fetch Exam Details from exam basic Object
                $this->CI->load->builder('examPages/ExamBuilder');
                $examBuilder    = new ExamBuilder();
                $examRepository = $examBuilder->getExamRepository();
                //filter out exam Ids if any zero values exist
                $examIds = array_filter($examIds);
                $multiExamObject = $examRepository->findMultiple($examIds);

                $examDetails = array();
                foreach ($multiExamObject as $examBasicDetails) {
                    $examObjectId          = $examBasicDetails->getId();
                    if(!empty($examObjectId)) {
                             
                        $examGroups = $examBasicDetails->getGroupMappedToExam();
                        $examConductedByArr = $this->getConductedBy($examConductedBy);
                        $examConductedBy = is_array($examConductedByArr) ? $examConductedByArr['conductedBy']['name'] : $examConductedBy;

                        foreach ($examGroups as $groupKey => $groupValue) {
			    $groupYear = '';
                            $groupObj  = $examRepository->findGroup($groupValue['id']);

			    if(!empty($groupObj) && is_object($groupObj)){
				$mapping   = $groupObj->getEntitiesMappedToGroup();
				$groupYear = $mapping['year'][0];
			    }

                            if($groupValue['primaryGroup'] == 1)
                            {
                                $examDetails[$groupValue['id']]  = array('name' => $examBasicDetails->getName(),'url' => $examBasicDetails->getUrl(), 'conductedBy' => $examConductedBy, 'fullName' => $examBasicDetails->getFullName(),'examId' => $examObjectId,'isPrimary' => 1, 'year' => $groupYear,'groupId' => $groupValue['id']);  
                            }
                            else
                            {
                                $examDetails[$groupValue['id']]  = array('name' => $examBasicDetails->getName(),'url' => $examBasicDetails->getUrl().'?course='.$groupValue['id'], 'conductedBy' => $examConductedBy, 'fullName' => $examBasicDetails->getFullName(),'examId' => $examObjectId,'isPrimary' => 0, 'year' => $groupYear,'groupId' => $groupValue['id']);  
                            }
                        }
                    }
                }
                unset($examIds);
                unset($multiExamObject);
                
                /*$this->examPageRequest = $this->CI->load->library('examPages/examPageRequest');
                foreach ($examDetailResult as $examData) {
                    $this->examPageRequest->setExamName($examData['name']);
                    $examUrl = $this->examPageRequest->getUrl('homepage',true);
                    if($examUrl != ''){
                        $examDetails[$examData['id']] = array(
                            'name' => $examData['name'],
                            'url'   => $examUrl 
                            );
                    }
                    unset($examDetailResult);
                }*/
                

                $this->CI->load->builder('ListingBaseBuilder', 'listingBase');
                $this->ListingBaseBuilder    = new ListingBaseBuilder();
                $basecourseRepository = $this->ListingBaseBuilder->getBaseCourseRepository();
                $this->hierarchyRepo = $this->ListingBaseBuilder->getHierarchyRepository();
                if(count($hierarchyIds) > 0){
                    
                    $hierarchyTostreamSubStreamMapping = $this->hierarchyRepo->getBaseEntitiesByHierarchyId($hierarchyIds,0,'array');
                    unset($hierarchyIds);
                    foreach ($hierarchyTostreamSubStreamMapping as $key => $value) {
                        if(!$value['substream_id'] || $value['substream_id'] ==''){
                            $hierarchyTostreamSubStreamMapping[$key]['substream_id'] = 0;
                        }
                        unset($hierarchyTostreamSubStreamMapping[$key]['specialization_id']);
                    }
                }

                // get exam order form exampage_order table
                $result = $this->ExamPageModel->getExamOrder();
                $examOrderByHierarchy = array();
                foreach ($result as $key => $value) {
                    if($value['courseId'] > 0){
                        $examOrderByHierarchy['course'][$value['courseId']][$value['examId']] = array(
                            'examOrder' => $value['exam_order'],
                            'isFeatured' => $value['is_featured']
                            );
                    }else{
                        if($value['subStreamId'] > 0){
                            $subStreamId = $value['subStreamId'];
                        }else{
                            $subStreamId = 0;
                        }
                        $examOrderByHierarchy['hierarchy'][$value['streamId']][$subStreamId][$value['examId']] = array(
                            'examOrder' => $value['exam_order'],
                            'isFeatured' => $value['is_featured']
                            );
                    }
                }
                unset($result);
                foreach ($hierarchyWiseExamData as $entityType => $entityIds) {
                    foreach ($entityIds as $entityId => $examIds) {
                        foreach ($examIds as $examId => $examData) {
                            if($examDetails[$examId]){
                                if(array_key_exists($examDetails[$examId]['examId'], $hierarchyWiseExamData[$entityType][$entityId]) && !($examDetails[$examId]['isPrimary'] == 1))
                                {
                                    continue;
                                }
                                if($entityType == 'hierarchy'){
                                    $streamId = $hierarchyTostreamSubStreamMapping[$entityId]['stream_id'];
                                    $subStreamId = $hierarchyTostreamSubStreamMapping[$entityId]['substream_id'];
                                    if($subStreamId > 0){
                                        if($examOrderByHierarchy['hierarchy'][$streamId][$subStreamId][$examDetails[$examId]['examId']]){
                                            $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']] = $examOrderByHierarchy['hierarchy'][$streamId][$subStreamId][$examDetails[$examId]['examId']];
                                        }else{
                                            $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']] = array(
                                                'examOrder' => -1,
                                                'isFeatured' => 0
                                                );
                                        }
                                        $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['examName'] = $examDetails[$examId]['name'];
                                        $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['examFullName'] = $examDetails[$examId]['fullName'];
					$hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['examYear'] = $examDetails[$examId]['year'];
                                        $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['conductedBy'] = $examDetails[$examId]['conductedBy'];
                                        $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['groupId'] = $examDetails[$examId]['groupId'];
                                        $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['isPrimary'] = $examDetails[$examId]['isPrimary'];
                                        $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['url'] = $examDetails[$examId]['url'];
                                        $subStreamId = 0;
                                        $hierarchyIdForStream = $this->hierarchyRepo->getHierarchyIdByBaseEntities($streamId, 'none','none', 'array');
                                        $entityIdTemp = $hierarchyIdForStream[0];
                                        if($examOrderByHierarchy['hierarchy'][$streamId][$subStreamId][$examDetails[$examId]['examId']]){
                                            $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']] = $examOrderByHierarchy['hierarchy'][$streamId][$subStreamId][$examDetails[$examId]['examId']];
                                        }else{
                                            $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']] = array(
                                                'examOrder' => -1,
                                                'isFeatured' => 0
                                                );
                                        }
                                        $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['examName'] = $examDetails[$examId]['name'];
                                        $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['examFullName'] = $examDetails[$examId]['fullName'];
					$hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['examYear'] = $examDetails[$examId]['year'];
                                        $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['conductedBy'] = $examDetails[$examId]['conductedBy'];
                                        $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['groupId'] = $examDetails[$examId]['groupId'];
                                        $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['isPrimary'] = $examDetails[$examId]['isPrimary'];
                                        $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['url'] = $examDetails[$examId]['url'];
                                    }else{
                                        if($examOrderByHierarchy['hierarchy'][$streamId][$subStreamId][$examDetails[$examId]['examId']]){
                                            $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']] = $examOrderByHierarchy['hierarchy'][$streamId][$subStreamId][$examDetails[$examId]['examId']];
                                        }else{
                                            $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']] = array(
                                                'examOrder' => -1,
                                                'isFeatured' => 0
                                                );
                                        }
                                        $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['examName'] = $examDetails[$examId]['name'];
                                        $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['examFullName'] = $examDetails[$examId]['fullName'];
					$hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['examYear'] = $examDetails[$examId]['year'];
                                        $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['conductedBy'] = $examDetails[$examId]['conductedBy'];
                                        $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['groupId'] = $examDetails[$examId]['groupId'];
                                        $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['isPrimary'] = $examDetails[$examId]['isPrimary'];
                                        $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['url'] = $examDetails[$examId]['url'];

                                        $hierarchyIdForStream = $this->hierarchyRepo->getHierarchyIdByBaseEntities($streamId, 'none','none', 'array');
                                        $entityIdTemp = $hierarchyIdForStream[0];
                                        if($entityIdTemp != $entityId){
                                            if($examOrderByHierarchy['hierarchy'][$streamId][$subStreamId][$examDetails[$examId]['examId']]){
                                                $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']] = $examOrderByHierarchy['hierarchy'][$streamId][$subStreamId][$examDetails[$examId]['examId']];
                                            }else{
                                                $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']] = array(
                                                                                                'examOrder' => -1,
                                                                                                'isFeatured' => 0
                                                                                                );
                                            }
                                            $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['examName'] = $examDetails[$examId]['name'];
                                            $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['examFullName'] = $examDetails[$examId]['fullName'];
					    $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['examYear'] = $examDetails[$examId]['year'];
                                            $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['conductedBy'] = $examDetails[$examId]['conductedBy'];
                                            $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['groupId'] = $examDetails[$examId]['groupId'];
                                            $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['isPrimary'] = $examDetails[$examId]['isPrimary'];
                                            $hierarchyWiseExamData[$entityType][$entityIdTemp][$examDetails[$examId]['examId']]['url'] = $examDetails[$examId]['url'];
                                        }
                                    }
                                    unset($hierarchyWiseExamData[$entityType][$entityId][$examId]);
                                }else{
                                    $baseCourseDetails = $basecourseRepository->find($entityId);
                                    if(!empty($baseCourseDetails) && $baseCourseDetails->getIsPopular() ==1){ 
                                        if($examOrderByHierarchy['course'][$entityId][$examDetails[$examId]['examId']]){
                                        $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']] = $examOrderByHierarchy['course'][$entityId][$examDetails[$examId]['examId']];
                                        }else{
                                            $hierarchyWiseExamData['course'][$entityId][$examDetails[$examId]['examId']] = array(
                                                'examOrder' => -1,
                                                'isFeatured' => 0
                                                );
                                        }
                                    }else{
                                        unset($hierarchyWiseExamData[$entityType][$entityId]);
                                        continue;
                                    }
                                    $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['examName'] = $examDetails[$examId]['name'];
                                    $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['examFullName'] = $examDetails[$examId]['fullName'];
				    $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['examYear'] = $examDetails[$examId]['year'];
                                    $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['conductedBy'] = $examDetails[$examId]['conductedBy'];
                                    $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['groupId'] = $examDetails[$examId]['groupId'];
                                    $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['isPrimary'] = $examDetails[$examId]['isPrimary'];
                                    $hierarchyWiseExamData[$entityType][$entityId][$examDetails[$examId]['examId']]['url'] = $examDetails[$examId]['url'];

                                    unset($hierarchyWiseExamData[$entityType][$entityId][$examId]);
                                }
                            }else{
                                unset($hierarchyWiseExamData[$entityType][$entityId][$examId]);
                            }
                        }
                    }
                }
                unset($hierarchyTostreamSubStreamMapping);
                unset($examOrderByHierarchy);
                foreach ($hierarchyWiseExamData as $entityType => $entityIds) {
                    foreach ($entityIds as $entityId => $examDetails) {
                        if(!(count($examDetails) > 0 )){
                            unset($hierarchyWiseExamData[$entityType][$entityId]);
                        }else{
                            uasort($examDetails, function($a, $b){
                                if($a["examOrder"] == -1 || $b["examOrder"] == -1){
                                    return $a["examOrder"]< $b["examOrder"];
                                }else{
                                    return $a["examOrder"]> $b["examOrder"];
                                }
                            });
                            $hierarchyWiseExamData[$entityType][$entityId] = $examDetails;
                        }
                        foreach ($examDetails as  $examId => $examDetails) {
                            if(count($examDetails)> 0){
                                unset($hierarchyWiseExamData[$entityType][$entityId][$examId]['examOrder']);
                            }else{
                                unset($hierarchyWiseExamData[$entityType][$entityId][$examId]);
                            }
                        }
                    }
                }
            }
            unset($examDetails);
            $this->ExamPageCache->storeHierarchiesWithExamNames($hierarchyWiseExamData);
            return $hierarchyWiseExamData;
        }else {
            return $hierarchiesWithExamNames;
        }
    }

    function updateHierarchiesWithExamNamesCache() {
        $hierarchiesWithExamNames = $this->getHierarchiesWithExamNames(true);
        if(is_array($hierarchiesWithExamNames) && !empty($hierarchiesWithExamNames)) {
            return true;
        }
        else {
            return false;
        }
    }

    function updateRedirectExamListCache(){
        $this->listingCache = $this->CI->load->library('listing/cache/ListingCache');
        if(!isset($this->ExamPageModel)){
            $this->ExamPageModel = $this->CI->load->model('examPages/exampagemodel');
        }
        $redirectExamsList = $this->ExamPageModel->getRedirectExams();
        $this->listingCache->storeRedirectExamsList($redirectExamsList);
    }

     function checkToShowExamCal($mapping){
       if(empty($mapping)){
            return;
        }
        $mappingArr = array();
       
        if(!empty($mapping['primaryHierarchy'])){
            $this->CI->load->builder('listingBase/ListingBaseBuilder');
            $listingBase = new ListingBaseBuilder();
            $hierarchyRepo = $listingBase->getHierarchyRepository();
            $baseEntities = $hierarchyRepo->getBaseEntitiesByHierarchyId($mapping['primaryHierarchy'], 0, 'array');
            foreach ($baseEntities as $value) {
                $mappingArr['streamIds'][] = $value['stream_id'];
            }
        }
        $result = 'other';
        if(in_array(MANAGEMENT_STREAM, $mappingArr['streamIds']) && in_array(MANAGEMENT_COURSE, $mapping['course']) && in_array(EDUCATION_TYPE, $mapping['otherAttribute'])){
            $result = 'fullTimeMba';
        }else if(in_array(ENGINEERING_STREAM, $mappingArr['streamIds']) && in_array(ENGINEERING_COURSE, $mapping['course']) && in_array(EDUCATION_TYPE, $mapping['otherAttribute'])){
            $result = 'beBtech';
        }
        return $result;
    }

    function getBlogIdsCsv($blogIds){
        $blogIdsCsv = '';
        foreach ($blogIds as $value) {
            $blogIdsCsv = $value['articleId'].','.$blogIdsCsv;   
        }
        return rtrim($blogIdsCsv, ',');
    }

    function getBlogIdsForInputExamId($examId){
        $blogIdsCsv = '';
        if(!empty($examId)){
            $examPageModel = $this->CI->load->model('examPages/exampagemodel');
            $blogIdsArr = $examPageModel->getAllBlogIdsMappedToExamId($examId);
            if(!empty($blogIdsArr)){
                $blogIdsCsv = $this->getBlogIdsCsv($blogIdsArr);
            }
        }
        return $blogIdsCsv;
    }
	
	function getExamMappingForURL($examId){
        $this->exampagemodel = $this->CI->load->model('examPages/exampagemodel');
        $res = $this->exampagemodel->getExamMappingForURL($examId);
        foreach ($res as $key => $value) {
            if($value['entityType'] == 'primaryHierarchy'){
                $result['primaryHierarchy'][] = $value['entityId'];
            }
            if($value['entityType'] == 'course'){
                $result['course'][] = $value['entityId'];
            }
        }
        return $result;
    }

   /*
    Desc   : this function is used to get hierarchyIds based on stream, substream and specialization
    @Param : $stream,$subStream='any',$specialization='any'
    @uthor : akhter
    @type  : return array,string
    */
    function getHierarchyId($stream,$subStream='any',$specialization='any',$returnType=false){
        if(empty($stream) && empty($subStream) && empty($specialization)){
            return;
        }
        $this->CI->load->builder('listingBase/ListingBaseBuilder');
        $listingObj = new ListingBaseBuilder();
        $repObj     = $listingObj->getHierarchyRepository();
        $hierarchyIds = $repObj->getHierarchyIdByBaseEntities($stream,$subStream,$specialization,'array');
        if($returnType){
            return $hierarchyIds; // array
        }else{
            return implode(',',$hierarchyIds); // string
        }
    }

    function getExamList($paramArray,$dataType){
        $data = $this->getHierarchiesWithExamNames();
        foreach ($data[$dataType] as $keyId => $value) {
            if(in_array($keyId, $paramArray)){
                $result[] = $value;    
            }
        }
        $finalArr = array();
        foreach ($result as $key => $value) {
                foreach ($value as $examId => $value) {
                    if(!in_array($examId, $duplicateArr)){
                        $result1['examId']       = $examId;
                        $result1['examName']     = $value['examName'];
                        $result1['urlKey']       = $value['url'];
                        $result1['examFullName'] = $value['examFullName'];
                        $result1['conductedBy']  = $value['conductedBy'];
                        $result1['groupId'] = $value['groupId'];
                        $result1['isPrimary'] = $value['isPrimary'];
			             $result1['examYear']    = $value['examYear'];
                        $finalArr[$examId] = $result1;
                        $duplicateArr[] = $examId;
                    }
                    
                }
        }
        return $finalArr;
    }

    function mergeData($result, $examPageResult){
        foreach ($examPageResult as $key => $value) {
            $examDescription[$value['examId']] = $value;
        }
        foreach ($result as $key => $value) {
            if(array_key_exists($value['examId'],$examDescription)){
                $addFiled['exampageId']      = $examDescription[$value['examId']]['exampageId'];
                $addFiled['examName']        = $value['examName'];
                $addFiled['examDescription'] = $examDescription[$value['examId']]['examDescription'];
                $addFiled['urlKey']          = $value['urlKey'];
                $finalExamList[]             = $addFiled;
            }
        }
        return $finalExamList;
    }

    function storExamList(){
        $this->exammainmodel = $this->CI->load->model('examPages/exammainmodel');
        $examList = $this->exammainmodel->getAllExams();
        if($this->ExamPageCache && !empty($examList)){
            $this->ExamPageCache->storExamList($examList);
        }
    }

    function getAllExams($isCache=false){
        $examList = $this->ExamPageCache->getExamList();
        if(empty($examList) || $isCache){
            error_log('Exam: exam list cache invalidated');
            $this->exammainmodel = $this->CI->load->model('examPages/exammainmodel');
            $examList = $this->exammainmodel->getAllExams();
            foreach ($examList as $exam) {
                $examData[$exam['examId']] = $exam['examName'];
            }
            $this->ExamPageCache->storExamList($examData);
            return $examData;
        }else{
            return $examList;
        }
    }

    function getAllExamUrl(){
        $this->exammainmodel = $this->CI->load->model('examPages/exammainmodel');
        $result = $this->exammainmodel->getUrl();
        foreach ($result as $key => $value) {
            $res[strtolower($value['examName'])] = $value['url'];
        }
        return $res;
    }

    /*
    Desc    : this function is used to create exam url and add in table exampage_main.
    @Param  : $examId
    @uthor  : akhter
    @type   : no return
    */
    function addExamUrl($examId, $excludeConductingBody = false){
        if(!empty($examId)){
            $this->exammainmodel = $this->CI->load->model('examPages/exammainmodel');
            $data = array();
            $finalData = array();

            $result = $this->exammainmodel->getDataForUrl($examId);
            $data['examName']    = $result[0]['examName'];
            $data['isRootUrl']   = $result[0]['isRootUrl'];
            if(!$excludeConductingBody){
                $data['conductedBy'] = is_numeric($result[0]['conductedBy']) ? $result[0]['conductedBy'] : '';
            }

            $mappingData = $this->getExamMappingForURL($examId); // get mapping data for exam
            
            $finalData = array_merge($data, (array)$mappingData);
            $urlLibObj = $this->CI->load->library('common/UrlLib'); 
            $url       = $urlLibObj->getExamUrl($finalData);
            $this->exammainmodel->addExamUrl($url, $examId);

            $this->invalidateExamCache();
        }
    }

    function prepareBreadCrumb($examRepository, $examBasicObj, $examId, $primaryGroupId, $sectioName){
        $data['page'] = 'examPage';
        $data['examName']    = $examBasicObj->getName();
        $data['isRootUrl']   = $examBasicObj->isRootUrl();
        $data['examUrl']   = $examBasicObj->getUrl();
        $conductedBy = is_numeric($examBasicObj->getConductedBy()) ? $examBasicObj->getConductedBy() : '';
        $result = $this->getConductedBy($conductedBy);
        $data['conductedBy'] = $result['conductedBy'];

        $groupObj = $examRepository->findGroup($primaryGroupId);
        if(!empty($groupObj) && is_object($groupObj)){
            $mapping  = $groupObj->getEntitiesMappedToGroup();
            $data['primaryHierarchy'] = $mapping['primaryHierarchy'][0];
            $data['courseIds']        = $mapping['course'];
        }
        $this->CI->load->config('examPages/examPageConfig.php');
        $sectionNameMappings = $this->CI->config->item('sectionNamesMapping');
        if($sectioName !='homepage'){
            $data['sectionName'] = $sectionNameMappings[$sectioName];
        }
        $urlLibObj = $this->CI->load->library('common/UrlLib'); 
        return $urlLibObj->getBreadCrumb($data);
    }

    function getConductedBy($conductedBy){
        if($conductedBy && is_numeric($conductedBy)){
            $this->CI->load->builder("nationalInstitute/InstituteBuilder");
            $instituteBuilder = new InstituteBuilder();
            $instituteRepo = $instituteBuilder->getInstituteRepository();
            $instObj = $instituteRepo->find($conductedBy);    
            if(!empty($instObj) && is_object($instObj)){
                $collegName  = $instObj->getName(); // only use for display
                $res['conductedBy']['name'] = $collegName;
                $res['conductedBy']['url']  = $instObj->getURL();
                $res['conductedBy']['instituteId']  = $instObj->getId();
                return $res;
            }
        }         
    }

    function sanitizeParam( $urlString ) {
        // sanitize the URL
        $urlString = str_replace(array(' ','/','(',')',',','-',' - '), ' ', $urlString);
        $urlString = str_replace(array("."), " ", $urlString); 
        $urlString = str_replace("&", "and", $urlString); 
        $urlString = preg_replace('!-+!','-', $urlString);
        $urlString = preg_replace('/\s+/', ' ',$urlString);
        $urlString = strtolower(trim($urlString, '-'));
        // return the sanitized URL
        return trim($urlString);
    }

    function getExamMappingForBeaconData($examId){
        $this->exampagemodel = $this->CI->load->model('examPages/exampagemodel');
        $res = $this->exampagemodel->getExamMappingForBeaconData($examId);
        return $res;
    }

    function getBeaconData($mappingData,$examId, $groupId, $pageIdentifier,$isAmp = false){      
        $hierarchyIds = array();
        $otherAttributeIds = array();
        $beaconHierarchyData = array();
        $baseCourseIds = array();
        $primaryHierarchy = 0;
        foreach ($mappingData as $key => $hierarchyData) {
            if($key == 'hierarchy' || $key == 'primaryHierarchy'){
                if($key == 'primaryHierarchy'){
                    $primaryHierarchy = $hierarchyData[0];
                }
                $hierarchyIds = $hierarchyData;
            }else if($key == 'otherAttribute'){
                $otherAttributeIds = $hierarchyData;
            }else if($key == 'course'){
                $baseCourseIds = $hierarchyData;
            }
        }
        if(!empty($primaryHierarchy) && $primaryHierarchy != 0){
            array_push($hierarchyIds, $primaryHierarchy);
        }
        $hierarchyIds = array_unique($hierarchyIds);
        $otherAttributeIds = array_unique($otherAttributeIds);
        $baseCourseIds = array_unique($baseCourseIds);

        if(!empty($hierarchyIds)){
            $this->CI->load->builder('listingBase/ListingBaseBuilder');
            $listingBase = new \ListingBaseBuilder();
            $HierarchyRepository = $listingBase->getHierarchyRepository();
            $hierarchyMappingData = $HierarchyRepository->getBaseEntitiesByHierarchyId($hierarchyIds);
            foreach ($hierarchyMappingData as $hierarchyId => $hierarchyMapping) {
                $beaconHierarchyData[] = array(
                        'streamId' =>$hierarchyMapping['stream_id'] ? $hierarchyMapping['stream_id'] :0,
                        'substreamId'=>$hierarchyMapping['substream_id']? $hierarchyMapping['substream_id'] :0,
                        'specializationId'=>$hierarchyMapping['specialization_id'] ? $hierarchyMapping['specialization_id'] :0,
                        'primaryHierarchy' => ($primaryHierarchy == $hierarchyId) ?1:0 
                    );
            }
        }

        if(!empty($otherAttributeIds)){
            $this->CI->load->library('listingBase/BaseAttributeLibrary');
            $baseAttributeLibrary = new BaseAttributeLibrary();
            $otherAttributeMapping = $baseAttributeLibrary->getAttributeNameByValueId($otherAttributeIds);
            $educationTypeIds = array();
            $deliveryMethodIds = array();
            foreach ($otherAttributeMapping as $otherAttributeId => $otherAttributeName) {
                if($otherAttributeName == 'Education Type'){
                    $educationTypeIds[] = $otherAttributeId;
                }else if($otherAttributeName == 'Medium/Delivery Method'){
                    $deliveryMethodIds[] = $otherAttributeId;
                }

            }
        }

        $beaconTrackData = array(
            'pageIdentifier' => $pageIdentifier,
            'pageEntityId'   => $examId, 
            'extraData' => array(
            'hierarchy' => $beaconHierarchyData,
            'groupId' => $groupId,
            'examId' => $examId,
            'countryId' => 2,
            'isAmpPage' => $isAmp ? true : false,
                )
        );
        if(!empty($baseCourseIds)){
            $beaconTrackData['extraData']['baseCourseId'] = $baseCourseIds;
        }

        if(!empty($educationTypeIds)){
            $beaconTrackData['extraData']['educationType'] = $educationTypeIds;
        }

        if(!empty($deliveryMethodIds)){
            $beaconTrackData['extraData']['deliveryMethod'] = $deliveryMethodIds;
        }
        return $beaconTrackData;
    }

    function getGTMArray($beaconTrackData, $examId, $groupId, $userStatus){
        foreach ($beaconTrackData['extraData']['hierarchy'] as $key => $value) {    
           if($value['streamId'] != ''){
                $stream[] = $value['streamId']; 
            }
            if($value['substreamId'] != ''){
                $substream[] = $value['substreamId'];   
            }
            if($value['specializationId'] != ''){
                $specialization[] = $value['specializationId']; 
            }
        }
        if(is_array($beaconTrackData['extraData']['baseCourseId'])){
            $baseCourseId = implode(',', $beaconTrackData['extraData']['baseCourseId']);
        }else{
            $baseCourseId = $beaconTrackData['extraData']['baseCourseId'];
        }
        $gtmArray = array(
            "pageType" => $beaconTrackData['pageIdentifier'],
            "stream"=>implode(',',array_unique($stream)),
            "substream"=>implode(',',array_unique($substream)),
            "specialization"=>implode(',',array_unique($specialization)),
            "baseCourseId"=>$baseCourseId,
            "exam"=> $examId,
            "groupId" => $groupId
        );
                $gtmArray['workExperience'] = $userStatus[0]['experience'];

        return $gtmArray;
    }
 
    public function formatExamContentData($result){
        $count = 0;
        foreach($result as $key=>$value){
            if(!in_array($value['id'], $tempArr)){
                $finalArr[$value['id']]['id']               = $value['id'];
                $finalArr[$value['id']]['name']             = $value['name'];
                $finalArr[$value['id']]['fullName']         = $value['fullName'];
                $finalArr[$value['id']]['conductedBy']      = $value['conductedBy'];
                $finalArr[$value['id']]['url']              = $value['url'];
                $finalArr[$value['id']]['status']           = $value['status'];
                $finalArr[$value['id']]['isRootUrl']        = $value['isRootUrl'];
                $finalArr[$value['id']]['creationDate']     = $value['creationDate'];
                $finalArr[$value['id']]['isPrimary']        = $value['isPrimary'];                  
                $finalArr[$value['id']]['groupMapping'][$count]['id']   = $value['groupId'];
                $finalArr[$value['id']]['groupMapping'][$count]['name'] = htmlentities($value['groupName']);
                $finalArr[$value['id']]['groupMapping'][$count]['primaryGroup'] = $value['isPrimary'];
            }else{
                $finalArr[$value['id']]['groupMapping'][$count]['id']   = $value['groupId'];
                $finalArr[$value['id']]['groupMapping'][$count]['name'] = htmlentities($value['groupName']);
                $finalArr[$value['id']]['groupMapping'][$count]['primaryGroup'] = $value['isPrimary'];
                $tempArr[] =  $value['id'];
            }
            if($value['isPrimary'] == 1){
                $finalArr[$value['id']]['primaryGroup'] = array('id' => $value['groupId'],'name' => $value['groupName'],'primaryGroup' => $value['isPrimary']);
            }
            $count++;
        }
        return $finalArr;
    }   

    public function generateExamContentObject($examContentObj, $sectionNameWithOrder,$ampFlag = false){
            /*HomePage*/
	$this->CI->load->config('examPages/examPageConfig.php');
        $homepagesectionorder = $this->CI->config->item('homepage_section_order');
        foreach ($homepagesectionorder as $key => $value) {
            foreach ($examContentObj['homepage'] as $k => $v) {
                    if($v['entity_type'] == $key){
                        $homePageData[] = $v;            
                    }
               }   
        }
        $sectionHavingData = array();
    	if(!empty($homePageData)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['homepage']['wiki'] =  $this->convertImagesToLazyLoadImages($homePageData);
                }
                else
                {
                    $formattedExamContentObj['homepage']['wiki']    = $homePageData;
                }
                $sectionHavingData[] = 'homepage';
    	}
    	$patternData = $examContentObj['pattern'];
    	if(!empty($patternData)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['pattern']['wiki'] =  $this->convertImagesToLazyLoadImages($patternData);
                }
                else
                {
                    $formattedExamContentObj['pattern']['wiki']     = $patternData;
                }
                $sectionHavingData[]                    = 'pattern';
    	}
            /*Syllabus*/
    	$syllabusData = $examContentObj['syllabus'];
    	if(!empty($syllabusData)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['syllabus']['wiki'] =  $this->convertImagesToLazyLoadImages($syllabusData);
                }
                else
                {
                    $formattedExamContentObj['syllabus']['wiki']   = $syllabusData;
                }
                $sectionHavingData[]                    = 'syllabus';
    	}
            /*CuttOff*/
    	$cutoffData = $examContentObj['cutoff'];
    	if(!empty($cutoffData)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['cutoff']['wiki'] =  $this->convertImagesToLazyLoadImages($cutoffData);
                }
                else
                {
                    $formattedExamContentObj['cutoff']['wiki']      = $cutoffData;
                }
                $sectionHavingData[]                    = 'cutoff';
    	}
            /*AdmitCard*/
    	$admitcardData = $examContentObj['admitcard'];
    	if(!empty($admitcardData)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['admitcard']['wiki'] =  $this->convertImagesToLazyLoadImages($admitcardData);
                }
                else
                {
                    $formattedExamContentObj['admitcard']['wiki']   = $admitcardData;
                }
                $sectionHavingData[]                    = 'admitcard';
    	}
            /*AnswerKey*/
    	$answerkeyData = $examContentObj['answerkey'];
    	if(!empty($answerkeyData)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['answerkey']['wiki'] =  $this->convertImagesToLazyLoadImages($answerkeyData);
                }
                else
                {
                    $formattedExamContentObj['answerkey']['wiki']   = $answerkeyData;
                }
                $sectionHavingData[]                    = 'answerkey';
    	}
            /*Counselling*/
    	$counsellingData = $examContentObj['counselling'];
    	if(!empty($counsellingData)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['counselling']['wiki'] =  $this->convertImagesToLazyLoadImages($counsellingData);
                }
                else
                {
                    $formattedExamContentObj['counselling']['wiki'] = $counsellingData;
                }
                $sectionHavingData[]                    = 'counselling';
    	}
            /*SlotBooking*/
        $slotbookingData = $examContentObj['slotbooking'];
    	if(!empty($slotbookingData)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['slotbooking']['wiki'] =  $this->convertImagesToLazyLoadImages($slotbookingData);
                }
                else
                {
                    $formattedExamContentObj['slotbooking']['wiki'] = $slotbookingData;
                }
                $sectionHavingData[]                    = 'slotbooking';
    	}
            /*Result*/
    	$resultWiki							   = $examContentObj['results'];
    	if(!empty($resultWiki)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['results']['wiki'] =  $this->convertImagesToLazyLoadImages($resultWiki);
                }
                else
                {
                    $formattedExamContentObj['results']['wiki']                = $resultWiki;
                }
                $sectionHavingData[]                    = 'results';
    	}
            /*Important Dates*/
    	$importantDateWiki 						   = $examContentObj['importantdates'];
    	if(!empty($importantDateWiki)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['importantdates']['wiki'] =  $this->convertImagesToLazyLoadImages($importantDateWiki);
                }
                else
                {
                    $formattedExamContentObj['importantdates']['wiki']         = $importantDateWiki;
                }
                $sectionHavingData[]                    = 'importantdates';
    	}


        $dates = $examContentObj['dateContent'];
        foreach ($dates as $datekey => $datevalue) {

            $startDateCheck = strtotime($datevalue['start_date']) > 0 ? strtotime($datevalue['start_date']) : 0;
            $endDateCheck = strtotime($datevalue['end_date']) > 0 ? strtotime($datevalue['end_date']) : 0;

            if(empty($startDateCheck) && empty($endDateCheck))
                continue;

            $tempValue = $datevalue;

            if(strtotime($tempValue['start_date']) <= 0){
                $tempValue['start_date'] = $tempValue['end_date'];
            }

            if(strtotime($tempValue['end_date']) <= 0){
                $tempValue['end_date'] = $tempValue['start_date'];   
            }


            $formattedExamContentObj[$datevalue['section_name']]['date'][] = $tempValue;
            if(!in_array($datevalue['section_name'], $sectionHavingData)){
                $sectionHavingData[] = $datevalue['section_name'];
            }
        }
            /*Sample Papers*/
    	$samplePaperWiki	 					   = $examContentObj['samplepapers'];
    	if(!empty($samplePaperWiki)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['samplepapers']['wiki'] =  $this->convertImagesToLazyLoadImages($samplePaperWiki);
                }
                else
                {
                    $formattedExamContentObj['samplepapers']['wiki']           = $samplePaperWiki;
                }
                $sectionHavingData[]                    = 'samplepapers';
    	}

        $filesData = $examContentObj['fileContent'];
        foreach ($filesData as $filekey => $filevalue) {
            if(!empty($filevalue['file_type']) && !empty($filevalue['file_url'])){
                if($filevalue['file_type'] == 'applicationform'){
                    $formattedExamContentObj['applicationform']['file']['applicationform'][] = $filevalue;
                    $sectionHavingData[]                    = 'applicationform';
                }
                else if($filevalue['file_type'] == 'preptips'){
                    $formattedExamContentObj['preptips']['file']['preptips'][]   = $filevalue;
                    $sectionHavingData[]                    = 'preptips';
                }
                else{
                    $tempFileType = $filevalue['file_type'];
                    if(in_array($tempFileType, array('samplepapers','guidepapers'))) {
                        $tempFileType = "samplepapers";
                    }
                    $formattedExamContentObj[$tempFileType]['file'][$filevalue['file_type']][]   = $filevalue;
                    
                    if(!in_array($tempFileType,$sectionHavingData)) {
                        $sectionHavingData[] = $tempFileType;    
                    }
                }
            }
        }
            /*Application Forms*/
    	$applicationFormWiki = $examContentObj['applicationform'];
    	if(!empty($applicationFormWiki)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['applicationform']['wiki'] =  $this->convertImagesToLazyLoadImages($applicationFormWiki);
                }
                else
                {
                    $formattedExamContentObj['applicationform']['wiki']                = $applicationFormWiki;
                }
                $sectionHavingData[]                    = 'applicationform';
    	}

        $preptipsWiki = $examContentObj['preptips'];
        if(!empty($preptipsWiki)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['preptips']['wiki'] =  $this->convertImagesToLazyLoadImages($preptipsWiki);
                }
                else
                {
                    $formattedExamContentObj['preptips']['wiki']                = $preptipsWiki;
                }
                $sectionHavingData[]                    = 'preptips';
        }

	
	   $vacancyWiki = $examContentObj['vacancies'];
        if(!empty($vacancyWiki)){
                if(!$ampFlag)
                {   
                    $formattedExamContentObj['vacancies']['wiki'] =  $this->convertImagesToLazyLoadImages($vacancyWiki);
                }
                else
                {   
                    $formattedExamContentObj['vacancies']['wiki']         = $vacancyWiki;
                }
                $sectionHavingData[]                    = 'vacancies';
        }
        $callletterWiki = $examContentObj['callletter'];
        if(!empty($callletterWiki)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['callletter']['wiki'] =  $this->convertImagesToLazyLoadImages($callletterWiki);
                }
                else
                {
                    $formattedExamContentObj['callletter']['wiki']         = $callletterWiki;
                }
                $sectionHavingData[]                    = 'callletter';
        }

        $newsWiki = $examContentObj['news'];
        if(!empty($newsWiki)){
                if(!$ampFlag)
                {
                    $formattedExamContentObj['news']['wiki'] =  $this->convertImagesToLazyLoadImages($newsWiki);
                }
                else
                {
                    $formattedExamContentObj['news']['wiki']         = $newsWiki;
                }
                $sectionHavingData[]                    = 'news';
        }

        $sectionHavingData = array_unique($sectionHavingData);
        foreach ($sectionNameWithOrder as $key => $sectioName) {
            if(in_array($sectioName,$sectionHavingData)){
                $formattedExamContentObj['sectionname'][] = $sectioName;
            }
        }
        return $formattedExamContentObj;
    }

    function convertImagesToLazyLoadImages($object)
    {

        $returnObject = array();
        foreach ($object as $k => $key) {
            $htmlBefore = $key['entity_value'];
            $htmlType = $key['entity_type'];

            if(in_array($htmlType, array('Exam Title', 'Phone Number', 'Official Website','applicationformurl')))
            {
                continue;
            }
            if(!empty($htmlBefore))
            {
                $htmlBefore = html_entity_decode($htmlBefore, ENT_HTML5 | ENT_QUOTES);
                $htmlBefore = convertImagesToLazyLoad($htmlBefore);
                $htmlBefore = convertIframesToLazyLoad($htmlBefore);

                //adding nofollow for anchor those are not conataining shiksha urls
                $htmlBefore = addNoFollowForUrlsInWikiData($htmlBefore);
                $object[$k]['entity_value'] = $htmlBefore;
            }
        }
        return $object;
    }
    /**
        Below function is used for check guide downloaded or not.
    */
    function checkActionPerformedOnGroup($groupId,$cookieName)
    {   
        $groupIds = $_COOKIE[$cookieName];
        if(empty($groupIds))
            return false;
        else
        {
            $groupIds = json_decode(base64_decode($groupIds));
            if(in_array($groupId, $groupIds))
            {
                return true;
            }
        }
        return false;
    }


    /**
    * @param $examIds : array of examIds 
    * @param $limit : number of courses/institues that to be returned
    * @param return value is array consisting of institute and it's course in sorted view count order desc.
    */

    function fetchCoursesAcceptingExam($examIds,$limit,$examName,$streamId,$streamName)
    {
        $courseInstitutesMapping = $this->ExamPageCache->getSolrCourseAcceptingExam($examIds[0]);
        if(!empty($courseInstitutesMapping)) 
        {
            $courseInstitutesMappingObject = json_decode($courseInstitutesMapping);
            $courseInstitutesMapping = array();

            $courseInstitutesMapping['instCourseMapping'] = (array) $courseInstitutesMappingObject->instCourseMapping;
            $courseInstitutesMapping['totalCount'] = $courseInstitutesMappingObject->totalCount;
        }
        else
        {
            /*$instituDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');
            $courseInstitutesMapping = $instituDetailLib->getInstitutesFromExams($examIds,10,'examPage');*/
            $this->CI->load->builder('SearchBuilderV3','search');
            $searchBuilderV3 = new SearchBuilderV3();
            $this->request = $searchBuilderV3->getRequest();
            $searchRepository = $searchBuilderV3->getSearchRepository();
            $params = array();
            $params['forExamPage'] = true;
            $params['limit'] = 20;
            $params['examId'] = $examIds[0];
            $params['streamId'] = $streamId;
            $data['searchData'] = $searchRepository->getRawSearchData($params);
            $mappingArray = array();
            foreach ($data['searchData']['instCourseMapping']as $key => $value) {
                $mappingArray[$key] =  $value[0];
            }
            $data['searchData']['instCourseMapping'] = $mappingArray;
            $courseInstitutesMapping = $data['searchData'];
            $this->ExamPageCache->storeSolrCourseAcceptingExam($examIds[0],json_encode($courseInstitutesMapping));
        }   
        $data = array();

        $examId = $examIds[0];
        if(!empty($courseInstitutesMapping['instCourseMapping']) && !empty($courseInstitutesMapping['totalCount']))
        {
            $this->CI->load->builder("nationalCourse/CourseBuilder");
            $courseBuilder = new CourseBuilder();
            $this->courseRepo = $courseBuilder->getCourseRepository();    
            $this->CI->load->builder("nationalInstitute/InstituteBuilder");
            $instituteBuilder = new InstituteBuilder();
            $this->instituteRepo = $instituteBuilder->getInstituteRepository();

            $this->CI->load->helper('image');
            $courseInstitutesMapping['instCourseMapping'] = array_slice($courseInstitutesMapping['instCourseMapping'], 0, $limit, true);
            foreach ($courseInstitutesMapping['instCourseMapping'] as $key => $value) {
                if(!empty($value) && !empty($key))
                {
                    $courseObj = $this->courseRepo->find($value,array('basic')); 
                    $instituteObj = $this->instituteRepo->find($key,array('basic','media'));

                    if(empty($courseObj) || empty($instituteObj))
                        continue;

                    if(count($data['instCourseMapping']) >= $limit)
                    {
                        break;
                    }

                    $headerImage = $instituteObj->getHeaderImage();

                    $mainLocationObj = $instituteObj->getMainLocation();
                    
                    if(!empty($mainLocationObj)){
                        $mainLocation = $mainLocationObj->getCityName();
                    }

                    if($headerImage && $headerImage->getUrl()){
                        $imageLink = $headerImage->getUrl();
                        $imageUrl = getImageVariant($imageLink,6);
                    }
                    else{
                        $imageUrl = MEDIAHOSTURL."/public/images/recommend_dummy.png";
                    }

                    $instituteName = $instituteObj->getName();
                    if(strpos($instituteName, $mainLocation) === false)
                    {
                        $instituteName .= ', '.$mainLocation;
                    }
                    $data['instCourseMapping'][] = array('courseId' => $value, 'instituteId' => $key, 'instituteName' => $instituteName,'courseName' => $courseObj->getName(),'instituteUrl' => $instituteObj->getURL(),'courseUrl' => $courseObj->getURL(),'imageUrl' => $imageUrl,'mainLocation' => $mainLocation);
                }
                
            }
            $data['totalCount'] = $courseInstitutesMapping['totalCount'];

            if($data['totalCount'] >= 1000)
            {
                $data['headingText'] = floor(($data['totalCount']/1000)).'k+'; 
            }
            elseif($data['totalCount'] >= 100)
            {
                $data['headingText'] = (floor(($data['totalCount']/100)) * 100) .'+';   
            }elseif($data['totalCount'] > 10)
            {
                $data['headingText'] = (floor(($data['totalCount']/10)) * 10 ).'+';
            }
            else
            {
                $data['headingText'] = $data['totalCount'];
            }

            if($data['totalCount'] > $limit && !empty($examName))
            {
                $data['srpUrl'] = $this->ExamPageCache->getLinkForAcceptingExam($examId);
		if(empty($data['srpUrl']))
                {
                 if($streamId==0){
                        $data['srpUrl'] = Modules::run('search/SearchV3/createOpenSearchUrl',array('keyword' => $examName, 'requestFrom' => 'examAcceptingWidget','examId' => $examId,'isQuer' => false),true);

                 }else{
                         $data['srpUrl'] = Modules::run('search/SearchV3/createClosedSearchUrl',array('keyword' => $streamName, 'entityId'=>$streamId, 'entityType'=>'s', 'requestFrom' => 'examAcceptingWidget','Exams_Accepted'=>array($examId)),true);
                            $this->ExamPageCache->storeLinkForAcceptingExam($examId,json_encode($data['srpUrl']));
                }
                }
                else
                {
                    $data['srpUrl'] = json_decode($data['srpUrl']);
                }

            }
        }
        return $data;
    }
    function prepareExamPageData(&$displayData)
    {
	$this->CI->benchmark->mark('get_exam_page_repo_start');
        $this->examRequest        = $this->CI->load->library('examPages/ExamPageRequest');
        $this->CI->load->builder('ExamBuilder','examPages');
        $examBuilder          = new ExamBuilder();
        $this->examRepository     = $examBuilder->getExamRepository();
	$this->CI->benchmark->mark('get_exam_page_repo_end');

        //fetch institutes those are accepting current Exam Page Id
	$this->CI->benchmark->mark('fetch_courses_acc_exam_start');
        $limit = !empty($displayData['instituteAccptLimit']) ? $displayData['instituteAccptLimit']: 10;
        $examId = $displayData['examId'];
        $groupId = $displayData['groupId'];
//        $displayData['examAccepting'] = $this->fetchCoursesAcceptingExam(array($examId),$limit,$displayData['examName']);
        $displayData['noSnippetSections'] = array('Exam Title', 'Phone Number', 'Official Website');
	$this->CI->benchmark->mark('fetch_courses_acc_exam_end');

        //get exam content data
	    $this->CI->benchmark->mark('get_exam_content_start');
        $this->CI->load->config('examPages/examPageConfig');
        $sectionNameToUrl = $this->CI->config->item('examPagesActiveSections');
        $sectionNameObject = array_search($displayData['sectionName'], $sectionNameToUrl);

        if(!empty($sectionNameObject))
        {
          $sectionsToAdd = array($sectionNameObject,'sectionname');
          if ($sectionNameObject != "importantdates"){
              $sectionsToAdd[] = "importantdates";
          }
          $examContentObj = $this->examRepository->findContent($groupId, $sectionsToAdd,$displayData['isAmp']);
          $importantDates = $examContentObj['importantdates']['date'];
          if ($sectionNameObject != "importantdates"){
              unset($examContentObj['importantdates']);
          }
          $isHomePage = false;
          $displayData['activeSectionName'] = $sectionNameObject;
        }
        else
        {
          $examContentObj = $this->examRepository->findContent($groupId, 'all', $displayData['isAmp']);
          $isHomePage = true;
          $importantDates = $examContentObj['importantdates']['date'];
        }
        $displayData['upcomingDateInformation'] = $this->getRecentExamDateAndString($importantDates);
        $displayData['isHomePage'] = $isHomePage;
        $displayData['examContent'] = $examContentObj;
  
        $this->CI->benchmark->mark('get_exam_content_end');
        //prepare updatedOn date for each section
        $section1 = array();
        $section2 = array();
        foreach ($displayData['examContent'] as $section => $value) {
            if(!in_array($section,array('importantdates','results','samplepapers','applicationform','sectionname','preptips'))){
                foreach ($value as $key => $obj) {
                    if(!empty($obj) && is_object($obj)){
                        if(strtotime($obj->getUpdatedOn())>0){
                            $time[] = strtotime($obj->getUpdatedOn());
                        }
                    }
                }    
                $section1[$section] = $time;
                unset($time);
            }else if(!in_array($section,array('sectionname'))){
               foreach ($value as $subSection => $row) {
                   if(in_array($subSection, array('wiki','date'))){
                        foreach ($row as $key => $d) {
                            if(!empty($d) && is_object($d)){
                                if(strtotime($d->getUpdatedOn())>0){
                                    $section2[$section][] = strtotime($d->getUpdatedOn());    
                                }
                            }
                        }
                   }else if(in_array($subSection, array('file'))){
                        foreach ($row as $key => $v) {
                            foreach ($v as $key => $vobj) {
                                if(!empty($vobj) && is_object($vobj)){
                                    if(strToTime($vobj->getUpdatedOn())>0){
                                        $section2[$section][] = strToTime($vobj->getUpdatedOn());
                                    }
                                }
                            }
                        }
                   }
               }
            }
        }
        $sectionUpdatedOn = array_merge($section1, $section2);
        foreach ($displayData['examContent']['sectionname'] as $key => $section) {
            if(count($sectionUpdatedOn[$section])){
                $maxTime[] =  max($sectionUpdatedOn[$section]);
                if($section != 'homepage'){
                    $displayData['updatedOn'][$section] = 'Updated on '.date('M d, Y', max($sectionUpdatedOn[$section]));
                }
            }
        }
        if(count($maxTime)>0){
            $displayData['updatedOn']['homepage']    = 'Updated on '.date('M d, Y', max($maxTime));
            $displayData['homepageData']['updationDate'] = date('M d, Y', max($maxTime));
        }
        foreach ($displayData['examContent']['homepage'] as $key => $curObj) { 
            if($curObj->getEntityType() == 'Summary'){
                $displayData['homepageData']['creationDate'] = $curObj->getCreationTime();
            }
        }
        //common redirection
        $this->_checkForCommonRedirections($displayData);
        //get all groups
        $groupList = $displayData['examBasicObj']->getGroupMappedToExam();
        //sort groups
        function group_sort($a,$b)
        {
            if ($a['name']==$b['name']) return 0;
              return (strtolower($a['name'])<strtolower($b['name']))?-1:1;
        }
        usort($groupList,"group_sort");
        $displayData['groupList'] = $groupList;
        if(!empty($groupId)){
            $groupObj  = $this->examRepository->findGroup($groupId);
            if(!empty($groupObj) && is_object($groupObj)){
                $mapping   = $groupObj->getEntitiesMappedToGroup();
                $groupYear = $mapping['year'][0];
                $displayData['groupYear'] = $groupYear;
                $displayData['groupName'] = htmlentities($groupObj->getName());
            }
        }
        $wikiFields = $this->CI->config->item('wikiFields');
        $sectionNameMapping = $this->CI->config->item('sectionNamesMapping');
        
        $displayData['wikiFields'] =  $wikiFields;
        $displayData['sectionNameMapping'] =  $sectionNameMapping;
        $isRootUrl = $displayData['isRootUrl'];
		$examSections = $displayData['examContent']['sectionname'];
        foreach ($examSections as $secKey => $secVal) {
            $displayData['snippetUrl'][$secVal] = $this->examRequest->getUrl($secVal, true, false, 0, $isRootUrl);
            if(!empty($_GET) && count($_GET) > 0)
            {
                $queryParams = $_GET;
                $queryParams = $this->removeQueryParams($queryParams);
                $queryParams = http_build_query($queryParams);
                $displayData['snippetUrl'][$secVal] .= '?'.$queryParams;  
            }
        }
        if(!$displayData['isAmp']){
            $updateLimit = $displayData['updatesLimit'];
        }
              
        $displayData['updates'] = $this->exampagemodel->getUpdateDetails($displayData['examId'], $displayData['groupId'],$updateLimit,array(),$displayData['isAmp']);
        $conductedBy = $displayData['examBasicObj']->getConductedBy();
        $conductedByArr = $this->getConductedBy($conductedBy);
        $displayData['conductedBy'] = is_array($conductedByArr) ? $conductedByArr['conductedBy'] : $conductedBy;

        $grpPrimaryStreamId = '';
        if (in_array(CP_BANNER_COURSE_ENGG, $mapping['course'])){
            $grpPrimaryCourseId = CP_BANNER_COURSE_ENGG;
        }else if(in_array(CP_BANNER_COURSE_MBA, $mapping['course'])){
            $grpPrimaryCourseId = CP_BANNER_COURSE_MBA;
        }else {
            $grpPrimaryCourseId = '';
        }

        if(!empty($mapping['primaryHierarchy'])){
            $this->CI->load->builder('listingBase/ListingBaseBuilder');
            $listingBase = new ListingBaseBuilder();
            $hierarchyRepo = $listingBase->getHierarchyRepository();
	    $streamRepoObj =  $listingBase->getStreamRepository();
            $baseEntities = $hierarchyRepo->getBaseEntitiesByHierarchyId($mapping['primaryHierarchy'], 0, 'array');
            foreach ($baseEntities as $value) {
                $grpPrimaryStreamId = $value['stream_id'];
		       $streamName   = $streamRepoObj->find($grpPrimaryStreamId);
            }
        }

        if(is_object($streamName) && !empty($streamName)){
            $examStreamName = $streamName->getName();
        }   

	    $displayData['examAccepting'] = $this->fetchCoursesAcceptingExam(array($examId),$limit,$displayData['examName'],$grpPrimaryStreamId, $examStreamName, $displayData['searchData']);


        $collegePredBannerDetails = getAndShowCollegePredBanner($grpPrimaryStreamId, $grpPrimaryCourseId);
	//Change to add DFP Banner on JEE Main EP
            if(!empty($collegePredBannerDetails) && $examId != '6244'){
                $displayData['predBannerStream'] = $collegePredBannerDetails['predStream'];
                $displayData['isShowIcpBanner'] = true;
            }else {
                $displayData['isShowIcpBanner'] = false;
        }

        $displayData['streamCheck'] = $this->checkToShowExamCal($mapping);
        if($displayData['isAmp']){
            if($displayData['streamCheck'] == 'beBtech'){
                $categoryName = 'Engineering';
                $courseId = ENGINEERING_COURSE;
                $educationTypeId = EDUCATION_TYPE;
            }else if($displayData['streamCheck'] == 'fullTimeMba'){
                $categoryName = 'MBA';
                $courseId = MANAGEMENT_COURSE;
                $educationTypeId = EDUCATION_TYPE;
            }
            $eventCalfilterArr['courseId'] = $courseId;
            $eventCalfilterArr['educationTypeId'] = $educationTypeId;
            $eventCalfilterArr['categoryName'] = $categoryName;
            $displayData['eventCalfilterArr'] = $eventCalfilterArr;
        }
	$this->CI->benchmark->mark('prepare_sections_start');
        $this->prepareContactInfoData($displayData);
        $this->prepareExamSamplePaperData($displayData);
        $this->prepareExamPrepTipsData($displayData);
        $this->prepareExamImportantDatesData($displayData);
        $this->prepareAppFormData($displayData);
        $this->prepareResultData($displayData);
        if($displayData['isAmp']){
          $displayData['sectionCSSForAMP'] = $this->prepareIndividualCSSForAMP($displayData);            
        }
	$this->CI->benchmark->mark('prepare_sections_end');

        if($displayData['isAmp']){
            $childPage = ($displayData['activeSectionName']) ? $displayData['activeSectionName'] : ''; 
            $childPageIdentifier = $displayData['sectionNameMapping'][$childPage];
            $childPageIdentifier = ($childPageIdentifier) ? 'exam'.str_replace(' ','',$childPageIdentifier).'Page' : 'examPageMain';    
        }
        $beaconTrackData = $this->getBeaconData($mapping,$examId, $groupId,'examPageMain',$displayData['isAmp']);
        if($childPageIdentifier){
            $beaconTrackData['extraData']['childPageIdentifier'] = $childPageIdentifier;
        }
        
        $displayData['beaconTrackData'] = $beaconTrackData;
        $gtmArray = $this->getGTMArray($beaconTrackData, $examId, $groupId, $displayData['validateuser']);
        $displayData['gtmParams'] = $gtmArray;

        
		//get Similar Exam Widget data 
        $isMobile = isset($displayData['isMobile']) ? $displayData['isMobile'] : false;

        //if calling is from AMP page pass $nolimit value as true
        $nolimit = (isset($displayData['isAmp']) && $displayData['isAmp'] === true) ? true : false;
	$this->CI->benchmark->mark('similar_widget_start');
        $displayData['similarExams'] = $this->getSimilarExamWidget($examId,$groupId,$displayData['similarExamLimit'],$nolimit,$isMobile);     
	$this->CI->benchmark->mark('similar_widget_end');

        // seo detail
        $seoDetail = $this->getSeoDetail($displayData['examBasicObj']->getName(), $displayData['seoSection'], $groupYear, $displayData['examBasicObj']->getFullName(), true, $displayData['examId']);
        $displayData['titleText']       = $seoDetail['title'];  
        $displayData['metaDescription'] = $seoDetail['description'];
	$displayData['h1'] = $seoDetail['h1'];
	$displayData['keywords'] = $seoDetail['keywords'];
        $displayData['canonicalurl']    =  $this->examRequest->getUrl($this->sectionName,true); 
        $displayData['ampUrl'] = $displayData['examBasicObj']->getAmpURL($displayData['sectionName'],$sectionNameToUrl);
        $year = (!empty($groupYear))?' '.$groupYear:'';
        $displayData['datesString'] = "Get all ".$displayData['examBasicObj']->getName()."$year important dates such as registration date, application date, admit card date, exam date, result date and counselling date. Sign up so that you don’t miss a single exam date or an update on ".$displayData['examBasicObj']->getName()."$year exam.";

        //amp tracking key Ids
        $displayData['ampKeys'] = $this->CI->config->item('ampTrackingKeys');
        // viewed response data
        $validateuser = !empty($displayData['validateuser']) ? $displayData['validateuser'] : $displayData['userStatus'];
        $validResponseUser = 0;
        if(($validateuser != "false") && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums"))) && ($validateuser[0]['mobile'] != ""))
        {
            $validResponseUser = 1;
            $displayData['validResponseUser'] = $validResponseUser;
        }
        $displayData['viewedResponseAction'] = 'exam_viewed_response';
        $displayData['guideDownloaded'] = $this->checkActionPerformedOnGroup($groupId,'examGuide');
        $displayData['isSubscribe'] = $this->checkActionPerformedOnGroup($groupId,'examSubscribe');
    }

    function getSimilarExamWidget($examId,$groupId,$limit,$nolimit=false,$isMobile=false, $examDigestMailer = false)
    {
        if(empty($examId) || empty($groupId))
            return array();

        if(!$nolimit)
        {
            $finalExamList = $this->ExamPageCache->getSimilarExamsMapping($examId,$groupId,$isMobile);
            if(!empty($finalExamList))
                return $finalExamList;
        }
        else if($nolimit)
        {
            $finalExamList = $this->ExamPageCache->getAllSimilarExamsMapping($examId,$groupId);
            if(!empty($finalExamList))
                return $finalExamList;   
        }   


        //get list of featured exams added on exam from cms
        $featuredExamList = $this->exampagemodel->getFeaturedExams($examId,$groupId);

        $featuredExamData = $featuredExamList['extraData'];
        $featuredExamList = $featuredExamList['rs'];

        //number of exams should be shown in similar exams widget on page load
        $numberOfExamsToShow = $limit; 
        $similarExamsOnCourse = array();
        $similarExamsOnHierarichy = array();
        $excludeExamIds = array();

        if(!empty($featuredExamList) && count($featuredExamList) > 0)
        {
            $temp = array_keys($featuredExamList);
            shuffle($temp);
            $shuffledArray = array();
            foreach ($temp as $key => $value) {
                $shuffledArray[$value] = $featuredExamList[$value];
            }
            $featuredExamList = $shuffledArray;
            $featuredExamList = array_unique($featuredExamList);
        }

        if(count($featuredExamList) <= $numberOfExamsToShow || $nolimit)
        {
            if(!empty($featuredExamList))
            {
                $excludeExamIds   = array_values($featuredExamList);    
                $excludeExamIds = array_merge($excludeExamIds,array($examId));
            }else{
                $excludeExamIds = array($examId);
            }


            $similarExamsOnCourse = $this->exampagemodel->getSimilarExamsByGroupId($groupId,$excludeExamIds);
            if(!empty($similarExamsOnCourse) && count($similarExamsOnCourse) > 0)
            {
                $similarExamsOnCourse = array_unique($similarExamsOnCourse);
            }
            $totalCount = count($featuredExamList)+count($similarExamsOnCourse);
            if($totalCount <= $numberOfExamsToShow || $nolimit)
            {
                if(!empty($featuredExamList))
                {
                    $excludeExamIds = array_values($featuredExamList);    
                    $excludeExamIds = array_merge($excludeExamIds,array($examId));
                }
                if(!empty($similarExamsOnCourse))
                {
                    if(!empty($excludeExamIds))
                    {
                        $excludeExamIds = array_merge($excludeExamIds,array_values($similarExamsOnCourse));        
                    }else
                    {
                        $excludeExamIds = array_values($similarExamsOnCourse);
                    }
                }                
                $similarExamsOnHierarichy = $this->exampagemodel->getSimilarExamsByGroupId($groupId,$excludeExamIds,1);
                if(!empty($similarExamsOnHierarichy))
                {
                    $similarExamsOnHierarichy = array_unique($similarExamsOnHierarichy);
                }
            }
        }

        if(empty($featuredExamList) && empty($similarExamsOnCourse) && empty($similarExamsOnHierarichy))
            return array();

        if(!empty($similarExamsOnCourse))
        {
            $similarExamsOnCourse = $this->getSortedGroupIdsBasedonViewCount($similarExamsOnCourse);
        }

        if(!empty($similarExamsOnHierarichy))
        {
            $similarExamsOnHierarichy = $this->getSortedGroupIdsBasedonViewCount($similarExamsOnHierarichy);
        }
        $showGroupExamMapping = $featuredExamList + $similarExamsOnCourse + $similarExamsOnHierarichy;
        $this->CI->load->builder('examPages/ExamBuilder');
        $examBuilder    = new ExamBuilder();
        $examRepository = $examBuilder->getExamRepository();
        $finalExamList = array();

        $count = 0;

        if(count($showGroupExamMapping) > $limit)
        {
            $finalExamList['isViewLink'] = true;
            if(!$isMobile)
            {   
                $numberOfExamsToShow = $numberOfExamsToShow - 1;
            }
        }

        /*$multipleExamIds = array();

        foreach ($showGroupExamMapping as $examsKey => $examsValue) {
            if(!empty($examsValue))
            {
                $multipleExamIds[] = $examsValue;    
            }
        }

        $multipleExamIds = array_unique($multipleExamIds);
        $multiExamObj = array();

        if(!empty($multipleExamIds) && count($multipleExamIds) > 0)
        {
            $multiExamObj = $examRepository->findMultiple($multipleExamIds);    
        }*/

        foreach ($showGroupExamMapping as $groupKey => $examKey) {
            if($count == $numberOfExamsToShow && !$nolimit)
                break;
            $examObj = $examRepository->find($examKey);    
            //$examObj = $multiExamObj[$examKey];
            if(!empty($examObj))
            {
                $examName    = $examObj->getName();
                $examFullName = $examObj->getFullName();

                if(!empty($examFullName))
                {
		    if($examDigestMailer){
                    	$examName = $examName .' ( '. $examFullName.' )';
		    }}
                $conductedBy = $examObj->getConductedBy();
                $conductedByArr = $this->getConductedBy($conductedBy);
                $conductedBy = is_array($conductedByArr) ? $conductedByArr['conductedBy']['name'] : $conductedBy;
                $url         = $examObj->getUrl();

                $groupMapping = $examObj->getGroupMappedToExam(); 
                $groupName = '';
                foreach ($groupMapping as $gkey => $gvalue) {
                    if($gvalue['id'] == $groupKey)
                    {
                        $groupName = $gvalue['name'];
			//Fetch the Group Obj and get its Year
			$year = '';
			$groupObj  = $examRepository->findGroup($gvalue['id']);
                        if(!empty($groupObj) && is_object($groupObj)){
                                $mapping   = $groupObj->getEntitiesMappedToGroup();
                                $year = $mapping['year'][0];
                        }			
                        break;
                    }
                }
                $finalExamList['similarExams'][] = array('examId' => $examKey,'groupId' => $groupKey,'examName' => $examName, 'conductedBy' => $conductedBy, 'url' => $url,'groupName' => $groupName,'examFullName' => $examFullName,'redirectUrl' => isset($featuredExamData[$groupKey]) ? $featuredExamData[$groupKey]['redirection_url'] : '','CTAText' => isset($featuredExamData[$groupKey]) ? $featuredExamData[$groupKey]['CTA_text'] : '', 'year' => $year);
                $count++;
            }
        }
        if(!empty($finalExamList) && count($finalExamList['similarExams']) > 0 && !$nolimit)
        {
            $this->ExamPageCache->storeSimilarExamsMapping($examId,$groupId,$isMobile,$finalExamList);
        }
        else if(!empty($finalExamList) && count($finalExamList['similarExams']) > 0 && $nolimit)
        {
            $this->ExamPageCache->storeAllSimilarExamsMapping($examId,$groupId,$finalExamList);   
        }
        return $finalExamList;
    }

// invalidate cache data on add/edit exam and group
    function invalidateExamCache(){
        $this->CI->load->config('examPages/examPageConfig');
        $this->ExamPageCache->deleteExamCache(ExamBasicByName);
        $this->ExamPageCache->deleteExamCache('redirectExamsList');
        $this->ExamPageCache->deleteExamCache('listOfExams');
        $this->getExamBasicByName('', true);
        $this->storeRedirectExamsList();
        $this->getAllExams(true);
    }

    function storeRedirectExamsList(){
        error_log('Exam: redirect exam list invalidated');
        $redirectExamsList = $this->exampagemodel->getRedirectExams();
        $redirectExams = array();
        if(count($redirectExamsList) > 0){
            foreach($redirectExamsList as $row){
                $oldName = strtolower(seo_url($row['oldName']));
                $redirectExams[$oldName] = $row['newName'];
            }
        }
        $this->ExamPageCache->storeRedirectExamsList($redirectExams);
    }

    function prepareExamSamplePaperData(&$displayData){
        $samplePapersArr = $displayData['examContent']['samplepapers']['file']['samplepapers']; 
        $guidePapersArr = $displayData['examContent']['samplepapers']['file']['guidepapers'];
        if($displayData['pageName'] == 'mobileExamPage'){
            $maxNoOfPaper = M_totalPaperCount;
            $maxNoOfSamplePaper = M_samplePaperCount;
            $maxNoOfPrepGuide = M_samplePaperCount;
        }else {
            $maxNoOfPaper = D_totalPaperCount; 
            $maxNoOfSamplePaper = D_samplePaperCount;
            $maxNoOfPrepGuide = D_samplePaperCount;
        }
        if(empty($samplePapersArr) && empty($guidePapersArr)){
            $displayData['samplePaperHeading'] = 'Question Papers';
            return;
        } 


        $samplePaperCount = count($samplePapersArr);
        $guidePapersCount = count($guidePapersArr);
        if(($samplePaperCount >= $maxNoOfSamplePaper) && ($guidePapersCount >= $maxNoOfPrepGuide)){
            for($i=0;$i<$maxNoOfSamplePaper;$i++) {
                $displayData['samplePaperData'][$i]['fileName'] = $samplePapersArr[$i]->getFileName();
                $displayData['samplePaperData'][$i]['url'] = $samplePapersArr[$i]->getFileUrl();
            }
            for($i=0;$i<$maxNoOfPrepGuide;$i++) {
                $displayData['guidePaperData'][$i]['fileName'] = $guidePapersArr[$i]->getFileName();
                $displayData['guidePaperData'][$i]['url'] = $guidePapersArr[$i]->getFileUrl();            
            }
        }

        if(($samplePaperCount < $maxNoOfSamplePaper) && ($guidePapersCount >= $maxNoOfPrepGuide)){
            for($i=0;$i<$samplePaperCount;$i++) {
                $displayData['samplePaperData'][$i]['fileName'] = $samplePapersArr[$i]->getFileName();
                $displayData['samplePaperData'][$i]['url'] = $samplePapersArr[$i]->getFileUrl();
            }
            $prepGuidesReq = $maxNoOfPaper - $samplePaperCount;
            if($prepGuidesReq > $guidePapersCount){
                $prepGuidesReq = $guidePapersCount;
            }
            for($i=0;$i<$prepGuidesReq;$i++) {
                $displayData['guidePaperData'][$i]['fileName'] = $guidePapersArr[$i]->getFileName();
                $displayData['guidePaperData'][$i]['url'] = $guidePapersArr[$i]->getFileUrl();            
            }
        }

        if(($guidePapersCount < $maxNoOfPrepGuide) && ($samplePaperCount >= $maxNoOfSamplePaper)){
            $samplePaperReq = $maxNoOfPaper - $guidePapersCount;
            if($samplePaperReq > $samplePaperCount){
                $samplePaperReq = $samplePaperCount;
            }
            for($i=0;$i<$samplePaperReq;$i++) {
                $displayData['samplePaperData'][$i]['fileName'] = $samplePapersArr[$i]->getFileName();
                $displayData['samplePaperData'][$i]['url'] = $samplePapersArr[$i]->getFileUrl();
            }
            for($i=0;$i<$guidePapersCount;$i++) {
                $displayData['guidePaperData'][$i]['fileName'] = $guidePapersArr[$i]->getFileName();
                $displayData['guidePaperData'][$i]['url'] = $guidePapersArr[$i]->getFileUrl();            
            }
        }
        if((($guidePapersCount < $maxNoOfPrepGuide) && ($samplePaperCount < $maxNoOfSamplePaper)) || ($displayData['sectionName'] == 'question-papers' && !empty($displayData['sectionName']))){
            for($i=0;$i<$samplePaperCount;$i++) {
                $displayData['samplePaperData'][$i]['fileName'] = $samplePapersArr[$i]->getFileName();
                $displayData['samplePaperData'][$i]['url'] = $samplePapersArr[$i]->getFileUrl();
            }
            for($i=0;$i<$guidePapersCount;$i++) {
                $displayData['guidePaperData'][$i]['fileName'] = $guidePapersArr[$i]->getFileName();
                $displayData['guidePaperData'][$i]['url'] = $guidePapersArr[$i]->getFileUrl();            
            }
        }

        if($samplePaperCount > 0 && $guidePapersCount > 0){
            $displayData['samplePaperHeading'] = 'Question Papers & Prep Guides';             
        }
        if($samplePaperCount > 0 && $guidePapersCount == 0 ){
            $displayData['samplePaperHeading'] = 'Question Papers';             
        }
        if($guidePapersCount > 0 && $samplePaperCount == 0){
            $displayData['samplePaperHeading'] = 'Prep Guides';             
        }
    }
    function prepareExamPrepTipsData(&$displayData)
    {

        $preptips = $displayData['examContent']['preptips']['file']; 
        if($displayData['pageName'] == 'mobileExamPage'){
            $maxNoOfPaper = M_preptipsCount;
        }else {
            $maxNoOfPaper = D_preptipsCount; 
        }
        
        $preptipsFileCount = count($preptips);
        if(($preptipsFileCount >= $maxNoOfPaper) && empty($displayData['sectionName'])) {
            for($i=0;$i<$maxNoOfPaper;$i++) {
                $displayData['preptipsData'][$i]['fileName'] = $preptips[$i]->getFileName();
                $displayData['preptipsData'][$i]['url'] = $preptips[$i]->getFileUrl();
            }
        }
        else{
            for($i=0;$i<$preptipsFileCount;$i++) {
                $displayData['preptipsData'][$i]['fileName'] = $preptips[$i]->getFileName();
                $displayData['preptipsData'][$i]['url'] = $preptips[$i]->getFileUrl();
            }   
        }    
    }

    function getContentDeliveryData($examId, $groupId){
        $this->exammodel = $this->CI->load->model('examPages/exammodel');
        $result = $this->exammodel->getContentDeliveryData($examId, $groupId);
        return $result;
    }


    function _checkForCommonRedirections($data){
        $redirectXssCase = false;
        $examUrl = getCurrentPageURLWithoutQueryParams();
        $examContent = $data['examContent'];
        unset($examContent['sectionname']);
        $examContent = !$data['isHomePage'] ? $examContent[$data['activeSectionName']] : $examContent;

        $queryParams = $_GET;
        if($data['course'] == $data['primaryGroupId'] || (empty($examContent) && $data['course'] != $data['primaryGroupId'] && $data['isHomePage']))
        {
            unset($queryParams['course']);   
        }
        if((empty($examContent) && $data['course'] != $data['primaryGroupId'] && !$data['isHomePage']))
        {
            $examUrl  = $data['redirectOriginalUrl'];
        }

        if(!empty($queryParams['course']) && !is_numeric($queryParams['course']))
        {
            $redirectXssCase = true;
            unset($queryParams['course']);
        }

        if(!empty($queryParams))
        {
            $queryParams = '?'.http_build_query($queryParams);    
            $examUrl .= $queryParams;
        }
        
        if(empty($examContent) && $data['groupId'] == $data['primaryGroupId'] && $data['isHomePage'] ){
            show_404();
            exit(0);
        }elseif(empty($examContent) && $data['course'] != $data['primaryGroupId']){
            if( (strpos($examUrl, "http") === false) || (strpos($examUrl, "http") != 0) || (strpos($examUrl, SHIKSHA_HOME) === 0) || (strpos($examUrl,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($examUrl,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($examUrl,ENTERPRISE_HOME) === 0) ){
                header("Location: $examUrl",TRUE,301);
            }
            else{
                header("Location: ".SHIKSHA_HOME,TRUE,301);
            }
            exit;
        }elseif((!empty($examContent) && $data['course'] == $data['primaryGroupId']) || $redirectXssCase) {
            if( (strpos($examUrl, "http") === false) || (strpos($examUrl, "http") != 0) || (strpos($examUrl, SHIKSHA_HOME) === 0) || (strpos($examUrl,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($examUrl,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($examUrl,ENTERPRISE_HOME) === 0) ){
                header("Location: $examUrl",TRUE,301);
            }
            else{
                header("Location: ".SHIKSHA_HOME,TRUE,301);
            }
            exit;
        }

    }
	 
	public function getSeoDetail($examName, $section, $year, $examFullName = '',$fetchFromDb = true, $examId){ 
        $examName = $examName;
	$examNameLower = strtolower($examName);
	$origYear = $prevYear = '';
        if(!empty($year)){
	    $prevYear = $year - 1;
	    $origYear = $year;
            $year     = ' '.$year;
        }
        $examFullNameOrig = '';
	if(!empty($examFullName)){
        $examFullNameOrig = $examFullName;
	    $examFullName = ' - '.$examFullName;
	}
        
        if($section !='homepage'){
            $this->CI->load->config('examPages/examPageConfig.php');
            $sectionNameMappings = $this->CI->config->item('sectionNamesMapping');  
            $sectionName = ' '.$sectionNameMappings[$section];
        }

	switch ($section){
		case 'homepage':
				$data['title'] = "$examName$year Exam: Registration, Syllabus, Results, Dates at Shiksha";
				$data['h1'] = "$examName$examFullName$year";
				$data['description'] = "Get all details of $examName$year Exam like dates, eligibility, application form, syllabus, admit card, results, pattern, preparation tips, question papers and more at Shiksha.com.";
				$data['keywords'] = "$examNameLower, $examNameLower exam, $examNameLower$year, $examNameLower exam$year";
				break;
                case 'admitcard':
                                $data['title'] = "$examName Admit Card$year: Get $examName Exam$year Admit Card at Shiksha.com";
                                $data['h1'] = "$examName Admit Card$year";
                                $data['description'] = "Download $examName$year admit card at Shiksha.com. You can get $examName admit card$year using your application number & password at Shiksha.com.";
                                $data['keywords'] = "$examNameLower admit card, $examNameLower$year admit card, $examNameLower admit card$year, $examNameLower exam admit card";
                                break;
                case 'answerkey':
                                $data['title'] = "$examName Answer Key$year: Get $examName Exam Latest Answer Key at Shiksha.com";
                                $data['h1'] = "$examName Answer Key$year";
                                $data['description'] = "Get $examName$year answer key at Shiksha.com. Read all about latest updated answer key of $examName exam$year including this year, last year & more at Shiksha.com";
                                $data['keywords'] = "$examNameLower answer key, $examNameLower$year answer key, $examNameLower exam answer key, $examNameLower exam$year answer key";
                                break;
                case 'importantdates':
                                $data['title'] = "$examName Exam dates$year: Result & Registration dates of $examName$year at Shiksha";
                                $data['h1'] = "$examName Exam Dates$year";
                                $data['description'] = "Get $examName exam$year important dates at Shiksha.com. Know all$year $examName dates such as Result dates, Application dates, admit card dates for $examName$year.";
                                $data['keywords'] = "$examNameLower exam date, $examNameLower dates, $examNameLower result date, $examNameLower registration dates, $examNameLower result dates";
                                break;
                case 'applicationform':
                                $data['title'] = "$examName Application$year: Registration & Application for $examName at Shiksha";
                                $data['h1'] = "$examName Application & Registration Form$year";
                                $data['description'] = "Get $examName$year exam Registration & Application form at Shiksha.com. Know dates, fees & how to fill $examName exam Registration & Application form at Shiksha.com";
                                $data['keywords'] = "$examNameLower application form, $examNameLower$year application form, $examNameLower exam registration form, $examNameLower exam$year registration form";
                                break;
                case 'counselling':
                                $data['title'] = "$examName Counselling$year: Check $examName counselling Dates & Centres at Shiksha.com";
                                $data['h1'] = "$examName Counselling$year";
                                $data['description'] = "Know $examName Exam$year counselling dates, procedure & your near by counselling centres at Shiksha.com. Also check $examName top colleges & their rank based on last year counselling in India.";
                                $data['keywords'] = "$examNameLower counselling, $examNameLower exam counselling, $examNameLower dates$year, $examNameLower exam counselling centers";
                                break;
                case 'cutoff':
                                $data['title'] = "$examName Cut Off$year: Get $examName Exam$year & $prevYear Cut Off at Shiksha.com";
                                $data['h1'] = "$examName Cut Off$year";
                                $data['description'] = "Get $examName$year exam cut off at Shiksha.com. Read all about this year($origYear) & last year($prevYear) cut off of $examName exam at Shiksha.com";
                                $data['keywords'] = "$examNameLower cut off, $examNameLower$year cut off, $examNameLower exam cut off, $examNameLower exam$year cut off";
                                break;
                case 'pattern':
                                $data['title'] = "$examName Pattern$year: Get $examName Exam$year Pattern at Shiksha.com";
                                $data['h1'] = "$examName Pattern$year";
                                $data['description'] = "Get $examName$year exam pattern at Shiksha.com. Read all about latest updated pattern of $examName exam, counselling and admission pattern$year at Shiksha.com";
                                $data['keywords'] = "$examNameLower pattern, $examNameLower$year pattern, $examNameLower exam pattern, $examNameLower exam$year pattern";
                                break;
                case 'results':
                                $data['title'] = "$examName Result$year: Check $examName Exam$year Result at Shiksha.com";
                                $data['h1'] = "$examName Result$year";
                                $data['description'] = "Get your $examName$year result in easy steps at Shiksha.com and also check the $examName Exam scorecard, rank list and top colleges in India.";
                                $data['keywords'] = "$examNameLower result, $examNameLower exam result, $examNameLower result$year, $examNameLower exam result$year, $examNameLower$year result";
                                break;
                case 'samplepapers':
                                $data['title'] = "$examName Question Papers$year - Download Previous Year Question & Sample Papers";
                                $data['h1'] = "$examName Question & Sample Papers$year";
                                $data['description'] = "Get $examName question papers$year for improving your speed and accuracy. Aspirants can download here previous year $examName question papers and sample papers free of cost.";
                                $data['keywords'] = "$examNameLower$year question paper, $examNameLower question paper, $examNameLower$year question paper, $examNameLower sample paper$year";
                                break;
                case 'slotbooking':
                                $data['title'] = "$examName Slot Booking$year: Available Dates & Time for Slot Booking";
                                $data['h1'] = "$examName Slot Booking$year";
                                $data['description'] = "Check $examName$year Exam Slot booking dates & time at Shiksha.com. Know all about $examName Exam Slot booking procedure, fees, availability & centers for year$year.";
                                $data['keywords'] = "$examNameLower slot booking, $examNameLower exam slot booking, $examNameLower slot booking$year, $examNameLower$year slot booking";
                                break;
                case 'syllabus':
                                $data['title'] = "$examName Syllabus$year: Get $examName Exam Latest Syllabus at Shiksha.com";
                                $data['h1'] = "$examName Syllabus$year";
                                $data['description'] = "Get $examName$year syllabus at Shiksha.com. Read all about latest updated syllabus of $examName exam$year including quantitative aptitude, verbal ability, logical reasoning & more at Shiksha.com";
                                $data['keywords'] = "$examNameLower syllabus, $examNameLower$year syllabus, $examNameLower exam syllabus, $examNameLower exam$year syllabus";
                                break;
                case 'preptips':
                                $data['title'] = "$examName Preparation Tips & Guide - Strategies to Crack $examName Exam$year @ Shiksha";
                                $data['h1'] = "$examName Preparation Tips & Guide$year";
                                $data['description'] = "Know how to prepare for the $examName exam$year at Shiksha.com. Read all section wise preparation Tips & Strategies for $examName Exam And target important & high scoring sections suggested by Shiksha experts.";
                                $data['keywords'] = "";
                                break;
                case 'vacancies':
                                $data['title'] = "$examName$year Vacancies - Total Seats for $examName$year @ Shiksha.com";
                                $data['h1'] = "$examName Vacancies & Seats$year";
                                $data['description'] = "Know how many $examName vacancies & seats are available this year. At Shiksha.com read full information on $examName Vacancy$year along with seats information based on categories and states across India.";
                                $data['keywords'] = "";
                                break;
                case 'callletter':
                                $data['title'] = "$examName Exam$year Call Letters for Interview & Next Rounds @ Shiksha.com";
                                $data['h1'] = "$examName Call Letter for Interview$year";
                                $data['description'] = "Check and Download $examName$year call letter for the interview & other rounds. You'll find latest status of $examName call letters along with release date & download procedure at Shiksha.com .";
                                $data['keywords'] = "";
                                break;
                case 'news':
                                $data['title'] = "$examName Exam$year News & Notifications - Admit Card, Dates, Cut Offs & more @ Shiksha";
                                $data['h1'] = "$examName News & Notifications";
                                $data['description'] = "Get latest news, notifications and updates on $examName exam$year only @ Shiksha.com. Read all $examName notifications related to admit cards, vacancy, seats, results, call letter and more at Shiksha.com.";
                                $data['keywords'] = "";
                                break;
                default:
                                $data['title'] = "$examName$year Exam: Registration, Syllabus, Results, Dates at Shiksha";
                                $data['h1'] = "$examName$examFullName$year";
                                $data['description'] = "Get all details of $examName$year Exam like dates, eligibility, application form, syllabus, admit card, results, pattern, preparation tips, question papers, and more at Shiksha.com.";
                                $data['keywords'] = "$examNameLower, $examNameLower exam, $examNameLower$year, $examNameLower exam$year";
                                break;			

	}

    if($fetchFromDb){
        $this->exammodel = $this->CI->load->model('examPages/exammodel');
        $result = $this->exammodel->getExamPageSeo($examId, $section);

        $search = array('#examName#', '#year#', '#examFullName#');
        $replace = array($examName, $origYear, $examFullNameOrig);
        
        if(!empty($result['metaTitle'])){
            $data['title'] = $result['metaTitle'];
            $data['title'] = str_replace($search, $replace, $data['title']);
        }
        if(!empty($result['metaDescription'])){
            $data['description'] = $result['metaDescription'];
            $data['description'] = str_replace($search, $replace, $data['description']);
        }
        if(!empty($result['h1Tag'])){
            $data['h1'] = $result['h1Tag'];
            $data['h1'] = str_replace($search, $replace, $data['h1']);
        }
    }

	/*
        if($section =='homepage'){
            $data['title']       = "$examName$year Exam: Eligibility, Dates, Registration, Syllabus, Results";
            $data['description'] = "View all details about $examName$year exam like eligibility, application form, dates, syllabus, admit card, results, pattern, preparation tips, question papers, and much more.";
        }else{
            $data['title']       = "$examName$year$sectionName | Shiksha.com";
            $data['description'] = "Read more about $examName$year$sectionName. Get all details about $examName $year on Shiksha.com";
            $data['h1']   = "$examName$year$sectionName";
        }
	*/
        return $data;
    }

    function prepareExamImportantDatesData(&$displayData){
    $dateObj = $displayData['examContent']['importantdates']['date'];   
    if(empty($dateObj)){
        return;
    }
    for($i=0;$i<count($dateObj); $i++) {
      $eventStartDate = $dateObj[$i]->getStartDate();
      $eventEndDate = $dateObj[$i]->getEndDate();
      if($eventStartDate == '0000-00-00' && $eventEndDate == '0000-00-00'){
        continue;
      }    
      $currentYear = date('Y');$currentMonth = date('n');$currentDate = date('j');
      $eventStartMonthName = substr(date('F', strtotime($eventStartDate)),0,3);
      $eventStartMonth = date('m', strtotime($eventStartDate));
      $eventStartYear = date('Y',strtotime($eventStartDate));
      $eventEndMonth = date('m', strtotime($eventEndDate));
      $eventEndMonthName = substr(date('F', strtotime($eventEndDate)), 0, 3);
      $eventEndYear = date('Y',strtotime($eventEndDate));
      $eventStartDay = date('d',strtotime($eventStartDate));
      $eventEndDay = date('d',strtotime($eventEndDate));
      $datesArr[$i]['event'] = $dateObj[$i]->getEventName();
      $datesArr[$i]['fullStartDate'] = strtotime($eventStartDate);
      $datesArr[$i]['fullEndDate'] = strtotime($eventEndDate);
      $datesArr[$i]['eventStartMonthName'] = $eventStartMonthName;
      $datesArr[$i]['eventEndMonthName'] = $eventEndMonthName;

      $datesArr[$i]['start_month'] = $eventStartMonth;
      $datesArr[$i]['start_date'] = $eventStartDay;
      $datesArr[$i]['start_year'] = $eventStartYear;
      $datesArr[$i]['end_month'] = $eventEndMonth;
      $datesArr[$i]['end_date'] = $eventEndDay;
      $datesArr[$i]['end_year'] = $eventEndYear;

    }
    uasort($datesArr,function($a,$b){
        $first = mktime(0,0,0,$a['start_month'],$a['start_date'],$a['start_year']);
        $second = mktime(0,0,0,$b['start_month'],$b['start_date'],$b['start_year']);
        if($first == $second){
            $first = mktime(0,0,0,$a['end_month'],$a['end_date'],$a['end_year']);
            $second = mktime(0,0,0,$b['end_month'],$b['end_date'],$b['end_year']);
            if($first == $second){
                return 0;
            }
            return ($first < $second) ? -1 : 1;
        }
        return ($first < $second) ? -1 : 1;
    });
    $i = 0;
    $j = 0;
    foreach ($datesArr as $key1 => $value1) {
        $finalDateArr[$value1['eventStartMonthName'].' '.$value1['start_year']][$i]['startDate'] = $value1['eventStartMonthName'].' '.$value1['start_date'];
        $finalDateArr[$value1['eventStartMonthName'].' '.$value1['start_year']][$i]['endDate'] = $value1['eventEndMonthName'].' '.$value1['end_date'];
        $finalDateArr[$value1['eventStartMonthName'].' '.$value1['start_year']][$i]['event'] = $value1['event'];
        if(($value1['fullStartDate'] <= strtotime(date('Y-m-d'))) && ($value1['fullEndDate'] >= strtotime(date('Y-m-d')))){
            $finalDateArr[$value1['eventStartMonthName'].' '.$value1['start_year']][$i]['isOngoing'] = 1;
        }else{
            $finalDateArr[$value1['eventStartMonthName'].' '.$value1['start_year']][$i]['isOngoing'] = 0; 
        }
        if(($value1['fullStartDate'] > strtotime(date('Y-m-d'))) && $j == 0){
            $finalDateArr[$value1['eventStartMonthName'].' '.$value1['start_year']][$i]['isUpcoming'] = 1;
            $j++;
            $upcomingStartDate = $value1['fullStartDate'];
            $upcomingEndDate = $value1['fullEndDate'];
        }else if($value1['fullStartDate'] == $upcomingStartDate && $value1['fullEndDate'] == $upcomingEndDate) {
            $finalDateArr[$value1['eventStartMonthName'].' '.$value1['start_year']][$i]['isUpcoming'] = 1;
        }else{
            $finalDateArr[$value1['eventStartMonthName'].' '.$value1['start_year']][$i]['isUpcoming'] =0;            
        }
        $i++;
    }
        $displayData['importantDatesData']['dates'] = $finalDateArr;
    }

    function prepareAppFormData(&$displayData){
        $appFormWikiObj = $displayData['examContent']['applicationform']['wiki'];
        $appFormFileObj = $displayData['examContent']['applicationform']['file'][0];
        if(!empty($appFormFileObj) && is_object($appFormFileObj)){
          $fileUrl = $appFormFileObj->getFileUrl();
        }
        if(!empty($appFormWikiObj)){
            foreach ($appFormWikiObj as $key => $obj) {
                if(empty($obj) || !is_object($obj)){
                    continue;
                }
                if($obj->getEntityType() == 'applicationform'){
                    $appFormWiki = $obj->getEntityValue();
                }else if($obj->getEntityType() == 'applicationformurl'){
                    $formURL = $obj->getEntityValue();
                    if (!empty($formURL)) {
                        if(false === strpos($formURL, '://')){
                            $formURL = 'http://' . $formURL;
                        }
                        $gaTracking = 'ga-attr="APPLICATION_FORM_URL"';
                        if($displayData['isAmp'])
                        {   
                            $gaTracking = 'data-vars-event-name="APPLICATION_FORM_URL"';
                        }
                        $formURL = '<a class="ga-analytic" rel="nofollow" target="_blank" href="'.$formURL.'" '.$gaTracking.'> (click to visit)</a>';
                    }
                }
            }
        }
        $displayData['appFormData']['appFormWiki'] = $appFormWiki;
        $displayData['appFormData']['formURL'] = $formURL;
        $displayData['appFormData']['fileUrl'] = $fileUrl;
    }

    function prepareResultData(&$displayData){
    $resultDate = $displayData['examContent']['results']['date'][0];
    if(empty($resultDate) || !is_object($resultDate)){
        return;
    }
    $eventName = $resultDate->getEventName();
    if(empty($eventName)){
        $eventName = 'Result Declaration Date:';
    }
    if(!empty($resultDate)){
        $startDate = $resultDate->getStartDate(); 
        $endDate = $resultDate->getEndDate();
        if($startDate == '0000-00-00' && $endDate == '0000-00-00'){
            return;
        }
        $eventStartMonth = substr(date('F', strtotime($startDate)), 0,3);
        $eventStartYear = date('Y',strtotime($startDate));
        $eventEndMonth = substr(date('F', strtotime($endDate)), 0, 3);
        $eventEndYear = date('Y',strtotime($endDate));
        $eventStartDay = date('d',strtotime($startDate));
        $eventEndDay = date('d',strtotime($endDate));
        $displayData['resultData']['startDate'] = $eventStartDay.' '.$eventStartMonth.' '.$eventStartYear;   
        $displayData['resultData']['endDate'] = $eventEndDay.' '.$eventEndMonth.' '.$eventEndYear;     
        $displayData['resultData']['eventName'] = $eventName;     
  
    }
    }

    function getFeaturedCollegeData($examId, $groupId){
        $this->exammodel = $this->CI->load->model('examPages/exammodel');
        $result = $this->exammodel->getFeaturedCollegeData($examId, $groupId);
        if(empty($result)){
            return $result;
        }
    	$finalData= array();
    	foreach($result as $key=>$data){
    		$instArr[]   = $data['dest_listing_id'];
    		$courseArr[] = $data['dest_course_id'];
    	}
    	/*Loading Institute Builder*/
    	$this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo    = $instituteBuilder->getInstituteRepository();
    	$instituteObj     = $instituteRepo->findMultiple($instArr,array('basic','media'));
    	$insData = array();
        $this->CI->load->helper('image');
    	foreach($instituteObj as $insId=>$obj){
    		$insData[$insId]['name']       = $obj->getName();
            $insData[$insId]['insUrl']     = $obj->getUrl();
            $mainLocationObj = $obj->getMainLocation();
            if((is_object($mainLocationObj) && !empty($mainLocationObj))){
                $insData[$insId]['location']   = $mainLocationObj->getCityName();
                if(strpos($insData[$insId]['name'], $insData[$insId]['location']) === false)
                {
                $insData[$insId]['name'] .= ', '.$insData[$insId]['location'];
                }
            }
    		$headerImage = $obj->getHeaderImage();
            if($headerImage && $headerImage->getUrl()){
             	$imageLink = $headerImage->getUrl();
                    $insData[$insId]['imageUrl']  = getImageVariant($imageLink,6);
             }
             else{
                    $insData[$insId]['imageUrl']  = MEDIAHOSTURL."/public/images/recommend_dummy.png";
            }

    	}
    	/*Loading Course Builder*/
    	$this->CI->load->builder("nationalCourse/CourseBuilder");
        $builder    = new CourseBuilder();
        $courseRepo = $builder->getCourseRepository();
    	$courseObj  =  $courseRepo->findMultiple($courseArr, array('basic'));
    	$courseData = array();
    	foreach($courseObj as $courseId=>$obj){
                $courseData[$courseId]['url']             = $obj->getUrl();
                $courseData[$courseId]['courseName']     = $obj->getName();
        }
        $count = 0;	
    	foreach($result as $key=>$data){
    		$finalData[$count]['insName']    =  $insData[$data['dest_listing_id']]['name'];
            $finalData[$count]['insUrl']    =  $insData[$data['dest_listing_id']]['insUrl'];
    		$finalData[$count]['insLoc']     =  $insData[$data['dest_listing_id']]['location'];
    		$finalData[$count]['insImage']   =  $insData[$data['dest_listing_id']]['imageUrl'];
            $finalData[$count]['courseName'] =  $courseData[$data['dest_course_id']]['courseName'];
    		$finalData[$count]['courseUrl']  =  $courseData[$data['dest_course_id']]['url'];
            $finalData[$count]['CTAText']  =  $data['CTA_text'];
            $finalData[$count]['redirectUrl']  =  $data['redirection_url'];
            if(false === strpos($data['redirection_url'], '://')){
                if(false === strpos($data['redirection_url'], 'shiksha.com')){
                    $domain = 'http://';
                }else{
                    $domain = 'https://';
                }
                $finalData[$count]['redirectUrl']  = $domain.$data['redirection_url'];
            }
		    $count++;
    	}	
        return $finalData;
    }
	function getViewCount($entityIds=array(),$type="exampage",$durationInDays="365"){

        if(empty($entityIds) || $entityIds == null){
            return array();
        }

        if($type == 'exampage')
        {
            $hashKey = 'examgroup_view_count';    
        }
        
        // get view count data from redis
        $redis_client = PredisLibrary::getInstance();
        $viewCount = $redis_client->getMembersOfHashByFieldNameWithValue($hashKey,$entityIds);
        arsort($viewCount);
        return $viewCount;
    }
    function getSortedGroupIdsBasedonViewCount($groupExamIds)
    {
        if(empty($groupExamIds))
            return array();
        $sortedGroupExamIds = array();
        $sortedGroupIds = $this->getViewCount(array_keys($groupExamIds));
            $sorderSimilarExamsOnCourse = array();
            foreach ($sortedGroupIds as $skey => $svalue) {
                $sortedGroupExamIds[$skey] = $groupExamIds[$skey];
            }
        return $sortedGroupExamIds;
    }

    /**
     * fetch article according to tags
     * @param  string $type
     * @param  string $blogIds
     * @param  integer $count
     * @param  integer $offset
     * @return array
     */
    function getExamArticles($type,$blogIds,$count=null,$offset=null){
        $examPageArticles = $this->exampagemodel->getExamArticles($type,$blogIds,$count,$offset);
        return $examPageArticles;
    }

    function prepareContactInfoData(&$displayData){
        foreach ($displayData['examContent']['homepage'] as $key => $curObj) { 
          if($curObj->getEntityType() == 'Phone Number'){
            $contactData['phoneNumber'] = $curObj->getEntityValue();
          }
          if($curObj->getEntityType() == 'Official Website'){
            $website = $curObj->getEntityValue();
          }
        }
        if(!empty($website)){ 
            if (false === strpos($website, '://')) {
                $website = 'http://' . $website;
            }
            $gaTracking = 'ga-attr="WEBSITE_LINK_CONTACT"';
            if($displayData['isAmp'])
            {
                $gaTracking = 'data-vars-event-name="WEBSITE_LINK_CONTACT"';
            }
            $website = '<a rel="nofollow" target="_blank" href="'.$website.'" class="ga-analytic" '.$gaTracking.'>'.$website.'</a>';
        }
        $contactData['website'] = $website;
        $displayData['contactData'] = $contactData;

    }
    //below function is used for fetch number of pageviews for groupId from Elastic Search
    function fetchExamPageViewCount($durationInDays = 365)
    {
        $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $clientCon = $ESConnectionLib->getShikshaESServerConnection();

        //prepare fetch exam page count query

        $startDate = time();
        $startDate = date('Y-m-d', strtotime('-'.$durationInDays.' day', $startDate));
        $startDate .= ' 00:00:00';
        $startDate = convertDateISTtoUTC($startDate);
        $startDate = str_replace(" ", "T", $startDate);
    
        $elasticQuery['index'] = PAGEVIEW_INDEX_NAME;
        $elasticQuery['type']  = 'pageview';

        $elasticQuery['body']['size'] = 0;
        $elasticQuery['body']['query'] = array();

        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['isStudyAbroad'] = "no";

        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['pageIdentifier'] = 'examPage';

        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['range'] = array(
            'visitTime' => array(
                'gte' => $startDate
            )
        );

        $elasticQuery['body']['aggs']['pageViews'] = array(
            'terms' => array(
                'field'    => 'groupId',
                'size' => ELASTIC_AGGS_SIZE
        ));
        $result = $clientCon->search($elasticQuery);
        $result = $result['aggregations']['pageViews']['buckets'];
        $finalArray = array();
        foreach ($result as $key => $value) {
            $finalResult[$value['key']] = $value['doc_count'];
        }
        return $finalResult;
    }

    function getResponsesForExam($groupIds){
        $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $clientCon = $ESConnectionLib->getShikshaESServerConnection();

        //prepare fetch exam page response query
        $elasticQuery['index'] = LDB_RESPONSE_INDEX_NAME;
        $elasticQuery['type']  = 'response';

        $elasticQuery['body']['size'] = 10000;
        $elasticQuery['body']['query'] = array();
        $elasticQuery['body']['_source'] = array('user_id','listing_type_id');

        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['terms']['listing_type_id'] = $groupIds;

        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['response_listing_type'] = 'exam';

        $result = $clientCon->search($elasticQuery);
        $result = $result['hits']['hits'];

        foreach($result as $key=>$val){
            $userIds[] = $val['_source']['user_id'];
            $res[$val['_source']['user_id']]= array('listing_type_id'=>$val['_source']['listing_type_id']);
        }
        
        $this->userLib    = $this->CI->load->library('user/UserLib');
        $userData         = $this->userLib->getUserDataFromSolr(array_unique($userIds));
        foreach($userData as $key=>$val){
            $userDetails[$val['userid']] = array('email'=>$val['Email'],'displayName'=>$val['First Name']);
        }

        foreach($res as $user_id=>$val){
            $res[$user_id] = $userDetails[$user_id];
            $res[$user_id]['groupId'] = $val['listing_type_id'];
        }

        return $res;
    }

    function examUrlRequest($params, $isAmp=false){
        $isRootUrl = 'No';
        $this->examRequest    = $this->CI->load->library('examPages/ExamPageRequest',$params);
        if($this->examRequest->isRootUrl() && $this->examRequest->isRootUrl() == 'Yes'){
            $isRootUrl = 'Yes';            
        }
        $this->examRequest->validateUrl($params, $isAmp, $isRootUrl);
        $data['sectionName']  = $this->examRequest->getSectionName();
        $data['examName']     = $this->examRequest->getExamName();
        $data['examId']       = $this->examRequest->getExamId();
        return $data;
    }

    function getExamBasicByName($urlQueryString, $isCache=false){
        $urlQueryString = strtolower(seo_url($urlQueryString));
        $examArr = $this->ExamPageCache->getExamBasicByName($urlQueryString);
        if(empty($examArr['examName']) || $isCache){
            error_log('Basic Exam data : basic exam list cache invalidated');
            $this->exammainmodel = $this->CI->load->model('examPages/exammainmodel');
            $examList = $this->exammainmodel->getAllExams();
            foreach ($examList as $exam) {
                $urlExam = strtolower(seo_url($exam['examName']));
                $examData[$urlExam] = array(
                                            'examId'=>$exam['examId'],
                                            'examName'=>$exam['examName'],
                                            'url'=>$exam['url'],
                                            'isRootUrl'=>$exam['isRootUrl']
                                        );
            }
            $this->ExamPageCache->setExamBasicByName($examData);
            return $examData[$urlQueryString];
        }else{
            return $examArr;
        }        
    }

    function prepareIndividualCSSForAMP(&$displayData){
        $examContentObj = $displayData['examContent'];
        $wikiFields = $displayData['wikiFields'];
        $homePageExternalCSS = '';
        $wikiExternalCSS = '';
        $otherExternalCSS = '';
        foreach ($examContentObj['homepage'] as $key => $value) {
            $sectionCSS1 = $value->getExternalCSS();
            $homePageExternalCSS = $homePageExternalCSS.$sectionCSS1;
        }
        foreach ($examContentObj['sectionname'] as $section) {
            if(array_key_exists($section, $wikiFields) && !empty($examContentObj[$section])){
                foreach ($examContentObj[$section] as $secKey => $secVal) {
                    $sectionCSS2 = $secVal->getExternalCSS();
                    $wikiExternalCSS =  $wikiExternalCSS.$sectionCSS2;
                }
            }
            else {
                foreach($examContentObj[$section]['wiki'] as $secKey1 => $secVal1){
                        $sectionCSS3 = $secVal1->getExternalCSS();
                        $otherExternalCSS =  $otherExternalCSS.$sectionCSS3;
                }   
            }
        }
        $externalCSS = $homePageExternalCSS.$wikiExternalCSS.$otherExternalCSS;
        return $externalCSS;
    }

    /**
     * purpose: To get ANA widget data
     * @param  examId, logged in user id
     */
    function getAnAWidgetData($examId, $userId, $count = 3, $isAmpFlag = false){
        //Fetch the Tag Id/Name associated with this Exam
        if($examId > 0){
            $this->CI->load->model('Tagging/taggingmodel');
            $tagData = $this->CI->taggingmodel->findTagAttachedToEntity($examId, 'Exams');
        }

        //Get the top 2 questions required to be displayed
	$displayData = array();
        if(isset($tagData['tag_id']) && $tagData['tag_id']>0){
	    //Store the latest questions and count of Questions in Redis
            //$quesArray = $this->CI->taggingmodel->getLatestAnsweredQuestionsOnTag($tagData['tag_id'], 3);
            $this->predisLib = PredisLibrary::getInstance();
            $data = $this->predisLib->getMembersOfSet("anaTagData:".$tagData['tag_id']);
            if(!empty($data)){
               $quesArray = json_decode($data[0],true);
            }
            else{
               $quesArray = $this->CI->taggingmodel->getLatestAnsweredQuestionsOnTag($tagData['tag_id'], 3);
               $details = array(json_encode($quesArray));
               $seconds = 60*60*24;
               if($this->predisLib->isRedisAlive()){
                     $redisKey = 'anaTagData:'.$tagData['tag_id'];
                     $this->predisLib->addMembersToSet($redisKey, $details, FALSE);
                     $this->predisLib->expireKey($redisKey, $seconds, FALSE);
               }
            }
	    //End storing in Redis

            if($quesArray['topContent'] && count($quesArray['topContent'])>0){
               $finalArray = array_slice($quesArray['topContent'],0,$count);
               $displayData['totalNumber'] = $quesArray['totalNumber'];
            }
        }
        
        //Get questions details from DB
        if(!empty($finalArray)){
            $questionIds = implode(',',$finalArray);

            $this->CI->load->model("messageBoard/anamodel");
            $questionsDetail = $this->CI->anamodel->getQuestionsDetails($questionIds, $userId, $isAmpFlag, false);

            if(is_array($questionsDetail)){
                $displayData['questionsDetail'] = $questionsDetail;
            }

            $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In':'Non-Logged In';
            $displayData['allQuestionURL'] = getSeoUrl($tagData['tag_id'], 'tag', $tagData['tags']);            
        }
        
        return $displayData;
    }

	// Exampage Apply Online CTA
    function getApplyOnline($examId,$groupId,$isMobile = false){

        $result = $this->ExamPageCache->getExamApplyOnline($examId,$groupId);
        if((!empty($result) && $result == 'NA') || (!empty($result)))
        {
            return $result == 'NA' ? '' : $result;
        }
        $courseId = $this->exampagemodel->getCourseIdByGroupId($examId,$groupId);

        if(empty($courseId)){
            $this->ExamPageCache->storeExamApplyOnline($examId,$groupId,"NA");
            return;
        }
        $this->CI->load->library('Online/OnlineFormUtilityLib');
        $this->onlineFormLib = new OnlineFormUtilityLib();
        $result = $this->onlineFormLib->getOAFBasicInfo(array($courseId));

        if($isMobile)
        {
            $this->CI->load->builder("nationalCourse/CourseBuilder");
            $courseBuilder = new CourseBuilder();
            $courseRepo = $courseBuilder->getCourseRepository();   
            $courseObj = $courseRepo->find($courseId);
            if(empty($courseObj))
                return;
            $result[$courseId]['courseId'] = $courseId;
            $result[$courseId]['instituteName'] = base64_encode($courseObj->getInstituteName());
        }
        if(!empty($result))
        {
            $this->ExamPageCache->storeExamApplyOnline($examId,$groupId,$result);    
        }
        return $result[$courseId];
    }

    function createExamDigestMailerData($userId, $groupId){
        $data['examInfo']                      = $this->getExamInfo($groupId);
        $data['instituteAcceptingSectionData'] = $this->createInstituteAcceptingSectionData($data['examInfo']['examId'], 5, $data['examInfo']['examName']);
	$data['iimcallPredictor']              = $this->getIIMCallPredictorData($data['examInfo']['examId']); 
        $data['sampleAndGuidePapers']          = $this->getSamplePaperData($groupId, $data['examInfo']['examName']);
	$data['preptipsData']		       = $this->getPrepTipsData($groupId, $data['examInfo']['examName']);
	$data['article']                       = $this->getArticles($data['examInfo']['examId'], $data['examInfo']['examName']);
	$data['ana']                           = $this->getAnAData($data['examInfo']['examId'], $userId);
        $data['similarExams']                  = $this->getSimilarExams($data['examInfo']['examId'],$data['examInfo']['examName'],$groupId, 10, false, true);
	if(empty($data['instituteAcceptingSectionData']) && empty($data['iimcallPredictor']['showIIMCallPredictor']) && empty($data['samplePapers']) && empty($data['article']) && empty($data['ana']) && empty($data['similarExams'])){
		return array();
	}
        return $data;
    }

    function getExamInfo($groupId){
        $exammodel        = $this->CI->load->model('examPages/exammodel');
        $examInfo         = $exammodel->getExamInfo($groupId);
	if(empty($examInfo)){
		return array();
	}
	if(!empty($examInfo['examYear'])){
	        $examYear  = ' '.$examInfo['examYear'];
	}else{
        	$examYear  = '';
	}
	if(!empty($examInfo['examFullName'])){
        	$examFullName  = ' ( '.$examInfo['examFullName']. ' )';
	}else{
        	$examFullName  = '';
	}
	$examInfo['examNameForMailer'] = $examInfo['examName'].$examFullName.$examYear;
	$examInfo['examUrl'] = $this->getExamPageUrl($examInfo['examName'], 'homepage', $groupId);
        return $examInfo;
    }

    function createInstituteAcceptingSectionData($examId, $limit, $examName){
        $examLibObj                  = $this->CI->load->library('examPages/ExamPageLib');
        $data                        = $examLibObj->fetchCoursesAcceptingExam(array($examId), $limit, $examName,0,'');
	$data['totalInstituteCount'] = count($data['instCourseMapping']);
	if(empty($data['totalInstituteCount'])){
		return array();
	}
        return $data;
    }

    function getIIMCallPredictorData($examId){
	$examIdOfCAT                  = '327';
	$data['showIIMCallPredictor'] = false;
	if($examId == $examIdOfCAT){
		$data['showIIMCallPredictor'] = true;
		$data['url']                  = SHIKSHA_HOME."/mba/resources/iim-call-predictor";
	}
	return $data;
    }

    function getSamplePaperData($groupId, $examName){
        $this->CI->load->config('examPages/examPageConfig');
        $this->CI->load->builder('ExamBuilder','examPages');
        $examBuilder          = new ExamBuilder();
        $examRepository       = $examBuilder->getExamRepository();
        $data['examContent']  = $examRepository->findContent($groupId, 'all');
        $examLibObj           = $this->CI->load->library('examPages/ExamPageLib');
        $examLibObj->prepareExamSamplePaperData($data);
        $countOfSamplePapers  = count($data['examContent']['samplepapers']['file']['samplepapers']);
        $countOfGuidePapers   = count($data['examContent']['samplepapers']['file']['guidepapers']);
        $totalCount           = $countOfSamplePapers+$countOfGuidePapers;	
	if($totalCount==0){
                return array();
        }
        $finalArray                = array();
        $count 			   = 0;
	    $finalArray['viewAllLink'] = $this->getExamPageUrl($examName, 'samplepapers', $groupId);
        $finalArray['guidePaperData'] = $data['guidePaperData'];
        $finalArray['samplePaperData'] = $data['samplePaperData'];
        $finalArray['samplePaperCount'] = $countOfSamplePapers;
        $finalArray['guidePaperCount'] = $countOfGuidePapers;

   /*
        foreach($data['guidePaperData'] as $key=>$guidevalue){
                $finalArray['samplePaperData'][$count]['name'] = $guidevalue['fileName'];
                $finalArray['samplePaperData'][$count]['url']  = $guidevalue['url'];
                $count++;
        }
        foreach($data['samplePaperData'] as $key=>$samplevalue){
                $finalArray['samplePaperData'][$count]['name'] = $samplevalue['fileName'];
                $finalArray['samplePaperData'][$count]['url']  = $samplevalue['url'];      
                $count++;
        }
     */
        $finalArray['totalCount'] = $totalCount;
        $finalArray['heading']    = $data['samplePaperHeading'];
        return $finalArray;
    }

    function getPrepTipsData($groupId, $examName){
        $this->CI->load->config('examPages/examPageConfig');
        $this->CI->load->builder('ExamBuilder','examPages');
        $examBuilder          = new ExamBuilder();
        $examRepository       = $examBuilder->getExamRepository();
        $data['examContent']  = $examRepository->findContent($groupId, 'all');
        $examLibObj           = $this->CI->load->library('examPages/ExamPageLib');
        $examLibObj->prepareExamPrepTipsData($data);
        $countOfPrepTips  = count($data['examContent']['preptips']['file']);
        if($countOfPrepTips==0){
                return array();
        }
        $finalArray                = array();
        $count                     = 0;
        $finalArray['viewAllLink'] = $this->getExamPageUrl($examName, 'preptips', $groupId);
        $finalArray['preptipsData'] = $data['preptipsData'];
        $finalArray['prepTipsCount'] = $countOfPrepTips;
        $finalArray['heading']    = 'Preparation Tips & Guide';
        return $finalArray;
    }

     function getArticles($examId, $examName){
        $articleData               = Modules::run('examPages/ExamPageMain/prepareArticleWidget',$examId, $examName, true);
        $articleData['totalCount'] = count($articleData['examPageArticles']);
	$articleData['viewAllLink'] = $this->getExamPageUrl($examName, 'homepage',$groupId, 'scrollTo=Articles');
	if(empty($articleData['totalCount'])){
                return array();
        }
        return $articleData;
    }

    function getAnAData($examId, $userId){
        $result  = $this->getAnAWidgetData($examId, $userId);
        $count   = 0;
        $anaData = array();
        $anaData['allQuestionURL'] = $result['allQuestionURL'];
        foreach($result['questionsDetail'] as $key=>$value){
                $anaData['questionsDetail'][$count] = $value;
                $count++;
        }
	$anaData['totalCount'] = $result['totalNumber'];
	if(empty($anaData['totalCount'])){
                return array();
        }
        return $anaData;
    }

    function getSimilarExams($examId,$examName,$groupId,$limit,$flag, $showFullList){
        $examLibObj   = $this->CI->load->library('examPages/ExamPageLib');
        $similarExams = $examLibObj->getSimilarExamWidget($examId,$groupId,$limit,$flag, $showFullList,true);
        $similarExams['totalSimilarExamCount']  = count($similarExams['similarExams']);
        $similarExams['viewAllLink'] = $this->getExamPageUrl($examName, 'homepage',$groupId, 'scrollTo=similarExams');
        if(empty($similarExams['totalSimilarExamCount'])){
                return array();
        }
        return $similarExams;
    }

    function getExamPageUrl($examName, $sectionName, $groupId, $queryParameter=''){
	$examRequest               = $this->CI->load->library('examPages/ExamPageRequest');
	$examRequest->setExamName($examName);
	$url = $examRequest->getUrl($sectionName,true,false,$groupId);
	if(!empty($queryParameter)){
		return $this->appendQueryParameterToUrl(array($url), $queryParameter);
	}
	return $url;
    }

    function getExamMainUrlsById($examIds=array()){
        $examUrlMapping=array();
        $examMainUrls = $this->exampagemodel->getExamMainUrlsById($examIds);
        $fetchFromAbroad = array();
        foreach ($examMainUrls as $examId => $examUrlArray) {
            if(empty($examUrlArray['url'])){
                $fetchFromAbroad[$examId] = $examUrlArray['name'];
                unset($examMainUrls[$examId]);
            }
            else{
                $examUrlMapping[$examId] = addingDomainNameToUrl(array('domainName'=>SHIKSHA_HOME,'url'=>$examUrlArray['url']));
            }
        }
        //fetching the remaining exam page urls from abroad lib
        if(!empty($fetchFromAbroad)) {
            $saContentLib = $this->CI->load->library('blogs/saContentLib');
            $abroadExamData = $saContentLib->getSAExamHomePageURLByExamNames(array_values($fetchFromAbroad));
        }
        foreach ($fetchFromAbroad as $examId => $name) {
            if(!empty($abroadExamData[$name])){
                $examUrlMapping[$examId] = addingDomainNameToUrl(array('domainName'=>SHIKSHA_STUDYABROAD_HOME,'url'=>$abroadExamData[$name]['contentURL']));
            }
        }
        return $examUrlMapping;
    }

    function appendQueryParameterToUrl($urls, $queryParameter){
	foreach($urls as &$url) {
		$parsedUrl = parse_url($url);
   		if ($parsedUrl['path'] == null) {
      			$url .= '/';
   		}
		$separator = ($parsedUrl['query'] == NULL) ? '?' : '&';
   		$url .= $separator . $queryParameter;
	}	
	return $url;
    }
    function removeQueryParams($queryParams)
    {
        if(array_key_exists('actionType', $queryParams))
        {
            unset($queryParams['actionType']);
        }

        if(array_key_exists('fromwhere', $queryParams))
        {
            unset($queryParams['fromwhere']);
        }
        if(array_key_exists('pos', $queryParams))
        {
            unset($queryParams['pos']);
        }
        if(array_key_exists('fileNo', $queryParams))
        {
            unset($queryParams['fileNo']);
        }
        if(array_key_exists('instituteName', $queryParams))
        {
            unset($queryParams['instituteName']);
        }
        if(array_key_exists('courseId', $queryParams))
        {
            unset($queryParams['courseId']);
        }
        if(array_key_exists('isInternal', $queryParams))
        {
            unset($queryParams['isInternal']);
        }
        return $queryParams;
    }

    function getAttachementData($groupId){
        $this->exammodel = $this->CI->load->model('examPages/exammodel');
        $guideData = $this->exammodel->getGuideData($groupId);
        return $guideData;
    }
    function isShowIcpBanner($hierarchyMapping)
    {
        if(in_array(ICP_BANNER_COURSE, $hierarchyMapping['course']))
        {
            return true;
        }
        return false;
    }

    function getAnsweredQuestionCount($examId, $userId){
        //Fetch the Tag Id/Name associated with this Exam
        if($examId > 0){
            $this->CI->load->model('Tagging/taggingmodel');
            $tagData = $this->CI->taggingmodel->findTagAttachedToEntity($examId, 'Exams');
        }

        //Get the top 2 questions required to be displayed
        $displayData = array();
        if(isset($tagData['tag_id']) && $tagData['tag_id']>0){
            //Store the latest questions and count of Questions in Redis
            $this->predisLib = PredisLibrary::getInstance();
            $data = $this->predisLib->getMembersOfSet("anaTagData:".$tagData['tag_id']);
            if(!empty($data)){
               $quesArray = json_decode($data[0],true);
            }else{
               $quesArray = $this->CI->taggingmodel->getLatestAnsweredQuestionsOnTag($tagData['tag_id'], 3);
               $details = array(json_encode($quesArray));
               $seconds = 60*60*24;
               if($this->predisLib->isRedisAlive()){
                     $redisKey = 'anaTagData:'.$tagData['tag_id'];
                     $this->predisLib->addMembersToSet($redisKey, $details, FALSE);
                     $this->predisLib->expireKey($redisKey, $seconds, FALSE);
               }
            }
            //End storing in Redis

            if($quesArray['topContent'] && count($quesArray['topContent'])>0){
               $displayData['totalNumber']    = formatNumber($quesArray['totalNumber']);
               $displayData['GA_userLevel']   = $userId > 0 ? 'Logged In':'Non-Logged In';
               $displayData['allQuestionURL'] = getSeoUrl($tagData['tag_id'], 'tag', $tagData['tags']);            
            }
        }
        return $displayData;
    }

    function getPopularExamWidgetForCHP($result){
        $examIds = array();
        $NUMBER_OF_POPULAR_COURSES = 4;
        foreach ($result as $examIdKey => $exampageIdValue) {
          array_push($examIds, $result[$examIdKey]['examId']);
        }
        $examId = implode(",", $examIds);
        $finalResult = $result;
        $exampageIds = array();

        $examPageModel  = $this->CI->load->model('examPages/exampagemodel');
        $exampageIdsmapping = $examPageModel->getPrimaryExamPageIdsForExams($examIds);

        foreach ($exampageIdsmapping as $key => $value) {
            array_push($exampageIds, $value);
            if(array_key_exists($key, $finalResult))
            {
              $finalResult[$key]['exampageId'] = $value;
            }
        }
        $popularExams = array_slice($finalResult, 0, $NUMBER_OF_POPULAR_COURSES);
        return $popularExams;
    }

    function getExamYears(){
        $examYearMapping = $this->ExamPageCache->getExamYearMapping();
        if(empty($examYearMapping)){
                $examPageModel  = $this->CI->load->model('examPages/exampagemodel');
                $examYears = $examPageModel->getExamYearsValue();
                $examYearMapping = array();
                foreach ($examYears as $row){
                        $examYearMapping[strtolower($row['name'])] = $row['year'];
                }
                $this->ExamPageCache->storeExamYearMapping($examYearMapping);
        }
        return $examYearMapping;
    }

    function reArrangeDateFormat($finalResult){
        if(empty($finalResult)){ return;}
        foreach ($finalResult as $oneResult => &$oneValue) {
           if(count($oneValue['dates']) > 0){
                $firstStartDate = $oneValue['dates'][0]['startDate'];
                $secondStartDate = $oneValue['dates'][1]['startDate'];
                $secondEndDate = $oneValue['dates'][1]['endDate'];
                $firstDescription = $oneValue[0]['description'];
                $secondDescription = $oneValue[1]['description'];
                if($firstStartDate == '0000-00-00' && $secondStartDate == '0000-00-00'){
                    continue;
                } 
                if(!empty($secondStartDate) && $secondStartDate != '000-00-00' && $secondStartDate < $firstStartDate){
                    $temp = $oneValue['dates'][0];
                    $oneValue['dates'][0] = $oneValue['dates'][1];
                    $oneValue['dates'][1] = $temp;
                } 
                foreach ($oneValue['dates'] as $dKey => &$dVal) {
                    $strToTime = strtotime($dVal['startDate']);
                    $strToTimeEnd = strtotime($dVal['endDate']);
                    if ($strToTime) {
                        $startDay = date('d', $strToTime);
                        $startMonth = date('M', $strToTime);
                        $startDateYear = date('Y', $strToTime);

                    }
                    if ($strToTimeEnd) {
                        $endDay = date('d', $strToTimeEnd);
                        $endMonth = date('M', $strToTimeEnd);
                        $endDateYear = date('Y', $strToTimeEnd);
                    }
                    if(($dVal['startDate'] == $dVal['endDate']) || ($dVal['endDate'] == '0000-00-00')){
                        $dVal['stringToShow'] = $startDay.' '.$startMonth.' '.$startDateYear;
                    }

                    if(($dVal['startDate'] != '0000-00-00' && $dVal['endDate'] != '0000-00-00') && ($dVal['startDate'] != $dVal['endDate'])){
                        if($startDateYear == $endDateYear){
                            $dVal['stringToShow'] = $startDay.' '.$startMonth.'-'.$endDay.' '.$endMonth.' '.$endDateYear; 
                        }else{
                            $dVal['stringToShow'] = $startDay.' '.$startMonth.' '.$startDateYear.'-'.$endDay.' '.$endMonth.' '.$endDateYear; 
                        }
                    }
                }
            }
        }
        return $finalResult; 
    }

    function checkActionPerformedOnGroupForAllExamPage($groupIdArr,$cookieName)
    {   
        
        if(empty($groupIdArr))
            return false;
        else
        {
            foreach ($groupIdArr as $key => $value) {
                $cookieToCheck = $cookieName.$value;
                if( isset( $_COOKIE[$cookieToCheck]  ) ) 
                { 
                    $groupIds = json_decode(base64_decode($_COOKIE[$cookieToCheck]));
                    if(in_array($value, $groupIds))
                    {
                        $guideDownloaded[$value] = 1;
                    }else{
                        $guideDownloaded[$value] = 0;
                    }
                }else{
                    $guideDownloaded[$value] = 0;
                }
            }
        }
    return $guideDownloaded;
    }


    private function getRecentExamDateAndString($examDateObjectArray){
        $returnArray = array();
        if (empty($examDateObjectArray)){
            $returnArray;
        }else{
            $strtotimeNow = strtotime("now");
            $commonDiff = $strtotimeNow;
            foreach ($examDateObjectArray as $key=>$examObj) {
                $tempStrToTime = strtotime($examObj->getEndDate());
                $tempStrToTime += 86400; // This added to include whole day as event. Because there is no time in date.
                $tempDiff = $tempStrToTime - $strtotimeNow;
                if (in_array($examObj->getEventCategory(), array(1,5,6,8,9))
                    && $tempStrToTime > 0 && $tempDiff >= 0 && $tempDiff < $commonDiff){
                    $commonDiff = $tempDiff;
                    $returnArray = array(   'event_category'    => $examObj->getEventCategory(),
                                            'start_date'        => $examObj->getStartDate()
                                        );
                    if (strtotime($examObj->getEndDate()) > 0){
                        $returnArray['end_date'] = $examObj->getEndDate();
                    }
                }
            }

            if (!empty($returnArray)){
                $this->CI->load->config('examPages/eventCategory');
                $events = $this->CI->config->item("events");
                $dateString = date_format(new DateTime($returnArray['start_date']), "d M'y");
                if (strtotime($returnArray['start_date']) !== strtotime($returnArray['end_date'])){
                    $dateString .= " - ".date_format(new DateTime($returnArray['end_date']), "d M'y");
                }
                $eventCategoryName = $events[$returnArray['event_category']];
                if (strpos($eventCategoryName, '| Pre Exam') !== false || strpos($eventCategoryName, '| Post Exam') !== false){
                    $eventCategoryName = explode("|", $eventCategoryName);
                    $eventCategoryName = trim($eventCategoryName[0]);
                }
                if($eventCategoryName == "Exam Date"){
                    $eventCategoryName = "Exam On";
                }elseif ($eventCategoryName == "Exam Result"){
                    $eventCategoryName = "Results On";
                }
                $returnArray['displayLabel'] = $eventCategoryName;
                $returnArray['displayDate'] = $dateString;
            }
        }
        return $returnArray;
    }



    public function formatExamContentObj($examContentObj){
                    /*HomePage*/
        $this->CI->load->config('examPages/examPageConfig.php');
            $homepagesectionorder = $this->CI->config->item('homepage_section_order');
            foreach ($homepagesectionorder as $key => $value) {
                foreach ($examContentObj->getHomepageContent() as $k => $v) {
                        if($v->getEntityType() == $key){
                            $homePageData[] = $v;            
                        }
                   }   
            }

            if(!empty($homePageData)){
                $formattedExamContentObj['homepage']    = $homePageData;
            }
                    /*Pattern*/
            $patternData = $examContentObj->getPatternContent();
            if(!empty($patternData)){
                $formattedExamContentObj['pattern']     = $patternData;
            }
                    /*Syllabus*/
            $syllabusData = $examContentObj->getSyllabusContent();
            if(!empty($syllabusData)){
                $formattedExamContentObj['syllabus']    = $syllabusData;
            }
                    /*CuttOff*/
            $cutoffData = $examContentObj->getCutOff();
            if(!empty($cutoffData)){
                $formattedExamContentObj['cutoff']      = $cutoffData;
            }
                    /*AdmitCard*/
            $admitcardData = $examContentObj->getAdmitCard();
            if(!empty($admitcardData)){
                $formattedExamContentObj['admitcard']   = $admitcardData;
            }
                    /*AnswerKey*/
            $answerkeyData = $examContentObj->getAnswerKey();
            if(!empty($answerkeyData)){
                $formattedExamContentObj['answerkey']   = $answerkeyData;
            }
                    /*Counselling*/
            $counsellingData = $examContentObj->getCounselling();
            if(!empty($counsellingData)){
                $formattedExamContentObj['counselling'] = $counsellingData;
            }
            /*SlotBooking*/
            $slotbookingData = $examContentObj->getSlotBooking();
            if(!empty($slotbookingData)){
                $formattedExamContentObj['slotbooking'] = $slotbookingData;
            }
                    /*Result*/
            $resultWiki  = $examContentObj->getResults();
            if(!empty($resultWiki)){
                $formattedExamContentObj['results']['wiki']  = $resultWiki;
            }
            $dates = $examContentObj->getExamDates();
            if(!empty($dates['results'])){
                $formattedExamContentObj['results']['date']  = $dates['results'];
            }
            /*Important Dates*/
            $importantDateWiki = $examContentObj->getImportantDates();
            if(!empty($importantDateWiki)){
                $formattedExamContentObj['importantdates']['wiki']         = $importantDateWiki;
            }
            if(!empty($dates['importantdates'])){
                $formattedExamContentObj['importantdates']['date']         = $dates['importantdates'];
            }
                    /*Sample Papers*/
            $samplePaperWiki = $examContentObj->getSamplePapers();
            if(!empty($samplePaperWiki)){
                $formattedExamContentObj['samplepapers']['wiki']           = $samplePaperWiki;
            }
            $samplePaperFiles = $examContentObj->getFiles();
            if(!empty($samplePaperFiles['samplepapers'])){
                $formattedExamContentObj['samplepapers']['file']['samplepapers']   = $samplePaperFiles['samplepapers'];
            }
            if(!empty($samplePaperFiles['guidepapers'])){
                $formattedExamContentObj['samplepapers']['file']['guidepapers']    = $samplePaperFiles['guidepapers'];
            }
                    /*Application Forms*/
            $applicationFormWiki = $examContentObj->getApplicationform();
            if(!empty($applicationFormWiki)){
                $formattedExamContentObj['applicationform']['wiki']   = $applicationFormWiki;
            }
            $applicationFormFiles = $examContentObj->getFiles();
            if(!empty($applicationFormFiles['applicationform'])){
                    $formattedExamContentObj['applicationform']['file'] = $applicationFormFiles['applicationform'];
            }

            $preptipsWiki = $examContentObj->getPreptips();
            if(!empty($preptipsWiki)){
                $formattedExamContentObj['preptips']['wiki']                = $preptipsWiki;
            }
                $preptipFiles = $examContentObj->getFiles();
            if(!empty($preptipFiles['preptips'])){
                $formattedExamContentObj['preptips']['file']   = $preptipFiles['preptips'];
            }

            $vacancyWiki = $examContentObj->getVacancies();
                if(!empty($vacancyWiki)){
                    $formattedExamContentObj['vacancies']         = $vacancyWiki;
                }
            $callletterWiki = $examContentObj->getCallletter();
            if(!empty($callletterWiki)){
                $formattedExamContentObj['callletter']         = $callletterWiki;
            }
                $newsWiki = $examContentObj->getNews();
                if(!empty($newsWiki)){
                    $formattedExamContentObj['news']         = $newsWiki;
                }

            if(!empty($examContentObj)){
                $formattedExamContentObj['sectionname'] = $examContentObj->getSectionOrder();
            }
            return $formattedExamContentObj;
    }

}
?>

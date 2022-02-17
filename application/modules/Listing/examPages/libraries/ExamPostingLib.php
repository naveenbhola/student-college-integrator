<?php
class ExamPostingLib
{
    private $CI;
    private $examCMSModelObj;
    
    function __construct()
    {
        $this->CI =& get_instance();
        $this->_setDependecies();
    }

    function _setDependecies()
    {
        $this->CI->load->model('examPages/examcmsmodel');
        $this->examCMSModelObj  = new examcmsmodel();
        
    }

    function getExamPageData($examPageId) {
        return $this->examCMSModelObj->getExamPageData($examPageId);
    }
    
    function getAllExamPageDataForTable() {
        return $this->examCMSModelObj->getAllExamPageDataForTable();
    }

	function addEditExamData($formData){
        $preparedData = $this->prepareExamData($formData);
        //_p($preparedData);die;
        $result = $this->examCMSModelObj->saveExamContent($preparedData);
        return $result;
    }

    public function saveExamPageContentData($formData){
        $preparedData = $this->prepareExamData($formData);
        $result = $this->examCMSModelObj->updateWikiContentData($preparedData);
        return $result;
    }

    function getExamPageSeo($examId, $sectioName){
        $dbData = $this->examCMSModelObj->getExamPageSeo($examId, $sectioName);

        $seoData = array();
        if(empty($dbData)){
            $ExamPageLib = $this->CI->load->library('examPages/ExamPageLib');
            $dataByConfig = $ExamPageLib->getSeoDetail('#examName#',$sectioName, '#year#', '#examFullName#', false);
            $seoData['metaTitle'] = $dataByConfig['title'];
            $seoData['metaDescription'] = $dataByConfig['description'];
            $seoData['h1Tag'] = $dataByConfig['h1'];
        }
        else{
            $seoData['metaTitle'] = $dbData['metaTitle'];
            $seoData['metaDescription'] = $dbData['metaDescription'];
            $seoData['h1Tag'] = $dbData['h1Tag'];
        }
        return $seoData;
    }

    function prepareExamData($formData)
    {
        $domDocumentLib = $this->CI->load->library('DomDocumentLib');
        
        $finalArray = array();

        $noNeedTocArray = array('Phone Number','Official Website','Exam Title');

        //preparing wikidata for homepage,importantdates and samplepapers data
        $multipleWikiFieldsArray = array('homepage','importantdates','samplepapers');
        foreach ($multipleWikiFieldsArray as $key => $value) {
                $diffWikiData = array();
                foreach ($formData[$value] as $homeKey => $homeValue) {
                    $homeValue['wikiData'] = trim($homeValue['wikiData']);
                    if(!empty($homeValue['label']) && !empty($homeValue['wikiData'])) 
                    {
                        $htmlResult = array();
                        if(!in_array($homeValue['label'], $noNeedTocArray)) {
                            $htmlResult = $domDocumentLib->getTagsInDynamicHtmlContent($homeValue['wikiData'],$homeValue['label'],array('h2','p[contains(@class,"kb-tags")]'),'table');
                        }
                        
                        if(!empty($htmlResult['html'])){
                            $diffWikiData[$homeValue['label']]['wikiData'] = $htmlResult['html'];
                            $diffWikiData[$homeValue['label']]['tocContent'] = $htmlResult['tocContent'];
                        }
                        else{
                            $diffWikiData[$homeValue['label']]['wikiData'] = $homeValue['wikiData'];    
                            $diffWikiData[$homeValue['label']]['tocContent'] = "";
                        }
                        $diffWikiData[$homeValue['label']]['updatedOn'] = ($homeValue['updatedOn']) ? date('Y-m-d h:i:s') : $homeValue['prevUpdatedOn'];
                    }
                }
            $finalArray['wiki'][$value] = $diffWikiData;
        }

        //preparing important dates data
        $importantDates = array();
        foreach ($formData['importantDatesData'] as $impKey => $impValue) {

            $startDate = !empty($impValue['startDate']) ? $impValue['startDate'] : '';
            $endDate = !empty($impValue['endDate']) ? $impValue['endDate'] : '';
            $eventName = !empty($impValue['eventName']) ? $impValue['eventName'] : '';
            $articleId = !empty($impValue['articleId']) ? $impValue['articleId'] : '';

            $eventCategory = !empty($impValue['eventCategory']) ? $impValue['eventCategory'] : '';
            $updatedOn = ($impValue['updatedOn']) ? date('Y-m-d h:i:s') : $impValue['prevUpdatedOn'];
            if(!empty($articleId) || !empty($startDate) || !empty($endDate) || !empty($eventName) ||!empty($eventCategory))
            {
                $finalArray['dates'][] = array('eventCategory'=>$eventCategory,'startDate' => $startDate, 'endDate' => $endDate,'eventName' => $eventName,'articleId' => $articleId,'sectionName' => 'importantdates','order' => $impValue['position'],'updatedOn'=>$updatedOn);
            }
            
        }


        $wikiFieldsArray = array('pattern','syllabus','results','admitcard','slotbooking','answerkey','cutoff','counselling','applicationform','vacancies','news','callletter');

        foreach ($wikiFieldsArray as $wikiKey => $wikiField) {
            //preparing important wiki data
            $tempData = trim($formData[$wikiField]['wikiData']); 
            if(!empty($tempData))
            {
                $htmlResult = $domDocumentLib->getTagsInDynamicHtmlContent($formData[$wikiField]['wikiData'],$wikiField,array('h2','p[contains(@class,"kb-tags")]'),'table');

                if(!empty($htmlResult['html'])){
                    $finalArray['wiki'][$wikiField][$wikiField]['wikiData'] = $htmlResult['html'];
                    $finalArray['wiki'][$wikiField][$wikiField]['tocContent'] = $htmlResult['tocContent'];
                }
                else{
                    $finalArray['wiki'][$wikiField][$wikiField]['wikiData'] = $formData[$wikiField]['wikiData'];
                    $finalArray['wiki'][$wikiField][$wikiField]['tocContent'] = "";
                }
                $finalArray['wiki'][$wikiField][$wikiField]['updatedOn'] = ($formData[$wikiField]['updatedOn']) ? date('Y-m-d h:i:s') : $formData[$wikiField]['prevUpdatedOn'];
            }
        }

        $preptips = array();
        foreach ($formData['preptips'] as $prepKey => $prepeValue) {
            $prepeValue['wikiData'] = trim($prepeValue['wikiData']);
            if(!empty($prepeValue['label']) && !empty($prepeValue['wikiData']))
            {
                $htmlResult = $domDocumentLib->getTagsInDynamicHtmlContent($prepeValue['wikiData'],$prepeValue['label'],array('h2','p[contains(@class,"kb-tags")]'),'table');
                if(!empty($htmlResult['html'])){
                    $preptips[$prepeValue['label']]['wikiData'] = $htmlResult['html'];
                    $preptips[$prepeValue['label']]['tocContent'] = $htmlResult['tocContent'];
                }
                else{
                    $preptips[$prepeValue['label']]['wikiData'] = $prepeValue['wikiData'];
                    $preptips[$prepeValue['label']]['tocContent'] = "";
                }
                $preptips[$prepeValue['label']]['updatedOn'] = ($prepeValue['updatedOn']) ? date('Y-m-d h:i:s') : $prepeValue['prevUpdatedOn'];
            }
        }

        $finalArray['wiki']['preptips'] = $preptips;


        foreach ($formData['preptipsData']['files'] as $key => $value) {
                $finalArray['files'][] = array('file_url' => $value['file_relative_url'], 'thumbnail_url' => !empty($value['thumbnail_url']) ? $value['thumbnail_url'] : '', 'file_name' => str_replace('+',' ', $value['file_name']),'order' => $value['position'],'section_name' => 'preptips','updatedOn'=> ($formData['preptips'][0]['updatedOn'])  ? date('Y-m-d h:i:s') : $formData['preptips'][0]['prevUpdatedOn']);
        }
        //preparing result dates data
        $results = array();
        if(!empty($formData['resultDatesData']) && count($formData['resultDatesData']) > 0)
        {
            $eventName = !empty($formData['resultDatesData']['eventName']) ? $formData['resultDatesData']['eventName'] : '';
            $startDate = !empty($formData['resultDatesData']['startDate']) ? $formData['resultDatesData']['startDate'] : '';
            $endDate = !empty($formData['resultDatesData']['endDate']) ? $formData['resultDatesData']['endDate'] : '';
            $articleId = !empty($formData['resultDatesData']['articleId']) ? $formData['resultDatesData']['articleId'] : '';
            $updatedOn = ($formData['resultDatesData']['updatedOn']) ? date('Y-m-d h:i:s') : $formData['resultDatesData']['prevUpdatedOn'];

            if(!empty($articleId) || !empty($startDate) || !empty($endDate) || !empty($eventName))
            {
                $finalArray['dates'][] = array(
                            'startDate' => $startDate,
                            'endDate'  => $endDate,
                            'eventName' => $eventName,
                            'articleId' => $articleId,
                            'sectionName' => 'results',
                            'order' => 0,
                            'updatedOn'=> $updatedOn

                );
            }
        }

        $tempComments = trim($formData['userComments']);
        if(!empty($tempComments))
        {
            $finalArray['userComments'] = trim($formData['userComments']);
        }

        $tempUrl = trim($formData['applicationformData']['url']);
        $updatedOn = ($formData['applicationformData']['updatedOn']) ? date('Y-m-d h:i:s') : $formData['applicationformData']['prevUpdatedOn'];
        
        if(!empty($tempUrl))
        {
            $finalArray['wiki']['applicationform']['applicationformurl'] = array('wikiData'=>$formData['applicationformData']['url'],'updatedOn'=>$updatedOn);
        }

        if(!empty($formData['applicationformData']['uploads']) && count($formData['applicationformData']['uploads']) > 0)
        {
            
            $appFiles = $formData['applicationformData']['uploads'];
            if(!empty($appFiles['file_relative_url']) && !empty($appFiles['file_name']))
            {
                $finalArray['files'][] = array('file_url' => $appFiles['file_relative_url'],'file_name' => str_replace('+',' ', $appFiles['file_name']),'section_name' => 'applicationform','order' => 1,'updatedOn'=>$updatedOn);    
            }
        }

        $samplepapersUpdatedOn = ($formData['samplepapersData']['updatedOn']) ? date('Y-m-d h:i:s') : $formData['samplepapersData']['prevUpdatedOn'];

        foreach ($formData['samplepapersData']['papers'] as $key => $value) {
                $finalArray['files'][] = array('file_url' => $value['file_relative_url'], 'thumbnail_url' => !empty($value['thumbnail_url']) ? $value['thumbnail_url'] : '','file_name' => str_replace('+',' ', $value['file_name']),'order' => $value['position'],'section_name' => 'samplepapers','updatedOn'=>$samplepapersUpdatedOn);
        }

        foreach ($formData['samplepapersData']['guide'] as $key => $value) {
                $finalArray['files'][] = array('file_url' => $value['file_relative_url'] , 'thumbnail_url' => !empty($value['thumbnail_url']) ? $value['thumbnail_url'] : '','file_name' => str_replace('+',' ', $value['file_name']),'order' => $value['position'],'section_name' => 'guidepapers','updatedOn'=>$samplepapersUpdatedOn);
        }

        $finalArray['sectionOrder'] = $formData['sectionOrder'];
        $finalArray['saveToMultiple'] = $formData['saveToMultiple'];

        $finalArray['examId'] = $formData['examId'];
        $finalArray['examName'] = $formData['examName'];
        $finalArray['groupId'] = $formData['groupId'];
        $finalArray['groupName'] = $formData['groupName'];
        $finalArray['status']   = $formData['status'];
        $finalArray['action']   = $formData['action'];
        $finalArray["created"]     = $formData["created"];
        $finalArray["updated"]   = $formData["updated"];
        $finalArray["createdBy"] = $formData["createdBy"];
        $finalArray["updatedBy"]= $formData["updatedBy"];
        $finalArray['examPageId'] = $formData['examPageId'];
        $finalArray['creationDate'] = $formData['creationDate'];
        $finalArray['examOrder'] = $formData['examOrder'] != -1 ? $formData['examOrder'] : 0;
        $finalArray['isFeatured'] = !empty($formData['isFeatured']) ? $formData['isFeatured'] : 0;
        $finalArray['view_count'] = !empty($formData['view_count']) ? $formData['view_count'] : 0;
        $finalArray['no_Of_Past_Views'] = !empty($formData['no_of_past_views']) ? $formData['no_of_past_views'] : 0;
        return $finalArray;
       
    }

    function getMetaDetailsFromDB($examPageId){
        $result = $this->examCMSModelObj->getMetaDetails($examPageId);
        foreach ($result as $key => $value) {
            $res[$value['section_name']] = $value;
        }
        return $res;
    }

    function getMetaDetailsDefault($examPageId){
        $this->CI->load->builder('ExamBuilder','examPages');
        $examPageBuilder          = new ExamBuilder($params);
        $this->builderRequest = $examPageBuilder->getRequest();
        $examDetails= $this->getExamNameAndFullFormById($examPageId);
        $this->builderRequest->setExamName($examDetails[0]['exam_name']);
        $this->builderRequest->setExamFullForm($examDetails[0]['exam_full_form']);
        $allSections = array('home','syllabus','imp_dates','results','preptips','article','discussion');
        foreach ($allSections as $key => $value) {
            $res[$value]['title'] = $this->builderRequest->getMetaTitle($value);   
            $res[$value]['description'] = $this->builderRequest->getMetaDescription($value);
        }
        return $res;
    }
    
    function getAlreadyAddedExams() {
        $examList = $this->examCMSModelObj->getAlreadyAddedExams();
        foreach($examList as $exam) {
            $list[$exam['category_name']][] = $exam['exam_name'];
        }
        return $list;
    }

    function getExamNameByExamId($examIds){
        return $this->examCMSModelObj->getExamNameByExamId($examIds);
    }

    function getExamOrderForInputHierarchy($examIds,$courseId,$streamId,$subStreamId){
        return $this->examCMSModelObj->getExamOrderForInputHierarchy($examIds,$courseId,$streamId,$subStreamId);
    }
    
    function getExamsByCategoryName($categoryName) {
        return $this->examCMSModelObj->getExamsByCategoryName($categoryName);
    }

    function getExamIdsByHierarchy($hierarchyIds,$entityType=''){
        return $this->examCMSModelObj->getExamIdsByHierarchy($hierarchyIds,$entityType);
    }
    
    function updateExamSortOrder($examData) {
        return $this->examCMSModelObj->updateExamSortOrder($examData);
    }
    
    function getLiveCategories() {
        return $this->examCMSModelObj->getLiveCategories();
    }

    function getExamNameById($id,$status = 'live'){
        return $this->examCMSModelObj->getExamNameById($id,$status);
    }

    function getExamNameAndFullFormById($id,$status = 'live'){
        return $this->examCMSModelObj->getExamNameAndFullFormById($id,$status);
    }

    public function getRedirectExams(){
        return $this->examCMSModelObj->getRedirectExams();
    }

    function getOldExamUrl($examId){
        return $this->examCMSModelObj->getOldExamUrl($examId);
    }

    function migrateOrDeleteExamMapping($listingIds,$newListingId){
        if(empty($listingIds) || (!is_array($listingIds))|| (!(count($listingIds) > 0))){
            return 'Old Listing is Not Valid,not able to Migrate / Delete Exam Mapping.';
        }

        $result = $this->examCMSModelObj->checkIfExamMappingExist($listingIds);
        if($result == true){
            if(empty($newListingId)){
                $responses = $this->examCMSModelObj->deleteExamListingMapping($listingIds);
            }else{
                if(!($newListingId > 0)){
                    return 'New listing is invalid,not able to Migrate / Delete Exam Mapping.';
                }else{
                    $responses = $this->examCMSModelObj->migrateExamListingMapping($listingIds,$newListingId);
                }
            }
            if($responses){
                return 'Exams Mapping is migrated / deleted.';
            }else{
                return 'Exams Mapping is not migrated / deleted.';
            }
        }else{
            return 'Exam migration not applicable.';
        }
    }

    function addExamToIndexLog($type,$data){
        $indexData = array();
        if($type == 'allexam'){
            $currentMapping = $data['currentMapping'];
            $newMapping = $data['newMapping'];
            $temp = array('stream','substream','base_course');
            foreach($temp as $key){
                if(!empty($currentMapping[$key])){
                    foreach ($currentMapping[$key] as $value) {
                        $indexData[] = array('operation'=>'index','listing_type'=>'allexam','listing_id'=>$key.'::'.$value);
                    }
                }
                if(!empty($newMapping[$key])){
                    foreach ($newMapping[$key] as $value) {
                        $indexData[] = array('operation'=>'index','listing_type'=>'allexam','listing_id'=>$key.'::'.$value);
                    }
                }
            }
        }
        else if($type == 'exam'){
            $indexData[] = array('operation'=>'index','listing_type'=>'exam','listing_id'=>$data['examId']);
        }
        if(!empty($indexData)){
            $this->examCMSModelObj->addExamToIndexLog($indexData);
        }
    }


    function getExamContentDataBasedOnPageId($examId,$groupId)
    {
        //to fetch wiki conetnt information and some text fields information
        $examPageData = $this->examCMSModelObj->getExamContentDataBasedOnPageId($examId,$groupId);
        if(empty($examPageData))
            return $examPageData;

        //collect information by section wise
        $finalData = array();
        foreach ($examPageData['wiki'] as $wikiKey => $wikiValue) {
            $finalData[$wikiValue['section_name']][$wikiValue['entity_type']]['wikiData'] = $wikiValue['entity_value'];
            $finalData[$wikiValue['section_name']][$wikiValue['entity_type']]['updatedOn']= $wikiValue['updatedOn'];
        }

        //dates data
        foreach ($examPageData['dates'] as $dateKey => $dateValue) {
            $finalData[$dateValue['section_name']]['dates'][] = array(
                    'start_date' => $dateValue['start_date'],
                    'end_date' => $dateValue['end_date'],
                    'event_name' => $dateValue['event_name'],
                    'article_id' => $dateValue['article_id'],
                    'position' => $dateValue['date_order'],
                    'eventCategory' =>$dateValue['eventCategory'],
                    'updatedOn' =>$dateValue['updatedOn']
                );
        }

        //files data
        foreach ($examPageData['files'] as $fileKey => $fileValue) {
            if($fileValue['file_type'] == 'applicationform')
            {
                $finalData[$fileValue['file_type']]['files'] = array('file_name' => addslashes($fileValue['file_name']),'file_url' => addingDomainNameToUrl(array('url' => $fileValue['file_url'],'domainName' => MEDIA_SERVER)) ,'thumbnail_url' => $fileValue['thumbnail_url'],'file_relative_url' => $fileValue['file_url'], 'position' => $fileValue['file_order'],'updatedOn'=>$fileValue['updatedOn']);    
            }
            else if($fileValue['file_type'] == 'preptips')
            {
                $finalData[$fileValue['file_type']]['files'][] = array('file_name' => addslashes($fileValue['file_name']),'file_url' => addingDomainNameToUrl(array('url' => $fileValue['file_url'] ,'thumbnail_url' => $fileValue['thumbnail_url'],'domainName' => MEDIA_SERVER)),'file_relative_url' => $fileValue['file_url'], 'position' => $fileValue['file_order'],'updatedOn'=>$fileValue['updatedOn']);    
            }
            else
            {
                $finalData['samplepapers']['files'][$fileValue['file_type']][] = array( 'file_name' => addslashes($fileValue['file_name']), 'file_url' => addingDomainNameToUrl(array('url' => $fileValue['file_url'],'domainName' => MEDIA_SERVER)) ,'thumbnail_url' => $fileValue['thumbnail_url'], 'file_relative_url' =>  $fileValue['file_url'],'position' => $fileValue['file_order'],'updatedOn'=>$fileValue['updatedOn']);
            }
        }

        //section order data 
        foreach ($examPageData['section_order'] as $secKey => $secValue) {
            $finalData['sectionOrder'][$secValue['section_name']] = $secValue['section_order'];
        }
        $finalData['examPageId'] = $examPageData['examPageId'];
        $finalData['creationDate'] = $examPageData['creationDate'];
        $finalData['createdBy'] = $examPageData['created_by'];
        $finalData['examOrder'] = $examPageData['exam_order'];
        $finalData['isFeatured'] = $examPageData['is_featured'];
        $finalData['status'] = $examPageData['status'];
        $finalData['view_count'] = $examPageData['view_count'];
        $finalData['no_Of_Past_Views'] = $examPageData['no_Of_Past_Views'];
        return $finalData;
    }
    function getGroupsUnderExam($examId)
    {
        return $this->examCMSModelObj->getGroupsUnderExam($examId);
    }
    function createAmpContentBasedOnPageId($examPageId)
    {
        if(empty($examPageId))
            return;
        $wikiData = $this->examCMSModelObj->getExamWikiContentBasedOnPageId($examPageId);

        if(!empty($wikiData))
        {
            $ampLibrary = AMPLibrary::getInstance();
            $ampHtmlCss = array();
            foreach ($wikiData as $wikiKey => $wikiValue) {
                if(!empty($wikiValue['entity_value']))
                {
                    $noNeedAmpArray = array('Phone Number','Official Website','applicationformurl','Exam Title');
                    if(!in_array($wikiValue['entity_type'], $noNeedAmpArray))
                    {
                        $tempHtml = html_entity_decode($wikiValue['entity_value'], ENT_HTML5 | ENT_QUOTES);
                        $csskeyName = str_replace(' ','_', $wikiValue['entity_type']);
                        $convertedHtml = $ampLibrary->convertHtmlToAmpHtml($tempHtml,$csskeyName);
                        if(!empty($convertedHtml) && !empty($convertedHtml['html']))
                        {
                            $ampHtmlCss[$wikiValue['section_name']][$wikiValue['entity_type']]['html'] = $convertedHtml['html'];
                            $tempCss = '';
                            foreach ($convertedHtml['css'] as $csskey => $cssvalue) {
                                $tempCss .= ' .'.$csskey.'{'.$cssvalue.'}'; 
                            }
                            $ampHtmlCss[$wikiValue['section_name']][$wikiValue['entity_type']]['css'] = $tempCss;
                        }
                    }
                    else
                    {
                        $ampHtmlCss[$wikiValue['section_name']][$wikiValue['entity_type']]['html'] = $wikiValue['entity_value'];
                    }
                }
            }
            if(!empty($ampHtmlCss) && count($ampHtmlCss) > 0)
            {
                $ampHtmlCss['examPageId'] = $examPageId;
                $this->examCMSModelObj->saveAmpExamPageData($ampHtmlCss);
            }    
        }
        
    }
}
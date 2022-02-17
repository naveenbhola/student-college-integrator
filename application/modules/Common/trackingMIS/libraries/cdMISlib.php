<?php
class cdMISLib 
{
	public function __construct()
	{
		$this->CI = & get_instance();
	}
	function getRegistrationsData($dateRangeArray = array(),$extraData=array())
	{
		$isType = key($extraData);
		if($isType == "National")
		{
				reset($extraData);
				$nationalFilterArray = current($extraData);
				return $this->getNationalRegistrationData($dateRangeArray,$nationalFilterArray);
		}
		if($isType == "studyAbroad")
		{
				reset($extraData);
				$saFilterArray = current($extraData);
				$result = $this->getStudyAbroadRegistrationData($dateRangeArray,$saFilterArray);
				return $result;
			
		}

	}
	function getCDRegistrations($instituteId=array(),$courseId=array(),$source='',$paidType = '',$startDate='',$endDate='',$viewWise=1,$isStudyAbroad='no',$subCategoryId=array(),$cityId = array())
	{
        $result = array();
        if( ! empty($courseId))
        {
      		$cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getCustomerDeliveryRegistrations($instituteId,$courseId,$source,$paidType,$startDate,$endDate,$viewWise,$isStudyAbroad);
        }
        else if( ! empty($subCategoryId) || ! empty($countryId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad,1);
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            if( $isStudyAbroad == 'no' && ! empty($subCategoryId))
                $result = $cdmismodel->getRegistrationCountForTopTileBySubcat($subCategoryId,$cityId,$keyIds,$paidType,$startDate,$endDate,$viewWise,'regGraph');
            elseif($isStudyAbroad == 'yes')
                $result = $cdmismodel->getRegistrationDataForStudyAbroadBySubcat($subCategoryId,$countryId,$keyIds,$paidType,$startDate,$endDate,$viewWise,'regGraph');
        }
        return $result;
	}
	function getNationalRegistrationData($dateRangeArray=array(),$nationalFilterArray=array())
	{

  		$cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getNationalRegistrationData($dateRangeArray,$nationalFilterArray);
        if(array_key_exists('sourceSplit', $result))
        {
            $result['splitInformation']['traffic Source'] = $this->getNationalRegistrationDataSourceWise($result['sourceSplit']);
            $result['splitInformation']['UTMSource'] = $this->getNationalRegistrationDataByUTMwise($result['sourceSplit']);
            unset($result['sourceSplit']);
        }
        return $result;
	}
	function getStudyAbroadRegistrationData($dateRangeArray=array(),$saFilterArray=array())
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getStudyAbroadRegistrationData($dateRangeArray,$saFilterArray);
        $overviewTopTiles =0;
        if($saFilterArray['pageName'] =='')
            $overviewTopTiles = 1;
        return $this->prepareStudyAbroadData($result,$overviewTopTiles);
    }
    function prepareStudyAbroadData($data,$overviewTopTiles=0)
    {
        /*[widgetName] => topsignupwidget
            [device] => Mobile
            [responseDate] => 2015-11-18 11:22:29
            [responsescount] => 1*/
        $totalRegistrations = 0;
        $signup = 0;
        $hamburgerReg = 0;
        $mmpReg =0; 
        $responseRegistrationCount = 0;
        $downloadGuideRegistrationCount = 0;
        $topSignup = array('topsignupwidget','findBestCollegesForYourself');
        $responseRegistration = array('response','Course shortlist');
        foreach ($data as $key => $value) 
        {
                if(in_array($value['widget'], $topSignup))
                {
                    if($overviewTopTiles == 0)
                    {
                        if($value['siteSource'] == 'Desktop')
                        {
                    
                            $signup  += $value['reponsesCount'];
                        }
                        if($value['siteSource'] == 'Mobile')
                        {
                            $hamburgerReg += $value['reponsesCount'];
                        }
                    }
                    else
                    {
                        if($value['siteSource'] == 'Desktop')
                        {                    
                            $signup  += $value['reponsesCount'];
                        }
                        if($value['siteSource'] == 'Mobile')
                        {
                            $hamburgerReg += $value['reponsesCount'];
                        }
                    }
                }
                else if($value['widget'] == 'marketingPageForm')
                    {
                        $mmpReg += $value['reponsesCount'];
                    }
                if($overviewTopTiles == 1)
                {
                    if(in_array($value['conversionType'], $responseRegistration))
                    {
                        $responseRegistrationCount += $value['reponsesCount'];
                    }
                    else if($value['conversionType'] == 'downloadGuide')
                    {
                        $downloadGuideRegistrationCount += $value['reponsesCount'];
                    }
                }
                $totalRegistrations += $value['reponsesCount'];
        }
        $studyAbroadRegData = array();
        if($overviewTopTiles == 0)
            $studyAbroadRegData['topTilesData'] = array($totalRegistrations,$signup,$hamburgerReg,$mmpReg);
        else
            $studyAbroadRegData['topTilesData'] = array($totalRegistrations,$mmpReg,$responseRegistrationCount,$downloadGuideRegistrationCount,$signup,$hamburgerReg);
        $studyAbroadRegData['dataForcharts'] = $data;
        return $studyAbroadRegData;
    }
    
    function getQuestionsData($instituteId=array(),$courseId=array(),$source='',$paidType='',$startDate='',$endDate='',$viewWise=1,$subCategoryId=array(),$cityId=array(),$isStudyAbroad='no')
    {
        $questionResult = array();
        if( ! empty($courseId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $questionResult = $cdmismodel->getQuestionsCount($instituteId,$courseId,$source,$startDate,$endDate,$viewWise);
        }
        elseif (! empty($subCategoryId)) {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad,0,'no','questionPost');
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $questionResult = $cdmismodel->getQuestionsCountForTopTileBySubcat($subCategoryId,$cityId,$keyIds,$startDate,$endDate,$viewWise,'questionGraph');
        }
        /*
        */
        return $questionResult;
    }
    function getAnswersData($instituteId=array(),$courseId=array(),$source='',$paidType='',$startDate='',$endDate='',$viewWise=1,$subCategoryId = array(),$cityId =array(),$isStudyAbroad='no')
    {
        $answerResult = array();
        if( ! empty($courseId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $answerResult   = $cdmismodel->getAnswersCount($instituteId,$courseId,$source,$startDate,$endDate,$viewWise);
        }
        elseif ( ! empty($subCategoryId)) {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad,0,'no','answerPost');
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $answerResult = $cdmismodel->getAnswersCountForTopTileBySubcat($subCategoryId,$cityId,$keyIds,$startDate,$endDate,$viewWise,'answerGraph');
        }
        return $answerResult;
    }
    function getDigUpData($instituteId=array(),$courseId=array(),$source='',$paidType='',$startDate='',$endDate='',$viewWise=1,$subCategoryId=array(),$cityId=array(),$isStudyAbroad='no')
    {
        $digupResult = array();
        if( ! empty($courseId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $digupResult = $cdmismodel->getDigupCount($instituteId,$courseId,$source,$startDate,$endDate,$viewWise);
        }
        elseif ( ! empty($subCategoryId)) {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad,0,'no','thumbUpAnswer');
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $digupResult = $cdmismodel->getDigupCountForTopTileBySubcat($subCategoryId,$cityId,$keyIds,$startDate,$endDate,$viewWise,'digGraph');
        }
        return $digupResult;
    }
    function getResponsesData($instituteId=array(),$courseId=array(),$source='',$paidType='',$startDate='',$endDate='',$viewWise=1,$subCategoryId = array(),$cityId = array(),$countryId=array(),$isStudyAbroad='no')
    {
        if( ! empty($courseId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $responsesResult = $cdmismodel->getResponses($instituteId,$courseId,$source,$paidType,$startDate,$endDate,$viewWise);
        }
        elseif ( ! empty($subCategoryId) || ! empty($countryId)) 
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad);
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            if( $isStudyAbroad=='no')
                $responsesResult = $cdmismodel->getResponseCountForTopTileBySubcat($subCategoryId,$cityId,$keyIds,$paidType,$startDate,$endDate,$viewWise,'responseGraph');
            elseif($isStudyAbroad == 'yes')
                $responsesResult  = $cdmismodel->getResponseDataForStudyAbroadBySubcat($subCategoryId,$countryId,$keyIds,$paidType,$startDate,$endDate,$viewWise,'responseGraph');
        }
        return $responsesResult;
    }
    function getRespondentsForResponsesData($courseId = array(),$source ='',$paidType = '',$startDate ='',$endDate ='',$subCategoryId = array(),$cityId = array(),$countryId=array(),$isStudyAbroad ='no')
    {
        $sourceUserMapping = array();
        if( ! empty($courseId) || ! empty($subCategoryId) || !empty($countryId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            if( ! empty($courseId))
                $respondentsResult = $cdmismodel->getResponseDataSourceWise($courseId,$source,$paidType,$startDate,$endDate,'respondents');
            else if( ! empty($subCategoryId) && $isStudyAbroad == 'no')
                $respondentsResult = $cdmismodel->getResponseDataSourceWiseBySubcat($subCategoryId,$cityId,$source,$paidType,$startDate,$endDate,'respondents');
            else if($isStudyAbroad == 'yes' && (!empty($subCategoryId) || !empty($countryId)))
                $respondentsResult = $cdmismodel->getResponsePieChartDataForAbroadBySubcat($subCategoryId,$countryId,$source,$paidType,$startDate,$endDate,'respondents');
            $visitorSessionIdArray = array();
            $i = 0;
            foreach ($respondentsResult as $key => $value) {
                   $visitorSessionIdArray[$i++] = $value['visitorsessionid'];
            }
            if( ! empty($visitorSessionIdArray))
                $sourceResult = $cdmismodel->getSourceForSessionId($visitorSessionIdArray);
            foreach ($sourceResult as $key => $value) {
                if( empty($value['source']))
                    $value['source'] = 'undefined';
                $sourceSessionMapping[$value['sessionId']] = $value['source'];
            }
            $sourceUserMapping = array();
            foreach ($respondentsResult as $key => $value) {
                if( array_key_exists($value['userId'], $sourceUserMapping))
                    array_push($sourceUserMapping[$value['userId']], $sourceSessionMapping[$value['visitorsessionid']]);
                else
                    $sourceUserMapping[$value['userId']] = array($sourceSessionMapping[$value['visitorsessionid']]);
            }
        }
        return $sourceUserMapping;
    }

    function getRespondentsData($sourceUserMapping=array())
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $userId = array();
        $i = 0;
        foreach ($sourceUserMapping as $key => $value) {
            $userId[$i++] = $key;
        }
        if( ! empty($userId))
            $result = $cdmismodel->getRespondentsData($userId);
        return $this->prepareDataTableForRespondents($result,$sourceUserMapping);
    }
    function getCourseIdsBasedOnSubcatId($subcategoryId='')
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $ldbCourseId = $cdmismodel->getNationalLDBCourseId($subcategoryId);
        $courseIdArray = array();
        if( !empty($ldbCourseId))
        {
            $courseId = $cdmismodel->getCourseIdsBasedOnLDBCourseIds($ldbCourseId);
        
            $i=0;
            foreach ($courseId as $key => $value) {
                $courseIdArray[$i++] = $value['clientCourseId'];
            }
        }

        return $courseIdArray;
    }
    function getCoursesBasedOnSubCategory_Location($subcategoryId=array(),$cityId=array())
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $courseId = $cdmismodel->getCoursesBasedOnSubCategory($subcategoryId);

        $courseIdArray = array();
        $i=0;
        foreach ($courseId as $key => $value) {
            $courseIdArray[$i++] = $value['listing_type_id'];
        }
        if( ! empty($cityId) && !empty($courseIdArray))
        {
            $courseId = $cdmismodel->getCoursesBasedOnLocation($courseIdArray,$cityId);
            $courseIdArray = array();
            $i=0;
            foreach ($courseId as $key => $value) {
            $courseIdArray[$i++] = $value['courseId'];
            }
        }
        return $courseIdArray;
    }
    function getUniversityBasedOnCountry($subcategoryId = array(),$countryId = array())
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $result  = $cdmismodel->getUniversityBasedOnCountry($subcategoryId,$countryId);
        $universityIdArray = array();
        $i = 0;
        foreach ($result as $key => $value) {
            $universityIdArray[$i] = $value['universityId'];
            $i++;
        }
        return $universityIdArray;
    }
    function getCoursesInStudyAbroad($subcategoryId = array(),$countryId = array())
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $courseResult = $cdmismodel->getCoursesBasedOnCountry($subcategoryId,$countryId);
        $courseIdArray = array();
        $i = 0;
        foreach ($courseResult as $key => $value) {
            $courseIdArray[$i++] = $value['courseId'];
        }
        return $courseIdArray;
    }
    function getInstitutesInIndia($subcategoryId = array(),$cityId = array(),$stateId = array())
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $instituteResult = $cdmismodel->getInstitutesBasedOnCity($subcategoryId,$cityId,$stateId);
        $instituteIdArray = array();
        $i = 0;
        foreach ($instituteResult as $key => $value) {
            $instituteIdArray[$i++] = $value['instituteId'];
        }
        
        return $instituteIdArray;
    }
    function getCoursesInIndia($subCategoryId = array(),$cityId = array(),$stateId = array())
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $courseResult = $cdmismodel->getCoursesBasedOnCity($subCategoryId,$cityId,$stateId);
        $courseIdArray = array();
        $i = 0;
        foreach ($courseResult as $key => $value) {
            $courseIdArray[$i++] = $value['courseId'];
        }
        return $courseIdArray;
    }
    function getCoursesBasedOnSubcategory_Country($subcategoryId = array(),$countryId = array())
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $courseId = $cdmismodel->getCoursesBasedOnSubCategory($subcategoryId);
         $courseIdArray = array();
        $i=0;
        foreach ($courseId as $key => $value) {
            $courseIdArray[$i++] = $value['listing_type_id'];
        }
        if( ! empty($countryId) && ! empty($courseIdArray))
        {
            $courseId = $cdmismodel->getCoursesBasedOnCountry($courseIdArray,$countryId);
            $courseIdArray = array();
            $i = 0;
            foreach ($courseId as $key => $value) {
                $courseIdArray[$i++] = $value['courseId'];
            }
        }
    }
    function getAuthorNames()
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $userIds = $cdmismodel->getAuthorUserIds();
        $userIdArray =array();
        $i = 0;
        foreach ($userIds as $key => $value) {
            $userIdArray[$i++] = $value['userid'];
        }
        $authorNames = $cdmismodel->getAuthorNames($userIdArray);
        return $authorNames;
    }
    function getSAAuthorNames()
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $userIds = $cdmismodel->getAuthorUserIds_SA();
        $userIdArray = array();
        $i =0;
        foreach ($userIds as $key => $value) {
            $userIdArray[$i++] = $value['created_by'];
        }
        $authorNames = $cdmismodel->getAuthorNames_SA($userIdArray);
        return $authorNames;
    }
    function getInstituteIdBasedOnCourseId($courseIdArray= array())
    {
        $instituteIdArray = array();
        if( ! empty($courseIdArray))
        {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $instituteId = $cdmismodel->getInstituteIdBasedOnCourseId($courseIdArray);
        
            $i=0;
            foreach ($instituteId as $key => $value) {
                $instituteIdArray[$i++] = $value['institute_id'];
            }
        }
        return $instituteIdArray;
    }
    function getRegistrationsDataBasedOnArticle($articleId=array(),$source='',$paidType='',$startDate='',$endDate='',$viewWise=1)
    {
        $result = array();
        if( ! empty($articleId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getRegistrationsDataBasedOnArticle($articleId,$source,$startDate,$endDate,$viewWise);
        }
        return $result;
    }
    function getDiscussionDataBasedOnSubCatId($subCategoryId=array(),$authorId=array(),$source='',$startDate='',$endDate='',$viewWise=1)
    {

        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getDiscussionDataBasedOnSubcatId($subCategoryId,$authorId,$source,$startDate,$endDate,$viewWise);
        return $result;    
    }
    function getCommentDataBasedOnsubCatId($subcatId = array(),$discussionId=array(),$source='',$startDate='',$endDate='',$viewWise=1)
    {
         $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
         $result = array();
        if( ! empty($discussionId))
            $result = $cdmismodel->getCommentDataOnDiscussionBasedOnIds($discussionId,$source,$startDate,$endDate,$viewWise);
        else if( ! empty($subcatId))
            $result = $cdmismodel->getCommentDataBasedOnsubCatId($subcatId,$source,$startDate,$endDate,$viewWise);
    
        return $result;       
    }
    function getArticleDataBasedOnSubCatId($subCategoryId=array(),$authorId=array(),$source='',$startDate='',$endDate='',$viewWise=1)
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $result = array();
        if($source != 'Mobile')
            $result = $cdmismodel->getArticleData($subCategoryId,$authorId,$startDate,$endDate,$viewWise);
        return $result;    
    }
    function getArticleDataBasedOnSubCatId_SA($subCategoryId=array(),$authorId=array(),$source='',$startDate='',$endDate='',$viewWise=1)
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $result = array();
        if($source != 'Mobile')
            $result = $cdmismodel->getArticleData_SA($subCategoryId,$authorId,$startDate,$endDate,$viewWise);
        return $result;    
    }
    function getCommentDataOnArticle($subcategoryId = array(),$articleId=array(),$authorId = array(),$source='',$paidType='',$startDate='',$endDate='',$viewWise=1)
    {
        $result = array();
        if( ! empty($articleId) || ! empty($subcategoryId) || ! empty($authorId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getCommentDataOnArticle($subcategoryId,$articleId,$authorId,$source,$startDate,$endDate,$viewWise);
        }
        return $result;
    }
    function getCommentDataOnArticle_SA($subcategoryId = array(),$articleId=array(),$authorId = array(),$source='',$paidType='',$startDate='',$endDate='',$viewWise=1)
    {
        $result = array();
        if( ! empty($articleId) || ! empty($subcategoryId) || ! empty($authorId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getCommentDataOnArticle_SA($subcategoryId,$articleId,$authorId,$source,$startDate,$endDate,$viewWise);
        }
        return $result;
    }
    function getReplyDataOnArticle($subcategoryId = array(),$articleId=array(),$authorId = array(),$source='',$paidType='',$startDate='',$endDate='',$viewWise=1)
    {
        $result = array();
        if( ! empty($articleId) || ! empty($subcategoryId) || ! empty($authorId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getReplyDataOnArticle($subcategoryId,$articleId,$authorId,$source,$startDate,$endDate,$viewWise);
        }
        return $result;   
    }
    function getReplyDataOnArticle_SA($subcategoryId = array(),$articleId=array(),$authorId = array(),$source='',$paidType='',$startDate='',$endDate='',$viewWise=1)
    {
        $result = array();
        if( ! empty($articleId) || ! empty($subcategoryId) || ! empty($authorId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getReplyDataOnArticle_SA($subcategoryId,$articleId,$authorId,$source,$startDate,$endDate,$viewWise);
        }
        return $result;   
    }

    function getArticleIds($subcategoryId=array(),$authorId=array(),$startDate='',$endDate='')
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $articleId = $cdmismodel->getArticleIds($subcategoryId,$authorId,$startDate,$endDate);
        $articleIdArray= array();
        $i=0;
        foreach ($articleId as $key => $value) {
            $articleIdArray[$i++] = $value['blogId'];
        }
        return $articleIdArray;
    }
    function getArticleIds_SA($subcategoryId=array(),$authorId=array(),$startDate='',$flag='')
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $articleId = $cdmismodel->getArticleIds_SA($subcategoryId,$authorId,$startDate,$flag);
        $articleIdArray= array();
        $i=0;
        foreach ($articleId as $key => $value) {
            $articleIdArray[$i++] = $value['contentId'];
        }
        return $articleIdArray;
    }
    function getPageviewData($dateRangeArray=array(),$extraData=array())
    {
        $this->engLib = $this->CI->load->library('trackingMIS/engagementLib');
        $result = $this->engLib->getPageviewData($dateRangeArray,'',$extraData);
         foreach ($result as $key => $gbd)    // gbd : group by date
            {
                $total += $gbd['doc_count'];
                $sourceWise = $gbd['sourseWise']['buckets'];
                foreach ($sourceWise as $key => $gbs)   // gbs: group by source
                {
                    $deviceWise = $gbs['siteSourse']['buckets'];
                    foreach ($deviceWise as $keyOne => $gbss)   //gbss: group by site source
                    {
                        $resData[$i++] = array(
                                            "responseDate" => date("Y-m-d", strtotime($gbd['key_as_string'])),
                                            "sourceWise"    => $gbs['key'],
                                            "siteSource" => ($gbss['key']=='no')?"Desktop":"Mobile",
                                            "responsescount" => $gbss['doc_count']
                                            );
                    }
                }
            }
            return $resData;
    }
    function prepareDataTableForRespondents($respondentsData,$sourceUserMapping)
    {
        // foreach ($responsesData as  $value) {
        //     $prepareTableData[$value['type']][$value['widget']][$value['siteSource']]['responsesCount']+=$value['reponsesCount'];
        // }
        $dataTableHeading = "Respondents Data";
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:20px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:20px">Id</th>'.
                            '<th style="padding-left:20px">Name </th>'.
                            '<th style="padding-left:20px">Email</th>'.
                            '<th style="padding-left:20px;width:100px">Mobile Number </th>'.
                            '<th style="padding-left:20px;width:100px">source </th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $i=0;
            foreach ($respondentsData as $key => $value) 
             {
                $sourceName = implode(',', array_unique($sourceUserMapping[$value['Id']]));
                if( empty($sourceName))
                    $sourceName = 'Others';
                    $dataTable = $dataTable.
                        '<tr class="even pointer">'.
                            '<td class="a-center ">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</td>'.
                            '<td class=" ">'.$value['Id'].'</td>'.
                            '<td class=" ">'.addslashes($value['firstName']).' '.addslashes($value['lastName']).'</td>'.
                            '<td class=" ">'.substr($value['Email'], 0, strpos($value['Email'], "@")+1).'*********'.'</td>'.
                            '<td class=" ">'.substr($value['MobileNumber'], 0, 5).'*****'.'</td>'.
                            '<td class=" ">'.$sourceName.'</td>'.
                        '</tr>';
                    $prepareDataForCSV[$i++] = array($value['Id'],$value['Name'],$value['Email'],$value['MobileNumber']);
            }
        $dataTable = $dataTable.'</tbody>';
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);

        return $DataForDataTable;
    }
    function createWeekWiseDataForElasticSearch($resData=array(),$startDate='',$endDate='')
    {
           $gendate = new DateTime();
        $startYear = date('Y', strtotime($startDate));
        $endYear = date('Y', strtotime($endDate));
            if($startYear == $endYear)
            {
                // creating week array
                $swn = date('W', strtotime($startDate));
                $ewn = date('W', strtotime($endDate)); 
                $lineArray = array();
                //$lineArray[$startDate] = 0;

                $getdate = new DateTime();
                if($swn > $ewn)
                    $conflictYear = $startYear-1;
                else 
                    $conflictYear = $startYear;
                $getdate->setISODate($conflictYear,$swn,1); //parameters are year,month,day no you want fetch the date
                $conflictDate=$getdate->format('Y-m-d');

                for ($i=$swn; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                
                foreach ($resData as $key =>  $value) {
                        $lineArray[$value['responseDate']] += $value['responsescount'];
                    }
                if($swn > $ewn)
                    $conflictYear = $startYear-1;
                else 
                    $conflictYear = $startYear;

                $gendate->setISODate($conflictYear,$swn,1); //year , week num , day
                $df = $gendate->format('Y-m-d');
                if(($lineArray[$startDate] ==0) && ($startDate != $df)){
                    $tempArray[$startDate] = $lineArray[$df]? $lineArray[$df] : 0;
                    unset($lineArray[$df]);  
                    $lineArray = $tempArray + $lineArray;
                    unset($tempArray);
                }
            }
            else
            {
                $swn = date('W', strtotime($startDate));
                $ewn =date('W', strtotime($startYear."-12-31"));
                if($ewn == 1){
                    $ewn = date('W', strtotime($startYear."-12-24"));
                }
                $swn1 = 1;
                $ewn1 =date('W', strtotime($endDate));
                $gendate->setISODate($startYear,$ewn,7); //year , week num , day
                $tempDate = $gendate->format('Y-m-d');
                if($tempDate >= $endDate){
                    $swn1 =0;
                    $ewn1 =-1;
                }
                $lineArray = array();
                $lineArray[$startDate] = 0;
                for ($i=$swn; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;
                }
                for ($i=$swn1; $i <= $ewn1 ; $i++) { 
                    $gendate->setISODate($endYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                //
                $getdate = new DateTime();
                $getdate->setISODate($startYear,$swn,1); //parameters are year,month,day no you want fetch the date
                $conflictDate=$getdate->format('Y-m-d');
                //
                foreach ($resData as $key =>  $value) {
                        $lineArray[$value['responseDate']] += $value['responsescount'];  
                    }
                $gendate->setISODate($startYear,$swn,1); //year , week num , day
                $df = $gendate->format('Y-m-d');
                if(($lineArray[$startDate] ==0) && ($startDate != $df)){
                    $tempArray[$startDate] = $lineArray[$df]? $lineArray[$df] :0;
                    unset($lineArray[$df]);
                    $lineArray = $tempArray + $lineArray;
                    unset($tempArray);
                } 
            }
            return $lineArray;
    }
    function createMonthWiseDataForElasticSearch($resData=array(),$startDate='',$endDate='')
    {
        $gendate = new DateTime();
        $startYear = date('Y', strtotime($startDate));
        $endYear = date('Y', strtotime($endDate));
        if($startYear == $endYear){
                $smn = date('m', strtotime($startDate));
                $emn = date('m', strtotime($endDate));
                $lineArray = array();
                $df = $startYear.'-'.$smn.'-01';
                $lineArray[$df] = 0;
                $lineArray[$startDate] = 0;       
                for ($i=$smn+1; $i <= $emn ; $i++){
                    if($i <= 9){
                        $i = '0'.$i;
                        $df = $startYear.'-'.$i.'-01';
                    }else{
                        $df = $startYear.'-'.$i.'-01';    
                    }
                    $lineArray[$df] = 0;       
                }
                foreach ($resData as  $value) {
                    $lineArray[$value['responseDate']] += $value['responsescount']; 
                    }
                
                $df = $startYear.'-'.$smn.'-01';
                if(($lineArray[$startDate] == 0) && ($startDate != $df)){
                    $lineArray[$startDate] = $lineArray[$df];
                    unset($lineArray[$df]);
                }
            }
            else{
                $smn = date('m', strtotime($startDate));
                $emn = 12;
                $smn1 = 1;
                $emn1 =date('m', strtotime($endDate));
                $lineArray = array();
                $lineArray[$startDate] = 0;
                $daten = $startDate;
                $mnp =0;
                $mnn =0;
                $y = date('Y', strtotime($resData[0]['responseDate']));
                $flag = 0;
                $sd='';
                for($i=$startYear; $i<=$endYear;$i++)
                {
                    
                    if($i == $startYear){
                        $sm =ltrim($smn,'0');    
                    }else{
                        $sm =1;
                    }

                    if($i == $endYear){
                        $em = ltrim($emn1,'0');
                    }else{
                        $em =12;
                    }
                    
                    for($j=$sm;$j<=$em;$j++)
                    {
                        if($j <= 9)
                        {
                            $daten = $i.'-0'.$j.'-01';
                        }else{
                            $daten = $i.'-'.$j.'-01';
                        }  
                        if($flag == 0)
                        {
                            $sd=$daten;
                            $flag=1;
                        }
                        $lineArray[$daten] = 0;
                    }
                }
                foreach ($resData as  $value) {
                    $lineArray[$value['responseDate']] += $value['responsescount']; 
                }
                $df = $startYear.'-'.$smn.'-01';
                if(($lineArray[$startDate] == 0) && ($startDate != $df)){
                    $lineArray[$startDate] = $lineArray[$df];
                    unset($lineArray[$df]);
                }
            }
            return $lineArray;
    }
    function getEngagementDataForCustomerDelivery($instituteId = array(),$courseId = array(),$articleId=array(),$discussionId= array(),$source='',$subcatArray='',$startDate='',$endDate='',$viewWise=1,$trafficSource='',$isStudyAbroad='no')
    {
        $this->engagementLib = $this->CI->load->library('trackingMIS/engagementLib');
        $dateRangeArray['startDate'] = $startDate;
        $dateRangeArray['endDate'] = $endDate;
        $extraData['CD'] = array();
        if( ! empty($instituteId))
        {
            $extraData['CD']['instituteId'] = $instituteId;
            $extraData['CD']['courseId'] =$courseId;
        }
        else if( ! empty($articleId))
        {
            $extraData['CD']['articleId'] = $articleId;
            $extraData['CD']['trafficSource'] = $trafficSource;
        }
        else if( ! empty($discussionId))
        {
            $extraData['CD']['discussionId'] = $discussionId;
            $extraData['CD']['trafficSource'] = $trafficSource;
        }
        else if( ! empty($subcatArray))
        {
            if( ! empty($subcatArray['subCategoryId']))
                    $extraData['CD']['subCategoryId'] = $subcatArray['subCategoryId'];
            $extraData['CD']['pageName'] = $subcatArray['pageName'];
            if( ! empty($subcatArray['cityId']))
            {
                $extraData['CD']['cityId'] = $subcatArray['cityId'];
            }
            if( ! empty($subcatArray['stateId']))
            {
                $extraData['CD']['stateId'] = $subcatArray['stateId'];
            }
            if( ! empty($subcatArray['authorId']))
            {
                $extraData['CD']['authorId'] = $subcatArray['authorId'];
            }
            if( ! empty($subcatArray['countryId']))
            {
                $extraData['CD']['countryId'] = $subcatArray['countryId'];
            }
            if( ! empty($trafficSource))
                $extraData['CD']['trafficSource'] = $trafficSource;
        }
        if($isStudyAbroad == 'yes')
        {
            $extraData['CD']['isStudyAbroad'] = 'yes';
        }
        $extraData['CD']['deviceType'] =strtolower($source);
        switch ($viewWise) {
                case '1':
                        $viewWise = 'day';
                        break;
                case '2':
                        $viewWise = 'week';
                        break;
                case '3':
                        $viewWise = 'month';
                        break;
                default:
                        $viewWise = 'day';
                        break;
        }
        $extraData['CD']['viewWise'] = $viewWise;
        $engageData = $this->engagementLib->getPageMetricData($dateRangeArray,'',$extraData);
        //$result = $result['aggregations']['dateWise']['buckets'];

        $engagementResult = array();
        $i =0;
        foreach ($engageData as $splitKey => $splitData) {
            $engagementResult[$splitKey] = array();
            switch($splitKey)
            {
                case 'pageView':
                      $totalPageviews = $splitData['hits']['total'];
                      $result = $splitData['aggregations']['dateWise']['buckets'];
                foreach ($result as $key => $value) {
                    $deviceWise = $value['deviceWise']['buckets'];
                    foreach ($deviceWise as $key => $deviceValue) {
                        $engagementResult[$splitKey][$i++] = array(
                            "responseDate" => date("Y-m-d", strtotime($value['key_as_string'])),
                            "siteSource" => ($deviceValue['key'] == 'no')?"Desktop":"Mobile",
                            "responsescount" => $deviceValue['doc_count']
                            );
                        }
                    }
                    //$engagementResult['totalPageviews'] = $totalPageviews;
                    break;
                case 'bounceRate':
                        $i =0;
                            $result = $splitData['aggregations']['dateWise']['buckets'];
                            foreach ($result as $key => $value) {
                                $deviceWise = $value['deviceWise']['buckets'];
                                $no_of_bounce_day_Desktop =0;
                                $no_of_bounce_day_mobile = 0;
                                foreach ($deviceWise as $deviceKey => $deviceValue) {
                                    $bounceWise = $deviceValue['Bounces']['buckets'];
                                        foreach ($bounceWise as $bounceKey => $bounceValue) {
                                            if($bounceValue['key'] == 1)
                                            {
                                                if($deviceValue['key'] == 'no')
                                                {
                                                    $no_of_bounce_day_Desktop = $bounceValue['doc_count'];
                                                }
                                                else 
                                                {
                                                    $no_of_bounce_day_mobile = $bounceValue['doc_count'];
                                                }
                                                
                                            }
                                            //$exitCount + = $bounceValue['doc_count'];
                                            
                                        }
                                    }
                                                   
                                        $bounceRate = number_format((($no_of_bounce_day_mobile + $no_of_bounce_day_Desktop) /  $value['doc_count'] ) * 100,2,'.','');
                                        $engagementResult[$splitKey][$i++] = array(
                                                "responseDate" => date("Y-m-d",strtotime($value['key_as_string'])),
                                                //'siteSource' => ($deviceValue['key'] == 'no')?"Desktop":"Mobile",
                                                'responsescount' => $bounceRate
                                                );
                                        
                                    
                                    
                                        
                                }
                                break;
                case 'avgPagePerSession':
                      $result = $splitData['aggregations']['dateWise']['buckets'];
                        foreach ($result as $key => $value) {
                            $deviceWise = $value['deviceWise']['buckets'];
                            $desktop_pageview = 0;
                            $mobile_pageview = 0;
                            foreach ($deviceWise as $key => $deviceValue) {
                                if($deviceValue['key'] == 'no')
                                {
                                    $desktop_pageview = $deviceValue['pageViewWise']['value'];
                                }
                                else if($deviceValue['key'] =='yes')
                                {
                                    $mobile_pageview = $deviceValue['pageViewWise']['value'];
                                }
                                }
                                $engagementResult[$splitKey][$i++] = array(
                                    "responseDate" => date("Y-m-d", strtotime($value['key_as_string'])),
                                    //"siteSource" => ($deviceValue['key'] == 'no')?"Desktop":"Mobile",
                                    "responsescount" => number_format(($mobile_pageview + $desktop_pageview) / $value['doc_count'],2,'.','')
                                    );
                            }
                    break;
                case 'avgSessionDuration':
                $i = 0;
                        $result = $splitData['aggregations']['dateWise']['buckets'];
                        foreach ($result as $key => $value) {
                            $deviceWise = $value['deviceWise']['buckets'];
                            $desktop_duration = 0;
                            $mobile_duration = 0;
                            foreach ($deviceWise as $deviceKey => $deviceValue) {
                                /*$engagementResult[$splitKey][$i++] = array(
                                    "responseDate" => date("Y-m-d", strtotime($value['key_as_string'])),
                                    "siteSource" => ($deviceValue['key'] == 'no')?"Desktop":"Mobile",
                                    "responsescount" => number_format($deviceValue['durationWise']['value'] / $deviceValue['doc_count'],2,'.','')
                                    );*/
                                    if($deviceValue['key'] == 'no')
                                    {
                                        $desktop_duration = $deviceValue['durationWise']['value'];
                                    }
                                    else
                                    {
                                        $mobile_duration = $deviceValue['durationWise']['value'];
                                    }
                            }
                            $engagementResult[$splitKey][$i++] = array(
                                    "responseDate" => date("Y-m-d", strtotime($value['key_as_string'])),
                                    //"siteSource" => ($deviceValue['key'] == 'no')?"Desktop":"Mobile",
                                    "responsescount" => number_format(($mobile_duration + $desktop_duration) / $value['doc_count'],2,'.','')
                                    );
                        }
                        break;
                case 'exitRate':
                        $i = 0;
                            $result = $splitData['aggregations']['dateWise']['buckets'];
                            foreach ($result as $key => $value) {
                                $deviceWise = $value['deviceWise']['buckets'];
                                foreach ($deviceWise as $deviceKey => $deviceValue) {
                                    $engagementResult[$splitKey][$i++] = array(
                                        "responseDate" => date("Y-m-d", strtotime($value['key_as_string'])),
                                        "siteSource" => ($deviceValue['key'] == 'no')?"Desktop":"Mobile",
                                        "responsescount" => $deviceValue['doc_count']
                                        );
                                }
                            }
                            break;
                                    
            }
          /*  $result = $splitData['aggregations']['dateWise']['buckets'];
        foreach ($result as $key => $value) {
                $deviceWise = $value['deviceWise']['buckets'];
                foreach ($deviceWise as $key => $deviceValue) {
                    $engagementResult[$splitKey][$i++] = array(
                        "responseDate" => date("Y-m-d", strtotime($value['key_as_string'])),
                        "siteSource" => ($deviceValue['key'] == 'no')?"Desktop":"Mobile",
                        "responsescount" => $deviceValue['doc_count']
                        );
                }
            }*/
        }
        unset($engageData);
        return $engagementResult;
    }
    function getPageviewForTopTenDiscussions($discussionId,$startDate,$endDate)
    {
        if(! empty($discussionId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $extraData['CD']['discussionId'] = $discussionId;
        
            $dateRangeArray['startDate'] = $startDate;
            $dateRangeArray['endDate']   = $endDate;
            $this->engagementLib = $this->CI->load->library('trackingMIS/engagementLib');
            $pageViewResult = $this->engagementLib->getPageviewDataCD($dateRangeArray,$extraData);
            $pageViewResult = $pageViewResult['aggregations']['pageIdWise']['buckets'];
            $pageviewByDiscussionId = array();
            $i = 0;
            $totalPageviews = 0;
            $totalComments = 0;
            foreach ($pageViewResult as $key => $value) {
                $pageviewByDiscussionId[$value['key']] = $value['doc_count'];
                $totalPageviews += $value['doc_count'];
                }
            uasort($pageviewByDiscussionId,function($c1,$c2){
                return (($c1 >=$c2)?-1:1);
            });

            $discussionComments = $cdmismodel->getTotalCommentsOnDiscussionIds($discussionId,$startDate,$endDate);
            $discussionCommentArray = array();
            foreach ($discussionComments as $key => $value) {
                $discussionCommentArray[$value['discussionId']] = $value['commentCount'];
                $totalComments += $value['commentCount'];
                }
            if( ! empty($pageviewByDiscussionId))
                $dataTable = $this->prepareDataForTopTenDiscussions($pageviewByDiscussionId,$discussionCommentArray);
                
                $result['dataTable'] = $dataTable;
                $result['totalPageviews'] = $totalPageviews?$totalPageviews:0;
                $result['totalComments'] = $totalComments?$totalComments:0;
        }
        return $result;
    }
    function prepareDataForTopTenDiscussions($pageviewByDiscussionId,$discussionCommentArray)
    {
        $discussionId = array();

        $len = count($pageviewByDiscussionId);
        foreach ($pageviewByDiscussionId as $key => $value) {
            $discussionId[$i++] = $key;
            if($i == 10 || $i == $len)
                break;
        }
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $userId = $cdmismodel->getUserIdsForDiscussionIds($discussionId);
        $userIdArray = array();
        $discussionId_UserId_array = array();
        $i = 0;
        foreach ($userId as $key => $value) {
            $userIdArray[$i] = $value['userId'];
            $discussionId_UserId_array[$value['discussionId']] = array('userId' => $value['userId'],'discussionId' => $value['discussionId']);
            $i++;
        }
        $authorName = $cdmismodel->getAuthorNames($userIdArray);
        $authorNamesArray = array();
        $i = 0;
        foreach ($authorName as $key => $value) {
            $authorNamesArray[$value['userid']] = $value['firstName'].''.$value['lastName'];
        }
        $discussionDetailArray = array();
        foreach ($discussionId_UserId_array as $key => $value) {
            if( ! array_key_exists($value['discussionId'], $discussionCommentArray))
            {
                $discussionCommentArray[$value['discussionId']] = 0;
            }
            $discussionDetailArray[$i++] = array('discussionId' => $value['discussionId'],'authorName'=> $authorNamesArray[$value['userId']],'Comments'=>$discussionCommentArray[$value['discussionId']],'PageViews'=>$pageviewByDiscussionId[$value['discussionId']]);
        }
        return $this->prepareDataTableForTopTenDiscussions($discussionDetailArray);


    }
    function prepareDataTableForTopTenDiscussions($result)
    {
        $dataTableHeading = "Top Ten Discussions Based on PageViews";
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:20px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:20px">S.NO</th>'.
                            '<th style="padding-left:20px">Discussion Id</th>'.
                            '<th style="padding-left:20px">Author Name</th>'.
                            '<th style="padding-left:20px">PageViews</th>'.
                            '<th style="padding-left:20px">Comments</th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $i=1;
            foreach ($result as $key => $value) 
             {
                    $dataTable = $dataTable.
                        '<tr class="even pointer">'.
                            '<td class="a-center ">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</td>'.
                            '<td class=" ">'.$i.'</td>'.
                            '<td class=" ">'.$value['discussionId'].'</td>'.
                            '<td class=" ">'.$value['authorName'].'</td>'.
                            '<td class=" ">'.$value['PageViews'].'</td>'.
                            '<td class=" ">'.$value['Comments'].'</td>'.
                        '</tr>';
                    //$prepareDataForCSV[$i++] = array($value['Id'],$value['Name'],$value['Email'],$value['MobileNumber']);
                        $i++;
            }
        $dataTable = $dataTable.'</tbody>';
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);

        return $DataForDataTable;
    }
    function getPageviewForTopTenArticles($articleId,$startDate,$endDate,$isStudyAbroad='no')
    {
         if ( ! empty($articleId))
        {
            $extraData['CD']['articleId'] = $articleId;
        }
        if( $isStudyAbroad == 'yes')
            $extraData['CD']['isStudyAbroad'] = 'yes';
        $extraData['CD']['Overview'] = 1;
        $dateRangeArray['startDate'] = $startDate;
        $dateRangeArray['endDate'] = $endDate;
        $this->engagementLib = $this->CI->load->library('trackingMIS/engagementLib');
        $topTilesResult = array();
        $pageViewResult = $this->engagementLib->getPageviewDataCD($dateRangeArray,$extraData);
        $pageViewResult = $pageViewResult['aggregations']['pageIdWise']['buckets'];

        $pageviewByArticleId = array();
            $i = 0;
            foreach ($pageViewResult as $key => $value) {
                $pageviewByArticleId[$value['key']] = $value['doc_count'];
                }
            uasort($pageviewByArticleId,function($c1,$c2){
                return (($c1 >=$c2)?-1:1);
            });
        
        if($isStudyAbroad == 'no')
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $totalPageviews = 0;
            $totalComments = 0 ;
            if( !empty($articleId))
            {
                    $articleComment = $cdmismodel->getTotalArticleCommentData($articleId,$startDate,$endDate);
                $articleCommentArray = array();
                foreach ($articleComment as $key => $value) {
                    $articleCommentArray[$value['blogId']] = $value['commentCount'];
                    }
                foreach ($pageviewByArticleId as $key => $value) {
                    $totalPageviews += $value;
                }
                foreach ($articleCommentArray as $key => $value) {
                    $totalComments += $value;
                }
                if( ! empty($pageviewByArticleId))
                    $dataTable = $this->prepareDataForTopTenArticle_Domestic($pageviewByArticleId,$articleCommentArray);
            }
            $result['dataTable'] = $dataTable;
            $result['totalPageviews'] = number_format($totalPageviews);
            $result['totalComments'] = number_format($totalComments);
            return $result;
        }
        else if($isStudyAbroad == 'yes')
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $totalPageviews = 0;
            $totalComments = 0 ;
            if(! empty($articleId))
            {
                $articleComment = $cdmismodel->getTotalArticleCommentData_SA($articleId,$startDate,$endDate);
            foreach ($articleComment as $key => $value) {
                    $articleCommentArray[$value['contentId']] = $value['commentCount'];
                }

            foreach ($pageviewByArticleId as $key => $value) {
                $totalPageviews += $value;
            }
            foreach ($articleCommentArray as $key => $value) {
                $totalComments += $value;
            }
            if( ! empty($pageviewByArticleId))
                $dataTable = $this->prepareDataForTopTenArticle_StudyAbroad($pageviewByArticleId,$articleCommentArray);
            }
            $result['dataTable'] = $dataTable;
            $result['totalPageviews'] = number_format($totalPageviews);
            $result['totalComments'] = number_format($totalComments);
            return $result;
                
        }
    }
    function prepareDataForTopTenArticle_Domestic($pageviewByArticleId,$articleCommentArray)
    {
        $articleId = array();
        $len = count($pageviewByArticleId);
        foreach ($pageviewByArticleId as $key => $value) {
            $articleId[$i++] = $key;
            if($i == 10 || $i == $len)
                break;
        }
        
        /*$authorNames = $cdmismodel->getAuthorNames($articleId);
        $authorNamesArray = array();
        foreach ($authorNames as $key => $value) {
            $authorNamesArray[$value['userid']] = $value['firstName'].' '.$value['lastName'];
        }
        $articleTitle = $cdmismodel->getArticleTitles($articleId);
        $articleTitleArray = array();
        foreach ($articleTitle as $key => $value) {
            $articleTitleArray[$value['blogId']] = array('title'=>$value['blogTitle'],'url'=>$value['url']);
        }*/
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $articleTitle = $cdmismodel->getArticleDetails($articleId);
        $articleTitleArray = array();
        foreach ($articleTitle as $key => $value) {
            $articleTitleArray[$value['blogId']] = array('title' => $value['title'],'authorName' => $value['firstName'].' '.$value['lastName'],'url'=>$value['url']);
        }
        
        $articleDetailArray = array();
        $i = 0;
        foreach ($articleTitleArray as $key => $value) {
            if( ! array_key_exists($key, $articleCommentArray))
            {
                $articleCommentArray[$key] = 0;
            }

            $articleDetailArray[$i++] = array('title' => $value['title'], 'url' =>$value['url'], 'authorName' =>$value['authorName'],'PageViews' => $pageviewByArticleId[$key],'Comments' =>$articleCommentArray[$key]);
        }
            return $this->prepareDataTableForTopTenArticles($articleDetailArray);
            
    }
    function prepareDataForTopTenArticle_StudyAbroad($pageviewByArticleId,$articleCommentArray)
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
          $articleId = array();
        $len = count($pageviewByArticleId);
        foreach ($pageviewByArticleId as $key => $value) {
            $articleId[$i++] = $key;
            if($i == 10 || $i == $len)
                break;
        }
        
        $articleTitle = $cdmismodel->getArticleDetails_SA($articleId);
        $articleTitleArray = array();
        foreach ($articleTitle as $key => $value) {
            $value['url'] = addingDomainNameToUrl(array('url' => $value['url'] ,'domainName' => SHIKSHA_STUDYABROAD_HOME));
            $articleTitleArray[$value['contentId']] = array('title' => $value['title'],'authorName' => $value['firstName'].' '.$value['lastName'],'url'=>$value['url']);
        }
        
        $articleDetailArray = array();
        $i = 0;
        foreach ($articleTitleArray as $key => $value) {
            if( ! array_key_exists($key, $articleCommentArray))
            {
                $articleCommentArray[$key] = 0;
            }
            $articleDetailArray[$i++] = array('title' => $value['title'], 'url' =>$value['url'], 'authorName' =>$value['authorName'],'PageViews' => $pageviewByArticleId[$key],'Comments' =>$articleCommentArray[$key]);
        }

        return  $this->prepareDataTableForTopTenArticles($articleDetailArray);
            

    }
    function prepareDataTableForTopTenArticles($result)
    {
        $dataTableHeading = "Top Ten Articles Based on PageViews";
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:20px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:20px">S.NO</th>'.
                            '<th style="padding-left:20px">Article Title</th>'.
                            '<th style="padding-left:20px">URL</th>'.
                            '<th style="padding-left:20px">Author Name</th>'.
                            '<th style="padding-left:20px">PageViews</th>'.
                            '<th style="padding-left:20px">Comments</th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $i=1;
            foreach ($result as $key => $value) 
             {
                    $dataTable = $dataTable.
                        '<tr class="even pointer">'.
                            '<td class="a-center ">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</td>'.
                            '<td class=" ">'.$i.'</td>'.
                            '<td class=" ">'.addslashes($value['title']).'</td>'.
                            '<td class=" ">'.$value['url'].'</td>'.
                            '<td class=" ">'.$value['authorName'].'</td>'.
                            '<td class=" ">'.$value['PageViews'].'</td>'.
                            '<td class=" ">'.$value['Comments'].'</td>'.
                        '</tr>';
                    //$prepareDataForCSV[$i++] = array($value['Id'],$value['Name'],$value['Email'],$value['MobileNumber']);
                        $i++;
            }
        $dataTable = $dataTable.'</tbody>';
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);

        return $DataForDataTable;
    }
    function getLeadDeliveryForClient($clientId,$startDate,$endDate,$viewWise=1,$flag = 'national')
    {
        $lineChartArray = array();        
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        if( ! empty($clientId))
            $lead_genies = $cdmismodel->getLeadGenies($clientId);
        $lead_normal_genies = array();
        $lead_porting_genies = array();
        $i = 0; $j =0;
        foreach ($lead_genies as $key => $value) {
            if($value['deliveryMethod'] == 'normal')
            {
                $lead_normal_genies[$i++] = $value['searchagentid'];
            }
            else if($value['deliveryMethod'] == 'porting')
            {
                $lead_porting_genies[$j++] = $value['searchagentid'];
            }
        }
        if( ! empty($lead_normal_genies))
            $lead_normal_delivery = $cdmismodel->getDeliveryDataByEmailGeniesForLineChart($lead_normal_genies,$startDate,$endDate,$viewWise);

        if( ! empty($lead_porting_genies))
            $lead_porting_delivery = $cdmismodel->getDeliveryDataByPortingGeniesForLineChart($lead_porting_genies,$startDate,$endDate,$viewWise);
        $i = 0;
        
        if($viewWise == 1)
        {
            foreach ($lead_normal_delivery as $key => $value) {
                if( array_key_exists($value['responseDate'], $lineChartArray))
                    array_push($lineChartArray[$value['responseDate']], $value['userid']);
                else
                    $lineChartArray[$value['responseDate']] = array($value['userid']);

            }

            foreach ($lead_porting_delivery as $key => $value) {
                if( array_key_exists($value['responseDate'], $lineChartArray))
                    array_push($lineChartArray[$value['responseDate']], $value['userid']);
                else
                    $lineChartArray[$value['responseDate']] = array($value['userid']);
            }
        }
        else if($viewWise == 2)
        {   
            foreach ($lead_normal_delivery as $key => $value) {
                if(array_key_exists($value['weekNumber'], $lineChartArray))
                    array_push($lineChartArray[$value['weekNumber']],$value['userid']);
                else
                    $lineChartArray[$value['weekNumber']] = array($value['userid']);
            }
            foreach ($lead_porting_delivery as $key => $value) {
                if(array_key_exists($value['weekNumber'], $lineChartArray))
                    array_push($lineChartArray[$value['weekNumber']],$value['userid']);
                else
                    $lineChartArray[$value['weekNumber']] = array($value['userid']);
            }
        }
        else if($viewWise == 3)
        {
            foreach ($lead_normal_delivery as $key => $value) {
                if(array_key_exists($value['responseDate'], $lineChartArray))
                {
                    array_push($lineChartArray[$value['responseDate']]['userid'], $value['userid']);
                }
                else
                {
                    $lineChartArray[$value['responseDate']] = array('monthNumber' => $value['monthNumber'],'userid'=> array($value['userid']));
                }
            }
            foreach ($lead_porting_delivery as $key => $value) {
                if(array_key_exists($value['responseDate'], $lineChartArray))
                {
                    array_push($lineChartArray[$value['responseDate']]['userid'], $value['userid']);
                }
                else
                {
                    $lineChartArray[$value['responseDate']] = array('monthNumber' => $value['monthNumber'],'userid'=> array($value['userid']));
                }
            }

        }
        if( ! empty($clientId))
            $view_lead_delivery = $cdmismodel->getViewDeliveryForLineChart($clientId,$startDate,$endDate,$viewWise,1,$flag);
        if($viewWise == 1)
        {
            foreach ($view_lead_delivery as $key => $value) {
                if(array_key_exists($value['responseDate'], $lineChartArray))
                {
                    array_push($lineChartArray[$value['responseDate']],$value['userid']);
                }
                else
                {
                    $lineChartArray[$value['responseDate']] = array($value['userid']);
                }
            }
        }
        else if($viewWise == 2)
        {
            foreach ($view_lead_delivery as $key => $value) {
                  if(array_key_exists($value['weekNumber'], $lineChartArray))
                    array_push($lineChartArray[$value['weekNumber']],$value['userid']);
                else
                    $lineChartArray[$value['weekNumber']] = array($value['userid']);
            }
        }
        else if($viewWise == 3)
        {
            foreach ($view_lead_delivery as $key => $value) {
                if(array_key_exists($value['responseDate'], $lineChartArray))
                {
                    array_push($lineChartArray[$value['responseDate']]['userid'], $value['userid']);
                }
                else
                {
                    $lineChartArray[$value['responseDate']] = array('monthNumber' => $value['monthNumber'],'userid'=> array($value['userid']));
                }
            }
        }
        $i = 0;
        $leadLineChartArray = array();
        if($viewWise == 1)
        {
            foreach ($lineChartArray as $key => $value) {
                $leadLineChartArray[$i++] = array('responseDate'=>$key,'responsescount'=>count(array_unique($value)));
            }
        }
        else if($viewWise == 2)
        {
            foreach ($lineChartArray as $key => $value) {
                $leadLineChartArray[$i++] = array('weekNumber'=>$key,'responsescount'=>count(array_unique($value)));
            }
        }
        else if($viewWise == 3)
        {
            ksort($lineChartArray);
            foreach ($lineChartArray as $key => $value) {
                $leadLineChartArray[$i++] = array('responseDate' => $key,'monthNumber'=>$value['monthNumber'],'responsescount'=>count(array_unique($value['userid'])));
            }
        }
        return $leadLineChartArray;   
    }
    //start1
    function getResponseDeliveryForClient($clientId,$startDate,$endDate,$viewWise=1,$flag = 'national')
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        if( ! empty($clientId))
            $response_genies = $cdmismodel->getResponseGenies($clientId);
        $response_normal_genies = array();
        $response_porting_genies = array();
        $i = 0; $j =0;
        foreach ($response_genies as $key => $value) {
            if($value['deliveryMethod'] == 'normal')
            {
                $response_normal_genies[$i++] = $value['searchagentid'];
            }
            else if($value['deliveryMethod'] == 'porting')
            {
                $response_porting_genies[$j++] = $value['searchagentid'];
            }
        }
        if( ! empty($response_normal_genies))
            $response_normal_delivery = $cdmismodel->getDeliveryDataByEmailGeniesForLineChart($response_normal_genies,$startDate,$endDate,$viewWise);
        if( ! empty($response_porting_genies))
            $response_porting_delivery = $cdmismodel->getDeliveryDataByPortingGeniesForLineChart($response_porting_genies,$startDate,$endDate,$viewWise);
        $lineChartArray = array();
        $i = 0;
        
        if($viewWise == 1)
        {
            foreach ($response_normal_delivery as $key => $value) {
                if(array_key_exists($value['responseDate'], $lineChartArray))
                {
                    array_push($lineChartArray[$value['responseDate']],$value['userid']);
                }
                else
                {
                    $lineChartArray[$value['responseDate']] = array($value['userid']);
                }
            }
            foreach ($response_porting_delivery as $key => $value) {
                if(array_key_exists($value['responseDate'], $lineChartArray))
                {
                    array_push($lineChartArray[$value['responseDate']],$value['userid']);
                }
                else
                {
                    $lineChartArray[$value['responseDate']] = array($value['userid']);
                }
            }
        }
        else if($viewWise == 2)
        {   
            foreach ($response_normal_delivery as $key => $value) {
                if(array_key_exists($value['weekNumber'], $lineChartArray))
                {
                    array_push($lineChartArray[$value['weekNumber']],$value['userid']);
                }
                else
                {
                    $lineChartArray[$value['weekNumber']] = array($value['userid']);
                }
            }
            foreach ($response_porting_delivery as $key => $value) {
                if(array_key_exists($value['weekNumber'], $lineChartArray))
                {
                    array_push($lineChartArray[$value['weekNumber']],$value['userid']);
                }
                else
                {
                    $lineChartArray[$value['weekNumber']] = array($value['userid']);
                }
            }
        }
        else if($viewWise == 3)
        {
            foreach ($response_normal_delivery as $key => $value) {
                if(array_key_exists($value['responseDate'], $lineChartArray))
                {
                    array_push($lineChartArray[$value['responseDate']]['userid'], $value['userid']);
                }
                else
                {
                    $lineChartArray[$value['responseDate']] = array('monthNumber' => $value['monthNumber'],'userid'=> array($value['userid']));
                }
            }
            foreach ($response_porting_delivery as $key => $value) {
                if(array_key_exists($value['responseDate'], $lineChartArray))
                {
                    array_push($lineChartArray[$value['responseDate']]['userid'], $value['userid']);
                }
                else
                {
                    $lineChartArray[$value['responseDate']] = array('monthNumber' => $value['monthNumber'],'userid'=> array($value['userid']));
                }
            }
        }
        if( ! empty($clientId))
            $view_response_delivery = $cdmismodel->getViewDeliveryForLineChart($clientId,$startDate,$endDate,$viewWise,0,$flag);
        if($viewWise == 1)
        {
            foreach ($view_response_delivery as $key => $value) {
                if(array_key_exists($value['responseDate'], $lineChartArray))
                {
                    array_push($lineChartArray[$value['responseDate']],$value['userid']);
                }
                else
                {
                    $lineChartArray[$value['responseDate']] = array($value['userid']);
                }
            }
        }
        else if($viewWise == 2)
        {
            foreach ($view_response_delivery as $key => $value) {
               if(array_key_exists($value['weekNumber'], $lineChartArray))
                {
                    array_push($lineChartArray[$value['weekNumber']],$value['userid']);
                }
                else
                {
                    $lineChartArray[$value['weekNumber']] = array($value['userid']);
                }
            }
        }
        else if($viewWise == 3)
        {
            foreach ($view_response_delivery as $key => $value) {
                if(array_key_exists($value['responseDate'], $lineChartArray))
                {
                    array_push($lineChartArray[$value['responseDate']]['userid'], $value['userid']);
                }
                else
                {
                    $lineChartArray[$value['responseDate']] = array('monthNumber' => $value['monthNumber'],'userid'=> array($value['userid']));
                }
            }
        }
        $i = 0;
        $responseLineChartArray = array();
        if($viewWise == 1)
        {
            foreach ($lineChartArray as $key => $value) {
                $responseLineChartArray[$i++] = array('responseDate'=>$key,'responsescount'=>count(array_unique($value)));
            }
        }
        else if($viewWise == 2)
        {
            foreach ($lineChartArray as $key => $value) {
                $responseLineChartArray[$i++] = array('weekNumber'=>$key,'responsescount'=>count(array_unique($value)));
            }
        }
        
        else if($viewWise == 3)
        {
            ksort($lineChartArray);
            foreach ($lineChartArray as $key => $value) {
                $responseLineChartArray[$i++] = array('responseDate' => $key,'monthNumber'=>$value['monthNumber'],'responsescount'=>count(array_unique($value['userid'])));
            }
        }
        return $responseLineChartArray;   
    }
    //end1
    function getActualDeliveryToClient($clientIdArray,$startDate,$endDate,$viewWise=1)
    {
           
        if( ! empty($clientIdArray))
        {  
           $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
           
           $agent_type_array = $cdmismodel->getClientDelivery_AgentId_Type($clientIdArray);
            $agentIdArray = array();
            $agent_type_mapping= array();
            $i = 0;
            foreach ($agent_type_array as $key => $value) {
                $agentIdArray[$i++] = $value['searchagentid'];
                $agent_type_mapping[$value['searchagentid']] = $value['type'];
            }
           if( ! empty($agentIdArray)) 
           {
           $deliveryToClient = $cdmismodel->getActualDeliveryToClient($agentIdArray,$startDate,$endDate,$viewWise);
           $leadDelivery = array();
           $responseDelivery = array();
           $i = 0 ; $j = 0;
           if($viewWise == 1)
           {
                foreach ($deliveryToClient as $key => $value) {
                    if($agent_type_mapping[$value['agentid']] == 'lead')
                        { 
                            $leadDelivery[$i++] = array('responseDate' => $value['deliveryDate'],'responsescount' => $value['deliveryCount']);
                        }
                    elseif ($agent_type_mapping[$value['agentid']] == 'response') {
                            $responseDelivery[$j++] = array('responseDate' => $value['deliveryDate'],'responsescount' => $value['deliveryCount']);
                        }
                    }
            }
            else if($viewWise == 2)
            {
                foreach ($deliveryToClient as $key => $value) {
                    if($agent_type_mapping[$value['agentid']] == 'lead')
                    {
                        $leadDelivery[$i++] = array('weekNumber'=> $value['weekNumber'],'responsescount' => $value['deliveryCount']);
                    }
                    elseif ($agent_type_mapping[$value['agentid']] == 'response') {
                        $responseDelivery[$j++] = array('weekNumber'=> $value['weekNumber'],'responsescount' => $value['deliveryCount']);    
                    }
                }
            }
            else 
            {
                foreach ($deliveryToClient as $key => $value) {
                    if($agent_type_mapping[$value['agentid']]== 'lead')
                    {
                        $leadDelivery[$i++] = array('responseDate' => $value['deliveryDate'],
                                                      'monthNumber'  => $value['monthNumber'],
                                                    'responsescount' => $value['deliveryCount']
                            );   
                    }
                    else if($agent_type_mapping[$value['agentid']] == 'response')
                    {
                        $responseDelivery[$j++] = array('responseDate' => $value['deliveryDate'],
                                                'monthNumber'  => $value['monthNumber'],
                                                'responsescount' => $value['deliveryCount']
                         );     
                    }
                }
            }
        }
           
        }
        $result['leadDeliveryArray'] = $leadDelivery;
        $result['responseDeliveryArray'] = $responseDelivery;
        return $result;

    }
    function getSubcategoryId($categoryId)
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $result = $cdmismodel->getSubcategoryId($categoryId);
        $subcategoryIdArray = array();
        $i = 0;
        foreach ($result as $key => $value) {
            $subcategoryIdArray[$i++] = $value['subcatId'];
        }
        return $subcategoryIdArray;
    }
    function getInstitutesInSubcategory($subcategoryId) 
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $instituteResult = $cdmismodel->getInstitutesBasedOnCity($subcategoryId);
        $instituteIdArray = array();
        $i = 0;
        foreach ($instituteResult as $key => $value) {
            $instituteIdArray[$i++] = $value['instituteId'];
        }
        return $instituteIdArray;
    }
    function getInstituteClientIds($instituteId = '',$listing_type = 'institute')
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $result  = $cdmismodel->getInstituteClientIds($instituteId,$listing_type);
        $i = 0;
        $clientIdArray = array();
        foreach ($result as $key => $value) {
            $clientIdArray[$i++] = $value['username'];
        }
        return $clientIdArray;
    }
    function getDeliveryToClient($clientIdArray,$startDate,$endDate)
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $agent_type_array = $cdmismodel->getClientDelivery_AgentId_Type($clientIdArray);
        $agentIdArray = array();
        $agent_type_mapping= array();
        $i = 0;
        foreach ($agent_type_array as $key => $value) {
            $agentIdArray[$i++] = $value['searchagentid'];
            $agent_type_mapping[$value['searchagentid']] = $value['type'];
        }
        $deliveryResult = $cdmismodel->getDeliveryToClient($agentIdArray,$startDate,$endDate);
        $leadCount = 0;
        $responseCount = 0;
        foreach ($deliveryResult as $key => $value) {
                if($agent_type_mapping[$value['agentid']] == 'lead')
                {
                    $leadCount += $value['deliveryCount'];
                }
                else if($agent_type_mapping[$value['agentid']] == 'response')
                {
                    $responseCount += $value['deliveryCount'];
                }
        }
        $result['leadCount'] = $leadCount;
        $result['responseCount'] = $responseCount;
        return $result;
    }
    function getActualDeliveryDataForDataTable($startDate,$endDate)
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $clientIdResult = $cdmismodel->getClientId_InstituteNames();        
        $clinetId_institute_mapping = array();
        $clientIdArray = array();
        $i = 0;
        foreach ($clientIdResult as $key => $value) {
            $clientIdArray[$i++] = $value['username'];
            $clientId_institute_mapping[$value['username']] = $value['instituteName'];
        }
        $agentIdResult = $cdmismodel->getClientDelivery_AgentId_Type($clientIdArray);   
        $agentId_clientId_mapping = array();
        $agentIdArray = array();
        $i = 0;
        foreach ($agentIdResult as $key => $value) {
            $agentIdArray[$i++] = $value['searchagentid'];
            $agentId_clientId_mapping[$value['searchagentid']] = array('clientId'=>$value['clientid'],'type'=>$value['type']);
        }
        $deliveryResult = $cdmismodel->getDeliveryToClient($agentIdArray,$startDate,$endDate);

        $salesResult = $cdmismodel->getSalesPerClient($clientIdArray,$startDate,$endDate);
        $client_sale_mapping = array();
        foreach ($salesResult as $key => $value) {
            $client_sale_mapping[$clientId_institute_mapping[$value['ClientUserId']]] = $value['price'];
        }

        $transaction_collection = $cdmismodel->getTransactionId_Paid($startDate,$endDate);
        $transaction_collection_mapping = array();
        $transactionIdArray = array();
        $i = 0;
        foreach ($transaction_collection as $key => $value) {
            $transaction_collection_mapping[$value['transactionId']] = $value['payment'];
            $transactionIdArray[$i++] = $value['transactionId'];
        }

        if( ! empty($transactionIdArray))
        {
            $client_transaction = $cdmismodel->getClientIdForTransactionId($transactionIdArray);
        }        
        $client_collection_mapping = array();
        foreach ($client_transaction as $key => $value) {
            $client_collection_mapping[$clientId_institute_mapping[$value['ClientUserId']]] += $transaction_collection_mapping[ltrim($value['TransactionId'], '0')];
        }
        foreach ($deliveryResult as $key => $value) {
            if($agentId_clientId_mapping[$value['agentid']]['type'] == 'lead')
            {
                $tempClientId = $agentId_clientId_mapping[$value['agentid']]['clientId'];
                $leadDelivery[$clientId_institute_mapping[$tempClientId]] += $value['deliveryCount'];
            }
            else if($agentId_clientId_mapping[$value['agentid']]['type'] == 'response')
            {
                $tempClientId = $agentId_clientId_mapping[$value['agentid']]['clientId'];
                $responseDelivery[$clientId_institute_mapping[$tempClientId]] += $value['deliveryCount'];   
            }
        }/*
        $result['leadDelivery'] = $leadDelivery;
        $result['responseDelivery'] = $responseDelivery;*/
        $lead_response_delivery = array();
        foreach ($leadDelivery as $key => $value) {
            $lead_response_delivery[$key]['lead'] = $value;
            $lead_response_delivery[$key]['response'] = 0;
            $lead_response_delivery[$key]['sales'] = 0;
            $lead_response_delivery[$key]['collections'] =0;
        }
        foreach ($responseDelivery as $key => $value) {
            if( array_key_exists($key, $lead_response_delivery))
                $lead_response_delivery[$key]['response'] = $value;
            else
            {
                $lead_response_delivery[$key]['lead'] = 0;
                $lead_response_delivery[$key]['response'] = $value;
                $lead_response_delivery[$key]['sales'] = 0;
                $lead_response_delivery[$key]['collections'] =0;
            }
        }
        foreach ($client_sale_mapping as $key => $value) {
            if( array_key_exists($key, $value))
            {
                $lead_response_delivery[$key]['sales']= $value;
            }
            else
            {
                $lead_response_delivery[$key]['sales']= $value;
                $lead_response_delivery[$key]['lead'] = 0;
                $lead_response_delivery[$key]['response'] = 0;
                $lead_response_delivery[$key]['collections'] =0;
            }
        }
        foreach ($client_collection_mapping as $key => $value) {
            if(array_key_exists($key, $lead_response_delivery))
            {
                $lead_response_delivery[$key]['collections'] = $value;
            }
            else
            {
                $lead_response_delivery[$key]['response'] =0;
                $lead_response_delivery[$key]['lead'] = 0;
                $lead_response_delivery[$key]['sales']= 0;
                $lead_response_delivery[$key]['collections'] = $value;
            }
        }

        return $this->prepareDataTableForActualDelivery($lead_response_delivery);
        
    }
    function getZoneWiseCollections($clientId,$startDate,$endDate)
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $transactionId = $cdmismodel->getZoneWiseTransactions($clientId);
        $transactionIdArray = array();
        $i =0;
        foreach ($transactionId as $key => $value) {
            $transactionIdArray[$i++] = ltrim($value['transactionId'],'0');
        }
        $payment = 0;
        if( ! empty($transactionIdArray))
            $payment = $cdmismodel->getZoneWiseCollections($transactionIdArray,$startDate,$endDate);
        return $payment;

    }
    function getClientDeliveryData($clientId,$startDate,$endDate,$listing_type = 'institute')
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $salesResult = $cdmismodel->getSalesPerClient($clientId,$startDate,$endDate);
        $client_sale_mapping = array();
        $clientId = array();
        $i = 0;
        $lead_response_dataTable = array();
        foreach ($salesResult as $key => $value) {
                $lead_response_dataTable[$value['ClientUserId']]['sales'] = $value['price'];
                $lead_response_dataTable[$value['ClientUserId']]['lead'] = array();
                $lead_response_dataTable[$value['ClientUserId']]['response'] = array();
                $lead_response_dataTable[$value['ClientUserId']]['collections'] =0 ;
            $clientId[$i++] = $value['ClientUserId'];
        }
        if( ! empty($clientId))
            $clientIdResult = $cdmismodel->getClientId_InstituteNames($clientId,$listing_type);        
        $clientId_institute_mapping = array();
        $i = 0;
        foreach ($clientIdResult as $key => $value) {
            $clientId_institute_mapping[$value['username']] = $value['instituteName'];
        }
        if(! empty($clientId))
            $mappingResult = $cdmismodel->getclientgenies($clientId);
        $porting_genies_array = array();
        $email_genies_array = array();
        $i = 0;
        $j = 0;
        $agentId_clientId_mapping = array();
        foreach ($mappingResult as $keyName => $keyValue) {
            if($keyValue['deliveryMethod'] == 'normal')
            {
                $email_genies_array[$i++] = $keyValue['searchagentid'];
            }
            else if($keyValue['deliveryMethod'] == 'porting')
            {
                $porting_genies_array[$j++] = $keyValue['searchagentid'];
            }
            $agentId_clientId_mapping[$keyValue['searchagentid']] = array('clientid' => $keyValue['clientid'],
                                                                      'type' => $keyValue['type'],'deliveryMethod' => $keyValue['deliveryMethod']);
        }
        if( ! empty($email_genies_array))
            $email_user_delivery =$cdmismodel->getDeliveryDataByEmailGenies($email_genies_array,$startDate,$endDate);
        $email_user_delivery_array = array();
        $i = 0;
        foreach ($email_user_delivery as $key => $value) {
            if( array_key_exists($value['agentid'], $email_user_delivery_array))
            {
                  array_push($email_user_delivery_array[$value['agentid']],$value['userid']);
            }
            else
            {
                $email_user_delivery_array[$value['agentid']] = array($value['userid']);
            }
        }
        if( ! empty($porting_genies_array))
        {
            $porting_user_delivery = $cdmismodel->getDeliveryDataByPortingGenies($porting_genies_array,$startDate,$endDate);
        }
        $porting_user_delivery_array = array();
        foreach ($porting_user_delivery as $key => $value) {
            if( array_key_exists($value['agentid'], $porting_user_delivery_array))
            {
                array_push($porting_user_delivery_array[$value['agentid']],$value['userid']);
            }
            else
            {
                $porting_user_delivery_array[$value['agentid']] = array($value['userid']);
            }
        }
        //$lead_response_dataTable = array();
        foreach ($agentId_clientId_mapping as $keyName => $keyValue) {
            //
            if($keyValue['type'] == 'lead')
            {
                if( empty($lead_response_dataTable[$keyValue['clientid']]['lead']))
                {
                    $lead_response_dataTable[$keyValue['clientid']]['lead'] = array();
                }
                if($keyValue['deliveryMethod'] == 'normal' && ! empty($email_user_delivery_array[$keyName]))
                {
                    $lead_response_dataTable[$keyValue['clientid']]['lead'] = array_merge($lead_response_dataTable[$keyValue['clientid']]['lead'], $email_user_delivery_array[$keyName]);
                }
                else if($keyValue['deliveryMethod'] == 'porting' && ! empty($porting_user_delivery_array[$keyName]))
                {
                    $lead_response_dataTable[$keyValue['clientid']]['lead'] = array_merge($lead_response_dataTable[$keyValue['clientid']]['lead'], $porting_user_delivery_array[$keyName]);
                }

            }
            else if($keyValue['type'] == 'response')
            {
                if( empty($lead_response_dataTable[$keyValue['clientid']]['response']))
                {
                    $lead_response_dataTable[$keyValue['clientid']]['response'] = array();
                }
                if($keyValue['deliveryMethod'] == 'normal' && ! empty($email_user_delivery_array[$keyName]))
                {
                    $lead_response_dataTable[$keyValue['clientid']]['response'] = array_merge($lead_response_dataTable[$keyValue['clientid']]['response'], $email_user_delivery_array[$keyName]);
                }
                else if($keyValue['deliveryMethod'] == 'porting' && ! empty($porting_user_delivery_array[$keyName]))
                {
                    $lead_response_dataTable[$keyValue['clientid']]['response'] = array_merge($lead_response_dataTable[$keyValue['clientid']]['response'], $porting_user_delivery_array[$keyName]);
                }
            }
            //
        }
        unset($email_user_delivery_array);
        unset($porting_user_delivery_array);
        $i=0;
        //view start
        $flag = $listing_type == 'institute' ? 'national' : 'studyabroad';
        if( ! empty($clientId))
        {
            $view_lead_user_delivery = $cdmismodel->DeliveryByView($clientId,$startDate,$endDate,1,$flag);
            $view_response_user_delivery = $cdmismodel->DeliveryByView($clientId,$startDate,$endDate,0,$flag);
        }
        foreach ($view_lead_user_delivery as $key => $value) {
            if(empty($lead_response_dataTable[$value['clientid']]['lead']))
                $lead_response_dataTable[$value['clientid']]['lead'] = array($value['userid']);
            else
                array_push($lead_response_dataTable[$value['clientid']]['lead'], $value['userid']);
        }
        foreach ($view_response_user_delivery as $key => $value) {
            if(empty($lead_response_dataTable[$value['clientid']]['response']))
                $lead_response_dataTable[$value['clientid']]['response'] = array($value['userid']);
            else
                array_push($lead_response_dataTable[$value['clientid']]['response'], $value['userid']);
        }

        //view end
            foreach ($lead_response_dataTable as $key => $value) {
                $lead_response_dataTable[$key]['lead']= count(array_unique($lead_response_dataTable[$key]['lead']));
                $lead_response_dataTable[$key]['response'] = count(array_unique($lead_response_dataTable[$key]['response']));
                //$lead_response_dataTable[$key]['sales'] =0 ;
                //$lead_response_dataTable[$key]['collections'] =0 ;
            }

        /*
        foreach ($salesResult as $key => $value) {
            $client_sale_mapping[$value['ClientUserId']] = $value['price'];
        }*/
        $transaction_collection = $cdmismodel->getTransactionId_Paid($startDate,$endDate);
        $transaction_collection_mapping = array();
        $transactionIdArray = array();
        $i = 0;
        foreach ($transaction_collection as $key => $value) {
            $transaction_collection_mapping[$value['transactionId']] = $value['payment'];
            $transactionIdArray[$i++] = $value['transactionId'];
        }
        if( ! empty($transactionIdArray))
        {
            $client_transaction = $cdmismodel->getClientIdForTransactionId($transactionIdArray);
        }        
        $client_collection_mapping = array();
        foreach ($client_transaction as $key => $value) {
            $client_collection_mapping[$value['ClientUserId']] += $transaction_collection_mapping[ltrim($value['TransactionId'], '0')];
        }
        foreach ($client_collection_mapping as $key => $value) {
            if(array_key_exists($key, $lead_response_dataTable))
            {
                $lead_response_dataTable[$key]['collections'] = $value;
            }
            /*else
            {
                $lead_response_dataTable[$key]['collections'] = $value;
                $lead_response_dataTable[$key]['sales'] = 0;
                $lead_response_dataTable[$key]['lead'] = 0;
                $lead_response_delivery[$key]['response'] = 0;
            }*/
        }
    return $this->prepareDataTableForActualDelivery($clientId_institute_mapping,$lead_response_dataTable,$client_sale_mapping,$client_collection_mapping);


    }
    function prepareDataTableForActualDelivery($clientId_institute_mapping,$lead_response_dataTable,$client_sale_mapping,$client_collection_mapping)
    {
        $totalSales =0;
       $dataTableHeading = "Actual Delivery";
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:20px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:20px">S.NO</th>'.
                            '<th style="padding-left:20px">Client</th>'.
                            '<th style="padding-left:20px">Sales(Rupees)</th>'.
                            '<th style="padding-left:20px">Collections(Rupees)</th>'.
                            '<th style="padding-left:20px">Delivery Lead &nbsp / &nbspResponses</th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $i=1;
            foreach ($lead_response_dataTable as $key => $value) 
             {
              
                /*if( !array_key_exists($key, $responseDelivery) && !array_key_exists($key, $leadDelivery))
                    continue;
                if( !array_key_exists($key, $responseDelivery))
                        $responseDelivery[$key] = 0;

                if( !array_key_exists($key, $leadDelivery))
                        $leadDelivery[$key] = 0;*/
                if( ! array_key_exists($key, $clientId_institute_mapping))
                    continue;
                    $dataTable = $dataTable.
                        '<tr class="even pointer">'.
                            '<td class="a-center ">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</td>'.
                            '<td class=" ">'.$i.'</td>'.
                            '<td class=" ">'.addslashes($clientId_institute_mapping[$key]).'</td>'.
                            '<td class=" ">'.number_format($value['sales'],2,'.','').'</td>'.
                            '<td class=" ">'.number_format($value['collections'],2,'.','').'</td>'.
                            '<td class=" ">'.$value['lead'].'&nbsp / &nbsp'.$value['response'].'</td>'.
                        '</tr>';
                    //$prepareDataForCSV[$i++] = array($value['Id'],$value['Name'],$value['Email'],$value['MobileNumber']);
                        $i++;
                        $totalSales += $value['lead'];
            }
        $dataTable = $dataTable.'</tbody>';
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
        unset($lead_response_dataTable);
        unset($client_collection_mapping);
        unset($client_sale_mapping);
        unset($clientId_institute_mapping);
        error_log('========================='.$totalSales);
        return $DataForDataTable;
    }
    function getLeadResponseForClients($clientId,$startDate,$endDate,$flag = 'national')
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        /*$viewed_user_array = $cdmismodel->getUserIdDeliveryByView($clientId,$startDate,$endDate);
        $view_client_user = array();
        foreach ($viewed_user_array as $key => $value) {
            $userIdArray[$i++] = $value['userid'];
        }
        array_unique($userIdArray);*/
        $view_lead_user_delivery = $cdmismodel->DeliveryByViewForTopTile($clientId,$startDate,$endDate,1,$flag);
        $view_response_user_delivery = $cdmismodel->DeliveryByViewForTopTile($clientId,$startDate,$endDate,0,$flag);
        $no_of_leads = $this->getLeadForClients($clientId,$startDate,$endDate,$view_lead_user_delivery[0]['count']);
        $no_of_responses = $this->getResponseForClients($clientId,$startDate,$endDate,$view_response_user_delivery[0]['count']);
        $result['leadCount'] = $no_of_leads;
        $result['responsesCount'] = $no_of_responses;
        return $result;

    }
    function getLeadForClients($clientId,$startDate,$endDate,$view_lead_user_delivery)
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        if( ! empty($clientId))
            $leadGenies = $cdmismodel->getLeadGenies($clientId);
        $email_genies_array = array();
        $porting_genies_array = array();
        $i = 0;
        foreach ($leadGenies as $key => $value) {
            if($value['deliveryMethod'] == 'normal')
            {
                $email_genies_array[$i++] = $value['searchagentid'];
            }
            else if($value['deliveryMethod'] == 'porting')
            {
                $porting_genies_array[$j++] = $value['searchagentid'];
            }
            
        }
        if( ! empty($email_genies_array))
            $email_user_delivery = $cdmismodel->getDeliveryByEmailGenies($email_genies_array,$startDate,$endDate);
        if( ! empty($porting_genies_array))
            $porting_genies_array = $cdmismodel->getDeliveryByPortingGenies($porting_genies_array,$startDate,$endDate);
        /*$userIdArray = array();
        $i = 0;
        foreach ($email_user_delivery as $key => $value) {
            $userIdArray[$i++] = $value['userid'];
        }
        foreach ($porting_genies_array as $key => $value) {
            $userIdArray[$i++] = $value['userid'];   
        }
        foreach ($view_lead_user_delivery as $key => $value) {
            $userIdArray[$i++] = $value['userid'];
        }*/
        $number_of_leads = $email_user_delivery[0]['count']+$porting_genies_array[0]['count']+$view_lead_user_delivery;//count(array_unique($userIdArray));
        return $number_of_leads;

    }
    function getResponseForClients($clientId,$startDate,$endDate,$view_response_user_delivery)
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        if( ! empty($clientId))
            $responseGenies = $cdmismodel->getResponseGenies($clientId);
        $email_genies_array = array();
        $porting_genies_array = array();
        $i = 0;
        foreach ($responseGenies as $key => $value) {
            if($value['deliveryMethod'] == 'normal')
            {
                $email_genies_array[$i++] = $value['searchagentid'];
            }
            else if($value['deliveryMethod'] == 'porting')
            {
                $porting_genies_array[$j++] = $value['searchagentid'];
            }
            
        }
        if( ! empty($email_genies_array))
            $email_user_delivery = $cdmismodel->getDeliveryByEmailGenies($email_genies_array,$startDate,$endDate);
        if( ! empty($porting_genies_array))
            $porting_genies_array = $cdmismodel->getDeliveryByPortingGenies($porting_genies_array,$startDate,$endDate);
        /*$userIdArray = array();
        $i = 0;
        foreach ($email_user_delivery as $key => $value) {
            $userIdArray[$i++] = $value['userid'];
        }
        foreach ($porting_genies_array as $key => $value) {
            $userIdArray[$i++] = $value['userid'];   
        }
        foreach ($view_repsonse_user_delivery as $key => $value) {
            $userIdArray[$i++] = $value['userid'];
        }*/
        $number_of_responses = $email_user_delivery[0]['count']+$porting_genies_array[0]['count']+$view_response_user_delivery;//count(array_unique($userIdArray));   
        return $number_of_responses;
    }
    function getRegistrationDataSourceWise($courseId = array(),$source = '',$paidType='',$startDate ='',$endDate = '',$isStudyAbroad ='no',$subCategoryId=array(),$cityId = array(),$countryId = array())
    {
        $sourceWise = array();
        if( ! empty($courseId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');   
            $result = $cdmismodel->getRegistrationPieChartData($courseId,$source,$paidType,$startDate,$endDate,'sourceWise',$isStudyAbroad);
        }
        elseif(! empty($subCategoryId) || (! empty($countryId) && $isStudyAbroad == 'yes'))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad,1);
            $keyIds = array();
            $desktopKeys = array();
            $mobileKeys = array();
            $i =0 ;
            $keyDeviceMapping = array();
            foreach ($trackingKeyIds as $key => $value) {
                    //$keyDeviceMapping[$value['id']] = $value['siteSource'];
                    $keyIds[$i++] = $value['id'];
            }
            if( ! empty($subCategoryId) && $isStudyAbroad == 'no')
                   $result = $cdmismodel->getRegistrationPieChartDataBySubcat($subCategoryId,$cityId,$keyIds,$paidType,$startDate,$endDate,'sourceWise');
            else if($isStudyAbroad == 'yes' && (!empty($subCategoryId) || !empty($countryId)))
                $result = $cdmismodel->getRegistrationPieChartDataForAbroadBySubcat($subCategoryId,$countryId,$keyIds,$paidType,$startDate,$endDate,'sourceWise');
        }
            $sessionId = array();
            $i = 0;
            foreach ($result as $key => $value) {
                $sessionId[$i++] = $value['sessionId'];
                $resultArray[$value['sessionId']] = $value['responsescount'];
            }
            if( ! empty($sessionId))
                $sessionResult = $cdmismodel->getSourceForSessionId($sessionId);
            foreach ($sessionResult as $key => $value) {
                $sessionResultArray[$value['sessionId']] = $value['source'];
            }
            foreach ($resultArray as $key => $value) {
                if( empty($sessionResultArray[$key]))
                    $sessionResultArray[$key] = 'undefined';
                $sourceWise[$i++] = array('sourceWise' => $sessionResultArray[$key],'responsescount' => $value);
            }
        return $sourceWise;
    }
    function getRegistrationDataDeviceWise($courseId = array(),$source ='',$paidType='',$startDate='',$endDate ='',$isStudyAbroad ='no',$subCategoryId=array(),$cityId = array(),$countryId = array())
    {
        $deviceWiseResult = array();
        if( ! empty($courseId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getRegistrationPieChartData($courseId,$source,$paidType,$startDate,$endDate,'deviceWise',$isStudyAbroad);
            $deviceWiseResult = array();
            $i = 0;
            foreach ($result as $key => $value) {
                $deviceWiseResult[$i++] = array('siteSource'=>$value['siteSource'],'responsescount'=>$value['responsescount']);
            }
        }
        elseif(! empty($subCategoryId) || ( !empty($countryId) && $isStudyAbroad == 'yes'))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad,1,'yes');
            $keyIds = array();
            $desktopKeys = array();
            $mobileKeys = array();
            $i =0 ;
            $keyDeviceMapping = array();
            foreach ($trackingKeyIds as $key => $value) {
                    $keyDeviceMapping[$value['id']] = $value['siteSource'];
                    $keyIds[$i++] = $value['id'];
            }
            if( ! empty($subCategoryId) && $isStudyAbroad == 'no')
                $result = $cdmismodel->getRegistrationPieChartDataBySubcat($subCategoryId,$cityId,$keyIds,$paidType,$startDate,$endDate,'deviceWise');
            else if( $isStudyAbroad == 'yes' && ( ! empty($subCategoryId) || ! empty($countryId)))
                $result = $cdmismodel->getRegistrationPieChartDataForAbroadBySubcat($subCategoryId,$countryId,$keyIds,$paidType,$startDate,$endDate,'deviceWise');

            $deviceWiseResult = array();
            $i = 0;
            foreach ($result as $key => $value) {
                $deviceWiseResult[$i++] = array('siteSource'=>$keyDeviceMapping[$value['tracking_keyid']],'responsescount'=>$value['responsescount']);
            }
        }
        return $deviceWiseResult;
    }
    function getRegistrationDataPaidFreeWise($courseId=array(),$subCategoryId=array(),$cityId=array(),$countryId=array(),$source='',$paidType='',$startDate='',$endDate='',$isStudyAbroad='no')
    {
        $paidFreeWiseResult = array();
        if( ! empty($courseId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getRegistrationPieChartData($courseId,$source,$paidType,$startDate,$endDate,'paidFreeWise',$isStudyAbroad);
        }
        elseif( !empty($subCategoryId) && $isStudyAbroad == 'no')
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad,1);
            $keyIds = array();
            $i =0 ;
            $keyDeviceMapping = array();
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $result = $cdmismodel->getRegistrationPieChartDataBySubcat($subCategoryId,$cityId,$keyIds,$paidType,$startDate,$endDate,'paidFreeWise');
        }
        elseif( $isStudyAbroad == 'yes' && (!empty($subCategoryId) || !empty($countryId)))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad,1);
            $keyIds = array();
            $i =0 ;
            $keyDeviceMapping = array();
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $result = $cdmismodel->getRegistrationPieChartDataForAbroadBySubcat($subCategoryId,$countryId,$keyIds,$paidType,$startDate,$endDate,'paidFreeWise');
        }
        $paidFreeWiseResult = array();
        $i = 0;
        foreach ($result as $key => $value) {
            $paidFreeWiseResult[$i++] = array('pivotName'=>$value['pivotName'],'responsescount'=>$value['responsescount']);
        }
        return $paidFreeWiseResult;
    }
    function getAvgPagePerSessionForCustomerDelivery($instituteId = array(),$courseId = array(),$source = '',$startDate = '',$endDate ='',$viewWise = 1,$isStudyAbroad ='no',$topTile = 1)
    {
        $engagementLib = $this->CI->load->library('trackingMIS/engagementLib');
        if( ! empty($instituteId) && ! empty($courseId))
        {
            $extraData['CD']['instituteId'] = $instituteId;
            $extraData['CD']['courseId'] = $courseId;
        }
        if($isStudyAbroad == 'yes')
        {
            $extraData['CD']['isStudyAbroad'] = 'yes';
        }
        $extraData['CD']['deviceType'] =strtolower($source);
        switch ($viewWise) {
                case '1':
                        $viewWise = 'day';
                        break;
                case '2':
                        $viewWise = 'week';
                        break;
                case '3':
                        $viewWise = 'month';
                        break;
                default:
                        $viewWise = 'day';
                        break;
        }
        $extraData['CD']['viewWise'] = $viewWise;
        $dateRangeArray['startDate'] = $startDate;
        $dateRangeArray['endDate'] = $endDate;
        $avgPagePerSessionResult = $engagementLib->getAvgPagePerSessionForCustomerDelivery($dateRangeArray,$extraData);
        if($topTile == 1)
        {
            $sessions = $avgPagePerSessionResult['hits']['total'];
            $totalPageviews = $avgPagePerSessionResult['aggregations']['totalPageViews']['value'];
            return $totalPageviews / $sessions;
        }
        else
        {
            $result = $splitData['aggregations']['dateWise']['buckets'];
                        foreach ($result as $key => $value) {
                            $deviceWise = $value['deviceWise']['buckets'];
                            $desktop_pageview = 0;
                            $mobile_pageview = 0;
                            foreach ($deviceWise as $key => $deviceValue) {
                                if($deviceValue['key'] == 'no')
                                {
                                    $desktop_pageview = $deviceValue['pageViewWise']['value'];
                                }
                                else if($deviceValue['key'] =='yes')
                                {
                                    $mobile_pageview = $deviceValue['pageViewWise']['value'];
                                }
                                }
                                $engagementResult[$splitKey][$i++] = array(
                                    "responseDate" => date("Y-m-d", strtotime($value['key_as_string'])),
                                    "responsescount" => number_format(($mobile_pageview + $desktop_pageview) / $value['doc_count'],2,'.','')
                                    );
                            }
        }
    }
    function getRegistrationsDataForToptile($courseId = array(),$source = '',$paidType='',$startDate = '',$endDate='',$isStudyAbroad = 'no',$subCategoryId = array(),$cityId = array(),$countryId = array())
    {
        $result = 0;
        if( ! empty($courseId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getRegistrationsDataForToptile($courseId,$source,$paidType,$startDate,$endDate,$isStudyAbroad);
        }
        else if( ! empty($subCategoryId) || ! empty($countryId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad,1);
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            if( $isStudyAbroad == 'no')
                $result = $cdmismodel->getRegistrationCountForTopTileBySubcat($subCategoryId,$cityId,$keyIds,$paidType,$startDate,$endDate);
            else if($isStudyAbroad == 'yes')
                $result = $cdmismodel->getRegistrationDataForStudyAbroadBySubcat($subCategoryId,$countryId,$keyIds,$paidType,$startDate,$endDate);

        }
        return $result?$result: 0;
        
    }
    function getQuestionsCountForTopTile($courseId = array(),$source = '',$startDate ='',$endDate = '',$subCategoryId =array(),$cityId = array(),$isStudyAbroad='no')
    {
        if( ! empty($courseId))        
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getQuestionsCountForTopTile($courseId,$source,$startDate,$endDate);
        }
        else if( ! empty($subCategoryId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad,0,'no','questionPost');
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $result = $cdmismodel->getQuestionsCountForTopTileBySubcat($subCategoryId,$cityId,$keyIds,$startDate,$endDate);
        }
        return $result?$result: 0;
    }
    function getAnswersCountForTopTile($courseId = array(),$source = '',$startDate = '',$endDate ='',$subCategoryId= array(),$cityId = array(),$isStudyAbroad='no')
    {
        if( ! empty($courseId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getAnswersCountForTopTile($courseId,$source,$startDate,$endDate);

        }
        else if( ! empty($subCategoryId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad,0,'no','answerPost');
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $result = $cdmismodel->getAnswersCountForTopTileBySubcat($subCategoryId,$cityId,$keyIds,$startDate,$endDate);
        }
        return $result?$result:0;
    }
    function getDigupCountForTopTile($courseId = array(),$source = '',$startDate = '',$endDate ='',$subCategoryId = array(),$cityId = array(),$isStudyAbroad='no')
    {
        if( ! empty($courseId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getDigupCountForTopTile($courseId,$source,$startDate,$endDate);
        }
        else if( ! empty($subCategoryId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad,0,'no','thumbUpAnswer');
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $result = $cdmismodel->getDigupCountForTopTileBySubcat($subCategoryId,$cityId,$keyIds,$startDate,$endDate);
        }
        return $result?$result:0;
    }
    function getResponseCountForTopTile($courseId = array(),$source = '',$paidType = '',$startDate = '',$endDate ='',$subCategoryId = array(),$cityId = array(),$countryId = array(),$isStudyAbroad = 'no')
    {
        if( ! empty($courseId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getResponseCountForTopTile($courseId,$source,$paidType,$startDate,$endDate);
        }
        else if(! empty($subCategoryId) || ! empty($countryId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad);
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            if($isStudyAbroad == 'no')
                $result = $cdmismodel->getResponseCountForTopTileBySubcat($subCategoryId,$cityId,$keyIds,$paidType,$startDate,$endDate);
            elseif($isStudyAbroad == 'yes')
                $result = $cdmismodel->getResponseDataForStudyAbroadBySubcat($subCategoryId,$countryId,$keyIds,$paidType,$startDate,$endDate);
        }
        return $result?$result:0;
    }
    function getResponseDataSourceWise1($courseId = array(),$source ='',$paidType ='',$startDate = '',$endDate ='')
    {
        $sourceWise = array();
        if( ! empty($courseId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getResponseDataSourceWise1($courseId,$source,$paidType,$startDate,$endDate);
            $sessionId = array();
            $i = 0;
            foreach ($result as $key => $value) {
                $sessionId[$i++] = $value['sessionId'];
                $resultArray[$value['sessionId']] = $value['responsescount'];
            }
            if( ! empty($sessionId))
                $sessionResult = $cdmismodel->getSourceForSessionId($sessionId);
            foreach ($sessionResult as $key => $value) {
                $sessionResultArray[$value['sessionId']] = $value['source'];
            }
            foreach ($resultArray as $key => $value) {
                if($sessionResultArray[$key] == 'mailer')
                {
                    $sourceWise[$i++] = array('sourceWise' => 'Push','responsescount' => $value);    
                }
                else
                {
                    $sourceWise[$i++] = array('sourceWise' => 'Organic','responsescount' => $value);
                }
            }
        }
        return $sourceWise;
    }
    function getResponseDataPaidFreeWise($subCategoryId = array(),$cityId = array(),$countryId =array(),$source='',$paidType='',$startDate='',$endDate='',$isStudyAbroad='no')
    {
        $paidFreeWise = array();
        if( ! empty($subCategoryId) && $isStudyAbroad == 'no')
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad);
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $result = $cdmismodel->getResponseDataSourceWiseBySubcat($subCategoryId,$cityId,$keyIds,$paidType,$startDate,$endDate,'paidFree');
        }
        else if ($isStudyAbroad == 'yes' && (! empty($subCategoryId) || ! empty($countryId)))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad);
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $result = $cdmismodel->getResponsePieChartDataForAbroadBySubcat($subCategoryId,$countryId,$keyIds,$paidType,$startDate,$endDate,'paidFree');
        }
        $i = 0;
        foreach ($result as $key => $value) {
            $paidFreeWise[$i++] = array('type'=>$value['paidFree'],'responsescount'=>$value['responsescount']);
        }
        return $paidFreeWise;
    }
    function getResponseDataSourceWise($courseId = array(),$source ='',$paidType ='',$startDate = '',$endDate ='',$subCategoryId=array(),$cityId = array(),$countryId = array(),$isStudyAbroad ='no')
    {
        $sourceWise = array();
        if( ! empty($courseId))
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $result = $cdmismodel->getResponseDataSourceWise($courseId,$source,$paidType,$startDate,$endDate);
        }
        else if(! empty($subCategoryId) && $isStudyAbroad == 'no')
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad);
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $result = $cdmismodel->getResponseDataSourceWiseBySubcat($subCategoryId,$cityId,$keyIds,$paidType,$startDate,$endDate);
        }
        elseif ($isStudyAbroad == 'yes' && (!empty($subCategoryId) || !empty($countryId))) {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad);
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $result = $cdmismodel->getResponsePieChartDataForAbroadBySubcat($subCategoryId,$countryId,$keyIds,$paidType,$startDate,$endDate);
        }
            $pushArray = array('CD','MOB_Client','Client');
            $i = 0;
            foreach ($result as $key => $value) {
                if(in_array($value['action'], $pushArray))
                {
                    $sourceWise[$i++] = array('sourceWise'=>'Push','responsescount' => $value['responsescount']);
                }
                else
                    $sourceWise[$i++] = array('sourceWise'=>'Organic','responsescount' => $value['responsescount']);
            }
        return $sourceWise;
    }
    function getLeadDeliveryCountForTopTile($clientId,$startDate,$endDate,$flag='national')
    {
        $userId = array();
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        if( ! empty($clientId))
            $lead_genies = $cdmismodel->getLeadGenies($clientId);
        $lead_normal_genies = array();
        $lead_porting_genies = array();
        $i = 0; $j =0;
        foreach ($lead_genies as $key => $value) {
            if($value['deliveryMethod'] == 'normal')
            {
                $lead_normal_genies[$i++] = $value['searchagentid'];
            }
            else if($value['deliveryMethod'] == 'porting')
            {
                $lead_porting_genies[$j++] = $value['searchagentid'];
            }
        }
        if( ! empty($lead_normal_genies))
            $lead_normal_delivery = $cdmismodel->getDeliveryDataByEmailGeniesForTopTile($lead_normal_genies,$startDate,$endDate);
        $i = 0;
        foreach ($lead_normal_delivery as $key => $value) {
            $userId[$i++] = $value['userid'];
        }

        if( ! empty($lead_porting_genies))
            $lead_porting_delivery = $cdmismodel->getDeliveryDataByPortingGeniesForTopTile($lead_porting_genies,$startDate,$endDate);
        foreach ($lead_porting_delivery as $key => $value) {
            $userId[$i++] = $value['userid'];
        }
        if( ! empty($clientId))
            $view_lead_delivery = $cdmismodel->getViewDeliveryForTopTile($clientId,$startDate,$endDate,1,$flag);
        foreach ($view_lead_delivery as $key => $value) {
            $userId[$i++] = $value['userid'];
        }
        return count(array_unique($userId));
    }
    function getResponseDeliveryCountForTopTile($clientId,$startDate,$endDate,$flag='national')
    {
        $userId = array();
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        if( ! empty($clientId))
            $response_genies = $cdmismodel->getResponseGenies($clientId);
        $response_normal_genies = array();
        $response_porting_genies = array();
        $i = 0; $j =0;
        foreach ($response_genies as $key => $value) {
            if($value['deliveryMethod'] == 'normal')
            {
                $response_normal_genies[$i++] = $value['searchagentid'];
            }
            else if($value['deliveryMethod'] == 'porting')
            {
                $response_porting_genies[$j++] = $value['searchagentid'];
            }
        }
        if( ! empty($response_normal_genies))
            $response_normal_delivery = $cdmismodel->getDeliveryDataByEmailGeniesForTopTile($response_normal_genies,$startDate,$endDate);
        $i = 0;
        foreach ($response_normal_delivery as $key => $value) {
            $userId[$i++] = $value['userid'];
        }
        if( ! empty($response_porting_genies))
            $response_porting_delivery = $cdmismodel->getDeliveryDataByPortingGeniesForTopTile($response_porting_genies,$startDate,$endDate);
        foreach ($response_porting_delivery as $key => $value) {
            $userId[$i++] = $value['userid'];
        }
        if( ! empty($clientId))
            $view_response_delivery = $cdmismodel->getViewDeliveryForTopTile($clientId,$startDate,$endDate,0,$flag);
        foreach ($view_response_delivery as $key => $value) {
            $userId[$i++] = $value['userid'];
        }
        return count(array_unique($userId));
    }
    function getResponsesCourseWise($courseId = array(),$source ='',$paidType ='',$startDate ='',$endDate='',$isStudyAbroad ='no',$subCategoryId = array(),$cityId = array(),$countryId=array())
    {
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        if(( ! empty($courseId) || ! empty($subCategoryId) || (!empty($countryId) && $isStudyAbroad == 'yes')) && $paidType != 'free')
        {
            if( ! empty($subCategoryId) && $isStudyAbroad == 'no')
            {
                $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad);
                $keyIds = array();
                $i =0 ;
                foreach ($trackingKeyIds as $key => $value) {
                    $keyIds[$i++] = $value['id'];
                }
                $result = $cdmismodel->getResponseDataSourceWiseBySubcat($subCategoryId,$cityId,$keyIds,'paid',$startDate,$endDate,'courseWiseResponse');
                $uniqueResult = $cdmismodel->getResponseDataSourceWiseBySubcat($subCategoryId,$cityId,$keyIds,'paid',$startDate,$endDate,'uniqResponsesCourseWise');
            }
            elseif($isStudyAbroad == 'yes' && (!empty($subCategoryId) || !empty($countryId)))
            {
                $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad);
                $keyIds = array();
                $i =0 ;
                foreach ($trackingKeyIds as $key => $value) {
                    $keyIds[$i++] = $value['id'];
                }
                $result = $cdmismodel->getResponsePieChartDataForAbroadBySubcat($subCategoryId,$countryId,$keyIds,'paid',$startDate,$endDate,'courseWiseResponse');
                $uniqueResult = $cdmismodel->getResponsePieChartDataForAbroadBySubcat($subCategoryId,$countryId,$keyIds,'paid',$startDate,$endDate,'uniqResponsesCourseWise');
            }
            else if( ! empty($courseId))
            {
                $result = $cdmismodel->getResponseDataSourceWise($courseId,$source,'paid',$startDate,$endDate,'courseWiseResponse');
                $uniqueResult = $cdmismodel->getResponseDataSourceWise($courseId,$source,'paid',$startDate,$endDate,'uniqResponsesCourseWise');
            }
        }
        if( ! empty($courseId))
        {
            if($isStudyAbroad == 'no')
                $courseNameArray = $cdmismodel->getCourseName($courseId);
            else if($isStudyAbroad == 'yes')
                $courseNameArray = $cdmismodel->getStudyAbroadCourseName($courseId);
        }

        $courseId_Name_mapping = array();
        $instituteId = array();
        foreach ($courseNameArray as $key => $value) {
            $courseId_Name_mapping[$value['courseId']] = array('courseName'=>$value['courseName'],'instituteId'=>$value['instituteId']);
            $instituteId[$i++] = $value['instituteId'];
        }
        $instituteId = array_unique($instituteId);
        if( ! empty($instituteId))
        {
            if($isStudyAbroad == 'no')
                $instituteNameArray = $cdmismodel->getInstituteNames($instituteId,$type);
            else if($isStudyAbroad == 'yes')
                $instituteNameArray = $cdmismodel->getUniversityName($instituteId);
        }
        $instituteNameMapping = array();
        foreach ($instituteNameArray as $key => $value) {
            $instituteNameMapping[$value['instituteId']] = $value['instituteName'];
        }
        $courseWiseDelivery = array();
        $i = 0;
        $courseDeliveryMapping = array();
        foreach ($result as $key => $value) {
            $courseDeliveryMapping[$value['courseId']] = $value['responsescount'];
        }
        $courseUniqueDelivery = array();
        foreach ($uniqueResult as $uniqueKey => $uniqueValue) {
            $courseUniqueDelivery[$uniqueValue['courseId']] = $uniqueValue['count'];
        }
        foreach ($courseId_Name_mapping as $courseKey => $courseValue) {
            $courseWiseDelivery[$i++] = array('course'=>$courseValue['courseName'],'institute'=>$instituteNameMapping[$courseValue['instituteId']],'responses'=>$courseDeliveryMapping[$courseKey]?$courseDeliveryMapping[$courseKey]:0,'unique'=>$courseUniqueDelivery[$courseKey]?$courseUniqueDelivery[$courseKey]:0);
        }
        return $this->prepareDataTableForCourseResponseDelivery($courseWiseDelivery);
    }
    function prepareDataTableForCourseResponseDelivery($courseWiseDelivery)
    {
        $dataTableHeading = "Responses Delivery Data Course Wise";
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:20px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:20px">course Name</th>'.
                            '<th style="padding-left:20px">Institute Name</th>'.
                            '<th style="padding-left:20px">Total Responses</th>'.
                            '<th style="padding-left:20px;width:100px">Unique Responses</th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $i=0;
            foreach ($courseWiseDelivery as $key => $value) 
            {
                    $dataTable = $dataTable.
                        '<tr class="even pointer">'.
                            '<td class="a-center ">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</td>'.
                            '<td class=" ">'.$value['course'].'</td>'.
                            '<td class=" ">'.$value['institute'].'</td>'.
                            '<td class=" ">'.$value['responses'].'</td>'.
                            '<td class=" ">'.$value['unique'].'</td>'.
                        '</tr>';
                    //$prepareDataForCSV[$i++] = array($value['Id'],$value['Name'],$value['Email'],$value['MobileNumber']);
                        $i++;
                if($i == 300)
                    break;
            }
        $dataTable = $dataTable.'</tbody>';
        unset($courseWiseDelivery);
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);

        return $DataForDataTable;        
    }
    function getResponseDeliveryInstituteWise($instituteId = array(),$source ='',$paidType = '',$startDate ='',$endDate ='',$isStudyAbroad='no',$subCategoryId= array(),$cityId = array(),$countryId=array())
    {
        //
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        if( (! empty($subCategoryId) && $isStudyAbroad=='no' ) && $paidType != 'free')
        {
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad);
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $result = $cdmismodel->getResponseDataSourceWiseBySubcat($subCategoryId,$cityId,$keyIds,'paid',$startDate,$endDate,'instituteWiseResponse');
            $uniqueResult = $cdmismodel->getResponseDataSourceWiseBySubcat($subCategoryId,$cityId,$keyIds,'paid',$startDate,$endDate,'uniqResponseInstituteWise');
        }
        elseif($isStudyAbroad == 'yes' && (!empty($subCategoryId) || !empty($countryId)) && $paidType != 'free')
        {
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad);
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $result = $cdmismodel->getResponsePieChartDataForAbroadBySubcat($subCategoryId,$countryId,$keyIds,'paid',$startDate,$endDate,'instituteWiseResponse');
            $uniqueResult = $cdmismodel->getResponsePieChartDataForAbroadBySubcat($subCategoryId,$countryId,$keyIds,'paid',$startDate,$endDate,'uniqResponseInstituteWise');
        }
        else if( ! empty($instituteId) && $paidType != 'free')
        {
            $result = $cdmismodel->getResponseInstituteWise($instituteId,$source,$startDate,$endDate,$isStudyAbroad);
            $uniqueResult = $cdmismodel->getResponseInstituteWise($instituteId,$source,$startDate,$endDate,$isStudyAbroad,'uniqResponse');
        }
        /*if($isStudyAbroad == 'no')
            $courseIdArray = $cdmismodel->getInstituteBasedOnCourseId($courseId);
        else if($isStudyAbroad == 'yes')
            $courseIdArray = $cdmismodel->getUniversityBasedOnCourse($courseId);*/
        //$courseId_Name_mapping = array();
        //$instituteId = array();
        $i = 0;
        /*foreach ($courseIdArray as $key => $value) {
            $courseId_Name_mapping[$value['courseId']] = $value['instituteId'];
            $instituteId[$i++] = $value['instituteId'];
        }*/
        $instituteId = array_unique($instituteId);
        if( ! empty($instituteId))
        {
            if($isStudyAbroad == 'no')
                $instituteNameArray = $cdmismodel->getInstituteNames($instituteId,$type);
            else if($isStudyAbroad == 'yes')
                $instituteNameArray = $cdmismodel->getUniversityName($instituteId);
        }
        $instituteNameMapping = array();
        foreach ($instituteNameArray as $key => $value) {
            $instituteNameMapping[$value['instituteId']] = $value['instituteName'];
        }
        $courseDeliveryMapping = array();
        foreach ($result as $key => $value) {
            $courseDeliveryMapping[$value['instituteId']] = $value['responsescount'];
        }
        $courseUniqueDelivery = array();
        foreach ($uniqueResult as $uniqueKey => $uniqueValue) {
            $courseUniqueDelivery[$uniqueValue['instituteId']] = $uniqueValue['count'];
        }
        $instituteWiseDelivery = array();
        $i = 0;
        foreach ($instituteNameMapping as $instituteKey => $instituteValue) {
            $instituteWiseDelivery[$i++] = array('institute'=>$instituteValue,'responses'=>$courseDeliveryMapping[$instituteKey]?$courseDeliveryMapping[$instituteKey]:0,'unique'=>$courseUniqueDelivery[$instituteKey]?$courseUniqueDelivery[$instituteKey]:0);
        }
        //
        return $this->prepareDataTableForResponseDeliveryInstituteWise($instituteWiseDelivery);
    }
    function prepareDataTableForResponseDeliveryInstituteWise($instituteWiseDelivery)
    {
        $dataTableHeading = "Responses Delivery Data Institute Wise";
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:20px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:20px">Institute Name</th>'.
                            '<th style="padding-left:20px">Total Responses</th>'.
                            '<th style="padding-left:20px;width:100px">Unique Responses</th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $i=0;
            foreach ($instituteWiseDelivery as $key => $value) 
             {
                    $dataTable = $dataTable.
                        '<tr class="even pointer">'.
                            '<td class="a-center ">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</td>'.
                            '<td class=" ">'.$value['institute'].'</td>'.
                            '<td class=" ">'.$value['responses'].'</td>'.
                            '<td class=" ">'.$value['unique'].'</td>'.
                        '</tr>';
                    //$prepareDataForCSV[$i++] = array($value['Id'],$value['Name'],$value['Email'],$value['MobileNumber']);
                $i++;
                if($i == 300)
                    break;
            }
            unset($instituteWiseDelivery);
        $dataTable = $dataTable.'</tbody>';
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
        return $DataForDataTable;        
    }
    function getNationalRegistrationDataSourceWise($result)
    {
        $sessionId = array();
        $resultArray = array();
        $i = 0;
        $sessionResultArray = array();
        foreach ($result as $key => $value) {
            $sessionId[$i++] = $value['sessionId'];
            $sessionResultArray[$value['sessionId']] = $value['ResponseCount'];
        }
        //$sourceWise = $this->getRegistraionDataSourceWise($sessionResultArray,$sessionId);
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        if( ! empty($sessionId))
        {
            $sourceResult =$cdmismodel->getSourceForSessionId($sessionId);
        }
        $sourceSessionMapping = array();
        foreach ($sourceResult as $key => $value) {
            $sourceSessionMapping[$value['sessionId']] = $value['source'];
        }
        $sourceWiseResult = array();
        $i = 0;
        foreach ($sessionResultArray as $key => $value) {
            if( empty($sourceSessionMapping[$key]))
                $sourceSessionMapping[$key] = 'Other';
            $sourceWiseResult[ $sourceSessionMapping[ $key ] ] += $value;
        }
        foreach ($sourceWiseResult as $key => $value) {
            $sourceWiseSingleSplit = new stdClass();
            $sourceWiseSingleSplit->Pivot = $key;
            $sourceWiseSingleSplit->ResponseCount = $value;
            $resultArray[] = $sourceWiseSingleSplit;
        }
        return $resultArray;
        //$utmWise = $this->getRegistrationDataByUTMwise($sessionResultArray,$sessionId);

    }
    function getNationalRegistrationDataByUTMwise($result)
    {
        $sessionId = array();
        $i = 0;
        $sessionResultArray = array();
        foreach ($result as $key => $value) {
            $sessionId[$i++] = $value['sessionId'];
            $sessionResultArray[$value['sessionId']] = $value['ResponseCount'];
        }

        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        $utmResult = array();
        if( ! empty($sessionId))
            $utmWiseResult = $cdmismodel->getCampaignForSessionId($sessionId);
        $utmSessionMapping = array();
        foreach ($utmWiseResult as $key => $value) {
            $utmSessionMapping[$value['sessionId']] = $value['campaignName'];
        }
        foreach ($utmSessionMapping as $key => $value) {
            $utmResult[$value] += $sessionResultArray[$key];
        }
        $resultArray = array();
        foreach ($utmResult as $key => $value) {
            $utmWiseSplit = new stdClass();
            $utmWiseSplit->Pivot = $key;
            $utmWiseSplit->ResponseCount = $value;
            $resultArray[]= $utmWiseSplit;
        }
        return $resultArray;
        
    }
    function getLeadDeliveryInstituteWise($clientId,$startDate ='',$endDate = '',$listing_type = 'institute' )
    {
        //
        $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
        if( ! empty($clientId))
            $clientIdResult = $cdmismodel->getClientId_InstituteNames($clientId,$listing_type);
        $clientId_institute_mapping = array();
        $i = 0;
        foreach ($clientIdResult as $key => $value) {
            $clientId_institute_mapping[$value['username']] = $value['instituteName'];
        }
        if(! empty($clientId))
            $mappingResult = $cdmismodel->getLeadGenies($clientId);
        $porting_genies_array = array();
        $email_genies_array = array();
        $i = 0;
        $j = 0;
        $agentId_clientId_mapping = array();
        foreach ($mappingResult as $keyName => $keyValue) {
            if($keyValue['deliveryMethod'] == 'normal')
            {
                $email_genies_array[$i++] = $keyValue['searchagentid'];
            }
            else if($keyValue['deliveryMethod'] == 'porting')
            {
                $porting_genies_array[$j++] = $keyValue['searchagentid'];
            }
            $agentId_clientId_mapping[$keyValue['searchagentid']] = array('clientid' => $keyValue['clientid'],
                                                                      'deliveryMethod' => $keyValue['deliveryMethod']);
        }
        if( ! empty($email_genies_array))
            $email_user_delivery =$cdmismodel->getDeliveryDataByEmailGenies($email_genies_array,$startDate,$endDate);
        $email_user_delivery_array = array();
        $i = 0;
        foreach ($email_user_delivery as $key => $value) {
            if( array_key_exists($value['agentid'], $email_user_delivery_array))
            {
                  array_push($email_user_delivery_array[$value['agentid']],$value['userid']);
            }
            else
            {
                $email_user_delivery_array[$value['agentid']] = array($value['userid']);
            }
        }
        if( ! empty($porting_genies_array))
        {
            $porting_user_delivery = $cdmismodel->getDeliveryDataByPortingGenies($porting_genies_array,$startDate,$endDate);
        }
        $porting_user_delivery_array = array();
        foreach ($porting_user_delivery as $key => $value) {
            if( array_key_exists($value['agentid'], $porting_user_delivery_array))
            {
                array_push($porting_user_delivery_array[$value['agentid']],$value['userid']);
            }
            else
            {
                $porting_user_delivery_array[$value['agentid']] = array($value['userid']);
            }
        }
        $lead_response_dataTable = array();
        foreach ($agentId_clientId_mapping as $keyName => $keyValue) {
                if( empty($lead_response_dataTable[$keyValue['clientid']]['lead']))
                {
                    $lead_response_dataTable[$keyValue['clientid']]['lead'] = array();
                }
                if($keyValue['deliveryMethod'] == 'normal' && ! empty($email_user_delivery_array[$keyName]))
                {
                    $lead_response_dataTable[$keyValue['clientid']]['lead'] = array_merge($lead_response_dataTable[$keyValue['clientid']]['lead'], $email_user_delivery_array[$keyName]);
                }
                else if($keyValue['deliveryMethod'] == 'porting' && ! empty($porting_user_delivery_array[$keyName]))
                {
                    $lead_response_dataTable[$keyValue['clientid']]['lead'] = array_merge($lead_response_dataTable[$keyValue['clientid']]['lead'], $porting_user_delivery_array[$keyName]);
                }

            }
            //
        unset($email_user_delivery_array);
        unset($porting_user_delivery_array);
        $i=0;
        //view start
        $flag = $listing_type == 'institute' ? 'national' : 'studyabroad';
        if( ! empty($clientId))
        {
            $view_lead_user_delivery = $cdmismodel->DeliveryByView($clientId,$startDate,$endDate,1,$flag);
            //$view_response_user_delivery = $cdmismodel->DeliveryByView($clientId,$startDate,$endDate,0,$flag);
        }
        foreach ($view_lead_user_delivery as $key => $value) {
            if(empty($lead_response_dataTable[$value['clientid']]['lead']))
                $lead_response_dataTable[$value['clientid']]['lead'] = array($value['userid']);
            else
                array_push($lead_response_dataTable[$value['clientid']]['lead'], $value['userid']);
        }
        //view end
            foreach ($lead_response_dataTable as $key => $value) {
                $lead_response_dataTable[$key]['lead']= count(array_unique($lead_response_dataTable[$key]['lead']));
            }
            if(! empty($clientId))
            {
                $userArray = $cdmismodel->getUserName($clientId);
                $userNameArray = array();
                foreach ($userArray as $key => $value) {
                    $userNameArray[$value['Id']] = $value['firstName'].' '.$value['lastName'];
                }
            }
            if( ! empty($clientId))
            {
                $courseCountArray = $cdmismodel->getCourseCountUnderClient($clientId);
                $courseCount = array();
                foreach ($courseCountArray as $key => $value) {
                    $courseCount[$value['username']] = $value['count'];
                }
            }
        //
        return $this->prepareDataTableForLeadDelivery($clientId_institute_mapping,$lead_response_dataTable,$userNameArray,$courseCount);
    }
    function prepareDataTableForLeadDelivery($clientId_institute_mapping,$lead_response_dataTable,$userNameArray,$courseCount)
    {
        //
        $dataTableHeading = "Lead Delivery Client Wise";
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:10px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:10px">S.NO</th>'.
                            '<th style="padding-left:20px">Client User Name</th>'.
                            '<th style="padding-left:20px">Institutes</th>'.
                            '<th style="padding-left:20px">Number of Courses</th>'.
                            '<th style="padding-left:20px">Number of Leads Delivered</th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $i=1;
            foreach ($clientId_institute_mapping as $key => $value) 
             {
              
                /*if( !array_key_exists($key, $responseDelivery) && !array_key_exists($key, $leadDelivery))
                    continue;
                if( !array_key_exists($key, $responseDelivery))
                        $responseDelivery[$key] = 0;

                if( !array_key_exists($key, $leadDelivery))
                        $leadDelivery[$key] = 0;*/
                /*if( ! array_key_exists($key, $clientId_institute_mapping))
                    continue;*/
                    $splitName = strlen($value) > 100 ? substr($value, 0, 96) . ' ...' : $value;
                    if(!isset($lead_response_dataTable[$key]['lead']))
                    {
                        $lead_response_dataTable[$key]['lead'] = 0;
                    }
                    $dataTable = $dataTable.
                        '<tr class="even pointer">'.
                            '<td class="a-center ">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</td>'.
                            '<td class=" ">'.$i.'</td>'.
                            '<td class=" ">'.$userNameArray[$key].'</td>'.
                            '<td class=" " title="'.addslashes($value).'">'.addslashes($splitName).'</td>'.
                            '<td class=" " >'.$courseCount[$key].'</td>'.
                            '<td class=" ">'.$lead_response_dataTable[$key]['lead'].'</td>'.
                        '</tr>';
                    //$prepareDataForCSV[$i++] = array($value['Id'],$value['Name'],$value['Email'],$value['MobileNumber']);
                        $i++;
            }
        $dataTable = $dataTable.'</tbody>';
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
        unset($lead_response_dataTable);
        unset($client_collection_mapping);
        unset($client_sale_mapping);
        unset($clientId_institute_mapping);
        return $DataForDataTable;
    }
    function getAvgResponseForPaidCourse($subCategoryId= array(),$cityId = array(),$countryId = array(),$source = '',$paidType = '',$startDate ='',$endDate='',$isStudyAbroad ='no')
    {
        $avgResponse = 0;
        $courseCount = 1;
        $paidResponses = 0;
        if( ! empty($subCategoryId) && $paidType != 'free' && $isStudyAbroad == 'no')
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad);
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $courseCount = $cdmismodel->getPaidCourseCount($subCategoryId,$cityId,$startDate,$endDate);
            $paidResponses = $cdmismodel->getPaidResponseCountBySubcat($subCategoryId,$cityId,$stateId,$keyIds,'paid',$startDate,$endDate);
        }
        else if ( $isStudyAbroad == 'yes' && (!empty($subCategoryId) || !empty($countryId)) && $paidType != 'free')
        {
            $cdmismodel = $this->CI->load->model('trackingMIS/cdmismodel');
            $trackingKeyIds = $cdmismodel->getTrackingKeyIdBasedOnPage($source,$isStudyAbroad);
            $keyIds = array();
            $i =0 ;
            foreach ($trackingKeyIds as $key => $value) {
                $keyIds[$i++] = $value['id'];
            }
            $courseCount = $cdmismodel->getPaidCourseCountForStudyAbroad($subCategoryId,$countryId,$startDate,$endDate);
            $paidResponses = $cdmismodel->getResponseDataForStudyAbroadBySubcat($subCategoryId,$countryId,$keyIds,'paid',$startDate,$endDate);
        }
        return number_format(($paidResponses / $courseCount),2,'.','');
    }
}
?>

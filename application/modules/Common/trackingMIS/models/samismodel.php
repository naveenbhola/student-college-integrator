<?php
class samismodel extends MY_Model
{
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct()
	{ 
		parent::__construct('MISTracking');
        $this->abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
	}
	
	private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}

    function getSessionIdsForRegistration($dateRange,$filterArray,$trackingIds,$userIds=''){   
        $this->initiateModel();
        $this->dbHandle->select('DISTINCT(visitorsessionid)');
        $this->dbHandle->from('tusersourceInfo');

        $this->dbHandle->where('date(time) >=', $dateRange['startDate']);
        $this->dbHandle->where('date(time) <=', $dateRange['endDate']);
        $this->dbHandle->where_in('tracking_keyid',$trackingIds);
        $this->dbHandle->where('visitorsessionid is not NULL');
        if(count($userIds)>0){
            $this->dbHandle->where_in('userid',$userIds);    
        }
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getPaidRegistrations($sessionIds,$dateRange){
        $this->initiateModel();
        $this->dbHandle->select('utm_campaign, count(1) as count');
        $this->dbHandle->from('session_tracking');

        $this->dbHandle->where_in('sessionId',$sessionIds);
        $startDate =  date('Y-m-d',strtotime($dateRange['startDate']."-1 days"));
        $this->dbHandle->where('date(startTime) >=', $startDate);
        $this->dbHandle->where('date(startTime) <=', $dateRange['endDate']);
        $this->dbHandle->where('source', 'paid');
        $this->dbHandle->where('utm_campaign is not NULL');
        $this->dbHandle->group_by('utm_campaign');

        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        return $result;
    }
    
    function getSessionIds($trackingIdsForSessions,$filterArray,$tableFilter,$responseType='',$filteredCourseIdsArray=''){
        //_p($trackingIdsForSessions);_p($filterArray);_p($tableFilter);_p($responseType);_p($filteredCourseIdsArray);die;
        $this->initiateModel();
        $this->dbHandle->select('visitorsessionid,count(distinct id) as count');
        switch ($tableFilter) {
            case 'responses':
                $this->dbHandle->from('tempLMSTable');
                $this->dbHandle->where('date(submit_date) >=', $filterArray['startDate']);
                $this->dbHandle->where('date(submit_date) <=', $filterArray['endDate']);
                if($filteredCourseIdsArray){
                    $this->dbHandle->where_in('listing_type_id',$filteredCourseIdsArray);    
                }
                break;

            case 'registrations':
                $this->dbHandle->from('tusersourceInfo');
                $this->dbHandle->where('date(time) >=', $filterArray['startDate']);
                $this->dbHandle->where('date(time) <=', $filterArray['endDate']);
                if($filteredCourseIdsArray){
                    $this->dbHandle->where_in('userId',$filteredCourseIdsArray);    
                }
                break;
            default:
                # code...
                break;
        }

        if($tableFilter == 'compare'){
            $this->dbHandle->where_in('trackingId', $trackingIdsForSessions);
        }else{
            $this->dbHandle->where_in('tracking_keyid', $trackingIdsForSessions);    
        }
        if($responseType == 'paid' || $responseType == 'free')
        {
            $this->dbHandle->where('listing_subscription_type',$responseType);
        }
        $this->dbHandle->group_by('visitorsessionid');
        //echo $this->dbHandle->_compile_select();die;
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //echo _p($result);die;
        //error_log('qry  tracking = '.$this->dbHandle->last_query());
        return $result;
    }

    function getCampaignForSessionId($sessionId)
    {
        $dbHandle = $this->getReadHandle();
        $sessionIds = '"'.implode('", "', $sessionId).'"';
        $sql = "SELECT utm_source as campaignName,sessionId FROM session_tracking WHERE sessionId IN (".$sessionIds.")  AND utm_source is not null";
        $result = $dbHandle->query($sql)->result_array();
        return $result;
    }

    function getSourceForSessionId($sessionId = array(),$columnFilter='source',$defaultView='')
    {
        $this->initiateModel();
        switch ($columnFilter) {
            case 'source':
                $this->dbHandle->select('sessionId,source as value');
                break;
            
            case 'utmSource':
                $this->dbHandle->select('sessionId,utm_source as value');
                break;

            case 'utmCampaign':
                $this->dbHandle->select('sessionId,utm_campaign as value');
                break;

            case 'utmMedium':
                $this->dbHandle->select('sessionId,utm_medium as value');
                break;

            default:
                $this->dbHandle->select('sessionId,source as value');
                break;
        }
        //_p($selectFields);die;
        //_p($sessionId);die;
        $this->dbHandle->from('session_tracking');
        if($defaultView){
            $this->dbHandle->where('source',$defaultView);
        }
        $this->dbHandle->where_in('sessionId',$sessionId);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getTrackingIds($pageName='',$filter='')
    {
        $this->initiateModel();
        $this->dbHandle->select('id');
        $this->dbHandle->FROM('tracking_pagekey');

        if(is_array($pageName)){
            $this->dbHandle->where_in('page',$pageName);
        }else if($pageName){
            $this->dbHandle->where('page',$pageName);
        }

        switch ($filter) {
            case 'rmc':
                $this->dbHandle->where('keyName','rateMyChance');
                break;
            
            case 'paid':
            case 'free':
                $conversionType = array('response','Course shortlist');
                $this->dbHandle->where_in('conversionType',$conversionType);
                break;

            case 'downloads':
                $this->dbHandle->where('conversionType','downloadGuide');
                break;

            case 'CPEnquiry':
                $this->dbHandle->where('keyName','consultantProfileEnquiries');
                break;

            case 'COMREP':
                $this->dbHandle->where('widget','commentWidget');
                break;

            case 'compare':
                if($pageName){
                    $this->dbHandle->where('conversionType' ,"compare");
                }else{
                    $this->dbHandle->where('((conversionType ="compare") OR (page="compareCoursesPage" and conversionType IN ("response","Course shortlist"))) ','',false);
                }
                break;
            case 'exam_upload' :
            {
                $this->dbHandle->where('conversionType' ,"profileEvaluationCall");
            }
        }

        $this->dbHandle->where('site', 'Study Abroad');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        foreach ($result as $key => $value) {
            $trackingIds[] = $value['id'];
        }
        //_p($trackingIds);die;
        //error_log('qry  trackingIDs = '.$this->dbHandle->last_query());
        return $trackingIds;
    }

    function getTrackingIdsForSelectedPage($pageName='',$filter='')
    {
        $this->initiateModel();
        if($pageName){
            switch ($filter) {
                case 'rmc':
                case 'paid':
                case 'free':
                    $this->dbHandle->select('id,keyName as type,widget,siteSource');
                    break;

                case 'downloads':
                    $this->dbHandle->select('id,widget,siteSource');
                    break;

                case 'commentReply':
                    $this->dbHandle->select('id,conversionType,siteSource');
                    break;

                case 'compare':
                case 'courseCompared':
                    $this->dbHandle->select('id,widget,siteSource,page');
                    break;

                case 'registration':
                    $this->dbHandle->select('id');
                    break;

                default:
                    $this->dbHandle->select('id,keyName as type,widget,siteSource');
                    break;
            }
        }else{
            switch ($filter) {
                case 'rmc':
                case 'paid':
                case 'free':
                    $this->dbHandle->select('id,keyName as type,widget,siteSource,page');
                    break;

                case 'downloads':
                case 'CPEnquiry':
                    $this->dbHandle->select('id,widget,siteSource,page');
                    break; 

                case 'commentReply':
                    $this->dbHandle->select('id,conversionType,siteSource,page');
                    break;

                case 'compare':
                case 'courseCompared':
                    $this->dbHandle->select('id,widget,siteSource,conversionType,page');
                    break;

                case 'registration':
                    $this->dbHandle->select('id');
                    break;

                default:
                    $this->dbHandle->select('id,keyName as type,widget,siteSource,page');
                    break;    
            }
        }
        
        $this->dbHandle->FROM('tracking_pagekey');

        if(is_array($pageName)){
            $this->dbHandle->where_in('page',$pageName);
        }else if($pageName){
            $this->dbHandle->where('page',$pageName);
        }
        $conversionType = array('response','Course shortlist');

        switch ($filter) {
            case 'rmc':
                $this->dbHandle->where('keyName','rateMyChance');
                break;
            
            case 'paid':
            case 'free':
                $this->dbHandle->where_in('conversionType',$conversionType);
                break;

            case 'downloads':
                $this->dbHandle->where('conversionType','downloadGuide');
                break;

            case 'CPEnquiry':
                $this->dbHandle->where('keyName','consultantProfileEnquiries');
                break;

            case 'COMREP':
                $this->dbHandle->where('widget','commentWidget');
                break;

            case 'courseCompared':
                $this->dbHandle->where('keyName' ,"finalCourseCompare");
                break;

            case 'compare':
                if($pageName){
                    $this->dbHandle->where('conversionType' ,"compare");
                }else{
                    $this->dbHandle->where('((conversionType ="compare") OR (page="compareCoursesPage" and conversionType IN ("response","Course shortlist"))) ','',false);
                }
                break;
            case 'exam_upload' :
            {
                $this->dbHandle->where('conversionType' ,"profileEvaluationCall");
            }
        }

        $this->dbHandle->where('site', 'Study Abroad');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        //error_log('qry  trackingIDs = '.$this->dbHandle->last_query());
        return $result;
    }

    function getDistinctSessionIdForSessionCountForComapre($pageName,$trackingIdsArray,$filterArray,$filteredCourseIdsArray){

        $this->initiateModel();        
        $this->dbHandle->select('count(distinct sessionId)');
        $this->dbHandle->from('abroadUserComparedCourses');
        
        if(count($filteredCourseIdsArray) > 0){
            $this->dbHandle->where_in('courseId', $filteredCourseIdsArray);            
        }

        $this->dbHandle->where_in('trackingId', $trackingIdsArray);
        $this->dbHandle->where('userId <=', 0);
        $this->dbHandle->where('status !=', 'history');
        $this->dbHandle->where('date(addedOn) >=', $filterArray['startDate']);
        $this->dbHandle->where('date(addedOn) <=', $filterArray['endDate']);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return reset(reset($result));
        //error_log('qry chart = '.$this->dbHandle->last_query());
    }
    
    function getCompareDataForSelectedDuration($pageArray,$trackingIdsArray,$filterArray,$filteredCourseIdsArray='')    
    {
        $this->initiateModel();
        if($filterArray['view'] == 1)
        {
            $res = '';    
        }else if($filterArray['view'] == 2)
        {
            $res = ', week(date(addedOn),1) as week';
        }else if($filterArray['view'] == 3)
        {
            $res = ', month(addedOn) as month';    
        }
        
        $this->dbHandle->select('userId,courseId, trackingId as tracking_keyid,status,date(addedOn) as responseDate  '.$res, false);

        $this->dbHandle->from('abroadUserComparedCourses');
        
        if($filteredCourseIdsArray)
        {
            $this->dbHandle->where_in('courseId', $filteredCourseIdsArray);            
        }

        $this->dbHandle->where_in('trackingId', $trackingIdsArray);

        $this->dbHandle->where('date(addedOn) >=', $filterArray['startDate']);
        $this->dbHandle->where('date(addedOn) <=', $filterArray['endDate']);
        $this->dbHandle->where('status !=','history');

        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        //error_log('qry chart = '.$this->dbHandle->last_query());
        return $result;
    }

    function getFinallyCompareDataForSelectedDuration($pageArray,$trackingIdsArray,$filterArray,$filteredCourseIdsArray='')    
    {
        $this->initiateModel();
        if($filterArray['view'] == 1)
        {
            $res = '';    
        }else if($filterArray['view'] == 2)
        {
            $res = ', week(addedOnDate,1) as week';
        }else if($filterArray['view'] == 3)
        {
            $res = ', month(addedOnDate) as month';    
        }
        
        $this->dbHandle->select('userId,courseId, trackingId as tracking_keyid,addedOnDate as responseDate  '.$res, false);

        $this->dbHandle->from('userFinallyComparedCourses');
        
        if($filteredCourseIdsArray)
        {
            $this->dbHandle->where_in('courseId', $filteredCourseIdsArray);            
        }

        $this->dbHandle->where_in('trackingId', $trackingIdsArray);

        $this->dbHandle->where('addedOnDate >=', $filterArray['startDate']);
        $this->dbHandle->where('addedOnDate <=', $filterArray['endDate']);

        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        //error_log('qry chart = '.$this->dbHandle->last_query());
        return $result;
    }

    function getAvgCompareCoursesINSession($pageName,$trackingIdsArray,$filterArray,$filteredCourseIdsArray){
        $this->initiateModel();        
        $this->dbHandle->select('count(distinct visitorsessionid)');
        $this->dbHandle->from('userFinallyComparedCourses');
        
        if(count($filteredCourseIdsArray) > 0){
            $this->dbHandle->where_in('courseId', $filteredCourseIdsArray);            
        }

        $this->dbHandle->where_in('trackingId', $trackingIdsArray);

        $this->dbHandle->where('addedOnDate >=', $filterArray['startDate']);
        $this->dbHandle->where('addedOnDate <=', $filterArray['endDate']);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return reset(reset($result));
        //error_log('qry chart = '.$this->dbHandle->last_query());
    }


    function getDistinctSessionIdForSessionCount($pageName,$trackingIdsArray,$filterArray,$filteredCourseIdsArray){
        $this->initiateModel();        
        $this->dbHandle->select('count(distinct visitorsessionid)');
        $this->dbHandle->from('userFinallyComparedCourses');
        
        if(count($filteredCourseIdsArray) > 0){
            $this->dbHandle->where_in('courseId', $filteredCourseIdsArray);            
        }

        $this->dbHandle->where_in('trackingId', $trackingIdsArray);
        $this->dbHandle->where('userId >', 0);
        $this->dbHandle->where('addedOnDate >=', $filterArray['startDate']);
        $this->dbHandle->where('addedOnDate <=', $filterArray['endDate']);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return reset(reset($result));
        //error_log('qry chart = '.$this->dbHandle->last_query());
    }

    function getCourseIdsForContent($filterArray)
    {

        $this->initiateModel();
        
        if($filterArray['category'] != "0"){
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }

            if(in_array($filterArray['category'],$ldbCourseIds))
            {
                $this->dbHandle->select('DISTINCT(saclm.content_id) as content_id');
                $this->dbHandle->from('sa_content_attribute_mapping saclm');
                $this->dbHandle->where('saclm.attribute_mapping','ldbcourse');
                if($filterArray['country'] != "0")
                {
                    $this->dbHandle->join('sa_content_attribute_mapping sacm','saclm.content_id = sacm.content_id and sacm.attribute_mapping ="country" ','inner');
                    $this->dbHandle->where('sacm.attribute_id',$filterArray['country']);
                    //$this->dbHandle->where('sacm.status','live');
                }
                $this->dbHandle->where('saclm.attribute_id',$filterArray['category']);
                //$this->dbHandle->where('saclm.status','live');
            }
            else
            {
                $this->dbHandle->select('DISTINCT(saccm.content_id) as content_id');
                $this->dbHandle->from('sa_content_course_mapping saccm');
                if($filterArray['country'] != "0")
                {
                    $this->dbHandle->join('sa_content_attribute_mapping sacm','saccm.content_id = sacm.content_id and sacm.attribute_mapping="country"','inner');
                    $this->dbHandle->where('sacm.attribute_id',$filterArray['country']);
                    //$this->dbHandle->where('sacm.status','live');
                }
                if($filterArray['courseLevel']!= '0'){
                    if($filterArray['courseLevel']!= ''){
                        $this->dbHandle->where('saccm.course_type',$filterArray['courseLevel']);
                    }
                }
                $this->dbHandle->where('saccm.parent_category_id',$filterArray['category']);
                //$this->dbHandle->where('saccm.status','live');
            } 
        }else if($filterArray['courseLevel']!= '0')
        {
            if($filterArray['courseLevel']!= ''){
                $this->dbHandle->select('DISTINCT(saccm.content_id) as content_id');
                $this->dbHandle->from('sa_content_course_mapping saccm');
                if($filterArray['country'] != "0")
                {
                    $this->dbHandle->join('sa_content_attribute_mapping sacm','saccm.content_id = sacm.content_id and sacm.attribute_mapping="country"','inner');
                    $this->dbHandle->where('sacm.attribute_id',$filterArray['country']);
                    //$this->dbHandle->where('sacm.status','live');
                }
                $this->dbHandle->where('saccm.course_type',$filterArray['courseLevel']);
                //$this->dbHandle->where('saccm.status','live');
            }
        }else if($filterArray['country'] != "0")
        {
            $this->dbHandle->select('DISTINCT(sacm.content_id) as content_id');
            $this->dbHandle->from('sa_content_attribute_mapping sacm');
            $this->dbHandle->where('sacm.attribute_mapping','country');
            $this->dbHandle->where('sacm.attribute_id',$filterArray['country']);
            //$this->dbHandle->where('sacm.status','live');
        }

        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //echo _p($result);die;
        return $result;        
    }

    function getCourseIdsBasedOnDifferentFilter($filterArray,$responseType='',$productIdsArray='')
    {
        $this->initiateModel();
        $this->dbHandle->select('DISTINCT(course_id)');
        $this->dbHandle->from('abroadCategoryPageData');
        //$this->dbHandle->where('status','live');
        if($filterArray['country'] != 0)
        {
            $this->dbHandle->where('country_id',$filterArray['country']);    
        }
        
        if($filterArray['category'] != 0)
        {
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }
            if(in_array($filterArray['category'],$ldbCourseIds))
            {
                $this->dbHandle->where('ldb_course_id',$filterArray['category']);   
            }
            else
            {
                $this->dbHandle->where('category_id',$filterArray['category']);     
                if($filterArray['courseLevel'] != '0')
                {
                    $this->dbHandle->where('course_level',$filterArray['courseLevel']);    
                }
            }
        }else if($filterArray['courseLevel'] != '0')
        {
            $this->dbHandle->where('course_level',$filterArray['courseLevel']);    
        }
        if($responseType == 'paid')
        {
            $this->dbHandle->where('pack_type',$productIdsArray);    
        }
        elseif($responseType == 'free')
        {
            $this->dbHandle->where('pack_type !=',$productIdsArray);
        } 

        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //echo _p($result);die;
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getCommentReplyDataForSelectedDuration($pageName,$trackingIdsArray,$filterArray,$responseType='',$filteredContentIdsArray='')
    {
        $this->initiateModel();
        if($filterArray['view'] == 1)
        {
            $res = '';    
        }else if($filterArray['view'] == 2)
        {
            $res = ', week(date(comment_time),1) as week';
        }else if($filterArray['view'] == 3)
        {
            $res = ', month(comment_time) as month';    
        }
        //_p($res);die;
        if($pageName == ''){
            $this->dbHandle->select('tracking_key_id as tracking_keyid, date(comment_time) as responseDate ,count(1) as reponsesCount'.$res, false);
        }
        else{
            $this->dbHandle->select('tracking_key_id as tracking_keyid,content_id as contentId,date(comment_time) as responseDate ,count(1) as reponsesCount'.$res, false);
        }

        $this->dbHandle->from('sa_comment_details');
        
        if($filteredContentIdsArray)
        {
            $this->dbHandle->where_in('content_id', $filteredContentIdsArray);            
        }

        $this->dbHandle->where_in('tracking_key_id', $trackingIdsArray);

        $this->dbHandle->where('date(comment_time) >=', $filterArray['startDate']);
        $this->dbHandle->where('date(comment_time) <=', $filterArray['endDate']);

        if($filterArray['view'] == 1)
        {
            $this->dbHandle->group_by('responseDate');    
        }else if ($filterArray['view'] == 2) {
            $this->dbHandle->group_by('week');    
        }else if ($filterArray['view'] == 3) {
            $this->dbHandle->group_by('month,responseDate');    
        }
        
        if($pageName)
        {
            $this->dbHandle->group_by('content_id');    
        }  
        $this->dbHandle->group_by('tracking_key_id');
        $this->dbHandle->order_by("responseDate", "asc");
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        //error_log('qry chart = '.$this->dbHandle->last_query());
        return $result;   
    }

    function getDownloadsDataForGuide($trackingIdsArray,$filterArray,$filteredCourseIdsArray='')
    {
        $this->initiateModel();
        if($filterArray['view'] == 1)
        {
            $res = '';    
        }else if($filterArray['view'] == 2)
        {
            $res = ', week(date(added_at),1) as week';
        }else if($filterArray['view'] == 3)
        {
            $res = ', month(added_at) as month';    
        }
       
        $this->dbHandle->select('tracking_key_id as tracking_keyid, date(added_at) as responseDate ,count(1) as reponsesCount'.$res, false);
        
        $this->dbHandle->from('sa_guide_download_tracking');
        
        if($filteredCourseIdsArray){
            $this->dbHandle->where_in('guide_id', $filteredCourseIdsArray);            
        }

        $this->dbHandle->where_in('tracking_key_id', $trackingIdsArray);

        $this->dbHandle->where('date(added_at) >=', $filterArray['startDate']);
        $this->dbHandle->where('date(added_at) <=', $filterArray['endDate']);

        if($filterArray['view'] == 1)
        {
            $this->dbHandle->group_by('responseDate');    
        }else if ($filterArray['view'] == 2) {
            $this->dbHandle->group_by('week');    
        }else if ($filterArray['view'] == 3) {
            $this->dbHandle->group_by('month,responseDate');    
        }
        
        $this->dbHandle->group_by('tracking_key_id');
        $this->dbHandle->order_by("responseDate", "asc");
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        //error_log('qry chart = '.$this->dbHandle->last_query());
        return $result;
    }

    function getDownloadsDataForApplyContent($trackingIdsArray,$filterArray,$filteredCourseIdsArray='')
    {
        $this->initiateModel();
        if($filterArray['view'] == 1)
        {
            $res = '';    
        }else if($filterArray['view'] == 2)
        {
            $res = ', week(date(downloadedAt),1) as week';
        }else if($filterArray['view'] == 3)
        {
            $res = ', month(downloadedAt) as month';    
        }
        
        $this->dbHandle->select('tracking_keyid, date(downloadedAt) as responseDate ,count(1) as reponsesCount'.$res, false);
        
        $this->dbHandle->from('applyContentDownloadTracking');
        
        if($filteredCourseIdsArray){
            $this->dbHandle->where_in('contentId', $filteredCourseIdsArray);            
        }

        $this->dbHandle->where_in('tracking_keyid', $trackingIdsArray);

        $this->dbHandle->where('date(downloadedAt) >=', $filterArray['startDate']);
        $this->dbHandle->where('date(downloadedAt) <=', $filterArray['endDate']);

        if($filterArray['view'] == 1)
        {
            $this->dbHandle->group_by('responseDate');    
        }else if ($filterArray['view'] == 2) {
            $this->dbHandle->group_by('week');    
        }else if ($filterArray['view'] == 3) {
            $this->dbHandle->group_by('month,responseDate');    
        }
        
        $this->dbHandle->group_by('tracking_keyid');
        $this->dbHandle->order_by("responseDate", "asc");
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        //error_log('qry chart = '.$this->dbHandle->last_query());
        return $result;
    }

    function CPEnquiryDataForSelectedDuration($trackingIdsArray,$filterArray)
    {
        $this->initiateModel();
        if($filterArray['view'] == 1)
        {
            $res = '';    
        }else if($filterArray['view'] == 2)
        {
            $res = ', week(date(submitTime),1) as week';
        }else if($filterArray['view'] == 3)
        {
            $res = ', month(submitTime) as month';    
        }
        $this->dbHandle->select('consultantId,tempLmsId ,source,tracking_keyid, date(submitTime) as responseDate '.$res, false);
        $this->dbHandle->from('consultantProfileEnquiries');
        
        $this->dbHandle->where('date(submitTime) >=', $filterArray['startDate']);
        $this->dbHandle->where('date(submitTime) <=', $filterArray['endDate']);
        $this->dbHandle->where_in('tracking_keyid', $trackingIdsArray);

        if($filterArray['consultantId'] != '0')
        {
            $this->dbHandle->where('consultantId',$filterArray['consultantId']);
        }
        if($filterArray['regionId'] != '0')
        {
            $this->dbHandle->where('regionId',$filterArray['regionId']);
        }

        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        //error_log('qry chart = '.$this->dbHandle->last_query());
        return $result;
    }

    function getResponsesDataForSelectedDuration($pageArray,$trackingIdsArray,$filterArray,$responseType='',$filteredCourseIdsArray='')
    {    
    	$this->initiateModel();
        if($filterArray['view'] == 1)
        {
            $res = '';    
        }else if($filterArray['view'] == 2)
        {
            $res = ', week(date(submit_date),1) as week';
        }else if($filterArray['view'] == 3)
        {
            $res = ', month(submit_date) as month';    
        }
        
        if($responseType == ''){
            $this->dbHandle->select('tracking_keyid,listing_subscription_type, date(submit_date) as responseDate ,count(1) as reponsesCount'.$res, false);
        }else if($responseType == 'rmc'){
            $this->dbHandle->select('tracking_keyid,listing_subscription_type, date(submit_date) as responseDate ,count(1) as reponsesCount'.$res, false);
        }
        else{
            $this->dbHandle->select('tracking_keyid,date(submit_date) as responseDate ,count(1) as reponsesCount'.$res, false);
        }

        $this->dbHandle->from('tempLMSTable');
        
        if($filteredCourseIdsArray)
        {
            $this->dbHandle->where_in('listing_type_id', $filteredCourseIdsArray);            
        }

        $this->dbHandle->where_in('tracking_keyid', $trackingIdsArray);

        if($responseType == 'paid' || $responseType == 'free')
        {
            $this->dbHandle->where('listing_subscription_type',$responseType);
        }

        $this->dbHandle->where('date(submit_date) >=', $filterArray['startDate']);
        $this->dbHandle->where('date(submit_date) <=', $filterArray['endDate']);

        if($filterArray['view'] == 1)
        {
            $this->dbHandle->group_by('responseDate');    
        }else if ($filterArray['view'] == 2) {
            $this->dbHandle->group_by('week');    
        }else if ($filterArray['view'] == 3) {
            $this->dbHandle->group_by('month,responseDate');    
        }
        
        $this->dbHandle->group_by('tracking_keyid');
        if(!$pageArray && $responseType !='rmc')
        {
            $this->dbHandle->group_by('listing_subscription_type');
        }
        if($responseType =='rmc')
        {
            $this->dbHandle->group_by('listing_subscription_type');
        }
        $this->dbHandle->order_by("responseDate", "asc");
        //echo $this->dbHandle->_compile_select();die;
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        //error_log('qry chart = '.$this->dbHandle->last_query());
        return $result;
    }

	function getDistinctUserCountForSelectedDuration($trackingIdsArray,$filterArray,$responseType,$filteredCourseIdsArray,$tableFilter='response')
    {
        $this->initiateModel();
        $this->dbHandle->select('DISTINCT(userId) as userId');

        switch ($tableFilter) {
            case 'response':
                $this->dbHandle->select('DISTINCT(userId) as userId');
                $this->dbHandle->FROM('tempLMSTable');
                $this->dbHandle->where('date(submit_date) >=', $filterArray['startDate']);
                $this->dbHandle->where('date(submit_date) <=', $filterArray['endDate']);
                if($filteredCourseIdsArray){
                    $this->dbHandle->where_in('listing_type_id',$filteredCourseIdsArray);    
                }
                break;

            case 'applyContent':
                $this->dbHandle->select('DISTINCT(userId) as userId');
                $this->dbHandle->FROM('applyContentDownloadTracking');
                $this->dbHandle->where('date(downloadedAt) >=', $filterArray['startDate']);
                $this->dbHandle->where('date(downloadedAt) <=', $filterArray['endDate']);
                if($filteredCourseIdsArray){
                    $this->dbHandle->where_in('contentId',$filteredCourseIdsArray);    
                }
                break;

            case 'guide':
                $this->dbHandle->select('DISTINCT(user_id) as userId');
                $this->dbHandle->FROM('sa_guide_download_tracking');
                $this->dbHandle->where('date(added_at) >=', $filterArray['startDate']);
                $this->dbHandle->where('date(added_at) <=', $filterArray['endDate']);
                if($filteredCourseIdsArray){
                    $this->dbHandle->where_in('guide_id',$filteredCourseIdsArray);    
                }
                break;

            case 'compare':
                $this->dbHandle->select('DISTINCT(userId) as userId');
                $this->dbHandle->FROM('abroadUserComparedCourses');
                $this->dbHandle->where('date(addedOn) >=', $filterArray['startDate']);
                $this->dbHandle->where('date(addedOn) <=', $filterArray['endDate']);
                if($filteredCourseIdsArray){
                    $this->dbHandle->where_in('courseId',$filteredCourseIdsArray);    
                }
                break;

            case 'exam_upload':
                $this->dbHandle->select('DISTINCT(userId) as userId');    
                $this->dbHandle->FROM('rmcStudentExamsDocuments');
                $this->dbHandle->where('date(addedOn) >=', $filterArray['dateRange']['startDate']);
                $this->dbHandle->where('date(addedOn) <=', $filterArray['dateRange']['endDate']);
                if($filterArray['abroadExamList'] && $filterArray['abroadExamList'] != 0){
                    $this->dbHandle->where('examId',$filterArray['abroadExamList']);
                }
                break;

            default:
                $this->dbHandle->select('DISTINCT(userId) as userId');
                $this->dbHandle->FROM('tempLMSTable');
                $this->dbHandle->where('date(submit_date) >=', $filterArray['startDate']);
                $this->dbHandle->where('date(submit_date) <=', $filterArray['endDate']);
                if($filteredCourseIdsArray){
                    $this->dbHandle->where_in('listing_type_id',$filteredCourseIdsArray);    
                }
                break;
        }

        if($tableFilter == 'compare'){
            $this->dbHandle->where_in('trackingId', $trackingIdsArray);
        }
        elseif ($tableFilter == 'exam_upload')
        {
            $this->dbHandle->where_in('trackingKeyId', $trackingIdsArray);
        }
        elseif ($tableFilter == 'guide')
        {
            $this->dbHandle->where_in('tracking_key_id', $trackingIdsArray);
        }
        else
        {
            $this->dbHandle->where_in('tracking_keyid', $trackingIdsArray);    
        }
          
        if($responseType == 'paid' || $responseType == 'free')
        {
            $this->dbHandle->where('listing_subscription_type',$responseType);
        }
        $result = $this->dbHandle->get()->result_array();
//        echo $this->dbHandle->last_query();die;
        //echo _p($result);die;
        //error_log('qry  tracking = '.$this->dbHandle->last_query());
        return $result;
    }

    function getResponsesByFirstTimeUser($distinctUserArray,$filterArray,$tableFilter='response')
    {
        if($distinctUserArray)
        {
            $this->initiateModel();
            
            switch ($tableFilter) {
                case 'response':
                    $this->dbHandle->select('distinct(userId)');
                    $this->dbHandle->FROM('tempLMSTable');
                    $this->dbHandle->where('date(submit_date) <',$filterArray['startDate']);
                    $this->dbHandle->where_in('userId',$distinctUserArray);
                    break;

                case 'applyContent':
                    $this->dbHandle->select('distinct(userId)');
                    $this->dbHandle->FROM('applyContentDownloadTracking');
                    $this->dbHandle->where('date(downloadedAt) <',$filterArray['startDate']);
                    $this->dbHandle->where_in('userId',$distinctUserArray);
                    break;

                case 'guide':
                    $this->dbHandle->select('distinct(user_id) as userId');
                    $this->dbHandle->FROM('sa_guide_download_tracking');
                    $this->dbHandle->where('date(added_at) <',$filterArray['startDate']);
                    $this->dbHandle->where_in('user_id',$distinctUserArray);
                    break;

                case 'compare':
                    $this->dbHandle->select('distinct(userId)');
                    $this->dbHandle->FROM('abroadUserComparedCourses');
                    $this->dbHandle->where('date(addedOn) <',$filterArray['startDate']);
                    $this->dbHandle->where_in('userId',$distinctUserArray);
                    break;
                case 'exam_upload':
                    $this->dbHandle->select('distinct(userId)');
                    $this->dbHandle->FROM('rmcStudentExamsDocuments');
                    $this->dbHandle->where('date(addedOn) <', $filterArray['dateRange']['startDate']);
                    $this->dbHandle->where_in('userId',$distinctUserArray);
                    if($filterArray['abroadExamList'] && $filterArray['abroadExamList'] != 0){
                        $this->dbHandle->where('examId',$filterArray['abroadExamList']);
                    }
                    break;

                default:
                    $this->dbHandle->select('distinct(userId)');
                    $this->dbHandle->FROM('tempLMSTable');
                    $this->dbHandle->where('date(submit_date) <',$filterArray['startDate']);
                    $this->dbHandle->where_in('userId',$distinctUserArray);
                    break;
            }
            //$this->dbHandle->group_by('userId');
            $result = $this->dbHandle->get()->result_array();
            //echo $this->dbHandle->last_query();die;
            //echo count($result);die;
            //echo _p($result);die;
            //error_log('qry  first = '.$this->dbHandle->last_query(), 3, '/home/praveen/Desktop/sqls');

            return $result;
        }
       return NULL;
    }

    function getCourseIdsForSelectedDuration($productIdsArray,$filterArray,$responseType,$filterUserIdsArray)
    {   
        $this->initiateModel();
        $this->dbHandle->select('DISTINCT(cshd.courseId) as courseId');
        $this->dbHandle->from('courseSubscriptionHistoricalDetails cshd');
        $this->dbHandle->join('abroadCategoryPageData acpd','cshd.courseId = acpd.course_id','inner');
        $this->dbHandle->where('cshd.addedOnDate <=',$filterArray['endDate']);
        $this->dbHandle->where('(cshd.endedOnDate >="'.$filterArray['startDate'].'" OR cshd.endedOnDate = "0000:00:00" ) ','',false);
        $this->dbHandle->where('cshd.source','abroad');
        if($responseType == 'paid')
        {
            $this->dbHandle->where('cshd.packType',$productIdsArray);    
        }
        elseif($responseType == 'free')
        {
            $this->dbHandle->where('cshd.packType !=',$productIdsArray);
        }
        //$this->dbHandle->where('acpd.status !=','deleted');

        if($filterArray['country'] != 0)
        {
            $this->dbHandle->where('acpd.country_id',$filterArray['country']);    
        }
        if($filterArray['category'] != 0)
        {
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }
            
            if(in_array($filterArray['category'],$ldbCourseIds))
            {
                $this->dbHandle->where('acpd.ldb_course_id',$filterArray['category']);   
            }
            else
            {
                $this->dbHandle->where('acpd.category_id',$filterArray['category']);     
                if($filterArray['courseLevel'] != '0'){
                    $this->dbHandle->where('acpd.course_level',$filterArray['courseLevel']);    
                }
            }
        }else if($filterArray['courseLevel'] != '0')
        {
            $this->dbHandle->where('acpd.course_level',$filterArray['courseLevel']);    
        }
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        return $result;
    }

    function getUniversityIds($courseIdsForSelectedDuration)
    {
        $this->initiateModel();
        $this->dbHandle->select('DISTINCT(university_id) as universityId');
        $this->dbHandle->from('abroadCategoryPageData');
        $this->dbHandle->where_in('course_id', $courseIdsForSelectedDuration);
        //$this->dbHandle->where('status','live');
        $result = $this->dbHandle->get()->result_array();
        //echo count($result);die;
        return $result;
    }
    

    function getRMCUniversityIdsForSelectedDuration($filterArray)
    {
        $this->initiateModel();
        $this->dbHandle->select('DISTINCT(universityId)');
        $this->dbHandle->from('rmcCounsellorUniversityMapping rcum');
        $this->dbHandle->join('abroadCategoryPageData acpd','acpd.university_id = rcum.universityId','inner');
        if($filterArray['country'] != "0")
        {
            $this->dbHandle->where('acpd.country_id',$filterArray['country']);    
        }

        if($filterArray['category'] != 0)
        {
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }
            if(in_array($filterArray['category'],$ldbCourseIds))
            {
                $this->dbHandle->where('acpd.ldb_course_id',$filterArray['category']);
            }
            else
            {
                $this->dbHandle->where('acpd.category_id',$filterArray['category']);
                if($filterArray['courseLevel'] != '0')
                {
                    $this->dbHandle->where('acpd.course_level',$filterArray['courseLevel']);
                }
            }
        }else if($filterArray['courseLevel'] != '0')
        {
            $this->dbHandle->where('acpd.course_level',$filterArray['courseLevel']);
        }

        //$this->dbHandle->where('rcum.status','live');
        $this->dbHandle->where('date(rcum.addedOn) <=',$filterArray['endDate']);
        $this->dbHandle->where('(rcum.status = "live" OR (rcum.status = "deleted" and rcum.modifiedOn >= '.'"'.$filterArray["startDate"].'"'.'))','',false);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        return $result;
    }

    function getRMCCourseIds($universityIdsArray,$filterArray)
    {
        $this->initiateModel();
        $this->dbHandle->select('DISTINCT(cad.courseId)');
        $this->dbHandle->from('abroadCategoryPageData as acpd');
        $this->dbHandle->join('courseApplicationDetails as cad','cad.courseId = acpd.course_id','inner');
        $this->dbHandle->where_in('acpd.university_id',$universityIdsArray);
        if($filterArray['country'] != "0")
        {
            $this->dbHandle->where('acpd.country_id',$filterArray['country']);
        }

        if($filterArray['category'] != 0)
        {
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }
            if(in_array($filterArray['category'],$ldbCourseIds))
            {
                $this->dbHandle->where('acpd.ldb_course_id',$filterArray['category']);
            }
            else
            {
                $this->dbHandle->where('acpd.category_id',$filterArray['category']);
                if($filterArray['courseLevel'] != '0')
                {
                    $this->dbHandle->where('acpd.course_level',$filterArray['courseLevel']);
                }
            }
        }else if($filterArray['courseLevel'] != '0')
        {
            $this->dbHandle->where('acpd.course_level',$filterArray['courseLevel']);
        }

        $this->dbHandle->where('date(cad.addedOn) <=',$filterArray['endDate']);
        $this->dbHandle->where('(cad.status = "live" OR (cad.status != "draft" and cad.modifiedOn >= "'.$filterArray['startDate'].'"))','',false);
        
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        return $result;   
    }

    function getConsultant()
    {   
        $this->initiateModel();
        $status = array('live','deleted');
        $this->dbHandle->select('distinct(consultantId),name,status');
        $this->dbHandle->from('consultant');
        $this->dbHandle->where_in('status',$status);
        $this->dbHandle->order_by('consultantId');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;    
    }

    function getCourseIdsFromTempLMSTable($tempLmsId)
    {
        $this->initiateModel();
        $this->dbHandle->select('id, listing_type_id');
        $this->dbHandle->from('tempLMSTable');
        $this->dbHandle->where_in('id',$tempLmsId);
        $this->dbHandle->order_by('listing_type_id');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        return $result;    
    }

    function getUniversityArray($courseIdsArray)
    {
        $this->initiateModel();
        $this->dbHandle->select('distinct(acpd.course_id),uni.name');  
        $this->dbHandle->from('abroadCategoryPageData acpd');
        $this->dbHandle->join('university uni','uni.university_id = acpd.university_id','inner');
        $this->dbHandle->where_in('acpd.course_id',$courseIdsArray);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        return $result;
    }

    function getUserIds($filterArray)
    {
        $this->initiateModel();
        $this->dbHandle->select('distinct(mobile) as mobile');
        $this->dbHandle->from('consultantProfileEnquiries');
        if($filterArray['consultantId'] !=0){
            $this->dbHandle->where('consultantId',$filterArray['consultantId']);    
        }
        if($filterArray['regionId'] !=0){
            $this->dbHandle->where('regionId',$filterArray['regionId']);    
        }
        $this->dbHandle->where('date(submitTime) >=', $filterArray['startDate']);
        $this->dbHandle->where('date(submitTime) <=', $filterArray['endDate']);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;                
        //_p($result);die;
        return $result;
    }

    function getResponsesByFirstTimeUserForConsultant($distinctUserArray,$filterArray)
    {
        if($distinctUserArray)
        {
            $this->initiateModel();
            $this->dbHandle->select('distinct(mobile) as mobile');
            $this->dbHandle->FROM('consultantProfileEnquiries');
            $this->dbHandle->where_in('mobile',$distinctUserArray);
            $this->dbHandle->where('date(submitTime) <',$filterArray['startDate']);
            //$this->dbHandle->group_by('userId');
            $result = $this->dbHandle->get()->result_array();
            //echo $this->dbHandle->last_query();die;
            //echo count($result);die;
            //echo _p($result);die;
            //error_log('qry  first = '.$this->dbHandle->last_query(), 3, '/home/praveen/Desktop/sqls');

            return $result;
        }
       return NULL;
    }

    function getClientConsultantSubscription($filterArray){
        $this->initiateModel();
        $this->dbHandle->select('distinct(consultantId) as consultantId,subscriptionId');
        $this->dbHandle->from('consultantClientSubscriptionDetail');
        $this->dbHandle->where('date(modifiedAt) <=',$filterArray['endDate']);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die; 
        //_p($result);die;
        return $result;   
    }

    function getPaidConsultant($consultantIds)
    {
        $this->initiateModel();
        $this->dbHandle->select('count(distinct universityId) univCount, count(distinct regionId) regionCount');
        $this->dbHandle->from('consultantRegionSubscription');
        $this->dbHandle->where_in('consultantId ',$consultantIds);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die; 
        //_p($result);die;
        return $result[0];
    }

    function getConsultantLocationRegions() {
        $this->initiateModel('read');
        $this->dbHandle->select('id,name as regionName');
        $this->dbHandle->from('consultantRegions');
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    function getOverviewpDataForSelectedDuration($filterArray)
    {
        //_p($filterArray);die;
        $this->initiateModel();
        if($filterArray['view'] == 1)
        {
            $res = '';    
        }else if($filterArray['view'] == 2)
        {
            $res = ', week(date) as week';    
        }else if($filterArray['view'] == 3)
        {
            $res = ', month(date) as month';    
        }
        
        //_p($res);die;
        $this->dbHandle->select('registrations,rmcResponses,CPEnquiries,paidResponses,freeResponses,comments,repliesOnComments,downloads,date as responseDate ,count(1) as reponsesCount'.$res, false);

        $this->dbHandle->from('SAMISOverviewData');
        $this->dbHandle->where('source', 'abroad');
        $this->dbHandle->where('date(date) >=', $filterArray['startDate']);
        $this->dbHandle->where('date(date) <=', $filterArray['endDate']);

        if($filterArray['view'] == 1)
        {
            $this->dbHandle->group_by('responseDate');    
        }else if ($filterArray['view'] == 2) {
            $this->dbHandle->group_by('week(responseDate)');    
        }else if ($filterArray['view'] == 3) {
            $this->dbHandle->group_by('month(responseDate)');    
        }
        $this->dbHandle->order_by("responseDate", "asc");
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        //error_log('qry chart = '.$this->dbHandle->last_query());
        return $result;
    }

    function getRegistrationsByDownloads($trackingIdsArray,$filterArray){
        $this->initiateModel();
        $this->dbHandle->select('count(id) as count');
        $this->dbHandle->from('registrationTracking');
        $this->dbHandle->where('isNewReg','yes');
        $this->dbHandle->where_in('trackingkeyId',$trackingIdsArray);
        $this->dbHandle->where('submitDate >=', $filterArray['startDate']);
        $this->dbHandle->where('submitDate <=', $filterArray['endDate']);
        $result =$this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //echo $result;die;
        return $result;
    }

    public function getCountForExamUpload($inputRequest,$trackingIds){
        $this->initiateModel();
        $this->dbHandle->select('count(id) as count');
        $this->dbHandle->from('rmcStudentExamsDocuments');
        $this->dbHandle->where_in('trackingKeyId',$trackingIds);
        if($inputRequest['abroadExamList'] && $inputRequest['abroadExamList'] != 0){
            $this->dbHandle->where('examId',$inputRequest['abroadExamList']);
        }
        $this->dbHandle->where('addedOn >=',$inputRequest['dateRange']['startDate'].' 00:00:00');
        $this->dbHandle->where('addedOn <=',$inputRequest['dateRange']['endDate'].' 23:59:59');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getTrackingIdsForAbroadExamUpload($inputRequest){
        $this->initiateModel();
        $this->dbHandle->select('id,siteSource,widget');
        $this->dbHandle->from('tracking_pagekey');
        if($inputRequest['sourceApplication'] && $inputRequest['sourceApplication'] != 'all'){
            $this->dbHandle->where('siteSource',ucfirst($inputRequest['sourceApplication']));
        }
        $this->dbHandle->where('conversionType','profileEvaluationCall');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getCountForAbroadExamUploadFieldWise($inputRequest,$fieldName)
    {
        $this->initiateModel();
        $select = 'count(1) as count';
        switch ($fieldName)
        {
            case 'sourceApplication' :
                $select .= ',tp.siteSource as fieldName';
                $this->dbHandle->group_by('tp.siteSource');
                break;
            case 'widget' :
                $select .= ',tp.widget as fieldName';
                $this->dbHandle->group_by('tp.widget');
                break;
            case 'sourceWidget' :
                $select .= ',tp.siteSource as source,tp.widget as widget';
                $this->dbHandle->group_by('tp.siteSource,tp.widget');
                break;
            default :
                return array();
        }
        $this->dbHandle->select($select);
        $this->dbHandle->from('rmcStudentExamsDocuments rsed');
        $this->dbHandle->join('tracking_pagekey tp','rsed.trackingKeyId = tp.id','inner');
        if($inputRequest['sourceApplication'] && $inputRequest['sourceApplication'] != 'all'){
            $this->dbHandle->where('tp.siteSource',ucfirst($inputRequest['sourceApplication']));
        }
        if($inputRequest['abroadExamList'] && $inputRequest['abroadExamList'] != 0){
            $this->dbHandle->where('rsed.examId',$inputRequest['abroadExamList']);
        }
        $this->dbHandle->where('tp.conversionType','profileEvaluationCall');
        $this->dbHandle->where('rsed.addedOn >=',$inputRequest['dateRange']['startDate'].' 00:00:00');
        $this->dbHandle->where('rsed.addedOn <=',$inputRequest['dateRange']['endDate'].' 23:59:59');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getStudyAbroadExamUploadTrands($inputRequest,$trackingIds,$fieldName){
        //_p($inputRequest);die;
        $this->initiateModel();
        if($inputRequest['view'] == 1){
            $res = '';
            $this->dbHandle->group_by('date');
        }else if($inputRequest['view'] == 2){
            $res = ', week(date('.$fieldName.'),1) as week';
            $this->dbHandle->group_by('week');
        }else if($inputRequest['view'] == 3){
            $res = ', month('.$fieldName.') as month';
            $this->dbHandle->group_by('month,date');
        }
        $this->dbHandle->select('date('.$fieldName.') as date ,count(1) as count'.$res,false);
        $this->dbHandle->from('rmcStudentExamsDocuments');
        if($inputRequest['abroadExamList'] && $inputRequest['abroadExamList'] != 0){
            $this->dbHandle->where('examId',$inputRequest['abroadExamList']);
        }
        $this->dbHandle->where_in('trackingKeyId',$trackingIds);
        $this->dbHandle->where('addedOn >=',$inputRequest['dateRange']['startDate'].' 00:00:00');
        $this->dbHandle->where('addedOn <=',$inputRequest['dateRange']['endDate'].' 23:59:59');
        $this->dbHandle->order_by("count", "asc");
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

}




























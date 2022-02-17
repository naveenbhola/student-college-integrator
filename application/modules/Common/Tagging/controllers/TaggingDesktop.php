<?php

class TaggingDesktop extends MX_Controller {

    private function _init(){

        if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }
        $this->load->helper(array('mAnA5/ana','image','shikshautility'));
        $this->load->config('messageBoard/DesktopSiteTracking');
        $this->load->config('Tagging/TaggingConfig');
    }

    public function getTagDetailPage($tagString){

        $this->_init();

        $data = array();

        //Get Tag Id and Pagination number
        $paramsArray = explode("-",$tagString);
        if(count($paramsArray)>1){
            $tagId = $paramsArray[0];
            $pageNumber = $paramsArray[1];
        }
        else{
            $tagId = $paramsArray[0];
            $pageNumber = 1;
        }

        // Check Added to Avoid Db Errors
        if(!is_numeric($tagId)){
            error_log(" Manual 404 Throws Desktop for Id: ".$tagId."\n",3,"/tmp/tdp_404.log");
            show_404();
        }
        
        //Get Tag Name from Database.
        $this->load->model('Tagging/taggingmodel');
        $this->tagmodel = new TaggingModel();
        $tagArray = array();
        $tagArray[0]['id'] = $tagId;
        $tagDetails = $this->tagmodel->findTagDetails($tagArray);

        $tagsArr[$tagId] = $tagDetails[0]['tagName'];
            $this->load->library('messageBoard/TagUrlMapping');
            $tagUrlMappingObj = new TagUrlMapping(); 

            //getting chp url for tagIds exist on hierarchy combination
            $this->load->library('messageBoard/AnALibrary');
        $chpTagUrls = $this->analibrary->getChpUrlForTagsExistOnHierarchies(array_keys($tagsArr));

            $notExistChpUrlTags = array();
            foreach ($tagsArr as $key => $value) {
                if(!array_key_exists($key, $chpTagUrls))
                {
                    $notExistChpUrlTags[$key] = $value;
                }
            }

            $tagUrlInfo = array();

            if(!empty($notExistChpUrlTags)){
                $tagUrlInfo = $tagUrlMappingObj->getTagsUrl($notExistChpUrlTags);    
            }
            if(!is_array($chpTagUrls)){
                $chpTagUrls = array();
            }
            $tagUrlInfo = $tagUrlInfo + $chpTagUrls;
            
            $tagDetails[0]['url'] = $tagUrlInfo[$tagId]['url'];

        if(count($tagDetails)<=0){
            show_404();
        }
        $tagName = $tagDetails[0]['tagName'];

	//Sanitize the Page number value.
        if(! ((int)$pageNumber == $pageNumber && (int)$pageNumber > 0) ){
		$baseURL = getSeoUrl($tagId, 'tag', $tagName);		
            if( (strpos($baseURL, "http") === false) || (strpos($baseURL, "http") != 0) || (strpos($baseURL, SHIKSHA_HOME) === 0) || (strpos($baseURL,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($baseURL,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($baseURL,ENTERPRISE_HOME) === 0) ){
                header("Location: $baseURL",TRUE,301);
            }
            else{
                header("Location: ".SHIKSHA_HOME,TRUE,301);
            }
        	exit;
        }
	//Sanitize the Type field
        if(isset($_REQUEST['type'])){
		if(!in_array($_REQUEST['type'], array('all','question','discussion','unanswered'))){
	                $baseURL = getSeoUrl($tagId, 'tag', $tagName);
        	        header("Location: $baseURL",TRUE,301);
                	exit;
		}
        }

        //Get SEO Details and check if entered URL is correct. If not, redirect to Corrent page
        $currentUrl = getCurrentPageURLWithoutQueryParams();
        $seoDetails = $this->getTagSeoDetails($tagName, $tagId, $pageNumber);
        $query_params = $_SERVER["QUERY_STRING"];

        if($seoDetails['canonicalURL'] != $currentUrl){
            if(!empty($query_params)){
                $query_param = '?'.$query_params;
            }
            $url = $seoDetails['canonicalURL'].$query_param;
            
            header("Location: $url",TRUE,301);
            exit;
        }
        
        //Get logged in user details
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userGroup    = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
        $displayData['userStatus'] = $this->userStatus;
        $displayData['userIdOfLoginUser']    = $userId;
        $displayData['userGroup']            = $userGroup;
        $displayData['validateuser'] = $this->userStatus;

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_Tagging','pageType'=>$this->input->get('type'),'entity_id'=>$tagId,'anaTags'=>$tagName);
        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['userStatus'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        //Make a CURL call to fetch the data
        $contentType = "all";
        $paginationPath = $seoDetails['baseURL'].'-@pagenum@';

        if(isset($_REQUEST['type'])){
            $contentType = $_REQUEST['type'];
            $paginationPath = $seoDetails['baseURL'].'-@pagenum@?type='.$contentType;
        }
        $pageType = $contentType;

        $count = 10;
        $start = ($pageNumber - 1) * $count;

        $APIClient = $this->load->library("APIClient");
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");
        $jsonDecodedData =$APIClient->getAPIData("Tags/getTagDetailPage/".$tagId."/".$contentType."/".$start."/".$count);

	if(isset($jsonDecodedData['error'][0]) && count($jsonDecodedData['error'][0])>1){
                $baseURL = getSeoUrl($tagId, 'tag', $tagName);
                header("Location: $baseURL",TRUE,301);
                exit;
	}

        $displayData['data'] = $jsonDecodedData;
	if(isset($displayData['data']['content'])){
		$displayData['data']['homepage'] = $displayData['data']['content'];
	}
        $displayData['data']['tagId'] = $tagId;
        $displayData['topFollowTrackingPageKeyId'] = D_TDP_ALL_FOLLOW_TAG_STATS_WIDGET;
        $totalResultSet = 0;
        switch($contentType){
                case 'all': if(isset($jsonDecodedData['questionCount']) && isset($jsonDecodedData['discussionCount'])){
                                $totalResultSet = $jsonDecodedData['questionCount'] + $jsonDecodedData['discussionCount'];
                            }
                            $displayData['trackingtype'] = D_TDP_ALL_TAB_PAGEVIEW;
                            $displayData['ctrackingPageKeyId'] = D_TDP_ALL_DCOMMENET_POST_WIDGET;
                            $displayData['atrackingPageKeyId'] = D_TDP_ALL_ANSWER_POST_WIDGET;
                            $displayData['tupdctrackingPageKeyId'] = D_TDP_ALL_TUP_DCOMMENT_TUPE;
                            $displayData['tdowndctrackingPageKeyId'] = D_TDP_ALL_TDOWN_DCOMMENT_TUPLE;
                            $displayData['tupatrackingPageKeyId'] = D_TDP_ALL_TUP_ANSWER_TUPLE;
                            $displayData['tdownatrackingPageKeyId'] = D_TDP_ALL_TDOWN_ANSWER_TUPLE;
                            $displayData['qfollowTrackingPageKeyId'] = D_TDP_ALL_FOLLOW_QUES;
                            $displayData['dfollowTrackingPageKeyId'] = D_TDP_ALL_FOLLOW_DISC;
                            $displayData['tfollowTrackingPageKeyId'] = D_TDP_ALL_FOLLOW_TAG_RELATED_WIDGET;
                            $displayData['ufollowTrackingPageKeyId'] = D_TDP_ALL_FOLLOW_USER_ACTIVE_WIDGET;
                            $displayData['flistfollowTrackingPageKeyId'] = D_TDP_ALL_FOLLOW_USER_FOLLOWER_LIST;
                            break;
                case 'discussion': if( isset($jsonDecodedData['discussionCount'])){
                                $totalResultSet = $jsonDecodedData['discussionCount'];
                            }
                            $displayData['trackingtype'] = D_TDP_DISC_TAB_PAGEVIEW;
                            $displayData['ctrackingPageKeyId'] = D_TDP_DISC_TAB_DCOMMENT_POST_WIDGET;
                            $displayData['tupdctrackingPageKeyId'] = D_TDP_DISC_TAB_TUP_DCOMMENT_TUPLE;
                            $displayData['tdowndctrackingPageKeyId'] = D_TDP_DISC_TAB_TDOWN_DCOMMENT_TUPLE;
                            $displayData['dfollowTrackingPageKeyId'] = D_TDP_DISC_FOLLOW_DISC;
                            $displayData['tfollowTrackingPageKeyId'] = D_TDP_DISC_FOLLOW_TAG_RELATED_WIDGET;
                            $displayData['ufollowTrackingPageKeyId'] = D_TDP_DISC_FOLLOW_USER_ACTIVE_WIDGET;
                            $displayData['flistfollowTrackingPageKeyId'] = D_TDP_DISC_FOLLOW_USER_FOLLOWER_LIST;
                            break;
                case 'unanswered':
                            $this->load->model('mTag5/TagsModel');
                            $totalResultSet = $this->TagsModel->getUnansweredQuestionCount($tagId);
                            $displayData['trackingtype'] = D_TDP_UANS_TAB_PAGEVIEW;
                            $displayData['atrackingPageKeyId'] = D_TDP_UANS_TAB_ANSWER_POST_WIDGET;
                            $displayData['qfollowTrackingPageKeyId'] = D_TDP_UANS_FOLLOW_QUES;
                            $displayData['tfollowTrackingPageKeyId'] = D_TDP_UANS_FOLLOW_TAG_RELATED_WIDGET;
                            $displayData['ufollowTrackingPageKeyId'] = D_TDP_UANS_FOLLOW_USER_ACTIVE_WIDGET;
                            $displayData['flistfollowTrackingPageKeyId'] = D_TDP_UANS_FOLLOW_USER_FOLLOWER_LIST;
	}
        $displayData['paginationHTML']      = doPagination_AnA($totalResultSet,$paginationPath,$pageNumber,$count,10,'tdp');
        //$displayData['noIndexFollow']       = true;
        //_p($tagDetails);die;
        if($displayData['data']['questionCount']>0 && $tagDetails[0][isNonCrawlable]!='Y')
        {
            $displayData['noIndexNoFollow']       = false;
        }
        else
        {
            $displayData['noIndexNoFollow']       = true;
        }
        $displayData['nextPaginationIndex'] = $jsonDecodedData['nextPaginationIndex'];
        $displayData['nextPageNo']          = $currentPageNo + 1;
        $displayData['pageType']            = $pageType;
        $displayData['pageName']            = 'tagDetailPage';
        $displayData['seoTitle']            = $seoDetails['title'];
        $displayData['metaDescription']     = $seoDetails['description'];
        $displayData['canonicalURL']        = $tagDetails[0]['url'];//$seoDetails['canonicalURL'];
        $displayData['tagUrlType']          = $tagUrlInfo[$tagId]['type'];
        $displayData['baseURL']             = $seoDetails['baseURL'];
	$displayData['metaKeywords']        = $seoDetails['keywords'];
        $displayData['data']['tagsRecommendations'] = $jsonDecodedData['relatedTags'];
        $displayData['data']['tagRecoWidgetTitle'] = "Related Tags";

        // change the position of tag recommendation in case number of results are less than recommendation index
        if($displayData['data']['showTagsRecommendationsAtPostion'] && $displayData['data']['showTagsRecommendationsAtPostion'] >= $totalResultSet){
            $displayData['data']['showTagsRecommendationsAtPostion'] = $totalResultSet - 1;
            if($displayData['data']['showTagsRecommendationsAtPostion'] < 0){
                $displayData['data']['showTagsRecommendationsAtPostion'] = 0;
            }
        }
       
	//Create Next & Prev Meta info
        //Check if next page exists
        if($totalResultSet > ($pageNumber*$count)){
              $showPage = $pageNumber + 1;
              $displayData['nextURL'] = $seoDetails['baseURL']."-$showPage";
              //Also, check for the Tabs
              if($contentType == 'discussion' || $contentType == 'unanswered'){
                     $displayData['nextURL'] .= "?type=".$contentType;
              }
        }
	if($pageNumber == 2){
		$displayData['previousURL'] = $seoDetails['baseURL'];
                //Also, check for the Tabs
                if($contentType == 'discussion' || $contentType == 'unanswered'){
                        $displayData['previousURL'] .= "?type=".$contentType;
                }
	}
	else  if($pageNumber > 2){
		$showPage = $pageNumber - 1;
                $displayData['previousURL'] = $seoDetails['baseURL']."-$showPage";;
                //Also, check for the Tabs
                if($contentType == 'discussion' || $contentType == 'unanswered'){
                        $displayData['previousURL'] .= "?type=".$contentType;
                }
        }

        $displayData['trackingpageIdentifier'] = D_TDP_PAGEVIEW;

         $displayData['gtmParams'] = array(
                        "pageType"    => $displayData['trackingpageIdentifier'],
                        "countryId"     => 2,
                        "tabName" => $displayData['trackingtype']
                );
        if($userId > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }

        //below line is used for pageview tracking purpose
        $tracking = $this->load->library('common/trackingpages');
        $displayData['trackingpageNo'] = $tagId;
        $displayData['trackingcountryId']=2;
        $tracking->_pagetracking($displayData);

        $this->getMISGATrackingDetails($displayData,$contentType);
        $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In': 'Non-Logged In';

        //ask now
        $displayData['qtrackingPageKeyId'] = '1437';
        $displayData['entityId']           = $tagId;
        $displayData['tagEntityType']     = 'tagDetailPage';
        $displayData['suggestorPageName']  = "all_tags";

        $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');
        if(!empty($displayData['data']['homepage']))
        {
        foreach($displayData['data']['homepage'] as $k=>$hpd)
        {
            foreach($hpd['tags'] as $x=>$y)
            {
                $tagsArr[$y['tagId']]=$y['tagName'];
            }
        }
            $this->load->library('messageBoard/TagUrlMapping');
            $tagUrlMappingObj = new TagUrlMapping(); 

            $tagUrlInfo = $tagUrlMappingObj->getTagsUrl($tagsArr);
            //_p($tagUrlInfo);die;
            foreach($displayData['data']['homepage'] as $k=>$hpd)
            {
                foreach($hpd['tags'] as $x=>$y)
                {
                    if(is_array($tagUrlInfo[$y['tagId']]))
                    {
                        $url = $tagUrlInfo[$y['tagId']]['url'];
                    }
                    else
                    {
                        $url = getSeoUrl($y['tagId'], 'tag', $y['tagName']);    
                    }
                    $displayData['data']['homepage'][$k]['tags'][$x]['type']=$tagUrlInfo[$y['tagId']]['type'];
                    $displayData['data']['homepage'][$k]['tags'][$x]['url']=$url;
                }
            }
        }
        $this->load->view('desktop/homepage',$displayData);

    }

    private function getTagSeoDetails($tagName, $tagId, $pageNumber){
        $displayData = array();
        if($pageNumber == 1){
            $displayData['title']               = "$tagName - Updates, Questions & Discussions on $tagName at Shiksha";
            $displayData['description']         = "Get latest updates on $tagName and read discussions, questions & answers on $tagName at Shiksha.com. Join Shiksha community to Know all about $tagName & get connected with thousands of career experts, counsellors, and students.";
	    $displayData['keywords']	        = "$tagName, latest updates on $tagName, $tagName questions, $tagName discussions";
            $baseUrl                            = getSeoUrl($tagId, 'tag', $tagName);
            $displayData['canonicalURL']        = $baseUrl;
            $displayData['baseURL']             = $baseUrl;
        }
        else if($pageNumber > 1){
            $displayData['title']               = "Page $pageNumber - $tagName - Updates, Questions & Discussions on $tagName at Shiksha";
            $displayData['description']         = "Page $pageNumber - Get latest updates on $tagName and read discussions, questions & answers on $tagName at Shiksha.com. Join Shiksha community to Know all about $tagName & get connected with thousands of career experts, counsellors, and students.";
	    $displayData['keywords']            = "$tagName, latest updates on $tagName, $tagName questions, $tagName discussions";
            $baseUrl                            = getSeoUrl($tagId, 'tag', $tagName);
            $displayData['baseURL']             = $baseUrl;
            $displayData['canonicalURL']        = $baseUrl."-$pageNumber";
        }
        return $displayData;
    }
    function getTags($start = 1)
    {
        $this->_init();
        if( !((int) $start == $start && (int) $start > 0))
        {
            $redirectUrl = '/tags';
            header("Location: $redirectUrl",TRUE,301);
            exit;
        }
        // logged in user details
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userGroup    = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
        $data['userStatus'] = $this->userStatus;
        $data['userIdOfLoginUser']    = $userId;
        $data['userGroup']            = $userGroup;
        $data['validateuser'] = $this->userStatus;
        $this->load->builder('SearchBuilder', 'search');
        $this->config->load('search_config');
        $this->searchServer = SearchBuilder::getSearchServer($this->config->item('search_server'));
        //http://10.10.16.71:8985/solr/collection1/select?q=*%3A*&wt=xml&indent=true&fq=facetype:tag&sort=tag_name%20asc&fl=tag_name&start=0&rows=10
        $rows=30;
        $startOffSet = ($start-1) * 30;
        $solrUrl = SOLR_AUTOSUGGESTOR_URL."q=*%3A*&wt=phps&indent=true&fq=facetype:tag&sort=tag_quality_factor%20desc&fl=tag_name,tag_id&start=".$startOffSet."&rows=".$rows;
        $solrContent = unserialize($this->searchServer->curl($solrUrl));
        $data['numberOfTags'] =  $solrContent['response']['numFound'];
        $data['startOffSet']  = $start;
        $tagsResult = $solrContent['response']['docs'];
        foreach ($tagsResult as $key => $value) {
            $url = getSeoUrl($value['tag_id'], 'tag', $value['tag_name']);    
            $tagsResult[$key]['url'] = $url;
        }
        $data['tags'] = $tagsResult;
        $data['limit'] = $rows;
        $paginationPath = '/tags-@pagenum@';
        $data['paginationHTMLForGoogle'] = doPagination_AnA($data['numberOfTags'],$paginationPath,$start,$rows,10,'viewAllTags_D');
        $data['noIndexFollow'] = true;
        
        $totalpages = ceil($data['numberOfTags']/$rows);
        if($start<=1){
            if($totalpages >1){
                $data['nextURL'] = SHIKSHA_HOME.'/tags-'.($start+1);
                $data['canonicalURL'] = SHIKSHA_HOME.'/tags';
            }
        }else{
            if($start < $totalpages){
                $data['nextURL'] = SHIKSHA_HOME.'/tags-'.($start+1);
                $data['previousURL'] = SHIKSHA_HOME.'/tags-'.($start-1);
                $data['canonicalURL'] = SHIKSHA_HOME.'/tags';
            }else if($start = $totalpages){
                $data['previousURL'] = SHIKSHA_HOME.'/tags-'.($start-1);
                $data['canonicalURL'] = SHIKSHA_HOME.'/tags';
            }
        }
        if($start == 2)
        {
            $data['previousURL'] = SHIKSHA_HOME.'/tags';
        }

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userGroup    = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
        $data['userStatus'] = $this->userStatus;
        $data['userIdOfLoginUser']    = $userId;
        $data['userGroup']            = $userGroup;
        $data['suggestorPageName']    = 'all_tags';

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_AllTags');
        $data['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        $data['trackingpageIdentifier'] = D_VIEW_TAGS_PAGEVIEW;

         $data['gtmParams'] = array(
                        "pageType"    => $data['trackingpageIdentifier'],
                        "countryId"     => 2,
                );
        if($userId > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }

        //below line is used for pageview tracking purpose
        $tracking = $this->load->library('common/trackingpages');
        $data['trackingcountryId']=2;
        $tracking->_pagetracking($data);

        $data['GA_userLevel'] = $userId > 0 ? 'Logged In': 'Non-Logged In';
        
        $data['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');
        
        $this->load->view('desktop/showAllTags',$data);
    }

   function getContributorTags($start = 1)
    {
        $this->_init();
        if( !((int) $start == $start && (int) $start > 0))
        {
            $redirectUrl = '/tags';
            header("Location: $redirectUrl",TRUE,301);
            exit;
        }
        // logged in user details
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userGroup    = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
        $data['userStatus'] = $this->userStatus;
        $data['userIdOfLoginUser']    = $userId;
        $data['userGroup']            = $userGroup;
        $data['validateuser'] = $this->userStatus;

        $this->load->builder('SearchBuilder', 'search');
        $this->config->load('search_config');
        $this->searchServer = SearchBuilder::getSearchServer($this->config->item('search_server'));
        //http://10.10.16.71:8985/solr/collection1/select?q=*%3A*&wt=xml&indent=true&fq=facetype:tag&sort=tag_name%20asc&fl=tag_name&start=0&rows=10
        $rows=30;
        $startOffSet = ($start-1) * 30;
        $solrUrl = SOLR_AUTOSUGGESTOR_URL."q=*%3A*&wt=phps&indent=true&fq=facetype:tag&sort=tag_quality_factor%20desc&fl=tag_name,tag_id&start=".$startOffSet."&rows=".$rows;
        $solrContent = unserialize($this->searchServer->curl($solrUrl));
        $data['numberOfTags'] =  $solrContent['response']['numFound'];
        $data['startOffSet']  = $start;
        $tagsResult = $solrContent['response']['docs'];
        foreach($tagsResult as $k=>$v)
        {
            $tagids[]=$v['tag_id'];
        }
        $this->load->model('Tagging/taggingmodel');
        $tagmodel = new TaggingModel();
        $data['followedTagsOnPage'] = $tagmodel->isUserFollowingTag($userId,$tagids,'tag');

        foreach ($tagsResult as $key => $value) {
            $url = getSeoUrl($value['tag_id'], 'tag', $value['tag_name']);    
            $tagsResult[$key]['url'] = $url;
        }
        $data['tags'] = $tagsResult;
        $data['limit'] = $rows;

        $paginationPath = '/contributorTags-@pagenum@';
        $data['paginationHTMLForGoogle'] = doPagination_AnA($data['numberOfTags'],$paginationPath,$start,$rows,10,'viewAllTags_D');
        $data['noIndexFollow'] = true;
        
        $totalpages = ceil($data['numberOfTags']/$rows);
        if($start<=1){
            if($totalpages >1){
                $data['nextURL'] = SHIKSHA_HOME.'/contributorTags-'.($start+1);
                $data['canonicalURL'] = SHIKSHA_HOME.'/contributorTags';
            }
        }else{
            if($start < $totalpages){
                $data['nextURL'] = SHIKSHA_HOME.'/contributorTags-'.($start+1);
                $data['previousURL'] = SHIKSHA_HOME.'/contributorTags-'.($start-1);
                $data['canonicalURL'] = SHIKSHA_HOME.'/contributorTags';
            }else if($start = $totalpages){
                $data['previousURL'] = SHIKSHA_HOME.'/contributorTags-'.($start-1);
                $data['canonicalURL'] = SHIKSHA_HOME.'/contributorTags';
            }
        }
        if($start == 2)
        {
            $data['previousURL'] = SHIKSHA_HOME.'/contributorTags';
        }

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userGroup    = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
        $data['userStatus'] = $this->userStatus;
        $data['userIdOfLoginUser']    = $userId;
        $data['userGroup']            = $userGroup;
        $data['suggestorPageName']    = 'all_tags';


        $data['trackingpageIdentifier'] = D_VIEW_TAGS_PAGEVIEW;

         $data['gtmParams'] = array(
                        "pageType"    => $data['trackingpageIdentifier'],
                        "countryId"     => 2,
                );
        if($userId > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }

        //below line is used for pageview tracking purpose
        $tracking = $this->load->library('common/trackingpages');
        $data['trackingcountryId']=2;
        $tracking->_pagetracking($data);

        $data['GA_userLevel'] = $userId > 0 ? 'Logged In': 'Non-Logged In';
        
        $data['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');
        $this->load->view('showAllContributorTags',$data);
    }

    function getAutoSuggestor()
    {
	$this->_init();
        $specialUser = false;
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $allowedUsers = $this->config->item('special_tags_users');
        $specialtags  = $this->config->item('special_tags');

        if(in_array($userId, $allowedUsers)){
            $specialUser = true;
        }

        $userText = $this->input->post('text');
        $type     = $this->input->post('suggestionType');
        $this->AutosuggestorLib = $this->load->library('search/Autosuggestor/AutosuggestorLib');
        $autoSuggestorData = array();
        if($type == 'tag')
        {   
            $optionalParams = array();

            $optionalParams['specialtags'] = $specialtags;
            
            if($specialUser){
                $optionalParams['specialUser'] = true;
            }else{
                unset($optionalParams['specialUser']);
            }

            $response = $this->AutosuggestorLib->getAutoSuggestions('tag',$userText,$optionalParams);
            $i = 0;
            foreach ($response['tag'] as $key => $value) {
		if(!$specialUser && $value['tagId'] == 834600) continue;
                $url = getSeoUrl($value['tagId'], 'tag', $value['tagName']);
                $autoSuggestorData[$value['tagName']] = array('id' => $value['tagId'], 'url' => $url,'tagName' => $value['tagName']);
            }
        }
        $autoSuggestorResult['solr_results']['institute_title_facet'] = $autoSuggestorData;
        $autoSuggestorResult['solr_facet_to_heading_mapping']['institute_title_facet'] = 'Please select from the following list';
        echo json_encode($autoSuggestorResult);
    }

    private function _getTagPageRightHandWidgetData(&$displayData){

        $rightData = array();
        $rightData['topHeading1'] = "Most active users";
        $rightData['topHeading2'] = $displayData['tcTagName'];
        $rightData['subHeading'] = "Based on last week activity";
        $rightData['showRightRegirationWidget'] = true;

        $mostActiveUsers = array();
        $i = 0;
        if($displayData['topContributors']){
            foreach ($displayData['topContributors'] as &$value) {
                $value['followers']   = $value['followerCount'];
                $value['isFollowing'] = $value['isUserFollowing'];
                unset($value['followerCount']);
                unset($value['isUserFollowing']);
            }
        }
        $displayData['rightWidgetUserData'] = $displayData['topContributors'];
        // _p($displayData['data']['topContributors']);die;

        $displayData['rightData'] = $rightData;
    }

    function getTagsMostActiveUsers(){

        $this->_init();

        $tagId = $this->input->post("tagId");
        $tagName = $this->input->post("tagName");

        $APIClient = $this->load->library("APIClient");
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");
        $APIClient->setRequestData(array("tagId"=>$tagId, "tagName" => $tagName));
        $jsonDecodedData =$APIClient->getAPIData("Tags/getTagsMostActiveUsers");

        $displayData = array();
        $displayData['topContributors'] = $jsonDecodedData['topContributors'];
        $displayData['tcTagName'] = $jsonDecodedData['tcTagName'];
        $displayData['userId'] = $userId;
        $this->_getTagPageRightHandWidgetData($displayData);
        
        $this->load->view("messageBoard/desktopNew/widgets/topContributorWidgetList", $displayData);
        

    }
    
function getMISGATrackingDetails(&$displayData,$tabName='')
{
    $displayData['GA_currentPage']       =  'TAG DETAIL PAGE';
    $displayData['GA_Tap_On_Follow_Top'] = 'FOLLOW_TAGDETAIL_DESKAnA';
    $displayData['GA_Tap_On_All_Tab']    = 'ALLTAB_TAGDETAIL_DESKAnA';
    $displayData['GA_Tap_On_Disc_Tab']   = 'DISCUSSIONTAB_TAGDETAIL_DESKAnA';
    $displayData['GA_Tap_On_Unans_Tab']  = 'UNANSWERED_TAGDETAIL_DESKAnA';
    $displayData['GA_Tap_On_Tag_Reco']          = 'RELATEDTAG_TAGDETAIL_DESKAnA';
    $displayData['GA_Tap_On_Profile_User_Reco'] = 'ACTIVEUSER_TAGDETAIL_DESKAnA';
    $displayData['GA_Tap_On_Follow_Tag_Reco']   = 'FOLLOW_RELATEDTAG_TAGDETAIL_DESKAnA';
    $displayData['GA_Tap_On_Follow_User_Reco']  = 'FOLLOW_ACTIVEUSER_TAGDETAIL_DESKAnA';
    $displayData['GA_Tap_On_Right_Reg_CTA']     = 'REGISTER_RHSWIDGET_TAGDETAIL_DESKAnA';
    $displayData['GA_Tap_On_Right_All_Ques']    = 'ALLQUESTIONS_RHSWIDGET_TAGDETAIL_DESKAnA';
    $displayData['GA_Tap_On_Right_All_Disc']    = 'ALLDISCUSSIONS_RHSWIDGET_TAGDETAIL_DESKAnA';
    $displayData['GA_Tap_On_Right_All_Tags']    = 'ALLTAGS_RHSWIDGET_TAGDETAIL_DESKAnA';
    $displayData['GA_Tap_On_Right_Community']   = 'COMMUNITYGUIDELINES_RHSWIDGET_TAGDETAIL_DESKAnA';
    $displayData['GA_Tap_On_Right_User_Point']  = 'USERPOINT_RHSWIDGET_TAGDETAIL_DESKAnA';
    $displayData['regRightTrackingPageKeyId']   = D_TDP_ALL_RIGHT_REG_WIDGET;
    switch($tabName)
    {
        case 'all':
                $displayData['GA_Tap_On_Answer_CTA']    = 'WRITEANSWER_QUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Answer']        = 'ANSWER_QUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_ViewMore_Ans']  = 'VIEWMORE_ANSWER_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Follow_QUES']   =  'FOLLOW_QUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Share_Ques']    = 'SHARE_QUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Owner_Ans']     = 'PROFILE_ANSWER_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Tag_Ques']      = 'TAG_QUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Ques']          = 'QUESTTITLE_QUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Upovte_Ans']    = 'UPVOTE_ANSWER_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Downvote_Ans']  = 'DOWNVOTE_ANSWER_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_ViewMore_Com']  = 'VIEWMORE_COMMENT_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Com_CTA']       = 'WRITECOMMENT_DISC_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Comment']       = 'COMMENT_DISC_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Follow_Disc']   = 'FOLLOW_DISC_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Share_Disc']    = 'SHARE_DISC_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Owner_Com']     = 'PROFILE_COMMENT_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Tag_Disc']      = 'TAG_DISC_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Disc']          = 'DISCTITLE_DISC_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Upovte_Com']    = 'UPVOTE_COMMENT_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Downvote_Com']  = 'DOWNVOTE_COMMENT_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Follow_List_QUES']   = 'FOLLOWERLIST_QUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Follow_List_DISC']   = 'FOLLOWERLIST_DISC_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Answer_CTA_UN']         = 'WRITEANSWER_UNANQUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Follow_QUES_UN']        =  'FOLLOW_UNANQUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Share_Ques_UN']         = 'SHARE_UNANQUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Tag_Ques_UN']           = 'TAG_UNANQUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Ques_UN']               = 'QUESTTITLE_UNANQUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Follow_List_QUES_UN']   = 'FOLLOWERLIST_UNANQUEST_TAGDETAIL_DESKAnA';
                $displayData['ctrackingPageKeyId']          = D_TDP_ALL_DCOMMENET_POST_WIDGET;
                $displayData['atrackingPageKeyId']          = D_TDP_ALL_ANSWER_POST_WIDGET;
                $displayData['tupdctrackingPageKeyId']      = D_TDP_ALL_TUP_DCOMMENT_TUPE;
                $displayData['tdowndctrackingPageKeyId']    = D_TDP_ALL_TDOWN_DCOMMENT_TUPLE;
                $displayData['tupatrackingPageKeyId']       = D_TDP_ALL_TUP_ANSWER_TUPLE;
                $displayData['tdownatrackingPageKeyId']     = D_TDP_ALL_TDOWN_ANSWER_TUPLE;
                $displayData['qfollowTrackingPageKeyId']    = D_TDP_ALL_FOLLOW_QUES;
                $displayData['dfollowTrackingPageKeyId']    = D_TDP_ALL_FOLLOW_DISC;
                $displayData['tfollowTrackingPageKeyId']    = D_TDP_ALL_FOLLOW_TAG_RELATED_WIDGET;
                //$displayData['ufollowTrackingPageKeyId'] = D_TDP_ALL_FOLLOW_USER_ACTIVE_WIDGET;
                $displayData['flistfollowTrackingPageKeyId'] = D_TDP_ALL_FOLLOW_USER_FOLLOWER_LIST;
                $displayData['fuRightActiveTrackingPageKeyId'] = D_TDP_ALL_FOLLOW_USER_RIGHT_MOST_ACTIVE;
                $displayData['fuRightTopTrackingPageKeyId']    = D_TDP_ALL_FOLLOW_USER_RIGHT_SIDE_TOP_CONTRI;
                    break;
        case 'discussion':
                $displayData['GA_Tap_On_ViewMore_Com']       = 'VIEWMORE_COMMENT_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Com_CTA']            = 'WRITECOMMENT_DISC_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Comment']            = 'COMMENT_DISC_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Follow_Disc']        = 'FOLLOW_DISC_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Share_Disc']         = 'SHARE_DISC_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Owner_Com']          = 'PROFILE_COMMENT_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Tag_Disc']           = 'TAG_DISC_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Disc']               = 'DISCTITLE_DISC_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Upovte_Com']         = 'UPVOTE_COMMENT_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Downvote_Com']       = 'DOWNVOTE_COMMENT_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Follow_List_DISC']   = 'FOLLOWERLIST_DISC_TAGDETAIL_DESKAnA';
                $displayData['ctrackingPageKeyId']              = D_TDP_DISC_TAB_DCOMMENT_POST_WIDGET;
                $displayData['tupdctrackingPageKeyId']          = D_TDP_DISC_TAB_TUP_DCOMMENT_TUPLE;
                $displayData['tdowndctrackingPageKeyId']        = D_TDP_DISC_TAB_TDOWN_DCOMMENT_TUPLE;
                $displayData['dfollowTrackingPageKeyId']        = D_TDP_DISC_FOLLOW_DISC;
                $displayData['tfollowTrackingPageKeyId']        = D_TDP_DISC_FOLLOW_TAG_RELATED_WIDGET;
                //$displayData['ufollowTrackingPageKeyId']      = D_TDP_DISC_FOLLOW_USER_ACTIVE_WIDGET;
                $displayData['flistfollowTrackingPageKeyId']    = D_TDP_DISC_FOLLOW_USER_FOLLOWER_LIST;
                $displayData['fuRightActiveTrackingPageKeyId']  = D_TDP_DISC_FOLLOW_USER_RIGHT_MOST_ACTIVE;
                $displayData['fuRightTopTrackingPageKeyId']     = D_TDP_DISC_FOLLOW_USER_RIGHT_SIDE_TOP_CONTRI;
                    break;
        case 'unanswered':
                $displayData['GA_Tap_On_Answer_CTA_UN']         = 'WRITEANSWER_UNANQUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Follow_QUES_UN']        =  'FOLLOW_UNANQUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Share_Ques_UN']         = 'SHARE_UNANQUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Tag_Ques_UN']           = 'TAG_UNANQUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Ques_UN']               = 'QUESTTITLE_UNANQUEST_TAGDETAIL_DESKAnA';
                $displayData['GA_Tap_On_Follow_List_QUES_UN']   = 'FOLLOWERLIST_UNANQUEST_TAGDETAIL_DESKAnA';
                $displayData['atrackingPageKeyId']              = D_TDP_UANS_TAB_ANSWER_POST_WIDGET;
                $displayData['qfollowTrackingPageKeyId']        = D_TDP_UANS_FOLLOW_QUES;
                $displayData['tfollowTrackingPageKeyId']        = D_TDP_UANS_FOLLOW_TAG_RELATED_WIDGET;
                //$displayData['ufollowTrackingPageKeyId']      = D_TDP_UANS_FOLLOW_USER_ACTIVE_WIDGET;
                $displayData['flistfollowTrackingPageKeyId']    = D_TDP_UANS_FOLLOW_USER_FOLLOWER_LIST;
                $displayData['fuRightActiveTrackingPageKeyId']  = D_TDP_UANS_FOLLOW_USER_RIGHT_MOST_ACTIVE;
                $displayData['fuRightTopTrackingPageKeyId']     = D_TDP_UANS_FOLLOW_USER_RIGHT_SIDE_TOP_CONTRI;
                    break;
        }
    }
    function getParentTags()
    {
        $tagId = isset($_POST['tagId']) ? $this->input->post('tagId') : '';

        if(!is_numeric($tagId) && $tagId <= 0)
        {
            return;
        }
        $this->load->model('Tagging/taggingmodel');
        $tagmodel = new TaggingModel();

        $parentTags = $tagmodel->getParentTagNames($tagId);
        echo json_encode($parentTags);
    }
}
?>


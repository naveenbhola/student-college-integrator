<?php 
class TagMobile extends MX_Controller
{

    public function __construct()
    {

    }

    private function _init()
    {
        $this->load->library(array('message_board_client'));
        $this->msgbrdClient = new Message_board_client();

        if($this->userStatus == ""){
                $this->userStatus = $this->checkUserValidation();
        }

        $this->load->config('mcommon5/mobi_config');
        $this->load->helper(array('shikshautility','mAnA5/ana'));
        $this->load->config('mAnA5/MobileSiteTracking');
        $this->load->helper(array('mcommon5/mobile_html5'));
        $this->load->config('Tagging/TaggingConfig');
    }

    function getTags($start=1){
        $this->_init();
        $this->load->builder('SearchBuilder', 'search');
        $this->config->load('search_config');
        $this->searchServer = 
        SearchBuilder::getSearchServer($this->config->item('search_server'));
        //http://10.10.16.71:8985/solr/collection1/select?q=*%3A*&wt=xml&indent=true&fq=facetype:tag&sort=tag_name%20asc&fl=tag_name&start=0&rows=10
        $rows=10;
        $startOffSet = ($start-1) * 10;
        $solrUrl = SOLR_AUTOSUGGESTOR_URL."q=*%3A*&wt=phps&indent=true&fq=facetype:tag&sort=tag_name%20asc&fl=tag_name,tag_id&start=".$startOffSet."&rows=".$rows;

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
        $data['paginationHTMLForGoogle'] = doPagination_AnA($data['numberOfTags'],$paginationPath,$start,$rows,4);
        //noIndexNoFollow

        //below code used for beacon tracking
        $data['trackingpageIdentifier'] = MVIEW_TAGS_PAGEVIEW;
        $data['trackingcountryId']=2;

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userGroup    = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
        $data['userStatus'] = $this->userStatus;
        $data['userIdOfLoginUser']    = $userId;
        $data['userGroup']            = $userGroup;


        $displayData['gtmParams'] = array(
                        "pageType"    => $displayData['trackingpageIdentifier'],
                        "countryId"     => 2,
                );
        if($userId > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_AllTags');
        $data['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        //loading library to use store beacon traffic inforamtion
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($data);

        $data['boomr_pageid'] = 'mobilesite_tags';
        $data['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','mobile');
        $data['canonicalURL'] = SHIKSHA_HOME.'/tags';

        $this->load->view('showAllTags',$data);

     }

     function getAutoSuggestor()
     {
        $this->_init();
        $userText = $this->input->post('userText');
        $type     = $this->input->post('type');

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        
        $allowedUsers = $this->config->item('special_tags_users');
        $specialtags  = $this->config->item('special_tags');

        if(in_array($userId, $allowedUsers)){
            $specialUser = true;
        }

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
                $url = getSeoUrl($value['tagId'], 'tag', $value['tagName']);
                $autoSuggestorData[] = array('tagName'=>$value['tagName'], "url" => $url,"tagId" => $value['tagId']);
            }
        }
        
        echo json_encode($autoSuggestorData);   
     }

     function getTagDetailPage($tagString){
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
        
        if(!is_numeric($tagId)){
            error_log(" Manual 404 Throws Mobile for Id: ".$tagId."\n",3,"/tmp/tdp_404.log");
            show_404();
        }
        //Get Tag Name from Database.
        $this->load->model('Tagging/taggingmodel');
        $this->tagmodel = new TaggingModel();
		$tagArray = array();
		$tagArray[0]['id'] = $tagId;
        $tagDetails = $this->tagmodel->findTagDetails($tagArray);
        $tagsArr[$tagId]= $tagDetails[0]['tagName'];
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
                $tagUrlInfo = $tagUrlMappingObj->getTagsUrl($tagsArr);
            }

            if(!is_array($chpTagUrls)){
                $chpTagUrls = array();
            }
            $tagUrlInfo = $tagUrlInfo + $chpTagUrls;


            $tagDetails[0]['url'] = $tagUrlInfo[$tagId]['url'];
            //_p($tagDetails);die;
        if(count($tagDetails)<=0){
            show_404();            
        }
        $tagName = $tagDetails[0]['tagName'];
        
        //Get SEO Details and check if entered URL is correct. If not, redirect to Corrent page
        $currentUrl = getCurrentPageURLWithoutQueryParams();
        $queryParameters = $_SERVER['QUERY_STRING'];
        if(!empty($queryParameters))
        {
            $queryParameters = '?'.$queryParameters;
        }
        else
        {
            $queryParameters = '';
        }
        $seoDetails = $this->getTagSeoDetails($tagName, $tagId, $pageNumber);
        if($seoDetails['canonicalURL'] != $currentUrl){
            $url = $seoDetails['canonicalURL'].$queryParameters;
            if( (strpos($url, "http") === false) || (strpos($url, "http") != 0) || (strpos($url, SHIKSHA_HOME) === 0) || (strpos($url,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($url,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($url,ENTERPRISE_HOME) === 0) ){
                header("Location: $url",TRUE,301);
            }
            else{
                header("Location: ".SHIKSHA_HOME,TRUE,301);
            }
            exit;
        }
                
        //Get Logged-in user details
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userGroup    = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
        $data['userStatus'] = $this->userStatus;
        $data['userIdOfLoginUser']    = $userId;
        $data['userGroup']            = $userGroup;

        $callType = isset($_POST['callType'])?$this->input->post('callType'):'NONAJAX';
        $data['callType'] = $callType;
        
        //Make a CURL call to fetch the data
        $contentType = "all";
        $paginationPath = $seoDetails['baseURL'].'-@pagenum@';

        if(isset($_REQUEST['type'])){
            $contentType = $_REQUEST['type'];
            $paginationPath = $seoDetails['baseURL'].'-@pagenum@?type='.$contentType;
        }
        $data['pageType'] = $contentType;
        
        $count = 10;
        $start = ($pageNumber - 1) * $count;
        
        $APIClient = $this->load->library("APIClient");
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");
        $jsonDecodedData =$APIClient->getAPIData("Tags/getTagDetailPage/".$tagId."/".$contentType."/".$start."/".$count);
        $data['data'] = $jsonDecodedData;
        $data['data']['tagId'] = $tagId;

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_Tagging','pageType'=>$contentType,'entity_id'=>$tagId,'anaTags'=>$tagName);
        $data['dfpData']  = $dfpObj->getDFPData($data['userStatus'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');
        
        $totalResultSet = 0;
        $data['topFollowTrackingPageKeyId'] = MTDP_ALL_FOLLOW_TAG_STATS_WIDGET;
	switch($contentType){
		case 'all': if(isset($jsonDecodedData['questionCount']) && isset($jsonDecodedData['discussionCount'])){
				$totalResultSet = $jsonDecodedData['questionCount'] + $jsonDecodedData['discussionCount'];
			    }
                $data['trackingtype'] = MTDP_ALL_TAB_PAGEVIEW;
                $data['ctrackingPageKeyId'] = MTDP_ALL_DCOMMENET_POST_WIDGET;
                $data['atrackingPageKeyId'] = MTDP_ALL_ANSWER_POST_WIDGET;
                $data['tupctrackingPageKeyId'] = MTDP_ALL_TUP_DCOMMENT_TUPE;
                $data['tdownctrackingPageKeyId'] = MTDP_ALL_TDOWN_DCOMMENT_TUPLE;
                $data['tupatrackingPageKeyId'] = MTDP_ALL_TUP_ANSWER_TUPLE;
                $data['tdownatrackingPageKeyId'] = MTDP_ALL_TDOWN_ANSWER_TUPLE;
                $data['qfollowTrackingPageKeyId'] = MTDP_ALL_FOLLOW_QUES;
                $data['dfollowTrackingPageKeyId'] = MTDP_ALL_FOLLOW_DISC;
                $data['tfollowTrackingPageKeyId'] = MTDP_ALL_FOLLOW_TAG_RELATED_WIDGET;
                $data['ufollowTrackingPageKeyId'] = MTDP_ALL_FOLLOW_USER_ACTIVE_WIDGET;
			    break;
		case 'discussion': if( isset($jsonDecodedData['discussionCount'])){
                                $totalResultSet = $jsonDecodedData['discussionCount'];
                            }
                            $data['trackingtype'] = MTDP_DISC_TAB_PAGEVIEW;
                            $data['ctrackingPageKeyId'] = MTDP_DISC_TAB_DCOMMENT_POST_WIDGET;
                            $data['tupctrackingPageKeyId'] = MTDP_DISC_TAB_TUP_DCOMMENT_TUPLE;
                            $data['tdownctrackingPageKeyId'] = MTDP_DISC_TAB_TDOWN_DCOMMENT_TUPLE;
                            $data['dfollowTrackingPageKeyId'] = MTDP_DISC_FOLLOW_DISC;
                            $data['tfollowTrackingPageKeyId'] = MTDP_DISC_FOLLOW_TAG_RELATED_WIDGET;
                            $data['ufollowTrackingPageKeyId'] = MTDP_DISC_FOLLOW_USER_ACTIVE_WIDGET;
                            break;
		case 'unanswered': 
			    $this->load->model('TagsModel');
			    $totalResultSet = $this->TagsModel->getUnansweredQuestionCount($tagId);
                $data['trackingtype'] = MTDP_UANS_TAB_PAGEVIEW;
                $data['atrackingPageKeyId'] = MTDP_UANS_TAB_ANSWER_POST_WIDGET;
                $data['qfollowTrackingPageKeyId'] = MTDP_UANS_FOLLOW_QUES;
                $data['tfollowTrackingPageKeyId'] = MTDP_UANS_FOLLOW_TAG_RELATED_WIDGET;
                $data['ufollowTrackingPageKeyId'] = MTDP_UANS_FOLLOW_USER_ACTIVE_WIDGET;
			    break;
	}
        $data['paginationHTMLForGoogle'] = doPagination_AnA($totalResultSet,$paginationPath,$pageNumber,$count,4,'tdp');
        //$data['addNoFollow'] = true;
                //$data['addNoFollow'] = true;
        if($data['data']['questionCount']>0 && $tagDetails[0][isNonCrawlable]!='Y')
        {
            $data['noIndexNoFollow']       = false;
        }
        else
        {
            $data['noIndexNoFollow']       = true;
        }
        
        //Pass the SEO Detailss
        if(is_array($seoDetails)){
                $data['m_meta_title'] = $seoDetails['title'];
                $data['m_meta_description'] = $seoDetails['description'];
                $data['m_meta_keywords']      = $seoDetails['keywords'];
                $data['m_canonical_url'] = $seoDetails['canonicalURL'];
                $data['baseURL'] = $seoDetails['baseURL'];
        }
        $data['m_canonical_url'] = $tagDetails[0]['url'];

        //below line is used to store inforamtion in beacon variable for tracking purpose
        $data['trackingpageIdentifier']= MTDP_PAGEVIEW;
        $data['trackingpageNo'] = $tagId;
        $data['trackingcountryId']=2;
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($data);
        $data['boomr_pageid'] = 'mobilesite_TDP';
        $data['viewTrackParams'] = $jsonDecodedData['viewTrackParams'];
        
         $displayData['gtmParams'] = array(
                        "pageType"    => $displayData['trackingpageIdentifier'],
                        "countryId"     => 2,
                        "tabName"  => $displayData['trackingtype']
                );
        if($userId > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }
        $data['validateuser']['userid'] = $userId;

        $data['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','mobile');

        $data['topAskQuesTrackingKeyId'] =  MTDP_TOP_ASK_WIDGET;
        if(!empty($jsonDecodedData['questionCount'])){
            $quesCount = formatNumber($jsonDecodedData['questionCount']);          
        }
        $data['tdpTopMsg'] =  "Get insights from ".$quesCount." questions on ".$tagName.", answered by students, alumni, and experts. You may also ask and answer any question you like about ".$tagName;

        if(!empty($data['data']['content']))
        {
        foreach($data['data']['content'] as $k=>$hpd)
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
            foreach($data['data']['content'] as $k=>$hpd)
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
                    $data['data']['content'][$k]['tags'][$x]['type']=$tagUrlInfo[$y['tagId']]['type'];
                    $data['data']['content'][$k]['tags'][$x]['url']=$url;
                }
            }
            //echo "sdddddddddddd";die;
//_p($displayData);die;
        }


        //Load the View file
        if($callType=='AJAX'){
            echo $this->load->view('homepageContent',$data);
        }else{
            $this->load->view('homepage',$data);
        }
        
     }
     
    function getTagSeoDetails($tagName, $tagId, $pageNumber){
        $displayData = array();
        if($pageNumber == 1){
            $displayData['title']               = "$tagName - Updates, Questions & Discussions on $tagName at Shiksha";
            $displayData['description']         = "Get latest updates on $tagName and read discussions, questions & answers on $tagName at Shiksha.com. Join Shiksha community to Know all about $tagName & get connected with thousands of career experts, counsellors, and students.";
	    $displayData['keywords']            = "$tagName, latest updates on $tagName, $tagName questions, $tagName discussions";
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
     
}
?>

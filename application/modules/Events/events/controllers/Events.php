<?php
/*

   Copyright 2007 Info Edge India Ltd

   $Rev:: $: Revision of last commit
   $Author: pankajt $: Author of last commit
   $Date: 2010-09-08 02:36:24 $: Date of last commit

   Events.php controller for events.

   $Id: Events.php,v 1.90 2010-09-08 02:36:24 pankajt Exp $:

 */
class Events extends MX_Controller {
    private $userStatus=null;
    function init() {
        if($this->userStatus==null){
            $this->userStatus=$this->checkUserValidation();
        }
        $this->load->helper(array('form', 'url', 'image','shikshautility'));
        $this->load->library('ajax');
        $this->load->library(array('event_cal_client','register_client','alerts_client'));
    }

    function index($appID = 1,$countryName='india', $location_id='All',$location_name='All', $category_id =1,$category_name) {
	//Disabling the page and showing 404 page since this module is no longer functional
	show_404();
	exit();

        $this->init();
        $this->load->library('category_list_client');
        $this->load->library('Blog_client');
        $displayData = array();
        $EventCalClientObj = new Event_cal_client();
        $BlogClientObj = new Blog_client();
        $categoryClient = new Category_list_client();
        $cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
        $cityListTier2 = $categoryClient->getCitiesInTier($appId,2,2);
        $spotlightEventsList=$EventCalClientObj->getSpotlightEvents();
        $displayData['spotlightEventsList']=$spotlightEventsList;
        if(!is_int($location_id))
            $location_id = '1';
        if(!is_int($category_id))
            $category_id = '1';
        $displayData['eventListByType']=$EventCalClientObj->getEventsByCategoryDateLocation($countryName,$category_id,$location_id,$start_date,$end_date);
        $countryList = $this->getCountries();
        $displayData['country_list'] = $countryList;
        $displayData['countryListById'] = $countryList;
        $displayData['location_id'] = $location_id;
        if(empty($location_name)){
            $location_name = "india";
        }
        $displayData['location_name'] = $location_name;
        $displayData['countryName'] = $countryName;
        $displayData['category_id'] = $category_id;
        if(empty($category_name)){
            $category_name = "All";
        }
        $displayData['category_name'] =$category_name;
        if($countryName=='india'){
            $locationId="2";
        }else{
            $locationId=$location_id;
        }
        $relatedArticlesList=$BlogClientObj->getRelatedBlogs($appID,$category_id,0,3,$locationId);
        $displayData['relatedArticlesList'] = $relatedArticlesList;
        error_log_shiksha("relatedArticlesList as".print_r($relatedArticlesList,true));
        $displayData['cityTier1'] = $cityListTier1;
        $displayData['cityTier2'] = $cityListTier2;
        $Validate = $this->checkUserValidation();
        $displayData['validateuser'] = $Validate;
        $this->load->view('events/eventsCalendar',$displayData);
    }

    private function getCountries(){
        $appId = 12;
        $countryList = array();
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $tempArray = $categoryClient->getCountries($appId);
        foreach($tempArray as $temp){
            $countryList[$temp['countryID']] = $temp['countryName'];
        }
        return $countryList;
    }

    private function getCategories(){
        $appId = 12;
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $categoryList = $categoryClient->getCategoryTree($appId);
        $others = array();
        $categoryForLeftPanel = array();
        foreach($categoryList as $temp)
        {
            if((stristr($temp['categoryName'],'Others') == false))
            {
                $categoryForLeftPanel[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
            }
            else
            {
                $others[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
            }
        }
        foreach($others as $key => $temp)
        {
            $categoryForLeftPanel[$key] = array($temp[0],$temp[1]);
        }
        return $categoryForLeftPanel;
    }

    function getRecentEvents($appID , $country=1, $city='', $category = 1 , $start = 0, $count = RECENT_EVENTS_COUNT) {
        $this->init();
        if($city == 'null'){
            $city = '';
        }
        $EventCalClientObj = new Event_cal_client();
        $recentEventsList = $EventCalClientObj->getRecentEvent($appID, $start, $count, $country,$city, $category);
        echo json_encode($recentEventsList);
    }

    function getEventsByMonth($appID , $month, $year,$country=1, $cityId ='', $category = 1 ) {
        $this->init();
        $this->load->helper(array('json'));
        $EventCalClientObj = new Event_cal_client();
        if($cityId == 'null'){
            $cityId = '';
        }
        $eventsResultArr = $EventCalClientObj->getEventCountByMonth($appID, $month, $year,$country,$cityId ,$category);
        $dates = $this->createEventsList($eventsResultArr);
        echo json_encode($dates);
    }

    function getEventsByDate($appID , $date, $month, $year, $country=1, $cityId = '', $category =1, $start =0, $count=4) {
        if($cityId == 'null') {
            $cityId = '';
        }
        $this->init();
        $this->load->helper(array('json'));
        $EventCalClientObj = new Event_cal_client();
        $eventsResultArr = $EventCalClientObj->getEventListByDate($appID, $date, $month, $year, $country,$cityId, $category,$start, $count);
        //$dates = $this->createEventsList($eventsResultArr);
        echo json_encode($eventsResultArr);
    }

    function createEventsList($events) {
        $eventsList = array();
        $i = 0;
        if(is_array($events)){
            foreach($events as $event) {
                $eventsList[$i++]['Event'] = $event;
            }
        }
        return 	$eventsList;
    }

    function getCategoryEventsCountForCountry($appID = 1, $country =1,$categoryId=1) {
        echo $this->getEventCountForCategoriesByCountry($appID, $country,$categoryId);
    }

    private function getEventCountForCategoriesByCountry($appID, $country,$categoryId=1) {
        $this->init();
        $EventCalClientObj = new Event_cal_client();
        $eventsCountList = $EventCalClientObj->getCategoryEventsCountForCountry($appID, $country,$categoryId);
        $eventCategoryCount = array();
        $i=0;
        if(is_array($eventsCountList)) {
            foreach($eventsCountList as $categoryId => $categoryCount) {
                $eventCategoryCount[$i]['categoryCount'] = $categoryCount;
                $i++;
            }
        }
        return json_encode($eventCategoryCount);
    }

    function eventDetail($appID, $eventID, $refererAddEvent = '') {
        //Disabling the page and showing 404 page since this module is no longer functional
        show_404();
        exit();

        $urlseg = $this->uri->segment(1);
        $url_segments = explode("-", $urlseg);

        if ($urlseg != 'events' && $url_segments[0] != 'getEventDetail') {
            $appID = 1;
            $i = 0;
            $value = 1;
            foreach ($url_segments as $arr)
            {
                if ($arr == 'update')
                {
                    $value = $i;
                    break;
                }
                $i++;
            }
            $eventID   		=	$url_segments[($value)+1];
            $refererAddEvent =  $url_segments[($value)+2];
        }
        $this->init();
        //In case of Events, get the URL from the function getSeoUrl
        //Then, check if the entered URL is same as this one. If yes, then OK. If no, then perform a 301 redirect to the correct one
        //P.S. This will be done only in case of no pagination i.e. for the first page only.
        if (REDIRECT_URL=='live' && $eventID>0){
            $url = getSeoUrl($eventID,'event');
            $enteredURL = $_SERVER['SCRIPT_URI'];
            if($url!='' && $url!=$enteredURL){
                if($_SERVER['QUERY_STRING']!='' && $_SERVER['QUERY_STRING']!=NULL){
                    $url = $url."?".$_SERVER['QUERY_STRING'];
                    if( (strpos($url, "http") === false) || (strpos($url, "http") != 0) || (strpos($url, SHIKSHA_HOME) === 0) || (strpos($url,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($url,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($url,ENTERPRISE_HOME) === 0) ){
                        header("Location: $url",TRUE,301);
                    }
                    else{
                        header("Location: ".SHIKSHA_HOME,TRUE,301);
                    }
                }
                else{
                    if( (strpos($url, "http") === false) || (strpos($url, "http") != 0) || (strpos($url, SHIKSHA_HOME) === 0) || (strpos($url,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($url,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($url,ENTERPRISE_HOME) === 0) ){
                        header("Location: $url",TRUE,301);
                    }
                    else{
                        header("Location: ".SHIKSHA_HOME,TRUE,301);
                    }
                }
                exit;
            }
        }
        //End code for Checking URL
        $this->load->library('category_list_client');
        $EventCalClientObj = new Event_cal_client();
        $recentEvents = array();
        $displayData = array();
        $redirect = false;
        //$eventID = $appID;
        $this->userStatus = $this->checkUserValidation();
        $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $displayData = $EventCalClientObj->getEventDetail($appID, $eventID,$userId);
        $selectedCategoryName=$displayData['categoryCsv'];
        $selectedCategoryName=str_replace("1,","",$selectedCategoryName);
        $Validate = $this->checkUserValidation();
        if($displayData == '')  $redirect = true;
        else{
            if(is_array($Validate)){
                if(($Validate[0]['usergroup']!='cms' && $displayData['status_id']=="2")) $redirect = true;
            }
            else{
                if($displayData['status_id']=="2") $redirect = true;
            }
        }

        if($redirect){
            //$deleteEvent = $EventCalClientObj->deleteEvent($appID, $eventID);
            header('Location:/events/Events');
        }
        $topicId = $displayData['threadId'];
        $displayData['topicId'] = $topicId;
        $categoryClient = new Category_list_client();
        $countryList = $categoryClient->getCountries($appID);
        $categoryList = $categoryClient->getCategoryTree($appID);
        $others = array();
        $categoryForLeftPanel = array();
        foreach($categoryList as $temp) {
            if((stristr($temp['categoryName'],'Others') == false)) {
                $categoryForLeftPanel[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
            } else {
                $others[$temp['categoryID']] =	array($temp['categoryName'],$temp['parentId']);
            }
        }
        foreach($others as $key => $temp)  {
            $categoryForLeftPanel[$key] = array($temp[0],$temp[1]);
        }
        $categoryClient->getCategoryTreeArray($category, $categoryList,0,'Root');
        $categoryTree = json_encode($category);
        $displayData['category_tree'] = $categoryTree;
        $displayData['country_list'] = $countryList;
        $relatedEventsList = $EventCalClientObj->getRelatedEvent($appID,0, 3, $selectedCategoryName, $displayData['country'], $eventID);
        $relatedEvents = $this->createEventsList($relatedEventsList);
        $displayData['relatedEvents'] = json_encode($relatedEvents);
        $displayData['relatedEvents'] = $relatedEventsList;
        $Validate = $this->checkUserValidation();
        $displayData['validateuser'] = $Validate;
        $displayData['categoryForLeftPanel'] = $categoryForLeftPanel;
        $displayData['refererAddEvent'] = $refererAddEvent;
        if(is_numeric($topicId)) {
            $threadArray = 	$this->getMessageBoardThreadsForEvent($topicId, $displayData['subCategoryId']);
            $displayData['topic_messages'] = $threadArray['topic_messages'];
            $displayData['main_message'] = $threadArray['main_message'];
        }

        //below code used for beacon tracking
        $displayData['trackingpageIdentifier'] = 'eventDetailsPage';
        $displayData['trackingcountryId']=2;


        //loading library to use store beacon traffic inforamtion
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($displayData);

        $this->load->view('events/eventDetails',$displayData);
    }

    function miniCalendar() {
        //Disabling the page and showing 404 page since this module is no longer functional
        show_404();
        exit();

        $this->init();
        $this->load->view('events/eventsCalendarMini');
    }

    function loadSearchListings($appID, $keyword, $location, $start, $rows) {
        //Disabling the page and showing 404 page since this module is no longer functional
        show_404();
        exit();

        $this->init();
        $this->load->library('category_list_client');
        $this->load->library('Miscelleneous');
        $displayData = array();
        $recentEvents = array();
        $eventsList = array();

        $categoryClient = new Category_list_client();
        $countryList = $categoryClient->getCountries($appID);
        $categoryList = $categoryClient->getCategoryTree($appID);
        $categoryClient->getCategoryTreeArray($category, $categoryList,0,'Root');
        $displayData['categoryList'] = $categoryList;
        $categoryTree = json_encode($category);
        $displayData['category_tree'] = $categoryTree;
        $displayData['country_list'] = $countryList;

        $EventCalClientObj = new Event_cal_client();
        $recentEventsList = $EventCalClientObj->getRecentEvent($appID,0, RECENT_EVENTS_COUNT);
        $recentEvents = $this->createEventsList($recentEventsList);
        $displayData['recentEvents'] = json_encode($recentEvents);

        if($keyword != '' || $location != '') {
            $eventsList = $EventCalClientObj->topicSearch($appID, $keyword, $location, $start, $rows);
        }
        $displayData['eventsList'] = $eventsList;
        $displayData['keyword'] = $keyword;
        $displayData['location'] = $location;
        $displayData['start'] = $start;
        $displayData['rows'] = $rows;
        $Validate = $this->checkUserValidation();
        $displayData['validateuser'] = $Validate;
        $this->load->view('events/eventSearch', $displayData);
    }

    function searchEventsByCategory($appID=1, $board_id =1) {
        $this->init();
        $this->load->library('Miscelleneous');
        $keyword = 'MBA'; $location =''; //TODO :- Remove it
        $start = 0;
        $rows = 15; //TODO :- Should be configurable based on some constant.
        $this->loadSearchListings($appID, $keyword, $location, $start, $rows); //TODO:- Some API from AMIT need to replace it.
    }

    function searchEvents($appID=1, $board_id =0) {
        $this->init();
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : "";
        $location = isset($_REQUEST['location']) ? $_REQUEST['location'] : "";
        $start = 0;
        $rows = 15; //TODO :- Should be configurable based on some constant.
        $this->loadSearchListings($appID, $keyword, $location, $start, $rows);
    }

    function searchEventsForPage($appID ,$searchCriteria, $rows=15, $start=0, $board_id =1) {
        $this->init();
        $this->load->library('Miscelleneous');
        $displayData = array();
        $recentEvents = array();
        $checkSearchCriteria = strpos( $searchCriteria,'+');
        if($checkSearchCriteria !== false) {
            if($checkSearchCriteria === 0) {
                $location = str_replace('+', '', $searchCriteria);
                $keyword = '';
            } else {
                $searchFields = explode('+', $searchCriteria);
                $keyword = $searchFields[0];
                $location = $searchFields[1];
            }
        } else {
            $keyword = $searchCriteria;
            $location = '';
        }
        $this->loadSearchListings($appID, $keyword, $location, $start, $rows);
    }



    function showAddEvent($eventId = '',$listingType='', $listingName='') {
        //Disabling the page and showing 404 page since this module is no longer functional
        show_404();
        exit();

        $appId =1;
        $this->init();
        $this->load->library('blog_client');
        $blogClient = new Blog_client();
        $examsList = $blogClient->getExamsForProducts($appId);
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $countryList = $categoryClient->getCountries($appId);
        $categoryTreeIndia	= $this->formatCategoryTree($categoryClient->getCategoryTree($appId,'','national'));
        $categoryTreeAbroad = $this->formatCategoryTree($categoryClient->getCategoryTree($appId,'','studyabroad'));

        $listingId = '';
        $examsSelected = '';
        if($eventId != '' && is_numeric($eventId) && $listingType == '') {
            $EventCalClientObj = new Event_cal_client();
            $displayData = $EventCalClientObj->getEventDetail($appId, $eventId);
            $examsSelected = $EventCalClientObj->getExamsForEvent($appId, $eventId);
        }else if($eventId !== '' && $listingType != ''){
            $listingId = $eventId;
            $eventId = '';
        }
        $displayData['examSelected'] = $examsSelected;
        $displayData['category_tree'] = json_encode($categoryTreeIndia);
        $displayData['category_tree_abroad'] = json_encode($categoryTreeAbroad);
        $displayData['category_tree_india'] = json_encode($categoryTreeIndia);

        $displayData['country_list'] = $countryList;
        $countryToshowOnly = array();
        foreach( $displayData['country_list'] as $country )
        {
        	if($country['countryID'] <= 2) {
        		$countryToshowOnly[] = $country;
        	}
        }	
       
         $displayData['country_list'] = $countryToshowOnly;
        
        $displayData['citiesList'] = $this->getCitiesForCountry($appId, 2);
        $displayData['examsList'] = $examsList;
        $displayData['eventId'] = $eventId;
        $Validate = $this->checkUserValidation();
        $displayData['validateuser'] = $Validate;
        $displayData['listingId'] = $listingId;
        $displayData['listingType'] = $listingType;

        //below code used for beacon tracking
        $displayData['trackingpageIdentifier']='addEventPage';
        $displayData['trackingcountryId']=2;

        //loading library to use store beacon traffic inforamtion
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($displayData);

        if((!is_array($Validate)) && ($Validate == "false")) {
            header('Location:/events/Events/index');
        } else {
            $this->load->view('events/addEvent', $displayData);
        }
    }

    function formatCategoryTree($categoryList){
        $others = array();
        $categoryForLeftPanel = array();
        foreach($categoryList as $temp)
        {
            if((stristr($temp['categoryName'],'Others') == false)){
                $categoryForLeftPanel[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
            }else{
                $others[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
            }
        }
        foreach($others as $key => $temp)
        {
            $categoryForLeftPanel[$key] = array($temp[0],$temp[1]);
        }
        return $categoryForLeftPanel;
    }

    function eventAdd() {
	return;
        $appID = 1;
        $this->init();
        $userStatus = $this->checkUserValidation();
        error_log_shiksha('userSTatus'.$userStatus);
        if((!is_array($userStatus)) && ($userStatus == "false")) {
            error_log_shiksha('Validation Failed');
            $response = "Validation Failed" ;
            header('Location:/events/Events/index');
        } else {
            $EventCalClientObj = new Event_cal_client();
            $requestArray = $_POST;
            if($requestArray['All_cities']==1){
                $requestArray['cities']=$requestArray['All_cities'];
            }
            if($requestArray['fromOthers']<4){
                $requestArray['countryAssoc']='';
            }
            $requestArray['examSelected'] = array($requestArray['examSelected'],'struct');
            $requestArray['city'] = $requestArray['cities'] ;
            if($requestArray['cities'] == -1) {
                $cityArray = array();
                $cityArray['country_id'] = $requestArray['country'];
                $cityArray['city_name'] = trim($requestArray['cities_other']);
                $this->load->library('listing_client');
                $ListingClientObj = new Listing_client();
                $requestArray['city'] = $ListingClientObj->insertCity($appID,$cityArray);
            }

            if($requestArray['end_time']==''){
                $requestArray['end_time'] = '00:01:00';
            }
            if($requestArray['end_date']==''){
                $requestArray['end_date'] = $requestArray['start_date'];
                if($requestArray['end_time']==''){
                    $requestArray['end_time'] = '00:00:00';
                }
            }
            $startTimeArray = explode(":",$requestArray['start_time']);
            $startTimeArray[0] += $requestArray['start_timeStamp'];
            $startTimeArray[0] %= 24;
            $startTime = implode(":", $startTimeArray);
            $endTimeArray = explode(":",$requestArray['end_time']);
            if($requestArray['end_time'] != '00:01:00'){
                $endTimeArray[0] += $requestArray['end_timeStamp'];
                $endTimeArray[0] %= 24;
                $endTime = implode(":", $endTimeArray);
            } else {
                $endTime = '00:01:00';
            }
            $requestArray['end_date'] .= " ". $endTime;
            $requestArray['user_id'] = $userStatus[0]['userid'];
            $requestArray['displayname'] = $userStatus[0]['displayname'];
            $fromOthers = isset($requestArray['fromOthers']) ? $requestArray['fromOthers'] : 0;
            $listingType= isset($requestArray['listingType']) ? $requestArray['listingType'] : '';
            $listingId = isset($requestArray['listingId']) ? $requestArray['listingId'] : '';
            $csvCatList='';
            $tempCatArray = $requestArray['selectCategory'];
            foreach((array)$tempCatArray as $temp)
            {
                $csvCatList .= $temp.',';
            }
            $csvCatList = substr($csvCatList,0,strlen($csvCatList)-1);
            $requestArray['board_id'] = $csvCatList;
            if(isset($requestArray['event_id']) && !empty($requestArray['event_id'])) {
                $newFlag = false;
                if($requestArray['fromOthers']<4){
                    $requestArray['countryAssoc']='';
                }
                $response = $EventCalClientObj->updateEvent($appID,$requestArray, $fromOthers);
            } else {
                $newFlag = true;
                $this->load->library('message_board_client');
                $msgbrdClient = new Message_board_client();
                $topicDescription = "You can discuss on this event below";
                $requestIp = S_REMOTE_ADDR;
                $topicResult = $msgbrdClient->addTopic($appID,1,$topicDescription,$requestArray['board_id'],$requestIp,'event',0,'',0);
                $requestArray['threadId'] = $topicResult['ThreadID'];
                $response = $EventCalClientObj->addEvent($appID,$requestArray, $fromOthers, $listingId, $listingType);
            }
            //Events are not getting indexed. Hence, commenting the call for the same.
            /*if(is_numeric($response)) {
              $this->indexEvent($response);
              }*/
            if(is_numeric($response)){
                if($newFlag) {
                    $showEventWarning = 'Success';
                } else {
                    $showEventWarning = 'Update';
                }
                $url = getSeoUrl($response,'event','',array('fromOthers'=>$fromOthers,'showEventWarning'=>$showEventWarning));
                header('Location:'.$url);
            }
        }
    }
    function indexEvent($id) {
        $appID = 1;
        $this->init();
        $userStatus = $this->checkUserValidation();
        //error_log_shiksha('userSTatus'.$userStatus);
        $EventCalClientObj = new Event_cal_client();
        $response = $EventCalClientObj->getEventForIndex($appID,$id);
        foreach($response as $row)
        {
            $result = $EventCalClientObj->indexEvent(12,$row,$id);
        }
    }

    function deleteEventByCMS($eventId){
        $appId = 1;
        $this->init();
        $userStatus = $this->checkUserValidation();
        $EventCalClientObj = new Event_cal_client();
        $eventData = $EventCalClientObj->getEventDetail($appId, $eventId);
        if( ($userStatus[0]['usergroup'] == "cms") ||
                ($eventData['user_id'] == $userStatus[0]['userid']) ||
                ($userStatus[0]['usergroup'] == "enterprise")
          ){
            $deleteEvent = $EventCalClientObj->deleteEvent($appId,$eventId);
            echo $deleteEvent['Result'];
        }else{
            echo "Invalid Call";
        }
    }

    function deleteEventSubscription($eventId){
        $appId = 1;
        $this->init();
        $userStatus = $this->checkUserValidation();
        $EventCalClientObj = new Event_cal_client();
        $eventData = $EventCalClientObj->getEventDetail($appId, $eventId);
        $deleteEvent = $EventCalClientObj->deleteEventSubscription($appId,$eventId);
        echo $deleteEvent['Result'];
    }

    function reportAbuseEvent($eventId){
        $appId = 1;
        $this->init();
        $userStatus = $this->checkUserValidation();
        $userId = isset($userStatus[0]['userid'])?$userStatus[0]['userid']:0;
        if($userId != '') {
            $EventCalClientObj = new Event_cal_client();
            $eventResponse = $EventCalClientObj->reportAbuseEvent($appId,$eventId);
            echo $eventResponse;
        }
    }

    function getMyEventsAfterDeletion($eventId) {
        $appId = 1;
        $this->init();
        $userStatus = $this->checkUserValidation();
        $userId = isset($userStatus[0]['userid'])?$userStatus[0]['userid']:0;
        if($userId != '')
        {
            $deleteEvent = $this->deleteEvent($appId, $eventId);
            $myEvents = $this->getMyEvents($appId,$userId);
            $result = array('userValidate' => $userId,
                    'result' =>$deleteEvent,
                    'myEvents' => $myEvents
                    );
            echo json_encode($result);
        }
    }

    private function getMyEvents($appID, $userId) {
        $this->load->library('event_cal_client');
        $categoryId = 1;
        $start = 0;
        $rows = 2;
        $countryId = 1;
        $myEvents = array();
        $eventsClient = new Event_cal_client();
        $myEvents = $eventsClient->getMyEvents($appID, $categoryId, $start, $rows, $countryId, $userId);
        //print_r($myEvents);
        return $myEvents;
    }
    function getCitiesList($countryId) {
        $appID = 1;
        echo $this->getCitiesForCountry($appID, $countryId);
    }
    function getCitiesForCountry($appID, $countryId) {
        $this->load->library('listing_client');
        $listingClient = new Listing_client();
        return json_encode($listingClient->getCityList($appID, $countryId));
    }
    function validateCaptcha($secCode) {
        $this->init();
        $captchResult = "";
        if(verifyCaptcha('security_code',$secCode))     //security code in the file displaying the security code.
	{
		$captchResult =  "successful";
	}
 
        $result = array('captchResult'=> $captchResult);
        echo json_encode($result);
    }

    private function getMessageBoardThreadsForEvent($topicId, $CategoryId){
        // Code for comments start here
        $this->init();
        $this->userStatus = $this->checkUserValidation();
        $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $this->load->library('message_board_client');
        $msgbrdClient = new Message_board_client();
        $ResultOfDetails = $msgbrdClient->getMsgTree(12,$topicId,0,1024,0,$userId);
        $topic_reply = isset($ResultOfDetails[0]['MsgTree'])?$ResultOfDetails[0]['MsgTree']:array();
        $answerReplies = isset($ResultOfDetails[0]['Replies'])?$ResultOfDetails[0]['Replies']:array();
        if(is_array($topic_reply) && count($topic_reply) > 0) {
            $topic_messages = array();
            $i = -1;
            foreach($topic_reply as $key => $temp) {
                if($key == 0) {
                    if($temp['status'] == 'deleted') break;
                    else continue;
                }
                $found = 0;
                if(substr_count($temp['path'],'.') == 1) {
                    $i++;
                    $topic_messages[$i] = array();
                    $temp['userStatus'] = getUserStatus($topic_reply[$i]['lastlogintime']);
                    $temp['creationDate'] = makeRelativeTime($temp['creationDate']);
                    array_push($topic_messages[$i],$temp);
                    $comparison_string = $temp['path'].'.';
                    $topic_replyInner = $answerReplies;
                    foreach($topic_replyInner as $keyInner => $tempInner){
                        if(strstr($tempInner['path'],$comparison_string)){
                            $tempInner['userStatus'] = getUserStatus($topic_reply[$i]['lastlogintime']);
                            $tempInner['creationDate'] = makeRelativeTime($tempInner['creationDate']);
                            array_push($topic_messages[$i],$tempInner);
                        }
                    }
                }
            }
            $commentCount = $i + 1;
        }
        $topic_reply[0]['userStatus'] = getUserStatus($topic_reply[0]['lastlogintime']);
        $main_message = $topic_reply[0];
        if($topic_reply[0]['status'] == 'closed') {
            $closeDiscussion = 1;
        }
        return array('topic_messages' => $topic_messages,'main_message' => $topic_reply[0]);
    }

    public function getEventsForListings($appID=1,$listingType,$type_id,$start=0,$count=10){
        $this->init();
	if($type_id>0){
	        $eventClientObj=new Event_cal_client();
        	$eventList=$eventClientObj->getEventsForListing($appID,$start,$count,$listingType,$type_id);
	        echo json_encode($eventList);
        }
        else{
                echo '';
        }

    }

    public function todaysEvents($appID=1,$countryName,$location_id,$category_id,$days){
        $this->init();
        $EventCalClientObj=new Event_cal_client();
        if($days!='All'){
            if(strpos($days,"-")){
                $start_date=$days;
                $end_date=$days;
            }else{
                $end_date=date("Y-m-d",time()+(86400*($days)));
                if($days!=0){
                    $start_date=date("Y-m-d",time()+(86400));
                }else{
                    $start_date=date("Y-m-d");
                }
            }}
        if(!is_int($location_id))
            $location_id = '1';
        if(!is_int($category_id))
            $category_id = '1';
        $eventsListByType=$EventCalClientObj->getEventsByCategoryDateLocation($countryName,$category_id,$location_id,$start_date,$end_date);
        error_log_shiksha("inside todaysEvents ".print_r($eventsListByType,true));
        echo json_encode($eventsListByType);

    }

    public function viewAllEvents($countryName,$fromOthers,$category_id,$category_name,$location_id,$location_name,$start,$count,$days){
        //Disabling the page and showing 404 page since this module is no longer functional
        show_404();
        exit();

        $this->init();
        $appID=1;
        $URL=SHIKSHA_EVENTS_HOME_URL.'/events/Events/viewAllEvents/'.$countryName.'/'.$fromOthers.'/'.$category_id.'/'.$category_name.'/'.$location_id.'/'.$location_name.'/@start@/@count@/'.$days;
        $displayData = array();
        $this->load->library('Blog_client');
        $this->load->library('category_list_client');
        $eventClientObj=new Event_cal_client();
        $BlogClientObj = new Blog_client();
        $categoryClient = new Category_list_client();
        if(!is_int($location_id)) $location_id = 'All';
        if(!is_int($category_id)) $category_id = '1';
        if(!is_numeric($start)) $start = '0';
        if(!is_numeric($count)) $count = '10';
	if(!is_numeric($fromOthers)) $fromOthers = '1';

        $eventListByType=$eventClientObj->viewAllEvents($countryName,$fromOthers,$category_id,$location_id,$start,$count,$days);
        error_log_shiksha("eventListByType is ".print_r($eventListByType,true));
        /* Pagination Code start here */
        $paginationHTML = doPagination($eventListByType[0]['total_events'],$URL,$start,$count,3);
        $displayData['paginationHTML'] = $paginationHTML;
        // $displayData['paginationHTML'];
        /* Pagination Code Ends here */
        $cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
        $cityListTier2 = $categoryClient->getCitiesInTier($appId,2,2);
        $displayData['cityTier1'] = $cityListTier1;
        $displayData['cityTier2'] = $cityListTier2;
        $displayData['eventListByType']=$eventListByType;
        $displayData['location_id'] = $location_id;
        if(empty($location_name)){
            $location_name = "india";
        }
        $displayData['location_name'] = $location_name;
        $displayData['category_id'] = $category_id;
        if(empty($category_name)){
            $category_name = "All";
        }
        $displayData['category_name'] =$category_name;
        $displayData['countryName'] =$countryName;
        $displayData['fromOthers'] = $fromOthers;
        $displayData['start'] = $start;
        $displayData['count'] = $count;
        $displayData['days'] = $days;
        $countryList = $this->getCountries();
        $displayData['country_list'] = $countryList;
        $displayData['countryListById'] = $countryList;
        if($countryName=='india'){
            $locationId="2";
        }else{
            $locationId=$location_id;
        }
        $relatedArticlesList=$BlogClientObj->getRelatedBlogs($appID,$category_id,0,3,$locationId);
        $displayData['relatedArticlesList'] = $relatedArticlesList;
        error_log_shiksha("data displayed as ".print_r($displayData,true));
        $Validate = $this->checkUserValidation();
        $displayData['validateuser'] = $Validate;
        $this->load->view('events/viewAllEvents',$displayData);
    }

    public function todaysViewAllEvents($appID=1,$countryName,$from_others,$location_id,$category_id,$days){
        $this->init();
        $EventCalClientObj=new Event_cal_client();
        $start=0;
        $count=10;
	if($from_others>0){
	        $eventsListByType=$EventCalClientObj->viewAllEvents($countryName,$from_others,$category_id,$location_id,0,10,$days);
        	echo json_encode($eventsListByType);
        }
        else{
                echo '';
        }

    }

    public function studyAbroadEvents($appID=1,$from_others,$location_id,$category_id=1,$start,$count){
        $this->init();
	if($from_others>0){
	        $EventCalClientObj=new Event_cal_client();
        	$eventsListByType=$EventCalClientObj->viewAllEvents($location_id,$from_others,$category_id,$location_id,$start,$count,$days);
	        echo json_encode($eventsListByType);
        }
        else{
                echo '';
        }
    }

    public function categoryPageEvents($appID=1,$from_others,$location_id,$category_id=1,$start,$count){
        $this->init();
	if($from_others>0){
        	$EventCalClientObj=new Event_cal_client();
	        $eventsListByType=$EventCalClientObj->viewAllEvents('india',$from_others,$category_id,$location_id,$start,$count,$days);
        	echo json_encode($eventsListByType);
	}
	else{
		echo '';
	}
    }

    public function eventMigration(){
        set_time_limit(0);
        $this->init();
        $EventCalClientObj=new Event_cal_client();
        $EventCalClientObj->eventMigration();
    }

    public function subscribeEvents($appID=1,$event_id,$subscriptionType,$subscriptionTypeId,$eventTitle){
        $this->init();
        $addReqInfo = array();
        $email = $this->input->post('reqInfoEmail');
        $mobile_number = $this->input->post('reqInfoPhNumber');
        $privacySettings = $this->input->post('privacySettings');
        $category = $this->input->post('category');
        $country = $this->input->post('country');
        $locationId = $this->input->post('locationId');
        $countryName = $this->input->post('locationName');
        $keyname = $this->input->post('keyname');
        $fromOthers = $this->input->post('fromOthers');
        $firstName = $this->input->post('reqInfofirstName');
        $lastName = $this->input->post('reqInfolastName');
        $displayname = $this->input->post('reqInfofirstName');
        $captchatext = $this->input->post('captchatext');
        $this->userStatus = $this->checkUserValidation();
        $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $signedInUser = $this->userStatus;
        $categoryName=substr($category,0,strpos($category,'-'));
        if($keyname!='EVENTS'){
            if($fromOthers==0){
                $eventType="Application Submission Deadline";
            }else if($fromOthers==1){
                $eventType="Course Commencement";
            }else if($fromOthers==2){
                $eventType="Result Declaration";
            }else if($fromOthers==3){
                $eventType="Examination Date";
            }else if($fromOthers==4){
                $eventType="Form Issuance";
            }else if($fromOthers==5){
                $eventType="General";
            }
            if($countryName=='All' && $keyname!='ALLSAEVENTS'){
                $eventTitle=$eventType.' in '.$categoryName.' in '.$countryName.' cities.';
            }else{
                $eventTitle = $eventType.' in '.$categoryName.' in '.$countryName;
            }
        }
        $category=substr($category,strpos($category,'-')+1,strlen($category));
        $register_client = new Register_client();
        //user is logged in
        if(is_array($this->userStatus)) {
            $signedInUser = $this->checkUserValidation();
            if($signedInUser[0]['usergroup'] == "veryshortregistration")
            {
                echo 'register';
            }
            else
            {
                echo 'thanks';
            }
        } else {
            if(verifyCaptcha('secCodeIndex',$captchatext)) {
                $responseCheckAvail = $register_client->getinfoifexists($appId,$email,'email');
                if(is_array($responseCheckAvail)) {
                    $responseCheckAvail = $register_client->getinfoifexists($appId,$email,'email');
                    $signedInUser =  $responseCheckAvail;
                    if($mobile_number !=  $responseCheckAvail[0]['mobile']){
                        $updatedStatus = $register_client->updateUserAttribute($appId,$responseCheckAvail[0]['userid'],'mobile',$mobile_number);
                    }
                    foreach($signedInUser as $temp){
                        $userId=$temp['userid'];
                    }
                    echo 'login';
                } else {
                    $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
                    while($responseCheckAvail == 1){
                        $displayname = $firstName . rand(1,100000);
                        $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
                    }
                    $password = 'shiksha@'. rand(1,1000000);
                    $mdpassword = sha256($password);
                    $userarray['appId'] = $appId;
                    $userarray['email'] = $email;
                    $userarray['password'] = $password;
                    $userarray['mdpassword'] = $mdpassword;
                    $userarray['displayname'] = $displayname;
                    $userarray['mobile'] = $mobile;
                    $userarray['firstname'] = $firstName;
                    $userarray['lastname'] = $lastName;
                    $userarray['sourceurl'] = $sourceurl;
                    $userarray['sourcename'] = $sourcename;
                    //$userarray['quicksignupFlag'] = "quicksignupuser";
                    $userarray['resolution'] = $resolution;
                    $userarray['coordinates'] = $coordinates;
                    $userarray['usergroup'] = 'veryshortregistration';
                    $userarray['quicksignupFlag'] = "requestinfouser";
                    $userarray['desiredCourse'] = $courseInterest;
                    $addResult = $register_client->adduser_new($userarray);
                    // COOKIE HACK:-
                    $key = 'user';
                    $value = $email.'|'.$mdpassword;
                    $this->cookie($key,$value);
                    //COOKIE HACK ENDS:-
                    $this->userStatus = $register_client->getinfoifexists($appId,$email,'email');
                    $signedInUser = $this->userStatus;
                    $this->sendWelcomeMailToNewUser($email, $password,$addReqInfo,$addResult,$actiontype,$this->userStatus);
                    echo 'register';
                }
            }
        }
        $eventClientObj=new Event_cal_client();
        $subscriptionTitle=$eventTitle;
        if($userId==0){
            $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        }
	if($userId>0 && $subscriptionType!=''){
        	$subscription_id=$eventClientObj->subscribeEvents($appID,$userId,$event_id,$subscriptionType,$subscriptionTitle,$mobile_number,$email,$privacySettings,$category,$country,$fromOthers,$locationId);
	}
	else{
		show_404();
	}
        if($subscription_id!=-1){
            echo 'AlreadySubscribed';
        }
    }

    private function cookie($key, $value) {
        $value1 = $value . '|pendingverification';
        //setcookie($key,$value1,time() + 2592000 ,'/',COOKIEDOMAIN);
        $this->init();
    }

    private function sendWelcomeMailToNewUser($email, $password, $addReqInfo,$addResult,$actiontype,$userinfo) {
        $this->init();
        $alerts_client = new Alerts_client();
        $data = array();
        $isEmailSent=0;
        try {
            $subject = "Your Shiksha Account has been generated";
            $data['usernameemail'] = $email;
            $data['userpasswordemail'] = $password;
            $content = $this->load->view('user/RegistrationMail',$data,true);
            $response=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,$contentType="html");

            /* For Shiksha Inbox. */
            $this->load->library('Mail_client');
            $mail_client = new Mail_client();
            $receiverIds = array();
            array_push($receiverIds,$addResult['status']);
            $body = $content;
            $content = 0;
            $sendmail = $mail_client->send($appId,1,$receiverIds,$subject,$body,$content);

        } catch (Exception $e) {
            // throw $e;
            error_log('Error occoured sendWelcomeMailToNewUser' .
                    $e,'MultipleApply');
        }
    }
    public function sendMailByCategory() {
        $this->init();
        $appId=1;
        $this->load->library('Event_cal_client');
        $eventClientObj = new Event_cal_client();
        $eventClientObj->getAllSubscriptionsEmail($appId);
    }

    public function sendSMSByCategory() {
        $this->init();
        $appId=1;
        $this->load->library('Event_cal_client');
        $eventClientObj = new Event_cal_client();
        $eventClientObj->getAllSubscriptionsSMS($appId);
    }
}
?>

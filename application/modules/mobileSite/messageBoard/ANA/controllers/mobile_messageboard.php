<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


// ==================================================================
//
// [ Section description goes here ... ]
//
// ------------------------------------------------------------------

class mobile_messageboard extends ShikshaMobileWebSite_Controller {

	private $userStatus;

	private $login_client;

	private $register_client;

	private $alerts_client;

	private $messageBoardProxy;

	private $message_board_client;

	public $wurfl_obj;

	public $logged_in_user;

	public $site_current_url;

	public $site_current_refferal;

	function __construct() 
	{
		parent::__construct();
		$this->load->library(array(
			'miscelleneous',
			'category_list_client',
			'listing_client',
			'register_client',
			'alerts_client',
			'lmsLib',
			'Login_client',
			'messageBoardProxy',
			'form_validation',
			'message_board_client',
			'RelatedClient'
		));	

		$this->load->helper(array('url','image','shikshautility'));

		$this->load->config('mcommon/mobi_config');

		$this->userStatus = $this->checkUserValidation();
		$this->login_client = new Login_client();
		$this->register_client = new Register_client();
		$this->alerts_client = new Alerts_client();
		$this->message_board_client = new message_board_client();
		$this->wurfl_obj = $this->ci_mobile_capbilities;
		$this->messageBoardProxy  = new messageBoardProxy($this->wurfl_obj);


		$this->logged_in_user 			= $this->logged_in_user_array;
		$this->site_current_url 		= $this->shiksha_site_current_url;
		$this->site_current_refferal 	= $this->shiksha_site_current_refferal;

		$this->load->library('form_validation');
		// Need Hack to work form_validation Lib with HMVC
		$this->form_validation->CI =& $this;

	}

	/**
	 * [render_messageboard_homepage description]
	 * @return [type] [description]
	 */

	public function render_messageboard_homepage($categoryId,$flag_UnansweredTopics='1',$countryId=2,$myqnaTab='answer',$actionDone='default',$start=0,$rows=10)
	{
		global $shiksha_site_current_refferal;

		$msgbrdClient = $this->message_board_client;
		$arrayOfRes = array();
		$arrayOfUsers = array();
		$appId = "12"; // :-(
		if($flag_UnansweredTopics == '3')
		{
			$Result = $msgbrdClient->getUnansweredTopics($appId,$categoryId,$start,$rows,$countryId);
		}
		else
		{
			$Result = $msgbrdClient->getRecentPostedTopics($appId,$categoryId,$start,$rows,$countryId);
		}

		$count = is_array($Result[0])?$Result[0]['totalCount']:0;
		$countAnswered = isset($Result[0]['totalAnswered'])?$Result[0]['totalAnswered']:0;
		$arrayOfRes = is_array($Result[0])?$Result[0]['results']:array();
		$categoryCountry = is_array($Result[0])?$Result[0]['categoryCountry']:array();
		$threadIdList = '';

		if(is_array($arrayOfRes))
		{
			for($i=0;$i<count($arrayOfRes);$i++)
			{
				$currentUserId = $arrayOfRes[$i]['userId'];
				$found = 0;
				$urlForTopic = site_url('messageBoard/MsgBoard/topicDetails').'/'.$arrayOfRes[$i]['msgId'];
				$userProfile = site_url('getUserProfile').'/'.$arrayOfRes[$i]['displayname'];
				$arrayOfRes[$i]['creationDate'] = makeRelativeTime($arrayOfRes[$i]['creationDate']);
				$arrayOfRes[$i]['editorPickFlag'] = is_array($arrayOfRes[$i])?$arrayOfRes[$i]['editorPickFlag']:0;
				$userStatus = getUserStatus($arrayOfRes[$i]['lastlogintime']);
				$arrayOfRes[$i]['urlForTopic'] = $urlForTopic;
				if(in_array($arrayOfRes[$i]['userId'],$userFriends))
				{
					$arrayOfUsers[$currentUserId]['isFriend'] = 'true';
				}
				else
				{
					$arrayOfUsers[$currentUserId]['isFriend'] = 'false';
				}		
				$arrayOfUsers[$currentUserId]['userStatus'] = $userStatus;
				$arrayOfUsers[$currentUserId]['userImage'] = $arrayOfRes[$i]['userImage'];
				$arrayOfUsers[$currentUserId]['displayname'] = $arrayOfRes[$i]['displayname'];
				$arrayOfUsers[$currentUserId]['level'] = $arrayOfRes[$i]['level'];
				$arrayOfUsers[$currentUserId]['userProfile'] = $userProfile;
				$threadIdList .= ($threadIdList=='')?$arrayOfRes[$i]['msgId']:",".$arrayOfRes[$i]['msgId'];
			}

		}

		//Now, get the User answer flag and editorial pick
		$userResult = $msgbrdClient->getUserFlag($appId,$userId,$userGroup,$threadIdList);
		if(is_array($userResult)){
			for($i=0;$i<count($userResult);$i++){
				for($j=0;$j<count($arrayOfRes);$j++){
					if($arrayOfRes[$j]['msgId'] == $userResult[$i]['msgId'])
					{
						$arrayOfRes[$j]['flagForAnswer'] = isset($userResult[$i]['flagForAnswer'])?$userResult[$i]['flagForAnswer']:0;
						$arrayOfRes[$j]['editorPickFlag'] = isset($userResult[$i]['editorPickFlag'])?$userResult[$i]['editorPickFlag']:0;
					}
				}
			}
		}

		$topics = array('results' => $arrayOfRes,
			'arrayOfUsers' => $arrayOfUsers,
			'totalCount'=> $count,
			'totalAnswered'=>$countAnswered,
			'newRepliesCount' => $newRepliesCount,
			'categoryCountry'=>$categoryCountry,
			'pcategoryId' =>$categoryId,
			'pflag_UnansweredTopics' => $flag_UnansweredTopics,
			'pstart'=> $start,
			'prows' => $rows,
			'pcountryId'=>$countryId,
			'shiksha_site_current_refferal'=>$shiksha_site_current_refferal
		);
		$topics['m_meta_title'] = 'Ask and Answer - Education Career Forum Community – Study Forum – Education Career Counselors – Study Circle -Career Counseling';
		$topics['m_meta_description'] = 'Ask Questions on various education and career topics or find answers to questions related to education and career options from our education counselors and users in this education and career forum community.';
		$topics['m_meta_keywords']= 'Ask and Answer, Education, Career Forum Community, Study Forum, Education Career Counselors, Career Counseling, study circle, Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships, shiksha';
		//_p($topics);die;
		$topics['userStatus'] = $this->userStatus;
		$this->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$topics['categoryRepository'] = $categoryBuilder->getCategoryRepository();
		$topics['boomr_pageid'] = "ana_listing";
		$this->messageBoardProxy->renderView('ANA','ana_homepage',$topics);
	}

	private function getCountries(){
		$appId = 12;
		$countryList = array();
		$this->init(array('category_list_client'),'');
		$categoryClient = new Category_list_client();
		$tempArray = $categoryClient->getCountries($appId);
		foreach($tempArray as $temp){
			$countryList[$temp['countryID']] = $temp['countryName'];
		}
		return $countryList;
	}

	private function getCategories(){
		$appId = 12;
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

	function getTitle($val='',$storeCache='false'){
		$start = 0;
		$num   = 10;
		$res = array();
		$str = '';
		//echo "sssssssss".$val;
		if(isset($_POST['title'])){
			$title = $this->input->post('title');
			$str = preg_replace('/(\s)/', '+', $title);
			$res[title][p] = '0';
		}else if($val!=''){
			$str = preg_replace('/(\s)/', '+', $val);
			$res[title][p] = '1';
		}

		$key = md5('getGoogleRelatedQ'.$str);
		$relatedFile = "relatedQuestions/".$key.".html";
		$makeGoogleCall = true;
		if(file_exists($relatedFile) && $storeCache=='true'){
			$last_modified = filemtime($relatedFile);
			$nowTime = time();
			if(($nowTime - $last_modified) < 864000)
				$makeGoogleCall = false;
		}
		if(!$makeGoogleCall && $storeCache=='true'){
			$fileContent = file_get_contents($relatedFile);
			return json_decode($fileContent,true);
		}

		$final = simplexml_load_file("http://www.google.com/cse?q=".$str."&requiredfields=isMasterList:present&sa=%C2%A0&start=".$start."&num=".$num."&cx=004839486360526637444%3Aznooniugfqo&cof=FORID%3A10%3BNB%3A1&output=xml&ie=UTF-8#1016");
		if(!empty($final->RES->R[1]->T)){
			$data = $final;
		}else{
			$year = date('Y');
			$month = date('m');
			$day = date('d');
			$start_date = $this->calculateJulianTime($year,$month,$day);

			$old = date('Y-m-d', strtotime('-30 days'));
			$info = explode('-',$old);

			$yearOld  =  $info[0];
			$monthOld =  $info[1];
			$dayOld   =  $info[2];
			$end_date =  $this->calculateJulianTime($yearOld,$monthOld,$dayOld);

			$data = simplexml_load_file("http://www.google.com/cse?q=".$str."&sa=%C2%A0&start=".$start."&num=".$num."&cx=004839486360526637444%3Aznooniugfqo&cof=FORID%3A10%3BNB%3A1&output=xml&ie=UTF-8#1016");
		}

		if(!empty($data->RES->R[0]->T)){
			foreach($data->RES->R as $info){
				$res[title][] = $info;

			}
			//Modified by ANkur on 30 Aug to add the Related questions from Google in a file. Using this, we will make the call to get related questions once every 10 days
			if($storeCache=='true'){
				$fp=fopen($relatedFile,'w+');
				fputs($fp,json_encode($res));
				fclose($fp);
			}
			return $res;
		}
		else{
			//Modified by ANkur on 30 Aug to add the Related questions from Google in a file. Using this, we will make the call to get related questions once every 10 days
			if($storeCache=='true'){
				$fp=fopen($relatedFile,'w+');
				fputs($fp,json_encode('true'));
				fclose($fp);
			}
			return true;
		}
	}
	function getDataFromGoogleSearch($questionTitle,$topicId,$bestAns='false',$googleSearch='false',$jsCall='false',$storeCache='false'){
		$googleRes = array();
		if(isset($_REQUEST['start'])){
			$start = $_REQUEST['start'];
		}else{
			$start =  0;
		}
		if(isset($_REQUEST['num'])){
			$num =  $_REQUEST['num'];
		}else{
			$num   = 10;
		}
		if($googleSearch=='true'){
			$bestAns = 'true';
			$str1 = trim(preg_replace('/(["\'@\^%\(\)])/', '', trim($questionTitle)));
			$str = trim(preg_replace('/(\s)/', '+', $str1));
			$googleRes['searchString']= $str;
			$final = simplexml_load_file("http://www.google.com/cse?q=".$str."&sa=%C2%A0&start=".$start."&num=".$num."&cx=004839486360526637444%3Aznooniugfqo&cof=FORID%3A10%3BNB%3A1&output=xml&ie=UTF-8#1016");
			if(!empty($final->RES->R[0]->T)){
				$data = $final;
			}
			if(!empty($data->RES->R[0]->T)){
				foreach($data->RES->R as $info){
					$xmlArray[title][] = $info;
				}
			}
			$googleRes['totalRes'] = (array)$final->RES->M;
			if(empty($googleRes['totalRes'])){
				//$googleRes['noResult'] = (array)$final->Q;
				$googleRes['noResult'] = (array)$final->PARAM[0]['original_value'];
				$tmp = (array)$final->PARAM[0]['original_value'];
				if($tmp[0]!=$questionTitle){
					$googleRes['noResult']=array($questionTitle);
				}
				$googleRes['googleSuggestion']= (array)$final->Spelling->Suggestion;
				if(empty($googleRes['noResult'])){
					$googleRes['noResult'] = array($questionTitle);
					$googleRes['specialcase']=array('yes');
				}
			}
		}
		else
		{
			$xmlArray = $this->getTitle($questionTitle,$storeCache);
		}
		$msgbrdClient = new Message_board_client();
		$finalArray = $msgbrdClient->calViewAnswerComment($xmlArray,$topicId,$bestAns,$googleSearch);
		$finalArray = json_decode($finalArray);
		foreach($finalArray as $index => $member){
			$temp =array();
			if(is_object($member)){
				foreach($member as $tIndex => $tmember){
					$temp[$tIndex] = $tmember;
				}
			}else{
				$temp = $member;
			}
			$googleRes[$index] = $temp;
		}
		if($jsCall=='true'){
			echo json_encode($googleRes);
		}
		else
		{
			return $googleRes;
		}
	}
	function getCommentCookieContent(){
		$tempStr = $_COOKIE['commentContent'];
		$detailsArray = explode('#$@!*$%^SHIKSHA',$tempStr);
		$arrayLength = count($detailsArray);
		$returnArray = array();
		$returnArray['questionText'] = '';
		for($i=0;$i<($arrayLength-6);$i++){
			$returnArray['questionText'] .= $detailsArray[$i];
			if(($arrayLength-6 > 1) && ($i < ($arrayLength-7)))
				$returnArray['questionText'] .= '#$@!*$%^SHIKSHA';
		}
		$returnArray['alertResult'] = 0;
		if($detailsArray[$arrayLength-6] == 'on')
			$returnArray['alertResult'] = 1;

		$returnArray['csvCatList'] = $detailsArray[$arrayLength-5];
		$returnArray['csvCountryList'] = $detailsArray[$arrayLength-4];
		$returnArray['listingType'] = $detailsArray[$arrayLength-3];
		$returnArray['listingTypeId'] = $detailsArray[$arrayLength-2];
		$returnArray['addedflag'] = $detailsArray[$arrayLength-1];
		return $returnArray;
	}
	public function get_topic_detail($topicId,$seoDetails=-1,$srcPage='askHome',$actionDone='',$parmeterValues=-1,$start=0,$count=10,$filter='reputation')
	{

		if ( $this->uri->segment(1) == 'helpline')
		{
			$this->load->library('Seo_client');
			$Seo_client = new Seo_client();
			$flag_seo_url = $Seo_client->getSeoUrlNewSchema($topicId,'question');
			if ($flag_seo_url[0] == 'false')
			{
				$title = $flag_seo_url[1];
				$title = seo_url_lowercase($title,"-",'','110');
				$seoDetails = ($seoDetails == '-1') ? "-All" : '-All';
				$srcPage = ($srcPage != 'askHome') ? "-" . $srcPage : '-askHome';
				$actionDone = ($actionDone != '') ? "-" . $actionDone : '-All';
				$parmeterValues = ($parmeterValues != '-1') ? "-" . $parmeterValues : '-All';
				$start = ($start != "0") ? "-" . $start : '-0';
				$count = ($count != '10') ? "-" . $count : '-10';
				$url=SHIKSHA_ASK_HOME_URL."/".$title."-qna-".$topicId;
				header("Location: $url",TRUE,301);
				exit;
			}
		}
		$urlseg = $this->uri->segment(1);
		$url_segments = explode("-", $urlseg);
		if ($url_segments[0] == 'getTopicDetail' && ($topicId=='' || $topicId==0)) {
			$url = SHIKSHA_ASK_HOME;
			header("Location: $url",TRUE,301);
			exit;
		}

		if ($url_segments[0] != 'getTopicDetail') {
			$i = 0;
			$value = 1;
			foreach ($url_segments as $arr)
			{
				if ($arr == 'qna')
				{
					$value = $i;
				}
				$i++;
			}
			$topicId   		=	$url_segments[($value)+1];
			$seoDetails		=	$url_segments[($value)+2];
			$srcPage		=	$url_segments[($value)+3];
			$actionDone		=	$url_segments[($value)+4];
			$parmeterValues	=	$url_segments[($value)+5];
			$start			=	$url_segments[($value)+6];
			$count			=	$url_segments[($value)+7];
			$filter 		=	$url_segments[($value)+8];
			if ((!isset($seoDetails))||($seoDetails == 'all'))
			{
				$seoDetails = -1;
			}
			if ((!isset($srcPage))||($srcPage == 'askhome'))
			{
				$srcPage = 'askHome';
			}
			if ((!isset($actionDone))||($actionDone == 'all'))
			{
				$actionDone = '';
			}
			if ((!isset($parmeterValues))||($parmeterValues == 'all'))
			{
				$parmeterValues = -1;
			}
			if (!isset($start))
			{
				$start = 0;
			}
			if (!isset($count))
			{
				$count = 10;
			}
			if (!isset($filter))
			{
				$filter = 'reputation';
			}
		}
		if ($start==0 && $srcPage=='askHome' && $actionDone=='' && $parmeterValues==-1 && $filter=='reputation' && REDIRECT_URL=='live'){
			$url = getSeoUrl($topicId,'question');
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

		$appId = 12;
		$topicCountryId = 1;$closeDiscussion = 0;$displayData = array();$relatedTopics = array();$main_message = array();
		$alertName = 'on';$alertId = '';$alreadyAnswer = 0;
		$arrayOfParameters=array();
		/*-----------------------------------------*/
		if((int)$parmeterValues !== -1){
			$parmeterValues = base64_decode($parmeterValues);
			$parmeterValues = explode('#',$parmeterValues);
			$key = '';$value='';
			foreach($parmeterValues as $value1){
				list($key,$value) = preg_split('~',$value1);
				$arrayOfParameters[$key] = $value;
			}
			$arrayOfParameters['answerUserId'] = -1;
			$arrayOfParameters['sameUserQuestion'] = 'false';
		}
		$msgbrdClient = new Message_board_client();
		$alertClient = new Alerts_client();

		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$avatarURL = isset($this->userStatus[0]['avtarurl'])?$this->userStatus[0]['avtarurl']:"";

		$RelatedClient = new RelatedClient();
		/*-------------------------------------------------*/
		if($topicId=='' || $topicId<=0 || !is_numeric($topicId) || !preg_match('/^\d+$/',$topicId)){
		    show_404();
		}
		$count=10;
		if($topicId>0  && preg_match('/^\d+$/',$topicId)){
			$resultTemp = $msgbrdClient->getCountCommentsToBeDisplayed($appId,$topicId);
			if(is_array($resultTemp)){
				$count = $resultTemp['count'];
			}
		}
		$ResultOfDetails = array();
		if($topicId>0)
			$ResultOfDetails = $msgbrdClient->getMsgTree($appId,$topicId,$start,$count,1,$userId,$userGroup,$filter);
		$answerReplies = isset($ResultOfDetails[0]['Replies'])?$ResultOfDetails[0]['Replies']:array();
		$topic_reply = isset($ResultOfDetails[0]['MsgTree'])?$ResultOfDetails[0]['MsgTree']:array();
		if(is_array($topic_reply))
			$fromOthersTopic = $topic_reply[0]['fromOthers'];
		$totalNumOfRows = isset($ResultOfDetails[0]['totalRows'])?$ResultOfDetails[0]['totalRows']:0;
		$totalComments = isset($ResultOfDetails[0]['totalComments'])?$ResultOfDetails[0]['totalComments']:0;
		$mainAnsCount = isset($ResultOfDetails[0]['mainAnsCount'])?$ResultOfDetails[0]['mainAnsCount']:0;
		$CategoryList = isset($ResultOfDetails[0]['CategoryIds'])?$ResultOfDetails[0]['CategoryIds']:0;

		$catcountry = isset($ResultOfDetails[0]['categoryCountry'])?$ResultOfDetails[0]['categoryCountry']:array();
		$questionCatCountry = '';

		if(isset($catcountry[0]['category']) && isset($catcountry[0]['country']))
			$questionCatCountry = $catcountry[0]['category']."-".$catcountry[0]['country']." ";
		$levelVCard = isset($ResultOfDetails[0]['levelVCard'])?$ResultOfDetails[0]['levelVCard']:array();
		$levelVCardArray = array();
		for($i=0;$i<count($levelVCard);$i++)
		{
			$userID = $levelVCard[$i]['userid'];
			$levelVCardArray[$userID]['level'] = $levelVCard[$i]['ownerLevel'];
			$levelVCardArray[$userID]['vcardStatus'] = $levelVCard[$i]['vcardStatus'];
			$levelVCardArray[$userID]['levelP'] = $levelVCard[$i]['ownerLevelP'];
		}
		$expertArray = isset($ResultOfDetails[0]['expertArray'])?$ResultOfDetails[0]['expertArray']:array();
		$userExpertArray = array();
		for($i=0;$i<count($expertArray);$i++)
		{
			$userID = $expertArray[$i]['userid'];
			$userExpertArray[$userID]['expertStatus'] = $expertArray[$i]['expertStatus'];
			$userExpertArray[$userID]['signature'] = $expertArray[$i]['signature'];
			$userExpertArray[$userID]['designation'] = $expertArray[$i]['designation'];
			$userExpertArray[$userID]['aboutCompany'] = $expertArray[$i]['aboutCompany'];
			$userExpertArray[$userID]['instituteName'] = $expertArray[$i]['instituteName'];
			$userExpertArray[$userID]['highestQualification'] = $expertArray[$i]['highestQualification'];
		}
		//End Modifications

		$questionUserId = isset($topic_reply[0]['userId'])?$topic_reply[0]['userId']:-1;
		$categoryForLeftPanel = $this->getCategories();
		$parentCategories = array();
		$parentCategoriesNew = array();
		foreach($CategoryList as $categoryId){
			if($categoryForLeftPanel[$categoryId][1] == 1){
				array_push($parentCategories,$categoryId);
			}
			array_push($parentCategoriesNew,$categoryId);
		}
		$selectedCategoryName = (count($parentCategories) > 0)?$categoryForLeftPanel[$parentCategories[0]][0]:$categoryForLeftPanel[$CategoryList[0]][0];
		$selectedSubCategoryName = (count($parentCategories) > 0)?$categoryForLeftPanel[$parentCategoriesNew[1]][0]:$categoryForLeftPanel[$CategoryList[1]][0];
		$displayData['selectedSubCategoryName'] = $selectedSubCategoryName;

		if(($srcPage==='askQuestion') && (($userId == 0) || ($userId !== $questionUserId))){
			$questionInfo = isset($topic_reply[0])?$topic_reply[0]:'';
			if(is_array($questionInfo)){
				$seoUrl = getSeoUrl($questionInfo['threadId'],'question',$questionInfo['msgTxt']);
			}
			header ('HTTP/1.1 301 Moved Permanently');
			header ('Location: '.$seoUrl);
			exit;
		}

		if($userId != 0){
			$alertCountForCreateTopic = 0;
		}

		$categoryId = is_array($catcountry)?($catcountry[0]['categoryId']):0;
		$staus = is_array($topic_reply[0])?($topic_reply[0]['categoryId']):0;
		if((count($topic_reply) <=0) || ($topic_reply[0]['status'] == 'deleted')){
			$listingClient = new Listing_client();
			$listingClient->deleteMsgbrdListing($appId,'msgbrd',$categoryId,$topicId);
		}

		//Get the related data and widget alert only in case of questions
		if($fromOthersTopic == 'user'){
			$selectedCategoryName = $topic_reply[0]['categoryName'];
			$categoryCrumb = $topic_reply[0]['categoryName'];
			$relatedData = '';
			$similarQuestions = array();
			//Code added to add Category and country to the Related questions found
			$relatedQuesCsv = '';
			if(isset($similarQuestions['resultList']) && (count($similarQuestions['resultList']) > 0)){
				for($i=0;$i<count($similarQuestions['resultList']);$i++)
					$relatedQuesCsv .= ($relatedQuesCsv=='')?$similarQuestions['resultList'][$i]['typeId']:",".$similarQuestions['resultList'][$i]['typeId'];
				$relatedCatCoun = $msgbrdClient->getCategoryCountry($appId,$relatedQuesCsv);
				$relatedCatCoun = is_array($relatedCatCoun)?$relatedCatCoun:array();
				for($i=0;$i<count($similarQuestions['resultList']);$i++){
					for($j=0;$j<count($relatedCatCoun);$j++){
						if($similarQuestions['resultList'][$i]['typeId'] == $relatedCatCoun[$j]['msgId']){
							$similarQuestions['resultList'][$i]['category'] = $relatedCatCoun[$j]['category'];
							$similarQuestions['resultList'][$i]['country'] = $relatedCatCoun[$j]['country'];
							$similarQuestions['resultList'][$i]['categoryId'] = $relatedCatCoun[$j]['categoryId'];
							$similarQuestions['resultList'][$i]['countryId'] = $relatedCatCoun[$j]['countryId'];
						}
					}
				}
			}
			//End code for related questions
			$WidgetStatus = $alertClient->getWidgetAlert($appId,$userId,8,'byComment',$topicId);
			$alertCount = isset($WidgetStatus['alertCount'])?$WidgetStatus['alertCount']:0;
			if(isset($WidgetStatus['result']) && ($WidgetStatus['result'] == 0)){
				$alertNameValue = "messageBoard-".$topic_reply[0]['msgTxt'];
			}
			elseif(isset($WidgetStatus['result']) && ($WidgetStatus['result'] != 0)){
				$alertNameValue = $WidgetStatus['state'];
				if(strcmp($alertNameValue,'on') == 0){
					$alertNameValue = 'off';  //This is for checking whther alert is already created or not.
				}else{
					$alertNameValue = 'on';
				}
				$alertId = $WidgetStatus['alert_id'];
			}
		}
		$displayData['topicId'] = $topicId;
		$bestAnsFlagForThread = 0;
		$mainAnswerId = 0;
		if(is_array($topic_reply) && count($topic_reply) > 0)
		{
			$topic_messages = array();
			$i = -1;
			foreach($topic_reply as $key => $temp){
				if($key == 0){
					if((count($arrayOfParameters) > 0) && ($userId == $temp['userId'])){
						$arrayOfParameters['sameUserQuestion'] = true;
					}
					if($temp['status'] == 'deleted'){
						break;
					}else{
						continue;
					}
				}
				if($temp['bestAnsFlag'] == 1){
					$bestAnsFlagForThread = 1;
				}

				$found = 0;
				if(substr_count($temp['path'],'.') == 1){
					if((count($arrayOfParameters) > 0) && ($arrayOfParameters['answerId'] == $temp['msgId'])){
						$arrayOfParameters['answerUserId'] = $temp['userId'];
					}
					$i++;
					$mainAnswerId = $temp['msgId'];
					$topic_messages[$i] = array();
					$temp['userStatus'] = getUserStatus($topic_reply[$i]['lastlogintime']);
					if($fromOthersTopic == 'user')
						$temp['creationDate'] = makeRelativeTime($temp['creationDate']);
					array_push($topic_messages[$i],$temp);
					$comparison_string = $temp['path'].'.';
					$topic_replyInner = $answerReplies;
					foreach($topic_replyInner as $keyInner => $tempInner){
						if(strstr($tempInner['path'],$comparison_string)){

							if($fromOthersTopic == 'user'){
								$tempInner['creationDate'] = makeRelativeTime($tempInner['creationDate']);
							}
							else{
								if($tempInner['parentId']==$mainAnswerId){	//In case the parent of the entity is different
									$tempInner['parentDisplayName'] = '';
								}
							}
							array_push($topic_messages[$i],$tempInner);
						}
					}
				}
			}
			if($topic_reply[0]['status'] != 'deleted'){
				$displayData['topic_messages'] = $topic_messages;
				$topic_reply[0]['userStatus'] = getUserStatus($topic_reply[0]['lastlogintime']);
				$topic_reply[0]['creationDate'] = makeRelativeTime($topic_reply[0]['creationDate']);
				$main_message = $topic_reply[0];
				$alreadyAnswer = isset($main_message['alreadyAnswered'])?$main_message['alreadyAnswered']:0;

				if($topic_reply[0]['status'] == 'closed')
					$closeDiscussion = 1;
			}

			if($main_message['listingTypeId'] > 0){
				$listingClient = new Listing_client();
				$listingDetailsForQuestion = $listingClient->getListingDetailForSms($appId,$main_message['listingTypeId'],$main_message['listingType']);
				$displayData['listingDetailsForQuestion'] = isset($listingDetailsForQuestion[0])?$listingDetailsForQuestion[0]:0;
			}
		}
		if(isset($ResultOfDetails[0]['MainQuestion'])){
			$main_message_temp = $ResultOfDetails[0]['MainQuestion'][0];
			if($main_message_temp['bestAnsFlag'] == 1){
				$bestAnsFlagForThread = 1;
			}
		}
		if($fromOthersTopic == 'user'){
			$_POST['title'] = $main_message['msgTxt'];
			$tmp = '';$m=0;
			$linkQuestionResult = $msgbrdClient->linkQuestionResult($topicId);
			$linkQuestionViewCount = json_decode($msgbrdClient->calViewAnswerComment($linkQuestionResult,$tmp,'true',1));
			foreach($linkQuestionResult['title'] as $res){
				$linkQuestionViewCount->msgTitle[$m]=$res[S];
				$linkQuestionViewCount->link[$m]=$res[U];
				$m++;
			}
			$linkedQuestionIds = array();
			$linkedQuestionCatCountry = array();
			$linkedQuestionCreationDate = array();
			$linkedQuestionBestAnswerFlag = array();
			$i=0;
			foreach($linkQuestionViewCount->link as $url){
				$urlArray = explode("/",$url);
				$linkedQuestionIds[] = $urlArray['4'];
				$linkQuestionViewCount->linkedQuestionId[$i] = $urlArray['4'];
				$i++;
			}
			$linkedQuestionBestAnswerFlag = $linkQuestionViewCount->bestAnsFlag;
			$linkQuestionViewCount->bestAnsFlag = $linkedQuestionBestAnswerFlag;
			$displayData['linkQuestionViewCount']=$linkQuestionViewCount;
			$googleRes = $this->getDataFromGoogleSearch($main_message['msgTxt'],'','true','false','false','true');
			$googleResIds = array();
			$googleResCatCountry = array();
			$googleResCreationDate = array();
			$googleResBestAnswerFlag = array();
			foreach($googleRes['link'] as $url){
				$urlArray = explode("/",$url);
				$googleResIds[] = $urlArray['4'];
			}
			$googleResBestAnswerFlag = $googleRes['bestAnsFlag'];
			$googleRes['bestAnsFlag'] = $googleResBestAnswerFlag;
			$displayData['googleRes'] = $googleRes;
			//End Code For related questions From Google

			//Start Code to get Description of the question if it exists(added during QnA rehash)
			$displayData['questionDescription'] = $msgbrdClient->getDescriptionForQuestion($topicId);
		}
		//End Code to get Title of the question
		if($ResultOfDetails['0']['isMasterList']!=0)
			$displayData['isMasterList'] = 'present';
		else
			$displayData['isMasterList'] = 'notpresent';

		$displayData['similarQuestions'] = $similarQuestions;
		$displayData['mainAnsCount'] = $mainAnsCount;
		$displayData['userGroup'] = $userGroup;
		$displayData['alreadyAnswer'] = $alreadyAnswer;
		$displayData['CategoryList'] = $CategoryList;
		$displayData['totalNumOfRows'] = $totalNumOfRows;
		$displayData['selectedCategoryName'] = $selectedCategoryName;
		$csvCatList = '';
		$returnArray = $this->getCommentCookieContent();
		$displayData['questionText'] = isset($returnArray['questionText'])?$returnArray['questionText']:'';
		$displayData['categoryId'] = $categoryId;
		$displayData['bestAnsFlagForThread'] = $bestAnsFlagForThread;
		$displayData['alertCountForCreateTopic'] = $alertCountForCreateTopic;
		$displayData['WidgetStatus'] = $WidgetStatus;
		$displayData['csvCatList'] = $csvCatList;
		$commentData['fromOthers'] = 'user';
		$displayData['alertCount'] = $alertCount;
		$displayData['main_message'] = $main_message;
		$displayData['alertId'] = $alertId;
		$displayData['appId'] = $appId;
		$displayData['topicId'] = $topicId;
		$displayData['categoryCrumb'] = $categoryCrumb;
		$displayData['closeDiscussion'] = $closeDiscussion;
		$displayData['categoryForLeftPanel'] = json_encode($categoryForLeftPanel);
		$displayData['selectedCategoryName'] = $selectedCategoryName;
		$displayData['userId'] = $userId;
		$displayData['alertNameValue'] = $alertNameValue;
		$displayData['tabselected'] = $tabselected;
		$displayData['validateuser'] = $this->userStatus;
		$displayData['relatedObj'] = $relatedData;
		$displayData['srcPage'] = $srcPage;
		$displayData['arrayOfParameters'] = $arrayOfParameters;
		$displayData['actionDone'] = $actionDone;
		$displayData['start'] = $start;
		$displayData['count'] = $count;

		//Modifications for Task on 24 March
		$displayData['questionCatCountry'] = $questionCatCountry;
		$displayData['catCountArray'] = $catcountry;
		$displayData['levelVCard'] = $levelVCardArray;
		$displayData['expertArray'] = $userExpertArray;
		$displayData['infoWidgetData'] = $this->getDateForInfoWidget();
		$displayData['userImageURL'] = $avatarURL;
		$displayData['averageTimeToAnswer'] = $this->getAverageTimeForAswer();
		$displayData['totalComments'] = $totalComments;
		$displayData['filterSel'] = $filter;
		if($actionDone==''){
			$actionDone = 'default';
		}
		if ($url_segments[0] != 'getTopicDetail') {
			$this->load->library('Seo_client');
			$Seo_client = new Seo_client();
			if($fromOthersTopic == 'user')
				$flag_seo_url = $Seo_client->getSeoUrlNewSchema($topicId,'question');
			else
				$flag_seo_url = $Seo_client->getSeoUrlNewSchema($topicId,'discussion');
			$title = $flag_seo_url[1];
			if($filter == 'reputation'){
				$displayData['paginationURL'] = "/".seo_url_lowercase($title,"-",'','110')."-qna-" . $topicId . '-all-'.$srcPage. '-all-'."all"."-@start@-@count@";
			}
			else{
				$displayData['paginationURL'] = "/".seo_url_lowercase($title,"-",'','110')."-qna-" . $topicId . '-all-'.$srcPage. '-all-'."all"."-@start@-@count@-".$filter;
			}
			$displayData['filterURL'] = "/".seo_url_lowercase($title,"-",'','110')."-qna-" . $topicId .'-all-'.$srcPage. '-all-'."all"."-0-10-";
			if($start==0){
				$displayData['canonicalURL'] = SHIKSHA_ASK_HOME."/".seo_url_lowercase($title,"-",'','110')."-qna-" . $topicId;
			}
		} else {
			if($filter == 'reputation'){
				$displayData['paginationURL'] = "/getTopicDetail/".$topicId."/".$seoDetails."/".$srcPage."/".$actionDone."/".$parmeterValues."/@start@/@count@";
			}else{
				$displayData['paginationURL'] = "/getTopicDetail/".$topicId."/".$seoDetails."/".$srcPage."/".$actionDone."/".$parmeterValues."/@start@/@count@/".$filter;
			}
			$displayData['filterURL'] = "/getTopicDetail/".$topicId."/".$seoDetails."/".$srcPage."/".$actionDone."/".$parmeterValues."/0/10/";
			if($start==0){
				$displayData['canonicalURL'] = SHIKSHA_ASK_HOME."/getTopicDetail/".$topicId."/".$seoDetails;
			}
		}

		$displayData['totalComments'] = isset($ResultOfDetails[0]['totalComments'])?$ResultOfDetails[0]['totalComments']:$mainAnsCount;
		//End Modifications
		$displayData['tabURL'] = site_url('messageBoard/MsgBoard/discussionHome')."/1/@tab@/1";

		//Code to get the Edit form data if the logged in user is the owner and no activity has been done on the Post
		if( ($userId == $main_message['userId'] && (count($topic_messages[0]) <= 1)) || ($userGroup == 'cms')  ){
			if($topicId>0)
				$topicDetails = $msgbrdClient->getTopicDetailForEdit(1,$fromOthersTopic,$topicId,$userId);
			if(is_array($topicDetails))
				$displayData['topicDetailEdit'] = $topicDetails;
			else
				$displayData['topicDetailEdit'] = array();

			$this->load->library('category_list_client');
			$categoryClient = new Category_list_client();
			$displayData['categoryList'] = $categoryClient->getCategoryTree($appId, 1);
			$displayData['entityType'] = $fromOthersTopic;
			$displayData['countryList'] = $countryList = $this->getCountries();
		}

		//TODO random selection needs to be fixed to get only child categories
		$this->load->library('cacheLib');
		$cacheLibObj = new CacheLib;

		$categoryMap = unserialize($cacheLibObj->get("catsubCatList"));
		$tempCategoryList = $CategoryList;
		shuffle($tempCategoryList);
		for($i = 0; $i < count($tempCategoryList) ; $i++){
			$randomCategory =  $tempCategoryList[$i];
			if(!isset($categoryMap[$randomCategory])){
				break;
			}
		}
		$displayData['fromOthersTopic'] = $fromOthersTopic;
		$displayData['trackForPages'] = 1;
		$res = json_decode($msgbrdClient->getUserReputationPoints($appId,$userId));
		$displayData['reputationPoints'] = $res[0]->reputationPoints;
		$displayData['showEditLink'] = $ResultOfDetails[0]['showEditLink'];

		if($userId>0){
			$this->load->library('acl_client');
			$aclClient =  new Acl_client();
			$displayData['ACLStatus'] = $aclClient->checkUserRight($userId,array('LinkQuestion','DelinkQuestion','LinkDiscussion','DelinkDiscussion'));
		}else{
			$displayData['ACLStatus'] = array('LinkQuestion'=>'False','DelinkQuestion'=>'False','LinkDiscussion'=>'False','DelinkDiscussion'=>'False');
		}

		if($fromOthersTopic=='discussion' && $displayData['ACLStatus']['LinkDiscussion']!='False' && $displayData['ACLStatus']['DelinkDiscussion']!='False'){
			$m=0;
			$discussionText = $topic_messages[0][0][msgTxt];
			$discussionArr = $msgbrdClient->getRelatedSearchDiscussion($topicId,$discussionText);

			$linkDiscussionViewCount = json_decode($msgbrdClient->calViewAnswerComment($discussionArr,$topicId,'true',1,'discussion'));

			foreach($discussionArr['title'] as $res){
				$linkDiscussionViewCount->msgTitle[$m]=$res[S];
				$linkDiscussionViewCount->link[$m]=$res[U];
				$linkDiscussionViewCount->tmplink[$m]=$res[tmp];
				$m++;
			}
			$linkedDiscussionIds = array();
			$linkedDiscussionCatCountry = array();
			$linkedDiscussionCreationDate = array();
			$linkedDiscussionBestAnswerFlag = array();
			$i=0;
			foreach($linkDiscussionViewCount->tmplink as $url){
				$urlArray = explode("/",$url);
				$linkedDiscussionIds[] = $urlArray['4'];
				$linkDiscussionViewCount->linkedDiscussionId[$i] = $urlArray['4'];
				$i++;
			}
			$linkedDiscussionDetails = json_decode($msgbrdClient->getSomeDetailsForGoogleResults($linkedDiscussionIds));
			for($countG=0;$countG< count($linkedDiscussionDetails[1]);$countG++){

				$creationDate = $linkedDiscussionDetails[1][$countG];
				if(!empty($creationDate)){
					$linkedDiscussionCreationDate[] = makeRelativeTime($creationDate);
				}else{
					$linkedDiscussionCreationDate[]='';
				}

			}
			$linkedDiscussionBestAnswerFlag = $linkDiscussionViewCount->bestAnsFlag;
			$linkedDiscussionCatCountry = $linkedDiscussionDetails[0];
			$linkDiscussionViewCount->categoryCountry = $linkedDiscussionCatCountry;
			$linkDiscussionViewCount->creationDate = $linkedDiscussionCreationDate;
			$linkDiscussionViewCount->bestAnsFlag = $linkedDiscussionBestAnswerFlag;
			$displayData['linkDiscussionViewCount']=$linkDiscussionViewCount;
		}
		//Check if discussion is Linked.
		if($fromOthersTopic=='discussion'){
			$checkForDiscussionStatus = $msgbrdClient->checkForDiscussionStatus($topicId);
			$displayData['checkForDiscussionStatus']=$checkForDiscussionStatus;
			if($checkForDiscussionStatus['result']=='accepted'){
				$getLinkedDiscussion = $msgbrdClient->getLinkedDiscussion($topicId);
				$displayData['getLinkedDiscussion'] = $getLinkedDiscussion;
			}
		}
		$this->load->library('Blog_client');
		$blog_client = new Blog_client();
		$this->load->library('categoryList/categoryPageRequest');
		$requestURL = new CategoryPageRequest();

		if($CategoryList[0]!='' || $CategoryList[1]!=''){
			$quick_links = $blog_client->getArticleWidgetsData('quick_links',$CategoryList[0],$CategoryList[1]);
			$latest_news = $blog_client->getArticleWidgetsData('latest_news',$CategoryList[0],$CategoryList[1]);

			$displayData['quickLinks'] = $quick_links;
			$displayData['latestNews'] = $latest_news;
			$requestURL->setData(array('categoryId'=>$CategoryList[0],'subCategoryId'=>$CategoryList[1],'countryId'=>$catcountry[0]['countryId']));
			$displayData['quickLinkURL'] = $requestURL->getURL();
		}

		$userLevel = $msgbrdClient->getUserLevel($appId,$userId,"AnA");
		$displayData['loggedInUserLevel'] = (is_array($userLevel))?$userLevel[0]['Level']:'Beginner';

		if($fromOthersTopic == 'user'){
			if(!$randomCategory || $randomCategory=='' || $randomCategory==0) $randomCategory = 149;
			$displayData['randomCategory'] = $randomCategory;
			/*Code to create Hidden URL and Hidden Code by Pranjul End*/
			if((count($main_message) <=0) || (isset($main_message['status'])) && (($main_message['status'] == 'deleted')||($main_message['status'] == 'abused'))){
				show_404();
			}
		}
		else
		{
			$displayData['functionToCall'] = 'refreshPage';			
		}

		$displayData['logged_in_user'] = $this->logged_in_user;
		$displayData['site_current_url'] = $this->site_current_url;
		$displayData['site_current_refferal'] = $this->site_current_refferal;

		$showListingTitleMessage = '';
		$showListingDescMessage='';
		if($main_message['listingTitle'] != ""){
			$showListingTitleMessage = $main_message['listingTitle']." | ";
			$showListingDescMessage = " @ ".$main_message['listingTitle'];
		}
		if((count($main_message) <=0) || (isset($main_message['status'])) && (($main_message['status'] == 'deleted')||($main_message['status'] == 'abused'))){
            			show_404();
       		}
		$displayData['m_meta_title'] = $showListingTitleMessage.seo_url($main_message['msgTxt']," ");
		$displayData['m_meta_description'] = seo_url($main_message['msgTxt']," ").'. Ask Questions on various education & career topics or find answers to questions related to education and career options from our education counselors and users in this education and career forum community.'.$showListingDescMessage;
		$displayData['m_meta_keywords']=  'Shiksha, Ask & Answer, Education, Career Forum Community, Study Forum, Education & Career Counselors, Career Counselling, study circle, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships';
		$displayData['userStatus'] = $this->userStatus;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['logged_in_user_expert_detail']  = $this->message_board_client->getUserInfoForLeaderBaord($appId,$userId);
		$displayData['boomr_pageid'] = "ana_detail";
		$this->messageBoardProxy->renderView('ANA','question_detail_page',$displayData);
	}

	/**
	 * [render_question_detail_page description]
	 * @return [type] [description]
	 */
	public function render_ask_question_page()
	{	
		if(($_COOKIE['ci_mobile'] != "mobile") || ($_COOKIE['user_force_cookie'] == "YES")) 
		{
			header("location:" . SHIKSHA_ASK_HOME . "/messageBoard/MsgBoard/discussionHome" . "/1/1/1/answer/");
			exit;
		}
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$res = json_decode($this->message_board_client->getUserReputationPoints($appId,$userId));
		$data['reputationPoints'] = $res[0]->reputationPoints;
		$data['categoryList'] = $this->getCategories();
		$data['countryList'] = $this->getCountries();
		$data['question_text_for_post'] = $this->input->post('question_text_for_post');
		$data['logged_in_user'] = $this->logged_in_user;
		$data['site_current_url'] = $this->site_current_url;
		$data['site_current_refferal'] = $this->site_current_refferal;
		$data['userStatus'] = $this->userStatus;
		$this->messageBoardProxy->renderView('ANA','ask_question',$data);
	}
}

<?php 

/*
Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: ankurg $:  Author of last commit
$Date: 2010/07/16 09:29:49 $:  Date of last commit

message_board_client.php makes call to server using XML RPC calls.

$Id: MsgBoard.php,v 1.205 2010/07/16 09:29:49 ankurg Exp $:
*/

class MsgBoard extends MX_Controller {

var $userStatus = '';
var $parentToChildMap = array();
function init($library=array('message_board_client','category_list_client','register_client','alerts_client','ajax','listing_client','relatedClient'),$helper=array('url','image','shikshautility')){
	if(is_array($helper)){
		$this->load->helper($helper);
	}
	if(is_array($library)){
		$this->load->library($library);
	}
	if(($this->userStatus == ""))
		$this->userStatus = $this->checkUserValidation();
		
	$this->load->helper('coursepages/course_page');	
	$this->load->library('coursepages/CoursePagesUrlRequest');
	$this->load->library(array('categoryList/categoryPageRequest','listing/listing_client'));
}

function postExpertReply(){
   $this->init(array('message_board_client'),array('url'));
   $msgbrdClient = new Message_board_client();

   $userId = $this->input->post('userId');
   $msgTxt = $this->input->post('msgTxt');
   $threadId = $this->input->post('threadId');
   $requestIP = $this->input->post('requestIP');
   $categoryList = $this->input->post('categoryList');
   $tags = $this->input->post('tags');

   $reply = $msgbrdClient->postExpertReply($userId,$msgTxt,$threadId,$requestIP,$categoryList,$tags);
   print_r($reply);
}

private function getCafeSearchResults()
{
	$this->load->library('Discussionhomesearchcontent');
    $discussionhomesearchcontent = new Discussionhomesearchcontent();
	$resultSet = $discussionhomesearchcontent->getQuestionDocuments();
	return $resultSet;
}

function clearCache(){
	$this->init(array('cacheLib'),'');
	//$this->cacheLib->clearCache('messageBoard');
}

function getQuestionDataFromQerSearch($questionTitle,$count, $excludeQuestionIds = array()){

        $this->load->library('Discussionhomesearchcontent');
        $discussionhomesearchcontent = new Discussionhomesearchcontent();
        $excludeQuestionIds = array_filter($excludeQuestionIds);
        $resultSet = $discussionhomesearchcontent->getRelatedQuestions($questionTitle,10, $excludeQuestionIds);
        return $resultSet;
}                                  

function postQuestionFromCafeForm(){
$url = SHIKSHA_ASK_HOME."/";
header("Location: $url",TRUE,301);
exit;

$this->init(array('message_board_client','alerts_client','ajax'));
//$questionTitle = $this->input->post('questionText');

$questionTitle = "";
if(isset($_REQUEST['questionText'])){
    $questionTitle = $this->input->get('questionText',true);
    if(empty($questionTitle)){
        $questionTitle = $this->input->post('questionText',true);
    }
}

$typeOfSearch = $this->setTypeOfSearch();
if($typeOfSearch == 'QER')
	$googleRes = $this->getQuestionDataFromQerSearch($questionTitle,10);
else
	$googleRes = $this->getDataFromGoogleSearch($questionTitle,'');

$categoryId = 1;
$countryId = 1;
$actionDone = '';
$displayData['googleRes'] = $googleRes;
$displayData['validateuser'] = $this->userStatus;
$displayData['myqnaTab'] = 'answer';
$displayData['actionDone'] = $actionDone;
$displayData['tabselected'] = 0;
$displayData['entity'] = 'question';
$displayData['tabURL'] = site_url('messageBoard/MsgBoard/discussionHome')."/".$categoryId."/@tab@/".$countryId."/@qnaTab@/".$actionDone;
$displayData['typeOfSearch'] = $typeOfSearch;
//course pages related change
$displayData['crs_pg_prms'] = $this->input->get_post('crs_pg_prms',true);
if(!empty($displayData['crs_pg_prms']) && trim($displayData['crs_pg_prms']) !='_') {
	$temp_array = explode("_", $displayData['crs_pg_prms']);
	if(checkIfCourseTabRequired($temp_array[0])) {	
		$displayData['tab_required_course_page'] = true;
		$displayData['subcat_id_course_page'] = $temp_array[0];
		$displayData['cat_id_course_page'] = $temp_array[1];
		$displayData['course_pages_tabselected'] = 'AskExperts';
	}
}

		//below code used for beacon tracking
		$displayData['trackingpageIdentifier'] = 'questionIntermediatePage';
		$displayData['trackingcountryId']=2;


		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($displayData);

//below code is used for conversion page tracking purpose
$trackingPageKeyId=$this->input->get('tracking_keyid');
if(isset($trackingPageKeyId))
	$displayData['trackingPageKeyId']=$trackingPageKeyId;
$this->load->view('/common/postCafeForm',$displayData);
}


public function getRelatedQuestionFromAjax()
{
        $typeOfSearch = $this->setTypeOfSearch();
        if($typeOfSearch == 'QER')
            $this->getDataFromShikshaSearchAjax();
        else
            $this->getDataFromGoogleAjax();
}

function getDataFromGoogleAjax()
{
    $questionTitle = $this->input->post('title');
    $displayData['googleRes'] = $this->getDataFromGoogleSearch($questionTitle,'');
    $displayData['typeOfSearch'] = 'GOOGLE';
    echo $this->load->view('/common/googleRelatedQuestionOnPostQuestion',$displayData);
}

function getDataFromShikshaSearchAjax()
{
    $questionTitle = $this->input->post('title');
    $displayData['googleRes'] = $this->getQuestionDataFromQerSearch($questionTitle,10);
    $displayData['typeOfSearch'] = 'QER';
    echo $this->load->view('/common/qerRelatedQuestionOnPostQuestion',$displayData);
}


function getDataFromGoogleSearch($questionTitle,$topicId,$bestAns='false',$googleSearch='false',$jsCall='false',$storeCache='false'){
$this->init();
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
$finalArray = $msgbrdClient->calViewAnswerComment($xmlArray,$topicId,'false',$googleSearch);
$finalArray = json_decode($finalArray);
foreach($finalArray as $index => $member){
    //error_log(print_r($member,true),3,'/home/aakash/Desktop/aakash.log');
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

function setTypeOfSearch()
{
//	setcookie("TYPEOFSEARCH",'QER',null,'/',COOKIEDOMAIN);
	return "QER";
	$typeOfSearch = 'QER';
	if(isset($_COOKIE['TYPEOFSEARCH']) && !empty($_COOKIE['TYPEOFSEARCH']))
	{
		return $_COOKIE['TYPEOFSEARCH'];
	}
	else
	{
		$cacheLib = $this->load->library('cacheLib');
		$key = "TYPEOFSEARCH";
		$typeOfSearch = $cacheLib->get($key);
		if($typeOfSearch == "QER" || empty($typeOfSearch))
		{
			$typeOfSearch = "QER";
			setcookie($key,'QER',null,'/',COOKIEDOMAIN);
			$cacheLib->store($key,"GOOGLE",864000);
		}
		else
		{
			$typeOfSearch= "GOOGLE";
			setcookie($key,'GOOGLE',null,'/',COOKIEDOMAIN);
			$cacheLib->store($key,"QER",864000);
		}   
	}
    return $typeOfSearch;
}


function discussionHome($categoryId = 1,$tabselected = 0,$countryId=1,$myqnaTab='answer',$actionDone='',$start=0,$rows=20){    

        //In case of Announcements, display a 410 Error
        if($tabselected == 7){
                show_410();
                exit;
        }


        //Redirect to new Ask/Answer homepage
        $url = SHIKSHA_ASK_HOME."/";
        header("Location: $url",TRUE,301);
        exit;


        $currentUrl = $_SERVER['SCRIPT_URI'];
        if($currentUrl == SHIKSHA_ASK_HOME_URL."/messageBoard/MsgBoard/discussionHome/1/0/1/answer/"){
               $url = SHIKSHA_ASK_HOME."/";
                header("Location: $url",TRUE,301);
                exit;
        }

        //Now, we are removing some of the old ANA URLs by redirecting them to all questions/all discussions/tag detail pages
        if($countryId == 1 && ($tabselected == 1 || $tabselected == 3) && $categoryId == 1){ //Case 1: All Country + All Category + Question page: Redirect to All Questions page
            $this->redirect_301(SHIKSHA_ASK_HOME_URL."/questions");
        }
        else if($countryId == 1 && ($tabselected == 6) && $categoryId == 1){    //Case 2: All Country + All Category + Discussions page: Redirect to All Discussions page
            $this->redirect_301(SHIKSHA_ASK_HOME_URL."/all-discussions");
        }
        else if($countryId == 1 && ($tabselected == 0 || $tabselected == 1 || $tabselected == 3 || $tabselected == 6) && $categoryId != 1){    //Case 3: All Country + Some Category + Questions/discussions page: Check the Tag from Config and redirect to corresponding Tag detail page
            $this->config->load('messageBoard/CategoryTagMappingConfig');
            $categoryTag = $this->config->item('categoryTag');
            if(isset($categoryTag[$categoryId])){
                $tagId = $categoryTag[$categoryId]['tagId'];
                $tagName = $categoryTag[$categoryId]['tagName'];
                $url = getSeoUrl($tagId, 'tag', $tagName);
                if($tabselected == 6){
                    $url .= '?type=discussion';
                }
		else if($tabselected == 3){
		    $url .= '?type=unanswered';
		}
            }
            else{
                $url = ($tabselected == 6) ? SHIKSHA_ASK_HOME_URL."/all-discussions" : SHIKSHA_ASK_HOME_URL."/questions";                
            }
            $this->redirect_301($url);
        }
        else if($countryId > 1 && ($tabselected == 0 || $tabselected == 1 || $tabselected == 3 || $tabselected == 6) && $categoryId == 1){    //Case 4: Some Country + All Category + Questions/discussions page: Check the Country Tag from DB and redirect to corresponding Tag detail page
            $this->load->model('AnAModel');
            $countryTag = $this->AnAModel->getCountryTag($countryId);
            if(isset($countryTag[0]['id'])){
                $tagId = $countryTag[0]['id'];
                $tagName = $countryTag[0]['tags'];
                $url = getSeoUrl($tagId, 'tag', $tagName);
                if($tabselected == 6){
                    $url .= '?type=discussion';
                }
                else if($tabselected == 3){
                    $url .= '?type=unanswered';
                }
            }
            else{
                $url = ($tabselected == 6) ? SHIKSHA_ASK_HOME_URL."/all-discussions" : SHIKSHA_ASK_HOME_URL."/questions";
            }
            $this->redirect_301($url);            
        }
        else if($countryId > 1 && ($tabselected == 0 || $tabselected == 1 || $tabselected == 3) && ($categoryId > 1 || $categoryId == 0)){   //Case 5: Some Country + Some Category + Questions page: Redirect to All Questions page
            $this->redirect_301(SHIKSHA_ASK_HOME_URL."/questions");
        }
        else if($countryId > 1 && ($tabselected == 6) && ($categoryId > 1 || $categoryId == 0)){    //Case 6: Some Country + Some Category + Discussions page: Redirect to All Discussions page
            $this->redirect_301(SHIKSHA_ASK_HOME_URL."/all-discussions");
        }
        

        $categoryForLeftPanel = $this->getCategories();

        $currentUrl = $_SERVER['SCRIPT_URI'];
        if($currentUrl == SHIKSHA_ASK_HOME_URL."/messageBoard/MsgBoard/discussionHome/1/0/1/answer/"){
               $url = SHIKSHA_ASK_HOME."/";
                header("Location: $url",TRUE,301);
                exit;
        }
        else if($currentUrl == SHIKSHA_ASK_HOME_URL."/messageBoard/MsgBoard/discussionHome/1/6/1/answer/"){
                $url = SHIKSHA_ASK_HOME_URL."/discussions";
                header("Location: $url",TRUE,301);
                exit;
        }
        else if($currentUrl == SHIKSHA_ASK_HOME_URL."/messageBoard/MsgBoard/discussionHome/1/3/1/answer/"){
                $url = SHIKSHA_ASK_HOME_URL."/unanswers";
                header("Location: $url",TRUE,301);
                exit;
        }

        //Redirect Question List page to New pages
        //Only in case of Country as All and Categories, we will be doing this Redirection. For all else, the Pages will run as it is.
        if($countryId == 1 && $tabselected == 1 && $categoryId <= 14){
                if($categoryId == 1){   //Reddirect to All Page
                        $url = SHIKSHA_ASK_HOME_URL."/questions";
                }
                else{   //Redirect to Specific Category Page
                        $categoryName = '';
                        foreach ($categoryForLeftPanel as $key => $value){
                                if( $key == $categoryId){
                                        $categoryName = seo_url_lowercase($value[0],"-");
                                }
                        }
			if($categoryId == 0){
				$categoryName = "miscellaneous";
			}
                        $url = SHIKSHA_ASK_HOME_URL."/questions/$categoryName";

                }
                header("Location: $url",TRUE,301);
                exit;
        }

   	$typeOfSearch = $this->setTypeOfSearch();
	$this->init(array('message_board_client','alerts_client','ajax'));
	$appId = 12;
	$myqnaTabArray = array('question','answer','bestanswer','untitledQuestion');
	if(!in_array($myqnaTab,$myqnaTabArray)){
		$myqnaTab='answer';
	}
	$alertCount = 0;
	$newRepliesCount = 0;
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
	$msgbrdClient = new Message_board_client();
	$discussionUrl = site_url('messageBoard/MsgBoard/discussionHome');
	
        if( !is_numeric($categoryId) || !is_numeric($countryId) || !is_numeric($start) || !is_numeric($rows) ){
	       $url=SHIKSHA_ASK_HOME_URL;
	       header("Location: $url",TRUE,301);
	       exit;
	}
	
	if(($userId == 0) && (($tabselected == 4) ||($actionDone == 'deleteQuestion'))){
		$url='/messageBoard/MsgBoard/discussionHome';
		header("Location:".$url);
		exit;
	}
	
	//$data['topContributtingAndExpertPanel'] = $this->getTopContributorsAndExpertData();
	if($tabselected == 2) $tabselected = 0;

	//Redirect if the page entered is not as per SEO
	//Ankur: Remove this Tab checking condition. The check for URL should be added for all the tabs except Search
        //if($tabselected == 1 || $tabselected == 3){
	if(!isset($_REQUEST['search_data_type'])){
            if(!checkIfCourseTabRequired($categoryId)) {
                $enteredURL = $_SERVER['SCRIPT_URI'];
                if($start==0){
                    if($categoryId == 1 && $tabselected == 0 && $countryId == 1){
			                $canonicalurl = SHIKSHA_ASK_HOME_URL."/";
			        } 
			        else if($categoryId == 1 && $tabselected == 6 && $countryId == 1){
			                $canonicalurl = SHIKSHA_ASK_HOME_URL."/discussions";
			        }
			        else if($categoryId == 1 && $tabselected == 3 && $countryId == 1){
			                $canonicalurl = SHIKSHA_ASK_HOME_URL."/unanswers";
			        }
			        else {
			                $canonicalurl = SHIKSHA_ASK_HOME_URL.'/messageBoard/MsgBoard/discussionHome/'.$categoryId.'/'.$tabselected.'/'.$countryId.'/answer/';
			        }
                }
                else{
                    $canonicalurl = SHIKSHA_ASK_HOME_URL.'/messageBoard/MsgBoard/discussionHome/'.$categoryId.'/'.$tabselected.'/'.$countryId.'/answer/default/'.$start.'/'.$rows;
                }
                if(ENVIRONMENT != "development" && $enteredURL!=$canonicalurl){
                        header("Location: $canonicalurl",TRUE,301);
                        exit;
                }        
            }
        }

	if($userId != 0)
	{
		$alertCount = 0;
		$result = array();
		$cardStatus =  $msgbrdClient->getVCardStatus(1,$userId);
        
		//Modified for Shiksha performance task on 8 March: We will not get these data in case of Announcement or discussion homepages
		/*if($tabselected != 6 && $tabSelected !=7){
		    //Commenting since this functionality is not used anymore
		    //$newRepliesCount = $msgbrdClient->getNewReplyCount(1,'1',$userId);
		    $newRepliesCount = '0';
		    $data['leaderBoardInfo'] = $this->getLeaderBoardInfo($userId);
		    $data['followUser'] = $this->getFollowUser('',$userId);
		    $data['leaderBoardInfo'] = (is_array($data['leaderBoardInfo']))?$data['leaderBoardInfo']:array();
		}
		else{
		    $data['followUser'] = array();
		}*/
		$data['followUser'] = array();
		$newRepliesCount = '0';

        $this->load->model('QnAModel');
        $leaderBoardInfo = array();
        $isAnAExpert = $this->QnAModel->checkIfAnAExpert('',$userId,false);
        $leaderBoardInfo['msgArray'][0]['isAnAExpert'] = $isAnAExpert;
        $data['leaderBoardInfo'] = $leaderBoardInfo;
		
		//End Modifications
                $res = json_decode($msgbrdClient->getUserReputationPoints($appId,$userId));
                if($res[0]->rank > 0){
                    $data['rank'] = $res[0]->rank;
                }else{
                    $data['rank'] = 'N/A';
                }
                if($res[0]->reputationPoints>0 && $res[0]->reputationPoints!='9999999'){
                  $data['reputationPoints'] = round($res[0]->reputationPoints);
                }elseif($res[0]->reputationPoints<=0 && $res[0]->reputationPoints!='9999999'){
                  $data['reputationPoints'] = 0;
                }elseif($res[0]->reputationPoints=='9999999'){
                  $data['reputationPoints'] = 10;
                }

                //$data['rank'] = $msgbrdClient->calculateRankByRepuationPoints($appId,$userId);
	}
        if($userId>0){ 
		$this->load->library('acl_client');
        $aclClient =  new Acl_client();
        $data['ACLStatus'] = $aclClient->checkUserRight($userId,array('MakeStickyDiscussion','RemoveStickyDiscussion','MakeStickyAnnouncement','RemoveStickyAnnouncement'));
        }else{
        $data['ACLStatus'] = array('MakeStickyDiscussion'=>'False','RemoveStickyDiscussion'=>'False','MakeStickyAnnouncement'=>'False','RemoveStickyAnnouncement'=>'False');
        }
	$userFriends = array();
	foreach($result as $temp)
	{
		array_push($userFriends,$temp['senderuserid']);
	}

	$parameterObj = array('popular_'.$categoryId.'_'.$countryId => array('totalCount'=>0),'recent_'.$categoryId.'_'.$countryId => array('totalCount'=>0),'unans_'.$categoryId.'_'.$countryId => array('totalCount'=>0),'myQuestions_'.$categoryId.'_'.$countryId => array('totalCount'=>0),'myAnswers_'.$categoryId.'_'.$countryId => array('totalCount'=>0),'myBestAnswers_'.$categoryId.'_'.$countryId => array('totalCount'=>0),'myuntitledQuestions_'.$categoryId.'_'.$countryId => array('totalCount'=>0));
	$wallKey = '';
	if($tabselected == 0){
		$wallKey = 'wallQuestions_'.$categoryId.'_'.$countryId.'_'.$start.'_'.$rows;
		$lastTimeStamp = date('Y-m-d H:i:s');
		$data['topicListings'] = $this->wallQuestionPage($categoryId,$countryId,$start,10,1,'1',$lastTimeStamp);
		$parameterObj['wall_'.$categoryId.'_'.$countryId]['totalCount']=isset($data['topicListings']['totalCount'])?$data['topicListings']['totalCount']:0;
	}
	$recentKey = '';
	if($tabselected == 1){
		$recentKey = 'recentQuestions_'.$categoryId.'_'.$countryId.'_'.$start.'_'.$rows;
		$data['topicListings'] = $this->recentTopicsPage($categoryId,$countryId,$start,$rows,1);
		$parameterObj['recent_'.$categoryId.'_'.$countryId]['totalCount']=isset($data['topicListings']['totalCount'])?$data['topicListings']['totalCount']:0;
	}
	$popularKey = '';
	if($tabselected == 2){
		$popularKey = 'popularQuestions_'.$categoryId.'_'.$countryId.'_'.$start.'_'.$rows;
		$data['topicListings'] = $this->popularTopicsPage($categoryId,$countryId,$start,$rows,1);
		$parameterObj['popular_'.$categoryId.'_'.$countryId]['totalCount']=isset($data['topicListings']['totalCount'])?$data['topicListings']['totalCount']:0;
	}
	$unAnsweredKey = '';
	if($tabselected == 3){
		$unAnsweredKey = 'unAnsweredQuestions_'.$categoryId.'_'.$countryId.'_'.$start.'_'.$rows;
		$data['topicListings'] = $this->unAnsweredQuestionsPage($categoryId,$countryId,$start,$rows,1);
		if(count($data['topicListings']['results'])==0){
			show_404();
		}
		$parameterObj['unans_'.$categoryId.'_'.$countryId]['totalCount']=isset($data['topicListings']['totalCount'])?$data['topicListings']['totalCount']:0;
	}
	$myKey = '';
	if($tabselected == 4){
		if($myqnaTab == 'question'){
			$myKey = 'questions_'.$categoryId.'_'.$countryId.'_'.$start.'_'. $rows;
			$topicListings = $this->myQuestionAnswers($categoryId,$countryId,$start,$rows,$myqnaTab,true,1);
			$data['topicListings'] = $topicListings;
		}elseif($myqnaTab == 'answer'){
			$myKey = 'answers_'.$categoryId.'_'.$countryId.'_'.$start.'_'.$rows;
			$topicListings = $this->myQuestionAnswers($categoryId,$countryId,$start,$rows,$myqnaTab,true,1);
			$data['topicListings'] = $topicListings;
		}/*elseif($myqnaTab == 'bestanswer'){
			$myKey = 'bestanswers_'.$categoryId.'_'.$countryId.'_'.$start.'_'.$rows;
			$topicListings = $this->myQuestionAnswers($categoryId,$countryId,$start,$rows,$myqnaTab,true,1);
			$data['topicListings'] = $topicListings;
		}*/elseif($myqnaTab == 'untitledQuestion'){
			$myKey = 'untitledQuestions_'.$categoryId.'_'.$countryId.'_'.$start.'_'.$rows;
			$topicListings = $this->myQuestionAnswers($categoryId,$countryId,$start,$rows,$myqnaTab,true,1);
			$data['topicListings'] = $topicListings;
		}
		$parameterObj['myQuestions_'.$categoryId.'_'.$countryId]['totalCount']=isset($topicListings['totalQuestions'])?$topicListings['totalQuestions']:0;
		$parameterObj['myAnswers_'.$categoryId.'_'.$countryId]['totalCount']=isset($topicListings['totalQuestionsAnswered'])?$topicListings['totalQuestionsAnswered']:0;
		$parameterObj['myBestAnswers_'.$categoryId.'_'.$countryId]['totalCount']=isset($topicListings['totalBestAnswers'])?$topicListings['totalBestAnswers']:0;
                $parameterObj['myuntitledQuestions_'.$categoryId.'_'.$countryId]['totalCount']=isset($topicListings['totaluntitledQuestions'])?$topicListings['totaluntitledQuestions']:0;
	}
	if($tabselected == 5){
		if($myqnaTab == 'answer'){
                        $editorKey = 'editorialBinQuestion_'.$categoryId.'_'.$countryId.'_'.$start.'_'.$rows;
                        $topicListings = $this->getQuestionsForEditorialBin($categoryId,$countryId,$start,$rows,1);
                        $data['topicListings'] = $topicListings;
                        $tmpArr = $this->myQuestionAnswers($categoryId,$countryId,$start,$rows,'untitledQuestion',true,1);
			$data['tmp'] = $tmpArr;
		}elseif($myqnaTab == 'untitledQuestion'){
			$myKey = 'untitledQuestions_'.$categoryId.'_'.$countryId.'_'.$start.'_'.$rows;
			$topicListings = $this->myQuestionAnswers($categoryId,$countryId,$start,$rows,$myqnaTab,true,1);
			$data['topicListings'] = $topicListings;//print_r($data['topicListings'][totaluntitledQuestions]);

		}
                // $parameterObj['myuntitledQuestions_'.$categoryId.'_'.$countryId]['totalCount']=isset($topicListings['totaluntitledQuestions'])?$topicListings['totaluntitledQuestions']:0;
	}
	if($tabselected == 6){
		$rows = 60;
		$editorKey = 'discussionPost_'.$categoryId.'_'.$countryId.'_'.$start.'_'.$rows;
		$topicListings = $this->getHomepageData('discussion',$categoryId,$countryId,$start,$rows);
		$data['topicListings'] = $topicListings;

	}
	if($tabselected == 7){
		$rows = 60;
		$editorKey = 'announcementPost_'.$categoryId.'_'.$countryId.'_'.$start.'_'.$rows;
		$topicListings = $this->getHomepageData('announcement',$categoryId,$countryId,$start,$rows);
		$data['topicListings'] = $topicListings;
	}
	//if(empty($actionDone)){
		//$actionDone = 'default';
	//}
	
	
	$canonicalurl='';
	$nexturl = '';
	$previousurl = '';
	/*Calculate maximum number of results for Q&A tab ,Discussion  and Announcement*/
	if($tabselected == 1 || $tabselected == 3){
		$totalResult = $data['topicListings']['totalCount'];	
	}
	if($tabselected == 6 || $tabselected == 7){
		$totalResult = $data['topicListings'][0]['totalTopicCount'];	
	}
	
	if($tabselected==0 && $categoryId==1 && $countryId==1){
		/*create canonical url for shiksha cafe*/
		$canonicalurl = SHIKSHA_ASK_HOME;
	} 
    else if($categoryId == 1 && $tabselected == 6 && $countryId == 1){
            $canonicalurl = SHIKSHA_ASK_HOME."/discussions";
    }
    else if($categoryId == 1 && $tabselected == 3 && $countryId == 1){
            $canonicalurl = SHIKSHA_ASK_HOME."/unanswers";
    }

	/*else if($tabselected == 6 || $tabselected == 7){
			$canonicalurl = SHIKSHA_ASK_HOME.'/'.$categoryId.'/'.$tabselected.'/'.$countryId.'/answer/';
			
	}*/else if($tabselected == 1 || $tabselected == 3 || $tabselected == 6 || $tabselected == 7 || $tabselected == 5){
		/*If User on first page Create canonical url, nexturl and previous url for Q&A tab ,Discussion  and Announcement*/
		if($start==0){
			if(!checkIfCourseTabRequired($categoryId)) {
				$enteredURL = $_SERVER['SCRIPT_URI'];
				$canonicalurl = SHIKSHA_ASK_HOME.'/messageBoard/MsgBoard/discussionHome/'.$categoryId.'/'.$tabselected.'/'.$countryId.'/answer/';
				if($totalResult>$rows){
					$nexturl = SHIKSHA_ASK_HOME.'/messageBoard/MsgBoard/discussionHome/'.$categoryId.'/'.$tabselected.'/'.$countryId.'/answer/default/'.($start+$rows).'/'.$rows;
				}
				/*Redirection Rule*/
					
				if($enteredURL!=$canonicalurl && REDIRECT_URL=='live'){
					header("Location: $canonicalurl",TRUE,301);
					exit;
				}
			}
		}else{
			if(!checkIfCourseTabRequired($categoryId)) {
				/*If User not on first page Create canonical url, nexturl and previous url for Q&A tab ,Discussion  and Announcement*/
				$enteredURL = $_SERVER['SCRIPT_URI'];
				$canonicalurl = SHIKSHA_ASK_HOME.'/messageBoard/MsgBoard/discussionHome/'.$categoryId.'/'.$tabselected.'/'.$countryId.'/answer/default/'.$start.'/'.$rows;
				if($enteredURL!=$canonicalurl && REDIRECT_URL=='live'){
					header("Location: $canonicalurl",TRUE,301);
					exit;
				}
					
					
				if($start+$rows < $totalResult){
					$nexturl = SHIKSHA_ASK_HOME.'/messageBoard/MsgBoard/discussionHome/'.$categoryId.'/'.$tabselected.'/'.$countryId.'/answer/default/'.($start+$rows).'/'.$rows;
				}
				if($start-$rows <=0){
					$previousurl = SHIKSHA_ASK_HOME.'/messageBoard/MsgBoard/discussionHome/'.$categoryId.'/'.$tabselected.'/'.$countryId.'/answer/';
				}else{
					$previousurl = SHIKSHA_ASK_HOME.'/messageBoard/MsgBoard/discussionHome/'.$categoryId.'/'.$tabselected.'/'.$countryId.'/answer/default/'.($start-$rows).'/'.$rows;
				}
			}
		}
	}
	$data['nexturl'] = $nexturl;
	$data['previousurl'] = $previousurl;
	$data['canonicalurl'] = $canonicalurl;
	$returnArray = $this->getCommentCookieContent();

	$data['recentKey'] = $recentKey;
	$data['popularKey'] = $popularKey;
	$data['unAnsweredKey'] = $unAnsweredKey;
	$data['myKey'] = $myKey;
	$data['editorKey'] = $editorKey;
	$data['userGroup'] = $userGroup;
	$data['myqnaTab'] = $myqnaTab;
	$data['questionText'] = isset($returnArray['questionText'])?$returnArray['questionText']:'';
	//$data['alertWidget'] = $this->getWidgetAlert(5,'byCategory',1,$this->userStatus);
	$data['parameterObj'] = json_encode($parameterObj);
	$data['actionDone'] = $actionDone;
	$data['listingParam'] = $listingParam;
	$data['appId'] = $appId;
	$data['alertCount'] = $alertCount;
	$data['categoryForLeftPanel'] = json_encode($categoryForLeftPanel);
	$countryList = $this->getCountries();
	$data['countryList'] = json_encode($countryList);
	$data['tabselected'] = $tabselected;
	$data['pageUrl'] = base64_encode(site_url('messageBoard/MsgBoard/discussionHome'));
	$data['categoryId'] = $categoryId;
	$data['countryId'] = $countryId;
	$data['selectedCategoryName'] = isset($categoryForLeftPanel[$categoryId][0])?$categoryForLeftPanel[$categoryId][0]:'All';
	$data['selectedCountryName'] = isset($countryList[$countryId])?$countryList[$countryId]:'';
	$data['friendArray'] = $userFriends;
	$data['newRepliesCount'] = $newRepliesCount;
        $data['trackForPages'] = true;
	$Validate = $this->userStatus;
	$data['validateuser'] = $Validate;
	$data['cardStatus'] = $cardStatus['status'];
	$data['catCountURL'] = site_url('messageBoard/MsgBoard/discussionHome')."/@cat@/".$tabselected."/@coun@/".$myqnaTab."/".$actionDone;
	$data['paginationURL'] = site_url('messageBoard/MsgBoard/discussionHome')."/".$categoryId."/".$tabselected."/".$countryId."/".$myqnaTab."/".$actionDone."/@start@/@count@";
	$data['tabURL'] = site_url('messageBoard/MsgBoard/discussionHome')."/".$categoryId."/@tab@/".$countryId."/@qnaTab@/".$actionDone;
	$data['categoryURL'] = site_url('messageBoard/MsgBoard/discussionHome')."/@cat@/".$tabselected."/".$countryId."/".$myqnaTab."/".$actionDone;
	$data['start'] = $start;
	$data['rows'] = $rows;
	$data['infoWidgetData'] = $this->getDateForInfoWidget();
        $data['averageTimeToAnswer'] = $this->getAverageTimeForAswer();
	$data['typeOfSearch'] = $typeOfSearch;
	
	$googleRemarketingParams = array(
			"categoryId" 	=> $categoryId,
			"countryId" 	=> 2
		); 
	
	$data['googleRemarketingParams'] = $googleRemarketingParams;
	
	if( ( $typeOfSearch=="GOOGLE") && isset($_REQUEST['q']) ){
		$questionTitle = htmlspecialchars($_REQUEST['q']);
		$data['googleRes'] =   $this->getDataFromGoogleSearch($questionTitle,'',true,true);//print_r($data['googleRes']['creationDate']);
		$totalCount = $data['googleRes']['totalRes'][0];
		$startNum = isset($_REQUEST['start'])?$_REQUEST['start']:0;
		$countNum = isset($_REQUEST['num'])?$_REQUEST['num']:10;
		$paginationURLForGoogle = '/messageBoard/MsgBoard/discussionHome/search2/?q='.$data['googleRes']['searchString'].'&sa=%C2%A0&start=@start@&num=@count@&cx=004839486360526637444%3Aznooniugfqo&cof=FORID%3A10%3BNB%3A1&ie=UTF-8';
		$paginationHTMLForGoogle = doPagination($totalCount,$paginationURLForGoogle,$startNum,$countNum,3);
		$data['paginationHTMLForGoogle'] = $paginationHTMLForGoogle;
		// $data['creationDateForGoogle'] = makeRelativeTime($data['googleRes']['creationDate']);


	}
	$cpgs_param = $this->input->get_post('cpgs_param',true);

	if( $typeOfSearch == "QER" && isset($_REQUEST['search_data_type']) && $_REQUEST['search_data_type']=='question'){
		$data['googleRes'] =   $this->getCafeSearchResults();
		$totalCount = $data['googleRes']['general']['numfound'];
		$startNum = isset($_REQUEST['start'])?$_REQUEST['start']:0;
		$countNum = 10;
		$paginationURLForGoogle = $this->makePaginationBaseUrl($cpgs_param);
		$paginationHTMLForGoogle = doPagination($totalCount,$paginationURLForGoogle,$startNum,$countNum,3);
		$data['paginationHTMLForGoogle'] = $paginationHTMLForGoogle;
	}

	$data['pageKeySuffixForDetail'] = 'ASK_ASKHOMEPAGE_WALL_';
	
	// added for course pages related change
	$data['tab_required_course_page'] = checkIfCourseTabRequired($categoryId);
	$data['subcat_id_course_page'] = $categoryId;
	if($data['tab_required_course_page']) {
		$data['cat_id_course_page'] = $categoryForLeftPanel[$categoryId][1];
		if($tabselected == 6) {
			$data['course_pages_tabselected'] = 'Discussions';
		} else if(in_array($tabselected, array(1,3))) {
			$data['course_pages_tabselected'] = 'AskExperts';
		}
	}
	
	// added if qna search is triggered from course page
	if(!empty($cpgs_param)) {
		$temp = explode("_", $cpgs_param);
		if(!empty($temp[0])) {
			$data['tab_required_course_page'] = checkIfCourseTabRequired($temp[0]);
			$data['subcat_id_course_page'] = $temp[0];
			$data['course_pages_tabselected'] = $temp[1];
			$message_tab = "All questions page";
			$backlink_url = $this->coursepagesurlrequest->getAskExpertsTabUrl($data['subcat_id_course_page']);
			if($temp[1] == 'Discussions') {
				$message_tab = 'All discussions page';
				$backlink_url = $this->coursepagesurlrequest->getDiscussionsTabUrl($data['subcat_id_course_page']);
			}
			
			$data['cat_id_course_page'] = $categoryForLeftPanel[$temp[0]][1];
			$data['cpgs_backLinkArray'] = array("MESSAGE" => $message_tab, "LANDING_URL" => $backlink_url);
		}
	}
	
	if($data['tab_required_course_page']) {		
		$CPGSPageNo = ($start / $rows) + 1;
		$coursePagesSeoDetails = $this->coursepagesurlrequest->getCoursePagesSeoDetails($data['subcat_id_course_page'], $CPGSPageNo);		
		$pageSeoDetails = $coursePagesSeoDetails[$data['course_pages_tabselected']];
		if(empty($pageSeoDetails)){
			$pageSeoDetails = $this->coursepagesurlrequest->getParticularPageSeoDetails($data['subcat_id_course_page'], $CPGSPageNo,$data['course_pages_tabselected']);
		}
		$data['metaTitle'] = $pageSeoDetails['TITLE'];
		$data['metKeywords'] = $pageSeoDetails['KEYWORDS'];
		$data['metaDescription'] = $pageSeoDetails['DESCRIPTION'];
		
		if(in_array($tabselected, array(1,6)))
		{
			if($CPGSPageNo > ceil($totalResult / $rows) && $CPGSPageNo != 1) {
				if($data['course_pages_tabselected'] == "Discussions") {				
					redirect($this->coursepagesurlrequest->getDiscussionsTabUrl($data['subcat_id_course_page']), 'location', 301);
				} else {				
					redirect($this->coursepagesurlrequest->getAskExpertsTabUrl($data['subcat_id_course_page']), 'location', 301);
				}
			}
			else if($tabselected == 1 && count($data['topicListings']['results'])==0){
				show_404();
			}
			
			$data['myCanonical'] = $cpgsUrl = $coursePagesSeoDetails[$data['course_pages_tabselected']]['URL'];
			$pos = strpos($cpgsUrl, "#");
			if($pos !== false) {			
				$len = strlen(substr($cpgsUrl, $pos));			
				$cpgsUrl = substr($cpgsUrl, 0, -$len);
				$data['myCanonical'] = trim($cpgsUrl);
			}
					  
			$enteredURL = trim($_SERVER['SCRIPT_URI']);
			$script_url = $_SERVER['SCRIPT_URL'];
			$explode_array = explode("-coursepage", $script_url);		
			$part1 = $explode_array[0];		
			if(strpos( $part1,"-questions-") !==FALSE) {
				$explode_array1 = explode("-questions-", $part1);
			} else if(strpos($part1,"-discussions-") !==FALSE) {
				$explode_array1 = explode("-discussions-", $part1);
			}	
			$course_page_url = str_replace($explode_array1[1], "", $part1); 
			$course_page_url = rtrim($course_page_url,"-");
			$course_page_url = $course_page_url."-@pageno@-coursepage";
			//echo $course_page_url;
			$data['paginationURL'] = $course_page_url;
			// error_log("enteredURL = ".$enteredURL.", canonical = ".$data['myCanonical']);
			if($enteredURL != $data['myCanonical'] && $data['myCanonical'] != '') {
				redirect($data['myCanonical'], 'location', 301);
			}
			if(is_object($this->coursepagesurlrequest) && $this->coursepagesurlrequest->getDirectoryName($data['subcat_id_course_page']) != '') {
				$courseHomePagePaginationUrl = '';
				$generatorType = '';
				switch($tabselected) {
					case 1: 
						$generatorType = 'QnaPage';
						$courseHomePagePaginationUrl = $this->coursepagesurlrequest->getAskExpertsTabUrl($categoryId);
						break;
					case 6: 
						$generatorType = 'DiscussionsPage';
						$courseHomePagePaginationUrl = $this->coursepagesurlrequest->getDiscussionsTabUrl($categoryId);
						break;
				}

				if($courseHomePagePaginationUrl != '') {
					if(empty($data['myCanonical']) || $data['myCanonical'] == '') {
						$urlSuffix = ($CPGSPageNo > 1) ? '/'.$CPGSPageNo : '';
						$data['myCanonical'] = $courseHomePagePaginationUrl.$urlSuffix;
					}
					$courseHomePagePaginationUrl .= '/@pageno@';
					$data['paginationURL'] = $courseHomePagePaginationUrl;
				}

				//preparing breadcrumbs
				if($generatorType != '') {
					$breadcrumbOptions = array('generatorType' 	=> $generatorType,
												'options' 		=> array('request'			=>	$this->coursepagesurlrequest,
																	     'subCategoryId'	=>	$categoryId));
					$BreadCrumbGenerator = $this->load->library('common/breadcrumb/BreadcrumbGenerator', $breadcrumbOptions);
					$data['breadcrumbHtml'] = $BreadCrumbGenerator->prepareBreadcrumbHtml();
				}
			}
		}
	}	
		
	/*Code to create Hidden URL and Hidden Code by Pranjul Start*/
	/*$this->load->spamcontrol('spamcontrol/SpamControl');
	$hcSC = new HiddenCodeGenerator();
	$hiddenCode = $hcSC->generateCode(5);
	$actualCode = $hcSC->getCode();
	$huSC = new HiddenUrlGenerator();
	$data['hiddenURL'] = $huSC->createHiddenUrl('Url','hidden','URL','','');
	$data['hiddenCode'] = $hiddenCode;
	*/
        /*Code to create Hidden URL and Hidden Code by Pranjul End*/

    //below line id used to store the required infromation in beacon varaible for tracking purpose
    $this->tracking=$this->load->library('common/trackingpages');

    //getting source page for PagetypeforGATracking
   $trackingpageIdentifier=$this->tracking->getSourcePageName($tabselected);

   $data['trackingpageIdentifier']=$trackingpageIdentifier;
   $data['trackingcatID']=$categoryId;
   $data['trackingcountryId']=$countryId;
   
 	$data['trackingPaginationKey']=($start/$rows)+1;

    $this->tracking->_pagetracking($data);
   
   //below line used for conversion tracking purpose
    $this->tracking->gettingPageKey($tabselected,$data);
    
   /* if(isset($trackingPageKeyId))
    {
    	$data['trackingPageKeyId']=$trackingPageKeyId;
    }*/

	if($typeOfSearch == "GOOGLE")
		$this->load->view('messageBoard/discussionHome',$data);
	else
		$this->load->view('messageBoard/discussionHome_newQer',$data);
}

//private function to make base url for pagination in cafer search page.
//$cpgs_param checks if search is used from coursepages
private function makePaginationBaseUrl($cpgs_param='')
{ 
    $this->load->library('search/searchCommon');
    $searchCommon = new SearchCommon();


    $params = $searchCommon->readSearchGetParameters();
    $url = SHIKSHA_ASK_HOME."/search1/?";
    foreach($params as $key=>$value)
    {
        $url =$url.$key."=".urlencode($value)."&";
    }
    $url .="start=@start@&rows=@count@";
    
    $url .="&cpgs_param=".$cpgs_param;
   return $url;
}

function wallQuestionPage($categoryId = 1,$countryId=1,$start=0,$rows=10,$return = 0,$threadIdCsv='',$lastTimeStamp)
{
	return 	$this->createQuestionList('getWallData',$categoryId,$countryId,$start,$rows,$return,$threadIdCsv,$lastTimeStamp);
}
/*
function closeQuestionsForBestAnswers(){
	$this->load->library('message_board_client');
	$msgbrdClient = new Message_board_client();
	$appId = 1;
	$reply = $msgbrdClient->closeQestionCron($appId);
	echo "<pre>"; print_r($reply); echo "</pre>";
}
*/

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

function getWidgetAlert($productId,$alertType,$alertValueId,$userStatus,$filterId='')
{
	$appId = 12;
	$userId = isset($userStatus[0]['userid'])?$userStatus[0]['userid']:0;
	$widgetAlert = array();
	$widgetAlert['loggedIn'] = 0;
	if($userId != 0)
	{
		$alertClient = new Alerts_client();
		$widgetAlert = $alertClient->getWidgetAlert($appId,$userId,$productId,$alertType,$alertValueId,$filterId);
		$widgetAlert['loggedIn'] = 1;
	}
	return json_encode($widgetAlert);
}

function updateNewReplies()
{
	$this->init(array('message_board_client'),'');
	$msgbrdClient = new Message_board_client();
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'';
	$questionIds = 0;
	if(isset($_POST['questionIds']))
	{
		$questionIds = $this->input->post('questionIds');
	}
	$newRepliesCountArray = $msgbrdClient->getNewReplyCountForQuestions($appId,$userId,$questionIds);
	$newRepliesCountArray = is_array($newRepliesCountArray)?$newRepliesCountArray[0]:array();
	echo json_encode($newRepliesCountArray);
}

function myQuestionAnswers($categoryId=1,$countryId=1,$start=0,$rows=10,$myqnaTab='question',$countFlag=false,$return = 0){
	if(isset($_POST['category']))
	{
		$categoryId = $this->input->post('category');
		$start = $this->input->post('startFrom');
		$rows = $this->input->post('countOffset');
		$countryId = $this->input->post('country');
		$myqnaTab = $this->input->post('myqnaTab');
		$return = 0;
	}
	$this->init(array('message_board_client'));
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$appId = 12;
	$msgbrdClient = new Message_board_client();
	$newRepliesCount = ($newRepliesCount != '')?$newRepliesCount:0;
	$ResultOfQuestion = $msgbrdClient->getQuestionAnswersForHome($appId,$userId,$categoryId,$countryId,$start,$rows,$myqnaTab,$countFlag,$myqnaTab);
	$arrayOfUsers = array();
	$totalQuestions = $ResultOfQuestion[0]['totalQuestions'];
	$totalQuestionsAnswered = $ResultOfQuestion[0]['totalQuestionsAnswered'];
	$totalBestAnswers = $ResultOfQuestion[0]['totalBestAnswers'];// print_r($ResultOfQuestion);
        $totaluntitledQuestion = $ResultOfQuestion[0]['totaluntitledQuestion'];

	//Execute a loop to merge the Question and the Category-Country arrays
	if(isset($ResultOfQuestion[0]) && (is_array($ResultOfQuestion[0]['results'])) && (is_array($ResultOfQuestion[0]['categoryCountry']))){
	 if($myqnaTab == 'question' || $myqnaTab == 'untitledQuestion'){
	 //   if($myqnaTab == 'question'){
            for($i=0;$i<count($ResultOfQuestion[0]['results']);$i++){
		  for($j=0;$j<count($ResultOfQuestion[0]['categoryCountry']);$j++){
			if($ResultOfQuestion[0]['results'][$i]['msgId'] == $ResultOfQuestion[0]['categoryCountry'][$j]['msgId']){
			  $ResultOfQuestion[0]['results'][$i]['category'] = $ResultOfQuestion[0]['categoryCountry'][$j]['category'];
			  $ResultOfQuestion[0]['results'][$i]['country'] = $ResultOfQuestion[0]['categoryCountry'][$j]['country'];
			  $ResultOfQuestion[0]['results'][$i]['categoryId'] = $ResultOfQuestion[0]['categoryCountry'][$j]['categoryId'];
			  $ResultOfQuestion[0]['results'][$i]['countryId'] = $ResultOfQuestion[0]['categoryCountry'][$j]['countryId'];
			}
	      }
	    }
		}
	  	else{
	    for($i=0;$i<count($ResultOfQuestion[0]['results']);$i++){
		  for($j=0;$j<count($ResultOfQuestion[0]['categoryCountry']);$j++){
			if($ResultOfQuestion[0]['results'][$i]['question']['msgId'] == $ResultOfQuestion[0]['categoryCountry'][$j]['msgId']){
			  $ResultOfQuestion[0]['results'][$i]['question']['category'] = $ResultOfQuestion[0]['categoryCountry'][$j]['category'];
			  $ResultOfQuestion[0]['results'][$i]['question']['country'] = $ResultOfQuestion[0]['categoryCountry'][$j]['country'];
			  $ResultOfQuestion[0]['results'][$i]['question']['categoryId'] = $ResultOfQuestion[0]['categoryCountry'][$j]['categoryId'];
			  $ResultOfQuestion[0]['results'][$i]['question']['countryId'] = $ResultOfQuestion[0]['categoryCountry'][$j]['countryId'];
			}
	      }
	    }
		}
	}
	//End loop for merge

	//Also, in case of Answer and Best Answer tabs, execute a loop to merge the Result and the Answer Comments
	if(isset($ResultOfQuestion[0]) && (is_array($ResultOfQuestion[0]['results'])) && (is_array($ResultOfQuestion[0]['answerComments']))){
	  	if($myqnaTab != 'question'){
		$k=0;
	    for($i=0;$i<count($ResultOfQuestion[0]['results']);$i++){
		  for($j=0;$j<count($ResultOfQuestion[0]['answerComments']);$j++){
			if($ResultOfQuestion[0]['results'][$i]['answer']['msgId'] == $ResultOfQuestion[0]['answerComments'][$j]['mainAnswerId']){
			  $ResultOfQuestion[0]['results'][$i]['comment'][$k] = $ResultOfQuestion[0]['answerComments'][$j];
			  $k++;
			}
	      }
	    }
		}
	}
	//End loop to merge Answer Comments array

	if($myqnaTab == 'question' || $myqnaTab=='untitledQuestion'){
		$arrayOfRes = $ResultOfQuestion[0]['results'];
		if(is_array($arrayOfRes))
		{
			for($i=0;$i<count($arrayOfRes);$i++)
			{
				$Result = &$arrayOfRes[$i];
				$this->createListForMyQnATab($Result);
                                if($myqnaTab=='untitledQuestion'){
                                    $currentUserId = $Result['userId'];
                                    $arrayOfUsers[$currentUserId]['userProfile'] = $Result['userProfile'];
                                }
			}
		}
	}else if(($myqnaTab == 'answer') || ($myqnaTab == 'bestanswer')){
		$arrayOfRes = $ResultOfQuestion[0]['results'];
		if(is_array($arrayOfRes))
		{
			for($i=0;$i<count($arrayOfRes);$i++)
			{
				if(isset($arrayOfRes[$i]['question']) && isset($arrayOfRes[$i]['answer'])){
					//The section for answer start here.
					$Result = &$arrayOfRes[$i]['question'];
					$this->createListForMyQnATab($Result);
					$currentUserId = $Result['userId'];
					if(in_array($Result['userId'],$userFriends)){
						$arrayOfUsers[$currentUserId]['isFriend'] = 'true';
					}else{
						$arrayOfUsers[$currentUserId]['isFriend'] = 'false';
					}
					$arrayOfUsers[$currentUserId]['userStatus'] = $Result['userStatus'];
					$arrayOfUsers[$currentUserId]['level'] = $Result['level'];
					$arrayOfUsers[$currentUserId]['userImage'] = $Result['userImage'];
					$arrayOfUsers[$currentUserId]['displayname'] = $Result['displayname'];
					$arrayOfUsers[$currentUserId]['userProfile'] = $Result['userProfile'];
					$arrayOfUsers[$currentUserId]['userOnlineStatus'] = getUserStatusToolTip($userStatus,$Result['displayname'],$Result['lastlogintime']);
					$arrayOfUsers[$currentUserId]['mailMsg'] = MAIL_TO_USER.$Result['displayname'];
					$arrayOfUsers[$currentUserId]['addNetworkMsg'] = ADD_TO_NETWORK.$Result['displayname'];
					$arrayOfUsers[$currentUserId]['alreadyAddedToNetworkMsg'] = $Result['displayname'].' '.ALREADY_ADDED_TO_NETWORK;
					$Result = &$arrayOfRes[$i]['answer'];

					//The section for answer start here.
					if($myqnaTab==='bestanswer'){
						$Result['bestAnsFlag'] = '1';
					}
					$Result['repliesToAnswerCount'] = isset($Result['repliesToAnswerCount'])?$Result['repliesToAnswerCount']:0;
					$this->createListForMyQnATab($Result);
				}
			}
		}
	}

	$topics = array('results' => $arrayOfRes,
			'arrayOfUsers' => $arrayOfUsers,
			'totalQuestions' => $totalQuestions,
			'totalQuestionsAnswered'=> $totalQuestionsAnswered,
			'totalBestAnswers' => $totalBestAnswers,
			'newRepliesCount' => $newRepliesCount,
                        'totaluntitledQuestions' => $totaluntitledQuestion);
	if($return == 1)
		return $topics;

	echo json_encode($topics);

}
private function createListForMyQnATab($Result){
	$found = 0;
	$urlForTopic = site_url('messageBoard/MsgBoard/topicDetails').'/'.$Result['msgId'];
	$userProfile = site_url('getUserProfile').'/'.$Result['displayname'];
	$Result['creationDate'] = makeRelativeTime($Result['creationDate']);
	$userStatus = getUserStatus($Result['lastlogintime']);
	$Result['userStatus'] = $userStatus;
	$Result['userProfile'] = $userProfile;
	$Result['urlForTopic'] = $urlForTopic;
	return;
}
function popularTopicsPage($categoryId=1,$countryId=1,$start=0,$rows=10,$return = 0){
	if(isset($_POST['category']))
	{
		$categoryId = $this->input->post('category');
		$start = $this->input->post('startFrom');
		$rows = $this->input->post('countOffset');
		$countryId = $this->input->post('country');
		$return = 0;
	}
	return 	$this->createQuestionList('getPopularTopics',$categoryId,$countryId,$start,$rows,$return);

}

function unAnsweredQuestionsPage($categoryId=1,$countryId=1,$start=0,$rows=10,$return = 0){
	if(isset($_POST['category']))
	{
		$categoryId = $this->input->post('category');
		$start = $this->input->post('startFrom');
		$rows = $this->input->post('countOffset');
		$countryId = $this->input->post('country');
		$return = 0;
	}
	return 	$this->createQuestionList('getUnansweredTopics',$categoryId,$countryId,$start,$rows,$return);
}

function recentTopicsPage($categoryId = 1,$countryId=1,$start=0,$rows=15,$return = 0){
	if(isset($_POST['category']))
	{
		$categoryId = $this->input->post('category');
		$start = $this->input->post('startFrom');
		$rows = $this->input->post('countOffset');
		$countryId = $this->input->post('country');
		$return = 0;
	}
	return 	$this->createQuestionList('getRecentPostedTopics',$categoryId,$countryId,$start,$rows,$return);
}

function getQuestionsForEditorialBin($categoryId = 1,$countryId=1,$start=0,$rows=15,$return = 0){
	if(isset($_POST['category']))
	{
		$categoryId = $this->input->post('category');
		$start = $this->input->post('startFrom');
		$rows = $this->input->post('countOffset');
		$countryId = $this->input->post('country');
		$return = 0;
	}
	return 	$this->createQuestionList('getQnAForEditorialBin',$categoryId,$countryId,$start,$rows,$return);
}

private function createQuestionList($functionToCall,$categoryId=1,$countryId=1,$start=0,$rows=10,$return = 0,$threadIdCsv='',$lastTimeStamp='')
{
	$this->init(array('message_board_client'));
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
	$appId = 12;
	$msgbrdClient = new Message_board_client();
	
	$newRepliesCount = 0;
	
	$userFriends = array();
	//foreach($result as $temp)
	//{
	//	array_push($userFriends,$temp['senderuserid']);
	//}
	$arrayOfRes = array();
	$arrayOfUsers = array();
	switch ($functionToCall) {
			case 'getUnansweredTopics':
				$Result = $msgbrdClient->getUnansweredTopics($appId,$categoryId,$start,$rows,$countryId);
				break;
			case 'getPopularTopics':
				$Result = $msgbrdClient->getPopularTopics($appId,$categoryId,$start,$rows,$countryId,$userId,$userGroup);
				break;
			case 'getRecentPostedTopics':
				$Result = $msgbrdClient->getRecentPostedTopics($appId,$categoryId,$start,$rows,$countryId,$userId,$userGroup);
				break;
			case 'getQnAForEditorialBin':
				$Result = $msgbrdClient->getQnAForEditorialBin($appId,$categoryId,$start,$rows,$countryId,$userId);
				break;
			case 'getWallData':
				$Result = $msgbrdClient->getWallData($appId,$userId,$start,$rows,$categoryId,$countryId,$threadIdCsv,$lastTimeStamp);
				break;
			}
	$count = is_array($Result[0])?$Result[0]['totalCount']:0;
	$countAnswered = isset($Result[0]['totalAnswered'])?$Result[0]['totalAnswered']:0;
	$arrayOfRes = is_array($Result[0])?$Result[0]['results']:array();
	$categoryCountry = is_array($Result[0])?$Result[0]['categoryCountry']:array();
	$levelVCard = isset($Result[0]['levelVCard'])?$Result[0]['levelVCard']:array();
	$levelAdvance = isset($Result[0]['levelAdvance'])?$Result[0]['levelAdvance']:array();
	$answerSuggestions = isset($Result[0]['answerSuggestions'])?$Result[0]['answerSuggestions']:array();
	$answerSuggestions = $this->convertSuggestionArray($answerSuggestions);
	$ratingStatusOfLoggedInUser = isset($Result[0]['ratingStatusOfLoggedInUser'])?$Result[0]['ratingStatusOfLoggedInUser']:array();
	$threadIdList = '';

	if(is_array($arrayOfRes))
	{
		for($i=0;$i<count($arrayOfRes);$i++)
		{
			if($functionToCall != 'getWallData'){
				$currentUserId = $arrayOfRes[$i]['userId'];
				$found = 0;
				$urlForTopic = site_url('messageBoard/MsgBoard/topicDetails').'/'.$arrayOfRes[$i]['msgId'];
				$userProfile = site_url('getUserProfile').'/'.$arrayOfRes[$i]['displayname'];
				$arrayOfRes[$i]['creationDate'] = makeRelativeTime($arrayOfRes[$i]['creationDate']);
				$arrayOfRes[$i]['editorPickFlag'] = is_array($arrayOfRes[$i])?$arrayOfRes[$i]['editorPickFlag']:0;
				$userStatus = getUserStatus($arrayOfRes[$i]['lastlogintime']);
				$arrayOfRes[$i]['urlForTopic'] = $urlForTopic;

				if(in_array($arrayOfRes[$i]['userId'],$userFriends))
					$arrayOfUsers[$currentUserId]['isFriend'] = 'true';
				else
					$arrayOfUsers[$currentUserId]['isFriend'] = 'false';

				$arrayOfUsers[$currentUserId]['userStatus'] = $userStatus;
				$arrayOfUsers[$currentUserId]['userImage'] = $arrayOfRes[$i]['userImage'];
				$arrayOfUsers[$currentUserId]['displayname'] = $arrayOfRes[$i]['displayname'];
				$arrayOfUsers[$currentUserId]['level'] = $arrayOfRes[$i]['level'];
				$arrayOfUsers[$currentUserId]['userProfile'] = $userProfile;
				$arrayOfUsers[$currentUserId]['userOnlineStatus'] = getUserStatusToolTip($userStatus,$arrayOfRes[$i]['displayname'],$arrayOfRes[$i]['lastlogintime']);
				$arrayOfUsers[$currentUserId]['mailMsg'] = MAIL_TO_USER.$arrayOfRes[$i]['displayname'];
				$arrayOfUsers[$currentUserId]['addNetworkMsg'] = ADD_TO_NETWORK.$arrayOfRes[$i]['displayname'];
				$arrayOfUsers[$currentUserId]['alreadyAddedToNetworkMsg'] = $arrayOfRes[$i]['displayname'].' '.ALREADY_ADDED_TO_NETWORK;
				$threadIdList .= ($threadIdList=='')?$arrayOfRes[$i]['msgId']:",".$arrayOfRes[$i]['msgId'];
			}else{
				$urlForTopic = site_url('messageBoard/MsgBoard/topicDetails').'/'.$arrayOfRes[$i][0]['msgId'];
				$arrayOfRes[$i][0]['creationDate'] = makeRelativeTime($arrayOfRes[$i][0]['creationDate']);
				$arrayOfRes[$i][0]['urlForTopic'] = $urlForTopic;
				if(is_array($arrayOfRes[$i][1])) $arrayOfRes[$i][1]['creationDate'] = makeRelativeTime($arrayOfRes[$i][1]['creationDate']);
			}
		}

	}

	//Now, get the User answer flag and editorial pick
	if($functionToCall == 'getPopularTopics' || $functionToCall == 'getRecentPostedTopics')
	{
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
	}
	$topics = array('results' => $arrayOfRes,
			'arrayOfUsers' => $arrayOfUsers,
			'totalCount'=> $count,
			'totalAnswered'=>$countAnswered,
			'newRepliesCount' => $newRepliesCount,
			'levelAdvance' => $levelAdvance,
			'categoryCountry'=>$categoryCountry,
			'levelVCard'=>$levelVCard,
			'ratingStatusOfLoggedInUser'=>$ratingStatusOfLoggedInUser,
			'answerSuggestions'=>$answerSuggestions);
	if($return == 1)
		return $topics;

	echo json_encode($topics);
}

function updateEditorialBin(){
	$this->init(array('message_board_client'));
	if(isset($_POST['msgId'])){
		$msgId = $this->input->post('msgId');
		$action = $this->input->post('action');
	}
	$appId = 12;
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
	$msgbrdClient = new Message_board_client();
	if(($userId != 0) && ($userGroup === 'cms')){
		$Result = $msgbrdClient->updateEditorialBin($appId,$msgId,'qna',$userId,$action);
		$success = isset($Result['Result'])?$Result['Result']:'failed';
		echo json_encode(array('result'=>$success));
	}else{
		echo json_encode(array('result'=>'Not permitted'));
	}
}

function userAskAndAnswer($displayName,$tabSelected = 'question'){
    //This function is no longer operational. Hence, redirecting it to User profile page
    $url = '/getUserProfile/'.$displayName;
    header ('HTTP/1.1 301 Moved Permanently');
    header ('Location: '.$url);
    exit;

	$this->init(array('message_board_client','register_client','ajax'));
	$appId = 12;
	$displayName = "'".$displayName."'";
	$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$registerClient = new Register_client();
	$msgbrdClient = new Message_board_client();
	$userDetails = $registerClient->getDetailsforDisplayname($appId,$displayName);
	$viewedUserId = isset($userDetails[0]['userid'])?$userDetails[0]['userid']:0;
	$userDetails = $registerClient->userdetail($appId,$viewedUserId,'AnA');
	$displayData['userDetails'] = isset($userDetails[0])?$userDetails[0]:array();
	$totalQuestions = 0;
	$totalAnswers = 0;
	$totalQuestionAsked = 0;
	$totalQuestionAnswered = 0;
	$parameterObj = array('answer' => array('offset'=>-1,'totalCount'=>0,'countOffset'=>20),'question' => array('offset'=>-1,'totalCount'=>0,'countOffset'=>$rows));
	if($tabSelected == 'answer'){
		$resultUserAnswer = $msgbrdClient->getUserAnswers($appId,$viewedUserId,0,20,'true',$loggedInUserId);
		$totalQuestions = isset($resultUserAnswer[0]['totalQuestions'])?$resultUserAnswer[0]['totalQuestions']:0;
		$totalAnswers = isset($resultUserAnswer[0]['totalAnswers'])?$resultUserAnswer[0]['totalAnswers']:0;
		$totalQuestionAsked = isset($resultUserAnswer[0]['totalQuestionAsked'])?$resultUserAnswer[0]['totalQuestionAsked']:0;
		$totalQuestionAnswered = isset($resultUserAnswer[0]['totalQuestionAnswered'])?$resultUserAnswer[0]['totalQuestionAnswered']:0;
		$parameterObj['answer']['offset'] = 0;
		$parameterObj['answer']['totalCount'] = $totalQuestionAnswered;
		$tempArray=array();
		if(isset($resultUserAnswer[0]['results'])){
			$tempArray = &$resultUserAnswer[0]['results'];
		}
		for($i=0;$i<count($tempArray);$i++)
		{
			if(is_array($tempArray[$i]['question']))
				$tempArray[$i]['question']['creationDate'] = makeRelativeTime($tempArray[$i]['question']['creationDate']);
		}
	}

	if($tabSelected == 'question'){
		$resultUserQuestion = $msgbrdClient->getUserQuestions($appId,$viewedUserId,0,20,'true',$loggedInUserId);
		$totalQuestions = isset($resultUserQuestion[0]['totalQuestions'])?$resultUserQuestion[0]['totalQuestions']:0;
		$totalAnswers = isset($resultUserQuestion[0]['totalAnswers'])?$resultUserQuestion[0]['totalAnswers']:0;
		$totalQuestionAsked = isset($resultUserQuestion[0]['totalQuestionAsked'])?$resultUserQuestion[0]['totalQuestionAsked']:0;
		$totalQuestionAnswered = isset($resultUserQuestion[0]['totalQuestionAnswered'])?$resultUserQuestion[0]['totalQuestionAnswered']:0;
		$parameterObj['question']['offset'] = 0;
		$parameterObj['question']['totalCount'] = $totalQuestionAsked;
		$tempArray = &$resultUserQuestion[0]['results'];
		for($i=0;$i<count($tempArray);$i++)
		{
			$tempArray[$i]['creationDate'] = makeRelativeTime($tempArray[$i]['creationDate']);
		}
	}

	$Validate = $this->userStatus;
	$returnArray = $this->getCommentCookieContent();
	$displayData['questionText'] = isset($returnArray['questionText'])?$returnArray['questionText']:'';
	$displayData['validateuser'] = $Validate;
	$displayData['viewedUserId'] = $viewedUserId;
	$displayData['parameterObj'] = json_encode($parameterObj);
	$displayData['userAnswer'] = isset($resultUserAnswer[0]['results'])?$resultUserAnswer[0]['results']:array();
	$displayData['userQuestion'] = isset($resultUserQuestion[0]['results'])?$resultUserQuestion[0]['results']:array();
	$displayData['totalAnswers'] = $totalAnswers;
	$displayData['totalQuestions'] = $totalQuestions;
	$displayData['totalQuestionAsked'] = $totalQuestionAsked;
	$displayData['totalQuestionAnswered'] = $totalQuestionAnswered;
	$displayData['tabSelected'] = $tabSelected;
	$this->load->view('messageBoard/userQandA',$displayData);
}

function getUserQuestions(){
	$this->init(array('message_board_client','register_client'));
	$appId = 12;
	$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	if(isset($_POST['displayName']))
	{
		$displayName = trim($this->input->post('displayName'));
		$displayName = "'".$displayName."'";
		$start=$this->input->post('startFrom');
		$rows=$this->input->post('countOffset');
		$msgbrdClient = new Message_board_client();
		$registerClient = new Register_client();
		$userDetails = $registerClient->getDetailsforDisplayname($appId,$displayName);
		$viewedUserId = isset($userDetails[0]['userid'])?$userDetails[0]['userid']:0;
		$resultUserQuestion = $msgbrdClient->getUserQuestions($appId,$viewedUserId,$start,$rows,'false',$loggedInUserId);
		if(is_array($resultUserQuestion[0]['results']))
		{
			$tempArray = &$resultUserQuestion[0]['results'];
			for($i=0;$i<count($tempArray);$i++)
			{
				$tempArray[$i]['creationDate'] = makeRelativeTime($tempArray[$i]['creationDate']);
			}
		}
	}
	echo json_encode($resultUserQuestion);
}

function getUserAnswers(){
	$this->init(array('message_board_client','register_client'));
	$appId = 12;
	if(isset($_POST['displayName']))
	{
		$displayName = trim($this->input->post('displayName'));
		$displayName = "'".$displayName."'";
		$start=$this->input->post('startFrom');
		$rows=$this->input->post('countOffset');
		$msgbrdClient = new Message_board_client();
		$registerClient = new Register_client();
		$userDetails = $registerClient->getDetailsforDisplayname($appId,$displayName);
		$viewedUserId = isset($userDetails[0]['userid'])?$userDetails[0]['userid']:0;
		$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$resultUserAnswer = $msgbrdClient->getUserAnswers($appId,$viewedUserId,$start,$rows,'false',$loggedInUserId);
		if(is_array($resultUserAnswer[0]['results']))
		{
			$tempArray = &$resultUserAnswer[0]['results'];
			for($i=0;$i<count($tempArray);$i++)
			{
				if(is_array($tempArray[$i]['question']))
				{
				$tempArray[$i]['question']['creationDate'] = makeRelativeTime($tempArray[$i]['question']['creationDate']);
				$tempArray[$i]['question']['questionOwnerProfileUrl'] = site_url('getUserProfile').'/'.$tempArray[$i]['question']['questionOwner'];
				}
			}
		}
	}
	echo json_encode($resultUserAnswer);
}

private function getCategories(){
	$appId = 12;
	$this->init(array('category_list_client'),'');
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
function askQuestion($questionId=-1,$listingParam = 0){
	$this->init(array('message_board_client','category_list_client','register_client','alerts_client'),array('url','form'));
	$appId = 12;
	if((!is_array($this->userStatus)) && ($this->userStatus == "false")){
		header("Location:".SHIKSHA_ASK_HOME);
		exit;
	}else{
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:-1;
	}
	$registerClient = new register_client();
	$userDetails = $registerClient->userdetail($appId, $userId);
	$userPoints = isset($userDetails[0]['userPoints'])?$userDetails[0]['userPoints']:0;
	$widgetAlert = array();
	$displayData = array();
	$msgbrdClient = new Message_board_client();
	$alertClient = new Alerts_client();
	$categoryClient = new Category_list_client();
	$displayData['csvCatList'] = '';
	$displayData['csvCountryList'] = '';
	$countryList = $categoryClient->getCountries($appId);
	/*code for categories start here */
	$categoryForLeftPanel = $this->getCategories();
	/*code for categories ends here */
	if(isset($_COOKIE['commentContent']) && ($questionId != -2)){
		if(stripos($_COOKIE['commentContent'],'#$@!*$%^SHIKSHA') === false)
			setcookie('commentContent','',time()-12000,'/',COOKIEDOMAIN);
	}
	$returnArray = array();
	$isAdded = false;
	if(isset($_COOKIE['commentContent'])){
		$returnArray = $this->getCommentCookieContent();
		if($returnArray['addedflag'] == 'ADDED'){
			$isAdded = true;
		}
	}
	$searchResult = array();
	if(($questionId!=-2) && ($listingParam !== 0)){
		$decodedListingParam = base64_decode($listingParam);
		$listingParamArr = unserialize($decodedListingParam);
		$displayData['csvCatList'] = $listingParamArr['csvCatList'];
		$displayData['listingType'] = $listingParamArr['listingType'];
		$displayData['listingTypeId'] = $listingParamArr['listingTypeId'];
		$displayData['csvCountryList'] = $listingParamArr['csvCountryList'];
		$listingData = '#$@!*$%^SHIKSHA'.'off'.'#$@!*$%^SHIKSHA'.$displayData['csvCatList'].'#$@!*$%^SHIKSHA'.$displayData['csvCountryList'].'#$@!*$%^SHIKSHA'.$displayData['listingType'].'#$@!*$%^SHIKSHA'.$displayData['listingTypeId'];
		setcookie('commentContent',$listingData,0,'/',COOKIEDOMAIN);
	}else if(isset($_REQUEST['questionText']) && ($questionId == -1) && (!$isAdded)){
		$questionText = $_REQUEST['questionText'];
		$displayData['questionText'] = $questionText;
		$searchResult = $this->getRelatedQuestion($questionText);
		setcookie('commentContent',$questionText.'#$@!*$%^SHIKSHA#$@!*$%^SHIKSHA#$@!*$%^SHIKSHA#$@!*$%^SHIKSHA#$@!*$%^SHIKSHA',0,'/',COOKIEDOMAIN);
	}else if(isset($_COOKIE['commentContent'])){
		if(count($returnArray) > 2){
			$returnArray = $this->getCommentCookieContent();
		}
		$displayData['questionText'] = $returnArray['questionText'];
		$displayData['alertResult']  = $returnArray['alertResult'];
		$displayData['csvCatList']  = $returnArray['csvCatList'];
		$displayData['csvCountryList'] = $returnArray['csvCountryList'];
		$displayData['listingType'] = $returnArray['listingType'];
		$displayData['listingTypeId'] = $returnArray['listingTypeId'];
		$searchResult = $this->getRelatedQuestion($displayData['questionText']);
	}
	$displayData['searchResult'] = $searchResult;
	$displayData['alertCount'] = 0;
	if(($userId != -1) && ($questionId < 0))
	{
		$result = $alertClient->getMyAlertCount($appId,$userId,8);
		$displayData['alertCount'] = isset($result[0]['count'])?$result[0]['count']:0;
	}

	if($questionId > 0)
	{
		if($userId != -1)
		{
			$WidgetStatus = $alertClient->getWidgetAlert($appId,$userId,8,'byComment',$questionId);
			$ResultOfDetails = $msgbrdClient->getMsgTree($appId,$questionId,0,1,1);
			$questionDetails = array();
			if(isset($ResultOfDetails[0]['MsgTree'][0]))
			{
				$questionDetails = $ResultOfDetails[0]['MsgTree'][0];
				$displayData['questionText'] = $questionDetails['msgTxt'];
			}
			$alertCount = isset($WidgetStatus['alertCount'])?$WidgetStatus['alertCount']:0;
			$displayData['alertCount'] = $alertCount;
			$displayData['alertResult'] = $WidgetStatus['result'];
		}
	}
	$displayData['isRelated'] = 'false';
	$displayData['questionId'] = $questionId;
	$displayData['countryList'] = $countryList;
	$displayData['userPoints'] = $userPoints;
	$displayData['categoryForLeftPanel'] = json_encode($categoryForLeftPanel);
	$displayData['countryList'] = $countryList;
	$displayData['validateuser'] = $this->userStatus;
	$this->load->view('messageBoard/createTopic',$displayData);
}

function getRelatedQuestion($questionDetail='')
{
	$returnIt = true;
	if(isset($_POST['questionDetail'])){
		$questionDetail = $this->input->post('questionDetail');
		$returnIt = false;
	}
	$this->load->library('listing_client');
	$searchResult=array();$location="";$countryId='';$categoryId='';$appId=1;
	$ListingClientObj = new Listing_client();
	$searchResult = $ListingClientObj->listingSponsorSearch($appId,$questionDetail,$location,$countryId,$categoryId,0,5,'ask','','',1);
	if($returnIt){
		return $searchResult;
	}else{
		echo json_encode($searchResult);
	}
}
/*
function thanksPageWithoutEdit(){
    echo $this->load->view('messageBoard/thanksPageWithoutEdit');
}
*/
function editUserQuestionTitle(){ error_log("post values ".print_r($_POST,true));
                 $info[msgTitle] = $this->input->post('msgTitle');
                 $info[msgId]   = $this->input->post('msgId');
                 $info[userId] = $this->input->post('userId');
                 $info[questionUserId] = $this->input->post('questionUserId');
                 //$info[fromFront] = $_POST['fromFront'];
                 $info[status] = $this->input->post('status');
                // $info[newUserId] = $_POST['newUserId'];
                 $this->init(array('message_board_client'));
                 $appId=12;
                 $msgbrdClient = new Message_board_client();
                 $topicResult = json_decode($msgbrdClient->checkInQuestionLog($appId,$info[msgId]));
                 $info[msgDesc] = $topicResult[0]->description;
                 //$info[newmsgTitle] = $topicResult[0]->msgTitle;
                 //if($info[msgDesc] && $info[status]=='add') //pranjul
                 if($info[msgDesc]) //pranjul
                    echo "Thank you|-|-|-|".$this->load->view('messageBoard/thanksPageWithoutEdit');
                 else
                    echo "Add title|-|-|-|".$this->load->view('messageBoard/editTitlePage',$info);
}

function calculateJulianTime($year,$month,$day){
            return (367*$year - (int)(7*($year + (int)(($month+9)/12))/4)-(int)(3*((int)(($year+($month-9)/7)/100)+1)/4)+ (int)(275*$month/9) + $day + 1721028.5);
            //return ($day + (153*$month+2)/5 + $year*365 + $year/4 - $year/100 + $year/400 - 32045);
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
             }//print_r($str);
            //$final =array();

	    //Modified by ANkur on 30 Aug to add the Related questions from Google in a file. Using this, we will make the call to get related questions once every 10 days
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

            //$final = simplexml_load_file("http://www.google.com/cse?q=".$str."&as_q=\"isMasterList+Question\"&sa=%C2%A0&start=".$start."&num=".$num."&cx=004839486360526637444%3Aznooniugfqo&cof=FORID%3A10%3BNB%3A1&output=xml&ie=UTF-8#1016");
            $checkStr = $str."+more:pagemap:metatags-ismasterlist:present";
	    $final = simplexml_load_file("http://www.google.com/cse?q=".$checkStr."&requiredfields=isMasterList:present&sa=%C2%A0&start=".$start."&num=".$num."&cx=004839486360526637444%3Aznooniugfqo&cof=FORID%3A10%3BNB%3A1&output=xml&ie=UTF-8#1016");
//print_r($final->RES->R[0]->T);
            if(!empty($final->RES->R[1]->T)){
                $data = $final;
                //echo 'kkk';
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

                 //$str .= '%2Banswer(0)+or+answer(1)+or+answer(2)%2Bdaterange%3A'.$start_date.'-'.$end_date;
		 //$str .= '%2Banswer(0)+or+answer(1)+or+answer(2)';
                 $data = simplexml_load_file("http://www.google.com/cse?q=".$str."&sa=%C2%A0&start=".$start."&num=".$num."&cx=004839486360526637444%3Aznooniugfqo&cof=FORID%3A10%3BNB%3A1&output=xml&ie=UTF-8#1016");
            }

            if(!empty($data->RES->R[0]->T)){
            foreach($data->RES->R as $info){ //print_r($info);
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


function updateTitle(){

    $this->init(array('message_board_client'));

    $appId=12;
    $msgId = $this->input->post('msgId');
    $userId = $this->input->post('userId');
    $questionUserId = $this->input->post('questionUserId');
    $msgTitle = addslashes(htmlspecialchars($this->input->post('msgTitle')));
    $msgDescription = $this->input->post('msgDescription');
    $status = $this->input->post('status');
    $msgbrdClient = new Message_board_client();

    $topicResult = json_decode($msgbrdClient->updateTitle($appId,$userId,$msgId,$questionUserId,$msgTitle,$msgDescription));

    //error_log("dddddddddd".print_r($topicResult,true));
    //header('location:'.$_SERVER['HTTP_REFERER']);
    if($topicResult[0]=='haveValue'){
            echo '1';
    }else{

        echo '2';
    }
}



function questionThanks(){
      echo $this->load->view('messageBoard/thanksPage');
}

function createTopic(){
	$this->init(array('message_board_client','category_list_client','register_client','alerts_client'),array('url'));
	$appId=12;
	global $parentCategoryMap;
	$msgbrdClient = new Message_board_client();
	$categoryClient = new Category_list_client();
	$alertClient = new Alerts_client();
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'';
	$userLevel = $msgbrdClient->getUserLevel($appId,$userId,"AnA");
	$levelId = $userLevel[0]['levelId']; 
	$registerClient = new register_client();
	$userDetails = $registerClient->userdetail($appId, $userId);
	$userPointValue = isset($userDetails[0]['userPoints'])?$userDetails[0]['userPoints']:0;
	$otherParameter = isset($_POST['fromOthers'])?$this->input->post('fromOthers'):'question';
	$hasModeratorAccess = 0;
	$userGroup = (isset($this->userStatus[0]['usergroup']) && $this->userStatus[0]['usergroup']=='cms')?$this->userStatus[0]['usergroup']:'';
	if($userId > 0 && $userId!='')
		$hasModeratorAccess = Modules::run("messageBoard/MessageBoardInternal/getAccessLevel", $userId, $userGroup);
if($otherParameter == 'discussion' && $levelId < 11 && $hasModeratorAccess==0){
	echo 'NoDiscussionPosted';
		exit();
		}
	//below line is used for conversion tracking purpose---
	$trackingPageKeyId= isset($_POST['tracking_keyid'])?$this->input->post('tracking_keyid'):'';
	
	$mentionedNames = isset($_POST['mentionedUsersList'])?$this->input->post('mentionedUsersList'):'';	//Added by Ankur on 6 July for @Mention task
	if($otherParameter=='user') $otherParameter = 'question';
	$userPointRes = ($userPointValue >= 10)?'success':'exhaust';

	if(($userPointValue < 10) && ($otherParameter == 'question')){
		header("Location:".SHIKSHA_ASK_HOME_URL."/messageBoard/MsgBoard/discussionHome");
		exit;
	}
	if(isset($_REQUEST['topicDesc']))
	{
		//$topicdesc = $this->input->post('topicDesc');
		$topicdesc = $_REQUEST['topicDesc'];
		//$topicdesc = str_replace('%','% ', $topicdesc);
		//$topicdesc = $this->input->xss_clean($topicdesc);
		$fromOthers = isset($_POST['fromOthers'])?$this->input->post('fromOthers'):'user';

		//Added to remove newline characters from Question text
		if($otherParameter == 'question' || $otherParameter == 'user'){
		      $remove = array("\n", "\r\n", "\r");
		      $topicdesc = str_replace($remove, ' ', $topicdesc);
		}

		$selectedCategoryCsv = '';
		if($otherParameter == 'question' || $fromOthers == 'discussion' || $fromOthers == 'announcement')
		{
			$selectedCategoryCsv = $this->input->post('selectCategory');
			if(is_array($selectedCategoryCsv)){
				$selectedCategoryCsv = implode(",",$selectedCategoryCsv); //category id
			}
		}
		$setAlert = $this->input->post('setAlert');
		$editTopicId = $this->input->post('editit',true);
		$secCode = (isset($_POST['secCode']))?$this->input->post('secCode'):$this->input->post('secCodeOther');
		$listingType = $this->input->post('listingType');
		$listingTypeId = $this->input->post('listingTypeId');
		$topicDescription = $this->input->post('topicDescription');
		
		//Disable links to be added for questions or discussions
		$pos = strpos($topicdesc,', http');
		$pos1 = strpos($topicDescription,', http');
		if($pos===12 || $pos1===12){
	                header("Location:".SHIKSHA_ASK_HOME_URL."/messageBoard/MsgBoard/discussionHome");
        	        exit;
		}
		$requestIp = S_REMOTE_ADDR;
		$displayname = isset($this->userStatus[0]['displayname'])?$this->userStatus[0]['displayname']:'';
		$countryCsv = isset($_POST['countryListForCreateTopic'])?$this->input->post('countryListForCreateTopic'):'';

		if(is_array($countryCsv)){
			$countryCsv = implode(",",$countryCsv);
		}
		
		$otherParamCsv = $topicDescription;
        if($otherParamCsv=='To help the experts give a more relevant answer to your question, you may add details like Academic Percentage, Work-experience, Entrance test scores etc.'){
            $otherParamCsv = '';
        }

		/* if user select study in india checkbox then overrride
		country value to 2 */
		if ((isset($_REQUEST['siI'])) && ($_REQUEST['siI'] == 'study_india')) {
		    $countryCsv = 2;
		}
		
		$captchaResult = 0;
		$resultOfCreation = array();
		$resultOfCreation['topicResult'] = '';
		$resultOfCreation['alertResult'] = '';
		$resultOfCreation['userValidate'] = $userId;
        if(SHOW_QUESTION_CAPTCHA==1){
                $secCode = (isset($_POST['secCode']))?$this->input->post('secCode'):$this->input->post('secCodeOther');
                $secCodeIndex = isset($_POST['secCodeIndex'])?$this->input->post('secCodeIndex'):'security_code';
                if(verifyCaptcha($secCodeIndex,$secCode)){
                     $captchaResult = 1;
                }
        }
        else{
            $captchaResult = 1;
        }

        //Code aadded for Spam check control on the questions, discussions or announcements
        $isSpamNotAvailable = true;
        $entityType = $this->input->post('entityType');
    	if(($entityType == "question" || $entityType == "user") && $editTopicId>0 && isset($_POST['questionDescD'])){
	    	$otherParamCsv = $this->input->post('questionDescD');
    	}
        if( $this->spamCheck($topicdesc,$requestIp) || $this->spamCheck($otherParamCsv,$requestIp) ){
            $resultOfCreation['topicResult'] = array('ThreadID' => 10010011);
            $resultOfCreation['editTopicId'] = $editTopicId;
            $resultOfCreation['captchaResult'] = $captchaResult;
            $resultOfCreation['userPointRes'] = $userPointRes;
            echo json_encode($resultOfCreation);
            $isSpamNotAvailable = false;
	        if($editTopicId>0){
		        header("Location:".$this->input->server('HTTP_REFERER',true));
        		exit;
	        }
        }
    	//Code end for Spam control

        if($isSpamNotAvailable){
        	if(($captchaResult==1) && ($userId != '') && ($editTopicId > 0)){
        
        		if($otherParameter == 'question' || $otherParameter == 'discussion' ){
    				$this->insertTagsForTopic($topicdesc,$editTopicId, $otherParameter,$editTopicId);
        		}
        		
        	}
        	
		if(($captchaResult==1) && ($userId != '') && ($editTopicId < 0))
		{
			if(true)
			{
				if((($selectedCategoryCsv == '') || ($countryCsv == '') || ($topicdesc == '')) && ($otherParameter == 'question')){
					$url='/messageBoard/MsgBoard/discussionHome';
					header("Location:".$url);
					exit;
				}

				try{
		                    	if(isset($mentionedNames) && $mentionedNames != ''){
                		             $topicdesc = $this->changeTextForAtMention($topicdesc,$mentionedNames);
                              		     $otherParamCsv = $this->changeTextForAtMention($otherParamCsv,$mentionedNames);
                    		        }

					$topicResult = $msgbrdClient->addTopic($appId,$userId,$topicdesc,$selectedCategoryCsv,$requestIp,$fromOthers,$listingTypeId,$listingType,1,$displayname,$countryCsv,$otherParamCsv,'',$trackingPageKeyId);

					

					//Function to create and save tags for the posted question or discussion
					if($otherParameter == 'question' || $otherParameter == 'discussion' ){
						if($topicResult['isDuplicate'] != 1)
							$this->insertTagsForTopic($topicdesc,$topicResult['ThreadID'], $otherParameter,$editTopicId);					
				    
				    }

					/***********************************************************************************************/
                                        ////QnA Rehash Phase-2 Start code to check status before posting question,discussion,announcement
                                        /***********************************************************************************************/
                                        if($topicResult=='NOREPD' || $topicResult=='NOREPA' || $topicResult=='NOREPQ'){
                                            echo $topicResult;
					    exit;					
					}
                                        /***********************************************************************************************/
                                        ////QnA Rehash Phase-2 End code to check status before posting question,discussion,announcement
                                        /***********************************************************************************************/
                                        $resultOfCreation['topicResult'] = $topicResult;
					$this->clearCacheForUser();
					//$redirectUrl = (isset($topicResult['ThreadID']) && is_numeric($topicResult['ThreadID']))?SHIKSHA_ASK_HOME_URL.'/messageBoard/MsgBoard/questionPostSuccessPage':SHIKSHA_ASK_HOME;
					$redirectUrl = (isset($topicResult['ThreadID']) && is_numeric($topicResult['ThreadID']))?SHIKSHA_ASK_HOME_URL.'/messageBoard/MsgBoard/discussionHome':SHIKSHA_ASK_HOME;
					//Added by Ankur on 6 July for @Mention task
					if(isset($mentionedNames) && $mentionedNames != '' && $fromOthers == 'discussion' && isset($topicResult['ThreadID']) && is_numeric($topicResult['ThreadID'])){
					    $this->sendAtMentionMailers($mentionedNames,$topicResult['ThreadID'],$userId,$topicdesc,'discussion');
					    $this->sendAtMentionMailers($mentionedNames,$topicResult['ThreadID'],$userId,$otherParamCsv,'discussion');
					}

					if((!isset($topicResult['ThreadID']) || (!is_numeric($topicResult['ThreadID']))) && ($otherParameter == 'question')){
						throw new Exception('Unable to post question');
					}
                    //Get the URL of the newly posted Question/Discussion/Announcement
		    $type = $fromOthers;
		    if($type=='user'){
			$type = 'question';
		    }
                    $resultOfCreation['topicURL'] = getSeoUrl($topicResult['ThreadID'],$type,$topicdesc,array(),'NA',date('Y-m-d H:i:s'));
		    
		    //Set cookie for the newly posted Entity. This is while posting Question/Discussion/Announcement
		    setcookie  ('latestThreadId',$topicResult['ThreadID'], time() + (60 * 5),'/',COOKIEDOMAIN);
		    
				}catch (Exception $e){
					$url='/messageBoard/MsgBoard/questionPostLandingPage';
					header("Location:".$url);
					exit;
				}

				if(isset($_COOKIE['commentContent'])){
					setcookie  ('commentContent','',time()-3600,'/',COOKIEDOMAIN);
					setcookie  ('globalLandingPagePostAna','',time()-3600,'/',COOKIEDOMAIN);
					setcookie  ('globalnumber_total_record_found','',time()-3600,'/',COOKIEDOMAIN);
				}
				// ALert feature is no longer in use
				/*
				if($setAlert == 'on')
				{
					$alertName = 'comment-'.$topicdesc;
					try{
						$alertResult = $alertClient->createWidgetAlert($appId,$userId,8,'byComment',$topicResult['ThreadID'],$alertName);
						$resultOfCreation['alertResult'] = $alertResult;
						if(isset($alertResult['alert_id']) && !is_numeric($alertResult['alert_id'])){
							throw new Exception('Unable to create the alert');
						}
					}catch(Exception $e){
						header("Location:".$redirectUrl);
						exit;
					}
				}*/

			}
			if($otherParameter == 'question')
			{
				//header("Location:".$redirectUrl);
				//exit;
			}

		}
		else
		{
			$captchaResult = 1;
			if($editTopicId > 0){	//In case of Edit topic
			    $entityType = $this->input->post('entityType');
			    if($entityType == "question" || $entityType == "user"){
				//In case of edit of question posted after qna rehash
                                if(isset($_POST['questionDescD']))
                                $otherParamCsv = $this->input->post('questionDescD');
				$redirectUrl = (is_numeric($editTopicId))?SHIKSHA_ASK_HOME_URL.'/getTopicDetail/'.$editTopicId.'/-1/askQuestion/editQuestion':SHIKSHA_ASK_HOME;

				$questionMoveToIns = isset($_POST['questionMoveToIns'])?$this->input->post('questionMoveToIns'):'off';
				$questionMoveToCafe = isset($_POST['questionMoveToCafe'])?$this->input->post('questionMoveToCafe'):'off';
                                $mcourseId = isset($_POST['courseId'])?$this->input->post('courseId'):'0';
                                $minstId = isset($_POST['instId'])?$this->input->post('instId'):'0';
                                $isPaid = 'false';
                                if($questionMoveToIns=='on'){
                                        $this->load->builder('ListingBuilder','listing');
                                        $listingBuilder = new ListingBuilder();
                                        $courseRepository = $listingBuilder->getCourseRepository();
                                        $courseObj = $courseRepository->find($mcourseId);
                                        $isPaid=$courseObj->isPaid();
                                        
                                        /**
                                         Code changes by Rahul
                                         */
                                        $qNaModel = $this->load->model('QnAModel');
                                        $qNaModel->sendMailToCampusReps($mcourseId,$minstId,$editTopicId);
                                         
                                        
                                        /**
                                         * changes ends here
                                         */                                        
                                }
                                $topicResult = $msgbrdClient->updateCafePost($appId,$editTopicId,$userId,$topicdesc,$selectedCategoryCsv,$requestIp,$fromOthers,$listingTypeId,$listingType,1,$displayname,$countryCsv,$otherParamCsv,$questionMoveToIns,$mcourseId,$minstId,$isPaid,$questionMoveToCafe);

				/*$topicResult = $msgbrdClient->updateTopic($appId,$editTopicId,$topicdesc,$requestIp);
				if(($setAlert == 'on') && ($alertId == -1))
				{
					$alertName = 'comment-'.$topicdesc;
					$alertResult = $alertClient->createWidgetAlert($appId,$userId,8,'byComment',$topicResult['ThreadID'],$alertName);
					$resultOfCreation['alertResult'] = $alertResult;
				}*/
			    }
			    else if($entityType == "discussion" || $entityType == "announcement"){
				$redirectUrl = (is_numeric($editTopicId))?SHIKSHA_ASK_HOME_URL.'/getTopicDetail/'.$editTopicId.'/-1/askQuestion/edit'.$entityType:SHIKSHA_ASK_HOME;
		            if(isset($mentionedNames) && $mentionedNames != ''){
                                $topicdesc = $this->changeTextForAtMention($topicdesc,$mentionedNames);
                                $otherParamCsv = $this->changeTextForAtMention($otherParamCsv,$mentionedNames);
                            }

				$topicResult = $msgbrdClient->updateCafePost($appId,$editTopicId,$userId,$topicdesc,$selectedCategoryCsv,$requestIp,$fromOthers,$listingTypeId,$listingType,1,$displayname,$countryCsv,$otherParamCsv);
			    }
			    $resultOfCreation['topicResult'] = $topicResult;
			    $resultOfCreation['topicdesc'] = $topicdesc;
			    if($otherParameter == 'question' || $fromOthers == 'discussion' || $fromOthers == 'announcement')
			    {
					header("Location:".$redirectUrl);
				    exit;
			    }
			}
			else	// In case of wrong Captch
			{
			    $resultOfCreation['topicResult'] = array('ThreadID' => 0);
			}
		}

		$resultOfCreation['editTopicId'] = $editTopicId;
		$resultOfCreation['captchaResult'] = $captchaResult;
		$resultOfCreation['userPointRes'] = $userPointRes;
		echo json_encode($resultOfCreation);
	}
    }
}


function insertTagsForTopic($topicdesc, $threadId, $otherParameter,$editTopicId ){
			
			$this->load->library('Tagging/TaggingLib'); 
				$taggingLib = new TaggingLib();	
			$this->load->model('Tagging/taggingmodel');
					$taggingModel = new TaggingModel();	
				$tags = $taggingLib->showTagSuggestions(array($topicdesc));
				$finalTagsArray = $taggingLib->attachTagsWithParent($tags);
				if($editTopicId > 0){
					$manualTags = $taggingModel->fetchManualTags($threadId);
					foreach ($manualTags as $key => $value) {
						$tagType = $value['tag_type'];
						$tagId = $value['tag_id'];
						if(array_key_exists($tagType, $finalTagsArray)){
							$finalTagsArray[$tagType][] = $tagId;
						} else {
							$finalTagsArray[$tagType] = array();
							$finalTagsArray[$tagType][] = $tagId;
						}
					}
				}
				
				$i = 0;
				foreach ($finalTagsArray as $key => $value) {
						if(!empty($value)){
							
							foreach ($value as $key1 => $value1) {
								if($value1 != ''){
									$tagsToInsert[$i]['tagId'] = $value1;
									$tagsToInsert[$i]['classification'] = $key;
									$i++;
								}
							}


						}
				}	

	
					if($editTopicId > 0) {
						// Edit Case			
						$taggingModel->deleteTagsWithContentToDB($threadId);
						$taggingLib->insertTagsWithContentToDB($tagsToInsert,$threadId,$otherParameter,'updatetag');
					} else {
						// Add Case
						$taggingLib->insertTagsWithContentToDB($tagsToInsert,$threadId,$otherParameter,'threadpost');
					}
					
}

function editQuestion(){
	$this->init(array('message_board_client','alerts_client'),array('url'));
	$appId=12;$msgbrdClient = new Message_board_client();$alertClient = new Alerts_client();$resultOfCreation = array();
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$captchaResult = 0; $resultOfCreation['alertResult'] = -1; $resultOfCreation['questionSuccess'] = 'false';
	if(isset($_POST['questionText']))
	{
		$questionText = $this->input->post('questionText');
		$topicdesc = str_replace('%','% ', $questionText);

		//Added to remove newline characters from Question text
		$remove = array("\n", "\r\n", "\r");
		$topicdesc = str_replace($remove, ' ', $topicdesc);

		//$questionText = $this->input->xss_clean($questionText);

		//Added to remove newline characters from Question text
		$remove = array("\n", "\r\n", "\r");
		$topicdesc = str_replace($remove, ' ', $topicdesc);

		$setAlert = $this->input->post('setAlert');
		$alertId = $this->input->post('alertId');
		$editQuestionId = $this->input->post('editQuestionId');
		$secCode = $this->input->post('secCodeForEditQuestion');
		if(verifyCaptcha('secCodeForEditQuestion',$secCode,1) && ($userId != 0))
		{
				$captchaResult = 1;
				$requestIp = S_REMOTE_ADDR;
				$Result = $msgbrdClient->updateTopic($appId,$editQuestionId,$questionText,$requestIp);
				if(($setAlert == 'on') && ($alertId == -1))
				{
					$alertName = 'comment-'.$questionText;
					$alertResult = $alertClient->createWidgetAlert($appId,$userId,8,'byComment',$editQuestionId,$alertName);
					$resultOfCreation['alertResult'] = $alertResult;
				}elseif(($setAlert == 'on') && ($alertId != -1)){
					$alertResult = $alertClient->updateState($appId,$alertId,$userId,'on');
					$resultOfCreation['alertResult'] = $alertResult;
				}elseif(($setAlert == 'off') && ($alertId != -1)){
					$alertResult = $alertClient->updateState($appId,$alertId,$userId,'on');
					$resultOfCreation['alertResult'] = $alertResult;
				}
				$resultOfCreation['questionSuccess'] = isset($Result['Result'])?'true':'false';
		}
	}
	$resultOfCreation['captchaResult'] = $captchaResult;
	echo json_encode($resultOfCreation);
}

function closeDiscussionTopic($topicId)
{
	$this->init(array('message_board_client'),'');
	$appId = 12;
	$closeDiscussion = 0;
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$msgbrdClient = new Message_board_client();
	if($userId != 0)
	{
		$resultOfClose = $msgbrdClient->closeDiscussion($appId,$topicId,$userId);
		$closeDiscussion = $resultOfClose['Result'];
	}
	$result = array('userValidate' => $userId,
			'result' =>$closeDiscussion);
	echo json_encode($result);
}

function validateCaptcha()
{
	$this->init('',array('url'));
	$secCode = rand(0,10000000000);
	if($this->input->post('secCode')){
		$secCode = $this->input->post('secCode');
		$securityIndex = $this->input->post('secCodeIndex');
	}
	$isValidate = "false";
	if(verifyCaptcha($securityIndex,$secCode)){
		$isValidate = "true";
	}
	echo $isValidate;
}
function reportAbuse()
{
	$this->init(array('message_board_client'),'');
	$appId = 12;
	$resultOfAbuse = 0;
	$msgId = $this->input->post('msgId');
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$msgbrdClient = new Message_board_client();
	if($userId != 0)
	{
		$resultOfAbuse = $msgbrdClient->reportAbuse($appId,$msgId,$userId);
	}
	$result = array('userValidate' => $userId,
			'result' =>$resultOfAbuse,
			'msgId' => $msgId);
	echo json_encode($result);
}

function tenDaysInactiveDiscussionAnnoucement(){
                $appId = 1;
                $this->init(array('message_board_client'));
                $msgbrdClient = new Message_board_client();
                $msgIds = json_decode($msgbrdClient->tenDaysInactiveDiscussionAnnoucement($appId));
}
function deleteDiscussionTopic($topicId,$userId)
{
	$this->init(array('message_board_client'),'');
	$appId = 12;
	
	if(isset($this->userStatus[0]['userid'])){
		$userId = $this->userStatus[0]['userid'];
	}
	
	
	$myTopics = array();
	$deleted = 0;
	$msgbrdClient = new Message_board_client();
	if($userId != 0)
	{
	   	$result = $msgbrdClient->deleteTopic($appId,$topicId);
		$deleted = $result['Result'];
	}
	//This function is only called for questions, so indexType has to be question here.
	//If this function is also called for discussion or anything other than question than the belows code
	// has to be change
	//modules::run('search/Indexer/addToQueue', $topicId, 'question', 'delete');
	
	$result = array('userValidate' => $userId,
			'result' =>$deleted);
	echo json_encode($result);
}

function getMyTopicsAfterDeletion($topicId)
{
	$this->init(array('message_board_client'),'');
	$appId = 12;
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$myTopics = array();
	$deleted = 0;
	$msgbrdClient = new Message_board_client();
	if($userId != 0)
	{
	   	$result = $msgbrdClient->deleteTopic($appId,$topicId);
		if($result['Result'] == 'deleted')
		{
			$deleted = $result['Result'];
			$categoryId = 1;
			$start = 0;
			$rows = 2;
			$countryId = 1;
			$myTopics = array();
			$Result = $msgbrdClient->getMyTopics($appId,$categoryId,$userId,$start,$rows);
			$myTopics = $Result[0]['results'];
		}
	}
	$result = array('userValidate' => $userId,
			'result' =>$deleted,
			'myTopics' => $myTopics);
	echo json_encode($result);
}

function deleteTopicFromCMS()
{
	$this->init(array('message_board_client','listing_client'),'');
	$appId = 12;
	$deleteFromCMS = 0;
	$msgId = $this->input->post('msgId');
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$msgbrdClient = new Message_board_client();
	$listingClient = new Listing_client();
	if($userId != 0)
	{
		$resultOfDelete = $msgbrdClient->deleteTopicFromCMS($appId,$msgId);
		$deleteFromCMS = $resultOfDelete['Result'];
		$result = $listingClient->deleteMsgbrdListing($appId,'msgbrd',$msgId);
	}
	
	//This function is only called for questions, so indexType has to be question here.
	//If this function is also called for discussion or anything other than question than the belows code
	// has to be change
	//modules::run('search/Indexer/addToQueue', $msgId, 'question', 'delete');
			
	$result = array('userValidate' => $userId,
			'result' =>$deleteFromCMS,
			'msgId' =>$msgId);
	echo json_encode($result);
}

function deleteCommentFromCMS()
{
	$this->init(array('message_board_client','listing_client'),'');
	$appId = 12;
	$deleteFromCMS = 0;
	$msgId = $this->input->post('msgId');
	$threadId = $this->input->post('threadId');
	$userIdForQuestion = $this->input->post('userId');
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$msgbrdClient = new Message_board_client();

        //Add Delete Entity Log
        $currentTime = date('Y-m-d H:i:s');
        $referrer = $_SERVER["HTTP_REFERER"];
        error_log("\r\nAnswer Deleted:::: Function = msgBoard/deleteCommentFromCMS, AnswerId = $msgId, Time = $currentTime, Deleted By = $userId, Referrer = $referrer", 3, "/tmp/deleteAnswer.log");

	if($userId != 0)
	{
		$resultOfDelete = $msgbrdClient->deleteCommentFromCMS($appId,$msgId,$threadId,$userIdForQuestion);
		$deleteFromCMS = $resultOfDelete['Result'];
	}
	
	//This function is only called for questions, so indexType has to be question here.
	//If this function is also called for discussion or anything other than question than the belows code
	// has to be change
	//We need top 10 comments of discussion, so if any of the comment gets deleted, than we have to reindex the discussion again.
	//modules::run('search/Indexer/addToQueue', $threadId, 'discussion', 'delete');
	//modules::run('search/Indexer/addToQueue', $threadId, 'discussion', 'index');
	
	$result = array('userValidate' => $userId,
			'result' =>$deleteFromCMS,
			'msgId' =>$msgId);
	echo json_encode($result);
}

function topicDetails($topicId,$seoDetails=-1,$srcPage='askHome',$actionDone='',$parmeterValues=-1,$start=0,$count=10,$filter='upvotes')
{
	
	$isDiscussionDetail = 0;
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
				if($arr == 'dscns')
				{
					$isDiscussionDetail = 1;
				}
				if ($arr == 'qna' || $arr == 'discussion' || $arr == 'announcement' || $arr == 'dscns' || $arr == 'ancmt')
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
            if(!isset($filter) && $isDiscussionDetail == 1)
            {
            	$filter = 'latest';
            }
            if (!isset($filter))
            {
                $filter = 'upvotes';
            }
	}
	$this->init();
	$msgbrdClient = new Message_board_client();
	$originalCount = $count;
	$count=10;
	$type='question';
	if( $topicId=='' || $topicId<=0 || !is_numeric($topicId) || !preg_match('/^\d+$/',$topicId) || $start<0 || !is_numeric($start) ){
	    show_404();
	}
	if($topicId>0 && preg_match('/^\d+$/',$topicId)){
		$resultTemp = $msgbrdClient->getCountCommentsToBeDisplayed($appId,$topicId);
		if(is_array($resultTemp)){
			$count = $resultTemp['count'];
			$type = $resultTemp['type'];
		}
	}
	$displayTypeInURL = $type;
	if($type=='question'){
		$displayTypeInURL = 'qna';
		$pageTypeName = 'questionDetailPage';
	}
	else if($type=='discussion'){
		$displayTypeInURL = 'dscns';
		$pageTypeName = 'discussionDetailPage';
	}
        else if($type=='announcement'){
                $displayTypeInURL = 'ancmt';
	        //In case of Announcements, display a 410 Error
                show_410();
                exit;
        }

        if($filter!='latest' && $filter!='oldest' && $filter!= 'upvotes'){
                $filter = 'upvotes';
        }

	
	//In case of Questions/discussions/announcements, get the URL from the function getSeoUrl
	//Then, check if the entered URL is same as this one. If yes, then OK. If no, then perform a 301 redirect to the correct one
	//P.S. This will be done only in case of no pagination i.e. for the first page only.
	if ( ($start==0 && $srcPage=='askHome' && $actionDone=='' && $parmeterValues==-1 && $filter=='reputation' && REDIRECT_URL=='live') || ($start>0 && $srcPage=='askHome' && $actionDone=='default' && $parmeterValues==-1 && REDIRECT_URL=='live') ){
			$url = getSeoUrl($topicId,$type);
			if($start>0){
				$url = $url . '/' . $srcPage . '/' . $actionDone . '/' . $parmeterValues . '/' . $start . '/' . $count . '/' . $filter;
			}
			$enteredURL = $this->input->server('SCRIPT_URI',true);
            $queryStr = $this->input->server('QUERY_STRING',true);
			if($url!='' && $url!=$enteredURL){
				if($queryStr!='' && $queryStr!=NULL){
				    $url = $url."?".$queryStr;

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

	//In case a dummy URL has bee created, get the correct URL and do a 301 redirect
	if($url_segments[0]=='dummy'){
		$this->load->library('Seo_client');
		$Seo_client = new Seo_client();
		$flag_seo_url = $Seo_client->getSeoUrlNewSchema($topicId,'discussion');
		$title = $flag_seo_url[1];
		$url=SHIKSHA_ASK_HOME_URL."/".seo_url_lowercase($title,"-",'','110')."-".$displayTypeInURL."-".$topicId;
		header("Location: $url",TRUE,301);
	}
	if($this->uri->segment(3)=='dummy' || $this->uri->segment(3)=='Dummy'){
		$this->load->library('Seo_client');
		$Seo_client = new Seo_client();
		$flag_seo_url = $Seo_client->getSeoUrlNewSchema($topicId,'discussion');
		$title = $flag_seo_url[1];
		$url=SHIKSHA_ASK_HOME_URL."/getTopicDetail/".$topicId."/".seo_url_lowercase($title,"-",'','110');
		header("Location: $url",TRUE,301);
	}
	//End code for redirection in Dummy case
	
	setcookie('showAnAQDPOnMobile','',time()-3600,'/',COOKIEDOMAIN);
	$appId = 12;
	$topicCountryId = 1;$closeDiscussion = 0;$displayData = array();$relatedTopics = array();$main_message = array();
	$alertName = 'on';$alertId = '';$alreadyAnswer = 0;
	$arrayOfParameters=array();
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

	$alertClient = new Alerts_client();
	
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
	$avatarURL = isset($this->userStatus[0]['avtarurl'])?$this->userStatus[0]['avtarurl']:"";
	$RelatedClient = new RelatedClient();

    //Now, check of the start and count will have some comments/answer. If not, we will redirect the user to the base page
    if($start>0 && $topicId>0){
        $this->load->model('QnAModel');
        $response = $this->QnAModel->checkForAvailableComments($userId,$topicId,$start,$count);
        if($response=='REDIRECT' || !is_numeric($originalCount) ){
             $url = getSeoUrl($topicId,$type);
             $queryStr = $this->input->server('QUERY_STRING',true);
             if($queryStr!='' && $queryStr!=NULL){
                   $url = $url."?".$queryStr;
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
	//added by akhter
	//referenceEntityId is using to show reference answer on the top of list
	if(is_numeric($_GET['referenceEntityId']) && $_GET['referenceEntityId']>0 && preg_match('/^\d+$/',$_GET['referenceEntityId'])){
		$referenceEntityId = $_GET['referenceEntityId'];
		$filter = 'upvotes';
	}else{
		$referenceEntityId = 0;
	}
	
	$typeOfSearch = $this->setTypeOfSearch();        
	$ResultOfDetails = array();
	if($topicId>0)
	$ResultOfDetails = $msgbrdClient->getMsgTree($appId,$topicId,$start,$count,1,$userId,$userGroup,$filter,$pageTypeName,$referenceEntityId);
	$answerReplies = isset($ResultOfDetails[0]['Replies'])?$ResultOfDetails[0]['Replies']:array();
	$topic_reply = isset($ResultOfDetails[0]['MsgTree'])?$ResultOfDetails[0]['MsgTree']:array();
	if(is_array($topic_reply))
	  $fromOthersTopic = $topic_reply[0]['fromOthers'];
	$totalNumOfRows = isset($ResultOfDetails[0]['totalRows'])?$ResultOfDetails[0]['totalRows']:0;
	$totalComments = isset($ResultOfDetails[0]['totalComments'])?$ResultOfDetails[0]['totalComments']:0;
	$mainAnsCount = isset($ResultOfDetails[0]['mainAnsCount'])?$ResultOfDetails[0]['mainAnsCount']:0;
	$CategoryList = isset($ResultOfDetails[0]['CategoryIds'])?$ResultOfDetails[0]['CategoryIds']:0;
	//Modifications for task on 24 March
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

	//Code added by Ankur for GA Custom variable tracking
        $displayData['subcatNameForGATracking'] = $selectedSubCategoryName;
	switch($fromOthersTopic){
		case 'user': $displayData['pageTypeForGATracking'] = 'QUESTION_DETAIL'; break;
		case 'discussion': $displayData['pageTypeForGATracking'] = 'DISCUSSION_DETAIL'; break;
		case 'announcement': $displayData['pageTypeForGATracking'] = 'ANNOUNCEMENT_DETAIL'; break;
		case 'default':  $displayData['pageTypeForGATracking'] = 'QUESTION_DETAIL'; break;
	}
//beacon tracking purpose
	switch($fromOthersTopic){
		case 'user': $displayData['trackingpageIdentifier'] = 'questionDetailPage'; break;
		case 'discussion': $displayData['trackingpageIdentifier'] = 'discussionDetailPage'; break;
		case 'announcement': $displayData['trackingpageIdentifier'] = 'announcementDetailPage'; break;
		case 'default':  $displayData['trackingpageIdentifier'] = 'questionDetailPage'; break;
	}

	if(($srcPage==='askQuestion') && (($userId == 0) || ($userId !== $questionUserId))){
		$questionInfo = isset($topic_reply[0])?$topic_reply[0]:'';
		if(is_array($questionInfo)){
			$seoUrl = getSeoUrl($questionInfo['threadId'],$type,$questionInfo['msgTxt']);
		}
		header ('HTTP/1.1 301 Moved Permanently');
  		header ('Location: '.$seoUrl);
		exit;
	}

	if($userId != 0){
		//$result = $alertClient->getMyAlertCount($appId,$userId,8);
		//$alertCountForCreateTopic = $result[0]['count'];
		$alertCountForCreateTopic = 0;
	}

	//$categoryId = is_array($topic_reply[0])?($topic_reply[0]['categoryId']):0;
	//$categoryId = (!$categoryId && is_array($parentCategories))?($parentCategories[0]):$categoryId;
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
	    //Commented by Ankur on 8 March. We are now using Google search for related data and do not require to call the below function
	    /*
	    $ResultOfRelatedData = $RelatedClient->getAllRelatedData($appId,'ask',$topicId);
	    foreach($ResultOfRelatedData as $Result){
		    if($Result['relatedProductName'] == 'listing')
			    $relatedData = json_decode($Result['relatedData']);
		    elseif($Result['relatedProductName'] == 'ask')
			    $similarQuestions = json_decode($Result['relatedData'],true);
	    }
	    */

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
						//$tempInner['userStatus'] = getUserStatus($topic_reply[$i]['lastlogintime']);
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
	   		$questionCreationDate = $topic_reply[0]['creationDate'];
			$displayData['topic_messages'] = $topic_messages;
			$topic_reply[0]['userStatus'] = getUserStatus($topic_reply[0]['lastlogintime']);
            $displayData['publishDate'] = date("Ymd",strtotime($topic_reply[0]['creationDate']));
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
	/*if($srcPage == 'askQuestion'){
		$searchResult = $msgbrdClient->getQuestionFromQuestionCategories($appId,$topicId,-1,0,10);
		$displayData['similarQuestions'] = $searchResult;
	}*/
	if(isset($ResultOfDetails[0]['MainQuestion'])){
		$main_message_temp = $ResultOfDetails[0]['MainQuestion'][0];
		if($main_message_temp['bestAnsFlag'] == 1){
			$bestAnsFlagForThread = 1;
		}
	}

        //$info  = $this->getTitle($ResultOfDetails[0]['MainQuestion'][0][msgTxt]);//print_r($info);
        //$info1 = $msgbrdClient->calViewAnswerComment($info,$topicId);
        //Start Code For related questions From Google
	if($fromOthersTopic == 'user'){
              $_POST['title'] = $main_message['msgTxt'];
			  $tmp = '';$m=0;
              $linkQuestionResult = $msgbrdClient->linkQuestionResult($topicId);
              $linkQuestionViewCount = json_decode($msgbrdClient->calViewAnswerComment($linkQuestionResult,$tmp,'false',1));
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
	      //This call is used to retrieve the Category, Country and creation date of the related questions. Since we are not displaying them on question detail page, I am commenting the same.
              /*$linkedQuestionDetails = json_decode($msgbrdClient->getSomeDetailsForGoogleResults($linkedQuestionIds));
              for($countG=0;$countG< count($linkedQuestionDetails[1]);$countG++){

              $creationDate = $linkedQuestionDetails[1][$countG];
              if(!empty($creationDate)){
                  $linkedQuestionCreationDate[] = makeRelativeTime($creationDate);
              }else{
                  $linkedQuestionCreationDate[]='';
              }

              }
              $linkedQuestionCatCountry = $linkedQuestionDetails[0];
              $linkQuestionViewCount->categoryCountry = $linkedQuestionCatCountry;
              $linkQuestionViewCount->creationDate = $linkedQuestionCreationDate;
	      */
              $linkedQuestionBestAnswerFlag = $linkQuestionViewCount->bestAnsFlag;
              $linkQuestionViewCount->bestAnsFlag = $linkedQuestionBestAnswerFlag;
			  $displayData['linkQuestionViewCount']=$linkQuestionViewCount;
              //$googleRes = $this->getDataFromGoogleSearch($topicId,'true');
	       

		if($typeOfSearch == 'QER')
		{
			$googleRes = $this->getQuestionDataFromQerSearch($main_message['msgTxt'],10, array($topicId));
		}
		else
		{
			$googleRes = $this->getDataFromGoogleSearch($main_message['msgTxt'],'','true','false','false','true');
		}
              $googleResIds = array();
              $googleResCatCountry = array();
              $googleResCreationDate = array();
              $googleResBestAnswerFlag = array();
          
              foreach($googleRes['link'] as $url){
                  $urlArray = explode("/",$url);
                  $googleResIds[] = $urlArray['4'];
              }
	      //This call is used to retrieve the Category, Country and creation date of the related questions. Since we are not displaying them on question
	      //detail page, I am commenting the same.
              /*$googleResDetails = json_decode($msgbrdClient->getSomeDetailsForGoogleResults($googleResIds));
              for($countG=0;$countG< count($googleResDetails[1]);$countG++){

              $creationDate = $googleResDetails[1][$countG];
              if(!empty($creationDate)){
		  $googleResCreationDate[] = makeRelativeTime($creationDate);
              }else{
                  $googleResCreationDate[]='';
              }

              }
              $googleResCatCountry = $googleResDetails[0];
              $googleRes['categoryCountry'] = $googleResCatCountry;
              $googleRes['creationDate'] = $googleResCreationDate;
	      */
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

    $displayData['typeOfSearch'] = $typeOfSearch;
    $displayData['referenceEntityId'] = ($referenceEntityId>0) ? $referenceEntityId : "";
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
	$displayData['answerSuggestions'] = isset($ResultOfDetails[0]['answerSuggestions'])?$ResultOfDetails[0]['answerSuggestions']:array();
	$displayData['answerSuggestions'] = $this->convertSuggestionArray($displayData['answerSuggestions']);

	if(is_array($CategoryList) && count($CategoryList) > 0) {
		$displayData['mainCategoryIdsOnPage'] = array($CategoryList[0]);
	}
	
	$googleRemarketingParams = array(
			"categoryId" 	=> $CategoryList[0],
			"subcategoryId" => $CategoryList[1],
			"countryId" 	=> 2
		); 
	
	$displayData['googleRemarketingParams'] = $googleRemarketingParams;
	
	if($actionDone==''){
	    $actionDone = 'default';
	}
	//$filterVal = '';
	if ($url_segments[0] != 'getTopicDetail') {
			$this->load->library('Seo_client');
			$Seo_client = new Seo_client();
			/*if($displayData['main_message']['msgCount'] > 10){
				$filterVal = '-'.$filter;
			}*/
			if($fromOthersTopic == 'user')
			$flag_seo_url = $Seo_client->getSeoUrlNewSchema($topicId,'question');
			else
			$flag_seo_url = $Seo_client->getSeoUrlNewSchema($topicId,'discussion');
			$title = $flag_seo_url[1];
			if($filter == 'reputation'){
			    $displayData['paginationURL'] = "/".seo_url_lowercase($title,"-",'','110')."-$displayTypeInURL-" . $topicId . '-all-'.$srcPage. '-all-'."all"."-@start@-@count@";
			}
			else{
			    $displayData['paginationURL'] = "/".seo_url_lowercase($title,"-",'','110')."-$displayTypeInURL-" . $topicId . '-all-'.$srcPage. '-all-'."all"."-@start@-@count@-".$filter;
			}
			$displayData['filterURL'] = "/".seo_url_lowercase($title,"-",'','110')."-$displayTypeInURL-" . $topicId .'-all-'.$srcPage. '-all-'."all"."-0-10-";
			if($isDiscussionDetail == 1)
			{
				$displayData['filterURL'] = "/".seo_url_lowercase($title,"-",'','110')."-$displayTypeInURL-" . $topicId .'-all-'.$srcPage. '-all-'.'all'.'-0-'.$count.'-';
			}
			
			/*Code start for Canonical Url,next url, previous Url for Question,Discussion,Annoucement Detail Page for New Urls.*/
			if($start==0){
				/*If user is on first page*/
				//$displayData['canonicalURL'] = SHIKSHA_ASK_HOME."/".seo_url_lowercase($title,"-",'','110')."-$displayTypeInURL-" . $topicId.$filterVal;
				$displayData['canonicalURL'] = SHIKSHA_ASK_HOME."/".seo_url_lowercase($title,"-",'','110')."-$displayTypeInURL-" . $topicId;
				if($totalComments>($start+$count)){
					/*If there are more page on pagination from current page.*/
					//$displayData['nextURL'] = SHIKSHA_ASK_HOME."/".seo_url_lowercase($title,"-",'','110')."-$displayTypeInURL-" . $topicId . '-all-'.$srcPage. '-all-'."all"."-".($start+$count)."-".$count;
					//$displayData['previousURL'] = '';
				}
			}else{
				if($totalComments>($start+$count)){
					/*If there are more page on pagination from current page.*/
					//$displayData['nextURL'] = SHIKSHA_ASK_HOME."/".seo_url_lowercase($title,"-",'','110')."-$displayTypeInURL-" . $topicId . '-all-'.$srcPage. '-all-'."all"."-".($start+$count)."-".$count;
				}
				if($start-$count <=0){
					/*If user is on second page of pagination.*/
					//$displayData['previousURL'] = SHIKSHA_ASK_HOME."/".seo_url_lowercase($title,"-",'','110')."-$displayTypeInURL-" . $topicId;
				}else{
					/*If user is on page othere then first or second page of pagination.*/
					//$displayData['previousURL'] = SHIKSHA_ASK_HOME."/".seo_url_lowercase($title,"-",'','110')."-$displayTypeInURL-" . $topicId . '-all-'.$srcPage. '-all-'."all"."-".($start-$count)."-".$count;
				}
				$displayData['canonicalURL'] = SHIKSHA_ASK_HOME."/".seo_url_lowercase($title,"-",'','110')."-$displayTypeInURL-" . $topicId . '-all-'.$srcPage. '-all-'."all"."-".$start."-".$count;
				//$displayData['canonicalURL'] = SHIKSHA_ASK_HOME."/".seo_url_lowercase($title,"-",'','110')."-$displayTypeInURL-" . $topicId . '-all-'.$srcPage. '-all-'."all"."-".$start."-".$count.$filterVal;
				$enteredURL = $this->input->server('SCRIPT_URI',true);
				$canonicalurl = $displayData['canonicalURL'];
				if($enteredURL!=$canonicalurl && REDIRECT_URL=='live'){
					header("Location: $canonicalurl",TRUE,301);
					exit;
				}
			}
	} else {
			if($filter == 'reputation'){
			    $displayData['paginationURL'] = "/getTopicDetail/".$topicId."/".$seoDetails."/".$srcPage."/".$actionDone."/".$parmeterValues."/@start@/@count@";
			}else{
			    $displayData['paginationURL'] = "/getTopicDetail/".$topicId."/".$seoDetails."/".$srcPage."/".$actionDone."/".$parmeterValues."/@start@/@count@/".$filter;
			}
			/*if($displayData['main_message']['msgCount'] > 10){
				$filterVal = '/'.$filter;
			}*/
			$displayData['filterURL'] = "/getTopicDetail/".$topicId."/".$seoDetails."/".$srcPage."/".$actionDone."/".$parmeterValues."/0/10/";
			/*Code start for Canonical Url,next url, previous Url for Question,Discussion,Annoucement Detail Page for Old Urls.*/
			if($start==0){
				/*If user is on first page*/
				$displayData['canonicalURL'] = SHIKSHA_ASK_HOME."/getTopicDetail/".$topicId."/".$seoDetails;
				//$displayData['canonicalURL'] = SHIKSHA_ASK_HOME."/getTopicDetail/".$topicId."/".$seoDetails.$filterVal;
				if($totalComments>($start+$count)){
					/*If there are more page on pagination from current page.*/
					//$displayData['nextURL'] = SHIKSHA_ASK_HOME."/getTopicDetail/".$topicId."/".$seoDetails."/".$srcPage."/".$actionDone."/".$parmeterValues."/".($start+$count)."/".$count;
					//$displayData['previousURL'] = '';
				}
			}else{
				if($totalComments>($start+$count)){
					/*If there are more page on pagination from current page.*/
					//$displayData['nextURL'] = SHIKSHA_ASK_HOME."/getTopicDetail/".$topicId."/".$seoDetails."/".$srcPage."/".$actionDone."/".$parmeterValues."/".($start+$count)."/".$count;
				}
				if($start-$count <=0){
					/*If user is on second page of pagination.*/
					//$displayData['previousURL'] = SHIKSHA_ASK_HOME."/getTopicDetail/".$topicId."/".$seoDetails;
				}else{
					/*If user is on page othere then first or second page of pagination.*/
					//$displayData['previousURL'] = SHIKSHA_ASK_HOME."/getTopicDetail/".$topicId."/".$seoDetails."/".$srcPage."/".$actionDone."/".$parmeterValues."/".($start-$count)."/".$count;
					//$displayData['previousURL'] = SHIKSHA_ASK_HOME."/getTopicDetail/".$topicId."/".$seoDetails."/".$srcPage."/".$actionDone."/".$parmeterValues."/".($start-$count)."/".$count.$filterVal;
				}
				$displayData['canonicalURL'] = SHIKSHA_ASK_HOME."/getTopicDetail/".$topicId."/".$seoDetails."/".$srcPage."/".$actionDone."/".$parmeterValues."/".$start."/".$count."/".$filter;
				//$displayData['canonicalURL'] = SHIKSHA_ASK_HOME."/getTopicDetail/".$topicId."/".$seoDetails."/".$srcPage."/".$actionDone."/".$parmeterValues."/".$start."/".$count.$filterVal;
				$enteredURL = $this->input->server('SCRIPT_URI',true);
				$canonicalurl = $displayData['canonicalURL'];
				if($enteredURL!=$canonicalurl && REDIRECT_URL=='live'){
					header("Location: $canonicalurl",TRUE,301);
					exit;
				}
					
			}
	}

	$displayData['totalComments'] = isset($ResultOfDetails[0]['totalComments'])?$ResultOfDetails[0]['totalComments']:$mainAnsCount;
	//End Modifications
	$displayData['tabURL'] = site_url('messageBoard/MsgBoard/discussionHome')."/1/@tab@/1";
	$displayData['caFlag'] = 'false';
	$displayData['doNoShowAnswerForm'] = false;
	if($main_message['listingType']=='institute' || $main_message['listingTypeId']!='0'){
		$this->load->model('QnAModel');
		$courseId = $this->QnAModel->getCourseIdOfQuestion($main_message['msgId']);
		if($courseId>0){
			 $this->load->model('CA/camodel');
		     $this->camodel = new CAModel();
		     
		     $this->load->model('CA/cadiscussionmodel');
		     $this->cadiscussionmodel = new CADiscussionModel();
		     
		     $this->load->library('CA/CADiscussionHelper');
		     $caDiscussionHelper =  new CADiscussionHelper();
		      
		     $this->load->builder('ListingBuilder','listing');
		     $listingBuilder = new ListingBuilder();
		     $courseRepository = $listingBuilder->getCourseRepository();
		     $courseObj = $courseRepository->find($courseId);
		      
		     $instituteId = $courseObj->getInstId();
                     if($instituteId=='' || $instituteId==0){
                         show_404();
                     }

		     $instituteRepository = $listingBuilder->getInstituteRepository();
		     $instituteObj = $instituteRepository->find($instituteId);

		     $locations = $courseObj->getLocations();
		     $currentLocation = $courseObj->getMainLocation();
		     
			 $displayData['noIndexQuestion'] = $this->QnAModel->getSiteMapFlagForQuestion($courseId , $main_message['msgId']);
			 $displayData['caFlag'] = $this->camodel->checkIfUserIsCAOfCourse($userId,$courseId);
			 $displayData['courseObj'] = $courseObj;
			 $displayData['insObj'] = $instituteObj;
				
			 //$caJoinDate = $this->cadiscussionmodel->getCAJoinDate($courseId);
			 $campusConnectData = $this->cadiscussionmodel->getCampusRepInfoForCourse(array($courseId), "course" ,$instituteId, 20, true,'true');
             $this->load->config('CA/MentorConfig',TRUE);
             $allowSubCatArr = array_keys($this->config->item('enabledSubCats','MentorConfig'));
			 $repData = $caDiscussionHelper->_separateCampusRepData($campusConnectData, $instituteId, 'course', $allowSubCatArr);
			 if(sizeof($campusConnectData['caInfo']) > 0 && ($repData['repData']['repInfo']['totalRep'] >0)) {
			 	$campusConnetAvailable = true;
			 }	
			 	 
			 $displayData['campusConnectAvailable'] = $campusConnetAvailable;
			 
			 // Get values only when campus rep is available 
			 if($campusConnetAvailable) {
			 	 $qna = array();
				 $qna = $this->cadiscussionmodel->getQnA(array('courseId' => $courseId),1,'question','',0,5,'all',0 ,array($main_message['msgId']));
				 
				 $displayData['totalQuesCount'] = $qna['total'];
				 $qna = $caDiscussionHelper->rearrangeQnA($qna['data']);
				 
				 /**
				  *description : comment these line used in old campus rep AND add new _separateCampusRepData() function for new rep data
				  *@author : akhter
				  *@team : UGC
				  **/
				 
				 //$displayData['campusRepCommentCount'] = $campusConnectData['commentCount'];
				 //$repData        = $caDiscussionHelper->formatCADataForListing($campusConnectData,3);
				 //$numberOfReps = sizeof($repData);
				 //$displayData['repData'] = $repData;
				 //$displayData['numberOfReps'] = $numberOfReps;
				 
				$displayData['repData'] = $repData['repData'];

				 //$campusConnectBadges = array();
				 //$campusConnectBadges = $caDiscussionHelper->getBadgesForCA($repData);
				 				 
				 $displayData['question_detail_page'] = true;
				 $displayData['qna'] = $qna['data'];
				 $displayData['currentLocation'] = $currentLocation;
				 $displayData['badges'] = $campusConnectBadges;

				 $categories = array();
				 $categories = $instituteRepository->getCategoryIdsOfListing($courseId,"course");
				 $coursepage_sub_cat_array = $categories;
				 $course_page_required_category = 0 ;
				 if(count($categories)>0) {
				 		
				 	foreach ($categories as $coursepage_subcat) {
				 		if(checkIfCourseTabRequired($coursepage_subcat)){
				 			$course_page_required_category = $coursepage_subcat;
				 			break;
				 		}
				 
				 	}
				 		
				 }			

				 
				 $displayData['course_page_required_category'] = $course_page_required_category;
				$tempCourses = $instituteRepository->getLocationwiseCourseListForInstitute($instituteId);
				$displayData['courses'] = "";
				$cityIds = array_keys($tempCourses);
				$course_ids = array();
				foreach($cityIds as $cid)
				{
					$course_ids = array_merge($course_ids,$tempCourses[$cid]['courselist']);
				}
				$courses = $courseRepository->findMultiple($course_ids);
				
				$categoryListByCourse 	= $instituteRepository->getCategoryIdsOfListing($course_ids, 'course', 'true', TRUE);				
				
				$breadCrumData = array();
				$breadCrumData['currentLocation'] = $currentLocation;
				$breadCrumData['categorylistByCourse'] = $categoryListByCourse;
				
 				$crumbData = Modules::run('listing/ListingPage/makeCourseBreadCrum',$breadCrumData,$instituteObj,$courseObj,'course','questionDetailPage');
				$displayData['courses'] = $courses;
				
				$displayData['breadCrumb'] = $crumbData;

		                //DO not show Answer form to the Institute owner if Campus rep is available
                		$ownerId = $courseObj->getClientId();
                		if( $ownerId==$userId ){
		                        $displayData['doNoShowAnswerForm'] = true;
                		}
				
			 }
			  
			 
			 
		}
	}
	$hasModeratorAccess = 0;
	$userGrp = ($userGroup=='cms')?$userGroup:'';
	if($userId > 0)
		$hasModeratorAccess = Modules::run("messageBoard/MessageBoardInternal/getAccessLevel", $userId, $userGrp);
	$displayData['hasModeratorAccess'] = $hasModeratorAccess;
	//Code to get the Edit form data if the logged in user is the owner and no activity has been done on the Post
	if( ($userId == $main_message['userId'] && (count($topic_messages[0]) <= 1)) || ($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3)  || $displayData['caFlag']=='true'){
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
	//Code End to get the Edit form data if the logged in user is the owner and no activity has been done on the Post
	if($srcPage == 'network'){
		
    }else{
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
        /************************************************************************************/
        ////QnA Rehash Phase-2 Start code for reputation and edit link on question detail page
        /************************************************************************************/
        $res = json_decode($msgbrdClient->getUserReputationPoints($appId,$userId));
        $displayData['reputationPoints'] = $res[0]->reputationPoints;
        $displayData['showEditLink'] = $ResultOfDetails[0]['showEditLink'];
        /************************************************************************************/
        ////QnA Rehash Phase-2 End code for reputation and edit link on question detail page
        /************************************************************************************/
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
            $discussionArr = $msgbrdClient->getRelatedSearchDiscussion($topicId,$discussionText);//print_r($discussionArr);
            //$linkQuestionResult = $msgbrdClient->linkQuestionResult($topicId);
            $linkDiscussionViewCount = json_decode($msgbrdClient->calViewAnswerComment($discussionArr,$topicId,'false',1,'discussion'));
            //$displayData['discussionSearch'] = $discussionArr;
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

       if($catcountry[0]['countryId']==2 || $catcountry[0]['countryId']==0){
               $regionId = 0;
       }
       else{
               $this->load->builder('LocationBuilder','location');
               $locationBuilder = new LocationBuilder;
               $locationRepository = $locationBuilder->getLocationRepository();
               $regionId = $locationRepository->findCountry($catcountry[0]['countryId'])->getRegionId();
       }
	
        if($CategoryList[0]!='' || $CategoryList[1]!=''){
       $quick_links = $blog_client->getArticleWidgetsData('quick_links',$CategoryList[0],$CategoryList[1],$catcountry[0]['countryId'],$regionId);
       $latest_news = $blog_client->getArticleWidgetsData('latest_news',$CategoryList[0],$CategoryList[1],$catcountry[0]['countryId'],$regionId);
		$this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $categoryName = $categoryClient->get_category_name(12, $CategoryList[0]);
        $displayData['selectedSubCategoryName'] = $categoryName;
        //_P($CategoryList);
 
	$displayData['quickLinks'] = $quick_links;
	$displayData['latestNews'] = $latest_news;
	$requestURL->setData(array('categoryId'=>$CategoryList[0],'subCategoryId'=>$CategoryList[1],'countryId'=>$catcountry[0]['countryId']));
	$displayData['quickLinkURL'] = $requestURL->getURL();
	}

        $userLevel = $msgbrdClient->getUserLevel($appId,$userId,"AnA");
        $displayData['loggedInUserLevel'] = (is_array($userLevel))?$userLevel[0]['Level']:'Beginner';
    //echo "subcat".$fromOthersTopic; 
    
    // added for course pages related change
	$displayData['tab_required_course_page'] = checkIfCourseTabRequired($CategoryList[1]);
	$displayData['subcat_id_course_page'] = $CategoryList[1];	
	$displayData['cat_id_course_page'] =  $CategoryList[0];
	$displayData['course_pages_tabselected'] = 'AskExperts'; 
	if(isset($displayData['subcat_id_course_page']) && $displayData['subcat_id_course_page']>0){
		$cp_msg_txt = "All questions page"; 
		$backlink_url = $this->coursepagesurlrequest->getAskExpertsTabUrl($displayData['subcat_id_course_page']);
		if($fromOthersTopic == 'discussion') {
			$cp_msg_txt = "All discussions page"; 
			$displayData['course_pages_tabselected'] = 'Discussions';
			$backlink_url = $this->coursepagesurlrequest->getDiscussionsTabUrl($displayData['subcat_id_course_page']); 
		} 
		$displayData['cpgs_backLinkArray'] = array("MESSAGE" => $cp_msg_txt, "LANDING_URL" => $backlink_url);	
	}


	//this is used for stroing data into beacon varaible for tracking purpose
	 $displayData['trackingpageNo']=$topicId;
	 $displayData['trackingcountryId']=2;
	 $displayData['trackingPaginationKey']=($start/$count)+1;
	 $displayData['trackingcatID']=$CategoryList[0];
	 $displayData['trackingsubCatID'] = $CategoryList[1];
	 $displayData['trackingAuthorId'] = $displayData['topic_messages'][0][0]['userId'];

	 // add app links for facebook and twitter to enable question/discussion links shared to be opened in shiksha app directly
	 if(in_array($type, array('question', 'discussion'))){
		$displayData['app_linking']['android_facebook']['app_name']        = "Shiksha Ask & Answer";
		$displayData['app_linking']['android_facebook']['package']         = "com.shiksha.android";
		$displayData['app_linking']['android_facebook']['should_fallback'] = "true";
		$displayData['app_linking']['android_facebook']['url']             = $displayData['canonicalURL'];

		$displayData['app_linking']['android_twitter']['app_name'] = "Shiksha Ask & Answer";
		$displayData['app_linking']['android_twitter']['app_id'] = "com.shiksha.android";
		$displayData['app_linking']['android_twitter']['url'] = $displayData['canonicalURL'];
	 }
	 
	 $this->tracking=$this->load->library('common/trackingpages');
         $this->tracking->_pagetracking($displayData);
	
	    
      $this->tracking->getDetailPageKeys($fromOthersTopic,$displayData);
    if($type=='discussion' || $type=='question')
		$displayData['alternate'] = "android-app://com.shiksha.android/https/".$_SERVER['SERVER_NAME']."/".seo_url_lowercase($title,"-",'','110')."-$displayTypeInURL-" . $topicId;
	if($fromOthersTopic == 'user'){
	    //$ListingClientObj = new Listing_client();
	    //$listings = $ListingClientObj->getInterestedInstitutes($appId,$randomCategory,1,0,5);
	    //$displayData['relatedListings'] = $listings;
	    if(!$randomCategory || $randomCategory=='' || $randomCategory==0) $randomCategory = 149;
	    $displayData['randomCategory'] = $randomCategory;
	    /*Code to create Hidden URL and Hidden Code by Pranjul Start*/
	    /*$this->load->spamcontrol('spamcontrol/SpamControl');
	    $hcSC = new HiddenCodeGenerator();
	    $hiddenCode = $hcSC->generateCode(5);
	    $actualCode = $hcSC->getCode();
	    $huSC = new HiddenUrlGenerator();
	    $displayData['hiddenURL'] = $huSC->createHiddenUrl('Url','hidden','URL','spamUrl','');
	    $displayData['hiddenCode'] = $hiddenCode;
	  */
	  /*
	    $displayData['trackingpageNo']=$topicId;
	     $this->tracking=$this->load->library('common/trackingpages');
                $this->tracking->_pagetracking($displayData);*/

        

	   	    /*Code to create Hidden URL and Hidden Code by Pranjul End*/
        if((count($main_message) <=0) || (isset($main_message['status'])) && (($main_message['status'] == 'deleted')||($main_message['status'] == 'abused'))){
	        //show_404();
                //In case a question is deleted or abused, redirect the user to Ask homepage
                $url = SHIKSHA_ASK_HOME;
                header("Location: $url",TRUE,301);
                exit;
        }
	    $this->load->view('messageBoard/topicDetails',$displayData);
	}
	else{
	    $displayData['functionToCall'] = 'refreshPage';
	    $this->load->view('messageBoard/topicDetails_others',$displayData);
	}
    }
}

function getReplyDetailsForDiscussionComment()
{
	$commentId = $_POST['entityId']!='' ? $this->input->post('entityId') : 0;
	$userId    = $this->input->post('userId');
	$count     = $this->input->post('count');
	$start     = 0;
	$this->load->library('v1/AnACommonLib');
        $anaCommonLib = new AnACommonLib();
	$replyData = $anaCommonLib->formatReplyDetailsPagination($commentId,$userId,$start,$count);
	$displayData = array();
	$displayData['childDetails'] = $replyData['childDetails'];
	$displayData['userId'] = $userId;
	$displayData['trackingPageKeyId']  = isset($_POST['tracking_keyid'])?$this->input->post('tracking_keyid'):'';
	$this->load->view('messageBoard/getReplyDetailsForDiscussionComment',$displayData);
}

function getQuestionDataFromSearchPart()
{
	
	$typeOfSearch = $this->setTypeOfSearch();
	if($typeOfSearch == 'QER')
	{
		$this->getQuestionDataFromQerPart();
	}
	else
	{
		$this->getQuestionDataFromGooglePart();
	}
}


function getQuestionDataFromGooglePart(){
    $questionTitle = $this->input->post('title');
    $displayData['googleRes'] = $this->getDataFromGoogleSearch($questionTitle,'',true,false);
    echo $this->load->view('/common/googleRelatedSearchQuestionPart',$displayData);
}
function getQuestionDataFromQerPart(){
   	$questionTitle = $this->input->post('title');
   	$questionId    = $this->input->post('questionId');
   	$questionTitle = trim($questionTitle);
    
     $displayData['typeOfSearch'] = 'QER';

     $this->load->library('v1/SearchRelatedEntities');
     $SearchRelatedEntities = new SearchRelatedEntities();
     $excludeThreads = array();
     if($questionId)
     	$excludeThreads['excludeThreads'] = array($questionId);
     $displayData['googleRes'] = $SearchRelatedEntities->getRelatedThreadByText($questionTitle, $excludeThreads, 10);
     echo $this->load->view('/common/qerRelatedSearchQuestionPart',$displayData);
}


function updateDigValue(){
	$this->init(array('message_board_client'),'');
	$appId = 12;
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$msgbrdClient = new Message_board_client();
	$msgId = $this->input->post('msgId');
	$digValue = $this->input->post('digValue');
	$pageType = $this->input->post('type');

    //below line is used for conversion tracking purpose
	$trackingPageKeyId=$this->input->post('tracking_keyid');
	if(!isset($trackingPageKeyId))
		$trackingPageKeyId=NULL;
	if($userId != 0)
	{
		$digResult = $msgbrdClient->updateDigVal($appId,$userId,$msgId,$digValue,'qna',$pageType,$trackingPageKeyId);
		$resultOfDig = $digResult['Result'];
	}
	$result = array('result' =>$resultOfDig);
	echo json_encode($result);
}

/*
function chooseBestAns(){
	$this->init(array('message_board_client'),'');
	$appId = 12;
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$msgbrdClient = new Message_board_client();
	$msgId = $this->input->post('msgId');
	$threadId = $this->input->post('threadId');
	$commentUserId = $this->input->post('commentUserId');
	$doClose = 	$this->input->post('close');
	$resultOfDig = 'error';
	$doCloseRes = 'error';
	if($userId != 0){
		if(($threadId!='') && ($msgId != '') && ($commentUserId != '')){
			$bestAnsResult = $msgbrdClient->setBestAnsForThread($appId,$userId,$threadId,$msgId,$commentUserId,$doClose);
                        $this->load->library(array('facebook','Register_client','facebook_client','alerts_client','Listing_client'));
                        $fbClient = new facebook_client();
                        $facebook = new Facebook();
                        $result = $fbClient->getFbPostSettings($commentUserId, 'publishBestAnswerAndLevelActivity');
                        error_log("user id best ".print_r($commentUserId,true));
                        if($result=='1'){
                        $data = $fbClient->getAccessToken_AnA($commentUserId);
                        $access_token = $data[0][access_token];
                        $fbData = $fbClient->getDataForFacebook(1,$commentUserId,'bestanswer',$threadId,$msgId);
                        $level = ($fbData[0]['level']=='')?'Beginner':$fbData[1]['level'];
                        $textVal = ($level=="Advisor")?"an":"a";
                        $message = $textVal.' '.$level.' at Shiksha Café on Shiksha.com – India’s leading education and career website, has received the Best Answer rating for their answer. Congratulations '.$fbData[1][displayname].'!';
                        //$message = $fbData[0][displayname].' selected '.$fbData[1][displayname]. '\'s answer i.e.'.$fbData[1]['msgTxt'].' as The Best Answer';
                        $link = SHIKSHA_HOME."/getUserProfile/".$fbData[1]['displayname']."/Answer";
                        if($data[0][access_token]!='noresult'){
                        $attachment = array("access_token" => $access_token,
                                                            "name" => "Q: ".$fbData[0]['msgTxt'],
                                                            "message" => $message,
                                                            "link" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['msgTxt']),
                                                            //"name" => $fbData[0]['postText'],
                                                            //"link" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['postText']),
                                                            "picture" => 'http://www.shiksha.com/public/images/90x90_3.jpg',
                                                            "link" => 'http://ask.shiksha.com',
                                                            //"description" => $fbData[0]['msgTxt'],
                                                            "description" => "Best Answer: ".$fbData[1]['msgTxt'],
                                                            "properties" => array("In" =>  array(
                                                                              "text" => "Shiksha.com's - ".$fbData[0]['name']." Channel",
                                                                              "href" => "http://ask.shiksha.com/messageBoard/MsgBoard/discussionHome/".$fbData[0]['categoryId']
                                                                              )
                                                                          ),
                                                             "actions" => array("name" => "View My Answers",
							                        "link" => $link
							      )
                                                      );
			$facebookLogData = $message."##".$fbData[0]['msgTxt']."##".$fbData[1]['msgTxt'];
                        $topicDetails = $msgbrdClient->setFBWallLog(1,$userId,$access_token,$facebookLogData,S_REMOTE_ADDR);
                        try{    
			$facebook->api('/me/feed', 'post', $attachment);
			}catch(Exception $e) {
			  $this->exceptionLogOfFBPost($exceptionMessage);			
			}
                            }
                        }
			$resultOfDig = $bestAnsResult['Result'];
			$doCloseRes = $bestAnsResult['closeFlag'];
			if($resultOfDig == 'success'){
				$this->sendMailToUSerForBestAnswer($bestAnsResult['seoUrl'],$bestAnsResult['msgTxt'],$threadId,$msgId,$commentUserId);
			}
		}

	}
	$result = array('result' =>$resultOfDig);
	echo json_encode($result);
}
*/
function exceptionLogOfFBPost($exceptionMessage){
		$myFile = FB_EXCEPTION_LOG_FILE;
		$fh = fopen($myFile, 'a') or die("can't open file");
		$stringData = $exceptionMessage."\n";
		fwrite($fh, $stringData);
	}

//This function will send mail to all the users on selection of best answer
function sendMailToUSerForBestAnswer($seoUrl,$msgTxt,$threadId,$msgId,$commentUserId){
	$this->init(array('message_board_client'),'');
	$appId = 1;
	$msgbrdClient = new Message_board_client();
	$ResultOfDetails = $msgbrdClient->getBestAnswerMailData($appId,$threadId,$msgId,$commentUserId);
	if(is_array($ResultOfDetails)){
	  $mtData = $ResultOfDetails[0]['mtData'];
	  $ratingData = $ResultOfDetails[0]['ratingData'];
	  //Get the Question text and owner name
	  for($i=0;$i<count($mtData);$i++){
	    if($mtData[$i]['parentId']==0){
	      $questionText = $mtData[$i]['msgTxt'];
	      $questionOwnerName = (trim($mtData[$i]['firstname']) != '')?$mtData[$i]['firstname']:$mtData[$i]['displayname'];
	    }
	  }
	  $answerUserName = '';
	  $answerText= '';
	  //Send mail to the answer owner
	  for($i=0;$i<count($mtData);$i++){
	    if($msgId==$mtData[$i]['msgId']){
	      $answerUserName = (trim($mtData[$i]['firstname']) != '')?$mtData[$i]['firstname']:$mtData[$i]['displayname'];
	      $answerText = $mtData[$i]['msgTxt'];
	      $this->sendBestAnswerMail($mtData[$i],$threadId,$msgId,$seoUrl,$answerText,$questionOwnerName,$questionText,true,$answerUserName);
	    }
	  }
	  $userIdList = array();
	  $questionUserId = 0;
	  //Send mail to all the other users
	  for($i=0;$i<count($mtData);$i++){
	    if((!(in_array($mtData[$i]['userId'],$userIdList))))
	    {
	      if(($msgId!=$mtData[$i]['msgId'])&&($mtData[$i]['parentId']!=0) &&($mtData[$i]['userId']!=$commentUserId)){
		array_push($userIdList, $mtData[$i]['userId']);
		$this->sendBestAnswerMail($mtData[$i],$threadId,$msgId,$seoUrl,$answerText,$questionOwnerName,$questionText,false,$answerUserName);
	      }
	      else if(($msgId!=$mtData[$i]['msgId'])&&($mtData[$i]['parentId']==0)){
		$questionUserId = $mtData[$i]['userId'];
	      }
	    }
	  }

	  for($i=0;$i<count($ratingData);$i++){
	    if((!(in_array($ratingData[$i]['userId'],$userIdList))))
	    {
	      if(($ratingData[$i]['userId']!=$commentUserId) && ($ratingData[$i]['userId']!=$questionUserId)){
		array_push($userIdList, $ratingData[$i]['userId']);
		$this->sendBestAnswerMail($ratingData[$i],$threadId,$msgId,$seoUrl,$answerText,$questionOwnerName,$questionText,false,$answerUserName);
	      }
	    }
	  }

	}
	return true;
}

function sendBestAnswerMail($dataArr,$threadId,$msgId,$seoUrl,$msgTxt,$questionOwnerName,$questionText,$isAnswerOwner,$answerUserName)
{
    $this->init(array('message_board_client'),'');
    $appId = 1;
    $fromAddress="noreply@shiksha.com";
    $urlOfLandingPage = SHIKSHA_ASK_HOME;
    if($isAnswerOwner){
      $subject = 'Congratulations! Your answer has been selected as the Best Answer.';
      $contentArr['type'] = "bestAnsMail";
    }
    else{
      $subject = $answerUserName.'\'s answer is selected as the Best Answer';
      $contentArr['type'] = "bestAnsMailAll";
    }
    $NameOfUser = (trim($dataArr['firstname']) != '')?$dataArr['firstname']:$dataArr['displayname'];
    $userEmail = $dataArr['email'];
    $contentArr['url'] = $urlOfLandingPage;
    $contentArr['seoUrl'] = $seoUrl;
    $contentArr['ratingUrl'] = SHIKSHA_HOME.'/messageBoard/MsgBoard/mailAction/rating/'.$dataArr['userId']."/".$threadId."/".$msgId;
    $contentArr['NameOfUser'] = $NameOfUser;
    $contentArr['msgTxt'] = strlen($msgTxt)>300?substr($msgTxt,0,297).'...'.'<a href="'.$contentArr['seoUrl'].'<!-- #AutoLogin --><!-- AutoLogin# -->">more</a>':$msgTxt;
    $contentArr['questionOwnerName'] = $questionOwnerName;
    $contentArr['questionText'] = $questionText;
    $contentArr['answerOwnerName'] = $answerUserName;
    $contentArr['mail_subject'] = $subject;
    //$content=$this->load->view("search/searchMail",$contentArr,true);
    //$AlertClientObj = new Alerts_client();
    //$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$content,"html");
    Modules::run('systemMailer/SystemMailer/answerSelectedAsBestAnswer', $userEmail, $contentArr);
}

function topicDetailsPage($topicId,$start,$rows)
{
	$this->init(array('message_board_client','ajax'));
	$appId = 12;
	$topicCountryId = 1;$closeDiscussion = 0;
	$displayData = array();
	$topic_reply = array();
	$main_message = array();
	$alertName = 'on';
	$alertId = '';
	$msgbrdClient = new Message_board_client();
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$avatarURL = isset($this->userStatus[0]['avtarurl'])?$this->userStatus[0]['avtarurl']:"";

	$ResultOfDetails = $msgbrdClient->getMsgTree($appId,$topicId,$start,$rows,1,$userId);
	if(isset($ResultOfDetails[0]['MsgTree'])){
		$topic_reply = $ResultOfDetails[0]['MsgTree'];
	}
	$answerReplies = isset($ResultOfDetails[0]['Replies'])?$ResultOfDetails[0]['Replies']:array();
	//Modifications for task on 24 March
	$catcountry = isset($ResultOfDetails[0]['categoryCountry'])?$ResultOfDetails[0]['categoryCountry']:array();
	$levelVCard = isset($ResultOfDetails[0]['levelVCard'])?$ResultOfDetails[0]['levelVCard']:array();
	$questionCatCountry = '';
	if(isset($catcountry[0]['category']) && isset($catcountry[0]['country']))
	  $questionCatCountry = $catcountry[0]['category']."-".$catcountry[0]['country']." ";
	$levelVCardArray = array();
	for($i=0;$i<count($levelVCard);$i++)
	{
	    $userID = $levelVCard[$i]['userid'];
	    $levelVCardArray[$userID]['level'] = $levelVCard[$i]['ownerLevel'];
	    $levelVCardArray[$userID]['vcardStatus'] = $levelVCard[$i]['vcardStatus'];
	}
	//End Modifications

	if(isset($ResultOfDetails[0]['MainQuestion'])){
		$main_message = $ResultOfDetails[0]['MainQuestion'][0];
		if($main_message['status'] == 'closed'){
			$closeDiscussion = 1;
		}
		if($main_message['bestAnsFlag'] == 1){
			$bestAnsFlagForThread = 1;
		}
	}

	if($start == 0)
	{
		$topic_reply = array_slice($topic_reply,1);
	}

	$displayData['topicId'] = $topicId;

	if(is_array($topic_reply) && count($topic_reply) > 0)
	{
		$topic_messages = array();
		$i = -1;
		foreach($topic_reply as $key => $temp)
		{
			if(!is_array($topic_messages[0]))
				$topic_messages[0] = array();

			if(substr_count($temp['path'],'.') == 1)
				break;

			array_push($topic_messages[0],$temp);
			$i = 0;
		}
		foreach($topic_reply as $key => $temp)
		{
			if($temp['bestAnsFlag'] == 1){
				$bestAnsFlagForThread = 1;
			}
			if(substr_count($temp['path'],'.') == 1)
			{
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
		$displayData['topic_messages'] = $topic_messages;
	}
	$displayData['main_message'] = $main_message;
	$displayData['bestAnsFlagForThread'] = $bestAnsFlagForThread;
	$displayData['alertId'] = $alertId;
	$displayData['appId'] = $appId;
	$displayData['topicId'] = $topicId;
	$displayData['closeDiscussion'] = $closeDiscussion;
	$displayData['fromOthers'] = $main_message['fromOthers'];
	$displayData['userId'] = $userId;
	$displayData['pageKeySuffixForDetail'] = 'ASK_ASKDETAIL_MIDDLEPANEL_';
	$displayData['validateuser'] = $this->userStatus;
	//Modifications for Task on 24 March
	$displayData['questionCatCountry'] = $questionCatCountry;
	$displayData['levelVCard'] = $levelVCardArray;
	$displayData['userImageURL'] = $avatarURL;
	//End Modifications
	$this->load->view('messageBoard/topicPage',$displayData);

}
function replyMsg($parentId = "",$page, $postReply=""){
	$this->init(array('message_board_client','register_client','ajax'));
	$flag = 0;
	$status = 0;
	$subComment = true;
	$appId = 12;
	$selectedCategoryName = '';
	$newEntityArray = array('discussion','announcement','review','eventAnA');
	$registerClient = new register_client();
	$displayName = isset($this->userStatus[0]['displayname'])?$this->userStatus[0]['displayname']:'';
	$firstName = isset($this->userStatus[0]['firstname'])?trim($this->userStatus[0]['firstname']):'';
	$lastName = isset($this->userStatus[0]['lastname'])?trim($this->userStatus[0]['lastname']):'';
	
	//below line is used for conversion tracking purpose
	$trackingPageKeyId=isset($postReply['tracking_keyid']) ? $postReply['tracking_keyid']: (!empty($_POST['tracking_keyid'])?$this->input->post('tracking_keyid'):null);

	
	if((!is_array($this->userStatus)) && ($this->userStatus == "false")){
		$userId = '';
		$status = 1;
	}else{
		$userId = $this->userStatus[0]['userid'];
	}
	$data = array();
	$returnValue = "";
	if(isset($_POST['appendThread']) && ($this->input->post('appendThread') == 'false'))
	{
		$str = 'threadid';
		$str1 = 'seccode';
		$str2 = 'dotCount';
		$str3 = 'replyText';
		$str4 = 'fromOthers';
		$str5 = 'displayname';
		$str6 = 'mainAnsId';
		$str7 = 'actionPerformed';
		$str8 = 'functionToCall';
		$str9 = 'sortFlag';
		$str10 = 'displaynameId';
		$str11 = 'userProfileImage';
		$str12 = 'immediateParentId';
		$str13 = 'immediateParentName';
		$str14 = 'mentionedUsers';
	}
	else
	{
		$str = 'threadid'.$parentId;
		$str1 = 'seccode'.$parentId;
		$str2 = 'dotCount'.$parentId;
		$str3 = 'replyText'.$parentId;
		$str4 = 'fromOthers'.$parentId;
		$str5 = 'displayname'.$parentId;
		$str6 = 'mainAnsId'.$parentId;
		$str7 = 'actionPerformed'.$parentId;
		$str8 = 'functionToCall'.$parentId;
		$str9 = 'sortFlag'.$parentId;
		$str10 = 'displaynameId'.$parentId;
		$str11 = 'userProfileImage'.$parentId;
		$str12 = 'immediateParentId'.$parentId;
		$str13 = 'immediateParentName'.$parentId;
		$str14 = 'mentionedUsers'.$parentId;
	}
	/*Code added by pranjul to validate spam start*/
	/*$this->load->spamcontrol('spamcontrol/SpamControl');
	$nt = new SpamControlStrategy(array('HiddenCodeGenerator','HiddenUrlGenerator'),array(array($_POST['hiddenCode']),array($_POST['URL'])));
	$res = $nt->execute();
	if($res['HiddenCodeGenerator']['status']=='false'){
		echo $status = 'HiddenCode';
		return;
	}
	if($res['HiddenUrlGenerator']['status']=='false'){
		echo $status = 'HiddenUrl';
		return;
	}*/
	/*Code added by pranjul to validate spam end*/
	//Added by Ankur on 6 July for @Mention task
	$mentionedNames = isset($postReply[$str14]) ? $postReply[$str14] : (isset($_POST[$str14]) ? $this->input->post($str14) : '');

	$actionToBePerformed = isset($postReply[$str7]) ? $postReply[$str7] : (isset($_POST[$str7]) ? $this->input->post($str7) :'addComment');
	
	if(strcmp($actionToBePerformed,'editAnswer') == 0) $mentionedNames = isset($_POST['EmentionedUsers'.$parentId])?$this->input->post('EmentionedUsers'.$parentId):'';
	$fromOthers = isset($postReply[$str4]) ? $postReply[$str4] : (isset($_POST[$str4]) ? $this->input->post($str4) : 'user');

	if($actionToBePerformed=='addComment' && ($fromOthers == 'user' || $fromOthers == 'blog' || in_array($fromOthers,$newEntityArray)))
	{
	    if(isset($_POST['appendThread']) && ($this->input->post('appendThread') == 'false'))
	      $str3 = 'replyCommentText';
	    else
	      $str3 = 'replyCommentText'.$parentId;
	 }

	$functionToCall = isset($_POST[$str8])?$this->input->post($str8):'-1';
	$userProfileImage = isset($_POST[$str11])?$this->input->post($str11):'';
	$mainAnsId = isset($postReply[$str6]) ? $postReply[$str6] : (isset($_POST[$str6])?$this->input->post($str6):0);
	
	if($fromOthers != "user" && $fromOthers != "blog" && (!in_array($fromOthers,$newEntityArray)))
	{
	   	$secCodeIndex = isset($postReply['secCodeIndex']) ? $postReply['secCodeIndex'] : (isset($_POST['secCodeIndex'])?$this->input->post('secCodeIndex'):'security_code');
	   	$secCode = $this->input->post($str1);
	   	if(!verifyCaptcha($secCodeIndex,$secCode)){
	   		$status = 'SCF';
	   	}
	}
	if((isset($postReply[$str]) && $userId != '') || isset($_REQUEST[$str]) && ($userId != ''))
	{
		//$userDetails = $registerClient->userdetail($appId,$userId);
		$requestIp = S_REMOTE_ADDR;
		$threadId = isset($postReply[$str]) ? $postReply[$str] : $this->input->post($str);

		$dotCount = $this->input->post($str2) + 1;

		if($threadId == $parentId)
		$subComment = false;
		$text = str_replace("&gt;","",str_replace("&lt;","",trim(isset($postReply[$str3]) ? $postReply[$str3] : $_REQUEST[$str3])));
		$this->load->model('QnAModel');
		if(isset($mentionedNames) && $mentionedNames != ''){
                          $text = $this->changeTextForAtMention($text,$mentionedNames);
                }

                if( ($mainAnsId==0 && $fromOthers!='blog') || (strcmp($actionToBePerformed,'editAnswer') == 0) ){
                        $response = $this->QnAModel->checkForSameAnswerInPreviousSevenDays($userId,trim($text),$threadId);
                        if($response>0){
                                echo $status = 'SAMEANS';
                                if(isset($postReply[$str7]) && $postReply[$str7] !='') { setcookie('mobile_post_suc_msg',$status,0,'/',COOKIEDOMAIN); return;}else{ exit;}
                        }
                }
		//$text = trim($_REQUEST[$str3]);
		//$text = $this->input->xss_clean($text);
		$replyToDisplayName = $this->input->post($str5);
		$replyToDisplayNameId = $this->input->post($str10);
		$replyTosortFlag = $this->input->post($str9);
		$executeOperation = true;
		$immediateParentId = (isset($_POST[$str12]) && ($this->input->post($str12)!=''))?$this->input->post($str12):$parentId;
		if($fromOthers != "user" && $fromOthers != "blog" && (!in_array($fromOthers,$newEntityArray)))
		{
			$secCodeIndex = isset($postReply['secCodeIndex']) ? $postReply['secCodeIndex'] : (isset($_POST['secCodeIndex'])?$this->input->post('secCodeIndex'):'security_code');
			if(!verifyCaptcha($secCodeIndex,$secCode,1))
			  $executeOperation = false;
		 }
		 if($executeOperation){
			$msgbrdClient = new Message_board_client();
			$res = json_decode($msgbrdClient->getUserReputationPoints($appId,$userId));
                        if($res[0]->reputationPoints<=0 && $res[0]->reputationPoints!='9999999'){
                            echo $status = 'NOREP';
                            if(isset($postReply[$str7]) && $postReply[$str7] !='') { setcookie('mobile_post_suc_msg',$status,0,'/',COOKIEDOMAIN); return;}else{ exit;}

                        }
			if(strcmp($actionToBePerformed,'editAnswer') == 0){
				$msgDetailsArray = array();
		                //In case @Mention was added by the user, please add the special characters surrounding it
                		if(isset($mentionedNames) && $mentionedNames != ''){
		                      $text = $this->changeTextForAtMention($text,$mentionedNames);
                		}
				$msgDetailsArray['msgId'] = $parentId;
				$msgDetailsArray['msgTxt'] = $text;
				$msgDetailsArray['requestIP'] = $requestIp;
				
				//Disable links in answers/comments
				if( $this->spamCheck($text,$requestIp) )
					$returnValue = 1001001;		
				else
					$returnValue = $msgbrdClient->editMsgDetails($appId,$msgDetailsArray,$userId);
				$returnValue = $returnValue['Result'];
			}else{
				//In case @Mention was added by the user, please add the special characters surrounding it
				if(isset($mentionedNames) && $mentionedNames != ''){
				      $text = $this->changeTextForAtMention($text,$mentionedNames);
				}
				
				//Disable links in answers/comments
				if( $this->spamCheck($text,$requestIp) )
					$returnValue = 1001001;		
				else
					$returnValue = $msgbrdClient->postReply($appId,$userId,$text,$threadId,$immediateParentId,$requestIp,$fromOthers,$displayName,$mainAnsId,$trackingPageKeyId);
                if( ! is_numeric ($returnValue) && $returnValue != 'Edited' ){
                    echo $returnValue;
                    if(isset($postReply[$str7]) && $postReply[$str7] !='') { setcookie('mobile_post_suc_msg',$returnValue,0,'/',COOKIEDOMAIN); return;}else{ exit;}
                }else{
		    // set cookie for success reply/comment from mobile on mobule run
		    if(isset($postReply[$str7]) && $postReply[$str7] !='' && $returnValue >0) {
			$cmntText = 'comment';
			if($actionToBePerformed == 'addAnswer'){$cmntText = 'answer';}
			setcookie('mobile_post_suc_msg','Thank you, your '.$cmntText.' has been successfully submitted.',0,'/',COOKIEDOMAIN); return;
			}	
		}
		
			}
			if(is_numeric($returnValue) || $returnValue == 'Edited')
			{
				if($mainAnsId == 0){
					$mainAnsId = $returnValue;
				}
				$msgId = $returnValue;
		                $msgMentionId = $returnValue;
                		if($returnValue == 'Edited') $msgMentionId = $parentId;
				$data['msgId'] = $msgId;
				$flag = 1;
				$this->userStatus = $this->checkUserValidation(); //This is just to update the cookie after comment is been posted Need to chage afterwards.
				//Added by Ankur on 6 July for @Mention task
				if(isset($mentionedNames) && $mentionedNames != ''){
				      if($fromOthers == 'user' && $mainAnsId == 0 || ($fromOthers == 'user' && $returnValue == 'Edited'))
					  $this->sendAtMentionMailers($mentionedNames,$msgMentionId,$userId,$text,'answer');
				      elseif(($fromOthers == 'user') && ($mainAnsId > 0))
					  $this->sendAtMentionMailers($mentionedNames,$msgMentionId,$userId,$text,'answerComment');
				      else if(($fromOthers == 'discussion'))
					  $this->sendAtMentionMailers($mentionedNames,$msgMentionId,$userId,$text,'discussionComment');
				}
				//Added by Ankur on 4 Dec for storing/updating suggestions on Answer
				$strSuggestion = 'suggestedInstitutes'.$parentId;
				if(isset($_POST['suggestedInstitutes']) && $this->input->post('suggestedInstitutes')!='' && $fromOthers == 'user'){
					$this->storeSuggestedInstitutes($this->input->post('suggestedInstitutes'),$msgMentionId);
				}
				else if(isset($_POST[$strSuggestion]) && $this->input->post($strSuggestion)!='' && $fromOthers == 'user'){
					$this->storeSuggestedInstitutes($this->input->post($strSuggestion),$msgMentionId);
				}
				//In case this is a reply. Added on 6th July
				if($immediateParentId!=$parentId && $fromOthers=='discussion'){
				      $immediateParentName = (isset($_POST[$str13]) && ($this->input->post($str13)!=''))?$this->input->post($str13):'';
				      //if($immediateParentName!='')
					//$this->sendAtMentionMailers($immediateParentName,$msgId,$userId,$text,'discussionComment','reply');
				}
				
				//Set cookie for the newly posted Entity. This is while posting Answer/Comments
				setcookie  ('latestThreadId',$threadId, time() + (60 * 5),'/',COOKIEDOMAIN);
				
			}
			if($returnValue == 'Edited'){
				$status = (count($returnValue) <= 8)?$returnValue:'USE'; //unspecified error.
			}
		}

	}
	
	$data['dotCount'] = $dotCount;
	$data['threadId'] = $threadId;
	$data['text'] = $text;
	$data['subComment'] = $subComment;
	$data['replyToDisplayName'] = $replyToDisplayName;
	$data['replyToDisplayNameId'] = $replyToDisplayNameId;
	$data['replyTosortFlag'] = $replyTosortFlag;
	$data['fromOthers'] = $fromOthers;
	$data['mainAnsId'] = $mainAnsId;
	$data['actionToBePerformed'] = $actionToBePerformed;
	$data['postedDate'] = 'few seconds ago';
	$data['maximumCommentAllowed'] = 4;
	$data['parentId'] = $parentId ;
	$data['displayName'] = $displayName;
	if($page=='campusTab' || $page == 'campusTab_quesDetail'){
		$data['nameToBeDisplayed']= $firstName.' '.$lastName;
	}
	
	$data['displayNameId'] = $userId;
	$data['functionToCall'] = $functionToCall;
	$data['flag'] = $flag;
	$data['status'] = $status;
	$data['commentUserId'] = $userId;
	$data['userProfileImage'] = $userProfileImage;
	$data['userGroup'] = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
	$data['immediateParentName'] = (isset($_POST[$str13]) && ($this->input->post($str13)!=''))?$this->input->post($str13):'';
	if((!(strrpos($_SERVER['HTTP_REFERER'], "getTopicDetail")))&&(!(strrpos($_SERVER['HTTP_REFERER'], "getArticleDetail")))  &&  (!(strrpos($_SERVER['HTTP_REFERER'], "qna")))  && (!(strrpos($_SERVER['HTTP_REFERER'], "discussion"))) && (!(strrpos($_SERVER['HTTP_REFERER'], "announcement"))) && (!(strrpos($_SERVER['HTTP_REFERER'], "dscns"))) && (!(strrpos($_SERVER['HTTP_REFERER'], "ancmt")))  )
	  $data['isWall'] = "1";
	
	if($userId!='' && $page=='campusTab'){
		 $this->load->view('messageBoard/replymsg_campusTab',$data);
	}else if($userId!='' && $page=='mobileReply'){
		$data['firstName'] = $firstName;
		$data['lastName']  = $lastName;
		$this->load->view('mAnA5/campusRep/comment_view_page',$data);
	}else if($userId!='' && $page=='campusDashboard'){
		$this->load->view('CA/dashboard/commentHTML',$data);
	}else if($userId!='' && $page=='myshortlist_overlay'){
		$this->load->view('messageBoard/commentInOverlayHTML',$data);
	}else if($userId!='' && $page=='campusTab_quesDetail'){
		$this->load->view('messageBoard/replymsg_quesDetail_new',$data);
	}else if($fromOthers == "user" || in_array($fromOthers,$newEntityArray) || $fromOthers == "")
	{
	  $data['firstName'] = $firstName;
	  $data['lastName']  = $lastName;
	  $this->load->view('messageBoard/replymsg_quesDetail',$data);
	}else if($fromOthers == "blog" && $actionToBePerformed !='addComment' && $userId!='' && $page == 'adpComment'){
        $this->load->helper('string');
        $data['trackingPageKeyId'] = $trackingPageKeyId;
        $data['page'] = $page;
        $data['totalReply'] = 0;
        $data['replyTrackingKey'] = 2027;
        $this->load->view('mAnA5/commentSnippet',$data);
    }else if($fromOthers == "blog" && $actionToBePerformed =='addComment' && $userId !='' && $page == 'adpComment'){
        $this->load->helper('string');
        $data['msgTxt'] = $data['text'];
        unset($data['text']);
        $finalData['replyArr'][] = $data;
        $this->load->view('mAnA5/replySnippet',$finalData);
    }
	else if($fromOthers == "blog" && $actionToBePerformed=='addComment' && $userId!='')
          $this->load->view('messageBoard/replymsg_blogComment',$data);
	else if($fromOthers == "blog" && $actionToBePerformed!='addComment' && $userId!='')
	  $this->load->view('messageBoard/replymsg_entity',$data);
	else
	  $this->load->view('messageBoard/replymsg',$data);
}

function indexMessageBoard($threadId)
{
    $this->init();
    error_log_shiksha("Entering IndexMessageBoard");
    $msgbrdClient = new Message_board_client();
    $result = $msgbrdClient->indexMessageBoard($threadId);
}

function answerPostSuccessPage($start=0,$count=10){
	$this->init();
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	if($userId == 0){
		header('LOCATION:'.SHIKSHA_ASK_HOME);
		exit;
	}

	$displayData = $this->getDataForActivityLandingPages('answer',$start,$count);
	$this->load->view('messageBoard/answerPostedPage',$displayData);
}

function questionPostSuccessPage($start=0,$count=10){
	$this->init();
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	if($userId == 0){
		header('LOCATION:'.SHIKSHA_ASK_HOME);
		exit;
	}
	$displayData = $this->getDataForActivityLandingPages('question',$start,$count);
	$this->load->view('messageBoard/questionPostedPage',$displayData);
}

private function getDataForActivityLandingPages($type,$start,$count){
	$this->init(array('message_board_client'));
	$msgbrdClient = new Message_board_client();

	$appId = 1;	$typeOtions = array('answer','question');
	$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
	if((!isset($type)) && (!in_array($type,$typeOtions))){
		return;
	}
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$displayData = array();
	$displayData['topContributtingAndExpertPanel'] = $this->getTopContributorsAndExpertData();
	$displayData['leaderBoardInfo'] = $this->getLeaderBoardInfo($userId);
	if($type == 'answer'){
		$userQuestionAndAnswer = $this->getUserLastQnA($userId,'answer');
		$displayData['userQuestionAndAnswer'] = $userQuestionAndAnswer;
		$userRecentAnswersQestion = (isset($userQuestionAndAnswer[1]) && is_array($userQuestionAndAnswer[1]))?$userQuestionAndAnswer[1]['question']:array();
		$displayData['answerCount'] = isset($userRecentAnswersQestion['answerCount'])?$userRecentAnswersQestion['answerCount']:0;

		if(is_array($displayData['userQuestionAndAnswer'][1])){
			$threadId = $displayData['userQuestionAndAnswer'][1]['question']['threadId'];
		}
	}else{
		$displayData['userQuestionAndAnswer'] = $this->getUserLastQnA($userId,'question');
		if(is_array($displayData['userQuestionAndAnswer'][0])){
			$threadId = $displayData['userQuestionAndAnswer'][0]['threadId'];
			if($displayData['userQuestionAndAnswer'][0]['answerCount'] > 0){
				header('LOCATION:'.SHIKSHA_ASK_HOME);
				exit;
			}
		}
		$questionId = is_array($displayData['userQuestionAndAnswer'][0])?$displayData['userQuestionAndAnswer'][0]['threadId']:0;
		if($questionId != 0){
			$alertClient = new Alerts_client();
			$WidgetStatus = $alertClient->getWidgetAlert($appId,$userId,8,'byComment',$questionId);
			$displayData['WidgetStatus'] =	$WidgetStatus;
		}
	}
	$userPoints = (array_key_exists('leaderBoardInfo',$displayData) && is_array($displayData['leaderBoardInfo']))?$displayData['leaderBoardInfo']['userPointValue']:'N.A.';
	$displayData['userGroup'] = $userGroup;
	$displayData['averageTimeToAnswer'] = $this->getAverageTimeForAswer();
	$displayData['questionList'] = $this->getQuestionsFromCategoryOfActivity($threadId,$start,$count,1);
	$displayData['userActivityAcknowledge'] = $this->getPointsToReachNextLevel($userPoints);
	$displayData['infoWidgetData'] = $this->getDateForInfoWidget();
	$displayData['recentActivityThreadId'] = $threadId;
	$displayData['validateuser'] = $this->userStatus;
	$vcardStatus = $msgbrdClient->getVCardStatus(1,$userId);
	$displayData['cardStatus'] = $vcardStatus['status'];
	return $displayData;
}

private function getAverageTimeForAswer(){
	$appId = 1;
	$msgbrdClient = new Message_board_client();
	return $ResultArray = $msgbrdClient->getAverageTimeForAswer($appId);
}

function getQuestionsFromCategoryOfActivity($threadId,$start,$count,$return){
	$this->init();
	$appId = 1;
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	if(isset($_POST['threadId'])){
		$threadId = $this->input->post('threadId');
		$start = $this->input->post('startFrom');
		$count = $this->input->post('countOffset');
		$return = 0;
	}
	$msgbrdClient = new Message_board_client();
	$Result = $msgbrdClient->getQuestionForActivityLandingPages($appId,$userId,$threadId,$start,$count);
	if(is_array($Result[0])){
		$questionListArray = &$Result[0]['results'];
	}
	for($i=0;$i<count($questionListArray);$i++){
		$questionListArray[$i]['creationDateForQuestion'] = makeRelativeTime($questionListArray[$i]['creationDate']);
	}
	if($return==1){
		return $Result;
	}else{
		echo json_encode($Result);
	}
}

private function getDateForInfoWidget(){
	$appId = 1;
	//$msgbrdClient = new Message_board_client();
	//return $msgbrdClient->getDataForInformationWidgetInAnA($appId);
	return array();
}
private function getTopContributorsAndExpertData($countOfMostContributtinguser=5,$countOfExpertPanel=3){
	$appId = 1;
	$msgbrdClient = new Message_board_client();
	return $msgbrdClient->getMostContributingUser($appId,$countOfMostContributtinguser,$countOfExpertPanel);
}

private function getLeaderBoardInfo($userId){
	$appId = 1;
	$msgbrdClient = new Message_board_client();
	if((trim($userId)!='') && ($userId > 0)){
		$Result = $msgbrdClient->getUserInfoForLeaderBaord($appId,$userId);
		$leaderBoardInfo = (isset($Result[0]) && is_array($Result[0]))?$Result[0]:array();
		return $leaderBoardInfo;
	}else{
		return array();
	}
}

private function getUserLastQnA($userId,$questionOrAnswer){
	$appId = 1;
	$msgbrdClient = new Message_board_client();
	if((trim($userId)!='') && ($userId > 0)){
		$Result = $msgbrdClient->getLastQnAOfUser($appId,$userId,$questionOrAnswer);
		$userQuestionAndAnswer = is_array($Result)?$Result:array();
		return $userQuestionAndAnswer;
	}else{
		return array();
	}
}

private function getPointsToReachNextLevel($userPoints){
	$appId = 1;
	if(!is_array($this->userStatus[0])){
		return;
	}
	$registerClient = new register_client();
	$Result = $registerClient->getUserPointLevel($appId);
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	if(!is_numeric($userPoints)){
		$userDetails = $registerClient->userdetail($appId,$userId,'AnA');
		$userPoints = is_array($userDetails[0])?$userDetails[0]['userPoints']:0;
	}
	$userPointLevel = $Result['Results'];
	$pointsNeedToReachNextLevel = 0;
	$nextLevel = '';
	for($i=1;$i<count($userPointLevel);$i++){
		if($userPoints < $userPointLevel[0]['minlimit']){
			$pointsNeedToReachNextLevel = $userPointLevel[0]['minlimit'] - $userPoints;
			$nextLevel = $userPointLevel[0]['level'];
			break;
		}
		if(($userPoints > $userPointLevel[$i-1]['minlimit']) && ($userPoints < $userPointLevel[$i]['minlimit'])){
			$pointsNeedToReachNextLevel = $userPointLevel[$i]['minlimit'] - $userPoints;
			$nextLevel = $userPointLevel[$i]['level'];
			break;
		}
	}
	return array('pointsNeedToReachNextLevel' => $pointsNeedToReachNextLevel,'nextLevel' => $nextLevel);

}

/* Not in use */
/*
function editorialBinQuestionForGlobalAnAWidget($categoryId,$countryId,$start,$count,$useRandom=0,$return=0){
	$userStatus = $this->checkUserValidation();
	$userId = isset($userStatus[0]['userid'])?$userStatus[0]['userid']:0;
	$this->load->model('QnAModel');
	$response = $this->QnAModel->getDataForGlobalAnAWidget($userId,$categoryId,$countryId,$start,$count,$useRandom);
	if($return==0){
		return $response;
	}else{
		echo json_encode($response);
	}
}
*/

private function clearCacheForUser(){
    $this->load->library('cacheLib');
    $cacheLib = new CacheLib;
    $coookie=isset($_COOKIE['user'])?$_COOKIE['user']:'';
    $cacheLib->clearCache('user');
    return;
}

function getRelatedQuestion_post_question($questionDetail='')
{
    $returnIt = true;
    if(isset($_POST['questionDetail'])){
        $questionDetail = $this->input->post('questionDetail');
        $returnIt = false;
    }
    if(isset($_POST['start'])){
        $start = $this->input->post('start');
    } else {
        $start = 0;
    }
    if(isset($_POST['rows'])){
        $rows = $this->input->post('rows');
    } else {
        $rows = 5;
    }
    $this->load->library(array('message_board_client','listing_client'));
    $searchResult=array();
    $location="";
    $countryId='';
    $categoryId='';
    $appId=1;
    $ListingClientObj = new Listing_client();
    $searchResult = $ListingClientObj->listingSponsorSearch($appId,$questionDetail,$location,$countryId,$categoryId,$start,$rows,'ask','','',1);
    if(is_array($searchResult)){
    	$ResultArray = is_array($searchResult)?$searchResult['results']:array();
		if(count($ResultArray) > 0){
			$threadIdCsv = -1;
			for ($i = 0; $i < count($ResultArray);$i++) {
				$threadIdCsv .= ($threadIdCsv != -1)?(",".$ResultArray[$i]['typeId']):$ResultArray[$i]['typeId'];
			}
			$msgbrdClient = new Message_board_client();
			$RelatedQuestionAnswers = $msgbrdClient->getDataForRelatedQuestions(1,$threadIdCsv);
			$RelatedQuestionAnswers = is_array($RelatedQuestionAnswers)?$RelatedQuestionAnswers['Results']:array();
			for ($i = 0; ($i < count($searchResult['results'])) && (count($RelatedQuestionAnswers) > 0);$i++) {
				$threadId = $searchResult['results'][$i]['typeId'];
				$searchResult['results'][$i]['answerDetail'] = $RelatedQuestionAnswers[$threadId];
			}
		}
    }else{
        $searchResult['results'] = array();
    }
    if($returnIt){
        return $searchResult;
    }else{
        echo json_encode($searchResult);
    }
}


function questionPostLandingPage(){
    $this->init();
    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
    /*if($userId == 0){
        header('LOCATION:'.SHIKSHA_ASK_HOME);
        exit;
    }*/
	$listingType = isset($_REQUEST['listingTypeForAskQuestion'])?$_REQUEST['listingTypeForAskQuestion']:'';
	$listingTypeId = isset($_REQUEST['listingTypeIdForAskQuestion'])?$_REQUEST['listingTypeIdForAskQuestion']:0;
    if (($_REQUEST['questionText']) && (!isset($_COOKIE['globalLandingPagePostAna']))) {
        $questionText = $_REQUEST['questionText'];
        setcookie('commentContent',$questionText,0,'/',COOKIEDOMAIN);
    }elseif(isset($_COOKIE['commentContent'])){
        $questionText = $_COOKIE['commentContent'];
    }
	if($questionText == ""){
        if($_REQUEST['questionText'] == ""){
            header('LOCATION:'.SHIKSHA_ASK_HOME);
            exit;
        }else{
            $questionText = $_REQUEST['questionText'];
        }
    }
    $displayData['pageViewed'] = 'ask';
    $displayData['infoWidgetData'] = $this->getDateForInfoWidget();
    $this->load->library('category_list_client');
    $categoryClient = new Category_list_client();
    $displayData['categoryList'] = $categoryClient->getCategoryTree($appId, 1);
    $displayData['questionText'] = $questionText;
	$displayData['listingType'] = $listingType;
	$displayData['listingTypeId'] = $listingTypeId;
	$displayData['validateuser'] = $this->userStatus;
    $displayData['countryList'] = $countryList = $this->getCountries();
    $displayData['averageTimeToAnswer'] = $this->getAverageTimeForAswer();
    $this->load->view('messageBoard/questionPostLandingPage',$displayData);
}

function populatEditWithAjax(){
	$this->init();
	$questionText = $this->input->post('questionText');
	echo shiksha_formatIt(insertWbr(htmlspecialchars($questionText),32));
}

function getReportAbuseForm(){
	$this->init(array('message_board_client','ajax'),array('url'));
	$appId = 1;
	$msgbrdClient = new Message_board_client();
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$Result = $msgbrdClient->getReportAbuseForm($appId,"QuestionAnswer");
	$displayData['reportAbuseFields'] = is_array($Result)?$Result:array();
	$displayData['url'] = '/messageBoard/MsgBoard/setReportAbuseReason';

        $res = json_decode($msgbrdClient->getUserReputationPoints($appId,$userId));
        $displayData['reputationPoints'] = $res[0]->reputationPoints;
     
     //below line is used for conversion tracking purpose
        $trackingPageKeyId=$this->input->post('tracking_keyid');
        if(isset($trackingPageKeyId))
        {
        	$displayData['trackingPageKeyId']=$trackingPageKeyId;
        }
	//return $reportAbuseFields;
	echo $this->load->view('messageBoard/reportAbuseForm',$displayData);
}

function setReportAbuseReason(){
$this->init(array('message_board_client'),array('shikshautility'),'');
	$msgId = $this->input->post('msgIdReportAbuse');
	$ownerId = $this->input->post('ownerIdReportAbuse');
	$parentId = $this->input->post('parentIdReportAbuse');
	$threadId = $this->input->post('threadIdReportAbuse');
	$reasonIdList = $this->input->post('chosenReasonList');
	$reasonIdText = $this->input->post('chosenReasonText');
	$entityTypeShown = $this->input->post('entityTypeReportAbuse');
	$eventId = $this->input->post('eventIdReportAbuse');
	$articleId = $this->input->post('articleIdReportAbuse');
	$entityName = getAbuseEntityName($entityTypeShown);
	if($this->input->post('abuseReason') == 6)
	{
		$otherReason = $this->input->post('chosenReasonText');
	}
	//below line is used for conversion tracking purpose
	$trackingPageKeyId=$this->input->post('tracking_keyid');
	
	if($entityTypeShown == '' && $parentId==$threadId){
                $entityName = 'answer';
        }else if($entityTypeShown == '' && $parentId!=$threadId && $parentId>0){
                $entityName = 'comment';
        }


	$appId = 12;
	$resultOfAbuse = 0;
	$secImg = 0;
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	//{
	    $secImg = 1;
	    $msgbrdClient = new Message_board_client();
	    if($userId != 0)
	    {
	      //Get the user level
	      
              $res = json_decode($msgbrdClient->getUserReputationPoints($appId,$userId));
              if($res[0]->reputationPoints<=0 && $res[0]->reputationPoints!='9999999'){
                        $result = array(
			'result' =>'NOREP');
                  echo json_encode($result);

              }else{
	      //Get the points for abuse based on user level
	      $userLevel = $msgbrdClient->getUserLevel($appId,$userId,"AnA");
	      if(is_array($userLevel))
	      {
		//Save the log in the DB and set the total abuse points + abuse flag
	    $this->load->helper('abuse');
	    $abuseRating = getAbusePointsFromLevelId($userLevel[0]['levelId']);
		if($entityTypeShown == ''){
		  if($parentId == 0)
		    $entityType = "Question";
		  else if($parentId == $threadId)
		    $entityType = "Answer";
		  else if($parentId != $threadId)
		    $entityType = "Comment";
		}
		else
		  $entityType = $entityTypeShown;

		$userLevel = ($userLevel[0]['Level']!='')?$userLevel[0]['Level']:'Beginner-Level 1';

		if($entityType == 'Event' || $entityType == 'Event Comment')
		    $topEntityId = $eventId;
		else if($entityType == 'Blog Comment')
		    $topEntityId = $articleId;
		else
		    $topEntityId = $threadId;
		$resultOfAbuse = $msgbrdClient->setAbuseRecord($appId,$userId,$userLevel,$abuseRating,$msgId,$reasonIdList,$entityType,$topEntityId,$otherReason,$trackingPageKeyId);
		if(!(is_array($resultOfAbuse)))
		    $results = 0;
		else
		{
		    $results = $resultOfAbuse[0]['results'];


		    //If abuse flag is set then set the entity as deleted
		    if($resultOfAbuse[0]['delete'] == 1 && $entityType == "Event" && $eventId>0)
		    {
			  $this->load->library('event_cal_client');
			  $eventObj = new Event_cal_client();
			  $deleteResult = $eventObj->deleteEvent($appId,$eventId,2,1);

		    }
		    else if($resultOfAbuse[0]['delete'] == 1 && $parentId == 0){
			  $deleteResult = $msgbrdClient->deleteTopicFromCMS($appId,$msgId,'abused');
			  $this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
			  $this->load->model('AnAModel');
			  $reportAbuseEntityData = $this->AnAModel->getReportAbuseEntityData($msgId,'question');
			  $reportAbuseUserData = $this->AnAModel->getReportAbuseUserData($msgId);
	
			  foreach($reportAbuseUserData as $key=>$val){ 
			       $reportAbuser[] = $val['userId'];
			       $reportAbuseDate[] = $val['creationDate'];   
			  }
			  
			  if($reportAbuseEntityData[0]['fromOthers'] == 'user'){
			      $threadType = 'question';
			      $reportAbuseThreadData = $this->AnAModel->getReportAbuseThreadData($threadId);
			  }else if($reportAbuseEntityData[0]['fromOthers'] == 'discussion'){
			      $threadType = $reportAbuseEntityData[0]['fromOthers'];
			      $reportAbuseThreadData = $this->AnAModel->getReportAbuseThreadData($threadId+1);
			  }
			  
			  error_log('====redis auto delete call====');
			   $this->appNotification->addAutoDeleteNotificationToRedis($entityName,$reportAbuseEntityData[0]['entityTitle'],$threadType,$reportAbuser,$reportAbuseDate,$ownerId,$reportAbuseThreadData[0]['threadTitle']);
			   
			   //Add the entry in Redis for Personalized Homepage
			   if(strtolower($entityTypeShown) == 'question' || strtolower($entityTypeShown) == 'discussion'){
				$this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
				$this->userinteractioncachestoragelibrary->deleteEntity($ownerId,$threadId,$threadType);
			   }
			  
		    }else if($resultOfAbuse[0]['delete'] == 1 && $parentId>0 ){
			  $deleteResult = $msgbrdClient->deleteCommentFromCMS($appId,$msgId,$threadId,$ownerId,'abused');
			  $this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
			  $this->load->model('AnAModel');
			  $reportAbuseEntityData = $this->AnAModel->getReportAbuseEntityData($msgId,'answer');
			  $reportAbuseUserData = $this->AnAModel->getReportAbuseUserData($msgId);
	
			  foreach($reportAbuseUserData as $key=>$val){ 
			       $reportAbuser[] = $val['userId'];
			       $reportAbuseDate[] = $val['creationDate'];   
			  }
			  
			  if($reportAbuseEntityData[0]['fromOthers'] == 'user'){
			      $threadType = 'question';
			      $reportAbuseThreadData = $this->AnAModel->getReportAbuseThreadData($threadId);
			  }else if($reportAbuseEntityData[0]['fromOthers'] == 'discussion'){
			      $threadType = $reportAbuseEntityData[0]['fromOthers'];
			      $reportAbuseThreadData = $this->AnAModel->getReportAbuseThreadData($threadId+1);
			  }
			
			error_log('====redis auto delete call====');
	                  $this->appNotification->addAutoDeleteNotificationToRedis($entityName,$reportAbuseEntityData[0]['entityTitle'],$threadType,$reportAbuser,$reportAbuseDate,$ownerId,$reportAbuseThreadData[0]['threadTitle']);
			  
			  //Add the entry in Redis for Personalized Homepage
			   if(strtolower($entityTypeShown) == 'question' || strtolower($entityTypeShown) == 'discussion'){
				$this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
				$this->userinteractioncachestoragelibrary->deleteEntity($ownerId,$threadId,$threadType);
			   }else if(strtolower($entityTypeShown) == 'answer' || $entityTypeShown == 'discussion Comment' || $entityTypeShown == 'discussionComment'){
				$this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
				$this->userinteractioncachestoragelibrary->deleteEntity($ownerId,$threadId,$threadType,$entityTypeShown);	
			   }
		    }

		    //Send the mail to the users for abuse and/or deleted
		    if($results=='1')
		    {
		      if($resultOfAbuse[0]['delete'] == 1)
			$this->sendAbuseMail($ownerId,$userId,$msgId,$entityType,$reasonIdText,true);
		      else
			$this->sendAbuseMail($ownerId,$userId,$msgId,$entityType,$reasonIdText,false);
		    }
		}

	      }
	    
	//}
	$result = array('userValidate' => $userId,
			'result' =>$results,
			'msgId' => $msgId,
			'secimg' => $secImg);
	echo json_encode($result);
        }
    }
    else{
	$result = array('userValidate' => $userId,
			'result' =>array());
	echo json_encode($result);
    }

}

function sendAbuseMail($ownerId,$userId,$msgId,$entityType,$reasonIdText,$sendDeleteMail){
        error_log_shiksha("CONTROLLER sendAbuseMail ");
	$this->init(array('message_board_client','alerts_client','MailerClient'),array('shikshautility'));
	$msgbrdClient = new Message_board_client();
        $userDetails = $msgbrdClient->getUserDetails(1,$ownerId);
        $details = $userDetails[0];
        $email = $details['email'];
        $fromMail = "info@shiksha.com";
        $ccmail = "sales@shiksha.com";
        $urlOfCommunityPage = SHIKSHA_HOME."/shikshaHelp/ShikshaHelp/communityGuideline";
        $contentArr['name'] = ($details['firstname']=='')?$details['displayname']:$details['firstname'];
        $contentArr['communityUrl'] = $urlOfCommunityPage;
	$entityDetails = $msgbrdClient->getMsgText(1,$msgId,$entityType);
	$MailerClient = new MailerClient();
	$entityText = '';
	$entityURL = '';
	if(is_array($entityDetails)){
	  $entityText = $entityDetails[0]['msgTxt'];
	  //$entityURL = $MailerClient->generateAutoLoginLink(1,$email,$entityDetails[0]['url']);
	  $entityURL = $entityDetails[0]['url'];
	}
	$reasonList = explode(":",$reasonIdText);
	$contentArr['reasons'] = array($reasonList,'struct');

	//Get the section name and url based on the entity type
	if($entityType == 'Blog Comment'){
	  $urlOfLandingPage = SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList';
	  $contentArr['section'] = 'Shiksha.com Articles';
	}
	else if($entityType == 'Event Comment' || $entityType == 'Event'){
	  $urlOfLandingPage = SHIKSHA_EVENTS_HOME;
	  $contentArr['section'] = 'Shiksha.com Important Dates';
	}
	else{
	  $urlOfLandingPage = SHIKSHA_ASK_HOME;
	 // $contentArr['section'] = 'Shiksha Caf&#233;';
	   $contentArr['section'] = 'Shiksha Ask & Answer';
	}
	//$contentArr['sectionURL'] = $MailerClient->generateAutoLoginLink(1,$email,$urlOfLandingPage);
	$contentArr['sectionURL'] = $urlOfLandingPage;
	//End code for section name and url
	$entityType = getAbuseEntityName($entityType);
	$contentArr['entityType'] = $entityType;
    $this->load->model('UserPointSystemModel');
	$contentArr['urlOfUntitledQues']='';
    $res = json_decode($msgbrdClient->getUserReputationPoints($appId,$ownerId));
    error_log("reputation point ===>>>>>>>>>>>>>>>>>>>".print_r($res,true));
    if($sendDeleteMail){	//Mail when the entity has deleted automatically
		  $subject = "Your ".$entityType." is deleted on account of abuse reports [Moderation Pending].";
		  $contentArr['type'] = 'abuseAutoDeleteMail';
		  $contentArr['entityText'] = $entityText;
		  $contentArr['receiverId'] = $ownerId;
		  $contentArr['subject'] = $subject;
		  $contentArr['fromMail'] = $fromMail;
		  $contentArr['ccmail'] = $ccmail;

	      if((int)$res['reputationPoints']>25 && (int)$res['reputationPoints']!=9999999){
	        $urlOfLandingPageUQ = SHIKSHA_ASK_HOME;
	        $contentArr['urlOfUntitledQues'] = $MailerClient->generateAutoLoginLink(1,$email,$urlOfLandingPageUQ.'/1/4/1/untitledQuestion');
	        //$contentArr['urlOfUntitledQues'] = $urlOfLandingPageUQ.'/1/4/1/untitledQuestion';
	      }
	      
	      Modules::run('systemMailer/SystemMailer/abuseAutoDeleteMail', $email, $contentArr);

	}
	else{	//Mail when an entity has been reported abuse by a user
	/***
	// Commenting this code as we do not need to notify entity owner of report abuse(MAB-1507)
	  $subject = "A user has reported your ".$entityType." as abuse.";
	  $contentArr['type'] = 'abuseReportMail';
	  $contentArr['subject'] = $subject;
	  $contentArr['receiverId'] = $ownerId;
 	  $contentArr['fromMail'] = $fromMail;
	  $contentArr['ccmail'] = $ccmail;
	  $contentArr['entityText'] = strlen($entityText)>300?substr($entityText,0,297).'...'.'<a href="'.$entityURL.'<!-- #AutoLogin --><!-- AutoLogin# -->">more</a>':$entityText;

      if((int)$res['reputationPoints']>25 && (int)$res['reputationPoints']!=9999999){
            $urlOfLandingPageUQ = SHIKSHA_ASK_HOME;
            $contentArr['urlOfUntitledQues'] = $MailerClient->generateAutoLoginLink(1,$email,$urlOfLandingPageUQ.'/1/4/1/untitledQuestion');
      }

     Modules::run('systemMailer/SystemMailer/abuseReportMail', $email, $contentArr);
	***/
	}
       /* $content = $this->load->view("search/searchMail",$contentArr,true);
        $mail_client = new Alerts_client();
        $response= $mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",$ccmail);*/
	//Added to send the mail when the entity is deleted to People who have reported the entity as Abuse
	if($sendDeleteMail){
		$msgbrdClient->sendMailToAbusePeople(1,$msgId);
	}
}

function getUserVCardDetails(){
	$this->init(array('message_board_client','ajax'),array('url','shikshautility','image'));
	$appId = 12;
	$userId=base64_decode($this->input->post('userId'));
	$vcardArray=array();
	if(!empty($userId)){
		$msgbrdClient = new Message_board_client();
		$userDetails = $msgbrdClient->getUserVCardDetails($appId,$userId);
	}

	if(is_array($userDetails)){
	    $arrayOfUsers = array();
	    $mainVCardArr = $userDetails;
	    $userDetails = $userDetails[0]['VCardDetails'];
	    $userStatus = getUserStatus($userDetails[0]['lastlogintime']);
	    $userProfile = site_url('getUserProfile').'/'.$userDetails[0]['displayname'];

	    //if(in_array($userId,$userFriends)){
	    //	    $userDetails[0]['isFriend'] = 'true';
	    //}else{
		  $userDetails[0]['isFriend'] = 'false';
	    //}
	    if(isset($userDetails[0]['firstname']))
	    {
	      if(isset($userDetails[0]['lastname']))
		  $userDetails[0]['userName'] = $userDetails[0]['firstname'].' '.$userDetails[0]['lastname'];
	    }
	    $userDetails[0]['userStatus'] = $userStatus;
	    $userDetails[0]['userProfile'] = $userProfile;
	    $userDetails[0]['userOnlineStatus'] = getUserStatusToolTip($userStatus,$userDetails[0]['displayname'],$userDetails[0]['lastlogintime']);
	    $userDetails[0]['mailMsg'] = MAIL_TO_USER.$userDetails[0]['displayname'];
	    $userDetails[0]['addNetworkMsg'] = ADD_TO_NETWORK.$userDetails[0]['displayname'];
	    $userDetails[0]['alreadyAddedToNetworkMsg'] = $userDetails[0]['displayname'].' '.ALREADY_ADDED_TO_NETWORK;
	    array_push($vcardArray,array($userDetails,'struct'));

	    if(isset($mainVCardArr[0]['otherUserDetails'])){
		array_push($vcardArray,array($mainVCardArr[0]['otherUserDetails'],'struct'));
	    }

	    if(isset($mainVCardArr[0]['participateUserDetails'])){
		array_push($vcardArray,array($mainVCardArr[0]['participateUserDetails'],'struct'));
	    }

	    $followUser = $this->getFollowUser('',$userId,"false");
	    array_push($vcardArray,array($followUser,'struct'));

	    //Check of the user is a Campus ambassador. 
	    $this->load->model('QnAModel');
	    $isCampusAmbassador = $this->QnAModel->checkIfUserIsCampusAmbassador($appId,$userId);
	    array_push($vcardArray,array($isCampusAmbassador,'struct'));
	    
	}

	echo json_encode($vcardArray);
}

/* For posting the question from ask institute widget */
  	function askQuestionFromListing($postQuestionData){
		$this->init();
		$appId = 1;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userPointValue = 100;
		$msgbrdClient = new Message_board_client();
		$registerClient = new register_client();
		$ListingClientObj = new Listing_client();
		$userPointRes = ($userPointValue >= 10)?'success':'exhaust';
		if((isset($_POST['encoded']) && $this->input->post('encoded')=='1') || ($postQuestionData['encoded'] ==1)){
			$questionText = ($this->input->post('questionText')) ? $this->input->post('questionText') : $postQuestionData['questionText'];
			$questionText = urldecode($questionText);
			$courseId = $this->input->post('courseId');
	    } else {
                 $questionText = ($this->input->post('questionText')) ? $this->input->post('questionText') : $postQuestionData['questionText'];
		}
  	    $nameOfUser = $this->input->post('nameOfUserForAskInstitute');
  	    $emailOfUser = $this->input->post('emailOfUserForAskInstitute');
  	    $mobileOfUser = $this->input->post('mobileOfUserForAskInstitute');
  	    $instituteId = ($this->input->post('instituteId')) ? $this->input->post('instituteId') : $postQuestionData['instituteId'];
  	    $categoryId = ($this->input->post('categoryId')) ? $this->input->post('categoryId') : $postQuestionData['categoryId'];
  	    $locationId = ($this->input->post('locationId')) ? $this->input->post('locationId') : $postQuestionData['locationId'];
		$courseId = ($this->input->post('courseId')) ? $this->input->post('courseId') : $postQuestionData['courseId'];
		$this->load->model('QnAModel');
		
		//$secCode = $this->input->post('secCodeForAskInstitute');
		//$secCodeIndex = $this->input->post('secCodeIndex');
		$secCodeResult = "SCF";
		$alreadyRegistered = 0; // LoggedIn user
		if($userId == 0){
				$alreadyRegistered = 2; // Not loggedIn and not registered user.
				$available = $registerClient->checkAvailability($appId,$emailOfUser,'email');
				if($available == 1){
						$alreadyRegistered = 1; //not loggedin but registered.
				}
		}
  	    $threadId = 0;
		$secCodeResult = "SCC";
		if($userId > 0){
			$userDetails = $registerClient->userdetail($appId, $userId);
			$userPointValue = isset($userDetails[0]['userPoints'])?$userDetails[0]['userPoints']:0;
		}else{
			$userarray = array();
			$this->load->model('UserPointSystemModel');
			$userarray['email'] = $emailOfUser;
			$userarray['firstname'] = htmlentities(addslashes(trim($nameOfUser)));
			$userarray['displayname'] = htmlentities(addslashes(trim($nameOfUser)));
			$userarray['mobile'] = $mobileOfUser;
			$userarray['password'] = 'shiksha@'.rand(1,1000000);
			$userarray['ePassword'] = sha256($userarray['password']);
			$userarray['coordinates'] = $this->input->post('coordinates_ForAskInstitute');
			$userarray['resolution'] = $this->input->post('resolution_ForAskInstitute');
			$userarray['sourceurl'] = $this->input->post('referer_ForAskInstitute');
			$userarray['sourcename'] = $this->input->post('loginproductname_ForAskInstitute');

			$userarray['quicksignupFlag'] = 'veryshortregistration';
			$userarray['usergroup'] = 'veryshortregistration';
			$userId = intval($this->UserPointSystemModel->doQuickRegistration($userarray,0,'default',true));
			if($userId == 0){
				$secCodeResult = "SCF";
			}
  	    }

  	    $requestIp = S_REMOTE_ADDR;
        $callBE = true;
		     
		     //Disable links in Questions from Listing
		$pos = strpos($questionText,', http');
		if($pos===12){
			$threadId = 1000;
			$callBE = false;
		}
		     
		//below line is used for conversion tracking purpose
		$trackingPageKeyId=isset($postQuestionData['tracking_keyid']) ? $postQuestionData['tracking_keyid']: (!empty($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):null);

  	    if(($userPointValue > 10) && ($userId > 0) && ($callBE)){
			if($categoryId == 0){
				$catRes = $ListingClientObj->getOptimumCategory($appId,$instituteId);
				$categoryId = is_numeric($catRes)?$catRes:150;
			}
			if($instituteId == 0 && $categoryId != 0 && $courseId == 'Others' && $locationId == 2)
			{
				$questionResult = $msgbrdClient->addTopic($appId,$userId,$questionText,$categoryId,$requestIp,'user',$instituteId,'catPageWidget',1,$displayname,$locationId);
			}
			else
			{
				$questionResult = $msgbrdClient->addTopic($appId,$userId,$questionText,$categoryId,$requestIp,'user',$instituteId,'institute',1,$displayname,$locationId,'',$courseId,$trackingPageKeyId);
			}
			$threadId = isset($questionResult['ThreadID'])?$questionResult['ThreadID']:0;
				
			if($threadId>0){
				// ankit bansal - Tagging Listing Questions start 
				$this->insertTagsForTopic($questionText,$threadId,'question','threadpost');
				// Tagging Listing Questions stop*/		
				

				$alertClient = new Alerts_client();
				$alertName = 'comment-'.$questionText;
				$alertResult = $alertClient->createWidgetAlert($appId,$userId,8,'byComment',$threadId,$alertName);
				$resultOfCreation['alertResult'] = $alertResult;

				//Ankur: After the Question for the Listing is added in the DB, then also add this in the Related questions table also
				$creationTime= date('Y-m-d H:i:s');
				if($courseId!='Others'){
					$this->load->builder('ListingBuilder','listing');
					$listingBuilder = new ListingBuilder();
					$courseRepository = $listingBuilder->getCourseRepository();
					$courseObj = $courseRepository->find($courseId);
					$isPaid=$courseObj->isPaid();
					$includeInSitemap  = 1;
					if($isPaid == 'true') {
						$campusRepData = array();
						$this->cadiscussionmodel = $this->load->model('CA/cadiscussionmodel');
						$campusRepData = $this->cadiscussionmodel->getCampusReps($courseId, $instituteId);
						if(empty($campusRepData['data'])) {
							$includeInSitemap = 0;
						}
					}
					if($questionResult['isDuplicate'] != 1)
						$this->QnAModel->makeResponseOfQuestionAsker($userId,$threadId,$creationTime,$courseId,$instituteId,$includeInSitemap);
				}
	
				//for UGC-2396, this if condition was modified
				//if(($courseId!='Others') && (($isPaid=='true') || (($isPaid!='true') && ($page_name = 'LISTING_DETAIL_PAGE_NEW_BOTTOM') && (!isset($_COOKIE['ci_mobile']))))) {
				if($courseId!='Others'){
					$addReqInfo['preferred_locality'] = ($this->input->post('pref_loc_id')) ? $this->input->post('pref_loc_id') : $postQuestionData['pref_loc_id'];
					$addReqInfo['preferred_city'] = ($this->input->post('pref_city_id')) ? $this->input->post('pref_city_id') : $postQuestionData['pref_city_id'];
					$addReqInfo['listing_type'] = 'course';
					$addReqInfo['listing_type_id'] = $courseId;
					$addReqInfo['displayName'] = $userDetails[0]['displayname'];
					$addReqInfo['contact_cell'] = $userDetails[0]['mobile'];
					$addReqInfo['userId'] = $userId;
					$addReqInfo['contact_email'] = $userDetails[0]['email'];
					$addReqInfo['tracking_page_key'] = $trackingPageKeyId;
					$page_name = $this->input->post('page_name')?$this->input->post('page_name'):'';
					if($page_name=='COMPARE_PAGE'){
						$addReqInfo['action'] = "COMPARE_AskQuestion";
				    }else if($page_name == 'CC_intermediate_page'){
					if(isset($_COOKIE['ci_mobile']) && $_COOKIE['ci_mobile'] =='mobile'){
						$addReqInfo['action'] = "Asked_Question_On_CCHome_MOB";
					}else{
						$addReqInfo['action'] = "Asked_Question_On_CCHome";
					}
				    }
				    else{
				    	
				    	if((isset($_COOKIE['mob_ask_cmpr']) && $_COOKIE['mob_ask_cmpr'] != '') || isset($_COOKIE['mobAskLgnCmpr']) && $_COOKIE['mobAskLgnCmpr'] != '')  {
							$addReqInfo['action'] = "Mobile5_Asked_Questions_On_Comparepage";

						}
						else if(isset($_COOKIE['ci_mobile']) && $_COOKIE['ci_mobile'] =='mobile'){
	
							$addReqInfo['action'] = "Asked_Question_On_Listing_MOB";
						}else if(isset($page_name) && $page_name == 'myShortlist_Ana'){
							$addReqInfo['action'] = "D_MS_Ask";
						}else{
							$addReqInfo['action'] = "Asked_Question_On_Listing";
						}
				    }
				  
					$addReqInfo['userInfo'] = json_encode($signedInUser);
					$addReqInfo['sendMail'] = true;
					if($isPaid=='true') {
						$addReqInfo['listing_subscription_type'] = 'paid';
					} else {
						$addReqInfo['listing_subscription_type'] = 'free';
					}
					$this->load->library('LmsLib');
					$LmsClientObj = new LmsLib();
					//for UGC-2396, this if condition was removed
					//if($page_name = 'LISTING_DETAIL_PAGE_NEW_BOTTOM' && (!isset($_COOKIE['ci_mobile']))) {
					if(true) {
						$addLeadStatus = $LmsClientObj->insertTempLead($appId,$addReqInfo);
						$addReqInfo['tempLmsRequest'] = $addLeadStatus['leadId'];
					}
				      //below line is used for response for after question post
				    $addReqInfo['trackingPageKeyId'] = $trackingPageKeyId;
				    $addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);
				}
				$ListingClientObj->updateListingQuestions($appId,$instituteId,$threadId,'One');
			}
        }
  	         //}
		 		
		$source = ($_COOKIE['ci_mobile'] !='') ? 'mobile' : 'desktop';
		if($threadId == $msgId || $threadId == $parentId || $mainAnswerId == $parentId){
		    $userId   = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		    $result = $this->QnAModel->insertAnAMobileTracking($userId,'Question',$source);

		}
		
		$ques_cat_data = array('msgId'=>$threadId, 'courseId'=>$courseId, 'userId'=>$userId);
		$this->insertQuestionCategoryFromCCIntermediatePage($ques_cat_data);
		 
		if($threadId>0)
	  	    $returnArray = array('userPointRes' => $userPointRes,'secCodeResult' => $secCodeResult,'questionResult' => $threadId ,'alreadyRegistered' => $alreadyRegistered);
		else
			$returnArray = array('error' => $questionResult);

  	    echo json_encode($returnArray);
  	}
	
	function insertQuestionCategoryFromCCIntermediatePage($ques_cat_data)
	{
		if(isset($_COOKIE['cc_question_category']) && $_COOKIE['cc_question_category']!='')
		{
			$this->load->model('QnAModel');
			$data = array();
			$data['msgId'] = $ques_cat_data['msgId'];
			$data['courseId'] = $ques_cat_data['courseId'];
			$data['userId'] = $ques_cat_data['userId'];
			$data['question_category'] = $_COOKIE['cc_question_category'];
			$data['status'] = 'live';
			$data['creationDate'] = date('Y-m-d H:i:s');
			$this->QnAModel->insertQuestionCategoryFromCCIntermediatePage($data);
			unset($_COOKIE['cc_question_category']);
			setcookie('cc_question_category', '', time() - 3600, '/');
		}
	}

	function vcardForm()
	{
		//Now, 301 redirect to Expert page from this page directly
		$url=SHIKSHA_ASK_HOME_URL."/messageBoard/MsgBoard/expertOnboard";
		header("Location: $url",TRUE,301);
		exit;

		$this->init(array('message_board_client','ajax'),array('url'));
		$appId = 1;
		$msgbrdClient = new Message_board_client();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$Result = $msgbrdClient->getUserVCardDetails($appId,$userId,'form');
		$displayData['followUser'] = $this->getFollowUser('',$userId);
		//$userLevel = $msgbrdClient->getUserLevel($appId,$userId,"AnA");
		if(is_array($Result)){
		    if(isset($Result[0]['firstname']))
		    {
		      if(isset($Result[0]['lastname']))
			  $Result[0]['userName'] = $Result[0]['firstname'].' '.$Result[0]['lastname'];
		    }
		}
		$displayData['Result'] = is_array($Result)?$Result:array();
		$displayData['vCardFields'] = is_array($Result)?$Result[0]['VCardDetails']:array();
		//$displayData['userLevel'] = is_array($userLevel)?$userLevel[0]['Level']:'Beginner';
		$Validate = $this->userStatus;
		$displayData['validateuser'] = $Validate;
		echo $this->load->view('messageBoard/vcardForm',$displayData);
	}

	function vcardInsert()
	{
	    $this->init(array('message_board_client'),'');
	    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	    if($userId>0){
	      $appId = 12;
	      if(isset($_POST['userNameHidden'])){
		$arrayURL = parse_url($this->input->post('imageURLInput'));
		$_POST['aboutMeInput'] = ($this->input->post('aboutMeInput')=='about Me')?'':$this->input->post('aboutMeInput');
		$_POST['blogURLInput'] = ($this->input->post('blogURLInput')=='Enter Your Blog URL')?'':$this->input->post('blogURLInput');
		$_POST['brijjURLInput'] = ($this->input->post('brijjURLInput')=='Enter Your Brijj Profile')?'':$this->input->post('brijjURLInput');
		$_POST['linkedInURLInput'] = ($this->input->post('linkedInURLInput')=='Enter Your Linked Profile')?'':$this->input->post('linkedInURLInput');
		$_POST['twitterURLInput'] = ($this->input->post('twitterURLInput')=='Enter Your Twitter Profile')?'':$this->input->post('twitterURLInput');
		$_POST['youtubeURLInput'] = ($this->input->post('youtubeURLInput')=='Enter Your Youtube Channel')?'':$this->input->post('youtubeURLInput');
		$msgbrdClient = new Message_board_client();
		$result = $msgbrdClient->setUserVCardDetails($appId,$userId,trim($this->input->post('userNameHidden')),trim($this->input->post('designationInput')),trim($this->input->post('instituteNameInput')),trim($this->input->post('highestQualificationHidden')),$this->input->post('imageURLInput'),$this->input->post('blogURLInput'),$this->input->post('brijjURLInput'),$this->input->post('linkedInURLInput'),$this->input->post('twitterURLInput'),$this->input->post('youtubeURLInput'),$this->input->post('aboutMeInput'));
	      }
	    }
	}

	function vcardInsertParam()
	{
	    $this->init(array('message_board_client'),'');
	    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	    if($userId>0){
		$appId = 12;
		$msgbrdClient = new Message_board_client();
		$result = $msgbrdClient->setUserVCardParam($appId,$userId,$this->input->post('type'),base64_decode($this->input->post('value')));
	      }
	}

	function showTopContributorList($default=1,$weekly=1,$start=0,$tc=1,$tp=1,$catId=1)
	{
		$this->benchmark->mark('code_start');
		error_log("==============startcont".memory_get_usage());
	    if(isset($_POST['tc'])){
			$tc = $this->input->post('tc');
			$tp = $this->input->post('tp');
			$tp = 0;
			$catId = $this->input->post('catgoryIdForTC'); // use for filter by category of TC;
	    }

	    $overviewFile = "ListingCache/topContributors".$start.$tc.$tp.$default.$catId.".html";
	    $makeDBCall = true;
	    if(file_exists($overviewFile)){
			$last_modified = filemtime($overviewFile);
			$nowTime = time();
			if(($nowTime - $last_modified) < 86400)
		  	$makeDBCall = false;
	    }
	    if(!$makeDBCall){
			echo file_get_contents($overviewFile);
	    }
	    else{
	    	$this->load->helper('image');
	    	$this->load->library('cacheLib');
	    	$cacheLib = new cacheLib;
	    	$key = "topContributorsList".$start.$weekly.$catId;
	    	if($cacheLib->get($key)=='ERROR_READING_CACHE'){
	    		error_log("===========from db cont");
		    	$this->load->model('QnAModel');
		    	$res[0]['mostcontributing'] = $this->QnAModel->getTopContributors(1,10,$weekly,$start,$tc,$tp,$catId);
		    	$cacheLib->store($key, $res[0]['mostcontributing'], 259200);
		    }
		    else{
		    	error_log("===========from cache cont");
		    	$res[0]['mostcontributing'] = $cacheLib->get($key);
		    }

			$displayData['topContributtingAndExpertPanel'] = $res;
			$displayData['weekly'] = $weekly;
			$displayData['default'] = $default;
			$displayData['tp'] = $tp;
			$displayData['tc'] = $tc;
			$displayData['catId'] = $catId;
			if($default==1){
			  $result =  $this->load->view('messageBoard/topContributors_quesDetail',$displayData,true);
			}
			else{
			  $displayData['showContributor'] = $tc;
			  $displayData['showParticipant'] = $tp;
			  $displayData['showSingleCategory'] = $catId;
			  $result = $this->load->view('messageBoard/topContributorsList',$displayData,true);
			}
			$fp=fopen($overviewFile,'w+');
			fputs($fp,$result);
			fclose($fp);
			echo $result;
		}
	    $this->benchmark->mark('code_end');
	    error_log("===========cont".$this->benchmark->elapsed_time('code_start', 'code_end'));
        error_log("==============endscont".memory_get_usage());
        error_log("==============endscontpeak".memory_get_peak_usage());
	}

	function sendMailOnComment()
	{
	    $this->init(array('message_board_client','Alerts_client','MailerClient'),'');
	    $msgbrdClient = new Message_board_client();
	    $displayName = isset($this->userStatus[0]['displayname'])?$this->userStatus[0]['displayname']:'';
	    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	    $answerId = $this->input->post('answerId');
	    $userIdList = array();
	    $mailData = $msgbrdClient->getMailDataOnCommentPosting(1,$answerId,$userId);
	    if(is_array($mailData))
	    {
	      for($i=0;$i<count($mailData);$i++)
	      {
		  if(!(in_array($mailData[$i]['userId'],$userIdList)))
		  {
		    array_push($userIdList, $mailData[$i]['userId']);
		    $fromAddress=SHIKSHA_ADMIN_MAIL;
		    $alertClient = new Alerts_client();
		    $fromMail = "noreply@shiksha.com";
		    $subject = "Your have received a new message.";
		    $contentArr = array();
		    $contentArr['type'] = 'commentMail';
		    $contentArr['entity'] = $mailData[$i]['type'];
		    $contentArr['name'] = $mailData[$i]['displayname'];
		    $MailerClient = new MailerClient();
		    $urlOfLandingPage = SHIKSHA_HOME."/getTopicDetail/".$mailData[$i]['threadId'];
		    $email = $mailData[$i]['email'];
		    $autoLoginUrl = $MailerClient->generateAutoLoginLink(1,$email,$urlOfLandingPage);
		    $contentArr['username'] = $displayName;
		    $contentArr['link'] = $autoLoginUrl;
		    $contentArr['msgTxt'] = $mailData[$i]['msgTxt'];
		    $content = $this->load->view("search/searchMail",$contentArr,true);
		    $response= $alertClient->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",'0000-00-00 00:00:00');
		  }
	      }
	    }
	}

	function showUpdateUserNameImage()
	{
	    $this->init(array('message_board_client'),'');
	    $displayName = isset($this->userStatus[0]['displayname'])?$this->userStatus[0]['displayname']:'';
	    $displayData['displayname'] = $displayName;
		$displayData['displayNameDiv'] = 1;
		$displayData['displayImageDiv'] = $this->input->post('displayImageDiv');
		$displayData['type'] = $this->input->post('type');
		$displayData['flag'] = $this->input->post('flag');
		$displayData['action'] = $this->input->post('action');
		$displayData['isrplyAnswerReg'] = $this->input->post('isrplyAnswerReg',true) == '1'? '1':'0';
		$displayData['onSubmittingImage'] = $this->input->post('onsubmittingImage',true);
	    echo $this->load->view('user/getUserNameImage',$displayData);
	}

	function getOlderWallData()
	{
		//This function is no longer in use and we will redirect any call to ASK Home
		$url = SHIKSHA_ASK_HOME;
                header("Location: $url",TRUE,301);
                exit;

		$this->init(array('message_board_client','alerts_client','ajax'));
	    $displayName = isset($this->userStatus[0]['displayname'])?$this->userStatus[0]['displayname']:'';
                $categoryId = isset($_POST['categoryId'])?$this->input->post('categoryId'):1;
                $countryId = isset($_POST['countryId'])?$this->input->post('countryId'):1;
                $threadIdCsv = isset($_POST['threadIdCsv'])?$this->input->post('threadIdCsv'):'0';
		$lastTimeStamp = $this->input->post('lastTimeStamp');
		$start = $this->input->post('start')+10;
		$rows = 10;
		$displayData['topicListings'] = $this->wallQuestionPage($categoryId,$countryId,$start,$rows,1,$threadIdCsv,$lastTimeStamp);
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$Validate = $this->userStatus;
		$displayData['pageKeySuffixForDetail'] = 'ASK_ASKHOMEPAGE_WALL_';
		$displayData['start'] = $start;
		$displayData['count'] = $rows;
		$displayData['countryId'] = $countryId;
		$displayData['categoryId'] = $categoryId;
		$displayData['userId'] = $userId;
		$displayData['userGroup'] = $userGroup;
		$displayData['validateuser'] = $Validate;
		$displayData['threadIdCsv'] = $threadIdCsv;
		
		//below code is used for conversion tracking purpose
		$tabselected=$this->input->post('tabselected');
		if(isset($tabselected))
		{
			$displayData['tabselected']=$tabselected;
			$this->tracking=$this->load->library('common/trackingpages');
			$this->tracking->gettingPageKey($tabselected,$displayData);
		}
		/*Code to create Hidden URL and Hidden Code by Pranjul Start*/
		/*$this->load->spamcontrol('spamcontrol/SpamControl');
		$huSC = new HiddenUrlGenerator();
		$displayData['hiddenURL'] = $huSC->createHiddenUrl('Url','hidden','URL','spamUrl','');
		*/
  	        /*Code to create Hidden URL and Hidden Code by Pranjul End*/
	    echo $this->load->view('messageBoard/questionHomePageWall',$displayData);
	}

        function getOlderWallDataForListings(){

                $this->init(array('message_board_client','alerts_client','ajax','listing_client'));
                $displayName = isset($this->userStatus[0]['displayname'])?$this->userStatus[0]['displayname']:'';
		$categoryId = $this->input->post('categoryId');
		$countryId = $this->input->post('countryId');
		$threadIdCsv = $this->input->post('threadIdCsv');
		$lastTimeStamp = $this->input->post('lastTimeStamp');
                $questionIds = array();
                $questionIds = $this->input->post('questionIds');
                $instituteId = $this->input->post('instituteId');
                $type = $this->input->post('type');
                $questionIds = explode(",",$questionIds);
                $categoryId = explode(",",$categoryId);
		$start = $this->input->post('start')+10;
		$rows = 10;
                $messageBoard = new Message_board_client();
		if($type=='RELATED' && empty($questionIds[0])){
			$wallData = $messageBoard->getWallDataForListings($appId=1,$userId=0,$start,$rows,$categoryId,$countryId=1,$threadIdCsv,$lastTimeStamp,$questionIds,$type,$instituteId);		    
		}
		else{
			$this->load->model('QnAModel');
			$wallData=$this->QnAModel->getWallDataForListingsByNewAlgorithm($appId=1,$userId=0,$start,$rows,$categoryId,$countryId=1,$threadIdCsv,$lastTimeStamp,$questionIds,$type,$instituteId);
		}
		$displayData['topicListings'] = $wallData;
		
	
		//$displayData['topicListings'] = $this->wallQuestionPage($categoryId,$countryId,$start,$rows,1,$threadIdCsv,$lastTimeStamp);
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$Validate = $this->userStatus;
	        $displayData['instituteId'] = $instituteId;
		$displayData['questionIds'] = $questionIds;
                $displayData['pageKeySuffixForDetail'] = 'ASK_ASKHOMEPAGE_WALL_';
		$displayData['start'] = $start;
		$displayData['count'] = $rows;
		$displayData['countryId'] = $countryId;
		$displayData['categoryId'] = $categoryId;
		$displayData['userId'] = $userId;
		$displayData['userGroup'] = $userGroup;
		$displayData['validateuser'] = $Validate;
		$displayData['threadIdCsv'] = $threadIdCsv;
		$displayData['relatedQuestions'] = false;
        $displayData['ajaxCall'] = true;
		if($type=='RELATED'){
			$displayData['relatedQuestions'] = true;
		}
	    echo $this->load->view('listing/widgets/ask&answerWall',$displayData);
        }

	function getCategoryCountryWidget()
	{
		$this->init(array('message_board_client','alerts_client','ajax'));
		$catCountURL = $this->input->post('catCountURL');
		$categoryId = $this->input->post('selectedCategory');
		$countryId = $this->input->post('selectedCountry');
		$CategoryArray = array('catCountURL'=>$catCountURL,'selectedCategory'=>$categoryId,'selectedCountry'=>$countryId);
	    echo $this->load->view('common/commonOverlay',$CategoryArray);
	}

	function getTopAnswersForWall()
	{
		$this->init(array('message_board_client','alerts_client','ajax'));
		$msgbrdClient = new Message_board_client();
		$questionId = $this->input->post('questionId');
		$displayAnswerId = $this->input->post('displayAnswerId');
		$start = $this->input->post('start');
		$count = $this->input->post('count');
		$catCountryLink = $this->input->post('catCountLink');
		$catCountryText = $this->input->post('catCountText');
		$showDate=$this->input->post('showDate');
		$appId = 1;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$ResultOfDetails = $msgbrdClient->getTopAnswersWall($appId,$questionId,$start,$count,$displayAnswerId,$userId,$userGroup);
		$displayData['ResultOfDetails'] = is_array($ResultOfDetails[0]['MsgTree'])?$ResultOfDetails[0]['MsgTree']:array();
		$displayData['levelVCard'] = is_array($ResultOfDetails[0]['levelVCard'])?$ResultOfDetails[0]['levelVCard']:array();
		$answerSuggestions = isset($ResultOfDetails[0]['answerSuggestions'])?$ResultOfDetails[0]['answerSuggestions']:array();
		$displayData['answerSuggestions'] = $this->convertSuggestionArray($answerSuggestions);		
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = $userId;
		$displayData['catCountryLink'] = $catCountryLink;
		$displayData['catCountryText'] = $catCountryText;
		$displayData['showDate'] = $showDate;
		
		//below lines is used for conversion tracking purpose
		$tabSelected=$this->input->post('tabSelected');
		if(isset($tabSelected))
		{
			$this->tracking=$this->load->library('common/trackingpages');
			$this->tracking->getUserDetailPageKeys($tabSelected,$displayData);
		}
	    echo $this->load->view('messageBoard/topAnswers_Wall',$displayData);
		//echo json_encode($ResultOfDetails);
	}

	function getCommentSection()
	{
		$this->init(array('message_board_client','alerts_client','ajax'));
		$msgbrdClient = new Message_board_client();
		$threadId = $this->input->post('threadId');
		$answerId = $this->input->post('answerId');
		$focusForm = $this->input->post('focusForm');
		$appId = 1;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$ResultOfDetails = $msgbrdClient->getCommentSection($appId,$answerId,$userId);
		$displayData['ResultOfDetails'] = is_array($ResultOfDetails[0]['commentTree'])?$ResultOfDetails[0]['commentTree']:array();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['focusForm'] = $focusForm;
		$displayData['answerId'] = $answerId;
		$displayData['threadId'] = $threadId;
		$res = json_decode($msgbrdClient->getUserReputationPoints($appId,$userId));
	        $displayData['reputationPoints'] = $res[0]->reputationPoints;

	    //below line is used for answer comment trackingPageKeyId
	        $trackingPageKeyId=$this->input->post('trackingPageKeyId');
	        if(isset($trackingPageKeyId))
	        {
	        	$displayData['ansctrackingPageKeyId']=$trackingPageKeyId;
	        }
	     	$raansctrackingPageKeyId=$this->input->post('raansctrackingPageKeyId');
	     	if(isset($raansctrackingPageKeyId))
	     	{
	     		$displayData['raansctrackingPageKeyId']=$raansctrackingPageKeyId;
	     	}
	        echo $this->load->view('messageBoard/comments_Wall',$displayData);
	}

	function getAnswerForm()
	{
		$this->init(array('message_board_client','alerts_client','ajax'));
		$threadId = $this->input->post('threadId');
		$questionId = $this->input->post('questionId');
		$isQuesDetailPage = $this->input->post('isQuesDetailPage');
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$userImageURLDisplay = isset($this->userStatus[0]['avtarurl'])?$this->userStatus[0]['avtarurl']:"/public/images/photoNotAvailable.gif";
		$questionUrl = SHIKSHA_ASK_HOME_URL."/getTopicDetail/".$threadId;
		if($isQuesDetailPage=='true'){
		  $dataArray = array('userGroup' =>$userGroup,'threadId' =>$threadId,'ansCount' => 2,'loggedUserImageURL' => $userImageURLDisplay,'userId' => $userId,'detailPageUrl' =>-1,'callBackFunction' => 'try{ addMainCommentForQues('.$threadId.',request.responseText,\'-1\',false,true,\'\',\'\',false,\''.$userImageURLDisplay.'\','.$questionId.',false,true); } catch (e) {}');
		  echo $this->load->view('messageBoard/InlineForm_Answer',$dataArray);
		}else{
		  $dataArray = array('showMention'=>true,'userId'=>$userId,'userImageURL'=>$userImageURLDisplay,'userGroup' =>$userGroup,'threadId' =>$threadId,'ansCount' => 2,'detailPageUrl' =>$questionUrl,'callBackFunction' => 'try{ addMainCommentForQues('.$threadId.',request.responseText,\'-1\',true,true,\'\',\'\',true,\''.$userImageURLDisplay.'\','.$questionId.'); } catch (e) {}');
		  echo $this->load->view('messageBoard/InlineForm_Homepage',$dataArray);
		}
	}

	function showMyArticleComments()
	{
		$this->init(array('message_board_client','alerts_client','ajax'));
		$msgbrdClient = new Message_board_client();
		$threadId = $this->input->post('threadId');
		$startFrom = $this->input->post('startFrom');
		$count = $this->input->post('count');
		$appId = 12;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$userImageURLDisplay = isset($this->userStatus[0]['avtarurl'])?$this->userStatus[0]['avtarurl']:"/public/images/photoNotAvailable.gif";
		if($threadId>0 && $threadId!='')
		$ResultOfDetails = $msgbrdClient->getEntityComments($appId,$threadId,$startFrom,$count,$userId);
		$topic_reply = isset($ResultOfDetails[0]['MsgTree'])?$ResultOfDetails[0]['MsgTree']:array();
		$answerReplies = isset($ResultOfDetails[0]['Replies'])?$ResultOfDetails[0]['Replies']:array();
		$displayData['topicId'] = $threadId;
		if(is_array($topic_reply) && count($topic_reply) > 0)
		{
			$topic_messages = array();
			$i = -1;
			foreach($topic_reply as $topicComment)
			{
				if($topicComment['parentId']!=0){
				  $found = 0;
				  if(substr_count($topicComment['path'],'.') == 1)
				  {
					  $i++;
					  $j = -1;
					  $topic_messages[$i] = array();
					  $topicComment['userStatus'] = getUserStatus($topicComment['lastlogintime']);
					  $topicComment['creationDate'] = makeRelativeTime($topicComment['creationDate']);
					  $answerId = $topicComment['msgId'];
					  $topic_replies[$answerId] = array();
					  array_push($topic_messages[$i],$topicComment);
					  $comparison_string = $topicComment['path'].'.';
					  $topic_replyInner = $answerReplies;
					  foreach($topic_replyInner as $keyInner => $tempInner){
						  if(strstr($tempInner['path'],$comparison_string)){
							  $j++;
							  $topic_replies[$answerId][$j] = array();
							  $tempInner['userStatus'] = getUserStatus($topic_reply[$i]['lastlogintime']);
							  $tempInner['creationDate'] = makeRelativeTime($tempInner['creationDate']);
							  array_push($topic_replies[$answerId][$j],$tempInner);
						  }
					  }
				  }

				}
			 }
		}
		$displayData['topic_messages'] = $topic_messages;
		$displayData['topic_replies'] = $topic_replies;
		$displayData['validateuser'] = $this->userStatus;
		$displayData['ajaxCall'] = true;
		$displayData['entityId'] = $this->input->post('entityId');
		$displayData['threadId'] = $threadId;
		$displayData['userId'] = $userId;
		$displayData['entityTypeShown'] = $this->input->post('entityTypeShown');
	    echo $this->load->view('messageBoard/showEntityComments',$displayData);

	}

	function showOtherRating()
	{
		$this->init(array('message_board_client','alerts_client','ajax'));
		$msgbrdClient = new Message_board_client();
		$answerId = $this->input->post('answerId');
		$shownUserId = $this->input->post('shownUserId');
		$appId = 1;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		if($answerId > 0 && $shownUserId > 0){
			$ResultOfDetails = $msgbrdClient->showOtherRating($appId,$answerId,$shownUserId,$userId);
		}
		$ResultOfDetails = is_array($ResultOfDetails)?$ResultOfDetails:array();
		echo json_encode($ResultOfDetails);
	}

	/*****
	Functions START to get the User Profile page
	******/
	function userProfile($displayname,$tabSelected = '',$product = 'user')
	{
                if($displayname=='' || ( $tabSelected!='' && $tabSelected!='Question' && $tabSelected!='Answer' && $tabSelected!='Comment' && $tabSelected!='Announcement' && $tabSelected!='Discussion' ) ){
			show_404();
		}

		//Also, check if the domain is ask.shiksha.com. If not, redirect the page to ask.shiksha.com
                if( strpos($_SERVER['SCRIPT_URI'],'ask.shiksha.com')===false && REDIRECT_URL=='live'){
                        if($tabSelected == ''){
                            $url = SHIKSHA_ASK_HOME.'/getUserProfile/'.$displayname;
                        }
                        else{
                            $url = SHIKSHA_ASK_HOME.'/getUserProfile/'.$displayname.'/'.$tabSelected;
                        }
                        header("Location: $url",TRUE,301);
                        exit;
                }

		$this->init(array('message_board_client','ajax','register_client'),array('url','shikshautility','image'));
		$appId = 12;$start = 0;$rows=10;
		$displayName = "'".$displayname."'";
		$netResult = array();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$registerClient = new Register_client();
		$userDetails = $registerClient->getDetailsforDisplayname($appId,$displayName);
		if(is_array($userDetails) && count($userDetails)>0)
		{
		  $viewedUserId = isset($userDetails[0]['userid'])?$userDetails[0]['userid']:0;
		  //Changed on 13th July 2016 by Ankur
		  //Redirecting to new User profile pages as this page is no longer required
		  if($viewedUserId > 0){
			$url = getSeoUrl($viewedUserId,'userprofile');
                  	header("Location: $url",TRUE,301);
	                exit;
		  }

		  $lastTimeStamp = date('Y-m-d H:i:s');
		  $data['topicListings'] = $this->getProfileData($appId,$userId,$start,$rows,'1',$lastTimeStamp,$tabSelected,$viewedUserId,$product);
		  $msgbrdClient = new Message_board_client();
		  $userInfoDetails = $msgbrdClient->getUserProfileDetails($appId,$viewedUserId);
		  if(is_array($userInfoDetails)){
			  if(isset($userInfoDetails[0]['otherUserDetails'][1]['weeklyPoints']))
				$userInfoDetails[0]['otherUserDetails'][0]['weeklyPoints'] = $userInfoDetails[0]['otherUserDetails'][1]['weeklyPoints'];
			  $data['otherUserDetails'] = $userInfoDetails[0]['otherUserDetails'];
			  if(isset($userInfoDetails[0]['participateUserDetails'][1]['weeklyParticipatePoints']))
				$userInfoDetails[0]['participateUserDetails'][0]['weeklyParticipatePoints'] = $userInfoDetails[0]['participateUserDetails'][1]['weeklyParticipatePoints'];
			  $data['participateUserDetails'] = $userInfoDetails[0]['participateUserDetails'];
			  $data['userExpertize'] = $userInfoDetails[0]['userExpertize'];
			  $vcardUserDetails = $userInfoDetails[0]['VCardDetails'];

			  $arrayOfUsers = array();
			  $userStatus = getUserStatus($vcardUserDetails[0]['lastlogintime']);
			  $userProfile = site_url('getUserProfile').'/'.$vcardUserDetails[0]['displayname'];
			  //if(in_array($userId,$userFriends)){
			  //	  $vcardUserDetails[0]['isFriend'] = 'true';
			  //}else{
				$vcardUserDetails[0]['isFriend'] = 'false';
			  //}
			  if(isset($vcardUserDetails[0]['firstname']))
			  {
				if(isset($vcardUserDetails[0]['lastname']))
				  $vcardUserDetails[0]['userName'] = $vcardUserDetails[0]['firstname'].' '.$vcardUserDetails[0]['lastname'];
			  }
			  $vcardUserDetails[0]['userStatus'] = $userStatus;
			  $vcardUserDetails[0]['userProfile'] = $userProfile;
			  $vcardUserDetails[0]['userOnlineStatus'] = getUserStatusToolTip($userStatus,$vcardUserDetails[0]['displayname'],$vcardUserDetails[0]['lastlogintime']);
			  $vcardUserDetails[0]['mailMsg'] = MAIL_TO_USER.$vcardUserDetails[0]['displayname'];
			  $vcardUserDetails[0]['addNetworkMsg'] = ADD_TO_NETWORK.$vcardUserDetails[0]['displayname'];
			  $vcardUserDetails[0]['alreadyAddedToNetworkMsg'] = $vcardUserDetails[0]['displayname'].' '.ALREADY_ADDED_TO_NETWORK;
		  }

		  $vcardArray = array();
		  array_push($vcardArray,array($vcardUserDetails,'struct'));
		  $data['userDetailsArray'] = $vcardArray;


		  $data['viewedDisplayName'] = $displayname;
		  $data['viewedUserName'] = $displayname;
		  if(isset($vcardArray) && is_array($vcardArray))
			if($vcardArray[0][0][0]['userName']!=' ')
			  $data['viewedUserName'] = $vcardArray[0][0][0]['userName'];
		  $data['netResult'] = $netResult;
		  $data['userGroup'] = $userGroup;
		  $data['appId'] = $appId;
		  $data['tabSelected'] = $tabSelected;
		  $data['pageUrl'] = base64_encode(site_url('messageBoard/MsgBoard/userProfile'));
		  $Validate = $this->userStatus;
		  $data['viewedUserId'] = $viewedUserId;
		  $data['pageKeySuffixForDetail'] = 'ANA_USER_PROFILE';
		  $data['followUser'] = $this->getFollowUser('',$viewedUserId,false);
		  $data['isValidUser'] = true;
		}
		else
		  $data['isValidUser'] = false;

		$data['validateuser'] = $Validate;
		$data['tabURL'] = site_url('getUserProfile')."/".$displayname;
		$data['start'] = $start;
		$data['rows'] = $rows;
		
		//Check of the user is a Campus ambassador. 
		//$this->load->model('QnAModel');
		//$data['isCampusAmbassador'] = $this->QnAModel->checkIfUserIsCampusAmbassador($appId,$viewedUserId);
		$this->load->model('CA/camodel');
		$this->camodel = new CAModel();
		$data['isCampusAmbassador'] = $this->camodel->checkIfUserIsCampusAmbassador($viewedUserId);
		
		$data['trackingpageIdentifier']='userDetailPage';
		//$data['trackingpageNo']=$displayName;
		$data['trackingviewedUserId']=$viewedUserId;
		$data['trackingkey']=$tabSelected;
		$data['trackingcountryId']=2;
		//below line is used to store the required infromation in beacon varaible for tracking purpose
                $this->tracking=$this->load->library('common/trackingpages');
                $this->tracking->_pagetracking($data);

        //below line is used for conversion tracking purpose
               $this->tracking->getUserDetailPageKeys($tabSelected,$data);

         $data['followTrackingPageKeyId'] = 535;
         $data['emailTrackingPageKeyId'] = 536;

		if($data['isCampusAmbassador']){
			$result = $this->camodel->getAllCADetails($viewedUserId,'live');
			$this->load->library('CAEnterprise/CAUtilityLib');
			$caUtilityLib =  new CAUtilityLib;
			$resultCA = $caUtilityLib->formatCAData($result);
			$data['campusambassador'] = $resultCA;
			$this->load->library('CA/CAHelper');
			$caHelper = new CAHelper();
			$rearrangeData = $caHelper->rearrangeBadgeInsAndEducation($resultCA);
			$data['campusambassador'] = $resultCA;
			$data['badgeInsCourseName'] = $rearrangeData;
			
			$this->load->view('CA/userProfilePage',$data);
		}else{
			$this->load->view('messageBoard/userProfilePage',$data);
		}
		if($data['isValidUser']){
			$canonicalurl = SHIKSHA_ASK_HOME.'/getUserProfile/'.$displayname;
			$data['canonicalURL'] = $canonicalurl;
		}
		
	}

	function getProfileData($appId,$userId,$start,$rows,$threadIdList,$lastTimeStamp,$tabSelected,$viewedUserId,$product)
	{
		$this->init(array('message_board_client','register_client','ajax'));
		$msgbrdClient = new Message_board_client();
		$Result = $msgbrdClient->getProfileData($appId,$userId,$start,$rows,$threadIdList,$lastTimeStamp,$tabSelected,$viewedUserId,$product);
		$count = is_array($Result[0])?$Result[0]['totalCount']:0;
		$countAnswered = isset($Result[0]['totalAnswered'])?$Result[0]['totalAnswered']:0;
		$arrayOfRes = is_array($Result[0])?$Result[0]['results']:array();
		$categoryCountry = is_array($Result[0])?$Result[0]['categoryCountry']:array();
		$levelVCard = isset($Result[0]['levelVCard'])?$Result[0]['levelVCard']:array();
		$levelAdvance = isset($Result[0]['levelAdvance'])?$Result[0]['levelAdvance']:array();
		$answerSuggestions = isset($Result[0]['answerSuggestions'])?$Result[0]['answerSuggestions']:array();
		$answerSuggestions = $this->convertSuggestionArray($answerSuggestions);
		$threadIdList = '';

		if(is_array($arrayOfRes))
		{
			for($i=0;$i<count($arrayOfRes);$i++)
			{
				$urlForTopic = site_url('messageBoard/MsgBoard/topicDetails').'/'.$arrayOfRes[$i][0]['msgId'];
				$arrayOfRes[$i][0]['creationDate'] = makeRelativeTime($arrayOfRes[$i][0]['creationDate']);
				$arrayOfRes[$i][0]['urlForTopic'] = $urlForTopic;
				if(is_array($arrayOfRes[$i][1])) $arrayOfRes[$i][1]['creationDate'] = makeRelativeTime($arrayOfRes[$i][1]['creationDate']);
			}

		}
		$topics = array('results' => $arrayOfRes,
				'arrayOfUsers' => $arrayOfUsers,
				'totalCount'=> $count,
				'totalAnswered'=>$countAnswered,
				'newRepliesCount' => $newRepliesCount,
				'levelAdvance' => $levelAdvance,
				'categoryCountry'=>$categoryCountry,
				'levelVCard'=>$levelVCard,
				'answerSuggestions'=>$answerSuggestions);
		return $topics;
	}

	function getOlderProfileData()
	{
		$this->init(array('message_board_client','alerts_client','ajax','register_client'));
	    $displayName = isset($this->userStatus[0]['displayname'])?$this->userStatus[0]['displayname']:'';
		$tabSelected = $this->input->post('tabSelected');
		$displayname = $this->input->post('displayname');
		$threadIdCsv = $this->input->post('threadIdCsv');
		if($threadIdCsv == '')
			$threadIdCsv = 0;
		$lastTimeStamp = $this->input->post('lastTimeStamp');
		$start = $this->input->post('start')+10;
		$rows = 10;
		$appId = 12;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$displayName = "'".$displayname."'";
		$registerClient = new Register_client();
		$userDetails = $registerClient->getDetailsforDisplayname($appId,$displayName);
		$viewedUserId = isset($userDetails[0]['userid'])?$userDetails[0]['userid']:0;
		$displayData['topicListings'] = $this->getProfileData($appId,$userId,$start,$rows,$threadIdCsv,$lastTimeStamp,$tabSelected,$viewedUserId,'user');
		$Validate = $this->userStatus;
		$displayData['pageKeySuffixForDetail'] = 'ANA_USER_PROFILE';
		$displayData['start'] = $start;
		$displayData['count'] = $rows;
		$displayData['countryId'] = $countryId;
		$displayData['categoryId'] = $categoryId;
		$displayData['userId'] = $userId;
		$displayData['userGroup'] = $userGroup;
		$displayData['validateuser'] = $Validate;
		$displayData['threadIdCsv'] = $threadIdCsv;
		$displayData['userGroup'] = $userGroup;
		$displayData['viewedDisplayName'] = $displayname;
		$displayData['tabSelected'] = $tabSelected;
		$displayData['viewedUserId'] = $viewedUserId;
		$displayData['pageUrl'] = base64_encode(site_url('messageBoard/MsgBoard/userProfile'));
		$displayData['tabURL'] = site_url('messageBoard/MsgBoard/userProfile')."/".$displayname;
		$msgbrdClient = new Message_board_client();
		$viewedUserLevel = $msgbrdClient->getUserLevel($appId,$viewedUserId,"AnA");
		$viewedUserLevelP = $msgbrdClient->getUserLevel($appId,$viewedUserId,"Participate");
		if(is_array($viewedUserLevel)) $viewedUserLevel = $viewedUserLevel[0]['Level'];
		$viewedUserImage = isset($userDetails[0]['avtarimageurl'])?$userDetails[0]['avtarimageurl']:"";
		$displayData['viewedOwnerDetails'] = array('viewedUserLevel'=>$viewedUserLevel,'viewedUserLevelP'=>$viewedUserLevelP,'viewedUserImage'=>$viewedUserImage);

		//below line is used for conversion tracking purpose for olde user profile data
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->getUserDetailPageKeys($tabSelected,$displayData);
		
		echo $this->load->view('messageBoard/questionHomePageWall',$displayData);
	}

	function getFollowUser($followingUserId,$followedUserId,$checkLogin="true")
	{
		$this->init(array('message_board_client','ajax'));
		$appId = 12;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		//if($checkLogin=="true" && $userId==0)
		//  return "0";
		$msgbrdClient = new Message_board_client();
		return $msgbrdClient->getFollowUser($appId,$followingUserId,$followedUserId);
	}

	function setFollowUser($followedUserId)
	{
		$this->init(array('message_board_client','ajax'));
		$appId = 12;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		if($userId > 0){
		  // $msgbrdClient = new Message_board_client();
		  // echo $msgbrdClient->setFollowUser($appId,$userId,$followedUserId);
			$this->load->model('common/UniversalModel');
            $result = $this->UniversalModel->followEntity($userId, $followedUserId, 'user', 'follow');
            if($result)
            	echo '1';
            else 
            	echo '0';
		}else
		  echo "0";
	}

	function showFollowingPersons()
	{
		$shownUserId = $this->input->post('shownUserId');
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$result = $this->getFollowUser('',$shownUserId);
		$result = is_array($result)?$result:array();
		echo json_encode($result);
	}

        function tenDaysInactivityMailer(){
		$this->validateCron();
                $appId = 1;
                $this->init(array('message_board_client','alerts_client'));
                $msgbrdClient = new Message_board_client();
                $UserIds = json_decode($msgbrdClient->tenDaysInactivityMailer($appId));
                //$UserIds = json_decode($msgbrdClient->getUserIdAboveAdvisor($appId));
                $fromAddress = 'info@Shiksha.com';
                $subject     = 'Your weekly Q&A activity update on Shiksha Caf&#233;';
                $contentArr = array();
                $contentArr['type']='InactiveUser';
                $contentArr['fromAddress']=$fromAddress;
                $contentArr['subject']=$subject;
                $hours = 10 * 24;
                $subtract = time()-($hours * 3600);
                $monthB = date("m", $subtract);
                $dayB = date("j", $subtract);
                $yearB = date("Y", $subtract);
                $beforedate = $dayB."-".$monthB."-".$yearB;

                $contentArr['fromdate']=$beforedate;

                $contentArr['todate']=date("j-m-Y");

                $AlertClientObj = new Alerts_client();


                if(is_array($UserIds[0]->tendayswithoutcheck))
                {
                    for($i=0;$i<count($UserIds[0]->tendayswithoutcheck);$i++)
                    {
						 $userId = $UserIds[0]->tendayswithoutcheck[$i];
                         if(in_array($userId,$UserIds[0]->tendayscheck)){
                             $tendayscheck= $userId;
                         }else{
                             $tendayscheck = 0;
                         }
						 $percentage = 0;
						 if(isset($UserIds[0]->RIDecay->$userId) && $UserIds[0]->RIDecay->$userId!=''){
							 $percentage = $UserIds[0]->RIDecay->$userId;
						 }
                         $contentArr['userInfo'] = json_decode($msgbrdClient->getDataUser(1,$UserIds[0]->tendayswithoutcheck[$i],$tendayscheck,$percentage));

						 //Mailers will be sent only to those user who are Experts in the System.
						 if(isset($UserIds[0]->isAnAExpert->$userId) && $UserIds[0]->isAnAExpert->$userId=='true'){
						   if($contentArr['userInfo'][0][0]->rnr->rank > 0){
								$contentArr['rank'] = $contentArr['userInfo'][0][0]->rnr->rank;
						   }else{
							   $contentArr['rank'] = 'N/A';
						   }

						   if($contentArr['userInfo'][0][0]->rnr->reputationPoints>0 && $contentArr['userInfo'][0][0]->rnr->reputationPoints!='9999999'){
								$contentArr['reputationPoints'] = round($contentArr['userInfo'][0][0]->rnr->reputationPoints);
								$contentArr['difference'] = round($contentArr['userInfo'][0][0]->rnr->difference);
							 }elseif($contentArr['userInfo'][0][0]->rnr->reputationPoints<=0 && $contentArr['userInfo'][0][0]->rnr->reputationPoints!='9999999'){

								$contentArr['reputationPoints'] = '0';
								$contentArr['difference'] = round($contentArr['userInfo'][0][0]->rnr->difference);
							 }elseif($contentArr['userInfo'][0][0]->rnr->reputationPoints=='9999999'){

								$contentArr['reputationPoints'] = '10';
								$contentArr['difference'] = round($contentArr['userInfo'][0][0]->rnr->difference);
							 }

							$content=$this->load->view("search/searchMail",$contentArr,true);//die;
							$userEmail = $contentArr['userInfo'][0][0]->email;
							$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$content,"html");
						 }
                    }
                }
              $msgbrdClient->tuserReputationPreviousPointEntry($appId);


        }

        function showRank()
	{
		$UserId = $this->input->post('UserId');
		$this->init(array('message_board_client'));
                $msgbrdClient = new Message_board_client();
                $result = $msgbrdClient->calculateRankByRepuationPoints($UserId);
		$result = is_array($result)?$result:array();
		echo json_encode($result);
	}



	/*****
	Functions END to get the Profile page
	******/

	function advisoryBoard($level='All', $start=0, $count=50){
		if(!(is_numeric($start) && is_numeric($count))){
		     show_404();
		}
		if($level != 'All')
		{
			$newLevel = explode('-community-members', $level);
			$level = $newLevel[0];
		}
		$level = explode(' ', $level);
		$level = $level[1];

		$URI_String  = '';
		if(!empty($level))
		{
			$URI_String  = '/Level-'.$level;
		}
		
		if(true)
        {
            redirect(SHIKSHA_ASK_HOME_URL.'/experts'.$URI_String, 'location', 301);
            exit;
        }

		$appId = 12;
		$dataArray = array();
		$this->init(array('message_board_client'));
		//$userId=base64_decode($this->input->post('userId'));
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

		$msgbrdClient = new Message_board_client();
		$allVcardUser = $msgbrdClient->advisoryBoard($appId, $level, $start, $count);
		if(empty($allVcardUser[0]['userDetails']))
		{
			redirect(SHIKSHA_HOME.'/messageBoard/MsgBoard/advisoryBoard/All/', 'location', 301);
			exit;
		}
		//Array for the count of each user level
		$dataArray['allVcardAllUser'] = is_array($allVcardUser)?$allVcardUser[0]['count']:array();
		//Array for the Level Id list
		$dataArray['allVcardFilterUser'] = is_array($allVcardUser)?$allVcardUser[0]['filterArray']:array();
		//Array for the VCard details like firstname, education, image
		$dataArray['VCardDetails'] = is_array($allVcardUser)?$allVcardUser[0]['userDetails'][0]['VCardDetails']:array();
		//Array for Likes, total points, weekly points of the user
		$dataArray['otherUserDetails'] = is_array($allVcardUser)?$allVcardUser[0]['userDetails'][0]['otherUserDetails']:array();
		//Array for the user expertize category
		$dataArray['userExpertize'] = is_array($allVcardUser)?$allVcardUser[0]['userDetails'][0]['userExpertize']:array();
		//Array for the Weekly points
		$dataArray['weeklyPoints'] = is_array($allVcardUser)?$allVcardUser[0]['userDetails'][0]['weeklyPoints']:array();

		$userIdList = '';
		$userArray = $dataArray['allVcardFilterUser'];
		foreach($userArray as $userArr){
		  $userIdList .= ($userIdList=='')?$userArr['userId']:",".$userArr['userId'];
		}
		//Get the user's who the logged in user is following
		$followUserArr = $msgbrdClient->getFollowUser($appId, $userId,$userIdList);
		$tempUserFollowArr = '';
		foreach($followUserArr as $followUser){
		  $followedUserId = $followUser['followedUserId'];
		  $tempUserFollowArr[$followedUserId] = true;
		}
		$dataArray['followUserArr'] = $tempUserFollowArr;

		//Get the Network list for the logged in user
		for($i=0;$i<count($dataArray['allVcardFilterUser']);$i++){
		  //if(in_array($dataArray['allVcardFilterUser'][$i]['userId'],$userFriends)){
		  //	  $dataArray['allVcardFilterUser'][$i]['isFriend'] = 'true';
		  //}else{
			$dataArray['allVcardFilterUser'][$i]['isFriend'] = 'false';
		  //}
		}

		$dataArray['logoMsg']="";
		$dataArray['start']=$start;
		$dataArray['rows']=$count;
		$dataArray['linkSel']=$level;
		$dataArray['validateuser'] = $this->userStatus;
		$dataArray['pageUrl'] = base64_encode(site_url('messageBoard/MsgBoard/advisoryBoard'));
		$dataArray['userGroup'] = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		
		/*Pranjul Code Start Canonical Url,Next,Previous metatags*/
		
		/*Calculate maximum count user of users at given level*/
		foreach($dataArray['allVcardAllUser'] as $key=>$value){
			if($value['Level']==$level){
				$maxCount = $value['count'];
				$newLevel = $value['Level'];
				break;
			}
			if($level=='All'){
				$maxCount = $maxCount + $value['count'];
				$newLevel = 'All';
			}
		}
		$dataArray['totalCount'] = $maxCount;
		
		$baseUrl = SHIKSHA_ASK_HOME_URL.'/messageBoard/MsgBoard/advisoryBoard';
		$enteredURL = $_SERVER['SCRIPT_URI'];
		
		if($start>0){
			/*if user is not on first page create url*/
			$url = $baseUrl.'/'.$level.'/'.$start.'/'.$count;
		}else{
			/*if user is on first page create url*/
			if($level=='All'){
				$url = $baseUrl;
				$urlOptional = $baseUrl.'/'.$level.'/';
			}else{
				$url = $baseUrl.'/'.$level.'/';
			}
			
		}
		/* Redirection Rule Start*/
		//Commenting since this is creating issues on Production
		if($url!='' && $url!=$enteredURL && REDIRECT_URL=='live'){
                        header("Location: $url",TRUE,301);
                        exit;
                }
		/* Redirection Rule End*/
		$this->load->helper('messageBoard/ana');
		$result = createSEOMetaTagsForAdvisoryBoard($baseUrl,$level,$start,$count,$maxCount);
		$dataArray['nextURL'] =  $result['nextUrl'];
		$dataArray['previousURL'] = $result['previousUrl'];
		$dataArray['canonicalURL'] = $result['canonicalUrl'];
		/*Pranjul Code End Canonical Url,Next,Previous metatags*/
		if($level == 'All'){
			$dataArray['seoTitle']       = 'Education and Career Community Members - Shiksha.com';
			$dataArray['seoDescription'] = 'Meet our education and career community members who are actively participating in community to help students with answers to their critical career related queries.';
		} else {
			$dataArray['seoTitle']       = "$level Education and Career Community Members - Shiksha.com";
			$dataArray['seoDescription'] = "A list of $level education and career community members to help the students on Shiksha.com";
		}
		
		$dataArray['followTrackingPageKeyId'] = 538;
		$dataArray['emailTrackingPageKeyId'] = 539;

		//below code used for beacon tracking
        $dataArray['trackingpageIdentifier'] = 'advisoryBoardPage';
        $dataArray['trackingcountryId']=2;


        //loading library to use store beacon traffic inforamtion
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($dataArray);
		$this->load->view('messageBoard/showAdvisoryBoard', $dataArray);
	}

	function mailAction($action='rating',$userId=0,$threadId=0,$msgId=0,$commentUserId=0){
	    $this->init(array('message_board_client'),'');
	    $appId = 12;
	    $msgbrdClient = new Message_board_client();
	    if($action=="rating"){ //In case of rating, update the dig value
	      $digValue = 1;
	      if($userId != 0)
	      {
		      $digResult = $msgbrdClient->updateDigVal($appId,$userId,$msgId,$digValue);
		      $resultOfDig = $digResult['Result'];
	      }
	      $result = array('result' =>$resultOfDig);
	    }
	 /*   else if($action=="bestAnswer"){
	      $doClose = 1;
	      $resultOfDig = 'error';
	      $doCloseRes = 'error';
	      if($userId != 0){
		      if(($threadId!='') && ($msgId != '') && ($commentUserId != '')){
			      $bestAnsResult = $msgbrdClient->setBestAnsForThread($appId,$userId,$threadId,$msgId,$commentUserId,$doClose);
			      $resultOfDig = $bestAnsResult['Result'];
			      $doCloseRes = $bestAnsResult['closeFlag'];
			      if($resultOfDig == 'success'){
				      $this->sendMailToUSerForBestAnswer($bestAnsResult['seoUrl'],$bestAnsResult['msgTxt'],$threadId,$msgId,$commentUserId);
			      }
		      }

	      }
	    }
	*/    //After completing the action, redirect to AnA Homepage
	    //Here, we need to check. If the Question is a Listing's question, we have to redirect to Question detail page. Else, we need to redirect to Cafe
	    $this->load->model('QnAModel');
	    $response = $this->QnAModel->checkQuestionSource($threadId);
	    if($response=='Cafe'){
		    //$url=SHIKSHA_ASK_HOME;
		    $url=SHIKSHA_ASK_HOME.'/-qna-'.$threadId;
	    }
	    else{
		    $url=SHIKSHA_ASK_HOME.'/-qna-'.$threadId;
	    }
	    header("Location:".$url);
	    exit;
	}

    function getCatCounOverlay(){
	$this->init(array('message_board_client','alerts_client','ajax','register_client'));
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$listingType = isset($_POST['listingTypeForAskQuestion'])?$this->input->post('listingTypeForAskQuestion'):'';
	$listingTypeId = isset($_POST['listingTypeIdForAskQuestion'])?$this->input->post('listingTypeIdForAskQuestion'):0;
	$entityType = $this->input->post('entityType');
	//below line is used for conversion tracking purpose
	$trackingPageKeyId=$this->input->post('trackingPageKeyId');
	if(isset($trackingPageKeyId))
	{
		$displayData['trackingPageKeyId']=$trackingPageKeyId;
	}
	if (($this->input->post('questionText')) && (!isset($_COOKIE['globalLandingPagePostAna']))) {
	    $questionText = $_REQUEST['questionText'];
	    setcookie('commentContent',$questionText,0,'/',COOKIEDOMAIN);
	}elseif(isset($_COOKIE['commentContent'])){
	    $questionText = $_COOKIE['commentContent'];
	}
	if($questionText == ""){
	    if($_REQUEST['questionText'] == ""){
		header('LOCATION:'.SHIKSHA_ASK_HOME);
		exit;
	    }else{
		$questionText = $_REQUEST['questionText'];
	    }
	}

	$this->load->library('Discussionhomesearchcontent');
        $discussionhomesearchcontent = new Discussionhomesearchcontent();
        $question_predicted_category = $discussionhomesearchcontent->getQuestionCategroy($questionText);
        $displayData['question_predicted_category'] = $question_predicted_category;

	$displayData['pageViewed'] = 'ask';
	//$displayData['infoWidgetData'] = $this->getDateForInfoWidget();
	$this->load->library('category_list_client');
	$categoryClient = new Category_list_client();
	$displayData['categoryList'] = $categoryClient->getCategoryTree($appId, 1);
	$displayData['questionText'] = $questionText;
	$displayData['listingType'] = $listingType;
	$displayData['listingTypeId'] = $listingTypeId;
	$displayData['validateuser'] = $this->userStatus;
	$displayData['entityType'] = $entityType;
	$displayData['countryList'] = $countryList = $this->getCountries();
	//$displayData['averageTimeToAnswer'] = $this->getAverageTimeForAswer();
	if($entityType=='review'){
	  $this->load->library('alumniSpeakClient');
	  $objAlumniSpeakClientObj = new AlumniSpeakClient();
	  $criterias = $objAlumniSpeakClientObj->getFeedBackCriterias($appId);
	  $displayData['criterias'] = $criterias;
	  $displayData['instituteName'] = "Amity";
	  echo $this->load->view('messageBoard/collegeReviewForm',$displayData);
	}
	else
	  echo $this->load->view('messageBoard/questionPostCategoryForm',$displayData);
    }

    function getCategoryList(){
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$categoryList = array();
		$categoryList[] = $categoryClient->getCategoryTree($appId, 1);
		$categoryList[] = $categoryClient->getCategoryTree($appId, 1, 'national');
		$categoryList[] = $categoryClient->getCategoryTree($appId, 1, 'studyabroad');
		echo json_encode($categoryList);
    }

    function getParentComments(){
	    $this->init(array('message_board_client','ajax'));
	    $appId = 12;
	    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	    $userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
	    $msgId = $this->input->post('msgId');
	    $msgbrdClient = new Message_board_client();
	    $result =  $msgbrdClient->getParentComments($appId,$msgId);
	    $result = is_array($result)?$result:array();
	    if(is_array($result) && (count($result)>0)){
		for($i=0;$i<count($result);$i++){
		  $result[$i]['msgTxt'] = formatQNAforQuestionDetailPage($result[$i]['msgTxt']);
		}
	    }
	    echo json_encode($result);
    }

    function showPostingWidget(){
	  $this->init(array('message_board_client','ajax'));
	  $entity = $this->input->post('entity');
	  if($entity=='question')
	  {
	  	$trackingPageKeyId=$this->input->post('qtrackingPageKeyId');
	  }
	  elseif($entity=='discussion')
	  {
	  	$trackingPageKeyId=$this->input->post('dtrackingPageKeyId');
	  }
	  elseif($entity=='announcement')
	  {
	  	$trackingPageKeyId=$this->input->post('atrackingPageKeyId');
	  }
	  //recieving trackingPageKeyId value for conversion tracking purpose
	  if(isset($trackingPageKeyId))
	  {
	  	$displayData['trackingPageKeyId']=$trackingPageKeyId;
	  }
	  	$displayData['qtrackingPageKeyId'] = $this->input->post('qtrackingPageKeyId');
	  	$displayData['dtrackingPageKeyId'] = $this->input->post('dtrackingPageKeyId');
	  	$displayData['atrackingPageKeyId'] = $this->input->post('atrackingPageKeyId');
	  $displayData['entity'] = $entity;
	  $displayData['displayHeading'] = "false";
	  $displayData['averageTimeToAnswer'] = $this->getAverageTimeForAswer();
	  echo $this->load->view('common/askCafeForm',$displayData);
	  //setcookie  ('posttitle','',time()-3600,'/',COOKIEDOMAIN);
	  //setcookie  ('postdescription','',time()-3600,'/',COOKIEDOMAIN);
	  //setcookie  ('entitytype','',time()-3600,'/',COOKIEDOMAIN);
    }

    function getHomepageData($entityType,$categoryId,$countryId,$start,$rows,$isAjax="0"){
	$this->init(array('message_board_client'));
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
	$appId = 12;
	$msgbrdClient = new Message_board_client();
	$returnData = $msgbrdClient->getHomepageData(1,$entityType,$categoryId,$countryId,$start,$rows,$userId);//print_r($returnData);
	if($isAjax=="0"){
	  return $returnData;
	}
	else{
	  $displayData['validateuser'] = $this->userStatus;
	  $displayData['topicListings'] = $returnData;
	  if($entityType=="discussion")
	    $displayData['tabselected'] = 6;
	  else if($entityType=="announcement")
	    $displayData['tabselected'] = 7;
	  $displayData['countryId'] = $countryId;
	  $displayData['isAjax'] = "1";
          if($userId>0){
           
	$this->load->library('acl_client');
        $aclClient =  new Acl_client();
        $displayData['ACLStatus'] = $aclClient->checkUserRight($userId,array('MakeStickyDiscussion','RemoveStickyDiscussion','MakeStickyAnnouncement','RemoveStickyAnnouncement'));
          }else{
            $displayData['ACLStatus'] = array('MakeStickyDiscussion'=>'False','RemoveStickyDiscussion'=>'False','MakeStickyAnnouncement'=>'False','RemoveStickyAnnouncement'=>'False');
          }
	  echo $this->load->view('messageBoard/commonHomepageView',$displayData);
	}
    }

    function showEditPostingWidget(){
	$this->init(array('message_board_client','alerts_client','ajax','register_client'));
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$entityType = $this->input->post('entityType');
	$topicId = $this->input->post('topicId');
	$displayData['pageViewed'] = 'ask';

	$msgbrdClient = new Message_board_client();
	if($topicId>0)
	$topicDetails = $msgbrdClient->getTopicDetailForEdit(1,$entityType,$topicId,$userId);
	if(is_array($topicDetails))
	    $displayData['topicDetail'] = $topicDetails;
	else
	    $displayData['topicDetail'] = array();

	$this->load->library('category_list_client');
	$categoryClient = new Category_list_client();
	$displayData['categoryList'] = $categoryClient->getCategoryTree($appId, 1);
	$displayData['validateuser'] = $this->userStatus;
	$displayData['entityType'] = $entityType;
	$displayData['countryList'] = $countryList = $this->getCountries();

	if($entityType=='review'){
	  $this->load->library('alumniSpeakClient');
	  $objAlumniSpeakClientObj = new AlumniSpeakClient();
	  $criterias = $objAlumniSpeakClientObj->getFeedBackCriterias($appId);
	  $displayData['criterias'] = $criterias;
	  $displayData['instituteName'] = "Amity";
	  echo $this->load->view('messageBoard/collegeReviewForm',$displayData);
	}
	else
	  echo $this->load->view('messageBoard/questionEditPostCategoryForm',$displayData);
    }

    //This function is called from the JS function to redirect the URL to facebook login page
    function shareOnFacebook()
    {
	$url = "http://www.facebook.com/login.php?api_key=4e5d4d4d4c56eae918b941e0ecbfbc75&v=1.0&next=https://www.shiksha.com/messageBoard/MsgBoard/loginSuccess\&&cancel_url=http://www.facebook.com/connect/login_failure.html&fbconnect=true&return_session=true&req_perms=read_stream,publish_stream,offline_access";
	header('location:'.$url);
    }

    //This function posts the entry on the FB wall. It is called by the FB on successful login and provides the Session key of the user
    function loginSuccess($session)
    {
	$this->load->library(array('facebook'));
	$userId = 0;
	//Get the logged in user information
        $cookie = isset($_COOKIE['user'])?$_COOKIE['user']:'';
	if(isset($cookie) && strlen($cookie) > 0){
		$this->load->library('login_client');
		$login_client = new login_client();
		$Validate = array();
		$Validate = $login_client->validateuser($cookie,'on');
		$userId = isset($Validate[0]['userid'])?$Validate[0]['userid']:0;
	}
	if(isset($_REQUEST['sessionKey']) && $_REQUEST['sessionKey']!=""){
	    $sessionKey = $_REQUEST['sessionKey'];
	}
	else{
	    $session = $_REQUEST['session'];
	    $arrayT = explode(',',$session);
	    $arrayTemp = explode(':',$arrayT[0]);
	    $sessionKey = substr($arrayTemp[1], 1, -1);
	}
	//After getting the session key, store the session key in the cookie so that if the user again makes a Facebook post, we will not ask for credentials
	setcookie('fbSessionKey',$sessionKey,time()+43200,'/',COOKIEDOMAIN);

	define('FB_APIKEY', '4e5d4d4d4c56eae918b941e0ecbfbc75');
	define('FB_SECRET', '4b7c641ae26fee3207ce3c4cecaa192e');
	define('FB_SESSION', $sessionKey);

	//After getting the session key, store the Key in the DB with the userId
	/*if($userId>0){
	      $this->load->library(array('message_board_client'));
	      $msgbrdClient = new Message_board_client();
	      $topicDetails = $msgbrdClient->setFBSessionKey(1,$userId,$sessionkey);
	}*/
	if(isset($_COOKIE['facebookData']) && $_COOKIE['facebookData']!='' && $userId>0){
	    $facebookData = $_COOKIE['facebookData'];
	    error_log(print_r($facebookData,true));
	    $fbDataArr = explode("##",$_COOKIE['facebookData']);
	    $this->load->library(array('message_board_client'));
	    $msgbrdClient = new Message_board_client();
	    if($fbDataArr[0]=="Post")
	      $fbData = $msgbrdClient->getDataForFacebook(1,$userId,$fbDataArr[0]);
	    else
	      $fbData = $msgbrdClient->getDataForFacebook(1,$userId,$fbDataArr[0],$fbDataArr[1],$fbDataArr[2]);
	    try {
		$facebook = new Facebook(FB_APIKEY, FB_SECRET);
		$facebook->api_client->session_key = FB_SESSION;
		$fetch = array('friends' =>  array('pattern' => '.*', 'query' => "select uid2 from friend where uid1={$user}"));
		$facebook->api_client->admin_setAppProperties(array('preload_fql' => json_encode($fetch)));
		$facebookLogData = '';
		if(is_array($fbData)){
		    $type = $fbData[0]['type'];
		    switch ($type){
		    case 'question':
			 $message = "asked a question at Shiksha Café on Shiksha.com – India’s leading education and career website.";
			 $feedStory = array("name" => "Q: ".$fbData[0]['msgTxt'],
					    "href" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['msgTxt']),
					    //"description" => "Education portal for India and Abroad",
					    "properties" => array("In" =>  array(
							      "text" => "Shiksha.com’s - ".$fbData[0]['name']." Channel",
							      "href" => "https://ask.shiksha.com/messageBoard/MsgBoard/discussionHome/".$fbData[0]['categoryId']
							      )
							  )
				      );
			  $actionLinks = json_encode(array( array("text" => "Answer It", "href" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['msgTxt'])  ) ));
			  $facebookLogData = $message."##"."Q: ".$fbData[0]['msgTxt'];
			  break;

		    case 'post':
			 $fromOthers = $fbData[0]['fromOthers'];
			 if($fromOthers == 'discussion')  $message = "started a discussion at Shiksha Café on Shiksha.com – India’s leading education and career website.";
			 else if($fromOthers == 'announcement')  $message = "posted an announcement at Shiksha Café on Shiksha.com – India’s leading education and career website.";

			 $feedStory = array("name" => $fbData[0]['msgTxt'],
					    "href" => getSeoUrl($fbData[0]['threadId'],$fromOthers,$fbData[0]['msgTxt']),
					    "description" => $fbData[0]['postDesc'],
					    "properties" => array("In" =>  array(
							      "text" => "Shiksha.com’s - ".$fbData[0]['name']." Channel",
							      "href" => "https://ask.shiksha.com/messageBoard/MsgBoard/discussionHome/".$fbData[0]['categoryId']
							      )
							  )
				      );
			 $actionLinks = json_encode(array( array("text" => "See More", "href" => SHIKSHA_ASK_HOME)));
			  $facebookLogData = $message."##".$fbData[0]['msgTxt'];
			 break;

		    case 'answer' :
			 $level = ($fbData[0]['level']=='')?'Beginner':$fbData[0]['level'];
			 $textVal = ($level=="Advisor")?"an":"a";
			 $message = $textVal." ".$level." at Shiksha Café on Shiksha.com – India’s leading education and career website, answered a student’s question.";
			 $feedStory = array("name" => "Q: ".$fbData[0]['questionText'],
					    "href" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['questionText']),
					    "description" => "A: ".$fbData[0]['msgTxt'],
					    "properties" => array("In" =>  array(
							      "text" => "Shiksha.com’s - ".$fbData[0]['name']." Channel",
							      "href" => "https://ask.shiksha.com/messageBoard/MsgBoard/discussionHome/".$fbData[0]['categoryId']
							      )
							  )
				      );
			 $actionLinks = json_encode(array( array("text" => "Help other students", "href" => SHIKSHA_ASK_HOME)));
			  $facebookLogData = $message."##"."Q: ".$fbData[0]['questionText']."##"."A: ".$fbData[0]['msgTxt'];
			 break;

		    case 'comment':
			 $message = "commented on a post at Shiksha Café on Shiksha.com – India’s leading education and career website";
			 $feedStory = array("name" => "Q: ".$fbData[0]['questionText'],
					    "href" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['questionText']),
					    "description" => $fbData[0]['msgTxt'],
					    "properties" => array("In" =>  array(
							      "text" => "Shiksha.com’s - ".$fbData[0]['name']." Channel",
							      "href" => "https://ask.shiksha.com/messageBoard/MsgBoard/discussionHome/".$fbData[0]['categoryId']
							      )
							  )
				      );
			 $actionLinks = json_encode(array( array("text" => "Join the discussion", "href" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['questionText']))));
			  $facebookLogData = $message."##"."Q: ".$fbData[0]['questionText']."##".$fbData[0]['msgTxt'];
			break;

		    case 'postcomment':
			 $fromOthers = $fbData[0]['fromOthers'];
			 if($fromOthers == 'discussion')  $message = "commented on a discussion post at Shiksha Café on Shiksha.com – India’s leading education and career website";
			 else if($fromOthers == 'announcement')  $message = "commented on a announcement at Shiksha Café on Shiksha.com – India’s leading education and career website";

			 $feedStory = array("name" => $fbData[0]['postText'],
					    "href" => getSeoUrl($fbData[0]['threadId'],$fromOthers,$fbData[0]['postText']),
					    "description" => $fbData[0]['msgTxt'],
					    "properties" => array("In" =>  array(
							      "text" => "Shiksha.com’s - ".$fbData[0]['name']." Channel",
							      "href" => "https://ask.shiksha.com/messageBoard/MsgBoard/discussionHome/".$fbData[0]['categoryId']
							      )
							  )
				      );
			 $actionLinks = json_encode(array( array("text" => "Join the discussion", "href" => getSeoUrl($fbData[0]['threadId'],$fromOthers,$fbData[0]['postText']) )));
			  $facebookLogData = $message."##".$fbData[0]['postText']."##".$fbData[0]['msgTxt'];
			break;
		    }

		    $feedStory['media'] = array(
				array(
				"type" => "image",
				"src" => "https://www.shiksha.com/public/images/90x90_3.jpg",
				"href" => "https://ask.shiksha.com"
				));
		    $feedStory = json_encode($feedStory);

		}
		if( $facebook->api_client->stream_publish($message,$feedStory,$actionLinks)){
		  echo "Successfully posted on your Facebook Wall!";
		  //After successfully posting on the Wall, make a log entry in the DB for the entry made ( IP Address, userId, content, sessionKey)
		  if($userId>0){
			$this->load->library(array('message_board_client'));
			$msgbrdClient = new Message_board_client();
			$topicDetails = $msgbrdClient->setFBWallLog(1,$userId,$sessionKey,$facebookLogData,S_REMOTE_ADDR);
		  }
		  //End code for Facebook Log entry
		}
	    }
	    catch(Exception $e) {
		error_log($e);
		echo "Could not be posted on your Facebook wall. Please try again later.<br />";
	    }
	}
    }

    function getRelatedInstitutes()
    {
	/*
	$this->init(array('message_board_client','listing_client','ajax'));
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$randomCategory = $_POST['randomCategory'];
	$ListingClientObj = new Listing_client();
	$relatedListings = $ListingClientObj->getInterestedInstitutes(12,$randomCategory,1,0,5);
	echo $this->load->view("listing/relatedInstitutes",array('resultArr' => $relatedListings,'showOther' => false));
	*/
	return false;
    }
      function logInShiksha(){
            $this->init(array('message_board_client','register_client','alerts_client','facebook_client'));            
            $appId =12;
            $email = $this->input->post('email');
            $displayname = $this->input->post('displayname');
            $firstname = $this->input->post('firstname');
	    $lastname = $this->input->post('lastname');
            $saveFBAccessTokenFlag = $this->input->post('saveFBAccessTokenFlag');
	    $accessToken = $this->input->post('accessToken');
	    $automaticFShare = $this->input->post('automaticFShare');
	    $cookieAuto = $this->input->post('cookieAuto');
	    $module = $this->input->post('module');
	    $addReqInfo = array();
            $register_client = new Register_client();

            $signedInUser = $register_client->getinfoifexists($appId,$email,'email');

            if(is_array($signedInUser)) {
                    $signedInUser = $register_client->getinfoifexists($appId,$email,'email');
                    $mdpassword = $signedInUser[0][mdpassword];
                    setcookie('userVal','Exist',time() + 2592000 ,'/',COOKIEDOMAIN);
            } else {
                    $signedInUser = $register_client->checkAvailability($appId,$displayname,'displayname');
                    setcookie('userVal','NotExist',time() + 2592000 ,'/',COOKIEDOMAIN);
                    while($signedInUser == 1){
                        $displayname = $firstname . rand(1,1000000);
                        $signedInUser = $register_client->checkAvailability($appId,$displayname,'displayname');
                    }
                    $password = 'shiksha@'. rand(1,1000000);
                    $mdpassword = sha256($password);
                    $userarray['appId'] = $appId;
                    $userarray['email'] = $email;
                    $userarray['password'] = $password;
                    $userarray['mdpassword'] = $mdpassword;
                    $userarray['displayname'] = $displayname;
                    $userarray['firstname'] = $firstname;
                    $userarray['lastname'] = $lastname;
                    $userarray['usergroup'] = 'veryshortregistration';
                    $userarray['quicksignupFlag'] = "requestinfouser";
                    $userarray['mobile'] = '';
		    $userarray['sourceurl'] = 'FacebookConnect#'.$module;	
                    $addResult = $register_client->adduser_new1($userarray);
                    $signedInUser = $register_client->getinfoifexists($appId,$email,'email');
                    setcookie('facebookData','registration',time() + 2592000 ,'/',COOKIEDOMAIN);
                    $this->sendWelcomeMailToNewUser($email, $password,$addReqInfo,$addResult,$actiontype,$signedInUser);

            }
            
	    $value = $email.'|'.$mdpassword. '|pendingverification';
	    $photo = "https://graph.facebook.com/me/picture?type=small&access_token=$accessToken";
	    $friends = "https://graph.facebook.com/me/friends?access_token=$accessToken";
	    //This function is no longer in use
            //setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
            setcookie('FBCookieCheckTimeStampForLogin',time(),time() + 2592000 ,'/',COOKIEDOMAIN);
            setcookie('FBCookieCheck','1',time() + 1296000 ,'/',COOKIEDOMAIN);                
	    setcookie('FBEmailCookieCheck',$email,time() + FB_USER_INFO_COOKIE,'/',COOKIEDOMAIN);
	    setcookie('FBDisplayNameCookieCheck',$displayname,time() + FB_USER_INFO_COOKIE,'/',COOKIEDOMAIN);
        setcookie('FBLastNameCookieCheck',$lastname,time() + FB_USER_INFO_COOKIE,'/',COOKIEDOMAIN);
	    setcookie('FBFirstNameCookieCheck',$firstname,time() + FB_USER_INFO_COOKIE,'/',COOKIEDOMAIN);
	    setcookie('FBPhotoCookieCheck',$photo,time() + FB_USER_INFO_COOKIE,'/',COOKIEDOMAIN);
	    setcookie('FBFriendsCookieCheck',$friends,time() + FB_USER_INFO_COOKIE,'/',COOKIEDOMAIN);
	    setcookie('FBAccessToken',md5($accessToken),time() + FB_USER_INFO_COOKIE,'/',COOKIEDOMAIN);	error_log("confusion===".$signedInUser[0]['userid']);error_log("confusion>>====".$saveFBAccessTokenFlag);
		
		/**
		 * Track user login time
		 */
		$signedInUserId = $signedInUser['0']['userid'];
		if(is_numeric($signedInUserId) && $signedInUserId>0){
			$this->load->model('user/usermodel');
			$this->usermodel->trackUserLogin($signedInUserId);
		}
		
		//After user has loggedin,update userId for made response
		if(is_numeric($signedInUser[0]['userid']) && $signedInUser[0]['userid']>0)
		{       
			$this->load->model('compareInstitute/compare_model');
			$this->compare_model->updateUserIdForMadeResponse($signedInUser[0]['userid']);	
		}
		
	    if(is_numeric($signedInUser[0]['userid']) && $signedInUser[0]['userid']>0 && $saveFBAccessTokenFlag == 1){
	    $userId = $signedInUser['0']['userid'];
	    
	    if($module=='AnA'){
		$fbClient = new facebook_client();
		$val = $fbClient->saveAccessToken_AnA($userId ,$accessToken,$email,$automaticFShare,$cookieAuto);
		echo $val[result];
	    }else{
                $userId = $signedInUser['0']['userid'];
		$fbClient = new facebook_client();
		$fbClient->saveAccessToken($userId,$accessToken);
		echo $signedInUser[0]['userid'];
		}

            }else{
                if(is_numeric($signedInUser[0]['userid']) && $signedInUser[0]['userid']>0){
                        echo $signedInUser[0]['userid'];
             }else{
                        return false;
             }
            }
            


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

    /**************************
    Name: updateQnAMasterListTable
    Purpose: To update the Master list in the DB and to create the Sitemap for the Masterlist and also send mailers to Product for uploading this Sitemap
    Parameters: None
    Return: None
    **************************/
    function updateQnAMasterListTable(){
	    $this->validateCron();
	    error_log("Cron to update question masterlist starts at :".date("Y-m-d H:i:s")."\n",3,"/tmp/updateMasterlist.log");
	    $this->init(array('message_board_client'));
            $appId =12;
	    //Update the master list in the Db table
	    $msgbrdClient = new Message_board_client();
	    $quesDataArr = $msgbrdClient->updateQnAMasterListTable($appId);
	    error_log("The Question master list has been updated\n",3,"/tmp/updateMasterlist.log");

	    //After updating the MasterList table, create the Sitemap for the Updated list
	    $this->createMasterListSitemap();
	    error_log("The Sitemap for the updated question master list has been created. Cron Ends: ".date("Y-m-d H:i:s")."\n\n",3,"/tmp/updateMasterlist.log");
    }

    /**************************
    Name: createMasterListSitemap
    Purpose: To create the Sitemap for the Masterlist and also send mailers to Product for uploading this Sitemap
    Parameters: None
    Return: None
    **************************/
    function createMasterListSitemap(){
	    $this->init(array('message_board_client','alerts_client'),'');
            $appId =12;
	    $sitemapOk = false;
	    //Get the list of 10000 questions with their modified time, priority and URL
	    $msgbrdClient = new Message_board_client();
	    $quesDataArr = $msgbrdClient->getMasterListSitemap($appId,10000);

	    //In case of a Valid array, start creating the Sitemap
	    if(is_array($quesDataArr) && count($quesDataArr)>0 && isset($quesDataArr[0]['results'])){
		$sitemapFile = 'MasterSitemap.xml';
		$fp=fopen($sitemapFile,'w+');
		fputs($fp,'<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
		$resultArr = $quesDataArr[0]['results'];
		//Now for each question data returned from Backend, create an entry in the XML
		foreach ($resultArr as $quesData){
		    $data = '<url><loc>'.htmlspecialchars($quesData['url'],ENT_QUOTES).'</loc><lastmod>'.date('Y-m-d',strtotime($quesData['modifiedDate'])).'</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
		    fputs($fp,$data);
  		}
		fputs($fp,'</urlset>');
		fclose($fp);
		$sitemapOk = true;
		error_log("Sitemap is generated\n",3,"/tmp/updateMasterlist.log");
	    }
	    //After creating the sitemap, send a mailer to product to upload it on Google
	    if($sitemapOk){
		$fromAddress="noreply@shiksha.com";
		$subject = 'New Master sitemap generated on Shiksha';
		$userEmail = 'nasr.khan@shiksha.com';
		$content = 'Hi Nasr,<br/><br/>A new sitemap of the Question Masterlist has been generated on Shiksha at the following URL:<br/>'.SHIKSHA_ASK_HOME.'/MasterSitemap.xml<br/><br/>Please upload it on Google.<br/><br/>-Shiksha Tech';
		$AlertClientObj = new Alerts_client();
		$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$content,"html");
		$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,'ankur.gupta@shiksha.com',$subject,$content,"html");
		error_log("Mailers are sent\n",3,"/tmp/updateMasterlist.log");
	    }
    }
	
	function showExpertTopContributorList($default=1,$weekly=1,$start=0,$tc=1,$tp=1,$catId=1)
	{		
	    $this->init(array('message_board_client'));
	    $msgbrdClient = new Message_board_client();
	    if(isset($_POST['tc'])){
			$tc = $this->input->post('tc');
			$tp = $this->input->post('tp');
			$catId = $this->input->post('catgoryIdForTC'); // use for filter by category of TC;
	    }
		$displayData['topContributtingAndExpertPanel'] = $msgbrdClient->getExpertsTopContributors(1,3,$weekly,$start,$tc,$tp,$catId);
		/* Here Array Rotation Code For $displayData['topContributtingAndExpertPanel'] */

		
		$displayData['weekly'] = $weekly;
		$displayData['default'] = $default;
		$displayData['tp'] = $tp;
		$displayData['tc'] = $tc;
		$displayData['catId'] = $catId;		
		if($default==1){
			echo $this->load->view('messageBoard/topExpertContributors_topicDetail',$displayData,true);
		} else {
			$displayData['showContributor'] = 1;
			$displayData['showParticipant'] = 0;		
			echo $this->load->view('messageBoard/topExpertContributorsList',$displayData,true);
		}
	}

    function getUserInNetwork(){
	    $this->init(array('message_board_client'));
	    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	    $appId = 12;
	    $msgbrdClient = new Message_board_client();
	    $returnData = $msgbrdClient->getUserInNetwork($appId,$userId);
		//error_log(json_encode($returnData));
	    if(is_array($returnData) && isset($returnData[0]))
		echo json_encode($returnData);
    }

    //Added by Ankur on 6 July for @Mention task
    function sendAtMentionMailers($mentionedNames,$msgId,$userId,$msgTxt,$type,$checkForExistence=''){
	    $this->init();
	    $appId = 12;

	    if($checkForExistence!='reply'){	//Check if this is a reply. In this cae, do not check for existence in Msg text
		//Check if the names are included in the text and not deleted
		$nameArr = explode(",",$mentionedNames);
		$newNameArr = '';
		for($i=0;$i<count($nameArr);$i++){
		    if($nameArr[$i]=='' || strpos($msgTxt,('@||'.$nameArr[$i])) !== false){
			$newNameArr .= ($newNameArr=='')? "'".$nameArr[$i]."'":",'".$nameArr[$i]."'";
		    }
		}
		if($newNameArr == '')
		    return false;
	    }
	    else
		$newNameArr = $mentionedNames;

	    //Now the mailers needs to be sent to the new names
	    //First, we need to get the data for mailers like Display name of logged in user, emails of mentioned users, URL of the thread
	    $msgbrdClient = new Message_board_client();
	    $ResultOfDetails = $msgbrdClient->getMentionMailersData($appId,$newNameArr,$msgId,$userId,$type);
	    $msgTxt = $this->removeTextForAtMention($msgTxt);
	    if(is_array($ResultOfDetails) && isset($ResultOfDetails[0])){
 		  $uniqueEmailArray = array();
                  $fromMail = "info@shiksha.com";
                  $ccmail = "";
                  $subject = "Someone has mentioned you on Shiksha Ask & Answer";
                  $this->load->library('mailerClient');
                  $MailerClient = new MailerClient();
                  $mail_client = new Alerts_client();

		  for($i=0; $i<count($ResultOfDetails[0]['emails']); $i++){
		      $contentArr = array();
		      $email = $ResultOfDetails[0]['emails'][$i][0]['email'];		
		      $urlOfLandingPage = $ResultOfDetails[0]['URL'];
		      $contentArr['userName'] = $ResultOfDetails[0]['displayname'];
		      $contentArr['mentioningUserName'] = $ResultOfDetails[0]['emails'][$i][0]['displayname'];
		      if($email!='' && $contentArr['mentioningUserName']!='' && !in_array($email,$uniqueEmailArray)){
		      array_push($uniqueEmailArray,$email);
		      $contentArr['typeOfEntity'] = $type;
		      $contentArr['link'] = $urlOfLandingPage;
		      $contentArr['text'] = strlen($msgTxt)>50?substr($msgTxt,0,50).'...':$msgTxt;
		      $contentArr['type'] = 'mentionMail';
		      $contentArr['receiverId'] = $ResultOfDetails[0]['emails'][$i][0]['userid'];
		      $contentArr['mail_subject'] = $subject;
		      
		      Modules::run('systemMailer/SystemMailer/mentionUserOnShiksha', $email, $contentArr);
		      }
		  }
	    }
    }

    /***********************
    Function: changeTextForAtMention
    Purpose: Add special characters at the places where @Mention is used so that the usernames can be identified
    Input: Text of the answer/comment and the list of @Mention usernames
    Output: Text for answer/comment with special characters added
    ************************/
    function changeTextForAtMention($msgTxt, $mentionedNames){
	//Check if the names are included in the text and not deleted
	$nameArr = explode(",",$mentionedNames);
	$newNameArr = '';
	$newMsgTxt = '';
	for($i=0;$i<count($nameArr);$i++){
	    if($nameArr[$i]!='' && (strpos($msgTxt,('@'.$nameArr[$i])) !== false || strpos($msgTxt,('@'.$nameArr[$i])) === 0)){
		$newMsgTxt .= substr($msgTxt,0,strpos($msgTxt,('@'.$nameArr[$i])));
		$newMsgTxt .= '@||'.$nameArr[$i].'||';
		$msgTxt = substr($msgTxt,strpos($msgTxt,('@'.$nameArr[$i])) + strlen('@'.$nameArr[$i]) );
	    }
	}
	$newMsgTxt .= $msgTxt;
	return $newMsgTxt;
    }

    function removeTextForAtMention($msgTxt){
	$newMsgTxt = '';
	do{
	if(strpos($msgTxt,('@||')) !== false || strpos($msgTxt,('@||')) === 0 ){
	    $newMsgTxt .= substr($msgTxt , 0 ,strpos($msgTxt,('@||'))) . '@';
	    $msgTxt = substr($msgTxt, strpos($msgTxt,('@||'))+3);
	    $newMsgTxt .= substr( $msgTxt, 0, strpos($msgTxt,('||') ));
	    $msgTxt = substr($msgTxt, strpos($msgTxt,('||'))+2);
	}
	}while(strpos($msgTxt,('@||')) !== false);
	$newMsgTxt .= $msgTxt;
	return $newMsgTxt;
    }

    function getDiscussionDataPart(){
            $this->init(array('message_board_client'));
	    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if(isset($_POST['mainDiscussionId'])){
                 $topicId= $this->input->post('mainDiscussionId');
            }else{
                 $topicId= '';
            }
	    $appId = 12;
            $m=0;
	    $msgbrdClient = new Message_board_client();
            $discussionText = $tc = $this->input->post('title');
            $searchType = $this->input->post('type');
            $discussionArr = $msgbrdClient->getRelatedSearchDiscussion($topicId,$discussionText,$searchType);//print_r($discussionArr);
            //$linkQuestionResult = $msgbrdClient->linkQuestionResult($topicId);
            $linkDiscussionViewCount = json_decode($msgbrdClient->calViewAnswerComment($discussionArr,$topicId,'false',1,'discussion'));
            //$displayData['discussionSearch'] = $discussionArr;
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
              echo $this->load->view('/common/relatedSearchDiscussionPart',$displayData);
    }

    /**
	 * function sendMailerToLegacyUserForDisplayNameChange(){
    	// this code was one time script. Not needed now.
		return;
                $this->init(array('mailerClient','alerts_client'));
                $conn = mysql_connect("localhost", "shiksha", "shiKm7Iv80l");
                if (!$conn) {

                    echo "1";
                }
                if (!mysql_select_db("data")) {

                    echo "2";
                }
                $MailerClient = new MailerClient();
                $AlertClientObj = new Alerts_client();
                $appId = 1;
                $shiksha_home_url = 'https://www.shiksha.com';
                if(defined('SHIKSHA_HOME_URL')){
                        $shiksha_home_url = SHIKSHA_HOME_URL;
                }
                $fromAddress="noreply@shiksha.com";
                $queryCmd = "select distinct email,userid,displayname from tuser where displayname like '%#%' or displayname like '%/%' or displayname like '%+%' or  displayname like '%?%' or displayname like '%\%%'";
                $result = mysql_query($queryCmd);
                while ($row = mysql_fetch_array($result)){
                    $newDisplayName = trim(preg_replace('/[#%+\/?]/',' ',$row['displayname']));
                    $subject = "names have been modified due to technical reasons";
                    $queryCmd = "update tuser set displayname = '".$newDisplayName."' where userid='".$row['userid']."'";
                    mysql_query($queryCmd);
                    $content['oldDisplayName'] = $row['displayname'];
                    $content['newDisplayName'] = $newDisplayName;
                    $urlOfLandingPage = site_url('getUserProfile').'/'.$newDisplayName;
                    $url = $MailerClient->generateAutoLoginLink(1,$row['email'],$urlOfLandingPage);
                   
                    $content = "<table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"border: 8px solid rgb(73, 107, 151);\">
  <tr>
    <td style=\"padding: 15px 0px 0px 15px;\" valign=\"top\" height=\"75\" width=\"300\"><a href=\"$shiksha_home_url/\" target=\"_blank\"><img src=\"".IEPLADS_DOMAIN."/mailers/shiksha/Ans_received_on_Ans_jan10/gifs/logo.gif\" width=\"233\" height=\"41\" alt=\"Shiksha.com\" border=\"0\"/></a></td>
    <td align=\"right\" width=\"285\" valign=\"top\" style=\"padding: 15px 15px 0px 0px; font-family: Arial; font-size: 12px;\">a naukri.com venture</td>
  </tr>
  <tr>
    <td bgcolor=\"#ededed\" height=\"1\" colspan=\"2\"></td>
  </tr>
  <tr>
    <td height=\"25\" colspan=\"2\"></td>
  </tr>
  <tr>
    <td align=\"center\" colspan=\"2\">
	<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"556\" style=\"font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(26, 26, 26); line-height: 18px; text-align: left;\">
	<tbody><tr><td>
        Dear ".$row['displayname']." ,
        <p>We have noticed that while creating your username on Shiksha Caf&#233;, you have used some special characters in the display name. We are in the process of making Shiksha a more friendly and secure environment for you. Therefore, with the recent upgrade, we have stopped the support of the following special characters in display names:</p>
		<p>Unsupported Characters: # % + / ?</p>
		<p>Our system has indicated that your display name consists of one or more of these characters, which has been automatically removed from your display name. Following is the link to your profile, with the edited display name:</p>
		<p><a href=".$url.">https://ask.shiksha.com/getUserProfile/".$newDisplayName."</a></p>
		<p>However, you can still change your display name to your liking, but without the above mentioned characters.  To do so, please log into Shiksha Caf&#233;  and change the display name from your V-Card displayed in the right-hand pane. In case you encounter any difficulties or have any queries regarding this change, please contact us by writing a mail to support@shiksha.com. We are happy to help you!</p>
        <p>Thanks,<br />Shiksha Team</p>
        </td></tr></tbody></table>
</td>
</tr>
<tr>
  <td valign=\"top\" colspan=\"2\" align=\"center\" style=\"border-top: 1px solid rgb(217, 217, 217); font-family: Arial,Helvetica,sans-serif; font-size: 10px; padding: 10px; color: rgb(102, 102, 102); line-height: 14px;\">
                For questions, advertising information, or to provide feedback, email: <a href=\"mailto:support@shiksha.com\" style=\"color:#231E19\">support@shiksha.com</a><br/>
		<br/> Copyright &copy; <?php echo date('Y')?> Shiksha.com. All rights reserved.<br/><a href=\"$shiksha_home_url/\" target=\"_blank\" style=\"color:#666666\">Shiksha.com</a> is located at A-88, Sector-2, NOIDA, UP 201301, INDIA
  </td>
</tr>
</table>";
            $alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$row['email'],$subject,$content,"html");
                }
     }*/

    function getRelatedDiscussions()
     { 
	$this->init(array('message_board_client','ajax'));
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$categoryId = $this->input->post('categoryId');
	$subCategoryId = $this->input->post('subCategoryId');
	$countryId = $this->input->post('countryId');
	$msgbrdClient = new Message_board_client();
	$relatedDiscussions = $msgbrdClient->getRelatedDiscussions($categoryId,$subCategoryId,$countryId);
	echo $this->load->view("messageBoard/relatedDiscussions",array('resultArr' => $relatedDiscussions));
     }

	 function expertOnboard(){
			$this->init(array('message_board_client','alerts_client','ajax'));
			$appId = 12;
			$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';

			//The following cases will be there:
			// 1. The user is not logged In: In this case, we simply have to show the Blank form
			// 2. The user is logged In: In this case, make a DB calls and check if user is an expert. If he is fill up his details in form. Else, simply hide some fields like Email, password, confirm password fields and show him the Blank form
			$data['userId'] = $userId;
			if($userId>0){
					//Also, we need to display the VCard of the user. So fetch the details of the same from DB.
					$msgbrdClient = new Message_board_client();
					$result = $msgbrdClient->getUserVCardDetails($appId,$userId,'','Draft');
					if(is_array($result) && isset($result[0]['VCardDetails'][0]['isAnAExpert'])){
						if($result[0]['VCardDetails'][0]['isAnAExpert']=='true' || $result[0]['VCardDetails'][0]['isAnAExpert']=='1')
							$data['edit'] = 'true';

						$res = json_decode($msgbrdClient->getUserReputationPoints($appId,$userId));
						if($res[0]->reputationPoints>0 && $res[0]->reputationPoints!='9999999'){
							$result[0]['VCardDetails'][0]['reputationPoints'] = round($res[0]->reputationPoints);
						}elseif($res[0]->reputationPoints<=0 && $res[0]->reputationPoints!='9999999'){
							$result[0]['VCardDetails'][0]['reputationPoints'] = 0;
						}elseif($res[0]->reputationPoints=='9999999'){
							$result[0]['VCardDetails'][0]['reputationPoints'] = 10;
						}

						$followUser = $this->getFollowUser('',$userId,"false");
						array_push($result[0]['VCardDetails'],array($followUser,'struct'));
					}
					$data['vcardDetails'] = $result;
			}
			else{
					//Assign default values for the VCard
					$result = array();
					$result[0]['VCardDetails'][0]['isAnAExpert'] = 0;
					$result[0]['VCardDetails'][0]['displayname'] = '';
					$result[0]['VCardDetails'][0]['totalAnswers'] = 0;
					$result[0]['VCardDetails'][0]['bestAnswers'] = 0;
					$result[0]["otherUserDetails"][0]["likes"] = 0;
					$result[0]["otherUserDetails"][0]["totalPoints"] = 0;
					$result[0]['VCardDetails'][0]['userName'] = '';
					$result[0]['VCardDetails'][0]['ownerLevel'] = 'Beginner';
					$result[0]["participateUserDetails"][0]["discussionPosts"] = 0;
					$result[0]["participateUserDetails"][0]["announcementPosts"] = 0;
					$result[0]["participateUserDetails"][0]["totalParticipatePoints"] = 0;
					$result[0]['VCardDetails'][0]['imageURL'] = '/public/images/dummyImg.gif';
					$data['edit'] = false;
					$data['vcardDetails'] = $result;
			}

			$Validate = $this->userStatus;
			$data['validateuser'] = $Validate;
			$categoryForLeftPanel = $this->getCategories();
			$data['categoryForLeftPanel'] = json_encode($categoryForLeftPanel);
			$data['catCountURL'] = site_url('messageBoard/MsgBoard/discussionHome')."/@cat@/1/1";
			$canonicalurl = SHIKSHA_HOME.'/messageBoard/MsgBoard/expertOnboard/';
			$data['canonicalURL'] = $canonicalurl;

			//below code used for beacon tracking
	        $data['trackingpageIdentifier']='expertProfileEditPage';
	        $data['trackingcountryId']=2;

	        //loading library to use store beacon traffic inforamtion
	        $this->tracking=$this->load->library('common/trackingpages');
	        $this->tracking->_pagetracking($data);

			$this->load->view('messageBoard/expertOnboard',$data);
	 }

	 function applyExpertProfile(){
		$this->init();
		
		$data = $_POST;
	    $msgbrdClient = new Message_board_client();
	    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		
		//First of all, if the user is not registered, we will register him with Shiksha
		if($userId<=0){
				$userarray['email'] = trim($this->input->post('quickemail'));
				$userarray['password'] = $this->input->post('quickpassword_ForAnA');
				$confirmpassword = $this->input->post('quickconfirmpassword_ForAnA');
				$userarray['ePassword'] = sha256($userarray['password']);
				$userarray['firstname'] = trim($this->input->post('quickfirstname_ForAnA'));
				$userarray['lastname'] = trim($this->input->post('quicklastname_ForAnA'));
				$userarray['displayname'] = trim($this->input->post('quickfirstname_ForAnA'));
				$userarray['coordinates'] = $this->input->post('coordinates_ForAnA');
				$userarray['resolution'] = $this->input->post('resolution_ForAnA');
				$userarray['sourceurl'] = SHIKSHA_ASK_HOME_URL."/messageBoard/MsgBoard/expertOnboard";
				$userarray['sourcename'] = $this->input->post('loginproductname_ForAnA');
				$userarray['mobile'] = $this->input->post('quickMobile_ForAnA');
				$userarray['quicksignupFlag'] = 'veryshortregistration';
				$userarray['usergroup'] = 'veryshortregistration';
				$secCodeIndex = 'secCodeForAnAReg';
				$secCode = $this->input->post('securityCode_ForAnA');
				$this->load->model('UserPointSystemModel');
				$addResult = $this->UserPointSystemModel->doQuickRegistration($userarray,$secCode,$secCodeIndex);
				if($addResult > 0) {
						//Set the cookie when the user has registered
						$Validate = $this->checkUserValidation();
						if(!isset($Validate[0]['userid'])){
							$value = $userarray['email'].'|'.$userarray['ePassword'];
							$value .= '|pendingverification';
							setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
							$userId = $addResult;
						}
				}
				else if($addResult == "Blank" || $addResult == "email" || $addResult == "displayname" || $addResult == "both" || $addResult == "code"){	//In case of some error in user registration
						echo $addResult;
						return;
				}
		}

		//Now, when the user is registered, we will submit his other details in the Expert table
	    //If some image has been uploaded, we need to upload it in the Media data folder
	    // Also, we need to validate the Uploaded files
	    $allCheck = true;
	    if(isset($_FILES) && is_array($_FILES) && !empty($_FILES['userApplicationfile']['name'][0]) ){
			$returnData = $this->uploadMedia();
			if(is_array($returnData) && isset($returnData['fileUrl'])){
				$data['profileImage'] = $returnData['fileUrl'];
			}
			else if(is_array($returnData) && isset($returnData['error'])){
				if( ($data['edit']=='true' || $data['edit']==true) && $returnData['error'] == 'Please select a file to upload' ){
				  $allCheck = true;
				}
				else{
					$allCheck = false;
					echo json_encode(array('errors'=>array(array('BrowserHidden',$returnData['error']))));
					return;
				}
			}
	    }

	    $allCheckS = true;

	    //If no validation issues are found, only then proceed with the submittion of form
	    if(is_array($data) && $allCheck && $allCheckS){
			$dataToSend = json_encode($data);
			$Result = $msgbrdClient->setExpertData($appId,$dataToSend,$userId);
			if(($data['edit']=='true' || $data['edit']==true)){
				echo "ExpertModified";
			}
			else{
				echo "ExpertAdded";
			}
			//After adding the data in the Expert table, we will also have to change the User's image in the user table
			$firstName = trim($this->input->post('quickfirstname_ForAnA'));
			$lastName = trim($this->input->post('quicklastname_ForAnA'));
			$this->load->library('Register_client');
			$registerClient = new Register_client();
			if(isset($data['profileImage']) && $data['profileImage']!=''){
				$registerClient->updateUserAttribute($appId, $userId, 'avtarimageurl', $data['profileImage'],'');
			}
			if($firstName != $this->userStatus[0]['firstname']){
				$registerClient->updateUserAttribute($appId,$userId,'firstname',$firstName);
			}
			if($lastName != $this->userStatus[0]['lastname']){
				$registerClient->updateUserAttribute($appId,$userId,'lastname',$lastName);
			}
			return;
	    }

		echo 1;
	 }

	function uploadMedia() {
	    $this->init();
	    $appId = 1;
		$index=0;
	    $ListingClientObj = new Listing_client();
	    $displayData['error'] = 'Please select a file to upload';
	    if(isset($_FILES['userApplicationfile']['name'][$index]) && $_FILES['userApplicationfile']['name'][$index]!=''){

		  $type = $_FILES['userApplicationfile']['type'][$index];
		  $size = $_FILES['userApplicationfile']['size'][$index];
		  if(!($type== "image/gif" || $type== "image/jpeg"|| $type=="image/jpg" || $type== "image/png" || $type== "image/pjpeg" || $type== "image/x-png" || $type== "image/pjpg"))
		  {
		      $displayData['error'] = 'Please upload only jpeg,png,jpg';
		  }
		  else if($size>2097152)
		  {
		      $displayData['error'] = 'Please upload a file of max 2 MB only';
		  }
		  else{
		      $fileName = explode('.',$_FILES['userApplicationfile']['name'][$index]);
		      $fileNameToBeAdded = $fileName[0];
		      $fileCaption= $fileNameToBeAdded;
		      $fileExtension = $fileName[count($fileName) - 1];
		      $fileCaption .= $fileExtension == '' ? '' : '.'. $fileExtension;

		      $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;;

		      $this->load->library('upload_client');
		      $uploadClient = new Upload_client();

		      $fileType = explode('/',$_FILES['userApplicationfile']['type'][$index]);
		      $mediaDataType = ($fileType[0]=='image')?'image':'pdf';

		      //$FILES = $_FILES;
              $FILES = array();
              $FILES['userApplicationfile']['type'][0] = $_FILES['userApplicationfile']['type'][$index];
              $FILES['userApplicationfile']['name'][0] = $_FILES['userApplicationfile']['name'][$index];
              $FILES['userApplicationfile']['tmp_name'][0] = $_FILES['userApplicationfile']['tmp_name'][$index];
              $FILES['userApplicationfile']['error'][0] = $_FILES['userApplicationfile']['error'][$index];
              $FILES['userApplicationfile']['size'][0] = $_FILES['userApplicationfile']['size'][$index];

		      //Before uploading the file, check the Size and type of file. Only if they are valid, will we proceed with the uploading


		      $upload_forms = $uploadClient->uploadFile($appId,$mediaDataType,$FILES,array($fileCaption),$userId, 'user','userApplicationfile');
		      //error_log(print_r($upload_forms,true));

		      if(is_array($upload_forms)) {
			  $displayData['fileId'] = $upload_forms[0]['mediaid'];
			  $displayData['fileName'] = $fileCaption;
			  $displayData['mediaType'] = $mediaDataType;
			  $displayData['fileUrl'] = $upload_forms[0]['imageurl'];
		      } else {
			  $displayData['error'] = $upload_forms;
		      }
		  }
	    }
	    return $displayData;
	}

	function spamCheck($str,$requestIp=''){
        if($str=='')
            return false;
	    //In case the text is Blank or coming from some spammed IP, we will discard them
            if( $requestIp=='82.33.210.228' || $requestIp=='122.168.213.101' || $requestIp=='122.168.204.87' || strpos($requestIp,'180.215.')!==false || strpos($requestIp,'116.203.')!==false || strpos($requestIp,'103.225.')!==false ){
                return true;
            }

            if ( strpos($str,'kala jaadu expert')!==false || strpos($str,'kala jaadu specialist')!==false || strpos($str,'ONLinE lOvE sOlUtIoNs')!==false || strpos($str,'LoVE mARrIaGE asTRoLOgEr')!==false || strpos($str,'9988310846')!==false || strpos($str,'9680047675')!==false || strpos($str,'9928926628')!==false || strpos($str,'9166736058')!==false || strpos($str,'9166647583')!==false ){
                return true;
            }

	    //Now, we will check if the text contains any objectionable/blocked words, then we will discard them
	    if(file_exists("globalconfig/stopwords.txt")){
		$blockedWords = file_get_contents("globalconfig/stopwords.txt");
        $blockedWords = base64_decode($blockedWords,true);
		$blockedArray = explode(",",$blockedWords);
		foreach ($blockedArray as $token) {
            if($token!='' && $token!='\n' && strlen($token)>2){
    		    if (stristr($str, ' '.$token.' ') !== FALSE) {
	    	    	return true;
		        }
            }
		}
	    }
	    return false;
	}

	function getInstituteURL(){
	    $instituteId = $this->input->post('instituteId');
	    if($instituteId>0){
		    $this->load->builder('ListingBuilder','listing');
		    $listingBuilder = new ListingBuilder;
		    $instituteRepository = $listingBuilder->getInstituteRepository();
		    $institute = $instituteRepository->find($instituteId);
		    echo $institute->getURL();
	    }
	}
    
	//Added by Ankur on 4 Dec for storing/updating suggestions on Answer
	function storeSuggestedInstitutes($suggestions,$answerId){
		$userStatus = $this->checkUserValidation();
		$userId = isset($userStatus[0]['userid'])?$userStatus[0]['userid']:0;
		$this->load->model('QnAModel');
		if($suggestions!=''){
			$response = $this->QnAModel->storeSuggestedInstitutes($suggestions,$answerId);
		}
	}
	
	function convertSuggestionArray($suggestionArray){
		$suggestionFinalArray = array();
		foreach ($suggestionArray as $suggestion){
			$answerId = $suggestion['answerId'];
			$instituteDetails = $this->getInstituteDetails(intval($suggestion['suggestionId']));
			$suggestionFinalArray[$answerId][] = array($suggestion['suggestionId'],$instituteDetails[0],$instituteDetails[1]);
		}
		return $suggestionFinalArray;
	}
	
	function getInstituteDetails($instituteId){
	    if($instituteId>0){
		    $this->load->builder('ListingBuilder','listing');
		    $listingBuilder = new ListingBuilder;
		    $instituteRepository = $listingBuilder->getInstituteRepository();
		    $institute = $instituteRepository->find($instituteId);
		    return array($institute->getName(),$institute->getURL());
	    }
	}

	function hideComment(){
		$type = $this->input->post('type');
		$msgId = $this->input->post('msgId');
		$threadId = $this->input->post('threadId');
		$this->load->model('QnAModel');
		$response = $this->QnAModel->hideComment($type,$msgId);
		modules::run('search/Indexer/addToQueue', $threadId, 'discussion', 'index');
		echo $response;
	}

        function unhideComment(){
                $type = $this->input->post('type');
                $msgId = $this->input->post('msgId');
                $this->load->model('QnAModel');
                $response = $this->QnAModel->unhideComment($type,$msgId);		
                echo $response;
        }

	function getAnswerFormforCampus()
	{
		$this->init(array('message_board_client','alerts_client','ajax'));
		$helper=array('url','image','shikshautility','validate','utility_helper');
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();
		
		$threadId = $this->input->post('threadId');
		$questionId = $threadId;
		$isQuesDetailPage = 'no';
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$userImageURLDisplay = isset($this->userStatus[0]['avtarurl'])?$this->userStatus[0]['avtarurl']:"/public/images/photoNotAvailable.gif";
		$questionUrl = SHIKSHA_ASK_HOME_URL."/getTopicDetail/".$threadId;
		  $dataArray = array('showMention'=>true,'userId'=>$userId,'userImageURL'=>$userImageURLDisplay,'userGroup' =>$userGroup,'threadId' =>$threadId,'ansCount' => 2,'detailPageUrl' =>$questionUrl,'callBackFunction' => 'try{ addMainCommentForQues('.$threadId.',request.responseText,\'-1\',true,true,\'\',\'\',true,\''.$userImageURLDisplay.'\','.$questionId.'); } catch (e) {}');
		  echo $this->load->view('messageBoard/replyBox',$dataArray);
		
	}
	
	function getCommentSectionforCampus()
	{
		$this->init(array('message_board_client','alerts_client','ajax'));
		$helper=array('url','image','shikshautility','validate','utility_helper');
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();
		$msgbrdClient = new Message_board_client();
		$threadId = $this->input->post('threadId');
		$answerId = $this->input->post('answerId');
		$focusForm = 'true';
		$appId = 1;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$ResultOfDetails = $msgbrdClient->getCommentSection($appId,$answerId,$userId);
		$displayData['ResultOfDetails'] = is_array($ResultOfDetails[0]['commentTree'])?$ResultOfDetails[0]['commentTree']:array();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['focusForm'] = $focusForm;
		$displayData['answerId'] = $answerId;
		$displayData['threadId'] = $threadId;
		$res = json_decode($msgbrdClient->getUserReputationPoints($appId,$userId));
	        $displayData['reputationPoints'] = $res[0]->reputationPoints;
	        echo $this->load->view('messageBoard/addComment',$displayData);
	}
	
	/**
	 * Returns the questions for course in question detail page for CA.
	 * The function call is made through Ajax
	 * @author Rahul
	 */
	function getOtherQuestion($excludeQuestionId) { 
		
		$this->init();
		
		$pageNo = isset($_POST['page_no'])?$this->input->post('page_no'):0;
		$callType = isset($_POST['callType'])?$this->input->post('callType'):'';
		$courseId = isset($_POST['course_id'])?$this->input->post('course_id'):$courseId;
		$excludeQuestionId = isset($_POST['exclude_question_id'])?$this->input->post('exclude_question_id'):$excludeQuestionId;

		$this->load->model('CA/cadiscussionmodel');
		$this->CADiscussionModel = new CADiscussionModel();
		
		$this->load->library('CA/CADiscussionHelper');
		$caDiscussionHelper =  new CADiscussionHelper();

		$pageStart = $pageNo*5;
		
		$excludeQuestionIdArray = array($excludeQuestionId);
		$questions = $this->CADiscussionModel->getQnA(
							array('courseId' => $courseId),1,'question','',$pageStart,5,'all',0,$excludeQuestionIdArray);

		
		$totalQuestions = $questions['total'];
		//$totalQuestionsLoaded = $pageStart + count($questions);
		
		$formatedData = $caDiscussionHelper->rearrangeQnA($questions['data']);
		
		$pageData = array();

		$pageData['showLoadMoreLink'] = true;
		if($pageNo == 1) {
			$pageData['showLoadMoreLink'] = false;
		}
		
		
		
		$pageData['qna'] = $formatedData['data'];
		
		if($callType=='Ajax'){
			echo $this->load->view('messageBoard/topicPage_quesDetail_otherQues',$pageData);
		}
		
	}
	
	
	/**
	Function to get MsgTree for myShortlist page.
	Author : Virender
	*/
	function getQuestionDetails($topicId)
	{
		$validity = $this->checkUserValidation();
		//_p($validity);die;
		$library=array('message_board_client','category_list_client','register_client','alerts_client','ajax','listing_client','relatedClient');
		$helper = array('url','image','shikshautility');
		$this->load->helper($helper);
		$this->load->library($library);
		$this->load->library('CA/CADiscussionHelper');
		$caDiscussionHelper =  new CADiscussionHelper();
		$appId = 12;
		$start = 0;
		$count = 10;
		$ResultOfDetails = array();
		$displayData = array();
		if($validity != 'false' && $topicId!='' && $topicId>0)
		{
			$userId    = $validity[0]['userid'];
			$userGroup = $validity[0]['usergroup'];
			$filter    ='upvotes';
			//$this->load->library('message_board_client');
			$msgbrdClient = new Message_board_client();
			$ResultOfDetails = $msgbrdClient->getMsgTree($appId,$topicId,$start,$count,1,$userId,$userGroup,$filter);
		}
		else
		{
			return;
		}
		//_p($ResultOfDetails);
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
		$totalComments = isset($ResultOfDetails[0]['totalComments'])?$ResultOfDetails[0]['totalComments']:$mainAnsCount;
		
		$displayData['entityType'] = $fromOthersTopic;
		$displayData['totalNumOfRows'] = $totalNumOfRows;
		$displayData['mainAnsCount'] = $mainAnsCount;
		$displayData['totalComments'] = isset($ResultOfDetails[0]['totalComments'])?$ResultOfDetails[0]['totalComments']:$mainAnsCount;
		$displayData['CategoryList'] = $CategoryList;
		//$displayData['courseId'] = $courseId;
		$displayData['questionCatCountry'] = $questionCatCountry;
		$displayData['questionText'] = $ResultOfDetails[0]['MainQuestion'][0]['msgTxt'];		
		
	    if(is_array($topic_reply) && count($topic_reply) > 0)
	    {
		$topic_messages = array();
		$i = -1;
		$arrayOfParameters = array();
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
						//$tempInner['userStatus'] = getUserStatus($topic_reply[$i]['lastlogintime']);
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
	   		$questionCreationDate = $topic_reply[0]['creationDate'];
			$displayData['topic_messages'] = $topic_messages;
			$topic_reply[0]['userStatus'] = getUserStatus($topic_reply[0]['lastlogintime']);
			$displayData['publishDate'] = date("Ymd",strtotime($topic_reply[0]['creationDate']));
			$topic_reply[0]['creationDate'] = makeRelativeTime($topic_reply[0]['creationDate']);
			$main_message = $topic_reply[0];
			$displayData['main_message'] = $main_message;
			$alreadyAnswer = isset($main_message['alreadyAnswered'])?$main_message['alreadyAnswered']:0;

			if($topic_reply[0]['status'] == 'closed')
				$closeDiscussion = 1;
		}
	    }
	    //$campusConnectBadges = array();
	    //$campusConnectBadges = $caDiscussionHelper->getBadgesForCA($repData);
	    $displayData['displayName'] = (!empty($main_message['lastname']))?$main_message['firstname'].' '.$main_message['lastname'] : $main_message['firstname'];
	    
	    if($main_message['listingType']=='institute' || $main_message['listingTypeId']!='0'){
		
		$this->load->model('QnAModel');
		$courseId = $this->QnAModel->getCourseIdOfQuestion($main_message['msgId']);
		if($courseId>0){
			 $this->load->model('CA/camodel');
		     $this->camodel = new CAModel();
		     
		     $this->load->model('CA/cadiscussionmodel');
		     $this->cadiscussionmodel = new CADiscussionModel();
		     
		     $this->load->library('CA/CADiscussionHelper');
		     $caDiscussionHelper =  new CADiscussionHelper();
		     
             $this->load->builder("nationalCourse/CourseBuilder");
             $courseBuilder = new CourseBuilder();
             $courseRepository = $courseBuilder->getCourseRepository();
             $courseObj = $courseRepository->find($courseId);
		      
		     $instituteId = $courseObj->getInstituteId();


			 $campusConnectData = 
			 			$this->cadiscussionmodel->getCampusRepInfoForCourse(array($courseId), "course" ,$instituteId);
			 
			 if(sizeof($campusConnectData['caInfo']) > 0) {
			 	$campusConnetAvailable = true;
			 }	
			 		 
			 $displayData['doNoShowAnswerForm'] = false;			 

			 // Get values only when campus rep is available 
			 if($campusConnetAvailable) {
			 	 $repData        = $caDiscussionHelper->formatCADataForListing($campusConnectData,3);

				 $campusConnectBadges = array();
				 $campusConnectBadges = $caDiscussionHelper->getBadgesForCA($repData);
				 $displayData['badges'] = $campusConnectBadges;
			
                                //DO not show Answer form to the Institute owner if Campus rep is available
                                $ownerId = $courseObj->getClientId();
                                if( $ownerId==$userId ){
                                        $displayData['doNoShowAnswerForm'] = true;
                                }

				 
				
				
			 }
			  
			 
			 
		}
	
	    }
	    $this->load->view('getQuestionDetailsInOverlay', $displayData);
	}
	
	//Update msgCount of All answers
	function updateMsgCountForAnswers(){
		ini_set('memory_limit',-1);
		$this->load->model('QnAModel');
		$this->QnAModel->updateMsgCountForAllAnswers();
		
	}
	
	function showRelatedArticles($articleId){
		$this->load->model('QnAModel');
		$data = $this->QnAModel->getCategoryAndSubCatIds($articleId);
		$unifiedCategoryId = $data['catId'];
		$CategoryId=$data['subCatId'];

		$url = "http://10.10.16.71:8985/solr/collection1/mlt?q=article_id:".$articleId."&wt=json&indent=true&mlt.mindf=1&mlt.mintf=1&mlt.fl=article_title&fl=article_id,%20article_title,article_category_ids,article_url,article_image_url,article_summary,article_category_info&fq=article_category_ids:".$unifiedCategoryId;
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postArray));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $decodeData = json_decode($response);

        $exactMatch = array();
        $secondLevelMatch = array();
		$finalMatchedData=array();
		if(empty($decodeData->response->docs))
		{
			return;
		}
        foreach ($decodeData->response->docs as $key => $value) {
        $ids = explode(",",$value->article_category_ids);
        $catId= $ids[1];
        $subcatId = $id[2];
  		if($value->article_category_ids=='1,'.$unifiedCategoryId.','.$CategoryId){
         	$exactMatch[] = $value;
        }
        if(($catId==$unifiedCategoryId) && $value->article_category_ids!='1,'.$unifiedCategoryId.','.$CategoryId){
         $secondLevelMatch[] = $value;
        }
        }

        curl_close ($ch);
        $finalMatchedData = array_merge($exactMatch,$secondLevelMatch);
		$relatedArticles = array();
		foreach ($finalMatchedData as $key => $value) {
			$relatedArticles[$key]['blogTitle'] = $value->article_title;
			$relatedArticles[$key]['blogId'] = $value->article_id;
			$relatedArticles[$key]['blogImageURL'] = $value->article_image_url;
			$relatedArticles[$key]['url'] = $value->article_url;
			$relatedArticles[$key]['summary'] = $value->article_summary;
			$blogIdArray[$key] = $value->article_id;
		}
		$result = $this->QnAModel->getCommentAndViewCount($blogIdArray);
		foreach ($relatedArticles as $key => $value) {
			$relatedArticles[$key]['viewCount'] = $result[$value['blogId']]['blogView'];
			$relatedArticles[$key]['commentCount'] = $result[$value['blogId']]['msgCount'];
		}
		return $relatedArticles;
	}	

        function migrateOldPoints(){
                ini_set("memory_limit", '2000M');
                $this->load->model('UserPointSystemModel');
                $this->UserPointSystemModel->migrateOldPointsToNewSystem();
        }

	function sixtyDaysInactivity(){
                $this->load->model('UserPointSystemModel');
                $this->UserPointSystemModel->sixtyDaysInactivity();
	}

	function clearTopContributorCache(){
		$this->load->library('cacheLib');
	    $cacheLib = new cacheLib;
		$catIdArray = array(1,2,3,4,5,6,7,9,10,11,12,13,14,239,240,241,242,243,244,245);
		foreach ($catIdArray as $key => $value) {
			$key1 = "topContributorsList01".$value;
			$key2 = "topContributorsList101".$value;
			$key3 = "topContributorsList201".$value;
			$key4 = "getTopContributors1".$value;
			$cacheLib->clearCacheForKey($key1);
			$cacheLib->clearCacheForKey($key2);
			$cacheLib->clearCacheForKey($key3);
			$cacheLib->clearCacheForKey($key4);
		}
		_p("done");

	}


	/**
	* Function to Migrate AnA Experts and Campus rep about me to their tuser profile
	*/
	function migrateAboutMeSection(){
        	$this->load->model('common/UniversalModel');
	        $result = $this->UniversalModel->migrateAboutMeSection();
    	}

    /* Function to send Application Link to User */
    function submitPhoneNoForApp(){
        $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
            header("Access-Control-Allow-Origin: ".$requestHeader);
            header("Access-Control-Allow-Credentials: true");
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
            header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
            header("Content-Type: application/json; charset=utf-8");
		 
		if(isset($_POST['phoneNo']))
			$phoneNo = $this->input->post('phoneNo');
		if(isset($_POST['sessionId']))
 			$sessionId = $this->input->post('sessionId');
		$message = 'Clear your career &#38; education doubts now. Download Shiksha Ask &#38; Answer App. Click to download -https://bit.ly/shk-desk-ana .';
		$isSent = \modules::run('SMS/sms_server/sendSingleSmsWithoutQueue',$phoneNo,$message);
		 $this->load->model('AnAModel');
     	 $submitPhoneNo = $this->AnAModel->savePhoneNoForAppLink($sessionId, $phoneNo, $isSent);
     	 if($submitPhoneNo>0){
     	 	echo 'success';
     	 }	
    	 
    }

    function expertDataForContent(){
    	$fromDate = isset($_POST['fromDate']) && $_POST['fromDate']!=''? $this->input->post('fromDate'):date('Y-m-d', strtotime('-7 day'));
    	$toDate = isset($_POST['toDate']) && $_POST['toDate']!=''? $this->input->post('toDate'):date('Y-m-d');
    	$emails   = $_POST['emailBox'];
    	$arr2 = array();
    	$emailStr = '';
    	if($emails != '')
    	{
    		$arr = explode("\n", $emails);
    		foreach ($arr as $value) {
    			$temp = trim($value, " \t\n");
    			if($temp != '')
    				$arr2[] = $temp;
    		}
    		$emailStr = '"'.implode('","', $arr2).'"';
    	}
    	$this->load->model('AnAModel');
     	$expertData = $this->AnAModel->getExpertDataForContent($emailStr, $fromDate, $toDate);
     	$displayData = array();
    	$displayData = $expertData;
    	$displayData['toDate'] = $toDate;
    	$displayData['fromDate'] = $fromDate;
    	$this->load->view('messageBoard/expertDataForContent', $displayData);
    }

        /**
         * @desc API to Fetch the List of Questions posted in Cafe
         * @param GET param1: Can be Page number / Date / Category
         * @param GET param2: Can be Date / Page Number
         * @param GET param3: Can be Page number
         * @return View with 20 question displayed
         * @date 2016-04-12
         * @author Ankur Gupta
         */
	function getQuestionListingPage($param1 = '', $param2 = '', $param3 = '', $param4 = ''){
		$tabselected = 1;
		$countryId=1;
		$myqnaTab='answer';
		$categoryId = 1;
		$inputDate = '';
		$start = 0;
		$rows = 20;
		$categoryForLeftPanel = $this->getCategories();
		$paginationURL = SHIKSHA_ASK_HOME."/questions/@pageno@";
		$dateURL = SHIKSHA_ASK_HOME."/questions/@date@";
		$canonicalurl = SHIKSHA_ASK_HOME."/questions";
		$nexturl = SHIKSHA_ASK_HOME."/questions/2";
		$previousurl = '';
		$data['title'] = "Ask Questions on Courses, Colleges, Exams, and Careers | Shiksha.com";
		$data['description'] = "Ask your questions on courses, colleges, exams, admissions, careers etc and connect with thousands of career experts, counsellors, and students online.";

		//Sort out the Params
		//1. First field can be Blank / Page number / Date / Category
		if(is_numeric($param1) && intval($param1) < 999999 && $param1>=1){	//This is Page number
			$start = ($param1 - 1) * $rows;
			if($param1 != 1){
				$canonicalurl .= "/$param1";
			}
			if($param1 == 2 || $param1 == 1){
				$previousurl = SHIKSHA_ASK_HOME."/questions";
			}
			else if($param1 > 2){
				$previousurl = SHIKSHA_ASK_HOME."/questions/" . ($param1 - 1);
			}
			$nexturl = SHIKSHA_ASK_HOME."/questions/" . ($param1 + 1) ;
		}
		elseif(is_numeric($param1) && $param1 > 999999){	//This is Date
			$canonicalurl .= "/$param1";
			//Now, convert the date
			$this->checkDateIsValid($param1);
			$inputDate = DateTime::createFromFormat('dmY', $param1)->format('Y-m-d');
			
			//Also, check Param 2, which will be Page number in this case
			if($param2 != '' && is_numeric($param2)){
				$start = ($param2 - 1) * $rows;
				if($param2 != 1){
					$canonicalurl .= "/$param2";
				}
			}elseif($param2 != ''){
				show_404();
                exit;
            }

			$paginationURL = SHIKSHA_ASK_HOME."/questions/$param1/@pageno@";

			$nexturl = SHIKSHA_ASK_HOME."/questions/$param1/2";
            if($param2 == 2){
				$nexturl = SHIKSHA_ASK_HOME."/questions/$param1/" . ($param2 + 1) ;
                $previousurl = SHIKSHA_ASK_HOME."/questions/$param1";
            }elseif($param2 > 2){
				$nexturl = SHIKSHA_ASK_HOME."/questions/$param1/" . ($param2 + 1) ;
                $previousurl = SHIKSHA_ASK_HOME."/questions/$param1/" . ($param2 - 1);
            }
			$titleDate = DateTime::createFromFormat('dmY', $param1)->format('jS F, Y');
	        $data['title'] = "Questions Asked on $titleDate | Shiksha.com";
	        $data['description'] = "Questions asked on $titleDate. Join education and community to connect with thousands of career experts, counsellors, and students online.";

		}elseif($param1 != ''){	//This is category
			$categoryName = $param1;
			$canonicalurl .= "/$param1";
			foreach ($categoryForLeftPanel as $key => $value){
				if( seo_url_lowercase($value[0],"-") == $categoryName){
					$categoryId = $key;
					$fullCategoryName = $value[0];
				}
			}
			if($param1 == "miscellaneous"){
				$categoryId = 0;
				$fullCategoryName = "Miscellaneous";
			}
			//Sanitize the Category Name
            if(!is_numeric($categoryId) || $categoryId==1){
            	//header("Location: ".SHIKSHA_ASK_HOME,TRUE,301);
				show_404();
				exit;
            }
			$paginationURL = SHIKSHA_ASK_HOME."/questions/$param1/@pageno@";
			$dateURL = SHIKSHA_ASK_HOME."/questions/$param1/@date@";
			$nexturl = SHIKSHA_ASK_HOME."/questions/$param1/2";
			$data['description'] = "Ask your questions on $fullCategoryName courses, colleges, exams, admissions etc and connect with thousands of career experts, counsellors, and students online.";
			$data['title'] = "Ask Questions on $fullCategoryName Education | Shiksha.com";
			
			//Also, check Param 2, which will be Date in this case
			if(is_numeric($param2) && $param2 > 999999){	//This is Date
				$this->checkDateIsValid($param2);
				$inputDate = DateTime::createFromFormat('dmY', $param2)->format('Y-m-d');
				$paginationURL = SHIKSHA_ASK_HOME."/questions/$param1/$param2/@pageno@";
				$canonicalurl .= "/$param2";
				$nexturl = SHIKSHA_ASK_HOME."/questions/$param1/2";
				$titleDate = DateTime::createFromFormat('dmY', $param2)->format('jS F, Y');
				$data['title'] = "$fullCategoryName Questions Asked on $titleDate | Shiksha.com";
				$data['description'] = "$fullCategoryName questions asked on $titleDate. Join education and community to connect with thousands of career experts, counsellors, and students online.";
			}elseif($param2 != '' && is_numeric($param2)){
				$start = ($param2 - 1) * $rows;
				if($param2 != 1){
					$canonicalurl .= "/$param2";
				}
				if($param2 == 2){
					$previousurl = SHIKSHA_ASK_HOME."/questions/$param1";
					$nexturl = SHIKSHA_ASK_HOME."/questions/$param1/3";
				}elseif($param2 > 2){
                                        $previousurl = SHIKSHA_ASK_HOME."/questions/$param1/". ($param2 - 1);
                                        $nexturl = SHIKSHA_ASK_HOME."/questions/$param1/" . ($param2 + 1);
				}
			}elseif($param2 != ''){
				show_404();
				exit;
			}
			
			//Also, check Param 3, which will be Page number in this case
			if($param3 != '' && is_numeric($param3)){
				$start = ($param3 - 1) * $rows;
				if($param3 != 1){
					$canonicalurl .= "/$param3";
				}
                if($param3 == 2){
                	$previousurl = SHIKSHA_ASK_HOME."/questions/$param1/$param2";
                	$nexturl = SHIKSHA_ASK_HOME."/questions/$param1/$param2/3";
                }elseif($param2 > 2){
                	$previousurl = SHIKSHA_ASK_HOME."/questions/$param1/$param2". ($param3 - 1);
                	$nexturl = SHIKSHA_ASK_HOME."/questions/$param1/$param2" . ($param3 + 1);
                }
			}elseif($param3 != ''){
				show_404();
				exit;
			}
		}
		
		if($param4 != ''){
			show_404();
			exit;
		}
		
		$currentUrl = $_SERVER['SCRIPT_URI'];
		$typeOfSearch = $this->setTypeOfSearch();
		$this->init(array('message_board_client','alerts_client','ajax'));
		$appId = 12;
		$myqnaTabArray = array('question','answer','bestanswer','untitledQuestion');
		if(!in_array($myqnaTab,$myqnaTabArray)){
			$myqnaTab='answer';
		}
		$alertCount = 0;
		$newRepliesCount = 0;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$msgbrdClient = new Message_board_client();
		$discussionUrl = site_url('messageBoard/MsgBoard/discussionHome');
		
		if($userId != 0){
			$alertCount = 0;
			$result = array();
			$cardStatus =  $msgbrdClient->getVCardStatus(1,$userId);
			
			//Modified for Shiksha performance task on 8 March: We will not get these data in case of Announcement or discussion homepages
			$data['followUser'] = array();
			$newRepliesCount = '0';
			
			$this->load->model('QnAModel');
			$leaderBoardInfo = array();
			$isAnAExpert = $this->QnAModel->checkIfAnAExpert('',$userId,false);
			$leaderBoardInfo['msgArray'][0]['isAnAExpert'] = $isAnAExpert;
			$data['leaderBoardInfo'] = $leaderBoardInfo;
			
			//End Modifications
			$res = json_decode($msgbrdClient->getUserReputationPoints($appId,$userId));
			if($res[0]->rank > 0){
			    $data['rank'] = $res[0]->rank;
			}else{
			    $data['rank'] = 'N/A';
			}
			if($res[0]->reputationPoints>0 && $res[0]->reputationPoints!='9999999'){
			  $data['reputationPoints'] = round($res[0]->reputationPoints);
			}elseif($res[0]->reputationPoints<=0 && $res[0]->reputationPoints!='9999999'){
			  $data['reputationPoints'] = 0;
			}elseif($res[0]->reputationPoints=='9999999'){
			  $data['reputationPoints'] = 10;
			}
	
		}
		
		$data['ACLStatus'] = array('MakeStickyDiscussion'=>'False','RemoveStickyDiscussion'=>'False','MakeStickyAnnouncement'=>'False','RemoveStickyAnnouncement'=>'False');
		$userFriends = array();
		foreach($result as $temp){
			array_push($userFriends,$temp['senderuserid']);
		}
		
		$parameterObj = array('popular_'.$categoryId.'_'.$countryId => array('totalCount'=>0),'recent_'.$categoryId.'_'.$countryId => array('totalCount'=>0),'unans_'.$categoryId.'_'.$countryId => array('totalCount'=>0),'myQuestions_'.$categoryId.'_'.$countryId => array('totalCount'=>0),'myAnswers_'.$categoryId.'_'.$countryId => array('totalCount'=>0),'myBestAnswers_'.$categoryId.'_'.$countryId => array('totalCount'=>0),'myuntitledQuestions_'.$categoryId.'_'.$countryId => array('totalCount'=>0));
		$recentKey = '';
		$recentKey = 'recentQuestions_'.$categoryId.'_'.$countryId.'_'.$start.'_'.$rows;
		
		$newRepliesCount = 0;		
		$userFriends = array();
		$arrayOfRes = array();
		$arrayOfUsers = array();
		$Result = $msgbrdClient->getLatestPostedQuestions($appId,$categoryId,$start,$rows,$userId,$inputDate);

		$count = is_array($Result[0])?$Result[0]['totalCount']:0;
		$countAnswered = isset($Result[0]['totalAnswered'])?$Result[0]['totalAnswered']:0;
		$arrayOfRes = is_array($Result[0])?$Result[0]['results']:array();
		$categoryCountry = is_array($Result[0])?$Result[0]['categoryCountry']:array();
		$levelVCard = isset($Result[0]['levelVCard'])?$Result[0]['levelVCard']:array();
		$levelAdvance = isset($Result[0]['levelAdvance'])?$Result[0]['levelAdvance']:array();
		$answerSuggestions = isset($Result[0]['answerSuggestions'])?$Result[0]['answerSuggestions']:array();
		$answerSuggestions = $this->convertSuggestionArray($answerSuggestions);
		$ratingStatusOfLoggedInUser = isset($Result[0]['ratingStatusOfLoggedInUser'])?$Result[0]['ratingStatusOfLoggedInUser']:array();
		$threadIdList = '';

		if(is_array($arrayOfRes)){
			for($i=0;$i<count($arrayOfRes);$i++){
				$currentUserId = $arrayOfRes[$i]['userId'];
				$found = 0;
				$urlForTopic = site_url('messageBoard/MsgBoard/topicDetails').'/'.$arrayOfRes[$i]['msgId'];
				$userProfile = site_url('getUserProfile').'/'.$arrayOfRes[$i]['displayname'];
				$arrayOfRes[$i]['creationDate'] = makeRelativeTime($arrayOfRes[$i]['creationDate']);
				$arrayOfRes[$i]['editorPickFlag'] = is_array($arrayOfRes[$i])?$arrayOfRes[$i]['editorPickFlag']:0;
				$userStatus = getUserStatus($arrayOfRes[$i]['lastlogintime']);
				$arrayOfRes[$i]['urlForTopic'] = $urlForTopic;
				
				if(in_array($arrayOfRes[$i]['userId'],$userFriends)){
					$arrayOfUsers[$currentUserId]['isFriend'] = 'true';
				}else{
					$arrayOfUsers[$currentUserId]['isFriend'] = 'false';
				}

				$arrayOfUsers[$currentUserId]['userStatus'] = $userStatus;
				$arrayOfUsers[$currentUserId]['userImage'] = $arrayOfRes[$i]['userImage'];
				$arrayOfUsers[$currentUserId]['displayname'] = $arrayOfRes[$i]['displayname'];
				$arrayOfUsers[$currentUserId]['level'] = $arrayOfRes[$i]['level'];
				$arrayOfUsers[$currentUserId]['userProfile'] = $userProfile;
				$arrayOfUsers[$currentUserId]['userOnlineStatus'] = getUserStatusToolTip($userStatus,$arrayOfRes[$i]['displayname'],$arrayOfRes[$i]['lastlogintime']);
				$arrayOfUsers[$currentUserId]['mailMsg'] = MAIL_TO_USER.$arrayOfRes[$i]['displayname'];
				$arrayOfUsers[$currentUserId]['addNetworkMsg'] = ADD_TO_NETWORK.$arrayOfRes[$i]['displayname'];
				$arrayOfUsers[$currentUserId]['alreadyAddedToNetworkMsg'] = $arrayOfRes[$i]['displayname'].' '.ALREADY_ADDED_TO_NETWORK;
				$threadIdList .= ($threadIdList=='')?$arrayOfRes[$i]['msgId']:",".$arrayOfRes[$i]['msgId'];
			}
	
		}
	
		$userResult = $msgbrdClient->getUserFlag($appId,$userId,$userGroup,$threadIdList);
		if(is_array($userResult)){
		   for($i=0;$i<count($userResult);$i++){
		      for($j=0;$j<count($arrayOfRes);$j++){
		      	if($arrayOfRes[$j]['msgId'] == $userResult[$i]['msgId']){
		      		$arrayOfRes[$j]['flagForAnswer'] = isset($userResult[$i]['flagForAnswer'])?$userResult[$i]['flagForAnswer']:0;
		      		$arrayOfRes[$j]['editorPickFlag'] = isset($userResult[$i]['editorPickFlag'])?$userResult[$i]['editorPickFlag']:0;
		      	}
		      }
		   }
		}
		
		$data['topicListings']	=	array(	'results'	=> $arrayOfRes,
											'arrayOfUsers' => $arrayOfUsers,
											'totalCount'=> $count,
											'totalAnswered'=>$countAnswered,
											'newRepliesCount' => $newRepliesCount,
											'levelAdvance' => $levelAdvance,
											'categoryCountry'=>$categoryCountry,
											'levelVCard'=>$levelVCard,
											'ratingStatusOfLoggedInUser'=>$ratingStatusOfLoggedInUser,
											'answerSuggestions'=>$answerSuggestions
										);
		$parameterObj['recent_'.$categoryId.'_'.$countryId]['totalCount']=isset($data['topicListings']['totalCount'])?$data['topicListings']['totalCount']:0;
	
		/*Calculate maximum number of results for Q&A tab ,Discussion  and Announcement*/
		$totalResult = $data['topicListings']['totalCount'];	

		//IN case of Last page, set the Next URL as Blank
		if(($start + $rows) >= $totalResult){
			$nexturl = '';
		}
				
		//In case a page number is added on which no data exists, redirect it to Base page
		if( $start > 0 && ((($start + $rows) / $rows) > ceil($totalResult / $rows)) ){
			$url = str_replace('/@pageno@','',$paginationURL);
			header("Location: $url",TRUE,301);
			exit;
		}
		
		$data['nexturl'] = $nexturl;
		$data['previousurl'] = $previousurl;
		$data['canonicalurl'] = $canonicalurl;
		$returnArray = $this->getCommentCookieContent();
	
		$data['recentKey'] = $recentKey;
		$data['popularKey'] = $popularKey;
		$data['unAnsweredKey'] = $unAnsweredKey;
		$data['myKey'] = $myKey;
		$data['editorKey'] = $editorKey;
		$data['userGroup'] = $userGroup;
		$data['myqnaTab'] = $myqnaTab;
		$data['questionText'] = isset($returnArray['questionText'])?$returnArray['questionText']:'';
		//$data['alertWidget'] = $this->getWidgetAlert(5,'byCategory',1,$this->userStatus);
		$data['parameterObj'] = json_encode($parameterObj);
		$data['actionDone'] = $actionDone;
		$data['listingParam'] = $listingParam;
		$data['appId'] = $appId;
		$data['alertCount'] = $alertCount;
		$data['categoryForLeftPanel'] = json_encode($categoryForLeftPanel);
		$countryList = $this->getCountries();
		$data['countryList'] = json_encode($countryList);
		$data['tabselected'] = $tabselected;
		$data['pageUrl'] = base64_encode(site_url('messageBoard/MsgBoard/discussionHome'));
		$data['categoryId'] = $categoryId;
		$data['countryId'] = $countryId;
		$data['selectedCategoryName'] = isset($categoryForLeftPanel[$categoryId][0])?$categoryForLeftPanel[$categoryId][0]:'All';
		if($categoryId == 0){
			$data['selectedCategoryName'] = "Miscellaneous";
		}
		$data['selectedCountryName'] = isset($countryList[$countryId])?$countryList[$countryId]:'';
		$data['friendArray'] = $userFriends;
		$data['newRepliesCount'] = $newRepliesCount;
		$data['trackForPages'] = true;
		$Validate = $this->userStatus;
		$data['validateuser'] = $Validate;
		$data['cardStatus'] = $cardStatus['status'];

		$data['catCountURL'] = SHIKSHA_ASK_HOME."/questions/@catName@";
		//$data['paginationURL'] = site_url('messageBoard/MsgBoard/discussionHome')."/".$categoryId."/".$tabselected."/".$countryId."/".$myqnaTab."/".$actionDone."/@start@/@count@";
		$data['paginationURL'] = $paginationURL;
		$data['tabURL'] = site_url('messageBoard/MsgBoard/discussionHome')."/".$categoryId."/@tab@/".$countryId."/@qnaTab@/".$actionDone;
		//$data['categoryURL'] = site_url('messageBoard/MsgBoard/discussionHome')."/@cat@/".$tabselected."/".$countryId."/".$myqnaTab."/".$actionDone;
		$data['start'] = $start;
		$data['rows'] = $rows;
		$data['infoWidgetData'] = $this->getDateForInfoWidget();
		$data['averageTimeToAnswer'] = $this->getAverageTimeForAswer();
		$data['typeOfSearch'] = $typeOfSearch;
		
		$googleRemarketingParams	=	array(	"categoryId" 	=> $categoryId,
												"countryId" 	=> 2
											); 
		
		$data['googleRemarketingParams'] = $googleRemarketingParams;
		$data['newQuestionPage'] = "true";
		
		$cpgs_param = $this->input->get_post('cpgs_param',true);
	
		$data['pageKeySuffixForDetail'] = 'ASK_ASKHOMEPAGE_WALL_';
		
		// added for course pages related change
		$data['tab_required_course_page'] = checkIfCourseTabRequired($categoryId);
		$data['subcat_id_course_page'] = $categoryId;
		if($data['tab_required_course_page']){
			$data['cat_id_course_page'] = $categoryForLeftPanel[$categoryId][1];
			if($tabselected == 6){
				$data['course_pages_tabselected'] = 'Discussions';
			}elseif(in_array($tabselected, array(1,3))) {
				$data['course_pages_tabselected'] = 'AskExperts';
			}
		}

		$dateDisplay = '';
		if($inputDate!=''){
			$dateDisplay = DateTime::createFromFormat('Y-m-d', $inputDate)->format('jS F, Y');
			$dateDisplay = $dateDisplay." Questions";
		}
		$data['dateDisplay'] = $dateDisplay;

		//below line id used to store the required infromation in beacon varaible for tracking purpose
		$this->tracking=$this->load->library('common/trackingpages');
	    
		//getting source page for PagetypeforGATracking
	    $trackingpageIdentifier=$this->tracking->getSourcePageName($tabselected);
	    
	    $data['trackingpageIdentifier']=$trackingpageIdentifier;
	    $data['trackingcatID']=$categoryId;
	    $data['trackingcountryId']=$countryId;
	       
		$data['trackingPaginationKey']=($start/$rows)+1;
	    
		$this->tracking->_pagetracking($data);
	       
	    //below line used for conversion tracking purpose
		$this->tracking->gettingPageKey($tabselected,$data);
		
		//Show the Links for Next/Prev Date Pages
        if($inputDate == ''){   //This is 30 days page
        	if( $totalResult <= ($rows+$start) ){   //This is the Last Page. SO, now we have to show the Link to 31st Day
        		$today = date("Y-m-d");
                $lastDate = strtotime("-31 days",strtotime($today));
                $lastDate = date ('Y-m-d' , $lastDate);
                $dateDis = DateTime::createFromFormat('Y-m-d', $lastDate)->format('jS F, Y');
                $dateText = $dateDis." Questions";
                $dateDis = DateTime::createFromFormat('Y-m-d', $lastDate)->format('dmY');
                $dateURLLink = str_replace('@date@',$dateDis,$dateURL);
                $data['dateDisplayText'] = "<a href='$dateURLLink' style='font-size:14px;'>View $dateText »</a>";
				$data['nexturl'] = $dateURLLink;
        	}
        }else{   //This is already a Date page and we have to go to the Next/Prev Page
        	$checkDate = strtotime('2008-05-13');
            if( ( $totalResult <= ($rows+$start) ) && ( $checkDate<=strtotime($inputDate) ) ){   //This is the Last Page of the Day and also date is greater than April 2008
            	$lastDate = strtotime("-1 days",strtotime($inputDate));
            	$lastDate = date ('Y-m-d' , $lastDate);
            	$dateDis = DateTime::createFromFormat('Y-m-d', $lastDate )->format('jS F, Y');
            	$dateText = $dateDis." Questions";
            	$dateDis = DateTime::createFromFormat('Y-m-d', $lastDate)->format('dmY');
            	$dateURLLink = str_replace('@date@',$dateDis,$dateURL);
            	$data['dateDisplayText'] = "<a href='$dateURLLink' style='font-size:14px;'>View $dateText »</a>";
				$data['nexturl'] = $dateURLLink;
            }
            //If this is the First Page, then we have to add code to go to the Prev Date page
			if($start == 0 && $inputDate!=''){
				$today = date("Y-m-d");
                $lastDate = strtotime("-31 days",strtotime($today));
                $lastDate = date ('Y-m-d' , $lastDate);
				if($inputDate == $lastDate){
					$dateURLLink = str_replace('/@date@','',$dateURL);
					$data['dateDisplayTextPrev'] = "<a href='$dateURLLink' style='font-size:14px;'>« View Recent Questions</a>";
				}else{
					$lastDate = strtotime("+1 days",strtotime($inputDate));
					$lastDate = date ('Y-m-d' , $lastDate);
					$dateDis = DateTime::createFromFormat('Y-m-d', $lastDate )->format('jS F, Y');
					$dateText = $dateDis." Questions";
					$dateDis = DateTime::createFromFormat('Y-m-d', $lastDate)->format('dmY');
					$dateURLLink = str_replace('@date@',$dateDis,$dateURL);
					$data['dateDisplayTextPrev'] = "<a href='$dateURLLink' style='font-size:14px;'>« View $dateText</a>";
				}
				$data['previousurl'] = $dateURLLink;
			}
        }
		$this->load->view('messageBoard/discussionHome',$data);
	}
    
	function checkDateIsValid($inputDate){
		$redirect = false;
		if(strlen($inputDate) != 8){
			$redirect = true;
		}
		if (!checkdate(substr($inputDate,2,2), substr($inputDate,0,2), substr($inputDate,4,4))) {//checkdate(month, day, year)
			$redirect = true;
		}
		if(!$redirect){
	                $checkDate = strtotime('2008-05-11');
			$inputDate = DateTime::createFromFormat('dmY', $inputDate)->format('Y-m-d');
                	if( $checkDate >= strtotime($inputDate) ){ 		
				$redirect = true;
			}
		}

		if($redirect){
                        header("Location: ".SHIKSHA_ASK_HOME."/questions",TRUE,301);
                        exit;
		}
		return true;
	}

        function redirect_301($url){
            header("Location: $url",TRUE,301);
            exit;        
        }


}
?>

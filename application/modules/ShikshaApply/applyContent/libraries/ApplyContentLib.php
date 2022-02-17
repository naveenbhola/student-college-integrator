<?php
class ApplyContentLib{

	private $CI;
	private $applyContentModel;
	
	function __construct() {
		$this->CI =& get_instance();
		$this->_setDependecies();
	}
	
	private function _setDependecies(){
		$this->applyContentModel = $this->CI->load->model('applyContent/applycontentmodel');
	}
	
	public function getContentData($type,$id){
		$data 						= $this->applyContentModel->getContentData($type,$id);
		$this->saContentModel 		= $this->CI->load->model('blogs/sacontentmodel'); 
		$authorInfo 				= $this->saContentModel->getAuthorInfo(array('contentId'=>$id)); 
		$data['authorInfo'] 		= reset($authorInfo); 
		return $data;
	}


	/* This function fetches the Urls for homepages of Apply Content
	*  DataArray is an array of all the content types who so ever is required eg. sop,lor,essay,cv,visa from our applyContentMasterList in abroadApplyContentConfig
	* $dataArray = array('1','2');
	*/
	public function getApplyContentHomePageUrl($dataArray = array())
	{
		$arr = array_filter($dataArray);
		if(empty($arr)){return false;}
		$data = $this->applyContentModel->getApplyContentHomePageUrl($dataArray);
		if(!empty($data))
		{
			$auxresult = array();
			$result = array();
			$this->CI->config->load('abroadApplyContentConfig');
			$applyContentTypes = $this->CI->config->item("applyContentMasterList");
			//now populate the array
			foreach ($data as $key => $value) 
			{

				$auxresult[$applyContentTypes[$value['content_type_id']]['type']]['contentId'] 			= $value['contentId'];
				$auxresult[$applyContentTypes[$value['content_type_id']]['type']]['contentURL'] 			= $value['contentURL'];
				$auxresult[$applyContentTypes[$value['content_type_id']]['type']]['content_type_id'] 		= $value['content_type_id'];
				$auxresult[$applyContentTypes[$value['content_type_id']]['type']]['type']					= $applyContentTypes[$value['content_type_id']]['type'];
				$auxresult[$applyContentTypes[$value['content_type_id']]['type']]['name']					= $applyContentTypes[$value['content_type_id']]['name'];
				$auxresult[$applyContentTypes[$value['content_type_id']]['type']]['heading']				= $applyContentTypes[$value['content_type_id']]['heading'];
                $auxresult[$applyContentTypes[$value['content_type_id']]['type']]['icon']				= $applyContentTypes[$value['content_type_id']]['icon'];
			}
			//sort types in the order as defined in the config
			$sortOrder = array_map(function($value){ return $value['type'];},$applyContentTypes);
			// _p($sortOrder);
			// _p($auxresult);

			foreach ($sortOrder as $value) 
			{
				if (array_key_exists($value, $auxresult)) 
				{
    				array_push($result,$auxresult[$value]);
    				unset($auxresult[$value]);
				}
			}
			// _p($result);
			// die;
			
			return $result;
		}
		return false;
	}
	
	// This function gets the correct guideURL for any applyContent piece.
	// It makes the parent and self decisions implicitly.
	public function getGuideURL($contentId){
		$url = $this->applyContentModel->getGuideURL($contentId);
		return $url;
	}

	/*
	 * apply content download count
	 */
	public function getApplyContentGuideDownloadCount($contentId)
	{	
		$val = $this->applyContentModel->totalGuideDownloded($contentId);
		if(!$val){
			return 0;
		}
		return $val;
	}
	public function trackDownloadGuide($dataArray)
	{
		return $this->applyContentModel->trackDownloadGuide($dataArray);
	}
	
	public function sendGuideEmail($guideUrl,$contentId,$trackingPageKeyId){
		if(!($contentId > 0))
		{
			return -1;
		}
		// if this is null, set a flag to get the url too
		$downloadLinkFlag = ($guideUrl == 'null'?true:false);
		$applyContentDetails = $this->applyContentModel->getContentDetails($contentId, $downloadLinkFlag);
		if($guideUrl == 'null')
		{
			$guideUrl = $applyContentDetails['download_link'];
		}
		$guideSize = getRemoteFileSize($guideUrl,FALSE);
        $sendGuideAsAttachment = FALSE;
        if($guideSize <= 5*1024*1024){
            $sendGuideAsAttachment = TRUE;
        }
        $guideSize = formatFileSize($guideSize);
        $alerts_client = $this->CI->load->library('alerts_client');
        if($sendGuideAsAttachment){
            $misObj = $this->CI->load->library('Ldbmis_client');
            $appId = 1;
            $type= 'guide';
            $contentURL = $guideUrl;
            $fileExtension = end(explode(".",$contentURL));
            $type_id = $misObj->updateAttachment($appId);
            $attachmentName = str_replace(" ",'_',$applyContentDetails['strip_title']);
            $attachmentName = preg_replace("/[^a-zA-Z0-9_]+/", "", $attachmentName);
            $attachmentName = $attachmentName.".".$fileExtension;
            $attachmentId = $alerts_client->createAttachment("12",$type_id,$type,'Guide','',$attachmentName,$fileExtension,'false', $contentURL);
        }
        $mailContents = $this->_getEmailContent($applyContentDetails,$guideUrl);
        $mailerData['toEmail'] = $mailContents['to'];
        $mailerData['fromEmail'] = SA_ADMIN_EMAIL;
        $mailerData['emailSubject'] = $mailContents['subject'];
        $mailerData['emailContent'] = $mailContents['body'];
        $mailerData['mailer_name'] = 'studyAbroadEmailApplyContentGuide';
        if(isset($attachmentId) && $attachmentId > 0) {
            $mailerData['attachmentId'] = $attachmentId;
        }
        $mailerResponse = Modules::run('systemMailer/SystemMailer/emailSAGuideToUser',$mailerData);
        /*if(isset($attachmentId) && $attachmentId > 0){
            $insertMailQResponse    = $alerts_client->externalQueueAdd("12",SA_ADMIN_EMAIL,$mailContents['to'],$mailContents['subject'],$mailContents['body'],"html",'','y',array($attachmentId));
        }else{
            $insertMailQResponse    = $alerts_client->externalQueueAdd("12",SA_ADMIN_EMAIL,$mailContents['to'],$mailContents['subject'],$mailContents['body'],"html");
        }*/
        if($mailerResponse == "Inserted Successfully"){
            $user = $this->CI->checkUserValidation();
			if($user == "false"){
				$userId = 0;
			}else{
				$userId = $user[0]['userid'];
			}
			$dataArray = array(
				'contentId'=>$contentId,
				'guideUrl'=>$guideUrl,
				'pageUrl'=>$_SERVER['HTTP_REFERER'],
				'sourceSite'=> 'mobile',
				'userId'=>$userId,
				'sessionId'=>sessionId(),
				'downloadedAt'=>date('Y-m-d H:i:s'),
				'tracking_keyid' => $trackingPageKeyId,
				'visitorSessionid' => getVisitorSessionId()
			);
			$this->trackDownloadGuide($dataArray);
            return 1;
        }else{
            return -1;
        }
	}
	
	private function _getEmailContent($applyContentDetails,$guideUrl){
		$mailContents = array();
        $mailContents['subject']    = $applyContentDetails['strip_title'];
		$userData = $this->CI->checkUserValidation();
        $params = array();
        $cookieStrArray = explode('|', $userData[0]['cookiestr']);
        $params['userEmailId']  = $cookieStrArray[0];
        $params['firstName']    = $userData[0]['firstname'];
        $params['guideSeoUrl']  = $applyContentDetails['contentURL'];
        $params['attachmentAvailable']  = $attachmentAvailable;
        $params['downloadLink'] = $guideUrl;
        $params['guideName']    = $applyContentDetails['strip_title'];
        $mailContents['body']   = $this->CI->load->view('contentPage/guideEmail',$params,TRUE);
		$mailContents['to'] =  $params['userEmailId'];
        return $mailContents;
	}
	
	public function getPopularArticlesLastNnoOfDays($contentType ,$contentTypeId,$contentId)
	{
		$data 		= $this->applyContentModel->getPopularArticlesLastNnoOfDays($contentType,$contentTypeId,$contentId);
		return $data;
	}
	
	public function getRecommendedContents($contentType ,$contentTypeId,$contentId,$noOfcontent)
    {
    	$result = $this->applyContentModel->getRecommendedContents($contentType ,$contentTypeId,$contentId,$noOfcontent);
        if(empty($result)) { return array(); }
        return $result;
    }	
	
	public function getComments($contentId,$pageNo = 0){
		  $this->SAContentModel =  $this->CI->load->model('blogs/sacontentmodel');
		  $this->CI->load->helper(array('blogs/sacontent'));
		  $returnArray = array();
		  $commentsArray = array();
		  $replyArray = array();
		  $pageStart = $pageNo*50;
		  $comments = $this->SAContentModel->getComments($contentId,'comment',array(),$pageStart);
		  $commentsArray  =  $comments['data'];
		  $commentIds = getCommentIds($commentsArray);
		  
		  if(!empty($commentIds)) {
			$userIds = array_map(function($a){ return $a['userId']; },$comments['data']);
			$replies = $this->SAContentModel->getComments($contentId,'reply',$commentIds);
			$replyArray = $replies['data'];
			if(count($replies['data'])>0)
			{
				// collect userIds of commentors
				$userIds = array_unique(array_merge($userIds, array_map(function($a){ return $a['userId']; },$replies['data'])));
			}
			$contentPageLib = $this->CI->load->library('contentPage/ContentPageLib');
			$returnArray['userData'] = $contentPageLib->getUserInfoForComments(array_unique($userIds));
		  }
		  
		  if(!empty($commentsArray) && !empty($replyArray)) {
			  $commentsArray  = array_merge($commentsArray,$replyArray);
		  }else if(!empty($commentsArray)) {
			  $commentsArray  = $commentsArray;
		  }else {
			  $commentsArray  = $replyArray;
		  }
		  
		  if(!empty($commentsArray)){
			  $commentsArray = rearrangeComments($commentsArray);
		  }
		  
		  
		  $returnArray['data'] = $commentsArray;
		  $returnArray['total'] = $comments['total'];
		  
		  return $returnArray;
	 }
	 /* Purpose :We will only show 2 articles in this recommendation:
				 We will show 1 article from previous bucket and 1 article from next bucket.
				 While selecting the 1 article from previous/next buckets, apply the old article popularity algo which accounts for viewcount, comments and creation date.
	 */
	 public function alsoLikeArticlesData($contentTypeId,$contentTypeIdMasterList,$numOfArticlesOfEachType)
	 {
	 	//choose the first bucket randomly
	 	$first = (rand(0,sizeof($contentTypeIdMasterList)-1));	 	
	 	$firstContentTypeId = $contentTypeIdMasterList[$first]; 
	 	unset($contentTypeIdMasterList[$first]);
	 	$contentTypeIdMasterList = array_values($contentTypeIdMasterList);
	 	//choose the second bucket randomly
	 	$second = (rand(0,sizeof($contentTypeIdMasterList)-1));
	 	$secondContentTypeId = $contentTypeIdMasterList[$second];
	 	$contentTypeIds = array($firstContentTypeId,$secondContentTypeId);
	 	$result = $this->applyContentModel->getRandomArticleData($contentTypeIds,$numOfArticlesOfEachType);
		return $result;
	 }
                 
	public function sendErrorMailForDownloadGuideTracking($dataTrackedArray) {
	   $mailHtml = '<table border="0" cellspacing="0" cellpadding="2">';
	   $dataToBeMailed = array_merge($_SERVER, $dataTrackedArray);
	   $mailHtml.="<tr><th>Parameter</th><th>ParameterValue</th></tr>";
	   foreach ($dataToBeMailed as $dataKey => $data) {
		   $mailHtml.="<tr>";
		   $mailHtml.="<td>$dataKey</td>";
		   $mailHtml.="<td>$data</td>";
		   $mailHtml.="</tr>";
	   }
	   $mailHtml.="</table>";
	   $studyAbroadCommonLibObject=$this->CI->load->library('common/studyAbroadCommonLib');
	   $studyAbroadCommonLibObject->selfMailer('Download Guide Tracking Error',$mailHtml);
   }
	/*
	 * finds content_type_id for given apply content id
	 */
	public function getApplyContentTypeIdById($contentId)
	{
		$applyContentModel = $this->CI->load->model("applyContent/applycontentmodel");
		$result = $applyContentModel->getApplyContentTypeIdById($contentId);
		$resArr = array();
		foreach($result as $row)
		{
			$resArr[$row['content_id']] = $row['content_type_id'];
		}
		return $resArr;
	}

    public function getPopularContentByContentType($applyContentTypeId,$noOfContents=4)
    {
        if(empty($applyContentTypeId) && !is_int($applyContentTypeId))
        {
            return false;
        }
        $saContentCache = $this->CI->load->library('applyContent/cache/ApplyContentCache');
        $popularApplyContent = $saContentCache->getPopularApplyContentByApplyContentType($applyContentTypeId.$noOfContents);
        if(empty($popularApplyContent))
        {
            $applyContentModel = $this->CI->load->model("applyContent/applycontentmodel");
            $popularApplyContent = $applyContentModel->getPopularApplyContentByApplyContentType($applyContentTypeId,$noOfContents);
            $saContentCache->storePopularApplyContentByApplyContentType($applyContentTypeId.$noOfContents,$popularApplyContent);
        }
        foreach($popularApplyContent as $key=>$row){
            $popularApplyContent[$key]['contentUrl'] = SHIKSHA_STUDYABROAD_HOME.$popularApplyContent[$key]['contentUrl'];
            $popularApplyContent[$key]['contentImageURL'] = MEDIAHOSTURL.$popularApplyContent[$key]['contentImageURL'];
        }
        return $popularApplyContent;
    }
    /*prepare breadcrumb data for apply content */
    public function prepareBreadCrumbData($displayData){
        $breadCrumbData=array();
        $contentDetails=$displayData['contentData'];
        //_p($contentDetails);die;
        array_push($breadCrumbData,array('url'=>SHIKSHA_STUDYABROAD_HOME,'title'=>'Home'));
        if(!$contentDetails['is_homepage']){
            if(isset($displayData['learnApllicationProcessData'])) {
                $homeContentUrl = $displayData['learnApllicationProcessData'][$displayData['contentTypeId'] - 1]['contentURL'];
                array_push($breadCrumbData, array('url' => $homeContentUrl, 'title' => strtoupper($displayData['contentType'])));
            }
            array_push($breadCrumbData, array('url'=>'','title'=>strip_tags($contentDetails['title'])));
        }else{
            array_push($breadCrumbData, array('url'=>'','title'=>strip_tags($contentDetails['title'])));
        }
        return $breadCrumbData;
    }

    public function getH1Title($displayData)
	{
		$contentId   = $displayData['contentId'];
		$contentLabel= $displayData['topNavData']['links_data'][$contentId][label];
		$contentType = $displayData['contentData']['type'];

		$H1Title='';
		$H1Title=htmlentities($displayData['topNavData']['content_type_title']); 
		if($contentType=='applyContent')
			$seperator=" - ";
		else $seperator=" ";
		
		 if(htmlentities($contentLabel)!='Overview' && !empty($contentLabel)) 
		 	$H1Title.=$seperator.$contentLabel;
		
		 return $H1Title;
	}
}

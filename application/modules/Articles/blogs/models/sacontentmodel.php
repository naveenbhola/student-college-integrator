<?php
class SAContentModel extends MY_Model
{ 	/*

   Copyright 2014 Info Edge India Ltd

   $Author: Rahul

   $Id: 

 */
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct()
	{ 
		parent::__construct('SAContent');
	}
	
	private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}
    /* This is to fetch all the content details of article guide and exam pages*/
	function getContentDetails($contentId) {
		$this->initiateModel();
		
		//fetches the content details from sa_content and it assocaited tags
		$sql = "select 
				sac.id,
				sac.content_id,
				sac.type,
				sac.exam_id as exam_type,
				sac.title,
				sac.title as strip_title,
				sac.summary,
				sac.seo_title,
				sac.seo_description,
				sac.seo_keywords,
				sac.is_downloadable,
				sac.download_link,
				sac.content_image_url as contentImageURL,
				sac.content_url as contentURL,
				sac.view_count as viewCount,
				sac.comment_count as commentCount,
				sac.created_on as created,
				sac.updated_on as last_modified,
				sac.status,
				sac.created_by,
				sac.updated_by as last_modified_by,
				sac.related_date as relatedDate,
				sac.published_on as contentUpdatedAt,
				sac.popularity_count as popularityCount,
				sac.is_homepage,
				sac.apply_content_type_id 

				,  group_concat(sact.tag_title separator ',') as tags from sa_content as sac left join sa_content_tags_mapping as sactm on sac.content_id = sactm.content_id
				left join sa_content_tags as sact on sact.id = sactm.tag_id  and  sactm.status = 'live' and  sact.status = 'live'
				where sac.content_id = ? and sac.status = 'live'  group by sac.content_id" ;
		$query = $this->dbHandle->query($sql,array($contentId));
		$contentResults = $query->result_array();

		//fetches content sections of that content
		$sql1 = "select * from sa_content_sections  where content_id = ? and status = 'live'" ;
		$query = $this->dbHandle->query($sql1,array($contentId));
		$sectionResults = $query->result_array();
		
		//fetches user details for the content written by
		$contentUserId = $contentResults[0]['created_by'];
		$sql2 = "select firstname,lastname,avtarimageurl,displayname ,email from tuser where userid = ?";		
		$query = $this->dbHandle->query($sql2,array($contentUserId));
		$userResult = $query->result_array();
		$userName = $userResult[0]['firstname']. ' ' . $userResult[0]['lastname'];

		//fetches country names and id for the tags
                $sqlCountry = "SELECT m.attribute_id as country_id, name countryName FROM sa_content_attribute_mapping m, ".ENT_SA_COUNTRY_TABLE_NAME." WHERE m.attribute_mapping='country' and m.content_id = ? AND m.status = 'live' AND countryId = m.attribute_id AND m.attribute_id > 2";
                $query = $this->dbHandle->query($sqlCountry,array($contentId));
				$mappedCountryResults = $query->result_array();
				$countryResult = reset($mappedCountryResults);
		$countryId = $countryResult['country_id'];
		$countryName = $countryResult['countryName'];

		//fetches associated guide details, if exists
		$guideURL = $guideSummary = "";
		if($countryId>0){
			$sql = "SELECT cnt.content_id,cnt.summary,cnt.created_on as created, cnt.content_url as contentURL FROM sa_content cnt INNER JOIN sa_content_attribute_mapping cntmp  ON (cntmp.attribute_mapping='country' and cnt.content_id=cntmp.content_id AND cnt.status=cntmp.status) WHERE cnt.status='live' AND cnt.type='guide' AND cntmp.attribute_id=? ";
        	        $query = $this->dbHandle->query($sql,array($countryId));
                	$countryResults = $query->row();
	                $guideSummary = $countryResults->summary;
        	        $guideURL = SHIKSHA_STUDYABROAD_HOME.$countryResults->contentURL;		
		}

		$finalResult = array();
		if(!empty($contentResults[0])) {
			// prepend protocol & domain to urls
			$contentResults[0]['strip_title'] = html_entity_decode(strip_tags($contentResults[0]['strip_title']),ENT_NOQUOTES, 'UTF-8');
			$contentResults[0]['download_link'] = MEDIAHOSTURL.$contentResults[0]['download_link'];
            $contentResults[0]['contentImageURL'] = MEDIAHOSTURL.$contentResults[0]['contentImageURL'];
            $contentResults[0]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$contentResults[0]['contentURL'];
			$finalResult['data'] = $contentResults[0];
			$finalResult['data']['countryId'] = $countryId;
			$finalResult['data']['countryName'] = $countryName;
			$finalResult['data']['mappedCountryResults'] = $mappedCountryResults;
            $finalResult['data']['guideSummary'] = $guideSummary;
            $finalResult['data']['guideURL'] = $guideURL;
			$finalResult['data']['username'] = $userName;
            $finalResult['data']['email'] 		= $userResult[0]['email'];
			$finalResult['data']['displayName'] = $userResult[0]['displayname'];
	        $finalResult['data']['avatarimageurl'] = MEDIAHOSTURL.$userResult[0]['avtarimageurl'];
	        foreach ($sectionResults as $index => $data) {
	        	$finalResult['data']['sections'][] = $data;
	        }	         
		}		
		return $finalResult;
	}
        
        
	/*
	* Get content articles of author for slider
	*/
	function getUserContent($userId,$start,$count,$contentId){
                $this->initiateModel();
		$sql =  "select 
				sac.id,
				sac.content_id,
				sac.type,
				sac.exam_id as exam_type,
				sac.title,
				sac.title as strip_title,
				sac.summary,
				sac.seo_title,
				sac.seo_description,
				sac.seo_keywords,
				sac.is_downloadable,
				sac.download_link,
				sac.content_image_url as contentImageURL,
				sac.content_url as contentURL,
				sac.view_count as viewCount,
				sac.comment_count as commentCount,
				sac.created_on as created,
				sac.updated_on as last_modified,
				sac.status,
				sac.created_by,
				sac.updated_by as last_modified_by,
				sac.related_date as relatedDate,
				sac.published_on as contentUpdatedAt,
				sac.popularity_count as popularityCount,
				sac.is_homepage,
				sac.apply_content_type_id 
		 from sa_content sac where sac.status = 'live' and sac.created_by=?  and sac.content_id != ?  ORDER BY  sac.updated_on Desc LIMIT $start ,$count";
		$query = $this->dbHandle->query($sql,array($userId,$contentId));
		$contentResults = $query->result_array();
                
                return $contentResults;
	}
         
	/*
	* Get content url and title for mailer
	*/
	function getContentUrlAndTitleForMailer($contentId){
        $this->initiateModel();
		$sql =  "select sac.content_url as contentURL,sac.type,sac.title as strip_title from sa_content sac where sac.status = '" . ENT_SA_PRE_LIVE_STATUS . "'  and sac.content_id = ? ";
		$query = $this->dbHandle->query($sql, array($contentId));
		$contentResults = $query->result_array();
		for($i=0;$i<count($contentResults);$i++)
		{
			$contentResults[$i]['strip_title'] = html_entity_decode(strip_tags($contentResults[$i]['strip_title']),ENT_NOQUOTES, 'UTF-8');
			$contentResults[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$contentResults[$i]['contentURL'];
		}
        return $contentResults;
	}
		
	/*
	* Get multiple content url and title for mailer
	*/
	function getMultipleContentUrlAndTitleForMailer($contentIdArray = array()){
        $this->initiateModel();
		$contentIds = implode(',',$contentIdArray);
		$sql =  "select sac.content_id,sac.content_url as contentURL,sac.title as strip_title,sac.content_image_url as contentImageURL,sac.summary from sa_content sac where sac.status = '" . ENT_SA_PRE_LIVE_STATUS . "' and sac.type = 'article' and sac.content_id in ($contentIds) ";
		$query = $this->dbHandle->query($sql);
		$contentResults = $query->result_array();
		for($i=0;$i<count($contentResults);$i++)
		{
			$contentResults[$i]['strip_title'] = html_entity_decode(strip_tags($contentResults[$i]['strip_title']),ENT_NOQUOTES, 'UTF-8');
			$contentResults[$i]['contentImageURL'] = MEDIAHOSTURL.$contentResults[$i]['contentImageURL'];
			$contentResults[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$contentResults[$i]['contentURL'];
		}
        return $contentResults;
	}
    
    /*
	* Get multiple content url and title for mailer
	*/
	function getMultipleContentDetailsAndCountryForMailer($contentIdArray = array()){
        $this->initiateModel();
		$contentIds = implode(',',$contentIdArray);
		$sql =  "select sac.content_id,sac.content_url as contentURL,sac.title as strip_title,sac.content_image_url as contentImageURL,sac.summary,saccm.attribute_id as country_id from sa_content sac LEFT JOIN sa_content_attribute_mapping saccm ON sac.content_id = saccm.content_id and saccm.attribute_mapping='country' and saccm.status = '" . ENT_SA_PRE_LIVE_STATUS . "' where sac.status = '" . ENT_SA_PRE_LIVE_STATUS . "' and sac.type in ('article','guide','applyContent','examContent') and sac.content_id in ($contentIds) ";
		$query = $this->dbHandle->query($sql);
		$contentResults = $query->result_array();
		foreach ($contentResults as $key => $result) {
			$returnArray[$result['content_id']]['content_id'] = $result['content_id'];
			$returnArray[$result['content_id']]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$result['contentURL'];
			$returnArray[$result['content_id']]['strip_title'] = $result['strip_title'];
			$returnArray[$result['content_id']]['contentImageURL'] = MEDIAHOSTURL.$result['contentImageURL'];
			$returnArray[$result['content_id']]['summary'] = $result['summary'];
			$returnArray[$result['content_id']]['country_id'][] = $result['country_id'];
		}
        return $returnArray;
	}

	/*
	* Get comment user details info
	*/
	function getCommentUserInfo($commentId){
                $this->initiateModel();
		$sql =  "select sacd.user_name as userName,sacd.email_id as emailId,sacd.user_id as userId,sacd.comment_text as commentText from  sa_comment_details sacd where sacd.status = '" . ENT_SA_PRE_LIVE_STATUS . "' and sacd.comment_id = ?";
		$query = $this->dbHandle->query($sql, array($commentId));
		$userInfo = $query->result_array();
                return $userInfo;
	}
	 
	/*
	* Get popular articles for right widget
	*/
        public function getPopularArticlesLastNnoOfDays($noOfDays=30,$noOfarticles=6,$contentType=""){

				$timeStamp = date('Y-m-d H:i:s', strtotime($date .' -'.$noOfDays.' day'));
         		$this->initiateModel('read');
                $sql = "SELECT
				sav.content_id as contentId,
				sum(sav.view_count) as viewCount,
				sac.title as strip_title,
				sac.content_url as contentUrl,
				sac.view_count as articleViewCnt,
				sac.comment_count as commentCount FROM
				sa_content_view_count sav ,
				sa_content sac
				WHERE sav.view_date > '".$timeStamp."' and
				sav.content_id = sac.content_id and
				sac.status = 'live' and ";
				if($contentType != '')
				{
					$sql .= " sac.type ='".$contentType."' ";
				}
				else{
					$sql .= " sac.type in ('article','guide') ";
				}
				$sql .= " GROUP BY sac.content_id ORDER BY 2 DESC LIMIT ".($noOfarticles+1)."";
				$result = $this->dbHandle->query($sql)->result_array();
				for($i=0;$i<count($result);$i++)
				{
					$result[$i]['strip_title'] = html_entity_decode(strip_tags($result[$i]['strip_title']),ENT_NOQUOTES, 'UTF-8');
				}
                return $result;
        }

    public function getContentImages($contentId,$status) {
    	$this->initiateModel('read');
		$sql = "select 
			content_image_id as saContentimageId,
			content_id as saContentId,
			image_url as imageUrl,
			media_id as mediaId,
			status,
			updated_on as timeUpdated
		 from sa_content_images where content_id = ? and status = ?";
	 	$query = $this->dbHandle->query($sql, array($contentId,$status));
    	$contentResults = $query->result_array();
    	
    	return $contentResults;
    	
    }
	/*
	* Tracking of download guide.
	* Params : ContentId : Id of the guide
	* 	   UserId :Incase the user is logged out user Id will be 0 
	*/
	
	public function trackDownloadGuide($contentId,$userId,$pageUrl,$trackingPageKeyId){
		$this->initiateModel('write');
		$pageUrl = base64_decode($pageUrl);
		$data = array(
				'session_id'=> sessionId(),
				'guide_id'=> $contentId,
				'page_url'=>$pageUrl,
				'user_id' =>$userId,
				'tracking_key_id' => $trackingPageKeyId,
				'visitor_session_id' => getVisitorSessionId()
			);
					
		$this->dbHandle->insert('sa_guide_download_tracking',$data);
        return 1;
	}
	
	public function totalGuideDownloded($guideId){
		$this->initiateModel('read');
		$sql = "select count(*) from  sa_guide_download_tracking where guide_id = ? ";
		$totalRows = reset(reset($this->dbHandle->query($sql,array($guideId))->result_array()));
		return $totalRows;
	}
	
	public function checkMappingwithSAContent($blogId){
		$this->initiateModel('read');
		$sql = "select countryId from blogTable where blogId = ? and status = 'live'";
		$countryResult = $this->dbHandle->query($sql, array($blogId))->result_array();
		
		if($countryResult[0]['countryId']!='2'){
			$sql = "select sac.content_url as contentURL from sa_content_mapping_articleid sacma inner join sa_content sac on sac.content_id = sacma.content_id where sacma.old_article_id = ? and sacma.status = '" .ENT_SA_PRE_LIVE_STATUS. "' and sac.status = '" .ENT_SA_PRE_LIVE_STATUS. "' order by sacma.created_on DESC limit 0,1";
			$contentResults = $this->dbHandle->query($sql, array($blogId))->result_array();
		
		$result['countryId'] =  $countryResult[0]['countryId'];
		$result['contentURL'] = $contentResults[0]['contentURL'];
		}
		return $result;
	}

    public function addComment($data,$saveAsDraft) {

    	$this->initiateModel('write');
        if($saveAsDraft){
            $data['status'] = 'draft';
		}
		$insertData = array();
		if(!is_null($data['commentId']))
		{
			$insertData['comment_id'] = $data['commentId'];
		}
		$insertData['comment_path'] = $data['commentPath'];
		$insertData['parent_id'] = $data['parentId'];
		$insertData['content_id'] = $data['contentId'];
		if(!is_null($data['userId']))
		{
			$insertData['user_id'] = $data['userId'];
		}
		$insertData['email_id'] = $data['emailId'];
		$insertData['user_name'] = $data['userName'];
		if(!is_null($data['commentTime'])){
			$insertData['comment_time'] = $data['commentTime'];
		}
		$insertData['comment_text'] = $data['commentText'];
		$insertData['section_id'] = $data['sectionId'];
		$insertData['status'] = $data['status'];
		$insertData['source'] = $data['source'];
		$insertData['tracking_key_id'] = $data['tracking_keyid'];
		$insertData['visitor_session_id'] = $data['visitorSessionid'];

    	$this->dbHandle->insert('sa_comment_details', $insertData);    	
    	
    	$commentId = $this->dbHandle->insert_id();
    	
    	$updateArray = array();
    	if($data['parentId'] == 0) {
    		$updateArray['comment_path'] = $commentId;
    	}else {
    		$updateArray['comment_path'] = $data['parentId'].".".$commentId;
    	}
    	
    	$updateArray['comment_id'] = $commentId;
    	$this->dbHandle->where("id",$commentId);
    	$this->dbHandle->update('sa_comment_details',$updateArray);

    	if($data['parentId'] == 0 && !$saveAsDraft) {
			$contentId = $data['contentId'];
			$selectedContent = $this->_getUpdatedOn($contentId);
			if(count($selectedContent)>0){
				$sql = "update sa_content set comment_count = comment_count + 1 , updated_on = ? where content_id = ? and status = 'live'";
				$query = $this->dbHandle->query($sql,array($selectedContent[0]['updated_on'], $contentId));
			}
    	}
    	
    	return $commentId;
    	
    } 
	
	private function _getUpdatedOn($contentId)
	{
		if(!($contentId > 0))
		{
			return array();
		}
		$selectSql = "select content_id, updated_on from sa_content where content_id=? and status='live' ";
		$selectedContent = $this->dbHandle->query($selectSql, array($contentId))->result_array();
		return $selectedContent;
	}
    public function deleteComment($type,$commentId,$contentId,$sectionId = 0) {
    	
    	$this->initiateModel('write');

    	if($type == 'comment') {
			$selectedContent = $this->_getUpdatedOn($contentId);
			if(count($selectedContent)>0){
				$sql = "update sa_content set comment_count = comment_count - 1 , updated_on = ? where content_id = ? and status = 'live'";
				$query = $this->dbHandle->query($sql,array($selectedContent[0]['updated_on'], $contentId));
			}
    	}
    	
    	$data['status'] = 'deleted';
    	$this->dbHandle->where("comment_id",$commentId);
	if($sectionId >0)
	{
		$this->dbHandle->where("section_id",$sectionId);
	}
    	$this->dbHandle->update('sa_comment_details',$data);
    	 
    }
    public function updateComment($commentId,$userId,$userName,$email,$contentId,$commentType){
        $this->initiateModel('write');
        $this->dbHandle->set('user_id',$userId);
        $this->dbHandle->set('user_name',$userName);
        $this->dbHandle->set('email_id',$email);
        $this->dbHandle->set('status','live');
        $this->dbHandle->where('id',$commentId);
        $this->dbHandle->update('sa_comment_details');
        if($commentType=="comment"){
			$selectedContent = $this->_getUpdatedOn($contentId);
			if(count($selectedContent)>0){
				$sql = "update sa_content set comment_count = comment_count + 1 , updated_on=? where content_id = ? and status = 'live'";
				$query = $this->dbHandle->query($sql,array($selectedContent[0]['updated_on'],$contentId));
			}
        }
    }

    public function updateQuestion($questionId,$userId){
        $this->initiateModel('write');
        $this->dbHandle->set('user_id',$userId);
        $this->dbHandle->set('status','live');
        $this->dbHandle->where('id',$questionId);
        $this->dbHandle->update('sa_user_question');        
    }
    /*
     * NOTE: sectionId has been added for the case of exam page 
     */
    public function getComments($contentId,$commentType,$commentIds = array() , $pageNo = 0, $sectionId = 0,$pageSize = 50) {
    	
        $this->initiateModel('read');
    	
    	$returnArray = array();
    	
    	$commentParentQuery = '';
    	$commentsOrder = '';
    	$commetsLimitQuery = '';
        $queryArray = array();
    	$queryArray[] = $contentId;
        
    	if($commentType == 'comment'){
    		$commentParentQuery =  " and parent_id = 0 " ;
    		$commentsOrder = " order by comment_time DESC"; 
                
                if ($sectionId > 0) {
                    $queryArray[] = $sectionId;
                }
                
    		$commetsLimitQuery = " limit ?, ?";
                $queryArray[] = $pageNo;
                $queryArray[] = $pageSize;
    	}else {
                $commentParentQuery = " and parent_id IN (?) ";

                $queryArray[] = $commentIds;
                if ($sectionId > 0) {
                    $queryArray[] = $sectionId;
                }
                $commentsOrder = " order by comment_time ASC ";
    	}
    	// add condition for sectionId as well (in case of exam page)
        $commentParentQuery .= ($sectionId>0?" and section_id = ? ":"");
        $sql = '';
    	if($commentType == 'reply')
        {
            $sql .= 'select ';

        }
        else
        {
            $sql .= 'select SQL_CALC_FOUND_ROWS ';
		}
		$sql .= " id, 
				comment_id as commentId,
				comment_path as commentPath,
				parent_id as parentId,
				content_id as contentId,
				user_id as userId,
				email_id as emailId,
				user_name as userName,
				comment_time as commentTime,
				comment_text as commentText,
				section_id as sectionId,
				status,
				source,
				tracking_key_id as tracking_keyid,
				visitor_session_id as visitorSessionid ";
    	$sql .= " from  sa_comment_details where content_id = ? ".
				$commentParentQuery .   
    			" and status = 'live'".
    			$commentsOrder.$commetsLimitQuery;
		$query = $this->dbHandle->query($sql, $queryArray);
		$commentResults = $query->result_array();

        if($commentType == 'reply')
        {
            $totalRows = count($commentResults);
        }
        else
        {
            $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
            $queryTotal = $this->dbHandle->query($queryCmdTotal);
            $queryResults = $queryTotal->result();
            $totalRows = $queryResults[0]->totalRows;
        }
    	$returnArray['data'] = $commentResults;
    	$returnArray['total'] = $totalRows;
    	
    	return $returnArray;
    	
	}
	/*
	 * 
	 */
	public function getUserInfoForCommentSection($userIds = array())
	{
        if(count($userIds) == 0)
        {
            return array();
        }
        $this->initiateModel('read');
        $this->dbHandle->select('tu.userid,tu.firstname,tu.lastname,tu.displayname,tu.avtarimageurl,tup.DesiredCourse as desiredCourseId',false);
        $this->dbHandle->from('tuser tu');
        $this->dbHandle->join('tUserPref tup','tu.userid = tup.UserId',inner);
        $this->dbHandle->where_in('tu.userid',$userIds);
        $this->dbHandle->group_by('tup.Userid');
        $returnArr['results'] = $this->dbHandle->get()->result_array();
        $returnArr['prefs'] = array();
        foreach ($returnArr['results'] as $row)
        {
            if($row['desiredCourseId']>=1508) { // intentionally left this check as it assure SA users
                $returnArr['prefs'][$row['userid']] = $row['desiredCourseId'];
            }
        }
//		$prefIds = array_map(function($a){ return $a['PrefId']; },$returnArr['results']);
//		if(count($prefIds)>0)
//		{
//			$this->dbHandle->select('UserId,DesiredCourse as desiredCourseId',false);
//			$this->dbHandle->from('tUserPref tup');
//			$this->dbHandle->where_in('PrefId',$prefIds);
//			$prefs = $this->dbHandle->get()->result_array();
//			foreach($prefs as $row)
//			{
//				if($row['desiredCourseId']>=1508){
//					$returnArr['prefs'][$row['UserId']] = $row['desiredCourseId'];
//				}
//			}
//		}
        return $returnArr;
	}
	/*
	* Author :Pragya
	* Purpose: save the feedback rating for guide
	* Params : data array (userId, feebackId, rating)
	*/
	
	function saveFeedback($data){
		$this->initiateModel('write');
		//Sanitize input
		$insertData = array();
		$insertData['session_id'] = sessionId();
		$insertData['user_id'] = $data['userId'];
		$insertData['rating'] = $data['rating'];
		$insertData['content_id'] = $data['contentId'];
		$insertData['type'] = $data['type'];
		$insertData['source'] = $data['source'];
		if(!isset($data['userId'])){
			$insertData['user_id'] = 0;
		}
		if(!isset($data['feedbackId'])){
			$queryCmd = $this->dbHandle->insert('sa_content_feedback',$insertData);
			$feedbackId = $this->dbHandle->insert_id();
		}
	    else{
			$this->dbHandle->where('feedback_id',$data['feedbackId']);
			$this->dbHandle->update('sa_content_feedback', $insertData);
		}
		$ratingData = $this->getRating($data['contentId']);
		if($feedbackId!='')
                  return $feedbackId.'::::'.$ratingData['totalLikesAnsDisLikes'].'::::'.$ratingData['totalLikes'];
		else
		  return 1;
	}
	
	/*
	* Author :Pragya
	* Purpose :Get author name and email id for mailing feedback
	* Params :  data array (contentId, rating)
	*/
	
	function getAuthorInfo($data){
		$this->initiateModel();
		$contentId = $data['contentId'];
		$sql =  "select t.firstname as firstname,
		        t.lastname as lastname,
				t.email as email
				from sa_content sac INNER JOIN tuser t
				on t.userId = sac.created_by
				where sac.status = 'live'
				and sac.content_id = ? ";
				
		$query = $this->dbHandle->query($sql,array($contentId));
		$contentResults = $query->result_array();
        return $contentResults;
		
	}
	
	function getRating($contentId){
		$this->initiateModel('read');
		$sql = "select rating, count(*) as num from sa_content_feedback where content_id = ? group by rating";
		$query = $this->dbHandle->query($sql,array($contentId));
                $results = $query->result_array();
		$likes = 0;
		$dislikes = 0;
		foreach($results as $row){
			if($row['rating'] == 0){
				$dislikes = $row['num'];
			}else{
				$likes = $row['num'];
			}
		}
		$data['totalLikesAnsDisLikes'] = $likes + $dislikes;
		$data['totalLikes'] = $likes;
		return $data;
	}

	private function _getResultWhenAllConditionsTrue($contentId, $maxCount){
		$this->initiateModel('read');
		$returnArray = array();
		$allConditionsTrueresults = array();
		//Here, put a check for Desired Course
		$sql = "select
					distinct sbcctm.tag_id,
					sbccm.attribute_id as country_id,
					sacccom.attribute_id as ldb_course_id
				from
					sa_content_tags_mapping sbcctm
				join sa_content_attribute_mapping sbccm on
					(sbcctm.content_id = sbccm.content_id and sbccm.attribute_mapping='country')
				join sa_content_attribute_mapping sacccom on
					(sbcctm.content_id = sacccom.content_id and sbccm.attribute_mapping='ldbcourse')
				where
					sbcctm.content_id = ?
					and sbcctm.status = 'live'
					and sbccm.status = 'live'
					and sacccom.status = 'live'";
		$query = $this->dbHandle->query($sql,array($contentId));
		$results = $query->result_array();
		if(!empty($results)){
			$contentIds = '';
			$contentIdsCount = 0;
			$tagIds = array();
			$countryIds = array();
			$ldbCourseIds = array();
			foreach($results as $key=>$value){
				$tagIds[] = $value['tag_id'];
				$countryIds[] = $value['country_id'];
				$ldbCourseIds[] = $value['ldb_course_id'];
			}
			$sql = "
			select
				distinct sac.content_url as contentURL,
				sac.content_image_url as contentImageURL,
				sac.view_count as viewCount,
				sac.comment_count as commentCount,
				sac.title,
				sac.content_id as contentId
			from
				sa_content sac
			inner join sa_content_tags_mapping sbcctm
				on sbcctm.status = 'live' and sbcctm.content_id = sac.content_id and sbcctm.tag_id in ('".implode("','",$tagIds)."')
			inner join sa_content_attribute_mapping sbccm
				on sbccm.status = 'live' and sbccm.attribute_mapping='country' and sbccm.content_id = sac.content_id and sbccm.attribute_id in ('".implode("','",$countryIds)."')
			inner join sa_content_attribute_mapping sacccom
				on sacccom.status = 'live' and sacccom.attribute_mapping='ldbcourse' and sacccom.content_id = sac.content_id and sacccom.attribute_id in ('".implode("','",$ldbCourseIds)."')
			where
				sac.status='live'
				and sac.content_id!= ?
			order by sac.view_count desc limit $maxCount";
			$query = $this->dbHandle->query($sql,array($contentId));
			$allConditionsTrueresults = $query->result_array();
			for($i=0;$i<count($allConditionsTrueresults);$i++){
				$allConditionsTrueresults[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$allConditionsTrueresults[$i]['contentURL'];
				$allConditionsTrueresults[$i]['contentImageURL'] = MEDIAHOSTURL.$allConditionsTrueresults[$i]['contentImageURL'];
				if($contentIdsCount>0){
					$contentIds .= ','.$allConditionsTrueresults[$i]['contentId'];
				}else{
					$contentIds .= $allConditionsTrueresults[$i]['contentId'];
				}
				$contentIdsCount++;
			}
			$contentIds .= ','.$contentId;
			
			$returnArray['contentIds'] = ltrim($contentIds,',');
			$returnArray['allConditionsTrueresults'] = $allConditionsTrueresults;
			return $returnArray;
		}else{	//If desired course is not available, check the Level + Parent Cat + Sub Cat
			$sql = "select
						distinct sbcctm.tag_id,
						sbccm.attribute_id as country_id,
						sacccom.course_type,
						sacccom.parent_category_id,
						sacccom.subcategory_id
					from
						sa_content_tags_mapping sbcctm
					join sa_content_attribute_mapping sbccm on
						(sbcctm.content_id = sbccm.content_id and sbccm.attribute_mapping='country')
					join sa_content_course_mapping sacccom on
						(sbcctm.content_id = sacccom.content_id)
					where
						sbcctm.content_id = ?
						and sbcctm.status = 'live'
						and sbccm.status = 'live'
						and sacccom.status = 'live'";
			$query = $this->dbHandle->query($sql,array($contentId));
			$results = $query->result_array();
			if(!empty($results)){
				$contentIds = '';$contentIdsCount = 0;
				$tagIds = array();
				$countryIds = array();
				$courseTypes = array();
				foreach($results as $key=>$value){
					$tagIds = $value['tag_id'];
					$countryIds = $value['country_id'];
					$courseTypes = $value['course_type'].$value['parent_category_id'];
				}
				$sql = "
				select
					distinct sac.content_url as contentURL,
					sac.content_image_url as contentImageURL,
					sac.view_count as viewCount,
					sac.comment_count as commentCount,
					sac.title,
					sac.content_id as contentId
				from
					sa_content sac
				inner join sa_content_tags_mapping sbcctm
					on sbcctm.status='live' and sbcctm.content_id = sac.content_id and sbcctm.tag_id in ('".implode("','",$tagIds)."')
				inner join sa_content_attribute_mapping sbccm
					on sbccm.status = 'live' and sbccm.attribute_mapping='country' and sbccm.content_id = sac.content_id and sbccm.attribute_id in ('".implode("','",$countryIds)."')
				inner join sa_content_course_mapping sacccom
					on sacccom.status = 'live' and sacccom.content_id = sac.content_id
					and concat(sacccom.course_type,sacccom.parent_category_id) in ('".implode("','",$courseTypes)."')
				where
					sac.status='live' and
					sac.content_id!= ?
				order by
					sac.view_count desc limit $maxCount";
				$query = $this->dbHandle->query($sql,array($contentId));
				$allConditionsTrueresults = $query->result_array();
				for($i=0;$i<count($allConditionsTrueresults);$i++)
				{
					$allConditionsTrueresults[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$allConditionsTrueresults[$i]['contentURL'];
					$allConditionsTrueresults[$i]['contentImageURL'] = MEDIAHOSTURL.$allConditionsTrueresults[$i]['contentImageURL'];
					if($contentIdsCount>0){
						$contentIds .= ','.$allConditionsTrueresults[$i]['contentId'];
					}else{
						$contentIds .= $allConditionsTrueresults[$i]['contentId'];
					}
					$contentIdsCount++;
				}
				$contentIds .= ','.$contentId;
				
				$returnArray['contentIds'] = ltrim($contentIds,',');
				$returnArray['allConditionsTrueresults'] = $allConditionsTrueresults;
				return $returnArray;
			}else{
				$returnArray['contentIds'] = $contentId;
				$returnArray['allConditionsTrueresults'] = '';
				return $returnArray;
			}
		}
	}
	
	private function _getResultWhenCourseCountryConditionsTrue($contentId, $maxCount, $contentIds){
		$this->initiateModel('read');
		$returnArray = array();$courseCountryTrueresults = array();
		
		//Here check for Desired Course
		$sql = "select distinct sbccm.attribute_id as country_id, sacccom.attribute_id as ldb_course_id from  sa_content_attribute_mapping sbccm join sa_content_attribute_mapping sacccom on (sbccm.content_id=sacccom.content_id and sbccm.attribute_mapping='ldbcourse')
where sbccm.content_id = ? and sbccm.attribute_mapping='country' and sbccm.status='live' and sacccom.status='live'";
		$query = $this->dbHandle->query($sql,array($contentId));
		$results = $query->result_array();
		$totalResultCount = count($results);
		if(!empty($results)){
			$countryIds = array();
			$ldbCourseIds = array();
			foreach($results as $key=>$value){
				$countryIds[] = $value['country_id'];
				$ldbCourseIds[] = $value['ldb_course_id'];
			}
			
			$contentIdSubQuery = '';
			if($contentIds!=''){
				$contentIdSubQuery = " and sbccm.content_id not in ($contentIds)";
			}
			$sql = "
			select
				distinct sac.content_url as contentURL,
				sac.content_image_url as contentImageURL,
				sac.view_count as viewCount,
				sac.comment_count as commentCount,
				sac.title,
				sac.content_id as contentId
			from
				sa_content sac
			inner join sa_content_attribute_mapping sbccm
				on sbccm.status = 'live' and sbccm.attribute_mapping = 'country' and sbccm.content_id = sac.content_id and sbccm.attribute_id in ('".implode("','",$countryIds)."')
			inner join sa_content_attribute_mapping sacccom
				on sacccom.status = 'live' and sbccm.attribute_mapping = 'ldbcourse' and sacccom.content_id = sac.content_id and sacccom.attribute_id in ('".implode("','",$ldbCourseIds)."')
			where
				sac.status='live'
				$contentIdSubQuery
			order by
				sac.view_count desc limit $maxCount";
			$query = $this->dbHandle->query($sql);
			$courseCountryTrueresults = $query->result_array();
			$contentIdsCount = 0;
			if($contentIds!='' && !empty($courseCountryTrueresults)){
				$contentIds .= ',';
			}
			for($i=0;$i<count($courseCountryTrueresults);$i++)
			{
				$courseCountryTrueresults[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$courseCountryTrueresults[$i]['contentURL'];
				$courseCountryTrueresults[$i]['contentImageURL'] = MEDIAHOSTURL.$courseCountryTrueresults[$i]['contentImageURL'];
				if($contentIdsCount>0){
					$contentIds .= ','.$courseCountryTrueresults[$i]['contentId'];
				}else{
					$contentIds .= $courseCountryTrueresults[$i]['contentId'];
				}
				$contentIdsCount++;
			}
			$contentIds .= ','.$contentId;
			
			$returnArray['contentIds'] = ltrim($contentIds,',');
			$returnArray['courseCountryTrueresults'] = $courseCountryTrueresults;
			return $returnArray;
		}
		else{	//Now, check for the Level + Parent Cat + Sub Cat
			$sql = "select distinct sbccm.attribute_id as country_id, sacccom.course_type, sacccom.parent_category_id, sacccom.subcategory_id from  sa_content_attribute_mapping sbccm join  sa_content_course_mapping sacccom on (sbccm.content_id=sacccom.content_id)
	where sbccm.content_id = ? and sbccm.attribute_mapping='country' and sbccm.status='live' and sacccom.status='live'";
			$query = $this->dbHandle->query($sql,array($contentId));
			$results = $query->result_array();
			$totalResultCount = count($results);
			if(!empty($results)){
				$subQuery = '';$count=0;
				foreach($results as $key=>$value){
					if($count>0){
						$subQuery .= " OR (sbccm.attribute_id = '".$value['country_id']."'  and sacccom.course_type = '".$value['course_type']."'   and sacccom.parent_category_id = '".$value['parent_category_id']."')";
					}else{
						$subQuery .= '(';
						$subQuery .= "(sbccm.attribute_id= '".$value['country_id']."'  and sacccom.course_type = '".$value['course_type']."'   and sacccom.parent_category_id = '".$value['parent_category_id']."')";
					}
	
					$count++;
					if($totalResultCount==$count){
						$subQuery .= ') and ';
					}
				}
				
				$contentIdSubQuery = '';
				if($contentIds!=''){
					$contentIdSubQuery = " and sbccm.content_id not in ($contentIds)";
				}
				$sql = "select distinct sac.content_url as contentURL, sac.content_image_url as contentImageURL, sac.view_count as viewCount, sac.comment_count as commentCount, sac.title, sac.content_id as contentId from  sa_content sac where sac.content_id in (select distinct sbccm.content_id from  sa_content_attribute_mapping sbccm join  sa_content_course_mapping sacccom on (sbccm.content_id=sacccom.content_id) where $subQuery sbccm.status='live' and sbccm.attribute_mapping='country' and sacccom.status='live' $contentIdSubQuery) and sac.status='live' order by sac.view_count desc limit $maxCount";
				$query = $this->dbHandle->query($sql);
				$courseCountryTrueresults = $query->result_array();
				$contentIdsCount = 0;
				if($contentIds!='' && !empty($courseCountryTrueresults)){
					$contentIds .= ',';
				}
				for($i=0;$i<count($courseCountryTrueresults);$i++)
				{
					$courseCountryTrueresults[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$courseCountryTrueresults[$i]['contentURL'];
					$courseCountryTrueresults[$i]['contentImageURL'] = MEDIAHOSTURL.$courseCountryTrueresults[$i]['contentImageURL'];
					if($contentIdsCount>0){
						$contentIds .= ','.$courseCountryTrueresults[$i]['contentId'];
					}else{
						$contentIds .= $courseCountryTrueresults[$i]['contentId'];
					}
					$contentIdsCount++;
				}
				$contentIds .= ','.$contentId;
				
				$returnArray['contentIds'] = ltrim($contentIds,',');
				$returnArray['courseCountryTrueresults'] = $courseCountryTrueresults;
				return $returnArray;
			}else{
				$returnArray['contentIds'] = $contentIds;
				$returnArray['courseCountryTrueresults'] = '';
				return $returnArray;
			}
		}
	}
	
	private function _getResultWhenCourseTagConditionsTrue($contentId, $maxCount, $contentIds){
		$this->initiateModel('read');
		$returnArray = array();$courseTagTrueresults = array();
		
		//Here check for Desired Course
		$sql = "select distinct sbcctm.tag_id, sacccom.attribute_id as ldb_course_id from  sa_content_tags_mapping sbcctm join  sa_content_attribute_mapping sacccom on (sbcctm.content_id=sacccom.content_id and sacccom.attribute_mapping='ldbcourse')
where sbcctm.content_id = ? and sbcctm.status='live' and sacccom.status='live'";
		$query = $this->dbHandle->query($sql,array($contentId));
		$results = $query->result_array();
		$totalResultCount = count($results);
		if(!empty($results)){
			$subQuery = '';$count=0;
			$contentIdSubQuery = '';
			if($contentIds!=''){
				$contentIdSubQuery = " and sbcctm.content_id not in ($contentIds)";
			}
			$tagIds = array();
			$ldbCourseIds = array();
			foreach($results as $key=>$value){
				$tagIds[] = $value['tag_id'];
				$ldbCourseIds[] = $value['ldb_course_id'];
			}
			
			$sql = "
			select
				distinct sac.content_url as contentURL,
				sac.content_image_url as contentImageURL,
				sac.view_count as viewCount,
				sac.comment_count as commentCount,
				sac.title,
				sac.content_id as contentId
			from
				sa_content sac
			inner join sa_content_tags_mapping sbcctm
				on sbcctm.content_id = sac.content_id and sbcctm.status = 'live' and sbcctm.tag_id in ('".implode("','",$tagIds)."')
			inner join sa_content_attribute_mapping sacccom
				on sacccom.status = 'live' and sacccom.attribute_mapping='ldbcourse' and sacccom.content_id = sac.content_id and sacccom.attribute_id in ('".implode("','",$ldbCourseIds)."')
			where
				sac.status='live'
				$contentIdSubQuery
			order by
				sac.view_count desc limit $maxCount";
				
			$query = $this->dbHandle->query($sql);
			$courseTagTrueresults = $query->result_array();
			$contentIdsCount = 0;
			if($contentIds!='' && !empty($courseTagTrueresults)){
				$contentIds .= ',';
			}
			for($i=0;$i<count($courseTagTrueresults);$i++)
			{
				$courseTagTrueresults[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$courseTagTrueresults[$i]['contentURL'];
				$courseTagTrueresults[$i]['contentImageURL'] = MEDIAHOSTURL.$courseTagTrueresults[$i]['contentImageURL'];
				if($contentIdsCount>0){
					$contentIds .= ','.$courseTagTrueresults[$i]['contentId'];
				}else{
					$contentIds .= $courseTagTrueresults[$i]['contentId'];
				}
				$contentIdsCount++;
			}
			$contentIds .= ','.$contentId;
			$returnArray['contentIds'] = ltrim($contentIds,',');
			$returnArray['courseTagTrueresults'] = $courseTagTrueresults;
			return $returnArray;
		}
		else{	//Now, check for the Level + Parent Cat + Sub Cat
			$sql = "select distinct sbcctm.tag_id, sacccom.course_type, sacccom.parent_category_id, sacccom.subcategory_id from  sa_content_tags_mapping sbcctm join  sa_content_course_mapping sacccom on (sbcctm.content_id=sacccom.content_id)
	where sbcctm.content_id = ? and sbcctm.status='live' and sacccom.status='live'";
			$query = $this->dbHandle->query($sql,array($contentId));
			$results = $query->result_array();
			$totalResultCount = count($results);
			if(!empty($results)){
				$tagIds = array();
				$courseTypes[] = array();
				foreach($results as $key=>$value){
					$tagIds[] = $value['tag_id'];
					$courseTypes[] = $value['course_type'].$value['parent_category_id'];
				}
				$contentIdSubQuery = '';
				if($contentIds!=''){
					$contentIdSubQuery = " and sbcctm.content_id not in ($contentIds)";
				}
				
				$sql = "
				select
					distinct sac.content_url as contentURL,
					sac.content_image_url as contentImageURL,
					sac.view_count as viewCount,
					sac.comment_count as commentCount,
					sac.title,
					sac.content_id as contentId
				from
					sa_content sac
				inner join sa_content_tags_mapping sbcctm
					on sbcctm.status = 'live' and sbcctm.content_id = sac.content_id and sbcctm.tag_id in ('".implode("','",$tagIds)."')
				inner join sa_content_course_mapping sacccom
					on sacccom.status = 'live' and sacccom.content_id = sac.content_id and concat(sacccom.course_type,sacccom.parent_category_id) in ('".implode("','",$courseTypes)."')
				where
					sac.status='live'
					$contentIdSubQuery
				order by
					sac.view_count desc limit $maxCount";
				$query = $this->dbHandle->query($sql);
				$courseTagTrueresults = $query->result_array();
				$contentIdsCount = 0;
				if($contentIds!='' && !empty($courseTagTrueresults)){
					$contentIds .= ',';
				}
				for($i=0;$i<count($courseTagTrueresults);$i++)
				{
					$courseTagTrueresults[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$courseTagTrueresults[$i]['contentURL'];
					$courseTagTrueresults[$i]['contentImageURL'] = MEDIAHOSTURL.$courseTagTrueresults[$i]['contentImageURL'];
					if($contentIdsCount>0){
						$contentIds .= ','.$courseTagTrueresults[$i]['contentId'];
					}else{
						$contentIds .= $courseTagTrueresults[$i]['contentId'];
					}
					$contentIdsCount++;
				}
				$contentIds .= ','.$contentId;
				$returnArray['contentIds'] = ltrim($contentIds,',');
				$returnArray['courseTagTrueresults'] = $courseTagTrueresults;
				return $returnArray;
			}else{
				$returnArray['contentIds'] = $contentIds;
				$returnArray['courseTagTrueresults'] = '';
				return $returnArray;
			}
		}
	}
	
	private function _getResultWhenCountryTagConditionsTrue($contentId, $maxCount, $contentIds){
		$this->initiateModel('read');
		$returnArray = array();$countryTagTrueresults = array();
		$sql = "select distinct sbcctm.tag_id, sbccm.attribute_id as country_id from  sa_content_tags_mapping sbcctm join sa_content_attribute_mapping sbccm on (sbcctm.content_id=sbccm.content_id and sbccm.attribute_mapping='country')
				where sbcctm.content_id = ? and sbcctm.status='live' and sbccm.status='live'";
		$query = $this->dbHandle->query($sql,array($contentId));
		$results = $query->result_array();
		$totalResultCount = count($results);
		if(!empty($results)){
			$tagIds = array();
			$countryIds = array();
			foreach($results as $key=>$value){
				$tagIds[] = $value['tag_id'];
				$countryIds[] = $value['country_id'];
			}
			$contentIdSubQuery = '';
			if($contentIds!=''){
				$contentIdSubQuery = " and sbcctm.content_id not in ($contentIds)";
			}
			$sql = "
			select
				distinct sac.content_url as contentURL,
				sac.content_image_url as contentImageURL,
				sac.view_count as viewCount,
				sac.comment_count as commentCount,
				sac.title,
				sac.content_id as contentId
			from
				sa_content sac
			inner join sa_content_tags_mapping sbcctm
				on sbcctm.status = 'live' and sbcctm.content_id = sac.content_id and sbcctm.tag_id in ('".implode("','",$tagIds)."')
			inner join sa_content_attribute_mapping sbccm
				on sbccm.status = 'live' and sbccm.attribute_mapping='country' and sbccm.content_id = sac.content_id and sbccm.attribute_id in ('".implode("','",$countryIds)."')
			where
				sac.status='live'
				$contentIdSubQuery
			order by
				sac.view_count desc limit $maxCount";
			$query = $this->dbHandle->query($sql);
			$countryTagTrueresults = $query->result_array();
			$contentIdsCount = 0;
			if($contentIds!='' && !empty($countryTagTrueresults)){
				$contentIds .= ',';
			}					
			for($i=0;$i<count($countryTagTrueresults);$i++)
			{
				$countryTagTrueresults[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$countryTagTrueresults[$i]['contentURL'];
				$countryTagTrueresults[$i]['contentImageURL'] = MEDIAHOSTURL.$countryTagTrueresults[$i]['contentImageURL'];
				if($contentIdsCount>0){
					$contentIds .= ','.$countryTagTrueresults[$i]['contentId'];
				}else{
					$contentIds .= $countryTagTrueresults[$i]['contentId'];
				}
				$contentIdsCount++;
			}
			$returnArray['contentIds'] = ltrim($contentIds,',');
			$returnArray['countryTagTrueresults'] = $countryTagTrueresults;
			return $returnArray;
		}else{
			$returnArray['contentIds'] = $contentIds;
			$returnArray['countryTagTrueresults'] = '';
			return $returnArray;
		}
		
	}
	
	private function _getResultWhenCourseConditionsTrue($contentId, $maxCount, $contentIds){
		$this->initiateModel('read');
		$returnArray = array();$courseTrueresults = array();
		//Here check for Desired Course
		$sql = "select distinct sacccom.attribute_id as ldb_course_id from sa_content_attribute_mapping sacccom where sacccom.content_id = ? and sacccom.attribute_mapping='ldbcourse' and sacccom.status='live'";
		$query = $this->dbHandle->query($sql,array($contentId));
		$results = $query->result_array();
		$totalResultCount = count($results);
		if(!empty($results)){
			$ldbCourseIds = array();
			foreach($results as $key=>$value){
				$ldbCourseIds[] = $value['ldb_course_id'];
			}
			$contentIdSubQuery = '';
			if($contentIds!=''){
				$contentIdSubQuery = " and sacccom.content_id not in ($contentIds)";
			}
			$sql = "
			select
				distinct sac.content_url as contentURL,
				sac.content_image_url as contentImageURL,
				sac.view_count as viewCount,
				sac.comment_count as commentCount,
				sac.title,
				sac.content_id as contentId
			from
				sa_content sac
			inner join sa_content_attribute_mapping sacccom
				on sacccom.status = 'live' and sacccom.attribute_mapping='ldbcourse' and sacccom.content_id = sac.content_id and sacccom.attribute_id in ('".implode("','",$ldbCourseIds)."')
			where
				sac.status='live'
				$contentIdSubQuery
			order by
				sac.view_count desc limit $maxCount";
			$query = $this->dbHandle->query($sql);
			$courseTrueresults = $query->result_array();
			$contentIdsCount = 0;
			if($contentIds!='' && !empty($courseTrueresults)){
				$contentIds .= ',';
			}
			for($i=0;$i<count($courseTrueresults);$i++)
			{
				$courseTrueresults[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$courseTrueresults[$i]['contentURL'];
				$courseTrueresults[$i]['contentImageURL'] = MEDIAHOSTURL.$courseTrueresults[$i]['contentImageURL'];
				if($contentIdsCount>0){
					$contentIds .= ','.$courseTrueresults[$i]['contentId'];
				}else{
					$contentIds .= $courseTrueresults[$i]['contentId'];
				}
				$contentIdsCount++;
			}
			$contentIds .= ','.$contentId;
			$returnArray['contentIds'] = ltrim($contentIds,',');
			$returnArray['courseTrueresults'] = $courseTrueresults;
			return $returnArray;
		}else{	//Now, check for the Level + Parent Cat + Sub Cat
			$sql = "select distinct sacccom.course_type, sacccom.parent_category_id, sacccom.subcategory_id from sa_content_course_mapping sacccom where sacccom.content_id = ? and sacccom.status='live'";
			$query = $this->dbHandle->query($sql,array($contentId));
			$results = $query->result_array();
			$totalResultCount = count($results);
			if(!empty($results)){
				$courseTypes = array();
				foreach($results as $key=>$value){
					$courseTypes[] = $value['course_type'].$value['parent_category_id'];
				}
				$contentIdSubQuery = '';
				if($contentIds!=''){
					$contentIdSubQuery = " and sacccom.content_id not in ($contentIds)";
				}
				$sql = "
				select
					distinct sac.content_url as contentURL,
					sac.content_image_url as contentImageURL,
					sac.view_count as viewCount,
					sac.comment_count as commentCount,
					sac.title,
					sac.content_id as contentId
				from
					sa_content sac
				inner join sa_content_course_mapping sacccom
					on sacccom.status = 'live' and sacccom.content_id = sac.content_id and concat(sacccom.course_type,sacccom.parent_category_id) in ('".implode("','",$courseTypes)."')
				where
					sac.status='live'
					$contentIdSubQuery
				order by sac.view_count desc limit $maxCount";
				$query = $this->dbHandle->query($sql);
				$courseTrueresults = $query->result_array();
				$contentIdsCount = 0;
				if($contentIds!='' && !empty($courseTrueresults)){
					$contentIds .= ',';
				}
				for($i=0;$i<count($courseTrueresults);$i++)
				{
					$courseTrueresults[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$courseTrueresults[$i]['contentURL'];
					$courseTrueresults[$i]['contentImageURL'] = MEDIAHOSTURL.$courseTrueresults[$i]['contentImageURL'];
					if($contentIdsCount>0){
						$contentIds .= ','.$courseTrueresults[$i]['contentId'];
					}else{
						$contentIds .= $courseTrueresults[$i]['contentId'];
					}
					$contentIdsCount++;
				}
				$contentIds .= ','.$contentId;
				$returnArray['contentIds'] = ltrim($contentIds,',');
				$returnArray['courseTrueresults'] = $courseTrueresults;
				return $returnArray;
			}else{
				$returnArray['contentIds'] = $contentIds;
				$returnArray['courseTrueresults'] = '';
				return $returnArray;
			}
		}
	}
	
	private function _getResultWhenCountryConditionsTrue($contentId, $maxCount, $contentIds){
		$this->initiateModel('read');
		$returnArray = array();$countryTrueresults = array();
		$sql = "select distinct sbccm.attribute_id as country_id from sa_content_attribute_mapping sbccm where sbccm.content_id = ? and sbccm.attribute_mapping='country' and sbccm.status='live'";
		$query = $this->dbHandle->query($sql,array($contentId));
		$results = $query->result_array();
		$totalResultCount = count($results);
		if(!empty($results)){
			$countryIds = array();
			foreach($results as $key=>$value){
				$countryIds[] = $value['country_id'];
			}
			$contentIdSubQuery = '';
			if($contentIds!=''){
				$contentIdSubQuery = " and sbccm.content_id not in ($contentIds)";
			}
			$sql = "
			select
				distinct sac.content_url as contentURL,
				sac.content_image_url as contentImageURL,
				sac.view_count as viewCount,
				sac.comment_count as commentCount,
				sac.title,
				sac.content_id as contentId
			from
				sa_content sac
			inner join sa_content_attribute_mapping sbccm
				on sbccm.status='live' and sbccm.attribute_mapping='country' and sbccm.content_id = sac.content_id and sbccm.attribute_id in ('".implode("','",$countryIds)."')
			where
				sac.status='live'
				$contentIdSubQuery
			order by sac.view_count desc limit $maxCount";
			$query = $this->dbHandle->query($sql);
			$countryTrueresults = $query->result_array();
			$contentIdsCount = 0;
			if($contentIds!='' && !empty($countryTrueresults)){
				$contentIds .= ',';
			}
			for($i=0;$i<count($countryTrueresults);$i++)
			{
				$countryTrueresults[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$countryTrueresults[$i]['contentURL'];
				$countryTrueresults[$i]['contentImageURL'] = MEDIAHOSTURL.$countryTrueresults[$i]['contentImageURL'];
				if($contentIdsCount>0){
					$contentIds .= ','.$countryTrueresults[$i]['contentId'];
				}else{
					$contentIds .= $countryTrueresults[$i]['contentId'];
				}
				$contentIdsCount++;
			}
			$contentIds .= ','.$contentId;
			$returnArray['contentIds'] = ltrim($contentIds,',');
			$returnArray['countryTrueresults'] = $countryTrueresults;
			return $returnArray;
		}else{
			$returnArray['contentIds'] = $contentIds;
			$returnArray['countryTrueresults'] = '';
			return $returnArray;
		}
	}
	
	private function _getResultWhenTagConditionsTrue($contentId, $maxCount, $contentIds){
		$this->initiateModel('read');
		$returnArray = array();$tagTrueresults = array();
		$sql = "select distinct sbcctm.tag_id from sa_content_tags_mapping sbcctm where sbcctm.content_id = ? and sbcctm.status='live'";
		$query = $this->dbHandle->query($sql,array($contentId));
		$results = $query->result_array();
		$totalResultCount = count($results);
		if(!empty($results)){
			$tagIds = array();
			foreach($results as $key=>$value){
				$tagIds[] = $value['tag_id'];
			}
			$contentIdSubQuery = '';
			if($contentIds!=''){
				$contentIdSubQuery = " and sbcctm.content_id not in ($contentIds)";
			}
			$sql = "
			select
				distinct sac.content_url as contentURL,
				sac.content_image_url as contentImageURL,
				sac.view_count as viewCount,
				sac.comment_count as commentCount,
				sac.title,
				sac.content_id as contentId
			from
				sa_content sac
			inner join sa_content_tags_mapping sbcctm
				on sbcctm.status = 'live' and sbcctm.content_id = sac.content_id and sbcctm.tag_id in ('".implode("','",$tagIds)."')
			where
				sac.status='live'
				$contentIdSubQuery
			order by
				sac.view_count desc limit $maxCount";
			$query = $this->dbHandle->query($sql);
			$tagTrueresults = $query->result_array();
			$contentIdsCount = 0;
			if($contentIds!='' && !empty($tagTrueresults)){
				$contentIds .= ',';
			}
			for($i=0;$i<count($tagTrueresults);$i++)
			{
				$tagTrueresults[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$tagTrueresults[$i]['contentURL'];
				$tagTrueresults[$i]['contentImageURL'] = MEDIAHOSTURL.$tagTrueresults[$i]['contentImageURL'];
				if($contentIdsCount>0){
					$contentIds .= ','.$tagTrueresults[$i]['contentId'];
				}else{
					$contentIds .= $tagTrueresults[$i]['contentId'];
				}
				$contentIdsCount++;
			}
			$contentIds .= ','.$contentId;
			$returnArray['contentIds'] = ltrim($contentIds,',');
			$returnArray['tagTrueresults'] = $tagTrueresults;
			return $returnArray;
		}else{
			$returnArray['contentIds'] = $contentIds;
			$returnArray['tagTrueresults'] = '';
			return $returnArray;
		}
	}
	
	function getRelatedGuideArticles($contentId){
		$finalArray = array();
		$maxCount = 3;
		$data = $this->_getResultWhenAllConditionsTrue($contentId, $maxCount);
		//_p($data);
		$allConditionsTrueresults  = $data['allConditionsTrueresults'];
		foreach($allConditionsTrueresults as $key=>$value){
				array_push($finalArray,$value);
		}
		$contentIds = $data['contentIds'];
		
		if(count($allConditionsTrueresults)==$maxCount){
			return $allConditionsTrueresults;
		}
		
		if(count($finalArray)<$maxCount){
			$limit = $maxCount-count($allConditionsTrueresults);
			$data = $this->_getResultWhenCourseCountryConditionsTrue($contentId, $limit , $contentIds);
			$courseCountryTrueresults  = $data['courseCountryTrueresults'];
			$contentIds = $data['contentIds'];
			foreach($courseCountryTrueresults as $key=>$value){
				array_push($finalArray,$value);
			}
			if(count($finalArray)==$maxCount){
				return $finalArray;
			}
		}
		
		if(count($finalArray)<$maxCount){
			$limit = $maxCount-count($finalArray);
			$data = $this->_getResultWhenCourseTagConditionsTrue($contentId, $limit , $contentIds);
			$courseTagTrueresults  = $data['courseTagTrueresults'];
			$contentIds = $data['contentIds'];
			foreach($courseTagTrueresults as $key=>$value){
				array_push($finalArray,$value);
			}
			if(count($finalArray)==$maxCount){
				return $finalArray;
			}
		}
		
		if(count($finalArray)<$maxCount){
			$limit = $maxCount-count($finalArray);
			$data = $this->_getResultWhenCountryTagConditionsTrue($contentId, $limit , $contentIds);
			$countryTagTrueresults  = $data['countryTagTrueresults'];
			$contentIds = $data['contentIds'];
			foreach($countryTagTrueresults as $key=>$value){
				array_push($finalArray,$value);
			}
			if(count($finalArray)==$maxCount){
				return $finalArray;
			}
		}
		
		if(count($finalArray)<$maxCount){
			$limit = $maxCount-count($finalArray);
			$data = $this->_getResultWhenCourseConditionsTrue($contentId, $limit , $contentIds);
			$courseTrueresults  = $data['courseTrueresults'];
			$contentIds = $data['contentIds'];
			foreach($courseTrueresults as $key=>$value){
				array_push($finalArray,$value);
			}
			if(count($finalArray)==$maxCount){
				return $finalArray;
			}
		}

		if(count($finalArray)<$maxCount){
			$limit = $maxCount-count($finalArray);
			$data = $this->_getResultWhenCountryConditionsTrue($contentId, $limit , $contentIds);
			$countryTrueresults  = $data['countryTrueresults'];
			$contentIds = $data['contentIds'];
			foreach($countryTrueresults as $key=>$value){
				array_push($finalArray,$value);
			}
			if(count($finalArray)==$maxCount){
				return $finalArray;
			}
		}

		if(count($finalArray)<$maxCount){
			$limit = $maxCount-count($finalArray);
			$data = $this->_getResultWhenTagConditionsTrue($contentId, $limit , $contentIds);
			$tagTrueresults  = $data['tagTrueresults'];
			$contentIds = $data['contentIds'];
			foreach($tagTrueresults as $key=>$value){
				array_push($finalArray,$value);
			}
			return $finalArray;
		}
	}
	
	
	
	public function getExamRelatedArticles($examId) {
		$this->initiateModel('read');
		$sql = "SELECT sac.id,
				sac.content_id,
				sac.type,
				sac.exam_id as exam_type,
				sac.title,
				sac.title as strip_title,
				sac.summary,
				sac.seo_title,
				sac.seo_description,
				sac.seo_keywords,
				sac.is_downloadable,
				sac.download_link,
				sac.content_image_url as contentImageURL,
				sac.content_url as contentURL,
				sac.view_count as viewCount,
				sac.comment_count as commentCount,
				sac.created_on as created,
				sac.updated_on as last_modified,
				sac.status,
				sac.created_by,
				sac.updated_by as last_modified_by,
				sac.related_date as relatedDate,
				sac.published_on as contentUpdatedAt,
				sac.popularity_count as popularityCount,
				sac.is_homepage,
				sac.apply_content_type_id 
		 FROM sa_content sac, sa_content_attribute_mapping sacem WHERE ".
			" sac.status = 'live' AND sac.type = 'article' AND sac.content_id = sacem.content_id and sacem.attribute_mapping='exam' ".
			" AND sacem.status = 'live' AND sacem.attribute_id = ? ORDER BY sac.popularityCount desc limit 20";
		$query = $this->dbHandle->query($sql,array($examId));
		$results = $query->result_array();
		return $results;
	}
	
	public function getBasicInfoOfContent($contentIdsArray, $limit = "") {
		if(!(is_array($contentIdsArray) && count($contentIdsArray))) {
			return false;
		}
		
		$this->initiateModel('read');
                $queryParamArray = array();
                $queryParamArray[] = $contentIdsArray;
                
		$sql = "SELECT sac.id,
					sac.content_id,
					sac.type,
					sac.exam_id as exam_type,
					sac.title,
					sac.title as strip_title,
					sac.summary,
					sac.seo_title,
					sac.seo_description,
					sac.seo_keywords,
					sac.is_downloadable,
					sac.download_link,
					sac.content_image_url as contentImageURL,
					sac.content_url as contentURL,
					sac.view_count as viewCount,
					sac.comment_count as commentCount,
					sac.created_on as created,
					sac.updated_on as last_modified,
					sac.status,
					sac.created_by,
					sac.updated_by as last_modified_by,
					sac.related_date as relatedDate,
					sac.published_on as contentUpdatedAt,
					sac.popularity_count as popularityCount,
					sac.is_homepage,
					sac.apply_content_type_id 
		 FROM sa_content sac WHERE content_id in (?) AND "
			." sac.status = 'live' AND sac.type in ('article', 'guide') ORDER BY popularity_count desc ";
		
                
		if($limit != "") {
			$sql .= " limit ?";
                        $queryParamArray[] = $limit;
		}
                
                $query = $this->dbHandle->query($sql, $queryParamArray);
		$results = $query->result_array();                
		return $results;
	}
	
	public function getAllContentDataToRaisePopularity() {
		$this->initiateModel('read');
		$sql = "SELECT  id, content_id, view_count as viewCount, comment_count as commentCount, created_on as created, updated_on as updated FROM sa_content WHERE status = '".ENT_SA_PRE_LIVE_STATUS."'";
		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();
		return $results;
	}
	
	public function updateContentPopularity($popularityCount) {
		if(!(is_array($popularityCount) && count($popularityCount))) {
			return false;
		}
		$this->initiateModel('write');
		foreach($popularityCount as $contentId => $popularityData) {
			$sql = "update sa_content set popularity_count = ? , updated_on = ? where content_id = ? AND status in ('live', 'draft')";
			$this->dbHandle->query($sql, array($popularityData['popularity'],$popularityData['updated'],$contentId));
			//echo "<br>".$this->dbHandle->last_query();
		}
	}
	
	public function getArticlesByPopularity($countryId, $limit = "") {
		if($countryId == "") {
			return false;
		}
		
                $queryParams = array();
                $queryParams[] = $countryId;
		
		$this->initiateModel('read');
		$sql = "SELECT cnt.content_id, cnt.title as strip_title, cnt.summary, cnt.content_image_url as contentImageURL, cnt.view_count as viewCount, cnt.comment_count as commentCount, cnt.created_on as created, ".
		" cnt.content_url as contentURL FROM sa_content cnt INNER JOIN sa_content_attribute_mapping cntmp  ON ".
		" (cnt.content_id=cntmp.content_id and cntmp.attribute_mapping='country' AND cnt.status=cntmp.status) WHERE cnt.status='live' AND ".
		" cnt.type='article' AND cntmp.attribute_id=? ORDER BY cnt.popularity_count desc ";
		
                
		if($limit != "") {
			$sql .= " limit ?";
                        $queryParams[] = $limit;
		}
                
		$query = $this->dbHandle->query($sql, $queryParams);
		$results = $query->result_array();
                                
		for($i=0;$i<count($results);$i++)
		{
			$results[$i]['strip_title'] = html_entity_decode(strip_tags($results[$i]['strip_title']),ENT_NOQUOTES, 'UTF-8');
			$results[$i]['contentImageURL'] = MEDIAHOSTURL.$results[$i]['contentImageURL'];
		}
		return $results;
	}
	
	public function getArticlesByCountryAndType($countryId,$articlesType='article', $articlesCount=0)
	{
	   $this->initiateModel('read');
	   $this->dbHandle->select('sacm.content_id,sacm.attribute_id as country_id,sac.title as strip_title,sac.summary,sac.download_link,sac.is_downloadable,sac.content_url as contentURL,sac.content_image_url as contentImageURL,sac.popularity_count as popularityCount');
	   $this->dbHandle->from('sa_content_attribute_mapping sacm');
	   $this->dbHandle->join('sa_content sac',"sacm.content_id = sac.content_id and sac.type='".$articlesType."' and sac.status='live'");
	   $this->dbHandle->where('sacm.attribute_id',$countryId);
	   $this->dbHandle->where('sacm.attribute_mapping','country');
	   $this->dbHandle->where('sacm.status','live');
	   if($articlesCount >0)
	   {
	     $this->dbHandle->limit($articlesCount); 
	   }
	   $query_res = $this->dbHandle->get()->result_array();
	   for($i=0;$i<count($query_res);$i++)
	   {
	   		$query_res[$i]['download_link'] = MEDIAHOSTURL.$query_res[$i]['download_link'];
	   		$query_res[$i]['strip_title'] = html_entity_decode(strip_tags($query_res[$i]['strip_title']),ENT_NOQUOTES, 'UTF-8');
            $query_res[$i]['contentImageURL'] = MEDIAHOSTURL.$query_res[$i]['contentImageURL'];
            $query_res[$i]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$query_res[$i]['contentURL'];
	   }
	   //echo $this->dbHandle->last_query();
	   //die;
	   return $query_res;
	}
      
      public function downloadCountForGuide($guideId = array())
      {
         if(count($guideId)>0){
	    $this->initiateModel('read');
            $this->dbHandle->select('guide_id as guideId,count(download_id) as totalDownload');
            $this->dbHandle->from('sa_guide_download_tracking');
            $this->dbHandle->where_in('guide_id',$guideId,FALSE);
            $this->dbHandle->group_by('guide_id');
            $query_res = $this->dbHandle->get()->result_array();
            //echo $this->dbHandle->last_query();
            $downloadArray = array();
            foreach($query_res as $obj)
            {
               $downloadArray[$obj['guideId']] = $obj['totalDownload'];
            }
         }
         return $downloadArray;
      }

      public function downloadCountForContent($guideId = array())
      {
         if(count($guideId)>0){
	    $this->initiateModel('read');
            $this->dbHandle->select('contentId,count(id) as totalDownload');
            $this->dbHandle->from('applyContentDownloadTracking');
            $this->dbHandle->where_in('contentId',$guideId,FALSE);
            $this->dbHandle->group_by('contentId');
            $query_res = $this->dbHandle->get()->result_array();
            //echo $this->dbHandle->last_query();
            $downloadArray = array();
            foreach($query_res as $obj)
            {
               $downloadArray[$obj['contentId']] = $obj['totalDownload'];
            }
         }
         return $downloadArray;
      }
      
    public function getRemarketingDataForContent($contentId){
        if(empty($contentId)){
          return array();
        }
        $this->initiateModel('read');
        $this->dbHandle->select("cncm.parent_category_id,cncm.subcategory_id,cnldb.attribute_id as ldb_course_id,cncon.attribute_id as country_id");
        $this->dbHandle->from("sa_content sc");
        $this->dbHandle->join("sa_content_course_mapping cncm","cncm.content_id=sc.content_id AND cncm.status='live'","left");
        $this->dbHandle->join("sa_content_attribute_mapping cnldb","cnldb.content_id=sc.content_id AND cnldb.attribute_mapping='ldbcourse' AND cnldb.status='live'","left");
        $this->dbHandle->join("sa_content_attribute_mapping cncon","cncon.content_id=sc.content_id AND cncon.attribute_mapping = 'country' AND cncon.status='live'","left");
        $this->dbHandle->where(array('sc.content_id'    => $contentId,
                                        'sc.status'     => 'live')
                                );
        $resultSet = $this->dbHandle->get()->result_array();
        //echo 'sql: '.$this->dbHandle->last_query();
        //_p($resultSet);die;
        $resultData = array();
        foreach($resultSet as $data){
            if($data['parent_category_id'] && !in_array($data['parent_category_id'], $resultData['categoryId'])){
                $resultData['categoryId'][] = $data['parent_category_id'];
            }
            if($data['subcategory_id'] && !in_array($data['subcategory_id'], $resultData['subCategoryId'])){
                $resultData['subCategoryId'][] = $data['subcategory_id'];
            }
            if($data['ldb_course_id'] && !in_array($data['ldb_course_id'], $resultData['ldbCourseId'])){
                $resultData['ldbCourseId'][] = $data['ldb_course_id'];
            }
            if($data['country_id'] && !in_array($data['country_id'], $resultData['countryId'])){
                $resultData['countryId'][] = $data['country_id'];
            }
        }
        return $resultData;
    }
	
	public function getURLForContent($contentId){
		$this->initiateModel('read');
		$this->dbHandle->select('content_url as contentURL');
		$this->dbHandle->from("sa_content");
		$this->dbHandle->where("status","live");
		$this->dbHandle->where('content_id',$contentId);
		$url = reset(reset($this->dbHandle->get()->result_array()));
		return SHIKSHA_STUDYABROAD_HOME.$url;
	}

	public function getRecommendedContents($contentId,$contentType,$noOfcontent)
    {
    	$this->initiateModel('read');
		$temp = array();
		foreach($contentType as $type)
		{
			$temp[] = $this->security->xss_clean($type);
		}
    	$contentType = implode("','", $temp);
    	
		$sql = "(select 
		    s1.primaryEntityId as recommendedIds,
			s1.cooccurenceScore,
		    sac1.content_id,
		    sac1.content_url as contentUrl,
		    sac1.title as strip_title,
			sac1.view_count as viewCount,
			sac1.comment_count as commentCount 
		from
		    studyAbroadCooccurrenceAnalysis s1 ,
		    sa_content sac1
		  
		where
		    sac1.content_id = s1.primaryEntityId and
		    s1.secondaryEntityId = ? 
		    and s1.entityType = 'content' 
		    and sac1.type in ('".$contentType."') 
		    and s1.status = 'live'
		    and sac1.status = 'live'
		)
		union 

		(select 
		    s2.secondaryEntityId as recommendedIds,
			s2.cooccurenceScore,
		    sac2.content_id,
			sac2.content_url as contentUrl ,
		    sac2.title as strip_title,
			sac2.view_count as viewCount,
			sac2.comment_count as commentCount 
		from
		    studyAbroadCooccurrenceAnalysis s2,
		    sa_content sac2
		where
		    sac2.content_id = s2.secondaryEntityId 
		    and s2.primaryEntityId = ?  
		    and s2.entityType = 'content' 
		    and sac2.type in ('".$contentType."') 
		    and s2.status = 'live' 
		    and  sac2.status = 'live') order by cooccurenceScore desc limit ?";
		
		$query = $this->dbHandle->query($sql,array($contentId,$contentId,$noOfcontent));
		$result = $query->result_array();
		for($i=0;$i<count($result);$i++)
		{
			$result[$i]['strip_title'] = html_entity_decode(strip_tags($result[$i]['strip_title']),ENT_NOQUOTES, 'UTF-8');
		}
		return $result;
    }	

    public function getExamContentDetailsByUrl($curUrl){
    	$this->initiateModel();
		$sql = "select sac.id,
				sac.content_id,
				sac.type,
				sac.exam_id as exam_type,
				sac.title,
				sac.title as strip_title,
				sac.summary,
				sac.seo_title,
				sac.seo_description,
				sac.seo_keywords,
				sac.is_downloadable,
				sac.download_link,
				sac.content_image_url as contentImageURL,
				sac.content_url as contentURL,
				sac.view_count as viewCount,
				sac.comment_count as commentCount,
				sac.created_on as created,
				sac.updated_on as last_modified,
				sac.status,
				sac.created_by,
				sac.updated_by as last_modified_by,
				sac.related_date as relatedDate,
				sac.published_on as contentUpdatedAt,
				sac.popularity_count as popularityCount,
				sac.is_homepage,
				sac.apply_content_type_id 
		 ,  group_concat(sact.tag_title separator ',') as tags from sa_content as sac left join sa_content_tags_mapping as sactm on sac.content_id = sactm.content_id
				left join sa_content_tags as sact on sact.id = sactm.tag_id  and  sactm.status = 'live' and  sact.status = 'live'
				where sac.content_url = ? and sac.type = 'examContent' and sac.status = 'live'  group by sac.content_id" ;
		$query = $this->dbHandle->query($sql,array(str_replace(SHIKSHA_STUDYABROAD_HOME, '', str_replace('http://','https://',$curUrl))));
		$contentResults = $query->result_array();
		foreach($contentResults as $key=>$value){
			$contentResults[$key]['strip_title'] = html_entity_decode(strip_tags($contentResults[$key]['strip_title']),ENT_NOQUOTES, 'UTF-8');
			$contentResults[$key]['contentImageURL'] = MEDIAHOSTURL.$contentResults[$key]['contentImageURL'];
			$contentResults[$key]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$contentResults[$key]['contentURL'];
			if(!empty($contentResults[$key]['download_link'])){
				$contentResults[$key]['download_link'] = MEDIAHOSTURL.$contentResults[$key]['download_link'];
			}
		}
		$contentId = $contentResults[0]['content_id'];
		if(empty($contentId)){
			return false;
		}

		$sql1 = "select * from sa_content_sections  where content_id = ? and status = 'live'" ;
		$query = $this->dbHandle->query($sql1,array($contentId));
		$sectionResults = $query->result_array();
		
		//fetches user details for the content written by
		$contentUserId = $contentResults[0]['created_by'];
		$sql2 = "select firstname,lastname,avtarimageurl,displayname ,email from tuser where userid = ?";		
		$query = $this->dbHandle->query($sql2,array($contentUserId));
		$userResult = $query->result_array();
		$userName = $userResult[0]['firstname']. ' ' . $userResult[0]['lastname'];

		$ldbCourseIds = array();
		$sql = "select id, content_id, attribute_id as ldb_course_id,status from sa_content_attribute_mapping where attribute_mapping = 'ldbcourse' and content_id = ? and status = ?";
		$resultArray = $this->dbHandle->query($sql,array($contentId,'live'))->result_array();
		$ldbCourses = array_map(function($ele){return $ele['ldb_course_id'];},$resultArray);
		$finalResult = array();
		if(!empty($contentResults[0])) { 
			$finalResult = $contentResults[0];
			//$finalResult['is_homepage'] = $secondaryContentResults['is_homepage']; available in contentResults
			$finalResult['description2'] = $sectionResults[1]['details'];
			$finalResult['countryId'] = $countryId;
			$finalResult['countryName'] = $countryName;
            $finalResult['guideSummary'] = $guideSummary;
            $finalResult['guideURL'] = $guideURL;
			$finalResult['username'] = $userName;
            $finalResult['author_firstname'] = $userResult[0]['firstname'];
            $finalResult['author_lastname'] = $userResult[0]['lastname'];
            $finalResult['email'] 		= $userResult[0]['email'];
			$finalResult['displayName'] = $userResult[0]['displayname'];
	        $finalResult['avatarimageurl'] = $userResult[0]['avtarimageurl'];
	        $finalResult['ldbCourses'] = $ldbCourses;
	        foreach ($sectionResults as $index => $data) {
	        	$finalResult['sections'][] = $data;
	        }
		}	
		return $finalResult;
    }


     public function getArticlesOnLDBCourses($data)
	{
		//get read DB handle
		$ldbCourseId =$data['ldbCourseId'];
		$contentId = $data['contentId'];
		$articlesCount = $data['articlesCount'];

		$this->initiateModel('read');
		$this->dbHandle->select('
								distinct(sac.content_id),
								sac.title as strip_title,
								sac.view_count as viewCount,
								sac.comment_count as commentCount,
								sac.content_url as contentURL,
								sac.content_image_url as contentImageURL',false);
		$this->dbHandle->from('sa_content sac');
		$this->dbHandle->join('sa_content_attribute_mapping salm', 'sac.content_id = salm.content_id and salm.attribute_mapping="ldbcourse" and sac.status=salm.status','inner');
		$this->dbHandle->where('sac.status','live');
		$this->dbHandle->where('salm.status','live');
		$this->dbHandle->where('sac.type','article');
		if(is_array($data['ldbCourseId']))
        {
            if(count($data['ldbCourseId']) > 1)
            {
                $this->dbHandle->where_in('salm.attribute_id',$data['ldbCourseId']);
            }
            else
            {
                $this->dbHandle->where('salm.attribute_id',$data['ldbCourseId'][0]);
            }
        }
        else
        {
            $this->dbHandle->where('salm.attribute_id',$data['ldbCourseId']);
        }
		$this->dbHandle->order_by('sac.content_id','random'); 
		$this->dbHandle->limit($articlesCount);

		$result = $this->dbHandle->get()->result_array();
		for($i=0;$i<count($result);$i++)
		{
			$result[$i]['strip_title'] = html_entity_decode(strip_tags($result[$i]['strip_title']),ENT_NOQUOTES, 'UTF-8');
		}
		// echo  $this->dbHandle->last_query();
		// die;
		return $result;
	}

	 public function getArticlesOnCountries($data)
	{
		//get read DB handle
		$ldbCourseId =$data['co'];
		$contentId = $data['contentId'];
		$articlesCount = $data['articlesCount'];

		$this->initiateModel('read');
		$this->dbHandle->select('
								distinct(sac.content_id),
								sac.title as strip_title,
								sac.view_count as viewCount,
								sac.comment_count as commentCount,
								sac.content_url as contentURL,
								sac.content_image_url as contentImageURL',false);
		$this->dbHandle->from('sa_content sac');
		$this->dbHandle->join('sa_content_attribute_mapping sacm', 'sac.content_id = sacm.content_id and sacm.attribute_mapping="country" and sac.status=sacm.status','inner');
		$this->dbHandle->where('sac.status','live');
		$this->dbHandle->where('sacm.status','live');
		$this->dbHandle->where('sac.type','article'); 
		$this->dbHandle->where_in('sacm.attribute_id',$data['countryId']);
		$this->dbHandle->order_by('sac.content_id','random'); 
		$this->dbHandle->limit($articlesCount);

		$result = $this->dbHandle->get()->result_array();
		for($i=0;$i<count($result);$i++)
		{
			$result[$i]['strip_title'] = html_entity_decode(strip_tags($result[$i]['strip_title']),ENT_NOQUOTES, 'UTF-8');
		}
		return $result;
	}   
	

	public function getRandomContentDetailsByExam($examId,$contentId){
    	if(empty($examId) || empty($contentId)){
    		return array();
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select('title as strip_title, content_image_url as contentImageURL, content_url as contentURL');
    	$this->dbHandle->from("sa_content");
    	$this->dbHandle->where("exam_id",$examId);
    	$this->dbHandle->where("content_id !=",$contentId);
    	$this->dbHandle->where("status","live");
    	$this->dbHandle->order_by("rand()",false);
    	$this->dbHandle->limit("4");
		$result = $this->dbHandle->get()->result_array();
		for($i=0;$i<count($result);$i++)
		{
			$result[$i]['strip_title'] = html_entity_decode(strip_tags($result[$i]['strip_title']),ENT_NOQUOTES, 'UTF-8');
		}
    	//echo $this->dbHandle->last_query(); die;
    	return $result;
    }

    public function checkMigratedExamContentRedirection($url){
    	if(empty($url)){
    		return false;
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select('sac.content_url as contentURL');
    	$this->dbHandle->from("sa_content_redirection_mapping sacrm");
    	$this->dbHandle->join("sa_content sac","sac.content_id = sacrm.content_id","inner");
    	$this->dbHandle->where("sac.status","live");
    	$this->dbHandle->where("sacrm.old_content_url",$url);
    	$res = reset(reset($this->dbHandle->get()->result_array()));
    	if(!empty($res)){
    		return $res;
    	}
    	return false;
    }
	
	public function getSAExamHomePageURLByExamNames($examNames){
    	if(count($examNames)<1){
			return false;
		}
		$this->initiateModel('read');
    	$this->dbHandle->select('sac.content_url as contentURL,sac.exam_id as examId,aemt.exam as examName');
    	$this->dbHandle->from("sa_content sac");
		$this->dbHandle->join("abroadListingsExamsMasterTable aemt","aemt.examId = sac.exam_id","inner"); 
    	$this->dbHandle->where("sac.status","live");
    	$this->dbHandle->where("sac.is_homepage",1);
		$this->dbHandle->where_in("aemt.exam",$examNames);
    	$res = $this->dbHandle->get()->result_array();
		if(!empty($res)){
    		return $res;
    	}
    	return false;
		
	}
	/**
	 * get parent exam content guide 
	 */
	public function getParentExamContentGuide($examId){
    	if(is_null($examId)){
			return false;
		}
		$this->initiateModel('read');
    	$this->dbHandle->select('sac.content_id,sac.download_link');
    	$this->dbHandle->from("sa_content sac");
    	$this->dbHandle->where("sac.status","live");
		$this->dbHandle->where("sac.exam_id",$examId);
    	$this->dbHandle->where("sac.is_homepage",1);
    	$res = $this->dbHandle->get()->result_array();
		if(!empty($res)){
    		return $res;
    	}
    	return false;
		
	}

	public function validateContentURL($contenturl){
		$res = array();
		if($contenturl!='')
		{
			$this->initiateModel('read');
			$this->dbHandle->select('sac.content_url as contentURL,sac.content_id');
			$this->dbHandle->from("sa_content sac");
			$this->dbHandle->where("sac.status","live");
			$this->dbHandle->where("sac.content_url",$contenturl);
			$this->dbHandle->where_in("sac.type",array('article','guide','applyContent','examContent'));
			$res = $this->dbHandle->get()->result_array();
		}
		if(count($res)==0){
    		$res['error'] = "invalid content URL";
    	}
    	return $res;
	}
        /**
         * 
         * @param type $examIDList Array
         * @return type Array
         * Get Most Popular exam articles according to exam ID List as input returns articles
         * in same order as that of exam id list provided in input
         */
        
        public function getMostPopularExamArticles($examIDList){
            if(!(is_array($examIDList) && count($examIDList)>0)){
                return;
            }
            $this->initiateModel('read');
            $this->dbHandle->_protect_identifiers = FALSE;
            $this->dbHandle->select("sac.comment_count as commentCount ,
                                     sac.view_count as viewCount, 
                                     sac.title as strip_title  ,
                                     sac.exam_id as exam_type ,
                                     sac.content_url as contentURL");
            $this->dbHandle->from("sa_content sac");
            $this->dbHandle->where("sac.type",'examContent');
            $this->dbHandle->where("sac.status",'live');
            $this->dbHandle->where_in("sac.exam_id",$examIDList);
            $this->dbHandle->order_by("FIELD (sac.exam_id,".  implode(',', $examIDList).")");
            $this->dbHandle->order_by("sac.popularity_count","desc");
			$mostPopularExamArticlesList = $this->dbHandle->get()->result_array();
			for($i=0;$i<count($mostPopularExamArticlesList);$i++)
			{
				$mostPopularExamArticlesList[$i]['strip_title'] = html_entity_decode(strip_tags($mostPopularExamArticlesList[$i]['strip_title']),ENT_NOQUOTES, 'UTF-8');
			}
            return $mostPopularExamArticlesList;
            
        }
        /*
         * gets limited content details like
         * content_id,strip_title,summary,download_link,contentImageURL
         */
        public function getContentBasicDetails($contentIds)
        {
                $this->initiateModel('read');
                $this->dbHandle->select('content_id,title as strip_title,summary,download_link,content_image_url as contentImageURL,type');
                $this->dbHandle->from('sa_content');
                $this->dbHandle->where_in('content_id',$contentIds);
                $this->dbHandle->where('status','live');
                $results = $this->dbHandle->get()->result_array();
                $res = array();
                foreach($results as $row)
                {
                        $row['contentImageURL'] = MEDIAHOSTURL.$row['contentImageURL'];
						$row['download_link'] = MEDIAHOSTURL.$row['download_link'];
						$row['strip_title'] = html_entity_decode(strip_tags($row['strip_title']),ENT_NOQUOTES, 'UTF-8');
                        $res[$row['content_id']] = $row;
                }
                return $res;
        }
                
        public function getCommentCount($contentId){
            $this->initiateModel('read');
            $this->dbHandle->select('count(*) as count');
            $this->dbHandle->from('sa_comment_details');
            $this->dbHandle->where('status','live');
            $this->dbHandle->where('content_id',$contentId);
            $this->dbHandle->where('parent_id',0);
            $resultArray = $this->dbHandle->get()->result_array();
            if(!empty($resultArray)){
                return $resultArray[0]['count'];
            }
            else{
                return 0;
            }
        }
        public function getPopularContentofSameCountry($contentId,$mentionedIds){
            $this->initiateModel('read');
            $this->dbHandle->select('saccm.content_id,sac.popularity_count as popularityCount');
            $this->dbHandle->from('sa_content_attribute_mapping saccm');
			$this->dbHandle->join('sa_content_attribute_mapping saccm2','saccm.attribute_id=saccm2.attribute_id and saccm.attribute_mapping = saccm2.attribute_mapping and saccm.status=saccm2.status
			');
            $this->dbHandle->join('sa_content sac','sac.content_id=saccm.content_id and sac.status=saccm.status');
            $this->dbHandle->where('saccm2.content_id',$contentId);
            $this->dbHandle->where('saccm.attribute_mapping','country');
            $this->dbHandle->where('saccm.status','live');
            $this->dbHandle->where('saccm2.status','live');
            $this->dbHandle->where('sac.is_downloadable','yes');
            if(!empty($mentionedIds)){
                $this->dbHandle->where_not_in('saccm.content_id',$mentionedIds);
            }
            $resultArray = $this->dbHandle->get()->result_array();
            return $resultArray;
        }
        
        public function getPopularContentofSameLDBCourse($contentId,$mentionedIds){
            $this->initiateModel('read');
            $this->dbHandle->select('sacem.content_id,sac.popularity_count as popularityCount');
            $this->dbHandle->from('sa_content_attribute_mapping sacem');
	    $this->dbHandle->join('sa_content_attribute_mapping sacem2','sacem.attribute_id=sacem2.attribute_id and sacem.status=sacem2.status and sacem.attribute_mapping=sacem2.attribute_mapping and sacem.attribute_mapping = "ldbcourse" ');
            $this->dbHandle->join('sa_content sac','sac.content_id=sacem.content_id');
            $this->dbHandle->where('sacem2.content_id',$contentId);
            $this->dbHandle->where('sac.status','live');
            $this->dbHandle->where('sacem.status','live');
            $this->dbHandle->where('sacem2.status','live');
            $this->dbHandle->where('sac.is_downloadable','yes');
            if(!empty($mentionedIds)){
                $this->dbHandle->where_not_in('sacem.content_id',$mentionedIds);
            }
	    $resultArray = $this->dbHandle->get()->result_array();
            return $resultArray;
        }
        
        
        public function getPopularContentofSameContentCourse($contentId,$mentionedIds){
            $this->initiateModel('read');
            $this->dbHandle->select('sacem.content_id,sac.popularity_count as popularityCount');
            $this->dbHandle->from('sa_content_course_mapping sacem');
            $this->dbHandle->join('sa_content_course_mapping sacem2','sacem.parent_category_id=sacem2.parent_category_id and sacem.subcategory_id = sacem2.subcategory_id and sacem.course_type = sacem2.course_type');
            $this->dbHandle->join('sa_content sac','sac.content_id=sacem.content_id');
            $this->dbHandle->where('sacem2.content_id',$contentId);
            $this->dbHandle->where('sac.status','live');
            $this->dbHandle->where('sacem.status','live');
            $this->dbHandle->where('sacem2.status','live');
            $this->dbHandle->where('sac.is_downloadable','yes');
            if(!empty($mentionedIds)){
                $this->dbHandle->where_not_in('sacem.content_id',$mentionedIds);
            }
            $resultArray = $this->dbHandle->get()->result_array();
            return $resultArray;
        }
        
        public function getContentDataForDownloadGuideWidget($contentIds){
            if(empty($contentIds)){
                return;
            }
            if(!is_array($contentIds)){
                $contentIds = array($contentIds);
            }
            $this->initiateModel('read');
            
            $sql = "select content_id,type,title as strip_title,summary,view_count as viewCount,content_url as contentURL,content_image_url as contentImageURL,download_link,view_count as viewCount,comment_count as commentCount";
            $sql.= " from sa_content where content_id in(?) and status='live' order by field(content_id, ?) ";
            $resultArray = $this->dbHandle->query($sql, array($contentIds, $contentIds))->result_array();
                       
            foreach($resultArray as $key=>$value){
                $resultArray[$key]['contentURL']        = SHIKSHA_STUDYABROAD_HOME.$resultArray[$key]['contentURL'];
                $resultArray[$key]['strip_title']        = html_entity_decode(strip_tags($resultArray[$key]['strip_title']),ENT_NOQUOTES, 'UTF-8');
                $resultArray[$key]['contentImageURL']   = MEDIAHOSTURL.$resultArray[$key]['contentImageURL'];
                $resultArray[$key]['download_link']     = MEDIAHOSTURL.$resultArray[$key]['download_link'];
            }
            return $resultArray;
        }
        
        public function getDownloadablePopularContent(){
            $this->initiateModel('read');
            $this->dbHandle->select('content_id');
            $this->dbHandle->from('sa_content');
            $this->dbHandle->where('is_downloadable','yes');
            $this->dbHandle->where('status','live');
            $this->dbHandle->order_by('popularity_count','desc');
            $this->dbHandle->limit(DOWNLOAD_GUIDE_SLIDER_WIDGET_COUNT+1);
            return $this->dbHandle->get()->result_array();
        }
        
        public function checkIfContentDownloadable($contentIds){
            if(empty($contentIds)){
                return;
            }            
            
            $sql = "select content_id from sa_content where is_downloadable='yes' and status='live' ";
            $sql.= "and content_id in (?) order by field(content_id, ?)";
            $result = $this->dbHandle->query($sql, array($contentIds, $contentIds))->result_array();
                        
            $returnArray = array();
            if(!empty($result)){
                foreach($result as $key=>$value){
                    $returnArray[] = $value['content_id'];
                }
            }
            return $returnArray;
        }

    public function getLevelTwoNavBarIdByContentId($contentId){
        $this->initiateModel('read');
        $this->dbHandle->select('cn.title,cn.navbar_id');
        $this->dbHandle->from('content_navbar_links cnl');
        $this->dbHandle->join('content_navbar cn','cnl.navbar_id = cn.navbar_id');
        $this->dbHandle->where('cnl.content_id',$contentId);
        $this->dbHandle->where('cnl.status','live');
        $this->dbHandle->where('cn.status','live');
        return $this->dbHandle->get()->result_array();
    }

    public function getLevelTwoNavBarLinksByNavBarId($navId){
        $this->initiateModel('read');
        $this->dbHandle->select('cnl.content_title as title,cnl.content_id');
        $this->dbHandle->from('content_navbar_links cnl');
        $this->dbHandle->where('cnl.navbar_id',$navId);
        $this->dbHandle->where('cnl.status','live');
        return $this->dbHandle->get()->result_array();
    }
}

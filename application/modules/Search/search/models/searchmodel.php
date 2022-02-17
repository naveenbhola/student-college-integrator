<?php
class searchmodel extends MY_Model {
	private $dbHandle = '';
	private $CI;
   
    function __construct(){
		parent::__construct('Search');
		$this->CI = &get_instance();
		$this->CI->load->helper('messageBoard/ana');
    }
	
	private function initiateModel($mode = "write"){
		$this->dbHandle = NULL;
		if($mode == 'read'){
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
	}
	
	public function getOAFormDetailsForCourse($courseId) {
		$this->initiateModel('read');
		$queryCmd = "SELECT fees AS course_aof_fee, min_qualification AS course_aof_min_qualification, last_date AS course_aof_last_date, exams_required AS course_aof_exams_accepted FROM  OF_InstituteDetails WHERE courseId=? AND status='live'";
		$data = array();
		$query = $this->dbHandle->query($queryCmd, $courseId);
		foreach($query->result() as $row) {
			$data = (array)$row;
		}
		return $data;
	}
	
	public function getOAFormExternalURL($courseId) {
		$this->initiateModel('read');
		$queryCmd = "
					SELECT
					externalURL
					FROM
					OF_InstituteDetails
					WHERE
					courseId= ?  AND
					status='live'";
		$data = NULL;
		$query = $this->dbHandle->query($queryCmd, $courseId);
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data = $row->externalURL;
			}	
		}
		return $data;
	}
	
	public function getContinentForCountry($countryId) {
		$this->initiateModel('read');
		
		$queryCmd = "SELECT C.continent_id AS course_continent_id, C.name AS course_country_name, CT.name AS course_continent_name FROM countryTable AS C, continentTable AS CT WHERE C.continent_id = CT.continent_id AND C.countryId =?";
		$data = array();
		$query = $this->dbHandle->query($queryCmd, $countryId);
		foreach($query->result() as $row) {
			$data = (array)$row;
		}
		return $data;
	}
	
	public function getListingCategory($listingId, $type){
		$this->initiateModel('read');	
		
		$queryCmd= "SELECT category_id
					FROM
					listing_category_table
					WHERE
					listing_type = ? AND
					listing_type_id = ? AND
					status = 'live'";
		$data = array();
		$query = $this->dbHandle->query($queryCmd, array($type, $listingId));
		foreach($query->result() as $row) {
			$data[] = $row->category_id;
		}
		return $data;
	}
	
	public function getParentCategoryId($id = null){
		if($id == null){
			return array();
		}
		$this->initiateModel('read');
		
		$data = array();
		$queryCmd ="SELECT t2 . * 
					FROM categoryBoardTable AS t1, categoryBoardTable AS t2
					WHERE t1.parentId = t2.boardId
					AND t1.boardId = ?";
		$query = $this->dbHandle->query($queryCmd, $id);
		$row = $query->result();
		$data = (array)$row[0];
		return $data;
	}
	
	public function getWikiDescription($id, $type){
		$this->initiateModel('read');	
		
		$queryCmd = "SELECT listing_attributes_table.* , listing_fields_table.key_name FROM listing_attributes_table LEFT JOIN listing_fields_table ON listing_attributes_table.keyId = listing_fields_table.keyId WHERE listing_type_id = ? AND listing_attributes_table.listing_type = ?";
		$data = array();
		$query = $this->dbHandle->query($queryCmd, array($id, $type));
		foreach($query->result() as $row) {
			$data[] = (array)$row;
		}
		return $data;
	}
	
	public function getQuestionDetails($id = null) {
		if($id == null){
			return array();
		}
		$this->initiateModel('read');	
		
		$data = array();
		$queryCmd ="SELECT mt . * , u.displayname AS displayname, u.avtarimageurl AS image_url, GROUP_CONCAT( mct.categoryId ) AS category_ids
				FROM messageTable AS mt, messageCategoryTable AS mct, tuser AS u
				WHERE mt.msgId = mct.threadId
				AND mt.fromOthers =  'user'
				AND mt.status IN ('live', 'closed')
				AND mt.msgId = ?
				AND mt.userId = u.userid
				GROUP BY mt.msgId 
				limit 1";
		$query = $this->dbHandle->query($queryCmd, $id);
		if($query->num_rows() > 0) {
			$row = $query->result();
			//$row[0]['msgTxt'] = sanitizeAnAMessageText($row[0]['msgTxt'],'question');
			$data = (array)$row[0];
		}
		return $data;
	}
	
	public function getQuestionDescription($id = null){
		if($id == null){
			return array();
		}
		$this->initiateModel('read');	
		
		$data = '';
		$queryCmd ="SELECT md.description FROM messageTable AS mt, messageDiscussion AS md
					WHERE mt.msgId = ?
					AND mt.fromOthers =  'user'
					AND mt.status IN ('live', 'closed')
					AND mt.msgId = md.threadId
					limit 1";
		$query = $this->dbHandle->query($queryCmd, $id);
		if($query->num_rows() > 0) {
			$row = $query->result();
			$data = $row[0]->description;
		}
		return $data;
	}
	
	public function getArticleDetails($id = null){
		if($id == null){
			return array();
		}
		$this->initiateModel('read');	
		
		$data = array();
		$queryCmd ="SELECT bt.* ,
					bd.description,	
					ct.name as country_name,
					u.displayname AS displayname,
					u.avtarimageurl AS image_url,
					bt.blogImageURL 
					FROM
					blogTable AS bt,
					countryTable AS ct,
					tuser as u,
					blogDescriptions as bd
					WHERE
					bt.blogId = ? AND
					bt.status =  'live' AND 
					bt.userId = u.userid AND 
					bt.countryId = ct.countryId AND
					bd.blogId = bt.blogId
					";
		$query = $this->dbHandle->query($queryCmd, $id);
		$result = array();
		foreach($query->result() as $row){
			$result[] = (array)$row;
		}
		return $result;
	}
	
	public function getSponsorListingFromDb($count = 10) {
		$this->initiateModel('read');	
		
		$baseCmd = "(
						SELECT sslt.id, sslt.listing_id, sslt.listing_type, sslt.parent_id, sslt.parent_type, sslt.sponsor_type, sslt.bmskey
						FROM
						search_sponsored_listings as sslt,
						listings_main
						WHERE
						sslt.search_type = 'course' AND
						sslt.sponsor_type = 'sponsored' AND
						sslt.status = 1 AND
						sslt.subscription_start_date <= now() AND
						sslt.subscription_end_date >= now() AND
						listings_main.listing_type_id = sslt.listing_id AND
						listings_main.listing_type = sslt.listing_type AND
						listings_main.status = 'live'
						ORDER BY RAND()
						LIMIT ?
					)
					UNION
					(
						SELECT sslt.id, sslt.listing_id, sslt.listing_type, sslt.parent_id, sslt.parent_type, sslt.sponsor_type, sslt.bmskey
						FROM
						search_sponsored_listings as sslt,
						listings_main
						WHERE
						sslt.search_type = 'course' AND
						sslt.sponsor_type = 'featured' AND
						sslt.status = 1 AND
						sslt.bmskey != '' AND
						sslt.subscription_start_date <= now() AND
						sslt.subscription_end_date >= now() AND
						listings_main.listing_type_id = sslt.listing_id AND
						listings_main.listing_type = sslt.listing_type AND
						listings_main.status = 'live'
						ORDER BY RAND()
						LIMIT ?
					)
					UNION
					(
						SELECT sslt.id, sslt.listing_id, sslt.listing_type, sslt.parent_id, sslt.parent_type, sslt.sponsor_type, sslt.bmskey
						FROM
						search_sponsored_listings as sslt,
						listings_main
						WHERE
						sslt.search_type = 'course' AND
						sslt.sponsor_type = 'banner' AND
						sslt.status = 1 AND
						sslt.bmskey != '' AND
						sslt.subscription_start_date <= now() AND
						sslt.subscription_end_date >= now() AND
						listings_main.listing_type_id = sslt.listing_id AND
						listings_main.listing_type = sslt.listing_type AND
						listings_main.status = 'live'
						ORDER BY RAND()
						LIMIT ?
					)
					";
					
		$query = $this->dbHandle->query($baseCmd, array($count, $count, $count));
		$data = array();
		foreach($query->result() as $row) {
			$data[] = (array)$row;
		}
		return $data;
	}
	
	public function startIndexing($operation, $id = null, $type = null){
		$rowId = null;
		if($id != null && $type != null){
			$this->initiateModel();
			
			$queryCmd = "SELECT * from indexlog WHERE operation = ? AND listing_id = ? AND listing_type = ? AND status = 'pending'";
			$query = $this->dbHandle->query($queryCmd, array($operation, $id, $type));
			$row = $query->result();
			$data = (array)$row[0];
			if(!empty($data)){
				$rowId = $data['id'];
				$queryCmd = "UPDATE indexlog SET status = 'processing', indexing_start_time = NOW() WHERE id = ?";
				$query = $this->dbHandle->query($queryCmd, $rowId);
			}
		}
		return $rowId;
	}
	
	public function finishIndexing($rowId = null, $status = null, $comment = ""){
		if($rowId != null && $status != null){
			$this->initiateModel();
			
			$queryCmd = "UPDATE indexlog SET status = ?, comment = ?, indexing_finish_time = NOW() WHERE id = ?";
			$query = $this->dbHandle->query($queryCmd, array($status, $comment, $rowId));
			if($query){
				return 1;
			}
		}
	}
	
	public function entryExistInIndexQueue($operation, $id = null, $type = null){
		$rowId = null;
		if($id != null && $type != null){
			$this->initiateModel();
			
			$queryCmd = "SELECT * from indexlog WHERE operation = ? AND listing_id = ? AND listing_type = ? AND status = 'pending'";
			$query = $this->dbHandle->query($queryCmd, array($operation, $id, $type));
			$row = $query->result();
			$data = (array)$row[0];
			if(!empty($data)){
				$rowId = $data['id'];
			}
		}
		return $rowId;
	}
	
	public function addToIndexQueue($operation, $id = null, $type = null){
		$last_insert_id = null;
		if($id != null && $type != null){
			$this->initiateModel();
			
			$queryCmd = "INSERT INTO indexlog (operation, listing_type, listing_id, status) values (?, ?, ?, 'pending') ";
			$query = $this->dbHandle->query($queryCmd, array($operation, $type, $id));
			$last_insert_id = $this->dbHandle->insert_id();
			return $last_insert_id;
		}
	}
	
	public function getIndexQueueEntries($offset = 0, $limit = 100, $startDate = ''){
		$this->initiateModel();
		$sqlData = array();
		$queryCmd ="SELECT *
					FROM
					indexlog
					WHERE 
					listing_type NOT IN ('course','institute','university','exam','question','article')
					";
		if($startDate != ''){
			$sqlData = array($startDate, $offset, $limit);
			$queryCmd.= " AND listing_added_time  >= ? ";
		}else{
			$sqlData = array($offset, $limit);
			$queryCmd.= " AND status  = 'pending' ";
		}
		$queryCmd.= " ORDER BY id asc LIMIT ?, ?";

		$query = $this->dbHandle->query($queryCmd, $sqlData);
		$data = array();
		foreach($query->result() as $row) {
			$data[] = (array)$row;
		}
		return $data;
	}
	
	public function getSearchIndexCronStatus(){
		$this->initiateModel();
		
		$queryCmd ="SELECT *
					FROM
					cron_management
					WHERE
					cron_type = 'SEARCH_INDEXING_CRON' AND
					status = 'running'
					";
		$query = $this->dbHandle->query($queryCmd);
		$data = array();
		$row = $query->result();
		$data = (array)$row[0];
		return $data;
	}

	public function getNationalSearchIndexCronStatus(){
		$this->initiateModel();
		
		$queryCmd ="SELECT *
					FROM
					cron_management
					WHERE
					cron_type = 'NATIONAL_SEARCH_INDEXING_CRON' AND
					status = 'running'
					";
		$query = $this->dbHandle->query($queryCmd);
		$data = array();
		$row = $query->result();
		$data = (array)$row[0];
		return $data;
	}
	
	public function startSearchIndexingCron($cronType = "SEARCH_INDEXING_CRON", $status = 'RUNNING', $comment = ""){
		$last_insert_id = null;
		$this->initiateModel();
		
		$processId = getmypid();
		$queryCmd ="INSERT
					INTO
					cron_management
					(pid, cron_type, status, comment)
					values
					(?, ?, ?, ?)";
		$query = $this->dbHandle->query($queryCmd, array($processId, $cronType, $status, $comment));
		$last_insert_id = $this->dbHandle->insert_id();
		return $last_insert_id;
	}
	
	public function updateSearchIndexingCronStatus($cronId = NULL, $status = NULL){
		if($cronId != null && $status != null){
			$this->initiateModel();
			
			$queryCmd ="UPDATE
						cron_management
						SET
						status = ? ,
						end_time = NOW()
						WHERE id = ?";
			$query = $this->dbHandle->query($queryCmd, array($status, $cronId));
			return $query;
		}
	}
	
	public function updateSearchIndexingCronAttempts($cronId = NULL, $newAttemptCount = NULL){
		if($cronId != null && $newAttemptCount != null){
			$this->initiateModel();
			
			$queryCmd ="UPDATE
						cron_management
						SET
						attempts = ? 
						WHERE id = ?";
			$query = $this->dbHandle->query($queryCmd, array($newAttemptCount, $cronId));
			return $query;
		}
	}
	
	
	public function getQuestionLatestAnswer($question_id = null){
		if($question_id == null){
			return '';
		}
		$this->initiateModel('read');
		
		//Get the latest answer
		$queryCmd = "SELECT MT.*,
					t.displayname,
					t.userid AS userId,
					t.avtarimageurl AS userImage
					FROM messageTable AS MT
					LEFT JOIN tuser AS t
					ON
					t.userId = MT.userId
					WHERE  MT.threadId = ?
					AND MT.mainAnswerId = 0
					AND MT.parentId != 0
					AND MT.status IN ('live', 'closed')
					ORDER BY MT.creationDate DESC
					LIMIT 1
					";
		$query = $this->dbHandle->query($queryCmd, $question_id);
		$bestAnswerDetails = array();
		if($query->num_rows() > 0) {
			$row = $query->row();
			$bestAnswerDetails['answer_user_id'] = $row->userId;
			$bestAnswerDetails['answer_user_display_name'] = $row->displayname;
			$bestAnswerDetails['answer_user_image_url'] = $row->userImage;
			$bestAnswerDetails['answer_title'] = $row->msgTxt;
			$bestAnswerDetails['answer_id'] = $row->msgId;
			$bestAnswerDetails['answer_created_time'] = $row->creationDate;
		}
		return $bestAnswerDetails;
	}
	
	public function getCourseViewCount($courseId = null) {
		if($courseId == null){
			return '';
		}
		$this->initiateModel('read');
		
		$queryCmd ="SELECT SUM(no_Of_Views) as viewCount
					FROM
					view_Count_Details
					WHERE is_Deleted = 0
					AND listing_id = ?
					AND listingType IN ('course_free', 'course_paid')";
		$query = $this->dbHandle->query($queryCmd, $courseId);
		$viewCount = 0;
		if($query->num_rows() > 0) {
			$row = $query->result();
			$data = (array)$row[0];
			$viewCount = $data['viewCount'];
		}
		return $viewCount;
	}
	
	public function getQuestionDisplayInformation($questionId = null){
		if($questionId == null){
			return '';
		}
		$this->initiateModel('read');
		
		$commentCount = -1;
		$answerCount = -1;
		$viewCount = -1;
		$bestAnswerDetails = array();
		
		/*
		$queryCmd = "SELECT count(*) as commentCount
					FROM
					messageTable MT
					WHERE
					MT.threadId = '".$questionId."' AND
					MT.fromOthers = 'user' AND
					MT.parentId != 0 AND
					MT.mainAnswerId != 0 AND
					MT.status IN ('live','closed')";
		
		$query = $this->dbHandle->query($queryCmd);
		if($query->num_rows() > 0) {
		   $row = $query->row();
		   $commentCount = 	$row->commentCount;
		}
		
		$queryCmd = "SELECT count(*) as answerCount
					FROM
					messageTable MT
					WHERE
					MT.threadId = '".$questionId."' AND
					MT.fromOthers = 'user' AND
					MT.parentId != 0 AND
					MT.mainAnswerId = 0 AND
					MT.status IN ('live','closed')";
		
		$query = $this->dbHandle->query($queryCmd);
		if($query->num_rows() > 0) {
		   $row = $query->row();
		   $answerCount = 	$row->answerCount;
		}
		
		$queryCmd = "SELECT viewCount
					FROM
					messageTable MT
					WHERE
					MT.threadId = '".$questionId."' AND
					MT.fromOthers = 'user' AND
					MT.parentId = 0 AND
					MT.mainAnswerId = -1 AND
					MT.status IN ('live','closed')";
		
		$query = $this->dbHandle->query($queryCmd);
		if($query->num_rows() > 0) {
		   $row = $query->row();
		   $viewCount = $row->viewCount;
		}
		
		$queryCmd = "SELECT * 
					FROM
					messageTableBestAnsMap
					WHERE
					threadId = '".$questionId."'";
					
		$query = $this->dbHandle->query($queryCmd);
		$bestAnswerFound = FALSE;
		if($query->num_rows() > 0) {
			$row = $query->row();
			$bestAnswerFound = TRUE;
			$bestAnswerId = $row->bestAnsId;
		}
		*/
		
		$queryCmd = "SELECT
		             sum(if(MT.parentId != 0 AND MT.mainAnswerId != 0,1,0)) as commentCount,
					 sum(if(MT.parentId != 0 AND MT.mainAnswerId = 0,1,0)) as answerCount,
					 sum(if(MT.parentId = 0 AND MT.mainAnswerId = -1,viewCount,0)) as viewCount,
					 sum(if(ifnull(MTBAM.bestAnsId,0) = MT.msgId,MT.msgId,0)) as bestAnsId
					 FROM
					 messageTable MT
					 LEFT JOIN messageTableBestAnsMap MTBAM
					 ON MT.threadId = MTBAM.threadId
					 WHERE
					 MT.threadId = ? AND
					 MT.fromOthers = 'user' AND
					 MT.status IN ('live','closed')";
					 
		$bestAnswerFound = FALSE;
		$bestAnswerDetailsFound = FALSE;
		$query = $this->dbHandle->query($queryCmd, $questionId);
		if($query->num_rows() > 0) {
			$row = $query->row();
			$commentCount 	= 	$row->commentCount;
			$answerCount 	= 	$row->answerCount;
			$viewCount 		= $row->viewCount;
			if($row->bestAnsId > 0){
				$bestAnswerFound = TRUE;
				$bestAnswerId = $row->bestAnsId;
			}
		}
		
		if($bestAnswerFound){
			//If question owner has selected any answer as best answer
			$queryCmd = "SELECT MT.*,
						t.displayname,
						t.userid AS userId,
						t.avtarimageurl AS userImage
						FROM messageTable AS MT
						LEFT JOIN
						tuser AS t
						ON
						t.userId = MT.userId
						WHERE  MT.msgId = ?
						AND MT.status IN ('live', 'closed')
						";
			$query = $this->dbHandle->query($queryCmd, $bestAnswerId);
			if($query->num_rows() > 0) {
				$bestAnswerDetailsFound = TRUE;
				global $isMobileApp;
				$row = $query->row();
				$bestAnswerDetails['answer_user_id'] = $row->userId;
				$bestAnswerDetails['answer_user_display_name'] = $row->displayname;
				$bestAnswerDetails['answer_user_image_url'] = $row->userImage;
				
				if($isMobileApp){
				     $bestAnswerDetails['answer_title'] = $row->msgTxt;
				}else{
				     $bestAnswerDetails['answer_title'] = $row->msgTxt;
				}
				$bestAnswerDetails['answer_id'] = $row->msgId;
				$bestAnswerDetails['answer_created_time'] = $row->creationDate;
			}
        }
		
		if(!$bestAnswerDetailsFound || !$bestAnswerFound) {
			//get answer that has maximum number of comments
			$queryCmd = "SELECT count(*) as count, mainAnswerId as answerid
						FROM
						messageTable
						WHERE
						threadId = ? AND
						parentId != 0 AND
						mainAnswerId != 0 AND
						mainAnswerId != -1 AND
						status IN ('live','closed')
						GROUP BY
						mainAnswerId
						ORDER BY
						count DESC, creationDate DESC
						LIMIT 1
						";
			$query = $this->dbHandle->query($queryCmd, $questionId);
			if($query->num_rows() > 0) {
				$row = $query->row();
				$bestAnswerId = $row->answerid;
				$tempCmntCnt  = $row->count;
				if($tempCmntCnt > 0){
					//Get the answer details
					$queryCmd = "SELECT MT.*,
								t.displayname,
								t.userid AS userId,
								t.avtarimageurl AS userImage
								FROM
								messageTable AS MT
								LEFT JOIN
								tuser AS t
								ON
								t.userId = MT.userId
								WHERE
								MT.status IN ('live','closed') AND 
								MT.msgId = ?";
					$query = $this->dbHandle->query($queryCmd, $bestAnswerId);
					if($query->num_rows() > 0) {
						global $isMobileApp;
						$row = $query->row();
						$bestAnswerDetails['answer_user_id'] = $row->userId;
						$bestAnswerDetails['answer_user_display_name'] = $row->displayname;
						$bestAnswerDetails['answer_user_image_url'] = $row->userImage;
						
						if($isMobileApp){
						     $bestAnswerDetails['answer_title'] = sanitizeAnAMessageText($row->msgTxt,'answer');
					        }else{
						     $bestAnswerDetails['answer_title'] = $row->msgTxt;
					        }
						$bestAnswerDetails['answer_id'] = $row->msgId;
						$bestAnswerDetails['answer_created_time'] = $row->creationDate;
						$answerBasedOnCommentCountFound = true;
					}
				}
				
				if(!$answerBasedOnCommentCountFound){
					//Get the latest answer
					$bestAnswerDetails = $this->getQuestionLatestAnswer($questionId);
				}
			} else {
				//Get the latest answer
				$bestAnswerDetails = $this->getQuestionLatestAnswer($questionId);
			}
		}
		$data = array(
					'questionId' 		=> $questionId,
					'commentCount' 		=> $commentCount,
					'answerCount' 		=> $answerCount,
					'viewCount' 		=> $viewCount,
					'bestAnswerDetails' => $bestAnswerDetails,
					);
		return $data;
	}
	
	public function getArticleDisplayInformation($articleId = null){
		if($articleId == null){
			return '';
		}
		$this->initiateModel('read');
		
		$commentCount = 0;
		$viewCount = 0;
		
		$queryCmd = "SELECT blogView as viewCount, discussionTopic as threadId 
					FROM
					blogTable BT
					where
					BT.blogId = ? AND
					BT.status IN ('live')";
		
		$query = $this->dbHandle->query($queryCmd, $articleId);
		$threadId = -1;
		if($query->num_rows() > 0) {
		   $row = $query->row();
		   $viewCount = $row->viewCount;
		   $threadId = $row->threadId;
		}
		if($threadId != -1){
			$queryCmd = "SELECT count(*) as count
						FROM
						messageTable
						WHERE
						threadId = ? AND
						parentId != 0 AND
						mainAnswerId = 0 AND
						status IN ('live')
						GROUP BY
						mainAnswerId
						ORDER BY
						count DESC
						LIMIT 1
						";
			$query = $this->dbHandle->query($queryCmd, $threadId);
			if($query->num_rows() > 0) {
				$row = $query->row();
				$commentCount = $row->count;
			}
		}
		$returnData = array(
						'commentCount' => $commentCount,
						'viewCount'	   => $viewCount
					);
		return $returnData;
	}
	
	public function getFeaturedListings($keyword = NULL, $location = "", $count = 20){
		if($keyword == null){
			return array();
		}
		$this->initiateModel('read');
		
		
		$queryCmd =	"SELECT
					tSponsoredListingByKeyword.id as sid,
					tSponsoredListingByKeyword.count as scount,
					tSponsoredListingByKeyword.listingId as slistingId,
					tSponsoredListingByKeyword.type as stype,
					institute.featured_panel_link as ipanelLink,
					institute.*
					FROM
					tSponsoredListingByKeyword,
					institute,
					listings_main
					WHERE
					tSponsoredListingByKeyword.sponsorType = 'featured' AND
					tSponsoredListingByKeyword.listingId = institute.institute_id AND
					listings_main.listing_type_id = tSponsoredListingByKeyword.listingId AND
					tSponsoredListingByKeyword.keyword = ? AND
					tSponsoredListingByKeyword.location= ? AND
					institute.featured_panel_link != '' AND
					listings_main.status = 'live' AND
					listings_main.listing_type = 'institute' AND
					institute.status = 'live' AND
					isDeleted = 0 AND
					tSponsoredListingByKeyword.set_time <= now() AND
					tSponsoredListingByKeyword.unset_time >= now()
					GROUP BY
					tSponsoredListingByKeyword.listingId
					ORDER BY
					update_time, RAND() limit ?";
		
		$query = $this->dbHandle->query($queryCmd, array($keyword, $location, $count));
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[$row->sid] = array(); 
				$data[$row->sid]['listing_id'] = $row->slistingId;
				$data[$row->sid]['listing_type'] = $row->stype;
				$data[$row->sid]['institute_name'] = $row->institute_name;
				$data[$row->sid]['imageUrl'] = $row->ipanelLink;
				$data[$row->sid]['count'] = $row->scount;
				$data[$row->sid]['url'] = "/getListingDetail/". $row->slistingId . "/" . $row->stype;
			}
		}
		return $data;
	}
	
	public function getDiscussionDetails($id = null, $maxComments = -1){
		if($id == null){
			return array();
		}
		$this->initiateModel('read');	
		
		$data = array('discussion' => array(), 'comments' => array());
		
		$queryCmd ="SELECT mt.*, md.description AS description, u.displayname AS displayname, u.avtarimageurl AS image_url, GROUP_CONCAT( mct.categoryId ) AS category_ids
					FROM
					messageTable AS mt,
					messageCategoryTable AS mct,
					tuser AS u,
					messageDiscussion as md
					WHERE
					mt.threadId = ? AND
					mt.msgId = mct.threadId AND
					mt.parentId = mt.threadId AND
					mt.fromOthers = 'discussion' AND
					mt.userId = u.userid AND
					md.threadId = mt.msgId AND
					mt.status IN ('live', 'closed')
					GROUP BY mt.threadId 
					LIMIT 1
					";
		$query = $this->dbHandle->query($queryCmd, $id);
		if($query->num_rows() > 0) {
		   $row = (array)$query->row();
		   $data['discussion'] = $row;
		}
		
		$limitCondition = "";
		if($maxComments != -1){
			$limitCondition = " LIMIT ". $maxComments;
		}
		$queryCmd ="SELECT SQL_CALC_FOUND_ROWS mt.*, u.displayname AS displayname, u.avtarimageurl AS image_url
					FROM
					messageTable AS mt,
					tuser AS u
					WHERE
					mt.threadId = ? AND
/*					mt.parentId != mt.threadId AND
					mt.parentId > 0 AND*/
					mt.parentId = mt.mainAnswerId AND
					mt.fromOthers = 'discussion' AND
					mt.userId = u.userid AND 
					mt.status IN ('live', 'closed')
					ORDER BY mt.creationDate DESC
					$limitCondition
					";
		$query = $this->dbHandle->query($queryCmd, $id);
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data['comments'][] = (array)$row;
			}
		}

		$commentCount = $this->dbHandle->query("select FOUND_ROWS() as count")->row_array();
		$data['commentCount'] = $commentCount ? $commentCount['count'] : 0;

		return $data;
	}
	
	public function getDiscussionCommentCount($id = null){
		if($id == null){
			return array();
		}
		$this->initiateModel('read');	
		
		$queryCmd ="SELECT msgId 
					FROM
					messageTable AS mt
					WHERE
					mt.threadId = ? AND
					mt.parentId = mt.threadId AND
					mt.fromOthers = 'discussion' AND
					mt.status IN ('live', 'closed')
					";
		$query = $this->dbHandle->query($queryCmd, $id);
		$msgId = -1;
		if($query->num_rows() > 0) {
		   $row = (array)$query->row();
		   $msgId = $row['msgId'];
		}
		$count = 0;
		if($msgId != -1){
			$queryCmd = "SELECT count(mt.mainAnswerId) AS total
						FROM messageTable mt 
						WHERE
						mt.fromOthers = 'discussion' AND
						mt.status IN ('live','closed') AND 
						parentId !=0 AND
						mt.mainAnswerId = ?
						GROUP BY 
						mt.mainAnswerId";
			
			$query = $this->dbHandle->query($queryCmd, $msgId);
			if($query->num_rows() > 0) {
			   $row = (array)$query->row();
			   $count = $row['total'];
			}
		}
		return $count;
	}
	
	public function getLiveSponsoredResults($params = array()){
		if(empty($params)){
			return array();
		}
		$this->initiateModel('read');	
		
		
		$queryCmd ="SELECT *
					FROM
					search_sponsored_listings
					WHERE
					status = 1 AND
					subscription_end_date >= NOW()
					";
		$queryStringExt = "";
		$queryParamDataArray = array();
		if(!empty($params['location_id'])){
			$queryStringExt .= " AND location_id = ?";
			$queryParamDataArray[] = $params['location_id'];
		}
		if(!empty($params['category_id'])){
			$queryStringExt .= " AND category_id = ?";
			$queryParamDataArray[] = $params['category_id'];
		}			
		if(!empty($params['parent_id']) && !empty($params['parent_type'])){
			$queryStringExt .= " AND parent_id = ? AND parent_type = ?";
			$queryParamDataArray[] = $params['parent_id'];
			$queryParamDataArray[] = $params['parent_type'];
		}
		if(!empty($params['listing_id']) && !empty($params['listing_type'])){
			$queryStringExt .= " AND listing_id = ? AND listing_type = ?";
			$queryParamDataArray[] = $params['listing_id'];
			$queryParamDataArray[] = $params['listing_type'];
		}
		if(!empty($params['product_reach'])){
			$queryStringExt .= " AND product_reach = ?";
			$queryParamDataArray[] = $params['product_reach'];
		}
		if(!empty($params['sponsor_type'])){
			$queryStringExt .= " AND sponsor_type = ?";
			$queryParamDataArray[] = $params['sponsor_type'];
		}
		
		$queryStringExt .= " ORDER BY set_time DESC ";
		$queryCmd = $queryCmd . $queryStringExt;
		if(!empty($queryParamDataArray)){
			$query = $this->dbHandle->query($queryCmd, $queryParamDataArray);
		} else {
			$query = $this->dbHandle->query($queryCmd);
		}
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = (array)$row;
			}
		}
		return $data;
	}
	
	public function insertSponsoredListing($associativeList = array()){
		if(empty($associativeList)){
			return -1;
		}
		$this->initiateModel();	
		
		$returnValue = $this->dbHandle->insert_batch('search_sponsored_listings', $associativeList);
		return $returnValue;
	}
	
	public function increaseSponsoredListingImpressions($sponsorType = NULL, $listingIds = NULL, $listingType = NULL, $increaseFactor = 1){
		if(empty($sponsorType) || empty($listingIds) || empty($listingType)){
			return;
		}
		$this->initiateModel();
		
		//$listingIdsStr = implode(",", $listingIds);
		$queryCmd ="UPDATE
					search_sponsored_listings
					SET
					impressions = impressions + ?
					WHERE
					listing_id IN (?) AND
					sponsor_type = ? AND
					listing_type = ? AND
					status = 1";
		$query = $this->dbHandle->query($queryCmd, array($increaseFactor, $listingIds, $sponsorType, $listingType));
	}
	
	public function getDataForIndexing($dataType = 'institute', $offset = 0, $limit = 1000, $order = 'ASC', $offsetId, $durationInDays = -1, $getInstitutesForQER = 0){
		$this->initiateModel('read');
		if(!in_array($order, array('ASC', 'DESC', 'asc', 'desc'))) {
			$order = '';
		}
		
		$data = array();
		switch($dataType){
			case 'institute':
				$data = $this->getInstituteIdsForIndexing($offset, $limit, $order, $offsetId, $getInstitutesForQER);
				break;
			case 'article':
				$data = $this->getArticleIdsForIndexing($offset, $limit, $order, $offsetId);
				break;
			case 'question':
				$data = $this->getQuestionIdsForIndexing($offset, $limit, $order, $offsetId);
				break;
			case 'discussion':
				$data = $this->getDiscussionIdsForIndexing($offset, $limit, $order, $offsetId);
				break;
			case 'university':
					$data = $this->getUniversityIdsForIndexing($offset, $limit, $order, $durationInDays);
					break;
			case 'abroadinstitute':
					$data = $this->getAbroadInstituteIdsForIndexing($offset, $limit, $order, $durationInDays);
					break;
			case 'abroadcourse':
					$data = $this->getAbroadCourseIdsForIndexing($offset, $limit, $order, $durationInDays);
					break;

			case 'career':
					$data = $this->getCareerIdsForIndexing($offset, $limit, $order);
					break;
			case 'tag':
					$data = $this->getTagIdsForIndexing($offset, $limit, $order, $offsetId, $durationInDays);
					break;
		}
		return $data;
	}


	public function getAbroadCourseIdsForIndexing($offset = 0, $limit = 1000, $order = 'ASC', $durationInDays = -1) {
		$this->initiateModel('read');
		if($durationInDays != -1) {
			$whereClause = "INNER JOIN listings_main lm ON cd.course_id = lm.listing_type_id AND lm.listing_type = 'course' AND lm.status = '".ENT_SA_PRE_LIVE_STATUS."'".
							"WHERE cd.status = 'live' AND DATE(lm.last_modify_date) >= DATE(DATE_ADD(NOW(), INTERVAL -$durationInDays DAY))";
		} else {
			$whereClause = " WHERE cd.status = 'live' ";
		}
		$queryCmd = "SELECT distinct(cd.course_id) as listing_type_id
					FROM `course_details` cd
					INNER JOIN `institute_location_table` ilt ON ( cd.institute_id = ilt.institute_id AND ilt.status = '".ENT_SA_PRE_LIVE_STATUS."' AND ilt.country_id >2 )
					$whereClause
					ORDER BY cd.course_id $order
					LIMIT $offset, $limit
					";
		$query = $this->dbHandle->query($queryCmd);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = $row->listing_type_id;
			}
		}
		return $data;
	}


	public function getAbroadInstituteIdsForIndexing($offset = 0, $limit = 1000, $order = 'ASC', $durationInDays = -1){
		$this->initiateModel('read');
		
		if($durationInDays != -1) {
			$extraFromClause = "INNER JOIN listings_main as lm ON lm.listing_type_id = i.institute_id AND lm.listing_type = 'institute' AND lm.status = 'live'";
			$extrawhereClause = "AND DATE(lm.last_modify_date) >= DATE(DATE_ADD(NOW(), INTERVAL -$durationInDays DAY))";
		} else {
			$extraFromClause = "";
			$extrawhereClause = "";
		}
		$queryCmd = "SELECT distinct(i.institute_id) as institute_id
						FROM `institute` i INNER JOIN `institute_university_mapping` m
											ON i.institute_id = m.institute_id AND m.status = 'live'
											$extraFromClause
					WHERE i.status='".ENT_SA_PRE_LIVE_STATUS."'
					$extrawhereClause
					ORDER BY institute_id $order
					LIMIT $offset, $limit
					";
		$query = $this->dbHandle->query($queryCmd);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = $row->institute_id;
			}
		}
		return $data;
	}

	public function getUniversityIdsForIndexing($offset = 0, $limit = 1000, $order = 'ASC', $durationInDays = -1){
		$this->initiateModel('read');	
		
		if($durationInDays != -1) {
			$fromClause = " university as u INNER JOIN listings_main as lm ON lm.listing_type_id = u.university_id AND lm.listing_type = 'university' AND lm.status = 'live' ";
			$whereClause = " u.status='".ENT_SA_PRE_LIVE_STATUS."' AND DATE(lm.last_modify_date) >= DATE(DATE_ADD(NOW(), INTERVAL -$durationInDays DAY)) ";
		} else {
			$fromClause = " university ";
			$whereClause = " status='".ENT_SA_PRE_LIVE_STATUS."' ";
		}
		$queryCmd = "SELECT university_id as university_id
					FROM
					$fromClause
					WHERE
                    $whereClause
					ORDER BY university_id $order
					LIMIT $offset, $limit
					";
		$query = $this->dbHandle->query($queryCmd);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = $row->university_id;
			}
		}
		return $data;
	}

	public function getDataForIndexingNew($dataType = 'streams',$offset = 0, $batchSize = 1000,$order = 'ASC'){
		$data = array();
		switch ($dataType) {
			case 'streams':
					$data = $this->getStreamsDataForIndexing();
					break;
			case 'substreams':
					$data = $this->getSubStreamsDataForIndexing();
					break;
			case 'specializations':
					$data = $this->getSpecializationDataForIndexing();
					break;
			case 'base_courses':
					$data = $this->getBaseCoursesDataForIndexing();
					break;
			case 'institutes' :
					$data = $this->getInstituteDataForIndexing($offset,$batchSize,$order);
					break;
			case 'university' :
					//$data = $this->getUniversityDataForIndexing($offset,$batchSize,$order);
					break;
			case 'popular_groups' :
					$data = $this->getPopularGroupsDataForIndexing();
					break;
			case 'certificate_providers' :
					$data = $this->getDataForCertificateProviders();
					break;
			case 'exams' :
					$data = $this->getExamsDataForIndexing();
					break;
			case 'careers' :
					$data = $this->getCareersDataForIndexing();
					break;
			default:
				# code...
				break;
		}
		return $data;
	}
	
	public function getInstituteIdsForIndexing($offset = 0, $limit = 1000, $order = 'ASC', $fromInstituteId, $getInstitutesForQER = 0){
		return; /*
		$this->initiateModel('read');
		
		$whereIntitutes = "";
		if(!empty($fromInstituteId)) {
			if($order == 'ASC') {
				$whereIntitutes = "AND lm.listing_type_id >= ".$fromInstituteId;
			} else {
				$whereIntitutes = "AND lm.listing_type_id <= ".$fromInstituteId;
			}
		}

		if($getInstitutesForQER) {
			$whereTestPrep = "i.institute_type NOT IN ('Department', 'Department_Virtual', 'Test_Preparatory_Institute')";
		} else {
			$whereTestPrep = "i.institute_type NOT IN ('Department', 'Department_Virtual')";
		}
		
		$queryCmd = "SELECT
					distinct lm.listing_type_id
					FROM
					listings_main lm, institute i
					WHERE
					lm.listing_type_id = i.institute_id AND 
					lm.listing_type = 'institute' AND
					lm.status = 'live' AND
					i.status = 'live' AND
					$whereTestPrep
					$whereIntitutes
					ORDER BY lm.listing_type_id $order
					LIMIT $offset, $limit
					";
		
		$query = $this->dbHandle->query($queryCmd);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = $row->listing_type_id;
			}
		}
		return $data;
		*/
	}
	
	public function getArticleIdsForIndexing($offset = 0, $limit = 1000, $order = 'ASC', $fromArticleId){
		$this->initiateModel('read');	
		
		$additionalWhere = "";
		if(!empty($fromArticleId)) {
			if($order == 'ASC') {
				$additionalWhere = "AND blogId >= ?";
			} else {
				$additionalWhere = "AND blogId <= ?";
			}
			$params[] = $fromArticleId;
		}
		$params[] = (int) $offset;
		$params[] = (int) $limit;
		
		$queryCmd = "SELECT blogId as blogId
					FROM blogTable
					WHERE status = 'live' $additionalWhere
					ORDER BY blogId $order
					LIMIT ?, ?
					";
		
		$query = $this->dbHandle->query($queryCmd, $params);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = $row->blogId;
			}
		}
		return $data;
	}
	
	public function getQuestionIdsForIndexing($offset = 0, $limit = 1000, $order = 'ASC', $fromQuestionId){
		$this->initiateModel('read');
		
		$additionalWhere = "";
		if(!empty($fromQuestionId)) {
			if($order == 'ASC') {
				$additionalWhere = "AND threadId >= ?";
			} else {
				$additionalWhere = "AND threadId <= ?";
			}
			$params[] = $fromQuestionId;
		}
		
		$queryCmd = "SELECT threadId as threadId
					FROM	messageTable
					WHERE	parentId = 0 AND (status='live' or status='closed') AND	fromOthers = 'user' $additionalWhere
					ORDER BY threadId $order
					LIMIT ?, ?
					";
		$params[] = (int) $offset;
		$params[] = (int) $limit;
		
		$query = $this->dbHandle->query($queryCmd, $params);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = $row->threadId;
			}
		}
		return $data;
	}
	
	public function getDiscussionIdsForIndexing($offset = 0, $limit = 1000, $order = 'ASC', $fromDiscussionId){
		$this->initiateModel('read');	
		
		$additionalWhere = "";
		if(!empty($fromDiscussionId)) {
			if($order == 'ASC') {
				$additionalWhere = "AND threadId >= ?";
			} else {
				$additionalWhere = "AND threadId <= ?";
			}
			$params[] = $fromDiscussionId;
		}
		
		$queryCmd = "SELECT threadId as threadId
					FROM	messageTable
					WHERE 	parentId = 0 AND (status='live' or status='closed') AND fromOthers = 'discussion' $additionalWhere
					ORDER BY threadId $order
					LIMIT ?, ?
					";
		
		$params[] = (int) $offset;
		$params[] = (int) $limit;

		$query = $this->dbHandle->query($queryCmd, $params);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = $row->threadId;
			}
		}
		return $data;
	}

	public function getTagIdsForIndexing($offset = 0, $limit = 1000, $order = 'ASC', $fromTagId, $durationInDays = -1){
		$this->initiateModel('read');
		
		$whereClause = "";

		// get the list of tag-ids on which any activity has been performed in last $durationInDays days
		if($durationInDays != -1) {

			$sql = "SELECT a.tag_id FROM ((SELECT follow.entityId AS tag_id
					FROM shiksha.tuserFollowTable follow 
					WHERE DATE(follow.modificationTime) >= DATE(DATE_ADD(NOW(), INTERVAL - ".$durationInDays." DAY) ) ORDER BY follow.entityId DESC)
					UNION
					(SELECT contentmap.tag_id as tag_id
					FROM shiksha.tags_content_mapping contentmap  
					where DATE(contentmap.modificationTime) >= DATE(DATE_ADD(NOW(), INTERVAL - ".$durationInDays." DAY) ) ORDER BY contentmap.tag_id DESC)) AS a ORDER BY a.tag_id ASC LIMIT $offset, $limit ";

			$query = $this->dbHandle->query($sql);

			$tagIds = array();
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$tagIds[] = $row->tag_id;
				}
			}

			$whereClause .= " AND status = 'live' ";

			if(!empty($tagIds))
				$whereClause .= " AND t.id IN (".implode(',', $tagIds).") ";
			else
				$whereClause .= " AND FALSE ";
		}

		if(!empty($fromTagId)) {
			if($order == 'ASC') {
				$whereClause .= " AND t.id >= ".$fromTagId;
			} else {
				$whereClause .= " AND t.id <= ".$fromTagId;
			}
		}
		
		$queryCmd = "SELECT distinct t.id
					 FROM tags t
					 WHERE 1
					 $whereClause
					 ORDER BY t.id $order
					 LIMIT $offset, $limit ";

		$query = $this->dbHandle->query($queryCmd);
		

		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = $row->id;
			}
		}
		return $data;
	}
	
	public function getSearchQueryStatsByRange($queryType = '', $fromDate = NULL, $toDate = NULL){
		$this->initiateModel('read');
		$data = array();
		$queryCmd = "";
		if(!empty($fromDate) && !empty($toDate) && !empty($queryType)){
			$toDate = date('Y-m-d', strtotime($toDate . ' + 1 day'));
			$params[] = $fromDate;
			$params[] = $toDate;
			switch($queryType){
				case 'SEARCH_QUERIES_COUNT':
					$queryCmd = "
						SELECT count(*) as count, date(timestamp) as d
						FROM
						track_searchqueries_newui
						WHERE
						timestamp >= ? AND
						timestamp < ?
						GROUP BY d
						ORDER BY d DESC
					";
					break;
				case 'SINGLE_INSTITUTE_COUNT':
					$queryCmd = "
						SELECT count(*) as count, date(timestamp) as d
						FROM
						track_searchqueries_newui
						WHERE
						institute_count = 1 AND
						timestamp >= ? AND
						timestamp < ?
						GROUP BY d
						ORDER BY d DESC
					";
					break;
				case 'RESULTS_CLICKED_COUNT':
					$queryCmd = "
						SELECT count(*) as count, date(timestamp) as d
						FROM
						track_searchqueries_newui
						WHERE
						result_clicked_row_count > 0 AND
						timestamp >= ? AND
						timestamp < ?
						GROUP BY d
						ORDER BY d DESC
					";
					break;
				case 'PAGINATION_COUNT':
					$queryCmd = "
						SELECT count(*) as count, date(timestamp) as d
						FROM
						track_searchqueries_newui
						WHERE
						max_page_id > 1 AND
						timestamp >= ? AND
						timestamp < ?
						GROUP BY d
						ORDER BY d DESC
					";
					break;
				case 'REQUEST_EBROCHURE':
					$queryCmd = "
						SELECT count(*) as count, date(submit_date) as d
						FROM
						tempLMSTable
						WHERE
						action = 'SEARCH_REQUEST_EBROCHURE' AND 
                                                listing_subscription_type = 'paid' AND 
						submit_date >= ? AND
						submit_date < ?
						GROUP BY d
						ORDER BY d DESC
					";
					break;
				case 'REGISTRATION':
					$queryCmd = "
						SELECT count(*) as count, date(time) as d
						FROM
						tusersourceInfo
						WHERE
						keyid = 154 AND 
						time >= ? AND
						time < ?
						GROUP BY d
						ORDER BY d DESC
					";
					break;
			}
		}
		if(!empty($queryCmd)){
			$query = $this->dbHandle->query($queryCmd, $params);
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$data[$row->d] = $row->count;
				}
			}	
		}
		return $data;
	}
	
       
       public function getDataForDeleting($dataType = 'university', $offset = 0, $limit = 1000, $durationInDays = -1){		
		$data = array();
		switch($dataType){
			case 'university':
					//$data = $this->getUniversityIdsForDeleting($offset, $limit);
					break;
			case 'abroadinstitute':
					//$data = $this->getAbroadInstituteIdsForDeleting($offset, $limit);
					break;
			case 'abroadcourse':
					//$data = $this->getAbroadCourseIdsForDeleting($offset, $limit, $durationInDays);
					break;
		}
		return $data;
	}
        
	public function getResultClickStats($type = NULL, $fromDate = NULL, $toDate = NULL){
		$this->initiateModel('read');
		$result = array();
		$queryCmd = "";
		if(!empty($fromDate) && !empty($toDate) && !empty($type)){
			$toDate = date('Y-m-d', strtotime($toDate . ' + 1 day'));
			switch($type){
				case 'RESULT_CLICK_TYPE':
					$coloumnName = 'result_clicked_type';
					break;
				case 'FROM_PAGE':
					$coloumnName = 'page';
					break;
				case 'LOGGED_TYPE':
					$coloumnName = 'loggedin_type';
					break;
			}
			$queryCmd = "
						SELECT DISTINCT $coloumnName
						FROM
						track_searchqueries_newui
						";
			$query = $this->dbHandle->query($queryCmd);
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					switch($type){
						case 'RESULT_CLICK_TYPE':
							$data[] = $row->result_clicked_type;
							break;
						case 'FROM_PAGE':
							$data[] = $row->page;
							break;
						case 'LOGGED_TYPE':
							$data[] = $row->loggedin_type;
							break;
					}
				}
			}
			
			foreach($data as $clickType){
				$result[$clickType] = array();
				$queryCmd = "
						SELECT count(*) as count, date(timestamp) as d
						FROM
						track_searchqueries_newui
						WHERE
						$coloumnName = '$clickType' AND
						timestamp >= ? AND
						timestamp < ?
						GROUP BY d
						ORDER BY d DESC
					";
				$params[] = $fromDate;
				$params[] = $toDate;
				$query = $this->dbHandle->query($queryCmd, $params);
				if($query->num_rows() > 0) {
					foreach($query->result() as $row) {
						$result[$clickType][$row->d] = $row->count;
					}
				}	
			}
		}
		return $result;
	}
	
	public function getSearchStats($fromDateString = NULL, $toDateString = NULL){
		$this->initiateModel('read');
		$data = array();
		if(!empty($fromDateString) && !empty($toDateString)){
			$fromDate = date('Y-n-j', strtotime($fromDateString));
			$toDate = date('Y-n-j', strtotime($toDateString));
			
			$searchQueries 		= $this->getSearchQueryStatsByRange('SEARCH_QUERIES_COUNT', $fromDate, $toDate);
			$singleInstitute 	= $this->getSearchQueryStatsByRange('SINGLE_INSTITUTE_COUNT', $fromDate, $toDate);
			$resultClicked 		= $this->getSearchQueryStatsByRange('RESULTS_CLICKED_COUNT', $fromDate, $toDate);
			$pagination 		= $this->getSearchQueryStatsByRange('PAGINATION_COUNT', $fromDate, $toDate);
			$requestEBrochure 	= $this->getSearchQueryStatsByRange('REQUEST_EBROCHURE', $fromDate, $toDate);
			$registration 		= $this->getSearchQueryStatsByRange('REGISTRATION', $fromDate, $toDate);
			
			$resultClickStats 	= $this->getResultClickStats('RESULT_CLICK_TYPE', $fromDate, $toDate);
			$fromPageStats 		= $this->getResultClickStats('FROM_PAGE', $fromDate, $toDate);
			$loggedStats 		= $this->getResultClickStats('LOGGED_TYPE', $fromDate, $toDate);
			
			$data['search_queries']   		= $searchQueries;
			$data['single_institute'] 		= $singleInstitute;
			$data['result_clicked']   		= $resultClicked;
			$data['pagination']       		= $pagination;
			$data['ebrochure']        		= $requestEBrochure;
			$data['registration']        	= $registration;
			$data['result_click_stats'] 	= $resultClickStats;
			$data['fromPageStats']			= $fromPageStats;
			$data['loggedStats']			= $loggedStats;
		}
		return $data;
	}
	
	public function getSubCategoriesListBasedOnTier($tier = 0, $flag = 'national'){
		$this->initiateModel('read');
		$data = array();
		if(empty($tier)){
			return $data;
		}
		$queryCmd = "
					SELECT boardId as category_id, name as category_name, parentid, tier
					FROM
					categoryBoardTable
					WHERE
					enabled = 0 AND
					parentId != 1 AND
					parentId != 0 AND
					tier = ? AND
					flag = ?
					";
					
		$query = $this->dbHandle->query($queryCmd, array($tier, $flag));
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$tempData = array();
				$tempData['category_id'] = $row->category_id;
				$tempData['category_name'] = $row->category_name;
				$tempData['parent_category_id'] = $row->parentid;
				$tempData['category_tier'] = $row->tier;
				$data[] = $tempData;
			}
		}
		return $data;
	}
	
	public function getCategoryDetail($categoryIds = array()){
		$this->initiateModel('read');
		$data = array();
		if(empty($categoryIds)){
			return $data;
		}
		
		$queryCmd = "
					SELECT boardId as category_id, name as category_name, parentid, tier
					FROM
					categoryBoardTable
					WHERE
					enabled = 0 AND
					boardId IN ( ? )
					";
		
		$query = $this->dbHandle->query($queryCmd, array($categoryIds));
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$tempData = array();
				$tempData['category_id'] = $row->category_id;
				$tempData['category_name'] = $row->category_name;
				$tempData['parent_category_id'] = $row->parentid;
				$tempData['category_tier'] = $row->tier;
				$data[] = $tempData;
			}
		}
		return $data;
	}

	public function getDeletedInstitutesNew($offset = 0, $limit = 1000){
		$this->initiateModel('read');
		$data = array();
		$queryCmd = "SELECT distinct listing_id as institute_id
					FROM
					shiksha_institutes
					WHERE
					status = 'deleted'
					LIMIT $offset, $limit
					";
		$query = $this->dbHandle->query($queryCmd);
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = $row->institute_id;
			}
		}
		return $data;
	}

    public function getQuestionExtraDetails($id =null)
    {
        if($id == null){
            return array();
        }
        $this->initiateModel('read');
        $data = array();

         $data['bestAnswerId'] = -1;
         $data['inMasterList'] = 0;

         $queryCmd = "SELECT count(*) as total from qnaMasterQuestionTable where msgId = ? and status = 'live'";
         $query = $this->dbHandle->query($queryCmd, $id);

         foreach($query->result() as $row)
         {
             $data['inMasterList'] = $row->total;    
         }
         $queryCmd = "SELECT bestAnsId
                      FROM `messageTableBestAnsMap`
                      WHERE threadId = ?";


         $query = $this->dbHandle->query($queryCmd, $id);

         foreach($query->result() as $row)
         {
             $data['bestAnswerId'] = $row->bestAnsId;
         }
         return $data;

    }
	
	public function getVirtualCityMappingForSearch() {
		$this->initiateModel('read');
		$data = array();
		$queryCmd = "SELECT * FROM virtualCityMapping WHERE virtualCityId != city_id";
		$query = $this->dbHandle->query($queryCmd);
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[$row->virtualCityId][] = $row->city_id;
			}
		}
		return $data;
	}
	
	public function fetchResultForIndexLogs($requestedLog) {
		$this->initiateModel('read');
		if(!empty($requestedLog['type'])) {
		 	$ListingType = " AND `listing_type` = ? ";
		 	$params[] = $requestedLog['type'];
		}
		if(!empty($requestedLog['fromDate']) && !empty($requestedLog['toDate'])) {
		 	$DateClause = " AND `listing_added_time` BETWEEN ? AND ? ";
		 	$params[] = $requestedLog['fromDate']." ".$requestedLog['fromHour'].":".$requestedLog['fromMinutes'].":00";
		 	$params[] = $requestedLog['toDate']." ".$requestedLog['toHour'].":".$requestedLog['toMinutes'].":00";
		}
		if(!empty($requestedLog['status']) && $requestedLog['status'] != 'All' ) {
		 	$statusClause = "AND `status` = ? ";
		 	$params[] = $requestedLog['status'];
		}
		
		$queryCmd = " SELECT listing_type as type,listing_id as typeId,listing_added_time as time,	status as status,comment as errorMsg,operation as OperationType 
					  FROM `indexlog`
					  WHERE 1 = 1".
					  $ListingType.
					  $DateClause. 
				      $statusClause." ORDER BY `listing_added_time` DESC ";
		
		$result = $this->dbHandle->query($queryCmd, $params)->result_array();

		return $result;
	}
	
	public function insertIntoSearchTracking($data,$action) {
		$this->initiateModel('write');
		if($action == 'insert') 
		{	
		 $this->dbHandle->insert('abroadSearchTracking', $data);
		 $rowId = $this->dbHandle->insert_id();
		} else {
			$id =  $data['id'];
			unset($data['id']);
		    $rowId = $this->dbHandle->update('abroadSearchTracking', $data,"id =".$id);
		}
		
		$rowId = $this->dbHandle->insert_id();
		return $rowId;
	}
	
	public function getCourseReviewCount($courseId = NULL) {
		if(empty($courseId)){
			return 0;
		}
		$count = 0;
		$this->initiateModel('read');
		$queryCmd 	= "SELECT count( * ) as count FROM CollegeReview_MappingToShikshaInstitute CMS, CollegeReview_MainTable CM WHERE CMS.courseId = ? AND CMS.reviewId = CM.id AND CM.status = 'published' ";
		$query 		= $this->dbHandle->query($queryCmd, array($courseId));
		if($query->num_rows() > 0) {
			$row = (array)$query->row();
			$count = $row['count'];
		}
		return $count;
	}

	public function getInstituteRelatedKeyword($instituteId = NULL) {
		if($instituteId == null) {
			return false;
		}
		$this->initiateModel('read');
		$queryCmd 	= "select * from instituteRelatedKeywords where instituteId = ? and status = 'live' ";
		$query 		= $this->dbHandle->query($queryCmd, $instituteId);
		$synonymList 	= array();
		$acronymList 	= array();
		if($query->num_rows() > 0) {
			$row 	= $query->result();
			$data 	= (array)$row[0];
			$synonyms = trim(trim($data['synonyms']), ",");
			$acronyms = trim(trim($data['acronyms']), ",");
			if(!empty($synonyms)){
				$explodedS 		= explode(",", $synonyms);
				foreach($explodedS as $synonym){
					$synonymList[] = trim($synonym);
				}
			}
			if(!empty($acronyms)){
				$explodedA 		= explode(",", $acronyms);
				foreach($explodedA as $acronym){
					$acronymList[] = trim($acronym);
				}
			}
		}
		return array('synonyms' => $synonymList, 'accronyms' => $acronymList);
	}
	
	public function getCareerSynonyms($careerId = NULL) {
		if(empty($careerId)){
			return false;
		}
		$this->initiateModel('read');
		$queryCmd = "SELECT synonym FROM career_synonyms WHERE career_id = ?";
		$query = $this->dbHandle->query($queryCmd, $careerId);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$synonym = $row->synonym;
				if(!empty($synonym)){
					$synonym = trim($synonym);
					$synonym = trim($synonym, ",");
					$explodedData = explode(",", $synonym);
					$data = array_merge($data, $explodedData);
				}
			}
		}
		$data = array_unique($data);
		return $data;
	}
	
	public function getCareerIdsForIndexing($offset = 0, $limit = 1000, $order = 'ASC') {
		$this->initiateModel('read');
		$queryCmd = "select careerId from  CP_CareerTable where status = 'live' ORDER BY careerId $order LIMIT $offset, $limit";
		$query = $this->dbHandle->query($queryCmd);
		$numOfRows = $query->num_rows();
		$result = $query->result_array();
		$i=0;
		if($numOfRows!=0){
			foreach($result as $key=>$value){
				$res[$i] = $value['careerId'];
				$i++;
			}
		}
		return $res;
	}

	public function getTagsData($id = null){
		if($id == null){
			return array();
		}
		$this->initiateModel('read');	
		
		$data = array('tagdata' => array());
		
		$queryCmd ="SELECT t.id, t.tags, t.tag_entity, t.description
					FROM
					tags AS t
					WHERE t.id = ?
					AND t.id = t.main_id
					LIMIT 1 ";

		$query = $this->dbHandle->query($queryCmd, $id);
		$tag_id = 0;
		if($query->num_rows() > 0) {
		   $row = (array)$query->row();
		   $tag_id = $row['id'];
		   $data['tagdata'] = $row;
		}

		global $synonymMapping;

		if(!$synonymMapping){
			error_log("Inside Individual synonym fetching");
			$queryCmd ="SELECT t.tags
						FROM
						tags AS t
						WHERE t.main_id = ?";
			if($tag_id){
				$query = $this->dbHandle->query($queryCmd, $tag_id);
				if($query->num_rows() > 0) {
				   $result = $query->result_array();
				   foreach ($result as $value) {
				   		$data['tagdata']['synonyms'][] = $value['tags'];
				   }
				}			
			}
		}
		else{
			$data['tagdata']['synonyms'] = $synonymMapping[$tag_id];
		}
		return $data;
	}

	function getTagsSynonymsMapping(){

		$this->initiateModel('read');	
		
		$queryCmd ="SELECT t.main_id, t.tags
					FROM
					tags AS t
					WHERE t.id != t.main_id ";

		$synonymsMapping = array();
		$query = $this->dbHandle->query($queryCmd);
		if($query->num_rows() > 0) {
		   $data = $query->result_array();
		   
		   foreach($data as $value){
		   		$synonymsMapping[$value['main_id']][] = $value['tags'];
		   }
		}

		return $synonymsMapping;
	}

	function getTagFollowCount($tagId){

		$this->initiateModel('read');	
		
		$queryCmd = "SELECT COUNT(*) as tagFollows FROM tuserFollowTable WHERE status = 'follow' AND entityType = 'tag' AND entityId = ?";

		$rs = (array)$this->dbHandle->query($queryCmd, array($tagId))->row();

		return $rs['tagFollows'];
	}

	function getTagContentMappingCount($tagId){

		$this->initiateModel('read');	
		
		$queryCmd = "SELECT content_type, count(*) AS num FROM tags_content_mapping WHERE tag_id = ? GROUP BY content_type";

		$rs = $this->dbHandle->query($queryCmd, array($tagId))->result_array();

		$contentMappingCount = array();
		foreach($rs as $value){
			$contentMappingCount[$value['content_type']] = $value['num'];
		}

		return $contentMappingCount;
	}

	function getContentMappedTags($contentId, $contentType, $type=""){

		$this->initiateModel('read');	
	
		$whereClause = "";

		if($type){
			$whereClause .= " AND tag_type = ? ";
		}	

		$queryCmd = "SELECT tag_id,tag_type FROM tags_content_mapping WHERE content_id = ? AND content_type = ? AND status='live' ".$whereClause;

		$rs = $this->dbHandle->query($queryCmd, array($contentId, $contentType, $type))->result_array();

		$contentMappedTags = array();
		foreach($rs as $value){
			$contentMappedTags[$value['tag_type']][] = $value['tag_id'];
		}

		return $contentMappedTags;
	}

	function updateTagQualityScore($id, $tagQualityFactor){
		$this->initiateModel('write');
		$this->dbHandle->where('id', $id);
		$this->dbHandle->update('tags', array('tag_quality_score' => $tagQualityFactor));
	}

	function getTagParents($tagId, $limit = 10){
		$this->initiateModel('read');	
	
		if(empty($tagId))
				return false;

		$queryCmd = "SELECT parent_id FROM tags_parent WHERE tag_id = ? AND status='live' LIMIT ".$limit;

		$rs = $this->dbHandle->query($queryCmd, array($tagId))->result_array();

		$result = array();
		foreach($rs as $value){
			$result[] = $value['parent_id'];
		}

		return $result;
	}

	function getTagChildren($tagId, $limit = 10){
	
		$this->initiateModel('read');	

		if(empty($tagId))
			return false;
		
		$queryCmd = "SELECT DISTINCT(tag_id) as tag_id,t.tag_quality_score FROM tags_parent tp INNER JOIN tags t ON(tp.tag_id = t.id) WHERE tp.parent_id = ?  AND t.status='live' AND tp.status='live' ORDER BY t.tag_quality_score DESC LIMIT ".$limit;

		$rs = $this->dbHandle->query($queryCmd, array($tagId))->result_array();

		$result = array();
		foreach($rs as $value){
			$result[] = $value['tag_id'];
		}

		return $result;
	}

	function getTagSiblings($tagId, $parentIds, $limit = 10){
		$this->initiateModel('read');	

		if(empty($parentIds))
			return false;

		$queryCmd = "SELECT DISTINCT(tag_id) as tag_id FROM tags_parent tp INNER JOIN tags t
					 ON(tp.tag_id = t.id)
					 WHERE tp.parent_id IN (?) AND tp.tag_id != ? AND t.status='live' ORDER BY tag_quality_score DESC LIMIT ".$limit;

		$rs = $this->dbHandle->query($queryCmd, array($parentIds, $tagId))->result_array();

		$result = array();
		foreach($rs as $value){
			$result[] = $value['tag_id'];
		}

		return $result;
	}

	function getTagDetails($tagIds){

		$this->initiateModel('read');	

		if(empty($tagIds))
			return false;

		$queryCmd = "SELECT id,tags FROM tags WHERE status='live' AND id IN ( ? )";

		$rs = $this->dbHandle->query($queryCmd, array($tagIds))->result_array();

		$result = array();
		foreach($rs as $value){
			$result[$value['id']] = $value;
		}

		return $result;
	}

	function getThreadsViewCount($threadIdList){
		if(empty($threadIdList))
			return array();

		$this->initiateModel('read');	

		$queryCmd = "SELECT msgId,msgTxt,viewCount FROM messageTable WHERE msgId IN ( ? )";

		$rs = $this->dbHandle->query($queryCmd, array($threadIdList))->result_array();

		$result = array();
		foreach($rs as $value){
			$result[$value['msgId']] = $value;
		}

		return $result;
	}

	function getThreadsDetails($threadIdList){

		if(empty($threadIdList))
			return array();

		$this->initiateModel('read');	

		$queryCmd = "SELECT msgId,viewCount,msgCount FROM messageTable WHERE msgId IN ( ? )";

		$rs = $this->dbHandle->query($queryCmd, array($threadIdList))->result_array();

		$result = array();
		foreach($rs as $value){
			$result[$value['msgId']] = $value;
		}

		return $result;
	}
	
	function getDiscussionsCommentCount($threadIdList){

		if(empty($threadIdList))
			return array();

		$this->initiateModel('read');	

		$queryCmd = "SELECT threadId,msgId FROM messageTable WHERE threadId = parentId AND threadId IN ( ? ) AND fromOthers = 'discussion'";

		$rs = $this->dbHandle->query($queryCmd, array($threadIdList))->result_array();

		$result = array();
		foreach($rs as $value){
			$result[$value['threadId']] = $value['msgId'];
		}


		$queryCmd = "SELECT count(*) as count, threadId FROM messageTable WHERE parentId IN ( ? ) 
					 AND threadId IN ( ? ) 
					 AND fromOthers = 'discussion'
					 AND status IN ('live','closed')
					 GROUP BY threadId";

		$rs = $this->dbHandle->query($queryCmd, array($result, $threadIdList) )->result_array();

		$result = array();
		foreach($rs as $value){
			$result[$value['threadId']] = $value['count'];
		}
		return $result;
	}
	
	function getListURLData($urlString = NULL) {
		if(empty($urlString)) {
			return false;
		}
		$this->initiateModel('read');
		$queryCmd 	= "SELECT * FROM old_list_urls WHERE url_string = ? AND status = 'live' LIMIT 1";
		$resultSet 	= $this->dbHandle->query($queryCmd, array($urlString))->result_array();
		$result = array();
		if(!empty($resultSet)){
			$result = $resultSet[0];
		}
		return $result;
	}

	function getLdbIdsWithSameName() {
		$this->initiateModel('read');
		$queryCmd 	= "Select DISTINCT t1.SpecializationName, t1.CourseName, GROUP_CONCAT(t2.SpecializationId ORDER BY t2.SpecializationId SEPARATOR ' ') as ids ".
						"from tCourseSpecializationMapping t1, tCourseSpecializationMapping t2 ".
						"where ((t1.CourseName = t2.CourseName AND LOWER(t1.SpecializationName) = 'all' AND LOWER(t2.SpecializationName) = 'all') ".
						"OR (t1.SpecializationName = t2.SpecializationName) AND LOWER(t1.SpecializationName) != 'all') ".
						"AND t1.CourseName != 'All' ".
						"AND t1.Status = 'live' AND t2.Status='live' ".
						"AND t1.scope = 'india' AND t2.scope = 'india' ".
						"Group by t1.SpecializationId";
		$resultSet 	= $this->dbHandle->query($queryCmd)->result_array();
		foreach ($resultSet as $key => $value) {
			if(strtolower($value['SpecializationName']) == 'all') {
				$result[trim($value['CourseName'])] = $value['ids'];
			} else {
				$result[trim($value['SpecializationName'])] = $value['ids'];
			}
		}
		return $result;
	}

	public function getInstituteSynonyms($instId){
		$this->initiateModel('read');
		$this->dbHandle->where(array('instituteId' => $instId,'status' => 'live'));
		$this->dbHandle->select('synonyms','acronyms');
		$query = $this->dbHandle->get('instituteRelatedKeywords');
		$returnarr = array();
		if($query->num_rows() > 0){
			$returnarr = $query->row_array();
		}
		return $returnarr;
	}

	function getDiscussionsCommentsCount($discussionIds = array()){

		if(empty($discussionIds))
			return array();
		
		$this->initiateModel('read');	

		$queryCmd = "SELECT mt.threadId, COUNT( * ) AS cnt
					FROM messageTable AS mt
					WHERE mt.threadId
					IN ( ".implode(",", $discussionIds)." ) 
					AND mt.parentId != mt.threadId
					AND mt.parentId >0
					AND mt.parentId = mt.mainAnswerId
					AND mt.fromOthers =  'discussion'
					AND mt.status
					IN ('live',  'closed')
					GROUP BY mt.threadId";

		$rs = $this->dbHandle->query($queryCmd)->result_array();

		$result = array();
		foreach($rs as $value){
			$result[$value['threadId']] = $value['cnt'];
		}

		return $result;
	}

	public function getExamsDataForIndexing() {
		$this->initiateModel('read');
		$sql = "SELECT DISTINCT em.id, em.name FROM exampage_main em ".
			   "INNER JOIN exampage_master ems ON ems.exam_id = em.id and ems.status = 'live' ".
			   "WHERE em.status = 'live' ";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}

	public function getCareersDataForIndexing() {
		$this->initiateModel('read');
		 // select careerId,name from CP_CareerTable where status='live'
		$sql = "SELECT DISTINCT careerId as id,name FROM CP_CareerTable ct ".
			   "WHERE status = 'live' ";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}


	public function getStreamsDataForIndexing(){
		$this->initiateModel('read');
		$sql = "SELECT stream_id, name, synonym FROM streams WHERE status = 'live' and stream_id NOT IN (21) ";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}

	public function getSubStreamsDataForIndexing(){
		$this->initiateModel('read');
		$sql = "SELECT substream_id, name, synonym FROM substreams WHERE status = 'live'";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}

	public function getSpecializationDataForIndexing(){
		$this->initiateModel('read');
		$sql = "SELECT specialization_id, name, synonym FROM specializations WHERE status = 'live'";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;	
	}

	public function getInstituteDataForIndexing($offset,$batchSize,$order,$indexForQer=true){
		$this->initiateModel('read');
		$result = array();
		$sql = "";
		if($indexForQer){
			/*	$sql = "SELECT a.listing_id as ins, a.name, a.synonym, b.website_url FROM".
				"   shiksha_institutes a LEFT JOIN shiksha_institutes_locations b".
				" ON (a.listing_id = b.listing_id and b.listing_type = 'institute')".
				" WHERE a.status = 'live' and b.is_main = 1 and b.status = 'live' and ".
				"(a.is_dummy = 0 OR a.is_dummy is NULL) ORDER BY ".
				"a.listing_id $order limit $offset,$batchSize";*/

			$sql = "SELECT a.name,a.listing_id AS institute_id,a.synonym,a.abbreviation
					FROM shiksha_institutes a 
					JOIN shiksha_institutes_locations b 
					ON (a.listing_id = b.listing_id AND b.is_main = 1) 
					JOIN shiksha_listings_contacts c 
					ON (c.listing_location_id = b.listing_location_id AND c.listing_id = a.listing_id AND c.listing_type IN('institute','university'))
					WHERE a.status = 'live' AND b.status = 'live' AND c.status = 'live'
					ORDER BY a.listing_id $order limit ?, ?";
			
			$params[] = $offset;
			$params[] = $batchSize;
		}
		
		$query = $this->dbHandle->query($sql, $params);

		if($query->num_rows() > 0) {
			$result = $query->result_array();
		}
		return $result;
	}

	public function getPopularGroupsDataForIndexing(){
		
		$this->initiateModel('read');
		$result = array();
		$sql = "SELECT popular_group_id, name, synonym FROM popular_groups where status = 'live'";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}

	public function getDataForCertificateProviders(){
		$this->initiateModel('read');
		$result = array();
		$sql = "SELECT certificate_provider_id, name, synonym FROM certificate_providers where status = 'live'";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;	
	}

	public function getBaseCoursesDataForIndexing(){
		$this->initiateModel('read');
		$result = array();
		$sql = "SELECT base_course_id, name, synonym FROM base_courses where status = 'live' and is_dummy = 0 and base_course_id NOT IN (145, 75, 153, 155, 163)";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;		
	}

	public function testData() {
		$this->initiateModel('read');
		$sql = "select distinct city_name from countryCityTable where countryId=2 and enabled=0;";
		$result = $this->dbHandle->query($sql)->result_array();
		
		foreach ($result as $key => $value) {
			$cities[] = $value['city_name'];
		}

		$sql = "select distinct synonym, listing_id from shiksha_institutes where ";
		foreach ($result as $key => $value) {
			$synSql[] = " lower(synonym) like '%".strtolower($value['city_name'])."%' ";
		}
		$sql = $sql.implode(' OR ', $synSql);
		$result = $this->dbHandle->query($sql)->result_array();

		$synMasterArr = array();
		foreach ($result as $key => $value) {
			$instituteId = $value['listing_id'];
			$synArr = explode(';', $value['synonym']);
			
			foreach ($synArr as $key => $value2) {
				foreach ($cities as $key => $city) {
					if (strpos($value2, $city) !== false) {
						if(!in_array($value2, $synMasterArr)) {
							$synMasterArr[] = $value2;
						}
						if(!in_array($instituteId, $expectedResults[$value2])) {
							$expectedResults[$value2][] = $instituteId;
						}
					}
				}
			}
		}
		
		return array('syns'=>$synMasterArr, 'expIds' => $expectedResults);
	}
}

?>

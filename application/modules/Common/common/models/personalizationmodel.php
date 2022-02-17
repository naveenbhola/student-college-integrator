<?php
	class personalizationmodel extends MY_Model {
		private $dbHandle;
		private $dbHandleMode;
		
		function __construct(){
			parent::__construct('AnA');
		}
		
		private function initiateModel($mode = "write"){
			if($this->dbHandle && $this->dbHandleMode == 'write')
				return;
		
			$this->dbHandleMode = $mode;
			$this->dbHandle = NULL;
			if($mode == 'read') {
				$this->dbHandle = $this->getReadHandle();
			} else {
				$this->dbHandle = $this->getWriteHandle();
			}
		}
		
		/* public function getBasicInformationOfThreadActions($upvoteMessageIds = array(), $followMessageIds = array(), $threadMessageId = array(), $threadIds = array(), $filter = 'home'){
			$result = array();
			try {
				$this->initiateModel('read');
				
				// first get all threads details based on type.
				$sql = "SELECT threadId, fromOthers as threadType, creationDate as actionTime FROM messageTable "
						." WHERE msgId IN(".implode(",", $threadIds).") AND status IN('live','closed') ";
				if ($filter == 'unanswered'){
					$sql .= " AND fromOthers = 'user' AND msgCount = 0 ";
				}elseif ($filter == 'discussion'){
					$sql .= " AND fromOthers = 'discussion' ";
				}
				
				$threadDetails = array();
				$resultSet = $this->dbHandle->query($sql)->result_array();
				foreach ($resultSet as $data){
					$threadDetails[$data['threadId']]['threadType'] = $data['threadType'];
					$threadDetails[$data['threadId']]['actionTime'] = $data['actionTime'];
				}
				
				
				// now remove all messageIds which don't have threadIds present in threadDetails. This way these will be filtered based on filter provided.
				$upvoteMessageIds	= array_intersect_key($upvoteMessageIds, $threadDetails);
				$followMessageIds	= array_intersect_key($followMessageIds, $threadDetails);
				$threadMessageId	= array_intersect_key($threadMessageId, $threadDetails);
				
				// also remove from threadMessageId which are already captured in threadDetails
				foreach ($threadMessageId as $key=>$value){
					if(array_key_exists($value, $threadDetails)){
						unset($threadMessageId[$key]);
					}
				}
				$upvoteIds		= array();
				$upvoteUserIds	= array();
				$followIds		= array();
				$followUserIds	= array();
				foreach ($upvoteMessageIds as $key => $data){
					$upvoteIds[$key]		= $data['msgId'];
					$upvoteUserIds[$key]	= $data['userId'];
				}
				foreach ($followMessageIds as $key => $data){
					$followIds[$key]		= $data['msgId'];
					$followUserIds[$key]	= $data['userId'];
				}
				unset($sql);
				if(count($upvoteIds) > 0){
					$sql = " SELECT productId as messageId, userId, digTime as actionTime FROM digUpUserMap WHERE productId IN(".implode(",", $upvoteIds).") AND userId IN(".implode(",", $upvoteUserIds).") AND product='qna' AND digFlag=1";
				}
				if(count($followIds) > 0){
					$sql .= (strlen($sql) > 0)?" UNION ":"";
					$sql .= " SELECT entityId as messageId, userId, creationTime as actionTime FROM tuserFollowTable WHERE entityId IN(".implode(",", $followIds).") AND userId IN(".implode(",", $followUserIds).") AND entityType IN('question','discussion') AND status IN('follow') ";
				}
				if(count($threadMessageId) > 0){
					$sql .= (strlen($sql) > 1)?" UNION ":"";
					$sql .= " SELECT msgId as messageId, userId, creationDate as actionTime FROM messageTable WHERE msgId IN(".implode(",", $threadMessageId).") AND fromOthers IN('user','discussion') AND status IN('live','closed') ";
				}
				$resultSet = array();
				if(!empty($sql)){
					$resultSet = $this->dbHandle->query($sql)->result_array();
				}
				
				foreach ($resultSet as $data){
					$result[$data['messageId']]['messageId']	= $data['messageId'];
					$result[$data['messageId']]['actionTime'] 	= $data['actionTime'];
					
					// check for threadId from different buckets and when found assign threadType from threadDetails array
					if(!($result[$data['messageId']]['threadType'] = $threadDetails[array_search($data['messageId'], $upvoteIds)]['threadType'])){
						if(!($result[$data['messageId']]['threadType'] = $threadDetails[array_search($data['messageId'], $followIds)]['threadType'])){
							if(!($result[$data['messageId']]['threadType'] = $threadDetails[array_search($data['messageId'], $threadMessageId)]['threadType'])){
								$result[$data['messageId']]['messageId']	= $threadDetails[$data['messageId']]['messageId'];
								$result[$data['messageId']]['actionTime']	= $threadDetails[$data['messageId']]['actionTime'];
								$result[$data['messageId']]['threadType']	= $threadDetails[$data['messageId']]['threadType'];
							}
						}
					}
				}
				// now add all those threads to result array which were not fetched in UNION query. Basically this will be the case when thread is posted
				foreach ($threadDetails as $key => $value){
					if(!(array_key_exists($key, $result) && isset($result[$key]['threadType']))){
						$result[$key]['messageId']	= $key;
						$result[$key]['actionTime']	= $value['actionTime'];
						$result[$key]['threadType']	= $value['threadType'];
					}
				}
				
			} catch (Exception $e) {
				error_log(" :: Error Occured ::  class:personalizationmodel function:getThreadBasicDetails  Message:".$e->getMessage()." trace:".$e->getTrace());
			}
			return $result;
		} */
		
		public function getThreadTypeForThreadsBasedOnFilter($threadIds = array()/* , $filter = 'home' */){
			$result = array();
			try{
				Contract::mustBeNonEmptyArrayOfIntegerValues($threadIds, 'Thread IDs');
				$this->initiateModel('read');
				
				$sql = "SELECT msgId, threadId, fromOthers as threadType, msgCount FROM messageTable "
						." WHERE msgId IN (?) AND status IN('live','closed') ";
						
						/*if ($filter == 'unanswered'){
							$sql .= " AND fromOthers = 'user' AND msgCount = 0 ";
						}elseif ($filter == 'discussion'){
							$sql .= " AND fromOthers = 'discussion' ";
						}
						*/
						
						$resultSet = $this->dbHandle->query($sql, array($threadIds))->result_array();
						foreach ($resultSet as $data){
							$result[$data['msgId']]['threadId']		= $data['threadId'];
							$result[$data['msgId']]['threadType']	= $data['threadType'];
							$result[$data['msgId']]['msgCount']		= $data['msgCount'];
						}
				
			}catch (Exception $e){
				error_log(" :: Exception Occured :: ".$e->getMessage().'  :: '.$e->getTrace());
			}
			
			return $result;
		}
		
		public function getUserIdsOnUpvotedMsgIds($msgIds = array()){
			$result = array();
			try {
				Contract::mustBeNonEmptyArrayOfIntegerValues($msgIds, 'Message IDs');
				$sql = "SELECT productId, group_concat(userId) AS userIds FROM digUpUserMap"
						." WHERE productId IN (?)"
						." AND product = 'qna' AND digFlag = 1 AND digUpStatus = 'live'"
						." GROUP BY 1";
				$this->initiateModel('read');
				$resultSet = $this->dbHandle->query($sql, array($msgIds))->result_array();
				foreach ($resultSet as $data){
					$result[$data['productId']] = explode(",", $data['userIds']);
				}
			} catch (Exception $e) {
				error_log(" :: Exception Occured :: ".$e->getMessage().'  :: '.$e->getTrace());
			}
			
			return $result;
		}
		
		
		public function getStaticContentForUsers(){
			$sql = "(SELECT `msgId`,`threadId`,`creationDate`,fromOthers from messageTable "
					."where status = 'live' and fromOthers = 'user' and `mainAnswerId` = -1 and msgTxt != '' "
							."order by creationDate desc limit 200) "
									."UNION "
					."(select `msgId`,`threadId`,`creationDate`,fromOthers from messageTable "
							."where status = 'live' and fromOthers = 'discussion' and `mainAnswerId` = 0 and msgTxt != '' "
									."order by creationDate desc limit 200)";
			$this->initiateModel('read');
			$resutSet = $this->dbHandle->query($sql)->result_array();
			$result = array();
			$result['discussion'] = array();
			$result['question']	= array();
			
			foreach ($resutSet as $data){
				if($data['fromOthers'] == 'user'){
					$result['question'][] = $data['threadId'];
				}else{
					$result['discussion'][] = $data['threadId'];
				}
			}
			
			return $result;
		}
		
		public function getTopCommentAnswerForThread($threadIds = array()){
			$this->initiateModel('read');
			/* $sql = "SELECT mt.msgId, mt.creationDate, sum(dum.digFlag) as upvotes "
					." from messageTable mt left join digUpUserMap dum ON(mt.msgId = dum.productId) "
					." where mt.status = 'live' and mt.threadId IN (".implode(",", $threadIds).") and dum.digFlag=1 AND "
					." (mt.parentId=mt.threadId AND mt.fromOthers='user') OR (mt.mainAnswerId=mt.parentId AND fromOthers='discussion') "
					." group by mt.msgId order by 2 desc,3 desc"; */
			
			$sql = " SELECT mt.threadId, mt.msgId, mt.fromOthers, mt.creationDate, SUM( IF(dum.digFlag is null,0,dum.digFlag) ) AS cnt "
					." FROM messageTable mt"
					." LEFT JOIN digUpUserMap dum ON ( mt.msgId = dum.productId )" 
					." WHERE mt.parentId > 0"
					." AND mt.status IN ('live','closed' AND dum.digUpStatus = 'live') AND"
					// below checks to get questions and discussions children
					." ((mt.parentId=mt.threadId AND fromOthers = 'user') OR (mt.parentId=mt.mainAnswerId AND fromOthers = 'discussion'))"
					." AND mt.threadId IN (?) "
					." GROUP BY 1,2 "
					." ORDER BY 5 desc,4 desc";
			$resultSet = $this->dbHandle->query($sql, array($threadIds))->result_array();
			$result = array();
			foreach ($resultSet as $data){
				if(!isset($result[$data['threadId']]) || $data['cnt'] > $result[$data['threadId']]['upvoteCount']){
					$result[$data['threadId']]['answerCommentId']	= $data['msgId'];
					$result[$data['threadId']]['upvoteCount']		= $data['cnt'];
					$result[$data['threadId']]['threadType']		= $data['fromOthers'];
					$result[$data['threadId']]['creationDate']		= $data['creationDate'];
				}
			}
			return $result;
		}
		
		/**
		 * @param array $threadQualityArray array(threadId1 => score1,threadId2 => score2)
		 * 
		 */
		public function updateThreadQuality($threadQualityArray = array()){
			try {
				Contract::mustBeNonEmptyArrayOfIntegerValues($threadQualityArray, 'Thread Quality Array');
				$this->initiateModel('read');
				$sql = "SELECT threadId,fromOthers FROM messageTable WHERE msgId IN(".implode(",", array_keys($threadQualityArray)).") ";
				$resultSet = $this->dbHandle->query($sql)->result_array();
				unset($sql);
				$sql = "INSERT INTO threadQualityTable (threadId,threadType,qualityScore,lastUpdate) "
						." VALUES ";
				$sqlInsertValues = "";
				$updateDate = date('Y-m-d H:i:s');
				foreach ($resultSet as $data){
					if(isset($threadQualityArray[$data['threadId']])){
						$threadType = ($data['fromOthers'] == 'user')?'question':$data['fromOthers'];
						$sqlInsertValues .= "(".$data['threadId'].",'".$threadType."',".$threadQualityArray[$data['threadId']].",'".$updateDate."'),";
					}
				}
				if(empty($sqlInsertValues)){
					return;
				}
				$sqlInsertValues = rtrim($sqlInsertValues,',');
				$sql .= $sqlInsertValues." ON DUPLICATE KEY UPDATE qualityScore=VALUES(qualityScore),lastUpdate=VALUES(lastUpdate) ";
				
				$this->initiateModel('write');
				$this->dbHandle->query($sql);
				
				return TRUE;
				
			} catch (Exception $e) {
				error_log(' :: Exception Ocurred :: Message:'.$e->getMessage().' :: Traces:'.$e->getTrace());
			}
		}

		public function getThreadType($threadIds = array()){
			$result = array();
			try {
				Contract::mustBeNonEmptyArrayOfIntegerValues($threadIds, 'Thread Ids');
				$this->initiateModel('read');
				$sql = "SELECT msgId,fromOthers FROM messageTable WHERE msgId IN (?) AND status IN ('live','closed') ";
				$resultArray = $this->dbHandle->query($sql, array($threadIds))->result_array();
				foreach ($resultArray as $data){
					$result[$data['msgId']]['msgId'] = $data['msgId'];
					$result[$data['msgId']]['fromOthers'] = $data['fromOthers'];
				}
			} catch (Exception $e) {
				error_log(' :: Exception Ocurred :: Message:'.$e->getMessage().' :: Traces:'.$e->getTrace());
			}
			return $result;
		}
		
		public function getTagsParents($tagIds = array()){
			$result = array();
			try {
				Contract::mustBeNonEmptyArrayOfIntegerValues($tagIds, 'Tag Ids Array');
				$this->initiateModel('read');
				$sql = "SELECT tag_id, parent_id FROM tags_parent WHERE status = 'live' AND tag_id IN (?) GROUP BY 1,2 ";
				$resultSet = $this->dbHandle->query($sql, array($tagIds))->result_array();
				foreach ($resultSet as $data){
					$result[$data['tag_id']][] = $data['parent_id'];
				}
			} catch (Exception $e) {
				error_log(' :: Exception Ocurred :: Message:'.$e->getMessage().' :: Traces:'.$e->getTrace());
			}
			return $result;
		}
		
		public function getChildrenOfTags($tagIds = array(), $exclusionSetOfTags = array()){
			$result = array();
			try {
				Contract::mustBeNonEmptyArrayOfIntegerValues($tagIds, 'Tag Ids Array');
				$this->initiateModel('read');
				$sql = " SELECT tp.tag_id, tp.parent_id, t.tag_quality_score FROM tags_parent tp "
						." LEFT JOIN tags t ON (tp.tag_id = t.id AND t.status = 'live')  WHERE tp.status='live' "
						." AND tp.parent_id IN (?) AND t.tag_quality_score > 0 " 
						.((count($exclusionSetOfTags))?" AND tp.tag_id NOT IN(".implode(",", $exclusionSetOfTags).")":"")
						." GROUP BY 1,2 ORDER BY 3 DESC ";
				//echo 'sql : '.$sql;die;
				$resultSet = $this->dbHandle->query($sql, array($tagIds))->result_array();
				foreach ($resultSet as $data){
					$result[$data['parent_id']][] = $data['tag_id'];
				}
			} catch (Exception $e) {
				error_log(' :: Exception Ocurred :: Message:'.$e->getMessage().' :: Traces:'.$e->getTrace());
			}
			return $result;
		}
		
		public function getTagBasedOnEntity($entityArray = array()){
			$result = array();
			try {
				Contract::mustBeNonEmptyArray($entityArray, 'Entity');
				$this->initiateModel('read');
				$sql = "SELECT id, tag_entity, tag_quality_score FROM tags WHERE status = 'live' "
						." AND tag_entity IN (?) "
						." ORDER BY 3 DESC ";
				$resultSet = $this->dbHandle->query($sql, array($entityArray))->result_array();
				foreach($resultSet as $data){
					$result[$data['tag_entity']][] = $data['id'];
				}
			} catch (Exception $e) {
				error_log(' :: Exception Ocurred :: Message:'.$e->getMessage().' :: Traces:'.$e->getTrace().' :: Class : personalizationmodel Member: getThreadBasedOnEntity');
			}
			return $result;
		}
		
		public function getTagNames($tagIds = array()){
			$result = array();
			try {
				Contract::mustBeNonEmptyArrayOfIntegerValues($tagIds, 'Tag IDs');
				$this->initiateModel('read');
				$sql = " SELECT t.id, t.tags,count(tuf.id) as followers from tags t "
						." LEFT JOIN tuserFollowTable tuf use index (idx_composite) ON(t.id=tuf.entityId AND tuf.entityType = 'tag' AND tuf.status='follow') "
						." WHERE t.status='live' AND t.id IN (".implode(",", $tagIds).") "
						." GROUP BY 1 ";
				//echo 'sql: '.$sql;
				$resultSet = $this->dbHandle->query($sql)->result_array();
				foreach ($resultSet as $data){
					//$key = array_search($data['id'], $tagIds);
					//if($key >= 0){
						$result[$data['id']] = array(	'tagId'		=> $data['id'],
														'tagName'	=> $data['tags'],
														'followers'	=> $data['followers']
													);
					//}
				}
				//ksort($result);
			} catch (Exception $e) {
				error_log(' :: Exception Ocurred :: Message:'.$e->getMessage().' :: Traces:'.$e->getTrace().' CLASS : personalizationmodel Member: getTagNames');
			}
			return $result;
		}
		
		public function getThreadsAttachedToTag($tagIds = array()){
			$result = array();
			try {
				Contract::mustBeNonEmptyArrayOfIntegerValues($tagIds, 'TAG IDs');
				$this->initiateModel('read');
				$sql = "SELECT tag_id, content_id FROM tags_content_mapping WHERE status = 'live' AND content_type IN('question','discussion') AND tag_id IN (?) ";
				$resultSet = $this->dbHandle->query($sql, array($tagIds))->result_array();
				foreach ($resultSet as $data){
					$result[$data['tag_id']][] = $data['content_id'];
				}
			} catch (Exception $e) {
				error_log(' :: Exception Ocurred :: Message:'.$e->getMessage().' :: Traces:'.$e->getTrace().' CLASS : personalizationmodel Member: getThreadsAttachedToTag');
			}
			return $result;
		}

		public function getUnansweredQuestionsReatedToTags($tags = array() , $startIndex = 0, $totalRecords = 10, $exclusionList = array(), $excludeQuestionByUsers = array()){
			$result = array();
			try {
				//Contract::mustBeNonEmptyArrayOfIntegerValues($tags, "TAG IDs");
				if (!empty($tags)){
					$sql = " SELECT mt.msgId, mt.creationDate, mt.userId, tcm.tag_id, IF(tcm.tag_id IN(".implode(",", $tags)."),1,0) as tagFlag from messageTable  mt USE INDEX (creationDateIndex)"
							." LEFT JOIN tags_content_mapping tcm ON(tcm.content_id = mt.msgId AND tcm.status = 'live' AND tcm.content_type = 'question' AND tcm.tag_id IN(".implode(",", $tags)."))"
							." WHERE mt.msgCount = 0 AND mt.parentId = 0 AND mt.fromOthers = 'user'" 
							." AND mt.status = 'live' ";
							//." AND tcm.tag_id IN (".implode(",", $tags).") ";
				}else{
					$sql = " SELECT mt.msgId, mt.creationDate, mt.userId FROM messageTable mt USE INDEX (creationDateIndex)"
							." WHERE mt.msgCount = 0 AND mainAnswerId = -1 "
							." AND mt.fromOthers = 'user' AND mt.status = 'live' ";
				}
				// date check. Get data not older than 2 months
				$sql .= " AND mt.creationDate >= '".date('Y-m-d', strtotime(' - 2 months',time()))."' ";
				if(!empty($exclusionList)){
					$sql .= " AND mt.msgId NOT IN(".implode(",", $exclusionList).") ";
				}
				if(!empty($excludeQuestionByUsers)){
					$sql .= " AND mt.userId NOT IN(".implode(",", $excludeQuestionByUsers).") ";
				}
				$sql .= " GROUP BY 1,2,3 ";
				
				if(!empty($tags)){
					$sql .= " ORDER BY tagFlag desc, mt.creationDate desc ";
				}else{
					$sql .= " ORDER BY mt.creationDate desc ";
				}
				$sql .= " LIMIT ".$startIndex.", ".$totalRecords;
				//echo 'sql: '.$sql;die;
				$this->initiateModel('read');
				$resultSet = $this->dbHandle->query($sql)->result_array();
				foreach ($resultSet as $data){
					$result[$data['msgId']]['msgId']			= $data['msgId'];
					$result[$data['msgId']]['creationDate']		= $data['creationDate'];
					$result[$data['msgId']]['userId']			= $data['userId'];
					$result[$data['msgId']]['threadType']		= 'user';
					if(isset($data['tag_id'])){
						$result[$data['msgId']]['tagId']		= $data['tag_id'];
					}else{
						$result[$data['msgId']]['tagId']		= -1;
					}
				}
			} catch (Exception $e) {
				error_log(' :: Exception Ocurred :: Message:'.$e->getMessage().' :: Traces:'.$e->getTrace().' CLASS : personalizationmodel Member: getUnansweredQuestions');
			}
			return $result;
		}
		
		public function getFollowTypeData($userIds = array(), $entityType, $status, $entityIdFilter = array()){
			$result = array();
			$entityIdFilter = array_filter($entityIdFilter);
			try {
				Contract::mustBeNonEmptyArrayOfIntegerValues($userIds, 'USER IDs');
				if(!in_array($entityType, array('tag','user','question','discussion')) || !in_array($status, array('follow','unfollow'))){
					return $result;
				}
				$sql = " SELECT userId, entityId FROM tuserFollowTable "
						." WHERE entityType = '".$entityType."' AND status = '".$status."' "
						." AND userId IN (".implode(",", $userIds).") ";
				if(!empty($entityIdFilter)){
					$sql .= " AND entityId IN (".implode(",", $entityIdFilter).") ";
				}
				
				$this->initiateModel('read');
				$resultSet = $this->dbHandle->query($sql)->result_array();
				foreach ($resultSet as $data){
					$result[$data['userId']][] = $data['entityId'];
				}
				
			} catch (Exception $e) {
				error_log(' :: Exception Ocurred :: Message:'.$e->getMessage().' :: Traces:'.$e->getTrace().' CLASS : personalizationmodel Member: getFollowTypeData');
			}
			return $result;
		}
		
		public function getUsersFollowingTags($tagIds = array()){
			$result = array();
			try {
				Contract::mustBeNonEmptyArrayOfIntegerValues($tagIds, 'TAG IDs');
				$sql = "SELECT userId, entityId FROM tuserFollowTable WHERE entityType = 'tag' AND status = 'follow' "
						." AND entityId IN (?) ";
				$this->initiateModel('read');
				$resultSet = $this->dbHandle->query($sql, array($tagIds))->result_array();
				foreach ($resultSet as $data){
					$result[$data['entityId']][] = $data['userId'];
				}
			} catch (Exception $e) {
				error_log(' :: Exception Ocurred :: Message:'.$e->getMessage().' :: Traces:'.$e->getTrace().' CLASS : personalizationmodel Member: getUsersFollowingTags');
			}
			return $result;
		}
		
		public function getTagIds($tagName = array(), $tagEntityName = array()){
			$result = array();
			try{
				$sql = "SELECT id FROM tags WHERE status = 'live'";
				if(is_array($tagName) && !empty($tagName)){
					$sql .= " AND tags IN('".implode("','", $tagName)."') ";
				}elseif (is_array($tagEntityName) && !empty($tagEntityName)){
					$sql .= " AND tag_entity IN('".implode("','", $tagEntityName)."')";
				}else {
					return $result;
				}
				$this->initiateModel('read');
				$resultSet = $this->dbHandle->query($sql)->result_array();
				foreach($resultSet as $value){
					$result[] = $value['id'];
				}
			} catch(Exception $e){
				error_log(' :: Exception Ocurred :: Message:'.$e->getMessage().' :: Traces:'.$e->getTrace().' CLASS : personalizationmodel Member: getTagIds');
			}
			return $result;
		}
		
		public function insertRedisBufferStreamInDB($buffer){
			$this->initiateModel('write');
			$data	=	array(	'streamCommand'	=> $buffer,
								'status'		=> 'unprocessed',
								);
			$this->dbHandle->insert('redisStreamCommands', $data);
			return ;
		}

		public function insertStaticThreadsIntoDB($staticThreadIds){
			if(empty($staticThreadIds)){
				return TRUE;
			}
			$insertData = array();
			$i=0;
			foreach ($staticThreadIds as $value){
				$data = explode(':', $value);
				if(in_array($data[0], array('discussion','question')) && $data[1] == 'thread' && $data[2] > 0){
					$insertData[] = array(	'threadId'		=>	$data[2],
											'threadType'	=>	$data[0],
											'displayOrder'	=>	$i++,
											'status'		=>	'live',
											'createdAt'		=>	date('Y-m-d H:i:s')
										);
				}
			}
			$this->initiateModel('write');
			$this->dbHandle->trans_start();
			$sql = "UPDATE staticThreadsHomeFeed SET status = ? WHERE status = ? ";
			$this->dbHandle->query($sql , array('history','live'));
			$this->dbHandle->insert_batch('staticThreadsHomeFeed', $insertData);
			$this->dbHandle->trans_complete();
			return TRUE;
		}
		
		public function getStaticThreadFromDB($start, $stop){
			if($start < 0){
				return array();
			}
			
			$sql = " SELECT threadId, threadType, displayOrder FROM staticThreadsHomeFeed WHERE status = 'live'"
					." AND displayOrder >= ".$start." ORDER BY 3 ASC";
			if($stop >= 0){
				$sql .= " LIMIT ".$stop;
			}
			//echo 'sql : '.$sql;
			$result = array();
			$this->initiateModel('read');
			$resultSet = $this->dbHandle->query($sql)->result_array();
			foreach($resultSet as $data){
				$result[] = $data['threadType'].':thread:'.$data['threadId'];
			}
			return $result;
		}
		
		/*
		 * Purpose	: TO get User Ids who have generated content recently
		 */
		public function getUserIdsGeneratedConetentRecently($recentLowerDate, $recentUpperDate, $offset = 0, $limit = 500){
			if(empty($recentUpperDate) || empty($recentLowerDate)){
				return array();
			}elseif (!(is_int($limit) && is_int($offset))){
				return array();
			}
			$sql = "SELECT SQL_CALC_FOUND_ROWS a.userId, a.threadId FROM shiksha.messageTable a JOIN shiksha.tuserflag b ON (b.userId = a.userId) WHERE a.status IN ('live','closed')"
					." AND ((a.fromOthers = 'user' AND a.parentId = a.threadId) OR (a.fromOthers = 'discussion' AND a.parentId = a.mainAnswerId))"
					." AND b.unsubscribe = '0' AND a.creationDate >= '".$recentLowerDate."' AND a.creationDate < '".$recentUpperDate."'"
							." LIMIT ".$offset.",".$limit;
							$this->initiateModel('read');
							//echo 'sql : '.$sql;die;
							$resultSet['data'] = $this->dbHandle->query($sql)->result_array();
							$resultSet['rows'] = $this->dbHandle->query("SELECT found_rows() as rows")->row_array();
							return $resultSet;
		}
		
		/*
		 * Purpose	:	TO get Thread Ids generated recently
		 */
		public function getThreadGeneratedRecently($recentLowerDate, $recentUpperDate, $offset = 0, $limit = 500){
			if(empty($recentUpperDate) || empty($recentLowerDate)){
				return array();
			}elseif (!(is_int($limit) && is_int($offset))){
				return array();
			}
			// Modified the query // added AND  listingTypeId = 0 in where clause // MAB-1345
			$sql = "SELECT SQL_CALC_FOUND_ROWS creationDate, userId, msgTxt, threadId, msgCount FROM shiksha.messageTable WHERE status = 'live' AND mainAnswerId = -1"
					." AND  listingTypeId = 0 AND fromOthers = 'user' AND creationDate >= ? AND creationDate < ? "
							." ORDER BY 2 ASC, 3 ASC "
									." LIMIT ".$offset." , ".$limit;
									//echo 'sql: '.$sql;die;
									$this->initiateModel('read');
									$resultSet['data'] = $this->dbHandle->query($sql, array($recentLowerDate,$recentUpperDate))->result_array();
									$resultSet['rows'] = $this->dbHandle->query("SELECT FOUND_ROWS() as rows")->row_array();
									return $resultSet;
		}
		
		/*
		 * Purpose : Insert DailyAsk&Answer Mailer data in dailAsknAnswerMailerLog table
		 */
		public function logDailyAskAndAnswerMailer($data = array()){
			if(empty($data)){
				return;
			}
			$this->initiateModel('write');
			$insertData = array();
			foreach ($data as $userId => $mailId){
				$temp = array(	'userId'		=> $userId,
						'mailId'		=> $mailId,
						'mailerSentDate'=> date('Y-m-d H:i:s')
				);
				$insertData[] = $temp;
				if(count($insertData) >= 200){
					$this->dbHandle->insert_batch('shiksha.dailyAsknAnswerMailerLog',$insertData);
					$insertData = array();
				}
			}
			if(count($insertData) > 0){
				$this->dbHandle->insert_batch('shiksha.dailyAsknAnswerMailerLog',$insertData);
			}
			return TRUE;
		}
		
		/**
         * 
		 * Purpose	:	Get UserIDs to whom DailyAsk&Answer mailer for a given date
		 */
		public function getUserIdsSentMailForDailyAskAndAnswerMailer($date){
			if(empty($date)){
				return array();
			}
			$result = array();
			$sql = "SELECT userId FROM shiksha.dailyAsknAnswerMailerLog WHERE date(mailerSentDate) = ? AND mailId > 0";

			$this->initiateModel('read');
			$resultSet	= $this->dbHandle->query($sql, array($date))->result_array();
			foreach ($resultSet as $data){
				$result[] = $data['userId'];
			}
			return $result;
		}

		/**
		* Function to get the list of User from LDBExclusion List
		* MAB-1345
		*/
		public function getLDBExclusionList(){
			$this->initiateModel('read');
			$sql = "SELECT distinct userId from shiksha.LDBExclusionList where status = 'live'";
			$query = $this->dbHandle->query($sql);
			$resultSet = $query->result_array();
			foreach ($resultSet as $data){
				$result[] = $data['userId'];
			}
			return $result;
		}
		
        /**
         * @author Abhinav Pandey
         * @param array $userIds haystack of userIds
         * @param string $days last n days for in-activity
         */
        public function getInActiveUsersFromGivenSet($userIds = array(), $days){
            if(empty($userIds) || empty($days) || !is_int($days) || $days <= 0){
                return FALSE;
            }
            $dateCheck = date('Y-m-d', strtotime('-'.$days.' days'));
            $userIdArrayLength	= count($userIds);
            $arrayPointer		= 0;
            $resultForWeb		= array();
            $resultForApp		= array();
            $result				= array();
            $this->initiateModel('read');
            while(TRUE){
            	$userIdToCheck = array_slice($userIds, $arrayPointer,1000);
            	// commented below code as it is found that it takes time because it scans lots of rows
            	/* $sql = "select userId, MAX(visitTime) as visitTime from pageview_tracking WHERE "
            			." userId IN(".implode(",", $userIdToCheck).") GROUP BY 1 HAVING  visitTime >= '".$dateCheck." 00:00:00' ";
            	$resultSet = $this->dbHandle->query($sql)->result_array();
            	error_log("inactive users cron user query : ".$sql);
            	foreach ($resultSet as $value){
            		$resultForWeb[] = $value['userId'];
            	} */
            	$resultForWeb = $this->getInActiveUsersFromElasticSearch($userIdToCheck,$days);
            	//$sql = "SELECT userId,lastActivityTime FROM appUserLastActivity WHERE userId IN(".implode(",", $userIdToCheck).") AND lastActivityTime < '".$dateCheck." 00:00:00' ";
            	if(!empty($resultForWeb)) {
	            	$sql = "SELECT userId,lastActivityTime FROM appUserLastActivity WHERE userId IN (?) AND lastActivityTime >= '".$dateCheck." 00:00:00' ";
	            	//error_log("ABHINAV@TEST SQL ".$sql);
	            	$resultSet = $this->dbHandle->query($sql, array($resultForWeb))->result_array();
	            	foreach ($resultSet as $value){
	            		$resultForApp[] = $value['userId'];
	            	}
	            }
            	
            	/* $inActiveUserIds        = array_diff($userIdToCheck, $resultForWeb, $resultForApp);
            	$result                 = array_merge($result, $inActiveUserIds); */
				$preResult = array_diff($resultForWeb, $resultForApp);
				$result    = array_merge($result, $preResult);
            	$resultForWeb	= array();
            	$resultForApp	= array();
            	$arrayPointer += 1000;
            	if($arrayPointer >= ($userIdArrayLength)){
            		break;
            	}
            }
            $result = array_values(array_unique($result));
            return $result;
        }
        
        /**
         * @author	Abhinav
         */
        public function getInActiveVisitorsFromGivenSet($visitorIds = array(), $days){
        	if(!is_array($visitorIds) || empty($visitorIds) || !is_int($days) || $days <= 0){
        		return FALSE;
        	}
        	$dateCheck = date('Y-m-d', strtotime('-'.$days.' days'));
        	$visitorIdArrayLength = count($visitorIds);
        	$arrayPointer = 0;
        	$result = array();
        	$this->initiateModel('read');
        	while(true){
        		/* $sql	=	"SELECT st.visitorId as visitorId, max(pv.visitTime) as visitTime FROM session_tracking st, pageview_tracking pv WHERE"
        					." st.visitorId IN('".implode("','", array_slice($visitorIds, $arrayPointer, 1000))."') AND st.sessionId = pv.sessionId"
        					." GROUP BY visitorId HAVING visitTime <  '".$dateCheck." 00:00:00' ";
        		$resultSet = $this->dbHandle->query($sql)->result_array();
        		error_log("inactive users cron visitor query : ".$sql);
        		foreach ($resultSet as $value){
        			$result[] = $value['visitorId'];

        		} */
				$tmpVisitorIds = array_slice($visitorIds, $arrayPointer, 1000);

        		$result = array_merge($result, $this->getInActiveVisitorsFromElasticSearch($tmpVisitorIds, $days));
        		$arrayPointer += 1000;
        		if($arrayPointer >= ($visitorIdArrayLength)){
        			break;
        		}
        	}
        	return $result;
        }
        
        /**
         * Insert Tag Affinity data for user in Database
         * @author	Abhinav
         */
        public function insertTagAffinityDataForUsers($entityTagAffinityData = array(), $entityType = ''){
        	if(!is_array($entityTagAffinityData) || empty($entityTagAffinityData) || !in_array($entityType, array('user','visitor'))){
        		return FALSE;
        	}
        	$insertBatchData = array();
        	$sql = "";
        	foreach($entityTagAffinityData as $entityId => $tagAffinityArray){
        		$sql .= ",('".$entityId."','".$entityType."','".json_encode($tagAffinityArray)."','live')";
        	}
        	$sql = substr($sql, 1);
        	$sql = "INSERT INTO tagAffinityForUser (entityId,entityType,affinity,status) VALUES ".$sql;
        	$sql .= " ON DUPLICATE KEY UPDATE affinity = VALUES(affinity),status=VALUES(status),lastUpdate=NOW()";
			//error_log("SQL : ".$sql);
        	$this->initiateModel('write');
        	$this->dbHandle->query($sql);
        	return TRUE;
        }
        
        public function getTopTagsFromDatabaseForInActiveUsers($userIds = array(), $userType){
        	if(!is_array($userIds) || empty($userIds) || !in_array($userType, array('user','visitor'))){
        		return array();
        	}

        	// make all userids as varchar
        	foreach ($userIds as $key=>$value) {
				$userIds[$key] = "".$value;
			}

        	$sql = "SELECT entityId,affinity FROM tagAffinityForUser "
        			."WHERE entityType = ? AND entityId IN (?) AND status = 'live'";
        	$this->initiateModel('read');
        	$resultSet = $this->dbHandle->query($sql, array($userType,$userIds))->result_array();
        	$result = array();
        	foreach($resultSet as $value){
        		$result[$value['entityId']] = $value['affinity'];
        	}
        	return $result;
        }
        
        public function markTagsAsHistoryForActiveUsers($userIds = array(), $userType){
        	if(!is_array($userIds) || empty($userIds) || !in_array($userType, array('user','visitor'))){
        		return FALSE;
        	}

        	// make all userids as varchar
        	foreach ($userIds as $key=>$value) {
				$userIds[$key] = "".$value;
			}

        	// Mark user as active
        	$sql	=	"UPDATE tagAffinityForUser SET status='history',lastUpdate=NOW() WHERE entityId IN (?)"
        				." AND entityType=? AND status='live' ";
        	$this->initiateModel('write');
        	$this->dbHandle->query($sql, array($userIds,$userType));
        	return TRUE;
        }
        
        public function getTopThreadsBasedOnTags($tagIds = array(), $userIdToAvoidContentFrom = 0){
        	if(!is_array($tagIds) || empty($tagIds)){
        		return array();
        	}
        	$dateCheck	= date('Y-m-d',strtotime(' - 10 days',time())).' 00:00:00';
        	
        	$sql1		=	"SELECT mt1.threadId AS threadId, MAX(mt2.msgId) AS msgId, tcm.tag_id AS tagId FROM messageTable mt1"
        					." INNER JOIN messageTable mt2 ON (mt1.threadId = mt2.threadId) INNER JOIN tags_content_mapping tcm ON (mt2.threadId = tcm.content_id)"
        					." WHERE tcm.creationTime >= '".$dateCheck."' AND mt1.status IN ('live' , 'closed') AND mt2.status IN ('live' , 'closed')"
        					." AND ((mt1.fromOthers = 'user' AND mt1.parentId = 0 AND mt1.mainAnswerId = - 1) OR (mt1.fromOthers = 'discussion' AND mt1.mainAnswerId = 0 AND mt1.parentId = mt1.threadId))"
        					." AND ((mt2.fromOthers = 'user' AND ((mt2.parentId = 0 AND mt2.mainAnswerId = - 1) OR (mt2.parentId = mt2.threadId AND mt2.mainAnswerId = 0)))"
        					." OR (mt2.fromOthers = 'discussion' AND ((mt2.mainAnswerId = 0 AND mt2.parentId = mt2.threadId) OR (mt2.mainAnswerId = mt2.parentId AND mt2.mainAnswerId > 0))))"
        					." AND tcm.status = 'live' ".(($userIdToAvoidContentFrom > 0 )?" AND mt2.userId != ".$userIdToAvoidContentFrom:"")." AND tcm.tag_id IN (".implode(",", $tagIds).") GROUP BY threadId LIMIT 200";
        	$this->initiateModel('read');
        	$resultSet1 = $this->dbHandle->query($sql1)->result_array();
        	$result = array();
        	$finalResult = array();
        	$msgIdsForWhichDetailsToBeFetched = array();
        	foreach ($resultSet1 as $value){
        		$result[$value['threadId']]['threadId']		= $value['threadId'];
        		$result[$value['threadId']]['msgId']		= $value['msgId'];
        		//$result[$value['threadId']]['creationDate']	= $value['creationDate'];
        		$result[$value['threadId']]['tagId']		= $value['tagId'];
        		$msgIdsForWhichDetailsToBeFetched[]			= $value['msgId'];
        	}
        	if(empty($msgIdsForWhichDetailsToBeFetched)){
        		return array();
        	}
        	// sql to get some basic details of msgIds corresponding to each fetched threads
        	$sql2 = "SELECT mt.msgId, mt.threadId, mt.userId, mt.fromOthers, mt.mainAnswerId, mt.parentId, mt.creationDate, date(mt.creationDate) AS actionDate, IF(tqt.qualityScore IS NULL, 0, tqt.qualityScore) as qualityScore FROM messageTable mt LEFT JOIN threadQualityTable tqt ON (mt.threadId = tqt.threadId) "
        			."WHERE mt.msgId IN (?) AND mt.status IN ('live','closed') "
        			."GROUP BY msgId ORDER BY actionDate DESC, qualityScore DESC";
        	$resultSet2 = $this->dbHandle->query($sql2, array($msgIdsForWhichDetailsToBeFetched))->result_array();
        	foreach($resultSet2 as $value){
        		if(key_exists($value['threadId'], $result)){ // check if this key exist
        			//$result[$value['threadId']]['actionByUser'] = $value['userId'];
        			$finalResult[$value['threadId']]					= $result[$value['threadId']];
        			$finalResult[$value['threadId']]['creationDate']	= $value['creationDate'];
        			$finalResult[$value['threadId']]['actionByUser']	= $value['userId'];
        			if($value['fromOthers'] == 'user'){
        				if($value['parentId'] == 0 && $value['mainAnswerId'] == -1){
        					$finalResult[$value['threadId']]['action']	= 'post';
        					$finalResult[$value['threadId']]['msgId']	= 0;
        				}elseif ($value['parentId'] == $value['threadId'] && $value['mainAnswerId'] == 0){
        					$finalResult[$value['threadId']]['action']	= 'answer';
        				}else{// unset if its neither question post/answer.
        					unset($finalResult[$value['threadId']]);
        					continue;
        				}
        			}elseif ($value['fromOthers'] == 'discussion'){
        				if($value['parentId'] == $value['threadId'] && $value['mainAnswerId'] == 0){
        					$finalResult[$value['threadId']]['action']	= 'post';
        					$finalResult[$value['threadId']]['msgId']	= 0;
        				}elseif ($value['parentId'] == $value['mainAnswerId'] && $value['mainAnswerId'] > 0){
        					$finalResult[$value['threadId']]['action']	= 'comment';
        				}else{// unset if its neither discussion post/comment.
        					unset($finalResult[$value['threadId']]);
        					continue;
        				}
        			}else{ // unset if any msgId other that question/discussion comes
        				unset($finalResult[$value['threadId']]);
        				continue;
        			}
        		}
        		unset($result[$value['threadId']]);
        	}
        	return $finalResult;
        }
        
        public function getInActiveUsersFromElasticSearch($userIds = array(), $days = 0){
        	if(!is_array($userIds) || empty($userIds) || !is_int($days) || $days <= 0){
        		return array();
        	}
        	$lowerDateLimit = date('Y-m-d', strtotime('-'.$days.' days')).'T00:00:00';
        	$upperDateLimit = date('Y-m-d', strtotime('now')).'T00:00:00';
        	$ESConnectionLib = $this->load->library('trackingMIS/elasticSearch/ESConnectionLib');
			$elasticSerachConn = $ESConnectionLib->getShikshaESServerConnection();
		        	
        	$searchInputArray = array('index' => array(), 'type' => array(), 'body' => array());
        	$searchInputArray['index']	= PAGEVIEW_INDEX_NAME_REALTIME_SEARCH;
        	$searchInputArray['type']	= 'pageview';
        	$searchInputArray['body']	= '';
        	
        	$searchQuery['size']		= 0;
        	//$searchQuery['_source']		= array('userId','visitTime');
        	$searchQuery['query']		= array("bool" => array("filter" => array( "bool" => array( "must" => array(array("range" => array("visitTime" => array("gte" => $lowerDateLimit, "lt" => $upperDateLimit))),array("terms" => array("userId" => $userIds)))))));
        	$searchQuery['aggs']		= array("userWise" => array("terms" => array("field" => "userId", "min_doc_count" => 1, "size" => ELASTIC_AGGS_SIZE)));
        	
        	$searchInputArray['body']	= $searchQuery;
//        	error_log("ABHINAV@TEST ELASTIC SEARCH QUERY : ".json_encode($searchInputArray['body']));
        	//return array();
        	$response = $elasticSerachConn->search($searchInputArray);
        	$result = array();
        	
        	$activeUsers = array();
        	foreach ($response['aggregations']['userWise']['buckets'] as $data){
        		$activeUsers[] = $data['key'];
        	}
        	
        	$result = array_diff($userIds, $activeUsers);
			$result = array_values($result);
        	return $result;
        }
        
        public function getInActiveVisitorsFromElasticSearch($visitorIds = array(), $days = 0){
        	if(!is_array($visitorIds) || empty($visitorIds) || empty($days) || $days <= 0){
        		return array();
        	}
        	$lowerDateLimit = date('Y-m-d', strtotime('-'.$days.' days')).'T00:00:00';
        	$upperDateLimit = date('Y-m-d', strtotime('now')).'T00:00:00';
        	$ESConnectionLib = $this->load->library('trackingMIS/elasticSearch/ESConnectionLib');
			$elasticSerachConn = $ESConnectionLib->getShikshaESServerConnection();

        	$searchInputArray = array('index' => array(), 'type' => array(), 'body' => array());
        	$searchInputArray['index']	= SESSION_INDEX_NAME_REALTIME_SEARCH;
        	$searchInputArray['type']	= 'session';
        	$searchInputArray['body']	= '';
        	
        	$searchQuery['size']		= 0;
        	$searchQuery['query']		= array("bool" => array("filter" => array( "bool" => array( "must" => array(array("range" => array("startTime" => array("gte" => $lowerDateLimit, "lt" => $upperDateLimit))),array("terms" => array("visitorId" => $visitorIds)))))));
        	$searchQuery['aggs']		= array("visitorWise" => array("terms" => array("field" => "visitorId", "min_doc_count" => 1, "size" => ELASTIC_AGGS_SIZE)));
        	
        	$searchInputArray['body']	= $searchQuery;
        	//error_log("ABHINAV@TEST ElasticSearch : ".json_encode($searchInputArray['body']));
        	$response = $elasticSerachConn->search($searchInputArray);
        	$result = array();
        	//_p($response);
        	
        	$activeVisitors = array();
        	foreach ($response['aggregations']['visitorWise']['buckets'] as $data){
        		$activeVisitors[] = $data['key'];
        	}
        	$result = array_diff($visitorIds, $activeVisitors);
		 	$result = array_values($result);
        	return $result;
        }
        
	}

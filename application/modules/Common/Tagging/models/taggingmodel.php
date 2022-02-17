<?php
class TaggingModel extends MY_Model
{ 
	private $dbHandle = '';

	/**
	* Constructor Function 
	*/	
	function __construct(){
		parent::__construct('User');
	}

	
	/**
	* To Initiate the dbHandler instance
	*/
	private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
			$this->dbHandle = $this->getWriteHandle();
		}		
	}


	/**
	* Function to find the parent tags of Input Tags
	* @param array $tags
	*/
	public function findParentTags($tags = array()){	

		$finalResult = array();
		$tags = array_filter($tags);
		if(empty($tags)){
			return $finalResult;
		}
		$this->initiateModel();
		$tags_query = implode(",", $tags);
		$sql = "SELECT distinct parent_id, tag_id FROM tags_parent WHERE tag_id IN (?) and status = 'live' ORDER BY FIELD(tag_id,$tags_query)";
		$query = $this->dbHandle->query($sql, array($tags));
		$result = $query->result_array();
		foreach ($result as $value) {
			$tagId = $value['tag_id'];
			$finalResult[$tagId] = $value['parent_id'];
		}
		return $finalResult;
	}

        public function findAllParentTags($tags = array()){

                $finalResult = array();
                $tags = array_filter($tags);
                if(empty($tags)){
                        return $finalResult;
                }
                $this->initiateModel();
                $tags_query = implode(",", $tags);
                $sql = "SELECT distinct parent_id, tag_id FROM tags_parent WHERE tag_id IN (?) and status = 'live' ORDER BY FIELD(tag_id,$tags_query)";
                $query = $this->dbHandle->query($sql, array($tags));

                $result = $query->result_array();
                foreach ($result as $value) {
                        $tagId = $value['tag_id'];
                        $finalResult[$tagId][] = $value['parent_id'];
                }
                return $finalResult;
        }
	
	/**
	* Function to insert tags with content to DB(in table tags_content_mapping)
	* @param array $tags
	* @param integer $content_id 
	* @param string $content_type
	*/
	public function insertTagsWithContent($tags,$content_id,$content_type){
		
		
		$data = array();
		$count = 0;
		$allTagArray = array();
		$this->initiateModel('write');
		$finalArrayWithLiveCheck = $this->checkLiveTags($tags);
		$this->initiateModel('write');
		foreach ($tags as $key => $tags_id_array) {
			if(!in_array($tags_id_array['tagId'],$allTagArray) && $finalArrayWithLiveCheck[$tags_id_array['tagId']] == 1){
							if($tags_id_array['tagId'] == 0 || $tags_id_array['tagId'] == "") continue;
	                        $data[$count]['tag_id'] = $tags_id_array['tagId'];
        	                $data[$count]['content_type'] = $content_type;
                	        $data[$count]['content_id'] = $content_id;
                        	$data[$count]['tag_type'] = $tags_id_array['classification'];
				$data[$count]['modificationTime'] = date('Y-m-d H:i:s');
        	                $count++;			
				$allTagArray[] = $tags_id_array['tagId'];
			}
		}

		$result = array();
		if(!empty($data)){
			$result = $this->dbHandle->insert_batch('tags_content_mapping',$data);	
		}

                //After we have inserted tags, we need to check for Tags type and send Listing Digest Mailer
                $this->sendListingMailer($allTagArray, $content_id);
		
		return $result;

	}

        public function sendListingMailer($allTagArray, $content_id){
                $this->initiateModel();

                //Check if this Tag is mapped to any DOmestic Institute / University
                if(is_array($allTagArray) && count($allTagArray)>0){
                        $sql = "SELECT entity_id, entity_type FROM tags_entity WHERE status = 'live' AND entity_type IN ('institute','National-University') AND tag_id IN (?)";
                        $query = $this->dbHandle->query($sql, array($allTagArray));
                        $result = $query->result_array();

                        //Find the owner of the Content
                        $sqlUser = "SELECT userId FROM messageTable WHERE msgId = ?";
                        $queryUser = $this->dbHandle->query($sqlUser, array($content_id))->row_array();
                        $userId = isset($queryUser['userId'])?$queryUser['userId']:0;

                        $mailsSent = 0;
                        $instituteArray = array();

                        foreach ($result as $row){
                                $instituteId = isset($row['entity_id'])?$row['entity_id']:0;

                                //If InstituteId is present, userId is present, less than 3 mails sent, this instituteId was not used, trigger the Mailer
                                if($instituteId > 0 && $userId > 0 && $mailsSent < 3 && !in_array($instituteId,$instituteArray)){
                                        $this->load->library("common/jobserver/JobManagerFactory");
                                        try {
                                            $jobManager = JobManagerFactory::getClientInstance();
                                            if ($jobManager) {
                                                    if(!empty($instituteId)){
                                                        $mailerData['instituteId'] = $instituteId;
                                                        $mailerData['userId'] = $userId;
                                                        $jobManager->addBackgroundJob("InstituteDigestMailerQueue", $mailerData);
                                                        $mailsSent++;
                                                        $instituteArray[] = $instituteId;
                                                    }
                                            }
                                        }catch (Exception $e) {
                                            error_log("Unable to connect to rabbit-MQ");
                                        }
                                }

                        }
                }
        }
	
	public function checkLiveTags($tags){
		$tmpArr = array();
		$finalArrayWithLiveCheck = array();
		$currentTags = array_map(function ($value) {
   		 return  $value['tagId'];
		}, $tags);

		$currentTags = array_filter($currentTags);
		if(empty($currentTags))
			return $finalArrayWithLiveCheck;
		$tagIdsToCheck = implode(',', $currentTags);
		$sql = "SELECT id as tagId from tags where status = 'live' and id in (?) ";
	    $queryParent = $this->dbHandle->query($sql, array($currentTags));
        $resultParent = $queryParent->result_array();
        
        foreach($resultParent as $key=>$value){
        $finalArrayWithLiveCheck[$value['tagId']] = 1; 
	}
	return $finalArrayWithLiveCheck;
}
	public function findTagDetails($tagArray){
                $finalResult = array();
                if(empty($tagArray)){
                        return $finalResult;
                }
                $this->initiateModel();
		$tagString = "";
		foreach ($tagArray as $tags){
			$tagString .= ($tagString=="") ? $tags['id'] : ','.$tags['id'];
		}
		if($tagString!=''){
	                $sql = "SELECT id as tagId, tags as tagName, tag_entity as tagType, description, isNonCrawlable FROM tags WHERE id IN (?) AND status = 'live'";
			$tagStringArray = explode(',',$tagString);
        	        $query = $this->dbHandle->query($sql, array($tagStringArray));
                	$result = $query->result_array();
			$i = 0;
			foreach ($result as $tagDesc){
				foreach ($tagArray as $tag){
					if($tag['id'] == $tagDesc['tagId']){
						$finalResult[$i] = $tagDesc;
                                                $finalResult[$i]['classification'] = $tag['classification'];
                                                $finalResult[$i]['checked'] = $tag['checked'];
						$finalResult[$i]['conflicted'] = false;
						$i++;
					}
				}
			}
		}
                return $finalResult;
	}

        public function findConflictedTag($tagArray){
                $finalResult = $tagArray;

                $this->initiateModel();
		//Create a String of all Tags
                $tagString = "";
                foreach ($tagArray as $tags){
                        $tagString .= ($tagString=="") ? "'".$tags['tagName']."'" : ','."'".$tags['tagName']."'";
                }
                if($tagString!=''){
                        $sql = "SELECT count(DISTINCT main_id) AS entryCount, tags FROM tags WHERE tags IN (?) AND status = 'live' GROUP BY tags";
			$tagStringArray = explode(',',$tagString);
                        $query = $this->dbHandle->query($sql, array($tagStringArray));
                        $result = $query->result_array();
                        foreach ($result as $tags){
                            if($tags['entryCount'] > 1){    //Meaning, this is a conflicted Tag i.e. it has multiple entries in tags table.
                                //Find its Parent Ids
                                $sql = "SELECT GROUP_CONCAT(tp.parent_id) as parentFields FROM tags t, tags_parent tp WHERE tags = ? AND tag_id = t.id AND t.status = 'live'";
                                $queryTag = $this->dbHandle->query($sql, array($tags['tags']));
                                $resultTag = $queryTag->row();

                                //Find Classification of the conflicted Tag:
                                for ($i = 0; $i < count($finalResult); $i++){
                                    if($finalResult[$i]['tagName'] == $tags['tags']){
                                        $classification = $finalResult[$i]['classification'];					
					$finalResult[$i]['conflicted'] = true;
                                    }
                                }

                                //Find details of each Parent Tag
				if($resultTag->parentFields!=""){
                                	$sql = "SELECT id as tagId, tags as tagName, tag_entity as tagType, description FROM tags WHERE id IN ($resultTag->parentFields) AND status = 'live'";
	                                $queryParent = $this->dbHandle->query($sql);
        	                        $resultParent = $queryParent->result_array();
                	                $i = count($finalResult);
                        	        foreach ($resultParent as $tagDesc){
                                	    $finalResult[$i] = $tagDesc;
	                                    $finalResult[$i]['classification'] = $classification."_parent";
        	                            $finalResult[$i]['checked'] = false;
					    $finalResult[$i]['conflicted'] = false;
                        	            $i++;
                                	}
				}
				//Also, convert 
                            }
                        }
                }
                return $finalResult;            
        }


	function checkIfConflicted($checkForConflicted){
                $this->initiateModel();
		$returnArray = array();
                //Create a String of all Tags
                $tagString = implode(",",$checkForConflicted);
                if($tagString!=''){
                        $sql = "SELECT count(*) AS entryCount, tag_id FROM tags_parent WHERE tag_id IN (?) GROUP BY tag_id";
			$tagStringArray = explode(',',$tagString);
                        $query = $this->dbHandle->query($sql, array($tagStringArray));
                        $result = $query->result_array();
			foreach ($result as $tags){
				$tagId = $tags['tag_id'];
                        	if($tags['entryCount'] > 1){    //Meaning, this is a conflicted Tag i.e. it has multiple entries in tags table.
					$returnArray[$tagId]['conflicted'] = true;
				}
				else{
					$returnArray[$tagId]['conflicted'] = false;
				}
			}
		}
		return $returnArray;
	}

	public function checkTagForEntity($tags, $entity){
                $finalResult = array();
                $tags = array_filter($tags);
                if(empty($tags)){
                        return $finalResult;
                }
                $this->initiateModel();
                $tags_query = implode(",", $tags);
                $sql = "SELECT id as tag_id, tags, tag_entity FROM tags WHERE id IN (?) AND status = 'live'";
                $query = $this->dbHandle->query($sql, array($tags));
                $result = $query->result_array();
                foreach ($result as $value) {
			if($value['tag_entity'] == $entity){	//If any of the Tag entity is found, return the value
				$finalResult['tagId'] = $value['tag_id'];
				$finalResult['tagName'] = $value['tags'];
				return $finalResult;
			}
                }

		//Now, find the details of the Parents of these tags
		$parentTags = $this->checkParentTagForEntity($tags, $entity);
		if(isset($parentTags['tagName']) && $parentTags['tagName']!=''){
			return $parentTags;
		}

		//Now, find the details of the Grand Parents of these tags
                $parentTags = $this->checkParentTagForEntity($parentTags, $entity);
                if(isset($parentTags['tagName']) && $parentTags['tagName']!=''){
                        return $parentTags;
                }

                return $finalResult;
	}

	public function checkParentTagForEntity($tags, $entity){
		$finalResult = array();
                $this->initiateModel();
		if(empty($tags)){
			return $finalResult;
		}
                $tags_query = implode(",", $tags);

                $sql = "SELECT tg.id as tag_id, tg.tags, tg.tag_entity FROM tags_parent tp, tags tg WHERE tp.tag_id IN (?) AND tp.parent_id = tg.id AND tg.status = 'live'";
                $query = $this->dbHandle->query($sql, array($tags));
                $result = $query->result_array();
		$i = 0;
		$tagArray = array();
                foreach ($result as $value) {
                        if($value['tag_entity'] == $entity){    //If any of the Tag entity is found, return the value
                                $finalResult['tagId'] = $value['tag_id'];
                                $finalResult['tagName'] = $value['tags'];
                                return $finalResult;
                        }
			$tagArray[$i] = $value['tag_id'];
			$i++;
                }	
		return $tagArray;
	}

	public function deleteTagsWithContentToDB($editEntityId){
		$this->initiateModel("write");
		$sql = "UPDATE tags_content_mapping SET status='deleted',modificationTime = now() WHERE content_id = ? AND status = 'live'";
		$query = $this->dbHandle->query($sql, array($editEntityId));
		return true;
	}

        public function getTagsByClassification($editEntityId, $classificationArray){
                $finalResult = array();
                $this->initiateModel();
                if($editEntityId<=0 || $editEntityId == ""){
                        return $finalResult;
                }
                $classification = "'" . implode("','", $classificationArray) . "'";

                $sql = "SELECT tg.id as tagId, tg.tags as tagName, tg.tag_entity as tagType, tg.description, cp.tag_type as classification FROM tags_content_mapping cp, tags tg WHERE cp.tag_type IN (?) AND cp.content_id = ? AND cp.tag_id = tg.id AND cp.status = 'live' AND tg.status = 'live'";
                $query = $this->dbHandle->query($sql, array($classificationArray,$editEntityId));
                $result = $query->result_array();
                $i = 0;
                foreach ($result as $value) {
                        if(in_array($value['classification'], $classificationArray)){
                                $finalResult[$i] = $value;
                                $finalResult[$i]['checked'] = true;
				$finalResult[$i]['conflicted'] = false;
				$i++;
                        }
                }
                return $finalResult;
        }

	public function getAllTagsOfEntity($entityId, $entityType){
                $finalResult = array();
                $this->initiateModel();
                if(is_array($entityId)){
                        return $this->getAllTagsOfMultipleEntity($entityId);
                }
                if($entityId<=0 || $entityId == ""){
                        return $finalResult;
                }

                $sql = "SELECT tg.id as tagId, tg.tags as tagName, tg.tag_entity as tagType, tg.description, cp.tag_type as classification FROM tags_content_mapping cp, tags tg WHERE cp.content_id = ? AND cp.tag_id = tg.id AND cp.status = 'live' AND tg.status = 'live'";
                $query = $this->dbHandle->query($sql, array($entityId));
                $result = $query->result_array();
                $i = 0;
                foreach ($result as $value) {
                        $finalResult[$i] = $value;
                        $finalResult[$i]['checked'] = true;
                        $finalResult[$i]['conflicted'] = false;
                        $i++;
                }
                return $finalResult;
	}

        public function getAllTagsOfMultipleEntity($entityIds){
                $finalResult = array();
                $this->initiateModel();
                if(is_array($entityIds) && count($entityIds) <= 0){
                        return $finalResult;
                }

                $sql = "SELECT cp.content_id, tg.id as tagId, tg.tags as tagName, tg.tag_entity as tagType, tg.description, cp.tag_type as classification FROM tags_content_mapping cp, tags tg WHERE cp.content_id IN (?) AND cp.tag_id = tg.id AND cp.status = 'live' AND tg.status = 'live'";
                $query = $this->dbHandle->query($sql, array($entityIds));
                $result = $query->result_array();

                foreach ($entityIds as $entityId){
                        $i = 0;
                        foreach ($result as $value) {
                                if($value['content_id'] == $entityId){
                                        $finalResult[$entityId][$i] = $value;
                                        $finalResult[$entityId][$i]['checked'] = true;
                                        $finalResult[$entityId][$i]['conflicted'] = false;
                                        $i++;
                                }
                        }
                }
                return $finalResult;
        }

	public function fetctTextForMessage($threadIdArray,$msgType='question'){
		$this->initiateModel();
		$in_query = implode("','", $threadIdArray);

		if($msgType == 'question'){
			$sql = "SELECT msgTxt,threadId,userId from messageTable where threadId IN (?) and parentId = 0 and status IN('live','closed') and fromOthers='user' ORDER BY threadId";

			$query = $this->dbHandle->query($sql, array($threadIdArray));
			$result = $query->result_array();
			return $result;
			
		} else if($msgType == 'discussion'){
			$sql = "SELECT msgTxt,threadId,userId from messageTable where threadId IN (?) and parentId = threadId and status IN('live','closed') and fromOthers='discussion' ORDER BY threadId";

			$query = $this->dbHandle->query($sql, array($threadIdArray));
			$result = $query->result_array();
			return $result;
		}
	}
	
	public function getThreadsFromTagContentMapping($offset = 0){
		$this->initiateModel('read');
		$sql = "SELECT SQL_CALC_FOUND_ROWS content_id, tag_id, tag_type FROM tags_content_mapping WHERE content_type IN ('question','discussion') AND status = 'live' ";
		$sql .= " LIMIT 5000 OFFSET ".$offset;
		$this->initiateModel('read');
		$resultSet = $this->dbHandle->query($sql)->result_array();
		//echo 'sql : '.$sql;
		$foundRows = $this->dbHandle->query("SELECT FOUND_ROWS() as totalRows")->row();
		$result = array();
		foreach ($resultSet as $data){
			if(strpos($data['tag_type'], 'parent') !== false){
				$result[$data['content_id']][$data['tag_id']] = 1;
			}else {
				$result[$data['content_id']][$data['tag_id']] = 0;
			}
		}
		$final = array(	'resultData' => $result, 'foundRows' => $foundRows->totalRows);
		return $final;
	}

	function getAPIResponseReport($date){

		$days      = 0;
		$dayClause = "";

		if($date)
			$dayClause = " AND creationDate >= '".$date." 00:00:00' AND creationDate <= '".$date." 23:59:59' ";
		
		if(empty($dayClause))
			return array();

        $dbHandle = $this->getReadHandle();
            
        $queryCmd ="SELECT controller,method,TRUNCATE(AVG(serverProcessingTime), 2) AS average_time, COUNT(*) AS totalhits  FROM api_tracking FORCE INDEX(index_2) WHERE serverProcessingTime > 0 ".$dayClause." AND type='APITrack' GROUP BY controller,method ORDER BY average_time DESC";

        $result = $dbHandle->query($queryCmd)->result_array();

        $data = array();
        foreach ($result as $value) {
            $data[] = $value;
        }

        return $data;
    }

    function getMinutewiseAPIData($date, $controller='', $method=''){

    	if(empty($date))
    		return false;

    	$dbHandle = $this->getReadHandle();

    	$whereClause = " creationDate >= '".$date." 00:00:00' AND creationDate <= '".$date." 23:59:59' ";

    	if($controller && $method){
    		$whereClause .= " AND controller = '".$controller."' AND method='".$method."' ";
    	}
            
        $queryCmd ="SELECT HOUR(creationDate) as hour, FLOOR(MINUTE(creationDate)/30) as halfhour, count(*) as cnt, TRUNCATE(AVG(serverProcessingTime), 2) as avg_time from api_tracking FORCE INDEX(index_2) WHERE ".$whereClause." group by hour, halfhour";

        $result = $dbHandle->query($queryCmd)->result_array();

        $data = array();
        for($i= 0; $i <=23; $i++) {
        	$data[$i][0] = 0;
        	$data[$i][1] = 0;
        }

        foreach ($result as $value) {
            $data[$value['hour']][$value['halfhour']] = $value;
        }

        return $data;
    	
    }

    function findListingTagToAttach($institute = 0){
    	$this->initiateModel('read');
    	$sql = "select tag_id from tags_entity where status = 'live' and entity_id = ? and entity_type IN('institute','National-University') limit 1";
    	$query = $this->dbHandle->query($sql, array($institute));
    	$result = $query->row_array();
    	return $result;
    }

    function fetchListingTag($threadId = 0,$listingTypeId=0){
    	$this->initiateModel('read');

    	$result =array();
    	if($listingTypeId>0){
    		$sql = "SELECT b.tag_id from tags_entity b where b.entity_id = ? and b.status = 'live' and b.entity_type IN ('institute','National-University') limit 1";
    		$query = $this->dbHandle->query($sql,array($listingTypeId));
    	}else{
    		$sql = "SELECT b.tag_id from messageTable a join tags_entity b ON a.listingTypeId = b.entity_id where a.listingType = 'institute' and a.listingTypeId > 0 and b.status = 'live' and b.entity_type IN ('institute','National-University') and a.msgId = ? limit 1";
		$query = $this->dbHandle->query($sql,array($threadId));
    	}
    	
    	$result = $query->row_array();
    	return $result;	
    }

    function fetchMultipleListingTag($threadId = 0,$listingIds=array()){
    	$this->initiateModel('read');
    	$result =array();
    	if(is_array($listingIds) && !empty($listingIds)){
    		$sql = "SELECT b.tag_id from tags_entity b where b.entity_id in (?) and b.status = 'live' and b.entity_type IN ('institute','National-University')";


	    	$query = $this->dbHandle->query($sql, array($listingIds));
	    	$result = $query->result_array();

	    	foreach($result as $key=>$val){
	    		$resultSet[] = $val['tag_id'];
	    	}
    	}

    	return $resultSet;
    	
    }


    function fetchDBQuestions(){
    	$this->initiateModel('read');
    	$sql = "SELECT msgId from messageTable where parentId = 0 and fromOthers = 'user'";

    	$query = $this->dbHandle->query($sql);
    	$result = $query->result_array();

    	return $result;
    }

    function fetchManualTags($threadId){
    	$this->initiateModel('read');
    	$sql = "SELECT tag_id,tag_type from tags_content_mapping where content_id = ? and status = 'live' and tag_type in ('manual','manual_parent')";
    	$query = $this->dbHandle->query($sql,array($threadId));
    	$result = $query->result_array();
    	return $result;	
    }

    // Find two level parent Tags
    function findAllTwoLevelParentTags($tagsArray){
    	$this->initiateModel('read');
    	$sql = "SELECT DISTINCT a.parent_id as levelOneParent,b.parent_id as levelTwoParent,"
    	." a.tag_id as mainTag from tags_parent a LEFT JOIN tags_parent b ON(a.parent_id = b.tag_id and b.status = 'live')"
    	." where a.tag_id IN (?) and a.status = 'live'";
    	$query = $this->dbHandle->query($sql, array($tagsArray));
    	$result = $query->result_array();
    	return $result;
    }

    //takes tagId string as input
    function getTagNameById($tagIdString){
    	if(empty($tagIdString)){
    		return;
    	}
    	$this->initiateModel('read');
    	$sql = "SELECT id as tagId, tags as tagName FROM tags WHERE id IN (?) AND status = 'live'";
	$tagIdArray = explode(',',$tagIdString);
    	return $this->dbHandle->query($sql, array($tagIdArray))->result_array();
    }
    
    function findData($tagIdsArray) {
    	$finalResult = array();
    	$tagIdsArray = array_filter($tagIdsArray);
    	if(empty($tagIdsArray)) return $finalResult;
    	$this->initiateModel('read');
    	$tagids = implode(",", $tagIdsArray);
    	$sql = "SELECT tags from tags where id IN (?) and status = 'live'";
    	$query = $this->dbHandle->query($sql, array($tagIdsArray));
    	$result = $query->result_array();
    	
    	foreach ($result as $key => $value) {
    		$finalResult[] = $value['tags'];
    	}
    	return $finalResult;

    }

    function findTagAttachedToEntity($entityId = 0, $entityType = ''){
        $this->initiateModel('read');
        $result =array();
        if($entityId > 0){
                $sql = "SELECT te.tag_id, t.tags FROM tags_entity te, tags t WHERE te.status = 'live' AND t.status = 'live' AND te.entity_id = ? AND te.entity_type = ? AND t.id = te.tag_id LIMIT 1";
                $query = $this->dbHandle->query($sql, array($entityId,$entityType));
                $result = $query->row_array();
        }
        return $result;
    }



    function getLatestAnsweredQuestionsOnTag($tagId, $count = 2){
         $this->initiateModel('read');
         $result = array();
         if($tagId > 0){
            //Find the Latest Answers
            $sql = "SELECT MAX(m.msgId) as mxx, threadId 
                FROM messageTable m, tags_content_mapping t use index (tag_id)
                WHERE m.threadId = t.content_id AND t.status = 'live' AND m.status IN ('live','closed')
                AND t.content_type = 'question' AND t.tag_id = ? AND m.parentId = m.threadId AND (select status from messageTable where msgId=m.threadId) IN ('live','closed')
                AND m.fromOthers='user'
                GROUP BY m.threadId
                ORDER BY mxx DESC
                LIMIT 0,$count";
            $rows     = $this->dbHandle->query($sql, array($tagId))->result_array();
            $questions = array();
            foreach ($rows as $row){
                $questions[] = $row['threadId'];
            }
            
            $queryCmd = "SELECT count(DISTINCT content_id) as totalRows 
                FROM messageTable m, tags_content_mapping t 
                WHERE m.msgId = t.content_id AND t.status = 'live' AND m.status IN ('live','closed')
                AND t.content_type = 'question' AND t.tag_id = ? AND m.parentId = 0 AND m.fromOthers='user'";
                
            $query = $this->dbHandle->query($queryCmd, array($tagId));
            $totalRows = 0;
            foreach ($query->result() as $row) {
                $totalRows = $row->totalRows;
            }
            
            $result = array('topContent'=>$questions,'totalNumber'=>$totalRows);
         }
         
         return $result;
    }
    function getParentTagNames($tagId)
    {
    	$this->initiateModel('read');
    	$this->dbHandle->select('id as tag_id,tags as tag_name');
    	$this->dbHandle->from('tags t');
    	$this->dbHandle->join('tags_parent tp','tp.parent_id = t.id');
    	$this->dbHandle->where('tp.tag_id',$tagId);
    	$this->dbHandle->where('tp.status','live');
    	$this->dbHandle->where('t.status','live');
    	$result = $this->dbHandle->get()->result_array();
    	return $result;
    }
    function getChildTagIds($tagId)
    {
    	$this->initiateModel('read');
    	$this->dbHandle->select('tag_id');
    	$this->dbHandle->from('tags_parent');
    	$this->dbHandle->where('status','live');
    	$this->dbHandle->where('parent_id',$tagId);
    	$result = $this->dbHandle->get()->result_array();
    	$rs = array();
    	foreach ($result as $key => $value) {
    		$rs[] = $value['tag_id'];
    	}
    	return $rs;
    }

    function getTagsType($tagIds){
 		$this->initiateModel('read');
        $result =array();
        if(!empty($tagIds)){
                $sql = "SELECT tag_id, entity_type, entity_id FROM tags_entity WHERE status = 'live' and tag_id in (?)";
                $query = $this->dbHandle->query($sql, array($tagIds));
                $result = $query->result_array();
        }

        return $result;    	
    }

    function findTagsEntity($tagIds, $tagType){
    	$this->initiateModel('read');
    	if($tagType == 'institute'){
    		$tagTypeCheck = 'and entity_type in ("institute", "National-University")';
    	}else if($tagType == 'course'){
    		$tagTypeCheck = 'and entity_type in ("Course")';    		
    	}else if($tagType == 'subStream'){
    		$tagTypeCheck = 'and entity_type in ("Sub-Stream")';    		
    	}else if($tagType == 'stream'){
    		$tagTypeCheck = 'and entity_type in ("Stream")';    		
    	}
    	else{
    		return;
    	}
        $result =array();
        if(!empty($tagIds)){
            $sql = "SELECT entity_type, entity_id FROM tags_entity WHERE status = 'live' and tag_id in (?) $tagTypeCheck";
            $query = $this->dbHandle->query($sql, array($tagIds));
            $result = $query->result_array();
            return $result;
        }
    }
    function isUserFollowingTag($userId, $tagArray, $entityType = 'tag'){
		$dbHandle = $this->getReadHandle();
		$finalArray = array();
		$tagString = implode(",",array_filter($tagArray));
	
		if(!empty($tagString))
		{
	        //Find if the user is following this Tag
        	$sql = "SELECT entityId as tagId, 1 as following FROM tuserFollowTable WHERE userId = ? AND status = 'follow' AND entityType = ? AND entityId IN (?)";
	        $rows = $dbHandle->query($sql, array($userId,$entityType,$tagArray))->result_array();
                $finalArray = array();
                foreach ($tagArray as $tag){
                        $entryFound = false;
                        foreach ($rows as $row){
                                if($tag == $row['tagId']){
                                        $finalArray[$tag] = 'true';
                                        $entryFound = true;
                                }
                        }
                        if(!$entryFound){
                                $finalArray[$tag] = 'false';
                        }
                }
                return $finalArray;
	}
	return array();
    }
	 function checkTagsExistOnDB($entityIds,$entityType = 'question')
    {
    	$this->initiateModel('read');
    	$sql = "SELECT distinct content_id from tags_content_mapping where content_id in (?) and status = 'live' and content_type = ?";
    	return $this->dbHandle->query($sql,array($entityIds,$entityType))->result_array();
    }
    function fetchAllTagsOnContentIds($entityIds = array(),$entityType = 'question')
    {
    	if(!is_array($entityIds) || count($entityIds) == 0 || empty($entityType))
    		return array();

    	$this->initiateModel('read');
    	$sql = "SELECT content_id,tag_id FROM tags_content_mapping where content_type = ? AND content_id IN (?) AND status = 'live'";
    	$result = $this->dbHandle->query($sql,array($entityType,$entityIds))->result_array();
    	$rs = array();
    	foreach ($result as $key => $value) {
    		if(array_key_exists($value['content_id'], $rs))
    		{
    			if(!in_array($value['tag_id'], $rs[$value['content_id']]))
    				$rs[$value['content_id']][] = $value['tag_id'];
    		}
    		else
    		{	
    			$rs[$value['content_id']] = array();
    			$rs[$value['content_id']][] = $value['tag_id'];	
    		}

    	}
    	return $rs;
    }
    function insertTagsToDBFromRedis($tagsMapping)
    {
    	if(empty($tagsMapping))
    		return;
    	$this->initiateModel('write');
    	$insertData = array();
    	$count = 0;
    	foreach ($tagsMapping as $key => $value) {
    		foreach ($value as $subkey => $subvalue) {
    			if(!empty($subvalue))
    			{
    				$insertData[$count]['tag_id'] = $subvalue;
	    			$insertData[$count]['content_id'] = $key;
	    			$insertData[$count]['content_type'] = 'question';
	    			$insertData[$count]['tag_type'] = 'manual';
	    			$insertData[$count]['status'] = 'live';
	    			$insertData[$count]['modificationTime'] = date('Y-m-d H:i:s');
	    			$count++;
    			}
    		}
    	}
		if(!empty($insertData)){
			$result = $this->dbHandle->insert_batch('tags_content_mapping',$insertData);	
		}
    }
    public function getTagDetails($tagArray)
    {
        if(empty($tagArray))
        {
            return $finalResult;
        }
        $this->initiateModel();
    
        $sql = "SELECT * FROM tags_entity WHERE tag_id IN (?) AND status = 'live'";
        $query = $this->dbHandle->query($sql, array($tagArray));
        $finalResult = $query->result_array();
        return $finalResult;
    }
    public function getContentType($tagIds,$content_ids)
    {
        if(empty($tagIds) || empty($content_ids))
        {
            return false;
        }
        $this->initiateModel();
        $sql = "SELECT tag_id,tag_type,content_id from tags_content_mapping where tag_id IN (?) and content_id IN (?) and status='live'";
        $query = $this->dbHandle->query($sql, array($tagIds,$content_ids));
        $finalResult = $query->result_array();
        return $finalResult;
    }

    function getSimilarTags($tagArray){

        $finalResult = array();
        if(empty($tagArray)){
                return $finalResult;
        }
        
        $this->initiateModel();
        $tagString = "";
        $tagString = implode(", ", $tagArray);

        if($tagString!=''){
            //$sql = "SELECT group_concat(a.tag1) as SeedTags,a.tag2 as RecommendedTag, b.tags,b.tag_entity as TagEntity,ROUND(SUM(a.similarity_index + a.manual_boost),3) AS TotalSimilarity, ROUND(SUM(a.similarity_index),3) as CoViewsSimilarity,SUM(a.manual_boost) as HeirarchySimilarity  FROM tags_similarity a inner join tags b on(a.tag2 = b.id and a.status=b.status and b.status='live') WHERE a.status = 'live' AND a.tag1 IN (?) GROUP BY a.tag2 ORDER BY TotalSimilarity DESC limit 50";
            
            $sql = "SELECT group_concat(a.tag1) as SeedTags, tag2 as RecommendedTag, (select b.tags from tags b where b.id=tag2) as tags,(select b.tag_entity from tags b where b.id=tag2) as TagEntity,ROUND(SUM(sim_comb2),2) as TotalSimilarity  ,ROUND(SUM(sim_sess_full),2) AS SimilaritySession, ROUND(SUM(sim_qna_full),2) AS SimilarityQNA, ROUND(SUM(manual_boost_full),2) AS ManualBoost  FROM ((select tag1, tag2, (sim_sess_full*1.5+sim_qna_full*1.0+manual_boost_full*0.05)*1.0*IF(tag2_type IN ('Specialization varients','Stream varients','Sub-Stream varients','Course varients','Colleges varients','University varients','College common varients ','Careers varients','Country varients','Exams varients','Mode varients'), 1.1,1)*IF(tag2_type IN ('Stream','City','Country'), 0.9,1) as sim_comb2  ,sim_sess_full, sim_qna_full, manual_boost_full, sim_comb_full FROM tags_similarity_new  WHERE tag1 IN (?) AND tag2_type NOT IN ('Course Level','Mode','Country','State','City')  order by  sim_comb_full desc LIMIT 14) UNION ALL (select tag1, tag2, (sim_sess_full*1.5+sim_qna_full*1.0+manual_boost_full*0.05)*0.5*IF(tag2_type IN ('Specialization varients','Stream varients','Sub-Stream varients','Course varients','Colleges varients','University varients','College common varients ','Careers varients','Country varients','Exams varients','Mode varients'), 1.1,1)*IF(tag2_type IN ('Stream','City','Country'), 0.9,1) as sim_comb2  ,sim_sess_full, sim_qna_full, manual_boost_full, sim_comb_full FROM tags_similarity_new  WHERE tag1 = 42 AND tag2_type NOT IN ('Course Level','Mode','Country','State','City')  order by  sim_comb_full desc LIMIT 14) UNION ALL (select tag1, tag2, (sim_sess_full*1.5+sim_qna_full*1.0+manual_boost_full*0.05)*0.2*IF(tag2_type IN ('Specialization varients','Stream varients','Sub-Stream varients','Course varients','Colleges varients','University varients','College common varients ','Careers varients','Country varients','Exams varients','Mode varients'), 1.1,1)*IF(tag2_type IN ('Stream','City','Country'), 0.9,1) as sim_comb2  ,sim_sess_full, sim_qna_full, manual_boost_full, sim_comb_full FROM tags_similarity_new  WHERE tag1 = 48 AND tag2_type NOT IN ('Course Level','Mode','Country','State','City')  order by  sim_comb_full desc LIMIT 14)) a  GROUP BY tag2 ORDER BY sum(sim_comb2) desc limit 20";
            $tagStringArray = explode(',' ,$tagString);
            $query = $this->dbHandle->query($sql, array($tagStringArray));
            $finalResult = $query->result_array();


        }
        return $finalResult;

    }
}

<?php
class TaggingCMSModel extends MY_Model
{ 
	private $dbHandle = '';
	private $actualTagsArray;
	/**
	* Constructor Function 
	*/	
	function __construct(){
		parent::__construct('User');
		$this->load->config('TaggingConfig');
		$this->actualTagsArray = $this->config->item('TAG_ENTITY_ACTUAL');
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

	public function fetchTagEntity($tag_id){
		$this->initiateModel('read');
		$sql = "SELECT tag_entity from tags where id = ?";
		$query = $this->dbHandle->query($sql, array($tag_id));
		$row_array = $query->row_array();
		$entity = $row_array['tag_entity'];
		return $entity;
	}

	/**
	* Function to check if the tag already exists in DB or not(in case of addition)
	* @param string $tagName (Tag Name to be checked)
	* @param string $tagEntity(Tag Entity - not used for now)
	*/
	public function findIfTagExists($tagName,$tagEntity){
		$this->initiateModel('read');
		$sql = "SELECT tags ,tag_entity FROM tags WHERE tags = '".$this->dbHandle->escape_str($tagName)."' AND status = 'live'";

		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}

	/**
	* Function to get the list of all tags of given tag_entity
	*/
	public function getTagsArray($tag_entity){
		$this->initiateModel('read');
		$sql = "SELECT tags,id FROM tags WHERE tag_entity = ? AND status = 'live'";	
		$query = $this->dbHandle->query($sql, array($tag_entity));
		$result = $query->result_array();
		return $result;
	}	

	/**
	* Function to fetch all the Tag Entities from DB
	* @return array Array of All tag entities
	*/
	public function fetchTagEntities(){
		$this->initiateModel('read');
		$sql = "SELECT DISTINCT tag_entity FROM tags WHERE status = 'live' order by tag_entity";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}

	/**
	* Funtion to insert all the Tags to Database
	*/
	public function addTags($data){
		$this->initiateModel('write');		
		$query = $this->dbHandle->insert_batch('tags',$data);
	}	

	/**
	* Function to fetch the latest tag_id from tags tables
	* @return integer Tag_id
	*/
	public function fetchMaxDBID(){
		$this->initiateModel('write');
		$sql = "SELECT MAX(id) as id from tags";
		$query = $this->dbHandle->query($sql);
		$row = $query->row_array();
		return $row['id'];
	}

	/**
	* Function to add data to tags_parent table
	* @param array $data Data to insert
	*/
	public function addParentTags($data){
		$this->initiateModel('write');		
		$query = $this->dbHandle->insert_batch('tags_parent',$data);
	}

	/**
	* Function to fetch all the child tags of the particular tags(CHILD TAGS WITH ONLY PARENT)
	* @param integer $tagId
	*/
	public function fetchChildTagsFromDBWithOnlyParent($tagId){

//		$tagId = 188879;
		$this->initiateModel('read');
		/*$sql = "SELECT a.tags as tagName,a.id as tagId FROM tags a JOIN tags_parent b".
				" ON a.id=b.tag_id AND b.parent_id=$tagId AND a.status='live' AND b.status='live'";*/
		$sql = "SELECT tag_id as tagId FROM tags_parent WHERE parent_id = ? and status = 'live'";

		$query = $this->dbHandle->query($sql, array($tagId));
		$result_array = $query->result_array();

		$ids = array();
		foreach ($result_array as $key => $value) {
			$ids[] = $value['tagId'];
		}

		if(!empty($ids)){

			$in_query = implode("','", $ids);
			$sql = "SELECT a.tag_id AS tagId, b.tags AS tagName FROM tags_parent a,tags b".
					 " WHERE a.tag_id IN (?) AND a.tag_id = b.id AND a.status = 'live'".
					 " AND b.status = 'live' GROUP BY a.tag_id HAVING COUNT(a.tag_id) = 1";
			$query = $this->dbHandle->query($sql, array($ids));
			$result_array = $query->result_array();
		}
		
		return $result_array;
	}

	public function findAllChildTags($tagId){
		$this->initiateModel('read');
		$sql = "select b.tag_id as tagId,a.tags as tagName,a.tag_entity as tagEntity from tags a, tags_parent b where b.tag_id = a.id and a.status='live' and b.status='live' and b.parent_id = ?";
		$query = $this->dbHandle->query($sql, array($tagId));
		$result_array = $query->result_array();
		return $result_array;
	}

	/**
	* Function to fetch the Question / Discussion Detail from the Question / Discussion Ids(Array of Ids)
	* @param array $data Array of Q/D Ids
	*/
	public function fetchQuestionOrDiscussionsById($data){
		$this->initiateModel('read');

		$result1 = array();
		$result = array();

		if(!empty($data['question'])){
			$in_query = implode("','", $data['question']);
			$sql = "SELECT msgTxt,threadId from messageTable where threadId IN (?) and parentId = 0";
			$query = $this->dbHandle->query($sql, array($data['question']));
			$result = $query->result_array();
		}
		
		if(!empty($data['discussion'])){
			$in_query = implode("','", $data['discussion']);
			$sql = "SELECT msgTxt,threadId from messageTable where threadId IN (?) and parentId = threadId";
			$query = $this->dbHandle->query($sql, array($data['discussion']));
			$result1 = $query->result_array();
		}
		$result = array_merge($result,$result1);

		return $result;
	}

	public function fetchSynList($data){
		$this->initiateModel('read');
		$in_query = implode("','", $data);
		$sql = "SELECT tags,id,main_id from tags where main_id IN (?) AND id NOT IN (?) AND status = 'live' ORDER BY tags";
		
		$query = $this->dbHandle->query($sql, array($data,$data));
		$result = $query->result_array();
		return $result;
	}


	public function deleteTagsFromDB($data){
		$this->initiateModel('write');
		$in_query = implode("','", $data);
		$date = date('Y-m-d H:i:s');

		$sql = "UPDATE tags set status='deleted' where id IN (?)";
		$query = $this->dbHandle->query($sql, array($data));

		$sql = "UPDATE tags_parent set status = 'deleted' where tag_id IN (?) OR parent_id IN (?)";
		$query = $this->dbHandle->query($sql, array($data,$data));


		$sql = "select content_id,content_type from tags_content_mapping where tag_id IN (?) and status='live'";
		$query = $this->dbHandle->query($sql, array($data));
		$result = $query->result_array();

		$sql = "UPDATE tags_content_mapping set status = 'deleted',modificationTime='$date' where tag_id IN (?) and status='live'";
		$query = $this->dbHandle->query($sql, array($data));	

		$sql = "UPDATE tuserFollowTable set status='deleted' where entityId IN (?) and entityType = 'tag'";
		$query = $this->dbHandle->query($sql, array($data));
	
		return $result;	
	}


	public function findCountOfTaggedQuestion($tagsArray){

		$this->initiateModel('read');

		$in_query = implode("','", $tagsArray);
		$sql = "SELECT COUNT(distinct content_id,content_type) AS cnt FROM tags_content_mapping WHERE tag_id IN (?) AND status = 'live'";	
		
		$query = $this->dbHandle->query($sql, array($tagsArray));
		$result = $query->row_array();
		return $result['cnt'];

	}

	public function findCountOfTaggedQuestionWithOnlyTags($tagsArray){

		$this->initiateModel('read');
		
		$in_query = implode("','", $tagsArray);

		$sql = "SELECT count(distinct content_id,content_type) as cnt from tags_content_mapping where content_id NOT IN (SELECT content_id from tags_content_mapping where tag_id NOT IN (?) and status = 'live') and status = 'live'";
		
		$query = $this->dbHandle->query($sql, array($tagsArray));
		$result = $query->row_array();
		return $result['cnt'];

	}

	public function findTaggedQuestion($tagsArray){

		$this->initiateModel('read');
		$in_query = implode("','", $tagsArray);
		$sql = "SELECT distinct content_id,content_type FROM tags_content_mapping WHERE tag_id IN (?) AND status = 'live' ORDER BY id desc limit 50";		
		$query = $this->dbHandle->query($sql, array($tagsArray));
		$result = $query->result_array();
		return $result;	

	}

	public function findTaggedQuestionWithOnlyTags($tagsArray){
		$this->initiateModel('read');
		$in_query = implode("','", $tagsArray);

		$sql = "SELECT distinct content_id,content_type  from tags_content_mapping where content_id NOT IN (SELECT content_id from tags_content_mapping where tag_id NOT IN (?) and status = 'live') and status = 'live' ORDER BY id desc limit 50";

		$query = $this->dbHandle->query($sql, array($tagsArray));
		$result = $query->result_array();
		return $result;		

	}


	public function checkParentChildMapping($tag_id,$parent_id) {
		$this->initiateModel('read');
		$sql = "SELECT * FROM tags_parent WHERE tag_id = ? AND parent_id = ? AND status = 'live' limit 1";		
		$query = $this->dbHandle->query($sql, array($tag_id,$parent_id));
		$result = $query->row_array();
		if(empty($result) || $result == null){		
			return false;
		} else {		
			return true;
		}
	}


	public function findParentTags($tag_id) {
		$this->initiateModel('read');
		$sql = "SELECT a.parent_id as parentId,b.tags as parentName FROM tags_parent a JOIN tags b ON a.parent_id = b.id".
			" WHERE a.status = 'live' and b.status = 'live' and a.tag_id = ?";
		$query = $this->dbHandle->query($sql, array($tag_id));
		$result = $query->result_array();
		return $result;
	}

	public function findParentTagsMultiple($tag_id_array) {
		$this->initiateModel('read');
		$in_query = implode("','", $tag_id_array);
		$sql = "SELECT a.parent_id as parentId,b.tags as parentName FROM tags_parent a JOIN tags b ON a.parent_id = b.id".
			" WHERE a.status = 'live' and b.status = 'live' and a.tag_id IN (?)";
		$query = $this->dbHandle->query($sql, array($tag_id_array));
		$result = $query->result_array();
		return $result;
	}

	public function renameTag($tag_id,$newTagName){
		$this->initiateModel('write');
		$sql = "UPDATE tags set tags = '".$this->dbHandle->escape_str($newTagName)."' WHERE id = ?";
		$query = $this->dbHandle->query($sql, array($tag_id));
		$cnt = $this->db->affected_rows();
	}

	public function addSynonyms($tag_id,$tag_synonyms){
		
		$dataToBeInserted = array();
		$tempArray = array();
		$tag_entity = $this->fetchTagEntity($tag_id);
		$this->initiateModel('write');
		$tag_entity_syn = $tag_entity." synonym";
		
		foreach ($tag_synonyms as $key => $syn) {
			
			$tempArray['tags'] = trim($syn);
			$tempArray['description'] = "";
			$tempArray['tag_entity'] = trim($tag_entity_syn);
			$tempArray['main_id'] = $tag_id;
			$tempArray['creationTime']= date("Y-m-d H:i:s");
			$dataToBeInserted[] = $tempArray;
		}

		$query = $this->dbHandle->insert_batch('tags',$dataToBeInserted);
	}

	public function deleteSynonyms($tag_id,$tag_synonyms){
		$this->initiateModel('write');	
	
		$in_query = implode("','", $tag_synonyms);
		$sql = "UPDATE tags set status ='deleted' where main_id = ? AND id IN (?)";
		$this->dbHandle->query($sql, array($tag_id,$tag_synonyms));
	}

	public function deleteParentChildMappings($tag_id,$parent_ids_array){
		$this->initiateModel('write');	
		$in_query = implode("','", $parent_ids_array);
		$sql = "UPDATE tags_parent set status = 'deleted' WHERE tag_id = ? AND parent_id IN (?)";
		$this->dbHandle->query($sql, array($tag_id,$parent_ids_array));	
	}

	public function insertTrackingDetails($action,$subaction,$tag_id,$data,$email){

		$this->initiateModel('write');
		$sql = "INSERT INTO tags_tracking_details(action,subaction,tag_id,data,email) VALUES(?,?,?,?,?)";
		$query = $this->dbHandle->query($sql,array($action,$subaction,$tag_id,$data,$email));
	}

	public function addUpdateParentChildMapping($tag_id,$parent_id){
		$this->initiateModel('write');
		$sql = "UPDATE tags_parent set status = 'live' where tag_id = ? and parent_id = ?";
		$query = $this->dbHandle->query($sql, array($tag_id,$parent_id));

		if($this->dbHandle->affected_rows() == 0){
			$date = date("Y-m-d H:i:s");
			$sql = "INSERT into tags_parent(tag_id,parent_id,status,creationTime) VALUES(?,?,?,?)";
			$query = $this->dbHandle->query($sql,array($tag_id,$parent_id,'live',$date));
		}

	}

	public function updateParentMapping($old_parent_id,$new_parent_id,$childs_array){
		$this->initiateModel('write');
		$in_query = implode("','", $childs_array);
		$sql = "UPDATE tags_parent set parent_id  = ? where parent_id = ? AND tag_id IN (?)";
		$query = $this->dbHandle->query($sql, array($new_parent_id,$old_parent_id,$childs_array));
	}

	public function fetchManualAutoSuggestorSuggestions($val){
		$this->initiateModel('read');
		$sql = "SELECT id,tags as value from tags where tags LIKE '%".$val."%' and tag_entity like '%synonym%' and status = 'live' limit 20";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}

	public function findSynOfTag($tag_id){
		$this->initiateModel('read');
		$sql = "select a.tags from tags a,tags b where a.id=b.main_id and b.id = ?";
		$query = $this->dbHandle->query($sql, array($tag_id));
		$result = $query->row_array();
		return $result['tags'];
	}

	public function findChildTags($tags_array){

		$this->initiateModel('read');
		$in_query = implode("','", $tags_array);
		$sql = "SELECT tag_id,parent_id from tags_parent ".
				" where parent_id IN (?) and status='live'";

		$query = $this->dbHandle->query($sql, array($tags_array));
		$result = $query->result_array();
		return $result;
	}

	public function findTagById($tagsArray){
		$this->initiateModel('read');
		$in_query = implode("','", $tagsArray);
		$sql = "SELECT tags from tags where id IN (?)";

		$query = $this->dbHandle->query($sql, array($tagsArray));
		return $query->result_array();
	}

	public function findLastNDaysQuestions($n=10){
		$this->initiateModel('read');
		$sql = "SELECT threadId,msgTxt,userId FROM messageTable WHERE creationDate > ( CURDATE() - INTERVAL ".$n." DAY ) and parentId = 0 and fromOthers = 'user' and status IN('live','closed')";
		$query = $this->dbHandle->query($sql);
		return $query->result_array();
	}

	public function findLastNDaysDiscussions($n=10){
		$this->initiateModel('read');
		$sql = "SELECT threadId,msgTxt,userId FROM messageTable WHERE creationDate > ( CURDATE() - INTERVAL ".$n." DAY ) and parentId = threadId and fromOthers = 'discussion' and status IN('live','closed')";
		$query = $this->dbHandle->query($sql);
		return $query->result_array();
	}

	public function insertTagContentMapping($data){
		$this->initiateModel('write');
		$insertData = array();
		foreach ($data as $content_type => $dataArray) {
			foreach ($dataArray as $threadId => $actualTagsArray) {
				foreach ($actualTagsArray as $tag_type => $value) {
					foreach ($value as $tag_id) {
						$tempArray = array();
						$tempArray['content_type'] = $content_type;
						$tempArray['content_id'] = $threadId;
						$tempArray['tag_type'] = $tag_type;
						$tempArray['tag_id'] = $tag_id;
						$insertData[] = $tempArray;
					}
					
				}
			}
		}
		$this->dbHandle->insert_batch('tags_content_mapping',$insertData);
	}


	public function findIfChangesDone($change='any'){
		$this->initiateModel('write');
		$days_ago = date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))));
		$whereClause = "";
		$sql = "";
		if($change == 'add'){
			$whereClause = "and action = 'addtoRedis'";
			$sql = "SELECT count(*) as cnt FROM tags_tracking_details WHERE DATE(timestamp) = DATE_ADD(CURDATE(), INTERVAL -1 DAY) $whereClause ";
		}else {
			$sql = "SELECT count(*) as cnt FROM tags_tracking_details WHERE DATE(timestamp) >= '$days_ago' $whereClause ";	
		}

		
		
		$result = $this->dbHandle->query($sql);
		$row = $result->row_array();
		if($row['cnt'] > 0){
			echo "return true";
			return true;
		} else{
			echo "return false";
			return false;
		}
	
	}

	public function findIfMultipleTagExists($tagsArray){
		$this->initiateModel('read');

		foreach ($tagsArray as $tagName) {
			$sql = "SELECT tags ,tag_entity FROM tags WHERE tags = '".$this->dbHandle->escape_str($tagName)."' AND status = 'live'";
			$query = $this->dbHandle->query($sql);
			$result = $query->result_array();	
			if(!empty($result)){
				return $result[0]['tags'];
			}
		}
		return false;
			
	}

	public function deleteTagListing($tag_id = 0){
		$this->initiateModel('write');
		$sql = "update tags_entity set status = 'deleted' where status = 'live' and tag_id = ?";
		$query = $this->dbHandle->query($sql, array($tag_id));
	}
	
	public function checkThreadIdsForValidation($threadIds = array()){
		if(empty($threadIds)){
			return array();
		}
		$sql = "SELECT threadId, fromOthers FROM messageTable WHERE"
				." (fromOthers='discussion' AND parentId = threadId AND mainAnswerId = 0)"
				." OR (fromOthers='user' AND msgId = threadId AND mainAnswerId = -1)"
				." AND status IN ('live','closed') AND threadId IN (?) ";
		$this->initiateModel('read');
		$resultSet = $this->dbHandle->query($sql, array($threadIds))->result_array();
		$result = array();
		foreach ($resultSet as $data){
			$result[$data['threadId']]['threadId']		= $data['threadId'];
			$result[$data['threadId']]['threadType']	= ($data['fromOthers'] == 'user')?'question':$data['fromOthers'];
		}
		return $result;
	}

	function getPendingTagPostingActions($id){

		$this->initiateModel('read');
		$inputArr = array();

		$sql = "SELECT id,entityId,entityType,action,additionsParams FROM tagsPendingAction WHERE status='pending'";

		if($id){
			$sql .= " AND id = ?";
			$inputArr[] = $id;
		}

		$query = $this->dbHandle->query($sql, $inputArr);
		return $query->result_array();	
	}

	function updatePendingTagPostingStatus($status,$id){
		$this->initiateModel('write');
		if(trim($status) != "" && trim($id) != ""){
			$sql = "update tagsPendingAction set status = ? where id = ?";	
			$query = $this->dbHandle->query($sql,array($status,$id));
		}
	}

	function getEntityData($entityIds,$deletedEntityId=array()){
		
		$result = array();
		if(empty($entityIds))
			return $result;

		$this->initiateModel('read');
		if($entityIds['Stream']){

			$sql = "SELECT stream_id,name,status FROM streams WHERE stream_id IN (?) AND status='live'";
			$resultSet = $this->dbHandle->query($sql, array($entityIds['Stream']))->result_array();
			foreach ($resultSet as $value) {
				$result['Stream'][$value['stream_id']] = $value;
			}

			if($deletedEntityId['Stream']){
				$sql = "SELECT stream_id, name, status from streams where stream_id IN (?) AND status = 'deleted'";
				$resultSet = $this->dbHandle->query($sql, array($entityIds['Stream']))->result_array();
				foreach ($resultSet as $value) {
					$result['Stream'][$value['stream_id']] = $value;
				}
			}
		}

		if($entityIds['Substream']){

			$sql = "SELECT substream_id,name, status FROM substreams WHERE substream_id IN (?) AND status='live'";
			$resultSet = $this->dbHandle->query($sql, array($entityIds['Substream']))->result_array();
			foreach ($resultSet as $value) {
				$result['Substream'][$value['substream_id']] = $value;
			}

			if($deletedEntityId['Substream']){
				$sql = "SELECT substream_id, name, status from substreams where substream_id IN (?) AND status = 'deleted'";
				$resultSet = $this->dbHandle->query($sql, array($entityIds['Substream']))->result_array();
				foreach ($resultSet as $value) {
					$result['Substream'][$value['substream_id']] = $value;
				}
			}
		}

		if($entityIds['Specialization']){

			$sql = "SELECT specialization_id,name, status FROM specializations WHERE specialization_id IN (?) AND status='live'";
			$resultSet = $this->dbHandle->query($sql, array($entityIds['Specialization']))->result_array();
			foreach ($resultSet as $value) {
				$result['Specialization'][$value['specialization_id']] = $value;
			}

			if($deletedEntityId['Specialization']){
				$sql = "SELECT specialization_id, name, status from specializations where specialization_id IN (?) AND status = 'deleted'";
				$resultSet = $this->dbHandle->query($sql, array($entityIds['Specialization']))->result_array();
				foreach ($resultSet as $value) {
					$result['Specialization'][$value['specialization_id']] = $value;
				}
			}
		}


		if($entityIds['Base-course']){

			$sql = "SELECT base_course_id ,name, status FROM base_courses WHERE base_course_id IN (?) AND status='live'";
			$resultSet = $this->dbHandle->query($sql, array($entityIds['Base-course']))->result_array();
			foreach ($resultSet as $value) {
				$result['Base-course'][$value['base_course_id']] = $value;
			}

			if($deletedEntityId['Base-course']){
				$sql = "SELECT base_course_id, name, status from base_courses where base_course_id IN (?) AND status = 'deleted'";
				$resultSet = $this->dbHandle->query($sql, array($entityIds['Base-course']))->result_array();
				foreach ($resultSet as $value) {
					$result['Base-course'][$value['base_course_id']] = $value;
				}
			}
		}

		if($entityIds['institute']){

			$sql = "SELECT listing_id ,name, status FROM  shiksha_institutes WHERE listing_id IN (?) AND status IN('live','draft') and listing_type = 'institute'";
			$resultSet = $this->dbHandle->query($sql, array($entityIds['institute']))->result_array();
			foreach ($resultSet as $value) {
				$result['institute'][$value['listing_id']] = $value;
			}

			if($deletedEntityId['institute']){
				$sql = "SELECT listing_id, name, status from shiksha_institutes where listing_id IN (?) AND status = 'deleted' and listing_type = 'institute'";
				$resultSet = $this->dbHandle->query($sql, array($entityIds['institute']))->result_array();
				foreach ($resultSet as $value) {
					$result['institute'][$value['listing_id']] = $value;
				}
			}


		}

		if($entityIds['National-University']){

			$sql = "SELECT listing_id ,name, status FROM  shiksha_institutes WHERE listing_id IN (?) AND status IN('live','draft') and listing_type = 'university'";
			$resultSet = $this->dbHandle->query($sql, array($entityIds['National-University']))->result_array();
			foreach ($resultSet as $value) {
				$result['National-University'][$value['listing_id']] = $value;
			}

			if($deletedEntityId['National-University']){
				$sql = "SELECT listing_id, name, status from shiksha_institutes where listing_id IN (?) AND status = 'deleted' and listing_type = 'university'";
				$resultSet = $this->dbHandle->query($sql, array($entityIds['National-University']))->result_array();
				foreach ($resultSet as $value) {
					$result['National-University'][$value['listing_id']] = $value;
				}
			}
		}
		if($entityIds['Popular-groups']){

			$sql = "SELECT popular_group_id, name, status FROM   popular_groups WHERE  popular_group_id  IN (?) AND status='live'";
			$resultSet = $this->dbHandle->query($sql, array($entityIds['Popular-groups']))->result_array();
			foreach ($resultSet as $value) {
				$result['Popular-groups'][$value['popular_group_id']] = $value;
			}

			if($deletedEntityId['Popular-groups']){
				$sql = "SELECT popular_group_id, name, status from popular_groups where popular_group_id IN (?) AND status = 'deleted'";
				$resultSet = $this->dbHandle->query($sql, array($entityIds['Popular-groups']))->result_array();
				foreach ($resultSet as $value) {
					$result['Popular-groups'][$value['popular_group_id']] = $value;
				}
			}
		}
		if($entityIds['Certificate-Provider']){

			$sql = "SELECT certificate_provider_id ,name, status FROM  certificate_providers WHERE certificate_provider_id IN (?) AND status='live'";
			$resultSet = $this->dbHandle->query($sql, array($entityIds['Certificate-Provider']))->result_array();
			foreach ($resultSet as $value) {
				$result['Certificate-Provider'][$value['certificate_provider_id']] = $value;
			}

			if($deletedEntityId['Certificate-Provider']){
				$sql = "SELECT certificate_provider_id, name, status from certificate_providers where certificate_provider_id IN (?) AND status = 'deleted'";
				$resultSet = $this->dbHandle->query($sql, array($entityIds['Certificate-Provider']))->result_array();
				foreach ($resultSet as $value) {
					$result['Certificate-Provider'][$value['certificate_provider_id']] = $value;
				}
			}
		}
		if($entityIds['Careers']){

			$sql = "SELECT careerId ,name, status FROM  CP_CareerTable WHERE careerId IN (?) AND status IN ('draft','live')";
			$resultSet = $this->dbHandle->query($sql, array($entityIds['Careers']))->result_array();
			foreach ($resultSet as $value) {
				$result['Careers'][$value['careerId']] = $value;
			}

			if($deletedEntityId['Careers']){
				$sql = "SELECT careerId ,name, status from CP_CareerTable where careerId IN (?) AND status = 'deleted'";
				$resultSet = $this->dbHandle->query($sql, array($entityIds['Careers']))->result_array();
				foreach ($resultSet as $value) {
					$result['Careers'][$value['careerId']] = $value;
				}
			}
		}
		if($entityIds['Exams']){

			$sql = "SELECT id ,name, status FROM  exampage_main WHERE id IN (?) AND status='live'";
			$resultSet = $this->dbHandle->query($sql, array($entityIds['Exams']))->result_array();
			foreach ($resultSet as $value) {
				$result['Exams'][$value['id']] = $value;
			}

			if($deletedEntityId['Exams']){
				$sql = "SELECT id ,name, status from exampage_main where id IN (?) AND status = 'deleted'";
				$resultSet = $this->dbHandle->query($sql, array($entityIds['Exams']))->result_array();
				foreach ($resultSet as $value) {
					$result['Exams'][$value['id']] = $value;
				}
			}
		}



		return $result;
	}

	function getTagsByName($tagNames){
	
		$this->initiateModel('read');
		$tagNameQuery = array();
		foreach ($tagNames as $val) {
			$tagNameQuery[] = $this->dbHandle->escape($val);
		}
		$sql = "SELECT id,tags FROM tags WHERE tags IN (?) AND status='live'";
		$query = $this->dbHandle->query($sql, array($tagNameQuery));
		return $query->result_array();	
	}

	public function fetchTagsInfo($idsArray){
		$result = array();
		if(empty($idsArray)) return $idsArray;
		$this->initiateModel('read');
		$sql = "SELECT id,tags from tags where id IN (?)";
		$result = $this->dbHandle->query($sql, array($idsArray))->result_array();
		return $result;
	}

	public function renameMultipleTags($tagsInfo,$oldTagName,$newTagName){

		if(empty($tagsInfo)) return;
		$this->initiateModel('write');
		$updateQuery = "UPDATE tags SET tags = CASE";
     	$inClause = "WHERE id IN (";
		foreach ($tagsInfo as $key => $value) {
			$tagsInfo[$key]['tags'] = str_replace($oldTagName, $newTagName, $value['tags']);
            $tagId = $value['id'];
            $updateQuery .= " WHEN id = ".$tagId." THEN ".$this->dbHandle->escape($tagsInfo[$key]['tags']);
            $inClause .= $tagId.",";
		}
		$inClause = substr($inClause, 0,-1).")";
		$updateQuery .= " END $inClause";

		$this->dbHandle->query($updateQuery);
	}


/*	function discardPendingTagAction($rowId){

		if(empty($rowId))
			return;

		$this->initiateModel('write');
		
		$data                     = array();
		$data['status']           = 'discard';
		$data['modificationDate'] = date("Y-m-d H:i:s");

        $this->dbHandle->where('id',$rowId);
        $status = $this->dbHandle->update('tagsPendingAction',$data);
	}*/

	public function fetchTagsMappings($tagId){
		$this->initiateModel('read');
		$sql = "select * from tags_entity where tag_id = ? and status = 'live'";
		$query = $this->dbHandle->query($sql, array($tagId));
		$result = $query->result_array();
		return $result;
	}

	public function fetchMultipleTagsMappings($tagIds = array()){
		if(empty($tagIds)) return array();
		$this->initiateModel('read');
		$sql = "select * from tags_entity where tag_id IN (?) and status = 'live'";
		$query = $this->dbHandle->query($sql, array($tagIds));
		$result = $query->result_array();
		
		$finalResult = array();
		foreach ($result as $key => $value) {
			$finalResult[$value['tag_id']][] = array('id' => $value['entity_id'], 'type' => $value['entity_type']);
		}
		return $finalResult;
	}

	public function fetchShikshaEntityToTagsMapping($entityId, $entityType){
		$this->initiateModel('read');
		$sql = "SELECT a.tag_id as tagId, b.tags as tagName from tags_entity a join tags b ON(a.tag_id = b.id) where a.entity_id = ? and a.entity_type = ? and a.status = 'live' and b.status = 'live' LIMIT 1";
		$query = $this->dbHandle->query($sql,array($entityId,$entityType));
		$result = $query->row_array();
		return $result;
	}

	public function updateSingleMapping($entity,$entityId,$tagId,$status){
		$this->initiateModel('write');
		$sql = "UPDATE tags_entity set status = ? where entity_type = ? and entity_id = ? and tag_id = ? and status = 'live'";
		$query = $this->dbHandle->query($sql,array($status,$entity,$entityId,$tagId));
		return $result;
	}

	public function updateAllMappingsForTag($tagId,$shikshaEntity,$shikshaEntityId,$totalShikshaEntities,$action) {

		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		if($action == "edit"){
			$sql = "update tags_entity set status = 'deleted' where tag_id = ? and entity_type IN (?)";
			$query = $this->dbHandle->query($sql,array($tagId, $totalShikshaEntities));	
		}
		
		$insertData = array();
		foreach ($shikshaEntity as $key => $value) {
			if($shikshaEntity[$key] != "" && $shikshaEntityId != ""){
				$tempArray = array();
				$tempArray['tag_id'] = $tagId;
				$tempArray['entity_id'] = $shikshaEntityId[$key];
				$tempArray['entity_type'] = $shikshaEntity[$key];
				$tempArray['creationTime'] = date("Y-m-d H:i:s");
				$alreadyThere = false;
				foreach ($insertData as $key => $value) {
					if(($value['entity_id'] == $tempArray['entity_id']) && $value['entity_type'] == $tempArray['entity_type']){
						$alreadyThere = true;
					}
				}
				if(!$alreadyThere){
					$insertData[] = $tempArray;	
				}
			}
		}

		if(!empty($insertData)){
			$this->dbHandle->insert_batch('tags_entity',$insertData);
		}
		$this->dbHandle->trans_complete();
	}

	public function getShikshaEntityMappings($inputMapping){

		$result = array();
		if(empty($inputMapping))
			return $result;

		$this->initiateModel('read');

		$sql = "SELECT id,tag_id,entity_id,entity_type from tags_entity where status='live' ";

		$whereClause = array();
		foreach ($inputMapping as $key => $value) {
			$whereClause[] = "( entity_type	= '".$key."' AND entity_id IN (".implode(',', $value)."))";
		}
		$whereClause = implode(' OR ', $whereClause);

		if($whereClause)
			$sql .= " AND (".$whereClause.")";

		$query = $this->dbHandle->query($sql);

		$result = $query->result_array();
		$finalResult = array();
		foreach ($result as $key => $value) {
			$finalResult[$value['entity_type']][$value['entity_id']] = $value;
		}
		return $finalResult;
	}

	public function fetchTagsOnThreads($qids=array()){
		$result = array();

		if(empty($qids))
			return $result;

		$this->initiateModel('read');
		$sql = "SELECT group_concat(tag_id) as allTagsId,content_id from tags_content_mapping where content_id IN (?) and status = 'live' group by content_id";
		$query = $this->dbHandle->query($sql, array($qids));
		$result = $query->result_array();
		return $result;
	}

	public function insertTagsPendingActions($entityType='',$entityId=0,$action='Add'){
		$this->initiateModel('write');
		$sql = "SELECT * from tagsPendingAction where entityType = ? and entityId = ? and status = 'pending' LIMIT 1";
		$query = $this->dbHandle->query($sql,array($entityType,$entityId));
		$result = $query->row_array();

		if(empty($result)){
			$sql = "INSERT IGNORE INTO tagsPendingAction(entityType,entityId,action) VALUES(?,?,?)";
			$this->dbHandle->query($sql,array($entityType,$entityId,$action));	
		}else{
			$id = $result['id'];
			$sql = "UPDATE tagsPendingAction set action = ? where id = ?";
			$this->dbHandle->query($sql,array($action,$id));
		}
		
	}

	public function fetchMappingsForEntity($entity=array(), $entityId=array()){
		if(empty($entity) || empty($entityId)) return array();
		$this->initiateModel('read');
		$whereClause = "";
		foreach ($entity as $key => $value) {
			$whereClause .= "(entity_type = '".$entity[$key]."' and entity_id = '".$entityId[$key]."' ) OR ";
		}

		$whereClause = trim($whereClause, " OR ");
		$sql = "select * from tags_entity where status = 'live' and (".$whereClause.")";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}

}

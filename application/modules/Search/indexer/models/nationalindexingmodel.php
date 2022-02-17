<?php
class nationalindexingmodel extends MY_Model {
	private $dbHandle = '';
	private $CI;
   
    function __construct(){
		parent::__construct('Search');
		$this->CI = &get_instance();
		$this->CI->load->config('indexer/nationalIndexerConfig');
    }
	
	private function initiateModel($mode = "read"){
		$this->dbHandle = NULL;
		if($mode == 'read'){
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
	}

	public function getCRIndexQueueEntries($offset = 0, $limit = 1000, $status = 'pending', $delay='',$startDate=''){
		$data = array();
		$this->initiateModel();

		$this->dbHandle->select('id,listing_id,listing_type,section_updated AS sections,extraData,operation');
		$this->dbHandle->from('indexlog');
		$this->dbHandle->where('listing_type','collegereview');
		
		if($startDate != ''){
			$this->dbHandle->where('listing_added_time >=', $startDate);
		}else{
			$this->dbHandle->where('status',$status);
			$this->dbHandle->limit($limit, $offset);
		}
		
		if(!empty($delay) && $delay>0){
			$delayTime = date('Y-m-d H:i:s',strtotime('- '.$delay.' minutes'));
			$this->dbHandle->where('indexing_finish_time <', $delayTime);
		}
		$data = $this->dbHandle->get()->result_array();
		return $data;
	}


	public function getIndexQueueEntries($offset = 0, $limit = 1000,$startDate){
		$this->initiateModel();
		$sqlData = array();

		$queryCmd ="SELECT id,
					listing_id,
					listing_type,
					section_updated AS sections,
					extraData,
					operation 
					FROM indexlog
					WHERE listing_type IN ('university','institute','course','exam','question','article')";
		if($startDate != ''){
			$sqlData = array($startDate);
			$queryCmd.= " AND listing_added_time  >= ?";
		}else{
			$sqlData = array($offset, $limit);
			$queryCmd.= " AND status  = 'pending' LIMIT ?, ?";
		}
		
		$query = $this->dbHandle->query($queryCmd, $sqlData);
		$data = array();
		foreach($query->result() as $row) {
			$data[] = (array)$row;
		}
		return $data;
	}

	public function setIndexingStatus($id=array(),$status='processing'){
		$id = array_filter(array_unique($id));
		if(empty($id)) return;
		$this->initiateModel('write');
		$timeColumn = "indexing_start_time";
		if($status == "complete" || $status == 'failed'){
			$timeColumn = "indexing_finish_time";
		}
		$queryCmd = "UPDATE indexlog SET status = ?, $timeColumn = NOW() WHERE id IN(?)";
		$query = $this->dbHandle->query($queryCmd,array($status, $id));
		echo $this->dbHandle->last_query();
	}

	public function fetchInsIds(){
		$this->initiateModel();
		$sql = "select listing_type_id from listings_main where status = 'live' and listing_type IN ('institute','university_national') order by listing_type_id desc";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		foreach ($result as $key => $value) {
			$finalResult[] = $value['listing_type_id'];
		}
		return $finalResult;
	}

	public function fetchInsIdsWithAbbreviation() {
		$this->initiateModel();
		
		$sql = "select listing_id from shiksha_institutes where status = 'live' and abbreviation is not null order by listing_id";
		$result = $this->dbHandle->query($sql)->result_array();
		
		foreach ($result as $key => $value) {
			$finalResult[] = $value['listing_id'];
		}
		return $finalResult;
	}


	public function fetchAllEntities($entity_type=null,$entity_ids){
		if(empty($entity_type)){
			return array();
		}
		$this->initiateModel();
		$tableName = "";
		$columns = array();

		switch ($entity_type) {
			case 'stream':
				$tableName = "streams";
				$columns = array('name','stream_id as entity_id','synonym','alias');
				break;

			case 'substream':
				$tableName = "substreams";
				$columns = array('name','substream_id as entity_id','synonym','alias');
				break;

			case 'specialization':
				$tableName = "specializations";
				$columns = array('name','specialization_id as entity_id','synonym','alias');
				break;

			case 'base_course':
				$tableName = "base_courses";
				$columns = array('name','base_course_id as entity_id','synonym','alias');
				break;

			case 'certificate_provider':
				$tableName = "certificate_providers";
				$columns = array('name','certificate_provider_id as entity_id','synonym','alias');
				break;

			case 'popular_group':
				$tableName = "popular_groups";
				$columns = array('name','popular_group_id as entity_id','synonym','alias');
				break;
		}


		if(empty($columns) || $tableName == "") return array();

		$sql = "SELECT ".implode(",", $columns)." from ".$tableName." where status = 'live'";
		
		if(!empty($entity_ids) && !empty($entity_ids[0])){
			$sql .= " AND {$entity_type}_id in (?)";
		}

		$query = $this->dbHandle->query($sql, array($entity_ids))->result_array();

		$result = array();
		foreach ($query as $row) {
			$result[$row['entity_id']] = $row;
		}
		return $result;
	}


	public function fetchResultCountForEntities($entity_type = 'stream', $entity_id=0, $locationType, $locationId){
		$this->initiateModel();
		$whereClause = "";
		$JOIN = "";

		switch ($entity_type) {
			case 'stream':
				$whereClause = " AND ctype.stream_id = ? ";
				break;

			case 'substream':
				$whereClause = " AND ctype.substream_id = ?";	
				break;

			case 'specialization':
				$whereClause = " AND ctype.specialization_id = ?";	
				break;

			case 'base_course':
				$whereClause = " AND ctype.base_course = ?";
				break;

			case 'certificate_provider':
				$JOIN = " JOIN entity_course_mapping ecm ON (ecm.entity_id = ? and ecm.entity_type = 'certificate_provider' and ctype.base_course = ecm.base_course_id) ";
				$whereClause = " AND ecm.status = 'live'";
				break;

			case 'popular_group':

				$JOIN = " JOIN entity_hierarchy_mapping ehm ON(ehm.entity_id = ? and ehm.entity_type = 'popular_group' and ehm.stream_id = ctype.stream_id and case ctype.substream_id when 0 then ehm.substream_id is null else ehm.substream_id = ctype.substream_id end and case ctype.specialization_id when 0 then ehm.specialization_id is null else ehm.specialization_id = ctype.specialization_id end)";
				$whereClause = "AND ehm.status = 'live'";
				break;
		}

		switch ($locationType) {
			case 'city':
				$JOIN = " JOIN shiksha_institutes_locations sil on sil.listing_id = sc.primary_id AND sil.status = 'live' ".
						" JOIN virtualCityMapping vcm on vcm.city_id = sil.city_id AND vcm.virtualCityId = ? ";
				$params[] = $locationId;
				break;
			
			case 'state':
				$JOIN = " JOIN shiksha_institutes_locations sil on sil.listing_id = sc.primary_id AND sil.status = 'live' AND sil.state_id = ? ";
				$params[] = $locationId;
				break;
		}

		$params[] = $entity_id;

		$sql = "SELECT DISTINCT sc.primary_id from shiksha_courses sc JOIN shiksha_courses_type_information ctype on sc.course_id = ctype.course_id ".$JOIN."WHERE sc.status = 'live' and ctype.status = 'live'".$whereClause." GROUP by sc.primary_id";
		
		$query = $this->dbHandle->query($sql, $params);
		// _p($this->dbHandle->last_query()); //die;
		//error_log("\n query: ".$this->dbHandle->last_query(), 3, '/tmp/nikita.log');

		$resultCount = $query->num_rows();
		return $resultCount;
	}


	public function aaa(){
		$this->initiateModel();
		$sql = "SELECT stream_id,substream_id,specialization_id,base_course,course_id from shiksha_courses_type_information where status = 'live'";
		$query = $this->dbHandle->query($sql);
		$result  = $query->result_array();
		return $result;
	}

	public function addToIndexQueue($listingId=null, $listingType=null,$operation='index', $sections=array(),$extraData = null){
		if(empty($listingId) || empty($listingType)){
			return;
		}
		if(empty($operation)){
			$operation = "index";
		}
		$this->initiateModel('write');
		$sectionsString = "";
		$sections = array_unique(array_filter($sections));
		$dataToInsert = array();
		if(!empty($sections)){
			foreach ($sections as $section_updated) {
				$temp = array();
				$temp['operation'] = $operation;
				$temp['listing_type'] = $listingType;
				$temp['listing_id'] = $listingId;
				$temp['section_updated'] = $section_updated;
				$temp['extraData'] = $extraData;
				$dataToInsert[] = $temp;
			}
		}else{
			$temp = array();
			$temp['operation'] = $operation;
			$temp['listing_type'] = $listingType;
			$temp['listing_id'] = $listingId;
			$dataToInsert[] = $temp;
		}

		$this->dbHandle->insert_batch('indexlog',$dataToInsert);
		
	}

	public function addfullIndexQueue($listingIds=null, $listingType=null,$operation='index'){
		if(empty($listingIds) || empty($listingType)){
			return;
		}
		if(empty($operation)){
			$operation = "index";
		}
		$this->initiateModel('write');
		$dataToInsert = array();
		foreach ($listingIds as $listingId) {
			$temp = array();
			$temp['operation']    = $operation;
			$temp['listing_type'] = $listingType;
			$temp['listing_id']   = $listingId;
			$dataToInsert[]       = $temp;
		}
		$this->dbHandle->insert_batch('indexlog',$dataToInsert);
	}

	
	public function fetchEntity($entity_type=null, $entity_id=null){
		if($entity_type == null || $entity_id == null){
			return array();
		}
		
		$this->initiateModel();
		$tableName = "";
		$columns = array();
		$where = "";
		switch ($entity_type) {
			case 'stream':
				$tableName = "streams";
				$columns = array('name','stream_id as entity_id','synonym');
				$where = "stream_id = ?";
				break;

			case 'substream':
				$tableName = "substreams";
				$columns = array('name','substream_id as entity_id','synonym');
				$where = "substream_id = ?";
				break;

			case 'specialization':
				$tableName = "specializations";
				$columns = array('name','specialization_id as entity_id','synonym');
				$where = "specialization_id = ?";
				break;

			case 'base_course':
				$tableName = "base_courses";
				$columns = array('name','base_course_id as entity_id','synonym');
				$where = "base_course_id = ?";
				break;

			case 'certificate_provider':
				$tableName = "certificate_providers";
				$columns = array('name','certificate_provider_id as entity_id','synonym');
				$where = "certificate_provider_id = ?";
				break;

			case 'popular_group':
				$tableName = "popular_groups";
				$columns = array('name','popular_group_id as entity_id','synonym');
				$where = "popular_group_id = ?";
				break;
		}


		if(empty($columns) || $tableName == "") return array();

		$sql = "SELECT ".implode(",", $columns)." from ".$tableName." where status = 'live' AND $where";
		
		$query = $this->dbHandle->query($sql, array($entity_id));
		$result = $query->result_array();
		return $result;
	}	

	function findAccrediationId($accName=null){
		if(empty($accName))
			return 0;
		$this->initiateModel();
		$sql = "SELECT value_id from base_attribute_list where value_name = ?";
		$query = $this->dbHandle->query($sql, array($accName));
		$result = $query->row_array();
		if(empty($result)){
			return 0;
		}else{
			return $result['value_id'];
		}

	}

	function gggg1(){
		$this->initiateModel('read');
		$sql = "SELECT shi.listing_id,shc.course_id,shi.listing_type,shi.name FROM shiksha_institutes shi
				LEFT JOIN shiksha_courses shc 
				ON shi.listing_id = shc.primary_id AND shc.status = 'live'
				WHERE  shi.status = 'live'
				ORDER BY listing_id";
		$query = $this->dbHandle->query($sql);
		$result_array = $query->result_array();
		$finalResult = array();
		foreach ($result_array as $key => $value) {
			$finalResultA[$value['listing_id']."_".$value['listing_type']][] = $value['course_id'];
			$finalResultB[$value['listing_id']] = $value['name'];
		}
		$finalResult = array($finalResultA,$finalResultB);
		return $finalResult;
	}

	function gggg($data){
		$this->initiateModel('write');
		$this->dbHandle->insert_batch('shiksha_courses_institutes',$data);
	}

	function getAllCoursesForInstitutesFromFlatTable($instituteIds){
		if(empty($instituteIds)) return;
    	if(!is_array($instituteIds)) return;
    	$this->initiateModel('read');

    	$sql = "SELECT 
    			sci.course_id,
    			sci.hierarchy_parent_id,
    			sci.primary_parent_id,
    			si.listing_type,
    			si.institute_specification_type,
    			si.is_satellite
    			FROM shiksha_courses_institutes sci
    			LEFT JOIN shiksha_institutes si 
    			ON si.listing_id = sci.primary_parent_id and si.status = 'live'
    		    WHERE hierarchy_parent_id IN (?)
    		    AND anotherUnivFlag = 0
    		    AND anotherSatFlag = 0";

    	$query = $this->dbHandle->query($sql, array($instituteIds));
    	$result = $query->result_array();
    	return $result;
	}

	function getAllParentsForCoursesFromFlatTable($courseIds){
		if(empty($courseIds)) return;
    	if(!is_array($courseIds)) return;
    	$this->initiateModel('read');

    	$sql = "SELECT
    			hierarchy_parent_id,course_id
    			FROM shiksha_courses_institutes
    			WHERE course_id IN (?)";

    	$query = $this->dbHandle->query($sql, array($courseIds));
    	$result = $query->result_array();
    	
    	return $result;
	}

	function getInstituteChildHierarchyFromFlatTable($instituteIds){
		if(empty($instituteIds)) return;
    	if(!is_array($instituteIds)) return;
    	$this->initiateModel('read');

    	$sql = "SELECT 
    			DISTINCT primary_parent_id,
    			hierarchy_parent_id,
    			si.is_autonomous,
    			si.name,
    			si.listing_type,
    			si.is_satellite,
    			si.is_aiu_membership,
    			si.is_open_university,
    			si.is_ugc_approved,
    			si.university_specification_type,
    			si.institute_specification_type,
    			si.ownership,
    			si.accreditation,
    			si.is_autonomous,
    			si.is_dummy,
    			si.disabled_url
    			FROM shiksha_courses_institutes sci
    			LEFT JOIN shiksha_institutes si ON si.listing_id = sci.primary_parent_id AND si.status = 'live'
    			WHERE hierarchy_parent_id IN (?)";

    	$query = $this->dbHandle->query($sql, array($instituteIds));
    	$result = $query->result_array();
    	return $result;
	}

	function getInstituteParentHierarchyFromFlatTable($instituteIds){
		if(empty($instituteIds)) return;
    	if(!is_array($instituteIds)) return;
    	$this->initiateModel('read');

    	$sql = "SELECT 
    			DISTINCT hierarchy_parent_id,
    			primary_parent_id,
    			si.is_autonomous,
    			si.name,
    			si.listing_type,
    			si.is_satellite,
    			si.is_aiu_membership,
    			si.is_open_university,
    			si.is_ugc_approved,
    			si.university_specification_type,
    			si.institute_specification_type,
    			si.ownership,
    			si.accreditation,
    			si.is_autonomous,
    			si.is_dummy,
    			si.disabled_url
    			FROM shiksha_courses_institutes sci
    			LEFT JOIN shiksha_institutes si ON si.listing_id = sci.hierarchy_parent_id AND si.status = 'live'
    			WHERE primary_parent_id IN (?)";

    	$query = $this->dbHandle->query($sql, array($instituteIds));
    	$result = $query->result_array();
    	return $result;
	}

	public function getAllExamsHavingExamPages(){
		$this->initiateModel('read');
		//$query = $this->dbHandle->where('status','live')->select('exampage_id,exam_id,exam_name,url,exam_full_form')->get('exampage_master')->result_array();

		$this->dbHandle->select('epm.exampage_id,em.id as exam_id,eg.groupName,em.name as exam_name,em.url,em.fullname as exam_full_form');
		$this->dbHandle->from('exampage_master epm');
		$this->dbHandle->join('exampage_groups eg','eg.groupId = epm.groupId');
		$this->dbHandle->join('exampage_main em','eg.examId = em.id');
		$this->dbHandle->where('eg.isPrimary',1);
		$this->dbHandle->where('eg.status','live');
		$this->dbHandle->where('epm.status','live');
		$this->dbHandle->where('em.status','live');
		$query = $this->dbHandle->get()->result_array();

		$returnData = array();
		foreach ($query as $row) {
			if(!empty($row['url'])){
				$row['url'] = '/'.trim($row['url'],'/');
			}
			$returnData[$row['exam_id']] = $row;
		}
		
		return $returnData;
	}

	public function getExamPagesDataByExamIds($examIds){
		if(empty($examIds) || !is_array($examIds)){
			return;
		}
		$this->initiateModel('read');
		//$query = $this->dbHandle->where('status','live')->select('exampage_id,exam_id,exam_name,url,exam_full_form')->where_in('exam_id',$examIds)->get('exampage_master')->result_array();

		$this->dbHandle->select('epm.exampage_id,em.id as exam_id,,eg.groupName,em.name as exam_name,em.url,em.fullname as exam_full_form');
		$this->dbHandle->from('exampage_master epm');
		$this->dbHandle->join('exampage_groups eg','eg.groupId = epm.groupId');
		$this->dbHandle->join('exampage_main em','eg.examId = em.id');
		$this->dbHandle->where('eg.isPrimary',1);
		$this->dbHandle->where_in('em.id',$examIds);
		$this->dbHandle->where('eg.status','live');
		$this->dbHandle->where('epm.status','live');
		$this->dbHandle->where('em.status','live');
		$query = $this->dbHandle->get()->result_array();
		
		$returnData = array();
		foreach ($query as $row) {
			if(!empty($row['url'])){
				$row['url'] = '/'.trim($row['url'],'/');
			}
			$returnData[$row['exam_id']] = $row;
		}
		
		return $returnData;
	}

	public function getExamPageHierarchiesByExamIds($examIds){
		if(empty($examIds) || !is_array($examIds)){
			return;
		}
		$this->initiateModel('read');
		$sql = "SELECT em.examId,bh.stream_id,bh.substream_id,bh.hierarchy_id from base_hierarchies bh join examAttributeMapping em on em.entityId = bh.hierarchy_id and bh.status = 'live' and em.status = 'live' join exampage_groups eg ON (eg.groupId = em.groupId AND eg.isPrimary = 1) join exampage_master master on master.exam_id = em.examId and master.status = 'live' and eg.status = 'live' and em.entityType in ('primaryHierarchy','hierarchy') and em.examId in (".implode(',',array_fill(0, count($examIds), '?')).")";
		// _p($sql);die;
		return $this->dbHandle->query($sql,$examIds)->result_array();
	}

	public function getExamPageBaseCoursesByExamIds($examIds){
		if(empty($examIds) || !is_array($examIds)){
			return;
		}
		$this->initiateModel('read');
		$sql = "SELECT em.examId,em.entityId as base_course_id from examAttributeMapping em join base_courses bc on bc.base_course_id = em.entityId and bc.status='live' and bc.is_popular=1
			join exampage_groups eg ON (eg.groupId = em.groupId AND eg.isPrimary = 1) where em.status = 'live' and em.entityType = 'course' and eg.status = 'live' and em.examId in (".implode(',',array_fill(0,count($examIds),'?')).")";
		// _p($sql);die;
		return $this->dbHandle->query($sql,$examIds)->result_array();
	}

	public function fetchExamCountByEntity($entityType,$entityId){
		if(empty($entityType) || !in_array($entityType,array('stream','substream','base_course'))) {
			return;
		}

		$this->initiateModel('read');
		if($entityType == 'stream' || $entityType == 'substream'){
			if(!empty($entityId)) {
				$sql = "SELECT count(distinct em.examId) as count from examAttributeMapping em join base_hierarchies bh on bh.hierarchy_id = em.entityId and bh.status='live' and em.status='live' and em.entityType in ('primaryHierarchy','hierarchy') join exampage_groups eg ON (eg.groupId = em.groupId AND eg.isPrimary = 1 ) join exampage_master master on master.groupId = eg.groupId and eg.staus = 'live' and master.status = 'live' where bh.{$entityType}_id = ?" ;
			} else {
				$sql = "SELECT count(distinct em.examId) as count from examAttributeMapping em join base_hierarchies bh on bh.hierarchy_id = em.entityId and bh.status='live' and em.status='live' and em.entityType in ('primaryHierarchy','hierarchy') join exampage_groups eg ON (eg.groupId = em.groupId AND eg.isPrimary = 1 ) join exampage_master master on master.groupId = eg.groupId and eg.staus = 'live' and master.status = 'live' " ;
			}
		}
		else{
			if(!empty($entityId)) {
				$sql = "SELECT count(distinct em.examId) as count from examAttributeMapping em join base_courses bc on bc.status='live' and em.status='live' and bc.{$entityType}_id = em.entityId and bc.is_popular=1 and em.entityType = 'course' join exampage_groups eg ON (eg.groupId = em.groupId AND eg.isPrimary = 1 ) join exampage_master master on master.groupId = eg.groupId and eg.status = 'live' and master.status = 'live' where bc.{$entityType}_id = ?";
			} else {
				$sql = "SELECT count(distinct em.examId) as count from examAttributeMapping em join base_courses bc on bc.status='live' and em.status='live' and bc.{$entityType}_id = em.entityId and bc.is_popular=1 and em.entityType = 'course' join exampage_groups eg ON (eg.groupId = em.groupId AND eg.isPrimary = 1 ) join exampage_master master on master.groupId = eg.groupId and eg.status = 'live' and master.status = 'live' ";
			}
		}

		if(!empty($entityId)) {
			$query = $this->dbHandle->query($sql,array($entityId))->row_array();
		} else {
			$query = $this->dbHandle->query($sql)->row_array();
		}

		return $query['count'];
	}

	public function getAllQuestionsToIndex($questionId) {
		$this->initiateModel('read');

		if(!empty($questionId)) {
			$whereQuestion = " and mt.msgId = ? ";
			$params[] = $questionId;
		}
		// if(!empty($lastUpdateDuration)) {
		// 	$time = date('Y-m-d H:i:s',strtotime($lastUpdateDuration));
		// 	$whereLastUpdated = " and mt.msgId = ?";
		// 	$params[] = $questionId;
		// }

		//Manually moderated questions
		$sql = "SELECT msgId as id, msgTxt as text, q.qualityScore, mt.msgCount as answerCount, mt.creationDate, mt.status ".
				"FROM messageTable mt ".
				"LEFT JOIN threadQualityTable q on q.threadId = mt.msgId and q.threadType = 'question' ".
				"INNER JOIN messageTableModeration mm on mt.msgId = mm.entityId and moderationStatus = 'completed' ".
				"where mt.status in ('live', 'closed') and mt.parentId=0 and mt.fromOthers = 'user' ".
				$whereQuestion;

		$result = $this->dbHandle->query($sql, $params)->result_array();

		return $result;
	}

	/*
	 * Get all tags that mapped to particular question
	 */
	public function getTagsForQuestion($questionIds) {
		$this->initiateModel('read');

		if(!is_array($questionIds)) {
			$questionIds = array($questionIds);
		}

		if(!empty($questionIds)) {
			$whereQuestionTag = " and tcm.content_id IN (?) ";
			$params[] = $questionIds;
		}

		$sql = "select tcm.content_id, t.id, t.tags, t.tag_entity from tags_content_mapping tcm ".
				"inner join tags t on t.id = tcm.tag_id and t.status = 'live' ".
				"where content_type = 'question' and tcm.status = 'live' ".
				$whereQuestionTag;

		$result = $this->dbHandle->query($sql, $params)->result_array();
		
		foreach ($result as $key => $value) {
			$questionTags[$value['content_id']][$value['id']] = $value['tags'];
			
			if(!$tagIds[$value['id']]) {
				$tagIds[$value['id']] = $value['id'];
			}
		}
		
		$tagSynonyms = $this->getTagSynonyms($tagIds);
		
		foreach ($questionTags as $questionId => $questionData) {
			foreach ($questionData as $tagId => $tagName) {
				if(!empty($tagSynonyms[$tagId])) {
					$questionTags[$questionId] = $questionTags[$questionId] + $tagSynonyms[$tagId];
				}
			}
		}

		return $questionTags;
	}

	/*
	 * Get all tags that are mapped to any question
	 */
	public function getQuestionTagsToIndex($tagId) {
		$this->initiateModel('read');

		if(!empty($tagId)) {
			$whereQuestionTag = " and t.id = ? ";
			$params[] = $tagId;
		}
		$sql = "select t.id, t.tags, t.tag_entity, count(distinct content_id) as questionCount, SUM(mt.msgCount) as answerCount ".
				"from tags_content_mapping tcm ".
				"inner join tags t on t.id = tcm.tag_id and t.status = 'live' ".
				"inner join messageTable mt on tcm.content_id = mt.msgId and mt.parentId=0 and mt.fromOthers = 'user' and mt.status in ('live', 'closed') ".
				"where content_type = 'question' and tcm.status = 'live' ".
				$whereQuestionTag .
				"group by t.id";

		$result = $this->dbHandle->query($sql, $params)->result_array();

		return $result;
	}

	public function getTagSynonyms($tagIds) {
		if(empty($tagIds)) {
			return;
		}
		$this->initiateModel('read');

		$sql = "SELECT t.main_id, t.id, t.tags ".
				"FROM tags AS t ".
				"WHERE t.id != t.main_id AND t.main_id IN (?) AND t.status = 'live' ";

		$result = $this->dbHandle->query($sql, array($tagIds))->result_array();
		foreach ($result as $key => $value) {
			$synonyms[$value['main_id']][$value['id']] = $value['tags'];
		}
		return $synonyms;
	}

	public function getArticlesData($articleId) {
		$this->initiateModel('read');

		if(!empty($articleId)) {
			$whereArticleId = " and bt.blogId = ? ";
			$params[] = $articleId;
		}
		
		$data = array();
		$sql ="SELECT bt.blogId as id, bt.blogTitle as text, bt.status, bt.url, bt.creationDate, bt.lastModifiedDate, stream_id, substream_id, specialization_id ".
				"FROM blogTable AS bt ".
				"LEFT JOIN articleAttributeMapping AS am ON am.articleId = bt.blogId AND am.status = 'live' and am.entityType = 'primaryHierarchy' ".
				"LEFT JOIN base_hierarchies AS bh ON bh.hierarchy_id = am.entityId ".
				"WHERE bt.status =  'live' ".
				$whereArticleId;

		$result = $this->dbHandle->query($sql, $params)->result_array();
		
		return $result;
	}

	public function getEntitiesMappedToArticle($articleIds) {
		$this->initiateModel('read');

		if(!is_array($articleIds)) {
			$articleIds = array($articleIds);
		}

		if(!empty($articleIds)) {
			$where = " and am.articleId IN (?) ";
			$params[] = $articleIds;
		}

		$sql = "select distinct am.entityId, am.entityType, am.articleId ".
				"from articleAttributeMapping am ".
				"where am.entityType IN ('primaryHierarchy','course','exam','university','college','career','tag') and am.status = 'live' ".
				$where;

		$result = $this->dbHandle->query($sql, $params)->result_array();

		return $result;
	}

	public function getTagNameById($tagIds) {
		$this->initiateModel('read');

		if(!is_array($tagIds)) {
			$tagIds = array($tagIds);
		}
		
		$sql = "SELECT distinct id as tagId, tags as tagName FROM tags WHERE id IN (?) AND status = 'live'";

		$result = $this->dbHandle->query($sql, array($tagIds))->result_array();
		
		return $result;
	}
} ?>

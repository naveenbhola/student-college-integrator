<?php

class articleRecommendationModel extends MY_Model {
	
	private $dbHandle = '';
	
	function __construct(){
		parent::__construct('ContentRecommendation');
		$this->dbHandle = $this->getReadHandle();
		$this->load->helper('ContentRecommendation/recommend');
	}

	public function getInstituteArticles($instituteId,$exclusionList=array()){

		if(!is_array($instituteId) && $instituteId != '' && $instituteId > 0){
			$instituteId = array($instituteId);
		}
		$instituteId = array_filter($instituteId);
		if(count($instituteId) <= 0 || !is_array($instituteId)){
			return array();
		}
       	$instituteId = array_unique($instituteId);
		$this->dbHandle->where('entityId in '. "(".implode(",", $instituteId).")");
		$this->dbHandle->where('articleAttributeMapping.status', 'live');
		$this->dbHandle->where("entityType in ('group','college','university')");//no 'institute' in enum
		$this->dbHandle->where('blogTable.status', 'live');
		$this->dbHandle->where("blogType not in ('exam','examstudyabroad')");

		$exclusionClause = getExclusionClause($exclusionList,'articleId');
		if($exclusionClause!=''){
			$this->dbHandle->where($exclusionClause);
		}

		$this->dbHandle->distinct();
		$this->dbHandle->select('entityId,blogTable.blogId,blogTable.creationDate, blogRelevancy, blogView');
		$this->dbHandle->from('articleAttributeMapping');
		$this->dbHandle->join('blogTable','articleAttributeMapping.articleId=blogTable.blogId');
		$query = $this->dbHandle->get();
		$result = $query->result_array();
		
		$content = array();
		foreach ($result as $row) {
			$content[$row['entityId']][$row['blogId']]['creationDate'] = $row['creationDate'];
			$content[$row['entityId']][$row['blogId']]['blogRelevancy'] = $row['blogRelevancy'];
			$content[$row['entityId']][$row['blogId']]['blogView'] = $row['blogView'];
		}
		return $content;
	}

	public function checkContentExistForInstitute($instituteId){

		if(!is_array($instituteId) && $instituteId != '' && $instituteId > 0){
			$instituteId = array($instituteId);
		}
		$instituteId = array_filter($instituteId);
		if(count($instituteId) <= 0 || !is_array($instituteId)){
			return array();
		}
       	$instituteId = array_unique($instituteId);
		$this->dbHandle->where('entityId in '. "(".implode(",", $instituteId).")");
		$this->dbHandle->where('articleAttributeMapping.status', 'live');
		$this->dbHandle->where("entityType in ('group','college','university')");//no 'institute' in enum
		$this->dbHandle->where('blogTable.status', 'live');
		$this->dbHandle->where("blogType not in ('exam','examstudyabroad')");

		$this->dbHandle->distinct();
		$this->dbHandle->select('entityId');
		$this->dbHandle->from('articleAttributeMapping');
		$this->dbHandle->join('blogTable','articleAttributeMapping.articleId=blogTable.blogId');
		$query = $this->dbHandle->get();
		$result = $query->result_array();
		
		$content = array();
		foreach ($result as $row) {
			$content[] = $row['entityId'];
		}
		return $content;
	}

	public function getArticleCommentCount($articleId){

		if(!is_array($articleId)&& $articleId != '' && $articleId > 0){
			$articleId = array($articleId);
		}
		$articleId = array_filter($articleId);
		if(count($articleId) <= 0 || !is_array($articleId) ){
			return array();
		}
		
		$content = array();

		$sql = "SELECT A.`blogId`, B.msgCount AS commentCount FROM `blogTable` A join messageTable B on A.discussionTopic = B.msgId where B.status IN ('live', 'closed') AND A.status = 'live' AND A.blogType NOT IN ('exam', 'examstudyabroad') and A.blogId in (?)";

		$result = $this->dbHandle->query($sql, array($articleId));
		$result = $result->result_array();
		foreach ($result as $row) {
			$content[$row['blogId']] = $row['commentCount'];
		}
		return $content;
	}

	public function getArticleInstituteCount($articleId){

		if(!is_array($articleId)&& $articleId != '' && $articleId > 0){
			$articleId = array($articleId);
		}
		$articleId = array_filter($articleId);
		if(count($articleId) <= 0 || !is_array($articleId) ){
			return array();
		}
		$this->dbHandle->where('articleId in '. "(".implode(",", $articleId).")");
		$this->dbHandle->where('status', 'live');
		$this->dbHandle->where("entityType in ('group', 'college', 'university')");//no 'institute' in enum
		$this->dbHandle->group_by('articleId');
		
		$this->dbHandle->select('articleId, count(*) as InstituteCount');
		$query = $this->dbHandle->get('articleAttributeMapping');
		$result = $query->result_array();
		
		$content = array();
		foreach ($result as $row) {
			$content[$row['articleId']] = $row['InstituteCount'];
		}
		return $content;
	}
public function getInstituteArticleIds($instituteIds){

		if(count($instituteIds) <= 0 || !is_array($instituteIds)){
            return array();
        }
		
       	$instituteIds = array_unique($instituteIds);
		$this->dbHandle->where('articleAttributeMapping.entityId in '. "(".implode(",", $instituteIds).")");
		$this->dbHandle->where('articleAttributeMapping.status', 'live');
		$this->dbHandle->where("articleAttributeMapping.entityType in ('group','college','university')");
		$this->dbHandle->where('blogTable.status', 'live');
		$this->dbHandle->where("blogTable.blogType not in ('exam','examstudyabroad')");

		
		$this->dbHandle->distinct();
		$this->dbHandle->select('articleAttributeMapping.entityId, blogTable.blogId');
		$this->dbHandle->from('articleAttributeMapping');
		
		$this->dbHandle->join('blogTable','articleAttributeMapping.articleId=blogTable.blogId');
		$query = $this->dbHandle->get();
		$result = $query->result_array();
		
		$content = array();
		foreach ($result as $row) {
			$content[$row['entityId']][] = $row['blogId'];
		}
		return $content;
	}
}
?>

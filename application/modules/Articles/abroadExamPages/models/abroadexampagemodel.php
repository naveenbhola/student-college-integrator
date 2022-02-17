<?php
/*
 * Model for study abroad exam pages
 *
 */
class abroadexampagemodel extends MY_Model{
	private $dbHandle = '';
	private $dbHandleMode = '';
	
	function __construct(){
		parent::__construct('SAContent');
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
	
	/*
	* get data for abroad exam pages
	*/
	public function getAbroadExamPageData($contentId, $section){
		echo "Exam pages are retired.";return false;
		/*
		if(empty($contentId)){
		return false;
		}
		//get read DB handle
		$this->initiateModel('read');
		
		// 1. get basic details of the exam page
		$this->dbHandle->select('sac.content_id as examPageId, sac.title as examPageTitle ,sac.exam_desc as examPageDescription,sac.summary as examPageSummary,alemt.exam as examName,sac.exam_type as examId,sac.contentURL as examPageURL,sac.relatedDate as relatedDate,saci.imageUrl as examPageImageUrl, sac.seo_title as seoTitle, sac.seo_description as seoDescription, sac.seo_keywords as seoKeywords, sac.download_link, sac.is_downloadable, sac.created_by');
		$this->dbHandle->from('study_abroad_content sac');
		$this->dbHandle->join('abroadListingsExamsMasterTable alemt', 'alemt.examId = sac.exam_type and alemt.status = alemt.status','inner');
		$this->dbHandle->join('study_abroad_contentImages saci', 'saci.saContentId = sac.content_id and saci.status = sac.status','inner');
		$this->dbHandle->where('sac.status','live');
		$this->dbHandle->where_in('sac.type',array('examPage'));
		$this->dbHandle->where('sac.content_id',$contentId,FALSE);
		$query_res = $this->dbHandle->get()->result_array();
		//echo "SQL1".$this->dbHandle->last_query();
		if(count($query_res) >= 1)
		{
			$examPageDetails =array();
			$examPageDetails[$contentId] = $query_res[0];
		}
		else{
			return false;
		}
		
		// 2. get sections of exam page
		$examPageSections = $this->getAbroadExamPageSectionData($contentId, $section);
		// combine all data
		$examPageData = $examPageDetails;
		$examPageData[$contentId]['sections'] = $examPageSections;
		return $examPageData;*/
	}
	/*
	 * function to get sections of exam pages along with their index
	 */
	private function getAbroadExamPageSectionData($contentId, $sectionId)
	{
		echo "Exam pages are retired.";return false;
		/*
		$this->dbHandle->select('sacs.indexes,sacs.heading ,sacs.details');
		$this->dbHandle->from('study_abroad_content_sections sacs');
		$this->dbHandle->where('sacs.status','live');
		$this->dbHandle->where('floor(sacs.indexes)=',$sectionId);
		$this->dbHandle->where('sacs.content_id',$contentId,FALSE);
		$query_res = $this->dbHandle->get()->result_array();
		//_p($query_res);
		//echo "SQL2".$this->dbHandle->last_query();
		$sectionArray = array();
		foreach($query_res as $section)
		{
			$sectionArray[$section['indexes']] = $section;
		}
		if(count($sectionArray) >= 1)
		{
			return $sectionArray;
		}
		else{
			return false;
		}
		*/
	}
	
	public function getExamPageDownloadCount($examPageId){
		//get read DB handle
		$this->initiateModel('read');		
		$this->dbHandle->select("count(1)");
		$this->dbHandle->from("sa_guide_download_tracking");
		$this->dbHandle->where("guide_id",$examPageId);
		return intval(reset(reset($this->dbHandle->get()->result_array())));
	}
	/*
	 * function to deleteReplies
	 */
	public function deleteReplies($commentId)
	{
		//get write DB handle
		$this->initiateModel('write');
		// delete the replies
		if($commentId>0){
			$data = array('status' => 'deleted');
			$this->dbHandle->where("parent_id",$commentId);
			$this->dbHandle->where("status",'live');
			$this->dbHandle->update('sa_comment_details',$data);
		}
	}
	/*
	 * function to track comment deletion
	 */
	public function trackCommentDeletion($data)
	{
		//get write DB handle
		$this->initiateModel('write');
		$tableData = array();
		$commentTrackData = array(
					'commentId'=> $data['commentId'],
					'userId'   => $data['userId'   ],
					'userName' => $data['userName' ],
					'userEmail'=> $data['userEmail'],
					'deletedAt'=> date('Y-m-d H:i:s')
					);
		array_push($tableData,$commentTrackData);
		if(count($data['replies'])>0){
			foreach($data['replies'] as $replyId)
			{
				$replyTrackData = array(
						'commentId'=> $replyId['id'],
						'userId'   => $data['userId'   ],
						'userName' => $data['userName' ],
						'userEmail'=> $data['userEmail'],
						'deletedAt'=> date('Y-m-d H:i:s')
						);
				array_push($tableData,$replyTrackData);
			}
		}
		$this->dbHandle->insert_batch('study_abroad_comments_deletion_tracking', $tableData);
		return;
	}

	/*This is a function to fetch data for more than one exampages at the same time 
	* params: ExamPageIds array
	*/
	public function prepareCatPageExamWidgetContent($examPageIds)
	{
		echo "Exam pages are retired.";return false;
		/*
		 $examPageIds = array_filter($examPageIds);
		 if(empty($examPageIds)== true)
		 {
		 	return false;
		 }
		//get read DB handle
		$this->initiateModel('read');
		
		//$contentIds = implode(',',$examPageIds);
		// 1. get basic details of the exam page
		$this->dbHandle->select('sac.content_id as examPageId,sac.exam_desc as examPageDescription,alemt.exam as examName,sac.contentURL as examPageURL,sac.download_link, sac.is_downloadable ,sac.contentImageURL as imageUrl');
		$this->dbHandle->from('study_abroad_content sac');
		$this->dbHandle->join('abroadListingsExamsMasterTable alemt', 'alemt.examId = sac.exam_type and alemt.status = alemt.status','inner');
		$this->dbHandle->where('sac.status','live');
		$this->dbHandle->where('sac.type','examPage');
		$this->dbHandle->where_in('sac.content_id',$examPageIds,FALSE);
		$this->dbHandle->group_by('examPageId'); 

		$query_res = $this->dbHandle->get()->result_array();
		//echo "SQL1".$this->dbHandle->last_query();
		//_p($query_res);
		//die;
		if(count($query_res) >= 1)
		{
			return $query_res;
		}
		else{
			return false;
		}
		*/
	}

	public function getContentNavigationLinks($contentTypeId,$contentType){
		if(empty($contentTypeId)){
			return false;
		}
		$this->initiateModel('read');
		$this->dbHandle->select('content_type_title, links_data,added_by,added_at');
		$this->dbHandle->from('sa_exam_apply_navbar_links');
		$this->dbHandle->where('content_type', $contentType);
		$this->dbHandle->where('status', 'live');
		$this->dbHandle->where('content_type_id', $contentTypeId);
		$result = $this->dbHandle->get()->result_array();
		if(!empty($result)){
			$result = reset($result);
			$result['links_data'] = json_decode($result['links_data'], true);
		}
		return $result;
	}

	public function getAllContentURLs($allContentIds){
		if(empty($allContentIds)){
			return false;
		}
		$this->initiateModel('read');
		$this->dbHandle->select('content_id, content_url as contentURL');
		$this->dbHandle->from('sa_content');
		$this->dbHandle->where('status', 'live');
		$this->dbHandle->where_in('content_id', $allContentIds);
		$result = $this->dbHandle->get()->result_array();
		$formattedData = array();
		foreach ($result as $value) {
			$formattedData[$value['content_id']] = $value['contentURL'];
		}
		return $formattedData;
	}
}
?>

<?php

class SolrDeleteDocument {
	
	private $_ci;
	public function __construct(){
		$this->_ci = & get_instance();
	}
	
	private function getDeleteQueryById($type, $id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>unique_id:".$type."_".$id."</query>";
		$deleteXML .= "</delete>";
		return $deleteXML;
	}
	
	public function getQuestionDeleteXML($id){
		$xml = $this->getDeleteQueryById('question', $id);
		return $xml;
	}
	
	public function getDiscussionDeleteXML($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>discussion_thread_id:".$id." AND facetype:discussion</query>";
		$deleteXML .= "</delete>";
		//$xml = $this->getDeleteQueryById('discussion', $id);
		return $deleteXML;
	}
	
	public function getArticleDeleteXML($id){
		$xml = $this->getDeleteQueryById('article', $id);
		return $xml;
	}
	
	public function getCareerDeleteXML($id){
		$xml = $this->getDeleteQueryById('career', $id);
		return $xml;
	}
	
	public function getAutosuggestorDeleteXML($id, $type = 'institute'){
		$deleteXML = "";
		$deleteXML .= "<delete>";
		if($type == "institute"){
			$deleteXML .= "<query>institute_id:".$id." AND facetype:autosuggestor</query>";
			$deleteXML .= "<query>institute_id:".$id." AND facetype:autosuggestorv2</query>";
		} else if($type == "course"){
			$deleteXML .= "<query>course_id:".$id." AND facetype:autosuggestor</query>";
			$deleteXML .= "<query>course_id:".$id." AND facetype:autosuggestorv2</query>";
		}
		$deleteXML .= "</delete>";
		return $deleteXML;
	}
	
	public function getInstituteAndAutosuggestorDeleteXML($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>institute_id:".$id." AND facetype:course</query>";
			$deleteXML .= "<query>institute_id:".$id." AND facetype:autosuggestor</query>";
			$deleteXML .= "<query>institute_id:".$id." AND facetype:autosuggestorv2</query>";	
		$deleteXML .= "</delete>";
		return $deleteXML;
	}
	
	public function getCourseAndAutosuggestorDeleteXML($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>course_id:".$id." AND facetype:course</query>";
			$deleteXML .= "<query>course_id:".$id." AND facetype:autosuggestor</query>";
			$deleteXML .= "<query>course_id:".$id." AND facetype:autosuggestorv2</query>";	
		$deleteXML .= "</delete>";
		return $deleteXML;
	}
	
	public function getCourseDeleteXML($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>course_id:".$id." AND facetype:course</query>";
		$deleteXML .= "</delete>";
		return $deleteXML;
	}
	
	public function getInstituteDeleteXML($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>institute_id:".$id." AND facetype:course</query>";
		$deleteXML .= "</delete>";
		return $deleteXML;
	}
        
        public function getUniversityDeleteXML($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>university_id:".$id." AND facetype:university</query>";
		$deleteXML .= "</delete>";
		return $deleteXML;
	}
        
        public function getAbroadInstituteDeleteXML($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>sa_institute_id:".$id." AND facetype:abroadinstitute</query>";
		$deleteXML .= "</delete>";
		return $deleteXML;
	}
        
        public function getAbroadCourseDeleteXML($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>sa_course_id:".$id." AND facetype:abroadcourse </query>";
		$deleteXML .= "</delete>";
		return $deleteXML;
	}

	public function getTagDeleteXML($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>tag_id:".$id." AND facetype:tag </query>";
		$deleteXML .= "</delete>";
		return $deleteXML;
	}

	public function getNLAutoSuggestorDeleteXML($type,$id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
		if(empty($id)){
			$deleteXML .= "<query>nl_entity_type:".$type." AND facetype:autosuggestor</query>";
		}
		else{
			$deleteXML .= "<query>nl_entity_type:".$type." AND nl_entity_id:".$id." AND facetype:autosuggestor</query>";
		}
		$deleteXML .= "</delete>";
		return $deleteXML;
	}

	public function getUgcDeleteXML($type,$id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
		if(empty($id)){
			$deleteXML .= "<query>nl_entity_type:".$type." AND facetype:ugc</query>";
		}
		else{
			$deleteXML .= "<query>nl_entity_type:".$type." AND nl_entity_id:".$id." AND facetype:ugc</query>";
		}
		$deleteXML .= "</delete>";
		return $deleteXML;
	}

	public function getNLCourseDeleteXML($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>nl_course_id:".$id." AND facetype:course</query>";
		$deleteXML .= "</delete>";
		return $deleteXML;
	}
	
	public function getNLInstituteDeleteXML($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>nl_institute_id:".$id." AND facetype:course</query>";
		$deleteXML .= "</delete>";
		return $deleteXML;
	}

	public function getCollegeReviewDeleteXML($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>reviewId:".$id."</query>";
		$deleteXML .= "</delete>";
		return $deleteXML;
	}
	
	//private function getAllDataDeleteXML(){
	//	$deleteXML = "";
	//	$deleteXML .= "<delete>";
	//		$deleteXML .= "<query>*:*</query>";
	//	$deleteXML .= "</delete>";
	//	return $deleteXML;
	//}

	public function getCollegeShortlistDeleteXML($id) {

		$deleteXML = "";
		$deleteXML .= "<delete>";
		if($id == "all"){
			$deleteXML .= "<query>*:*</query>";
		}
		else{
			$deleteXML .= "<query>examId:".$id."</query>";
		}
		$deleteXML .= "</delete>";
		return $deleteXML;	
	}
	
}	

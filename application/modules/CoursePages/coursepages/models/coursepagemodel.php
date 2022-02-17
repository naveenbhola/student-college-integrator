<?php
class coursePageModel extends MY_Model
{
	function __construct()
	{
		parent::__construct('coursePages');
		$this->db = $this->getReadHandle();
		$this->masterdb = $this->getWriteHandle();
	}

	function getWidgetListForCoursePage($courseHomePageId,$status_array = array()) {
		if(count($status_array)>0) {
			$sql = "select cpm.*,cpw.widgetKey from course_pages_category_widgets_mapping cpm,course_pages_widgets cpw ".
	       "WHERE cpm.status IN (".implode(",", $status_array).") AND cpw.status IN (".implode(",", $status_array).") AND cpm.widgetId=cpw.widgetId AND courseHomePageId = ?";
		} else {
			$sql = "select cpm.*,cpw.widgetKey from course_pages_category_widgets_mapping cpm,course_pages_widgets cpw ".
	       "WHERE cpm.status = 'live' AND cpw.status='live' AND cpm.widgetId=cpw.widgetId AND courseHomePageId = ?";	
		}
		$results = $this->db->query($sql, $courseHomePageId)->result_array();
		return $results;
	}

	function getSubcategoriesWhereFeaturedInstituteIsSet() {

		$sql = "SELECT subcatId ".
				"FROM course_pages_featured_institutes ".
		        "WHERE status ='live' AND addedBy='CMS'";

		$results = array();

		foreach ($this->db->query($sql)->result() as $row) {
			$results[] = $row->subcatId;
		}

		if(count($results) > 0) {
			$results = array_unique($results);
		}

		return $results;
	}

	function addFeaturedInstitutes($institutes_categorywise) {

		if(count($institutes_categorywise) == 0 ) {
			return;
		}

		$category_ids = array_keys($institutes_categorywise);

		$this->load->model('coursepages/coursepagecmsmodel');

		// check whether CRON has alreay added featured institute for the categories
		$already_added_featured_instutes = $this->coursepagecmsmodel->getSLideDetailsAddedByCron($category_ids);

		foreach ($institutes_categorywise as $cat_id=>$value) {

			if(in_array($cat_id, array_keys($already_added_featured_instutes))) {
				$update_array = array(
					'landingUrl'=>$institutes_categorywise[$cat_id]['institute_url'],
				    'imageUrl'=>$institutes_categorywise[$cat_id]['image_path'],
				    'imageTitle'=>$institutes_categorywise[$cat_id]['institute_name'],
				    'id'=>$already_added_featured_instutes[$cat_id]['id'],
				    'addedBy'=>'CRON',
				    'open_new_tab'=>'YES' 
				    );
				    $this->coursepagecmsmodel->updateSlide($update_array);
			} else {
				$this->coursepagecmsmodel->addSlide($value['institute_name'],$value['institute_url'],$value['image_path'],$cat_id,'YES','CRON');
			}

		}
	}

	function getFaqQuestionsOnHomePageByCourseHomePageId($courseHomePageId = "") {

		if(empty($courseHomePageId)) {
			return array();
		}

		$sql = "SELECT faq.id,faq.questionText ".
				"FROM course_pages_faqs faq, course_pages_faq_headings faqh ".
		        "WHERE faq.status='live' AND faqh.status='live' ".
		        "AND faq.groupHeadingId = faqh.id AND faqh.courseHomePageId = ? ".
		        "ORDER BY faq.no_of_clicks DESC, faq.position ASC LIMIT 4";
			
		return $this->db->query($sql, $courseHomePageId)->result_array();
	}

	function getFaqQuestionsListSortedBySectionAndQuestionOrder($courseHomePageId = '') {
                
		if(empty($courseHomePageId)) {
			return array();
		}

		$sql = "SELECT faq.id as question_id,faqh.id as section_id,faqh.position as section_position, ".
			   "faq.position as question_position,faqh.groupHeading, ".
		        "faq.answerText,faq.questionText,faq.no_of_clicks ".
				"FROM course_pages_faqs faq, course_pages_faq_headings faqh ".
		        "WHERE faq.status='live' AND faqh.status='live' ".
		        "AND faq.groupHeadingId = faqh.id AND faqh.courseHomePageId = ? ".
		        "ORDER BY faqh.position ASC, faq.position ASC";

		$response_array = $this->db->query($sql, $courseHomePageId)->result_array();
         
		$response = array();

		foreach ($response_array as $row) {
			$response[$row['section_id']][$row['question_id']] = $row;
		}

		return $response;

	}

    function getDeletedFaqQuestionsDetails($questions_list = array()) {

		if(count($questions_list) == 0) {
			return array();
		}

		$sql = "SELECT faq.id as question_id,faqh.id as section_id,faqh.position, ".
				"faq.status as question_status,faqh.status as section_status,faqh.courseHomePageId ".
				"FROM course_pages_faqs faq, course_pages_faq_headings faqh ".		        
		        "WHERE faq.groupHeadingId = faqh.id AND faq.id IN (?) ";

		$response_array = $this->db->query($sql,array($questions_list))->result_array();
	
		return $response_array;
	}

	/*function insertCourseHomePageWidgetMapping($subData){
		$widgetArray = array(
							3 	=> 	array('widgetHeading'=>'Recent Questions on '.$subData['shortName'],'columnPosition'=>1,'displayorder'=>1),
							5 	=> 	array('widgetHeading'=>'Recent Articles on '.$subData['shortName'],'columnPosition'=>2,'displayorder'=>1),
							6 	=> 	array('widgetHeading'=>'Recent Discussions on '.$subData['shortName'],'columnPosition'=>1,'displayorder'=>2),
							1 	=> 	array('widgetHeading'=>'Featured Colleges for '.$subData['shortName'],'columnPosition'=>3,'displayorder'=>1),
							2 	=> 	array('widgetHeading'=>'Have a Question? Ask Our experts','columnPosition'=>4,'displayorder'=>1)
							);


		foreach ($widgetArray as $key => $value) {
			$sql = "SELECT * FROM course_pages_category_widgets_mapping WHERE subCatId =".$subData['boardId']." and widgetId = ".$key." and status = 'live'";

			if($numCount == 0){
				$insertSql = "INSERT INTO course_pages_category_widgets_mapping (widgetId,subCatId,widgetHeading,columnPosition,displayorder,STATUS) VALUES(".$key.",".$subData['boardId'].",'".$value['widgetHeading']."',".$value['columnPosition'].",".$value['displayorder'].",'live')";
			}
		}
	}*/

	function getCourseHomePageUrlByParams($courseHomePageData){
		$validParams = array('stream_id','substream_id','base_course_id','education_type','delivery_method');
		foreach($courseHomePageData as $key => $value){
			if(!in_array($key, $validParams)){
				unset($courseHomePageData[$key]);
			}
		}
		foreach($validParams as $param){
			if(empty($courseHomePageData[$param])){
				$courseHomePageData[$param] = 0;
			}
		}
		$this->db->select('seo_url');
		$this->db->from('courseHomePages');
		foreach($courseHomePageData as $key=>$value){
			$this->db->where($key,$value);
		}
		$this->db->where('status','live');
		$res = $this->db->get()->result_array();
		if(count($res) != 1){
			return '';
		}
		return SHIKSHA_HOME.reset(reset($res));
	}
	
	function getCourseHomePageTable(){
		$sql = "select * from courseHomePages where status = 'live'";
		return $this->db->query($sql)->result_array();
	}
}

<?php

/**
 * Feature Guide Model
 */

/**
 * Feature Guide Model
 */
class featuredguidemodel extends MY_Model
{
    /**
     * @var Object DB Handle
     */
    private $dbHandle;
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct('User');
    }
    
    /**
     * Initiate the model
     *
     * @param string $operation
     */
    private function initiateModel($operation = 'read')
    {
	if($operation=='read'){
            $this->dbHandle = $this->getReadHandle();
	} else {
            $this->dbHandle = $this->getWriteHandle();
	}
    }
    
    /**
     * Function to insert data in featured_guide table
     * @param : integer category_id
     * @param : integer Course_id
     * @param : String Url
     * @param : string attachment Name
     */
    function insertInFeaturedGuide($category_id,$course_id,$pdf_url,$attachment_name){
        //$this->initiateModel('write','User');
        $course_type = 'national';
        if($category_id == '14'){
            $course_type = 'testprep';
        }

        $data = array(
            'stream_id' => $category_id,
            'base_course_id' => $course_id,
            'course_type' => $course_type,
            'guide_url' => $pdf_url,
            'status' => 'live',
            'attachment_name' => $attachment_name
        );
        
		$checkIfExists = $this->alreadyExistingGuide($course_id,$attachment_name,$category_id);
		if($checkIfExists == 1){
			return 1;
		}
		
		$this->initiateModel('write','User');
		$this->dbHandle->insert('featured_guide',$data);
		return 0;
		
		}
    
    /**
     * Function to get already existing guides for a course
     * @param integer $courseId
     */
    function getExistingGuides($courseId,$stream_id){
	
	if(empty($courseId) || empty($stream_id)) {
		return;
	}	
	
	$this->initiateModel('read','User');		
	//_P($courseId."___".$stream_id);
	$queryForFeaturedGuide = "SELECT id,stream_id,base_course_id, attachment_name, guide_url FROM  featured_guide WHERE base_course_id = ? AND stream_id=? AND status = ?";
	$queryResultFromFeaturedGuide = $this->dbHandle->query($queryForFeaturedGuide, array($courseId,$stream_id,'live'));
	$result  = $queryResultFromFeaturedGuide->result_array();
	return $result;
	
    }
    
    /**
     * Function to check if name-course pair exists already
     * @param integer course Id
     * @param string name
     */
    function alreadyExistingGuide($base_course_id,$attachment_name,$stream_id){
	$this->initiateModel('read','User');
	
	$queryForAlreadyExistingGuide = "SELECT * FROM  featured_guide WHERE base_course_id = ? AND stream_id=? AND attachment_name = ? AND status = 'live'";
	$queryResultFromAlreadyExistingGuide = $this->dbHandle->query($queryForAlreadyExistingGuide, array($base_course_id,$stream_id,$attachment_name));
		if($queryResultFromAlreadyExistingGuide->num_rows()){
			return 1;
		} else {
			return 0;
		}
    }
    
    /**
     * Function to delete a guide
     * @param integer courseId
     * @param String attachment_name
     */
    function deleteFromFeaturedGuide($id,$attachment_name){
	$this->initiateModel('write','User');
	$queryForDelete = "UPDATE `featured_guide` SET `status` = 'deleted' ".
			"WHERE `featured_guide`.`id` = ?";
	$queryResultForDelete = $this->dbHandle->query($queryForDelete, array($id,$attachment_name));
	
	if($queryResultForDelete){
	    return 1;
	}
	return 0;
    }
    
    /**
     * Function to get the attachment for mailer
     * @param integer course Id
     */
    function getGuideForAttachment($stream_id,$base_courses_ids_array){
			
	if($stream_id>0 && count($base_courses_ids_array)>0) {
		
		$final_base_course = array();
		foreach($base_courses_ids_array as $val) {
			if($val>0) {
				$final_base_course[] = $val;
			}	
		}
				
		if(count($final_base_course) >0) {
			$this->initiateModel('read','User');
			$sql =  "SELECT * ".
				"FROM `featured_guide`  ".
				"WHERE base_course_id in (?) AND stream_id=? AND status = 'live'";
				$query = $this->dbHandle->query($sql,array($final_base_course, $stream_id));			
				return $query->result_array();
		}
									
		}
		
    }
    
}

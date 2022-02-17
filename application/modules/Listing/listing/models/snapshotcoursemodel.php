<?php

class SnapshotCourseModel extends MY_Model
{
    function __construct() {
		parent::__construct('Listing');
    }
    
    public function getCourse($courseId){
        if(!is_numeric($courseId) || $courseId<0){
            return array();
        }
        $this->db->select("*");
        $this->db->where("status","live");
        $this->db->where("course_id",$courseId);
        return reset($this->db->get("snapshot_courses")->result_array());
    }
    
    public function getSnapshotCoursesOfUniversity($univId){
        if(!is_numeric($univId) || $univId < 0){
            return array();
        }
        $this->db->select("course_id");
        $this->db->where("status","live");
        $this->db->where("university_id",$univId);
        $result = $this->db->get("snapshot_courses")->result_array();
        $idArray=array();
        foreach($result as $key=>$id){
            $idArray[] = reset($id);
        }
        return $idArray;
    }
    
    public function getMultipleCourses($courseIds){
		if(is_array($courseIds)){
			$courseIds =  array_filter($courseIds,"is_numeric");
		}
        if(!is_array($courseIds) || empty($courseIds)){
            return array();
        }
        $this->db->select("*");
        $this->db->where("status","live");
        $this->db->where_in("course_id",$courseIds);
        return $this->db->get("snapshot_courses")->result_array();
    }
	
	public function getSnapshotCoursesByUniversities($universityIdArray){
		if(!is_array($universityIdArray) || empty($universityIdArray)){
			return array();
		}
		$universityIdArray = array_filter($universityIdArray,"is_numeric");
		if(count($universityIdArray) == 0){
			return array();
		}
		$this->db->select("*");
		$this->db->where("status","live");
		$this->db->where_in("university_id",$universityIdArray);
		return $this->db->get("snapshot_courses")->result_array();
	}
	
	public function getLiveSnapshotCourses(){
		$this->db->select("course_id");
		$this->db->where("status",'live');
		$res = $this->db->get("snapshot_courses")->result_array();
		$result = array();
		foreach($res as $row){
			$result[$row["course_id"]] = $row['course_id'];
		}
		return $result;
	}
    
}

?>
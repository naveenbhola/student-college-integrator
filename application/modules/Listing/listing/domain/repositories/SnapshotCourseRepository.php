<?php

class SnapshotCourseRepository extends EntityRepository{
    
    function __construct($dao,$cache){
		parent::__construct($dao,$cache);
        $this->CI->load->entities(array('SnapshotCourse'),'listing');
	}

    public function find($snapshotCourseId){
        if(!is_numeric($snapshotCourseId)|| $snapshotCourseId<0){
            return false;
        }
        
        // if($this->caching && $cachedCourse = $this->cache->getSnapshotCourse($snapshotCourseId)){
        //     return $cachedCourse;
        // }
        
        $data = $this->dao->getCourse($snapshotCourseId);
        if(empty($data)){
            return false;
        }
        $obj = $this->_createEntity($data);
        $this->cache->storeSnapshotCourse($obj);
        return $obj;
    }
    
    public function findMultiple($snapshotCourseIds,$categoryPageFlag){
        if(!is_array($snapshotCourseIds) || empty($snapshotCourseIds)){
            return array();
        }
        $finalArray = array();
        if($this->caching) {
			$coursesFromCache = $this->cache->getMultipleSnapshotCourses($snapshotCourseIds,$categoryPageFlag);
			$foundInCache = array_keys($coursesFromCache);
			$snapshotCourseIds = array_diff($snapshotCourseIds,$foundInCache);
            $finalArray = array_merge($finalArray,$coursesFromCache);
		}
        $coursesData = $this->dao->getMultipleCourses($snapshotCourseIds);
        foreach($coursesData as $courseData){
            $snapObject = new SnapshotCourse;
            $this->fillObjectWithData($snapObject,$courseData);
            $this->cache->storeSnapshotCourse($snapObject);
            $finalArray[$snapObject->getId()] = $snapObject;
        }
        
        /*foreach($snapshotCourseIds as $snapshotCourseId){
            $obj = $this->find($snapshotCourseId);
            if($obj !== false){
                $finalArray[$snapshotCourseId] = $obj;
            }
        }*/
        return $finalArray;
    }
    
    public function findByUniversityId($universityId){
        $courseIds = $this->dao->getSnapshotCoursesOfUniversity($universityId);
        return $this->findMultiple($courseIds);
    }
	
	public function findByUniversities($universityIdArray){
		if(!is_array($universityIdArray)) return array();
		$result = $this->dao->getSnapshotCoursesByUniversities($universityIdArray);
		$courseIdArray = $this->_getCourseIds($result);
		$coursesArray = $this->findMultiple($courseIdArray);
		$finalResult = $this->_associateUniversitiesAndCourses($coursesArray);
		return $finalResult;
	}
	
	private function _getCourseIds($sqlResult){
		$result = array();
		foreach($sqlResult as $row){
			$result[] = $row["course_id"];
		}
		return $result;
	}
	
	private function _associateUniversitiesAndCourses($coursesArray){
		$result = array();
		foreach($coursesArray as $snapshotCourse){
			$result[$snapshotCourse->getUniversityId()][] = $snapshotCourse;
		}
		return $result;
	}
    
    public function delete($snapshotCourseId){
		$this->cache->deleteSnapshotCourse($snapshotCourseId);
    }
    
    public function refreshCache($snapshotCourseId){
		$this->delete($snapshotCourseId);
		$this->find($snapshotCourseId);
    }
    
    private function _createEntity($data){
        $snapObject = new SnapshotCourse;
        $this->fillObjectWithData($snapObject,$data);
        return $snapObject;
    }
	
	public function getLiveSnapshotCourses(){
		return $this->dao->getLiveSnapshotCourses();
	}
    
}

?>
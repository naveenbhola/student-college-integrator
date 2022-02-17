<?php
class homepagemodel extends MY_Model {
	function __construct(){
		parent::__construct();
	}

	private function initiateModel($mode = 'read'){
        if($mode == 'write') {
            return $this->getWriteHandle();
        } else {
            return $this->getReadHandle();
        }
    }

    function getUserPrefData($userId){
    	$dbHandle = $this->initiateModel();
    	$sql ="SELECT sm.ldbCourseID, cbt.parentId as categoryId, cbt.boardId as subCatId from tUserPref tu join LDBCoursesToSubcategoryMapping sm on tu.DesiredCourse = sm.ldbCourseID join categoryBoardTable cbt on sm.categoryID = cbt.boardId where tu.UserId = ? limit 1";
    	$queryRes = $dbHandle->query($sql, array($userId));
    	return $queryRes->result_array();
    }

    function insertPBT($data){
            $dbHandle = $this->initiateModel('write');
            $dbHandle->insert_batch('OF_PBTSeoDetails', $data); 
            return $dbHandle->insert_id();
    }

    function getNoneZeroBaseCourseByStream($streamId, $baseCourseArr){
        $baseCourse = implode(',', $baseCourseArr);
        if(empty($baseCourse) || $streamId ==''){
            return array();
        }
        $dbHandle = $this->initiateModel('read');
        $sql = "SELECT distinct base_course_id FROM category_page_seo WHERE stream_id = ? AND base_course_id IN (?) 
                AND status = 'live' AND result_count >0";
        $queryRes = $dbHandle->query($sql, array($streamId, $baseCourseArr));
        $result   = $queryRes->result_array();
        foreach ($result as $key => $value) {
            $baseCourseAr[] = $value['base_course_id'];
        }
        return (count($baseCourseAr)>0) ? $baseCourseAr : array();
    }

    function getNoneZeroStream_Substream_SpecializationByStream($streamId,$substreamid,$specialization){
        $specStr = implode(',', $specialization);
        if(empty($specStr) || $streamId =='' || $substreamid ==''){
            return array();
        }
        $dbHandle = $this->initiateModel('read');
        $sql = "SELECT distinct specialization_id FROM category_page_seo WHERE stream_id = ? AND substream_id = ? AND specialization_id IN (?) AND status = 'live' AND result_count >0";
        $queryRes = $dbHandle->query($sql, array($streamId,$substreamid,$specialization));
        $result   = $queryRes->result_array();
        foreach ($result as $key => $value) {
            $specData[$value['specialization_id']] = $value['specialization_id'];        
        }
        return (count($specData)>0) ? $specData : array();
    }

    function getNoneZeroSpecializationByStream($streamId,$spec){
        $specStr = implode(',', $spec);
        if(empty($specStr) || $streamId =='' ){
            return array();
        }
        $dbHandle = $this->initiateModel('read');
        $sql = "SELECT distinct specialization_id FROM category_page_seo WHERE stream_id = ? AND specialization_id IN (?) 
                AND status = 'live' AND result_count >0";
        $queryRes = $dbHandle->query($sql, array($streamId,$spec));
        $result   = $queryRes->result_array();
        foreach ($result as $key => $value) {
            $specData[$value['specialization_id']] = $value['specialization_id'];
        }
        return (count($specData)>0) ? $specData : array();
    }

    function insertUserSessionInfo($sessionId, $userId, $clientIP){
        $dbHandle = $this->initiateModel('write');
        $queryCmd = "SELECT * FROM user_session_info WHERE session_id = ?";
        $query = $dbHandle->query($queryCmd,array($sessionId));

        if($query->num_rows() == 0) {
            $queryCmd = "INSERT INTO user_session_info SET session_id = ?,user_id = ?,user_ip = ?";
            $query = $dbHandle->query($queryCmd,array($sessionId,$userId,$clientIP));
        }
    }


}

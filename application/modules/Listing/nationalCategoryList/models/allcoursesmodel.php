<?php

class allcoursesmodel extends MY_Model {

    private function initiateModel($mode = "write") {
        if($this->dbHandle && $this->dbHandleMode == 'write') {
            return;
        }

        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
        } else {
            $this->dbHandle = $this->getWriteHandle();
        }
    }

    public function getPopularCourses($instituteId) {
        if(empty($instituteId)) return;

        $this->initiateModel('read');
        
        $sql = "SELECT entityId ".
                "FROM shiksha_listing_contentSticky ".
                "WHERE status='live' AND listing_id = ? AND entityType = 'course' and type = 'popular' ".
                "AND listing_type IN ('university','institute') AND (expiry_date >= NOW() OR ISNULL(expiry_date)) ".
                "ORDER BY course_order ASC";
        
        $result = $this->dbHandle->query($sql, $instituteId)->result_array();
        
        return $result;
    }
   
}
<?php 

class AllContentModel extends MY_Model{

	private $dbHandle = null;
	private $cache;

    function __construct($cache)
	{
		parent::__construct('AnA');
		$this->cache = $cache;
    }

	private function initiateModel($mode = "write", $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	public function getInstituteUnansweredAnA($instituteIds, $start, $count){
		$this->initiateModel('read');

                if(!is_array($instituteIds) && $instituteIds != '' && $instituteIds > 0){
                        $instituteIds = array($instituteIds);
                }
                $instituteIds = array_filter($instituteIds);
                if(count($instituteIds) <= 0 || !is_array($instituteIds)){
                        return array();
                }

                $start = (int)$start;
                $count = (int)$count;

                $sql = "SELECT SQL_CALC_FOUND_ROWS 
                distinct msgId
                FROM
                tags_content_mapping tcm, tags_entity te, messageTable m 
                WHERE
                te.status = 'live' AND tcm.tag_id = te.tag_id AND m.parentId = 0 AND m.fromOthers='user' AND m.status IN ('live','closed') AND m.msgCount = 0 AND te.entity_type in ('institute','National-University') AND tcm.status = 'live' AND te.entity_id IN (?) AND tcm.content_id = m.msgId AND tcm.content_type = 'question' ORDER BY msgId DESC LIMIT $start, $count";

                $resultSet = $this->dbHandle->query($sql,array($instituteIds))->result_array();
                $finalArray = array();
                foreach ($resultSet as $entry){
                        $finalArray[] = $entry['msgId'];
                }


            $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
            $query = $this->dbHandle->query($queryCmd);
	        $totalRows = 0;
        	foreach ($query->result() as $row) {
	            $totalRows = $row->totalRows;
        	}

                return array('topContent'=>$finalArray,
			     'numFound'=>$totalRows);
	}

	public function getCourseUnansweredAnA($courseIds, $start, $count){
		$this->initiateModel('read');

                if(!is_array($courseIds) && $courseIds != '' && $courseIds > 0){
                        $courseIds = array($courseIds);
                }
                $courseIds = array_filter($courseIds);
                if(count($courseIds) <= 0 || !is_array($courseIds)){
                        return array();
                }

                $start = (int)$start;
                $count = (int)$count;

		$sql = 'SELECT SQL_CALC_FOUND_ROWS distinct messageId FROM questions_listing_response qlr, messageTable m WHERE qlr.messageId=m.msgId AND qlr.status = "live" AND m.status in ("live","closed") AND courseId in (?) AND m.msgCount = 0 AND m.parentId = 0 AND m.fromOthers="user" ORDER BY m.msgId DESC LIMIT '.$start.', '.$count;
		$resultSet = $this->dbHandle->query($sql,array($courseIds))->result_array();
		$finalArray = array();
		foreach ($resultSet as $entry){
			$finalArray[] = $entry['messageId'];
		}

                $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
                $query = $this->dbHandle->query($queryCmd);
                $totalRows = 0;
                foreach ($query->result() as $row) {
                    $totalRows = $row->totalRows;
                }

                return array('topContent'=>$finalArray,
                             'numFound'=>$totalRows);

	}

    public function getAllUniversities(){

        $this->initiateModel('read');
        $sql = "SELECT distinct(listing_id) FROM shiksha_institutes WHERE status='live'";

        $result = $this->dbHandle->query($sql)->result_array();

        return $result;
    }
}

?>

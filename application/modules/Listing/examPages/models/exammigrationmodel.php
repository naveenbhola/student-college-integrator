<?php 
class exammigrationmodel extends MY_Model{
	private $dbHandle = '';
    private $dbHandleMode = '';
	
	function __construct() {
		parent::__construct('Listing');
    }
    
    private function initiateModel($mode = "write"){
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

    public function getOldExamList(){
    	$this->initiateModel('read');
    	$sql = "SELECT acronym, blogId, boardId, parentId from blogTable where parentId in (298,299,464,10055,10113,10114,11924) and boardId in (18,23,33,56,69,75,84) and blogType='exam' and acronym!='' and status in ('live', 'draft') group by acronym";
    	return $this->dbHandle->query($sql)->result_array();
    }

    public function getNewMappings($subCats){
    	$this->initiateModel('read');
    	$sql = "SELECT oldcategory_id, oldsubcategory_id, oldspecializationid, stream_id, substream_id, specialization_id, base_course_id, education_type, delivery_method from base_entity_mapping where oldspecializationid = 0 and oldsubcategory_id in (?)";
    	//18,33,75 not available
    	return $this->dbHandle->query($sql, array($subCats))->result_array();
    }

    public function insertIntoTable($tableName, $rowData){
        $sts = false;
        if(!empty($rowData)){
            $this->initiateModel('write');
            $this->dbHandle->insert($tableName, $rowData);
            $sts = true;
        }
        return $sts;
    }

    public function insertBatchIntoTable($tableName, $rowsData){
    	$this->initiateModel('write');
    	if(!empty($rowsData)){
            $this->dbHandle->insert_batch($tableName, $rowsData);
            return true;
        }
        return false;
    }

    public function getAllExampageIds($examNames){
    	$this->initiateModel('read');
    	$this->dbHandle->select('exampage_id, exam_name');
    	$this->dbHandle->from('exampage_master');
    	$this->dbHandle->where_in('status', array('live', 'draft'));
    	$this->dbHandle->where_in('exam_name', $examNames);
    	$data = $this->dbHandle->get()->result_array();
    	$finalData = array();
    	foreach ($data as $value) {
    		$finalData[$value['exam_name']] = $value['exampage_id'];
    	}
    	return $finalData;
    }

    public function updateExamIdInExamMaster($examId, $exampageId){
    	$this->initiateModel('write');
    	$sql = "update exampage_master set exam_id = ? where exampage_id = ? and status = 'live'";
    	$this->dbHandle->query($sql, array($examId, $exampageId));
    }

    public function getNewExamsWithMapping(){
        $this->initiateModel('read');
        $sql = "SELECT * from exampage_main em join examAttributeMapping eam on em.id=eam.examId where em.status='live' and eam.status='live' and eam.entityType in ('primaryHierarchy', 'hierarchy', 'course')";
        $data = $this->dbHandle->query($sql)->result_array();
        //_p($data);
        return $data;
    }

    public function updateUrlInMasterTable($examId, $examName, $examUrl){
        $this->initiateModel('write');
        $sql = "UPDATE exampage_master set url = ? where exam_id = ? and exam_name = ? and status = 'live'";
        $this->dbHandle->query($sql, array($examUrl, $examId, $examName));
    }

    function getAllExamIds(){
        $this->initiateModel('read');
        $sql =  "SELECT id as examId,name as examName, conductedBy from exampage_main
                 where isRootUrl != 'Yes' AND status = 'live'";
        $data = $this->dbHandle->query($sql)->result_array();
        foreach ($data as $key => $value) {
            $result[$value['examId']] = $value;
        }
        return $result;
    }

    function getExamMappingForURL($examIds){
        $this->initiateModel('read');
        $handle = $this->dbHandle;
        if(empty($examIds) && count($examIds)<=0){
            return;
        }
        $sql = "SELECT grp.examId, attr.entityType, attr.entityId FROM examAttributeMapping attr JOIN exampage_groups grp On grp.groupId = attr.groupId 
                WHERE grp.examId IN (?) 
                AND grp.isPrimary = 1 
                AND attr.entityType IN ('primaryHierarchy','course') 
                AND attr.status = 'live';";
        $res = $handle->query($sql,array($examIds))->result_array();
        foreach ($res as $key => $value) {
            $result[$value['examId']][$value['entityType']][] = $value['entityId'];
        }
        return $result;
    }

    function addExamUrl($data){
        if(empty($data)){
            return array();
        }
        $this->initiateModel('write');
        $handle = $this->dbHandle;        
        $handle->update_batch('exampage_main', $data, 'id');
        //echo $handle->last_query();
    }
}

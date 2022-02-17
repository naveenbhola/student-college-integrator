<?php
class scholarshipcronsmodel extends MY_Model {
    private $dbHandle = '';
    function __construct() {
        parent::__construct('ShikshaApply');
    }

    private function initiateModel($operation='read'){
        if($operation=='read'){ 
            $this->dbHandle = $this->getReadHandle();
        }else{
            $this->dbHandle = $this->getWriteHandle();
        }		
    }
    public function getScholarshipIds($offset,$limit){
        $this->initiateModel('read');
        $this->dbHandle->select('scholarshipId');
        $this->dbHandle->where('status','live');
        if(isset($limit) && isset($offset) && $limit > 0 && $offset >= 0){
            $this->dbHandle->limit($limit, $offset);
        }
        $result = $this->dbHandle->get('scholarshipBaseTable')->result_array();
        $returnArray = array();
        foreach ($result as $row){
            $returnArray[] = $row['scholarshipId'];
        }
        return $returnArray;
    }

    public function getScholarshipAddedDate($scholarshipIds = array()){
        $scholarshipData = array();
        $this->initiateModel('read');
        $this->dbHandle->select('scholarshipId');
        $this->dbHandle->select('addedAt');
        $this->dbHandle->from('scholarshipBaseTable');
        $this->dbHandle->where('status','live');
        // get data for specific scholarships if scholarshipIds are passed
        if(count($scholarshipIds)>0){
            $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        }
        $res = $this->dbHandle->get()->result_array();
        $noOfRows = count($res);
        for($i=0;$i<$noOfRows;$i++){
            $scholarshipData[$res[$i]['scholarshipId']] = $res[$i]['addedAt'];
        }
        return $scholarshipData;
    }
}
?>
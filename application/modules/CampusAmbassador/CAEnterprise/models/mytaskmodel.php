<?php

class MyTaskModel extends MY_Model {
    var $userInfo;
    function __construct() {
        parent::__construct('CareerProduct');
    }
    private function initiateModel($operation='write'){
        if($operation=='read'){
                $this->db = $this->getReadHandle();
        }else{
        $this->db = $this->getWriteHandle();
        }		
    }
    function getAllTasks() {
        $this->initiateModel('read');
        $sql = 'select (select count(*) from CA_TaskSubmission where taskRefId=mt.id) totalSubmittedTasks, mt.id,mt.name,mt.start_date,mt.end_date,mt.status from my_tasks mt order by id desc;';
        $queryTotal  = $this->db->query($sql);
        return $queryTotal->result_array();
    }

    function getTasksByCategory($programId) {
        $whereCond = '';
        if(!empty($programId)){
            $whereCond = "WHERE mt.programId = ?";
        }

        $this->initiateModel('read');
        $sql = "select (select count(*) from CA_TaskSubmission where taskRefId=mt.id) totalSubmittedTasks, mt.id,mt.name,mt.start_date,mt.end_date,mt.status from my_tasks mt ".$whereCond." order by id desc";
        $queryTotal  = $this->db->query($sql,$programId);
        return $queryTotal->result_array();
    }
    
    function getTaskData($id) {
        $this->initiateModel('read');
        if(isset($id)) {
            $sql = "select id,programId,name,description,start_date,end_date,my_tasks.status,allow_submission_type,my_task_prizes.prize_name,my_task_prizes.prize_amount from my_tasks left join my_task_prizes "
                    . "on my_tasks.id = my_task_prizes.my_task_id where my_tasks.id = ? AND my_task_prizes.status != 'history';";
            $data = array();
            $queryTotal  = $this->db->query($sql,array($id));
            $data        = $queryTotal->result_array();
            return $data;
        }
        else
            return false;
    }
    
    function add() {
       
        $this->initiateModel();
        $error = $this->validateUserInputData();
        
        if($error == '') {
            $this->db->trans_start();
	
            $data = array(
                    'name'                  => $this->input->post('name'),
                    'description'           => $this->input->post('description'),
                    'start_date'            => $this->input->post('startDate'),
                    'allow_submission_type' => $this->input->post('allowSubmissionType'),
                    'status'                => 'draft',
                    'created_on'            => date('Y-m-d H:i:s'),
                    'created_by_user'       => $this->userInfo,
                    'categoryId'            => 0,
                    'programId'             => $this->input->post('program')
            );
            if($this->input->post('endDate') != '') {
                $data['end_date'] = $this->input->post('endDate').' 23:59:59';
            }
            $this->db->insert('my_tasks', $data);
            $this->_addPrizes($this->input->post('prizes'),$this->db->insert_id());
            $this->db->trans_complete();
//             _p("here1");    
            if ($this->db->trans_status() === FALSE) {
		throw new Exception('Transaction Failed');
            }
            return true;
        }
        else {
            echo $error;
            return false;
        }
    }
    
    
    function updateMyTask($id = null) {
        $this->initiateModel();
        $error = $this->validateUserInputData($data);
        if($error == '') {
            $this->db->trans_start();
            $sql = "UPDATE my_tasks set categoryId = ?,name = ?,description=?"
            . ",start_date=?,";
            if($this->input->post('endDate') != '') {
                $endDate = $this->input->post('endDate').' 23:59:59';
                $sql .= "end_date='{$endDate}',";
            }
            $sql .= "allow_submission_type=?,status='draft',"
            . "modified_on='".date('Y-m-d H:i:s')."',modified_by_user='".$this->userInfo."' where id = ?;";
            $this->db->query($sql,array($this->input->post('category'),$this->input->post('name'),$this->input->post('description'),$this->input->post('startDate'),$this->input->post('allowSubmissionType'),$id));
            $prizeUpdateSql = "UPDATE my_task_prizes set status = 'history' where my_task_id = ?;";
            $this->db->query($prizeUpdateSql,array($id));
            $this->_addPrizes($this->input->post('prizes'),$id);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
		throw new Exception('Transaction Failed');
            }
            return '2';
        }
        else {
            echo $error;
            return false;
        }
    }
    
    
    
    function udpateStatus($id,$status) {
        $this->initiateModel();
        $sql = "Update my_tasks set status=?, modified_on = NOW(), modified_by_user = 'test' where id=?";
        $this->db->query($sql,array($status,$id));
        $sql = "Update my_task_prizes set status=? where my_task_id=? AND status = 'draft'";
        $this->db->query($sql,array($status,$id));
        return true;
    }
    
    function _addPrizes($prizesData,$lastInsertedId) {
        $data = array();
        foreach($prizesData as $val) {
            $data[] = array(
                'my_task_id'   => $lastInsertedId,
                'prize_name'   => $val['prizeName'],
                'prize_amount' => $val['prizeAmount'],
                'status'       => 'draft'
            );
        }
        return $this->db->insert_batch('my_task_prizes',$data);
    }
    
    function validateUserInputData() {
        $error = '';
        if($this->input->post('name')                == '') $error .= '--Name is empty';
        if($this->input->post('description')         == '') $error .= '--Description is empty';
        if($this->input->post('startDate')           == '') $error .= '--Start Date is empty';
        if($this->input->post('allowSubmissionType') == '') $error .= '--Submission Type is empty';
        return $error;
    }

    function getSubmittedTasks($taskId,$start,$rows){
	$this->initiateModel();
	$sql = "select end_date from my_tasks where id=? and end_date is not NULL";
	$query  = $this->db->query($sql,array($taskId));
	$result = $query->result_array();
	
	$sql = "select SQL_CALC_FOUND_ROWS * from CA_TaskSubmission where taskRefId=?";
	$query  = $this->db->query($sql,array($taskId));
	$resultRows = $query->result_array();
	
	$queryCmdTotal1 = 'SELECT FOUND_ROWS() as totalRows';
	$queryTotal1 = $this->db->query($queryCmdTotal1);
	foreach ($queryTotal1->result() as $rowT1) {
		$num_rows  = $rowT1->totalRows;
	}
	
	$emptyArr = array();
	if(strtotime(date('d-m-y')) < strtotime($result[0]['end_date']) && $num_rows<1){
	    return $emptyArr;
	}
	$limit = "limit $start, $rows";
	if($rows=='-1' && $start=='-1'){
	    $limit     = '';
	}
        $escapedTaskId = $this->db->escape($taskId);	
	//echo date('Y-m-d').'>>>>>'.$result[0]['end_date'];
	//echo strtotime(date('Y-m-d')).'===='.strtotime($result[0]['end_date']);die;
	if(!empty($result) && strtotime(date('Y-m-d')) > strtotime($result[0]['end_date']) && $num_rows<1){
	    $sql = "select distinct SQL_CALC_FOUND_ROWS capt.displayName, (select name from my_tasks where id=? and status='live') as taskName, reward  as rewardAmout, camcmt.instituteId, capt.userId from  CA_ProfileTable capt join CA_MainCourseMappingTable camcmt on (capt.id=camcmt.caId)  join CA_MappingToTaskPrize camttp on (camttp.userId=capt.userId) left join CA_wallet cw on (cw.userId = capt.userId  and cw.entityType='task' and cw.action='task' and cw.status='earned' and cw.entityId=?) where capt.userId not in (select userId from CA_TaskSubmission where taskRefId=?) and capt.profileStatus='accepted' and camcmt.badge='CurrentStudent' and camttp.taskId=? and camttp.status='live'   and camttp.taskId=? $limit";
	    $queryTotal  = $this->db->query($sql, array($taskId,$taskId,$taskId,$taskId,$taskId));
	}

	if(!empty($result) && (strtotime(date('Y-m-d')) > strtotime($result[0]['end_date'])) && $num_rows>0){
	    $sql = "select distinct SQL_CALC_FOUND_ROWS capt.displayName, (select name from my_tasks where id=? and status='live') as taskName, cw.reward as rewardAmout, camcmt.instituteId, capt.userId from CA_ProfileTable capt join CA_MainCourseMappingTable camcmt on (capt.id=camcmt.caId) join CA_MappingToTaskPrize camttp on (camttp.userId=capt.userId) left join CA_wallet cw on (cw.userId = capt.userId and cw.entityType='task' and cw.action='task' and cw.status='earned' and cw.entityId=?) where capt.userId in (select userId from CA_TaskSubmission where taskRefId=?) and capt.profileStatus='accepted' and camcmt.badge='CurrentStudent' and camttp.status='live' and camttp.taskId=?
UNION
select distinct capt.displayName, (select name from my_tasks where id=? and status='live') as taskName, cw.reward as rewardAmout, camcmt.instituteId, capt.userId from CA_ProfileTable capt join CA_MainCourseMappingTable camcmt on (capt.id=camcmt.caId) join CA_MappingToTaskPrize camttp on (camttp.userId=capt.userId) left join CA_wallet cw on (cw.userId = capt.userId and cw.entityType='task' and cw.action='task' and cw.status='earned' and cw.entityId=?) where capt.userId not in (select userId from CA_TaskSubmission where taskRefId=?) and capt.profileStatus='accepted' and camcmt.badge='CurrentStudent' and camttp.status='live'  and camttp.taskId=? $limit";
	    $queryTotal  = $this->db->query($sql, array($taskId,$taskId,$taskId,$taskId,$taskId,$taskId,$taskId,$taskId));
	}
	

	if((!empty($result) && strtotime(date('Y-m-d')) < strtotime($result[0]['end_date']) || empty($result)) && $num_rows>0 || (!empty($result) && (strtotime(date('Y-m-d')) == strtotime($result[0]['end_date'])) &&  $num_rows>0) ){
	    //$sql = "select distinct SQL_CALC_FOUND_ROWS camttp.name as fileName, camttp.url, camttp.id, capt.displayName, (select name from my_tasks where id='".$taskId."' and status='live') as taskName, (select reward from CA_wallet where entityType='task' and action='task' and status='paid' and userId=capt.userId and entityId='".$taskId."') as rewardAmout, camcmt.instituteId, capt.userId from  CA_ProfileTable capt join CA_MainCourseMappingTable camcmt on (capt.id=camcmt.caId) join CA_MappingToTaskPrize camttp on (camttp.userId=capt.userId) where capt.userId in (select userId from CA_TaskSubmission where taskRefId='".$taskId."') and capt.profileStatus='accepted' and camcmt.badge='CurrentStudent' and camttp.taskId='".$taskId."' and camttp.status='live' $limit";
	     $sql = "select distinct SQL_CALC_FOUND_ROWS capt.displayName, (select name from my_tasks where id=? and status='live') as taskName, cw.reward as rewardAmout, camcmt.instituteId, capt.userId from  CA_ProfileTable capt join CA_MainCourseMappingTable camcmt on (capt.id=camcmt.caId) join CA_MappingToTaskPrize camttp on (camttp.userId=capt.userId) left  join CA_wallet cw on (cw.userId = capt.userId  and camttp.status='live' and cw.entityType='task' and cw.action='task' and cw.status='earned' and cw.entityId=? ) where capt.userId in (select userId from CA_TaskSubmission where taskRefId=?) and capt.profileStatus='accepted' and camcmt.badge='CurrentStudent' and camttp.taskId=? and camttp.status='live'   and camttp.taskId=? $limit";
	     $queryTotal  = $this->db->query($sql, array($taskId,$taskId,$taskId,$taskId,$taskId));
	}
	
	if((empty($result) && $num_rows<1) ||  ((strtotime(date('Y-m-d')) < strtotime($result[0]['end_date']) || strtotime(date('Y-m-d')) == strtotime($result[0]['end_date'])) && $num_rows<1)){
	    return $emptyArr;
	}
	
        $data['resultSet']        = $queryTotal->result_array();
	if(empty($data['resultSet'])){
	    return $emptyArr;
	}
	
	$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
	$queryTotal = $this->db->query($queryCmdTotal);
	foreach ($queryTotal->result() as $rowT) {
		$totalUsers  = $rowT->totalRows;
	}
	$data['totalUsers'] = $totalUsers;
	foreach($data['resultSet'] as $key=>$value){
	    $sql = "select camttp.name as fileName, camttp.url, camttp.id from CA_MappingToTaskPrize camttp where camttp.userId=? and camttp.taskId=? and camttp.status='live'";
	    $query  = $this->db->query($sql,array($value['userId'], $taskId));
	    $res = $query->result_array();
	    foreach($res as $k=>$v){
		$data['resultSet'][$key]['fileName'][] = $v['fileName'];
		$data['resultSet'][$key]['url'][] = $v['url'];
	    }
	}
	return $data;
    }
    
    function makePaymentForTask($amount,$userId,$taskId){
	$this->initiateModel('write');
	$sql = "insert into CA_wallet (userId, entityId, reward, entityType, action, status) values (?,?,?,?,?,?)";
	$this->db->query($sql,array($userId, $taskId, $amount, 'task', 'task','earned'));
    }
    function getTaskDetails($id) {
        $this->initiateModel('read');
        if(isset($id)) {
            $sql = "select * from my_tasks where id = ?";
            $data = array();
            $queryTotal  = $this->db->query($sql,array($id));
            $data        = $queryTotal->result_array();
            if(is_array($data[0]) && !empty($data[0]))
		return $data[0];
	    else
		return false;
        }
        else
            return false;
    }

}

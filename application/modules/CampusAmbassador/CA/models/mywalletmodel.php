<?php
class MyWalletModel extends MY_Model
{ 
	private $dbHandle = '';
	function __construct(){
		parent::__construct('CampusAmbassador');
	}
	/**
	 * returns a data base handler object
	 * @param none
	 * @return object
	 */

    	private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
        	$this->dbHandle = $this->getWriteHandle();
		}		
	}
	
	function getTotalPaidToUser($userId)
	{
		$this->initiateModel('read');	
		$query = "select sum(p.paidAmount) as paid from CA_payout p where userId = ? group by userId";
			 $query = $this->dbHandle->query($query,array($userId));
		         return $query->result();
	}
	
	function getTotalEarning($userId)
	{
		$this->initiateModel('read');	
		$query = "SELECT reward,entityType,action,status from CA_wallet where userId = ? and status != 'delete'";
			 $query = $this->dbHandle->query($query,array($userId));
		         return $query->result();
	}
	
	function getCreatedTask($userId)
	{
		$this->initiateModel('read');
		$query = "select mt.id,mt.name,w.reward from my_tasks mt, CA_wallet w where w.userId=? and mt.id = w.entityId and w.entityType = 'task' and w.action = 'task' and w.status = 'earned' and mt.status='live' order by w.reward desc";
			 $query = $this->dbHandle->query($query,array($userId));
		         return $query->result();
	}
	
	/***
	*
	*  Moderator views
	*
	***/
	
	function getAllCRForPaid($position, $item_per_page)
	{
		
		$this->initiateModel('read');

		$query = "SELECT SQL_CALC_FOUND_ROWS distinct caw.userId ,capt1.displayName,(SELECT cm.courseId FROM CA_MainCourseMappingTable AS cm, `CA_ProfileTable` AS c WHERE c.id = cm.caId AND cm.status = 'live' AND c.userId = caw.userId AND c.profileStatus = 'accepted' limit 1) as mainCourseId,(SELECT cp.creationDate from CA_payout cp where cp.userId = caw.userId order by cp.creationDate desc limit 1) as lastPaydate,(select sum(caw1.reward) from CA_wallet caw1 where caw1.status ='earned' and caw1.userId=caw.userId) as earnedRewards, ifnull((select sum(cap.paidAmount) from CA_payout cap where cap.userId=caw.userId group by cap.userId),0) as paidRewards from CA_wallet caw join CA_ProfileTable capt1 on (capt1.userId=caw.userId) where caw.userId in (select userId from CA_ProfileTable capt where capt.profileStatus='accepted') and capt1.profileStatus='accepted' having (earnedRewards - paidRewards)>0 order by earnedRewards desc LIMIT $position, $item_per_page";
			
			$query = $this->dbHandle->query($query);
			$result_array["result"] =  $query->result_array();
			
			$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
			$queryTotal = $this->dbHandle->query($queryCmdTotal);
			$queryResults = $queryTotal->result_array();
			$totalRows = $queryResults[0]['totalRows'];
			$result_array["totalRows"] = $totalRows;
			return $result_array;
		
	}
	
	function addPaidAmount($userId,$paidAmount,$chequeNumber)
	{
		$this->initiateModel('write');
		$query = "INSERT into `CA_payout` (`userId`,`paidAmount`,`chequeNumber`) values(?,?,?)";
		$this->dbHandle->query($query,array($userId,$paidAmount,$chequeNumber));
		$insertId = $this->dbHandle->insert_id();
	        return $insertId;
	}
	
	function checkCrCategory($userId)
	{
		$this->initiateModel('read');
		if($userId !=''){
			$query = "select caft.userId,cpd.category_id from CA_ProfileTable caft,CA_MainCourseMappingTable camt,categoryPageData cpd where camt.caId = caft.id
					AND caft.profileStatus = 'accepted'
					AND camt.instituteId>0
					AND camt.courseId>0
					AND camt.locationId>0
					AND camt.courseId = cpd.course_id
					AND camt.badge = 'CurrentStudent'
					AND caft.userId IN ($userId)
					AND cpd.status = 'live'";
					return $this->dbHandle->query($query)->result_array();
		}
		return array();

	}

	function getCreditedAmount($userId)
	{
		$this->initiateModel('read');
		$res = array();
		if($userId !='' && $userId >0){
			$query = "select sum(w.reward) as totalEarnings, ifnull((select sum(p.paidAmount) as paidAmount from shiksha.CA_payout p
 where p.userId = ? group by p.userId limit 1),0) as paidAmount from shiksha.CA_wallet w where w.status = 'earned'
 and w.userId = ? group by w.userId";
 			$res = $this->dbHandle->query($query,array($userId,$userId))->result_array();
 			$res[0]['creaditedAmount'] = ($res[0]['totalEarnings'] - $res[0]['paidAmount']);
		}
		return $res;
	}

	function addInWallet($data){
		$this->initiateModel('write');
		$this->dbHandle->insert_batch('CA_payout', $data); 
		return $this->dbHandle->insert_id();
	}
    

	/**
	 * This function is used to update naukri data only
	 * This function is called in mywallet library
	 * Data updates in these table naukri_salary_data, naukri_alumni_stats, naukri_functional_salary_data
	 * Added by akhter
	 */
	function updateNaukriData($tableName,$data){
		$this->initiateModel('write');
		$this->dbHandle->insert_batch($tableName, $data); 
		return $this->dbHandle->insert_id();		
	}

}

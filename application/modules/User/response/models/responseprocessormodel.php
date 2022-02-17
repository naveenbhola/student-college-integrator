<?php
/** 
 * Model for database related operations related to response.
*/

class responseprocessormodel extends MY_Model {
	/**
	 * Variable for DB Handling
	 * @var object
	 */
	private $dbHandle = '';
	
	/**
	 * Constructor Function
	 */
	function __construct(){
		parent::__construct('MIS');
	}
	
	/**
	 * Function to initiate the Model
	 * 
	 * @param string $operation
	 *
	 */
	private function initiateModel($operation = 'read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		} else {
        	$this->dbHandle = $this->getWriteHandle();
		}
	}

	public function getResponseCount($entityIds,$userLocationIds,$dateRange,$entityType){
		if(
			!is_array($entityIds) || count($entityIds) <=0 ||
			empty($dateRange) || empty($dateRange['from']) || empty($dateRange['to']) ||
			empty($entityType)			
		){
			return false;
		}

		if(!empty($userLocationIds) && (!is_array($userLocationIds) || (is_array($userLocationIds) && count($userLocationIds) <= 0)))
		{
			return false;
		}
		$this->initiateModel('read');
		$this->dbHandle->select('count(distinct(tlt.userid),tlt.listing_type_id ) count');
		$this->dbHandle->from('tempLMSTable tlt ');
		if(is_array($userLocationIds) && count($userLocationIds) >0){
			$this->dbHandle->join('tuser tu ',' tlt.userId = tu.userid ','inner');
			$this->dbHandle->where_in('tu.city',$userLocationIds);
		}
		$this->dbHandle->join('tuserflag tuf','tlt.userId = tuf.userId','inner');
		
		$this->dbHandle->where('tlt.listing_type',$entityType);
		$this->dbHandle->where('tuf.isTestUser','NO');
		$this->dbHandle->where('tlt.submit_date >= ',$dateRange['from'].' 00:00:00');
		$this->dbHandle->where('tlt.submit_date <= ',$dateRange['to'].' 23:59:59');
		$this->dbHandle->where_in('tlt.listing_type_id',$entityIds);
		
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}
}
?>
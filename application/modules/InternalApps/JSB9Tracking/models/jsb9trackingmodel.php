<?php

class jsb9trackingmodel extends MY_Model
{

	public function getJSB9DataForSelectedModule($selectedModulePages,$fromDate,$toDate,$selectedSite){
		$this->dbHandle = $this->getReadHandleByModule('JSB9Report');
		$this->dbHandle->select('*');
		$this->dbHandle->from('tracking_data');
		$this->dbHandle->where('Site',$selectedSite);
		$this->dbHandle->where('Date <=',$fromDate);
		$this->dbHandle->where('Date >=',$toDate);
		if($selectedModulePages[0] != 'all'){
			$this->dbHandle->where_in('PageName',$selectedModulePages);	
		}
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}
}
?>

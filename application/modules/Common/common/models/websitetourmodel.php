<?php 
class websitetourmodel extends MY_Model {
	
	function __construct() {
		parent::__construct('default');
		$this->load->config('common/websiteTourConfig');
	}

	private function initiateModel($mode = "write", $module = ''){
		if($mode == 'read') {
		    $this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
		    $this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	public function trackButtonClick($data){
		if(!empty($data)){
			$data['session_id'] = getVisitorSessionId();
			$this->initiateModel('write');
			$this->dbHandle->insert('website_tour_tracking',$data);
		}
	}

	public function checkIfUserSeenTour($feature_type,$userId){
		$this->initiateModel('read');
		$data = $this->dbHandle->where(array('feature_type'=>$feature_type,'user_id'=>$userId))->get('website_tour_tracking')->result_array();
		return empty($data) ? false : true;
	}
}
?>
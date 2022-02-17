<?php
/*
 * Model for study abroad Content Org pages
 *
 */
class abroadcontentorgmodel extends MY_Model{
	private $dbHandle = '';
	private $dbHandleMode = '';
	
	function __construct(){
		parent::__construct('SAContent');
	}
	
	private function initiateModel($mode = "write"){
		if($this->dbHandle && $this->dbHandleMode == 'write')
		return;
		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
	}
	
	
	/*
	* Get life cycle tags' data for Content org pages..
	*/
	public function getDataForLifeCycleTags($stage,$filterValues)
	{
		if(empty($stage))
		{
			return false;
		}

		$this->initiateModel('read');

		$query = $this->dbHandle;
		$this->dbHandle->where(array('status' => 'live', 'level' => $stage));
		if($filterValues != ''){
			$filterValues .= ',all';
			$this->dbHandle->where_in('value',explode(',',$filterValues));
		}
		$this->dbHandle->from('study_abroad_content_lifecycle_tags');
		$result = $this->dbHandle->get()->result_array();
		
		$data['contentIds'] = array();
		$data['stageValues'] = array();
		
		foreach ($result as $row)
		{
			if(!in_array($row['contentId'], $data['contentIds']))
			{
				$data['contentIds'][] = $row['contentId'];
			}
			
			if(!in_array($row['value'], $data['stageValues']))
			{
				$data['stageValues'][] = $row['value'];
			}
		}
		
		return $data;
	}
	
	public function getOrderOfFilters($contentId = array(),$stageName = ''){
		if(count($contentId) <= 0 || $stageName == ''){
			return array();
		}
		
		$this->initiateModel('read');
		$this->dbHandle->select('value');
		$this->dbHandle->select('count(1) as articleCount');
		$this->dbHandle->from('study_abroad_content_lifecycle_tags');
		$this->dbHandle->where(array('status'	=> 'live',
					     'level'	=> $stageName
					     ));
		$this->dbHandle->where_in('contentId',$contentId);
		$this->dbHandle->group_by('value');
		$this->dbHandle->order_by('articleCount','desc');
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}
        
}

?>        

<?php 
class institutemodel extends MY_Model{
	public $dbHandle = '';
    public $dbHandleMode = '';
    
    function __construct() {
		parent::__construct('Listing');
    }
    
    private function initiateModel($mode = "write") {
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

    function getInstituteData($instituteId) {
    	$this->initiateModel('read');
    	$this->dbHandle->select('name,type, synonym,abbreviation');
    	$this->dbHandle->where('entity_type',$entityType);
    	$this->dbHandle->where_in('institute_id',$instituteId);
    	return $this->dbHandle->get('institutes')->result_array();
    }

    /**
     * [getInstituteSuggestions description]
     * @author Ankit Garg <g.ankit@shiksha.com>
     * @date   2016-07-05
     * @param  string     $keyword [word for which suggestion will work]
     * @param  integer    $limit   [limit of suggestions]
     * @return [type]     $suggestions [associative array of id name pair]
     */
    function getInstituteSuggestions($keyword = '', $limit = 5) {
    	$this->initiateModel();
    	$this->dbHandle->select('institute_id, name')
    					->where('name LIKE ',$keyword.'%')
    					->where('status','live')
    					->limit($limit);
		$results = $this->dbHandle->get('institutes')->result_array();
		$suggestions = array();
		foreach($results as $val) {
			$suggestions[$val['institute_id']] = $val['name'];
		}
		return $suggestions;
    }
}
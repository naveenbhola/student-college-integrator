<?php 
class autosuggestormodel extends MY_Model{
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

    /**
     * [getInstituteSuggestions description]
     * @author Ankit Garg <g.ankit@shiksha.com>
     * @date   2016-07-05
     * @param  string     $keyword [word for which suggestion will work]
     * @param  integer    $limit   [limit of suggestions]
     * @return [type]     $suggestions [associative array of id name pair]
     */
    function getSuggestions($keyword = '', $limit = 50, $suggestionType = 'all',$statusCheck=false) {
    	$this->initiateModel();
        if($statusCheck)
            $statusCondition = " status in ('live','draft','disabled') ";
        else
            $statusCondition = " status = 'live' ";
        
        
        $instituteQueryResult  =array();
        $universityQueryResult =array();
        // select listing of type INSTITUTE when $suggestionType is anything but UNIVERSITY
        if($suggestionType!='university'){
            $this->dbHandle->select('listing_id as id, name,listing_type as type');
            $this->dbHandle->from('shiksha_institutes');
            $this->dbHandle->like('name', $keyword,'after');
            $this->dbHandle->where("listing_type = 'institute'");
            $instituteQuery = $this->dbHandle->_compile_select();
            $instituteQuery = $instituteQuery. ' AND '.$statusCondition.' LIMIT ?';
            $instituteQueryResult = $this->dbHandle->query($instituteQuery,array((int)$limit))->result_array();
//            _p($instituteQuery);
        }
        $this->dbHandle->_reset_select();
        // select listing of type UNIVERSITY when $suggestionType is anything but INSTITUTE
        if($suggestionType!='institute'){
            $this->dbHandle->select('listing_id as id, name,listing_type as type');
            $this->dbHandle->from('shiksha_institutes');
            $this->dbHandle->like('name', $keyword,'after');
            $this->dbHandle->where("listing_type = 'university'");
            $universityQuery = $this->dbHandle->_compile_select();
            $universityQuery = $universityQuery. ' AND '.$statusCondition.' LIMIT ?';
            $universityQueryResult = $this->dbHandle->query($universityQuery,array((int)$limit))->result_array();
//            _p($universityQuery);
        }
        
        $order = array('institute','university');
        if(count($universityQueryResult)<count($instituteQueryResult) && count($universityQueryResult)<25){
            $order = array('university','institute');
        }
        $returnArrary  = array();
        foreach ($order as $key=>$value){
            $resultType = $value.'QueryResult';
            foreach ($$resultType as $row){
                if(count($returnArrary)>=25 && $key==0 || count($returnArrary)>=50){break;}
                $returnArrary[] = $row;
            }
        }
//        _p($returnArrary);
        return $returnArrary;        
    }
}
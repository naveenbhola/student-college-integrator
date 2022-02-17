<?php

class countryhomewidgetmodel extends MY_Model{
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    
    public function __construct() {
        parent::__construct('countryPage');
    }
    
    // function to be called for getting dbHandle with read/write mode
    private function initiateModel($mode='read'){
        if($this->dbHandle && $this->dbHandleMode == 'write'){
            return ;
        }
        $this->dbHandleMode = $mode;
        if($mode == 'write'){
            $this->dbHandle = $this->getWriteHandle();
        }elseif ($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
		}
	}
    
    public function getWidgetDetailByCountryId($countryId){
		$this->initiateModel('read');
		$this->dbHandle->select('c.name,c.countryId as country,achwd.*');
	    $this->dbHandle->from('`countryTable` c ');
	    $this->dbHandle->join('`abroadCountryHomeWidgetDetails` achwd ', "achwd.countryId = c.countryId and achwd.status='live'",'left');
	    $this->dbHandle->where('c.countryId', $countryId);
        $this->dbHandle->where('c.showOnRegistration','YES');
        
        $result = $this->dbHandle->get()->result_array();
        foreach($result as $key=>$row){
        	$result[$key]['visaArticleLink'] = empty($result[$key]['visaArticleLink'])?'':SHIKSHA_STUDYABROAD_HOME.$result[$key]['visaArticleLink'];
			$result[$key]['partTimeWorkArticleLink'] = empty($result[$key]['partTimeWorkArticleLink'])?'':SHIKSHA_STUDYABROAD_HOME.$result[$key]['partTimeWorkArticleLink'];
			$result[$key]['postStudyWorkArticleLink'] = empty($result[$key]['postStudyWorkArticleLink'])?'':SHIKSHA_STUDYABROAD_HOME.$result[$key]['postStudyWorkArticleLink'];
			$result[$key]['economicArticleLink'] = empty($result[$key]['economicArticleLink'])?'':SHIKSHA_STUDYABROAD_HOME.$result[$key]['economicArticleLink'];
			$result[$key]['popularSectorArticleLink'] = empty($result[$key]['popularSectorArticleLink'])?'':SHIKSHA_STUDYABROAD_HOME.$result[$key]['popularSectorArticleLink'];
        }
	    //echo $this->dbHandle->last_query();
	    return $result;
	}
	
	public function saveCountryHomeWidgets($dataArray, $isTransactionActive = false){
		$this->initiateModel('write');
		if(!$isTransactionActive){
			$this->dbHandle->trans_start();
		}
		
		$udata = array(
		   'status' => 'history'
		);
		$this->dbHandle->where('countryId', $dataArray['countryId']);
		$this->dbHandle->where('status', 'live');
		$this->dbHandle->update('abroadCountryHomeWidgetDetails', $udata);
		
		$dataArray['widgetDetails']['visaArticleLink'] = str_replace(SHIKSHA_STUDYABROAD_HOME, '', $dataArray['widgetDetails']['visaArticleLink']);
		$dataArray['widgetDetails']['partTimeWorkArticleLink'] = str_replace(SHIKSHA_STUDYABROAD_HOME, '', $dataArray['widgetDetails']['partTimeWorkArticleLink']);
		$dataArray['widgetDetails']['postStudyWorkArticleLink'] = str_replace(SHIKSHA_STUDYABROAD_HOME, '', $dataArray['widgetDetails']['postStudyWorkArticleLink']);
		$dataArray['widgetDetails']['economicArticleLink'] = str_replace(SHIKSHA_STUDYABROAD_HOME, '', $dataArray['widgetDetails']['economicArticleLink']);
		$dataArray['widgetDetails']['popularSectorArticleLink'] = str_replace(SHIKSHA_STUDYABROAD_HOME, '', $dataArray['widgetDetails']['popularSectorArticleLink']);
		
		$this->dbHandle->insert('abroadCountryHomeWidgetDetails',$dataArray['widgetDetails']);
		//$rowId = $this->dbHandle->insert_id();

		if(!$isTransactionActive){
		$this->dbHandle->trans_complete();
		
		if ($this->dbHandle->trans_status() === FALSE)
		{
		throw new Exception('Transaction Failed');
		return true;
		}
		}	
	}
	
	public function getCountryHomeTableData($filterData){
		$this->initiateModel();
		$dataTableParams 	= $filterData['dataTableParams'];
		//_p($dataTableParams);
		$this->initiateModel('read');
		$this->dbHandle->select('SQL_CALC_FOUND_ROWS c.name,c.countryId as country,acpd.country_id,achwd.modifiedBy,achwd.modifiedAt',false);
	    $this->dbHandle->from('`countryTable` c ');
	    $this->dbHandle->join('`abroadCategoryPageData` acpd ', "acpd.country_id = c.countryId and acpd.status='live'");
		$this->dbHandle->join('`abroadCountryHomeWidgetDetails` achwd ', "achwd.countryId = c.countryId and achwd.status='live'",'left');
        $this->dbHandle->where('c.showOnRegistration','YES');
        
		if($dataTableParams['search']!='')
		{
			$this->dbHandle->where('c.name like "%'.mysql_escape_string($dataTableParams['search']).'%"','',false);
		}
		
		$this->dbHandle->order_by('achwd.modifiedAt','DESC');
		$this->dbHandle->group_by('c.countryId');
		$this->dbHandle->limit($dataTableParams['length'],$dataTableParams['start']);
		
        $result = $this->dbHandle->get()->result_array();
		//echo  $this->dbHandle->last_query();
		$this->dbHandle->select('found_rows() as totalCount');
		$count = $this->dbHandle->get()->result_array();
		
		$userInfo = array();
		$userIds = array();
		foreach($result as $user)
	    {
	    	$userIds[] = $user['modifiedBy']; 
	    }
	    if(count($userIds) > 0)
	    {
			$this->dbHandle->select('tu.userId,
										tu.email,
										tu.mobile,
										tu.firstname,
										tu.lastname');
			
			$this->dbHandle->from('tuser tu');
		    $this->dbHandle->where_in('tu.userId',$userIds);
		    $userResult = $this->dbHandle->get()->result_array();
			foreach($userResult as $key=>$val){
				$userInfo[$val['userId']] = $val;
			}
		}

		return array('rows'=>$result,
					 'userInfo'=>$userInfo,
					 'totalCount'=>$count[0]['totalCount']
					);
	}
}

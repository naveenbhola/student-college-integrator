<?php

class countryHomePosting extends MX_Controller
{
    private $usergroupAllowed;
    private $saCMSToolsLib;
    private $countryHomeLib;
    public function __construct() {
        parent::__construct();
        $this->usergroupAllowed = array('saAdmin','saContent');
        $this->config->load('studyAbroadCMSConfig');
        // for common tools like abroad cms user validation
        $this->saCMSToolsLib= $this->load->library('saCMSTools/SACMSToolsLib');
        $this->countryHomeLib= $this->load->library('countryPage/countryHomeWidgetLib');
    }
    
    public function index(){
        $this->viewCountryHomeWidgetTable();
    }
    
    public function countryHomeAbroadUserValidation($noRedirectionButReturn = false)
    {
        $usergroupAllowed 	= $this->usergroupAllowed;
        $validity 		    = $this->checkUserValidation();	    
        $returnArr = $this->saCMSToolsLib->cmsAbroadUserValidation($validity, $usergroupAllowed,$noRedirectionButReturn);
        return $returnArr;
    }
    
    public function viewCountryHomeWidgetTable(){
        $displayData = $this->countryHomeAbroadUserValidation();
        $displayData['formName'] = ENT_SA_VIEW_COUNTRYHOME_WIDGETS;
        $displayData['selectLeftNav']   = "COUNTRY_HOME";
        $data ['page']=  'countryHomeWidgetTable';
        $this->load->view('countryPage/countryHomeOverview',$displayData);
    }
    
    public function editCountryHomeWidgets($countryId){
        // get the user data
        if($countryId <=2){
            show_404();
        }
        $displayData = $this->countryHomeAbroadUserValidation();
        $displayData['formName'] = ENT_SA_EDIT_COUNTRYHOMEWIDGETS;
        $displayData['selectLeftNav']   = "COUNTRY_HOME";
        $this->abroadCommonLib 		 = $this->load->library('listingPosting/AbroadCommonLib');
        $displayData['currencyData'] = $this->abroadCommonLib->getCurrencyList();
        $displayData['widgetData']   = $this->countryHomeLib->getWidgetDetailByCountryId($countryId);
        if(array_key_exists('error',$displayData['widgetData'])){
            show_404();
        }
        
        if($displayData['widgetData']['modifiedBy'] !='')
        {
            $this->load->model('user/usermodel');
            $usermodel = new usermodel;
            $user = $usermodel->getUserById($displayData['widgetData']['modifiedBy']);
            $displayData['widgetData']['modifiedByName'] = $user->getFirstName()." ".$user->getLastName();
        }
        //_p($displayData['userid']);
        //_p($displayData['widgetData']);
        $this->load->view('countryPage/countryHomeOverview',$displayData);
    }
    
    public function validateContentURL(){
        $userValidation = $this->countryHomeAbroadUserValidation(true);
        if(!empty($userValidation['error']) && !empty($userValidation['error_type'])) {
            echo json_encode($userValidation);
            return;
        }else{
            $userid = $userValidation['userid'];
        }
        $return = array();
        $contentURL = base64_decode($this->input->post('contentURL'));
        $contentURL = str_replace(SHIKSHA_STUDYABROAD_HOME,'',$contentURL);
        if($contentURL !=''){
            $saContentLib= $this->load->library('blogs/saContentLib');
            $return = $saContentLib->validateContentURL($contentURL);
        }else{
            $return['error'] = "Please enter content URL";
        }
        echo json_encode($return);
    }
    
    private function _getWidgetRequestData($userid){
       $insertArray = array(); 
       $result          = array();
       $result['countryId']                 = $this->input->post('countryId');
       $result['visaProcessComplexity']     = $this->input->post('visaComplexity');
       
       if($this->input->post('feeAmount')!='' && $this->input->post('visaFeeCurrencyId')!=''){
       $result['visaFeeAmount']             = trim($this->input->post('feeAmount'));
       $result['visaFeeUnit']               = $this->input->post('visaFeeCurrencyId');
       }
       
       $result['visaTimeline']              = trim($this->input->post('visaTimeLine'));
       $result['visaDescription']           = trim($this->input->post('visaDescription'));
       $result['visaArticleLink']           = trim($this->input->post('visaArticleLink'));
       $result['partTimeWorkStatus']        = $this->input->post('partTimeWorkStatus');
       $result['partTimeWorkHours']         = trim($this->input->post('partTimeHours'));
       $result['partTimeWorkDays']          = trim($this->input->post('partTimeDays'));
       $result['partTimeWorkDescription']   = trim($this->input->post('partTimeDescription'));
       $result['partTimeWorkArticleLink']   = trim($this->input->post('partTimeArticleLink'));
       $result['postStudyWorkStatus']       = $this->input->post('postStudyWorkStatus');
       $result['postStudyWorkHours']        = trim($this->input->post('postStudyHours'));
       $result['postStudyWorkDays']         = trim($this->input->post('postStudyDays'));
       $result['postStudyWorkDescription']  = trim($this->input->post('postStudyDescription'));
       $result['postStudyWorkArticleLink']  = trim($this->input->post('postStudyArticleLink'));
       $result['ecocnomicGrowthRate']       = trim($this->input->post('ecocnomicGrowthRate'));
       $result['economicDescription']       = trim($this->input->post('economicDescription'));
       $result['economicArticleLink']       = trim($this->input->post('economicArticleLink'));
       $result['popularSector']             = trim($this->input->post('popularSector'));
       $result['popularSectorArticleLink']  = trim($this->input->post('popularSectorArticleLink'));
       $result['addedBy']                   = $this->input->post('addedBy');
       $result['addedAt']                   = $this->input->post('addedAt');
       $result['modifiedAt']                = date('Y-m-d H:i:s');
       $result['status']                    = 'live';
       $result['modifiedBy']                = $userid;
       
       $insertArray['widgetDetails'] = $result;
       $insertArray['countryId']     = $this->input->post('countryId');
       return $insertArray;
    }
    
    public function saveCountryHomeWidgets(){
        $userValidation = $this->countryHomeAbroadUserValidation(true);
        if(!empty($userValidation['error']) && !empty($userValidation['error_type'])) {
            echo json_encode($userValidation);
            return;
        }else{
            $userid = $userValidation['userid'];
        }
        $result = $this->_getWidgetRequestData($userid);
        
        $this->countryHomeLib->saveCountryHomeWidgets($result);
    }
	/*
	 * function to get data for data table
	 */
	public function getDataTableData($tableName)
	{
		//this is to know the call sequence incase of async calls
        $draw = $this->input->post('draw');
        // prepare params for query
        $dataTableParams = array();
        $dataTableParams['start'] = $this->input->post('start');            // limit offset
        $dataTableParams['length'] = $this->input->post('length');          // num of records to be fetched
        $search = $this->input->post('search');                             
        $dataTableParams['search'] = trim($search['value']);                      // search value

        // get records
        $resultRows =array();
        switch($tableName)
        {
            case ENT_SA_VIEW_COUNTRYHOME_WIDGETS:
                $tableResultData = $this->_getCountryHomeWidgetTableData($resultRows,$dataTableParams);
                break;
        }
        // prepare & send response
        $returnDataArray = array(
                            'draw'              => intval($draw),
                            'recordsTotal'      => $tableResultData['totalCount'],
                            'recordsFiltered'   => $tableResultData['totalCount'],
                            'data'              => $resultRows,
                            );
        echo json_encode($returnDataArray);
	}
	private function _getCountryHomeWidgetTableData(&$resultRows,&$dataTableParams)
    {
        $loggedInData = $this->countryHomeAbroadUserValidation();
        $tableResultData = $this->countryHomeLib->getCountryHomeWidgetTableData(array('dataTableParams'=>$dataTableParams));
		$userInfo = $tableResultData['userInfo'];
		//prepare data for the view
        if($tableResultData['totalCount'] > 0)                                 
        {
            foreach ($tableResultData['tableResult'] as $k=>$row)
            {
				$userName = "--";
				if(count($userInfo[$row['modifiedBy']])>0)
				{
					$userName = htmlentities($userInfo[$row['modifiedBy']]['firstname']." ".$userInfo[$row['modifiedBy']]['lastname']);
				}
                $formattedRowData = array(
                            ($dataTableParams['start']+($k+1)),
                            '<p class="cms-associated-cat">'.$row['name'].'<br>'.
                            '<a href="'.ENT_SA_CMS_COUNTRYHOME_PATH.ENT_SA_EDIT_COUNTRYHOMEWIDGETS.'/'.$row['country_id'].'">Edit</a></p>',
                            '<p class="cms-associated-cat">'.$userName.'</p>',
                            '<p>'.($row['modifiedAt']!=''?date_format(date_create($row['modifiedAt']),'d M Y'):'--').'</p>'
                            );
                array_push($resultRows,$formattedRowData);
            }
        }
        return $tableResultData;
    }
}

<?php


class OafPorting extends MX_Controller
{
	function init()
	{
		ini_set('max_execution_time', '10000');
	}

    private function _createHeader($misRow){
        $header = "";
        $line = "";
        foreach($misRow as $key=>$rowData){
		//if(!($key == 'response')){
			$value = '"' . $key . '"' . ",";
			$line.= $value;
		//}
	}
        $header .= trim($line)."\n";
        return $header;
    }

    private function _validateUser() {
        $this->_validateuser = $this->checkUserValidation();
        if (empty($this->_validateuser['0']['userid'])) {
            header('location:'.ENTERPRISE_HOME);
            exit();
        }
        elseif ($this->_validateuser['0']['userid'] != LMS_PORTING_USER_ID) {
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
        }
    }

	private function _loadHeaderContent($tabId) {
		$headerComponents = array(
			'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','lms_porting'),
			'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','onlineformporting'),
			'displayname'=> (isset($this->_validateuser[0]['displayname'])?$this->_validateuser[0]['displayname']:""),
			'tabName'   =>  '',
			'taburl' => site_url('enterprise/Enterprise'),
			'metaKeywords'  =>'',
			'prodId'=>$tabId
		);
		$this->load->library('Enterprise_client');
		$headerTabs = $this->enterprise_client->getHeaderTabs(1,$this->_validateuser[0]['usergroup'],$this->_validateuser[0]['userid']);

		$headerComponents['headerTabs'] = $headerTabs;
		$headerCMSHTML = $this->load->view('enterprise/headerCMS', $headerComponents,true);
		$headerTABSHTML = $this->load->view('enterprise/cmsTabs',$headerComponents,true);
		return array($headerCMSHTML,$headerTABSHTML);
	}

	function addNewPorting()
	{
		$this->_validateUser();
		$data = array();
		$data['headerContentaarray'] = $this->_loadHeaderContent(OAF_PORTING_TAB_ID);
		$this->load->view('oafPorting/oafAddPorting',$data);
	}

	function getFormList($clientId,$validation=false)
	{
		if(empty($clientId))
		{
			$clientId = $this->input->post('clientId');
		}
		$this->load->library('oafPorting/OAFPortingFactory');
		
		$portingRepository = OAFPortingFactory::getPortingRepository();
		
		$formList = $portingRepository->getFormListByClientId($clientId,$validation);
		if($validation){
			return $formList;
		}
		$data = array();
		$data['formList'] = $formList;
		$this->load->view('oafPorting/oafFormList.php',$data);
	}

	function addPorting()
	{
		$this->load->library('oafPorting/OAFPortingFactory');
		$portingRepository = OAFPortingFactory::getPortingRepository();
		$portingRepository->addPorting($_POST);
		echo 'success';
	}

	function updateProtingStatus()
	{
		$this->_validateUser();
		
		$portingId = $this->input->post('porting_id');
		$status = $this->input->post('status');
		$portingmodel = $this->load->model('oafPorting/oafportingmodel');
		if($portingmodel->changePortingStatus($portingId,$status)) {
			if($status == 'live'){
				$portingmodel->updatePortingsRunFirstTime($portingId, 'no');
			}
			echo 'success';
		}
	}
    
    function getFieldMapping()
    {
		$formIds = array();
    	foreach ($_POST['singleForm'] as $key=>$formId)
		{
			$formIds[] = $formId;
		}
    	$clientFields = array(); 
    	$var_name = $_POST['var_name'];
    	$var_key = $_POST['var_key'];
    	$count = count($var_name);
    	for ($i = $_POST['preEditedCount'] ;$i < $count ; $i++)
    	{
    		if(!empty($var_name[$i])){
    			$clientFields[] = array(
    								'client_field_name' => $var_name[$i],
    								'master_field_id'  => $var_key[$i]
    									);
    		}
    	}
    	$data['customizedCount'] =$_POST['preEditedCount'];
    	$data['clientFields'] = $clientFields;
    	// get formID preeditedvalues new edited values and then fill this view as cleintFields
    	$this->load->config('oafPortingConfig');
    	$customizedFields = $this->config->item('fieldMapping');
    	$this->load->library('oafPorting/OAFPortingFactory');
		$portingRepository = OAFPortingFactory::getPortingRepository();
		$fieldMappings = $portingRepository->getFieldMapping($formIds);
		$data['shikshaFields'] = $fieldMappings;
		$data['customized'] = array_keys($customizedFields['customized']);
		$this->load->view('oafPorting/oafFieldMapping.php',$data);
    }

    function manageOAFPortings(){
		$this->_validateUser();
		$data = array();
		$data['headerContentaarray'] = $this->_loadHeaderContent(OAF_PORTING_TAB_ID);
		
		$this->load->model('oafPorting/oafportingmodel');
		$portings = $this->oafportingmodel->getAllPortings('oaf');

		$data['portings'] = $portings;
		$this->load->view('oafPorting/oafManagePorting',$data);
	}

	public function setCustomizedFields()
	{
		$clientId = $this->input->post('clientId');
		$customFieldIds = $this->input->post('customFieldId');
		$customField = $this->input->post('customField');
		$entityIds = $this->input->post('courseId');

		$this->load->model('oafPorting/oafportingmodel');

		$insert = array();

		foreach ($entityIds as $key => $value)
		{
			if ($customFieldIds[$key])
			{
				
				$insert[] = array("client_id"=>$clientId,"entity_id"=>$entityIds[$key],"entity_value"=>$customFieldIds[$key],"entity_type"=>$customField,"status"=>'live');
			}
		}

		$this->oafportingmodel->setCustomizedMappedFields($clientId,$customField,$insert);
		
		echo "Success";
	}

	public function getCustomForm($client_id, $customField, $formIds,$validation=false) {
		if((empty($client_id)) || (empty($customField)) || (empty($formIds))) {
			return;
		}

		$this->config->load('oafPorting/oafPortingConfig');
        $config = $this->config->item('fieldMapping');
		$fieldType = $config['customized'][$customField]['field_type'];
		$functionName = $config['customized'][$customField]['function_name'];
		$fieldId = $config['customized'][$customField]['fieldId'];
 
 		$this->load->model('oafPorting/oafportingmodel');

 		$params = array();
 		$params['formIds'] = $formIds;
 		$params['fieldId'] = $fieldId;
		$fieldAllData = $this->$functionName($params);

		$mappedFields = array();
		$mappedFieldsArray = $this->oafportingmodel->getMappedCourseName($client_id,$fieldAllData['entityIds'],$fieldType);
		foreach ($mappedFieldsArray as $mapped)	{
			$mappedFields[$mapped['entity_id']] = $mapped['entity_value'];
		}

		$data = array();
		$data['entityData'] = $fieldAllData['entityData'];
		$data['clientId'] = $client_id;
		$data['customField'] = $fieldType;
		$data['mappedFields'] = $mappedFields;
		if ($validation)
		{
			return $data;
		}
		$html = $this->load->view('oafPorting/courseDetails', $data, true);
		echo $html;

	}

	public function getDetailsForCustomizedLayer($params) {

		$fieldId = $params['fieldId'];
		$fieldPrefilledData = $this->oafportingmodel->getFieldPrefilledData($fieldId);
		$entityIds = explode(",",$fieldPrefilledData['values']);

		$entityData = array();
		foreach ($entityIds as $entityId) {
			$entityData[]['course_id'] = $entityId;
		}

		$data = array();
		$data['entityData'] = $entityData;
		$data['entityIds'] = $entityIds;

		return $data;		

	}

	public function getCourseNameForForm($params) {
		
		$formIdsArray = explode(",",$params['formIds']);

		$this->load->model('oafPorting/oafportingmodel');
		$courses = $this->oafportingmodel->getCoursesForForms($formIdsArray);

		$courseIds = array();
		foreach ($courses as $course) {
			$courseIds[] = $course['course_id'];
		}

		$data = array();
		$data['entityData'] = $courses;
		$data['entityIds'] = $courseIds;

		return $data;
	}

	public function startPortingForNewPortings(){
		$this->validateCron();
		$this->init();

		$this->load->library('oafPorting/OAFPortingFactory');
		$portingRepository = OAFPortingFactory::getPortingRepository();
		$porterObj = OAFPortingFactory::getPorterObj();

		$portingmodel = $this->load->model('oafPorting/oafportingmodel');
		$lastprocessedId = $portingmodel->getLastProcessedId('OAF_RESPONSE_PORTING');

		// _p($lastprocessedId);die;

		$portings = $portingRepository->getAllNewPortings();
		$testPortings = $portingRepository->getAllTestPortings();

		foreach ($testPortings as $key => $value) {
			$portings[$key] = $value;
		}
		
		// _p($portings);die;
		foreach ($portings as $portingId => $row) {
			$portingFormIds = array();
			$criteria = $row->getPortingCriteria();
			foreach ($criteria as $criteriaRow) {
				$portingFormIds[] = $criteriaRow['value'];
			}
			// _p($portingFormIds);die;
			$responses = $portingmodel->getResponsesByFormIds($lastprocessedId, $row->getLastPortedId(), $portingFormIds, $row->getStatus());
			// _p($responses);die;
			$portingData = array();
			$portingData['responses'] = $responses;
			$portingData['portings'] = array($row->getId() => $row);
			
			$returnData = $porterObj->port($portingData);
			if($returnData['lastprocessedId'] > 0){
				if($row->getStatus() != 'intest'){
					$portingmodel->updatePortingsProcessedId($lastprocessedId, array_keys($returnData['portingIds']));
					$portingmodel->updateResponsesAsProcessed(array_keys($returnData['responseIds']));
				}
				else{
					$portingmodel->changePortingStatus($row->getId(), 'stopped');
				}
			}


			else{
				$portingmodel->updatePortingsRunFirstTime($portingId, 'yes');
			}
		}
		echo 'Porting Done';
	}

    public function startPorting(){
    	$this->validateCron();
    	$this->init();

    	$this->load->library('oafPorting/OAFPortingFactory');
    	$portingLib = $this->load->library('oafPorting/OAFPortingLib');
    	$portingmodel = $this->load->model('oafPorting/oafportingmodel');
    	$porterObj = OAFPortingFactory::getPorterObj();

    	$responses = $portingLib->getOAFResponses();
    	// _p($responses);die;
    	
    	if(!empty($responses)){
    		$formIds = array();
    		$formToPortingsMapping = array();

    		foreach ($responses as $row) {
    			$formIds[$row['formId']] = $row['formId'];
    		}
    		$formIds = array_keys($formIds);
    		$data = $portingmodel->getPortingsByFormIds($formIds);
    		// _p($data);die;
    		foreach ($data as $row) {
    			$portingIds[$row['porting_master_id']] = $row['porting_master_id'];
    		}


    		$portingIds = array_keys($portingIds);
    		$portingRepository = OAFPortingFactory::getPortingRepository();
    		$portings = $portingRepository->getOAFPortings($portingIds);
    		// _p($portings);die;

    		$portingData = array();
    		$portingData['responses'] = $responses;
    		$portingData['portings'] = $portings;

    		$returnData = $porterObj->port($portingData);
    		if($returnData['lastprocessedId'] > 0){
    			$portingmodel->updateLastProcessedId($returnData['lastprocessedId'], 'OAF_RESPONSE_PORTING');
    			$portingmodel->updatePortingsProcessedId($returnData['lastprocessedId'], array_keys($returnData['portingIds']));
    			$portingmodel->updateResponsesAsProcessed(array_keys($returnData['responseIds']));
    		}
    	}
    	echo 'Porting Done';
    }

	function editOAFPorting($portingId)
	{
		$this->_validateUser();
		$data = array();
		$data['headerContentaarray'] = $this->_loadHeaderContent(OAF_PORTING_TAB_ID);
		$data['editedFlow'] =1;
		if(!empty($portingId)) {
			$this->load->config('oafPortingConfig');
    		$customizedFields = $this->config->item('fieldMapping');
    		$data['customized'] = array_keys($customizedFields['customized']);
			$this->load->library('oafPorting/OAFPortingFactory');
			$portingRepository = OAFPortingFactory::getPortingRepository();
			$data['portingData'] = $portingRepository->getPortingMain($portingId);
			$this->load->model('oafPorting/oafportingmodel');
			if(!empty($data['portingData'])) {
				$data['portingId'] = $portingId;
				$data['editPortedValues']     = 1;
				$data['clientFields']         = $this->oafportingmodel->getClientFields($portingId);
				$data['preEditedCount'] = count($data['clientFields']);
				
				$data['portingConditions']    = $this->oafportingmodel->getPortingConditions($portingId);
				$formId = array_keys($data['portingConditions']['oafFormId']);
				$fieldMappings = $portingRepository->getFieldMapping($formId);
				$data['shikshaFields'] = $fieldMappings;
				$data['fieldMappingView'] =	  $this->load->view('oafPorting/oafFieldMapping.php',$data,true);
				$formList = $portingRepository->getFormListByClientId($data['portingData']['client_id']);
				$data['formList'] = $formList;
				$data['checkFormList'] = array_keys($data['portingConditions']['oafFormId']);
				$data['formListView'] =  $this->load->view('oafPorting/oafFormList.php',$data,true);
				$this->load->view('oafPorting/oafAddPorting',$data);
				return true;
			}
		}
		
		header('location:'.ENTERPRISE_HOME);
		exit();
	}

	function updatePorting()
	{
		$this->load->library('oafPorting/OAFPortingFactory');
		$portingRepository = OAFPortingFactory::getPortingRepository();
		$portingRepository->updatePorting($_POST);
		echo 'success';
	}

	public function oafPortingMIS(){

    	$this->_validateuser = $this->checkUserValidation();
        if (empty($this->_validateuser['0']['userid'])) {
            header('location:'.ENTERPRISE_HOME);
            exit();
        }
        $data = array();
        $data['headerContentaarray'] = $this->_loadHeaderContent(OAF_MIS_PORTING_TAB_ID);
        $data['userId'] = $this->_validateuser['0']['userid'];
        $data['usergroup'] = $this->_validateuser['0']['usergroup'];
        $this->load->view('oafPorting/oafPortingMIS',$data);

    }

    function oafPortingMisForm(){
        $this->_validateuser = $this->checkUserValidation();
        if (empty($this->_validateuser['0']['userid'])) {
            header('location:'.ENTERPRISE_HOME);
            exit();
        }

        $data = array();
        $clientid = $this->input->post('clientid');
        if($clientid>0){

            $this->load->model('oafPorting/oafportingmodel');
            $portings = $this->oafportingmodel->getPortingsByClientId($clientid);

            if(!empty($portings)) {

                $data['oaf_porting'] = $portings;

                $data['clientid'] = $clientid;
                $this->load->view('oafPorting/oafPortingMISForm',$data);
            }
        }
    }

    function misData(){

    	$this->_validateuser = $this->checkUserValidation();
    	if (empty($this->_validateuser['0']['userid'])) {
    	    header('location:'.ENTERPRISE_HOME);
    	    exit();
    	}

		$clientId     = $this->input->post('clientid',true);
		$portingIds   = $this->input->post('portings',true);
		$dateFrom     = $this->input->post('timerange_from',true);
		$dateTo       = $this->input->post('timerange_to',true);
		$reportType   = $this->input->post('report_type',true);
		$reportFormat = $this->input->post('report_days',true);


		$this->load->model('oafPorting/oafportingmodel');
        $this->oafportingmodel = new oafportingmodel();

        
        $misData = array();
        if($reportType == 'number'){
        	$misData = $this->getDataByNumber($reportFormat,$portingIds,$dateFrom,$dateTo);

        }
        if($reportType == 'data'){
            foreach($portingIds as $k=>$portingId){
                $misData[$portingId] = $this->oafportingmodel->getMisData($portingId, $dateFrom, $dateTo);
            }

            foreach($misData as $portingId=>$portingData){
                foreach($portingData as $id=>$actualData){
                	// rename response field and remove extra fields
                	$misData[$portingId][$id]['Client API Response'] = $misData[$portingId][$id]['response'];
                	unset($misData[$portingId][$id]['response']);
                	
                    $actualDataSent = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", base64_decode($actualData['sent_data']));
                    $unserDataArr = unserialize($actualDataSent);
                    
                    unset($misData[$portingId][$id]['sent_data']);
                    $this->fieldsToSend($portingId,$unserDataArr,$misData,$id);
                }
            }
        }
        $finalOutput = "";
        foreach($misData as $portingId=>$data){
            $finalOutput.= $this->_createCSV($data);
        }

        
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=Shiksha-OAF-Porting-MIS.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $finalOutput;
    }


    private function _createCSV($misData){
        $data = "";
        $header = "";
        foreach($misData as $row){
            $newHeader = $this->_createHeader($row);
            if($header == "" || $header != $newHeader){
                $data .= $newHeader;
                $header = $newHeader;
            }
            $line = "";
            foreach($row as $key=>$value){
	
		//if(!($key == 'response')){
			if(!isset($value) || $value == ""){
				 $value = ",";
			}else{
				$value = str_replace('"', '""', $value);
				$value = '"' . $value . '"' . ",";
			}
                $line.= $value;
		//}
	    }
            $data .= trim($line)."\n";
        }
        $data = str_replace("\r", "", $data);
        return $data;
    }

    public function getDataByNumber($reportFormat,$portingIds,$dateFrom,$dateTo)
    {
    	$this->load->model('oafPorting/oafportingmodel');
        $this->oafportingmodel = new oafportingmodel();

    	if($reportFormat=='D'){
    		foreach($portingIds as $k=>$portingId){
           $misData[$portingId] = $this->oafportingmodel->getMisCountDataDaily($portingId,$dateFrom,$dateTo);
        	}
    	}
    	elseif($reportFormat=='W'){
    		foreach($portingIds as $k=>$portingId){
           $misData[$portingId] = $this->oafportingmodel->getMisCountDataWeekly($portingId,$dateFrom,$dateTo);
        	}
    	}
    	elseif($reportFormat=='M'){
    		foreach($portingIds as $k=>$portingId){
           $misData[$portingId] = $this->oafportingmodel->getMisCountDataMonthly($portingId,$dateFrom,$dateTo);
        	}
    	}

    	return $misData;
    }

    public function fieldsToSend($portingId,$unserDataArr,&$misData,$id)
    {
    	foreach($unserDataArr as $key=>$value){

        	$misData[$portingId][$id][$key] = $value;
        }
    }

    public function getDetailsForPaymentMode() {

    	$entityIds = $this->config->item('paymentModes');

		$entityData = array();
		foreach ($entityIds as $entityId) {
			$entityData[]['course_id'] = $entityId;
		}

		$data = array();
		$data['entityData'] = $entityData;
		$data['entityIds'] = $entityIds;

		return $data;		

	}

	public function getEntityIdsForClient($clientId,$entityType){
		if (empty($clientId) || empty($entityType)){
			return;
		}
		else{
			$this->config->load('oafPorting/oafPortingConfig');
			switch ($entityType) {
				case 'oaf_paymentmode':
					$data =$this->getDetailsForPaymentMode();
					return array_values($data['entityIds']);
					break;
				case 'oaf_gender':
					$data =$this->getDetailsForCustomizedLayer(array('fieldId'=>4));
					return array_values($data['entityIds']);
					break;
				case 'oaf_course':	
					$clientId =421502;
					$data = $this->getFormList($clientId,true);	
					return array_column($data,'listing_type_id');	
					// $data['']
					break;
				default:
					break;
			}
		}
	}
}	

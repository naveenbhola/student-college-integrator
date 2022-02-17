<?php
//Main class for Online Form Enterprise
class OnlineFormEnterprise extends MX_Controller {

    function init(){
	$this->load->helper(array('form', 'url','date','image','shikshaUtility','utility_helper'));
        $this->load->library(array('OnlineFormEnterprise_client','Online_form_client','Online_form_mail_client'));
	$this->userStatus = $this->checkUserValidation();
    }

    function index(){
        $this->init();
        if($this->userStatus!='false'){
               $usergroup = $this->userStatus[0]['usergroup'];
               if($usergroup == "cms"){
                    //Redirect to the Mapping interface
                    $url='/onlineFormEnterprise/OnlineFormEnterprise/getMappingInterface';
                    header("Location:".$url);
                    exit;
               }
        }
    }


    function enterpriseUserDashBoardForInstitute(){

        if (isset($_REQUEST['institute_id']) && $_REQUEST['institute_id']!='') {
            $_POST['institute_id'] = $_REQUEST['institute_id'];
        }

        $url='/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard/'.$_REQUEST['institute_id'];
        header("Location:".$url);
        exit;
    }    

    function enterpriseUserDashBoard($oaf_institute_id){
	//Check if the Logged in Enterprise user is the owner of the course
	$this->onlinesecurity = new \onlineFormEnterprise\libraries\OnlineFormSecurity();
	if (isset($_REQUEST['courseId']) && $_REQUEST['courseId']!='') {
		if(!$this->onlinesecurity->checkCourse($_REQUEST['courseId'])){
			header("location:/enterprise/Enterprise/disallowedAccess");
        		exit();
		}
	}
	//Check if the Logged in Enterprise user is allowed to view the form details
        /*if (isset($_REQUEST['formId']) && $_REQUEST['formId']!='') {
                if(!$this->onlinesecurity->checkForm($_REQUEST['formId'])){
			header("location:/enterprise/Enterprise/disallowedAccess");
                        exit();
                }
        }*/

        $this->init();
        $onlineFormEnterpriseUserInfo = $this->cmsUserValidation();
    	  $appId =1;
        $userid = $onlineFormEnterpriseUserInfo['userid'];
        $usergroup = $onlineFormEnterpriseUserInfo['usergroup'];
        $thisUrl = $onlineFormEnterpriseUserInfo['thisUrl'];
        $validity = $onlineFormEnterpriseUserInfo['validity'];
        $flagMedia = 1;
		$this->load->model(array('Online/onlineparentmodel','Online/onlinemodel','onlineFormEnterprise/onlineformenterprise_model'));
		$this->load->library('Online/courseLevelManager');
		global $onlineFormsDepartments;
        $enterpriseUserArr['flagMedia'] = $flagMedia;
        $enterpriseUserArr['userid'] = $userid;
        $enterpriseUserArr['usergroup'] = $usergroup;
        $enterpriseUserArr['thisUrl'] = $thisUrl;
        $enterpriseUserArr['validateuser'] = $validity;
        $enterpriseUserArr['headerTabs'] =  $onlineFormEnterpriseUserInfo['headerTabs'];
        $enterpriseUserArr['myProducts'] = $onlineFormEnterpriseUserInfo['myProducts'];
        $enterpriseUserArr['prodId'] = '777';
        $enterpriseUserArr['type'] = 'default';

        if($oaf_institute_id<1){
            $oaf_institute_id = $_REQUEST['institute_id'];
        }

        if($oaf_institute_id<1){
            $oaf_institute_id = 0;
        }

      

        $all_oaf_institute = $this->onlineformenterprise_model->getOnlineFormInstitute($userid);
        foreach ($all_oaf_institute as $oaf_institute) {
            $institute_oaf[$oaf_institute['listing_type_id']] = $oaf_institute['listing_title'];
        }
      
        $ofObj = new OnlineFormEnterprise_client();
        $enterpriseUserInfo= $ofObj->checkOnlineFormEnterpriseTabStatus($userid, $oaf_institute_id);

      
        
        $appId = 12;
        $onlineClient = new Online_form_client();
        $arr = array();
        if(isset( $_REQUEST['searchType']))
            $arr['searchType'] = $_REQUEST['searchType'];
        if(isset( $_REQUEST['searchTextValue']))
            $arr['searchTextValue'] = $_REQUEST['searchTextValue'];
        if(isset( $_REQUEST['from_date_main_first']))
            $arr['from_date_main_first'] = $_REQUEST['from_date_main_first'];
        if(isset( $_REQUEST['from_date_main_second']))
            $arr['from_date_main_second'] = $_REQUEST['from_date_main_second'];
	if(isset( $_REQUEST['sortBy']) && $_REQUEST['sortBy']!=''){
	    $arr['sortBy'] = $_REQUEST['sortBy'];
	    $enterpriseUserArr['sortBy'] = $_REQUEST['sortBy'];
	}
        $moduleName = trim($this->input->post('moduleName'));
        $filter = trim($this->input->post('Filter'));
        $start=isset($_POST['startFrom'])?$this->input->post('startFrom'):'0';
        $rows=isset($_POST['countOffset'])?$this->input->post('countOffset'):'20';
        $ajaxCall = isset($_POST['ajaxCall'])?$this->input->post('ajaxCall'):'false';
        $parameterObj = array('form' => array('offset'=>-1,'totalCount'=>0,'countOffset'=>20));
        if (isset($_REQUEST['courseId']) && $_REQUEST['courseId']!='') {
		$enterpriseUserArr['mapping'] = $this->onlinemodel->getEnterpriseFieldMapping($_REQUEST['courseId']);
	    if(isset($_REQUEST['tab'])){
		if($_REQUEST['tab']=='notawaitedForms')
		    $enterpriseUserArr['tab'] = 'notawaitedForms';
		else
		    $enterpriseUserArr['tab'] = 'awaitedForms';
	    }else{
		$enterpriseUserArr['tab'] = 'notawaitedForms';
	    }
	    $enterpriseUserArr['type'] = 'CourseAndDepartment';
            $instituteInfo = $onlineClient->getFormListForInstitute($appId,$enterpriseUserInfo['instituteId'],$_REQUEST['courseId'],'course',$arr,$start,$rows,$enterpriseUserArr['tab']);//print_r($instituteInfo);die;

            $totalFormNumber = isset($instituteInfo[0][data][0][0][totalFormNumber][0])?$instituteInfo[0][data][0][0][totalFormNumber][0]:0;
            $parameterObj['form']['offset'] = 0;
            $parameterObj['form']['totalCount'] = $totalFormNumber;
            $enterpriseUserArr['parameterObj'] = json_encode($parameterObj);
            $enterpriseUserArr['totalForm'] = $totalFormNumber;
            $enterpriseUserArr['filterSel'] = $filter;
            $enterpriseUserArr['moduleName'] = $moduleName;
            $enterpriseUserArr['startFrom'] = $start;
            $enterpriseUserArr['countOffset'] = $rows;
            $enterpriseUserArr['ajaxCall'] = $ajaxCall;
			$enterpriseUserArr['gdPiName'] = $onlineFormsDepartments[$instituteInfo[0]['data'][0][0]['courseDetails'][0][0]['departmentName']]['gdPiName'];
			$enterpriseUserArr['onlineFormEnterpriseInfo']  = $instituteInfo[0]['data'][0][0];
            $enterpriseUserInfos = $ofObj->getAllAlerts($enterpriseUserArr['onlineFormEnterpriseInfo'],'enterpriseUser');
            $enterpriseUserArr['onlineFormEnterpriseInfo']  = json_decode($enterpriseUserInfos[alerts],true);
            $enterpriseUserArr['courseIdSet'] = $_REQUEST['courseId'];

            $this->load->model('onlineformenterprise_model');
            $getTabCount = ($enterpriseUserArr['tab'] == 'notawaitedForms')?'awaitedForms':'notawaitedForms';
            $enterpriseUserArr['formTabCount'] = $this->onlineformenterprise_model->getFormCountTabWise($_REQUEST['courseId'],$getTabCount);
            $enterpriseUserArr['gdpiInfoCourseWise'] = $this->onlineformenterprise_model->getGDPILocationsWithCourseId();

 	}else if(isset($_REQUEST['departmentId']) && $_REQUEST['departmentId']!=''){
		$this->courselevelmanager->setLevelByDepartmentId($_REQUEST['departmentId']);
	    $enterpriseUserArr['mapping'] = $this->onlinemodel->getEnterpriseFieldMapping($enterpriseUserInfo['courseId']);
	    if(isset($_REQUEST['tab'])){
		if($_REQUEST['tab']=='notawaitedForms')
		    $enterpriseUserArr['tab'] = 'notawaitedForms';
		else
		    $enterpriseUserArr['tab'] = 'awaitedForms';
	    }else{
		$enterpriseUserArr['tab'] = 'notawaitedForms';
	    }
            $enterpriseUserArr['type'] = 'CourseAndDepartment';
            $instituteInfo = $onlineClient->getFormListForInstitute($appId,$enterpriseUserInfo['instituteId'],$_REQUEST['departmentId'],'department',$arr,$start,$rows,$enterpriseUserArr['tab']);
            $totalFormNumber = isset($instituteInfo[0][data][0][0][totalFormNumber][0])?$instituteInfo[0][data][0][0][totalFormNumber][0]:0;
            $parameterObj['form']['offset'] = 0;
            $parameterObj['form']['totalCount'] = $totalFormNumber;
            $enterpriseUserArr['parameterObj'] = json_encode($parameterObj);
            $enterpriseUserArr['totalForm'] = $totalFormNumber;
            $enterpriseUserArr['filterSel'] = $filter;
			$enterpriseUserArr['department'] = $this->courselevelmanager->getCurrentDepartment();
			$enterpriseUserArr['gdPiName'] = $onlineFormsDepartments[$enterpriseUserArr['department']]['gdPiName'];
            $enterpriseUserArr['moduleName'] = $moduleName;
            $enterpriseUserArr['startFrom'] = $start;
            $enterpriseUserArr['countOffset'] = $rows;
            $enterpriseUserArr['ajaxCall'] = $ajaxCall;
            $enterpriseUserArr['onlineFormEnterpriseInfo']  = $instituteInfo[0]['data'][0][0];//print_r($enterpriseUserArr['onlineFormEnterpriseInfo']);//die;
            $enterpriseUserInfos = $ofObj->getAllAlerts($enterpriseUserArr['onlineFormEnterpriseInfo'],'enterpriseUser');
            $enterpriseUserArr['onlineFormEnterpriseInfo']  = json_decode($enterpriseUserInfos[alerts],true);
	    $enterpriseUserArr['onlineFormEnterpriseInfo']  = json_decode($enterpriseUserInfos[alerts],true);
	    $this->load->model('onlineformenterprise_model');
	    $enterpriseUserArr['gdpiInfoCourseWise'] = $this->onlineformenterprise_model->getGDPILocationsWithCourseId();
        $getTabCount = ($enterpriseUserArr['tab'] == 'notawaitedForms')?'awaitedForms':'notawaitedForms';
        $courseId = (isset($enterpriseUserArr['onlineFormEnterpriseInfo']['courseDetails'][0][0]['courseId']))?$enterpriseUserArr['onlineFormEnterpriseInfo']['courseDetails'][0][0]['courseId']:0;
        $enterpriseUserArr['formTabCount'] = $this->onlineformenterprise_model->getFormCountTabWise($courseId,$getTabCount);
        }elseif(isset($_REQUEST['userId']) && isset($_REQUEST['formId']) && !isset($_REQUEST['viewForm']) && !isset($_REQUEST['cId'])){
             $enterpriseUserArr['type'] = 'Alerts';
             $instituteInfo = $onlineClient->getFormForInstitute($appId,$enterpriseUserInfo['instituteId'],$_REQUEST['userId'],$_REQUEST['formId']);
             $enterpriseUserArr['onlineFormEnterpriseInfo']  = $instituteInfo[0]['data'][0][0];
             $enterpriseUserArr['onlineFormEnterprisePaymentInfo'] = $onlineClient->getPaymentDetailsByUserId($_REQUEST['userId'],$_REQUEST['formId']);
             $enterpriseUserInfos = $ofObj->getAllAlerts($enterpriseUserArr['onlineFormEnterpriseInfo'],'All');
			 $enterpriseUserArr['gdPiName'] = $onlineFormsDepartments[$instituteInfo[0]['data'][0][0]['instituteDetails'][0][0]['departmentName']]['gdPiName'];
             $enterpriseUserArr['onlineFormEnterpriseInfo']  = json_decode($enterpriseUserInfos[alerts],true);
	     $enterpriseUserArr['onlineFormEnterpriseInfo']  = json_decode($enterpriseUserInfos[alerts],true);
	    $this->load->model('onlineformenterprise_model');
	    $enterpriseUserArr['gdpiInfoCourseWise'] = $this->onlineformenterprise_model->getGDPILocationsWithCourseId();
        }elseif(isset($_REQUEST['userId']) && isset($_REQUEST['formId']) && isset($_REQUEST['viewForm']) && isset($_REQUEST['cId'])){
             $enterpriseUserArr['type'] = 'viewForm';
             $ResultOfDetails = $onlineClient->getOnlineInstituteInfo($appId,$_REQUEST['cId']);

                    if(isset($ResultOfDetails) && is_array($ResultOfDetails) && isset($ResultOfDetails[0]['instituteInfo'][0]) && isset($ResultOfDetails[0]['instituteInfo'][0]['courseTitle']) ){
                        $data['instituteInfo'] = $ResultOfDetails;
                        if(is_array($ResultOfDetails) && isset($ResultOfDetails[0]['instituteInfo'][0]['courseCode'])){
                                $data['courseCode'] = $ResultOfDetails[0]['instituteInfo'][0]['courseCode'];
                                $data['courseName'] = $ResultOfDetails[0]['instituteInfo'][0]['courseTitle'];
								$enterpriseUserArr['gdPiName'] = $onlineFormsDepartments[$ResultOfDetails[0]['instituteInfo'][0]['departmentName']]['gdPiName'];

                        }
                    }//echo $data['userId'];echo $_REQUEST['cId'];
                    $ResultOfDetails = $onlineClient->getFormCompleteData($appId,$_REQUEST['userId'],$_REQUEST['cId']);

                    global $serverMDPIP;
                    $ResultOfDetails['profileImage'] = SITE_PROTOCOL.$serverMDPIP.$ResultOfDetails['profileImage'];    

		    $enterpriseUserArr['profile_data'] = array();
                    if(is_array($ResultOfDetails) && count($ResultOfDetails)>0){
                      $keyArray = array_keys($ResultOfDetails);
                      foreach($keyArray as $fieldData){
                           $enterpriseUserArr[$fieldData] = $ResultOfDetails[$fieldData];
			   $enterpriseUserArr['profile_data'][$fieldData] = htmlentities($ResultOfDetails[$fieldData],ENT_NOQUOTES);
                      }
                    }
             $instituteInfo = $onlineClient->getFormForInstitute($appId,$enterpriseUserInfo['instituteId'],$_REQUEST['userId'],$_REQUEST['formId']);
             $enterpriseUserArr['instituteInfo'] = $instituteInfo;
             if($instituteInfo[0]['data'][0][0]['instituteDetails'][0][0]['readStatus']=='unread'){
                     $updateFromStatus = $ofObj->updateOnlineFormEnterpriseStatus($enterpriseUserInfo['instituteId'],$_REQUEST['userId'],$_REQUEST['formId']);
             }
                $enterpriseUserArr['onlineFormEnterpriseInfo']  = $instituteInfo[0]['data'][0][0];
             $enterpriseUserArr['instituteSpecId']  = isset($instituteInfo[0]['data'][0][0]['instituteDetails'][0][0]['instituteSpecId'])?$instituteInfo[0]['data'][0][0]['instituteDetails'][0][0]['instituteSpecId']:'';

             $enterpriseUserInfos = $ofObj->getAllAlerts($enterpriseUserArr['onlineFormEnterpriseInfo'],'All');

             //$data['instituteInfo'] = $instituteInfo[0]['data'][0][0];
             $enterpriseUserArr['onlineFormEnterpriseInfo']  = json_decode($enterpriseUserInfos[alerts],true);

             $data = $onlineClient->getFormListForUser($_REQUEST['userId'],$_REQUEST['formId']);
             if(is_array($data) && is_array($data[0])) {
                     $enterpriseUserArr['preferredGDPILocation'] = $data[0]['preferredGDPILocation'];
                     $enterpriseUserArr['gdpiLocation'] = $data[0]['GDPILocation'];
             }

             $gdpiLocations = $onlineClient->getGDPILocations($appId,$_REQUEST['cId']);
             $enterpriseUserArr['gdpiLocations'] = $gdpiLocations;
	     $enterpriseUserArr['onlineFormEnterpriseInfo']  = json_decode($enterpriseUserInfos[alerts],true);
	     $this->load->model('onlineformenterprise_model');
	     $enterpriseUserArr['gdpiInfoCourseWise'] = $this->onlineformenterprise_model->getGDPILocationsWithCourseId();

        }else if(isset($_REQUEST['courseIdFormDatadashBoard']) && $_REQUEST['courseIdFormDatadashBoard']!=''){
	    $enterpriseUserArr['type'] = 'formDatadashBoard';
	}else{
            $instituteInfo = $onlineClient->getFormsForInstitute($appId,$enterpriseUserInfo['instituteId']);
            $enterpriseUserArr['onlineFormEnterpriseInfo']  = $instituteInfo[0]['data'][0][0];
        }
	if(isset($_REQUEST['userId']) && isset($_REQUEST['formId'])){
            $paymentDetails = $onlineClient->getPaymentDetailsByUserId($_REQUEST['userId'],$_REQUEST['formId']);
            if(is_array($paymentDetails)) {
                  $enterpriseUserArr['paymentDetails'] = $paymentDetails[0];
            }
	}

    $enterpriseUserArr['all_oaf_institute'] = $institute_oaf;
    $enterpriseUserArr['oaf_institute_id']  = $oaf_institute_id;

	if($ajaxCall=='false')
	    $this->load->view('onlineFormEnterprise/OF_EnterpriseDashboard', $enterpriseUserArr);
	else
	    $this->load->view('onlineFormEnterprise/show_form_list', $enterpriseUserArr);

    }
    
    function cmsUserValidation() {
        //$validity = $this->checkUserValidation();
        error_log_shiksha("VAlidity: ".$validity);
        if(isset($_REQUEST['loggedInUserId'])){
                        $loggedInUserId = decode($_REQUEST['loggedInUserId']);
        }
        if($loggedInUserId!='')
                 $validity = 'true';
        else
                $validity = $this->checkUserValidation();
        global $logged;
        global $userid;
        global $usergroup;
        $thisUrl = $_SERVER['REQUEST_URI'];
        if(($validity == "false" )||($validity == "")) {
            $logged = "No";
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }else {
            $logged = "Yes";
            //$userid = $validity[0]['userid'];
            //$usergroup = $validity[0]['usergroup'];
            if($loggedInUserId!=''){
            $userid = $loggedInUserId;
            $usergroup = 'enterprise';
            $validity = array();
            $validity[0]['usergroup'] = 'enterprise';
            $validity[0]['userid']= $loggedInUserId;
            }else{
            $userid = $validity[0]['userid'];
            $usergroup = $validity[0]['usergroup'];
            }

            if ($usergroup=="user" || $usergroup == "requestinfouser" || $usergroup == "quicksignupuser" || $usergroup == "tempuser" || $usergroup == "marketingPage"|| $usergroup == "veryshortregistration") {
                header("location:/enterprise/Enterprise/migrateUser");
                exit;
            }
            if( !(($usergroup == "cms")||($usergroup == "enterprise")||($usergroup=="sums")) ){
                header("location:/enterprise/Enterprise/unauthorizedEnt");
                exit();
            }
        }
        $this->load->library('enterprise_client');
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1,$validity[0]['usergroup'],$validity[0]['userid']);
        $this->load->library('sums_product_client');
        $objSumsProduct =  new Sums_Product_client();
        $myProductDetails = $objSumsProduct->getProductsForUser(1,array('userId'=>$userid));
        $returnArr['userid']=$userid;
        $returnArr['usergroup']=$usergroup;
        $returnArr['logged'] = $logged;
        $returnArr['thisUrl'] = $thisUrl;
        $returnArr['validity'] = $validity;
        $returnArr['headerTabs'] = $headerTabs;
        $returnArr['myProducts'] = $myProductDetails;

        return $returnArr;
    }

    function sendAlertFromEnterpriseToUser(){ error_log("sendAlertFromEnterpriseToUser===".print_r($_POST,true)); 
        $this->init();
        $actionType = isset($_POST['actionType'])?$this->input->post('actionType'):'';
        $userAndFormIds = $this->input->post('formanduserIds');
		
		$typeaction = $this->input->post('typeaction');
		if(!empty($typeaction)) {
				$typeaction = "user";
		} else {
				$typeaction = "institute";
		}
		
		if($actionType == '1' && $typeaction != "user")
		{
			$userAndFormData = json_decode($userAndFormIds,TRUE);
			
			$userId = $userAndFormData['information'][0]['userid'];
			$onlineFormId = $userAndFormData['information'][0]['formid'];
			
			$onlineClient = new Online_form_client;
			$paymentDetails = $onlineClient->getPaymentDetailsByUserId($userId,$onlineFormId);
			
			if(is_array($paymentDetails))
			{
				$paymentDetails = $paymentDetails[0];
				$paymentStatus = $paymentDetails['status'];
				$paymentMode = $paymentDetails['mode'];
				
				if($paymentMode == 'Online')
				{
					echo 'payment_mode_online';
					return;
				}
				else if($paymentStatus == 'Success')
				{
					echo 'payment_already_made';
					return;
				}
			}
		}
		
        $instituteId  = $this->input->post('instituteId');
        $calenderDate = isset($_POST['calenderDate'])?$this->input->post('calenderDate'):'';
        $userDraftNumber = isset($_POST['userDraftNumber'])?$this->input->post('userDraftNumber'):'';
        $userDraftDate = isset($_POST['userDraftDate'])?$this->input->post('userDraftDate'):'';
        $userDraftPayeeBank = isset($_POST['userDraftPayeeBank'])?$this->input->post('userDraftPayeeBank'):'';
		$instituteSpecId = isset($_POST['instituteSpecId'])?$this->input->post('instituteSpecId'):'';

	$gdpiId = isset($_POST['gdpiId'])?$this->input->post('gdpiId'):'';
        if($actionType==''){ return 'NoAction';}
        $ofObj = new OnlineFormEnterprise_client();
     
        $result = $ofObj->sendAlertFromEnterpriseToUser($userAndFormIds ,$actionType,$instituteId,$calenderDate,$typeaction,$instituteSpecId);
        $info = json_decode($userAndFormIds,true);
        $userId = $info['information'][0]['userid'];
        $formId = $info['information'][0]['formid'];
        $ofmc = new Online_form_mail_client();
        if($actionType==1){
                $userDraftDateStandard = (strpos($userDraftDate,'-')===false)?getStandardDate($userDraftDate):$userDraftDate;
                $data = array('bankName' => $userDraftPayeeBank ,'draftNumber' => $userDraftNumber,'draftDate' => $userDraftDateStandard);
                $this->load->library('Online/payment/PaymentProcessor');
                $paymentProcessor = new PaymentProcessor();

                $paymentProcessor->updateDraftDetails($typeaction,$formId,$userId,$data);
        }
	if($actionType==7){
	    $ofcObj = new Online_form_client();
            $ofcObj->updateGDPILocation($formId,$userId,$gdpiId);
        }
        if($actionType==7){
            $ofmc->run($userId,$formId,'institute_updates_gdpi_date','both');
        }
        if($actionType==14 || $actionType==15 || $actionType==16){
            $ofmc->run($userId,$formId,'institute_asks_photograph','both');
        }
        if($actionType==10 || $actionType==11 || $actionType==12){
            $ofmc->run($userId,$formId,'institute_asks_documents','both');
        }
        echo $result['result'];
    }

    function showAlertOnDashBoard(){
        
    }

     function getAttachedDocuments(){
                 $this->init();
                 $appId =1;
                 $onlineClient = new Online_form_client();
                 $userId=isset($_POST['userId'])?$this->input->post('userId'):'0';error_log("getAttachedDocuments userid=".print_r($userId,true));
                 $onlineFormId=isset($_POST['onlineFormId'])?$this->input->post('onlineFormId'):'0';error_log("getAttachedDocuments formid=".print_r($onlineFormId,true));
                 $documentsDetails =$onlineClient->getAttachedDocuments($userId,$onlineFormId);error_log("getAttachedDocuments OFDocumentInfo=".print_r($documentsDetails,true));
                  $innerHtml = "";
                foreach($documentsDetails as $document){
                        if($document['doc_type'] == 'pdf') {
                                $class = 'pdfFile';
                                $target = ' target="blank"';
                        } elseif(in_array($document['doc_type'],array('doc','txt','xls','docx'))) {
                                $class = 'docFile';
                        } elseif(in_array(strtolower($document['doc_type']),array('jpeg','gif','png','jpg'))) {
                                $class = 'imgfFile';
                                $target = ' target="blank"';
                        }
                        $fileInfo = explode('/',$document['document_saved_path']);
			$innerHtml = $innerHtml.'<li><span class='.'"'.$class.'"'.'title='.'"'.$document['document_title'].'"'.'>'.$document['document_title'].'</span>'.'<a href="/onlineFormEnterprise/OnlineFormEnterprise/downloadFile/'.$document['documentId'].'" class="downloadDoc" title="Download" id='.'"'.$document['id'].'"'.'>Download</a></li>';
			$class = "";
                }
                $str1 = '<div id="myDocWrapper"><ul>';
                $str2 = '</ul></div><div class="spacer10 clearFix"></div>';
                if(!empty($innerHtml))  {
                        $innerHtml = $str1.$innerHtml.$str2;
                }
                echo $innerHtml;
        }

        function downloadFile($fileId)
		{
			$onlineFormEnterpriseUserInfo = $this->cmsUserValidation();
			$userid = $onlineFormEnterpriseUserInfo['userid'];
			$this->load->library('Online/document/OnlineDocument');
			$document = new OnlineDocument($fileId,$userid);
			if(!$document->download()){
				echo "You don not have access to this document.";
			}
		}

/*************************************/
//Code to generate PDF from HTML Start
/*************************************/
        function createPDFEnterprise($userType,$userId,$formId,$cId ){
            $this->init();
            $loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            createPDF($userType,$userId,$formId,$cId,$loggedInUserId);
        }
/*************************************/
//Code to generate PDF from HTML End
/*************************************/

/***************************************************/
//Code to Download Forms in CSV,XLS,XML Format Start
/***************************************************/
function downloadOnlineForms($type,$courseId,$instituteId){ 
                $this->init();
        	$onlineFormEnterpriseUserInfo = $this->cmsUserValidation();
                $ofObj = new OnlineFormEnterprise_client();
                //$data = $ofObj->getOnlineFormLabelsAndValues($userId,$formId);
                $data = $ofObj->getOnlineFormLabelsAndValues($courseId,$instituteId);//print_r($data);die;
                if($type=='csv'){
                        $filename = date(Ymdhis).'data.csv';
                        $mime = 'text/x-csv';
                        $columnListArray = array();
                        $csv = '';
                       foreach($data[0] as $key=>$value){
                                $csv .= '"'.$key.'",';
                        } 
                        $csv .= "\n";
                        for($i=0;$i<count($data);$i++){
                                foreach ($data[$i] as $key=>$value){
                                        $csv .='"'.str_replace(",","",$value).'",';
                                }
                                $csv .= "\n";
                        }
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=file.csv");
                header("Pragma: no-cache");
                header("Expires: 0");
                print_r($csv);
                }else if($type=='xml'){

                        for($i=0;$i<count($data);$i++){
                                foreach($data[$i] as $key=>$value){
                                        $arr['node'.$i][preg_replace(array('/\s/i','/\//i','/&/','/[_]+/','/[()\.,]+/i','/^10th_/','/^12th_/','/\?/'),array('_','_','_','_','_','Tenth_','Tweleth_',''),$key)] = array(0=>addslashes($value));
                                 }
                        }
                        $array = array("root"=>$arr);
                        $this->load->library('onlineFormEnterprise/XmlDomConstruct');
                        $dom = new XmlDomConstruct('1.0', 'utf-8');
                        $dom->fromMixed($array);
			$dom->formatOutput=true;
                        header("Content-Type:text/xml");
                        echo $dom->saveXML();
                }else{
                        $this->load->library('onlineFormEnterprise/ExportXLS');
                        $filename = date(Ymdhis).'data.xls';
                        foreach($data[0] as $key=>$value){
                                $header[] = $key;
                        } 
                        $xls = new ExportXLS($filename);
                        $xls->addHeader($header);
                        for($i=0;$i<count($data);$i++){
                                foreach ($data[$i] as $key=>$value){
                                        $row[$i][]=$value;
                                }

                        }
                        $xls->addRow($row);
                        $xls->sendFile();
                }
	
		
        }

/***************************************************/
//Code to Download Forms in CSV,XLS,XML Format Start
/***************************************************/
/****************************
Purpose: Function is use to load the mapping interface.
Input: Institute Id and courseId
*****************************/
public function getMappingInterface($instituteId='',$courseId='')
{
    $this->init();
    if($this->userStatus!='false'){
	$usergroup = $this->userStatus[0]['usergroup'];
	if($usergroup == "cms"){
	    $validity = array();
	    $validity = $this->checkUserValidation();
	    $validity[0]['usergroup'] = 'cms';
	    $validity[0]['userid']= $this->userStatus[0]['userid'];
	    $validity[0]['displayname']= $this->userStatus[0]['displayname'];
	    $InstituteInformation['validateuser'] = $validity;
	    //$InstituteInformation['displayname']= $this->userStatus[0]['displayname'];
	    $separatorArray    = array('Comma(,)'=>',','Minus(-)'=>'-','Underscore(_)'=>'_','Space( )'=>'Space');
	    $InstituteInformation['separatorInfo'] = $separatorArray;
	    $this->load->model('onlineformenterprise_model');
	    $instituteNamesAndIds = $this->onlineformenterprise_model->getInstituteForMapping();
	    $InstituteInformation['instituteInfo'] = $instituteNamesAndIds;
	    if($instituteId!='' && $courseId=='')
	    {
		$InstituteInformation['instituteId'] = $instituteId;
	    
		$courseNameAndIds = $this->onlineformenterprise_model->getCourseForMapping($instituteId);
		$InstituteInformation['courseInfo'] = $courseNameAndIds;
	    }
	    elseif($instituteId!='' && $courseId!='')
	    {
		$this->load->library('fieldconfig');
		$InstituteInformation['instituteId'] = $instituteId;
		$InstituteInformation['courseId']    = $courseId;
		
		$courseNameAndIds = $this->onlineformenterprise_model->getCourseForMapping($instituteId);
		$InstituteInformation['courseInfo'] = $courseNameAndIds;
		
		$shikshaFieldIdAndName    = $this->onlineformenterprise_model->getFieldsByCourseId($courseId);
		$InstituteInformation['shikshaFieldInfo'] = $shikshaFieldIdAndName['result'];
		
		$mappingFieldInformation = $this->__getMappingFromDB($courseId);
		$InstituteInformation['mappingFieldInfo'] = $mappingFieldInformation;

               foreach(FieldConfig::$multipleFieldArray as $field){
                       $tempField = new stdClass;
                       $tempField->fieldId = $field;
                       $tempField->name = $field;
                       $InstituteInformation['shikshaFieldInfo'][] = $tempField;
               }
               foreach(FieldConfig::$otherFormFields as $field){
                       $tempField = new stdClass;
                       $tempField->fieldId = $field;
                       $tempField->name = $field;
                       $InstituteInformation['shikshaFieldInfo'][] = $tempField;
               }
               foreach(FieldConfig::$forthFormFields[$courseId] as $field){
                       $tempField = new stdClass;
                       $tempField->fieldId = $field;
                       $tempField->name = $field;
                       $InstituteInformation['shikshaFieldInfo'][] = $tempField;
               }
               usort($InstituteInformation['shikshaFieldInfo'],function($a,$b){
                       return strcasecmp($a->name,$b->name);

               });

		
	    }
	    else
	    {

	    }
	    $this->load->view('onlineFormEnterprise/EnterpriseFieldMapping', $InstituteInformation);
	}else{
	   $this->showLoginPage();
	}
    }else{ 
	$this->showLoginPage();
    }
    
}

/****************************
Purpose: Function is use to show the login page for mapping interface.
*****************************/
public function showLoginPage(){ 
		  $this->init();
		  $appId = 12;
		  $data['validateuser'] = $this->userStatus;			
		  $data['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		  echo $this->load->view('onlineFormEnterprise/showLoginForm', $data);
}

/*************************************
Purpose: Function is use to set Mapping Values.
*************************************/
public function setMappingForEnterprise()
{ 
   $i=0;$j=1;
   $courseId = $this->input->post('courseId');
   $maximumNumberOfEnterpriseFields = $this->input->post('maximumNumberOfEnterpriseFields');
   $maximunNumberOfAddMoreOptions = $this->input->post('maximunNumberOfAddMoreOptions');
	for($i=0;$i<$maximumNumberOfEnterpriseFields;$i++)
	{ 
	   for($j=0;$j<$maximunNumberOfAddMoreOptions;$j++){
		$arr[$i][$j]['EnperpriseFieldId'] = $this->input->post('EnterpriseFieldId_'.$i);
		$arr[$i][$j]['orderOfFieldId'] = $this->input->post('orderOfFieldId'.$i.'_'.$j);
		$arr[$i][$j]['orderOfEnterpriseFieldId'] = $this->input->post('orderOfEnterpriseFieldId_'.$i);
		$arr[$i][$j]['EnterpriseField'] = $this->input->post('EnterpriseField_'.$i);
		$arr[$i][$j]['ShikshaField'] = $this->input->post('ShikshaField'.$i.'_'.$j);
		$arr[$i][$j]['Seperator'] = $this->input->post('Seperator'.$i.'_'.$j);
		$arr[$i][$j]['typeOfField'] = $this->input->post('typeOfField_'.$i);
	   }
	} 
    $this->load->model('onlineformenterprise_model');
    $res = $this->onlineformenterprise_model->insertMapping($arr,$courseId);
}

/*************************************
Purpose: Function is use to get Mapping Values.
Input: courseId
*************************************/
private function __getMappingFromDB($courseId)
{
    $this->load->model('onlineformenterprise_model');
    $mappingElements = $this->onlineformenterprise_model->getMappingInfo($courseId);

    if(!empty($mappingElements))
    {
	$i=0;
	foreach($mappingElements as $key=>$value){
	    if($enterpriseFieldName!='' && $enterpriseFieldName==$value['enterprisefield'])
	    {
		$i--;
		$count = $i;
		$mappingFieldArray['ShikshaField'.$i.'_'.$j]  	  = $value['shikshafieldid'];
		$mappingFieldArray['Seperator'.$i.'_'.$j]   	  = $value['seperator'];
		$mappingFieldArray['EnterpriseFieldId_'.$i] = $value['enterprisefieldId'];
		$mappingFieldArray['OrderOfEnterpriseField_'.$i] = $value['orderOfEnterpriseField'];
		$mappingFieldArray['OrderOfShikshaField'.$i.'_'.$j] = $value['orderOfShikshaField'];
		$mappingFieldArray['shikshaFieldName'.$i.'_'.$j] = $value['shikshaFieldName'];
		$mappingFieldArray['typeOfField_'.$i] = $value['typeOfField'];
	    }else
	    {
		$j=0;
		$mappingFieldArray['EnterpriseField_'.$i] 	  = $value['enterprisefield'];
		$mappingFieldArray['ShikshaField'.$i.'_'.$j]  	  = $value['shikshafieldid'];
		$mappingFieldArray['Seperator'.$i.'_'.$j]         = $value['seperator'];
		$mappingFieldArray['EnterpriseFieldId_'.$i] = $value['enterprisefieldId'];
		$mappingFieldArray['OrderOfEnterpriseField_'.$i] = $value['orderOfEnterpriseField'];
		$mappingFieldArray['OrderOfShikshaField'.$i.'_'.$j] = $value['orderOfShikshaField'];
		$mappingFieldArray['shikshaFieldName'.$i.'_'.$j] = $value['shikshaFieldName'];
		$mappingFieldArray['typeOfField_'.$i] = $value['typeOfField'];
		$enterpriseFieldName = $value['enterprisefield'];
	    }
	    $i++;$j++;
	}
    }
    return $mappingFieldArray;
}

/*************************************
Purpose: Function is use to Get Draft Details.
*************************************/
public function getDraftDetails(){
	$onlineFormId = isset($_POST['onlineFormId'])?$this->input->post('onlineFormId'):'0';
	if($onlineFormId>0){
	    $this->load->model('onlineformenterprise_model');
	    $draftDetails = $this->onlineformenterprise_model->getDraftDetails($onlineFormId);
	    echo json_encode($draftDetails);
	}else{
		echo 'No Results';
	}
	
}

/*************************************
Purpose: Function is use to Delete Mapping.
*************************************/
public function deteleShikshaFieldFromMapping(){
    $enterpriseFieldId = isset($_POST['EnterpriseFieldId'])?$this->input->post('EnterpriseFieldId'):'0';
    $orderOfShikshaField = isset($_POST['orderOfShikshaField'])?$this->input->post('orderOfShikshaField'):'0';
    $shikshaFieldNumber = isset($_POST['shikshaFieldNumber'])?$this->input->post('shikshaFieldNumber'):'';
    $shikshaFieldName = isset($_POST['shikshaFieldName'])?$this->input->post('shikshaFieldName'):'';
    $this->load->model('onlineformenterprise_model');
    $mappingElements = $this->onlineformenterprise_model->deteleShikshaFieldFromMapping($enterpriseFieldId,$orderOfShikshaField,$shikshaFieldNumber,$shikshaFieldName);
}

function getDetailEnterpriseField($courseId){
    $this->load->model('onlineformenterprise_model');
    $detailOfEnterpriseField = $this->onlineformenterprise_model->getDetailEnterpriseField($courseId);
    return $detailOfEnterpriseField;
}
/*************************************
Purpose: Function is use to upload external forms
*************************************/
public function uploadExternalOnlineForm(){
    $this->init();
    $this->load->model('onlineformenterprise_model');
    $userId = $this->userStatus[0]['userid'];
    $courseId = $this->input->post('courseId');
    $detailOfEnterpriseField = $this->getDetailEnterpriseField($courseId);
    
    unlink("/tmp/Course$courseId.txt");
    $this->onlineformenterprise_model->writeInFile('{"'.$courseId.'":{"externalFormCount":"","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":""}}',$courseId);
    if($_FILES['datafile']['tmp_name'][0] == '')
    {
	$this->onlineformenterprise_model->writeInFile('{"'.$courseId.'":{"externalFormCount":"","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"","ERRORMSG":"UPLOAD_ERROR"}}',$courseId);
	echo "<div class='errorMsg'>Please select a document to upload</div>";
    }
    else
    {
	
	$sourceName = $this->input->post('docTitle');
	if($sourceName=='Source Name' || $sourceName==''){
	    $this->onlineformenterprise_model->writeInFile('{"'.$courseId.'":{"externalFormCount":"","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"","ERRORMSG":"SOURCE_ERROR"}}',$courseId);
	    echo "<div class='errorMsg'>Please enter the Source Name</div>";
	    return;
	}else{
	    if(preg_match('![^a-z0-9\s]!i', $sourceName)){
		$this->onlineformenterprise_model->writeInFile('{"'.$courseId.'":{"externalFormCount":"","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"","ERRORMSG":"SPECIAL_CHARACTERS_ERROR"}}',$courseId);
		echo "<div class='errorMsg'>Special Characters Not Allowed.</div>";
		return;
	    }
	}
	$mappingElements = $this->onlineformenterprise_model->getHeading($courseId);
	$mappingElementsCount = count($mappingElements);
	if($mappingElements[0]['fieldName']=='NoResult' || $mappingElements[0]['entFieldId']=='NoResult'){
	    $this->onlineformenterprise_model->writeInFile('{"'.$courseId.'":{"externalFormCount":"","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"","ERRORMSG":"MAAPPING_ERROR"}}',$courseId);
	    echo "<div class='errorMsg'>No Mapping for this course</div>";
	    return;
	}

	$type_doc = $_FILES['datafile']['type']['0'];
	
	$status = $this->__checkDocumenttypeForExternalForms($type_doc);
	
	if($status == 0){
	    $this->onlineformenterprise_model->writeInFile('{"'.$courseId.'":{"externalFormCount":"","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"","ERRORMSG":"FORMAT_ERROR"}}',$courseId);
	    echo "<div class='errorMsg'>Only xls files are allowed.</div>";
	    return;
	}
	$maxAllowedFileSize = MAX_ALLOWED_SIZE_FOR_IMPORT_EXTERNAL_FORM;
	
	$targetPath = '/tmp/myfile.csv';
	if($_FILES['datafile']['size'][0] > $maxAllowedFileSize){
	    $this->onlineformenterprise_model->writeInFile('{"'.$courseId.'":{"externalFormCount":"","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"","ERRORMSG":"SIZE_ERROR"}}',$courseId);
	    echo "<div class='errorMsg'>Size is greater than 5MB</div>";
	    return;
	}
	move_uploaded_file($_FILES["datafile"]["tmp_name"][0], $targetPath);
	$this->__importData($targetPath,$mappingElementsCount,$mappingElements,$type_doc,$courseId,$sourceName,$userId,$detailOfEnterpriseField);
	
    }
}

/*************************************
Purpose: Function is use to Import Data.
Input: fileName,mappingElementsCount,mappingElements,type_doc,courseId,sourceName,userId
*************************************/
private function __importData($fileName,$mappingElementsCount,$mappingElements,$type_doc,$courseId,$sourceName,$userId,$detailOfEnterpriseField){
    //if($type_doc == "application/vnd.ms-excel"){
	$this->load->library('common/reader');
	$this->load->library('common/PHPExcel/IOFactory');
	//ini_set('memory_limit','400M');
	
	$inputFileName = $fileName;
	$inputFileType = PHPExcel_IOFactory::identify($inputFileName);  
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);  
	$objReader->setReadDataOnly(true);  
	/**  Load $inputFileName to a PHPExcel Object  **/  
	$objPHPExcel = $objReader->load($inputFileName);  
	
	$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
	$highestRow = $objWorksheet->getHighestRow();
	$highestColumn = $objWorksheet->getHighestColumn();
	
	$headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
	$headingsArray = $headingsArray[1];
	
	$r = -1;
	$namedDataArray = array();
	$date_format='d-m-Y';
	foreach($detailOfEnterpriseField as $res){
		$headingNames[] = $res['fieldName'];
	}
	
	$j=0;
	for ($row = 1; $row <= 1; ++$row) {
	    $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
	   
	    if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
		++$r;
		$j=0;
		foreach($headingsArray as $columnKey => $columnHeading) {
		    $namedDataArray[$r][$j] = $dataRow[$row][$columnKey];
		    if(in_array($namedDataArray[$r][$j],$headingNames)){
			$positionOfDateInExcel[] = $j;
		    }
		    $j++;
		}
	    }
	    $countOfElementsInFirstField = $j;
	}
	$externalElementsHeadingArray = $namedDataArray[0];

	if($countOfElementsInFirstField==0  ){
	    $this->onlineformenterprise_model->writeInFile('{"'.$courseId.'":{"externalFormCount":"","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"","ERRORMSG":"ZERO_RESULT_ERROR"}}',$courseId);
	    echo "<span class='errorMsg'><strong>0 records imported. Import file is blank</strong></span>";
	    exit;
	}
	if($mappingElementsCount!=$countOfElementsInFirstField){
	    $this->onlineformenterprise_model->writeInFile('{"'.$courseId.'":{"externalFormCount":"","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"","ERRORMSG":"COUNT_MATCHING_ERROR"}}',$courseId);
	    echo "<div class='errorMsg'>Field Count is not Matching.</div>";
	    echo "<div class='errorMsg'>Mapping tool contains $mappingElementsCount Mapping ".(($mappingElementsCount>1 || $mappingElementsCount!=0)?'fields.':'field.')."</div>";
	    echo "<div class='errorMsg'>File contains $countOfElementsInFirstField ".(($countOfElementsInFirstField>1 || $countOfElementsInFirstField!=0)?'headings.':'heading.')."</div>";
	    exit;
	}    
	
	
    
	$flag =  'true';
	$k=0;
	for($i=0;$i<$mappingElementsCount;$i++){
	    if(trim(strtolower($mappingElements[$i]['fieldName']))!=trim(strtolower($externalElementsHeadingArray[$i]))){
		$this->onlineformenterprise_model->writeInFile('{"'.$courseId.'":{"externalFormCount":"","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"","ERRORMSG":"VALUE_MATCHING_ERROR"}}',$courseId);
		$errorMsg[$k] = "<span class='errorMsg'><strong>".$externalElementsHeadingArray[$i]."</strong> should be <strong>".$mappingElements[$i][fieldName]."</strong> at position <strong>$i</strong></span>";
		$k++;
		$flag =  'false';
	    }
	}
	if($flag =='false'){
		$str='';
		for($i=0;$i<count($errorMsg);$i++){
		    $str .= $errorMsg[$i].'<br/>';
		}
		echo $str;
		return;
	}else{
	        $orderArray = $this->onlineformenterprise_model->getOrderOfEmailIdFromMapping($courseId);
$OrderOfEmailId = $orderArray['emailIdOrder'];
$entFieldid = $orderArray['entFieldid'];
		$r = -1;
		

		$colIndex = PHPExcel_Cell::stringFromColumnIndex($OrderOfEmailId-1);
		$numberOfRowsNotContainEmailId=0;
		for ($row = 2; $row <= $highestRow; ++$row) {
		    $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
		   
		    if ((isset($dataRow[$row][$colIndex])) && ($dataRow[$row][$colIndex] > '')) {
			++$r;
			$j=0;
			foreach($headingsArray as $columnKey => $columnHeading) {
			   if(in_array($j,$positionOfDateInExcel)){
			    $dataArray[$r][$j] = $dataRow[$row][$columnKey];
				 $dataArray[$r][$j] = PHPExcel_Style_NumberFormat::toFormattedString($dataRow[$row][$columnKey], "M/D/YYYY");
			    }else{
				$dataArray[$r][$j] = $dataRow[$row][$columnKey];
			    }
			    $j++;
			}
		    }else{
			$numberOfRowsNotContainEmailId++;
		    }
		}
		
		if(empty($dataArray[0]) && $numberOfRowsNotContainEmailId==0){
		    $this->onlineformenterprise_model->writeInFile('{"'.$courseId.'":{"externalFormCount":"","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"","ERRORMSG":"ZERO_RESULT_ERROR"}}',$courseId);
		    echo "<span class='errorMsg'><strong>0 records imported. Import file is blank</strong></span>";
		}else{
		    $maximumCountForForms = count($dataArray);
		    $maximumCountForFormsData = $maximumCountForForms*$countOfElementsInFirstField;
		    $res = $this->onlineformenterprise_model->insertExternalOnlineFormInformationIntoDatabase($courseId,$dataArray,$sourceName,$mappingElements,$userId,$type_doc,$maximumCountForForms,$maximumCountForFormsData,$fileName,$numberOfRowsNotContainEmailId,$entFieldid,$OrderOfEmailId);
		}
	}
	
   // }
    


}

/*************************************
Purpose: Function is use to check Document type For ExternalForms.
Input: type
*************************************/
private function __checkDocumenttypeForExternalForms($type){
    $xlsType = array("application/vnd.ms-excel","application/msexcel","application/x-msexcel","application/x-ms-excel","application/x-excel","application/x-dos_ms_excel","application/xls","application/x-xls","application/octet-stream","application/x-zip-compressed");
    //if(!($type== "application/vnd.ms-excel" || $type == "text/csv"))
    if(!in_array($type,$xlsType))
	return  0;
    else
	return 1;
}

function checkProgressBarStatus(){ 
	    $courseId = trim($this->input->post('courseId'));
	    $fileName = trim($this->input->post('fileName'));
	    $this->load->model('onlineformenterprise_model');
	    $result = json_decode($this->readFromFile($courseId),true);
	    $separateChar = "#";
	    
	    if(isset($result[$courseId]['maximumNumberOfForms'])) $maximumNumberOfForms = $result[$courseId]['maximumNumberOfForms'];else $maximumNumberOfForms='';
	    if(isset($result[$courseId]['externalFormCountlastUpdatedAt'])) $formLastUpdatedOn = strtotime($result[$courseId]['externalFormCountlastUpdatedAt']);else $formLastUpdatedOn='';
	    if(isset($result[$courseId]['externalFormCountOfData'])) $externalFormCountOfData = $result[$courseId]['externalFormCountOfData'];else $fexternalFormCountOfData='';
	    if(isset($result[$courseId]['externalFormCount'])) $externalFormCount = $result[$courseId]['externalFormCount'];else $externalFormCount='';
	
	    if(isset($result[$courseId]['ERRORMSG'])){
		if($result[$courseId]['ERRORMSG']=='SUCCESSFULLY_MADE_LIVE' || $result[$courseId]['ERRORMSG']=='ALL_DRAFTED_SUCCESSFULLY'){
		    echo $response = "1".$separateChar.$result[$courseId]['ERRORMSG'];
		}else if($result[$courseId]['ERRORMSG']=='SUCCESSFULLY_MADE_LIVE_DUPLICATE' || $result[$courseId]['ERRORMSG']=='SUCCESSFULLY_MADE_LIVE_DUPLICATE_WITH_EMAIL_BLANK' || $result[$courseId]['ERRORMSG']=='SUCCESSFULLY_MADE_LIVE_WITH_EMAIL_BLANK'){
		    echo $response = "1".$separateChar.$result[$courseId]['ERRORMSG'].$separateChar.$result[$courseId]['numberOfRowsNotContainEmailId'];
		}elseif($result[$courseId]['ERRORMSG']=='PROBLEM_WHILE_UPLOADING'){
		    echo $response = "3".$separateChar.$result[$courseId]['ERRORMSG'];
		}else{
		    echo $ERRORMSG = "2".$separateChar.$result[$courseId]['ERRORMSG'];
		}
		exit;
	    }
	    $currentTime = strtotime(date('Y-m-d H:i:s'));
	   
	    if(($currentTime-$formLastUpdatedOn) > 120 && $formLastUpdatedOn!=''){
		echo $response = "0".$separateChar."FORM_UPLOAD_ISSUE";
		exit;
	    }
	    else{
		$response = "1".$separateChar;
	    }

	    if(!empty($result[$courseId])){
		$percentage = intval(($externalFormCount /$maximumNumberOfForms)*100);
		if($percentage=='100'){
		    $response .= 'ALL_DRAFTED_SUCCESSFULLY';		    
		}else{
		    $response .= $percentage;
		}
	    }else{
		$response = "0".$separateChar."FILE_DATA_NOT_FOUND";
	    }
	    echo $response;
	    exit;
	}
	
	function readFromFile($courseId){
	    $myFile = "/tmp/Course$courseId.txt";
	    $fh = fopen($myFile, 'r');
	    $theData = fread($fh, filesize($myFile));
	    fclose($fh);
	    return $theData;
	}
	
	
	function viewDuplicateEnteries($courseId){
	    $this->init();
	    if($this->userStatus!='false'){
	    $usergroup = $this->userStatus[0]['usergroup'];
	    $result = json_decode($this->readFromFile($courseId),true);
	    if($result[$courseId]['EMAIL']!=''){
		$result['email'] = explode(",",base64_decode($result[$courseId]['EMAIL']));
	    }else{
		$result['email'] = '';
	    }
	    $this->load->view('onlineFormEnterprise/duplicateEmails', $result);
	    }
	}
	
	function deleteDuplicateEntries(){
	    $courseId=isset($_POST['courseId'])?$this->input->post('courseId'):'';
	    if($courseId!=''){
		$result = json_decode($this->readFromFile($courseId),true);
		$FORMIDS = $result[$courseId]['FORMIDS'];
		$formIdsArray = explode(',',$FORMIDS);
		$this->load->model('onlineformenterprise_model');
		echo $this->onlineformenterprise_model->deleteDuplicateEntries($formIdsArray,$courseId);
	    }
	}

	public function updateOnlineFormInformation($instituteId='')
	{
	    $this->init();
	    if($this->userStatus!='false'){
		$usergroup = $this->userStatus[0]['usergroup'];
		if($usergroup == "cms"){
		    if(isset($_POST['instituteId'])){
			$instituteId = $this->input->post('instituteId');
		    }
		    $InstituteInformation['tab'] = (isset($_REQUEST['tab']))?$_REQUEST['tab']:'internal';
		    $validity = array();
		    $validity = $this->checkUserValidation();
		    $validity[0]['usergroup'] = 'cms';
		    $validity[0]['userid']= $this->userStatus[0]['userid'];
		    $validity[0]['displayname']= $this->userStatus[0]['displayname'];
		    $InstituteInformation['validateuser'] = $validity;
		    $this->load->model('onlineformenterprise_model');
		    $instituteNamesAndIds = $this->onlineformenterprise_model->getInstituteForMapping(true);
		    $InstituteInformation['instituteInfo'] = $instituteNamesAndIds;
		    $InstituteInformation['instituteId'] = $instituteId;
		    if(isset($_POST['fees']) && $_POST['fees']!='' && $_POST['last_date']!='' && $_POST['instituteEmailId']!='' && $_POST['instituteMobileNo']!=''){
			if($_POST['externalURL']=='NULL' || $_POST['externalURL']==''){
			    unset($_POST['externalURL']);
			}
			$this->onlineformenterprise_model->setInstituteBasicInfo($_POST);
		    }
		    if($instituteId!=''){
			$InstituteInformation['instituteBasicInfo'] = $this->onlineformenterprise_model->getInstituteBasicInfo($instituteId);
		    }
		    $this->load->view('onlineFormEnterprise/updateOnlineFormInfo', $InstituteInformation);
		}else{
		   $this->showLoginPage();
		}
	    }else{ 
		$this->showLoginPage();
	    }
	    
	}

    function getAttachedDocumentsInForm()
    {
        $this->init();
        $appId =1;

        $this->load->model('Online/onlineparentmodel');
        $documentModel = $this->load->model('Online/documentmodel');

        $userId=isset($_POST['userId'])?$this->input->post('userId'):'0';

        $onlineFormId=isset($_POST['onlineFormId'])?$this->input->post('onlineFormId'):'0';

        $documentsDetails = $documentModel->getAttachedDocumentsInForm($userId,$onlineFormId);

        foreach ($documentsDetails as $key => $value) 
        {
            if($documentsDetails[$key]['document_title']=='profileImage')
            {
                unset($documentsDetails[$key]);
            }
            else
            {
                $documentsDetails[$key]['doc_type'] = end(explode('.',$documentsDetails[$key]['document_saved_path']));
            }
        }

        $innerHtml = "";

        foreach($documentsDetails as $document)
        {
            if($document['doc_type'] == 'pdf') 
            {
                $class = 'pdfFile';
                $target = ' target="blank"';
            } 
            elseif(in_array($document['doc_type'],array('doc','txt','xls','docx'))) 
            {
                $class = 'docFile';
            }
            elseif(in_array(strtolower($document['doc_type']),array('jpeg','gif','png','jpg'))) 
            {
                $class = 'imgfFile';
                $target = ' target="blank"';
            }
            $fileInfo = explode('/',$document['document_saved_path']);
            $innerHtml = $innerHtml.'<li><span class='.'"'.$class.'"'.'title='.'"'.$document['document_title'].'"'.'>'.$document['document_title'].'</span>'.'<a href="/onlineFormEnterprise/OnlineFormEnterprise/downloadFileForForm/'.$onlineFormId."/".$userId."/".$document['document_title'].'" class="downloadDoc" title="Download" id='.'"'.$document['document_saved_path'].'"'.'>Download</a></li>';
            $class = "";
        }

        $str1 = '<div id="myDocWrapper"><ul>';
        $str2 = '</ul></div><div class="spacer10 clearFix"></div>';
        if(!empty($innerHtml))  {
            $innerHtml = $str1.$innerHtml.$str2;
        }
        echo $innerHtml;
    }

    public function downloadFileForForm($onlineFormId,$userId,$document_title)
    {
       $this->load->library('Online/document/OnlineDocumentInForm');
        $document = new OnlineDocumentInForm($onlineFormId,$userId,$document_title);
        if(!$document->download()){
            echo "You don not have access to this document.";
        }
    }

}
?>

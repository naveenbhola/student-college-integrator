<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 8/10/18
 * Time: 12:36 PM
 */

class ClientActivation extends MX_Controller{
    private $usergroupAllowed;
    private $salesFields;
    private $counsellorTLFields;
    private $saAdminFields;
    private $auditorAndRmsField;
    //private $allowedFormField
    public function __construct()
    {
        parent::__construct();
        $this->clientActivationLib = $this->load->library('dashboard/clientActivationLib');
        $this->saCMSToolsLib= $this->load->library('saCMSTools/SACMSToolsLib');
        $this->config->load('studyAbroadCMSConfig');

        $this->salesFields = array(
            'fields'=>array(
                'university_id'=>array('univId','int'),
                'paid_courses'=>array('paidCourses','json'),
                'sales_rep'=>array('salesRep','varchar'),
                'type_of_account'=>array('typeOfAccount','varchar'),
                'client_details'=>array('clientDetails','text'),
                'prepaid_collection'=>array('prepaidCollection','int'),
                'postpaid_billing'=>array('postpaidBilling','int'),
                'gst_flag'=>array('clientWillForGST','varchar'),
                'commission_per_enrollment'=>array('commissionPerEnrollment','text'),
                'census_date_description'=>array('censusDateDesc','text'),
                'invoice_date_description'=>array('invoiceDateDesc','text'),
                'client_status'=>array('clientStatus','varchar'),
                'client_renewed'=>array('clientRenewed','varchar'),
                'client_renewal_remarks'=>array('clientRenewalRemarks','text')
            ),
            'disabled'=>array(
            )
        );

        $this->counsellorTLFields = array(
            'fields'=>array(
                'university_id'=>array('univIdHidden','int'),
                'paid_courses'=>array('paidCourses','json'),
                'sales_rep'=>array('salesRep','varchar'),
                'counselling_rep'=>array('counsellorRep','varchar'),
                'type_of_account'=>array('typeOfAccount','varchar'),
                'client_details'=>array('clientDetails','varchar'),
                'counselling_start_date'=>array('counsellingStartDate','date'),
                'counselling_end_date'=>array('counsellingEndDate','date'),
                'counsellor_training_status'=>array('counsellorTrainingStatus','varchar'),
                'counsellor_training_date'=>array('counsellorTrainingDate','date'),
                'total_commitment'=>array(array('totalCommitment','commitmentIntakeYear','commitmentIntakeMonth','commitmentBachelorsInp','commitmentMastersInp','countTotalCommitment'),'multiple'),
                'min_needed_for_renewal'=>array('minNeededForRenewal','int'),
                'visa_delivered'=>array('visaCountDeliveredByEndDate','int'),
                'counselling_remarks'=>array('counsellorRemarks','text')
            ),
            'disabled'=>array(
                'university_id','paid_courses','sales_rep','type_of_account','client_details'
            )
        );

        $this->viewOnlyFields = array(
            'fields'=>array(
                'university_id'=>array('univId','int'),
                'paid_courses'=>array('paidCourses','json'),
                'sales_rep'=>array('salesRep','varchar'),
                'counselling_rep'=>array('counsellorRep','varchar'),
                'type_of_account'=>array('typeOfAccount','varchar'),
                'client_details'=>array('clientDetails','text'),
                'client_status'=>array('clientStatus','varchar'),
                'client_renewed'=>array('clientRenewed','varchar'),
                'counselling_start_date'=>array('counsellingStartDate','date'),
                'counselling_end_date'=>array('counsellingEndDate','date'),
                'counsellor_training_status'=>array('counsellorTrainingStatus','varchar'),
                'counsellor_training_date'=>array('counsellorTrainingDate','date'),
                'total_commitment'=>array(array('totalCommitment','commitmentIntakeYear','commitmentIntakeMonth','commitmentBachelorsInp','commitmentMastersInp','countTotalCommitment'),'multiple'),
                'min_needed_for_renewal'=>array('minNeededForRenewal','int'),
                'visa_delivered'=>array('visaCountDeliveredByEndDate','int'),
            ),
            'disabled'=>array(
                'university_id','paid_courses','sales_rep','counselling_rep','type_of_account','client_details','client_status','client_renewed','counselling_start_date','counselling_end_date','counsellor_training_status','counsellor_training_date','total_commitment','min_needed_for_renewal','visa_delivered'
            )
        );

        $this->saAdminFields = array(
            'fields'=>array(
                'university_id'=>array('univId','int'),
                'paid_courses'=>array('paidCourses','json'),
                'sales_rep'=>array('salesRep','varchar'),
                'counselling_rep'=>array('counsellorRep','varchar'),
                'type_of_account'=>array('typeOfAccount','varchar'),
                'client_details'=>array('clientDetails','text'),
                'prepaid_collection'=>array('prepaidCollection','int'),
                'postpaid_billing'=>array('postpaidBilling','int'),
                'gst_flag'=>array('clientWillForGST','varchar'),
                'commission_per_enrollment'=>array('commissionPerEnrollment','text'),
                'census_date_description'=>array('censusDateDesc','text'),
                'invoice_date_description'=>array('invoiceDateDesc','text'),
                'client_status'=>array('clientStatus','varchar'),
                'client_renewed'=>array('clientRenewed','varchar'),
                'client_renewal_remarks'=>array('clientRenewalRemarks','text'),
                'counselling_start_date'=>array('counsellingStartDate','date'),
                'counselling_end_date'=>array('counsellingEndDate','date'),
                'counsellor_training_status'=>array('counsellorTrainingStatus','varchar'),
                'counsellor_training_date'=>array('counsellorTrainingDate','date'),
                'total_commitment'=>array(array('totalCommitment','commitmentIntakeYear','commitmentIntakeMonth','commitmentBachelorsInp','commitmentMastersInp','countTotalCommitment'),'multiple'),
                'min_needed_for_renewal'=>array('minNeededForRenewal','int'),
                'visa_delivered'=>array('visaCountDeliveredByEndDate','int'),
                'counselling_remarks'=>array('counsellorRemarks','text')
            ),
            'disabled'=>array(

            )
        );

        $this->auditorAndRmsField = array(
            'fields'=>array(
                'university_id'=>array('univIdHidden','int'),
                'paid_courses'=>array('paidCourses','json'),
                'sales_rep'=>array('salesRep','varchar'),
                'counselling_rep'=>array('counsellorRep','varchar'),
                'type_of_account'=>array('typeOfAccount','varchar'),
                'client_details'=>array('clientDetails','text'),
                'client_status'=>array('clientStatus','varchar'),
                'client_renewed'=>array('clientRenewed','varchar'),
                'client_renewal_remarks'=>array('clientRenewalRemarks','text'),
                'counselling_start_date'=>array('counsellingStartDate','date'),
                'counselling_end_date'=>array('counsellingEndDate','date'),
                'counsellor_training_status'=>array('counsellorTrainingStatus','varchar'),
                'counsellor_training_date'=>array('counsellorTrainingDate','date'),
                'total_commitment'=>array(array('totalCommitment','commitmentIntakeYear','commitmentIntakeMonth','commitmentBachelorsInp','commitmentMastersInp','countTotalCommitment'),'multiple'),
                'min_needed_for_renewal'=>array('minNeededForRenewal','int'),
                'visa_delivered'=>array('visaCountDeliveredByEndDate','int'),
                'counselling_remarks'=>array('counsellorRemarks','text')
            ),
            'disabled'=>array(
                'university_id','paid_courses','sales_rep','type_of_account','client_details'
            )
        );




    }

    public function index(){
        $this->viewClientActivation();
    }

    public function viewClientActivation(){
        $this->usergroupAllowed = array('saAdmin','saSales','saShikshaApply','saRMS','saCMS','saContent','saCMSLead','saAuditor','saCustomerDelivery');
        $displayData = $this->dashboardAbroadUserValidation();
        // get post parameters
        //$searchDeptName = $this->input->get("q");
        $resultPerPage  = $this->input->get("resultPerPage");
        // data massaging
        //$searchDeptName = ($searchDeptName == "Search Department") ? "" : $searchDeptName;
        $resultPerPage  = ($resultPerPage) ? $resultPerPage : "";

        // prepare the query parameters coming
        $queryParams    = "1";
        $queryParams   .= ($resultPerPage  ? "&resultPerPage=".$resultPerPage : "");
        $queryParams    = $queryParams     ? "?".$queryParams : "";

        // prepare the URL for view as well as for paginator
        $URL        = ENT_SA_CMS_CLIENT_ACTIVATION_PATH.ENT_SA_VIEW_LISTING_CLIENT_ACTIVATION;
        $URLPagination  = ENT_SA_CMS_CLIENT_ACTIVATION_PATH.ENT_SA_VIEW_LISTING_CLIENT_ACTIVATION."/".($queryParams ? $queryParams : "");

        // initialize the paginator instance
        $this->load->library('listingPosting/Paginator');
        $displayData['paginator']     = new Paginator($URLPagination);

        // fetch the universities data
        $result = $this->clientActivationLib->getClientActivationTableData($displayData['paginator']);

        $displayData['paginator']->setTotalRowCount($result['totalCount']);
        //_p($displayData['paginator']); die;

        $displayData['formName']             = ENT_SA_VIEW_LISTING_CLIENT_ACTIVATION;
        $displayData['selectLeftNav']        = "CLIENT_ACTIVATION";
        $displayData['displayDataStatus']    = $displayDataStatus;        
        $displayData['queryParams']          = $queryParams;
        $displayData['URL']                  = $URL;
        $displayData["clientActivationData"] = $result['data'];
        $displayData["successMessage"] = isset($_GET['message'])?$_GET['message']:0;
        $this->load->view('dashboard/dashboardOverview',$displayData);
    }


    public function dashboardAbroadUserValidation($noRedirectionButReturn = false){
        $usergroupAllowed 	= $this->usergroupAllowed;
        $validity 		    = $this->checkUserValidation();
        $returnArr = $this->saCMSToolsLib->cmsAbroadUserValidation($validity, $usergroupAllowed,$noRedirectionButReturn);
        return $returnArr;
    }

    public function addClientActivationForm(){
        $this->usergroupAllowed = array('saAdmin','saSales');
        $displayData = $this->dashboardAbroadUserValidation();
        // prepare the display data here
        $displayData['formName'] 		= ENT_SA_FORM_ADD_CLIENT_ACTIVATION;
        $displayData['selectLeftNav']   = "CLIENT_ACTIVATION";
        if($displayData['usergroup']=='saAdmin') {
            $this->rmsCounsellorLib = $this->load->library('shikshaApplyCRM/rmsCounsellorLib');
            $activeCounsellorList = $this->rmsCounsellorLib->getAllCounsellor();
            $displayData['activeCounsellorList'] = $activeCounsellorList;
            $this->load->helper('utility');
            $displayData['totalCommitmentMonthData']    = getAllMonthsArr();
            $displayData['totalCommitmentYearData']     = getYearListForGivenDifference(3,3);
        }
        $displayData['fieldArray']                  = $this->getAllowedFormField($displayData['usergroup'],0);
        $displayData['isManager']                   = 0;
        $this->load->view('dashboard/dashboardOverview',$displayData);
    }

    public function editClientActivationForm($univId){
        $this->usergroupAllowed = array('saAdmin','saSales','saShikshaApply','saRMS','saCMS','saContent','saCMSLead','saAuditor','saCustomerDelivery');
        // get the user data
        $displayData                                = $this->dashboardAbroadUserValidation();
        $this->rmcPostingLib = $this->load->library('shikshaApplyCRM/rmcPostingLib');
        $displayData['isManager'] = 0;
        if($displayData['usergroup']=='saShikshaApply'){
            $displayData['isManager'] = $this->rmcPostingLib->checkIfCounsellorIsManager($displayData['validity'][0]);
        }
        // prepare the display date here
        $displayData['clientActivationData']        = $this->clientActivationLib->getClientActivationData($univId);
        if(empty($displayData['clientActivationData'])){
            $URL = ENT_SA_CMS_CLIENT_ACTIVATION_PATH.ENT_SA_VIEW_LISTING_CLIENT_ACTIVATION;
            header('Location:' .$URL);
            exit;
        }
        $this->rmsCounsellorLib = $this->load->library('shikshaApplyCRM/rmsCounsellorLib');
        $activeCounsellorList =  $this->rmsCounsellorLib->getAllCounsellor();
        $displayData['activeCounsellorList'] = $activeCounsellorList;
        $courseListForUniv                          = $this->clientActivationLib->validateUniversityAndGetCourses($univId,false);
        $courseListForUniv                          = (array) json_decode($courseListForUniv);
        $displayData['courseListForUniv']           = (array) $courseListForUniv['data'];
        //_p($displayData);die;
        $this->load->helper('utility');
        $displayData['totalCommitmentMonthData']    = getAllMonthsArr();
        $displayData['totalCommitmentYearData']     = getYearListForGivenDifference(3,3);
        $displayData['formName'] 	                = ENT_SA_FORM_EDIT_CLIENT_ACTIVATION;
        $displayData['selectLeftNav']               = "CLIENT_ACTIVATION";
        $displayData['fieldArray']                  = $this->getAllowedFormField($displayData['usergroup'],$displayData['isManager']);
        //_p($displayData);die;
        $this->load->view('dashboard/dashboardOverview',$displayData);
    }

    public function getAllowedFormField($usergroup,$isManager){
        if($usergroup=='saShikshaApply'){
            if($isManager) {
                return $this->counsellorTLFields;
            }
            else{
                return $this->viewOnlyFields;
            }
        }
        else if($usergroup=='saSales'){
            return $this->salesFields;
        }
        else if($usergroup=='saAdmin'){
            return $this->saAdminFields;
        }
        else if($usergroup=='saRMS' || $usergroup=='saAuditor'){
            return $this->auditorAndRmsField;
        }
        else{
            return $this->viewOnlyFields;
        }
    }

    public function saveClientActivationFormData(){
        $this->usergroupAllowed = array('saSales','saShikshaApply','saAdmin','saRMS','saAuditor');
        $data = $this->dashboardAbroadUserValidation();
        if($data['usergroup']=='saShikshaApply'){
            $this->rmcPostingLib = $this->load->library('shikshaApplyCRM/rmcPostingLib');
            $isManager = 0;
            $isManager = $this->rmcPostingLib->checkIfCounsellorIsManager($data['validity'][0]);
        }
        $allowedFormFields = $this->getAllowedFormField($data['usergroup'],$isManager);
        //_p($allowedFormFields);
        $postData = $this->preparePostClientActivationFormData($allowedFormFields);
        //_p($postData);die;
        if($data['usergroup']=='saShikshaApply'){
            $postData['activation_status'] = 'counsellor_activated';
        }
        else if($data['usergroup']=='saSales'){
            $postData['activation_status'] = 'sales_activated';
        }
        else if($data['usergroup']=='saAdmin'){
            $postData['activation_status'] = 'counsellor_activated';
        }

        $insertId = $this->clientActivationLib->saveClientActivationFormData($postData);
            $URL = "viewClientActivation?message=1";
            header('Location:' .$URL);
    }

    public function preparePostClientActivationFormData($allowedFormFields){
        $postData = array();
        foreach ($allowedFormFields['fields'] as $fieldName=>$value) {
            if ($value[1] == 'int' && (!in_array($fieldName,$allowedFormFields['disabled']) || $fieldName=='university_id')) {
                $postData[$fieldName] = intval($this->input->post($value[0]));
            } else if ($value[1] == 'varchar' && !in_array($fieldName,$allowedFormFields['disabled'])) {
                $postData[$fieldName] = $this->input->post($value[0]);
            } else if ($value[1] == 'text' && !in_array($fieldName,$allowedFormFields['disabled'])) {
                $postData[$fieldName] = $this->input->post($value[0]);
            } else if ($value[1] == 'date' && !in_array($fieldName,$allowedFormFields['disabled'])) {
                $date = $this->input->post($value[0]);
                if(!empty($date)){
                    $postData[$fieldName] = date('Y-m-d', strtotime($this->input->post($value[0])));
                }
            } else if ($value[1] == 'json' && !in_array($fieldName,$allowedFormFields['disabled'])) {
                $postData[$fieldName] = json_encode($this->input->post($value[0]));
            } else if ($value[1] == 'multiple' && !in_array($fieldName,$allowedFormFields['disabled'])) {
                foreach ($value[0] as $key => $field) {
                    $totalCommitment = $this->input->post("totalCommitment");
                    $commitmentIntakeYear = $this->input->post("commitmentIntakeYear");
                    $commitmentIntakeMonth = $this->input->post("commitmentIntakeMonth");
                    $commitmentBachelorsInp = $this->input->post("commitmentBachelorsInp");
                    $commitmentMastersInp = $this->input->post("commitmentMastersInp");
                    $commitmentFieldCount = $this->input->post("countTotalCommitment");
                    $postData['total_commitment']['totalCount'] = $totalCommitment;
                    for ($i = 1; $i <= $commitmentFieldCount; $i++) {
                        $postData['total_commitment']['commitmentData'][$i] = array(
                            'intakeYear' => $commitmentIntakeYear[$i],
                            'intakeMonth' => $commitmentIntakeMonth[$i],
                            'bachelorInput' => $commitmentBachelorsInp[$i],
                            'masterInput' => $commitmentMastersInp[$i],
                        );
                    }
                }
                $postData['total_commitment'] = json_encode($postData['total_commitment']);
            }
            if(empty($postData[$fieldName]) && !in_array($fieldName,$allowedFormFields['disabled'])){
                $postData[$fieldName]=null;
            }
        }
        return $postData;
    }

    public function validateUniversityAndGetCourses(){
        $univId = intval($this->input->post('univId'));
        echo $this->clientActivationLib->validateUniversityAndGetCourses($univId,true);
    }

}
?>


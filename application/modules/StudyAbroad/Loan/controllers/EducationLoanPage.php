<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 24/10/18
 * Time: 11:13 AM
 */
class EducationLoanPage extends MX_Controller {
    private $loanLib = '';

    function __construct()
    {
        $this->loanLib = $this->load->library('LoanLib');
    }


    public function index()
    {
        $this->_validateEducationLoanLandingUrl();
        $displayData = array();
        $displayData['isMobile'] = isMobileSite()?true:false;
        $displayData['applyTrackingId'] = isMobileSite()?1919:1917;
        $displayData['isAlreadyApplied'] = false;
        $displayData['validateuser'] = $this->checkUserValidation();
        if($displayData['validateuser'] !== 'false') {
            $applicationNumber = $this->loanLib->checkIfUserAlreadyAppliedForEducationLoan($displayData['validateuser'][0]['userid']);
            if($applicationNumber)
            {
                $displayData['isAlreadyApplied'] = true;
                $displayData['applicationNumber'] = $applicationNumber;
            }
        }
        $this->loanLib->setMISTrackingDetails($displayData);
        $this->loanLib->setSEODetails($displayData);
        $this->load->view('educationLoanOverview',$displayData);
    }

    private function _validateEducationLoanLandingUrl()
    {
        if(SHIKSHA_STUDYABROAD_HOME.'/apply-education-loan' != getCurrentPageURLWithoutQueryParams())
        {
            redirect(SHIKSHA_STUDYABROAD_HOME.'/apply-education-loan', 'location', 301);
        }
    }

    public function getEducationLoanLayer($step=''){
        if(!empty($step))
        {
            $layerStep = intval($step);
        }
        else
        {
            $layerStep  = $this->input->post('layerStep');
        }

        $data = array();
        if($layerStep == 2){
            $commonStudyAbroadLib   = $this->load->library('common/studyAbroadCommonLib');
            $data['cities'] = $commonStudyAbroadLib->getCities(2,true);
        }

        $layerContent = $this->load->view('educationLoanLayer/educationLoanLayer'.$layerStep,array(),true);
        if(!empty($step))
        {
            return $layerContent;
        }
        else
        {
            echo json_encode(array('layerHTML' => $layerContent,'data' => $data));
            exit;
        }

    }

    public function checkCurrentUserStatusForEducationLoan()
    {
        $responseData = array('isLoggedIn' => false, 'isAlreadyApplied' => false);
        $displayData['validateuser'] = $this->checkUserValidation();
        if ($displayData['validateuser'] !== 'false') {
            $responseData['isLoggedIn'] = true;
            $applicationNumber = $this->loanLib->checkIfUserAlreadyAppliedForEducationLoan($displayData['validateuser'][0]['userid']);
            if($applicationNumber)
            {
                $responseData['isAlreadyApplied'] = true;
                $responseData['applicationNumber'] = $applicationNumber;
            }
            else
            {
                $responseData['layerHTML'] = $this->getEducationLoanLayer(1);
            }
        }
        echo json_encode($responseData);
        die;
    }

    public function saveUserEducationLoanDetails(){
        $postData = array();
        $userDetails = $this->checkUserValidation();
        if($userDetails != 'false') {
            $postData['user_id'] = $userDetails[0]['userid'];
        }
        $validationFlag = $this->loanLib->prepareAndValidateEducationLoanPostData($postData);
        if($validationFlag == false){            
            echo json_encode(array('status'=>'failed','layerContent'=> 'Something went wrong'));        
            die;
        }
        $saveStatus = $this->loanLib->saveUserEducationLoanDetails($postData);
        try
        {
            $popularEducationLoanContents = $this->loanLib->getPopularApplyContentByApplyContentType('loan',6);
        }
        catch(Exception $n)
        {
            $popularEducationLoanContents = array();
        }
        $appNumber = $this->loanLib->getUserEducationLoanApplicationNumber($postData['user_id']);
        $layerHTML = $this->load->view('educationLoanLayer/educationLoanThankyouLayer',array('applicationNumber'=>$appNumber,'popularEducationLoanContents'=>$popularEducationLoanContents),true);
        echo json_encode(array('status'=>'success','layerContent'=> $layerHTML,'applicationNumber'=>$appNumber));
        die;
    }

}
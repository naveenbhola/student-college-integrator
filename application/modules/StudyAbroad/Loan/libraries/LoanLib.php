<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 24/10/18
 * Time: 1:04 PM
 */
class LoanLib
{
    private $loanModel='';
    function __construct(){
        $this->CI = & get_instance();
        $this->loanModel = $this->CI->load->model('Loan/loanmodel');
    }

    public function setMISTrackingDetails(&$displayData){
        $displayData['beaconTrackData']['pageEntityId'] = 0;
        $displayData['beaconTrackData']['extraData']    = null;
        $displayData['beaconTrackData']['pageIdentifier'] = 'educationLoanPage';
    }

    public function setSEODetails(&$displayData){
        $displayData['seoDetails']['url'] = getCurrentPageURLWithoutQueryParams();
        $displayData['seoDetails']['seoTitle'] = " Apply Education Loan - Study Abroad Education Loan Eligibility, Calculation and Guidance";
        $displayData['seoDetails']['seoDescription'] = "Apply education loan for abroad studies online in India. Check the foreign education loan eligibility, schemes, features & benefits and required documents at Shiksha’s education loan section and fulfil your study abroad dreams easily.";
        $displayData['seoDetails']['seoMetaKeywords'] = "education loan for study abroad, study abroad education loan, study abroad education loan schemes, loan for study abroad";
    }

    public function getUserEducationLoanDetails($userId){
        $result = $this->loanModel->getUserEducationLoanDetails($userId);
        return $result;
    }

    public function saveUserEducationLoanDetails($postData){
        $saveStatus = $this->loanModel->saveUserEducationLoanDetails($postData);
        return $saveStatus;
    }

    public function getUserEducationLoanApplicationNumber($userId){
        $result = $this->loanModel->getUserEducationLoanApplicationNumber($userId);
        return $result[0]['id'];
    }

    public function checkIfUserAlreadyAppliedForEducationLoan($userId,$timeFrameInMonths=6){
        $result = $this->getUserEducationLoanDetails($userId);
        if(empty($result)){
            return false;
        }
        $currentDate = date('Y-m-d');
        $dateNMonthsAgo = date('Y-m-d',strtotime("-$timeFrameInMonths Months",strtotime($currentDate)));
        $lastUpdateTime = $result[0]['updated_on'];
        if($lastUpdateTime >= $dateNMonthsAgo){
            return $result[0]['id'];
        }
        return false;
    }

    public function prepareAndValidateEducationLoanPostData(& $postData){
        $postData['loan_amount']                = intval($this->CI->input->post('loanAmount',true));
        if(($postData['loan_amount'] < 100000 || $postData['loan_amount'] > 20000000) && $postData['loan_amount'] != NULL){
            return false;
        }
        $postData['working_status']             = $this->CI->input->post('workingStatus',true);
        $postData['annual_income']              = isset($_POST['annualIncome'])?intval($this->CI->input->post('annualIncome',true)):NULL;
        if(($postData['annual_income'] < 0 || $postData['annual_income'] > 10000000) && $postData['annual_income'] != NULL){
            return false;
        }
        $postData['collateral_availability']    = $this->CI->input->post('collateralAvailability',true);
        $postData['collateral_location_id']        = $this->CI->input->post('collateralLocationId',true);
        $postData['collateral_location_id'] = (!empty($postData['collateral_location_id']))?$postData['collateral_location_id']:NULL;
        $postData['collateral_value']           = intval($this->CI->input->post('collateralValue',true));
        $postData['collateral_value'] = (!empty($postData['collateral_value']))?$postData['collateral_value']:NULL;
        if(($postData['collateral_value'] < 100000 || $postData['collateral_value'] > 990000000) && $postData['collateral_value'] != NULL){
            return false;
        }
        $postData['coborrower_relation']        = $this->CI->input->post('coborrowerRelation',true);
        $postData['coborrower_emp_status']      = $this->CI->input->post('coborrowerEmpStatus',true);
        $postData['coborrower_annual_income']   = intval($this->CI->input->post('coborrowerAnnualIncome',true));
        $postData['coborrower_annual_income'] = (!empty($postData['coborrower_annual_income']))?$postData['coborrower_annual_income']:NULL;
        if(($postData['coborrower_annual_income'] < 100000 || $postData['coborrower_annual_income'] > 50000000) && $postData['coborrower_annual_income'] != NULL){
            return false;
        }
        $postData['tracking_key_id']            = $this->CI->input->post('trackingKeyId',true);
        return true;
    }

    public function getPopularApplyContentByApplyContentType($type='loan',$noOfContents=6)
    {
        $this->CI->load->config("abroadApplyContentConfig");
        $applyContentTypes = $this->CI->config->item('applyContentMasterList');
        $educationLoanTypeId = 0;
        foreach ($applyContentTypes as $typeId => $typeDef)
        {

            if(strtoupper($typeDef['type']) == strtoupper($type))
            {
                $educationLoanTypeId = $typeId;
            }
        }
        $applyContentLib = $this->CI->load->library('applyContent/ApplyContentLib');
        $popularEducationLoanContents = $applyContentLib->getPopularContentByContentType($educationLoanTypeId,$noOfContents);
        return $popularEducationLoanContents;
    }
}
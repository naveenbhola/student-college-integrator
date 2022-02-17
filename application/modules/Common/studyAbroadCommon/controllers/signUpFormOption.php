<?php
class signUpFormOption extends MX_Controller{

    public function __construct(){
        $this->signUpFormOptionLib = $this->load->library('signUpFormOptionLib');
    }

    public function insertSignUpFormABTracking(){
        $validateuser = $this->checkUserValidation();
        if(!($validateuser !== 'false') && USE_ABTEST_ABROAD_SIGNLESIGNUPFORM){
            $inputParams = $this->input->post('trackingParams',true);
            trimAssociativeArray($inputParams);
            $this->signUpFormOptionLib->insertSignUpFormABTracking($inputParams);
        }
    }

    public function updateUnloadData(){
        $validateuser = $this->checkUserValidation();
        if(!($validateuser !== 'false') && USE_ABTEST_ABROAD_SIGNLESIGNUPFORM){
            $inputParams = $this->input->post('trackingParams',true);
            trimAssociativeArray($inputParams);
            if(empty($inputParams['MISTrackingId'])){
                $inputParams['MISTrackingId'] = 0;
            }
            $this->signUpFormOptionLib->updateUnloadData($inputParams['MISTrackingId'], $$inputParams['pageReferer']);
        }
    }
}
?>
<?php

class FbExceptionLeadLib {

	private $CI;

	function __construct(){
		$this->CI = & get_instance();
	}


	function getResolvedException(){
    $this->fb_exception_model = $this->CI->load->model('marketing/fbexceptionmodel');
    $exceptions = $this->fb_exception_model->getResolvedException();

    return $exceptions;
  }   

  function getLeadIdsWithExceptionValue($key, $old_value){
    $this->fb_exception_model = $this->CI->load->model('marketing/fbexceptionmodel');
    $leads = $this->fb_exception_model->getLeadIdsWithExceptionValue($key, $old_value);

    return $leads;
  }

  function updateCorrectLeadData($primary_ids, $corrected_value){
    $this->fb_exception_model = $this->CI->load->model('marketing/fbexceptionmodel');
    
    $update_data['corrected_value'] = $corrected_value;
    $update_data['is_corrected'] = 'yes';
    $update_data['updated_on'] = date('Y-m-d H:i:s');

    $this->fb_exception_model->updateCorrectLeadData($primary_ids, $update_data);

    return;
  }

  function getLeadsWithNoException($lead_ids){
    $this->fb_exception_model = $this->CI->load->model('marketing/fbexceptionmodel');
    $lead_with_excpetion = $this->fb_exception_model->getLeadsWithException($lead_ids);

    $exception_lead = array();

    foreach ($lead_with_excpetion as $lead) {
      $exception_lead[] = $lead['lead_id'];
    }

    $lead_without_exception = array_diff($lead_ids, $exception_lead);

    return $lead_without_exception;
  }

  public function updateLeadDataStatus($lead_ids){
    $this->fb_exception_model = $this->CI->load->model('marketing/fbexceptionmodel');
    $data['status'] = 'lead_data';
    $this->fb_exception_model->updateLeadDataStatus($lead_ids, $data);

  }

  public function getLeadsToRegister(){
    $this->fb_exception_model = $this->CI->load->model('marketing/fbexceptionmodel');
    $lead_ids = $this->fb_exception_model->getLeadsToRegister();      

    foreach ($lead_ids as $lead) {
      $return_lead_ids[] = $lead['lead_id'];
    }

    return $return_lead_ids;
  }


}
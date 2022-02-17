<?php
class ABTestTracking extends MX_Controller{
	function __construct(){
		$this->abTestTrackingModel = $this->load->model('abtesttrackingmodel');
	}	
	/*
	 * landing function for ajax to insert a row in ab tracking for a page load
	 */
	public function trackABPageLoad()
	{
		$data = array();
		$data['page_identifier'] = $this->input->post('pageIdentifier',true);
		$data['visitor_session_id'] = getVisitorSessionId();
		$userData = $this->checkUserValidation();
		$data['user_id'] = ($userData[0]['userid'] > 0 ? $userData[0]['userid'] : NULL);
		$data['variation_type'] = $this->input->post('variationType',true);
		$data['source_application'] = (isMobileRequest()?'mobile':'desktop');
		$data['created_on'] = date('Y-m-d H:i:s');
		$id = $this->abTestTrackingModel->insertABTestTracking($data);
		echo $id;
	}
    /*
	 * landing function for ajax to add CTA conversion data (mis tracking id & created at) in ab test cta conversion tracking
	 */
	public function trackABCTAConversion()
	{
		$data = array();
		$data['sa_abtest_tracking_id'] = $this->input->post('id',true);
		$data['mis_tracking_id'] = $this->input->post('misTrackingId',true);
		$data['created_on'] = date('Y-m-d H:i:s');
        	$id = $this->abTestTrackingModel->insertABTestCTAConversionTracking($data);
        	echo $id;
	}
}
?>
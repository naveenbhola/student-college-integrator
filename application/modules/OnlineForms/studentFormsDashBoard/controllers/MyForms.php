<?php
/**
 * This class is responsible for rendering all the document uploaded by the user.
 * It also helps in uploading new document
 *
 * @author     Aditya <aditya.roshan@shiksha.com>
 * @version
 */
include_once 'ManageBreadCrumb.php';
class MyForms extends MX_Controller {
	// it stores user details of the logged in user
	private $_validateuser;
	// it holdes the reference of breadcrumb library
	private $_manageBreadCrumb_object;
	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	private function _init() {
		//set user details
		$this->load->library('Online/courseLevelManager');
		$this->_validateuser = $this->checkUserValidation();
		if(($this->_validateuser == "false" )||($this->_validateuser == "")) {
			header('location:'.$GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']);exit();
		}
		//load the required library
		$this->load->library('StudentDashboardClient');
		$this->load->library('Online_form_client');
		$this->load->library('OnlineFormEnterprise_client');
		//instantiate object of bread crumb
		$this->_manageBreadCrumb_object = new ManageBreadCrumb();
	}
	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	public function index() {
		// call init method to set basic objects
		$this->_init();
		// set data needs to be send to template
		$data['validateuser'] = $this->_validateuser;
		$data['breadCrumbHTML'] = $this->_manageBreadCrumb_object->renderBredCrumbDetails();
		$form_list = $this->online_form_client->getFormListForUser($this->_validateuser[0]['userid']);
                $i=0;
		foreach($form_list as $form) {
			$array['instituteDetails'][0][$i]['onlineFormId'] = $form['onlineFormId'];
			$array['instituteDetails'][0][$i]['userId'] = $this->_validateuser[0]['userid'];
			$i++;
		}
		$alert = $this->onlineformenterprise_client->getAllAlerts($array,'onlineFormUser');
		$alert = json_decode($alert['alerts'],true);
		$alert = $alert['instituteDetails'][0];
		$count = count($alert);
		for($i=0;$i<$count;$i++) {
			if($form_list[$i]['onlineFormId'] == $alert[$i]['onlineFormId']) {
				$form_list[$i]['alerts'] = 	$alert[$i];
			}
		}
                foreach ($form_list as $form) {
			$res = $this->online_form_client->formHasExpired($form['courseId']);
			$res = json_decode($res,true);
			if($res[$form['courseId']] ==1) {
				$data['form_is_expired'][$form['courseId']] = "expired";
			} else {
				$data['form_is_expired'][$form['courseId']] = "notexpired";
			}
		}
		$data['form_list'] = $form_list;
		$data['userId'] = $this->_validateuser[0]['userid'];

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_OnlineForm','pageType'=>'My Forms');
        $data['dfpData']  = $dfpObj->getDFPData($data['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		//below code used for beacon tracking
		$data['beaconTrackData'] = $this->studentdashboardclient->prepareBeaconTrackData('myForms');
		$this->load->view('studentFormsDashBoard/myforms',$data);
	}
}
?>

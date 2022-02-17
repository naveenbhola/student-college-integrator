<?php 

/**
 * Class: customizemmp_lib
 * Handles all stuff related to customized mmp client/server calls
*/

class customizemmp_lib {
	private $_ci;
	
	function __construct() {
		$this->_ci = & get_instance();
		$this->_ci->load->library('xmlrpc');
	}
	
	private function _setMode($mode = 'read') {
		$server_url = CUSTOMIZE_MMP_READ_SERVER;
		$server_port = CUSTOMIZE_MMP_READ_SERVER_PORT;
		if($mode == 'write') {
			$server_url = CUSTOMIZE_MMP_WRITE_SERVER;
			$server_port = CUSTOMIZE_MMP_WRITE_SERVER_PORT;
		}
		$this->_ci->xmlrpc->set_debug(0);
		$this->_ci->xmlrpc->server($server_url,$server_port);
	}
	
	/**
	 * @method createMMPPage : creates customized mmp page
	 */
	function createMMPPage($parameters = array()) {
		$this->_setMode('write');
		$this->_ci->xmlrpc->method('createMMPPage');
		$request = array();
		if(!empty($parameters)){
			$request = $parameters;	
		}
		
		$this->_ci->xmlrpc->request(array(json_encode($request)));
		if (!$this->_ci->xmlrpc->send_request()){
			return $this->_ci->xmlrpc->display_error();
        } else {
			return $this->_ci->xmlrpc->display_response();
        }
	}
	
	/**
	 * @method updateMMPStatus : Updates mmp status value.
	 * @param string $page_id : pageid of which the status value need to be updated
	 * @param string $status : the new status of the page
	 */
	function updateMMPStatus($page_id, $status) {
		$validStatus = array('live', 'development', 'created', 'history');
		$this->_setMode('write');
		$this->_ci->xmlrpc->method('updateMMPStatus');
		$request = array();
		$request['page_id'] = $page_id;
		if(in_array($status, $validStatus)) {
			$request['status'] = $status;
			$this->_ci->xmlrpc->request(array(json_encode($request)));
			if (!$this->_ci->xmlrpc->send_request()){
				return $this->_ci->xmlrpc->display_error();
			} else {
				return $this->_ci->xmlrpc->display_response();
			}
		} else {
			return 0;
		}
	}
	
	/**
	 * @method listCustomizedMMP : List data of all the customized mmp in db
	*/
	public function listCustomizedMMP() {
		$this->_setMode('read');
		$this->_ci->xmlrpc->method('listCustomizedMMP');
		$request = array();
		$this->_ci->xmlrpc->request(array(json_encode($request)));
		if (!$this->_ci->xmlrpc->send_request()){
			return $this->_ci->xmlrpc->display_error();
        } else {
			$returnResult = $this->_ci->xmlrpc->display_response();
			$result = json_decode($returnResult);
			$data = array();
			$data['mmp_details'] = $result->mmp_details;
			$mmpCourseCount = json_decode($result->count_list, true);
			$mmpFormsList = json_decode($result->form_list, true);
			$tempMMPCourseCount = array();
			foreach($mmpCourseCount as $key=>$value) {
				$tempMMPCourseCount[$value['page_id']] = $value['count'];
			}
			$data['mmp_course_count'] = $tempMMPCourseCount;
			$data['mmp_forms_list'] = $mmpFormsList;
			return $data;
		}
	}
	
	/**
	 * @method createMMPPage : List data of customized mmp in db
	 * @param string $page_id : pageid of the mmp of which details needs to be fetched
	 */
	public function marketingPageDetailsById($page_id){
		$this->_setMode('read');
		$this->_ci->xmlrpc->method('marketingPageDetailsById');
		$request = array();
		$request['page_id'] = $page_id;
		$this->_ci->xmlrpc->request(array(json_encode($request)));
		if (!$this->_ci->xmlrpc->send_request()){
			return $this->_ci->xmlrpc->display_error();
        } else {
			$returnResult = $this->_ci->xmlrpc->display_response();
			$result = json_decode($returnResult, true);
			return $result;
		}
	}



    public function getNormalForms($page_id){
        $this->_setMode('read');
        $this->_ci->xmlrpc->method('getNormalForms');
        $request = array();
        $request['page_id'] = $page_id;
        $this->_ci->xmlrpc->request(array(json_encode($request)));
        if (!$this->_ci->xmlrpc->send_request()){
            return $this->_ci->xmlrpc->display_error();
        } else {
            $returnResult = $this->_ci->xmlrpc->display_response();
            $result = json_decode($returnResult, true);
            return $result;
        }
    }
	
	/**
	 * @method getMMPFormTypes : List all the form types available for customized mmp, If form id is given than only that form information
	 * will be retrieved
	 * @param string optional $form_id : formid of which details needs to be fetched
	 */
	public function getMMPFormTypes($form_id = NULL){
		$this->_setMode('read');
		$this->_ci->xmlrpc->method('getMMPFormTypes');
		$request = array();
		if($form_id != NULL){
			$request['form_id'] = $form_id;
		}
		$this->_ci->xmlrpc->request(array(json_encode($request)));
		if (!$this->_ci->xmlrpc->send_request()){
			return $this->_ci->xmlrpc->display_error();
        } else {
			$returnResult = $this->_ci->xmlrpc->display_response();
			$result = json_decode($returnResult, true);
			return $result;
		}
	}

        public function getCoursesForForm($formid){
                $this->_setMode('read');
                $this->_ci->xmlrpc->method('formCourses');
                $request = array();
                $request['formid'] = $formid;
                $this->_ci->xmlrpc->request(array(json_encode($request)));
                if (!$this->_ci->xmlrpc->send_request()){
                        return $this->_ci->xmlrpc->display_error();
	        } else {
                        $returnResult = $this->_ci->xmlrpc->display_response();
                        $result = json_decode($returnResult, true);
                        return $result;
                }
        }
        public function getTestPrepCoursesForForm($formid){
                $this->_setMode('read');
                $this->_ci->xmlrpc->method('getTestPrepCourses');
                $request = array();
                $request['formid'] = $formid;
                $this->_ci->xmlrpc->request(array(json_encode($request)));
                if (!$this->_ci->xmlrpc->send_request()){
                        return $this->_ci->xmlrpc->display_error();
            } else {
                        $returnResult = $this->_ci->xmlrpc->display_response();
                        $result = json_decode($returnResult, true);
                        return $result;
                }
        }
        public function getTestPrepGroupDetails($groupId){
                $this->_setMode('read');
                $this->_ci->xmlrpc->method('getTestPrepGroupDetails');
                $request = array();
                $request['groupId'] = $groupId;
                $this->_ci->xmlrpc->request(array(json_encode($request)));
                if (!$this->_ci->xmlrpc->send_request()){
                        return $this->_ci->xmlrpc->display_error();
            } else {
                        $returnResult = $this->_ci->xmlrpc->display_response();
                        $result = json_decode($returnResult, true);
                        return $result;
                }
        }

	public function addCustomization($formId, $fieldJson,$ruleJson,$fId,$type,$condition,$todo){
		$this->_setMode('write');
                $this->_ci->xmlrpc->method('saveCustomization');
                $request = array();
		$request['fieldJson'] = $fieldJson;
		$request['ruleJson'] = $ruleJson;
		$request['type'] = $type;
		$request['fId'] = $fId;
		$request['formId'] = $formId;
		$request['condition'] = $condition;
		$request['todo'] = $todo;
                if(!empty($parameters)){
                        $request = $parameters;
                }

                $this->_ci->xmlrpc->request(array(json_encode($request)));
                if (!$this->_ci->xmlrpc->send_request()){
                        return $this->_ci->xmlrpc->display_error();
        	} else {
	        	return $this->_ci->xmlrpc->display_response();
	        }
	}

	public function getCustomizationsForForm($formid,$id,$type){
                $this->_setMode('read');
                $this->_ci->xmlrpc->method('getCustomizations');
                $request = array();
                $request['formId'] = $formid;
		$request['id'] = $id;
		$request['type'] = $type;
                if(!empty($parameters)){
                        $request = $parameters;
                }
                $this->_ci->xmlrpc->request(array(json_encode($request)));
                if (!$this->_ci->xmlrpc->send_request()){
                        return $this->_ci->xmlrpc->display_error();
                } else {
                        return $this->_ci->xmlrpc->display_response();
                }
	}
	public function getPageType($formId){
                $this->_setMode('read');
                $this->_ci->xmlrpc->method('getPageType');
                $request = array();
                $request['formId'] = $formId;
                if(!empty($parameters)){
                        $request = $parameters;
                }
                $this->_ci->xmlrpc->request(array(json_encode($request)));
                if (!$this->_ci->xmlrpc->send_request()){
                        return $this->_ci->xmlrpc->display_error();
                } else {
                        return $this->_ci->xmlrpc->display_response();
                }
	}

	public function getCustomizedDataForMMPForm($mmp_details) {

		$data = array();
		$data['mmp_details'] = $mmp_details;
		$data['submitButtonText'] = $mmp_details['submitButtonText'];

		global $MMP_Tracking_keyId;
        $data['trackingKeyId'] = $MMP_Tracking_keyId['desktop'][$mmp_details['page_type']];

        $customHelpText = array();
        if(!empty($mmp_details['form_heading'])) {
			$form_heading = trim($mmp_details['form_heading']);
		} else {
			$form_heading = 'Find the best colleges for you';
		}
        if(!empty($form_heading)) {
        	$customHelpText['heading'] = $form_heading;
    	}
    	if(!empty($mmp_details['subheading'])) {
	    	$subheading = explode(",", $mmp_details['subheading']);
	    	foreach($subheading as $eachsubheading) {
	    		$finalsubheading = '';
	    		$finalsubheading = trim($eachsubheading);
	    		if(!empty($finalsubheading)) {
	    			$customHelpText['body'][] = $finalsubheading;
	    		}
	    	}
	    }
	    if(empty($customHelpText['body'])) {
    		global $registrationFormSubHeading;
    		$customHelpText['body'] = $registrationFormSubHeading;
    	}
        $data['customHelpText'] = json_encode($customHelpText);
        $data['display_on_page'] = $mmp_details['display_on_page'];

        return $data;
	}

	public function seoMMPLayerFromOrganicTraffic($mmpType, $isLoggedIn=false){
		
	    $mmp_details = array();

        $SER_HTTP_REFERER = $this->_ci->input->server('HTTP_REFERER',true);
        $REQ_showpopup = $_REQUEST['showpopup'];
        $REQ_resetpwd = $_REQUEST['resetpwd'];

        global $mmp_display_on_page_array;
        if(((strpos($SER_HTTP_REFERER, 'google') !== false) || ($REQ_showpopup != '')) && (empty($mmp_details)) && ($REQ_resetpwd != 1) && ($isLoggedIn == false) && (in_array($mmpType, $mmp_display_on_page_array))) {
  
            $this->customizemmp_model = $this->_ci->load->model('customizedmmp/customizemmp_model');
            $mmp_details = $this->customizemmp_model->getMMPDetailsByType($mmpType);
            $newMMPDetails = $this->getCustomizedDataForMMPForm($mmp_details);

        }
        return $newMMPDetails;
	}
}
?>

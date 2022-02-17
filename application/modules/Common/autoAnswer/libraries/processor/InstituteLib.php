<?php
include 'Constants.php';
class InstituteLib {

	public function __construct(){
		$this->CI=& get_instance();
		$this->htmlGeneratorLib = $this->CI->load->library('HtmlGenerator');
		$this->dataPopulators = $this->CI->load->library('autoAnswer/DataPopulators');
		$this->CI->load->model('autoAnswer/shikshabotmodel');
		$this->attributeList = array(
			'fees' => 'Fees',
			'seats' => 'Seats',
			/*'placements'=> 'Placements',*/
			);
		$this->clientCoursesData = array();
		$this->clientCoursesData[12] = "B.Tech in CSE";
		$this->clientCoursesData[13] = "B.Tech in ME";
		$this->clientoursesData[14] = "B.Tech in Civil";
		$this->clientCoursesData[15] = "B.Tech in IT";
		$this->clientCoursesData[16] = "B.Tech in ECE";
	}	
	public function findResponseForInstituteAttr($request,$response){
		
		$mainAttribute = "institute";
		$instituteObject = $request->getInstituteObject();
		$this->populateDataToObject($request,$instituteObject);
		$instituteId = $instituteObject->getId();
		$preferredChoice = $instituteObject->getPreferredChoice();
		$baseCourseId = $instituteObject->getBaseCourse();
		$clientCourseArray = $instituteObject->getClientCourseArray();
		$selectedAttrList = $instituteObject->getSelectedAttrList();

		$responseToBeSend = array();
		if($preferredChoice == "") {
			global $institutePreferredChoices;
			$responseToBeSend[] = "Are you having doubt regarding some particular institute or generic institute related doubt? Please choose.";
			$html = $this->htmlGeneratorLib->generateHtml($institutePreferredChoices);
			$response->setOptionResponses($html);
			$response->setIsOptionsResponse(true);	
			$response->setDisableTextBox(true);
			$response->setAttachClickhandler(true);
			$response->setDisableSendButton(true);
			$response->setAttachOnClass(true);
			$customData['setPrefChoice'] = true;
			$response->setCustomData($customData);
		}
		else if(/*$instituteId == 0*/isset($_POST['setPrefChoice'])) {
			$responseToBeSend[] = "Please Type in the institute name and select the institute";
			$customData['setInsId'] = true;
			$response->setCustomData($customData);
			$response->setAttachKeyUpHandler(true);
			$response->setAutoSuggestorFor("instituteName");
			$response->setDisableSendButton(true);
			$response->setAttachClickhandler(true);
			$response->setAttachOnClass(true);
		} 
		else if(/*$instituteId != 0 && $baseCourseId == 0 && empty($clientCourseArray)*/ isset($_POST['setInsId'])){
			$responseToBeSend[] = "Please select the course you are insterested in";
			$customData['setBaseCourses'] = true;
			$response->setCustomData($customData);
			$response->setDisableSendButton(true);
			$response->setAttachClickhandler(true);
			$response->setAttachOnClass(true);
			$baseCoursesData = $this->getInstituteBaseCourses($instituteId);
			$html = $this->htmlGeneratorLib->generateHtml($baseCoursesData);
			$response->setOptionResponses($html);
			$response->setIsOptionsResponse(true);				
		}
		else if (/*$baseCourseId != 0 && empty($clientCourseArray)*/ isset($_POST['setBaseCourses'])) {
			$responseToBeSend[] = "Please select among the following and press Send.";
			$customData['setClientCourses'] = true;
			$response->setCustomData($customData);
			$response->setAttachClickhandler(true);
			$response->setAttachOnClass(true);
			$response->setIsOptionsMultiSelect(true);

			$clientCoursesData = $this->getInstituteClientCourses($instituteId,$baseCourseId);
			$html = $this->htmlGeneratorLib->generateHtml($clientCoursesData);
			$response->setOptionResponses($html);
			$response->setIsOptionsResponse(true);			
			$response->setDisableTextBox(true);
		}else if(/*!empty($clientCourseArray) && empty($selectedAttrList)*/ isset($_POST['setClientCourses'])){
			$responseToBeSend[] = "Please select which all you need to find out.";
			$customData['setInstituteAttributes'] = true;
			$response->setCustomData($customData);
			$response->setAttachClickhandler(true);
			$response->setAttachOnClass(true);
			$response->setIsOptionsMultiSelect(true);
			$attributeList = $this->getInstituteAttributes($instituteId,$baseCourseId);
			$html = $this->htmlGeneratorLib->generateHtml($attributeList);
			$response->setOptionResponses($html);
			$response->setIsOptionsResponse(true);			
			$response->setDisableTextBox(true);
		} else if(/*!empty($selectedAttrList)*/isset($_POST['setInstituteAttributes'])){

			foreach ($selectedAttrList as $attr) {
				$finalResult = $this->fetchAttrData($attr,$instituteObject);
				foreach ($finalResult as $courseData) {
					$responseToBeSend[] = $this->generateDataFromAttributeResult($attr, $courseData);	
				}				
			}
			$responseToBeSend[] = "Do want to E-Brouchure for the course? Please type in yes/no";
			$customData['requestEBrouchure'] = true;
			$customData['validateResponse'] = 'yesNoTypeResponseValidate';
			$response->setCustomData($customData);
			$response->setIsCustomDataForTextRequest(true);
		}else if(isset($_POST['requestEBrouchure'])){
			$txt = $request->getText();
			if($txt == "yes"){
				$responseToBeSend[] = "Please provide us your Mobile Number to proceed WITH country code.(ex. +91-88888888888)";
				$customData['phoneNumberInput'] = true;
				$customData['validateResponse'] = "validateMobileNumber";
				$response->setIsCustomDataForTextRequest(true);
				$response->setCustomData($customData);
			}else{
				$responseToBeSend[] = "Don't know what to do from here?";
			}
		}else if(isset($_POST['phoneNumberInput'])){
			$_SESSION['phone'] = $request->getText();
			$responseToBeSend[] = "We have sent you the OTP. Please verify";
			$_SESSION['OTP'] = rand(100000,999999);
			$customData['otpVerificationForInstitute'] = true;
			$customData['validateResponse'] = "validateOTP";
			$response->setIsCustomDataForTextRequest(true);
			$response->setCustomData($customData);
		} else if(isset($_POST['otpVerificationForInstitute'])){
			$responseToBeSend[] = "WE have mailed you the E-Brouchure";
			$responseToBeSend[] = "What to do Next?";
		}

		$_SESSION['instituteObject'] = $this->dataPopulators->json_encode_private($instituteObject);
		return $responseToBeSend;
	}

	public function fetchAttrData($attr,$instituteObject){
		switch ($attr) {
			case 'fees';
				$result = $this->_fetchFeesForCourses($instituteObject);
				break;
			case 'seats':
				$result = $this->_fetchSeatsForCourses($instituteObject);
				break;
			case 'placements':
				$result = $this->_fetchPlacementsDataForCourses($instituteObject);
				break;
		}
		$finalResult = $this->processResult($result,'attributeList');
		return $finalResult;
	}

	private function _fetchSeatsForCourses($instituteObject){
		$clientCourseArray = $instituteObject->getClientCourseArray();
		$result = array();
		foreach ($clientCourseArray as $courseId) {
			$result[$courseId] = rand(60,100);
		}
		return $result;
	}

	private function _fetchFeesForCourses($instituteObject){
		$clientCourseArray = $instituteObject->getClientCourseArray();
		$result = array();
		foreach ($clientCourseArray as $courseId) {
			$result[$courseId] = rand(50000,100000)." INR";
		}
		return $result;
	}

	private function _fetchPlacementsDataForCourses($instituteObject){

	}

	public function populateDataToObject($request,$instituteObject){

		if(isset($_POST['setPrefChoice'])){
			$val = $_POST['setPrefChoice'];
			if($val == true || $val == "true"){
				$instituteObject->setPreferredChoice($_POST['optionsData']);
			}
		}else if (isset($_POST['setInsId'])) {
			$val = $_POST['setInsId'];
			if($val == true || $val == "true"){
				$data = explode("__", $_POST['optionsData']);
				$instituteObject->setId($data[0]);
				$instituteObject->setValue($data[1]);
			}
		} else if(isset($_POST['setBaseCourses'])){
			$val = $_POST['setBaseCourses'];
			if($val == true || $val == "true"){
				$data = $_POST['optionsData'];
				$instituteObject->setBaseCourse($data);
			}
		} else if(isset($_POST['setClientCourses'])){
			$val = $_POST['setClientCourses'];
			if($val == true || $val == "true"){
				$data = json_decode($_POST['optionsData']);
				$instituteObject->setClientCourseArray($data);
			}
		} else if(isset($_POST['setInstituteAttributes'])){
			$val = $_POST['setInstituteAttributes'];
			if($val == true || $val == "true"){
				$data = json_decode($_POST['optionsData']);
				$instituteObject->setSelectedAttrList($data);
			}
		}else if (isset($_POST['requestEBrouchure'])) {
			$val = $_POST['requestEBrouchure'];
			if($val == true || $val == "true"){
				$data = json_decode($_POST['optionsData']);
			}
		}
	}

	public function getInstituteBaseCourses($instituteId){
		$baseCoursesData = array();
		$baseCoursesData[10] = "B.Tech";
/*		$baseCoursesData[16] = "B.Tech";
		$baseCoursesData[21] = "BCA";
		$baseCoursesData[24] = "MCA";
*/		return $baseCoursesData;
	}

	public function getInstituteClientCourses($instituteId,$baseCourseId){
		$clientCoursesData = array();
		/*$clientCoursesData[12] = "B.Tech in CSE";
		$clientCoursesData[13] = "B.Tech in ME";
		$clientCoursesData[14] = "B.Tech in Civil";
		$clientCoursesData[15] = "B.Tech in IT";
		$clientCoursesData[16] = "B.Tech in ECE";*/
		$clientCoursesData = $this->clientCoursesData;
		return $clientCoursesData;
	}

	public function getInstituteAttributes($instituteId){
		$attributeList = $this->attributeList;
		return $attributeList;

	}

	public function processResult($result,$whichCase='attributeList'){
		$finalResult = array();
		if($whichCase == 'attributeList'){
			$ids = array_keys($result);
			foreach ($ids as $courseId) {
				$finalResult[$courseId]['name'] =$this->clientCoursesData[$courseId];
				$finalResult[$courseId]['attrValue'] =$result[$courseId];
			}
		}
		return $finalResult;
	}

	public function generateDataFromAttributeResult($attr,$data){

		$resString = "";
		$resString .= "$attr for ".$data['name']." is ".$data['attrValue'];
		return $resString;
	}

}

?>
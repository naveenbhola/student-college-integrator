<?php
/**
 * This class is responsible for rendering all the document uploaded by the user.
 * It also helps in uploading new document
 *
 * @author     Aditya <aditya.roshan@shiksha.com>
 * @version
 */
include_once 'ManageBreadCrumb.php';
class MyDocuments extends MX_Controller {
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
		//load the required library
		$this->load->library('Online/courseLevelManager');
		$this->load->library('StudentDashboardClient');
		//set user details
		$this->_validateuser = $this->checkUserValidation();
                if(($this->_validateuser == "false" )||($this->_validateuser == "")) {
			header('location:'.$GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']);exit();
		}
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
		if($this->_validateuser !='false') {
		$data['documentsDetails'] = $this->renderDocumentsDetails($this->_validateuser[0]['userid']);
		}
		if($data['validateuser'] == 'false') {
			$data['documntr_id'] = "";
		} else {
			$data['documntr_id'] = $this->_validateuser['0']['userid'];
		}

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_OnlineForm','pageType'=>'My Documents');
        $data['dfpData']  = $dfpObj->getDFPData($data['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		//below code used for beacon tracking
		$data['beaconTrackData'] = $this->studentdashboardclient->prepareBeaconTrackData('myDocuments');	
		$this->load->view('studentFormsDashBoard/mydocuments',$data);
	}
	/**
	 * This method upload the user documents and saves the path in data base
	 *
	 * @param none
	 * @return void
	 */
	public function upload() {
		// call init method to set basic objects
		$this->_init();
		$doc_title = trim($this->input->post('docTitle'));
		if($this->_validateuser == 'false') {
			echo "<div class='errorMsg'>Please log in to upload a document</div>";
			return;
		}
		if(empty($doc_title)) {
			echo "<div class='errorMsg'>Please enter a document title</div>";
			return;
		}
		$check_if_exists = json_decode($this->studentdashboardclient->checkDocumentTitle($doc_title, $this->_validateuser['0']['userid']),true);
		if($check_if_exists['0']>0) {
			echo "<div class='errorMsg'>Please select another title for the document</div>";
			return;
		}
		if($_FILES['datafile']['tmp_name'][0] == '') {
			echo "<div class='errorMsg'>Please select a document to upload</div>";
		} else
		{
			$this->load->library('Upload_client');
			$uploadClient = new Upload_client();
			$type_doc = $_FILES['datafile']['type']['0'];
			$type_doc = explode("/", $type_doc);
			$type_doc = $type_doc['0'];
			if($type_doc == 'image') {
				$upload = $uploadClient->uploadFile($appId,'image',$_FILES,array(),"-1","of_documents", 'datafile');
			} else {
				$upload = $uploadClient->uploadFile($appId,'pdf',$_FILES,array(),"-1","of_documents", 'datafile');
			}
			if(!is_array($upload)) {
				echo "<div class='errorMsg'>".$upload."</div>";
			} else
			{
				$type = explode(".",$_FILES['datafile']['name'][0]);
				$type = $type[count($type)-1];
				$insert_record = $this->studentdashboardclient->insertDocument($doc_title,$upload['0']['imageurl'],NULL,"live",$this->_validateuser['0']['userid'],$type);
				$onlineFormId = $this->input->post('onlineFormId');
				if($onlineFormId)
				{
					$this->load->library('Online_form_client');
					$this->online_form_client->attachDocuments($this->_validateuser[0]['userid'],$onlineFormId,$insert_record);
				}
				$doc_html = $this->renderDocumentsDetails($this->_validateuser['0']['userid']);
				echo "<div class='errorMsg' style='color:green'>Document has been successfully uploaded</div>"."__".$doc_html;
			}
				
		}
	}
	/**
	 * This method updates document details in data base
	 *
	 * @param none
	 * @return void
	 */
	public function updateDocumentDetails($column_name,$column_value,$request_through = "",$userId,$id) {
		$this->_init();
		$id = trim($id);
		if(!empty($id)){
			$updated_array = $this->studentdashboardclient->updateDocumentDetails($column_name,$column_value,$id);
                        $updated_array = json_decode($updated_array);
			$updated_array = trim($updated_array);
			if($updated_array == 0) {
				echo $updated_array;
				return;
			}
		}
		if(!empty($request_through)) {
			$innerHtml = $this->renderDocumentsDetails($userId);
			echo $innerHtml;
		}
	}
	/**
	 * This method lists the documents uploaded by the user
	 *
	 * @param none
	 * @return void
	 */
	public function renderDocumentsDetails($userid) {
		// call init method to set basic objects
		$this->_init();
		$documentsDetails = json_decode($this->studentdashboardclient->getDocumentDetails('all',$userid),true);
		$innerHtml = "";
		foreach($documentsDetails as $document){
			if($document['doc_type'] == 'pdf') {
				$class = 'pdfFile';
                                $target = ' target="blank"';
			} elseif(in_array($document['doc_type'],array('doc','txt','xls'))) {
				$class = 'docFile';
			} elseif(in_array(strtolower($document['doc_type']),array('jpeg','gif','png','jpg'))) {
				$class = 'imgfFile';
				$target = ' target="blank"';
			}
			$innerHtml = $innerHtml.'<li><a href='.'"/studentFormsDashBoard/MyDocuments/downloadFile/'.$document['id'].'"'.'class='.'"'.$class.'"'.'title='.'"'.$document['document_title'].'"'.'>'.$document['document_title'].'</a>'.'<a href='.'"'.'javascript:void(0);'.'"'.'onclick="'.'if(typeof(OnlineFormStudentDashboard)!=\'undefined\') {OnlineFormStudentDashboard.updateDocument(this);}'.'"'.'class="deleteDoc" title="Delete" id='.'"'.$document['id'].'"'.'>Delete</a></li>';
			$class = "";
		}
		$str1 = '<h3>My Document</h3><div id="myDocWrapper"><ul>';
		$str2 = '</ul></div><div class="spacer10 clearFix"></div>';
		if(!empty($innerHtml))  {
			$innerHtml = $str1.$innerHtml.$str2;	
		}
		return $innerHtml;
	}
	
	function downloadFile($fileId)
	{
		$this->_init();
		$onlineFormEnterpriseUserInfo = $this->_validateuser[0];
		$userid = $onlineFormEnterpriseUserInfo['userid'];
		$this->load->library('Online/document/OnlineDocument');
		$document = new OnlineDocument($fileId,$userid);
		if(!$document->download()){
			echo "You don not have access to this document.";
		}
	}
}
?>

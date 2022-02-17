<?php
/**
 *  File for Welcome Mailer attachment controller.
 */

/**
 * Welcome Mailer attachment controller.
 */
 
class WelcomeMailerAttachment extends MX_Controller
{
    /**
    * This is the Registration form ID
    * @var string
    */
    private $regFormId;
    
    /**
     * Function to load the library and helpers
     */
    private function init(){
        $this->load->library(array('enterprise_client'));
        $this->userStatus = $this->checkUserValidation();
        $this->load->helper('string');
    }
    
    /**
    * This is the Array to hold the data
    * @var array
    */
    private $data= array();
    
    /** 
     * Function to load form to upload guide
     */
    public function featuredGuideManagement(){
    
        $this->init();
        
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
			exit();
        }

        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        $this->userStatus[0]['headerTabs'] = $headerTabs;
        $this->data['validateuser'] = $this->userStatus;
        $this->data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        
        $this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new \ListingBaseBuilder();
		$HierarchyRepository = $listingBase->getHierarchyRepository();		
		$streamsArray = $HierarchyRepository->getAllStreams();
		foreach($streamsArray as $value) {
			$categoryArray[$value['id']] = $value['name']; 				
		}		
        asort($categoryArray);
        
        $this->data['category'] = $categoryArray;        
        $this->load->view('featuredGuideManagement',$this->data);
    }

    /**
     *Fuction called on form submit to save data
     */
    public function saveAllData(){

        $this->init();

        $category_id = $this->input->post('fieldOfInterest');
        $course_id = $this->input->post('desiredCourse');
        $attachment_name = $this->input->post('attachment_name');
        
        if($category_id == ''){
           $this->featuredGuideManagement(); 
        }
        
        $featured_model = $this->load->model('registration/featuredguidemodel');
        
        $this->data['fieldOfInterest'] = $category_id;
        $this->data['desiredCourse'] = $course_id;
        


        if(!empty($_FILES['pdf']['name'][0])) {
            
            if($_FILES['pdf']['type'][0] != 'application/pdf'){
                $this->data['error_message'] = 'Please upload a PDF file.';
                $this->data['attachment_name'] = $attachment_name;
                $this->featuredGuideManagement($this->data);
                return;
            }
            
            if($_FILES['pdf']['size'][0] > 5242880){
                $this->data['attachment_name'] = $attachment_name;
                $this->data['error_message'] = 'Maximum file size is 5MB.';
                $this->featuredGuideManagement($this->data);
                return;
            }

            $pdfData = $this->uploadPDF($_FILES);

            if($pdfData['error_message']){
                $this->data['attachment_name'] = $attachment_name;
                $this->data['error_message'] = $pdfData['error_message'];
                $this->featuredGuideManagement($this->data);
                return;
    
            } else {
            
                $pdf_url = $pdfData['data'];
                $pdf_name = trim($_FILES['pdf']['name'][0]);
            }
        
        }

        $insertStatus = $featured_model->insertInFeaturedGuide($category_id,$course_id,$pdf_url,$attachment_name);
        if($insertStatus == 1){
            $this->data['error_message'] = 'This course already has a guide with this name.';
            $this->featuredGuideManagement($this->data);
            return;
        }
        $this->data['error_message'] = '';
        $this->data['success_message'] = $data['success_message'];
        $this->featuredGuideManagement($this->data);
    }
    
    /**
     *Function to upload pdf files
     * @param array $files ($_FILES ARRAY CONTAINING THE UPLOADED FILES)
     * @param int $vcard
     * @param int $appId
     * @return array $data Array containing the message for error or success
     */
    public function uploadPDF($files, $vcard = 0,$appId = 1){
        $this->init();
	$this->userStatus = $this->checkUserValidation();
        
        if($files['pdf']['tmp_name'][0] == '') {
            $data['error_message'] = "Please select a document to upload";
        } else {
            $this->load->library('Upload_client');
            $uploadClient = new Upload_client();
            $upload = $uploadClient->uploadFile($appId,'pdf',$files,array(),$this->userStatus['0']['userid'],"notification", 'pdf');
    
            if(!is_array($upload)) {
                $data['error_message'] =  $upload;
            } else {
                list($width, $height, $type, $attr) = getimagesize($upload[0]['imageurl']);
                $data['data'] =  $upload[0]['imageurl'];
                $data['success_message'] = "File has been successfully uploaded.";
	    }
        }
        
        return $data;
    }
    
    /**
     *Function to delete a guide
     */
    public function deleteFromFeaturedGuide(){
        $this->init();
        $featured_model = $this->load->model('registration/featuredguidemodel');

        $id = $this->input->get('id',true);
        $stream_id = $this->input->get('stream_id',true);
        $base_course_id = $this->input->get('base_course_id',true);
        $data['error_message'] = '';

        $deleteResult = $featured_model->deleteFromFeaturedGuide($id);
        if($deleteResult == 0){
            $data['error_message'] = 'Something went wrong. This guide could not be deleted.';
        } else {
            $data['error_message'] = 'Guide deleted successfully.';
        }
        echo $this->getExistingGuides($base_course_id,1,$stream_id);
    }
    
    /**
     *Function to dynamically load the course dropdown
     * @param int $categoryId Category id
     */
    public function getCourseDropdown($categoryId){
        
        if(empty($categoryId)) {
            echo 0;
            return;
        }
        $obj = new \registration\libraries\FieldValueSources\BaseCourses;
        $selectedHierarchies[0]['streamId'] = $categoryId;
	    $selectedHierarchies[0]['substreamId'] = 'any';
	    $selectedHierarchies[0]['specializationId'] = 'any';
        $desiredCourse = $obj->getValues(array('baseEntityArr'=> $selectedHierarchies));
        //_P($desiredCourse);
        $base_courses =array();
        foreach($desiredCourse as $val) {
			if(count($val)>0) {
				foreach($val as $data) {
					//_P($data);exit;
					foreach($data as $base_course_id=>$base_course_name) {
						$base_courses[$base_course_id] = $base_course_name;		
					}
				}
			}
		}
        
        $desiredCourse = $base_courses;
        asort($desiredCourse);
        
        if(count($desiredCourse)>0) {            
            $dropdown = "<label for='desiredCourse' id='labelCourse' style='width: 126px;float: left; margin-left: 80px; display: inline-block; font-size: 15px;position: relative;'>Select Course<i class='semiColon1'>:</i></label><select name='desiredCourse' id='desiredCourse' onchange='getExistingGuides(this.value);' style='min-width:307px;'> <option value=''>Base Course</option>";        
            foreach($desiredCourse as $id=>$course) {
				if($id >0) {
					$dropdown .= "<option value = '".$id."'>".$course."</option>";
				}
			}	
            $dropdown .= "</select>";
            echo $dropdown;
            return;
        
        } else {
            
            echo "ERROR";
            return;
        }
    }
    
    /**
     *Function to get existing guides for a course
     * @param int $courseId Course Id
     * @param int $isAjax To check the call from AJax
     */
    public function getExistingGuides($courseId,$isAjax = 0,$stream_id){
		
        if(empty($courseId) || empty($stream_id)) {
            echo 0;
            return;
        }
                
        $featured_model = $this->load->model('registration/featuredguidemodel');
        $Data['existing_guides'] = $featured_model->getExistingGuides($courseId,$stream_id);
        
        if($isAjax && count($Data['existing_guides'])>0){
            return $this->load->view('Existing_guides',$Data);       
        }
        
        if(count($Data['existing_guides'])>0){   
            echo $this->load->view('Existing_guides',$Data);
        } else {
            echo 0;
        }
    }
}
 
?>

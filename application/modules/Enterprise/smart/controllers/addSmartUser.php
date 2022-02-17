<?php
/**
 *To create/update smart users iff they are already registered on shiksha.
 */
class AddSmartUser extends MX_Controller {
    
    function init(){
        $this->load->helper(array('form', 'url','date','image','shikshaUtility'));
	$this->load->library(array('enterprise_client'));
	$this->userStatus = $this->checkUserValidation();
    }
    
    /**
    *Function to load form
    *
    *@param: none
    *
    *return: none
    */
    function showField(){
        $this->init();
	$msg=$this->input->get("msg");
	$messagetext=$this->input->get("messagetext");
    $roleModel=$this->load->model("smartmodel");
	$userRole=$roleModel->getUserRoles();
	$branch=$roleModel->getBranch();
	
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
	    exit();
        }
        
	$entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        $this->userStatus[0]['headerTabs'] = $headerTabs;
        $data['validateuser'] = $this->userStatus;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
	$data['msg']=$msg;
	$data['messagetext']=$messagetext;
	$data['role']=$userRole;
	$data['branch']=$branch;
        $this->load->view('SmartUser',$data);
    }            
    
    /**
    *Function to validate field entries and load model
    *
    *This function validates fields of the form, sends those values to the model and handles the integer returned by the model to determine and inform about succes or failure along with the reason.
    *
    *@param: none
    *
    *return: none
    */
    function useradd(){
        $this->load->library('form_validation');
        $useremail=$this->input->post('user_email');
        $role=$this->input->post('role');
		$managerEmail=$this->input->post('mgr_email');
        $branch=$this->input->post('branch');
		$empID=$this->input->post('emp_id');
	
	$this->form_validation->set_rules('user_email','User Email','required|valid_email');
        
	$this->form_validation->set_rules('mgr_email','Manager Email','required|valid_email');
        
	$this->form_validation->set_rules('emp_id','Employee Id','required|(greater_than > 0)');	    
	
	$this->form_validation->set_rules('role','User Role','required');
        
	
	
	if($this->form_validation->run()){
            $AddUser = $this->load->model('smart/smartmodel');
            $flag=$AddUser->addSmartUsers($useremail,$role,$managerEmail,$empID);  
	    if($flag=='CREATED_SUCCESSFULLY' || $flag=='UPDATED_SUCCESSFULLY'){
		
		
		$flag_array=$AddUser->insertSumsUserDetails($empID, $useremail, $managerEmail, $role);   
		if($flag_array['userIdExists'] == 0){
		    error_log("===== userIdExists  ".$flag_array['userIdExists']);
		    redirect("/smart/addSmartUser/showField?msg=Failure&messagetext=User already exists");
		}else if($flag_array['empIdAlreadyExistsWithDifferentUserId'] == 1)
		{
		    redirect("/smart/addSmartUser/showField?msg=Failure&messagetext=Enter a Different Employee ID");
		}else{
		    $flag_2=$AddUser->insertSumsUserBranchMapping($useremail,$branch);
		}
		
		if($flag_array['flag'] == 1 && $flag_2 == 1){
		    redirect("/smart/addSmartUser/showField?msg=Success&messagetext=User Details Saved Succesfully");
		}else{
		    redirect("/smart/addSmartUser/showField");
		}
	    }else if($flag=='MANAGER_NOT_FOUND'){
		
	        redirect("/smart/addSmartUser/showField?msg=Failure&messagetext=Manager not found");
	    
	    }else if($flag=='USER_NOT_FOUND'){
		
	        redirect("/smart/addSmartUser/showField?msg=Failure&messagetext=User not found");
		
	    }else if($flag=='INVALID_QUERY'){
		
	        redirect("/smart/addSmartUser/showField?msg=Failure&messagetext=Invalid Query");
	    
	    }else if($flag =='EMP_ID_USER_ID_NOT_MATCH'){
		redirect("/smart/addSmartUser/showField?msg=Failure&messagetext=Employee ID and User ID don't match");
	    }else{
		
	        redirect("/smart/addSmartUser/showField");
	    
	    }
        
	}else{
            redirect("/smart/addSmartUser/showField");
        }
}
}
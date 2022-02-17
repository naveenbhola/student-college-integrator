<?php

class ClientLandingPage extends MX_Controller
{
    private $model;
    
    function __construct()
    {
        $this->load->model('marketing/clientlandingpagemodel');
        $this->model = new ClientLandingPageModel;
    }
    
    function index($pageId)
    {
        $data = array();
        $data['validateuser'] = $this->checkUserValidation();
        $data['page'] = $this->model->getPage($pageId);
        if(!$data['page']['id']) {
            echo "This page does not exist.";
            exit();
        }
        $this->load->view('marketing/ClientLandingPage/page',$data);
    }
    
    function listPages()
    {
        $data = array();
        $data['validateuser'] = $this->checkUserValidation();
        $data['pages'] = $this->model->getPages();
        $data['headerTabs'] =$this->getHeaderTabs();
        $data['prodId'] = 780;
        $data['msg'] = $_GET['msg'];
        $this->load->view('marketing/ClientLandingPage/listPages',$data);
    }
    
    function createPage()
    {
        $data = array();
        $data['validateuser'] = $this->checkUserValidation();
        $data['headerTabs'] =$this->getHeaderTabs();
        $data['prodId'] = 780;
        $this->load->view('marketing/ClientLandingPage/createPage',$data);
    }
    
    function editPage($pageId)
    {
        $data = array();
        $data['validateuser'] = $this->checkUserValidation();
        $data['headerTabs'] =$this->getHeaderTabs();
        $data['prodId'] = 780;
        $data['pageId'] = $pageId;
        $data['page'] = $this->model->getPage($pageId);
        if(!$data['page']['id']) {
            header('Location: /marketing/ClientLandingPage/listPages?msg=editPageDoesNotExist');
            exit();
        }
        $this->load->view('marketing/ClientLandingPage/createPage',$data);
    }
    
    function savePage()
    {
        $loggedInUser = $this->checkUserValidation();
        $loggedInUserId = 0;
        if(is_array($loggedInUser) && is_array($loggedInUser[0])) {
            $loggedInUserId = $loggedInUser[0]['userid'];
        }
        
        $pageId = intval(trim($this->input->post('pageId')));
        $pageName = trim($this->input->post('name'));
        $pageHTML = trim($_REQUEST['html']);
        
        $errors = array();
        if(!$loggedInUserId) {
            $errors[] = "You do not have sufficient permissions to create this page.";
        }
        if(!$pageName) {
            $errors[] = "Page name is not entered.";
        }
        if(!$pageHTML) {
            $errors[] = "Page HTML is not entered.";
        }
        
        if(count($errors) > 0) {
            $data = array('pageId' => $pageId,'errors' => $errors);
            $this->load->view('marketing/ClientLandingPage/error',$data);
        }
        else {
            $data = array(
                'pageId' => $pageId,
                'userId' => $loggedInUserId,
                'name' => $pageName,
                'html' => $pageHTML
            );
            $this->model->savePage($data);
            $msg = $pageId ? "pageUpdationSuccessful" : "pageCreationSuccessful";
            header('Location: /marketing/ClientLandingPage/listPages?msg='.$msg);
            exit();
        }
    }
    
    /**
	 * It will render view pertaining to a particular marketing page
	 *
	 * @access	private
	 * @return	array
	 */
	private function getHeaderTabs()
    {
        $this->userStatus = $this->checkUserValidation();
		if(($this->userStatus == "false" )||($this->userStatus == "")) {
			header('location:/enterprise/Enterprise/loginEnterprise');
			exit();
		}
		if(is_array($this->userStatus) && $this->userStatus['0']['usergroup']!='cms') {
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
        $this->load->library('enterprise/Enterprise_client');
		$entObj = new Enterprise_client();
		$headerTabs = $entObj->getHeaderTabs(1,$this->userStatus[0]['usergroup'],$this->userStatus[0]['userid']);
		$headerTabs['0']['selectedTab'] = 780;
		return $headerTabs;
	}
}
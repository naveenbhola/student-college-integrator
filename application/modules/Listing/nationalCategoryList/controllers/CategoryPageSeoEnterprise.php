<?php

/**
 * Class CategoryPageSeoEnterprise
 *
 * This class handles the functionalities required around the Category Page Creation CMS 
 */
class CategoryPageSeoEnterprise extends MX_Controller {
	function _init() {
		ini_set("memory_limit", '-1');
		ini_set('max_execution_time', -1);
		
		//load library that calls the script
		$this->load->library('nationalCategoryList/CategoryPageSeoLib');
		$this->categoryPageSEOLib = new CategoryPageSeoLib();
        $this->categoryPageSEOModel = $this->load->model('categorypageseomodel');
	}

	public function viewList(){
		$this->userValidation();
		$this->load->view('enterprise/adminBase/adminLayout');
	}

	public function create(){
		$this->userValidation();
		$this->load->view('enterprise/adminBase/adminLayout');
	}

	/**
	 * Controller to recieve the Ajax hit from the GO button from the url <code>/categoryPageSEO/CategoryPageSEO/create</code>
	 *
	 * @see \CategoryPageSEOLib::getSEODetails for the data processing
	 */
	public function getSEODetails(){
		$this->userValidation();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$data = $data['inputCombination'];

		$this->_init();
		echo json_encode($this->categoryPageSEOLib->getSEODetails($data));
	}

	/**
	 * Controller to cater the list of category url rules saved in the database.
	 *
	 * @see \CategoryPageSEOLib::getCategoryURLs for the data processing
	 */
	public function getCategoryURLs(){
		$this->userValidation();
		$this->_init();

		$loggedInUser = $this->userValidation();
		$doneBy = $loggedInUser['userid']; // The userID
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$data = $data['inputCombination'];
		if($data!=''){
			echo json_encode($this->categoryPageSEOLib->getCategoryURLs($data));
		} else {
			echo json_encode($this->categoryPageSEOLib->getCategoryURLs());
		}
	}

	/**
	 * Validate if the user is logged in or not
	 */
	private function userValidation(){
		$cmsUserInfo = $this->cmsUserValidation();
		if($cmsUserInfo['usergroup']!= "cms") {
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		return $cmsUserInfo;
	}

	/**
	 * Accept the data input for the category page URL creation/deletion and pass it to the next layer which processes the data
	 *
	 * @param string $mode A switch to decide if the submitted data is for deletion or creation / updation. Currently it accepts either of the two values : <b>write</b> or <b>remove</b>
	 *
	 * @see \CategoryPageSEOLib::submitSEODetails for the data processing
	 *
	 */
	public function submitSEODetails($mode='write'){
		$loggedInUser = $this->userValidation();
		$doneBy = $loggedInUser['userid']; // The userID
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$data = $data['categoryData'];

		// _p($data);die;

		$this->_init();
		if($mode == 'remove'){ // Delete data which exists
			echo json_encode($this->categoryPageSEOLib->deleteSEODetails($data, $doneBy));
		} else {
			echo json_encode($this->categoryPageSEOLib->submitSEODetails($data, $doneBy, $mode));
		}
	}


	/**
	 * Accept the data containing the id, url and the rearranged priority of the URLs and pass on to the next layer for data processing
	 *
	 * @see \CategoryPageSEOLib::submitOrderedURLs for the data processing
	 */
	public function submitOrderedURLs(){
		$this->userValidation();
		$loggedInUser = $this->userValidation();
		$createdBy = $loggedInUser['userid'];
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$data = $data['orderedURLs'];

		$this->_init();
		echo json_encode($this->categoryPageSEOLib->submitOrderedURLs($data, $createdBy));
	}


	public function pingTest(){
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$data = SHIKSHA_HOME."/".$data['url'];
		$returnCode = get_headers($data, 1);

		if(!$returnCode){
			echo json_encode(array('status' => 'not-found'));
		} else if($returnCode[0] == 'HTTP/1.1 200 OK'){
			echo json_encode(array('status' => 'ok'));
		} else {
			echo json_encode(array('status' => 'not-found'));
		}
	}

	public function bhstEdit($pageId = null)
    {
        $this->_init();
        $cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
        if ($cmsUserInfo['usergroup'] != 'cms') {
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
        }
        $userid = $cmsUserInfo['userid'];
        $validity = $cmsUserInfo['validity'];
        $cmsPageArr = array();
        $cmsPageArr['userid'] = $userid;
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] = $cmsUserInfo['headerTabs'];
        $cmsPageArr['prodId'] = 1054; //Tab ID
        $cmsPageArr['action'] = 'edit';


        if (empty($pageId)) {
            $cmsPageArr['idForm'] = true;
            $this->load->view('enterprise/BHSTEdit', $cmsPageArr);
            return;
        }
        $data = $this->categoryPageSEOModel->getCategoryPageBHSTData($pageId);
        if (empty($data)) {
            $cmsPageArr['wrongId'] = true;
            $this->load->view('enterprise/BHSTEdit', $cmsPageArr);
            return;
        }
        $cmsPageArr['bhstData'] = $data['bhst_data'];
        $cmsPageArr['headingCTP'] = $data['heading_mobile'];
        $cmsPageArr['pageId'] = $pageId;
        $cmsPageArr['idForm'] = false;
        $cmsPageArr['ctpUrl'] = $data['url'];
        $this->load->view('enterprise/BHSTEdit', $cmsPageArr);
    }

    public function bhstSubmit(){
        $this->_init();
        $this->load->helper('security');
        $bhst = xss_clean($this->input->post('bhstData'));
        $pageId = (integer)xss_clean($this->input->post('pageId'));
        $userId = (integer)xss_clean($this->input->post('userId'));
        $response = $this->categoryPageSEOModel->saveBhst($pageId, $bhst, $userId);
        echo json_encode(array('status'=>'success', "dbResponse" => $response));
    }
}

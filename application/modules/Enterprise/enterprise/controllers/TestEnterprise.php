<?php



class TestEnterprise extends MX_Controller {
    function init() {
        $this->load->helper(array('form', 'url','date','image','shikshaUtility'));
        $this->load->library(array('miscelleneous','message_board_client','blog_client','event_cal_client','ajax','category_list_client','listing_client','register_client','enterprise_client','sums_manage_client'));
        $this->userStatus = $this->checkUserValidation();
    }
	function cmsUserValidation() {
        $validity = $this->checkUserValidation();
        error_log_shiksha("VAlidity: ".$validity);
        global $logged;
        global $userid;
        global $usergroup;
        $thisUrl = $_SERVER['REQUEST_URI'];
        if(($validity == "false" )||($validity == "")) {
            $logged = "No";
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        } else {
            $logged = "Yes";
            $userid = $validity[0]['userid'];
            $usergroup = $validity[0]['usergroup'];
            if ($usergroup=="user" || $usergroup == "requestinfouser" || $usergroup == "quicksignupuser" || $usergroup == "tempuser" || $usergroup == "fbuser") {
				header("location:/enterprise/Enterprise/migrateUser");
				exit;
            }
            if( !(($usergroup == "cms")||($usergroup == "enterprise")||($usergroup=="sums")) ){
                header("location:/enterprise/Enterprise/unauthorizedEnt");
                exit();
            }
	 	}
	 		$this->load->library('enterprise_client');
	 		$entObj = new Enterprise_client();
         	$headerTabs = $entObj->getHeaderTabs(1,$validity[0]['usergroup'],$validity[0]['userid']);
	    	$this->load->library('sums_product_client');
            $objSumsProduct =  new Sums_Product_client();
            $myProductDetails = $objSumsProduct->getProductsForUser(1,array('userId'=>$userid));

        $returnArr['userid']=$userid;
        $returnArr['usergroup']=$usergroup;
        $returnArr['logged'] = $logged;
        $returnArr['thisUrl'] = $thisUrl;
        $returnArr['validity'] = $validity;
		$returnArr['headerTabs'] = $headerTabs;
        $returnArr['myProducts'] = $myProductDetails;

        return $returnArr;
    }
    
    function addCourseCMS($prodId,$id)
    {
    	$cmsUserInfo = $this->cmsUserValidation();
    	$userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();
        $cmsPageArr = array();
        $cmsPageArr['userid'] = $userid;
        $cmsPageArr['usergroup'] = $usergroup;
        $cmsPageArr['thisUrl'] = $thisUrl;
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
        $cmsPageArr['viewType'] = $id;
        $this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$countryList = $categoryClient->getCountries($prodId);
		$cmsPageArr['country_list'] = $countryList;
		$ListingClientObj = new Listing_client();
		if ($id == 1) {
			$listing_type = 'institute';
		} else if ($id == 2) {
			$listing_type = 'course';
		}
        $wikiData = $ListingClientObj->getWikiFields('1',$listing_type);
        $cmsPageArr['wikiData'] = $wikiData;
        $cmsPageArr['listing_type'] = $listing_type;
		$categoryForLeftPanel = $this->getCategories();
		$cmsPageArr['categoryForLeftPanel'] = $categoryForLeftPanel;
		$this->load->view('listing_forms/new_homepage',$cmsPageArr);
    }
	
	private function getCategories(){
		$appId = 12;
		$this->init();
		$categoryClient = new Category_list_client();
		$categoryList = $categoryClient->getCategoryTree($appId);
		$others = array();
		$categoryForLeftPanel = array();
		foreach($categoryList as $temp)
		{	
			if((stristr($temp['categoryName'],'Others') == false))
			{
			$categoryForLeftPanel[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
			}
			else
			{
			$others[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);	
			}
		}
		foreach($others as $key => $temp)
		{
			$categoryForLeftPanel[$key] = array($temp[0],$temp[1]);
		} 	
		return $categoryForLeftPanel;
	}
	
    function addCollegeCourse()
    {
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
    }
}


?>

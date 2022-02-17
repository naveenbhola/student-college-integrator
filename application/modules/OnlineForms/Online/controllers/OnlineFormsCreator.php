<?php 

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: ankurg $:  Author of last commit
$Date: 2010/07/16 09:29:49 $:  Date of last commit

message_board_client.php makes call to server using XML RPC calls.

$Id: MsgBoard.php,v 1.205 2010/07/16 09:29:49 ankurg Exp $:

*/
class OnlineFormsCreator extends MX_Controller {

	var $userStatus = '';

	function init($library=array('Online_form_client','category_list_client','register_client','alerts_client','ajax','listing_client','relatedClient'),$helper=array('url','image','shikshautility')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();
	}

	//Main function to display the Online forms to the user for this Course.
	// This will display any page of the form including the Base pages, custom pages, payment or Dashboard
	function createPageTemplate($pageId=0){
		$this->init();
		$appId = 12;
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$onlineClient = new Online_form_client();

		//Call the Backend to get the page data in case only one page is defined.
		//Else, call the Backend to get the data for all the fields/groups in the pages
		if($pageId>0){
		      $this->createpage($pageId);
		}
		else{
		      $pageList = $onlineClient->getPagesWithNoTemplate($appId);
		      if(is_array($pageList)){
			      foreach ($pageList[0]['pageData'] as $pageIdValue){
				    $this->createpage($pageIdValue['pageId']);
			      }
		      }
		}
	}


	function createpage($pageId,$courseId,$previewType=0){
		$this->init();
		$appId = 12;
		$onlineClient = new Online_form_client();
		
		if($pageId>0){
		      $ResultOfDetails = $onlineClient->getPageDataForTemplate($appId,$pageId);
		      //echo "<pre>";var_dump($ResultOfDetails);
		      //Here, we have got all the data required from the DB.
		      //Now, we will start creating the form template file based on the type of the fields using a view
		      if(is_array($ResultOfDetails)){
			    //$pageTemplateData = $this->load->view('Online/createPageTemplate',$displayData,true);
			    $pageTemplateData = $this->load->view('Online/createPageTemplate',$ResultOfDetails[0],true);
			    //echo $pageTemplateData;
			    
			    //Now write this data in a template file
			    $templateFile = "/var/www/html/shiksha/application/modules/OnlineForms/Online/views/Templates/".$ResultOfDetails[0]['pageData']['pageInfo'][0]['pageName'].".php";
			    $templatePath = "Online/Templates/".$ResultOfDetails[0]['pageData']['pageInfo'][0]['pageName'];
			    if(!file_exists($templateFile)){
				      $fp=fopen($templateFile,'w+');
				      fputs($fp,$pageTemplateData);
				      fclose($fp);
			    }
				
			    //After creating the file, update the Template path in the DB
			    $onlineClient->updateTemplatePath($appId,$pageId,$templatePath);
				
				$pageTemplateData = file_get_contents("/var/www/html/shiksha/application/modules/OnlineForms/Online/views/FormTemplate/course".$previewType.".php");
				
			
				$pageTemplateData .= $this->load->view('Online/createPageFormTemplate',$ResultOfDetails[0],true);

				
				$templateFile = "/var/www/html/shiksha/application/modules/OnlineForms/Online/views/FormTemplate/course".$courseId.".php";
				if(!file_exists($templateFile)){
				      $fp=fopen($templateFile,'w+');
				      fputs($fp,$pageTemplateData);
				      fclose($fp);
			    }
		      }
			 
		}
	}

        function dbUpdatesForRenew($courseId,$instituteShortName,$newSessionYear=2013){
		header('Content-Type: text/plain');
                $this->init();
                $appId = 12;
		$response = 'No DB Updates. Please check the parameters provided. The courseId and institute short name are mandatory.';
                $this->load->model('onlineparentmodel');
	        $this->load->model('OnlineModel');
		if($courseId>0 && $instituteShortName!=''){
		        $response = $this->OnlineModel->dbUpdatesForRenew($courseId,$instituteShortName,$newSessionYear);
		}
	       	echo $response;
        }

}
?>

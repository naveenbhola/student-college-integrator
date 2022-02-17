<?php

class RelatedDiscussion extends MX_Controller
{
    function index($categoryId,$subCategoryId,$countryId=1,$module='AnA')
    {
	        $this->load->helpers(array('shikshautility','image')); 
		$this->load->library('message_board_client');
		$appId = 1;
		$msgbrdClient = new Message_board_client();
		$response = $msgbrdClient->getRelatedDiscussions($categoryId,$subCategoryId,$countryId);
		
		switch($module){
			case 'AnA': $viewFile = 'relatedDiscussions';
					break;
			case 'Articles' : $viewFile = 'relatedDiscussionsArticles';
					break;
			case 'default' : $viewFile = 'relatedDiscussions';
					break;
		};
		if(is_array($response) && is_array($response[0])) {
			$this->load->view($viewFile,array('resultArr' => $response));
		}
    }
}

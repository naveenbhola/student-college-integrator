<?php

class CafeBuzz extends MX_Controller
{
    function index($catId = 1)
    {
		//Sanitize the input param CatId. Allow csv with integer values
		$splitArray = explode(',',$catId);
		foreach ($splitArray as $categoryId){
			if(!is_numeric($categoryId)){
				return;
			}
		}
		
	        $this->load->helpers(array('shikshautility','image')); 
		$this->load->library('message_board_client');
		$appId = 1;
		$msgbrdClient = new Message_board_client();
		$response = $msgbrdClient->getHomepageCafeWall($appId,$catId,$userId,0,20);
		
		if(is_array($response) && is_array($response[0]) && is_array($response[0]['results']) && count($response[0]['results']) > 0) {
			$displayData['topicListings'] = $response[0];
			$displayData['categoryId'] = $catId;
			$this->load->view('CafeBuzz',$displayData);
		}
    }
}

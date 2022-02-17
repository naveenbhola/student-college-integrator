<?php

class UserLeaderBoardWidget extends MX_Controller
{
    function index()
    {
	$this->load->helper(array('image','shikshautility'));
	$appId = 12;
	$data = array();
        $validateuser = $this->checkUserValidation();
        if($validateuser != "false"){
            $data['validateuser'] = $validateuser;
	    $data['userId'] = $validateuser[0]['userid'];
	}
	else{
	    return false;
	}
	$this->load->library(array('message_board_client'));
	$msgbrdClient = new Message_board_client();
	if((trim($data['userId'])!='') && ($data['userId'] > 0)){
		$Result = $msgbrdClient->getUserInfoForLeaderBaord($appId,$data['userId']);
		$data['leaderBoardInfo'] = (isset($Result[0]) && is_array($Result[0]))?$Result[0]:array();
	}
	$data['cardStatus'] =  $msgbrdClient->getVCardStatus(1,$data['userId']);
	$data['followUser'] = $msgbrdClient->getFollowUser($appId,$followingUserId,$data['userId']);
    $this->load->view('userLeaderBoard',$data);
    }
}

<?php 

    function rearrangeComments($commentsArray)  {
    	
    	$returnArray  = array();
    	
    	foreach ($commentsArray as $index => $data) {
    		if($data['parentId'] == 0 ) {
    			$returnArray[$data['commentId']]['data'] = $data;
    		}else { 
    			$returnArray[$data['parentId']]['replies'][] = $data;
    		}
    		 
    	}
    	
    	return $returnArray;
    }
    
    function getPostData($postData,$userData) {
    	
    	$returnArray = array();
    	
    	if($postData['type'] == 'comment') {
    		$returnArray['parentId'] = 0 ; 
    	}else {
    		$returnArray['parentId'] = $postData['parentId'] ;
    	}

    	if( !(isset($postData['name']) && !empty($postData['name']) ) ) {
     		if(isset($userData) && !empty($userData[0]) && $userData != 'false') {
     			$userName = $userData[0]['firstname'].' '.$userData[0]['lastname'];
     			$userName = trim(($userName));
     			if(empty($userName)) {
     				$userName = $userData[0]['displayname'];
     			}
     		}else if(isset($_COOKIE['sacontent_userName']) && !empty($_COOKIE['sacontent_userName'])) {
				$userName = $_COOKIE['sacontent_userName'];
     		}
    	}else {
    		$userName = $postData['name'];
    	}
    	
    	if( !(isset($postData['email']) && !empty($postData['email']))) {
    		if(isset($userData) && !empty($userData[0]) && $userData != 'false') {
    			$userCookieArray = explode('|', $userData[0]['cookiestr']);
    			$email = $userCookieArray[0];
    		}else if(isset($_COOKIE['sacontent_userEmail']) && !empty($_COOKIE['sacontent_userEmail'])) {
    			$email = $_COOKIE['sacontent_userEmail'];
    		}
    	}else {
    			$email = $postData['email'];
    	}
    	 
    	$userId = 0 ;
    	if(! (isset($postData['userId']) && !empty($postData['userId'])) ) {
    		if(isset($userData) && !empty($userData[0]) && $userData != 'false') {
    			$userId = $userData[0]['userid'];
    		}
    	}
    	 
    	$returnArray['userName'] = $userName ;
    	$returnArray['emailId'] = $email;
    	$returnArray['userId'] = $userId ;
    	$returnArray['contentId'] = $postData['contentId'] ;
    	$returnArray['commentText'] = base64_decode($postData['commenText']) ;
    	$returnArray['status'] = ENT_SA_PRE_LIVE_STATUS ;
        $returnArray['tracking_keyid'] = $postData['trackingPageKeyId'];
        $returnArray['visitorSessionid'] = getVisitorSessionId();
    	
    	return $returnArray;
    }

    
    function getCommentIds($commentsArray) {
    	
    	$returnArray  = array();
    	 
    	foreach ($commentsArray as $index => $data) {
    		 $returnArray[] = $data['commentId'];
    	}
    	 
    	
    	return $returnArray;
    	
    	
    }


?>

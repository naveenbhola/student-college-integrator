<?php

class UserFlowRedirection extends MX_Controller{

    public function getEncodedEmail($email) {
        $data = array();
        $data['email'] = $email;
        $this->load->view("BritishCouncilCampaign", $data);
    }
    
    public function redirectThroughCurl($email){
        
        define('SERVICE_API_URL', 'http://knowledgeisgreat.in/lead_push.php');
        $this->load->model('user/usermodel');
        $userModel = new UserModel();
        $userId = $userModel->getUserIdByEmail($email, true);
        
        $userInfo = $userModel->getUserById($userId);
        
        $postArray = array();
        $postArray['name'] = $userInfo->getFirstName().' '.$userInfo->getLastName();
        $postArray['email'] = $userInfo->getEmail();
        $postArray['mobile'] = $userInfo->getMobile();
        $postArray['course'] = 'test';
        $postArray['college'] = 'test';
        $push_to_log_file = array('user_id'=>$userId,'time'=>date('m/d/Y h:i:s a', time()),'email'=>$postArray['email'],'mobile'=>$postArray['mobile']);        
        error_log("####BRITISH COUNCIL DATA... ".print_r($push_to_log_file,true));
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, SERVICE_API_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postArray));
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
           
        curl_close ($ch);
        error_log("####BRITISH COUNCIL DATA RESPONSE... ".$response);
        print_r($response);
        
    }

    /**
     *Function to call a user
     */
    function callUserPhone($toNumber,$fromNumber,$is_ajax = false){
        
        //$toNumber=7838060230;
        define('CALL_API_URL', 'http://www.valuecallz.com/utils/sendVoice.php?cid=822818&tonumber='.$fromNumber.'&bparty='.$toNumber);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, CALL_API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response= curl_exec($ch);
        $call_back_respone= explode(",", $response);
        curl_close ($ch);
        
        if(is_array($call_back_respone) && count($call_back_respone)>0) {
            
            $message = "FAILURE";
            $status = $call_back_respone[1];
            
            if($status == 200) {
                $message = "SUCCESS";
            } 
            
            if($is_ajax) {
                echo $message;
            } else {
                return $message;
            }
            
        } else {
            
            $message = "FAILURE";
            
            if($is_ajax) {
                echo $message;
            } else {
                return $message;
            }
        }
    }
}

?>

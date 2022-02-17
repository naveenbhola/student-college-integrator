<?php
class FirstPageLogin extends MX_Controller {
	function init() {
		$this->load->helper(array('form', 'url'));
		$this->load->library('ajax');
        $this->load->library('paymentClient');
        global $validity;
        $validity = $this->checkUserValidation();
        global $logged;
        if($validity == "false" ) {
            $logged = "No";
        }else {
            $logged = "Yes";
        }

    }

    function index() {
        global $logged;
            $this->init();
            $this->load->library('login_client');
            $loginClientObj = new login_client();
            //error_log_shiksha(print_r($_REQUEST,true)."kdfhkljsdfklhasdhasdfh");
            if($logged == "No") {
                $email = $_POST['email'];
                $password = $_POST['password'];
                //error_log_shiksha("data = ".$email." $password");
                $password = urldecode($password);
                //error_log_shiksha("data = ".$email." $password");

                $loginData = $loginClientObj->validateuser("$email|$password","");
                //error_log_shiksha("data12334567678 = ".print_r($loginData,true));
                //            $loginData[0]['userid'] = 10;
                if(isset($loginData[0]['userid'])) {
                    
                    setcookie("user",$email."|".$password,time()+36000,"/");
                    //error_log_shiksha("data1233456767812 = ".print_r($loginData,true));
                    $userid = $loginData[0]['userid'];
                    $paymentClientObj = new PaymentClient();
                    //error_log_shiksha($userid);
                    //error_log_shiksha("user id = ".$userid);
                    $productDataList =  $paymentClientObj->getProductForUser(1,$userid);
                    //error_log_shiksha(print_r($productDataList,true));
                    if(isset($productDataList[0])) {
                        echo "REDIRECT||".base_url() ."/listing/Listing/addCourse";
                    }else {
                        echo "REDIRECT||".base_url() ."/payment/payment";
                    }
                }else {
                    //error_log_shiksha("returning khali");
                    echo "";
                }
            }




    }
   }
   ?>

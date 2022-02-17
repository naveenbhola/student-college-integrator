<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Purpose       : This Class enables you to perform all sort of operations relating Shiksha's Coupon System.
* Author        : Romil/Nikita
* Creation Date : 8th Jan 2015
*/

class CouponLib
{
    function __construct() {
        $this->CI = & get_instance();
    }
    
    function _init() {
        $this->couponmodel = $this->CI->load->model("common/couponmodel");
    }
    
    /**
    * Purpose : Method to create a new coupon code for the given user(if no coupon already exists for that user)
    * Params  :	1. User-id - Integer
    * Author  : Romil
    */
    public function createUsersCoupon($userId) {
        // initialize
        $this->_init();
        $userCoupon = "";
        
        // check if user coupon already exists or not
        $userCouponData = $this->getUserCoupon($userId);
        
        // create a new coupon if no coupon for user exists
        if(empty($userCouponData))
            $userCoupon = $this->couponmodel->createUsersCoupon($userId);
        
        // return false if no coupon is created
        if(empty($userCoupon))
            return false;
        
        return $userCoupon;
    }
    
    public function getUserCoupon($userId) {
        if(!empty($userId)) {
            $this->_init();
            return $this->couponmodel->getUserCoupon($userId);
        } else {
            return false;
        }
    }
    
    /*
     * Output:
     *  1. coupon is valid or not
     *  2. coupon type, if user id is given
     */
    public function validateAndGetCouponType($coupon, $userId = false) {
        // initialize
        $this->_init();
        $coupon = strtoupper($coupon);
        
        //if coupon starts with SHK
        if($coupon == DEFAULT_COUPON_CODE) {
            $output['isValid'] = 1;
            $output['couponType'] = 'default';
            return $output;
        }
        
        //if coupon does not start with RC
        $pos_rc = strpos($coupon, COUPON_CODE_PREFIX);
        if($pos_rc !== false) {
            if($pos_rc != 0) {
                $output['isValid'] = 0;
                return $output;
            } else {
                $couponUserId = $this->couponmodel->getCouponUserId($coupon);
                if($couponUserId) {
                    $output['isValid'] = 1;
                    if($userId) {
                        if($couponUserId == $userId) {
                            $output['couponType'] = 'own';
                        } else {
                            $output['couponType'] = 'referral';
                            $output['referrerUserId'] = $couponUserId;
                        }
                    } else {
                        $output['couponType'] = '';
                    }
                    return $output;
                } else {
                    $output['isValid'] = 0;
                    return $output;
                }
            }
        } else {
            $output['isValid'] = 0;
            return $output;
        }
    }
    
    public function logOnlineFormCouponSystem($data) {
        $this->_init();
        $this->couponmodel->logOnlineFormCouponSystem($data);
    }
    
    public function integratePaytm($formId, $userId, $couponCode = false, $isExternalFormFlag = 0) {
    	global $paymentPaymentExclusionCourses;
    	// LF-2986 : do not make paytm paytm to some specific Online forms
    	if(in_array($formId, $paymentPaymentExclusionCourses)){
        	return array();
        }

		$paytmLib = $this->CI->load->library('common/PaytmLib');
		
	if(!empty ($couponCode)) {
            //logging details - if paytm coupon is used
			$data = array();
			$data['formId'] = $formId;
			$data['userId'] = $userId;
			$data['couponCode'] = $couponCode;
			$data['status'] = 'in_process';
			$data['action'] = $isExternalFormFlag == 1 ? 'PayTM coupon used. Process started.' : 'Shiksha payment done. PayTM coupon used. Process started.';
			$data['isExternalFormFlag'] = $isExternalFormFlag;
			$this->logOnlineFormCouponSystem($data);
			
            /*
             * Check coupon validity and its type
             */
			$couponData = $this->validateAndGetCouponType($couponCode, $userId);
            
			if($couponData['isValid']) {
                            
                                // reset the referral coupon cookie
                                setcookie("referral_Coupon","",time() - 36000,'/',COOKIEDOMAIN);
                        
				//logging details - coupon has been verified, its valid
                $data = array();
				$data['formId'] = $formId;
				$data['userId'] = $userId;
				$data['couponCode'] = $couponCode;
				$data['isValidCode'] = 'yes';
				$data['couponType'] = $couponData['couponType'];
                $data['referrerUserId'] = $couponData['referrerUserId'];
				$data['status'] = 'in_process';
				$data['action'] = 'PayTM coupon used. Valid coupon code. '.$couponData['couponType'].' coupon used.';
				$data['isExternalFormFlag'] = $isExternalFormFlag;
				$this->logOnlineFormCouponSystem($data);
				
                /*
                 * Coupon is default/referral, creating new coupon
                 */
				if($couponData['couponType'] == 'default' || $couponData['couponType'] == 'referral') {
					$couponCreated = $this->createUsersCoupon($userId);
				}
                
                //logging details
                $data = array();
				$data['formId'] = $formId;
				$data['userId'] = $userId;
				$data['couponCode'] = $couponCode;
				$data['isValidCode'] = 'yes';
				$data['couponType'] = $couponData['couponType']; // default	or referral or own
				$data['referrerUserId'] = $couponData['referrerUserId']; //(if referral)
				$data['status'] = 'in_process';
				if($couponCreated) {
                    //new coupon created successfully
					$data['isNewCouponCreated'] = 'yes';
					$data['couponCodeCreated'] = $couponCreated;
					$data['action'] = 'PayTM '.$couponData['couponType'].' coupon used. New coupon code created successfully.';
				} else if($couponData['couponType'] != 'own') {
                    //new coupon not created, coupon already exists
					$data['isNewCouponCreated'] = 'no';
					$data['couponCodeCreated'] = '';
					$data['action'] = 'PayTM '.$couponData['couponType'].' coupon used. No new coupon created. Coupon already exists.';
				} else {
                    //no new coupon is to be generated
					$data['action'] = 'PayTM '.$couponData['couponType'].' coupon used. No new coupon created. Coupon already exists.';
				}
				$data['isExternalFormFlag'] = $isExternalFormFlag;
				$this->logOnlineFormCouponSystem($data);
				
                /*
                 * Coupon is own/default/referral, transferring money
                 */
                //logging details - initiating money transfer
				$data = array();
				$data['formId'] = $formId;
				$data['userId'] = $userId;
				$data['couponCode'] = $couponCode;
				$data['isValidCode'] = 'yes';
				$data['couponType'] = $couponData['couponType']; // default or own or referral
				$data['referrerUserId'] = $couponData['referrerUserId']; //(if referral)
				if($couponCreated) {
					$data['isNewCouponCreated'] = 'yes';
					$data['couponCodeCreated'] = $couponCreated;
				} else if($couponData['couponType'] != 'own') {
					$data['isNewCouponCreated'] = 'no';
					$data['couponCodeCreated'] = '';
				}
				$data['status'] = 'in_process';
				$data['action'] = 'PayTM '.$couponData['couponType'].' coupon used. Money transfer to self initiated.';
				$data['isExternalFormFlag'] = $isExternalFormFlag;
				$this->logOnlineFormCouponSystem($data);
				
                //getting user details
                $this->CI->load->model("user/usermodel");
				$usermodel_object = new usermodel();
				$temp[] = $userId;
				$user = $usermodel_object->getUsersBasicInfo($temp);
				$mobile = $user[$userId]['mobile'];
				$email = $user[$userId]['email'];
                
                //calling money transfer API
				$result = $paytmLib->transferMoneyToUserWallet($formId, $userId, $couponCode, $couponData['couponType'], $mobile, $email);
				
				// LF-2679 : Send mail to user submitting external online form to acknowledge the successful form submission informing his/her Coupon code and link to share page
                if($isExternalFormFlag == 1)
                        $this->sendExternalOFEmailToUser($formId, $userId);

                //send mail and sms to user
				$this->sendEmailSMSToUser($userId);
                
                //logging details - end of money transfer
				$data = array();
				$data['formId'] = $formId;
				$data['userId'] = $userId;
				$data['couponCode'] = $couponCode;
				$data['isValidCode'] = 'yes';
				$data['couponType'] = $couponData['couponType']; // or own or referral
				$data['referrerUserId'] = $couponData['referrerUserId']; //(if referral)
				if($couponCreated) {
					$data['isNewCouponCreated'] = 'yes';
					$data['couponCodeCreated'] = $couponCreated;
				} else if($couponData['couponType'] != 'own') {
					$data['isNewCouponCreated'] = 'no';
					$data['couponCodeCreated'] = '';
				}
				$data['paytmTrackId'] = $result['rowId'];
				$data['status'] = 'completed';
				if($couponData['couponType'] == 'referral') {
					$data['status'] = 'in_process'; // 'in process' if referral
				}
				$data['action'] = 'PayTM '.$couponData['couponType'].' coupon used. Money transfer to self completed.';
               	$data['isExternalFormFlag'] = $isExternalFormFlag;
				$this->logOnlineFormCouponSystem($data);
                
                /*
                 * Coupon is referral, transferring money to referrer
                 */
                //logging details - initiating money transfer
				if($couponData['couponType'] == 'referral') {
					$data = array();
					$data['formId'] = $formId;
					$data['userId'] = $userId;
					$data['couponCode'] = $couponCode;
					$data['isValidCode'] = 'yes';
					$data['couponType'] = $couponData['couponType']; // default or own or referral
					$data['referrerUserId'] = $couponData['referrerUserId']; //(if referral)
					if($couponCreated) {
						$data['isNewCouponCreated'] = 'yes';
						$data['couponCodeCreated'] = $couponCreated;
					} else if($couponData['couponType'] != 'own') {
						$data['isNewCouponCreated'] = 'no';
						$data['couponCodeCreated'] = '';
					}
					$data['status'] = 'in_process';
					$data['action'] = 'PayTM referral coupon used. Money transfer to referral initiated.';
					$data['isExternalFormFlag'] = $isExternalFormFlag;
					$this->logOnlineFormCouponSystem($data);
					
                    //getting user details
                    $temp[] = $couponData['referrerUserId'];
                    $user = $usermodel_object->getUsersBasicInfo($temp);
					$refMobile = $user[$couponData['referrerUserId']]['mobile'];
					$refEmail = $user[$couponData['referrerUserId']]['email'];
					
                    //calling money transfer API
					$trackRowId = $paytmLib->transferMoneyToUserWallet($formId, $couponData['referrerUserId'], $couponCode, $couponData['couponType'], $refMobile, $refEmail);
					
                    //send mail and sms to referrer
                    $this->sendEmailSMSToReferrer($couponData['referrerUserId'], $userId);
                    
                    //logging details - end of money transfer
					$data = array();
					$data['formId'] = $formId;
					$data['userId'] = $userId;
					$data['couponCode'] = $couponCode;
					$data['isValidCode'] = 'yes';
					$data['couponType'] = $couponData['couponType']; // or own or referral
					$data['referrerUserId'] = $couponData['referrerUserId']; //(if referral)
					if($couponCreated) {
						$data['isNewCouponCreated'] = 'yes';
						$data['couponCodeCreated'] = $couponCreated;
					} else if($couponData['couponType'] != 'own') {
						$data['isNewCouponCreated'] = 'no';
						$data['couponCodeCreated'] = '';
					}
					$data['paytmTrackId'] = $trackRowId['rowId'];
					$data['status'] = 'completed';
					$data['action'] = 'PayTM referral coupon used. Money transfer to referral completed.';
					$data['isExternalFormFlag'] = $isExternalFormFlag;
					$this->logOnlineFormCouponSystem($data);
				}
                $output['msg'] = 'paytmMoneyTransferSuccessful';
			} else {
                //logging details - coupon has been verified, and it's invalid
				$data = array();
				$data['formId'] = $formId;
				$data['userId'] = $userId;
				$data['couponCode'] = $couponCode;
				$data['isValidCode'] = 'no';
				$data['couponType'] = '';
				$data['status'] = 'completed';
				$data['action'] = 'PayTM coupon used. Invalid coupon code. Process completed.';
				$data['isExternalFormFlag'] = $isExternalFormFlag;
				$this->logOnlineFormCouponSystem($data);
                $output['msg'] = 'invalidCoupon';
			}
		} else {
            //logging details - if paytm coupon is not used
			$data = array();
			$data['formId'] = $formId;
			$data['userId'] = $userId;
			$data['couponCode'] = '';
			$data['status'] = 'in_process';
			$data['action'] = 'Shiksha payment done. PayTM coupon not used.';
			$data['isExternalFormFlag'] = $isExternalFormFlag;
			$this->logOnlineFormCouponSystem($data);
			
            /*
             * Creating new coupon
             */
			$couponCreated = $this->createUsersCoupon($userId);
			
            //logging details
			if($couponCreated) {
                //new coupon created successfully
				$data = array();
				$data['formId'] = $formId;
				$data['userId'] = $userId;
				$data['couponCode'] = '';
				$data['isNewCouponCreated'] = 'yes';
				$data['couponCodeCreated'] = $couponCreated;
				$data['status'] = 'completed';
				$data['action'] = 'PayTM coupon not used. New coupon code created successfully. Process completed.';
				$data['isExternalFormFlag'] = $isExternalFormFlag;
				$this->logOnlineFormCouponSystem($data);
			} else {
                //none created as coupon already exists
				$data = array();
				$data['formId'] = $formId;
				$data['userId'] = $userId;
				$data['couponCode'] = '';
				$data['isNewCouponCreated'] = 'no';
				$data['couponCodeCreated'] = '';
				$data['status'] = 'completed';
				$data['action'] = 'PayTM coupon not used. No new coupon created. Coupon already exists. Process completed.';
				$data['isExternalFormFlag'] = $isExternalFormFlag;
				$this->logOnlineFormCouponSystem($data);
			}
            
            $output['msg'] = 'paytmNotUsed';
		}
                
        // send alert if daily paytm amount is more than a threshold and if number of applications submissions with paytm are more than a threshold
        $this->checkPaytmDailyThresholdForAlerts();
        $output['couponCreated'] = $couponCreated;
        $output['couponType']    = $couponData['couponType'];
        
        return $output;
	}
    
    public function encodeCouponCode($coupon) {
        $uniqueCode = substr($coupon, 2);
        $encodedUniqueCode = base_convert($uniqueCode, 10, 36);
        return $encodedUniqueCode;
    }
    
    public function decodeCouponCode($encodedCoupon) {
        $couponCode = base_convert($encodedCoupon, 36, 10);
        $decodedCouponCode = COUPON_CODE_PREFIX.$couponCode;
        return $decodedCouponCode;
    }
    
    public function sendEmailSMSToUser($userId) {
        //getting user details
        $this->CI->load->model("user/usermodel");
        $usermodel_object = new usermodel();
        $temp[] = $userId;
        $user = $usermodel_object->getUsersBasicInfo($temp);
        $mobile = $user[$userId]['mobile'];
        $email = $user[$userId]['email'];
        
        //get unique code
        $this->_init();
        $coupon = $this->getUserCoupon($userId);
        $encodedUniqueCode = $this->encodeCouponCode($coupon);
        
        //load libraries to send mail
        $this->CI->load->library('Alerts_client');
        $alertClient = new Alerts_client();
        
        $shortLink = SHIKSHA_HOME."/r/c/".$encodedUniqueCode;
        $subject = "Forward this email to your friends and ask them to apply on Shiksha.com";
        $content = "<p>Hey,</p>".
                    "<p>I have applied to an MBA college on Shiksha.com and got Rs. 100 in my Paytm wallet. Use my code $coupon if you want to apply too and both of us will earn Rs. 100. Applying to MBA colleges on Shiksha is easy, convenient and safe. Shiksha is an authorized partner for colleges like XIME, LIBA, Christ and 20+ top MBA colleges. Apply before the last date runs out.</p>".
                    "<p><a href='$shortLink'>Click here</a> to apply now.</p>".
                    "<p>Remember to use $coupon to earn Rs. 100 on Paytm. </p>";
        $alertClient->externalQueueAdd("12", ADMIN_EMAIL, $email, $subject, $content, "html", '', 'n');
        
        //load libraries to send sms
        $this->CI->load->model('SMS/smsModel');
		$smsmodel_object = new smsModel();
		
        $content_sms = "I got Rs.100 on Paytm when I applied to a college on Shiksha. Apply with code $coupon & get Rs.100 on Paytm. ".
                        "Click $shortLink to apply";
        										//$dbHandle,$toSms,$content,$userId,$sendTime
		$msg = $smsmodel_object->addSmsQueueRecord("1", $mobile, $content_sms, $userId, 0);
    }
    
    /*
     * referrerUserId -> the one who has referred, to whom the mail is being sent, whose coupon is used
     * referralUserId -> to whom it is referred
     */
    public function sendEmailSMSToReferrer($referrerUserId, $referralUserId) {
        //getting user details
        $this->CI->load->model("user/usermodel");
        $usermodel_object = new usermodel();
        $temp[] = $referrerUserId;
        $temp[] = $referralUserId;
        $user = $usermodel_object->getUsersBasicInfo($temp);
        $mobile = $user[$referrerUserId]['mobile'];
        $email = $user[$referrerUserId]['email'];
        $firstName = $user[$referralUserId]['firstname'];
        
        //get unique code
        $this->_init();
        $coupon = $this->getUserCoupon($referrerUserId);
        
        //load libraries to send mail
        $this->CI->load->library('Alerts_client');
        $alertClient = new Alerts_client();
        
        $subject = "You have earned Rs. 100 on Paytm";
        $content = "<p>Hi,</p>".
                    "<p>You have a Paytm credit of Rs. 100 from Shiksha. $firstName has applied with your code. Earn more by asking more friends to apply with your code : $coupon</p></br>".
                    "<p>Thanks & Regards</p>".
                    "<p>Shiksha.com</p>";
        $alertClient->externalQueueAdd("12", ADMIN_EMAIL, $email, $subject, $content, "html", '', 'n');
        
        //load libraries to send sms
        $this->CI->load->model('SMS/smsModel');
		$smsmodel_object = new smsModel();
		
        $content_sms = "Hi, you have a Paytm credit of Rs.100 from Shiksha. Earn more by asking your friends to apply with your code : $coupon on Shiksha.com";
        										//$dbHandle, $toSms, $content, $userId, $sendTime
		$msg = $smsmodel_object->addSmsQueueRecord("1", $mobile, $content_sms, $referrerUserId, 0);
    }
    
    public function getCouponUserData($couponCode, $isEncoded = 0) {
        if($isEncoded) {
            $couponCode = $this->decodeCouponCode($couponCode);
        }
        
        $validity = $this->validateAndGetCouponType($couponCode);
        if($validity['isValid']) {
            $this->_init();
            $userId = $this->couponmodel->getCouponUserId($couponCode);
            
            if($userId) {
                //getting user details
                $this->CI->load->model("user/usermodel");
                $usermodel_object = new usermodel();
                $temp[] = $userId;
                $user = $usermodel_object->getUsersBasicInfo($temp);
                $displayName = $user[$userId]['displayname'];
                $firstName = $user[$userId]['firstname'];
                
                $output['msg'] = 'VALID COUPON';
                $output['couponCode'] = $couponCode;
                $output['userId'] = $userId;
                $output['userName'] = $displayName;
                $output['firstName'] = $firstName;
            } else {
                $output['msg'] = 'COUPON USER DOES NOT EXIST';
                $output['couponCode'] = $couponCode;
            }
        } else {
            $output['msg'] = 'INVALID COUPON';
            $output['couponCode'] = $couponCode;
        }
        
        return $output;
    }
    
    public function checkPaytmDailyThresholdForAlerts(){
        $this->_init();

        $emailIdarray = array('romil.goel@shiksha.com', 'pankaj.meena@shiksha.com','aman.varshney@shiksha.com', 'g.ankit@shiksha.com', 'nikita.jain@shiksha.com', 'vinay.airan@shiksha.com');
        $mobilearray = array("9718622036", "9958992660", "9555677416", "9654553155", "9873136513");
        $result = $this->couponmodel->getTodaysPaytmCreditsTransferred();

        // check thresholds and send alert
        if($result['amount'] > 4000 || $result['application_count'] > 40)
        {
            $this->CI->load->library('Alerts_client');
            $alertClient = new Alerts_client();

            $subject = "Paytm Daily Threshold Limit Crossed";
            $content = "<p>Hey,</p>".
                    "<p>Paytm Amount Transferred Today : ".$result['amount']."</p>".
                    "<p>Online Forms using Paytm : ".$result['application_count']."</p>";

            foreach($emailIdarray as $key=>$emailId)
                $alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $content, "html", '', 'n');
        
            //load libraries to send sms
            $this->CI->load->model('SMS/smsModel');
	    $smsmodel_object = new smsModel();

            $content_sms = "Paytm Daily Threshold Limit Crossed. Amount : ".$result['amount'].". Online Forms : ".$result['application_count'];

            foreach($mobilearray as $key=>$mobileNum)
                $msg = $smsmodel_object->addSmsQueueRecord("1", $mobileNum, $content_sms, 0, 0);
        }
    }

    /**
    * Purpose : Method to send mail to user submitting external online form to acknowledge the successful form submission informing his/her Coupon code and link to share page
    * Params  : 1. Course-id - Integer
    *           2. User-id   - Integer
    * Author  : Romil
    */
    public function sendExternalOFEmailToUser($courseId, $userId) {

        //getting user details
        $this->_init();
        $this->CI->load->model("user/usermodel");
        $this->CI->load->builder('ListingBuilder','listing');
        
        $listingBuilder     = new ListingBuilder;
        $courseRepository   = $listingBuilder->getCourseRepository();
        
        // get user's details
        $usermodel_object   = new usermodel();
        $temp[]             = $userId;
        $user               = $usermodel_object->getUsersBasicInfo($temp);
        $email              = $user[$userId]['email'];
        $name               = $user[$userId]['firstname'];
        
        // get institute name
        $courseObj          = $courseRepository->find($courseId);
        $instituteName      = $courseObj->getInstituteName();
        
        //get unique code
        $coupon = $this->getUserCoupon($userId);
        
        //load libraries to send mail
        $this->CI->load->library('Alerts_client');
        $alertClient = new Alerts_client();
        
        // get users online form dashboard link(autologin link)
        $encodedEmail                   = $usermodel_object->getEncodedEmail($email);
        $redirectURL                    = SHIKSHA_HOME.'/mba/resources/application-forms';
        $dashboardAutoLoginLink         = SHIKSHA_HOME.'/index.php/mailer/Mailer/autoLogin/email~'.$encodedEmail.'_url~'.base64_encode($redirectURL);
        
        // get users coupon share link(autologin link)
        $redirectURL                    = SHIKSHA_HOME.'/Online/OnlineForms/showOFConfirmationPage/0';
        $couponShareAutoLoginLink       = SHIKSHA_HOME.'/index.php/mailer/Mailer/autoLogin/email~'.$encodedEmail.'_url~'.base64_encode($redirectURL);
        
        // create mail data
        $subject = "You have earned Rs.100 on Paytm";
        $content = "<p>Hi ".$name.",</p>".
                    "<p>Thank you for submitting your form for ".$instituteName." through Shiksha.com.</p>".
                    "<p>We have credited your Paytm account with Rs. 100.</p>".
                    "<p style='font-size: 13px;'>(If you don't have a paytm account, sign-up with your mobile number on <a href='https://paytm.com/' rel='nofollow'>paytm.com</a> within 7 days to retain your credit)</p>".
                    "<br/>".
                    "<p><b>Your unique code is : ".$coupon."</b></p>".
                    "<p>Use you code to apply to more colleges on shiksha.com and earn Rs.100 every time you apply</p>".
                    "<p><a href='".$dashboardAutoLoginLink."'>View more college applications</a></p>".
                    "<br/>".
                    "<p>or</p>".
                    "<br/>".
                    "<p>Share your code with your friends and encourage them to apply.</p>".
                    "<p>Each time your friend applies both you and your friend get Rs.100</p>".
                    "<p><a href='".$couponShareAutoLoginLink."'>Share you code now</a></p>".
                    "<br/>".
                    "<p>You will get a mail/SMS from Paytm to claim your money</p>".
                    "<br/>".
                    "<p>Thanks & Regards</p>".
                    "<p>Shiksha.com</p>";

        // send mail
        $alertClient->externalQueueAdd("12", ADMIN_EMAIL, $email, $subject, $content, "html", '', 'n');
    }

	 /*
     * Enter entries for Coupon applied on external forms
     * 
     */
    
    function trackUserAppliedCoupon($couponCode,$userId,$courseId) {
    	$this->_init();
    	$this->couponmodel->trackUserAppliedCoupon($couponCode,$userId,$courseId);
    }
    
    /*
     * Fetch applied unpaid coupon for an course for a specific user.
    *
    */
    function fetchAppliedCouponForACourse($courseId,$userId) {
    	$this->_init();
    	return $this->couponmodel->fetchAppliedCouponForACourse($courseId,$userId);
    }
    

}

?>

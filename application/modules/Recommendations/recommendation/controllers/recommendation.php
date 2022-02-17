<?php 

class Recommendation extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->load->library('recommendation/recommendation_front_lib');
		$this->load->helper('recommendation');
		
		registerShutdown('recommendation');
		
		set_time_limit (0);
		//ini_set('memory_limit','1824M');
		ini_set('display_errors','Off');
	}
					
	function processNationalMailer($listing_type,$recommendation_id,$hash,$fromMMM=0,$downloadBrochure=0)
	{
		try {
			$recommendation_id = (int) $recommendation_id;
			
			if($listing_type == 'course' && $recommendation_id > 0)
			{
				$response = $this->recommendation_front_lib->processRecommendationMailer($listing_type,$recommendation_id);
				
				if($response == -1)
				{
					
					header('Location: '.SHIKSHA_HOME.'/shikshaHelp/ShikshaHelp/errorPage');
					exit();

				} else	{

					$listing_id = $response['listing_id'];
					$listing_url = $response['listing_url'];
					$coursePackType = $response['coursePackType'];

					/*
					 * Register click on recommendation (write server)
					 */				
					$this->recommendation_front_lib->registerClickOnRecommendation($recommendation_id,$listing_id,$listing_type);
					
					/*
					 * Redirect to listing
					 */
					if($fromMMM) {
						$fromIdentifier = "&fromRecoProdMailer=1";
					} else {
						$fromIdentifier = "&fromRecoMailer=1";
					}

					$extra_params = "?utm_source=shiksha&utm_medium=email&utm_campaign=recomailers".$fromIdentifier;
					
					if($coursePackType == GOLD_SL_LISTINGS_BASE_PRODUCT_ID OR $coursePackType == GOLD_ML_LISTINGS_BASE_PRODUCT_ID  OR $coursePackType == SILVER_LISTINGS_BASE_PRODUCT_ID) { // Only for paid courses.
						$extra_params .= "&apply=1";
					}
						
					if($downloadBrochure == 1) {
						$extra_params .= "&download=1";
					}			
					
					header( "HTTP/1.1 301 Moved Permanently" );
					header('Location: '.$listing_url.$extra_params);
					exit();
				}
			}
			else 
			{
				header('Location: '.SHIKSHA_HOME.'/shikshaHelp/ShikshaHelp/errorPage');
				exit();
			}
	 	} catch(Exception $e) {
			//
		}
	}
	
	function registerFeedback($email,$mailerId,$mailId,$feedbackType,$isAbroad)
	{
		list($text,$mailerId) = explode('-',$mailerId);
		$this->load->model('user/usermodel');
		$userId = $this->usermodel->getUserIdByEmail($email, True);
		if(!empty($userId)) {
			$this->load->model('recommendation_model');
			$this->recommendation_model->registerFeedback($userId,$mailerId,$mailId,$feedbackType);
		}
		
		$redirectURL = "";
		if($isAbroad) {
			$redirectURL = SHIKSHA_STUDYABROAD_HOME;
		}
		else {
			$redirectURL = SHIKSHA_HOME_URL;
		}
		
		if($feedbackType == 'YES') {
			echo "<script>alert('Thanks! We are glad you are happy with our recommendations. You may continue to browse our site now.');location.href = '".$redirectURL."' ;</script>";
		}
		elseif ($feedbackType  == 'NO') {
			echo "<script>alert('Thanks for your valuable feedback. Our team will look into this.');location.href = '".$redirectURL."' ;</script>";
		}
	}

	function processMailer($listing_type,$recommendation_id,$hash,$fromMMM=0,$downloadBrochure=0)
	{
		try {
			$recommendation_id = (int) $recommendation_id;
			
			if(($listing_type == 'institute' || $listing_type == 'course') && $recommendation_id > 0)
			{
				$response = $this->processAbroadRecommendationMailer($listing_type,$recommendation_id,$hash);
				
				if($response == -1)
				{				
					header('Location: '.SHIKSHA_HOME.'/shikshaHelp/ShikshaHelp/errorPage');
					exit();
				}
				else
				{
					$listing_id = $response['listing_id'];
					$listing_url = $response['listing_url'];
					$user_cookie = $response['user_cookie'];
					$categoryID = $response['categoryID'];
					$course_id = $response['course_id'];
					$course_url = $response['course_url'];
					$coursePackType = $response['coursePackType'];
					
					/**
					 * If course not found, set listing type to institute
					 * so that user can be redirected to institute page
					 */ 
					if(!$course_id) {
						$listing_type = 'institute';
					}

					/*
					 * Register click on recommendation (write server)
					 */
					
					$this->recommendation_front_lib->registerClickOnRecommendation($recommendation_id,$listing_id,$listing_type);
					
					/*
					 * Auto-login user
					 */
					if($user_cookie != '-1')
					{
						setcookie('user',$user_cookie,0,'/',COOKIEDOMAIN);
					}
					
					/**
					 * If course URL is available, redirect to course page
					 */ 
					if($listing_type == 'institute') {
						$listing_url = $course_url ? $course_url : $listing_url;
					}
					
					/*
					 * Redirect to listing
					 */
					if($fromMMM) {
						$fromIdentifier = "&fromRecoProdMailer=1";
					}
					else {
						$fromIdentifier = "&fromRecoMailer=1";
					}
					
					//  Added by Amit K for ticket 870..
					// if($coursePackType == 1 OR $coursePackType == 2) // Only for paid courses.
					if($coursePackType == GOLD_SL_LISTINGS_BASE_PRODUCT_ID OR $coursePackType == GOLD_ML_LISTINGS_BASE_PRODUCT_ID  OR $coursePackType == SILVER_LISTINGS_BASE_PRODUCT_ID) // Only for paid courses.
						$extra_params = "?apply=1&cat=".$categoryID.$fromIdentifier."&utm_source=shiksha&utm_medium=email";
					else
						$extra_params = "?utm_source=shiksha&utm_medium=email";
						
					if($downloadBrochure == 1) {
						$extra_params .= "&download=1";
					}
					
					$extra_params .= '&utm_campaign=recomailers';
					
					// error_log("AMIT Pack_type: ".$coursePackType.", cid: ".$course_id.", listing_url: ".$listing_url);
					
					header( "HTTP/1.1 301 Moved Permanently" );
					header('Location: '.$listing_url.$extra_params);
					// header('Location: '.$course_url.$extra_params);
					exit();
				}
			}
			else 
			{
				header('Location: '.SHIKSHA_HOME.'/shikshaHelp/ShikshaHelp/errorPage');
				exit();
			}
		} catch(Exception $e) {
			//
		}
	}

	/*******************************************
	 * RESPONSE PROCESSING FUNCTIONS
	 ******************************************/
	
	function processAbroadRecommendationMailer($listing_type, $recommendation_id, $hash)
	{		
		if($recommendation_id)
		{
			$this->load->model('recommendation_model');
			$recommendation_details = $this->recommendation_model->getRecommendationDetails($recommendation_id);
		
			if($recommendation_details)
			{
				if($listing_type == 'course' && !intval($recommendation_details->courseID)) {
					$listing_type = 'institute';
				}
				
				
				$listing_id = $listing_type == 'institute' ? $recommendation_details->instituteID : $recommendation_details->courseID; 
				
				/*
				 * Get user auto-login cookie
				 */
			
				$user_email = $recommendation_details->email;
				$user_password = $recommendation_details->password;
				$user_creation_date = $recommendation_details->usercreationDate;
				$user_random_key = $recommendation_details->randomkey;
				
				$user_hash = sha1($user_email.";".$user_creation_date.";".$user_random_key);
				
				$user_cookie_value = -1;
				
				if($user_hash == $hash)
				{
					$user_cookie_value = rh_getUserLoginCookie($user_email,$user_password);
				}
			
				/*
				 * Get Listing URL to be sent back to front controller
				 */
				
				$listing_url = $listing_type == 'institute' ? $recommendation_details->institute_url : $recommendation_details->course_url;
				if(!$listing_url)
				{
					$listing_details_for_url = $this->recommendation_model->getListingDetailsForSeoURL($listing_id,$listing_type);
				
					$locationArray = array();
					$locationArray[0] = $listing_details_for_url['city']."-".$listing_details_for_url['country'];
					
					$optionalArgs = array();
					$optionalArgs['location'] = $locationArray;
					$optionalArgs['institute'] = $listing_details_for_url['institute_name'];
                                        // error_log("AMIT listing_type - ".$listing_type);
					if($listing_type == 'institute')
					{
						$listing_url = getSeoUrl($listing_details_for_url['institute_id'],'institute',$listing_details_for_url['institute_name'],$optionalArgs);
					}	     
					else 
					{       	
						$listing_url = getSeoUrl($listing_details_for_url['course_id'],'course',$listing_details_for_url['course_name'],$optionalArgs);
					}
				}
                                
                //  Added by Amit K for ticket 870 |  getting the course info as we have to make it the response of the Course
                if($listing_type == 'institute')
                {
                        if(!is_array($optionalArgs) OR count($optionalArgs) <= 1) {
                            $listing_details_for_url = $this->recommendation_model->getListingDetailsForSeoURL($listing_id,$listing_type);

                            $locationArray = array();
                            $locationArray[0] = $listing_details_for_url['city']."-".$listing_details_for_url['country'];

                            $optionalArgs = array();
                            $optionalArgs['location'] = $locationArray;
                            $optionalArgs['institute'] = $listing_details_for_url['institute_name'];

                        }                                        
                        $course_url = $recommendation_details->course_url;
						
                        if(!$course_url && $recommendation_details->courseID) {
							
                            $course_details_for_url = $this->recommendation_model->getListingDetailsForSeoURL($recommendation_details->courseID, 'course');
							
                            // error_log("AMIT course_details_for_url - ".print_r($course_details_for_url, true));
                            $course_url = getSeoUrl($recommendation_details->courseID, 'course', $course_details_for_url['course_name'],$optionalArgs);
                        }
                }
                
				$response = array(
                                    'listing_id'  => $listing_id,
                                    'listing_url' => $listing_url,
                                    'categoryID' => $recommendation_details->categoryID,
                                    'user_cookie' => $user_cookie_value,
                                    'course_id' => $recommendation_details->courseID,
                                    'coursePackType' => $recommendation_details->coursePackType,
                                    'course_url' => $course_url
							     );
													
				return $response;				 	
			}
			else 
			{
				return array();
			}
		}
		else 
		{
			return array();
		}
	}
	

}

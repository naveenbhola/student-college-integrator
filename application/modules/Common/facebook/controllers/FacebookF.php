<?php
	/**
	 * CodeIgniter Facebook Connect Graph API Library
	 *
	 * Author: Graham McCarthy (graham@hitsend.ca) HitSend inc. (http://hitsend.ca)
	 *
	 * VERSION: 1.0 (2010-09-30)
	 * LICENSE: GNU GENERAL PUBLIC LICENSE - Version 2, June 1991
	 *
	 **/

	class FacebookF extends MX_Controller {
		//declare private variables
		private $_obj;
		private $_api_key		= NULL;
		private $_secret_key	= NULL;

		//declare public variables
		public 	$user 			= NULL;
		public 	$user_id 		= FALSE;

		public $fbLoginURL 	= "";
		public $fbLogoutURL = "";

		public $fb 			= FALSE;
		public $fbSession	= FALSE;
		public $appkey		= 0;

		//constructor method.
		var $userStatus = '';
		function init(){
			$this->load->library(array('facebook','Register_client','facebook_client','alerts_client','Listing_client'));
			if($this->userStatus == "")
			$this->userStatus = $this->checkUserValidation();

		}

                function setCookieVal(){
                    $value = $this->input->post('cookieValSet');
                    setcookie('facebookData',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
                }


                function postOnFacebook($message=''){
	           $this->init();
		   $userInfo = $this->userStatus;
		   $userId = $userInfo['0']['userid'];
                   if($this->input->post('action'))
                     $action = $this->input->post('action');
                   //else
                    //$action = 'registration';
                    
                   $fbClient = new facebook_client();
                   $facebook = new Facebook();
		   if(($action == 'request-E-Brochure')){
		      $courseId = $this->input->post('listingTypeId');

		      $ListingClientObj = new Listing_client();
		      $instituteId =$ListingClientObj->getInstituteIdForCourseId('1',$courseId);
		      $fbClient->saveFacebookFollowInfo($userId,$instituteId,$action);
		   }
		   if(($action == 'followInstitute')){
			$listingType = $this->input->post('listingType');
			$ListingClientObj = new Listing_client();
			if($listingType == 'institute'){
			$instituteId = $this->input->post('listingTypeId');
			$courseId =$ListingClientObj->getCourseIdForInstituteId('1',$instituteId);
			}
			if($listingType == 'course'){
			$courseId = $this->input->post('listingTypeId');
                        $instituteId = $ListingClientObj->getInstituteIdForCourseId('1',$courseId);
                        }
			//$this->updateFacebookFlag($action,$instituteId);
			$fbClient->saveFacebookFollowInfo($userId,$instituteId,$action);
		   }

		   if(($action == 'followArticle')){
			$blogId =  $this->input->post('blogId');
			$boardId = $this->input->post('boardId');
			//$this->updateFacebookFlag($action,$blogId);
			$fbClient->saveFacebookFollowInfo($userId,$boardId,$action);
		   }

		   if(($action != 'request-E-Brochure')&&($action != 'followInstitute')&&($action != 'followArticle')){
                   //$email = $this->input->post('email');
		   $access_token = $this->input->post('accessToken');
                   $cookie = isset($_COOKIE['user'])?$_COOKIE['user']:'';
		   }

                   if((isset($_COOKIE['facebookData']) && $_COOKIE['facebookData']!='' && $userId>0)||(($action == 'request-E-Brochure')||($action == 'followInstitute')||($action == 'followArticle'))){


			if(($action != 'request-E-Brochure')&&($action != 'followInstitute')&&($action != 'followArticle')&& $_COOKIE['facebookData']!='registration'){
				$facebookData = $_COOKIE['facebookData'];
				$fbDataArr = explode("##",$_COOKIE['facebookData']);
			if($fbDataArr[0]=="Post" || $fbDataArr[0]=="level")
                          $fbData = $fbClient->getDataForFacebook(1,$userId,$fbDataArr[0]);
                        else if($fbDataArr[0]=="bestanswer")
                          $fbData = $fbClient->getDataForFacebook(1,$userId,$fbDataArr[0],$fbDataArr[1],$fbDataArr[2]);
                        else
                          $fbData = $fbClient->getDataForFacebook(1,$userId,$fbDataArr[0],$fbDataArr[1],$fbDataArr[2]);
			}

			if(($action == 'request-E-Brochure')||($action == 'followInstitute')||($action == 'followArticle')){

			    $autoShare =  $this->input->post('autoShare');

			    if($autoShare == '1'){
			    if($action == 'request-E-Brochure'){
				$settingName = 'publishRequestEBrochure';
			    }
			    if($action == 'followInstitute'){
				$settingName = 'publishInstituteUpdates';
			    }
			    if($action == 'followArticle'){
				$settingName = 'publishArticleFollowing';
			    }
			    $this->editFbPostSettings($userId,$settingName,'1');
			    }

			   if($action!='followArticle'){

			    $fbData = $fbClient->getDataForFacebook(1,$userId,$action,'','',$instituteId,$courseId);
			    $access_token = $fbData['7']['access_token'];
			    }
			    if($action =='followArticle')
			    $fbData = $fbClient->getDataForFacebook(1,$userId,$action,$blogId,'','','');
			}

                        if($_COOKIE['facebookData'] == 'registration' &&  $action=='')
                                $fbData = array(array('type'=>'registration'));
                                
                        try {
		             $facebookLogData = '';
                             if(is_array($fbData)){

				if(($action != 'request-E-Brochure')&&($action != 'followInstitute')&&($action != 'followArticle')){
                                    $type = $fbData[0]['type'];
				}else{
				    $type = $action;
				}
                                    switch ($type){

                                    case 'question':
                                         $message = "asked a question at Shiksha Café on Shiksha.com - India's leading education and career website.";
                                         $attachment= array("access_token" => $access_token,
                                            "message" => $message,
                                            "name" => "Q: ".$fbData[0]['msgTxt'],
                                            "link" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['msgTxt']),
					    "picture" => SHIKSHA_HOME.'/public/images/90x90_3.jpg',
					    "properties" => array("In" =>  array(
							      "text" => "Shiksha.com's - ".$fbData[0]['name']." Channel",
							      "href" => SHIKSHA_ASK_HOME."/".$fbData[0]['categoryId']
							      )
							  ),
                                             "actions" => array("name" => "Answer It",
							       "link" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['msgTxt'])
							      )
				      );
                                    $facebookLogData = $message."##"."Q: ".$fbData[0]['msgTxt'];
                                    break;

                                    case 'post':
                                         $fromOthers = $fbData[0]['fromOthers'];
                                         if($fromOthers == 'discussion')  $message = "started a discussion at Shiksha Café on Shiksha.com - India's leading education and career website.";
                                         else if($fromOthers == 'announcement')  $message = "posted an announcement at Shiksha Café on Shiksha.com - India's leading education and career website.";

                                         $attachment = array("access_token" => $access_token,
                                                            "message" => $message,
                                                            "name" => $fbData[0]['msgTxt'],
                                                            "link" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['msgTxt']),
                                                            "picture" => SHIKSHA_HOME.'/public/images/90x90_3.jpg',
                                                            "description" => $fbData[0]['postDesc'],
                                                            "properties" => array("In" =>  array(
                                                                              "text" => "Shiksha.com's - ".$fbData[0]['name']." Channel",
                                                                              "href" => SHIKSHA_ASK_HOME."/".$fbData[0]['categoryId']
                                                                              )
                                                                          ),
                                                            "actions" => array("name" => "See More",
                                                                               "link" => SHIKSHA_ASK_HOME
							      )
                                                      );
                                         $facebookLogData = $message."##".$fbData[0]['msgTxt'];
                                         break;

                                    case 'answer' :
                                         $level = ($fbData[0]['level']=='')?'Beginner':$fbData[0]['level'];
                                         $textVal = ($level=="Advisor")?"an":"a";
                                         $message = $textVal." ".$level." at Shiksha Café on Shiksha.com - India's leading education and career website, answered a student's question.";
                                         $attachment= array("access_token" => $access_token,
                                                            //"privacy"=>array("value"=> "CUSTOM",  "friends"=> "EVERYONE"),
                                                            "name" => "Q: ".$fbData[0]['questionText'],
                                                            "message" => $message,
                                                            "link" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['questionText']),
                                                            "picture" => SHIKSHA_HOME.'/public/images/90x90_3.jpg',
                                                            "description" => "A: ".$fbData[0]['msgTxt'],
                                                            "properties" => array("In" =>  array(
                                                                              "text" => "Shiksha.com's - ".$fbData[0]['name']." Channel",
                                                                              "href" => SHIKSHA_ASK_HOME."/".$fbData[0]['categoryId']
                                                                              )
                                                                          ),
                                                            "actions" => array("name" => "Help other students",
							                       "link" => SHIKSHA_ASK_HOME
							      )
                                                      );
                                          $facebookLogData = $message."##"."Q: ".$fbData[0]['questionText']."##"."A: ".$fbData[0]['msgTxt'];
                                         break;

                                    case 'comment':
                                         $message = "commented on a post at Shiksha Café on Shiksha.com - India's leading education and career website";
                                         $attachment = array("access_token" => $access_token,
                                                            "message" => $message,
                                                            "name" => "Q: ".$fbData[0]['questionText'],
                                                            "link" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['questionText']),
                                                            "picture" => SHIKSHA_HOME.'/public/images/90x90_3.jpg',
                                                            "description" => $fbData[0]['msgTxt'],
                                                            "properties" => array("In" =>  array(
                                                                              "text" => "Shiksha.com's - ".$fbData[0]['name']." Channel",
                                                                              "href" => SHIKSHA_ASK_HOME."/".$fbData[0]['categoryId']
                                                                              )
                                                                          ),
                                                            "actions" => array("name" => "Join the discussion",
							                       "link" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['questionText'])
							      )
                                                      );
                                          $facebookLogData = $message."##"."Q: ".$fbData[0]['questionText']."##".$fbData[0]['msgTxt'];
                                        break;

                                    case 'postcomment':
                                         $fromOthers = $fbData[0]['fromOthers'];
                                         if($fromOthers == 'discussion')  $message = "commented on a discussion post at Shiksha Café on Shiksha.com - India's leading education and career website";
                                         else if($fromOthers == 'announcement')  $message = "commented on a announcement at Shiksha Café on Shiksha.com - India's leading education and career website";

                                         $attachment = array("access_token" => $access_token,
                                                            "message" => $message,
                                                            "name" => $fbData[0]['postText'],
                                                            "link" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['postText']),
                                                            "picture" => SHIKSHA_HOME.'/public/images/90x90_3.jpg',
                                                            "description" => $fbData[0]['msgTxt'],
                                                            "properties" => array("In" =>  array(
                                                                              "text" => "Shiksha.com's - ".$fbData[0]['name']." Channel",
                                                                              "href" => SHIKSHA_ASK_HOME."/".$fbData[0]['categoryId']
                                                                              )
                                                                          ),
                                                            "actions" => array("name" => "Join the discussion",
							                       "link" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['questionText'])
							      )
                                                      );
                                          $facebookLogData = $message."##".$fbData[0]['questionText']."##".$fbData[0]['msgTxt'];
                                        break;

                                        case 'level':
                                         $message =  'an expert on Shiksha has just been promoted to '.$fbData[0]['level'].' Level';
                                         if($fbData[0]['level']=='Trainee') $description = "As you keep contributing on Ask and Answer, you will soon reach the Advisor level and secure a position in our Panel of Experts. Keep up the good work!";
                                         else if($fbData[0]['level']=='Chief Advisor') $description = "The community has awarded you a \"Orange Star\" and a senior position in our Panel of Experts. We appreciate your invaluable contribution to the community and look forward to more of it.";
                                         else if($fbData[0]['level']=='Advisor') $description = "The community has awarded you a \"Grey Star\" and a senior position in our Panel of Experts. We appreciate your invaluable contribution to the community and look forward to more of it.";
				         else if($fbData[0]['level']=='Senior Advisor') $description = "The community has awarded you a \"Blue Star\" and a senior position in our Panel of Experts. We appreciate your invaluable contribution to the community and look forward to more of it.";
				         else if($fbData[0]['level']=='Lead Advisor')  $description ="The community has awarded you a \"Pink Star\" and a senior position in our Panel of Experts. We appreciate your invaluable contribution to the community and look forward to more of it.";
				         else if($fbData[0]['level']=='Principal Advisor') $description= "The community has awarded you a \"Green Star\" and a senior position in our Panel of Experts. We appreciate your invaluable contribution to the community and look forward to more of it.";
                                         //$message = 'Your level is upgraded to '.$fbData[0]['level'];
                                         $attachment = array("access_token" => $access_token,
                                                            "message" => $message,
                                                            "name" => 'Congratulations '.$fbData[0][displayname].', you are now '.$fbData[0]['level'],
                                                            //"link" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['postText']),
                                                            "picture" => SHIKSHA_HOME.'/public/images/communityAwards.jpg',
							                                "link" => SHIKSHA_HOME."/getUserProfile/".$fbData[0]['displayname'],
                                                            "description" => $description,
                                                            "properties" => array("In" =>  array(
                                                                              "text" => "Shiksha.com",
                                                                              "href" => SHIKSHA_ASK_HOME
                                                                              )
                                                                          ),
                                                             "actions" => array("name" => "View My Shiksha Profile",
							                        "link" => SHIKSHA_HOME."/getUserProfile/".$fbData[0]['displayname']
							      )
                                                      );
                                          $facebookLogData = $message."##".$fbData[0]['displayname']."##".$fbData[0]['level'];
                                        break;
                                        case 'bestanswer':
                                         $level = ($fbData[0]['level']=='')?'Beginner':$fbData[1]['level'];
                                         $textVal = ($level=="Advisor")?"an":"a";
                                         $message = $textVal.' '.$level.' at Shiksha Café on Shiksha.com – India’s leading education and career website, has received the Best Answer rating for their answer. Congratulations '.$fbData[1][displayname].'!';
                                         //$message = 'your answer '.$fbData[1][msgTxt].' is selected as The Best Answer by '.$fbData[0][displayname];
                                         $attachment = array("access_token" => $access_token,
                                                            "name" => "Q: ".$fbData[0]['msgTxt'],
                                                            "message" => $message,
                                                            "link" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['msgTxt']),
                                                            //"name" => $fbData[0]['postText'],
                                                            //"link" => getSeoUrl($fbData[0]['threadId'],'question',$fbData[0]['postText']),
                                                            "picture" => SHIKSHA_HOME.'/public/images/90x90_3.jpg',
                                                            //"description" => $fbData[0]['msgTxt'],
                                                            "description" => "Best Answer: ".$fbData[1]['msgTxt'],
                                                            "properties" => array("In" =>  array(
                                                                              "text" => "Shiksha.com's - ".$fbData[0]['name']." Channel",
                                                                              "href" => SHIKSHA_ASK_HOME."/".$fbData[0]['categoryId']
                                                                              )
                                                                          ),
                                                             "actions" => array("name" => "View My Answers",
							                        "link" => SHIKSHA_HOME."/getUserProfile/".$fbData[1]['displayname']."/Answer"
							      )
                                                      );
                                         $facebookLogData = $message."##".$fbData[0]['msgTxt']."##".$fbData[1]['msgTxt'];
                                        break;

				     case 'request-E-Brochure':

                                         $message = "has just requested an e-brochure on Shiksha.com";
					 $imageSrc = $fbData['6'][0]['image'];
					 if(empty($imageSrc)){
					 $imageSrc = SHIKSHA_HOME.'/public/images/fb_default.jpg';
					 }
					 $instituteName = $fbData[0][0]['title'];
					 $city = '';
					 if(!empty($fbData[1][0]['city'])){
						$city = $fbData[1][0]['city'];
					 }
					 $locality = '';
					 if(!empty($fbData[1][0]['locality'])){
						$locality = $fbData[1][0]['locality'];
					 }
					 $location = array("location"=>array($locality, $city));
					 $link = getSeoUrl($instituteId,'institute',$instituteName,$location);
					 $whyJoin = $fbData[8]['whyJoin'];
					 $featureString = $this->getfeatureString($fbData[4],$fbData[5],$whyJoin);
                                         $attachment= array("access_token" => $access_token,
                                                            "name" => $instituteName." , ".$city,
                                                            "message" => $message,
                                                            "picture" => $imageSrc,
                                                            "link" => $link,
                                                            "description" => $featureString,
                                                            "properties" => array("In" =>  array(
                                                                              "text" => "Shiksha.com's - ".$fbData[2][0]['categoryName']." Channel",
                                                                           "href" => constant("SHIKSHA_".strtoupper($fbData[2][0]['categoryUrl'])."_HOME")
                                                                              )
                                                                          ),
							    "actions" => array("name" => "View this institute",
									       "link" => $link
									       )
                                                      );

                                          $facebookLogData = $message."##"."Request-E_Brochure"."##".$instituteId."--".$courseId;
					   break;
				     case 'followInstitute':
					   $message = "has just started following an Institute on Shiksha.com";
					 $imageSrc = $fbData['6'][0]['image'];
					 if(empty($imageSrc)){
					 $imageSrc = SHIKSHA_HOME.'/public/images/fb_default.jpg';
					 }
					 $instituteName = $fbData[0][0]['title'];
					 $city = '';
					 if(!empty($fbData[1][0]['city'])){
						$city = $fbData[1][0]['city'];
					 }
					 $locality = '';
					 if(!empty($fbData[1][0]['locality'])){
						$locality = $fbData[1][0]['locality'];
					 }
					 $location = array("location"=>array($locality, $city));
					 $link = getSeoUrl($instituteId,'institute',$instituteName,$location);
					 $whyJoin = $fbData[8]['whyJoin'];
					 $featureString = $this->getfeatureString($fbData[4],$fbData[5],$whyJoin);
                                         $attachment= array("access_token" => $access_token,
                                                            "name" => $instituteName." , ".$city,
                                                            "message" => $message,
                                                            "picture" => $imageSrc,
                                                            "link" => $link,
                                                            "description" => $featureString,
                                                            "properties" => array("In" =>  array(
                                                                              "text" => "Shiksha.com's - ".$fbData[2][0]['categoryName']." Channel",
                                                                           "href" => constant("SHIKSHA_".strtoupper($fbData[2][0]['categoryUrl'])."_HOME")
                                                                              )
                                                                          ),
							    "actions" => array("name" => "View this institute",
									       "link" => $link
									       )
                                                      );

                                          $facebookLogData = $message."##"."FollowInstitute"."##".$instituteId."--".$courseId;

					   break;

					  case 'followArticle':
					 $message = "has just started following an Article on Shiksha.com";
					 $imageSrc = $fbData['0']['0']['imageUrl'];
					 if(empty($imageSrc)){
					 $imageSrc = SHIKSHA_HOME.'/public/images/fb_default.jpg';
					 }
					 $blogId = $fbData[0][0]['blogId'];
					 $blogName = $fbData[0][0]['blogTitle'];
					 $blogUrl = $fbData[0][0]['url'];
					 $access_token = $fbData[1]['access_token'];
                                         $attachment= array("access_token" => $access_token,
							    "privacy"=>array("value"=> "CUSTOM",  "friends"=> "SELF"),
                                                            "name" => $blogName,
                                                            "message" => $message,
                                                            "picture" => $imageSrc,
                                                            "link" => $blogUrl,
                                                      );
					 $facebookLogData = $message."##"."FollowArticle"."##".$blogId;

					   break;
                                           
                                         case 'registration':
                                         $message = " Has just joined Shiksha.com, India's premier education portal.";
					 $attachment= array("access_token" => $access_token,
							    "name" => 'Shiksha.com – Stop Following, Start Exploring',
                                                            "link" => SHIKSHA_HOME,
                                                            "message" => $message,
                                                            "picture" => SHIKSHA_HOME.'/public/images/shiksha-logo-fb.jpg',
                                                            "link" => SHIKSHA_HOME,
                                                            "description" => 'Shiksha helps aspiring students find the right institute and course. On Shiksha Café, students can get their career queries answered by education experts. Join today, start exploring.',
                                                            "properties" => array("Know more" =>  array(
                                                                              "text" => "What's Shiksha?",
                                                                              "href" => SHIKSHA_HOME
                                                                              )
                                                                          ),

                                                            "actions" => array("name" => "Ask a question",
									       "link" => SHIKSHA_ASK_HOME,
                                                                               )
                                                  );
					 $facebookLogData = $message."##"."Registration from Header FacebookConnect";

					   break;
                                    }


		}

		if($facebook->api('/me/feed', 'post', $attachment)){

		  //After successfully posting on the Wall, make a log entry in the DB for the entry made ( IP Address, userId, content, sessionKey)
		  if($userId>0){
			$this->load->library(array('message_board_client'));
			$msgbrdClient = new Message_board_client();
			$topicDetails = $msgbrdClient->setFBWallLog(1,$userId,$access_token,$facebookLogData,S_REMOTE_ADDR);
		  }
			echo $type;
		  //End code for Facebook Log entry
		}
	    }
	    catch(Exception $e) {
		if($userId>0){
		$this->deleteAccessToken($userId);
		}
		$exceptionMessage = $e." =>".$userId."==>".$type;
		$this->exceptionLogOfFBPost($exceptionMessage);
		error_log($e);
		//echo "Could not be posted on your Facebook wall. Please try again later.<br />";
		echo $type;
	    }
           }

          }

	function getfeatureString($salientFeatures,$courseAttributes,$whyJoin){

	$fString = array();
	$featureCountVar = 0;

		if(!empty($courseAttributes)){
		$cAttr = array();
		foreach($courseAttributes as $attributes)
		$cAttr[$attributes['attributeName']] = $attributes['attributeValue'];
			if(isset($cAttr['AICTEStatus'])&&($cAttr['AICTEStatus'] == 'yes')){
				$featureCountVar++;
				$fString []='AICTE Approved';
			}
			if(isset($cAttr['AffiliatedToIndianUni'])&&($cAttr['AffiliatedToIndianUni'] == 'yes')){
				if(!empty($cAttr['AffiliatedToIndianUniName'])){
				$featureCountVar++;
				$fString []=$cAttr['AffiliatedToIndianUniName'];
				}
			}
			if(isset($cAttr['AffiliatedToForeignUni'])&&($cAttr['AffiliatedToForeignUni'] == 'yes')){
				if(!empty($cAttr['AffiliatedToForeignUniName'])){
				$featureCountVar++;
				$fString []=$cAttr['AffiliatedToForeignUniName'];
				}
			}
			if(isset($cAttr['AffiliatedToAutonomous'])&&($cAttr['AffiliatedToAutonomous'] == 'yes')){
				$featureCountVar++;
				$fString []='Autonomous Institute';
			}
			if(isset($cAttr['AffiliatedToDeemedUni'])&&($cAttr['AffiliatedToDeemedUni'] == 'yes')){
				$featureCountVar++;
				$fString []='Deemed University';
			}
		}

		if(!empty($salientFeatures)){
		foreach($salientFeatures as $features)
		$sAttr[$features['salientFeatureName']] = $features['salientFeatureValue'];
			if(isset($sAttr['jobAssurance'])&&($sAttr['jobAssurance'] == 'assurance')){
				$featureCountVar++;
				$fString []='100% Assured Placement ';
			}
			if(isset($sAttr['jobAssurance'])&&($sAttr['jobAssurance'] == 'record')){
				$featureCountVar++;
				$fString []='100% Placement Record';
			}
			if(isset($sAttr['jobAssurance'])&&($sAttr['jobAssurance'] == 'guarantee')){
				$featureCountVar++;
				$fString []='100% Placement Guarantee';
			}
			if(isset($sAttr['freeLaptop'])&&($sAttr['freeLaptop'] == 'yes')){
				$featureCountVar++;
				$fString []='Free Laptop ';
			}
			if(isset($sAttr['acCampus'])&&($sAttr['acCampus'] == 'yes')){
				$featureCountVar++;
				$fString []='AC Campus ';
			}
			if(isset($sAttr['wifi'])&&($sAttr['wifi'] == 'yes')){
				$featureCountVar++;
				$fString []='Wifi Campus ';
			}
			if(isset($sAttr['hostel'])&&($sAttr['hostel'] == 'yes')){
				$featureCountVar++;
				$fString []='Hostel Facility ';
			}
		}

		$fString = implode(" | ",$fString);
		if($featureCountVar<'5'){
			$whyJoin = strip_tags($whyJoin);
			$starCheck = strpos($whyJoin,"*");
			if(isset($starCheck)){
			$whyJoin = preg_replace('/\*/','|',$whyJoin);
			}
			$whyJoin = (strlen($whyJoin)>100)?substr($whyJoin,0,100)."...":$whyJoin;
			$fString = $fString."|".$whyJoin;
		}

		//error_log(print_r($fString,true),3,'/home/aakash/Desktop/error.log');
		return $fString;
	}

	function editFbPostSettings($userId='', $columnName='', $attributeValue=''){

	$this->init();
	$fbClient = new facebook_client();
	$this->load->library('cacheLib');
	$cacheLib = new cacheLib();
	$cacheLib->clearCache('user');
	if($userId == ''){
		$userInfo = $this->userStatus;
		$userId = $userInfo['0']['userid'];

		$columnName = $this->input->post('columnName');
		$attributeValue = $this->input->post('columnValue');
		$response = $fbClient->editFbPostSettings($userId, $columnName, $attributeValue);
		if($response['result']=='SUCCESS'){
			echo "1";
		}else{
			echo "0";
		}
	}else{
		$response = $fbClient->editFbPostSettings($userId, $columnName, $attributeValue);
		//echo "<pre>";print_r($response);echo "</pre>";
		return $response;
	}

	}

	//Prajul function Starts
	function getAccountSetttingInfo(){
            $this->init();
            $action = $this->input->post('action');
            $userId = $this->userStatus[0][userid];
            $fbClient = new facebook_client();
            $val = $fbClient->getAccountSetttingInfo($action,$userId);
            echo $val[result];
        }

	function donShowAgain(){
            $this->init();
            $cookieAuto = $this->input->post('cookieAuto');
            $userId = $this->userStatus[0][userid];
            $fbClient = new facebook_client();
            $val = $fbClient->donShowAgain($cookieAuto,$userId);

        }

	function saveAccessToken_AnA($userId='',$access_token=''){
	$this->init();
	$email = $this->input->post('email');
                    $uid = $this->input->post('userId');
                    $accessToken = $this->input->post('accessToken');
                    $automaticFShare = $this->input->post('automaticFShare');
                    $cookieAuto = $this->input->post('cookieAuto');
                    $fbClient = new facebook_client();
                    $val = $fbClient->saveAccessToken_AnA($uid,$accessToken,$email,$automaticFShare,$cookieAuto);
                    echo $val[result];
	}

	function getAccessToken_AnA($userId=''){
            $this->init();
            if($this->userStatus=='false'){
                echo 'noresult';
            }else{
                $userId = $this->userStatus[0][userid];
                $fbClient = new facebook_client();
                $data = $fbClient->getAccessToken_AnA($userId);
                echo $data[0][access_token];
            }
	}

	//Prajul function ends
	function getFbPostSettings($userId='',$columnName=''){
	$this->init();
	$fbClient = new facebook_client();
	//print_r($columnName);
	$result = $fbClient->getFbPostSettings($userId, $columnName);
	return $result ;
	}

	function saveAccessToken($userId='',$accessToken=''){
	$this->init();
	$userId = $this->input->post('userId');
	$userInfo = $this->userStatus;
	if(isset($userInfo['0']['userid']))
	$userId = $userInfo['0']['userid'];
	$accessToken = $this->input->post('accessToken');
	$fbClient = new facebook_client();
	return $fbClient->saveAccessToken($userId,$accessToken);
	}

	function getAccessToken($userId){
	$this->init();
	$userIdAjax = $this->input->post('userId');
	if(isset($userIdAjax)&&(!empty($userIdAjax))){
	$userId = $userIdAjax;
	}
	$fbClient = new facebook_client();
	$token =  $fbClient->getAccessToken($userId);
		if(isset($userIdAjax)){
		echo $token['0']['access_token'];
		}else{
		return $token;
		}
	}


	function checkForAccessToken($userId=''){
	$this->init();
	if($userId ==''){
	$userId = $this->input->post('userId');
	}
	//$userId = '23456';
	$fbClient = new facebook_client();
	$result = $fbClient->getAccessToken($userId);
	//error_log(print_r($result,true),3,'/home/aakash/Desktop/error.log');
	if($userId ==''){
		if(empty($result)){
		echo '0';
		}else{
		echo '1';
		}
	}else{
	return $result;
	}

	}

	function deleteAccessToken($userId='',$var='echoResult'){
	$this->init();	
	if($userId ==''){
	$userId = $this->input->post('userId');
	}
	$fbClient = new facebook_client();
	$result = $fbClient->deleteAccessToken($userId);
	if($var != 'echoResult'){
	return $result;	
	}else{
	//echo $result;
	}
	}
	function saveFacebookFollowInfo(){
	$this->init();
	$fbClient = new facebook_client();
	$userInfo = $this->userStatus;
	$fbPostSetting = '';
        if(isset($userInfo[0])){
	$userId = $userInfo[0]['userid'];	
	}
	if(!empty($userId))
	$access_token = $this->checkForAccessToken($userId);
	if(!empty($access_token)){
	$access_token = $access_token['0']['access_token'];
	}

	$action = $this->input->post('action');
	if($action == 'request-E-Brochure'){
	$email = $this->input->post('email');
	$listingTypeId = $this->input->post('listingTypeId');
	$listingType = $this->input->post('listingType');

		if($listingType == 'course'){
		$ListingClientObj = new Listing_client();
		$actionId =$ListingClientObj->getInstituteIdForCourseId('1',$listingTypeId);
		}

		if($listingType == 'institute'){
		   $actionId = $listingTypeId;
		}
	$settingName = 'publishRequestEBrochure';
	}

	if($action == 'followInstitute'){
	$listingTypeId = $this->input->post('listingTypeId');
	$listingType = $this->input->post('listingType');

		if($listingType == 'course'){
		$ListingClientObj = new Listing_client();
		$actionId =$ListingClientObj->getInstituteIdForCourseId('1',$listingTypeId);
		}

		if($listingType == 'institute'){
		   $actionId = $listingTypeId;
		}

	$settingName = 'publishInstituteUpdates';
	}

	if($action == 'followArticle'){
	$actionId = $this->input->post('boardId');
	$settingName = 'publishArticleFollowing';
	}
	if(is_array($userInfo)&&isset($userInfo)){
	$userId = $userInfo['0']['userid'];
	$fbPostSetting = $userInfo['0'][$settingName];
	}

		$result = $fbClient->saveFacebookFollowInfo($userId,$actionId,$action);
		if($result['result'] == 'alreadySubscribedArticle'){
		$response = $result['result'];	
		}else{
		if(!empty($access_token)){
		//$fbPostSetting = $this->getFbPostSettings($userId,$settingName);
		if($fbPostSetting == '1'){
		$response = 'noFoverlay1';
		}
		if($fbPostSetting == '2'){
		$response = 'noFoverlay2';
		}
		if($fbPostSetting == '0'){
		$response = 'showFoverlay';
		}
	}else{
		$response = 'showFoverlay';

	}
		}

	echo $response;

	}
	function postToFacebookAjax(){
		$this->init();
		$userInfo = $this->userStatus;
		$userId = $userInfo['0']['userid'];
		$email = $this->input->post('email');
		$action = $this->input->post('action');
		if($action == 'request-E-Brochure'){
		$actionId = $this->input->post('listing_type_id');
		}
		$fbClient = new facebook_client();
		$fbClient->saveFacebookFollowInfo($userId,$actionId,$action);
		$this->postOnFacebook($userId,$actionId,$action);
		echo "1";
	}

	function showFaceboolLoginPopUp(){
	$url = "http://www.facebook.com/login.php?api_key=78832b6c915a8f69c324a76cd1c8fb92&v=1.0&next=http://".$_SERVER['SERVER_NAME']."/facebook/FacebookF/loginSuccess?user=\&&cancel_url=http://www.facebook.com/connect/login_failure.html&fbconnect=true&return_session=true&req_perms=read_stream,publish_stream,offline_access";
	header('location:'.$url);
	}

	function logInShiksha(){

	    $this->init();
            $appId =12;
            $email = $this->input->post('email');
            $displayname = $this->input->post('displayname');
            $firstname = $this->input->post('firstname');
            $addReqInfo = array();
            $register_client = new Register_client();

            $signedInUser = $register_client->getinfoifexists($appId,$email,'email');

            if(is_array($signedInUser)) {
                    $signedInUser = $register_client->getinfoifexists($appId,$email,'email');
                    $mdpassword = $signedInUser[0][mdpassword];
            } else {
                    $signedInUser = $register_client->checkAvailability($appId,$displayname,'displayname');

                    while($signedInUser == 1){
                        $displayname = $firstName . rand(1,1000);
                        $signedInUser = $register_client->checkAvailability($appId,$displayname,'displayname');
                    }
                    $password = 'shiksha@'. rand(1,1000000);
                    $mdpassword = sha256($password);
                    $userarray['appId'] = $appId;
                    $userarray['email'] = $email;
                    $userarray['password'] = $password;
                    $userarray['ePassword'] = $mdpassword;
                    $userarray['displayname'] = $displayname;
                    $userarray['firstname'] = $firstName;
                    $userarray['usergroup'] = 'veryshortregistration';
                    $userarray['quicksignupFlag'] = "requestinfouser";

                    $addResult = $register_client->adduser_new1($userarray);
                    $signedInUser = $register_client->getinfoifexists($appId,$email,'email');
                    $this->sendWelcomeMailToNewUser($email, $password,$addReqInfo,$addResult,$actiontype,$signedInUser);

            }
            $value = $email.'|'.$mdpassword. '|pendingverification';
            //setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
            echo $signedInUser[0]['userid'];

    }

    function cronForPostingUpdates(){
	$this->init();
	$fbClient = new facebook_client();

		$userInfo = $fbClient->getUserDetailsForEventUpdates(1);

		foreach($userInfo as $detail){
			$message = "Event Update From ".$detail['instituteName'].",".$detail['city'];

			if(!empty($detail['imgSrc']))
			$imageSrc = $detail['imgSrc'];
			else
			$imageSrc = SHIKSHA_HOME.'/public/images/fb_default.jpg';
			$location = array("location"=>array($detail['locality'], $detail['city']));
			$link = getSeoUrl($detail['instituteId'],'institute',$detail['instituteName'],$location);
			if(empty($detail['event_venue'])){
				$eventVenue = $detail['locality'].$detail['city'];
			}else{
				$eventVenue = $detail['event_venue'];
			}
			$captionString = "<b>Event:</b>".$detail['event_title'];
			$eventString = "<b>Where:</b>".$detail['start_date']."-".$detail['end_date']."<b>Venue:</b>".$eventVenue;
			$attachment= array("access_token" => $detail['access_token'],
					   "privacy"=>array("value"=> "CUSTOM",  "friends"=> "EVERYONE"),
								    "name" => $detail['instituteName'].",".$detail['city'],
								    "message" => $message,
								    "picture" => $imageSrc,
								    "link" => $link,
								    "caption" => $captionString,
								    "description" => $eventString
								    
							      );


			$facebookLogData = $message."##"."InstitutesUpdates"."##".$detail['instituteId'];
			$facebook = new Facebook();
			$thresholdCheck = $this->checkThresholdValue($detail['userId']);
			if($thresholdCheck['count']<FB_POST_THRESHOLD){
			try{
			if(($facebook->api('/me/feed', 'post', $attachment))){
			  //After successfully posting on the Wall, make a log entry in the DB for the entry made ( IP Address, userId, content, sessionKey)
			  if($detail['userId']>0){
				$this->load->library(array('message_board_client'));
				$msgbrdClient = new Message_board_client();
				$topicDetails = $msgbrdClient->setFBWallLog(1,$detail['userId'],$detail['access_token'],$facebookLogData,S_REMOTE_ADDR);
			  }
			}
		}catch(Exception $e) {
			if($detail['userId']>0){
			$this->deleteAccessToken($detail['userId']);
			}
			$exceptionMessage = $e." =>".$detail['userId']."==>InstituteUpdatesCRON";
	                $this->exceptionLogOfFBPost($exceptionMessage);
			error_log($e);
			echo $e;
		    }
		}
			//$facebook->api('/me/feed', 'post',$attachment );
		}
	

		$userInfo = $fbClient->getUserDetailsForArticlesUpdates(1);
		//echo "<pre>";print_r($userInfo);echo "</pre>";
		foreach($userInfo as $detail){
			$message = "Article Update From Shiksha.com";
			$link = $detail['blogUrl'];
			$Blogname = $detail['blog_title'];
			$articleImage = $detail['articleImage'];
			if(!empty($detail['articleImage']))
                        $articleImage = $detail['articleImage'];
                        else
                        $articleImage = SHIKSHA_HOME.'/public/images/fb_default.jpg';
			$attachment= array("access_token" => $detail['access_token'],
					   "privacy"=>array("value"=> "CUSTOM",  "friends"=> "SELF"),
								    "name" => $Blogname,
								    "message" => $message,
								    "picture" => $articleImage,
								    "link" => $link
								    //"description" => $eventString,
							      );


			$facebookLogData = $message."##"."ArticleUpdate"."##".$detail['blog_id'];
			$facebook = new Facebook();
			$thresholdCheck = $this->checkThresholdValue($detail['userId']);
			if($thresholdCheck['count']<FB_POST_THRESHOLD){
			try{
			if(($facebook->api('/me/feed', 'post', $attachment))){
			  //After successfully posting on the Wall, make a log entry in the DB for the entry made ( IP Address, userId, content, sessionKey)
			  if($detail['userId']>0){
				$this->load->library(array('message_board_client'));
				$msgbrdClient = new Message_board_client();
				$topicDetails = $msgbrdClient->setFBWallLog(1,$detail['userId'],$detail['access_token'],$facebookLogData,S_REMOTE_ADDR);
			  }
			}
		}catch(Exception $e) {
			if($detail['userId']>0){
			$this->deleteAccessToken($detail['userId']);
			}
			$exceptionMessage = $e." =>".$detail['userId']."==>ArticleUpdatesCRON";
	                $this->exceptionLogOfFBPost($exceptionMessage);
			error_log($e);
			error_log(print_r("CronException=>".$e.$detail['userId'],true)."FacebookConnect");	
			echo $e;
		    }
		}
			//$facebook->api('/me/feed', 'post',$attachment );
		}



    }
	function checkThresholdValue($userId=''){
	$this->init();
	if($userId == ''){
	$ajaxFlag = 0;
	$userInfo = $this->userStatus;
        $userId = $userInfo['0']['userid'];
	}
	else{
	$ajaxFlag = 1;
	}
	$fbClient = new facebook_client();
	$thresholdValue = $fbClient->checkThresholdValue($userId);
	if($thresholdValue['count']>FB_POST_THRESHOLD){
	error_log(print_r("Reached Threshold Value For =>".$userId,true)."FacebookConnect");	
	}
		if($ajaxFlag ==0){
		echo $thresholdValue['count'];
		}else{
		return $thresholdValue['count'];
		}

	}

	function facebookUserDetails(){
	$this->init();
	$fb_friends = $this->input->post('fb_friends');
	
	$process = curl_init($fb_friends); 
	curl_setopt($process, CURLOPT_TIMEOUT, 30); 
	curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($process, CURLOPT_POST, 1); 
	$response = curl_exec($process); 
	curl_close($process);	
	$flag = strrpos($response,"password");
/*	if(empty($flag)){
	$flag = strrpos($response,"permissions");
	}
	if(empty($flag)){
        $flag = strrpos($response,"Permissions");
        }*/
	if(empty($flag)){
        $flag = strrpos($response,"Invalid");
        }
	if(empty($flag)){
        $flag = strrpos($response,"invalid");
        }
	if(empty($flag)){
		$facebook = new Facebook();
		$friends = file_get_contents("$fb_friends");
		echo count(json_decode($friends)->data);
	}else{
	$userInfo = $this->userStatus;
        $userId = $userInfo['0']['userid'];	
	$this->deleteAccessToken($userId,'returnToFucntion');
	echo "invalid";	
	}
	}

	function updateFacebookFlag($action,$actionId){
		$this->init();
		$fbClient = new facebook_client();
		$fbClient->updateFacebookFlag($action,$actionId);
	}

	function checkLinkArticle(){
		$this->init();
		$fbClient = new facebook_client();
		$userInfo = $this->userStatus;
		if(isset($userInfo['0']['userid']))
		$userId = $userInfo['0']['userid'];
		$Id = $this->input->post('categoryId');
		$result = $fbClient->checkLinkArticle($userId,$Id);
		echo ($result['result']);
	}
	
	function exceptionLogOfFBPost($exceptionMessage){
		$myFile = FB_EXCEPTION_LOG_FILE;
		$fh = fopen($myFile, 'a') or die("can't open file");
		$stringData = $exceptionMessage."\n";
		fwrite($fh, $stringData);
	}
	
 	function deleteFBCookie(){	
	    setcookie('FBCookieCheck','2',time() + 86400 ,'/',COOKIEDOMAIN);                
	    setCookie('FBEmailCookieCheck','',time() - 3600 ,'/',COOKIEDOMAIN);
	    setCookie('FBDisplayNameCookieCheck','',time() - 3600 ,'/',COOKIEDOMAIN);
            setCookie('FBLastNameCookieCheck','',time() - 3600 ,'/',COOKIEDOMAIN);
	    setCookie('FBFirstNameCookieCheck','',time() - 3600 ,'/',COOKIEDOMAIN);
	    setCookie('FBPhotoCookieCheck','',time() - 3600 ,'/',COOKIEDOMAIN);
	    setCookie('FBFriendsCookieCheck','',time() - 3600 ,'/',COOKIEDOMAIN);
	    setCookie('FBAccessToken','',time() - 3600 ,'/',COOKIEDOMAIN);		
	}

	} // end class

?>


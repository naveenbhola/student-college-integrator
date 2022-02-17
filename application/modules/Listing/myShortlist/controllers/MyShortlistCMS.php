<?php
class MyShortlistCMS extends MX_Controller {
	
	private $tabId = 815;
	
	function initForMarketing($redirectOnFail = true) {
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if(!empty($cmsUserInfo['validity'])) {
			$cookiesString 	= $cmsUserInfo['validity'][0]['cookiestr'];
       	    $emailId 		= explode('|', $cookiesString);
			if(trim($emailId[0]) != "marketing@shiksha.com") {
				if($redirectOnFail){
					header("location:/enterprise/Enterprise/disallowedAccess");
					exit;
				} else {
					$cmsUserInfo['invalid_user_type'] = true;
				}
			}
		}
		return $cmsUserInfo;
	}
	
	function _init() {
		$this->myshortlistmodel = $this->load->model("myshortlistmodel");
		$this->load->model('CA/cadiscussionmodel');
		$this->cadiscussionmodel = new CADiscussionModel();
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$this->courseRepository = $listingBuilder->getCourseRepository();
	}
	
	public function index() {
		$cmsUserInfo = $this->initForMarketing();
		$cmsPageArr = array();
		
		$userid 	= $cmsUserInfo['userid'];
		$usergroup 	= $cmsUserInfo['usergroup'];
		$validity 	= $cmsUserInfo['validity'];
		$cmsPageArr = array();
		$cmsPageArr['userid'] 		= 	$userid;
		$cmsPageArr['validateuser'] = 	$validity;
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		=   $this->tabId;
		$cmsPageArr['formName'] 	=   "myshortlist";
		$this->load->view('cms/main', $cmsPageArr);
	}
	

	public function saveNotificationTemplate(){
		$mailBody         		= $this->input->post('body');
		$subject          		= $this->input->post('subject');
		$from             		= $this->input->post('from');
		$linkType         		= $this->input->post('linktype');
		$shortlistNo      		= $this->input->post('maxCourseNum');
		$notificationBody 		= $this->input->post('notification');
		$mobileNotificationBody = $this->input->post('mobileNotification');
		
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		$userid 	= $cmsUserInfo['userid'];
		
		if(!empty($mailBody) && !empty($notificationBody) && !empty($mobileNotificationBody)){
			$data = array(
				array(
					'id'		   => '',
					'type'         => 'mail',
					'body'         => $mailBody,
					'subject'      => $subject,
					'from'         => $from ,
					'link_type'    => $linkType,
					'created'      => date('Y-m-d H:i:s'),
					'shortlist_no' => $shortlistNo,
					'status'       => 'live',
					'is_processed' => '0',
					'cms_user_id'  => $userid,
				),
				array(
					'id'		   => '',
					'type'         => 'notification',
					'body'         => $notificationBody,
					'subject'      => NULL,
					'from'         => NULL,
					'link_type'    => $linkType,
					'created'      => date('Y-m-d H:i:s'),
					'shortlist_no' => $shortlistNo,
					'status'       => 'live',
					'is_processed' => '0',
					'cms_user_id'  => $userid,
				),
				array(
					'id'		   => '',
					'type'         => 'mobile_notification',
					'body'         => $mobileNotificationBody,
					'subject'      => NULL,
					'from'         => NULL,
					'link_type'    => $linkType,
					'created'      => date('Y-m-d H:i:s'),
					'shortlist_no' => 1,
					'status'       => 'live',
					'is_processed' => '0',
					'cms_user_id'  => $userid,
				));
		}
		
		$this->myshortlistmodel = $this->load->model("myshortlistmodel");
		$shortlistData = $this->myshortlistmodel->insertNotificationTemplate($data);
		echo 1;
	}
	
	public function parseBody($data) {
		$this->_init();
		$this->load->library('mailerClient');
		$mailerClient = new MailerClient();
		if(empty($data)) {
			$cmsUserInfo = $this->initForMarketing(false);
			if($cmsUserInfo['invalid_user_type']){
				echo json_encode(array('error'=> true, 'error_text' => 'Invalid User'));
				return;
			}
			$templateArr['subject']['body'] = $this->input->post('subject');
			$templateArr['subject']['maxCourseNum'] = $this->input->post('maxCourseNum');
			$templateArr['subject']['linkType'] = $this->input->post('linktype');
			
			$templateArr['mail']['body'] = $this->input->post('body');
			$templateArr['mail']['maxCourseNum'] = $this->input->post('maxCourseNum');
			$templateArr['mail']['linkType'] = $this->input->post('linktype');
			
			$templateArr['notification']['body'] = $this->input->post('notification');
			$templateArr['notification']['maxCourseNum'] = $this->input->post('maxCourseNum');
			$templateArr['notification']['linkType'] = $this->input->post('linktype');
			
			$templateArr['mobile_notification']['body'] = $this->input->post('mobileNotification');
			$templateArr['mobile_notification']['maxCourseNum'] = 1;
			$templateArr['mobile_notification']['linkType'] = $this->input->post('linktype');
			$preview = 1;
		} else {
			$templateArr = $data['templateArr'];
			$preview = 0;
		}
		
		//$templateArr['mobile_notification_1']['body'] = 'some text, </br> CR1: $CR$ </br> CR2: $CR$ <i>some text</i> </br> <b>course 1: </b> $C$ </br> course 2: $C$ </br> institute1: $I$ </br> institute2: $I$ </br> links: $LT$some link text 1$some link text 2$one more link$LT$.';
		//$templateArr['mobile_notification_2']['body'] = 'some text, </br> some text $CR$ some text <i>some text</i> <b>some</b> $C$ some text $LT$some text$some link text$LT$.';
		//$templateArr['mobile_notification_3']['body'] = 'some text, </br> some text $CR$ some text <i>some text</i> <b>some</b> some text $I$ some text $LT$some link$LT$.';
		//$templateArr = array();
		//$templateArr['mobile_notification_4']['body'] = '$C$ $CR$';
		//$templateArr['mobile_notification_4']['linkType'] = 'generic';
		//$templateArr['mobile_notification_4']['maxCourseNum'] = 1;
		//$preview = 0;
		
		$round = 0;
		$valid = 0;
		$queryExecuted = 0;
		$courseObjs = array();
		$courseCRData = array();
		$validCourseIds = array();
		$shortlistCourses = array();
		foreach($templateArr as $key => $templateData) {
			$msg 			= $templateData['body'];
			$maxCourseNum 	= $templateData['maxCourseNum'];
			$linkType 		= $templateData['linkType'];
			
			$pos_c  = strpos($msg, '$C$');
			$pos_cr = strpos($msg, '$CR$');
			$pos_i  = strpos($msg, '$I$');
			$pos_lt = strpos($msg, '$LT$');
			
			//find $C$, if not found, mark invalid, skip parsing
			if($pos_c === false && !($pos_cr === false && $pos_i === false && $pos_lt === false)) {
				$output[$key]['all']['valid'] = 0;
				$output[$key]['all']['reason'] = 'Syntax error. $C$ not found.';
				$output[$key]['all']['parsedMsg'] = '';
				continue;
			}
			
			if(substr_count($msg, '$LT$') % 2 != 0) {
				$output[$key]['all']['valid'] = 0;
				$output[$key]['all']['reason'] = 'Syntax error. Number of $LT$ found is incorrect.';
				$output[$key]['all']['parsedMsg'] = '';
				continue;
			}
			
			if($pos_lt !== false) {
				$linkMsg = explode('$LT$', $msg);
				$linkTextArr = explode('$', $linkMsg[1]);
				if(sizeof($linkTextArr) > $maxCourseNum) {
					$output[$key]['all']['valid'] = 0;
					$output[$key]['all']['reason'] = 'Syntax error. Number of links found is more than the number of courses.';
					$output[$key]['all']['parsedMsg'] = '';
					continue;
				}
			}
			
			//get all userids and courseids
			if(!$queryExecuted) {
				$shortlistData = $this->myshortlistmodel->getAllShortlistedUsersAndCourses();
				$queryExecuted = 1;
			}
			
			//find all variables, if none found(static), skip parsing
			if($pos_c === false && $pos_cr === false && $pos_i === false && $pos_lt === false) {
				$round = 0;
				foreach($shortlistData['userWiseCourseIds'] as $userId => $courseIds) {
					$output[$key][$userId]['valid'] = 1;
					$output[$key][$userId]['email'] = $shortlistData['userEmailId'][$userId];
					$output[$key][$userId]['parsedMsg'] = $msg;
					$round++;
					if($preview && $round == 10) {
						break;
					}
				}
				continue;
			}
			
			if($pos_cr !== false) {
				//get Campus Rep data for all shortlisted courses
				$coursesWithoutCR = array();
				$coursesWithCR = array_keys($courseCRData);
				$coursesWithoutCR = array_diff($shortlistData['uniqueCourseIds'], $coursesWithCR);
				if(!empty($coursesWithoutCR)) {
					$CRData = $this->cadiscussionmodel->getDetailsOfCampusRepForCourses($coursesWithoutCR);
					foreach($CRData as $courseId => $courseCR) {
						if($courseCR != 'false') {
							$courseCRData[$courseId] = $courseCR['displayName'];
						}
					}
				}
				$courseIdsWithCampusRep = array_keys($courseCRData);
			}
			
			if($pos_cr !== false) {
				$validCourseIds = $courseIdsWithCampusRep;
			} else {
				$validCourseIds = $shortlistData['uniqueCourseIds'];
			}
			$round = 0;
			foreach($shortlistData['userWiseCourseIds'] as $userId => $courseIds) {
				$shortlistCourses = array_slice(array_intersect($courseIds, $validCourseIds), 0, $maxCourseNum);
				
				if(!empty($shortlistCourses)) {
					$CR = array();
					$courseNames = array();
					$insttIds = array();
					$insttNames = array();
					$link = array();
					$commaSeparatedCR = '';
					$commaSeparatedCourses = '';
					$commaSeparatedInstts = '';
					$commaSeparatedLinks = '';
					$parsedMsg = '';
					
					$courseObjs = $this->courseRepository->findMultiple($shortlistCourses);
					
					foreach($shortlistCourses as $i => $courseId) {
						$courseNames[] = $courseObjs[$courseId]->getName();
						if($pos_cr !== false) {
							$CR[] = $courseCRData[$courseId];
						}
						if($pos_i !== false) {
							$insttId = $courseObjs[$courseId]->getInstId();
							if(!in_array($insttId, $insttIds)) {
								$insttIds[] = $insttId;
								$insttNames[] = $courseObjs[$courseId]->getInstituteName();
							}
						}
						if($pos_lt !== false) {
							$domain = SHIKSHA_HOME;
							$tempUserEmail = $shortlistData['userEmailId'][$userId];
							$tempURL = $domain . "/my-shortlist#nav-" . $courseId . "-" . $linkType;
							$autoLoginURL = $mailerClient->generateAutoLoginLink(1, $tempUserEmail, $tempURL);
							$link[] = "<a linkId='nav-$courseId-$linkType' href='$autoLoginURL'>".$linkTextArr[$i]."</a>";
						}
					}
					//replace $C$ with comma separated courses
					$commaSeparatedCourses = implode(', ', $courseNames);
					$parsedMsg = str_replace('$C$', $commaSeparatedCourses, $msg);
					
					//replace $CR$ with comma separated CRs
					if($pos_cr !== false) {
						$commaSeparatedCR = implode(', ', $CR);
						$parsedMsg = str_replace('$CR$', $commaSeparatedCR, $parsedMsg);
					}
					
					//replace $I$ with comma separated institutes
					if($pos_i !== false) {
						$commaSeparatedInstts = implode(', ', $insttNames);
						$parsedMsg = str_replace('$I$', $commaSeparatedInstts, $parsedMsg);
					}
					
					//replace $LT$......$........$.......$LT$ with <a href> based on link type and courseId
					if($pos_lt !== false) {
						$commaSeparatedLinks = implode(', ', $link);
						$parsedLinkMsg = explode('$LT$', $parsedMsg);
						$parsedLinkMsg[1] = $commaSeparatedLinks;
						$parsedMsg = implode('', $parsedLinkMsg);
					}
					$output[$key][$userId]['valid'] = 1;
					$output[$key][$userId]['email'] = $shortlistData['userEmailId'][$userId];
					$output[$key][$userId]['parsedMsg'] = $parsedMsg;
					if($templateData['type'] == 'mobile_notification') {
						$output[$key][$userId]['courseId'] = $shortlistCourses[0];
					}
				} else {
					$output[$key][$userId]['valid'] = 0;
					$output[$key][$userId]['email'] = $shortlistData['userEmailId'][$userId];
					$output[$key][$userId]['reason'] = 'Course might not be live or campus rep might not be present for any shortlisted courses for this user.';
					$output[$key][$userId]['parsedMsg'] = '';
				}
				$round++;
				if($preview && $round == 10) {
					break;
				}
				
				unset($courseObjs);
			}
			if($preview && $round == 10) {
				continue;
			}
		}
		if($preview) {
			echo json_encode(array('html'=>$this->load->view('cms/previewContent', $output, true)));
			exit;
		}
		return $output;
	}
}
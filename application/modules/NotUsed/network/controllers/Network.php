<?php 

exit();

/*
   Copyright 2007 Info Edge India Ltd

 */

define("USERS_DETAIL_PAGE", 10);
define("USERS_VIEW_ALL_PAGE", 30);
define("TOPICS_DETAIL_PAGE", 6);
define("TOPICS_VIEW_ALL_PAGE", 30);
define("EVENTS_DETAIL_PAGE", 3);
define("QUESTIONS_DETAIL_PAGE", 2);
define("GROUPS_DETAIL_PAGE", 3);
define("ARTICLES_DETAIL_PAGE", 5);

class Network extends CI_Controller{

	function showAllGroups()
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".SHIKSHA_HOME_URL);
		exit();
		$this->init();
		$Validate = $this->checkUserValidation();
		$data['validateuser'] = $Validate;
		if(is_array($Validate) && $Validate != "false")
			$userId = $Validate[0]['userid'];
		/*$response = $this->totalCollegesandUsers();
		  $data['groups'] = $response[0]['colleges'];
		  $data['groupmembers'] = $response[0]['users'];*/
		$Network_client = new Network_client();
		$NetworkList = 0;
		if(is_array($Validate) && $Validate != "false")
		{
			$countResponse = $this->totalUsersCollegesinUserNet($userId,3);
			$data['myCount'] = $countResponse[0]['colleges'];
			$NetworkList = $Network_client->showCollegeNetworkList(1,0,6,$userId,3);
		}
		$count = 6;
		$RecentMembers = $this->getRecentlyAddedMembers(1,3,$count,3);
		$categoryStats = $Network_client->getCollegesByCategory(1,0);
		$data['recentmembers'] = $RecentMembers;
		if(is_array($Validate) && $Validate != "false")
		{
			$getuserNetwork = $Network_client->showUserNetworkList(1,0,0,$userId);
			$data['userNetwork'] = $getuserNetwork;
		}
		$data['networkList'] = $NetworkList;
		$data['categoryStats'] = $categoryStats;
		$this->load->view('network/home.php',$data);
	}

	function index($institute = "college",$country = "2",$category)
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".SHIKSHA_HOME_URL);
		exit();

		if($institute == "college")
			$institute = 1;
		if($institute == "school")
			$institute = 2;
		$this->init();
		$Network_client = new Network_client();
		$Validate = $this->checkUserValidation();
		$data['validateuser'] = $Validate;
		if(is_array($Validate) && $Validate != "false")
			$userId = $Validate[0]['userid'];
		if($category == "Test Preparation")
			$category = "TestPreparation";
		if($institute == 1)
		{
			$categoryStats = $Network_client->getCollegesByCategory(1,$category);
			$data['insticount'] = $categoryStats[0]['collegecount'];
			$data['membercount'] = $categoryStats[0]['membercount'];
		}
		if($institute == 2)
		{
			$response = $this->totalCollegesandUsers();
			$data['insticount'] = $response[0]['schools'];
			$data['membercount'] = $response[0]['schoolusers'];
		}

		$data['categoryStats'] = $categoryStats;;
		$data['institute'] = $institute ;
		$data['country'] = $country;
		$this->load->library('listing/listing_client');
		$listingClient = new Listing_client();
		$cities = $listingClient->getCityList(1,2);
		$data['city'] = $cities;
		$count = 10;
		if($institute == 1)
			$response = $this->getRecentlyAddedMembers(1,1,$count,$category);
		else
			$response = $this->getRecentlyAddedMembers(1,2,$count,2);

		$data['recentmembers'] = $response;
		if(is_array($Validate) && $Validate != "false")
		{
			$getuserNetwork = $Network_client->showUserNetworkList(1,0,0,$userId);
			$data['userNetwork'] = $getuserNetwork;
		}
		$data['groupCategory'] = $category;
		$this->load->view('network/networkHome',$data);
	}


	function getRecentlyAddedMembers($appId,$institute,$count,$category)
	{
		$this->init();
		$Network_client = new Network_client();

		$response = $Network_client->getRecentlyAddedMembers($appId,$institute,$count,$category);
		for($i = 0;$i < count($response) ; ++$i)
		{
			$response[$i]['avtarimageurl'] = getSmallImage($response[$i]['avtarimageurl']);
			$response[$i]['addtime'] = makeRelativeTime($response[$i]['joiningDate']);
			$onlinestatus = $this->showUserStatus($response[$i]['lastlogintime']);
			$reltime = makeRelativeTime($response[$i]['lastlogintime']);
			if($onlinestatus == "online")
			{
				$response[$i]['statusimage'] = "/public/images/online_user.gif";
				$response[$i]['statusmsg'] = $response[$i]['displayname'] ." is online on Shiksha.com";
			}
			if($onlinestatus == "inactive")
			{
				$response[$i]['statusimage'] = "/public/images/inactive_user.gif";
				$response[$i]['statusmsg'] = $response[$i]['displayname'] ." has not been using Shiksha.com for ".$reltime;
			}
			if($onlinestatus == "offline")
			{
				$response[$i]['statusimage'] = "/public/images/offline_user.gif";
				$response[$i]['statusmsg'] = $response[$i]['displayname'] . " is offline on Shiksha.com";
			}

		}
		return $response;
	}

	function init()
	{
		$this->load->library('network/Network_client');
		$this->load->library('messageBoard/Message_board_client');
		$this->load->helper(array('url','form','image','shikshautility'));
		$this->load->library('common/ajax');
		if($this->validation == 1)
		{
			$Validate = $this->checkUserValidation();
			if(is_array($Validate) && $Validate != "false")
			{
				$this->userId = $Validate[0]['userid'];
				$this->validateUser = $Validate;
			}
			else
				$this->userId = '';

		}
	}


	function getcitiesbyCountry($country,$start,$count)
	{
		$this->init();
		$Network_client = new Network_client();
		$cities = $Network_client->getcitiesbyCountry(1,$country,$start,$count);
		$result = array('results' => $cities);
		echo json_encode($result);
	}


	function uploadpic(){


		$this->init();
		$appId = 1;
		$Validate = $this->checkUserValidation();

		if($_FILES['collegephoto']['tmp_name'][0] == '')
			echo 'Please select a photo to upload';
		else
		{
			if(!(is_array($Validate) && $Validate != "false"))
			{
				echo 'Please login or signup to upload the photo';
				exit;
			}
			$networkClient = new Network_client();
			error_log($_POST['grouptype']);
			if($_POST['grouptype'] == "TestPreparation")
				$grouptype = "TestPreparation";
			else
				$grouptype = "group";
			$membercheck = $networkClient->checkifmember(1,'college',$Validate[0]['userid'],$_POST['institute_id'],$grouptype);
			$flag = $membercheck['flag'];
			if(!$flag)
			{
				echo "Only members of this college group are allowed to upload the photo";
				exit;
			}
			else
			{
				//$institute_id = 1;
				$institute_id = $_POST['institute_id'];
				$this->load->library('common/Upload_client');
				$uploadClient = new Upload_client();

				$description['0'] = '';
				$uploadRes = $uploadClient->uploadFile($appId,'image',$_FILES,$description,	$institute_id,"institute","collegephoto");
				if(is_array($uploadRes))
				{
					$logoLink = $uploadRes[0]['thumburl_m'];
					if($grouptype != "TestPreparation")
					{
						$this->load->library('listing/Listing_client');
						$ListingClientObj = new Listing_client();
						$updateInstituteData = array();
						$updateInstituteData['institute_id'] = $institute_id;
						$updateInstituteData['logo_link'] = $logoLink;
						$status = $ListingClientObj->update_institute($appId,$updateInstituteData);
					}
					else
					{
						$this->load->library('blogs/Blog_client');
						$BlogClientObj = new Blog_client();
						$status = $BlogClientObj->updateImageUrl($appId,$institute_id,$logoLink);
					}
					echo 1;
				}
				else
				{
					echo $uploadRes;
				}
			}
		}
	}

	function totalCollegesandUsers()
	{

		$appId  = 1;
		$networkClient = new Network_client() ;
		return $networkClient->totalCollegesandUsers($appId);

	}

	function showUserStatus($time)
	{

		if($time == null)
			return "offline";
		if($time == "0000-00-00 00:00:00")
			return "offline";
		$timeDiff = strtotime("now") - strtotime($time);
		if($timeDiff > 0 && $timeDiff <= 3600)
		{
			return "online";
		}
		else
		{
			if($timeDiff > 3600)
				return "inactive";
			else
			{
				if($timeDiff <= 0)
					return "offline";
			}
			return "offline";
		}

	}

	function showuserCollegeNetwork($start = 0,$count = 12,$collegeId,$graduationYear,$userStatus,$type,$grouptype = "group")
	{
		$this->init();
		$appId = 1;
		//$userStatus = "Student','Alumni";

		$networkClient = new Network_client();

		//			$collegeNetworkCount = $networkClient->showCollegeNetworkCount($appId,$userStatus,$collegeId,	$graduationYear);

		$collegeNetwork = $networkClient->showuserCollegeNetwork($appId,$start,$count,$userStatus,$collegeId,$graduationYear,$grouptype);
		$Validate = $this->checkUserValidation();
		$getuserNetwork = array();
		if(is_array($Validate) && $Validate != "false")
		{
			$userId = $Validate[0]['userid'];
			$email = $Validate[0]['email'];
			$getuserNetwork = $networkClient->showUserNetworkList($appId,0,0,$userId);
		}
		else
		{
			$userId =  0;
			$email = 0;
		}

		if(is_array($collegeNetwork) && count($collegeNetwork))
		{
			$totalCount = $collegeNetwork[0]['totalCount'];
			$collegeNetwork = $collegeNetwork[0]['network'];

			for($i = 0;$i < count($collegeNetwork); $i++)
			{
				$collegeNetwork[$i]['avtarimageurl'] = getSmallImage($collegeNetwork[$i]['avtarimageurl']);
				$onlinestatus = $this->showUserStatus($collegeNetwork[$i]['lastlogintime']);
				$collegeNetwork[$i]['addtime'] = makeRelativeTime($collegeNetwork[$i]['joiningDate']);
				if($onlinestatus == "online")
				{
					$collegeNetwork[$i]['statusimage'] = "/public/images/online_user.gif";
					$collegeNetwork[$i]['onlinestatus'] = "online";
				}
				if($onlinestatus == "inactive")
				{
					$collegeNetwork[$i]['statusimage'] = "/public/images/inactive_user.gif";
					$collegeNetwork[$i]['onlinestatus'] = "idle";
				}
				if($onlinestatus == "offline")
				{
					$collegeNetwork[$i]['statusimage'] = "/public/images/offline_user.gif";
					$collegeNetwork[$i]['onlinestatus'] = "offline";
				}
				$msgbrdClient = new Message_board_client();
				$collegeNetwork[$i]['RelativeTime'] = makeRelativeTime($collegeNetwork[$i]['lastlogintime']);

			}
			$network = array('results'=>$collegeNetwork,'totalCount'=>$totalCount,'userId'=>$userId,'email'=>$email,'userNetwork'=>$getuserNetwork);
			if($type == "return")
				return $network;
			else
				echo json_encode($network);
		}
		else
		{
			if($type == "return")
				return "";
			else
				echo "";
		}

	}

	function showRequestResponse($senderid,$userid,$response)
	{
		$this->init();
		$networkClient = new Network_Client();
		$appId = 1;
		$response = $networkClient->showRequestResponse($appId,$senderid,$userid,$response);
		if($response == 1)
			echo $response ;
		else
			echo "";
	}


	function deleteUserFromNetwork($collegeId,$grouptype)
	{
		$this->init();
		$appId = 1;
		$Validate = $this->checkUserValidation();
		$userId = isset($Validate[0]['userid'])?$Validate[0]['userid']:0;
		$deleted = 0;
		$networkClient = new Network_Client();
		if($userId != 0)
		{
			$result = $networkClient->deleteUserFromNetwork($appId,$userId,$collegeId,$grouptype);
			$deleted = $result['Result'];
		}
		$result = array('userValidate' => $userId,
				'result' =>$deleted);
		echo json_encode($result);
	}

	function showCollegeNetworkCount($collegeId)
	{
		$this->init();
		$appId = 1;
		$networkClient = new Network_client();
		$collegeNetworkCount = $networkClient->showCollegeNetworkCount($appId,'All',$collegeId,	0);
		if(is_array($collegeNetworkCount) && count($collegeNetworkCount))
		{
			$network = array('collegeName'=>$collegeNetworkCount[0]['name'],'totalCount'=>$collegeNetworkCount[0]['count'],'logo'=>$collegeNetworkCount[0]['logo']);

			echo json_encode($network);

		}
		else
			echo '';
	}


	function getnetworkCountforYear($collegeId,$minyear,$maxyear)
	{
		$this->init();
		$appId = 1;
		$networkClient = new Network_client();
		$collegeNetworkCount = $networkClient->getnetworkCountforYear($appId,'All',$collegeId,$minyear,$maxyear);

		if(is_array($collegeNetworkCount) && count($collegeNetworkCount))
		{
			$network = array('results'=>$collegeNetworkCount);

			echo json_encode($network);

		}
		else
			echo '';


	}

	function checkifmember($institute,$instituteId,$grouptype = "group")
	{

		$this->init();
		$Validate = $this->checkUserValidation();
		$networkClient = new Network_client();
		if(is_array($Validate) && $Validate != "false")
		{
			$membercheck = $networkClient->checkifmember(1,$institute,$Validate[0]['userid'],$instituteId,$grouptype);
			$flag = $membercheck['flag'];
			echo $flag;
		}
	}
	function collegeNetwork($collegeId,$cityid,$join,$grouptype = "group")
	{
		$this->init();
		$msgbrdClient = new Message_board_client();
		$displayData['collegeId'] = $collegeId;
		$displayData['cityid'] = $cityid;
		$displayData['join'] = $join;
		$displayData['grouptype'] = $grouptype;
		$Validate = $this->checkUserValidation();
		$networkClient = new Network_client();
		$displayData['validateuser'] = $Validate;
		error_log_shiksha('ENTER');
		if(is_array($Validate) && $Validate != "false")
		{
			$displayData['userId'] = $Validate[0]['userid'];
			$membercheck = $networkClient->checkifmember(1,'college',$Validate[0]['userid'],$collegeId,$grouptype);
			$flag = $membercheck['flag'];
			$displayData['member'] = $flag;
			$displayData['statusFlag'] = $membercheck['status'];
		}
		else
			$displayData['userId'] = 0;

		$groups = $msgbrdClient->getTopicsForGroups(1,$collegeId,$grouptype,0,TOPICS_DETAIL_PAGE);
		$usergroup = $this->showuserCollegeNetwork(0,USERS_DETAIL_PAGE,$collegeId,"All","All","return",$grouptype);
		$groupnews = $networkClient->getgroupnews($collegeId,$grouptype,0,20);
		if($grouptype == "TestPreparation")
		{
			$this->load->library('blogs/Blog_client');
			$blog_client = new Blog_client();
			$start = 0;
			$count = 5;
			$examEvents = $blog_client->getTestPrepInfoForGroups($appId, $collegeId, 0, 5);
			$blogArr = json_decode($examEvents[0],true);
			$displayData['blogDetails'] = $blogArr[0][0][0];
			$blogDetails = $displayData['blogDetails'] ;
			$relatedProduct = json_decode($examEvents[1],true);
			$relatedListing = json_decode($examEvents[2],true);
			$displayData['collegeName'] = $blogDetails['blogTitle'];
			$displayData['relatedListings'] = $relatedListing[0][0][0]['results'][0];
			$displayData['relatedEvents'] = $relatedProduct[0][0][0]['results'][0];
		}

		if($grouptype != "TestPreparation")
		{
			$listingDetails = $this->listingDetails($collegeId);
			$relatedQuestions = $listingDetails[0]['relatedQuestions'];
			$relatedListings = $listingDetails[0]['relatedListings'];
			$relatedEvents = $listingDetails[0]['relatedEvents'];
			$this->load->library('blogs/Blog_client');
			$objBlog = new Blog_client();
			$categoryId =  $listingDetails[0]['categories'];
			$blogs = $objBlog->getBlogsForHomePages(1, $categoryId,1,0,ARTICLES_DETAIL_PAGE);
			$displayData['listingDetails'] = $listingDetails;
			$displayData['blogs'] = $blogs;
			$displayData['collegeName'] = $listingDetails[0]['instituteName'];
			$displayData['relatedQuestions'] = json_decode($relatedQuestions,true);
			$displayData['relatedListings'] = json_decode($relatedListings,true);
			$displayData['relatedEvents'] = json_decode($relatedEvents,true);
		}

		$displayData['messages'] = $groups;
		$displayData['groupnews'] = $groupnews;
		$displayData['usergroup'] = $usergroup;
		if($grouptype == "TestPreparation")
			$this->load->view('network/TestPrep',$displayData);
		else
			$this->load->view('network/detailPage',$displayData);
	}



	function searchInGroup($collgeName)
	{
		$this->init();

		$this->load->library('listing/Listing_client');
		$listingClient = new Listing_client();
		$appId = 1;
		$response = $listingClient->checkInstituteTitle($collgeName);
		$courses = $response;
		if(is_array($response))
			echo json_encode($courses);
		else
			echo "";

	}

	function listingDetails($instituteId)
	{
		$this->init();
		$this->load->library('listing/Listing_client');
		$ListingClientObj = new Listing_client();
		$joinGroupInfo = $ListingClientObj->getJoinGroupInfo($appId,$instituteId);
		//print_r($joinGroupInfo);
		return $joinGroupInfo;
		/*$network = array('results' =>$joinGroupInfo);
		  if(is_array($joinGroupInfo) && count($joinGroupInfo))
		  return  json_encode($network);
		  else
		  return "";	*/

	}

	function showrelatedquestions($start,$count,$collgeId,$categories)
	{
		$this->init();
		$msgbrdClient = new Message_board_client();
		$this->load->library('listing/Listing_client');
		$listingclient = new Listing_client();
		$appId = 1;

		$response1 = $listingclient->getParentCategoriesForListing($appId,$collgeId,'institute');
		$categories = $response1['catList'];
		$response = $msgbrdClient->showrelatedquestions($appId,$start,$count,$collgeId,$categories,'institute');
		$questions = array('results' =>$response);
		/*			if(is_array($response))
					echo json_encode($questions);
					else
					echo "";	*/

	}


	function showNewUserRequests($userId,$start = 0,$count = 5)
	{
		$appId = 1;
		$this->load->library('network/Network_client');
		$network_client = new Network_client();
		$response = $network_client->showNewRequests($appId,$userId,'new',$start,$count);
		$requests = array('results' =>$response);
		if(is_array($response))
			echo json_encode($requests);
		else
			echo "";
	}

	function showRecentUserComment($start = 0 ,$count = 3,$collegeId)
	{
		$this->init();
		$appId = 1;
		$networkClient = new Network_client();
		$resp = $networkClient->getThreadId($appId,$collegeId);
		$threadId = $resp[0]['threadid'];
		$boardId = $resp[0]['categoryid'];
		$countryId = $resp[0]['countryid'];
		$title = $resp[0]['title'];
		$response = $networkClient->showRecentComment($appId,$start,$count,$threadId,$boardId);
		if(is_array($response) && count($response))
		{
			$network = array('results'=>$response,'totalCount'=>5,'BoardId'=>$boardId,'ThreadId'=>$threadId,'parentId'=>0,'countryId'=>$countryId,'title'=>$title);
			echo json_encode($network);
		}
		else
		{
			if($response > 0)
			{
				$network = array('results'=>'','totalCount'=>5,'BoardId'=>$boardId,'ThreadId'=>$threadId,'parentId'=>$response,'countryId'=>$countryId,'title'=>$title);
				echo json_encode($network);
			}
			else
				echo "";
		}
	}

	function submitComment()
	{
		$this->init();
		$appId = 1;
		$userId = $_POST['userIdComment'];
		$collegeId = $_POST['collegeId'];
		$comment = $_POST['comment'];
		$parentId = $_POST['parent'];
		$countryId = $_POST['countryId'];
		$selectedCategory = $_POST['category'];
		$threadId = $_POST['thread'];
		$requestIP = S_REMOTE_ADDR;
		$topicTitle = $_POST['titleofComment'];
		$this->load->library('messageBoard/Message_board_client');
		$msgbrdClient = new Message_board_client();
		$topicResult = $msgbrdClient->postReply($appId,$selectedCategory,$userId,$comment,$topicTitle,$threadId,$parentId,$requestIP,$countryId);
		$network = array('results'=>$response,'totalCount'=>count($response));

		if(is_array($response) && count($response))
		{

			echo json_encode($network);
		}
		else
			echo "";

	}


	function addtoNetwork()
	{
		$this->validation = 1;
		$this->init();
		$appId = 1;
		$userStatus = $this->input->post('status');
		$userId = $this->input->post('loggeduserid');
		$collegeName = $this->input->post('collegeName');

		$collegeId = $this->input->post('collegeId');
		$year = $this->input->post('GradYear');
		$grouptype = $this->input->post('grouptype');
		if($grouptype != "TestPreparation")
		{
			$course = $this->input->post('courseTitle');
			$cityId = $this->input->post('collegeLocation');
		}
		$networkClient = new Network_client();
		$secCode = $this->input->post('joinseccode');
		
			if($this->userId != '')
			{
				$response = $networkClient->addtoNetwork($appId,$userStatus,$course,$year,$this->userId,$collegeId,$cityId,$collegeName,$grouptype);
				if($response > 0  && (!($response == "false")))
				{
					//lead code
					if($userStatus == "Prospective Student")
					{
						$signedInUser = $this->validateUser;
						$email = explode('|',$signedInUser[0]['cookiestr']);
						$addReqInfo['listing_type'] = 'institute';
						$addReqInfo['listing_type_id'] = $collegeId;
						$addReqInfo['displayName'] = $signedInUser[0]['displayname'];
						$addReqInfo['contact_cell'] = $signedInUser[0]['mobile'];
						$addReqInfo['userId'] = $signedInUser[0]['userid'];
						$addReqInfo['contact_email'] = $email[0];
						$addReqInfo['action'] = "joinedgroup";
						$addReqInfo['userInfo'] = json_encode($signedInUser);
						$addReqInfo['sendMail'] = true;
						$this->load->library('lms/LmsLib');
						$LmsClientObj = new LmsLib();
						$addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);
					}
					if($userStatus == "Faculty")
						echo 1;
					if($response == -1)
						echo 2;
					else
						echo 0;
				}
				else
				{
					if($response == -1)
						echo 2;
					else
						echo -1;
				}
			}
			else
				echo "invalid user";
	}

	function showUserNetworkList($start = 0,$count = 2,$userId)
	{
		$this->init();
		$appId = 1;
		$networkClient = new Network_client();
		$response = $networkClient->showUserNetworkList($appId,$start,$count,$userId);

		if(is_array($response) && count($response))
		{
			for($i = 0;$i < count($response); $i++)
			{
				$response[$i]['userStatus'] = getUserStatus($response[$i]['lastlogintime']);
				$response[$i]['userStatusToolTip'] = getUserStatusToolTip($response[$i]['userStatus'],$response[$i]['displayname'],$response[$i]['lastlogintime']);
			}
		}
		$countsResponse = $this->totalUsersCollegesinUserNet($userId);
		$network = array('results'=>$response,'totalCount'=>$countsResponse[0]['users']);
		echo json_encode($network);


	}

	function totalUsersCollegesinUserNet($userId,$institute = 1)
	{
		$appId  = 1;
		$networkClient = new Network_client() ;
		return $networkClient->totalUsersCollegesinUserNet($appId,$userId,$institute);
	}

	function showCollegeNetworkList($start = 0,$count = 2,$userId,$institute = 1)
	{
		$this->init();
		$Validate = $this->checkUserValidation();
		$appId = 1;
		$networkClient = new Network_client();
		if(is_array($Validate) && $Validate != "false" && $userId == 0)
		{
			$userId = $Validate[0]['userid'];
		}
		$countsResponse = $this->totalUsersCollegesinUserNet($userId,$institute);
		$response = $networkClient->showCollegeNetworkList($appId,$start,$count,$userId,$institute);
		$network = array('results'=>$response,'totalCount'=>$countsResponse[0]['colleges']);
		echo json_encode($network);

	}


	function showColleges($start = 0,$count = 12,$country = 'All',$alphabet = 'All',$city = 'All' ,$category)
	{
		$this->init();
		$appId = 1;
		$networkClient = new Network_client();
		$response = $networkClient->showColleges($appId,$alphabet,$start,$count,$country,$city,$category);
		$collegeCount = $response[0]['totalRows'];
		$network = array('results' =>$response,'totalCount'=> $collegeCount);
		if(is_array($response) && count($response))
			echo json_encode($network);
		else
			echo "";

	}
	function showNetwork($CategoryId = 1,$countryId = 1,$tabselected = 1)
	{
		$this->init();
		$appId = 1;
		$networkClient = new Network_client();
		$response = $networkClient->showColleges($appId,$alphabet,$start,$count,$country);

		$colleges = array('results' =>$response);

		if(is_array($response) && count($response))
			echo json_encode($colleges);
		else
			echo "";
		$this->load->view('network/networkHome');

	}
	function sendMail()
	{
		$this->init();
		$appId = 1;
		$userid = $this->input->post('receiverid');
		$useremail = $this->input->post('username');
		$senderid = $this->input->post('senderid');
		$senderemail = $this->input->post('sender');
		$network_client = new Network_client();
		$response = $network_client->addUserRequest($appId,$senderid,$userid,'new');
		echo $response;
	}


	function showNewRequests($userId = 1,$appId = 1)
	{
		$this->init();
		$appId = 1;
		$network_client = new Network_client();
		$response = $network_client->showNewRequests($appId,$userid,'new');
		echo $response;
	}

	function sendRequest($userid)
	{
		$this->init();
		$appId = 1;
		$network_client = new Network_client();
		$Validate = $this->checkUserValidation();
		$senderid = $Validate[0]['userid'];
        if($senderid != $userid)
        {
            $response = $network_client->addUserRequest($appId,$senderid,$userid,'new');
            if($response > 0)
            {
                $this->load->library('mail/Mail_client');
                $mail_client = new Mail_client();
                $receiverIds = array();
                array_push($receiverIds,$userid);
                $subject = "New Request";
                $body = 'A new user wants to add you to his/her network. Please visit your account to accept or decline the request';
                $content = 0;
                $sendmail = $mail_client->send($appId,$senderid,$receiverIds,$subject,$body,$content);
            }
            echo $response;
        }
        else
        echo -6;
	}


	/* Functions for school Network Page*/
	function showSchools($start = 0,$count = 12,$country = 'All',$alphabet = 'All')
	{
		$this->init();
		$appId = 1;
		$networkClient = new Network_client();
		$schoolCount = $networkClient->getSchoolCount($appId,$alphabet,$country);
		$response = $networkClient->showSchools($appId,$alphabet,$start,$count,$country);
		$network = array('results' =>$response,'totalCount'=> $schoolCount);

		if(is_array($response) && count($response))
			echo json_encode($network);
		else
			echo "";

	}

	function getAlertcontent()
	{
		$this->init();
		$appId = 1;
		$networkClient = new Network_client();
		$alertcontent = $networkClient->getAlertcontent($appId,'',1,1);
		$alertcontent1 = $networkClient->getAlertcontent($appId,'',1,2);

	}

	function subscribegroupalert($productname,$instituteId,$status,$grouptype)
	{
		$this->init();
		$Validate = $this->checkUserValidation();
		$networkClient = new Network_client();

		if(is_array($Validate) && $Validate != "false")
		{
			if($productname == "collegegroup")
			{
				$membercheck = $networkClient->checkifmember(1,'college',$Validate[0]['userid'],$instituteId,$grouptype);
				$flag = $membercheck['flag'];
			}
			else
			{
				$membercheck = $networkClient->checkifmember(1,'school',$Validate[0]['userid'],$instituteId,$grouptype);
				$flag = $membercheck['flag'];
			}
			if($flag == 0)
				return $flag;
			$updatealert = $networkClient->updatealertstatus(1,$productname,$instituteId,$status,$Validate[0]['userid'],$grouptype);
			return $updatealert;
		}

	}

	function schoolNetwork($schoolId,$schoolName,$join)
	{
		$this->init();
		$displayData['collegeId'] = $schoolId;
		$displayData['schoolId'] = $schoolId;
		$displayData['schoolName'] = $schoolName;
		$displayData['join'] = $join;
		$Validate = $this->checkUserValidation();
		$displayData['validateuser'] = $Validate;
		$networkClient = new Network_client();
		if(is_array($Validate) && $Validate != "false")
		{
			$displayData['userId'] = $Validate[0]['userid'];
			$membercheck = $networkClient->checkifmember(1,'school',$Validate[0]['userid'],$schoolId);
			$flag = $membercheck['flag'];
			$displayData['member'] = $flag;
			$displayData['statusFlag'] = $membercheck['status'];
		}
		else
			$displayData['userId'] = 0;

		$resp = $networkClient->getThreadIdforSchool($appId,$schoolId);
		$threadId = $resp[0]['threadid'];
		$topicId = $threadId;


		$msgbrdClient = new Message_board_client();

		$ResultOfDetails = $msgbrdClient->getMsgTree($appId,$threadId,0,30);
		if(isset($ResultOfDetails[0]['MsgTree']))
			$topic_reply = $ResultOfDetails[0]['MsgTree'];

		if(isset($ResultOfDetails[0]['RelatedTopic']))
			$relatedTopics = $ResultOfDetails[0]['RelatedTopic'];

		$rows = 5;
		$displayData['topicId'] = $topicId;

		$param_arr = array(array($topicId,'int'));
		if(is_array($topic_reply) && count($topic_reply) > 0)
		{
			$topic_messages = array();
			$i = -1;
			foreach($topic_reply as $key => $temp)
			{
				if($key == 0)
				{
					if($temp['status'] == 'deleted')
						break;
					else
						continue;
				}
				$found = 0;
				$comparison_string = $temp['path'];
				if(substr_count($temp['path'],'.') == 1)
				{

					$i++;
					$topic_messages[$i] = array();
					$temp['userStatus'] = getUserStatus($topic_reply[$i]['lastlogintime']);
					$temp['creationDate'] = makeRelativeTime($temp['creationDate']);
					array_push($topic_messages[$i],$temp);
					$comparison_string = $temp['path'].'.';

				}
				elseif(strstr($temp['path'],$comparison_string))
				{
					$temp['userStatus'] = getUserStatus($topic_reply[$i]['lastlogintime']);
					$temp['creationDate'] = makeRelativeTime($temp['creationDate']);
					array_push($topic_messages[$i],$temp);
				}

			}
			if($temp['status'] != 'deleted')
			{
				$displayData['topic_messages'] = $topic_messages;
				$topic_reply[0]['userStatus'] = getUserStatus($topic_reply[0]['lastlogintime']);
				$main_message = $topic_reply[0];
				$closeDiscussion = $topic_reply[0]['closeDiscussion'];
			}
		}
		$categoryId = 1;
		$displayData['categoryId'] = $categoryId;
		$displayData['main_message'] = $main_message;
		$displayData['alertId'] = $alertId;
		$displayData['appId'] = $appId;
		$displayData['topicId'] = $topicId;
		$displayData['threadId'] = $topicId;
		$displayData['closeDiscussion'] = $closeDiscussion;
		$MemberCount = $networkClient->showSchoolMembersCount($appId,'All',$schoolId,0);
		$displayData['schoolName'] = $MemberCount[0]['name'];
		$displayData['relatedTopics'] = $relatedTopics;
		$this->load->view('network/schoolNetwork',$displayData);

	}

	function showSchoolMembersCount($schoolId)
	{
		$this->init();
		$appId = 1;
		$networkClient = new Network_client();
		$MemberCount = $networkClient->showSchoolMembersCount($appId,'All',$schoolId,	0);

		if(is_array($MemberCount) && count($MemberCount))
		{
			$network = array('collegeName'=>$MemberCount[0]['name'],'totalCount'=>$MemberCount[0]['count']);

			echo json_encode($network);

		}
		else
			echo '';
	}



	function showuserSchoolNetwork($start = 0,$count = 12,$collegeId,$graduationYear,$userStatus)
	{
		$this->init();
		$appId = 1;
		//$userStatus = "Student','Alumni";

		$networkClient = new Network_client();

		$collegeNetworkCount = $networkClient->showSchoolMembersCount($appId,$userStatus,$collegeId,	$graduationYear);


		$collegeNetwork1 = $networkClient->showuserSchoolNetwork($appId,$start,$count,$userStatus,$collegeId,$graduationYear);


		$Validate = $this->checkUserValidation();
		$getuserNetwork = array();
		if(is_array($Validate) && $Validate != "false")
		{
			$userId = $Validate[0]['userid'];
			$email = $Validate[0]['email'];
			$getuserNetwork = $networkClient->showUserNetworkList($appId,0,0,$userId);

		}
		else
		{
			$userId =  0;
			$email = 0;
		}
		if(is_array($collegeNetwork1) && (count($collegeNetwork1)> 0))
		{

			for($i = 0;$i < count($collegeNetwork1); $i++)
			{
				$onlinestatus = $this->showUserStatus($collegeNetwork1[$i]['lastlogintime']);
				if($onlinestatus == "online")
				{
					$collegeNetwork1[$i]['statusimage'] = "/public/images/online_user.gif";
					$collegeNetwork1[$i]['onlinestatus'] = "online";
				}
				if($onlinestatus == "inactive")
				{
					$collegeNetwork1[$i]['statusimage'] = "/public/images/inactive_user.gif";
					$collegeNetwork1[$i]['onlinestatus'] = "idle";
				}
				if($onlinestatus == "offline")
				{
					$collegeNetwork1[$i]['statusimage'] = "/public/images/offline_user.gif";
					$collegeNetwork1[$i]['onlinestatus'] = "offline";
				}
				$msgbrdClient = new Message_board_client();
				$collegeNetwork1[$i]['RelativeTime'] = makeRelativeTime($collegeNetwork1[$i]['lastlogintime']);
			}


			$network = array('results'=>$collegeNetwork1,'totalCount'=>$collegeNetworkCount[0]['count'],'userId'=>$userId,'email'=>$email,'userNetwork'=>$getuserNetwork);
			echo json_encode($network);
		}
		else {
			echo " ";
		}


	}


	function addtoSchoolNetwork()
	{
		$this->validation = 1;
		$this->init();
		$appId = 1;
		$userStatus = $this->input->post('status');
		$course = $this->input->post('courseId');
		$userId = $this->input->post('loggeduserid');
		$collegeId = $this->input->post('collegeId');
		$cityId = $this->input->post('cityid');
		//if($userStatus != "Faculty")
		$year = $this->input->post('GradYear');
		$networkClient = new Network_client();
        if($this->userId != '')
        {
            $response = $networkClient->addtoSchoolNetwork($appId,$userStatus,$year,$this->userId,$collegeId,$cityId);
            if($response > 0 && (!($response == "false")))
            {
                if($userStatus == "Faculty")
                    echo 1;
                else
                {
                    if($response == -1)
                        echo 2;
                    else
                        echo 0;
                }
            }
            else
            {
                if($response == -1)
                    echo 2;
                else
                    echo -1;
            }
        }
        else
        echo 'invalid user';
	}

	function submitSchoolComment()
	{
		$this->init();
		$appId = 1;
		$userId = $_POST['userIdComment'];
		$collegeId = $_POST['collegeId'];
		$comment = $_POST['comment'];
		$titleofcomment = $_POST['titleofComment'];

		$networkClient = new Network_client();
		$response = $networkClient->insertSchoolComment($appId,$userId,$collegeId,$comment,$titleofcomment);
		$network = array('results'=>$response,'totalCount'=>count($response));

		if(is_array($response) && count($response))
		{

			echo json_encode($network);
		}
		else
			echo "";

	}

	function showRecentSchoolComments($start = 0 ,$count = 3,$schoolId)
	{
		$this->init();
		$appId = 1;
		$networkClient = new Network_client();
		$commentCount = $networkClient->commentCount($appId,$schoolId);
		$response = $networkClient->showRecentSchoolComments($appId,$start,$count,$schoolId);
		if(is_array($response) && count($response))
		{
			$network = array('results'=>$response);
			echo json_encode($network);
		}
		else
			echo "";
	}


	function indexSchools($schoolId)
	{
		$this->init();
		$blog_client = new Network_client();
		$result = $blog_client->getSchoolsForIndex(12,$schoolId);
		$request=array('cityList'=>$result[0]['city_id'],'countryList'=>2,'title' => $result[0]['SchoolName'],'Id' => $result[0]['SchoolId'],'type' => 'schoolgroups');
		$this->load->library('blogs/Blog_client');
		//    print_r($request);
		$blog_client = new Blog_client();
		$indexResult = $blog_client->indexIt(12,$request);
		print_r($indexResult);
	}

	function indexTestPrepGroups($schoolId)
	{
		$this->init();
		$blog_client = new Network_client();
		$result = $blog_client->getTestPrepGroupForIndex(12,$schoolId);
        echo print_r($result);
		$request=array('countryList'=>$result[0]['countryId'],'title' => $result[0]['title'],'content'=>$result[0]['content'],'Id' => $result[0]['Id'],'abbr'=> $result[0]['abbr'],'type' => 'examgroup');
		$this->load->library('blogs/Blog_client');
		    print_r($request);
		$blog_client = new Blog_client();
		$indexResult = $blog_client->indexIt(12,$request);
		//print_r($indexResult);
	}

	/* Functions for school Network Page end*/

	function discussionAll($collegeId,$start = 0 ,$end = 30,$type,$collegeName,$grouptype = 'group')
	{
		$this->init();
		$msgbrdClient = new Message_board_client();
		$displayData['collegeId'] = $collegeId;
		$displayData['cityid'] = $cityid;
		$displayData['grouptype'] = $grouptype;
		$displayData['join'] = $join;
		$Validate = $this->checkUserValidation();
		$networkClient = new Network_client();
		$displayData['validateuser'] = $Validate;
		if(is_array($Validate) && $Validate != "false")
		{
			$displayData['userId'] = $Validate[0]['userid'];
			$membercheck = $networkClient->checkifmember(1,'college',$Validate[0]['userid'],$collegeId);
			$flag = $membercheck['flag'];
			$displayData['member'] = $flag;
			$displayData['statusFlag'] = $membercheck['status'];
		}
		else
			$displayData['userId'] = 0;

		$groups = $msgbrdClient->getTopicsForGroups(1,$collegeId,$grouptype,$start,$end);
		error_log_shiksha(print_r($groups,true));
		$displayData['messages'] = $groups;
		$displayData['collegeName'] = $collegeName;
		$this->load->view('network/discussionViewAll',$displayData);
	}

	function getMessages($collegeId,$start,$end,$type,$grouptype)
	{
		$this->init();
		$msgbrdClient = new Message_board_client();
		$results = $msgbrdClient->getTopicsForGroups(1,$collegeId,$grouptype,$start,$end);
		$groups = array('results' => $results);
		if($type == "return")
			return $groups;
		else
			echo json_encode($groups);

	}

	function MembersAll($collegeId,$start = 0 ,$count = USERS_VIEW_ALL_PAGE,$collegeName,$grouptype = 'group')
	{
        show_404();
		$this->init();
		$msgbrdClient = new Message_board_client();
		$displayData['collegeId'] = $collegeId;
		$displayData['cityid'] = $cityid;
		$displayData['join'] = $join;
		$displayData['grouptype'] = $grouptype;
		$Validate = $this->checkUserValidation();
		$networkClient = new Network_client();
		$displayData['validateuser'] = $Validate;
		if(is_array($Validate) && $Validate != "false")
		{
			$displayData['userId'] = $Validate[0]['userid'];
			$membercheck = $networkClient->checkifmember(1,'college',$Validate[0]['userid'],$collegeId,$grouptype);
			$flag = $membercheck['flag'];
			$displayData['member'] = $flag;
			$displayData['statusFlag'] = $membercheck['status'];
		}
		else
			$displayData['userId'] = 0;

		$usergroup = $this->showuserCollegeNetwork($start,$count,$collegeId,"All","All","return",$grouptype);
		$displayData['usergroup'] = $usergroup;
		$displayData['collegeName'] = $collegeName;
		$this->load->view('network/ViewAllMembers',$displayData);
	}

}
?>

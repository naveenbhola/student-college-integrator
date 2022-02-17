<?php

/**
 *
 * Login server class file
 */

/**
 *
 * Login server class
 */
class Login_server extends MX_Controller
{
	/**
	 * Index function for initialization purposes
	 */
	function index()
	{
		$this->dbLibObj = DbLibCommon::getInstance('User');
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('userconfig');

		$config['functions']['validateuser'] = array('function' => 'login_server.validateuser');
		$config['functions']['logOffUser'] = array('function' => 'login_server.logOffUser');
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}

	/**
	 * Function to validate the user
	 * @param object $request
	 */
	function validateuser($request)
	{
		$parameters = $request->output_parameters();
		$user=$parameters['0'];
		$source=$parameters['1'];
		$values = explode("|",$user);
		$uname = $values[0];
		$password = $values[1];
		$appID = 1;

		$dbHandle = $this->_loadDatabaseHandle('write');

		if($uname == '' || $password == 'd41d8cd98f00b204e9800998ecf8427e')
		{
			$response = "false";
			return $this->xmlrpc->send_response($response);
		}
		if($source == 'login')
		{
			$sql = "select tEducationLevel.EducationLevel as  educationlevel,IFNULL(Options,tuser.educationlevel) as Options, tuser.userid,displayname,ePassword,age,email,isdCode,mobile,city,country,profession,avtarimageurl,usergroup,firstname,lastname,dateofbirth,quicksignupFlag,publishRequestEBrochure,publishInstituteFollowing,publishInstituteUpdates,publishBestAnswerAndLevelActivity,publishQuestionOnFB,publishAnswerOnFB,publishDiscussionOnFB,publishAnnouncementOnFB,publishArticleFollowing,userPointValue,gender,experience,tuserflag.* from  tuser inner join tuserflag on(tuserflag.userId = tuser.userId) left outer join  userPointSystem on tuser.userid = userPointSystem.userId left outer join tEducationLevel on (tuser.EducationLevel = tEducationLevel.EducationId) where email = ? and ePassword = ?";
		}
		else
		{
			$sql = "select tEducationLevel.EducationLevel as educationlevel,IFNULL(Options,tuser.educationlevel) as Options, tuser.userid,displayname,ePassword,age,email,isdCode,mobile,city,country,profession,avtarimageurl,usergroup,firstname,lastname,dateofbirth,quicksignupFlag,publishRequestEBrochure,publishInstituteFollowing,publishInstituteUpdates,publishBestAnswerAndLevelActivity,publishQuestionOnFB,publishAnswerOnFB,publishDiscussionOnFB,publishAnnouncementOnFB,publishArticleFollowing,userPointValue,gender,experience,tuserflag.* from  tuser inner join tuserflag on(tuserflag.userId = tuser.userId) left outer join  userPointSystem on tuser.userid = userPointSystem.userId left outer join tEducationLevel on (tuser.EducationLevel = tEducationLevel.EducationId) where email = ? and ePassword = ? and tuserflag.ownershipchallenged = '0' and tuserflag.abused = '0'" ;
		}
		$query = $dbHandle->query($sql,array($uname,$password));
		$row = $query->row();
		
		error_log('number of rows'.$query->num_rows());
		if($query->num_rows() == 0)
		{
			$response = "false";
			return $this->xmlrpc->send_response($response);
		}
		$quicksignuser = 0;
		$requestsignuser = 0;
		$id = $row->userid;
		$sql = "update tuser set lastlogintime = now() where userid = ?";
		$query = $dbHandle->query($sql,array($id));
		// $catOfInterest = $row->catOfInterest;
		// if(is_numeric($row->catOfInterest))
		// {
		// 	$sql = "select group_concat(name) as category from categoryBoardTable where boardId in (".$row->catOfInterest.")";
		// 	$query1 = $dbHandle->query($sql);
		// 	$rowcat = $query1->row();
		// 	$catOfInterest = $rowcat->category;
		// }

		$orusergroup = $row->quicksignupFlag;
		$displayname = $row->displayname;
		$password = $row->ePassword;
		$uname = $row->email;
		$isdCode = $row->isdCode;
		$mobile = $row->mobile;
		$city = $row->city;
		$age = $row->age;
		$country = $row->country;
		$dob = $row->dateofbirth;
		$profession = $row->profession;
		$usergroup = $row->usergroup;
		$publishRequestEBrochure=$row->publishRequestEBrochure;
		$publishInstituteFollowing=$row->publishInstituteFollowing;
		$publishInstituteUpdates = $row->publishInstituteUpdates;
		$publishArticleFollowing = $row->publishArticleFollowing;
		$publishBestAnswerAndLevelActivity = $row->publishBestAnswerAndLevelActivity;
		$publishQuestionOnFB = $row->publishQuestionOnFB;
		$publishAnswerOnFB = $row->publishAnswerOnFB;
		$publishDiscussionOnFB = $row->publishDiscussionOnFB;
		$publishAnnouncementOnFB = $row->publishAnnouncementOnFB;
		$experience = $row->experience;
		
		if($row->avtarimageurl == null || $row->avtarimageurl == '')
			$avtarimageurl = '';
		else
			$avtarimageurl = $row->avtarimageurl;
		$userPointValue = $row->userPointValue;
		$strcookie = $uname.'|'.$password;
		$cityName = $row->city;
		if(is_numeric($row->city))
		{
			$queryCmd = "select city_name as city from countryCityTable where city_id = ?";
			error_log_shiksha($queryCmd);
			$querytmp = $dbHandle->query($queryCmd, array($row->city));
			$row1 = $querytmp->row();
			$cityName = $row1->city;
		}
		/*if($id>0){
			$this->load->library(array('message_board_client'));
			$msgbrdClient = new Message_board_client();
			$resultSession = $msgbrdClient->getFBSessionKey(1,$id);
			if(is_array($resultSession)){
			  $sessionKey = $resultSession[0]['sessionData'][0]['sessionKey'];
			}
		}*/
		$msgArray = array();
		array_push($msgArray,array(
					array(
						'userid'=>array($id),
						'cookiestr'=>array($strcookie),
						'displayname'=>array($displayname),
						'isdCode'=>array($isdCode),
						'mobile'=>array($mobile),
						'city'=>array($city),
						'cityname'=>array($cityName),
						'DOB'=>array($dob),
						'catofinterest'=>array(),
						'educationLevel'=>array($row->educationlevel),
						'degree'=>array($row->Options),
						'profession'=>array($profession),
						'usergroup'=>array($usergroup),
						'orusergroup'=>array($orusergroup),
						'avtarurl'=> array($avtarimageurl),
						'age'=> array($age),
						'country'=>array($country),
						'firstname'=>array($row->firstname),
						'lastname'=>array($row->lastname),
						'quicksignuser'=>array($quicksignuser),
						'requestinfouser'=>array($requestsignuser),
						'userPointValue'=>array($userPointValue),
						'pendingverification' =>array($row->pendingverification),
						'hardbounce' =>array($row->hardbounce),
						'unsubscribe' =>array($row->unsubscribe),
						'ownershipchallenged' =>array($row->ownershipchallenged),
						'softbounce' =>array($row->softbounce),
						'abused' =>array($row->abused),
						'mobileverified' =>array($row->mobileverified),
						'emailverified' =>array($row->emailverified),
						'emailsentcount' =>array($row->emailsentcount),
						'gender' =>array($row->gender),
						'publishRequestEBrochure'=>array($publishRequestEBrochure),
						'publishInstituteFollowing'=>array($publishInstituteFollowing),
						'publishInstituteUpdates' => array($publishInstituteUpdates),
						'publishArticleFollowing' => array($publishArticleFollowing),
						'publishBestAnswerAndLevelActivity' => array($publishBestAnswerAndLevelActivity),
						'publishQuestionOnFB' => array($publishQuestionOnFB),
						'publishAnswerOnFB' => array($publishAnswerOnFB),
						'publishDiscussionOnFB' => array($publishDiscussionOnFB),
						'publishAnnouncementOnFB' => array($publishAnnouncementOnFB),
						'experience' => array($experience)
						//'fbSessionKey' =>array($sessionKey)
					     ),'struct')
					     );

		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * Function to log off the user
	 *
	 * @param object $request
	 */
	function logOffUser($request)
	{
		$parameters = $request->output_parameters();
		$user=$parameters['0'];
		$values = explode("|",$user);
		$uname = $values[0];
		$password = $values[1];
		$appID = 1;

		$dbHandle = $this->_loadDatabaseHandle('write');

		//$sql = "update tuser set lastlogintime = ''  where email = ? and password = ?";
		//error_log_shiksha($sql);					
		//$query = $dbHandle->query($sql,array($uname,$password));
		//error_log(print_r($dbHandle,true));
		//$response = $dbHandle->affected_rows();
		
		$response = 1;
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * Function to get the user details
	 * @param object $request
	 */
	function getuserdetails($request)
	{
		$parameters = $request->output_parameters();
		$user=$parameters['0'];
		$remember=$parameters['2'];

		$values = explode("|",$user);
		$uname = $values[0];
		$password = $values[1];
		$appID = 1;

		$dbHandle = $this->_loadDatabaseHandle();
		$queryCmd = "select id.url, id.description, id.thumburl, mp.userid from tMediaMapping mp join tImageData id on(mp.mediaid = id.mediaid)
			where mp.type = 'user' and mp.typeid = ? and mp.mediatype = 'image'";


		log_message('debug','getUserDetails query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd, array($userid));
		$row = mysql_fetch_array(mysql_query($sql));

		if($row == null)
		{

			$response = array("");
			return $this->xmlrpc->send_response($response);

		}
		$uname = $row[1];
		$password = $row[2];
		$id = $row[0];
		$email = $row[3];
		$mobile = $row[4];
		$city = $row[5];
		$profession = $row[6];

		$strcookie = $uname.'|'.$password;
		/*$response = array(
		  array("userid"=> array($id)),
		  array("cookiestr"=> array($strcookie)),

		  'struct');*/


		$msgArray = array();
		array_push($msgArray,array(
					array(
						'userid'=>array($id),
						'cookiestr'=>array($strcookie)
					     ),'struct')
			  );
		$response = array($msgArray,'struct');

		return $this->xmlrpc->send_response($response);



	}





}
?>

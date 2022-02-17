<?php
/**

   Copyright 2007 Info Edge India Ltd

   $Rev::               $:  Revision of last commit
   $Author: build $:  Author of last commit
   $Date: 2010/05/31 06:24:46 $:  Date of last commit

   This class provides the Blog Server Web Services.
   The blog_client.php makes call to this server using XML RPC calls.

   $Id: Register_server.php,v 1.145 2010/05/31 06:24:46 build Exp $:

 */

 /**
  * Register_server class
  *
  */
class Register_server extends MX_Controller
{
	/**
	 * Index function to load library and initialization purposes
	 */
	function index()
	{
		$this->dbLibObj = DbLibCommon::getInstance('User');
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('userconfig');
		$this->load->library('schoolconfig');
        	$this->load->library('ndnc_lib');
		$config['functions']['adduser_new'] = array('function' => 'Register_server.adduser_new');
		$config['functions']['getDetailsforUsers'] = array('function' => 'Register_server.getDetailsforUsers');
		$config['functions']['getUserDetails'] = array('function' => 'Register_server.getUserDetails');
		$config['functions']['EducationLevel'] = array('function' => 'Register_server.EducationLevel');
		$config['functions']['updateUser'] = array('function' => 'Register_server.updateUser');
		$config['functions']['checkAvailability'] = array('function' => 'Register_server.checkAvailability');
		$config['functions']['sResetPassword'] = array('function' => 'Register_server.sResetPassword');
		$config['functions']['schangePassword'] = array('function' => 'Register_server.schangePassword');
		$config['functions']['schangeEmail'] = array('function' => 'Register_server.schangeEmail');
		$config['functions']['sgetIdforEmail'] = array('function' => 'Register_server.sgetIdforEmail');
		$config['functions']['populatecolleges'] = array('function' => 'Register_server.populatecolleges');
		$config['functions']['getDetailsforDisplayname'] = array('function' => 'Register_server.getDetailsforDisplayname');
		$config['functions']['requestCall'] = array('function' => 'Register_server.requestCall');
		$config['functions']['quicksignupuser'] = array('function' => 'Register_server.quicksignupuser');
		$config['functions']['updateuserinfo'] = array('function' => 'Register_server.updateuserinfo');
		$config['functions']['updateuserPointSystem'] = array('function' => 'Register_server.updateuserPointSystem');
		$config['functions']['senduserResponse'] = array('function' => 'Register_server.senduserResponse');
		$config['functions']['sendVerification'] = array('function' => 'Register_server.sendVerification');
		$config['functions']['sendReminder'] = array('function' => 'Register_server.sendReminder');
		$config['functions']['getEdLevelFromId'] = array('function' => 'Register_server.getEdLevelFromId');
		$config['functions']['updatetUserInterest'] = array('function' => 'Register_server.updatetUserInterest');
		$config['functions']['updateUserGen'] = array('function' => 'Register_server.updateUserGen');
		$config['functions']['getinfoifexists'] = array('function' => 'Register_server.getinfoifexists');
		$config['functions']['getUserPointLevel'] = array('function' => 'Register_server.getUserPointLevel');
		$config['functions']['sAddUser'] = array('function' => 'Register_server.sAddUser');
		$config['functions']['sUpdateUser'] = array('function' => 'Register_server.sUpdateUser');
		$config['functions']['sGetUserSpecialization'] = array('function' => 'Register_server.sGetUserSpecialization');
		$config['functions']['sGetUserDetailForMigration'] = array('function' => 'Register_server.sGetUserDetailForMigration');
		$config['functions']['sGetIdForCityName'] = array('function' => 'Register_server.sGetIdForCityName');
		$config['functions']['sGetIdForCountryName'] = array('function' => 'Register_server.sGetIdForCountryName');
		$config['functions']['sGetPreferencesForUser'] = array('function' => 'Register_server.sGetPreferencesForUser');
		$config['functions']['suserInfoSystemPoint'] = array('function' => 'Register_server.suserInfoSystemPoint');
		$config['functions']['scheckforleadinfo'] = array('function' => 'Register_server.scheckforleadinfo');
                $config['functions']['setTheCookie'] = array('function' => 'Register_server.setTheCookie');
                $config['functions']['sGetCategoryIdForUser'] = array('function' => 'Register_server.sGetCategoryIdForUser');

		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}
	
	/**
	 * Function to check the lead info
	 *
	 * @param object $request
	 */
    function scheckforleadinfo($request)
    {
        $parameters = $request->output_parameters();
        $userId = $parameters['0'];
        $dbHandle = $this->_loadDatabaseHandle();

        $queryCmd = "select * from tLeadInfo where userid = ? and pagename = 'studyabroad'";
        error_log($queryCmd.'RESPONSE');
        $query = $dbHandle->query($queryCmd, array($userId));
        error_log($row->SpecializationId.'RESPONSE');
        return $this->xmlrpc->send_response($query->num_rows());
    }
	/**
	 * Function to get the User Specialization
	 * 
	 * @param object $request
	 */
	function sGetUserSpecialization($request) {
	        $parameters = $request->output_parameters();
	        $categoryId = $parameters['0'];
	        $dbHandle = $this->_loadDatabaseHandle();
	        $queryCmd = "select ifnull((select SpecializationId from tCourseSpecializationMapping where CategoryId = ? and SpecializationName = 'All' and CourseName = 'All' and status = 'live'), (select SpecializationId from tCourseSpecializationMapping, categoryBoardTable where categoryBoardTable.parentId = CategoryId and SpecializationName = 'All' and CourseName = 'All' and boardId= ?  and tCourseSpecializationMapping.status = 'live')) as SpecializationId";
		error_log($queryCmd.'RESPONSE');
			$query = $dbHandle->query($queryCmd, array($categoryId, $categoryId));
			$row = $query->row();
		error_log($row->SpecializationId.'RESPONSE');
	        return $this->xmlrpc->send_response($row->SpecializationId);
	    }
	/**
	* Function to get User Details for migration
	* 
	* @param object $request
	*/
	function sGetUserDetailForMigration($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

        $queryCmd = 'select userid, userstatus, institute , countryofEducation , cityofEducation, graduationyear, educationLevel, (select group_concat(distinct(tuserInterest.keyValue)) from tuserInterest where tuserInterest.userId = tuser.userid and tuserInterest.keyofInterest like "countries%") as country , (select group_concat(distinct(tuserInterest.keyValue)) from tuserInterest where tuserInterest.userId = tuser.userid and tuserInterest.keyofInterest = "category") as category , (select group_concat(distinct(tuserInterest.keyValue)) from tuserInterest where tuserInterest.userId = tuser.userid and tuserInterest.keyofInterest = "subCategory") as subCategory, (select group_concat(distinct(tuserInterest.keyValue)) from tuserInterest where tuserInterest.userId = tuser.userid and tuserInterest.keyofInterest = "city") as city from tuser where userId =?';
		error_log_shiksha($queryCmd);
		error_log_shiksha($userId);
		log_message('debug','getUser Details query cmd is ' . $queryCmd);

		$msgArray = array();
		$query = $dbHandle->query($queryCmd, array($userId));
			foreach ($query->result_array() as $row){
				array_push($msgArray,array($row,'struct'));
            }
		$response = array($msgArray,'struct');
		error_log_shiksha(print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}
/**
 * Function to update the User Interest
 * 
 * @param object $request
 */
function updatetUserInterest($request) {
        $parameters = $request->output_parameters();
        $key = $parameters['1'];
        $userId = $parameters['2'];
        $valueStr = $parameters['3'];
        $value = json_decode($valueStr,true);
        error_log("UIO".$value);
        error_log("UIO ".$userId." ".print_r($value,true)." ".$key);
        $dbHandle = $this->_loadDatabaseHandle('write');


        $queryCmd = 'delete from tuserInterest where userId=? and keyofInterest=?';
		$query = $dbHandle->query($queryCmd, array($userId, $key));
        for($i = 0; $i< count($value);$i++) {
            $queryCmd = "insert into tuserInterest values('',?,?,?)";
            $query = $dbHandle->query($queryCmd, array($userId, $key, $value[$i]));

        }
    }
	
	/**
	 * Function to get the Education Level 
	 * @param object $request
	 */
    function getEdLevelFromId($request) {
        $parameters = $request->output_parameters();
        error_log("LKJ ".print_r($parameters,true));
        $edLevelId = $parameters['1'];
        $dbHandle = $this->_loadDatabaseHandle();

		$Flag = 0;
		$code = '';

		$sql = "select Options from tEducationLevel where EducationId = ? limit 1";
        error_log("LKJ12121".$sql);
		$query = $dbHandle->query($sql, array($edLevelId));
if($query->num_rows() == 1)
			$response1 = $row->Options;
		else
			$response1 = $edLevelId;
        $response = array(
                array("level"=> $response1),
                'struct');
        return $this->xmlrpc->send_response($response);


    }
	
	/**
	 * Function suserInfoSystemPoint
	 * @param object $request
	 */
	function suserInfoSystemPoint($request) {
        $parameters = $request->output_parameters();
		$userId=$parameters['0'];
		$action=$parameters['1'];
        error_log("LKJ ".print_r($parameters,true));
        $dbHandle = $this->_loadDatabaseHandle('write');
		$this->load->model('UserPointSystemModel');
		$response = $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,$action);
        return $this->xmlrpc->send_response($response);
    }
	
	/**
	 * Function to update the User Info
	 *
	 * @param object $request
	 */
	function updateuserinfo($request)
	{
		$parameters = $request->output_parameters();
		$userid=$parameters['0'];
		$userdetail=$parameters['1'];
		$country=$parameters['2'];
		$city=$parameters['3'];
		$mobile=$parameters['4'];
		$viamobile=$parameters['5'];
		$viaemail=$parameters['6'];
		$newsletteremail=$parameters['7'];
		$appID = $parameters[8];
		$educationlevel = $parameters['9'];
		$experience = $parameters['10'];
		$DOB = $parameters['11'];
		$institute = $parameters['12'];
		$youare = $parameters['13'];
		$gradYear = $parameters['14'];
		$usergroup = $parameters['15'];
		$firstname = $parameters['16'];
		$lastname = $parameters['17'];
		$categories = $parameters['18'];
		$countries = $parameters['19'];
		$countryofedu = $parameters['20'];
		$cityofedu = $parameters['21'];
		$password = $parameters['22'];
		$displayname = $parameters['23'];
		$mdpassword = $parameters['24'];
		$requestuser = $parameters['25'];
		$cities = $parameters['26'];

		$dbHandle = $this->_loadDatabaseHandle('write');

		$Flag = 0;
		$code = '';

		$sql = "select usergroup from tuser where userid = ?";
		$query = $dbHandle->query($sql, array($userid));
		$row = $query->row();

		if($requestuser == "requestinfo")
		{
			$sql = "update tuser set profession = ".$dbHandle->escape($userdetail).",viamobile = ".$dbHandle->escape($viamobile).",viaemail = ".$dbHandle->escape($viaemail).",newsletteremail = ".$dbHandle->escape($newsletteremail).",lastmodifiedOn = now(),educationlevel = ".$dbHandle->escape($educationlevel).",experience = ".$dbHandle->escape($experience).",lastlogintime = now(),";
			if($row->usergroup != "enterprise" && $row->usergroup != "sums")
				$sql .= " usergroup = 'user',";
			$sql .= " institute = ".$dbHandle->escape($institute).",userstatus = ".$dbHandle->escape($youare).",graduationyear = ".$dbHandle->escape($gradYear).",countryofEducation = ".$dbHandle->escape($countryofedu).",cityofEducation = ".$dbHandle->escape($cityofedu).",quicksignupFlag = 'user' where userid = ".$dbHandle->escape($userid);
		}
		else
		{
			if($requestuser == "tempuser")
			{
				$sql = "update tuser set lastmodifiedOn = now(),educationlevel = ".$dbHandle->escape($educationlevel).",experience = ".$dbHandle->escape(experience).",lastlogintime = now(),";

				if($row->usergroup != "enterprise" && $row->usergroup != "sums")
					$sql .= " usergroup = 'user',";
				$sql .= " institute = ".$dbHandle->escape($institute).",userstatus = ".$dbHandle->escape($youare).",graduationyear = ".$dbHandle->escape($gradYear).",countryofEducation = ".$dbHandle->escape($countryofedu).",cityofEducation = ".$dbHandle->escape($cityofedu).",quicksignupFlag = 'user' where userid = ".$dbHandle->escape($userid);
			}
			else
			{
				$sql = "update tuser set mobile = ".$dbHandle->escape($mobile).", city = ".$dbHandle->escape($city).",profession = ".$dbHandle->escape($userdetail).",viamobile = ".$dbHandle->escape($viamobile).",viaemail = ".$dbHandle->escape($viaemail).",newsletteremail = ".$dbHandle->escape($newsletteremail).",lastmodifiedOn = now(),educationlevel = ".$dbHandle->escape($educationlevel).",experience = ".$dbHandle->escape($experience).",lastlogintime = now(),";
				if($row->usergroup != "enterprise" && $row->usergroup != "sums")
					$sql .= " usergroup = 'user',";
				$sql .= " dateofbirth = ".$dbHandle->escape($DOB).",institute = ".$dbHandle->escape($institute).",userstatus = ".$dbHandle->escape($youare).",graduationyear = ".$dbHandle->escape($gradYear).",country = ".$dbHandle->escape($country).",firstname = ".$dbHandle->escape($firstname).",lastname = ".$dbHandle->escape($lastname).",countryofEducation = ".$dbHandle->escape($countryofedu).",cityofEducation = ".$dbHandle->escape($cityofedu).",quicksignupFlag = 'user' where userid = ".$dbHandle->escape($userid);
			}
		}
		error_log($sql);

		$query = $dbHandle->query($sql);

		if($dbHandle->affected_rows() == 1)
		{

			if($categories != '')
			{
				$sql3 = "insert into tuserInterest values('',?,'category',?)";
				$dbHandle->query($sql3, array($userid, $categories));
			}
            if($subCategories != '')
			{
				$sql5 = "insert into tuserInterest values('',?,'subCategory',?)";
                error_log("FGH ".$sql5);
				$dbHandle->query($sql5, array($userid, $subCategories));
			}

			if($countries != '')
			{
				$sql4 = "insert into tuserInterest values('',?,'countries',?)";
				$dbHandle->query($sql4, array($userid, $countries));
			}
			if($cities != '')
			{
				$sql4 = "insert into tuserInterest values('',?,'city',?)";
				$dbHandle->query($sql4, array($userid, $cities));
			}

			$response = array(
					array("status"=> array($userid)),
					'struct');
			error_log($response.'ReSPONSE');
			return $this->xmlrpc->send_response($response);

		}
		else
		{					$response = array(
				array("status"=> array('0')),
				'struct');
		error_log($response['status']);
		return $this->xmlrpc->send_response($response);
		}

	}
	
	/**
	 * Function to add new user
	 *
	 * @param object $request
	 */
	function adduser_new($request)
	{
		$parameters = $request->output_parameters();
		$username=$parameters['0'];
		$ePassword=$parameters['2'];
		$displayname=$parameters['3'];
		$userdetail=$parameters['4'];
		$country=$parameters['5'];
		$city=$parameters['6'];
		$mobile=$parameters['7'];
		$viamobile=$parameters['8'];
		$viaemail=$parameters['9'];
		$newsletteremail=$parameters['10'];
		$appID = $parameters[11];
		$educationlevel = $parameters['12'];
		$experience = $parameters['13'];
		$DOB = $parameters['14'];
		$institute = $parameters['15'];
		$youare = $parameters['16'];
		$gradYear = $parameters['17'];
		$usergroup = $parameters['18'];
		$firstname = $parameters['19'];
		$lastname = $parameters['20'];
		$countries = $parameters['22'];
		$categories = $parameters['21'];
		$countryofedu = $parameters['23'];
		$cityofedu = $parameters['24'];
		$cityofhigheredu = $parameters['25'];
		$quicksignupFlag = $parameters['26'];
		$age = $parameters['27'];
		$gender = $parameters['28'];
		$landline = $parameters['29'];
		$sourceurl = $parameters['30'];
		$sourcename = $parameters['31'];
		$coordinates = $parameters['32'];
		$resolution = $parameters['33'];
		$landingpage = $parameters['34'];
		$browser = $parameters['35'];
		$subCategories = $parameters['36'];
		$preferredCity = $parameters['37'];
		error_log('FGH 12345 '.$subCategories);

		$dbHandle = $this->_loadDatabaseHandle('write');

		//$query = $dbHandle->query($sql);
		//Ashish : Commenting the code below.
		/*
		if($DOB != '' && $age == 0)
		{
			$age = date("Y") - substr($DOB,0,4);
		}
		*/
		// Ashish : updating the values of age and creating the DOB (virtually)
		if(($DOB!= '' || $DOB != 0) &&($age == 0 || $age == '') ) {
			$age = $DOB;
		}
		$DOB = date("Y") - $age;
		$DOB .= '-01-01';
		//Ashish: Changes Done
		error_log($age.'AGE');
		$sql = "select userid from tuser where email = ?";
		log_message('debug','getUser Details query cmd is ' . $sql);
		$query = $dbHandle->query($sql, array($username));
		$row = $query->row();
		$sql = "select displayname from tuser where displayname = ?";
		$query = $dbHandle->query($sql, array($displayname));
		$row1 = $query->row();
		if($row != null || $row1 != null)
		{
			$email = 0;
			$displayname = 0;
			error_log_shiksha('here');
			if($row != null)
				$email = -1;
			if($row1 != null)
				$displayname = -1;

			error_log_shiksha('user');
			$msgArray = array('status'=> -1,
					'email'=>$email,
					'displayname'=>$displayname,
					);
			$response = array($msgArray,'struct');
			error_log_shiksha(print_r($response,true));
			return $this->xmlrpc->send_response($response);
		}

		$Flag = 0;
		$code = '';
		while($Flag != 1)
		{
			$possible = '23456789bcdfghjkmnpqrstvwxyz';
				$i = 0;
				for ($i=0;$i < 6 ; $i++) {
					$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
				}
			$sql1 = "select userId from tuser where randomkey = ?";
			error_log_shiksha($sql1);
			$query = $dbHandle->query($sql1, array($code));
			if($query->num_rows() == 0)
				$Flag = 1;
		}
		error_log_shiksha($code.'CODE');

		$date = date("y.m.d");
		$logintime = date("y.m.d:h:m:s");

		error_log_shiksha($logintime.'LOGINTIME');
					if($youare == "School")
                        $educationlevel = "School";

		$userid = '';

		if(($usergroup == "cms") || ($usergroup == "privileged")){
			$queryUserid = $dbHandle->query('select MAX(userid) as specialId from tuser where userid<1000'); //1-100 Reserved for CMS and Privileged users
			$row = $queryUserid->row_array();
			$userid = ($row['specialId']+1);
			//error_log_shiksha("SHASHW 11111111");
			$sql = "insert into tuser (userid, displayname, email, mobile, ePassword, city, profession, viamobile, viaemail, newsletteremail, avtarimageurl, usercreationDate, lastModifiedOn, educationlevel, experience, randomkey, lastlogintime, usergroup, dateofbirth, institute, userstatus, graduationyear, country, firstname, lastname, countryofEducation, cityofEducation, quicksignupFlag, age, gender, landline, secondaryemail, Locality, publishInstituteFollowing, publishInstituteUpdates, publishRequestEBrochure, publishBestAnswerAndLevelActivity, publishArticleFollowing, publishQuestionOnFB, publishAnswerOnFB, publishDiscussionOnFB, publishAnnouncementOnFB) values (?,?,?,?,?,?,?,?,?,?,?,now(),'',?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'','','1','1','1','0','1','0','0','0','0')";
			error_log_shiksha($sql);

			$query = $dbHandle->query($sql, array($userid, $displayname,$username,$mobile,$ePassword,$city,$userdetail,$viamobile,$viaemail,$newsletteremail,'/public/images/photoNotAvailable.gif', $educationlevel,$experience,$code,$logintime,$usergroup,$DOB,$institute,$youare,$gradYear,$country,$firstname,$lastname,$countryofedu,$cityofedu,$quicksignupFlag,$age,$gender,$landline));
		}else{
			//error_log_shiksha("SHASHW 2222222");
			//   $usergroup = "user";
			$sql = "insert into tuser (userid, displayname, email, mobile, ePassword, city, profession, viamobile, viaemail, newsletteremail, avtarimageurl, usercreationDate, lastModifiedOn, educationlevel, experience, randomkey, lastlogintime, usergroup, dateofbirth, institute, userstatus, graduationyear, country, firstname, lastname, countryofEducation, cityofEducation, quicksignupFlag, age, gender, landline, secondaryemail, Locality, publishInstituteFollowing, publishInstituteUpdates, publishRequestEBrochure, publishBestAnswerAndLevelActivity, publishArticleFollowing, publishQuestionOnFB, publishAnswerOnFB, publishDiscussionOnFB, publishAnnouncementOnFB) values ('',?,?,?,?,?,?,?,?,?,?,now(),'',?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'','','1','1','1','0','1','0','0','0','0')";
			error_log($sql);

			$query = $dbHandle->query($sql, array($displayname,$username,$mobile,$ePassword,$city,$userdetail,$viamobile,$viaemail,$newsletteremail,'/public/images/photoNotAvailable.gif', $educationlevel,$experience,$code,$logintime,$usergroup,$DOB,$institute,$youare,$gradYear,$country,$firstname,$lastname,$countryofedu,$cityofedu,$quicksignupFlag,$age,$gender,$landline));
			$userid = $dbHandle->insert_id();
			error_log_shiksha("SHASHWWWWWWWWW insert id=> ".$dbHandle->insert_id());
		} error_log("logInShiksha ".print_r($sql,true));

		if($dbHandle->affected_rows() == 1)
		{

			$querkey = 'select keyid from tkeyTable where keyname = ?';
			$query = $dbHandle->query($querkey, array($sourcename));
			$row1 = $query->row();
			$keyid = $row1->keyid;
			$quersource = "insert into tusersourceInfo (userid,keyid,coordinates,referer,type,time,resolution,landingpage,keyquery,browser) values(?,?,?,?,'registration',now(),?,?,?,?)";
			$queryres = $dbHandle->query($quersource, array($userid,$keyid,$coordinates,$sourceurl,$resolution,$landingpage,$querkey,$browser));
            // ---------------- NDNC CHECK START----------------//
            $this->load->library('ndnc_lib');
            $ndnc_lib = new ndnc_lib();
	    if($mobile){
            $result = $ndnc_lib->ndnc_mobile_check($mobile);
	    }else{
            $result = 'na';
	    }

	    //----------is LDB user Check Start-------------//
	    $resultLDB = $this->isLDBUser($userid);
            $user_id=$resultLDB[0]['UserId'];
	    if($userid==$user_id){
	            $isLDBUser='YES';
	    }else{
        	    $isLDBUser='NO';
	    }

            if($result == 'true')
            {
                $sqlflag = "insert into tuserflag(`userId`, `pendingverification`, `hardbounce`, `unsubscribe`, `ownershipchallenged`, `softbounce`, `abused`, `mobileverified`, `emailsentcount`, `emailverified`, `isNDNC`, `isLDBUser`) values(?,'1','0','0','0','0','0','0',0,'0','YES',?)";
                $dbHandle->query($sqlflag, array($userid, $isLDBUser));
            }
            elseif ($result == 'false')
            {
                $sqlflag = "insert into tuserflag(`userId`, `pendingverification`, `hardbounce`, `unsubscribe`, `ownershipchallenged`, `softbounce`, `abused`, `mobileverified`, `emailsentcount`, `emailverified`, `isNDNC`, `isLDBUser`) values(?,'1','0','0','0','0','0','0',0,'0','NO',?)";
                $dbHandle->query($sqlflag, array($userid, $isLDBUser));
            }
            elseif ($result == 'na')
            {
                $sqlflag = "insert into tuserflag(`userId`, `pendingverification`, `hardbounce`, `unsubscribe`, `ownershipchallenged`, `softbounce`, `abused`, `mobileverified`, `emailsentcount`, `emailverified`, `isNDNC`, `isLDBUser`) values(?,'1','0','0','0','0','0','0',0,'0','NA',?)";
                $dbHandle->query($sqlflag, array($userid, $isLDBUser));
            }
            // ---------------- NDNC CHECK END ----------------//
			$response1 = "select email,displayname,randomkey from tuser where userId = ?";
			$query = $dbHandle->query($response1, array($userid));
			$row = $query->row();
			$verifylink = "https://".SHIKSHACLIENTIP."/shiksha/userresponse/".$row->randomkey."/verify";
			$unsubscribelink = "https://".SHIKSHACLIENTIP."/shiksha/userresponse/".$row->randomkey."/unsubscribe";
			//$this->sendVerificationMail($row->email,$row->displayname,$verifylink,$unsubscribelink,$userid);

			if($categories != '')
			{
				$sql3 = "insert into tuserInterest values('',?,'category',?)";
				error_log("FGH ".$sql3);
				$dbHandle->query($sql3, array($userid, $categories));
			}
            if($subCategories != '')
			{
				$sql5 = "insert into tuserInterest values('',?,'subCategory',?)";
                error_log("FGH ".$sql5);
				$dbHandle->query($sql5, array($userid, $subCategories));
			}
			$this->load->library('MailerClient');
			$objmailerClient = new MailerClient;
			if($newsletteremail==1)
			{
			if($subCategories!='')
			{
        		//$resultQmailer = $objmailerClient->sendRegistrationQuestionMailer($appID,$userId,$row->displayname, $subCategories,REGISTRATION_QUESTION_POOL_DURATION,$row->email);
			}
			else
			{

        		//$resultQmailer = $objmailerClient->sendRegistrationQuestionMailer($appID,$userId,$row->displayname, $categories,REGISTRATION_QUESTION_POOL_DURATION,$row->email);
			}
			}


            if($preferredCity != '')
            {
                $csvArr = split(",",$preferredCity);
                for($ijk = 0;$ijk < count($csvArr);$ijk++) {
                    if(trim($csvArr[$ijk]) != ""){
                        preg_match('/\d+/',$csvArr[$ijk],$matches);
                        error_log("GHJ ".$csvArr[$ijk]."    ".print_r($matches,true));
                        if(count($matches) >0) {

                            $sql9 = "insert into tuserInterest values('',?,'city',?)";
                            error_log("GHJ ".$sql9);
                            $dbHandle->query($sql9, array($userid, $csvArr[$ijk]));

                        }
                    }
                }
            }

			if($countries != '')
			{
				$sql4 = "insert into tuserInterest values('',?,'countries',?)";
				$dbHandle->query($sql4, array($userid, $countries));
			}
			if($cityofhigheredu != '')
			{
				$sql4 = "insert into tuserInterest values('',?,'city',?)";
				$dbHandle->query($sql4, array($userid, $cityofhigheredu));
			}
			$sql1 = "insert into userPointSystem(userId,userPointValue) values(?,100)";
			$sql2 = "INSERT INTO shiksha_mypage values('',?,'myShikshaDiscussion',1,'1',2),('',?,'myShikshaEvents',2,'1',2),('',?,'myShikshaNetwork',3,'1',10),('',?,'myShikshaCollgenetwork',4,'1',3),('',?,'blogs',5,'1',2),('',?,'polls',6,'0',2),('',?,'myShikshaListing',7,'1',3)";
			$dbHandle->query($sql1, array($userid));
			$dbHandle->query($sql2, array($userid, $userid, $userid, $userid, $userid, $userid, $userid));

			$this->load->library('cacheLib');
			$cacheLib = new cacheLib();
			$cacheLib->clearCache('Network');

			$response = array(
					array("status"=> array($userid)),
					'struct');
			error_log_shiksha($response.'ReSPONSE');
			$this->addDataToRegistrationTracking($userid, $country, $city);
			return $this->xmlrpc->send_response($response);

		}
		else
		{					$response = array(
				array("status"=> array('0')),
				'struct');
		error_log_shiksha($response['status'].'RegisterStatus');
		return $this->xmlrpc->send_response($response);
		}
	}


	function addDataToRegistrationTracking($userid, $country, $city, $tracking_keyid){
		$data = array();

		if(empty($userid)){	
			return;
		}

		if(!empty($country)){
			$data['country'] = $country;
		}else{
			$data['country'] = NULL;
		}

		if(!empty($city)){
			$data['city'] = $city;
		}else{
			$data['city'] = NULL;
		}
		$data['prefCountry1'] = NULL;
		$data['prefCountry2'] = NULL;
		$data['prefCountry3'] = NULL;

		if(!empty($tracking_keyid)){
			$data['trackingKeyId'] = $tracking_keyid;
		}else{
			$data['trackingKeyId'] = $this->input->post('tracking_keyid');
			
		}

		if($data['trackingKeyId'] == 599){
			$data['userType'] = 'APP';
		}else{
			$data['userType'] = 'national';
		}

		$data['userId'] = $userid;
        $data['visitorSessionId'] = getVisitorSessionId();
        $data['submitDate'] = date("Y-m-d");
        $data['SubmitTime'] = date("Y-m-d H:i:s");
        $data['referer'] = $_SERVER['HTTP_REFERER'];
        if(empty($data['referer'])){
        	$data['referer'] = NULL;
        }

        $data['desiredCourse'] = NULL;
        $data['subCatId'] = NULL;
        $data['categoryId'] = NULL;
        $data['blogId'] = NULL;
        $pageReferer = $this->input->post('pageReferer');
        if(!empty($pageReferer)){
            $data['pageReferer'] = $pageReferer;
        }else{
        	$data['pageReferer'] = NULL;
        }
        $data['source'] = 'free';
        $data['isNewReg'] = 'yes';

        $usermodel = $this->load->model('user/usermodel');
        $usermodel->insertIntoRegistrationTracking($data);
        // $usermodel->insertIntoMISTracking($data);
	}

	/**
	 * Function to get the User details
	 *
	 * @param object $request
	 */
	function getUserDetails($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];
		$module=$parameters['2'];

		$dbHandle = $this->_loadDatabaseHandle();

		if($module == 'AnA'){
			$queryCmd = "select tuser.userId,usergroup,email,displayname,mobile,city,country,viamobile,viaemail,newsletteremail,publishQuestionOnFB,publishAnswerOnFB,publishDiscussionOnFB,publishAnnouncementOnFB,publishInstituteFollowing,publishInstituteUpdates,publishRequestEBrochure,publishBestAnswerAndLevelActivity,publishArticleFollowing,upv.userpointvaluebymodule as points,lastlogintime,profession,avtarimageurl,ifnull((select ups.levelName from userpointsystembymodule ups where ups.modulename = 'AnA' and ups.userId = ? LIMIT 1),'Beginner-Level 1') as level from tuser LEFT JOIN userpointsystembymodule upv ON (tuser.userId = upv.userId and upv.modulename='AnA') where tuser.userId =?";


                }else{
			$queryCmd = 'select tuser.userId,usergroup,email,displayname,mobile,city,country,viamobile,viaemail,newsletteremail,publishQuestionOnFB,publishAnswerOnFB,publishDiscussionOnFB,publishAnnouncementOnFB,publishInstituteFollowing,publishInstituteUpdates,publishRequestEBrochure,publishBestAnswerAndLevelActivity,publishArticleFollowing,upv.userPointValue as points,lastlogintime,profession,avtarimageurl,ifnull((select ups.levelName from userpointsystembymodule ups where ups.modulename = "AnA" and ups.userId = ? LIMIT 1),"Beginner-Level 1") as level from tuser,userPointSystem upv where tuser.userId =? AND tuser.userId = upv.userId';
		}

		error_log_shiksha($queryCmd);
		error_log_shiksha($userId);
		log_message('debug','getUser Details query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd, array($userId,$userId));
		$row = $query->row();

		//  unsubscribe table change from "unsubscribe" to "user_unsubscribe_mapping", also this field is not in used.
		/*$sql = "select unsubscribe from tuserflag where userid = ?";
		$cmd = $dbHandle->query($sql, array($userId))->row();
		$unsubscribeFlag = $cmd->unsubscribe;*/
		
		$cityName = $row->city;

		if(is_numeric($row->city))
		{
			$queryCmd = "select city_name as city from countryCityTable where city_id = ?";
			error_log_shiksha($queryCmd);
			$query = $dbHandle->query($queryCmd,array($row->city));
			$row1 = $query->row();
			$cityName = $row1->city;

		}
		$msgArray = array();
		array_push($msgArray,array(
					array(
                                                'userid'=>$row->userId,
						'usergroup'=>$row->usergroup,
						'email'=>$row->email,
						'displayname'=>$row->displayname,
						'mobile'=>$row->mobile,
						'city'=>$cityName,
						'country'=>$row->country,
						'detail'=>$row->profession,
						'avtarimageurl' =>$row->avtarimageurl,
						'userStatus'=>($this->getUserStatus($row->lastlogintime)),
						'lastlogintime'=>$row->lastlogintime,
						'userStatusMsg' => ($this->getUserStatusMsg($row->lastlogintime)),
						'viamobile' => $row->viamobile,
						'viaemail' => $row->viaemail,
						'publishInstituteFollowing' =>$row->publishInstituteFollowing,
						'publishInstituteUpdates' =>$row->publishInstituteUpdates,
						'publishRequestEBrochure' =>$row->publishRequestEBrochure,
						'publishQuestionOnFB' =>$row->publishQuestionOnFB,
                        'publishAnswerOnFB' =>$row->publishAnswerOnFB,
                        'publishDiscussionOnFB' =>$row->publishDiscussionOnFB,
                        'publishAnnouncementOnFB' =>$row->publishAnnouncementOnFB,
                       // 'publishAnaActivity' =>$row->publishAnaActivity,
						'publishBestAnswerAndLevelActivity' =>$row->publishBestAnswerAndLevelActivity,
						'publishArticleFollowing' =>$row->publishArticleFollowing,
						'newsletteremail' => $row->newsletteremail,
						'userLevel' => $row->level,
						/*'unsubscribeFlag' => $unsubscribeFlag,*/
						'userPoints' => $row->points
					     ),'struct')
			  );
		$response = array($msgArray,'struct');
		error_log_shiksha(print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}
	
	
	/**
	 * Function to get the Status of User(Available,Inactive etc.)
	 * @param string $time
	 */
	function getUserStatusMsg($time)
	{
		$timeDiff = time()-strtotime($time);
		/* If time difference is greater than 0 and less than 1 hour, we assume user is active,
		 * greater then one hour he might be inactive.
		 * Else offline */
		if($timeDiff>0 && $timeDiff <=60*60 ){
			return "Available";
		}elseif($timeDiff<=0){
			return "Not Available";
		}elseif($timeDiff>60*60){
			return "Inactive User";
		}
		return "Not Available";

	}

	
	/**
	 * Function to populate the colleges
	 *
	 * @param object $request
	 */
	function populatecolleges($request)
	{
		error_log('server');
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$strNames = $parameters['1'];
		$institute = $parameters['2'];

		$dbHandle = $this->_loadDatabaseHandle();

		if($institute == 1)
		{
			$queryCmd = "select i.institute_id as collegeId,i.institute_name as collegeName from institute i INNER JOIN institute_location_table ilt ON(i.institute_id = ilt.institute_id) INNER JOIN countryCityTable cct ON(cct.city_id = ilt.city_id) where i.status = 'live' and ilt.status = 'live' and cct.city_id = ? order by trim(i.institute_name)";
		}
		else
		{
			$queryCmd = "select i.schoolId as collegeId,i.school as collegeName from NW_SCHOOLLIST i INNER JOIN countryCityTable ilt ON(i.cityId = ilt.city_Id) where ilt.city_Id = ? order by trim(i.school)";
		}
		error_log($queryCmd .'ASHISH');
		$query = $dbHandle->query($queryCmd, array($strNames));
		$msgArray = array();
		if($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row){
				array_push($msgArray,array($row,'struct'));
			}
			$response = array($msgArray,'struct');
		}
		else
			$response = 0;


		return $this->xmlrpc->send_response($response);

	}


	/**
	 * Fetch Education Level data
	 * @param object $request
	 */
	function EducationLevel($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$education=$parameters['1'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		if($education == "Education")
			$education = "('Graduation','PostGraduation','School')";
		else {
			if($education == "Working")
				$education = "('Working')";
			else
				$education = "('Experience')";
		}
		$queryCmd = "select EducationId,options from tEducationLevel where EducationLevel in ".$education;
		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);
		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * Get details for user
	 *
	 * @param object $request
	 */
	function getDetailsforUsers($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();	
		$userIdsList = explode(",", $userId);	
		$queryCmd = 'select * from tuser where userId in (?)';
		
		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd,array($userIdsList));
		error_log_shiksha("AFFECTEDROWS".$query->num_rows());
		$msgArray = array();
		$iCount = 0;
		foreach($query->result() as $row){
			//array_push($msgArray,array($row,'struct'));
			array_push($msgArray,array(
						array(
							'userid'=>array($row->userid,'string'),
							'email'=>array($row->email,'string'),
							'displayname'=>array($row->displayname,'string'),
							'mobile'=>array($row->mobile,'string'),
							'lastlogintime'=>array($row->lastlogintime,'string'),
							'city'=>array($row->city,'string'),
							'profession'=>array($row->profession,'string'),
							'avtarimageurl'=>array($row->avtarimageurl,'string'),
							'userStatus'=>array($this->getUserStatus($row->lastlogintime),'string'),
							'firstname'=>array($row->firstname,'string'),
							'lastname'=>array($row->lastname,'string')
						     ),'struct')
				  );//close array_push

		}
		$response = array($msgArray,'struct');
		error_log_shiksha(print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * xxx move to common library. Give user offline/online Images
	 * @param string $time
	 */

	function getUserStatus($time){
		$timeDiff = time()-strtotime($time);
		/* If time difference is greater than 0 and less than 1 hour, we assume user is active,
		 * greater then one hour he might be inactive.
		 * Else offline
		 */
		if($timeDiff>0 && $timeDiff <=60*60 ){
			return "/public/images/online_user.gif";
		}elseif($timeDiff<=0){
			return "/public/images/offline_user.gif";
		}elseif($timeDiff>60*60){
			return "/public/images/inactive_user.gif";
		}
		return "/public/images/offline_user.gif";
	}

	
	/**
	 * Function to get the details for display name
	 *
	 * @param object $request
	 */
	function getDetailsforDisplayname($request){

		$parameters = $request->output_parameters();error_log("AFFECTEDROWS:".print_r($parameters,true));
		$appID=$parameters['0'];
		$displayname=trim($parameters['1'],'\'');
                $new_displayname=  '';
                for($i=0;$i<strlen($displayname);$i++)
                {
                    if((int)ord($displayname[$i]) == 92)
                    {
                        $new_displayname .= "\\\\";
                    }
                    else
                    {
                        if((int)ord($displayname[$i]) == 34){
                             $new_displayname .= '&quot;';
                        }else{
                            $new_displayname .= $displayname[$i];
                        }
                    }
                    if((int)ord($displayname[$i]) == 34)
                    {
                        $left ="'";
                        $right  = "'";
                    }
                    else
                    {
                        $left ='"';
                        $right  = '"';
                    }
                }

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		
		if(!(strlen("$left$new_displayname$right"))) {
			return array();
		}
			$queryCmd = "select userid,email,displayname,mobile,city,profession,avtarimageurl from tuser where displayname = ?";


		
		$query = $dbHandle->query($queryCmd, array($new_displayname));
		error_log_shiksha("AFFECTEDROWS".$query->num_rows());
		$msgArray = array();
		$iCount = 0;
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		error_log_shiksha(print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * Function to update the user details
	 * 
	 * @param object $request
	 */
	function updateUser($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];
		$columnname = $parameters['2'];
		$columnvalue = $parameters['3'];
		$displayname = $parameters['4'];
		$product = $parameters['5'];
		error_log("User_Profile_ in update user function  : ".print_r((memory_get_peak_usage()/(1024*1024)),true));
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');
		if ($columnname == "newsletteremail" )
		{
			$unsubscribeFlag = $columnvalue == 1 ? '0' : '1';
			$queryCmd = 'update tuser a, tuserflag b set a.newsletteremail = '. $dbHandle->escape($columnvalue) .' , b.unsubscribe = ' . $dbHandle->escape($unsubscribeFlag) . ', lastmodifiedOn = now() where a.userId =' . $dbHandle->escape($userId) . ' and b.userId =' . $dbHandle->escape($userId);
			
		} else if ($columnname == "viaemail") {
			$unsubscribeFlag = $columnvalue == 1 ? '0' : '1';
			mail('teamldb@shiksha.com', 'function -updateUser(File : Register_Server) after Unsubscribe removed from tuserflag', print_r($_SERVER,true));
			$queryCmd = 'update  tuser a,tuserflag b set  b.unsubscribe = ' . $dbHandle->escape($unsubscribeFlag). ', lastmodifiedOn = now() where a.userId =' . $dbHandle->escape($userId) . ' and b.userId =' . $dbHandle->escape($userId);
			error_log("##########sql".$queryCmd);

		}
		else
		{
			$queryCmd = 'update tuser set ' . $columnname . ' = '. $dbHandle->escape($columnvalue) .', lastmodifiedOn = now() where userId ='.$dbHandle->escape($userId);
		}
		error_log("POI ".$queryCmd);
		log_message('debug','getUser Details query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd);
		$response = array($dbHandle->affected_rows());
		error_log("User_Profile_ in update user function before calling userLib  : ".print_r((memory_get_peak_usage()/(1024*1024)),true));
		$this->load->library('user/UserLib');
                $userLib = new UserLib;
                $userLib->updateUserData($userId);

		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * Function to update the User Information(Decided at run time which column will be updated)
	 *
	 * @param object $request
	 */
    function updateUserGen($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $updatearray = $parameters['1'];
        $columnname = $parameters['2'];
        $columnvalue = $parameters['3'];
        $userintarray = $parameters['4'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        //check for userId/email to as userId and email are unique and to avoid multiple updation of user profile this check is there
        error_log(strcasecmp($columnname,"userId").'STRCMP');
	if(!strcasecmp($columnname,"userId") || !strcasecmp($columnname,"email"))
	{
		$setstr = "";
		foreach($updatearray as $key=>$value)
		{
			$setstr .= $key .' = "'. $value.'",';
		}
		$setstr = substr($setstr,0,strlen($strstr)-1);
		$sql = "update IGNORE tuser set $setstr,lastmodifiedOn = now() where $columnname = ?";
		error_log($sql.'UPDATESQL');
		$query = $dbHandle->query($sql,array($columnvalue));
		if($dbHandle->affected_rows() == 1)
			$response = 1;
		else
			$response = 0;
		error_log($response.'RESPONSE');
		error_log(empty($userintarray).'EmPTYARRAY');
		if(!empty($userintarray))
		{
			foreach($userintarray as $key=>$value)
			{
//				$sql = "update tuserInterest a inner join tuser b on a.userId = b.userId set keyValue = ? where keyofInterest = ? and b.$columnname = ?";
				$sql = "insert into tuserInterest (select '',userId,?,? from tuser where $columnname = ?)";
				error_log($sql.'UPDATESQL');
				$query = $dbHandle->query($sql,array($key, $value, $columnvalue));
				if($dbHandle->affected_rows() >= 1)
					$response = 1;
				else
					$response = 0;
			}
		}
	}
        else
        $response = 0;
        return $this->xmlrpc->send_response($response);
    }
	
	/**
	 * Function to get the User Id based on certain check(Decided at run time- Generic)
	 *
	 * @param object $request
	 */
	function checkAvailability($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$name = strip_tags($parameters['1']);
		$type = strip_tags($parameters['2']);
		error_log_shiksha('server');
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$sql = "select userid from tuser where $type = ?";

		log_message('debug','getUser Details query cmd is ' . $sql);
		error_log_shiksha($sql.'QUERY');
		$query = $dbHandle->query($sql, array(addslashes($name)));
		$row = $query->row();
		if($row != null)
			$response = 1;
		else
			$response = 0;
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	* Get info for user based on certain check(Decided at run time- Generic)
	* @param object $request
	*/
	function getinfoifexists($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$name = $parameters['1'];
		$type = $parameters['2'];

		error_log_shiksha('server');
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');
		
		$sql = "select userid,email,displayname,mobile,avtarimageurl,usergroup,firstname,lastname,age,quicksignupFlag,password from tuser where $type = ?";
		log_message('debug','getUser Details query cmd is ' . $sql);
		error_log($sql.'QUERY');
		$query = $dbHandle->query($sql, array($name));
		$row = $query->row();
        if($row != null)
        {
            $msgArray = array();
            array_push($msgArray,array(
                        array(
                            'userid'=>array($row->userid),
                            'displayname'=>array($row->displayname),
                            'email'=>array($row->email),
                            'mobile'=>array($row->mobile),
                            'age'=>array($row->age),
                            'usergroup'=>array($row->usergroup),
                            'avtarurl'=> array($row->avtarimageurl),
                            'firstname'=>array($row->firstname),
                            'lastname'=>array($row->lastname),
                            'quicksignuser'=>array($row->quicksignupFlag),
                            'mdpassword'=>array($row->password)
                            ),'struct')
                    );

            $response = array($msgArray,'struct');
        }
		else
		{
			$response = 0;
        }
		return $this->xmlrpc->send_response($response);
	}
	
	
	/**
	 * Function to reset the Password
	 *
	 * @param object $request
	 */
	function sResetPassword($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$uname = $parameters['1'];
		$name = $parameters['2'];
		$password = $parameters['3'];
		$textpassword = $parameters['4'];
		
		if(!is_string($name)) {
			    $response = 0;
				return $this->xmlrpc->send_response($response);
		}
		
		$dbHandle = $this->_loadDatabaseHandle('write');
		
		$sql = "Select randomkey,email,userId from tuser where email = ?";
		$query = $dbHandle->query($sql, array($name));
		$disname = $query->row()->randomkey;
		$userId = $query->row()->userId;
		error_log_shiksha($sql.'SQL');
		if($query->num_rows() == 0)
			$response = -1 ;
		else
		{

			$sql = 'select emailverified,pendingverification,ownershipchallenged,abused,hardbounce,softbounce from tuserflag where userId = ?';
			error_log($sql.'ERRORSQL');
			$query = $dbHandle->query($sql, array($userId));
			$row = $query->row();
			$msgArray = array();
			array_push($msgArray,array(
						array(
							'pendingverification' =>array($row->pendingverification),
							'hardbounce' =>array($row->hardbounce),
							'ownershipchallenged' =>array($row->ownershipchallenged),
							'softbounce' =>array($row->softbounce),
							'abused' =>array($row->abused),
							'emailverified' =>array($row->emailverified),
							'userId' => array($userId)
						     ),'struct')
				  );
			$response = array($msgArray,'struct');
			if($row->abused != 1 && $row->ownershipchallenged != 1)
			{
				error_log_shiksha($disname);
				if(md5($disname) == $uname)
				{
					$sql = "Update tuser set ePassword = ? where email = ?";
					error_log($sql);
					$query = $dbHandle->query($sql, array($password, $name));
					error_log($dbHandle->affected_rows());
					//if($dbHandle->affected_rows() == 1)
					//{
						// $subject = "You have successfully changed your password";
						// $data['usernameemail'] = $name;
						// $this->load->library('Alerts_client');
						// $alertClient = new Alerts_client();
						// $data['content'] = "You have successfully changed your password. Your new password is ".$textpassword;
						// $content = $this->load->view('user/PasswordChangeMail',$data,true);
						// $mailresponse=$alertClient->externalQueueAdd("12",ADMIN_EMAIL,$name,$subject,$content,$contentType="html");

					//}
					//else
					//	$response = 0;
						
						$usermodel = $this->load->model('user/usermodel');
						$usermodel->trackPasswordChange($userId);

				}
				else
					$response = 0;
			}

		}
		error_log(print_r($response,true).'PRINTRESPONSE');
		return $this->xmlrpc->send_response($response);

	}
	
	/**
	 * Function to the User ID based on email
	 *
	 * @param object $request
	 */
	function sgetIdforEmail($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$uname = $parameters['1'];
		$writeHandle = $parameters['2'];

		if($writeHandle == 'yes'){
			$dbHandle = $this->_loadDatabaseHandle('write');
		}else{
			$dbHandle = $this->_loadDatabaseHandle();
		}
		
		$sql = "Select a.randomkey as randomkey,a.userId as userId,b.ownershipchallenged,b.abused, a.firstname from tuser a inner join tuserflag b on a.userId = b.userId and b.hardbounce = '0' where email = ?";
		error_log($sql);
		$query = $dbHandle->query($sql, array($uname));
		$response = '';

		$row = $query->row();
		error_log($query->num_rows().'NUMROWS');
		if($query->num_rows() == 1)
		{
			$msgArray = array();
			$id = md5($row->randomkey);
			error_log($id);
			array_push($msgArray,array(
						array(
							'id'=>$id,
							'userId'=>$row->userId,
							'displayname'=>$row->displayname,
							'email'=>$row->email,
							'ownershipchallenged'=>$row->ownershipchallenged,
							'abused'=>$row->abused,
							'firstname'=>$row->firstname
						     ),'struct')
				  );

			$response = array($msgArray,'struct');
			error_log($response.'RESPONSE');
		}
		else
		$response = 0;
		return $this->xmlrpc->send_response($response);

	}
	
	/**
	 * Function to change the Password
	 * @param object $request
	 */
	function schangePassword($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId = $parameters['1'];
		$oldpassword = $parameters['2'];
		$newpassword = $parameters['3'];
		$textpassword = $parameters['4'];

		$dbHandle = $this->_loadDatabaseHandle('write');
		
		$sql = "Select userId as userId from tuser where userId = ? and ePassword = ?";
		error_log_shiksha($sql);
		$query = $dbHandle->query($sql, array($userId, $oldpassword));
		
		if($query->num_rows() == 1)
		{
			$sql = "Update tuser set ePassword = ? where userId = ?";
			$query = $dbHandle->query($sql, array($newpassword, $userId));
			if($dbHandle->affected_rows() == 1){
				$response = 1;
				$usermodel = $this->load->model('user/usermodel');
				$usermodel->trackPasswordChange($userId);
			}
				
			else
				$response = 0;
		}
		else
			$response = -1;
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * Function to generate Response for sending to user
	 * @param object $request
	 */
	function senduserResponse($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$key = $parameters['1'];
		$verifyflag = $parameters['2'];
		$cookie = $parameters['3'];

		$dbHandle = $this->_loadDatabaseHandle('write');
		
		$response = "different|";
		$userId = 0;
		if($cookie != '')
		{
			$cookievalue = explode("|",$cookie);
			$sql = "Select userId from tuser where email = ? and password = ?";
            error_log($sql);
			$query = $dbHandle->query($sql, array($cookievalue[0], $cookievalue[1]));
			if($query->num_rows() == 1)
			{
			$row = $query->row();
			$userId = $row->userId;
			}
		}
		$sql = "Select a.userId as userId,ownershipchallenged,abused from tuser a inner join tuserflag b on a.userId = b.userId where randomkey = ?";
		error_log($sql);
		$query = $dbHandle->query($sql, array($key));

		$row = $query->row();
		$id = $row->userId;
		$num = $query->num_rows();
		if($num == 1)
		{
				if($id == $userId)
				$response = "same|";
			else
				$response = "different|";
			if($row->ownershipchallenged == '1' || $row->abused == '1')
				$response = "deleted|";
			if($verifyflag == "verify")
			$columnname = "emailverified";
			if($verifyflag == "unsubscribe")
			$columnname = "ownershipchallenged";
			$sql = "update tuserflag set $columnname  = '1',pendingverification = '0' where userId = ?";
			error_log($sql);
			$query = $dbHandle->query($sql, array($id));
			if($dbHandle->affected_rows() == 1)
			$response .= 1;
			else
			$response .= "already";

		}
		if($num == 0 || ($verifyflag != "verify" && $verifyflag != "unsubscribe"))
		{
				$response .= "invalid";
		}
		error_log($response.'USERRESPONSE');
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * Function to change the user email
	 * @param object $request
	 */
	function schangeEmail($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId = $parameters['1'];
		$email = $parameters['2'];

		$dbHandle = $this->_loadDatabaseHandle('write');
		$sql = "Select userId as userId from tuser where email = ?";
		error_log_shiksha($sql);
		$query = $dbHandle->query($sql, array($email));

		$response = '';
		$row = $query->row();
		$id = $row->userId;
		if($query->num_rows() == 1)
		{
			if($id == $userId)

				$response = "same";

			else
				$response = "exists";
		}
		if($query->num_rows() == 0)
		{
			while($Flag != 1)
			{
				$possible = '23456789bcdfghjkmnpqrstvwxyz';
					$i = 0;
					for ($i=0;$i < 6 ; $i++) {
						$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
					}
				$sql1 = "select userId from tuser where randomkey = ?";
				error_log_shiksha($sql1);
				$query = $dbHandle->query($sql1, array($code));
				if($query->num_rows() == 0)
					$Flag = 1;
			}
			$sql = "update tuser set secondaryemail = email,email = ?,randomkey = ? where userId = ?";
			$query = $dbHandle->query($sql, array($email, $code, $userId));
			if($dbHandle->affected_rows() == 1)
			{
				$sql1 = "update tuserflag set pendingverification = '1',hardbounce = '0',softbounce = '0' where userId = ?";
				$query = $dbHandle->query($sql1, array($userId));
				$response = 1;
				$response1 = "select email,displayname,randomkey from tuser where userId = ?";
		                $query = $dbHandle->query($response1, array($userId));
				$row = $query->row();
				$verifylink = "https://".SHIKSHACLIENTIP."/shiksha/userresponse/".$row->randomkey."/verify";
				$unsubscribelink = "https://".SHIKSHACLIENTIP."/shiksha/userresponse/".$row->randomkey."/unsubscribe";
				//$this->sendVerificationMail($row->email,$row->displayname,$verifylink,$unsubscribelink,$userId);
			}
			else
				$response = 0;
		}
		error_log($response.'USERRESPONSE');
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * Function to generate verify / unsubscribe link
	 * @param object $request
	 */
	function sendVerification($request)
	{

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId = $parameters['1'];

		$dbHandle = $this->_loadDatabaseHandle();

		$response1 = "select email,displayname,randomkey from tuser where userId = ?";
		error_log($response1);
		$query = $dbHandle->query($response1, array($userId));
		$row = $query->row();
		$verifylink = "https://".SHIKSHACLIENTIP."/shiksha/userresponse/".$row->randomkey."/verify";
		$unsubscribelink = "https://".SHIKSHACLIENTIP."/shiksha/userresponse/".$row->randomkey."/unsubscribe";

		//$this->sendVerificationMail($row->email,$row->displayname,$verifylink,$unsubscribelink,$userId);
	}

	/**
	 * Function to send the verification email
	 *
	 * @param string $email
	 * @param string $displayname
	 * @param string $verifyLink
	 * @param string $unsubscribelink
	 * @param integer $userId
	 */
	function sendVerificationMail($email,$displayname,$verifylink,$unsubscribelink,$userId)
	{
		$this->load->library('Alerts_client');
		$alertClient = new Alerts_client();
		$subject = "Verify your shiksha account";
		$data['usernameemail'] = $email;
		$data['displayname'] = $displayname;
		$data['verifylink'] = $verifylink;
		$data['unsubscribelink'] = $unsubscribelink;
		error_log($verifylink.'VERIFYLINK');
 		$content = $this->load->view('user/VerificationMail',$data,true);
        $response=$alertClient->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,$contentType="html");
		$dbHandle = $this->_loadDatabaseHandle();
	}
	
	/**
	 * Function to send the verification SMS
	 *
	 * @param string $mobile
	 * @param string $displayname
	 * @param integer $userId
	 */
	function sendVerificationSms($mobile,$displayname,$userId)
	{
		if($mobile!=''){
            $message = "Your number has been successfully verified";

            $this->load->model('smsModel');
	    

	    $Isregistration = 'Yes';
            $tempArray1 = $this->smsModel->addSmsQueueRecord('',$mobile,$message,$userId,'0000-00-00 00:00:00',"",$Isregistration);
	    }
	}
	
	/** 
	 * Function to send Reminder mail
	 *
	 * @param object $request
	 */
	function sendReminder($request)
	{
		set_time_limit(0);
		$dbHandle = $this->_loadDatabaseHandle('write');

		$chunkcount = 10000;
		$start = 0;
		$end = $chunkcount;

		global $domainsUsingAmazonMailService;
		global $emailidsUsingAmazonMailService;

		$sql = "select count(a.userId) as count from tuser a inner join tuserflag b on a.userId = b.userId where b.emailsentcount < 3  and b.emailverified <> '1' and b.pendingverification = '1' and a.usercreationDate >= '2009-04-01';";
		error_log($sql);
		$query = $dbHandle->query($sql);
		$row = $query->row();
		$startpage = 0;
       	$endpage = floor($row->count/$chunkcount) + (($row->count%$chunkcount) != 0 ? 1 : 0);
        $file = "/var/www/html/shiksha/mediadata/emailverification.txt";
		while($startpage < $endpage)
		{
            error_log($startpage.'STARTPAGE');
            error_log($start.'START');
            error_log(memory_get_usage().'MEMORYUSAGE');
            $insertBat = '';
			$sql = "select a.randomkey,a.displayname,a.email,a.userId from tuser a inner join tuserflag b on a.userId = b.userId where b.emailsentcount < 3  and b.emailverified <> '1' and b.pendingverification = '1' and a.usercreationDate >= '2009-04-01' limit $start, $end";
			error_log($sql);
			$query = $dbHandle->query($sql);
			$row = $query->row();
            foreach ($query->result_array() as $row){

            	// Filter mails for Amazon SES
		        $mailerServiceType = 'shiksha';

		        $toDomainName = explode("@", $row['email']);
		        if( (in_array($toDomainName[1], $domainsUsingAmazonMailService)) || (in_array($row['email'], $emailidsUsingAmazonMailService)) ) {
		            $mailerServiceType = 'amazon';
		        }

                $subject = "Reminder - Verify your shiksha account";
                $data['usernameemail'] = $row['email'];
                $data['displayname'] = $row['displayname'];
                $verifylink = "https://".SHIKSHACLIENTIP."/shiksha/userresponse/".$row['randomkey']."/verify";
                $unsubscribelink = "https://".SHIKSHACLIENTIP."/shiksha/userresponse/".$row['randomkey']."/unsubscribe";
                $data['verifylink'] = $verifylink;
                $data['unsubscribelink'] = $unsubscribelink;
                $content = $this->load->view('user/VerificationMail',$data,true);
                $content = xmlrpcHtmlDeSanitize($content,array());
                $insertBat .= "insert into tMailQueue(fromEmail,toEmail,subject,content,contentType,sendTime,mailerServiceType) values(".$dbHandle->escape(ADMIN_EMAIL).",".$dbHandle->escape($row['email']).",".$dbHandle->escape($subject).",".$dbHandle->escape($content).",'html',now(),".$dbHandle->escape($mailerServiceType).");";
                $insertBat .= "update tuserflag set emailsentcount = emailsentcount + 1 where userId = ".$dbHandle->escape($row['userId']) .";";

                unset($content);
                unset($subject);
                unset($data);
                unset($row);
            }
            unset($dbHandle);
            $dbHandle = $this->_loadDatabaseHandle('write');
            $fh = fopen($file,'w');
            fwrite($fh,$insertBat);
            $hostname = $dbHandle->hostname;
            exec("mysql -u shiksha -pshiKm7Iv80l -h $hostname shiksha < ".$file);
            exec("rm " . $file);
            fclose($fh);

            $start += $chunkcount;
			$startpage += 1;
            unset($insertBat);
            unset($insertBat1);
		}
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * Function requestCall
	 * @param object $request
	 */
	function requestCall($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$requestArr = $parameters['1'];
		$dbHandle = $this->_loadDatabaseHandle('write');

		$queryCmd = $dbHandle->insert_string('requestCall',$requestArr);
		$query = $dbHandle->query($queryCmd);

		$recent_id = $dbHandle->insert_id();
		$response = array ($recent_id,'int');
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * Function to check if the user is quick signup user
	 * @param object $request
	 */
	function quicksignupuser($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId = $parameters['1'];

		$dbHandle = $this->_loadDatabaseHandle('write');
		
		$sql = "Select userid from tuser where userId = ? and (quicksignupFlag = 'requestinfouser' || quicksignupFlag = 'quicksignupuser')";
		error_log_shiksha($sql);
		$query = $dbHandle->query($sql, array($userId));

		if($query->num_rows() == 1)
			$response = 1;
		else
			$response = 0;
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * Function to update the User Points
	 *
	 * @param object $request
	 */
	function updateuserPointSystem($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$points = $parameters['1'];
		$userId = $parameters['2'];

		$dbHandle = $this->_loadDatabaseHandle('write');

		$sql = "update userPointSystem set userPointValue = userPointValue + ".$points ." where userid = ?";
		error_log_shiksha($sql);
		$query = $dbHandle->query($sql, array($userId));

		$response = $dbHandle->affected_rows();
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * Function to get the User Point for All module
	 * @param object $request
	 */
	function getUserPointLevel($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];

		$dbHandle = $this->_loadDatabaseHandle('write');

		$queryCmd = "select * from userPointLevelByModule where module='All' order by minlimit asc";
		$result = $dbHandle->query($queryCmd);
		$msgArray = array();
		foreach ($result->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}

		$response = array(
							array('Results'=>array($msgArray,'struct')
						),'struct');
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * Fucntion for storing info user related tables
	 */
	function userTablesMap() {
		$userTables = array(
				/* START Of tUser */
				'userId'=>array('column' => 'userid','table'=>'tuser'),
				'userid'=>array('column' => 'userid','table'=>'tuser'),
				'displayName'=>array('column' => 'displayname','table'=>'tuser'),
				'displayname'=>array('column' => 'displayname','table'=>'tuser'),
				'email'=>array('column' => 'email','table'=>'tuser'),
				'mobile'=>array('column' => 'mobile','table'=>'tuser'),
				'ePassword'=>array('column' => 'ePassword','table'=>'tuser'),
				'residenceCountry'=>array('column' => 'country','table'=>'tuser'),
				'firstName'=>array('column' => 'firstname','table'=>'tuser'),
				'lastName'=>array('column' => 'lastname','table'=>'tuser'),
				'residenceCity'=>array('column' => 'city','table'=>'tuser'),
				'contactViaMobile'=>array('column' => 'viamobile','table'=>'tuser'),
				'viamobile'=>array('column' => 'viamobile','table'=>'tuser'),
				'contactViaEmail'=>array('column' => 'viaemail','table'=>'tuser'),
				'viaemail'=>array('column' => 'viaemail','table'=>'tuser'),
				'contactViaNewsLetter'=>array('column' => 'newsletteremail','table'=>'tuser'),
				'newsletteremail'=>array('column' => 'newsletteremail','table'=>'tuser'),
				'userAvatar'=>array('column' => 'avtarimageurl','table'=>'tuser'),
				'userStatus'=>array('column' => 'userstatus','table'=>'tuser'),
				'randomKey'=>array('column' => 'randomkey','table'=>'tuser'),
				'userGroup'=>array('column' => 'usergroup','table'=>'tuser'),
				'signUpFlag'=>array('column' => 'quicksignupFlag','table'=>'tuser'),
				'age'=>array('column' => 'age','table'=>'tuser'),
				'gender'=>array('column' => 'gender','table'=>'tuser'),
				'phone'=>array('column' => 'landline','table'=>'tuser'),
				'secondaryEmail'=>array('column' => 'secondaryemail','table'=>'tuser'),
				'locality'=>array('column' => 'Locality','table'=>'tuser'),
				'userProfession'=>array('column' => 'profession','table'=>'tuser'),
				/* Deprecated Fields Of tUser */
				'educationLevel'=>array('column' => 'educationlevel','table'=>'tuser'),
				'experience'=>array('column' => 'experience','table'=>'tuser'),
				'dob'=>array('column' => 'dateofbirth','table'=>'tuser'),
				'isdCode'=>array('column' => 'isdCode','table'=>'tuser'),
				'country'=>array('column' => 'country','table'=>'tuser'),
				'institute'=>array('column' => 'institute','table'=>'tuser'),
				'graduationYear'=>array('column' => 'graduationyear','table'=>'tuser'),
				'countryOfEducation'=>array('column' => 'countryofEducation','table'=>'tuser'),
				'cityOfEducation'=>array('column' => 'cityofEducation','table'=>'tuser'),
				/* END Of tUser */

				/* START Of tUserLocationPref */
				'countryId'=>array('column' => 'CountryId','table'=>'tUserLocationPref'),
				'stateId'=>array('column' => 'StateId','table'=>'tUserLocationPref'),
				'cityId'=>array('column' => 'CityId','table'=>'tUserLocationPref'),
				'localityId'=>array('column' => 'LocalityId','table'=>'tUserLocationPref'),
				/* END Of tUserLocationPref */

				/* START Of tUserPref */
				'prefId'=>array('column' => 'PrefId','table'=>'tUserPref'),
				'degreePrefAICTE'=>array('column' => 'DegreePrefAICTE','table'=>'tUserPref'),
				'degreePrefUGC'=>array('column' => 'DegreePrefUGC','table'=>'tUserPref'),
				'degreePrefInternational'=>array('column' => 'DegreePrefInternational','table'=>'tUserPref'),
				'degreePrefAny'=>array('column' => 'DegreePrefAny','table'=>'tUserPref'),
				'modeOfEducationFullTime'=>array('column' => 'ModeOfEducationFullTime','table'=>'tUserPref'),
				'modeOfEducationPartTime'=>array('column' => 'ModeOfEducationPartTime','table'=>'tUserPref'),
				'modeOfEducationDistance'=>array('column' => 'ModeOfEducationDistance','table'=>'tUserPref'),
				'userFundsOwn'=>array('column' => 'UserFundsOwn','table'=>'tUserPref'),
				'userFundsBank'=>array('column' => 'UserFundsBank','table'=>'tUserPref'),
				'userFundsNone'=>array('column' => 'UserFundsNone','table'=>'tUserPref'),
				'timeOfStart'=>array('column' => 'TimeOfStart','table'=>'tUserPref'),
				'userDetail'=>array('column' => 'UserDetail','table'=>'tUserPref'),
				'sourceInfo'=>array('column' => 'SourceInfo','table'=>'tUserPref'),
				'desiredCourse'=>array('column' => 'DesiredCourse','table'=>'tUserPref'),
				'extraFlag'=>array('column' => 'ExtraFlag','table'=>'tUserPref'),
				'suitableCallPref'=>array('column' => 'suitableCallPref','table'=>'tUserPref'),
				'otherFundingDetails'=>array('column' => 'otherFundingDetails','table'=>'tUserPref'),
				/* END Of tUserPref */

				/* START Of tUserEducation */
				'name'=>array('column' => 'Name','table'=>'tUserEducation'),
				'instituteId'=>array('column' => 'InstituteId','table'=>'tUserEducation'),
				'level'=>array('column' => 'Level','table'=>'tUserEducation'),
				'marks'=>array('column' => 'Marks','table'=>'tUserEducation'),
				'marksType'=>array('column' => 'MarksType','table'=>'tUserEducation'),
				'courseCompletionDate'=>array('column' => 'CourseCompletionDate','table'=>'tUserEducation'),
				'courseSpecialization'=>array('column' => 'CourseSpecialization','table'=>'tUserEducation'),
				'ongoingCompletedFlag'=>array('column' => 'OngoingCompletedFlag','table'=>'tUserEducation'),
				'city'=>array('column' => 'City','table'=>'tUserEducation'),
				'country'=>array('column' => 'Country','table'=>'tUserEducation'),
				'educationStatus'=>array('column' => 'Status','table'=>'tUserEducation'), // values should be 'live' or 'deleted'
				/* END Of tUserEducation */

				/* START Of tUserSpecializationPref */
				'specializationId'=>array('column' => 'SpecializationId','table'=>'tUserSpecializationPref'),
				/* END Of tUserSpecializationPref */

				/* START Of tusersourceInfo*/
				'keyid'=>array('column' => 'keyid', 'table' => 'tusersourceInfo'),
				'coordinates'=>array('column' => 'coordinates', 'table' => 'tusersourceInfo'),
				'referer'=>array('column' => 'referer', 'table' => 'tusersourceInfo'),
				'type'=>array('column' => 'type', 'table' => 'tusersourceInfo'),
				'time'=>array('column' => 'time', 'table' => 'tusersourceInfo'),
				'resolution'=>array('column' => 'resolution', 'table' => 'tusersourceInfo'),
				'landingpage'=>array('column' => 'landingpage', 'table' => 'tusersourceInfo'),
				'keyquery'=>array('column' => 'keyquery', 'table' => 'tusersourceInfo'),
				'browser'=>array('column' => 'browser', 'table' => 'tusersourceInfo'),
				'tracking_keyid'=>array('column' => 'tracking_keyid', 'table' => 'tusersourceInfo'),
				'sessionid'=>array('column' => 'sessionid', 'table' => 'tusersourceInfo'),
				'visitorsessionid'=>array('column' => 'visitorsessionid', 'table' => 'tusersourceInfo'),
				/* END Of tusersourceInfo*/


				/* TESTPREP START */
				'testPrep_blogid'=>array('column' => 'blogid', 'table' => 'tUserPref_testprep_mapping'),
				'testPrep_status'=>array('column' => 'status', 'table' => 'tUserPref_testprep_mapping'),
				'testPrep_updateTime'=>array('column' => 'updateTime', 'table' => 'tUserPref_testprep_mapping')
				/* TESTPREP START */
			);

		$tableDataInsertionOrder = array(
				'tuser'=> array('default'=>array('lastlogintime', 'lastModifiedOn', 'usercreationDate')),
				'tUserPref' => array('fkey'=>array('UserId'), 'default'=>array('SubmitDate')),
				'tUserEducation' => array('fkey' => array('UserId'), 'default'=>array('SubmitDate')),
				'tUserSpecializationPref' => array('fkey'=>array('UserId', 'PrefId'), 'default'=>array('SubmitDate')),
				'tUserLocationPref' => array('fkey'=>array('UserId', 'PrefId'), 'default'=> array('SubmitDate')),
				'tusersourceInfo' => array('fkey'=> array('userid')),

				/* TESTPREP START */
				'tUserPref_testprep_mapping' => array('fkey'=>array('PrefId'), 'default'=> array('updateTime'))
				/* TESTPREP START */
				);

		$tableDataUpdationOrder = array(
				'tuser'=> array('default'=>array('lastlogintime', 'lastModifiedOn'), 'operation'=>'update'),
				'tUserPref' => array('fkey'=>array('UserId'), 'default'=>array('SubmitDate'), 'operation'=>'insert'),
				'tUserSpecializationPref' => array('fkey'=>array('UserId', 'PrefId'), 'default'=>array('SubmitDate'), 'operation'=>'insert'),
				'tUserLocationPref' => array('fkey'=>array('UserId', 'PrefId'), 'default'=> array('SubmitDate'), 'operation'=>'insert'),
                                'tusersourceInfo' => array('fkey'=>array('userid'), 'default'=> array('referer'), 'operation'=>'update'),
				/* TESTPREP START */
				'tUserPref_testprep_mapping' => array('fkey'=>array('PrefId'), 'default'=>array('updateTime'), 'operation'=>'insert')
				/* TESTPREP START */
				);

		$tablefKeyEssentials = array(
			'userid' => array('tuser'),
			'PrefId' => array('tUserPref'),
			'UserID' => array('tuser')
                );
		return array('userTables' => $userTables, 'tableDataInsertionOrder' => $tableDataInsertionOrder, 'tableDataUpdationOrder' => $tableDataUpdationOrder, 'fkeyTableMappings' => $tablefKeyEssentials);
	}
	/**
	 * Function to map the User Fields to DB
	 *
	 * @param array $userDetails
	 * @param array $userTables
	 * @param array $tableDataOrder
	 * @param array $fieldInfoRelatedData
	 *
	 */
	private function mapUserFieldsToDB($userDetails, $userTables, $tableDataOrder, $fieldInfoRelatedData) {
		$userTablesData = array();
		/* Creating data for the tables */
		foreach($userDetails as $columnName => $columnValue) {
			if(!isset($userTables[$columnName])) continue;
			$dbMapping = $userTables[$columnName];
			if(is_array($dbMapping)) {
				if($dbMapping['column'] == 'usergroup') {
					$userId = $this->getSpecialIdForUser($columnValue);
					if($userId != '') {
						$userTablesData[$dbMapping['table']][0]['userid'] = $userId ;
					}
				}
				if(is_array($columnValue)) {
					$countColumnValues = count($columnValue) ;
					for($columnValuesIndex =0; $columnValuesIndex < $countColumnValues ;$columnValuesIndex++) {
						$columnValueAtIndex = $columnValue[$columnValuesIndex];
						if(trim($columnValueAtIndex) != '') {
							$userTablesData[$dbMapping['table']][$columnValuesIndex][$dbMapping['column']] = $columnValueAtIndex;
						}
					}
				} else {
                    // Be Careful Using PHP's empty() API
					if(!empty($columnValue) || $columnValue == 0) {
						$userTablesData[$dbMapping['table']][0][$dbMapping['column']] = $columnValue;
					}
				}
			}
		}
		$userTablesMapping = $this->userTablesMap();
        $fkeyEssentials = $userTablesMapping['fkeyTableMappings'];
        foreach($userTablesData as $key=>$value)
        {
            $fkeyList=$tableDataOrder[$key]['fkey'];
            foreach($fkeyList as $fkey)
            {
                if(!isset($fieldInfoRelatedData[$fkey]) || empty($fieldInfoRelatedData[$fkey]))
                {
                    $essentialTableList=$fkeyEssentials[$fkey];
                    foreach($essentialTableList as $essentialTable)
                    {
                        if(!isset($userTablesData[$essentialTable]))
                        {
                            $essentialTableData = array();
                            $essentialTableData[]=array();
                            $userTablesData[$essentialTable]=$essentialTableData;
                        }
                    }
                }
            }
        }

		return $userTablesData;
	}
	
	/**
	 * function cookDataForDBOperations
	 */
	private function cookDataForDBOperations(&$data, $fieldInfo, $fieldInfoRelatedData) {
		if(is_array($fieldInfo) && !empty($fieldInfo)) {
			$defaultFields = array();
			if(isset($fieldInfo['default'])) {
				$defaultFields = $fieldInfo['default'];
			}
			foreach($defaultFields as $field) {
				switch(strtolower($field)) {
					case 'submitdate':
					case 'lastlogintime':
					case 'usercreationdate':
					case 'lastmodifiedon': $data[$field] = 'now()'; break;
				}
			}
			$fKeyFields = array();
			if(isset($fieldInfo['fkey'])) {
				$fKeyFields = $fieldInfo['fkey'];
			}
			/* Foreign keys will be generated as their main tables will get populated. Order is the key */
			foreach($fKeyFields as $field) {
				switch(strtolower($field)) {
					case 'userid': $data[$field] = $fieldInfoRelatedData['userId']; break;
					case 'prefid': $data[$field] = $fieldInfoRelatedData['prefId']; break;
				}
			}
		}
	}
	
	/**
	 * Function to check if the Location Data is valid
	 *
	 * @param array $data
	 * @param string $tableName
	 * @param array $userDetails
	 */
    function isLocationDataValid($data,$tableName,$userDetails)
    {
	/*
        if (($tableName == 'tUserLocationPref') &&
          ((isset($data['CountryId']) == false) || ($data['CountryId'] == '2' || $data['CountryId'] == 'NULL' || (empty($data['CountryId']) == true) || $data['CountryId'] == '')) &&
          ((isset($data['StateId']) == false) || ($data['StateId'] == 'NULL' || (empty($data['StateId']) == true) || $data['StateId'] == ''))
          &&
          ((isset($data['CityId']) == false) || ($data['CityId'] == 'NULL' || (empty($data['CityId']) == true) || $data['CityId'] == ''))
          &&
          ((isset($data['LocalityId']) == false) || ($data['LocalityId'] == 'NULL' || (empty($data['LocalityId']) == true) || $data['LocalityId'] == ''))
        )
	*/
        if (($tableName == 'tUserLocationPref') &&
          (!isset($data['CountryId']) || ($data['CountryId'] == '2' || $data['CountryId'] == 'NULL' || empty($data['CountryId']) || $data['CountryId'] == '')) &&
          (!isset($data['StateId']) || ($data['StateId'] == 'NULL' || empty($data['StateId']) || $data['StateId'] == '')) &&
          (!isset($data['CityId']) || ($data['CityId'] == 'NULL' || empty($data['CityId']) || $data['CityId'] == '')) &&
          (!isset($data['LocalityId']) || ($data['LocalityId'] == 'NULL' || empty($data['LocalityId']) || $data['LocalityId'] == ''))
        )
        {
            $msg  = print_r($tableName,true) . ":::" . print_r($data,true);
            $msg .= print_r($userDetails,true);
            sendMailAlert($msg,'Found wrong entries in tUserLocationPref table.');
            return false;
        }
        else
        {
            return true;
        }
    }
	function sAddUser($request) {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$userDetails = json_decode($parameters['1'], true);
        error_log("LDBSERVER init::". print_r($userDetails, true));
        $FlagSendQNAMailerLater = 'false';
        $userDetailsStr = 'null';
        if ( isset($userDetails['FlagSendQNAMailerLater']) && ($userDetails['FlagSendQNAMailerLater'] == 'true'))
        {
            $FlagSendQNAMailerLater = 'true';
        }
		$isUserExists = $this->checkIfUserExists($userDetails);
		if($isUserExists !== false) { return $isUserExists; }
########################## CODE START TO VALIDATE MOBILE NUMBER & EMAIL ID #############################

	if (isset($userDetails['bypassmobilecheck']) && ($userDetails['bypassmobilecheck'] == 'true'))
	{
	  $bypassmobilecheck = 'true';
	}
	else
	{
	  $bypassmobilecheck = 'false';
	}
		
	if((array_key_exists('mobile',$userDetails)) && ($bypassmobilecheck == 'false'))
	{
		if ($userDetails["residenceCountry"]!='')
		{
				if(!validateMobile($userDetails["mobile"],$userDetails["residenceCountry"]) || empty($userDetails["mobile"]) || !isset($userDetails["mobile"]))
			{
				$userId = 0; $prefId = 0; $userDetailsStr = 'invalid mobile';
				$response = array(array("status"=>array($userId), "prefId"=>$prefId,"userDetailsStr"=>$userDetailsStr),'struct');
				//return $this->xmlrpc->send_error_message('007', 'Invalid Mobile Or Email');
				return $this->xmlrpc->send_response($response);
			}
		}
		else
		{
			if(!validateEmailMobile('mobile',$userDetails["mobile"],$userDetails['isdCode']) || empty($userDetails["mobile"]) || !isset($userDetails["mobile"]))
			{
			
				$userId = 0; $prefId = 0; $userDetailsStr = 'invalid mobile';
				$response = array(array("status"=>array($userId), "prefId"=>$prefId,"userDetailsStr"=>$userDetailsStr),'struct');
				//return $this->xmlrpc->send_error_message('007', 'Invalid Mobile Or Email');
				return $this->xmlrpc->send_response($response);
			}
		}
	}
	if (!validateEmailMobile('email',$userDetails["email"]))
	{
		$userId = 0; $prefId = 0; $userDetailsStr = 'invalid email';
		$response = array(array("status"=>array($userId), "prefId"=>$prefId,"userDetailsStr"=>$userDetailsStr),'struct');
		error_log("LDBSERVER FAILED ::".$response["status"].' RegisterStatus');
		// return $this->xmlrpc->send_error_message('007', 'Invalid Mobile Or Email');
		return $this->xmlrpc->send_response($response);
	}

########################## CODE END TO VALIDATE MOBILE NUMBER & EMAIL ID #############################
		$dbHandle = $this->_loadDatabaseHandle('write');

		$userDetails['randomKey'] = $this->generateValidationCode($userDetails['email']);
		$userDetails['keyid'] = $this->getKeyIdForSourceName($userDetails['sourcename']);
		$userDetails['keyquery']= 'SELECT keyid FROM tkeyTable WHERE keyname = "'. $userDetails['sourcename'] .'"';
		// TODO :: Hacks for the age, dob fields and the 'youare' fields :: Refer adduser_new : Ashish

		$userTablesMapping = $this->userTablesMap();
		$userTables = $userTablesMapping['userTables'];
		$tableDataInsertionOrder = $userTablesMapping['tableDataInsertionOrder'];
		$fieldInfoRelatedData = array();
		$userTablesData = $this->mapUserFieldsToDB($userDetails, $userTables,$tableDataInsertionOrder,$fieldInfoRelatedData);
        error_log("ANK Reg server");
		/* Creating data for the tables */

		$prefId = $userId = '';
		/* Dumping data for the tables */
		foreach($tableDataInsertionOrder as $tableName => $fieldInfo) {
			$dataSets = $userTablesData[$tableName];
			foreach($dataSets as $data) {
				/* creating data for the tables' default & foreign keys */
				$this->cookDataForDBOperations($data, $fieldInfo, $fieldInfoRelatedData);

				//if(!empty($data) && ($this->isLocationDataValid($data,$tableName,$userDetails) == true)) { //wrong boolean comparison in php
				if(!empty($data) && $this->isLocationDataValid($data,$tableName,$userDetails)) {
					$insertQuery = $dbHandle->insert_string($tableName,$data);
					$insertQuery = str_replace("'now()'","now()", $insertQuery);
					error_log('LDBSERVER ::'. $insertQuery);
					$dbHandle->query($insertQuery);
					/* Generating foreign keys data*/
					if($tableName == 'tUserPref') {
						$prefId = $fieldInfoRelatedData['prefId'] = $dbHandle->insert_id();
					}
					if($tableName == 'tuser') {
						$fieldInfoRelatedData['userId']=$userId=$dbHandle->insert_id();
					}
					if($tableName == 'tuser' || $tableName == 'tUserEducation' || $tableName == 'tUserLocationPref') {
						$pointData = array();
						$pointData = $data;
						if(($tableName == 'tuser') && (!empty($data['city']))) {
							$pointData['residenceCity'] = $data['city'];
						}
						$userpoint = new \user\libraries\RegistrationObservers\UserPointUpdation;
        				$userpoint->update('', $pointData, $userId);
					}
				}
			}
		}
		if($userId != '') { /* Do the stuff related to the user registration */
			$userDetails['userId'] = $userId;
			$loggingtype = 'register';
			$loggingput = logToFile($userId,$userDetails['mobile'],$loggingtype);
			$this->createUserDefaultMappings($userId,$userDetails['mobile'],$userDetails['sourcename'],$userDetails['email'],$userDetails['firstName']." ".$userDetails['lastName']); 
            if ($FlagSendQNAMailerLater == 'false')
            {
                $this->sendMailsToNewUser($appID, $userDetails);
            }
		} else {
			$userId = $prefId = 0;
		}
        if ($FlagSendQNAMailerLater == 'true')
        {
            $userDetailsStr = base64_encode(json_encode($userDetails));
        }
        $this->addDataToRegistrationTracking($userId, $userDetails['country'], $userDetails['city'], $userDetails['tracking_keyid']);
		$response = array(array("status"=>array($userId), "prefId"=>$prefId,"userDetailsStr"=>$userDetailsStr), 'struct');
		error_log("LDBSERVER end::".$response['status'].'RegisterStatus');
		return $this->xmlrpc->send_response($response);
	}

	function sUpdateUser($request) {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$userDetails = json_decode($parameters['1'], true);
		$userId = $parameters['2'];
	    error_log("LDBSERVER UPDATE init ::". print_r($userDetails, true));
        /*
        TODO::
        this will fail if normal user update his age etc. specially those cases
        when tuserpref table was empty in user registration
        also it's againest as per our history model where we plan to update
        tuserpref table for each user session
        */
        if($userDetails['prefId']==''){
                // it's not require that $resultLDB always return int PrefId ??
                $resultLDB=$this->isLDBUser($userId);
                $userDetails['prefId']=$resultLDB[0]['PrefId'];
        }
		$dbHandle = $this->_loadDatabaseHandle('write');

		// TODO :: Hacks for the age, dob fields and the 'youare' fields :: Refer adduser_new : Ashish

		$userTablesMapping = $this->userTablesMap();
		$userTables = $userTablesMapping['userTables'];
		$tableDataUpdationOrder = $userTablesMapping['tableDataUpdationOrder'];
		$userTablesData = $this->mapUserFieldsToDB($userDetails, $userTables,$tableDataUpdationOrder,$fieldInfoRelatedData);
                if(empty($userDetails['referer'])) {
                	unset($userTablesData['tusersourceInfo']);
                }
		/* Creating data for the tables */
		$status = 0;
		/* Dumping data for the tables */
		$fieldInfoRelatedData = array('userId'=> $userId);
		error_log("LDBSERVER UPDATE init :: 2". print_r($userDetails, true));
		$fieldInfoRelatedData['prefId'] = $prefId = isset($userDetails['prefId']) ? $userDetails['prefId'] : '';
		$where = "userid = '$userId'";
 		foreach($tableDataUpdationOrder as $tableName => $fieldInfo) {
			$dataSets = $userTablesData[$tableName];
			foreach($dataSets as $data) {
			/* creating data for the tables' default & foreign keys and other generated values */
				$this->cookDataForDBOperations($data, $fieldInfo, $fieldInfoRelatedData);
                if(isset($userDetails['prefId']) && !empty($userDetails['prefId']) && ($tableName=='tUserPref'/* || $tableName == 'tUserSpecializationPref'*/) && $tableName !='tusersourceInfo')
                {
                    $fieldInfo['operation'] = 'update';
                    $where = 'PrefId = '. $dbHandle->escape($prefId);
                }
                else
                {
                    $where = "userid = '$userId'";
                }

				if(!empty($data)) {
					if($fieldInfo['operation'] == 'insert') {
						$operationQuery = $dbHandle->insert_string($tableName,$data);
					} else {
						$operationQuery = $dbHandle->update_string($tableName,$data, $where);
					}
					$operationQuery = str_replace("'now()'","now()", $operationQuery);
					error_log('LDBSERVER UPDATE ::'. $operationQuery);
					$dbHandle->query($operationQuery);
					if($fieldInfo['operation'] !== 'insert') {
						$status = $dbHandle->affected_rows();
                        $sql="update tUserPref set is_processed='no' where PrefId=?";
                        error_log('LDBSERVER UPDATE ::'. $sql);
                        $dbHandle->query($sql, array($prefId));
					}
					/* Generating foreign keys data*/
					if($tableName == 'tUserPref' && $prefId == '') {
						$prefId = $fieldInfoRelatedData['prefId'] = $dbHandle->insert_id();
					}
				}
			}
		}

        // Updating the user's education details
		$this->updateUserEducationDetails($userTablesData['tUserEducation'], $userId);

        // Suppose user change mobile no is second overlay
        // check NDNC validation here again if mobile no comes in update case
        $mobile = isset($userDetails['mobile']) ? $userDetails['mobile'] : '';
        if (isset($mobile) && !empty($mobile))
        {
            $this->load->library('ndnc_lib');
            $ndnc_lib = new ndnc_lib();
            $result = $ndnc_lib->ndnc_mobile_check($mobile);

            //----------is LDB user Check Start-------------//
            $resultLDB = $this->isLDBUser($userId);
                $user_id=$resultLDB[0]['UserId'];
            if($userId==$user_id){
                    $isLDBUser='YES';
            }else{
                    $isLDBUser='NO';
            }

            if($result == 'true')
            {
                 $sql3= "update tuserflag set isNDNC='YES', isLDBUser='$isLDBUser' WHERE userId=?";
                error_log('LDBSERVER ::'. $sql3);
                $dbHandle->query($sql3, array($userId));
            }
            elseif ($result == 'false')
            {
                $query_sms = "select isNDNC from tuserflag where userId = ?";
                $query_sms = $dbHandle->query($query_sms, array($userId));
                foreach ($query_sms->result_array() as $row1)
                {
                    $isNDNC = $row1['isNDNC'];
                }
                if ( $isNDNC == 'NA')
                {
                    $this->load->library('Alerts_client');
                    $alerts_client = new Alerts_client();
		  // changes in sms
		    $Isregistration = 'Yes';
                    $alerts_clients->addSmsQueueRecord('12',$mobile,'Your number has been successfully verified',$userId,'0000-00-00 00:00:00',"",$Isregistration);
                    }
                $sql3= "update tuserflag set isNDNC='NO', isLDBUser=? WHERE userId=?";
                error_log('LDBSERVER ::'. $sql3);
                $dbHandle->query($sql3, array($isLDBUser, $userId));
            }
            elseif ($result == 'na')
            {

                $sql3= "update tuserflag set mobileverified='0', isNDNC='NA', isLDBUser=? WHERE userId=?";
                error_log('LDBSERVER ::'. $sql3);
                $dbHandle->query($sql3, array($isLDBUser, $userId));
            }
        }

        $resultLDB = $this->isLDBUser($userId);
	    $user_id=$resultLDB[0]['UserId'];
		if($userId==$user_id){
            $isLDBUser='YES';
	    }
        else
        {
            $isLDBUser='NO';
	    }
		$sqlLDB= "update tuserflag set isLDBUser=? WHERE userId=?";
        error_log('LDBSERVER ::'. $sqlLDB);
        $dbHandle->query($sqlLDB, array($isLDBUser, $userId));

		$response = array(array("status"=>array($status), "prefId"=> $prefId), 'struct');
		error_log("LDBSERVER UPDATE END ::" . $response['status'].'RegisterStatus');
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * Function to get the city_id,country_id,state_id by city name
	 *
	 * @param object $request
	 *
	 */
    function sGetIdForCityName($request)
    {
		$parameters = $request->output_parameters();
		$dbHandle = $this->_loadDatabaseHandle();
        $cityName = $parameters[0];
        $queryCmd = "select city_id, countryId, state_id from countryCityTable where city_name=?";
		$msgArray = array();
        $query = $dbHandle->query($queryCmd, array($cityName));
        foreach ($query->result_array() as $row){
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
    }
	
	/**
	 * Function to get the detail of Country by name
	 *
	 * @param object $request
	 */
    function sGetIdForCountryName($request)
    {
		$parameters = $request->output_parameters();
		$dbHandle = $this->_loadDatabaseHandle();
        $countryName = $parameters[0];
        $queryCmd = "select * from countryTable where name = ?";
		$msgArray = array();
        $query = $dbHandle->query($queryCmd, array($countryName));
        foreach ($query->result_array() as $row){
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
    }
    
    /**
     * Function to get the Prefernces for User
     *
     * @param object $request
     */
    function sGetPreferencesForUser($request)
    {
		$parameters = $request->output_parameters();
		$dbHandle = $this->_loadDatabaseHandle();
        $userId= $parameters[1];
        $queryCmd = "select * from tUserPref where userId = ?";
		$msgArray = array();
        $query = $dbHandle->query($queryCmd, array($userId));
        foreach ($query->result_array() as $row){
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
    }

	/**
	 * Function to update the user Education Details
	 *
	 * @param array $detailRows
	 * @param integer $userId
	 *
	 */
	private function updateUserEducationDetails($detailRows, $userId){
		$dbHandle = $this->getDBHandle('write');
		//Unique Fields with default Values
		$uniqueFieldsForTable = array('UserId'=>'', 'Level'=>'', 'Name'=>'', 'CourseSpecialization'=>0);
		foreach($detailRows as $detailRow){
			$detailRow['UserId'] = $userId;
			foreach($uniqueFieldsForTable as $uniqueField => $uniqueFieldValue) {
				if(!array_key_exists($uniqueField, $detailRow)) {
					$detailRow[$uniqueField] = $uniqueFieldValue;
				}
			}
			$detailRow['SubmitDate'] = 'now()';
			$operationQuery = $dbHandle->insert_string('tUserEducation',$detailRow);
			$operationQuery .= 'ON DUPLICATE KEY UPDATE ';
			$operationQueryColumns = array();
			foreach($detailRow as $columnName => $columnValue ) {
				switch($columnName) {
					case 'InstituteId' :
					case 'Marks' :
					case 'MarksType' :
					case 'CourseCompletionDate' :
					case 'OngoingCompletedFlag' :
					case 'City' :
					case 'Country' :
					case 'Status' :
					case 'Level' :
						$operationQueryColumns[] = $columnName .' = "'. $columnValue .'"';
					break;
				}
			}
			$operationQuery .=  implode(',',$operationQueryColumns);
			$operationQuery = str_replace("'now()'","now()", $operationQuery);
			error_log('LDBSERVER ::'. $operationQuery);
			$dbHandle->query($operationQuery);
		}
	}
	
	/**
	 * Function to check user id by email
	 * @param string $email
	 */
	private function checkUserByEmail($email) {
		$dbHandle = $this->_loadDatabaseHandle();
		$sql = "select userid from tuser where email = ?";
		$query = $dbHandle->query($sql, array(addslashes($email)));
		$row = $query->row();
		return (!empty($row));
	}
	
	/**
	 * Function to check User by Display name
	 * @param string $displayname
	 */
	private function checkUserByDisplayName($displayName) {
		$dbHandle = $this->_loadDatabaseHandle();
		$sql = "select displayname from tuser where displayname = ?";
		$query = $dbHandle->query($sql, array(addslashes($displayName)));
		$row = $query->row();
		return (!empty($row));
	}
	
	/**
	 * Funnction to get the DB Handler
	 *
	 * @param string $operation
	 */
	private function getDBHandle($operation = 'read') {
		if($operation=='read'){
			return $this->_loadDatabaseHandle();
		}
		else{
			return $this->_loadDatabaseHandle('write');
		}
	}
	
	
	/**
	 * Function to check if the user exists or not
	 * @param object $request
	 */
	function checkIfUserExists($request) {
		$displayNameFlag = $this->checkUserByDisplayName($request['displayname']);
		$emailFlag = $this->checkUserByEmail($request['email']);
		if($emailFlag || $displayNameFlag) {
			$email = $emailFlag ? -1 : 0;
			$displayName = $displayNameFlag ? -1 : 0;
			$msgArray = array('status' => -1, 'email' => $email, 'displayname' => $displayName);
			$response = array($msgArray,'struct');
			//error_log_shiksha(print_r($response,true));
			return $this->xmlrpc->send_response($response);
		}
		return false;
	}
	
	/**
	 * Function to generate the Validation code
	 * @param string $email
	 */
	private function generateValidationCode($email) {
		$code = md5($email .':'. date());
		return $code;
	}
	
	/**
	 * Function to get the MAX user id as special ID for usergroup
	 *
	 * @param string $userGroup
	 */
	private function getSpecialIdForUser($userGroup) {
		$userId = '';
		$dbHandle = $this->getDBHandle();
		if(($userGroup == "cms") || ($userGroup == "privileged")){
			$queryUserid = $dbHandle->query('SELECT MAX(userid) AS specialId FROM tuser WHERE userid <1000'); //1-100 Reserved for CMS and Privileged users
			$row = $queryUserid->row_array();
			$userId = ($row['specialId']+1);
		}
		return $userId;
	}
	
	
	/**
	 * Function to create the User Default Mappings
	 *
	 * @param integer $userId
	 * @param string $mobile
	 * @param string $sourceflag
	 */
	function createUserDefaultMappings($userId,$mobile,$sourceflag = '',$email = '', $name = '') {
		$dbHandle = $this->getDBHandle('write');
		$sql1="INSERT INTO userPointSystem(userId,userPointValue) VALUES('?',100)";
                error_log('LDBSERVER ::'. $sql1);
		$dbHandle->query($sql1,array($userId));
                $sql2="INSERT INTO shiksha_mypage VALUES('',?,'myShikshaDiscussion',1,'1',2),('',?,'myShikshaEvents',2,'1',2),('',?,'myShikshaNetwork',3,'1',10),('',?,'myShikshaCollgenetwork',4,'1',3),('',?,'blogs',5,'1',2),('',?,'polls',6,'0',2),('',?,'myShikshaListing',7,'1',3)";
		        error_log('LDBSERVER ::'. $sql2);
                $dbHandle->query($sql2, array($userId, $userId, $userId, $userId, $userId, $userId, $userId));
        // ---------------- NDNC CHECK START----------------//
        $this->load->library('ndnc_lib');
        $ndnc_lib = new ndnc_lib();
        $result = $ndnc_lib->ndnc_mobile_check($mobile);

	//----------is LDB user Check Start-------------//
	$resultLDB = $this->isLDBUser($userId);
        $user_id=$resultLDB[0]['UserId'];
	
      if($sourceflag == 'SUMS_ENTERPRISE_REGISTRATION'){ 
                $isLDBUser='NO'; 
        } 
        elseif($userId==$user_id){ 
		$isLDBUser='YES';
	}else{
		$isLDBUser='NO';
	}
	error_log("isLDBUser is found as....".$isLDBUser);

	//After the NDNC Check, check if the details entered are for a Test User
        $isTestUser = "NO";
        require FCPATH.'globalconfig/testUserConfig.php';
        if($name != "") {
            $filter = '/^testuser[ ]*[0-9]+$/i';
            if(preg_match($filter,$name)) {
                $isTestUser = "YES";
            }
        }

        if($email != "") {
            $emailDomain = end(explode('@',$email));
            if(is_array($testUserDomainsForEmail) && in_array($emailDomain,$testUserDomainsForEmail)) {
                $isTestUser = "YES";
            }
        }

        if($mobile != "") {
            if(is_array($testUserMobileNumbers) && in_array($mobile,$testUserMobileNumbers)) {
                $isTestUser = "YES";
            }
        }

        if($result == 'true')
        {
            $sql3= "insert into tuserflag(`userId`, `pendingverification`, `hardbounce`, `unsubscribe`, `ownershipchallenged`, `softbounce`, `abused`, `mobileverified`, `emailsentcount`, `emailverified`, `isNDNC`, `isLDBUser`, `isTestUser`) values(?, '1','0','0','0','0','0','0',0,'0','YES',?,?)";
            error_log('LDBSERVER ::'. $sql3);
            $dbHandle->query($sql3, array($userId, $isLDBUser, $isTestUser));
        }
        elseif ($result == 'false')
        {
            $sql3= "insert into tuserflag(`userId`, `pendingverification`, `hardbounce`, `unsubscribe`, `ownershipchallenged`, `softbounce`, `abused`, `mobileverified`, `emailsentcount`, `emailverified`, `isNDNC`, `isLDBUser`, `isTestUser`) values(?,'1','0','0','0','0','0','0',0,'0','NO',?,?)";
            error_log('LDBSERVER ::'. $sql3);
            $dbHandle->query($sql3, array($userId, $isLDBUser, $isTestUser));
        }
        elseif ($result == 'na')
        {
            $sql3= "insert into tuserflag(`userId`, `pendingverification`, `hardbounce`, `unsubscribe`, `ownershipchallenged`, `softbounce`, `abused`, `mobileverified`, `emailsentcount`, `emailverified`, `isNDNC`, `isLDBUser`, `isTestUser`) values(?,'1','0','0','0','0','0','0',0,'0','NA',?,?)";
            error_log('LDBSERVER ::'. $sql3);
            $dbHandle->query($sql3, array($userId, $isLDBUser, $isTestUser));
        }
        // ---------------- NDNC CHECK END ----------------//
	}
	
	
	/**
	 * Function to send mails to new users
	 *
	 * @param integer $appID
	 * @param array $userData
	 */
	function sendMailsToNewUser($appID,$userData) {
		$userId = $userData['userId'];
		$displayName= $userData['displayname'];
		$email = $userData['email'];
		$mobile= $userData['mobile'];
		$newsLetterEmailFlag = $userData['newsletteremail'];
		$randomKey = $userData['randomKey'];
		$subCategories = $userData['subCategory'];
		$categories = $userData['category'];

		$verifyLink = "https://".SHIKSHACLIENTIP."/shiksha/userresponse/".$randomKey."/verify";
		$unsubscribeLink = "https://".SHIKSHACLIENTIP."/shiksha/userresponse/".$randomKey."/unsubscribe";
        error_log($verifyLink.'VERIFYLINK');
		//$this->sendVerificationMail($email,$displayName,$verifyLink,$unsubscribeLink,$userId);
		error_log("PAN mail sent : ");
		$this->sendVerificationSms($mobile,$displayName,$userId);
		error_log("PAN sms sent : ");

		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		if(!is_numeric($newsLetterEmailFlag) && empty($newsLetterEmailFlag)) { $newsLetterEmailFlag = 1; }
		if($newsLetterEmailFlag==1) {
			if($subCategories!='') {
		//		$resultQmailer = $objmailerClient->sendRegistrationQuestionMailer($appID,$userId,$displayName, $subCategories,REGISTRATION_QUESTION_POOL_DURATION,$email);
			} else {
		//		$resultQmailer = $objmailerClient->sendRegistrationQuestionMailer($appID,$userId,$displayName, $categories,REGISTRATION_QUESTION_POOL_DURATION,$email);
			}
		}
	}
	
	/**
	 * Get Key ID for key name
	 *
	 * @param string $sourceName
	 */
	function getKeyIdForSourceName($sourceName) {
		$dbHandle = $this->getDBHandle();
		$query = 'SELECT keyid FROM tkeyTable WHERE keyname = ?';
		$resultSet = $dbHandle->query($query,array($sourceName));
		$row = $resultSet->row();
		return $row->keyid;
	}
	
	/**
	 * Function to check if the User if LDB User
	 *
	 * @param integer $userid
	 */
	function isLDBUser($userid){
                $dbHandle = $this->getDBHandle();
		$query = "select UserId, PrefId from tUserPref where DesiredCourse is not NULL and UserId=? and DesiredCourse not in(1,35,36,37,38,39,40,41,42,43,44,45,382) order by PrefId desc limit 1";
                error_log("query for isLDBUser is as ".$query);
                $query = $dbHandle->query($query, array($userid));
                $result = $query->result_array();
                if(empty($result)){
                $query = "select PrefId from tUserPref where UserId=? order by PrefId desc limit 1";
                error_log("query for isLDBUser is as ".$query);
                $query = $dbHandle->query($query, array($userid));
                $result = $query->result_array();
                }
		error_log("result is finally as ".print_r($result,true));
		return $result;

        }


        /**
     * Function to get the Category Id for User
     *
     * @param object $request
     */
    function sGetCategoryIdForUser($request)
    {
    	$parameters = $request->output_parameters();
    	$userId= $parameters[1];
        $dbHandle= $parameters[2];
		
		$dbHandle = $this->_loadDatabaseHandle($dbHandle);

        $queryCmd = "select tcs.CategoryId from tUserPref tuf inner join tCourseSpecializationMapping tcs on tuf.desiredCourse = tcs.specializationId left join LDBCoursesToSubcategoryMapping ldbcsm on tcs.specializationId = ldbcsm.ldbCourseId where tcs.status = 'live' and tcs.CategoryId = 3 and tuf.userid = ? and ldbcsm.categoryID not in (28,29,30,31)";
		$msgArray = array();
        $query = $dbHandle->query($queryCmd, array($userId));
        foreach ($query->result_array() as $row){
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        
        return $this->xmlrpc->send_response($response);
    }
}
?>

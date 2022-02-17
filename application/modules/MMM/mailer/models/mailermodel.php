<?php

class mailermodel extends MY_Model
{
	private $dbHandle = null;

	function __construct()
	{
		parent::__construct('Mailer');
	}

	private function initiateModel($mode = "write", $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	public function getTrackingDetails($mailerIds = array()){
		
		$this->initiateModel('read');
		if(empty($mailerIds)){
        	return;
        }

		//$sql = "SELECT mailerId, trackerId, count(*) as count from mailer.mailerMis WHERE mailerId in (". implode(',', $mailerIds) .") group by mailerId, trackerId ";
		$sql = "SELECT mailerId, trackerId, count(*) as count from mailer.mailerMis WHERE mailerId in (?) group by mailerId, trackerId ";

		$query = $this->dbHandle->query($sql, array($mailerIds));

		return $query->result();
	}


	public function getUserId($email)
	{
		$this->initiateModel('read','User');
		$sql = "select userid from tuser where email = ?";
		$query = $this->dbHandle->query($sql,array($email));
		$userid = 0;
		foreach($query->result() as $row) {
			$userid = $row->userid;
		}
		return $userid;
	}

	public function saveTestMail($userId, $subject, $message, $toEmail, $check)
	{
		$this->initiateModel('write');
		$userId = $this->dbHandle->escape($userId);
		//$subject = $this->dbHandle->escape($subject);
		//$message = $this->dbHandle->escape($message);

		$issent = 'NO';
		if($check == 'TRUE') {
			$issent = 'YES';
		} 

		$sql1 = "insert into mailQueue (`mailid` , `userid` , `mailerid` ,`fromemail`,`toemail`,`mailertype` ,`issent` , `createdtime` , `buildtime`, `sendtime`) values ('', ?, 0, 'info@shiksha.com', ?, 'test', ?, now(), '', '')";
		$this->dbHandle->query($sql1, array($userId, $toEmail, $issent));
		$insertId = $this->dbHandle->insert_id();
		$sql2 = "insert into ".$this->_getContentTableName()." (`id` , `mailid` , `subject` , `content` , `hasattachment` ) values ('', ?, ?, COMPRESS(?), 'no')";
		$this->dbHandle->query($sql2,array($insertId, $subject, $message));
	}

	public function saveTestMailByAmazon($userId, $subject, $message, $toEmail) {
		$this->initiateModel('write');
		$userId = $this->dbHandle->escape($userId);

		$sql1 = "insert into mailQueue (`mailid` , `userid` , `mailerid` ,`fromemail`,`toemail`,`mailertype` ,`issent`, `mailerServiceType`, `createdtime` , `buildtime`, `sendtime`) values ('', ?, 0, 'info@shiksha.com', ?, 'test', 'NO', 'amazon', now(), '', '')";
		$this->dbHandle->query($sql1, array($userId, $toEmail));
		$insertId = $this->dbHandle->insert_id();
		$sql2 = "insert into ".$this->_getContentTableName()." (`id` , `mailid` , `subject` , `content` , `hasattachment` ) values ('', ?, ?, COMPRESS(?), 'no')";
		$this->dbHandle->query($sql2,array($insertId, $subject, $message));

	}

	public function getTemplateData($templateId)
	{
		$this->initiateModel('read');
		$sql = "SELECT id as templateId, subject as subject, htmlTemplate as htmlTemplate from mailerTemplate b where  b.id = ? ";
		$query = $this->dbHandle->query($sql, $templateId);
		foreach($query->result() as $row) {
			$returnData['templateId'] = $row->templateId;
			$returnData['subject'] = $row->subject;
			$returnData['htmlTemplate'] = $row->htmlTemplate;
		}
		$queryCmd = "SELECT a.varname, a.varvalue, a.flagother, b.varField, b.varTable from templateVariable a left join variableNames b on a.varValue = b.varValue where a.templateId = ? order by a.id";
		$data = array();
		$query = $this->dbHandle->query($queryCmd, array($templateId));
		foreach($query->result() as $row) {
			$data[] = (array)$row;
		}
		
		$returnData['templateVars'] = $data;
		return $returnData;
	}

	public function getCSVData($listId)
	{
		$this->initiateModel('read');
		$sql = "select num,keyname,value from csvKeyValue where listid = ?";
		error_log($sql);
		$query = $this->dbHandle->query($sql, array($listId));
		$data = array();
		foreach($query->result() as $row) {
			$data[$row->num][$row->keyname] = $row->value;
		}
		unset($query);
		return $data;
	}

	public function saveSpamScore($templateId,$score)
	{
		$this->initiateModel('write');
		$sql = "update mailerTemplate set spamscore = ? where id = ?";
		error_log($sql);
		$query = $this->dbHandle->query($sql, array($score, $templateId));
	}

	public function getTestMailData($templateId)
	{
		$this->initiateModel('read');
		$queryCmd = "SELECT id as templateId, subject as subject, htmlTemplate as htmlTemplate from mailerTemplate b where  b.id = ? ";
		$data = array();
		$query = $this->dbHandle->query($queryCmd, array($templateId));
		foreach($query->result() as $row) {
			$data['templateId'] = $row->templateId;
			$data['subject'] = $row->subject;
			$data['content'] = $row->htmlTemplate;
		}
		return $data;
	}

	public function updateNumberProcessed($mailerId, $numberProcessed)
	{
		$this->initiateModel('write');
		$sql = "update mailer set numberprocessed = numberprocessed + ? where id = ?";
		error_log($sql);
		$query = $this->dbHandle->query($sql, array($numberProcessed, $mailerId));
	}

	public function setTimeWindowProcessed($mailerId, $time_window)
	{
		$this->initiateModel('write');
		list($time_window_start,$time_window_end) = explode(";",$time_window);
		$sql = "update mailer set numberprocessed = 0, last_processed_time_window = ? where id = ?";
		error_log($sql);
		$query = $this->dbHandle->query($sql, array($time_window_end, $mailerId));
	}

	public  function getUnprocessedMailers($batch)
	{
		if($batch < 0){
			return array();
		}	

		$this->initiateModel('read');
		$queryCmd = "SELECT a.clientId,a.dripMailerType,a.parentMailerId, a.id as mailerId, a.mailername as mailerName, a.listId as listId, a.criteria as criteria, a.templateid as templateId, b.subject as subject, b.htmlTemplate as htmlTemplate, a.senderMail,a.sendername, a.numberprocessed, a.totalMailsToBeSent, a.last_processed_time_window, a.templateCriteria from mailer a, mailerTemplate b where a.templateId = b.id and a.mailssent in ('false','inProgress') and a.mailertype = 'client' and a.time < ? and a.batch =?";
		$data = array();

		$query = $this->dbHandle->query($queryCmd, array(date('Y-m-d H:i:s'), $batch));
		foreach($query->result() as $row) {
			$data[] = (array)$row;
		}
		return $data;
	}

	public  function getProductMailers($batch)
	{
		$this->initiateModel('read');
		$queryCmd = "SELECT a.id as mailerId, a.mailername as mailerName, a.listId as listId, a.criteria as criteria, a.templateid as templateId, b.subject as subject, b.htmlTemplate as htmlTemplate, a.senderMail,a.sendername,a.numberprocessed, a.totalMailsToBeSent, a.last_processed_time_window, a.templateCriteria from mailer a, mailerTemplate b where a.templateId = b.id and a.mailertype = 'product' and a.mailssent = 'false' and a.batch = ? order by a.priority asc";
		$data = array();
		$query = $this->dbHandle->query($queryCmd, array($batch));
		foreach($query->result() as $row) {
			$data[] = (array)$row;
		}
		return $data;
	}

	public  function getMailerById($id)
	{
		$this->initiateModel('read');
		$queryCmd = "SELECT a.id as mailerId, a.mailername as mailerName, a.listId as listId, a.criteria as criteria, a.templateid as templateId, b.subject as subject, b.htmlTemplate as htmlTemplate, a.senderMail,a.numberprocessed, a.totalMailsToBeSent, a.last_processed_time_window, a.templateCriteria from mailer a, mailerTemplate b where a.templateId = b.id and a.id = ?";
		$data = array();
		$query = $this->dbHandle->query($queryCmd, array($id));
		foreach($query->result() as $row) {
			$data[] = (array)$row;
		}
		return $data;
	}

	public function updateMailerStatus($mailerId, $status, $transFlag = false)
	{
		$this->initiateModel('write');
		if($transFlag){
			$this->dbHandle->trans_start();
		}
		$queryCmd = "update mailer set mailsSent = ?  where id =?";
		$this->dbHandle->query($queryCmd, array($status, $mailerId));
	}

	public function getTemplateVars($templateid)
	{
		$this->initiateModel('read');
		$queryCmd = "SELECT a.varname, a.varvalue, a.flagother, b.varField, b.varTable from templateVariable a left join variableNames b on a.varValue = b.varValue where a.templateId = ? order by a.id";
		$data = array();
		$query = $this->dbHandle->query($queryCmd, array($templateid));
		foreach($query->result() as $row) {
			$data[] = (array)$row;
		}
		return $data;
	}

	public function addMailToQueue($userDetails, $mailerId, $mailerType, $senderMail, $senderName)
	{
		$this->initiateModel('write');
		if(trim($senderMail) == ''){
			$senderMail = 'info@shiksha.com';
		}
		
		$userids = array_keys($userDetails);
		//cross database query
		//$resultUsers = $query->result_array();

		$this->initiateModel('write', 'Mailer');
		

		if(!empty($userDetails)) {

			// Filter mails for Amazon SES
	        global $domainsUsingAmazonMailService;
	        global $emailidsUsingAmazonMailService;

			$queryCmdMailQ = "insert into mailQueue (`mailid` , `userid` , `mailerid` ,`fromusername`, `fromemail`,`toemail`,`mailertype` ,`issent` , `createdtime` , `buildtime`, `sendtime`, `mailerServiceType`) values ";
			if(trim($senderName) == ''){
				$senderName = "Shiksha.com";
			}
			foreach($userDetails as $userId => $email){

				$mailerServiceType = 'shiksha';
				$toDomainName = explode("@", $email);
		        if( (in_array($toDomainName[1], $domainsUsingAmazonMailService)) || (in_array($email, $emailidsUsingAmazonMailService)) ) {
		            $mailerServiceType = 'amazon';
		        }

		        $mailStatus = 'no';
				$queryCmdMailQ.= "(NULL, ".$this->dbHandle->escape($userId).", ".$this->dbHandle->escape($mailerId).",".$this->dbHandle->escape($senderName).", ".$this->dbHandle->escape($senderMail)." ,".$this->dbHandle->escape($email).",".$this->dbHandle->escape($mailerType).", ".$this->dbHandle->escape($mailStatus)." , now(), '','', ".$this->dbHandle->escape($mailerServiceType)."),";
			}
			$queryCmdMailQ = substr($queryCmdMailQ,0,-1);
			// error_log($queryCmdMailQ);
			$this->dbHandle->query($queryCmdMailQ);
			unset($queryCmdMailQ);
		}
		
		
		$sql = "select userid,mailid,toemail,hex(encode(`toemail`,'ShikSha')) as encodedMail from mailQueue where mailerid = ? and mailertype = ? and userid in (?) order by mailid asc";
		$query = $this->dbHandle->query($sql, array($mailerId, $mailerType,$userids));
		unset($userids);
		$data = array();
		$result = $query->result_array();
		foreach($result as $row) {
			$data[$row['userid']]['mailid'] = $row['mailid'];
			$data[$row['userid']]['email'] = $row['toemail'];
			$data[$row['userid']]['encodedMail'] = $row['encodedMail'];
			unset($row);
		}
		unset($query);
		unset($result);
		return $data;
	}

	public function addToMailQueue($userData, $mailerId){
		if(empty($userData)) {
			return true;
		}
		$this->initiateModel('write');
		$this->dbHandle->save_queries = false;
		$sql = "insert into ".$this->_getContentTableName()." (`id` , `mailid` , `subject` , `content` , `hasattachment` ) values ";
		
		foreach($userData as $userId => $userMailDetails){
			$sql .= "('','".$userMailDetails["mailid"]."','".mysql_escape_string($userMailDetails["subject"])."',COMPRESS('".mysql_escape_string($userMailDetails["template"])."'),'no'),";
			
			unset($userMailDetails);
			unset($userId);
		}

		$sql = rtrim($sql, ",");

		unset($userData);		

		$this->dbHandle->query($sql);
		$this->dbHandle->save_queries = true;

		unset($sql);

	}

	public function updateMailQueue($userData, $mailerId, $mailerType, $count, $CHUNK)
	{
		if(empty($userData)) {
			return true;
		}
		$this->initiateModel('write');
		if($count%$CHUNK == 0)$lastOffset = $count - $CHUNK;
		else $lastOffset = floor($count/$CHUNK)*$CHUNK;
		
		//error_log("ZYNGA ".$count." CHUNK SIZE = ".$CHUNK." lastoffset = $lastOffset");

		$sql = "insert into ".$this->_getContentTableName()." (`id` , `mailid` , `subject` , `content` , `hasattachment` ) values ";
		$i = 0;
		foreach($userData as $userId => $userMailDetails){
			if($i < $lastOffset){$i++;continue;}
			if($i >= $count)break;
			$sql .= "('','".$userMailDetails["mailid"]."','".mysql_escape_string($userMailDetails["subject"])."',COMPRESS('".mysql_escape_string($userMailDetails["template"])."'),'no'),";
			$i++;
		}
                unset($userData);
                
		//$sql = substr($sql,0,-1);
                $sql = trim($sql,",");
		$this->dbHandle->query($sql);
                unset($sql);
	}

	public function markUsersInMailQueue($mailIds)
	{
		if(empty($mailIds)) {
			return true;
		}
		$this->initiateModel('write');
		
		$sql = "update mailQueue set issent = 'notsent' where mailid in (".implode(',',$mailIds).")";
		$this->dbHandle->query($sql);
	}

	public function getUsersDataByEmailIds($emailIds) {
		if(empty($emailIds)) {
			return;
		} else {
			$allEmailIds = implode(",", $emailIds);
		}

		$this->initiateModel('read','User');

		$sql = "select t.userid, t.email, tuf.hardbounce, unscr.unsubscribe_category from tuser t inner join tuserflag tuf on t.userid = tuf.userId left join user_unsubscribe_mapping unscr on unscr.user_id = t.userid and unscr.status = 'live' and unscr.unsubscribe_category =5 where t.email in (".$allEmailIds.")";
		$query = $this->dbHandle->query($sql);
		foreach($query->result_array() as $row){
			$data[$row['email']]['userid']	   = $row['userid'];
			$data[$row['email']]['hardbounce'] = $row['hardbounce'];
			$data[$row['email']]['unsubscribeCategory'] = $row['unsubscribe_category'];
		} 
		unset($query);
		return $data;
	}

	public function getUsersDataByUserIds($chunk){
		if(!is_array($chunk) || count($chunk) <1) {
			return;
		}	

		$this->initiateModel('read','User');
		
		$sql = "select t.userid, t.email,tuf.hardbounce,unscr.unsubscribe_category  from tuser t inner join tuserflag tuf on t.userid = tuf.userId  left join user_unsubscribe_mapping unscr on unscr.user_id = t.userid and unscr.status = 'live' and unscr.unsubscribe_category =5 where tuf.softbounce='0' and tuf.ownershipchallenged='0' and tuf.abused='0' and t.usergroup NOT IN ('sums', 'enterprise', 'cms', 'saAdmin', 'saCMS') and t.userid in (?) and unscr.id is null";		

		$query = $this->dbHandle->query($sql,array($chunk));
		$data = array();
		foreach($query->result_array() as $row){
			if($row['hardbounce'] == '1' || $row['unsubscribe_category'] == 5){
				continue;
			}

			$data[$row['userid']]= $row['email'];
			
		}
		return $data;
	}

	public function addCSVMailToQueue($csvData, $mailerId, $mailType, $senderMail, $senderName, $usersData)
	{
		$this->initiateModel('write');
		if(trim($senderMail) == ''){
			$senderMail = 'info@shiksha.com';
		}
		if(trim($senderName) == ''){
			$senderName = "Shiksha.com";
		}
		$emailArray = array();

        // Filter mails for Amazon SES
        global $domainsUsingAmazonMailService;
        global $emailidsUsingAmazonMailService;

		$sql = "insert into mailQueue (`mailid` , `userid` , `mailerid` ,`fromusername`, `fromemail`,`toemail`,`mailertype` ,`issent` , `createdtime` , `buildtime`, `sendtime`, `mailerServiceType`) values ";
		foreach($csvData as $data){

			$emailArray[] = $data['email'];
			$mailerServiceType = 'shiksha';
			$toDomainName = explode("@", $data['email']);
	        if( (in_array($toDomainName[1], $domainsUsingAmazonMailService)) || (in_array($data['email'], $emailidsUsingAmazonMailService)) ) {
	            $mailerServiceType = 'amazon';
	        }

			$mailStatus = 'no';
	        if($usersData[$data['email']]['hardbounce'] == '1') {
	        	$mailStatus = 'notsent';
	        }
	        $userId = ($usersData[$data['email']]['userid'])?($usersData[$data['email']]['userid']):0;

			$sql.= " (NULL, ".$this->dbHandle->escape($userId).", ".$this->dbHandle->escape($mailerId).",".$this->dbHandle->escape($senderName).", ".$this->dbHandle->escape($senderMail).", ".$this->dbHandle->escape($data['email']).",".$this->dbHandle->escape($mailType).", ".$this->dbHandle->escape($mailStatus).",now(),'','', ".$this->dbHandle->escape($mailerServiceType)."),";
		}
		$sql = substr($sql,0,-1);
		$this->dbHandle->query($sql);

		$sql2 = "select mailid, toemail, hex(encode(`toemail`,'ShikSha')) as encodedMail from mailQueue where mailerid = ? and toemail in (?) order by mailid asc";
		$query = $this->dbHandle->query($sql2, array($mailerId, $emailArray));

		unset($emailArray);
		$returnData = array();
		$result = $query->result_array();
		foreach($result as $row) {
			$returnData[$row['toemail']]['mailid']      = $row['mailid'];
			$returnData[$row['toemail']]['encodedMail'] = $row['encodedMail'];
			unset($row);
		}
		unset($query);
		unset($result);
		unset($csvData);
		return $returnData;
	}

	public function updateCSVMailQueue($email, $mailerId, $mailerType, $subject, $message)
	{
		$this->initiateModel('write');
		if(trim($email)) {
			$queryCmdMailQContent = "insert into ".$this->_getContentTableName()." (`id` , `mailid` , `subject` , `content` , `hasattachment` )  ";
			$queryCmdMailQContent.= " SELECT '', mailid , '".addslashes($subject)."', COMPRESS('".addslashes($message)."'), 'no' from mailQueue where toemail = ".$this->dbHandle->escape($email)." and mailerid = ".$this->dbHandle->escape($mailerId)." and mailertype = ".$this->dbHandle->escape($mailerType);
			$this->dbHandle->query($queryCmdMailQContent);
		}
	}

	public function getUserBasicDetails($userIds, $params)
	{
		$this->initiateModel('read','User');
		$sql = "select userid, firstname as FirstName, CONCAT(firstname, ' ', IFNULL( lastname, '' ))  as displayname,email,hex(encode(`email`,'ShikSha')) as encodedMail,'aHR0cHM6Ly93d3cuc2hpa3NoYS5jb20vdXNlcnByb2ZpbGUvZWRpdD91bnNjcj01' as unsubscribeUrl from tuser where userid in (?)";
		$query = $this->dbHandle->query($sql,array($userIds));
		$data = array();
		foreach($query->result() as $row) {
			$data[$row->userid] = (array)$row;
		}
		unset($query);
		unset($row);
		unset($sql);
		return $data;
	}

	public function getUserCourseDetails($userIds)
	{
		$this->initiateModel('read','User');
		
		$sql = "SELECT c.name as course, a.userid
			FROM tUserPref a, tCourseSpecializationMapping b left join categoryBoardTable c on b.categoryid = c.boardid
			WHERE a.desiredcourse = b.specializationid
			AND a.userid IN (?)
			AND a.DesiredCourse > 0 AND (a.ExtraFlag IS NULL OR a.ExtraFlag = '')
			and b.courseName = 'All'
			
			UNION
			
			SELECT b.coursename as course, a.userid
			FROM tUserPref a, tCourseSpecializationMapping b
			WHERE a.desiredcourse = b.specializationid
			AND a.userid IN (?)
			AND a.DesiredCourse > 0 AND (a.ExtraFlag IS NULL OR a.ExtraFlag = '')
			and b.courseName != 'All'
			
			UNION
			
			SELECT c.acronym as course, a.userid
			FROM tUserPref a, tUserPref_testprep_mapping b,blogTable c
			WHERE a.PrefId = b.prefid
			AND b.blogid = c.blogId
			AND c.status = 'live'
			AND a.userid IN (?)
			AND a.DesiredCourse = 0 AND a.ExtraFlag = 'testprep'
			
			UNION
			
			SELECT c.name as course, a.userid
			FROM tUserPref a, tCourseSpecializationMapping b,categoryBoardTable c
			WHERE a.desiredcourse = b.specializationid
			AND b.CategoryId = c.boardId
			AND a.userid IN (?)
			AND a.DesiredCourse > 0 AND a.ExtraFlag  = 'studyabroad'";
		
		$query = $this->dbHandle->query($sql,array($userIds,$userIds,$userIds,$userIds));
		$data = array();
		foreach($query->result() as $row) {
			$data[$row->userid] = (array)$row;
		}
		return $data;
	}
        
        function getUserAbroadCourseDetails($userIds){
            $this->initiateModel('read','User');
		
		$sql = "SELECT c.name as course, a.userid, b.CourseName
			FROM tUserPref a, tCourseSpecializationMapping b,categoryBoardTable c
			WHERE a.desiredcourse = b.specializationid
			AND b.CategoryId = c.boardId
			AND a.userid IN (?)
			AND a.DesiredCourse > 0 AND a.ExtraFlag  = 'studyabroad'";
		
		$query = $this->dbHandle->query($sql, array($userIds));
		$data = array();
		foreach($query->result() as $row) {
                    if($row->course === 'All'){
                        $row->course = $row->CourseName;
                    }else{
                        $row->course = $row->CourseName.' of '.$row->course;
                    }
			$data[$row->userid] = (array)$row;
		}
		return $data;
        }
        
        function getUserAbroadSubCatDetails($userIds){
            $this->initiateModel('read','User');
		
		$sql = "SELECT c.name as subcatname, a.userid
			FROM tUserPref a, categoryBoardTable c
			WHERE a.abroad_subcat_id = c.boardId
			AND a.userid IN (?)
			AND a.ExtraFlag  = 'studyabroad'";
		
		$query = $this->dbHandle->query($sql, array($userIds));
		$data = array();
		foreach($query->result() as $row) {
                    $data[$row->userid] = (array)$row;
		}
		return $data;
        }

	public function getUserDesiredCountryDetails($userIds)
	{
		$this->initiateModel('read','User');
		$sql = "SELECT b.name as country , a.userid
			FROM tUserLocationPref a, countryTable b
			WHERE a.countryid = b.countryid
			AND a.userid in (?)
			ORDER BY a.prefid ASC";
		$query = $this->dbHandle->query($sql, array($userIds));
		$data = array();
		foreach($query->result() as $row) {
			$data[$row->userid] = (array)$row;
		}
		return $data;
	}

	public function getUserDesiredCategoryDetails($userIds)
	{
		$this->initiateModel('read','User');
		$sql = "SELECT a.userid, c.name as category
			FROM tUserPref a, tCourseSpecializationMapping b, categoryBoardTable c
			WHERE a.desiredcourse = b.specializationid
			AND c.boardid = b.categoryid
			AND a.userid IN (?)
			AND a.DesiredCourse > 0 AND (a.ExtraFlag IS NULL OR a.ExtraFlag = '' OR a.ExtraFlag = 'studyabroad')
			
			UNION
			
			SELECT a.userid, d.blogTitle as category
			FROM tUserPref a, tUserPref_testprep_mapping b,blogTable c, blogTable d
			WHERE a.PrefId = b.prefid
			AND b.blogid = c.blogId
			AND c.status = 'live'
			and c.parentId = d.blogId
			AND a.userid IN (?)
			AND a.DesiredCourse = 0 AND a.ExtraFlag = 'testprep'";
		
		$query = $this->dbHandle->query($sql, array($userIds ,$userIds));
		$data = array();
		foreach($query->result() as $row) {
			$data[$row->userid] = (array)$row;
		}
		return $data;
	}

        public function getUserDesiredCategoryDetailsSA($userIds)
	{
		$this->initiateModel('read','User');
		$sql = "SELECT a.userid, c.name as category, a.ExtraFlag, b.CourseName 
			FROM tUserPref a, tCourseSpecializationMapping b, categoryBoardTable c
			WHERE a.desiredcourse = b.specializationid
			AND c.boardid = b.categoryid
			AND a.userid IN (?)
			AND a.DesiredCourse > 0 AND a.ExtraFlag = 'studyabroad'";
		
		$query = $this->dbHandle->query($sql, array($userIds));
		$data = array();
		foreach($query->result() as $row) {
                    if($row->ExtraFlag == 'studyabroad' && $row->category == 'All'){
                        if($row->CourseName == 'MBA')
                            $row->category = 'Business';
                        elseif($row->CourseName == 'MS')
                            $row->category = 'Engineering';
                        elseif($row->CourseName == 'BE/Btech')
                            $row->category = 'Engineering';
                    }
                    $data[$row->userid] = (array)$row;
		}
		return $data;
	}


	public function getUserSearchCriteria($userSearchCriteriaId)
	{
		$this->initiateModel('read');
		$sql = "select criteriaJSON as criteria, criteriaType as criteriaType from userSearchCriteria where id = ?";
		$query = $this->dbHandle->query($sql, array($userSearchCriteriaId));
		foreach($query->result() as $row) {
			$data['criteria'] = $row->criteria;
			$data['criteriaType'] = $row->criteriaType;
		}
		return $data;
	}

	public function getAllUserSearchCriteria($userid, $groupId, $adminType)
	{
		$data = array();
		$this->initiateModel('read');
		$sql = "select id, name from userSearchCriteria where status = 'live'";
		$searchCriteriaId = '';

		if($adminType == 'group_admin') {
            $sql .= " and group_id = ?";
            $searchCriteriaId = $groupId;
        } else if ($adminType == 'normal_admin') {
            $sql .= " and user_id = ?";
            $searchCriteriaId = $userid;
        }
        $sql .= " order by createdTime desc limit 500";
		$query = $this->dbHandle->query($sql,array($searchCriteriaId));
		foreach($query->result() as $row) {
			$data[$row->id] = $row->name;
		}
		return $data;
	}

	public function saveUserSearchCriteria($name, $criteriaJSON, $criteriaType, $userid, $groupId)
	{
		$this->initiateModel('write');
		$name = mysql_escape_string($name);
		$criteriaJSON = mysql_escape_string($criteriaJSON);
		
		$queryCmd = "insert into userSearchCriteria (`id` , `name` , `criteriaJSON` ,`criteriaType`,`createdTime`,`user_id`,`group_id` ) values ('', ?,'$criteriaJSON',".$this->dbHandle->escape($criteriaType).",'".date('Y-m-d H:i:s')."',?,?)";
		error_log($queryCmd);
		$this->dbHandle->query($queryCmd, array($name, $userid, $groupId));
		return $this->dbHandle->insert_id();
	}

	public function getHugeSetUserIds($number)
	{
		$this->initiateModel('read','User');
		$sql = "select userid from tuser order by userid desc limit ".$number;
		$query = $this->dbHandle->query($sql);
		foreach($query->result() as $row) {
			$data[] = $row->userid;
		}
		return $data;
	}

	public function getUserSets($userid, $groupId, $adminType)
	{
		$data = array();
		$this->initiateModel('read');
		$sql = "select * from userSearchCriteria where status = 'live'";
		if($adminType == 'group_admin') {
            $sql .= " and group_id = ".$this->dbHandle->escape($groupId);
        } else if ($adminType == 'normal_admin') {
            $sql .= " and user_id = ".$this->dbHandle->escape($userid);
        }
        $sql .= " ORDER BY createdTime DESC limit 500";
		$query = $this->dbHandle->query($sql);
		return $query->result_array();
	}

	// For getting data for articles widget
	public function getUsersLastInstituteIds($userIds, $params)
	{
		$this->initiateModel('read','User');
		
		$exclude_users = array();
		global $highPriorityActions;
		
		$sql = "SELECT userId, instituteId, courseId, listing_subscription_type
			FROM latestUserResponseData
			WHERE userId in (".$userIds.")
			AND action IN (\"".implode('","', $highPriorityActions)."\")";
		
		$query = $this->dbHandle->query($sql);
		$data = array();
		foreach($query->result_array() as $row) {
			if($row['instituteId']) {
				$data[$row['userId']]['institute_id'] = $row['instituteId'];
				$data[$row['userId']]['course_id'] = $row['courseId'];
				$data[$row['userId']]['subscription_type'] = $row['listing_subscription_type'];
				$exclude_users[] = $row['userId'];
			}
		}
		
		if(count($exclude_users)) {
			$userIds = implode(',', array_diff(explode(',', $userIds), $exclude_users));
		}
		
		if(strlen($userIds)) {
			$sql = "SELECT userId, instituteId, courseId, listing_subscription_type
				FROM latestUserResponseData
				WHERE userId in (".$userIds.")
				AND action NOT IN (\"".implode('","', $highPriorityActions)."\")";
			
			$query = $this->dbHandle->query($sql);
			
			foreach($query->result_array() as $row) {
				if($row['instituteId']) {
					$data[$row['userId']]['institute_id'] = $row['instituteId'];
					$data[$row['userId']]['course_id'] = $row['courseId'];
					$data[$row['userId']]['subscription_type'] = $row['listing_subscription_type'];
				}
			}
		}
		
		return $data;
	}

	// For getting data for alumni speak widget
	public function getDesiredCategoryIds($userIds, $params)
	{
		mail("teamldb@shiksha.com", "function - getDesiredCategoryIds called", "/var/www/html/shiksha/application/modules/MMM/mailer/models/mailermodel.php");
		$this->initiateModel('read','User');
		$sql = "SELECT distinct a.userid, c.categoryId as subcategoryId , b.categoryId  FROM tUserPref a, tCourseSpecializationMapping b , LDBCoursesToSubcategoryMapping c where a.desiredcourse = c.ldbcourseid and a.desiredcourse = b.specializationid  and a.userid in (".$userIds.")  and a.status = 'live' and b.status = 'live' having subcategoryId > 0
		UNION
		SELECT a.userid,  c.boardId AS subcategoryId,d.parentId as categoryId
		FROM tUserPref a, tUserPref_testprep_mapping b,blogTable c, categoryBoardTable d
		WHERE a.PrefId = b.prefid
		AND b.blogid = c.blogId
		and c.boardId = d.boardId
		AND c.status = 'live'
		AND a.userid IN (".$userIds.")
		AND a.DesiredCourse = 0 AND a.ExtraFlag = 'testprep' ";
		
		
		$query = $this->dbHandle->query($sql);
		$data = array();
		error_log("here in model".$sql);
		foreach($query->result() as $row) {
			$data[$row->userid] = (array)$row;
		}
		return $data;
	}

	// For getting data for alumni speak widget
	public function getDesiredCountryRegionIds($userIds, $params)
	{
		mail("teamldb@shiksha.com", "function - getDesiredCountryRegionIds called", "/var/www/html/shiksha/application/modules/MMM/mailer/models/mailermodel.php");
		$this->initiateModel('read','User');
		$sql = "SELECT a.userid, a.countryid, if( b.sno IS NULL , 0, b.regionid ) as regionid FROM `tUserLocationPref` a LEFT JOIN `tregionmapping` b ON b.id = a.countryid WHERE `UserId` IN (".$userIds.") AND a.status = 'live' order by userid asc";
		$query = $this->dbHandle->query($sql);
		$data = array();
		foreach($query->result() as $row) {
			$data[$row->userid] = (array)$row;
		}
		return $data;
	}

	// For getting data for complete profile widget
	public function getUsersWithIncompleteProfile($userIds, $params)
	{
		$this->initiateModel('read','User');
		
		$data = array();
		if(is_array($userIds) && count($userIds) > 0) {
			$sql = "SELECT DISTINCT UserId AS userid
					FROM tUserPref
					WHERE UserId IN (".implode(',',$userIds).")
					AND (DesiredCourse IS NULL OR DesiredCourse = 0)
					AND (ExtraFlag IS NULL OR ExtraFlag != 'testprep')
					UNION
					SELECT a.userid
					FROM tuser a
					LEFT JOIN tUserPref b ON a.userid = b.userid
					WHERE b.prefid IS NULL
					AND a.userid IN (".implode(',',$userIds).")";
			
			$query = $this->dbHandle->query($sql);
			foreach($query->result_array() as $row) {
				$data[$row['userid']] = $row['userid'];
			}
		}
		return $data;
	}

	public function updateUserMailerSentTrigger($userIds, $mailerId)
	{
		if(empty($userIds)) {
			return true;
		}
		$this->initiateModel('write');
		
		$sql = "INSERT INTO userMailerSentCount (`userId`,`mailerId`,`count`,`resetOn`) VALUES ";
		foreach($userIds as $userId){
			$sql .= "(".$this->dbHandle->escape($userId).",".$this->dbHandle->escape($mailerId).",'1',NOW()),";
		}
		$sql = substr($sql,0,-1);
		$sql .= " ON DUPLICATE KEY UPDATE `count` = `count`+1";
		error_log("updateUserMailerSentTrigger ".$sql);
		$this->dbHandle->query($sql);
	}

	public function resetUserMailerSentTrigger($userId, $mailerIds)
	{
		if(empty($mailerIds)) {
			return true;
		}
		$this->initiateModel('write');
		
		$sql = "update userMailerSentCount set `count` = 0 ,`resetOn` = now() where `userId` = ? and `mailerId` in (".implode(',',$mailerIds).")";
		error_log("resetUserMailerSentTrigger ".$sql);
		$this->dbHandle->query($sql, array($userId));
	}

	public function getUserMailerSentTrigger($params = array(), $extraParams = array())
	{
		$this->initiateModel('read');
		$users = array();
		$clauses = array();
		
		if($extraParams['boundrySet'] && count($extraParams['boundrySet']) > 0) {
			$clauses[] = "userId IN (".implode(',',$extraParams['boundrySet']).")";
		}
		
		if($params['mailerId']) {
			$clauses[] = "mailerId = ".$this->dbHandle->escape($params['mailerId']);
		}
		
		if(!empty($params['countType']) && $params['count'] && intval($params['count'])) {
			if($params['countType'] == 'days') {
				$clauses[] = "DATEDIFF(NOW(),resetOn) > ".$this->dbHandle->escape(intval($params['count']));
			}
			else {
				$clauses[] = "count >= ".$this->dbHandle->escape(intval($params['count']));
			}
			
			$sql =  "SELECT userid ".
					"FROM userMailerSentCount ".(count($clauses) > 0 ? "WHERE ".implode(' AND ',$clauses) : "WHERE 1");
			
			error_log("userMailerSentCount ".$sql);
			
			$query = $this->dbHandle->query($sql);
			
			while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM))
			{
				$users[$result[0]] = TRUE;
			}
		}
		return $users;
	}

	public function getUsersForMailerSent($params = array(), $extraParams = array())
	{
		mail("teamldb@shiksha.com", "function - getUsersForMailerSent called", "/var/www/html/shiksha/application/modules/MMM/mailer/models/mailermodel.php");

		$this->initiateModel('read');
		
		$clauses = array();
		$temp_table_clauses = array("widget IN ('mustread', 'article')");
		
		if($extraParams['boundrySet'] && count($extraParams['boundrySet']) > 0) {
			$clauses[] = "mq.userid IN (".implode(',',$extraParams['boundrySet']).")";
			$temp_table_clauses[] = "userId IN (".implode(',',$extraParams['boundrySet']).")";
		}
		
		if($params['mailerId']) {
			$clauses[] = "mq.mailerid = ".$this->dbHandle->escape($params['mailerId']);
			$temp_table_clauses[] = "mailerId = ".$this->dbHandle->escape($params['mailerId']);
		}
		
		if($params['count'] && intval($params['count'])) {
			$groupby = " GROUP BY mq.userid HAVING cn >= ".intval($params['count']);
		}
		else {
			$groupby = "";
		}
		
		if(empty($params['after_last_action'])) {
			$sql =  "SELECT mq.userid, count(*) as cn ".
					"FROM mailQueue mq ".(count($clauses) > 0 ? "WHERE ".implode(' AND ',$clauses) : "WHERE 1").$groupby;
		}
		else {
			$clauses[] = "(mm.date is NULL OR mq.sendtime > mm.date)";
			$temp_table = "(SELECT userId, Max( date ) as date FROM mailerMis WHERE ".implode(' AND ',$temp_table_clauses)." GROUP BY userId)";
			$sql =  "SELECT mq.userid, count(*) as cn ".
					"FROM mailQueue mq LEFT JOIN ".$temp_table." as mm ".
					"ON mq.userid = mm.userId ".(count($clauses) > 0 ? "WHERE ".implode(' AND ',$clauses) : "WHERE 1").$groupby;
		}
		
		error_log("MailQueueUsers ".$sql);
		
		$query = $this->dbHandle->query($sql);
		$users = array();
		while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM))
		{
			$users[$result[0]] = TRUE;
		}
		return $users;
	}

	public function getMailerSentforUser($params = array())
	{
		$this->initiateModel('read');
		
		$clauses = array();
		
		if($params['boundrySet'] && count($params['boundrySet']) > 0) {
			$clauses[] = "mq.userid IN (".implode(',',$params['boundrySet']).")";
		}
		
		if($params['mailerId']) {
			$clauses[] = "mq.mailerid = ".$this->dbHandle->escape($params['mailerId']);
		}
		
		$sql =  "SELECT mq.userid, count(*) as cn ".
				"FROM mailQueue mq ".(count($clauses) > 0 ? "WHERE ".implode(' AND ',$clauses) : "WHERE 1")." GROUP BY mq.userid";
		
		error_log("MailerCount ".$sql);
		
		$query = $this->dbHandle->query($sql);
		$users = array();
		while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM))
		{
			$users[$result[0]] = $result[1];
		}
		return $users;
	}

	private function _getContentTableName(){
		return "mailQueueContent_".date('Y_m');
	}

	public function createArticleMailerTagging($articleId,$mailerIds,$status='live')
	{
		$this->initiateModel('write');
		foreach($mailerIds as $mailerId) {
			$data = array(
				'articleId' => $articleId,
				'mailerId' => $mailerId,
				'status' => $status
			);
			$this->dbHandle->insert('mailer_article_mapping',$data);
		}
	}

	public function updateArticleMailerTagging($articleId,$mailerIds,$status='live')
	{
		$this->initiateModel('write');
		
		$query = "UPDATE mailer_article_mapping SET status = 'history' WHERE articleId = ?";
		$this->dbHandle->query($query,array($articleId));
		
		$this->createArticleMailerTagging($articleId,$mailerIds,$status);
	}

	public function getMailersTaggedToArticle($articleId,$status='live')
	{
		$this->initiateModel('read');
		$sql = "SELECT mailerId FROM mailer_article_mapping WHERE articleId = ? AND status = ?";
		$query = $this->dbHandle->query($sql,array($articleId,$status));
		$taggedMailers = $this->getColumnArray($query->result_array(),'mailerId');
		return $taggedMailers;
	}

	public function getTaggedArticles($mailerId)
	{
		$this->initiateModel('read');
		$sql = "SELECT articleId FROM mailer_article_mapping WHERE mailerId = ? AND status = 'live'";
		$query = $this->dbHandle->query($sql,array($mailerId));
		$taggedArticleIds = $this->getColumnArray($query->result_array(),'articleId');
		$taggedArticles = array();
		if(count($taggedArticleIds) > 0) {
			$this->load->model('blogs/articlemodel');
			$taggedArticles = $this->articlemodel->getArticlesData($taggedArticleIds);
		}
		return $taggedArticles;
	}

	public function storeMailedArticles($userArticleMap,$mailerId,$widgetKey)
	{
		$this->initiateModel('write');
		$sql = "INSERT INTO mailed_articles (userId,articleId,categoryId,mailerId,date,type) VALUES ";
		$insertValues = array();
		foreach($userArticleMap as $userId => $articles) {
			foreach($articles as $article) {
				$insertValues[] = "(".$this->dbHandle->escape($userId).",".$this->dbHandle->escape($article['blogId']).",".$this->dbHandle->escape($article['categoryId']).",".$this->dbHandle->escape($mailerId).",".$this->dbHandle->escape(date('Y-m-d H:i:s')).",".$this->dbHandle->escape($widgetKey).")";
			}
		}
		if(count($insertValues) > 0) {
			$sql .= implode(',',$insertValues);
			$this->dbHandle->query($sql);
		}
	}

	public function getMailedArticles($userId,$categoryId,$type,$mailerId)
	{
		$this->initiateModel('read');
		
		$sql = "SELECT DISTINCT articleId FROM mailed_articles WHERE userId = ? ".
				($categoryId ? " AND categoryId = ".$this->dbHandle->escape($categoryId)." " : "").
				($type ? " AND type = ".$this->dbHandle->escape($type)." " : "").
				($mailerId ? " AND mailerId = ".$this->dbHandle->escape($mailerId)." " : "");
		$query = $this->dbHandle->query($sql, array($userId));
		return $this->getColumnArray($query->result_array(),'articleId');
	}

	public function getArticlesByRotation($userId,$categoryId,$num)
	{
		$this->initiateModel('read');
		
		$sql =  "SELECT articleId,max(id) FROM mailed_articles WHERE userId = ? AND categoryId = ? AND type = 'mustread' ".
				"GROUP BY articleId ORDER BY max(id) LIMIT $num";
				
		$query = $this->dbHandle->query($sql, array($userId, $categoryId));
		return $this->getColumnArray($query->result_array(),'articleId');
	}

	public function getArticleExclusionList($userIds)
	{
		mail("teamldb@shiksha.com", "function - getArticleExclusionList called", "/var/www/html/shiksha/application/modules/MMM/mailer/models/mailermodel.php");
		
		$this->initiateModel('read');
		$sql = "SELECT userId,articleId FROM mailed_articles WHERE userId IN ($userIds)";
		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();
		$articleExclusionList = array();
		foreach($results as $result) {
			$articleExclusionList[$result['userId']][$result['articleId']] = TRUE;
		}
		return $articleExclusionList;
	}

	public function getUsersLastInstituteAndCourse($userIds)
	{
		$this->initiateModel('read', 'User');
		$exclude_users = array();
		global $highPriorityActions;
		
		$sql = "SELECT latestUserResponseData.userId, latestUserResponseData.courseId, course_details.courseTitle, institute.institute_name, university.name, countryTable.name as country
			FROM latestUserResponseData
			LEFT JOIN course_details ON latestUserResponseData.courseId = course_details.course_id AND course_details.status = 'live'
			LEFT JOIN institute ON latestUserResponseData.instituteId = institute.institute_id AND institute.status = 'live'
			LEFT JOIN university ON latestUserResponseData.universityId =  university.university_id AND university.status = 'live'
			INNER JOIN countryTable ON latestUserResponseData.countryId = countryTable.countryId
			WHERE userId in (".$userIds.")
			AND action IN (\"".implode('","', $highPriorityActions)."\")";
		
		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();
		$returnArr = array();
		foreach($results as $result) {
			$returnArr[$result['userId']]['courseName'] = $result['courseTitle'];
			$returnArr[$result['userId']]['instituteName'] = $result['institute_name'];
			$returnArr[$result['userId']]['universityName'] = $result['name'];
			$returnArr[$result['userId']]['countryName'] = $result['country'];
			$returnArr[$result['userId']]['course_id'] = $result['courseId'];
			$exclude_users[] = $result['userId'];
		}
		
		if(count($exclude_users)) {
			$userIds = implode(',', array_diff(explode(',', $userIds), $exclude_users));
		}
		
		if(strlen($userIds)) {
			$sql = "SELECT latestUserResponseData.userId, latestUserResponseData.courseId, course_details.courseTitle, institute.institute_name, university.name, countryTable.name as country
				FROM latestUserResponseData
				LEFT JOIN course_details ON latestUserResponseData.courseId = course_details.course_id AND course_details.status = 'live'
				LEFT JOIN institute ON latestUserResponseData.instituteId = institute.institute_id AND institute.status = 'live'
				LEFT JOIN university ON latestUserResponseData.universityId =  university.university_id AND university.status = 'live'
				INNER JOIN countryTable ON latestUserResponseData.countryId = countryTable.countryId
				WHERE userId in (".$userIds.")
				AND action NOT IN (\"".implode('","', $highPriorityActions)."\")";
			$query = $this->dbHandle->query($sql);
			$results = $query->result_array();
			
			foreach($results as $result) {
				$returnArr[$result['userId']]['courseName'] = $result['courseTitle'];
				$returnArr[$result['userId']]['instituteName'] = $result['institute_name'];
				$returnArr[$result['userId']]['universityName'] = $result['name'];
				$returnArr[$result['userId']]['countryName'] = $result['country'];
				$returnArr[$result['userId']]['course_id'] = $result['courseId'];
			}
		}
		
		return $returnArr;
	}

	public function getUsersLastTwoInstituteAndCourse($userIds,$last_mail_time_window)
	{
		mail("teamldb@shiksha.com", "function - getUsersLastTwoInstituteAndCourse called", "/var/www/html/shiksha/application/modules/MMM/mailer/models/mailermodel.php");

		$this->initiateModel('read', 'User');
		$recommendedDelay = '72 hours';
		$time_window_start = date('Y-m-d H:i:s',strtotime($recommendedDelay,strtotime($last_mail_time_window)));
		
		$sql = "SELECT a.id, a.userid, if( a.listing_type = 'course', c.courseTitle , 'NA' ) AS courseName, if(a.listing_type = 'course', e.listing_title, d.listing_title) AS instituteName ,c.course_id as course,c.institute_id as institute ".
				"FROM tempLMSTable a LEFT JOIN course_details c ON a.listing_type_id = c.course_id AND a.listing_type = 'course' ".
				"left join listings_main d on a.listing_type_id = d.listing_type_id and a.listing_type = 'institute' ".
				"left join listings_main e on e.listing_type_id = c.institute_id and e.listing_type = 'institute' ".
				"WHERE a.listing_subscription_type='paid' and a.userid in (".$userIds.") and a.submit_date <= '$time_window_start' and if( a.listing_type = 'course', c.status = 'live', d.status = 'live') ".
				"group by a.id order by a.id desc";
		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();
		$returnArr = array();
		$i = 0;
		foreach($results as $result) {
			if(count($returnArr[$result['userid']]) < 2){
				$returnArr[$result['userid']][$i]['courseName'] = $result['courseName'];
				$returnArr[$result['userid']][$i]['instituteName'] = $result['instituteName'];
				$returnArr[$result['userid']][$i]['instituteId'] = $result['institute'];
				$returnArr[$result['userid']][$i]['courseId'] = $result['course'];
				$i++;
			}
		}
		return $returnArr;
	}

	public function getUsersDesiredEducation($userIds)
	{
		$this->initiateModel('read','User');
		$sql = "select up.userid,cct.city_name, ModeOfEducationFullTime,ModeOfEducationPartTime,ModeOfEducationDistance, tcsm.coursename, tcsm.specializationid from tUserPref up left join tUserLocationPref tlpf  on up.userid=tlpf.userid and tlpf.status = 'live' and tlpf.countryid = 2 left join countryCityTable cct on cct.city_id = tlpf.cityid, tCourseSpecializationMapping tcsm where up.desiredcourse = tcsm.specializationid and up.userid in (".$userIds.")";
		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();
		$returnArr = array();
		foreach($results as $result) {
			if($result['ModeOfEducationFullTime'] == 'yes')
				$mode = "Full Time";
			elseif($result['ModeOfEducationPartTime'] == 'yes')
				$mode = "Part Time";
			elseif($result['ModeOfEducationDistance'] == 'yes')
				$mode = "Distance";
			else
				$mode = "NA";
			$returnArr[$result['userid']]['mode'] = $mode;
			$returnArr[$result['userid']]['coursename'] = $result['coursename'];
			$returnArr[$result['userid']]['city'] = $result['city_name'];
			$returnArr[$result['userid']]['specializationid'] = $result['specializationid'];
		}
		return $returnArr;
	}

	public function getSAUsersCategoryAndPrefLocation($userIds)
	{
		$this->initiateModel('read','User');
		$sql = "select up.userid,cbt.name,group_concat(ct.`name`) as countries from tUserPref up, tCourseSpecializationMapping tcsm, categoryBoardTable cbt, tUserLocationPref ulp, countryTable ct where up.prefid = ulp.prefid and up.status = 'live' and ulp.status = 'live' and up.extraflag = 'studyabroad' and tcsm.status = 'live' and up.desiredcourse = tcsm.specializationid and tcsm.CategoryId = cbt.boardId and tcsm.scope = 'abroad' and ct.countryId = ulp.countryId and up.userid in (".$userIds.") group by up.userid ";
		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();
		$returnArr = array();
		foreach($results as $result) {
			$returnArr[$result['userid']]['category'] = $result['name'];
			$returnArr[$result['userid']]['countries'] = $result['countries'];
		}
		return $returnArr;
	}

	/**
	 * All responses made by users in last 6 months at $frequency interval
	 * e.g. if $time_window_end is 2013-07-31 16:30:00 and $frequency is 7
	 * Then responses made by users in following time intervals
	 * 2013-07-30 16:30:00 - 2013-07-31 16:30:00
	 * 2013-07-23 16:30:00 - 2013-07-24 16:30:00
	 * 2013-07-16 16:30:00 - 2013-07-17 16:30:00
	 * 2013-07-09 16:30:00 - 2013-07-10 16:30:00
	 * ...so on upto last 6 months
	 */
	public function getTotalResponseForUserId($userIds,$time_window_end,$frequency)
	{
		$this->initiateModel('read','User');
		
		if(empty($frequency)) {
			$frequency = 7;
		}
		
		$startTime = date("Y-m-d H:i:s",strtotime('-6 months',strtotime($time_window_end)));
		
		$sql =  "SELECT userId,listing_type_id from tempLMSTable
				WHERE listing_type = 'course'
                                AND listing_subscription_type='paid' 
				AND userId in (".$userIds.")
				AND MOD(TIMESTAMPDIFF(DAY,submit_date,".$this->dbHandle->escape($time_window_end)."),$frequency) = 0
				AND submit_date > ".$this->dbHandle->escape($startTime)."
				AND submit_date <= ".$this->dbHandle->escape($time_window_end)." ";
		
		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();
		
		$returnArr = array();
		foreach($results as $result) {
			$returnArr[$result['userId']][] = $result['listing_type_id'];
		}
		return $returnArr;
	}

	public function getInstituteIdsForCourseId($userIds)
	{
		mail("teamldb@shiksha.com", "function - getInstituteIdsForCourseId called", "/var/www/html/shiksha/application/modules/MMM/mailer/models/mailermodel.php");
		$this->initiateModel('read','User');
		
		$sql = "SELECT userId,listing_type_id from tempLMSTable where lsting_subscription_type='paid' and listing_type = 'course' AND userId in (".$userIds.") ";
		
		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();
		
		$returnArr = array();
		foreach($results as $result) {
			$returnArr[$result['userId']][] = $result['listing_type_id'];
		}
		return $returnArr;
	}

	function recordMailerReportSpam($userId,$mailerId,$mailId,$reportSpamReasons = array())
	{
		$this->initiateModel('write');
		$data = array(
						'userId'=> $userId,
						'mailerId'=> $mailerId,
						'mailId'=> $mailId,
						'time'=> date('Y-m-d H:i:s')
					);
					
		$this->dbHandle->insert('mailerReportSpam',$data);
		$reportSpamId = $this->dbHandle->insert_id();
		foreach($reportSpamReasons as $reportSpamReasonId) {
			$reasonData = array(
							'reportSpamId'=>$reportSpamId,
							'reasonId'=> $reportSpamReasonId
						);
						
			$this->dbHandle->insert('mailerReportSpamReason',$reasonData);
		}
		return true;
	}

	function getMailerReportSpamReasons($mailerId)
	{
		$this->initiateModel('read');
		
		$sql =  "SELECT id, reason FROM mailerReportSpamReasonMaster WHERE mailerId = 0 or mailerId = ?";
		
		$query = $this->dbHandle->query($sql, array($mailerId));
		$data = array();
		while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM))
		{
			$data[$result[0]] = $result[1];
		}
		return $data;
	}

	public function getUsersWhoReportedSpam($params = array(), $extraParams = array())
	{
		$this->initiateModel('read');
		
		$clauses = array();
		
		if($extraParams['boundrySet'] && count($extraParams['boundrySet']) > 0) {
			$clauses[] = "userId IN (".implode(',',$extraParams['boundrySet']).")";
		}
		
		if($params['mailerId']) {
			$clauses[] = "mailerId = ".$this->dbHandle->escape($params['mailerId']);
		}
		
		$sql =  "SELECT userId FROM mailerReportSpam ".(count($clauses) > 0 ? "WHERE ".implode(' AND ',$clauses) : "WHERE 1");
		
		error_log("mailerReportSpam ".$sql);
		
		$query = $this->dbHandle->query($sql);
		$users = array();
		while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM))
		{
			$users[$result[0]] = TRUE;
		}
		return $users;
	}

	public function getCoursesToCompare($userIds,$time)
	{
		$this->initiateModel('read','User');
		
		$startTime = date("Y-m-d H:i:00",strtotime('-33 days',strtotime($time)));
		$endTime = date("Y-m-d H:i:00",strtotime('-3 days',strtotime($time)));
		
		$sql =  "SELECT t.userId,t.listing_type_id,cbt.parentId ".
				"FROM tempLMSTable t ".
				"INNER JOIN listings_main lm ON (lm.listing_type = t.listing_type AND lm.listing_type_id = t.listing_type_id) ".
				"INNER JOIN listing_category_table lct ON (lct.listing_type_id = t.listing_type_id and lct.listing_type = t.listing_type and lct.status = 'live') ".
				"INNER JOIN categoryBoardTable cbt ON cbt.boardId = lct.category_id ".
				"WHERE t.userId IN (".implode(',',$userIds).") ".
                                "AND t.listing_subscription_type='paid' ".
				"AND t.submit_date > ".$this->dbHandle->escape($startTime)." ".
				"AND t.submit_date <= ".$this->dbHandle->escape($endTime)." ".
				"AND t.listing_type = 'course' ".
				"AND lm.status = 'live' ".
				"ORDER BY t.userId ASC,t.submit_date DESC";
				
		$query = $this->dbHandle->query($sql);
		
		$coursesToCompare = array();
		
		foreach($query->result_array() as $row) {
			
			if(!$coursesToCompare[$row['userId']]) {
				$coursesToCompare[$row['userId']][] = $row['listing_type_id'];
				$category = $row['parentId'];
			}
			else if(count($coursesToCompare[$row['userId']]) == 1 && $coursesToCompare[$row['userId']][0] != $row['listing_type_id'] && $category == $row['parentId']) {
				$coursesToCompare[$row['userId']][] = $row['listing_type_id'];
			}
		}
		
		return $coursesToCompare;
	}
	
	public function hasAccessToMailer($userId,$mailId)
	{
		$this->initiateModel('read');
		$sql = "SELECT mailid FROM mailQueue WHERE userid = ? AND mailid = ".$this->dbHandle->escape($mailId);
		$query = $this->dbHandle->query($sql, array($userId));
		$result = $query->row_array();
		if($result['mailid']) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	public function getMailContent($mailId,$date)
	{
		$this->initiateModel('read');
		
		$year = substr($date,0,4);
		$month = substr($date,4,2);
		
		$mailContentTable = 'mailQueueContent_'.$year.'_'.$month;
		
		$sql = "SHOW TABLES LIKE '".$mailContentTable."'";
		$query = $this->dbHandle->query($sql);
		$result = $query->row_array();
		if(is_array($result) && count($result) > 0) {
			$sql = 'SELECT UNCOMPRESS(content) as content FROM '.$mailContentTable.' WHERE mailid = '.$this->dbHandle->escape($mailId);
			$query = $this->dbHandle->query($sql);
			$result = $query->row_array();
			return $result['content'];
		}
		else {
			return FALSE;
		}
	}

	/*Function to get RecomendationMailerData*/
	function getRecomendationMailerData(){
		$this->initiateModel('read');
		$sql = "select mailid from recomendationLastSMS";
		$query = $this->dbHandle->query($sql);
		$resultMailerId = $query->result_array();
		$finalArr = array();
		$mailId = '';
		if(!empty($resultMailerId)){
			$mailId = " and mq.mailid >".$this->dbHandle->escape($resultMailerId[0]['mailid']);
		}
                $sql = "select * from mailQueue mq where mq.issent='yes' and mq.mailerid  in ('4406','4407') $mailId order by mq.mailid asc";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		$i=0;
		if(is_array($result) && count($result) > 0) {
			foreach($result as $row) {
				$finalArr[$i]['mailid'] = $row['mailid'];
				$finalArr[$i]['userid'] = $row['userid'];
				$finalArr[$i]['toemail'] = $row['toemail'];
				$finalArr[$i]['createdtime'] = $row['createdtime'];
				$i++;
			}
			return $finalArr;
		}else{
			return $finalArr;
		}
	}
	/*Function to store Last SMS Mail Id for Recomendation Mailer*/
	public function storeLastMailIdForSMS($mailid){
		$this->initiateModel('write');
		$sql ="INSERT INTO recomendationLastSMS (id,mailid) VALUES (1,".$this->dbHandle->escape($mailid).") ON DUPLICATE KEY UPDATE mailid=".$this->dbHandle->escape($mailid);
		$this->dbHandle->query($sql);
	}

	public function getUserDesiredCityDetails($userIds)
	{
		mail("naveen.bhola@shiksha.com", "Not in used function called.", "Function : getUserDesiredCityDetails");
        return;
		$this->initiateModel('read','User');
		$sql = "SELECT b.city_name as city , a.userid FROM tUserLocationPref a, countryCityTable b WHERE a.CityId = b.city_id AND a.userid in (".$userIds.") ORDER BY a.prefid ASC";
		$query = $this->dbHandle->query($sql);
		$data = array();
		foreach($query->result() as $row) {
			$data[$row->userid] = (array)$row;
		}
		return $data;
	}
	
	public function getNewsletterParams($templateId)
	{
		$this->initiateModel('read');
		if($templateId == NULL) {
			$templateId = '3148';
		}
		$sql = "SELECT templateId,articleIds,discussionIds,eventIds,include_MPT_tuple FROM newsletterParams WHERE status = 'live' AND templateId = ?"; 
		$query = $this->dbHandle->query($sql, array($templateId));
		return $query->row_array();
	}
	
	public function saveNewsletterParams($templateId,$articleIDs,$discussionIDs,$eventIDs,$include_MPT_tuple)
	{
		$this->initiateModel('write');
		
		$sql = "UPDATE newsletterParams SET status = 'history' WHERE templateId = ?";
		$this->dbHandle->query($sql, array($templateId));
		
		$data = array(
			'templateId' => $templateId,
			'articleIds' => $articleIDs,
			'discussionIds' => $discussionIDs,
			'eventIds' => $eventIDs,
			'status' => 'live',
			'include_MPT_tuple' => $include_MPT_tuple,
			'date' => date('Y-m-d H:i:s')
		);
		$this->dbHandle->insert('newsletterParams',$data);
	}
	
	public function getCourseLocations($courseId) {
		$courseLocations = array();
		
		if($courseId > 0) {
			$this->initiateModel('read','User');
			$sql = "SELECT city_id, city_name, locality_id, locality_name
				FROM course_location_attribute, institute_location_table
				WHERE course_location_attribute.institute_location_id = institute_location_table.institute_location_id
				AND course_location_attribute.course_id = ?
				AND course_location_attribute.attribute_type = 'Head Office'
				AND course_location_attribute.status = 'live'
				AND institute_location_table.status = 'live'
				ORDER BY city_name ASC, locality_name ASC";
			
			$result = $this->dbHandle->query($sql, array($courseId))->result_array();
			
			if(count($result)) {
				$numOfCities = 0;
				$numOfLocalities = 0;
				foreach($result as $row) {
					if($row['city_id'] > 0 && empty($courseLocations['cities'][$row['city_id']])) {
						$numOfCities++;
						$courseLocations['cities'][$row['city_id']] = $row['city_name'];
					}
					
					if($row['city_id'] > 0 && $row['locality_id'] > 0) {
						$numOfLocalities++;
						$courseLocations['localities'][$row['city_id']][$row['locality_id']] = $row['locality_name'];
					}
				}
				
				$courseLocations['numOfCities'] = $numOfCities;
				$courseLocations['numOfLocalities'] = $numOfLocalities;
			}
		}
		
		return $courseLocations;
	}
	
	public function getNewsletterMailsToBeSent($templateId) {
		$this->initiateModel('read');
		$sql = "SELECT totalMailsToBeSent - numberprocessed - 50 AS mailsToBeSent
			FROM mailer
			WHERE totalMailsToBeSent > numberprocessed
			AND time >= DATE_SUB(CURDATE(), INTERVAL 3 DAY)
			AND mailer.templateId = ? AND mailsSent IN ('false','inProgress','draft')";
		
		$result = $this->dbHandle->query($sql, array($templateId))->result_array();
		
		$mailsToBeSent = 0;
		if(count($result)) {
			foreach($result as $row) {
				$mailsToBeSent += $row['mailsToBeSent'];
			}
		}
		
		if($mailsToBeSent < 0) {
			$mailsToBeSent = 0;
		}
		
		return $mailsToBeSent;
	}

	/*
	* Insert new entries in smsCampaign with insert batch function of codeigniter
	* @params : $data=>array having all column values
	*/
	public function saveSMSCampaign($data){
		if(empty($data)){
			return;
		}
		$this->db->insert_batch('shiksha.smsCampaign',$data);
	}

	/*
	* Get Last Auto Increment Value of smsCampaign table
	*/
	public function getLastAutoIncrementValue(){
		// $this->initiateModel('read');
		$sql = 'SELECT AUTO_INCREMENT
				FROM information_schema.TABLES
				WHERE TABLE_SCHEMA = "shiksha"
				AND TABLE_NAME = "smsCampaign"';
		$result = $this->db->query($sql)->result();
		return $result[0]->AUTO_INCREMENT;
	}

	/*
	* Get all entries whose status = saved from smsCampaign
	*/
	public function getAllSavedSMSCampaign(){
		$sql = "select clientId, campaignName from shiksha.smsCampaign 
				where status='saved' 
				group by campaignName";
		return  $this->db->query($sql)->result_array();
	}

	/*
	* Get basic info for user from smsCampaign table
	* @params: $clientIds => array containing userIds
	*/
	public function getAllUserOfClientForCampaign($campaignName){ 
		mail("naveen.bhola@shiksha.com", "Not in used function called.", "Function : getAllUserOfClientForCampaign");
        return;
		$sql = "select mobile, userId, message from shiksha.smsCampaign 
				where status='saved' AND campaignName IN (".implode(', ',$campaignName).")";
				
		return $this->db->query($sql)->result_array();
	}


	/*
	* update status to sent and sentDate with timestamp of smsCampaign table. 
	* @params: $clientIds => array containing userIds
	*/
	public function startSMSCapaign($campaignName){
		mail("teamldb@shiksha.com", "function - startSMSCapaign called", "/var/www/html/shiksha/application/modules/MMM/mailer/models/mailermodel.php");

		$sql = "update shiksha.smsCampaign set status = 'sent', sentDate = now() 
				where status='saved' AND campaignName IN (".implode(', ',$campaignName).")";
				
		return $this->db->query($sql);
	}

	/*
	* Find Exam Name and Blog Id for user's who are registered test prep courses
	* @params: array containing user Ids
	*/

	public function getBlogAcronymForNonGrouped($userIds){
		$sql = "SELECT a.acronym, a.blogId
				FROM blogTable a
				LEFT JOIN tUserPref_testprep_mapping b ON ( a.blogid = b.blogid ) 
				LEFT JOIN tUserPref c ON ( b.prefid = c.PrefId ) 
				LEFT JOIN tuser d ON ( c.UserId = d.userid ) 
				WHERE d.userid IN (?) AND a.status = 'live' group by a.blogId";
		return $this->db->query($sql,array($userIds))->result_array();
	}
	
	public function SMSCampaignTracking($data){
		$this->initiateModel('write');
		$sql ="INSERT INTO shiksha.smsCampaignTracking (campaign_id, user_id, course_id) VALUES (?, ?, ?)";
		$this->db->query($sql, array($data['campaign_id'], $data['user_id'], $data['course_id']));
	}
	
	public function getSMSCampaignDataById($key)	{
		$this->initiateModel('read');
		$sql = "SELECT * FROM smsCampaign WHERE unique_key=?";
		$query = $this->db->query($sql, array($key));
		$result = $query->row_array();
		return $result;
	}

	/*
	* Find Course Name and specialization Id for user's who are registered test prep courses
	* @params: array containing user Ids
	*/

	public function getSpecializationDetails($userIds){
		$sql = "SELECT a.CourseName, a.SpecializationId from tCourseSpecializationMapping a
				left join tUserPref b on (a.SpecializationId = b.DesiredCourse)
				where b.UserId IN (?) group by a.SpecializationId";
		return $this->db->query($sql,array($userIds))->result_array();
	}

	/*
	* Update status to deleted in smsCampaign
	* @params: clientId whose status is to be updated
	*/
	public function deleteSMSCampaign($campaignName){
		$sql = "UPDATE smsCampaign set status = 'deleted' where campaignName = ? ";
		return $this->db->query($sql,$campaignName);
	}

	/*
	* Check that whether user in a group or not
	* @params: userId which we need to check for a Group
	*/
	public function isUserinGroup($userId){
		$this->initiateModel('read');
		$sql = "SELECT * FROM user_group_mapping where user_id = ? and status = ?";
		$query = $this->dbHandle->query($sql,array($userId, 'live'));

		$result = $query->row_array();
		return $result;
	}
	
	/*
	* Get Group Information of the User
	* @params: userId whose information to be fetched
	*/
	public function getUserGroupInfo($userId){
		$this->initiateModel('read');
		$sql = "SELECT * FROM user_group_mapping ugm inner join groups g on ugm.group_id = g.group_id where ugm.user_id = ? and g.status = ? and ugm.status = ?";
		$query = $this->dbHandle->query($sql,array($userId, 'live','live'));
		$result = $query->row_array();
		return $result;
	}

	/*
	* Get all the tabs of MMM
	*/
	public function getAllTabs(){
		$this->initiateModel('read');
		$sql = "SELECT * FROM tabs where status = ?";
		$query = $this->dbHandle->query($sql, array('live'));
		$data = array();
		foreach($query->result() as $row) {
			$data[$row->tab_id] = (array)$row;
		}
		return $data;
	}

	/*
	* Get Information of all the Admins
	*/
	public function getAllAdminData(){
		$this->initiateModel('read');
		$sql = "SELECT user_id FROM user_group_mapping where status = ? group by user_id";
		$query = $this->dbHandle->query($sql, array('live'));
		$data = array();$user_ids =array();
		foreach($query->result() as $row) {
			$user_ids[] = $row->user_id;
		}
		if(!empty($user_ids)) {
			//$all_user_ids = implode(",", $user_ids);
			//$data = $this->getUserBasicDetails($all_user_ids);
			$data = $this->getUserBasicDetails($user_ids);
		}
		return $data;
	}

	public function isValidEmailHardbouceCheck($email){
		$this->initiateModel('read');
		$sql = "SELECT count(*) as count from UserBounceData where email_id = ?";
		$query = $this->dbHandle->query($sql, array($email));
		$row = $query->row();
		
		$count = $row->count;
		
		if($count > 0){
			return false;
		}
		else {
			return true;
		}
			
	}

	public function getGroupsList(){
		$this->initiateModel('read');
		$sql = "SELECT group_id,group_name from groups where group_name NOT LIKE 'mailer'";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		
		return $result;

	}

	public function updateCampaignTime($campaignName,$campaignTime){
		$this->initiateModel('write','User');

		$sql = "update shiksha.smsCampaign set savedDate = ?,status= 'start' where campaignName = ?";
		$this->dbHandle->query($sql,array($campaignTime,$campaignName));
		
	}


	function fetchScheduledCampaign(){
       	$this->initiateModel('read','User');

		$sql = "select id,userId,mobile,message,campaignName from smsCampaign where savedDate <= now() and status = 'start'";
		$result = $this->dbHandle->query($sql)->result_array();

		return $result;
	}

	function updateSmsCampaignStatus($id){
		$this->initiateModel('write','User');

		$sql = "update smsCampaign set sentDate = now(),status='sent' where id=?";
		
		$this->dbHandle->query($sql,array($id));

	}

	public function getSavedSMSCampaign(){
		$this->initiateModel('read','User');

		$sql = "select DISTINCT campaignName,savedDate from smsCampaign where status = 'start'";
		$result = $this->dbHandle->query($sql)->result_array();

		return $result;
	}

	public function getMailersByStatus($status, $userId, $groupId, $adminType, $mailertype)
	{
		$this->initiateModel('read');

		$queryCmd = "SELECT id,mailerName,mailsSent,time,campaignId,parentMailerId from mailer where mailsSent IN ($status) and mailertype = ?";
		if($adminType == 'group_admin') {
            $queryCmd .= " and group_id = ".$this->dbHandle->escape($groupId);
        } else if ($adminType == 'normal_admin') {
            $queryCmd .= " and userId = ".$this->dbHandle->escape($userId);
        }
        $queryCmd .= ' order by id desc';
		$data = array();
		$result = $this->dbHandle->query($queryCmd, array($mailertype))->result_array();
		
		return $result;
	}

	public function getMailerDetailsByMailerId($mailerId, $status)
	{
		$this->initiateModel('read');
		$queryCmd = "SELECT *,UNIX_TIMESTAMP(time) as mailerScheduleTime from mailer where id = ? and mailsSent = ?";
		$query = $this->dbHandle->query($queryCmd, array($mailerId, $status));
		$data = $query->row_array();
		return $data;
	}

	public function updateMailersStatusByMailerId($mailerIds, $status, $previousStatus)
	{
		if(!empty($mailerIds)) {

			$this->initiateModel('write');

			$queryCmd = "UPDATE mailer set mailsSent = ? where id IN (?) and mailsSent = ?";
			$this->dbHandle->query($queryCmd, array($status, $mailerIds, $previousStatus));

			return true;

		}
	}

	public function getSpecializationsForSubCategories($subCategoriesArray)
	{
		if(!empty($subCategoriesArray)){
			$this->initiateModel('read','User');		
			//$subCategories = implode(",", $subCategoriesArray);

			$queryCmd = "select tcs.specializationId, ldbcsm.categoryID from tCourseSpecializationMapping tcs left join LDBCoursesToSubcategoryMapping ldbcsm on tcs.specializationId = ldbcsm.ldbCourseId where ldbcsm.categoryID in (?) ";
			$query = $this->dbHandle->query($queryCmd, array($subCategoriesArray));
			$data = array();
			foreach($query->result_array() as $row) {
				$data[] = $row['specializationId'];
			}
			return $data;
		}
	}

	public function addtoMailTracking($mailerId)
	{
		$this->initiateModel('write');
		
		$queryCmd = "insert into mailTracking (mailerId, addedOn) values (?, now())";
		$this->dbHandle->query($queryCmd, array($mailerId));
		return true;
	}

	public function deleteUserSet($usersetId){
		if(!empty($usersetId)){
			$this->initiateModel('write');

			$queryCmd = "update mailer.userSearchCriteria set status = 'deleted' where id = ?";
			$this->dbHandle->query($queryCmd, array($usersetId));
		}
	}

	public function deleteTemplate($templateId,$templateType){
		if(!empty($templateId) && !empty($templateType)){
			$this->initiateModel('write');

			$queryCmd = "update mailer.mailerTemplate set isActive = 'false' where id = ? and templateType = ?";
			$this->dbHandle->query($queryCmd, array($templateId,$templateType));
		}
	}

	public function getUserDetailsByEmail($encryptedEmail) {

		if(empty($encryptedEmail)) {
			return;
		}

		$this->initiateModel('read','User');

		$sql = "select email, ePassword from tuser where email=DECODE(UNHEX(?),'ShikSha')";
		$result = $this->dbHandle->query($sql, array($encryptedEmail))->row_array();

		return $result;
	}

	public function getUsersLastInstituteAndCourseNational($userIds) {

		return false;
		$this->initiateModel('read', 'User');
		$exclude_users = array();
		global $highPriorityActions;
		
		$sql = "SELECT latestUserResponseData.userId, latestUserResponseData.courseId, shiksha_courses.name, shiksha_institutes.name as institute_name
			FROM latestUserResponseData
			LEFT JOIN shiksha_courses ON latestUserResponseData.courseId = shiksha_courses.course_id AND shiksha_courses.status = 'live'
			LEFT JOIN shiksha_institutes ON latestUserResponseData.instituteId = shiksha_institutes.listing_id AND shiksha_institutes.status = 'live'
			WHERE userId in (".implode(",", $userIds).")
			AND action IN (\"".implode('","', $highPriorityActions)."\")";
		
		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();
		$returnArr = array();
		foreach($results as $result) {
			$returnArr[$result['userId']]['courseName'] = $result['name'];
			$returnArr[$result['userId']]['instituteName'] = $result['institute_name'];
			$returnArr[$result['userId']]['course_id'] = $result['courseId'];
			$exclude_users[] = $result['userId'];
		}
		
		if(count($exclude_users)) {
			$userIds = array_diff($userIds, $exclude_users);
		}
		
		if(!empty($userIds)) {
			$sql = "SELECT latestUserResponseData.userId, latestUserResponseData.courseId, shiksha_courses.name, shiksha_institutes.name as institute_name
				FROM latestUserResponseData
				LEFT JOIN shiksha_courses ON latestUserResponseData.courseId = shiksha_courses.course_id AND shiksha_courses.status = 'live'
				LEFT JOIN shiksha_institutes ON latestUserResponseData.instituteId = shiksha_institutes.listing_id 
				AND shiksha_institutes.status = 'live'	WHERE userId in (".implode(",", $userIds).")
				AND action NOT IN (\"".implode('","', $highPriorityActions)."\")";
				
			$query = $this->dbHandle->query($sql);
			$results = $query->result_array();
			
			foreach($results as $result) {
				$returnArr[$result['userId']]['courseName'] = $result['name'];
				$returnArr[$result['userId']]['instituteName'] = $result['institute_name'];
				$returnArr[$result['userId']]['course_id'] = $result['courseId'];
			}
		}
		
		return $returnArr;
	}

	public function getUsersExplicitInterest($userIds)
	{
		$this->initiateModel('read','User');
        // $sql = "select tup.userId as userid, tup.educationLevel, s.name
        //         from tUserPref tup
        //         left join tUserInterest tui on tup.userId = tui.userId and tui.status IN ('live','draft')
        //         left join streams s on tui.streamId = s.stream_id
        //         where tup.userId in (".implode(",",$userIds).")";

         $sql = "select tu.userid, tup.educationLevel, s.name, cct.city_name
                from tuser tu
                inner join countryCityTable cct on tu.city = cct.city_id
                inner join tUserPref tup on tu.userid = tup.userId
                left join tUserInterest tui on tup.userId = tui.userId and tui.status IN ('live','draft')
                left join streams s on tui.streamId = s.stream_id
                where tu.userid in (?)"; 

        $query = $this->dbHandle->query($sql, array($userIds));
        $results = $query->result_array();
        $returnArr = array();
        foreach($results as $result) {
            $returnArr[$result['userid']]['stream'] = $result['name'];
            $returnArr[$result['userid']]['city'] = $result['city_name'];
            $returnArr[$result['userid']]['level'] = $result['educationLevel'];
        }
        return $returnArr;
	}

	public function getUsersImplicitInterest($userIds)
	{
		$this->initiateModel('read','User');
		$sql = "select urp.user_id, urp.education_level, s.name 
				from user_response_profile urp 
				inner join streams s on urp.stream_id = s.stream_id
				where urp.user_id in (?) and urp.status = 'live'";

		$query = $this->dbHandle->query($sql, array($userIds));
		$results = $query->result_array();
		$returnArr = array();
		foreach($results as $result) {
			$returnArr[$result['user_id']]['stream'] = $result['name'];
			$returnArr[$result['user_id']]['level'] = $result['education_level'];
		}
		return $returnArr;
	}

	public function getMultipleMailerDetails($mailerIds)
	{
		$this->initiateModel('read');
		$queryCmd = "SELECT id, parentMailerId from mailer where id IN (?)";
		$query = $this->dbHandle->query($queryCmd, array($mailerIds));
		$results = $query->result_array();
		$finalResult = array();
		foreach($results as $result) {
			$finalResult[$result['id']] = $result;
		}
		return $finalResult;
	}

	public function getallProfileUserSets()
	{
		$data = array();
		$this->initiateModel('read');
		$sql = "select id, criteriaJSON from userSearchCriteria where criteriaType = 'Profile' and createdTime > DATE_SUB(now(), INTERVAL 6 MONTH)";
		$query = $this->dbHandle->query($sql);
		return $query->result_array();
	}

	public function updateProfileUserSets($usersetIds)
	{
		$data = array();
		$this->initiateModel('read');
		$sql = "update userSearchCriteria set status = 'live' where id in (?)";
		$this->dbHandle->query($sql, array($usersetIds));
	}

	public function getMailReport($mailerId)
	{
		$data = array();
		$this->initiateModel('read');
		$sql = "select * from mailerMis where mailerId = ? and userId > 0";
		$query = $this->dbHandle->query($sql, array($mailerId));
		return $query->result_array();
	}


	public function getActivityUserSearchCriteria(){
		
		$this->initiateModel('write');
		 $sql = "select id, criteriaJSON from mailer.userSearchCriteria where criteriaType='Activity' and status='live'";

	    $result = $this->dbHandle->query($sql)->result_array();

	    return $result;
	}

	public function updateUserSearchCriteria($criteria_data,$id){
		
		
		$this->initiateModel('write');
		$sql_update = 'update mailer.userSearchCriteria set criteriaJSON=? where id=?';
	   	$this->dbHandle->query($sql_update,array($criteria_data,$id));
	}

	public function updateTemplateHtml($templateId,$templateHtml){
		if(!empty($templateId) && $templateId>0 && !empty($templateHtml)){
			$this->initiateModel('write');

			$queryCmd = "update mailer.mailerTemplate set htmlTemplate = ? where id = ?";
			$this->dbHandle->query($queryCmd, array($templateHtml,$templateId));
		}
	}


	public function getUnTrackedMailers(){
		$mailers = array();
		$this->initiateModel('read');
		$sql = "Select mailer.id as mailerId,mailer.clientId as clientId ,mailer.mailerName,mailer.totalMailsToBeSent as totalMails , mailer.time as scheduledDate ,mailer.userId as createdBy from mailer left join mailerReport ON mailer.id = mailerReport.mailerId where mailer.mailsSent = 'true' and mailerReport.mailerId IS NULL and mailer.id >= 48581 ";
		
		$mailers = $this->dbHandle->query($sql)->result_array();

		
		return $mailers;

	}

	public function getMailerIdsToDisplay($groupId,$timeStart,$timeEnd,$groupFilter,$adminType,$status,$userId){
		$mailerIds  = array();
		if(empty($groupId) || empty($timeStart) || empty($timeEnd) || empty($adminType)){
			return $mailerIds;
		}
		
		$this->initiateModel('read');
		$sql = "Select id,mailerName,parentMailerId,campaignId,subject,sendername,dripMailerType,clientId from mailer where  mailertype = 'client' AND time >= ".$this->dbHandle->escape($timeStart)." AND time <= ".$this->dbHandle->escape($timeEnd);
		if($adminType == 'group_admin') {
            $sql .= " and group_id = ".$groupId;
        } else if ($adminType == 'normal_admin') {
            $sql .= " and userId = ".$userId;
        } else if($adminType == 'super_admin' && $groupFilter != ""){
            $sql .= " and group_id = ".$groupFilter;
        }
        if(!empty($status)) {
            $sql .= " and mailsSent = ".$status;
        }
        $sql.= " order by 1 desc";
		$mailerData = $this->dbHandle->query($sql)->result_array();
		
		return $mailerData;
	}

	public function getMailerReportData($mailerIds){
		$mailerData  = array();
		if(empty($mailerIds)){
			return $mailerIds;
		}

		$this->initiateModel('read');

		$sql = "Select * from mailerReport where mailerId IN(?) order by scheduledDate DESC";
		
		$mailerData = $this->dbHandle->query($sql,array($mailerIds))->result_array();

		return $mailerData;
	}	

	public function getUnprocessedMailerFromTracking(){
		$mailers = array();
		$this->initiateModel('read');
		$sql = "Select mailerId ,clientId, mailerName, totalMails , scheduledDate ,createdBy   from mailerReport where isProcessed = 'false'";
		$mailers = $this->dbHandle->query($sql)->result_array();
		return $mailers;
	}

	

	public function getMailersStatusData($mailerIds){
		if(empty($mailerIds)){
			return ;
		}
		$mailerStatus = array();
		$this->initiateModel('read');
		$sql = "Select mailerid, issent, count(*)  as count from mailQueue where mailerid in (?) group by mailerid,issent";

		$mailerStatusData = $this->dbHandle->query($sql,array($mailerIds))->result_array();
		return $mailerStatusData;

	}
	public function updateMailerTrackingData($data){
		if(empty($data)){
			return;
		}
		$this->initiateModel('write');
		$this->dbHandle->update_batch('mailerReport',$data,'mailerId');
		
	}

	public function insertMailerTrackingData($data){
		if(empty($data)){
			return;
		}
		$this->initiateModel('write');
		$this->dbHandle->insert_batch('mailerReport',$data);
		
	}

	public function  getUserActionData($offset,$limit){
		
		if($offset == null || $limit == null){
			return;
		}
		$this->initiateModel('read');
		$sql = "Select id,mailerId,userId,widget,trackingType from mailerMis where id >? limit ? ";
		$userActionData =  $this->dbHandle->query($sql , array(intval($offset),$limit))->result_array();
	
		return $userActionData;
	}

	public function getExistingTrackingData($mailerIds){
		if($mailerIds == null){
			return;
		} 
		$this->initiateModel('read');
		$sql = "Select mailerId,sentMails,uniqueMailsOpened,uniqueMailsClicked,uniqueUnsubscribeClicked,totalMailsOpened,totalMailsClicked,totalUnsubscribeClicked from mailerReport where mailerId IN (?)";
		$trackingData = $this->dbHandle->query($sql,array($mailerIds))->result_array();
		$existingTrackingData = array();
		if(!empty($trackingData)){
			foreach ($trackingData as $value) {
				$existingTrackingData[$value['mailerId']] = $value;
			}
		}
		return $existingTrackingData ;
	}

	public function getPreviousActionCountForUsers($timeStart,$userIds,$lastProcessedId,$trackingType,$mailerId){
		if($timeStart ==  null || empty($userIds) || $userIds == null || $lastProcessedId == null || $trackingType == null || $mailerId == null){
			return;
		}
		$this->initiateModel('read');

		$sql =  "Select count(Distinct userId) as count from mailerMis where userId IN (?) and date >= ? and id <= ? and mailerId = ? ";
		if($trackingType == 'open'){
			$sql .= "and trackingType = 'open'";
		}
		if($trackingType == 'click'){
			$sql .= "and trackingType = 'click' and widget!='unsubscribe'";
		}
		if($trackingType == 'unsubscribe'){
			$sql .= "and trackingType = 'click' and widget='unsubscribe'";
		}

		$countData = $this->dbHandle->query($sql,array($userIds,$timeStart,intval($lastProcessedId),$mailerId))->result_array();
		if(!empty($countData)){
			return $countData[0]['count'];
		} else {
			return 0;
		}
	}

	public function getSubscriptionDetails(){
		$this->initiateModel('read');
		$sql = "Select id , subscriptionDetails from mailer";
		$subsctiptionDetails = $this->dbHandle->query($sql)->result_array();
		return $subsctiptionDetails;
	}

	public function populateMailerClientId($data){
		if(empty($data)){
			return;
		}
		$this->initiateModel('write');
		$this->dbHandle->update_batch('mailer',$data,'id');
	}

	public function getMailerMisOldData($id,$limit){
		
		$this->initiateModel('read');
		$sql = "Select * from mailerMis_old where id > ? limit ?";
		return $this->dbHandle->query($sql,array($id,$limit))->result_array();
	}

	public function populateNewMailerMisTable($data){
		$this->initiateModel('write');
		if(!empty($data)){
			$this->dbHandle->insert_batch('mailerMis',$data);
		}
	}

	public function updateNumberOfDripUserSet($numberOfUsers,$mailerId){
		if($numberOfUsers<1 || empty($mailerId)){
			return;
		}

		$this->initiateModel('write');
		$sql = "update locked_amount_for_subscription set expectedDeduction = ? where mailerId = ? and status = 'live'";

		$this->dbHandle->query($sql,array($numberOfUsers,$mailerId));

	}

	public function getProcessedUsers($mailerId){
		if(empty($mailerId)){
			return;
		}

		$this->initiateModel('read');
		$sql = "select actualDeduction from locked_amount_for_subscription where mailerId = ? and status = 'live'";

		return $this->dbHandle->query($sql,array($mailerId))->result_array()[0]['actualDeduction'];
	}

	public function updateNumberProcessedDripCampaign($mailerId, $numberProcessed){
		if(empty($mailerId)){
			return;
		}
		$this->initiateModel('write');
		$sql = "update locked_amount_for_subscription set actualDeduction = actualDeduction + ? where mailerId = ?";
		$this->dbHandle->query($sql, array($numberProcessed, $mailerId));
	}

	public function getExpectedAndDeductedAmount($mailerId){
		if(empty($mailerId)){
			return;
		}

		$this->initiateModel('read');
		$sql = "select subscriptionId,actualDeduction,expectedDeduction from locked_amount_for_subscription where mailerId = ? and status = 'live'";

		return $this->dbHandle->query($sql,array($mailerId))->result_array();
	}

	public function freeLockedCreditsForMailer($mailerId){
		if(empty($mailerId)){
			return;
		}
		$sql = "update locked_amount_for_subscription set status = 'history', freeDate = now() where mailerId = ? and status = 'live'";
		$this->dbHandle->query($sql, array($mailerId));
		$this->dbHandle->trans_complete();
		if ($this->dbHandle->trans_status() === FALSE) {
			$return = false;
			throw new Exception('Transaction Failed');
	  	} else {
	  		return true;
	  	}
	}

	public function deductAmountFromSubscription($subId , $amount){
		if(empty($subId) || empty($amount)){
			return;
		}
		$this->initiateModel('write','SUMS');
		$sql = "update Subscription_Product_Mapping set BaseProdRemainingQuantity = BaseProdRemainingQuantity - ? where SubscriptionId = ? and status = 'ACTIVE'";
		$this->dbHandle->query($sql, array($amount,$subId));
	}

    public function getScheduleTimeForMailer($mailerId){
    	if(empty($mailerId)){
    		return;
    	}
    	$this->initiateModel('read');
    	$sql = "select id, month(time) as month, year(time) as year from mailer where id in (?)";
		return $this->dbHandle->query($sql, array($mailerId))->result_array();
    }

    public function getDripMailersFromParentMailers($dripParents){
    	if(empty($dripParents)){
    		return;
    	}
    	$this->initiateModel('read');
    	$sql = "select id from mailer where parentMailerId in (?)";
		return $this->dbHandle->query($sql, array($dripParents))->result_array();
    }

    public function freeLockForCancelledMailer($allMailers){
    	if(empty($allMailers)){
    		return;
    	}
    	$this->initiateModel('write');
    	$sql = "update locked_amount_for_subscription set status = 'history', freeDate = now() where mailerId in (?) and status = 'live'";
		$this->dbHandle->query($sql, array($allMailers));
    }
	
}

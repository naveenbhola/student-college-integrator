<?php
/**
 * User model class file
 */

 
/**
 * User model class
 */ 
class UserAPIModel extends MY_Model
{
    function __construct()
    {
        parent::__construct('User');
    }
    
    /**
     * Initiate the model
     *
     * @param string $operation
     */ 
    private function initiateModel($operation = 'read')
    {
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}
    
    function insertFBAccessToken($userId, $accessToken, $facebookUserId){
        $this->initiateModel('write');

        $dataArr = array("userId" => $userId,
                         "access_token" => $accessToken,
                         "facebookUserId" => $facebookUserId
                         );
        $this->dbHandle->insert("facebook_access_token", $dataArr);
    }
    
    function updateFBAccessToken($userId, $accessToken, $facebookUserId){
        $this->initiateModel('write');

        $rs = $this->dbHandle->query("select * from facebook_access_token where userId = ? ", array($userId));

        if($rs->num_rows() > 0){
            $dataArr = array("access_token" => $accessToken, "status" => "live");

            $this->dbHandle->where('userId',$userId);
            $this->dbHandle->update("facebook_access_token", $dataArr);
        }
        else{
            $this->insertFBAccessToken($userId, $accessToken, $facebookUserId);
        }
    }

    function updateUserAvatarUrl($userId, $avtarimageurl){
        $this->initiateModel('write');

        $dataArr = array("avtarimageurl" => $avtarimageurl);

        $this->dbHandle->where('userid',$userId);
        $this->dbHandle->update("tuser", $dataArr);   
    }

    function getUsersBasicDetailsByIds($userIds){

        $result = array();
        if(!is_array($userIds) || empty($userIds)){
            return $result;
        }

        $this->initiateModel('read');

        $sql = "SELECT userid as userId,firstname,lastname,avtarimageurl FROM tuser WHERE userid in (".implode(',', $userIds).")";
        $rs = $this->dbHandle->query($sql)->result_array();
        
        foreach ($rs as $value) {
            $result[$value['userId']] = $value;
        }
        
        return $result;
    }

    function getUsersLevelDetails($userIds){

        $result = array();
        if(!(is_array($userIds) && !empty($userIds))){
            return $result;
        }
        error_log("ABHINAV@TEST ::: ".print_r($userIds, true));

        $this->initiateModel('read');

        $sql = "SELECT userId,userpointvaluebymodule,HQContentCount,levelId,levelName FROM userpointsystembymodule WHERE modulename='AnA' AND userId IN (".implode(',', $userIds).")";
        $rs = $this->dbHandle->query($sql)->result_array();
        
        foreach ($rs as $value) {
            $result[$value['userId']] = $value;
        }
        
        return $result;
    }

    function getUsersFollowerCount($userIds){

        $result = array();
        if(!(is_array($userIds) && !empty($userIds))){
            return $result;
        }

        $this->initiateModel('read');

        $sql = "SELECT entityId as userId, count(*) as followers FROM tuserFollowTable WHERE entityType='user' AND status='follow' AND entityId IN (".implode(',', $userIds).") GROUP BY entityId ORDER BY followers desc";
        $rs = $this->dbHandle->query($sql)->result_array();
        
        foreach ($rs as $value) {
            $result[$value['userId']] = $value;
        }

        foreach ($userIds as $value) {
            if(!isset($result[$value]))
                $result[$value] = array("followers" => 0);
        }
        
        return $result;
    }

    function getUsersAnswerCount($userIds){

        $result = array();
        if(!(is_array($userIds) && !empty($userIds))){
            return $result;
        }

        $this->initiateModel('read');

        $sql = "SELECT m.userId,count(*) as answerCount FROM messageTable m use index (idx_composite_userId_status_type), messageTable m1 WHERE m.userId IN (".implode(',', $userIds).") AND m.status = 'live' AND m.fromOthers = 'user' AND m.parentId = m.threadId AND m1.msgId = m.threadId AND m1.status IN ('live','closed') group by m.userId;";
        $rs = $this->dbHandle->query($sql)->result_array();
        
        foreach ($rs as $value) {
            $result[$value['userId']] = $value;
        }

        foreach ($userIds as $value) {
            if(!isset($result[$value]))
                $result[$value] = array("answerCount" => 0);
        }
        
        return $result;
    }

    function isUserFollowingEntity($loggedInUserId, $userIds, $type){

        $result = array();
        if(!(is_array($userIds) && !empty($userIds) && !empty($loggedInUserId))){
            return $result;
        }

        $this->initiateModel('read');


        $sql = "SELECT entityId FROM tuserFollowTable WHERE userId = ? AND entityType=? AND status='follow' AND entityId IN (".implode(",", $userIds).")";
        $rs = $this->dbHandle->query($sql, array($loggedInUserId, $type))->result_array();
        
        $followList = array();
        foreach ($rs as $value) {
            $followList[] = $value['entityId'];
        }

        foreach ($userIds as $value) {
            if(in_array($value, $followList)){
                $result[$value] = array("isFollowing" => 1);
            }
            else{
                $result[$value] = array("isFollowing" => 0);
            }
        }
        return $result;   
    }

    function getUsersUpvotesCount($userIds){

        $result = array();
        if(!(is_array($userIds) && !empty($userIds))){
            return $result;
        }

        $this->initiateModel('read');


        $sql = "SELECT sum(mt.digUp) as upvoteCount, mt.userId FROM messageTable mt, messageTable mt1 WHERE ((mt.parentId = mt.threadId AND mt.fromOthers = 'user' ) OR (mt.parentId = mt.mainAnswerId AND mt.fromOthers = 'discussion')) AND mt1.msgId = mt.threadId and mt1.status in ('live' , 'closed') and mt.status = 'live' and mt.userId in (".implode(",",$userIds).") group by mt.userId;"; 
        $rs = $this->dbHandle->query($sql)->result_array();
        
        foreach ($rs as $value) {
            $result[$value['userId']] = $value;
        }

        foreach ($userIds as $value) {
            if(!isset($result[$value]))
                $result[$value] = array("upvoteCount" => 0);
        }
        
        return $result;
    }

    function getAboutMeOfUsers($userIds){

        $result = array();
        if(!(is_array($userIds) && !empty($userIds))){
            return $result;
        }

        $this->initiateModel('read');


        $sql = "SELECT userId,aboutMe FROM tUserAdditionalInfo WHERE userId IN (".implode(",", $userIds).")";
        $rs = $this->dbHandle->query($sql)->result_array();
        
        foreach ($rs as $value) {
            $result[$value['userId']] = $value;
        }

        foreach ($userIds as $value) {
            if(!isset($result[$value]))
                $result[$value] = array("aboutMe" => "");
        }
        
        return $result;
    }
    /*** get User Current  Employement Details
    @param :    userIds => array of Id.
    */
    function getDesignationOfUsers($userIds)
    {
        $result = array();
        if(!is_array($userIds) && empty($userIds)){
            return $result;
        }
        $this->initiateModel('read');
        $sql = "select userId,designation,employer,currentJob from tUserWorkExp where userId in (".implode(',',$userIds).")";
        $rs = $this->dbHandle->query($sql)->result_array();

        $sql = "SELECT userId,fieldId FROM tUserDataPrivacySettings WHERE userId IN (".implode(',', $userIds).") AND status = 'live'";
        $privacyResult = $this->dbHandle->query($sql)->result_array();

        $userIdJobCount = array();
        foreach ($rs as $key => $value) {
            if($value['currentJob'] == 'YES')
            {
                $result[$value['userId']] = $value;
                $userIdJobCount[$value['userId']] = !empty($userIdJobCount[$value['userId']]) ? $userIdJobCount[$value['userId']] + 1: 1;

            }
            if(empty($result[$value['userId']]))
                $userIdJobCount[$value['userId']] = !empty($userIdJobCount[$value['userId']]) ? $userIdJobCount[$value['userId']] + 1: 1;
        }
        foreach ($privacyResult as $key => $value) {
            if(isset($result[$value['userId']]) && 'EmployerworkExp'.$userIdJobCount[$value['userId']] === $value['fieldId'])
            {
                $result[$value['userId']]['designation'] = 'Information Marked as Private';
                $result[$value['userId']]['employer'] = '';
            }
        }
        foreach ($userIds as $value) {
            if(!isset($result[$value]))
                $result[$value] = array("designation" => "","employer" => "");
        }
        return $result;
    }
    /*** get User Higher Educational Details
    @param :    userIds => array of Id.
    */
    function getHigherEducationDetailsofUsers($userIds)
    {
        $result = array();
        if(!is_array($userIds) && empty($userIds))
        {
            return $result;
        }
        $this->initiateModel('read');
        $sql = "SELECT userId,Name as Qualification,Level,instituteName FROM tUserEducation where userId IN (".implode(',', $userIds).") AND status = 'live' and Level != 'Competitive exam'";
        $rs = $this->dbHandle->query($sql)->result_array();

        $sql = "SELECT userId,fieldId FROM tUserDataPrivacySettings WHERE userId IN (".implode(',', $userIds).") AND status = 'live'";
        $privacyResult = $this->dbHandle->query($sql)->result_array();
        $priorityArray= array('PHD','PG','UG','12','10');
        foreach ($rs as $key => $value) {
            if(empty($result[$value['userId']]))
            {
                $result[$value['userId']] = $value;
            }
            elseif(!empty($result[$value['userId']]))
            {
                $minFlag = array_search($value['Level'], $priorityArray);
                $expectedFlag = array_search($result[$value['userId']]['Level'],$priorityArray);
                if($minFlag < $expectedFlag)
                    $result[$value['userId']] = $value;       
            }
        }
        foreach ($privacyResult as $key => $value) {
            if(isset($result[$value['userId']]) && ('InstituteName'.$result[$value['userId']]['Level'] === $value['fieldId']))
            {
                $result[$value['userId']]['Qualification'] = 'Information Marked as Private';
                $result[$value['userId']]['instituteName'] = '';
            }
        }
        foreach ($userIds as $value) {
            if(!isset($result[$value]))
                $result[$value] = array("Qualification" => "","instituteName" => "","Level" => "");
        }
        return $result;
    }
    function getSocialProfileForUsers($userIds)
    {
        $result = array();
        if(!is_array($userIds) && empty($userIds))
        {
            return $result;
        }
        $this->initiateModel('read');
        $sql = "SELECT userId,twitterId,facebookId,linkedinId,youtubeId,personalURL FROM userSocialProfile where userId IN (".implode(',', $userIds).")";
        $rs = $this->dbHandle->query($sql)->result_array();
        foreach ($rs as $key => $value) {
            $result[$value['userId']] = $value;
        }
        foreach ($userIds as $value) {
            if(!isset($result[$value]))
                $result[$value] = array("twitterId" => "","facebookId" => "", "linkedinId" => "", "youtubeId"=> "",
                    "personalURL" => "");
        }
        return $result;
    }

}

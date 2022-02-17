<?php

class mailerschedulermodel extends MY_Model
{
	private $dbHandle;

	private function initiateModel($mode = "write", $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	function getClientDetailsByUserId($queryValue){
		if(empty($queryValue)){
    		return;
    	}

    	$this->initiateModel('read','User');
    	$sql = "select userid,email,firstname,lastname from tuser where userid = ?";

    	return $this->dbHandle->query($sql,array($queryValue))->row_array();
	}

	function getClientDetailsByEmailId($queryValue){
		if(empty($queryValue)){
    		return;
    	}

    	$this->initiateModel('read','User');
    	$sql = "select userid,email,firstname,lastname from tuser where email = ?";

    	return $this->dbHandle->query($sql,array($queryValue))->row_array();
	}
	
	function getTemplatesByUserId($userid,$groupId,$adminType){
		if(empty($userid) || empty($groupId) || empty($adminType)){
			return;
		}

		$this->initiateModel('read','Mailer');
		$sql = "SELECT id,name from mailerTemplate ";

		if($adminType == 'group_admin') {
            $sql .= " where group_id = ? and isActive = true ";
            $searchCriteria = $groupId;
        } else if ($adminType == 'normal_admin') {
            $sql .= " where createdBy = ? and isActive = true ";
            $searchCriteria = $userid;
        }
        else{
        	$sql .= " where isActive = true ";
        }

        $sql .= " and templateType = 'mail'and parentTemplateId is NULL and id not in (4003,16740,9804) order by createdOn desc limit 500";
		return $this->dbHandle->query($sql,array($searchCriteria))->result_array();
	}

	function lockCredits($subscriptionId,$amount,$BaseProdRemainingQuantity,$index){
        if(empty($subscriptionId) || empty($amount)){
            return false;
        }
        if ($index == 0){
          	$this->initiateModel('write','Mailer');
            $this->dbHandle->trans_start();
        }

        $sql = "insert into locked_amount_for_subscription (subscriptionId,lockedAmount,BaseProdRemainingQuantity) values(?,?,?)";
    	$this->dbHandle->query($sql,array($subscriptionId,$amount,$BaseProdRemainingQuantity));
    	$insertId = $this->dbHandle->insert_id();

   		return $insertId;
    }

    function getSubscribtionDetails($subscriptionIds){
    	if(empty($subscriptionIds)){
    		return;
    	}

    	$this->initiateModel('read','SUMS');
    	$sql = "select SubscriptionId,BaseProdRemainingQuantity,BaseProductId,BaseProdRemainingQuantity from Subscription_Product_Mapping where SubscriptionId in (?) and Status='ACTIVE'";
    	return $this->dbHandle->query($sql,array($subscriptionIds))->result_array();
    }

    function getLockedAmountForSubscription($subscriptionIds){
    	if(empty($subscriptionIds)){
    		return false;
    	}

    	$this->initiateModel('read','Mailer');
    	$sql = "select subscriptionId,SUM(lockedAmount) as lockedAmount from locked_amount_for_subscription where subscriptionId in (?) and status='live' group by subscriptionId";
    	return $this->dbHandle->query($sql,array($subscriptionIds))->result_array();
    }

    public function updateMailerInLockedAmountTable($mailerId,$id){
    	if(empty($mailerId) || empty($id)){
    		return false;
    	}
      	$sql = "update locked_amount_for_subscription set mailerId=? where id=? and status='live'";
        $this->dbHandle->query($sql,array($mailerId,$id));
        return true;
    }

    public function insertUserSetForMailer($csvArr,$count, $user, $userGroup) {
        $queryCmdInsert = "insert into list values('','TempName', 'TempDesc', '', 'true', ?, 'true' , NOW(), ? ,0)";    
        $this->initiateModel('write','Mailer');
        $queryTemp = $this->dbHandle->query($queryCmdInsert, array($count, $user));
        $listId = $this->dbHandle->insert_id();
    
        //After inserting the row in list table, update its masterListId. For a csv upload, there will be a single list entry
        $queryCmdUpdate = "update list set masterListId=? where id=? ";
        $queryTempUpdate = $this->dbHandle->query($queryCmdUpdate, array($listId, $listId));
        $queryCmdInsert = "insert into csvKeyValue values";
            foreach ($csvArr as $key=>$val) {
                for($i = 0 ; $i < count($val); $i++) {
                    $queryCmdInsert.= "('', $listId, '".mysql_escape_string($key)."', $i, '".mysql_escape_string($val[$i])."'),";
                    
                }
            }
        $queryCmdInsert = substr($queryCmdInsert, 0,-1);
        $queryTemp = $this->dbHandle->query($queryCmdInsert);
        return $listId;
    }

    public function createDuplicateTemplate($templateId){
        if (empty($templateId)){
            return;
        }
        $query = "select name,description,subject,htmlTemplate,createdBy,isActive,group_id from mailerTemplate where id=?";
        $result = $this->dbHandle->query($query,$templateId)->result_array();
        if (empty($result)){
            return;
        }
        $result[0]["createdOn"] = date('Y-m-d H:i:s');
        $result[0]["updatedOn"] = date('Y-m-d H:i:s');
        $result[0]["parentTemplateId"] = $templateId;
        $this->dbHandle->insert_batch('mailerTemplate',$result);
        $newTemplateId = $this->dbHandle->insert_id();

        // Copying template variable
        $query = "select varName,varValue,flagOther from templateVariable where templateId = ?";
        $result = $this->dbHandle->query($query,$templateId)->result_array();
        if (empty($result)){
            return;
        }
        foreach ($result as $key => $value) {
            $result[$key]['templateId'] = $newTemplateId;
        }

        $this->dbHandle->insert_batch('templateVariable',$result);
        return $newTemplateId;
    }

    public function saveMailerInformation($data,$campaignId,$parentMailerId,$save=0)
    {
        if ($save==1){
            $this->initiateModel('write','Mailer');
        }
        if(empty($data)){
            return;
        }
        if (!empty($parentMailerId)){
            $data["parentMailerId"] = $parentMailerId;
        }
        
        if($data['id'] > 0){
            $this->dbHandle->update_batch('mailer',array($data),'id');
            return $data['id'];
        } else {
            $data["campaignId"] = $campaignId;
            $this->dbHandle->insert_batch('mailer',array($data));
            return $this->dbHandle->insert_id();
        }
    }

    public function insertCampaignName($campaignName,$save){
        if ($save==1){
            $this->initiateModel('write',"Mailer");
        }
        $sql = "insert into campaignDetails (name) values (?)";
        $this->dbHandle->query($sql,$campaignName);
        return $this->dbHandle->insert_id();
    }

    public function completeTransaction()
    {
        $this->dbHandle->trans_complete();
        return true;
    }

    public function getParentMailerChildDetails($parentMailerId){
        if($parentMailerId <=0 || empty($parentMailerId)){
            return;
        }
        $this->initiateModel('read','Mailer');
        $sql = "select mailerName from mailer where parentMailerId = ? and mailsSent != 'cancel' ";
        $data = $this->dbHandle->query($sql,$parentMailerId)->result_array();
        return $data;
    }


    public function getParentMailerDetails($parentMailerId){
        if($parentMailerId <=0 || empty($parentMailerId)){
            return;
        }
        $this->initiateModel('read','Mailer');
        $sql = "select m.*,c.name as campaignName, mt.parentTemplateId as parentTemplateId from mailer m left join campaignDetails c on m.campaignId = c.id join mailerTemplate mt on m.templateId = mt.id where m.id = ? ";
        $data = $this->dbHandle->query($sql,$parentMailerId)->result_array()[0];
        return $data;
    }

     public function getChildMailersCount($mailerIds){
        $mailerData  = array();
        if(empty($mailerIds)){
            return $mailerIds;
        }

        $this->initiateModel('read','Mailer');

        $sql = "Select count(*) as count,parentMailerId from mailer where parentMailerId in (?) and mailsSent != 'cancel' group by parentMailerId";
        
        $mailerData = $this->dbHandle->query($sql,array($mailerIds))->result_array();

        return $mailerData;
    }   
     public function getCampaignDetails($campaignIds){
        if(empty($campaignIds)){
            return false;
        }

        $this->initiateModel('read','Mailer');
        $sql = "select * from campaignDetails where id in (?)";
        return $this->dbHandle->query($sql,array($campaignIds))->result_array();
    }

    public function getMailQueueDataForES($lastMailQueueId){
        $this->initiateModel('read',"Mailer");
        $sql    = "select mailid,userid,mailerid from mailQueue where mailid>? limit  10000";
        $result = $this->dbHandle->query($sql,array($lastMailQueueId))->result_array();
        return $result;
    }

    public function getMailMisDataForES($lastMisQueueId){
        $this->initiateModel('read',"Mailer");
        $sql    = "select id,mailerid, mailid, trackingType from mailerMis where  id>? and widget!='unsubscribe' limit  10000 ";
        $result = $this->dbHandle->query($sql,array($lastMisQueueId))->result_array();
        return $result;
    }

    public function getDataForParentMailer($mailerId, $status){
        if($mailerId == null){
            return;
        }
        $this->initiateModel('read',"Mailer");
        $sql =  "Select id,mailerName, templateId, time, senderName , senderMail , totalMailsToBeSent , criteria, subject , clientId , parentMailerId ,dripMailerType , campaignId from mailer where mailsSent = ?  and id  = ? ";

        $parentMailerData = $this->dbHandle->query($sql,array($status, $mailerId))->result_array();

        return $parentMailerData ;
    }

   public function getDataForChildMailer($mailerId, $status){
        if($mailerId == null){
            return;
        }
        $this->initiateModel('read',"Mailer");

        $sql =  "Select id,mailerName, templateId, time, senderName , senderMail , totalMailsToBeSent , criteria, subject , clientId , parentMailerId ,dripMailerType , campaignId from mailer where mailsSent = ?  and parentMailerId  = ? ";

        $dripMailerData = $this->dbHandle->query($sql,array($status, $mailerId))->result_array();

        return $dripMailerData ;
    }

    public function getCampaignName($campaignId){
        if (empty($campaignId)){
            return;
        }
        $this->initiateModel('read','Mailer');
        $sql = "Select  name from  campaignDetails where id  = ?";
        return $this->dbHandle->query($sql,$campaignId)->result_array()[0]['name'];   
    }

    public function updateUnselectedMailers($mailerIds,$save = 0){
        if ($save==1){
            $this->initiateModel('write','Mailer');
        }
        if(empty($mailerIds)){
            return;
        }
        $sql = "Update mailer SET mailsSent='cancel' where id IN (?)";
        $this->dbHandle->query($sql,array($mailerIds));
    }

    public function startTransaction(){
        $this->initiateModel('write','Mailer');
        $this->dbHandle->trans_start();
    }

    public function getUserSetName($userSets){
        if (empty($userSets)){
            return;
        }
        $this->initiateModel('read','Mailer');
        $sql = "Select  name from  userSearchCriteria where id  in (?)";
        $result = $this->dbHandle->query($sql,array($userSets))->result_array();   
        return $result;
    }

	public function getNewsletterTemplateData($oldTemplateId){
        if(empty($oldTemplateId)){
            return;
        }

        $sql = "select articleIds,discussionIds,eventIds,status,include_MPT_tuple from newsletterParams where templateId=? and status='live'";
        $oldTemplateData = $this->dbHandle->query($sql,array($oldTemplateId))->result_array();
        return $oldTemplateData;

    }

    public function insertNewsletterData($templateData){
        if(!empty($templateData)){
            $this->dbHandle->insert_batch('newsletterParams',$templateData);
        }
    }
}

?>

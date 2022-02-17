<?php
class Enterprise_Mailermodel extends MY_Model {
	
	
	private $dbHandle = '';
	function __construct(){
		parent::__construct('OnlineForms');
	}

	
	
	private function initiateModel($operation='read'){
		$appId = 1;
		$this->load->library('OnlineFormConfig');
		$onlineConfig = new OnlineFormConfig();
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
			$this->dbHandle = $this->getWriteHandle();
		}		
	}

	/*************************************
	Purpose: Function is used to get details of a particular template.
	Input: templateId,courseId
	*************************************/
	
	function getTemplateData($templateId,$courseId){
		if(!is_resource($dbHandleSent)){
			$this->initiateModel();
		}else{
			$this->dbHandle = $dbHandleSent;
		}
		$queryCmd = "select subject,body from OF_ENT_ENTERPRISE_EMAIL_TEMPLATE where Id = ? and courseId = ?";
		$query = $this->dbHandle->query($queryCmd,array($templateId,$courseId));
		$result = $query->result_array();
		foreach($result as $res){
			$final =array();
			$final['Subject'] = $res['subject'];
			$final['Body'] = $res['body'];
		}
		return $final;
	}

	/*************************************
	Purpose: Function is used to add a template.
	Input: subject,body,date,courseId
	*************************************/
	
	function addTemplate($subject,$body,$date,$courseId){
		if(!is_resource($dbHandleSent)){
			$this->initiateModel("write");
		}else{
			$this->dbHandle = $dbHandleSent;
		}
		$queryCmd = "INSERT INTO OF_ENT_ENTERPRISE_EMAIL_TEMPLATE (`templateName`,`subject`,`body`,`creationDate`,`courseId`) VALUES (?,?,?,?,?)";
		$query = $this->dbHandle->query($queryCmd,array($subject,$subject,$body,$date,$courseId));
		$result =  ($query==1)?$result = 1:$result = 0;
		return $result;
	}

	/*************************************
	Purpose: Function is used to get data of all the templates..
	Input: courseId
	*************************************/
	
	function getallTemplates($courseId){
		if(!is_resource($dbHandleSent)){
			$this->initiateModel();
		}else{
			$this->dbHandle = $dbHandleSent;
		}
		$queryCmd = "select Id,templateName,Subject,body from OF_ENT_ENTERPRISE_EMAIL_TEMPLATE WHERE courseId = ? ORDER BY Id";
		$query = $this->dbHandle->query($queryCmd,array($courseId));
		$results = $query->result_array();
		$i=0;
		foreach($results as $res){
			$finalArray[$i]['id'] = $res['Id'];
			$finalArray[$i]['templateName'] = $res['templateName'];
			$finalArray[$i]['Subject'] = $res['Subject'];
			$finalArray[$i]['body'] = $res['body'];
			$i++;
		}
		return $finalArray;
	}

	/*************************************
	Purpose: Function is used to update a particular template..
	Input: templateId,subject,body,date
	*************************************/
	
	function updateTemplate($templateId,$subject,$body,$date){
		if(!is_resource($dbHandleSent)){
			$this->initiateModel("write");
		}else{
			$this->dbHandle = $dbHandleSent;
		}
		$queryCmd ="UPDATE OF_ENT_ENTERPRISE_EMAIL_TEMPLATE SET templateName= ? , Subject= ? , body = ? , creationDate= ? WHERE Id = ? ";
		$query = $this->dbHandle->query($queryCmd,array($subject,$subject,$body,$date,$templateId));
		if($query)
			$result = 1;
		else
			$result = 0;
		return $result;
	}

	/*************************************
	Purpose: Function is used to get subject of a particular template..
	Input: templateId
	*************************************/
	
	function getTemplateSubject($templateId){
		if(!is_resource($dbHandleSent)){
			$this->initiateModel();
		}else{
			$this->dbHandle = $dbHandleSent;
		}
		$queryCmd = "select subject from OF_ENT_ENTERPRISE_EMAIL_TEMPLATE where Id = ? ";
		$query = $this->dbHandle->query($queryCmd,array($templateId));
		$result = $query->result_array();
		foreach($result as $res){
			$final =array();
			$final['Subject'] = $res['subject'];
		}
		return $final;		
	}
	
	/*************************************
	Purpose: Function is used to get data for formids..
	Input: formIds
	*************************************/
	
	function getdataforFormIds($formIds){
		$this->initiateModel();
		foreach($formIds as $form){
			$formDet = explode("_",$form);
			if($formDet[0] == "int"){
				$finalData[$form] =  $this->getInternalFormData($formDet[1]);
			}else{
				$finalData[$form] =  $this->getExternalFormData($formDet[1]);
			}
		}
		return $finalData;
	}
	
	/*************************************
	Purpose: Function is used to get data for internal formids..
	Input: formId
	*************************************/
	
	function getInternalFormData($formId){
		$queryCmd ="SELECT o.value, o.fieldName
				FROM OF_FormUserData o
				WHERE o.fieldName
				IN (
				'firstName',  'lastName',  'middleName' ,'email'
				)
				AND o.onlineFormId
				IN (
				SELECT onlineFormId
				FROM OF_UserForms uf
				WHERE uf.onlineFormId = ? AND uf.formStatus='live'
				)
				GROUP BY o.fieldName";
				
			

		//$queryCmd = "select firstname as name,lastname as lname,u.userid ,email, IF(instituteSpecId  IS NULL, onlineFormId, instituteSpecId) as onlineFormId from OF_UserForms uf, tuser u where u.userId = uf.userid and uf.onlineFormId = ".$formId;
		$query = $this->dbHandle->query($queryCmd,array($formId));
		$result  = $query->result_array();
		$fresult = array();
			foreach($result as $row){
				for($i=0;$i<=3;$i++){
				$fieldName= $result[$i]['fieldName']; 	
				$fresult[$fieldName] = $result[$i]['value'];
				}
			}
		return $fresult;
		
	}
	
	/*************************************
	Purpose: Function is used to get data for external formids..
	Input: formId
	*************************************/
	
	function getExternalFormData($formId){
		$queryCmd = "SELECT *
					FROM `OF_ENT_ENTERPRISE_FIELDS`,OF_ENT_EXTERNAL_FORMS,OF_ENT_EXTERNAL_FORMS_USER_DATA
					WHERE OF_ENT_EXTERNAL_FORMS.`courseId` = OF_ENT_ENTERPRISE_FIELDS.`courseId`
					AND OF_ENT_EXTERNAL_FORMS_USER_DATA.`entFieldId` = OF_ENT_ENTERPRISE_FIELDS.`entFieldid`
					AND OF_ENT_EXTERNAL_FORMS_USER_DATA.entFormId = ?
					AND `typeOfField`
					IN (
					'email', 'name'
					)";
		$query = $this->dbHandle->query($queryCmd,array($formId));
		$result  = $query->result_array();
		$finalResult = array();
		$finalResult['userid'] = 0;
		$finalResult['onlineFormId'] = 0;
		foreach($result as $row){
			if($row['typeOfField']=="name"){
				$finalResult['firstName'] = $row['fieldValue'];
			}
			if($row['typeOfField']=="email"){
				$finalResult['email'] = $row['fieldValue'];
			}
		}
		return $finalResult;
	}
}


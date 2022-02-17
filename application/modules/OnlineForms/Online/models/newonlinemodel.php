<?php
/* 
    Model for database related operations related to Online forms.
*/
class NewOnlineModel extends OnlineParentModel {
		
	function getAsOtherCourse($courseId){
		$this->initiateModel();	
		$queryCmd = "select * from OF_InstituteDetails where status ='live' and otherCourses like  ? and last_date >= now() ;";
		$queryRes = $this->dbHandle->query($queryCmd,array('%'.$courseId.'%'));
		return $queryRes;
	}

	function getPageIdFromPageNumber($courseId, $pageNumber){
		$this->initiateModel();
		$query = "select pageId from OF_PageMappingInForm pmf join OF_ListForms lf on pmf.formId = lf.formId where pageOrder = ? and courseId=? and lf.status ='live'";
		$queryRes = $this->dbHandle->query($query,array($pageNumber,$courseId));
		$row = $queryRes->row();
		$response = $row->pageId;
		if(empty($response) || $response == 0){
			$response = $pageNumber;
		}
	    return $response;			
	}

	function getPagesUserHasFilled($userId, $onlineFormId, $courseId){
		$this->initiateModel();
		$formDetails = $this->getOnlineFormId($userId,$courseId);
		$queryCmd = "SELECT DISTINCT pmf.pageId, pmf.pageOrder ".
					"FROM OF_PageMappingInForm pmf, OF_FilledPageMappingInForm fpm ".
					"WHERE status='live' ".
					"AND pmf.pageId = fpm.pageId ".
					"AND fpm.onlineFormId = ? ".
					"AND fpm.userId = ? ".
					"ORDER by pmf.pageOrder";
		
		$query = $this->dbHandle->query($queryCmd,array($formDetails['onlineFormId'],$userId));
				
		$results = $query->result_array();
		$pageArray = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$row['onlineFormId'] = $formDetails['onlineFormId'];
				$row['status'] = $formDetails['status'];
				$pageArray[] = $row;
			}
		}
		$ret[0]['data']= $pageArray;
		return $ret;
	}

	function getOnlineFormId($userId, $courseId, $pageId){
		$this->initiateModel();
		if($courseId==0){
		    $queryCmd = "select onlineFormId,status from OF_UserForms where userId = ? and type='master' and formStatus = 'live'";
			$queryRes = $this->dbHandle->query($queryCmd,array($userId));
		}
		else{
		    $queryCmd = "select onlineFormId,status from OF_UserForms where userId = ? and type='course' and courseId = ? and formStatus = 'live'";
			$queryRes = $this->dbHandle->query($queryCmd,array($userId,$courseId));
		}
		
		$row = $queryRes->row();
		$response['onlineFormId'] = $row->onlineFormId;
	    $response['status'] = $row->status;
	    return $response;
	}

	function getPageDataForEdit($courseId, $userId, $pageId){
		$this->initiateModel();
		$type = ($courseId==0)?'master':'course';
		if ($type = 'course'){
			$queryCmd = "SELECT pem.entitySetId,fl.parentId,fl.name ".
						"FROM OF_PageEntityMapping pem ".
						"LEFT JOIN OF_FieldsList fl ON fl.fieldId = pem.entitySetId ". 
						"WHERE pem.pageId in (?) ".
						"AND pem.status = 'live' ";
			$queryRes = $this->dbHandle->query($queryCmd,$pageId);
		}

		$fieldParentMapping = array();
		$parentId = array();
		foreach($queryRes->result() as $row){
			$fieldParentMapping[$row->parentId] = $row;
			$parentId[$row->parentId] = $row->parentId;
		}
		
		if (empty($parentId)){
			$queryCmd = "SELECT fu.* FROM OF_FormUserData fu, OF_UserForms uf WHERE uf.onlineFormId = fu.onlineFormId and fu.userId = ? AND fu.pageId in (?) AND uf.courseId = ? AND uf.type = ? AND uf.formStatus = 'live'";
			$queryRes = $this->dbHandle->query($queryCmd,array($userId,$pageId,$courseId,$type))->result_array();
		}

		else{
			$queryCmd = "SELECT fu.* FROM OF_FormUserData fu, OF_UserForms uf WHERE uf.onlineFormId = fu.onlineFormId and fu.userId = ? AND (fu.pageId in (?) or fu.fieldId in (?)) AND uf.courseId = ? AND uf.type = ? AND uf.formStatus = 'live'";
		
			$queryRes = $this->dbHandle->query($queryCmd,array($userId,$pageId,$parentId,$courseId,$type))->result_array();
		}

		$response = array();
		foreach ($queryRes as $row){
			if ($row['fieldId']!="" && isset($row['fieldId']) && isset($fieldParentMapping[$row['fieldId']])){
				$row['fieldName'] = $fieldParentMapping[$row['fieldId']]->name;
				$row['fieldId'] = $fieldParentMapping[$row['fieldId']]->entitySetId; 
			}
			$row['value'] = htmlspecialchars_decode($row['value']);
			$response[] = $row;
		}
		return $response;
	}

	function getTemplatePathAndType($pageId){
		$this->initiateModel();
		$queryCmd = "select templatePath,pageType from OF_PageList where pageId = ?";
		$queryRes = $this->dbHandle->query($queryCmd,array($pageId));
		$row = $queryRes->row();
		$response['templatePath'] = $row->templatePath;
		$response['pageType'] = $row->pageType;
	    return $response;
	}

	function getFormCompleteData($userId, $courseId){
		$this->initiateModel();
		if($courseId<=0 && $userId>0){	//Get the Master form of the user 
			$queryCmdI = "select onlineFormId,status,instituteSpecId from OF_UserForms where userId = ? and type='master' and formStatus = 'live'";
			$queryI = $this->dbHandle->query($queryCmdI,array($userId));
			$resultsI = $queryI->result_array();
			$onlineIds = '';
			foreach ($resultsI as $rowI){
				$onlineIds .= ($onlineIds=='')?$rowI['onlineFormId']:','.$rowI['onlineFormId'];
			}
            if($onlineIds!=''){
    			$queryCmd = "select * from OF_FormUserData where onlineFormId IN ( $onlineIds ) ";
	    		$query = $this->dbHandle->query($queryCmd);
		    	$results = $query->result_array();
            }
		}

		if($courseId>0 && $userId>0){	//Get the course form of the user
			$queryCmdI = "select onlineFormId,status,instituteSpecId from OF_UserForms where userId = ? and type='course' and courseId = ? and formStatus = 'live'";
			
			$queryI = $this->dbHandle->query($queryCmdI,array($userId,$courseId));
			$resultsI = $queryI->result_array();
			$onlineIds = '';
			foreach ($resultsI as $rowI){
				$onlineIds .= ($onlineIds=='')?$rowI['onlineFormId']:','.$rowI['onlineFormId'];
			}
			if($onlineIds!=''){
				$queryCmd = "select * from OF_FormUserData where onlineFormId IN ( $onlineIds ) ";
				$query = $this->dbHandle->query($queryCmd);
				$results = $query->result_array();
			}
		}
		$pageArray = array();
		$city =array();
		$country =array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				
				if($row['fieldName'] == 'city' || $row['fieldName'] == 'Ccity') {
					$city[] = $row['value'];
				}
				
				if($row['fieldName'] == 'country' || $row['fieldName'] == 'Ccountry') {
					$country[] = $row['value'];
				}
			        
			}
		}

		if (!empty($city)){
			$queryCmd = "SELECT city_name,city_id FROM countryCityTable WHERE city_id in (?)";
			$query = $this->dbHandle->query($queryCmd,array($city))->result_array();
			$cityMapping = array();
			foreach ($query as $data){
			$cityMapping[$data['city_id']] = strtoupper($data['city_name']) ; 
			}
		}
		


		if (!empty($country)){
			$queryCmd = "SELECT countryId,name FROM countryTable WHERE countryId in (?)";
			$query = $this->dbHandle->query($queryCmd,array($country))->result_array();
			$countryMapping = array();
			foreach ($query as $data){
				$countryMapping[$data['countryId']] = strtoupper($data['name']) ; 
			}
		}


		foreach ($results as $row) {
			if($row['fieldName'] == 'city' || $row['fieldName'] == 'Ccity') {
					$row['value'] = $cityMapping[$row['value']];
				}
				
				if($row['fieldName'] == 'country' || $row['fieldName'] == 'Ccountry') {
					$row['value'] = $countryMapping[$row['value']];
				}
			$row['value'] = htmlspecialchars_decode($row['value']);
			$pageArray[$row['fieldName']] = $row['value'];
		}
		$temp = array('formUserData'=>$pageArray,'userFormData'=>$resultsI);
		return $temp;
	}

	public function checkValidCourse($courseId,$checkForExpire,$checkForInternal){
        $this->initiateModel();
		$checkExpire = '';
		$checkInternal = '';
		if($checkForExpire=='true'){
			$checkExpire = ' AND last_date>=DATE(now()) ';
		}
                if($checkForInternal=='true'){
                        $checkInternal = ' AND (externalURL = "" OR externalURL IS NULL) ';
                }

                $queryCmd = "SELECT last_date,courseId  
                                        FROM OF_InstituteDetails 
                                        WHERE (courseId = '$courseId') $checkExpire $checkInternal
                                        AND `status` = 'live'";
                $query = $this->dbHandle->query($queryCmd);
                $result = $query->result_array();

                if(empty($result)){

                	$queryCmd = "SELECT last_date,courseId
                                        FROM OF_InstituteDetails 
                                        WHERE (otherCourses LIKE ?) $checkExpire $checkInternal
                                        AND `status` = 'live'";
	                $query = $this->dbHandle->query($queryCmd,array('%'.$courseId.'%'));
	                $result = $query->result_array();
                }
                return $result;
        }

}

?>

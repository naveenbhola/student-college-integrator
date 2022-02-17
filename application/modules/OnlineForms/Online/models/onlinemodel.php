<?php
/* 
    Model for database related operations related to Online forms.
*/
class OnlineModel extends OnlineParentModel {
		
	function getPagesWithNoTemplate(){
		$this->initiateModel();
		$queryCmd = "SELECT pageId ".
					"FROM OF_PageList ".
					"WHERE templatePath IS NULL ".
					"AND status = 'live'";
		$queryRes = $this->dbHandle->query($queryCmd);
		$response = array();
	        foreach ($queryRes->result_array() as $row){
	            $response[] = $row;
	        }
	        return $response;
	}

	function getPageDetails($pageId){
		$this->initiateModel();
		$queryCmd = "SELECT * ".
					"FROM OF_PageList ".
					"WHERE pageId = ?";
		$queryRes = $this->dbHandle->query($queryCmd,array($pageId));
		$response = $queryRes->row_array();
		return $response;
	}

	function checkIfOtherCourse($courseId){
		$this->initiateModel();
 	
 		$queryCmd = "SELECT courseId as count
                                        FROM OF_InstituteDetails 
                                        WHERE courseId = ? AND `status` = 'live' and last_date >= now()";
        $query = $this->dbHandle->query($queryCmd, array($courseId));
        $result = $query->result_array();
       	return $result;
    }
	
	function getOnlineInstituteInfo($courseId,$isOtherCourse=0){
		$this->initiateModel();
		$AND = ($isOtherCourse==1)? "AND  of.otherCourses like '%$courseId%'" : "AND of.courseId = sc.course_id";
		$queryCmd = "SELECT distinct sc.name as courseTitle, st.listing_id as institute_id,st.name as institute_name, ". 
						"of.courseId, of.discount,of.logoImage logo_link, of.fees, of.min_qualification,of.instituteDisplayText, of.courseCode, of.basicInformation, of.documentsRequired, of.last_date,".
						"of.demandDraftInFavorOf,of.demandDraftPayableAt,".
						"of.sessionYear, of.departmentName ".
					"FROM shiksha_courses sc, shiksha_institutes st, OF_InstituteDetails of ".
					"WHERE sc.status = 'live' ".
					"AND sc.course_id = ? ".
					"AND st.status = 'live' ".
					"AND st.listing_id = sc.primary_id ".
					//"AND of.courseId = cd.course_id ".
					"AND of.status = 'live' AND of.instituteId = st.listing_id";
		//city name to be fetched here with new logic
		$queryRes = $this->dbHandle->query($queryCmd,array($courseId));
		
		$response = array();
		foreach ($queryRes->result_array() as $row){
			$fee = (int) $row['fees'];
			$discount = (int) $row['discount'];
			$actualFees = $fee - (($fee * $discount)/100);
			$row['actualFees'] = $actualFees;
			$response[] = $row;
		}

		return $response;
	}

	function getPageDataForTemplate($pageId){
		$this->initiateModel();

		$queryCmd = "SELECT pageName, pageType ".
					"FROM OF_PageList ".
					"WHERE pageId = ? ".
					"AND status = 'live'";
		$queryRes = $this->dbHandle->query($queryCmd,array($pageId));
		$pageInfo = array();
		foreach ($queryRes->result_array() as $row){
			$pageInfo[] = $row;
		}

		$response = array();
		$response['pageInfo'] = $pageInfo;

		$queryCmd = "SELECT DISTINCT entitySetId, entitySetType, entityOrder ".
					"FROM OF_PageEntityMapping ".
					"WHERE status = 'live' ".
					"AND pageId = ? ".
					"ORDER BY entityOrder";
		$queryRes = $this->dbHandle->query($queryCmd,array($pageId));
		$OFArray = array();
		$i=0;
		
		foreach ($queryRes->result_array() as $row){
			//Now, for each field/group, get all its details
			$entityId = $row['entitySetId'];
			$entityType = $row['entitySetType'];
			if($entityType=='field'){
				$queryCmdF = "SELECT fl.*, fpv.values, fv.* ".
							"FROM OF_FieldsList fl ".
							"LEFT JOIN OF_FieldPrefilledValues fpv ON fl.fieldId = fpv.fieldId ".
							"LEFT JOIN OF_FieldValidations fv on fl.fieldId = fv.fieldId ".
							"WHERE fl.fieldId = ? ";
				
				$queryF = $this->dbHandle->query($queryCmdF,array($entityId));
				$fields = array();
				foreach ($queryF->result_array() as $rowF){
					$fields[] = $rowF;
				}
				$row['details'] = $fields;
			}
			else if($entityType=='group'){
				$queryCmdG = "SELECT gl.groupId, gl.groupName, gl.allowMultiple, gl.maxMultiplesAllowed, fl.*, fpv.values, fv.* ".
							"FROM OF_GroupList gl, OF_FieldsList fl ".
							"LEFT JOIN OF_FieldPrefilledValues fpv on fl.fieldId = fpv.fieldId ".
							"LEFT JOIN OF_FieldValidations fv on fl.fieldId = fv.fieldId  ".
							"WHERE gl.groupId = ? ".
							"AND fl.fieldId = gl.fieldId ".
							"AND fl.fieldId ".
							"AND gl.status = 'live'";
				$queryG = $this->dbHandle->query($queryCmdG,array($entityId));
				$groupFields = array();
				foreach ($queryG->result_array() as $rowG){
					$groupFields[] = $rowG;
				}
				$row['details'] = $groupFields;
			}
			$OFArray[$i] = $row;
			$i++;
		}

		$response['pageTemplate'] = $OFArray;
	    return $response;
	}

	function updateTemplatePath($pageId, $templatePath){
		$this->initiateModel('write');
		$queryCmd = "UPDATE OF_PageList ".
					"SET templatePath = ? ".
					"WHERE pageId = ?";
		$queryRes = $this->dbHandle->query($queryCmd,array($templatePath,$pageId));
		$response = 1;
	    return $response;
	}

	function getFieldValidations($fieldList){
		$this->initiateModel();
		$queryCmd = "SELECT OF_FieldValidations.* , OF_FieldsList.name, OF_FieldsList.required ".
					"FROM OF_FieldValidations,OF_FieldsList ".
					"WHERE OF_FieldValidations.fieldId = OF_FieldsList.fieldId ".
					"AND OF_FieldsList.name IN ($fieldList)";
		$queryRes = $this->dbHandle->query($queryCmd);
		$response = array();
		foreach ($queryRes->result_array() as $row){
			$response[] = $row;
		}
		return $response;
	}

	function getInfoForPageToBeDisplayed($userId, $courseId){
		$this->initiateModel();
		
		if($courseId==0){	//The user is filling his master form
		    $response = $this->checkForMasterPages($userId);
		}

		elseif ($courseId > 0){		//The user is applying on a course
		    //First, check if the user has filled his Master form
		    $response = $this->checkForMasterPages($userId,$courseId);
		    if($response[0]=='DASHBOARD'){	//Means the user has filled his Master form 
				//This will return the details of the Custom form for this course
				$queryCmd = "SELECT pl.*, pmf.* ".
							"FROM OF_ListForms lf, OF_PageMappingInForm pmf, OF_PageList pl ".
							"WHERE lf.courseId = ? ".
							"AND lf.status='live' ".
							"AND pmf.formId = lf.formId ".
							"AND pmf.status = 'live' ".
							"AND pmf.pageId NOT IN (select pageId from OF_PageList where pageType='baseMBA' and status='live') ".
							"AND pmf.pageId = pl.pageId ".
							"AND pl.status = 'live'";
				
				$queryR = $this->dbHandle->query($queryCmd,array($courseId));
				$response = array();
				$pageId = '';
				foreach ($queryR->result_array() as $row){
					$pageId = $row['pageId'];
					$response[] = $row;
				}
				
				if(!empty($pageId)){
					//Now, check if the user has filled the custom form for this course
					$queryCmd = "SELECT * ".
								"FROM OF_FilledPageMappingInForm ".
								"WHERE userId = ? ".
								"AND pageId IN ($pageId)";
					
					$queryR = $this->dbHandle->query($queryCmd,array($userId));
					$rowNum = $queryR->num_rows();	
				}  
				//If no rows are found, it means he has to fill the custom form
				//If rows are found, it means he has filled the custom form
				if($rowNum>0){
					//If he has also filled the custom form for this institute, check if the payment is done. If no, return PAYMENT
					$onlineFormId = '';
					foreach ($queryR->result_array() as $row){
						$onlineFormId = $row['onlineFormId'];
					}
					$queryCmd = "SELECT * ".
								"FROM OF_UserForms ".
								"WHERE onlineFormId = ? ";
					$queryR = $this->dbHandle->query($queryCmd,array($onlineFormId));
					$row = $queryR->row();
					if($row->status == 'started' || $row->status == 'uncompleted' || $row->status == 'completed') {
						$response[] = 'PAYMENT';
					}
					else{
						$response[] = 'DASHBOARD';
					}
				}
		    }
		}
	    return $response;
	}

	function checkForMasterPages($userId,$courseId=0){
		$this->initiateModel();
		$response = array();

		// CHeck if the user has filled the Base type pages. If no, return the first base type page details
		//First, check if the user has ever started filling his master form
		$queryCmd = "SELECT DISTINCT pl.* ".
					"FROM OF_FilledPageMappingInForm fpmf, OF_PageList pl ".
					"WHERE userId = ? ".
					"AND pl.pageType='baseMBA' ".
					"AND pl.status='live' ".
					"AND fpmf.pageId = pl.pageId";
		
		$queryR = $this->dbHandle->query($queryCmd,array($userId));
		$rowNum = $queryR->num_rows();

		//If not, it means he has to start filling from the very first page
		if($rowNum<=0){
			$queryCmd = "SELECT pmf.*,pl.pageName,pl.templatePath ".
						"FROM OF_PageMappingInForm pmf, OF_ListForms lf, OF_PageList pl ".
						"WHERE pmf.formId = lf.formId ".
						"AND pmf.status = 'live' ".
						"AND pmf.pageOrder = 1 ".
						"AND lf.formName like 'MBAMaster' ".
						"AND pl.status = 'live' ".
						"AND pl.pageId = pmf.pageId";
			$queryP = $this->dbHandle->query($queryCmd);
			foreach ($queryP->result_array() as $row){
			    $response[] = $row;
			}
		}
		else{	
			//If yes, find how many pages he has filled. If 1 is filled, then return 2 and so on. If all are filled, return Dashboard
			$pageList = '';
			foreach ($queryR->result_array() as $row){
			    $pageList .= ($pageList=='')?$row['pageId']:",".$row['pageId'];
			}
			$queryCmd = "SELECT pmf.*,pl.pageName,pl.templatePath ".
						"FROM OF_PageMappingInForm pmf, OF_ListForms lf, OF_PageList pl ".
						"WHERE pmf.formId = lf.formId ".
						"AND pmf.status = 'live' ".
						"AND lf.formName like 'MBAMaster' ".
						"AND pl.status = 'live' ".
						"AND pl.pageId = pmf.pageId ".
						"AND pl.pageId NOT IN ($pageList) ".
						"ORDER BY pageOrder Limit 1";
		
			$queryP = $this->dbHandle->query($queryCmd);
			$rowNumP = $queryP->num_rows();
			if($rowNumP<=0)	//All the master pages have been filled by user
			      $response[] = 'DASHBOARD';
			else{
			      $response = array();
			      foreach ($queryP->result_array() as $row){
				  $response[] = $row;
			    }
			}

			// If this is a course page, we have to copy the master pages (whichever user has already filled) to the course pages.
			if($courseId>0){
			      $this->copyMasterPagesToCourse($userId,$courseId);
			}

		}
		return $response;
	}


	function copyMasterPagesToCourse($userId,$courseId){
        $this->initiateModel('write');
        //Add the initial entry for course to assign the OnlineFormId
        $this->setUserStartingForm($userId, $courseId, 0, false);
        
        //First check if Master form is filled and how many pages are filled
        $queryCmd = "SELECT * ".
                    "FROM OF_UserForms uf, OF_FilledPageMappingInForm fpm ".
                    "WHERE uf.type = 'master' ".
                    "AND uf.userId = ? ".
                    "AND uf.userId = fpm.userId and uf.onlineFormId = fpm.onlineFormId and uf.formStatus = 'live'";
        
        $queryP = $this->dbHandle->query($queryCmd,array($userId));
        $rows = $queryP->num_rows();

        // If the master form is filled, we will fill all the previous pages of this course form with these master pages
        if($rows>0){
            foreach ($queryP->result_array() as $row){
                //Now for each page filled for Master form, check if that page is also filled for Course form.
                // If not copy the same to Course form and also make an entry in OF_FilledPageMappingInForm.
                // If already filled in course form, then do nothing.
                $response[] = $row;
    
                $queryCmd = "SELECT pageOrder ".
                            "FROM OF_PageMappingInForm ".
                            "WHERE pageId = ? ".
                            "AND formId IN (SELECT formId FROM OF_ListForms WHERE formName = 'MBAMaster' AND status = 'live' )";
                $queryPO = $this->dbHandle->query($queryCmd,array($row['pageId']));
                $rowOrder = $queryPO->row();
                $order = $rowOrder->pageOrder;
    
                //Now get the page for the course form with the same Page order
                $queryCmd = "SELECT onlineFormId, pageId ".
                            "FROM OF_PageMappingInForm pmf, OF_ListForms lf, OF_UserForms uf ".
                            "WHERE pmf.pageOrder = ? ".
                            "AND pmf.formId = lf.formId ".
                            "AND lf.courseId = ? ".
                            "AND lf.courseId = uf.courseId ".
                            "AND uf.userId = ? AND uf.formStatus = 'live'";
                $queryPO = $this->dbHandle->query($queryCmd,array($order,$courseId,$userId));
                $rowPage = $queryPO->row();
                $pageIdCourse = $rowPage->pageId;
                $onlineFormIdCourse = $rowPage->onlineFormId;
    
                //Check if this page has been already filled
                $queryCmd = "SELECT * ".
                            "FROM OF_FilledPageMappingInForm ".
                            "WHERE onlineFormId = ? ".
                            "AND userId = ? ".
                            "AND pageId = ?";
                $queryPO = $this->dbHandle->query($queryCmd,array($onlineFormIdCourse,$userId,$pageIdCourse));
                //If the page has not been filled, then fill the page with the master form
                if($queryPO->num_rows() <= 0){
                    //Now, get the values of the master page
                    $queryCmd = "SELECT * ".
                                "FROM OF_FormUserData ".
                                "WHERE onlineFormId = ? ".
                                "AND pageId = ? ".
                                "AND userId = ?";
                    $queryDataMaster = $this->dbHandle->query($queryCmd,array($row['onlineFormId'],$row['pageId'],$userId));
                    foreach ($queryDataMaster->result_array() as $rowData){
                        $insertData = array(
                            'onlineFormId' => $onlineFormIdCourse,
                            'userId' => $userId,
                            'pageId' => $pageIdCourse,
                            'value' => $rowData['value'],
                            'isMultipleCase' => $rowData['isMultipleCase'],
                            'fieldName' => $rowData['fieldName'],
                            'fieldId' => $rowData['fieldId']
                        );
                        
                        $this->dbHandle->insert('OF_FormUserData',$insertData);
                    }
                    //Now, also add the entry in Forms Filled table
                    $insertData = array(
                        'onlineFormId' => $onlineFormIdCourse,
                        'userId' => $userId,
                        'pageId' => $pageIdCourse
                    );
                    $this->dbHandle->insert('OF_FilledPageMappingInForm',$insertData);
                    
                    //Also, set the entry of Course form from Started to Uncompleted
                    $queryCmd = "UPDATE OF_UserForms set status = 'uncompleted' where courseId = ? and onlineFormId = ?";
                    $queryInsert = $this->dbHandle->query($queryCmd,array($courseId,$onlineFormIdCourse));
                }
            }
        }
    }

	function getPageDataForEdit($courseId, $userId, $pageId){
		$this->initiateModel();
		$type = ($courseId==0)?'master':'course';
		
		/*$queryCmd = "SELECT * ".
					"FROM OF_FormUserData ".
					"WHERE userId = ? ".
					"AND pageId = ? ".
					"AND onlineFormId IN (SELECT onlineFormId FROM OF_UserForms WHERE courseId = ? AND userId = ? AND type = ? AND formStatus = 'live')";
		*/
		$queryCmd = "SELECT fu.* FROM OF_FormUserData fu, OF_UserForms uf WHERE uf.onlineFormId = fu.onlineFormId and fu.userId = ? AND fu.pageId = ? AND uf.courseId = ? AND uf.userId = ? AND uf.type = ? AND uf.formStatus = 'live' ";
		
		$queryRes = $this->dbHandle->query($queryCmd,array($userId,$pageId,$courseId,$userId,$type));
		$response = array();
		$userFilledValues = array();
		foreach ($queryRes->result_array() as $row){
			$row['value'] = htmlspecialchars_decode($row['value']);
			$response[] = $row;
			$userFilledValues[$row['fieldId']] = $row['value'];
		}

		/*
		 * Check if there is an unfilled field that is linked to a master field
		 * If yes, pick master fields' value for this
		 */ 
		if($type == 'course') {
			$queryCmd = "SELECT pem.entitySetId,fl.parentId,fl.name ".
						"FROM OF_PageEntityMapping pem ".
						"LEFT JOIN OF_FieldsList fl ON fl.fieldId = pem.entitySetId ". 
						"WHERE pem.pageId = ? ".
						"AND pem.entitySetType = 'field' ".
						"AND pem.status = 'live' ".
						"AND fl.parentId IS NOT NULL";
			$queryRes = $this->dbHandle->query($queryCmd,array($pageId));
			
			$fieldParentMapping = array();
			foreach($queryRes->result() as $row) {
				
				if(!isset($userFilledValues[$row->entitySetId])) {
					/*$queryCmd = "SELECT * ".
								"FROM OF_FormUserData ".
								"WHERE userId = ? ".
								"AND fieldId = ? ".
								"AND onlineFormId IN (SELECT onlineFormId FROM OF_UserForms WHERE courseId = ? AND userId = ? AND type = ? and formStatus = 'live')";*/
					$queryCmd = "SELECT fu.* FROM OF_FormUserData fu, OF_UserForms uf WHERE uf.onlineFormId = fu.onlineFormId and fu.userId = ? AND fu.fieldId = ? AND uf.courseId = ? AND uf.userId = ? AND uf.type = ? AND uf.formStatus = 'live'";
					$result = $this->dbHandle->query($queryCmd,array($userId,$row->parentId,$courseId,$userId,$type));
					$resultRow = $result->row_array();
					$resultRow['fieldId'] = $row->entitySetId;
					$resultRow['fieldName'] = $row->name;
					$response[] = $resultRow;
				}
			}
		}
		
		return $response;
	}

	function setUserStartingForm($userId, $courseId, $formId, $sendInternalMailer = true){
		$this->initiateModel('write');

		//Fetch the session year from the DB for the courseId selected. In case of Master form, it doesn't matter with the session year
		if($courseId>0){
			$queryCmd = "select sessionYear from OF_InstituteDetails where (courseId = ? or otherCourses LIKE '%$courseId%') and status = 'live' ";
			$queryP = $this->dbHandle->query($queryCmd, array($courseId));
			$row = $queryP->row();
			$sessionYear = $row->sessionYear;
		}

		//Check if any entry is already present
		//If courseId is 0, it means user has started filling his Master form
		if($courseId==0){
		    $queryCmd = "select * from OF_UserForms where userId = ? and type='master' and formStatus = 'live' ";
			$queryP = $this->dbHandle->query($queryCmd,array($userId));
		}
		else{
		    $queryCmd = "select * from OF_UserForms where userId = ? and type='course' and courseId = ? and formStatus = 'live' ";
			$queryP = $this->dbHandle->query($queryCmd,array($userId,$courseId));
		}

		$rowNumP = $queryP->num_rows();
		//If no entry is found, insert one
		if($rowNumP<=0) {
		    if($courseId==0){
				$insertData = array(
					'userId' => $userId,
					'type' => 'master',
					'status' => 'started',
					'courseId' => 0,
					'sessionYear' => 2013
				);

		    }
		    else{
				$insertData = array(
					'userId' => $userId,
					'type' => 'course',
					'status' => 'started',
					'courseId' => $courseId,
					'sessionYear' => $sessionYear
				);
		    }

			$queryRes = $this->dbHandle->insert('OF_UserForms',$insertData);
		    $onlineFormId = $this->dbHandle->insert_id();

                    //Send an internal mailer to the Team
		    if($sendInternalMailer){
                    	$this->sendInternalMailerForInforming($userId,$courseId,'started');
		    }

		}
		else{
		    $row = $queryP->row();
		    $onlineFormId = $row->onlineFormId;
		}

	    return $onlineFormId;
	}

	private function _updateFormStatus($data,$userId)
	{
		/*
		 * get type of form
		 */
		$queryCmd = "select type,status from OF_UserForms where onlineFormId = ? and userId = ? and formStatus = 'live' ";
		$query = $this->dbHandle->query($queryCmd,array($data['onlineFormId'],$userId));
		$row = $query->row();
		$formType = $row->type;
		$formStatus = $row->status;
		
		if(!$formStatus || $formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')
		{
			/*
			 * No. of filled pages by the user for this form
			 */ 
			$queryCmd = "select id from OF_FilledPageMappingInForm where userId = ? and onlineFormId = ?";
			$query = $this->dbHandle->query($queryCmd,array($userId,$data['onlineFormId']));
			$numPagesFilled = $query->num_rows();
			
			$status = 'uncompleted';
			
			if(($formType=='master' && $numPagesFilled == 3) || ($formType=='course' && $numPagesFilled == 4)){
				$status = 'completed';
			}
			
			$this->setFormStatus($data['onlineFormId'],$userId,$status);
		}
	}


	function setFormData($data, $userId){

		$this->initiateModel('write');
		
		$data = json_decode($data,true);
	        if(isset($data['courseId']) && $data['courseId']>0)
	            $type = 'course';
        	else
	            $type = 'master';


		// 3. Add the mapping in OF_FilledPageMappingInForm
		$queryCmd = "INSERT INTO OF_FilledPageMappingInForm (onlineFormId,pageId,userId) ".
					"VALUES (?,?,?) ON DUPLICATE KEY Update creationDate = now()";
		$queryRes = $this->dbHandle->query($queryCmd,array($data['onlineFormId'],$data['pageId'],$userId));
		
		$this->_updateFormStatus($data,$userId);

		/*============= optimization starts here =========================*/	

		$bulk_insert_string = "INSERT INTO OF_FormUserData (onlineFormId, userId, pageId, value, isMultipleCase, fieldName) ".
		"VALUES ";

		$flag_for_multivalue_query = 0;
		$flag_for_empty_data = 0;

		// 4. Add the data in the table OF_FormUserData
		// Take care of Allow multiple fields and fields in both base and custom forms
		foreach ($data as $key=>$value){
		      if(is_array($value)){	//In case of CheckBox
			    $valTemp = '';
			    for($i=0; $i < count($value); $i++)
				$valTemp .= ($valTemp=='')?$value[$i]:','.$value[$i];
			    $value = $valTemp;
				$data[$key] = $value;
		      }


		      
		      //Check if this is case of Multiple
		      $valArr = array();
		      $valArr = explode("_mul_",$key);
		      if(count($valArr)>1 && $value!=''){	//Yes, this is a case of Multiple entry
		      	if($data['courseId']>0)
		      	{
		      		$value = str_replace("'","\'", $value);
		  			$value = str_replace('"','\"', $value);
		  		}

			    $bulk_insert_string .= '('.$data['onlineFormId'].','.$userId.','.$data['pageId'].',"'.$value.'",'."1".',"'.$key.'"),';
			    $flag_for_multivalue_query = 1;
		      }
		      else if($value!='')
		      {
		      	$field_name_array[] = $key;
		      	$flag_for_empty_data = 1;
		      }

		      /*============= optimization end here =========================*/	

		      /*=============== old code before optimization starts here ==============*/
		      /*else if($value!=''){
			    $queryCmd = "select fieldId from OF_FieldsList where name = ?";
			    $queryP = $this->dbHandle->query($queryCmd,array($key));
			    $rowNumP = $queryP->num_rows();
			    if($rowNumP>0){
				  $row = $queryP->row();
				  $fieldId = $row->fieldId;


	                if(!($key == 'profileImage' &&  $data['pageId']!=1)) {
					  $queryCmd = "REPLACE INTO OF_FormUserData (onlineFormId, userId, pageId, value, isMultipleCase, fieldName, fieldId) ".
								  "VALUES (?,?,?,?,?,?,?)";
					  $queryP = $this->dbHandle->query($queryCmd,array($data['onlineFormId'],$userId,$data['pageId'],$value,0,$key,$fieldId));
				    }
	                

	                if($key == 'profileImage' &&  $data['pageId']!=1) {
							$queryCmd = "update OF_FormUserData set value = ? where onlineFormId=? and userId=? and pageId=1 and fieldId=?";
							$queryP = $this->dbHandle->query($queryCmd,array($value,$data['onlineFormId'],$userId,$fieldId));
							$afffected = $this->dbHandle->affected_rows();
							if($afffected == 0) {
								$queryCmd = "REPLACE INTO OF_FormUserData (onlineFormId, userId, pageId, value, isMultipleCase, fieldName, fieldId) ".
								  "VALUES (?,?,?,?,?,?,?)";
					  			$queryP = $this->dbHandle->query($queryCmd,array($data['onlineFormId'],$userId,1,$value,0,$key,$fieldId));
							}
					}
			    }
		    }*/
		    /*=============== old code before optimization end here ==============*/

		}

		// _P($data);die;
		if($flag_for_multivalue_query==1)
		{
			$bulk_insert_string = substr($bulk_insert_string, 0,-1);
			$bulk_insert_string .= " ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)";
			$this->dbHandle->query($bulk_insert_string);
		}


		/*============= optimization starts here =========================*/	

		$sql = "select fieldId, type, name from OF_FieldsList where name in (?) ";
		$sql_result = $this->dbHandle->query($sql,array($field_name_array))->result_array();
		if(!empty($sql_result) && $flag_for_empty_data==1)
		{
		
			/*_P($sql_result);die;*/

			$bulk_insert_string = "INSERT INTO OF_FormUserData (onlineFormId, userId, pageId, value, isMultipleCase, fieldName, fieldId) ".
			"VALUES ";


		$is_multi_value = 0;
		foreach ($sql_result as $sql_row) {
			if($data['courseId']>0)
			{
				$data[$sql_row['name']] = str_replace("'","\'", $data[$sql_row['name']]);
		  		$data[$sql_row['name']] = str_replace('"','\"', $data[$sql_row['name']]);
			}


				if(!($sql_row['name'] == 'profileImage' &&  $data['pageId']!=1)){
					$bulk_insert_string .= '('.$data['onlineFormId'].','.$userId.','.$data['pageId'].',"'.$data[$sql_row['name']].'",'.$is_multi_value.',"'.$sql_row['name'].'",'.$sql_row['fieldId'].'),';

			  	}

			  	if($sql_row['name'] == 'profileImage' &&  $data['pageId']!=1) {
						
						$queryCmd = "update OF_FormUserData set value = ? where onlineFormId=? and userId=? and pageId=1 and fieldId=?";
						$queryP = $this->dbHandle->query($queryCmd,array($data[$sql_row['name']],$data['onlineFormId'],$userId,$sql_row['fieldId']));

						$afffected = $this->dbHandle->affected_rows();

						if($afffected == 0) {
							$queryCmd = "REPLACE INTO OF_FormUserData (onlineFormId, userId, pageId, value, isMultipleCase, fieldName, fieldId) ".
							  "VALUES (?,?,?,?,?,?,?)";
				  			$queryP = $this->dbHandle->query($queryCmd,array($data['onlineFormId'],$userId,1,$data[$sql_row['name']],0,$sql_row['name'],$sql_row['fieldId']));
						}
				}

			  }

			$bulk_insert_string = substr($bulk_insert_string, 0,-1);
			$bulk_insert_string .= " ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)";
			$this->dbHandle->query($bulk_insert_string);
		}

		/*============= optimization ends here =========================*/	

		

		//After the data is filled for this form, we will check if this if a Course form. 
		// If this is a course form, there are two cases: 
		// 1. If master form is not filled, copy the course fields into the fields in master form with each form submission of master pages.
		//    Also, in this case enter proper entries in OF_UserForms and OF_FilledPageMappingInForm
		// 2. If master form is already filled and this is the custom form, then copy the master fields to this form.
		// There could also be cases like 1st Page of Master form is filled, but then he started filling course form
		if($type!='master'){

			//First check if Master form is filled and how many pages are filled
			$queryCmd = "select * from OF_UserForms uf, OF_FilledPageMappingInForm fpm where uf.type = 'master' and uf.userId = ? and uf.userId = fpm.userId and uf.onlineFormId = fpm.onlineFormId and uf.formStatus = 'live'";
			$queryP = $this->dbHandle->query($queryCmd,array($userId));
			$rows = $queryP->num_rows();

			//Check if the page Id is 1,2 or 3, it means this is a master page and we will add an entry for the Master form also.
			$queryCmdPage = "select pageOrder from OF_PageMappingInForm where pageId = ? and status = 'live' and formId IN (select formId from OF_ListForms where courseId = ? and status = 'live')";
			$queryPage = $this->dbHandle->query($queryCmdPage,array($data['pageId'],$data['courseId']));
			$rowPage = $queryPage->row();
			$order = $rowPage->pageOrder;

			// If the master form is not filled, we will start filling the master pages with these course master pages
			if($rows<3 && ($order==1 || $order==2 || $order==3) ){
				  //Set the initial entry in OF_UserForms for Master form of this user
				  $masterOnlineFormId = $this->setUserStartingForm($userId, 0, 0, false);

				  //Now, add the data in this Master form page and add according entries in OF_FilledPageMappingInForm and OF_UserForms
				  $masterData = $data;
                  $masterData['onlineFormId'] = $masterOnlineFormId;
                  $masterData['courseId'] = 0;
				  $this->setFormData(json_encode($masterData), $userId);
			}

			//Added on 24Sept, 2012			
			//Now, after the form data is set, we have to check one more thing.
			//If this is custom page and if we have any field in this which has a mapping with any master field, we have to update that field in the master form.
			//First, get all the fields with any mapping with the Master fields.
			$queryCmd = "select fl.fieldId, fl.name, fl.parentId, fu.value from OF_PageEntityMapping pm, OF_FieldsList fl, OF_FormUserData fu where pm.pageId = ? and pm.status = 'live' and pm.entitySetId = fl.fieldId and pm.entitySetType='field' and parentId!='' and fu.fieldId=fl.fieldId and fu.userId=? and fu.onlineFormId = ? ";
			$queryRes = $this->dbHandle->query($queryCmd,array($data['pageId'],$userId,$data['onlineFormId']));
			foreach($queryRes->result() as $row) {
                                $queryCmd = "select value from OF_FormUserData where onlineFormId=? and userId=? and fieldId=?";
                                $queryP = $this->dbHandle->query($queryCmd,array($data['onlineFormId'],$userId,$row->parentId));
                                if($queryP->num_rows()>0){
                                        //Update the value of the parent field
                                        $queryCmd = "update OF_FormUserData set value = ? where onlineFormId= ? and userId= ? and fieldId= ?";
                                        $queryUpdate = $this->dbHandle->query($queryCmd,array($row->value,$data['onlineFormId'],$userId,$row->parentId));
                                }
                                else{
                                        //Get the name and page for the Parent Field
                                        $queryCmd = "select name,pageId from OF_FieldsList,OF_PageEntityMapping where fieldId = ? and fieldId=entitySetId and status='live'";
                                        $querySelect = $this->dbHandle->query($queryCmd,array($row->parentId));
                                        $rowV = $querySelect->row();
                                        //Insert the value of the parent field
                                        $queryCmd = "INSERT INTO OF_FormUserData (onlineFormId,userId,pageId,fieldId,value,fieldName) VALUES (?,?,?,?,?,?)";
                                        $queryInsert = $this->dbHandle->query($queryCmd,array($data['onlineFormId'],$userId,$rowV->pageId,$row->parentId,$row->value,$rowV->name));
                                }
			}

		}

		return 1;
	}

	function getFormData($userId,$onlineFormId)
	{
		$this->initiateModel();
		$queryCmd = "SELECT uf.*,inst.instituteId ".
					"FROM OF_UserForms uf ".
					"LEFT JOIN OF_InstituteDetails inst ON inst.courseId = uf.courseId ".
					"WHERE uf.onlineFormId = ? ".
					"AND uf.userId = ?";
		$queryRes = $this->dbHandle->query($queryCmd,array($onlineFormId,$userId));
		
		$results = $queryRes->result_array();
		$form_data_array = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$form_data_array[] = $row;
			}
		}
		return $form_data_array;
	}
	
	function getFormDataByCourseId($userId,$courseId)
	{
		$this->initiateModel();
		$queryCmd = "SELECT * ".
					"FROM OF_UserForms  ".
					"WHERE courseId = ? ".
					"AND userId = ? AND formStatus = 'live' ";
		$queryRes = $this->dbHandle->query($queryCmd,array($courseId,$userId));
		
		$results = $queryRes->result_array();
		$form_data_array = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$form_data_array[] = $row;
			}
		}
		return $form_data_array;
	}

	function setFormStatus($onlineFormId,$userId,$status){
		$this->initiateModel('write');
		$queryCmd = "UPDATE OF_UserForms set status = ? where onlineFormId = ? and userId = ?";
		$queryRes = $this->dbHandle->query($queryCmd,array($status,$onlineFormId,$userId));
	}

	function getFieldsOnPage($pageId,$userId=0,$onlineFormId=0)
	{
		$this->initiateModel('write');
		$queryCmd = "select pem.entitySetId,pem.entitySetType,fl.name ".
					"from OF_PageEntityMapping pem ".
					"left join OF_FieldsList fl ON fl.fieldId = pem.entitySetId  ".
					"where pem.pageId = ? ".
					"and pem.status = 'live'";
					
		$queryRes = $this->dbHandle->query($queryCmd,array($pageId));
		
		$fieldsOnPage = array();
		foreach($queryRes->result() as $row)
		{
			if($row->entitySetType == 'group') {
				$queryCmd = "select gl.fieldId,fl.name ".
							"from OF_GroupList gl ".
							"left join OF_FieldsList fl ON fl.fieldId = gl.fieldId ".
							"where gl.groupId = ?";
					
				$results = $this->dbHandle->query($queryCmd,array($row->entitySetId));
				
				foreach($results->result() as $irow) {
					$fieldsOnPage[$irow->fieldId] = $irow->name;
				}
			}
			else {
				$fieldsOnPage[$row->entitySetId] = $row->name;
			}		
		}
		
		/*
		 * Also get fields with multiple property
		 */
		if($userId && $onlineFormId) {
			$queryCmd = "select fieldName ".
						"from OF_FormUserData ".
						"where pageId = ? ".
						"AND userId = ? ".
						"AND onlineFormId = ? ".
						"AND fieldName LIKE '%_mul_%'";
						
			$queryRes = $this->dbHandle->query($queryCmd,array($pageId,$userId,$onlineFormId));
			
			foreach($queryRes->result() as $row) {
				$fieldsOnPage[] = $row->fieldName;
			}
		}
		
		return $fieldsOnPage;
	}

	function updateFormData($data, $userId, $onlineFormId, $pageId, $action){
		$this->initiateModel('write');
		$data = json_decode($data,true);
		if(empty($data['onlineFormId']))
		{
			$data['onlineFormId'] = $onlineFormId;
		}

		if(empty($data['onlineFormId']) && empty($onlineFormId))
		{
			return;
		}
		// Update the data in the table OF_FormUserData
		// Take care of Allow multiple fields and fields in both base and custom forms
		
		/*
		 * For all the fields not submitted i.e. unchecked checkboxes
		 * Set value to blank
		 */ 
		if(isset($data['profileImage'])) {
			$profileImage = $data['profileImage'];
		}
		
		/*=============== old code before optimization starts here ==============*/

		/*$fieldsOnPage = $this->getFieldsOnPage($pageId,$userId,$onlineFormId);
		if(is_array($fieldsOnPage) && count($fieldsOnPage)) {
			$fieldsNotSubmitted = array_diff($fieldsOnPage,array_keys($data));
			foreach($fieldsNotSubmitted as $field) {
				if($field != 'profileImage') {
					$data[$field] = '';
				}
			}
		} */
		/*=============== old code before optimization ends here ==============*/

		if(!empty($profileImage)) {
			$data['profileImage'] = $profileImage;
		}


		/*============= optimization starts here =========================*/	

		$bulk_insert_string = "INSERT INTO OF_FormUserData (onlineFormId, userId, pageId, value, isMultipleCase, fieldName) ".
		"VALUES ";

		$flag_for_multivalue_query = 0;

		$page_id = $data['pageId'];
		foreach ($data as $key=>$value){
		      if(is_array($value)){	//In case of CheckBox
		      	$valTemp = '';
		      	for($i=0; $i < count($value); $i++) {
		      		$valTemp .= ($valTemp=='')?$value[$i]:','.$value[$i];
		      	}
		      	$value = $valTemp;

		      	$data[$key] = $value;
		      }

		      $field_name_array[] = $key;

		      $valArr = array();
		      $valArr = explode("_mul_",$key);
		      if(count($valArr)>1){	//Yes, this is a case of Multiple entry

		      	$value = str_replace("'","\'", $value);
		  		$value = str_replace('"','\"', $value);

			    $bulk_insert_string .= '('.$data['onlineFormId'].','.$userId.','.$data['pageId'].',"'.$value.'",'."1".',"'.$key.'"),';
			    $flag_for_multivalue_query = 1;
		      }

		      
		      /*=============== old code before optimization starts here ==============*/

		      //Check if this is case of Multiple
		      /*$valArr = array();
		      $valArr = explode("_mul_",$key);
		      if(count($valArr)>1){	//Yes, this is a case of Multiple entry
		      	$queryCmd = "INSERT INTO OF_FormUserData (onlineFormId, userId, pageId, value, isMultipleCase, fieldName) ".
		      	"VALUES (?,?,?,?,?,?) ".
		      	"ON DUPLICATE KEY UPDATE value = ?";
		      	if(!(empty($value) && $action == 'updateScore'))
		      		$queryP = $this->dbHandle->query($queryCmd,array($data['onlineFormId'],$userId,$data['pageId'],$value,1,$key,$value));
		      }else{

	
		      	$queryCmd = "select fieldId, type from OF_FieldsList where name = ?";
		      	$queryP = $this->dbHandle->query($queryCmd,array($key));
		      	$rowNumP = $queryP->num_rows();
		      	if($rowNumP>0){
		      		$row = $queryP->row();
		      		$fieldId = $row->fieldId;
		      		$type = $row->type;

		      		$insertData['isMultipleCase'] = 0;
		      		$insertData['fieldId'] = $fieldId;

		      		if($key == 'profileImage') {$data['pageId'] =1;} else {$data['pageId'] = $page_id;}

		      		if(!($type=='file' && $value=='')){					
		      			$queryCmd = "INSERT INTO OF_FormUserData (onlineFormId, userId, pageId, value, isMultipleCase, fieldName, fieldId) ".
		      			"VALUES (?,?,?,?,?,?,?) ".
		      			"ON DUPLICATE KEY UPDATE value = ?";

		      			if(!(empty($value) && $action == 'updateScore')){
		      				$queryP = $this->dbHandle->query($queryCmd,array($data['onlineFormId'],$userId,$data['pageId'],$value,0,$key,$fieldId,$value));
		      			}

		      			if($action == 'updateScore' && ($fieldId==1207 || $fieldId==1208)){
		      				$queryP = $this->dbHandle->query($queryCmd,array($data['onlineFormId'],$userId,$data['pageId'],$value,0,$key,$fieldId,$value));

		      			}
		      		}
		      	}
		      }*/

		      /*=============== old code before optimization ends here ==============*/

			}


		if($flag_for_multivalue_query==1)
		{
			$bulk_insert_string = substr($bulk_insert_string, 0,-1);
			$bulk_insert_string .= " ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)";
			$this->dbHandle->query($bulk_insert_string);
		}

		$flag_for_score_update = 0;

		  $sql = "select fieldId, type, name from OF_FieldsList where name in (?) ";
		  $sql_result = $this->dbHandle->query($sql,array($field_name_array))->result_array();

		  $bulk_insert_string = "INSERT INTO OF_FormUserData (onlineFormId, userId, pageId, value, isMultipleCase, fieldName, fieldId) ".
		      					"VALUES ";

		  foreach ($sql_result as $sql_row) {
		  	
		  	if($sql_row['name'] == 'profileImage') {
		  		$data['pageId'] =1;
		  	} else {
		  		$data['pageId'] = $page_id;
		  	}

		  	$valArr = array();
		    $valArr = explode("_mul_",$sql_row['name']);
		    $is_multi_value = 0;
		    if(count($valArr)>1){
		    	$is_multi_value = 1;
		    }


		  	if(!($sql_row['type'] == 'file' && $data[$sql_row['name']] =='')){

		  		$data[$sql_row['name']] = str_replace("'","\'", $data[$sql_row['name']]);
		  		$data[$sql_row['name']] = str_replace('"','\"', $data[$sql_row['name']]);

		  		if(!(empty($data[$sql_row['name']]) && $action == 'updateScore')){
		  			$flag_for_score_update = 1;
		  			$bulk_insert_string .= '('.$data['onlineFormId'].','.$userId.','.$data['pageId'].',"'.$data[$sql_row['name']].'",'.$is_multi_value.',"'.$sql_row['name'].'",'.$sql_row['fieldId'].'),';
		  		}

		  		if($action == 'updateScore' && ($sql_row['fieldId']==1207 || $sql_row['fieldId']==1208)){
		  			$flag_for_score_update = 1;
      				$bulk_insert_string .= '('.$data['onlineFormId'].','.$userId.','.$data['pageId'].',"'.$data[$sql_row['name']].'",'.$is_multi_value.',"'.$sql_row['name'].'",'.$sql_row['fieldId'].'),';
      			}

		  	}
		  			
		  }

		  if($flag_for_score_update==1)
		  {
		  	$bulk_insert_string = substr($bulk_insert_string, 0,-1);
			$bulk_insert_string .= " ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)";
			$this->dbHandle->query($bulk_insert_string);

			$this->_updateFormStatus($data,$userId);

		  }

		
		//Added on 24Sept, 2012			
		//Now, after the form data is set, we have to check one more thing.
		//If this is custom page and if we have any field in this which has a mapping with any master field, we have to update that field in the master form.
		  if($pageId>3){
			//First, get all the fields with any mapping with the Master fields.
		  	$queryCmd = "select fl.fieldId, fl.name, fl.parentId, fu.value from OF_PageEntityMapping pm, OF_FieldsList fl, OF_FormUserData fu where pm.pageId = ? and pm.status = 'live' and pm.entitySetId = fl.fieldId and pm.entitySetType='field' and parentId!='' and fu.fieldId=fl.fieldId and fu.userId=? and fu.onlineFormId = ? ";

		  	$queryRes = $this->dbHandle->query($queryCmd,array($data['pageId'],$userId,$data['onlineFormId']));
		  	
		  	foreach($queryRes->result() as $row) {
		  		$queryCmd = "select value from OF_FormUserData where onlineFormId=? and userId=? and fieldId=?";
		  		$queryP = $this->dbHandle->query($queryCmd,array($data['onlineFormId'],$userId,$row->parentId));
		  		if($queryP->num_rows()>0){
                                        //Update the value of the parent field
		  			$queryCmd = "update OF_FormUserData set value = ? where onlineFormId= ? and userId= ? and fieldId= ?";
		  			$queryUpdate = $this->dbHandle->query($queryCmd,array($row->value,$data['onlineFormId'],$userId,$row->parentId));
		  		}
		  		else{
                                        //Get the name and page for the Parent Field
		  			$queryCmd = "select name,pageId from OF_FieldsList,OF_PageEntityMapping where fieldId = ? and fieldId=entitySetId and status='live'";
		  			$querySelect = $this->dbHandle->query($queryCmd,array($row->parentId));
		  			$rowV = $querySelect->row();
                                        //Insert the value of the parent field
		  			$queryCmd = "INSERT INTO OF_FormUserData (onlineFormId,userId,pageId,fieldId,value,fieldName) VALUES (?,?,?,?,?,?)";
		  			$queryInsert = $this->dbHandle->query($queryCmd,array($data['onlineFormId'],$userId,$rowV->pageId,$row->parentId,$row->value,$rowV->name));
		  		}
		  	}
		  }
		else{	//But in case of Master pages, we will have to check if there is any mapping in the Custom form. If it is, update the custom field
			$queryCmd = "select pageId from OF_PageMappingInForm pm, OF_ListForms lf where lf.courseId=? and lf.formId=pm.formId and pageId>3 and pm.status='live';";
			$queryRes = $this->dbHandle->query($queryCmd,array($data['courseId']));
			$pageRow = $queryRes->row();
			$customPageId = $pageRow->pageId;
			$queryCmd = "select fl.fieldId, fl.name, fl.parentId, fu.value from OF_PageEntityMapping pm, OF_FieldsList fl, OF_FormUserData fu where pm.pageId = ? and pm.status = 'live' and pm.entitySetId = fl.fieldId and pm.entitySetType='field' and parentId!='' and fu.fieldId=fl.parentId and fu.userId=? and fu.onlineFormId = ? ";
			$queryRes = $this->dbHandle->query($queryCmd,array($customPageId,$userId,$data['onlineFormId']));
			foreach($queryRes->result() as $row) {
				//Update the value of the Custom field
				$queryCmd = "update OF_FormUserData set value = ? where onlineFormId=? and userId=? and fieldId=?";
				$queryP = $this->dbHandle->query($queryCmd,array($row->value,$data['onlineFormId'],$userId,$row->fieldId));				
			}			
		}
		//End changes
		
		return 1;
	}

	function getTemplatePath($pageId){
		$this->initiateModel();
		$queryCmd = "select templatePath from OF_PageList where pageId = ?";
		$queryRes = $this->dbHandle->query($queryCmd,array($pageId));
		$row = $queryRes->row();
		$response = $row->templatePath;
	    return $response;
	}

	function getOnlineFormId($userId, $courseId, $pageId){
		$this->initiateModel();
		if($courseId==0){
		    $queryCmd = "select onlineFormId from OF_UserForms where userId = ? and type='master' and formStatus = 'live'";
			$queryRes = $this->dbHandle->query($queryCmd,array($userId));
		}
		else{
		    $queryCmd = "select onlineFormId from OF_UserForms where userId = ? and type='course' and courseId = ? and formStatus = 'live'";
			$queryRes = $this->dbHandle->query($queryCmd,array($userId,$courseId));
		}
		
		$row = $queryRes->row();
		$response = $row->onlineFormId;
	    return $response;
	}

	function getPageIdFromPageNumber($userId, $courseId, $pageNumber){
		$this->initiateModel();
		if($courseId==0){	//This is the Master form
		    $queryCmd = "select onlineFormId, formId ".
						"from OF_UserForms, OF_ListForms ".
						"where userId = ? ".
						"and type='master' ".
						"and OF_ListForms.courseId = 0 ".
						"and OF_ListForms.status = 'live' and OF_UserForms.formStatus = 'live'";
			$queryRes = $this->dbHandle->query($queryCmd,array($userId));
		}
		else{
		    $queryCmd = "select onlineFormId, formId ".
						"from OF_UserForms, OF_ListForms ".
						"where userId = ? ".
						"and type='course' ".
						"and OF_UserForms.courseId = ? ".
						"and OF_ListForms.courseId = OF_UserForms.courseId ".
						"and OF_ListForms.status = 'live' and OF_UserForms.formStatus = 'live'";
			$queryRes = $this->dbHandle->query($queryCmd,array($userId,$courseId));			
		}
		
		$row = $queryRes->row();
		$onlineFormId = $row->onlineFormId;
		$formId = $row->formId;

		$queryCmd = "select pmf.pageId ".
					"from OF_FilledPageMappingInForm fpmf, OF_PageMappingInForm pmf ".
					"where fpmf.userId = ? ".
					"and onlineFormId = ? ".
					"and pageOrder = ? ".
					"and pmf.pageId = fpmf.pageId ".
					"and pmf.formId = ?";
		$queryRes = $this->dbHandle->query($queryCmd,array($userId,$onlineFormId,$pageNumber,$formId));
		$row = $queryRes->row();
		$response = $row->pageId;
		if(empty($response) || $response == 0){
			$response = $pageNumber;
		}
	    return $response;
	}

	function getInstitutesForOnlineHomepage($showExternalForms,$filterArray,$department){
		$this->initiateModel();
        //First get all the Internal forms
        $extCondition = " AND (externalURL IS NULL OR externalURL='') ";
        $filterCond = '';
		if($department){
			$departmentQuery = " AND departmentName=?  "; 
		}
        if( count($filterArray)>0 && isset($filterArray['filter']) && $filterArray['filter']!='all' && $filterArray['filter']!=''){
                       $date = date("Y-m-d");
                       $date7 = strtotime("7 days",strtotime($date));
                       $date14 = strtotime("14 days",strtotime($date));
                        $date7 = date ( 'Y-m-j' , $date7 );
                        $date14 = date ( 'Y-m-j' , $date14 );

                       if($filterArray['filter']=='thisWeek')
                               $filterCond = " AND last_date < '$date7' AND last_date >= DATE(now()) ";
                       else if($filterArray['filter']=='nextWeek')
                               $filterCond = " AND last_date < '$date14' AND last_date >= '$date7' ";
        }

	if($showExternalForms=='true'){
		$institue_query = "(select instituteId from OF_InstituteDetails where status='live' and last_date >= DATE(now()) $filterCond $departmentQuery ORDER BY last_date)";
	}
	else{
        	$institue_query = "(select instituteId from OF_InstituteDetails where status='live' and last_date >= DATE(now()) $extCondition $filterCond $departmentQuery ORDER BY last_date)";
	}
		error_log($institue_query.$department);
		if($department){
			$query = $this->dbHandle->query($institue_query, array($department));
		}
		else{
			$query = $this->dbHandle->query($institue_query);
		}
		$results = $query->result();
		$institute_array = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$institute_array[] = $row->instituteId;
			}
		}

        //Now, get all the External forms if we require
	//Now, we are showing the External forms with internal forms only.
        /*if($showExternalForms=='true'){
               $institue_query = "(select instituteId from OF_InstituteDetails where status='live' and last_date >= DATE(now()) and externalURL!='' $filterCond $departmentQuery ORDER BY last_date)";
                $query = $this->dbHandle->query($institue_query);
                $results = $query->result();
                if(!empty($results) && is_array($results)) {
                        foreach ($results as $row){
                           $institute_array[] = $row->instituteId;
                        }
                }
        }*/
             /*  $institue_query = "(select instituteId from OF_InstituteDetails where status='live' and last_date < DATE(now()) $extCondition $filterCond ORDER BY last_date)";
		$query = $this->dbHandle->query($institue_query);
		$results = $query->result();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$institute_array[] = $row->instituteId;
			}
		}*/
		return (json_encode($institute_array));
	}

	function getFormsForInstitute($instituteId){
		$this->initiateModel();

		//$institue_query = "select uf.*, idt.*,cd.courseTitle from OF_UserForms uf, OF_InstituteDetails idt, course_details cd where uf.status IN ('paid','draft','accepted','rejected','shortlisted') and idt.instituteId = '$instituteId' and idt.courseId = uf.courseId and cd.course_id = uf.courseId order by departmentId";
		$institue_query = "select distinct idt.*,sc.name as courseName ".
						"from OF_InstituteDetails idt, shiksha_courses sc ".
						"where idt.instituteId = ? ".
						"and sc.course_id = idt.courseId ".
						"and sc.status = 'live' and idt.status = 'live' ".
						"order by departmentId";
		
		$query = $this->dbHandle->query($institue_query,array($instituteId));
		$results = $query->result_array();
		$institute_array = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$courseId = $row['courseId'];

				$course_query = "select count(*) unreadForms ".
								"from OF_UserForms uf ".
								"where uf.status IN ('paid','draft','accepted','rejected','shortlisted','cancelled','Payment Awaited','Payment Under Process','Payment Confirmed','Under Process','GD/PI Update') ".
								"and uf.courseId = ? ".
								"and readStatus = 'unread' and formStatus = 'live' ";
				$queryC = $this->dbHandle->query($course_query,array($courseId));
				$resultsC = $queryC->row();
				$unreadCount = $resultsC->unreadForms;

				$course_query = "select count(*) readForms ".
								"from OF_UserForms uf ".
								"where uf.status IN ('paid','draft','accepted','rejected','shortlisted','cancelled','Payment Awaited','Payment Under Process','Payment Confirmed','Under Process','GD/PI Update') ".
								"and uf.courseId = ? ".
								"and readStatus = 'read' and formStatus = 'live'";
				$queryC = $this->dbHandle->query($course_query,array($courseId));
				$resultsC = $queryC->row();
				$readCount = $resultsC->readForms;

				$row['unreadCount'] = $unreadCount;
				$row['readCount'] = $readCount;
				
				$fee = (int) $row['fees'];
				$discount = (int) $row['discount'];
				$actualFees = $fee - (($fee * $discount)/100);
				$row['actualFees'] = $actualFees;
				
				$institute_array[] = $row;
			}
		}
		$nameQuery = "select name as institute_name,listing_id as institute_id from shiksha_institutes where listing_id = ? and status = 'live'";
		$query = $this->dbHandle->query($nameQuery,array($instituteId));
		$results = $query->result_array();

		$mainArr = array();
		array_push($mainArr,array(
		    array(
		    'instituteInfo'=>array($results,'struct'),
		    'instituteDetails'=>array($institute_array,'struct'),
		    ),'struct')
		);//close array_push
		return $mainArr;
	}

	function getFormListForInstitute($instituteId, $entityId, $type,$searchParameter,$startFrom ,$count,$tab){
		$this->initiateModel();
		$clause = "";
	        $searchClause = "";
		$sortClause = " order by uf.onlineFormId desc ";
        	$from_date_main_first = "";
	        $from_date_main_second = "";
		if($type=='department'){
		    $clause = "and departmentId = ".$this->dbHandle->escape($entityId);
		}
		else if($type=='course'){
		    $clause = "and uf.courseId = ".$this->dbHandle->escape($entityId);
		}
		
		$searchTextValue = mysql_escape_string($searchParameter['searchTextValue']);
		if(isset($searchParameter['searchType']) && $searchParameter['searchType']=='cityName'){
			$searchClause = " and fud.value=( select city_id from countryCityTable where city_name = '{$searchTextValue}')";
		}else if( isset($searchParameter['searchType']) && $searchParameter['searchType']=='email'){
			$searchClause = " and tu.userId=( select userid from tuser where email = '{$searchTextValue}')";
		}else if( isset($searchParameter['searchType']) && $searchParameter['searchType']=='displayName'){
			$searchClause = " and tu.userid in ( select userid from tuser where displayName like '%{$searchTextValue}%')";
		}else if( isset($searchParameter['searchType']) && $searchParameter['searchType']=='appNum'){
			$searchClause = " and uf.instituteSpecId = '{$searchTextValue}'";
		}

		if(isset($searchParameter['sortBy'])){
			switch ($searchParameter['sortBy']){
				case 'appNumD': $sortClause = " order by uf.instituteSpecId desc "; break;
				case 'appNum': $sortClause = " order by uf.instituteSpecId asc "; break;
				case 'locationD': $sortClause = " order by cityName desc "; break;
                                case 'location': $sortClause = " order by cityName "; break;
                                case 'paymentD': $sortClause = " order by paymentMode desc "; break;
				case 'payment': $sortClause = " order by paymentMode "; break;
                                case 'gdpiD': $sortClause = " order by GDPIDate desc "; break;
                                case 'gdpi': $sortClause = " order by GDPIDate "; break;
                                case 'formstageD': $sortClause = " order by formStatus desc "; break;
                                case 'formstage': $sortClause = " order by formStatus "; break;
                                case 'formstatusD': $sortClause = " order by onlineFormEnterpriseStatus desc "; break;
                                case 'formstatus': $sortClause = " order by onlineFormEnterpriseStatus "; break;
			}
		}

		if(isset($searchParameter['from_date_main_first']) && $searchParameter['from_date_main_first']!='' && $searchParameter['from_date_main_first']!='dd/mm/yyyy'){
				$date1 = explode("/",$searchParameter['from_date_main_first']);
				$to_date = $date1[2].'-'.$date1[1].'-'.$date1[0];
		}
		if(isset($searchParameter['from_date_main_second']) && $searchParameter['from_date_main_second']!='' && $searchParameter['from_date_main_second']!='dd/mm/yyyy'){
				$date2 = explode("/",$searchParameter['from_date_main_second']);
				$from_date = $date2[2].'-'.$date2[1].'-'.$date2[0];
		}

		if(isset($to_date) && $searchParameter['from_date_main_first']!='dd/mm/yyyy' && $to_date!=''){
				$from_date_main_first = " and UNIX_TIMESTAMP(DATE(uf.creationDate)) >= UNIX_TIMESTAMP('{$to_date}')";
		}
		if(isset($from_date) && $searchParameter['from_date_main_second']!='dd/mm/yyyy' && $from_date!=''){
				$from_date_main_second = "  and UNIX_TIMESTAMP(DATE(uf.creationDate)) <= UNIX_TIMESTAMP('{$from_date}')";
		}

               if($tab!='awaitedForms') 
               { 
                               $statuses = " 'paid','accepted','rejected','shortlisted','cancelled','Payment Confirmed','Under Process','GD/PI Update','Payment Awaited','Payment Under Process' "; 
               } 
               else 
               { 
                               $statuses = " 'draft' "; 
               } 
			
		$institue_query = "select SQL_CALC_FOUND_ROWS distinct uf.GDPILocation as gdpiId,uf.onlineFormId,uf.instituteSpecId,uf.courseId,uf.userId,".
									"uf.type,uf.status,uf.creationDate,uf.readStatus,uf.GDPIDate,uf.onlineFormEnterpriseStatus,".
									"(select city_name from countryCityTable cct where uf.GDPILocation=cct.city_id) as GDPILocation,".
									"idt.*,sc.name as courseTitle, tu.firstname, tu.lastname, tu.displayName, tu.email,".
									"(select city_name from countryCityTable where city_id=fud.value ) as cityName,".
									"uf.status as formStatus,".
									"payments.mode as paymentMode,payments.status as paymentStatus ".
						"from OF_UserForms uf, OF_InstituteDetails idt, shiksha_courses sc, tuser tu, OF_FormUserData fud,OF_Payments payments ".
						"where uf.status IN ($statuses) ".
						"and idt.instituteId = ? ".
						"and idt.courseId = uf.courseId ".
						"and sc.course_id = uf.courseId ".
						"and sc.status = 'live' ".
						"and tu.userid = uf.userId ".
						"and fud.userId = uf.userId ".
						"and fud.onlineFormId = uf.onlineFormId ".
						"and uf.userId = payments.userId ".
						"and uf.onlineFormId = payments.onlineFormId ".
						"and uf.formStatus = 'live' ".
						"and idt.status = 'live' ".
						"and fud.fieldName = 'city' ".
						"$clause $searchClause $from_date_main_first $from_date_main_second ".
						"$sortClause ".
						"LIMIT ".$startFrom." , ".$count;				
		$query = $this->dbHandle->query($institue_query,array($instituteId));
		$results = $query->result_array();
		$institute_array = array();
		$i=0;
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$institute_array[$i] = $row;
				$i++;
			}
		}

                $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $this->dbHandle->query($queryCmd);
		foreach ($query->result() as $rowT) {
			$totalFormNumber  = $rowT->totalRows;
		}
		$j=0;
		foreach($institute_array as $key=>$value){
			$sql = "select fieldName,value from OF_FormUserData where fieldName in ('firstName','middleName','lastName') and onlineFormId=? and userId=?";
		 	$query = $this->dbHandle->query($sql,array($value['onlineFormId'],$value['userId']));
			$results = $query->result_array();
			foreach($results as $k=>$v){
				if($v['fieldName']=='firstName'){
					$institute_array[$j]['firstname'] = $v['value'];
				}
				if($v['fieldName']=='lastName'){
					$institute_array[$j]['lastname'] = $v['value'];
				}
				if($v['fieldName']=='middleName'){
					$institute_array[$j]['middlename'] = $v['value'];
				}
			}
			$j++;
		}

		$nameQuery = "select name as institute_name,listing_id as institute_id from shiksha_institutes where listing_id = ? and status = 'live'";
		$query = $this->dbHandle->query($nameQuery,array($instituteId));
		$results = $query->result_array();
		//$instituteName = $results->institute_name;

		$courseQuery = "select courseId,departmentName from OF_InstituteDetails where instituteId=? and status='live'";
		$query = $this->dbHandle->query($courseQuery,array($instituteId));
		$courseResults = $query->result_array();

		$mainArr = array();
		array_push($mainArr,array(
		    array(
                    'totalFormNumber'=>array($totalFormNumber,'struct'),
		    'instituteInfo'=>array($results,'struct'),
		    'instituteDetails'=>array($institute_array,'struct'),
		    'courseDetails'=>array($courseResults,'struct'),
		    ),'struct')
		);//close array_push
		return $mainArr;
	}

	function getFormForInstitute($instituteId, $userId, $onlineFormId){
		$this->initiateModel();
		
		$institue_query = "select SQL_CALC_FOUND_ROWS distinct uf.GDPILocation as gdpiId,uf.onlineFormId,uf.instituteSpecId,uf.courseId,uf.userId,".
									"uf.type,uf.status,uf.creationDate as cDate,uf.readStatus,uf.GDPIDate,uf.onlineFormEnterpriseStatus,".
									"(select city_name from countryCityTable cct where uf.GDPILocation=cct.city_id) as GDPILocation,".
									"idt.*,sc.name as courseTitle,tu.gender,tu.dateofbirth, tu.firstname, tu.lastname, tu.displayName, tu.email,".
									"(select city_name from countryCityTable where city_id=fud.value ) as cityName, uf.status as formStatus ".
						"from OF_UserForms uf, OF_InstituteDetails idt, shiksha_courses sc, tuser tu, OF_FormUserData fud ".
						"where uf.status IN ('paid','draft','accepted','rejected','shortlisted','cancelled','Payment Awaited','Payment Under Process','Payment Confirmed','Under Process','GD/PI Update') ".
						"and idt.instituteId = ? ".
						"and idt.courseId = uf.courseId ".
						"and sc.course_id = uf.courseId ".
						"and sc.status = 'live' ".
						"and tu.userid = uf.userId ".
						"and fud.userId = uf.userId ".
						"and fud.onlineFormId = uf.onlineFormId ".
						"and fud.fieldName = 'city' ".
						"and uf.userid = ? ".
						"and uf.onlineFormId = ? and uf.formStatus = 'live' and idt.status = 'live' ";
						
		$query = $this->dbHandle->query($institue_query,array($instituteId,$userId,$onlineFormId));
		
		
		$results = $query->result_array();
		$institute_array = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$institute_array[] = $row;
			}
		}
		$j=0;
		foreach($institute_array as $key=>$value){
			$sql = "select fieldName,value from OF_FormUserData where fieldName in ('firstName','middleName','lastName') and onlineFormId=? and userId=?";
		 	$query = $this->dbHandle->query($sql,array($value['onlineFormId'],$value['userId']));
			$results = $query->result_array();
			foreach($results as $k=>$v){
				if($v['fieldName']=='firstName'){
					$institute_array[$j]['firstname'] = $v['value'];
				}
				if($v['fieldName']=='lastName'){
					$institute_array[$j]['lastname'] = $v['value'];
				}
				if($v['fieldName']=='middleName'){
					$institute_array[$j]['middlename'] = $v['value'];
				}
			}
			$j++;
		}

		$nameQuery = "select name as institute_name,listing_id as institute_id from shiksha_institutes where listing_id = ? and status = 'live'";
		$query = $this->dbHandle->query($nameQuery,array($instituteId));
		$results = $query->result_array();
		//$instituteName = $results->institute_name;

		$mainArr = array();
		array_push($mainArr,array(
		    array(
		    'instituteInfo'=>array($results,'struct'),
		    'instituteDetails'=>array($institute_array,'struct'),
		    ),'struct')
		);//close array_push
		return $mainArr;
	}
	
	function getPagesUserHasFilled($userId, $onlineFormId, $courseId){
		$this->initiateModel();
		$onlineFormId = $this->getOnlineFormId($userId,$courseId);
		$queryCmd = "SELECT DISTINCT pmf.pageId, pmf.pageOrder ".
					"FROM OF_PageMappingInForm pmf, OF_FilledPageMappingInForm fpm ".
					"WHERE status='live' ".
					"AND pmf.pageId = fpm.pageId ".
					"AND fpm.onlineFormId = ? ".
					"AND fpm.userId = ? ".
					"ORDER by pmf.pageOrder";
		
		$query = $this->dbHandle->query($queryCmd,array($onlineFormId,$userId));
				
		$results = $query->result_array();
		$pageArray = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$row['onlineFormId'] = $onlineFormId;
				$pageArray[] = $row;
			}
		}

		return $pageArray;
	}
	
	function getFormCompleteData($userId, $courseId){
		$this->initiateModel();
		if($courseId<=0 && $userId>0){	//Get the Master form of the user 
			$queryCmdI = "select onlineFormId from OF_UserForms where userId = ? and type='master' and formStatus = 'live'";
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
			$queryCmdI = "select onlineFormId from OF_UserForms where userId = ? and type='course' and courseId = ? and formStatus = 'live'";
			
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
			$pageArray[] = $row;
		}
		return $pageArray;
	}
	
	function getFormListForUser($userid,$formid){
		$this->initiateModel();
		$formid = trim($formid);
		if(!empty($formid)) {
			$append_query = "ofu.onlineFormId=".$this->dbHandle->escape($formid)." and ";
		}
		$institue_query = "SELECT distinct ofu.instituteSpecId,ofi.instituteMobileNo,ofi.instituteLandline,ofu.creationDate,ofi.documentsRequired,".
								"ofi.imageSpecifications,ofi.last_date,DATEDIFF(ofi.last_date,'".date('Y-m-d')."') as deadline,ofi.instituteId,".
								"ofu.GDPIDate,(select city_name from countryCityTable cct where ofu.GDPILocation=cct.city_id) as GDPILocation,ofu.GDPILocation as preferredGDPILocation,ofi.instituteEmailId,".
								"ofi.instituteAddress,ofi.documentsRequired,ofi.imageSpecifications,ofi.fees,ofi.discount,ofu.onlineFormId,".
								"ofu.courseId,ofi.instituteId, ofu.status,inst.name as institute_name,sc.name as courseTitle,ofi.sessionYear,ofi.departmentName ".
						  "FROM shiksha_courses sc, shiksha_institutes inst,OF_UserForms ofu,OF_InstituteDetails ofi ".
						  "WHERE $append_query sc.primary_id = inst.listing_id ".
						  "AND inst.listing_id = ofi.instituteId ".
						  "AND sc.course_id = ofi.courseId ".
						  "AND sc.course_id = ofu.courseId ".
						  "AND ofi.courseId= ofu.courseId ".
						  "AND sc.status='live' ".
						  "AND inst.status='live' ".
						  "AND ofu.courseId!=0 ".
						  "AND ofu.type='course' ".
						  "AND ofu.formStatus = 'live' ".
						  "AND ofi.status = 'live' ".
						  "AND ofu.userId=? ORDER BY ofu.creationDate DESC"; 
		$query = $this->dbHandle->query($institue_query,array($userid));
		$results = $query->result_array();
		$institute_array = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$fee = (int) $row['fees'];
				$discount = (int) $row['discount'];
				$actualFees = $fee - (($fee * $discount)/100);
				$actualFees = $this->getFeesForInstitute($row['courseId'],$userid,$actualFees);
				$row['actualFees'] = $actualFees;
				$institute_array[] = $row;
			}
		}
		return $institute_array;
	}
	
	function addNotification($onlineFormId,$userId,$instituteId,$msgId,$status)
	{
		$this->initiateModel('write');
		
		if(!$status) {
			$status = 'Unviewed';
		}
		
		$notificationData = array(
			'instituteId' => $instituteId,
			'userId' => $userId,
			'onlineFormId' => $onlineFormId,
			'msgId' => $msgId,
			'createdDate' => date('Y-m-d H:i:s'),
			'status' => $status
		);
		
		$this->dbHandle->insert('OF_FormInstituteUserMessageTable', $notificationData);
		$notificationId = $this->dbHandle->insert_id();

		return $notificationId;
	}
	
	function getPageFieldList($page_array){
		$this->initiateModel();
		$institue_query = "SELECT pgmp.label,map.entitySetId,map.pageId,pgmp.name ".
						  "FROM OF_PageEntityMapping map,OF_FieldsList pgmp ".
						  "WHERE (map.entitySetId=pgmp.fieldId AND map.status ='live')".
						  "AND map.pageId in (".implode(',',$page_array).") ".
						  "ORDER BY pgmp.name";
		$query = $this->dbHandle->query($institue_query);
		$results = $query->result_array();
		$institute_array = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$institute_array[] = $row;
			}
		}
		$query_to_get_group = "SELECT pgmp.label,map.entitySetId,map.pageId,pgmp.name ".
						  "FROM OF_PageEntityMapping map,OF_FieldsList pgmp,OF_GroupList grp ".
						  "WHERE (map.entitySetId=grp.groupId AND map.status ='live' AND map.entitySetType ='group' AND grp.fieldId = pgmp.fieldId)".
						  "AND map.pageId in (".implode(',',$page_array).") ".
						  "ORDER BY pgmp.name";
		$query1 = $this->dbHandle->query($query_to_get_group);
		$results1 = $query1->result_array();
		$institute_array1 = array();
		if(!empty($results1) && is_array($results1)) {
			foreach ($results1 as $row){
				$institute_array1[] = $row;
			}
		}
		$institute_array = array_merge($institute_array,$institute_array1);
		return $institute_array;
	}
	
	function getGDPILocations($courseId){
		$this->initiateModel();
		$queryCmd = "SELECT city.city_id,city.city_name ".
					"FROM OF_GDPILocations gdpi ".
					"LEFT JOIN countryCityTable city ON city.city_id = gdpi.cityId ".
					"WHERE gdpi.courseId = ? ".
					"AND gdpi.status = 'live' ".
					"ORDER BY city.city_name";
		$query = $this->dbHandle->query($queryCmd,array($courseId));
		$results = $query->result_array();
		$locations = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$locations[] = $row;
			}
		}
		return $locations;
	}
	
	function updateGDPILocation($onlineFormId,$userId,$gdpiLocation){
		$this->initiateModel('write');
		$queryCmd = "UPDATE OF_UserForms SET GDPILocation = ? WHERE onlineFormId = ? AND userId = ?";
		$query = $this->dbHandle->query($queryCmd,array($gdpiLocation,$onlineFormId,$userId));
		return 1;
	}
	
	function getUsersForDeadlineNotifications()
	{
		$this->initiateModel();
		$queryCmd = "SELECT uf.onlineFormId,uf.userId ".
					"FROM OF_UserForms uf ".
					"INNER JOIN OF_InstituteDetails idt ON (idt.courseId = uf.courseId AND idt.status = 'live') ".
					"WHERE uf.courseId > 0 ".
					"AND uf.status IN ('started','uncompleted','completed') AND uf.formStatus = 'live' ".
					"AND (DATEDIFF(idt.last_date,'".date('Y-m-d')."') = 1 OR DATEDIFF(idt.last_date,'".date('Y-m-d')."') = 2) AND idt.sessionYear = uf.sessionYear";
		
		$query = $this->dbHandle->query($queryCmd);
		
		$results = $query->result_array();
		$data = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$data[] = $row;
			}
		}
		return $data;
	}

	function setInstituteSpecId($onlineFormId,$userId,$instituteId){
		$this->initiateModel('write');

		//Check if the Spec ID is already set for any form. If yes, get the last set Id and increment it by 1
		if($instituteId=='21970')
			$queryCmd = "select max(CONVERT(instituteSpecId,SIGNED INTEGER)) instituteSpecId from OF_UserForms where courseId IN (select courseId from OF_InstituteDetails where instituteId = ?) and formStatus = 'live' LIMIT 1";
		else
			$queryCmd = "select max(instituteSpecId) instituteSpecId from OF_UserForms where courseId IN (select courseId from OF_InstituteDetails where instituteId = ?) and formStatus = 'live' LIMIT 1";
		$query = $this->dbHandle->query($queryCmd,array($instituteId));
		$notFound = true;
		if($query->num_rows() > 0){
		      $row = $query->row();
		      $lastSpecId = $row->instituteSpecId;
			$cc = strlen($lastSpecId);
		      if($lastSpecId>0){
			  $lastSpecId++;
			  $notFound = false;
			  if(strlen($lastSpecId)<$cc){
					$nn = $cc - strlen($lastSpecId);
					for($i=0;$i<$nn;$i++)
						$lastSpecId = '0'.$lastSpecId;
				}
		      }
		}

		//If the Spec Id is not set, we will get the Limit from the Institute table
		if($notFound)
		{	
		      $queryCmd = "select formIdMinRange from OF_InstituteDetails where instituteId = ? and status = 'live' ";
		      $query = $this->dbHandle->query($queryCmd,array($instituteId));
		      if($query->num_rows() > 0){
			    $row = $query->row();
			    $lastSpecId = $row->formIdMinRange;
		      }
		}

		//Now, update the User form with the correct Institute Spec Id
		if($lastSpecId>0){
		      $selectQuery = "select instituteSpecId  from OF_UserForms where userId = ? and onlineFormId = ?";
                      $query = $this->dbHandle->query($selectQuery,array($userId,$onlineFormId));
                      $row = $query->row();
                      if(empty($row->instituteSpecId)){
                        $queryCmd = "UPDATE OF_UserForms set instituteSpecId = ? where userId = ? and onlineFormId = ?";
                        $query = $this->dbHandle->query($queryCmd,array($lastSpecId,$userId,$onlineFormId));
                      }
		}
		return 1;
	}

	function checkIfUserCameOnOnlineForms($userId){
		$this->initiateModel();
		$response = 'false';
		$queryCmd = "select * from OF_UserForms where userId = ? and formStatus = 'live' ";
		$queryRes = $this->dbHandle->query($queryCmd,array($userId));
		if($queryRes->num_rows()>0)
		      $response = 'true';
		return $response;
	}

	//*************************************/
	//Cron to get Daily Data Start
	/*************************************/

	function cronToGetDailyInformation($time){
                $this->initiateModel();
                if($time!='daily'){
                    $queryCmd = "SELECT uf.userId, uf.onlineFormId, uf.instituteSpecId, uf.status, uf.courseId, uf.creationDate,(select cd.name from shiksha_courses cd where uf.courseId = cd.course_id and cd.status = 'live' LIMIT 1) courseTitle, (select inst.name from shiksha_institutes inst, shiksha_courses cd where uf.courseId = cd.course_id and cd.status = 'live' and cd.primary_id = inst.listing_id and inst.status = 'live' LIMIT 1) instituteName, (select value from OF_FormUserData where fieldName = 'firstName' and onlineFormId = uf.onlineFormId LIMIT 1) FirstName, (select value from OF_FormUserData where fieldName = 'lastName' and onlineFormId = uf.onlineFormId LIMIT 1) LastName,(select value from OF_FormUserData where fieldName = 'email' and onlineFormId = uf.onlineFormId LIMIT 1) Email,(select value from OF_FormUserData where fieldName = 'mobileNumber' and onlineFormId = uf.onlineFormId LIMIT 1) MobileNo, (select value from OF_FormUserData where fieldName = 'catRollNumber' and onlineFormId = uf.onlineFormId LIMIT 1) CATRegNo,(select value from OF_FormUserData where fieldName = 'matRollNumber' and onlineFormId = uf.onlineFormId LIMIT 1) MATRegNo,(select value from OF_FormUserData where fieldName = 'graduationExaminationName' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationName,(select value from OF_FormUserData where fieldName = 'graduationBoard' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationBoard,(select value from OF_FormUserData where fieldName = 'graduationPercentage' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationPercentage, (select value from OF_FormUserData where fieldName = 'graduationSchool' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationInstitute, (select value from OF_FormUserData where fieldName = 'graduationYear' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationYear, (select firstname from tuser where userid = uf.userId) tuserFirstName, (select lastname from tuser where userid = uf.userId) tuserLastName, (select email from tuser where userid = uf.userId) tuserEmail, (select mobile from tuser where userid = uf.userId) tuserMobile from OF_UserForms uf where uf.creationDate >= DATE_SUB(now(),INTERVAL 30 MINUTE) and uf.creationDate < now() and uf.status IN ('started','uncompleted','completed','draft','paid') and uf.formStatus = 'live' order by uf.status  ";
                }
                else{
                    $queryCmd = "SELECT uf.userId, uf.onlineFormId, uf.instituteSpecId, uf.status, uf.courseId, uf.creationDate,(select cd.name from shiksha_courses cd where uf.courseId = cd.course_id and cd.status = 'live' LIMIT 1) courseTitle, (select inst.name from shiksha_institutes inst, shiksha_courses cd where uf.courseId = cd.course_id and cd.status = 'live' and cd.primary_id = inst.listing_id and inst.status = 'live' LIMIT 1) instituteName, (select value from OF_FormUserData where fieldName = 'firstName' and onlineFormId = uf.onlineFormId LIMIT 1) FirstName, (select value from OF_FormUserData where fieldName = 'lastName' and onlineFormId = uf.onlineFormId LIMIT 1) LastName,(select value from OF_FormUserData where fieldName = 'email' and onlineFormId = uf.onlineFormId LIMIT 1) Email,(select value from OF_FormUserData where fieldName = 'mobileNumber' and onlineFormId = uf.onlineFormId LIMIT 1) MobileNo, (select value from OF_FormUserData where fieldName = 'catRollNumber' and  onlineFormId = uf.onlineFormId LIMIT 1) CATRegNo,(select value from OF_FormUserData where fieldName = 'matRollNumber' and onlineFormId = uf.onlineFormId LIMIT 1) MATRegNo,(select value from OF_FormUserData where fieldName = 'graduationExaminationName' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationName,(select value from OF_FormUserData where fieldName = 'graduationBoard' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationBoard,(select value from OF_FormUserData where fieldName = 'graduationPercentage' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationPercentage, (select value from OF_FormUserData where fieldName = 'graduationSchool' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationInstitute, (select value from OF_FormUserData where fieldName = 'graduationYear' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationYear, (select firstname from tuser where userid = uf.userId) tuserFirstName, (select lastname from tuser where userid = uf.userId) tuserLastName, (select email from tuser where userid = uf.userId) tuserEmail, (select mobile from tuser where userid = uf.userId) tuserMobile from OF_UserForms uf where uf.creationDate >= DATE_SUB(DATE(now()),INTERVAL 1 DAY) and uf.creationDate < DATE(now()) and uf.status IN ('started','uncompleted','completed','draft','paid') and uf.formStatus = 'live' order by uf.status  ";                
                }
                $query = $this->dbHandle->query($queryCmd);
                $results = $query->result_array();
                $data = array();
                if(!empty($results) && is_array($results)) {
                        foreach ($results as $row){
                                $data[] = $row;
                        }
                }
                return $data;
        }
	//*************************************/
	//Cron to get Daily Data End
	/*************************************/

	//*************************************/
	//Cron to get every fifteenth Day Data Start
	/*************************************/
	function cronToGetEveryFifteentDayInformation(){
		$this->initiateModel();
		$queryCmd = "SELECT distinct (select value from OF_FormUserData where OF_FormUserData.fieldName ='firstName' AND OF_FormUserData.onlineFormId = ofu.onlineFormId LIMIT 1) firstName,(select value from OF_FormUserData where OF_FormUserData.fieldName ='middleName' AND OF_FormUserData.onlineFormId = ofu.onlineFormId LIMIT 1) middleName,(select value from OF_FormUserData where OF_FormUserData.fieldName ='lastName' AND OF_FormUserData.onlineFormId = ofu.onlineFormId LIMIT 1) lastName,payment.*,payment.status as trans_status,ofi.instituteMobileNo,ofi.instituteLandline,ofu.creationDate,ofi.documentsRequired,ofi.imageSpecifications,ofi.last_date,DATEDIFF(ofi.last_date,'2012-01-23') as deadline,ofi.instituteId,ofu.GDPIDate,(select city_name from countryCityTable cct where ofu.GDPILocation=cct.city_id) as GDPILocation,ofu.GDPILocation as preferredGDPILocation,ofi.instituteEmailId,ofi.instituteAddress,ofi.documentsRequired,ofi.imageSpecifications,ofi.fees,ofi.discount,ofu.onlineFormId,ofu.courseId,ofi.instituteId, ofu.status,inst.name institute_name,cd.name,ofu.status as formstatus,ofu.userId FROM shiksha_courses cd, shiksha_institutes inst,OF_UserForms ofu,OF_InstituteDetails ofi,OF_Payments payment WHERE  payment.userId=ofu.userId AND payment.onlineFormId = ofu.onlineFormId AND cd.primary_id = inst.listing_id AND inst.listing_id = ofi.instituteId AND cd.course_id = ofi.courseId AND cd.course_id = ofu.courseId AND ofi.courseId= ofu.courseId AND ofi.status = 'live' AND cd.status='live' AND inst.status='live' AND ofu.courseId!=0 AND ofu.type='course' AND ofu.formStatus = 'live' AND ((payment.mode ='Offline' AND payment.status ='Started' AND ofu.status ='draft') OR (payment.mode ='Online' AND payment.status ='Success') OR (payment.mode ='Offline' AND payment.status ='Pending') OR (payment.mode ='Online' AND payment.status ='Failed') OR (payment.mode ='Online' AND payment.status ='Started')) AND ((unix_timestamp(now())- unix_timestamp(payment.date))<1296000 AND (unix_timestamp(now())- unix_timestamp(payment.date))>=0) ORDER BY payment.date DESC";
		$query = $this->dbHandle->query($queryCmd);
		$results = $query->result_array();
		$data = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$data[$row['onlineFormId']] = $row;
			}
		}
		error_log("OF_MIS_CRON_15_DAYS_QUERY".$queryCmd);
		return $data;
	}
	//*************************************/
	//Cron to get every fifteenth Day Data End
	/*************************************/
        	function formHasExpired($courseId){
		$this->initiateModel();
		$response = '1';
		$queryCmd = "select * from OF_InstituteDetails where status ='live' and courseId = ? and (unix_timestamp(last_date) -unix_timestamp(now()))>(-86400)";
		$queryRes = $this->dbHandle->query($queryCmd,array($courseId));
		if($queryRes->num_rows()>0){
			$response = '0';
		}else{
	                $queryCmd = "select * from OF_InstituteDetails where status ='live' and otherCourses like ? and (unix_timestamp(last_date) -unix_timestamp(now()))>(-86400)";
        	        $queryRes = $this->dbHandle->query($queryCmd,array('%'.$courseId.'%'));
	                if($queryRes->num_rows()>0){
				$row = $queryRes->row();
	   	               // $response = $row->courseId;
				 $response = $courseId;
                	}
		}
		error_log('form_expired_api'.$response);
		return $response;
	}

	function cronToGetDailyPaidInformation($time){
                $this->initiateModel();
                if($time!='daily'){
            		$queryCmd = "SELECT pa.*, uf.userId, uf.onlineFormId, uf.instituteSpecId, uf.status formStatus, uf.courseId, uf.creationDate, (select cd.name from shiksha_courses cd where uf.courseId = cd.course_id and cd.status = 'live' LIMIT 1) courseTitle, (select inst.name from shiksha_institutes inst, shiksha_courses cd where uf.courseId = cd.course_id and cd.status = 'live' and cd.primary_id = inst.listing_id and inst.status = 'live' LIMIT 1) instituteName, (select value from OF_FormUserData where fieldName = 'firstName' and onlineFormId = uf.onlineFormId LIMIT 1) FirstName, (select value from OF_FormUserData where fieldName = 'lastName' and onlineFormId = uf.onlineFormId LIMIT 1) LastName,(select value from OF_FormUserData where fieldName = 'email' and onlineFormId = uf.onlineFormId LIMIT 1) Email,(select value from OF_FormUserData where fieldName = 'mobileNumber' and onlineFormId = uf.onlineFormId LIMIT 1) MobileNo, (select value from OF_FormUserData where fieldName = 'catRollNumber' and onlineFormId = uf.onlineFormId LIMIT 1) CATRegNo,(select value from OF_FormUserData where fieldName = 'matRollNumber' and onlineFormId = uf.onlineFormId LIMIT 1) MATRegNo,(select value from OF_FormUserData where fieldName = 'graduationExaminationName' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationName,(select value from OF_FormUserData where fieldName = 'graduationBoard' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationBoard,(select value from OF_FormUserData where fieldName = 'graduationPercentage' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationPercentage, (select value from OF_FormUserData where fieldName = 'graduationSchool' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationInstitute, (select value from OF_FormUserData where fieldName = 'graduationYear' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationYear from OF_UserForms uf, OF_Payments pa where pa.date >= DATE_SUB(now(),INTERVAL 30 MINUTE) and pa.date < now() and pa.onlineformId = uf.onlineFormId and uf.status NOT IN ('started','uncompleted','completed') and uf.formStatus = 'live' and pa.status IN ('Success','Failed','Pending') order by pa.status"; 
                }
                else{
                    $queryCmd = "SELECT pa.*, uf.userId, uf.onlineFormId, uf.instituteSpecId, uf.status formStatus, uf.courseId, uf.creationDate, (select cd.name from shiksha_courses cd where uf.courseId = cd.course_id and cd.status = 'live' LIMIT 1) courseTitle, (select inst.name from shiksha_institutes inst, shiksha_courses cd where uf.courseId = cd.course_id and cd.status = 'live' and cd.primary_id = inst.listing_id and inst.status = 'live' LIMIT 1) instituteName, (select value from OF_FormUserData where fieldName = 'firstName' and onlineFormId = uf.onlineFormId LIMIT 1) FirstName, (select value from OF_FormUserData where fieldName = 'lastName' and onlineFormId = uf.onlineFormId LIMIT 1) LastName,(select value from OF_FormUserData where fieldName = 'email' and onlineFormId = uf.onlineFormId LIMIT 1) Email,(select value from OF_FormUserData where fieldName = 'mobileNumber' and onlineFormId = uf.onlineFormId LIMIT 1) MobileNo, (select value from OF_FormUserData where fieldName = 'catRollNumber' and onlineFormId = uf.onlineFormId LIMIT 1) CATRegNo,(select value from OF_FormUserData where fieldName = 'matRollNumber' and onlineFormId = uf.onlineFormId LIMIT 1) MATRegNo,(select value from OF_FormUserData where fieldName = 'graduationExaminationName' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationName,(select value from OF_FormUserData where fieldName = 'graduationBoard' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationBoard,(select value from OF_FormUserData where fieldName = 'graduationPercentage' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationPercentage, (select value from OF_FormUserData where fieldName = 'graduationSchool' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationInstitute, (select value from OF_FormUserData where fieldName = 'graduationYear' and onlineFormId = uf.onlineFormId LIMIT 1) GraduationYear from OF_UserForms uf, OF_Payments pa where pa.date >= DATE_SUB(DATE(now()),INTERVAL 1 DAY) and pa.date < DATE(now()) and pa.onlineformId = uf.onlineFormId and uf.status NOT IN ('started','uncompleted','completed') and uf.formStatus = 'live' and pa.status IN ('Success','Failed','Pending') order by pa.status";
                }
        		error_log("cronfordailyinfo".$queryCmd);
                $query = $this->dbHandle->query($queryCmd);
                $results = $query->result_array();
                $data = array();
                if(!empty($results) && is_array($results)) {
                        foreach ($results as $row){
                                $data[] = $row;
                        }
                }
                return $data;
        }

	function checkIfListingHasOnlineForm($listingType,$listingId){
		 $this->initiateModel();
		 $str = ($listingType=='institute')?" instituteId = ? ":" courseId = ? ";
		 $queryCmd = "SELECT id,courseId,externalURL,instituteId,departmentName,last_date from OF_InstituteDetails where status = 'live' and last_date>=DATE(now()) and $str ";
                $query = $this->dbHandle->query($queryCmd,array($listingId));
                $results = $query->result_array();
                $resultVal = array();
                if(!empty($results) && is_array($results)) {
                       foreach ($results as $row){
                            $resultVal[] = $row;
                       }
                }
                if(count($resultVal)<=0 && $listingType!='institute'){
                        //Now check Online forms on all courses of this institute
                        $queryCmd = "SELECT otherCourses,courseId,externalURL,instituteId,last_date from OF_InstituteDetails where status = 'live' and last_date>=DATE(now())  and otherCourses LIKE ? LIMIT 1";
                        $query = $this->dbHandle->query($queryCmd,array('%'.$listingId.'%'));
                        $results = $query->result_array();
                        $results[0]['typeId'] = $listingId;
                        $results[0]['isOtherCourse'] = 1;

                        if(!empty($results) && is_array($results)) {
                                foreach ($results as $row){
                                     //if(strpos($row['otherCourses'],$listingId)!==false){
				     if(in_array($listingId,explode(',',$row['otherCourses']))){
                                             $resultVal[] = $row;
                                     }
                                }
                        }
		  }
		  return $resultVal;
	}
        //*******************************************/
	//Cron to handle failed transaction Starts
	/********************************************/
	function cronToHandleFailedTransactions(){
		$this->initiateModel('write');
		$queryCmd = "select userId,onlineFormId,paymentId from OF_Payments where status = 'Started' AND mode ='Online' AND now() >DATE_ADD(date, INTERVAL 30 MINUTE) AND date > DATE_SUB(now(), INTERVAL 1 DAY)";
		$query = $this->dbHandle->query($queryCmd);
		$results = $query->result_array();
		$data = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$update_query = 'UPDATE OF_Payments set status="Failed" where onlineFormId=? AND userId=? AND status="Started"';
				$query = $this->dbHandle->query($update_query,array($row['onlineFormId'],$row['userId']));
                                $update_query1 = 'UPDATE OF_PaymentLog set status="Failed",log="This transaction is failed by cron" where paymentId=? AND status="Started"';
                                $query = $this->dbHandle->query($update_query1,array($row['paymentId']));

                                $update_query1 = 'UPDATE OF_PaymentFinanceFields set status="Failed" where paymentId=? AND status="Started"';
                                $query = $this->dbHandle->query($update_query1,array($row['paymentId']));

				error_log("OF_MIS_CRON_FAILED_TRANSACTION_QUERY".$update_query1);
                                error_log("OF_MIS_CRON_FAILED_TRANSACTION_QUERY".$update_query);
				$data[] = $row;
			}
		}
		error_log("OF_MIS_CRON_FAILED_TRANSACTION_QUERY".$queryCmd);
		return $data;
	}
	//*******************************************/
	//Cron to handle failed transaction Ends
	/********************************************/

        //Function to get the DB updates for renewing an old form for a new session
        function dbUpdatesForRenew($courseId,$instituteShortName,$newSessionYear){
                $this->initiateModel('write');

                //Write the Update queries.
                $oldSessionYear = intval($newSessionYear)-1;
                $result = "## Update queries to Make the old data as History in OF_InstituteDetails ,OF_PageList ,OF_ListForms, OF_UserForms,OF_PageMappingInForm\r\n \r\n";
                $result .= "UPDATE OF_InstituteDetails set status = 'history' where courseId=$courseId and sessionYear=$oldSessionYear;\r\n";
                $result .= "UPDATE OF_UserForms set formStatus = 'history' where courseId=$courseId and sessionYear=$oldSessionYear;\r\n";
                $result .= "UPDATE OF_ListForms set status = 'history' where courseId=$courseId;\r\n";

                $queryCmd = "select * from OF_PageList where pageType='custom' and status='live' and pageName like '%$instituteShortName%'";
                $query = $this->dbHandle->query($queryCmd);
                $rowD = $query->row();
                $pageName = $rowD->pageName;
                $pageId = $rowD->pageId;
                $result .= "UPDATE OF_PageList set status = 'history' where pageType='custom' and pageName = '$pageName';\r\n";
                $result .= "UPDATE OF_PageMappingInForm set status = 'history' where pageId='$pageId';\r\n \r\n";

                $result .= "## Insert queries to Add a new form, new custom page and make entries in these DB tables\r\n \r\n";
                //Insert query in OF_InstituteDetails table
                $queryCmd = "select * from OF_InstituteDetails where courseId=? ";
                $query = $this->dbHandle->query($queryCmd,array($courseId));
                $rowD = $query->row();
                $result .= "INSERT INTO `OF_InstituteDetails` ( `instituteId`, `courseId`, `courseCode`, `formIdMinRange`, `formIdMaxRange`, `instituteDisplayText`, `fees`, `creationDate`, `status`, `logoImage`, `basicInformation`, `discount`, `last_date`, `min_qualification`, `exams_required`, `courses_available`, `departmentId`, `departmentName`, `instituteEmailId`, `instituteAddress`, `imageSpecifications`, `documentsRequired`, `demandDraftInFavorOf`, `demandDraftPayableAt`, `instituteMobileNo`, `instituteLandline`, `sessionYear`)\r\n VALUES ('$rowD->instituteId', '$rowD->courseId', '$rowD->courseCode', '$rowD->formIdMinRange', '$rowD->formIdMaxRange', '$rowD->instituteDisplayText', '$rowD->fees', now(), 'live', '$rowD->logoImage', '$rowD->basicInformation', '$rowD->discount', '$rowD->last_date', '$rowD->min_qualification ', '$rowD->exams_required', '$rowD->courses_available', '$rowD->departmentId', '$rowD->departmentName', '$rowD->instituteEmailId', '$rowD->instituteAddress', '$rowD->imageSpecifications', '$rowD->documentsRequired', '$rowD->demandDraftInFavorOf', '$rowD->demandDraftPayableAt', '$rowD->instituteMobileNo', '$rowD->instituteLandline',$newSessionYear);\r\n \r\n";

                //Insert query for the new form
                $queryCmd = "select max(formId) maxId from OF_ListForms";
                $query = $this->dbHandle->query($queryCmd);
                $rowF = $query->row();
                $result .= "INSERT INTO `OF_ListForms` (`formId`, `formName`, `courseId`, `creationDate`, `status`) VALUES (".(intval($rowF->maxId)+1).", '".$instituteShortName."MBA2013', '$courseId', CURRENT_TIMESTAMP, 'live');\r\n \r\n";

                //Insert query for the new custom page
                $queryCmd = "select max(pageId) maxId from OF_PageList";
                $query = $this->dbHandle->query($queryCmd);
                $rowP = $query->row();
                $result .= "INSERT INTO `OF_PageList` (`pageId`, `pageName`, `pageType`, `creationDate`, `status`, `templatePath`) VALUES (".(intval($rowP->maxId)+1).", '".$instituteShortName."MBAPage1', 'custom', CURRENT_TIMESTAMP, 'live', 'Online/Templates/".$instituteShortName."MBAPage1');\r\n \r\n";

                //Insert query for mapping between new form and master+custom page
                $result .= "INSERT INTO `OF_PageMappingInForm` (`formId`, `pageId`, `pageOrder`, `status`) VALUES \r\n(".(intval($rowF->maxId)+1).", 1, 1, 'live'),(".(intval($rowF->maxId)+1).", 2, 2, 'live'),(".(intval($rowF->maxId)+1).", 3, 3, 'live'),(".(intval($rowF->maxId)+1).", ".(intval($rowP->maxId)+1).", 4, 'live');\r\n \r\n";

                //Insert queries for mapping between fields and new custom page
                $result .= "INSERT INTO `OF_PageEntityMapping` (`pageEntityId`, `pageId`, `entitySetId`, `entitySetType`, `entityOrder`, `status`) VALUES \r\n";
                $queryCmd = "select * from OF_PageEntityMapping where pageId = ?";
                $query = $this->dbHandle->query($queryCmd,array($pageId));
                $mapping = $query->result_array();
                if(!empty($mapping) && is_array($mapping)) {
                        $i=0;
                        foreach ($mapping as $row){
                                if($i>0) $result .= ", \r\n";
                                $result .= "(NULL, '".(intval($rowP->maxId)+1)."' , '".$row['entitySetId']."' , '".$row['entitySetType']."' , '".$row['entityOrder']."' , 'live')";
                                $i++;
                        }
                        $result .= "; \r\n";
                }

                return $result;
        }

       function getOnlineInstituteTitles($department){
                $this->initiateModel();
                $queryCmd = "select listing_title,instituteId from listings_main lm,OF_InstituteDetails of where of.status = 'live' and lm.status = 'live' and lm.listing_type = 'institute' and lm.listing_type_id = of.instituteId and departmentName = ?";
                $query = $this->dbHandle->query($queryCmd,array($department));
                $results = $query->result_array();
                $data = array();
                if(!empty($results) && is_array($results)) {
                        foreach ($results as $row){
                                $data[] = $row;
                        }
                }
                return $data;
       }
        function getFeesForInstitute($courseId,$userId,$fees){
if($courseId==159341){
	            $this->initiateModel();
	            //Find the number of courses selected on 4th page
	            $queryCmd = "select * from OF_UserForms where courseId=? and userId=? and formStatus='live'";
	            $query = $this->dbHandle->query($queryCmd,array($courseId,$userId));
	            $row = $query->row();
	           	$onlineFormId = $row->onlineFormId;
	            $queryCmd = "select * from OF_FormUserData where userId=? and onlineFormId=? and fieldName='courseIMI'";
	            $query = $this->dbHandle->query($queryCmd,array($userId,$onlineFormId));
	            $row = $query->row();
	            $coursesSel = $row->value;
	            $courseArray = explode(',',$coursesSel);
	            $fees = 1500 + (count($courseArray)*500);
	            return $fees;
        	}
	        else if($courseId==1450){
	            $this->initiateModel();
	            //Find the course selected on 4th page
	            $queryCmd = "select * from OF_UserForms where courseId=? and userId=? and formStatus='live'";
	            $query = $this->dbHandle->query($queryCmd,array($courseId,$userId));
	            $row = $query->row();
	            $onlineFormId = $row->onlineFormId;
	            $queryCmd = "select * from OF_FormUserData where userId=? and onlineFormId=? and fieldName='courseCodeFORE'";
	            $query = $this->dbHandle->query($queryCmd,array($userId,$onlineFormId));
	            $row = $query->row();
	            $coursesSel = $row->value;
	            $fees = ($coursesSel=='BOTH')?3300:1650;
	            return $fees;
	        }else if($courseId==164937){
	            $this->initiateModel();
	            //Find the course selected on 4th page
	            $queryCmd = "select * from OF_UserForms where courseId=? and userId=? and formStatus='live'";
	            $query = $this->dbHandle->query($queryCmd,array($courseId,$userId));
	            $row = $query->row();
	            $onlineFormId = $row->onlineFormId;
	            $queryCmd = "select * from OF_FormUserData where userId=? and onlineFormId=? and fieldName='ISBM_campus'";
	            $query = $this->dbHandle->query($queryCmd,array($userId,$onlineFormId));
	            $row = $query->row();
	            $coursesSel = $row->value;
	                       $fees = 900 + (count(explode(",",$coursesSel))*200);
	            return $fees;
	        }else if($courseId==161985){
	                       $this->initiateModel();
	            //Find the course selected on 4th page
	            $queryCmd = "select * from OF_UserForms where courseId=? and userId=? and formStatus='live'";
	            $query = $this->dbHandle->query($queryCmd,array($courseId,$userId));
	            $row = $query->row();
	            $onlineFormId = $row->onlineFormId;
	            $queryCmd = "select * from OF_FormUserData where userId=? and onlineFormId=? and fieldName='PSG_course'";
	            $query = $this->dbHandle->query($queryCmd,array($userId,$onlineFormId));
	            $row = $query->row();
	            $coursesSel = $row->value;
	                         $fees = (count(explode(",",$coursesSel))*1000);
	            return $fees;
	        }else if($courseId==166106){
	            $this->initiateModel();
	            //Find the course selected on 4th page
	            $queryCmd = "select * from OF_UserForms where courseId=? and userId=? and formStatus='live'";
	            $query = $this->dbHandle->query($queryCmd,array($courseId,$userId));
	            $row = $query->row();
	            $onlineFormId = $row->onlineFormId;
	            $queryCmd = "select * from OF_FormUserData where userId=? and onlineFormId=? and fieldName='PSG_course'";
	            $query = $this->dbHandle->query($queryCmd,array($userId,$onlineFormId));
	            $row = $query->row();
	            $coursesSel = $row->value;
	            $fees = (count(explode(",",$coursesSel))*1000);
	            return $fees;
	        }else if($courseId==170335){
	            $this->initiateModel();
	            //Find if the user has also filled and paid XIME Banglore. If yes, fee will be Rs. 300/-
	            $queryCmd = "select * from OF_UserForms where courseId='128457' and userId=? and formStatus='live' and status IN ('paid','accepted','rejected','shortlisted','cancelled','Payment Confirmed','Under Process','GD/PI Update','Payment Awaited','Payment Under Process')";
	            $query = $this->dbHandle->query($queryCmd,array($userId));
	                          if($query->num_rows()>0)
	                                  $fees = 300;
	                return $fees;
	        }else if($courseId==170061){
		        $this->initiateModel();
		        //Find the course selected on 4th page
		        $queryCmd = "select * from OF_UserForms where courseId=? and userId=? and formStatus='live'";
		        $query = $this->dbHandle->query($queryCmd,array($courseId,$userId));
		        $row = $query->row();
		        $onlineFormId = $row->onlineFormId;
		        $queryCmd = "select * from OF_FormUserData where userId=? and onlineFormId=? and fieldName='IBA_course'";
		        $query = $this->dbHandle->query($queryCmd,array($userId,$onlineFormId));
		        $row = $query->row();
		        $coursesSel = $row->value;
	           $fees = 750;
	        if( strpos($coursesSel,",")!==false ){
	                 $fees = 810;
	         		}
	                return $fees;
	        }
	        else{
	         	return $fees;
	          	}
    		return 0;
		}

	function getEnterpriseFieldMapping($courseId){
		$this->initiateModel();
		$queryCmd = "select * from OF_ENT_ENTERPRISE_FIELDS ef where ef.status = 'live' and ef.courseId=? order by orderOfEnterpriseField";
                $query = $this->dbHandle->query($queryCmd,array($courseId));
                $results = $query->result_array();
                $data = array();
                if(!empty($results) && is_array($results)) {
                        foreach ($results as $row){
				$entFieldId = "ent_".$row['entFieldid'];
                                $data[$entFieldId] = array();
				$data[$entFieldId]['fieldName'] = $row['fieldName'];
				$data[$entFieldId]['status'] = $row['status'];
				$data[$entFieldId]['order'] = $row['orderOfEnterpriseField'];
				$data[$entFieldId]['creationDate'] = $row['creationDate'];
				$data[$entFieldId]['id'] = "ent_".$row['entFieldid'];
				$data[$entFieldId]['typeOfField'] = $row['typeOfField'];
				$data[$entFieldId]['shikshaFields'] = array();
                        }
			
			//Now get the mapping of this Enterprise field with Shiksha fields.
			$queryCmd = "select * from OF_ENT_ENTERPRISE_FIELDS ef, OF_ENT_ENTERPRISE_FIELDS_MAPPING efm where ef.status = 'live' and ef.courseId=? and efm.entFieldId = ef.entFieldid order by efm.entFieldId, efm.Order";
			$query = $this->dbHandle->query($queryCmd,array($courseId));
			$results = $query->result_array();
			if(!empty($results) && is_array($results)) {
				$i=0;
				foreach ($results as $row){
					$entFieldId = "ent_".$row['entFieldid'];
					$data[$entFieldId]['shikshaFields'][$i]['shikshaFieldId'] = "int_".$row['shikshaFieldId'];
					$data[$entFieldId]['shikshaFields'][$i]['Order'] = $row['Order'];
					$data[$entFieldId]['shikshaFields'][$i]['Separator'] = $row['Separator'];
					$data[$entFieldId]['shikshaFields'][$i]['shikshaFieldName'] = $row['shikshaFieldName'];
					$i++;
				}
			}			
                }
		
                return $data;
	}

	function getExternalFormsAndData($courseId,$parameters = array()){
		$this->initiateModel();

                //Check for External forms for which the complete data needs to be returned
		$paramArray = array($courseId);
                if(isset($parameters['forms']) && count($parameters['forms'])>0){
                      $formIdStrCommaSep = implode(",",$parameters['forms']);
                      $formIdStr = " AND ef.entFormId IN (?) ";
		      array_push($paramArray, $parameters['forms']);
		      //$customFormCheck = " AND cd.onlineFormId IN (".$formIdStrCommaSep.") ";
                }

		//Get the externa forms which are uploaded and their respective source
		$queryCmd = "select ds.sourceId, ds.sourceName, ef.entFormId, ef.userId, ef.creationDate,ef.status from OF_ENT_EXTERNAL_DATA_SOURCE ds, OF_ENT_EXTERNAL_FORMS ef where ef.status = 'live' and ef.courseId=? and ef.courseId=ds.courseId and ef.sourceId=ds.sourceId $formIdStr";
                $query = $this->dbHandle->query($queryCmd,$paramArray);
                $results = $query->result_array();

		//Now, process the forms
                $data = array();
		$formArr = array();
                if(!empty($results) && is_array($results)) {
					
                        foreach ($results as $row){
				$entFormId = "ext_".$row['entFormId'];
                                $data[$entFormId] = array();
				$data[$entFormId]['sourceName'] = $row['sourceName'];
				$data[$entFormId]['status'] = $row['status'];
				$data[$entFormId]['sourceId'] = $row['sourceId'];
				$data[$entFormId]['creationDate'] = $row['creationDate'];
				$data[$entFormId]['userId'] = $row['userId'];
				$data[$entFormId]['type'] = 'external';
				$data[$entFormId]['fields'] = array();
				
	                }

	                //Check for fieldIds of External forms
        	        $fieldIdStr = '';
                	if(isset($parameters['fields']['external']) && count($parameters['fields']['external'])>0){
                        	$fieldIdStr = implode(",",$parameters['fields']['external']);
	                        $fieldIdStr = " AND entf.entFieldid IN (?) ";
				array_push($paramArray, $parameters['fields']['external']);
        	        }
			else if(!$parameters['forms']){
				$fieldIdStr = " AND entf.entFieldid IN ('') ";
			}
			
			//Now get the field values for each forms
			$queryCmd = "select ud.entFormId, ud.entFieldId, ud.fieldValue, entf.fieldName, entf.typeOfField from OF_ENT_EXTERNAL_FORMS_USER_DATA ud, OF_ENT_EXTERNAL_FORMS ef, OF_ENT_ENTERPRISE_FIELDS entf where ef.status = 'live' and ef.courseId=? and ef.entFormId = ud.entFormId and entf.status='live' and ud.entFieldId=entf.entFieldid and entf.courseId=ef.courseId $formIdStr $fieldIdStr";

			$query = $this->dbHandle->query($queryCmd,$paramArray);
			$results = $query->result_array();
			$fieldArray = array();
			if(!empty($results) && is_array($results)) {
				foreach ($results as $row){
					$entFormId = "ext_".$row['entFormId'];
					$entFieldId = "ent_".$row['entFieldId'];
					$fieldArray['fieldName'] = $row['fieldName'];
					//If this is a date field, convert the date format to Y-m-d
					if($row['typeOfField']=='date' && $row['fieldValue']!=''){
						
						$dateVal = $row['fieldValue'];
						$dateObj = strtotime($dateVal);
						$fieldArray['value'] = date ( 'Y-m-d' , $dateObj );
					}
					else{
						$fieldArray['value'] = $row['fieldValue'];
					}
					$fieldArray['typeOfField'] = $row['typeOfField'];
					$fieldArray['type'] = 'enterprise';
					$data[$entFormId]['fields'][$entFieldId] = $fieldArray;
				}
			}

	                //Check for fieldIds of Custom fields
        	        $fieldIdStr = '';
                	if(isset($parameters['fields']['custom']) && count($parameters['fields']['custom'])>0){
                        	$fieldIdStr = implode(",",$parameters['fields']['custom']);
	                        $fieldIdStr = " AND cd.columnFieldId IN (".$fieldIdStr.") ";
        	        }
                        else if(!$parameters['forms']){
                                $fieldIdStr = " AND cd.columnFieldId IN ('') ";
                        }
			
			//Now get the custom field values for each forms
			$queryCmd = "select cd.onlineFormId, cd.columnFieldId, cd.value, ec.columnName from OF_ENT_ENTERPRISE_COLUMN_DATA cd, OF_ENT_ENTERPRISE_COLUMN ec where ec.status = 'live' and ec.courseId=? and ec.columnFieldId = cd.columnFieldId and cd.formType = 'external'";
			$query = $this->dbHandle->query($queryCmd,array($courseId));
			$results = $query->result_array();
			if(!empty($results) && is_array($results)) {
				foreach ($results as $row){
					$entFormId = "ext_".$row['onlineFormId'];
					$customFieldId = "cus_".$row['columnFieldId'];
					$data[$entFormId]['fields'][$customFieldId]['fieldName'] = $row['columnName'];
					$data[$entFormId]['fields'][$customFieldId]['value'] = $row['value'];
					$data[$entFormId]['fields'][$customFieldId]['type'] = 'custom';
				}
			}			
                }
                return $data;		
	}
	
	function getCustomFields($courseId){
		$this->initiateModel();
		$queryCmd = "select * from OF_ENT_ENTERPRISE_COLUMN where status = 'live' and courseId=?";
                $query = $this->dbHandle->query($queryCmd,array($courseId));
                $results = $query->result_array();
                $data = array();
                if(!empty($results) && is_array($results)) {
                        foreach ($results as $row){
				$entFieldId = "cus_".$row['columnFieldId'];
                                $data[$entFieldId] = array();
				$data[$entFieldId]['id'] = "cus_".$row['columnFieldId'];
				$data[$entFieldId]['fieldName'] = $row['columnName'];
				$data[$entFieldId]['userId'] = $row['userId'];
				$data[$entFieldId]['creationDate'] = $row['creationDate'];
                        }
                }
                return $data;
	}

	function getInternalFormsAndData($courseId,$parameters = array()){
		$this->initiateModel();
		
                $CI = & get_instance();
                $CI->load->builder('LocationBuilder','location');
                $locationBuilder = new LocationBuilder();
                $locationRepository = $locationBuilder->getLocationRepository();

                //Check for External forms for which the complete data needs to be returned
		$paramArray = array($courseId);
                if(isset($parameters['forms']) && count($parameters['forms'])>0){
                      $formIdStrCommaSep = implode(",",$parameters['forms']);
                      $formIdStr = " AND uf.onlineFormId IN (?) ";
		      array_push($paramArray, $parameters['forms']);
                      $customFormCheck = " AND cd.onlineFormId IN (".$formIdStrCommaSep.") ";
                }

		//Get the internal forms which are filled on Shiksha
                $statuses = " 'paid','accepted','rejected','shortlisted','cancelled','Payment Confirmed','Under Process','GD/PI Update','Payment Awaited','Payment Under Process' "; 
				$queryCmd = "select uf.onlineFormId,uf.instituteSpecId,uf.userId,uf.status
				as formStatus,uf.onlineFormEnterpriseStatus,uf.creationDate,uf.GDPIDate,uf.GDPILocation,p.*,p.status as paymentStatus from OF_UserForms uf
				left join OF_Payments p on(p.onlineFormId=uf.onlineFormId)
				where uf.status IN ($statuses) and uf.courseId = ?
				and uf.formStatus = 'live' $formIdStr
				order by uf.onlineFormId desc ";
                $query = $this->dbHandle->query($queryCmd,$paramArray);
                $results = $query->result_array();
                $data = array();
                if(!empty($results) && is_array($results)) {
                        foreach ($results as $row){
							$intFormId = "int_".$row['onlineFormId'];
							$data[$intFormId] = array();
							$data[$intFormId]['status'] = $row['formStatus'];
							$row['instituteSpecId'] = $row['instituteSpecId']?$row['instituteSpecId']:$row['onlineFormId'];
							$data[$intFormId]['instituteSpecId'] = $row['instituteSpecId'];
							$data[$intFormId]['creationDate'] = $row['creationDate'];
							$data[$intFormId]['userId'] = $row['userId'];
							$data[$intFormId]['fields'] = array();
							$this->populateAdditionalFields($data[$intFormId]['fields'],$row,$locationRepository);
							//_p($row);
							//_p($data);
							//die();
							$data[$intFormId]['customFields'] = array();
						}

                        //Check for fieldIds of Internal forms
                        $fieldIdStr = '';
                        if(isset($parameters['fields']['internal']) && count($parameters['fields']['internal'])>0){
                                $fieldIdStr = "'".implode("','",$parameters['fields']['internal'])."'";
                                $fieldIdStr = " AND ud.fieldName IN (?) ";
				array_push($paramArray, $parameters['fields']['internal']);
                        }
                        else if(!$parameters['forms']){
                                $fieldIdStr = " AND ud.fieldName IN ('') ";
                        }
			
			//Now get the field values for each forms
			$queryCmd = "select ud.* from OF_FormUserData ud, OF_UserForms uf where ud.onlineFormId = uf.onlineFormId and uf.status IN ($statuses) and uf.courseId = ? and uf.formStatus = 'live' $formIdStr $fieldIdStr ";
			$query = $this->dbHandle->query($queryCmd,$paramArray);
			$results = $query->result_array();
			if(!empty($results) && is_array($results)) {
				foreach ($results as $row){
					$intFormId = "int_".$row['onlineFormId'];
					$intFieldName = "int_".$row['fieldName'];
					if($row['fieldName']=='city' || $row['fieldName']=='Ccity' || $row['fieldName']=='pref2IMI' || $row['fieldName']=='gdpiLocation2' || $row['fieldName']=='gdpiLocation3' ){
						$cityObj = $locationRepository->findCity($row['value']);
						$row['value'] = $cityObj->getName();
					}
					if($row['fieldName']=='country' || $row['fieldName']=='Ccountry'){
						$countryObj = $locationRepository->findCountry($row['value']);
						$row['value'] = $countryObj->getName();
					}
					if( $row['value']!='' && ($row['fieldName']=='dateOfBirth' || $row['fieldName']=='weFrom' || $row['fieldName']=='weTill' || strpos($row['fieldName'],'DateOfExamination')!==false || strpos($row['fieldName'],'weTill')!==false || strpos($row['fieldName'],'weFrom')!==false || strpos($row['fieldName'],'Date')!==false)){
						$dateVal = $row['value'];
						$dateVal = implode('/', array_reverse(explode('/',$dateVal)));
						$dateObj = strtotime($dateVal);
						if($dateObj){
							$row['value'] = date ( 'Y-m-d' , $dateObj );
						}else{
							$row['value'] = "";
						}
					}
					$data[$intFormId]['fields'][$intFieldName]['id'] = "int_".$row['fieldId'];
					$data[$intFormId]['fields'][$intFieldName]['fieldName'] = $row['fieldName'];
					$data[$intFormId]['fields'][$intFieldName]['value'] = $row['value'];
					//$data[$intFormId]['fields'][$intFieldName]['isMultiple'] = $row['isMultipleCase'];
					$data[$intFormId]['fields'][$intFieldName]['type'] = 'internal';
				}
			}

                        //Check for fieldIds of Custom fields
                        $fieldIdStr = '';
			$paramArray = array($courseId);

                        if(isset($parameters['fields']['custom']) && count($parameters['fields']['custom'])>0){
                                //$fieldIdStr = implode(",",$parameters['fields']['custom']);
                                $fieldIdStr = " AND cd.columnFieldId IN (?) ";
				array_push($paramArray, $parameters['fields']['custom']);	
                        }
                        else if(!$parameters['forms']){
                                $fieldIdStr = " AND cd.columnFieldId IN ('') ";
                        }

                        if(isset($parameters['forms']) && count($parameters['forms'])>0){
                                //$formIdStrCommaSep = implode(",",$parameters['forms']);
                                $customFormCheck = " AND cd.onlineFormId IN (?) ";
				array_push($paramArray, $parameters['forms']);
                        }

			
			//Now get the custom field values for each forms
			$queryCmd = "select cd.onlineFormId, cd.columnFieldId, cd.value, ec.columnName from OF_ENT_ENTERPRISE_COLUMN_DATA cd, OF_ENT_ENTERPRISE_COLUMN ec where ec.status = 'live' and ec.courseId=? and ec.columnFieldId = cd.columnFieldId and cd.formType = 'internal' $fieldIdStr $customFormCheck ";
			
			$query = $this->dbHandle->query($queryCmd,$paramArray);
			$results = $query->result_array();
			if(!empty($results) && is_array($results)) {
				foreach ($results as $row){
					$entFormId = "int_".$row['onlineFormId'];
					$customFieldId = "cus_".$row['columnFieldId'];
					$data[$entFormId]['customFields'][$customFieldId]['fieldName'] = $row['columnName'];
					$data[$entFormId]['customFields'][$customFieldId]['value'] = $row['value'];
					$data[$entFormId]['customFields'][$customFieldId]['type'] = 'custom';
				}
			}			
                }
				//_p($data);
                return $data;		
	}
	
	function populateAdditionalFields(& $fields,$row,$locationRepository){
		// public static $otherFormFields =  array('GDPIDate','GDPILocation','amount','mode','orderId','bankName','draftNumber','draftDate');
		$this->getSingleField($fields,'instituteSpecId',$row['instituteSpecId']);
		$this->getSingleField($fields,'statusOfForm',$row['formStatus']);
		$this->getSingleField($fields,'paymentId',$row['paymentId']);
		$this->getSingleField($fields,'date',date ( 'Y-m-d' , strtotime($row['date'])));
		$this->getSingleField($fields,'amount',$row['amount']);
		$this->getSingleField($fields,'mode',$row['mode']);
		$this->getSingleField($fields,'bankName',$row['bankName']);
		$this->getSingleField($fields,'draftNumber',$row['draftNumber']);
		$this->getSingleField($fields,'orderId',$row['orderId']);
		$this->getSingleField($fields,'onlineFormEnterpriseStatus',$row['onlineFormEnterpriseStatus']);
		if($row['draftDate']!='0000-00-00' && strtotime($row['draftDate'])){
			$this->getSingleField($fields,'draftDate',date ('Y-m-d', strtotime($row['draftDate'])));
		}
		else{
			$this->getSingleField($fields,'draftDate',$row['draftDate']);
		}
		if(strtotime($row['GDPIDate'])){
			$this->getSingleField($fields,'GDPIDate',date ('Y-m-d', strtotime($row['GDPIDate'])));
		}
		//_p($row);
		if($row['GDPILocation'] && $city = $locationRepository->findCity($row['GDPILocation'])){
			$this->getSingleField($fields,'GDPILocation',$city->getName());
		}
	}
	
	function getSingleField(& $fields,$fieldName,$value){
		$fields['int_'.$fieldName]['fieldName'] = $fieldName;
		$fields['int_'.$fieldName]['value'] = $value;
		$fields['int_'.$fieldName]['type'] = 'internal';
	}
	
	function getOnlineInstituteForCourseId($courseId){
		$this->initiateModel();
		$queryCmd = "SELECT *
					FROM `OF_InstituteDetails`
					WHERE courseId =?";
		$query = $this->dbHandle->query($queryCmd,array($courseId));
		$result  = $query->result_array();
		return $result[0];
	}
	
	function getUserIdsForIncompleteFormsAutoMailer($startTime,$endTime,$date,$data = false,$currentTime = NULL){
		$this->initiateModel();
		//$this->CI = & get_instance();
		$this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder   = new CourseBuilder();
        $this->courseRepository      = $courseBuilder->getCourseRepository();

		$this->load->library('dashboardconfig');
		$online_form_institute_seo_url = DashboardConfig::$institutes_autorization_details_array;
		$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails');
		$online_form_institute_seo_url += $PBTSeoData;
		
		if(!$currentTime) {
			$currentTime = date('Y-m-d H:i:s');
		}
		
		$frequency = 3;
		$queryCmd = "SELECT uf.userId,uf.onlineFormId, idt.courseId, u.email, u.firstname, u.lastname,
					group_concat(distinct if(fieldId=1,ifnull(fud.value,''),'')) FirstName,
					group_concat(distinct if(fieldId=2,ifnull(fud.value,''),'')) MiddleName,
					group_concat(distinct if(fieldId=3,ifnull(fud.value,''),'')) LastName
					FROM OF_UserForms uf
					INNER JOIN OF_InstituteDetails idt ON ( idt.courseId = uf.courseId AND idt.status = 'live' ) 
					LEFT JOIN OF_FormUserData fud ON ( fud.onlineFormId = uf.onlineFormId
							AND fieldId
							IN ( 1, 2, 3 ) )
					LEFT JOIN tuser u ON ( u.userid = uf.userId )
					WHERE 
					uf.courseId >0
					AND uf.status
					IN (
					'started', 'uncompleted', 'completed'
					)
					AND uf.formStatus = 'live' 
					AND MOD(DATEDIFF(?,uf.creationDate),?) = 0 
					AND TIME(uf.creationDate) >= ? 
					AND TIME(uf.creationDate) < ?
					AND TIMESTAMPDIFF(HOUR, uf.creationDate,?) >= 72
					AND TIMESTAMPDIFF(DAY, ? , idt.last_date ) > 2
					group by uf.onlineFormId";
		
		$query = $this->dbHandle->query($queryCmd,array($date,$frequency,$startTime,$endTime,$currentTime,$currentTime));
		$result  = $query->result_array();
		array_map(function(& $tempUser){
			$tempUser['firstname'] = trim($tempUser['FirstName'],",")?trim($tempUser['FirstName'],","):strtoupper($tempUser['firstname']);
			$tempUser['middlename'] = trim($tempUser['MiddleName'],",")?trim($tempUser['MiddleName'],","):'';
			$tempUser['lastname'] = trim($tempUser['LastName'],",")?trim($tempUser['LastName'],","):strtoupper($tempUser['lastname']);
			unset($tempUser['FirstName']);
			unset($tempUser['MiddleName']);
			unset($tempUser['LastName']);
		},$result);
		
		$users = array();
		foreach($result as $user){
			if($data){
				$course = $this->courseRepository->find($user['courseId']);
				$users[$user['userId']]['email'] = $user['email'];
				$users[$user['userId']]['name'] = $user['firstname']." ".($user['middlename']?$user['middlename']." ":'').$user['lastname'];
				$users[$user['userId']]['course_name'][] = $course->getName();
				$users[$user['userId']]['institute_name'][] = $course->getInstituteName();
				if(array_key_exists('seo_url', $online_form_institute_seo_url[$course->getInstId()])) {$seo_url = SHIKSHA_HOME.$online_form_institute_seo_url[$course->getInstId()]['seo_url'];} else {$seo_url = SHIKSHA_HOME."/Online/OnlineForms/showOnlineForms/".$course->getId();}
				$users[$user['userId']]['online_form_url'][] = $seo_url;
			}else{
				$users[$user['userId']] = TRUE;
			}
		}
		return $users;
	}
	

	
	function getDataForIncompleteFormsAutoMailer(){
		$this->initiateModel();
		$queryCmd = "SELECT uf.onlineFormId, idt.courseId, u.email, u.firstname, u.lastname,
							group_concat(distinct if(fieldId=1,ifnull(fud.value,''),'')) FirstName,
							group_concat(distinct if(fieldId=2,ifnull(fud.value,''),'')) MiddleName,
							group_concat(distinct if(fieldId=3,ifnull(fud.value,''),'')) LastName
							FROM OF_UserForms uf
							INNER JOIN OF_InstituteDetails idt ON ( idt.courseId = uf.courseId
							AND idt.status = 'live' )
							LEFT JOIN OF_FormUserData fud ON ( fud.onlineFormId = uf.onlineFormId
							AND fieldId
							IN ( 1, 2, 3 ) )
							LEFT JOIN tuser u ON ( u.userid = uf.userId )
							WHERE uf.courseId >0
							AND TIMESTAMPDIFF( HOUR , uf.creationDate,
							CURRENT_TIMESTAMP ) >72
							AND uf.status
							IN (
							'started', 'uncompleted', 'completed'
							)
							AND uf.formStatus = 'live'
							AND TIMESTAMPDIFF(
							DAY ,
							CURRENT_TIMESTAMP , idt.last_date ) >2
							group by uf.onlineFormId";
		$query = $this->dbHandle->query($queryCmd);
		$result  = $query->result_array();
		
		array_map(function(& $tempUser){
			$tempUser['firstname'] = trim($tempUser['FirstName'],",")?trim($tempUser['FirstName'],","):strtoupper($tempUser['firstname']);
			$tempUser['middlename'] = trim($tempUser['MiddleName'],",")?trim($tempUser['MiddleName'],","):'';
			$tempUser['lastname'] = trim($tempUser['LastName'],",")?trim($tempUser['LastName'],","):strtoupper($tempUser['lastname']);
			unset($tempUser['FirstName']);
			unset($tempUser['MiddleName']);
			unset($tempUser['LastName']);
		},$result);
		
		return $result;
	}
	
	function getUserCurrentLevel($userId){
		$this->initiateModel();
		if($userId){
			$queryCmd = "SELECT level from OF_currentCourseLevel where userid = ?";
			$query = $this->dbHandle->query($queryCmd,array($userId));
			$result = $query->result_array();
			if(count($result) > 0){
				return $result[0]['level'];
			}
		}
	}
	
	function setUserCurrentLevel($userId,$level){
		$this->initiateModel('write');
		if($userId && $level){
			$queryCmd = "update OF_currentCourseLevel set level = ? where userId= ?";
			$query = $this->dbHandle->query($queryCmd,array($level,$userId));
		}
	}
	
	
	function checkThirdPageProfile($userid,$courseId){
		$this->initiateModel();
		if($courseId){
			$query = $this->dbHandle->query("select count(*) as count from OF_FormUserData ufd join OF_UserForms uf on
											(uf.onlineFormId = ufd.onlineFormId)
											where uf.userId = ? and courseId = ? and fieldName = ?",
											array($userid,$courseId,'graduationExaminationName'));
			$result = $query->result_array();
			if($result[0]['count'] == "0"){
				return true;
			}else{
				return false;
			}
			
		}else{
			return false;
		}
		
	}
	
	
	function checkOnlineFormForMrecApp($userId,$listingId){
		$this->initiateModel();
		$this->CI = get_instance();
		$this->CI->load->library(array('Online_form_client'));
		//load the required library for StudentDashboardClient to get online forms list from online form config file
		$this->CI->load->library('StudentDashboardClient');
		$this->CI->load->library('dashboardconfig');
		$OFFormsArr = DashboardConfig::$institutes_autorization_details_array;
		$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails');
		$OFFormsArr += $PBTSeoData;
		
		$instituteformids = array_keys($OFFormsArr);
		
		if(is_array($listingId)){
			$listingStr = "'" .implode("','",$listingId)."'" ;
			$str = " courseId in (?) ";
		}
		$queryCmd = "SELECT instituteId,last_date,fees,id,courseId,externalURL,instituteId from OF_InstituteDetails where status = 'live' and last_date>=DATE(now()) and $str ";
		if(is_array($listingId)){
			$query = $this->dbHandle->query($queryCmd, array($listingId));
		}
		else{
                	$query = $this->dbHandle->query($queryCmd);
		}
                $results = $query->result_array();
                $resultVal = array();
                if(!empty($results) && is_array($results)) {
                       foreach ($results as $row){
                            $resultVal[$row['courseId']]['last_date'] = $row['last_date'];
			    $resultVal[$row['courseId']]['fees'] = $row['fees'];
			    $resultVal[$row['courseId']]['instituteId'] = $row['instituteId'];
			    if(in_array($row['instituteId'],$instituteformids)){
					$resultVal[$row['courseId']]['showOFLink'] = true;
					$resultVal[$row['courseId']]['OFlink'] = THIS_CLIENT_IP.$OFFormsArr[$row['instituteId']]['seo_url'];
				}
			    
			    
			    $courseIdArr[] = $row['courseId'];
                       }
		       if(is_array($listingId)){
				$remianingCourseIds = array_diff($listingId,$courseIdArr);
		       }
                }
		if(count($remianingCourseIds)>0){
			$listingId = $remianingCourseIds;
		}
		if(count($resultVal)<=0 || count($remianingCourseIds)>0){
		$queryCmd = "SELECT instituteId,last_date,fees,id,courseId,externalURL,instituteId,otherCourses from OF_InstituteDetails where status = 'live' and last_date>=DATE(now())";
			$query = $this->dbHandle->query($queryCmd);
			$results = $query->result_array();
			$i=0;
			if(!empty($results) && is_array($results)) {
				foreach ($results as $row){
					$resultArr[$i]['last_date'] = $row['last_date'];
					$resultArr[$i]['fees'] = $row['fees'];
					$resultArr[$i]['instituteId'] = $row['instituteId'];
					if(in_array($row['instituteId'],$instituteformids)){
					$resultArr[$i]['showOFLink'] = true;
					$resultArr[$i]['OFlink'] = THIS_CLIENT_IP.$OFFormsArr[$resultArr[$i]['instituteId']]['seo_url'];
				}
					$resultArr[$i]['otherCourses'] = $row['otherCourses'];
					if(!empty($row['otherCourses'])){
						$resultArr[$i]['courseArr'] = explode(',',$row['otherCourses']);	
					}else{
						$resultArr[$i]['courseArr'] = array();
					}
					$i++;
				}
			}
			
			if(is_array($listingId)){
				foreach($resultArr as $key=>$value){
					for($j=0;$j<count($value['courseArr']);$j++){
						if(in_array($value['courseArr'][$j],$listingId)){
							$resultVal[$value['courseArr'][$j]]['last_date'] = $value['last_date'];
							$resultVal[$value['courseArr'][$j]]['fees'] = $value['fees'];
							$resultVal[$value['courseArr'][$j]]['instituteId'] = $value['instituteId'];
							if(in_array($value['instituteId'],$instituteformids)){
							$resultVal[$value['courseArr'][$j]]['OFlink'] = THIS_CLIENT_IP.$OFFormsArr[$value['instituteId']]['seo_url'];
							$resultVal[$value['courseArr'][$j]]['showOFLink'] = true;
							}
						}
					}
				}
			}
		}
				
		foreach($resultVal as $key=>$value){
			$query= "select count(*) as total from OF_UserForms where courseId=? and userId=?";
			$queryRes = $this->dbHandle->query($query,array($key,$userId));
			$results = $queryRes->result_array();
			if($results[0]['total'] == 0){
				$finalArr[$key] = $value;
			}
		}
		
		return $finalArr;
	}
	
	function cronForLIBA($courseId){
                $this->initiateModel();
                $query= "select * from OF_Payments where userId In (SELECT userId FROM `OF_UserForms` WHERE `courseId` =187300) and mode='Offline' and status in ('Started', 'Pending', 'Success') and instituteId ='33857' UNION ".
			"select * from OF_Payments where userId In (SELECT userId FROM `OF_UserForms` WHERE `courseId` =187300) and mode='Online' and status ='Success' and instituteId ='33857'";
                $queryRes = $this->dbHandle->query($query);
                $results = $queryRes->result_array();
                $form_data_array = array();
                if(!empty($results) && is_array($results)) {
                        foreach ($results as $row){
                                $form_data_array[] = $row;
                        }
                }
                return $form_data_array;
        }
	
	/**
	* Purpose  : Method to get Online-forms data on which given user has applied(by default it returns all incomplete forms)
	* Params   : 1. User-id 	 - Integer
	* 	     2. Form Status List - Array
	* Author   : Romil Goel
	*/
	function getOnlineFormsByUserId($userId, $statusArr = array('started', 'uncompleted', 'completed')){
		// get db handle
		$this->initiateModel();
		
		// prepare query
		$queryCmd = "	SELECT uf.courseId, id.instituteId, id.last_date, id.fees
				FROM 
				OF_UserForms uf
				INNER JOIN
				OF_InstituteDetails id
				ON(uf.courseId = id.courseId AND id.status = 'live' AND id.last_date >= DATE(now()))
				WHERE uf.courseId > 0
				AND uf.status
				IN (?) 
				AND uf.formStatus = 'live'
				AND uf.userId = ?
				ORDER BY id.last_date";

		$query = $this->dbHandle->query($queryCmd,array($statusArr,$userId));
		$result  = $query->result_array();
		
		// fetch all data from result set and prepare another array
		$data = array();
		foreach($result as $key=>$value)
		{
			$data[$value['courseId']] = $value;
		}
		
		return $data;
	}
	
	/**
	* Purpose  : Method to get all active online forms courses
	* Params   : none
	* Author   : Romil Goel
	*/
	function getActiveOnlineForms(){
		// get db handle
		$this->initiateModel();
		
		// prepare query
		$queryCmd = "	SELECT 
				courseId, instituteId
				FROM OF_InstituteDetails 
				WHERE status = 'live'
				AND last_date >= DATE(now())
				ORDER BY last_date";

		$query = $this->dbHandle->query($queryCmd);
		$result  = $query->result_array();
		
		// fetch all data from result set and prepare another array
		$data = array();
		foreach($result as $key=>$value)
		{
			$data[$value['courseId']] = $value['instituteId'];
		}
		
		return $data;
	}

	/**
	* Purpose  : Method to get No of pages completed by the user of the given Online Form 
	* Params   : 1. Course-IDs 	 - Array
	* 	     2. UserId - Integer
	* Author   : Bharat Issar
	*/
	function getOnlineFormStatus($courseIds , $userId){
		$this->initiateModel();
		if($courseIds == NULL || $userId == NULL ){
			return $courseids;
		}
		
		$courseIds = implode(",", $courseIds);
		$queryCmd = "SELECT uf.onlineFormId, uf.courseId, count(DISTINCT (fud.pageId) ) as NoofPages FROM OF_UserForms uf LEFT JOIN OF_FormUserData fud ON ( uf.onlineFormId = fud.onlineFormId ) WHERE uf.courseId IN (?) AND uf.userId =? AND uf.type =  'course' AND uf.formStatus =  'live' GROUP BY uf.courseId ";
		$courseArr = explode(',',$courseIds);
		$queryRes = $this->dbHandle->query($queryCmd,array($courseArr,$userId));
		$response = array();
		foreach ($queryRes->result_array() as $row){
			$response[] = $row;
		}
		
		return $response;
	}

	/**
	* Purpose  : Method to update action type in templmstable while creating response from online form apply now button
	* Params   : none
	* Author   : Naveen Bhola
	*/
	function update_response_action_type() {
		$this->initiateModel('write');
		
		$queryCmd = "SELECT tl.id,tl.action FROM `OF_UserForms` ouf, tempLMSTable tl WHERE ouf.courseId = tl.listing_type_id AND ouf.userId = tl.userId AND ouf.type = 'course' AND ouf.creationDate >= '2014-10-16 00:00:00' AND tl.submit_date >= '2014-10-16 00:00:00' and tl.action NOT IN ('Client','Online_Application_Started')";
		
		$queryRes = $this->dbHandle->query($queryCmd);
		$response = array();
		foreach ($queryRes->result_array() as $row){
			$response[$row['id']] = $row['action'];
		}

		$content = '';$show_content = '';
		foreach($response as $response_id=>$action) {
			$query = "UPDATE tempLMSTable SET action = 'Online_Application_Started' WHERE id = ?";
			$this->dbHandle->query($query,array($response_id));
			
			$content = $response_id.",".$action."\n";
			$show_content .= $response_id.",".$action."\n";
			$fp = fopen('/tmp/online_response_action_type.txt','a+');
			fwrite($fp, $content);
			fclose($fp);
		}
			
		return $show_content;
		
	}

	/**
	* Purpose  : Method to fetch provide online form status value
	* Params   : 1. $onlineFormId - Online form Id - Integer
	* Author   : Romil Goel
	*/
	function getOnlineFormStatusVal($onlineFormId){

		if(empty($onlineFormId))
			return false;
		
		$this->initiateModel('write');

		$queryCmd = "SELECT status FROM OF_UserForms ".
			    "WHERE onlineFormId = ?";

		$queryRes = $this->dbHandle->query($queryCmd,array($onlineFormId))->result_array();

		$response = $queryRes[0]['status'];

		return $response;
	}

	/**
	* Purpose  : Method to get the row-id of Paytm reponse-log entry for a given online form and user
	* Params   : 1. $onlineFormId - Online form Id - Integer
	* 	     2. $userId - User Id - Integer
	* Author   : Romil Goel
	*/
	function checkPaytmPaymentStatus($onlineFormId, $userId){

		if(empty($onlineFormId) || empty($userId))
			return false;

		$this->initiateModel('write');

		$queryCmd = "SELECT id FROM paytmResponseTrack ".
			    "WHERE application_id = ? AND user_id = ? ";

		$queryRes = $this->dbHandle->query($queryCmd,array($onlineFormId, $userId))->result_array();

		$response = $queryRes[0]['id'];

		return $response;
	}

	/**
	* Purpose  : Method to get given user's latest online application reference number
	* Params   : 1. $userId - User Id - Integer
	* Author   : Romil Goel
	*/
	function getUsersLatestOFApplicationNumber($userId){

		if(empty($userId))
			return false;

		$this->initiateModel('write');

		$queryCmd = "SELECT ofuf.instituteSpecId as instituteSpecId
			     FROM 
			     OF_Payments ofp
			     INNER JOIN
			     OF_UserForms ofuf
			     ON(ofp.userId = ofuf.userId AND ofp.onlineFormId = ofuf.onlineFormId)
			     WHERE 1
			     AND ofp.status = 'Success'
			     AND ofp.userId = ?
			     order by ofp.date desc
			     LIMIT 1";

		$queryRes = $this->dbHandle->query($queryCmd,array($userId))->result_array();

		$response = $queryRes[0]['instituteSpecId'];

		return $response;
	}


	/**
	 * Method to get Incomplete internal and external Online Form data on which given user has applied 
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-02-04
	 * @param  Integer     $userId    UserId
	 * @param  Array       $statusArr form status 
	 * @return Array       User Incomplete forms
	 */
	function getIncompleteOnlineFormsByUserId($userId, $statusArrInternal = array('started', 'uncompleted', 'completed'),$statusArrExternal = array('sent','landed')){
		// get db handle
		$this->initiateModel();
		
		// prepare query
		$queryCmd = "
					SELECT * FROM
					(
						      SELECT uf.courseId, id.instituteId, id.last_date, id.fees, 'Internal' as formType 
						      FROM  OF_UserForms uf INNER JOIN OF_InstituteDetails id ON(uf.courseId = id.courseId) 
						      WHERE id.status = 'live' 
						      AND id.last_date >= DATE(now()) 
						      AND uf.courseId > 0 
						      AND uf.status IN (?) 
						      AND uf.formStatus = 'live' 
						      AND uf.userId = ?
						      AND id.externalURL is NULL
					UNION
							  SELECT ef.course_id, id.instituteId, id.last_date, id.fees,'External' as formType
							  FROM OF_ExternalForms_Tracking ef INNER JOIN OF_InstituteDetails id ON(ef.course_id = id.courseId)  
							  WHERE ef.user_id = ? 
							  AND ef.action IN (?) 
							  AND id.status = 'live' 
							  AND ef.course_id not in (SELECT course_id FROM OF_ExternalForms_Tracking WHERE action = 'submitted' AND user_id = ? GROUP BY course_id) 
							  AND id.last_date >= DATE(now()) 
							  GROUP BY ef.course_id
				    ) a ORDER BY last_date
					";

		$query = $this->dbHandle->query($queryCmd,array($statusArrInternal,$userId,$userId,$statusArrExternal,$userId));
		$result  = $query->result_array();
		
		// fetch all data from result set and prepare another array
		$data = array();
		foreach($result as $key=>$value)
		{
			$data[$value['courseId']] = $value;
		}
		
		return $data;
	}

	/**
	* Purpose : Method to get all unpaid coupon codes of External Online forms
	* Params  : None
	* Author  : Romil Goel
	*/
	public function getExternalOFUnpaidCoupons(){

		$this->initiateModel('read');

		$queryCmd =  " SELECT id,userId,courseId,couponCode".
			     " FROM userAppliedCoupon".
			     " WHERE".
			     " status = 'live'".
			     " AND paytmPaymentStatus = 'unpaid'";

		$result = $this->dbHandle->query($queryCmd)->result_array();

		return $result;
	}
	
	/**
	* Purpose : Method to get given User's External form pixel details
	* Params  : 1. $courseId : Course Id (Integer)
	* 	    2. $userId   : User's Id (Integer)
	* 	    3. $type     : Type of the Pixel (String)
	* Author  : Romil Goel
	*/
	public function getExternalFormPixelInfo($courseId, $userId, $type){

		$this->initiateModel('read');

		$queryCmd =  " SELECT id,attempt,ip_address FROM `OF_ExternalForms_Tracking` WHERE".
			     " course_id = ?".
			     " AND user_id = ?".
			     " AND action = ?";

		$result = $this->dbHandle->query($queryCmd, array($courseId, $userId, $type))->row_array();

		return $result;
	}
	
	/**
	* Purpose : Method to get given User's External form pixel details
	* Params  : 1. $courseId : Course Id (Integer)
	* 	    2. $userId   : User's Id (Integer)
	* Author  : Romil Goel
	*/
	public function getPaytmPaidExternalFormCount($courseId, $userId){

		$this->initiateModel('read');

		$queryCmd =  " SELECT count(*) as num".
			     " FROM userAppliedCoupon".
			     " WHERE".
			     " status = 'live'".
			     " AND paytmPaymentStatus = 'paid'".
			     " AND userId = ?".
			     " AND courseId = ?";

		$result = $this->dbHandle->query($queryCmd, array($courseId, $userId))->row_array();

		return $result['num'];
	}

	/**
	* Purpose : Method to update the external form coupon Paytm payment status
	* Params  : 1. $courseId : Course Id (Integer)
	* 	    	2. $userId   : User's Id (Integer)
	*			3. $fromStatus: Status Value to be replace from
	*			4. $toStatus : Status Value to be replace with
	* Author  : Romil Goel
	*/
	public function updateExternalOFPaymentStatus($courseId, $userId, $fromStatus, $toStatus){
		$this->initiateModel('write');

		$udata = array( 'paytmPaymentStatus' => $toStatus );

		$this->dbHandle->where('userId'				, $userId);
		$this->dbHandle->where('courseId'			, $courseId);
		$this->dbHandle->where('paytmPaymentStatus'	, $fromStatus);
		$this->dbHandle->where('status'				, 'live');

		// update
		$this->dbHandle->update('userAppliedCoupon'	, $udata);
	}

	/**
	* Purpose : Method to update the external form coupon Paytm payment status
	* Params  : 1. $action 		: Pixel Action type
	* 	    	2. $ipAddress   : Pixel's IP Address
	* Author  : Romil Goel
	*/
	public function getPixelCountByIPAddress($action, $ipAddress){
		$this->initiateModel('read');

		$queryCmd = " SELECT count(id) as num FROM OF_ExternalForms_Tracking WHERE".
			     	" action = ?".
			     	" AND ip_address = ?".
			     	" AND DATE(time) = CURRENT_DATE()";

		$result = $this->dbHandle->query($queryCmd, array($action, $ipAddress))->row_array();

		return $result['num'];
	}

	public function getPaidOnlineFormsByUser($type, $userInfo, $courseId = false){

		$this->initiateModel('read');

		$queryCmd = "SELECT ".
					" uac.userId, uac.courseId".
					" FROM".
					" tuser tu".
					" INNER JOIN".
					" userAppliedCoupon uac".
					" ON(tu.userid = uac.userId)".
					" WHERE".
					" uac.status = 'live'".
					" AND uac.paytmPaymentStatus = 'paid'";

		if($type == 'email')
			$queryCmd .= " AND tu.email = ? ";
		else
			$queryCmd .= " AND tu.mobile = ? ";

		if($courseId)
			$queryCmd .= " AND uac.courseId = ? ";

		$dataArray = array();

		if($courseId)
			$dataArray = array($userInfo, $courseId);
		else
			$dataArray = array($userInfo);

		$result = $this->dbHandle->query($queryCmd, $dataArray)->result_array();

		return $result;
	}

	/*
	 * sendInternalMailerForInforming
	 *
	 * Purpose: Send an internal mailer to Product and Ops informing them as soon as a user starts filling a form
	 *
	 * @param: UserId, CourseId and Status (Status will be started for now)
	 * @return: NONE
	 */
	 private function sendInternalMailerForInforming($userId,$courseId,$status){
		//Fetch User's information
                $this->load->library('register_client');
                $user_details = $this->register_client->getDetailsforUsers('1',$userId);
                $userEmail = $user_details[0]['email'];
		$userMobile = $user_details[0]['mobile'];
		$userName = $user_details[0]['firstname']." ".$user_details[0]['lastname'];
		$userDisplayName = $user_details[0]['displayname'];
		if($courseId==0){
			$subject='User has Started Master Online Form';
			$content = "<p>Hi, </p><p>A user has just started filling Master Online form. The details are as following:</p><p>UserId: $userId<br/>Name: $userName<br/>DisplayName: $userDisplayName<br/>Email: $userEmail<br>Mobile: $userMobile</p>Best Regards,<p>- Shiksha Tech.</p>";
		}
		else{
			//Fetch Course and Institute Name
                        $this->load->builder("nationalCourse/CourseBuilder");
				        $courseBuilder   = new CourseBuilder();
				        $this->courseRepository      = $courseBuilder->getCourseRepository();

                        $course = $this->courseRepository->find($courseId);
                        $courseName = $course->getName();
                        $instituteName = $course->getInstituteName();
			
			$subject='User has Started Internal Course Online Form';
                        $content = "<p>Hi, </p><p>A user has just started filling Internal Course Online form. The details are as following:</p><p>UserId: $userId<br/>Institute Name: $instituteName<br/>Course Name: $courseName<br/>CourseId: $courseId<br/>Name: $userName<br/>DisplayName: $userDisplayName<br/>Email: $userEmail<br>Mobile: $userMobile</p>Best Regards,<p>- Shiksha Tech.</p>";
		}
	        $this->load->library('alerts_client');
	        $alertClient = new Alerts_client();
         	$email   = array('akanksha.sharma@shiksha.com','piyush.kumar@shiksha.com' );
	        for($i=0;$i<count($email);$i++){
			$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $email[$i], $subject, $content, "html", '', 'n');
         	}
		
	}

	function getUserPaymentStatusPaytm($userId, $courseId) {
            if($userId != '' && $courseId != '') {
                $this->initiateModel('read');
            $sql = "SELECT * FROM OF_ExternalForms_Tracking "
                    . "WHERE action = 'submitted' "
                    . "AND user_id = ? "
                    . "AND course_id = ?";
                $query = $this->dbHandle->query($sql, array($userId,$courseId));
                return ($query->num_rows() >= 1) ? true : false;
            }
            else {
                return false;
            }
        }


    // Takes courseId as input and checks if that course has any live form(internal+external). Works for the case where the courseId is listed as 'otherCourse' of a course.
    function checkIfOnlineFormExists($courseId)
    {
    	if($courseId == '')
    	{
    		return 0;
    	}
    	$this->initiateModel('read');
    	$sql = "select courseId,externalURL,otherCourses from OF_InstituteDetails where status = 'live' and (courseId in (?) or otherCourses like (?)) and last_date > now()";
    	$resultArray = $this->dbHandle->query($sql,array($courseId,'%'.$courseId.'%'))->result_array();
    	if(empty($resultArray)){
    		$res['hasOnlineForm'] = 0;
    	}
    	else{
    		$otherCourses = array();
    		$otherCourses = explode(',', $resultArray[0]['otherCourses']);
    		if(!in_array($courseId, $otherCourses) && $resultArray[0]['courseId'] != $courseId){
    			return 0;
    		}
    		$res['hasOnlineForm'] = 1;
    		$res['courseId'] = $resultArray[0]['courseId'];
    		$res['externalURL'] = $resultArray[0]['externalURL'];
    		if($resultArray[0]['externalURL'] != ''){
    			$res['isInternal'] = 'false';
    		}
    		else{
    			$res['isInternal'] = 'true';
    		}	
    	}
    	return $res;
    }

      /***
     * functionName : getOnlineFormAllCourses
     * functionType : return an array
     * desciption   : this code is used to make online form config for other course's
     * @author      : akhter
     * @team        : UGC
    ***/
    function getOnlineFormAllCourses()
    {
	$dbHandle = $this->getReadHandle();
	$query = "SELECT `instituteId`, `courseId`, `otherCourses` FROM OF_InstituteDetails where last_date >= DATE(now()) AND status = 'live'";
	return $dbHandle->query($query)->result_array();
    }

    // one time cron function need to remove after that THIS_CODE_NEEDS_TO_BE_REMOVE_AFTER_RECAT_GOES_LIVE
    function getPBTSeoDetails(){
    	$this->initiateModel();
    	$this->dbHandle->select('courseId,seoUrl,instituteId');
    	$this->dbHandle->from('OF_PBTSeoDetails');
    	//$this->dbHandle->where('status','live');
    	$result = $this->dbHandle->get()->result_array();
    	//echo $this->dbHandle->last_query();
    	return $result;
    }

    // one time cron function need to remove after that THIS_CODE_NEEDS_TO_BE_REMOVE_AFTER_RECAT_GOES_LIVE
    function updatePBTSeoDetailsTable($courseDetails){
    	if(is_array($courseDetails) && count($courseDetails) > 0){
    		$this->initiateModel();
    		$sql = "UPDATE OF_PBTSeoDetails set ";
    		foreach ($courseDetails as $courseId => $courseDetail) {
    			$updateQuery = 'seoTitle = ?, seoDescription = ?, seoUrl = ? where courseId = ?';
    			$updateQuery  = $sql.$updateQuery.';';
    			$this->dbHandle->query($updateQuery, array($courseDetail['seoTitle'],$courseDetail['seoDescription'],$courseDetail['seoUrl'],$courseId));
    		}
    	}
    }

    function getAllAvailableOnlineForms(){
    	$this->initiateModel();
    	$sql = "SELECT id, courseId, otherCourses, externalURL, last_date, creationDate from OF_InstituteDetails where status = 'live' and last_date >= DATE(now())";
    	return $this->dbHandle->query($sql)->result_array();
    }

    
    function getInstituteDetailsById($instituteDetailId, $otherCourses = 'true') {

    	if(empty($instituteDetailId)) { 
    		return; 
    	}

    	$this->initiateModel();

    	if($otherCourses == 'true') {
    		$otherCoursesQuery = " and otherCourses != ''";
    	}
    	$sql = "SELECT otherCourses from OF_InstituteDetails where id = ? and status = 'live' and last_date >= DATE(now())".$otherCoursesQuery;
    	return $this->dbHandle->query($sql, array($instituteDetailId))->row_array();
    }
}

<?php
class OnlineFormEnterprise_model extends MY_Model {
	private $dbHandle = '';
	function __construct(){
		parent::__construct('OnlineForms');
	}

        private function initiateModel($operation='read'){
		$appId = 1;
		$this->load->library('OnlineFormConfig');
		$onlineConfig = new OnlineFormConfig();
		//$dbConfig = array( 'hostname'=>'localhost');
		//$onlineConfig->getDbConfig($appID,$dbConfig);
		//$this->dbHandle = $this->load->database($dbConfig,TRUE);
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	        $this->dbHandle = $this->getWriteHandle();
		}		
	}

        function checkOnlineFormEnterpriseTabStatus($userId, $institute_id){
            if(!is_resource($dbHandleSent)){
    			$this->initiateModel();
    	    }else{
    			$this->dbHandle = $dbHandleSent;
                }
            $queryCmd = "select lm.listing_title,listing_type_id,ofid.courseId from listings_main lm,`OF_InstituteDetails` ofid where lm.username =?  and lm.listing_type in ('institute','university_national') and lm.status='live' and ofid.`instituteId`=lm.listing_type_id and ofid.status = 'live' AND (ofid.externalURL IS NULL OR ofid.externalURL='')";

            if($institute_id>0){
                $queryCmd .= " and lm.listing_type_id=?";
            }


            $query = $this->dbHandle->query($queryCmd,array($userId, $institute_id));

    	    $result = $query->row_array(0);
    	    $numOfRows = $query->num_rows();
            $final =array();
	       if($numOfRows){ $final = $result;}else{$final['listing_title'] = 'None';}
            return $final;
        }

        function sendAlertFromEnterpriseToUser($userAndFormIds=array(),$msgId,$instituteId,$calenderDate,$typeOfUser,$instituteSpecId){
            if(!is_resource($dbHandleSent)){
			$this->initiateModel('write');
	    }else{
			$this->dbHandle = $dbHandleSent;
            }
            $valueToSet='';
            $valueForEnterpriseStatus='';
            if($msgId==1){
                if($typeOfUser=='institute'){$valueForEnterpriseStatus  = " onlineFormEnterpriseStatus= 'Payment Received'";$flag=50;}
                if($typeOfUser=='user'){ $valueToSet = " status='Payment Under Process'";}
            }else if($msgId==4){
                $valueToSet = "status = 'accepted' ,";
                $valueForEnterpriseStatus  = " onlineFormEnterpriseStatus= 'Accepted'";
            }else if($msgId==17){
                $valueToSet = "status = 'cancelled' ,";
                $valueForEnterpriseStatus  = " onlineFormEnterpriseStatus= 'Cancelled' ";
            }else if($msgId==18){
                $valueToSet = "status = 'shortlisted' ,";
                $valueForEnterpriseStatus  = " onlineFormEnterpriseStatus= 'Shortlisted'";
            }else if($msgId==6){
                $valueToSet = "status = 'rejected' ,";
                $valueForEnterpriseStatus  = " onlineFormEnterpriseStatus= 'Rejected' ";
            }else if($msgId==7 && $calenderDate!='yyyy-mm-dd'){
                $date = explode("/",$calenderDate);
                $valueToSet = "gdpiDate='".$date[2]."-".$date[1]."-".$date[0]."' , status='GD/PI Update',";
                $valueForEnterpriseStatus  = " onlineFormEnterpriseStatus= 'GD/PI Update'";
            }else if($msgId==22){
                $valueToSet = "status = 'Cancellation Requested' ,";
                $valueForEnterpriseStatus  = " onlineFormEnterpriseStatus= 'Cancellation Requested'";
            }
                
            $userAndFormIdsArray = json_decode($userAndFormIds,true);
	    //error_log("sendAlertFromEnterpriseToUserModel<<==>>".print_r($userAndFormIdsArray,true));
            $lenthOfArray = count($userAndFormIdsArray[information]);
            $finalArr = $userAndFormIdsArray[information];
            $userId ='';
            $formId ='';
            for($i=0;$i<$lenthOfArray;$i++){
                $data =   $finalArr[$i];
                $userId = $data['userid'];
                $formId = $data['formid'];
                $queryCmd = "insert into OF_FormInstituteUserMessageTable (instituteId,userId,onlineFormId,msgId) values (?,?,?,?)";
                $queryParam = array($instituteId,$userId,$formId,$msgId);
				if($flag == 50) {
                    $queryCmd = "insert into OF_FormInstituteUserMessageTable (instituteId,userId,onlineFormId,msgId) values (?,?,?,?)";
					$queryParam = array($instituteId,$userId,$formId,$flag);
				}
	            $queryRes = $this->dbHandle->query($queryCmd,$queryParam);
                //error_log("sendAlertFromEnterpriseToUserModel<<==>>".print_r($calenderDate,true));
                if($msgId==1 || $msgId==4 || $msgId==6|| ($msgId==7 && $calenderDate!='yyyy-mm-dd') || $msgId==17 || $msgId==18){
                    if($instituteSpecId=="")
                    {
                        $queryCmd1 = "update OF_UserForms set $valueToSet $valueForEnterpriseStatus where userId=? and onlineFormId=?";
                        $queryRes1 = $this->dbHandle->query($queryCmd1,array($userId,$formId));
                    }
                    else
                    {
                        $queryCmd1 = "update OF_UserForms set $valueToSet $valueForEnterpriseStatus where instituteSpecId=?";
                        //error_log("sendAlertFromEnterpriseToUserModel<<==>>".print_r($queryCmd1,true));
                        $queryRes1 = $this->dbHandle->query($queryCmd1,array($instituteSpecId));
                    }
                }

                 if($msgId==17){ $query = "update OF_FormInstituteUserMessageTable set status='Viewed' where msgId=22 and onlineFormId=? and userId=? and instituteId=?"; $queryRes = $this->dbHandle->query($query,array($formId,$userId,$instituteId));
}
if($msgId==70){ $query = "update OF_FormInstituteUserMessageTable set status='Viewed' where msgId=70 and onlineFormId= ? and userId=? and instituteId=?"; $queryRes = $this->dbHandle->query($query,array($formId,$userId,$instituteId));
}
                if($msgId==1){
                     if($typeOfUser=='institute')
                          $queryCmd2 = "update OF_FormInstituteUserMessageTable set status='Viewed' where msgId in ('31','40') and onlineFormId=? and userId=? and instituteId=?";
                    else
                          $queryCmd2 = "update OF_FormInstituteUserMessageTable set status='Viewed' where msgId=31 and onlineFormId=? and userId=? and instituteId=?";
		    $queryRes2 = $this->dbHandle->query($queryCmd2,array($formId,$userId,$instituteId));

                     if($typeOfUser=='institute'){
                         $queryCmd3 = "insert into OF_FormInstituteUserMessageTable (instituteId,userId,onlineFormId,msgId) values (?,?,?,'24')";
                         $queryRes3 = $this->dbHandle->query($queryCmd3,array($instituteId,$userId,$formId));
                     }
                }
            }
          }

          function getAllAlerts($onlineFormEnterpriseInfo,$userTypeString){
              if(!is_resource($dbHandleSent)){
			$this->initiateModel();
	    }else{
			$this->dbHandle = $dbHandleSent;
            }
            
            if($userTypeString=='userAll'){
            	$userType = " and ofm.userType='onlineFormUser'";
           		$status   = '';
            }else{
	            if($userTypeString=='All'){
	               $userType = " and ofm.userType= 'enterpriseUser'";
	               $status   = '';
	            }else{
	               $userType = " and ofm.userType='{$userTypeString}'";
	               $status   = " and offiumt.status ='Unviewed' ";
	            }
            }
            $formArray=array();
            $userArray=array();
            $userIds= '';
            $comma = '';
            $onlineFormEnterpriseInfo = json_decode($onlineFormEnterpriseInfo, true);
            if($userTypeString != 'onlineFormUser' && $userTypeString!='userAll') {
            	$instituteInfo =  $onlineFormEnterpriseInfo[instituteInfo][0][0];
            	$instituteId = $instituteInfo[institute_id];
            	$append_query = " and offiumt.instituteId=".$this->dbHandle->escape($instituteId);
            }
            for($i=0;$i<count($onlineFormEnterpriseInfo['instituteDetails'][0]);$i++){
                if($i>0)$comma=',';
               // $temp = $onlineFormEnterpriseInfo['mainInfo'][$i];
                $formArray[] = $onlineFormEnterpriseInfo['instituteDetails'][0][$i]['onlineFormId'];
                //$userIds .= $comma."'".$temp['userId']."'";
                $userArray[] = $onlineFormEnterpriseInfo['instituteDetails'][0][$i]['userId'];
            } 
            $alertDetails=array();
            //$userId = '522,5445,5134,5226,222';
            //$formId = array('1002','1003','1001','1004');
            $k=0;
            foreach($formArray as $frmid){
		$creationDateQuery = "select creationDate as cDate from OF_UserForms where onlineFormId=?"; 
                $queryCD = $this->dbHandle->query($creationDateQuery,array($frmid));
                $resultCD = $queryCD->row();

                // $query = "select offiumt.instituteId,offiumt.msgId,offiumt.userId,offiumt.onlineFormId,ofm.messageType,ofm.messageText,ofm.notifications from OF_MessageTable ofm ,OF_FormInstituteUserMessageTable offiumt where ofm.id=offiumt.msgId and ofm.userType='enterpriseUser' group by offiumt.userId,offiumt.onlineFormId,offiumt.msgId having offiumt.userId in ($userIds) and offiumt.onlineformId=$frmid and offiumt.instituteId=25429";
		$query = "select offiumt.id as alertStatusId,offiumt.status as alertStatus,offiumt.instituteId,offiumt.msgId,offiumt.userId,offiumt.onlineFormId,offiumt.createdDate,ofm.messageType,ofm.messageText,ofm.notifications from OF_MessageTable ofm ,OF_FormInstituteUserMessageTable offiumt where ofm.id=offiumt.msgId $userType and offiumt.userId =? and offiumt.onlineformId=$frmid $append_query $status order by createdDate desc"; 
                
                $j=0;
                $queryRes = $this->dbHandle->query($query, array($userArray[$k]));
                foreach ($queryRes->result_array() as $row){   
                // $onlineFormEnterpriseInfo['mainInfo'][$k] = (array)$onlineFormEnterpriseInfo['mainInfo'][$k];error_log("whatinit Modelkkk<<==>>".print_r($row,true));
                $onlineFormEnterpriseInfo['instituteDetails'][0][$k]['alertsMessageText'][$j]   =  $row['messageText'];
                $onlineFormEnterpriseInfo['instituteDetails'][0][$k]['alertsNotifications'][$j] =  $row['notifications'];
                $onlineFormEnterpriseInfo['instituteDetails'][0][$k]['alertsMsgId'][$j]         =  $row['msgId'];
                $onlineFormEnterpriseInfo['instituteDetails'][0][$k]['alertsCreatedDate'][$j]   =  $resultCD->cDate;
                $onlineFormEnterpriseInfo['instituteDetails'][0][$k]['alertStatus'][$j]   =  $row['alertStatus'];
                $onlineFormEnterpriseInfo['instituteDetails'][0][$k]['alertStatusId'][$j]   =  $row['alertStatusId'];
                if($userTypeString=='All' || $userTypeString=='userAll')
                    $this->changeAlertNotificationsStatus($userArray[$k],$frmid,$userTypeString);
                $j++;
               }
              $k++;
           }  
           return $onlineFormEnterpriseInfo;
          }


          function changeAlertNotificationsStatus($userId,$formId,$userTypeString){
                if(!is_resource($dbHandleSent)){
			$this->initiateModel('write');
                }else{
			$this->dbHandle = $dbHandleSent;
                }
               //$queryCmd = "update OF_FormInstituteUserMessageTable set status='Viewed' where userId='{$userId}' and onlineFormId='{$formId}' and msgId not in ('31','22','25')";
               if($userTypeString=='userAll'){
                        $queryCmd = "update OF_FormInstituteUserMessageTable set status='Viewed' where userId=? and onlineFormId=? and msgId not in ('31','22','40','23')";

                }else{
                        $queryCmd = "update OF_FormInstituteUserMessageTable set status='Viewed' where userId=? and onlineFormId=? and msgId not in ('40','22','25','7','10','11','12','14','15','16','6','4','18','50','29','31')";

                }
               error_log("getAllAlerts Model<<==>>".print_r($queryCmd,true));
               $queryRes = $this->dbHandle->query($queryCmd,array($userId,$formId));
          }
          function updateOnlineFormEnterpriseStatus($instituteId,$userId,$formId){
                if(!is_resource($dbHandleSent)){
                        $this->initiateModel('write');
                }else{
                        $this->dbHandle = $dbHandleSent;
                }
               $queryCmd = "update OF_UserForms set onlineFormEnterpriseStatus='Viewed',readStatus='read' where userId=? and onlineFormId=?";
               error_log("getAllAlerts Model<<==>>".print_r($queryCmd,true));
               $queryRes = $this->dbHandle->query($queryCmd,array($userId,$formId));
                $queryCmd1 = "insert into OF_FormInstituteUserMessageTable (instituteId,userId,onlineFormId,msgId) values (?,?,?,'25')";
               $queryRes1 = $this->dbHandle->query($queryCmd1,array($instituteId,$userId,$formId));

        }

	/***************************************************/
	//Code to Download Forms in CSV,XLS,XML Format Start
	/***************************************************/
	function getOnlineFormLabelsAndValues($courseId,$instituteId){
		$this->initiateModel('read');
		$this->load->library('OnlineEnterpriseConfig');
                $queryCmd = "select onlineFormId,userId from OF_UserForms uf where uf.courseId = ? and uf.status IN ('paid','draft','accepted','rejected','shortlisted','cancelled','Payment Awaited','Payment Under Process','Payment Confirmed','Under Process','GD/PI Update') and uf.formStatus = 'live' ";

                $queryRes = $this->dbHandle->query($queryCmd,array($courseId));
                foreach ($queryRes->result_array() as $row){
                        $res[] = $row;
                }
		
                $queryCmd = '';
                for($i=0;$i<count($res);$i++){
                        $queryCmd .= "select ofud.fieldName as label,ofud.fieldId from OF_FormUserData ofud,OF_FieldsList ofl where ofud.onlineFormId='".$res[$i]['onlineFormId']."' and ofud.userId = '".$res[$i]['userId']."' and ofl.fieldId=ofud.fieldId and ofud.value!='' union select ofud.fieldName as label,ofud.fieldId from OF_FormUserData ofud where ofud.onlineFormId='".$res[$i]['onlineFormId']."' and ofud.userId = '".$res[$i]['userId']."' and ofud.isMultipleCase=1 and ofud.value!=''";
                        if($i<count($res)-1){$queryCmd .=" UNION ";}
                }
		$queryRes = $this->dbHandle->query($queryCmd);
		$i=0;
                foreach ($queryRes->result_array() as $row){
                        $newres[$i][fieldId] = $row[fieldId];
                        $newres[$i][fieldName] = $row[label];
                        $i++;
                }
		for($i=0;$i<count($res);$i++){
                                for($j=0;$j<count($newres);$j++){
					if($newres[$j][fieldId]==NULL){
                                        $queryCmd = "select ofud.value from OF_FormUserData ofud where ofud.onlineFormId = ? and ofud.userId = ? and ofud.isMultipleCase=1 and ofud.fieldName = '".$newres[$j][fieldName]."' and ofud.value!=''";
                                         
                                	}else{
                                        $queryCmd = "select ofud.value from OF_FormUserData ofud where ofud.onlineFormId = ? and ofud.userId = ? and ofud.fieldId='".$newres[$j][fieldId]."'";
					}
				$queryRes = $this->dbHandle->query($queryCmd, array($res[$i]['onlineFormId'], $res[$i]['userId']) );
				$row = $queryRes->row_array();//error_log(print_r($newres[$j][fieldName],true),3,'/home/pranjul/Desktop/file.log');
				 	if(array_key_exists($newres[$j][fieldName],OnlineEnterpriseConfig::$institutes_label_array[$instituteId])){
                                                $newres1[$i][OnlineEnterpriseConfig::$institutes_label_array[$instituteId][$newres[$j][fieldName]]] = $row[value];
                                        }else{
                                                $newres1[$i][$newres[$j]['fieldName']] = $row['value'];
                                        }				
				}
				$query = "select city_name from countryCityTable cct,OF_UserForms ofuf where cct.city_id=ofuf.GDPILocation and ofuf.onlineFormId=?";
				$queryRes = $this->dbHandle->query($query, array($res[$i]['onlineFormId']) );		
				$rowNew = $queryRes->row_array();
				$newres1[$i]['gdpilocation'] = $rowNew['city_name'];
				$newres1[$i]['onlineFormId'] = $res[$i]['onlineFormId'];
		}
                return $newres1;
        }

	/***************************************************/
	//Code to Download Forms in CSV,XLS,XML Format End
	/***************************************************/
	
	/****************************
	Purpose: Function is use to get gdpi locations.
	Input: courseId
	*****************************/
	function getGDPILocationsWithCourseId(){
		$this->initiateModel('read');
		$queryCmd = "select distinct courseId,city_name,gdpiloc.cityId as cityId from countryCityTable cct right join OF_GDPILocations gdpiloc  on (gdpiloc.cityId = cct.city_id) WHERE gdpiloc.status='live' order by gdpiloc.courseId desc";
		$query = $this->dbHandle->query($queryCmd);
		$results = $query->result_array();
		$gdpiloc_array = array();
		if(!empty($results) && is_array($results)) {
            $numOfRows = $query->num_rows(); $i=0;
			$flag = false;$city_name='';$city_id='';
			foreach ($results as $row){
                $i++;
				if($storeCourseId != $row[courseId] && $flag == true && $storeCourseId!=''){
					$gdpiloc_array[$storeCourseId]['cityName'] = $city_name;
					$gdpiloc_array[$storeCourseId]['cityId'] = $city_id;
				}else if($storeCourseId != $row[courseId] && $flag == false  && $storeCourseId!=''){
					$gdpiloc_array[$storeCourseId]['cityName'] = $city_name;
					$gdpiloc_array[$storeCourseId]['cityId'] = $city_id;
				}
				if($storeCourseId == $row[courseId]){$city_name .= ','.$row[city_name];$city_id .= ','.$row[cityId];$flag = false;}
				else{$city_name = $row[city_name];$city_id = $row[cityId];$storeCourseId = $row[courseId];$flag = true;}

                if($i==$numOfRows && $flag == true && $storeCourseId == $row[courseId]){        //If this is the Last row
                    $gdpiloc_array[$storeCourseId]['cityName'] = $city_name;
                    $gdpiloc_array[$storeCourseId]['cityId'] = $city_id;
                }
				
			}
		}
		return $gdpiloc_array;
	}
	
	/****************************
	 Purpose: Function is use to get Institute Details.
	 *****************************/
	
	 function getInstituteForMapping($getExternalForms = false){
                $this->initiateModel('read');
		if(!$getExternalForms){
			$institue_query = "select ofi.instituteId, ins.name as institute_name from OF_InstituteDetails ofi left join shiksha_institutes ins on (ofi.instituteId=ins.listing_id ) where ins.status='live' and ofi.status='live' and  ofi.externalURL is NULL order by ofi.instituteId";
		}
		else{
                        $institue_query = "select ofi.instituteId, ins.name as institute_name from OF_InstituteDetails ofi left join shiksha_institutes ins on (ofi.instituteId=ins.listing_id ) where ins.status='live' and ofi.status='live' order by ins.name";
		}
                $query = $this->dbHandle->query($institue_query);
		$results = $query->result();
                $i=0;
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$institute_array[$i][instituteId] = $row->instituteId;
				$institute_array[$i][instituteName] = $row->institute_name;
                                $i++;
			}
		}
		return $institute_array;
	}
	
	/****************************
	Purpose: Function is use to get Course Details.
	Input: instituteId
	*****************************/
	
	function getCourseForMapping($institueId){
                $this->initiateModel('read');
		$course_query = "select sc.course_id,cd.name as cd.courseTitle from shiksha_courses sc, OF_InstituteDetails ofid where cd.course_id = ofid.courseId and institute_id=? and cd.status='live' and ofid.status='live' and ofid.status='live' ;";
                $query = $this->dbHandle->query($course_query,array($institueId));
		$results = $query->result();
                $i=0;
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$course_array[$i][courseId] = $row->course_id;
				$course_array[$i][courseName] = $row->courseTitle;
                                $i++;
			}
		}
		return $course_array;
	}
		
	/****************************
	Purpose: Function is use to get gdpi locations.
	Input: courseId
	*****************************/
	
	function getFieldsByCourseId($courseId){
		$this->initiateModel('read');
		$queryCmd = "select pageId from OF_PageMappingInForm where formId in (select formId from OF_ListForms where courseId=? and status='live' and status not in ('started','uncompleted','completed')) and status='live'";
		$query = $this->dbHandle->query($queryCmd,array($courseId));
		$results = $query->result();
		$numOfRows = $query->num_rows();
		$final =array();
		if($numOfRows){
			if(!empty($results) && is_array($results)) {
				foreach ($results as $row){
					$pageIds[] = $row->pageId;
				}
			}
			
			//$pageIds = implode(',',$pageIds);
			$i=0;
			
			$queryCmd = "(select distinct fieldId,name from OF_FieldsList where fieldId in (select entitySetId from OF_PageEntityMapping where pageId in (?) and entitySetType='field' and status='live')) UNION (select distinct fieldId,name from OF_FieldsList where fieldId in (select fieldId from OF_GroupList where groupId in (select entitySetId from OF_PageEntityMapping where pageId =3 and entitySetType='group' and status='live')))";
			$query = $this->dbHandle->query($queryCmd, array($pageIds));
			$results = $query->result();
			foreach ($results as $row){
				$res[] = $row;
				$i++;
			}
			$final['result'] = $res;
		}else{
			$final['result'] = 'None';	
		}
                
                return $final;
        }
	
	/****************************
	Purpose: Function is use to get Mapping Information.
	Input: courseId
	*****************************/
	
	function getMappingInfo($courseId){
		$this->initiateModel('read');
		$mappingInfo_query = "select ofeef.orderOfEnterpriseField,ofeefm.Order,ofeefm.shikshaFieldName,ofeef.typeOfField,ofeef.entFieldid,ofeef.fieldName,ofeefm.shikshaFieldId,ofeefm.Separator from OF_ENT_ENTERPRISE_FIELDS ofeef,OF_ENT_ENTERPRISE_FIELDS_MAPPING ofeefm where ofeef.entFieldid = ofeefm.entFieldid and ofeef.courseId=? group by ofeef.fieldName,ofeefm.Order,ofeef.orderOfEnterpriseField order by ofeef.orderOfEnterpriseField asc,ofeef.fieldName,ofeefm.Order";

		$query = $this->dbHandle->query($mappingInfo_query,array($courseId));
		$results = $query->result();
                $i=0;
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$mappingInfo_array[$i][enterprisefieldId] = $row->entFieldid;
				$mappingInfo_array[$i][enterprisefield] = $row->fieldName;
				$mappingInfo_array[$i][shikshafieldid] = $row->shikshaFieldId;
				$mappingInfo_array[$i][seperator] = $row->Separator;
				$mappingInfo_array[$i][orderOfEnterpriseField] = $row->orderOfEnterpriseField;
				$mappingInfo_array[$i][orderOfShikshaField] = $row->Order;
				$mappingInfo_array[$i][shikshaFieldName] = $row->shikshaFieldName;
				$mappingInfo_array[$i][typeOfField] = $row->typeOfField;
                                $i++;
			}
		}
		return $mappingInfo_array;
               
	}
	
	/****************************
	Purpose: Function is use to insert Mapping Details.
	Input: mappinng information,courseId
	*****************************/
	
	function insertMapping($result,$courseId){
		$this->initiateModel('write');
		$this->deleteAllTabs($courseId);
		$last_insert_id='';
		for($i=0;$i<count($result);$i++)
		{
		   for($j=0;$j<count($result[$i]);$j++){
			$enterpriseFieldId = $result[$i][$j]['EnperpriseFieldId'];
			if($result[$i][$j]['orderOfEnterpriseFieldId']==''){
//				$enterpriseFieldOrder = $i+1;
 				$selectOrder = "SELECT max( `orderOfEnterpriseField` ) AS ord FROM `OF_ENT_ENTERPRISE_FIELDS` GROUP BY `courseId` HAVING `courseId` = ?";
                                $query = $this->dbHandle->query($selectOrder,array($courseId));
                                $results = $query->result_array();
                                $enterpriseFieldOrder = $results[0]['ord']+1;
			}
			else
				$enterpriseFieldOrder = $result[$i][$j]['orderOfEnterpriseFieldId'];
			if($result[$i][$j]['orderOfFieldId']==''){
				if(!empty($enterpriseFieldId)){
				$selectOrder = "SELECT `entFieldId`,max(`Order`) as ord FROM `OF_ENT_ENTERPRISE_FIELDS_MAPPING` group by `entFieldId` having `entFieldId`= ?";

				$query = $this->dbHandle->query($selectOrder,array($enterpriseFieldId));
				$results = $query->result_array();
				
				

					$shikshaFieldOrder = $results[0]['ord']+1;
				}else{
					$shikshaFieldOrder = $j+1;

				}
			}
			else{
				$shikshaFieldOrder = $result[$i][$j]['orderOfFieldId'];	
			}	

			$enterpriseFieldName = trim($result[$i][$j]['EnterpriseField']);
			$typeOfField = $result[$i][$j]['typeOfField'];
			$extractMul = explode('_',$result[$i][$j]['ShikshaField']);
			
			$separator = $result[$i][$j]['Seperator'];
			$enterpriseIdCheck_query = "select * from OF_ENT_ENTERPRISE_FIELDS where entFieldid=?";
			$query = $this->dbHandle->query($enterpriseIdCheck_query,array($enterpriseFieldId));
			$numOfRows = $query->num_rows();
			
			if(isset($extractMul[1]) && $extractMul[1]=='mul'){
				$shikshaFieldName = $result[$i][$j]['ShikshaField'];
			}else{
				$shikshaFieldId = $result[$i][$j]['ShikshaField'];
				if(is_numeric($shikshaFieldId)!='' && $shikshaFieldId!=''){
					$shikshaFieldName_Query = "select name from OF_FieldsList where fieldId = ?";
					$queryRes = $this->dbHandle->query($shikshaFieldName_Query, array($shikshaFieldId) );
					$row = $queryRes->row();
					$shikshaFieldName = $row->name;
					
				}else if(is_numeric($shikshaFieldId)=='' && $shikshaFieldId!=''){
					$extractMul[1] = 'mul';
					$shikshaFieldName = $shikshaFieldId;
				}else{
					$shikshaFieldName = '';
				}	
			}
			
			if(empty($enterpriseFieldId)){
				if(!empty($enterpriseFieldName) && trim($enterpriseFieldName)!='' && $shikshaFieldName!=''){
					if($tempEnterpriseFieldName!=$enterpriseFieldName){
						$enterpriseInsert_query = "insert into OF_ENT_ENTERPRISE_FIELDS (`courseId`,`fieldName`,`orderOfEnterpriseField`,`typeOfField`) values (?,?,?,?)";
						$queryRes = $this->dbHandle->query($enterpriseInsert_query,array($courseId ,$enterpriseFieldName,$enterpriseFieldOrder,$typeOfField));	
						$last_insert_id = $this->dbHandle->insert_id();
					}
					if(isset($extractMul[1]) && $extractMul[1]=='mul'){
						$mappingInsert_query = "insert into OF_ENT_ENTERPRISE_FIELDS_MAPPING (`entFieldId`,`shikshaFieldId`,`Order`,`Separator`,`shikshaFieldName`) values (?,NULL,?,?,?)";
						$queryRes = $this->dbHandle->query($mappingInsert_query,array($last_insert_id,$shikshaFieldOrder,$separator,$shikshaFieldName));
						
					}else{
						$mappingInsert_query = "insert into OF_ENT_ENTERPRISE_FIELDS_MAPPING (`entFieldId`,`shikshaFieldId`,`Order`,`Separator`,`shikshaFieldName`) values (?,?,?,?,?)";
						$queryRes = $this->dbHandle->query($mappingInsert_query,array($last_insert_id,$shikshaFieldId,$shikshaFieldOrder,$separator,$shikshaFieldName));
						
					}
					
					
					$tempEnterpriseFieldName = $enterpriseFieldName;
					
				}
			}else if(!empty($enterpriseFieldId) && $numOfRows>0 && $shikshaFieldName!=''){
				$checkShikshaField_array = array();
				$checkShikshaFieldOrder_array = array();
				$checkShikshaField_Query = "select `shikshaFieldName`,`Order` from `OF_ENT_ENTERPRISE_FIELDS_MAPPING` where `entFieldId`=?";
				$query = $this->dbHandle->query($checkShikshaField_Query,array($enterpriseFieldId));
				$results = $query->result();
				if(!empty($results) && is_array($results)) {
					foreach ($results as $row){
						$checkShikshaField_array[] = $row->shikshaFieldName;
						$checkShikshaFieldOrder_array[] = $row->Order;
					}
				}
				if(!in_array($shikshaFieldName,array_unique($checkShikshaField_array)) && !in_array($shikshaFieldOrder,array_unique($checkShikshaFieldOrder_array)) )
				{
					if(isset($extractMul[1]) && $extractMul[1]=='mul'){
						$mappingInsert_query = "insert into OF_ENT_ENTERPRISE_FIELDS_MAPPING (`entFieldId`,`shikshaFieldId`,`Order`,`Separator`,`shikshaFieldName`) values (?,NULL,?,?,?)";
						$queryRes = $this->dbHandle->query($mappingInsert_query,array($enterpriseFieldId,$shikshaFieldOrder,$separator,$shikshaFieldName));
					}else{
						$mappingInsert_query = "insert into OF_ENT_ENTERPRISE_FIELDS_MAPPING (`entFieldId`,`shikshaFieldId`,`Order`,`Separator`,`shikshaFieldName`) values (?,?,?,?,?)";
						$queryRes = $this->dbHandle->query($mappingInsert_query,array($enterpriseFieldId,$shikshaFieldId,$shikshaFieldOrder,$separator,$shikshaFieldName));
					}
					
				}else{
					$enterpriseUpdate_query = "update OF_ENT_ENTERPRISE_FIELDS set `typeOfField` = ?,`fieldName`=?,`orderOfEnterpriseField`=? where `entFieldId`=?";
					//error_log("enterpriseFieldName3==".$enterpriseUpdate_query);
					$queryRes = $this->dbHandle->query($enterpriseUpdate_query,array($typeOfField,$enterpriseFieldName,$enterpriseFieldOrder,$enterpriseFieldId));
					if(isset($extractMul[1]) && $extractMul[1]=='mul'){
						$mappingUpdate_query = "update OF_ENT_ENTERPRISE_FIELDS_MAPPING set `shikshaFieldId` = NULL , `Separator` = ? , `shikshaFieldName`=? where `entFieldId`=?  and `Order`=?";
						$queryRes = $this->dbHandle->query($mappingUpdate_query,array($separator,$shikshaFieldName,$enterpriseFieldId,$shikshaFieldOrder));
					}else{
						$mappingUpdate_query = "update OF_ENT_ENTERPRISE_FIELDS_MAPPING set `shikshaFieldId` = ? , `Separator` = ? , `shikshaFieldName`=? where `entFieldId`=?  and `Order`=?";
						$queryRes = $this->dbHandle->query($mappingUpdate_query,array($shikshaFieldId,$separator,$shikshaFieldName,$enterpriseFieldId,$shikshaFieldOrder));
					}
					
				}
			}else{ 
				
			}
		   }
		}
	}
		
	/****************************
	Purpose: Function is use to get Draft Details.
	Input: onlineFormId
	*****************************/
	
	function getDraftDetails($onlineFormId){
		$this->initiateModel('read');
		$draftDetails_query = "select bankName,draftNumber,draftDate from OF_Payments where onlineFormId =?";

		$query = $this->dbHandle->query($draftDetails_query,array($onlineFormId));
		$results = $query->result();
                
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$draftDetails_array[bankName] = $row->bankName;
				$draftDetails_array[draftNumber] = $row->draftNumber;
				$draftDetails_array[draftDate] = $row->draftDate;
			}
		}
		return $draftDetails_array;
	}
		
	/****************************
	Purpose: Function is use to Delete Shiksha Field and Enterprise Field From Mapping.
	Input: enterpriseFieldId,orderOfShikshaField,shikshaFieldPosition
	*****************************/
	
	function deteleShikshaFieldFromMapping($enterpriseFieldId,$orderOfShikshaField,$shikshaFieldNumber){
		$this->initiateModel('write');
		if($shikshaFieldNumber==0){
			$deteleEnterpriseFieldFromMapping = "delete from OF_ENT_ENTERPRISE_FIELDS where `entFieldId`=?";
			$query = $this->dbHandle->query($deteleEnterpriseFieldFromMapping,array($enterpriseFieldId));
			$deteleShikshaFieldFromMapping = "delete from OF_ENT_ENTERPRISE_FIELDS_MAPPING where `entFieldId`=?";
			$query = $this->dbHandle->query($deteleShikshaFieldFromMapping,array($enterpriseFieldId));
		}
		
		$deteleShikshaFieldFromMapping = "delete from OF_ENT_ENTERPRISE_FIELDS_MAPPING where `entFieldId`= ? and `Order`=?";
		$query = $this->dbHandle->query($deteleShikshaFieldFromMapping,array($enterpriseFieldId,$orderOfShikshaField));
		
		
	}
	
	/*function getOrderOfEmailIdFromMapping($courseId){
		$this->initiateModel('read');
		$selectOrderOfEmailIdFromMapping = "select entFieldid,orderOfEnterpriseField as emailIdOrder from OF_ENT_ENTERPRISE_FIELDS where typeOfField = 'email' and courseId = ?";
		$query = $this->dbHandle->query($selectOrderOfEmailIdFromMapping,array($courseId));
		$resultQ = $query->row();
		$emailIdOrder = $resultQ->emailIdOrder;
		return $emailIdOrder;
	}*/
function getOrderOfEmailIdFromMapping($courseId){
                $this->initiateModel('read');
                $selectOrderOfEmailIdFromMapping = "select entFieldid,orderOfEnterpriseField as emailIdOrder,typeOfField from OF_ENT_ENTERPRISE_FIELDS where courseId = ? order by orderOfEnterpriseField";
                $query = $this->dbHandle->query($selectOrderOfEmailIdFromMapping,array($courseId));
		$results = $query->result();
$i=1;          
                foreach ($results as $row){


                        if($row->typeOfField=='email'){
                                $arr['emailIdOrder'] = $i;
				$arr['entFieldid'] = $row->entFieldid;
			}
                        $i++;
                }
                return $arr;
        }  

	function existingEmailIds($courseId){
		$this->initiateModel('read');
		$queryEmails ="SELECT value FROM OF_FormUserData WHERE fieldName = 'email' AND onlineFormId IN ( SELECT onlineFormId FROM `OF_UserForms` WHERE `courseId` = ? and formStatus='live' and status not in ('started','uncompleted','completed'))";
		$queryRes = $this->dbHandle->query($queryEmails,array($courseId));
		foreach ($queryRes->result_array() as $row){
			$emailArray[] = strtolower($row['value']);
		}
		return $emailArray;
	}


	/****************************
	Purpose: Function is use to insert external onlineForm Information.
	Input: courseId,fieldValues,sourceName,mappingElements,userId,type
	**************************/
	function insertExternalOnlineFormInformationIntoDatabase($courseId,$result,$sourceName,$mappingElements,$userId,$type,$maximumCountForForms,$maximumCountForFormsData,$fileName,$numberOfRowsNotContainEmailId,$entFieldid,$emailIdOrder){
		$this->initiateModel('write');
                $sourceInsert_query = "insert into OF_ENT_EXTERNAL_DATA_SOURCE (`sourceName`,`courseId`) values (?,?)";
                $query = $this->dbHandle->query($sourceInsert_query,array($sourceName ,$courseId));
                $sourceId = $this->dbHandle->insert_id();
		$tmpArray1 = $this->existingEmailIds($courseId);
/*		$selectOrderOfEmailIdFromMapping = "select entFieldid,orderOfEnterpriseField as emailIdOrder from OF_ENT_ENTERPRISE_FIELDS where typeOfField = 'email' and courseId = ?";
		$query = $this->dbHandle->query($selectOrderOfEmailIdFromMapping,array($courseId));
		$resultQ = $query->row();
		$emailIdOrder = $resultQ->emailIdOrder;
		$entFieldid = $resultQ->entFieldid;
*/		
		$queryForEmailIds = "select fieldValue from OF_ENT_EXTERNAL_FORMS_USER_DATA where entFieldId=?";
		$queryRes = $this->dbHandle->query($queryForEmailIds,array($entFieldid));
                foreach ($queryRes->result_array() as $row){
			$tmpArray2[] = strtolower(trim($row['fieldValue']));
		}
		if(!empty($tmpArray2))
			$tmpArray = array_merge($tmpArray1,array_unique($tmpArray2));
		else
			$tmpArray = $tmpArray1;
		$numOfFormRows = 0;
		$numOfFormsDataRows = 0;
		//$tmpArray = array();
                for($i=0;$i<count($result);$i++){
                        $externalFormInsert_query = "insert into OF_ENT_EXTERNAL_FORMS (`courseId`,`sourceId`,`userId`) values (?,?,?)";
                        $query = $this->dbHandle->query($externalFormInsert_query,array($courseId,$sourceId,$userId));
                        $entFormId[$i] = $this->dbHandle->insert_id();
			$numOfFormRows++;
			
                        for($j=0;$j<count($result[0]);$j++){
				if($j == ($emailIdOrder-1)){
					if(in_array(strtolower(trim($result[$i][$emailIdOrder-1])),$tmpArray)){
						$trackEmailArray[]  = strtolower(trim($result[$i][$emailIdOrder-1]));
						$trackFormIdArray[]  = $entFormId[$i];
					}else{
						$tmpArray[] = strtolower(trim($result[$i][$emailIdOrder-1]));
					}
				}
                                $enterpriseFieldId = $mappingElements[$j][entFieldId];
                                if($j == 0)
					$externalFormValueInsert_query_values = "('".$entFormId[$i]."','".$enterpriseFieldId."','".addslashes($result[$i][$j])."')";
				else
					$externalFormValueInsert_query_values .= ", ('".$entFormId[$i]."','".$enterpriseFieldId."','".addslashes($result[$i][$j])."')";
				$numOfFormsDataRows++;
                        }
			$externalFormValueInsert_query = "insert into OF_ENT_EXTERNAL_FORMS_USER_DATA (`entFormId`,`entFieldId`,`fieldValue`) values ".$externalFormValueInsert_query_values;
                        $query = $this->dbHandle->query($externalFormValueInsert_query);
			if($i%10==0)
				$this->writeInFile('{"'.$courseId.'":{"externalFormCount":"'.($i+1).'","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"'.$maximumCountForForms.'"}}',$courseId);
			
                }
		
		$this->writeInFile('{"'.$courseId.'":{"externalFormCount":"'.($i).'","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"'.$maximumCountForForms.'","ERRORMSG":"ALL_DRAFTED_SUCCESSFULLY"}}',$courseId);
		
		if(($numOfFormRows==$maximumCountForForms) && ($numOfFormsDataRows==$maximumCountForFormsData)){
			$numOfUpdatedFormRows = 0;
			$updateSourceStatusQuery = "update OF_ENT_EXTERNAL_DATA_SOURCE set status='live' where sourceId = ?";
			$query = $this->dbHandle->query($updateSourceStatusQuery,array($sourceId));
			$rowCount=0;
			$entFormIdString = $entFormId;//"'" . implode("','", $entFormId) . "'";

			$updateFormsStatusQuery = "update OF_ENT_EXTERNAL_FORMS set status='live' where entFormId in (?)";
			$query = $this->dbHandle->query($updateFormsStatusQuery,array($entFormIdString));

			$numOfUpdatedFormRows = $this->dbHandle->affected_rows();
			$updateFormsStatusQuery = "update OF_ENT_EXTERNAL_FORMS_USER_DATA set status='live' where entFormId in (?)";
			$query = $this->dbHandle->query($updateFormsStatusQuery,array($entFormIdString));

			$countStatusOfUpdatedData = "select * from OF_ENT_EXTERNAL_FORMS_USER_DATA where status='live' and entFormId in (?)";
			$query = $this->dbHandle->query($countStatusOfUpdatedData,array($entFormIdString));


			$num_rows = $query->num_rows();
			$rowCount = $num_rows;
			if(($numOfUpdatedFormRows == $maximumCountForForms) && ($maximumCountForFormsData==$rowCount)){
				$filterTrackEmailArray = array_unique($trackEmailArray);
				$arrayToStringEmail = implode(",",$filterTrackEmailArray);
				$filterTrackFormIdArray = array_unique($trackFormIdArray);
				$arrayToStringFormIds = implode(",",$filterTrackFormIdArray);
				if($numberOfRowsNotContainEmailId==0){
					if(!empty($filterTrackEmailArray)){
						$this->writeInFile('{"'.$courseId.'":{"externalFormCount":"'.($i).'","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"'.$maximumCountForForms.'","ERRORMSG":"SUCCESSFULLY_MADE_LIVE_DUPLICATE","EMAIL":"'.base64_encode($arrayToStringEmail).'","FORMIDS":"'.$arrayToStringFormIds.'","SOURCEID":"'.$sourceId.'"}}',$courseId);
					}else{
						$this->writeInFile('{"'.$courseId.'":{"externalFormCount":"'.($i).'","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"'.$maximumCountForForms.'","ERRORMSG":"SUCCESSFULLY_MADE_LIVE"}}',$courseId);
					}
				}else{
					if(!empty($filterTrackEmailArray)){
						$this->writeInFile('{"'.$courseId.'":{"externalFormCount":"'.($i).'","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"'.$maximumCountForForms.'","ERRORMSG":"SUCCESSFULLY_MADE_LIVE_DUPLICATE_WITH_EMAIL_BLANK","EMAIL":"'.base64_encode($arrayToStringEmail).'","FORMIDS":"'.$arrayToStringFormIds.'","SOURCEID":"'.$sourceId.'","numberOfRowsNotContainEmailId":"'.$numberOfRowsNotContainEmailId.'"}}',$courseId);
					}else{
						$this->writeInFile('{"'.$courseId.'":{"externalFormCount":"'.($i).'","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"'.$maximumCountForForms.'","ERRORMSG":"SUCCESSFULLY_MADE_LIVE_WITH_EMAIL_BLANK","numberOfRowsNotContainEmailId":"'.$numberOfRowsNotContainEmailId.'"}}',$courseId);
					}
				}
				//return 'Success';
			}else{
				$this->writeInFile('{"'.$courseId.'":{"externalFormCount":"'.($i).'","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"'.$maximumCountForForms.'","ERRORMSG":"PROBLEM_WHILE_UPLOADING"}}',$courseId);
				//return 'Failed';
			}
		}else{
			$this->writeInFile('{"'.$courseId.'":{"externalFormCount":"'.($i).'","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"'.$maximumCountForForms.'","ERRORMSG":"PROBLEM_WHILE_UPLOADING"}}',$courseId);			
		}
        }
	
	function writeInFile($jsonString,$courseId){
		$myFile = "Course$courseId.txt";
		$fh = fopen('/tmp/'.$myFile, 'w') or die("can't open file");
		$stringData = $jsonString;
		fwrite($fh, $stringData);
		fclose($fh);
	}

	/****************************
	Purpose: Function is use to get Enterprise Field Name.
	Input: courseId
	**************************/
	
	function getHeading($courseId){
		$this->initiateModel('read');
		$headingQuery = "SELECT ofeef.entFieldid,ofeef.fieldName FROM `OF_ENT_ENTERPRISE_FIELDS` ofeef, OF_ENT_ENTERPRISE_FIELDS_MAPPING ofeefm where courseId=? and ofeef.entFieldid = ofeefm.entFieldid group by fieldName order by orderOfEnterpriseField;";
		$query = $this->dbHandle->query($headingQuery,array($courseId));
		$results = $query->result();
                $numOfRows = $query->num_rows();
		$i=0;
		if($numOfRows){
			if(!empty($results) && is_array($results)) {
				foreach ($results as $row){
					$heading_array[$i]['fieldName'] = $row->fieldName;
					$heading_array[$i]['entFieldId'] = $row->entFieldid;
					$i++;
				}
			}
		}else{
			$heading_array[$i]['fieldName'] = 'NoResult';
			$heading_array[$i]['entFieldId'] = 'NoResult';
		}
		return $heading_array;
	}
	
	
	function addTab($courseId,$tabName){
		$this->initiateModel('write');
		$query = "SELECT * from `OF_ENT_ENTERPRISE_TABS` where courseId=? and tabName=? and status='live'";
		$query = $this->dbHandle->query($query,array($courseId,$tabName));
		$numOfRows = $query->num_rows();
		if($numOfRows){
			return 0;
		}
		$query = "INSERT INTO `OF_ENT_ENTERPRISE_TABS` (
					`tabId` ,
					`tabName` ,
					`status` ,
					`courseId` ,
					`sortingData` ,
					`filterData` ,
					`exclusionList` ,
					`analyticsData` ,
					`creationDate`
					)
					VALUES (
					NULL , ? , 'live', ?, '' , '' , '' , '[]' ,
					CURRENT_TIMESTAMP
					)";
		$query = $this->dbHandle->query($query,array($tabName,$courseId));
		return $this->dbHandle->insert_id();
		
	}
	
	function getAllTabData($courseId){
		$this->initiateModel('read');
		$query = "SELECT * from `OF_ENT_ENTERPRISE_TABS` where courseId=? and status='live' order by tabId";
		$query = $this->dbHandle->query($query,array($courseId));
		return $query->result_array();
	}
	
	
	function saveFilterData($filter,$tabId){
		$this->initiateModel('write');
		$query = "update `OF_ENT_ENTERPRISE_TABS` set filterData=? where tabId=? and status='live'";
		$query = $this->dbHandle->query($query,array($filter,$tabId));
	}
	
	
	function saveSorterData($sorter,$tabId){
		$this->initiateModel('write');
		$query = "update `OF_ENT_ENTERPRISE_TABS` set sortingData=? where tabId=? and status='live'";
		$query = $this->dbHandle->query($query,array($sorter,$tabId));
	}
	
	function getFilterData($tabId){
		$this->initiateModel('read');
		$query = "select filterData from `OF_ENT_ENTERPRISE_TABS` where tabId=? and status='live'";
		$query = $this->dbHandle->query($query,array($tabId));
		$result = $query->row();
		return $result->filterData;
	}
	
	function getSorterData($tabId){
		$this->initiateModel('read');
		$query = "select sortingData from `OF_ENT_ENTERPRISE_TABS` where tabId=? and status='live'";
		$query = $this->dbHandle->query($query,array($tabId));
		$result = $query->row();
		return $result->sortingData;
	}
	
	function checkTab($tabId,$courseId){
		$this->initiateModel('read');
		$query = "SELECT * from `OF_ENT_ENTERPRISE_TABS` where courseId=? and tabId=? and status='live'";
		$query = $this->dbHandle->query($query,array($courseId,$tabId));
		return $query->row_array();
	}

	function deleteTab($courseId,$tabId){
		$this->initiateModel('write');
		$query = "update `OF_ENT_ENTERPRISE_TABS` set status='deleted' where courseId=? and tabId=? and status='live'";
		$query = $this->dbHandle->query($query,array($courseId,$tabId));
	}
	
	function deleteAllTabs($courseId){
		$this->initiateModel('write');
		$query = "update `OF_ENT_ENTERPRISE_TABS` set status='deleted' where courseId=? and status='live'";
		$query = $this->dbHandle->query($query,array($courseId));
		$this->addTab($courseId,"Analytics");
	}
	
	function addColumn($courseId,$columnName){
		$this->initiateModel('write');
		$query = "SELECT * FROM `OF_ENT_ENTERPRISE_COLUMN` where courseId=? and columnName = ? and status='live'";
		$query = $this->dbHandle->query($query,array($courseId,$columnName));
		$numOfRows = $query->num_rows();
		if($numOfRows){
			return 0;
		}
		$query = "INSERT INTO `OF_ENT_ENTERPRISE_COLUMN` (
					`columnFieldId` ,
					`columnName` ,
					`courseId` ,
					`userId` ,
					`creationDate` ,
					`status`
					)
					VALUES (
					NULL , ?, ?, '1',
					CURRENT_TIMESTAMP , 'live'
					)";
		$query = $this->dbHandle->query($query,array($columnName,$courseId));
		return $this->dbHandle->insert_id();
		
	}
	
	function saveField($formId,$fieldId,$formType,$data){
		$this->initiateModel('write');
		//if(base64_encode($data) == "AAAA"){
		//	$data = "";
		//}
		$query = "SELECT * FROM `OF_ENT_ENTERPRISE_COLUMN_DATA` where columnFieldId=? and onlineFormId=? and formType = ? and status='live'";
		$query = $this->dbHandle->query($query,array($fieldId,$formId,$formType));
		$numOfRows = $query->num_rows();
		if($numOfRows){
			$query = "UPDATE `OF_ENT_ENTERPRISE_COLUMN_DATA` set value = ? where columnFieldId=? and onlineFormId=? and formType = ? and status='live'";
			$queryArg = array($data,$fieldId,$formId,$formType);
		}else{
			$query = "INSERT INTO `OF_ENT_ENTERPRISE_COLUMN_DATA` (
						`id` ,
						`columnFieldId` ,
						`onlineFormId` ,
						`formType` ,
						`value` ,
						`creationDate` ,
						`status`
						)
						VALUES (
						NULL , ?, ?, ?, ?,
						CURRENT_TIMESTAMP , 'live'
						)";
			$queryArg = array($fieldId,$formId,$formType,$data);
		}
		$query = $this->dbHandle->query($query,$queryArg);
	}
	
	function deleteForms($tabId,$forms){
		$exclusionList = $this->getExclusionList($tabId);
		$this->initiateModel('write');
		$exclusionList = json_decode($exclusionList,true);
		if(!$exclusionList){
			$exclusionList =  array();
		}
		if(!$exclusionList['forms']){
			$exclusionList['forms'] = $forms;
		}else{
			foreach($forms as $form){
				$exclusionList['forms'][] = $form;
			}
		}
		$query = "update `OF_ENT_ENTERPRISE_TABS` set exclusionList=? where tabId=? and status='live'";
		$query = $this->dbHandle->query($query,array(json_encode($exclusionList),$tabId));
	}
	
	
	function getExclusionList($tabId){
		$this->initiateModel('read');
		$query = "select exclusionList from `OF_ENT_ENTERPRISE_TABS` where tabId=? and status='live'";
		$query = $this->dbHandle->query($query,array($tabId));
		$result = $query->row();
		$exclusionList = $result->exclusionList;
		return $exclusionList;
	}
	
	
	function getAnalyticsData($tabId){
		$this->initiateModel('read');
		$query = "select analyticsData from `OF_ENT_ENTERPRISE_TABS` where tabId=? and status='live'";
		$query = $this->dbHandle->query($query,array($tabId));
		$result = $query->row();
		$analyticsData = $result->analyticsData;
		return $analyticsData;
	}
	
	
	function saveGraph($tabId,$graphs){
		$masterGraphs = $this->getAnalyticsData($tabId);
		$this->initiateModel('write');
		$masterGraphs = json_decode($masterGraphs,true);
		if(!$masterGraphs){
			$masterGraphs =  array();
		}
		
		$masterGraphs[$graphs['id']] = array($graphs['field'],$graphs['type']);

		if($graphs['field'] === 0 && $graphs['type'] === 0){
			unset($masterGraphs[$graphs['id']]);
		}
		
		
		
		$i=0;
		$newGraphs = array();
		foreach($masterGraphs as $graph){
			$i++;
			$newGraphs[$i] = $graph;
		}
		
		$query = "update `OF_ENT_ENTERPRISE_TABS` set analyticsData=? where tabId=? and status='live'";
		$query = $this->dbHandle->query($query,array(json_encode($newGraphs),$tabId));
	}
	
	
	function deleteColumn($tabId,$field){
		$exclusionList = $this->getExclusionList($tabId);
		$this->initiateModel('write');
		$exclusionList = json_decode($exclusionList,true);
		if(!$exclusionList){
			$exclusionList =  array();
		}
		if(!$exclusionList['fields']){
			$exclusionList['fields'] = array();
		}
		$exclusionList['fields'][] = $field;
		$query = "update `OF_ENT_ENTERPRISE_TABS` set exclusionList=? where tabId=? and status='live'";
		$query = $this->dbHandle->query($query,array(json_encode($exclusionList),$tabId));
	}
	
	function deleteDuplicateEntries($formIds,$courseId){
		$this->initiateModel('write');
		$formIdString = "('" . implode("','", $formIds) . "')";
		$deleteDuplicateFromsQuery="update OF_ENT_EXTERNAL_FORMS set status='deleted' where entFormId in $formIdString and status!='deleted'";
		$queryRes = $this->dbHandle->query($deleteDuplicateFromsQuery);
		
		$deleteDuplicateQuery = "update OF_ENT_EXTERNAL_FORMS_USER_DATA set status='deleted' where entFormId in $formIdString and status!='deleted'";
		$queryRes = $this->dbHandle->query($deleteDuplicateQuery);
		$this->writeInFile('{"'.$courseId.'":{"externalFormCount":"","externalFormCountlastUpdatedAt":"'.date('Y-m-d H:i:s').'","maximumNumberOfForms":"","ERRORMSG":"DELETE_DUPLICATE_ENTRIES","EMAIL":"","FORMIDS":"","SOURCEID":""}}',$courseId);
		return 'Success';
	}
	
	function getDetailEnterpriseField($courseId){
		$this->initiateModel('read');
		$querySelect = "select fieldName,orderOfEnterpriseField,entFieldid from `OF_ENT_ENTERPRISE_FIELDS` where courseId=? and typeOfField='date'";
		$queryRes = $this->dbHandle->query($querySelect,array($courseId));
		$numOfRows = $queryRes->num_rows();
		$i=0;
		if($numOfRows){
			foreach ($queryRes->result_array() as $row){
				$result_array[$i]['fieldName'] = $row['fieldName'];
				$result_array[$i]['orderOfEnterpriseField'] = $row['orderOfEnterpriseField'];
				$result_array[$i]['entFieldid'] = $row['entFieldid'];
				$i++;
			}
			return $result_array;
		}else{
			return 'None';
		}
	}

    function getFormCountTabWise($courseId,$tab){
        $this->initiateModel('read');
        if($tab!='awaitedForms'){
            $statuses = " 'paid','accepted','rejected','shortlisted','cancelled','Payment Confirmed','Under Process','GD/PI Update','Payment Awaited','Payment Under Process' ";
        }
        else{
            $statuses = " 'draft' ";
        }
        $querySelect = "select count(*) as formCount from OF_UserForms where status IN ($statuses) and courseId = ? and formStatus='live'";
        $queryRes = $this->dbHandle->query($querySelect,array($courseId));
        $rows = $queryRes->row();
        return $rows->formCount;
    }
	
	function getInstituteBasicInfo($instituteId){
		$this->initiateModel('read');
		$querySelect = "select * from OF_InstituteDetails where status='live' and instituteId=?";
		$queryRes = $this->dbHandle->query($querySelect,array($instituteId));
		return $queryRes->result_array();
	}
	
	function setInstituteBasicInfo($fieldArray){
		$this->initiateModel('write');
		$this->dbHandle->where('instituteId', $fieldArray['instituteId']);
		if($this->dbHandle->update('OF_InstituteDetails', $fieldArray)) {
		    return 1;
		}
		else{
		    return 0;
		}
	}

    function getOnlineFormInstitute($userId){

            $this->initiateModel('read');
            $queryCmd = "select lm.listing_title,listing_type_id from listings_main lm,`OF_InstituteDetails` ofid where lm.username =?  and lm.listing_type in ('institute','university_national') and lm.status='live' and ofid.`instituteId`=lm.listing_type_id and ofid.status = 'live' AND (ofid.externalURL IS NULL OR ofid.externalURL='')";
            
            $queryRes = $this->dbHandle->query($queryCmd,array($userId))->result_array();

            return $queryRes;
        }
}

<?php
class CareerEnterpriseModel extends MY_Model
{ /*

   Copyright 2013 Info Edge India Ltd

   $Author: Pranjul

   $Id: CareerEnterpriseModel.php

 */


	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	private $dbHandle = '';
	function __construct(){
		parent::__construct('CareerProduct');
	}
	/**
	 * returns a data base handler object
	 *
	 * @param none
	 * @return object
	 */

    	private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
        	$this->dbHandle = $this->getWriteHandle();
		}		
	}

	function escapeMyString($variable){
        if(mysql_real_escape_string($variable))
            return mysql_real_escape_string($variable);
        else
            return mysql_escape_string($variable);
        }

	/*
	 @name: getCareerList
	 @description: this is for get list of careers
	 @param string $userInput: No input parameters
	*/
	
	public function getCareerList(){
		$this->initiateModel('read');
	       	$queryCmd =  "select careerId,name from CP_CareerTable where status!='deleted' order by name";
	        $query = $this->dbHandle->query($queryCmd);
		$result = $query->result_array();
	 	return $result;
       }
	/*
	 @name: getCareerName
	 @description: this is for get career name
	 @param string $userInput: careerId
	*/

       public function getCareerName($careerId){
		$this->initiateModel('read');
	       	$queryCmd =  "select name from CP_CareerTable where careerId = ?";
	        $query = $this->dbHandle->query($queryCmd,array($careerId));
		$result = $query->result_array();
	 	return $result;
       }
	/*
	 @name: getCareerData
	 @description: this is for get full career information
	 @param string $userInput: careerId
	*/
       public function getCareerData($careerId){
		$this->initiateModel('read');
	       	$queryCmd =  "select ct.* from  CP_CareerTable ct where ct.careerId = ? and ct.status = 'live'";
	        $query = $this->dbHandle->query($queryCmd,array($careerId));
		$res[0] = $query->result_array();
		$result = $res[0][0];
		$queryCmd =  "select cpvt.* from CP_CareerPageValueTable cpvt where cpvt.careerId = ? and cpvt.status = 'live'";
	        $query = $this->dbHandle->query($queryCmd,array($careerId));
		$results = $query->result();
		$count = $query->num_rows();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$result[$row->keyname] = $row->value;
			}
		}
		$result['totalRows'] = $count;
	 	return $result;
       }
	/*
	 @name: insertCareerToLDBMapping
	 @description: this is for create Career To LDB Course Mapping
	 @param string $userInput: careerId,ldbCourseId
	*/
       public function insertCareerToLDBMapping($mappingInformation){
		$this->initiateModel('write');
		for($i=1;$i<=5;$i++){
			if($mappingInformation['courseMap_'.$i]!='0'){
				$queryCmd =  "insert into CP_CareerToLDBMapping (`careerId`,`ldbCourseId`)values (?,?)";
				$query = $this->dbHandle->query($queryCmd,array($mappingInformation['careerId'] ,$mappingInformation['courseMap_'.$i]));
			}
		}
       }
	/*
	 @name: insertUpdateCareerToLDBMapping
	 @description: this is for insert update Career To LDB Course Mapping
	 @param string $userInput: careerId,ldbCourseId
	*/
      public function insertUpdateCareerToLDBMapping($mappingInformation,$careerId){
		$this->initiateModel('write');
		$queryCmd =  "update CP_CareerToLDBMapping set `status` = 'deleted' where `careerId` = ?";
		$query = $this->dbHandle->query($queryCmd,array($careerId));
		$queryPart='';
		$j=0;
		for($i=1;$i<=count($mappingInformation);$i++){
			if($mappingInformation['courseMap_'.$i]!='0'){
				if($j>0){$comma = ' , ';}else{$comma = '';}
				$queryPart .= $comma."('".$this->escapeMyString($careerId)."','".$this->escapeMyString($mappingInformation['courseMap_'.$i])."','live')";
				$j++;
			}
		}
		if($j>0){
			$queryCmd =  "insert into CP_CareerToLDBMapping (`careerId`,`ldbCourseId`,`status`) values $queryPart";
			$query = $this->dbHandle->query($queryCmd);
		}
	}
	/*
	 @name: checkCareerToLDBMapping
	 @description: this is for check Career To LDB Course Mapping
	 @param string $userInput: careerId
	*/
      public function checkCareerToLDBMapping($careerId){
		$this->initiateModel('read');
		$queryCmd =  "SELECT CP_CareerToLDBMapping.* from  CP_CareerToLDBMapping WHERE CP_CareerToLDBMapping.careerId  = ? and status = 'live'";
		$query = $this->db->query($queryCmd,array($careerId));
		$result = $query->result_array();
		return $result;
      }
    
      /*
	 @name: updateCareerShortInformation/
	 @description: this is for update Career Information,display on Career Recommendation Page
	 @param string $userInput: minimumSalaryInLacs,maximumSalaryInLacs,minimumSalaryInThousand,maximumSalaryInThousand,shortDescription,image and careerId
	*/
      public function updateCareerShortInformation($careerInformationArray){
		$this->initiateModel('write');
		if(trim($careerInformationArray['minimumSalaryInLacs'])=='Lakh'){
			$careerInformationArray['minimumSalaryInLacs'] = NULL;
		}
		if(trim($careerInformationArray['maximumSalaryInLacs'])=='Lakh'){
			$careerInformationArray['maximumSalaryInLacs'] = NULL;
		}
		if(trim($careerInformationArray['minimumSalaryInThousand'])=='Thousand'){
			$careerInformationArray['minimumSalaryInThousand'] = NULL;
		}
		if(trim($careerInformationArray['maximumSalaryInThousand'])=='Thousand'){
			$careerInformationArray['maximumSalaryInThousand'] = NULL;
		}
		
		$query = "update CP_CareerTable set `shortDescription` =  ? ,`minimumSalaryInLacs` = ? ,`maximumSalaryInLacs` = ? ,`minimumSalaryInThousand` = ?,`maximumSalaryInThousand` = ? ,`status` = 'live' where `careerId`= ?";
                $queryRes = $this->dbHandle->query($query,array($careerInformationArray['shortIntro'],$careerInformationArray['minimumSalaryInLacs'],$careerInformationArray['maximumSalaryInLacs'],$careerInformationArray['minimumSalaryInThousand'],$careerInformationArray['maximumSalaryInThousand'],$careerInformationArray['careerId']));
                if(array_key_exists('smallImageIntro',$careerInformationArray)){
                         $query = "update CP_CareerTable set `image` =  ? ,`status` = 'live' where `careerId`= ?";
                         $queryRes = $this->dbHandle->query($query,array($careerInformationArray['smallImageIntro'],$careerInformationArray['careerId']));
                }
                $query = "update CP_CareerTable set `madeLiveDate` = now() where `careerId`= ? and madeLiveDate is NULL and status='live'";
                $queryRes = $this->dbHandle->query($query,array($careerInformationArray['careerId']));

		$seoUrl = $this->createSeoUrl($careerInformationArray['careerId']);
		$this->initiateModel('write');
		if($seoUrl){
			$query = "update CP_CareerTable set `careerUrl` =  ?  where `careerId`= ?";
			$queryRes = $this->dbHandle->query($query,array($seoUrl,$careerInformationArray['careerId']));
		}
      }
      
      function createSeoUrl($careerId){
		$this->initiateModel('read');
		$query = 'select name,careerUrl from CP_CareerTable where careerId=?';
		$queryRes = $this->dbHandle->query($query,array($careerId));
		$rowD = $queryRes->row();
                $careerName = $rowD->name;
		$careerUrl = $rowD->careerUrl;
		if(!empty($careerUrl)){
			return false;
		}else{
			$url = getSeoUrl($careerId,'newcareerposted',$careerName);
			return $url;	
		}
		
      }
	/*
	 @name: insertUpdateCareerOtherInformation/
	 @description: this is for insert and update Career Information DB as name-value pairs.
	 @param string $userInput: all form post data in of array and careerId
	*/
     public function insertUpdateCareerOtherInformation($nameValuePairArray,$careerId){
		$this->initiateModel('write');
		foreach($nameValuePairArray as $name=>$value){
			if(in_array($value,array('Enter Title','Enter Company Name','Enter YouTube URL','Enter Institute Name','Enter Course Id','Enter Text'))){
				$value = '';
			}
			//if(!in_array($value,array('Enter Title','Enter Company Name','Enter YouTube URL','Enter Institute Name','Enter Course Id','Enter Text'))){
				$queryCmd = "select * from CP_CareerPageValueTable where keyname=? and careerId=? and status!='deleted'";
				$queryP = $this->dbHandle->query($queryCmd,array($name,$careerId));
				$count = $queryP->num_rows();
				if($count >0){
					$queryCmd = "update CP_CareerPageValueTable set value = ? ,`modificationDate` = NOW() where keyname= ? and careerId= ?";
					$queryP = $this->dbHandle->query($queryCmd,array($value,$name,$careerId));
					//$afffected = $this->dbHandle->affected_rows();
				}else{
					$query = "insert into CP_CareerPageValueTable (`careerId`,`keyname`,`value`,`modificationDate`) values (?,?,?,NOW())";
					$queryRes = $this->dbHandle->query($query,array($careerId,$name,$value));
				}
			//}
		}
				$queryCmd = "update CP_CareerMappingTable set status = 'live' where careerId= ? and status!='deleted'";
				$queryP = $this->dbHandle->query($queryCmd,array($careerId));
     }
     	/*
	 @name: removePath
	 @description: this is for removePath and its details from Career Detail.
	 @param string $userInput: pathId and careerId
	*/
     public function removePath($pathId,$careerId){
		$this->initiateModel('write');
		$query = "update CP_CareerPathTable set `status` = 'deleted' where `pathId` = ? and `careerId` = ?";
		$queryRes = $this->dbHandle->query($query,array($pathId,$careerId));

		$query = "update CP_CareerPathStepsTable set `status` = 'deleted' where `pathId` = ?";
		$queryRes = $this->dbHandle->query($query,array($pathId));

		return 'done';
     }
         /*
	 @name: savePathPreviewInformation
	 @description: this is for save preview of Path in How do I get There Mapping Tab.
	 @param string $userInput: pathId and careerId
	*/
     public function savePathPreviewInformation($pathId,$careerId){
		$this->initiateModel('write');
		$query = "update CP_CareerPathTable set `status` = 'live' where `pathId` = ? and `careerId` = ?";
		$queryRes = $this->dbHandle->query($query,array($pathId,$careerId));
		$query = "update CP_CareerPathStepsTable set `status` = 'live' where `pathId` = ? and `status`!= 'deleted'";
		$queryRes = $this->dbHandle->query($query,array($pathId));
		return 'done';
     }



     public function setDefaultCareerPath($careerId){
		$this->initiateModel('write');
		$queryCmd = "select * from CP_CareerPathTable where careerId=?";
		$querySelect = $this->dbHandle->query($queryCmd,array($careerId));
		$count = $querySelect->num_rows();
		if($count==0){
			$query = "insert into CP_CareerPathTable (`pathName`,`careerId`,`creationDate`) values (?,?,NOW())";
			$queryRes = $this->dbHandle->query($query,array('PathName',$careerId));
		}
     }

     public function createPath($careerId){
		$this->initiateModel('write');
		$query = "insert into CP_CareerPathTable (`pathName`,`careerId`,`creationDate`) values (?,?,NOW())";
		$queryRes = $this->dbHandle->query($query,array('PathName',$careerId));
		$pathId = $this->dbHandle->insert_id();
		return $pathId;		
     }

         /*
	 @name: saveCareerPathInformation
	 @description: this is for save information of a Path in How do I get There Mapping Tab.
	 @param string $userInput: pathId,stepTitle,stepDescription,stepOrder,careerId
	*/
     public function saveCareerPathInformation($pathId,$stepTitle,$stepDescription,$stepOrder,$careerId){
		$this->initiateModel('write');
		$queryCmd = "select * from CP_CareerPathTable";
		$querySelect = $this->dbHandle->query($queryCmd);
		$count = $querySelect->num_rows();
		if($count==0){
			$query = "insert into CP_CareerPathTable (`pathName`,`careerId`,`creationDate`) values (?,?,NOW())";
			$queryRes = $this->dbHandle->query($query,array('PathName',$careerId));
			$pathId = $this->dbHandle->insert_id();
		}
		
		$queryCmd = "select * from CP_CareerPathStepsTable where `pathId` = ? and `stepOrder` = ? and status!='deleted'";
	        $query = $this->dbHandle->query($queryCmd,array($pathId,$stepOrder));
		$count = $query->num_rows();
		
		if($count>0){
			$query = "update CP_CareerPathStepsTable set `stepTitle` = ? ,`stepDescription` =? , `status` = 'draft' where `pathId` = ? and `stepOrder` = ? ";
			$queryRes = $this->dbHandle->query($query,array($stepTitle,$stepDescription,$pathId,$stepOrder));
		}else{
			$query = "insert into CP_CareerPathStepsTable (`pathId`,`stepTitle`,`stepDescription`,`stepOrder`) values (?,?,?,?)";
			$queryRes = $this->dbHandle->query($query,array($pathId,$stepTitle,$stepDescription,$stepOrder));
		}

		
    }
	 /*
	 @name: getCareerPathInformation
	 @description: this is for fetching Path information in How do I get There Mapping Tab.
	 @param string $userInput: careerId
	*/
   public function getCareerPathInformation($careerId){
	  $this->initiateModel('read');
	  $queryCmd = "select cpt.* from CP_CareerPathTable cpt where `careerid` = ? and status in ('draft','live') order by pathId";
	  $query = $this->db->query($queryCmd,array($careerId));
	  $pathCount = $query->num_rows();
	  foreach ($query->result_array() as $row)
          {
                        $tmpResult = array();
                        $tmpResult['pathId'] = $row['pathId'];
                        $tmpResult['pathName'] = $row['pathName'];
                        $tmpResult['careerid'] = $row['careerid'];
                        $tmpResult['status'] = $row['status'];
						$tmpResult['pathCount'] = $pathCount;
                        $results[$row['pathId']] = $tmpResult;
			$queryCmd = "select cpst.* from CP_CareerPathStepsTable cpst where `pathId` = ? and status in ('draft','live') order by stepOrder";
			$query = $this->db->query($queryCmd, array($row['pathId']));
			$i=1;$tmpResults = array();
			$count = $query->num_rows();
			if($count>0){
				foreach ($query->result_array() as $rows)
				{
					$tmpResults['stepTitle'] = $rows['stepTitle'];
					$tmpResults['stepDescription'] = $rows['stepDescription'];
					$tmpResults['stepOrder'] = $rows['stepOrder'];
					$results[$row['pathId']]['stepDetails'][$i] = $tmpResults;
					$i++;
				}
			}else{
				$results[$row['pathId']]['stepDetails'] = array();
			}

			
          }
	  return $results;
   }
	
   public function getMaxPath($careerId){
	  $queryCmd = "select max(`pathId`) as pathId from CP_CareerPathTable";
	  $query = $this->db->query($queryCmd,array($careerId));
	  $result = $query->result_array();
	  return $result[0]['pathId'];
   }
 /*
 @name: removeCustomFieldInPathTab
 @description: this is for removing Custom Fields in How do I get There Mapping Tab.
 @param string $userInput: pathId,stepOrder,careerId
*/
   public function removeCustomFieldInPathTab($pathId,$stepOrder,$careerId){
		$this->initiateModel('write');
		$query = "update CP_CareerPathStepsTable set status ='deleted' where `pathId` = ?  and `stepOrder` = ?";
		$queryRes = $this->dbHandle->query($query,array($pathId,$stepOrder));
   }

   public function removeCourseIdFromEarning($careerId,$courseName){
	$this->initiateModel('write');
	$query = "delete from CP_CareerPageValueTable where `careerId` = ?  and `keyname` = ?";
	$queryRes = $this->dbHandle->query($query,array($careerId,$courseName));
   }
 /*
 @name: removeSectionForPrestigiousInstitute
 @description: this is for removing Section in WhereToStudy Section.
 @param string $userInput: careerId,location,sectionNumber
*/
   public function removeSectionForPrestigiousInstitute($careerId,$location,$sectionNumber){
	$this->initiateModel('write');
	if($location=='abroad'){$newLocation = 'Abroad';}else{$newLocation = 'India';}
	$query = "delete from CP_CareerPageValueTable where `careerId` = ?  and `keyname` like 'courseId".$newLocation."_".$sectionNumber."_%'";
	$queryRes = $this->dbHandle->query($query,array($careerId));
	$query = "delete from CP_CareerPageValueTable where `careerId` = ?  and `keyname` = ?";
	$queryRes = $this->dbHandle->query($query,array($careerId, 'prestigiousInstitueTitle'.$location.$sectionNumber));
   }
 /*
 @name: removeSkill
 @description: this is for removing Required Skills in Job Profile Section.
 @param string $userInput: careerId,position,newSkillRequiredString
*/
   public function removeSkill($careerId,$position,$newSkillRequiredString){
	$this->initiateModel('write');
	
	$query = "update CP_CareerPageValueTable set `value` = ? where `keyname` = ? and `careerId` = ?";
	$queryRes = $this->dbHandle->query($query,array($newSkillRequiredString,'skillRequiredCount',$careerId));	
		
	$query = "update CP_CareerPageValueTable set `status` = 'deleted' where `careerId` = ?  and `keyname` = ?";
	$queryRes = $this->dbHandle->query($query,array($careerId,'skillRequired_'.$position));
   }
 /*
 @name: removeCustomFields
 @description: this is for removing Custom Tab Information in Career Detail Tab.
 @param string $userInput: careerId,counter,updatedString,sectionName
*/
   public function removeCustomFields($careerId,$counter,$updatedString,$sectionName){
	$this->initiateModel('write');
	$query = "update CP_CareerPageValueTable set `value` = ? where `keyname` = ? and `careerId` = ?";
	$queryRes = $this->dbHandle->query($query,array($updatedString,$sectionName."Count",$careerId));

	$query = "update CP_CareerPageValueTable set `status` = 'deleted' where `careerId` = ?  and `keyname` = ?";
	$queryRes = $this->dbHandle->query($query,array($careerId,'wikkicontent_title_'.$sectionName.'_'.$counter));
	
	$query = "update CP_CareerPageValueTable set `status` = 'deleted' where `careerId` = ?  and `keyname` = ?";
	$queryRes = $this->dbHandle->query($query,array($careerId,'wikkicontent_detail_'.$sectionName.'_'.$counter));
   }
 /*
 @name: removeSection
 @description: this is for removing Section Information in Career Detail Tab.
 @param string $userInput: careerId,counter,updatedString,region,sectionName
*/
   public function removeSection($careerId,$counter,$updatedString,$region,$sectionName){
	$this->initiateModel('write');
	$query = "update CP_CareerPageValueTable set `value` = ? where `keyname` = ? and `careerId` = ?";
	$queryRes = $this->dbHandle->query($query,array($updatedString,$region."whereToStudyCount",$careerId));

	$query = "update CP_CareerPageValueTable set `value` = ? where `keyname` = ? and `careerId` = ?";
	$queryRes = $this->dbHandle->query($query,array('2',"total".$region."whereToStudyCourseIdCountFor".$sectionName."Section",$careerId));

	$query = "update CP_CareerPageValueTable set `value` = ? where `keyname` = ? and `careerId` = ?";
	$queryRes = $this->dbHandle->query($query,array('1,2',$region."whereToStudyCourseIdCountFor".$sectionName."Section",$careerId));

	$query = "update CP_CareerPageValueTable set status= 'deleted' where `careerId` = ?  and `keyname` like ?";
	$queryRes = $this->dbHandle->query($query,array($careerId, $region.'CourseId_'.$sectionName.'_%'));

	$query = "update CP_CareerPageValueTable set status= 'deleted' where `careerId` = ?  and `keyname` = ?";
	$queryRes = $this->dbHandle->query($query,array($careerId,$region.'Heading_'.$counter));
   }
 /*
 @name: removeCourseIdForSection
 @description: this is for removing Course Id in Where To Study Section
 @param string $userInput: careerId,counter,updatedString,region,sectionName
*/
   public function removeCourseIdForSection($careerId,$counter,$updatedString,$region,$sectionName){
	$this->initiateModel('write');
	$query = "update CP_CareerPageValueTable set `value` = ? where `keyname` = ? and `careerId` = ?";
	$queryRes = $this->dbHandle->query($query,array($updatedString,$region.'whereToStudyCourseIdCountFor'.$sectionName.'Section',$careerId));
	$query = "update CP_CareerPageValueTable set status= 'deleted' where `careerId` = ?  and `keyname` = ?";
	$queryRes = $this->dbHandle->query($query,array($careerId,$region.'CourseId_'.$sectionName.'_'.$counter));
   }

   /*****************************************************************************
	This function is used to get express interest list from db whose status are live.
        *******************************************************************************/
       
       public function getExpressInterestList(){
		$this->initiateModel('read');
	       	$queryCmd =  "SELECT eiName,eiId FROM `CP_ExpressInterestTable` where status='live'";
	        $query = $this->dbHandle->query($queryCmd);
		$result = $query->result_array();
	 	return $result;
       }

	 /**************************************************************************************
       This is used to get the career stream mapping of the selected career  editing a career.
       Parameters:careerId
       **************************************************************************************/
      
       public function getStreamExpInterestMappingForSelectedCareer($careerId){
		$this->initiateModel('read');
		$queryCmd =  "SELECT c1.stream,c2.name,c1.ei1,c1.ei2,c2.mandatorySubject,c2.difficultyLevel  FROM `CP_CareerMappingTable` c1,CP_CareerTable c2 WHERE c1.careerId=? AND c2.careerId=c1.careerId and c1.status!='deleted'";
		$Result = $this->db->query($queryCmd,array($careerId));
                $results = array();
                foreach ($Result->result_array() as $row){
                        $results[] = $row;
                }

		return $results;
        }
	
	 /***********************************************************************************************************
	//This is used to check the action to be performed on the career(add/edit/delete) and call the desired function.
	***************************************************************************************************/

	public function addOrEditCareerToStreamInterestMapping($data){
            $careerId = 0; 
 	    if($data['action']=='edit'){ 
 	            $matchingCareerExistsFlag='false'; 
 	            if(strcmp($data['oldcareerName'],$data['careerName'])!='0'){ 
 	                    $matchingCareerExistsFlag=$this->doesMatchingCareerExists($data['careerName']); 
 	            } 
 	            if($matchingCareerExistsFlag=='false'){ 
 	                    $data['careerId'] = $this->input->post('careerid'); 
 	                    $careerId = $this->updateCareerToStreamExpInterestMapping($data); 
 	            } 
 	    } 
 	    else if($data['action']=='delete'){ 
 	            $data['careerId'] = $this->input->post('careerid'); 
 	            $careerId = $this->deleteCareerToStreamExpInterestMapping($data['careerId']); 
 	    } 
 	    else{ 
 	            $matchingCareerExistsFlag=$this->doesMatchingCareerExists($data['careerName']); 
 	            if($matchingCareerExistsFlag=='false'){ 
 	                  $careerId = $this->createCareerToStreamExpInterestMapping($data); 
 	            } 
 	    } 
 	    return $careerId; 
	}
    
	 /***************************************************************
	//This is used to check if a career with same name exists already.
	parameter:careername
	*****************************************************************/
	 
	private function doesMatchingCareerExists($careerName){
		$results=$this->getCareerList();
		$matchingCareerExistsFlag='false';
		foreach($results as $career){
			if($career['name']==$careerName){
				$matchingCareerExistsFlag='true';
				return $matchingCareerExistsFlag; 
			}
		}
		return $matchingCareerExistsFlag;
	}
    
         /*
	 @name: createCareerToStreamExpInterestMapping/
	 @description: this is for create mapping among Career,Express Interest1,Express Interest2
	 @param array $userInput: careername,stream,expressInterest1,expressInterest2,difficultyLevel,mandatorySubject
	*/
        private function createCareerToStreamExpInterestMapping($data){
		$stream=$data['stream'];
		$expressInterest1=$data['expressInterest1'];
		$expressInterest2=$data['expressInterest2'];
		$difficultyLevel=$data['difficultyLevel'];
		$mandatorySubject=$data['mandatorySubject'];
		$action=$data['action'];
		$careername=$data['careerName'];
		
		$this->initiateModel('write');
		$streamExplodeArr = explode(',',$stream);
		$expressInterest1Arr = explode(',',$expressInterest1);
		$expressInterest2Arr = explode(',',$expressInterest2);
		
		$query = "insert into CP_CareerTable (`name`,`difficultyLevel`,`mandatorySubject`) values (?,?,?)";
		$queryRes = $this->dbHandle->query($query,array($careername ,$difficultyLevel,$mandatorySubject));	
		$last_insert_id = $this->dbHandle->insert_id();
		$i=0;
		foreach($streamExplodeArr as $key=>$value){
			if($value=='All'){continue;}
			foreach($expressInterest1Arr as $key1=>$value1){
				foreach($expressInterest2Arr as $key2=>$value2){ 
					if($i>0){$comma = ' , ';}
					if($value2 ==''){
						$queryPart .= $comma."('".$last_insert_id."','".$this->escapeMyString($value)."','".$this->escapeMyString($value1)."',NULL)";
					}else{
						$queryPart .= $comma."('".$last_insert_id."','".$this->escapeMyString($value)."','".$this->escapeMyString($value1)."','".$this->escapeMyString($value2)."')";	
					}
					$i++;
				}
			}
		}
		$query = "insert into CP_CareerMappingTable (`careerId`,`stream`,`ei1`,`ei2`) values $queryPart";
		$queryRes = $this->dbHandle->query($query);
		return $last_insert_id; 
	}
      
       /**********************************************************************************************************
       This is used to update the career stream mapping for editing a career.
       Parameters array containing:careerId,newCareerName,stream,expressInterest1,expressInterest2,difficultyLevel,mandatorySubject
       ***********************************************************************************************************/
       
        private function updateCareerToStreamExpInterestMapping($data){
		$stream=$data['stream'];
		$expressInterest1=$data['expressInterest1'];
		$expressInterest2=$data['expressInterest2'];
		$difficultyLevel=$data['difficultyLevel'];
		$mandatorySubject=$data['mandatorySubject'];
		$action=$data['action'];
		$newCareerName=$data['careerName'];
		$careerId=$data['careerId'];
		
		$this->initiateModel('write');
		$streamExplodeArr = explode(',',$stream);
		$expressInterest1Arr = explode(',',$expressInterest1);
		$expressInterest2Arr = explode(',',$expressInterest2);
		
		$query = "SELECT  status  FROM `CP_CareerTable` WHERE `careerId`=?";
		$queryRes = $this->dbHandle->query($query,array($careerId));
		$result = $queryRes->result_array();
		
		
		$query = "update CP_CareerTable set name=?,difficultyLevel= ?,mandatorySubject=? where careerId=?";
		$queryRes = $this->dbHandle->query($query,array($newCareerName,$difficultyLevel,$mandatorySubject,$careerId));
		$query = "update CP_CareerMappingTable set status=? where careerId=?";
		$queryRes = $this->dbHandle->query($query,array('deleted',$careerId));
		
		
		$last_insert_id = $this->dbHandle->insert_id();
		$i=0;
		
		if($result[0]['status']=='live')
			$status='live';
		else
			$status='draft';
			
		foreach($streamExplodeArr as $key=>$value){
				if($value=='All'){continue;}
			foreach($expressInterest1Arr as $key1=>$value1){
				foreach($expressInterest2Arr as $key2=>$value2){ 
					if($i>0){$comma = ' , ';}
					if($value2 ==''){
						$queryPart .= $comma."('".$last_insert_id."','".$this->escapeMyString($careerId)."','".$this->escapeMyString($value)."','".$this->escapeMyString($value1)."',NULL,'".$this->escapeMyString($status)."')";
					}else{
						$queryPart .= $comma."('".$last_insert_id."','".$this->escapeMyString($careerId)."','".$this->escapeMyString($value)."','".$this->escapeMyString($value1)."','".$this->escapeMyString($value2)."','".$this->escapeMyString($status)."')";	
					}
					$i++;
				}
			}
		}
		
		$query = "insert into CP_CareerMappingTable (id,`careerId`,`stream`,`ei1`,`ei2`,`status`) values $queryPart";
		$queryRes = $this->dbHandle->query($query);
	        return $careerId;	
        }
      
	/******************************
	This is used to delete career
	Parameters :careerId
	***********************************************************************************************************/
        private function deleteCareerToStreamExpInterestMapping($careerId){
		$this->initiateModel('write');
		$query = "update CP_CareerTable set status=? where careerId=?";
		$queryRes = $this->dbHandle->query($query,array('deleted',$careerId));
		
		$query = "update CP_CareerMappingTable set status=? where careerId=?";
		$queryRes = $this->dbHandle->query($query,array('deleted',$careerId));

		$query = "update CP_CareerPageValueTable set status=? where careerId=?";
		$queryRes = $this->dbHandle->query($query,array('deleted',$careerId));

		return $careerId; 
	}

	public function checkForMandatoryImagesInDatabase($careerId,$arr1,$arr2){
		$this->initiateModel('read');
		if(!empty($arr1)){
			$query = "SELECT `image` FROM `CP_CareerTable` WHERE `careerId`=?";
			$queryRes = $this->dbHandle->query($query,array($careerId));
			foreach ($queryRes->result_array() as $row){
				$result['smallImageIntro'] = $row['image'];
			}
		}

		for($i=0;$i<count($arr2);$i++){
			$query = "SELECT `value` FROM `CP_CareerPageValueTable` WHERE `careerId`=? and `keyname`=? and status!='deleted'";
			$queryRes = $this->dbHandle->query($query,array($careerId,$arr2[$i]));
			$count = $queryRes->num_rows();
			if($count >0){
				foreach ($queryRes->result_array() as $row){
					$result[$arr2[$i]] = $row['value'];
				}
			}else{
				$result[$arr2[$i]] = '';
			}
		}
		return $result;
		
	}

	public function clearText($careerId,$keyname){
		$this->initiateModel('write');
		$query = "update CP_CareerPageValueTable set value='' where careerId=? and keyname=? and status!='deleted'";
		$queryRes = $this->dbHandle->query($query,array($careerId,$keyname));
		return '1';
	}
	
	public function clearTextForPrestigiousInstitute($careerId,$keyname1,$keyname2){
		$this->initiateModel('write');
		$query = "update CP_CareerPageValueTable set value='' where careerId=? and keyname=? and status!='deleted'";
		$queryRes = $this->dbHandle->query($query,array($careerId,$keyname1));
		$query = "update CP_CareerPageValueTable set value='' where careerId=? and keyname=? and status!='deleted'";
		$queryRes = $this->dbHandle->query($query,array($careerId,$keyname2));
		return '1';
	}

	public function getPathImage($careerId){
		$this->initiateModel('read');
		$query = "SELECT `value` FROM `CP_CareerPageValueTable` WHERE `careerId`=? and `keyname`=? and status!='deleted'";
		$queryRes = $this->dbHandle->query($query,array($careerId,'pathImage'));
		$result = $queryRes->result_array();
		return $result[0]['value'];
	}
	
	public function removeLDBCourseFromMapping($careerId,$ldbCourseId){
		$this->initiateModel('write');
		$queryCmd =  "update CP_CareerToLDBMapping set `status` = 'deleted' where `careerId` = ? and `ldbCourseId`=?";
		//error_log("update CP_CareerToLDBMapping set `status` = 'deleted' where `careerId` = '".$careerId."' and ldbCourseId='".$ldbCourseId."'");
		$queryRes = $this->dbHandle->query($queryCmd,array($careerId,$ldbCourseId));
	}

	public function addFeaturedColleges($careerId,$clientId,$title,$url,$status, $rowOrder){
		$this->initiateModel('write');

        // insert new entry
		$sql="insert into CP_FeaturedColleges (careerId,clientId,rowOrder,title,URL,status) values (?,?,?,?,?,?)";
		$result = $this->dbHandle->query($sql,array($careerId,$clientId,$rowOrder,$title,$url,$status));
		return $this->dbHandle->insert_id();
	}

	function getFeaturedCollegesData($career){
		$this->initiateModel('read');

		$sql = "select id,clientId, title, url from CP_FeaturedColleges where careerId = ? and status =?";
		$result = $this->dbHandle->query($sql,array($career,'live'))->result_array();

		return $result;
	}


	function updateFeaturedColleges($rowId){
		$this->initiateModel('write');

		$sql="update CP_FeaturedColleges set status = 'history' where id = ?";
		$result = $this->dbHandle->query($sql,array($rowId));
	}

	function getCareerHierarchyData($careerId){
		if(empty($careerId)){
			return;
		}
		$this->initiateModel('read');
		$sql = "select id, hierarchyId,courseId from careerAttributeMapping where status='live' and careerId = ?";
		return $this->dbHandle->query($sql, array($careerId))->result_array();
	}
	
	function updateCareerAttrMapping($careerId){
		$this->initiateModel('write');
	 	$dbHandleLocal = $this->dbHandle;
		$dbHandleLocal->trans_start();
		$sql="update careerAttributeMapping set status = 'deleted' where careerId = ?";
		$result = $dbHandleLocal->query($sql,$careerId);
		$dbHandleLocal->trans_complete();
		return $dbHandleLocal->trans_status();
	}
	
	function insertCareerAttrMapping($data){
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		$this->db->insert_batch('careerAttributeMapping', $data); 
		$this->dbHandle->trans_complete();
		return $this->dbHandle->trans_status();
	}

	function checkIfCareerDetailPageExists($careerId){
                $this->initiateModel('read');
                $sql = "select careerId from CP_CareerTable where status='live' and shortDescription!='' and careerId=?";
                $result = $this->dbHandle->query($sql,array($careerId));
                $count = $result->num_rows();
                return $count;
        }
}
?>

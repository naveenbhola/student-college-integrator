<?php
class Careermodel extends MY_Model
{ 	/*

   Copyright 2013 Info Edge India Ltd

   $Author: Pranjul

   $Id: Careermodel.php

 */
   private $_streamAny = 'Science, Commerce, Humanities';

	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct()
	{ 
		parent::__construct('CareerProduct');
	}
	
	private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
        	$this->dbHandle = $this->getWriteHandle();
		}		
	}
	
	function getRecommendedCareerId($firstChoice,$secondChoice,$stream,$careerId){
		$this->initiateModel('read');
		$res = array();
		$numOfRows =''; 
		$firstChoiceArr = explode(',', $firstChoice);
		$secondChoiceArr = explode(',', $secondChoice);
		$streamArr = explode(',', $stream);

		if($firstChoice!='' && $secondChoice!=''){
			$queryCmd = "select distinct careerId from  CP_CareerMappingTable where ei1 in (?) and ei2 in (?) and `stream` in (?) and careerId!=? and status = 'live' ";

			$query = $this->dbHandle->query($queryCmd, array($firstChoiceArr, $secondChoiceArr, $streamArr, $careerId));
			$numOfRows = $query->num_rows();
			$result = $query->result_array();
			$i=0;
			if($numOfRows!=0){
				foreach($result as $key=>$value){
					$res[$i] = $value['careerId'];
					$i++;
				}
			}
		}
		
		if(($firstChoice!='' && $secondChoice=='') || ($numOfRows<4)){
			$queryCmd = "select distinct careerId from  CP_CareerMappingTable where ei1 in (?) and `stream` in (?) and careerId!=? and status = 'live'";	
			$query = $this->dbHandle->query($queryCmd, array($firstChoiceArr, $streamArr, $careerId));
			$numOfRows1 = $query->num_rows();
			$result1 = $query->result_array();
			$res1 = array();
			$j=0;
			if($numOfRows1!=0){
				foreach($result1 as $key1=>$value1){
					$res1[$j] = $value1['careerId'];
					$j++;
				}
			}
			if($numOfRows>0){
				$array = array_merge($res,$res1);
				
				//$array = $this->removeDupicateElementsFromArray($array);
				$array = array_unique($array);

				$res= $array;
			}else{
				$res= $res1;
			}
		}
 		return $res;
	}
	function getSuggestedCareers($careerIdsData){
		$careerIds = array_keys($careerIdsData);
		$careerDataResults = $this->getMultipleCareerData($careerIds);
		foreach($careerIdsData as $key=>$value){
			if(!empty($careerDataResults[$key])){
				$careerDataResults[$key]['otherCareerInformation']['streamAndExpressInterest'] = $careerIdsData[$key];				
			}
		}
		return $careerDataResults;
	}
	
	function getSuggestedCareerDetails(){
		$this->initiateModel('read');
		$queryCmd = "select distinct cmt.* from CP_CareerMappingTable cmt join CP_CareerTable ct on ct.careerId= cmt.careerId where ct.status ='live' and cmt.status ='live' order by ct.name";
		$query = $this->dbHandle->query($queryCmd);
		$numOfRows = $query->num_rows();
		$result = $query->result_array();
		$res = array();
		if($numOfRows!=0){
			foreach($result as $key=>$value){
				$res[] = $value;
			}
		}
		return $res;
	}
	
	function getCareerData($careerId){
		$careerIds = array();
		$careerIds[] = $careerId;
		$res = $this->getMultipleCareerData($careerIds);
		return $res;
	}
	function getCareerHiearchyMapping($careerIdCSV){
		$this->initiateModel('read');
		$sql = "select careerId, hierarchyId, courseId from careerAttributeMapping where careerId in (?) and  status='live'";
		$careerIdArr = explode(',',$careerIdCSV);
		$query = $this->dbHandle->query($sql, array($careerIdArr));
		$result = $query->result_array();
		return $result;
	}
	
	function getMultipleCareerData($careerIds = array(),$type='ALL_INFO'){
                $this->initiateModel('read');
                $res =array();
                if(empty($careerIds)){
                        return $res;
                }
                $careerIdCSV = implode(',',$careerIds);
                $queryCmd = "select * from CP_CareerTable where careerId in (?) and status = 'live'";
                $query = $this->dbHandle->query($queryCmd, array($careerIds));
                $numOfRows1 = $query->num_rows();
				if($numOfRows1==0) return $res;
                $result = $query->result_array();
                $tmpArray = explode(',',$careerIdCSV);
                foreach($result as $key=>$value){
                        $tmp[$value['careerId']] = $value;
                        //$tmp[$value['careerId']]['seo_url'] = getSeoUrl($value['careerId'],'careerproduct',$value['name']);
                }
                for($i=0;$i<count($tmpArray);$i++){
						if(!empty($tmp[$tmpArray[$i]]) && preg_match('/^\d+$/',$tmpArray[$i])){
							$res[$tmpArray[$i]] =  $tmp[$tmpArray[$i]];
						}
						/*if(!empty($res[$tmpArray[$i]]['seo_url'] ) && preg_match('/^\d+$/',$tmpArray[$i])){
							$res[$tmpArray[$i]]['seo_url'] =  $tmp[$tmpArray[$i]]['seo_url'];
						}*/
                }
		if($type=='BASIC_INFO'){
			return $res;
                }
		if(empty($res)) return $res;
		$ldbResults = $this->getCareerHiearchyMapping($careerIdCSV);
		foreach($ldbResults as $key=>$value){
                        $res[$value['careerId']]['hierarchyId'][] = $value['hierarchyId'];
			$res[$value['careerId']]['courseId'][] = $value['courseId'];
                }
		
		$queryCmd ="SELECT cpvt.careerId,cpvt.keyname,cpvt.value FROM CP_CareerPageValueTable cpvt WHERE cpvt.careerId IN (?) AND cpvt.status = 'live'";
		$query = $this->dbHandle->query($queryCmd, array($careerIds));
		$numOfRows2 = $query->num_rows();
		$result = $query->result_array();

		if($numOfRows2!=0){
			foreach($result as $key=>$value){
				$res[$value['careerId']]['otherCareerInformation'][$value['keyname']] = $value['value'];
			}
		}
		$pathResult = $this->getCareerPath($careerIds);
		for($i=0;$i<count($careerIds);$i++){
			if(array_key_exists($careerIds[$i],$pathResult)){
				$res[$careerIds[$i]]['CareerPathResults'] = $pathResult[$careerIds[$i]];
			}
		}
		$i=0;
		$result = array();
		foreach($res as $key=>$value){
			$result[$i] = $value;
			$i++;
		}
 		return $res;
	}
	
	function getCareerPath($careerId){
		$this->initiateModel('read');
		$careerIdCSV = implode(',',$careerId);
		$res = array();
		$queryCmd = "select cpt.careerId,cpst.id,cpst.stepOrder,cpst.stepTitle,cpst.stepDescription,cpt.pathName,cpt.pathId from  CP_CareerPathTable cpt left join CP_CareerPathStepsTable cpst on (cpt.pathId = cpst.pathId) where cpt.careerId in (?) and cpt.status = 'live' and cpst.status = 'live' order by cpst.stepOrder";
		$query = $this->dbHandle->query($queryCmd, array($careerId));
		$numOfRows = $query->num_rows();
		$result = $query->result_array();
		$tmp = array();
		$j=0;
		if($numOfRows!=0){
			foreach($result as $key=>$value){
				if($value['stepTitle'] || $value['stepDescription']){
					$res[$value['careerId']][$value['pathId']]['pathId'] = $value['careerId'];
					$res[$value['careerId']][$value['pathId']]['pathId'] = $value['pathId'];
					$res[$value['careerId']][$value['pathId']]['pathName'] = $value['pathName'];
					$res[$value['careerId']][$value['pathId']]['steps'][$j]['id'] = $value['id'];
					$res[$value['careerId']][$value['pathId']]['steps'][$j]['stepOrder'] = $value['stepOrder'];
					$res[$value['careerId']][$value['pathId']]['steps'][$j]['stepTitle'] = $value['stepTitle'];
					$res[$value['careerId']][$value['pathId']]['steps'][$j]['stepDescription'] = $value['stepDescription'];
				}
				$j++;
			}
		}
		return $res;
	}
	
	function getAllCareers($type){
		$careerIds = $this->getAllCareerIds();
		$careerDataResults = $this->getMultipleCareerData($careerIds,$type);
		return $careerDataResults;
	}
	function getAllCareerIds(){
		$this->initiateModel('read');
		$queryCmd = "select careerId from  CP_CareerTable where status = 'live' order by name";
		$query = $this->dbHandle->query($queryCmd);
		$numOfRows = $query->num_rows();
		$result = $query->result_array();
		$i=0;
		if($numOfRows!=0){
			foreach($result as $key=>$value){
				$res[$i] = $value['careerId'];
				$i++;
			}
		}
		return $res;
	}

	/*function getRecommendedCareerData($firstChoice,$secondChoice,$stream,$careerId){
		$recommendedCareerIds = $this->getRecommendedCareerId($firstChoice,$secondChoice,$stream,$careerId);
		$res = $this->getMultipleCareerData($recommendedCareerIds);
		return $res;
	}*/
	
	function getChoiceAndStream($careerId){
		$this->initiateModel('read');
		$queryCmd = "select ei1,ei2,stream from  CP_CareerMappingTable where careerId=? and status='live'";
		$query = $this->dbHandle->query($queryCmd,array($careerId));
		$numOfRows = $query->num_rows();
		$result = $query->result_array();
		$i=0;
		if($numOfRows!=0){
			foreach($result as $key=>$value){
				$res['stream'][$i] = $value['stream'];
				$res['ei1'][$i] = $value['ei1'];
				$res['ei2'][$i] = $value['ei2'];
				$i++;
			}
		}
		$data['stream'] = '"'. implode('","', array_unique($res['stream'])) .'"';
		$data['ei1'] = '"'. implode('","', array_unique($res['ei1'])) .'"';
		$data['ei2'] = '"'. implode('","', array_unique($res['ei2'])) .'"';
		return $data;
	}

	function getRecommendedCareerData($careerId){
		$result = $this->getChoiceAndStream($careerId);
		$firstChoice = $result['ei1'];
		$secondChoice = $result['ei2'];
		$stream	= $result['stream'];
		$recommendedCareerIds = $this->getRecommendedCareerId($firstChoice,$secondChoice,$stream,$careerId);
		$res = $this->getMultipleCareerData($recommendedCareerIds);
		return $res;
	}

	function removeDupicateElementsFromArray($array)
	{
		for ($e = 0; $e < count($array); $e++)
				{
				  $duplicate = null;
				  for ($ee = $e+1; $ee < count($array); $ee++)
				  {
				    if (strcmp($array[$ee]['name'],$array[$e]['name']) === 0)
				    {
				      $duplicate = $ee;
				      break;
				    }
				  }
				  if (!is_null($duplicate))
				    array_splice($array,$duplicate,1);
		}
		return $array;
	}

	public function getExpressInterestDetails(){
		$this->initiateModel('read');
		$queryCmd = "SELECT `eiImage`,`eiDescription`,`eiName`,`eiId` FROM `CP_ExpressInterestTable` where status='live' ORDER BY eiId";		
		$query = $this->dbHandle->query($queryCmd);
		$result = $query->result_array();
	 	return $result;
	}
	
	public function _getExpressInterestName($id){
		$this->initiateModel('read');
		$queryCmd = "SELECT `eiName` FROM `CP_ExpressInterestTable` where status='live' and `eiId`=?";		
		$query = $this->dbHandle->query($queryCmd,array($id));
		$result = $query->result_array();
	 	return $result[0]['eiName'];
	}

	public function createCareerUrls(){
		$this->initiateModel('write');
		$queryCmd = "select careerId,careerUrl,name from  CP_CareerTable where status = 'live'";
		$query = $this->dbHandle->query($queryCmd);
		$result = $query->result_array();
		foreach($result as $key=>$value){
			if(empty($value['careerUrl'])){
				$careerUrl = getSeoUrl($value['careerId'],'careerproduct',$value['name']);
				$queryUpdate = "update CP_CareerTable set careerUrl = ? where careerId = ?";
				$query = $this->dbHandle->query($queryUpdate, array($careerUrl,$value['careerId']));
			}
		}
	}

	function storeUserIdToCareerMapping($userId,$careerId){
		$this->initiateModel('write');
		$queryInsert = "insert into  CP_SuggestedCareerMappingForMailer (`id`,`userId`,`mainCareerId`,`suggestedCareerId`,`type`,`creationDate`) values (NULL,?,?,?,?,now())";
		$this->dbHandle->query($queryInsert,array($userId,$careerId,'0','registration'));
	}

	function getUserIdAndCareerIdMapping($type){
		$this->initiateModel('read');
		$res = array();
		$queryCmd = "select cpscm.* from  CP_SuggestedCareerMappingForMailer cpscm, tuser tu where status = ? and type=? and cpscm.userId = tu.userid and (unix_timestamp(now()) - unix_timestamp(tu.usercreationDate))>86400";
		$query = $this->dbHandle->query($queryCmd,array('new',$type));
		$numOfRows = $query->num_rows();
		$result = $query->result_array();
		$i=0;
		if($numOfRows!=0){
			foreach($result as $key=>$value){
				$res[] = $value;
			}
		}
		return $res;
	}

	function updateStatusForRecomReg($userId,$careerId){
		$this->initiateModel('write');
		$queryCmd = "update CP_SuggestedCareerMappingForMailer set status = ?, modificationDate = now() where `userId`=? and `mainCareerId`=? and type=?";
		$this->dbHandle->query($queryCmd,array('old',$userId,$careerId,'registration'));
	}
	
	function setMappingForSimiliarCareerMailer($mainCareerId,$suggestedCareerId,$userId){
		$this->initiateModel('read');
		$queryCmd = "select * from  CP_SuggestedCareerMappingForMailer where mainCareerId = ? and userId=? and status=? and type!='registration'";
		$query = $this->dbHandle->query($queryCmd,array($mainCareerId,$userId,'new'));
		$numOfRows = $query->num_rows();
		$this->initiateModel('write');
		if($numOfRows>0){
			$queryUpdate = "update CP_SuggestedCareerMappingForMailer set modificationDate=now(), suggestedCareerId=? where mainCareerId = ? and userId=? and status=? and type!='registration'";
			$query = $this->dbHandle->query($queryUpdate,array($suggestedCareerId,$mainCareerId,$userId,'new'));
		}else{
			$queryInsert = "insert into CP_SuggestedCareerMappingForMailer (`id`,`mainCareerId`,`suggestedCareerId`,`userId`,`creationDate`,`status`,`modificationDate`,`type`) values (?,?,?,?,now(),?,now(),'similar')";
			$this->dbHandle->query($queryInsert,array(NULL,$mainCareerId,$suggestedCareerId,$userId,'new'));	
		}
	}
	
	function getRecentClickedCareer(){
		$this->initiateModel('read');
		$res = array();
		$queryCmd = "select cps.* from CP_SuggestedCareerMappingForMailer cps ,CP_CareerTable ct where cps.suggestedCareerId = ct.careerId and ct.status = 'live' and (unix_timestamp(now()) - unix_timestamp(cps.modificationDate))>86400 and cps.status=? and type!='registration'";
		$query = $this->dbHandle->query($queryCmd,array('new'));
		$numOfRows = $query->num_rows();
		$result = $query->result_array();
		$i=0;
		if($numOfRows!=0){
			foreach($result as $key=>$value){
				$res[] = $value;
			}
		}
		return $res;
	}

	function updateStatusSimilarRecom($userId,$careerId){
		$this->initiateModel('write');
		$queryCmd = "update CP_SuggestedCareerMappingForMailer set status = ? where `userId`=? and `mainCareerId`=?";
		$this->dbHandle->query($queryCmd,array('old',$userId,$careerId));
	}

	function getSimilarCareerClicked($fromDate, $toDate){
                $this->initiateModel('read');
		$paramsArray = array();
		if(trim($fromDate)!=''){
			$firstpart = " and date(`date`) >= ?";
			array_push($paramsArray,$fromDate);
		}
		if(trim($toDate)!=''){
			$secondpart = " and date(`date`) <= ?";
			array_push($paramsArray,$toDate);
		}
                $sql = "SELECT * FROM `shikshaMailerMis` WHERE url like '%Careers/CareerController/mailer%' and mailer='SimilarCareer' $firstpart $secondpart";
                $query = $this->dbHandle->query($sql, $paramsArray);
                $numOfRows = $query->num_rows();
                $result = $query->result_array();
                $i=0;
		$data = array();
                if($numOfRows!=0){
                        foreach($result as $key=>$value){
                                $parseUrlPath = parse_url($value['url']);
                                $str = ltrim($parseUrlPath['path'],'/');
                                $explodeStr = explode('/',$str);
                                $data[] = $explodeStr[3];
			}
                }
		return $data;
        }
	
	function similarMailerSentOnCurrentDay($fromDate, $toDate){
		$this->initiateModel('read');
		$firstpart='';$secondpart='';
		$paramsArray = array();
		if(trim($fromDate)!=''){
			$firstpart = " and date(`createdTime`) >= ?";
			array_push($paramsArray,$fromDate);
		}
		if(trim($toDate)!=''){
			$secondpart = " and date(`createdTime`) <= ?";
			array_push($paramsArray,$toDate);
		}
		$sql = "SELECT id, toEmail, createdTime FROM `tMailQueue` WHERE `subject` like '%You could also consider other career options ...%' and isSent='sent' $firstpart $secondpart order by createdTime desc";
		$query = $this->dbHandle->query($sql,$paramsArray);
                $numOfRows = $query->num_rows();
		$result = $query->result_array();
		$res = array();
		if($numOfRows!=0){
                        foreach($result as $key=>$value){
				$res[strtotime((date('Y-m-d',strtotime($value['createdTime']))))][] = $value;
			}
                }
		return $res;
	}
	
	function totalNumberOfMailOpened($fromDate, $toDate){
		$this->initiateModel('read');
		$firstpart='';$secondpart='';
		$paramsArray = array();
		if(trim($fromDate)!=''){
			$firstpart = " and date(`date`) >= ?";
			array_push($paramsArray,$fromDate);
		}
		if(trim($toDate)!=''){
			$secondpart = " and date(`date`) <= ?";
			array_push($paramsArray,$toDate);
		}
                $sql = "SELECT * FROM `shikshaMailerMis` WHERE url is NULL and mailer='SimilarCareer' $firstpart $secondpart ";
                $query = $this->dbHandle->query($sql,$paramsArray);
                $numOfRows = $query->num_rows();;
		return $numOfRows;
	}

	function getFeaturedCollegesData($careerId){
		$this->initiateModel('read');
		
		$sql = "select careerId, title, URL, clientId from CP_FeaturedColleges f where f.status = 'live' ";

		if($careerId){
			$sql .= " and f.careerId = ? ";
		}

		$sql .= " order by id asc"; 

		if($careerId){
			$result = $this->dbHandle->query($sql, array($careerId))->result_array();
		}
		else{
			$result = $this->dbHandle->query($sql)->result_array();
		}

		$data = array();
		foreach ($result as $key => $value) {
			$data[$value['careerId']][] = $value;
		}

		return $data;
	}

	/*function getCareerName($careerId){
		$this->initiateModel('read');
		
		if(empty($careerId)){
			return array();
		}

		$sql = "select name from CP_CareerTable where careerId =? and status != 'deleted'";
		$result = $this->dbHandle->query($sql,array($careerId))->result_array();

		return $result[0]['name'];

	}*/

	function updateClientData($clientId){
		$this->initiateModel('write');

		if(empty($clientId)){
			return;
		}

		$sql = "update CP_FeaturedColleges set status='history' where clientId =?";

		$this->dbHandle->query($sql,array($careerId));	
		return;
	}

	function getCareerDataForQuickLinks($careerIds){
		$this->initiateModel('read');
		if($careerIds != ''){
			$queryCmd = "select careerId,careerUrl,name from  CP_CareerTable where status = 'live' and careerId in (?) ";
			$careerArr = explode(',',$careerIds);
			$query = $this->dbHandle->query($queryCmd, array($careerArr));
			$result = $query->result_array();
			return $result;
		}
		return;
	}
}
?>

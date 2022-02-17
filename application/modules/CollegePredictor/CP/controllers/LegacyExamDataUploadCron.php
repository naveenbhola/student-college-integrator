<?php
	class LegacyExamDataUploadCron extends MX_Controller {


		function __construct(){
			parent::__construct();
			$this->validateCron();
			$this->cpmodel = $this->load->model('CP/cpmodel');
			$this->rpmodel  = $this->load->model('RP/rpmodel');
			$this->collegeshortlistmodel = $this->load->model('collegeshortlist');
			$this->ldbmodel = $this->load->model('LDBLeadMigrationModel');
			$this->iimpredictormodel = $this->load->model('IIMPredictor/iimpredictormodel');
			$this->load->config('common/examGroupConfig');
			$this->examMapping = $this->config->item('examMapping');
			$this->collegepredictorlibrary = $this->load->library('CP/CollegePredictorLibrary');
		}

		public function uploadTrackingData($type = "cp"){
			ini_set('max_execution_time', -1);
			ini_set('memory_limit',"2500M");
			$startDate = date('Y-m-d G:i:s');
			$endDate = "2019-01-01 00:00:00";
			$batchSize = 500;
			$batchNo = 1;
			if($type == "cp"){
				error_log("Running for college predictor");
				$count = $this->cpmodel->getTrackingDataCount($startDate,$endDate);
				$count = $count[0]['count'];
				error_log("Total size of data = ".$count);
				while((($batchNo-1)*$batchSize) < $count){
					error_log("Inserting for batch = ".$batchNo);
					$collegePredictorData = $this->cpmodel->getPredictorTrackingData($startDate,$endDate,$batchNo-1,$batchSize);
					$trackingData = array();
					foreach ($collegePredictorData as $key => $value) {
						if(empty($value['userId']) || $value['userId'] <= 0 ){
							continue;
						}
						$data['userId'] = $value['userId'];
						$data['examName'] = $value['examName'];
						if(!empty($value['rank']) && $value['rank'] > 0){
							$data['marks'] = $value['rank'];
							$data['marksType'] = "rank";
						}
						else if (!empty($value['percentile']) && $value['percentile'] > 0 && $value['percentile'] <= 100) {
							$data['marks'] = $value['percentile'];
							$data['marksType'] = "percentile";
						}
						else if (!empty($value['score'] ) && $value['score'] > 0) {
							$data['marks'] = $value['score'];
							$data['marksType'] = "score";
						}
						else{
							continue;
						}
						$data["SubmitDate"] = $value["SubmitDate"];
						$trackingData[] = $data;
					}
					$this->formatDataAndUpload($trackingData);
					$batchNo++;
				}
				$batchNo = 1;
			}
			else if ($type == "rp") {
				error_log("Running for rank predictor");
				$count = $this->rpmodel->getTrackingDataCount($startDate,$endDate);
				$count = $count[0]['count'];
				error_log("Total size of data = ".$count);
				while((($batchNo-1)*$batchSize) < $count){
					echo $batchNo;
					error_log("Inserting for batch = ".$batchNo);
					$rankPredictorData = $this->rpmodel->getPredictorTrackingData($startDate,$endDate,$batchNo-1,$batchSize);
					$trackingData = array();
					foreach ($rankPredictorData as $key => $value) {
						if(empty($value['userId']) || $value['userId'] <= 0 ){
							continue;
						}
						$data['userId'] = $value['userId'];
						$data['examName'] = $value['examName'];
						if(!empty($value['rank']) && $value['rank'] > 0){
							$data['marks'] = $value['rank'];
							$data['marksType'] = "rank";
						}
						else if (!empty($value['percentile']) && $value['percentile'] > 0 && $value['percentile'] <= 100) {
							$data['marks'] = $value['percentile'];
							$data['marksType'] = "percentile";
						}
						else if (!empty($value['score'] ) && $value['score'] > 0) {
							$data['marks'] = $value['score'];
							$data['marksType'] = "score";
						}
						else{
							continue;
						}
						$data["SubmitDate"] = $value["SubmitDate"];
						$trackingData[] = $data;
					}
					$this->formatDataAndUpload($trackingData);
					$batchNo++;
				}
				$batchNo = 1;
			}
			else if($type == "shortlist"){
				error_log("Running for college shortlist");
				$count = $this->collegeshortlistmodel->getTrackingDataCount($startDate,$endDate);
				$count = $count[0]['count'];
				error_log("Total size of data = ".$count);
				while((($batchNo-1)*$batchSize) < $count){
					error_log("Inserting for batch = ".$batchNo);
					$shortlistPredictorData = $this->collegeshortlistmodel->getPredictorTrackingData($startDate,$endDate,$batchNo-1,$batchSize);
					$trackingData = array();
					foreach ($shortlistPredictorData as $key => $value) {
						if(empty($value['userId']) || $value['userId'] <= 0 ){
							continue;
						}
						$data['userId'] = $value['userId'];
						$data['examName'] = $value['examName'];
						if($value['resultType'] == "rank" && $value['result'] >0){
							$data['marks'] = $value['result'];
							$data['marksType'] = $value['resultType'];
						}
						else if($value['resultType'] == "percentile" && $value['result'] >0 && $value['result'] <= 100 ){
							$data['marks'] = $value['result'];
							$data['marksType'] = $value['resultType'];
						}
						else if($value['resultType'] == "score" && $value['result'] >0){
							$data['marks'] = $value['result'];
							$data['marksType'] = $value['resultType'];
						}

						$data["SubmitDate"] = $value["SubmitDate"];
						$trackingData[] = $data;
					}
					$this->formatDataAndUpload($trackingData);
					$batchNo++;
				}
				$batchNo = 1;
			}
			else if($type == "er"){
				$this->load->builder('ExamBuilder','examPages');
		    	$examBuilder          = new ExamBuilder();
		      	$examRepository = $examBuilder->getExamRepository();
				error_log("Running for e-Responses");
				$startDate = DateTime::createFromFormat("Y-m-d G:i:s", $startDate)->format("Y-m-d\TH:i:s");
				$endDate = DateTime::createFromFormat("Y-m-d G:i:s", $endDate)->format("Y-m-d\TH:i:s");
				$from = 0;
				$bunch = 1;
				while (true) {
					$result = $this->collegepredictorlibrary->processAutoSubscriptionForLegacyUsers($endDate,$startDate,$from);
					if($result['gotResult'] == false){
						break;
					}
					$from += 50000;
					$returnData = $result['return'];
					$chunkedData = array_chunk($returnData, 500);
					foreach ($chunkedData as $value) {
						error_log("For bunch = ".$bunch);
						$bunch++;
						$this->formatEResponseData($examRepository,$value);
						$this->formatDataAndUpload($value);
					}
					error_log("Done");
				}
				$batchNo = 1;
			}
			else if($type == "iim"){
				error_log("Running for iim predictor");
				$count = $this->iimpredictormodel->getTrackingDataCount($startDate,$endDate);
				$count = $count[0]['count'];
				error_log("Total size of data = ".$count);
				while((($batchNo-1)*$batchSize) < $count){
					error_log("Inserting for batch = ".$batchNo);
					$iimPredictorData = $this->iimpredictormodel->getPredictorTrackingData($startDate,$endDate,$batchNo-1,$batchSize);
					$trackingData = array();
					foreach ($iimPredictorData as $key => $value) {
						if(empty($value['userId']) || $value['userId'] <= 0 ){
							continue;
						}
						$data['userId'] = $value['userId'];
						$data['examName'] = "CAT";
						if (!empty($value['percentile']) && $value['percentile'] > 0 && $value['percentile'] <= 100) {
							$data['marks'] = $value['percentile'];
							$data['marksType'] = "percentile";
						}
						else if (!empty($value['score'] ) && $value['score'] > 0) {
							$data['marks'] = $value['score'];
							$data['marksType'] = "score";
						}
						else{
							continue;
						}

						$data["SubmitDate"] = $value["SubmitDate"];
						$trackingData[] = $data;
					}
					$this->formatDataAndUpload($trackingData);
					$batchNo++;
				}
				$batchNo = 1;
			}
			error_log("Done !!!");

		}

		function formatDataAndUpload(&$trackingData){
			$examsDataToUpload = array();
			$userIds = array();
			if(is_array($trackingData) && !empty($trackingData)){
				foreach ($trackingData as $predictorData) {
					$userIds[$predictorData['userId']] = $predictorData['userId'];
					if(empty($predictorData['groupId']) && !array_key_exists($predictorData['examName'], $this->examMapping)){
						error_log("No groupId available for exam = ".$predictorData['examName']);
					}
				}
			}
			else{
				error_log("Empty Array");
				return ;
			}
			$userData = $this->ldbmodel->getUserDataInProfile($userIds);

			foreach ($trackingData as $predictorData) {
				if(empty($predictorData['groupId'])){
					if(isset($userData[$predictorData['userId']][$this->examMapping[$predictorData['examName']]['groupId']])) {
						continue ;
					}
				}
				else{
					if(isset($userData[$predictorData['userId']][$predictorData['groupId']]) ){
						continue ;
					}
				}
				$examData['UserId'] = $predictorData['userId'];
				if(isset($predictorData['marks'])){
					$examData['marks'] = $predictorData['marks'];
					$examData['marksType'] = $predictorData['marksType'];
				}
				if(empty($predictorData['groupId']) && !array_key_exists($predictorData['examName'], $this->examMapping)){
					error_log("No groupId available for exam = ".$predictorData['examName']);
					unset($examData);
					continue ;
				}
				if(!empty($predictorData['groupId'])){
					$examData['examGroupId'] = $predictorData['groupId'];
				}
				else{
					$examData['examGroupId'] = $this->examMapping[$predictorData['examName']]['groupId'];
				}
				$examData['Level'] = "Competitive exam";
				$examData['Name'] = $predictorData['examName'];
				$examData['Status'] = 'live';
				$examData['SubmitDate'] = $predictorData['SubmitDate'];
				$examsDataToUpload[]=$examData;
				unset($examData);
			}
			if(is_array($examsDataToUpload) && !empty($examsDataToUpload)){
				$insertedCount = $this->ldbmodel->postUserExamDataInProfile($examsDataToUpload);
				error_log("Inserted Data Count = ".$insertedCount);
			}
			else{
				error_log("Zero rows inserted");
			}
		}


		function formatEResponseData($examRepository,&$eResponseData){
			$groupIds = array();
			foreach ($eResponseData as $key => $value) {
				$groupIds[] = $value['groupId'];
			}
	        $groupObjs = $examRepository->findMultipleGroup($groupIds);
	        $examIds = array();
	        foreach ($groupObjs as $groupId => $groupObj) {
	            $examIds[] = $groupObj->getExamId();
	        }
	        $examObjs = $examRepository->findMultiple($examIds);
			foreach ($eResponseData as $key => $value) {
				$groupObject = $groupObjs[$value['groupId']];
				if($groupObject == null){
					unset($eResponseData[$key]);
					continue;
				}
				$examObj = $examObjs[$groupObject->getExamId()];
				$eResponseData[$key]['examName'] = $examObj->getName();
			}
		}
	}
?>
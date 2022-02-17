<?php

class RankPredictor_Utility {

	private $_ci = null;
	
	function __construct()
	{
		$this->_ci =& get_instance();
                $this->_ci->load->config('RP/RankPredictorConfig',TRUE);
                $this->settingsRankPredictor = $this->_ci->config->item('settings','RankPredictorConfig');
	}

	public function getExamData($data){
		if(isset($data['examName']) && $data['examName']!=''){
			$examNameFromURL = $data['examName'];
			$examName = $this->settingsRankPredictor[$examNameFromURL]['examName'];
			$rollNumber = isset($data['rollNumber'])?$data['rollNumber']:'';
			$examScore = $data['examScore'];
			$boardScore = isset($data['boardScore'])?$data['boardScore']:0;
			$source = isset($data['source'])?$data['source']:'desktop';
			$userArray = isset($data['userArray'])?$data['userArray']:array();

			if($this->settingsRankPredictor[$examNameFromURL]['isCallAakashAPI']=='YES'){
				$result = $this->callAakashAPI($examName,$rollNumber, $examScore, $boardScore,$source, $userArray);
			}
			else{
				$result = $this->getRankDataForExam($examName,$rollNumber, $examScore, $boardScore,$source, $userArray);				
			}
			return $result;
		}
		return false;
	}
	
        private function callAakashAPI($examName,$rollNumber, $examScore, $boardScore, $source, $userArray){

                if(isset($userArray) && is_array($userArray) && count($userArray)>0){
                        $signedInUser = $userArray;
                        $userId = $signedInUser[0]['userid'];
                        $userFirstName = $signedInUser[0]['firstname'];
                        $userLastName = $signedInUser[0]['lastname'];
                        $array = explode('|',$signedInUser[0]['cookiestr']);
                        $email = $array[0];
                        $mobile = $signedInUser[0]['mobile'];
                }
                else{
                        $result = 'User not logged-in';
                }

                if($examScore !=''){
                        $url = "http://www.aakash.ac.in/global_script/demofunction.php?type=rank_pretict&name=$userFirstName$userLastName&rollno=$rollNumber&jeemarks=$examScore&emailid=$email&mobile=$mobile";
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,30);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                        $output = curl_exec($ch);
                        // Check if any error occurred
                        if(curl_errno($ch) > 0)
                        {
                            curl_close($ch);
                            $result = 'Something went wrong.';
                        }
                        curl_close($ch);
                        $result = $output;
                }else{
                        $result = 'Data is wrong';
                }
		
		//Parse Result returned by Aakash
                $msg = "Something went wrong.";
                $result = json_decode($result,true);
                if($result == 'Data is wrong' || $result == 'Something went wrong.' || $result['cases'][0]['msg'] == "" || $result == 'User not logged-in'){
                        $msg = array('msg'=>'Sorry, unable to predict your rank now.<br> Please try again later.','startRank'=>0,'closingRank'=>0);
                }else if(is_array($result) && isset($result['cases'])){
                        if($result['cases'][0]['msg'] != ''){
                                $msg = $result['cases'][0]['msg'];
                                $array = explode('between',$msg);
                                if(isset($array[1])){
                                   $arrayTemp = explode('and',$array[1]);
                                   $minRange = trim($arrayTemp[0]);
                                   $maxRange = trim($arrayTemp[1]);
                                }
                                $msg = array('msg'=>'Your Predicted Rank:<strong class="font-30" id="predicted-rank" style="vertical-align: middle;color:#1b1b1b;">&nbsp;'.$minRange.' - '.$maxRange.'</strong>','startRank'=>$minRange,'closingRank'=>$maxRange);

                                if(($minRange =="" || $minRange == 0) || ($maxRange =="" || $maxRange ==0)){
                                        $msg = array('msg'=>'Sorry, unable to predict your rank now.<br> Please try again later.','startRank'=>0,'closingRank'=>0);
                                }

                        }
                }

                if($msg['startRank'] == 0 || $msg['closingRank'] == 0)
                {
                        $aakashResult = 'Rank not found';
                }else{
                        $aakashResult = $msg['startRank'].' - '.$msg['closingRank'];
                }
                $this->makeLogEntry($examName,$rollNumber,$examScore,$boardScore,$aakashResult,$source, $userArray);
                return json_encode($msg);		
        }

	private function getRankDataForExam($examName,$rollNumber, $examScore, $boardScore,$source, $userArray){
                $this->_ci->load->model('RP/rpmodel');
		$data['examName'] = $examName;
		
		//In case of COMEDK, the CalcuatedScore is same as Exam Score
		if($examName=='COMEDK' || $examName=='JEE Advanced'){
			$data['calculatedScore'] = $examScore;
		}
		
                $result = $this->_ci->rpmodel->getRankDataForExam($data);
		if(is_array($result) && count($result)>0){
			$minRange = $maxRange = 0;
			foreach ($result as $rankArray){
				$minRange = $rankArray['minRank'];
				$maxRange = $rankArray['maxRank'];
			}
			$msg = array('msg'=>'Your Predicted Rank:<strong class="font-30" id="predicted-rank" style="vertical-align: middle;color:#1b1b1b;">&nbsp;'.$minRange.' - '.$maxRange.'</strong>','startRank'=>$minRange,'closingRank'=>$maxRange);
		}
		else{
			$msg = array('msg'=>'Sorry, unable to predict your rank now.<br> Please try again later.','startRank'=>0,'closingRank'=>0);
		}
		
                if($msg['startRank'] == 0 || $msg['closingRank'] == 0)
                {
                        $dbResult = 'Rank not found';
                }else{
                        $dbResult = $msg['startRank'].' - '.$msg['closingRank'];
                }
                $this->makeLogEntry($examName,$rollNumber,$examScore,$boardScore,$dbResult,$source,$userArray);
		return json_encode($msg);
	}

        function makeLogEntry($exam,$examRollNumber,$examScore,$boardScore,$apiResponse,$source='desktop',$userArray=array()){
                $data = array();
                $this->_ci->load->model('RP/rpmodel');
                //Fetch the User Id
                if(isset($userArray) && is_array($userArray) && count($userArray)>0){
                        $signedInUser = $userArray;
                        $data['userId'] = $signedInUser[0]['userid'];
                }
                else{
                        $data['userId'] = 0;
                }

                if(!empty($exam)){
                    $data['examName'] = $exam;
                    $data['examRollNumber'] = $examRollNumber;
                    $data['examScore'] = $examScore;
                    $data['boardScore'] = $boardScore;
                    $data['apiResponse'] = $apiResponse;
                    $data['source'] = $source;
                    $this->_ci->rpmodel->insertActivityLog($data);    
                }
        }

        function prepareBeaconTrackData($pageIdentifier,$examName){
        $beaconTrackData = array(
            'pageIdentifier' => $pageIdentifier,
            'pageEntityId'   => '0', // No Page entity id for this one
            'extraData' => array(
                'hierarchy' =>  array(
                    'streamId'          => ENGINEERING_STREAM,
                    'substreamId'       => 0,
                    'specializationId'  => 0
                    ),
                'examName'  =>  strtolower($examName),
                'baseCourseId' => ENGINEERING_COURSE,
                'countryId' =>  2
                )
        );
        return $beaconTrackData;
    }

    function getGTMArray($pageType, $examName){
        
        $gtmArray = array(
            "pageType" => $pageType,
            "exam"=> $examName,
            "toolName"=>'RankPredictor',
        );
        if($userStatus!='false' && $userStatus[0]['experience']!==""){
            $gtmArray['workExperience'] = $userStatus[0]['experience'];
        }
        return $gtmArray;
    }

    function getRankBasedOnUserScore($score,$percentile1,$percentile2,$examName){

        if(($score < 0 && $percentile1 < 0 && $percentile2 < 0) || empty($examName)){
            return;
        }

        $score       = $score > 0 ? $score : 0;
        $percentile2 = $percentile2 > 0 ? $percentile2 : 0;
        $percentile1 = $percentile1 > 0 ? $percentile1 : 0;

        $rpmodel = $this->_ci->load->model("RP/rpmodel");
        

        $cacheLibrary = $this->_ci->load->library('RP/cache/RankPredictorCache');

        $maxRankInPredictor1 = 884000;
        $maxRankInPredictor2 = 934000;

        /*$maxRankInPredictor = $cacheLibrary->getMaxRank();
        if(empty($maxRankInPredictor)) {
            $maxRankInPredictor = $rpmodel->getMaxRankFromTable($examName);
            $cacheLibrary->storeMaxRank($maxRankInPredictor);
        }*/

        if(!empty($score) && $score > 0 && (empty($percentile2) || $percentile2 < 0)) {
            $result = $rpmodel->getRankByUserScore($score,$examName);    
            $calculatedRank = (($result['maxScore'] - $score ) * $result['slope']) + $result['minRank'];
            $maxRankInPredictor = $maxRankInPredictor2;
            $calculatedRank = round($calculatedRank);
            $percentile2 = (($maxRankInPredictor - $calculatedRank) / $maxRankInPredictor )* 100;
        }

        if(!empty($percentile1) && !empty($percentile2)){
            $maxPercentile = $percentile1 > $percentile2 ? $percentile1 : $percentile2;
            $maxRankInPredictor = $percentile1 > $percentile2 ? $maxRankInPredictor1 : $maxRankInPredictor2;
            $calculatedRank = ($maxRankInPredictor + 1) - ($maxPercentile * $maxRankInPredictor) / 100;
        }else if(!empty($percentile1)){
            $maxPercentile = $percentile1;
            $maxRankInPredictor = $maxRankInPredictor1;
            $calculatedRank = ($maxRankInPredictor + 1) - ($maxPercentile * $maxRankInPredictor) / 100;
        }else if(!empty($percentile2)){
            $maxPercentile = $percentile2;
            $maxRankInPredictor = $maxRankInPredictor2;
            $calculatedRank = ($maxRankInPredictor + 1) - ($maxPercentile * $maxRankInPredictor) / 100;
        }
        //
        $dbData = array();
        if(!empty($calculatedRank)){

            //$calculatedRank = (($result['maxScore'] - $score ) * $result['slope']) + $result['minRank'];
            $calculatedRank = round($calculatedRank);
            //$calculatedPercentile = (($maxRankInPredictor - $calculatedRank) / $maxRankInPredictor )* 100;
            $dbData['midRank'] = round($calculatedRank * 1.20);
            $dbData['minRank'] = round($dbData['midRank'] * 0.95);
            $dbData['maxRank'] = round($dbData['midRank'] * 1.05);
            $dbData['userPercentile'] = number_format($maxPercentile,4,'.','');

            if($dbData['minRank'] > 0 && $dbData['maxRank'] > 0 && $dbData['midRank'] > 0){
                if($dbData['minRank'] == $dbData['midRank'] && $dbData['maxRank'] == $dbData['midRank']){
                    $dbData['minRank'] = "";
                    $dbData['maxRank'] = "";
                }
            }
            else{
                $dbData['minRank'] = 0;
                $dbData['maxRank'] = 0;
                $dbData['midRank'] = 0;
            }
        }
        return $dbData;
    }
}

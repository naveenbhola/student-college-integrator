<?php
class CareerUtilityLib
{
private $_streamAny = 'Science, Commerce, Humanities/Arts';
private $_streamScienceCommerce = 'Science, Commerce';
private $_streamScienceHumanities = 'Science, Humanities';
private $_streamCommerceHumanities = 'Commerce, Humanities';
private $_streamScience = 'Science';
private $_streamCommerce = 'Commerce';
private $_streamHumanities = 'Humanities';

function __construct()
{
		$this->CI = & get_instance();
		$this->CI->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$this->careerRepository = $careerBuilder->getCareerRepository();
		$this->CI->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepository = $courseBuilder->getCourseRepository();
		
}

public function formatCareerSectionAndCourseIds($country,$careerInformation){
	if($careerInformation['indiawhereToStudyCount']!=''){
		$indiawhereToStudyCountArr = explode(',',$careerInformation['indiawhereToStudyCount']);
	}

	if($careerInformation['abroadwhereToStudyCount']!=''){
		$abroadwhereToStudyCountArr = explode(',',$careerInformation['abroadwhereToStudyCount']);
	}
       $postionArr = array('','First','Second','Third','Forth','Fifth');
       foreach(${$country.'whereToStudyCountArr'} as $key=>$value){
        ${$country.'whereToStudyCourseIdCountFor'.$postionArr[$value].'SectionArr'} = explode(',',$careerInformation[$country.'whereToStudyCourseIdCountFor'.$postionArr[$value].'Section']);
        $i=0;
        foreach(${$country.'whereToStudyCourseIdCountFor'.$postionArr[$value].'SectionArr'} as $k=>$v){
                if(array_key_exists($country.'CourseId_'.$postionArr[$value].'_'.$v,$careerInformation)){
                        $course_id = $careerInformation[$country.'CourseId_'.$postionArr[$value].'_'.$v];
			if($course_id>0){
		                $courseIdArr[$country.'CourseId_'.$postionArr[$value].'_'.$v] = $this->courseRepository->find($course_id);
		                $institute_id = '';
		                if($courseIdArr[$country.'CourseId_'.$postionArr[$value].'_'.$v]->getId() > 0) {
		                        $instituteDetail[$country.'CourseId_'.$postionArr[$value].'_'.$v]['name'] = $courseIdArr[$country.'CourseId_'.$postionArr[$value].'_'.$v]->getInstituteName();
		                        $instituteDetail[$country.'CourseId_'.$postionArr[$value].'_'.$v]['url']  = $courseIdArr[$country.'CourseId_'.$postionArr[$value].'_'.$v]->getURL();
		                }
			}else{
				$instituteDetail[$country.'CourseId_'.$postionArr[$value].'_'.$v]['name'] = '';
		                $instituteDetail[$country.'CourseId_'.$postionArr[$value].'_'.$v]['url']  = '';
			}
                        $i++;
                }
		if(array_key_exists($country.'CourseText_'.$postionArr[$value].'_'.$v,$careerInformation)){
                        $course_text = $careerInformation[$country.'CourseText_'.$postionArr[$value].'_'.$v];
                        $instituteDetail[$country.'CourseText_'.$postionArr[$value].'_'.$v]['name'] = $course_text;
                        $instituteDetail[$country.'CourseText_'.$postionArr[$value].'_'.$v]['url']  = '';
                        $i++;
                }
		
        }
    } 
    return $instituteDetail;
}

function careerErrorPage($code='404') {
        header("Sorry, we couldn't find the page you requested.", true, 404);
        $this->config = & get_config();
        $base_url = $this->config['base_url'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url . "Careers/CareerController/errorPage/".$code);
        curl_exec($ch);
        curl_close($ch);
        exit();
}

function getFormatedSuggestedCareerData($data,$expressInterestFirst,$expressInterestSecond,$expressInterestFirstName,$expressInterestSecondName,$stream){
		if($expressInterestFirst!='' && $expressInterestSecond!=''){
				$firstResult = $this->_applyFirstAlgorithm($data,$expressInterestFirst,$expressInterestSecond);
				if(!empty($firstResult)){
						$firstFinalResult = $this->_processData($firstResult,$expressInterestFirstName,$expressInterestSecondName,$stream);
						$firstFinalResultCareerIds = array_keys($firstFinalResult);	
				}else{
						$firstFinalResult = array();
						$firstFinalResultCareerIds = array();
				}
				if(count($firstFinalResult)<5){
						$secondResult = $this->_applySecondAlgorithm($data,$expressInterestFirst,$expressInterestSecond,$firstFinalResultCareerIds);
						if(!empty($secondResult)){
								$secondFinalResult = $this->_processData($secondResult,$expressInterestFirstName,$expressInterestSecondName,$stream);
								$secondFinalResultCareerIds = array_keys($secondFinalResult);		
						}else{
								$secondFinalResult = array();
								$secondFinalResultCareerIds = array();		
						}
				}else{
						return $firstFinalResult;				
				}
				
				if(count($firstFinalResult+$secondFinalResult)<5){
						$careerIds = array_merge($firstFinalResultCareerIds,$secondFinalResultCareerIds);
						$thirdResult = $this->_applyThirdAlgorithm($data,$expressInterestFirst,$expressInterestSecond,$careerIds);
						if(!empty($thirdResult)){
								$thirdFinalResult = $this->_processData($thirdResult,$expressInterestFirstName,'',$stream);
								$thirdFinalResultCareerIds = array_keys($thirdFinalResult);
						}else{
								$thirdFinalResult = array();
								$thirdFinalResultCareerIds = array();
						}
						
				}else{
						return $firstFinalResult+$secondFinalResult;
				}
				
				if(count($firstFinalResult+$secondFinalResult+$thirdFinalResult)<5){
						$careerIds = array_merge($firstFinalResultCareerIds,$secondFinalResultCareerIds+$thirdFinalResultCareerIds);
						$forthResult = $this->_applyForthAlgorithm($data,$expressInterestFirst,$expressInterestSecond,$careerIds);
						if(!empty($forthResult)){
								$forthFinalResult = $this->_processData($forthResult,$expressInterestFirstName,'',$stream);
								$forthFinalResultCareerIds = array_keys($forthFinalResult);								
						}else{
								$forthFinalResult = array();
								$forthFinalResultCareerIds = array();
						}
						
				}else{
						return $firstFinalResult+$secondFinalResult+$thirdFinalResult;
				}
				
				if(count($firstFinalResult+$secondFinalResult+$thirdFinalResult+$forthFinalResult)<5){
						$careerIds = array_merge($firstFinalResultCareerIds,$secondFinalResultCareerIds+$thirdFinalResultCareerIds,$forthFinalResultCareerIds);
						$fifthResult = $this->_applyFifthAlgorithm($data,$expressInterestFirst,$expressInterestSecond,$careerIds);
						if(!empty($fifthResult)){
								$fifthFinalResult = $this->_processData($fifthResult,'',$expressInterestSecondName,$stream);
								$fifthFinalResultCareerIds = array_keys($fifthFinalResult);								
						}else{
								$fifthFinalResult = array();
								$fifthFinalResultCareerIds = array();
						}
						
				}else{
						return $firstFinalResult+$secondFinalResult+$thirdFinalResult+$forthFinalResult;
				}
				
				if(count($firstFinalResult+$secondFinalResult+$thirdFinalResult+$forthFinalResult+$fifthFinalResult)<5){
						$careerIds = array_merge($firstFinalResultCareerIds,$secondFinalResultCareerIds+$thirdFinalResultCareerIds,$forthFinalResultCareerIds+$fifthFinalResultCareerIds);
						$sixthResult = $this->_applySixthAlgorithm($data,$expressInterestFirst,$expressInterestSecond,$careerIds);
						if(!empty($sixthResult)){
								$sixthFinalResult = $this->_processData($sixthResult,'',$expressInterestSecondName,$stream);
								$sixthFinalResultCareerIds = array_keys($sixthFinalResult);
						}else{
								$sixthFinalResult = array();
								$sixthFinalResultCareerIds = array();
								
						}
						return $firstFinalResult+$secondFinalResult+$thirdFinalResult+$forthFinalResult+$fifthFinalResult+$sixthFinalResult;
						
				}else{
						return $firstFinalResult+$secondFinalResult+$thirdFinalResult+$forthFinalResult+$fifthFinalResult;
				}
		}else{
				$firstResult = $this->_applyFirstAlgorithmForSignleEI($data,$expressInterestFirst);
				if(!empty($firstResult)){
						$firstFinalResult = $this->_processData($firstResult,$expressInterestFirstName,$expressInterestSecondName,$stream);
						$firstFinalResultCareerIds = array_keys($firstFinalResult);
				}else{
						$firstFinalResult = array();
						$firstFinalResultCareerIds = array();
						
				}
				
				if(count($firstFinalResult)<5){
						$secondResult = $this->_applySecondAlgorithmForSignleEI($data,$expressInterestFirst,$firstFinalResultCareerIds);
						$secondFinalResult = $this->_processData($secondResult,$expressInterestFirstName,$expressInterestSecondName,$stream);
						return $firstFinalResult+$secondFinalResult;
				}else{
						return $firstFinalResult;
				}
		}
}

private function _applyFirstAlgorithm($res,$expressInterestFirst,$expressInterestSecond){
		foreach($res as $key=>$value){
			if($value['ei1']==$expressInterestFirst && $value['ei2'] == $expressInterestSecond){
				$data[$value['stream']][] = $value['careerId'];
			}
		}
		return $data;
}

private function _applySecondAlgorithm($res,$expressInterestFirst,$expressInterestSecond,$firstFinalResultCareerIds){
		foreach($res as $key=>$value){
			if(!in_array($value['careerId'],$firstFinalResultCareerIds)){	
				if($value['ei1']==$expressInterestSecond && $value['ei2'] == $expressInterestFirst){
					$data[$value['stream']][] = $value['careerId'];
				}
			}
		}
		return $data;
}

private function _applyThirdAlgorithm($res,$expressInterestFirst,$expressInterestSecond,$careerIds){
		foreach($res as $key=>$value){
			if(!in_array($value['careerId'],$careerIds)){	
				if($value['ei1']==$expressInterestFirst){
					$data[$value['stream']][] = $value['careerId'];
				}
			}
		}
		return $data;
}

private function _applyForthAlgorithm($res,$expressInterestFirst,$expressInterestSecond,$careerIds){
		foreach($res as $key=>$value){
			if(!in_array($value['careerId'],$careerIds)){	
				if($value['ei2']==$expressInterestFirst){
					$data[$value['stream']][] = $value['careerId'];
				}
			}
		}
		return $data;
}

private function _applyFifthAlgorithm($res,$expressInterestFirst,$expressInterestSecond,$careerIds){
		foreach($res as $key=>$value){
			if(!in_array($value['careerId'],$careerIds)){	
				if($value['ei1']==$expressInterestSecond){
					$data[$value['stream']][] = $value['careerId'];
				}
			}
		}
		return $data;
}

private function _applySixthAlgorithm($res,$expressInterestFirst,$expressInterestSecond,$careerIds){
		foreach($res as $key=>$value){
			if(!in_array($value['careerId'],$careerIds)){	
				if($value['ei2']==$expressInterestSecond){
					$data[$value['stream']][] = $value['careerId'];
				}
			}
		}
		return $data;
}

private function _applyFirstAlgorithmForSignleEI($res,$expressInterestFirst){
		foreach($res as $key=>$value){
			if($value['ei1']==$expressInterestFirst){
				$data[$value['stream']][] = $value['careerId'];
			}
		}
		return $data;
}

private function _applySecondAlgorithmForSignleEI($res,$expressInterestFirst){
		foreach($res as $key=>$value){
			if(!in_array($value['careerId'],$careerIds)){	
				if($value['ei2']==$expressInterestFirst){
					$data[$value['stream']][] = $value['careerId'];
				}
			}
		}
		return $data;
}


private function _processData($data,$expressInterestFirstName,$expressInterestSecondName,$stream){
		$finalInfo['SCH'] = array_intersect($data['Science'],$data['Commerce'],$data['Humanities']);
		 
		if(empty($finalInfo['SCH'])){
				$finalInfo['SCH'] = array();		
		}
		
		//$commonElemetBetweenScienceAndCommerce = array_intersect($data['Science'],$data['Commerce']);
		/*$finalInfo['SC'] = array();
		if(!empty($commonElemetBetweenScienceAndCommerce)){
				$finalInfo['SC'] = array_diff($commonElemetBetweenScienceAndCommerce,$finalInfo['SCH']);
		}*/
		/*$commonElemetBetweenCommerceAndHUmanities = array_intersect($data['Commerce'],$data['Humanities']);
		$finalInfo['CH'] = array();
		if(!empty($commonElemetBetweenCommerceAndHUmanities)){
				$finalInfo['CH'] = array_diff($commonElemetBetweenCommerceAndHUmanities,$finalInfo['SCH']);
		}*/
		/*$commonElemetBetweenScienceAndHumanities = array_intersect($data['Science'],$data['Humanities']);
		$finalInfo['SH'] = array();
		if(!empty($commonElemetBetweenScienceAndHumanities)){
			$finalInfo['SH'] = array_diff($commonElemetBetweenScienceAndHumanities,$finalInfo['SCH']);
		}*/
		
		if($stream=='Science'){
				$finalInfo['SC'] = array();
				$commonElemetBetweenScienceAndCommerce = array_intersect($data['Science'],$data['Commerce']);
				if(!empty($commonElemetBetweenScienceAndCommerce)){
						$finalInfo['SC'] = array_diff($commonElemetBetweenScienceAndCommerce,$finalInfo['SCH']);
				}
				$commonElemetBetweenScienceAndHumanities = array_intersect($data['Science'],$data['Humanities']);
				$finalInfo['SH'] = array();
				if(!empty($commonElemetBetweenScienceAndHumanities)){
						$finalInfo['SH'] = array_diff($commonElemetBetweenScienceAndHumanities,$finalInfo['SCH']);
				}
				$finalInfo['S'] = array_diff($data['Science'],array_unique($finalInfo['SCH']+$finalInfo['SC']+$finalInfo['SH']));
		}
		if($stream=='Commerce'){
				$finalInfo['SC'] = array();
				$commonElemetBetweenScienceAndCommerce = array_intersect($data['Science'],$data['Commerce']);
				if(!empty($commonElemetBetweenScienceAndCommerce)){
						$finalInfo['SC'] = array_diff($commonElemetBetweenScienceAndCommerce,$finalInfo['SCH']);
				}
				$commonElemetBetweenCommerceAndHUmanities = array_intersect($data['Commerce'],$data['Humanities']);
				$finalInfo['CH'] = array();
				if(!empty($commonElemetBetweenCommerceAndHUmanities)){
						$finalInfo['CH'] = array_diff($commonElemetBetweenCommerceAndHUmanities,$finalInfo['SCH']);
				}
				$finalInfo['C'] = array_diff($data['Commerce'],array_unique($finalInfo['SCH']+$finalInfo['CH']+$finalInfo['SC']));
		}
		if($stream=='Humanities'){
				$commonElemetBetweenScienceAndHumanities = array_intersect($data['Science'],$data['Humanities']);
				$finalInfo['SH'] = array();
				if(!empty($commonElemetBetweenScienceAndHumanities)){
						$finalInfo['SH'] = array_diff($commonElemetBetweenScienceAndHumanities,$finalInfo['SCH']);
				}
				$commonElemetBetweenCommerceAndHUmanities = array_intersect($data['Commerce'],$data['Humanities']);
				$finalInfo['CH'] = array();
				if(!empty($commonElemetBetweenCommerceAndHUmanities)){
						$finalInfo['CH'] = array_diff($commonElemetBetweenCommerceAndHUmanities,$finalInfo['SCH']);
				}
				$finalInfo['H'] = array_diff($data['Humanities'],array_unique($finalInfo['SCH']+$finalInfo['SH']+$finalInfo['CH']));
		}
		
		$expressInterest = '';
		if($expressInterestFirstName!='' && $expressInterestSecondName!=''){
				$expressInterest = $expressInterestFirstName.', '.$expressInterestSecondName;
		}
		
		if($expressInterestFirstName!='' && $expressInterestSecondName==''){
				$expressInterest = $expressInterestFirstName;
		}
		
		if($expressInterestFirstName=='' && $expressInterestSecondName!=''){
				$expressInterest = $expressInterestSecondName;
		}
		
		$scieneCommerceHumaitiesArr=array();$scieneCommerceArr=array();$scieneHumaitiesArr=array();$commerceHumaitiesArr=array();$scieneORCommerceORHumaitiesArr=array();
		foreach($finalInfo as $key=>$value){
			if($key=='SCH'){
				foreach($value as $k=>$v){
					$scieneCommerceHumaitiesArr[$v] = $this->_streamAny.'#'.$expressInterest;
				}
			}
			 
			if($stream=='Science'){
				if($key=='SC'){
						foreach($value as $k=>$v){
							$scieneCommerceArr[$v] = $this->_streamScienceCommerce.'#'.$expressInterest;
						}
				}
				if($key=='SH'){
						foreach($value as $k=>$v){
							$scieneHumaitiesArr[$v] = $this->_streamScienceHumanities.'#'.$expressInterest;
						}
				}
				if($key=='S'){
						foreach($value as $k=>$v){
							$scieneORCommerceORHumaitiesArr[$v] = $this->_streamScience.'#'.$expressInterest;
						}
				}
			} 
			if($stream=='Commerce'){ 
				if($key=='SC'){
						foreach($value as $k=>$v){
						        $scieneCommerceArr[$v] = $this->_streamScienceCommerce.'#'.$expressInterest;
						}
				}
				if($key=='CH'){
						foreach($value as $k=>$v){
							$commerceHumaitiesArr[$v] = $this->_streamCommerceHUmanities.'#'.$expressInterest;
						}
				}
				if($key=='C'){
						foreach($value as $k=>$v){
							$scieneORCommerceORHumaitiesArr[$v] = $this->_streamCommerce.'#'.$expressInterest;
						}
				}
			}
			
			if($stream=='Humanities'){
				if($key=='SH'){
						foreach($value as $k=>$v){
							$scieneHumaitiesArr[$v] = $this->_streamScienceHumanities.'#'.$expressInterest;
						}
				}
				if($key=='CH'){
						foreach($value as $k=>$v){
							$commerceHumaitiesArr[$v] = $this->_streamCommerceHUmanities.'#'.$expressInterest;
						}
				}
				if($key=='H'){
						foreach($value as $k=>$v){
							$scieneORCommerceORHumaitiesArr[$v] = $this->_streamHumanities.'#'.$expressInterest;
						}
				}
			}
		}
		if($stream=='Science'){
				$finalArr = $scieneORCommerceORHumaitiesArr+$scieneCommerceArr+$scieneHumaitiesArr+$scieneCommerceHumaitiesArr;
		}
		if($stream=='Commerce'){
				$finalArr = $scieneORCommerceORHumaitiesArr+$scieneCommerceArr+$commerceHumaitiesArr+$scieneCommerceHumaitiesArr;
		}
		if($stream=='Humanities'){
				$finalArr = $scieneORCommerceORHumaitiesArr+$scieneHumaitiesArr+$commerceHumaitiesArr+$scieneCommerceHumaitiesArr;
		}
		return $finalArr;
}

	function prepareBeaconTrackData($beaconData,$careerId){
		$hierarchy = array();
		$baseCourseIds = array();

		if(is_array($beaconData) && count($beaconData) > 0){
			foreach ($beaconData as $key => $value) {
				$hierarchy[] = array(
					'streamId' =>			$value['stream_id'],
					'substreamId'=> 		$value['substream_id'],
					'specializationId'=>	$value['specialization_id'],
					);
				if($value['base_course_id'] != 0){
					$baseCourseIds[] = $value['base_course_id'];	
				}
			}	
		}

		$beaconTrackData = array(
			'pageIdentifier' => 'careerDetailPage',
			'pageEntityId'   => $careerId,
			'extraData' => array(				
					'countryId' => 2
				)
		);
		if(is_array($hierarchy) && count($hierarchy) > 0){
			$beaconTrackData['extraData']['hierarchy'] = $hierarchy;
		}
		if(!empty($baseCourseIds)){
			$beaconTrackData['extraData']['baseCourseId'] = $baseCourseIds;
		}
		return $beaconTrackData;
	}

	function getGTMArray($beaconTrackData){
		foreach ($beaconTrackData['extraData']['hierarchy'] as $key => $value) {
			if($value['streamId'] != ''){
				$stream[] = $value['streamId'];	
			}
			if($value['substreamId'] != ''){
				$substream[] = $value['substreamId'];	
			}
			if($value['specializationId'] != ''){
				$specialization[] = $value['specializationId'];	
			}
		}
		$gtmArray = array(
		    "pageType" => $beaconTrackData['pageIdentifier'],
		 	"stream"=>implode(',',array_unique($stream)),
	 		"substream"=>implode(',',array_unique($substream)),
	 		"specialization"=>implode(',',array_unique($specialization)),
		 	"baseCourseId"=>$beaconTrackData['extraData']['baseCourseId'],
		);
        if($userStatus!='false' && $userStatus[0]['experience']!==""){
            $gtmArray['workExperience'] = $userStatus[0]['experience'];
	    }

	    return $gtmArray;
	}

}
?>

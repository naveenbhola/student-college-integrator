<?php
/*

   Copyright 2013 Info Edge India Ltd

   $Author: Pranjul

   $Id: CAUtilityLib.php

 */
class CAUtilityLib
{
		/**
		* default Constructor
		*  @name: init
		*  @description: In this constructor we are loading ListingBuilder.
		*  @param string $userInput: no paramaters
		*/
		function __construct()
		{
				$this->CI = & get_instance();
				$this->CI->load->builder("nationalCourse/CourseBuilder");
				$courseBuilder = new CourseBuilder();
				$this->courseRepository = $courseBuilder->getCourseRepository();
		}		
		/**
		*  @name: formatCAData
		*  @description: Formating Data of Campus Ambassadors.
		*  @param string $userInput: Campus Ambassadors Information
		*/
		function formatCAData($resultCA){
				$tmpArr = array();
				foreach($resultCA as $key=>$value){
						if(array_key_exists('ca',$value)){
								if($value['ca']['officialInstituteLocId']!=0 && $value['ca']['officialInstituteId']!=0){
										$res = $this->courseRepository->find($value['ca']['officialCourseId'], array('location'));
										if(is_object($res) && $res->getId()>0){
										$resultCA[$key]['ca']['officailInsName'] = $res->getInstituteName();
										$resultCA[$key]['ca']['officailCourseName'] = $res->getName();
										$allLocations = $res->getLocations();

										if(is_object($allLocations)){
												$resultCA[$key]['ca']['officailLocName'] = $allLocations[$value['ca']['officialInstituteLocId']]->getLocalityName();
												$resultCA[$key]['ca']['officailCityName'] = $allLocations[$value['ca']['officialInstituteLocId']]->getCityName();
												$resultCA[$key]['ca']['officailStateName'] = $allLocations[$value['ca']['officialInstituteLocId']]->getStateName();
												$resultCA[$key]['ca']['officailCountryName'] = 'India';
										}
										}
										$tmpArr[] = $value['ca']['officialInstituteId'];
								}
								if(array_key_exists('mainEducationDetails',$value['ca'])){
										
										foreach($value['ca']['mainEducationDetails'] as $k=>$v){
												$res1 = $this->courseRepository->find($v['courseId'], array('location'));
												if(is_object($res1) && $res1->getId()>0){
												$resultCA[$key]['ca']['mainEducationDetails'][$k]['insName'] = $res1->getInstituteName();
												$resultCA[$key]['ca']['mainEducationDetails'][$k]['courseName'] = $res1->getName();
												$allLocations1 = $res1->getLocations();
											if(is_object($allLocations1)){
												$resultCA[$key]['ca']['mainEducationDetails'][$k]['locName'] = ($allLocations1[$v['locationId']])?$allLocations1[$v['locationId']]->getLocalityName():'';
												$resultCA[$key]['ca']['mainEducationDetails'][$k]['cityName'] = ($allLocations1[$v['locationId']])?$allLocations1[$v['locationId']]->getCityName():'';
												$resultCA[$key]['ca']['mainEducationDetails'][$k]['stateName'] = ($allLocations1[$v['locationId']])?$allLocations1[$v['locationId']]->getStateName():'';
											$resultCA[$key]['ca']['mainEducationDetails'][$k]['countryName'] = ($allLocations1[$v['locationId']])? 'India' :'';
											}

											if(array_key_exists($v['locationId'], $allLocations1)){
												$resultCA[$key]['ca']['mainEducationDetails'][$k]['stateId'] = ($allLocations1[$v['locationId']])?$allLocations1[$v['locationId']]->getStateId():'';

												$resultCA[$key]['ca']['mainEducationDetails'][$k]['cityId'] = ($allLocations1[$v['locationId']])?$allLocations1[$v['locationId']]->getCityId():'';

												$resultCA[$key]['ca']['mainEducationDetails'][$k]['localityId'] = ($allLocations1[$v['locationId']])?$allLocations1[$v['locationId']]->getLocalityId():'';

											}
												}
												$tmpArr[] = $v['instituteId'];
										}
										$resultCA[$key]['ca']['insIds'] = implode(',',array_unique($tmpArr));
												
								}
								
						}
				}
				return $resultCA;
		}
		/**
		*  @name: formatCADataByIns
		*  @description: Formating Data of Campus Ambassadors by Institute Name.
		*  @param string $userInput: Campus Ambassadors Information
		*/
		function formatCADataByIns($result,$instituteId){ 
				$finalArr = array();
				foreach($result as $key=>$value){
						if(array_key_exists('ca',$value)){
								$valueArr = explode(', ', $value['ca']['insIds']);
								if(!in_array($instituteId,$valueArr)){
									countinue;	
								}else{
									$finalArr[] = 	$value;			
								}
						}
				}
				return $finalArr;
		}
		/**
		*  @name: getCoursesOfSubcategory
		*  @description: Find Courses of a given Subcategory.
		*  @param string $userInput: $currentCourseSubCatId,$allCourseWithSubCat
		*/
		function getCoursesOfSubcategory($currentCourseSubCatId,$allCourseWithSubCat){
				$courseArr = array();
				foreach($allCourseWithSubCat as $courseId=>$subCat){
						foreach($subCat as $key=>$value){
								for($i=0;$i<count($currentCourseSubCatId);$i++){
										if($currentCourseSubCatId[$i]==$value){
												$courseArr[] = 	$courseId;		
										}
								}
						}
				}
				return $courseArr;
		}


		
		/**
		* Function to get the List of institutes to generate the Institute 3-grams Maps
		* @param array $instituteData Array having list of institutes
		*/

		function generate_institute_map($instituteData) {

			foreach ($instituteData as $row) {
				$institute_map[$row['institute_id']] =  $this->clean_string($row['institute_name']);

				$original_institute_map[$row['institute_id']] =  $this->clean_string($row['institute_name'],true);
			}
			$newArray = array();
			array_push($newArray, $institute_map);
			array_push($newArray, $original_institute_map);
			return $newArray;
		}

		/**
		* Function to get the sanitize the string for similarity checking
		*
		* @param string $str String to be sanitized
		* @param boolean $less Whether to remove speical chars and lowercase the string
		*/
		
		function clean_string($str = "",$less = false){

	  		$clean_str = $str;
			if($less == false){
		    	$clean_str = strtolower($str);	
	    		$clean_str = preg_replace('/[^A-Za-z0-9 ]/', '', $clean_str);
			}
			$wordsArr = explode(" ", $clean_str);
			$clean_str = implode(" ", $wordsArr);

			return $clean_str;
		}

		/**
		* Function to get the N-Grans for a passed string
		*
		* @param string $string String for which N-Grams need to be generated
		* @param integer $ngramLen Length of n-gram needed(Default 3)
		*/
		function generate_ngrams($string = "",$ngramLen = 3){
			$ngrams = array();
			$len = strlen($string);
			$finalStr = "";
			for($i=0;$i<$len-$ngramLen+1;$i++){
				$temp = substr($string, $i,$ngramLen);
				$finalStr .= $temp.",";
			}
			if($finalStr != ""){
				$finalStr = substr($finalStr, 0,-1);
			}
			return $finalStr;
		}

		/**
		* Function to get the N-Grans for the list of institutes
		* @param array $institute_map List of institutes
		*/
		function generate_ngrams_map($institute_map){
			$ngrams_map = array();
			foreach ($institute_map as $institute_id => $institute_name) {
				$tempArray = $this->generate_ngrams($institute_name);
				$ngrams_map[$institute_id] = $tempArray;
			}
			return $ngrams_map;
		}

		/**
		 * Function to get Similarity Map
		 *
		 * @param array $ngrams_map Institute N-grams map
		 * @param string $search 
		 */
		function matchData($ngrams_map,$search){
			$result = array();
			$searchNgrams = $this->generate_ngrams($this->clean_string($search));
			foreach ($ngrams_map as $institute_id => $institute_map_str) {
				$result[$institute_id] = $this->getJaccardSimilarityPercent($institute_map_str,$searchNgrams);
			}
			arsort($result);
			$result = array_slice($result, 0,3,true);
			return $result;	
		}

		/**
		 * Function to get the Jaccard Similarity Cofficient between two instituites
		 *
		 * @param string $institute_ngram String having n-grams for institute
		 * @param string $institute_ngram String having n-grams for un-mapped institute
		 * @param string $separator 
		 */
		function getJaccardSimilarityPercent( $institute_ngram, $search_string_ngrams, $separator = "," ) {

			$institute_ngram = explode( $separator, $institute_ngram );
			$search_string_ngrams = explode( $separator, $search_string_ngrams );
			$a1 = array_unique($institute_ngram);
			$a2 = array_unique($search_string_ngrams);
			$arr_intersection = array_intersect( $a1, $a2 );

			//$arr_union = (array_merge( $a1, $a2 ));
			$institute_ngram_arr = implode(",", $a1);
			$search_string_ngrams_arr = implode(",", $a2);
			$arr_union = $institute_ngram_arr.",".$search_string_ngrams_arr;
			$arr_union = explode(",", $arr_union);

			$count_intersect = count( $arr_intersection ) ;
			
			$percent = round($count_intersect / (count( $arr_union ) - $count_intersect) * 100);
			return $percent;
		}
		
		function mergeMentorQnaData($caData, $mentorQna)
		{
			$merge = $caData;
			foreach($caData as $key=>$value)
			{
				if(is_array($value['ca']))
				{
					$merge[$key]['mentorQna'] = $mentorQna[$value['ca']['userId']];
				}
			}
			return $merge;
		}

		// College review tracking code 
 		public function trackCollegeReview($CRId, $action , $data, $addedBy){ 
 	        if(empty($CRId) || $CRId <=0 || empty($action) || empty($data)){ 
 	                return false; 
 	        } 
 	        if($action == "reviewAdded" || $action == "reviewEdited"){ 
 	                $finalData['reviewTitle'] = $data['reviewTitle']; 
 	                $finalData['placementDescription'] = $data['placementDescription']; 
 	                $finalData['infraDescription'] = $data['infraDescription']; 
 	                $finalData['facultyDescription'] = $data['facultyDescription']; 
 	                $finalData['reviewDescription'] = $data['reviewDescription']; 
 	                if($data['isShikshaInst']=='YES'){ 
 	                        $finalData['instituteId'] = $data['suggested_institutes']; 
 	                        $finalData['locationId']  = $data['location']; 
 	                        $finalData['courseId']    = $data['course']; 
 	                }else{ 
 	                        $finalData['instituteName'] = $data['suggested_institutes']; 
 	                        $finalData['locationName']  = $data['location']; 
 	                        $finalData['courseName']    = $data['course']; 
 	                } 
 	                $finalData['anonymousFlag'] = $data['anonymous']; 
 	                $data = json_encode($finalData); 
 	        }else if($action == "statusUpdated"){ 
 	                $data = json_encode($data); 
 	        }else if($action == "courseDetailsUpdated"){ 
 	                $data = json_encode($data); 
 	        }else if($action == 'autoModerated'){
             	    $data = json_encode($data);
             }
 	 
 	        $crTrackingData = array( 
 	                "reviewId" => $CRId, 
 	                "addedBy" => $addedBy, 
 	                "action" => $action, 
 	                "data" => $data 
 	                ); 
 	         
 	        $caModel = $this->CI->load->model('CAEnterprise/reviewenterprisemodel'); 
 	        $caModel->trackCollegeReview($crTrackingData); 
 		}

 		public function checkIfDetailsExistInDB($email,$suggested_institutes,$course,$isShikshaInst){
 			$collegeReviewModel = $this->CI->load->model('CollegeReviewForm/collegereviewmodel'); 
 	        $result = $collegeReviewModel->checkIfDetailsExistInDB($email,$suggested_institutes,$course,$isShikshaInst);
 	        $collegeReviewDetails = array();
 	        //_p($result);
 	        
        	foreach ($result as $collegeReview) {
        		$collegeReviewDetails = array(
									'id' => $collegeReview['id'],
									'status' => $collegeReview['status']
								);
        		if($collegeReview['status'] == "published"){
        			break;
        		}
        	}
 	        
 	        return $collegeReviewDetails;
 		}
}
?>

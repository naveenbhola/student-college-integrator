<?php

class EnterpriseLib {

	private $CI;

	function __construct(){
		$this->CI = & get_instance();
	}

	function excludeLDBList($ExtraFlag, $response){


            $this->ldbmodel = $this->CI->load->model('LDB/ldbmodel');
            $userToBeExcluded = $this->ldbmodel->getExcludedList();

            if(count($userToBeExcluded) >0) {

            	$finalUsers  = array_diff($response, $userToBeExcluded);

            	foreach ($finalUsers as $users) {
            		$finalUserIds[] = $users;
            	}
            	return $finalUserIds;
            }
        return $response;
	}
      /*
       * Function to get data from customMultilocationMail table
       * @return all the rows of customMultilocationMail
       */
      function getCustomMultiplocationDetails(){
            $matchingcriteriamodel = $this->CI->load->model('enterprise/matchingcriteriamodel');
            $tableData = $matchingcriteriamodel->getCustomMultiplocationDetails();
            
            $data = array();
            foreach ($tableData as $key => $value) {
                  $data[$value['courseId']][$value['city']] = $value['email'];
                  if($value['isHeadOffice'] == 'yes'){
                    $data[$value['courseId']]['isHeadOffice'] = $value['email'];
                  }
            }
            return $data;
      }

      /*
       * Function to get basic course details like course Name, institute name and institute locations
       * @Params: CourseIds => array containing courseIds
       * @return: array having course Name, institute name and institute locations
       */
      function getNationalCourseDetails($courseIds = array()){
            if(empty($courseIds)){
                  return array();
            }

            $this->CI->load->model('listing/coursemodel');
            $coursemodel = new coursemodel;

            $courseDetails = array();

            $courseData = $coursemodel->getNationalCourseDetails($courseIds);
            foreach($courseData as $key=>$value){
              $courseDetails[$value['courseId']]['courseTitle'] = $value['courseTitle'];
              $courseDetails[$value['courseId']]['institute_name'] = $value['institute_name'];
              $courseDetails[$value['courseId']]['city_id'][$value['city_id']] = $value['city_id'];
            }
            return $courseDetails;
      }

      function getResponseDataForCourses($courseIds = array()){
          if(empty($courseIds)){
              return array();
          }

          $this->CI->load->model('LDB/ldbmodel');
          $ldbmodel = new ldbmodel;

          $date = date('Y-m-d');
          $from = $date.' '.(date('H')-1).':00:00';
          $to = $date.' '.(date('H')).':00:00';

          $count = $ldbmodel->getResponseCountForCoursesInTimeRange($courseIds, $from, $to);
          $count = $count[0]['count'];
          $index = 0;
          $userDetails = array();
          while($index < $count){
            $tempData = $ldbmodel->getResponseDataForCourses($courseIds, $from, $to, $index, 500);
            foreach ($tempData as $key => $value) {
              $temp['UserId']=$value['UserId'];
              $temp['action']=$value['action'];
              $userDetails[$value['courseId']][] = $temp;
            }
            $index += 500;
          }
          return $userDetails;
      }

      /*
       * Function to get the user basic details like name, mobile, email, grad year and XII year
       *
       */
      function getUserDataFromUserId($userIds = array()){
        if(empty($userIds)){
          return array();
        }

        $this->CI->load->model('user/usermodel');
        $usermodel = new usermodel;

        $userData = array();
        foreach ($userIds as $key => $value) {
          $user = $usermodel->getUserById($value);
          if($user->getFlags()->getIsTestUser() == 'YES' || $user->getFlags()->getMobileVerified() == 0){
              continue;
          }

          $userData[$value]['Name'] = $user->getFirstName().' '.$user->getLastName();
          $userData[$value]['Email'] = $user->getEmail();
          $userData[$value]['ISDCode'] = $user->getISDCode();
          $userData[$value]['Mobile'] = $user->getMobile();
          $userData[$value]['City'] = $user->getCity();
          $userData[$value]['Locality'] = $user->getLocality() > 0 ? $user->getLocality() : '';
          $userData[$value]['NDNC'] = $user->getFlags()->getIsNDNC();

          $education = $user->getEducation();
          if(is_object($education)){
            foreach ($education as $key => $values) {
              if($values->getLevel() == '12'){
                $userData[$value]['XII'] = $values->getCourseCompletionDate()->format('Y') != "-0001" ? $values->getCourseCompletionDate()->format('Y'): '';
              }else if($values->getLevel() == 'UG'){
                $userData[$value]['Graduation'] = $values->getCourseCompletionDate()->format('Y') != "-0001" ? $values->getCourseCompletionDate()->format('Y'): '';
              }
            }
          }

        }
        return $userData;
      }

      function getCityNamesFromCityIds($cityIds = array()){
        if(empty($cityIds)){
          return array();
        }

        $this->CI->load->model('LDB/ldbmodel');
        $ldbmodel = new ldbmodel;

        $returnData = array();
        $cityData = $ldbmodel->getCityNamesFromCityIds($cityIds);
        foreach ($cityData as $key => $value) {
          $returnData[$value['CityId']] = $value['CityName'];
        }

        return $returnData;
      }

      function getLocalitiesNamesFromLocalityIds($localityIds = array()){
        if(empty($localityIds)){
          return array();
        }

        $this->CI->load->model('LDB/ldbmodel');
        $ldbmodel = new ldbmodel;

        $returnData = array();
        $localityData = $ldbmodel->getLocalitiesNamesFromLocalityIds($localityIds);
        foreach ($localityData as $key => $value) {
          $returnData[$value['localityId']] = $value['localityName'];
        }

        return $returnData;
      }

      public function makeCustomMultiLocationMailData($customTableData, $courseDetails, $responseData, $userData, $userCityData){
        $mailData = array();
        $cityIds = array();
        $localityIds = array();
        foreach ($customTableData as $courseId => $customData) {
            foreach($userCityData[$courseId] as $cityId=>$userData){
                if(!empty($customData[$cityId])){
                    
                    foreach($userData as $key=>$userD){
                       if(empty($userD['Email'])){
                            continue;
                        }
                        $temp = $userD;
                        $temp['courseTitle'] = $courseDetails[$courseId]['courseTitle'];
                        $temp['institute_name'] = $courseDetails[$courseId]['institute_name'];
                        $temp['responsetime'] = date('d M Y');
                        $mailData[$customData[$cityId]][] = $temp;
                       
                        $cityIds[] = $userD['City'];
                        if(!empty($userD['Locality'])){
                            $localityIds[] = $userD['Locality'];
                        }
                       
                        unset($userCityData[$courseId][$key]);
                    }
                }else{

                     foreach($userData as $key=>$userD){
                        if(empty($userD['Email'])){
                            continue;
                        }
                        $temp = $userD;
                        $temp['courseTitle'] = $courseDetails[$courseId]['courseTitle'];
                        $temp['institute_name'] = $courseDetails[$courseId]['institute_name'];
                        $temp['responsetime'] = date('d M Y');

                        $mailData[$customData['isHeadOffice']][] = $temp;
                        
                        $cityIds[] = $userD['City'];
                        if(!empty($userD['Locality'])){
                            $localityIds[] = $userD['Locality'];
                        }
                        
                        unset($userCityData[$courseId][$key]);

                    }
                }
            }
        }

        $cityNames = $this->getCityNamesFromCityIds($cityIds);
        $localityNames = $this->getLocalitiesNamesFromLocalityIds($localityIds);
        $mailData = $this->_replaceLocationIdsWithName($mailData, $cityNames, $localityNames);

        return $mailData;
      }

      private function _replaceLocationIdsWithName($mailData, $cityNames, $localityNames){
          foreach($mailData as $email=>$col){
            foreach ($col as $key => $data) {
               $mailData[$email][$key]['City'] = $cityNames[$data['City']];
               $mailData[$email][$key]['Locality'] = $localityNames[$data['Locality']];
            }

          }
          return $mailData;
      }

      function formatCustomLocationMailData($mailData){ 
         $returnData = array();
          
         $csvKeyPairs = array('Name'=>'Name', 'institute_name'=>'Institute Name', 'courseTitle'=>'Response to', 'responsetime'=>'Response Date', 'action'=>'Source', 'Email'=>'Email', 'ISDCode'=>'ISD Code', 'Mobile'=>'Mobile', 'City'=>'Current Location', 'Locality'=>'Current Locality', 'NDNC'=>'Is in NDNC List', 'XII'=>'XII Year', 'Graduation'=>'Graduation Year');
         $returnData[] = array_values($csvKeyPairs);
         
         foreach($mailData as $k=>$data){
            $temp = array();
            foreach ($csvKeyPairs as $key => $value) {
              $temp[] = $mailData[$k][$key];
            }
            $returnData[] = $temp;
         }
         return $returnData;
      }
}
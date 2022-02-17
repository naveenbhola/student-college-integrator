<?php

class ListingBulkUploadCreationLib
{
        var $issueFound = false;

	var $enumFields = array(
				'instituteType' => array('college','school','faculty','department','centre'),
				'ownership' => array('public/government','private','public-private'),
				'studentType' => array('co-ed','girls only','boys only'),
				'naac' => array('a','b','c','d','a+','b+','a++','b++'),
				'isAutonomous' => array('yes','no'),
				'hostel' => array('yes','no'),
				'boysHostel' => array('yes','no'),
				'girlsHostel' => array('yes','no'),
				'coedHostel' => array('yes','no'),
				'library' => array('yes','no'),
				'labs' => array('yes','no'),
				'sportsComplex' => array('yes','no'),
				'auditorium' => array('yes','no'),
				'gym' => array('yes','no'),
				'hospital' => array('yes','no'),
				'cafeteria' => array('yes','no'),
				'wifi' => array('yes','no'),
				'courseVariant' => array('single','double'),
				'entryCred' => array('degree','diploma','certificate'),
				'entryLevel' => array('after 10th','ug','pg','advanced masters','doctorate','post doctorate'),
				'exitCredential' => array('degree','diploma','certificate'),
				'exitCourseLevel' => array('after 10th','ug','pg','advanced masters','doctorate','post doctorate'),
				'educationType' => array('full time','part time'),
				'deliveryMethod' => array('classroom','distance/correspondence','on the job (apprenticeship)','virtual classroom','blend','online'),
				'durationUnit' => array('years','months','weeks','days','hours'),
				'approvingBodies' => array('aicte','bci','cch','ccim','coa','dci','deb-ugc','icar','inc','mci','nba','ncri','ncte','pci','rci','siro','vci'),
				'affiliatingBody' => array('domestic','study abroad'),
				'batchYear' => array(2018,2019,2020)
			);

	var $validationFields = array(
				'instituteName' => array('min'=>'0',max=>'200',type=>'string'),
				'address' => array('min'=>'30',max=>'150',type=>'string'),
				'contactGeneric' => array('min'=>'10',max=>'20',type=>'integer'),
				'contactAdmission' => array('min'=>'10',max=>'20',type=>'integer'),
				'boysNoOfBeds' => array('min'=>'1',max=>'6',type=>'integer'),
				'girlsNoOfBeds' => array('min'=>'1',max=>'6',type=>'integer'),
				'coedNoOfBeds' => array('min'=>'1',max=>'6',type=>'integer'),
				'hostelHighlights' => array('min'=>'200',max=>'2000',type=>'string'),
				'libraryHighlights' => array('min'=>'200',max=>'2000',type=>'string'),
				'labHighlights' => array('min'=>'200',max=>'2000',type=>'string'),
				'hostelHighlights' => array('min'=>'200',max=>'2000',type=>'string'),
				'sportsHighlights' => array('min'=>'200',max=>'2000',type=>'string'),
				'gymHighlights' => array('min'=>'200',max=>'2000',type=>'string'),
				'cafeteriaHighlights' => array('min'=>'200',max=>'2000',type=>'string'),
				'courseName' => array('min'=>'0',max=>'200',type=>'string'),
				'duration' => array('min'=>'0',max=>'10',type=>'integer'),
				'totalSeats' => array('min'=>'0',max=>'10',type=>'integer')
			);

        function __construct() {
		$this->CI                    = & get_instance();
                $this->model = $this->CI->load->model('common/listingbulkupdatecreationmodel');
        }

	public function checkMandatory($fieldValue, $type){
		if($fieldValue == ""){
			echo "- $type is Blank. It needs to have a value<br/>";
			$this->issueFound = true;
		}
	}

	public function checkEnumValue($fieldValue, $type){

		$valueArray = $this->enumFields[$type];
		if($this->isBlank($fieldValue)){
			return true;
		}

		if(!in_array(strtolower($fieldValue), $valueArray)){
			echo "- $type has a value other than allowed.<br/>";
			$this->issueFound = true;
		}
	}

	public function checkValidations($fieldValue, $type){
		if($this->isBlank($fieldValue)){
			return true;
		}
		$valueArray = $this->validationFields[$type];
		if($valueArray['type'] == "integer"){
			$this->validateInteger($fieldValue, $type);
		}
                if(strlen($fieldValue) > intval($valueArray['max'])) {
			echo "- $type can only have ".$valueArray['max']." characters.<br/>";
			$this->issueFound = true;
                } else if(strlen($fieldValue) < intval($valueArray['min'])){
			echo "- $type requires atleast ".$valueArray['min']." characters.<br/>";
			$this->issueFound = true;
                }
	}

        public function validateInteger($number, $type) {
            if (!preg_match('/^[0-9]*$/', $number)) {
		echo "- $type is not Integer. Only Integers are allowed in this field<br/>";
		$this->issueFound = true;
            }
    	}

	public function checkEmail($fieldValue){
		if($this->isBlank($fieldValue)){
			return true;
		}
	        $res = preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $fieldValue);
        	if(!$res){
			echo "- Email field is not a valid Email<br/>";
			$this->issueFound = true;
        	}
	}

	public function checkEstablishment($fieldValue){
		if($this->isBlank($fieldValue)){
			return true;
		}
		$this->validateInteger($fieldValue, 'YearOfEstablishment');
		if($fieldValue < 1500 | $fieldValue > 2019){
			echo "- Year of Establishment field does not have correct value.<br/>";
			$this->issueFound = true;
		}
	}

	public function checkLink($fieldValue){
		if($this->isBlank($fieldValue)){
			return true;
		}
		if(strpos($fieldValue, '.') === false){
			echo "- College website does not have correct value.<br/>";
			$this->issueFound = true;
		}
	}

	public function checkCity($fieldValue){
		if($this->isBlank($fieldValue)){
			return true;
		}
		$res = $this->model->checkValidCity($fieldValue);
		if(is_array($res) && count($res) == 0){
			echo "- cityId is not a valid city.<br/>";
			$this->issueFound = true;
		}
	}

	public function checkInstituteIdType($fieldValue, $insituteType){
		if($this->isBlank($fieldValue)){
			return true;
		}
		$res = $this->model->checkValidInstitute($fieldValue);
		if(is_array($res) && count($res) == 0){
			echo "- InstituteId is not a valid institute in Shiksha DB.<br/>";
			$this->issueFound = true;
		}
		else if($res['type'] == $insituteType){
			echo "- InstituteType cannot be same as Parent Institute type.<br/>";
			$this->issueFound = true;
		}
	}

	public function checkLatLong($fieldValue, $field){
		if($this->isBlank($fieldValue)){
			return true;
		}
		if(!is_numeric($fieldValue)){
			echo "- $field does not have correct value.<br/>";
			$this->issueFound = true;
		}

                if(strlen($fieldValue) > 10){
			echo "- $field can only have max 10 characters.<br/>";
			$this->issueFound = true;
                }
	}

	public function checkPhoneNumber($fieldValue, $field){
		if($this->isBlank($fieldValue)){
			return true;
		}
		if (!preg_match("/^[0-9-]+$/", $fieldValue)) {
			echo "- $field can only have numbers or hyphen.<br/>";
			$this->issueFound = true;
		}
                if(strlen($fieldValue) > 20){
			echo "- $field can only have max 20 characters.<br/>";
			$this->issueFound = true;
                }
	}

	public function isBlank($fieldValue){
		if($fieldValue == '' && $fieldValue != '0'){
			return true;
		}
		return false;
	}

	public function checkEntryLevel($level,$credential){
		if($this->isBlank($fieldValue)){
			return true;
		}
		if(strtolower($credential) != 'certificate'){
			if($level == ''){
				echo "- Entry course Level is Blank. It needs to have a value<br/>";
				$this->issueFound = true;
			}
		}
	}

	public function checkDeliveryMethod($educationType,$deliveryMethod){
		if(strtolower($educationType) != "part time"){
			if($deliveryMethod != ''){
				echo "- Education type is not part time. For this, delivery method should be blank<br/>";
				$this->issueFound = true;
			}
		}
	}

    public function checkCourseId($fieldValue){
        if($this->isBlank($fieldValue)){
                return true;
        }
        $res = $this->model->checkValidCourse($fieldValue);
        if(is_array($res) && count($res) == 0){
                echo "- CourseId is not a valid course in Shiksha DB.<br/>";
                $this->issueFound = true;
        }
    }

	public function checkInstituteId($fieldValue){
		if($this->isBlank($fieldValue)){
			return true;
		}
		$res = $this->model->checkValidInstitute($fieldValue);
		if(is_array($res) && count($res) == 0){
			echo "- ParentId is not a valid institute in Shiksha DB.<br/>";
			$this->issueFound = true;
		}
	}

	public function checkUniversityId($fieldValue){
		if($this->isBlank($fieldValue)){
			return true;
		}
		$res = $this->model->checkValidUniversity($fieldValue);
		if(is_array($res) && count($res) == 0){
			echo "- Affiliating University is not a valid university in Shiksha DB.<br/>";
			$this->issueFound = true;
		}
	}

	public function checkStream($fieldValue, $field){
		if($this->isBlank($fieldValue)){
			return true;
		}
		$res = $this->model->checkStream($fieldValue);
		if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
			echo "- $field does not contain valid Streams.<br/>";
			$this->issueFound = true;
		}
	}

	public function checkSubStream($fieldValue, $field, $streamId){
		if($this->isBlank($fieldValue)){
			return true;
		}
		$res = $this->model->checkSubStream($fieldValue);
		if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
			echo "- $field does not contain valid Sub Streams.<br/>";
			$this->issueFound = true;
		}

		$res = $this->model->checkSubStreamMapping($fieldValue, $streamId);
		if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
			echo "- $field does not Map with Streams.<br/>";
			$this->issueFound = true;
		}

	}

	public function checkSpecialization($fieldValue, $field, $substreamId, $streamId){
		if($this->isBlank($fieldValue)){
			return true;
		}
		$res = $this->model->checkSpecialization($fieldValue);
		if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
			echo "- $field does not contain valid Specialization.<br/>";
			$this->issueFound = true;
		}

		if($substream == ''){
			$res = $this->model->checkSpecializationStreamMapping($fieldValue, $streamId);
			if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
				echo "- $field does not Map with Streams.<br/>";
				$this->issueFound = true;
			}
		}
		else{
			$res = $this->model->checkSpecializationSubStreamMapping($fieldValue, $substreamId);
			if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
				echo "- $field does not Map with Sub Streams.<br/>";
				$this->issueFound = true;
			}
		}
	}

	public function checkBaseCourse($fieldValue, $field, $streamId = 0){
		if($this->isBlank($fieldValue)){
			return true;
		}
		$res = $this->model->checkBaseCourse($fieldValue);
		if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
			echo "- $field does not contain valid Base Course.<br/>";
			$this->issueFound = true;
		}

		if($streamId > 0){
			$res = $this->model->checkBCStreamMapping($fieldValue, $streamId);
			if(is_array($res) && count($res) < count(explode(',',$fieldValue))){
				echo "- $field does not Map with Streams.<br/>";
				$this->issueFound = true;
			}
		}

	}

	public function checkBatch($data){
		if(!($data >= 1 && $data <= 100 )){
			echo "- Percentage of Batch placed is not correct.<br/>";
			$this->issueFound = true;
		}
	}

	public function checkSalary($min, $mid, $max, $type){
		if($min > 0 && $mid > 0 && $min > $mid){
			echo "- $type is less than Minimum Salary<br/>";
			$this->issueFound = true;
		}
		if($max > 0 && $mid > 0 && $max < $mid){
			echo "- $type is more than Maximum Salary<br/>";
			$this->issueFound = true;
		}
	}

}
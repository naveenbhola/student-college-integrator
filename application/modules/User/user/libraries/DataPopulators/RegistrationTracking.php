<?php
/**
 * File for Populator class for RegistrationTracking entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for RegistrationTracking entity
 */ 
class RegistrationTracking extends AbstractPopulator
{
    /**
     * Constructor
     *
     * @param string $mode create|update
     */
    function __construct($mode = 'create')
    {
        parent::__construct($mode);
    }
    
    /**
     * Populate data into RegistrationTracking entity
     *
     * @param object $registrationTracking \user\Entities\RegistrationTracking
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\RegistrationTracking $registrationTracking,$data = array())
    {
        $this->setData($data);
        if(!empty($registrationTracking) && gettype($registrationTracking) == 'object') {

    	    if(!empty($data['desiredCourse'])){ 
                if($data['isTestPrep'] != 'yes'){
                    $registrationTracking->setDesiredCourse($data['desiredCourse']);
                }else{
                    $registrationTracking->setDesiredCourse(0);
                    $registrationTracking->setBlogId($data['desiredCourse']);
                }
            }else if(!empty($data['fieldOfInterest']) && $data['isStudyAbroad'] == 'yes') {
                $data['desiredCourse'] = $this->_getDesiredCourseForStudyAbroad($data);
                $registrationTracking->setDesiredCourse($data['desiredCourse']);
            }

            if(!empty($data['fieldOfInterest'])){ 
                $registrationTracking->setCategoryId($data['fieldOfInterest']);
            }

            if(!empty($data['subCatId']) || $data['subCatId'] ==0){ 
                $registrationTracking->setSubCatId($data['subCatId']);
            }

            if(!empty($data['residenceCity'])){ 
                $registrationTracking->setCity($data['residenceCity']);
            }

            if(!empty($data['residenceCityLocality'])){ 
                $registrationTracking->setCity($data['residenceCityLocality']);
            }

            if(!empty($data['isdCode'])){ 
                $isdData = $data['isdCode'];
                $countryID = explode('-', $isdData);
                
                $registrationTracking->setCountry($countryID[1]);
            }

            if(!empty($data['destinationCountry'][0])){ 
                $registrationTracking->setPrefCountry1($data['destinationCountry'][0]);
            }

            if(!empty($data['destinationCountry'][1])){ 
                $registrationTracking->setPrefCountry2($data['destinationCountry'][1]);
            }

            if(!empty($data['destinationCountry'][2])){ 
                $registrationTracking->setPrefCountry3($data['destinationCountry'][2]);
            }

            if(!empty($data['source'])){
                $registrationTracking->setSource($data['source']);
            }

            $registrationTracking->setUserType($this->_getUserType($data));

            if(!empty($data['isNewReg']) && $data['isNewReg'] == 'yes'){
                $registrationTracking->setIsNewReg('yes');
            }else{
                $registrationTracking->setIsNewReg('no');
            }
            $registrationTracking->setSubmitDate(new \DateTime);
            $registrationTracking->setSubmitTime(new \DateTime);

            if(!empty($data['tracking_keyid'])){ 
                $registrationTracking->setTrackingkeyId($data['tracking_keyid']);
            }

            $registrationTracking->setVisitorSessionId(getVisitorSessionId());
            $registrationTracking->setReferer($_SERVER['HTTP_REFERER']);

            if(!empty($data['pagereferer'])){
                $registrationTracking->setPageReferer($data['pagereferer']);
            }

	   }
    }

    private function _isPaidUser($data){

       if(!empty($data['mmpFormId']) && $data['mmpFormId'] > 0){
            return 'paid';
       }

       return 'free';
    }

    private function _getUserType($data){
        if($data['isTestPrep'] == 'yes'){
            return 'testPrep';
        }else if($data['isStudyAbroad'] == 'yes'){
            return 'abroad';
        }else if($data['desiredCourse']){
            return 'national';
        }else if(empty($data['desiredCourse']) && $data['tracking_keyid'] == '697'){
            return 'APP';
        }else{
            return 'national';
        }
    }

     /**
     * Get desired course value fr study abroad
     * It's calculated by a combination of field of interest (category) and desired graduation level (UG|PG)
     *
     * @return integer
     */ 
     private function _getDesiredCourseForStudyAbroad($data)
     {
        $categoryId = $data['fieldOfInterest'];
        $desiredGraduationLevel = $data['desiredGraduationLevel'];
        
        // STUDY_ABROAD_NEW_REGISTRATION;
        if(STUDY_ABROAD_NEW_REGISTRATION) {
            $sql =  "SELECT SpecializationId ".
            "FROM tCourseSpecializationMapping WHERE 1 ".
            "AND CategoryId = ? ".
            "AND CourseName = ?";

            $query = $this->dbHandle->query($sql,array($categoryId,$desiredGraduationLevel));
        }
        else {
            $sql =  "SELECT SpecializationId ".
            "FROM tCourseSpecializationMapping WHERE scope = 'abroad' ".
            "AND CategoryId = ? ".
            "AND CourseLevel1 = ? ".
            "AND CourseName = ?";

            $query = $this->dbHandle->query($sql,array($categoryId,$desiredGraduationLevel,$desiredGraduationLevel));
        }
        
        
        $result = $query->row_array();
        return $result['SpecializationId'];
    }
}

<?php
/**
 * File for Populator class for MISTracking entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for MISTracking entity
 */ 
class MISTracking extends AbstractPopulator
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
     * Populate data into MISTracking entity
     *
     * @param object $MISTracking \user\Entities\MISTracking
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\MISTracking $MISTracking,$data = array())
    {
        $this->setData($data);
        if(!empty($MISTracking) && gettype($MISTracking) == 'object') {

    	    if(!empty($data['desiredCourse'])){ 
                if($data['isTestPrep'] != 'yes'){
                    $MISTracking->setDesiredCourse($data['desiredCourse']);
                }else{
                    $MISTracking->setDesiredCourse(0);
                    $MISTracking->setBlogId($data['desiredCourse']);
                }
            }else if(!empty($data['fieldOfInterest']) && $data['isStudyAbroad'] == 'yes') {
                $data['desiredCourse'] = $this->_getDesiredCourseForStudyAbroad($data);
                $MISTracking->setDesiredCourse($data['desiredCourse']);
            }

            if(!empty($data['fieldOfInterest'])){ 
                $MISTracking->setCategoryId($data['fieldOfInterest']);
            }

            if(!empty($data['subCatId']) || $data['subCatId'] ==0){ 
                $MISTracking->setSubCatId($data['subCatId']);
            }

            if(!empty($data['residenceCity'])){ 
                $MISTracking->setCity($data['residenceCity']);
            }

            if(!empty($data['residenceCityLocality'])){ 
                $MISTracking->setCity($data['residenceCityLocality']);
            }

            if(!empty($data['isdCode'])){ 
                $isdData = $data['isdCode'];
                $countryID = explode('-', $isdData);
                
                $MISTracking->setCountry($countryID[1]);
            }

            if(!empty($data['destinationCountry'][0])){ 
                $MISTracking->setPrefCountry1($data['destinationCountry'][0]);
            }

            if(!empty($data['destinationCountry'][1])){ 
                $MISTracking->setPrefCountry2($data['destinationCountry'][1]);
            }

            if(!empty($data['destinationCountry'][2])){ 
                $MISTracking->setPrefCountry3($data['destinationCountry'][2]);
            }

            if(!empty($data['source'])){
                $MISTracking->setSource($data['source']);
            }

            $MISTracking->setUserType($this->_getUserType($data));

            if(!empty($data['isNewReg']) && $data['isNewReg'] == 'yes'){
                $MISTracking->setIsNewReg('yes');
            }else{
                $MISTracking->setIsNewReg('no');
            }
            $MISTracking->setSubmitDate(new \DateTime);
            $MISTracking->setSubmitTime(new \DateTime);

            if(!empty($data['tracking_keyid'])){ 
                $MISTracking->setTrackingkeyId($data['tracking_keyid']);
            }

            $MISTracking->setVisitorSessionId(getVisitorSessionId());
            $MISTracking->setReferer($_SERVER['HTTP_REFERER']);

            if(!empty($data['pagereferer'])){
                $MISTracking->setPageReferer($data['pagereferer']);
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

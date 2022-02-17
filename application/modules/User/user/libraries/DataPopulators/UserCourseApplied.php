<?php
/**
 * File for Populator class for UserCourseApplied entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserCourseApplied entity
 */ 
class UserCourseApplied extends AbstractPopulator
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
     * Populate data into UserCourseApplied entity
     *
     * @param object $specializationPref \user\Entities\UserCourseApplied
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserCourseApplied $userCourseApplied, $data = array())
    {
        $this->setData($data);
        if(isset($userCourseApplied) && gettype($userCourseApplied) == 'object') {
           if(isset($data['appliedCourseId'])){
                $userCourseApplied->setCourseId($data['appliedCourseId']);
            }
            if(isset($data['appliedCourseName'])){
                $userCourseApplied->setCourseName($data['appliedCourseName']);
            }
            if(isset($data['courseCategory'])){
                $userCourseApplied->setCourseCategory($data['courseCategory']);
            }
            if(isset($data['courseSubCategory'])){
                $userCourseApplied->setCourseSubCategory($data['courseSubCategory']);
            }
             if(isset($data['LDBCourseId'])){
                $userCourseApplied->setLDBCourseId($data['LDBCourseId']);
            }
            if(isset($data['universityName'])){
                $userCourseApplied->setUniversityName(trim($data['universityName']));
            }
            if(isset($data['scholarshipReceived'])){
                $userCourseApplied->setScholarshipReceived(trim($data['scholarshipReceived']));
            }
            if(isset($data['scholarshipDetails'])){
                $userCourseApplied->setScholarshipDetails(trim($data['scholarshipDetails']));
            }
             if(isset($data['applicationAccepted'])){
                $userCourseApplied->setApplicationAccepted($data['applicationAccepted']);
            }
            if(isset($data['AdmissionTaken'])){
                $userCourseApplied->setAdmissionTaken($data['AdmissionTaken']);
            }
            if(isset($data['timeOfAdmission'])){
                $userCourseApplied->setTimeOfAdmission($data['timeOfAdmission']);
            }
            if(isset($data['reasonsForRejection'])){
                $userCourseApplied->setReasonsForRejection(trim($data['reasonsForRejection']));
            }
	      }

    }
}

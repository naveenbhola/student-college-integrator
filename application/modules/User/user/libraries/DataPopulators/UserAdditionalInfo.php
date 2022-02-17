<?php
/**
 * File for Populator class for UserSocialProfile entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserSocailProfile entity
 */ 
class UserAdditionalInfo extends AbstractPopulator
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
     * Populate data into UserSocialProfile entity
     *
     * @param object $UserSocialProfile \user\Entities\UserSocialProfile
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserAdditionalInfo $userAdditionalInfo,$data = array())
    {
        $this->setData($data);
        if(isset($userAdditionalInfo) && gettype($userAdditionalInfo) == 'object') {

            if(isset($data['currentClass'])){
                $userAdditionalInfo->setCurrentClass($data['currentClass']);
            }
			if(isset($data['currentSchool'])){
                $userAdditionalInfo->setCurrentSchool($data['currentSchool']);
            }
			if(isset($data['bookedExamDate'])){
                $userAdditionalInfo->setBookedExamDate($data['bookedExamDate']);
            }
    	    if(isset($data['gradUniversity'])){
                    $userAdditionalInfo->setGradUniversity(trim($data['gradUniversity']));
            }
            if(isset($data['gradCollege'])){
                $userAdditionalInfo->setGradCollege(trim($data['gradCollege']));
            }
            if(isset($data['extracurricular'])){
                $userAdditionalInfo->setExtracurricular(trim($data['extracurricular']));
            }
            if(isset($data['specialConsiderations'])){
                $userAdditionalInfo->setSpecialConsiderations(trim($data['specialConsiderations']));
            }         
            if(isset($data['preferences'])){
                $userAdditionalInfo->setPreferences(trim($data['preferences']));
            }
            if(isset($data['workExpDetails'])){
                $userAdditionalInfo->setWorkExpDetails(trim($data['workExpDetails']));
            }
            if(isset($data['aboutMe'])){
                $userAdditionalInfo->setAboutMe(trim($data['aboutMe']));
            }
            if(isset($data['bio'])){
                $userAdditionalInfo->setBio(trim($data['bio']));
            }
            if(isset($data['studentEmail'])){
                $userAdditionalInfo->setStudentEmail(trim($data['studentEmail']));
            }
            if(isset($data['employmentStatus'])){
                $userAdditionalInfo->setEmploymentStatus(trim($data['employmentStatus']));
            }
            if(isset($data['maritalStatus'])){
                $userAdditionalInfo->setMaritalStatus(trim($data['maritalStatus']));
            }
	   }

    }
}

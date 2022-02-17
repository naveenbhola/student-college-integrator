<?php
/**
 * Registration observer file to update user points
 */
namespace user\libraries\RegistrationObservers;

/**
 * Registration observer to update user points
 */ 
class UserPointUpdation extends AbstractObserver
{
	/**
	 * Instance for User Points
	 *
	 * @var object syntax: (column name => userPointType)
	 *
	 */
	private $userPoints  = array(
									'avtarimageurl'         =>'profileUploadPhoto',
									// 'residenceCity'         =>'profileResidenceCity', 
									// 'residenceCityLocality' =>'profileResidenceCity', 
									// 'desiredCourse'         =>'profileStream', 
									'destinationCountry'    =>'profileCountryInterest', 
									// 'mobile'                =>'profilePhoneNumber', 
									'EducationBackground'   =>'profileEducationBackground', 
									'employer'              =>'profileWorkEx', 
									'aboutMe'               =>'profileAboutMe', 
									'dob'                   =>'profileDOB'
								);
	
	/**
	 * Instance for Old User Data
	 *
	 * @var object
	 *
	 */
	private $userOldData;

	/**
	 * Constructor
	 */
    function __construct()
    {
        parent::__construct();
    }
    
	/**
	 * Update the observer
	 *
	 * @param object $user \user\Entities\User
	 * @param array $data
	 */ 
    public function update(\user\Entities\User $user,$data = array(), $user_id) {

    	if((!empty($user)) && (is_object($user))) {
			$userId = $user->getId();
    	} else {
    		if(!empty($user_id)) {
    			$userId = $user_id;
    		} else {
    			return;
    		}
    	}
    	
    	$dbHandle = '';

		$this->CI->load->model('UserPointSystemModel');
		$this->UserPointSystemModel = new \UserPointSystemModel;

		foreach($this->userPoints as $fieldName=>$userPointType) {
			if((!empty($data[$fieldName])) && (isset($data[$fieldName])) && (empty($this->userOldData[$fieldName]))) {
				$this->UserPointSystemModel->updateUserPointSystem($dbHandle, $userId, $userPointType);				
			}
		}
    }
	
	public function setUserOldData(\user\Entities\User $user, $data) {

		if((empty($user)) || (!is_object($user))) {
			return;
		}

		$userData = array();

		if(!empty($data['avtarimageurl'])) {
			$userData['avtarimageurl'] = $this->setavtarimageurl($user);
		}

		if(!empty($data['residenceCity'])) {
			$userData['residenceCity'] = $this->setresidenceCity($user);
		}

		if(!empty($data['residenceCityLocality'])) {
			$userData['residenceCityLocality'] = $this->setresidenceCity($user);
		}

		if(!empty($data['mobile'])) {
			$userData['mobile'] = $this->setmobile($user);
		}

		if(!empty($data['dob'])) {
			$userData['dob'] = $this->setdob($user);
		}

		if(!empty($data['aboutMe'])) {
			$userData['aboutMe'] = $this->setaboutMe($user);
		}

		if(!empty($data['EducationBackground'])) {
			$userData['EducationBackground'] = $this->setEducationBackground($user);
		}		

		if(!empty($data['employer'])) {
			$userData['employer'] = $this->setemployer($user);
	    }

		if(!empty($data['desiredCourse'])) {
			$userData['desiredCourse'] = $this->setdesiredCourse($user);
		}

		if(!empty($data['destinationCountry'])) {
			$userData['destinationCountry'] = $this->setdestinationCountry($user);
		}

		$this->userOldData = $userData;
	}

	public function setavtarimageurl($user) {
		return $user->getAvatarImageURL();
	}

	public function setresidenceCity($user) {
		return $user->getCity();
	}

	public function setmobile($user) {
		return $user->getMobile();	
	}

	public function setdob($user) {
		$dob = $user->getDateOfBirth()->format('Y');
		$dob = (int)$dob;
		$finalDOB = '';
		if($dob > 0){
			$finalDOB = $dob;
		}
		return $finalDOB;
	}

	public function setaboutMe($user) {
		$aboutMe = '';
		$aboutMeData = $user->getUserAdditionalInfo();
		if(!empty($aboutMeData)) {
			$aboutMe = $aboutMeData->getAboutMe();
		}
		return $aboutMe;
	}

	public function setEducationBackground($user) {
		$EducationBackground = "";
		$levels = array('10','12','UG','PG','PHD');
		$userEducation = $user->getEducation();
		$examDetails = array();
		foreach($userEducation as $education) {
			$level = "";
			$level = $education->getLevel();
			if(in_array($level, $levels)){
				$instituteName = '';
				$instituteName = $education->getInstituteName();	
				if(!empty($instituteName)) {
					$EducationBackground = $instituteName;
					break;
				}
			}
		}
		return $EducationBackground;
	}

	public function setemployer($user) {
		$employer = "";
		$workExperience = $user->getUserWorkExp();
        if($workExperience){
            foreach ($workExperience as $key => $workExperienceRow) {
            	$currentEmployer = "";
                $currentEmployer = $workExperienceRow->getEmployer();

                if(!empty($currentEmployer)) {
		    	    $employer = $currentEmployer;
		    	    break;  
		    	}
                              
            }
        }
        return $employer;
	}

	public function setdesiredCourse($user) {
		// Set desired course in place of education uinterest because both are mandatory fields when filled and we store desired course in DB
		$desiredCourse = "";
		$preferenceDetails = $user->getPreference();
    	if(!empty($preferenceDetails)) {
    		$desiredCourse = $preferenceDetails->getDesiredCourse();
    	}
    	return $desiredCourse;
	}

	public function setdestinationCountry($user) {
		$destinationCountry = '';
		$locations = $user->getLocationPreferences();

		if(!empty($locations)) {
			$countryIds = array();			
			foreach ($locations as $location) {
				$countryId = '';
				$countryId = $location->getCountryId();
				if(!empty($countryId)) {
					$destinationCountry = $countryId;
					break;
				}
			}
		}
		return $destinationCountry;
	}

	public function updateUserProfilePicPoints($userId) {

		if(!empty($userId)) {
			$this->CI->load->model('UserPointSystemModel');
			$this->UserPointSystemModel = new \UserPointSystemModel;
			$this->UserPointSystemModel->updateUserPointSystem('', $userId, 'profileUploadPhoto');
		}

	}

}

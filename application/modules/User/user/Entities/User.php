<?php
/**
 * File for user\Entities\User(tUser DB Table)
 */
namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\User
 */
class User
{
    /**
     * Field for displayname
     * @var string $displayName
     */
    private $displayName;

    /**
     * Field for email
     * @var string $email
     */
    private $email;

    /**
     * Field for isdCode
     * @var string $isdCode
     */
    private $isdCode;

    /**
     * Field for mobile
     * @var string $mobile
     */
    private $mobile;

    /**
     * Field for city
     * @var string $city
     */
    private $city;

    /**
     * Field for profession
     * @var string $profession
     */
    private $profession;

    /**
     * Field for Viamobile
     * @var boolean $viamobile
     */
    private $viamobile;

    /**
     * Field for viaemail
     * @var boolean $viaemail
     */
    private $viaemail;

    /**
     * Field for newsLetteremail
     * @var boolean $newsLetterEmail
     */
    private $newsLetterEmail;

    /**
     * Field for avatar Image URL
     * @var string $avtarImageURL
     */
    private $avtarImageURL;

    /**
     * Field for User Creation Date
     * @var datetime $userCreationDate
     */
    private $userCreationDate;

    /**
     * Field for Last Modified on
     * @var date $lastModifiedOn
     */
    private $lastModifiedOn;

    /**
     * Field for Education Level
     * @var string $educationLevel
     */
    private $educationLevel;

    /**
     * Field for experience
     * @var integer $experience
     */
    private $experience;

    /**
     * Field for random key
     * @var string $randomKey
     */
    private $randomKey;

    /**
     * Field for LastLoginTime
     * @var datetime $lastLoginTime
     */
    private $lastLoginTime;

    /**
     * Field for User Group
     * @var string $userGroup
     */
    private $userGroup;

    /**
     * Field for Date of birth
     * @var date $dateOfBirth
     */
    private $dateOfBirth;

    /**
     * Field for Institute
     * @var string $institute
     */
    private $institute;

    /**
     * Field for user status
     * @var string $userStatus
     */
    private $userStatus;

    /**
     * Field for Graduation Year
     * @var integer $graduationYear
     */
    private $graduationYear;

    /**
     * Field for Text Password
     * @var string $textPassword
     */
    private $textPassword;

    /**
     * Field for country
     * @var string $country
     */
    private $country;

    /**
     * Field for First name
     * @var string $firstName
     */
    private $firstName;

    /**
     * Field for last name
     * @var string $lastName
     */
    private $lastName;

    /**
     * Field for Country of Education
     * @var string $countryOfEducation
     */
    private $countryOfEducation;

    /**
     * Field for City of Education
     * @var string $cityOfEducation
     */
    private $cityOfEducation;

    /**
     * Field for QuickSignUp Flag
     * @var string $quickSignupFlag
     */
    private $quickSignupFlag;

    /**
     * Field for age
     * @var integer $age
     */
    private $age;

    /**
     * Field for gender
     * @var string $gender
     */
    private $gender;
    
    /**
     * Field for passport
     * @var string $passport
     */
    private $passport;

    /**
     * Field for Landline
     * @var string $landline
     */
    private $landline;

    /**
     * Field for secondary email
     * @var string $secondaryEmail
     */
    private $secondaryEmail;

    /**
     * Field for locality
     * @var string $locality
     */
    private $locality;

    /**
     * Field for Publish Institue Following
     * @var integer $publishInstituteFollowing
     */
    private $publishInstituteFollowing;

    /**
     * Field for Publish Institute Updates
     * @var integer $publishInstituteUpdates
     */
    private $publishInstituteUpdates;

    /**
     * Field for Public Request EBrouchre
     * @var integer $publishRequestEBrochure
     */
    private $publishRequestEBrochure;

    /**
     * Field for publishBestAnswerAndLevelActivity
     * @var integer $publishBestAnswerAndLevelActivity
     */
    private $publishBestAnswerAndLevelActivity;

    /**
     * Field for publishArticleFollowing
     * @var integer $publishArticleFollowing
     */
    private $publishArticleFollowing;

    /**
     * Field for publishQuestionOnFB
     * @var integer $publishQuestionOnFB
     */
    private $publishQuestionOnFB;

    /**
     * Field for publishAnswerOnFB
     * @var integer $publishAnswerOnFB
     */
    private $publishAnswerOnFB;

    /**
     * Field for Publish Discussion on FB
     * @var integer $publishDiscussionOnFB
     */
    private $publishDiscussionOnFB;

    /**
     * Field for PublishAnnouncementOnFB
     * @var integer $publishAnnouncementOnFB
     */
    private $publishAnnouncementOnFB;

    /**
     * Field for ID
     * @var integer $id
     */
    private $id;

    /**
     * Field for Entity UserFlags
     * @var user\Entities\UserFlags
     */
    private $flags;

    /**
     * Field for Entity UserPointSystem
     * @var user\Entities\UserPointSystem
     */
    private $pointSystem;

    /**
     * Field for Entity UserRegistrationSource
     * @var user\Entities\UserRegistrationSource
     */
    private $registrationSource;

    /**
     * Array Collection for Education
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $education;

    /**
     * Array Collection for registration tracking
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $registrationTracking;
    /**
     * Array Collection for registration tracking
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $MISTracking;

    /**
     * Array Collection for Preference
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $preferences;

    /**
     * Array Collection for Location Prefernce
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $locationPreferences;

    /**
     * Array Collection for Specialization Preference
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $specializationPreferences;

    /**
     * Array Collection for page components
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $myPageComponents;

    /**
     * Field for Epassword
     * @var string $Epassword
     */
    private $password;

    private $ePassword;

    /**
     * Field for Entity UserCourseApplied
     * @var user\Entities\UserCourseApplied
     */
    private $courseApplied;

    /**
     * Field for Entity UserCourseApplied
     * @var user\Entities\UserCourseApplied
     */

    private $socialProfile;

    /**
     * Field for Entity userAdditionalInfo
     * @var user\Entities\userAdditionalInfo
     */
    private $userAdditionalInfo;

    /**
     * Field for Entity userWorkExp
     * @var user\Entities\userWorkExp
     */
    private $userWorkExp;

     /**
     * Field for Entity UserInterest
     * @var user\Entities\UserInterest
     */
    private $userInterest;

    /**
     * Constructor Function
     */
    public function __construct()
    {
        $this->education = new \Doctrine\Common\Collections\ArrayCollection();
        $this->preferences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->locationPreferences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specializationPreferences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myPageComponents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userInterest = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set displayName
     *
     * @param string $displayName
     * @return User
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

     /**
     * Set isdCode
     *
     * @param string $isdCode
     * @return User
     */
    public function setISDCode($isdCode)
    {
        $this->isdCode = $isdCode;
        return $isdCode;
    }

    /**
     * Get isdCode
     *
     * @return string 
     */
    public function getISDCode()
    {
        return $this->isdCode;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return User
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set profession
     *
     * @param string $profession
     * @return User
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;
        return $this;
    }

    /**
     * Get profession
     *
     * @return string 
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set viamobile
     *
     * @param boolean $viamobile
     * @return User
     */
    public function setViamobile($viamobile)
    {
        $this->viamobile = $viamobile;
        return $this;
    }

    /**
     * Get viamobile
     *
     * @return boolean 
     */
    public function getViamobile()
    {
        return $this->viamobile;
    }

    /**
     * Set viaemail
     *
     * @param boolean $viaemail
     * @return User
     */
    public function setViaemail($viaemail)
    {
        $this->viaemail = $viaemail;
        return $this;
    }

    /**
     * Get viaemail
     *
     * @return boolean 
     */
    public function getViaemail()
    {
        return $this->viaemail;
    }

    /**
     * Set newsLetterEmail
     *
     * @param boolean $newsLetterEmail
     * @return User
     */
    public function setNewsLetterEmail($newsLetterEmail)
    {
        $this->newsLetterEmail = $newsLetterEmail;
        return $this;
    }

    /**
     * Get newsLetterEmail
     *
     * @return boolean 
     */
    public function getNewsLetterEmail()
    {
        return $this->newsLetterEmail;
    }

    /**
     * Set avtarImageURL
     *
     * @param string $avtarImageURL
     * @return User
     */
    public function setAvtarImageURL($avtarImageURL)
    {
        $this->avtarImageURL = $avtarImageURL;
        return $this;
    }

    /**
     * Get avtarImageURL
     *
     * @return string 
     */
    public function getAvtarImageURL()
    {
        return $this->avtarImageURL;
    }

    /**
     * Set userCreationDate
     *
     * @param datetime $userCreationDate
     * @return User
     */
    public function setUserCreationDate($userCreationDate)
    {
        $this->userCreationDate = $userCreationDate;
        return $this;
    }

    /**
     * Get userCreationDate
     *
     * @return datetime 
     */
    public function getUserCreationDate()
    {
        return $this->userCreationDate;
    }

    /**
     * Set lastModifiedOn
     *
     * @param date $lastModifiedOn
     * @return User
     */
    public function setLastModifiedOn($lastModifiedOn)
    {
        $this->lastModifiedOn = $lastModifiedOn;
        return $this;
    }

    /**
     * Get lastModifiedOn
     *
     * @return date 
     */
    public function getLastModifiedOn()
    {
        return $this->lastModifiedOn;
    }

    /**
     * Set educationLevel
     *
     * @param string $educationLevel
     * @return User
     */
    public function setEducationLevel($educationLevel)
    {
        $this->educationLevel = $educationLevel;
        return $this;
    }

    /**
     * Get educationLevel
     *
     * @return string 
     */
    public function getEducationLevel()
    {
        return $this->educationLevel;
    }

    /**
     * Set experience
     *
     * @param integer $experience
     * @return User
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
        return $this;
    }

    /**
     * Get experience
     *
     * @return integer 
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set randomKey
     *
     * @param string $randomKey
     * @return User
     */
    public function setRandomKey($randomKey)
    {
        $this->randomKey = $randomKey;
        return $this;
    }

    /**
     * Get randomKey
     *
     * @return string 
     */
    public function getRandomKey()
    {
        return $this->randomKey;
    }

    /**
     * Set lastLoginTime
     *
     * @param datetime $lastLoginTime
     * @return User
     */
    public function setLastLoginTime($lastLoginTime)
    {
        $this->lastLoginTime = $lastLoginTime;
        return $this;
    }

    /**
     * Get lastLoginTime
     *
     * @return datetime 
     */
    public function getLastLoginTime()
    {
        return $this->lastLoginTime;
    }

    /**
     * Set userGroup
     *
     * @param string $userGroup
     * @return User
     */
    public function setUserGroup($userGroup)
    {
        $this->userGroup = $userGroup;
        return $this;
    }

    /**
     * Get userGroup
     *
     * @return string 
     */
    public function getUserGroup()
    {
        return $this->userGroup;
    }

    /**
     * Set dateOfBirth
     *
     * @param date $dateOfBirth
     * @return User
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return date 
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set institute
     *
     * @param string $institute
     * @return User
     */
    public function setInstitute($institute)
    {
        $this->institute = $institute;
        return $this;
    }

    /**
     * Get institute
     *
     * @return string 
     */
    public function getInstitute()
    {
        return $this->institute;
    }

    /**
     * Set userStatus
     *
     * @param string $userStatus
     * @return User
     */
    public function setUserStatus($userStatus)
    {
        $this->userStatus = $userStatus;
        return $this;
    }

    /**
     * Get userStatus
     *
     * @return string 
     */
    public function getUserStatus()
    {
        return $this->userStatus;
    }

    /**
     * Set graduationYear
     *
     * @param integer $graduationYear
     * @return User
     */
    public function setGraduationYear($graduationYear)
    {
        $this->graduationYear = $graduationYear;
        return $this;
    }

    /**
     * Get graduationYear
     *
     * @return integer 
     */
    public function getGraduationYear()
    {
        return $this->graduationYear;
    }

    /**
     * Set textPassword
     *
     * @param string $textPassword
     * @return User
     */
    public function setTextPassword($textPassword)
    {
        $this->textPassword = $textPassword;
        return $this;
    }

    /**
     * Get textPassword
     *
     * @return string 
     */
    public function getTextPassword()
    {
        return $this->textPassword;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set countryOfEducation
     *
     * @param string $countryOfEducation
     * @return User
     */
    public function setCountryOfEducation($countryOfEducation)
    {
        $this->countryOfEducation = $countryOfEducation;
        return $this;
    }

    /**
     * Get countryOfEducation
     *
     * @return string 
     */
    public function getCountryOfEducation()
    {
        return $this->countryOfEducation;
    }

    /**
     * Set cityOfEducation
     *
     * @param string $cityOfEducation
     * @return User
     */
    public function setCityOfEducation($cityOfEducation)
    {
        $this->cityOfEducation = $cityOfEducation;
        return $this;
    }

    /**
     * Get cityOfEducation
     *
     * @return string 
     */
    public function getCityOfEducation()
    {
        return $this->cityOfEducation;
    }

    /**
     * Set quickSignupFlag
     *
     * @param string $quickSignupFlag
     * @return User
     */
    public function setQuickSignupFlag($quickSignupFlag)
    {
        $this->quickSignupFlag = $quickSignupFlag;
        return $this;
    }

    /**
     * Get quickSignupFlag
     *
     * @return string 
     */
    public function getQuickSignupFlag()
    {
        return $this->quickSignupFlag;
    }

    /**
     * Set age
     *
     * @param integer $age
     * @return User
     */
    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }

    /**
     * Get age
     *
     * @return integer 
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }
    
    /**
     * Set passport
     *
     * @param string $passport
     * @return User
     */
    public function setPassport($passport)
    {
        $this->passport = $passport;
        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getPassport()
    {
        return $this->passport;
    }

    /**
     * Set landline
     *
     * @param string $landline
     * @return User
     */
    public function setLandline($landline)
    {
        $this->landline = $landline;
        return $this;
    }

    /**
     * Get landline
     *
     * @return string 
     */
    public function getLandline()
    {
        return $this->landline;
    }

    /**
     * Set secondaryEmail
     *
     * @param string $secondaryEmail
     * @return User
     */
    public function setSecondaryEmail($secondaryEmail)
    {
        $this->secondaryEmail = $secondaryEmail;
        return $this;
    }

    /**
     * Get secondaryEmail
     *
     * @return string 
     */
    public function getSecondaryEmail()
    {
        return $this->secondaryEmail;
    }

    /**
     * Set locality
     *
     * @param string $locality
     * @return User
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
        return $this;
    }

    /**
     * Get locality
     *
     * @return string 
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Set publishInstituteFollowing
     *
     * @param integer $publishInstituteFollowing
     * @return User
     */
    public function setPublishInstituteFollowing($publishInstituteFollowing)
    {
        $this->publishInstituteFollowing = $publishInstituteFollowing;
        return $this;
    }

    /**
     * Get publishInstituteFollowing
     *
     * @return integer 
     */
    public function getPublishInstituteFollowing()
    {
        return $this->publishInstituteFollowing;
    }

    /**
     * Set publishInstituteUpdates
     *
     * @param integer $publishInstituteUpdates
     * @return User
     */
    public function setPublishInstituteUpdates($publishInstituteUpdates)
    {
        $this->publishInstituteUpdates = $publishInstituteUpdates;
        return $this;
    }

    /**
     * Get publishInstituteUpdates
     *
     * @return integer 
     */
    public function getPublishInstituteUpdates()
    {
        return $this->publishInstituteUpdates;
    }

    /**
     * Set publishRequestEBrochure
     *
     * @param integer $publishRequestEBrochure
     * @return User
     */
    public function setPublishRequestEBrochure($publishRequestEBrochure)
    {
        $this->publishRequestEBrochure = $publishRequestEBrochure;
        return $this;
    }

    /**
     * Get publishRequestEBrochure
     *
     * @return integer 
     */
    public function getPublishRequestEBrochure()
    {
        return $this->publishRequestEBrochure;
    }

    /**
     * Set publishBestAnswerAndLevelActivity
     *
     * @param integer $publishBestAnswerAndLevelActivity
     * @return User
     */
    public function setPublishBestAnswerAndLevelActivity($publishBestAnswerAndLevelActivity)
    {
        $this->publishBestAnswerAndLevelActivity = $publishBestAnswerAndLevelActivity;
        return $this;
    }

    /**
     * Get publishBestAnswerAndLevelActivity
     *
     * @return integer 
     */
    public function getPublishBestAnswerAndLevelActivity()
    {
        return $this->publishBestAnswerAndLevelActivity;
    }

    /**
     * Set publishArticleFollowing
     *
     * @param integer $publishArticleFollowing
     * @return User
     */
    public function setPublishArticleFollowing($publishArticleFollowing)
    {
        $this->publishArticleFollowing = $publishArticleFollowing;
        return $this;
    }

    /**
     * Get publishArticleFollowing
     *
     * @return integer 
     */
    public function getPublishArticleFollowing()
    {
        return $this->publishArticleFollowing;
    }

    /**
     * Set publishQuestionOnFB
     *
     * @param integer $publishQuestionOnFB
     * @return User
     */
    public function setPublishQuestionOnFB($publishQuestionOnFB)
    {
        $this->publishQuestionOnFB = $publishQuestionOnFB;
        return $this;
    }

    /**
     * Get publishQuestionOnFB
     *
     * @return integer 
     */
    public function getPublishQuestionOnFB()
    {
        return $this->publishQuestionOnFB;
    }

    /**
     * Set publishAnswerOnFB
     *
     * @param integer $publishAnswerOnFB
     * @return User
     */
    public function setPublishAnswerOnFB($publishAnswerOnFB)
    {
        $this->publishAnswerOnFB = $publishAnswerOnFB;
        return $this;
    }

    /**
     * Get publishAnswerOnFB
     *
     * @return integer 
     */
    public function getPublishAnswerOnFB()
    {
        return $this->publishAnswerOnFB;
    }

    /**
     * Set publishDiscussionOnFB
     *
     * @param integer $publishDiscussionOnFB
     * @return User
     */
    public function setPublishDiscussionOnFB($publishDiscussionOnFB)
    {
        $this->publishDiscussionOnFB = $publishDiscussionOnFB;
        return $this;
    }

    /**
     * Get publishDiscussionOnFB
     *
     * @return integer 
     */
    public function getPublishDiscussionOnFB()
    {
        return $this->publishDiscussionOnFB;
    }

    /**
     * Set publishAnnouncementOnFB
     *
     * @param integer $publishAnnouncementOnFB
     * @return User
     */
    public function setPublishAnnouncementOnFB($publishAnnouncementOnFB)
    {
        $this->publishAnnouncementOnFB = $publishAnnouncementOnFB;
        return $this;
    }

    /**
     * Get publishAnnouncementOnFB
     *
     * @return integer 
     */
    public function getPublishAnnouncementOnFB()
    {
        return $this->publishAnnouncementOnFB;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set flags
     *
     * @param user\Entities\UserFlags $flags
     * @return User
     */
    public function setFlags(\user\Entities\UserFlags $flags = null)
    {
        $this->flags = $flags;
        return $this;
    }

    /**
     * Get flags
     *
     * @return user\Entities\UserFlags 
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * Set pointSystem
     *
     * @param user\Entities\UserPointSystem $pointSystem
     * @return User
     */
    public function setPointSystem(\user\Entities\UserPointSystem $pointSystem = null)
    {
        $this->pointSystem = $pointSystem;
        return $this;
    }

    /**
     * Get pointSystem
     *
     * @return user\Entities\UserPointSystem 
     */
    public function getPointSystem()
    {
        return $this->pointSystem;
    }

/**
     * Set pointSystem
     *
     * @param user\Entities\UserCourseApplied $courseApplied
     * @return User
     */
    public function setCourseApplied(\user\Entities\UserCourseApplied $courseApplied)
    {
        $this->courseApplied[] = $courseApplied;
        return $this;
    }

    /**
     * Get courseApplied
     *
     * @return user\Entities\UserCourseApplied 
     */
    public function getCourseApplied()
    {
        return $this->courseApplied;
    }


    /**
     * Set socialProfile
     *
     * @param user\Entities\UserSocialProfile $socialProfile
     * @return User
     */
    public function setSocialProfile(\user\Entities\UserSocialProfile $socialProfile = null)
    {
        $this->socialProfile = $socialProfile;
        return $this;
    }

    /**
     * Get socialProfile
     *
     * @return user\Entities\UserSocialProfile 
     */
    public function getSocialProfile()
    {
        return $this->socialProfile;
    }

    /**
     * Set userAdditionalInfo
     *
     * @param user\Entities\UserAdditionalInfo $userAdditionalInfo
     * @return User
     */
    public function setUserAdditionalInfo(\user\Entities\UserAdditionalInfo $userAdditionalInfo = null)
    {
        $this->userAdditionalInfo = $userAdditionalInfo;
        return $this;
    }

    /**
     * Get userAdditionalInfo
     *
     * @return user\Entities\UserAdditionalInfo 
     */
    public function getUserAdditionalInfo()
    {
        return $this->userAdditionalInfo;
    }


    /**
     * Set userInterest
     *
     * @param user\Entities\UserInterest $userInterest
     * @return User
     */
    public function addUserInterest(\user\Entities\UserInterest $userInterest = null)
    {
        $this->userInterest[] = $userInterest;
        return $this;
    }

    /**
     * Get userAdditionalInfo
     *
     * @return user\Entities\UserAdditionalInfo 
     */
    public function getUserInterest()
    {
        return $this->userInterest;
    }

    /**
     * Set userWorkExp
     *
     * @param user\Entities\UserWorkExp $userWorkExp
     * @return User
     */
    public function setUserWorkExp(\user\Entities\UserWorkExp $userWorkExp = null)
    {
        $this->userWorkExp[] = $userWorkExp;
        return $this;
    }

    /**
     * Get userWorkExp
     *
     * @return user\Entities\UserWorkExp 
     */
    public function getUserWorkExp()
    {
        return $this->userWorkExp;
    }

    /**
     * Set registrationSource
     *
     * @param user\Entities\UserRegistrationSource $registrationSource
     * @return User
     */
    public function setRegistrationSource(\user\Entities\UserRegistrationSource $registrationSource = null)
    {
        $this->registrationSource = $registrationSource;
        return $this;
    }

    /**
     * Get registrationSource
     *
     * @return user\Entities\UserRegistrationSource 
     */
    public function getRegistrationSource()
    {
        return $this->registrationSource;
    }

    /**
     * Add education
     *
     * @param user\Entities\UserEducation $education
     * @return User
     */
    public function addUserEducation(\user\Entities\UserEducation $education)
    {
        $this->education[] = $education;
        return $this;
    }

    /**
     * Get education
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * Add registraion tracking
     *
     * @param user\Entities\RegistrationTracking $registrationTracking
     * @return User
     */
    public function addRegistrationTracking(\user\Entities\RegistrationTracking $registrationTracking)
    {
        $this->registrationTracking[] = $registrationTracking;
        return $this;
    }

    /**
     * Get registraion tracking
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRegistrationTracking()
    {
        return $this->registrationTracking;
    }

    /**
     * Add registraion tracking
     *
     * @param user\Entities\MISTracking $MISTracking
     * @return User
     */
    public function addMISTracking(\user\Entities\MISTracking $MISTracking)
    {
        $this->MISTracking[] = $MISTracking;
        return $this;
    }

    /**
     * Get registraion tracking
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMISTracking()
    {
        return $this->MISTracking;
    }


    /**
     * Add preferences
     *
     * @param user\Entities\UserPref $preferences
     * @return User
     */
    public function addUserPref(\user\Entities\UserPref $preferences)
    {
        $this->preferences[] = $preferences;
        return $this;
    }

    /**
     * Get preferences
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPreferences()
    {
        return $this->preferences;
    }
    
    /**
     * Get preferences
     */
    public function getPreference()
    {
        return $this->preferences[0];
    }

    /**
     * Add locationPreferences
     *
     * @param user\Entities\UserLocationPref $locationPreferences
     * @return User
     */
    public function addUserLocationPref(\user\Entities\UserLocationPref $locationPreferences)
    {
        $this->locationPreferences[] = $locationPreferences;
        return $this;
    }
	
    /**
     * Update SpecializationPreferences
     *
     * @param user\Entities\UserSpecializationPref $SpecializationPreferences
     * @return User
     */
    public function updateUserSpecializationPref($SpecializationPreferences)
    {
        $this->specializationPreferences =  $SpecializationPreferences;
    }    

    /**
     * Get locationPreferences
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLocationPreferences()
    {
        return $this->locationPreferences;
    }

    /**
     * Add specializationPreferences
     *
     * @param user\Entities\UserSpecializationPref $specializationPreferences
     * @return User
     */
    public function addUserSpecializationPref(\user\Entities\UserSpecializationPref $specializationPreferences)
    {
        $this->specializationPreferences[] = $specializationPreferences;
        return $this;
    }

    /**
     * Get specializationPreferences
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSpecializationPreferences()
    {
        return $this->specializationPreferences;
    }

    /**
     * Add myPageComponents
     *
     * @param user\Entities\UserMyPageComponent $myPageComponents
     * @return User
     */
    public function addUserMyPageComponent(\user\Entities\UserMyPageComponent $myPageComponents)
    {
        $this->myPageComponents[] = $myPageComponents;
        return $this;
    }

    /**
     * Get myPageComponents
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMyPageComponents()
    {
        return $this->myPageComponents;
    }
    /**
     * Field for Avatar Image URl
     * @var string $avatarImageURL
     */
    private $avatarImageURL;


    /**
     * Set avatarImageURL
     *
     * @param string $avatarImageURL
     * @return User
     */
    public function setAvatarImageURL($avatarImageURL)
    {
        $this->avatarImageURL = $avatarImageURL;
        return $this;
    }

    /**
     * Get avatarImageURL
     *
     * @return string 
     */
    public function getAvatarImageURL()
    {
        return $this->avatarImageURL;
    }
    
    /**
     * Check if user is LDB user
     *
     * @return bool
     */ 
    public function isLDB()
    {
        return ($this->flags->getIsLDBUser() == 'YES');
    }

    /**
     * Set Epassword (Encrypted Password in SHA-256)
     *
     * @param string $Epassword
     * @return User
    */

    public function setEpassword($ePassword){
        $this->ePassword = $ePassword;
        return $this;
    }

    /**
     * Get password (Encrypted Password in md5)
     *
     * @return string 
    */

    public function getEpassword(){
        return $this->ePassword;
    }

}

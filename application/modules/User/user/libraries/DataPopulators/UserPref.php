<?php
/**
 * Populator File for UserPref entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserPref entity
 */ 
class UserPref extends AbstractPopulator
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
     * Populate data into UserPref entity
     *
     * @param object $userPref \user\Entities\UserPref
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserPref $userPref,$data = array())
    {
        $this->setData($data);
        
        /**
         * Populate data for degree preferences
         */ 
        $degreePreferences = array('aicte' => 'no','ugc' => 'no','international' => 'no','any' => 'no');
        
        if(is_array($data['degreePreference'])) {
            foreach($degreePreferences as $pref => $value) {
                if(in_array($pref,$data['degreePreference'])) {
                    $degreePreferences[$pref] = 'yes';
                }
            }
        }
        
        if($this->canSet('degreePreference')) {
            $userPref->setDegreePrefAICTE($degreePreferences['aicte']);
            $userPref->setDegreePrefUGC($degreePreferences['ugc']);
            $userPref->setDegreePrefInternational($degreePreferences['international']);
            $userPref->setDegreePrefAny($degreePreferences['any']);
        }
        
        /**
         * populate data for mode
         */
        $modes = array('fullTime' => 'no','partTime' => 'no','distance' => 'no');
        
        if(is_array($data['mode'])) {
            foreach($modes as $mode => $value) {
                if(in_array($mode,$data['mode'])) {
                    $modes[$mode] = 'yes';
                }
            }
        }
        
        if($this->canSet('mode')) {
            $userPref->setModeOfEducationFullTime($modes['fullTime']);
            $userPref->setModeOfEducationPartTime($modes['partTime']);
            $userPref->setModeOfEducationDistance($modes['distance']);
        }
        
        /**
         * populate data for funds
         */
        $funds = array('own' => 'no','bank' => 'no','other' => 'no');
        
        if(is_array($data['fund'])) {
            foreach($funds as $fund => $value) {
                if(in_array($fund,$data['fund'])) {
                    $funds[$fund] = 'yes';
                }
            }
        }
        
        if($this->canSet('fund')) {
            $userPref->setUserFundsOwn($funds['own']);
            $userPref->setUserFundsBank($funds['bank']);
            $userPref->setUserFundsNone($funds['other']);
            if(!$data['otherFundingDetails']) {
                $data['otherFundingDetails'] = NULL;
            }
            $userPref->setOtherFundingDetails($data['otherFundingDetails']);
        }
        
        if($this->canSet('whenPlanToStart') || $this->canSet('whenPlanToGo')) {
            if($data['isStudyAbroad'] == 'yes') {
                if($timeOfStart = $this->_getTimeOfStart()) {
                    $userPref->setTimeOfStart(new \DateTime($timeOfStart));
                }
            }else{
                $userPref->setTimeOfStart(null);
            }
        }
        

        if($this->canSet('prefYear')) {
            $userPref->setPrefYear($data['prefYear']);
        }

        if($this->canSet('sourceInfo')) {
            $userPref->setSourceInfo($this->getValue('sourceInfo'));    
        }
        
        if($this->canSet('abroadSpecialization')) {
            $userPref->setAbroadSpecialization($this->getValue('abroadSpecialization'));
        }
        else {
            $userPref->setAbroadSpecialization(NULL);
        }
        
        if($this->canSet('budget')) {
            $userPref->setBudget($this->getValue('budget'));    
        }
        
        if($this->canSet('contactByConsultant')) {
            $userPref->setContactByConsultant($this->getValue('contactByConsultant'));
        }
        
        $desiredPrefUpdated = FALSE;
        if($data['isStudyAbroad'] == 'yes' || $data['isStudyAbroadFlag'] == 'yes') { // education background details not able to edit from national profile page in case user is abroad type because abroad user education fields are different.
            if(!empty($data['desiredCourse']) && $this->canSet('desiredCourse')){
                $userPref->setDesiredCourse($this->getValue('desiredCourse'));
                $userPref->setExtraFlag('studyabroad');
                $desiredPrefUpdated = TRUE;
            }
            elseif(!empty($data['fieldOfInterest']) && $this->canSet('fieldOfInterest')) {
                $desiredCourse = $this->_getDesiredCourseForStudyAbroad();
                if(!$desiredCourse) {
                    $desiredCourse = NULL;
                    if(empty($_POST['mmpFormId'])){
                        mail('naveen.bhola@shiksha.com,satech@shiksha.com,jahangeer.alam@shiksha.com,geetu.sadana@shiksha.com','FOI empty at '.date('Y-m-d H:i:s'), 'Some data missing <br/>From page: '.$_SERVER['HTTP_REFERER'].'<br/>Visitor Session Id: '.getVisitorSessionId().'<br/>'.print_r($_POST, true));
                    }else{
                        mail('naveen.bhola@shiksha.com','FOI empty (For MMP Form) at '.date('Y-m-d H:i:s'), 'Some data missing <br/>From page: '.$_SERVER['HTTP_REFERER'].'<br/>Visitor Session Id: '.getVisitorSessionId().'<br/>'.print_r($_POST, true));    
                    }
                }
                $userPref->setDesiredCourse($desiredCourse);
                $userPref->setExtraFlag('studyabroad');
                $userPref->setIsProcessed('no');
                $desiredPrefUpdated = TRUE;
            } else {
                $userPref->setExtraFlag('studyabroad');
                $userPref->setIsProcessed('no');
                $desiredPrefUpdated = TRUE;
                if(empty($data['sectionType'])){
                    if(empty($_POST['mmpFormId'])){
                        mail('naveen.bhola@shiksha.com,satech@shiksha.com,jahangeer.alam@shiksha.com,geetu.sadana@shiksha.com','Both empty at '.date('Y-m-d H:i:s'), 'Some data missing <br/>From page: '.$_SERVER['HTTP_REFERER'].'<br/>Visitor Session Id: '.getVisitorSessionId().'<br/>'.print_r($_POST, true));
                    }else{
                        mail('naveen.bhola@shiksha.com','Both empty(For MMP Form) at '.date('Y-m-d H:i:s'), 'Some data missing <br/>From page: '.$_SERVER['HTTP_REFERER'].'<br/>Visitor Session Id: '.getVisitorSessionId().'<br/>'.print_r($_POST, true));
                    }
                }else{
                    mail('naveen.bhola@shiksha.com','National Profile Page, Both empty at '.date('Y-m-d H:i:s'), 'Some data missing <br/>From page: '.$_SERVER['HTTP_REFERER'].'<br/>Visitor Session Id: '.getVisitorSessionId().'<br/>'.print_r($_POST, true));
                }
                
            }

        }
        else if($data['isTestPrep'] == 'yes') {
            if($this->canSet('desiredCourse')) {
                $userPref->setDesiredCourse(0);
                $userPref->setExtraFlag('testprep');
                $desiredPrefUpdated = TRUE;
            }    
        }
        else {
            if($this->canSet('desiredCourse') && !empty($data['desiredCourse'])) {
                if(!$data['desiredCourse'] || $data['desiredCourse'] == 1) {
                    $data['desiredCourse'] = NULL;
                }
                $userPref->setDesiredCourse($data['desiredCourse']);
                $userPref->setExtraFlag(NULL);
                $desiredPrefUpdated = TRUE;
            }else{
                $userPref->setExtraFlag(NULL);
                $userPref->setDesiredCourse(NULL);
                $userPref->setAbroadSpecialization(NULL);
            }
        }
        
        if($this->canSet('callPreference')) {
            $userPref->setSuitableCallPref($this->getValue('callPreference',1));
        }
        
        if($this->canSet('otherDetails')) {
            if(!$data['otherDetails']) {
                $data['otherDetails'] = NULL;
            }
            $userPref->setUserDetail($data['otherDetails']);
        }
        
        if($this->isCreation() || !empty($data['stream']) || $desiredPrefUpdated || $data['isCityChanged'] || $data['isCountryChanged']) {
            $userPref->setSubmitDate(new \DateTime);
        }
        $userPref->setStatus('live');
        
        if($this->isCreation() || !$userPref->getIsProcessed()) {
            $userPref->setIsProcessed('no');
        }

        if(!empty($data['sourceOfFunding'])){

            if($data['sourceOfFunding'] == 'own'){
                $userPref->setUserFundsOwn('yes');
                $userPref->setUserFundsNone('no');
            }else if($data['sourceOfFunding'] == 'bank'){
                $userPref->setUserFundsBank('yes');
                $userPref->setUserFundsOwn('no');
            }else if($data['sourceOfFunding'] == 'company'){
                $userPref->setUserFundsBank('no');
                $userPref->setUserFundsOwn('no');
                $userPref->setOtherFundingDetails($data['sourceOfFunding']);
            }else if($data['sourceOfFunding'] == 'other'){
                $userPref->setUserFundsNone('yes');
                $userPref->setUserFundsOwn('no');
                $userPref->setUserFundsBank('no');
            }
        }else{
            $userPref->setUserFundsBank('no');
            $userPref->setUserFundsOwn('no');
        }
        
        if(!empty($data['stream']) && ($data['mode'] == 'create' || $data['isResponseForm'] != 'yes') ){
            $userPref->setEducationLevel($data['courseLevelData']['educationLevel']);
            $userPref->setFlow($data['flow']);
        }
    }
    
    /**
     * Get actual date for when plan to start
     *
     * @param string $whenPlanToStart
     * @return datetime
     */ 
    private function _getTimeOfStart($whenPlanToStart)
    {
        if($timeOfStart = $this->_getTimeOfStartFromAcademicDetails()) {
            return $timeOfStart;
        }
        
        $whenPlanToStartMapping =  array(
                                            'immediately'   =>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y"))),
                                            'within2Months' =>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m") + 2,date("d"),date("Y"))),
                                            'within3Months' =>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m") + 3,date("d"),date("Y"))),
                                            'thisYear'      =>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y"))),
                                            'nextYear'      =>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+1)),
                                            'nextToNextYear'=>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+2)),
                                            'notSure'       =>  '0000-00-00 00:00:00'
                                        );

         if(date('m',strtotime('now')) > 9) {
            $whenPlanToGoMapping =  array(
                                                'thisYear'  =>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y"))),
                                                'in1Year'   =>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+1)),
                                                'in2Years'  =>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+2)),
                                                'later'     =>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+3))
                                            );
        }else{
            $whenPlanToGoMapping =  array(
                                    'thisYear'  =>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y"))),
                                    'in1Year'   =>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+1)),
                                    'in2Years'  =>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+2)),
                                    'later'     =>  date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+2))
                                );
        }
        
        if($this->data['whenPlanToStart']) {
            return $whenPlanToStartMapping[$this->data['whenPlanToStart']];
        }
        else if($this->data['whenPlanToGo']) {
            return $whenPlanToGoMapping[$this->data['whenPlanToGo']];
        }
    }
    
    /**
     * Get actual date from academic details for when plan to start
     *
     * @return datetime
     */ 
    private function _getTimeOfStartFromAcademicDetails()
    {
        $referenceYear = NULL;
        
        /**
         * Full Time MBA
         */ 
        if($this->data['desiredCourse'] == 2) {
            $referenceYear = $this->data['graduationCompletionYear'];
        }
        else if($this->data['desiredCourse'] == 52 && !in_array('partTime',$this->data['mode'])) {
            $referenceYear = $this->data['xiiYear'];
        }
        
        if(!$referenceYear) {
            return FALSE;
        }
        
        $currentYear = date('Y');
        if($referenceYear <= $currentYear) {
            return date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")));
        }
        else {
            $yearDiff = $referenceYear-$currentYear;
            return date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+$yearDiff));
        }
    }
    
    /**
     * Get desired course value fr study abroad
     * It's calculated by a combination of field of interest (category) and desired graduation level (UG|PG)
     *
     * @return integer
     */ 
    private function _getDesiredCourseForStudyAbroad()
    {
        $categoryId = $this->data['fieldOfInterest'];
        $desiredGraduationLevel = $this->data['desiredGraduationLevel'];
        
        STUDY_ABROAD_NEW_REGISTRATION;
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

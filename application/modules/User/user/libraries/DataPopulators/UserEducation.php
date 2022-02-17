<?php
/**
 * Populator class file for UserEducation entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserEducation entity
 */ 
class UserEducation extends AbstractPopulator
{
    /**
     * @var array Education levels
     */ 
    public static $educationLevels = array('xii','ug','exam');
    
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
     * Populate data into UserEducation entity
     *
     * @param object $education \user\Entities\UserEducation
     * @param array $data Data to be populated in
     * @param array $params Additional parameters
     */
    public function populate(\user\Entities\UserEducation $education,$data = array(),$params = array())
    {
        require APPPATH.'modules/User/registration/config/examConfig.php';
        
        $name = $level = $marks = $marksType = $completionDate = '';
        $ongoingCompletedFlag = 0;
        
        $educationLevel = $params['level'];
        $educationLevelComponents = explode('-',$educationLevel);
        if($educationLevelComponents[0] == 'exam' || $educationLevelComponents[0] == 'otherExam') {
            $educationLevel = $educationLevelComponents[0];
            $examType = $educationLevelComponents[1];
        }

        if($educationLevelComponents[0] == 'subjects') {
            $educationLevel = $educationLevelComponents[0];
            $subjectName = $educationLevelComponents[1];
        }
        
        switch($educationLevel) {
            case 'xii':
                $name = $data['xiiStream'];
                $level = 12;
                $marks = $data['xiiMarks'];
                $marksType = NULL;
                $completionDate = new \DateTime($data['xiiYear'].'-01-01');
                $board = NULL;
                $subject = NULL;
                break;
                
            case 'graduation':
                $name = $data['graduationDetails'];
                $level = 'UG';
                $marks = $data['graduationMarks'];
                $marksType = NULL;
                if($data['graduationStatus'] == 'Pursuing') {
                    $ongoingCompletedFlag = 1;
                }
                if($data['graduationCompletionYear']) {
                    $graduationCompletionMonth = $data['graduationCompletionMonth'] ? $data['graduationCompletionMonth'] : 1;
                    $completionDate = new \DateTime($data['graduationCompletionYear'].'-'.$graduationCompletionMonth.'-01');
                }
                else {
                    $completionDate = NULL;
                }
                $board = NULL;
                $subject = NULL;
                break;

            case 'exam':
                global $examGrades;
                global $examFloat;
                $examGroupId = $params["examGroupId"][$examType];
                $name = $examType;
                if($name == 'Other' && $data['otherExamName']) {
                    $name = $data['otherExamName'];
                }
                
                $level = 'Competitive exam';
                if(empty($examGrades[$name])) {
                    if(empty($examFloat[$name])) {
                        $marks = (int) $data[$examType.'_score'];
                    }
                    else {
                        $marks = (float) $data[$examType.'_score'];
                    }
                }
                else {
                    $marks = (int) $examGrades[$name][trim(strtoupper($data[$examType.'_score']))];
                }
                
                $marksType = $data[$examType.'_scoreType'];
                
                if($data[$examType.'_year']) {
                    $completionDate = new \DateTime($data[$examType.'_year']);
                }
                else {
                    $completionDate = new \DateTime('0000-00-00');
                }

                $board = NULL;
                $subject = NULL;
                break;
                
            case 'otherExam':
                $name = $examType;
                $level = 'Competitive exam';
                $marks = $data['otherExamScore'][array_search($examType,$data['otherExams'])];
                $marksType = NULL;
                $completionDate = new \DateTime('0000-00-00');
                $board = NULL;
                $subject = NULL;
                break;

            case 'subjects':
                $name = '10';
                $level = $data['isUnifiedProfile'] == 'yes' ? '10':'UG';
                $board = $data['tenthBoard'];
                $instituteName = isset($data['xthSchool']) ? $data['xthSchool']: NULL;
                /*Global variables defined in shikshaConstant.php for mapping marks to board */
                global $CBSE_Grade_Mapping;
                global $IGCSE_Grade_Mapping;
                global $IBMYP_Grade_Mapping;

                if($board == 'CBSE'){
                    $marks = $CBSE_Grade_Mapping[$data['tenthmarks']];
                }else if($board == 'ICSE' || $board=='NIOS'){
                    $marks = $data['tenthmarks'];
                }else if($board == 'IGCSE'){
                    $marks = $IGCSE_Grade_Mapping[$data['tenthmarks']];
                }else if($board == 'IBMYP'){
                    $marks = $IBMYP_Grade_Mapping[$data['tenthmarks']];
                }

                if($data['tenthBoard'] == 'CBSE' || $data['tenthBoard'] == 'IGCSE'){
                    $marksType = 'grades';
                }else{
                    $marksType = 'marks';
                }
                $subject = $subjectName;    
                $completionDate = !empty($data['xthCompletionYear'])? new \DateTime($data['xthCompletionYear'].'-01-01') : new \DateTime('0000-00-00');
            break;

            case 'PG':
                $name = 'Graduation';
                $level = 'PG';
                $marks = $data['graduationMarks'];
                $marksType = 'percentage';
                $completionDate = new \DateTime('0000-00-00');
                $board = NULL;
                $subject = $data['graduationStream'];
            break;

            case '12th':
                $name = '12th';
                $level = 12;
                $marks = $data['12thMarks'];
                $marksType = 'percentage';
                $completionDate = new \DateTime('0000-00-00');
                $board = $data['12thBoard'];
                $subject = NULL;
            break;

            case 'RmcWithoutSub':
                $name = '10';
                $level = 'UG';
                $board = $data['tenthBoard'];
                
                /*Global variables defined in shikshaConstant.php for mapping marks to board */
                global $CBSE_Grade_Mapping;
                global $IGCSE_Grade_Mapping;
                global $IBMYP_Grade_Mapping;
                if($board == 'CBSE'){
                    $marks = $CBSE_Grade_Mapping[$data['tenthmarks']];
                }else if($board == 'ICSE'  || $board=='NIOS'){
                    $marks = $data['tenthmarks'];
                }else if($board == 'IGCSE'){
                    $marks = $IGCSE_Grade_Mapping[$data['tenthmarks']];
                }else if($board == 'IBMYP'){
                    $marks = $IBMYP_Grade_Mapping[$data['tenthmarks']];
                }

                if($data['tenthBoard'] == 'CBSE' || $data['tenthBoard'] == 'IGCSE'){
                    $marksType = 'grades';
                }else{
                    $marksType = 'marks';
                }
                $subject = NULL;    
                $completionDate = new \DateTime('0000-00-00');
            break;

            case 'xiith':
                $name = '12';
                $level = '12';
                $board = isset($data['xiiBoard'])? $data['xiiBoard']:NULL;
                $instituteName = isset($data['xiithSchool']) ? $data['xiithSchool']: NULL;
                $marks = $data['xiiMarks'];
                $marksType = 'percentage';
                $subject = NULL;
                $specialization = isset($data['xiiSpecialization']) ? $data['xiiSpecialization'] : NULL;
                /*Global variables defined in shikshaConstant.php for mapping marks to board */
                $completionDate = !empty($data['xiiYear'])? new \DateTime($data['xiiYear'].'-01-01') : new \DateTime('0000-00-00');
            break;            

            case 'bachelors':
                $name = !empty($data['bachelorsDegree'])? $data['bachelorsDegree']:NULL;
                $level = 'UG';
                $board = !empty($data['bachelorsUniv'])? $data['bachelorsUniv']:NULL;;
                $instituteName = !empty($data['bachelorsCollege']) ? $data['bachelorsCollege']: NULL;
                $marks = !empty($data['bachelorsMarks']) ? $data['bachelorsMarks']: NULL;
                $marksType = 'percentage';
                $subject = !empty($data['bachelorsStream']) ? $data['bachelorsStream']: NULL;
                $specialization = !empty($data['bachelorsSpec']) ? $data['bachelorsSpec'] : NULL;
                /*Global variables defined in shikshaConstant.php for mapping marks to board */
                
                $completionDate = !empty($data['graduationCompletionYear'])? new \DateTime($data['graduationCompletionYear'].'-01-01') : new \DateTime('0000-00-00');
            break;

            case 'masters':
                $name = !empty($data['mastersDegree'])? $data['mastersDegree']:NULL;
                $level = 'PG';
                $board = !empty($data['mastersUniv'])? $data['mastersUniv']:NULL;;
                $instituteName = !empty($data['mastersCollege']) ? $data['mastersCollege']: NULL;
                $marks = !empty($data['mastersMarks']) ? $data['mastersMarks']: NULL;
                $marksType = 'percentage';
                $subject = !empty($data['mastersStream']) ? $data['mastersStream']: NULL;
                $specialization = !empty($data['mastersSpec']) ? $data['mastersSpec'] : NULL;
                /*Global variables defined in shikshaConstant.php for mapping marks to board */
                $completionDate = !empty($data['mastersCompletionYear'])? new \DateTime($data['mastersCompletionYear'].'-01-01') : new \DateTime('0000-00-00');
            break;

            case 'phd':
                $name = !empty($data['phdDegree'])? $data['phdDegree']:NULL;
                $level = 'PHD';
                $board = !empty($data['phdUniv'])? $data['phdUniv']:NULL;;
                $instituteName = !empty($data['phdCollege']) ? $data['phdCollege']: NULL;
                $marks = !empty($data['phdMarks']) ? $data['phdMarks']: NULL;
                $marksType = 'percentage';
                $subject = !empty($data['phdStream']) ? $data['phdStream']: NULL;
                $specialization = !empty($data['phdSpec']) ? $data['phdSpec'] : NULL;
                /*Global variables defined in shikshaConstant.php for mapping marks to board */
                $completionDate = !empty($data['phdCompletionYear'])? new \DateTime($data['phdCompletionYear'].'-01-01') : new \DateTime('0000-00-00');
            break;

            case 'xthWithoutSub':
                $name = '10';
                $level = '10';
                $board = isset($data['tenthBoard'])? $data['tenthBoard']: NULL;
                $instituteName = isset($data['xthSchool']) ? $data['xthSchool']: NULL;
                global $CBSE_Grade_Mapping;
                global $IGCSE_Grade_Mapping;
                global $IBMYP_Grade_Mapping;
                if($board == 'CBSE'){
                    $marks = $CBSE_Grade_Mapping[$data['tenthmarks']];
                }else if($board == 'ICSE'  || $board=='NIOS'){
                    $marks = $data['tenthmarks'];
                }else if($board == 'IGCSE'){
                    $marks = $IGCSE_Grade_Mapping[$data['tenthmarks']];
                }else if($board == 'IBMYP'){
                    $marks = $IBMYP_Grade_Mapping[$data['tenthmarks']];
                }

                if($data['tenthBoard'] == 'CBSE' || $data['tenthBoard'] == 'IGCSE'){
                    $marksType = 'grades';
                }else{
                    $marksType = 'marks';
                }

                $subject = NULL;
                $specialization = isset($data['xthSpecialization']) ? $data['xthSpecialization'] : NULL;
                /*Global variables defined in shikshaConstant.php for mapping marks to board */
                $completionDate = !empty($data['xthCompletionYear'])? new \DateTime($data['xthCompletionYear'].'-01-01') : new \DateTime('0000-00-00');
            break;
        }

        if($data['isStudyAbroad'] == 'yes' && $name == "GMAT" && $marks <= 0){
            mail('naveen.bhola@shiksha.com','GMAT scores appearing 0.0 '.date('Y-m-d H:i:s'), '<br/>Some data missing <br/>'.print_r($_POST, true));
        }

        $education->setName($name);
        $education->setLevel($level);
        $education->setMarks($marks);
        $education->setMarksType($marksType);
        $education->setCourseCompletionDate($completionDate);
        $education->setOngoingCompletedFlag($ongoingCompletedFlag);
        $education->setCourseSpecialization(0);
        $education->setCity(0);
        $education->setCountry(2);
        $education->setInstituteId(0);
        $education->setSubmitDate(new \DateTime);
        $education->setStatus('live');
        $education->setSubjects($subject);
        $education->setBoard($board);
        $education->setInstituteName($instituteName);
        $education->setSpecialization($specialization);
        $education->setExamGroupId($examGroupId);

    }
    
    /**
     * Get education levels present in data
     *
     * @param array $data
     * @return array
     */ 
    public static function getEducationLevelsInData($data = array())
    {
        $educationLevels = array();
        if($data['xiiStream']) {
            $educationLevels[] = 'xii';
        }
        if($data['12thBoard']){
            $educationLevels[] = '12th';
        }
        if($data['graduationDetails']) {
            $educationLevels[] = 'graduation';
        }
        if(is_array($data['exams']) && count($data['exams']) > 0) {
            if(!($data['isStudyAbroad'] == 'yes' && !empty($data['examTaken']) && ($data['examTaken'] == 'no' || $data['examTaken'] == 'bookedExamDate'))){     
                foreach($data['exams'] as $exam) {
                    $educationLevels[] = 'exam-'.$exam;
                }
            }
        }
        if(is_array($data['otherExams']) && count($data['otherExams']) > 0) {
            foreach($data['otherExams'] as $exam) {
                if($exam) {
                    $educationLevels[] = 'otherExam-'.$exam;
                }
            }
	    }

        //SA shiksha Apply
        if(is_array($data['CurrentSubjects']) && count($data['CurrentSubjects']) > 0) {
            foreach($data['CurrentSubjects'] as $subjects) {
                $educationLevels[] = 'subjects-'.$subjects;
            }
        }  

        if(isset($data['graduationStream']) && !empty($data['graduationStream'])){
             $educationLevels[] = 'PG';
        }

        if(isset($data['isRmcStudentprofile']) && $data['isRmcStudentprofile'] == 'yes'){
            if(!(is_array($data['CurrentSubjects']) && count($data['CurrentSubjects']) > 0) && !empty($data['tenthBoard'])){
                $educationLevels[] = 'RmcWithoutSub';
            } 

        }

        if(empty($data['CurrentSubjects']) && $data['tenthBoard'] || $data['xthSchool'] || $data['xthCompletionYear']){
            $educationLevels[] = 'xthWithoutSub';
        }

        foreach($data['EducationBackground'] as $index=>$value){
            if($value == 'xth' && !(is_array($data['CurrentSubjects']) && count($data['CurrentSubjects']) > 0)){
                $flag = ($data['tenthBoard'] || $data['xthSchool'] || $data['xthCompletionYear'] || is_array($data['CurrentSubjects']) );
                if($flag)
                $educationLevels[] = 'xthWithoutSub';
            }
            if($value == 'xiith'){
                $flag = ($data['xiithSchool'] || $data['xiiMarks'] || $data['xiiSpecialization'] || $data['xiiYear'] );
                if($flag)
                $educationLevels[] = 'xiith';
            }
            if($value == 'bachelors'){
                $flag = ($data['bachelorsDegree'] || $data['bachelorsCollege'] || $data['bachelorsUniv'] || $data['bachelorsMarks'] || $data['bachelorsStream'] || $data['bachelorsSpec'] || $data['graduationCompletionYear'] );
                if($flag)
                $educationLevels[] = 'bachelors';
            }
            if($value == 'masters'){
                $flag = ($data['mastersDegree'] || $data['mastersUniv'] || $data['mastersCollege'] || $data['mastersMarks'] || $data['mastersStream'] || $data['mastersSpec'] || $data['mastersCompletionYear'] );
                if($flag)
                $educationLevels[] = 'masters';
            }
            if($value == 'phd'){
                $flag = ($data['phdDegree'] || $data['phdUniv'] || $data['phdCollege'] || $data['phdMarks'] || $data['phdStream'] || $data['phdSpec'] || $data['phdCompletionYear'] );
                if($flag){
                $educationLevels[] = 'phd';
                }
            }
        }
       // error_log("==shiksha== education levels in data pop ===".print_r($educationLevels, true));
        $educationLevels = array_unique($educationLevels);
        return $educationLevels;
    }
}

<?php

/**
 * Class for Competitive Exam fields
 */
namespace registration\libraries;


/**
 * Class for Competitive Exam fields
 */
class CompetitiveExam
{
    /**
     * Field for ID
     * @var integer
     */
    private $id;
    
     /**
      * Field for name
     * @var string
     */
    private $name;
    /**
    * Field for scoretype 
    * @var string
    */
    private $scoreType;
     /**
      * Field for minScore
     * @var integer
     */
    private $minScore;
     /**
      * Field for max score
     * @var integer
     */
    private $maxScore;
     /**
      * Field for has Passing year
     * @var boolean
     */
    private $hasPassingYear;
     /**
      * Field for state
     * @var string
     */
    private $state;
     /**
      * Field for score
     * @var integer
     */
    
    private $score;
     /**
      * Field for scoreTypeUser
     * @var 
     */
    private $scoreTypeUser;
     /**
      * Field for passingyear
     * @var string
     */
    private $passingYear;
    
     /**
      * Field for examconfig
     * @var 
     */
    private $examConfig;
    
    /**
     * Field
     * @var 
     */
    private $nameBox;
            
    /**
     * Constructor function
     * @param integer $id
     * @param array $data
     */ 
    function __construct($id,$data= array())
    {
        $this->id = $id;
        $this->setData($data);
    }
    
    /**
     * Setter function to populate the data to class members
     * @param array $data
     */
    public function setData($data)
    {
        foreach($data as $key => $value) {
            if($key != 'id') {
                if($key == 'Marks') {
                    $this->score = $value;
                }
                else if($key == 'CourseCompletionDate') {
                    $this->passingYear = $value;
                }
                else if($key == 'MarksType') {
                    $this->scoreTypeUser = $value;
                }
                else {
                    $this->$key = $value;
                }
            }
        }
    }
    
    /**
     * Getter function for Name
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Getter function for Name(Display Name)
     * @return string name
     */
    public function getDisplayNameForUser()
    {
        return $this->name;
    }
    
    /**
     * Getter function for Name(Display Name for Enterprise)
     * @return string name
     */
    public function getDisplayNameForEnterprise()
    {
        return $this->id == 'NOEXAM' ? 'No Exam Required' : $this->name;
    }
    
    /**
     * Getter function for Score type
     * @return string scoreType
     */
    public function getScoreType()
    {
        return $this->scoreType;
    }
    
    /**
     * Getter function for min score
     * @return string minScore
     */
    public function getMinScore()
    {
        return $this->minScore;
    }
    
    
    /**
     * Getter function for max score
     * @return string maxScore
     */
    public function getMaxScore()
    {
        return $this->maxScore;
    }
    
    
    /**
     * Getter function for min score
     * @return boolean hasPassingYear
     */
    public function hasPassingYear()
    {
        return $this->hasPassingYear == 'yes' ? TRUE : FALSE;
    }
    
    /**
     * Getter function for State
     * @return string state
     */
    public function getState()
    {
        return $this->state;
    }
    
    /**
     * Getter function for min score
     * @return boolean hasPassingYear
     */
    public function hasNameBox()
    {
        return $this->nameBox;
    }
    
    /**
     * Function to get the Passing year values
     * @return array $passingYearValues
     */
    public function getPassingYearValues()
    {
        $passingYearValues = array();
        
        if($this->id == 'MAT') {
            $passingYearValues[date('Y-02-01',strtotime('-1 Year'))] = 'Feb '.date('Y',strtotime('-1 Year'));
            $passingYearValues[date('Y-05-01',strtotime('-1 Year'))] = 'May '.date('Y',strtotime('-1 Year'));
            $passingYearValues[date('Y-09-01',strtotime('-1 Year'))] = 'September '.date('Y',strtotime('-1 Year'));
            $passingYearValues[date('Y-12-01',strtotime('-1 Year'))] = 'December '.date('Y',strtotime('-1 Year'));
            $passingYearValues[date('Y-02-01')] ='Feb '.date('Y');
            $passingYearValues[date('Y-05-01')] ='May '.date('Y');
            $passingYearValues[date('Y-09-01')] ='September '.date('Y');
            $passingYearValues[date('Y-12-01')] ='December '.date('Y');
            $passingYearValues[date('Y-02-01',strtotime('+1 Year'))] = 'Feb '.date('Y',strtotime('+1 Year'));
            $passingYearValues[date('Y-05-01',strtotime('+1 Year'))] = 'May '.date('Y',strtotime('+1 Year'));
            $passingYearValues[date('Y-09-01',strtotime('+1 Year'))] = 'September '.date('Y',strtotime('+1 Year'));
            $passingYearValues[date('Y-12-01',strtotime('+1 Year'))] = 'December '.date('Y',strtotime('+1 Year'));
        }
        else if($this->id == 'CMAT') {
            $passingYearValues[date('Y-02-01',strtotime('-1 Year'))] = 'Feb '.date('Y',strtotime('-1 Year'));
            $passingYearValues[date('Y-09-01',strtotime('-1 Year'))] = 'September '.date('Y',strtotime('-1 Year'));
            $passingYearValues[date('Y-02-01')] ='Feb '.date('Y');
            $passingYearValues[date('Y-09-01')] ='September '.date('Y');
            $passingYearValues[date('Y-02-01',strtotime('+1 Year'))] = 'Feb '.date('Y',strtotime('+1 Year'));
            $passingYearValues[date('Y-09-01',strtotime('+1 Year'))] = 'September '.date('Y',strtotime('+1 Year'));
        }
        else {
            $passingYearValues[date('Y-01-01',strtotime('-1 Year'))] = date('Y',strtotime('-1 Year'));
            $passingYearValues[date('Y-01-01')] = date('Y');
            $passingYearValues[date('Y-01-01',strtotime('+1 Year'))] = date('Y',strtotime('+1 Year'));
        }
        
        return $passingYearValues;
    }
    
    /**
     * Function to display the exam name
     *
     * @param boolean $examNameBold
     *
     * @return string $display
     */
    public function displayExam($examNameBold = FALSE)
    {
        $examName = $this->id == 'NOEXAM' ? "Don't want to take any exam" : $this->name;
        if(!$examName) {
            $examName = $this->id;
        }
        
        $examYear = '';
        if($this->passingYear && $this->passingYear != '0000-00-00 00:00:00') {
            if($this->name == 'MAT' || $this->name == 'CMAT') {
                $examYear = date('M Y',strtotime($this->passingYear));
            }
            else {
                $examYear = date('Y',strtotime($this->passingYear));
            }
        }
        
        if($examNameBold) {
            $display = '<b>'.$examName.'</b>';
        }
        else {
            $display = $examName;
        }
        if($this->id != 'NOEXAM') {
            if($this->score || $examYear) {
                $display .= ' (';
            }
            if($this->score) {
                global $examGrades;
                global $examFloat;
                if(isset($examGrades[$examName])) {
                    $this->score = $examGrades[$examName][(int)$this->score];
                    $display .= $this->score.' Grade';
                }
                else if(isset($examFloat[$examName])){
                    $display .= $this->score.' '.($this->scoreType ? $this->scoreType : $this->scoreTypeUser);
                }
                else {
                    $score = (int)$this->score;
                    $display .= $score.' '.($this->scoreType ? $this->scoreType : $this->scoreTypeUser);
                }
            }
            if($examYear) {
                if($this->score) {
                    $display .= ', ';
                }
                $display .= $examYear;
            }
        
            if($this->score || $examYear) {
                $display .= ')';
            }
        }
        
        return $display;
    }

    /**
     * Function to display the exam name
     *
     * @param boolean $examNameBold
     *
     * @return string $display
     */
    public function displayExamName($examNameBold = FALSE)
    {
        $examName = $this->id == 'NOEXAM' ? "Don't want to take any exam" : $this->name;
        if(!$examName) {
            $examName = $this->id;
        }
        
        $examYear = '';
        if($this->passingYear && $this->passingYear != '0000-00-00 00:00:00') {
            if($this->name == 'MAT' || $this->name == 'CMAT') {
                $examYear = date('M Y',strtotime($this->passingYear));
            }
            else {
                $examYear = date('Y',strtotime($this->passingYear));
            }
        }
        
        if($examNameBold) {
            $display = '<b>'.$examName.'</b>';
        }
        else {
            $display = $examName;
        }
        
        return $display;
    }

}
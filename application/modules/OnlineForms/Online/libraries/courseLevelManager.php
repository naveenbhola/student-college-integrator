<?php

class CourseLevelManager{
    
    private $dao;
    private $currentCourseLevel;
    private $cookie;
    
    
    function __construct(){
        $this->CI = & get_instance();
        $this->CI->load->model('Online/onlineparentmodel');
        $this->CI->load->model('Online/onlinemodel');
        $this->dao = new OnlineModel();
        $this->cookie = $_COOKIE['currentCourseLevel'];
        $user = $this->CI->checkUserValidation();
        if($user != "false"){
            $this->userId = $user[0]['userid'];
        }
        
        $this->currentCourseLevel = $this->cookie?$this->cookie:$this->dao->getUserCurrentLevel($this->userId);
        if(!$this->currentCourseLevel){
            $this->currentCourseLevel = "PG";
        }
        
        $this->dao->setUserCurrentLevel($this->userId,$this->currentCourseLevel);
        
        $this->setOnlineFormURL();
    }
    
    public function setNewLevel($level){
        $this->currentCourseLevel = $level;
        $this->dao->setUserCurrentLevel($this->userId,$level);
        setcookie('currentCourseLevel',$level,0,'/',COOKIEDOMAIN);
        $_COOKIE['currentCourseLevel'] = $level;
        $this->setOnlineFormURL();
    }
    
    public function getCurrentLevel(){
        return $this->currentCourseLevel;
    }
    
    public function setOnlineFormURL(){
        if($this->currentCourseLevel == "PG"){
            $GLOBALS['SHIKSHA_ONLINE_FORMS_HOME'] = SHIKSHA_HOME . '/mba/resources/application-forms';
        }elseif($this->currentCourseLevel == "UG"){
            $GLOBALS['SHIKSHA_ONLINE_FORMS_HOME'] = SHIKSHA_HOME . '/college-admissions-engineering-online-application-forms';
        }
    }
    
    public function getCurrentDepartment(){
        global $onlineFormsDepartments;
        if($this->currentCourseLevel){
            foreach($onlineFormsDepartments as $key=>$department){
                if($department['level'] == $this->currentCourseLevel){
                    return $key;
                }
            }
        }
    }
    
    public function setLevelByDepartmentId($deptID){
        global $onlineFormsDepartments;
         foreach($onlineFormsDepartments as $key=>$department){
            if($department['id'] == $deptID){
                $this->setNewLevel($department['level']);
            }
        }
    }
    
    
}
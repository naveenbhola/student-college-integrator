<?php
Class ExamRedirect {
    private $CI;
    
    function __construct() {
        $this->CI =& get_instance();
        $this->examPageLib = $this->CI->load->library('examPages/ExamPageLib');
    }

    // Description : redirect 301 only for old mba exam page url like (mba/exam/cat) to new exam url
    function redirectMBAExam($examName, $subCategory, $sectionName){
        if(!empty($sectionName) && !is_numeric($sectionName)){
            $sectionName = '/'.$sectionName;
        }

        $this->examRequest = $this->CI->load->library('examPages/ExamPageRequest');
        $this->examRequest->setExamName($examName);
        $url = $this->examRequest->getUrl('',true);

        if(empty($url)){
            $url = SHIKSHA_HOME.'/'.$subCategory.'/exams/'.$this->formateExam($examName).$sectionName;
        }else{
            $url = $url.$sectionName;
        }
        header("Location: $url",TRUE,301);exit();  
    }

    // Description : redirect 301 for old other exam page like (jee-mains-exampage) to new url
    function redirectOtherExam($param){
        
        $exampagemodel = $this->CI->load->model("examPages/exampagemodel");
        $this->CI->load->config("examPages/examPageConfig");
        $validSectionNames = $this->CI->config->item('examPagesActiveSections');
        foreach($validSectionNames as $validName => $sectionInUrlFormat) {
            $pos = strpos($param, $sectionInUrlFormat);
            if($pos>0) {
                $sectionName = $sectionInUrlFormat;
                break;
            }
        }

        $tempSectionName =  ($sectionName == 'dates') ? 'important-dates' : $sectionName;
        
        $examName = rtrim(str_replace($tempSectionName, '', $param),'-'); 
        $param = $examName;
        if(!empty($tempSectionName) && !is_numeric($tempSectionName)){
            $param = $param.'@'.$tempSectionName;
        }
        $this->examRequest        = $this->CI->load->library('examPages/ExamPageRequest',$param);
        $this->examRequest->validateUrl($param);
    }

    // Description - this function will 301 redirect from old exam list page like (mba/engineering/design/law) to new exam list page
    public function examList($parameter)
    {
        $parameter = strtolower($parameter);
        $this->CI->load->config('examPages/examPageConfig');
        $examSteamMapping = $this->CI->config->item('examMapping');
        if(empty($examSteamMapping[$parameter])){
            show_404();
        }
        // old exam list page will be redirect to new url
        if($parameter == 'mba'){
            $url = SHIKSHA_HOME.'/'.$parameter.'/exams-pc-'.$examSteamMapping[$parameter]; 
        }else if($parameter == 'engineering'){
            $url = SHIKSHA_HOME.'/b-tech/exams-pc-'.$examSteamMapping[$parameter]; 
        }else{
            $url = SHIKSHA_HOME.'/'.$parameter.'/exams-st-'.$examSteamMapping[$parameter];     
        }
        header("Location: $url",TRUE,301);exit();
    }

    function formateExam($title, $words=30){
        return strtolower(seo_url($title, "-", $words));
    }
}

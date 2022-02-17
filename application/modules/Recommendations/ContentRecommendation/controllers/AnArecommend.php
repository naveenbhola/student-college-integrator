<?php

class AnArecommend extends MX_Controller{

	private $_CI;
	
    public function __construct() {
        parent::_construct();
        $this->_CI = & get_instance();
        $this->_CI->load->library('ContentRecommendation/AnAPreprocess');
        $this->anarecommendmodel = $this->_CI->load->model('ContentRecommendation/anarecommendationmodel');
        $this->_CI->load->helper('ContentRecommendation/recommend');
    }

    /*
    * Cron to update AnA content in AnARecommendation table
    */
    public function InstituteAnACron(){
        $this->validateCron();
        $lastTime = $this->anarecommendmodel->getLastProcessedTimeWindow();

        if($lastTime!=''){

            $windows = getTimeWindowsToProcess($lastTime);
            $times = array();
            
            foreach ($windows as $window) {

                $lastProcessedTime = explode(';',$window)[1];
                $cronId = $this->anarecommendmodel->registerCron($lastProcessedTime);
                
                if($cronId){
                    if($this->_CI->anapreprocess->updateInstituteAnARecommendation($window)){
                        
                        $this->anarecommendmodel->updateCron($cronId,'success');
                    }
                    else{
                        $this->anarecommendmodel->updateCron($cronId,'failed');
                    }
                }
            }
        }
        
    }

    /*
    * Function to migrate AnA content for last 10 years to AnARecommendation table 
    */
    public function populateInstituteAnAdb(){
        
        $lastProcessedTime = date("Y-m-d H:00:00");
        
        $time = new DateTime('2005-01-01');
        
        $timeWindow = ($time->format('Y-m-d H:00:00')).";".$lastProcessedTime;
        
        $cronId = $this->anarecommendmodel->registerCron($lastProcessedTime);
        
        if($cronId){
            if($this->anapreprocess->updateInstituteAnARecommendation($timeWindow)){

                $this->anarecommendmodel->updateCron($cronId,'success');
            }
            else{
                $this->anarecommendmodel->updateCron($cronId,'failed');
            }
        }        
    }

}

<?php

require_once APPPATH.'/modules/User/searchAgents/libraries/LeadConsumptionReportGenerator/LeadConsumptionReportGeneratorAbstract.php';

class LeadConsumptionReportGeneratorAbroad extends LeadConsumptionReportGeneratorAbstract
{    
    private static $fields = array(
            'User ID', 'Email', 'Course', 'City', 'Country', 'Consumption', 'Porting Consumption'
        );
    
    function __construct($model)
    {
        parent::__construct($model);
    }
    
    public function getFields()
    {
        return self::$fields;
    }
     
    public function getLeadConsumptionData()
    {
        /**
         * Date range
         */ 
        if($this->dateFrom && $this->dateTo) {
            $dateFrom = $this->dateFrom;
            $dateTo = $this->dateTo;
        }
        else {
            /**
             * Last 15 days data
             */ 
            $dateFrom = date('Y-m-d',strtotime('-15 day'));
            $dateTo = date('Y-m-d');
        }
        
        /**
         * Generate maps for course, city and locality
         */ 
        $maps = $this->model->getLeadConsumptionMaps();
        
        /**
         * Leads generated during this interval
         */ 
        $leadsGenerated = $this->model->getAbroadLeadsGenerated($dateFrom,$dateTo);
                
        /**
         * Leads consumed during this interval
         */
        $leadsConsumed = $this->model->getLeadsViewCounts($dateFrom,$dateTo);
        $leadsConsumedByPorting = $this->model->getLeadsConsumedByPorting($dateFrom,$dateTo);
        
        $data = array();
        
        foreach($leadsGenerated as $lead) {
            
            $scope = 'studyabroad';
            $courseId = intval($lead['DesiredCourse']);
            $cityId = intval($lead['city']);
            $countryId = intval($lead['countryId']);
            
            $userId = $lead['UserId'];
            $email = $lead['email'];
            
            if(!$maps['country'][$countryId] || !$maps['city'][$cityId] || !$maps['course'][$scope][$courseId]) {
                continue;
            }
            
            $dataRow = array();
            $dataRow[] = $userId;
            $dataRow[] = $email;
            $dataRow[] = $maps['course'][$scope][$courseId]['course'];
            $dataRow[] = $maps['city'][$cityId];
            $dataRow[] = $maps['country'][$countryId];
            $dataRow[] = intval($leadsConsumed[$userId]);
            $dataRow[] = intval($leadsConsumedByPorting[$userId]);
            
            $data[] = $dataRow;
        }
        
        return $data;
    }    		    	
}

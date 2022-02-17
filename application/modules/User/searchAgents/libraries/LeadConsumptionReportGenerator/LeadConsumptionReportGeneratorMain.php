<?php

require_once APPPATH.'/modules/User/searchAgents/libraries/LeadConsumptionReportGenerator/LeadConsumptionReportGeneratorAbstract.php';

class LeadConsumptionReportGeneratorMain extends LeadConsumptionReportGeneratorAbstract
{    
    private static $fields = array(
            'Lead Type', 'Course', 'Sub-category', 'City', 'Locality', 'Country', 'No. of active genies',
            'Leads generated', 'Leads consumed', 'Avg consumption per lead', 'Modal consumption per lead'
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
         * Fetch currently active genies
         */ 
        $groupedGenies = $this->_getGroupedActiveGenies($maps);
        
        /**
         * Leads generated during this interval
         */ 
        $leadsGenerated = $this->model->getLeadsGenerated($dateFrom,$dateTo);
        /**
         * Group generated leads by course, city and locality
         */
        $groupedLeadsGenerated = array();
        $leadMap = array();
        $leadGroupMap = array();
        
        foreach($leadsGenerated as $lead) {
            
            $leadId = intval($lead['UserId']);
            $courseId = intval($lead['DesiredCourse']) > 0 ? intval($lead['DesiredCourse']) : 0;
            $testprepCourseId = intval($lead['blogid']) > 0 ? intval($lead['blogid']) : 0;
            $cityId = intval($lead['city']) > 0 ? intval($lead['city']) : 0;
            $localityId = intval($lead['Locality']) > 0 ? intval($lead['Locality']) : 0;
            $countryId = intval($lead['countryId']) > 0 ? intval($lead['countryId']) : 0;
            $flag = $lead['ExtraFlag'];
            
            if($cityId && ($courseId || $testprepCourseId)) {
                if($flag == 'studyabroad') {
                    /** locality should not be considered for study abroad
                     *  will result in a key mismatch between leads and genies
                     */ 
                    $group = "studyabroad:".$courseId.":".$cityId.":0:".$countryId;
                    
                    /** Add one for aggregate on city-level **/
                    $group_agg = "studyabroad:".$courseId.":".$cityId.":0:0";
                }
                else if($flag == 'testprep') {
                    $group = 'testprep:'.$testprepCourseId.":".$cityId.":".$localityId.":0";
                }
                else {
                    $group = 'national:'.$courseId.":".$cityId.":".$localityId.":0";
                }
                
                /**
                 * Make sure we add a lead to a group only once
                 */ 
                if(!array_key_exists($group, $leadGroupMap)) {
                    $leadGroupMap[$group] = array();
                }
                if(!array_key_exists($leadId, $leadGroupMap[$group])) {
                    $groupedLeadsGenerated[$group]++;
                    $leadGroupMap[$group][$leadId] = TRUE;
                }
                
                if($group_agg) {
                    if(!array_key_exists($group_agg, $leadGroupMap)) {
                        $leadGroupMap[$group_agg] = array();
                    }
                    if(!array_key_exists($leadId, $leadGroupMap[$group_agg])) {
                        $groupedLeadsGenerated[$group_agg]++;
                        $leadGroupMap[$group_agg][$leadId] = TRUE;
                    }
                }
                
                /**
                 * For study abroad, a lead can be counted in multiple groups
                 * due to multiple preferred countires being selected
                 */ 
                if($flag == 'studyabroad') {
                    if(!array_key_exists($leadId, $leadGroupMap[$group])) {
                        $leadMap[$lead['UserId']][] = $group;
                    }
                    if($group_agg && !array_key_exists($leadId, $leadGroupMap[$group_agg])) {
                        $leadMap[$lead['UserId']][] = $group_agg;
                    }
                }
                else {
                    $leadMap[$lead['UserId']] = array($group);
                }
            }
        }
        
        /**
         * Leads consumed during this interval
         */
        if($this->deliveryType == 'porting') {
            $leadsConsumed = $this->model->getLeadsConsumedByPorting($dateFrom,$dateTo);
        }
        else {
            $leadsConsumed = $this->model->getLeadsConsumed($dateFrom,$dateTo);
        }
        $groupedLeadsConsumed = array();
        $groupedLeadsTotalConsumption = array();
        $groupedLeadsConsumptionFrequency = array();
        
        /**
         * Group leads consumed
         * Gather necessary data to compute average consumption and modal consumption
         */ 
        foreach($leadsConsumed as $lead) {
            if($leadMap[$lead['userid']]) {
                foreach($leadMap[$lead['userid']] as $leadGroupId) {
                    $groupedLeadsConsumed[$leadGroupId]++;
                    $groupedLeadsTotalConsumption[$leadGroupId] += $lead['num'];
                    $groupedLeadsConsumptionFrequency[$leadGroupId][$lead['num']]++;
                }
            }
        }
        
        $data = array();
        
        foreach($groupedLeadsGenerated as $group => $numLeads) {
            list($scope,$courseId,$cityId,$localityId,$countryId) = explode(":",$group);
            
            if(!$maps['city'][$cityId] || !$maps['course'][$scope][$courseId]) {
                continue;
            }
            
            $dataRow = array();
            $dataRow[] = $scope;
            $dataRow[] = $maps['course'][$scope][$courseId]['course'];
            $dataRow[] = $maps['course'][$scope][$courseId]['category'];
            $dataRow[] = $maps['city'][$cityId];
            $dataRow[] = $maps['locality'][$localityId];
            $dataRow[] = $countryId > 2 ? $maps['country'][$countryId] : '';
            $dataRow[] = $groupedGenies[$group];
            $dataRow[] = $numLeads;
            $dataRow[] = $groupedLeadsConsumed[$group];
            
            if($groupedLeadsConsumed[$group]) {
                /**
                 * Compute average and modal consumption for group
                 */ 
                $averageConsumptionPerLead = $groupedLeadsTotalConsumption[$group] / $groupedLeadsConsumed[$group];
                
                /**
                 * Sort by consumption frequency
                 */ 
                arsort($groupedLeadsConsumptionFrequency[$group]);
                $highestConsumption = reset($groupedLeadsConsumptionFrequency[$group]);
                
                $countsWithHighestConsumption = array();
                foreach($groupedLeadsConsumptionFrequency[$group] as $kk => $vv) {
                    if($vv == $highestConsumption) {
                        $countsWithHighestConsumption[] = $kk;
                    }
                }

                $modalConsumptionPerLead = max($countsWithHighestConsumption);
                
                $dataRow[] = number_format($averageConsumptionPerLead,1);
                $dataRow[] = $modalConsumptionPerLead;
            }
            else {
                $dataRow[] = "";
                $dataRow[] = "";
            }
            
            $data[] = $dataRow;
        }
        
        return $data;
    }
    
    /**
     * Currently active genies
     * grouped by course, current city and current locality
     */ 
    private function _getGroupedActiveGenies($maps)
    {
        /**
         * Fetch active genies
         */
        if($this->deliveryType == 'porting') {
            $activeGenies = $this->model->getActivePortingLeadGenies();
        }
        else {
            $activeGenies = $this->model->getActiveLeadGenies();
        }
	
        /**
         * Grouping
         */ 
        $groupedGenies = array();
        foreach($activeGenies as $genie) {
            
            $cityIds = $genie['currentlocation'];
            $localityIds = $genie['currentlocality'];
            $countryIds = $genie['country'];
            $courseIds = $genie['testprep'] ? $genie['testprep'] : $genie['desiredcourse'];
            $isTestPrep = $genie['testprep'] ? TRUE : FALSE;	   
     
            if(is_array($courseIds)) {
                $courseIds = array_unique($courseIds);
            }
            if(is_array($cityIds)) {
                $cityIds = array_unique($cityIds);
            }
            if(is_array($localityIds)) {
                $localityIds = array_unique($localityIds);
            }
            if(is_array($countryIds)) {
                $countryIds = array_unique($countryIds);
            }
            
            foreach($courseIds as $courseId) {
                $scope = $isTestPrep ? 'testprep' : ( $maps['course']['studyabroad'][$courseId] ? 'studyabroad' : 'national' );
                
                foreach($cityIds as $cityId) {
                    $group = $scope.':'.$courseId.':'.$cityId.':0:0';
                    $groupedGenies[$group]++;
                    foreach($localityIds as $localityId) {
                        if(intval($localityId) > 0) {
                            $group = $scope.':'.$courseId.":".$cityId.':'.$localityId.':0';
                            $groupedGenies[$group]++;
                        }
                    }
                    foreach($countryIds as $countryId) {
                        if(intval($countryId) > 0) {
                            $group = $scope.':'.$courseId.":".$cityId.':0:'.$countryId;
                            $groupedGenies[$group]++;
                        }
                    }
                }
            }
        }
        //_p($groupedGenies); exit();
        return $groupedGenies;
    }		    		    	
}

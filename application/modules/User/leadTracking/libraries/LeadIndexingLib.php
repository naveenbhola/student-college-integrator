<?php

class LeadIndexingLib
{
    private $CI;
    private $searchCriteria;
    private $requestParamGlobal = array();

    function __construct()
    {
        $this->CI = & get_instance();
    }
    
    public function getAllAttributeIdsWithNames(){
        $leadtrackingmodel = $this->CI->load->model('leadTracking/leadtrackingmodel');

        $stream_id_Data = $leadtrackingmodel->getAllStreamsWithIds();
        $substream_id_Data = $leadtrackingmodel->getAllSubStreamsWithIds();
        $specialization_id_Data = $leadtrackingmodel->getAllSpecializationsWithIds();
        $base_course_Data = $leadtrackingmodel->getAllBaseCoursesWithIds();
        $mode_Data = $leadtrackingmodel->getAllModesWithIds();
        $city_Data = $leadtrackingmodel->getAllCityWithIds();
        $state_Data = $leadtrackingmodel->getAllStateWithIds();
        $country_Data = $leadtrackingmodel->getAllCountryWithIds();
        $locality_Data = $leadtrackingmodel->getAllLocalityWithIds();
        $desired_course_data = $leadtrackingmodel->getAllDesiredCourseWithIds();

        $master_data = array();
        

        foreach ($stream_id_Data as $val) {
            $master_data['stream'][$val['stream_id']] = $val['name'];
        }

        
        foreach ($substream_id_Data as $val) {
            $master_data['substream'][$val['substream_id']] = $val['name'];
        }

        foreach ($specialization_id_Data as $val) {
            $master_data['specialization'][$val['specialization_id']] = $val['name'];
        }

        foreach ($base_course_Data as $val) {
            $master_data['basecourse'][$val['base_course_id']] = $val['name'];
        }

        foreach ($mode_Data as $val) {
            $master_data['mode'][$val['value_id']] = $val['value_name'];
        }

        foreach ($state_Data as $val) {
            $master_data_temp[$val['state_id']] = $val['state_name'];
        }

        foreach ($city_Data as $val) {
            $master_data['city'][$val['city_id']] = $val['city_name'];
            $master_data['state'][$val['city_id']] = $master_data_temp[$val['state_id']];
            $master_data['tier'][$val['city_id']] = $val['tier'];
        }

        unset($master_data_temp);

        foreach ($country_Data as $val) {
            $master_data['country'][$val['country_id']] = $val['country_name'];
        }
        
        foreach ($locality_Data as $val) {
            $master_data['locality'][$val['localityId']] = $val['localityName'];
        }

        foreach ($desired_course_data as $val) {
            $master_data['desired_course'][$val['desired_id']] = $val['desired_name'];
        }
        return $master_data;
    }
    
    public function getLeadDeliveryData($allocation_data){
        $itr=0;
        
        while ($itr < $allocation_data['allocation_count']) {

            $sla_ids[] = $allocation_data['allocation_insert_id'] + $itr*2;

            $itr = $itr+1;
        }

        if($itr == 0){
            return;
        }

        $leadtrackingmodel = $this->CI->load->model('leadTracking/leadtrackingmodel');
        $actual_lead_delivery_data = $leadtrackingmodel->getActualLeadDeliveryData($sla_ids);

        $final_allocated_genie = $leadtrackingmodel->getFinalAllocatedGenies($sla_ids);

        $first_sla_id =0 ;
        $last_sla_id = 0;

        foreach ($actual_lead_delivery_data as $del_data) {
            if( ($del_data['slaId']<$first_sla_id || $first_sla_id<1)  && $del_data['auto_download'] =='YES'){
                $first_sla_id = $del_data['slaId'];
                $return_data['partial_sms_time'] = $del_data['smsTime'];
                $return_data['partial_email_time'] = $del_data['mailTime'];
            }

            
            if( ($del_data['slaId']>$last_sla_id )  && $del_data['auto_download'] =='YES'){
                $last_sla_id = $del_data['slaId'];
                $return_data['full_sms_time'] = $del_data['smsTime'];
                $return_data['full_email_time'] = $del_data['mailTime'];
            }

        }

        $return_data['final_allocated_genie'] = array();

        foreach ($final_allocated_genie as $genie) {
            $return_data['final_allocated_genie'][] = $genie['agentid'];    
        }

        return $return_data;
    }    

    public function formatTrackingData($tracking_data, $master_data){
        $this->master_map_id_with_values = $master_data;
        unset($master_data);
        
        $profileCounter = 1;
        $loopFlag       = true;

        $commonDataForProfiles['user_id'] = $tracking_data['userId'];
        $commonDataForProfiles['exclude_genie_ids'] = unserialize($tracking_data['excludeGenieIds']);

        if( count($commonDataForProfiles['exclude_genie_ids'])<1 ||  $commonDataForProfiles['exclude_genie_ids'] ==''){
            unset($commonDataForProfiles['exclude_genie_ids']);
        }
       
        if($tracking_data['totalViewCountReached']){
            $commonDataForProfiles['user_global_limit_reached'] = $tracking_data['totalViewCountReached'];
        }

        $commonDataForProfiles['extra_flag']            = $tracking_data['ExtraFlag'];
        $commonDataForProfiles['cron_type']             = $tracking_data['cronType'];
        $commonDataForProfiles['user_creation_time']    = str_replace(' ', 'T', $tracking_data['userCreationDate']);
        $commonDataForProfiles['last_login_time']       = str_replace(' ', 'T', $tracking_data['lastLoginTime']);
        $commonDataForProfiles['pref_submit_date']      = str_replace(' ', 'T', $tracking_data['prefSubmitDate']);

        
        $commonDataForProfiles['total_allocation_time'] = (float) $tracking_data['totalAllocationTime'];

        $commonDataForProfiles['user_pick_time'] = str_replace(' ', 'T', $tracking_data['userPickTime']);
        $commonDataForProfiles['cron_start_time'] = str_replace(' ', 'T', $tracking_data['cronStartTime']);

        unset($commonDataForProfiles['userPickTime']);
        unset($commonDataForProfiles['cronStartTime']);

        //if user_pick_time missing (for old data)
        if($commonDataForProfiles['cron_start_time'] == ''){
            $commonDataForProfiles['cron_start_time'] = $commonDataForProfiles['user_pick_time'];
        }

        $distinctProfiles = array();

        while($loopFlag){
            if(count($tracking_data['Profile_'.$profileCounter])>0){
                $distinctProfiles[] = $tracking_data['Profile_'.$profileCounter];
                unset($commonDataForProfiles['Profile_'.$profileCounter]);
                $profileCounter++;

            }else{
                $loopFlag= false;
            }
        }

        unset($tracking_data);
    
        foreach ($distinctProfiles as $key => $serialized_profile) {
            
            $profiles = unserialize($serialized_profile);
        
            $index_profile = array();

            $user_profile_data = unserialize($profiles['profile']);
            
            $pref_country = $user_profile_data['locationPrefCountryId'];

            $index_profile['profile'] = json_encode($user_profile_data);

            if($user_profile_data['ProfileType'] != ''){
                $index_profile['profile_type']         = $user_profile_data['ProfileType'];
            }
           
            if($user_profile_data['streamId']>0){
                $index_profile['stream_id']         = $user_profile_data['streamId'];
                $index_profile['stream_name']       = $this->master_map_id_with_values['stream'][$user_profile_data['streamId']];
            }

            if(count($user_profile_data['subStreamId'])>0){
                
                foreach ($user_profile_data['subStreamId'] as $subStreamId) {
                    $index_profile['substream_id'][] = $subStreamId;;
                    $index_profile['substream_name'][] = $this->master_map_id_with_values['substream'][$subStreamId];
                    
                }
                unset($temp_array);
            }

            if(count($user_profile_data['specialization'])>0){
                
                foreach ($user_profile_data['specialization'] as $spezId) {
                    $index_profile['specialization'][] = $spezId;
                    $index_profile['specialization_name'][] = $this->master_map_id_with_values['specialization'][$spezId];
                    
                }
                unset($temp_array);
            }

            if(count($user_profile_data['attributeValues'])>0){
                foreach ($user_profile_data['attributeValues'] as $mode) {
                    $index_profile['mode'][] = $mode;
                    $index_profile['mode_name'][] = $this->master_map_id_with_values['mode'][$mode];
                }
                unset($temp_array);
            }

            if(count($user_profile_data['courseId'])>0){
                
                foreach ($user_profile_data['courseId'] as $course) {
                    $index_profile['base_course'][] = $course;
                    $index_profile['base_course_name'][] = $this->master_map_id_with_values['basecourse'][$course];
                }

                unset($temp_array);
            }
            
            if($user_profile_data['desiredCourse'][0]>0){
                $index_profile['desired_course']            = $user_profile_data['desiredCourse'][0];
                $index_profile['desired_course_name']       = $this->master_map_id_with_values['desired_course'][$user_profile_data['desiredCourse'][0]];
            }

            if($pref_country[0]>0){
                foreach ($pref_country as $country_id) {
                    $index_profile['user_country_name'][]       = $this->master_map_id_with_values['country'][$country_id];
                }
            }

            if($user_profile_data['city']>0){
                $index_profile['user_city']                 = $user_profile_data['city'];
                $index_profile['user_city_name']            = $this->master_map_id_with_values['city'][$user_profile_data['city']];

                $index_profile['user_state_name']            = $this->master_map_id_with_values['state'][$user_profile_data['city']];

                if($index_profile['user_state_name'] == '' || empty($index_profile['user_state_name'])){
                    unset($index_profile['user_state_name']);
                }
            }
            
            if($user_profile_data['locality']>0){
                $index_profile['user_locality']             = $user_profile_data['locality'];
                $index_profile['user_locality_name']        = $this->master_map_id_with_values['locality'][$user_profile_data['locality']];
            }
            
            if($user_profile_data['SmsCredit']>0){
                $index_profile['deduct_credit_sms']         = $user_profile_data['SmsCredit'];
            }
            
            if($user_profile_data['ViewCredit']>0){
                $index_profile['deduct_credit_view']        = $user_profile_data['ViewCredit'];
            }
            
            if($user_profile_data['EmailCredit']>0){
                $index_profile['deduct_credit_email']       = $user_profile_data['EmailCredit'];
            }

            
            if($profiles['profile_revenue']>0){
                $index_profile['total_credit_deduction']    = $profiles['profile_revenue'];
            }else{
                $index_profile['total_credit_deduction']    = 0;
            }

            
            if($user_profile_data['ViewCount']>0){
                $index_profile['user_count_view']           = $user_profile_data['ViewCount'];
            }

            if($user_profile_data['SMSCount']>0){
                $index_profile['user_count_sms']            = $user_profile_data['SMSCount'];
            }

            if($user_profile_data['EmailCount']>0){
                $index_profile['user_count_email']          = $user_profile_data['EmailCount'];
            }
            
            if($profiles['profilePickTime']!=''){
                $index_profile['profile_pick_time']         = str_replace(' ', 'T', $profiles['profilePickTime']);
            }

            if($profiles['matchingInsertTime']!=''){
                $index_profile['matching_insert_time']      = str_replace(' ', 'T', $profiles['matchingInsertTime']);
            }

            if($profiles['allocationInsertTime']!=''){
                $index_profile['allocation_insert_time']    = str_replace(' ', 'T', $profiles['allocationInsertTime']);
            }

            unset($user_profile_data);
            

            if(count($profiles['solrMatchedGenies'])>0){
                $index_profile['solr_matched_genies'] = unserialize($profiles['solrMatchedGenies']);
                $index_profile['solr_matched_genies'] = $this->filterGenieIdOnly($index_profile['solr_matched_genies']);
            }

            if(count($index_profile['solr_matched_genies'])<1){
                unset($index_profile['solr_matched_genies']);
            }

            if($profiles['afterPorting']){
                $index_profile['after_porting'] = unserialize($profiles['afterPorting']);
                $index_profile['after_porting'] = $this->filterGenieIdOnly($index_profile['after_porting']);    
                
            }

            //genies excluded in after porting call
            $index_profile['excluded_porting'] = array_diff($index_profile['solr_matched_genies'], $index_profile['after_porting']);
            $index_profile['excluded_porting'] = array_values($index_profile['excluded_porting']);

            if(count($index_profile['excluded_porting'])<1){
                unset($index_profile['excluded_porting']);
            }


            if($profiles['afterInsufficientCredit']){
                $index_profile['after_insufficient_credit'] = unserialize($profiles['afterInsufficientCredit']);
                $index_profile['after_insufficient_credit'] = $this->filterGenieIdOnly($index_profile['after_insufficient_credit']);
                
            }   

            if(empty($index_profile['after_insufficient_credit'])){
                unset($index_profile['after_insufficient_credit']);
            }


            //genies excluded in insufficient credits call
            $index_profile['excluded_insufficient_credits'] = array_diff($index_profile['after_porting'], $index_profile['after_insufficient_credit']);
            $index_profile['excluded_insufficient_credits'] = array_values($index_profile['excluded_insufficient_credits']);

            if(count($index_profile['excluded_insufficient_credits'])<1){
                unset($index_profile['excluded_insufficient_credits']);
            }

            if($profiles['afterAlreadyAllocated']){
                $index_profile['after_already_allocated'] = unserialize($profiles['afterAlreadyAllocated']);
                $index_profile['after_already_allocated'] = $this->filterGenieIdOnly($index_profile['after_already_allocated']);
            }

            //genies excluded in already allocated call
            $index_profile['excluded_already_allocated'] = array_diff($index_profile['after_insufficient_credit'], $index_profile['after_already_allocated']);
            $index_profile['excluded_already_allocated'] = array_values($index_profile['excluded_already_allocated']);
            if(count($index_profile['excluded_already_allocated'])<1){
                unset($index_profile['excluded_already_allocated']);
            }

            if($profiles['afterRemovingNewGenie']){
                $index_profile['after_opted_Old_leads'] = unserialize($profiles['afterRemovingNewGenie']);
                $index_profile['after_opted_Old_leads'] = $this->filterGenieIdOnly($index_profile['after_opted_Old_leads']);
                
            }

            //genies excluded in final genie call
            $index_profile['excluded_old_genie'] = array_diff($index_profile['after_already_allocated'], $index_profile['after_opted_Old_leads']);
            $index_profile['excluded_old_genie'] = array_values($index_profile['excluded_old_genie']);
            
            if(count($index_profile['excluded_old_genie'])<1){
                unset($index_profile['excluded_old_genie']);
            }
            
            $allocation_data = unserialize($profiles['allocation_id_data']);

            if($profiles['finalGenie'] && $allocation_data['allocation_insert_id']>0 ){
                $index_profile['final_allocated_genie'] = unserialize($profiles['finalGenie']);
                //$profiles['finalGenie'] = $this->filterGenieIdOnly($profiles['finalGenie']);
            }


            //genies excluded in final genie call
            $index_profile['excluded_final_genie']  = array_diff($index_profile['after_already_allocated'], $index_profile['excluded_final_genie']);
            $index_profile['excluded_final_genie'] = array_values($index_profile['excluded_final_genie']);
            
            if(count($index_profile['excluded_final_genie'])<1){
                unset($index_profile['excluded_final_genie']);
            }

            if($profiles['timeInMatching']>0){
                $index_profile['time_in_matching']              =(float) $profiles['timeInMatching'];
            }
            
            if($profiles['profileViewLimitReached']){
                $index_profile['user_profile_limit_reached']            = $profiles['profileViewLimitReached'];
            }
            
            $allocation_data = unserialize($profiles['allocation_id_data']);
                
            if( $allocation_data['allocation_insert_id']  >0 ){
                $delivery_data = $this->getLeadDeliveryData(unserialize($profiles['allocation_id_data']));
                
                $index_profile['final_allocated_genie'] = $delivery_data['final_allocated_genie'];

                $index_profile['partial_delivery_time_sms']     = str_replace(' ', 'T', $delivery_data['partial_sms_time']);
                $index_profile['full_delivery_time_sms']        = str_replace(' ', 'T', $delivery_data['full_sms_time']);

                $index_profile['partial_delivery_time_email']   = str_replace(' ', 'T', $delivery_data['partial_email_time']);
                $index_profile['full_delivery_time_email']      = str_replace(' ', 'T', $delivery_data['full_email_time']);

                if($index_profile['partial_delivery_time_sms'] =='' || $index_profile['partial_delivery_time_sms']=='0000-00-00T00:00:00'){
                    unset($index_profile['partial_delivery_time_sms']);
                }

                if($index_profile['full_delivery_time_sms'] =='' || $index_profile['full_delivery_time_sms']=='0000-00-00T00:00:00'){
                    unset($index_profile['full_delivery_time_sms']);
                }

                if($index_profile['partial_delivery_time_email'] =='' || $index_profile['partial_delivery_time_email']=='0000-00-00T00:00:00'){
                    unset($index_profile['partial_delivery_time_email']);
                }

                if($index_profile['full_delivery_time_email'] =='' || $index_profile['full_delivery_time_email']=='0000-00-00T00:00:00'){
                    unset($index_profile['full_delivery_time_email']);
                }
            }else{
                unset($index_profile['allocation_insert_time']);
            }

            $IndexingProfiles[] = array_merge($commonDataForProfiles,$index_profile);
            
            $profiles = '';
        }


        if(empty($distinctProfiles)){
            $IndexingProfiles[] = $commonDataForProfiles;
        }

        return $IndexingProfiles;
        
    }

    private function filterGenieIdOnly($data){
        $genies = array();
        foreach ($data as $val) {
            if($val['SearchAgentId']>0){
                $genies[] = $val['SearchAgentId'];
            }else{
                $genies[] = $val['agentid'];
            }
        }

        return $genies;
    }   

}

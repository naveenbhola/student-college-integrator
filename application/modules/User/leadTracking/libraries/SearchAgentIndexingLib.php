<?php

class SearchAgentIndexingLib
{
    private $CI;
    private $master_map_id_with_values;

    function __construct(){
        $this->CI = & get_instance();
    }

    public function fetchSearchAgentData($search_agent_id, $start_date, $end_date, $attribute_master_data, $first_run=true, $custom_data, $clientId){
        $searchagenttrackingmodel = $this->CI->load->model('leadTracking/searchagenttrackingmodel');

        
        if($first_run){
            
            $search_agent_data      = $searchagenttrackingmodel->getSearchAgentData($search_agent_id);
            $search_agent_criteria  = $searchagenttrackingmodel->getSearchAgentCriteria($search_agent_id);       
        }else{
           
            $search_agent_data      = $custom_data['search_agent_data'];
            $search_agent_criteria  = $custom_data['search_agent_criteria'];       
            
        }

        $search_agent_allocation_data = $searchagenttrackingmodel->getSearchAgentAllocationData($search_agent_id, $start_date, $end_date);

        $search_agent_credits = $searchagenttrackingmodel->getRemainingCreditsForGenie($clientId);
        
        
        $search_agent_data['search_agent_id']    = $search_agent_id;
       /*fetch Exam criteria, location - PENDING*/

        $format_data['timeline_date']                    = $start_date;
        $format_data['search_agent_data']                = $search_agent_data;
        $format_data['search_agent_criteria']            = $search_agent_criteria;
        $format_data['search_agent_allocation_data']     = $search_agent_allocation_data;
        $format_data['credits_remaining']                = $search_agent_credits;

       $indexing_data = $this->formatSearchAgentIndexingData($format_data, $attribute_master_data);

       return  $indexing_data;
    }

    private function formatSearchAgentIndexingData($format_data, $attribute_master_data){

        $search_agent_data               = $format_data['search_agent_data'];
        $search_agent_criteria           = $format_data['search_agent_criteria'];
        $search_agent_allocation_data    = $format_data['search_agent_allocation_data'];


      
        $indexing_data = array();

        $indexing_data['client_id']                                 = $search_agent_data['clientid'];
        $indexing_data['search_agent_id']                           = $search_agent_data['search_agent_id'];
        $indexing_data['delivery_method']                           = $search_agent_data['deliveryMethod'];
        
        if($search_agent_data['leads_daily_limit']>0){
            $indexing_data['daily_download_limit']                     = $search_agent_data['leads_daily_limit'];
        }

        if($search_agent_data['daily_sms_limit']>0){
            $indexing_data['daily_sms_limit']                     = $search_agent_data['daily_sms_limit'];
        }

        if($search_agent_data['daily_email_limit']>0){
            $indexing_data['daily_email_limit']                     = $search_agent_data['daily_email_limit'];
        }


        if($search_agent_data['email_freq'] != ''){
            $indexing_data['email_freq']                            = $search_agent_data['email_freq'];
        }

        if($search_agent_data['sms_freq'] != ''){
            $indexing_data['sms_freq']                              = $search_agent_data['sms_freq'];
        }

        if($search_agent_data['flag_auto_download'] != ''){
            $indexing_data['flag_auto_download']                    = $search_agent_data['flag_auto_download'];
        }

        if($search_agent_data['flag_auto_responder_sms'] != ''){
            $indexing_data['flag_auto_responder_sms']               = $search_agent_data['flag_auto_responder_sms'];
            
        }

        if($search_agent_data['flag_auto_responder_email'] != ''){
            $indexing_data['flag_auto_responder_email']             = $search_agent_data['flag_auto_responder_email'];
            
        }

        if($search_agent_data['created_on'] != ''){
            $indexing_data['created_on']                            = str_replace(' ', 'T', $search_agent_data['created_on']);
            
        }

        if($search_agent_data['type'] != ''){
            $indexing_data['search_agent_type']                     = $search_agent_data['type'];
        }
        
        $indexing_data['extra_flag']                                = 'studyabroad';
        

        foreach ($search_agent_criteria as $criteria) {


            if($criteria['includeActiveUsers'] == 'no'){
                $indexing_data['include_active_user']                     = false;
            }else{
                $indexing_data['include_active_user']                     = true;
            }

            if($criteria['keyname'] == 'PlanToStart'){
                if($criteria['value'] =! 'Later'){
                    $indexing_data['plan_to_start'][]                  = $criteria['value'];
                }
            }

            if($criteria['keyname'] == 'desiredcourse'){
                $indexing_data['desired_course'][]                     = $criteria['value'];
                $indexing_data['desired_course_name'][]                = $attribute_master_data['desired_course'][$criteria['value']];
            }

            if($criteria['keyname'] == 'currentlocation'){
                $indexing_data['current_location'][]                     = $criteria['value'];
                $indexing_data['current_location_name'][]                = $attribute_master_data['city'][$criteria['value']];
            }

            /*if($criteria['keyname'] == 'Specialization'){
                $indexing_data['specialization'][]                     = $criteria['value'];
            }*/

            if($criteria['keyname'] == 'Locality'){
                $indexing_data['locality'][]                     = $criteria['value'];
                $indexing_data['locality_name'][]                = $attribute_master_data['locality'][$criteria['value']];
            }

            //to check if this field exists
            if($criteria['keyname'] == 'UGCourse'){
                
                $indexing_data['ug_course'][]                     = $criteria['value'];
            }

            if($criteria['keyname'] == 'AbroadSpecialization'){
                $indexing_data['abroad_specialization'][]                     = $criteria['value'];
            }

            if($criteria['keyname'] == 'Passport'){
                if($criteria['value'] == 'Yes'){
                    $indexing_data['passport']                     = true;
                }else{
                    $indexing_data['passport']                     = false;
                }
            }


            if($criteria['keyname'] == 'clientcourse'){
                $indexing_data['extra_flag']                        = 'national';
                $indexing_data['client_course'][]                   = $criteria['value'];
            }

            if($criteria['keyname'] == 'currentlocality'){
                $indexing_data['current_locality'][]                = $criteria['value'];
            }

            if($criteria['keyname'] == 'mrlocation'){
                $indexing_data['mr_location'][]                     = $criteria['value'];
                $indexing_data['mr_location_name'][]                = $attribute_master_data['city'][$criteria['value']];
            }

            if($criteria['keyname'] == 'Stream'){
                $indexing_data['extra_flag']                        = 'national';
                $indexing_data['stream']                            = $criteria['value'];
                $indexing_data['stream_name']                       = $attribute_master_data['stream'][$criteria['value']];

                unset($indexing_data['desired_course']);
                unset($indexing_data['desired_course_name']);
            }

            if($criteria['keyname'] == 'Courses'){
                $indexing_data['base_course'][]                     = $criteria['value'];
                $indexing_data['base_course_name'][]               = $attribute_master_data['basecourse'][$criteria['value']];
            }

            if($criteria['keyname'] == 'Mode_Value'){
                $indexing_data['mode'][]                     = $criteria['value'];
                $indexing_data['mode_name'][]               = $attribute_master_data['mode'][$criteria['value']];
            }

            if($criteria['keyname'] == 'Substreams'){
                $indexing_data['sub_stream'][]                     = $criteria['value'];
                $indexing_data['substream_name'][]               = $attribute_master_data['substream'][$criteria['value']];
            }

            if($criteria['keyname'] == 'Specializations'){
                $indexing_data['specialization'][]                     = $criteria['value'];
                $indexing_data['specialization_name'][]               = $attribute_master_data['specialization'][$criteria['value']];
            }

        }
        
        $indexing_data['allocation_download']    = 0;
        $indexing_data['allocation_auto_sms']   = 0;
        $indexing_data['allocation_auto_email']  = 0;

        foreach ($search_agent_allocation_data as $allocation_data) {
            if($allocation_data['auto_download'] == 'YES'){
                $indexing_data['allocation_download'] += 1;
            }

            if($allocation_data['auto_responder_email'] == 'YES'){
                $indexing_data['allocation_auto_email'] += 1;
            }

            if($allocation_data['auto_responder_sms'] == 'YES'){
                $indexing_data['allocation_auto_sms'] += 1;
            }
        }

        $indexing_data['timeline_date'] = $format_data['timeline_date'];

        if($format_data['credits_remaining']>0){
            $indexing_data['credits_remaining'] = $format_data['credits_remaining'];
            $indexing_data['credits_available'] = true;
        }else{
            $indexing_data['credits_remaining'] = 0;
            $indexing_data['credits_available'] = false;
        }
        
        $return_data['indexing_data'] = $indexing_data;
        
        $return_data['custom_data']['search_agent_data']        = $search_agent_data;
        $return_data['custom_data']['search_agent_criteria']    = $search_agent_criteria;

        return $return_data;        
    }

    public function getAllAttributeIdsWithNames(){
        ini_set('memory_limit', '512M');

        $lead_indexing_lib = $this->CI->load->library('leadTracking/LeadIndexingLib');
        $master_data = $lead_indexing_lib->getAllAttributeIdsWithNames();
        //$this->master_map_id_with_values = $master_data;    
        return $master_data;
    }
    
}

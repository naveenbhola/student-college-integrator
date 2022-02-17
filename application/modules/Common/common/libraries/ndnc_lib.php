<?php

   /**
    * Class ndnc library File for the shiksha
    * @package ndnc_lib
    * @author Ravi Raj<ravi.raj@shiksha.com>
    */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ndnc_lib {

    /**
     * @var integer The number that decide chunks size in cron
     */
    var $log_frequency;

    /**
     * @var object of The main CodeIgniter object.
     */
    var $CI;

    /**
     * @var string The name of the database to log to.
     */
    var $db_name;

    var $first_time_run;
    
    var $is_ndnc_run;

    var $is_mobile_verification_run;

    var $return_ndnc_check_if_cron_off;

    var $no_of_days_to_be_check;
    /**
     * default Constructor
     */

    function ndnc_lib()
    {
        $this->CI =& get_instance();
        if (!is_null($this->CI))
        {
            // Load ndnc configuration
            $this->CI->config->load('ndnc_settings', true);
            $this->log_frequency = $this->CI->config->item('log_frequency', 'ndnc_settings');
            $this->db_name = $this->CI->config->item('db_name', 'ndnc_settings');
            $this->first_time_run = $this->CI->config->item('first_time_run', 'ndnc_settings');
            $this->is_ndnc_run = $this->CI->config->item('is_ndnc_run', 'ndnc_settings');
            $this->is_mobile_verification_run = $this->CI->config->item('is_mobile_verification_run', 'ndnc_settings');
            $this->return_ndnc_check_if_cron_off = $this->CI->config->item('return_ndnc_check_if_cron_off', 'ndnc_settings');
            $this->no_of_days_to_be_check = $this->CI->config->item('no_of_days_to_be_check', 'ndnc_settings');
        }
    }

    function get_db_name()
    {
        return $this->db_name;
    }

    function get_first_time_run()
    {
        return $this->first_time_run;
    }

    function get_log_frequency()
    {
        return $this->log_frequency;
    }

    function set_log_frequency($log_frequency)
    {
        $this->log_frequency = $log_frequency;
    }

     function is_ndnc_run()
    {
        return $this->is_ndnc_run;
    }

    function is_mobile_verification_run()
    {
        return $this->is_mobile_verification_run;
    }

    function return_ndnc_check_if_cron_off()
    {
        return $this->return_ndnc_check_if_cron_off;
    }

    function no_of_days_to_be_check()
    {
        return $this->no_of_days_to_be_check;
    }


    /* Check mobile number is ndnc or not */
    function ndnc_mobile_check($mobile)
    {
       error_log("NDNCLDB flag is ". $this->is_ndnc_run);
        if ($this->is_ndnc_run != 'false')
        {
      $ndnc_db = $this->CI->load->database('ndnc', TRUE);
        $returnArray = array();

        $queryCmdMainNDNC = "SELECT PHONE FROM DNC_LIST WHERE PHONE =?";
        $queryCmdMainNDNC = $ndnc_db->query($queryCmdMainNDNC,array($mobile));
        /*
        if query failed or db handler null or mobile is empty then it will return NA
        we can also check here number of records return $queryCmdMainNDNC->num_rows();
        */
        if (($queryCmdMainNDNC != false) && ($ndnc_db != null) && ($mobile != ""))
        {
                     foreach ($queryCmdMainNDNC->result_array() as $row) 
                     {
                       $returnArray[] = $row['PHONE'];  
                     }
        // $queryCmdMainNDNC->num_rows();
                    if (count($returnArray) > 0)
                    {
                       return 'true';
                       
                    }
                    else
                       {
                       return 'false';                              
                    }
         
        }
        else
            {
                 return "na"; 
             }

        }
                   else{  
                         error_log("NDNCLDB return ". $this->return_ndnc_check_if_cron_off); 
                      return $this->return_ndnc_check_if_cron_off;             
                   }


    }
}








/* End of file searchAgents_client.php */ /* Location: ./system/system/application/libraries/searchAgents_client.php */
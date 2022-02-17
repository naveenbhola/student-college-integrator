<?php
/**
 * NDNC Class
 * @package Package NDNC
 * @subpackage NDNC Front-end
 * @category shiksha
 * @author Ravi Raj<ravi.raj@shiksha.com>
 * @link https://www.shiksha.com
 */
class ndnc extends MX_Controller {

    var $shiksha_db = null;
    var $ndnc_db = null;
   /**
    * The default constructor for NDNC.
    * @access   public
    */
    public function __construct()
    {
        parent::__construct();
        $this->dbLibObj = DbLibCommon::getInstance('NDNCShiksha');
        $this->ndnc_db = $this->load->database('ndnc', TRUE);
        $this->shiksha_db = $this->_loadDatabaseHandle('write');
        $this->load->library('ndnc_lib');
        $this->load->library('Alerts_client');

        if($this->ndnc_db === null)
        {
            $this->ndnc_db->close();
            $this->ndnc_db->initialize();
        }
        if($this->shiksha_db === null)
        {
            $this->shiksha_db->close();
            $this->shiksha_db->initialize();
        }
    }

    private function __ReconnectDB()
    {
        if($this->ndnc_db === null)
        {
            $this->ndnc_db->close();
            $this->ndnc_db->initialize();
        }
        if($this->shiksha_db === null)
        {
            $this->shiksha_db->close();
            $this->shiksha_db->initialize();
        }
    }

    /*
       CRON THAT RUN TO CHECK ALL SHIKSHA USERS FOR NDNC VALIDATION
    */
    public function run_ndnc_check_cron()
    {   mail('teamldb@shiksha.com', 'NDNC Cron is run', 'remove the mail call');
        
        return;
        /* set required server level configuration */
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        /* get all config values */
        $get_log_frequency  =   $this->ndnc_lib->get_log_frequency();
        $get_first_time_run =   $this->ndnc_lib->get_first_time_run();
        $is_ndnc_run =   $this->ndnc_lib->is_ndnc_run(); 
         $is_mobile_verification_run =   $this->ndnc_lib->is_mobile_verification_run(); 
       $return_ndnc_check_if_cron_off =   $this->ndnc_lib->return_ndnc_check_if_cron_off(); 
        $no_of_days_to_be_check =   $this->ndnc_lib->no_of_days_to_be_check();
        /* assign defualt value to var. that hold total records count */
        $total_record_for_processing = 0;
        /* so let's start  */
        echo ("\n ################################################################### \n");
        try {
            $queryTotalRecordCount = 'SELECT count( mobile ) AS totalRecord FROM
            tuser WHERE mobile IS NOT NULL AND mobile != "" AND mobile != "0"';
            if (isset($no_of_days_to_be_check) && ($no_of_days_to_be_check > 0) && $is_ndnc_run == 'false') 
                    { 
                        $no_of_days_to_be_check = (int)$no_of_days_to_be_check; 
                        $queryTotalRecordCount .= " AND usercreationDate >= (DATE_SUB(curdate(), INTERVAL ".$this->shiksha_db->escape($no_of_days_to_be_check)." DAY)) ORDER BY usercreationDate;"; 
                    }             
            
            $queryTotalRecordCount_result = $this->shiksha_db->query($queryTotalRecordCount);
            foreach ($queryTotalRecordCount_result->result_array() as $row)
            {
                $total_record_for_processing    =   (int)$row['totalRecord'];
            }

            // FOR DEBUG
            // $total_record_for_processing = 20; // change here 2 test 4 small chunks
            unset($queryTotalRecordCount);
            unset($queryTotalRecordCount_result);

            /* Now chunkify data, we will pick 10k in 1 shot */
            $loopcount = ceil($total_record_for_processing/$get_log_frequency);

            $m = 1;
            /* main loop start... JMD ... */
            for ($nloop = 0; $nloop < $loopcount; $nloop++)
            {
                $this->__ReconnectDB();
                // set limit offset & no of rows to b pick data
                $queryCmd_offset = (($m * $get_log_frequency) - ($get_log_frequency));
                $queryCmd_rows  = $get_log_frequency;

                // Pick last updated 5k mobile nos from tuser
                $queryCmdMainSql  =   'SELECT userid,mobile FROM tuser WHERE mobile IS NOT NULL AND mobile != "" AND mobile != "0" order by usercreationDate desc ';
                $queryCmdMainSql .= "  LIMIT ".$queryCmd_offset." , ".$queryCmd_rows." ";

                // run query now
                $queryCmdMain = $this->shiksha_db->query($queryCmdMainSql);

                echo("\n $nloop MAIN SQL 2 PICK DATA::".$queryCmdMainSql . "\n");
                unset($queryCmdMainSql);

                // check shiksha db obj & query run well
                if (( $queryCmdMain != false) || ($this->shiksha_db != null))
                {
                    // array to hold mobile Nos
                    $result_array = array();
                    // array to hold userIds
                    $result_array_userid = array();
                    // get mobile & userids separately
                    foreach ($queryCmdMain->result_array() as $row)
                    {
                        $result_array[] = $row['mobile']; // **
                        $result_array_userid[] = $row['userid']; //**
                    }
                    unset($queryCmdMain); // destroy main query result array

                    // make mobile nos csv for ndnc query
                    $mobilecsv = "'" . implode("','",$result_array) . "'"; // **
                    echo("\n $nloop profiles that are picked now::".implode(',',$result_array)."\n");

                    // --------------- get NDNC mobile no START ---------------//
                    $queryCmdMainNDNCSql   =   "SELECT PHONE FROM DNC_LIST WHERE PHONE IN (".$mobilecsv.")";
                    $queryCmdMainNDNC = $this->ndnc_db->query($queryCmdMainNDNCSql);
                    unset($queryCmdMainNDNCSql);

                    if (($queryCmdMainNDNC != false ) || ($this->ndnc_db != null))
                    {
                        // tmp array 2 hold ndnc mobile no,need to unset it later
                        $result_arrayNDNC = array();

                        // get all nos that found in ndnc
                        foreach ($queryCmdMainNDNC->result_array() as $row)
                        {
                            $result_arrayNDNC[] = $row['PHONE'];
                        }
                        // --------------- get NDNC mobile no END -------------//

                        echo("\n $nloop JUST CHECK FROM NDNC SERVER GOT ARRAY:::". implode(",",$result_arrayNDNC) . "\n");
                        /*
                                NOW WE DO SANITY ON NON NDNC NUMBERS. FOR THIS WE WILL PICK ALL
                                NON NDNC USERS
                        */
                        if (count($result_arrayNDNC) >= 0 && $is_ndnc_run !='false')
                        {
                            $non_ndnc_verified_users = array_diff($result_array,$result_arrayNDNC);
                            echo("\n $nloop Remove NDNC from  total user ARRAY :::". implode(",",$non_ndnc_verified_users) . "\n");

                            if (count($non_ndnc_verified_users) > 0)
                            {
                                // get mobile Nos
                                $mobilecsvMob = "'" . implode("','",$non_ndnc_verified_users) . "'";
                                unset($non_ndnc_verified_users); // unset ndnc result array
                                $queryCmdMainMobSql = '';
                                $queryCmdMainMobSql = "select userid,mobile from tuser where mobile in (".$mobilecsvMob.")";
                                echo("\n $nloop sorry bad coding ... again get userid & mobile no for all non_ndnc users ::::" . $queryCmdMainMobSql ."\n");
                                $queryCmdMainMob = $this->shiksha_db->query($queryCmdMainMobSql);
                                $result_array_Mob = array(); // need to unset it
                                $rs_mob = array();
                                foreach ($queryCmdMainMob->result_array() as $row)
                                {
                                    $result_array_Mob[] = $row['userid'];
                                    $rs_mob[] = $row['mobile'];
                                }
                                unset($queryCmdMainMob); // unset query obj

                                // update in tuserflag Now
                                $i = 0;
                                $cvsAllUserIds = implode(",",$result_array_userid);
                                $NA_User = array();
                                $NA_mobile = array();
                                if($get_first_time_run == 'false')
                                {
                                    $query_smsSql = "SELECT tuserflag.userId, tuser.mobile FROM tuser, tuserflag WHERE tuser.userid = tuserflag.userId
                                    AND tuserflag.isNDNC = 'NA' AND tuserflag.userId
                                    in (".$cvsAllUserIds.")";
                                    echo "\n $nloop Now check if any non-ndnc user marked as NA in DB $query_smsSql  \n";
                                    $query_sms = $this->shiksha_db->query($query_smsSql);
                                    foreach ($query_sms->result_array() as $row1)
                                    {
                                        $NA_User[] = $row1['userId'];
                                        $NA_mobile[] = $row1['mobile'];
                                    }
                                    unset($query_sms); // unset query object
                                    unset($query_smsSql); // unset all string
                                    if (count($NA_User) > 0)
                                    {
                                        echo("\n $nloop Getting ALL NON-NDNC users who are marked in DB as mobileverified ZERO ...so planing to send sms them. \n");
                                        $i = 0;
                                        foreach($NA_User as $user_na_data)
                                        {
                                            $alerts_client = new Alerts_client();
					    // changes in sms flow 
					    $Isregistration = 'Yes';	 	                                           
                                            $alerts_client->addSmsQueueRecord('12',$NA_mobile[$i],'Your number has been successfully verified',$user_na_data,'0000-00-00 00:00:00',"",$Isregistration);
                                            $i++;
                                        }
                                    }
                                }
                                unset($NA_mobile);
                                unset($NA_User);
                                $queryCmdMainTuserFlagSql = "UPDATE  tuserflag set isNDNC='NO' where userId IN (".$cvsAllUserIds.")";
                                $this->shiksha_db->query($queryCmdMainTuserFlagSql);
                                echo("\n $nloop So finally updating all NON NDNC User $queryCmdMainTuserFlagSql \n");
                                if ( $this->shiksha_db->affected_rows() == 0 || $this->shiksha_db->affected_rows() == -1)
                                {
                                        // error update failed
                                        echo(" \n $nloop  OOPS ... UPDATE FAILED \n");
                                }
                                unset($result_array_Mob); // unset userid array
                                unset($rs_mob); // unset mobile array
                                unset($cvsAllUserIds); // all CSV of user id
                                unset($queryCmdMainTuserFlagSql); // sql string
                                unset($cvsAllUserIds); // csv of user ids
                            }
                        }
                        // Now update rest NDNC USERS
                        if (count($result_arrayNDNC) > 0 && $is_ndnc_run !='false')
                        {
                            // get mobile Nos
                            $mobilecsvMob = "'" . implode("','",$result_arrayNDNC) . "'";
                            unset($result_arrayNDNC); // unset ndnc result array

                            $queryCmdMainMobSql = "select userid from tuser where mobile in (".$mobilecsvMob.")";
                            $queryCmdMainMob = $this->shiksha_db->query($queryCmdMainMobSql);

                            $result_array_Mob = array(); // need to unset it
                            foreach ($queryCmdMainMob->result_array() as $row)
                            {
                                $result_array_Mob[] = $row['userid'];
                            }
                            echo("\n $nloop so final array of ndnc userids:::".implode(',',$result_array_Mob) . "\n");

                            // update in tuserflag Now
                            $useridcsv = implode(",",$result_array_Mob);
                            unset($result_array_Mob); // unset userid array

                            $queryCmdMainTuserFlagSql = "UPDATE tuserflag set isNDNC='YES' where userId in ($useridcsv)";
                            echo("\n $nloop Final SQL for update NDNC YES::::".$queryCmdMainTuserFlagSql . "\n");
                            $this->shiksha_db->query($queryCmdMainTuserFlagSql);    
                            if ( $this->shiksha_db->affected_rows() == 0 || $this->shiksha_db->affected_rows() == -1)
                            {
                                    // error update failed
                                    echo("\n $nloop OOPS AGAIN UPDATE FAILED :: $useridcsv \n");
                            }
                            unset($mobilecsvMob);
                            unset($useridcsv);
                            unset($queryCmdMainTuserFlagSql);
			      }
                             if ($is_ndnc_run =='false')
                            { 
                                if ($is_mobile_verification_run == 'true') 
                                { 
                                    // Pick all user ids for that chunk 
                                    $cvsAllUserIds = implode(",",$result_array_userid); 
                                    //$cvsAllUserIds = $this->ndnc_db->escape_str($cvsAllUserIds); 
                                    $NA_User = array();$NA_mobile = array(); 
                                    if($get_first_time_run == 'false') 
                                    { 
                                        $query_smsSql = "SELECT tuserflag.userId, tuser.mobile FROM tuser, tuserflag WHERE tuser.userid = tuserflag.userId 
                                        AND tuserflag.isNDNC = 'NA' AND tuserflag.userId 
                                        in (".$cvsAllUserIds.")"; 
                                        echo "\n  $query_smsSql \n"; 
                                        $query_sms = $this->shiksha_db->query($query_smsSql); 
                                        foreach ($query_sms->result_array() as $row1) 
                                        { 
                                            $NA_User[] = (int)$row1['userId']; 
                                            $NA_mobile[] = (int)$row1['mobile'];
                                        } 
                                   
                                   
                                        unset($query_sms); // unset query object 
                                        unset($query_smsSql); // unset all string 
                                        if (count($NA_User) > 0) 
                                        { 
                                            echo("\n $nloop ALL-ARE-NON-NDNC and insert into SMSQ userids are ".implode(',',$NA_User). "\n"); 
                                            $j = 0; 
                                            foreach($NA_User as $user_na_data) 
                                            { 
                                                $alerts_client = new Alerts_client(); 
                                                // changes in sms flow 
                                                $Isregistration = 'Yes';
                                                $alerts_client->addSmsQueueRecord('12',$NA_mobile[$j],'Your number has been successfully verified',$user_na_data,'0000-00-00 00:00:00',"",$Isregistration); 
                                                $j++; 
                                            } 
                                        } 
                                    } 
                                    unset($NA_mobile); 
                                    unset($NA_User); 
                                } 
                                 
                                $queryCmdMainTuserFlagSql = "UPDATE  tuserflag set isNDNC='NO' where userId in (".$cvsAllUserIds.")"; 
                                echo("\n $nloop all r non-ndnc query is ".$queryCmdMainTuserFlagSql . "\n"); 
                                $this->shiksha_db->query($queryCmdMainTuserFlagSql); 
                                if ( $this->shiksha_db->affected_rows() == 0 || $this->shiksha_db->affected_rows() == -1) 
                                { 
                                        // error update failed 
                                        echo("\n $nloop & $j non-ndnc TUSERFLAG NO UPDATE FAILED \n"); 
                                } 
                                unset($cvsAllUserIds); // csv string 
                                unset($useridcsv); // unset userid csv string 
                                unset($mobilecsvMob);// unset ndnc mobile no csv string 
                                unset($mobilecsv);// unset tuser mobile no csv string 
                                unset($result_array); // unset tuser mobile array
                                unset($result_array_userid); // unset tuser id array 
                                unset($queryCmdMainNDNC); // main NDNC query 
                                unset($queryCmdMain); 
                            } 

                        unset($useridcsv); // unset userid csv string
                        unset($mobilecsvMob);// unset ndnc mobile no csv string
                        unset($mobilecsv);// unset tuser mobile no csv string
                        unset($result_array); // unset tuser mobile array
                        unset($result_array_userid); // unset tuser id array
                        unset($queryCmdMainNDNC); // main NDNC query
                        unset($queryCmdMain);
                    }
                    // QUERY FAILED OR DB CONNECTION OBJ NULL OR SUNAMI AT NDNC SERVER
                    else
                    {
                        $this->load->library('Alerts_client');
                        $alertClient = new Alerts_client();
                        $subject = "Alert Mail regarding NDNC server end SQL failure";
                        $htmlTemplate = "NDNC check Query Failed for following users \n $useridcsv";
                        $time=date('Y-m-d h-i-s');
                        $senderMail = 'info@shiksha.com';
                        $alertClient->externalQueueAdd("1",$senderMail, "ankur.gupta@shiksha.com", $subject,$htmlTemplate,"html",$time);
                        $alertClient->externalQueueAdd("1",$senderMail, "sachin.singhal@naukri.com", $subject,$htmlTemplate,"html",$time);
                        $alertClient->externalQueueAdd("1",$senderMail, "ravi.raj@shiksha.com", $subject,$htmlTemplate,"html",$time);
                        $alertClient->externalQueueAdd("1",$senderMail, "abhishek.jain@naukri.com", $subject,$htmlTemplate,"html",$time);
                        unset($useridcsv); // unset userid csv string
                        unset($mobilecsvMob);// unset ndnc mobile no csv string
                        unset($mobilecsv);// unset tuser mobile no csv string
                        unset($result_array); // unset tuser mobile array
                        unset($result_array_userid); // unset tuser id array
                        unset($queryCmdMainNDNC); // main NDNC query
                        unset($queryCmdMain); // main query
                    }
                }
                // shiksha db connection error or somthing wrong
                else
                {
                        error_log("\n $nloop NDNC unable to load data from shiksha db" . sprintf("DB Query Failed: %s", $queryCmdMain));
                }
                unset($queryCmdMain); // main query that pick 10k in 1 chunk
                $m++;
                echo ("\n ################################################################### \n");
            } // close main forloop
        } catch (Exception $e) {
            error_log('Error occoured during NDNC CRON. Please Look into issue.'.$e->getMessage());
            $to  = 'ravi.raj@shiksha.com';
            // subject
            $subject = 'NDNC CHECK CRON FAILED';
            // message
            $message = 'NDNC cron failed with following error(s) \n';
            $message .= $e->getMessage();
            $message .= "\n at " . date('l jS \of F Y h:i:s A');
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            // Additional headers
            $headers .= 'To: ankur Gupta <ankur.gupta@shiksha.com>' . "\r\n";
            $headers .= 'From: info <info@shiksha.com>' . "\r\n";
            $headers .= 'Cc: Amit Kuksal <amit.kuksal@shiksha.com>' . "\r\n";
            $headers .= 'Bcc: sachin Singhal <sachin.singhal@brijj.com>' . "\r\n";
            // Mail it
            @mail($to, $subject, $message, $headers);
        }
    }

}

/* End of file ndnc.php */
/* Location: ./system/application/controllers/searchAgents/ndnc.php */

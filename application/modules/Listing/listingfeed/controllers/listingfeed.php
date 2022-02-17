<?php
ini_set('memory_limit','256M');
set_time_limit(0);
//ignore_user_abort();
/**
 * Class listingfeed extends the Controller class for the shiksha
 * Sub category parameter needs to be added
 * Paid/unpaid status of the listings would need
 * to be made part of the same feeds.
 * limit area
 * @package listingfeed
 * @author shiksha team
 */

class listingfeed extends MX_Controller
{

    /**
     * XML version. Defaults to 1.0
     */
     
    private $_xmlVersion = '1.0';
    
    /**
     * Character set. Defaults to UTF-8.
     */
     
    private $_charSet = 'ISO-8859-1';
    
    /**
     * xml_files_path. Defaults to $_xml_files_path dir.
     */
     
    private $_xml_files_path = '/var/www/html/shiksha/system/application/views/listingfeed/xmldump';
    
    /**
     * Cron_time_interval. Defaults 1 hr.
     */
     
    private $_cron_time_interval = 1;

    /**
     * Result limit fetch from backend. Defaults is 100.
     */
     
    private $_result_limit = 100;

    /**
     * Reserved Keyword for SHIKSHA Tech. will be used later
     */
     
    public $appId = 1;
    
    function init()
    {
        parent::Controller();
        // $this->load->helper(array('xml','file','shikshaUtility'));
        $this->load->library(array('user_agent','Listingfeed_client'));
    }

    function index()
    { 
        $this->init();
        if( $this->agent->is_browser() or $this->agent->is_robot() or $this->agent->is_mobile()):
            exit('You may not access this file.');
        endif;
        
    }

    function _current_time()
    {
        $ago = $this->_cron_time_interval;
        $timestamp = time() - ($ago * 60*60);
        $time = date ("Y-m-d H:i:s", $timestamp);
        return $time;
    }

    
    /**
    * Run Through Cron.php default function
    * @see CRON JOB BOOTSTRAPPER at /var/www/html/shiksha/corn.php if u want run php as a CLI
    * @return string
    * @throws error_log_shiksha
    * @param boolean $flag
    */
    
    function runCronJob($flag=1,$noOfDays=0) {
        //echo "At the start PHP using (in bytes): ",memory_get_usage() , "\n<br>";
        $this->init();
        /*
        while(1)
        {
            
            // Did the connection fail?
            // User might be click the "Stop" button or browser is closed.
            // !Connection_Aborted()
            
            if(connection_status() != CONNECTION_NORMAL)
            {
                error_log_shiksha('Connection Is Aborted .. Grr... Uhh...');
                break;
            }
        */
        $objlistingfeedClient = new Listingfeed_client;
        /*
            flag = false mean it will take whole DB xml dump
            with increment of 1000
        */
        if ($flag == 1) {
            $limit = 1000;
            $time = '0000-00-00 00:00:00';
        } else {
			if($noOfDays == 0){
				$limit = $this->_result_limit;
				$time = $this->_current_time();
			}else{
				$limit = 1000;
            	$time = date("Y-m-d H:i:s",mktime(0, 0, 0, date("m"),date("d")-$noOfDays,date("Y")));
			}
        }
        //$time = '2009-04-09';
		
        $countSetArray = $objlistingfeedClient->ResultCountSet($this->appId,$time);
        $resultSetCountListing = $countSetArray['totalRowsListing'];
        $resultSetCountEvent = $countSetArray['totalRowsEvent'];
        try {
            $xmlstringdump = '<?xml version="1.0" encoding="ISO-8859-1"?>' . "\n";
            $xmlstringdump .= '<LISTINGFEED>'."\n";
            $file_path = $this->_xml_files_path . '/listing_' . time() . '.xml';
            @chmod( $file_path, 0777 );
            $fp = fopen($file_path, 'a+');
            fwrite($fp, $xmlstringdump); 
            /*
                Loop for reading listing data in chunks
            */
            if ($resultSetCountListing != 0) {
                $pagewise = ceil($resultSetCountListing/$limit);
                $number_loop  = 1 ;
                for ($nloop = 0; $nloop <= $pagewise; $nloop++) {
                    $queryCmd_offset = ($number_loop * $limit) - $limit;
                    $queryCmd_rows = $limit;
                    $resultArray = $objlistingfeedClient->runCronJob($this->appId,'institute',$time,$flag,$queryCmd_offset,$queryCmd_rows);
                    $resultArray = json_decode(base64_decode($resultArray),true);
                    //echo "Now we got ARRAY and PHP using (in bytes): ",memory_get_usage() , "\n<br>";
                    foreach ($resultArray['listing_xml_feed'] as $key1=>$listing_xml_data) {
                        if (is_numeric($key1)) {
                            foreach ($listing_xml_data as $key2=>$listing_xmldata) {
                                if (is_numeric($key2)) {
                                    /* Parse xml string */
                                    $xmlstringdump = $this->load->view('listingfeed/listingfeedview', $listing_xmldata, true);
                                    fwrite($fp, $xmlstringdump);
                                    unset($xmlstringdump);
                                }
                            }
                        }
                    }
                    $number_loop++;
                }
            }
                $xmlstringdump = '';
                $xmlstringdump = "\n".'</LISTINGFEED>';
                fwrite($fp, $xmlstringdump);
                //echo " LISTING FEED IS DUMPING IN FILE :( :( and PHP using (in bytes): ",memory_get_usage() , "\n<br>";
                fclose($fp);
                unset($xmlstringdump);
                unset($resultArray);
                // echo "LISTING XML STREAM IS RELEASED and PHP using (in bytes): ",memory_get_usage() , "\n<br>";
        } catch (Exception $e) {
            error_log_shiksha('Error occoured during dumping listing xml feed into files'.$e->getMessage(),'listingfeed');
        }

        try {
            $xmlstringdump = '<?xml version="1.0" encoding="ISO-8859-1"?>' . "\n";
            $xmlstringdump .= '<EVENTFEED>'."\n";
            $file_path = $this->_xml_files_path . '/event_' . time() . '.xml';
            @chmod( $file_path, 0777 );
            $fp = fopen($file_path, 'a+');
            fwrite($fp, $xmlstringdump);
            /*
                Loop for reading Events data in chunks
            */    
            if ($resultSetCountEvent != 0) {
                $pagewise = ceil($resultSetCountEvent/$limit);
                $number_loop  = 1 ;
                for ($nloop = 0; $nloop <= $pagewise; $nloop++) {
                    $queryCmd_offset = ($number_loop * $limit) - $limit;
                    $queryCmd_rows = $limit;
                    $resultArray = $objlistingfeedClient->runCronJob($this->appId,'event',$time,$flag,$queryCmd_offset,$queryCmd_rows);
                    $resultArray = json_decode(base64_decode($resultArray),true);
                    foreach ($resultArray['event_xml_feed'] as $key1=>$event_xml_data) {
                        if (is_numeric($key1)) {
                            foreach ($event_xml_data as $key2=>$event_xmldata) {
                                if (is_numeric($key2)) {
                                    /* Parse xml string */
                                    $xmlstringdump = $this->load->view('listingfeed/eventsfeed',$event_xmldata, true);
                                    fwrite($fp, $xmlstringdump);
                                    unset($xmlstringdump);
                                }
                            }
                        }
                    }
                    $number_loop ++;
                }
            }
            $xmlstringdump = '';
            $xmlstringdump = "\n".'</EVENTFEED>';
            fwrite($fp, $xmlstringdump);
            //echo "EVENT FEED IS DUMPING and PHP using (in bytes): ",memory_get_usage() , "\n<br>";
            fclose($fp);
            unset($xmlstringdump);
            unset($resultArray);
            // echo " EVENT XML STREAM IS RELEASED and PHP using (in bytes): ",memory_get_usage() , "\n<br>";
        } catch (Exception $e) {
            error_log_shiksha('Error occoured during dumping listing xml feed into files'.$e->getMessage(),'listingfeed');
        }
        //echo "At the END PHP using (in bytes): ",memory_get_usage() , "\n<br>";
        /*
        sleep(1);
        }
        */
    }
}

?>  
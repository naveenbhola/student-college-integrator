#!/usr/bin/php
<?php
/**
 * #########################
 * #      CRON JOB         #
 * #########################
 * Set the CRON_CI_INDEX constant to the CodeIgniter index.php file
 * Make this file executable (chmod a+x cron.php)
 * ./cron.php --run=/sums/SumsMailerServer/SumsMailerCron/45 -S  --log-file=/var/log/sums-cron.log --time-limit=18000
 * edit crontab
 * crontab -l > $tmpfile
 * edit $tmpfile
 * crontab $tmpfile
 * rm $tmpfile
 */
	register_shutdown_function('cron_shutdown_handler');
 
	@include_once('/var/www/html/shiksha/shikshaConfig.php');
	if(!defined('CRON_CI_INDEX'))
	{
		define('CRON_CI_INDEX', '/var/www/html/shiksha/index.php');
	}

    set_time_limit(0);
    ini_set('memory_limit', '256M');
    // CodeIgniter main index.php file
    define('CRON', TRUE);
    // if you only want controller accessible via cron

# Parse the command line
    $script = array_shift($argv);
    $cmdline = implode(' ', $argv);
    $dailylogFileName = '';
    $usage = "Usage: cron.php --run=/controller/method [--show-output][-S] [--log-file=logfile] [--time-limit=N] [--rotate-log=yes]\n\n";
    $required = array('--run' => FALSE);
    foreach($argv as $arg)
    {
        @list($param, $value) = @explode('=', $arg);
        switch($param)
        {
            case '--run':
                // Simulate an HTTP request
                $_SERVER['PATH_INFO'] = $value;
                $_SERVER['REQUEST_URI'] = $value;
                // URI patch (_parse_cli_args()) for CodeIgniter Reactor 2.0.X
                unset($_SERVER['argv']);
                $_SERVER['argv'][0] = null;
                $_SERVER['argv'][1] = $value;
                $required['--run'] = TRUE;
                break;

            case '-S':
            case '--show-output':
                define('CRON_FLUSH_BUFFERS', TRUE);
                break;

            case '--log-file':
                if(!empty($value))
                    {
                    $op = array();
                    $ret = array();
                    exec('touch '.$value,$op,$ret);
                    if($ret != 0)
                    {
                        die("Can not create the log file".$value);
                    }
                    /* break path into parts so that we can implement rotate log later */
                    $tmpArray = explode('.',$value);
                    if (count($tmpArray) <= 0)
                    {
                         die("Invalid log file name".$value);
                    }
                    $beforedotStr = $tmpArray[0];
                    $afterdotStr = $tmpArray[1];

                    define('CRON_LOG_1',$beforedotStr);
                    define('CRON_LOG_2',$afterdotStr);
                }
                break;

            case '--time-limit':
                define('CRON_TIME_LIMIT', $value);
                break;

            case '--rotate-log':
                if(!empty($value) && ($value == 'yes')) {
                    $dailylogFileName = '-'.date('Y-m-d');
                    define('CRON_LOG_3',$dailylogFileName);
                }
                break;

            default:

                die($usage);
        }
    }

    if(!defined('CRON_LOG_1')) define('CRON_LOG_1', '');
    if(!defined('CRON_LOG_2')) define('CRON_LOG_2', '');
    if(!defined('CRON_LOG_3')) define('CRON_LOG_3', '');

    if(CRON_LOG_1 =='' || CRON_LOG_2 =='')
    {
		define('CRON_LOG','/home/cron.log');
    }
    else
    {
	define('CRON_LOG',CRON_LOG_1.CRON_LOG_3.".".CRON_LOG_2);
    }

    if(!defined('CRON_TIME_LIMIT')) define('CRON_TIME_LIMIT', 0);

    foreach($required as $arg => $present)
    {
        if(!$present) die($usage);
    }

# Set run time limit
    set_time_limit(CRON_TIME_LIMIT);


# Run CI and capture the output
    ob_start();

    chdir(dirname(CRON_CI_INDEX));
    require(CRON_CI_INDEX);           // Main CI index.php file
    $output = ob_get_contents();

    if(CRON_FLUSH_BUFFERS === TRUE) {
        while(@ob_end_flush());       // display buffer contents
    } else {
        ob_end_clean();
    }

# Log the results of this run
    error_log("###".date('Y-m-d H:i:s')." cron.php $cmdline\n", 3, CRON_LOG);
    error_log($output, 3, CRON_LOG);
	
	function cron_shutdown_handler()
	{
		$error = error_get_last();	
		if($error && in_array($error['type'], array(E_ERROR, E_PARSE))) {
			$error_message = $error['message']." in file ".$error['file']." at line ".$error['line'];
			logCronError($error_message,$error['file'],$error['line']);
		}
	}
	
	function cron_db_error_handler($error)
	{
		$error_message = "DATABASE ERROR:: ".implode(" === ",$error);
		logCronError($error_message,$error[3],$error[4]);
	}
	
	function logCronError($error_message,$file,$line)
	{
		global $cmdline;
		
		/**
		 * Send email alert
		 */
		$headers = 'From: ProductionMonitor@shiksha.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

		if(ENVIRONMENT == 'production') {
			$to = "ShikshaProdEmergencyTeam@shiksha.com";//,vikas.k@shiksha.com,abhinav.k@shiksha.com,ankur.gupta@shiksha.com,amit.kuksal@shiksha.com,naveen.bhola@shiksha.com,pranjul.raizada@shiksha.com,sukhdeep.kaur@99acres.com";
		}
		else {
			$to = "Shiksha-Dev@Infoedge.com,shikshaqa@Infoedge.com";
		}
        
		$subject = "[".ENVIRONMENT."] CRON FATAL ERROR:: ".$cmdline;
		
        mail($to, $subject, $error_message." in file ".$file." on line number ".$line,$headers);
		
		/**
		 * Log in DB
		 */ 
        if(ENVIRONMENT == 'production'){
    	    $CI = & get_instance();
            $dbLibObj = DbLibCommon::getInstance('AppMonitor');
            $dbHandle = $dbLibObj->getWritehandle();
            
            $CI->load->config("app_monitor_config");
            $appMonitorLib = $CI->load->library('AppMonitor/AppMonitorLib');
            $cronPath = $appMonitorLib->getCronPath($cmdline);
            $team = $appMonitorLib->getCronTeam($cronPath);
            
            $data = array(
                                    'cron' => $cronPath,
                                    'team' => $team,
                                    'error' => $error_message,
                                    'file' => $file,
                                    'line' => $line,
                                    'time' => date('Y-m-d H:i:s')
                            );

            $dbHandle->insert('cron_php_errors',$data);	
        }
	}
?>

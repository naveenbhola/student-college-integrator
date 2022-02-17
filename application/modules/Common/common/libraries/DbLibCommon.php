<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: ankur $:  Author of last commit
$Date: 2012-07-10 12:00:11 $:  Date of last commit

DbLibCommon to proivide library method to the clients

$Id: DbLibCommon.php,v 1.0 2012-07-10 12:00:11 ankur Exp $: 

*/

class DbLibCommon{

	private $dbMapping = array(
					'AnA' => array(
							'read'=>'124',
							'write'=>'302'
					),
					'Listing' => array(
							'read'=>'168',
							'write'=>'302'
					),
					'coursePages' => array(
							'read'=>'168',
							'write'=>'302'
					),
					'OnlineForms' => array(
							'read'=>'124',
							'write'=>'302'
					),
					'CareerProduct' => array(
							'read'=>'124',
							'write'=>'302'
					),
			                'CampusAmbassador' => array(
                        			        'read'=>'124',
			                                'write'=>'302'
		                        ),
					'CollegePredictor' => array(
                                                        'read'=>'124',
                                                        'write'=>'302'
                                        ),
					'Blog' => array(
							'read'=>'124',
							'write'=>'302'
					),
					'SAContent' => array(
							'read'=>'124',
							'write'=>'302'
					),
					'User' => array(
							'read'=>'124',
							'write'=>'302'
					),
					'SearchAgents' => array(
							'read'=>'124',
							'write'=>'302'
					),
					'recommendation' => array(
							'read'=>'168',
							'write'=>'302'
					),
					'ContentRecommendation' => array(
							'read'=>'124',
							'write'=>'302'
					),
					'Alert' => array(
							'read'=>'124',
							'write'=>'302'
					),
					'Event' => array(
							'read'=>'124',
							'write'=>'302'
					),
					'Beacon' => array(
							'read'=>'124',
							'write'=>'302'
					),
					'Homepage' => array(
						'read'=>'124',
						'write'=>'302'
					),
					'CategoryList' => array(
						'read'=>'168',
						'write'=>'302'
					),
					'MyShiksha' => array(
						'read'=>'124',
						'write'=>'302'
					),
					'Mailer' => array(
						'read'=>'mailer',
						'write'=>'mailer'
					),
					'SMS' => array(
						'read'=>'168',
						'write'=>'302'
					),
					'Enterprise' => array(
						'read'=>'124',
						'write'=>'302'
					),
					'LDB' => array(
						'read'=>'124',
						'write'=>'302'
					),
					'LMS' => array(
						'read'=>'168',
						'write'=>'302'
					),
					'Marketing' => array(
						'read'=>'168',
						'write'=>'302'
					),
					'MIS' => array(
						'read'=>'168',
						'write'=>'302'
					),
					'Misc' => array(
						'read'=>'168',
						'write'=>'302'
					),
					'CustomizedMMP' => array(
						'read'=>'168',
						'write'=>'302'
					),
					'SUMS' => array(
						'read'=>'sumsread',
						'write'=>'sumswrite'
					),
					'SUMSShiksha' => array(
						'read'=>'168',
						'write'=>'302'
					),
					'NDNCShiksha' => array(
						'read'=>'168',
						'write'=>'302'
					),
					'Payment' => array(
						'read'=>'124',
						'write'=>'302'
					),
					'RelatedData' => array(
						'read'=>'168',
						'write'=>'302'
					),
					'Facebook' => array(
						'read'=>'124',
						'write'=>'302'
					),
					'Seo' => array(
						'read'=>'124',
						'write'=>'302'
					),
					'Mail' => array(
						'read'=>'124',
						'write'=>'302'
					),
					'location' => array(
						'read'=>'124',
						'write'=>'302'
					),
					'Search' => array(
						'read'=>'168',
						'write'=>'302'
					),
					'deferral' => array(
						'read'=>'deferral',
						'write'=>'deferral'
					),
					'QER' => array(
						'read'=>'qer',
						'write'=>'qer'
					),
					'crm' => array(
						'read'=>'crm',
						'write'=>'crm'
					),
					'Ranking' => array(
						'read'=>'168',
						'write'=>'302'
					),
					'session' => array(
						'read' => 'session',
						'write' => 'session',
					),
					'default' => array(
						'read'=>'302',
						'write'=>'302'
					),
					'LDBSEARCH' => array(
						'read'=>'201',
						'write'=>'302'
					),
					'CountryHome' => array(
							'read'=>'168',
							'write'=>'302'
					),
					'NaukriTool' => array(
							'read'=>'124',
							'write'=>'302'
                    ),
                    'Consultant' => array(
							'read'=>'168',
							'write'=>'302'
					),
					'ShikshaApply' => array(
							'read'=>'168',
							'write'=>'302'
					),
					'countryPage' => array(
							'read'=>'168',
							'write'=>'302'
					),
					'HardBounceTracking' => array(
							'read'=>'mailbounce',
							'write'=>'mailbounce'
					),
                    'AppMonitor' => array(
                            'read'=>'app_monitoring_read',
                            'write'=>'app_monitoring_write'
                    ),
                    'JSB9Report' => array(
                            'read'=>'jsb9_read'
                    ),
                    'MISTracking' => array(
                            'read'=>'mis',
                            'write'=>'302'
                    ) ,
					'qerSA' => array( // abroad QER
							'read' =>'qerSA',
							'write'=>'qerSA'
					),
					'VaMachine' => array(
							'read' =>'va_machine',
							'write'=>'va_machine'
					),
					'VaMachineSUMS' => array(
							'read' =>'va_machine_SUMS',
							'write'=>'va_machine_SUMS'
					),
					'VaMachineSessions' => array(
							'read' =>'va_machine_sessions',
							'write'=>'va_machine_sessions'
					),
					'VaMachineAppMonitor' => array(
							'read' =>'va_machine_appmonitor',
							'write'=>'va_machine_appmonitor'
					)
					
	);

	private $moduleName = '';
	private static $CI;
	private static $instances = array();

	public function __construct($moduleName){
		$this->moduleName = $moduleName;
		$this->CI =& get_instance();
	}

	public static function getInstance($moduleName)
	{
		// check for scan tool and change the db handle accordingly
		if(ENVIRONMENT == 'production')
			$moduleName = self::changeDBForScanTool($moduleName);

		if (!isset(self::$instances[$moduleName])) {
			self::$instances[$moduleName] = new DbLibCommon($moduleName);
		}
		return self::$instances[$moduleName];
	}

	public function getReadHandle(){
		$module = $this->moduleName;
		$db = $this->dbMapping[$module]['read'];
		return $this->CI->load->database($db,TRUE);
	}
	
	public function getWriteHandle(){
                $module = $this->moduleName;
                $db = $this->dbMapping[$module]['write'];
                return $this->CI->load->database($db,TRUE);
	}
	
	public function getDBSettings($module,$mode)
	{
		return $this->dbMapping[$module][$mode];
	}

	public function changeDBForScanTool($moduleName){

		if(in_array($moduleName, array('deferral', 'NDNCShiksha', 'QER', 'qerSA', 'crm', 'HardBounceTracking'))){
			return $moduleName;
		}

		if((strtolower($_SERVER['HTTP_SOURCE']) == 'scantool' || strtolower($_SERVER['SOURCE']) == 'scantool')){
			if($moduleName == 'SUMS')
				$moduleName = "VaMachineSUMS"; 
			else if($moduleName == 'session')
				$moduleName = "VaMachineSessions"; 
			else if($moduleName == 'AppMonitor')
				$moduleName = "VaMachineAppMonitor"; 
			else
				$moduleName = "VaMachine";

			error_log("VaMachine 1:".$_SERVER['HTTP_SOURCE'].", 2:".$_SERVER['SOURCE']);
		}
		return $moduleName;
	}
}
?>

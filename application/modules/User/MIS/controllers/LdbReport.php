<?php
ini_set('memory_limit', '-1');
/**
 *
 *  
 * 
 * @category LDB
 * @author Shiksha Team
 * @link https://www.shiksha.com
 */
class LdbReport extends MX_Controller {
	function init()
	{
		$this->load->model('ldbreportmodel');
	}

	/**
	 * API for generating Ldb report
	 */

	function LdbDataRetreival()
	{
		set_time_limit(0);
		$this->init();
		// Diff time intervals  for this mis cron	

		$todaysDate = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$todaysDate=date("Y-m-d 00:00:00", $todaysDate);
		$Dateweekback = mktime(0,0,0,date("m"),date("d")-7,date("Y"));
		$Dateweekback=date("Y-m-d 00:00:00", $Dateweekback);
		$DateTwoweekback = mktime(0,0,0,date("m"),date("d")-14,date("Y"));
		$DateTwoweekback=date("Y-m-d 00:00:00", $DateTwoweekback);
		$Datemonthback = mktime(0,0,0,date("m")-1,date("d"),date("Y"));
		$Datemonthback=date("Y-m-d 00:00:00", $Datemonthback);
		$DateNinetydaysback = mktime(0,0,0,date("m")-3,date("d"),date("Y"));
		$DateNinetydaysback=date("Y-m-d 00:00:00", $DateNinetydaysback);
		$Datesixmonthsback = mktime(0,0,0,date("m")-6,date("d"),date("Y"));
		$Datesixmonthsback=date("Y-m-d 00:00:00", $Datesixmonthsback);
		$similarmonthbacklastyear = mktime(0,0,0,date("m")-1,date("d"),date("Y")-1);
		$similarmonthbacklastyear=date("Y-m-d 00:00:00", $similarmonthbacklastyear);
		$similardatelastyear = mktime(0,0,0,date("m"),date("d"),date("Y")-1);
		$similardatelastyear=date("Y-m-d 00:00:00", $similardatelastyear);

		$LeadsType = array('0' => 'national',
				'1' =>	'studyabroad',
				'2' => 'testprep'
				);

		$finalResult = array();
		foreach($LeadsType as $key=>$LeadType )
		{
			$lastsixmonthsarray =$this->ldbreportmodel->cronToGetMISInformation($LeadType,$Datesixmonthsback,$todaysDate);
			$lastthreemonthsarray = $this->ldbreportmodel->cronToGetMISInformation($LeadType,$DateNinetydaysback,$todaysDate);
		
			$returndata = $this->formatLdbReport($lastsixmonthsarray,$lastthreemonthsarray,'Last 3 months',$LeadType);
			$lastmontharray = $this->ldbreportmodel->cronToGetMISInformation($LeadType,$Datemonthback,$todaysDate);
			$returndata = $this->formatLdbReport($returndata,$lastmontharray,'Last 1 month',$LeadType);

			$lasttwoweeksarray = $this->ldbreportmodel->cronToGetMISInformation($LeadType,$DateTwoweekback,$todaysDate);
			$returndata = $this->formatLdbReport($returndata,$lasttwoweeksarray,'Last 2 weeks',$LeadType);

			$lastweekarray = $this->ldbreportmodel->cronToGetMISInformation($LeadType,$Dateweekback,$todaysDate);
			$returndata = $this->formatLdbReport($returndata,$lastweekarray,'Last 1 week',$LeadType);

			// For next year Q4 outcome to be identified
			$month = date("m");
			if($month < '04'){
				$yearvariable = date("Y")-1; 	
			}
			else{

				$yearvariable = date("Y"); 	
			}

			$NextYearVariable = $yearvariable + 1;

			$firstquarter = $this->ldbreportmodel->cronToGetMISInformation($LeadType,$yearvariable.'-04-01 00:00:00',$yearvariable.'-07-01 00:00:00');		
			$returndata = $this->formatLdbReport($returndata,$firstquarter,'Current year Q1',$LeadType);

			$secondquarter = $this->ldbreportmodel->cronToGetMISInformation($LeadType,$yearvariable.'-07-01 00:00:00',$yearvariable.'-10-01 00:00:00');
			$returndata = $this->formatLdbReport($returndata,$secondquarter,'Current year Q2',$LeadType);

			$thirdquarter = $this->ldbreportmodel->cronToGetMISInformation($LeadType,$yearvariable.'-10-01 00:00:00',$yearvariable.'-12-31 23:59:59');		
			$returndata = $this->formatLdbReport($returndata,$thirdquarter,'Current year Q3',$LeadType);

			$fourthquarter = $this->ldbreportmodel->cronToGetMISInformation($LeadType,$NextYearVariable.'-01-01 00:00:00',$NextYearVariable.'-04-01 00:00:00');
			$returndata = $this->formatLdbReport($returndata,$fourthquarter,'Current year Q4',$LeadType);


			$similarperiodlastyear = $this->ldbreportmodel->cronToGetMISInformation($LeadType,$similarmonthbacklastyear,$similardatelastyear);
			$returndata = $this->formatLdbReport($returndata,$similarperiodlastyear,'1 month same period last year',$LeadType);

			foreach ($returndata as $row) {
				array_push($finalResult, $row);
			}
		}

		$csvdata = $this->createCSV($finalResult);
		$this->generateAndSendReport($csvdata);
		flush();
		exit;
	}
	/*************************************/
	//Cron to get Daily Data Start
	/*************************************/	
	function createCSV($array)
	{
		$filename = date(Ymdhis).' data.csv';
		$mime = 'text/x-csv';
		$this->init();
		$columnListArray = array();
		$columnListArray[]='Category';
		$columnListArray[]='LDBCourseName';
		$columnListArray[]='PreferredCity';
		$columnListArray[]='PreferredAbroadCountry';
		$columnListArray[]='Last 1 week';
		$columnListArray[]='Last 2 weeks';
		$columnListArray[]='Last 1 month';
		$columnListArray[]='Last 3 months';
		$columnListArray[]='Last_6_months';
		$columnListArray[]='1 month same period last year';
		$columnListArray[]='Current year Q1';
		$columnListArray[]='Current year Q2';
		$columnListArray[]='Current year Q3';
		$columnListArray[]='Current year Q4';
		$ColumnList = $columnListArray;
		$csv = '';
		foreach ($ColumnList as $ColumnName){
			$csv .= '"'.$ColumnName.'",';
		}
		$csv .= "\n";
		foreach ($array as $lead){
			foreach ($ColumnList as $ColumnName){
				$csv .= '"'.$lead[$ColumnName].'",';
			}
			$csv .= "\n";
		}
		$data = $csv;
		return ($data);
	}



	function formatLdbReport($initialBuildArray,$arrayToTraverseseconArray,$timeinterval,$LeadType)
	{

		global $finalarray;

		foreach($initialBuildArray as $key=>$ArrayTraversed){		
			if($arrayToTraverseseconArray[$key] && ($LeadType == 'national' || $LeadType == 'studyabroad')){
				$initialBuildArray[$key][$timeinterval] = $arrayToTraverseseconArray[$key]['Last_6_months'];
				unset($arrayToTraverseseconArray[$key]);
			}
			elseif($arrayToTraverseseconArray[$key] && $LeadType == 'testprep'){
				$initialBuildArray[$key][$timeinterval] = $arrayToTraverseseconArray[$key]['Last_6_months'];
				unset($arrayToTraverseseconArray[$key]);
			}
		}

		foreach($arrayToTraverseseconArray as $ArrayTraversed){
			if($LeadType == 'studyabroad'){
				$key = $ArrayTraversed['Category']."_".$ArrayTraversed['PreferredAbroadCountry'];
				$initialBuildArray[$key]['PreferredAbroadCountry']= $ArrayTraversed['PreferredAbroadCountry'];
				$initialBuildArray[$key]['Category'] = $ArrayTraversed['Category'];
				$initialBuildArray[$key][$timeinterval] = $ArrayTraversed['Last_6_months'];
			}
			elseif($LeadType == 'national'){
				$key = $ArrayTraversed['LDBCourseName']."_".$ArrayTraversed['PreferredCity'];
				$initialBuildArray[$key]['LDBCourseName']= $ArrayTraversed['LDBCourseName'];
				$initialBuildArray[$key]['PreferredCity'] = $ArrayTraversed['PreferredCity'];
				$initialBuildArray[$key]['Category'] = $ArrayTraversed['Category'];
				$initialBuildArray[$key][$timeinterval] = $ArrayTraversed['Last_6_months'];
			}
			elseif($LeadType == 'testprep'){
				$key = $ArrayTraversed['LDBCourseName']."_".$ArrayTraversed['PreferredCity'];
				$initialBuildArray[$key]['LDBCourseName']= $ArrayTraversed['LDBCourseName'];
				$initialBuildArray[$key]['PreferredCity'] = $ArrayTraversed['PreferredCity'];
				$initialBuildArray[$key]['Category'] = $ArrayTraversed['Category'];
				$initialBuildArray[$key][$timeinterval] = $ArrayTraversed['Last_6_months'];
			}


		}
		$finalarray = $initialBuildArray;
		return $finalarray;
	}

	function generateAndSendReport($csv)
	{	
		$appId = 1;
		$this->init();		

		$date = date("Y-m-d 00:00:00");
		$Dateweekback = mktime(0,0,0,date("m"),date("d")-7,date("Y"));
		$Dateweekback=date("Y-m-d 00:00:00", $Dateweekback);
		$Subject = "Lead report from "."$Dateweekback"." to ".$date;
		$Content = "Lead report from "."$Dateweekback"." to ".$date;

		error_log("The csv file\n".$csv,3,"/tmp/Lead_reports_for_".date("Y-m-d H:i:s").".csv");
		chmod("/tmp/Lead_reports_for_".date("Y-m-d H:i:s").".csv", 0777);

		$filename = "Lead_reports_for_".date("Y-m-d H:i:s").".csv";		

		$this->load->library('Ldbmis_client');
		$misObj = new Ldbmis_client();
		$this->load->library('alerts_client');
		$alertClientObj = new Alerts_client();

		$type_id = $misObj->updateAttachment($appId);
		$attachmentResponse = $alertClientObj->createAttachment("12",$type_id,'COURSE','E-Brochure',$csv,$filename,'text');
		$attachmentId = $attachmentResponse;
		$attachmentArray=array();
		array_push($attachmentArray,$attachmentId);
		
		error_log("Create attachment response is  == ".$attachmentId);

		$csvName = $file.".csv";
		
		$tomailarray = array( 
		     '0' => 'prakash.sangam@naukri.com', 
		     '1' => 'saurabh.gupta@shiksha.com', 
		     '2' => 'ambrish@shiksha.com',
		     '3' => 'konark.arora@shiksha.com',
		     '4' => 'sachin.singhal@brijj.com',
		     '5' => 'vikas.k@shiksha.com',
		     '6' => 'soumendu.g@naukri.com'
		     ); 
		
		foreach($tomailarray as $to){ 
	
		$response = $alertClientObj->externalQueueAdd("12","info@shiksha.com",$to,$Subject,$Content,$contentType="text",'','y',$attachmentArray);
	
		}
	
	}
}

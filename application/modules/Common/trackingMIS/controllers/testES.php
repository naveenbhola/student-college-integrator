<?php

/**
 * Class Overview
 *
 * This class handles the overview for the Shiksha / Domestic / Abroad domains
 */
require_once('vendor/autoload.php');
class testES extends MX_Controller
{
    
	private $CI;
	public static $TRAFFICDATA_PAGEVIEWS, $TRAFFICDATA_SESSIONS;

    function __construct(){
        if (ENVIRONMENT == 'production'){
            return;
        }

        //$this->CI = & get_instance();
        testES::$TRAFFICDATA_PAGEVIEWS = 'shiksha_trafficdata_pageviews';
        testES::$TRAFFICDATA_SESSIONS  = 'shiksha_trafficdata_sessions';

        //$this->clientCon = $this->_getSearchServerConnection();
    }

    public function _getSearchServerConnection() {
        $this->clientParams = array();
        $this->clientParams['hosts'] = array('127.0.0.1');
        return new Elasticsearch\Client($this->clientParams);
    }

    public function _getSearchServerConnection1() {
        $this->clientParams = array();
        $this->clientParams['hosts'] = array('127.0.0.1');
        return new Elasticsearch\Client($this->clientParams);
    }

    function pushDataToTestServer_pageviews(){
        ini_set('memory_limit', '4096M');
        $params = array();
        #278456
        $size = 10000;
        $form = 0;
        $ESConnection = $this->_getSearchServerConnection();
        $ESConnection1 = $this->_getSearchServerConnection1();
        for ($i=1; $i <=96 ; $i++) {
            $form += 10000;
            $params['index'] = 'trafficdata_pageviews_2';
            $params['type'] = 'pageview';
            $params['body']['size'] = $size;//$size;//2;//78456;
            $params['body']['from'] = $form;

            $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isStudyAbroad'] = "yes";
            $params['body']['query']['filtered']['filter']['bool']['must'][]['range'] = array(
                'visitTime' => array(
                      "gte"=> "2016-09-01T00:00:00",
                  "lte"=> "2016-09-30T23:59:59"
                    )
                );
            $search = $ESConnection->search($params);
            $search = $search['hits']['hits'];

            $resultArray = array();
            foreach ($search as $key => $value) {
                $resultArray[] = $value['_source'];
                unset($search[$key]);
            }

            $params1 = array();
            $params1['body'] = array();

            foreach($resultArray as $result) {
                    $params1['body'][] = array('index' => array(
                                            '_index' => 'trafficdata_pageviews_2',
                                            '_type' => 'pageview',
                                        )
                                    );
                    $params1['body'][] = $result;
            }
            //_p($params1);die;
            $ESConnection1->bulk($params1);
            error_log("Response Data Form : ".$form);

        }
    }

    function pushDataToTestServer(){
        ini_set('memory_limit', '4096M');
        $params = array();
        #278456
        $size = 2000;
        $form = 0;
        $ESConnection = $this->_getSearchServerConnection();
        $ESConnection1 = $this->_getSearchServerConnection1();
        for ($i=1; $i <=426 ; $i++) {
            $form += 2000;
            $params['index'] = 'mis_responses';
            $params['type'] = 'response';
            $params['body']['size'] = $size;//2;//78456;
            $params['body']['from'] = $form;
            
            $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['site'] = "Study Abroad";
            /*$params['body']['query']['filtered']['filter']['bool']['must'][]['range'] = array(
                'visitTime' => array(
                    "gte"=> "2016-10-01T00:00:00",
                    "lte"=> "2016-10-31T23:59:59"
                    )
                );*/
            //_p((json_encode($params)));die;
            
            $search = $ESConnection->search($params);
            $search = $search['hits']['hits'];
            $resultArray = array();
            foreach ($search as $key => $value) {
                $resultArray[] = $value['_source'];
                unset($search[$key]);
            }

            $params1 = array();
            $params1['body'] = array();
            foreach($resultArray as $result) {
                    $params1['body'][] = array('index' => array(
                                            '_index' => 'mis_responses',
                                            '_type' => 'response',
                                        )
                                    );          
                    $params1['body'][] = $result;
            }
            //_p($params1);
            $ESConnection1->bulk($params1);
            error_log("pageviews Data Form : ".$form);
        }
    }

    function FunctionName2(){
        echo 'sdfs';die;
        $listingName = 'Institute of Management, Nirma University (IMNU Ahmedabad)';
        echo '1</br>';_p($listingName);
        $listingName = seo_url($listingName,"-","200",true);

        $cityName = 'Ahmedabad';
        echo '2</br>';_p($cityName);
        if(stripos($listingName,$cityName) === FALSE && $cityName != ''){
            $cityAppend = '-'.seo_url($cityName,"-","150",true);
        }

        $localityName = 'Gota';
        echo '3</br>';_p($localityName);
        if(isset($localityName) && $localityName != ''){
            $localityAppend = '-'.seo_url($localityName,"-","150",true);
        }
        echo '4</br>';
        $url = SHIKSHA_HOME.'/college/'.$listingName.$localityAppend.$cityAppend.'/mba-application-form-127761';
        _p($listingName.$localityAppend.$cityAppend);
        echo '5</br>';
        _p($url);
        //_p($temp);
    }

    function FunctionName1(){
        //select short_name,name from shiksha_institutes where status = "live" and listing_id = 35407;
        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        $temp = $instituteRepo->find(1235);
        //_p($temp);
        echo '1</br>';_p($temp->getName());
        $listingName = seo_url($temp->getName(),"-","200",true);


        echo '2</br>';_p($temp->getMainLocation()->getCityName());
        
        if(stripos($temp->getName(), $temp->getMainLocation()->getCityName()) === FALSE && $temp->getMainLocation()->getCityName() != ''){
            $cityAppend = '-'.seo_url($temp->getMainLocation()->getCityName(),"-","150",true);
        }

        $localityName = $temp->getMainLocation()->getLocalityName();
        echo '3</br>';_p($localityName);
        if(isset($localityName) && $localityName != ''){
            $localityAppend = '-'.seo_url($localityName,"-","150",true);
        }
        echo '4</br>';
        $url = SHIKSHA_HOME.'/college/'.$listingName.$localityAppend.$cityAppend.'/mba-application-form-127761';
        _p($listingName.$localityAppend.$cityAppend);
        echo '5</br>';
        _p($url);
        //_p($temp);
    }

    function pustDataForTestServer(){
        ini_set('memory_limit', '4096M');

        /*$date['startDate'] = '2016-01-01';
        $date['endDate'] = '2016-01-02';*/
        $params = array();
        $params['index'] = testES::$TRAFFICDATA_PAGEVIEWS;
        $params['type'] = 'pageview';
        $params['body']['size'] = 0;

        // must not filter
        $must_not_filter = array();
        $must_not_filter['term']['userId'] = 0;

        // must filter
        $must_filter = array();
        $must_filter['range']['visitTime'] = array(
            'gte' => $date.'T00:00:00',
            'lte' => $date.'T23:59:59'
            );

        //$params['body']['fields'] = array('userId');
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isStudyAbroad'] = 'yes';
        $params['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['gte'] = $date['startDate'].'T00:00:00';
        $params['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['lte'] = $date['endDate'].'T23:59:59';
        /*        $params['body']['aggs']['userIds']['terms'] = array(
            'field'=>'userId',
            'size'=>5499
            );*/
        $search = $this->clientCon->search($params);

        $totalCount = $search['hits']['total'];

        $params['body']['size'] = $totalCount;
        $params['body']['fields'] = array('userId');
        $search = $this->clientCon->search($params);
        $search = $search['hits']['hits'];
        foreach ($search as $key => $value) {
            $userIds[$value['fields']['userId'][0]] = 1;
            unset($search[$key]);
        }
        //_p($userId);die;
        //$userId = array_keys($userId);
        //return count($userId);
        return $userId;
    }

    function getDailyData(){
    	ini_set('memory_limit', '4096M');
    	$dailyData = array();

    	$date['startDate'] = "2016-01-01";
    	$date['startDate'] = date('Y-m-d',strtotime($date['startDate']));
    	$monthlyData = array();
    	for ($i=1; $i < 12; $i++) {
    		$date['endDate'] = date('Y-m-d',strtotime('-1 day'.date('Y-m-d',strtotime('+1 month'.$date['startDate']))));
				$monthlyData[$date['startDate']] = $this->index($date);
				error_log('date  :'.print_r($date,true));
    		//_p($date);
    		$date['startDate'] = date('Y-m-d',strtotime('+1 month'.$date['startDate']));
    	}


    	$dataForCSV = $this->prepareDataForCSV($monthlyData);

    	$this->load->library('common/PHPExcel');
		$objPHPExcel    = new PHPExcel();

		$objPHPExcelActiveSheet = $objPHPExcel->getActiveSheet();
		$objPHPExcelActiveSheet->setTitle("Unique Users");
		$objPHPExcelActiveSheet->fromArray($dataForCSV, null, 'A2');

		$objWriter 	= new PHPExcel_Writer_Excel2007($objPHPExcel);
		$reportName = "unique_users_report_monthly.xlsx";
		$path 		= "/home/praveen/Shiksha/";
		$excelURL 	= $path.$reportName;
		$objWriter->save($path.$reportName);
    }

    function collegeReviewFile(){
        $inputFileName  = '/home/praveen/Shiksha/LDB/Review list_To be remapped_new.xlsx';
        $this->load->library('common/PHPExcel');
        $objPHPExcel    = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel  = $objReader->load($inputFileName);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $rows = array();
        $attributes = array();
        $CRCourseMappingSql ='update CollegeReview_MappingToShikshaInstitute set ';
        $CRindexLogSql ='insert into indexlog (operation,listing_type,listing_id) VALUES ';
        $CRTrackingSql ='insert into CollegeReview_Tracking (reviewId, addedBy,action,data) VALUES ';

        $instituteIdSql = ' instituteId = case reviewId ';
        $courseIdSql = ' courseId = case reviewId ';
        $locationIdSql = ' locationId = case reviewId ';
        $instituteIdSqlData ='';$courseIdSqlData ='';$locationIdSqlData = '';$CRindexLogSqlData = '';$CRTrackingSqlData = '';
        // reviewId courseId instatituteId locationId

        //$sqlDaaaa = "update CollegeReview_MappingToShikshaInstitute set ";
        $sqlDaaaa = "select * from CollegeReview_MappingToShikshaInstitute where ";
        $sqlDaaaa1= '';
        $rowCount = 0;
        $totalRowCount = 0;
        $sqlQueryArray = array();
        for($row = 2; $row <= $highestRow; ++$row) {
            $reviewId = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
            //_p($reviewId);die;
            $instituteId = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
            $locationId = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
            $courseId = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
            
            if($reviewId > 0 && $instituteId > 0 && $courseId > 0 && $locationId >0){
                $sqlDaaaa1 = $sqlDaaaa . " (reviewId = ".$reviewId." and courseId = ".$courseId. ") OR ";
                //$sqlDaaaa = "update CollegeReview_MappingToShikshaInstitute set  instituteId =".$instituteId." , locationId = ".$locationId." , courseId = ".$courseId." where reviewId = ".$reviewId.";<br>";
                
                $rowCount++;
                $totalRowCount++;
                $instituteIdSqlData .= " when ".$reviewId." then ".$instituteId;
                $courseIdSqlData .= " when ".$reviewId." then ".$courseId;
                $locationIdSqlData .= " when ".$reviewId." then ".$locationId;
                $CRindexLogSqlData .= "( 'index','collegereview',".$reviewId."),";
                //{"courseId":"280292","instituteId":"1","locationId":"143684"}
                $CRCourseMapping = array("courseId" => $courseId, "instituteId"=> $instituteId, "locationId" => $locationId);
                $CRTrackingSqlData .= "( ".$reviewId.", 11, 'courseDetailsUpdated','".json_encode($CRCourseMapping)."'),";
                if($rowCount == 100){
                    //_p();
                    $rowCount =0;
                    $sqlQueryArray['CRCourseMappingSql'][] = $CRCourseMappingSql . $instituteIdSql." ".$instituteIdSqlData." end, ".$courseIdSql." ".$courseIdSqlData." end, ".$locationIdSql." ".$locationIdSqlData." end".";";
                    $sqlQueryArray['CRindexLogSql'][] = $CRindexLogSql.substr($CRindexLogSqlData, 0,-1).";";

                    $sqlQueryArray['CRTrackingSql'][] = $CRTrackingSql.substr($CRTrackingSqlData, 0,-1).";";

                    $instituteIdSqlData ='';$courseIdSqlData ='';$locationIdSqlData = '';$CRindexLogSqlData = '';$CRTrackingSqlData='';
                }
            }
        }
        
        if(!empty($instituteIdSqlData) && !empty($courseIdSqlData) && !empty($locationIdSqlData)){
            $sqlQueryArray['CRCourseMappingSql'][] = $CRCourseMappingSql . $instituteIdSql." ".$instituteIdSqlData." end, ".$courseIdSql." ".$courseIdSqlData." end, ".$locationIdSql." ".$locationIdSqlData." end";
            $instituteIdSqlData ='';$courseIdSqlData ='';$locationIdSqlData = '';
        }

        if(!empty($CRindexLogSqlData)){
            $sqlQueryArray['CRindexLogSql'][] = $CRindexLogSql.substr($CRindexLogSqlData, 0,-1).";";
        }

        if(!empty($CRTrackingSqlData)){
            $sqlQueryArray['CRTrackingSql'][] = $CRTrackingSql.substr($CRTrackingSqlData, 0,-1).";";
        }
        
        _p($sqlQueryArray);die;
    }

    function prepareDataForCSV($dailyData){
    	$dataForCSV = array();
    	$i=1;
    	$dataForCSV[0]  = array('Date','Count');
    	foreach ($dailyData as $date => $count) {
    		$dataForCSV[$i++] = array($date,$count);
    	}
    	return $dataForCSV;
    }

    function userWiseSessionCount(){
    	ini_set('memory_limit', '4096M');

    	$date['startDate'] = "2016-01-01";
    	$date['startDate'] = date('Y-m-d',strtotime($date['startDate']));
    	$monthlyData = array();
    	$userIds = array();
    	for ($i=1; $i < 12; $i++) {
    		$date['endDate'] = date('Y-m-d',strtotime('-1 day'.date('Y-m-d',strtotime('+1 month'.$date['startDate']))));
				$this->index($date,$userIds);
				error_log('date  :'.print_r($date,true));
    		//_p($date);
    		$date['startDate'] = date('Y-m-d',strtotime('+1 month'.$date['startDate']));
    	}
    	_p(count($userIds));
    	//_p($userIds);

    }

    function getUserwiseSessionCount($userId){
    	/*$date['startDate'] = '2016-01-01';
    	$date['endDate'] = '2016-01-02';*/
    	$params = array();
        $params['index'] = testES::$TRAFFICDATA_PAGEVIEWS;
        $params['type'] = 'pageview';
        $params['body']['size'] = 0;

        // must not filter
        $must_not_filter = array();
        $must_not_filter['term']['userId'] = 0;

        // must filter
        $must_filter = array();
        $must_filter['term']['isStudyAbroad'] = 'yes';
        $must_filter['range']['visitTime'] = array(
        	'gte' => $dateRange['startDate'].'T00:00:00',
        	'lte' => $dateRange['endDate'].'T23:59:59'
        	);

        //$params['body']['fields'] = array('userId');
        $params['body']['query']['filtered']['filter']['bool']['must_not'] = $must_not_filter;
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isStudyAbroad'] = 'yes';
        $params['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['gte'] = '2016-01-01T00:00:00';
        $params['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['lte'] = '2016-11-30T23:59:59';
		/*        $params['body']['aggs']['userIds']['terms'] = array(
        	'field'=>'userId',
        	'size'=>5499
        	);*/
        $search = $this->clientCon->search($params);

        $totalCount = $search['hits']['total'];

        $params['body']['size'] = $totalCount;
        $params['body']['fields'] = array('userId');
        $search = $this->clientCon->search($params);
        $search = $search['hits']['hits'];
        $userId = array();
        foreach ($search as $key => $value) {
        	$userId[$value['fields']['userId'][0]] = 1;
        	unset($search[$key]);
        }
        $userIds = $userId;
        //_p($userId);die;
        //$userId = array_keys($userId);
        //return count($userId);
        return $userId;
    }

    function index($date,&$userIds){
    	/*$date['startDate'] = '2016-01-01';
    	$date['endDate'] = '2016-01-02';*/
    	$params = array();
        $params['index'] = "shiksha_trafficdata_pageviews";
        $params['type'] = 'pageview';
        $params['body']['size'] = 0;

        // must not filter
        $must_not_filter = array();
        $must_not_filter['term']['userId'] = 0;

        // must filter
        $must_filter = array();
        $must_filter['term']['isStudyAbroad'] = 'yes';
        $must_filter['range']['visitTime'] = array(
        	'gte' => $dateRange['startDate'].'T00:00:00',
        	'lte' => $dateRange['endDate'].'T23:59:59'
        	);

        //$params['body']['fields'] = array('userId');
        $params['body']['query']['filtered']['filter']['bool']['must_not'] = $must_not_filter;
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isStudyAbroad'] = 'yes';
        $params['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['gte'] = $date['startDate'].'T00:00:00';
        $params['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['lte'] = $date['endDate'].'T23:59:59';
		/*        $params['body']['aggs']['userIds']['terms'] = array(
        	'field'=>'userId',
        	'size'=>5499
        	);*/
        $search = $this->clientCon->search($params);

        $totalCount = $search['hits']['total'];

        $params['body']['size'] = $totalCount;
        $params['body']['fields'] = array('userId');
        $search = $this->clientCon->search($params);
        $search = $search['hits']['hits'];
        foreach ($search as $key => $value) {
        	$userIds[$value['fields']['userId'][0]] = 1;
        	unset($search[$key]);
        }
        //_p($userIds);die;
        //$userId = array_keys($userId);
        return count($userIds);
        return $userId;
    }

    function courseDetails(){        
        $course = 25094912212243;
        $this->CI->load->builder("nationalCourse/CourseBuilder");
                $courseBuilder = new CourseBuilder();
                $courseRepo = $courseBuilder->getCourseRepository();
                $courseObj = $courseRepo->find($course);
                $curseId = $courseObj->getId();
                if(empty($curseId)){
                    echo 'course object is blank for courseId = '.$newCourse;
                }else{
                    $courseName = $courseObj->getName();
                    _p($courseName);
                    $cityName = $courseObj->getMainLocation()->getCityName();
                    _p($cityName);
                }
    }

    // check SMS status
    function checkSMSStatus(){
        $xmlMessage = '<?xml version="1.0" encoding="ISO-8859-1"?>
        <!DOCTYPE STATUSREQUEST SYSTEM "http://127.0.0.1:80/psms/dtd/requeststatusv12.dtd">
        <STATUSREQUEST VER="1.2">
            <USER USERNAME="shikshaot" PASSWORD="shikst02"/>
            <GUID GUID="ki1c2595787152f4100008yn93qSHIKSHAOT" ><STATUS SEQ="1" /></GUID>
            <GUID GUID="ki23m530680131f410000z9o8w2SHIKSHAOT" ><STATUS SEQ="1" /></GUID>
            <GUID GUID="ki23m525600753f410000v42ypnSHIKSHAOT" ><STATUS SEQ="1" /></GUID>
            <GUID GUID="ki23m520680852f410000175kt5SHIKSHAOT" ><STATUS SEQ="1" /></GUID>
            <GUID GUID="ki23m505146532f4100004ezrpaSHIKSHAOT" ><STATUS SEQ="1" /></GUID>
        </STATUSREQUEST>';

        _p($xmlMessage);
        $response = $this->makeSmsCURLcall($xmlMessage,'status');
        $response = simplexml_load_string($response);
        _p($response);
    }

    // check SMS status by date
    //https://localshiksha.com/trackingMIS/testES/listDeliveredSMS
    function listDeliveredSMS(){
        /*$xmlMessage = '<?xml version="1.0" encoding="ISO-8859-1"?><!DOCTYPE STATUSREQUEST SYSTEM "http://127.0.0.1:80/psms/dtd/requeststatusv12.dtd"><STATUSREQUEST><USER USERNAME="shikshaot" PASSWORD="shikst02"/><GUID GUID="ki23m500036932f410000n12k3xSHIKSHAOT"><STATUS SEQ="1" /></GUID></STATUSREQUEST>';*/
        $xmlMessage = '<?xml version="1.0" encoding="ISO-8859-1"?>
            <!DOCTYPE SCHEDULE_LIST SYSTEM "http://127.0.0.1:80/psms/dtd/schedule_sq.dtd">
            <SCHEDULE_LIST ACTION="LIST">
            <USER USERNAME="shikshaot" PASSWORD="shikst02"/>
            <CONDITION STATUS_TYPE="PROCESSED" START_DATE="2018-01-22 00:02:10 GMT+5:30" END_DATE="2018-01-22 00:02:59dddd GMT+5:30" GUID=""/>
            </SCHEDULE_LIST>';

        _p($xmlMessage);
        $response = $this->makeSmsCURLcall($xmlMessage,'schedule');
        $response = simplexml_load_string($response);
        _p($response);
    }

    function makeSmsCURLcall($xmlMessage,$status)
    {
        $time = microtime(true);
        $data = 'data='.urlencode($xmlMessage).'&action='.$status;
        $url = "http://api.myvaluefirst.com/psms/servlet/psms.Eservice2";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, 0); // set url to post to
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/x-www-form-urlencoded"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
        curl_setopt($ch, CURLOPT_POST, 2);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        // Make the Curl Request
        $result=curl_exec($ch);
        
        $response = curl_exec($ch); 
        curl_close($process); 
        $time2 = microtime(true);
        //error_log('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'.($time2 - $time));
        return $response;
    }
    
    function VAScanDATA(){
        $model = $this->load->model('mailer/mailermodel');

        //$userIds = array(2128,2127,2126,2125);
        $userIds = array("edy@shiksha.com","nishtha.gulati@naukri.com");
        //$userIds = implode(",", $userIds);
        //$model->markUsersInMailQueue($userIds,22094,"product","praveenalerts@shiksha.com","praveen");
        $model->getMailContent(428190801,"2018-2-12");
    }   

    function test(){

        $strArray = array("StudentName"=>"mintukumartest",
                        "email"=>"kumarisonam@gmail.com",
                        "mobileNumber"=>"9852781198",
                        "courseId"=>"5",
                        "cityId"=>"1",
                        "stateId"=>"1",
                        "utm_sitetarget"=>"",
                        "utm_source"=>"media",
                        "utm_medium"=>"shiksha",
                        "url"=>"www.shiksha.com",
                        "ip"=>"0.0.0.0",
                        "utm_content"=>"ManipalUniversity",
                        "utm_term"=>"Bihar",
                        "utm_network"=>"Patna"
        );

        $str= '';
        //$retArr[$userId]['str'].= urlencode($k)."=".urlencode($v)."&";
        foreach ($strArray as $key => $value) {
            $str .=$key."=".$value."&";
        }
        $str = substr($str, 0,-1);
        //_p($str);die;
        $url = "https://mu-uat.manipaltech.net//bin/manipal/components/generate/thirdpartyrfileads";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            $url );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT,        10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $str);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'apikey: 52f204f9-1577-46c9-bd59-7d98a6cc5cf2'
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        print_r($response);
    }

    function testSoapWsdl1() {

        $wsdl_url='https://c54web1.saas.talismaonline.com/cofiservicec54/cof.asmx?wsdl';
        $client = new SOAPClient($wsdl_url, array("trace" => true,"exceptions" => true));
        $client->__setSoapHeaders(Array(new WsseAuthHeader("serviceadmin", "talisma")));
            $default_Learning = "13";
        $PropertyInfo=array();
        $PropertyInfo[]=array('propertyID'=>'4800002','propValue'=>'praveen_test','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'4810065','propValue'=>'prveen_test@shiksha.com' ,'rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1'); 


            
            $PropertyInfo[]=array('propertyID'=>'4810051','propValue'=>'9899765435',
            'rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        
            // for dx conversion id
            $PropertyInfo[]=array('propertyID'=>'24422','propValue'=>'1',
                'rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

            $PropertyInfo[]=array('propertyID'=>'24447','propValue'=>'1',
                'rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

            $PropertyInfo[]=array('propertyID'=>'23044','propValue'=>'4','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

            $PropertyInfo[]=array('propertyID'=>'4800003','propValue'=>'29',
                'rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

            $PropertyInfo[]=array('propertyID'=>'4800005','propValue'=>'9','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');
            $PropertyInfo[]=array('propertyID'=>'23145','propValue'=>'2',
                'rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

            $PropertyInfo[]=array('propertyID'=>'4810021','propValue'=>'508',
            'rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

            $PropertyInfo[]=array('propertyID'=>'4810007','propValue'=>'12',
            'rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

            //for UTM parameters
            $PropertyInfo[]=array('propertyID'=>'24446','propValue'=>'1',
                'rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');
            //-----------------------                 
           
        $operationParams=array('ignoreMandatoryCheck'=>'true','updateReadOnlyProperties'=>'true');    
        $params=array(
            'objectType' => '20005',
            'objectName' => '',
            'ownerID' => '2',
            'teamID' => '',
            'strAddTeam'=>'',
            'propData'=> $PropertyInfo,
            
        );
        //_p($params);die;
        $response = $client->CreateObject($params);
        _p($response);//_p($client-> __getLastRequest());//_p($client->CreateObjectExResponse());
    }

    function testtt(){
        $xml = "<?xml version='1.0' encoding='utf-8'?>
        <soap>
            <soapHeader>
                <userName>serviceadmin</userName>
                <password>talisma</password>
                <extraHeaderData>
                    <TalismaSessionkey></TalismaSessionkey>
                </extraHeaderData>
            </soapHeader>
            <soapData>
                <CreateObjectEx>        
                    <objectType>20005</objectType>
                    <objectName></objectName>
                    <ownerID>2</ownerID>
                    <teamID>8</teamID>
                    <propData>
                        <PropertyInfo>
                            <propertyID>4800002</propertyID>
                            <propValue>Shiksha_Name</propValue>
                            <rowID>-1</rowID>
                            <relJoinID>-1</relJoinID>
                            <constraintId>-1</constraintId>
                        </PropertyInfo>
                        <PropertyInfo>
                            <propertyID>4810069</propertyID>
                            <propValue>8</propValue>
                            <rowID>-1</rowID>
                            <relJoinID>-1</relJoinID>
                            <constraintId>-1</constraintId>
                        </PropertyInfo>
                        <PropertyInfo>
                            <propertyID>4810051</propertyID>
                            <propValue>Shiksha_Mobile</propValue>
                            <rowID>-1</rowID>
                            <relJoinID>-1</relJoinID>
                            <constraintId>-1</constraintId>
                        </PropertyInfo>
                        <PropertyInfo>
                            <propertyID>23044</propertyID>
                            <propValue>1</propValue>
                            <rowID>-1</rowID>
                            <relJoinID>-1</relJoinID>
                            <constraintId>-1</constraintId>
                        </PropertyInfo>
                        <PropertyInfo>
                            <propertyID>4810065</propertyID>
                            <propValue>Shiksha_Email</propValue>
                            <rowID>-1</rowID>
                            <relJoinID>-1</relJoinID>
                            <constraintId>-1</constraintId>
                        </PropertyInfo>
                        <PropertyInfo>
                            <propertyID>4810007</propertyID>
                            <propValue>27</propValue>
                            <rowID>-1</rowID>
                            <relJoinID>-1</relJoinID>
                            <constraintId>-1</constraintId>
                        </PropertyInfo>
                        <PropertyInfo>
                            <propertyID>24422</propertyID>
                            <propValue>1</propValue>
                            <rowID>-1</rowID>
                            <relJoinID>-1</relJoinID>
                            <constraintId>-1</constraintId>
                        </PropertyInfo>
                        <PropertyInfo>
                            <propertyID>24446</propertyID>
                            <propValue>1</propValue>
                            <rowID>-1</rowID>
                            <relJoinID>-1</relJoinID>
                            <constraintId>-1</constraintId>
                        </PropertyInfo>
                        <PropertyInfo>
                            <propertyID>24447</propertyID>
                            <propValue>1</propValue>
                            <rowID>-1</rowID>
                            <relJoinID>-1</relJoinID>
                            <constraintId>-1</constraintId>
                        </PropertyInfo>
                        <PropertyInfo>
                            <propertyID>4810021</propertyID>
                            <propValue>940</propValue>
                            <rowID>-1</rowID>
                            <relJoinID>-1</relJoinID>
                            <constraintId>-1</constraintId>
                        </PropertyInfo>
                        <PropertyInfo>
                            <propertyID>24209</propertyID>
                            <propValue>30</propValue>
                            <rowID>-1</rowID>
                            <relJoinID>-1</relJoinID>
                            <constraintId>-1</constraintId>
                        </PropertyInfo>
                        <PropertyInfo>
                            <propertyID>4810007</propertyID>
                            <propValue>27</propValue>
                            <rowID>-1</rowID>
                            <relJoinID>-1</relJoinID>
                            <constraintId>-1</constraintId>
                        </PropertyInfo>
                        <PropertyInfo>
                            <propertyID>24408</propertyID>
                            <propValue>13</propValue>
                            <rowID>-1</rowID>
                            <relJoinID>-1</relJoinID>
                            <constraintId>-1</constraintId>
                        </PropertyInfo>
                    </propData>
                    <operationParams>
                        <ignoreMandatoryCheck>false</ignoreMandatoryCheck>
                        <updateReadOnlyProperties>true</updateReadOnlyProperties>
                    </operationParams>
                </CreateObjectEx>
            </soapData>
        </soap>";

        $returnArray =  json_decode(json_encode((array)simplexml_load_string($xml)),1);

        $data = $returnArray['soapData']['CreateObjectEx']['propData'];
        $data['PropertyInfo']['0']['constraintId'] = array('ddf'=> array("fdfd"));
        echo 'ds';_p($this->array_depth($data));
        _p($data);die;
    }

    function array_depth(array $array) {
        $max_depth = 1;

        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = $this->array_depth($value) + 1;

                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }

        return $max_depth;
    }

    function testSoapWsdl11() {

        $wsdl_url='https://c54web1.saas.talismaonline.com/cofiservicec54/cof.asmx?wsdl';
        $client = new SOAPClient($wsdl_url, array("trace" => true,"exceptions" => true));
        $client->__setSoapHeaders(Array(new WsseAuthHeader("serviceadmin", "talisma")));
            
        $PropertyInfo=array();
        $PropertyInfo[]=array('propertyID'=>'4800002','propValue'=>'praveen_test','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'4810065','propValue'=>'prveen_test@shiksha.com' ,'rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1'); 

        $PropertyInfo[]=array('propertyID'=>'4810051','propValue'=>'9899765435','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');
            
       
           
        $operationParams=array('ignoreMandatoryCheck'=>'true','updateReadOnlyProperties'=>'true');    
        $params=array(
            'objectType' => '20005',
            'objectName' => '',
            'ownerID' => '2',
            'teamID' => '',
            'strAddTeam'=>'',
            'propData'=> array("PropertyInfo" => $PropertyInfo)
        );
        
        $response = $client->CreateObject($params);
        _p($response);
    }

    function testSoapWsdl_1() {

        $wsdl_url='https://c54web1.saas.talismaonline.com/cofiservicec54/cof.asmx?wsdl';
        $client = new SOAPClient($wsdl_url, array("trace" => true,"exceptions" => true));
        $client->__setSoapHeaders(Array(new WsseAuthHeader("serviceadmin", "talisma")));
            
        $PropertyInfo=array();
        $PropertyInfo[]=array('propertyID'=>'4800002','propValue'=>'praveen_test','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'4810065','propValue'=>'prveen_test@shiksha.com' ,'rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1'); 

        $PropertyInfo[]=array('propertyID'=>'4810051','propValue'=>'9899765435','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');
            
        $PropertyInfo[]=array('propertyID'=>'24422','propValue'=>'1','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');
            
        $PropertyInfo[]=array('propertyID'=>'24447','propValue'=>'1','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'23044','propValue'=>'4','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'4800003','propValue'=>'29','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'4800005','propValue'=>'9','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'23145','propValue'=>'2','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'4810021','propValue'=>'508','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'4810007','propValue'=>'12','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'24446','propValue'=>'1','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');
           
        $operationParams=array('ignoreMandatoryCheck'=>'true','updateReadOnlyProperties'=>'true');    
        $params=array(
            'objectType' => '20005',
            'objectName' => '',
            'ownerID' => '2',
            'teamID' => '',
            'strAddTeam'=>'',
            'propData'=> array("PropertyInfo" => $PropertyInfo)
        );
        
        $response = $client->CreateObject($params);
        _p($response);
    }
    
    function testSoapWsdl() {
        $wsdl_url='https://c54web1.saas.talismaonline.com/cofiservicec54/cof.asmx?wsdl';
        $client = new SOAPClient($wsdl_url, array("trace" => true,"exceptions" => true));
        $client->__setSoapHeaders(Array(new WsseAuthHeader("serviceadmin", "talisma")));
            
        $PropertyInfo=array();
        $PropertyInfo[]=array('propertyID'=>'4800002','propValue'=>'praveen_test','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'4810065','propValue'=>'prveen_test@shiksha.com' ,'rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1'); 

        $PropertyInfo[]=array('propertyID'=>'4810051','propValue'=>'9899765435','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');
            
        $PropertyInfo[]=array('propertyID'=>'24422','propValue'=>'1','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');
            
        $PropertyInfo[]=array('propertyID'=>'24447','propValue'=>'1','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'23044','propValue'=>'4','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        //$PropertyInfo[]=array('propertyID'=>'4800003','propValue'=>'29','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'4800005','propValue'=>'9','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        //$PropertyInfo[]=array('propertyID'=>'23145','propValue'=>'2','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'4810021','propValue'=>'508','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'4810007','propValue'=>'12','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');

        $PropertyInfo[]=array('propertyID'=>'24446','propValue'=>'1','rowID'=>'-1','relJoinID'=>'-1','constraintId'=>'-1');
           
        $operationParams=array('ignoreMandatoryCheck'=>'true','updateReadOnlyProperties'=>'true');    
        $params=array(
            'objectType' => '20005',
            'objectName' => '',
            'ownerID' => '2',
            'teamID' => '',
            'strAddTeam'=>'',
            'propData'=> array("PropertyInfo" => $PropertyInfo)
        );
        
        $response = $client->CreateObject($params);
        _p($response);
    }

    function removeBlankData($params){
        foreach ($params as $key => $arrayDepthOne) {
            if(is_array($arrayDepthOne)){
                if(count($arrayDepthOne) > 0){
                    foreach ($arrayDepthOne as $depthOneKey => $arrayDepthTwo) {
                        if(is_array($arrayDepthTwo)){
                            if(count($arrayDepthTwo) > 0){
                                foreach ($arrayDepthTwo as $depthTwoKey => $arrayDepthThree) {
                                    if(is_array($arrayDepthThree)){
                                        if(count($arrayDepthThree) > 0){
                                            foreach ($arrayDepthThree as $depthThreeKey => $value) {
                                                if(is_array($value) && count($value) <=0){
                                                    $params[$key][$depthOneKey][$depthTwoKey][$depthThreeKey] = '';
                                                }
                                            }
                                        }else{$params[$key][$depthOneKey][$depthTwoKey] = '';
                                        }
                                    }
                                }
                            }else{
                                $params[$key][$depthOneKey] = '';
                            }
                        }
                    }
                }else{
                    $params[$key] = '';
                }
            }
        }
        return $params;
    }
}


/* class for header */
class WsseAuthHeader extends SoapHeader {    
    function __construct($user, $pass) {
        $wss_ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';    
        $wsu_ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';        
        $created = gmdate('Y-m-d\TH:i:s\Z');
        $nonce = mt_rand();
        $passdigest = base64_encode( pack('H*', sha1( pack('H*', $nonce) . pack('a*',$created).  pack('a*',$pass))));
        
        $auth = new stdClass();
        $auth->Username = new SoapVar($user, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns);
        $auth->Password = new SoapVar($pass, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns);
        $auth->Nonce = new SoapVar($passdigest, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns);
        $auth->Created = new SoapVar($created, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wsu_ns);
        $auth->TalismaSessionkey = new SoapVar('', XSD_STRING, NULL, $this->wss_ns, NULL, $this->wsu_ns);
        
        $username_token = new stdClass();
        $username_token->UsernameToken = new SoapVar($auth, SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'UsernameToken', $this->wss_ns);
        
        $security_sv = new SoapVar(
        new SoapVar($username_token, SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'UsernameToken', $this->wss_ns),
        SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'Security', $this->wss_ns);
        parent::__construct($this->wss_ns, 'Security', $security_sv, true);
    }
}
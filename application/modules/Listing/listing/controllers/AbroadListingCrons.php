<?php
class AbroadListingCrons extends MX_Controller{
    private $inputData = array('UNIV' => array( 'entityType'                => 'university',
                                                'urlPattern'                => '-univlisting-',
                                                'timePeriodForDataAnalysis' => '3 months',//'6 months',
                                                'noOfTopItemsToStore'       => 10,
                                                'dataInputSource'           => 'getUniversityToUserData',
                                                'dataOutputSource'          => 'setUniversityLogLikeliHoodAnalysisData'
                                                )
                                );
    
    function __construct() {
        parent::__construct();
    }
    
    public function logLikeliHoodAnalysis() {
		$this->validateCron(); // prevent browser access
        //error_log('logliklihood_script_start'.date('H:i:s').PHP_EOL);
        $this->benchmark->mark('start_cron');
        ini_set('max_execution_time', (18000)); // setting max execution time for script to be 100 minutes
        ini_set('memory_limit', '1000M');   // setting max memory limit for script to be 1000 MB

        $logLikeliHoodAnalysis = $this->load->library('common/recommendationAlgorithms/LogLikeliHoodAnalysis');
        $logLikeliHoodAnalysis->performLogLikeliHoodAnalysis($this->inputData['UNIV']);
        //error_log('ALL DONE Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/logliklihood.txt');
        
        $this->benchmark->mark('end_cron');
        $timeElapsed = $this->benchmark->elapsed_time('start_cron', 'end_cron');
        $lib = $this->load->library('common/studyAbroadCommonLib');
		$lib->selfMailer('logLikeliHoodAnalysis Generated For university',
                                'Cron executed successfully at '.date('d-m-Y H:i:s').
                                '<br><b>Cron Location:</b> /var/www/html/shiksha/application/modules/Listing/listing/controllers/AbroadListingCrons.php'.
                                '<br><b>Method:</b> /listing/AbroadListingCrons/logLikeliHoodAnalysis/'.
                                '<br><br><b>Total memory allocated (MB) :</b> '.(memory_get_peak_usage(TRUE)/(1024*1024)).
                                '<br><b>Total time (s):</b> '.$timeElapsed);
        echo 'SUCCESS';
        exit(0);
    }
    public function CooccurrenceBasedRecommendationGenerator($entityType = 'content') {
		$this->validateCron(); // prevent browser access
		$this->benchmark->mark('start_cron');
		ini_set('memory_limit', '800M');   // Saurabh :: max memory limit set to 800, a lot of data from studyAbroadUserMovementTracking is queried in this script& it requires this much memory
        $cooccurrenceRecommenderLib = $this->load->library('common/recommendationAlgorithms/CooccurrenceSimilarity');
        $cooccurrenceRecommenderLib->createCooccurrenceBasedRecommendations($entityType);
        //error_log('ALL DONE Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/logliklihood.txt');
		$this->benchmark->mark('end_cron');
		$timeElapsed = $this->benchmark->elapsed_time('start_cron', 'end_cron');
        $lib = $this->load->library('common/studyAbroadCommonLib');
		$lib->selfMailer('Cooccurrence Based Similarity Generated For '.$entityType,
						 'Cron executed successfully for creation of cooccurrence based similarity at '.date('d-m-Y H:i:s').
						 '<br><br><b>Cron Location:</b> /var/www/html/shiksha/application/modules/Listing/listing/controllers/AbroadListingCrons.php'.
						 '<br><br><b>Method:</b> /listing/AbroadListingCrons/CooccurrenceBasedRecommendationGenerator/'.
						 '<br><br><b>Total memory allocated (MB) :</b> '.(memory_get_peak_usage(TRUE)/(1024*1024)).
						 '<br><br><b>Total time (s):</b> '.$timeElapsed);
        echo 'SUCCESS';
        exit(0);
    }
	
	/*
	 * cron to generate & save in cache the country-course based average of
	 * 		1st yr fees,
	 * 		exam score average
	 * 	and country wise living expense
	 * 	Note: here by course we mean, desired course or a category course level combination
	 */
	public function createCountryWiseAverages()
	{
		$this->validateCron(); // prevent browser access
		echo "MEM".memory_get_usage();
		$this->benchmark->mark("start");
		$abroadListingCronLibrary = $this->load->library("AbroadListingCronLibrary");
		// create cache keys for all abroad courses
		$courseCacheKeys = $abroadListingCronLibrary->createAbroadCourseCacheKeys();
		// first year fees
		$abroadListingCronLibrary->createFirstYearFeeAverages();
		error_log("Country Average: done for fees at ".date("H:i:s"));
		// living expense
		$abroadListingCronLibrary->createLivingExpenseAverages();
		error_log("Country Average: done for living exp at ".date("H:i:s"));
		// exam score
		$abroadListingCronLibrary->createExamScoreAverages();
		error_log("Country Average: done for exam score at ".date("H:i:s"));
		$this->benchmark->mark("end");
		_p( "SRB1 time :: ".$this->benchmark->elapsed_time('start', 'end')."::mem::".memory_get_peak_usage());
		echo "MEM".memory_get_usage();
	}
	public function testCountryAverage($type,$key)
	{
		$abroadListingCache = $this->load->library("cache/AbroadListingCache");
		switch($type)
		{
			case 'fee': 	var_dump($abroadListingCache->getAverage1stYearFees($key)); break;
			case 'liv': 		var_dump($abroadListingCache->getAverageLivingExpense($key)); break;
			case 'exam': var_dump($abroadListingCache->getAverageExamScores($key)); break;
		}
		return;
	}
	
	
	public function calculateResponseCountForCourseByActionType(){
		$this->validateCron(); // prevent browser access
		ini_set('memory_limit', '800M');
		$this->benchmark->mark('start_cron');

		$abroadListingCronLibrary = $this->load->library("AbroadListingCronLibrary");
		$abroadListingCronLibrary->populateCourseResponseCountWithActionType(false);
		// error_log('Course Response Count: memory uses '.(memory_get_usage(TRUE)/(1024*1024)));

		$this->benchmark->mark('end_cron');
		$timeElapsed = $this->benchmark->elapsed_time('start_cron', 'end_cron');
		
		$commonStudyAbroadLib   = $this->load->library('common/studyAbroadCommonLib'); 
		$subject = 'calculateResponseCountForCourseByActionType cron ran successfully.';
		$msg .= "<br/>Cron executed successfully <br/>";
		$msg .= '<br/><br><b>Path :</b> /var/www/html/shiksha/application/modules/Listing/listing/controllers/AbroadListingCrons.php';
		$msg .= '<br><br><b>Method:</b> /listing/AbroadListingCrons/calculateResponseCountForCourseByActionType';
		$msg .= '<br/><br><b>Memory uses (MB):</b> '.(memory_get_peak_usage(TRUE)/(1024*1024)).'<br/>';
		$msg .= '<br><b>Total time (s):</b> '.$timeElapsed;
		$msg .= '<br/><br/>Regards,<br/>SA Team';
		$commonStudyAbroadLib->selfMailer($subject,$msg);				
	}
	
	public function populateResponseCountForCourseByActionTypeDaily(){
		$this->validateCron(); // prevent browser access
		ini_set('memory_limit', '800M');
		$this->benchmark->mark('start_cron');

		$abroadListingCronLibrary = $this->load->library("AbroadListingCronLibrary");
		$abroadListingCronLibrary->populateCourseResponseCountWithActionType(true);
		error_log('Course Response Count: memory uses '.(memory_get_usage(TRUE)/(1024*1024)));

		$this->benchmark->mark('end_cron');
		$timeElapsed = $this->benchmark->elapsed_time('start_cron', 'end_cron');
		
		$commonStudyAbroadLib   = $this->load->library('common/studyAbroadCommonLib'); 
		$subject = 'populateResponseCountForCourseByActionTypeDaily cron ran successfully.';
		$msg .= "<br/>Cron executed successfully <br/>";
		$msg .= '<br/><br><b>Path :</b> /var/www/html/shiksha/application/modules/Listing/listing/controllers/AbroadListingCrons.php<br/>';
		$msg .= '<br><br><b>Method:</b> /listing/AbroadListingCrons/populateResponseCountForCourseByActionTypeDaily';
		$msg .= '<br/><br><b>Memory uses (MB):</b> '.(memory_get_peak_usage(TRUE)/(1024*1024)).'<br/>';
		$msg .= '<br><b>Total time (s):</b> '.$timeElapsed;
		$msg .= '<br/><br/>Regards,<br/>SA Team';
		$commonStudyAbroadLib->selfMailer($subject,$msg);				
	}
        public function populateElasticDataForApplyContent(){
            $ESConnectionLib = $this->load->library('trackingMIS/elasticSearch/ESConnectionLib');
            $elasticClientCon = $ESConnectionLib->getESServerConnection();
            $this->load->model('abroadlistingcronmodel');
            $abroadListingCronModel = new abroadlistingcronmodel();
            $abroadListingCronModel->updateApplyContentData($elasticClientCon);
        }


    public function populateDiffCourseIdPackTypeFromDbAndCache(){
		$this->validateCron();
        $this->benchmark->mark('code_start');
        $before = memory_get_usage();
        $date = date("d_m_Y");
        $fp = fopen('/tmp/PackType_'.$date.'.txt', 'w');
        $errMsg = "date----".$date."\n";
        $abroadListingCronLibrary = $this->load->library("AbroadListingCronLibrary");
        $dataFromdb = $abroadListingCronLibrary->getCourseIdAndPackTypeFromDb();
        $courseIds = $abroadListingCronLibrary->extractCourseIds($dataFromdb);
        $arr = array();
        $dataFromCache = array();
        $batch_of = 100;
        $batch = array_chunk($courseIds,$batch_of);
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder 			= new ListingBuilder;
        $abroadCourseRepository 	= $listingBuilder->getAbroadCourseRepository();
        $abroadInstituteRepository 		= $listingBuilder->getAbroadInstituteRepository();
        $abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
        foreach($batch as $b){
            $objs =$abroadCourseRepository->findMultiple($b);
            foreach ($objs as $courseId=>$obj){
                $dataFromCache[$obj->getId()] = array(
                    'course_id'=>$obj->getId(),
                    'pack_type'=>$obj->getCoursePackType(),
                );
            }
            unset($objs);
        }
        $a = count($dataFromCache);
        $b = count($dataFromdb);
        $errMsg .= "course_count_cache = $a    course_count_db = $b\n";
        if(count($dataFromCache) != count($dataFromdb))
		{
			$errMsg .= "errro course count doesn't match.\n";
            die;
		}
		$count = 0;
		foreach ($dataFromCache as $cId => $val)
		{
			if(!isset($dataFromdb[$cId]))
			{
                $count++;
                $errMsg .= 'error course id is not set in db  '.$cId."\n";
			}
			elseif($val['pack_type'] != $dataFromdb[$cId]['pack_type'])
			{
                $count++;
                $errMsg .= "error cache_pack_type = {$val['pack_type']}     DB pack type = {$dataFromdb[$cId]['pack_type']}      courseId = ".$cId.".......";
                $errMsg .= "refreshing cache for this course.......";
                if($cId>0){
                    global $forceListingWriteHandle; // used in ListingModelAbstract to acquire a write handle
                    $forceListingWriteHandle = true;
                    $abroadCourseRepository->disableCaching();
                    $abroadInstituteRepository->disableCaching();
                    $abroadUniversityRepository->disableCaching();
                    $courseObj = $abroadCourseRepository->find($cId);
                    $instId = $courseObj->getInstId();
                    if($instId>0) {
                        $abroadInstituteRepository->find($instId);
                    }
                    $univId = $courseObj->getUniversityId();
                    if($univId>0) {
                        $abroadUniversityRepository->find($univId);
                    }
                    $errMsg .= "Refresh Completed.\n";
				}
			}
		}
		$errMsg .= "Error count : $count\n";
        $after = memory_get_usage();
        $errMsg .= "memory : ".($after - $before)."\n";
		$errMsg .= "used Memory : ".memory_get_peak_usage(true)."\n";
        $this->benchmark->mark('code_end');
        $errMsg .= "execution time :- ".$this->benchmark->elapsed_time('code_start', 'code_end')." seconds";
        fwrite($fp,$errMsg);
        fclose($fp);
    }

    public function populateCityTableThumbUrlsAndCorrectedUrls(){
        $this->validateCron(); //prevent browser access

        set_time_limit(0);

        $this->benchmark->mark('code_start');

        $videoUrls = array();
        $abroadListingCronLibrary = $this->load->library("AbroadListingCronLibrary");
        $result = $abroadListingCronLibrary->updateDataInCityVideosTable();
        echo $result;
        error_log("populateCityTableThumbUrlAndCorrectUrl cron = ".$result);

        $errMsg = "Memory : ".memory_get_peak_usage(true)."\n";
        $this->benchmark->mark('code_end');
        $errMsg .= "execution time :- ".$this->benchmark->elapsed_time('code_start', 'code_end')." seconds";
        echo $errMsg;
    }


}

?>

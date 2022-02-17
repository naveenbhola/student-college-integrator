<?php
/*
Cron to get Count of total no of Institutes, Courses, Answers etc live on shiksha.These counts are displayed at the desktop Home Page.
Author : Bharat
*/
class homePageCron extends MX_Controller {

	public function homePageCountCron()
    {   
        $this->validateCron();
        $cacheLib = $this->load->library('cacheLib');
        $key = md5('nationalHomepageCounters_json');
        $this->load->model('home/homepagemodel');
        $this->load->helper('home/homepage');
        $this->load->library('studyAbroadHome/studyAbroadHomepageLibrary');
        $studyAbroadHomepageLibrary = new studyAbroadHomepageLibrary();
        $data['national'] = $this->homepagemodel->getNationalCountStats();
        $data['abroad'] = $studyAbroadHomepageLibrary->getCountStats();
        $mutiplesArr = array('instMul' => 1000, 'reviewMul' => 1000, 'quesMul' => 50000,'careerMul' =>10,'univMul'=>1000, 'courseMul' => 1000, 'countryMul' => 5 , 'examMul' => 10);
        $data = getRoundOffValues($data, $mutiplesArr);
	//Cache Expiration time = 1 week + 1 hour  = 2592000 + 3600 = 2595600
	$cacheExpirationTime = 2595600;
        $cacheLib->store($key, json_encode($data), $cacheExpirationTime);
        echo "Cron Run successfully.";
    }

}
?>

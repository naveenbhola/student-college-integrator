<?php
class CronToCleanupEntityName extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        //$this->inputArr = array('exam','basecourse','institute');
        $this->inputArr = array('exam','basecourse');
        $this->entitycleanupmodel = $this->load->model("common/entitycleanupmodel");
        $this->entitycleanuplib   =$this->load->library('common/EntityCleanupLib');
        $this->cache              = $this->load->library('common/CleanUpEntityCache');
    }


    function storeData(){
    	$this->validateCron();
		$this->storeMappingInDatabase();
		$this->storeMappingInCache();
    }

	function storeMappingInDatabase(){
		$actualData    = $this->entitycleanupmodel->getEntityName($this->inputArr);
		$processedData = $this->entitycleanuplib->removeSpecialCharsAndSpaces($actualData);
		$res           = $this->entitycleanupmodel->saveIntoDatabase($actualData,$processedData,$this->inputArr);
		if($res){
			echo 'DB Done!!!';
		}else{
			echo 'DB Error!!!';
		}
	}

	function storeMappingInCache(){
		$data    = $this->entitycleanupmodel->getData($this->inputArr);
		foreach ($this->inputArr as $key => $entityName) {
			$this->cache->deleteCache($entityName,"cleanedupentity");
			$this->cache->storeEntity($entityName, $data[$entityName]);
		}
		echo "Cache Done!!!";
	}

	function getMappingFromCache(){
		$data    = array();
		foreach ($this->inputArr as $key => $entityName) {
			$data[$entityName] = $this->cache->getEntity($entityName);
		}
		$resultData = array();
		foreach ($this->inputArr as $key => $entityName) {
			$count = 0;
			foreach ($data[$entityName] as $k => $v) {
				$resultData[$entityName][$count] = $v;
				$count++;
			}
		}
		return $resultData;
	}

	function runCleaningProcess($entity){
		if(empty($entity)){
			return;
		}
		$cachedData = $this->getMappingFromCache();
		foreach ($this->inputArr as $key => $entityName) {
			if(empty($cachedData[$entityName])){
				$returnData = $this->entitycleanupmodel->getData(array($entityName));
				$data[$entityName]    = $returnData[$entityName];
			}else{
				$data[$entityName]   =  $cachedData[$entityName];
			}
		}
		//_p($data[$entityName]);die;
		if($entity=='question'){
			$time = '1172800';
			$this->load->model('QnAModel');
        	$msgArrayCatCountry = array();
        	$questionList = $this->QnAModel->getQuestionList($time);
        	$output = exec("/usr/bin/python2.7 application/config/entityAutoModeration.py ".escapeshellarg(json_encode($questionList))." ".escapeshellarg(json_encode($data)));
        	//print_r($output);die;
        	$resultData = json_decode($output, true);
        	$finalArr = array();
        	foreach ($questionList as $key => $value) {
        		$finalArr[$key]['actualText']   = $value;
        		$finalArr[$key]['modifiedText'] = $resultData[$key];
        	}
        	print_r($finalArr);
		}
	}
}

?>

<?php

class PortingRepository
{
	private $CI;
	private $model;

	function __construct($model)
	{
		$this->CI = & get_instance();
		$this->model = $model;
		$this->CI->load->entities(array('PortingEntity'));
	}

	public function getAllLivePortings($type)
	{
		$portings = array();
		$portingDataset = $this->model->getAllLivePortings($flag,$portingTime,$type);
		foreach($portingDataset as $portingData) {
			$portings[] = $this->buildPortingObject($portingData);
		}
		return $portings;
	}
	
	public function getAllLiveEmailPortings($portingTime)
	{
		$portings = array();
		$portingDataset = $this->model->getAllLivePortings('Email',$portingTime);
		
		foreach($portingDataset as $portingData) {
			$portings[] = $this->buildPortingObject($portingData);
		}
		return $portings;
	}	

	public function getAllLiveExamResponsePortings(){
		$examPortingLib = $this->CI->load->library('lms/porting/portingLib');
		//fwrite($fp, "Get Live Exam Porting Start ==".time()."\n");
		$data = $examPortingLib->getAllLivePortings('examResponse');
		//fwrite($fp, "Get Live Exam Porting End ==".time()."\n");

		$portingDataSet = array();
		$portings = array();
		if(count($data['portingIds'])>0){
			//fwrite($fp, "Get Live Exam Porting Details Start ==".time()."\n");
			$portingDataSet = $examPortingLib->getPortingDetails($data['portingIds'],'examResponse');
			//fwrite($fp, "Get Live Exam Porting Details End ==".time()."\n");
		}
		//_p($portingDataSet);die;
		foreach($portingDataSet as $portingData) {
			$portings[] = $this->buildPortingObject($portingData);
		}

		$data['portings'] = $portings;
		unset($data['portingIds']);
		return $data;
	}

	private function buildPortingObject($portingData)
	{
		return new PortingEntity($portingData);
	}

}

<?php

class CareerFinder {
	
	private $_ci;
	
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->searchModel 	= new SearchModel();
	}
	
	public function getData($id = null) {
		if($id == null){
			return array();
		}
		$this->_ci->load->builder('CareerBuilder','Careers');
		$careerBuilder 		= new CareerBuilder;
		$careerRepository 	= $careerBuilder->getCareerRepository();
		$careerDataObject 	= $careerRepository->find($id);
		$careerIndexData 	= false;
		if(!empty($careerDataObject)){
			$careerIndexData = $this->preprocessRawData($careerDataObject);
		}
		return $careerIndexData;
	}
	
	public function preprocessRawData($careerDataObject = null) {
		$career = array();
		$career['facetype'] 		= 'career';
		$career['career_id'] 		= $careerDataObject->getCareerId();
		$career['career_name'] 		= $careerDataObject->getName();
		$career['career_url'] 		= $careerDataObject->getCareerUrl();
		$career['career_synonyms'] 	= $this->searchModel->getCareerSynonyms($career['career_id']);
		$career['unique_id'] 		= 'career_' . $career['career_id'];
		
		return $career;
	}
}

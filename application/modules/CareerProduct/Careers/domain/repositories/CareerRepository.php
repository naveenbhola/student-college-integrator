<?php
class CareerRepository extends EntityRepository
{
	/*function __construct()
    	{
      	  $this->CI = &get_instance();    
	  $this->CI->load->library('InstituteRepository','listing');
    	}*/	

	function index(){
		echo "Career Finder";
	}	
	
	function __construct($dao,$cache,$model)
	{  
		parent::__construct($dao,$cache,$model);
		/*
		 * Load entities required
		 */
		$this->CI->load->entities(array('Career','CareerPath','CareerPathStep'),'Careers');
	}

	function find($careerId){
		$careerDataResults = $this->model->getCareerData($careerId);
		$career = $this->_loadOne($careerDataResults);
		if(!empty($career)){
			return current($career);
		}
		else{
			return $career;
		}
      
	}

	function findMultiple($careerIds){
		$careerDataResults = $this->model->getMultipleCareerData($careerIds);
		$career = $this->_loadMultiple($careerDataResults);
		return $career;
	}
	
	function findAll(){ 
		$careerIds = $this->model->getAllCareerIds();
		$careerDataResults = $this->model->getMultipleCareerData($careerIds);
		$career = $this->_loadMultiple($careerDataResults);
		return $career;
	}

	private function _loadMultiple($results)
	{
		$careers = $this->_load($results);
		return ($careers);
	}

	private function _loadOne($results)
	{
		$careers = $this->_load($results);
		return ($careers);
	}
	
	public function getCareerList($type,$format='object'){
		$careerDataResults = $this->model->getAllCareers($type);
		$careerList = $this->_loadMultiple($careerDataResults);
		if($format == 'array'){
			$i=0;
			foreach ($careerList as $key => $value) {
				$data[$i]['id'] = $value->getCareerId();
				$data[$i]['name'] = $value->getName();
				$i++;
			}
			$returnVar = $data;
		}
		else{
			$returnVar = $careerList;
		}
		return $returnVar;
	}

	public function getExpressInterestDetails(){
		$expressInterest = $this->model->getExpressInterestDetails();
		return $expressInterest;
	}

	private function _load($results)
	{
		$careers = array();
		$i=1;
		if(is_array($results) && count($results)) {
			foreach($results as $careerId => $result){
				$career = $this->_createCareer($result);
				foreach($result['CareerPathResults'] as $careerPathResults){
					$careerPathObj = $this->_createCareerPath($careerPathResults['pathId'],$careerPathResults['pathName']);
					foreach($careerPathResults['steps'] as $steps){
						$careerStepObj = $this->_createCareerPathStep($steps);
						$careerPathObj->addStep($careerStepObj);
					}
					$career->addPath($careerPathObj);
				}
				$careers[$careerId] = $career;
			}
		}
		return $careers;
	}

	private function _createCareer($result)
	{
		$career = new Career;
		$careerData = (array) $result;
		$this->fillObjectWithData($career,$careerData);
		return $career;
	}
	private function _createCareerPath($pathId,$pathName)
	{
		$result['pathId'] = $pathId;
		$result['pathName'] = $pathName;
		$careerPath = new CareerPath;
		$careerPathData = (array) $result;
		$this->fillObjectWithData($careerPath,$careerPathData);
		return $careerPath;
	}
	private function _createCareerPathStep($result)
	{
		$careerPathSteps = new CareerPathStep;
		$careerPathStepsData = (array) $result;
		$this->fillObjectWithData($careerPathSteps,$careerPathStepsData);
		return $careerPathSteps;
	}


	/*public function getRecommendedCareers($expressInterestFirst,$expressInterestSecond,$stream,$careerId){
		$careerDataResults = $this->model->getRecommendedCareerData($expressInterestFirst,$expressInterestSecond,$stream,$careerId);
		$careerList = $this->_loadMultiple($careerDataResults);
		return $careerList;
	}*/
	
	public function getRecommendedCareers($careerId){
		$careerDataResults = $this->model->getRecommendedCareerData($careerId);
		$careerList = $this->_loadMultiple($careerDataResults);
		return $careerList;
	}
	public function getSuggestedCareers($expressInterestFirst,$expressInterestSecond,$stream){
		$careerDataResults = $this->model->getSuggestedCareers($expressInterestFirst,$expressInterestSecond,$stream);
		$careerList = $this->_loadMultiple($careerDataResults);
		return $careerList;
	}
}
?>

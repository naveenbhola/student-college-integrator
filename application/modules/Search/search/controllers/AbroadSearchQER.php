<?php

class AbroadSearchQER extends MX_Controller {
	//private $searchQERLib;
	private $debug = false;
	private $durationInHours;
	private $qerSATables;
	private $abroadSearchModel;
	private $abroadSearchQerModel;
	public function __construct(){
		$this->durationInHours = 24;
		$this->qerSATables = array(
								   'CITY' 					=> 'city',
								   'STATE'					=> 'state',
								   'COUNTRY'				=> 'country',
								   'CONTINENT'			=> 'continent',
								   'UNIVERSITY'			=> 'universities',
								   'DEPARTMENT'		=> 'institute',
								   'EXAMS'					=> 'exams',
								   'CATEGORY'			=> 'categories',
								   'SUBCATEGORY'		=> 'subcategories',
								   'LEVEL'					=> 'level',
								   'DESIRED_COURSE'	=> 'course',
								   'SPECIALIZATION'	=> 'specialization'
								   );
		$this->abroadSearchModel = $this->load->model('search/abroadsearchmodel');
		$this->abroadSearchQerModel = $this->load->model('search/abroadsearchqermodel');
		$this->load->config("SASearch/SASearchIndexConfig");
	}
	
	/*
	 * main function to populate values for all tables into query_entity_recognition_studyabroad
	 * this function operates in two ways:
	 * (1) it can be run as one-time script & can populate complete db from the beginning, OR
	 * (2) it can skip tables for which the values are not added via any interface (& dont have any chance of getting updated later)
	 * 		and for other tables it takes up changes in data since 'durationInHours' number of hours
	 * @param: $scriptFlag (true => case (1)), false by default
	 */
	public function populateQERAbroad($scriptFlag = false)
	{
		$this->validateCron(); // prevent browser access
		$scriptFlag = $scriptFlag =='true'?true:false;
		if($scriptFlag !== true)
		{
			$durationStartDatetime = date('Y-m-d H:i:s',  strtotime("-".$this->durationInHours." hours"));
			$this->removeDeletedEntitiesQERAbroad(); // remove deleted entries
		}
		// get data for various entities
		$qerTableData = array(); // indexes should be names of tables in qer DB
		/***************** Locations *******************/
		// 1. city [table name : city]
		$cities = $this->abroadSearchModel->getCitiesForQER($durationStartDatetime);
		$qerTableData[$this->qerSATables['CITY']] = $this->addSynonymToCity($cities);
		if($scriptFlag === true)
		{
			// 2. state [table name : state]
			$states = $this->abroadSearchModel->getStatesForQER();
			$qerTableData[$this->qerSATables['STATE']] = $this->addSynonymToState($states);
			// 3. country [table name : country]
			$countries = $this->abroadSearchModel->getCountriesForQER();
			$qerTableData[$this->qerSATables['COUNTRY']] = $this->addSynonymToCountry($countries);
			// 4. continent [table name : continent]
			$qerTableData[$this->qerSATables['CONTINENT']] = $this->abroadSearchModel->getContinentsForQER();
		}
		//_p(array_keys($qerTableData));
		/***************** univ & dept *******************/
		// 5. universities [table name : universities]
		$qerTableData[$this->qerSATables['UNIVERSITY']] = $this->abroadSearchModel->getUniversitiesForQER($durationStartDatetime);
		// 6. department [table name : institute]
		$qerTableData[$this->qerSATables['DEPARTMENT']] = $this->abroadSearchModel->getInstitutesForQER($durationStartDatetime);
		/*********** cat, subcat, course level, exam **********/
		if($scriptFlag === true){
			$categories = $this->abroadSearchModel->getCategoriesForQER(); // $durationStartDatetime
			$qerTableData[$this->qerSATables['CATEGORY']] = $this->addSynonymToCategory($categories); // $durationStartDatetime
			$qerTableData[$this->qerSATables['SUBCATEGORY']] = $this->abroadSearchModel->getSubcategoriesForQER();
			$levels = $this->abroadSearchModel->getLevelsForQER();
			$qerTableData[$this->qerSATables['LEVEL']] = $this->addSynonymToLevels($levels);
			$qerTableData[$this->qerSATables['EXAMS']] = $this->abroadSearchModel->getExamsForQER();
		}
		/********* specialization, desired course ************/
		$qerTableData[$this->qerSATables['DESIRED_COURSE']] = $this->abroadSearchModel->getDesiredCoursesForQER();
		$qerTableData[$this->qerSATables['SPECIALIZATION']] = $this->abroadSearchModel->getSpecializationsForQER();
		//_p($qerTableData);
		
		// insert into query_entity_recognition_studyabroad database tables
		$this->abroadSearchQerModel->insertQERTableData($qerTableData);
		echo "SYNCHRONIZATION COMPLETE";
		return true;
	}
	/*
	 * function to remove deleted entities like university & institute
	 * based on a duration.
	 */
	public function removeDeletedEntitiesQERAbroad(){
		//$this->durationInHours = 24;
		//$durationStartDatetime = date('Y-m-d H:i:s',  strtotime("-".$this->durationInHours." hours"));
		$qerTableDeletionData= array();
		$qerTableDeletionData[$this->qerSATables['UNIVERSITY']] = $this->abroadSearchModel->getUniversitiesForQER($durationStartDatetime, true);
		$qerTableDeletionData[$this->qerSATables['DEPARTMENT']] = $this->abroadSearchModel->getInstitutesForQER($durationStartDatetime, true);
		_p($qerTableDeletionData);
		// insert into query_entity_recognition_studyabroad database tables
		$this->abroadSearchQerModel->deleteFromQERTableData($qerTableDeletionData);
		echo "DELETION COMPLETE";
		return true;
	}
	
	/*
	 * add synonyms to levels
	 */
	public function addSynonymToLevels($levels)
	{
		// load level synonym config
		$qerSynonym = $this->config->item('QER_SYNONYM_MAPPING');
		$levelArr = array();
		foreach($levels as $level)
		{
			$level['more_popular_abb'] = $qerSynonym['LEVEL'][str_replace(' ','_',$level['name'])];
			$levelArr[] = $level;
		}
		return $levelArr;
	}
	/*
	 * add synonyms to category
	 */
	public function addSynonymToCategory($categories)
	{
		// load category synonym config
		$qerSynonym = $this->config->item('QER_SYNONYM_MAPPING');
		$categoryArr = array();
		foreach($categories as $category)
		{
			$category['more_popular_abb'] = $qerSynonym['CATEGORY'][$category['id']];
			$categoryArr[] = $category;
		}
		return $categoryArr;
	}
	/*
	 * add synonyms to category
	 */
	public function addSynonymToCity($cities)
	{
		// load category synonym config
		$qerSynonym = $this->config->item('LOCATION_SYNONYMS');
		$cityArr = array();
		foreach($cities as $city)
		{
			$city['more_popular_abb'] = $qerSynonym['CITIES'][$city['id']];
			$cityArr[] = $city;
		}
		return $cityArr;
	}
	/*
	 * add synonyms to category
	 */
	public function addSynonymToState($states)
	{
		// load category synonym config
		$qerSynonym = $this->config->item('LOCATION_SYNONYMS');
		$stateArr = array();
		foreach($states as $state)
		{
			$state['more_popular_abb'] = $qerSynonym['STATES'][$state['id']];
			$stateArr[] = $state;
		}
		return $stateArr;
	}
	/*
	 * add synonyms to country
	 */
	public function addSynonymToCountry($countries)
	{
		// load country synonym config
		$qerSynonym = $this->config->item('LOCATION_SYNONYMS');
		$countryArr = array();
		foreach($countries as $country)
		{
			$country['more_popular_abb'] = $qerSynonym['COUNTRIES'][$country['id']];
			$countryArr[] = $country;
		}
		return $countryArr;
	}
}

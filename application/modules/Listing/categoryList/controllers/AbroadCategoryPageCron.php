<?php

class AbroadCategoryPageCron extends MX_Controller
{
    /*
     *Author: Rahul Bhatnagar
     *Purpose: To populate countries also registered on by other users for use on a widget on the category page.
     *Params: None
     */
    
    public function populateAlsoRegisteredCountries()
	{
		$this->validateCron(); // prevent browser access
        $model = $this->load->model('listing/listingmodel');
		$this->benchmark->mark("programStart");
		$chunkSize = 10000;
		$startPoint = 0;
		$courseCountryCorelevance = array();
		do{
			$rawData = $model->getAlsoRegisteredCountriesData($startPoint,$chunkSize);
			$userCourseCountryPref = array();
			foreach($rawData as $row){
				$userCourseCountryPref[$row['DesiredCourse']][$row['UserId']][] = $row['CountryId'];
			}
			foreach($userCourseCountryPref as $course => $userCountryPref){
				if($course == 0){ continue; }
				foreach($userCountryPref as $key=>$preferences){
					foreach($preferences as $countryId1){
						foreach($preferences as $countryId2){
							if($countryId1 != $countryId2 || count($preferences) == 1){
								if(empty($courseCountryCorelevance[$course][$countryId1][$countryId2])){
									$courseCountryCorelevance[$course][$countryId1][$countryId2] = 0;
								}
								$courseCountryCorelevance[$course][$countryId1][$countryId2]+=1;
							}
						}
					}
				}
			}
			$startPoint+=count($rawData);
			error_log("::SA-954:: Memory Usage : ".memory_get_usage());
		}while(count($rawData) == $chunkSize);
		$pushArray = array();
		foreach($courseCountryCorelevance as $course=>$k1){
			foreach($k1 as $countryId1=>$k2){
				foreach($k2 as $countryId2=>$count){
					$row = array();
					$row['specializationId'] = $course;
					$row['parentCountry'] = $countryId1;
					$row['relatedCountry'] = $countryId2;
					$row['count'] = $count;
					$pushArray[] = $row;
				}
			}
		}
		$model->saveAlsoRegisteredCountriesData($pushArray);
		$this->benchmark->mark("programEnd");
		error_log("::SA-954:: Time Elapsed : ".$this->benchmark->elapsed_time("programStart","programEnd"));
    }
	
	public function saveFiltersToCacheForAllCategoryPages()
	{
		$this->validateCron(); // prevent browser access
		ini_set('memory_limit', '500M');
		ini_set('max_execution_time',600);
		$this->benchmark->mark('set_filters_in cache_start');
		error_log("SRB1 memory usage at set_filters_in cache_start :: ".(memory_get_usage(TRUE)/1024));
		$this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
		$abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
		$abroadCPCache = $this->load->library('categoryList/cache/AbroadCategoryPageCache');
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$this->locationRepository = $locationBuilder->getLocationRepository();
		// get all category,subcategories
		$categories = $abroadCommonLib->getAbroadCategories();
		// get all popular courses
		$LDBCourses = $abroadCommonLib->getAbroadMainLDBCourses();
		$LDBCourses = array_map(function($a){return $a['SpecializationId'];}, $LDBCourses);
		// get all course levels for abroad
		$courseLevels = $abroadCommonLib->getAbroadCourseLevelsForFindCollegeWidgets();
		//_p($courseLevels);
		// get all abroad countries
		$countries = $this->locationRepository->getAbroadCountries();
		// remove All country option
		//unset($countries['All']);
		$countries = array_map(function($a){return $a->getId();}, $countries);

		$requestDataSet 	= array();
		
		// create relevant id sets for desired course pages
		foreach($LDBCourses as $ldbCourse)
		{
			foreach($countries as $country)
			{
				$singleSet = array('countryId'=>array($country),'LDBCourseId'=>$ldbCourse,'courseLevel'=>'','categoryId'=>1,'subCategoryId'=>1,'buildCategoryPageViaSolr'=>false);
				array_push($requestDataSet,$singleSet);
			}
		}
		// now create relevant id sets for category courselevel pages
		foreach(array_keys($categories) as $category)
		{
			foreach($courseLevels as $courseLevel)
			{
				foreach($countries as $country)
				{
					$singleSet = array('countryId'=>array($country),'LDBCourseId'=>1,'courseLevel'=>$courseLevel,'categoryId'=>$category,'subCategoryId'=>1,'buildCategoryPageViaSolr'=>false);
					array_push($requestDataSet,$singleSet);
				}
			}
		}
		_p(count($requestDataSet));
		// load cat page obj
		$this->load->builder('AbroadCategoryPageBuilder','categoryList');
        foreach($requestDataSet as $keySet)
		{   
			$categoryPageBuilder = new AbroadCategoryPageBuilder($keySet);
			$categoryPage = $categoryPageBuilder->getCategoryPage();
			$request = $categoryPage->getRequest();
			// get filter for each key generated
			echo "<br>".$request->getPageKey();
			$temp = $categoryPage->getUniversities($filterGenerationInCacheActive = true);
			unset($temp); // univs not required
			// sort exams
			$filtersApplicable = $categoryPage->getFilters(); // all filters
			$requestData = array('LDBCourseId'=>$categoryPage->getRequest()->getLDBCourseId(),
								 'categoryId'=>$categoryPage->getRequest()->getCategoryId(),
								 'courseLevel'=>$categoryPage->getRequest()->getCourseLevel());
			// get exam order
			$examOrder = $this->abroadCategoryPageLib->getExamOrderByCategory($requestData);
			//var_dump($examOrder);
			$fees = $filtersApplicable['fees']->getFilteredValues();
			$exams = $filtersApplicable['exams']->getFilteredValues();
			$examsScore = $filtersApplicable['examsScore']->getFilteredValues();
			$examsScoreSorted = array();
			foreach($examsScore as $key=>$val){
				rsort($val,true);
				$examsScoreSorted[$key] = $val;
			}

			$orderedExams = array();
			foreach($exams as $k=>$v)
			{
				$orderedExams[$k] = array('id'=>$k,'exam'=>$v);
			}
			usort($orderedExams, function ($a,$b) use($examOrder){
				return ($examOrder[$a['exam']] > $examOrder[$b['exam']]?1:-1);
			});
			$filterValue = json_encode(array(
								'exams' => $orderedExams,
								'examsScore' 	=> $examsScoreSorted ,
								'fees'	=> $fees,
								)
						);
			//error_log(gzcompress(serialize($filterValue)), 3, '/home/saurabh/Desktop/FilterCache.txt');
			// no need to store empty filters
			if($filterValue != '{"exams":[],"fees":[]}'){
				$abroadCPCache->storeFilters($categoryPage->getRequest()->getPageKey(),$filterValue);
			}
			unset($categoryPageBuilder);
		}
		$this->benchmark->mark('set_filters_in cache_end');
		error_log("SRB1 memory usage at set_filters_in cache_end :: ".(memory_get_usage(TRUE)/(1024*1024)));
		error_log( "SRB1 Total time taken for filters= ".$this->benchmark->elapsed_time('set_filters_in cache_start', 'set_filters_in cache_end'));
		
	}
}
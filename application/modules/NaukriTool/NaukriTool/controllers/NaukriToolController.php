<?php
class NaukriToolController extends MX_Controller {
	public function __construct(){
		$this->load->model('naukritool');
		$this->naukriTool = new NaukriTool;
		$this->load->config('NaukriTool/NaukriToolConfig',TRUE);
		$this->load->helper('NaukriTool/naukritool');

        $helper=array('url','image','shikshautility','utility_helper');
        if(is_array($helper)){
            $this->load->helper($helper);
        }
		$this->load->helper('coursepages/course_page');

	    if(is_array($library)){
	            $this->load->library($library);
	    }
	    if(($this->userStatus == "")){
	            $this->userStatus = $this->checkUserValidation();
	    }
    }

	public function showNaukriTool($functionType=''){
		$this->naukritoolredirectionrules = $this->load->library('NaukriToolRedirectionRules'); 
		$this->naukritoolredirectionrules->redirectionRule($functionType);
		
		if($functionType!=''){
			$result 	= setTopSixDoghnutChartDataForSeo($functionType);
			$url    	= $result['url'];
			$title 		= $result['title'];
			$seoData	= $result['seo_data'];
		}else{
            $url 		= SHIKSHA_HOME.'/mba/resources/mba-alumni-data';
			$title 		= 'MBA Career Compass | Shiksha.com';
			$seoData	= '';
        }
		$pageData          = $this->naukriTool->defaultPageData();
		$configData        = $this->config->item('jobFuncMapping','NaukriToolConfig');
		$data 		   = checkForOtherData($pageData, $jobFunc, $companies, $cities, $chartType, $configData);
		$formattedPageData = getJobOperationShortName($data, $configData);
		$data['graphData'] = json_encode($formattedPageData);
		$data['jobFuncMapping'] = $configData;

		$data['validateuser'] = $this->userStatus;
		$this->load->model('coursepages/coursepagecmsmodel');
		$this->ntmodel = new coursepagecmsmodel();
        $data['institutedetailsdata'] = $this->ntmodel->getSectionsDetails('', 1);  // 1 for MBA in courseHomepages

		$data['title'] 		= $title;
		$data['canonicalURL'] 	= $url;
		$data['isNaukriPage'] 	= true;
		$data['seo_data']	= $seoData;

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_CareerCompass');
        $data['dfpData']  = $dfpObj->getDFPData($data['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		//below code used for beacon tracking
		$data['beaconTrackData'] = $this->naukritoolredirectionrules->prepareBeaconTrackData();
		//prepare breadcrumb html
        /*$breadcrumbOptions = array('generatorType' 	=> 'CareerCompassHomePage',
									'options' 		=> array('subCategoryId'	=>	$subcatId));
		$BreadCrumbGenerator = $this->load->library('common/breadcrumb/BreadcrumbGenerator', $breadcrumbOptions);
		$data['breadcrumbHtml'] = $BreadCrumbGenerator->prepareBreadcrumbHtml();*/
		$this->load->view('NaukriTool',$data);
	}

	public function recreateGraph(){	
		$jobFunc           = isset($_POST['jobFunc'])?$this->input->post('jobFunc'):'';
		$companies         = isset($_POST['companies'])?$this->input->post('companies'):'';
		$cities            = isset($_POST['cities'])?$this->input->post('cities'):'';
		$chartType         = isset($_POST['chartType'])?$this->input->post('chartType'):'';

		$configData        = $this->config->item('reverseJobFunctionMapping','NaukriToolConfig');
		$jobFunction       = $configData[$jobFunc];

		$pageData          = $this->naukriTool->getDataForChart($jobFunction, $companies, $cities);
		$configData  	   = $this->config->item('jobFuncMapping','NaukriToolConfig');

		$data              = checkForOtherData($pageData, $jobFunc, $companies, $cities, $chartType, $configData);	
		$formattedPageData = getJobOperationShortName($data, $configData);
		$data['graphData'] = json_encode($formattedPageData);

		echo json_encode($formattedPageData);
	}
	
	function recreateInstituteDetailGraph(){
		$jobFunc           = isset($_POST['jobFunc'])?$this->input->post('jobFunc'):'';
		$companies         = isset($_POST['companies'])?$this->input->post('companies'):'';
		$cities            = "";
		$chartType         = isset($_POST['chartType'])?$this->input->post('chartType'):'';
		$instituteId	   = isset($_POST['instituteId'])?$this->input->post('instituteId'):'';

		$configData        = $this->config->item('reverseJobFunctionMapping','NaukriToolConfig');
		$jobFunction       = $configData[$jobFunc];

		if($chartType == 'companies'){
			$pageData['jobFuncData']   = $this->naukriTool->getJobFunctionsData(array($instituteId), $companies, $cities);
		}
		if($chartType=='JobFunction'){
		    $pageData['companiesData'] = $this->naukriTool->getCompaniesData(array($instituteId), $jobFunction, $cities);
		}
		
		//$pageData          = $this->naukriTool->getDataForChart($jobFunction, $companies, $cities);
		$configData  	   = $this->config->item('jobFuncMapping','NaukriToolConfig');

		//$data              = checkForOtherData($pageData);
		$data              = checkForOtherData($pageData, $jobFunc, $companies, $cities, $chartType, $configData);	
		$formattedPageData = getJobOperationShortName($data, $configData);
		$data['graphData'] = json_encode($formattedPageData);

		echo json_encode($formattedPageData);
	}
	
	// @akhter
	// show institute list on basis of selection on the google graph
	function getCollegeList(){
	    $this->load->model('naukritool');
	    $pageNo 	= isset($_POST['pageNo'])?$this->input->post('pageNo'):0;
	    $callType 	= isset($_POST['callType'])?$this->input->post('callType'):'';
            
	    if($callType == 'Ajax'){
			$mappingInfo = $this->config->item('reverseJobFunctionMapping','NaukriToolConfig');
			$jobFunction = ($_COOKIE['job_func'] !='')?$mappingInfo[$_COOKIE['job_func']]:'';
			$company     = ($_COOKIE['company'] !='')?$_COOKIE['company']: '';
			$cityId      = ($_COOKIE['city_name'] !='')?$this->naukriTool->getCityIdByName($_COOKIE['city_name']):'';
	    }else{
            $seo_data = (isset($_COOKIE['seo_data']) && $_COOKIE['seo_data']!='')?$_COOKIE['seo_data']:'';
            $filterValueType = '';
            if ($seo_data!='') {
                $splitData     = explode('_',$seo_data);
                $filterValueType      = $splitData[0];
				$filterValue = $splitData[1];
            }
            if($filterValueType=='JobFunc') {
				$mappingInfo = $this->config->item('reverseJobFunctionMapping','NaukriToolConfig');
                $jobFunction = $mappingInfo[$filterValue];
            }else if ($filterValueType=='Companies') {
                $company = $filterValue;
            }else{
                $this->deleteCookie(array('job_func','company','city_name'));
            }
	    }
	    $pageSize 	= 12;
	    $pageStart	= ($pageNo*$pageSize);
	    $param = array('jobFunction'=>$jobFunction,'company'=>$company,'cityId'=>$cityId,'pageStart'=>$pageStart,'pageSize'=>$pageSize);
	    $result = $this->naukritool->getInstitueRsult($param);
	    $instituteIds = $result['institute'];
	    $data['total'] = $result['total'];
        $data['pageSize'] = $pageSize;
	    if(is_array($instituteIds) && count($data['total'])>0){
			$this->load->builder("nationalInstitute/InstituteBuilder");
			$instituteBuilder = new InstituteBuilder();
			$instituteRepo = $instituteBuilder->getInstituteRepository();
			$data['instituteObjs'] = $instituteRepo->findMultiple($instituteIds);
	    }
	    if($callType == 'Ajax'){
			echo $this->load->view('instituteList',$data);
	    }else{
			$this->load->view('instituteList',$data);
	    }
	}
    
	function showInstituteDetails(){
		$data = array();
		$instituteId = $this->input->post("instId");
		if($instituteId>0){
			$this->load->model('naukritool');
			$institutemodel = $this->load->model("nationalInstitute/institutemodel");
			$naukriTool     = new NaukriTool;
			$naukriAverageSalary = 0;
			$mbaBaseCourseId	  = MANAGEMENT_COURSE;
			$fullTimeEducationTypeId = EDUCATION_TYPE;
			$mbaCourseIds = array();
			// load the course object from Course Repo
			$this->load->builder("nationalCourse/CourseBuilder");
			$courseBuilder = new CourseBuilder();
			$courseRepository = $courseBuilder->getCourseRepository();
			$this->load->library("nationalInstitute/InstituteDetailLib");
			$lib = new InstituteDetailLib();
			$result = $lib->getInstituteCourseIds($instituteId, 'institute');
			$courseList = $result['courseIds'];
		    if(count($courseList) > 0){
		        $allCourses = $courseRepository->findMultiple($courseList,array('basic', 'eligibility'));
		    }
			foreach ($allCourses as $courseObj) {
				$courseTypeInfo = $courseObj->getCourseTypeInformation();
				foreach ($courseTypeInfo as $courseTypeObj) {
					if($courseTypeObj){
						$baseCourseId = $courseTypeObj->getBaseCourse();
						if($baseCourseId == $mbaBaseCourseId){
							$educationType = $courseObj->getEducationType();
							$educationTypeId = $educationType->getId();
							if($educationTypeId == $fullTimeEducationTypeId){
								$hierarchy = $courseTypeObj->getHierarchies();
								foreach ($hierarchy as $hierarchyRow) {
									if($hierarchyRow['specialization_id'] == 0){
										$finalCourseIds = $courseObj->getId();
									}
								}
								$mbaFullTimeCourseIds[] = $courseObj->getId();
							}
						}
					}
				}
			}
		 	if((is_array($finalCourseIds) && count($finalCourseIds)>0) || ($finalCourseIds != '')){
		 		if(is_array($finalCourseIds)){
		 			$finalCourseIds = $finalCourseIds[0];
		 		}
		 	}else{
		 		if(!empty($mbaFullTimeCourseIds)){
					$finalCourseIds	= $lib->getCourseViewCount($mbaFullTimeCourseIds);
					$finalCourseIds = array_keys($finalCourseIds);
					$finalCourseIds = $finalCourseIds[0];
				}
		 	}
			
		    $salaryDataResults = $institutemodel->getNaukriSalaryData($instituteId);
		    foreach($salaryDataResults as $naukriDataRow){
		        if($naukriDataRow['exp_bucket'] == '2-5')
		            $naukriAverageSalary = $naukriDataRow;
		    }
		    $courseObj = $allCourses[$finalCourseIds];
		    if (!is_object($courseObj)) {
		        return false;
		    }
			$this->load->builder("nationalInstitute/InstituteBuilder");
		    $instituteBuilder = new InstituteBuilder();
		    $instituteRepository = $instituteBuilder->getInstituteRepository();
		    $this->rankingLib = $this->load->library('rankingV2/RankingCommonLibv2');
		    $courseRank = $this->rankingLib->getCourseRankBySource(array($finalCourseIds));
		    $courseObj->course_ranking = $courseRank[$finalCourseIds];
		   
		    $total_alumni = 0;
		    foreach($salaryDataResults as $salaryData) {
		            $total_alumni = $total_alumni + $salaryData['tot_emp'];
		    }

		    $data['total_alumni'] = $total_alumni;
		    $naukriFunctionalSalaryData = $this->naukriTool->getNaukriFunctionalSalaryData($instituteId);
		    $jobFuncMapping        = $this->config->item('jobFuncMapping','NaukriToolConfig');
		    $i = 0;    
		    foreach($naukriFunctionalSalaryData as $dataRow){
				$chart[$jobFuncMapping[$dataRow['functional_area']]]['Exp_Bucket'] = $dataRow['exp_bucket'];
				$chart[$jobFuncMapping[$dataRow['functional_area']]]['AvgCTC'] = $dataRow['ctc50'];
		    	if(array_key_exists($dataRow['functional_area'], $jobFuncMapping))
					$chart[$jobFuncMapping[$dataRow['functional_area']]]['functional_area'] = $jobFuncMapping[$dataRow['functional_area']];
		        else
					$chart[$jobFuncMapping[$dataRow['functional_area']]]['functional_area'] = $dataRow['functional_area'];
		    	$i++;
		    }
		    $data['chart'] = json_encode($chart);


		    $jobFunctionAlumniData['jobFuncData']   = $this->naukriTool->getJobFunctionsData(array($instituteId));
		    $jobFunctionAlumniData['companiesData'] = $this->naukriTool->getCompaniesData(array($instituteId));
		    $configData         = $this->config->item('jobFuncMapping','NaukriToolConfig');

		    $jobFunctionAlumniData = checkForOtherData($jobFunctionAlumniData);
		    $jobFunctionAlumniData = getJobOperationShortName($jobFunctionAlumniData, $configData);
		    $jobFunctionAlumniData = json_encode($jobFunctionAlumniData);

		    $data['instituteNaukriSalaryData'] = $naukriAverageSalary;

		    // myShortlist (remaining)
			    $categoryPage_SubCat = 23;
			    $data['categoryPage_SubCat'] = $categoryPage_SubCat;
			    $data['userInfo'] = $this->userStatus;
			    if($categoryPage_SubCat == 23){
			        if(isset($data['userInfo'][0]['userid'])) {
			            $data['shortlistedCoursesOfUser'] = Modules::run('myShortlist/MyShortlist/fetchShortlistedCoursesOfAUser',$data['userInfo'][0]['userid']);
			        }
			    }
			//_p($jobFunctionAlumniData);die;
			$data['courseObj'] = $courseObj;
			//_p($data['chart']);die;
		    $html = $this->load->view("naukriToolInstituteDetails",$data,true);
		    //_p($jobFunctionAlumniData);die;
		    echo json_encode(array(
		        "html" => $html,
		        "chart" => $data['chart'],
				"jobFunctionAlumniData"=>$jobFunctionAlumniData));
	    }
	}

	public function getJobFunctionsListForOther(){
	    $instId     = isset($_POST['instId'])?$this->input->post('instId'):'';
	    $cities     = isset($_POST['cities'])?$this->input->post('cities'):'';
	    $companies  = isset($_POST['companies'])?$this->input->post('companies'):'';
	    $viewAll    = (isset($_POST['viewAll']) && $_POST['viewAll']=='yes')?'yes':'no';
	    $configData = $this->config->item('jobFuncMapping','NaukriToolConfig');
	    
	    function job_function($elem)
	    {
		return $elem['functional_area'];
	    }
	    $topJobFuncData = array();
	    $brk = 0;
	    if(empty($instId))
	    {
	    $instituteIds  = $this->naukriTool->getInstituteIds();
	    $data = $this->naukriTool->getJobFunctionsData($instituteIds, $companies, $cities);
	    $topJobFuncData  = checkForOtherData(array('jobFuncData'=>$data), $jobFunc, $companies, $cities, 'JobFunction', $configData);
	    array_pop($topJobFuncData['jobFuncData']);
	    $temp=array();
	    foreach($topJobFuncData['jobFuncData'] as $val)
	    {
		$brk++;
		$temp[] = $val;
		if($brk >= 6)
		    break;
	    }
		    $topJobFuncData = $temp;
			$topJobFuncData = array_map("job_function", $topJobFuncData);
	    }
	    else
	    {
		$instituteIds = array($instId);
		$jobFunctionAlumniData   = $this->naukriTool->getJobFunctionsData(array($instId));
		foreach($jobFunctionAlumniData as $key=>$value)
		{
		    $brk++;
		    $topJobFuncData[] = $value['functional_area'];
		    if($brk==6)
			break;
		}
	    }

	    $jobFuncData = $this->naukriTool->getJobFunctionsListForOverlay($instituteIds, $companies, $cities);
	    $myJobFuncData = array();
	    foreach($jobFuncData as $key=>$value)
	    {
		if(key_exists($value['functional_area'], $configData))
		{
		    $myJobFuncData[] = $value;
		}
	    }
	    $output = '';
	    foreach($myJobFuncData as $key=>$value)
	    {
		if($viewAll=='yes' || !in_array($value['functional_area'], $topJobFuncData))
		{
		    $output .= '<li name="'.$configData[$value['functional_area']].'" id="func--'.$key.'"><a onclick="jobFuncSelection = \''.$configData[$value['functional_area']].'\'; $j(\'#JobFuncContainer\').hide(); $j(\'#pageOverlayBg\').hide();" href="javascript:void(0);">'.$configData[$value['functional_area']].'</a></li>';
		}
	    }
	    if(count($myJobFuncData) < 1)
	    {
		$output = 'No data available.';
	    }
	    echo $output;
	}
	
	public function getCompaniesFunctionsListForOther($alphabet = 'A'){
	    $jobFunc           = isset($_POST['jobFunc'])?$this->input->post('jobFunc'):'';
	    $configData        = $this->config->item('jobFuncMapping','NaukriToolConfig');
	    $revConfigData     = $this->config->item('reverseJobFunctionMapping','NaukriToolConfig');
	    $jobFunc           = $revConfigData[$jobFunc];
	    $cities            = isset($_POST['cities'])?$this->input->post('cities'):'';
	    $viewAll           = (isset($_POST['viewAll']) && $_POST['viewAll']=='yes')?'yes':'no';
	    $instId 	       = $this->input->post("instId");
	    if(empty($instId))
			$instituteIds  = $this->naukriTool->getInstituteIds();
	    else
			$instituteIds  = array($instId);

	    $data              = $this->naukriTool->getCompaniesData($instituteIds, $jobFunc, $cities);
	    $topCompanyData    = checkForOtherData(array('companiesData'=>$data), $jobFunc, $companies, $cities, 'companies', $configData);
	    
	    array_pop($topCompanyData['companiesData']);
	    $temp=array();
	    $brk = 0;
	    foreach($topCompanyData['companiesData'] as $val)
	    {
		$brk++;
		$temp[] = $val;
		if($brk >= 6)
		    break;
	    }
	    $topCompanyData = $temp;
	    
	    function comp_label($elem)
	    {
		return $elem['comp_label'];
	    }
	    $topCompanyName = array_map("comp_label", $topCompanyData);
	    $companiesFuncData = $this->naukriTool->getCompaniesFunctionsListForOverlay(trim($alphabet), $jobFunc, $cities, $instituteIds);
	    $output = '<ul style="width:450px">';
	    $i =1;
	    $block = 0;
	    foreach($companiesFuncData as $key=>$value)
	    {
		if($viewAll=='yes' || !in_array($value['comp_label'], $topCompanyName))
		{
		    $output .= '<li name=\''.$value['comp_label'].'\'><a href="javascript:void(0);" onclick="companiesSelection = \''.$value['comp_label'].'\'; $j(\'#CompaniesFuncContainer\').hide(); $j(\'#pageOverlayBg\').hide();">'.$value['comp_label'].' ('.$value['totalEmployee'].')</a></li>';
		    if(($block+1)%15 == 0)
		    {
			$output .= '</ul><ul style="width:450px">';
			$i++;
		    }
		    $block++;
		}
	    }
	    $output .= '</ul>';
	    if(count($companiesFuncData) < 1)
	    {
		$output = 'No data available.';
	    }
	    $containerWidth = $i * 450;
	    echo json_encode(array('outputData'=>$output, 'outputWidth'=>$containerWidth));
	}
	
	public function getLocationFunctionsListForOther(){
	    $jobFunc           = isset($_POST['jobFunc'])?$this->input->post('jobFunc'):'';
	    $companies         = isset($_POST['companies'])?$this->input->post('companies'):'';
	    $viewAll           = (isset($_POST['viewAll']) && $_POST['viewAll']=='yes')?'yes':'no';
	    $revConfigData        = $this->config->item('reverseJobFunctionMapping','NaukriToolConfig');
	    $instituteIds  = $this->naukriTool->getInstituteIds();
	    $data = $this->naukriTool->getCitiesData($instituteIds, $revConfigData[$jobFunc], $companies);
	    $configData        = $this->config->item('jobFuncMapping','NaukriToolConfig');
	    $topCityData      	   = checkForOtherData(array('citiesData'=>$data), $jobFunc, $companies, $cities, 'cities', $configData);
	    array_pop($topCityData['citiesData']);
	    $temp=array();
	    $brk = 0;
	    foreach($topCityData['citiesData'] as $val)
	    {
		$brk++;
		$temp[] = $val;
		if($brk >= 6)
		    break;
	    }
	    $topCityData = $temp;
	    
	    function city_name($elem)
	    {
		return $elem['city_name'];
	    }
	    $topCityName = array_map("city_name", $topCityData);
	    $locationFuncData = $this->naukriTool->getLocationFunctionsListForOverlay($revConfigData[$jobFunc], $companies);
	    $output = '';
	    foreach($locationFuncData as $key=>$value)
	    {
		if($viewAll=='yes' || !in_array($value['city_name'], $topCityName))
		{
		    $output .= '<li id="loc--'.$key.'" name="'.$value['city_name'].'"><a href="javascript:void(0);" onclick="citiesSelection = \''.$value['city_name'].'\'; $j(\'#LocationFuncContainer\').hide(); $j(\'#pageOverlayBg\').hide();">'.$value['city_name'].'</a></li>';
		}
	    }
	    if(count($locationFuncData) < 1)
	    {
		$output = 'No data available.';
	    }
	    echo $output;
	}

	function deleteCookie($nameArray){
	    if(is_array($nameArray)){foreach($nameArray as $name){
		setcookie($name, "", time() - 3600,"/",COOKIEDOMAIN);
	    }}
	}
	
	// not used now
	function getNaurkiEBrochure($institute_id,$instituteRepository){
	    if($institute_id){
	    	$this->load->library('nationalCourse/CourseDetailLib'); 
	 		$courseDetailLib = new CourseDetailLib; 
	 		$locationWiseCourseIds = $courseDetailLib->getCourseForInstituteLocationWise($institute_id);
		    //$this->courses = $instituteRepository->getLocationwiseCourseListForInstitute($institute_id);
	    }
	    $courseList = array();
	    foreach($locationWiseCourseIds as $locationId => $courseIds){	
		$courseList = array_merge($courseList,$courseIds);
	    }
	    $courseList = array_unique($courseList);
	    
	    if($courseList){
			$institute = reset($instituteRepository->findWithCourses(array($institute_id => $courseList)));
	    }else{
			$institute = $instituteRepository->find($institute_id);
	    }
	    
	    $displayData['paid_courses'] = array();
	    // seperate paid and free courses of the institute
	    if($courses = $institute->getCourses()){
		    foreach($courses as $course){
			    if($course->isPaid()){
				    $displayData['paid_courses'][] = $course;
			    } else {
				$displayData['free_courses'][] = $course;
			    }
		    }
	    }
	    
	    $paidCourses = $displayData['paid_courses'];
	    $freeCourses = $displayData['free_courses'];
    
	    $coursesListData = array();
	    // get all course-ids
	    foreach($paidCourses as $c){
			$course_ids_array[] = $c->getId();
	    }
	    foreach($freeCourses as $c){
			$course_ids_array[] = $c->getId();
	    }
	    //echo 'sdfgh';
		 //_p($coursesListData);   
	    // get the brochure links of the courses
	    if(!empty($course_ids_array)) {
			//$courses_with_brochure = $national_course_lib->getMultipleCoursesBrochure($course_ids_array);
			$this->load->library('listingCommon/ListingCommonLib'); 
	 		$listingCommonLib = new ListingCommonLib;
	 		//_p($course_ids_array);

	 		$courses_with_brochure = $listingCommonLib->getCustomizedBrochureData('course',$course_ids_array);
			//$courses_with_brochure = $national_course_lib->getMultipleCoursesBrochure($course_ids_array);
	    }
	    //_p($courses_with_brochure);die;
	    
	    // get all paid courses
	    foreach($paidCourses as $c){
		    $coursesListData[$c->getId()] = $c->getName();
	    }
			    
	    // get only those free courses that have brochure 
	    foreach($freeCourses as $c){
		    // for free courses check if that course have brochure or not
		    if(array_key_exists($c->getId(), $courses_with_brochure))
		    {
				$coursesListData[$c->getId()] 	= $c->getName();
		    }
	    }
	    //_p($coursesListData);die;
	    return $coursesListData;
	}
	
}
?>
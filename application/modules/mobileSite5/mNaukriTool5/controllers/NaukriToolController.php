<?php
class NaukriToolController extends ShikshaMobileWebSite_Controller
{
    //constructor
    public function __construct(){
        $this->load->model('NaukriTool/naukritool');
        $this->naukriTool = new NaukriTool;
        $this->load->config('NaukriTool/NaukriToolConfig',TRUE);
        $this->load->helper('NaukriTool/naukritool');
        
        $helper=array('url','image','shikshautility','utility_helper');
        if(is_array($helper)){
                $this->load->helper($helper);
        }
        $this->load->helper('coursepages/course_page');
        $this->load->helper('mcommon5/mobile_html5');
        if(is_array($library)){
                $this->load->library($library);
        }
        if(($this->userStatus == "")){
                $this->userStatus = $this->checkUserValidation();
        }
    }
    
    //Default function to show naukri tool data
    public function showNaukriTool($functionType=''){
        $this->naukritoolredirectionrules = $this->load->library('NaukriTool/NaukriToolRedirectionRules'); 
        $this->naukritoolredirectionrules->redirectionRule($functionType);
        if($functionType!=''){
            $result 	= setTopSixDoghnutChartDataForSeo($functionType);
            $url    	= $result['url'];
            $title 	= $result['title'];
            $seoData	= $result['seo_data'];
            $oldUrl = 'best-colleges-for-'.$functionType.'-jobs-based-on-mba-alumni-data';
            $newUrl = SHIKSHA_HOME.'/mba/resources/best-mba-'.$functionType.'-colleges-based-on-mba-alumni-data';
        }else{
            $url 	= SHIKSHA_HOME.'/mba/resources/mba-alumni-data';
            $title 	= 'MBA Career Compass | Shiksha.com';
            $seoData	= '';
		    $oldUrl = 'best-colleges-for-jobs-based-on-mba-alumni-data';
		    $newUrl = SHIKSHA_HOME.'/mba/resources/mba-alumni-data';
        }

        //$enteredURL = $_SERVER['SCRIPT_URI'];
        //if($url!='' && $url!=$enteredURL){
        //     if($_SERVER['QUERY_STRING']!='' && $_SERVER['QUERY_STRING']!=NULL){
        //           $url = $url."?".$_SERVER['QUERY_STRING'];
        //               header("Location: $url",TRUE,301);
        //     }
        //     else{
        //               header("Location: $url",TRUE,301);
        //     }
        //         exit;
        //}

        $pageData          = $this->naukriTool->defaultPageData();
        $configData        = $this->config->item('jobFuncMapping','NaukriToolConfig');
        $data 		   = checkForOtherData($pageData, $jobFunc, $companies, $cities, $chartType, $configData);	
        $formattedPageData = getJobOperationShortName($data, $configData);
        $data['graphData'] = json_encode($formattedPageData);
        $data['jobFuncMapping'] = $configData;

        $data['validateuser'] = $this->userStatus;
        $data['pageName']     = 'NaukriTool';
        $data['m_meta_title'] = 'MBA Career Compass | Shiksha.com';
        $data['m_meta_description'] = 'Find out the best MBA colleges to achieve your career goals. See colleges based on alumni data, only on Shiksha.com';
        $data['canonicalURL'] = SHIKSHA_HOME.'/mba/resources/mba-alumni-data';
        $data['m_meta_keywords'] = ' ';
        $data['userStatus']   = $this->userStatus;
			
		
        $data['boomr_pageid'] = 'CAREER_COMPASS';
        $data['m_meta_title'] 		= $title;
	    $data['m_canonical_url'] 	= $url;
        $data['seo_data']	= $seoData;

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_CareerCompass');
        $data['dfpData']  = $dfpObj->getDFPData($data['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		//below cod eused for beacon tracking purpose 
        $data['beaconTrackData'] = $this->naukritoolredirectionrules->prepareBeaconTrackData();
        //below line is used for conversion tracking purpose
        $data['trackingPageKeyId']=313;
        $data['shortlistCollegeTupleTrackingPageKeyId']=311;
        $data['shortlistTrackingPageKeyId']=311;
        $data['shortlistbottomTrackingPageKeyId'] =314;
        $this->load->view('mNaukriTool5/naukriTool',$data);
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

		if($chartType=='companies'){
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
    
    public function getJobFunctionsListForOther(){
        $instId     = isset($_POST['instId'])?$this->input->post('instId'):'';
        $cities     = isset($_POST['cities'])?$this->input->post('cities'):'';
        $companies  = isset($_POST['companies'])?$this->input->post('companies'):'';
        $configData = $this->config->item('jobFuncMapping','NaukriToolConfig');

        function job_function($elem){
            return $elem['functional_area'];
        }
        $topJobFuncData = array();
        $brk = 0;
        if(empty($instId)){
            $instituteIds  = $this->naukriTool->getInstituteIds();
        $data = $this->naukriTool->getJobFunctionsData($instituteIds, $companies, $cities);
        $topJobFuncData  = checkForOtherData(array('jobFuncData'=>$data), $jobFunc, $companies, $cities, 'JobFunction', $configData);
        array_pop($topJobFuncData['jobFuncData']);
        $temp=array();
        foreach($topJobFuncData['jobFuncData'] as $val){
                $brk++;
                $temp[] = $val;
                if($brk >= 6)
                    break;
            }
            $topJobFuncData = $temp;
            $topJobFuncData = array_map("job_function", $topJobFuncData);
        }else{
            $instituteIds = array($instId);
            $jobFunctionAlumniData   = $this->naukriTool->getJobFunctionsData(array($instId));
            foreach($jobFunctionAlumniData as $key=>$value){
                $brk++;
                $topJobFuncData[] = $value['functional_area'];
                if($brk==6)
                    break;
            }
        }

        $jobFuncData = $this->naukriTool->getJobFunctionsListForOverlay($instituteIds, $companies, $cities);
        $myJobFuncData = array();
        foreach($jobFuncData as $key=>$value){
            if(key_exists($value['functional_area'], $configData))
            {
                $myJobFuncData[] = $value;
            }
        }
        $output = '';
        foreach($myJobFuncData as $key=>$value){
            if(!in_array($value['functional_area'], $topJobFuncData)){
				$relBack = false;
                if(!empty($instId)){
                    //$hrefStr = '#naukri-widget-right-col';
                    $hrefStr='javascript:void(0)';
                    $relBack = true;
                }else
                    $hrefStr='javascript:void(0)';
                $output .= '<li name="'.$configData[$value['functional_area']].'" id="func--'.$key.'"><a '.(($relBack)?'data-rel="back"':'').' href="'.$hrefStr.'">'.$configData[$value['functional_area']].'</a></li>';
            }
        }
        if(count($myJobFuncData) < 1){
            $output = 'No data available.';
        }
        echo $output;
    }
    
    public function getCompaniesFunctionsListForOther($alphabet = 'A'){
        $jobFunc            = isset($_POST['jobFunc'])?$this->input->post('jobFunc'):'';
        $configData         = $this->config->item('jobFuncMapping','NaukriToolConfig');
        $revConfigData      = $this->config->item('reverseJobFunctionMapping','NaukriToolConfig');
        $jobFunc            = $revConfigData[$jobFunc];
        $cities             = isset($_POST['cities'])?$this->input->post('cities'):'';
        $instId 	        = $this->input->post("instId");
        if(empty($instId))
            $instituteIds   = $this->naukriTool->getInstituteIds();
        else
            $instituteIds   = array($instId);

        $data               = $this->naukriTool->getCompaniesData($instituteIds, $jobFunc, $cities);
        $topCompanyData     = checkForOtherData(array('companiesData'=>$data), $jobFunc, $companies, $cities, 'companies', $configData);
        
        array_pop($topCompanyData['companiesData']);
        $temp=array();
        $brk = 0;
        foreach($topCompanyData['companiesData'] as $val){
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
        $output = '<ul>';
        $i =1;
        //$block = 0;
        foreach($companiesFuncData as $key=>$value){
            if(!in_array($value['comp_label'], $topCompanyName)){
		        $relBack = false;
                if(!empty($instId))
		        {
                    //$hrefStr = '#naukri-widget-right-col';
		            $hrefStr = 'javascript:void(0)';
                    $relBack = true;
                }else
                    $hrefStr='javascript:void(0)';
                $output .= '<li id="comp--'.$key.'" name=\''.$value['comp_label'].'\'><a '.(($relBack)?'data-rel="back"':'').' href="'.$hrefStr.'">'.$value['comp_label'].' ('.$value['totalEmployee'].')</a></li>';
                /*if(($block+1)%15 == 0)
                {
                    $output .= '</ul><ul style="width:450px">';
                    $i++;
                }
                $block++;*/
            }
        }
        $output .= '</ul>';
        if(count($companiesFuncData) < 1){
            $output = 'No data available.';
        }
        echo $output;
    }
    
    public function getLocationFunctionsListForOther(){
        $jobFunc       = isset($_POST['jobFunc'])?$this->input->post('jobFunc'):'';
        $companies     = isset($_POST['companies'])?$this->input->post('companies'):'';
        $revConfigData = $this->config->item('reverseJobFunctionMapping','NaukriToolConfig');
        $instituteIds  = $this->naukriTool->getInstituteIds();
        $data = $this->naukriTool->getCitiesData($instituteIds, $revConfigData[$jobFunc], $companies);
        $configData        = $this->config->item('jobFuncMapping','NaukriToolConfig');
        $topCityData      	   = checkForOtherData(array('citiesData'=>$data), $jobFunc, $companies, $cities, 'cities', $configData);
        array_pop($topCityData['citiesData']);
        $temp=array();
        $brk = 0;
        foreach($topCityData['citiesData'] as $val){
            $brk++;
            $temp[] = $val;
            if($brk >= 6)
                break;
        }
        $topCityData = $temp;
        
        function city_name($elem){
            return $elem['city_name'];
        }
        $topCityName = array_map("city_name", $topCityData);
        $locationFuncData = $this->naukriTool->getLocationFunctionsListForOverlay($revConfigData[$jobFunc], $companies);
        $output = '';
        foreach($locationFuncData as $key=>$value)
        {
            if(!in_array($value['city_name'], $topCityName))
            {
                $output .= '<li id="loc--'.$key.'" name="'.$value['city_name'].'"><a href="javascript:void(0);">'.$value['city_name'].'</a></li>';
            }
        }
        if(count($locationFuncData) < 1)
        {
            $output = 'No data available.';
        }
        echo $output;
    }

    function getCollegeList(){
        $data = array();
        if($this->input->is_ajax_request()){
            $pageNo 	 = (isset($_POST['pageNo']) && $_POST['pageNo']!='')?$this->input->post('pageNo'):0;
            $mappingInfo = $this->config->item('reverseJobFunctionMapping','NaukriToolConfig');
            $jobFunction = (isset($_POST['job_func']) && $_POST['job_func']!='')?$mappingInfo[$this->input->post('job_func')]:'';
            $company     = (isset($_POST['company']) && $_POST['company']!='')?$this->input->post('company'): '';
            $cityId      = (isset($_POST['city_name']) && $_POST['city_name']!='')?$this->naukriTool->getCityIdByName($this->input->post('city_name')):'';

            //below line is used for conversion tracking purpose
            if(!empty($_POST['trackingPageKeyId'])){
                $data['trackingPageKeyId']= $this->input->post('trackingPageKeyId');
            }
			
            if(!empty($_POST['shortlistTrackingPageKeyId'])){
                $data['shortlistTrackingPageKeyId']= $this->input->post('shortlistTrackingPageKeyId');
            }
       
            $pageSize 	= 10;
            $pageStart	= ($pageNo*$pageSize);
            $param          = array('jobFunction'=>$jobFunction, 'company'=>$company, 'cityId'=>$cityId, 'pageStart'=>$pageStart, 'pageSize'=>$pageSize);
            $result = $this->naukritool->getInstitueRsult($param);
            $instituteIds = $result['institute'];
            $data['ctc50'] = $result['ctc50'];
            $data['total'] = $result['total'];
            $data['pageNo'] = $pageNo;
            $data['pageSize'] = $pageSize;
            
            if(is_array($instituteIds) && count($data['total'])>0){
                $this->load->builder('nationalInstitute/InstituteBuilder'); //Get the institute object
                $instituteBuilder = new InstituteBuilder;
                $this->instituteRepository = $instituteBuilder->getInstituteRepository();

                $this->load->builder("nationalCourse/CourseBuilder");
                $courseBuilder = new CourseBuilder();
                $courseRepository = $courseBuilder->getCourseRepository();
                
                $data['instituteObjs'] = $this->instituteRepository->findMultiple($instituteIds);
                $shortlistedCourses = Modules::run('myShortlist/MyShortlist/getShortlistedCourse',  $this->userStatus[0]['userid']);
                $data['shortlistedCourses'] = $shortlistedCourses;

                $this->load->library("nationalInstitute/InstituteDetailLib");
                $InstituteDetailLibObj = new InstituteDetailLib();
                
                $courseIdForShorlist = array();
                foreach ($instituteIds as $key => $instituteId) {
                    $result = $InstituteDetailLibObj->getInstituteCourseIds($instituteId, 'institute');
                    if(is_array($result) && (count($result) < 0 || empty($result))){
                        $courseList = array();
                    }else{
                        $courseList = $result['courseIds'];
                    }
                    if(count($courseList) > 0){
                        $allCourses = $courseRepository->findMultiple($courseList,array('basic', 'eligibility'));
                    }
                    $mbaFullTimeCourseIds    = array();
                    $mbaBaseCourseId         = MANAGEMENT_COURSE;
                    $fullTimeEducationTypeId = EDUCATION_TYPE;
                    $finalCourseIds = '';
                    foreach ($allCourses as $courseObj) {
                        $courseTypeInfo = $courseObj->getCourseTypeInformation();
                        foreach ($courseTypeInfo as $courseTypeObj) {
                            if($courseTypeObj){
                                $baseCourseId = $courseTypeObj->getBaseCourse();
                                if($baseCourseId == $mbaBaseCourseId){
                                    $educationType = $courseObj->getEducationType();
                                    $educationTypeId = 0;
                                    if(is_object($educationType)){
                                        $educationTypeId = $educationType->getId();
                                    }
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
                            $finalCourseIds = $InstituteDetailLibObj->getCourseViewCount($mbaFullTimeCourseIds);
                            $finalCourseIds = array_keys($finalCourseIds);
                            $finalCourseIds = $finalCourseIds[0];
                        }
                    }
                    $courseIdForShorlist[$instituteId] = $finalCourseIds;
                }
                $data['courseIdForShorlist'] = $courseIdForShorlist;
            }
            echo $this->load->view('instituteListWidget', $data, true);
        }
    }

    function showInstituteDetails(){
    	$data = array();
    	$instituteId = $this->input->post("instId");
    	$this->load->model('naukritool');
    	$institutemodel = $this->load->model("nationalInstitute/institutemodel");
    	$naukriTool 	= new NaukriTool;
    	$naukriAverageSalary = 0;
        $mbaBaseCourseId      = MANAGEMENT_COURSE;
        $fullTimeEducationTypeId = EDUCATION_TYPE;
        $mbaCourseIds = array();
        // load the course object from Course Repo
        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $courseRepository = $courseBuilder->getCourseRepository();
    
        $this->load->library("nationalInstitute/InstituteDetailLib");
        $lib = new InstituteDetailLib();
        $result = $lib->getInstituteCourseIds($instituteId, 'institute');
        if(is_array($result) && (count($result) < 0 || empty($result))){
            return;
        }
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
                        $educationTypeId = 0;
                        if(is_object($educationType)){
                            $educationTypeId = $educationType->getId();
                        }
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
                $finalCourseIds = $lib->getCourseViewCount($mbaFullTimeCourseIds);
                $finalCourseIds = array_keys($finalCourseIds);
                $finalCourseIds = $finalCourseIds[0];
            }
        }

        $salaryDataResults = $institutemodel->getNaukriSalaryData($instituteId);
        foreach($salaryDataResults as $naukriDataRow){
            if($naukriDataRow['exp_bucket'] == '2-5')
                $naukriAverageSalary = $naukriDataRow;
        }
        //_p($allCourses);die;
        $courseObj = $allCourses[$finalCourseIds];
        //_p($courseObj);die;
        if (!is_object($courseObj)) {
            return false;
        }

        $this->rankingLib = $this->load->library('rankingV2/RankingCommonLibv2');
        //$courseRank = $this->rankingLib->getCourseRankBySource(array($finalCourseIds));
        $courseRank = $this->rankingLib->getCourseRankBySource(array($finalCourseIds));
        $courseObj->course_ranking = $courseRank[$finalCourseIds];

        //_p($courseObj->course_ranking);die;
    	
    	$total_alumni = 0;
    	foreach($salaryDataResults as $salaryData) {
    		    $total_alumni = $total_alumni + $salaryData['tot_emp'];
    	}

    	$data['total_alumni'] = $total_alumni;
    	$naukriFunctionalSalaryData = $this->naukriTool->getNaukriFunctionalSalaryData($instituteId);
        $naukriFunctionalSalaryData = array_slice($naukriFunctionalSalaryData,0,4);
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

    	$jobFunctionAlumniData['jobFuncData']   = $this->naukriTool->getJobFunctionsData(array(307));
    	$jobFunctionAlumniData['companiesData'] = $this->naukriTool->getCompaniesData(array(307));
    	$configData  	   = $this->config->item('jobFuncMapping','NaukriToolConfig');
        $data['allData']=$jobFunctionAlumniData;
        
    	$jobFunctionAlumniData = checkForOtherData($jobFunctionAlumniData);
    	$jobFunctionAlumniData = getJobOperationShortName($jobFunctionAlumniData, $configData);
    	//$jobFunctionAlumniData['companiesData'] = getJobOperationShortName($jobFunctionAlumniData['companiesData'], $configData);
    	$jobFunctionAlumniData = json_encode($jobFunctionAlumniData);
    	$data['instituteNaukriSalaryData'] = $naukriAverageSalary;
    	
        //below line is used for conversion tracking purpose
        $data['shortlistTrackingPageKeyId'] = 448;
        $data['shortlistbottomTrackingPageKeyId'] =314;

    	// myShortlist (remainig)
        	$categoryPage_SubCat = 23;    
        	$data['categoryPage_SubCat'] = $categoryPage_SubCat;
        	$data['userInfo'] = $this->userStatus;
        	if($categoryPage_SubCat == 23){
        	    if(isset($data['userInfo'][0]['userid'])) {
        			     $data['shortlistedCoursesOfUser'] =  Modules::run('myShortlist/MyShortlist/fetchShortlistedCoursesOfAUser',$data['userInfo'][0]['userid']); 
        	    }
        	}
   
            $data['courseIdForShorlist'] = $courseIdForShorlist;
        
        //below line is used for conversion tracking purpose
    	if(!empty($_POST['trackingPageKeyId'])){
            $data['trackingPageKeyId']=$this->input->post('trackingPageKeyId');
        }
                 
        //get Shortlisted course from Db/cookie
        //$data['courseShortArray'] = Modules::run('mobile_category5/CategoryMobile/getMShortlistedCourse');
        //$data['courseMBAShortlistArray'] = Modules::run('mobile_category5/CategoryMobile/getMBAShortlistedCourses');
        $shortlistedCourses = Modules::run('myShortlist/MyShortlist/getShortlistedCourse',  $this->userStatus[0]['userid']);
        $data['shortlistedCourses'] = $shortlistedCourses;


        $data['courseObj'] = $courseObj;
    	$html = $this->load->view("naukriToolInstituteDetails",$data,true);
    	echo json_encode(array(
    			       "html" => $html,
    			       "chart" => $data['chart'],
    			       "jobFunctionAlumniData"=>$jobFunctionAlumniData));
    	
        }
    }
?>

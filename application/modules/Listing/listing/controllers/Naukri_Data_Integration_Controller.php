<?php if (!defined ('BASEPATH')) exit ('No direct access allowed');

class Naukri_Data_Integration_Controller extends MX_Controller {
    
    public function index() {
            ini_set('memory_limit',-1); 
	    ini_set('max_execution_time', -1);		    
            ini_set('display_errors',1);
	    $this->benchmark->mark('code_start');
	    error_log("STARTED METHOD ########################### index");
	    
	    $this->readSalaryData();
	    $this->readAlumniData();
	    
	    error_log("ENDED METHOD ########################### index");
	    $this->benchmark->mark('code_end');
	    error_log("total time taken to run index():::".$this->benchmark->elapsed_time('code_start', 'code_end')."seconds");
	    register_shutdown_function("self::printErrorStack");
    }
    
    public function getDataBaseConnection() {
	    return $this->load->database('default',TRUE);
    }
    
    public function readSalaryData() {

	    $file_name = "/var/www/html/shiksha/public/SalaryData.xlsx";
	    $this->load->library('common/PHPExcel');
	    $objReader= PHPExcel_IOFactory::createReader('Excel2007');
	    $objReader->setReadDataOnly(true);
	    $objPHPExcel=$objReader->load($file_name);
	    $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);
	    
	    $this->load->model('listing/coursemodel');
	    $courseModel = new coursemodel;
	    
	    for ($i=2;$i<=1183;$i++) {
		$institute_id = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
		$exp_bucket = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
		$tot_emp = $objWorksheet->getCellByColumnAndRow(2,$i)->getValue();
		$avg_ctc = $objWorksheet->getCellByColumnAndRow(3,$i)->getValue();
		$ctc85 = $objWorksheet->getCellByColumnAndRow(4,$i)->getValue();
		$ctc50 = $objWorksheet->getCellByColumnAndRow(5,$i)->getValue();
		$ctc25 = $objWorksheet->getCellByColumnAndRow(6,$i)->getValue();
		
		if($exp_bucket == '2 to 5') {
		    $exp_bucket = '2-5';
		}
		
		$data_inst=array(
				'institute_id'=>$institute_id,
				'exp_bucket'=>$exp_bucket,
				'tot_emp'=>$tot_emp,
				'avg_ctc'=>$avg_ctc,
				'ctc85'=>$ctc85,
				'ctc50'=>$ctc50,
				'ctc25'=>$ctc25
				);
    
		$courseModel->add_data('naukri_salary_data',$data_inst);
	    }
	    return;
    }
    
    public function readAlumniData() {
	    $file_name = "/var/www/html/shiksha/public/Institute-AlumniStats.xlsx";
	    $this->load->library('common/PHPExcel');
            $objReader= PHPExcel_IOFactory::createReader('Excel2007');
	    $objReader->setReadDataOnly(true);
	    $objPHPExcel=$objReader->load($file_name); 
	    $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);
	    $this->load->model('listing/coursemodel');
	    $courseModel = new coursemodel;
	    
	    for ($i=2;$i<=161469;$i++) {
		
		$institute_id = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
		$functional_area = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
		$specialization = $objWorksheet->getCellByColumnAndRow(2,$i)->getValue();
		$comp_label = $objWorksheet->getCellByColumnAndRow(3,$i)->getValue();
		$industry = $objWorksheet->getCellByColumnAndRow(4,$i)->getValue();
		$total_emp = $objWorksheet->getCellByColumnAndRow(5,$i)->getValue();
		
		$data_inst=array(
				'institute_id'=>$institute_id,
				'functional_area'=>$functional_area,
				'specialization'=>$specialization,
				'comp_label'=>$comp_label,
				'industry'=>$industry,
				'total_emp'=>$total_emp
				);
		error_log("###naukri_alumni_stats ".$i);
		$courseModel->add_data('naukri_alumni_stats',$data_inst);
	    }
	    return;
    }
    
    private function _checkIfNaukriDataExist($course_id) {

    	$course_model = $this->load->model('listing/coursemodel');  
        $specialization_list = $course_model->getSpecializationIdsByClientCourse(array($course_id),TRUE);
        $specialization_ids = array();
        $mapped_to_full_time_mba = FALSE;
        foreach($specialization_list[$course_id] as $row) {

		if(!$mapped_to_full_time_mba) {
			if($row['ParentId'] == 2 || $row['SpecializationId'] == 2) {
				$mapped_to_full_time_mba = TRUE;			
                        } 
                }
                
		$specialization_ids[] = $row['SpecializationId'];			
        } 

        $response_array = array('splz_ids'=>$specialization_ids,'mapped_to_full_time_mba'=>$mapped_to_full_time_mba);           
        return $response_array;
    } 
    /*
     *  API to check if courses have valid naukri data available.
     *  Input: An array with values as instituteId and keys as courseId
     *       $course_institute_array = array('73056' => '24995','1653' => '307','8377' => '3050','2364'=> '486','113084'=>'28564');
     *  Ouput: An array of courses having valid naukri data.
     *       $returnArray = Array
                            (   [0] => 73056,
                                [1] => 1653,
                                [2] => 113084
                            )
     */
    /*
     * Portions of this function flow should be moved to a library and also getDataForNaukriSalaryWidget() should also be built using functions in it's flow.
    */
    public function getCourseHavingNaukriData($course_institute_array){
        $course_institute_array = $this->security->xss_clean($course_institute_array);
        //place validation checks
        if(!is_array($course_institute_array)){
            return;
        }
        $courseIds = array_keys($course_institute_array);
        $this->load->builder('nationalCourse/CourseBuilder');
        $courseBuilder     = new CourseBuilder();
        $courseRepository  = $courseBuilder->getCourseRepository();
        $courseObjects     = $courseRepository->findMultiple($courseIds);
        global $mbaBaseCourse;
        global $fullTimeEdType;
        $eligibleCourses = array();
        foreach ($courseObjects as $courseObj){
            $baseCourse              = $courseObj->getBaseCourse();
            $educationType           = $courseObj->getEducationType();
            if(($baseCourse['entry'] == $mbaBaseCourse && !empty($educationType) && $educationType->getId() == $fullTimeEdType)){
                $eligibleCourses[$courseObj->getId()] = $courseObj->getId();
            }
        }
        
        $instituteIds = array();
        foreach($eligibleCourses as $eligibleCourseId){
            $instituteIds[] = $course_institute_array[$eligibleCourseId];
        }
        $instituteIds = array_unique($instituteIds);
        $naukriData = $this->getNaukriData($instituteIds);
        $institutesWithNaukriData = array();
        foreach($naukriData as $instituteId=>$instituteWiseNaukriData){
            if($instituteWiseNaukriData['salary_total_employee'] >30 || $instituteWiseNaukriData['totalNaukriEmployees'] >30){
                $institutesWithNaukriData[$instituteId] = 'true';
            }
        }
        $returnArray = array();
        foreach($course_institute_array as $courseId => $instituteId){
            if(isset($eligibleCourses[$courseId]) && isset($institutesWithNaukriData[$instituteId])){
                $returnArray[] = $courseId;
            }
        }
        return $returnArray;
    }
    private function _processNaukriSalaryData($salaryDataResults){
        $total_employees = 0;	               
        foreach($salaryDataResults as $salaryData) {
                $total_employees = $total_employees + $salaryData['tot_emp'];
                if($salaryData['exp_bucket'] == '0-2') {
                    $NoOfEmployees_bucket1  = $salaryData['tot_emp'];
                    $data[0]['Exp_Bucket']  = $salaryData['exp_bucket'];
                    $data[0]['AvgCTC']      = $salaryData['ctc50'];
                }
                else if($salaryData['exp_bucket'] == '2-5') {
                    $NoOfEmployees_bucket2  = $salaryData['tot_emp'];
                    $data[1]['Exp_Bucket']  = $salaryData['exp_bucket'];
                    $data[1]['AvgCTC']      = $salaryData['ctc50'];
                }
                else {
                    $NoOfEmployees_bucket3  = $salaryData['tot_emp'];
                    $data[2]['Exp_Bucket']  = $salaryData['exp_bucket'];
                    $data[2]['AvgCTC']      = $salaryData['ctc50'];
                }
        }

        unset($salaryDataResults);
        if($total_employees > 30) {
                $show_bucket1 = 'yes';
                $show_bucket2 = 'yes';
                $show_bucket3 = 'yes';

                if($NoOfEmployees_bucket1 < 10) {
                        $show_bucket1 = 'no';
                        unset($data[0]);
                }
                if($NoOfEmployees_bucket2 < 10) {
                        $show_bucket2 = 'no';
                        unset($data[1]);
                }
                if($NoOfEmployees_bucket3 < 10) {
                        $show_bucket3 = 'no';
                        unset($data[2]);
                }

                if(($show_bucket2 == 'yes') && ($data[1]['AvgCTC'] < $data[0]['AvgCTC'])) {
                        $show_bucket2 = 'no';
                        unset($data[1]);	
                }
                if(($show_bucket3 == 'yes') && (($data[2]['AvgCTC'] < $data[1]['AvgCTC']) || ($data[2]['AvgCTC'] < $data[0]['AvgCTC']))) {
                        $show_bucket3 = 'no';
                        unset($data[2]);
                }
        }

        else {
            unset($data[0]);
            unset($data[1]);
            unset($data[2]);
        }
        ksort($data);
        $returnArray['data'] = $data;
        $returnArray['total_employees'] = $total_employees;
        return $returnArray;
    }
    public function getNaukriSalaryData($instituteId,$numberOfInstitutes='single'){
        if(empty($instituteId))
            return;
        if($numberOfInstitutes =='multiple' && !is_array($instituteId)||count($instituteId)==0)
            return;
        $this->load->model('nationalInstitute/institutedetailsmodel');
        $instituteDetailsModel = new institutedetailsmodel;
        $results = $instituteDetailsModel->getNaukriSalaryData($instituteId,$numberOfInstitutes,array('institute_id','exp_bucket','tot_emp','ctc50'));
        $instituteWiseSalaryData = array();
        foreach($results as $row){
            $instituteWiseSalaryData[$row['institute_id']][] = $row;
        }
        foreach($instituteWiseSalaryData as $instituteId=>$salaryDataResults){
            $salaryDataResults  = $this->_processNaukriSalaryData($salaryDataResults);
            $response[$instituteId]['chart']                 =  json_encode($salaryDataResults['data']);
            $response[$instituteId]['salary_data_count']     = count($salaryDataResults['data']);
            $response[$instituteId]['salary_total_employee'] = $salaryDataResults['total_employees'];
        }
        return $response;
    }
    private function _processNaukriEmployeesData($naukri_employees_data){
        $totalNaukriEmployees  = 0;
        $specialization_array  = array();
        foreach($naukri_employees_data as $row) {
            //$specialization_array[] = $row['specialization'];
            $totalNaukriEmployees   = $totalNaukriEmployees + $row['total_emp'];
        }
        //$returnArray['specialization_array'] = $specialization_array;
        $returnArray['totalNaukriEmployees'] = $totalNaukriEmployees;
        return $returnArray;
    }
    public function getNaukriEmployeesData($instituteId,$numberOfInstitutes='single'){
        if(empty($instituteId))
            return;
        if($numberOfInstitutes =='multiple' && !is_array($instituteId)||count($instituteId)==0)
            return;
        $this->load->model('nationalInstitute/institutedetailsmodel');
        $instituteDetailsModel = new institutedetailsmodel;
        //$result = $instituteDetailsModel->getNaukriEmployeesData($instituteId,$numberOfInstitutes,array('total_emp','institute_id'));
        $result = $instituteDetailsModel->getCountOfNaukriEmployeesDataForInstitute($instituteId,$numberOfInstitutes);
        $instituteWiseEmployeesData = array();
        foreach($result as $row){
            $instituteWiseEmployeesData[$row['institute_id']][] = $row;
        }
        unset($result);
        foreach($instituteWiseEmployeesData as $instituteId=>$naukri_employees_data){
            $naukri_employees_data = $this->_processNaukriEmployeesData($naukri_employees_data);
            //$response[$instituteId]['specialization_array'] = $naukri_employees_data['specialization_array'];
            $response[$instituteId]['totalNaukriEmployees'] = $naukri_employees_data['totalNaukriEmployees'];
        }
        return $response;
    }
    public function getNaukriData($instituteIds){
        //place validation checks
        if(!is_array($instituteIds) || count($instituteIds)==0){
            return;
        }
        $salaryData    = $this->getNaukriSalaryData($instituteIds,'multiple');
        $employeesData = $this->getNaukriEmployeesData($instituteIds,'multiple');
        $returnArray = array();
        foreach($salaryData as $instituteId =>$data){
            $returnArray[$instituteId] = array_merge($salaryData[$instituteId],$employeesData[$instituteId]);
            unset($employeesData[$instituteId]);
        }
        foreach ($employeesData as $instituteId=>$data){
            $returnArray[$instituteId] = $data;
        }
        return $returnArray;
    }
    public function getDataForNaukriSalaryWidget($instituteId,$course_id,$posted_splz_id,$no_of_functional,$no_of_companies,$mobileFlag=0, $pageType = false , $ampViewFlag=false) {
        /* Adding XSS cleaning (Nikita) */
            $instituteId        = $this->security->xss_clean($instituteId);
            $course_id          = $this->security->xss_clean($course_id);
            $posted_splz_id     = $this->security->xss_clean($posted_splz_id);
            $no_of_functional   = $this->security->xss_clean($no_of_functional);
            $no_of_companies    = $this->security->xss_clean($no_of_companies);
            $mobileFlag         = $this->security->xss_clean($mobileFlag);
            $pageType           = $this->security->xss_clean($pageType);
            $ampViewFlag        = $this->security->xss_clean($ampViewFlag);

            //$viewType           = $this->security->xss_clean($viewType);
            $isShortlistPage    = $this->input->post("isShortlistPage", true);

            $this->load->builder('nationalCourse/CourseBuilder');
            $courseBuilder              = new CourseBuilder();
            $courseRepository           = $courseBuilder->getCourseRepository();
            $courseObj                  = $courseRepository->find($course_id);
            $baseCourse                 = $courseObj->getBaseCourse();
            $educationType              = $courseObj->getEducationType();

	    //Adding exception for some Courses on which we don't want to show the Naukri Data
	    $exceptionArray = array(298950,298924,302839,302851);
	    if(in_array($course_id, $exceptionArray)){
		echo json_encode("");
		return;	
	    }

            global $mbaBaseCourse;
            global $fullTimeEdType;
            if(!($baseCourse['entry'] == $mbaBaseCourse && !empty($educationType) && $educationType->getId() == $fullTimeEdType)){
                if( $pageType=="courseDetailPageNaukriLayer"||
                    $pageType=="courseDetailPage"||
                    $pageType=="courseDetailPageIndustryNaukriLayer"||
                    $pageType=="courseDetailPageCompanyNaukriLayer"
                  ){
                    echo json_encode("");return;
                }
               	return;exit();
            }
            $courseHierarchies          = $courseObj->getCourseTypeInformation();
            $courseHierarchies          = $courseHierarchies['entry_course']->getHierarchies();
            $courseSpecializations      = array();

            foreach($courseHierarchies as $courseHierarchy){
               $courseSpecializations[] = $courseHierarchy['specialization_id'];
            }
	   
        $this->load->model('nationalInstitute/institutedetailsmodel');
	    $instituteDetailsModel = new institutedetailsmodel;
	    
	    $data = array();	   
	    $salaryDataResults = $instituteDetailsModel->getNaukriSalaryData($instituteId);
	    $total_employees = 0;	               
	    foreach($salaryDataResults as $salaryData) {
		    $total_employees = $total_employees + $salaryData['tot_emp'];
		    if($salaryData['exp_bucket'] == '0-2') {
			$NoOfEmployees_bucket1  = $salaryData['tot_emp'];
			$data[0]['Exp_Bucket']  = $salaryData['exp_bucket'];
			$data[0]['AvgCTC']      = $salaryData['ctc50'];
		    }
		    else if($salaryData['exp_bucket'] == '2-5') {
			$NoOfEmployees_bucket2  = $salaryData['tot_emp'];
			$data[1]['Exp_Bucket']  = $salaryData['exp_bucket'];
			$data[1]['AvgCTC']      = $salaryData['ctc50'];
		    }
		    else {
			$NoOfEmployees_bucket3  = $salaryData['tot_emp'];
			$data[2]['Exp_Bucket']  = $salaryData['exp_bucket'];
			$data[2]['AvgCTC']      = $salaryData['ctc50'];
		    }
	    }
	    
	    if($total_employees > 30) {
		    $show_bucket1 = 'yes';
		    $show_bucket2 = 'yes';
		    $show_bucket3 = 'yes';
		    
		    if($NoOfEmployees_bucket1 < 10) {
			    $show_bucket1 = 'no';
			    unset($data[0]);
		    }
		    if($NoOfEmployees_bucket2 < 10) {
			    $show_bucket2 = 'no';
			    unset($data[1]);
		    }
		    if($NoOfEmployees_bucket3 < 10) {
			    $show_bucket3 = 'no';
			    unset($data[2]);
		    }
		    
		    if(($show_bucket2 == 'yes') && ($data[1]['AvgCTC'] < $data[0]['AvgCTC'])) {
			    $show_bucket2 = 'no';
			    unset($data[1]);	
		    }
		    if(($show_bucket3 == 'yes') && (($data[2]['AvgCTC'] < $data[1]['AvgCTC']) || ($data[2]['AvgCTC'] < $data[0]['AvgCTC']))) {
			    $show_bucket3 = 'no';
			    unset($data[2]);
		    }
	    }
	    
	    else {
		unset($data[0]);
		unset($data[1]);
		unset($data[2]);
	    }
	    
	    $response = array();
	    ksort($data);	
            $response['chart'] =  json_encode($data);
            $response['salary_data_count'] = count($data);
            $response['salary_total_employee'] = $total_employees;
            
           /* $Validate = $this->checkUserValidation();
            if($Validate == "false"){
                $response['isloggedin'] = false;
            }else{
                $response['isloggedin'] = true;
            }
            */
            $naukri_employees_data = $instituteDetailsModel->getNaukriEmployeesData($instituteId);
            $totalNaukriEmployees  = 0;
            $specialization_array  = array();
            foreach($naukri_employees_data as $row) {
                $specialization_array[] = $row['specialization'];
                $totalNaukriEmployees   = $totalNaukriEmployees + $row['total_emp'];
            }
 
            $specialization_array = array_unique($specialization_array);           
            $posted_splz_id       = base64_decode($posted_splz_id);
            if(!empty($posted_splz_id)) {
		$naukri_splz_specialization_selected = $posted_splz_id;
            }
            else if(empty($naukri_splz_specialization_selected)){
                $naukri_splz_specialization_selected = $this->_getNaukriSpecialization($courseSpecializations,$specialization_array);
            }
            
            if(empty($no_of_functional)) {
		      $no_of_functional = 5;
            }

            if(empty($no_of_companies)) {
	    	$no_of_companies = 5;	
            }
            $naukri_employees_data_copy = $naukri_employees_data;
            if(!empty($naukri_splz_specialization_selected)) {
	    	foreach($naukri_employees_data as $key=>$row) {
		    if($naukri_splz_specialization_selected == "All Specialization") {
			break;
		    }
		    else if($naukri_splz_specialization_selected == "IT & Systems") {
			if($row['specialization'] != "Information Technology" && $row['specialization'] != "Systems") {
			    unset($naukri_employees_data[$key]);		
			}
		    }
		    else {
			if($row['specialization'] != $naukri_splz_specialization_selected) {
                            unset($naukri_employees_data[$key]);		
                        }
		    }
                }
		if(count($naukri_employees_data) == 0) {
		    $naukri_splz_specialization_selected =  "All Specialization";
                    $naukri_employees_data               =  $naukri_employees_data_copy;
		    //$naukri_employees_data = $instituteDetailsModel->getNaukriEmployeesData($instituteId);  
		} 
            }
	    if($totalNaukriEmployees > 30) {
          if($ampViewFlag){
            $response['placement_data_all_spec']         = $this->getNaukriPlacementCompaniesWidget($naukri_employees_data,'all');
            $response['placement_data_count']   = count(json_decode($response['placement_data_all_spec'],true));
            $response['naukriCompaniesAndFuncWRTSpec'] = $this->getNaukriCompaniesAndFuncWRTSpecForAMP($naukri_employees_data);
            $response['industry_all']  = $this->getNaukriFunctionalAreaWidget($naukri_employees_data,'all');  
            $response['industry_count']         = count(json_decode($response['industry_all'],true));
          }else {
            $response['placement_data']         = $this->getNaukriPlacementCompaniesWidget($naukri_employees_data,$no_of_companies);
            $response['placement_data_count']   = count(json_decode($response['placement_data'],false));
            $response['industry']               = $this->getNaukriFunctionalAreaWidget($naukri_employees_data,$no_of_functional);           
            $response['industry_count']         = count(json_decode($response['industry'],false));
         }
    	  $response['placement_comp_count']   = $this->getNaukriPlacementCompaniesCount($naukri_employees_data);
    	  $response['industry_fa_count']      = $this->getNaukriFunctionalAreaCount($naukri_employees_data);
		}
	    else {
		$response['placement_data_count']   = 0;
		$response['placement_comp_count']   = 0;
		$response['industry_count']         = 0;
		$response['industry_fa_count']      = 0;
	    }
	    $response['selected_naukri_splzn']      = $naukri_splz_specialization_selected;
	    $response['naukri_specializations']     = $specialization_array;  
	    $response['no_of_companies']            = $no_of_companies; 	          
	    $response['no_of_functional']           = $no_of_functional;	
	    $response['total_naukri_employees']     = $totalNaukriEmployees;
	    
	    $response['isShortlistPage']            = $isShortlistPage;
	    $response['instituteId']                = $instituteId;
	    $response['course_id']                  = $course_id;
	    $response['pageType']                   = $pageType;
            $response['courseName']                 = $courseObj->getName();
            $response['instituteName']              = $courseObj->getInstituteName();
            if($mobileFlag==1){
                $this->load->helper(array('mcommon5/mobile_html5','shikshautility_helper'));
                if($pageType =='courseDetailPage' && !$ampViewFlag){
                    echo json_encode($this->load->view('mobile_listing5/course/widgets/courseAlumniEmploymentWidget',$response,true));
                }
                else if($pageType =='courseDetailPage' && $ampViewFlag){
                    return $response;
                }
                else if($pageType=='courseDetailPageCompanyNaukriLayer'){
                    $res = array();
                    $res['heading'] = "Employment details of ".$totalNaukriEmployees." alumni";
                    $res['content'] = $this->load->view('mobile_listing5/course/widgets/courseAlumniCompanyLayer',$response,true);
                    echo json_encode($res);
                }
                else if($pageType=='courseDetailPageIndustryNaukriLayer'){
                    $res = array();
                    $res['heading'] = "Employment details of ".$totalNaukriEmployees." alumni";
                    $res['content'] = $this->load->view('mobile_listing5/course/widgets/courseAlumniIndustryLayer',$response,true);
                    echo json_encode($res);
                }else if($pageType=='mobileshortlist'){
                	echo base64_encode($this->load->view('mobile_listing5/naukridataintegration/NaukriSalaryDataWidget',$response,true));
                }
            }
            else{
                
                if($pageType=='courseDetailPage'){
                    echo json_encode($this->load->view('nationalCourse/CoursePage/CourseAlumniEmploymentWidget',$response,true));
                }
                else if($pageType=='courseDetailPageNaukriLayer'){
                    echo json_encode($this->load->view('nationalCourse/CoursePage/CourseNaukriSalaryLayer',$response,true));
                }
                else{
                    echo base64_encode($this->load->view('listing/naukridataintegration/NaukriSalaryDataWidget',$response,true));
                }
            }
    }
   
    public function getNaukriPlacementCompaniesWidget($placementDataResults,$no_of_companies) {   	    
	     	   	
            $response_array = array();
            foreach($placementDataResults as $data) {
    		$response_array[$data['comp_label']][] = $data['total_emp'];
            }        
            $final_response = array();

            foreach($response_array as $key=>$value) {
                $total_emp = array_sum($value);
		$final_response[] = array('comp_name'=>$key,'no_of_emps'=>array_sum($value));
	    }

	    uasort($final_response, function ($a, $b) {
		if ($a['no_of_emps'] == $b['no_of_emps']) {
			return 0;
		}
		return ($a['no_of_emps'] < $b['no_of_emps']) ? 1 : -1;
	        }
	    );
            if($no_of_companies!='all'){
                $send_array = array_slice($final_response,0,$no_of_companies);
                return  json_encode($send_array);	    
            }
            else{
                $send_array = array_values($final_response);
                return  json_encode($send_array);	    

    
            }
             
            
   }

   public function getNaukriCompaniesAndFuncWRTSpecForAMP($placementDataResults) {          
                
            $response_array = array();
            foreach($placementDataResults as $data) {
                $response_company_array[$data['specialization']][$data['comp_label']][] = $data['total_emp'];
                $response_functions_array[$data['specialization']][$data['functional_area']][] = $data['total_emp'];

            }       
            $final_response = array();
            foreach($response_company_array as $specName=>$specVal) {
                foreach ($specVal as $compName => $empCount) {
                $total_emp = array_sum($empCount);
                $final_response[$specName]['companies'][$compName] = $total_emp;
                }
            }

            foreach($response_functions_array as $funKey=>$funVal) {
                foreach ($funVal as $funcName => $empCount) {
                $total_emp = array_sum($empCount);
                $final_response[$funKey]['functions'][$funcName] = $total_emp;
            }
        }
        foreach ($final_response as $index=>&$valArr) {
            foreach ($valArr as $indexName=>&$value) {
                arsort($value);
            }
        }
        return $final_response;
            
   }

  
   
   public function getNaukriPlacementCompaniesCount($placementDataResults) {   	    
	     	   	
            $response_array = array();

            foreach($placementDataResults as $data) {
		$response_array[$data['comp_label']][] = $data['total_emp'];
            }        
   
            $final_response = array();
            
            foreach($response_array as $key=>$value) {
                $total_emp = array_sum($value);
		$final_response[] = array('comp_name'=>$key,'no_of_emps'=>array_sum($value));
	    }

	    uasort($final_response, function ($a, $b) {
		if ($a['no_of_emps'] == $b['no_of_emps']) {
			return 0;
		}
		return ($a['no_of_emps'] < $b['no_of_emps']) ? 1 : -1;
	        }
	    );
             
            return count($final_response);    
   }

   public function getNaukriFunctionalAreaWidget($placementDataResults,$no_of_functional) {   

            $response_array = array();
            global $naukri_functional_name_mapping;

            foreach($placementDataResults as $data) {
                $key = $naukri_functional_name_mapping[$data['functional_area']];
                if(empty($key)) {
			$key = "Others";
                }  
		$response_array[$key][] = $data['total_emp'];
            }        
   
            $final_response = array();
            
            foreach($response_array as $key=>$value) {                
                $total_emp = array_sum($value);
		$final_response[] = array('industry'=>$key,'no_of_emps'=>array_sum($value));
	    }

	    uasort($final_response, function ($a, $b) {
		if ($a['no_of_emps'] == $b['no_of_emps']) {
			return 0;
		}
		return ($a['no_of_emps'] < $b['no_of_emps']) ? 1 : -1;
	        }
	    );
            if($no_of_functional!='all'){
                $send_array = array_slice($final_response,0,$no_of_functional);            
                return  json_encode($send_array);	    
            }   
            else{
                $send_array = array_values($final_response);
              
                return  json_encode($send_array);	    
            }
            
            
    }
    
    public function getNaukriFunctionalAreaCount($placementDataResults) {   

            $response_array = array();
            global $naukri_functional_name_mapping;

            foreach($placementDataResults as $data) {
                $key = $naukri_functional_name_mapping[$data['functional_area']];
                if(empty($key)) {
			$key = "Others";
                }  
		$response_array[$key][] = $data['total_emp'];
            }        
   
            $final_response = array();
            
            foreach($response_array as $key=>$value) {                
                $total_emp = array_sum($value);
		$final_response[] = array('industry'=>$key,'no_of_emps'=>array_sum($value));
	    }

	    uasort($final_response, function ($a, $b) {
		if ($a['no_of_emps'] == $b['no_of_emps']) {
			return 0;
		}
		return ($a['no_of_emps'] < $b['no_of_emps']) ? 1 : -1;
	        }
	    );

            return count($final_response);	    
    }

    private function _getNaukriSpecialization($shiksha_ids,$naukri_splz) {

        $selected_splz = "";
        
   	/*if(count($shiksha_ids)>1) {
        	 $selected_splz = "";
        } else if(count($shiksha_ids) == 1) {
		 if($shiksha_ids[0] == 2 || $shiksha_ids[0] == 4) {
			$selected_splz = "";
                 } else {
			global $naukri_specialization_mapping; 
		 	$selected_splz = $naukri_specialization_mapping[$shiksha_ids[0]]['naukrispl'];                         
                 }
        } else {
		$selected_splz = ""; 		 
        }
         * 
         */
        if(count($shiksha_ids)==1){
            global $naukri_specialization_mapping; 
            $selected_splz = $naukri_specialization_mapping[$shiksha_ids[0]]['naukrispl'];
        }
      return $selected_splz;
    }
    
    public function getDataForNaukriSalaryWidgetForRankingPage($instituteId,$course_id,$posted_splz_id,$no_of_functional,$no_of_companies,$mobileFlag=0) {
            
	   /* $check_if_naukri_data_exist = $this->_checkIfNaukriDataExist($course_id);
            if($check_if_naukri_data_exist['mapped_to_full_time_mba'] == FALSE) {
		return FALSE; 
            }
             */ 
	    $this->load->model('nationalInstitute/institutedetailsmodel');
	    $instituteDetailsModel = new institutedetailsmodel;
	    $data = array();	   
	    $salaryDataResults = $instituteDetailsModel->getNaukriSalaryData($instituteId);
	    $total_employees = 0;	               
	    foreach($salaryDataResults as $salaryData) {
		    $total_employees = $total_employees + $salaryData['tot_emp'];
		    if($salaryData['exp_bucket'] == '0-2') {
			$NoOfEmployees_bucket1 = $salaryData['tot_emp'];
			$data[0]['Exp_Bucket'] = $salaryData['exp_bucket'];
			$data[0]['AvgCTC'] = $salaryData['ctc50'];
		    }
		    else if($salaryData['exp_bucket'] == '2-5') {
			$NoOfEmployees_bucket2 = $salaryData['tot_emp'];
			$data[1]['Exp_Bucket'] = $salaryData['exp_bucket'];
			$data[1]['AvgCTC'] = $salaryData['ctc50'];
		    }
		    else {
			$NoOfEmployees_bucket3 = $salaryData['tot_emp'];
			$data[2]['Exp_Bucket'] = $salaryData['exp_bucket'];
			$data[2]['AvgCTC'] = $salaryData['ctc50'];
		    }
	    }

	    if($total_employees > 30) {
		    $show_bucket1 = 'yes';
		    $show_bucket2 = 'yes';
		    $show_bucket3 = 'yes';
		    
		    if($NoOfEmployees_bucket1 < 10) {
			    $show_bucket1 = 'no';
			    unset($data[0]);
		    }
		    if($NoOfEmployees_bucket2 < 10) {
			    $show_bucket2 = 'no';
			    unset($data[1]);
		    }
		    if($NoOfEmployees_bucket3 < 10) {
			    $show_bucket3 = 'no';
			    unset($data[2]);
		    }
		    
		    if(($show_bucket2 == 'yes') && ($data[1]['AvgCTC'] < $data[0]['AvgCTC'])) {
			    $show_bucket2 = 'no';
			    unset($data[1]);	
		    }
		    if(($show_bucket3 == 'yes') && (($data[2]['AvgCTC'] < $data[1]['AvgCTC']) || ($data[2]['AvgCTC'] < $data[0]['AvgCTC']))) {
			    $show_bucket3 = 'no';
			    unset($data[2]);
		    }
	    }
	    
	    else {
		unset($data[0]);
		unset($data[1]);
		unset($data[2]);
	    }
	    
	    $response = array();
	    ksort($data);	
            $response['chart'] =  json_encode($data);
            $response['salary_data_count'] = count($data);
            $response['salary_total_employee'] = $total_employees;
           
	    $response['isloggedin'] = true;
            

           
            echo base64_encode($this->load->view('listing/naukridataintegration/NaukriSalaryDataWidgetForRankingPage',$response,true));
    }


    public function readFunctionalAreaSalaryMapping() {
            $file_name = "/var/www/html/shiksha/public/CollegeFunctionalAreaSalary.xlsx";
            $this->load->library('common/PHPExcel');
            $objReader= PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(true);
            $objPHPExcel=$objReader->load($file_name);
            $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);

            $this->load->model('listing/coursemodel');
            $courseModel = new coursemodel;

            for ($i=2;$i<=3000;$i++) {
                $institute_id = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
                $farea = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
                $functional_area = $objWorksheet->getCellByColumnAndRow(2,$i)->getValue();
                $exp_bucket = $objWorksheet->getCellByColumnAndRow(3,$i)->getValue();
		$tot_emp = $objWorksheet->getCellByColumnAndRow(4,$i)->getValue();
		$avg_ctc = $objWorksheet->getCellByColumnAndRow(5,$i)->getValue();
                $ctc85 = $objWorksheet->getCellByColumnAndRow(6,$i)->getValue();
                $ctc50 = $objWorksheet->getCellByColumnAndRow(7,$i)->getValue();
                $ctc25 = $objWorksheet->getCellByColumnAndRow(8,$i)->getValue();

                if($exp_bucket == '2 to 5') {
                    $exp_bucket = '2-5';
                }

                $data_inst=array(
                                'institute_id'=>$institute_id,
				'farea'=>$farea,
				'functional_area'=>$functional_area,
                                'exp_bucket'=>$exp_bucket,
                                'tot_emp'=>$tot_emp,
                                'avg_ctc'=>$avg_ctc,
                                'ctc85'=>$ctc85,
                                'ctc50'=>$ctc50,
                                'ctc25'=>$ctc25
                                );

                $courseModel->add_data('naukri_functional_salary_data',$data_inst);
            }
            return;
    }

	/**
	 * THIS function is used to update naukri data only
	 * Data updates in these table naukri_salary_data, naukri_alumni_stats, naukri_functional_salary_data
	 * If any xlx file size > 5M, in this case we need to break file into two parts.
	 * it can be upload 80000 rows at a time only.
	 * Added by akhter
	 */
    function upadateNaukriData(){
    	ini_set('memory_limit','2000M');
    	$this->load->library('session');
    	if(isset($_POST['submit'])){
    			if($_POST['type'] =='' || $_FILES['datafile']['tmp_name'] ==''){
    				$this->session->set_flashdata('error',1);
    				$this->session->set_flashdata('msg', 'Please select your data type / file.');
		    		redirect('/naukri-data');
		    	}else if(isset($_POST['submit']) && !empty($_FILES['datafile']) && $_FILES['datafile']['tmp_name'] !=''){
		    		$this->load->library('CA/Mywallet');
					$data = $this->mywallet->readCommonExcel();
					if(isset($_POST['type']) && $_POST['type'] == 'salary'){
						$res = $this->_updateData($data,'naukri_salary_data');
					}else if(isset($_POST['type']) && $_POST['type'] == 'alumni_stats'){
						$res = $this->_updateData($data,'naukri_alumni_stats');
					}else if(isset($_POST['type']) && $_POST['type'] == 'functional_salary_data'){
						$res = $this->_updateData($data,'naukri_functional_salary_data');
					}
		    	}
	    		if($res>0){
	    			$this->session->set_flashdata('msg', 'File has been successfully uploaded.');
	    			redirect('/naukri-data');
	    		}
    	}    	
    	$this->load->view('commonExcelUploader');
    }

    function _updateData($data,$type){
    	if(count($data)>0){
			foreach ($data as $data) {
				if($type === 'naukri_salary_data' && $data['institute_id'] !='' && $data['exp_bucket'] !=''){
									$insertData[]=array(
										'institute_id'=>$data['institute_id'],
										'exp_bucket'=>$data['exp_bucket'],
										'tot_emp'=>$data['tot_emp'],
										'avg_ctc'=>$data['avg_ctc'],
										'ctc85'=>$data['ctc85'],
										'ctc50'=>$data['ctc50'],
										'ctc25'=>$data['ctc25']
									);		
				}else if($type === 'naukri_alumni_stats' && $data['institute_id'] !='' && $data['specialization'] !=''){
									$insertData[]=array(
										'institute_id'=>$data['institute_id'],
										'functional_area'=>$data['functional_area'],
										'specialization'=>$data['specialization'],
										'comp_label'=>$data['comp_label'],
										'industry'=>$data['industry'],
										'total_emp'=>$data['total_emp']
									);	
				}else if($type === 'naukri_functional_salary_data' && $data['institute_id'] !='' && $data['farea'] !=''){
									$insertData[]=array(
										'institute_id'=>$data['institute_id'],
										'farea'=>$data['farea'],
										'functional_area'=>$data['functional_area'],
										'exp_bucket'=>$data['exp_bucket'],
										'tot_emp'=>$data['tot_emp'],
										'avg_ctc'=>$data['avg_ctc'],
										'ctc85'=>$data['ctc85'],
										'ctc50'=>$data['ctc50'],
										'ctc25'=>$data['ctc25']
									);	
				}
			}
			return $this->mywallet->insertNaukriData($type,$insertData);
		}
    }
}

?>

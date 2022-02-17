<?php
class NaukriTool extends MY_Model
{
    function __construct()
    { 
            parent::__construct('NaukriTool');
    }
    
    private function initiateModel($operation='read'){
            if($operation=='read'){
                    $this->dbHandle = $this->getReadHandle();
            }else{
                $this->dbHandle = $this->getWriteHandle();
            }
    }
    
    // not used now
    public function getInstitutesFullTimeMBACourseIds($instituteId){
        $this->initiateModel('read');
        
        $sql = "SELECT
                course_id, ldb_course_id
                FROM 
                categoryPageData
                WHERE 1
                AND status= 'live'
                AND category_id = 23
                AND institute_id = ? order by course_id asc";

        $query      = $this->dbHandle->query($sql,array($instituteId));
        $numOfRows  = $query->num_rows();
        $data       = $query->result_array();
        return $data;
    }
    
    // not used now
    public function getInstitutesFullTimeMBACourseIdsForMobile($instituteIds){
        $this->initiateModel('read');
        $instituteIdsArr = explode(',', $instituteIds);
        $sql = "SELECT
                course_id, ldb_course_id, institute_id
                FROM 
                categoryPageData
                WHERE 1
                AND status= 'live'
                AND category_id = 23
                AND institute_id in (?) order by course_id asc";
        $query      = $this->dbHandle->query($sql, array($instituteIdsArr));
        $numOfRows  = $query->num_rows();
        $data       = $query->result_array();
        return $data;
    }
    
    public function getTotalEmployee($instituteIds){
        $this->initiateModel('read');
        if(!empty($instituteIds)){
            $instSubQuery = " nas.institute_id in (?)";
        }
        $sql        = "select SUM( nas.total_emp ) AS total_emp FROM naukri_alumni_stats nas where $instSubQuery";
        $query      = $this->dbHandle->query($sql, array($instituteIds));
        $numOfRows  = $query->num_rows();
        $data       = $query->result_array();
        return $data[0]['total_emp'];
    }
    
    public function getInstituteIds(){
        $this->initiateModel('read');
        $cacheLib = $this->load->library('cacheLib');
        $instituteIds = $cacheLib->get('instituteIdsForNaukriTool');
        if($instituteIds=='ERROR_READING_CACHE' || empty($instituteIds)){
            $this->dbHandle->select('distinct(sc.parent_id) as instituteId');
            $this->dbHandle->from('shiksha_courses_type_information scti');
            $this->dbHandle->join('shiksha_courses sc','sc.course_id = scti.course_id','inner');
            $this->dbHandle->where('scti.stream_id',MANAGEMENT_STREAM);
            $this->dbHandle->where('scti.base_course',MANAGEMENT_COURSE);
            $this->dbHandle->where('sc.education_type',EDUCATION_TYPE);
            $this->dbHandle->where('scti.status','live');
            $this->dbHandle->where('sc.status','live');
            $result = $this->dbHandle->get()->result_array();
            $instituteIds = array();
            foreach ($result as $key => $value) {
                $instituteIds[] = $value['instituteId'];
            }
            if(count($instituteIds) > 0){
                $this->dbHandle->select('inst.institute_id, inst.tot_emp');
                $this->dbHandle->from('(SELECT institute_id, SUM( total_emp ) AS tot_emp FROM naukri_alumni_stats GROUP BY institute_id HAVING tot_emp >=15) inst',false);
                $this->dbHandle->where_in('inst.institute_id',$instituteIds);
                $this->dbHandle->group_by('inst.institute_id');
                $result = $this->dbHandle->get()->result_array();
                $instituteIds = array();
                if(count($result) > 0){
                    foreach ($result as $key => $value) {
                        $instituteIds[] = $value['institute_id'];
                    }
                }
            }
            $cacheLib->store('instituteIdsForNaukriTool',$instituteIds, -1);
        }
        return $instituteIds;
    }
        
    public function getCityId($city_name){
        $this->initiateModel('read');
        $sql   = "select city_id from countryCityTable where city_name=?";
        $query = $this->dbHandle->query($sql,array($city_name));
        $data  = $query->result_array();
        return $data[0]['city_id'];
    }
    
    public function getJobFunctionsData($instituteIds, $companies='', $cities=''){
        $this->initiateModel('read');
        if(!empty($instituteIds)){
            $instStr = implode(',',$instituteIds);
            $instSubQuery = " where nas.institute_id in (?)";
        }

        $cacheKey = md5('comp_cities').md5($instStr).md5($companies).md5($cities);
        $cacheLib = $this->load->library('cacheLib');
        $subQueryFuncArea = " and nas.functional_area in ('Sales / BD','Marketing / Advertising / MR / PR','HR / Administration / IR','Accounts / Finance / Tax / CS / Audit','Banking / Insurance','IT Software','ITES / BPO / KPO / Customer Service / Operations','Purchase / Logistics / Supply Chain','Corporate Planning / Consulting','Production / Maintenance / Quality','Analytics & Business Intelligence','Pharma / Biotech / Healthcare / Medical / R&D','Export / Import / Merchandising','Site Engineering / Project Management')";
        if($cacheLib->get($cacheKey)=='ERROR_READING_CACHE'){
            if($companies!='' && $cities==''){
                $companiesSubQuery = ' and nas.comp_label=?';
                $sql = "SELECT functional_area, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` as nas $instSubQuery $companiesSubQuery $subQueryFuncArea GROUP BY functional_area ORDER BY totalEmployee DESC";
            }
            if($companies=='' && $cities!=''){
                $cityId = $this->getCityId($cities);
                //$sql = "SELECT functional_area, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join institute_location_table ilt on (nas.institute_id = ilt.institute_id) join countryCityTable as cct on (ilt.city_id=cct.city_id) $instSubQuery and ilt.status='live' and ilt.city_id='".$cityId."' $subQueryFuncArea GROUP BY functional_area ORDER BY totalEmployee DESC";
                $sql = "SELECT functional_area, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join shiksha_institutes_locations sil on (nas.institute_id = sil.listing_id) join countryCityTable as cct on (sil.city_id=cct.city_id) $instSubQuery and sil.status='live' and sil.city_id='".$cityId."' $subQueryFuncArea GROUP BY functional_area ORDER BY totalEmployee DESC";
            }
            
            if($companies!='' && $cities!=''){
                $cityId = $this->getCityId($cities);
                $companiesSubQuery = ' and nas.comp_label=?';
                //$sql = "SELECT functional_area, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join institute_location_table ilt on (nas.institute_id = ilt.institute_id) join countryCityTable as cct on (ilt.city_id=cct.city_id) $instSubQuery and ilt.status='live' and ilt.city_id='".$cityId."' $companiesSubQuery $subQueryFuncArea GROUP BY functional_area ORDER BY totalEmployee DESC";
                $sql = "SELECT functional_area, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join shiksha_institutes_locations sil on (nas.institute_id = sil.listing_id) join countryCityTable as cct on (sil.city_id=cct.city_id) $instSubQuery and sil.status='live' and sil.city_id='".$cityId."' $companiesSubQuery $subQueryFuncArea GROUP BY functional_area ORDER BY totalEmployee DESC";
            }
            
            if($companies=='' && $cities==''){
                $sql = "SELECT functional_area, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` as nas $instSubQuery $subQueryFuncArea GROUP BY functional_area ORDER BY totalEmployee DESC";
            }

            $query      = $this->dbHandle->query($sql,array($instituteIds, $companies));
            $numOfRows  = $query->num_rows();
            $data       = $query->result_array();
            $result     = array();
            $i=0;
            $sumOfTopSixEmployees = 0;$totalEmp=0;
            if($numOfRows>0){
                foreach($data as $key=>$value){
                   // if($i<6){
                        $result[] = $value;
                        $sumOfTopSixEmployees = $sumOfTopSixEmployees+$value['totalEmployee'];
                        //$i++;                        
                    //}
                    $totalEmp = $totalEmp + $value['totalEmployee'];
                }
            }
            $result['totalEmp'] = $totalEmp;
            $cacheLib->store($cacheKey,$result,-1);
        }else{
            $result = $cacheLib->get($cacheKey);
        }
        return $result;
    }
    
    public function getCompaniesData($instituteIds, $jobFunc='', $cities=''){
        $this->initiateModel('read');
        $jobFuncSubQuery = '';$citiesSubQuery = '';
        if(!empty($instituteIds)){
            $instStr = implode(',',$instituteIds);
            $instSubQuery = " where nas.institute_id in (?)";
        }
        $cacheKey = md5('jobfnc_cities').md5($instStr).md5($jobFunc).md5($cities);
        $cacheLib = $this->load->library('cacheLib');
        if($cacheLib->get($cacheKey)=='ERROR_READING_CACHE'){
            $subQueryFuncArea = " and nas.functional_area in ('Sales / BD','Marketing / Advertising / MR / PR','HR / Administration / IR','Accounts / Finance / Tax / CS / Audit','Banking / Insurance','IT Software','ITES / BPO / KPO / Customer Service / Operations','Purchase / Logistics / Supply Chain','Corporate Planning / Consulting','Production / Maintenance / Quality','Analytics & Business Intelligence','Pharma / Biotech / Healthcare / Medical / R&D','Export / Import / Merchandising','Site Engineering / Project Management')";
            if($jobFunc!='' && $cities==''){
                $subQuery = ' and nas.functional_area=?';
                $sql = "SELECT comp_label, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` as nas $instSubQuery $subQuery GROUP BY comp_label ORDER BY totalEmployee DESC";
            }
            if($jobFunc=='' && $cities!=''){
                $cityId = $this->getCityId($cities);
                //$sql = "SELECT comp_label, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join institute_location_table ilt on (nas.institute_id = ilt.institute_id) join countryCityTable as cct on (ilt.city_id=cct.city_id) $instSubQuery and ilt.status='live' and ilt.city_id='".$cityId."' $subQueryFuncArea GROUP BY comp_label ORDER BY totalEmployee DESC";
                $sql = "SELECT comp_label, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join shiksha_institutes_locations sil on (nas.institute_id = sil.listing_id) join countryCityTable as cct on (sil.city_id=cct.city_id) $instSubQuery and sil.status='live' and sil.city_id='".$cityId."' $subQueryFuncArea GROUP BY comp_label ORDER BY totalEmployee DESC";
            }
            
            if($jobFunc!='' && $cities!=''){
                $cityId = $this->getCityId($cities);
                $subQuery = ' and nas.functional_area=?';
                //$sql = "SELECT comp_label, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join institute_location_table ilt on (nas.institute_id = ilt.institute_id) join countryCityTable as cct on (ilt.city_id=cct.city_id) $instSubQuery and ilt.status='live' and ilt.city_id='".$cityId."' $subQuery GROUP BY comp_label ORDER BY totalEmployee DESC";
                $sql = "SELECT comp_label, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join shiksha_institutes_locations sil on (nas.institute_id = sil.listing_id) join countryCityTable as cct on (sil.city_id=cct.city_id) $instSubQuery and sil.status='live' and sil.city_id='".$cityId."' $subQuery GROUP BY comp_label ORDER BY totalEmployee DESC";
            }
            
            if($jobFunc=='' && $cities==''){
                $sql = "SELECT comp_label, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` as nas $instSubQuery $subQueryFuncArea GROUP BY comp_label ORDER BY totalEmployee DESC";
            }

            $query      = $this->dbHandle->query($sql,array($instituteIds, $jobFunc));
            $numOfRows  = $query->num_rows();
            $data       = $query->result_array();
            $result     = array();
            $i=0;
            $sumOfTopSixEmployees = 0;
            if($numOfRows>0){
                foreach($data as $key=>$value){
                   // if($i<6){
                        $result[] = $value;
                        $sumOfTopSixEmployees = $sumOfTopSixEmployees+$value['totalEmployee'];
                     //   $i++;
                    //}
                    $totalEmp = $totalEmp + $value['totalEmployee'];
                }
            }
           // $totalEmp = $this->getTotalEmployee($instituteIds);
            $result['totalEmp'] = $totalEmp;
            $cacheLib->store($cacheKey,$result,-1);
        }else{
            $result = $cacheLib->get($cacheKey);
        }
        return $result;
    }

    public function getCitiesData($instituteIds, $jobFunc='', $companies=''){
        $this->initiateModel('read');
	$valueArr = '';
        if(!empty($instituteIds)){
            //$instStr = implode(',',$instituteIds);
            $instSubQuery = " and sil.listing_id in (?)";
	    $valueArr = $instituteIds;
        }
        $cacheKey = md5('jobfnc_comp').md5($instStr).md5($jobFunc).md5($companies);
        $cacheLib = $this->load->library('cacheLib');
        if($cacheLib->get($cacheKey)=='ERROR_READING_CACHE'){
            if($jobFunc!='' && $companies==''){
                $sql = "select distinct institute_id from `naukri_alumni_stats` nas where functional_area=?";
                $query      = $this->dbHandle->query($sql,array($jobFunc));
                $numOfRows  = $query->num_rows();
                $data       = $query->result_array();
                $jobFuncInstId = array();
                if($numOfRows>0){
                    foreach($data as $key=>$value){
                        $jobFuncInstId[] = $value['institute_id'];
                    }
                }
                $commonInstIds = array_intersect($instituteIds, $jobFuncInstId);
                $implodeInstIds = '';$subQuery = '';
                if(!empty($commonInstIds)){
                    //$implodeInstIds = implode(',',$commonInstIds);
                    $subQuery = 'and sil.listing_id in (?)';
		    $valueArr = $commonInstIds;
                }
                else{
                    $subQuery = $instSubQuery;
                }
                
                //$sql = "select cct.city_name,ilt.city_id,count(distinct ilt.institute_id) as totalInst,ilt.status from countryCityTable cct, (select city_id,`institute_id`,status from institute_location_table ilt where status = 'live' $subQuery) ilt where cct.`city_id` = ilt.`city_id` group by cct.city_id order by totalInst DESC";

                $sql = "select cct.city_name,sil.city_id,count(distinct sil.listing_id) as totalInst,sil.status from countryCityTable cct, (select city_id,`listing_id`,status from shiksha_institutes_locations sil where status = 'live' $subQuery) sil where cct.`city_id` = sil.`city_id` group by cct.city_id order by totalInst DESC";
            }
            if($jobFunc=='' && $companies!=''){
                $subQueryFuncArea = " and nas.functional_area in ('Sales / BD','Marketing / Advertising / MR / PR','HR / Administration / IR','Accounts / Finance / Tax / CS / Audit','Banking / Insurance','IT Software','ITES / BPO / KPO / Customer Service / Operations','Purchase / Logistics / Supply Chain','Corporate Planning / Consulting','Production / Maintenance / Quality','Analytics & Business Intelligence','Pharma / Biotech / Healthcare / Medical / R&D','Export / Import / Merchandising','Site Engineering / Project Management')";
                 $sql = "select distinct institute_id from `naukri_alumni_stats` nas where comp_label=? $subQueryFuncArea";
                $query      = $this->dbHandle->query($sql,array($companies));
                $numOfRows  = $query->num_rows();
                $data       = $query->result_array();
                $companiesInstId = array();
                if($numOfRows>0){
                    foreach($data as $key=>$value){
                        $companiesInstId[] = $value['institute_id'];
                    }
                }
                $commonInstIds = array_intersect($instituteIds, $companiesInstId);
                $implodeInstIds = implode(',',$commonInstIds);
                if(!empty($commonInstIds)){
                    //$implodeInstIds = implode(',',$commonInstIds);
                    $subQuery = 'and sil.listing_id in (?)';
		    $valueArr = $commonInstIds;
                }
                else{
                    $subQuery = $instSubQuery;
                }
                //$sql = "select cct.city_name,ilt.city_id,count(distinct ilt.institute_id) as totalInst,ilt.status from countryCityTable cct, (select city_id,`institute_id`,status from institute_location_table ilt where status = 'live' $subQuery) ilt where cct.`city_id` = ilt.`city_id` group by cct.city_id order by totalInst DESC";

                $sql = "select cct.city_name,sil.city_id,count(distinct sil.listing_id) as totalInst,sil.status from countryCityTable cct, (select city_id,`listing_id`,status from shiksha_institutes_locations sil where status = 'live' $subQuery) sil where cct.`city_id` = sil.`city_id` group by cct.city_id order by totalInst DESC";
            }
            if($jobFunc!='' && $companies!=''){
                $sql = "select distinct institute_id from `naukri_alumni_stats` nas where comp_label=? and functional_area=?";
                $query      = $this->dbHandle->query($sql,array($companies,$jobFunc));
                $numOfRows  = $query->num_rows();
                $data       = $query->result_array();
                $jobFuncCompaniesInstId = array();
                if($numOfRows>0){
                    foreach($data as $key=>$value){
                        $jobFuncCompaniesInstId[] = $value['institute_id'];
                    }
                }
                $commonInstIds = array_intersect($instituteIds, $jobFuncCompaniesInstId);
                $implodeInstIds = implode(',',$commonInstIds);
                if(!empty($commonInstIds)){
                    //$implodeInstIds = implode(',',$commonInstIds);
                    $subQuery = 'and sil.listing_id in (?)';
		    $valueArr = $commonInstIds;
                }
                else{
                    $subQuery = $instSubQuery;
                }
                //$sql = "select cct.city_name,ilt.city_id,count(distinct ilt.institute_id) as totalInst,ilt.status from countryCityTable cct, (select city_id,`institute_id`,status from institute_location_table ilt where status = 'live' $subQuery) ilt where cct.`city_id` = ilt.`city_id` group by cct.city_id order by totalInst DESC";

                $sql = "select cct.city_name,sil.city_id,count(distinct sil.listing_id) as totalInst,sil.status from countryCityTable cct, (select city_id,`listing_id`,status from shiksha_institutes_locations sil where status = 'live' $subQuery) sil where cct.`city_id` = sil.`city_id` group by cct.city_id order by totalInst DESC";
            }
            if($jobFunc=='' && $companies==''){
                //$sql = "select cct.city_name,ilt.city_id,count(distinct ilt.institute_id) as totalInst,ilt.status from countryCityTable cct, (select city_id,`institute_id`,status from institute_location_table ilt where status = 'live' $instSubQuery) ilt where cct.`city_id` = ilt.`city_id` group by cct.city_id order by totalInst DESC";
                $sql = "select cct.city_name,sil.city_id,count(distinct sil.listing_id) as totalInst,sil.status from countryCityTable cct, (select city_id,`listing_id`,status from shiksha_institutes_locations sil where status = 'live' $instSubQuery) sil where cct.`city_id` = sil.`city_id` group by cct.city_id order by totalInst DESC";
                //_p($sql);die;
            }
            $query      = $this->dbHandle->query($sql, array($valueArr));
            $numOfRows  = $query->num_rows();
            $data       = $query->result_array();
            $result     = array();
            $i=0;
            $sumOfTopSixInstitute = 0;$totalInstNum = 0;
            if($numOfRows>0){
                foreach($data as $key=>$value){
                   // if($i<6){
                        $result[] = $value;
                        $sumOfTopSixInstitute = $sumOfTopSixInstitute+$value['totalInst'];
                     //   $i++;
                    //}
                    $totalInstNum = $totalInstNum + $value['totalInst'];
                }
            }
            //$totalInst = count($instituteIds);
            $totalInst = $totalInstNum;
            $result['totalInst'] = $totalInst;
            $cacheLib->store($cacheKey,$result,-1);
        }else{
            $result = $cacheLib->get($cacheKey);
        }
        return $result;
    }
    
    public function defaultPageData(){
        $result = array();
        $instituteIds  = $this->getInstituteIds();
        $result['jobFuncData']   = $this->getJobFunctionsData($instituteIds);
        $result['companiesData'] = $this->getCompaniesData($instituteIds);
        $result['citiesData']    = $this->getCitiesData($instituteIds);
        //_p($result['citiesData']);die;
        return $result;
    }
    
    public function getDataForChart($jobFunc, $companies, $cities){
        $result = array();
        $instituteIds  = $this->getInstituteIds();
        //if($jobFunc==''){
            $result['jobFuncData']   = $this->getJobFunctionsData($instituteIds, $companies, $cities);
        //}
        //if($companies==''){
            $result['companiesData'] = $this->getCompaniesData($instituteIds, $jobFunc, $cities);
        //}
        //if($cities==''){
            $result['citiesData']    = $this->getCitiesData($instituteIds, $jobFunc, $companies);
        //}
        return $result;
    }
    
    function getInstitueRsult($param){
        $this->initiateModel('read');
        $AND ='';
        if($param['jobFunction'] !=''){
           $AND .= " AND nas.functional_area = ".$this->dbHandle->escape($param[jobFunction]);
        }
        if($param['company'] !=''){
           $AND .= " AND nas.comp_label = ".$this->dbHandle->escape($param[company]);
        }
        if($param['cityId'] !=''){
           $AND .= " AND sil.city_id = ".$this->dbHandle->escape($param[cityId]);
        }
        
        $pageStart = $param['pageStart'];
        $pageSize = $param['pageSize'];
//        $totalInstitute = implode(',',$this->getInstituteIds());
        $totalInstituteArr = $this->getInstituteIds();
        //1. If no filter, only use naukri salary data and order by institutes
        //2. In case either Company or Job function is selected and no city is selected
        //3. In case City is selected and no Job function and company is selected
        //4. In case City is selected and either Job function or company is selected
        if($param['jobFunction'] == '' && $param['company'] == '' && $param['cityId'] == ''){
            $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT nsd.institute_id, nsd.ctc50 FROM naukri_salary_data nsd where nsd.exp_bucket = '2-5' AND nsd.institute_id IN (?) $AND order by CAST(nsd.ctc50 AS DECIMAL(18,2)) desc limit $pageStart,$pageSize";
        }else if(($param['jobFunction'] !='' || $param['company'] !='') && $param['cityId'] ==''){
            $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT nas.institute_id, nsd.ctc50 FROM naukri_alumni_stats nas join naukri_salary_data nsd on (nas.institute_id = nsd.institute_id) where nsd.exp_bucket = '2-5' AND nas.institute_id IN (?) $AND order by CAST(nsd.ctc50 AS DECIMAL(18,2)) desc limit $pageStart,$pageSize";
        }else if($param['cityId'] !='' && $param['jobFunction'] =='' && $param['company'] ==''){
            //$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT ilt.institute_id, nsd.ctc50 FROM institute_location_table ilt join naukri_salary_data nsd on (ilt.institute_id = nsd.institute_id) where ilt.status='live' AND nsd.exp_bucket = '2-5' AND ilt.institute_id IN ($totalInstitute) $AND order by CAST(nsd.ctc50 AS DECIMAL(18,2)) desc limit $pageStart,$pageSize";

                $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT sil.listing_id institute_id, nsd.ctc50 FROM shiksha_institutes_locations sil join naukri_salary_data nsd on (sil.listing_id = nsd.institute_id) where sil.status='live' AND nsd.exp_bucket = '2-5' AND sil.listing_id IN (?) $AND order by CAST(nsd.ctc50 AS DECIMAL(18,2)) desc limit $pageStart,$pageSize";   
        }else if($param['cityId'] !='' && ($param['jobFunction'] !='' || $param['company'] !='')){
            //$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT ilt.institute_id, nsd.ctc50 FROM institute_location_table ilt join naukri_salary_data nsd on (ilt.institute_id = nsd.institute_id) join naukri_alumni_stats nas on (ilt.institute_id = nas.institute_id) where ilt.status='live' AND nsd.exp_bucket = '2-5' AND ilt.institute_id IN ($totalInstitute) $AND order by CAST(nsd.ctc50 AS DECIMAL(18,2)) desc limit $pageStart,$pageSize";
            $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT sil.listing_id institute_id, nsd.ctc50 FROM shiksha_institutes_locations sil join naukri_salary_data nsd on (sil.listing_id = nsd.institute_id) join naukri_alumni_stats nas on (sil.listing_id = nas.institute_id) where sil.status='live' AND nsd.exp_bucket = '2-5' AND sil.listing_id IN (?) $AND order by CAST(nsd.ctc50 AS DECIMAL(18,2)) desc limit $pageStart,$pageSize";
        }
       
        $query      = $this->dbHandle->query($sql, array($totalInstituteArr));
        $numOfRows  = $query->num_rows();
        $data       = $query->result_array();
        $result     = array();
        if($numOfRows>0){
            foreach($data as $key=>$value){
                $result['institute'][] = $value['institute_id'];
                $result['ctc50'][$value['institute_id']] = $value['ctc50'];
            }
        }
        
        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $this->dbHandle->query($queryCmdTotal);
        $queryResults = $queryTotal->result();
        $totalRows = $queryResults[0]->totalRows;
        $result["total"] = $totalRows;
        return $result;
    }
    
    public function getJobFunctionsListForOverlay($instituteIds, $companies='', $cities=''){
        $this->initiateModel('read');
        if(!empty($instituteIds)){
            $instStr = implode(',',$instituteIds);
            $instSubQuery = " where nas.institute_id in (?)";
        }
        $subQueryFuncArea = " and nas.functional_area in ('Sales / BD','Marketing / Advertising / MR / PR','HR / Administration / IR','Accounts / Finance / Tax / CS / Audit','Banking / Insurance','IT Software','ITES / BPO / KPO / Customer Service / Operations','Purchase / Logistics / Supply Chain','Corporate Planning / Consulting','Production / Maintenance / Quality','Analytics & Business Intelligence','Pharma / Biotech / Healthcare / Medical / R&D','Export / Import / Merchandising','Site Engineering / Project Management')";
        $cacheKey = md5('overlay_comp_cities'.$instStr.$companies.$cities);
        $cacheLib = $this->load->library('cacheLib');
        if($cacheLib->get($cacheKey)=='ERROR_READING_CACHE'){
            if($companies!='' && $cities==''){
                $companiesSubQuery = ' and nas.comp_label=?';
                $sql = "SELECT functional_area, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` as nas $instSubQuery $companiesSubQuery $subQueryFuncArea GROUP BY functional_area ORDER BY totalEmployee DESC";
            }
            if($companies=='' && $cities!=''){
                $cityId = $this->getCityId($cities);
                //$sql = "SELECT functional_area, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join institute_location_table ilt on (nas.institute_id = ilt.institute_id) join countryCityTable as cct on (ilt.city_id=cct.city_id) $instSubQuery and ilt.status='live' and ilt.city_id='".$cityId."' $subQueryFuncArea GROUP BY functional_area ORDER BY totalEmployee DESC";
                $sql = "SELECT functional_area, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join shiksha_institutes_locations sil on (nas.institute_id = sil.listing_id) join countryCityTable as cct on (sil.city_id=cct.city_id) $instSubQuery and sil.status='live' and sil.city_id='".$cityId."' $subQueryFuncArea GROUP BY functional_area ORDER BY totalEmployee DESC";
            }
            
            if($companies!='' && $cities!=''){
                $cityId = $this->getCityId($cities);
                $companiesSubQuery = ' and nas.comp_label=?';
                //$sql = "SELECT functional_area, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join institute_location_table ilt on (nas.institute_id = ilt.institute_id) join countryCityTable as cct on (ilt.city_id=cct.city_id) $instSubQuery and ilt.status='live' and ilt.city_id='".$cityId."' $companiesSubQuery $subQueryFuncArea GROUP BY functional_area ORDER BY totalEmployee DESC";
                $sql = "SELECT functional_area, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join shiksha_institutes_locations sil on (nas.institute_id = sil.listing_id) join countryCityTable as cct on (sil.city_id=cct.city_id) $instSubQuery and sil.status='live' and sil.city_id='".$cityId."' $companiesSubQuery $subQueryFuncArea GROUP BY functional_area ORDER BY totalEmployee DESC";
            }
            
            if($companies=='' && $cities==''){
                $sql = "SELECT functional_area, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` as nas $instSubQuery $subQueryFuncArea GROUP BY functional_area ORDER BY totalEmployee DESC";
            }
            $query      = $this->dbHandle->query($sql,array($instituteIds, $companies));
            $numOfRows  = $query->num_rows();
            $data       = $query->result_array();
            $cacheLib->store($cacheKey, $data, -1);
        }else{
            $data = $cacheLib->get($cacheKey);
        }
        return $data;
    }
    
    public function getCompaniesFunctionsListForOverlay($alphabet, $jobFunc='', $cities='', $instituteIds = ''){
        $this->initiateModel('read');
        if(empty($instituteIds))
            $instituteIds  = $this->getInstituteIds();
        $instSubQuery = '';
	$paramArr = array();
        if(!empty($instituteIds)){
            //$instStr = implode(',',$instituteIds);
            $instSubQuery = " and nas.institute_id in (?)";
	    array_push($paramArr,$instituteIds);
        }
        $subQueryFuncArea = " and nas.functional_area in ('Sales / BD','Marketing / Advertising / MR / PR','HR / Administration / IR','Accounts / Finance / Tax / CS / Audit','Banking / Insurance','IT Software','ITES / BPO / KPO / Customer Service / Operations','Purchase / Logistics / Supply Chain','Corporate Planning / Consulting','Production / Maintenance / Quality','Analytics & Business Intelligence','Pharma / Biotech / Healthcare / Medical / R&D','Export / Import / Merchandising','Site Engineering / Project Management')";
        $cacheLib = $this->load->library('cacheLib');
        $cacheKey = md5('overlay_jobfnc_cities'.$instStr.$jobFunc.$cities.$alphabet);
        if($cacheLib->get($cacheKey)=='ERROR_READING_CACHE'){
            if($jobFunc!='' && $cities==''){
                $subQuery = ' and nas.functional_area=? and nas.comp_label LIKE "'.mysql_escape_string($alphabet).'%"';
                $sql = "SELECT comp_label, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` as nas where 1=1 $instSubQuery $subQuery GROUP BY comp_label ORDER BY comp_label";
		array_push($paramArr, $jobFunc);
            }
            else if($jobFunc=='' && $cities!=''){
                $cityId = $this->getCityId($cities);
                $subQuery = ' and nas.comp_label LIKE "'.mysql_escape_string($alphabet).'%"';
                $sql = "SELECT comp_label, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join shiksha_institutes_locations sil on (nas.institute_id = sil.listing_id) join countryCityTable as cct on (sil.city_id=cct.city_id) $instSubQuery $subQuery and sil.status='live' and sil.city_id=? $subQueryFuncArea GROUP BY comp_label ORDER BY comp_label";
		array_push($paramArr, $cityId);
            }
            else if($jobFunc!='' && $cities!=''){
                $cityId = $this->getCityId($cities);
                $subQuery = ' and nas.functional_area=? and nas.comp_label LIKE "'.mysql_escape_string($alphabet).'%"';
                $sql = "SELECT comp_label, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` nas join shiksha_institutes_locations sil on (nas.institute_id = sil.listing_id) join countryCityTable as cct on (sil.city_id=cct.city_id) $instSubQuery and sil.status='live' and sil.city_id=? $subQuery GROUP BY comp_label ORDER BY comp_label";
		array_push($paramArr, $cityId);
		array_push($paramArr, $jobFunc);
            }
            else if($jobFunc=='' && $cities==''){
                $sql = 'SELECT comp_label, SUM(total_emp) AS totalEmployee FROM `naukri_alumni_stats` as nas where comp_label LIKE "'.mysql_escape_string($alphabet).'%" '.$instSubQuery.' '.$subQueryFuncArea.' GROUP BY comp_label ORDER BY comp_label';
            }
            
            $query = $this->dbHandle->query($sql,$paramArr);
            $data = $query->result_array();
            $cacheLib->store($cacheKey, $data, -1);
        }else{
            $data = $cacheLib->get($cacheKey);
        }
        return $data;
    }
    
    public function getLocationFunctionsListForOverlay($jobFunc, $companies){
        $this->initiateModel('read');
        $instituteIds  = $this->getInstituteIds();
        $instSubQuery = '';
	$valueArr = '';
        if(!empty($instituteIds)){
            //$instStr = implode(',',$instituteIds);
            $instSubQuery = " and sil.listing_id in (?) ";
	    $valueArr = $instituteIds;
        }
        $subQueryFuncArea = " and nas.functional_area in ('Sales / BD','Marketing / Advertising / MR / PR','HR / Administration / IR','Accounts / Finance / Tax / CS / Audit','Banking / Insurance','IT Software','ITES / BPO / KPO / Customer Service / Operations','Purchase / Logistics / Supply Chain','Corporate Planning / Consulting','Production / Maintenance / Quality','Analytics & Business Intelligence','Pharma / Biotech / Healthcare / Medical / R&D','Export / Import / Merchandising','Site Engineering / Project Management')";
        $cacheLib = $this->load->library('cacheLib');
        $cacheKey = md5('overlay_jobfnc_comp'.$instStr.$jobFunc.$companies);
        if($cacheLib->get($cacheKey)=='ERROR_READING_CACHE'){
            if($jobFunc!='' && $companies==''){
                $sql = "select distinct institute_id from `naukri_alumni_stats` nas where functional_area=?";
                $query      = $this->dbHandle->query($sql,array($jobFunc));
                $numOfRows  = $query->num_rows();
                $data       = $query->result_array();
                $jobFuncInstId = array();
                if($numOfRows>0){
                    foreach($data as $key=>$value){
                        $jobFuncInstId[] = $value['institute_id'];
                    }
                }
                $commonInstIds = array_intersect($instituteIds, $jobFuncInstId);
                if(!empty($commonInstIds)){
                    //$implodeInstIds = implode(',',$commonInstIds);
                    $subQuery = 'and sil.listing_id in (?)';
		    $valueArr = $commonInstIds;
                }
                else{
                    $subQuery = $instSubQuery;
                }
                $sql = "select cct.city_name,sil.city_id,count(distinct sil.listing_id) as totalInst,sil.status from countryCityTable cct, (select city_id,listing_id,status from shiksha_institutes_locations sil where status = 'live' $subQuery) sil where cct.`city_id` = sil.`city_id` group by cct.city_id order by totalInst DESC";
            }
            else if($jobFunc=='' && $companies!=''){
                $sql = "select distinct institute_id from `naukri_alumni_stats` nas where comp_label=? $subQueryFuncArea";
                $query      = $this->dbHandle->query($sql,array($companies));
                $numOfRows  = $query->num_rows();
                $data       = $query->result_array();
                $companiesInstId = array();
                if($numOfRows>0){
                    foreach($data as $key=>$value){
                        $companiesInstId[] = $value['institute_id'];
                    }
                }
                $commonInstIds = array_intersect($instituteIds, $companiesInstId);
                if(!empty($commonInstIds)){
                    //$implodeInstIds = implode(',',$commonInstIds);
                    $subQuery = 'and sil.listing_id in (?)';
		    $valueArr = $commonInstIds;
                }
                else{
                    $subQuery = $instSubQuery;
                }
                //$sql = "select cct.city_name,ilt.city_id,count(distinct ilt.institute_id) as totalInst,ilt.status from countryCityTable cct, (select city_id,`institute_id`,status from institute_location_table ilt where status = 'live' $subQuery) ilt where cct.`city_id` = ilt.`city_id` group by cct.city_id order by totalInst DESC";
                $sql = "select cct.city_name,sil.city_id,count(distinct sil.listing_id) as totalInst,sil.status from countryCityTable cct, (select city_id,`listing_id`,status from shiksha_institutes_locations sil where status = 'live' $subQuery) sil where cct.`city_id` = sil.`city_id` group by cct.city_id order by totalInst DESC";
            }
            else if($jobFunc!='' && $companies!=''){
                $sql = "select distinct institute_id from `naukri_alumni_stats` nas where comp_label=? and functional_area=?";
                $query      = $this->dbHandle->query($sql,array($companies,$jobFunc));
                $numOfRows  = $query->num_rows();
                $data       = $query->result_array();
                $jobFuncCompaniesInstId = array();
                if($numOfRows>0){
                    foreach($data as $key=>$value){
                        $jobFuncCompaniesInstId[] = $value['institute_id'];
                    }
                }
                $commonInstIds = array_intersect($instituteIds, $jobFuncCompaniesInstId);
                if(!empty($commonInstIds)){
                    //$implodeInstIds = implode(',',$commonInstIds);
                    $subQuery = 'and sil.listing_id in (?)';
		    $valueArr = $commonInstIds;
                }
                else{
                    $subQuery = $instSubQuery;
                }
                //$sql = "select cct.city_name,ilt.city_id,count(distinct ilt.institute_id) as totalInst,ilt.status from countryCityTable cct, (select city_id,`institute_id`,status from institute_location_table ilt where status = 'live' $subQuery) ilt where cct.`city_id` = ilt.`city_id` group by cct.city_id order by totalInst DESC";
                $sql = "select cct.city_name,sil.city_id,count(distinct sil.listing_id) as totalInst,sil.status from countryCityTable cct, (select city_id,`listing_id`,status from shiksha_institutes_locations sil where status = 'live' $subQuery) sil where cct.`city_id` = sil.`city_id` group by cct.city_id order by totalInst DESC";
            }
            else if($jobFunc=='' && $companies==''){
                $sql = "select cct.city_name,sil.city_id,count(distinct sil.listing_id) as totalInst,sil.status from countryCityTable cct, (select city_id,`listing_id`,status from shiksha_institutes_locations sil where status = 'live' $instSubQuery) sil where cct.`city_id` = sil.`city_id` group by cct.city_id order by totalInst DESC";
            }         

            $query = $this->dbHandle->query($sql, array($valueArr));
            $data = $query->result_array();
            $cacheLib->store($cacheKey, $data, -1);
        }else{
            $data = $cacheLib->get($cacheKey);
        }
        return $data;
    }
    
    function getCityIdByName($cityName)
    {
        $this->initiateModel('read');
        $sql = "SELECT city_id FROM `countryCityTable` where city_name = ?";
        $query = $this->dbHandle->query($sql,array($cityName));
        $data  = $query->result_array();
        return $data[0]['city_id'];
    }

	function getNaukriFunctionalSalaryData($instituteId)
    {
        $this->initiateModel('read');
        $query = "  SELECT * 
                    FROM 
                    naukri_functional_salary_data
                    WHERE institute_id = ? AND tot_emp >= 5 order by ctc50+0 desc LIMIT 6" ;

        $query      = $this->dbHandle->query($query,array($instituteId));
        $data       = $query->result_array();
        return $data;
    }
}

?>

<?php
exit;
include('/var/www/html/shiksha/system/application/controllers/sums/Sums_Common.php');

class Lms extends Sums_Common
{

    var $appId = 1;
    var $cacheLib;
    function init()
    {
        $this->load->helper(array('form', 'url','date','image'));
        $this->load->library('sums_manage_client');
        $this->load->library('lmsLib');
        $this->load->library('listing_client');
        $this->load->library('messageboardconfig');
        $this->load->library('cacheLib');
        $this->cacheLib = new cacheLib();
        $this->makeApcCountryMap();
        $this->makeApcCategoryNameMap();
    }

    function makeApcCountryMap(){
        $dbConfig = array( 'hostname'=>'localhost');
        $appId = 12;
        if($this->cacheLib->get("country_flag") != "1"){
            $dbConfig = array( 'hostname'=>'localhost');
            $this->messageboardconfig->getDbConfig($appID,$dbConfig);	
            $dbHandle = $this->load->database($dbConfig,TRUE);
            if($dbHandle == ''){
                log_message('error','adduser can not create db handle');
            }

            $queryCmd = 'select * from countryTable';
            log_message('debug', 'query cmd is ' . $queryCmd);
            $query = $dbHandle->query($queryCmd);
            $counter = 0;
            $msgArray = array();
            foreach ($query->result() as $row){
                $key = "country_".$row->countryId;
                $val = $row->name;
                $this->cacheLib->store($key,$val);
            }
            $this->cacheLib->store("country_flag","1");
        }
    }

    function makeApcCategoryNameMap(){
        $boardId = 1;
        //connect DB
        $appId = 1;
        if($this->cacheLib->get("catname_flag") != "1"){
            $dbConfig = array( 'hostname'=>'localhost');
            $this->messageboardconfig->getDbConfig($appID,$dbConfig);	
            $dbHandle = $this->load->database($dbConfig,TRUE);
            if($dbHandle == ''){
                log_message('error','adduser can not create db handle');
            }
            $boardIdArray = array();
            $boardIdString='';
            if($dbHandle == ''){
                log_message('error','getRecentEvent can not create db handle');
            }

            $queryCmd = ' SELECT t1.boardId AS lev1,t1.name as name1, t2.boardId as lev2, t2.name as name2, t3.boardId as lev3, t3.name as name3, t4.boardId as lev4, t4.name as name4 FROM categoryBoardTable AS t1 LEFT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId LEFT JOIN categoryBoardTable AS t3 ON t3.parentId = t2.boardId LEFT JOIN categoryBoardTable AS t4 ON t4.parentId = t3.boardId WHERE t1.boardId = ?';

            error_log_shiksha($queryCmd);
            $query = $dbHandle->query($queryCmd, array($boardId));
            $catArray = array();
            foreach ($query->result() as $row){
                $key = "catname_".$row->lev3;
                $val =$row->name2." / ".$row->name3 ;
                $this->cacheLib->store($key,$val);
            }
            foreach ($query->result() as $row){
                $key = "catname_".$row->lev2;
                $val = $row->name2 ;
                $this->cacheLib->store($key,$val);
            }

            $this->cacheLib->store("catname_flag","1");
        }
    }

    function login()
    {
        $this->init();
        $this->load->view('sums/login');
    }
	
	function responseViewer()
    {
        show_404();
		$this->init();
//        $data['sumsUserInfo'] = $this->sumsUserValidation();
		$this->load->view('lms/responseViewer');
    }
    
    function getLeadsByListingBE($appId = 1, $type = '',$typeId= '',$start=0, $num=10000)
    {
//        $parameters = $request->output_parameters();
        $this->init();
        $listing_type = $type;
        $listing_type_id = $typeId;
        $count=$num;
        //connect DB
        $dbConfig = array( 'hostname'=>'localhost');
        $this->messageboardconfig->getDbConfig($appId,$dbConfig);	
        $dbHandle = $this->load->database($dbConfig,TRUE);
        if($dbHandle == ''){
            log_message('error','adduser can not create db handle');
        }
        $optionalArgs = array();
        $addType = '';
        $addTypeId = '';
        if(is_numeric($listing_type_id)){
            $addTypeId = ' and tempLMSTable.listing_type_id = '.$listing_type_id.' ';
        }
        $listing_type = strtolower($listing_type);
        if($listing_type == 'course' || $listing_type == 'institute' || $listing_type == 'scholarship' || $listing_type == 'notification'){
            $addType = ' and tempLMSTable.listing_type = "'.$listing_type.'" ';
        }
        $queryCmd = "SELECT tempLMSTable.*, listing_title , tuser.displayname, tuser.email, tuser.mobile, tuser.city, (select CategoryId from tCourseSpecializationMapping, tUserPref where tCourseSpecializationMapping.SpecializationId=tUserPref.DesiredCourse and tUserPref.UserId = tuser.userid limit 1 ) as catOfInterest ,(select CountryId from tUserLocationPref where  tUserLocationPref.UserId = tuser.userid limit 1) as countryOfInterest from tuser, tempLMSTable , listings_main where  listings_main.listing_type = tempLMSTable.listing_type and listings_main.listing_type_id = tempLMSTable.listing_type_id and tuser.userid = tempLMSTable.userId and listings_main.status='live' and tempLMSTable.listing_subscription_type='paid' order by tempLMSTable.submit_date desc LIMIT ?, ?";
        error_log_shiksha("LMS".$queryCmd);
        $query = $dbHandle->query($queryCmd,array((int)$start,(int)$count));
        $crsToIns = array();
        $admToIns = array();
        $crsString = "-1";
        $admString = "-1";
        foreach ($query->result() as $row){
            switch($row->listing_type){
                case 'scholarship':
                case 'institute':
                    break;
                case 'course':
                    $crsString .=",".$row->listing_type_id;
                    break;
                case 'notification':
                    $admString .=",".$row->listing_type_id;
                    break;
            }
        }
        $queryCmdCrs = 'select course_id , institute_name from institute, institute_courses_mapping_table where institute_courses_mapping_table.course_id in ('.$crsString.') and institute.institute_id=institute_courses_mapping_table.institute_id';
        error_log_shiksha("LMS".$queryCmdCrs);
        $queryTemp = $dbHandle->query($queryCmdCrs);
        foreach ($queryTemp->result() as $rowTemp){
            $crsToIns[$rowTemp->course_id] = $rowTemp->institute_name; 
        }

        $queryCmdAdm = 'select admission_notification_id, institute_name from institute, institute_examinations_mapping_table where institute_examinations_mapping_table.admission_notification_id in ('.$admString.')  and institute.institute_id=institute_examinations_mapping_table.institute_id';
        error_log_shiksha("LMS".$queryCmdAdm);
        $queryTemp = $dbHandle->query($queryCmdAdm);
        foreach ($queryTemp->result() as $rowTemp){
            $admToIns[$rowTemp->admission_notification_id] = $rowTemp->institute_name; 
        }

        $msgArray = array();
        foreach ($query->result() as $row){
            $cityName = $row->city;
            if(is_numeric($row->city))
            {
                $cityName = $this->cacheLib->get("city_".$row->city);
            }
            $listing_title = $row->listing_title;
            $institute_name = ""; 
            switch($row->listing_type){
                case 'scholarship':
                case 'institute':
                    $listing_title = $row->listing_title;
                    $institute_name = "-"; 
                    break;
                case 'course':
                    $listing_title = $row->listing_title;
                    $institute_name = $crsToIns[$row->listing_type_id]; 
                    break;
                case 'notification':
                    $listing_title = $row->listing_title;
                    $institute_name = $admToIns[$row->listing_type_id]; 
                    break;
            }
            $countries = explode(',',$row->countryOfInterest);
            for($i = 0; $i<count($countries); $i++){
                if(is_numeric($countries[$i]))
                {
                    $countries[$i] =$this->cacheLib->get('country_'.$countries[$i]);
                }
            }
            $countriesOfInterest = implode(',',$countries);

            $countries = explode(',',$row->catOfInterest);
            for($i = 0; $i<count($countries); $i++){
                if(is_numeric($countries[$i]))
                {
                    $countries[$i] =$this->cacheLib->get('catname_'.$countries[$i]);
                }
            }
            $categoriesOfInterest = implode(',',$countries);


            $url = getSeoUrl($row->listing_type_id,$row->listing_type,$row->listing_title,$optionalArgs);
            array_push($msgArray,
                        array(
                            'displayName'=>$row->displayName,
                            'email'=>$row->email,
                            'contact_cell'=>$row->contact_cell,
                            'listing_title'=>$listing_title,
                            'institute_name'=>$institute_name,
                            'listing_type'=>$row->listing_type,
                            'listing_type_id'=>$row->listing_type_id,
                            'query'=>$row->message,
                            'url'=>$url,
                            'User city'=>$cityName,
                            'action'=>$row->action,
                            'categoriesOfInterest'=>$categoriesOfInterest,
                            'countriesOfInterest'=>$countriesOfInterest,
                            'submit_date'=>$row->submit_date
                            ));//close array_push
        }
//        echo "<pre>".print_r($msgArray);"</pre>";
        header("Content-type: text/x-csv");
        $filename =preg_replace('/[^A-Za-z0-9]/', '',"leads".$type.$typeId);	
        header("Content-Disposition: attachment; filename=".$filename.".csv");
        $leads = $msgArray;
        
        $csv = '';
        foreach ($leads as $lead){
            foreach ($lead as $key=>$val){
                $csv .= '"'.$key.'",'; 
            }
            $csv .= "\n"; 
            break;
        }

        foreach ($leads as $lead){
            foreach ($lead as $key=>$val){
                $csv .= '"'.$val.'",'; 
            }
            $csv .= "\n"; 
        }
        echo $csv;

 
    }

	function profile()
    {
//        $data['sumsUserInfo'] = $this->sumsUserValidation();
        $this->load->library(array('category_list_client','listing_client'));
        $cat_client = new Category_list_client();
        $categoryList = $cat_client->getCategoryTree($appId);
        foreach($categoryList as $temp)
        {
            $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
        }
        $data['completeCategoryTree'] = json_encode($categoryForLeftPanel);
        $ListingClientObj = new Listing_client();
        $data['countryList'] = $ListingClientObj->getCountries($appId);

        $this->load->view('sums/profile',$data);
    }

    function searchUser()
    {
        $this->init();
//        $data['sumsUserInfo'] = $this->sumsUserValidation();
        $this->load->view('sums/userSelect',$data);
    }

    function getUsersForQuotation()
    {
        error_log_shiksha("RECEIVED POST DATA: ".print_r($_POST,true));
        $this->init();
//        $data['sumsUserInfo'] = $this->sumsUserValidation();
        $request['email'] = $this->input->post('email',true);
        $request['displayname'] = $this->input->post('displayname',true);
        $request['collegeName'] = $this->input->post('collegename',true);
        $request['contactName'] = $this->input->post('contactName',true);
        $request['contactNumber'] = $this->input->post('contactNumber',true);
        $request['clientId'] = $this->input->post('clientId',true);
        $objSumsManage = new Sums_Manage_client();
        $response['users'] =  $objSumsManage->getUserForQuotation($this->appId,$request);
        $this->load->view('lms/usersForQuotation',$response);
    }

    function getLeadsForClient( $clientId = '1') {
        $appId = 1;
        $this->init();
        $LmsClientObj = new LmsLib();
        $leads = $LmsClientObj->getLeadsByClient($appId,$clientId,$start="0",$count="40");
		$data['results'] = $leads;
		$data['csvURL'] = '/lms/lms/getLeadsForClientCSV/'.$clientId;
		$this->load->view('lms/showLeadsListing',$data);

    }

 function getLeadsForClientCSV( $clientId = '1') {
        $appId = 1;
        $this->init();
        $LmsClientObj = new LmsLib();
        $leads = $LmsClientObj->getLeadsByClient($appId,$clientId,$start="0",$count="10000");
        header("Content-type: text/x-csv");
        //header("Content-type: text/csv");
        //header("Content-type: application/csv");
        $filename =preg_replace('/[^A-Za-z0-9]/', '',"leadsByClient".$clientId);	
        header("Content-Disposition: attachment; filename=".$filename.".csv");
        $csv = '';
        foreach ($leads as $lead){
            foreach ($lead as $key=>$val){
                $csv .= '"'.$key.'",'; 
            }
            $csv .= "\n"; 
            break;
        }

        foreach ($leads as $lead){
            foreach ($lead as $key=>$val){
                $csv .= '"'.$val.'",'; 
            }
            $csv .= "\n"; 
        }
        echo $csv;

    }


    function getLeadsByListingCSV( $type = '',$typeId= '',$start=0, $num=1000) {
        $appId = 1;
        $this->init();
        $LmsClientObj = new LmsLib();
        $leads = $LmsClientObj->getLeadsByListing($appId,$type,$typeId,$start,$num);
//                echo "<pre>"; print_r($leads); echo "</pre>";
        header("Content-type: text/x-csv");
        //header("Content-type: text/csv");
        //header("Content-type: application/csv");
        $filename =preg_replace('/[^A-Za-z0-9]/', '',"leads".$type.$typeId);	
        header("Content-Disposition: attachment; filename=".$filename.".csv");
        $csv = '';
        foreach ($leads as $lead){
            foreach ($lead as $key=>$val){
                $csv .= '"'.$key.'",'; 
            }
            $csv .= "\n"; 
            break;
        }

        foreach ($leads as $lead){
            foreach ($lead as $key=>$val){
                $csv .= '"'.$val.'",'; 
            }
            $csv .= "\n"; 
        }
        echo $csv;
    }

    function getLeadsByListing( $type = '',$typeId= '') {
        $appId = 1;
        $this->init();
        $LmsClientObj = new LmsLib();
        $leads = $LmsClientObj->getLeadsByListing($appId,$type,$typeId,$start="0",$count="40");
		$data['results'] = $leads;
		$data['csvURL'] = '/lms/lms/getLeadsByListingCSV/'.$type.'/'.$typeId;
		$this->load->view('lms/showLeadsListing',$data);
    }


    function getListings($clientId = '1') {
        $appId = 1;
        $this->init();
        $LmsClientObj = new Listing_client();
        $leads = $LmsClientObj->getListingsByClient($appId,$clientId,$start="0",$count="40");
		$data['results'] = $leads;
		$data['clientId'] = $clientId;
		$this->load->view('lms/showLeadSelectBox',$data);
	}


    function getProducts()
    {
        error_log_shiksha("posted data for Get Products: ".print_r($_POST,true));
        $this->init();
        $this->load->library(array('register_client','sums_product_client'));
//        $data['sumsUserInfo'] = $this->sumsUserValidation();
        $regObj = new Register_client();
        $ud = $regObj->userdetail($this->appId,$this->input->post('selectedUserId',true));
        $data['selectedUserDetails'] = $ud[0];
        $data['selectedUserDetails']['userId'] =$this->input->post('selectedUserId',true);
        $objSumsProduct = new Sums_Product_client();
        $data['products'] = $objSumsProduct->getDerivedProducts();
        $this->load->view('sums/productSelect',$data);
    }

}
?>

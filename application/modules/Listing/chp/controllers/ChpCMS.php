<?php
/**
 * Purpose : Controller for CHP CMS related functionalitie (National)
 * Author  : Akhter
 **/
class ChpCMS extends MX_Controller
{
    private function _init()
    {
        $this->validateuser = $this->checkUserValidation();
        $this->cmsUserInfo = $this->cmsUserValidation();

        if($this->cmsUserInfo['usergroup']!='cms'){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
        }
    }

    private function _initCHP() {
        // prepare the header components
        $headerComponents = array(
                        'css' => array('headerCms', 'footer','common_new', 'exampages_cms','cmsForms'),
                        'js' => array('user','common','header','CalendarPopup','cmsChpContent'),
                        'jsFooter' =>'',
                        'title' => "",
                        'product' => '',
                        'displayname' => (isset($this->validateuser[0]['displayname']) ? $this->validateuser[0]['displayname'] : ""),
                        'isOldTinyMceNotRequired' => 1
                        );
        // tab to be selected
        $this->cmsUserInfo['prodId'] =  1045;
        
        // render the view
        echo $this->load->view('enterprise/headerCMS', $headerComponents,true);
        echo $this->load->view('common/calendardiv');
        echo $this->load->view('enterprise/cmsTabs', $this->cmsUserInfo, true);
        $this->ChpClient = $this->load->library('ChpClient');
    }

     public function viewList(){
        // initialize
        $this->_init();
        $this->_initCHP();
        $displayData = array();

        $this->load->config("chpAPIs");
        $getAllChpUrl = $this->config->item('CHP_LIST');
        $result = $this->ChpClient->makeCURLCall('GET',$getAllChpUrl);
        $result = json_decode($result,true);
        $displayData['list'] = $result['data'];
        $displayData['tabName'] = 'viewAllCHP';
        $this->load->view('chp/enterprise/mainCHP', $displayData);
    }


    public function addCHP(){
        // initialize
        $this->_init();
        $this->_initCHP();
        $displayData = array();
        $displayData['chpType'] = $this->input->get('chpType',true);
        $displayData['tabName'] = 'addCHP';
        $this->load->view('chp/enterprise/mainCHP',$displayData);
    }

    function prepareCHPHierarchyData(){
        $this->_init();
    
        $streamId     = $this->input->post('stream',true);
        $subStreamId  = $this->input->post('subStream',true);
        $specId       = $this->input->post('spec',true);
        $baseCourseId = $this->input->post('baseCourse',true);

        $this->load->config("chpAPIs");
        $hierarchyURL = $this->config->item('CHP_GET_HIERARCHY');
        $baseCourseUrl = $this->config->item('CHP_BASECOURSE');
        
        $this->ChpClient = $this->load->library('ChpClient');
        $result = $this->ChpClient->makeCURLCall('GET',$hierarchyURL);
        $result = json_decode($result,true);
        $result = $result['data'];

        
        $streamHtml = '';$subStreamHtml='';$specHtml='';
        $subStreamName = 'any';$specName = 'any';
        
        //default stream list
        foreach ($result['hierarchyData'] as $key => $value) {
            if($value['name'] == 'Sarkari Exams'){
                continue;
            }
            $streamHtml .='<option value='.$value['id'].'>'.$value['name'].'</option>';
        }

        if($streamId){
            foreach ($result['hierarchyData'] as $key => $value) {
                if($streamId == $value['id']){
                    $hierarchyData = $value;
                    break;
                }
            }    
        }else{
            $hierarchyData = $result['hierarchyData'];
        }
        
        if($streamId){
            //stream=>subStream
            foreach ($hierarchyData['substreams'] as $key => $subStream) {
                $subStreamHtml .='<option value='.$subStream['id'].'>'.$subStream['name'].'</option>';
            }
            
            if(empty($subStreamId)){
                //stream=>specialization
                foreach ($hierarchyData['specializations'] as $key => $spec) {
                    $specHtml .='<option value='.$spec['id'].'>'.$spec['name'].'</option>';
                }    
            }
        }

        if($streamId && $subStreamId){
            $specHtml = '';
            $subStreamName = $hierarchyData['substreams'][$subStreamId]['name'];
            if($specId && $hierarchyData['substreams'][$subStreamId]['specializations'][$specId]){
                $specName  = $hierarchyData['substreams'][$subStreamId]['specializations'][$specId]['name'];
            }
            $subStrSpec    = $hierarchyData['substreams'][$subStreamId];
            //stream=>subStream=>specialization
            foreach ($subStrSpec['specializations'] as $key => $spec) {
                $specHtml .='<option value='.$spec['id'].'>'.$spec['name'].'</option>';
            }
        }

        if(($streamId && $specId) && $hierarchyData['specializations'][$specId]){
            $specName  = $hierarchyData['specializations'][$specId]['name'];
        }

        $data = array();
        $tmpCrendetial = array();
        $defaultOption = "<option value=''>Select</option>";
    
        $data['stream'] = $defaultOption.$streamHtml;
        $data['subStream'] = $defaultOption.$subStreamHtml;
        $data['spec'] = $defaultOption.$specHtml;

        if($streamId){
            $payload = array('streamId'=>$streamId,'substreamId'=>$subStreamName,'specializationId'=>$specName);
            $payload = '['.json_encode($payload).']';
            $bcResult = $this->ChpClient->makeCURLCall('POST',$baseCourseUrl,$payload);
            $bcResult = json_decode($bcResult,true);
            $baseCoursesList = $bcResult['data']['baseCourses'];
            $credentialList  = $bcResult['data']['credentials'];

            foreach ($baseCoursesList as $credentialId => $value) {
                $bcHtml .='<option value='.$value['baseCourseId'].'>'.$value['name'].'</option>';
                foreach ($value['credential'] as $key => $crId) {
                    $credential[$credentialId][] = $crId;
                }        
            }
            
            if($baseCourseId){
                $tmpCrendetial = array_unique($credential[$baseCourseId]);
            }
            
            foreach ($credentialList as $key => $cr) {
                if($cr['valueName'] == 'None'){
                    continue;
                }
                if(count($tmpCrendetial)>0 && in_array($cr['valueId'], $tmpCrendetial)){
                    $credentialHtml .='<option value='.$cr['valueId'].'>'.$cr['valueName'].'</option>';
                }else if(empty($tmpCrendetial)){
                    $credentialHtml .='<option value='.$cr['valueId'].'>'.$cr['valueName'].'</option>';
                }
            }
            unset($credential,$tmpCrendetial);

            $data['credential'] = $defaultOption.$credentialHtml;
            $data['baseCourse'] = $defaultOption.$bcHtml;
        }

        $mode = $result['deliveryMethod'];
        foreach ($mode as $key => $value) {
            $modeHtml .='<option value='.$value['valueId'].'>'.$value['valueName'].'</option>';
        }

        $educationType = $result['educationType'];
        foreach ($educationType as $key => $value) {
            $eduHtml .='<option value='.$value['valueId'].'>'.$value['valueName'].'</option>';
        }

        $data['deliveryMethod'] = $defaultOption.$modeHtml;
        $data['educationType'] = $defaultOption.$eduHtml;
        echo json_encode($data);
    }

    public function editCHP($chpId){
         // initialize
        if(empty($chpId) || !is_numeric($chpId)){
            echo 'Invalid CHP ID.';exit();
        }
        $this->_init();
        $this->_initCHP();
        $displayData = array();

        $this->load->config("chpAPIs");
        $apiUrl = $this->config->item('CHP_GET_WIKICONTENT');
        $result = $this->ChpClient->makeCURLCall('GET',$apiUrl,array('chpId'=>$chpId));
        $result = json_decode($result,true);
        $displayData['content'] = $result['data'];
    
        foreach ($displayData['content']['sectionOrder'] as $key => $value) {
            $temp['name'] = str_replace(' ', '', $value);
            $temp['position'] = $key+1;
            $sectionOrder[] = $temp;
        }

        $secionMapping = array('homePage'=>'HomePage','popularColleges'=>'Popular Colleges','topRateCourses'=>'Top Rate Courses','popularUGCourses'=>'Popular UG Courses','popularPGCourses'=>'Popular PG Courses','popularSpecialization'=>'Popular Specialization','topCollegesByLocation'=>'Top Colleges By Location','popularExams'=>'Popular Exams','salary'=>'Salary');

        $displayData['secionMapping'] = $secionMapping;
        $displayData['sectionOrder']  = $sectionOrder;

        $displayData['tabName'] = 'editCHP';
        
        // lock cms editor info
        $lockRes = '';
        $chpCache = $this->load->library('chp/cache/ChpCache');
        $result = $chpCache->getCHPCMSUserLockingInfo($chpId);
        if(!empty($result) && !empty($result['userId']) && $result['userId'] != $this->validateuser[0]['userid']){
            $errorMsg = 'This CHP Content has been locked by '.$result['userName'];
        }else{
            $cacheData['userId']   = $this->validateuser[0]['userid'];
            $cacheData['userName'] = $this->validateuser[0]['displayname'];
            $chpCache->setCHPCMSUserLockingInfo($chpId, $cacheData);
            unset($cacheData);    
        }
        $displayData['errorMsg'] = $errorMsg;
        $displayData['chpId'] = $chpId;
        $this->load->view('chp/enterprise/mainCHP',$displayData);
    }

    function saveContent(){
        $this->_init();
        $data['userId'] = $this->cmsUserInfo['userid'];
        $formData = json_decode($this->input->post("data",true),true);

         // Check lock cms editor info
        $chpCache = $this->load->library('chp/cache/ChpCache');
        $result = $chpCache->getCHPCMSUserLockingInfo($formData['chpId']);
        if(!empty($result) && !empty($result['userId']) && $result['userId'] != $data['userId']){
            $response = array('error' => array('msg' => 'This CHP Content has been locked by '.$result['userName']));
            echo json_encode($response);exit;
        }

        //insert into html cache purging queue
        if(!empty($formData['chpId'])){
            $arr = array("cache_type" => "htmlpage", "entity_type" => "chp", "entity_id" => $formData['chpId'], "cache_key_identifier" => "");
            $shikshamodel = $this->load->model("common/shikshamodel");
            $shikshamodel->insertCachePurgingQueue($arr);
        }

        foreach ($formData['sectionOrder'] as $key => $value) {
            $temp[$value['name']] = $value['position'];
        }

        $formData['sectionOrder'] = $temp;
        unset($temp);

        $formData['userId'] = $this->validateuser[0]['userid'];
        $this->load->config("chpAPIs");
        $apiUrl = $this->config->item('CHP_SAVE_WIKICONTENT');
        $this->ChpClient = $this->load->library('ChpClient');

        $result = $this->ChpClient->makeCURLCall('POST',$apiUrl, json_encode($formData));
        $result = json_decode($result,true);
        
        if($result['status']){
            $this->unlockCHPContent($formData['chpId']);
        }

        echo json_encode($result['data']);
    }

    function submitCHpData(){
        $this->_init();
        
        $formFieldInfo = json_decode($this->input->post("data",true),true);

        $data['chpType']     = $formFieldInfo['type'];
        $data['streamId']    = $formFieldInfo['streamId'];
        $data['substreamId'] = $formFieldInfo['subStream'];
        $data['specializationId'] = $formFieldInfo['spec'];
        $data['basecourseId']  = $formFieldInfo['baseCourse'];
        $data['educationtypeId'] = $formFieldInfo['eduType'];
        $data['deliverymethodId'] = $formFieldInfo['mode'];
        $data['credentialId'] = $formFieldInfo['credential'];
        $data['entityX'] = ($formFieldInfo['x_value'] == 'spec') ? 'specialization' : strtolower($formFieldInfo['x_value']);
        $data['entityY'] = ($formFieldInfo['y_value'] == 'spec') ? 'specialization' : strtolower($formFieldInfo['y_value']);
        $data['name']        = $formFieldInfo['chpName'];
        $data['displayName'] = $formFieldInfo['displayName'];
        $data['userId'] = $this->validateuser[0]['userid'];

        $this->load->config("chpAPIs");
        $submitDataApi = $this->config->item('CHP_SAVECHP');
        $this->ChpClient = $this->load->library('ChpClient');
        $result = $this->ChpClient->makeCURLCall('POST',$submitDataApi,json_encode($data));
        $result = json_decode($result,true);
        echo json_encode($result['data']);exit();
    }

    public function manageSeoCHP(){
        // initialize
        $this->_init();
        $this->_initCHP();
        $displayData = array();
        $displayData['tabName'] = 'seoCHP';
        $this->load->view('chp/enterprise/mainCHP',$displayData);
    }

    function searchChp(){
        // initialize
        $this->_init();
        $input = $this->input->post('input',true);
    
        $this->load->config("chpAPIs");
        if(!empty($input) && is_numeric($input)){
            $url = $this->config->item('CHP_SEODATA'); 
            $data['chpId'] = $input;
        }else{
            $url = $this->config->item('CHP_SEARCH_BY_NAME');
            $data['str'] = $input;
        }

        $this->ChpClient = $this->load->library('ChpClient');
        $result = $this->ChpClient->makeCURLCall('GET', $url, $data);
        $result = json_decode($result,true);
        echo json_encode($result['data']);
    }

    function saveSeoDetails(){
        $this->_init();
        $data['chpId'] = $this->input->post('chpId');
        $data['title'] = $this->input->post('title');
        $data['description'] = $this->input->post('description');
        $data['userId'] = $this->validateuser[0]['userid'];

        //insert into html cache purging queue
        if(!empty($data['chpId'])){
            $arr = array("cache_type" => "htmlpage", "entity_type" => "chp", "entity_id" => $data['chpId'], "cache_key_identifier" => "");
            $shikshamodel = $this->load->model("common/shikshamodel");
            $shikshamodel->insertCachePurgingQueue($arr);
        }

        $this->load->config("chpAPIs");
        $url = $this->config->item('CHP_SAVE_SEODATA'); 
        $this->ChpClient = $this->load->library('ChpClient');
        $result = $this->ChpClient->makeCURLCall('POST', $url, json_encode($data));
        $result = json_decode($result,true);
        echo json_encode($result['data']);
    }

    function uploadHeaderImage()
    {
        $response['data'] = array('error' => array('msg' => 'Unable to upload file due to incorrect data'));
        if($_FILES['uploads']) {
            $response = array();
            $response = $this->prepareUploadData($_FILES['uploads']);
            $finalResponse = $response;
            if(!is_array($response) && $response != ""){
                $finalResponse = array();
                $finalResponse['file_url'] = addingDomainNameToUrl(array('url' => $response,'domainName' => MEDIA_SERVER));
                $finalResponse['file_relative_url'] = $response;
                $finalResponse['file_name'] = $_FILES['uploads']['name'][0];
                $finalResponse['file_size'] = $_FILES['uploads']['size'][0];
            }
            $response = array('data' => $finalResponse);
            
        }
        echo json_encode($response);
    }

    /*below funciton is used for uploading files from exam content*/
    function prepareUploadData($uploadArrData)
    {
        // check if files has been uploaded
        if(!empty($uploadArrData['tmp_name'][0])) {
            $return_response_array = array();
            $this->_initUploadClient();
            // get file data and type check
            $type_doc = $uploadArrData['type']['0'];
            $type_doc = explode("/", $type_doc);
            $type_doc = $type_doc['0'];
            $type = explode(".",$uploadArrData['name'][0]);
            $type = strtolower($type[count($type)-1]);
            // display error if type doesn't match with the required file types
            if(!in_array($type, array('png','jpeg','jpg'))) {
                $return_response_array['error']['msg'] = "Only Image of type .jpeg, .jpg and .png allowed";
                return $return_response_array;
            }
            // all well, upload now
                $this->load->library('upload_client');
                $this->UploadClient = new Upload_client();
                $upload_array = $this->UploadClient->uploadFile($appId,'image',array('chp_headerImage' => $uploadArrData),array(),"-1","CHP",'chp_headerImage');
            // check the response from upload library
            if(is_array($upload_array) && $upload_array['status'] == 1) {
                $return_response_array = $upload_array[0]['imageurl'];
            }
            else {
                if($upload_array == 'Size limit of 25 Mb exceeded') {
                    $upload_array = "Please upload a file less than 25 MB in size"; 
                }
                $return_response_array['error']['msg'] = $upload_array;
            }
            return $return_response_array;
        }
        else {
            return "";
        }
    }

    function unlockCHPContent($chpId){
        if(!empty($_GET['chpId'])){
            $this->_init();
        }
        $chpId = !empty($_GET['chpId']) ? $this->input->get('chpId') : $chpId;
        $chpId = !empty($_POST['chpId']) ? $this->input->post('chpId') : $chpId;
        if(empty($chpId) || !is_numeric($chpId)){
            return;
        }
        $chpCache = $this->load->library('chp/cache/ChpCache');
        $chpCache->deleteCHPCMSUserLockingInfo($chpId);
    }
}
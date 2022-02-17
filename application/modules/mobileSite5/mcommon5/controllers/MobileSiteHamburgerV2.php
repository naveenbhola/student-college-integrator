<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("LAYER1CLASS", "cat-head");
define("LAYER2CLASS", "subcat-a");
define("LAYER3CLASS", "subchild-l3");
define("LAYER4CLASS", "spec-l4 subchild-l3");
define("THRESHOLD_LIMIT", 2);

//Desc - this is used for hamburger only, to personalize hamburger data
//Version - V3
//@modified by - akhter 

class MobileSiteHamburgerV2 extends ShikshaMobileWebSite_Controller {
	function __construct() {
        parent::__construct();
    }

    function init(){
        $this->load->config('mcommon5/hamburger_config');
        $this->menuLayer1Groups = $this->config->item('menuLayer1Groups');
        $this->menuLayer1Subtitles = $this->config->item('menuLayer1Subtitles');
        $this->streamWiseMenuIds = $this->config->item('streamWiseMenuIds');
        
        global $tabsContentByCategory;
        $hamObj = $this->load->library('mcommon5/HamburgerLib');
        $this->tabsContentByCategory = $hamObj->getTabsContentByStream();

        $this->selectedStreamId = $this->input->cookie('user_selected_stream');
        if(empty($this->selectedStreamId)){
            $this->selectedStreamId = 0; // stands for all
            $this->selectedStreamName = '';
        } else {
            $this->selectedStreamName = $this->tabsContentByCategory[$this->selectedStreamId]['name'];
        }

        $this->userStatus = $this->checkUserValidation();
        $this->layerToShow = $this->input->post('layerToShow');
        if(empty($this->layerToShow)){
            $this->layerToShow = 'MHMenu';
        }

        if($this->layerToShow == 'fcl3' && $this->selectedStreamId == 0){
            $this->layerToShow = 'fcl2';
        }
    }

    function getWrapperHtmlForHamburger(){
        $this->load->view('mcommon5/hamburgerCustomize');
    }

    function getWrapperHtmlForHamburgerDummy(){
        $this->load->view('mcommon5/hamburgerCustomize_jq');
    }

  /**
    *Desc    : Prepare hamburger
    *@uthor  : akhter
    *Version : V3
    *return  : html
    **/
    function getCompleteHamburger($showProfileData = 1) {
        $this->init();
        $mobileHamburger = "HomePageRedesignCache/mobileHamburger.html";
        if(file_exists($mobileHamburger) && (time() - filemtime($mobileHamburger))<=(1*24*60*60) && $this->selectedStreamId == 0){
            echo file_get_contents($mobileHamburger);
        }else{
            ob_start();
            $displayData['profileData'] = $this->getProfileData();
            //get selected stream from cookie
            $displayData['selectedStreamId'] = $this->selectedStreamId;
            //get menu links
            $displayData['tabsContentByCategory'] = $this->tabsContentByCategory;
            $displayData['menuContent'] = $this->getHamburgerMenu();
            $displayData['showProfileData'] = $showProfileData;
            $displayData['dropDownText'] = (!empty($this->tabsContentByCategory[$this->selectedStreamId]['name'])) ? $this->tabsContentByCategory[$this->selectedStreamId]['name'] : 'Select Stream';
            $viewFile = 'mcommon5/hamburgerV2/hamburgerContent';
            $layerWithoutProfileHtml = $this->load->view($viewFile, $displayData, true);
            echo sanitize_output($layerWithoutProfileHtml);

            if($this->selectedStreamId == 0){ 
                $pageContent = ob_get_contents();
                ob_end_clean();
                echo $pageContent;
                $fp=fopen($mobileHamburger,'w+');
                flock( $fp, LOCK_EX ); // exclusive lock
                fputs($fp,$pageContent);
                flock( $fp, LOCK_UN ); // release the lock
                fclose($fp);    
            }
        }
    }


    public function getProfileData() {
    	
        if(isset($_GET['from']) && $_GET['from'] == 'pwa'){
            $this->init();
        }

        $data['userStatus'] = $this->userStatus;
        $data['displayname'] = 'Guest';
        if($this->userStatus != 'false'){
            if($this->userStatus[0]['firstname']!=''){
                $data['displayname'] = ucwords($this->userStatus[0]['firstname']);
            }
            else{
                $data['displayname'] = ucwords($this->userStatus[0]['displayname']);
            }
            $data['avtarurl']    = $this->userStatus[0]['avtarurl'];
            $data['profileUrl']  = $this->config->item('profileUrl');
        }
        
        if(isset($_GET['from']) && $_GET['from'] == 'pwa'){
            
            $this->insertUserSessionInfo($this->userStatus);

            $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
            header("Access-Control-Allow-Origin: ".$requestHeader);
            header("Access-Control-Allow-Credentials: true");
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
            header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($data);return;
        }
        return $data;
    }

    function insertUserSessionInfo($validate){
        $sessionId = sessionId();
        $clientIP = getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):(getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):getenv("REMOTE_ADDR"));
        $userId = ($validate != false) ? $validate[0]['userid'] : 0;

        // creating Data for inserting in redis
        // author : pulkit
        $redis_key = 'user_session_info';
        $post_data['sessionId']= $sessionId;
        $post_data['userId']= $userId;
        $post_data['clientIP']= $clientIP;

        if ($userId < 0 || !is_numeric($userId) ){
            $post_data['userId'] = null;
        }
        $redis_client = PredisLibrary::getInstance();
        $redis_client->addMembersToSet($redis_key, array(json_encode($post_data)));
        // this code is commented to move the insert query to redis
        //$modelObj = $this->load->model('homepagemodel');
        //$modelObj->insertUserSessionInfo($sessionId, $userId, $clientIP);
    }

    public function getHamburgerMenu() {
    	$data = array();
    	$allowedMenu = $this->streamWiseMenuIds[$this->selectedStreamId];
    	foreach($allowedMenu as $key => $menuId) {
    		$groupId = $this->menuLayer1Subtitles[$menuId]['group'];
    		$groupName = $this->menuLayer1Groups[$groupId];
            
        	$menuData = '';
            if(!empty($this->menuLayer1Subtitles[$menuId]['apiCall'])) {
                $funcName = $this->menuLayer1Subtitles[$menuId]['apiCall'];
                if(method_exists($this, $funcName)){
				    $menuData = $this->$funcName($menuId);
    				if(!empty($menuData) && !empty($menuData['layer1'])) {
                        $data['layer1'][$groupName][$menuId] = $menuData['layer1'];
                        $data['layer2'][$menuId] = $menuData['layer2'];
                        $data['layer3'][$menuId] = $menuData['layer3'];
                        $data['layer4'][$menuId] = $menuData['layer4'];
                    }
                }
            }
        }
        
        return $data;
    }

    // Desc : Find College By Specialization Tab
    public function getFindCollegesHtml($menuId){
        $data['layerToShow'] = $this->layerToShow;
        $data['layerHeading'] = $this->menuLayer1Subtitles[$menuId]['name'];
        if($this->_isGeneric()){
            $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
            $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'data-rel'=>'fcl2', 'gaTrackParam'=>$gaTrackParam));

            foreach($this->tabsContentByCategory as $streamArr){
                $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$streamArr['name'];
                if($streamArr['name'] == 'Sarkari Exams' || $streamArr['id'] == 21){
                    $data['links'][] = $this->generateMenuLinkHtml(array('data'=>$streamArr['name'],'class'=>LAYER2CLASS,'catId' => $streamArr['id'],'type' => 'fc', 'gaTrackParam'=>$gaTrackParam,'link'=> SHIKSHA_HOME.'/government-exams/exams-st-21'));
                }else{
                    $data['links'][] = $this->generateMenuLinkHtml(array('data'=>$streamArr['name'],'class'=>LAYER2CLASS,'catId' => $streamArr['id'],'type' => 'fc','data-rel'=>'fcl3', 'gaTrackParam'=>$gaTrackParam));
                }
            }

            $data['id'] = 'fcl2';
            $html['layer2'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer2Menu',$data,true);

            $data['links'] = array();
            $linksLayer4 = array();
            
            // stream=>substream
            foreach($this->tabsContentByCategory as $streamArr){

                $allLinkData = array('type' => 'fc','catId' => $streamArr['id'],'data' => 'All '.$streamArr['name'].' Colleges','class' => LAYER3CLASS, 'gaTrackParam'=>$gaTrackParam);

                foreach($streamArr['substreams'] as $subStreamArr){
                    $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$streamArr['name'].' : '.$subStreamArr['name'];
                    $l2data = array('type' => 'fc','catId' => $streamArr['id'],'subCatId' => $subStreamArr['id'],'data' => $subStreamArr['name'],'class' => LAYER3CLASS, 'gaTrackParam'=>$gaTrackParam);
                    if(!empty($subStreamArr['specializations'])){
                        $l2data['data-rel'] = 'fcl4';
                    }

                    $data['links'][] = $this->generateMenuLinkHtml($l2data);
                    // stream=>substream=>specialization
                    $allSub = array('type' => 'fc','catId' => $streamArr['id'],'subCatId' => $subStreamArr['id'],'data' => 'All '.$subStreamArr['name'].' Colleges','class' => LAYER3CLASS, 'gaTrackParam'=>$gaTrackParam);
                    foreach($subStreamArr['specializations'] as $subSpecArr){
                        $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$subSpecArr['name'].' : '.$subSpecArr['name'];
                        $linksLayer4[] = $this->generateMenuLinkHtml(array('type' => 'fc','catId' => $streamArr['id'],'subCatId' => $subStreamArr['id'],'specId'=>$subSpecArr['id'],'data' => $subSpecArr['name'],'class' => LAYER4CLASS, 'gaTrackParam'=>$gaTrackParam));
                    }
                    $allSbLink = $this->generateMenuLinkHtml($allSub);
                    array_unshift($linksLayer4, $allSbLink);
                }
                $allLink = $this->generateMenuLinkHtml($allLinkData);
                array_unshift($data['links'], $allLink);

            }

            //stream=>specialization
            foreach($this->tabsContentByCategory as $streamArr){
                foreach($streamArr['specializations'] as $specArr){
                    $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$streamArr['name'].' : '.$specArr['name'];
                    $data['links'][] = $this->generateMenuLinkHtml(array('type' => 'fc','catId' => $streamArr['id'],'specId' => $specArr['id'],'data' => $specArr['name'],'class' => LAYER3CLASS, 'gaTrackParam'=>$gaTrackParam));
                }
            }

            $data['id'] = 'fcl3';
            $html['layer3'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer3Menu',$data,true);

            $data['id'] = 'fcl4';
            $data['links'] = $linksLayer4;
            $html['layer4'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer4Menu',$data,true);
        }else{
            $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];
            $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'data-rel'=>'fcl3', 'gaTrackParam'=>$gaTrackParam));
            $data['headingCaption'] = 'in '.$this->tabsContentByCategory[$this->selectedStreamId]['name'];
            $data['showEditStream'] = true;

            // stream=>substream
            $allLinkData = array('type' => 'fc','catId' => $this->selectedStreamId,'data' => 'All '.$this->selectedStreamName.' Colleges','class' => LAYER3CLASS, 'gaTrackParam'=>$gaTrackParam);
            foreach($this->tabsContentByCategory[$this->selectedStreamId]['substreams'] as $subStreamArr){
                $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$subStreamArr['name'];
                
                $l2data = array('type' => 'fc','catId' => $this->selectedStreamId,'subCatId' => $subStreamArr['id'],'data' => $subStreamArr['name'],'class' => LAYER3CLASS, 'gaTrackParam'=>$gaTrackParam);
                
                if(!empty($subStreamArr['specializations'])){
                    $l2data['data-rel'] = 'fcl4';
                }
                
                $data['links'][] = $this->generateMenuLinkHtml($l2data);
                // stream=>substream=>specialization
                $allSub = array('type' => 'fc','catId' => $this->selectedStreamId,'subCatId' => $subStreamArr['id'],'data' => 'All '.$subStreamArr['name'].' Colleges','class' => LAYER3CLASS, 'gaTrackParam'=>$gaTrackParam);
                foreach($subStreamArr['specializations'] as $subSpecArr){
                    $gaTrackParam = 'Personalized : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$subSpecArr['name'].' : '.$subSpecArr['name'];
                    $linksLayer4[] = $this->generateMenuLinkHtml(array('type' => 'fc','catId' => $this->selectedStreamId,'subCatId' => $subStreamArr['id'],'specId'=>$subSpecArr['id'],'data' => $subSpecArr['name'],'class' => LAYER4CLASS, 'gaTrackParam'=>$gaTrackParam));
                }
                $allSbLink = $this->generateMenuLinkHtml($allSub);
                array_unshift($linksLayer4, $allSbLink);
            }

            //stream=>specialization
            foreach($this->tabsContentByCategory[$this->selectedStreamId]['specializations'] as $specArr){
                $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$specArr['name'];
                $data['links'][] = $this->generateMenuLinkHtml(array('type' => 'fc','catId' => $this->selectedStreamId,'specId' => $specArr['id'],'data' => $specArr['name'],'class' => LAYER3CLASS, 'gaTrackParam'=>$gaTrackParam));
            }
            
            $allLink = $this->generateMenuLinkHtml($allLinkData);
            array_unshift($data['links'], $allLink);

            $data['id'] = 'fcl3';
            $html['layer3'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer3Menu',$data,true);

            $data['id'] = 'fcl4';
            $data['links'] = $linksLayer4;
            $html['layer4'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer4Menu',$data,true);
        }
        return $html;
    }
    // Desc : Find College By Course (baseCourse) Tab
    function getFindCollegeByCourseHtml($menuId){
        $data['layerToShow'] = $this->layerToShow;
        $data['layerHeading'] = $this->menuLayer1Subtitles[$menuId]['name'];
        if($this->_isGeneric()){
            $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
            $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'data-rel'=>'bcStream', 'gaTrackParam'=>$gaTrackParam));

            foreach($this->tabsContentByCategory as $streamArr){
                $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$streamArr['name'];

                if($streamArr['name'] == 'Sarkari Exams' || $streamArr['id'] == 21){
                    $data['links'][] = $this->generateMenuLinkHtml(array('data'=>$streamArr['name'],'class'=>LAYER2CLASS,'catId' => $streamArr['id'],'type' => 'fc', 'gaTrackParam'=>$gaTrackParam,'link'=> SHIKSHA_HOME.'/government-exams/exams-st-21'));
                }else{
                    $data['links'][] = $this->generateMenuLinkHtml(array('data'=>$streamArr['name'],'class'=>LAYER2CLASS,'catId' => $streamArr['id'],'type' => 'fc','data-rel'=>'fclbc', 'gaTrackParam'=>$gaTrackParam));
                }
            }
            $data['id'] = 'bcStream';
            $html['layer2'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer2Menu',$data,true);

            $data['links'] = array();
            $hamObj = $this->load->library('mcommon5/HamburgerLib');
            $streamBaseCourse = $hamObj->getBaseCourseByStream();

            foreach($streamBaseCourse as $streamId=>$baseCourse){
                $allLinkData = array('type' => 'fc','catId' => $streamId,'data' => 'All '.$this->tabsContentByCategory[$streamId]['name'].' Colleges','class' => LAYER3CLASS, 'gaTrackParam'=>$gaTrackParam);
                foreach ($baseCourse as $key => $value) {
                    $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$this->tabsContentByCategory[$streamId]['name'];
                    $data['links'][] = $this->generateMenuLinkHtml(array('data'=> $value,'class'=>LAYER3CLASS,'catId' => $streamId,'bcId' => $key,'type' => 'fc','gaTrackParam'=>$gaTrackParam));   
                }
                $allLink = $this->generateMenuLinkHtml($allLinkData);
                array_unshift($data['links'], $allLink);
            }
            $data['id'] = 'fclbc';
            $html['layer3'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer3Menu',$data,true);

        }else{
            $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];
            $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'data-rel'=>'fclbc', 'gaTrackParam'=>$gaTrackParam));
            $data['headingCaption'] = 'in '.$this->tabsContentByCategory[$this->selectedStreamId]['name'];
            $data['showEditStream'] = true;

            $data['links'] = array();
            $hamObj = $this->load->library('mcommon5/HamburgerLib');
            $streamBaseCourse = $hamObj->getBaseCourseByStream();
            $allLinkData = array('type' => 'fc','catId' => $this->selectedStreamId,'data' => 'All '.$this->selectedStreamName.' Colleges','class' => LAYER3CLASS, 'gaTrackParam'=>$gaTrackParam);
            foreach($streamBaseCourse[$this->selectedStreamId] as $key => $value){
                     $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$this->tabsContentByCategory[$this->selectedStreamId]['name'];
                    $data['links'][] = $this->generateMenuLinkHtml(array('data'=> $value,'class'=>LAYER3CLASS,'catId' => $this->selectedStreamId,'bcId' => $key,'type' => 'fc','gaTrackParam'=>$gaTrackParam));   
            }
            $allLink = $this->generateMenuLinkHtml($allLinkData);
            array_unshift($data['links'], $allLink);

            $data['id'] = 'fclbc';
            $html['layer3'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer3Menu',$data,true);
        }
        return $html;
    }

    public function getCompareCollegesHtml($menuId){
        if($this->_isGeneric()){
            $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
        } else {
            $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];
        }
        $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'link' =>SHIKSHA_HOME."/resources/college-comparison", 'gaTrackParam'=>$gaTrackParam));
        return $html;
    }

    public function getRankPredictorHtml($menuId){
        $html = array();$data = array();
        $data['layerToShow'] = $this->layerToShow;
        $data['layerHeading'] = $this->menuLayer1Subtitles[$menuId]['name'];
        $this->load->config('RP/RankPredictorConfig',TRUE);
        $rankPredictorSetting = $this->config->item('settings','RankPredictorConfig');
        $rpExams = $rankPredictorSetting['RPEXAMS'];ksort($rpExams);

        $data['showEditStream'] = false;
        $data['headingCaption'] = false;

        if($this->_isGeneric()){
            $gaTrackParam = 'Generic : '.$data['layerHeading'];
            $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $data['layerHeading'],'data-rel'=>'rpl2', 'gaTrackParam'=>$gaTrackParam));

            foreach($rpExams as $alias => $examArr){
                $gaTrackParam = 'Generic : '.$data['layerHeading'].' : '.$examArr['name'];
                $data['links'][] = $this->generateMenuLinkHtml(array('data' => $examArr['name'],'link' =>SHIKSHA_HOME."/".$examArr['url'],'class'=>LAYER2CLASS, 'gaTrackParam'=>$gaTrackParam));
            }
            $data['id'] = 'rpl2';
            $html['layer2'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer2Menu',$data,true);
        }
        else{
            foreach($rpExams as $alias => $examArr){
                $fieldOfInterest = $rankPredictorSetting[$alias]['fieldOfInterest'];
                $categoryExams[$fieldOfInterest][] = $alias;
            }

            if(empty($categoryExams[$this->selectedStreamId])){
                return $html;
            }
            else if(count($categoryExams[$this->selectedStreamId]) <= THRESHOLD_LIMIT){
                foreach($categoryExams[$this->selectedStreamId] as $examAlias){
                    $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$rpExams[$examAlias]['name'].' Rank Predictor';
                    $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $rpExams[$examAlias]['name'].' Rank Predictor','link' =>SHIKSHA_HOME."/".$rpExams[$alias]['url'], 'gaTrackParam'=>$gaTrackParam));
                }
            }
            else{
                $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$data['layerHeading'];
                $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $data['layerHeading'],'data-rel'=>'rpl2', 'gaTrackParam'=>$gaTrackParam));

                foreach($rpExams as $alias => $examArr){
                    $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$data['layerHeading'].' : '.$examArr['name'];
                    $data['links'][] = $this->generateMenuLinkHtml(array('data' => $examArr['name'],'link' =>SHIKSHA_HOME."/".$examArr['url'],'class'=>LAYER2CLASS, 'gaTrackParam'=>$gaTrackParam));
                }
                $data['id'] = 'rpl2';
                $html['layer2'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer2Menu',$data,true);
            }
        }
        return $html;
    }

    public function getCollegePredictorHtml($menuId){
        $html = array();$data = array();
        $data['layerToShow'] = $this->layerToShow;
        $data['layerHeading'] = $this->menuLayer1Subtitles[$menuId]['name'];

        $this->load->config('CP/CollegePredictorConfig',TRUE);
        $collegepredictorlibrary = $this->load->library('CP/CollegePredictorLibrary');
        $settings = $this->config->item('settings','CollegePredictorConfig');
        $exams = $settings['CPEXAMS'];ksort($exams);

        $data['showEditStream'] = false;
        $data['headingCaption'] = false;

        if($this->_isGeneric()){
            $gaTrackParam = 'Generic : '.$data['layerHeading'];
            $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $data['layerHeading'],'data-rel'=>'cpl2', 'gaTrackParam'=>$gaTrackParam));

            foreach($exams as $alias => $examArr){
                $gaTrackParam = 'Generic : '.$data['layerHeading'].' : '.$alias;
                if($collegepredictorlibrary->isValidPredictorForStream($alias, $this->selectedStreamName) || empty($this->selectedStreamName)) {
                    $data['links'][] = $this->generateMenuLinkHtml(array('data' => $alias,'link' =>$collegepredictorlibrary->generateCollegePredictorUrl($examArr),'class'=>LAYER2CLASS, 'gaTrackParam'=>$gaTrackParam));
                }
            }
            $data['id'] = 'cpl2';
            $html['layer2'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer2Menu',$data,true);
        }
        else{
            $finalExams = array();
            foreach($exams as $alias => $exam){
                if($collegepredictorlibrary->isValidPredictorForStream($alias, $this->selectedStreamName)) {
                    $finalExams[$alias] = $exam;
                }
            }
            $exams = $finalExams;
            if(count($exams) <= THRESHOLD_LIMIT){
                foreach($exams as $exam){
                    $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$exam['name'];
                    $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $exam['name'],'link' =>SHIKSHA_HOME.$exam['directoryName']."/".$exam['collegeUrl'], 'gaTrackParam'=>$gaTrackParam));
                }
            }
            else{
                $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$data['layerHeading'];
                $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $data['layerHeading'],'data-rel'=>'cpl2', 'gaTrackParam'=>$gaTrackParam));
                
                foreach($exams as $exam){
                    $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$exam['name'];
                    $data['links'][] = $this->generateMenuLinkHtml(array('data' => $exam['name'],'link' =>SHIKSHA_HOME.$exam['directoryName']."/".$exam['collegeUrl'],'class'=>LAYER2CLASS,'gaTrackParam'=>$gaTrackParam));
                }   

                $data['id'] = 'cpl2';
                $html['layer2'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer2Menu',$data,true);
            }
        }
        return $html;
    }

    public function getIIMPredictorHtml($menuId){
        if($this->_isGeneric()){
            $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
        } else {
            $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];
        }
        $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'link' =>$this->config->item('iimPredictorUrl'), 'gaTrackParam'=>$gaTrackParam));
        return $html;
    }

    public function getAlumniHtml($menuId){
        if($this->_isGeneric()){
            $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
        } else {
            $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];
        }
        $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'link' =>SHIKSHA_HOME."/mba/resources/mba-alumni-data", 'gaTrackParam'=>$gaTrackParam));
        return $html;
    }

    public function getNewsHtml($menuId){
        if($this->_isGeneric()){
            $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
        } else {
            $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];
        }
        if(!empty($this->selectedStreamId)){
            $link = SHIKSHA_HOME.'/'.strtolower(seo_url($this->selectedStreamName, "-", 30)).'/articles-st-'.$this->selectedStreamId; 
        }else {
            $link = $this->config->item('articleUrl');
        }
        $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'link' =>$link, 'gaTrackParam'=>$gaTrackParam));
        return $html;
    }


     private function getApplyCollegesHtml($menuId){
        $anchorText            = $this->menuLayer1Subtitles[$menuId]['name'];
        $applyColleges = $this->config->item('applyColleges');
        $layer2Id              = 'applyCollegeId';
        $layerToShow           = $this->layerToShow;
        
        if($this->_isGeneric()) {
            $option1Data['data']          = str_replace('{name} ', '', $anchorText);
            $option1Data['class']         = LAYER1CLASS;
            $option1Data['data-rel']      = $layer2Id;
            $option1Data['gaTrackParam'] = 'Generic : '.$option1Data['data'];
            $applyCollegesData['layer1'][] = $this->generateMenuLinkHtml($option1Data);
            $applyCollege = array();
            foreach ($applyColleges as $streamId => $examImpDatesInfo) {
                $option2Data['data']      = $examImpDatesInfo['name'];
                $option2Data['class']     = LAYER2CLASS;
                $option2Data['link']      = $examImpDatesInfo['url'];
                $option2Data['gaTrackParam'] = 'Generic : '.$option1Data['data'].' : '.$option2Data['data'];
                $applyCollege['links'][] = $this->generateMenuLinkHtml($option2Data);
            }
            $applyCollege['id']           = $layer2Id;
            $applyCollege['layerHeading'] = $option1Data['data'];
            $applyCollege['layerToShow']  = $layerToShow;

            $applyCollegesData['layer2'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer2Menu',$applyCollege,true);
        } else {
            $option1Data['data']  = str_replace('{name} ', '', $anchorText);
            $option1Data['class'] = LAYER1CLASS;
            $option1Data['link']  = $applyColleges[$this->selectedStreamId]['url'];
            $option1Data['gaTrackParam'] = 'Personalized : '.$this->selectedStreamName.' : '.$option1Data['data'];
            $applyCollegesData['layer1'][]     = $this->generateMenuLinkHtml($option1Data);
        }
    
        return $applyCollegesData;   
    }


    public function getDiscussionsHtml($menuId){
        if($this->_isGeneric()){
            $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
        } else {
            $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];
        }
        if(!empty($this->selectedStreamId)){
            $streamToTagsMapping = $this->config->item('streamToTagsMapping');
            $tagId = $streamToTagsMapping[$this->selectedStreamId]['id'];
            $tagName = $streamToTagsMapping[$this->selectedStreamId]['name'];
            $seoLink = getSeoUrl($tagId,'tag',$tagName); 
            $url = $seoLink.'?type=discussion';
        }else {
            $url = $this->config->item('discussionsHome');
        }
        $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'link' =>$url, 'gaTrackParam'=>$gaTrackParam));
        return $html;
    }

    public function getQuestionsHtml($menuId){
        if($this->_isGeneric()){
            $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
        } else {
            $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];
        }
        if(!empty($this->selectedStreamId)){
            $streamToTagsMapping = $this->config->item('streamToTagsMapping');
            $tagId = $streamToTagsMapping[$this->selectedStreamId]['id'];
            $tagName = $streamToTagsMapping[$this->selectedStreamId]['name'];
            $seoLink = getSeoUrl($tagId,'tag',$tagName); 
            $url = $seoLink.'?type=question';
        }else {
            $url = SHIKSHA_ASK_HOME_URL;
        }

        $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'link' =>$url, 'gaTrackParam'=>$gaTrackParam));
        return $html;
    }

    public function getAboutHtml($menuId){
        if($this->_isGeneric()){
            $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
        } else {
            $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];
        }
        $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'link' =>$this->config->item('aboutusUrl'), 'gaTrackParam'=>$gaTrackParam));
        return $html;
    }

    public function getHelpHtml($menuId){
        if($this->_isGeneric()){
            $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
        } else {
            $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];
        }
        $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'link' =>$this->config->item('helplineUrl'), 'gaTrackParam'=>$gaTrackParam));
        return $html;
    }

    public function collegeCutoffHTML($menuId){
        $url = SHIKSHA_HOME.'/university/university-of-delhi-24642/cutoff';
        if($this->_isGeneric()){
            $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
        } else {
            $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];
        }
        $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'link' =>$url, 'gaTrackParam'=>$gaTrackParam));
        return $html;
    }

    private function _getReviewsHtml($menuId) {
        $html = array();
        $anchorText = $this->menuLayer1Subtitles[$menuId]['name'];
        $collegeReviewsUrl = $this->config->item('collegeReviewsUrl');
        $layer2Id = 'collegeReviewl2';
        if($this->_isGeneric()) {
            $l1AnchorText = str_replace('{name} ', '', $anchorText);
            $gaTrackParam = 'Generic : '.$l1AnchorText;
            $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $l1AnchorText, 'data-rel' => $layer2Id, 'gaTrackParam' => $gaTrackParam));
            $reviewData = array();
            foreach($collegeReviewsUrl as $streamId => $collegeReviewData) {
                $l2AnchorText = $collegeReviewData['name'];
                $gaTrackParam = 'Generic : '.$l1AnchorText.' : '.$l2AnchorText;
                $reviewData['links'][] = $this->generateMenuLinkHtml(array('data' => $l2AnchorText, 'class' => LAYER2CLASS, 'link' => $collegeReviewData['url'], 'gaTrackParam' => $gaTrackParam));
            }
            $reviewData['id'] = $layer2Id;
            $reviewData['layerHeading'] = $l1AnchorText;
            $reviewData['layerToShow'] = $this->layerToShow;
            $reviewData['showEditStream'] = false;
            $html['layer2'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer2Menu',$reviewData,true);
        }
        else if(!empty($collegeReviewsUrl[$this->selectedStreamId])) {
            $l1AnchorText = str_replace('{name}', $collegeReviewsUrl[$this->selectedStreamId]['name'], $anchorText);
            $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$l1AnchorText;
            $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $l1AnchorText, 'link' => $collegeReviewsUrl[$this->selectedStreamId]['url'], 'gaTrackParam' => $gaTrackParam));
        }
        return $html;
    }

    private function _isGeneric() {
        return ($this->selectedStreamId == 0) ? true : false;
    }

    /**
     * api to created exam options
     * LF-4345 
     * @author Aman Varshney <varshney.aman@gmail.com>
     * @date   2016-05-12
     * @return Exam Menu Item HTML 
     */
    private function getViewExamDetailsHtml($menuId){
        $this->load->config('examPages/examPageConfig');
        $anchorText              = $this->menuLayer1Subtitles[$menuId]['name'];
        $examPageLib             = $this->load->library('examPages/ExamPageLib');
        $hierarchiesAndCourseWithExamNames = $examPageLib->getHierarchiesWithExamNames();
        $hierarchyWithExam = array();
        foreach ($hierarchiesAndCourseWithExamNames['hierarchy'] as $key => $value) {
            $hierarchyWithExam[] =  $key;
        }
        $this->load->builder('listingBase/ListingBaseBuilder');
        $ListingBaseBuilder = new ListingBaseBuilder();
        $this->hierarchyRepo = $ListingBaseBuilder->getHierarchyRepository();
        $hierarchyMappingData = $this->hierarchyRepo->getBaseEntitiesByHierarchyId($hierarchyWithExam,1);
        foreach ($hierarchyMappingData as $key1 => $value1) {
            foreach ($hierarchiesAndCourseWithExamNames['hierarchy'][$key1] as $streamKey => $streamVal) {
                $streamWithExamNames[$value1['stream']['name']][$streamVal['examName']]['is_featured'] = $streamVal['isFeatured'];
                $streamWithExamNames[$value1['stream']['name']][$streamVal['examName']]['url'] = $streamVal['url'];
                $examWithId[$value1['stream']['name']] = $value1['stream']['id'];
                
            }
        }
        $layer2Id                = 'examlistl2';
        $layer3Id                = 'examlistl3';
        $layerToShow             = $this->layerToShow;

        $orderedCustomExamList   = array('MBA', 'Engineering', 'Law','Hospitality','Mass Communication','Design');
        
        foreach ($orderedCustomExamList as $key => $value) {
            if(!in_array($value, array_keys($categoriesWithExamNames))){
                unset($orderedCustomExamList[$key]);
            }
        }
        
        $categoriesWithExamNames = array_merge(array_flip($orderedCustomExamList), $categoriesWithExamNames);
        if($this->_isGeneric()) {
            $option1Data['data']     = str_replace('{name} ', '', $anchorText);
            $option1Data['class']    = LAYER1CLASS;
            $option1Data['data-rel'] = $layer2Id;
            $option1Data['gaTrackParam'] = 'Generic : '.$option1Data['data'];
            $examData['layer1'][]    = $this->generateMenuLinkHtml($option1Data);
            foreach ($streamWithExamNames as $key => $examList) {
                if($key == 'Sarkari Exams'){
                    $option2Data['data'] = $key;
                    $option2Data['link'] = SHIKSHA_HOME.'/government-exams/exams-st-21';
                    unset($option2Data['data-rel']);
                }else{
                    $option2Data['data']     = ($key == 'Hospitality')? 'Hotel Management':$key;
                    $option2Data['data-rel'] = $layer3Id;
                }
                $option2Data['class']    = LAYER2CLASS;
                $option2Data['type']     = 'fc';
                $option2Data['catId']    = $examWithId[$key];
                $option2Data['gaTrackParam'] = 'Generic : '.$option1Data['data'].' : '.$option2Data['data'];
                $examSubCatList[]        = $this->generateMenuLinkHtml($option2Data);
                foreach ($examList as $examName => $examInfo) {
                        $option3Data['data']  = $examName;
                        $option3Data['class'] = LAYER3CLASS;
                        $option3Data['link']  = $examInfo['url'];
                        $option3Data['catId'] = $examWithId[$key];
                        $option3Data['gaTrackParam'] = 'Generic : '.$option1Data['data'].' : '.$option2Data['data'].' : '.$option3Data['data'];
                        $examOptionList[]     = $this->generateMenuLinkHtml($option3Data);
                    }
                }
            $examData['layer2'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer2Menu',array('links'=>$examSubCatList,'id'=>$layer2Id,'layerHeading'=>str_replace('{name} ', '', $anchorText),'layerToShow'=>$layerToShow),true);  

            $examData['layer3'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer3Menu',array('links'=>$examOptionList,'id'=>$layer3Id,'layerHeading'=>str_replace('{name} ', '', $anchorText),'layerToShow'=>'$layerToShow'),true);            
        }
        else 
        {

            $selectedStreamName = '';
            foreach ($streamWithExamNames as $key => $examCatInfo) {
                if($key == $this->selectedStreamName){
                    $selectedStreamName     = $key;
                    $selectedStreamExamList = $streamWithExamNames[$key];
                    break;
                }
            }
            
            if(count($selectedStreamExamList) > 0){                
                if(count($selectedStreamExamList) <= THRESHOLD_LIMIT){
                    foreach ($selectedStreamExamList as $examName => $examInfo) {
                        $option1Data['data']  = str_replace('{name} ', $examName, $anchorText);
                        $option1Data['class'] = LAYER1CLASS;
                        $option1Data['link']  = $examInfo['url'];
                        $option1Data['gaTrackParam'] = 'Personalized : '.$this->selectedStreamName.' : '.$option1Data['data'];
                        $examData['layer1'][] = $this->generateMenuLinkHtml($option1Data);
                    }
                }
                else
                {
                    $option1Data['data']     = str_replace('{name} ', '', $anchorText);
                    $option1Data['class']    = LAYER1CLASS;
                    $option1Data['data-rel'] = $layer2Id;
                    $option1Data['gaTrackParam'] = 'Personalized : '.$this->selectedStreamName.' : '.$option1Data['data'];
                    $examData['layer1'][]    = $this->generateMenuLinkHtml($option1Data);
                    $examListData            = array();
                    foreach ($selectedStreamExamList as $examName => $examInfo) {
                        $option3Data['data']     = $examName;
                        $option3Data['class']    = LAYER2CLASS;
                        $option3Data['link']     = $examInfo['url'];
                        $option3Data['gaTrackParam'] = 'Personalized : '.$this->selectedStreamName.' : '.$option1Data['data'].' : '.$option3Data['data'];
                        $examListData['links'][] = $this->generateMenuLinkHtml($option3Data);   
                    }
                    $examListData['id']           = $layer2Id;
                    $examListData['layerHeading'] = $selectedStreamName.' Exam List';
                    $examListData['layerToShow']  = $layerToShow;
                    $examData['layer2']           =  $this->load->view('mcommon5/hamburgerV2/hamburgerLayer2Menu',$examListData,true);
                }
            }
        }
        
        return $examData;        
    }

    /**
     * api to create exam important dates 
     * LF-4346
     * @author Aman Varshney <varshney.aman@gmail.com>
     * @date   2016-05-12
     * @return Exam Important Dates Menu Item HTML 
     */
    private function getExamImportantDatesHtml($menuId){
        $anchorText            = $this->menuLayer1Subtitles[$menuId]['name'];
        $examImportantDatesUrl = $this->config->item('examImportantDatesUrl');
        $layer2Id              = 'examImpDatel2';
        $layerToShow           = $this->layerToShow;
        
        if($this->_isGeneric()) {
            $option1Data['data']          = str_replace('{name} ', '', $anchorText);
            $option1Data['class']         = LAYER1CLASS;
            $option1Data['data-rel']      = $layer2Id;
            $option1Data['gaTrackParam'] = 'Generic : '.$option1Data['data'];
            $examImpDatesData['layer1'][] = $this->generateMenuLinkHtml($option1Data);
            $examDatesData = array();
            foreach ($examImportantDatesUrl as $streamId => $examImpDatesInfo) {
                $option2Data['data']      = $examImpDatesInfo['name'];
                $option2Data['class']     = LAYER2CLASS;
                $option2Data['link']      = $examImpDatesInfo['url'];
                $option2Data['gaTrackParam'] = 'Generic : '.$option1Data['data'].' : '.$option2Data['data'];
                $examDatesData['links'][] = $this->generateMenuLinkHtml($option2Data);
            }
            $examDatesData['id']           = $layer2Id;
            $examDatesData['layerHeading'] = $option1Data['data'];
            $examDatesData['layerToShow']  = $layerToShow;

            $examImpDatesData['layer2'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer2Menu',$examDatesData,true);
        } else {
            $option1Data['data']  = str_replace('{name} ', '', $anchorText);
            $option1Data['class'] = LAYER1CLASS;
            $option1Data['link']  = $examImportantDatesUrl[$this->selectedStreamId]['url'];
            $option1Data['gaTrackParam'] = 'Personalized : '.$this->selectedStreamName.' : '.$option1Data['data'];
            $examImpDatesData['layer1'][]     = $this->generateMenuLinkHtml($option1Data);
        }
    
        return $examImpDatesData;   
    }

    
    public function getRankingMenuHtml($menuId) {
    	//get ranking data
    	$this->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
		$rankingPageUrlManager = RankingPageBuilder::getURLManager();
    	$rankingMenuData = $rankingPageUrlManager->getStreamWiseRankingPageUrl();
        $layer2Id = 'rankingl2';
        $data['layerToShow'] = $this->layerToShow;
    	
    	//create menu html
    	if($this->_isGeneric()) {
            if(count($rankingMenuData) <= THRESHOLD_LIMIT){
                foreach ($rankingMenuData as $streamId => $rankings) {
                    foreach ($rankings as $key => $rankingData) {
                        $menuText = $streamToTagsMapping[$streamId]['name'];
                        $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$menuText;
                        $data['links'][] = $this->generateMenuLinkHtml(array('data' => $menuText, 'link' => $rankingData['url'], 'class' => LAYER1CLASS, 'gaTrackParam' => $gaTrackParam));
                    }                
                }
            }
            else{
            //layer 1 html
            $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
    		$html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'], 'data-rel' => 'rankingLayer2', 'gaTrackParam' => $gaTrackParam));
    		//layer 2 html
    		$data['layerHeading'] = $this->menuLayer1Subtitles[$menuId]['name'];
    		$data['id']			  = 'rankingLayer2' ;
            $data['showEditStream'] = false;
            $streamToTagsMapping = $this->config->item('streamToTagsMapping');
            foreach ($rankingMenuData as $streamId => $rankings) {
                $menuText = $streamToTagsMapping[$streamId]['name'];
                $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$menuText;
                $data['links'][] = $this->generateMenuLinkHtml(array('data' => $menuText, 'class' => LAYER2CLASS, 'data-rel' => 'fclrank', 'catId'=>$streamId,'type'=>'fc','gaTrackParam' => $gaTrackParam));
                
            }

    		$html['layer2'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer2Menu', $data, true);
            $data['links'] = array();

            
            // prepare data for layer3
            foreach($rankingMenuData as $streamId => $rankings){
                
                foreach ($rankings as $key => $rankingData) {
                    $menuText = $rankingData['title'];
                    $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$menuText;
                    $data['links'][] = $this->generateMenuLinkHtml(array('data' => $menuText, 'link' => $rankingData['url'], 'class' => LAYER3CLASS, 'gaTrackParam' => $gaTrackParam, 'catId' => $streamId));
                    
                }
            }

                $data['id'] = 'fclrank';
                $html['layer3'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer3Menu',$data,true);


    	   } 
        }
        else {
    		if(count($rankingMenuData[$this->selectedStreamId]) <= THRESHOLD_LIMIT){
                foreach ($rankingMenuData[$this->selectedStreamId] as $key => $rankingData) {
                    $menuText = $rankingData['title'].' College Rankings';
                    $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$menuText;
                    $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $menuText, 'link' => $rankingData['url'],'gaTrackParam' => $gaTrackParam, 'class' => LAYER1CLASS));
                }
            }else{

                $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];
                $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'], 'data-rel' => 'rankingLayer2', 'gaTrackParam' => $gaTrackParam));
                $data['id']           = 'rankingLayer2' ;
                $streamToTagsMapping = $this->config->item('streamToTagsMapping');
                $data['links'] = array();
                $data['layerHeading'] = $this->menuLayer1Subtitles[$menuId]['name'];
                foreach ($rankingMenuData[$this->selectedStreamId] as $key => $rankingData) {
                    $menuText = $rankingData['title'].' College Rankings';
                    $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$menuText;
                    $data['links'][] = $this->generateMenuLinkHtml(array('data' => $menuText, 'class' => LAYER3CLASS,'catId'=>$this->selectedStreamId,'type'=>'fc', 'gaTrackParam' => $gaTrackParam, 'link' => $rankingData['url']));
                }
                $html['layer2'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer3Menu', $data, true);
                

                
        }
    	
    }
    	
    	return $html;
    }

    private function generateMenuLinkHtml($option) {
    	$type     = empty($option['type']) ? '':'type="'.$option['type'].'"';
        $catId    = empty($option['catId']) ? '':'catId="'.$option["catId"].'"';
        $subCatId = empty($option['subCatId']) ? '':'subCatId="'.$option['subCatId'].'"';
        $progId   = empty($option['progId']) ? '': 'progId="'.$option['progId'].'"';
        $specId   = empty($option['specId']) ? '': 'specId="'.$option['specId'].'"';
        $bcId   = empty($option['bcId']) ? '': 'bcId="'.$option['bcId'].'"';
        $class    = empty($option['class']) ? 'class="cat-head"':'class="'.$option['class'].'"';
        $link     = empty($option['link']) ? 'href="javascript:void(0);"':'href="'.$option['link'].'"';
        $datarel  = empty($option['data-rel']) ? '':'data-rel="'.$option['data-rel'].'"';
        $dataTransition  = empty($option['data-transition']) ? '':'data-transition="'.$option['data-transition'].'"';
        $gaTrackParam = empty($option['gaTrackParam']) ? '':'gaTrackParam="'.$option['gaTrackParam'].'"';
        $data     = $option['data'];

        return "<a $type $catId $subCatId $specId $bcId $class $link $datarel $dataTransition>$data</a>";
    }


    function getCampusRepPrograms($menuId){
        $data['layerToShow'] = $this->layerToShow;
        $hamObj = $this->load->library('mcommon5/HamburgerLib');
        $data['layerHeading'] = str_replace('{name} ', '', $this->menuLayer1Subtitles[$menuId]['name']);
        if($this->_isGeneric()){
            $campusPrograms = $hamObj->getCampusRepProgramData();
        }else{
            $campusPrograms = $hamObj->getCampusRepProgramData($this->selectedStreamId);
        }
        if(empty($campusPrograms)){
            return;
        }
        if($this->_isGeneric()){
            if(count($campusPrograms) <= THRESHOLD_LIMIT){
                foreach($campusPrograms as $progArr){
                    if($progArr['programName'] == 'Engineering'){
                        $progArr['programName'] = 'B.Tech';
                    }
                    $this->menuLayer1Subtitles[$menuId]['name'] = str_replace('{name} ', $progArr['programName'], $this->menuLayer1Subtitles[$menuId]['name']);
                    $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
                    $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'data-rel'=>'repStream', 'gaTrackParam'=>$gaTrackParam, 'link'=>$progArr['ccUrl']));
                }
            }else{
                $this->menuLayer1Subtitles[$menuId]['name'] = str_replace('{name} ', '', $this->menuLayer1Subtitles[$menuId]['name']);
                $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'];
                $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'data-rel'=>'repStream', 'gaTrackParam'=>$gaTrackParam));

                foreach($campusPrograms as $progArr){
                    if($progArr['programName'] == 'Engineering'){
                        $progArr['programName'] = 'B.Tech';
                    }
                    $gaTrackParam = 'Generic : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$progArr['programName'];
                    $data['links'][] = $this->generateMenuLinkHtml(array('data'=>$progArr['programName'],'class'=>LAYER2CLASS,'progId' => $progArr['programId'],'type' => 'fc', 'gaTrackParam'=>$gaTrackParam, 'link'=>$progArr['ccUrl']));
                }
                $data['id'] = 'repStream';
                $html['layer2'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer2Menu',$data,true);
            }
        }else{
            if(count($campusPrograms) <= THRESHOLD_LIMIT){
                foreach($campusPrograms as $value){
                    if($value['programName'] == 'Engineering'){
                        $value['programName'] = 'B.Tech';
                    }
                    $this->menuLayer1Subtitles[$menuId]['name'] = str_replace('{name} ', $this->selectedStreamName, $this->menuLayer1Subtitles[$menuId]['name']);
                    $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];
                    $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => 'Ask Current '.$value['programName'].' Students','data-rel'=>'ccStream', 'gaTrackParam'=>$gaTrackParam, 'link'=>$value['ccUrl']));
                }
            }else{
                $this->menuLayer1Subtitles[$menuId]['name'] = str_replace('{name} ', '', $this->menuLayer1Subtitles[$menuId]['name']);
                $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'];

                $html['layer1'][] = $this->generateMenuLinkHtml(array('data' => $this->menuLayer1Subtitles[$menuId]['name'],'data-rel'=>'ccStream', 'gaTrackParam'=>$gaTrackParam));

                $data['headingCaption'] = 'in '.$this->tabsContentByCategory[$this->selectedStreamId]['name'];
                $data['showEditStream'] = true;

                $data['links'] = array();
                foreach($campusPrograms as $value){
                    if($value['programName'] == 'Engineering'){
                        $value['programName'] = 'B.Tech';
                    }
                    $gaTrackParam = 'Personalized : '.$this->selectedStreamName.' : '.$this->menuLayer1Subtitles[$menuId]['name'].' : '.$this->tabsContentByCategory[$this->selectedStreamId]['name'];
                    $data['links'][] = $this->generateMenuLinkHtml(array('data'=> $value['programName'],'class'=>LAYER3CLASS, 'progId'=>$value['programId'], 'catId' => $this->selectedStreamId, 'type' => 'fc','gaTrackParam'=>$gaTrackParam, 'link'=>$value['ccUrl']));   
                }
                $data['id'] = 'ccStream';
                $html['layer3'] = $this->load->view('mcommon5/hamburgerV2/hamburgerLayer3Menu',$data,true);
            }
        }
        return $html;
    }

    /**
     * (LF-4348) Obtain the Ask and Answer layer (Ask Shiksha Experts)
     *
     * @param int $menuId The stream id
     *
     * @return array An array containing the proper layer 1 and the layer 2 links
     */
    public function getAnaLayerHtml($menuId){
        $anchorText = $this->menuLayer1Subtitles[$menuId]['name'];

        $htmlLinkData = array(
            'data'=>$anchorText,
            'class'=>LAYER1CLASS.' _hmAsk',
            'link'=> '#questionPostingLayerOneDiv',
            'type' => 'askShikshaExperts',
            'data-rel' => 'dialog',
            'data-transition'=>'fade',
            'gaTrackParam' => $this->_isGeneric() ? 'Generic: ' . $anchorText : 'Personalized: '. $anchorText,
        );
        $html['layer1'][] = $this->generateMenuLinkHtml($htmlLinkData);
        return $html;
    }
    
    function ampAuthorization(){
        $data['subscriber']  = false;
        $data['access']  = false;
        $user = $this->checkUserValidation();
        $requestType = !empty($_GET['callType'])?$this->input->get('callType'):'';
        $courseId = !empty($_GET['courseId'])?$this->input->get('courseId'):'';
        $listingId = !empty($_GET['listingId'])?$this->input->get('listingId'):'';
        $pageType = !empty($_GET['pageType'])?$this->input->get('pageType'):'';
        $viewedResKey = !empty($_GET['viewedResKey'])?$this->input->get('viewedResKey'):'';

        if(!empty($courseId) && preg_match('/^\d+$/', $courseId)){
            $courseId = $courseId;
        }
    
        if(!empty($user[0]['userid'])){
            $data['userid']  = $user[0]['userid'];
            $data['displayName']  = ($user[0]['firstname']) ? ucwords($user[0]['firstname']) :  ucwords($user[0]['displayname']); 
            $data['profileIcon'] = strtoupper($data['displayName'][0]);
            $data['subscriber']  = true;
            $data['access']  = true;
            $data['validuser'] = false;


            if(isset($_GET['fromwhere']) && $_GET['fromwhere'] == 'pwa'){
                header('access-control-allow-credentials:true');
                /*header('access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token');
                header('access-control-allow-methods:POST, GET, OPTIONS');*/
                //header('access-control-allow-origin: '.AMP_SHIKSHA_DOMAIN);
                header('access-control-allow-origin: http://localshiksha.com:3022');
                header('access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin');
               // header('amp-access-control-allow-source-origin: '.SHIKSHA_HOME);
                header('amp-access-control-allow-source-origin: http://localshiksha.com:3022');
                 
            }
            

            if(!empty($courseId) && $requestType != 'pingback')
            {                
                if(in_array($pageType, array('examPage'))){
                    $data['validuser'] = Modules::run('mobile_examPages5/ExamPageMain/checkUserIsValidForExam',$courseId);    
                    if($data['validuser']){
                        Modules::run('mobile_listing5/CourseMobile/ampCreateViewedResponse',$courseId,$viewedResKey,'exam'); 
                    }
                    
                    $data['GuideMailed'] = Modules::run('mobile_examPages5/ExamPageMain/checkActionPerformedOnexamPage',$courseId,'examGuide');
                    $data['examSubscribe'] = Modules::run('mobile_examPages5/ExamPageMain/checkActionPerformedOnexamPage',$courseId,'examSubscribe');
                }else{
                    $data['validuser'] = Modules::run('mobile_listing5/CourseMobile/checkUserIsValidForCourse',$courseId); 
                    Modules::run('mobile_listing5/CourseMobile/ampCreateViewedResponse',$courseId,$viewedResKey); 
                    $data['bMailed'] = Modules::run('mobile_listing5/CourseMobile/_checkIfBrochureDownloaded',$courseId);
                    $data['shortlisted'] = Modules::run('nationalInstitute/InstituteDetailPage/checkIfUserShortlistedCourse' ,$courseId,true);
                    $data['compared'] = Modules::run('mobile_listing5/CourseMobile/checkCourseAddedToCompare',$courseId);
                    $data['contact'] = !$data['bMailed'] ? Modules::run('mobile_listing5/CourseMobile/showContactInfoToUser',$courseId,'course') : true;
                }
            }
            elseif(!empty($listingId) && $requestType!= 'pingback')
            {
                $data['contact'] = Modules::run('mobile_listing5/CourseMobile/showContactInfoToUser',$listingId,'institute');
            }
            $shortlistCount = Modules::run('mobile_category5/CategoryMobile/getMShortlistedCourse');
            if($shortlistCount>0){
                $data['shortlistCount'] = '('.$shortlistCount.')';
            }
            $cmpLib = $this->load->library('comparePage/comparePageLib');
            $cmpCount = count($cmpLib->getComparedData('mobile'));
            if($cmpCount>0){
                $data['cmpCount'] = '('.$cmpCount.')';
            }
        }
        echo json_encode($data);
    }
    //@Desc : Prepare AMP hamburger
    //@author : akhter
    function getAMPHamburger($param){
            $this->init();        

            $displayData['profileData'] = $this->getProfileData();
            $displayData['selectedStreamId'] = $this->selectedStreamId;
            $displayData['tabsContentByCategory'] = $this->tabsContentByCategory;
            $ampHamburger = "HomePageRedesignCache/ampHamburger.html";
            if( !(file_exists($ampHamburger) && (time() - filemtime($ampHamburger))<=(1*24*60*60))){

            $hamObj = $this->load->library('mcommon5/HamburgerLib');
            $displayData['streamBaseCourse'] = $hamObj->getBaseCourseByStream();
            //get College Ranking data
            $this->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
            $rankingPageUrlManager = RankingPageBuilder::getURLManager();
            $displayData['rankingMenuData'] = $rankingPageUrlManager->getStreamWiseRankingPageUrl();
            $displayData['streamToTagsMapping'] = $this->config->item('streamToTagsMapping');
            //get college review
            $displayData['collegeReviewsUrl'] = $this->config->item('collegeReviewsUrl');
            $displayData['discussionsHome'] = $this->config->item('discussionsHome');

            // get examlist
            $this->load->config('examPages/examPageConfig');
            $anchorText              = $this->menuLayer1Subtitles[$menuId]['name'];
            $examPageLib             = $this->load->library('examPages/ExamPageLib');
            $hierarchiesAndCourseWithExamNames = $examPageLib->getHierarchiesWithExamNames();
            $hierarchyWithExam = array();
            foreach ($hierarchiesAndCourseWithExamNames['hierarchy'] as $key => $value) {
                $hierarchyWithExam[] =  $key;
            }
            $this->load->builder('listingBase/ListingBaseBuilder');
            $ListingBaseBuilder = new ListingBaseBuilder();
            $this->hierarchyRepo = $ListingBaseBuilder->getHierarchyRepository();
            $hierarchyMappingData = $this->hierarchyRepo->getBaseEntitiesByHierarchyId($hierarchyWithExam,1);
            foreach ($hierarchyMappingData as $key1 => $value1) {
                foreach ($hierarchiesAndCourseWithExamNames['hierarchy'][$key1] as $streamKey => $streamVal) {
                    $streamWithExamNames[$value1['stream']['name']][$streamVal['examName']]['is_featured'] = $streamVal['isFeatured'];
                    $streamWithExamNames[$value1['stream']['name']][$streamVal['examName']]['url'] = $streamVal['url'];
                    $examWithId[$value1['stream']['name']] = $value1['stream']['id'];
                    
                }
            }
            $orderedCustomExamList   = array('MBA', 'Engineering', 'Law','Hospitality','Mass Communication','Design');
            foreach ($orderedCustomExamList as $key => $value) {
                if(!in_array($value, array_keys($categoriesWithExamNames))){
                    unset($orderedCustomExamList[$key]);
                }
            }
            
            $categoriesWithExamNames = array_merge(array_flip($orderedCustomExamList), $categoriesWithExamNames);
            $displayData['streamWithExamNames'] = $streamWithExamNames;
            // check exam dates
            $displayData['examImportantDatesUrl'] = $this->config->item('examImportantDatesUrl');
            // predict your exam rank
            $this->load->config('RP/RankPredictorConfig',TRUE);
            $rankPredictorSetting = $this->config->item('settings','RankPredictorConfig');
            $rpExams = $rankPredictorSetting['RPEXAMS'];ksort($rpExams);
            $displayData['rpExams'] = $rpExams;
            
            // predict college
            $this->load->config('CP/CollegePredictorConfig',TRUE);
            $settings = $this->config->item('settings','CollegePredictorConfig');
            $exams = $settings['CPEXAMS'];ksort($exams);
            $displayData['cpExams'] = $exams;
            // Ask current students
     
           $displayData['campusPrograms'] = $hamObj->getCampusRepProgramData();
            }
            $loginUrl = SHIKSHA_HOME.'/muser5/UserActivityAMP/getLoginAmpPage';
            $registerUrl = SHIKSHA_HOME.'/muser5/UserActivityAMP/getRegistrationAmpPage';

            if(!empty($param['entityId'])){
                $loginUrl = add_query_params($loginUrl,array('entityId' => $param['entityId'], 'entityType' => $param['entityType']));
                $registerUrl = add_query_params($registerUrl,array('entityId' => $param['entityId'], 'entityType' => $param['entityType']));
            }
            if(!empty($param['fromwhere'])){
                $loginUrl = add_query_params($loginUrl,array('fromwhere' => $param['fromwhere']));
                $registerUrl = add_query_params($registerUrl,array('fromwhere' => $param['fromwhere']));
            }
            if(!empty($param['listingId'])){
                $loginUrl = add_query_params($loginUrl,array('listingId' => $param['listingId']));
                $registerUrl = add_query_params($registerUrl,array('listingId' => $param['listingId']));
            }
            if(isset($_GET['from'])){
                $loginUrl = add_query_params($loginUrl,array('fromwhere' => $_GET['from']));
                $registerUrl = add_query_params($registerUrl,array('fromwhere' => $_GET['from']));
            }
            if(isset($_GET['listingId'])){
                $loginUrl = add_query_params($loginUrl,array('listingId' => $_GET['listingId']));
                $registerUrl = add_query_params($registerUrl,array('listingId' => $_GET['listingId']));
            }

            $displayData['loginUrl'] = $loginUrl;
            $displayData['registerUrl'] = $registerUrl;


            if(isset($_GET['fromwhere']) && $_GET['fromwhere'] == 'pwa'){
               

                $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
                header("Access-Control-Allow-Origin: ".$requestHeader);
                //header("Access-Control-Allow-Origin: http://localshiksha.com:3022");
                header("Access-Control-Allow-Credentials: true");
                header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
                header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
                header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
                header("Content-Type: application/json; charset=utf-8");
    
                $html = $this->load->view('mcommon5/AMP/hamburger',$displayData,true);
                echo json_encode(array('html'=>$html));
            }else{
                $this->load->view('mcommon5/AMP/hamburger',$displayData);
            }
    }

    //Desc - This function is used only for update cache for hamburger data.
    //URL -  www.shiksha.com/mcommon5/MobileSiteHamburgerV2/resetHamburger?resetData=1
    //@uthor - akhter
    function resetHamburger($isReset=0){
	$this->validateCron();
        $resetData = (isset($_GET['resetData']) && ($_GET['resetData'] == '1')) ? 1 : $isReset;
        $resetData = ($resetData>0) ? true : false;
        $hamObj    = $this->load->library('mcommon5/HamburgerLib');
        $tabContent = $hamObj->nonZeroTabContent($resetData);    
        $baseCourse = $hamObj->getBaseCourseByStream($resetData);
        echo '=========Stream/Substream/specialization===========';
        _p($tabContent);
        echo '=====================baseCourse====================';
        _p($baseCourse);
    }   
}

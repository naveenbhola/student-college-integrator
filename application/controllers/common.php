<?php 
class common extends MX_Controller {
	function init() {
		$this->load->helper(array('url','image'));
	}

    function index($flag='',$limit = '100') {
        $appId = 1;
        if($flag != 'new'){
            $cityList = $this->getCityList($appId, $flag);
        }
        else{
            $cityList = $this->getNewCityList($appId, 'new',$limit);
        }
        $cities = array();
        foreach($cityList as $cityListItem) {
            if(trim($cityListItem['city_name']) != '') {
                $cities[$cityListItem['countryId']][$cityListItem['city_id']] = $cityListItem['city_name'];
            }
        }
        foreach($cities as $country => $cityList) {
            asort($cityList);
            $cities[$country] =  $cityList;
        }
        echo json_encode($cities);
	}
	
	function loadOverlayContent($overlayname,$data = '') {
		$validity = $this->checkUserValidation();
		
		$courseId = (int) $this->input->post('courseId');
                $instituteId = (int) $this->input->post('instituteId');
		
		$OTPVerification = (int) $this->input->post('OTPVerification');
		$ODBVerification = (int) $this->input->post('ODBVerification');
		
		if($courseId > 0) {
			$OTPVerification = 0;
			$ODBVerification = 0;
			if($validity !== false) {
				global $OTPCourses;
				global $ODBCourses;
				
				if($courseId > 0) {
					if($OTPCourses[$courseId]) {
						$OTPVerification = 1;
					}
					else if($ODBCourses[$courseId]) {
						$ODBVerification = 1;
					}
				}
			}
			
			$data['OTPVerification'] = $OTPVerification;
			$data['ODBVerification'] = $ODBVerification;
		}
		else if(isset($OTPVerification) && isset($ODBVerification)) {
			if($validity !== false) {
				$data['widget'] = (string) $this->input->post('widget');
				$data['OTPVerification'] = $OTPVerification;
				$data['ODBVerification'] = $ODBVerification;
				$data['response'] = (string) $this->input->post('response');
			}
		}
		
		$overlayname = str_replace('-','/',$overlayname);
                
                $encodedlistData 	= $this->input->post('listData');
                $isCourseDropDownEnable = $this->input->post('isCourseDropDownEnable');
                $listData 	= unserialize(base64_decode($encodedlistData));
		$widgetName = $this->input->post('widgetName');
        //conversion tracking  
              $data['trackingPageKeyId']=$this->input->post('trackingPageKeyId');
                if($courseId > 0 || ($widgetName != 'undefined' && !empty($widgetName))) {
                    if( !empty($listData) && is_array($listData) && count($listData) > 0){
                        $displayForm = "";
                        $courseIds = array_keys($listData);
                        $national_course_lib = $this->load->library('listing/NationalCourseLib');
                        $dominantDesiredCourseData = $national_course_lib->getDominantDesiredCourseForClientCourses($courseIds);
                        foreach ($dominantDesiredCourseData as $key => $value) {
                            $dominantDesiredCourseData[$key]['name'] = $listData[$key];
                        }
                        
			if(count($listData) == 1){
				$data['desiredCourse'] = ($courseId > 0)?$dominantDesiredCourseData[$courseId]['desiredCourse']:$dominantDesiredCourseData[$courseIds[0]]['desiredCourse'];
				$data['fieldOfInterest'] = ($courseId > 0)?$dominantDesiredCourseData[$courseId]['categoryId']:$dominantDesiredCourseData[$courseIds[0]]['categoryId'];
				$data['courseIdSelected'] = ($courseId > 0)?$courseId:$courseIds[0];
			}
			else {
				$data['instituteCourses'] = $dominantDesiredCourseData;
				$data['defaultCourse'] = ($courseId > 0)?$dominantDesiredCourseData[$courseId]['desiredCourse']:$dominantDesiredCourseData[$courseIds[0]]['desiredCourse'];
				$data['defaultCategory'] = ($courseId > 0)?$dominantDesiredCourseData[$courseId]['categoryId']:$dominantDesiredCourseData[$courseIds[0]]['categoryId'];
				if($isCourseDropDownEnable !== '1'){
				    $data['defaultCourseId'] = ($courseId > 0)?$courseId:$courseIds[0];
				}
			}
                    }
                    else if($courseId > 0) {
                        $national_course_lib = $this->load->library('listing/NationalCourseLib');
                        $dominantDesiredCourseData = $national_course_lib->getDominantDesiredCourseForClientCourses(array($courseId));
                        $data['desiredCourse'] = $dominantDesiredCourseData[$courseId]['desiredCourse'];
                        $data['fieldOfInterest'] = $dominantDesiredCourseData[$courseId]['categoryId'];
                        $data['courseIdSelected'] = $courseId;
                    }
                    $data['instituteId'] = $instituteId;
                    $data['widget'] = ($widgetName != 'undefined' && !empty($widgetName))?$widgetName:'common';
                    $data['customCallBack'] = ($this->input->post('customCallBack') != 'undefined')?$this->input->post('customCallBack'):'';
                    echo Modules::run('registration/Forms/showResponseRegisterFreeLayer', $data);
                    
                }else{
                    
                    $content = $this->load->view($overlayname,$data,true);
                    echo $content;
                }
	}
	
	private function getCityList($appId, $flag){
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
        $cityList = $categoryClient->getCityList($appId, $flag);
		return $cityList;
	}

	private function getNewCityList($appId, $flag,$limit){
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
        $cityList = $categoryClient->getNewCityList($appId, $flag,$limit);
		return $cityList;
	}

    function getTierCities($flag='',$limit = '100') {
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
        $appId = 1;
        $cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
        $cityListTier2 = $categoryClient->getCitiesInTier($appId,2,2);
        $cityListTier3 = $categoryClient->getCitiesInTier($appId,3,2);
        $indiaCities = array_merge(array_merge($cityListTier1, $cityListTier2),$cityListTier3);
        if($flag != 'new'){
            $cityList = $this->getCityList($appId, $flag);
        }
        else{
            $cityList = $this->getNewCityList($appId, 'new',$limit);
        }

        $cities = array();
        foreach($indiaCities as $indianCity){
            if(trim($indianCity['cityName']) != '') {
                $cities[$indianCity['countryId']][$indianCity['cityId']] = $indianCity['cityName'];
            }
        }
        foreach($cityList as $cityListItem) {
            if(trim($cityListItem['city_name']) != '' && $cityListItem['countryId']!=2) {
                $cities[$cityListItem['countryId']][$cityListItem['city_id']] = $cityListItem['city_name'];
            }
        }
        foreach($cities as $country => $countryCityList) {
            asort($countryCityList);
            $cities[$country] =  $countryCityList;
        }
        echo 'var cityList = eval('. json_encode($cities) .');'; 
	}

    function updateArticleForMsgBoard($blogId, $categoryId){
        $this->load->library(array('message_board_client'));
        $msgbrdClient = new Message_board_client();
        $topicResult = $msgbrdClient->addTopic(1,22,'You can discuss on this blog below',$categoryId,'220.225.240.2','blog');

        echo 'UPDATE blogTable SET userId = 22, discussionTopic = "'.$topicResult['ThreadID'].'" where blogId = '. $blogId;
    }

    function logCLCs($dUrl, $srcUrl, $fromLayer){
        $dUrl = htmlentities($dUrl);
        $srcUrl = htmlentities($srcUrl);
        $fromLayer = htmlentities($fromLayer);
        echo $dUrl .'::'. $srcUrl.'::'.$fromLayer;
        error_log('CLCs==='. base64_decode($dUrl) .' ::: '. base64_decode($srcUrl) .' ::: '. $fromLayer);
    }

    function setCkAtS(){
        $cookieName = $_POST['ckn'];
        $value = $_POST['ckv'];
        $days = $_POST['ckd'];
        $timeStamp = $days + (60 * 60 * 24);
        setcookie($cookieName,$value,time() + $timeStamp,'/',COOKIEDOMAIN);
        echo "1";
    }

    function uploadImage($productType, $productId){
       $this->init(); 
       $appId = 1;
       $title = isset($_POST['title']) ? $_POST['title'] : '';
       if($productType == 'blog') {
        $imageProperties = getimagesize($_FILES['shikshaImage']['tmp_name'][0]);
        $homePageimageProperties = getimagesize($_FILES['shikshaHomePageImage']['tmp_name'][0]);
        if($imageProperties[0] > 680 || $imageProperties[1]>460 || $_FILES['shikshaImage']['size'][0] > (60*1024) ) {
            die('The upload image should not exceed 60KB in size and must be within dimensions of 680x460');
        }
        if($homePageimageProperties[0] > 175 || $homePageimageProperties[1]> 97 || $_FILES['shikshaHomePageImage']['size'][0] > (15*1024) ) {
            die('The upload image should not exceed 15KB in size and must be within dimensions of 175x97');
        }



       }else if($productType == 'sacontent') {
       		$imageProperties = getimagesize($_FILES['shikshaImage']['tmp_name'][0]);
       		error_log(print_r($imageProperties,true));
       		
       		if(floatVal($imageProperties[0]/$imageProperties[1]) != 1.5 && !($imageProperties[0] == 172 && $imageProperties[1] == 115) ) {
       			die('The upload image should be in ratio of 3:2');
       		}	
       		if($imageProperties[0] < 172) {
       			die('The upload image should exceed 172KB in size');
       		}
       		$productType = 'saContentBlog';
       		
       }
       if($_FILES['shikshaImage']['tmp_name'] != '') {
           $this->load->library('upload_client');
           $uploadclient = new upload_client();
           $uploadres = $uploadclient->uploadfile($appId,'image',$_FILES,$title,$productId,$productType,"shikshaImage");
		   if(is_array($uploadres)) {
               //Ankur:: Added to convert relative url to absolute url
               $imageurl = $uploadres[0]['imageurl'];
               if(strpos($imageurl,'shiksha.com')===false){
                        if($productType == 'saContentBlog'){
                            $imageurl = $imageurl;
                        }else{
                            $imageurl = MEDIA_SERVER.$imageurl;
                        }
                       $uploadres[0]['imageurl'] = $imageurl;
               }
               $imageurl = $uploadres[0]['thumburl'];
               if(strpos($imageurl,'shiksha.com')===false){
                        if($productType == 'saContentBlog'){
                            $imageurl = $imageurl;
                        }else{
                            $imageurl = MEDIA_SERVER.$imageurl;
                        }
                        $uploadres[0]['thumburl'] = $imageurl;
               }

               $imageDetails = $uploadres[0];
               echo json_encode($imageDetails);
           } else {
                echo print_r($uploadres, true);
           }
       }
    if($_FILES['shikshaHomePageImage']['tmp_name'] != '') {
           $this->load->library('upload_client');
           $uploadclient = new upload_client();
           $uploadres = $uploadclient->uploadfile($appId,'image',$_FILES,$title,$productId,$productType,"shikshaHomePageImage");
                   if(is_array($uploadres)) {
               $imageDetails = $uploadres[0];
               echo json_encode($imageDetails);
           } else {
                echo print_r($uploadres, true);
           }
       }

   }
   
   
   function uploadImageDialog($productType, $productId){
       $this->init(); 
       $appId = 1;
       $title = isset($_POST['title']) ? $_POST['title'] : '';
       if($productType == 'blog') {
        $imageProperties = getimagesize($_FILES['shikshaImageDialog']['tmp_name'][0]);
        if($_FILES['shikshaImageDialog']['size'][0] > (600*1024) ) {
            die('The upload image should not exceed 600KB in size');
        }
       }
       if($_FILES['shikshaImageDialog']['tmp_name'] != '') {
           $this->load->library('upload_client');
           $uploadclient = new upload_client();
           $uploadres = $uploadclient->uploadfile($appId,'image',$_FILES,$title,$productId,$productType,"shikshaImageDialog");
		   if(is_array($uploadres)) {
               //Ankur:: Added to convert relative url to absolute url
               $imageurl = $uploadres[0]['imageurl'];
               if(strpos($imageurl,'shiksha.com')===false){
                       $imageurl = MEDIA_SERVER.$imageurl;
                       $uploadres[0]['imageurl'] = $imageurl;
               }

               $imageDetails = $uploadres[0];
               echo json_encode($imageDetails);
           } else {
                echo print_r($uploadres, true);
           }
       }
   }


	function setCookieImage(){
		$name = $_REQUEST['cn'];
		$value = $_REQUEST['cv'];
		$time = $_REQUEST['ct'];
		$value = str_replace('ZG*PW1','?',$value);
		if($value == 'DELETECOOKIE'){
			$value='';
		}
		setcookie($name, $value,$time,'/',COOKIEDOMAIN);
		header('Content-Length:1');
        header('Content-Type: image/png');
		echo '';
	}

    function createJSRepos() {
        $jsPath = JS_PATH;
        $jsRepos = array();
        if ($handle = opendir($jsPath)) {
            while (false !== ($file = readdir($handle))) {
                if($file !== '.' && $file !== '..' && strpos($file, '.js')) {
                    $fileName = $jsPath.'/' . $file;
                    $fileSize = ceil(filesize($fileName)/1024);
                    if( $fileSize  < REPOS_SIZE) {
                        $jsRepos[str_replace('.js','',$file)]['code'] = file_get_contents($fileName);
                        $jsRepos[str_replace('.js','',$file)]['size'] = $fileSize;
                        echo $file .'<br/>';
                    }
                }
            }
            closedir($handle);
        }
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
        $categoryClient->fillJSRepos($jsRepos);
        echo "I'm Done";
    }

    function jsRepos($jsName) {
        $jsName = str_replace('.js','',$jsName);
        if($jsName == '') return false;
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
        $jsRepos = $categoryClient->getJSFromRepos();
        $jsCode = '';
        $jsArray = explode('--',$jsName);
        foreach($jsArray as $js) {
            $jsCode .= $jsRepos[$js]['code'];
        }
        header("Content-type: application/x-javascript");
        echo $jsCode;
    }

    function TestAoPArch() {
        $compArray = array(
            'js' => array('header', 'homePage', 'common', 'ratings', 'mail')
        );
        $this->load->view('common/AOPHeader', $compArray);
    }
    function TestAoPArch1() {
        $compArray = array(
            'js' => array('header', 'homePage', 'common', 'mail')
        );
        $this->load->view('common/AOPHeader', $compArray);
    }
    function TestAoPArch2() {
        $compArray = array(
            'js' => array('header', 'homePage', 'common', 'mail','relatedContent','ajax')
        );
        $this->load->view('common/AOPHeader', $compArray);
    }
    function loadMultipleOverlayContent($overlaynames,$data=''){
		/* Adding XSS cleaning (Nikita) */
		$overlaynames = $this->security->xss_clean($overlaynames);
		$data = $this->security->xss_clean($data);
		
		$this->load->library(array('ajax'));
        $contentArray = array();
        $overlayArray = explode('$$$',$overlaynames);
        foreach($overlayArray as $temp){
            $tempArray = explode('@@@',$temp);
            $overlayId = $tempArray[1];
            $overlayname = str_replace('-','/',$tempArray[0]);
            $content = false;
            if($overlayname != ''){
                $content = $this->load->view($overlayname,$data,true);
            }
            if($content != false){
                $contentArray[$overlayId] = $content;
            }
        }
        echo json_encode($contentArray);
    }
	
	function deleteCache($key)
	{
		$this->load->library('CacheLib');
		$cacheLib = new CacheLib;
		$cacheLib->clearCacheForKey($key);
	}
}

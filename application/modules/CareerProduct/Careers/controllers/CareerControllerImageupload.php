<?php
/*

   Copyright 2013 Info Edge India Ltd

   $Author: Pranjul

   $Id: CareerController.php

 */

class CareerController extends MX_Controller
{
	private $careerRepository;
	private $expressInterestFirst;
	private $expressInterestSecond;

        function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();
		}

	/*
	 @name: getCareerIds
	 @description: this is for getting Career Ids by passing in it 3 arguments.This function gets call inside applyAlgorithm function.
	 @param string $userInput: expressInterestFirst,expressInterestSecond,stream
	*/
	public function getCareerIds($expressInterestFirst,$expressInterestSecond,$stream)
	{	
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$this->careerRepository = $careerBuilder->getCareerRepository();
		$careerIds = $this->careerRepository->getCareerIds($expressInterestFirst,$expressInterestSecond,$stream);
		return $careerIds;
	}
	/*
	 @name: recommendCareerOption
	 @description: this is for Suggest Different Career Options.We are also setting some cookie in this function,they will use on career detail page.
	 @param string $userInput: expressInterestFirst,expressInterestSecond,stream
	*/
	public function recommendCareerOption()
        {    	
		$this->init();
		$finalArr['validateuser'] = $this->userStatus;
		
		$expressInterestObjectParams=(json_decode($_COOKIE['expressInterestDetail']));
		$expressInterestDetailsArray=array();
		if (is_object($expressInterestObjectParams)) {
		    $expressInterestDetailsArray = get_object_vars($expressInterestObjectParams);
		}
		
		if(!isset($_COOKIE['streamSelected'])){
			header('location:'.CAREER_HOME_PAGE);
			exit;
		}
		if(!isset($_COOKIE['expressInterestDetail'])){
			header('location:'.CAREER_EXPRESSINTEREST_PAGE);
			exit;
		}
		$stream = $_COOKIE['streamSelected'];
		$expressInterestFirst = $expressInterestDetailsArray['ei1'];
		$expressInterestFirstName = $expressInterestDetailsArray['display1'];
		$expressInterestSecond = $expressInterestDetailsArray['ei2'];
		$expressInterestSecondName = $expressInterestDetailsArray['display2'];
		

		$expressInterestIds = array();
		$careerIds = array();
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$this->careerRepository = $careerBuilder->getCareerRepository();
		$this->careerServiceRepository = $careerBuilder->getCareerService();
		$finalArr['result'] = $this->careerServiceRepository->getSuggestedCareers($expressInterestFirst,$expressInterestSecond,$stream);
		$finalArr['expressInterestFirstName'] = $expressInterestFirstName;
		$finalArr['expressInterestSecondName'] = $expressInterestSecondName;
		$finalArr['stream'] = $stream;
		$this->load->view('Careers/career-suggestion',$finalArr);
        }
	
	/*
	 @name: getCareerDetialPage
	 @description: this is for getting Career Details and this function gets call from inside Apply Algorithm function.
	 @param string $userInput: expressInterestFirst,expressInterestSecond,stream
	*/
	public function getCareerDetailPage($careerId){
		$this->init();
		$finalArr['validateuser'] = $this->userStatus;
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$this->careerRepository = $careerBuilder->getCareerRepository();
		$this->careerData = $this->careerRepository->find($careerId);
		$indiawhereToStudyCountArr = array();
		$abroadwhereToStudyCountArr = array();
		$this->load->library('CareerUtilityLib');
		$careerUtilityLib =  new CareerUtilityLib;
		if(empty($this->careerData)){
			//			$careerUtilityLib->careerErrorPage('404');
			header("Sorry, we couldn't find the page you requested.", true, 404);
			$this->load->view('Careers/404Page');
		}else{
			if(isset($_COOKIE['expressInterestDetail']) && isset($_COOKIE['streamSelected'])){
				$expressInterestObjectParams=(json_decode($_COOKIE['expressInterestDetail']));
				$expressInterestDetailsArray=array();
				if (is_object($expressInterestObjectParams)) {
				    $expressInterestDetailsArray = get_object_vars($expressInterestObjectParams);
				}
				if(!isset($_COOKIE['streamSelected'])){
					header('location:'.CAREER_HOME_PAGE);
					exit;
				}
				if(!isset($_COOKIE['expressInterestDetail'])){
					header('location:'.CAREER_EXPRESSINTEREST_PAGE);
					exit;
				}
			  }
				$stream = $_COOKIE['streamSelected'];
				$expressInterestFirst = $expressInterestDetailsArray['ei1'];
				$expressInterestSecond = $expressInterestDetailsArray['ei2'];
				//if((isset($expressInterestFirst) || isset($expressInterestSecond)) && isset($stream)){
					$this->careerServiceRepository = $careerBuilder->getCareerService();
					//$finalArr['recommendedOptions'] = $this->careerServiceRepository->getRecommendedCareers($expressInterestFirst,$expressInterestSecond,$stream,$careerId);
					$finalArr['recommendedOptions'] = $this->careerServiceRepository->getRecommendedCareers($careerId);
				//}
				//else{
					//$finalArr['recommendedOptions']=array();
				//}
			//}
			$careerInformation = $this->careerData->getOtherCareerInformation();
			$finalArr['metaTagsDescription'] = $careerInformation['metaTagsDescription'];
			$finalArr['metaTagsKeywords'] = $careerInformation['metaTagsKeywords'];
			$finalArr['instituteDetailIndia'] = $careerUtilityLib->formatCareerSectionAndCourseIds('india',$careerInformation);
			$finalArr['instituteDetailAbroad'] = $careerUtilityLib->formatCareerSectionAndCourseIds('abroad',$careerInformation);
			$finalArr['result'] = $this->careerData;
			$this->load->view('Careers/careerDetailPage',$finalArr);
		}

	}
	
		/*******************************************************************************************************
    This function is used to call model to get career list for auto suggestor and get streams from the config file
    and display the CAREER HOMEPAGE. 
    *****************************************************************************************/
    
    function getCareerHomepage(){
		$this->init();
		$this->load->library('CareerConfig');
		$streamArray=CareerConfig::$streamVariables;
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$this->careerRepository = $careerBuilder->getCareerRepository();
		$careersList = $this->careerRepository->getCareerList();
		$stringOfCareerTitles = '';
		$stringOfCareersSeoUrl = '';
		
		foreach ($careersList as $career){
		    $careerTitle = addslashes($career->getName());
		    $stringOfCareerTitles .= ($stringOfCareerTitles=='')?"'$careerTitle'":",'$careerTitle'";
		    $careerSeoUrl = $career->getSeoUrl();
		    $stringOfCareersSeoUrl .= ($stringOfCareersSeoUrl=='')?"'$careerSeoUrl'":",'$careerSeoUrl'";
		}
		
		$data['stringOfCareerTitles'] = $stringOfCareerTitles;
		$data['stringOfCareerUrl'] = $stringOfCareersSeoUrl;
		$data['validateuser'] = $this->userStatus;
		$data['streamArray']=$streamArray;
		$data['streamArrayLength'] =count($streamArray);
		
		$this->load->view('Careers/career-homepage',$data);
        }

    /******************************************************************
    This function is used to call model to get express interest details
    and display the CAREER EXPRESS PAGE. 
    *******************************************************************/
     
       function getExpressInterestPage() {
		$this->init();
		$this->load->model('Careers/careermodel');
		$this->careerModel = new Careermodel();	
		$expressInterestDetails = $this->careerModel->getExpressInterestDetails();
		
		$data['validateuser'] = $this->userStatus;
		$data['expressInterestDetails'] = $expressInterestDetails;
		$stream = $_COOKIE['streamSelected'];
		if($stream==''){
			header('location:'.CAREER_HOME_PAGE);
			exit;
		}
		$data['stream']=$stream;
		
		$this->load->view('Careers/career-express',$data);
	}

	function errorPage($page='')
	{
		$this->load->view('Careers/'.$page.'Page');
	}
	
	function getCareersListForFooterOnHomePage($startCountSearch=0,$countOffsetSearch=60){
		$this->init();
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$this->careerRepository = $careerBuilder->getCareerRepository();
		$careersList = $this->careerRepository->getCareerList();
		
		$i=1;
		foreach ($careersList as $career){
		    $careerName = $career->getName();
		    $careerSeoUrl = $career->getSeoUrl();
		    $arrayOfCareersSeoUrl[$i]['name']=$careerName;
		    $arrayOfCareersSeoUrl[$i]['url']=$careerSeoUrl;
		    $i++;
		}
		$totalRows=count($arrayOfCareersSeoUrl);
		$arrayOfCareersSeoUrl=$this->getLimitedCarrersAccordingToPagination($startCountSearch,$countOffsetSearch,$arrayOfCareersSeoUrl);
	
		$data['arrayOfCareersSeoUrl'] = $arrayOfCareersSeoUrl;
		$data['validateuser'] = $this->userStatus;
		$paginationURL = SHIKSHA_HOME."/careers-after-12th-list/@start@/@count@";
		$totalCount = $totalRows;
		$paginationHTML = doPagination($totalCount,$paginationURL,$startCountSearch,$countOffsetSearch,3);
		$data['paginationHTML'] = $paginationHTML;
		$this->load->view('Careers/careersList',$data);
	}
	
	function getLimitedCarrersAccordingToPagination($startCountSearch,$countOffsetSearch,$arrayOfCareersSeoUrl){
		$i=$startCountSearch+1;
		foreach($arrayOfCareersSeoUrl as $key=>$value){
			if($i<=$startCountSearch+$countOffsetSearch && $i<=count($arrayOfCareersSeoUrl)){
				$url=$value['url'];
					$resultArrayOfCareersSeoUrl[$i]['url']=$arrayOfCareersSeoUrl[$i]['url'];
					$resultArrayOfCareersSeoUrl[$i]['name']=$arrayOfCareersSeoUrl[$i]['name'];
					$i++;
			}
		}
		return $resultArrayOfCareersSeoUrl;
	}

    function fbAppDemo(){
        exit;
		require 'facebook.php';

			$app_id = '191415211010732';
			$app_secret = 'd712432b1cc3f87e96cc977953f108d4';
			$app_namespace = 'myavator';
			$app_url = 'https://apps.facebook.com/' . $app_namespace . '/';
			$scope = 'user_photos,publish_actions,publish_stream';
		
			// Init the Facebook SDK
			$facebook = new Facebook(array(
				 'appId'  => $app_id,
				 'secret' => $app_secret,
		));
		
		// Get the current user
		$user = $facebook->getUser();
		error_log($user);
		// If the user has not installed the app, redirect them to the Login Dialog
		if (!$user) {
				$loginUrl = $facebook->getLoginUrl(array(
				'scope' => $scope,
				'redirect_uri' => $app_url,
				));
				print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
				exit;
		}
		$file = rand(10000,100000).'file.jpg';
		copy('https://graph.facebook.com/'.$user.'/picture?width=400', '/var/www/html/shiksha/mediadata/'.$file);
		$face = $this->detectFace('https://graph.facebook.com/'.$user.'/picture?width=400');
		$this->load->view('Careers/fbApp',array('file'=>$file,'face'=>$face));
    }
	
	function detectFace($url){
		copy($url, '/tmp/face.jpeg');
		
		$face = face_detect('/tmp/face.jpeg', '/var/www/html/shiksha/application/modules/CareerProduct/Careers/controllers/haarcascade_frontalface_alt.xml');
		if(count($face) == 0){
			error_log("level 2 hit");
			$face = face_detect('/tmp/face.jpeg', '/var/www/html/shiksha/application/modules/CareerProduct/Careers/controllers/haarcascade_frontalface_default.xml');
		}
		return $face;
	}

    function uploadImage(){
        error_log(print_r($_FILES,true));
		include "FaceDetector.php";
        if(isset($_FILES['fbImage'])){
            $type=$_FILES['fbImage']['name'];
            $size=$_FILES['fbImage']['size'];
            $this->load->library('upload_client');
            $uploadClient = new Upload_client();

            $fileName = explode('.',$_FILES['fbImage']['name']);
            $fileNameToBeAdded = $fileName[0];
            $fileCaption= $fileNameToBeAdded;
            $fileExtension = $fileName[count($fileName) - 1];
            $fileCaption .= $fileExtension == '' ? '' : '.'. $fileExtension;
            $FILES = array();
            $FILES['userApplicationfile']['type'][0] = $_FILES['fbImage']['type'];
            $FILES['userApplicationfile']['name'][0] = $_FILES['fbImage']['name'];
            $FILES['userApplicationfile']['tmp_name'][0] = $_FILES['fbImage']['tmp_name'];
            $FILES['userApplicationfile']['error'][0] = $_FILES['fbImage']['error'];
            $FILES['userApplicationfile']['size'][0] = $_FILES['fbImage']['size'];
            $upload_forms = $uploadClient->uploadFile(1,'image',$FILES,array($fileCaption),1121, 'facebook','userApplicationfile');
            error_log("Returned array==========".print_r($upload_forms,true));
			$protocol = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http';
			if($protocol == 'https'){
				$output['url'] =  str_replace('http://localshiksha.com:80','https://localshiksha.com',$upload_forms[0]['imageurl']);
			}else{
				$output['url'] =  $upload_forms[0]['imageurl'];
			}
			$rectangles = face_detect('face', 'haarcascade_frontalface_alt.xml');
			//$detector = new Face_Detector('/var/www/html/shiksha/application/modules/CareerProduct/Careers/controllers/detection.dat');
			$output['face'] = $this->detectFace($output['url']);
			echo json_encode($output);
        }
    }
	
	
	function mergeImages($width,$height,$left,$top,$url){
		
		list($orig_width, $orig_height) = getimagesize(url_base64_decode($url));
		$url = url_base64_decode($url);
		
		/* Resize original Image */
		$image_p = ImageCreateTrueColor($width, $height);
		$image = imagecreatefromstring(file_get_contents($url));
		imagecopyresampled($image_p, $image, 0, 0, 0, 0,
                                     $width, $height, $orig_width, $orig_height);
		
		
		
		/* Make face of avator */
		$image_c = ImageCreateTrueColor(77, 104);
		imagecopy($image_c, $image_p, $left, $top, 0, 0, $width, $height);	
		
		/*Get the frame of avator */
		$frame = imagecreatefrompng('http://localshiksha.com/public/images/avatarFB.png');
		list($frame_width, $frame_height) = getimagesize('http://localshiksha.com/public/images/avatarFB.png');
		
		
		/* Create a frame size Image */
		$image_f = imagecreatetruecolor($frame_width, $frame_height);
		imagealphablending($image_f, false);
		$col=imagecolorallocatealpha($image_f,255,255,255,127);
		//Create overlapping 100x50 transparent layer
		imagefilledrectangle($image_f,0,0,$frame_width, $frame_height,$col);
		//Continue to keep layers transparent
		imagealphablending($image_f,true);
		imagesavealpha($image,true);
		imagecopymerge($image_f, $image_c, 47, 38, 0, 0, 77, 104, 100);
		

		/* Merge all */
		imagecopy( $image_f,$frame, 0, 0, 0, 0, $frame_width, $frame_height);
		
		
		header("Content-type: image/png");
		//header('Content-Disposition: attachment; filename=avator_'.date("Ymd").'_'.date("Gis").'.png');
		imagepng($image_f);

	}

}
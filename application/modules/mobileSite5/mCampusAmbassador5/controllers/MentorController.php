<?php
/**
 *Copyright 2015-16 Info Edge India Ltd
 *$Author: Akhter (UGC Team)
 *$Id: Mentor Controller
 **/
class MentorController extends ShikshaMobileWebSite_Controller 
{

        private $userStatus;
	
	function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		$this->userStatus = $this->checkUserValidation();
	
		$this->load->model('CA/mentormodel');
		$this->mentormodel = new mentormodel();
		
		$this->load->config('CA/MentorConfig',TRUE);
		$this->mentorConfig = $this->config->item('settings','MentorConfig');
	}

	/**
	 * Mentor Homapage
	 * @param  : None
	 * @return : None
	 * @author : akhter
	 */
	function mentorHomepage($url){
		redirect(SHIKSHA_HOME."/tags/engineering-tdp-20",'location',301);exit;
		$this->init();
		$displayData = array();
	
		$addCategoryUrl = explode('-',$url);
		$addCategoryUrl = $addCategoryUrl[1];
	
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		
		$subcatId = 56;
		$subCatName  =  ($subcatId == 56) ? 'Engineering' : 'MBA';
		$displayData['subcatId'] = $subcatId;
				
		//Google Remarketing Code
                $googleRemarketingParams = array(
                                "categoryId"    => '2',
                                "subcategoryId" => '56',
                                "countryId"     => '2',
                                "cityId"        => ''
                );
                $displayData['googleRemarketingParams'] = $googleRemarketingParams;
		
		//Get SEO Details
                $displayData['m_meta_title'] = 'Mentors for '.ucfirst($addCategoryUrl).' Exams, Colleges & Courses - Shiksha.com';
                $displayData['m_meta_description'] = 'Get a dedicated student mentor to guide for Engineering exams, selecting colleges and courses. Connect with current '.$addCategoryUrl.' students for entire '.$addCategoryUrl.' preparation.';
                $displayData['canonicalURL'] = SHIKSHA_HOME.'/mentors-'.$addCategoryUrl.'-exams-colleges-courses';
		
		$displayData['boomr_pageid'] = 'mentorShipHome';
		
		$this->load->library('listing/cache/ListingCache');
		$this->load->library('category_list_client');
		$this->listingCache = new ListingCache;
		$categoryClient     = new Category_list_client();
		$exam_list = $this->listingCache->getExamsList();
	        if(empty($exam_list)){
	            $exam_list = $categoryClient->getTestPrepCoursesList(1);
	            $this->listingCache->storeExamsList($exam_list);
	        }
		$exam_list = $this->prepareMExamList($exam_list);
		$displayData['exam_list'] = $exam_list[$subCatName];
		
		$displayData['totalMentor'] = $this->mentormodel->getMentorDetails($subcatId);
                $displayData['totalMentor']['result'] = array_slice($displayData['totalMentor']['result'],0,4);
                $this->_prepareMentorList($displayData);

		$displayData['branchList'] = $this->mentorConfig['mentorBranchName'];
		$displayData['isMentee']  = $this->existMentee();
		
		//below code used for beacon purpose
		$displayData['trackingpageIdentifier']='mentorshipPage';		
		$displayData['trackingsubCatID']=$subcatId;
		$displayData['trackingcatID']=2;
		$displayData['trackingcountryId']=2;
		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');

		$this->tracking->_pagetracking($displayData);

		//below line is used for conversion tracking purpose
		
		if(isset($_GET['tracking_keyid']))
		{
			$displayData['trackingPageKeyId']=$_GET['tracking_keyid'];
		}
		else
		{
			$displayData['trackingPageKeyId']=287;
		}
		$displayData['bottomTrackingPageKeyId']=520;

		
		$this->load->view('mentorship/mentorHomepage',$displayData);
	}

        private function _prepareMentorList(&$displayData) {
                $mentorListData = $displayData['totalMentor']['result'];

                $this->load->builder('ListingBuilder','listing');
                $listingBuilder  = new ListingBuilder;
                $insRepo = $listingBuilder->getInstituteRepository();
                $courseRepo = $listingBuilder->getCourseRepository();

                $this->load->builder('LocationBuilder','location');
                $locationBuilder  = new LocationBuilder;
                $locationRepo = $locationBuilder->getLocationRepository();

                $this->load->model('CAEnterprise/caenterprisemodel');
                $enterpriseModel = new caenterprisemodel;

                foreach($mentorListData as $key=>$listData)
                {
                        $mentorListData[$key]['menteeCount'] = $enterpriseModel->findMenteeCount($listData['userId']);

                        if($listData['instituteId'] > 0) {
                        $insObj = $insRepo->find($listData['instituteId']);
                        }
                        if($listData['courseId'] > 0) {
                        $courseObj = $courseRepo->find($listData['courseId']);
                        }
                        if($listData['locationId'] > 0){
                        $insLoc = $insObj->getLocations();
                        }
                        $insLocObj = $insLoc[$listData['locationId']];
                        if(empty($insLocObj)){
                                unset($mentorListData[$key]);
                                continue;
                        }

                        $cityObj = $insLocObj->getCity();

                        $mentorListData[$key]['instituteName'] = $insObj->getName();
                        $mentorListData[$key]['instituteURL'] = $insObj->getURL();
                        $mentorListData[$key]['courseName'] = $courseObj->getName();
                        $mentorListData[$key]['courseURL'] = $courseObj->getURL();
                        $mentorListData[$key]['city'] = $cityObj->getName();
                }
                $displayData['mentorListData'] = $mentorListData;
                $displayData['pageNo'] = isset($displayData['pageNo']) ? $displayData['pageNo'] : 0;
                $displayData['mentorCount'] =  $displayData['totalMentor']['totalMentor'];
        }

        function getMentorListAjax($pageNo,$subcatId) {
                $this->init();
                $displayData['pageNo'] = $pageNo;
                $displayData['subcatId'] = $subcatId;
                $displayData['totalMentor']  = $this->mentormodel->getMentorDetails($subcatId);
                $displayData['totalMentor']['result'] = array_slice($displayData['totalMentor']['result'],$pageNo*4,4);
                $this->_prepareMentorList($displayData);
                echo $this->load->view('mentorship/studentMentorTupleList',$displayData,true);
        }
	
	function prepareMExamList($exam_list = array()) {
		$final_exam_list = array();
		if(count($exam_list) >0) {
			foreach ($exam_list as $list) {
				foreach ($list as $list_child) {
					$final_exam_list[$list_child['acronym']][] = $list_child['child']['acronym'];
				}
			}
		}
		//Entry for no exam required in MBA
		if(!empty($final_exam_list['MBA'])){
			$final_exam_list['MBA'][] = "No Exam Required";
		}
		return $final_exam_list;
	}

	/***
	 * functionName : existMentee
	 * functionType : return type
	 * param        : POST type
	 * desciption   : this function is used to check exist mentee for exist user
	 * @author      : akhter
	 * @team        : UGC
	***/
	
	function existMentee()
	{
		$this->init();
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;				
		$res = $this->mentormodel->existMentee($userId);
		if($res>0)
		{
			return true;
		}
		return false;
	}
	
	function prepareLocationLayer()
	{
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		$cityList = $locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
		$data['cityList'] = $cityList;
		$data['locationRepository'] = $locationRepository;
		echo $this->load->view('mentorship/menteeLocationLayer',$data);
	}
	
	/***
	 * functionName : addMenteeData
	 * functionType : return type
	 * param        : POST type
	 * desciption   : this function is used on callback from mobile registration,used as module run and ajax
	 * @author      : akhter
	 * @team        : UGC
	***/
	function addMenteeData($userId = 0)
	{
		$userId = ($_POST['userId']) ? $this->input->post('userId') : $userId;
		$isMentee = $this->existMentee();
		
		if($userId >0 && $_COOKIE['menteeData'] !='' && $isMentee < 1)
		{
			$menteeData = explode('&',$_COOKIE['menteeData']);
		
			$examYr = explode('=',$menteeData[0]);
			$examYr = $examYr[1];
			
			$targetClg = explode('=',$menteeData[1]);
			$targetClg = $targetClg[1];
			
			$branchPref1 = explode('=',$menteeData[2]);
			$branchPref1 = $branchPref1[1];
			
			$branchPref2 = explode('=',$menteeData[3]);
			$branchPref2 = $branchPref2[1];
			
			$branchPref3 = explode('=',$menteeData[4]);
			$branchPref3 = $branchPref3[1];
			
			$prefC1 = explode('=',$menteeData[5]);
			$prefC1 = $prefC1[1];
			
			$prefC2 = explode('=',$menteeData[6]);
			$prefC2 = $prefC2[1];
			
			$prefC3 = explode('=',$menteeData[7]);
			$prefC3 = $prefC3[1];
			
			$examList = explode('=',$menteeData[8]);
			$examList = explode(',',$examList[1]);
			
			foreach($examList as $exam){
			    $examListArr[] = $exam;
			}
			
			$examScore = explode('=',$menteeData[9]);
			$examScore = explode(',',$examScore[1]);
			
			foreach($examScore as $Score){
			    $examScoreArr[] = $Score;
			}
			
			$this->load->model('CA/mentormodel');
			$this->mentormodel = new mentormodel();
			$menteeId = $this->mentormodel->addMentee($userId,$prefC1,$prefC2,$prefC3,$examYr,$branchPref1,$branchPref2,$branchPref3,$targetClg);
			if($menteeId>0)
			{       if(count($examListArr)>0)
				{
					foreach($examListArr as $key=>$examName){
						$res = $this->mentormodel->addMenteeExam($menteeId,$examName,$examScoreArr[$key]);
					}
				}
				
				setcookie('menteeData','',time()-3600,'/',COOKIEDOMAIN);
				setCookie('openMenteeLayer' ,'1',0 ,'/',COOKIEDOMAIN);
				$this->load->library('CA/MentorMailers');
				$mentorMailer = new MentorMailers;
				$mentorMailer->sendMenteeRegistrationMailertoMentee($menteeId);
				echo 'success';
			}
		}else{
			setcookie('menteeData','',time()-3600,'/',COOKIEDOMAIN);
			setCookie('openMenteeLayer' ,'',time()-3600,'/',COOKIEDOMAIN);
			echo 'existUser';
		}
	}

	function getMentorInlineWidget($frompage, $categoryId, $count,$trackingPageKeyId=''){
		if(!isset($categoryId)){
			$categoryId = 56;
		}
		if(!isset($count)){
			$count=10;
		}
		$displayData['isMentee']  = $this->existMentee();
		$displayData['frompage'] = $frompage;
		$displayData['totalMentorCount'] = $this->load->mentormodel->getTotalMentorCount($categoryId);
		$displayData['count'] = $count;
		$displayData['trackingPageKeyId']=$trackingPageKeyId;
		$this->load->view('mentorship/studentMentorInlineWidget',$displayData);
	}
	
}?>

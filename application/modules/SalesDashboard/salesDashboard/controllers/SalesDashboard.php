<?php

class SalesDashboard extends MX_Controller
{
	
	public function __construct(){
		parent::__construct();
		$this->salesDashboardLib = $this->load->library("salesDashboard/SalesDashboardLib");
		$this->config->load('studyAbroadCMSConfig');
	}

	/**
    * Purpose : Method to validate the user and do the necessary action(s)
    * Params  :	none
    * Author  : none
    */
    function salesAbroadUserValidation($noRedirectionButReturn = false){
        $usergroupAllowed 	= $this->usergroupAllowed;
        $validity 		= $this->checkUserValidation();	    
        global $logged;
        global $userid;
        global $usergroup;
        $thisUrl 		= $_SERVER['REQUEST_URI'];
        $errorType 		= "";
        if(($validity == "false" )||($validity == ""))
        {
            $logged = "No";
            if(!$noRedirectionButReturn){
            
                    header('location:/enterprise/Enterprise/loginEnterprise');
                    exit();
            } else {
                    $errorType = "notloggedin";
            }
        }
        else
        {
                $logged 		= "Yes";
                $userid 		= $validity[0]['userid'];
                $usergroup 		= $validity[0]['usergroup'];
                $displayname 	= $validity[0]['displayname'];
                if(!in_array($usergroup,$usergroupAllowed))
                {
                    if(!$noRedirectionButReturn)
                    {
                            header("location:/enterprise/Enterprise/disallowedAccess");
                            exit();	
                    }
                    else
                    {
                            $errorType = "disallowedaccess";
                    }
                }
        }
        $returnArr['userid']		= $userid;
        $returnArr['usergroup']		= $usergroup;
        $returnArr['logged'] 		= $logged;
        $returnArr['thisUrl'] 		= $thisUrl;
        $returnArr['validity'] 		= $validity;
        $returnArr['displayname'] 	= $displayname;
            
        if(!empty($errorType)){
                $returnArr['error'] 		= "true";
                $returnArr['error_type'] 	= $errorType;
        }
        return $returnArr;
    }

	public function index(){
		$this->clientDeliveryDashboard();
	}

	public function clientDeliveryDashboard(){
		$this->usergroupAllowed = array('saAdmin','saCustomerDelivery','saShikshaApply');
		$displayData = $this->salesAbroadUserValidation();
		$data = array();
		$universities = $this->salesDashboardLib->getClientDeliveryUniversities();
		foreach($universities as $row){
			$data[$row['universityId']]['name'] = $row['name'];
		}
		$courseIds = array();
		$coursesByUniversity = $this->_getUniversityCourseDetailsForClientDelivery($data);
		$this->_removeInactiveUniversities($data);
		$this->_getUniversityShikshaApplyDetailsForClientDelivery($data,$coursesByUniversity);
		$filterData = $this->_prepareDataForClientDeliveryFilters($data);
		uasort($data, function($a,$b){return strtotime($a['campaignEndDate'])-strtotime($b['campaignEndDate']);});
		$displayData['filterData'] = $filterData;
		$displayData['data'] = $data;
		$displayData['selectLeftNav']   = "CLIENT_DELIVERY";
		$displayData['formName'] = ENT_SA_CUSTOMER_DELIVERY;
		$this->load->view('salesDashboardOverview',$displayData);
	}

	private function _getUniversityCourseDetailsForClientDelivery(& $data){
		$coursesByUniversity = $this->salesDashboardLib->getCoursesOfUniversities(array_keys($data));
		$universityCountries = $coursesByUniversity['countries'];
		$coursesByUniversity = $coursesByUniversity['courses'];
		$courseIds = array();
		foreach($coursesByUniversity as $uid => $cids){
			$courseIds = array_merge($courseIds,$cids);
		}
		$subDetails = $this->salesDashboardLib->getCourseSubscriptionDetails($courseIds);
		foreach($data as $univId => $universityData){
			$sDate = $eDate = '-';
			$paidCoursesCount = 0;
			$paidResponsesCount = 0;
			$rmcExamUsers = 0;
			foreach($coursesByUniversity[$univId] as $courseId){
				if($subDetails[$courseId]){
					$nsDate = strtotime($subDetails[$courseId]['SubscriptionStartDate']);
					$neDate = strtotime($subDetails[$courseId]['SubscriptionEndDate']);
					$sDate = ($sDate === "-")?$nsDate:min($sDate,$nsDate);
					$eDate = ($eDate === "-")?$neDate:max($eDate,$neDate);
					$paidCoursesCount +=1;
					$data[$univId]['salesPerson'] = $subDetails[$courseId]['salesPerson'];
				}
			}
			$data[$univId]['campaignStartDate'] = ($sDate === "-")?$sDate:date('d M Y',$sDate);
			$data[$univId]['campaignEndDate'] = ($eDate === "-")?$eDate:date('d M Y',$eDate);
			$data[$univId]['paidCoursesCount'] = $paidCoursesCount;
			$data[$univId]['paidResponsesCount'] = $paidResponsesCount;
			$data[$univId]['rmcExamResponses'] = $rmcExamUsers;
			$data[$univId]['country'] = $universityCountries[$univId];
		}
		return $coursesByUniversity;
	}

	private function _removeInactiveUniversities(& $data){
		foreach($data as $univId => $record){
			if(!(strtotime($record['campaignStartDate']) < time() && strtotime($record['campaignEndDate']) > time())){
				unset($data[$univId]);
			}
		}
	}

	private function _getUniversityShikshaApplyDetailsForClientDelivery(& $data,$coursesByUniversity){
		$univIds = array_keys($coursesByUniversity);
		$univCounsellors = $this->salesDashboardLib->getCounsellorsOfUniversities($univIds);
		foreach($data as $univId => $univData){
			$data[$univId]['submitted'] = 0;
			$data[$univId]['accepted'] = 0;
			$data[$univId]['rejected'] = 0;
			$data[$univId]['finalizedCourses'] = 0;
			$data[$univId]['sopUnderReview'] = 0;
			$data[$univId]['counsellorName'] = $univCounsellors[$univId];
		}
	}

	private function _prepareDataForClientDeliveryFilters($data){
		$countries = array();
		$counsellors = array();
		$salesReps = array();
		foreach($data as $univId => $univData){
			$countries[$univData['country']] = $univData['country'];
			$counsellors[$univData['counsellorName']] = $univData['counsellorName'];
			$salesReps[$univData['salesPerson']] = $univData['salesPerson'];
		}
		asort($countries);
		asort($counsellors);
		asort($salesReps);
		return array('countries'=>$countries,'counsellors'=>$counsellors,'salesReps'=>$salesReps);
	}

	public function getRemainingDataForUniversities(){
		$this->usergroupAllowed = array('saAdmin','saCustomerDelivery','saShikshaApply');
		$displayData = $this->salesAbroadUserValidation(true);
		if($displayData['error'] == "true"){
			echo json_encode(array('error'=>'true'));
		}
		$univIds = $this->input->post("univIds");
		$coursesByUniversity = $this->salesDashboardLib->getCoursesOfUniversities($univIds)['courses'];
		$courseIds = array();
		foreach($coursesByUniversity as $uid => $cids){
			$courseIds = array_merge($courseIds,$cids);
		}
		$coursePaidResponses = $this->salesDashboardLib->getCoursePaidResponses($courseIds);
		$courseRMCExamUserData = $this->salesDashboardLib->getCourseRMCExamUserData($courseIds);
		$univPaidResponses = array();
		$univRMCUsers = array();
		$univFinalizedCourses = array();
		$univFinalizedCounts = array();
		$sopFinalizedCounts = array();
		$coursesFinalized = $this->salesDashboardLib->getCoursesFinalizedForUsers();
		$userIds = array();
		foreach($coursesFinalized as $courseId => $cdata){
			$userIds = array_merge($userIds,array_keys($cdata));
		}
		$userIds = array_unique($userIds);
		$sopFinalizedUsers = $this->salesDashboardLib->getSOPFinalizedUsers($userIds);
		foreach($univIds as $univId){
			$paidResponsesCount = $rmcExamUsers = 0;
			$univFinalizedCount = $sopFinalizedCount = 0;
			$univFinalizedCourses[$univId]['submitted'] = $univFinalizedCourses[$univId]['accepted'] = $univFinalizedCourses[$univId]['rejected'] = 0;
			foreach($coursesByUniversity[$univId] as $courseId){
				if($coursePaidResponses[$courseId]){
					$paidResponsesCount += $coursePaidResponses[$courseId]['responseCount'];
				}
				if($coursesFinalized[$courseId]){
					//_p($coursesFinalized[$courseId]);
					$univFinalizedCount+=count($coursesFinalized[$courseId]);
					foreach($coursesFinalized[$courseId] as $userId => $admissionStage){
						if(in_array($userId, $sopFinalizedUsers)){
							$sopFinalizedCount+=1;
							$univFinalizedCourses[$univId][$admissionStage] = ($univFinalizedCourses[$univId][$admissionStage])?$univFinalizedCourses[$univId][$admissionStage]+1:1;
						}
					}
				}
				$rmcExamUsers+= $courseRMCExamUserData[$courseId];
			}
			$univPaidResponses[$univId] = $paidResponsesCount;
			$univRMCUsers[$univId] = $rmcExamUsers;
			$univFinalizedCounts[$univId] = $univFinalizedCount;
			$sopFinalizedCounts[$univId] = $sopFinalizedCount;
		}
		echo json_encode(
			array(
				'univPaidResponseCount'=>$univPaidResponses,
				'univRMCExamUserCount'=>$univRMCUsers,
				'univFinalizedCourses'=>$univFinalizedCourses,
				'univFinalizedCounts'=>$univFinalizedCounts,
				'sopFinalizedCounts'=>$sopFinalizedCounts
			)
		);
	}
}
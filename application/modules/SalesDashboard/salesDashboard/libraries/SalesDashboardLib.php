<?php
class SalesDashboardLib {

	public function __construct(){
		$this->CI = &get_instance();
		$this->salesdashboardmodel = $this->CI->load->model("salesDashboard/salesdashboardmodel");
	}

	public function getClientDeliveryUniversities(){
		return $this->salesdashboardmodel->getClientDeliveryUniversities();
	}

	public function getCoursesOfUniversities($univIds){
		$result = $this->salesdashboardmodel->getCoursesOfUniversities($univIds);
		$uniCourses = array();
		$uniCountries = array();
		foreach($result as $row){
			$uniCourses[$row['university_id']][$row['course_id']] = $row['course_id'];
			$uniCountries[$row['university_id']] = $row['country_id'];
		}
		$countryNames = $this->salesdashboardmodel->getNamesOfCountries(array_unique($uniCountries));
		foreach($uniCountries as $id=>$countryId){
			$uniCountries[$id] = $countryNames[$countryId];
		}

		return array('courses'=>$uniCourses,'countries'=>$uniCountries);
	}

	/* Note : This function only returns gold subscriptions for courses! */
	public function getCourseSubscriptionDetails($courseIds){
		$this->CI->load->library('subscription_client');
		$subscriptionClient = new Subscription_client();
		$subscriptionDetails = $this->salesdashboardmodel->getSubscriptionIdsForCourses($courseIds);
		$clients = $subscriptionDetails['clients'];
		$clientIds = array_unique($clients);
		$salesPersonInfo = $subscriptionClient->sgetSalesPersonInfo($clientIds);
		$subscriptionIds = $subscriptionDetails['ids'];
		$subscriptionDetails = array();
		$uniqueSubscriptionIds = array_unique($subscriptionIds);
		$details = $subscriptionClient->getMultipleSubscriptionDetails(1,$uniqueSubscriptionIds,true);
		foreach($details as $row){
			$subscriptionDetails[(integer)$row['SubscriptionId']] = $row;
		}
		$courseSubscriptionDetails = array();
		foreach($courseIds as $courseId){
			$subId = $subscriptionIds[$courseId];
			$subDetail = $subscriptionDetails[$subId];
			if(!empty($subDetail['SubscriptionId'])){
				if($subDetail['BaseProductId'] != GOLD_SL_LISTINGS_BASE_PRODUCT_ID){
					continue;
				}
				$courseSubscriptionDetails[$courseId] = $subDetail;
				$courseSubscriptionDetails[$courseId]['salesPerson'] = $salesPersonInfo[$clients[$courseId]]['displayName'];
			}
		}
		return $courseSubscriptionDetails;
	}

	public function getCoursePaidResponses($courseIds){
		$res = $this->salesdashboardmodel->getCoursePaidResponses($courseIds);
		$result = array();
		foreach($res as $row){
			$result[$row['courseId']]['responseCount'] = $row['responseCount'];		
		}
		return $result;
	}

	public function getCourseRMCExamUserData($courseIds){
		$res = $this->salesdashboardmodel->getRMCUsers($courseIds);
		$courseUserMapping = array();
		$userIds = array();
		foreach($res as $row){
			$courseUserMapping[$row['courseId']][] = $row['userId'];
			$userIds[$row['userId']] = $row['userId'];
		}
		$examsForUsers = $this->salesdashboardmodel->getExamsForUsers($userIds);
		$this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
		$abroadExams = $this->abroadCommonLib->getAbroadExamsMasterList();
		$abroadExams = array_map(function($ele){return $ele['exam'];},$abroadExams);
		foreach($userIds as $key=>$userId){
			$abroadExamFlag = false;
			$exams = $examsForUsers[$userId];
			foreach($exams as $examName){
				if(in_array($examName, $abroadExams)){
					$abroadExamFlag = true;
				}
			}
			if(!$abroadExamFlag){
				unset($userIds[$userId]);
			}
		}
		$userIds = array_keys($userIds);
		$finalData = array();
		foreach($courseUserMapping as $courseId => $userArray){
			$finalData[$courseId] = 0;
			foreach($userArray as $userId){
				if(in_array($userId, $userIds)){
					$finalData[$courseId]+=1;
				}
			}
		}
		return $finalData;
	}

	public function getCoursesFinalizedForUsers(){
		$courseFinalizedData = $this->salesdashboardmodel->getCoursesFinalizedForUsers();
		$finalData = array();
		foreach($courseFinalizedData as $row){
			$finalData[$row['courseId']][$row['userId']] = $row['admissionOffered'];
		}
		return $finalData;
	}

	public function getSOPFinalizedUsers($userIds){
		$data = $this->salesdashboardmodel->getSOPFinalizedUsers($userIds);
		foreach($data as $ele){
			$ids[] = reset($ele);
		}
		return $ids;
	}

	public function getCounsellorsOfUniversities($univIds){
		$data = $this->salesdashboardmodel->getCounsellorsOfUniversities($univIds);
		return $data;
	}

}
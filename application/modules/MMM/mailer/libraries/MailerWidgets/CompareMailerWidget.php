<?php
include_once('MailerWidgetAbstract.php');

class CompareMailerWidget extends MailerWidgetAbstract
{
	private $instituteRepository;
	private $courseRepository;
	
	function __construct(MailerModel $mailerModel,InstituteRepository $instituteRepository,CourseRepository $courseRepository)
	{
		parent::__construct($mailerModel);
		$this->instituteRepository = $instituteRepository;
		$this->courseRepository = $courseRepository;
	}
	
	/**
	 * API for getting comparison widget data
	 */
	public function getData($userIds, $params = "",$processingParams = "")
	{			
		$widgetData = array();
		
		$comparisonData = $this->getCompareData($userIds,$processingParams);
		
		foreach($userIds as $userId) {
			if($comparisonData[$userId]) {
				$displayData = array('compare' => $comparisonData[$userId]);
				foreach($params as $key => $value) {
					if ($value == 'CompareMailerText') {
						$widegtHTML = $this->CI->load->view('MailerWidgets/CompareMailerText',$displayData,true);
						$widgetData[$userId][$value] = $widegtHTML;
					}
					else if ($value == 'CompareMailerTable') {
						$widegtHTML = $this->CI->load->view('MailerWidgets/compareTableTemplate',$displayData,true);
						$widgetData[$userId][$value] = $widegtHTML;
					}
					else if ($value == 'ComparisonPrimaryInstituteTitle') {
						$widgetData[$userId][$value] = $displayData['compare'][0]['InstituteTitle'];
					}
					else if ($value == 'ComparisonSecondaryInstituteTitle') {
						$widgetData[$userId][$value] = $displayData['compare'][1]['InstituteTitle'];
					}
				}
			}
			else {
				foreach($params as $key => $value){
					$widgetData[$userId][$value] = '';
				}
			}
		}
		
		return $widgetData;
	}
	
	public function getCompareData($userIds,$processingParams)
	{
		$data = array();
		
		if(!$processingParams['timeWindow']) {
			return $data;
		}
		
		//added by akhter
		//get dashboard config for online form
		$national_course_lib = $this->CI->load->library('listing/NationalCourseLib');
		$onlineForms = $national_course_lib->getOnlineFormAllCourses();
		
		list($timeWindowStart,$timeWindowEnd) = explode(';',$processingParams['timeWindow']);
		$coursesToCompare = $this->mailerModel->getCoursesToCompare($userIds,$timeWindowStart);
		
		foreach($coursesToCompare as $userId => $courses) {
			
			/**
			 * We should have at least 2 courses to compare
			 */
			if(count($courses) < 2) {
				continue;
			}
			
			/**
			 * Fetch data for each course in comparison
			 */ 
			foreach($courses as $courseId) {
				$course = $this->courseRepository->find($courseId);
				$instituteId = $course->getInstId();
				$institute = $this->instituteRepository->find($instituteId);
				
				if($institute instanceof AbroadInstitute) { continue; }
				if($course instanceof AbroadCourse) { continue; }
				
				$comparisonData = array();
				/**
				 *  Retriving Data for Compare Mailer Widget
				 */	
				$comparisonData['InstituteTitle'] = $institute->getName();
				$comparisonData['locality'] = $institute->getMainLocation()->getCity()->getName();
				$comparisonData['HeaderImage'] = $institute->getMainHeaderImage()->getFullURL();
				$comparisonData['CourseTitle'] = $course->getName();
				$comparisonData['CourseDuration'] = $course->getDuration()->getDisplayValue();
				$comparisonData['CourseFees'] = $course->getFees();
				$comparisonData['ExamsDetails'] = $course->getEligibilityExams();//->getAcronym();
				$comparisonData['ModeOfStudy'] = $course->getCourseType();
				$comparisonData['CourseLevel'] = $course->getCourseLevel();
				$comparisonData['Affiliations'] = $course->getAffiliations();
				$comparisonData['isAICTEApproved'] = $course->isAICTEApproved();
				$comparisonData['isUGCRecognised'] = $course->isUGCRecognised();
				$comparisonData['isDECApproved'] = $course->isDECApproved();
				$comparisonData['AverageSalary'] = $course->getAverageSalary();
				$comparisonData['RecruitingCompanies'] = $course->getRecruitingCompanies();
				$comparisonData['offersDualDegree'] = $course->offersDualDegree();
				$comparisonData['offersForeignTravel'] = $course->offersForeignTravel();
				$comparisonData['providesFreeLaptop'] = $course->providesFreeLaptop();
				$comparisonData['providesHostelAccomodation'] = $course->providesHostelAccomodation();
				$comparisonData['HasWifiCampus'] = $course->hasWifiCampus();
				$comparisonData['HasACCampus'] = $course->hasACCampus();
				$comparisonData['instituteid'] = $instituteId;
				
				if($onlineForms[$course->getId()]) {
					$comparisonData['showOFLink'] = true;
					$comparisonData['OFlink'] = THIS_CLIENT_IP.$onlineForms[$course->getId()]['seo_url'];
				}
				$comparisonData['alumnisReviews'] = $institute->getAlumniRating();
				
				$data[$userId][] = $comparisonData;
			}
		}
		
		return $data;
	}
}

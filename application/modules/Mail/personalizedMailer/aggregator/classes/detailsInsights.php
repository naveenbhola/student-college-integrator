<?php

include_once '../WidgetsAggregatorInterface.php';

class detailsInsights implements WidgetsAggregatorInterface {
    private $_params = array();
    
    public function __construct($params) {
        $this->_params = $params;
        $this->CI = & get_instance();

        $this->CI->load->library('nationalCourse/CourseDetailLib');
        $this->courseDetailLib = new CourseDetailLib; 

        $this->CI->load->model('nationalCourse/Coursedetailmodel');
        $this->coursedetailmodel = new Coursedetailmodel;

        $this->CI->load->helper('listingCommon/listingcommon');

        $this->CI->load->library('ContentRecommendation/ArticleRecommendationLib');
        $this->articleRecommendationLib = new ArticleRecommendationLib;

        $this->CI->load->library('ContentRecommendation/AnARecommendationLib');
        $this->AnARecommendationLib = new AnARecommendationLib;
    }

    public function getWidgetData() {
        $params = $this->_params['customParams'];

        $courseObj = $params['courseObj'];
        $instituteObj = $params['instituteObj'];
        if(is_object($courseObj)) {
            $params['reviewCount'] = $courseObj->getReviewCount();
            $params['reviewUrl'] = $instituteObj->getURL().'/reviews?course='.$courseObj->getId();

            //$params['anaCount']    = $courseObj->getQuestionsCount();
            $anaCount = $this->AnARecommendationLib->getRecommendedCourseQuestionsCount(array($courseObj->getId()));
            $params['anaCount'] = $anaCount[$courseObj->getId()];
            $params['anaUrl'] = $instituteObj->getURL().'/questions?course='.$courseObj->getId();

            $params['admission']   = $this->courseDetailLib->getAdmissionsData($courseObj->getId());
            $params['admissionUrl'] = $courseObj->getURL().'?scrollTo=admission&utm_term=AdmissionProcess';
            
            $params['articleCount'] = $this->articleRecommendationLib->getInstituteArticleCounts(array($instituteObj->getId()));
            $params['articleCount'] = $params['articleCount'][$instituteObj->getId()];

            $params['articleUrl'] = $instituteObj->getURL().'/articles';
            
            $params['eligibility'] = $this->coursedetailmodel->getEligibilityData($courseObj->getId());
            $params['eligibilityUrl'] = $courseObj->getURL().'?scrollTo=eligibility&utm_term=EligibilityExams';

            $placements  = $courseObj->getPlacements();
            if(!empty($placements)) {
                $showPlacements = 1;
            }
            else {
                $placementsCompanies = $this->courseDetailLib->getRecruitmentCompanies($courseObj->getId());
                if(!empty($placementsCompanies)) {
                    $showPlacements = 1;
                } else {
                    $internships = $courseObj->getInternships();
                    if(!empty($internships) && $internships->getReportUrl()) {
                        $showPlacements = 1;
                    }
                }
            }
            $params['showPlacements'] = $showPlacements;
            $params['placementUrl'] = $courseObj->getURL().'?scrollTo=placement&utm_term=PlacementCompanies';

            $instituteLocation = $instituteObj->getMainLocation();
            $params['mediaCount'] = getMediaCountForInstitute($instituteObj, $instituteLocation->getLocationId());
            $params['mediaUrl'] = $courseObj->getURL().'?scrollTo=gallery&utm_term=CampusPhotos';
        }
        
        if($params['reviewCount'] >= 3 || $params['articleCount'] > 0 || $params['mediaCount'] > 0 || $params['anaCount'] > 0 || $params['showPlacements'] > 0 || !empty($params['admission']) || !empty($params['eligibility'])) {
            $widgetHTML = $this->CI->load->view("personalizedMailer/widgets/detailsInsights", $params, true);
        }
        
        return array('key'=>'detailsInsights', 'data'=>$widgetHTML);
    }
}
<?php 

include_once '../WidgetsAggregatorInterface.php';

class InstituteDigest implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
		$this->instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');

		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder = new InstituteBuilder();
		$this->instituteRepo = $instituteBuilder->getInstituteRepository();

		$this->CI->load->builder("nationalCourse/CourseBuilder");
		$builder          = new CourseBuilder();
		$this->courseRepo = $builder->getCourseRepository();

		$this->anarecommendationlib = $this->CI->load->library('ContentRecommendation/AnARecommendationLib');
		$this->tagsmodel = $this->CI->load->model('v1/tagsmodel');

		$this->articlerecommendationlib = $this->CI->load->library('ContentRecommendation/ArticleRecommendationLib');

		$this->alsoviewed = $this->CI->load->library('recommendation/alsoviewed');
	}

	public function getWidgetData() {
		$customParams = $this->_params['customParams'];
		$instituteId     = $customParams['instituteId'];
		$userId     = $customParams['userId'];

		$instituteObj = $customParams['instituteObj'];

		$listingType = $instituteObj->getType();

		$instituteWiseCourses = $this->instituteDetailLib->getAllCoursesForInstitutes($instituteId);
		$customParams['courseCount'] = count($instituteWiseCourses['courseIds']);
		$customParams['allCoursesUrl'] = $instituteObj->getAllContentPageUrl('courses');

		$customParams['instituteName'] = $instituteObj->getName();
		$customParams['abbreviation'] = $instituteObj->getAbbreviation();

		// _p($instituteWiseCourses);die;

		$viewCountData = $this->instituteDetailLib->getCourseViewCount($instituteWiseCourses['courseIds']);
		arsort($viewCountData);

		$popularCourseIds = array();
		foreach ($viewCountData as $key => $value) {
			$popularCourseIds[] = $key;
			if(count($popularCourseIds) >= 3){
				break;
			}
		}
		$courseObjs = $this->courseRepo->findMultiple($popularCourseIds);
		foreach ($courseObjs as $courseObj) {
			if(empty($courseObj) || $courseObj->getId() == ""){
				continue;
			}
			$popularCourseData[] = array('name' => $courseObj->getName(), 'url' => $courseObj->getURL());
		}
		$customParams['popularCourseData'] = $popularCourseData;

		$reviewCount = $this->instituteDetailLib->getCountOfReviewsForListings($instituteId,'institute',$instituteWiseCourses,$instituteWiseCourses);
		// $reviewCount = 0;
		if(!empty($reviewCount)){
			$customParams['reviewCount'] = $reviewCount;
			$customParams['allReviewsUrl'] = $instituteObj->getAllContentPageUrl('reviews');

			$customParams['reviewLinks'][] = array('text' => 'By Year of graduation', 'url' => add_query_params($customParams['allReviewsUrl'],array('sort_by' => 'Year of Graduation','type' => 'question')));
			$customParams['reviewLinks'][] = array('text' => 'Highest rated reviews', 'url' => add_query_params($customParams['allReviewsUrl'],array('sort_by' => 'Highest Rating','type' => 'question')));
			$customParams['reviewLinks'][] = array('text' => 'Lowest rated reviews', 'url' => add_query_params($customParams['allReviewsUrl'],array('sort_by' => 'Lowest Rating','type' => 'question')));
		}

		$sortingOptions = 'RELEVANCY';

		if($listingType == 'institute'){
			$questionIdsData = $this->anarecommendationlib->forInstitute($instituteId, array() , 3);
			$articleIdsData = $this->articlerecommendationlib->forInstitute($instituteId,array(),3,0,$sortingOptions,$instituteWiseCourses);
		}
		else if($listingType == 'university'){
			$questionIdsData = $this->anarecommendationlib->forUniversity($instituteId, array() , 3);
			$articleIdsData = $articleArray = $this->articlerecommendationlib->forUniversity($instituteId,array(),3,0,$sortingOptions, $instituteWiseCourses);
		}

		$contentIdsArray = $questionIdsData['topContent'];
		// $contentIdsArray = array();
		if(!empty($contentIdsArray)){
			$questionsData = $this->tagsmodel->getContentDetails(implode(',',$contentIdsArray), 'question', 0, 10, $userId);
			$customParams['questionCount'] = $questionIdsData['numFound'];
			$customParams['allQuestionURL'] = $instituteObj->getAllContentPageUrl('questions');
			$customParams['questionsData'] = $questionsData;
		}
		// $articleIds = array();
		$articleIds = $articleIdsData['topContent'];
		if(!empty($articleIds)){
			$customParams['articlesCount'] = $articleIdsData['numFound'];

			$this->CI->load->builder('ArticleBuilder','article');
			$this->articleBuilder = new ArticleBuilder;
			$this->articleRepository = $this->articleBuilder->getArticleRepository();
			$articleObj = $this->articleRepository->findMultiple($articleIds);
			if(!empty($articleObj)){
			    foreach($articleObj as $key =>$article){
			        $id = $article->getId();
			        $articleData[$id] = array('id'=>$id,
			            'url'=>addingDomainNameToUrl(array('url' => $article->getUrl(),'domainName' => SHIKSHA_HOME)),
			            'blogTitle'=>$article->getTitle(),
			            'summary'=>$article->getSummary(),
			            );
			        }
			}

			$articleArray = array();
			foreach ($articleIds as $articleId) {
				$articleArray[] = $articleData[$articleId];
			}
			$customParams['articleData'] = $articleArray;
			$customParams['allArticleURL'] = $instituteObj->getAllContentPageUrl('articles');
		}
		$examList = modules::run('nationalInstitute/InstituteDetailPage/getExamsMappedToUniversity',$instituteId);
		$customParams['examList'] = array_slice($examList,0,4);
		// $customParams['examList'] = array();

		if($listingType == 'institute'){
			$results = $this->alsoviewed->getAlsoViewedInstitutes(array($instituteId),'3');
		}
		else if($listingType == 'university'){
			$results = $this->alsoviewed->getAlsoViewedUniversities(array($instituteId),'3');
		}
		if(!empty($results)){
			$instituteObjs = $this->instituteRepo->findMultiple($results);
			foreach ($instituteObjs as $key => $value) {
				if(empty($value) || $value->getId() == ""){
					continue;
				}
				$similarColleges[] = array('name' => $value->getName(),'url' => $value->getURL());
			}
		}
		$customParams['similarColleges'] = $similarColleges;
		// $customParams['similarColleges'] = array();
		// _p($customParams);die;

		$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/InstituteDigest", $customParams, true);
		$widgetHTML = sanitize_output($widgetHTML);
		return array('key' => 'InstituteDigest', 'data' => $widgetHTML);
	}

}

?>
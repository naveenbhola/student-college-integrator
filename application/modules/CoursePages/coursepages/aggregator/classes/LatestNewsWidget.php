<?php

include_once '../WidgetsAggregatorInterface.php';

class LatestNewsWidget implements WidgetsAggregatorInterface{

	private $_params = array();
	private $_CI;
	private $articlemodel;
	private $dateToCheckFor;	
	
	public function __construct($params) {
		$this->_params = $params;
		$this->_CI = & get_instance();
		$this->articlemodel = $this->_CI->load->model('blogs/articlemodel');
		$this->cache = $this->_CI->load->library('coursepages/cache/CoursePagesCache');
	}
	
	public function getWidgetData() {
		$param = $this->_params['coursePageData'];
		$articleUtilityLib = $this->_CI->load->library('article/ArticleUtilityLib');
		$input = $articleUtilityLib->parseInputForWidget($param);
		$this->cache = $this->_CI->load->library('coursepages/cache/CoursePagesCache');
		if($this->cache->isCPGSCachingOn()) {
			$displayData = $this->cache->getArticlesData($input['courseHomePageId']);
		}
		if(empty($displayData)){
			$lib = $this->_CI->load->library('common/UrlLib');
			$data['result'] = $lib->parseThroughUrlGenerationRules($input);
			if(!empty($data['result']['popularCourseName'])){
				$data['case'] = 'popularCourse';
			}elseif(!empty($data['result']['subStreamName'])){
				$data['case'] = 'substream';
			}else{
				$data['case'] = 'stream';
			}
			$data['limit'] = 5;
			$popularArticles = $this->getPopularArticleListAndURL($data,$articleUtilityLib);
			$displayData['articleList'] = $this->getMergedArray($popularArticles);
			$displayData['limit'] = $data['limit'];
			$displayData['allPageUrl'] = $data['url'];
			$displayData['coursePageName'] = $input['courseHomePageName'];
			$this->cache->storeArticlesData($input['courseHomePageId'], $displayData);
		}
		return array('key'=>'latestNews','data'=>($displayData));
	}

	public function disableCaching(){
		$this->cache->disableCPGSCaching();
	}

	function getPopularArticlesForCoursePageWidget($param){

		$this->_CI->load->view('newsAndArticleWidget',$displayData);
	}

	function getPopularArticleListAndURL(&$data,&$articleUtilityLib){
		if(empty($articleutilitylib)){
			$articleUtilityLib = $this->_CI->load->library('article/ArticleUtilityLib');
		}
		$url = '';
		switch ($data['case']) {
			case 'popularCourse':
				$articleUtilityLib->createUrlForPopularCourseBasedArticleListingPage($data);
				break;

			case 'substream':
				$articleUtilityLib->createUrlForSubstreamBasedArticleListingPage($data);
				break;

			case 'stream':
				$articleUtilityLib->createUrlForStreamBasedArticleListingPage($data);
				break;
		}
		return $this->getPopularArticles($data,$articleUtilityLib);
	}

	function getPopularArticles(&$data,&$articleUtilityLib){
		switch ($data['case']) {
			case 'popularCourse':
				$entityIds = $data['result']['base_course_id'];
				break;
			
			default:
				if(empty($articleUtilityLib)){
					$articleUtilityLib = $this->_CI->load->library('article/ArticleUtilityLib');
				}
				$entityIds = $articleUtilityLib->getHierarchyId($data['result']['stream_id'],$data['result']['substream_id']);
				break;
		}
		$articleModel = $this->_CI->load->model('article/articlenewmodel');
		$articleIds = $articleUtilityLib->getFilteredArticles($data['filters'],$articleModel);
		$result = $articleModel->getPopularArticlesForCoursePageWidget($data['result']['courseHomePageId'], $data['limit']=5, $data['case'],$entityIds, $articleIds);
		foreach ($result as $type => $value) {
			foreach ($value as $key => $val) {
				if(empty($val['blogImageURL'])){
					$result[$type][$key]['blogImageURL'] = IMGURL_SECURE."/public/images/news-pic.jpg";
				}
				else{
					$result[$type][$key]['blogImageURL'] = addingDomainNameToUrl(array('url'=>$result[$type][$key]['blogImageURL'],'domainName'=>MEDIA_SERVER));
				}
				// See if Medium size thumbnail (104 X 78) is available for this Article image..
			    $mediumSizeThumb = str_replace("_s.", "_m.", $val['blogImageURL']);
			    // if($articleUtilityLib->urlExists($mediumSizeThumb)) {
					$val['blogImageURL'] = $mediumSizeThumb;
			    // }
			    $result[$type][$key]['dateText'] = $articleUtilityLib->getFormattedDateDiff(floor((strtotime(date('Y-m-d H:i:s')) - strtotime($val['creationDate']))  / (60 * 60 * 24)));
			    $result[$type][$key]['url'] = addingDomainNameToUrl(array('url'=>$result[$type][$key]['url'],'domainName'=>SHIKSHA_HOME));
			}
		}
		return $result;
	}

	function getMergedArray(&$popularArticles){
		if(empty($popularArticles['result'])){
			$popularArticles['result'] = array();
		}
		if(empty($popularArticles['sticky'])){
			$popularArticles['sticky'] = array();
		}
		if(empty($popularArticles['boost'])){
			$popularArticles['boost'] = array();
		}
		return array_merge($popularArticles['sticky'],$popularArticles['boost'],$popularArticles['result']);
	}

}
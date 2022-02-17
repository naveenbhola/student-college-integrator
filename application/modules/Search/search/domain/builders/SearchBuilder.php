<?php

class SearchBuilder {
	
	static protected $_ci;

	public static function init(){
		self::$_ci = & get_instance();
	}
	
	public static function initFinders(){
		self::init();
		self::$_ci->load->library('search/Finders/CourseFinder');
		self::$_ci->load->library('search/Finders/InstituteFinder');
		self::$_ci->load->library('search/Finders/QuestionFinder');
		self::$_ci->load->library('search/Finders/AutosuggestorFinder');
		self::$_ci->load->library('search/Finders/AutosuggestorFinderV2');
		self::$_ci->load->library('search/Finders/ArticleFinder');
		self::$_ci->load->library('search/Finders/DiscussionFinder');
        self::$_ci->load->library('search/Finders/UniversityFinder');
        self::$_ci->load->library('search/Finders/AbroadCourseFinder');
        self::$_ci->load->library('search/Finders/AbroadInstituteFinder');
		self::$_ci->load->library('search/Finders/CareerFinder');
        self::$_ci->load->library('search/Finders/TagFinder');
	}
	
	public static function initDocumentGenerator(){
		self::init();
		self::$_ci->load->library('search/DocumentGenerator/SolrXmlDocumentGenerator');
	}
	
	public static function initDeleteDocument(){
		self::init();
		self::$_ci->load->library('search/DeleteDocument/SolrDeleteDocument');
	}
	
	public static function initSearchServer(){
		self::init();
		self::$_ci->load->library('search/SearchServer/SolrServer');
	}
	
	public static function initSearcher(){
		self::init();
		self::$_ci->load->library('search/Searcher/SolrSearcher');
	}
	
	public static function initSearchWrapper(){
		self::init();
		self::$_ci->load->library('search/Searcher/SearchWrapper');
	}
	
	public static function initSearchCommon(){
		self::init();
		self::$_ci->load->library('search/Common/SearchCommon');
	}
	
	public static function initSearchSponsored(){
		self::init();
		self::$_ci->load->library('search/Common/SearchSponsored');
	}
	
	public static function initSearchQERLib(){
		self::init();
		self::$_ci->load->library('search/Common/SearchQERLib');
	}
	
	public static function initSearchRepository(){
		self::init();
		self::$_ci->load->repository('SearchRepository','search');
	}
	
	public static function initSolrDataProcessor(){
		self::init();
		self::$_ci->load->library('search/Searcher/SolrDataProcessor');
	}
	
	public static function initInstituteSearchResultRepository(){
		self::init();
		self::$_ci->load->repository('InstituteSearchResultRepository','search');
	}
	
	public static function initContentSearchResultRepository(){
		self::init();
		self::$_ci->load->repository('ContentSearchResultRepository','search');
	}
	
	public static function getFinder($type = "course"){
		self::initFinders();
		switch($type){
			case 'course':
				return new CourseFinder();
			
			case 'article':
				return new ArticleFinder();
			
			case 'question':
				return new QuestionFinder();
				
			case 'institute':
				return new InstituteFinder();
			
			case 'autosuggestor':
				return new AutosuggestorFinder();

			case 'autosuggestorv2':
				return new AutosuggestorFinderV2();
			
			case 'discussion':
				return new DiscussionFinder();
                            
			case 'university':
				return new UniversityFinder();
			
			case 'tag':
				return new TagFinder();

			case 'abroadcourse':
				return new AbroadCourseFinder();
				
			case 'abroadinstitute':
				return new AbroadInstituteFinder();
			
			case 'career':
				return new CareerFinder();
				
			default:
				return null;
		}
	}
	
	public static function getDocumentDeleteInstance($searchServer = "solr"){
		self::initDeleteDocument();
		switch($type){
			case 'solr':
				return new SolrDeleteDocument();
			
			default:
				return new SolrDeleteDocument();
		}
	}
	
	public static function getDocumentGenerator($type = "solr_xml"){
		self::initDocumentGenerator();
		switch($type){
			case 'solr_xml':
				return new SolrXmlDocumentGenerator();
			
			default:
				return new SolrXmlDocumentGenerator();
		}
	}
	
	public static function getSearchServer($searchServer = "solr"){
		self::initSearchServer();
		switch($searchServer){
			case 'solr':
				return new SolrServer();
			
			default:
				return new SolrServer();
		}
	}
	
	public static function getSearcher($searchServer = "solr"){
		self::initSearcher();
		switch($searchServer){
			case 'solr':
				return new SolrSearcher();
			default:
				return new SolrSearcher();
		}
	}
	
	public static function loadSolrDataProcessor($searchServer = "solr"){
		self::initSolrDataProcessor();
	}
	
	public function getSearchRepository() {
        self::initSearchRepository();
        $searchRepository = new SearchRepository();
        return $searchRepository;
    }
	
	public function getInstituteSearchResultRepository() {
        self::initInstituteSearchResultRepository();
        $instituteSearchRepository = new InstituteSearchResultsRepository();
        return $instituteSearchRepository;
    }
	
	public function getContentSearchResultRepository(){
		self::initContentSearchResultRepository();
		$contentSearchRepository = new ContentSearchResultRepository();
        return $contentSearchRepository;
	}
	
	public static function getSearchWrapper(){
		self::initSearchWrapper();
		return new SearchWrapper();
	}
	
	public static function getSearchCommon(){
		self::initSearchCommon();
		return new SearchCommon();
	}
	
	public static function getSearchSponsored(){
		self::initSearchSponsored();
		return new SearchSponsored();
	}
	
	public static function getSearchQERLib(){
		self::initSearchQERLib();
		return new SearchQERLib();
	}
}
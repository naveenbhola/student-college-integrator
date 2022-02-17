<?php

class RankingPageBuilder {
	
	static protected $_ci;

	private static function init(){
		self::$_ci = & get_instance();
	}
	
	public static function initURLManager(){
		self::init();
		self::$_ci->load->builder("LocationBuilder", "location");
		self::$_ci->load->helper(RANKING_PAGE_MODULE."/RankingUtility");
		self::$_ci->load->helper('url');
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/RankingPageURLManager/RankingPageRequest');
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/RankingPageURLManager/RankingURLManager');
	}
	
	public static function initRankingPageWrapper(){
		self::init();
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/RankingPageWrapper');
	}
	
	public static function initRankingPageRepository(){
		self::init();
		self::$_ci->load->builder("nationalInstitute/InstituteBuilder");
		self::$_ci->load->builder("nationalCourse/CourseBuilder");
		self::$_ci->load->entities(array('RankingPage', 'RankingPageData','RankingSource'), RANKING_PAGE_MODULE);
                self::$_ci->load->library('listing/AbroadListingCommonLib');
		self::$_ci->load->repository('RankingPageRepository',RANKING_PAGE_MODULE);
		self::$_ci->load->model('listing/listingextendedmodel');
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/cache/RankingPageCache');
	}
	
	public static function initRankingPageManager(){
		self::init();
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/RankingPageManager/RankingPageManager');
	}
	
	public static function initRankingCommonLib(){
		self::init();
		self::$_ci->config->load('ranking_config');
		self::$_ci->load->builder("LocationBuilder", "location");
		self::$_ci->load->builder("nationalInstitute/InstituteBuilder");
		self::$_ci->load->builder("nationalCourse/CourseBuilder");
		self::$_ci->load->builder('CategoryBuilder','categoryList');
		self::$_ci->load->builder("LDBCourseBuilder", "LDB");
		// self::$_ci->load->library("Category_list_client");
		self::$_ci->load->model("search/SearchModel", "", true);
		self::$_ci->load->model(RANKING_PAGE_MODULE."/ranking_model", "", true);
		self::$_ci->load->model("nationalCourse/nationalcoursemodel", "", true);
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/RankingCommonLib');
	}
	
	public static function initRankingEnterpriseLib(){
		self::init();
		self::$_ci->load->builder("LocationBuilder", "location");
		self::$_ci->config->load('ranking_config');
		self::$_ci->load->model(RANKING_PAGE_MODULE.'/ranking_model', '', true);
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/RankingEnterpriseLib');
	}
	
	public static function initRankingModel(){
		self::init();
		self::$_ci->load->model(RANKING_PAGE_MODULE.'/ranking_model', '');
	}
	
	public static function initFilterManager(){
		self::init();
		self::$_ci->load->builder("LocationBuilder", "location");
		self::$_ci->load->library(RANKING_PAGE_MODULE."/RankingPageFilterManager/RankingPageFilter");
		self::$_ci->load->library(RANKING_PAGE_MODULE."/RankingPageURLManager/RankingPageRequest");
		self::$_ci->load->helper(RANKING_PAGE_MODULE."/RankingUtility");
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/RankingPageFilterManager/RankingPageFilterManager');
	}
	
	public static function initSorterManager(){
		self::init();
		self::$_ci->load->builder("RankingPageBuilder", RANKING_PAGE_MODULE);
		self::$_ci->load->library(RANKING_PAGE_MODULE."/RankingPageSorterManager/RankingPageSorter");
		self::$_ci->load->library("common/GeneralSorter");
		self::$_ci->load->helper(RANKING_PAGE_MODULE.'/RankingUtility');
		self::$_ci->config->load('ranking_config');
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/RankingPageSorterManager/RankingPageSorterManager');
	}
	
	public static function initRelatedLib(){
		self::init();
		self::$_ci->config->load('ranking_config');
		self::$_ci->load->builder("LocationBuilder", "location");
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/RankingPageFilterManager/RankingPageFilter');
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/RankingPageURLManager/RankingPageRequest');
		self::$_ci->load->helper(RANKING_PAGE_MODULE."/RankingUtility");
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/RankingPageRelatedLib');
	}
	
	public static function getURLManager(){
		self::initURLManager();
		$locationBuilder 	= new LocationBuilder();
		$rankingModel 		= RankingPageBuilder::getRankingModel();
		return new RankingURLManager($locationBuilder, $rankingModel);
	}
	
	public static function getRankingPageRepository(){
		self::initRankingPageRepository();
		$instituteBuilder = new InstituteBuilder();
		$courseBuilder = new CourseBuilder();
		$rankingPageCommonLib 	= self::getRankingPageCommonLib();
		$instituteRepo 			= $instituteBuilder->getInstituteRepository();
		$courseRepo    			= $courseBuilder->getCourseRepository();
		$rankingPageManager 	= self::getRankingPageManager();
		$rankingCache 			= new RankingPageCache();
                $abroadListingCommonLib =new AbroadListingCommonLib();
		return new RankingPageRepository($rankingPageCommonLib, $instituteRepo, $courseRepo, $rankingPageManager, $rankingCache,$abroadListingCommonLib);
	}
	
	public static function getRankingPageManager(){
		self::initRankingPageManager();
		$rankingModel 		 	= RankingPageBuilder::getRankingModel();
		$rankingPageCommmonLib 	= RankingPageBuilder::getRankingPageCommonLib();
		return new RankingPageManager($rankingPageCommmonLib, $rankingModel);
	}
	
	public static function getRankingPageCommonLib(){
		self::initRankingCommonLib();
		$courseBuilder 		= new CourseBuilder();
		$instituteBuilder 	= new InstituteBuilder();
		$categoryBuilder 	= new CategoryBuilder();
		$LocationBuilder 	= new LocationBuilder();
		$LDBCourseBuilder 	= new LDBCourseBuilder();
        
		// $rankingFilterManager = RankingPageBuilder::getFilterManager();
		$instituteRepo 			= $instituteBuilder->getInstituteRepository();
		$categoryRepo 			= $categoryBuilder->getCategoryRepository();
		$courseRepo 			= $courseBuilder->getCourseRepository();
		$locationRepo			= $LocationBuilder->getLocationRepository();
		$LDBCourseRepository 	= $LDBCourseBuilder->getLDBCourseRepository();
		return new RankingCommonLib(self::$_ci, $instituteRepo, $categoryRepo, $courseRepo, $locationRepo, $LDBCourseRepository);
	}
	
	public static function getRankingPageEnterpriseLib(){
		self::initRankingEnterpriseLib();
		$rankingPageCommonLib 	= self::getRankingPageCommonLib();
		$rankingURLManager 		= self::getURLManager();
		return new RankingEnterpriseLib(self::$_ci, $rankingPageCommonLib, $rankingURLManager);
	}
	
	public static function getRankingModel(){
		self::initRankingModel();
		return new ranking_model();
	}
	
	public static function getFilterManager(){
		self::initFilterManager();
		$locationBuilder 			= new LocationBuilder();
		$locationRepo			= $locationBuilder->getLocationRepository();
		$rankingURLManager  	= self::getURLManager();
		$rankingModel			= self::getRankingModel();
		return new RankingPageFilterManager(self::$_ci, $locationRepo, $rankingURLManager, $rankingModel);
	}
	
	public static function getSorterManager(){
		self::initSorterManager();
		return new RankingPageSorterManager(self::$_ci);
	}
	
	public static function getRankingPageRelatedLib(){
		self::initRelatedLib();
		$locationBuilder 	= new LocationBuilder();
		$locationRepo		= $locationBuilder->getLocationRepository();
		$rankingURLManager	= self::getURLManager();
		$rankingModel		= self::getRankingModel();
		return new RankingPageRelatedLib(self::$_ci, $locationRepo, $rankingURLManager, $rankingModel);
	}

	public static function initRankingMbaLib(){
		self::init();
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/RankingMbaLib');
	}

	public static function getRankingMbaLib(){
		self::initRankingMbaLib();
		$rankingPageRelatedLib = self::getRankingPageRelatedLib();
		return new RankingMbaLib(self::$_ci, $rankingPageRelatedLib);
	}

	public static function initRankingEngineeringLib(){
		self::init();
		self::$_ci->load->library(RANKING_PAGE_MODULE.'/RankingEngineeringLib');
	}

	public static function getRankingEngineeringLib(){
		self::initRankingEngineeringLib();
		$rankingPageRelatedLib = self::getRankingPageRelatedLib();
		return new RankingEngineeringLib(self::$_ci, $rankingPageRelatedLib);
	}
}

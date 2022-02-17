<?php

class HttpToHttpsMovement extends MX_Controller {
	private $httpsModelHandle;
	
	function __construct(){
		$this->httpsModelHandle = $this->load->model('listingMigration/httptohttpsmovementmodel');
 	}


 	function updateListingsTable(){
        return;
 	    ini_set('memory_limit','-1');
        set_time_limit(0);
	
 		if(ENVIRONMENT == 'production' ){
 			$commonDomain = array(
	 								str_replace('https://', 'http://', SHIKSHA_HOME),
	 								str_replace('https://', 'http://', SHIKSHA_STUDYABROAD_HOME),
	 								str_replace('https://', 'http://', MEDIAHOSTURL),
	 								str_replace('https://', 'http://', SHIKSHA_ASK_HOME)
	 							);
 		}else{
	 		$commonDomain = array(
	 								str_replace('https://', 'http://', SHIKSHA_HOME),
	 								str_replace('https://', 'http://', SHIKSHA_STUDYABROAD_HOME),
	 								'http://www.shiksha.com',
	 								'http://studyabroad.shiksha.com',
	 								'http://localshiksha.com:80',
	 								'http://localshiksha.com',
	 								'http://shikshatest02.infoedge.com:80',
	 								'http://shikshatest02.infoedge.com',
	 								'http://images.shiksha.com',
	 								str_replace('https://', 'http://', SHIKSHA_ASK_HOME)
	 							);
 			
 		}

 		$tables = array(
 						'listings_main' => array(
 												'tableName'         => 'listings_main',
 												'columnName'        => 'listing_seo_url',
 												'domainToBeRepaced' =>  $commonDomain,
 												'statusCheck'       => 1
 												),
 						'placement' => array(
 												'tableName'         => 'shiksha_courses_placements_internships',
 												'columnName'        => 'report_url',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 1
 												),
 						'brochure' => array(
 												'tableName'         => 'shiksha_listings_brochures',
 												'columnName'        => 'brochure_url',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 1
 												),
 						'contacts' => array(
 												'tableName'         => 'shiksha_listings_contacts',
 												'columnName'        => 'google_url',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 1
 												),
 						'insMedia' => array(
 												'tableName'         => 'shiksha_institutes_medias',
 												'columnName'        => 'media_url',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 1
 												),
 						'insMedia1' => array(
 												'tableName'         => 'shiksha_institutes_medias',
 												'columnName'        => 'media_thumb_url',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 1
 												),
 						'imageData' => array(
 												'tableName'         => 'tImageData',
 												'columnName'        => 'url',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 0
 												),
 						'imageData1' => array(
 												'tableName'         => 'tImageData',
 												'columnName'        => 'thumburl',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 0
 												),
 						'imageData2' => array(
 												'tableName'         => 'tImageData',
 												'columnName'        => 'thumburl_s',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 0
 												),
 						'tVideoData' => array(
 												'tableName'         => 'tVideoData',
 												'columnName'        => 'url',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 0
 												),
 						'tVideoData1' => array(
 												'tableName'         => 'tVideoData',
 												'columnName'        => 'thumburl_big',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 0
 												),
 						'tVideoData2' => array(
 												'tableName'         => 'tVideoData',
 												'columnName'        => 'thumburl',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 0
 												),
 						'disabledUrl' => array(
 												'tableName'         => 'shiksha_institutes',
 												'columnName'        => 'disabled_url',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 1
 												),
 						'instituteLogo' => array(
 												'tableName'         => 'shiksha_institutes',
 												'columnName'        => 'logo_url',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 1
 												),
 						'disabledUrlCourse' => array(
 												'tableName'         => 'shiksha_courses',
 												'columnName'        => 'disabled_url',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 1
 												),
 						'expertBroad' => array(
 												'tableName'         => 'expertOnboardTable',
 												'columnName'        => 'imageURL',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 0
 												),
 						'pdf' => array(
 												'tableName'         => 'tPdfData',
 												'columnName'        => 'url',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 0
 												),
 						'homepageCoverBanner' => array(
 												'tableName'         => 'homepageBannerProduct',
 												'columnName'        => 'image_url',
 												'domainToBeRepaced' => $commonDomain,
 												'statusCheck'       => 1
 												),
 						
 						);
		error_log("Updating listings tables cron start");
		$scriptStartTime   = time();
		$httpContentLib    = $this->load->library('common/httpContent');

		foreach ($tables as $key => $value) {
			error_log("TABLE NAME  to be processed  : " .$value['tableName']);
			if(ENVIRONMENT != 'production' ){
				$this->httpsModelHandle->backupTable($value['tableName']);
			}
			$httpContentLib->replaceAbsolutePathWithRelativepath($value['tableName'], $value['columnName'], $value['domainToBeRepaced'], $value['statusCheck']);
		}
		
		$scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		
		error_log("Listings tables updated");
		error_log("Time Taken : ".$timeTaken." mins");
		_p("done");die;
 	}


    //shiksha_listing

    function updateRankingBannerUrls(){
        return;
    	ini_set('memory_limit','-1');
        set_time_limit(0);
		
		error_log("Script ======== started");

		$bannerData = $this->httpsModelHandle->getRankingBannerData();
		$updateData = array();
		foreach ($bannerData as $row) {
			$url = parse_url($row['file_path']);
			$url = 'https://'.$url['host'].$url['path'];
			$updateData[] = array('id'=>$row['id'],'file_path'=>$url);
		}
		// _p($updateData);die;
		if(!empty($updateData)){
			$this->httpsModelHandle->updateRankingBannerUrls($updateData);
		}
		error_log("Script ======== end");
    }

    function updateCourseHomePageTablesForHttps(){
        return;
    	$httpLib = $this->load->library('common/httpContent');
    	$httpLib->findHttpInContent('course_pages_featured_institutes','id','landingUrl','live',false);
    	$httpLib->findHttpInContent('course_pages_featured_institute_sections','sectionId','sectionURL','live',false);
        $httpLib->findHttpInContent('course_pages_featured_institute_sections_links','id','landinURL','live',false);
    	$httpLib->findHttpInContent('course_pages_faqs','id','answerText','live',false);
    	$httpLib->replaceAbsolutePathWithRelativepath('course_pages_featured_institutes','imageUrl',array(),1);
    }

    function moveYoutubeUrlstoHTTPs(){
        return;
    	ini_set('memory_limit','-1');
        set_time_limit(0);
    	$httpLib = $this->load->library('common/httpContent');
    	
    	// youtube urls
    	$httpLib->findHttpInContent('shiksha_institutes_medias','id','media_url',array('live', 'draft'),true, 'http://www.youtube', true);
    	$httpLib->findHttpInContent('tVideoData','mediaid','url','',true, 'http://www.youtube', true);
    	
    	// youtube thumnails
    	$httpLib->findHttpInContent('shiksha_institutes_medias','id','media_thumb_url',array('live', 'draft'),true, 'http://i.ytimg', true);
    	$httpLib->findHttpInContent('tVideoData','mediaid','thumburl','',true, 'http://i.ytimg', true);
    	$httpLib->findHttpInContent('tVideoData','mediaid','thumburl_big','',true, 'http://i.ytimg', true);
    }

    function updateInstituteAnAContentToHttps(){
        return;
    	ini_set('memory_limit','-1');
        set_time_limit(0);
    	$httpLib = $this->load->library('common/httpContent');
    	
    	// questions/discussion content urls
    	$httpLib->findHttpInContent('messageTable','msgId','msgTxt',array('live', 'draft'),false);

    	//discussion description content urls
    	$httpLib->findHttpInContent('messageDiscussion','id','description',array(),false);


    	// research_project, usp, questions/discussion content urls
    	$httpLib->findHttpInContent('shiksha_institutes_additional_attributes','id','description',array('live', 'draft'),false);

    	// academic staff professional highlights
    	$httpLib->findHttpInContent('shiksha_institutes_academic_staffs','id','professional_highlights',array('live', 'draft'),false);

    	// facilities description
    	$httpLib->findHttpInContent('shiksha_institutes_facilities','id','description',array('live', 'draft'),false);

    	// facilities additional info
    	$httpLib->findHttpInContent('shiksha_institutes_facilities','id','additional_info',array('live', 'draft'),false);

    	// events description
    	$httpLib->findHttpInContent('shiksha_institutes_events','id','description',array('live', 'draft'),false);

    	// scholarship description
    	$httpLib->findHttpInContent('shiksha_institutes_scholarships','id','description',array('live', 'draft'),false);

    	// CollegeReview_InstituteReply
    	$httpLib->findHttpInContent('CollegeReview_InstituteReply','id','replyTxt',array('live', 'draft'),false);
    }

    //Will update homepagebannerProducts table used for showing homepage first fold content
    function updateHomepageBanners() {
        return;
    	ini_set('memory_limit','-1');
		set_time_limit(0);
    	$httpLib = $this->load->library('common/httpContent');
    	
    	//this will update only internal URLs i.e. shiksha.com
    	$httpLib->findHttpInContent('homepageBannerProduct','id','target_url',array('live', 'draft'),false);
		modules::run('home/HomePageCMS/refreshHomepageBannerCache');
    }

}

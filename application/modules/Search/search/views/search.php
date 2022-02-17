<?php
	$searchBannerProperties = array();
	foreach($banner_institutes as $instituteId => $courses){
		foreach($courses as $course){
			if(!empty($course)){
				if(count($course->getBannerBMSKey()) > 0){
					$bannerKeys = $course->getBannerBMSKey();
					$key 		= rand(0, count($bannerKeys) - 1);
					$bmsKey 	= $bannerKeys[$key];
					if(trim($bmsKey) != ""){
						$pageZone = "HEADER";
						$shikshaCriteria  = array('keyword' => $bmsKey);
						$searchBannerProperties = array('pageId'=>'SEARCH', 'pageZone'=> $pageZone);
						$searchBannerProperties['shikshaCriteria'] = $shikshaCriteria;
					}
				}
			}
		}
	}
	
	$keyword = (!empty($solr_institute_data['raw_keyword']))  ? htmlspecialchars($solr_institute_data['raw_keyword']) : '';
	$title = 'Shiksha.com - Search Results – Education – College – University – Study Abroad – Scholarships – Education Events – Admissions - Notifications -'.htmlspecialchars($keyword);
	$metaTitle = "";
	$metDescription = 'Search Shiksha.com for Colleges, University, Institutes, Foreign Education programs and information to study in India. Find course / program details, admissions, scholarships of universities of India and from countries all over the world -'.htmlspecialchars($keyword);
	$metaKeywords = 'Shiksha, Study, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships -'.htmlspecialchars($keyword);
	$headerComponents = array(
		'js'=>array('search', 'common', 'multipleapply','category','user','customCityList','ajax-api', 'AutoSuggestor', 'processForm'),
		'jsFooter' =>array('lazyload','onlinetooltip'),
		'product'=> "Search",
		'taburl' =>  site_url(),
		'title'	=>	$title,
		'searchEnable' => false,
		'canonicalURL' => "",
		'metaDescription' => $metDescription,
		'metaKeywords'	=> $metaKeywords,
		'searchBannerProperties' => $searchBannerProperties,
		'noIndexFollow' => $noIndexFollow
	);
	
	$noIndexFollow = (isset($noIndexFollow) && $noIndexFollow)?$noIndexFollow:false;
	$this->load->view('common/header', $headerComponents);
?>
<div id="content-wrapper" style="background:none;padding:0px;margin-top:-9px;">
	<div id="cateTitleBlock"></div> <!-- empty div to make compare layer work -->
	<div id="mainWrapper">
		<div id="mainPage">
			<div id="search-wrap">
				<?php $this->load->view('search/search_bar'); ?>
				<?php $this->load->view('search/search_page_hidden'); ?>
				<div id="search-content">
				  <?php
				       //echo Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', $subcat_id_course_page, $course_pages_tabselected, TRUE); 
				   ?>

					<?php $this->load->view('search/search_left_coloumn'); ?>
					<?php $this->load->view('search/search_right_coloumn'); ?>
				
				</div><!--search-content ends-->
			</div><!--search-wrap ends-->
		</div><!-- main page ends-->
		<!--
		<div id="comparePage">
			<?php //$this->load->view('search/search_compare_institute_page');?>
		</div>
		-->
		
		
		
	</div><!--wrapperFxd ends -->
	<?php $this->load->view('search/search_bar_bottom'); ?>
</div><!--content-wrapper ends-->
<?php
	//$channelId = isset($urlparams['channelId']) ? $urlparams['channelId'] : 'MAIN_SEARCH_PAGE';
	//$adsProperties = array('keyword'=> addslashes(htmlspecialchars($solr_institute_data['raw_keyword'])), 'channelId'=>$channelId, 'urlparams' => $urlparams);
	//$adsProperties = array('keyword'=> addslashes($solr_institute_data['raw_keyword']), 'channelId'=>$channelId, 'urlparams' => $urlparams);
	//$this->load->view('search/search_google_results', $adsProperties);
	//$this->load->view('search/search_google_results_csa', $adsProperties);
	
	$this->load->view('common/footerNew',array('loadJQUERY' => 'YES', 'searchPage' => 'YES'));
	$this->load->view('search/searchPageJquerySlider');
?>

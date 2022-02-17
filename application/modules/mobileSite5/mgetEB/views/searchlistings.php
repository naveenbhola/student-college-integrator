<?php ob_start('compress'); ?>
<?php
	$keyword = (!empty($solr_institute_data['raw_keyword']))  ? htmlspecialchars($solr_institute_data['raw_keyword']) : '';
	$title = 'Shiksha.com - Search Results – Education – College – University – Study Abroad – Scholarships – Education Events – Admissions - Notifications -'.htmlspecialchars($keyword);
	$metDescription = 'Search Shiksha.com for Colleges, University, Institutes, Foreign Education programs and information to study in India. Find course / program details, admissions, scholarships of universities of India and from countries all over the world -'.htmlspecialchars($keyword);
	$metaKeywords = 'Shiksha, Study, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships -'.htmlspecialchars($keyword);
	$headerComponents = array(
	'm_meta_title'=>$title,
	'm_meta_description'=>$metDescription,
	'm_meta_keywords'=>$metaKeywords
	);
	$this->load->view('mcommon5/header',$headerComponents);
?>

<?php 
$urlstring = "";
foreach ($searchurlparams as $keyword => $value) {
	$urlstring .= $keyword."=".$value."&";	
}
?>

<div id="wrapper" data-role="page"  >
	<?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel','false');?>
    
	<!-- Show the Search page Header -->    
	<header id="page-header" class="clearfix" data-role="header">
	    <div class="head-group" data-enhance="false">
		<a class="head-icon" href="#mypanel"><i class="icon-menu"></i></a>
		<h1>
			<div class="left-align" style="margin-right:50px;">
			    <?=displayTextAsPerMobileResolution(html_escape($searchurlparams['keyword']),2,true)?><br />
			    <?php if($solr_institute_data['total_institute_groups']>0):?>
				    <p>Total <?php echo getPlural($total_results, 'Institute');?> found</p>
			    <?php endif;?>
			</div>
			<span style="cursor: pointer;" onclick="window.location='<?=SHIKSHA_HOME?>';" class="head-icon-r"><i class="icon-home"></i></span>		    
		</h1>
		<div class="head-filter" id="showFilterButton" style="display: none;">
				<?php if($refine_action == '/search/refineSearch'):?>
				<a id="refineOverlayOpen" href="#refineDiv" data-inline="true" data-rel="dialog" data-transition="slide" >
					<i class="icon-busy" aria-hidden="true"></i>
					<p>Filter</p>
				</a>
				<?php else:?>
				<form method="post" action="<?php echo $refine_action;?>" id="refineForm">
					<input type="hidden" name="keyword" value="<?php echo url_base64_encode($searchurlparams['keyword']);?>"/>
					<input type="hidden" name="city_id" value="<?php echo $searchurlparams['city_id'];?>"/>	 
					<input type="hidden" name="course_type" value="<?php echo $searchurlparams['course_type'];?>"/>
					<input type="hidden" name="course_level" value="<?php echo $searchurlparams['course_level'];?>"/>
					<a href="javascript:void(0);" onClick="$('#refineForm').submit();">
						<i class="icon-busy" aria-hidden="true"></i>
						<p>Filter</p>
					</a>
				</form>
				<?php endif;?>
		</div>
	    </div>
	</header>    
	<!-- End the Search for Category page -->
		
        <div data-role="content">
        	<?php 
		        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
		    ?>
		<div data-enhance="false">
		
			<!--	Refine Options Header -->
			<?php
			if(!empty($searchurlparams['city_id']) || !empty($searchurlparams['country_id']) || !empty($searchurlparams['zone_id']) || !empty($searchurlparams['locality_id'])):
			$msearch_locations = json_decode(base64_decode($_COOKIE['msearch_locations']),true);
			$temp_location = $search_lib_object->getLocationFacets();
			foreach ($temp_location as $key=>$value) {
				$msearch_locations[$key] = $value['name'];
				foreach ($value['cities'] as $key1=>$value1) {
					$msearch_locations[$key1] = $value1['name'];
					foreach ($value1['zone'] as $key2=>$value2) {
						$msearch_locations[$key2] = $value2['name'];
						foreach ($value2['locality'] as $key3=>$value3) {
							$msearch_locations[$key3] = $value3['name'];
						}
					}
				}
			}
			$zone_key = $searchurlparams['zone_id']?$searchurlparams['zone_id']:0;
			$locality_key = $searchurlparams['locality_id']?$searchurlparams['locality_id']:0;
			$city_key = $searchurlparams['city_id']?$searchurlparams['city_id']:0;
			$country_key = $searchurlparams['country_id']?$searchurlparams['country_id']:0;
			$showLocation = true;
			$refinedata['showLocation'] = true;
			if($locality_key>0){
				$refinedata['locationSelected'] = $msearch_locations[$locality_key];
			}
			else if($zone_key>0){
				$refinedata['locationSelected'] = $msearch_locations[$zone_key];
			}
			else if($city_key>0){
				$refinedata['locationSelected'] = $msearch_locations[$city_key];
			}
			else if($country_key>0){
				$refinedata['locationSelected'] = $temp_location[$country_key]['name'];
			}
			endif;?>
			
			<?php if(!empty($searchurlparams['course_type'])):
			$showCourseType = true;
			$refinedata['typeSelected'] = msearchLib::$course_type_array[$searchurlparams['course_type']];
			endif;?>
			
			<?php if(!empty($searchurlparams['course_level'])):
			$showCourseLevel = true;
			$refinedata['levelSelected'] = msearchLib::$course_level_array[$searchurlparams['course_level']];
			endif;?>
			
			<?php if($showLocation || $showCourseLevel || $showCourseType)	{ ?>
			<section id="showSelectedFilters" class="filter-applied" onClick="$('#refineOverlayOpen').click();" style="cursor:pointer;">
			    <div class="filter-child">
				<strong>Applied Filters : Tap to Edit</strong>
				<p>
					<?php if($showLocation){echo $refinedata['locationSelected'];} ?>
					<?php if($showCourseType && $showLocation){echo "<span>.</span>";} ?>
					<?php if($showCourseType){echo (!empty(msearchLib::$course_type_array[$searchurlparams['course_type']]))?msearchLib::$course_type_array[$searchurlparams['course_type']]:$searchurlparams['course_type'];} ?>
					<?php if(($showCourseType || $showLocation) && $showCourseLevel){echo "<span>.</span>";} ?>
					<?php if($showCourseLevel){echo (!empty(msearchLib::$course_level_array[$searchurlparams['course_level']]))?msearchLib::$course_level_array[$searchurlparams['course_level']]:$searchurlparams['course_level'];} ?>
				</p>
				<div class="filter-arr"></div>
			    </div>
			</section>
			<?php } ?>

			
			<?php
			$this->load->view("showSearchResults");
			?>
			
		</div>
		<?php $this->load->view('mcommon5/footerLinks');?>
	</div>

</div>
<input type="hidden" value="<?php echo $urlstring?>" id="search_string" />


<div data-role="page" id="refineDiv" data-enhance="false"><!-- dialog--> 
	<div id="loading" style="text-align:center;margin-top:10px;display:block ; top:40%;position:absolute;left:46%"><img id="loadingImage" src="/public/mobile5/images/ajax-loader.gif" border=0 alt="" ></div>
</div>

<div data-role="page" id="locationDiv" data-enhance="false"><!-- dialog--> 
 <?php //$this->load->view('refine_location',$refinedata); ?>
</div>

<?php $this->load->view('mcommon5/footer');?>
<?php ob_end_flush(); ?>


<script type="text/javascript">

$( document ).ready(function() {
	showFilterHtmlForSearch();
	});
			
		
</script>

<?php ob_start('compress'); ?>
<?php
	$keyword = (!empty($solr_institute_data['raw_keyword']))  ? htmlspecialchars($solr_institute_data['raw_keyword']) : '';
	$title = 'Shiksha.com - Search Results – Education – College – University – Study Abroad – Scholarships – Education Events – Admissions - Notifications -'.htmlspecialchars($keyword);
	$metDescription = 'Search Shiksha.com for Colleges, University, Institutes, Foreign Education programs and information to study in India. Find course / program details, admissions, scholarships of universities of India and from countries all over the world -'.htmlspecialchars($keyword);
	$metaKeywords = 'Shiksha, Study, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships -'.htmlspecialchars($keyword);
	$headerComponents = array(
	'm_meta_title'=>$title,
	'm_meta_description'=>$metDescription,
	'm_meta_keywords'=>$metaKeywords,
	'jsMobile'	=> array('searchTrackingMobile'),
	'addNoFollow'=>"true"

	);
	$this->load->view('mcommon5/header',$headerComponents);
?>

<?php 
$urlstring = "";
foreach ($searchurlparams as $keyword => $value) {
	$urlstring .= $keyword."=".$value."&";	
}
?>

<div id="popupBasic" style="display:none">	
<header class="recommen-head" style="border-radius:0.6em 0.6em 0 0;">
       <p style="width:210px;" class="flLt">Students who showed interest in this institute also looked at</p>
       <a href="#" class="flRt popup-close" onclick = "$('#popupBasic').hide();$('#popupBasicBack').hide();">&times;</a>
       <div class="clearfix"></div>
       </header>
	<div id="recomendation_layer_listing" style="margin-top:20px;margin-bottom:20px;"></div>
</div>
<div id="popupBasicBack" data-enhance='false'>	
</div>

<div id="wrapper" data-role="page" style="min-height: 413px;padding-top: 40px;">
	<?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel','true');
	      echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
	?>
    
	<!-- Show the Search page Header -->    
	<header id="page-header" class="header ui-header-fixed" data-role="header" data-tap-toggle="false"  data-position="fixed">
	 <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true);?>
	</header>	      
	<!-- End the Search for Category page -->

		
        <div data-role="content">
        	<?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
		
		<?php $this->load->view('searchlistingsSubHeader');?>
		
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

<div data-role="page" id="subcategoryDiv" data-enhance="false"><!-- dialog--> 
</div>

<div data-role="page" id="refineDiv" data-enhance="false"><!-- dialog--> 
	<div id="loading" style="text-align:center;margin-top:10px;display:block ; top:40%;position:absolute;left:46%"><img id="loadingImage" src="/public/mobile5/images/ajax-loader.gif" border=0 alt="" ></div>
</div>

<div data-role="page" id="locationDiv" data-enhance="false"><!-- dialog--> 
 <?php //$this->load->view('refine_location',$refinedata); ?>
</div>

<?php $this->load->view('mcommon5/footer');?>
<?php ob_end_flush(); ?>


<script type="text/javascript">
var result_search_id = '<?php echo $result_search_id;?>';
$( document ).ready(function() {
	showFilterHtmlForSearch();
	});
			
		
</script>

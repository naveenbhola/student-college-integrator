<div style="background:#efefef !important" id="refineDivInner">
<?php
$dataobject = $search_lib_object;
$url_params = $dataobject->getUrlParams();
$searchurlparams = $url_params;
$resetParams = array();
$resetParams["from_page"] = "mobilesearchhome";
$resetParams["keyword"] = $url_params['keyword'];

$location_facets = $dataobject->getLocationFacets();
$name_array = array();
foreach ($location_facets as $key=>$value) {
	$name_array[$key] = $value['name'];
	foreach ($value['cities'] as $key1=>$value1) {
		$name_array[$key1] = $value1['name'];
	}
}
$typeSelected = $searchurlparams['course_type'];
$levelSelected = $searchurlparams['course_level'];

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
// $refinedata['showLocation'] = true;
if($locality_key>0){
	$locationSelected = $msearch_locations[$locality_key];
}
else if($zone_key>0){
	$locationSelected = $msearch_locations[$zone_key];
}
else if($city_key>0){
	$locationSelected = $msearch_locations[$city_key];
}
else if($country_key>0){
	$locationSelected = $temp_location[$country_key]['name'];
}endif;



?>

<script type="text/javascript">
</script>

 	<header id="page-header" class="clearfix">
		<div class="head-group">
		    <a id="refineOverlayClose" href="javascript:void(0);" data-rel="back" ><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>
		    <h3>
			<div class="left-align">
				Refine Your Results
			</div>
		    </h3>
		</div>
	</header>
    
	<section class="refine-section" style="margin-top: 15px;">
	    <label class="text-shadow-w">Location</label>
	    <a href="#locationDiv" class="selectbox" data-inline="true" data-rel="dialog" data-transition="slide"><p><?php if($locationSelected && $locationSelected!=''){ echo $locationSelected;} else {echo "Change Location";}?> <i class="icon-select2"></i></p></a>
	</section>

	<?php if(count($dataobject->getCoursetypeFacets())):?>
		<section class="refine-section">
		    <ul>
		    <li class="search-filter text-shadow-w">
		    <label>Mode of Learning</label>
			<!-- Display the All Option -->
			<?php
				if($searchurlparams['course_type'] && $searchurlparams['course_type']!=''){
					$newsearchurlparams = $searchurlparams;
					$newsearchurlparams['course_type'] = '';
					$course_type = $newsearchurlparams['course_type'];
					$url = urldecode($search_lib_object->makeSearchURL($current_url,$newsearchurlparams));
					echo '<a  href="javascript:void(0);" onclick="loadFilterForSearch(\''.$url.'\');" ><p>All</p></a>';
				}
				else{
					echo "<p class='active'>All<i class='icon-check'></i></p>";					
				}
			?>
			
			<!-- Display the Other Mode Option -->
			<?php foreach($dataobject->getCoursetypeFacets() as $course_type_facet):
				if($course_type_facet['name']== str_replace('-',' ',$typeSelected)){
					$active = true;					
				}
				else{
					$active = false;
					$newsearchurlparams = $searchurlparams;
					$newsearchurlparams['course_type'] = str_replace(' ','-', $course_type_facet['name']);
					
					$url = urldecode($search_lib_object->makeSearchURL($current_url,$newsearchurlparams));
					echo '<a href="javascript:void(0);" onclick="loadFilterForSearch(\''.$url.'\');">';
				}
			?>
				<?php if($course_type_facet['count']>0):?>
					<p <?php if($active){ echo "class='active'";} ?>><?php echo $course_type_facet['name'].' ('.$course_type_facet['count'].')';?><?php if($active){ echo "<i class='icon-check'></i>";} ?></p>
				<?php else:?>
					<p <?php if($active){ echo "class='active'";} ?>><?php echo $course_type_facet['name'];?><?php if($active){ echo "<i class='icon-check'></i>";} ?></p>
				<?php endif;?>
			<?php
				if($course_type_facet['name']!= str_replace('-',' ',$typeSelected)){
					echo '</a>';
				}			
				endforeach;?>
		</li>
	   </ul>
	</section>
	<?php endif;?>
    
	<?php if(count($dataobject->getCourselevelFacets())):?>
	<section class="refine-section" style="box-shadow:none; border:0 none">
	    <ul>
		<li class="search-filter text-shadow-w">
		<label>Course Level</label>
			<!-- Display the All Option -->
			<?php
				if($searchurlparams['course_level'] && $searchurlparams['course_level']!=''){
					$newsearchurlparams = $searchurlparams;
					$newsearchurlparams['course_level'] = '';
					$course_level = $newsearchurlparams['course_level'] = '';
					$url = urldecode($search_lib_object->makeSearchURL($current_url,$newsearchurlparams));
					echo '<a  href="javascript:void(0);" onclick="loadFilterForSearch(\''.$url.'\');" ><p>All</p></a>';
				}
				else{
					echo "<p class='active'>All<i class='icon-check'></i></p>";					
				}
			?>
			
			<!-- Display the Other Level Option -->
			<?php foreach($dataobject->getCourselevelFacets() as $course_level_facet):
				if($course_level_facet['name']== str_replace('-',' ',$levelSelected)){
					$activeL = true;
				}
				else{
					$activeL = false;
					$newsearchurlparams = $searchurlparams;
					$newsearchurlparams['course_level'] = str_replace(' ','-',$course_level_facet['name']);
					$url = urldecode($search_lib_object->makeSearchURL($current_url,$newsearchurlparams));
// 					$course_level = $course_level_facet['name'];
					echo '<a href="javascript:void(0);" onclick="loadFilterForSearch(\''.$url.'\');">';
// 					echo '<a href="'.$course_level_facet['url'].'">';					
				}
			?>
			<?php if($course_level_facet['count']>0):?>
			<p <?php if($activeL){ echo "class='active'";} ?>><?php echo $course_level_facet['name'].' ('.$course_level_facet['count'].')';?><?php if($activeL){ echo "<i class='icon-check'></i>";} ?></p>
			<?php else:?>
			<p <?php if($activeL){ echo "class='active'";} ?>><?php echo $course_level_facet['name'];?><?php if($activeL){ echo "<i class='icon-check'></i>";} ?></p>
			<?php endif;?>
			<?php
				if($course_level_facet['name']!= str_replace('-',' ',$levelSelected)){
					echo "</a>";
				}
				endforeach;?>
		</li>
	   </ul>
	</section>
       <?php endif;?>
    
    <?php
    	  $resetUrl = urldecode($search_lib_object->makeSearchURL($current_url,$resetParams));
    	  $current_url = SHIKSHA_HOME.'/search/index';
    	  $refineUrl = urldecode($search_lib_object->makeSearchURL($current_url,$searchurlparams));
    	   
    ?>
	<div class="clearfix" style="margin:0 0 5px">
	<a href="javascript:void(0);" onclick="refineClick('<?php echo $refineUrl; ?>');" class="refine-btn flLt" style="width:49%; border-top:1px solid #a4a4a6">Refine</a>
	<a href="javascript:void(0);" onclick="loadFilterForSearch('<?php echo $resetUrl;?>')" class="cancel-btn flRt" style="width:49%; background:#b7b5b6; border-top:1px solid #a4a4a6">Reset</a>
	</div>
	<a href="javascript:void(0);" onClick="cancelFilterClick();" class="cancel-btn">Cancel</a>
</div>

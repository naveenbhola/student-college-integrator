<?php
global $pageHeading;
global $filters;
global $appliedFilters;
$filters = $categoryPage->getFilters();
$appliedFilters = $request->getAppliedFilters();
$tagText = "Courses";
if($request->isTestPrep()){
			if(stripos($pageHeading,"Coaching")>0){
				$tagText = "";		
			}else{
			    $tagText = "Coaching";
			}
}


$durationFilterValues = $filters['duration'] ? $filters['duration']->getFilteredValues() : array();
$durationTypes = $filters['duration'] ? $filters['duration']->getDurationTypes() : array();
$examFilterValues = $filters['exams'] ? $filters['exams']->getFilteredValues() : array();
$modeFilterValues = $filters['mode'] ? $filters['mode']->getFilteredValues() : array();
$courseLevelFilterValues = $filters['courseLevel'] ? $filters['courseLevel']->getFilteredValues() : array();
modify_courselevel_filter_values($courseLevelFilterValues);

$filterCount = 0;
if(count($durationFilterValues) > 1){
		$filterCount += 1;	
}
if(count($modeFilterValues) > 1){
		$filterCount += 1;	
}
if(count($courseLevelFilterValues) > 1){
		$filterCount += 1;	
}
if(count($examFilterValues) > 1){
		$filterCount += 1;	
}
$filterDisplayed = 0;

$showExamString = 'Change Exam';
if(isset($appliedFilters['exams']) && count($appliedFilters['exams'])>0){
			$count = count($appliedFilters['exams']);
			$showExamString = "Selected ($count)";
}

$showLocationString = 'Change Location';
$count = 0;
if(isset($appliedFilters['city']) && count($appliedFilters['city'])>0){
			$count = count($appliedFilters['city']);
			$showLocationString = "Selected ($count)";
}
if(isset($appliedFilters['state']) && count($appliedFilters['state'])>0){
			$count += count($appliedFilters['state']);
			$showLocationString = "Selected ($count)";
}
if(isset($appliedFilters['country']) && count($appliedFilters['country'])>0){
			$count += count($appliedFilters['country']);
			$showLocationString = "Selected ($count)";
}

$requestUrl = clone $request;
$currentUrl = $requestUrl->getURL();
$filterTrackingURL = '/categoryList/CategoryList/trackFilters/'.$request->getCategoryId()."-".$request->getSubCategoryId()."-".$request->getLDBCourseId()."-".$request->getLocalityId()."-".$request->getZoneId()."-".$request->getCityId()."-".$request->getStateId()."-".$request->getCountryId()."-".$request->getRegionId()."-none-1-".$request->isNaukrilearningPage();
$key = $request->getPageKey();
$categoryFlag = 'abroad';
$subCategories = $categoryRepository->getSubCategories($request->getCategoryId(),$categoryFlag);
?>

<script>
var url = '<?=$currentUrl?>';
var filterTrackingURL = '<?=$filterTrackingURL?>';
var categoryKey = '<?=$key?>';
</script>

<div style="background:#efefef !important">
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
    
        <!-- Course Section -->
	<section class="refine-section" style="margin-top: 5px;">
	    <label class="text-shadow-w">Course</label>
	    <a href="#courseDiv" class="selectbox" id="courseOverlayOpen" data-inline="true" data-rel="dialog" data-transition="slide" onclick="trackEventByGAMobile('HTML5_SA_CATEGORY_COURSE_FILTER');">
		<p id ="courseNameSA">
		<?php $flag= true;
			foreach($subCategories as $row){
			      if($row->getId() == $request->getSubCategoryId()){
				   echo $row->getName();
			           $flag=false;
			       }
			}
			
			if($flag) echo "Change Course";?>
		<i class="icon-select2"></i>
		</p>
	</a>
	</section>


        <!-- Duration Section -->
<?php if(count($durationFilterValues) > 1){?>
    <section class="refine-section">
	<ul>
		<li>
		<label class="text-shadow-w">Duration</label>
			<div class="duration-cont clearfix">
			<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
			<?php $i=0;
			foreach($durationFilterValues as $filter){
			$class = '';
			if($appliedFilters == false){
				//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['duration'])){
				$class = "active";
			}
			?>
			<td id="duration<?=$i?>" class="<?=$class?>" onClick="toggleDurationClass('<?php echo 'duration'.$i;?>') ;trackEventByGAMobile('HTML5_SA_CATEGORY_DURATION_FILTER');"><?=$filter?></td>
			<?php $i++;} ?>
			</tr>
		    </table>
		</div>
	    </li>
	</ul>
    </section>
<?php } ?>	
	
        <!-- Exam Section -->
<?php if(count($examFilterValues) > 1){ ?>        
	<section class="refine-section" >
	    <label class="text-shadow-w">Exam Accepted</label>
	    <a href="#examDiv" class="selectbox" id="examOverlayOpen" data-inline="true" data-rel="dialog" data-transition="slide" onclick="trackEventByGAMobile('HTML5_SA_CATEGORY_EXAM_FILTER');"><p id="exam_div"><?=$showExamString?> <i class="icon-select2"></i></p></a>
	</section>
<?php } ?>	

	<!-- Mode Section -->
<?php if(count($modeFilterValues) > 1){ ?>
    <section class="refine-section">
	<ul class="course-level">
		<li>
		<label class="text-shadow-w"><strong>Mode</strong></label>
			<?php foreach($modeFilterValues as $filter){
					$checked = '';
					if($appliedFilters == false){
								//$checked = "checked";	
					}
					elseif(in_array($filter,$appliedFilters['mode'])){
								$checked = "checked";
					}
					?>
			<span><label><input type="checkbox" <?=$checked?> name="mode[]" value = "<?=$filter?>" onclick="trackEventByGAMobile('HTML5_SA_CATEGORY_MODE_FILTER');" /> <?=$filter?></label></span>
			<?php } ?>
	        </li>
	</ul>
    </section>
<?php } ?>

<?php if(count($courseLevelFilterValues) > 1){ ?>
    <section class="refine-section">
	<ul class="course-level">
		<li>
		<label class="text-shadow-w"><strong>Course Level</strong></label>
			<?php foreach($courseLevelFilterValues as $filter){
					$checked = '';
					if($appliedFilters == false){
								//$checked = "checked";	
					}
					elseif(in_array($filter,$appliedFilters['courseLevel'])){
								$checked = "checked";
					}
					?>
			<span><label><input type="checkbox" <?=$checked?> name="courseLevel[]" value = "<?=$filter?>" onclick="trackEventByGAMobile('HTML5_SA_CATEGORY_COURSE_LEVEL_FILTER');" /> <?=$filter?></label></span>
			<?php } ?>
	        </li>
	</ul>
    </section>
<?php } ?>

<?php
$locationDataFlag = 1;
global $filter_list_title;
global $filter_list_source;
$filter_list_source='country';
if($filters['country'] && count($filters['country']->getFilteredValues()) > 1){
                        $filter_list_source = "country";
                        $filter_list_title = "Countries";
} else if(is_array($topFilteredCitieslist) && count($topFilteredCitieslist) > 1){
                        $filter_list_source = "topFilteredCities";
                        $filter_list_title = "State & Top Cities";
} else if($filters['city'] && count($filters['city']->getFilteredValues()) > 1){
                        $filter_list_source = "city";
                        $filter_list_title = "State & Cities";
} else {
                        $locationDataFlag = 0;
}

if($locationDataFlag == 1) {
?>	
	<section class="refine-section">
	    <label class="text-shadow-w">Location</label>
	    <a href="#locationDiv" class="selectbox" id="locationOverlayOpen" data-inline="true" data-rel="dialog" data-transition="slide" onclick="trackEventByGAMobile('HTML5_SA_CATEGORY_LOCATION_FILTER');" ><p id="location_div"><?=$showLocationString?> <i class="icon-select2"></i></p></a>
	</section>
<?php } ?>

        <a href="javascript:void(0)" onclick="applyFiltersOnCategoryPages(); trackEventByGAMobile('HTML5_SA_CATEGORY_REFINE_FILTER');" class="refine-btn">Refine</a>
        <a href="javascript:void(0);" onclick="clearFiltersOnCategoryPages();" class="cancel-btn">Clear All</a>
	
</div>
<input type="hidden" id="filter_list_resource" value="<?php echo $filter_list_source ?>" />

<script>
var durationSelectedCount = <?php if(isset($appliedFilters['duration']) && count($appliedFilters['duration'])>0 ){echo count($appliedFilters['duration']);} else{ echo 0;} ?>;
</script>

<?php
function modify_courselevel_filter_values(&$courseLevelFilterValues) {
        $ug_pg_phd_page_identifier = $_COOKIE['ug-pg-phd-catpage'];
        global $COURSELEVEL_TOBEHIDDEN_CONFIG;

        if(empty($ug_pg_phd_page_identifier)) {
                return true;
        }
        foreach($courseLevelFilterValues as $key =>$value) {
                if(!in_array($key,$COURSELEVEL_TOBEHIDDEN_CONFIG[$ug_pg_phd_page_identifier])) {
                        unset($courseLevelFilterValues[$key]);
                }

        }
}
?>

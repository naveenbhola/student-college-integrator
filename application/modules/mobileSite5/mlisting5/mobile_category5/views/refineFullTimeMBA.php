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
$feesFilterValues = $filters['fees'] ? $filters['fees']->getFilteredValues() : array();
$modeFilterValues = $filters['mode'] ? $filters['mode']->getFilteredValues() : array();
$degreeFilterValues = $filters['degreePref'] ? $filters['degreePref']->getFilteredValues() : array();

$filterCount = 0;
if(count($durationFilterValues) > 1){
		$filterCount += 1;	
}
if(count($modeFilterValues) > 1){
		$filterCount += 1;	
}
if(count($degreeFilterValues) > 1){
		$filterCount += 1;	
}
if(count($examFilterValues) > 1){
		$filterCount += 1;	
}
if(count($feesFilterValues) > 1){
		$filterCount += 1;	
}
$filterDisplayed = 0;

$showExamString = 'Change Exam';
if(isset($appliedFilters['exams']) && count($appliedFilters['exams'])>0){
			$count = count($appliedFilters['exams']);
			$showExamString = "Selected ($count)";
}

$showFeesString = 'Change Fees';
if(isset($appliedFilters['fees']) && count($appliedFilters['fees'])>0){
			$count = count($appliedFilters['fees']);
			$showFeesString = $appliedFilters['fees'][0];
}

$locationCount = 0;
$showLocationString = 'Change Location';
if(isset($appliedFilters['city']) && count($appliedFilters['city'])>0){
			$locationCount += count($appliedFilters['city']);
}
if(isset($appliedFilters['locality']) && count($appliedFilters['locality'])>0){
			$locationCount += count($appliedFilters['locality']);
}
if($locationCount>0){
			$showLocationString = "Selected ($locationCount)";
}

$requestUrl = clone $request;
$currentUrl = $requestUrl->getURL();
if($categoryPageTypeFlag == CP_NEW_RNR_URL_TYPE && in_array($request->getSubCategoryId(), $subcategoriesChoosenForRNR)){
	$newRequest = clone $request;
	$URLPgnoUpdate['pageNumber'] = 1;
	$newRequest->setData($URLPgnoUpdate);
	$urlData = $newRequest->urlManagerObj->getURL();
	$filterTrackingURL = '/categoryList/CategoryList/rnrTrackFilters/'. $request->getPageKeyString();
} else {
	$filterTrackingURL = '/categoryList/CategoryList/trackFilters/'.$request->getCategoryId()."-".$request->getSubCategoryId()."-".$request->getLDBCourseId()."-".$request->getLocalityId()."-".$request->getZoneId()."-".$request->getCityId()."-".$request->getStateId()."-".$request->getCountryId()."-".$request->getRegionId()."-none-1-".$request->isNaukrilearningPage();
}
$key = $request->getPageKey();
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
	    <label class="text-shadow-w">Specialisation</label>
	   
	    <a href="#courseDiv" class="selectbox" id="courseOverlayOpen" data-inline="true" data-rel="dialog" data-transition="slide" onclick="trackEventByGAMobile('HTML5_MBA_CATEGORY_SPECIALISATION_FILTER');">
		<p id = "courseName">
			<?php
			$row=$categoryPage->getLDBCourse();
			$categoryName=$LDBCourseRepository->getLDBCoursesForSubCategory($request->getSubCategoryId());
		
			
			if((!strcasecmp($row->getSpecialization(),"All")) && (in_array($row->getId(),array(2,13,52,53)) || $row->getCourseName() == $categoryPage->getSubCategory()->getName())){
				echo $row->getCourseName();
			}
			else if($row->getId() == $request->getLDBCourseId()){
			          $selectedVal=($row->getSpecialization()!="All"?$row->getSpecialization():$row->getCourseName());
				  echo $selectedVal;
			          
			}
			else if($categoryName[0]){
			      echo $categoryName[0]->getCourseName();
			}
			else {
			     echo "Change Specialisation";
			}
			?>
			<i class="icon-select2"></i>
			</p></a>
	</section>
	
        <!-- Exam Section -->
<?php if(count($examFilterValues) > 1){ ?>        
	<section class="refine-section" >
	    <label class="text-shadow-w">Exam Accepted</label>
	    <a href="#examDiv" class="selectbox" id="examOverlayOpen" data-inline="true" data-rel="dialog" data-transition="slide" onclick="trackEventByGAMobile('HTML5_MBA_CATEGORY_EXAM_FILTER');"><p id="exam_div"><?=$showExamString?> <i class="icon-select2"></i></p></a>
	</section>
<?php } ?>	

<?php if(count($feesFilterValues) > 1){ ?>        
	<section class="refine-section" >
	    <label class="text-shadow-w">Course Fees</label>
	    <a href="#feesDiv" class="selectbox" id="feesOverlayOpen" data-inline="true" data-rel="dialog" data-transition="slide" onclick="trackEventByGAMobile('HTML5_MBA_CATEGORY_FEES_FILTER');"  ><p id="fees_div"><?=$showFeesString?> <i class="icon-select2"></i></p></a>
	</section>
<?php } ?>





	
<?php if(count($degreeFilterValues) > 1){ ?>
    <section class="refine-section">
	<ul class="course-level">
		<li>
		<label class="text-shadow-w"><strong>Affiliation</strong></label>
			<?php foreach($degreeFilterValues as $filter){
					$checked = '';
					if($appliedFilters == false){
								//$checked = "checked";	
					}
					else if(in_array($filter,$appliedFilters['degreePref'])){
								$checked = "checked";
					}
                                        foreach($affiliationSuffix as $affiliationfilter=>$value){
                                            if($affiliationfilter == $filter)
                                               $filterName=strtoupper($affiliationfilter)." ".ucfirst(strtolower($value))." " ;
                                        }
					?>
			<span><label><input type="checkbox" <?=$checked?> name="degreePref[]" value = "<?=$filter?>" onclick="trackEventByGAMobile('HTML5_MBA_CATEGORY_AFFILIATION_FILTER');" /> <?=$filterName?></label></span>
			<?php } ?>
	        </li>
	</ul>
    </section>
<?php } ?>

<?php
	if(isset($filters['city']) || isset($filters['locality'])){
	      $cityFilterValues = $filters['city']->getFilteredValues();
	      $localityFilterValues = $filters['locality']->getFilteredValues();
	      
	      $cityCount = count($cityFilterValues);
	      $localityCount = 0 ;
	      //get locality count 
	      // changes done by rahul
	      foreach ($localityFilterValues as $index => $locationArray) :
	      		$localityCount += count($locationArray['localities']);
	      endforeach;
	      if( $localityCount > 1 || $cityCount > 1 ){

?>	
	<section class="refine-section" style="padding-bottom:90px">
	    <label class="text-shadow-w">Location</label>
	    <a href="#locationDiv" class="selectbox" id="locationOverlayOpen" data-inline="true" data-rel="dialog" data-transition="slide" onclick="trackEventByGAMobile('HTML5_MBA_CATEGORY_LOCATION_FILTER');"><p id="location_div"><?=$showLocationString?> <i class="icon-select2"></i></p></a>
	</section>
<?php
		}
	}
?>
</div>
<div  id="refine_clearall_div" style="position:fixed;left:0px;bottom:0px;width:100%;float:left;display:block;">
        <a href="javascript:void(0)" onclick="applyFiltersOnCategoryPages(); trackEventByGAMobile('HTML5_MBA_CATEGORY_REFINE');" class="refine-btn">Refine</a>
        <a href="javascript:void(0);" onclick="clearFiltersOnCategoryPages();" class="cancel-btn">Clear All</a>
</div>
<script>
var durationSelectedCount = <?php if(isset($appliedFilters['duration']) && count($appliedFilters['duration'])>0 ){echo count($appliedFilters['duration']);} else{ echo 0;} ?>;
</script>

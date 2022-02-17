<?php
$cityFilters  	= $filters['city'];
$stateFilters  	= $filters['state'];
$examFilters 	= $filters['exam'];
$specializationFilters = $filters['specialization'];
$citySelected = false;

$totalSpecializationFilters = count($specializationFilters);
$defaultSpecializationSelected = false;
foreach($specializationFilters as $filter){
	if($filter->isSelected() == true){
		$defaultSpecializationSelected = true;
	}
}

$totalExamFilters = count($examFilters);
$defaultExamSelected = false;
foreach($examFilters as $filter){
	if($filter->isSelected() == true){
		$defaultExamSelected = true;
	}
}

$showSpecializationFilters = true;
if($totalSpecializationFilters <= 1 && $defaultSpecializationSelected){
	$showSpecializationFilters = false;
}

$showExamFilters = true;
$filterBlockStyle = "width:97%;";
if($totalExamFilters <= 1 && $defaultExamSelected){
	$showExamFilters = false;
	$filterBlockStyle = "width:62%;";
}


if($showExamFilters != false || $showSpecializationFilters != false){
?>

<div style="background:#efefef !important">
	<header id="page-header" class="clearfix">
		<div class="head-group">
		    <a id="refineOverlayClose" href="javascript:void(0);" data-rel="back" ><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>
		    <h3>
			<div class="left-align">
				Refine Top <?php echo $page_headline['page_name'];?> Institutes by
			</div>
		    </h3>
		</div>
	</header>

	
		<?php
		if(!empty($specializationFilters)){
		?>
			<!-- Course Section -->
			<section class="refine-section" style="margin-top: 5px;">
			    <label class="text-shadow-w">Course</label>
			   
			    <a href="#courseSelectionDiv" class="selectbox" id="courseOverlayOpen" data-inline="true" data-rel="dialog" data-transition="slide">
				<p id = "courseName">
					<?php
					      echo "<span id='selectedCourseName'>Change Course</span>";
					?>
					<i class="icon-select2"></i></p>
			    </a>
			</section>
		<?php
		}
		?>
		
		<?php
		$examBlockShown = false;
		if(!empty($examFilters) && count($examFilters) > 1){
			$examBlockShown = true;
			$totalExamFilters = count($examFilters);
			$examFiltersLeft 	= $examFilters;
			$examFiltersRight 	= array();
			if($totalExamFilters > 6){
				$examFiltersLeft 		= array_slice($examFilters, 0, (int)$totalExamFilters / 2);
				$examFilterLeftCount 	= count($examFiltersLeft);
				$examFiltersRight 		= array_slice($examFilters, $examFilterLeftCount, $totalExamFilters);
			}
		?>
			<!-- Exam Section -->
			<section class="refine-section" style="margin-top: 5px;">
			    <label class="text-shadow-w">Exam Accepted</label>
			   
			    <a href="#examSelectionDiv" class="selectbox" id="examOverlayOpen" data-inline="true" data-rel="dialog" data-transition="slide">
				<p id = "examName">
					<?php
					      echo "<span id='selectedExamName'>Change Exam</span>";
					?>
					<i class="icon-select2"></i></p>
			    </a>
			</section>
		<?php
		}
		?>
		
		<?php
		if(!empty($stateFilters) || !empty($cityFilters)){
			$tempCityFilterSelected = NULL;
			$tempStateFilterSelected = NULL;
			foreach($cityFilters as $filter){
				$isSelected = $filter->isSelected();
				if($isSelected){
					$tempCityFilterSelected = $filter;
					break;
				}
			}
			foreach($stateFilters as $filter){
				$isSelected = $filter->isSelected();
				if($isSelected){
					$tempStateFilterSelected = $filter;
					break;
				}
			}
			
			$useCityFilter = true;
			if(!empty($tempCityFilterSelected) && !empty($tempStateFilterSelected)){
				$tempCitySelected = $tempCityFilterSelected->getName();
				$tempStateSelected = $tempStateFilterSelected->getName();
				if(strtolower($tempCitySelected) == "all" && trim($tempCitySelected) != ""){
					$useCityFilter = false;
				}
			}
		
		$className = "locality-col";
		$blockRightBorderStyle = "";
		if(!$examBlockShown){
			$className = "ent-col";
			$blockRightBorderStyle = "border-right:none;";
			
		}
		?>
			<!-- Location Section -->
			<section class="refine-section" style="margin-top: 5px;">
			    <label class="text-shadow-w">Location</label>
			   
			    <a href="#locationSelectionDiv" class="selectbox" id="locationOverlayOpen" data-inline="true" data-rel="dialog" data-transition="slide">
				<p id = "locationName">
					<?php
					      echo "<span id='selectedLocationName'>Change Location</span>";
					?>
					<i class="icon-select2"></i></p>
			    </a>
			</section>
		<?php
		}
		?>
	<a href="javascript:void(0);" onclick="$('#refineOverlayClose').click();" class="cancel-btn">Cancel</a>

   </div>
<?php
}
?>

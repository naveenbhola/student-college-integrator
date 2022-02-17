<?php
global $filters;
global $appliedFilters;
global $MBA_SCORE_RANGE;
global $MBA_SCORE_RANGE_CMAT;
global $MBA_SCORE_RANGE_GMAT;
global $MBA_PERCENTILE_RANGE_MAT;
global $MBA_PERCENTILE_RANGE_XAT;
global $MBA_PERCENTILE_RANGE_NMAT;
global $ENGINEERING_EXAMS_REQUIRED_SCORES;
global $MBA_EXAMS_REQUIRED_SCORES;
global $MBA_NO_OPTION_EXAMS;
$durationFilterValues = $filters['duration'] ? $filters['duration']->getFilteredValues() : array();
$durationTypes = $filters['duration'] ? $filters['duration']->getDurationTypes() : array();
$examFilterValues = $filters['exams'] ? $filters['exams']->getFilteredValues() : array();
$modeFilterValues = $filters['mode'] ? $filters['mode']->getFilteredValues() : array();
$courseLevelFilterValues = $filters['courseLevel'] ? $filters['courseLevel']->getFilteredValues() : array();
$courseExamFilteredValues = $filters['courseexams'] ?$filters['courseexams']->getFilteredValues() : array();
$feesFilterValues = $filters['fees'] ?$filters['fees']->getFilteredValues() : array();

//_p($examFilterValues); 

//echo "vinay";





$CI_INSTANCE->config->load('categoryPageConfig');
$feesRange = $CI_INSTANCE->config->item('CP_FEES_RANGE');

if($request->isStudyAbroadPage()) {
	modify_courselevel_filter_values($courseLevelFilterValues);
}

$AIMARatingFilterValues = $filters['AIMARating'] ? $filters['AIMARating']->getFilteredValues() : array();
$classTimingFilterValues = $filters['classTimings'] ? $filters['classTimings']->getFilteredValues() : array();
$degreePrefFilterValues = $filters['degreePref'] ? $filters['degreePref']->getFilteredValues() : array();
$filterCount = 0;

if(CP_SOLR_FLAG) {
if(array_key_exists("NO EXAM REQUIRED", $courseExamFilteredValues)) {
	unset($courseExamFilteredValues['NO EXAM REQUIRED']);
	$noExamRequiredArr = array("No Exam Required" => array("No Exam Required", 0, "percentile"));
	$courseExamFilteredValues["No Exam Required"] = Array ( '0' => "No Exam Required");
}
 }else {
if(array_key_exists("No Exam Required", $courseExamFilteredValues)) {
	unset($courseExamFilteredValues['No Exam Required']);
	$noExamRequiredArr = array("No Exam Required" => array("No Exam Required", 0, "percentile"));
	$courseExamFilteredValues["No Exam Required"] = $noExamRequiredArr;
}
}


//die;

if(count($durationFilterValues) > 1){
		$filterCount += 1;	
}
if(count($courseExamFilteredValues) > 1){
		$filterCount += 1;	
}
if(count($modeFilterValues) > 1){
		$filterCount += 1;	
}
if(count($courseLevelFilterValues) > 1){
		$filterCount += 1;	
}
if(count($AIMARatingFilterValues) > 1){
		$filterCount += 1;	
}
if(count($classTimingFilterValues) > 1){
		$filterCount += 1;	
}
if(count($degreePrefFilterValues) > 1){
		$filterCount += 1;	
}
if(count($feesFilterValues) > 1){
	$filterCount += 1;	
}
$filterDisplayed = 0;


$pageSubCategoryId = $request->getSubcategoryId();
?>

<?php

$examNamesInFilters = array_keys($courseExamFilteredValues);
$validExamFilterCount = 0;
$categoryExams = array();
if($pageSubCategoryId == 23){
	if(isset($exam_list['MBA'])){
		$categoryExams = $exam_list['MBA'];
	}
} else if($pageSubCategoryId == 56){
	if(isset($exam_list['Engineering'])){
		$categoryExams = $exam_list['Engineering'];
	}
} else {
	$categoryExams = array();
}

$showExamFilters = false;
if(count($examNamesInFilters) > 0 && count($categoryExams) > 0 ){
	foreach($examNamesInFilters as $exam){
		if(in_array($exam, $categoryExams)){
			$showExamFilters = true;
			break;
		}
	}
}

?>

<?php
//$urlRequest = clone $request;
if($showExamFilters) { ?>
<ul class ="exam-list">
<li>
	<strong class="flLt">Exams Accepted</strong>
	<?php
		$resetRequest = clone $request;
		$resetRequest->setData(array('examName'=>""));
		$resetPageKey = $resetRequest->getPageKey();
	?>
	<a class="flRt" id="examReset" onclick="removeFilterCookiesOnReset('<?=$resetPageKey?>', 'exam');" style="display:none" href="<?=$resetRequest->getURL()?>">Reset</a>
</li>
	<?php
		$appliedCourseExamNames = array();
		$appliedCourseScoreRange = array();
		foreach($appliedFilters['courseexams'] as $examRange){
			if(!empty($examRange)){
				$examRangeData = explode("_", $examRange);
				if(count($examRangeData) > 1) {
					$tempExamName  = $examRangeData[0];
					$tempExamRange = $examRangeData[1];
					$appliedCourseExamNames[] = $tempExamName;
					$appliedCourseScoreRange[$tempExamName] = $tempExamRange;
				}
			}
		}
		
		foreach($courseExamFilteredValues as $examName => $filterValues){
			$urlRequest = clone $request;
			$urlExamName = $request->getExamName();
			if(!in_array($examName, $categoryExams)){
				continue;
			}
			$checked = '';
			$examScoreContDisplayStyle = "display:none;";
			if(in_array($examName, $appliedCourseExamNames)){
				$examScoreContDisplayStyle = "display:block;";
				$checked = "checked";
				$disabled = "";
			}
			else{
				$checked = "";
				$disabled = "disabled";
			}
			if(!$appliedCourseExamNames){
				$disabled = "";
			}
			
			if(!$urlExamName)
			{
				$disabled = "";
			}
			
			$labelStyle = "";
			if(in_array($examName, $MBA_NO_OPTION_EXAMS)){
				$examScoreContDisplayStyle = "display:none;";
				$labelStyle = "width:120px;";
			}
			
			//$urlRequest->setData(array('examName'=>$examName));
			?>
		    <li>
				<label style="<?php echo $labelStyle;?>">
				<?php if($disabled=='disabled')
				{ ?>
					<span class="checkbox-disabled"></span>
					<a onclick="return false;" style="text-decoration:none;color:gray;cursor: default;"
						href="#">
						<?=$examName?>
					</a>
				<?php }
				else { ?>
					<input onclick="onExamSelect('<?php echo trim($examName);?>', this, '<?php echo $pageSubCategoryId;?>', undefined);" id="exam_cb_<?php echo trim($examName);?>" type="checkbox" <?=$checked?> <?=$disabled?> name="courseexams[]" value = "<?=$examName?>" style="padding:0;margin:0;"/>
						<a onclick="onExamSelect('<?php echo trim($examName);?>', $('exam_cb_<?php echo trim($examName);?>'), '<?php echo $pageSubCategoryId;?>', undefined, 'oppositeaction'); return false;" style="text-decoration:none;color:black;cursor: default;"
							href="#">
							<?=$examName?>
						</a>
				<?php } ?>
				</label>
				<?php
				if($pageSubCategoryId == 23){
					$placeHolderText = "Cut-off percentile";
					$defaultDropdownValue = "Any";
					if(in_array($examName, $MBA_EXAMS_REQUIRED_SCORES)){
						$placeHolderText = "Cut-off score";
					}
				?>
				<select style="width:137px;<?php echo $examScoreContDisplayStyle;?>" id="courseexamdd_<?php echo trim($examName);?>" onchange="onExamRangeChange('<?php echo trim($examName);?>');">
						<option value="0"><?php echo $placeHolderText;?></option>
						<option value="0"  <?php echo (isset($appliedCourseScoreRange[$examName]) && $appliedCourseScoreRange[$examName] == 0) ? "selected" : ""; ?>><?php echo $defaultDropdownValue;?></option>
						<?php
						$scoreRanges = $MBA_SCORE_RANGE;
						if($examName == "CMAT"){
							$scoreRanges = $MBA_SCORE_RANGE_CMAT;
						} else if($examName == "GMAT"){
							$scoreRanges = $MBA_SCORE_RANGE_GMAT;
						} else if($examName == "MAT"){
							$scoreRanges = $MBA_PERCENTILE_RANGE_MAT;
						} else if($examName == "XAT") {
							$scoreRanges = $MBA_PERCENTILE_RANGE_XAT; 
						} else if($examName == "NMAT"){
							$scoreRanges = $MBA_PERCENTILE_RANGE_NMAT;
						}
						foreach($scoreRanges as $key => $value){
							?>
							<option value="<?php echo $value;?>" <?php echo (!empty($appliedCourseScoreRange[$examName]) && $appliedCourseScoreRange[$examName] == $value) ? "selected" : ""; ?> ><?php echo $key;?></option>
							<?php
						}
						?>
				</select>
				<?php
				} else if($pageSubCategoryId == 56){
					$placeHolderText = "Enter Rank";
					if(in_array(trim($examName), $ENGINEERING_EXAMS_REQUIRED_SCORES)){
						$placeHolderText = "Enter Score";
					}
				?>
					<input style="width:75px;<?php echo $examScoreContDisplayStyle;?>" type="text" id="courseexamtextbox_<?php echo trim($examName);?>" onblur="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');" default="<?php echo $placeHolderText;?>" value="<?php echo (!empty($appliedCourseScoreRange[$examName])) ? $appliedCourseScoreRange[$examName] : $placeHolderText; ?>"></input>
				<?php
				}
				?>
			</li>
		<?php
		}
		?>
<?php 
	$filterDisplayed++;
	echo '</ul>';
}
?>

<?php if(count($feesFilterValues) > 1){
?>
<ul>
<li>
	<strong class="flLt">Fees</strong>
	<?php
		$resetRequest = clone $request;
		$resetRequest->setData(array('feesValue'=>0));
		$resetPageKey = $resetRequest->getPageKey();
	?>
	<a class="flRt" id="feesReset" onclick="removeFilterCookiesOnReset('<?=$resetPageKey?>', 'fees');" style="display:none" href="<?=$resetRequest->getURL()?>">Reset</a>
</li>
	<?php
			$urlFeeValue = $request->getFeesValue();
			foreach($feesFilterValues as $filter) {
				$checked = '';
				if($appliedFilters == false){
							//$checked = "checked";	
				}
				elseif(trim($filter) == $appliedFilters['fees'][0]) {
							$checked = "checked";
				}
				$feeValue = array_search($filter,$feesRange['RS_RANGE_IN_LACS']);
				$urlRequest = clone $request;
				//$urlRequest->setData(array('feesValue'=>$feeValue));
			?>		
			<li>
					<label>
						<input type="radio" <?=$checked?> name="fees" value = "<?=$filter?>"/>
							<a onclick="selectRadioButtons('fees', '<?=$filter?>'); return false;" href="#" style="text-decoration:none;color:black;cursor: default;"> <?=$filter?></a>
					</label>
			</li>
			<?php 	if($urlFeeValue > 0 && $feeValue == $urlFeeValue)
							break;
			?>
	<?php } ?>

<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2) || 1){
			echo '</ul>';
}
}
?>

<?php if(count($durationFilterValues) > 1 && false){?>
<ul>
<li>
	<strong>Duration</strong>
	<?php if(count($durationTypes)> 1){?>
	<a href="#" class="showMoreLink" id="moreLink" onclick="moreDurations();return false;">+ More</a>
	<a href="#" class="showMoreLink" id="lessLink" onclick="lessDurations();return false;" style="display:none;">- Less</a>
	<?php } ?>
</li>
<li class="durationRow">
	<?php foreach($durationFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
				//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['duration'])){
				$checked = "checked";
			}
			?>
	<span><label><input type="checkbox" <?=$checked?> name="duration[]" value = "<?=$filter?>" /> <?=$filter?></label></span>
	<?php } ?>
	<div class="clearFix"></div>
    <?php if(count($durationTypes)> 1){
?>
<div id="moredurations" style="display:<?php if($appliedFilters['durationRange']) echo 'block'; else echo 'none';  ?>">
	<div class="clearFix spacer5"></div>
	<select id="durationtype" onchange="changeDuration();">
		<option value="">Select</option>
		<?php
		foreach($durationTypes as $key=>$filter){
		?>
			<option value="<?=$key?>" <?php if($appliedFilters['durationRange'] && $appliedFilters['durationRange']['type'] == $key) echo "selected='selected'";?>><?=$key?></option>
		<?php
		}
		?>
	</select>
	&nbsp;
	<span id="durationvaluewrapper" style="display:inline; margin:0; width:auto">
	<select id="durationvalue">
		<option value="0">Select</option>
		<?php
		if($appliedFilters['durationRange']) {
			$durationRangeFilteredValues = $durationTypes;
			
			foreach($durationRangeFilteredValues[$appliedFilters['durationRange']['type']] as $fkey => $fvalue) {
				echo "<option value='".$fkey."' ".($appliedFilters['durationRange']['range'] == $fkey?"selected='selected'":"").">".$fvalue."</option>";
			}
		}
		?>
	</select>
	</span>
	
	<script>
		//var durationTypes = <?=json_encode($filters['duration']->getDurationTypes())?>;
		var durationTypes = {};
		<?php
		foreach($durationTypes as $durationType => $durationTypeValues) {
		?>
			var currentDurationTypeValues = new Array();
		<?php
			foreach($durationTypeValues as $durationValueKey => $durationValue) {
		?>		
				currentDurationTypeValues.push({"<?php echo $durationValueKey; ?>":"<?php echo $durationValue; ?>"});
		<?php
			}
		?>
			durationTypes["<?php echo $durationType; ?>"] = currentDurationTypeValues;
		<?php
		}
		if($appliedFilters['durationRange']) {
		?>
			moreDurations();
		<?php
		}
		?>
	</script>
	
</div>
<?php } ?>
</li>

<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2)){
			echo '</ul>';
}
}
?>
<?php if(count($AIMARatingFilterValues) > 1 && false){ ?>
<ul>
<li>
	<div class="clearFix"></div>
	<strong>AIMA Rating</strong>
</li>
<li class="durationRow">
	<?php foreach($AIMARatingFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['AIMARating'])){
						$checked = "checked";
			}
			?>
	<span><label><input type="checkbox" <?=$checked?> name="AIMARating[]" value = "<?=$filter?>"/> <?=$filter?></label></span>
	<?php } ?>
</li>
<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2)){
			echo '</ul>';
}
}
?>
<?php if(count($modeFilterValues) > 1 && false){ ?>
<ul>
<li>
	<strong>Mode</strong>
</li>
<li class="durationRow">
	<?php foreach($modeFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['mode'])){
						$checked = "checked";
			}
			?>
	<span><label><input type="checkbox" <?=$checked?> name="mode[]" value = "<?=$filter?>"/> <?=$filter?></label></span>
	<?php } ?>
    <div class="clearFix"></div>
</li>
<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2)){
			echo '</ul>';
}
}
?>
<?php if(count($courseLevelFilterValues) > 1 && false){ ?>
<ul>
<li>
	<strong>Course Level</strong>
</li>
<li class="durationRow">
	<?php foreach($courseLevelFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['courseLevel'])){
						$checked = "checked";
			}
			?>
	<span><label><input type="checkbox" <?=$checked?> name="courseLevel[]" value = "<?=$filter?>"/> <?=$filter?></label></span>
	<?php } ?>
</li>
<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2)){
			echo '</ul>';
}
}
?>

<?php if(count($degreePrefFilterValues) > 1){ ?>
<ul>
<li>
	<div class="clearFix"></div>
	<strong class="flLt">Degree Preference</strong>
	<?php
		$resetRequest = clone $request;
		$resetRequest->setData(array('affiliation'=>''));
		$resetPageKey = $resetRequest->getPageKey();
	?>
	<a class="flRt" id="degreePrefReset" onclick="removeFilterCookiesOnReset('<?=$resetPageKey?>', 'degreePref');" style="display:none" href="<?=$resetRequest->getURL()?>">Reset</a>
</li>
	
<li class="durationRow">
	<?php foreach($degreePrefFilterValues as $filter){
			$checked = '';
			$disabled = "";
			if($appliedFilters == false){
				$disabled = "";
			}
			else if(in_array($filter,$appliedFilters['degreePref'])){
				$disabled = "";
				$checked = "checked";
			}
			else{
				$disabled = "disabled";
				$checked = "";
			}
			if(!$appliedFilters['degreePref']){
				$disabled = "";
			}
			$urlRequest = clone $request;
			$urlAffiliation = $request->getAffiliationName();
			if(!$urlAffiliation)
				$disabled = "";
			//$urlRequest->setData(array('affiliation'=>$filter));
			?>
	<span>
		<label>
			<?php if($disabled=='disabled'){ ?>
				<span class="checkbox-disabled"></span>
				<a 
				    onclick="return false;" style="text-decoration:none;color:gray;cursor: default;"
				    href="#" >
				    <?=langStr('approval_'.$filter)?>
				</a>
			<?php }
			else { ?>
			    <input id="degreepref_filter_<?=$filter?>" type="checkbox" <?=$checked?> <?=$disabled?> name="degreePref[]" value = "<?=$filter?>"/>
				<a 
				    onclick="selectFilterCheckBoxes('degreepref_filter_<?=$filter?>');return false;" style="text-decoration:none;color:black;cursor: default;"
				    href="#" >
				    <?=langStr('approval_'.$filter)?>
				</a>
			<?php } ?>
		</label>
	</span>
	
	<?php } ?>
</li>
<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2)){
			echo '</ul>';
}
}
?>
<?php if(count($classTimingFilterValues) > 1 && false){ ?>
<ul>
<li>
	<div class="clearFix"></div>
	<strong>Class Timings</strong>
</li>
<li class="durationRow">
	<?php foreach($classTimingFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['classTimings'])){
						$checked = "checked";
			}
			?>
	<span><label><input type="checkbox" <?=$checked?> name="classTimings[]" value = "<?=$filter?>"/> <?=langStr($filter)?></label></span>
	<?php } ?>
</li>
<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2)){
			echo '</ul>';
}
}
?>

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
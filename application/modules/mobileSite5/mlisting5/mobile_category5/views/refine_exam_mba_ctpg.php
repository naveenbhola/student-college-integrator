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

$filters = $categoryPage->getFilters();
$appliedFilters = $request->getAppliedFilters();
$examFilterValues = $filters['exams'] ? $filters['exams']->getFilteredValues() : array();
$count= 0;
if(isset($appliedFilters['exams']) && count($appliedFilters['exams'])>0){
	$count = count($appliedFilters['exams']);
}
$courseExamFilteredValues = $filters['courseexams'] ?$filters['courseexams']->getFilteredValues() : array();
if(array_key_exists("No Exam Required", $examFilterValues)) {
        unset($examFilterValues['No Exam Required']);
        $noExamRequired = "No Exam Required";
        $examFilterValues["No Exam Required"] = $noExamRequired;
}
?>
<header id="page-header" class="clearfix" >
        <div data-role="header" data-position="fixed" class="head-group" data-tap-toggle="false">
	    <a id="examOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   
            <h3>Select Exams given by you</h3>
        </div>
</header>
<section class="layer-wrap">
	<p class="rnr-title-txt">This is an optional field, please select your exam score to get better result</p>
	<ul class="layer-list rnr-list">
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

	foreach($examFilterValues as $filter){ 
		$courseexamsArr = $appliedFilters['courseexams'];
		$i++;
                $checked = '';
		$examScoreContDisplayStyle = "display:none;";
                                if($appliedFilters == false){
                                                        //$checked = "checked"; 
                                }
                                elseif(in_array($filter,$appliedFilters['exams'])){
                                      $checked = "checked";
				      $examScoreContDisplayStyle = "display:block;";
                                }

	$labelStyle = "";
                        if(in_array($filter, $MBA_NO_OPTION_EXAMS)){
                                $examScoreContDisplayStyle = "display:none;";
                        }
	?>
	<li>
	<label><input onClick="setCPExam('examSet<?=$i?>');setScorePercentileForExams('<?php echo trim($filter);?>', this);" id="examSet<?=$i?>" type="checkbox" <?=$checked?> name="exams[]" value = "<?=$filter?>"/> <?=$filter?></label>
	<?php 
	$placeHolderText = "Cut-off percentile";
        $defaultDropdownValue = "Any";
        if(in_array($filter, $MBA_EXAMS_REQUIRED_SCORES)){
                $placeHolderText = "Cut-off score";
        }
	?>
	<select  style="width: 100%; border-radius: 0px 0px 0px 0px; border: 1px solid rgb(204, 204, 204); background:#fff; outline: medium none; padding:5px;font-size:1em;<?php echo $examScoreContDisplayStyle;?>" id="courseexamdd_<?php echo trim($filter);?>" >
	<option value="0"><?php echo $placeHolderText;?></option>
	<option value="0"  <?php echo (isset($appliedCourseScoreRange[$filter]) && $appliedCourseScoreRange[$filter] == 0) ? "selected" : ""; ?>><?php echo $defaultDropdownValue;?></option>
	<?php
                                                                        $scoreRanges = $MBA_SCORE_RANGE;
                                                                        if($filter == "CMAT"){
                                                                                $scoreRanges = $MBA_SCORE_RANGE_CMAT;
                                                                        } else if($filter == "GMAT"){
                                                                                $scoreRanges = $MBA_SCORE_RANGE_GMAT;
                                                                        } else if($filter == "MAT"){
                                                                                $scoreRanges = $MBA_PERCENTILE_RANGE_MAT;
                                                                        } else if($filter == "XAT"){
                                                                                $scoreRanges = $MBA_PERCENTILE_RANGE_XAT;
                                                                        } else if($filter == "NMAT"){
                                                                                $scoreRanges = $MBA_PERCENTILE_RANGE_NMAT;
                                                                        }
                                                                        foreach($scoreRanges as $key => $value){
                                                                                ?>
                                                                                <option value="<?php echo $value;?>" <?php echo (!empty($appliedCourseScoreRange[$filter]) && $appliedCourseScoreRange[$filter] == $value) ? "selected" : ""; ?>><?php echo $key;?></option>
                                                                                <?php
                                                                        }
                                                                        ?>
	</select>
	</li>
	<?php } ?>
    </ul>
</section>

<a id="examButton" class="refine-btn" style="position:fixed;left:0px;bottom:0px;width:100%" href="javascript:void(0);" onclick="selectCPExam();"><span class="icon-done"><i></i></span> Done</a>
<script>
var examIdsCount = <?=$count?>;
</script>
<script>
var MBA_NO_OPTION_EXAMS = <?=json_encode($MBA_NO_OPTION_EXAMS);?>;
</script>



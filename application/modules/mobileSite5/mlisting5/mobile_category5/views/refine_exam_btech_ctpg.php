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
$examFilterValues = $filters['courseexams'] ? $filters['courseexams']->getFilteredValues() : array();

$count= 0;
if(isset($appliedFilters['exams']) && count($appliedFilters['exams'])>0){
	$count = count($appliedFilters['exams']);
}

if(isset($exam_list['Engineering'])){
	$categoryExams = $exam_list['Engineering'];
}

?>
<header id="page-header" class="clearfix" >
        <div data-role="header" data-position="fixed" class="head-group" data-tap-toggle="false">
	    <a id="examOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   
            <h3>Select Exams given by you</h3>
        </div>
</header>
<section class="layer-wrap">
	<p class="rnr-title-txt">This is an optional field, please select your exam rank to get better result</p>
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

	foreach($examFilterValues as $filter => $filterValues){ 
		$courseexamsArr = $appliedFilters['courseexams'];
		$i++;
                $checked = '';
		$examScoreContDisplayStyle = "display:none;";
		
				if(!in_array($filter, $categoryExams)){
					continue;
				}
                                if($appliedFilters == false){
                                                        //$checked = "checked"; 
                                }
                                elseif(in_array($filter,$appliedFilters['exams'])){
                                      $checked = "checked";
				      $examScoreContDisplayStyle = "display:block;";
                                }

	$labelStyle = "";
                       
	?>
	<li>
	<label><input onClick="setCPExam('examSet<?=$i?>');setRankForExams('<?php echo trim($filter);?>', this);" id="examSet<?=$i?>" type="checkbox" <?=$checked?> name="exams[]" value = "<?=$filter?>"/> <?=$filter?></label>
	<?php 
        $placeHolderText = "Enter Rank";
										if(in_array(trim($filter), $ENGINEERING_EXAMS_REQUIRED_SCORES)){
												$placeHolderText = "Enter Score";
										}
	?>
	<div class="exam-filterText" >
											<input type="text" id="courseexamtextbox_<?php echo trim($filter);?>" style="<?php echo $examScoreContDisplayStyle;?> border:1px solid #ccc; border-bottom:2px solid #ccc;padding:6px 5px; width:100%; -moz-box-sizing:border-box; -webkit-box-sizing:border-box; box-sizing:border-box; color:#999;margin-top:7px;" onblur="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');"  default="<?php echo $placeHolderText;?>" value="<?php echo (!empty($appliedCourseScoreRange[$filter])) ? $appliedCourseScoreRange[$filter] : $placeHolderText; ?>" onkeyup="validateRank($(this),&quot;courseexamtextbox_<?php echo $i;?>_error&quot;)"></input>
											
										</div>
	<div id="courseexamtextbox_<?php echo $i;?>_error" class="examvalidate_error"></div>
	
        </li>
        <?php } ?>
    </ul>
</section>

<a id="examButton" class="refine-btn" style="position:fixed;left:0px;bottom:0px;width:100%" href="javascript:void(0);" onclick="selectCPExam();"><span class="icon-done"><i></i></span> Done</a>
<script>
var examIdsCount = <?=$count?>;
var ENGINEERING_EXAMS_REQUIRED_SCORES = <?=json_encode($ENGINEERING_EXAMS_REQUIRED_SCORES);?>;
</script>




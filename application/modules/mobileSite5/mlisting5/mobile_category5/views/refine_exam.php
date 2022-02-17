<?php
global $filters;
global $appliedFilters;
$filters = $categoryPage->getFilters();
$appliedFilters = $request->getAppliedFilters();
$examFilterValues = $filters['exams'] ? $filters['exams']->getFilteredValues() : array();
$count= 0;
if(isset($appliedFilters['exams']) && count($appliedFilters['exams'])>0){
	$count = count($appliedFilters['exams']);
}

?>

<header id="page-header" class="clearfix">
    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
	<a id="examOverlayClose" data-rel="back" href="javascript:void(0);"><i class="head-icon-b"><span class="icon-arrow-left"></span></i></a>   	
        <h3>Select Exams</h3>
    </div>
</header>

<section class="layer-wrap fixed-wrap" style="height: 100%">
    <ul class="layer-list">
		<?php $i=0;
		foreach($examFilterValues as $filter){
				$i++;
				$checked = '';
				if($appliedFilters == false){
							//$checked = "checked";	
				}
				elseif(in_array($filter,$appliedFilters['exams'])){
							$checked = "checked";
				}
				
				if(($i%2)==1){
					if($i>1) echo "</li>";
					echo "<li>";
				}
				?>
			<label class="exam-label"><input onClick="setCPExam('examSet<?=$i?>');" id="examSet<?=$i?>" type="checkbox" <?=$checked?> name="exams[]" value = "<?=$filter?>"/> <?=$filter?></label>	
		<?php } ?>
		</li>
		<div style='clear: both'>&nbsp;</div>
    </ul>
</section>

<a id="eDButton" class="refine-btn" href="javascript:void(0);" onclick="selectCPExam();"><span class="icon-done"><i></i></span> Done</a>
<script>
var examIdsCount = <?=$count?>;
</script>

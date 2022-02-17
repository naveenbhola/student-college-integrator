<?php
$filteredRoundValues = array();
$filteredRoundSelectedValues = array();
foreach($collegePredictorFilters['round'] as $key=>$value){
	$filteredRoundValues[] = $value->getValue();
	$filteredRoundSelectedValues[$value->getValue()] = $value->isSelected();
}
?>
<header id="page-header" class="clearfix" >
        <div data-role="header" data-position="fixed" class="head-group ui-header-fixed" data-tap-toggle="false">
            <a data-rel="back" id="roundFilterOverlayClose" onclick="" href="javascript:void(0);"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a> 
            <h3>Select Round</h3>
        </div>
</header>

<section class="layer-wrap fixed-wrap">
        <ul class="layer-list rnr-list">
        <?php foreach($defaultCollegePredictorFilters['round'] as $key=>$value){ ?>
        <li>
                <label><input type="checkbox" value="<?php echo $value->getValue();?>" name="roundFilter[]" style="vertical-align:middle;" <?php  if(!in_array($value->getValue(),$filteredRoundValues)){ echo 'disabled';} ?> <?php if(in_array($value->getValue(),$filterInputData['roundFilter'])){ echo 'checked';} ?>/><span  style="vertical-align:middle;<?php  if(!in_array($value->getValue(),$filteredRoundValues)){ echo 'color:#cacaca;';} ?>"> <?php echo $value->getName();?></span></label>
        </li>
        <?php } ?>
    </ul>
</section>
<a onclick="var filterStr = getFilterString();closeLayer('round');showSelected('round');callAllFilters(filterStr);trackEventByGAMobile('HTML5_JEECollegePredictor_Filter_Round');" href="javascript:void(0);" style="position:fixed;left:0px;bottom:0px;width:100%" class="refine-btn" id="examButton"><span class="icon-done"><i></i></span> Done</a>



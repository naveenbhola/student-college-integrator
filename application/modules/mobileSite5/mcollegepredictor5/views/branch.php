<?php
$filteredBranchValues = array();
$filteredBranchSelectedValues = array();
foreach($collegePredictorFilters['branch'] as $key=>$value){
        $filteredBranchValues[] = $value->getValue();
        $filteredBranchSelectedValues[$value->getValue()] = $value->isSelected();
}
?>
<header id="page-header" class="clearfix" >
        <div data-role="header" data-position="fixed" class="head-group ui-header-fixed" data-tap-toggle="false">
            <a data-rel="back" id="branchFilterOverlayClose" onclick="" href="javascript:void(0);"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a> 
            <h3>Select Branch</h3>
        </div>
</header>

<section class="layer-wrap fixed-wrap">
        <ul class="layer-list rnr-list">
        <?php
        $count = count($defaultCollegePredictorFilters['branch']);
        $i=0;
        foreach($defaultCollegePredictorFilters['branch'] as $key=>$value){ ?>
        <li <?php if($i==$count-1){?> style="padding-bottom: 60px;"<?php }?>>
                <label><input type="checkbox" value="<?php echo $value->getValue();?>" name="branchFilter[]" style="vertical-align:middle; float:left; width:18px" <?php  if(!in_array($value->getValue(),$filteredBranchValues)){ echo 'disabled';} ?> <?php if(in_array($value->getValue(),$filterInputData['branchFilter'])){ echo 'checked';} ?>/><span  style="vertical-align:middle;margin-left:20px; display:block;<?php  if(!in_array($value->getValue(),$filteredBranchValues)){ echo 'color:#cacaca;';} ?>"> <?php echo $value->getName();?></span></label>
        </li>
        <?php $i++;} ?>
    </ul>
</section>
<a onclick="var filterStr = getFilterString();closeLayer('branch');showSelected('branch');callAllFilters(filterStr);trackEventByGAMobile('HTML5_JEECollegePredictor_Filter_Branch');" href="javascript:void(0);" style="position:fixed;left:0px;bottom:0px;width:100%" class="refine-btn" id="examButton"><span class="icon-done"><i></i></span> Done</a>
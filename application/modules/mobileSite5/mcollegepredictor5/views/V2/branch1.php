<?php

$filteredBranchValues = array();
foreach($collegePredictorFilters['branch'] as $key=>$value){
        $filteredBranchValues[] = $value->getValue();
}
?>
<ul>    
    <?php
        foreach($defaultCollegePredictorFilters['branch'] as $key=>$value){ ?>
        <li >
        <div class="Customcheckbox">
            <input id="<?php echo $value->getValue();?>" type="checkbox" value="<?php echo $value->getValue();?>" name="branchFilter[]" <?php if(in_array($value->getValue(),$filters['branchFilter'])){ echo 'checked';} ?>/>
            <label for="<?php echo $value->getValue();?>"><span> <?php echo $value->getName();?></span></label>
        </div>
        </li>
    <?php } ?>
</ul>
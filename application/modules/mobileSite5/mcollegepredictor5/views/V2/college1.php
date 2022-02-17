<?php
$filteredCollegeValues = array();
foreach($collegePredictorFilters['college'] as $key=>$value){
        $filteredCollegeValues[] = $value->getValue();
        
}
?>
<ul>
    <?php  
        foreach($defaultCollegePredictorFilters['college'] as $key=>$value){ ?>
            <li >
            <div class="Customcheckbox">
                <input id="collegeNameLI<?php echo $value->getValue();?>" <?php if(in_array($value->getValue(),$filters['collegeFilter'])){ echo 'checked';} ?> type="checkbox" value="<?php echo $value->getValue();?>" name="collegeFilter[]" />

                <label for= "collegeNameLI<?php echo $value->getValue();?>"><span id="collegeName-<?php echo $value->getValue();?>"> <?php echo $value->getName();?></span></label>
            </div>
            </li>
    <?php } ?>
 </ul>  
<?php
$filteredLocationValues = array();
foreach($collegePredictorFilters['location'] as $key=>$value){
        $filteredLocationValues[] = $value->getValue();
}
?>
<ul>   
    <?php
    if ($filters['rankType'] == 'Home' || $filters['rankType'] == 'StateLevel' || $filters['rankType'] == 'HomeUniversity' || $filters['rankType'] == 'HyderabadKarnatakaQuota' || strtolower($filters['examName']) == 'mhcet' || strtolower($filters['examName']) == 'ptu' || strtolower($filters['examName']) == 'mppet' || strtolower($filters['examName']) == 'upsee'  || strtolower($filters['examName']) == 'wbjee'|| strtolower($filters['examName']) == 'kcet') {
        $locationType = 'city';
    } else {
        $locationType = 'state';
    }
    foreach($defaultCollegePredictorFilters['location'] as $key=>$value){ ?>
        <li>
        <div class="Customcheckbox">
        <input id="locationNameLI<?php echo $value->getValue();?>"' <?php if(in_array($value->getValue(),$filters['locationFilter'][$locationType])){ echo 'checked';} ?> type="checkbox" value="<?php echo $value->getValue();?>" name="locationFilter[]" />

            <label for="locationNameLI<?php echo $value->getValue();?>"><span id="locationName-<?php echo $value->getValue();?>"> <?php echo $value->getName();?></span></label>
        </div>
        </li>
    <?php } ?> 
</ul>   
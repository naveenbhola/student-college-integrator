<?php
if($keyName == 'companies')
{
    $placementData    = json_decode($naukriData['placement_data_all_spec'],true);
    $maximumEmployees = $placementData[0]['no_of_emps'];
    $className = 'bg-f7';
}
else{
    $industryData = json_decode($naukriData['industry_all'],true);
    $maximumEmployees = $industryData[0]['no_of_emps'];    
    $className = 'bg-5f';
} 
foreach($naukriData['naukriCompaniesAndFuncWRTSpec'] as $specVal => $mainData){ ?>
<input type="radio" name="<?=$keyName;?>" value="<?=$keyName.'_spec_'.$specMappingAMP[trim($specVal)];?>" id="<?=$keyName.'_spec_'.$specMappingAMP[trim($specVal)];?>" class="hide st">
<div class="al-ul">
    <p class="color-3f14 f12 font-w6 n-border-color"><span class="i-block color-6 font-w4">Showing info for</span> " <?php
    if($specVal == 'Marketing'){
        echo 'Sales & Marketing';
    }else if($specVal == 'HR/Industrial Relations'){
        echo 'Human Resources';
    }else if($specVal == 'Information Technology'){
        echo 'IT';
    }else{
        echo trim($specVal);
    } ?> Category "
    </p>
    <?php if($keyName == 'companies'){ ?>
    <p class ="f14 color-6 margin-20 font-w6">Companies they work for</p>
<?php }else{ ?>
    <p class ="f14 color-6 margin-20 font-w6">Business functions they are in</p>
<?php } ?>
        <ul class="al-ul m-top">
        <?php foreach ($mainData[$keyName] as $index => $empCount) {
            $width = round($empCount*100/$maximumEmployees);
        ?>
        <li>
        <label class ="block f14 color-3 m-3btm"><strong class="f14 color-3 font-w6"><?php echo $empCount;?></strong> <span class="f12 pad3"> Employees</span> | <?php echo $index?></label>
        <div class="ps-bar bg-clr-e border-e4 block pos-rl"><p class="status-bar pos-abs <?=$className;?>  cl<?php echo $width; ?>" ></p></div>
        </li>   
<?php } ?>
    </ul>
</div>
<?php }  ?>
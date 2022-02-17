<?php 
$placementData    = json_decode($naukriData['placement_data_all_spec'],true);
$maximumEmployees = $placementData[0]['no_of_emps'];
$industryData = json_decode($naukriData['industry_all'],true);
$maximumEmployeesIndustry = $industryData[0]['no_of_emps'];
foreach($naukriData['naukriCompaniesAndFuncWRTSpec'] as $specVal => $mainData){ ?>
<input type="radio" name="placement" value="spec_<?=$specMappingAMP[trim($specVal)];?>" id="spec_<?=$specMappingAMP[trim($specVal)];?>" class="hide st">
<div class="table tob1">
    <p class="color-3f14 f12 font-w6 n-border-color padtb0">
        <span class="i-block color-6 font-w4">Showing info for</span> "
    <?php 
    if($specVal == 'Marketing'){
        echo 'Sales & Marketing';
    }else if($specVal == 'HR/Industrial Relations'){
        echo 'Human Resources';
    }else if($specVal == 'Information Technology'){
        echo 'IT';
    }else{
        echo trim($specVal);
    } ?> Category "</p>
<?php if(!empty($mainData['companies'])){ 
    $i = 0;?>
    <div class="col-prime pad10">
        <p class ="f14 color-6 m-btm font-w6">Companies they work for</p>
        <ul class="al-ul">
    <?php foreach ($mainData['companies'] as $compName => $empCount) {
            $width = round($empCount*100/$maximumEmployees);
            $i++; ?>
    
        <?php if ($i < 4){ ?>
                <li>
                    <label class ="block f14 color-3 m-3btm"><strong class="f14 color-3 font-w6"><?php echo $empCount;?></strong> <span class="f12 pad3"> Employees</span> | <?php echo $compName?></label>
                    <div class="ps-bar bg-clr-e border-e4 block pos-rl"><p class="status-bar pos-abs bg-f7 cl<?php echo $width;?> "></p></div>
                </li>
                <?php } else {?>
                <label for="companies_spec_<?php echo $specMappingAMP[trim($specVal)];?>" class="block" >
                <a class="read-more-trigger block m-top color-b t-cntr f14 font-w6 v-arr" on="tap:companies-view-more-list" role="button" tabindex="0">View more</a></label>
                <?php break; } ?>

            <?php }  ?>
        </ul>
    </div>
<?php }?>
<?php if(!empty($mainData['functions'])){ 
        $i = 0; ?>
        <div class="col-prime pad10">
        <p class="f14 color-6 m-btm font-w6 border-top">Business functions they are in</p>
        <ul class="al-ul">
        
        <?php foreach ($mainData['functions'] as $specName => $empCount) { 
            $width = round($empCount*100/$maximumEmployeesIndustry);    
            $i++;
        ?>
            <?php if ($i < 4){ ?>
                    <li>
                        <label class="block f14 color-3 m-3btm"><strong class="f14 color-3 font-w6"><?php echo $empCount;?></strong> <span class="f12 pad3"> Employees</span> | <?php echo $specName?></label>
                        <div class="ps-bar bg-clr-e border-e4 block pos-rl"><p class="status-bar pos-abs bg-5f cl<?php echo $width; ?>" ></p></div>
                    </li>
                <?php } else {?>
                        <label for="functions_spec_<?php echo $specMappingAMP[trim($specVal)];?>" class="block" >
                        <a class="read-more-trigger block m-top color-b t-cntr f14 font-w6 v-arr" on="tap:functions-view-more-list" role="button" tabindex="0">View more</a></label>
                        <?php break; } ?>
            <?php }  ?>
            </ul>
        </div>
<?php } ?>
</div>
<?php } ?>  
<?php $this->load->view('mobile_listing5/course/AMP/Widgets/chartsViewMore',array('keyName'=>'companies'));
$this->load->view('mobile_listing5/course/AMP/Widgets/chartsViewMore',array('keyName'=>'functions'));
?>
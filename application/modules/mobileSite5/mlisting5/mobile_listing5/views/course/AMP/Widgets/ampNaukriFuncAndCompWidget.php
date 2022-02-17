<?php 
$checked = 'checked=true';
?>
<input type="radio" name="placement" value="spec_all" id="spec_all" class="hide st" <?=$checked;?>>
    <div class="table tob1">
<?php if($naukriData['placement_data_count']>0){ ?>
    <div class="col-prime pad10">
        <p class ="f14 color-6 m-btm font-w6">Companies they work for</p>
        <ul class="al-ul">
        <?php 
        $placementData    = json_decode($naukriData['placement_data_all_spec'],true);
          $maximumEmployees = $placementData[0]['no_of_emps'];
            for($i=0;$i<3;$i++){
                if(!empty($placementData[$i])){
                    $width = round($placementData[$i]['no_of_emps']*100/$maximumEmployees);
            ?>
                <li>
                    <label class ="block f14 color-3 m-3btm"><strong class="f14 color-3 font-w6"><?php echo $placementData[$i]['no_of_emps'];?></strong> <span class="f12 pad3"> Employees</span> | <?php echo $placementData[$i]['comp_name']?></label>
                    <div class="ps-bar bg-clr-e border-e4 block pos-rl"><p class="status-bar pos-abs bg-f7 cl<?php echo $width;?>"></p></div>
                </li>
                <?php
                }
                else{
                    break;
                }
            }

        ?>
        </ul>
        <?php if(count($placementData)>3){?>
        <label for="companies_spec_all" class="block" >
        <a class="read-more-trigger block m-top color-b t-cntr f14 font-w6 v-arr" on="tap:companies-view-more-list" role="button" tabindex="0">View more</a>
        <?php }?>
    </div>
<?php }?>
<?php if($naukriData['industry_count']>0){ ?>
    <div class="col-prime pad10 border-top">
        <p class="f14 color-6 m-btm font-w6">Business functions they are in</p>
        <ul class="al-ul">
        <?php $industryData             = json_decode($naukriData['industry_all'],true);
              $maximumEmployeesIndustry = $industryData[0]['no_of_emps'];
            for($i=0;$i<3;$i++){
                if(!empty($industryData[$i])){
              $width = round($industryData[$i]['no_of_emps']*100/$maximumEmployeesIndustry);

            ?>
                <li>
                    <label class="block f14 color-3 m-3btm"><strong class="f14 color-3 font-w6"><?php echo $industryData[$i]['no_of_emps'];?></strong> <span class="f12 pad3"> Employees</span> | <?php echo $industryData[$i]['industry']?></label>
                    <div class="ps-bar bg-clr-e border-e4 block pos-rl"><p class="status-bar pos-abs bg-5f cl<?php echo $width;?>"></p></div>
                </li>
                <?php
                }
                else{
                    break;
                }
            }

        ?>
        </ul>
        <?php if(count($industryData)>3){?>
            <label for="functions_spec_all" class="block" >
            <a class="read-more-trigger block m-top color-b t-cntr f14 font-w6 v-arr" on="tap:functions-view-more-list" role="button" tabindex="0">View more</a>
        <?php }?>
    </div>
    <?php } ?>
</div>
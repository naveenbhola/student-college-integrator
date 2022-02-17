<amp-lightbox id="<?=$keyName;?>-view-more-list" class="" layout="nodisplay" scrollable>
<div class="lightbox">
<div class="color-w full-layer">
    <div class="pos-fix f14 color-3 bck1 pad10 font-w6">
                  Employment details of <?php echo $naukriData['total_naukri_employees'] ?> alumni <a class="cls-lightbox color-3 font-w6 t-cntr" on="tap:<?=$keyName;?>-view-more-list.close" role="button" tabindex="0">&times;</a>
    </div>
    <div class="col-prime pad10 margin-50">
        <div class="f12 color-0 pos-rl m-btm">View By Specialization
                  <div class="dropdown-primary pos-abs color-w border-all tab-cell pos-abs" on="tap:<?=$keyName?>-spec-list-amp" role="button" tabindex="0">
                    <span class="option-slctd block color-6 f12 font-w6" id="optnSlctd">All Specializations</span>
                </div>
        </div>

<?php if($naukriData['placement_data_count']>0 && $keyName == 'companies'){ ?>
        <input type="radio" name="<?=$keyName;?>" value="<?=$keyName?>_spec_all" id="<?=$keyName?>_spec_all" class="hide st" checked=true>
    <div class="al-ul">
        <p>All Specializations</p>
        <p class ="f14 color-6 margin-20 font-w6">Companies they work for</p>
        <ul class="al-ul m-top">
        <?php 
            $placementData    = json_decode($naukriData['placement_data_all_spec'],true);
            $maximumEmployees = $placementData[0]['no_of_emps'];
            foreach ($placementData as $placementKey => $placementValue) {
                $width = round($placementValue['no_of_emps']*100/$maximumEmployees);
            ?>
                <li>
                    <label class ="block f14 color-3 m-3btm"><strong class="f14 color-3 font-w6"><?php echo $placementValue['no_of_emps'];?></strong> <span class="f12 pad3"> Employees</span> | <?php echo $placementValue['comp_name']?></label>
                    <div class="ps-bar bg-clr-e border-e4 block pos-rl"><p class="status-bar pos-abs bg-f7 cl<?php echo $width; ?>" ></p></div>
                </li>
                <?php
            }
        ?>
        </ul>
    </div>
<?php
    $this->load->view('mobile_listing5/course/AMP/Widgets/chartsViewMoreWRTspec',array('keyName'=>$keyName)); 
?>
<?php }?>
<?php if($naukriData['industry_count']>0 && $keyName == 'functions'){ ?>

        <input type="radio" name="<?=$keyName;?>" value="<?=$keyName?>_spec_all" id="<?=$keyName?>_spec_all" class="hide st" checked=true>
        <div class="al-ul">
        <p>All Specializations</p>
        <p class="f14 color-6 margin-20 font-w6">Business functions they are in</p>
        <ul class="al-ul m-top">
        <?php $industryData             = json_decode($naukriData['industry_all'],true);
              $maximumEmployeesIndustry = $industryData[0]['no_of_emps'];
              foreach ($industryData as $industryKey => $industryValue) {
                    $width = round($industryValue['no_of_emps']*100/$maximumEmployeesIndustry);
                ?>
                       <li>
                    <label class="block f14 color-3 m-3btm"><strong class="f14 color-3 font-w6"><?php echo $industryValue['no_of_emps'];?></strong> <span class="f12 pad3"> Employees</span> | <?php echo $industryValue['industry']?></label>
                    <div class="ps-bar bg-clr-e border-e4 block pos-rl"><p class="status-bar pos-abs bg-5f cl<?php echo $width; ?>" ></p></div>
                </li>
           <?php   }
           ?>
       </ul>
    </div>
    <?php
    $this->load->view('mobile_listing5/course/AMP/Widgets/chartsViewMoreWRTspec',array('keyName'=>$keyName)); 
?>
    <?php } ?>
    </div>
    </div></div>
</amp-lightbox>
<amp-lightbox id="<?php echo $keyName;?>-spec-list-amp" class="" layout="nodisplay" scrollable> 
   <div class="lightbox" on="tap:<?php echo $keyName;?>-spec-list-amp.close" role="button" tabindex="0">
     <a class="cls-lightbox  color-f font-w6 t-cntr">&times;</a>
       <div class="m-layer">
         <div class="min-div color-w catg-lt">
           <ul class="color-6">
                <li><label for="<?=$keyName?>_spec_all" class="block">All Specializations</label></li>
                    <?php 

                    foreach($naukriData['naukri_specializations'] as $splz){
                        $splz = trim($splz);
                        if($splz == "Systems" || $splz == "Other Management" || $splz == "#N/A") { continue; }
                        elseif($splz == "Marketing") {?>
                           <li><label for="<?php echo $keyName;?>_spec_<?php echo $specMappingAMP[$splz];?>" class="block" >Sales & Marketing</label></li>
                        <?php }
                            elseif($splz == "HR/Industrial Relations"){?>
                                <li><label for="<?php echo $keyName;?>_spec_<?php echo $specMappingAMP[$splz];?>" class="block" >Human Resources</label></li>
                        <?php }
                            elseif($splz == "Information Technology"){?>
                               <li><label for="<?php echo $keyName;?>_spec_<?php echo $specMappingAMP[$splz];?>" class="block" >IT</label></li>
                        <?php }
                            else{?>
                                <li><label for="<?php echo $keyName;?>_spec_<?php echo $specMappingAMP[$splz];?>" class="block" ><?php echo $splz; ?></label></li>
                        <?php }
                    } 
                        if(in_array("Other Management", $naukriData['naukri_specializations'])) { ?>
                               <li><label for="<?php echo $keyName;?>_spec_other" class="block" >Other Management</label></li>

                        <?php } ?>
                             
           </ul>
         </div>
       </div>
   </div>
</amp-lightbox>
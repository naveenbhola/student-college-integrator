<!--infrastucture-->
<?php 
   $GA_Tap_On_View_Details = 'VIEW_DETAILS_FACILITY';
?>
      <div class="new-row">
       <div class="group-card no__pad gap listingTuple" id="amenities">
         <h2 class="head-1 gap">Infrastructure / Facilities</h2>
         <div class="gap">
           <ul class="infra flex-ul">
                    <?php global $FACILITY_ID_CSS_ICON_NAME_MAPPING;
                    foreach($facilities['facilities'] as $facilityKey => $facilityValue) { if($facilityKey != 'others') {?>
                      <li class=""> <a class="<?php echo $FACILITY_ID_CSS_ICON_NAME_MAPPING[$facilityKey];?>"> 
                       </a>
                       <p><?php echo $facilityValue?><?php if(in_array($facilityValue, json_decode($facilities['viewFacilities']))) {?></p>
                       <span class="link" id="show-popup" ga-attr="<?=$GA_Tap_On_View_Details?>" onclick="openViewDetailsLayer(<?php echo $listing_id;?>,'<?php echo $facilityValue;?>',<?php echo $facilityKey;?>)">View Details</span> <?php }?>
                       </li>

                      <?php }} ?>
            </ul>
         </div>
         
         <?php 
            $otherFacilityInfo = ''; 
            foreach ($facilities['facilities']['others'] as $otherKey => $otherValue) { 
             if(!empty($otherFacilityInfo))
             {
                $otherFacilityInfo .= ' | ';
             }
             $otherFacilityInfo .= $otherValue;
         }
         if(!empty($otherFacilityInfo)) {
         ?>
         <p class="head-s-12 other-f">Other facilities : <span class="para-2"><?php echo $otherFacilityInfo;?></span></p>
         <?php } ?>
       </div>
     </div>

<script>
var viewFacilitiesArray = <?php echo $facilities['viewFacilities']?>;
</script>

   

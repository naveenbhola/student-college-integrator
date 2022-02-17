<?php 
    global $formFlow;
    if ($onBehalfOf == "true") {?>
<input type="hidden" value="<?php echo $onBehalfOf; ?>" name="onBehalfOf">
<input type="hidden" value="<?php echo $userid;?>" name="clientId">
<?php } ?>

<script>
   var productsDataList = <?php echo json_encode($userProducts); ?>;
   var productInfo = <?php echo $productInfo; ?>;
</script>

<div class="row">
   <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Select Pack to Consume</span></div>
   <div class="grayLine mar_top_5p"></div>
</div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
       <div>
           <div class="r1 bld">Available Pack(s):</div>
           <div class="r2">
<?php if ($formFlow != "edit") {?>
               <select onchange="packSpecificChanges('<?php echo $listingType; ?>');" id="userPack" name="userPack" validate="validateSelect" minlength="1" maxlength="100" caption="Pack" required="true" >
                   <?php }else{ 
                   ?>
                   <select onchange="packSpecificChanges('<?php echo $listingType; ?>');" id="userPack" name="userPack" validate="validateSelect" minlength="1" maxlength="100" caption="Pack" >
                   <?php } ?>
                   <option value="" selected>Select Pack</option>
                   <?php
                       foreach($userProducts as $key=>$vals) {
                   ?>
                   <?php if($vals['BaseProdCategory']=='Listing'){ ?>
                   <option value="<?php echo $key; ?>" <?php //echo $selected; ?>><?php echo $vals['BaseProdCategory'],"-",$vals['BaseProdSubCategory']; ?></option>
                   <?php } ?>
                   <?php } ?>
               </select>
           </div>
           <div class="clear_L"></div>
           <div class="row errorPlace">
               <div class="r1">&nbsp;</div>
               <div class="r2 errorMsg" id="userPack_error" ></div>
               <div class="clear_L"></div>
           </div>
           <div class="lineSpace_10">&nbsp;</div>
           <div id="pack_info_title" style="display:none;" class="r1 bld">Pack Details:</div>
           <div class="r2">
               <div id="selected_pack_info" style="display:none;">
                   <ul>
                       <li><b> Remaining Listings:</b> <div id="remainingList"></div> </li>
                   </ul>
               </div>
           </div>
           <div class="clear_L"></div>
       </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

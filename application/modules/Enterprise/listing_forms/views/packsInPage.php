<input type="hidden" id="clientUserId" value="<?php echo $clientId; ?>"/>

<script>
    var subscriptionsList = eval(<?php echo json_encode($subscriptionDetails); ?>);
    //var productInfo = <?php echo json_encode($productInfo); ?>;
</script>
<input type="hidden" id="required_packtype" value="" name="required_packtype"/>
<?php
 foreach($subscriptionDetails as $key=>$vals){
    // Added by Amit K on 25 June 2012 as we will only show the Bronze subscription for Institutes..
    if($wikiData[0]['listing_type'] == 'institute' && $vals['BaseProductId'] != BRONZE_LISTINGS_BASE_PRODUCT_ID) {
        continue;
    }
   
    $finalSubscriptionDetails[$key] = $vals;
}

if($wikiData[0]['listing_type'] == 'institute') {
    $onChangeJSCall = 'massMailSubsChange();';
} else {
    $onChangeJSCall = 'controllOnchangeEvents();';
}
?>
<div class="contentBT">
    <div style="margin-bottom:10px">
        <div class="float_L" style="width:28%">
            <div class="OrgangeFont bld txt_align_r fontSize_13p" style="line-height:18px">Subscription being consumed:&nbsp;</div>
        </div>
        <div class="float_L" style="width:65%;line-height:18px">
            <select onchange="<?=$onChangeJSCall;?>" id="selectedSubscription" name='selectedSubs' validate="validateSelect" minlength="1" maxlength="100" caption="Pack" >
                <option value="" selected>Select Subscription</option>
                <?php
                    $goldMLFlag = false;
                    $goldFlag = false;                    
                    $silverFlag = false;                    
                    $bronzeFlag = false;
                    foreach($finalSubscriptionDetails as $key=>$vals){
                        if( ($vals['BaseProdCategory']=='Listing')){
                            
                            if(strtolower($vals['BaseProdSubCategory']) == 'gold ml'){
                                $goldMLFlag = true;
                            }

                            if($vals['BaseProdSubCategory']=='Gold' || strtolower($vals['BaseProdSubCategory']) == 'gold sl') {
                                $goldFlag = true;
                            }
                            if($vals['BaseProdSubCategory']=='Silver'){
                                $silverFlag = true;
                            }
                            if($vals['BaseProdSubCategory']=='Bronze'){
                                $bronzeFlag = true;
                            }
                        }
                    }

                    if($goldMLFlag){
                        $i = 1;
                        foreach($finalSubscriptionDetails as $key=>$vals){
                            // if($vals['BaseProdSubCategory']=='Gold'){
                            if(strtolower($vals['BaseProdSubCategory']) == 'gold ml'){
                            ?>
                            <option value="<?php echo $key; ?>" <?php if($i==1){echo 'selected="selected"';} ?> ><?php echo $vals['BaseProdCategory'],"-",$vals['BaseProdSubCategory']," : ",$key;if($i==1){echo " (Recommended Gold ML)";} ?></option>
                            <?php $i++;
                            }else{
                                continue;
                            }
                        }
                    }

                    if($goldFlag){
                        $i = 1;
                        foreach($finalSubscriptionDetails as $key=>$vals){
                            // if($vals['BaseProdSubCategory']=='Gold'){
                            if($vals['BaseProdSubCategory']=='Gold' || strtolower($vals['BaseProdSubCategory']) == 'gold sl'){
                            ?>
                            <option value="<?php echo $key; ?>" <?php if($goldMLFlag==false && $i==1){echo 'selected="selected"';} ?> ><?php echo $vals['BaseProdCategory'],"-",$vals['BaseProdSubCategory']," : ",$key;if($i==1){echo " (Recommended Gold)";} ?></option>
                            <?php $i++; 
                            }else{
                                continue;
                            }
                        }
                    }

                    if($silverFlag){
                        $i = 1;
                        foreach($finalSubscriptionDetails as $key=>$vals){
                            if($vals['BaseProdSubCategory']=='Silver'){
                            ?>
                            <option value="<?php echo $key; ?>" <?php if($goldFlag==false && $i==1){echo 'selected="selected"';} ?> ><?php echo $vals['BaseProdCategory'],"-",$vals['BaseProdSubCategory']," : ",$key;if($i==1){echo " (Recommended Silver)";} ?></option>
                            <?php $i++; 
                            }else{
                                continue;
                            }
                        }
                    }

                    if($bronzeFlag){
                        foreach($finalSubscriptionDetails as $key=>$vals){
                            if($vals['BaseProdSubCategory']=='Bronze'){
                            ?>
                            <option value="<?php echo $key; ?>" <?php if($goldFlag==false && $silverFlag==false){echo 'selected="selected"';} ?> ><?php echo $vals['BaseProdCategory'],"-",$vals['BaseProdSubCategory']," : ",$key; ?></option>
                            <?php $i++; 
                            }else{
                                continue;
                            }
                        }
                    }
                
                    ?>
        </select>
                <?php
                    if($wikiData[0]['listing_type'] != 'institute' && $goldFlag==false && $silverFlag==false && $paidStatus=='true'){
                    ?>
                    <br/><br/>You have consumed all your <b>Paid Listings</b>. <a href="/enterprise/Enterprise/prodAndServ">Buy more</a>
                    <?php
                    }
                    
                    if($bronzeFlag==false && $paidStatus=='false'){
                    ?>
                    <br/><br/>You have consumed all your <b>Free (Bronze) Listings</b>. <a href="/enterprise/Enterprise/prodAndServ">Buy paid listings</a>
                    <?php
                    }
                ?>
            </div>
            <div class="row errorPlace">
                <div class="r1">&nbsp;</div>
                <div class="r2 errorMsg" id="selectedSubscription_error" ></div>
                <div class="clear_L"></div>
            </div>
        <div class="float_L" style="width:30%;line-height:18px">&nbsp;</div>
        <div style="line-height:1px;font-size:1px;clear:left">&nbsp;</div>
    </div>
    <div id="selected_pack_info" style="display:none;">
        <div class="float_L" style="width:28%">
            <div class="OrgangeFont bld txt_align_r fontSize_13p" style="line-height:18px">Subscription Details:&nbsp;</div>
        </div>
        <div class="float_L bld" style="width:33%;line-height:18px">Start Date<br />Expiry Date<br />No. of Listings remaining<br />Subscription Id</div>
        <div class="float_L bld" style="width:30%;line-height:18px">:<p id="SubsStartDate" name="SubsStartDate"></p><br />:<p id="SubsEndDate" name="SubsEndDate"></p><br />:<p id="RemainingQty" name="RemainingQty"></p><br/>:<p id="SubscriptionId" name="SubscriptionId"></p></div>
        <div style="line-height:1px;font-size:1px;clear:left">&nbsp;</div>
    </div>
</div>
<div id="subs_unselect_error" style="display:none;color:red;"></div><br/>
<script>
var coursepackType = '<?php echo $coursepackType;?>';
var count_couerse_location = '<?php echo $count_course_locations;?>';
function massMailSubsChange()
{    
    subscriptionSelection();
    fetchLatestSubscriptionInfo();
    var subscriptInfo = subscriptionsList;
    var subsId = document.getElementById('selectedSubscription').value;
    if(subsId != ''){
        populateFeaturesForSubscription(subscriptInfo,subsId);
        $('required_packtype').value = subscriptionsList[subsId].BaseProductId;
    }
}

function populateFeaturesForSubscription(subscriptInfo,subsId)
{
    if(subsId !=''){
        document.getElementById('selected_pack_info').style.display = 'inline';

        document.getElementById('SubsStartDate').style.display = 'inline';
        document.getElementById('SubsStartDate').innerHTML = subscriptInfo[subsId].SubscriptionStartDate;
        document.getElementById('SubsEndDate').style.display = 'inline';
        document.getElementById('SubsEndDate').innerHTML = subscriptInfo[subsId].SubscriptionEndDate;
        document.getElementById('RemainingQty').style.display = 'inline';
        document.getElementById('RemainingQty').innerHTML = subscriptInfo[subsId].BaseProdPseudoRemainingQuantity;
        document.getElementById('SubscriptionId').style.display = 'inline';
        document.getElementById('SubscriptionId').innerHTML = subsId;
    }else{
        document.getElementById('selected_pack_info').style.display = 'none';
    }
}

function fetchLatestSubscriptionInfo()
{
    var url = '/enterprise/Enterprise/fetchLatestPseudoSubscriptionInfo';
    var data = 'userid='+document.getElementById('clientUserId').value;
    new Ajax.Request (url,{ method:'post', parameters: (data), onSuccess:function (response) {
                    try{
                      if(response.responseText.length > 0){
                         subscriptionsList = eval("eval("+response.responseText+")");
                         var subsId = document.getElementById('selectedSubscription').value;
                         populateFeaturesForSubscription(subscriptionsList,subsId);
                      }
                     } catch (e) {} 
            }});
}

function subscriptionSelection(){
    subsSelVal = document.getElementById('selectedSubscription').value;
    if(subsSelVal != ''){
        document.getElementById('subs_unselect_error').innerHTML = "";
        document.getElementById('subs_unselect_error').style.display = 'none';
        return true;
    }else{
        document.getElementById('subs_unselect_error').innerHTML = "Please select a Subscription to continue !!";
        document.getElementById('subs_unselect_error').style.display = 'inline';
        return false;
    }
}
massMailSubsChange();
function controllOnchangeEvents() {
  if($('selectedSubscription').value == "")  {
        document.getElementById('subs_unselect_error').innerHTML = "Please select a Subscription to continue !!";
        document.getElementById('subs_unselect_error').style.display = 'inline';
        document.getElementById('selected_pack_info').style.display = 'none';

        return false;
  }
 <?php if($flow == 'upgrade') { ?>
    	if(!checkUpgradeDownGrade()) {
	 return false;
	}
    <?php } ?>
validateSubscriptionForMultiLocations(1);
updateAvailableLocations(1);
massMailSubsChange();
}
</script>

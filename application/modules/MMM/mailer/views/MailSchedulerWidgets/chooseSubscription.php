  <?php if($clientDetails['clientId'] != $mailerAdminUserId) { ?>
  <div class="drip--form_data">
    <label for="">Choose Subscription<span class="redText">*</span>:</label>
    <div class="drip--form-fileds">
      <select class="width-200 drip--fileds" id="subscriptionId_<?php echo $mailCriteria?>_<?php echo $widgetNumber;?>" name="subscriptionId_<?php echo $mailCriteria?>_<?php echo $widgetNumber;?>">
        <option>Select</option>
        <?php 
        foreach($clientDetails['subscriptionDetails'] as $subDetails){ 
            $subEndDate = strtotime($subDetails['SubscriptionEndDate']);
            ?>
            <option value="<?php echo $subDetails['SubscriptionId'];?>" sub_end_date="<?php echo $subDetails['SubscriptionEndDate'];?>" sub_enddate_timestamp="<?php echo $subEndDate;?>"><?php echo "SubscriptionId: ",$subDetails['SubscriptionId'].", Remaining Credits:".$subDetails['BaseProdRemainingQuantity']; ?></option>
        <?php
        } ?>
      </select>
       <i class="pwaicono-exclamationCircle default-exclCircle">
    <span class="help-text-popup">Set Subscription for mail scheduling</span>
  </i>
    </div>
     <div id="subscriptionId_error_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" class="drip--form-fileds campaign_errors"></div>
  </div>
  <?php } ?>

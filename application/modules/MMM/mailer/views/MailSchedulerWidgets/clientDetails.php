<div class="drip_table">
  <div class="drip_table_row">
   <div class="drip_table_cell">Client Id:</div>
   <div class="drip_table_cell"><strong><?php echo $clientDetails['clientId']; ?></strong></div>
 </div>
 <div class="drip_table_row">
   <div class="drip_table_cell">Email Id:</div>
   <div class="drip_table_cell"><strong><?php echo $clientDetails['emailId']; ?></strong></div>
 </div>
 <div class="drip_table_row">
   <div class="drip_table_cell">First Name:</div>
   <div class="drip_table_cell"><strong><?php echo $clientDetails['firstName']; ?></strong></div>
 </div>
 <div class="drip_table_row">
   <div class="drip_table_cell">Last Name:</div>
   <div class="drip_table_cell"><strong><?php echo $clientDetails['lastName']; ?></strong></div>
 </div>
 <div class="drip_table_row">
   <div class="drip_table_cell">Campaign Name:<span class="redText">*</span></div>
   <div class="drip_table_cell">
     <input type="text" name="campaignName" id='campaignName' maxlength="80" class="drip--fileds">
   </div>
 </div>
 <div id="campaign_name_error" class="drip--form-fileds campaign_errors"></div>
</div>

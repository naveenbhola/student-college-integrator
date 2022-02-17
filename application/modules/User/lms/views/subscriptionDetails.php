<select name='selected_subscription' id="selected_subscription" onchange="select_porting_data_type();" style="width:220px">
	<option value="" selected>Select Subscription</option>
	<?php
		foreach($subscriptionDetails as $key=>$vals){
	?>
		<option value="<?php echo $key; ?>" <?php if ($activeSubscriptionId == $key) { echo "selected";}?> ><?php echo $vals['BaseProdSubCategory']." : ".$key; ?></option>
	<?php
		}
	?>
</select>

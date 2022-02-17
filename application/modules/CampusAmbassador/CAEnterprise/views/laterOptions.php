<div id = "pop-up-layer" style = "display:block;">
	<div class="pop-msg" style="postion:fixed !important">
	<ul class="pop-msg-list">
	<li class="pop-msg-hd"><strong>Reason For Marking Later :</strong></li>
	<li>
	<?php foreach ($reasons as $key => $value) { ?>
		<input type="radio" value="<?php echo $value['id']; ?>" name="reason_later" id="reason_later" <?php echo ($value['id'] == $reasonId) ? 'checked="checked"' : ''; ?> ><label><?php echo $value['reason']; ?></label><br>	
	<?php } ?>
	<li style="margin-top:10px;">
	<input type="button" class="orangeButtonStyle" value="Submit" onclick="updateReviewStatus('later','<?php echo $reviewId; ?>','<?php echo $isShikshaInstitute; ?>','','<?php echo $yearOfGraduation; ?>','<?php echo $isMapFlag; ?>',this)">&nbsp;&nbsp; &nbsp;
	<input type="button" class="orangeButtonStyle" value="Cancel" onclick="closeLayer();">
	</li>
	</ul>
	</div>
	
</div>
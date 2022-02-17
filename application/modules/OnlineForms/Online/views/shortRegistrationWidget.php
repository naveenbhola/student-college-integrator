<div class="change-col">
	<div class="shadedBox">
	  <h2>Apply to college online:</h2>
	<ul class="ul-t">
  	<li><p>Create your profile once, use it for various colleges</p></li>
  	<li><p>Fast and completely online process</p></li>
  	<li><p>Track your admission progress</p></li>
 	</ul>
 	<?php
 	$tracking_keyId  = ($trackingPageKeyId > 0) ? $trackingPageKeyId : 167;
 	?>
	<input type="submit" id="autostart-my-application" value="Start Application" class="orange-button pad2" onclick="responseForm.showResponseForm('<?php echo $courseId ;?>','Online_Application_Started','course',{'trackingKeyId': '<?php echo $tracking_keyId;?>','callbackFunction': 'applyNowPBTCallBack','callbackFunctionParams': {'courseId':'<?php echo $courseId;?>'}},{});"> 
  	<div class="clearFix"></div>                 
	</div>
</div>
<script type="text/javascript">
function applyNowPBTCallBack(response, customParam){
	setCookie(response.listingId+"oaf",response.userId,1,"days");
	window.location.reload();
}
</script>

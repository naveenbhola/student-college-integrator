<div class="wrapper-holder">
<?php 
    if(!empty($customMsgText)){
        $showCustomMailerMessage = 1;
        ?>
        <div class="higlightedBox">
            <?php echo $customMsgText; ?>
            <a id="closeRecommendationLayer">&times;</a>
        </div>   
        <?php
    }
?>
<div class="head-fix" style="position:relative; !important;">
	<?php
		if($showCustomMailerMessage != 1){
			?>
			<a id="closeRecommendationLayer">&times;</a>		
		<?php
		}
	?>
	<h3 class="para-L1" style="width:90%;">Students who showed interest in this institute also looked at</h3>
</div>
</div>
<div class="tpl-layer">
	<?php
		foreach($displayDataObject as $courseId =>$data){
			$course = $courseObjs[$courseId];
			$institute = $instituteObjs[$course->getInstituteId()];
			if(is_object($course) && is_object($institute)){
				echo '<div class="tuple-container nomorecourses">';
				$this->load->view('msearch5/msearchV3/mtupleContent',array('course'=>$course,'institute'=>$institute,'courseWidgetData' => $courseWidgetData[$courseId]));

				echo '</div>';
			}
		}
	?>
</div>
<script>
	var customMsgText = '<?php echo $customMsgText;?>';

	if(customMsgText){
		prevMsgTimeOut = 4000; //delay between 2 msgs
		setTimeout(function(){
			handleToastMsg(customMsgText,4000); 
		}, prevMsgTimeOut);
	}

	var ampViewFlag = '<?php echo $ampViewFlag;?>';
	registrationForm.closeRegistrationForm();
	$("#closeRecommendationLayer").on("click",function(){
//		$('#recommendations').dialog('close');
		if(!noJqueryMobile) {
			$.mobile.back();
		}
		else {
			window.history.go(-1);
		}
		if(is_user_logged_in === "false"){
			setTimeout(function(){window.location.reload();},500);
		}
		setTimeout(function(){
			if(ampViewFlag && typeof replaceStateUrl != 'undefined' && replaceStateUrl.length > 0)
			{
				history.replaceState('','',replaceStateUrl);
			}
		},500);
	});
</script>
<?php if(count($institute_features) > 0){
		$urlToRedirectWhenFormExpired    = '/studentFormsDashBoard/MyForms/Index/';
	?>                                    
<div class="onlineAppsListing">
<?php $inst_id = $instituteId;
if(array_key_exists('seo_url', $online_form_institute_seo_url[$onlineCourseId])) {$seo_url = SHIKSHA_HOME.$online_form_institute_seo_url[$onlineCourseId]['seo_url'];} else {$seo_url = "/Online/OnlineForms/showOnlineForms/".$onlineCourseId;}?>

        <?php if(isset($externalURL) && $externalURL!=''){
                //$externalURL = '/Online/OnlineForms/showPage/'.base64_encode($externalURL);
                //call changed for external forms to seo page.
         ?>
        <input type="button" class="onlineAppFormButton" title="Online Application Form" onClick="window.location='<?=$seo_url?>'"/>
        <?php }else{ ?>
        <input type="button" class="onlineAppFormButton" title="Online Application Form" onClick="setCookie('onlineCourseId','<?php echo $onlineCourseId;?>',0); checkOnlineFormExpiredStatus('<?php echo $onlineCourseId;?>','<?php echo $urlToRedirectWhenFormExpired;?>','<?php echo $seo_url?>'); return false;"/>
        <?php } ?>
<?php
	if(!$link){
?>
	<div class="clearFix spacer5"></div>
	
	<div style="float:left; padding-right:5px">
	<a  href="javascript:void(0);" onClick="showHowItWorksLayer();" >How it Works</a> |
	<!--How it Works Layer Starts here-->
	<div class="howitWorksLayerWrapListing" id="howitWorksLayerDiv" style="display:none; top:50px">
		<span class="howitWorksPointerListing"></span>
		<div class="howitWorksLayerContent">
			<div>
			<div class="selectCollege selectCollegeAlign"></div>
			<div class="horArrow1"></div>
			<div class="submitForm submitFormAligner"></div>
			<div class="horArrow2"></div>
			<div class="receiveForm receiveFormAligner"></div>
			<div class="horArrow1"></div>
			<div class="getUpdates getUpdatesAligner"></div>
			<div class="horArrow2"></div>
			<div class="onlineResult"></div>
		</div>    
			<ul class="howWorksLayerDetail">
			<li class="firstItem">
				<strong>Select Colleges</strong>
				<p>Compare and shortlist colleges that you wish to apply</p>
				
			</li>
			
			<li class="secItem">
				<strong>Submit form</strong>
				<p>Fill the application form once and use for multiple college applications. Also, attach documents and make online payment</p>
				
			</li>
			
			<li class="thirdItem">
				<strong>Institute receives form</strong>
				<p>Institute receives and reviews your form.You get instant update as soon as institute reviews the form</p>
				
			</li>
			
			<li class="fourthItem">
				<strong>Get <?=$gdPiName?> Updates</strong>
				<p>Institutes sends the <?=$gdPiName?> updates.You also track your application status at all the stages of admission process</p>
				
			</li>
			
			<li class="fifthItem">
				<strong>Know your result online</strong>
				<p>Get updated about the final decision of the institute towards your admission application</p>
			</li>
			</ul>
			<div class="clearFix"></div>
			<div class="studentNotice">Shiksha.com facilitates application form submission and tracking throught online process. It does not, however, gaurantees admissions.The final decision lies with the <br />institute itself.</div>
			
			<div class="howitWorkBtn"><input type="button" value="Start Now" title="Start Now" class="startNowBtn" onClick="setCookie('onlineCourseId','<?php echo $onlineCourseId;?>',0); window.location.href='/Online/OnlineForms/showOnlineFormsHomepage';" /></div>
			
		</div>
	</div>
	<!--How it Works Layer Ends here-->
	</div>

	<div class="eligibilityBox" style="float:left">
		<a href="javascript:void(0);" onmouseover="if($('eligibilityLayerWrap')) $('eligibilityLayerWrap').style.display = '';" onmouseout="if($('eligibilityLayerWrap')) $('eligibilityLayerWrap').style.display = 'none';">Eligibility Criteria</a>

		<?php $inst_id = $instituteId; ?>

		<!--Eligibility Layer Starts here-->
		<div class="eligibilityLayerWrap" style="display:none;" id="eligibilityLayerWrap">
		<span class="eligibilityPointer"></span>
		<div class="applylayerContent">
		<ul>
		<?php if($institute_features[$inst_id]['min_qualification']):?>
		<li>
			<label>Min Qualification:</label>
			<span><?php echo $institute_features[$inst_id]['min_qualification'];?></span>
		</li>
		<?php endif;?>
		<?php if($institute_features[$inst_id]['fees']):?>
		<li>
			<label>Form Fees:</label>
			<span <?php if($institute_features[$inst_id]['discount']):?>class="line-through"<?php endif;?>>Rs.<?php echo $institute_features[$inst_id]['fees'];?></span>
		</li>
		<?php endif;?>
		<?php if($institute_features[$inst_id]['discount']):?>
		<li>
			<label>Pay only:</label>
			<span><strong>Rs.<?php echo ($institute_features[$inst_id]['fees']-$institute_features[$inst_id]['discount']*$institute_features[$inst_id]['fees']/100)?></strong></span>
		</li>
		<?php endif;?>
		<?php if($institute_features[$inst_id]['exams_required']):?>
		<li>
			<label>Exams Accepted:</label>
			<span><?php echo $institute_features[$inst_id]['exams_required'];?></span>
		</li>
		<?php endif;?>
		<?php if($institute_features[$inst_id]['courses_available']):?>
		<li>
			<label>Courses Available:</label><br />
								<span><?php echo $institute_features[$inst_id]['courses_available']?></span>
		</li>
		<?php endif;?>
		  <?php if($institute_features[$inst_id]['last_date'] && $inst_id!='35413' && $inst_id!='35407'):?>
		<li><div class="lastDateNotify">Last Date to Apply: <span><?php echo $institute_features[$inst_id]['last_date'];?></span></div>
			
		</li>
		<?php endif;?>
		</ul>
			<div class="clearFix"></div>
		</div>
		</div>
		<!--Eligibility Layer Ends here-->
	</div>
	<div class="clear_B"></div>
<?php } ?>
</div>
<?php } ?>


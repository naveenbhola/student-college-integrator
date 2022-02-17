<?php
if($formName == ENT_SA_FORM_EDIT_RMS_COUNSELLOR) {
		$pageTitle = "Edit RMS Counsellor";
		
		$displayData["breadCrumb"] = array(
						array("text" => "All RMS Counsellors", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_RMS_COUNSELLOR ),
						array("text" => "Edit RMS Counsellor", "url" => "")
						);
		$displayData["pageTitle"] = $pageTitle;		
		$displayData["lastUpdatedInfo"] = array(
						"date" => $counsellor['last_modified'],
						"username" => $counsellor['last_modified_by_name']
						);
		
		$formName = ENT_SA_FORM_EDIT_RMS_COUNSELLOR;
		$actionFunction = $saveFunctionName;
		$counsellor['counsellor_image'] = ($counsellor['counsellor_image']==''?'/public/images/photoNotAvailable.gif':$counsellor['counsellor_image']);
} else if($formName == ENT_SA_FORM_ADD_RMS_COUNSELLOR) {
		$displayData["breadCrumb"] = array(
						array("text" => "All RMS Counsellors", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_RMS_COUNSELLOR ),
						array("text" => "Edit RMS Counsellor", "url" => "")
						);
		$displayData["pageTitle"] = "Add New RMS Counsellor";
		$actionFunction = $saveFunctionName;
}
else{
	show_404_abroad();
	die();
}
?>

<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<div class="abroad-cms-rt-box">
    <?php $this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData); ?>
    <form name="form_<?=$formName?>" id="form_<?=$formName?>" action="<?=ENT_SA_CMS_PATH?><?= $actionFunction;?>" enctype="multipart/form-data" method="post">
	<?php
		if($formName == ENT_SA_FORM_EDIT_RMS_COUNSELLOR){
	?>
			<input type="hidden" name="counsellorId" value="<?=$counsellor['counsellor_id']?>"/>
			<input type="hidden" name="created" value="<?=$counsellor['created']?>"/>
			<input type="hidden" name="createdBy" value="<?=$counsellor['created_by']?>"/>
			<input type="hidden" name="seoUrl" value="<?=$counsellor['seoUrl']?>"/>
	<?php
		} else { ?>
		    <input type="hidden" name="counsellorUserId" value=""/>
	<?php 			
		}
	?>
	
    <?//php if($guideId !=''){?>
	    <!--<input type="hidden" name="guideId" value="<?= $guideId?>" />
	    <input type="hidden" name="createdById" value="<?= $createdById?>" /> -->
	<?//php }?>
	    <div class="cms-form-wrapper clear-width">
			<div class="clear-width">
				<h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite minus-icon"></i>Counsellor Detail</h3>
				<div class="cms-form-wrap">
					<ul>
						<li>
                            <label>Counsellor Image* : </label>
                            <div class="cms-fields">
                                <?php if($formName == ENT_SA_FORM_ADD_RMS_COUNSELLOR){ ?>
                                    <input type="file" name = "counsellorImage_<?=$formName?>[]" id = "counsellorImage_<?=$formName?>"  onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" required="true" caption = "Counsellor Image"  validationType = "file"/>
                                	<div id="counsellorImage_<?=$formName?>_error" class="errorMsg" style="display: none" />
									</div>
                                <?php }else{
                                        $styleForLogoInput = 'style="display: none;"';
                                    ?>
                                    <input type="file" id = "counsellorImage_<?=$formName?>" name = "counsellorImage_<?=$formName?>[]" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "Counsellor Image" <?=$styleForLogoInput?> validationType = "file"/>
                                    <div style="display: none" class="errorMsg" id="counsellorImage_<?=$formName?>_error"></div>
                                    <div class="image-box">
                                        <img src="<?php echo MEDIAHOSTURL.$counsellor['counsellor_image'];?>" width="116" height="117" alt="counsellor image"><i class="abroad-cms-sprite remove-icon2 remove-couns-img"></i>
                                        <input type="hidden" id = "counselorImageUrl" name="counselorImageUrl" value="<?php echo html_entity_decode($counsellor['counsellor_image']); ?>"/>
                                    </div>
                                <?php } ?>
                                <div style="display: none" class="errorMsg" id="counsellorImage_<?=$formName?>_error"></div>
                            </div>
                        </li>
						<li>
							<label>Counsellor Email* : </label>
							<div class="cms-fields">
								<input id="counsellorEmail_<?=$formName?>" <?=($formName == ENT_SA_FORM_EDIT_RMS_COUNSELLOR?'disabled="disabled" ':'')?> name="counsellorEmail_<?=$formName?>" type="text" class="universal-txt-field cms-text-field" validationType="email" required="true" caption="Counsellor Email" minlength="1" maxlength="100" onblur="showErrorMessage(this,'<?=$formName?>');"/>
								<div id="counsellorEmail_<?=$formName?>_error" class="errorMsg" style="display: none" />
							</div>
							<input type="checkbox" id="counsellorIsManager_<?=$formName?>" name="counsellorIsManager_<?=$formName?>" value="1"> IsTL?<br>
						</li>
						<li>
							<label>Counsellor Name* : </label>
							<div class="cms-fields">
								<input id="counsellorName_<?=$formName?>" disabled="disabled" name="counsellorName_<?=$formName?>" type="text" class="universal-txt-field cms-text-field" validationType="name" required="true" caption="Counsellor Name" minlength="1" maxlength="100"/>
								<div id="counsellorName_<?=$formName?>_error" class="errorMsg" style="display: none" />
							</div>
							
						</li>
						<li>
							<label>Counsellor Mobile No* : </label>
							<div class="cms-fields">
								<input id="counsellorMobile_<?=$formName?>" name="counsellorMobile_<?=$formName?>" type="text" class="universal-txt-field cms-text-field" validationType="mobile" required="true" caption="Counsellor Mobile Number" minlength="10" maxlength="10"/>
								<div id="counsellorMobile_<?=$formName?>_error" class="errorMsg" style="display: none" />
							</div>
							
						</li>
						<li>
							<label>Counsellor Manager* : </label>
							<div class="cms-fields">
								<select class="universal-select cms-field" validationType="select" caption="Manager" id="manager_<?=$formName?>" name="manager_<?=$formName?>" required="true">
									<?php if($formName == ENT_SA_FORM_ADD_RMS_COUNSELLOR){
									?>
										<option value="">Select One</option>
									<?php } ?>
									<?php
										foreach($managers as $manager){
											if($manager['email'] == $counsellor['counsellor_email']){
												continue;
											}
									?>
										<option email="<?=$manager['email']?>" value="<?=$manager['id']?>"><?=$manager['name']?></option>
									<?php } ?>
								</select>
								<div id="manager_<?=$formName?>_error" class="errorMsg" style="display: none"/>
							</div>
						</li>
						<li>
                            <label>Counsellor Bio* : </label>
							<div class="cms-fields">
								<textarea class="cms-textarea" name = "counsellorBio_<?=$formName?>" id="counsellorBio_<?=$formName?>" maxlength = "1000" required = true caption = "counsellor bio" validationType = "html"><?php echo html_entity_decode($counsellor["counsellor_bio"]); ?></textarea>
								<div style="display: none" class="errorMsg" id="counsellorBio_<?=$formName?>_error">error shown here</div>
							</div>
						</li>
						<li>
                            <label>Counsellor Expertise* : </label>
							<div class="cms-fields">
								<textarea class="cms-textarea" name = "counsellorExpertise_<?=$formName?>" id="counsellorExpertise_<?=$formName?>" maxlength = "1000" required = true caption = "counsellor expertise" validationType = "html"><?php echo html_entity_decode($counsellor["counsellor_expertise"]); ?></textarea>
								<div style="display: none" class="errorMsg" id="counsellorExpertise_<?=$formName?>_error">error shown here</div>
							</div>
						</li>
					 </ul>
				</div>
			</div>
        </div>
    </form>
	<div class="button-wrap">
		<?php if($formName == ENT_SA_FORM_EDIT_RMS_COUNSELLOR){ ?>
			<a target="_blank" href="<?=SHIKSHA_STUDYABROAD_HOME.$counsellor['seoUrl']?>" class="gray-btn">Preview</a>
		<?php } ?>
		<a href="javascript:void(0);" onclick="saveRMSCounsellorForm(this, '<?=$formName?>')" id="bttnSavePublish" name="bttnSavePublish" class="orange-btn">Save & Publish</a>
		<a href="javascript:void(0);" onclick="cancelAction()" class="cancel-btn">Cancel</a>
    </div>
    <div class="clearFix"></div>
    </div>
<script>
	var preventOnUnload = false;
	
	function startCallback(){
		return true;
	}
	
	function completeCallback(response) {
        saveInitiated = false;
        // check response
        var respData;
        if (response != 0) {
            respData = JSON.parse(response);
        }
		if (respData == 1) {
			alert("Operation Successful!");
			preventOnUnload = true;
			window.location.href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_RMS_COUNSELLOR?>";
		}
        if (typeof(respData) != 'undefined') {
            preventOnUnload = true;
            if (typeof(respData.Email) != 'undefined') {
				$j("#counsellorEmail_<?=$formName?>_error").html(respData.Email).show();
			}
			if (typeof(respData.Mobile) != 'undefined') {
				$j("#counsellorMobile_<?=$formName?>_error").html(respData.Mobile).show();
			}
			if (typeof(respData.fileError) != 'undefined') {
				$j("#counsellorImage_<?=$formName?>_error").html(respData.fileError).show();
			}
			$j("#counsellorName_"+formname)  .attr("disabled","disabled");
		    $j("#counsellorMobile_"+formname).attr("disabled","disabled");
	
        }
        else{
			alert("Operation Successful!");
			preventOnUnload = true;
			window.location.href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_RMS_COUNSELLOR?>";
        }
    }
	
	function initFormPosting() {
        AIM.submit(document.getElementById('form_<?=$formName?>'), {'onStart' : startCallback, 'onComplete' : completeCallback});
    }
    
    if(document.all) {
            document.body.onload = initFormPosting;
    } else {
            initFormPosting();
    }
		
	function cancelAction() {
			if (confirm("Are you sure you want to cancel? All data changes will be lost.")) {
					window.location.href = "<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_RMS_COUNSELLOR?>";
			}
	}
	
	function confirmExit(){
		if(preventOnUnload == false){
			return 'Any unsaved change will be lost.';
		}
	}
	<?php
	if ($formName == ENT_SA_FORM_EDIT_RMS_COUNSELLOR) { ?>
		document.getElementById("counsellorName_<?=$formName?>").value="<?=$counsellor["counsellor_name"]?>";
		document.getElementById("counsellorEmail_<?=$formName?>").value="<?=$counsellor["counsellor_email"]?>";
		document.getElementById("counsellorMobile_<?=$formName?>").value="<?=$counsellor["counsellor_mobile"]?>";
		document.getElementById("manager_<?=$formName?>").value="<?=$counsellor["counsellor_manager_id"]?>";
		<?php if($counsellor["is_manager"] == 1) {?>
		document.getElementById("counsellorIsManager_<?=$formName?>").checked = true;
		<?php }?>
	<?php } ?>
</script>

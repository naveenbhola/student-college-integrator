<style>
.questionBoxStyle{width:100%; height:60px; margin-bottom:10px;}
</style>
<!--[if IE 6]>
<style>
.questionBoxStyle{width:500px; height:60px; margin-bottom:10px;}
</style>
<![endif]-->
<?php 
     $userPoints = isset($userPoints)?$userPoints:0;	
     if(($questionId > 0) || ($userPoints >= 10))
     {			
	if(isset($questionText) && (trim($questionText) != '')){  
		$questionText = trim($questionText);
		$questionTextCharCount = strlen(trim($questionText));
	}else{
		$questionText = '';
		$questionTextCharCount = 0;	
	}
	$alertCheckBox = "checked";
	if(isset($alertResult) && ($alertResult == 0) && ($questionId > 0)){
		$alertCheckBox = ""; 
	}
	
	if(($alertCount >= 5) && ($questionId < 0)){	
		$alertCheckBox = ""; 
	}
	
	if((isset($alertCount) && ($alertCount >= 5)) || ($questionId > 0)){
		$alertCheckBox .= " disabled"; 
	}
?>
<div style="margin:0 17%">
<div style="display:block;" id="createTopicFormBox">
	<div>
		<?php 
			$action = '/messageBoard/MsgBoard/createTopic';
			$validateFunctionCall = "return validateQuestionForm_old(this,'ANA_EDIT_QUESTION','formsubmit','askQuestionForm');";
			$buttonText = 'Post Question';
			$questionLabel = 'Please Type Your Question';
			$categorySectionStyle='';
			$textAreaAttriButes = 'onkeyup="textKey(this);if((event.keyCode == 32) || (event.keyCode == 8) || (event.keyCode == 46)){callGetRelatedQuestion(this,event.keyCode);}" blurMethod="callGetRelatedQuestion(this,32);"';
			if($questionId > 0){				
				$buttonText = 'Update';
				$textAreaAttriButes = 'onkeyup="textKey(this);"';
			}
			if(($questionId > 0) || ($questionTextCharCount >= 10)){
				$questionLabel = 'Your Question';
			}
			$attributes = array('id' => 'askQuestionForm','onSubmit' => $validateFunctionCall);
			echo form_open($action,$attributes); 
		?>
	        <div class="mar_full_10p normaltxt_11p_blk">
	                <div class="lineSpace_5">&nbsp;</div>
			<div id="createTopicErrorMsgPlace" class="errorMsg" style="display:none;">&nbsp;</div>
			<div class="lineSpace_10">&nbsp;</div>
	                <div style="margin-bottom:5px" class="fontSize_13p bld"><?php echo $questionLabel; ?>:</div>
	                <div><textarea  name="topicDesc" id="topicDesc"  onblur="enterEnabled=false;" onfocus="enterEnabled=true;"  <?php echo $textAreaAttriButes; ?>  profanity="true" validate="validateStr" caption="Question" maxlength="300" minlength="2" required="true" class="questionBoxStyle"><?php echo $questionText; ?></textarea></div>
	                <div style="margin-bottom:5px">
			    <table width="100%" cellpadding="0" border="0">
			    <tr>
			    <td><span id="topicDesc_counter"><?php echo $questionTextCharCount; ?></span>&nbsp;out of 300 characters</td>
			    <td><div align='right'><span align="right">Make sure your question follows <a href="javascript:void(0);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline');">Community Guidelines</a>&nbsp;</span></div></td>
			    </tr></table>
			</div>
			<div class="row errorPlace">
				<div id="topicDesc_error" class="errorMsg"></div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="mar_full_10p" id="relatedQuestionPlace">
			<?php 
	 		if(is_array($searchResult['results']) && count($searchResult['results']) > 0){ 
				$categorySectionStyle='style="display:none;"';
			?>
				<div class="bld fontSize_14p">
					<img src="/public/images/similarQuestions.gif" align="absmiddle" /><span class="bld">Wait!</span> We may already have the answer you are looking for. Check the similar questions and their answers below.
				</div>
				<div class="lineSpace_10">&nbsp;</div>
				<div style="background-color:#F7FFE7;border:1px solid #DDF3AB; padding:10px">
					<?php foreach($searchResult['results'] as $questionDetails){
					      if(is_array($questionDetails)){
					?>
					<div class="qmarked">
						<a href="#" onClick="javascript:addCompleteQuestionToCookie('<?php echo $questionDetails['url']; ?>');return false;" style="color:#000000;"><?php echo $questionDetails['title']; ?></a><br />
						<a href="#" onClick="javascript:addCompleteQuestionToCookie('<?php echo $questionDetails['url']; ?>');return false;">
							<?php echo $questionDetails['noOfComments']; ?> answers
						</a>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
					<?php } } 
						if(isset($searchResult['numOfRecords']) && ($searchResult['numOfRecords'] > 5)){	
					?> 
					<div align="right"><a href="#" onClick="searchAllQuestion();return false;" id="viewAllQuestionLink">View All &lt; <?php echo $searchResult['numOfRecords']; ?> &gt;</a></div>
					<?php } ?>
				</div>
			<?php } ?>	
			</div>
				<div>
				<div class="lineSpace_18">&nbsp;</div>
				<div id="categorySectionLinkForAskQuestion" <?php if($categorySectionStyle===''){ echo 'style="display:none;"'; } ?>><button onClick="javascript:showHideCatSection(true);return false;">
	                        <div class="btn-submit13">
					<p class="btn-submit14 whiteFont">I still want to post my question</p>
				</div></button>
				</div>
				</div>
			<div <?php echo $categorySectionStyle; ?> id="categorySectionId">
			<?php if($questionId <= 0){ ?>
			<div style="margin-bottom:5px" class="fontSize_13p bld"><span>Select Category : </span></div>
	                <div id="c_categories_combo"></div>
			<div class="row errorPlace">
				<div id="selectCategory_error" class="errorMsg"></div>
			</div>
			<div class="lineSpace_18">&nbsp;</div>
			<div style="margin-bottom:5px" class="fontSize_13p bld"><span>Select Country : </span><span class="grayFont" style="font-size:8px;">(Max 3)</span></div>
	                <div id="country_combo">
				<select name="countryListForCreateTopic[]" id="countryListForCreateTopic" multiple size="6" tip="question_country" style="width:297px;" caption="Country" required="true" validate="validateSelect">
					<?php
		
						$selectedCountryArray = ($csvCountryList != '')?explode(",",$csvCountryList):array();
		                                foreach($countryList as $country) {
		                                    $countryId = $country['countryID'];
		                                    $countryName = $country['countryName'];
		                                    if($countryId == 1) { continue; }
		                                    $selected = "";
		                                    if(in_array($countryId,$selectedCountryArray)) { $selected = 'selected="true"'; }
				        ?>
					<option value="<?php echo $countryId; ?>" <?php echo $selected ?> ><?php echo $countryName; ?></option>
				       <?php
				           }
				       ?>
				</select>
			</div>
			<div class="row errorPlace">
				<div id="countryListForCreateTopic_error" class="errorMsg"></div>
			</div>	
			<?php } ?>	
			
			<div>	
				<div class="fontSize_12p">Questions posted will be answered &amp; discussed by shiksha experts &amp; users</div>
			</div>	
	                <div class="lineSpace_10">&nbsp;</div>
	                <div class="fontSize_12p"><input type="checkbox" name="setAlert" id="setAlert" value="on" <?php echo $alertCheckBox; ?>   /> Send me an email if someone answers my question.</div>
			<div class="lineSpace_5">&nbsp;</div>
			<div id="quotaExceedMsg" style="display:none;">(Quota of Alerts exceeded.Please delete an existing alert to create a new one&nbsp;<a href="/alerts/Alerts/alertsHome/12">Manage alerts</a>)</div>	
	                <div class="lineSpace_20">&nbsp;</div>	             
			<input type="hidden" name="categoryListForCreateTopic" id="categoryListForCreateTopic" value="<?php echo isset($csvCatList)?$csvCatList:''; ?>"/>
			<input type="hidden" name="listingType" id="listingType" value="<?php echo isset($listingType)?$listingType:''; ?>"/>
			<input type="hidden" name="listingTypeId" id="listingTypeId" value="<?php echo isset($listingTypeId)?$listingTypeId:''; ?>"/>
			<input type="hidden" name="secCodeIndex" value="seccodeForAskQuestion" />
			<input type="hidden" name="editit" id="editit" value="<?php echo $questionId; ?>" />
			<div class="mar_left_20per">
	                <button class="btn-submit13" type="Submit" style="width:125px" id="mainCreateTopicButton">
	                        <div class="btn-submit13"><p class="btn-submit14 whiteFont" id="createTopicButton">
							<span style="position:relative;top:-2px;*top:1px"><?php echo $buttonText; ?></span></p></div>
	                </button>&nbsp;<button class="btn-submit5 w6" type="button" onClick="setCookie('commentContent', '',-1);location.href = 'javascript:history.go(-1);'">
	                        <div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
	                </button>
			</div>
			</div>
	                <div class="lineSpace_10">&nbsp;</div>

	        </div>
	     </form>	
	</div>
</div>
</div>

<?php  }
	if($userPoints < 10):
?>
<div align="center">
<div style="border:1px solid #A9A9A9;width:430px;" id="pointExhaustOverlay">
							<div style="padding:10px 10px;" class="fontSize_12p">You need atleast 10 shiksha points to ask a question.You can earn shiksha points by completing any of the following actions.</div>
							<div class="row">
								<div style="margin:0 10px">
									<div style="width:240px; border-right:1px solid #EDEDEB; background:#EDEDED;line-height:25px; float:left" class="OrgangeFont bld fontSize_13p"><span class="pd_left_10p">Action</span></div>
									<div style="width:160px; background:#EDEDED;line-height:25px; float:left" class="OrgangeFont bld fontSize_13p"><span class="pd_left_10p">Points</span></div>
									<div class="clear_L"></div>
								</div>
							</div>
							<div class="row">
								<div style="margin:0 10px; border-bottom:1px solid #EDEDEB">
									<div style="width:210px; border-right:1px solid #EDEDEB;line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">Answer a question</span></div>
									<div style="width:160px; line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">10</span></div>
									<div class="clear_L"></div>
								</div>
							</div>
							<div class="row">
								<div style="margin:0 10px; border-bottom:1px solid #EDEDEB">
									<div style="width:210px; border-right:1px solid #EDEDEB;line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">Add an Event</span></div>
									<div style="width:160px; line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">10</span></div>
									<div class="clear_L"></div>
								</div>
							</div>
							<div class="row">
								<div style="margin:0 10px; border-bottom:1px solid #EDEDEB">
									<div style="width:210px; border-right:1px solid #EDEDEB;line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">Request Info for a course</span></div>
									<div style="width:160px; line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">10</span></div>
									<div class="clear_L"></div>
								</div>
							</div>
							<div class="row">
								<div style="margin:0 10px; border-bottom:1px solid #EDEDEB">
									<div style="width:210px; border-right:1px solid #EDEDEB;line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">Create Alert</span></div>
									<div style="width:160px; line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">10</span></div>
									<div class="clear_L"></div>
								</div>
							</div>
							<div class="row">
								<div style="margin:0 10px; border-bottom:1px solid #EDEDEB">
									<div style="width:210px; border-right:1px solid #EDEDEB;line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">Upload college photo in groups</span></div>
									<div style="width:160px; line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">10</span></div>
									<div class="clear_L"></div>
								</div>
							</div>
							<div class="row">
								<div style="margin:0 10px; border-bottom:1px solid #EDEDEB">
									<div style="width:210px; border-right:1px solid #EDEDEB;line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">Join college group</span></div>
									<div style="width:160px; line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">10</span></div>
									<div class="clear_L"></div>
								</div>
							</div>
							<div class="row">
								<div style="margin:0 10px; border-bottom:1px solid #EDEDEB">
									<div style="width:210px; border-right:1px solid #EDEDEB;line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">Join school group</span></div>
									<div style="width:160px; line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">10</span></div>
									<div class="clear_L"></div>
								</div>
							</div>
							<div class="row">
								<div style="margin:0 10px; border-bottom:1px solid #EDEDEB">
									<div style="width:210px; border-right:1px solid #EDEDEB;line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">Invite friends to Shiksha</span></div>
									<div style="width:160px; line-height:25px; float:left" class="fontSize_13p"><span class="pd_left_10p">10 per invite.</span></div>
									<div class="clear_L"></div>
								</div>
							</div>
							<div style="line-height:30px">&nbsp;</div>
</div>
</div>
<?php endif; ?>

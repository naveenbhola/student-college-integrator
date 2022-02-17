<div id="createTopicFormBox" style="position:absolute;display:none;z-index:100000001;">
	<div style="width:400px">
		<?php echo $this->ajax->form_remote_tag( array(
                            'url' => '/messageBoard/MsgBoard/createTopic',
                            'success' => 'javascript:topicposted(request.responseText)' 	
			)
                    ); 
		?>
        <input type = "hidden" name = "otherParam" id = "otherParam" value = "collegegroup"/>
        <input type = "hidden" name = "listingTypeId" id = "listingTypeId" value = "<?php echo $collegeId?>"/>
        <input type = "hidden" name = "listingType" id = "listingType" value = "<?php echo $grouptype?>"/>
        <input type = "hidden" name = "setAlert" id = "setAlert" value = "off"/>
        <input type = "hidden" name = "editit" id = "editit" value = "-1"/>
        <input type ="hidden" name = "fromOthers" id = "fromOthers" value = "<?php echo $grouptype?>"/>
        <input type ="hidden" name = "secCodeIndex" id = "secCodeIndex" value = "seccodeTopic"/>

	        <div class="mar_full_10p normaltxt_11p_blk">
	                <div class="lineSpace_5">&nbsp;</div>
			<div id="createTopicErrorMsgPlace" class="errorMsg" style="display:none;">&nbsp;</div>
			<div class="lineSpace_10">&nbsp;</div>
	                <div style="margin-bottom:5px">Topic Description:</div>
	                <div><textarea  name="topicDesc" id="topicDesc"  onblur="enterEnabled=false;" onfocus="enterEnabled=true;" onkeyup="textKey(this)" profanity="true" validate="validateStr" caption="Topic description" maxlength="1000" minlength="25" required="true" style="width:370px; height:100px; margin-bottom:10px"></textarea></div>
	                <div style="margin-bottom:5px"><span id="topicDesc_counter">0</span>&nbsp;out of 1000 characters</div>
			<div class="row errorPlace">
				<div id="topicDesc_error" class="errorMsg"></div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div>	
			</div>	
	                <div class="lineSpace_10">&nbsp;</div>
<!--                <div><input type="checkbox" name="setAlert" id="setAlert" value="on" checked /> Send me an email if someone comments on my topic.</div>
			<div class="lineSpace_5">&nbsp;</div>
			<div id="quotaExceedMsg" style="display:none;">(Quota of Alerts exceeded.Please delete an existing alert to create a new one&nbsp;<a href="/alerts/Alerts/alertsHome/12">Manage alerts</a>)</div>
	                <div class="lineSpace_20">&nbsp;</div>-->
	                <div style="margin-bottom:5px">Type in the characters you see in the picture below:</div>
	                <div class="lineSpace_10">&nbsp;</div>
	                <div class="txt_align_c">
                    <img src="/CaptchaControl/showCaptcha?width=100&height=30&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccodeTopic"  onabort="reloadCaptcha(this.id)" id="topicCaptcha" align="absmiddle"/>&nbsp;
                    <input type="text" name="secCodeOther" id="secCodeOther" size="5" validate="validateSecurityCode" maxlength="5" minlength="5" required="true" caption="Security Code"/> </div>
			<div class="row errorPlace">
				<div id="secCodeOther_error" class="errorMsg"></div>
			</div>
	                <div class="lineSpace_10">&nbsp;</div>
			<div class="row txt_align_c">
	                <button class="btn-submit13" type="Submit" onClick = "return validateGroupTopic(this.form);" style="width:125px" id="mainCreateTopicButton">
	                        <div class="btn-submit13"><p class="btn-submit14 whiteFont" id="createTopicButton">Submit</p></div>
	                </button>
	                <button class="btn-submit5 w6" type="button" onClick="javascript:hideOverlay();">
	                        <div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
	                </button>
			</div>
	                <div class="lineSpace_10">&nbsp;</div>

	        </div>
	     </form>	
	</div>
</div>



<div style="display:none;width:430px;" id="pointExhaustOverlay">
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

<script>
var FormObject = document.getElementById('topicDesc').form;
addOnBlurValidate(FormObject);
fillProfaneWordsBag();
</script>


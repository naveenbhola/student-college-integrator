<?php if(isset($userGroup) && (strcmp($userGroup,'cms') !== 0)) {
	$detailPageUrl = isset($detailPageUrl)?$detailPageUrl:-1;
	$message = 'Write a new comment...';

?>
<!--Start_Inline_Comment-->
<div id="inlineAnswerFormCntainer<?php echo $threadId; ?>" >
<input type ="hidden" id="detailPageUrl<?php echo $threadId; ?>" value="<?php echo $detailPageUrl; ?>" />
	<!-- Start of form -->
	<?php
	$url = site_url("messageBoard/MsgBoard/replyMsg");
	echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$threadId,'before' => 'try{ checkTextElementOnTransition(document.getElementById(\'replyText'.$threadId.'\'),\'focus\');if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$threadId.')} }catch (e) {}','success' => $callBackFunction));
	?>
		<div style='display:block;'>
		  <textarea name="replyText<?php echo $threadId; ?>_hide" class="ftBx" id="replyText<?php echo $threadId; ?>_hide" onclick="try{ showEntityCommentForm('<?php echo $threadId; ?>'); }catch (e){}" validateSingleChar='true'><?php echo $message;?></textarea>
		</div>

		<!--Start_For Show and Hide-->
		<div style="display:none;padding-left:10px;overflow:hidden;line-height:20px;" id="hiddenFormPart<?php echo $threadId; ?>">
			<div class="imgBx">
				<div class="wdh100">
				  <img id="userProfileImageForComment<?php echo $threadId; ?>" align='left' valign='top' src="<?php echo ($userImageURL != '')?getTinyImage($userImageURL):getTinyImage('/public/images/photoNotAvailable.gif');?>" />
				</div>
			</div>
			<div class="cntBx">
				<div class="flLt wdh100">                                                            
					<div class="wdh100 flLt">
						<div>
							<textarea name="replyText<?php echo $threadId; ?>" onkeyup="textKey(this)" class="ftxArea" id="replyText<?php echo $threadId; ?>" validate="validateStr" caption="Comment" maxlength="300" minlength="3" required="true" rows="3" validateSingleChar='true'></textarea>
						</div>
						<div class="Fnt10 fcdGya"><span id="replyText<?php echo $threadId; ?>_counter">0</span> out of 300 characters</div>
						<div class="errorPlace Fnt11" style="display:none;"><div class="errorMsg" id="replyText<?php echo $threadId; ?>_error"></div></div>
						<div class="lineSpace_1">&nbsp;</div>                                                                                                                                
						<div class="flLt lineSpace_22 fcdGya">Make sure your answer follows <a href="javascript:void(0);" onclick='return popitup("<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline");'>Community Guidelines</a></div>
						<div class="float_R pr10 lineSpace_22">
							<input type="Submit" class="orange-button" value="Submit" onclick = "<?php echo $onClick ?>" id="submitButton<?php echo $threadId; ?>"/>&nbsp;&nbsp;
							<a href="javascript:void(0);" onClick="hideEntityCommentForm('<?php echo $threadId; ?>',true);">Cancel</a>
						</div>
						<div class="clear_B">&nbsp;</div>
					</div>
				</div>
			</div>
			<div class="errorPlace" style="display:block"><div class="errorMsg" id="seccode<?php echo $threadId; ?>_error"></div></div>
			<input type="hidden" name="threadid<?php echo $threadId; ?>" value="<?php echo $threadId; ?>" id="threadid<?php echo $threadId; ?>" />
			<input type="hidden" name="secCodeIndex" value="seccodeForInlineAnswer" id="secCodeIndex" />	
			<input type="hidden" name="fromOthers<?php echo $threadId; ?>" value="blog" id="fromOthers<?php echo $threadId; ?>" />
			<input type="hidden" name="actionPerformed<?php echo $threadId; ?>" value="addAnswer" id="actionPerformed<?php echo $threadId; ?>"/>
			<input type="hidden" name="functionToCall" id="functionToCall" value="incrementCommentCount()" />
			<input type="hidden" name="userProfileImage<?php echo $threadId; ?>" id="userProfileImage" value="<?php echo ($userImageURL != '')?$userImageURL:'/public/images/photoNotAvailable.gif';?>" />
			<input type="hidden" id="tracking_keyid<?php echo $threadId; ?>" name="tracking_keyid" value="<?=$trackingPageKeyId?>">
		</div>

	</form>
	<!-- End of form -->
</div>
<!--End_Inline_Comment-->
<?php } ?>

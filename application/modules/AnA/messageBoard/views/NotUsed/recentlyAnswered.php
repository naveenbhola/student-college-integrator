
<?php
	$userRecentAnswer = (isset($userQuestionAndAnswer[0]) && is_array($userQuestionAndAnswer[0]))?$userQuestionAndAnswer[0]['answer']:array();
	$userRecentAnswersQestion = (isset($userQuestionAndAnswer[1]) && is_array($userQuestionAndAnswer[1]))?$userQuestionAndAnswer[1]['question']:array();
	$questionCreationDate = makeRelativeTime($userRecentAnswersQestion['creationDate']);
	$viewText = ($userRecentAnswersQestion['viewCount'] <=1)?'<span style="color:#000">'.$userRecentAnswersQestion['viewCount'].'</span> View':'<span style="color:#000">'.$userRecentAnswersQestion['viewCount'].'</span> Views';
	if($userRecentAnswersQestion['answerCount'] == 0){
		$answerText = 'No answer';
	}else{
		$answerText = ($userRecentAnswersQestion['answerCount'] ==1)?$userRecentAnswersQestion['answerCount'].' answer':$userRecentAnswersQestion['answerCount'].' answers';
	}
	
	$urlForQuestion = $userRecentAnswersQestion['url'];
	$msgId = $userRecentAnswer['msgId'];
	$url = site_url("messageBoard/MsgBoard/replyMsg");
	$mainAnsId = $userRecentAnswer['mainAnswerId'];
	$functionToCall = 'editResultOnQuestionPostedPage()';
	$userProfile = site_url('getUserProfile').'/'; 
?>
<div class="defaultAdd lineSpace_10">&nbsp;</div>
<!--Start_Recently_Question-->
<div class="darkOrangeColor Fnt14" style="padding-bottom:4px"><b>Your Recently Answered Question</b></div>
<div style="width:100%">
	<div class="shik_outerBorder" style="background:#eff7ff;padding-top:9px">
		<div class="marfull_LeftRight10">
			<!--Start_QuestionListing-->
			<div style="width:100%">
				<div class="shik_bgQuestionSign">
					<div style="line-height:17px"><?php echo $userRecentAnswersQestion['msgTxt']; ?></div>
					<div class="grayColorFnt12" style="padding:10px 0;line-height:20px">Asked by <a href="<?php echo $userProfile.$userRecentAnswersQestion['displayname']; ?>" class="Fnt12" target="_blank"><?php echo $userRecentAnswersQestion['displayname']; ?></a> <span style="color:#000"><?php echo $questionCreationDate; ?></span>, <?php echo $viewText; ?>, <a href="<?php echo $urlForQuestion; ?>" target="_blank" class="Fnt12"><?php echo $answerText; ?></a></div>
					<!--Start_ShowConfimationMessage-->
					<div style="width:100%;margin-bottom:10px;display:none;" id="confirmationForEditAnswer">
						<div class="shik_confirmationMsg" style="padding:5px;">
							<div style="width:100%" >
								<div class="float_L" style="width:96%">
									<div style="width:100%" >
										<div class="Fnt14"><b>Your Answer has been succesfully changed.</b></div>
									</div>
								</div>
								<div class="float_L" style="width:4%">
									<div><a href="javascript:void(0);" class="spirit_header shik_crossButton" style="text-decoration:none" onClick="javascript:showDiv(new Array('confirmationForEditAnswer'),-1);">&nbsp;</a></div>
								</div>
								<div class="clear_L">&nbsp;</div>
							</div>
						</div>
					</div>
					<!--End_ShowConfimationMessage-->
					<div id="answerAndLinkContainer" style="width:100%;">
						<!--Start_share_this_question-->
						<div style="border:1px solid #c6e1fc;background:#FFF;padding:10px;width:70%;">
							<div style="width:100%">
								<div style="width:100%">
									<div class="float_L" style="width:60%">
										<div><b>Your Answer</b></div>
									</div>
									<div class="float_L" style="width:40%">
										<div class="txt_align_r">
											<a href="javascript:void(0);" onClick="javascript:showEditAnswerForm('<?php echo $msgId; ?>');">edit</a>
										</div>
									</div>
									<div class="clear_L">&nbsp;</div>
								</div>
								<div style="padding-top:5px" id="msgTxtContent<?php echo $msgId; ?>"><?php echo $userRecentAnswer['msgTxt']; ?></div>
							</div>
						</div>
						<!--End_share_this_question-->
						<!--Start_BottomLink-->
						<div class="txt_align_r shik_bottomLinksMargin"><a href="javascript:void(0);" onClick="javascript:showShareThisQuestionOverlay();" style="padding-right:20px"><span class="cssSprite_Icons" style="background-position:-288px -2px;padding-left:20px">&nbsp;</span>Share This Question</a></div>
						<!--End_BottomLink-->
					</div>
				</div>	
			</div>
			<!--End_QuestionListing-->
			<!-- start of form -->
					<div style="background:#eff7ff;position:relative;height:155px;width:100%;display:none;" id="replyForm<?php echo $msgId;?>">
						<?php
						echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$msgId,'before' => 'if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$msgId.')}','success' => 'javascript:addSubComment('.$msgId.',request.responseText,'.$mainAnsId.',\''.$functionToCall.'\');'));
						?>
						<div style="width:100%;position:relative">
							<div style="width:85px"><b>Your Answer</b></div>
							<div style="width:100%;position:absolute;top:0">
								<div style="margin-left:85px">
									<div style="width:100%">
										<div><textarea name="replyText<?php echo $msgId; ?>" onkeyup="textKey(this)" class="textboxBorder mar_left_10p" id="replyText<?php echo $msgId; ?>" validate="validateStr" caption="Answer" maxlength="1250" minlength="3" required="true" rows="5" style="width:98%;"></textarea></div>
										<div style="margin-left:10px;">
											<div style="padding-top:6px">
												<table width="100%" cellpadding="0" border="0">
												<tr>
												<td><span id="replyText<?php echo $msgId; ?>_counter">0</span> out of 1250 character</td>
												<script>
												  if(document.getElementById('replyText<?php echo $msgId; ?>').offsetWidth > 450)
												    document.write("<td><div align='right'><span align='right'>Make sure your answer follows <a href='javascript:void(0);' onclick='return popitup(\"<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline\");'>Community Guidelines</a>&nbsp;</span></div></td>");
												  else
												    document.write("<td><div align='right'><span align='right'>Shiksha <a href='javascript:void(0);' onclick='return popitup(\"<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline\");'>Community Guidelines</a>&nbsp;</span></div></td>");
												</script>
												</tr></table>
											</div>
											<div style="display:none;">
												<div class="errorMsg" id="replyText<?php echo $msgId; ?>_error"></div>
											</div>
											<div style="display:none;">
												<div class="errorMsg" id="seccode<?php echo $msgId; ?>_error"></div>
											</div>
											<input type="hidden" name="threadid<?php echo $msgId; ?>" value="<?php echo $threadId; ?>" />
											<input type="hidden" name="actionPerformed<?php echo $msgId; ?>" id="actionPerformed<?php echo $msgId; ?>" value="editAnswer" />
											<input type="hidden" name="functionToCall<?php echo $msgId; ?>" id="functionToCall<?php echo $msgId; ?>" value="<?php echo $functionToCall; ?>" />		
											<div style="padding-top:7px">
												<div class="float_L" style="width:65px;height:21px"><input type="Submit" value="" id="submitButton<?php echo $msgId; ?>" class="spirit_middle shik_submitButton" /></div>
												<div class="float_L txt_align_c" style="width:54px;height:21px;line-height:21px"><a href="javascript:void(0);" onClick="javascript:editResultOnQuestionPostedPage('close');hidereply_form('<?php echo $msgId; ?>','ForHiding');">Cancel</a></div>
												<div class="clear_L">&nbsp;</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						</form>
					</div>
					<!-- end of form -->
		</div>
	</div>
</div>
<!--End_Recently_Question-->
<div class="defaultAdd lineSpace_10">&nbsp;</div>

<?php
	$questionUrl = getSeoUrl($userRecentAnswersQestion['threadId'],'question',$userRecentAnswersQestion['msgTxt']);
	$this->load->view('messageBoard/shareThisQuestion',array('threadId'=>$userRecentAnswersQestion['threadId'],'msgTxt' => $userRecentAnswersQestion['msgTxt'],'urlForQuestion' => $questionUrl,'display' => 'none'));
?>
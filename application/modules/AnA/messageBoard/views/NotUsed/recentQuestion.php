<?php
	$userRecentQuestion = (isset($userQuestionAndAnswer[0]) && is_array($userQuestionAndAnswer[0]))?$userQuestionAndAnswer[0]:array();
	$questionCreationDate = makeRelativeTime($userRecentQuestion['creationDate']);
	$viewText = ($userRecentQuestion['viewCount'] <=1)?'<span style="color:#000">'.$userRecentQuestion['viewCount'].'</span>  View':'<span style="color:#000">'.$userRecentQuestion['viewCount'].'</span> Views';
	if($userRecentQuestion['answerCount'] == 0){
		$answerText = 'No answer';
	}else{	
		$answerText = ($userRecentQuestion['answerCount'] ==1)?$userRecentQuestion['answerCount'].' answer':$userRecentQuestion['answerCount'].' answers';
	}
	$msgId = $userRecentQuestion['msgId'];
	$threadId = $userRecentQuestion['threadId'];
	$urlForQuestion = $userRecentQuestion['url'];
	$url = site_url("messageBoard/MsgBoard/editQuestion");
	$mainAnsId = $userRecentQuestion['mainAnswerId'];
	$alertId = (is_array($WidgetStatus)) && ($WidgetStatus['result'] == 1)?$WidgetStatus['alert_id']:0;
 	$userProfile = site_url('getUserProfile').'/';
if(count($userRecentQuestion) > 0){
?>	
<div class="darkOrangeColor Fnt14" style="padding-bottom:4px"><b>Your Recently Posted Open Question</b></div>
	<div style="width:100%">
		<div class="shik_outerBorder" style="background:#eff7ff;padding-top:9px">
			<div class="marfull_LeftRight10">
				<!--Start_QuestionListing-->
				<div style="width:100%;display:block" id="shareQuestionFormContainer">
					<div class="shik_bgQuestionSign">
						<div style="line-height:17px" id="questionTextContainer"><?php echo $userRecentQuestion['msgTxt']; ?></div>
						<div class="grayColorFnt12" style="padding:10px 0;line-height:20px">Asked by <a href="<?php echo $userProfile.$validateuser[0]['displayname']; ?>"  target="_blank" class="Fnt12">you...</a> <span style="color:#000"><?php echo $questionCreationDate; ?></span>, <span style="color:#000"><?php echo $viewText; ?></span>, <a href="<?php echo $urlForQuestion; ?>" target="_blank" class="Fnt12"><?php echo $answerText; ?></a></div>
						<!--Start_share_this_question-->
						<div style="border:1px solid #c6e1fc;background:#FFF;padding:10px;width:70%">
							<div style="width:100%">
								<div><b>Share this Question</b></div>
						<?php
							$this->load->view('messageBoard/shareThisQuestion',array('threadId'=>$threadId,'msgTxt' => $userRecentQuestion['msgTxt'],'urlForQuestion' => $urlForQuestion,'display' => 'inline'));
						?>
							</div>
						</div>
						<!--End_share_this_question-->
						<!--Start_BottomLink-->
						<div class="txt_align_r shik_bottomLinksMargin"><a href="javascript:void(0);" onClick="javascript:operateOnDiscussion('<?php echo $threadId; ?>','deleteDiscussionTopic');"><span class="spirit_allIcons shik_deletedIcon">&nbsp;</span>Delete Question</a><a href="javascript:void(0);" onClick="javascript:showEditQuestionForm();"><span class="spirit_allIcons shik_editedIcon">&nbsp;</span>Edit Question</a></div>
						<!--End_BottomLink-->
					</div>
				</div>
				<!--End_QuestionListing-->

				<!--Start_EditQuestion-->
				<div style="width:100%;display:none;" id="editQuestionFormContainer">
					<?php
						echo $this->ajax->form_remote_tag( array('url'=>$url,'before' => 'if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$msgId.')}','success' => 'javascript:editQuestionSuccess(request.responseText);'));
					?>
					<div><textarea  name="questionText" id="questionTextBox"  onblur="enterEnabled=false;" onfocus="enterEnabled=true;" profanity="true" validate="validateStr" caption="Question" maxlength="300" minlength="2" required="true" style="width:99%;height:50px" onkeyup="textKey(this);" class="shik_bgTextArea"></textarea></div>
					<div class="grayColor Fnt11"><span id="questionTextBox_counter">0</span> out of 300 Character <div class="float_R">Make sure your question follows <a href='javascript:void(0);' onclick='return popitup("<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline");'>Community Guidelines</a>&nbsp;</div></div>
					<div class="row errorPlace">
						<div id="questionTextBox_error" class="errorMsg"></div>
					</div>
					<div style="padding:10px 0;margin-left:-4px"><input type="checkbox" name="setAlert" id="setAlert" value="on" checked="true" /> Send me an email if someone answers my question</div>
					<div class="Fnt11">Type in the character you see in the picture below:</div>
					<div style="margin-left:50px">
						<div style="padding-top:10px"><img src="/CaptchaControl/showCaptcha?width=100&height=30&characters=5&secvariable=secCodeForEditQuestion&randomkey=<?php echo rand(0,10000000000); ?>"  onabort="reloadCaptcha(this.id)" id="questionEditCaptcha" align="absmiddle"/>
						<input type="text" name="secCodeForEditQuestion" id="secCodeForEditQuestion" secvariable="seccodeForAskQuestion" size="5" validate="validateSecurityCode" maxlength="5" minlength="5" required="true" caption="Security Code" style="width:55px;margin-left:12px"/></div>
						<div class="row errorPlace">
							<div id="secCodeForEditQuestion_error" class="errorMsg"></div>
						</div>	
						<input type="hidden" name="alertId" value="<?php echo $alertId; ?>" />
						<input type="hidden" name="editQuestionId" value="<?php echo $threadId; ?>" />
							
						<div style="padding:10px 0">
							<div class="float_L" style="width:65px;height:22px"><div><input type="Submit" class="spirit_middle shik_updateButton" value="" /></div></div>
							<div class="float_L txt_align_c" style="width:54px;height:21px;line-height:22px"><a href="javascript:void(0);" onClick="javascript:hideEditQuestionForm();">Cancel</a></div>
							<div class="clear_L">&nbsp;</div>
						</div>
					</div>
					</form>
				</div>
			<!--End_EditQuestion-->
		</div>
	</div>
</div>
<?php } ?>

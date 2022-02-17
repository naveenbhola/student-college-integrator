<?php
	$topicUrl = site_url('messageBoard/MsgBoard/topicDetails').'/'.$topicId;
	$answerCountForQuestion = isset($main_message['msgCount'])?$main_message['msgCount']:0;
	$answerText = ($answerCountForQuestion <= 1)?' comment':' comments';
	$viewsCountForQuestion = isset($main_message['viewCount'])?$main_message['viewCount']:0;
	$viewsText = ($viewsCountForQuestion <= 1)?$viewsCountForQuestion.' view':$viewsCountForQuestion.' views';
	echo "<script language=\"javascript\"> "; 	
	echo "var commentCount  = ".$answerCountForQuestion.";";
	echo "var topicUrl  = '".$topicUrl."';";
	echo "</script> ";
	
	$mainTextBoxWidth = "width:930px;";
	if($_COOKIE['client']<=1024){
		$mainTextBoxWidth = "width:674px;";
	}
	
	if($_COOKIE['client']<=800){
		$mainTextBoxWidth = "width:450px;";
	}
	$isCmsUser =0;
	if((is_array($validateuser))&&(strcmp($validateuser[0]['usergroup'],'cms') == 0)){	
		$isCmsUser = 1;
	}
	$lnUserDisplayName = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';   
	$userProfile = site_url('getUserProfile').'/'; 
	$userImage =  isset($main_message['userImage'])?getSmallImage($main_message['userImage']):'';
	$threadId = $main_message['msgId']; 
	$url = site_url("messageBoard/MsgBoard/replyMsg");
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	$isAskQuestion = false;
	if($srcPage=='askQuestion'){
		$isAskQuestion = true;
	}
	if($userId == 0){	
		$isAskQuestion = false;
	}
	$isQuestionUser = false;
	if($userId == $main_message['userId']){
		$isQuestionUser = true;
	}	
?>
<!-- Star of best answer overlay -->
<div style="padding:50px;display:none;" id="chooseBestAnswerOverLay">
	<div>
		<?php 
			$urlForBestAns = site_url("messageBoard/MsgBoard/chooseBestAns");
			echo $this->ajax->form_remote_tag( array('url'=>$urlForBestAns ,'before' => 'beforeChooseBestAnswer();','success' => 'javascript:afterChooseBestAnswer(request.responseText);'));	
		?>
		<div class="errorMsg mar_full_10p" id="confirmMsgForBestAnsOverLay"></div>
		<div style="padding:0 10px">
		<div class="lineSpace_10p">&nbsp;</div>
		<div><input type="radio" name="close" id="closeDiscussion1" value="1" checked /> Yes, I have found what I was looking for. Close this question</div>
<div class="lineSpace_10p">&nbsp;</div>
		<div><input type="radio" name="close" id="closeDiscussion2" value="0" /> No, keep the question open for 3 more days. I want to receive more answers</div>
<div class="lineSpace_10p">&nbsp;</div>		
		<input type="hidden" id="msgIdForBestAnswer" name="msgId" value="-1" />
		<input type="hidden" id="threadIdForBestAnswer" name="threadId" value="-1" />
		<input type="hidden" id="commentUserIdForBestAnswer" name="commentUserId" value="-1" />
		
		<div align="center"><input type="Submit" value="Submit" id="chooseBestAnswerSubmit" class="submitGlobal" /> &nbsp;
	 	<input type="button" value="Cancel" class="cancelGlobal" onClick="javascript:hideOverlay();" /></div>
<div class="lineSpace_10p">&nbsp;</div>
		</div>
		</form>
	</div>
</div>
<!-- End of best answer overlay -->

		<!--Start_mainQuestion-->
	        	<div class="mainquestionBg">
        		<div style="padding-bottom:20px">
            		<div class="fontSize_16p bld">
						<?php 
							echo nl2br_Shiksha(insertWbr($main_message['msgTxt'],32)); 
							$userProfileLink = $userProfile.$main_message['displayname'];	
							$displayName = $main_message['displayname'];
							$displayNameLink = '<a href="'.$userProfileLink.'" class="fontSize_14p">'.$displayName.'</a>';					
							if($userId == $main_message['userId']){
								$displayNameLink = 'you';
							}
						?>
					</div>
					<div class="fontSize_14p" style="padding:10px 0 15px 0">
							Posted by <?php echo $displayNameLink; ?> <?php if(isset($main_message['creationDate'])): echo $main_message['creationDate']; endif;   ?> <?php echo $viewsText; ?>, <span id="commentholder"><?php echo $answerCountForQuestion.$answerText; ?></span></div>
					<?php 	if(($isCmsUser == 0) && (!$isQuestionUser) &&($closeDiscussion == 0)):		?>
                    <div class="answerNowShikshaBtnL" style="margin-bottom:10px"><input type="button" value="Comment now and Earn Shiksha Points"  onclick="reply_form(<?php echo $threadId; ?>);return false;" class="answerNowShikshaBtnR" /></div>
					<?php endif; ?>			
                    <!--Start_ReplyForm-->
	                <div class="formReplyBrder" style="display:none;" id="replyForm<?php echo $threadId;  ?>">
					<?php 	if(($isCmsUser == 0) && (!$isQuestionUser) &&($closeDiscussion == 0)):
							echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$threadId ,'before' => 'if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$threadId.')}','success' => 'javascript:addMainComment('.$threadId.',request.responseText,\'before\');'));	
					?>	
                    	<div class="bld" style="padding-bottom:5px"><span class="OrgangeFont">Reply to </span><?php echo $main_message['displayname']; ?></div>
                        <div><textarea name="replyText<?php echo $threadId; ?>" onkeyup="textKey(this)" class="textboxBorder mar_left_10p" id="replyText<?php echo $threadId; ?>" validate="validateStr" caption="Comment" maxlength="2000" minlength="2" required="true" rows="5" style="width:98%;height:150px"></textarea></div>
                        <div><span id="replyText<?php echo $threadId;  ?>_counter">0</span> out of 2000 character</div>
						<div class="errorPlace" style="display:block"><div class="errorMsg" id="replyText<?php echo $threadId; ?>_error"></div></div>
                        <div style="padding:10px 0 5px 0">Type in the characters you see in picture below:</div>
                        <div><img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5" onabort="reloadCaptcha(this.id)" onClick="reloadCaptcha(this.id)" id="secimg<?php echo $threadId; ?>" align="absmiddle" /> &nbsp; &nbsp;<input type="text" type="text" name="seccode<?php echo $threadId; ?>" id="seccode<?php echo $threadId; ?>" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="6" /></div>
						<div class="errorPlace" style="display:block"><div class="errorMsg" id="seccode<?php echo $threadId; ?>_error"></div></div>
						<input type="hidden" name="displayname<?php echo $threadId; ?>" value="<?php echo $main_message['displayname']; ?>" />
						<input type="hidden" name="threadid<?php echo $threadId; ?>" value="<?php echo $threadId; ?>" />
						<input type="hidden" name="fromOthers<?php echo $threadId; ?>" value="user" />
                        <div style="padding-top:10px"><input type="Submit" onClick = "checkcommentmem(this.form,'GROUPS_GROUPSDISCUSSION_MIDDLEPANEL_SUBMITCOMMENT','<?php echo $main_message['listingType']?>');return false;" value="Submit" class="submitGlobal" id="submitButton<?php echo $threadId; ?>" style="cursor:pointer;" /> &nbsp; 
							<input type="button" value="Cancel" class="cancelGlobal" onClick="hidereply_form('<?php echo $threadId; ?>','ForHiding');" style="cursor:pointer;" /></div>
					</form>
					<?php endif; ?>
                    </div>
                    <!--End_ReplyForm-->
                    <div class="showMessages" style="margin-top:10px;display:none;" id="confirmMsg<?php  echo $threadId; ?>"></div>
            	</div>
            </div>
			<!--End_Question_Block-->
			
            <div class="lineSpace_10">&nbsp;</div>
			
<?php 
	if(!$isAskQuestion): //The condition for the Posted question started here
?>
			<!--Pagination Place start here -->
			<div class="pagingID mar_full_10p lineSpace_18 txt_align_r" id="paginataionPlace1"></div>
			<!-- Pagination Place ends here -->	
			<!--Start_AnswerHeading-->
            <div class="OrgangeFont bld fontSize_14p" style="line-height:25px">Comment for <?php echo $main_message['displayname']; ?> <span class="blackFont">(<span id="answerCountHolderForQuestion"><?php echo $mainAnsCount; ?></span>)</span></div>
            <div class="dottedLine">&nbsp;</div>            
            <!--End_AnswerHeading-->

				<!--Start_Answer_Block-->
				<div>
					<div id="topicContainer">
						<div id="threadedCommentId">	
							<?php	
								$commentData['url'] = $url;
								$commentData['threadId'] = $threadId;	
								$commentData['isCmsUser'] = $isCmsUser;	
								$commentData['fromOthers'] = $main_message['listingType'];
								$commentData['maximumCommentAllowed'] = 4;
								$commentData['pageKeySuffixForDetail'] = 'GROUPS_GROUPSDISCUSSION_MIDDLEPANEL_';
								$this->load->view('messageBoard/topicPage',$commentData);  
							?>
						</div>	
					</div>
				</div>
				<!--End_Answer_Block-->

		<div class="lineSpace_10p">&nbsp;</div>
		<?php if(($isCmsUser == 0) && (!$isQuestionUser) &&($closeDiscussion == 0)): ?>
		<!--Start_ReplyForm-->
            <div style="line-height:1px;font-size:1px">&nbsp;</div>
            <div class="formReplyBrder" style="border:none">
				<?php $temp = 1;  
				echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$threadId ,'before' => 'if(validateFields(this) != true){return false;} else { disableReplyFormButton(\'\');}','success' => 'javascript:addMainComment('.$temp.',request.responseText,\'after\');')); 
				?>
                <div class="bld" style="padding-bottom:5px">Your Comment</div>
                <div>
						<textarea name="replyText" onkeyup="textKey(this)" class="textboxBorder mar_left_10p" id="replyText" validate="validateStr" caption="Comment" maxlength="2000" minlength="2" required="true" rows="5" style="width:95%;height:100px"></textarea>
				</div>
                <div><span id="replyText_counter">0</span> out of 2000 character</div>
                <div class="errorPlace" style="display:block"><div class="errorMsg" id="replyText_error"></div></div>
                <div style="padding:10px 0 5px 0">Type in the characters you see in picture below:</div>
                <div>
					<img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&secvariable=seccode&junk=<?php echo rand(0,100000000); ?>" id="secimg" onabort="reloadCaptcha(this.id,'seccode')" onClick="reloadCaptcha(this.id,'seccode')" align="absmiddle" />&nbsp;&nbsp;
					<input type="text" type="text" name="seccode" id="seccode" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="5" />
				</div>
                <div class="errorPlace" style="display:block"><div class="errorMsg" id="seccode_error"></div></div>
				<input type="hidden" name="displayname" value="<?php echo $main_message['displayname']; ?>" />
						<input type="hidden" name="threadid" value="<?php echo $threadId; ?>" />
						<input type="hidden" name="secCodeIndex" value="seccode" />
			      		<input type="hidden" name="appendThread" value="false" />
                        <input type="hidden" name="fromOthers" value="<?php echo $main_message['listingType']?>" />
                <div style="padding-top:10px">
                <input type="Submit" value="Submit" onClick = "checkcommentmem(this.form,'GROUPS_GROUPSDISCUSSION_MIDDLEPANEL_SUBMITCOMMENT','<?php echo $main_message['listingType']?>');return false;" id="submitButton" class="submitGlobal" /> &nbsp; 
				</div>
				</form>	
            </div>
            <!--Start_ReplyForm-->
<div class="dottedLine" style="padding:5px 0">&nbsp;</div>
				<?php 	endif; ?>
<div class="lineSpace_10">&nbsp;</div>
<?php 
	elseif($isQuestionUser && $isAskQuestion):
		$this->load->view('messageBoard/questionPosted');
	endif; //The condition for the Posted question ends here
	
 ?>
<?php	echo "<script>"; 
				echo "var COLLEGEID = '".$main_message['listingTypeId']."';";			
                            echo "</script>";?>

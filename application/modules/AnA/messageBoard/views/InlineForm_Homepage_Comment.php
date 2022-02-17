<?php 
$replyContext = 'addComment';
if(isset($userGroup)) {
	$detailPageUrl = isset($detailPageUrl)?$detailPageUrl:-1;
	$messageToShow = isset($messageToShow)?$messageToShow:'Write a comment...';
	$isWall = isset($wall)?true:false;
	$tHeight = (isset($tHeight))?$tHeight:'18px';

	$commenterDisplayNameString = (isset($commenterDisplayName)&&($commenterDisplayName!=''))?'Reply to <a href="/getUserProfile/'.$commenterDisplayName.'">'.$commenterDisplayName.'</a>':'';
	$commenterDisplayName = (isset($commenterDisplayName)&&($commenterDisplayName!=''))?$commenterDisplayName:'';
	$displayCommenterDisplayName = (isset($commenterDisplayName)&&($commenterDisplayName!=''))?'block':'none';
	$commentParentId = (isset($commentParentId)&&($commentParentId!=''))?$commentParentId:'';
	$submitButtonClass = (isset($commenterDisplayName)&&($commenterDisplayName!=''))?'orange-button':'orange-button';
	$valueSubmitButton = (isset($commenterDisplayName)&&($commenterDisplayName!=''))?'Reply':'Submit';
	$textHeight = 42;
	//In case of discussion/annoucement, increase the height of the comment textarea
	if($fromOthers=='discussion' || $fromOthers=='announcement' || $fromOthers=='review'){
	  $textHeight = 106;
	}
	switch($fromOthers)
	{
		case 'discussion':
					$commentTrackingPageKeyID=$dctrackingPageKeyId;
					break;
		case 'announcement':
					$commentTrackingPageKeyID=$actrackingPageKeyId;
					break;
		case 'user':
					$commentTrackingPageKeyID=$ansctrackingPageKeyId;
					break;
		case 'blog':
					$commentTrackingPageKeyID=$rtrackingPageKeyId;
					break;
		default:
				$commentTrackingPageKeyID='NULL';
	}

// Code to compensate impact on Listing AnA.This $check variable will be used later to differentiate.
$requestUrl = (string)$_SERVER['REQUEST_URI'];
$httpRefereral = (string)$_SERVER['HTTP_REFERER'];
$check = false;
$requestCheck = strpos($requestUrl,"listinganatab");
$check = $requestCheck;
if($requestCheck == false){
$requestCheck = strpos($requestUrl,"getOlderWallDataForListings");
$httpCheck = strpos($httpRefereral,"listinganatab");
$check = $requestCheck&&$httpCheck;
}
if($requestCheck == false){
$requestCheck = strpos($requestUrl,"getCommentSection");
$httpCheck = strpos($httpRefereral,"listinganatab");
$check = $requestCheck&&$httpCheck;
}
if($requestCheck == false){
$requestCheck = strpos($requestUrl,"getAnswerForm");
$httpCheck = strpos($httpRefereral,"listinganatab");
$check = $requestCheck&&$httpCheck;
}

$showMention = isset($showMention)?$showMention:false;
?>
<!--Start_Inline_Comment-->
<div class="fbkBx" id="replyCommentForm<?php echo $msgId;?>" <?php if($isWall) echo 'style="width:507px;"'; else if($fromOthers=="blog") echo 'style="width:490px;"';?>>
	<?php
	$url = site_url("messageBoard/MsgBoard/replyMsg");
	echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$msgId,'before' => 'if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$msgId.')}','success' => 'javascript:newDiscussionForm('.$msgId.',request.responseText,'.$mainAnsId.',\''.$functionToCall.'\',\''.$check.'\',\''.$replyContext.'\'); '));
	?>
	<div class="subContainerAnswer">
		<div id="replyCommentText<?php echo $msgId; ?>_reputationVal" style="display:none"><?php if(isset($loginreputationPoints)) echo $loginreputationPoints;else echo $reputationPoints;?></div>
		<div style='display:block;'>

		  <textarea name="replyCommentText<?php echo $msgId; ?>_hide" class="ftBx" id="replyCommentText<?php echo $msgId; ?>_hide" style="height:<?php echo $tHeight;?>;width:98%;" onclick="try{ showAnswerCommentForm('<?php echo $msgId; ?>','<?php echo $commentTrackingPageKeyID;?>'); }catch (e){}" validateSingleChar='true'><?php echo $messageToShow;?></textarea>
		</div>

		<!--Start_For Show and Hide-->
		<div style="display:none;overflow:hidden;" id="hiddenCommentFormPart<?php echo $msgId; ?>">
			<div class="imgBx">
				<div class="wdh100">
				  <img id="userProfileImageForComment<?php echo $msgId;?>" align='left' valign='top' src="<?php echo ($userImageURL != '')?getTinyImage($userImageURL):getTinyImage('/public/images/photoNotAvailable.gif');?>" />
				</div>
			</div>
			<div class="cntBx">
				<div class="float_L wdh100">
					<div class="wdh100 float_L">
						<div id="replyToUser<?php echo $msgId; ?>" style="display:<?php echo $displayCommenterDisplayName;?>" class="Fnt11"><?php echo $commenterDisplayNameString;?></div>
						<?php if($showMention){ ?>
							<div><textarea name="replyCommentText<?php echo $msgId; ?>" onkeyup="textKey(this); checkForNameMention(event,this,'replyCommentText<?php echo $msgId; ?>','true');" class="ftxArea" id="replyCommentText<?php echo $msgId; ?>" validate="validateStr" caption="Comment" maxlength="2500" minlength="3" required="true" rows="1" style="height:15px; " validateSingleChar='true'></textarea></div>
						<?php }else{ ?>
							<div><textarea name="replyCommentText<?php echo $msgId; ?>" onkeyup="textKey(this);" class="ftxArea" id="replyCommentText<?php echo $msgId; ?>" validate="validateStr" caption="Comment" maxlength="2500" minlength="3" required="true" rows="1" style="height:<?php echo $textHeight;?>px; " validateSingleChar='true'></textarea></div>
						<?php } ?>
						<div class="Fnt10 fcdGya"><span id="replyCommentText<?php echo $msgId; ?>_counter">0</span> out of 2500 characters</div>
						<div class="errorPlace Fnt11" style="display:none;"><div class="errorMsg" id="replyCommentText<?php echo $msgId; ?>_error"></div></div>
						<div class="errorMsg" style="display:none;" id="replyCommentText<?php echo $msgId; ?>_reputationError" name="replyCommentText<?php echo $msgId; ?>_reputationError">&nbsp;</div>
						<div class="lineSpace_1">&nbsp;</div>
						<!-- Code for Facebook integration -->
						<?php /*if($check == false){?>
                                                <?php $isChecked = 'checked';
						      if(isset($_COOKIE['facebookCheck']) &&  $_COOKIE['facebookCheck'] == 'no'){
							$isChecked = ''; }
						?>
						<div class="Fnt11">
						    <input id="facebookCheck<?php echo $msgId; ?>" name="facebookCheck<?php echo $msgId; ?>" value="true" type="checkbox" <?php echo $isChecked; ?> style="position:relative;top:2px;left:-4px" onClick="setFacebookCheck('<?php echo $msgId; ?>');"/>Also post this on my Facebook wall
						</div>
                                                <?php }else{?>
                                                <div class="Fnt11">
						    <input id="facebookCheck<?php echo $msgId; ?>" name="facebookCheck<?php echo $msgId; ?>" value="false" type="checkbox" <?php echo $isChecked; ?> style="display:none;position:relative;top:2px;left:-4px" onClick="setFacebookCheck('<?php echo $msgId; ?>');"/>
						</div>
                                                <?php }*/?>
						<!-- Code End for Facebook integration -->
						<div class="float_L lineSpace_22 Fnt11 fcdGya"><?php echo "Kindly follow our "; ?><a href="javascript:void(0);" onclick='return popitup("<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline");'>Community Guidelines</a>
							<span style="margin-left:5px;margin-top:2px;display:none;" id="loaderDiv"><img src="//<?=IMGURL;?>/public/images/working.gif" align="absmiddle"/></span>
						</div>
						<div class="float_R pr10 lineSpace_22">
							<input type="Submit" class="<?php echo $submitButtonClass; ?>" value="<?php echo $valueSubmitButton; ?>" id="submitButton<?php echo $msgId; ?>"/>&nbsp;&nbsp;
							<a href="javascript:void(0);" onClick="hideAnswerCommentForm('<?php echo $msgId; ?>',true);">Cancel</a>
						</div>
						<div class="clear_B">&nbsp;</div>
					</div>
				</div>
			</div>
			<div class="errorPlace" style="display:block"><div class="errorMsg" id="seccode<?php echo $msgId; ?>_error"></div></div>
			<input type="hidden" name="displayname<?php echo $msgId; ?>" id="displayname<?php echo $msgId; ?>" value="<?php echo $displayname;  ?>" />
			<input type="hidden" name="sortFlag<?php echo $msgId; ?>" id="sortFlag<?php echo $msgId; ?>" value="<?php echo $sortFlag;?>" />
			<input type="hidden" name="threadid<?php echo $msgId; ?>" id="threadid<?php echo $msgId; ?>" value="<?php echo $threadId; ?>" />
			<input type="hidden" name="dotCount<?php echo $msgId; ?>" id="dotCount<?php echo $msgId; ?>" value="<?php echo $dotCount; ?>" />
			<input type="hidden" name="fromOthers<?php echo $msgId; ?>" id="fromOthers<?php echo $msgId; ?>" value="<?php echo $fromOthers; ?>" />
			<input type="hidden" name="mainAnsId<?php echo $msgId; ?>" id="mainAnsId<?php echo $msgId; ?>" value="<?php echo $mainAnsId; ?>" />
			<input type="hidden" name="displaynameId<?php echo $msgId; ?>" id="displaynameId<?php echo $msgId; ?>" value="<?php echo $userId; ?>" />
			<input type="hidden" name="actionPerformed<?php echo $msgId; ?>" id="actionPerformed<?php echo $msgId; ?>" value="addComment" />
			<input type="hidden" name="functionToCall<?php echo $msgId; ?>" id="functionToCall<?php echo $msgId; ?>" value="<?php echo $functionToCall; ?>" />
			<input type="hidden" name="userProfileImage<?php echo $msgId; ?>" id="userProfileImage" value="<?php echo ($userImageURL != '')?$userImageURL:'/public/images/photoNotAvailable.gif';?>" />
			<input type="hidden" name="immediateParentId<?php echo $msgId; ?>" id="immediateParentId<?php echo $msgId; ?>" value="<?php echo $commentParentId;?>" />
			<input type="hidden" name="immediateParentName<?php echo $msgId; ?>" id="immediateParentName<?php echo $msgId; ?>" value="<?php echo $commenterDisplayName;?>" />
			<input type="hidden" name="isWall<?php echo $msgId; ?>" id="isWall<?php echo $msgId; ?>" value="<?php echo $isWall;?>" />
			<input type="hidden" name="mentionedUsers<?php echo $msgId; ?>" value="" id="mentionedUsers<?php echo $msgId; ?>"/>
			<input type='hidden' name='tracking_keyid' id="tracking_keyid<?php echo $msgId; ?>" value="">

		</div>
	</div>
	</form>
</div>
<!--End_Inline_Comment-->
<?php } ?>

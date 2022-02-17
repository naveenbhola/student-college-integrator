<?php
if($commentsUserData['userName'] != '')
{
	$userName = $commentsUserData['userName'];
	$imageUrl = $commentsUserData['imageUrl'];
	$admittedStr = $commentsUserData['admittedStr'];
	$profilePageUrl = $commentsUserData['profilePageUrl'];
	$linkOpen = '<a href="'.$profilePageUrl.'">';
	$imgLinkOpen = '<a href="'.$profilePageUrl.'" class="pp-img">';
	$linkClose = $imgLinkClose = '</a>';
}else{
	$linkOpen = '';
	$linkClose = '';
	$imgLinkOpen = '<a class="pp-img">';
	$imgLinkClose = '</a>';
	$imageUrl = IMGURL_SECURE.'/public/images/studyAbroadCounsellorPage/profileDefaultNew1_s.jpg';
	$admittedStr='';
}
if($type == 'reply'){
	?>
		<div class="comment-reply clearwidth" id="reply_<?php echo $commentId;?>">
			<div class="clearfix head-info">
				<?php echo $imgLinkOpen; ?>
					<img src="<?php echo $imageUrl; ?>">
				<?php echo $imgLinkClose; ?>
				<div class="flLt text-head">
					<div class="clearfix">
						<?php echo $linkOpen; ?><span class="flLt bold" style="color: #008489;"><?php echo $userName; ?></span><?php echo $linkClose; ?>
						<span class="flRt commnt-date"><?php echo date("d M'y, h:i A",strtotime(date('y-m-d  h:i:s')));?></span>
					</div>
					<span class="lbl-aspirent"><?php echo $admittedStr; ?></span>
				</div>
			</div>
			<p class="commentText"><?php echo formatQNAforQuestionDetailPage($commentText);?></p>
			<?php if($deletionPermissionArray[$commentId]['userEligibleForCommentDeletion'] == 1){ ?>
			<p><a href="javascript:void(0);" onclick="deleteComment('reply','<?php echo $replyData['commentId']; ?>','<?php echo $commentData['data']['contentId'];?>')" class="flRt font-11">Delete Reply</a></p>
			<?php } ?>

		</div>

<?php }else{?>

<li class="clearwidth" id="comment_<?php echo $commentId;?>" >
	<div class="clearfix head-info">
		<?php echo $imgLinkOpen; ?><img src="<?php echo $imageUrl; ?>"><?php echo $imgLinkClose; ?>
		<div class="flLt text-head">
			<div class="clearfix">
				<?php echo $linkOpen; ?><span class="flLt bold" style="color: #008489;"><?php echo $userName;?></span><?php echo $linkClose; ?>
				<span class="flRt commnt-date"><?php echo date("d M'y, h:i A",strtotime(date('y-m-d  h:i:s'))); ?></span>
			</div>
			<span class="lbl-aspirent"><?php echo $admittedStr; ?></span>
		</div>
	</div>
	<p class="commentText"><?php echo formatQNAforQuestionDetailPage($commentText);?></p>
	<p>

		<span class="flLt bold" style="color: #008489;">Reply to <?php echo $userName;?></span>
	</p>

	<textarea class="article-txtarea" name="textarea" id="replyBox_<?php echo $commentId; ?>" style="display:none; margin-top:10px;" onblur="$j('#replyBox_<?php echo $commentId; ?>').val('Add your reply');" onfocus="if($j('#replyBox_<?php echo $commentId; ?>').val() == 'Add your reply') $j('#replyBox_<?php echo $commentId; ?>').val('');" required="true" minlength="3" maxlength="2500" caption="reply" validate="validateStr" value="Add your reply">Add your reply</textarea>
	<div id="replyBox_<?php echo $commentId; ?>_error" class="errorMsg" style="margin-top : 3px;display: none;";></div>

	<div class="regstr-comment-box flRt" id="replyButton_<?php echo $commentId;?>" style="display:none">
		<a href="javascript:void(0);" onclick="$j('#replyBox_<?php echo $commentId; ?>_error').hide();   $j('#replyBox_<?php echo $commentId; ?>').hide();$j('#replyButton_<?php echo $commentId; ?>').hide();$j('#reply_user_<?php echo $commentId; ?>').show(); $j('#replyBox_<?php echo $commentId; ?>').val('Add your reply')">Cancel</a>
		<button  id="replySubmit_<?php echo $commentId; ?> " onclick="comment_type= 'reply'; comment_parent = '<?php echo $commentId;?>';   $j('#replyBox_<?php echo $commentId; ?>').val('');  if(validateComment('reply','<?php echo $commentId; ?>')) { if(checkUserStatus() == 0) {addUserValues(); openCommentOverlay('<?php echo $commentId; ?>'); }else {  addComment('reply','<?php echo $commentId; ?>');}}else return false; " class="button-style sbmt-btn">Submit</button>
	</div>

</li>
<?php } ?>

<?php  //this comment section is common to both article and guide pages
	$contentId = '';
	foreach ($comments as $commentId => $commentData) {
		$contentId = $commentData['data']['contentId'];
		break;
	}

	if(empty($contentId)) {
		$contentId =  $content_id;
	}
	$cookieStrArray = array();
	$name 	= '';
	$email 	= '';
	$userid = '';

	if($userStatus != 'false') {
		$cookieStr =  $userStatus[0]['cookiestr'];
		$cookieStrArray = explode('|',$cookieStr);
		$name = $userStatus[0]['firstname'].' '.$userStatus[0]['lastname'];
		$email = $cookieStrArray[0];
		$userid =  $userStatus[0]['userid'];
	}else if(isset($_COOKIE['sacontent_userName'])) {
		$name = $_COOKIE['sacontent_userName'];
		$email = $_COOKIE['sacontent_userEmail'];
	}
?>
<script> var spamKeywordsList = JSON.parse('<?php echo $spamKeywordsList;?>'); </script>
 <input name="nameVal" id="nameVal" type="hidden" value="<?php echo $name;?>" />
 <input name="emailVal" id="emailVal" type="hidden" value="<?php echo $email;?>" />
 <input name="userIdval" id="userIdVal" type="hidden" value="<?php echo $userid;?>" />
 <input name="contentId" id="contentId" type="hidden" value="<?php echo $contentId;?>" />

<?php $this->load->view('/blogs/saContent/userAddForm');?>
<div class="widget-wrap commentSectionWrap clearwidth" style="margin-top:20px;">
	<a id="comment_section_start" name="comment_section_start"></a>
	<h2><i id = "commentSection"class="article-sprite big-comment-icon"></i><?php echo $content['data']['commentCount'];?> <?php if($content['data']['commentCount'] > 1)echo 'Comments'; else echo 'Comment'?></h2>
	<div class="comment-section clearwidth">
		<p>Participate in discussion, write your comment</p>
		<textarea class="article-txtarea" name="textarea" id="commentBox_<?php echo $contentId; ?>"
			onblur=" if($j('#commentBox_<?php echo $contentId;?>').val() == '') 			{ $j('#commentBox_<?php echo $contentId;?>').val('Add your comment');}"
			onfocus="if($j('#commentBox_<?php echo $contentId;?>').val() == 'Add your comment')     { $j('#commentBox_<?php echo $contentId;?>').val('');}"
			required="true" minlength="3" maxlength="300" caption="comment" validate="validateStr" value="Add your comment">Add your comment</textarea>
		<div id="commentBox_<?php echo $contentId; ?>_error" class="errorMsg" style="margin-top : 3px;display: none;";></div>
		<div class="regstr-comment-box flRt">
		<button	id="commentButton_<?php echo $contentId;?>" onclick="comment_type= 'comment'; comment_parent = '<?php echo $contentId;?>';
		postComment('comment','<?php echo $contentId;?>')
		studyAbroadTrackEventByGA('ABROAD_<?php echo strtoupper($content['data']['type']);?>_PAGE', 'submitYourComment')"
		class="button-style sbmt-btn">Submit</button>
	</div>
	<?php
		$data = array();
		$data['email'] = $email;
		$data['userStatus'] = $userStatus;
	?>
	<div><?php $style = ($totalComments == 0 )?'display:none;':'';?>
		<div class="comment-box clearwidth" id="comment_box_main" style="<?php echo $style;?>">
			<ul id="comment_main_<?php echo $contentId; ?>">
				<?php $this->load->view('/blogs/saContent/commentsInner',$data);?>
			</ul>
		</div>
		<?php if($totalComments > 50):?>
		<div class="clearwidth">
			<a href="javascript:void(0)" onclick="loadMoreComments()" class="load-more clear-width" id="load_more">Show more comments</a>
			<span class="load-more clear-width" id="load_more_span" style="display:none;">No more comments to show</span >
		</div><?php endif;?>
	</div>
 </div>
</div>
<script>
var totalComments = '<?php echo $totalComments;?>'
</script>

<?php
$userImageURLDisplay = isset($validateuser['avtarurl'])?$validateuser['avtarurl']:'/public/images/photoNotAvailable_v1.gif';
$displayName = isset($validateuser['displayname'])?$validateuser['displayname']:'';
$userGroup = isset($validateuser['usergroup'])?$validateuser['usergroup']:'normal';
$userGroup = ($userGroup == 'cms') ? 'normal' : $userGroup;
$replyFormData['userImageURLDisplay'] = $userImageURLDisplay;
$replyFormData['displayName'] = $displayName;
?>

<?php if(!(isset($ajaxCall))){?>

			<div class="show-option">
				<div>
					<div class="topic_titleblock">Discussion Board
					  <strong id="showCount" <?php if($commentCountForTopic == 0){?> style="display: none" <?php }?> >(Showing 1-<span id="commentCount_total"><?php echo count($topic_messages);?></span> of total <?php echo "<span id='commentCountHolder'>".$commentCountForTopic."</span> ";if($commentCountForTopic>1) echo "comments";else echo "<span id='cmttxt_s'>comment</span>";?>)</strong> </div>
				</div>
			</div>

			<!-- comment box -->
			<?php if((!(isset($ajaxCall)))&&($closeDiscussion==0)&&($userGroup!='cms')){
				$dataArray = array('userImageURL'=>$userImageURLDisplay,'userGroup' =>$userGroup,'threadId' =>$threadId,'detailPageUrl' =>'','placeholder'=>'Write a new comment...','trackingPageKeyId'=>$commentTrackingKey);
			  	$inlineCommentForm = $this->load->view('mAnA5/InlineForm_Entity_Comment',$dataArray,true);
			  	echo $inlineCommentForm;
			}?>
			<!-- comment box end -->
	
<?php }

if(count($topic_messages)>0){ 
	$userProfile = site_url('getUserProfile').'/';?>
	
	<div id="commentContainer">
		<?php $this->load->view('mAnA5/innerCommentReplyThread',$replyFormData);?>
	</div>

	<?php if(!(isset($ajaxCall))){?>
	<a id="commentMore" data-callType="comment" <?php if($commentCountForTopic > $commentLimit){?> class="load-cmnts" 	<?php }else{?> class="load-cmnts" style="display:none" <?php }?>>Load More Comments<i class="load-cmntsico"></i></a>
	<?php }?>

<?php }else{ ?>
	<!-- Div Start to display newly added comments -->
	<div id="commentContainer"></div>
	<a id="commentMore" data-callType="comment" class="load-cmnts" style="display:none">Load More Comments<i class="load-cmntsico"></i></a>
	<!-- Div End to display newly added comments -->
<?php } ?>

<?php if(!(isset($ajaxCall))){ ?>
<input type="hidden" id="commentLimit" value="<?php echo $commentLimit;?>"/>
<input type="hidden" id="totalComment" value="<?php echo $commentCountForTopic;?>"/>
<input type="hidden" id="topicId" value="<?php echo $threadId;?>"/>
<input type="hidden" id="fromPage" value="<?php echo $page;?>"/>
<input type="hidden" id="ctKeyId" value="<?php echo $commentTrackingKey;?>"/>
<input type="hidden" id="rtKeyId" value="<?php echo $replyTrackingKey;?>"/>
<?php } ?>
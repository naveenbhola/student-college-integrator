                        		<?php $count = 0 ;?>
                        		<?php foreach ($comments as $commentId => $commentData) :?>
                        		<?php $count++;?>
                        		<?php $class = ($count == count($comments)) ? 'last':'';?>
	                            	<li class="clearwidth <?php echo $class;?>" id="comment_<?php echo $commentId;?>" >
	                                	<p class="clearfix">
	                                    	<span class="flLt bold" style="color: #008489;"><?php echo $commentData['data']['userName'];?></span>
	                                        <span class="flRt commnt-date"><?php echo date("d M'y",strtotime($commentData['data']['commentTime']));?>, <?php echo date("h:i A",strtotime($commentData['data']['commentTime']));?></span>
	                                    </p>
	                                    <p><?php echo formatQNAforQuestionDetailPage($commentData['data']['commentText']);?></p>
	                                    <p id="reply_user_<?php echo $commentData['data']['commentId']; ?>">
	                                    	<a href="javascript:void(0);" onclick="$j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').show();$j('#replyButton_<?php echo $commentData['data']['commentId']; ?>').show();$j('#reply_user_<?php echo $commentData['data']['commentId']; ?>').hide()" class="flLt font-14">Reply to <?php echo $commentData['data']['userName']; ?></a>
	                                    	<?php if($deletionPermissionArray[$commentId]['userEligibleForCommentDeletion']==1):?>
	                                    	<a href="javascript:void(0);" onclick="deleteComment('comment','<?php echo $commentData['data']['commentId']; ?>','<?php echo $commentData['data']['contentId'];?>')" class="flRt font-14">Delete Comment</a>
	                                    	<?php endif;?>
	                                    </p>

				                       	<textarea class="article-txtarea" name="textarea" id="replyBox_<?php echo $commentData['data']['commentId']; ?>" style="display:none; margin-top:10px;" onblur="if( $j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').val() == '')  $j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').val('Add your reply');" onfocus="if($j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').val() == 'Add your reply') $j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').val('');" required="true" minlength="3" maxlength="2500" caption="reply" validate="validateStr" value="Add your reply" >Add your reply</textarea>
				                        <div id="replyBox_<?php echo $commentData['data']['commentId']; ?>_error" class="errorMsg" style="margin-top : 3px;display: none;"></div>

				                        <div class="regstr-comment-box flRt" id="replyButton_<?php echo $commentData['data']['commentId'];?>" style="display:none">
				                        	<a href="javascript:void(0);" onclick="$j('#replyBox_<?php echo $commentData['data']['commentId']; ?>_error').hide();   $j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').hide();$j('#replyButton_<?php echo $commentData['data']['commentId']; ?>').hide();$j('#reply_user_<?php echo $commentData['data']['commentId']; ?>').show(); $j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').val('Add your reply')">Cancel</a>
					                        <button id="replySubmit_<?php echo $commentData['data']['commentId']; ?>" onclick="comment_type= 'reply'; comment_parent = '<?php echo $commentData['data']['commentId'];?>';  $j('#replyBoxaa_<?php echo $commentData['data']['commentId']; ?>').html('');  if(validateComment('reply','<?php echo $commentData['data']['commentId']; ?>')) { if(checkUserStatus() == 0) {addUserValues(); openCommentOverlay('<?php echo $commentData['data']['commentId']; ?>'); }else {  addComment('reply','<?php echo $commentData['data']['commentId']; ?>');}}else return false; " class="button-style sbmt-btn">Submit</button>
				                        </div>

				                        <?php $data = array();
				                        	   $data['type'] = 'reply';
				                        	   $data['parent_id'] = $commentData['data']['commentId'];;
				                        ?>

				                        <?php if(count($commentData['replies']) > 5):?>
										<div id="viewReplies_<?php echo $commentData['data']['commentId'];?>" style="width:490px;margin-top:50px;" class="fbkBx">
											<a class="fbxVw" onclick="showReplies('<?php echo $commentData['data']['commentId']; ?>');" href="javascript:void(0)">View All <span id=""><?php echo count($commentData['replies']);?></span> Replies</a>
										</div>
										<?php endif;?>
				                        <div id="reply_main_<?php echo $commentData['data']['commentId']; ?>">
	                                    <?php foreach ($commentData['replies'] as $index => $replyData):?>
	                                    	<?php $style = ($index > 4)?'display:none':''; ?>
											<div class="comment-reply clearwidth" style="<?php echo $style;?>" id="reply_<?php echo $replyData['commentId'];?>">
	                                           	<p class="clearfix">


	                                    	<span class="flLt bold" style="color: #008489;"><?php echo $replyData['userName'];?></span>
	                                            <span class="flRt commnt-date"><?php echo date("d M'y",strtotime($replyData['commentTime']));?>, <?php echo date("h:i A",strtotime($replyData['commentTime']));?></span>
	                                                </p>
		                                        <p><?php echo formatQNAforQuestionDetailPage($replyData['commentText']);?></p>
		                                        <?php if($deletionPermissionArray[$replyData['commentId']]['userEligibleForCommentDeletion']==1):?>
		                                        <p><a href="javascript:void(0);" onclick="deleteComment('reply','<?php echo $replyData['commentId']; ?>','<?php echo $commentData['data']['contentId'];?>')" class="flRt font-14">Delete Reply</a></p>
		                                        <?php endif;?>
	                                    	</div>
	                                    <?php endforeach;?>
	                                    </div>

	                                </li>
                        		<?php endforeach;?>

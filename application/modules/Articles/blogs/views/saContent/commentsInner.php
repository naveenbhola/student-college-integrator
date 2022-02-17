								<?php //echo "WOOSH YOU GOT SERVED";_p($commentsUserData);
								$count = 0 ;?>
                        		<?php foreach ($comments as $commentId => $commentData) :?>
								<?php
									if($commentData['data']['userId']>0)
									{
										$userName = $commentsUserData[$commentData['data']['userId']]['userName'];
										$imageUrl = $commentsUserData[$commentData['data']['userId']]['imageUrl'];
										$admittedStr = $commentsUserData[$commentData['data']['userId']]['admittedStr'];
										$profilePageUrl = $commentsUserData[$commentData['data']['userId']]['profilePageUrl'];
										$linkOpen = '<a href="'.$profilePageUrl.'">';
										$imgLinkOpen = '<a href="'.$profilePageUrl.'" class="pp-img">';
										$linkClose = $imgLinkClose = '</a>';
									}else{
										$linkOpen = '';
										$linkClose = '';
										$imgLinkOpen = '<a class="pp-img">';
										$imgLinkClose = '</a>';
										$userName = $commentData['data']['userName'];
										$imageUrl = IMGURL_SECURE.'/public/images/studyAbroadCounsellorPage/profileDefaultNew1_s.jpg';
										$admittedStr='';
									}
									$count++;
								?>
                        		<?php $class = ($count == count($comments)) ? 'last':'';?>
									<li class="clearwidth <?php echo $class;?>" id="comment_<?php echo $commentId;?>" >
									<div class="clearfix head-info">
										<?php echo $imgLinkOpen; ?><img src="<?php echo $imageUrl; ?>"><?php echo $imgLinkClose; ?>
										<div class="flLt text-head">
											<div class="clearfix">
												<?php echo $linkOpen; ?><span class="flLt bold" style="color: #008489;"><?php echo $userName;?></span><?php echo $linkClose; ?>
												<span class="flRt commnt-date"><?php echo date("d M'y",strtotime($commentData['data']['commentTime']));?>, <?php echo date("h:i A",strtotime($commentData['data']['commentTime']));?></span>
											</div>
											<span class="lbl-aspirent"><?php echo $admittedStr; ?></span>
										</div>
									</div>
	                                    <p class="commentText"><?php echo formatQNAforQuestionDetailPage($commentData['data']['commentText']);?></p>
	                                    <p id="reply_user_<?php echo $commentData['data']['commentId']; ?>">
	                                    	<a href="javascript:void(0);" onclick="$j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').show();$j('#replyButton_<?php echo $commentData['data']['commentId']; ?>').show();$j('#reply_user_<?php echo $commentData['data']['commentId']; ?>').hide()" class="flLt font-14">Reply to <?php echo $userName ; ?></a>
	                                    	<?php if($deletionPermissionArray[$commentId]['userEligibleForCommentDeletion']==1):?>
	                                    	<a href="javascript:void(0);" onclick="deleteComment('comment','<?php echo $commentData['data']['commentId']; ?>','<?php echo $commentData['data']['contentId'];?>')" class="flRt font-11">Delete Comment</a>
	                                    	<?php endif;?>
	                                    </p>

				                       	<textarea class="article-txtarea" name="textarea" id="replyBox_<?php echo $commentData['data']['commentId']; ?>" style="display:none; margin-top:10px;" onblur="if( $j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').val() == '')  $j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').val('Add your reply');" onfocus="if($j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').val() == 'Add your reply') $j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').val('');" required="true" minlength="3" maxlength="2500" caption="reply" validate="validateStr" value="Add your reply" >Add your reply</textarea>
				                        <div id="replyBox_<?php echo $commentData['data']['commentId']; ?>_error" class="errorMsg" style="margin-top : 3px;display: none;"></div>

				                        <div class="regstr-comment-box flRt" id="replyButton_<?php echo $commentData['data']['commentId'];?>" style="display:none">
				                        	<a href="javascript:void(0);" onclick="$j('#replyBox_<?php echo $commentData['data']['commentId']; ?>_error').hide();   $j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').hide();$j('#replyButton_<?php echo $commentData['data']['commentId']; ?>').hide();$j('#reply_user_<?php echo $commentData['data']['commentId']; ?>').show(); $j('#replyBox_<?php echo $commentData['data']['commentId']; ?>').val('Add your reply')">Cancel</a>
					                        <button id="replySubmit_<?php echo $commentData['data']['commentId']; ?>" onclick="comment_type= 'reply'; comment_parent = '<?php echo $commentData['data']['commentId'];?>';  $j('#replyBoxaa_<?php echo $commentData['data']['commentId']; ?>').html(''); postComment('reply',<?php echo $commentData['data']['commentId']; ?>); " class="button-style sbmt-btn">Submit</button>
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
											<?php
											if($replyData['userId']>0)
											{
												$userName = $commentsUserData[$replyData['userId']]['userName'];
												$imageUrl = $commentsUserData[$replyData['userId']]['imageUrl'];
												$admittedStr = $commentsUserData[$replyData['userId']]['admittedStr'];
												$profilePageUrl = $commentsUserData[$replyData['userId']]['profilePageUrl'];
												$linkOpen = '<a href="'.$profilePageUrl.'">';
												$imgLinkOpen = '<a href="'.$profilePageUrl.'" class="pp-img">';
												$linkClose = $imgLinkClose = '</a>';
											}else{
												$linkOpen = '';
												$linkClose = '';
												$imgLinkOpen = '<a class="pp-img">';
												$imgLinkClose = '</a>';
												$userName = $replyData['userName'];
												$imageUrl = IMGURL_SECURE.'/public/images/studyAbroadCounsellorPage/profileDefaultNew1_s.jpg';
												$admittedStr='';
											}
											$style = ($index > 4)?'display:none':'';
											?>
											<div class="comment-reply clearwidth" style="<?php echo $style;?>" id="reply_<?php echo $replyData['commentId'];?>">
	                                           	<div class="clearfix head-info">
												   	<?php echo $imgLinkOpen; ?>
													   <img src="<?php echo $imageUrl; ?>">
													<?php echo $imgLinkClose; ?>
													<div class="flLt text-head">
														<div class="clearfix">
															<?php echo $linkOpen; ?><span class="flLt bold" style="color: #008489;"><?php echo $userName; ?></span><?php echo $linkClose; ?>
															<span class="flRt commnt-date"><?php echo date("d M'y, h:i A",strtotime($replyData['commentTime']));?></span>
														</div>
														<span class="lbl-aspirent"><?php echo $admittedStr; ?></span>
													</div>
	                                            </div>
		                                        <p class="commentText"><?php echo formatQNAforQuestionDetailPage($replyData['commentText']);?></p>
		                                        <?php if($deletionPermissionArray[$replyData['commentId']]['userEligibleForCommentDeletion']==1):?>
		                                        <p><a href="javascript:void(0);" onclick="deleteComment('reply','<?php echo $replyData['commentId']; ?>','<?php echo $commentData['data']['contentId'];?>')" class="flRt font-11">Delete Reply</a></p>
		                                        <?php endif;?>
	                                    	</div>
	                                    <?php endforeach;?>
	                                    </div>

	                                </li>
                        		<?php endforeach;?>

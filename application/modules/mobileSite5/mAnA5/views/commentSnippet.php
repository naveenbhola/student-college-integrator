<div class="comment-wrapp" id="cmtBox<?php echo $msgId;?>">
    <div class="comment-details">    
		<div class="fbkBx w6 flLt wdh100">
			<div>
				<div class="flLt wdh100">
					
					<div class="mobile-flex">
						<div class="imgBx"> 
							<img id="userProfileImageForComment<?php echo $msgId.rand(0, 10000);?>" src="<?php echo getSmallImage($userProfileImage);?>" style="cursor:pointer;" onClick="window.location=('<?php echo site_url('getUserProfile').'/'.$displayName; ?>');" />
						</div>
						<div class="cntBx">
							<div class="wdh100 flLt">
								<div>
                                <div class='cmttxt'>
									  <span><strong><a href="<?php echo site_url('getUserProfile').'/'.$displayName; ?>">
									  <?php echo $displayName;?></a></strong></span>		
								</div>
                                </div>
                                <div class="clearFix"></div>
							</div>
						</div>
					 </div>
					

					<div class="topic-discusn wdh100 flLt">
						<?php 
							  $text = html_entity_decode(html_entity_decode($text,ENT_NOQUOTES,'UTF-8'));
							  $text = formatQNAforQuestionDetailPage($text,30);
					  	?>
                        	<?php if(strlen(strip_tags($text))>300){?>
							<p id="shortCnt<?php echo $msgId;?>">
								<?php echo cutString($text, 300);?> <a data-cntId="<?php echo $msgId;?>" class="_readMore">Read more</a>
				            </p>
				           <?php }?>
							<p id="fullCntnt<?php echo $msgId;?>" <?php if(strlen(strip_tags($text))>300){?> class="hide" <?php }?>>
								<?php echo $text;?>
				            </p>
					</div>
					<div class="topic-flex wdh100 flLt">
						<span class="fcdGya" style="font-size:12px">a few secs ago</span>
						<span class="rplybox" data-msgId="<?php echo $msgId;?>"><a><i class="reply-ico"></i>Reply</a></span>				
					</div>

						<!--reply box on comment -->
						<?php $dataArray = array('userId'=>$displayNameId,'userImageURL'=>$userProfileImage,'userGroup' =>$userGroup,'threadId' =>$threadId,'ansCount' => $commentCountForTopic,'detailPageUrl' =>'', 'msgId' => $msgId, 'mainAnsId' => $msgId, 'dotCount'=>2 , 'displayname'=> $displayName, 'sortFlag'=>2, 'placeholder'=>'Reply...','trackingPageKeyId'=>$replyTrackingKey,'page'=>$page,'totalReply'=>$totalReply);
							$inlineReplyForm = $this->load->view('mAnA5/InlineForm_Reply_Comment',$dataArray,true);
							echo $inlineReplyForm;
						?>
						<!--end reply box on comment -->

					</div>
			</div>
		</div>
		<div id="<?php echo 'repliesContainer'.$msgId; ?>" style="display: none"></div>
	</div>
</div>
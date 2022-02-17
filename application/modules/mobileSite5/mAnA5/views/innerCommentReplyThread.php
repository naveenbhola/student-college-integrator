<?php  foreach ($topic_messages as $key => $comment){
			$answerId = $comment['msgId'];
	?>
    <div class="comment-wrapp" id="cmtBox<?php echo $answerId;?>">
    <div class="comment-details">
		<div class="fbkBx w6 flLt wdh100">
			<div>
				<div class="flLt wdh100">
					<?php if($comment['status']!='abused'){ ?>
					<div class="mobile-flex">
						<div class="imgBx"> 
							<img src="<?php if($comment['userImage']=='') echo getSmallImage("/public/images/photoNotAvailable_v1.gif"); else echo getSmallImage($comment['userImage']); ?>" style="cursor:pointer;" onClick="window.location=('<?php echo $userProfile.$comment['displayname']; ?>');"/>
						</div>
						<div class="cntBx">
							<div class="wdh100 flLt">
								<div>
                                <div class='cmttxt'>
									  <span><strong><a href="<?php echo $userProfile.$comment['displayname']; ?>">
									  <?php echo $comment['displayname']; ?></a></strong></span>
								</div>
                                </div>
                                <div class="clearFix"></div>
							</div>
						</div>
					 </div>
					<?php }else{ ?>
						<div class="wdh100 flLt">
							<div style="padding-top:2px;">
								This entity has been removed on account of report abuse.
							</div>
						</div>
					<?php } ?>

					<div class="topic-discusn wdh100 flLt">
					<?php $text = html_entity_decode(html_entity_decode($comment['msgTxt'],ENT_NOQUOTES,'UTF-8'));
						  $text = formatQNAforQuestionDetailPage($text,500);
					?>
							   <?php if(strlen(strip_tags($text))>300){?>
								<p id="shortCnt<?php echo $answerId;?>">
									<?php echo cutString($text, 300);?> <a data-cntId="<?php echo $answerId;?>" class="_readMore">Read more</a>
                                </p>
                               <?php }?>
								<p id="fullCntnt<?php echo $answerId;?>" <?php if(strlen(strip_tags($text))>300){?> class="hide" <?php }?>>
									<?php echo $text;?>
                                </p>
                            
					</div>
					<div class="topic-flex wdh100 flLt">
						<span class="fcdGya" style="font-size:12px">
											<?php echo $comment['creationDate'];?>
										</span>
						<span class="rplybox" data-msgId="<?php echo $answerId;?>"><a><i class="reply-ico"></i>Reply</a></span>				
					</div>
						<!-- reply box -->
						<?php $dataArray = array('userId'=>$userId,'userImageURL'=>$userImageURLDisplay,'userGroup' =>$userGroup,'threadId' =>$comment['threadId'],'ansCount' => $commentCountForTopic,'detailPageUrl' =>'', 'msgId' => $answerId, 'mainAnsId' => $answerId, 'dotCount'=>2 , 'displayname'=> $displayName, 'sortFlag'=>2, 'placeholder'=>'Reply...','trackingPageKeyId'=>$replyTrackingKey, 'totalReply'=>$comment['msgCount'],'page'=>$page);
							$inlineReplyForm = $this->load->view('mAnA5/InlineForm_Reply_Comment',$dataArray,true);
							echo $inlineReplyForm;
						?>
						<!-- end reply box -->

					</div>
			</div>
		</div>

		<div id="<?php echo 'repliesContainer'.$answerId; ?>" <?php if(count($topic_replies[$answerId])<=0){?> style="display:none;" <?php }?> >
		  	<?php 
		  	$replyArr = $topic_replies[$answerId];
			foreach ($replyArr as $key => $reply) {
				$commentId = $reply['msgId'];
		  	?>
			  <div id="completeMsgContent<?php echo $commentId;?>" class="fbkBx" style="display:<?php if($y>=10)echo 'none';else echo '';?>;">
				  <div>
					  <div class="flLt wdh100">
						  <?php if($reply['status']!='abused'){ ?>
						  <div class="mobile-flex">							  <div class="imgBx">
								  <img src="<?php if($reply['userImage']=='') echo getTinyImage("/public/images/photoNotAvailable_v1.gif"); else echo getTinyImage($reply['userImage']); ?>" style="cursor:pointer;" onClick="window.location=('<?php echo $userProfile.$reply['displayname']; ?>');"/>
							  </div>
							  <div class="cntBx">
								  <div class="wdh100 flLt">
									  <div class="">
										<span>
											<span><strong><a href="<?php echo $userProfile.$reply['displayname']; ?>">
											<?php echo $reply['displayname']; ?></a></strong></span>
										</span>
									  </div>
								  </div>
							  </div>
							  </div>

							  <div class="clearFix"></div>
						  <?php }else{ ?>
							  <div class="wdh100 flLt">
								  <div style="padding-top:2px;">
									  This entity has been removed on account of report abuse.
								  </div>
							  </div>
						  <?php } ?>

						  <!-- user replies on comments -->
						  <div class="topic-discusn wdh100 flLt">
					  <?php $text = html_entity_decode(html_entity_decode($reply['msgTxt'],ENT_NOQUOTES,'UTF-8')); 
						 	$text = formatQNAforQuestionDetailPage($text,500);?>
						  	 
						  	   <?php if(strlen(strip_tags($text))>300){?>
								<p id="shortCnt<?php echo $commentId;?>">
									<?php echo cutString($text, 300);?> <a data-cntId="<?php echo $commentId;?>" class="_readMore">Read more</a>
                                </p>
                               <?php }?>
								<p id="fullCntnt<?php echo $commentId;?>" <?php if(strlen(strip_tags($text))>300){?> class="hide" <?php }?>>
									<?php echo $text;?>
                                </p>

						  </div>
						  <div class="topic-flex wdh100 flLt"><span class="fcdGya"><?php echo $reply['creationDate'];?></span>
						  </div>

					  </div>
				  </div>
			  </div>
			  
		  <?php } ?>
		</div>
			
		<a id="replyMore<?php echo $answerId;?>" data-msgId="<?php echo $answerId;?>" data-callType="reply" <?php if($comment['msgCount'] > $commentLimit){?> class="load-replies" <?php }else{?> class="load-replies hide" <?php }?> >
			<i class="load-rplyico"></i> Load More Replies
		</a>
		
    </div>
    	 
	</div>
	<?php } ?>
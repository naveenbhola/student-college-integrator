 <?php if(!empty($qna)){ ?>
 	<input type="hidden" id="campus-connect-page" value="campus-qna" />
 	<?php $count = 0 ;
 	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
 	$isCmsUser =0;
 	if((is_array($validateuser))&&(strcmp($validateuser[0]['usergroup'],'cms') == 0)){
 		$isCmsUser = 1;
 	}
 	?>
 	
	<input id="pageKeyForReportAbuse" type="hidden" value="LISTING_PAGE_REPORTABUSE">
	<?php foreach ($qna as $index => $data){ ?> 
	<?php $count++;?>
	<?php $questionData = $data["data"];?>
	<?php if(!empty($questionData["title"])){ ?>
	
			<dl>
				<dt> 
					<div class="ques-icon">Q</div>
					<?php  
					$questionText = $questionData["title"];
					$questionPostedFromUserId = $questionData["userId"];
					$answerPostedByUsers = array();
					$answers = $data["answers"];
					foreach ($answers as $answerId => $answerData){
					    $answer = $answerData["data"];
					    if(is_array($answer)){
					        $answerPostedByUsers[] = $answer['userId'];
					    }
					}

					$questionText = html_entity_decode(html_entity_decode($questionText,ENT_NOQUOTES,'UTF-8'));
					$questionText = formatQNAforQuestionDetailPageWithoutLink($questionText,140);
					?>
					<div class="camp-qna-details">
						<p><a href="<?php echo $questionData["q_url"];?>" ><?php echo $questionText;?></a></p>
						<p class="posted-info clearfix">
							<span><label>Posted by:</label> <?php echo $questionData["firstname"].' '.$questionData['lastname'];?></span>
							<span><?php echo  makeRelativeTime($questionData["creationDate"]);?> </span>
						</p>
						
						<?php if($questionData['status'] == 'live'){?>

						<?php if($userId!=$questionPostedFromUserId && !in_array($userId, $answerPostedByUsers) && $isCmsUser == 0){ ?>
						<!--reply--->
						<p style="background:#fefbcd; padding:2px 5px; width:auto; font-size:12px;display:none;" id="success<?php echo $questionData['msgId'];?>_msg"></p>
						<?php if(!$doNoShowAnswerForm){ ?><a class="button blue small _rply_btn" id="_rply_btn<?php echo $questionData['msgId'];?>" href="javascript:void(0)" onclick="showReplyCommentBox(this, 'Reply','<?php echo $questionData["msgId"];?>')"><span>Reply</span></a><?php } ?>
						
						<?php 
						 $data['viewType'] = 'reply';
						 $data['buttonName'] = 'Reply';
						 $data['msgId'] = $questionData["msgId"];
						 $data['pageName'] = 'CourseDetailPage';
						 $data['trackingPageKeyId']=$rtrackingPageKeyId;
					        $this->load->view('mAnA5/campusRep/commentReplyPage',$data);?>
						<?php } ?>
						<!--end-reply--->
						<?php }else{ ?>
						<p  style="background:#fefbcd; padding:2px 5px; width:auto; font-size:12px">This question has been closed for answering.</p>
						<?php } ?>	
					</div>
					
					<div id ="new_answer_id_<?php echo $questionData["msgId"];?>"></div>	
					
				</dt>
			       
				<?php
				$answers = $data["answers"];
				if(!empty($answers)){
					$i = 0;
					foreach ($answers as $answerId => $answerData){
						$answer = $answerData["data"];
						if(!empty($answer)){
							$i++;
							?>
					       
							<dd <?php if($i>1){echo "style='margin-top:15px;'";} ?> >
								<div class="ans-icon">A</div>
								<div class="camp-qna-details">
									<?php  
									$answerText =  $answer["title"];
									$answerText = html_entity_decode(html_entity_decode($answerText,ENT_NOQUOTES,'UTF-8'));
									$answerText = formatQNAforQuestionDetailPage($answerText,300);
									$showShortAnswer = false;
									$answerTextShortened = '';

									$htmlTagsRemovedAnswerText = strip_tags($answerText);
									if(strlen($htmlTagsRemovedAnswerText)>120){
										$showShortAnswer = true;
										$answerTextShortened = substr($htmlTagsRemovedAnswerText,0,120);
									}
									?>
									
									<?php if($showShortAnswer){ ?>
										<p id="shortened<?=$answer['msgId']?>"><?php echo $answerTextShortened;?><a id="expandLink<?=$answer['msgId']?>" href='javascript:void(0)' onClick='expandAnswer("<?=$answer['msgId']?>");'>...Read More</a></p>
										<p id="expanded<?=$answer['msgId']?>" style="display: none;"><?php echo $answerText;?></p>
									<?php }else{ ?>
										<p><?=$answerText?></p>
									<?php } ?>
									
									<p class="posted-info clearfix">
										<span><label>Posted by:</label> <?php echo $answer["firstname"].' '.$answer['lastname'];?><a href="javascript:void(0)" class="current-stu-btn ans_<?php echo $answer['userId']?>" style="display: none;cursor:default;"></a></span>
										<span><?php echo makeRelativeTime($answer["creationDate"]);?> </span>
									</p>
									
									<?php
									if(!empty($answerData['comments'])){
										$total = count($answerData['comments']);
									?>
									<p>
										<a class="que-count" href="<?php echo $questionData["q_url"];?>"><i class="sprite comment-icon"></i> <span id="comment<?php echo $answer['msgId'];?>_count"><?php echo $total;?></span> <?php echo ($total > 1)?' Comments':' Comment'?></a>
									</p>
									<?php }else{
								         $data['q_url'] = $questionData['q_url'];
									 ?>
									<p id="make_total_comment_<?php echo $answer['msgId'];?>" style="display: none;"></p>
									<?php }?>
									<!--reply--->
									<p style="background:#fefbcd; padding:2px 5px; width:auto; font-size:12px;display:none;" id="success<?php echo $answer['msgId'];?>_msg"></p>
									
									<?php if(!$doNoShowAnswerForm){ ?><a class="button blue small _rply_btn" id="_rply_btn<?php echo $answer['msgId'];?>" href="javascript:void(0)" onclick="showReplyCommentBox(this,'Comment','<?php echo $answer['msgId'];?>')"><span>Comment</span></a><?php } ?>
									
									<?php
									$data['viewType'] = 'comment';
									$data['buttonName'] = 'Comment';
									$data['msgId'] = $answer['msgId'];
									$data['threadId'] = $answer['threadId'];
									$data['pageName'] = 'CourseDetailPage';
									$data['trackingPageKeyId']=$ctrackingPageKeyId;
									$this->load->view('mAnA5/campusRep/commentReplyPage',$data);?>
									<!--end--reply--->
								</div>
							</dd>
						<?php
						}
					}
				}
				?>			
			</dl>	
	<?php
	}
	}	//End For each
	
	if(count($qna) < $pageSize){	//Questions have exhausted. No more questions
		echo "<input type='hidden' name='areQuestionExhausted".$pageNo."' id='areQuestionExhausted".$pageNo."' value='true'>";
	}
}
?>

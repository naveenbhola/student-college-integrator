 <?php if(!empty($qna)):?>
 	<input type="hidden" id="campus-connect-page" value="campus-qna" />
 	<?php $count = 0 ;
 	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
 	$isCmsUser =0;
 	if((is_array($validateuser))&&(strcmp($validateuser[0]['usergroup'],'cms') == 0)){
 		$isCmsUser = 1;
 	}
 	?>
 	
<input id="pageKeyForReportAbuse" type="hidden" value="LISTING_PAGE_REPORTABUSE">
<?php foreach ($qna as $index => $data):?> 
<?php $count++;?>
<?php $questionData = $data["data"];?>
<?php if(!empty($questionData["title"])):?>
                <li id="questionDiv_<?php echo $questionData['msgId'];?>">
                	
                	
		<?php $answers = $data["answers"];
		$count_answers = count($count_answers);
		$count_answers++;
		?>
				<div class="clear-width">
	                    <span class="ques-icon">Q</span>
	                    <div class="ques-details" onmouseover="showReportAbuseDiv(<?php echo $questionData['msgId'];?>)" onmouseout="hideReportAbuseDiv(<?php echo $questionData['msgId'];?>)">
	                        
	                        <?php  
	                        $questionText = $questionData["title"];
	                        $questionText = html_entity_decode(html_entity_decode($questionText,ENT_NOQUOTES,'UTF-8'));
	                        $questionText = formatQNAforQuestionDetailPageWithoutLink($questionText,140);
	                        ?>
				<p><a class="black-link" href="<?php echo $questionData["q_url"];?>" style="color:#0065de !important;"><?php echo $questionText;?></a></p>
	                        <p class="ques-head">
	                            <strong class="fllt" ><span>Posted by:</span> <a target="_blank" href="/getUserProfile/<?php echo $questionData['displayname'];?>"><?php echo $questionData["firstname"].' '.$questionData['lastname'];?></a></strong>
	                            <span id="div_<?php echo $questionData['msgId'];?>_postedBy" style="vertical-align:middle;"> | <?php echo  makeRelativeTime($questionData["creationDate"]);?></span>
				    <?php if(!$isQuestionUser):?>
									<?php if($questionData['reportedAbuse']==0):?>
											<?php if(!(($isCmsUser == 1)&&($questionData['status']=='abused'))):?>																		
												<span id="abuseLink<?php echo $questionData['msgId']?>" class="flRt" style="display:none;">
												<a class="flRt" href="javascript:void(0);" 
												onclick="report_abuse_overlay('<?php echo $questionData['msgId'];?>','<?php echo $questionData['userId'];?>','<?php echo $questionData['parentId'];?>','<?php echo $questionData['threadId'];?>','Question',0,0);" >
												Report Abuse
												</a>
												</span>
											<?php endif;?>
									<?php else:?>
											 <span id="abuseLink<?php echo $questionData['msgId']?>" class="flRt" style="display:none;">Reported as inappropriate</span>			
									<?php endif;?>
					<?php endif;?>
	                        </p>
				
							<?php if($questionData['status'] == 'live'):?>
								<!--Start_AbuseForm-->
								<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $questionData['msgId'];?>">
								</div>
								<!--End_AbuseForm-->							
	                        <p class="ques-head">
								<?php if(!$doNoShowAnswerForm){ ?><a class="flLt" style="font-size: 14px" href="javascript:void(0);" onclick="showReplyBox('<?php echo $questionData["msgId"];?>','<?php echo $rtrackingPageKeyId;?>')">Reply</a><?php } ?>
								<?php 
									$isQuestionUser = false;
									if($userId == $questionData['userId']){
										$isQuestionUser = true;
									}
								?>
								</p>
								<?php else:?>
								<p  style="background:#fefbcd; padding:2px 5px; width:auto; font-size:12px">This question has been closed for answering.</p>								
<div class="clearFix"></div>
							<?php endif;?>
	                        
				
	                        <div id ="new_answer_id_<?php echo $questionData["msgId"];?>"></div>	
	                    </div>
			    </div>
	                    <?php if(!empty($answers)):?>
	                    <?php foreach ($answers as $answerId => $answerData): ?>
	                    <?php $answer = $answerData["data"];?>
	                    <?php if(!empty($answer)):?>
		                    <div class="mt10 clear-width">
				    <span class="ques-icon">A</span>
		                    <div class="ques-details" onmouseover="showReportAbuseDiv(<?php echo $answer['msgId'];?>);"
				    onmouseout="hideReportAbuseDiv(<?php echo $answer['msgId'];?>);">
		                        
		                        <?php  
		                        $answerText =  $answer["title"];
		                        $answerText = html_entity_decode(html_entity_decode($answerText,ENT_NOQUOTES,'UTF-8'));
		                        $answerText = formatQNAforQuestionDetailPage($answerText,300);
		                        ?>
					
					<?php if(strlen($answerText)>200) {?>
					 <p class="answerText_<?php echo $answer['msgId']; ?>"><?php echo substr($answerText,0,197).'...'.' ';?><a href="javascript:void(0);" onclick="showAnswerThreadDiv(<?php echo $answer['msgId']; ?>);showCommentBox('<?php echo $questionData["msgId"];?>','<?php echo $answerId;?>')" style=" font-size:12px;">View more</a></p><?php }else{?>
					 
					  <p><?php echo $answerText;?></p>	 
					<?php } ?>
		                        <p style="display:none;" class="answerThread_<?php echo $answer['msgId']; ?>"><?php echo $answerText;?></p>
					<p class="ques-head">
		                            <strong class="flLt" style="line-height:20px;"><span style="vertical-align:top;">Posted by:</span> <a target="_blank" href="/getUserProfile/<?php echo $answer['displayname'];?>" style="vertical-align:top;"><?php echo $answer["firstname"].' '.$answer['lastname'];?></a> <b class="blue-btn ans_<?php echo $answer['userId']?>" style="display:none;"></b></strong>
		                            <span id="div_<?php echo $answer['msgId'];?>_postedBy" style="margin-left: 8px;line-height:20px;"><?php echo makeRelativeTime($answer["creationDate"]);?></span>
					    <?php if(!$isAnswerUser):?>
									<?php if($answer['reportedAbuse']==0):?>
											<?php if(!(($isCmsUser == 1)&&($answer['status']=='abused'))):?>
											<span id="abuseLink<?php echo $answer['msgId']?>" class="flRt" style="display:none;">																		
												<a  class="flRt" href="javascript:void(0);" 
												onclick="report_abuse_overlay('<?php echo $answer['msgId'];?>','<?php echo $answer['userId'];?>','<?php echo $answer['parentId'];?>','<?php echo $answer['threadId'];?>','Answer',0,0);" >
												Report Abuse
												</a>
											</span>	
											<?php endif;?>
									<?php else:?>
											<span id="abuseLink<?php echo $answer['msgId']?>" class="flRt" style="display:none;">Reported as inappropriate</span>			
									<?php endif;?>
								<?php endif;?>
		                        </p>
								<!--Start_AbuseForm-->
								<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $answer['msgId'];?>">
								</div>
								<!--End_AbuseForm-->		                        
		                        <div class="ques-head" style="margin-bottom: 10px;float:left; width:100%;">
					<?php if(!empty($answerData['comments'])){$commentlabel = count($answerData['comments']).' '.'Comment(s)';} else {$commentlabel = 'Comment';}?>
					<?php if(!$doNoShowAnswerForm){ ?><a href="javascript:void(0);" style="font-size:14px" class="flLt" onclick="threadId='<?php echo $questionData["msgId"];?>';showCommentBox('<?php echo $questionData["msgId"];?>','<?php echo $answerId;?>')"><?=$commentlabel ?></a><?php } ?>
								<?php 
									$isAnswerUser = false;
									if($userId == $answer['userId']){
										$isAnswerUser = true;
									}
								?>							
								
								<!-- Block Start for Dig Up / Dig down -->
					<div class="flRt " id="digup_down_block_<?php echo $answer["msgId"];?>" >
                                        <input type="hidden" id="pageKeyForDigVal" value="COURSE_DETAILS_UPDATEDIGVAL" /> 
                                        <p class="font-12" style="color: #3d3d3d; text-align: right;"> 
                                           Did you find this answer useful: 
                                           <a href="javascript: void(0);" onClick="updateDig(this,'<?=$answer['msgId']?>',1,'','',false,'answer','listingPage');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;">Yes</a> 
                                           <span class="pipe">|</span> 
                                           <a href="javascript: void(0);" onClick="updateDig(this,'<?=$answer['msgId']?>',0,'','',false,'answer','listingPage');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;">No</a> 
                                        </p> 
                                        <div class="showMessages" style="display:none;margin-top:5px;margin-bottom:5px;" id="confirmMsg<?=$answer['msgId']?>">&nbsp;</div> 
                                       </div>
				    <div class="clearFix"></div>
					<!-- Block End for Dig Up / Dig down -->

                </div>
                
				<div id="displayAllcomments<?php echo $answer["msgId"];?>"  class="answerThread_<?php echo $answer['msgId']; ?>" style="display:none;">

		                <?php if(!empty($answerData['comments'])):?>
		                    	<?php foreach ($answerData['comments'] as $id => $commentData):?>
					<div class="comment-box" style="margin-bottom:0; border-bottom:1px dashed #ccc;margin-left:5px;margin-right:20px;" >
		                    	<?php  
		                        $commentText =  $commentData["title"];
		                        $commentText = html_entity_decode(html_entity_decode($commentText,ENT_NOQUOTES,'UTF-8'));
		                        $commentText = formatQNAforQuestionDetailPage($commentText,300);
		                        ?>
					<?php if(strlen($commentText)>200) {?>
					<p class="commentText_<?php echo $answer['msgId']; ?>"><?php echo substr($commentText,0,197).'...'.' ';?><a href="javascript:void(0);" onclick="showCommentThreadDiv(<?php echo $answer['msgId']; ?>);" style=" font-size:12px;"> View more</a></p><?php }else{?>
					<p><?php echo $commentText;?></p>
		<?php } ?>
		                        <p style="display:none;" class="commentThread_<?php echo $answer['msgId']; ?>"><?php echo $commentText;?></p>
					<p class="ques-head">
		                            <strong class="fllt"><span>Posted by: </span><a target="_blank" href="/getUserProfile/<?php echo $commentData['displayname'];?>"><?php echo $commentData["firstname"].' '.$commentData['lastname'];?></a></strong>
		                            <span  id="div_<?php echo $commentData['msgId'];?>_postedBy" style="vertical-align:middle;"> | <?php echo makeRelativeTime($commentData["creationDate"]);?></span>
					    
					<!-- <a href="javascript:void(0);" class="flLt">Reply</a>--> 
								<?php 
									$isCommentUser = false;
									if($userId == $commentData['userId']){
										$isCommentUser = true;
									}
								?>
								<?php if(!$isCommentUser):?>
									<?php if($commentData['reportedAbuse']==0):?>
											<?php if(!(($isCmsUser == 1)&&($commentData['status']=='abused'))):?>	
											<span id="abuseLink<?php echo $commentData['msgId']?>" class="flRt" style="display:none;">																	
												<a class="flRt" href="javascript:void(0);" 
												onclick="report_abuse_overlay('<?php echo $commentData['msgId'];?>','<?php echo $commentData['userId'];?>','<?php echo $commentData['parentId'];?>','<?php echo $commentData['threadId'];?>','Comment',0,0);" >
												Report Abuse
												</a>
											</span>	
											<?php endif;?>
									<?php else:?>
											<span id="abuseLink<?php echo $commentData['msgId']?>" class="flRt" style="display:none;">Reported as inappropriate</span>			
									<?php endif;?>
								<?php endif;?>
		                        </p>
								<!--Start_AbuseForm-->
								<div class="formResponseBrder" id="abuseForm<?php echo $commentData['msgId'];?>">
								</div>
								<!--End_AbuseForm-->			                        
		                         
		                        
		                       
		                    	<div class="clearFix"></div>
		                    </div>
				     <?php endforeach;?>
		                   <?php endif;?>
				</div>
				<div style="clear:left; margin-top:10px;"  id ="commentDisplaySection_<?php echo $answer["msgId"];?>"></div>       
			
				</div>
				
		                  <?php endif;?> 
	                    <?php endforeach;?>
	                    <?php endif;?>
	               
                </li>
 <?php endif;?>                    
<?php endforeach;?>
<?php endif;?>

<?php if(count($result)>0)
{
     foreach((object)$result['result'] as $result)
     {
	if($result->courseId<=0 || $result->courseId==''){
		continue;
	}
	$course = $courseRepository->find($result->courseId);
	if(is_object($course))
	{
	$postedUserCourse = html_escape($course->getName());
	//echo htmlspecialchars(html_escape($course->getInstituteName()));	
	}
?>

<?php
        $url = site_url("messageBoard/MsgBoard/replyMsg");
	$maximumCommentAllowed = 4;
	$functionToCall = isset($functionToCall)?$functionToCall:'-1';
	$entityTypeShown = isset($entityTypeShown)?$entityTypeShown:'';
	$articleId = isset($articleId)?$articleId:0;
	$eventId = isset($eventId)?$eventId:0;
	$userImageURL = ($userImageURL != '')?$userImageURL:'/public/images/photoNotAvailable.gif';
?>

<li id="question_<?php echo $result->msgId;?>">
			<div class="qna-col">
			    <dl>
				<dt>
				    <div class="ques-icon">Q</div>
				    <div class="qna-details">
					<h3><?php echo $result->msgTxt;?></h3>
				    <p class="posted-info">
				    Posted by: <?php echo $result->PostedBy?><br />
				    Course: <?php echo $postedUserCourse;?>
				    </p></div>
				</dt>
				
				<dd>
				    <div class="ques-icon">A</div>
				    <div class="qna-details">
					<h3><?php echo $result->Answer;?></h3>
	
				    <div class="approved-bar clear-width">
			                <?php if($result->answer_status=='approved'){ ?>
					<p><span><i class="campus-sprite approved-icon"></i>Approved</span></p>
					<?php } else { ?>
					<p><span class="q-mark">?</span><span>Pending Approval</span></a></p>
					<?php } ?>
					<p id='commentTab_<?php echo $result->msgId;?>' onclick="toggleCommentTab('<?php echo $result->msgId;?>')" ><a href="javascript:void(0);"><i class="campus-sprite comment-icon" ></i><?php if(count($AnswerComment[$result->answerId])>0){
			       echo count($AnswerComment[$result->answerId]); ?> Comment(s)
			      <?php  } else { ?>Comment <?php } ?></a></p>
					<?php if($result->isFeatured!=0){ ?>
					<p><span><i class="campus-sprite featured-icon"></i>Featured</span></p>
					<?php } ?>
				    </div>
				    </div>
				 </dd>
			     </dl>
			 </div>
		    
			 <div id ="comment_<?php echo $result->msgId;?>" class="comment-section" style="display:none;">
			 
						<div class="comment-head" <?php if(count($AnswerComment[$result->answerId])<=0){
						echo "style='display:none;'";
						} ?> >
									<i class="campus-sprite comment-icon-2"></i>
									<?php if(count($AnswerComment[$result->answerId])>5){ ?>
												<a id="commentLink<?=$result->answerId?>" href="javascript:void(0)" onClick="showAllComments('<?=$result->answerId?>')">View all <?=count($AnswerComment[$result->answerId])?> Comment(s)</a>
												<span id="commentSpanLink<?=$result->answerId?>" style="display:none;"><?=count($AnswerComment[$result->answerId])?> Comment(s)</span>
									<?php }else if(count($AnswerComment[$result->answerId])>0){ 			
												echo count($AnswerComment[$result->answerId]); ?> Comment(s)
									<?php } ?>

			      
						</div>
			
						<div class="comments-list clear-width" style='border-bottom: 2px solid #e6edf1; <?php if(count($AnswerComment[$result->answerId])<=0){echo "display:none;'";}?>' >
									<ul id="commentDisplaySection_<?=$result->answerId?>">
									       <?php for ($i=0; $i<count($AnswerComment[$result->answerId]);$i++){ ?>
									       
									       <?php if($i<count($AnswerComment[$result->answerId])-5){
												echo "<li style='display:none' id='hidden_".$result->answerId."_$i'>";
												}
												else{
															echo "<li>";
												} ?>
										  
										    <div style="width:70%;" class="flLt">
											<dl>
											    <dt>
												<i class="campus-sprite user-icon"></i>
												<p>Posted by: <?php echo $AnswerComment[$result->answerId][$i]['displayname'];?></p>
											    </dt>
											    <dd>
												<?php
												$commentDisplayText = html_entity_decode(html_entity_decode($AnswerComment[$result->answerId][$i]['msgTXT'],ENT_NOQUOTES,'UTF-8'));
																  //Show only the first 500 characters of the comment followed by a More link
												$commentCharLength = strlen($commentDisplayText);
												if($commentCharLength<500){
															$commentDisplayText = formatQNAforQuestionDetailPage($commentDisplayText,500);
															echo $commentDisplayText;
																    
															}
															else{
																    echo "<span id='previewComment".$AnswerComment[$result->answerId][$i]['msgId']."'>".formatQNAforQuestionDetailPage(substr($commentDisplayText, 0, 497))."</span></a>";
																    echo "<span id='relatedCommentDiv".$AnswerComment[$result->answerId][$i]['msgId']."'>&nbsp;<FONT COLOR='#000000'>...</FONT> <a href='javascript:void(0);' id='relatedCommentHyperDiv".$AnswerComment[$result->answerId][$i]['msgId']."' onClick='showCompleteCommentHomepage(".$AnswerComment[$result->answerId][$i]['msgId'].");'>more</a></span>";
																    $commentDisplayText = formatQNAforQuestionDetailPage($commentDisplayText,2500);
																    echo "<span id='completeRelatedCommentDiv".$AnswerComment[$result->answerId][$i]['msgId']."' style='display:none;'>".$commentDisplayText."</span>";
																  }
												?>
		
											    </dd>
											</dl>
										    </div>
										    <div class="flRt tar posted-time"><?php echo ucwords(makeRelativeTime($AnswerComment[$result->answerId][$i]['creationDate']));?></div>
										    
										</li>
										<?php } ?>
									       
									</ul>
												    
						</div>
						
						<div class="comments-list clear-width">
									<ul>
										<li style="margin-bottom:0; ">
										  
										    
										    <form id="commentFormToBeSubmitted<?php echo $result->answerId;?>" 
						method="post" onsubmit="return false;" action="<?php echo 
						base_url();?>messageBoard/MsgBoard/replyMsg/<?php echo 
						$result->answerId;?>" novalidate="">
										    <div class="comment-reply clear-width">
										       <textarea class="flLt comment-area" validatesinglechar="true"  required="true" minlength="15" 
						maxlength="2500" caption="Comment" validate="validateStr" id="replyCommentText<?php echo $result->answerId;?>" onkeyup="textKey(this);autoGrowField(this);" 
						name="replyCommentText<?php echo $result->answerId;?>"></textarea>
											
											<a class="submit-btn reply-btn flLt" id="submitButton<?php echo 
						$result->answerId;?>" onclick="if(validateFields($('commentFormToBeSubmitted<?php echo $result->answerId;?>')) != true){return false;} else { new Ajax.Request('<?php echo SHIKSHA_HOME; ?>/messageBoard/MsgBoard/replyMsg/<?=$result->answerId?>/campusDashboard',{onSuccess:function(request){javascript:addSubCommentHTML(<?php echo $result->answerId;?> ,request.responseText); }, evalScripts:true, parameters:Form.serialize($('commentFormToBeSubmitted<?php echo $result->answerId;?>'))})};return false;" href="javascript:void(0);">Reply</a>
											<p class="char-text clear-width"><span id="replyCommentText<?php echo 
						$result->answerId;?>_counter">0</span> out of 2500 characters are entered<br />
											Campus Representative <a href="javascript:void(0);" onclick='return popitup("<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/campusRepGuideline",500,1030,"yes",50,200);'>Guideline</a></p>
											<div class="spacer5 clearFix"></div>
						     <div style="display:none;" class="errorPlace Fnt11"><div 
						id="replyCommentText<?php echo $result->answerId;?>_error" 
						class="errorMsg"></div></div>
											   
															
												<div class="errorPlace" style="display:block"><div class="errorMsg" id="seccode<?php echo 
									$result->answerId;?>_error"></div></div>
									
												    <input type="hidden" name="sortFlag<?php echo 
									$result->answerId;?>" value="<?php echo $temp['sortFlag'];?>" />
												    <input type="hidden" name="threadid<?php echo 
									$result->answerId;?>" value="<?php echo $result->msgId; ?>" />
												    <input type="hidden" name="dotCount<?php echo 
									$result->answerId;?>" value="<?php echo $dotCount; ?>" />
												    <input type="hidden" name="fromOthers<?php echo 
									$result->answerId;?>" value="user" />
												    <input type="hidden" name="mainAnsId<?php echo 
									$result->answerId;?>" value="<?php echo $result->answerId; ?>" />
												    <input type="hidden" name="displaynameId<?php echo 
									$result->answerId;?>" value="<?php echo $temp['userId']; ?>" />
												    <input type="hidden" name="displayname<?php echo 
									$result->answerId;?>" value="<?php echo $temp['userId']; ?>" />
												    <input type="hidden" name="actionPerformed<?php echo 
									$result->answerId;?>" id="actionPerformed<?php echo 
									$result->answerId;?>" value="addComment" />
												    <input type="hidden" name="functionToCall<?php echo 
									$result->answerId;?>" id="functionToCall<?php echo 
									$result->answerId;?>" value="<?php echo $functionToCall; ?>" />
												    <input type="hidden" name="userProfileImage<?php echo 
									$result->answerId;?>" id="userProfileImage" value="<?php echo ($userImageURL != '')?$userImageURL:'/public/images/photoNotAvailable.gif';?>" />
												    <input type="hidden" name="mentionedUsers<?php echo 
									$result->answerId;?>" value="" id="mentionedUsers<?php echo 
									$result->answerId;?>"/>
																		
															
												</div>
										    </form>
										</li>
												
									</ul>
									
						</div>

		     </div>
</li>
<?php }
}
?>

<script>

function toggleCommentTab(threadId){
     
     $j('#comment_'+threadId).toggle();
     
}
	
function showAllComments(answerId){
			$j("li[id*='hidden_"+answerId+"_']").show();
			$j("#commentLink"+answerId).hide();
			$j("#commentSpanLink"+answerId).show();
}

function addSubCommentHTML(answerId,responseText){
			var html = $j("#commentDisplaySection_"+answerId).html();
			$j("#commentDisplaySection_"+answerId).html(html+responseText);
			$j("#commentDisplaySection_"+answerId).parent().show();
			$j("#replyCommentText"+answerId).val('');
			
}

function showCompleteCommentHomepage(quesId)
{
    $('previewComment'+quesId).style.display = 'none';
    $('relatedCommentDiv'+quesId).style.display = 'none';
    $('relatedCommentHyperDiv'+quesId).style.display = 'none';
    $('completeRelatedCommentDiv'+quesId).style.display = '';
}

function autoGrowField(f) {

  if (f.style.overflowY != 'hidden') { f.style.overflowY = 'hidden' }
  var maxHeight = 800; id = f;
   var text = id && id.style ? id : document.getElementById(id);
   if ( !text )
      return;

   var adjustedHeight = text.clientHeight;
   if ( !maxHeight || maxHeight > adjustedHeight )
   {
      adjustedHeight = Math.max(text.scrollHeight, adjustedHeight);
      if ( maxHeight )
         adjustedHeight = Math.min(maxHeight, adjustedHeight);
      if ( adjustedHeight > text.clientHeight )
         text.style.height = adjustedHeight + "px";
      if($('autoSuggestorContent'))
          $j('#autoSuggestorContent').css('top',($j('#'+f.id).offset().top + $j('#'+f.id).height() + 55)+'px');
   }

}

</script>

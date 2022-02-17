<?php 
			$strLimit = 100;
            $replyContext="addExamPageComment";
			$functionToCall = isset($functionToCall)?$functionToCall:-1;
			foreach($discussionArr as $key=>$discussion)
			{
						
			?><li>
                        	<dl>
                            	<dt>
                                	<i class="exam-sprite thread-icon"></i>
                                    <div class="thread-info">
                                    	<a href="<?=getSeoUrl($discussion['threadId'],'discussion',$discussion['Title']);?>" class="thread-title"><?php echo $discussion['Title'];?></a>
                                        <span>Started By <?=$discussion['displayname']?> , On <?=date('F Y, h:i a', strtotime($discussion['creationDate']))?></span>
                                    </div>
                                </dt>
                                <dd>
                                	<a href="<?=getSeoUrl($discussion['threadId'],'discussion',$discussion['Title']);?>" class="comment-link">Total <?=$countArr[$discussion['threadId']]?> Comments</a>
                                    <div class="last-comment-sec">
			                <?php
					if($lastCommentArr[$discussion['msgId']]['avtarimageurl']==''){
					?>
						<i class="exam-sprite user-thumb"></i>
					<?php
					}else{
			                ?>
						<img src="<?=MEDIA_SERVER.$lastCommentArr[$discussion['msgId']]['avtarimageurl']?>" width="25" height="25" />
					<?php
					}
					?>
                                    	<div class="last-comment-info">
                                        	<span>Last Comment by: <?=$lastCommentArr[$discussion['msgId']]['displayName']?></span>
                                            
						<?php
						$description = $lastCommentArr[$discussion['msgId']]['description'];
						?>
						<p id="span-half-<?=$lastCommentArr[$discussion['msgId']]['msgId']?>"><?=cutString($description, $strLimit)?>
						<?php
						if($strLimit <= strlen($description))
						{
						?><a href="javascript:void(0);" onclick="showCompleteComment('<?=$lastCommentArr[$discussion['msgId']]['msgId']?>')">More</a>
						<?php
						}
						?>
						</p>
						<p id="span-full-<?=$lastCommentArr[$discussion['msgId']]['msgId']?>" style="display:none;"><?=$lastCommentArr[$discussion['msgId']]['description']?></p>
						<div class="comment-field clearfix">
						  
    <form id="commentFormToBeSubmitted<?php echo $discussion['msgId'];?>" method="post" onsubmit="checkTextElementOnTransition($('replyCommentText<?php echo $discussion['msgId'];?>'),'focus');document.getElementById('submitButton<?php echo $discussion['msgId'];?>').disabled=true;if(validateFields($('commentFormToBeSubmitted<?php echo $discussion['msgId'];?>')) != true){
			document.getElementById('submitButton<?php echo $discussion['msgId'];?>').disabled=false; return false;} else { document.getElementById('submitButton<?php echo $discussion['msgId'];?>').disabled=true; new Ajax.Request('/messageBoard/MsgBoard/replyMsg/<?=$discussion['msgId']?>/examPageComment',{onSuccess:function(request){javascript: newDiscussionForm('<?php echo $discussion['msgId'];?>' ,request.responseText,'<?php echo $discussion['msgId'];?>','refreshPage','','<?php echo $replyContext; ?>'); }, evalScripts:true, parameters:Form.serialize($('commentFormToBeSubmitted<?php echo $discussion['msgId'];?>'))})};return false;" action="<?php echo base_url();?>messageBoard/MsgBoard/replyMsg/<?php echo $discussion['msgId'];?>" novalidate="">
 <input type="Submit" class="submit-btn" id="submitButton<?php echo $discussion['msgId'];?>"  value="Comment" />
    <div class="comment-txt-field">
    <input type="text" class="" onblur="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');$j(this).css({'color':'#333333'});" default="Participate in the discussion" value="Participate in the discussion" validatesinglechar="true"  required="true" minlength="15" maxlength="2500" caption="Comment" validate="validateStr" id="replyCommentText<?php echo $discussion['msgId'];?>" name="replyCommentText<?php echo $discussion['msgId'];?>" autocomplete="off"/>
    </div>
    <!--<a class="comment-btn" id="submitButton<?php echo $discussion['msgId'];?>"  href="javascript:void(0);">Comment</a>-->
    
    <input type="hidden" name="sortFlag<?php echo $discussion['msgId'];?>" value="<?php echo $temp['sortFlag'];?>" id="sortFlag<?php echo $discussion['msgId'];?>" />
    <input type="hidden" name="threadid<?php echo $discussion['msgId'];?>" value="<?php echo $discussion['threadId']; ?>" id="threadid<?php echo $discussion['msgId'];?>" />
    <input type="hidden" name="dotCount<?php echo $discussion['msgId'];?>" value="<?php echo $dotCount; ?>" id="dotCount<?php echo $discussion['msgId']; ?>" />
    <input type="hidden" name="fromOthers<?php echo $discussion['msgId'];?>" value="discussion" id="fromOthers<?php echo $discussion['msgId'];?>" />
    <input type="hidden" name="mainAnsId<?php echo $discussion['msgId'];?>" value="<?php echo $discussion['msgId']; ?>" id="mainAnsId<?php echo $discussion['msgId'];?>" />
    <input type="hidden" name="displaynameId<?php echo $discussion['msgId'];?>" value="<?php echo $temp['userId']; ?>" id="displaynameId<?php echo $discussion['msgId'];?>" />
    <input type="hidden" name="displayname<?php echo $discussion['msgId'];?>" value="<?php echo $temp['userId']; ?>" id="displayname<?php echo $discussion['msgId'];?>" />
    <input type="hidden" name="actionPerformed<?php echo $discussion['msgId'];?>" id="actionPerformed<?php echo $discussion['msgId'];?>" value="addComment" />
    <input type="hidden" name="functionToCall<?php echo $discussion['msgId'];?>" id="functionToCall<?php echo 
$discussion['msgId'];?>" value="<?php echo $functionToCall; ?>" />
    <input type="hidden" name="userProfileImage<?php echo $discussion['msgId'];?>" id="userProfileImage" value="<?php echo ($discussion['avtarimageurl'] != '')?$discussion['avtarimageurl']:'/public/images/photoNotAvailable.gif';?>" />
    <input type="hidden" name="mentionedUsers<?php echo $discussion['msgId'];?>" value="" id="mentionedUsers<?php echo $discussion['msgId'];?>"/>
    </form>
						</div>
    <div style="display:none;" class="errorPlace Fnt11">
        <div id="replyCommentText<?php echo $discussion['msgId'];?>_error" class="errorMsg"></div>
    </div>
    <div class="errorPlace" style="display:block">
        <div class="errorMsg" id="seccode<?php echo $discussion['msgId'];?>_error"></div>
    </div>
                                        </div>
                                  </div>
                                </dd>
                            </dl>
</li><?php
			}
			?>
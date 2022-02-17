<?php $strlimit=100;?>

		<?php foreach($discussionArr as $key=>$discussion)
             {?>
                    	<li>
                          <i class="exam-mini-sprite thread-icon"></i>
                          <div class="thread-info">
                                    	<a href="javascript:void(0);" class="thread-title"><?=$discussion['Title']?></a>
                                        <span>Started By <?=$discussion['displayname']?> , On <?=date('F Y, h:i a', 
strtotime($discussion['creationDate']))?></span>
                                        <a href="javascript:void(0);" class="comment-link">Total <?=$countArr[$discussion['threadId']]?> Comments</a>
                                    </div>
                          <div class="last-comment-sec">
				<?php if($discussion['avtarimageurl']==''){ ?>
					<i class="exam-mini-sprite user-thumb"></i>
				<?php }else{ ?>
					<img src="<?=$discussion['avtarimageurl']?>" width="22" height="22" />
				 <?php
				 } ?>
                                        <div class="last-comment-info">
                                        	<span>Last Comment by: <?=$lastCommentArr[$discussion['msgId']]['displayName']?></span>

                                            <p id="commentDesc_<?=$lastCommentArr[$discussion['msgId']]['msgId'];?>"><?=substr($lastCommentArr[$discussion['msgId']]['description'],0,100)?><?php if($strlimit < strlen($lastCommentArr[$discussion['msgId']]['description'])){ ?><a href="javascript:void(0);" onclick="showWholeComment(<?=$lastCommentArr[$discussion['msgId']]['msgId'];?>)">... More</a><?php } ?></p>
					<p id="commentFullDesc_<?=$lastCommentArr[$discussion['msgId']]['msgId'];?>" style="display:none;"><?=$lastCommentArr[$discussion['msgId']]['description']?></p>
			 	             <div class="comment-field clearfix">
                                                <form id="commentFormToBeSubmitted<?php echo $discussion['msgId'];?>" method="post" onsubmit="return false;" action="<?php echo base_url();?>messageBoard/MsgBoard/replyMsg/<?php echo $discussion['msgId'];?>" novalidate="">
						<a class="comment-btn" id="submitButton<?php echo $discussion['msgId'];?>" onclick="if(validateStr($('#replyCommentText<?php echo $discussion['msgId'];?>').val(),'Comment','2500','15') != true){return false;} else { jQuery.ajax('<?php echo SHIKSHA_HOME; ?>/messageBoard/MsgBoard/replyMsg/<?=$discussion['msgId'];?>',{onSuccess:function(request){}, evalScripts:true})};return false;" href="javascript:void(0);">Comment</a>
						<div class="comment-txt-field" ><input type="text" validatesinglechar="true"  required="true" minlength="15" maxlength="2500" caption="Comment" validate="validateStr" id="replyCommentText<?php echo $discussion['msgId'];?>" name="replyCommentText<?php echo $discussion['msgId'];?>" /></div>
						<div class="errorPlace" style="display:block">
					<div class="errorMsg" id="seccode<?php echo $discussion['msgId'];?>_error"></div>
					</div>
					<input type="hidden" name="sortFlag<?php echo $discussion['msgId'];?>" value="<?php echo $temp['sortFlag'];?>" />
					<input type="hidden" name="threadid<?php echo $discussion['msgId'];?>" value="<?php echo $discussion['threadId']; ?>" />
					<input type="hidden" name="dotCount<?php echo $discussion['msgId'];?>" value="<?php echo $dotCount; ?>" />
					<input type="hidden" name="fromOthers<?php echo $discussion['msgId'];?>" value="discussion" />
					<input type="hidden" name="mainAnsId<?php echo $discussion['msgId'];?>" value="<?php echo $discussion['msgId']; ?>" />
					<input type="hidden" name="displaynameId<?php echo $discussion['msgId'];?>" value="<?php echo $temp['userId']; ?>" />
					<input type="hidden" name="displayname<?php echo $discussion['msgId'];?>" value="<?php echo $temp['userId']; ?>" />
					<input type="hidden" name="actionPerformed<?php echo $discussion['msgId'];?>" id="actionPerformed<?php echo $discussion['msgId'];?>" value="addComment" />
					<input type="hidden" name="functionToCall<?php echo $discussion['msgId'];?>" id="functionToCall<?php echo $discussion['msgId'];?>" value="<?php echo $functionToCall; ?>" />
					<input type="hidden" name="userProfileImage<?php echo $discussion['msgId'];?>" id="userProfileImage" value="<?php echo ($discussion['avtarimageurl'] != '')?$discussion['avtarimageurl']:'/public/images/photoNotAvailable.gif';?>" />
					<input type="hidden" name="mentionedUsers<?php echo $discussion['msgId'];?>" value="" id="mentionedUsers<?php echo $discussion['msgId'];?>"/>
     </form>
                                            </div>
					     <div style="display:none;" class="errorPlace Fnt11"><div id="replyCommentText<?php echo $discussion['msgId'];?>_error" class="errorMsg"></div></div>
                                        </div>
                                        
                                    </div>
                        </li><?php } ?>
			
			

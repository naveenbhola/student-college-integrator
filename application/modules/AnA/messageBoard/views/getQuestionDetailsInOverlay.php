<?php
//_p($main_message);die;
?>
<style>
    .forSmallFont span {color: #414141 !important; font-size: 12px !important;}
</style>
    <span class="shortlist-sprite pointer"></span>
    <a href="javascript:void(0);" onclick="$('quesOverlay_<?=$main_message['msgId']?>').innerHTML = '';$('quesOverlay_<?=$main_message['msgId']?>').style.display='none';" class="close-layer-btn" title="Close">&times;</a>
    <dl>
        <dt>
            <p><span class="qna-txt">Q&nbsp;:&nbsp;</span><?php echo formatQNAforQuestionDetailPage($questionText,32);?></p>
            <div class="clearfix post-info">
                <b class="flLt">Posted by: <?=$displayName;?></b>
                <b class="flRt"><?=$main_message['creationDate'];?></b>
            </div>
            
            <div>
                <?php if(!$doNoShowAnswerForm){ ?><a href="javascript:void(0);" onclick="toggleAnswerBoxOnId(<?=$main_message['msgId']?>);" id="showBoxToSubmitAnswer<?=$main_message['msgId']?>" class="write-ans-btn" style="margin-top:5px; display: inline-block;">Write Answer</a><?php } ?>
                  <form id="answerFormToBeSubmitted<?=$main_message['msgId']?>" method="post" onsubmit="return false;" action="<?=SHIKSHA_HOME?>/messageBoard/MsgBoard/replyMsg/<?=$main_message['msgId']?>" novalidate="">
                        <div style="display: none; overflow: hidden;padding:8px; background: #f1f1f1;margin-top:5px;" id="hiddenFormPart_<?=$main_message['msgId']?>">
                                <div style="margin:5px" class="cntBx">
                                        <div>
                                                <textarea class="answer-textarea" validatesinglechar="true" rows="1" required="true" minlength="15" maxlength="2500" caption="Answer" validate="validateStr" class="ftxArea comment-field" id="replyText<?=$main_message['msgId']?>" onkeyup="textKey(this);" name="replyText<?=$main_message['msgId']?>"></textarea>
                                        </div>

                                        <div class="flLt">
                                                <div class="Fnt10 fcdGya forSmallFont"  style="color:#333;">
						    <span id="replyText<?=$main_message['msgId']?>_counter">0</span> <span>out of 2500 characters</span></div>
                                                <div class="spacer5 clearFix"></div>
                                                <div style="display:none;" class="errorPlace Fnt11"><div id="replyText<?=$main_message['msgId']?>_error" class="errorMsg"></div></div>
                                                <div style="display:block" class="errorPlace"><div id="seccode<?=$main_message['msgId']?>_error" class="errorMsg"></div></div>
                                                <div class="flLt Fnt11 fcdGya">Kindly follow our <a onclick="return popitup('<?=SHIKSHA_HOME?>/shikshaHelp/ShikshaHelp/communityGuideline');" href="javascript:void(0);">Community Guidelines</a></div>
                                        </div>
                                        <div style="padding-top:5px" class="flRt">
                                                <input type="button" id="submitButton<?=$main_message['msgId']?>" onclick="disableReplyFormButton('<?=$main_message['msgId']?>');  try{ checkTextElementOnTransition(document.getElementById('replyText<?=$main_message['msgId']?>'),'focus');if(validateFields($('answerFormToBeSubmitted<?=$main_message['msgId']?>')) != true){enableReplyFormButton('<?=$main_message['msgId']?>');   return false;} }catch (e) {}; if(makeAnswerAjax){ makeAnswerAjax = false; new Ajax.Request('/messageBoard/MsgBoard/replyMsg/<?=$main_message['msgId']?>',{onSuccess:function(request){try{ addMainCommentForQues(<?=$main_message['msgId']?>,request.responseText,'-1',false,true,'','',false,'',0,false,true); if(request.responseText!='SUQ' && request.responseText!='MTOA' && request.responseText!='CAMPUSREPEXISTS' && request.responseText!='SAMEANS' && request.responseText!='NOREP'){openQnaInOverlay(<?=$main_message['msgId']?>); updateAnswerCountInQuestionListView(<?=$main_message['msgId']?>);} } catch (e) {}}, evalScripts:true, parameters:Form.serialize($('answerFormToBeSubmitted<?=$main_message['msgId']?>'))});} return false;" value="Submit" class="orange-button">
                                                &nbsp;&nbsp;
                                                <a onclick="makeAnswerAjax = true; toggleAnswerBoxOnId(<?=$main_message['msgId']?>); $j('#replyText<?=$main_message['msgId']?>').val('');" href="javascript:void(0);" style="font-size:12px;">Cancel</a>
                                        </div>
                                </div>
                        </div>
                        <div class="clearFix spacer5"></div>
                        <input type="hidden" value="<?=$main_message['msgId']?>" name="threadid<?=$main_message['msgId']?>">
                        <input type="hidden" value="seccodeForInlineAnswer" name="secCodeIndex">
                        <input type="hidden" value="myShortlistAnA_anwser" name="page_name">
                        <input type="hidden" value="user" name="fromOthers<?=$main_message['msgId']?>">
                        <input type="hidden" id="actionPerformed<?=$main_message['msgId']?>" value="addAnswer" name="actionPerformed<?=$main_message['msgId']?>">
                        <input type="hidden" id="mentionedUsers<?=$main_message['msgId']?>" value="" name="mentionedUsers<?=$main_message['msgId']?>">
			<input type="hidden" value="<?=$main_message['listingTypeId']?>" name="institute_id">
			<input type="hidden" value="<?=$courseId; ?>" name="course_id">
			
                </form>
            </div>
        </dt>
        
        <dd id="answerDD-<?=$main_message['msgId']?>">
            <?php
            $answerFlag = true;
            $answerCount = 0;
            foreach($topic_messages as $message)
            {
                //_p($message);
                $hideCommentSection = '';
                $noOfCommentsInThread = 0;
                $mainAnsId = $message[0]['msgId'];
                foreach($message as $key => $temp)
                {
                    $commentUserId = $temp['userId'];
                    $msgId = $temp['msgId'];
                    $noOfCommentsInThread++;
                    $temp['msgTxt'] = html_entity_decode(html_entity_decode($temp['msgTxt'],ENT_NOQUOTES,'UTF-8'));
                    $displayName2 = (!empty($temp['lastname']))?$temp['firstname'].' '.$temp['lastname'] : $temp['firstname'];
                    if($answerFlag) //for answer
                    {
                        $answerFlag = false;
                        $answerCount++;
                        echo '<div class="answerBlocks" id="answerBlock-'.$answerCount.'" style="margin-bottom:20px;min-height:250px;max-height:250px;overflow-y:scroll;'.(($answerCount>1)?'display:none;':'').'">';
                    ?>
                      <div style="padding-right:10px;">  
                        <p><span class="qna-txt">A :</span> <?php echo formatQNAforQuestionDetailPage($temp['msgTxt'],300);?></p>
                        <div class="clearfix post-info">
                            <b class="flLt">Posted by: <?=$displayName2?> &nbsp;&nbsp;
                            <?php if(isset($badges[$commentUserId]) && !empty($badges[$commentUserId])):?>
                                <?php if($badges[$commentUserId] == 'CurrentStudent'):?>
                                <?php echo "Current Student"?>
                                <?php elseif($badges[$commentUserId] == 'Alumni'):?>
                                Alumni
                                <?php elseif($badges[$commentUserId] == 'Official'):?>
                                Official
                                <?php endif;?>
		            <?php endif;?>
                            
                            </b>
                            <b class="flRt"><?=$temp['creationDate'];?></b>
                        </div>
                        <div class="reply-ans-box">
                            <div class="comment-display-box">
                                <p class="comment-title">Comments</p>
                                <?php
                                if(count($message)-1 > 5)
                                {
                                    echo '<a href="javascript:void(0);" onclick="$j(\'.comment-detail\').show();$j(this).hide();">View All '.(count($message)-1).' Comments.</a>';
                                }
                                ?>
                                <div id="commentContainer<?=$mainAnsId?>">
                    <?php
                    }
                    else //for comments
                    {
                    ?>
                                <div class="comment-detail" <?=$hideCommentSection?>>
                                    <p><?php echo formatQNAforQuestionDetailPage($temp['msgTxt'],2500);?></p>
                                    <p><span>Posted by:&nbsp;</span><?=$displayName2?></p>
                                </div>
                    <?php
                    }
                    if($noOfCommentsInThread > 5)
                    {
                        $hideCommentSection = 'style="display:none;"';
                    }
                }
                                $answerFlag = true;
                                //break;
                ?></div>
				<?php if(!$doNoShowAnswerForm){ ?>
                                <div class="comment-field-box" style="padding:8px;">
                                    <span class="caret"></span>
                                    <form method="post" onsubmit="if(validateFields(this) != true){return false;} else { disableReplyFormButton(<?=$msgId?>)};new Ajax.Request('<?=SHIKSHA_HOME?>/messageBoard/MsgBoard/replyMsg/<?=$msgId?>/myshortlist_overlay',{onSuccess:function(request){javascript:addSubCommentForQues(<?=$msgId?>,request.responseText,<?=$mainAnsId?>,'-1'); /*openQnaInOverlay(<?=$main_message['msgId']?>);*/$j('#commentContainer<?=$mainAnsId?>').append(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" action="<?=SHIKSHA_HOME?>/messageBoard/MsgBoard/replyMsg/<?=$msgId?>">
                                        <div class="subContainerAnswer">
                                              <div style="display:block;">
                                                    <input class="comment-field" onclick="$j('#answerBlock-<?=$answerCount?>').animate({scrollTop: $j('#answerBlock-<?=$answerCount?>').offset().top}, 500);try{ showAnswerCommentForm('<?=$msgId?>'); }catch (e){}" id="replyCommentText<?=$msgId?>_hide" class="ftBx" name="replyCommentText<?=$msgId?>_hide" value="Write a comment..."/>
                                              </div>
                                           <div id="hiddenCommentFormPart<?=$msgId?>" style="display: none; overflow: hidden;">
                                              <div class="cntBx">
                                                <div class="float_L wdh100">                                                            
                                                        <div class="wdh100 float_L">
                                                                <div><textarea style="height:30px;" class="answer-textarea" validatesinglechar="true" rows="3" required="true" minlength="3" maxlength="2500" caption="Comment" validate="validateStr" id="replyCommentText<?=$msgId?>" class="ftxArea" onkeyup="textKey(this); checkForNameMention(event,this,'replyCommentText<?=$msgId?>','true');" name="replyCommentText<?=$msgId?>"></textarea></div>
                                                                <div class="Fnt10 fcdGya forSmallFont" style="color:#333;"><span id="replyCommentText<?=$msgId?>_counter">0</span><span> out of 2500 characters</span></div>
                                                                <div style="display:none;" class="errorPlace Fnt11"><div id="replyCommentText<?=$msgId?>_error" class="errorMsg"></div></div>
                                                                <div class="float_L lineSpace_22 Fnt11 fcdGya">Make sure your answer follows <a onclick="return popitup('<?=SHIKSHA_HOME?>/shikshaHelp/ShikshaHelp/communityGuideline');" href="javascript:void(0);">Community Guidelines</a>
                                                                        <span id="loaderDiv" style="margin-left:40px;margin-top:2px;display:none;"><img src="/public/images/working.gif"></span>
                                                                </div>
                                                                <div class="float_R pr10">
                                                                      <input type="Submit" id="submitButton<?=$msgId?>" onclick="" value="Submit" class="orange-button" style="width:auto !important; color: #ffffff;">
                                                                      <a onclick="hideAnswerCommentForm('<?=$msgId?>',true); $j('#replyCommentText<?=$msgId?>').val('');" href="javascript:void(0);">Cancel</a>
                                                                </div>
                                                                <div class="clear_B">&nbsp;</div>
                                                        </div>
                                                </div>
                                              </div>
                                              <div style="display:block" class="errorPlace"><div id="seccode<?=$msgId?>_error" class="errorMsg"></div></div>
                                              <input type="hidden" value="<?php if($temp['sortFlag']=="180000"){echo $main_message['listingTitle']; }else{echo $temp['displayname']; } ?>" name="displayname<?=$msgId?>">
                                              <input type="hidden" value="" name="sortFlag<?=$msgId?>">
                                              <input type="hidden" value="<?=$main_message['msgId']?>" name="threadid<?=$msgId?>">
                                              <input type="hidden" value="" name="dotCount<?=$msgId?>">
                                              <input type="hidden" value="myShortlistAnA_comment" name="page_name">
                                              <input type="hidden" value="user" name="fromOthers<?=$msgId?>">
                                              <input type="hidden" value="<?=$mainAnsId?>" name="mainAnsId<?=$msgId?>">
                                              <input type="hidden" value="<?php echo $temp['userId']; ?>" name="displaynameId<?=$msgId?>">
                                              <input type="hidden" value="addComment" id="actionPerformed<?=$msgId?>" name="actionPerformed<?=$msgId?>">
                                              <input type="hidden" value="-1" id="functionToCall<?=$msgId?>" name="functionToCall<?=$msgId?>">
                                              <input type="hidden" value="<?php echo ($userImageURL != '')?$userImageURL:'/public/images/photoNotAvailable.gif';?>" id="userProfileImage" name="userProfileImage<?=$msgId?>">
                                              <input type="hidden" id="mentionedUsers<?=$msgId?>" value="" name="mentionedUsers<?=$msgId?>">
					      <input type="hidden" value="<?=$main_message['listingTypeId']?>" name="institute_id">
					      <input type="hidden" value="<?=$courseId; ?>" name="course_id">
                                           </div>
                                        </div>
                                  </form>
                                </div>
				<?php } ?>
                            </div>
                        </div>
                      </div>  
                <?php
                echo '</div>';//end of .answerBlocks div
                //break;
            }
            ?>
        </dd>
    </dl>
    <?php
    if($answerCount > 1)
    {
    ?>
    <div class="new-pagination clearfix">
        <ul>
            <li class="prev" id="showPrevAnswer"><a class="inactive" href="javascript:void(0);"><span>&lsaquo;</span> Back</a></li>
            <?php
            $makeFirstActive = true;
            for($i=1; $i<=$answerCount; $i++)
            {
            ?>
                <li class="goToPage <?=(($makeFirstActive)?'active':'')?>" id="page-<?=$i?>" lid="<?=$i?>" style="<?=($i>3)?'display:none;':''?>"><a href="javascript:void(0);"><?=$i?></a></li>
            <?php
                $makeFirstActive = false;
            }
            ?>
            <li class="next" id="showNextAnswer"><a href="javascript:void(0);">Next <span>&rsaquo;</span></a></li>
        </ul>
    </div>
    <?php
    }
    ?>
    <script>
        var currentActiveAnswer = 1;
        var pageWindow = 3;
        var totalPages = <?=$answerCount?>;
        $j('.goToPage').click(function(){
            var block = $j(this).attr('lid');
            $j('.answerBlocks').hide();
            $j('#answerBlock-'+block).show();
            $j('.goToPage').removeClass('active');
            $j(this).addClass('active');
            currentActiveAnswer = block;
            if (currentActiveAnswer > 1 && currentActiveAnswer < totalPages) {
                //alert('here');
                $j('#showPrevAnswer').find('a').removeClass('inactive');
                $j('#showNextAnswer').find('a').removeClass('inactive');
            }
            if (currentActiveAnswer <= 1) {
                $j('#showPrevAnswer').find('a').addClass('inactive');
                $j('#showNextAnswer').find('a').removeClass('inactive');
            }
            if (currentActiveAnswer >= totalPages) {
                $j('#showNextAnswer').find('a').addClass('inactive');
                $j('#showPrevAnswer').find('a').removeClass('inactive');
            }
            //alert(block);
            createMiddlePaginationView(totalPages, block);
        });
        $j('#showNextAnswer').click(function(){
            //alert(currentActiveAnswer);
            if ($j(this).find('a').hasClass('inactive')) {
                //alert('return next');
                return;
            }
            
            currentActiveAnswer++;
            block = currentActiveAnswer;
            
            $j('.answerBlocks').hide();
            $j('#answerBlock-'+block).show();
            $j('.goToPage').removeClass('active');
            $j('#page-'+block).addClass('active');
            
            $j('#showPrevAnswer').find('a').removeClass('inactive');
            if (block >= totalPages) {
                $j(this).find('a').addClass('inactive');
            }
            else {
                $j(this).find('a').removeClass('inactive');
            }
            createMiddlePaginationView(totalPages, block);
        });
        $j('#showPrevAnswer').click(function(){
            //alert(currentActiveAnswer);
            if ($j(this).find('a').hasClass('inactive')) {
                //alert('return prev');
                return;
            }
            
            currentActiveAnswer--;
            block = currentActiveAnswer;
            
            $j('.answerBlocks').hide();
            $j('#answerBlock-'+block).show();
            $j('.goToPage').removeClass('active');
            $j('#page-'+block).addClass('active');
            
            $j('#showNextAnswer').find('a').removeClass('inactive');
            if (block <= 1) {
                $j(this).find('a').addClass('inactive');
            }
            else {
                $j(this).find('a').removeClass('inactive');
            }
            createMiddlePaginationView(totalPages, block);
        });
        function createMiddlePaginationView(totalPages, currentPage)
        {
            if (currentPage != 1 && currentPage != totalPages && totalPages > pageWindow) {
                //alert(currentPage);
                $j('.goToPage').hide();
                $j('#page-'+parseInt(currentPage)).show();
                $j('#page-'+(parseInt(currentPage)+1)).show();
                $j('#page-'+(parseInt(currentPage)-1)).show();
            }
            else if (currentPage == 1) {
                //alert(currentPage);
                $j('.goToPage').hide();
                $j('#page-'+parseInt(currentPage)).show();
                $j('#page-'+(parseInt(currentPage)+1)).show();
                $j('#page-'+(parseInt(currentPage)+2)).show();
            }
            else if (currentPage == totalPages) {
                $j('.goToPage').hide();
                $j('#page-'+parseInt(currentPage)).show();
                $j('#page-'+(parseInt(currentPage)-1)).show();
                $j('#page-'+(parseInt(currentPage)-2)).show();
            }
        }
    </script>

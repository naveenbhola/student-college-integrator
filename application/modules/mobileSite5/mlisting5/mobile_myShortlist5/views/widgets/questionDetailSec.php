<?php
      // _p("prev : ".$prevQuestion);
      // _p("next : ".$nextQuestion);
      $questionData              = $formatedData['data'][$questionId];
      $questionPosteddFromUserId = $formatedData['data'][$questionId]['userId'];

      $answerPostedByUsers = array();
      foreach ($formatedData['answers'] as $key => $value) {
        $answerPostedByUsers[] = $value['data']['userId'];
      }
      // _p($detailPageData);die;

?>
<section class="listing-tupple">
      <div class="college-detail-review-list">
        <ul>
          <li>
            <div class="college-review-head2">
              <p><b><?php echo html_escape($insObj->getName()) ?></b><br>
                <span><?php echo $insObj->getMainLocation()->getCity()->getName();?></span></p>
              <p class="college-review-title"><strong>Previously asked questions (<?php echo $totalQuestions;?>)</strong></p> </div>
              <h2 class="ans-title">Question</span></h2>
            <div class="mys-qnaPart">
              <div class="all-qna" style="margin-top:0px;">
                <dl style="border: 0px none; margin: 0px; padding: 0px;">
                  <dt>
                    <div class="ques-icon">Q</div>
                      <div class="camp-qna-details">
                        <p style="font-weight:bold;"><?php echo $questionData['data']['title']?></p>
                        <p class="posted-info clearfix"> <span>
                          <label>Posted by:</label>
                          <?php echo $questionData['data']['firstname']." ".$questionData['data']['lastname']?></span> <span><?php echo makeRelativeTime($questionData['data']['creationDate'])?> </span> </p>
                          <p style="background:#fefbcd; padding:2px 5px; width:auto; font-size:12px;display:none;" id="success<?php echo $questionId;?>_msg"></p>
                          <?php
                            if($userId!=$questionPosteddFromUserId && !in_array($userId, $answerPostedByUsers) && $userGroup!='cms'){
                              if(!$doNoShowAnswerForm){
                          ?>
                              <a class="button blue small _rply_btn" id="_rply_btn<?php echo $questionId;?>" href="javascript:void(0);" style="margin-top:2px" onclick="showReplyCommentBox(this,'Answer this question','<?php echo $questionId;?>');"><span style="font-size:16px;margin-top:0px;">Answer this question</span></a>
                          <?php
                              }
                            }
                          ?>
                          <?php
                              $data['viewType'] = 'reply';
                              $data['buttonName'] = 'Answer this question';
                              $data['msgId'] = $questionId;
                              $data['pageName'] = 'QuestionDetailPage';
                              $this->load->view('mAnA5/campusRep/commentReplyPage',$data);
                          ?>
                          <div class="clearfix"></div>
                      </div>
                  </dt>
                  </dl>
                  </div>
                  </div>
                  <?php
                  if(!empty($detailPageData['answer']))
                  {
                  ?>
                  <h2 class="ans-title">Answers <span style="display:inline;">(<?php echo $totalAnswerCount;?>)</span></h2>
                  
                  <div class="mys-qnaPart">
              <div class="all-qna" style="margin-top:0px;">
              <dl style="border: 0px none; margin: 0px; padding: 0px;">
                  <?php
                  $count = 0;
                  foreach ($detailPageData['answer'] as $key => $value) {
                    $remainingCommentCount = count($value['comment'])-1;
                    $count++;
                  ?>
                  <input type="hidden" id="remainingCommentCount_<?=$key?>" value="<?php echo $remainingCommentCount;?>" />
                  <dd style="display: inline-block; padding: 10px 0px; <?php echo (count($detailPageData['answer'])>$count) ? 'border-bottom: 1px solid #eee;' : '' ?>">
                    <div class="ans-icon">A</div>
                    <div class="camp-qna-details">
                      <?php
                        $answerText =  $value['msgTxt'];
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
                            <p id="shortened<?=$value['msgId']?>"><?php echo $answerTextShortened;?><a id="expandLink<?=$value['msgId']?>" href='javascript:void(0)' onClick='expandAnswer("<?=$value['msgId']?>");'>...Read More</a></p>
                            <p id="expanded<?=$value['msgId']?>" style="display: none;"><?php echo $answerText;?></p>
                        <?php }else{ ?> 
                            <p><?php echo $answerText;?></p>
                        <?php } ?>
                      <p class="posted-info clearfix"> <span>
                        <label>Posted by:</label>
                        <?php echo $value['postedByUser'];?>
                        <?php if(array_key_exists($value['userId'],$formattedCAData)){ ?>
                          <a href="javascript:void(0);" class="current-stu-btn" style="cursor: default;"><?php echo $formattedCAData[$value['userId']];?></a></span>
                <?php } ?></span> <span><?php echo $value['postedAt'];?></span></p>

                      <?php
                        $commentCounter = 0;
                        if(count($value['comment'])>1 && $commentCounter==0){ ?>
                        <p id="moreCommentLink_<?php echo $key;?>" class="morelinks"><a style="line-height: 12px;" href="javascript:void(0);" onclick="showHideComment('<?php echo $key;?>');"><i class="sprite comment-icon"></i> <span id="remainCountDigit_<?php echo $key;?>" style="display:inline-block;"><?php echo $remainingCommentCount; ?></span> Comment<span class="pluralComment" id="pluralComment_<?php echo $key;?>"><?php echo ($remainingCommentCount>1)?'s':'';?></span></a></p>
                      <?php } ?>
                      
                      <?php foreach($value['comment'] as $k=>$v){
                      ?>
                        <div class="posted-ans-detail hideComment_<?php echo $key;?>" <?php if($commentCounter!=$remainingCommentCount){ echo 'style="display:none;"';}?>>
                       
                            <?php
                            $commentText =  $v['msgTxt'];
                            $commentText = html_entity_decode(html_entity_decode($commentText,ENT_NOQUOTES,'UTF-8'));
                            $commentText = formatQNAforQuestionDetailPage($commentText,300);
                            $showShortComment = false;
                            $commentTextShortened = '';
                            
                            $htmlTagsRemovedCommentText = strip_tags($commentText);
                            if(strlen($htmlTagsRemovedCommentText)>120){
                                    $showShortComment = true;
                                    $commentTextShortened = substr($htmlTagsRemovedCommentText,0,120);
                            }
                            ?>
                            <?php if($showShortComment){ ?>
                                <p id="shortened<?=$v['msgId']?>"><?php echo $commentTextShortened;?><a id="expandLink<?=$v['msgId']?>" href='javascript:void(0)' onClick='expandAnswer("<?=$v['msgId']?>");'>...Read More</a></p>
                                <p id="expanded<?=$v['msgId']?>" style="display: none;"><?php echo $commentText;?></p>
                            <?php }else{ ?> 
                                <p><?php echo $commentText;?></p>
                            <?php } ?>
                            <p class="posted-info clearfix">
                            <span><label>Posted by:</label> <?php echo $v['postedByUser'];?></span>
                            <span><?php echo $v['postedAt'];?> </span>
                            </p>
                        </div>
                        <?php $commentCounter++; } ?>
                        <p style="background:#fefbcd; padding:2px 5px; width:auto; font-size:12px;display:none;" id="success<?php echo $value['msgId'];?>_msg"></p>

                      <?php if(!$doNoShowAnswerForm){ ?><a class="button blue small flLt _rply_btn" id="_rply_btn<?php echo $value['msgId'];?>" href="javascript:void(0);"   onclick="showReplyCommentBox(this,'Comment','<?php echo $value['msgId'];?>');"><span style="font-size:16px;margin-top:0px;">Comment</span></a><?php } ?>

                        <?php
            $data['viewType'] = 'comment';
            $data['buttonName'] = 'Comment';
            $data['msgId']    = $value['msgId'];
            $data['threadId'] = $questionId;
            $data['pageName'] = 'QuestionDetailPage';
            $this->load->view('mAnA5/campusRep/commentReplyPage',$data);?>

                        </div>
                  </dd>
                  <?php
                }
                  ?>
                  <div class="clearfix"></div>
                </dl>
              </div>
            </div>
            <?php
                  }
                  ?>
          </li>
        </ul>
        <?php
        if($prevQuestion)
        { ?>
        <a href="/mobile_myShortlist5/MyShortlistMobile/showQuestionDetailPage/<?php echo $courseId;?>/<?php echo $prevQuestion;?>"><div class="slide-prev-2"><i class="msprite prev-icon-2"></i></div></a>
        <?php
        }
        if($nextQuestion)
        {
        ?>
        <a href="/mobile_myShortlist5/MyShortlistMobile/showQuestionDetailPage/<?php echo $courseId;?>/<?php echo $nextQuestion;?>"><div class="slide-next-2"><i class="msprite next-icon-2"></i></div></a>
        <?php
        }?>
      </div>
    </section>
<script>
    function showHideComment(id) {
        $('.hideComment_'+id).show();
        $('#moreCommentLink_'+id).hide();
    }
</script>
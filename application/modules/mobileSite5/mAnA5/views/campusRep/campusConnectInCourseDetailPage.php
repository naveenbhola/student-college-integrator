<?php 
if($questionType == 'All')
{
    $GA_TapOnAnswer = 'WRITEANSWER_QUEST_COURSELISTING_WEBAnA';
    $GA_TapOnAnswerToQDP = 'ANSWER_QUEST_COURSELISTING_WEBAnA';
    $GA_TapOnViewMore = 'VIEWMORE_ANSWER_COURSELISTING_WEBAnA';
    $GA_TapOnFollow = 'FOLLOW_QUEST_COURSELISTING_WEBAnA';
    $GA_TapOnShare = 'SHARE_QUEST_COURSELISTING_WEBAnA';
    $GA_TapOnProfileName = 'PROFILE_ANSWER_COURSELISTING_WEBAnA';
    $GA_TapOnTag = 'TAG_QUEST_COURSELISTING_WEBAnA';
    $GA_TapOnQuestion = 'QUESTTITLE_QUEST_COURSELISTING_WEBAnA';
    $GA_TapOnUpvote = 'UPVOTE_ANSWER_COURSELISTING_WEBAnA';
    $GA_TapOnDownVote = 'DOWNVOTE_ANSWER_COURSELISTING_WEBAnA';
}
else
{
  $GA_TapOnAnswer = 'WRITEANSWER_UNANQUEST_COURSELISTING_WEBAnA';
  $GA_TapOnFollow = 'FOLLOW_UNANQUEST_COURSELISTING_WEBAnA';
  $GA_TapOnShare = 'SHARE_UNANQUEST_COURSELISTING_WEBAnA';
  $GA_TapOnTag = 'TAG_UNANQUEST_COURSELISTING_WEBAnA';
  $GA_TapOnQuestion ='QUESTTITLE_UNANQUEST_COURSELISTING_WEBAnA';
}
?>
<?php foreach ($qna as $qnaKey => $qnaValue) { ?>
                  <div class="card-data" tracking="true" questionid="<?php echo $qnaValue['data']['threadId'];?>" answerid="<?php echo $qnaValue['answers'] ? $qnaValue['answers']['msgId']: '0' ;?>" type="Q">
                <div class="type-of-que">
                 <div style="overflow: hidden;white-space: nowrap;">
                                          <?php if(! empty($qnaValue['data']['tags'])){
                                                  $len = 0;
                                                  $limit = 50;
                                                  foreach($qnaValue['data']['tags'] as $tagKey=>$tagValue){
                                                            $len += strlen($tagValue['name']);
                                                            if($len < $limit){
                                                              ?>
                                                                  <a href="<?php echo $tagValue['url'];?>" class="a" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnTag;?>','<?php echo $GA_userLevel;?>')"><?php echo $tagValue['name']?></a>
                                                              <?php
                                                              } else {
                                                                      $len -= strlen($tagValue['name']);
                                                                      ?>
                                                                        <a href="<?php echo $tagValue['url'];?>" class="a" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnTag;?>','<?php echo $GA_userLevel;?>')"> 

                                                                          <?php
                                                                          echo substr($tagValue['name'],0,($limit - 3 - $len))."...";
                                                                          ?>
                                                                          </a>
                                                                          <?php
                                                                            break;
                                                                          ?>
                                                                        
                                                                      <?php
                                                                  }
                                                                  ?>                        
                                              <?php }} ?>
                                      </div>
                
                <a class="d-txt" href="<?=$qnaValue['data']['q_url'];?>" id="quesTitle_<?=$qnaValue['data']['msgId']?>" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnQuestion;?>','<?php echo $GA_userLevel;?>')"><?php echo $qnaValue['data']['title']; ?></a>
                  <div class="" style="position:relative;display:table;width:100%">
                  <span class="a-span">
                      <?php 

                            $answerCountText = $qnaValue['data']['msgCount'] > 1 ? 'Answers': 'Answer';
                            $followerCountText = $qnaValue['data']['followCount'] > 1 ? 'Followers' : 'Follower';
                            $viewCountText = $qnaValue['data']['viewCount'] > 1 ? 'Views' : 'View';
                            $qnaInformation = '';
                            if($qnaValue['data']['msgCount'] > 0)
                              {
                                  $qnaInformation .= $qnaValue['data']['msgCount'].' '.$answerCountText;
                              }
                              if($qnaValue['data']['followCount'] > 0)
                              {
                                  if(!empty($qnaInformation))
                                  {
                                      $qnaInformation .= '<span> . </span>';
                                  } 
                                   $qnaInformation .= $qnaValue['data']['followCount'].' '.$followerCountText;
                              }
                              if($qnaValue['data']['viewCount'] > 0)
                              {
                                  if(!empty($qnaInformation))
                                  {
                                      $qnaInformation .= '<span> . </span>';
                                  }
                                  $qnaInformation .= $qnaValue['data']['viewCount'].' '.$viewCountText;
                              }
                              echo $qnaInformation;
                              ?>
                  
                   </span>
                    <span class="time-view">
                    <i class="clock-ico"></i><?php echo makeRelativeTime($qnaValue['data']["creationDate"]); ?>
                  </span>
                  </div>
                  <p class="clr"></p>
                
              </div>
              <div class="box-col">
              <?php if(isset($qnaValue['answers'])){
                ?>
                <div>
                  <a class="user-list-dtls" href="<?php echo $qnaValue['answers']['userProfileUrl']?>" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnProfileName;?>','<?php echo $GA_userLevel;?>')">
                     <div class="user-pic-col">
                      <?php 

                      if($qnaValue['answers']['userPicUrl'] != '' && strpos($qnaValue['answers']['userPicUrl'],'/public/images/photoNotAvailable.gif') === false){?>
                          <img src=<?php echo getSmallImage($qnaValue['answers']['userPicUrl']);?> alt="Shiksha Ask & Answer" style="width: 55px;height: 55px;">

                      <?php }else{
                          echo ucwords(substr($qnaValue['answers']['firstname'],0,1));
                      }?>
                     </div>
                      <div class="user-inf-col">
                        <p class="name"><?php echo $qnaValue['answers']['firstname'].' '.$qnaValue['answers']['lastname']; ?></p>
                        <p class="e-level"><?php echo $qnaValue['answers']['userLevel']?$qnaValue['answers']['userLevel']:'Beginner-Level 1'; ?></p>
                        <p class="e-level"><?php echo $qnaValue['answers']['aboutMe'] ? $qnaValue['answers']['aboutMe'] : 'Current Student' ;?></p>
                      </div>
                      <p class="clr"></p> 
                  </a>
                  <div class="user-review" id="answerTxt_<?=$qnaValue['data']['msgId'];?>">
                  <?php 
                    $ansTextlen = strlen(strip_tags($qnaValue['answers']['title']));
                    if($ansTextlen > 300){
                        echo substr(strip_tags($qnaValue['answers']['title']),0,300).'...';    
                    }else{
                        echo sanitizeAnAMessageText($qnaValue['answers']['title'],'answer'); 
                    }
                  ?>
                  </div>

                  

                  <?php if($ansTextlen > 300){?>
                  <div class="user-review" id="answerfullTxt_<?=$qnaValue['data']['msgId'];?>" style = "display:none;"><?php echo sanitizeAnAMessageText($qnaValue['answers']['title'],'answer'); ?></div>
                  <a href="javascript:void(0)" class="box-view" id="viewMoreBtn_<?=$qnaValue['data']['msgId']?>" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnViewMore;?>','<?php echo $GA_userLevel;?>');viewFullAnswerText('<?php echo $qnaValue['data']['msgId']?>')">View More</a>
                  <?php } ?>

                  <?php if($qnaValue['answers']['hasUserVotedUp'] == 'true'){
                          $upvotedClass = 'like-actv-ico';
                          $downvotedClass = 'disable-unlike';
                          $upvotedReverseClass = 'like-ico';
                          $downvotedReverseClass = 'dislike-act-ico';
                          $upvotedDisableClass = 'disable-like';
                          $downvotedDisableClass = 'dislike-ico';
                          $upvotedAction = 'cancelupvote';
                          $downvotedAction = 'noaction';
                        }else if($qnaValue['answers']['hasUserVotedDown'] == 'true'){
                          $upvotedClass = 'disable-like';
                          $downvotedClass = 'dislike-act-ico';
                          $upvotedReverseClass = 'like-actv-ico';
                          $downvotedReverseClass = 'dislike-ico';
                          $upvotedDisableClass = 'like-ico';
                          $downvotedDisableClass = 'disable-unlike';
                          $upvotedAction = 'noaction';
                          $downvotedAction = 'canceldownvote';
                        }else{
                          $upvotedClass = 'like-ico';
                          $downvotedClass = 'dislike-ico';
                          $upvotedReverseClass = 'like-actv-ico';
                          $downvotedReverseClass = 'dislike-act-ico';
                          $upvotedDisableClass = 'disable-like';
                          $downvotedDisableClass = 'disable-unlike';
                          $upvotedAction = 'upvote';
                          $downvotedAction = 'downvote';
                        }
                    ?>
                  <div class="like-col"> 
                    <a class="like-a" href="javascript:void(0)" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnUpvote;?>','<?php echo $GA_userLevel;?>');setEntityRating(this,'<?php echo $qnaValue['answers']['msgId']?>','answer',false,'<?php echo $tupaTrackingPageKeyId;?>')" callforaction="<?php echo $upvotedAction;?>"><i class="<?php echo $upvotedClass;?>" reverseclass="<?php echo $upvotedReverseClass;?>" disabledclass="<?php echo $upvotedDisableClass;?>">&nbsp;</i><?php echo $qnaValue['answers']['digUp']; ?></a>
                    <a class="like-d" href="javascript:void(0)" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnDownVote;?>','<?php echo $GA_userLevel;?>');setEntityRating(this,'<?php echo $qnaValue['answers']['msgId']?>','answer',false,'<?php echo $tdownTrackingPageKeyId;?>')" callforaction="<?php echo $downvotedAction;?>"><i class="<?php echo $downvotedClass;?>" reverseclass="<?php echo $downvotedReverseClass;?>" disabledclass="<?php echo $downvotedDisableClass;?>"></i><?php echo $qnaValue['answers']['digdown']; ?></a>
                  </div>
                </div>
              <?php } ?>
                  <div class="bottom-col">

                  <?php
                  
                            if($qnaValue['data']['isThreadOwner'] == 'true'){?>
                                <a class="cmnt-btn" href="javascript:void(0)" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnAnswer;?>','<?php echo $GA_userLevel;?>');showAnswerResponseMessage('You cannot answer your own question.')">Answer</a>

                            <?php }else if($qnaValue['data']['hasUserAnswered'] == 'true'){ ?>
                                <a class="cmnt-btn" href="javascript:void(0)" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnAnswer;?>','<?php echo $GA_userLevel;?>');showAnswerResponseMessage('You cannot answer more than once on the same question.')">Answer</a>

                            <?php }else if($qnaValue['data']['status'] == 'closed'){?>
                                <a class="cmnt-btn" href="javascript:void(0)" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnAnswer;?>','<?php echo $GA_userLevel;?>');showAnswerResponseMessage('This question is closed for answering.')">Answer</a>

                            <?php }else{?>
                                <a class="cmnt-btn" href="#answerPostingLayerDiv" data-inline="true" data-rel="dialog" data-transition="fade" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnAnswer;?>','<?php echo $GA_userLevel;?>');makeAnswerPostingLayer('<?=$qnaValue['data']['msgId']?>','0','add','<?php echo $atrackingPageKeyId;?>')">Answer</a>

                      <?php }?>

                     
                     <?php if($qnaValue['data']['isUserFollowing'] == 'true'){ ?>
                          <a class="u-flw-txt" reverseclass="flw-txt" href="javascript:void(0)" callforaction="unfollow" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnFollow;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $qnaValue['data']['msgId'];?>,'question',false,'<?php echo $fqTrackingPageKeyId;?>')">unfollow</a>
                    <?php }else{?>
                          <a class="flw-txt" reverseclass="u-flw-txt" href="javascript:void(0)" callforaction="follow" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnFollow;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $qnaValue['data']['msgId'];?>,'question',false,'<?php echo $fqTrackingPageKeyId;?>')">follow</a>
                    <?php } ?>     
                     <a class="share-tag" href="javascript:void(0)" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnShare;?>','<?php echo $GA_userLevel;?>');mngPage('<?=urlencode($qnaValue['data']['q_url'])?>','Checkout this Question on shiksha','<?=$qnaValue['data']['msgId']?>','question');"><i class="share-ico"></i></a>
                  </div>
              </div>
      </div>
      <input type="hidden" name="quesOwnerName<?=$qnaValue['data']['msgId']?>"  id="quesOwnerName<?=$qnaValue['data']['msgId']?>" value="<?=$qnaValue['data']['firstname'].' '.$qnaValue['data']['lastname'];?>" />
      <?php }?>
       <?php 
       if(count($qna) < $pageSize){ //Questions have exhausted. No more questions
              echo "<input type='hidden' name='areQuestionExhausted".$pageNo."' id='areQuestionExhausted".$pageNo."' value='true'>";
        }
       ?>

<script>
closeDropDown();
function closeDropDown(){
     $(document).click(function(e){
     var container =  $('#sortOptionTab');
     var container2 = $('.qa-dropdown');
     if (!container.is(e.target) && container.has(e.target).length === 0 && !container2.is(e.target) && container2.has(e.target).length === 0)
      {   
        if($('#sortOptionTab').is(":visible") == true){
            $('#sortOptionTab').hide();
        }
      }

    });
}
</script>

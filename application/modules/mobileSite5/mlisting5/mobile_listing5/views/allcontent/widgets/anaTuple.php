<?php

$flag=0;
foreach($data['homepage'] as $key=>$homeTabData){

  if($key==3 && !$pdf){
    $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_ANA_LAA"));
        $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_ANA_LAA1"));
        $flag=1;
  }
  if($key==6 && !$pdf){
    $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_ANA_AON"));
        $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_ANA_AON1"));
  }


	if($key == 8 && !$pdf){
	 ?>
	    <div id='askProposition' style="display:none;">
        	 <div style='text-align: center; margin-top: 10px; margin-bottom: 10px;'><img border='0' alt='' id='loadingImage1' class='small-loader' style='border-radius:50%;width: 60px;border: 1px solid rgb(229, 230, 230)' src='//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif'/></div>
	    </div>           
	<?php }

 	if($key == 4 && !$pdf){
		echo $recoWidget;
	}

      $GA_currentPage = 'ALL QUESTIONS PAGE';

    	if($homeTabData['type'] == 'Q'){
        $actionType = 'answer';
        $threadType = 'question';

        if($homeTabData['answerOwnerName'] == ''){
            $GA_tapChildCta = 'WRITEANSWER_UNANQUEST';
            $GA_tapFollow = 'FOLLOW_UNANQUEST';
            $GA_tap_Share = 'SHARE_UNANQUEST';
            $GA_tapTagName = 'TAG_UNANQUEST';
            $GA_tapEntity = 'QUESTTITLE_UNANQUEST';
            $followTrackingPageKeyId = $followUnQuesTrackingPageKeyId;
        }else{
            $GA_tapChildCta = 'WRITEANSWER_QUEST';
            $GA_tapChild = 'ANSWER_QUEST';
            $GA_tapViewMore = 'VIEWMORE_ANSWER';
            $GA_tapFollow = 'FOLLOW_QUEST';
            $GA_tap_Share = 'SHARE_QUEST';
            $GA_tapProfileName = 'PROFILE_ANSWER';
            $GA_tapTagName = 'TAG_QUEST';
            $GA_tapEntity = 'QUESTTITLE_QUEST';
            $GA_tapUpvote = 'UPVOTE_ANSWER';
            $GA_tapDownvote = 'DOWNVOTE_ANSWER';
            $followTrackingPageKeyId = $followQuesTrackingPageKeyId;
        }

    	}else{
    		$actionType = 'comment';
    		$threadType = 'discussion';
        $GA_tapChildCta = 'WRITECOMMENT_DISC';
        $GA_tapChild = 'COMMENT_DISC';
        $GA_tapViewMore = 'VIEWMORE_COMMENT';
        $GA_tapFollow = 'FOLLOW_DISC';
        $GA_tap_Share = 'SHARE_DISC';
        $GA_tapProfileName = 'PROFILE_COMMENT';
        $GA_tapTagName = 'TAG_DISC';
        $GA_tapEntity = 'DISCTITLE_DISC';
        $GA_tapUpvote = 'UPVOTE_COMMENT';
        $GA_tapDownvote = 'DOWNVOTE_COMMENT';

        $followTrackingPageKeyId = $followDiscTrackingPageKeyId;

    	}

	$homeTabData['questionId'] = $homeTabData['id'];
?>
          <div class="card-data" questionid="<?php echo $homeTabData['questionId']?>" answerid="<?php echo $homeTabData['answerId'] ? $homeTabData['answerId'] : 0;?>" type="<?php echo $homeTabData['type']?>" tracking="true">
              <div class="type-of-que">
                
                <a class="d-txt" href="<?=$homeTabData['URL'];?>" id="quesTitle_<?=$homeTabData['questionId']?>" ga-attr="<?=$GA_tapEntity;?>"><?php echo $homeTabData['title']; ?></a>

                  <span class="a-span">
                      <?php 

                            if($homeTabData['type'] == 'Q'){
                              if($homeTabData['answerCount']== 1){
                                  $answerCountText = 'Answer';
                              }else{
                                  $answerCountText = 'Answers';
                              }
                            }else if($homeTabData['type'] == 'D'){
                              if($homeTabData['answerCount']== 1){
                                  $answerCountText = 'Comment';
                              }else{
                                  $answerCountText = 'Comments';
                              }
                            }

                            $viewCountText = ($homeTabData['viewCount']== 1) ? 'View' : 'Views';
                            $followerCountText = ($homeTabData['followerCount']== 1) ? 'Follower' : 'Followers';
                       ?>


                      <?php if($homeTabData['answerCount'] > 0 && $homeTabData['viewCount'] == 0 && $homeTabData['followerCount'] == 0){ ?>  
                                <?php echo $homeTabData['answerCount'].' '.$answerCountText; ?>
                      <?php }else if($homeTabData['answerCount'] == 0 && $homeTabData['viewCount'] > 0 && $homeTabData['followerCount'] == 0){?>
                                <?php echo $homeTabData['viewCount'].' '.$viewCountText; ?>
                      <?php }else if($homeTabData['answerCount'] == 0 && $homeTabData['viewCount'] == 0 && $homeTabData['followerCount'] > 0){?>
                                <?php echo $homeTabData['followerCount'].' '.$followerCountText; ?>
                      <?php }else if($homeTabData['answerCount'] > 0 && $homeTabData['viewCount'] > 0 && $homeTabData['followerCount'] == 0){?>
                                <?php echo $homeTabData['answerCount'].' '.$answerCountText; ?><span> .</span>
                                <?php echo $homeTabData['viewCount'].' '.$viewCountText; ?>
                      <?php }else if($homeTabData['answerCount'] > 0 && $homeTabData['viewCount'] == 0 && $homeTabData['followerCount'] > 0){?>
                                <?php echo $homeTabData['answerCount'].' '.$answerCountText; ?><span> .</span>
                                <?php echo $homeTabData['followerCount'].' '.$followerCountText; ?>
                      <?php }else if($homeTabData['answerCount'] == 0 && $homeTabData['viewCount'] > 0 && $homeTabData['followerCount'] > 0){ ?>
                                <?php echo $homeTabData['followerCount'].' '.$followerCountText; ?><span> .</span>
                                <?php echo $homeTabData['viewCount'].' '.$viewCountText; ?>
                      <?php }else if($homeTabData['answerCount'] > 0 && $homeTabData['viewCount'] > 0 && $homeTabData['followerCount'] > 0){ ?>
                                <?php echo $homeTabData['answerCount'].' '.$answerCountText; ?><span> .</span>
                                <?php echo $homeTabData['followerCount'].' '.$followerCountText; ?><span> .</span>
                                <?php echo $homeTabData['viewCount'].' '.$viewCountText; ?>
                      <?php } ?>

                   </span>
              </div>
              
              
              <div class="box-col">
              <?php if(isset($homeTabData['answerOwnerName']) && $homeTabData['answerOwnerName'] != ''){
			$userProfileURL = getSeoUrl($homeTabData['answerOwnerUserId'],'userprofile');
		?>
                <div>
                  <a class="user-list-dtls" href="<?=$userProfileURL?>" ga-attr="<?=$GA_tapProfileName;?>">
                      <span <?php if($homeTabData['type'] == 'D'){ ?> style="margin-right:20px;" <?php } ?>  class="time-stmp">

                  <?php if(trim($homeTabData['activityTime']) != "") { ?>
                  <i class="qa-sprite clock-ico"></i><?php echo $homeTabData['activityTime']; ?>
                  <?php } ?>
                  </span>
                     <div class="user-pic-col">
                      <?php 

                      if($homeTabData['answerOwnerImage'] != '' && strpos($homeTabData['answerOwnerImage'],'/public/images/photoNotAvailable.gif') === false){?>
                          <img src=<?php echo getSmallImage($homeTabData['answerOwnerImage']);?> alt="Shiksha Ask & Answer" style="width: 55px;height: 55px;">

                      <?php }else{
                          echo ucwords(substr($homeTabData['answerOwnerName'],0,1));
                      }?>
                     </div>
                      <div class="user-inf-col">
                        <p class="name"><?php echo $homeTabData['answerOwnerName']; ?></p>
			<p class="currentStu e-level" style="display:none;" id="currentStudent_<?=$homeTabData['answerOwnerUserId']?>">Current Student</span>
                        <p class="e-level"><?php echo $homeTabData['answerOwnerLevel']; ?></p>
                        <?php if(isset($homeTabData['aboutMe']) && $homeTabData['aboutMe'] != ''){?>
                        <p class="e-level"><?php echo $homeTabData['aboutMe']; ?></p>
                        <?php } ?>
                      </div>
                      <p class="clr"></p> 
                  </a>
                  <div class="user-review" id="answerTxt_<?=$homeTabData['questionId'];?>">
                  <?php 
                    $ansTextlen = strlen(strip_tags($homeTabData['answerText']));
                    if($ansTextlen > 300 && !$pdf){
                        echo substr(strip_tags($homeTabData['answerText']),0,300).'...';    
                    }else{
                        echo $homeTabData['answerText']; 
                    }
                  ?>
                  </div>

                  

                  <?php if($ansTextlen > 300 && !$pdf){?>
                  <div class="user-review" id="answerfullTxt_<?=$homeTabData['questionId'];?>" style = "display:none;"><?php echo $homeTabData['answerText']; ?></div>
                  <a href="javascript:void(0)" class="link" id="viewMoreBtn_<?=$homeTabData['questionId']?>" ga-attr="<?=$GA_tapViewMore;?>" onclick="viewFullAnswerText('<?=$homeTabData['questionId']?>')">View More</a>
                  <?php } ?>

                  <?php if($homeTabData['hasUserVotedUp'] == 'true'){
                          $upvotedClass = 'like-actv-ico';
                          $downvotedClass = 'disable-unlike';
                          $upvotedReverseClass = 'like-ico';
                          $downvotedReverseClass = 'dislike-act-ico';
                          $upvotedDisableClass = 'disable-like';
                          $downvotedDisableClass = 'dislike-ico';
                          $upvotedAction = 'cancelupvote';
                          $downvotedAction = 'noaction';
                        }else if($homeTabData['hasUserVotedDown'] == 'true'){
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
                    <?php
                    if($actionType == 'comment')
                    {
                      $tuptrackingPageKeyId = $tupdctrackingPageKeyId;
                      $tdowntrackingPageKeyId = $tdowndctrackingPageKeyId;
                    }
                    elseif($actionType=='answer')
                    {
                        $tuptrackingPageKeyId = $tupatrackingPageKeyId;
                        $tdowntrackingPageKeyId = $tdownatrackingPageKeyId;
                    }
                    ?>
                  <div class="like-col pdf_displayNone"> 
                    <a class="like-a" href="javascript:void(0)" ga-attr="<?=$GA_tapUpvote;?>" onclick="setEntityRating(this,'<?php echo $homeTabData['answerId']?>','<?php echo $actionType;?>',false,'<?php echo $tuptrackingPageKeyId;?>')" callforaction="<?php echo $upvotedAction;?>"><i class="<?php echo $upvotedClass;?>" reverseclass="<?php echo $upvotedReverseClass;?>" disabledclass="<?php echo $upvotedDisableClass;?>">&nbsp;</i><?php echo $homeTabData['likeCount']; ?></a>
                    <a class="like-d" href="javascript:void(0)" ga-attr="<?=$GA_tapDownvote;?>" onclick="setEntityRating(this,'<?php echo $homeTabData['answerId']?>','<?php echo $actionType;?>',false,'<?php echo $tdowntrackingPageKeyId;?>')" callforaction="<?php echo $downvotedAction;?>"><i class="<?php echo $downvotedClass;?>" reverseclass="<?php echo $downvotedReverseClass;?>" disabledclass="<?php echo $downvotedDisableClass;?>"></i><?php echo $homeTabData['dislikeCount']; ?></a>
                  </div>
                </div>
              <?php } ?>
                  <div class="bottom-col pdf_displayNone">

                     
                     <?php if($homeTabData['isUserFollowing'] == 'true'){ ?>
                          <a style="margin-left:0px;" class="u-flw-txt" reverseclass="flw-txt" href="javascript:void(0)" callforaction="unfollow" ga-attr="<?=$GA_tapFollow;?>" onclick="followEntity(this,<?php echo $homeTabData['questionId'];?>,'<?php echo $threadType;?>',false,'<?php echo $followTrackingPageKeyId;?>')">unfollow</a>
                    <?php }else{?>
                          <a style="margin-left:0px;" class="flw-txt" reverseclass="u-flw-txt" href="javascript:void(0)" callforaction="follow" ga-attr="<?=$GA_tapFollow;?>" onclick="followEntity(this,<?php echo $homeTabData['questionId'];?>,'<?php echo $threadType;?>',false,'<?php echo $followTrackingPageKeyId;?>')">follow</a>
                    <?php } ?>     
                     <a class="share-tag" href="javascript:void(0)" ga-attr="<?=$GA_tap_Share;?>" onclick="mngPage('<?=urlencode($homeTabData['URL'])?>','<?php if($homeTabData['type'] == 'D'){echo 'Checkout this Discussion on shiksha';} else {echo 'Checkout this Question on shiksha';} ?>','<?=$homeTabData['id']?>','<?php if($homeTabData['type'] == 'D'){echo 'discussion';} else {echo 'question';} ?>');"><i class="share-ico"></i></a>
                  </div>
              </div>
          </div>
          <input type="hidden" id="quesOwnerName<?=$homeTabData['questionId']?>" value="<?=$homeTabData['questionOwnerName']?>" />
<?php }
  if($flag==0 && !$pdf){
  $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_ANA_LAA"));
        $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_ANA_LAA1"));
}

?>

<?php

                if(count($data['homepage']) == 0){
                        if($contentType == 'question'){
                                echo "<div style='color: #9e9e9e;font-size: 14px;padding: 40px 0;text-align: center;'>No questions yet</div>";
                        }else if($contentType == 'discussion'){
                                echo "<div style='color: #9e9e9e;font-size: 14px;padding: 40px 0;text-align: center;'>No discussions yet</div>";
                        }else{
                                echo "<div style='color: #9e9e9e;font-size: 14px;padding: 40px 0;text-align: center;'>No unanswered questions yet</div>";
                        }
                }

if(count($data['homepage']) <= 4 && !$pdf) {
        echo $recoWidget;
}

if(count($data['homepage']) <= 8 && !$pdf) {
         ?>
            <div id='askProposition' style="display:none;">
                 <div style='text-align: center; margin-top: 10px; margin-bottom: 10px;'><img border='0' alt='' id='loadingImage1' class='small-loader' style='border-radius:50%;width: 60px;border: 1px solid rgb(229, 230, 230)' src='//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif'/></div>
            </div>
<?php }
?>

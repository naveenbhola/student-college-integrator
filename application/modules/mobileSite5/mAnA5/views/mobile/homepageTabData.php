<?php
$i = 0;
foreach($data['homepage'] as $key=>$homeTabData){
		if($data['tagsRecommendations'] && $data['showTagsRecommendationsAtPostion'] == $key){
            $this->load->view('mobile/cards/tagRecommendation');
		}

    	if($data['userRecommendations'] && $data['showUserRecommendationsAtPostion'] == $key){
            $this->load->view('mobile/cards/userRecommendation');
    	}

        if($callType!='AJAX' && $key == 2 && $boomr_pageid == 'mobilesite_AnA_homePage' && $pageType=='home'){
                echo Modules::run('mAnA5/AnAMobile/articleWidgetAnA');
        }
    	
    	if($homeTabData['type'] == 'Q'){
        $actionType = 'answer';
        $threadType = 'question';

        if($homeTabData['answerOwnerName'] == ''){
            $GA_tapChildCta = 'WRITEANSWER_UNANQUEST_HOMEPAGE_WEBAnA';
            $GA_tapFollow = 'FOLLOW_UNANQUEST_HOMEPAGE_WEBAnA';
            $GA_tap_Share = 'SHARE_UNANQUEST_HOMEPAGE_WEBAnA';
            $GA_tapTagName = 'TAG_UNANQUEST_HOMEPAGE_WEBAnA';
            $GA_tapEntity = 'QUESTTITLE_UNANQUEST_HOMEPAGE_WEBAnA';

        }else{
            $GA_tapChildCta = 'WRITEANSWER_QUEST_HOMEPAGE_WEBAnA';
            $GA_tapChild = 'ANSWER_QUEST_HOMEPAGE_WEBAnA';
            $GA_tapViewMore = 'VIEWMORE_ANSWER_HOMEPAGE_WEBAnA';
            $GA_tapFollow = 'FOLLOW_QUEST_HOMEPAGE_WEBAnA';
            $GA_tap_Share = 'SHARE_QUEST_HOMEPAGE_WEBAnA';
            $GA_tapProfileName = 'PROFILE_ANSWER_HOMEPAGE_WEBAnA';
            $GA_tapTagName = 'TAG_QUEST_HOMEPAGE_WEBAnA';
            $GA_tapEntity = 'QUESTTITLE_QUEST_HOMEPAGE_WEBAnA';
            $GA_tapUpvote = 'UPVOTE_ANSWER_HOMEPAGE_WEBAnA';
            $GA_tapDownvote = 'DOWNVOTE_ANSWER_HOMEPAGE_WEBAnA';
        }

    	}else{
    		$actionType = 'comment';
    		$threadType = 'discussion';
        $GA_tapChildCta = 'WRITECOMMENT_DISC_HOMEPAGE_WEBAnA';
        $GA_tapChild = 'COMMENT_DISC_HOMEPAGE_WEBAnA';
        $GA_tapViewMore = 'VIEWMORE_COMMENT_HOMEPAGE_WEBAnA';
        $GA_tapFollow = 'FOLLOW_DISC_HOMEPAGE_WEBAnA';
        $GA_tap_Share = 'SHARE_DISC_HOMEPAGE_WEBAnA';
        $GA_tapProfileName = 'PROFILE_COMMENT_HOMEPAGE_WEBAnA';
        $GA_tapTagName = 'TAG_DISC_HOMEPAGE_WEBAnA';
        $GA_tapEntity = 'DISCTITLE_DISC_HOMEPAGE_WEBAnA';
        $GA_tapUpvote = 'UPVOTE_COMMENT_HOMEPAGE_WEBAnA';
        $GA_tapDownvote = 'DOWNVOTE_COMMENT_HOMEPAGE_WEBAnA';

    	}
?>
          <div class="card-data" questionid="<?php echo $homeTabData['questionId']?>" answerid="<?php echo $homeTabData['answerId'] ? $homeTabData['answerId'] : 0;?>" type="<?php echo $homeTabData['type']?>" tracking="true">
              <div class="card-data-head">
                  <h2 class="titl" <?php if($homeTabData['type'] == 'D'){?>style="width:67%"<?php } ?>>
                  <?php
                    $limit = 70;
                    $heading = $homeTabData['heading'];
                    if($homeTabData['setHeadingUsername'] == 1){
                        $heading = ucfirst($homeTabData['headingUsername'])." ".$heading;
                    }
                    if($homeTabData['type'] == 'D'){
                      $limit = 60;
                    }

                    if(strlen($heading) > $limit){
                          echo substr($heading,0,$limit)."...";
                          
                    } else {
                          echo $heading; 
                    }
                    
                    //echo displayTextAsPerMobileResolution($heading,2,false,false,true);
                 ?>
                 </h2>
                  <?php if($homeTabData['type'] == 'D'){
                  ?>
                    <i class="discus-ico"></i>
                  <?php } ?>
                  <span <?php if($homeTabData['type'] == 'D'){ ?> style="margin-right:20px;" <?php } ?> >

                  <?php if(trim($homeTabData['activityTime']) != "") { ?>
                  <i class="qa-sprite clock-ico"></i><?php echo $homeTabData['activityTime']; ?>
                  <?php } ?>
                  </span>
                  
                  <p class="clr"></p>
               </div>
              <div class="type-of-que">
                  <div style="overflow: hidden;white-space: nowrap;">
                 <?php if(isset($homeTabData['tags']) && $homeTabData['tags'] != ''){
                    $len = 0;
                    $limit = 50;
                    foreach($homeTabData['tags'] as $key1=>$value){
                      if(!isset($value['tagName']) || $value['tagName'] == ''){
                                continue;
                      }
                      $len += strlen($value['tagName']);
		      //$tagUrl = getSeoUrl($value['tagId'], 'tag', $value['tagName']);
                      if($len < $limit){
                          ?>
                            <a href="<?=$value['url']?>" class="tag-card <?php if($value['type']!="ne") {?> ent-tgMClr <?php } ?> " onclick="gaTrackEventCustom('HOMEPAGE_WEBAnA','<?=$GA_tapTagName?>','<?=$GA_userLevel?>');"><?php echo $value['tagName'];?></a>
                          <?php
                      } else {
                          $len -= strlen($value['tagName']);
                          ?>
                            <a href="<?=$value['url']?>" class="tag-card <?php if($value['type']!="ne") {?> ent-tgMClr <?php } ?> " onclick="gaTrackEventCustom('HOMEPAGE_WEBAnA','<?=$GA_tapTagName?>','<?=$GA_userLevel?>');">

                              <?php
                              echo substr($value['tagName'],0,($limit - 3 - $len))."...";
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
                
                <a class="d-txt" href="<?=$homeTabData['URL'];?>" id="quesTitle_<?=$homeTabData['questionId']?>" onclick="gaTrackEventCustom('HOMEPAGE_WEBAnA','<?=$GA_tapEntity?>','<?=$GA_userLevel?>');"><?php echo $homeTabData['title']; ?></a>

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
                  <a class="user-list-dtls" href="<?=$userProfileURL?>" onclick="gaTrackEventCustom('HOMEPAGE_WEBAnA','<?=$GA_tapProfileName?>','<?=$GA_userLevel?>');">
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
                    if($ansTextlen > 650){
                        echo substr(strip_tags($homeTabData['answerText']),0,650).'...';    
                    }else{
                        echo $homeTabData['answerText']; 
                    }
                  ?>
                  </div>

                  

                  <?php if($ansTextlen > 650){?>
                  <div class="user-review" id="answerfullTxt_<?=$homeTabData['questionId'];?>" style = "display:none;" ><?php echo $homeTabData['answerText']; ?></div>
                  <a href="javascript:void(0)" class="box-view" id="viewMoreBtn_<?=$homeTabData['questionId']?>" onclick="gaTrackEventCustom('HOMEPAGE_WEBAnA','<?=$GA_tapViewMore?>','<?=$GA_userLevel?>');viewFullAnswerText('<?=$homeTabData['questionId']?>')">View More</a>
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
                  <div class="like-col"> 
                    <a class="like-a" href="javascript:void(0)" onclick="gaTrackingForAna(this,'HOMEPAGE_WEBAnA','<?=$GA_tapUpvote?>','<?=$GA_userLevel?>');setEntityRating(this,'<?php echo $homeTabData['answerId']?>','<?php echo $actionType;?>',false,'<?php echo $tuptrackingPageKeyId;?>')" callforaction="<?php echo $upvotedAction;?>"><i class="<?php echo $upvotedClass;?>" reverseclass="<?php echo $upvotedReverseClass;?>" disabledclass="<?php echo $upvotedDisableClass;?>">&nbsp;</i><?php echo $homeTabData['likeCount']; ?></a>
                    <a class="like-d" href="javascript:void(0)" onclick="gaTrackingForAna(this,'HOMEPAGE_WEBAnA','<?=$GA_tapDownvote?>','<?=$GA_userLevel?>');setEntityRating(this,'<?php echo $homeTabData['answerId']?>','<?php echo $actionType;?>',false,'<?php echo $tdowntrackingPageKeyId;?>')" callforaction="<?php echo $downvotedAction;?>"><i class="<?php echo $downvotedClass;?>" reverseclass="<?php echo $downvotedReverseClass;?>" disabledclass="<?php echo $downvotedDisableClass;?>"></i><?php echo $homeTabData['dislikeCount']; ?></a>
                  </div>
                </div>
              <?php } ?>
                  <div class="bottom-col">

                  <?php

                       if($homeTabData['type'] == 'Q'){
                            $followTrackingPageKeyId = $qfollowTrackingPageKeyId;
  							  
                            if($homeTabData['isThreadOwner'] == true){?>
                                <a class="cmnt-btn" href="javascript:void(0)" onclick="showAnswerResponseMessage('You cannot answer your own question.');markThreadAsViewed(<?=$homeTabData['id']?>, <?php echo $homeTabData['answerId'] ? $homeTabData['answerId'] : 0?>);"><?php echo $actionType;?></a>

                            <?php }else if($homeTabData['hasUserAnswered'] == true){ ?>
                                <a class="cmnt-btn" href="javascript:void(0)" onclick="showAnswerResponseMessage('You cannot answer more than once on the same question.');markThreadAsViewed(<?=$homeTabData['id']?>, <?php echo $homeTabData['answerId'] ? $homeTabData['answerId'] : 0?>);"><?php echo $actionType;?></a>

                            <?php }else if($homeTabData['threadStatus'] == 'closed'){?>
                                <a class="cmnt-btn" href="javascript:void(0)" onclick="showAnswerResponseMessage('This question is closed for answering.');markThreadAsViewed(<?=$homeTabData['id']?>, <?php echo $homeTabData['answerId'] ? $homeTabData['answerId'] : 0?>);"><?php echo $actionType;?></a>

                            <?php }else{?>
                                <a class="cmnt-btn" href="#answerPostingLayerDiv" data-inline="true" data-rel="dialog" data-transition="fade" onclick="gaTrackEventCustom('HOMEPAGE_WEBAnA','<?=$GA_tapChildCta?>','<?=$GA_userLevel?>');makeAnswerPostingLayer('<?=$homeTabData['questionId']?>','0','add','<?php echo $atrackingPageKeyId;?>')"><?php echo $actionType;?></a>

                      <?php }}else{
									$followTrackingPageKeyId = $dfollowTrackingPageKeyId; ?>

                          <a class="cmnt-btn" href="#commentPostingLayerDiv" onclick="gaTrackEventCustom('HOMEPAGE_WEBAnA','<?=$GA_tapChildCta?>','<?=$GA_userLevel?>');makeCommentPostingLayer('<?=$homeTabData['questionId']?>','<?=$homeTabData['questionId']?>','<?=$threadType?>','<?=$actionType?>',0,'<?php echo $ctrackingPageKeyId;?>')" data-inline="true" data-rel="dialog" data-transition="fade"><?php echo $actionType;?></a>

                    <?php } ?>

                     
                     <?php if($homeTabData['isUserFollowing'] == 'true'){ ?>
                          <a class="u-flw-txt" reverseclass="flw-txt" href="javascript:void(0)" callforaction="unfollow" onclick="gaTrackingForAna(this,'HOMEPAGE_WEBAnA','<?=$GA_tapFollow?>','<?=$GA_userLevel?>');followEntity(this,<?php echo $homeTabData['questionId'];?>,'<?php echo $threadType;?>',false,'<?php echo $followTrackingPageKeyId;?>')">unfollow</a>
                    <?php }else{?>
                          <a class="flw-txt" reverseclass="u-flw-txt" href="javascript:void(0)" callforaction="follow" onclick="gaTrackingForAna(this,'HOMEPAGE_WEBAnA','<?=$GA_tapFollow?>','<?=$GA_userLevel?>');followEntity(this,<?php echo $homeTabData['questionId'];?>,'<?php echo $threadType;?>',false,'<?php echo $followTrackingPageKeyId;?>')">follow</a>
                    <?php } ?>     
                     <a class="share-tag" href="javascript:void(0)" onclick="gaTrackEventCustom('HOMEPAGE_WEBAnA','<?=$GA_tap_Share?>','<?=$GA_userLevel?>');mngPage('<?=urlencode($homeTabData['URL'])?>','<?php if($homeTabData['type'] == 'D'){echo 'Checkout this Discussion on shiksha';} else {echo 'Checkout this Question on shiksha';} ?>','<?=$homeTabData['id']?>','<?php if($homeTabData['type'] == 'D'){echo 'discussion';} else {echo 'question';} ?>');"><i class="share-ico"></i></a>
                  </div>
              </div>
          </div>

          <?php 
                    if(($callType == 'AJAX' && $nextPaginationIndex<=10) || $callType != 'AJAX') {
                        $i++;
                        if($callType != 'AJAX' && $i==3)
                        {
                          $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
                          $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
                        }
                        
                        if(($pageName !='AnAMobile' && $i==6) ||  ($callType == 'AJAX' && $i==3))
                        {
                          $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'AON'));
                          $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'AON1'));
                        }
                    }
                    if($callType != 'AJAX' && $i==count($data['homepage'] ) && count($data['homepage'])<3)
                    { 
                        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
                        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
                    }
          ?>

          <input type="hidden" id="quesOwnerName<?=$homeTabData['questionId']?>" value="<?=$homeTabData['questionOwnerName']?>" />
<?php }?>
 

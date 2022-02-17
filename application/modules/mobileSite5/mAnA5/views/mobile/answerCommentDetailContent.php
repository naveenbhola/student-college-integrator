<!--answers card-->
<?php 
    $childCount_=0;
    foreach($data['childDetails'] as $key=>$childDetail){


        if ($data['entityDetails']['childCount']>5) {
      $childCount_++;
      if ($childCount_==4) {
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'QDP_AON'));
      }
    }

        if($data['entityDetails']['queryType'] == 'Q'){
              $entityType = 'Answer';
              $GA_currentPage = 'QUESTION DETAIL PAGE ';
              $GA_viewMore = 'VIEWMORE_ANSWER_QUESTIONDETAIL_WEBAnA';
              $GA_answerOwnerProfile = 'PROFILE_ANSWER_QUESTIONDETAIL_WEBAnA';
              $GA_tapOnAnswerComment = 'COMMENT_ANSWER_QUESTIONDETAIL_WEBAnA';
              $GA_share = 'SHARE_ANSWER_QUESTIONDETAIL_WEBAnA';
              $GA_reportAbuse = 'REPORTABUSE_ANSWER_QUESTIONDETAIL_WEBAnA';
              $GA_edit = 'EDIT_ANSWER_QUESTIONDETAIL_WEBAnA';
              $GA_delete = 'DELETE_ANSWER_QUESTIONDETAIL_WEBAnA';
              $GA_upvote = 'UPVOTE_ANSWER_QUESTIONDETAIL_WEBAnA';
              $GA_downvote = 'DOWNVOTE_ANSWER_QUESTIONDETAIL_WEBAnA';
              $GA_tapOverFlow = 'OVERFLOW_ANSWER_QUESTIONDETAIL_WEBAnA';
              $GA_TapOnListOfUpvoters = 'UPVOTERS_ANSWER_QUESTIONDETAIL_WEBAnA';

        }
        else{
             $entityType = 'Comment';
             $GA_currentPage = 'DISCUSSION DETAIL PAGE ';
             $GA_viewMore = 'VIEWMORE_COMMENT_DISCUSSIONDETAIL_WEBAnA';
             $GA_answerOwnerProfile = 'PROFILE_ANSWER_DISCUSSIONDETAIL_WEBAnA';
             $GA_tapOnAnswerComment = 'REPLY_COMMENT_DISCUSSIONDETAIL_WEBAnA';
             $GA_share = 'SHARE_COMMENT_DISCUSSIONDETAIL_WEBAnA';
             $GA_reportAbuse = 'REPORTABUSE_COMMENT_DISCUSSIONDETAIL_WEBAnA';
             $GA_edit = 'EDIT_COMMENT_DISCUSSIONDETAIL_WEBAnA';
             $GA_delete = 'DELETE_COMMENT_DISCUSSIONDETAIL_WEBAnA';
             $GA_upvote = 'UPVOTE_COMMENT_DISCUSSIONDETAIL_WEBAnA';
             $GA_downvote = 'DOWNVOTE_COMMENT_DISCUSSIONDETAIL_WEBAnA';
             $GA_tapOverFlow = 'OVERFLOW_COMMENT_DISCUSSIONDETAIL_WEBAnA';
             $GA_TapOnListOfUpvoters = 'UPVOTERS_COMMENT_DISCUSSIONDETAIL_WEBAnA';
        }
  ?>

      <div class="qdp-layer-col" style="display:none;z-index:200" id="qdpLayer_<?php echo $childDetail['msgId'];?>">
         <a href="javascript:void(0);" class="layer-cls">&times</a>
          <ul class="qdp-ul">
                <?php foreach($childDetail['overflowTabs'] as $key=>$actions){
                  if($data['entityDetails']['queryType'] == 'Q')
                  {
                    $abuseTrackingPageKeyId = $raaTrackingPageKeyId;
                    $entityType = 'Answer';
                    $reportAbuseEntityType = 'answer';
                  }
                  else
                  {
                    $abuseTrackingPageKeyId = $racTrackingPageKeyId;
                    $entityType = 'Comment';
                    $reportAbuseEntityType = 'discussionComment';
                  }
                if($actions['label'] == 'Report Abuse') {
                  ?>

                <li><a href="javascript:void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_reportAbuse?>','<?=$GA_userLevel?>');fetchReportAbuseLayer('<?php echo $childDetail['msgId'];?>','<?php echo $childDetail['threadId']?>','<?php echo $reportAbuseEntityType;?>','<?php echo $abuseTrackingPageKeyId;?>')" id="reportAbuse" data-inline="true" data-rel="dialog" data-transition="fade"><?=$actions['label']?></a></li>
                <?php } elseif($actions['label'] == 'Delete')
              { ?>
              <li><a href="javascript:void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_delete?>','<?=$GA_userLevel?>');showConfirmMessage('<?php echo $childDetail['msgId'];?>','<?php echo $childDetail['threadId'];?>','<?php echo $entityType;?>','<?php echo $actions['label'];?>','page')" id="closeEntity" data-inline="true" data-rel="dialog" data-transition="fade"><?=$actions['label']?></a></li>
         		<?php }else if($actions['label'] == 'Edit' && $data['entityDetails']['queryType'] == 'Q') {?>
                    <li><a href="javascript:void(0);" class="editAnswer edit_<?=$childDetail['msgId']?>" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_edit?>','<?=$GA_userLevel?>');makeAnswerPostingLayer('<?=$childDetail['threadId']?>','<?=$childDetail['msgId'];?>','edit')" data-inline="true" data-rel="dialog" data-transition="fade"><?=$actions['label']?></a></li>
                <?php }else if($actions['label'] == 'Edit' && $data['entityDetails']['queryType'] == 'D') {?>
                    <li><a href="javascript:void(0);" class="editComment" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_edit?>','<?=$GA_userLevel?>');makeCommentPostingLayer('<?=$childDetail['threadId']?>','<?= $childDetail['threadId']?>','discussion','comment','<?=$childDetail['msgId']?>')" data-inline="true" data-rel="dialog" data-transition="fade"><?=$actions['label']?></a></li>     
              <?php }else {?>
                <li><a href="javascript:void(0);"><?=$actions['label']?></a></li>
                <?php } }?>
          </ul>
        </div> 
      <div class="user-card card-in" tracking="true" questionid="0" answerid="<?php echo $childDetail['msgId'];?>" type="<?php echo $data['entityDetails']['queryType'];?>">
	  <?php $userProfileURL = getSeoUrl($childDetail['userId'],'userprofile'); ?>
          <a class="user-mt-card" href="<?=$userProfileURL?>" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_answerOwnerProfile?>','<?=$GA_userLevel?>');">
            <div class="user-i-card">
              <?php 

                      if($childDetail['picUrl'] != '' && strpos($childDetail['picUrl'],'/public/images/photoNotAvailable.gif') === false){?>
                          <img src=<?php echo getSmallImage($childDetail['picUrl']);?> alt="Shiksha Ask & Answer" style="width: 55px;height: 55px;">

                      <?php }else{
                          echo ucwords(substr($childDetail['firstname'],0,1));
              }?>

            </div>
            <div class="mt-inf-card">
              <p class="u-name"><?php echo $childDetail['firstname'].' '.$childDetail['lastname'];?></p>
              <p class="u-level"><?php echo $childDetail['levelName'];?></p>
              <?php if(isset($childDetail['aboutMe']) && $childDetail['aboutMe'] != ''){?>
              <p class="u-level"><?php echo $childDetail['aboutMe'];?></p>
              <?php } ?>
              <span class="show-time"><i class="clock-ico"></i><?php if($childDetail['formattedTime'] == ''){echo 'Just now';}else {echo $childDetail['formattedTime'];}?></span>
              
            </div>
            <p class="clr"></p>
          </a>
          <p class="user-review" id="answerTxt_<?=$childDetail['msgId'];?>">
            <?php 
                    $fullAnwerWithInstSuggestions = $childDetail['msgTxt'].$childDetail['suggestionText'];
                    $ansTextlen = strlen(strip_tags($fullAnwerWithInstSuggestions));
                    
                    if($ansTextlen > 650){
                        echo substr(strip_tags($fullAnwerWithInstSuggestions),0,650).'...';    
                    }else{
                        echo $fullAnwerWithInstSuggestions; 
                    }
                  ?>
          </p>
          <p class="user-review" id="answerfullTxt_<?=$childDetail['msgId'];?>" style = "display:none;"><?php echo $fullAnwerWithInstSuggestions; ?></p>
          <p name="answerMsgTxt_<?=$childDetail['msgId'];?>" id="answerMsgTxt_<?=$childDetail['msgId'];?>" style = "display:none;"><?=$childDetail['msgTxt'];?> </p>

          <?php if($ansTextlen > 650){?>
                  <a href="javascript:void(0)" msgId = "<?=$childDetail['msgId'];?>" class="box-view view-more-txt" id="viewMoreBtn_<?=$childDetail['msgId'];?>" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_viewMore?>','<?=$GA_userLevel?>');">View More</a>
          <?php } ?>

          <?php	if($childDetail['hasUserVotedUp'] == 'true'){
		          	$upvotedClass = 'like-actv-ico';
		          	$downvotedClass = 'disable-unlike';
		          	$upvotedReverseClass = 'like-ico';
		          	$downvotedReverseClass = 'dislike-act-ico';
		          	$upvotedDisableClass = 'disable-like';
		          	$downvotedDisableClass = 'dislike-ico';
		          	$upvotedAction = 'cancelupvote';
		          	$downvotedAction = 'noaction';
                }else if($childDetail['hasUserVotedDown'] == 'true'){
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

          <?php if($childDetail['queryType'] == 'Q'){
                          $childType = 'Comment';
                          $type = 'question';
                          $tuptrackingPageKeyId = $tupatrackingPageKeyId;
                          $tdowntrackingPageKeyId = $tdownatrackingPageKeyId;
                          $addNewTrackingPageKeyId = $ctrackingPageKeyId;
                }else{
                          $childType = 'Reply';
                          $type = 'discussion';
                          $tuptrackingPageKeyId = $tupdctrackingPageKeyId;
                          $tdowntrackingPageKeyId = $tdowndctrackingPageKeyId;
                          $addNewTrackingPageKeyId = $rtrackingPageKeyId;
                }
          ?>

          <div class="upvotes-block">
            <p class="upvote-txt">
            <?php 
                  if($childDetail['digUp']>0){ 
                      $displayUpClass = 'display:none';
                      $displayStyle = '';
                      
                  }else{
                     $displayUpClass = '';
                      $displayStyle = 'display:none';
                  }
    
                  if($childDetail['digUp']>=1000){
                    $digUpCount = round(($childDetail['digUp']/1000),1).'k'; 
                  }else{
                    $digUpCount = $childDetail['digUp'];
                  }

                  if($childDetail['digDown']>=1000){
                    $digDownCount = round(($childDetail['digDown']/1000),1).'k'; 
                  }else{
                    $digDownCount = $childDetail['digDown'];
                  }

                 $digUplabel = ($childDetail['digUp']>1)? 'upvotes':'upvote';
                 $digDownlabel = ($childDetail['digDown']>1)? 'downvotes':'downvote'; 

              ?>
                    <a href="javascript:void(0);" data-rel="dialog" data-inline="true" data-transition="fade" class="up-count userListLayer" id="upvoteLinkEnable_<?=$childDetail['msgId']?>" style="<?=$displayStyle?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_TapOnListOfUpvoters;?>','<?php echo $GA_userLevel;?>');getUserListFollowedOrUpvoted('<?=$childDetail['msgId']?>','<?=$type?>','<?=$childDetail['digUp']; ?>','upvote')">
                      <span class="digupCount" style="color:#000 !important;"><?php echo $digUpCount; ?></span><?php echo ' ';?> 
                      <span class="digupText" style="color:#000 !important;"><?=$digUplabel?></span>
                    </a>
            
                    <a class="upvotes-txt" id="upvoteLinkDisable_<?=$childDetail['msgId']?>" style="<?=$displayUpClass?>">
                      <span class="digupCount" style="color:#999 !important;"><?php echo $digUpCount; ?></span><?php echo ' ';?> 
                      <span class="digupText" style="color:#999 !important;"><?=$digUplabel?></span>
                    </a>

                    <span class="bullet">&bull;</span>
                    <a class="devotes-txt">
                        <span class="digdownCount" style="color:#999 !important;"><?=$digDownCount?></span><?php echo ' ';?>
                        <span class="digdownText" style="color:#999 !important;"><?=$digDownlabel ?></span>
                    </a>
            </p>

            <?php 
                if($childType == 'Comment'){
                  $childLabel = ($childDetail['childCount'] != 1)?'Comments':'Comment';
                }
                else{
                  $childLabel = ($childDetail['childCount'] != 1)?'Replies':'Reply';
                }
            ?>

            <?php if($childDetail['childCount'] >0){?>

                <a childCount = '<?=$childDetail['childCount']?>' href="javascript:void(0);" data-inline="true" data-rel="dialog" data-transition="fade" class="uc-block commentDetails" id="replyCommentLink_<?=$childDetail['msgId']?>" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapOnAnswerComment?>','<?=$GA_userLevel?>');getCommentOrReplyDetails('<?=$childDetail['msgId']?>',0,10)">
               <b class="uc-num" id="entityChildCount_<?=$childDetail['msgId']?>"><?=$childDetail['childCount']?></b>

               <span id='entityChildText_<?=$childDetail['msgId']?>'><?php echo $childLabel; ?></span>
                </a>

            <?php }else{?>
                <a href="javascript:void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapOnAnswerComment?>','<?=$GA_userLevel?>');makeCommentPostingLayer('<?php echo $childDetail['threadId']?>','<?php echo $childDetail['msgId'];?>','<?=$type?>','<?=$childType?>',0,'<?php echo $addNewTrackingPageKeyId;?>')" data-inline="true" data-rel="dialog" data-transition="fade" class="uc-block commentPosting" >
                <b class="uc-num"><?=$childDetail['childCount']?></b><?php echo $childLabel; ?></a>

            <?php } ?>
          </div>
           <div class="btm-card">
               <div class="left-col"> 
                    <a class="like-a" href="javascript:void(0)" onclick="gaTrackingForAna(this,'<?=$GA_currentPage?>','<?=$GA_upvote?>','<?=$GA_userLevel?>');setEntityRating(this,'<?php echo $childDetail['msgId'];?>','<?php echo $entityType;?>',true,'<?php echo $tuptrackingPageKeyId;?>')" callforaction="<?php echo $upvotedAction;?>"><i class="<?php echo $upvotedClass;?>" reverseclass="<?php echo $upvotedReverseClass;?>" disabledclass="<?php echo $upvotedDisableClass;?>"></i></a>

                    <a class="like-d" href="javascript:void(0)" onclick="gaTrackingForAna(this,'<?=$GA_currentPage?>','<?=$GA_downvote?>','<?=$GA_userLevel?>');setEntityRating(this,'<?php echo $childDetail['msgId'];?>','<?php echo $entityType;?>',true,'<?php echo $tdowntrackingPageKeyId;?>')" callforaction="<?php echo $downvotedAction;?>"><i class="<?php echo $downvotedClass;?>" reverseclass="<?php echo $downvotedReverseClass;?>" disabledclass="<?php echo $downvotedDisableClass;?>"></i></a>

                   <?php if($childDetail['childCount'] >0){?>
                         <a href="javascript:void(0);" data-inline="true" data-rel="dialog" data-transition="fade" class="flw-txt commentDetails" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapOnAnswerComment?>','<?=$GA_userLevel?>');getCommentOrReplyDetails('<?=$childDetail['msgId']?>',0,10)" ><?php echo $childType; ?></a>
                  <?php }else{?>
                        <a href="javascript:void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapOnAnswerComment?>','<?=$GA_userLevel?>');makeCommentPostingLayer('<?php echo $childDetail['threadId']?>','<?php echo $childDetail['msgId'];?>','<?=$type?>','<?=$childType?>',0,'<?php echo $addNewTrackingPageKeyId;?>')" data-inline="true" data-rel="dialog" data-transition="fade" class="flw-txt commentPosting" > <?php echo $childType; ?></a>        
                <?php } ?>
            

                    <a href="javascript:void(0);" class="share-tag" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_share?>','<?=$GA_userLevel?>');mngPage('<?=urlencode($childDetail['shareUrl'])?>','<?php if($childDetail['queryType'] == 'D'){echo 'Checkout this Comment on shiksha';} else {echo 'Checkout this Answer on shiksha';} ?>','<?=$childDetail['msgId']?>','<?php if($childDetail['queryType'] == 'D'){echo 'discussionComment';} else {echo 'answer';} ?>');"><i class="share-ico" style="margin-left:10px!important;"></i></a>
                </div>
              <?php if(!empty($childDetail['overflowTabs'])){?>
                    <a href="javascript:void(0);" class="more-tag" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapOverFlow?>','<?=$GA_userLevel?>');showOverFlowTab('<?=$childDetail['msgId']?>');"><i class="more-ico"></i></a>
              <?php } ?>
           </div>
       </div>
       <input type="hidden" name="userCountUpvote_<?=$childDetail['msgId']?>" id="userCountUpvote_<?=$childDetail['msgId']?>" value="<?=$childDetail['digUp']?>">
       <input type="hidden" name="userCountDownvote_<?=$childDetail['msgId']?>" id="userCountDownvote_<?=$childDetail['msgId']?>" value="<?=$childDetail['digDown']?>">
<?php } ?>

<script type="text/javascript"> 
    <?php if($callType == 'AJAX'){?>
          $("#showViewMore").val("<?php echo $data['entityDetails']['showViewMore']; ?>");
          $("#start").val("<?php echo ($start+$count); ?>");
          $("#count").val("<?php echo $count; ?>");
    <?php } ?>
 
</script>

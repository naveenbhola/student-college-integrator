
<?php if($data['entityDetails']['queryType'] == 'Q'){
              $childType = 'Answer';
              $threadType = 'question';
              $GA_currentPage = 'QUESTION DETAIL PAGE ';
              $GA_tapTag = 'TAG_QUESTIONDETAIL_WEBAnA';
              $GA_descViewMore = 'VIEWMORE_DESCRIPTION_QUESTIONDETAIL_WEBAnA';
              $GA_postChild = 'WRITEANSWER_QUESTIONDETAIL_WEBAnA';
              $GA_tapOwnerProfile = 'PROFILE_QUEST_QUESTIONDETAIL_WEBAnA';
              $GA_tapFollow = 'FOLLOW_QUESTIONDETAIL_WEBAnA';
              $GA_tapShare = 'SHARE_QUESTIONDETAIL_WEBAnA';
              $GA_tapOverFlowTab = 'OVERFLOW_QUEST_QUESTIONDETAIL_WEBAnA'; 
              $GA_reportAbuse = 'REPORTABUSE_QUEST_QUESTIONDETAIL_WEBAnA'; 
              $GA_editEntity = 'EDITQUEST_QUEST_QUESTIONDETAIL_WEBAnA';
              $GA_editTags = 'EDITTAGS_QUEST_QUESTIONDETAIL_WEBAnA';
              $GA_close = 'CLOSE_QUEST_QUESTIONDETAIL_WEBAnA';
              $GA_delete = 'DELETE_QUEST_QUESTIONDETAIL_WEBAnA';
	          $GA_tapMarkLater = 'ANSWER_LATER_QUESTIONDETAIL_WEBAnA';
              $markLaterTrackingPageKeyId = $alTrackingPageKeyId;
              $GA_TapOnListOfFollowers = 'FOLLOWERLIST_QUESTIONDETAIL_WEBAnA';
              
      }else{
              $childType = 'Comment';
              $threadType = 'discussion';
              $GA_currentPage = 'DISCUSSION DETAIL PAGE ';
              $GA_tapTag = 'TAG_DISCUSSIONDETAIL_WEBAnA';
              $GA_descViewMore = 'VIEWMORE_DESCRIPTION_DISCUSSIONDETAIL_WEBAnA';
              $GA_postChild = 'WRITECOMMENT_DISCUSSIONDETAIL_WEBAnA';
              $GA_tapOwnerProfile = 'PROFILE_DISC_DISCUSSIONDETAIL_WEBAnA';
              $GA_tapFollow = 'FOLLOW_DISCUSSIONDETAIL_WEBAnA';
              $GA_tapShare = 'SHARE_DISCUSSIONDETAIL_WEBAnA';
              $GA_tapOverFlowTab = 'OVERFLOW_DISC_DISCUSSIONDETAIL_WEBAnA'; 
              $GA_reportAbuse = 'REPORTABUSE_DISC_DISCUSSIONDETAIL_WEBAnA'; 
              $GA_editEntity = 'EDITDISC_DISC_DISCUSSIONDETAIL_WEBAnA';
              $GA_editTags = 'EDITTAGS_DISC_DISCUSSIONDETAIL_WEBAnA';
              $GA_close = 'CLOSE_DISC_DISCUSSIONDETAIL_WEBAnA';
              $GA_delete = 'DELETE_DISC_DISCUSSIONDETAIL_WEBAnA';
	          $GA_tapMarkLater = 'COMMENT_LATER_DISCUSSIONDETAIL_WEBAnA';
              $markLaterTrackingPageKeyId = $clTrackingPageKeyId;
              $GA_TapOnListOfFollowers = 'FOLLOWERLIST_DISCUSSIONDETAIL_WEBAnA';
            
      }
?>

  <div class="qdp-layer-col" style="display:none;z-index:200" id="qdpLayer_<?=$data['entityDetails']['msgId']?>" >
   <a href="javascript:void(0);" class="layer-cls" onclick="$('#overflowTab_<?=$entityId?>').hide();">&times</a>
    <ul class="qdp-ul">
          <?php 
          foreach($data['entityDetails']['overflowTabs'] as $key=>$actions){ ?>
          <?php if($actions['label'] == 'Report Abuse') {?>
                <li><a href="javascript:void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_reportAbuse?>','<?=$GA_userLevel?>');fetchReportAbuseLayer('<?php echo $data['entityDetails']['msgId'];?>','<?php echo $data['entityDetails']['threadId']?>','<?php echo $entityType;?>','<?php echo $raTrackingPageKeyId;?>')" id="reportAbuse" data-inline="true" data-rel="dialog" data-transition="fade"><?=$actions['label']?></a></li>
	      <?php }else if($actions['label'] == 'Share') {?>
	      <li><a href="javascript:void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapShare?>','<?=$GA_userLevel?>');hideOverFlowTab(); mngPage('<?=urlencode($data['entityDetails']['shareUrl'])?>','<?php if($data['entityDetails']['queryType'] == 'D'){echo 'Checkout this Discussion on shiksha';} else {echo 'Checkout this Question on shiksha';} ?>','<?=$data['entityDetails']['threadId']?>','<?php if($data['entityDetails']['queryType'] == 'D'){echo 'discussion';} else {echo 'question';} ?>');"><?=$actions['label']?></a></li>
              <?php }
              else if($actions['label'] == 'Edit Question'){?>
              <li><a id="editQuestion" href="javascript:void(0);" data-inline="true" data-rel="dialog" data-transition="fade" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_editEntity?>','<?=$GA_userLevel?>');"><?=$actions['label']?></a></li>
              <?php
              }
              else if($actions['label'] == 'Edit Tags' && $data['entityDetails']['fromOthers'] == "user"){?>
              <li><a id="editTag" href="javascript:void(0);" data-inline="true" data-rel="dialog" data-transition="fade" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_editTags?>','<?=$GA_userLevel?>');fetchIntermediatePage('question',<?=$data['entityDetails']['msgId'];?>,'editTag',<?=$data['entityDetails']['threadId'];?>);"><?=$actions['label']?></a></li>
              <?php
              }
              else if($actions['label'] == 'Edit Tags' && $data['entityDetails']['fromOthers'] == "discussion"){?>
              <li><a href="javascript:void(0);" data-inline="true" data-rel="dialog" data-transition="fade" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_editTags?>','<?=$GA_userLevel?>');fetchIntermediatePage('discussion',<?=$data['entityDetails']['msgId'];?>,'editTag',<?=$data['entityDetails']['threadId'];?>);"><?=$actions['label']?></a></li>
              <?php
              }
              else if($actions['label'] == 'Edit Discussion'){?>
              <li><a id="editDiscussion" href="javascript:void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_editEntity?>','<?=$GA_userLevel?>');"><?=$actions['label']?></a></li>
              <?php } elseif($actions['label'] == 'Close' || $actions['label'] == 'Delete')
              { 
                      if($actions['label'] == 'Close'){
                            $GA_closeDelete = $GA_close;
                      }else{
                            $GA_closeDelete = $GA_delete;
                      }

                ?>
              <li><a href="javascript:void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_closeDelete?>','<?=$GA_userLevel?>');showConfirmMessage('<?php echo $data['entityDetails']['threadId'];?>','<?php echo $data['entityDetails']['threadId'];?>','<?php echo $entityType;?>','<?php echo $actions['label'];?>','page')" id="closeEntity" data-inline="true" data-rel="dialog" data-transition="fade"><?=$actions['label']?></a></li>
	      <?php }else if($actions['label'] == 'Answer Later' || $actions['label'] == 'Comment Later'){
			if(count($data['entityDetails']['overflowTabs']) == 1){
				?><script>var hideOverflowIcon = true;</script><?php
			}
		 ?>
	      <li id="markLaterActionLink"><a href="javascript: void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapMarkLater?>','<?=$GA_userLevel?>');markLater(<?php echo $entityId;?>,'<?php echo $threadType;?>','<?php echo $markLaterTrackingPageKeyId;?>');"><?=$actions['label']?></a></li>
              <?php } else{?>
              <li><a href="javascript:void(0);"><?=$actions['label']?></a></li>
                <?php }} ?>
    </ul>
  </div>

<div class="qdp-m-card">
   <div class="qstn-card">
    <div>
    <?php if(!empty($data['entityDetails']['tagsDetail'])){?>
      <div style="overflow-x:scroll;overflow-y:hidden;width:auto;white-space: nowrap;" class='tagDiv'>
                   <?php foreach($data['entityDetails']['tagsDetail'] as $key=>$value){ 
			//$tagUrl = getSeoUrl($value['tagId'], 'tag', $value['tagName']);
		   ?>
              
                    <a href="<?=$value['url']?>" class="tag-card <?php if($value['type']!='ne') {?> ent-tgMClr <?php } ?>" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapTag?>','<?=$GA_userLevel?>');"><?=$value['tagName'];?></a>
               
             <?php } ?>     
         </div>
      <?php } ?>
     <h1 id="quesTitle_<?=$data['entityDetails']['threadId']?>" class="qstn-link"><?=$data['entityDetails']['title']?></h1>
     <p class="ask-q-txt" id="questionDesc_<?=$data['entityDetails']['threadId']?>"><?php 
                    $quesDescLen = strlen(strip_tags($data['entityDetails']['description']));
                    if($quesDescLen > 200){
                        echo substr(strip_tags($data['entityDetails']['description']),0,200).'...';    
                    }else{
                        echo trim($data['entityDetails']['description']); 
                    }
                  ?></p> 
     <?php if($quesDescLen > 200){?>
                  <p class="ask-q-txt" id="questionFullDesc_<?=$data['entityDetails']['threadId']?>" style = "display:none;"><?php echo $data['entityDetails']['description']; ?></p>
                  <a href="javascript:void(0)" class="box-view" id="viewMoreBtn_<?=$data['entityDetails']['threadId']?>" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_descViewMore?>','<?=$GA_userLevel?>');viewFullQuestionDiscussionDesc('<?=$data['entityDetails']['threadId']?>')">View More</a>
          <?php } ?>

    <?php if(isset($data['entityDetails']['referrenceName']) && $data['entityDetails']['referrenceName'] != ''){?>
           <p class="ask-q-txt"><?=$data['entityDetails']['referrenceName']?></p>
    <?php } ?>
    <input type="hidden" id="threadEntityId" value="<?=$data['entityDetails']['threadId']?>" / >
    <input type="hidden" id="msgEntityId" value="<?=$data['entityDetails']['msgId']?>" / >
   </div>
  <div class="user-card">
      <?php $userProfileURL = getSeoUrl($data['entityDetails']['userId'],'userprofile'); ?>
      <a class="user-mt-card" href="<?=$userProfileURL?>" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapOwnerProfile?>','<?=$GA_userLevel?>');">
        <div class="user-i-card">
          <?php 

                      if($data['entityDetails']['picUrl'] != '' && strpos($data['entityDetails']['picUrl'],'/public/images/photoNotAvailable.gif') === false){?>
                          <img src=<?php echo getSmallImage($data['entityDetails']['picUrl']);?> alt="Shiksha Ask & Answer" style="width: 55px;height: 55px;">

                      <?php }else{
                          echo ucwords(substr($data['entityDetails']['firstname'],0,1));
              }?>
          
        </div>
        <div class="mt-inf-card">
          <p class="u-name"><?php echo $data['entityDetails']['firstname'].' '.$data['entityDetails']['lastname']?></p>
          <p class="u-level"><?=$data['entityDetails']['levelName']?></p>
          <?php if(isset($data['entityDetails']['aboutMe']) && $data['entityDetails']['aboutMe'] != ''){?>
              <p class="u-level"><?php echo $data['entityDetails']['aboutMe'];?></p>
          <?php } ?>
          <span class="show-time"><i class="clock-ico"></i><?=$data['entityDetails']['creationDate']?></span>
        </div>
        <p class="clr"></p>
      </a>

         <div class="follower-col">

         <?php 
              if($data['entityDetails']['followerCount']==0){   
                  $displayClass = "display:none";
              }else{
                  $label = ($data['entityDetails']['followerCount']>1)? 'Followers':'Follower';
                  $displayClass = "";
              } 

              if($data['entityDetails']['followerCount'] >= 1000){
                 $totalFollowCount =  round(($data['entityDetails']['followerCount']/1000),1).'k';
                
              }else{
                 $totalFollowCount = $data['entityDetails']['followerCount'];
              }

              if($data['entityDetails']['viewCount'] >= 1000){
                 $totalViewCount =  round(($data['entityDetails']['viewCount']/1000),1).'k';
                
              }else{
                 $totalViewCount = $data['entityDetails']['viewCount'];
              }

          ?>

              <a href="javascript:void(0);" data-rel="dialog" data-inline="true" data-transition="fade" class="follow-count" style="<?=$displayClass?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_TapOnListOfFollowers;?>','<?php echo $GA_userLevel;?>');getUserListFollowedOrUpvoted('<?=$data['entityDetails']['threadId']?>','<?=$threadType?>','<?=$data['entityDetails']['followerCount']?>','follow')" id="followLayer"><span class='followerCount'><?=$totalFollowCount?></span><?php echo ' '?><span class='followerText'><?=$label?></span> </a><span class="bullet" style="<?=$displayClass?>">&bull;</span>
          

              <a class="f-views"><?=$totalViewCount?> views</a>
         </div>

       <div class="btm-card s-class">
        <div class="left-col">

          <?php if($data['entityDetails']['queryType'] == 'Q'){
                      $childType = 'Answer';
                      $threadType = 'question';
                      $followTrackingPageKeyId = $qfollowTrackingPageKeyId;
            }else{
                      $childType = 'Comment';
                      $threadType = 'discussion';
                      $followTrackingPageKeyId = $dfollowTrackingPageKeyId;
            }
            ?>
          <?php if(($data['entityDetails']['isEntityOwner']== false && $data['entityDetails']['hasUserAnswered']== false && $entityType == 'question' && $data['entityDetails']['status']!='closed' ) || ($entityType == 'discussion' && empty($linkedDiscussions['linkedEntities']))) {

                if($data['entityDetails']['queryType'] == 'Q'){?>
                      <a href="javascript:void(0);" class="wrtn-btn" data-rel="dialog" data-inline="true" data-transition="fade" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_postChild?>','<?=$GA_userLevel?>');makeAnswerPostingLayer('<?=$data['entityDetails']['msgId']?>',0,'add','<?php echo $atrackingPageKeyId;?>')" id="answerPosting">WRITE <?=$childType;?></a> 
                <?php }else{ ?>
                     <a href="javascript:void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_postChild?>','<?=$GA_userLevel?>');makeCommentPostingLayer('<?=$data['entityDetails']['threadId']?>','<?=$data['entityDetails']['threadId']?>','<?=$entityType?>','<?=$childType?>',0,'<?php echo $ctrackingPageKeyId;?>')" data-inline="true" data-rel="dialog" data-transition="fade" class="wrtn-btn commentPosting">WRITE <?=$childType;?></a>

           <?php }} ?>
           
                <?php if($entityType == 'question' && $data['entityDetails']['status'] !='closed' || ($entityType == 'discussion' && empty($linkedDiscussions['linkedEntities']))){

                        if($data['entityDetails']['hasUserFollowed'] == 'true'){ ?>
                              <a class="u-flw-txt" reverseclass="flw-txt" callforaction="unfollow" href="javascript:void(0)" onclick="gaTrackingForAna(this,'<?=$GA_currentPage?>','<?=$GA_tapFollow?>','<?=$GA_userLevel?>');followEntity(this,<?php echo $entityId;?>,'<?php echo $threadType;?>',true,'<?php echo $followTrackingPageKeyId;?>');">UNFOLLOW
                        <?php }else{?>
                              <a class="flw-txt" reverseclass="u-flw-txt" callforaction="follow" href="javascript:void(0)" onclick="gaTrackingForAna(this,'<?=$GA_currentPage?>','<?=$GA_tapFollow?>','<?=$GA_userLevel?>');followEntity(this,<?php echo $entityId;?>,'<?php echo $threadType;?>',true,'<?php echo $followTrackingPageKeyId;?>');">FOLLOW
                        <?php } ?>
                        </a>
                    <a href="javascript:void(0);" class="share-tag" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapShare?>','<?=$GA_userLevel?>');mngPage('<?=urlencode($data['entityDetails']['shareUrl'])?>','<?php if($data['entityDetails']['queryType'] == 'D'){echo 'Checkout this Discussion on shiksha';} else {echo 'Checkout this Question on shiksha';} ?>','<?=$data['entityDetails']['threadId']?>','<?php if($data['entityDetails']['queryType'] == 'D'){echo 'discussion';} else {echo 'question';} ?>');"><i class="share-ico"></i></a>
              <?php } ?>
              
                <?php if($data['entityDetails']['status']=='closed' && $data['entityDetails']['queryType'] == 'Q'){?>
                    <div class="span-col">
                        <p>The question is closed for answering</p>
                    </div>

                <?php } else if($data['entityDetails']['queryType'] == 'D' && !empty($linkedDiscussions['linkedEntities'])) {?>
                    <div class="span-col">
                        <p>This discussion has been closed by the moderator because a similar discussion exist. Check out the linked discussion here -</p>
                        <?php foreach($linkedDiscussions['linkedEntities'] as $key=>$linkedEntities){?>
                        <a href="<?=$linkedEntities['url']?>" onclick="gaTrackEventCustom('DISCUSSION DETAIL PAGE','LINKED_DISC_DISCUSSIONDETAIL_WEBAnA','<?=$GA_userLevel?>');"><?=$linkedEntities['title']?></a>
                        <?php } ?>
                    </div>

                <?php } ?>
                 
        </div>
        	 <?php if(!empty($data['entityDetails']['overflowTabs']) || (is_array($data['entityDetails']['overflowTabs']) && count($data['entityDetails']['overflowTabs']) > 0)){?>   
          		<a id="overflowIconOnDetailPage" href="javascript:void(0);" class="more-tag" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_tapOverFlowTab?>','<?=$GA_userLevel?>');showOverFlowTab('<?=$data['entityDetails']['msgId']?>');"><i class="more-ico"></i></a>
        	<?php } ?>
       </div>
       <input type="hidden" id="quesOwnerName<?=$data['entityDetails']['msgId']?>" value="<?=$data['entityDetails']['firstname']?>" />
   </div>
       <input type="hidden" id="userCountFollower_<?=$data['entityDetails']['threadId']?>" value="<?=$data['entityDetails']['followerCount']?>">
</div>
</div>
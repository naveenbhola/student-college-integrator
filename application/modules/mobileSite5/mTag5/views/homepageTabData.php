<?php
	$contentCount = count($data['content']);
	$i = 0;
  foreach($data['content'] as $key=>$homeTabData){
		if($data['relatedTags'] && (($contentCount <= $data['showTagsRecommendationsAtPostion'] && $key == $contentCount - 1) || ($data['showTagsRecommendationsAtPostion'] == $key))){
      		$this->load->view('cards/relatedTags');
    	}
    	
    	if($data['topContributors'] && (($contentCount <= $data['showActiveUserRecommendationsAtPostion'] && $key == $contentCount - 1) || ($data['showActiveUserRecommendationsAtPostion'] == $key))){
            $this->load->view('cards/topContributors');
    	}
    	
      $gaShareCTA = "";
      $gaActionCTAAns = "";
      $gaViewMore = "";
      $gaProfileName = "";
      $gaTagNameClick = "";
      $gaTitle = "";
      $gaAnswerClick = "";

    	if($homeTabData['type'] == 'Q'){
    		$actionType = 'answer';
    		$threadType = 'question';
        $gaViewMore = "VIEWMORE_ANSWER_TAGDETAIL_WEBAnA";
        $gaProfileName = "PROFILE_ANSWER_TAGDETAIL_WEBAnA";
        $gaAnswerClick = "ANSWER_QUEST_TAGDETAIL_WEBAnA";

    		if($homeTabData['answerCount'] == 0){
    			$gaTrackingActionFollow		= 'FOLLOW_UNANQUEST_TAGDETAIL_WEBAnA';
          $gaShareCTA = "SHARE_UNANQUEST_TAGDETAIL_WEBAnA";
          $gaActionCTAAns = "WRITEANSWER_UNANQUEST_TAGDETAIL_WEBAnA";
          $gaTagNameClick = "TAG_UNANQUEST_TAGDETAIL_WEBAnA";
          $gaTitle = "QUESTTITLE_UNANQUEST_TAGDETAIL_WEBAnA";
    		}
        else{
    			$gaTrackingActionFollow		= 'FOLLOW_QUEST_TAGDETAIL_WEBAnA';
    			$gaTrackingActionUpvote		= 'UPVOTE_ANSWER_TAGDETAIL_WEBAnA';
    			$gaTrackingActionDownvote	= 'DOWNVOTE_ANSWER_TAGDETAIL_WEBAnA';
          $gaShareCTA = "SHARE_QUEST_TAGDETAIL_WEBAnA";
          $gaActionCTAAns = "WRITEANSWER_QUEST_TAGDETAIL_WEBAnA";
          $gaTagNameClick = "TAG_QUEST_TAGDETAIL_WEBAnA";
          $gaTitle = "QUESTTITLE_QUEST_TAGDETAIL_WEBAnA";
          
    		}
    	}
      else{
    		$actionType = 'comment';
    		$threadType = 'discussion';
    		$gaTrackingActionFollow		= 'FOLLOW_DISC_TAGDETAIL_WEBAnA';
    		$gaTrackingActionUpvote		= 'UPVOTE_COMMENT_TAGDETAIL_WEBAnA';
    		$gaTrackingActionDownvote	= 'DOWNVOTE_COMMENT_TAGDETAIL_WEBAnA';
        $gaShareCTA = "SHARE_DISC_TAGDETAIL_WEBAnA";
        $gaActionCTAAns = "WRITECOMMENT_DISC_TAGDETAIL_WEBAnA";
        $gaViewMore = "VIEWMORE_COMMENT_TAGDETAIL_WEBAnA";
        $gaProfileName = "PROFILE_COMMENT_TAGDETAIL_WEBAnA";
        $gaTagNameClick = "TAG_DISC_TAGDETAIL_WEBAnA";
        $gaTitle = "DISCTITLE_DISC_TAGDETAIL_WEBAnA";
        $gaAnswerClick = "COMMENT_DISC_TAGDETAIL_WEBAnA";
    	}
?>

          <div class="card-data" questionid="<?php echo $homeTabData['id']?>" answerid="<?php echo $homeTabData['answerId']?>" type="<?php echo $homeTabData['type']?>" tracking="true">
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

                    //echo displayTextAsPerMobileResolution($heading,2,false,false,true);
                 ?>
                 </h2>
                  <?php if($homeTabData['type'] == 'D'){
                  ?>
                    <i class="discus-ico"></i>
                  <?php } ?>

                  
                  <p class="clr"></p>
               </div>
              <div class="type-of-que">
                  <div style="overflow: hidden;white-space: nowrap;">
                 <?php if(isset($homeTabData['tags']) && $homeTabData['tags'] != ''){
                    $len = 0;
                    $limit = 50;
                    foreach($homeTabData['tags'] as $key1=>$value){
                      $len += strlen($value['tagName']);

		      //$tagUrl = getSeoUrl($value['tagId'], 'tag', $value['tagName']);
                      if($len < $limit){
                          ?>
                            <a href="<?=$value['url']?>" class="tag-card <?php if($value['type']!="ne") {?>ent-tgMClr <?php } ?>" onclick="gaTrackEventCustom('TAG DETAIL PAGE','<?=$gaTagNameClick;?>','<?php echo addslashes($value[tagName]);?>',this,'<?=$value[url]?>');"><?=$value['tagName']?></a>
                          <?php
                      } else {
                          $len -= strlen($value['tagName']);
                          ?>
                            <a href="<?php echo $value['url'];?>" class="tag-card <?php if($value['type']!="ne") {?>ent-tgMClr <?php } ?>" onclick="gaTrackEventCustom('TAG DETAIL PAGE','<?=$gaTagNameClick;?>','<?php echo addslashes($value[tagName]);?>',this,'<?=$value[url]?>');">

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
                
                <a class="d-txt" href="<?=$homeTabData['URL'];?>" onclick = "gaTrackEventCustom('TAG DETAIL PAGE','<?=$gaTitle;?>','<?=$data['tagName'];?>',this,'<?=$homeTabData['URL'];?>');" id="quesTitle_<?=$homeTabData['id']?>"><?php echo ucfirst($homeTabData['title']); ?></a>

                  <span class="a-span">
                      <?php 

                            if($homeTabData['type'] == 'Q'){
                              if($homeTabData['answerCount']== 1){
                                  $answerCountText = 'Answer';
                              }else{
                                  $answerCountText = 'Answers';
                              }
                              $tuptrackingPageKeyId = $tupatrackingPageKeyId;
                              $tdowntrackingPageKeyId = $tdownatrackingPageKeyId;
                              $followTrackingPageKeyId = $qfollowTrackingPageKeyId;
                            }else if($homeTabData['type'] == 'D'){
                              if($homeTabData['answerCount']== 1){
                                  $answerCountText = 'Comment';
                              }else{
                                  $answerCountText = 'Comments';
                              }
                              $tuptrackingPageKeyId = $tupctrackingPageKeyId;
                              $tdowntrackingPageKeyId = $tdownctrackingPageKeyId;
                              $followTrackingPageKeyId = $dfollowTrackingPageKeyId;
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
              <?php
		if(isset($homeTabData['answerId']) && isset($homeTabData['answerOwnerName']) && $homeTabData['answerOwnerName'] != ''){
			$userProfileURL = getSeoUrl($homeTabData['answerOwnerUserId'],'userprofile');
		?>
                <div>
                  <a class="user-list-dtls" href="<?=$userProfileURL?>" onclick="gaTrackEventCustom('TAG DETAIL PAGE','<?=$gaProfileName;?>','<?=$data['tagName'];?>',this,'<?=$userProfileURL?>');">

                     <div class="user-pic-col">
                      <?php 

                      if($homeTabData['answerOwnerImage'] != '' && strpos($homeTabData['answerOwnerImage'],'/public/images/photoNotAvailable.gif') === false){?>
                          <img src=<?php echo getSmallImage($homeTabData['answerOwnerImage']);?> alt="Shiksha Ask & Answer" style="width: 55px;height: 55px;">

                      <?php }else{
                          echo ucwords(substr($homeTabData['answerOwnerName'],0,1));
                      }?>
                     </div>
                      <div class="user-inf-col">
                        <p class="name" ><?php echo $homeTabData['answerOwnerName']; ?></p>
                        <p class="e-level"><?php echo $homeTabData['answerOwnerLevel']; ?></p>
                        <?php if(isset($homeTabData['aboutMe']) && $homeTabData['aboutMe'] != ''){?>
                        <p class="e-level"><?php echo $homeTabData['aboutMe']; ?></p>
                        <?php } ?>
                      </div>
                      <p class="clr"></p> 
                  </a>
                  <div class="user-review" id="answerTxt_<?=$homeTabData['id'];?>">
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
                  <div class="user-review" id="answerfullTxt_<?=$homeTabData['id'];?>" style = "display:none;"><?php echo $homeTabData['answerText']; ?></div>

                  <a href="javascript:void(0)" class="box-view" id="viewMoreBtn_<?=$homeTabData['id']?>" onclick="gaTrackEventCustom('TAG DETAIL PAGE','<?=$gaViewMore;?>','<?=$data['tagName'];?>');viewFullAnswerText('<?=$homeTabData['id']?>')">View More</a>
                  <?php } ?>

                  <?php	if($homeTabData['hasUserVotedUp'] == 'true'){
                  			$upvotedClass = 'like-actv-ico';
	                        $downvotedClass = 'disable-unlike';
	                        $upvotedReverseClass = 'like-ico';
	                        $downvotedReverseClass = 'dislike-act-ico';
	                        $upvotedDisableClass = 'disable-like';
	                        $downvotedDisableClass = 'dislike-ico';
	                        $upvotedAction = 'cancelupvote';
	                        $downvotedAction = 'noaction';
                        }elseif($homeTabData['hasUserVotedDown'] == 'true'){
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
                    <a class="like-a" href="javascript:void(0)" onclick="gaTrackingForAna(this,'TAG DETAIL PAGE','<?php echo $gaTrackingActionUpvote;?>','<?php echo $data['tagName'];?>');setEntityRating(this,'<?php echo $homeTabData['answerId']?>','<?php echo $actionType;?>',false,'<?php echo $tuptrackingPageKeyId;?>')" callforaction="<?php echo $upvotedAction;?>"><i class="<?php echo $upvotedClass;?>" reverseclass="<?php echo $upvotedReverseClass;?>" disabledclass="<?php echo $upvotedDisableClass;?>">&nbsp;</i><?php echo $homeTabData['likeCount']; ?></a>
                    <a class="like-d" href="javascript:void(0)" onclick="gaTrackingForAna(this,'TAG DETAIL PAGE','<?php echo $gaTrackingActionDownvote;?>','<?php echo $data['tagName'];?>');setEntityRating(this,'<?php echo $homeTabData['answerId']?>','<?php echo $actionType;?>',false,'<?php echo $tdowntrackingPageKeyId;?>')" callforaction="<?php echo $downvotedAction;?>"><i class="<?php echo $downvotedClass;?>" reverseclass="<?php echo $downvotedReverseClass;?>" disabledclass="<?php echo $downvotedDisableClass;?>"></i><?php echo $homeTabData['dislikeCount']; ?></a>
                  </div>
                </div>
              <?php } ?>
                  <div class="bottom-col">

                    <?php
                       if($homeTabData['type'] == 'Q'){
                  
                            if($homeTabData['isThreadOwner'] == true){?>
                                <a class="cmnt-btn" href="javascript:void(0);" onclick="gaTrackEventCustom('TAG DETAIL PAGE','<?=$gaActionCTAAns;?>','<?=$data['tagName'];?>');showAnswerResponseMessage('You cannot answer your own question.');markThreadAsViewed(<?=$homeTabData['id']?>, <?php echo $homeTabData['answerId'] ? $homeTabData['answerId'] : 0?>);"><?php echo $actionType;?></a>

                            <?php }else if($homeTabData['hasUserAnswered']== true){ ?>
                                <a class="cmnt-btn" href="javascript:void(0);" onclick="gaTrackEventCustom('TAG DETAIL PAGE','<?=$gaActionCTAAns;?>','<?=$data['tagName'];?>');showAnswerResponseMessage('You cannot answer more than once on the same question.');markThreadAsViewed(<?=$homeTabData['id']?>, <?php echo $homeTabData['answerId'] ? $homeTabData['answerId'] : 0?>);"><?php echo $actionType;?></a>

                            <?php }else if($homeTabData['threadStatus'] == 'closed'){?>
                                <a class="cmnt-btn" href="javascript:void(0);" onclick="gaTrackEventCustom('TAG DETAIL PAGE','<?=$gaActionCTAAns;?>','<?=$data['tagName'];?>');showAnswerResponseMessage('This question is closed for answering.');markThreadAsViewed(<?=$homeTabData['id']?>, <?php echo $homeTabData['answerId'] ? $homeTabData['answerId'] : 0?>);"><?php echo $actionType;?></a>

                            <?php }else{?>
                                <a class="cmnt-btn" href="#answerPostingLayerDiv" data-inline="true" data-rel="dialog" data-transition="fade" onclick="gaTrackEventCustom('TAG DETAIL PAGE','<?=$gaActionCTAAns;?>','<?=$data['tagName'];?>');makeAnswerPostingLayer('<?=$homeTabData['id']?>','0','add','<?php echo $atrackingPageKeyId;?>')"><?php echo $actionType;?></a>

                      <?php }}else{?>
                          <a class="cmnt-btn" href="#commentPostingLayerDiv" onclick="gaTrackEventCustom('TAG DETAIL PAGE','<?=$gaActionCTAAns;?>','<?=$data['tagName'];?>');makeCommentPostingLayer('<?=$homeTabData['id']?>','<?=$homeTabData['id']?>','discussion','<?=$actionType?>',0,'<?php echo $ctrackingPageKeyId;?>')" data-inline="true" data-rel="dialog" data-transition="fade"><?php echo $actionType;?></a>
                              
                    <?php	}?>

                     	<?php	if($homeTabData['isUserFollowing'] == 'true'){?>
                          			<a class="u-flw-txt" reverseclass="flw-txt" href="javascript:void(0)" callforaction="unfollow" onclick="gaTrackingForAna(this,'TAG DETAIL PAGE','<?php echo $gaTrackingActionFollow;?>','<?php echo $data['tagName'];?>');followEntity(this,<?php echo $homeTabData['id'];?>,'<?php echo $threadType;?>',false,'<?php echo $followTrackingPageKeyId;?>')">unfollow</a>
                        <?php	} else{?>
                        			<a class="flw-txt" reverseclass="u-flw-txt" href="javascript:void(0)" callforaction="follow" onclick="gaTrackingForAna(this,'TAG DETAIL PAGE','<?php echo $gaTrackingActionFollow;?>','<?php echo $data['tagName'];?>');followEntity(this,<?php echo $homeTabData['id'];?>,'<?php echo $threadType;?>',false,'<?php echo $followTrackingPageKeyId;?>')">follow</a>
                        <?php	}?>
                     		<a class="share-tag" href="javascript:void(0)" onclick="gaTrackEventCustom('TAG DETAIL PAGE','<?=$gaShareCTA;?>','<?=$data['tagName'];?>');mngPage('<?=urlencode($homeTabData['URL'])?>','<?php if($homeTabData['type'] == 'D'){echo 'Checkout this Discussion on shiksha';} else {echo 'Checkout this Question on shiksha';} ?>','<?=$homeTabData['id']?>','<?php if($homeTabData['type'] == 'D'){echo 'discussion';} else {echo 'question';} ?>');"><i class="share-ico"></i></a>
                  </div>
              </div>
          </div>

          <?php $i++;
          if($i==3){
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
          }
          if($i==6){
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'AON'));
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'AON1'));
          }
          ?>          
          
          <input type="hidden" id="quesOwnerName<?=$homeTabData['id']?>" value="<?=$homeTabData['questionOwnerName']?>" />
<?php }
        if($i<3){
          $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
          $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
        }
?>
 
<?php
if(count($data['content']) == 0){
		echo "<div class='no-results'>".$data['responseMessage']."</div>";
}
?>

  <?php 
  switch ($trackingPageKeyId) {
    case 885:
          $GA_currentPage         = 'QUESTION DETAIL PAGE';
          $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_QUESTIONDETAIL_DESKAnA';
          $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_QUESTIONDETAIL_DESKAnA';
      break;
    case 886:
          $GA_currentPage         = 'QUESTION DETAIL PAGE';
          $GA_Tap_On_Follow       = 'FOLLOW_UPVOTERS_ANSWER_QUESTIONDETAIL_DESKAnA';
          $GA_Tap_On_User         = 'PROFILE_UPVOTERS_ANSWER_QUESTIONDETAIL_DESKAnA';
          break;
    case 890:
          $GA_currentPage         = 'DISCUSSION DETAIL PAGE';
          $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_DISCUSSIONDETAIL_DESKAnA';
          $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_DISCUSSIONDETAIL_DESKAnA';
          break;
    case 891:
          $GA_currentPage         = 'DISCUSSION DETAIL PAGE';
          $GA_Tap_On_Follow       = 'FOLLOW_UPVOTERS_COMMENT_DISCUSSIONDETAIL_DESKAnA';
          $GA_Tap_On_User         = 'PROFILE_UPVOTERS_COMMENT_DISCUSSIONDETAIL_DESKAnA';
          break;
    case 857:
          $GA_currentPage         = 'HOMEPAGE_DESKAnA';
          if($threadType == 'question')
          {
              $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_QUEST_HOMEPAGE_DESKAnA';
              $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_QUEST_HOMEPAGE_DESKAnA';  
          }
          else
          {
            $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_DISC_HOMEPAGE_DESKAnA';
            $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_DISC_HOMEPAGE_DESKAnA';
          }  
          break;
    case 866:
          $GA_currentPage         = 'HOMEPAGE_DESKAnA';
          $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_DISC_HOMEPAGE_DESKAnA';
          $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_DISC_HOMEPAGE_DESKAnA';
          break;
    case 868:
          $GA_currentPage         = 'HOMEPAGE_DESKAnA';
          $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_UNANQUEST_HOMEPAGE_DESKAnA';
          $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_UNANQUEST_HOMEPAGE_DESKAnA';
          break;
    case 875:
          $GA_currentPage         = 'ALLQUEST_DESKAnA';
          $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_QUEST_ALLQUEST_DESKAnA';
          $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_QUEST_ALLQUEST_DESKAnA';
          break;
    case 882:
          $GA_currentPage         = 'ALLDISC_DESKAnA';
          $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_DISC_ALLDISC_DESKAnA';
          $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_DISC_ALLDISC_DESKAnA';
          break;
    case 844:
          $GA_currentPage         = 'TAG DETAIL PAGE';
          if($threadType == 'question')
          {
              $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_QUEST_TAGDETAIL_DESKAnA';
              $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_QUEST_TAGDETAIL_DESKAnA';  
          }
          else
          {
            $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_DISC_TAGDETAIL_DESKAnA';
            $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_DISC_TAGDETAIL_DESKAnA';
          }
          break;
    case 845:
          $GA_currentPage         = 'TAG DETAIL PAGE';
          $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_DISC_TAGDETAIL_DESKAnA';
          $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_DISC_TAGDETAIL_DESKAnA';
          break;
    case 846:
          $GA_currentPage         = 'TAG DETAIL PAGE';
          $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_UNANQUEST_TAGDETAIL_DESKAnA';
          $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_UNANQUEST_TAGDETAIL_DESKAnA';
          break;
     case 1055:
          $GA_currentPage         = 'ALL QUESTIONS PAGE';
          $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_DISC_ALLQUESTIONS_DESK';
          $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_DISC_ALLQUESTIONS_DESK';
          break;
    case 1056:
          $GA_currentPage         = 'ALL QUESTIONS PAGE';
          $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_UNANQUEST_ALLQUESTIONS_DESK';
          $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_UNANQUEST_ALLQUESTIONS_DESK';  
          break;
    case 1054:
          $GA_currentPage         = 'ALL QUESTIONS PAGE';
          if($threadType == 'question')
          {
            $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_QUEST_ALLQUESTIONS_DESK';
            $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_QUEST_ALLQUESTIONS_DESK';    
          }
          else
          {
            $GA_Tap_On_Follow       = 'FOLLOW_FOLLOWERLIST_DISC_ALLQUESTIONS_DESK';
            $GA_Tap_On_User         = 'PROFILE_FOLLOWERLIST_DISC_ALLQUESTIONS_DESK';
          }
		break;
          
  }
  $i = 0;
  foreach ($userList as $userKey => $userValue){
    //it is used for identifying first element of ul list
    if($startIndex == 0 && $i ==0)
      {
        $firstchild = "id = 'ulFE'";
        $i++;
      }
      else{
        $firstchild ='';
      }?>
                           <li <?php echo $firstchild;?>>
                                <div class="main" style="cursor:pointer" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_User;?>','<?php echo $GA_userLevel;?>');window.open('<?php echo $userValue['url'];?>','_top');">
                                   <div class="div-d">
                                        <span>  
                                                <?php 
                                                if($userValue['avtarimageurl'] != '' && strpos($userValue['avtarimageurl'],'/public/images/photoNotAvailable.gif') === false){?>
                                                    <img src="<?php echo getSmallImage($userValue['avtarimageurl'])?>" alt="Shiksha Ask & Answer" style="width: 45px;height: 45px;">
                                                    <?php //echo ucwords(substr($userValue['userName'],0,1)); ?>
                                                <?php }else{
                                                    echo ucwords(substr($userValue['userName'],0,1));
                                           }?>
                                         </span>  
                                        <div class="s-in">
                                           <p class="avatar-name">
                                             <?php if(strlen($userValue['userName'])>37){
                                                    $userName = substr($userValue['userName'],0,37).'...';
                                                }
                                                else{
                                                  $userName = $userValue['userName'];
                                                }
                                                echo strtoupper($userName);
                                                  ?>
                                           </p>
                                           <p class="g-l">
                                            <?php
                                              if(!empty($userValue['aboutMe']))
                                              {
                                                if(strlen($userValue['aboutMe'])>37)
                                                {
                                                  $aboutMe = substr($userValue['aboutMe'],0,37).'...'; 
                                                }
                                                else
                                                {
                                                  $aboutMe = $userValue['aboutMe'];
                                                }
                                                echo $aboutMe;
                                              }
                                            ?>
                                           </p>
                                        </div>
                                   </div>
                                    <div class="s-cell">
                                      <?php
                                      $userLevel = explode('-', $userValue['levelName']);
                                      ?>
                                           <p class="avatar-name"><?php echo $userLevel[0];?></p>
                                           <p class="g-l"><?php echo $userLevel[1];?></p>
                                   </div>
                                      <div class="l-cell">
                                        <?php if($userValue['userId'] != $loggedInUser) {?>
                                          <?php if($userValue['isUserFollowing'] == 'true'){ ?>
                                                    <a class="ut-dis-btn" curclass="ut-dis-btn" reverseclass="ut-btn" callforaction="unfollow" href="javascript:void(0)" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Follow;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $userValue['userId'];?>,'user',false,'<?php echo $trackingPageKeyId?>');preventOnclickFollow(event);">Unfollow
                                              <?php }else{?>
                                                    <a class="ut-btn" curclass="ut-btn" reverseclass="ut-dis-btn" callforaction="follow" href="javascript:void(0)" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Follow;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $userValue['userId'];?>,'user',false,'<?php echo $trackingPageKeyId;?>');preventOnclickFollow(event);">Follow
                                              <?php } ?>
                                              </a>
                                          <p class="clr"></p>
                                          <?php } ?>
                                      </div>
                                      
                                   <!-- div class="l-cell">
                                     <a href="" class="ana-btns f-btn">Follow</a>
                                   </div> -->
                                </div>
                           </li>                      
  <?php }?>
 <script>
 var pageNoUserList = '<?php echo $pageNoUserList;?>';
 var startIndex = '<?php echo $startIndex;?>';
 var countIndex = '<?php echo $countIndex;?>';
  handleUserListLayer();
  loadMoreUsersOnLayer();
  </script>
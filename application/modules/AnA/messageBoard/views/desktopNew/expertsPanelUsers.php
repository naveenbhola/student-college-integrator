   <?php
   if($expertsLevelPage == 'All')
   {
      $GA_currentPage  = 'EXPERTPANEL_ALL_DESKAnA';
      $GA_Tap_On_User  = 'PROFILE_EXPERTPANEL_ALL_DESKAnA';
      $GA_Tap_on_Follow = 'FOLLOW_EXPERTPANEL_ALL_DESKAnA';
   }
   else
   {
      $GA_currentPage  = 'EXPERTPANEL_LEVEL'.$expertsLevelPage.'_DESKAnA';
      $GA_Tap_On_User  = 'PROFILE_EXPERTPANEL_LEVEL'.$expertsLevelPage.'_DESKAnA';
      $GA_Tap_on_Follow = 'FOLLOW_EXPERTPANEL_LEVEL'.$expertsLevelPage.'_DESKAnA';  
   }
                               foreach ($expertsInfoUserWise as $expertKey => $expertValue) { 
                                if($inculdeLevelHeading == true) {
                                  if($expertsLevelPage == 'All')
                                  {
                                    $levelArray = array_keys($expertsCountPerLevel);
                                    $firstElement = $levelArray[0];  
                                  }
                                  else
                                  {
                                    $firstElement = $expertsLevelPage;
                                  }
                                  if($firstElement == $expertKey)
                                  {
                                    $noStyle = 'no-style';
                                  }
                                  else
                                  {
                                    $noStyle = ' '; 
                                  }
                                  ?>

                                  <!--div class="scholar"--> 
                                   <h3 class="scholar-h3 <?php echo $noStyle;?>"><?php echo $expertValue[0]['levelName'];?></h3>
                                   <?php }
                                   elseif($inculdeLevelHeading == false)
                                    {
                                      $inculdeLevelHeading = true; 
                                    }
                                    ?>
                              <?php 
                                   foreach ($expertValue as $userKey => $userValue) { ?>

                                      <div class="ana-col-md-4 prn">
                                        <div class="expert-data">
                                            <div class="c-block cta-block">
                                               <!-- <a href="" class="btns-flw">Follow</a> -->
                                                     <?php if($userValue['userId'] != $loggedInUser) {?>
                                                <?php if($expertsBasicInfo[$userValue['userId']]['isFollowing'] == true){ ?>
                                                          <a class="btns-flw ut-dis-btn" curclass="ut-dis-btn" reverseclass="ut-btn" callforaction="unfollow" href="javascript:void(0)" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_on_Follow;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $userValue['userId'];?>,'user',false,'<?php echo $trackingPageKeyId?>');preventOnclickFollow(event);">Unfollow
                                                    <?php }else{?>
                                                          <a class="btns-flw ut-btn" curclass="ut-btn" reverseclass="ut-dis-btn" callforaction="follow" href="javascript:void(0)" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_on_Follow;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $userValue['userId'];?>,'user',false,'<?php echo $trackingPageKeyId;?>');preventOnclickFollow(event);">Follow
                                                    <?php } ?>
                                                    </a>
                                                <p class="clr"></p>
                                                <?php } ?>
                                                 <div class="new-avatar" style="cursor:pointer;" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_User;?>','<?php echo $GA_userLevel;?>');window.open('<?php echo $userValue['userProfileUrl'];?>','_top');">
                                                 
                                                   <span>  
                                                <?php 
                                                //var_dump(function_exists("getSmallImage"));echo "2";die;
                                                if($expertsBasicInfo[$userValue['userId']]['avtarimageurl'] != '' && strpos($expertsBasicInfo[$userValue['userId']]['avtarimageurl'],'/public/images/photoNotAvailable.gif') === false){?>
                                                    <img src="<?php echo getSmallImage($expertsBasicInfo[$userValue['userId']]['avtarimageurl']);?>" alt="Shiksha Ask & Answer" style="width: 45px;height: 45px;">
                                                    <?php //echo ucwords(substr($userValue['userName'],0,1)); ?>
                                                <?php }else{
                                                    echo ucwords(substr($expertsBasicInfo[$userValue['userId']]['firstname'],0,1));
                                                        }?>
                                                    </span>
                                                 </div>
                                                    <div class="c-inf">
                                                       <p class="avatar-name" style="cursor:pointer;" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_User;?>','<?php echo $GA_userLevel;?>');window.open('<?php echo $userValue['userProfileUrl'];?>','_top');">
                                                         <?php 
                                                          $userName = $expertsBasicInfo[$userValue['userId']]['firstname']." ".$expertsBasicInfo[$userValue['userId']]['lastname'];
                                                         if(strlen($userName)>37){
                                                            $userName = substr($userName,0,34).'...';
                                                      }
                                                      else{
                                                        $userName = $userName;
                                                      }
                                                      echo ucfirst($userName);
                                                        ?>
                                                       </p>
                                                       <div class="l-div">
                                                          <p class="des-p"><?php echo $userValue['levelName'];?> <span>| Points <?php echo $userValue['points'];?></span></p>
                                                          <p class="c-level">
                                                             <?php
                                                                if(!empty($expertsBasicInfo[$userValue['userId']]['aboutMe']))
                                                                {
                                                                  if(strlen($expertsBasicInfo[$userValue['userId']]['aboutMe'])>37)
                                                                  {
                                                                    $aboutMe = substr($expertsBasicInfo[$userValue['userId']]['aboutMe'],0,34).'...'; 
                                                                  }
                                                                  else
                                                                  {
                                                                    $aboutMe = $expertsBasicInfo[$userValue['userId']]['aboutMe'];
                                                                  }
                                                                  echo $aboutMe;
                                                                }
                                                              ?>
                                                          </p>
                                                       </div>
                                                    </div>
                                                    <?php 
                                                      $designation = $expertsBasicInfo[$userValue['userId']]['designation'];
                                                      $employer = $expertsBasicInfo[$userValue['userId']]['employer'];
                                                     $presentWork = '';
                                                      if(! empty($designation))
                                                        $presentWork = $designation;
                                                      if(! empty($designation) && !empty($employer))
                                                        $presentWork .= ' , ';
                                                      if(!empty($employer))
                                                        $presentWork .= $employer;

                                                      $qualification = $expertsBasicInfo[$userValue['userId']]['Qualification'];
                                                      $instituteName = $expertsBasicInfo[$userValue['userId']]['instituteName'];
                                                     $higherQuali = '';
                                                      if(! empty($qualification))
                                                        $higherQuali = $qualification;
                                                      if(! empty($qualification) && !empty($instituteName))
                                                        $higherQuali .= ' , ';
                                                      if(!empty($instituteName))
                                                        $higherQuali .= $instituteName;
                                                    ?>
                                                    <div class="expert-dtls">
                                                    
                                                        <p class="stream"><?php echo ((!empty($presentWork)) ? $presentWork : 'No Data Available');?> </p>
                                                           
                                                        <p class="e-stream"><?php echo ((!empty($higherQuali)) ? $higherQuali : 'No Data Available');?></p>
                                                    </div>
                                                   <!--LEAST COL-->
                                                     <div class="btm-div">
                                                          <div class="new-sec">
                                                           
                                                              <div class="t-cell">
                                                               <p class="">Upvotes<b><?php echo $expertsBasicInfo[$userValue['userId']]['upvoteCount'] ? formatNumber($expertsBasicInfo[$userValue['userId']]['upvoteCount']) : 0?></b></p>
                                                             </div>
                                                              <div class="t-cell">
                                                               <p class="">Answers<b><?php echo $expertsBasicInfo[$userValue['userId']]['answerCount'] ? formatNumber($expertsBasicInfo[$userValue['userId']]['answerCount']) : 0;?></b></p>
                                                             </div>
                                                             <div class="t-cell">
                                                              <span style="display:none;" class="followersCountTextArea" curClass="" revClass=""><?php echo $expertsBasicInfo[$userValue['userId']]['followers']? $expertsBasicInfo[$userValue['userId']]['followers'] : 0;?></span>
                                                               <p class="">Followers<b class="followCnt"><?php echo $expertsBasicInfo[$userValue['userId']]['followers']? formatNumber($expertsBasicInfo[$userValue['userId']]['followers']) : 0;?></b></p>
                                                             </div>
                                                            </div> 
                                                            <?php 
                                                            $socailUrl = array();
                                                            if(!empty($expertsBasicInfo[$userValue['userId']]['twitterId']))
                                                              $socailUrl['twitterId'] = $expertsBasicInfo[$userValue['userId']]['twitterId'];
                                                            if(!empty($expertsBasicInfo[$userValue['userId']]['facebookId']))
                                                                $socailUrl['facebookId'] = $expertsBasicInfo[$userValue['userId']]['facebookId'];
                                                              if(!empty($expertsBasicInfo[$userValue['userId']]['linkedinId']))
                                                                $socailUrl['linkedinId'] = $expertsBasicInfo[$userValue['userId']]['linkedinId'];
                                                              if(!empty($expertsBasicInfo[$userValue['userId']]['youtubeId']))
                                                                $socailUrl['youtubeId'] = $expertsBasicInfo[$userValue['userId']]['youtubeId'];
                                                              if(!empty($expertsBasicInfo[$userValue['userId']]['personalURL']))
                                                              {
                                                                $socailUrl['personalURL'] = $expertsBasicInfo[$userValue['userId']]['personalURL'];
                                                              }
                                                            ?>
                                                            <?php if(!empty($socailUrl)) {?>
                                                            <div  class="twt-f" style="cursor:pointer;">Connect
                                                             <div class="animate-col">
                                                                <ul class="animate-ul">
                                                                <?php if(!empty($socailUrl['twitterId'])) {?>
                                                                  <li>
                                                                      <a href="<?php echo prep_url($socailUrl['twitterId']);?>" target="_blank"><i class="hover-icons tweet"></i>Twitter</a>
                                                                  </li>
                                                                  <?php } if(!empty($socailUrl['facebookId'])) {?>
                                                                  <li>
                                                                      <a target="_blank" href="<?php echo prep_url($socailUrl['facebookId']);?>"><i class="hover-icons fb"></i>Facebook</a>
                                                                  </li>
                                                                  <?php } if(!empty($socailUrl['personalURL'])) {?>
                                                                  <li>
                                                                      <a target="_blank" href="<?php echo prep_url($socailUrl['personalURL']);?>"><i class="hover-icons blog"></i>Blog</a>
                                                                  </li>
                                                                  <?php } if(!empty($socailUrl['linkedinId'])) {?>
                                                                  <li>
                                                                      <a target="_blank" href="<?php echo prep_url($socailUrl['linkedinId']);?>"><i class="hover-icons linkedn"></i>Linkdein</a>
                                                                  </li>
                                                                  <?php } if(!empty($socailUrl['youtubeId'])) {?>
                                                                   <li>
                                                                      <a target="_blank" href="<?php echo prep_url($socailUrl['youtubeId']);?>"><i class="hover-icons ytube"></i>Youtube</a>
                                                                  </li>
                                                                  <?php } ?>
                                                                </ul>
                                                               </div>
                                                            </div>
                                                            <?php } ?>
                                                            
                                                     </div>   
                                                    
                                              </div>
                                        </div>
                                     </div>
                                   <?php }
                                   ?>
                                   <p class="clr"></p>
                               <?php 
                                }

                               ?>

<script>
    var totalExperts = <?php echo $totalExperts;?>;
    var expertsViewedCount = <?php echo $expertsViewedCount;?>;
    var expertsLevelPage = '<?php echo $expertsLevelPage;?>';
    var expertPanelPageNo = <?php echo $expertPanelPageNo;?>;
</script>

<?php 
$regFormId = random_string('alnum', 6);
$userDisplayPic = isset($personalInfo['AvatarImageURL'])?($personalInfo['AvatarImageURL']):'/public/images/noun_45478_cc.png';
$isPic = 0; if(!empty($personalInfo['AvatarImageURL'])) { $isPic = 1;}
$this->load->view('user/uploadMyImage', array('isPic'=>$isPic));
?>

<div class="n-row">

            <section class="prf-main">
                <div class="prf-img">
                    <?php if($userDisplayPic == '/public/images/noun_45478_cc.png' || $userDisplayPic == ''){ ?>
                    <img id="userImage" class="lazy" name="userImage" data-original="<?php echo MEDIA_SERVER.$userDisplayPic;?>" style="height: 100%; width: 100%;"/>
                      <?php if($publicProfile != true){?>
                        <a href="#" class="change-pic" onclick="return showUploadMyImage('updateMyImageOverlay','Upload Your Profile Photo');">Upload Photo</a>
                      <?php } }else { ?>
                      <img id="userImage" class="lazy" name="userImage" data-original="<?php echo MEDIA_SERVER.$userDisplayPic;?>" style="height: 100%; width: 100%;"/>
                      <?php if($publicProfile != true){?>
                        <a href="#" class="change-pic" onclick="return showUploadMyImage('updateMyImageOverlay','Change Your Profile Photo');">Change Photo</a>
                      <?php } }?>
                </div>

                <div class="prf-dtls">
                  <h1 class="pfn" id="myname" ><?=$personalInfo['FirstName'].' '.$personalInfo['LastName'];?></h1>
                  <?php if(is_array($userLevelDetails) && !empty($userLevelDetails['levelName'])){ ?>
                  <p class="level-txt"><span class="l-txt"  style="word-wrap:break-word"><?php echo $userLevelDetails['levelName']; ?><?php if(!empty($userLevelDetails['points']) || $userLevelDetails['points'] != 0){ ?><span class="q-l"></span></span><span  style="word-wrap:break-word"><?php echo $userLevelDetails['points'].' points'; ?></span> <?php } ?></p>
                  <?php } ?>
                  <h4 class="pfi" id="aboutme"><?php if($additionalInfo['AboutMe']) echo $additionalInfo['AboutMe']; ?></h4>
                  <span class="pfd" id="currentEmployer">
                  <?php 
                      $flag = false;
                    for ($x = 1; $x <= 10; $x++) {
                      $workExp = 'workExp'.$x;
                      $workExp = $$workExp;
                      if($workExp['CurrentJob'] == 'YES'){ 
                        
                        if($flag){
                          continue;
                        }
                        
                  ?>
                  
                    <i class="icons1 ic_work"></i>
                    <p>
                        <?php  echo $workExp['Designation'].' at '.$workExp['Employer']; 
                        $flag =true;?>
                    </p>
                  <?php }
                      }
                  ?>
                  </span>

                  <span class="pfd" id="currentStudy">
                  <?php 
                    if($PHD && $PHD['InstituteName']){
                      $study = 'PHD from '.$PHD['InstituteName'];
                    }else if($PG && $PG['Name'] && $PG['InstituteName']){
                      $study = $PG['Name'].' from '.$PG['InstituteName'];
                    }else if($UG && $UG['InstituteName'] && $UG['Name']){
                      $study = $UG['Name'].' from '.$UG['InstituteName'];
                    }else if($xiith && $xiith['InstituteName']){
                      $study = '12th at '.$xiith['InstituteName'];
                    }else if($xth && $xth['InstituteName']){
                      $study = '10th at '.$xth['InstituteName'];
                    }

                    if($study != ''){
                  ?>

                  
                    <i class="icons1 ic_edu"></i>
                    <p><?php echo $study; ?></p>
                  
                  <?php } ?>
                  </span>
                  <ul class="prf-scl" id="socialProfile">
                    <?php if($socialInfo['FacebookId'] && $socialInfo['FacebookId'] != ''){ 
                            //var_dump($finalData['socialInfo']['FacebookId']);
                            if (strpos($socialInfo['FacebookId'], 'http') === false) {
								$socialInfo['FacebookId'] = 'https://'.$socialInfo['FacebookId'];
                            }
                    ?>
                    <li><a href="<?php echo $socialInfo['FacebookId']; ?>" target="_blank"><i class="icons1 ic_fb1"></i></a></li>
                    <?php } ?>
                    
                    <?php  if($socialInfo['TwitterId'] && $socialInfo['TwitterId'] != ''){ 
                            
                            if (strpos($socialInfo['TwitterId'], 'http') === false) {
								$socialInfo['TwitterId'] = 'https://'.$socialInfo['TwitterId'];
                            }
                      ?>
                    <li><a href="<?php echo $socialInfo['TwitterId']; ?>" target="_blank"><i class="icons1 ic_twtr"></i></a></li>
                    <?php } ?>

                    <?php  if($socialInfo['LinkedinId'] && $socialInfo['LinkedinId'] != ''){ 
                            
                            if (strpos($socialInfo['LinkedinId'], 'http') === false) {
                              $socialInfo['LinkedinId'] = 'https://'.$socialInfo['LinkedinId'];
                            }
                    ?>
                    <li><a href="<?php echo $socialInfo['LinkedinId']; ?>" target="_blank"><i class="icons1 ic_ln"></i></a></li>
                    <?php } ?>

                    <?php  if($socialInfo['YoutubeId'] && $socialInfo['YoutubeId'] != ''){ 
                            
                            if (strpos($socialInfo['YoutubeId'], 'https://') === false) {
                              $socialInfo['YoutubeId'] = 'https://'.$socialInfo['YoutubeId'];
                            }
                      ?>
                    <li><a href="<?php echo $socialInfo['YoutubeId']; ?>" target="_blank"><i class="icons1 ic_youtube"></i></a></li>
                    <?php } ?>

                    <?php  if($socialInfo['PersonalURL'] && $socialInfo['PersonalURL'] != ''){ 
                            
                            if (strpos($socialInfo['PersonalURL'], 'https://') === false) {
                              $socialInfo['PersonalURL'] = 'https://'.$socialInfo['PersonalURL'];
                            }
                      ?>
                    <li><a href="<?php echo $socialInfo['PersonalURL']; ?>" target="_blank"><i class="icons1 ic_webicon"></i></a></li>
                    <?php } ?>
                    
                  </ul>

                  

                </div>
                <?php if($publicProfile != true){?>
                  <div class="prf-rght">
                  <a href="/userprofile/<?php echo $userData['userId']; ?>" target="_blank" class="btn-grey">View your public profile </a>
                  </div>
                <?php } ?>

                <?php if($publicProfile == true && $loggedInUserId > 0 && $loggedInUserId != $userId){?>
                  <?php if($isUserFollowing == 'follow'){ ?>
                    <a href="javaScript:void(0)" class="public-un-btn public-fl-btn pblc-flwUnflw" follow="1" entity='user' followEntityId='<?php echo $userId; ?>'>UNFOLLOW</a> 
                  <?php }else if($isUserFollowing == 'unfollow' || $isUserFollowing == ''){  ?>
                    <a href="javaScript:void(0)" class="public-fl-btn pblc-flwUnflw" follow="0" entity='user' followEntityId='<?php echo $userId; ?>'>FOLLOW</a> 
                  <?php } ?>
                <?php } ?> 

          <p class="clr"></p>
            </section>

			
		</div>

    <?php
    $js = array('header', 'mail');
    $jsFooter = array('ana_common');
  $cvsJsIncludedOnPage = '';
  if(is_array($js)){
    $cvsJsIncludedOnPage = implode(",",$js);
  }
  if(is_array($jsFooter)){
    if(strlen($cvsJsIncludedOnPage) > 0)
      $cvsJsIncludedOnPage .= ','.implode(",",$jsFooter);
    else
      $cvsJsIncludedOnPage .= implode(",",$jsFooter);
  }
?>
<input type="hidden" name="cvsJsIncludedOnPage" id="cvsJsIncludedOnPage" value="<?php echo $cvsJsIncludedOnPage; ?>" />
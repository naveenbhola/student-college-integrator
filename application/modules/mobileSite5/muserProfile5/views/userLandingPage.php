<div class="loader-col initial_hide">
    <div class="three-quarters-loader-activity">Loading…</div>
</div>

<div class="profile-container">
    <div class="user-profile-detail">

        <?php $this->load->view('userProfilePicture');?>

        <?php $this->load->view('userNameSection'); ?>
        <?php if($publicProfile == true && $loggedInUserId['userId'] > 0 && $loggedInUserId['userId'] != $userId){ ?>
            <?php if($isUserFollowing == 'follow'){ ?>
                <a href="javaScript:void(0)" class="mbl-fl-btn mbl-un-btn pblc-flwUnflw" follow="1" entity='user' followEntityId='<?php echo $userId; ?>'>UNFOLLOW</a>
            <?php }else if($isUserFollowing == 'unfollow' || $isUserFollowing == ''){ ?>
                <a href="javaScript:void(0)" class="mbl-fl-btn pblc-flwUnflw" follow="0" entity='user' followEntityId='<?php echo $userId; ?>'>FOLLOW</a>
            <?php } ?>
        <?php } ?>
    </div>

</div>
    <div class="tab-container">
        <div class="tabs-cont clearFix">
            <div class="tabs-section">
                <?php if(!$publicProfile){ ?>
                <ul class="tabs-ul">
                    <li class="" id="aboutMeTab">
                        <a href="#tab_0">About Me</a>
                    </li>
                    <li class="" id="activityTab">
                        <a href="#tab_1">Activity Stats</a>
                    </li>
                    <p class="clr"></p>
                </ul>
                <?php }else{ ?>
                <ul class="tabs-ul">
                    <li class="" id="aboutMeTab">
                        <a href="#tab_0">About</a>
                    </li>
                    <li class="" id="activityTab">
                        <a href="#tab_1">Activity</a>
                    </li>
                    <p class="clr"></p>
                </ul>
                <?php } ?>
                <div class="tab-content">
                    <div class="tabs-panel-cont" id="tab_0">
                        <div class="profile-background-sec">

                         <?php if( (!empty($additionalInfo['Bio']) || !empty($additionalInfo['StudentEmail']) || !empty($personalInfo['Country'])) && $publicProfile ) {
                            $personalInfoFlag = true;
                            ?>

                             <div class="profile-col" id="personalInformationSection">
                                <?php $this->load->view('profilePagePersonalInformation');?>
                            </div>


                         <?php } else if(!$publicProfile){
                             $personalInfoFlag = true;
                          ?>
                            <div class="profile-col" id="personalInformationSection">
                                <?php $this->load->view('profilePagePersonalInformation');?>
                            </div>
                         <?php }?>

                         <?php
                            if( !empty($tenth['InstituteName']) || !empty($twelfth['InstituteName']) || !empty($UG['InstituteName']) || !empty($PG['InstituteName']) || !empty($PHD['InstituteName']) || (!empty($UG['CourseCompletionDate']) && $UG['CourseCompletionDate'] !=' -000') || (!empty($tenth['CourseCompletionDate']) && $tenth['CourseCompletionDate'] !=' -000') || ( !empty($twelfth['CourseCompletionDate']) && $twelfth['CourseCompletionDate'] !=' -000') ) {
                                $flagEdu = true;
                            }

                         if($publicProfile && $flagEdu == true){
                            $educationBckFlag = true;
                                ?>

                            <div class="profile-col" id="educationalBackgroundSection">
                                <?php $this->load->view('userProfileEducationBackground'); ?>
                            </div>
                        <?php } else if(!$publicProfile){
                            $educationBckFlag = true;
                            ?>
                            <div class="profile-col" id="educationalBackgroundSection">
                                <?php $this->load->view('userProfileEducationBackground'); ?>
                            </div>
                        <?php }?>

                         <?php
                                $flag = false;
                                for ($i=1; $i <=10 ; $i++) {
                                    $workExLevel = 'workExp'.$i;
                                    $workExLevelData = $$workExLevel;

                                    if(!empty($workExLevelData)){
                                        $flag= true;
                                        break;
                                    }
                                }

                            if( $publicProfile && (!empty($personalInfo['Experience']) || $personalInfo['Experience'] == '0'  || $flag == true) ){
                                $workExFlag= true;
                                ?>
                            <div id='workExperienceSection'>
                                <?php $this->load->view('userProfileWorkEx');?>
                            </div>
                         <?php } else if(!$publicProfile){
                                $workExFlag= true;
                            ?>
                            <div id='workExperienceSection'>
                                <?php $this->load->view('userProfileWorkEx');?>
                            </div>
                          <?php }

                          if( $publicProfile && $userProfilePrivacy['DesiredCourse'] == 'public') {
                                $eduPreferenceFlag = true;
                                 $this->load->view('userEducationalPreference');
                             }else if(!$publicProfile){
                                $eduPreferenceFlag = true;
                                $this->load->view('userEducationalPreference');
                             }


                            if($publicProfile && !(empty($expertiseInfo['stream']) && empty($expertiseInfo['country']) )){
                                $this->load->view('userExpertise');
                            } else if(!$publicProfile){
                                $this->load->view('userExpertise');
                            }

                            if(!$publicProfile){
                                $this->load->view('userEducationPreferencesTags');
                            }



                           ?>

                        </div>
					<?php if(!$eduPreferenceFlag && !$workExFlag && !$educationBckFlag && !$personalInfoFlag){?>

                        <p class="private-prf"><i class=""></i></p>
                            <?php
                                echo '<p class="private-prf-txt">The user has decided to keep his profile private.</p>';

                            }
                        ?>
                        <p class="clr"></p>
                    </div>
                    <div class="tabs-panel-cont titl-h2" id="tab_1">

                        <?php if(!$publicProfile){ ?>
                            <h3 class="activity-head">STATS
                            <?php
                                $privacyFields = array('0'=>'activitystats');
                                $privacyFields = serialize($privacyFields);
                                $publicFlag = false;
                                if($privacyDetails['activitystats'] == 'public'){
                                    $publicFlag = true;
                                }
                                $this->load->view('userTogglePrivacy',array('privacyFields' =>$privacyFields,'publicFlag'=>$publicFlag));
                            ?>
                            </h3>
                            <?php
                                $this->load->view('userProfileActivityStats');
                                if(!empty($activities)){
                            ?>
                            <div class="user-act-titl">
                              <div class="titl-h2 flLt">
                                <h3>Latest Activity</h3>
                              </div>
                               <p class="clr"></p>
                           </div>
						        <div id="user_recent_activities">
                                    <input type="hidden" id="ajaxCallCounterRecent" value="0"/>
                                    <?php $this->load->view('userProfileRecentActivity');?>

						      </div>
                          <?php    }else{
                                 echo '<p class="private-prf-txt">There is no activity to display.</p>';
                            } ?>
                                <div style="text-align: center; margin-top: 10px; display: none;" id="loadingNew">
                                    <img class="small-loader" border="0" alt="" id="loadingImageNew" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif">
                                </div>
                       <?php  }else if($publicProfile && $privacyDetails['activitystats'] == 'public' && !empty($stats)){ ?>
                        <h3 class="activity-head">STATS</h3>
    				    <?php

                            $this->load->view('userProfileActivityStats');
                            if(!empty($activities)){
                            ?>
                            <div class="user-act-titl">
                              <div class="titl-h2 flLt">
                                <h3>Latest Activity</h3>
                              </div>
                               <p class="clr"></p>
                           </div>
                          <div id="user_recent_activities">
                            <input type="hidden" id="ajaxCallCounterRecent" value="0"/>
                            <?php

                                $this->load->view('userProfileRecentActivity');

                            ?>
                          </div>
                          <?php

                                }else{
                                 echo '<p class="private-prf-txt">There is no activity to display.</p>';
                            }

                          ?>
                            <div style="text-align: center; margin-top: 10px; display: none;" id="loadingNew">
                                <img class="small-loader" border="0" alt="" id="loadingImageNew" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif">
                            </div>
						                <?php }else{
                            echo '<p class="private-prf-txt">There is no activity to display.</p>';
                        }
                    ?>
                    </div>

                </div>
            </div>
            <p class="clr"></p>
        </div>
    </div>
    <a href="#pagetwo" id="pageTwotransition" data-transition="slideup" class="ui-link"></a>
    <a href="#tupples" id="tuppleTransition" data-transition="slideup" class="ui-link"></a>

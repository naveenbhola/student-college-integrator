<div id="allContentTuple" style="position: relative;">
    <div class="loaderAjax" id="allContentTupleLoader" style="display: none;background: rgba(0, 0, 0, 0.180392);"></div>   
                      <div class="active" data-index="1">
                             <div> 
                                 <?php
                                 $i=0;
                                  foreach ($data['homepage'] as $key=>$homeTabData){
                                          if($homeTabData['type'] == 'Q')
                                          {
                                              $entityType = 'question';
                                              $actionType = 'answer';
                                              $followTrackingPageKeyId = $qfollowTrackingPageKeyId;
                                              $tdowntrackingPageKeyId = $tdownatrackingPageKeyId;
                                              $tuptrackingPageKeyId = $tupatrackingPageKeyId;
                                              $postingTrackignPageKeyId = $atrackingPageKeyId;
                                              if($homeTabData['answerCount'] >= 1) 
                                              {
                                                  $GA_Tap_On_Child_CTA      = $GA_Tap_On_Answer_CTA;
                                                  $GA_Tap_On_Child          = $GA_Tap_On_Answer;
                                                  $GA_Tap_On_View_More_Child = $GA_Tap_On_ViewMore_Ans;
                                                  $GA_Tap_On_Follow         =  $GA_Tap_On_Follow_QUES;
                                                  $GA_Tap_On_Share          =  $GA_Tap_On_Share_Ques;
                                                  $GA_Tap_On_Child_Owner    = $GA_Tap_On_Owner_Ans;
                                                  $GA_Tap_On_Tag            = $GA_Tap_On_Tag_Ques;
                                                  $GA_Tap_On_Parent         = $GA_Tap_On_Ques;
                                                  $GA_Tap_On_Upvote         = $GA_Tap_On_Upovte_Ans;
                                                  $GA_Tap_On_Downvote       = $GA_Tap_On_Downvote_Ans; 
                                                  $GA_Tap_On_Follow_List    = $GA_Tap_On_Follow_List_QUES; 
                                              }
                                              else
                                              {
                                                  $GA_Tap_On_Child_CTA      = $GA_Tap_On_Answer_CTA_UN;
                                                  $GA_Tap_On_Follow         =  $GA_Tap_On_Follow_QUES_UN;
                                                  $GA_Tap_On_Tag            = $GA_Tap_On_Tag_Ques_UN;
                                                  $GA_Tap_On_Share          =  $GA_Tap_On_Share_Ques_UN;
                                                  $GA_Tap_On_Parent         = $GA_Tap_On_Ques_UN;
                                                  $GA_Tap_On_Follow_List    = $GA_Tap_On_Follow_List_QUES_UN;
                                              }
                                              
                                          }
                                          else if($homeTabData['type'] == 'D')
                                          {
                                              $entityType = 'discussion';
                                              $actionType = 'comment';
                                              $followTrackingPageKeyId = $dfollowTrackingPageKeyId;
                                              $tdowntrackingPageKeyId = $tdowndctrackingPageKeyId;
                                              $tuptrackingPageKeyId = $tupdctrackingPageKeyId;
                                              $postingTrackignPageKeyId = $ctrackingPageKeyId;

                                              $GA_Tap_On_Child_CTA      = $GA_Tap_On_Com_CTA;
                                              $GA_Tap_On_Child          = $GA_Tap_On_Comment;
                                              $GA_Tap_On_View_More_Child = $GA_Tap_On_ViewMore_Com;
                                              $GA_Tap_On_Follow         =  $GA_Tap_On_Follow_Disc;
                                              $GA_Tap_On_Share          =  $GA_Tap_On_Share_Disc;
                                              $GA_Tap_On_Child_Owner    = $GA_Tap_On_Owner_Com;
                                              $GA_Tap_On_Tag            = $GA_Tap_On_Tag_Disc;
                                              $GA_Tap_On_Parent         = $GA_Tap_On_Disc;
                                              $GA_Tap_On_Upvote         = $GA_Tap_On_Upovte_Com;
                                              $GA_Tap_On_Downvote       = $GA_Tap_On_Downvote_Com;
                                              $GA_Tap_On_Follow_List    = $GA_Tap_On_Follow_List_DISC;
                                          }
                                          $heading = $homeTabData['heading'];
                                          if($homeTabData['setHeadingUsername'] == 1){
                                              $heading = ucfirst($homeTabData['headingUsername'])." ".$heading;
                                          }

                                          if($homeTabData['followerCount'] >= 1000){
                                             $totalFollowCount =  round(($homeTabData['followerCount']/1000),1).'k';
                                            
                                          }else{
                                             $totalFollowCount = $homeTabData['followerCount'];
                                          }

                                          if($homeTabData['viewCount'] >= 1000){
                                             $totalViewCount =  round(($homeTabData['viewCount']/1000),1).'k';
                                            
                                          }else{
                                             $totalViewCount = $homeTabData['viewCount'];
                                          }

                                          $viewCountText = ($homeTabData['viewCount']== 1) ? 'View' : 'Views';
                                          $viewCountText = $totalViewCount." ".$viewCountText;

                                          $followerCountText = ($homeTabData['followerCount']> 1) ? 'Followers' : 'Follower';
                                          $followerCountText = $totalFollowCount." ".$followerCountText;

                                          if($data['tagsRecommendations'] && $data['showTagsRecommendationsAtPostion'] == $key){
                                            $this->load->view("messageBoard/desktopNew/tagsRecommendation");
                                          }
                                          if($data['userRecommendations'] && $data['showUserRecommendationsAtPostion'] == $key){
                                            $this->load->view("messageBoard/desktopNew/usersRecommendation");
                                          }
                                      ?>
                                      <div class="post-col" questionid="<?php echo $homeTabData['id']?>" answerid="<?php echo $homeTabData['answerId'] ? $homeTabData['answerId'] : 0;?>" type="<?php echo $homeTabData['type']?>" tracking="true">
                                        <?php
                                        if($homeTabData['type'] == 'D'){
                                          echo  '<i class="disc-ico"></i>';
                                        }
                                        ?>
                                      
                                         <div class="dtl-qstn col-head">
                                             <span>
                                              <?=$homeTabData['activityTime'];?>
                                             </span>

                                           <a href="<?=$homeTabData['URL'];?>" ga-attr="<?=$GA_Tap_On_Parent;?>"><?=$homeTabData['title'];?></a>
                                         </div>
                                         <!---->
                                         <div class="new-column cta-block">
                                            <div class="right-cl">
                                                <?php if(1 || $homeTabData['followerCount'] > 0){

                                                  $followerCountClass = "follower";
                                                  $revClass = "viewers-span";
                                                  $clickElement = "getListOfUsersUpvotedFollowed('".$homeTabData['id']."','".$entityType."','follow','".$flistfollowTrackingPageKeyId."')";

                                                  if($homeTabData['followerCount'] == 0){
                                                    $followerCountClass = "viewers-span";
                                                    $revClass = "follower";
                                                    $styleElement = '';
                                                  }
                                                  else
                                                  {
                                                    $styleElement = "";
                                                  }
                                                  ?>
                                                    <span class="<?=$followerCountClass;?> followersCountTextArea" revClass="<?php echo $revClass;?>" curClass="<?php echo $followerCountClass;?>" style="<?php echo $styleElement;?>" ga-attr="<?=$GA_Tap_On_Follow_List;?>" onclick="<?php echo $clickElement;?>" valueCount="<?php echo $homeTabData['followerCount'];?>" listElement="list_<?php echo $homeTabData['id'];?>"><?=$followerCountText;?></span>
                                                    <!-- <input type="hidden" id="userCountFollower_<?php echo $homeTabData['id'];?>" value="<?php echo $homeTabData['followerCount'];?>"> -->
                                                  <?php
                                                }
                                                ?>
                                                <?php if($homeTabData['viewCount'] > 0) {
                                                  ?>
                                                    <Span class="viewers-span"><?=$viewCountText;?></Span>
                                                  <?php
                                                }
                                                ?>
                                                
                                            </div>
                                            <div class="left-cl">
                                               <ul class="nav-discussion">
                                                 <li class="nav-item"><a class="nav-lnk" ga-attr="<?=$GA_Tap_On_Share;?>" onClick="socialLayer(this, '<?=$homeTabData['id']?>','<?=$homeTabData['URL']?>','<?php if($homeTabData['type']=='D'){echo 'discussion';} else{ echo 'question';} ?>');"><i class="share"></i></a></li>
                                                 <?php
                                                  if($homeTabData['isUserFollowing'] == 'true'){
                                                 ?>
                                                 <li class="nav-item"><a class="ana-btns un-btn" curclass="un-btn" reverseclass="f-btn" href="javascript:void(0);" callforaction="unfollow" ga-attr="<?=$GA_Tap_On_Follow;?>" onclick="followEntity(this,<?php echo $homeTabData['id'];?>,'<?php echo $entityType;?>',false,'<?php echo $followTrackingPageKeyId;?>')">Unfollow</a></li>
                                                  <?php
                                                  }else {
                                                  ?>
                                                  <li class="nav-item"><a class="ana-btns f-btn" curclass="f-btn" reverseclass="un-btn" callforaction="follow" href="javascript:void(0);" ga-attr="<?=$GA_Tap_On_Follow;?>" onclick="followEntity(this,<?php echo $homeTabData['id'];?>,'<?php echo $entityType;?>',false,'<?php echo $followTrackingPageKeyId;?>')">Follow</a></li>
                                                <?php
                                                  }
                                                 ?>
                                                 
                                               </ul>
                                            </div>
                                            <p class="clr"></p>

                                          <?php if($homeTabData['type'] == 'Q'){
                                              $postingAction = "initializeAnswerPostingAnA('".$homeTabData['id']."')";
                                          }else{
                                              $postingAction = "initializeCommentPostingAnA('".$homeTabData['id']."')";               
                                          } ?>

                                         
                                 <!--user image col-->
                                 </div>
                                  <?php
                                      if($homeTabData['answerCount'] >= 1){ 
                                        ?>
                                    <div class="avatar-col">
                                      <?php 
                                            $profileUrl = "";

                                            if($homeTabData['answerOwnerUserId'] == $loggedInUser){
                                              $profileUrl = SHIKSHA_HOME."/userprofile/edit";
                                            }else {
                                              $profileUrl = SHIKSHA_HOME."/userprofile/".$homeTabData['answerOwnerUserId'];
                                            }
                                      ?>
                                      <a href='<?=$profileUrl;?>' ga-attr="<?=$GA_Tap_On_Child_Owner;?>">
                                      <div class="new-avatar">
                                        <?php

                                            if($homeTabData['answerOwnerImage'] != '' && strpos($homeTabData['answerOwnerImage'],'/public/images/photoNotAvailable.gif') === false){?>
                                                <img class="lazy" data-original="<?php echo getSmallImage($homeTabData['answerOwnerImage']);?>" alt="Shiksha Ask & Answer" style="width: 55px;height: 55px;cursor:pointer;">

                                            <?php }else{
                                                echo ucwords(substr($homeTabData['answerOwnerName'],0,1));
                                          }
                                          ?>
                                      </div>
                                    </a>
                                      
                                          <div class="inf-block">
                                               <a href='<?=$profileUrl;?>' class="avatar-name" ga-attr="<?=$GA_Tap_On_Child_Owner;?>"><?=$homeTabData['answerOwnerName']?><span class="currentStu" style="display:none;" id="currentStudent_<?=$homeTabData['answerOwnerUserId']?>">Current Student</span>
                                                <?php if(!empty($homeTabData['aboutMe'])) {?>
													<span class=""><?=$homeTabData['aboutMe'];?></span>
												<?php } ?>
											  </a>
                                               <p class="g-l"><?=$homeTabData['answerOwnerLevel'];?></p>
                                               <div class="rp-txt">
                                                      <?php 
                                                        $ansTextlen = strlen(strip_tags($homeTabData['answerText']));
                                                        if($ansTextlen > 250){
                                                            $con = substr(strip_tags($homeTabData['answerText']),0,250).'...';    

                                                            ?>
                                                            <div id="lessAnswer_<?=$homeTabData['answerId']?>" class='lessAnswer' onclick="viewFullAnswerText('<?=$homeTabData['answerId']?>')" ga-attr="<?=$GA_Tap_On_View_More_Child;?>"><?=$con;?>
                                                              <a href="javascript:void(0)" class="link" id="viewMoreBtn_<?=$homeTabData['answerId']?>" ga-attr="<?=$GA_Tap_On_View_More_Child;?>" onclick="viewFullAnswerText('<?=$homeTabData['answerId']?>')">View More</a>
                                                            </div>
                                                            <?php
                                                        }else{
                                                            echo $homeTabData['answerText']; 
                                                        }

                                                        if($ansTextlen > 250){?>
                                                            <div id="entityTxt_<?=$homeTabData['answerId'];?>" style = "display:none;" onclick=""><?php echo $homeTabData['answerText']; ?></div>

                                                            
                                                        <?php } ?>
                                                                   
                                               </div>
                                                 <div class="opinion-col">
                                                      <?php
                                                      $upvotedClass = "";
                                                      $downvotedClass = "";
                                                      if($homeTabData['hasUserVotedUp'] == 'true'){
                                                      $upvotedClass = 'b-like';
                                                      $downvotedClass = 'g1-dislike';
                                                      $upvotedReverseClass = 'g-like';
                                                      $downvotedReverseClass = 'b-dislike';
                                                      $upvotedDisableClass = 'g1-like';
                                                      $downvotedDisableClass = 'g-dislike';
                                                      $upvotedAction = 'cancelupvote';
                                                      $downvotedAction = 'noaction';
                                                    }else if($homeTabData['hasUserVotedDown'] == 'true'){
                                                      $upvotedClass = 'g1-like';
                                                      $downvotedClass = 'b-dislike';
                                                      $upvotedReverseClass = 'b-like';
                                                      $downvotedReverseClass = 'g-dislike';
                                                      $upvotedDisableClass = 'g-like';
                                                      $downvotedDisableClass = 'g1-dislike';
                                                      $upvotedAction = 'noaction';
                                                      $downvotedAction = 'canceldownvote';
                                                    }else{
                                                      $upvotedClass = 'g-like';
                                                      $downvotedClass = 'g-dislike';
                                                      $upvotedReverseClass = 'b-like';
                                                      $downvotedReverseClass = 'b-dislike';
                                                      $upvotedDisableClass = 'g1-like';
                                                      $downvotedDisableClass = 'g1-dislike';
                                                      $upvotedAction = 'upvote';
                                                      $downvotedAction = 'downvote';
                                                    }
                                                      ?>
                                                       <a class="up-thumb like-a" href="javascript:void(0)" ga-attr="<?=$GA_Tap_On_Upvote;?>" onclick="setEntityRatingAnA(this,'<?php echo $homeTabData['answerId']?>','<?php echo $actionType;?>',false,'<?php echo $tuptrackingPageKeyId;?>')" callforaction="<?php echo $upvotedAction;?>"><i class="<?php echo $upvotedClass;?>" reverseclass="<?php echo $upvotedReverseClass;?>" disabledclass="<?php echo $upvotedDisableClass;?>"></i><?=$homeTabData['likeCount'];?></a>
                                                       <a class="up-thumb like-d" href="javascript:void(0)" ga-attr="<?=$GA_Tap_On_Downvote;?>" onclick="setEntityRatingAnA(this,'<?php echo $homeTabData['answerId']?>','<?php echo $actionType;?>',false,'<?php echo $tdowntrackingPageKeyId;?>')" callforaction="<?php echo $downvotedAction;?>"><i class="<?php echo $downvotedClass;?>" reverseclass="<?php echo $downvotedReverseClass;?>" disabledclass="<?php echo $downvotedDisableClass;?>"></i><?=$homeTabData['dislikeCount'];?></a>
                                                    </div>
                                          </div>
                                        <?php
                                      }
                                      ?>
                                      <?php
                                      if($homeTabData['answerCount'] > 1){
                                        ?>
                                        <div class="v-cmnts"><a href='<?=$homeTabData['URL'];?>' ga-attr="<?=$GA_Tap_On_Child;?>">View All <?=$homeTabData['answerCount'];?>
                                        <?php if($homeTabData['type'] == 'Q')
                                                echo "Answers";
                                              else 
                                                echo "Comments"; ?>
                                            </a>
                                        </div>
                                        <?php  
                                      }
                                    
                                     if($homeTabData['answerCount'] >= 1){
                                        echo "</div>";
                                     }

                                        ?> 
                                 
                               </div>
                                      <?php
                                    $i++;
                                    if($i==3)
                                    {
                                      $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'ANA_C_C2','bannerType'=>"content"));
                                      $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'ANA_C_C2_2','bannerType'=>"content"));
                                    }
                                    if($i==6)
                                    {
                                      $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'ANA_C_C1','bannerType'=>"content"));
                                      $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'ANA_C_C1_2','bannerType'=>"content"));
                                    }
                                  }
                                                              if($i<3 || $i<6)
                            {
                              $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'ANA_C_C2','bannerType'=>"content"));
                              $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'ANA_C_C2_2','bannerType'=>"content"));
                            }
                                 ?>
                                        

                                   
                             </div>    
                           <p class="clr"></p>
                    </div>
                    </div>
                <?php if($callType == 'AJAX'){
                  ?>
                  <script type="text/javascript">
                    $j(document).ready(function(){
                      //$j("#nextPaginationIndex").val("<?php echo $nextPaginationIndex; ?>");
                     // $j("#nextPageNo").val("<?php echo $nextPageNo; ?>");
                          nextPaginationIndex = "<?php echo $nextPaginationIndex;?>";
                          nextPageNo = "<?php echo $nextPageNo;?>";
                    });
                </script>
                  <?php  
                }

		if(count($data['homepage']) == 0){
			if($contentType == 'question'){
				echo "<div style='color: #9e9e9e;font-size: 14px;padding: 40px 0;text-align: center;'>No questions yet</div>";
                        }else if($contentType == 'discussion'){
                                echo "<div style='color: #9e9e9e;font-size: 14px;padding: 40px 0;text-align: center;'>No discussions yet</div>";
			}else{
				echo "<div style='color: #9e9e9e;font-size: 14px;padding: 40px 0;text-align: center;'>No unanswered questions yet</div>";
			}
		}

   
                      <div class="active" data-index="1">
                             <div> 
                                 <?php
                                  $i = 0;
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

					  //In case of TDP, modify the Heading
					  if($pageName == "tagDetailPage"){
						if($homeTabData['type'] == 'Q'){
							if($homeTabData['answerCount'] >= 1){
								$heading = "New answer posted";
							}
							else{
								$heading = "New question posted";
							}
						}
						else if($homeTabData['type'] == 'D'){
                                                        if($homeTabData['answerCount'] >= 1){
                                                                $heading = "New comment posted";
                                                        }
                                                        else{
                                                                $heading = "New discussion posted";
                                                        }
						}
					  }
					  //End change on TDP

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
				          if($callType!='AJAX' && $key == 2 && $pageType == 'home'){
				            echo Modules::run('messageBoard/AnADesktop/articleWidgetAnA');
				          }

                                      ?>
                                      <div class="post-col" questionid="<?php echo $homeTabData['id']?>" answerid="<?php echo $homeTabData['answerId'] ? $homeTabData['answerId'] : 0;?>" type="<?php echo $homeTabData['type']?>" tracking="true">
                                        <?php
                                        if($homeTabData['type'] == 'D'){
                                          echo  '<i class="disc-ico"></i>';
                                        }
                                        ?>
                                      
                                         <div class="col-head">
                                             <p class="titl"><?=$heading;?></p>
                                             <span>
                                              <?=$homeTabData['activityTime'];?>
                                             </span>
                                             <p class="clr"></p>
                                         </div>
                                         <!--questions link block-->
                                         <div class="ana-qstn-block">
                                         <?php
                                         if(!empty($homeTabData['tags'])){
                                            ?>
                                              <div class="qstn-row">
                                                <?php
                                                  foreach ($homeTabData['tags'] as $tagsData) {
                                                    if(!isset($tagsData['tagName']) || $tagsData['tagName'] == ''){
                                                        continue;
                                                    }
                                                    if(!$tagsData['url'])
                                                    $tagUrl = getSeoUrl($tagsData['tagId'], 'tag', $tagsData['tagName']);
                                                  else
                                                    $tagUrl = $tagsData['url'];
                                                    ?>
                                                    <a <?php if($tagsData['type']!="ne") {?> class="ent-tgClr" <?php } ?> href="<?=$tagUrl;?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Tag;?>','<?php echo $GA_userLevel;?>');"><?=$tagsData['tagName'];?></a>
                                                    <?php
                                                  }
                                                ?>
                                               
                                            </div>
                                            <?php
                                         }
                                         ?>
                                         </div>
                                         <!---->
                                         <div class="dtl-qstn">
                                           <a href="<?=$homeTabData['URL'];?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Parent;?>','<?php echo $GA_userLevel;?>');"><?=$homeTabData['title'];?></a>
                                         </div>
                                         <!---->
                                         <div class="new-column cta-block">
                                            <div class="right-cl">
                                                <?php if(1 || $homeTabData['followerCount'] > 0){

                                                  $followerCountClass = "follower";
                                                  $revClass = "viewers-span";
                                                  $clickElement = "gaTrackEventCustom('".$GA_currentPage."','".$GA_Tap_On_Follow_List."','".$GA_userLevel."');getListOfUsersUpvotedFollowed('".$homeTabData['id']."','".$entityType."','follow','".$flistfollowTrackingPageKeyId."')";

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
                                                    <span class="<?=$followerCountClass;?> followersCountTextArea" revClass="<?php echo $revClass;?>" curClass="<?php echo $followerCountClass;?>" style="<?php echo $styleElement;?>" onclick="<?php echo $clickElement;?>" valueCount="<?php echo $homeTabData['followerCount'];?>" listElement="list_<?php echo $homeTabData['id'];?>"><?=$followerCountText;?></span>
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
                                                 <li class="nav-item"><a class="nav-lnk" onClick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Share;?>','<?php echo $GA_userLevel ;?>');socialLayer(this, '<?=$homeTabData['id']?>','<?=$homeTabData['URL']?>','<?php if($homeTabData['type']=='D'){echo 'discussion';} else{ echo 'question';} ?>');"><i class="share"></i></a></li>
                                                 <?php
                                                  if($homeTabData['isUserFollowing'] == 'true'){
                                                 ?>
                                                 <li class="nav-item"><a class="ana-btns un-btn" curclass="un-btn" reverseclass="f-btn" href="javascript:void(0);" callforaction="unfollow" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Follow;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $homeTabData['id'];?>,'<?php echo $entityType;?>',false,'<?php echo $followTrackingPageKeyId;?>')">Unfollow</a></li>
                                                  <?php
                                                  }else {
                                                  ?>
                                                  <li class="nav-item"><a class="ana-btns f-btn" curclass="f-btn" reverseclass="un-btn" callforaction="follow" href="javascript:void(0);" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Follow;?>','<?php echo $GA_userLevel ;?>');followEntity(this,<?php echo $homeTabData['id'];?>,'<?php echo $entityType;?>',false,'<?php echo $followTrackingPageKeyId;?>')">Follow</a></li>
                                                <?php
                                                  }
                                                 ?>
                                                  <?php
                                                    if($homeTabData['type'] == 'Q') {
                                                      
                                                      if($homeTabData['isThreadOwner'] == true){?>
                                                          <li class="nav-item"><a class="ana-btns a-btn" href="javascript:void(0)" onclick="showAnswerResponseMessage('You cannot answer your own question.');">Answer</a></li>

                                                      <?php }else if($homeTabData['hasUserAnswered'] == true){ ?>
                                                          <li class="nav-item"><a class="ana-btns a-btn" href="javascript:void(0)" onclick="showAnswerResponseMessage('You cannot answer more than once on the same question.');">Answer</a></li>

                                                      <?php }else if($homeTabData['threadStatus'] == 'closed'){?>
                                                          <li class="nav-item"><a class="ana-btns a-btn" href="javascript:void(0)" onclick="showAnswerResponseMessage('This question is closed for answering.');">Answer</a></li>

                                                      <?php }else{?>
                                                          <li class="nav-item"><a class="ana-btns a-btn" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Child_CTA;?>','<?php echo $GA_userLevel;?>');makeAnswerPostingLayer('<?=$homeTabData['id']?>','0','add');">Answer</a></li>
                                                      <?php } ?>
                                                      <?php
                                                    }else {
                                                      ?>
                                                      <li class="nav-item"><a class="ana-btns a-btn" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Child_CTA;?>','<?php echo $GA_userLevel;?>');makeCommentPostingLayer('<?=$homeTabData['id']?>','0')">Comment</a></li>
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

                                           <!--entity-posting-col-->
                                          <div id="entityPostingCol_<?=$homeTabData['id']?>" style="display:none;">
                                           <form id="postEntityAnA_<?=$homeTabData['id']?>" action=""  accept-charset="utf-8" method="post"  novalidate="novalidate" name="postAnswerAnA">
                                             <div class="ans-block" >
                                                <p class="txt-count" id="entityTxtCounter<?=$homeTabData['id']?>" style="display:block;top:11px">Characters <span id="entity_text_<?=$homeTabData['id']?>_counter">0</span>/2500</p>
                                                <textarea placeholder="Type Your <?=ucfirst($actionType)?>" onkeypress="handleCharacterInTextField(event,true);" onkeyup="autoGrowField(this,300);textKey(this)" validate="validateStr" minlength=15 maxlength=2500 caption="Answer" id="entity_text_<?=$homeTabData['id']?>" required="true" onpaste="handlePastedTextInTextField('entity_text_<?=$homeTabData['id']?>',true);"></textarea>
                                                <div>
                                                  <p class="err0r-msg"  id="entity_text_<?=$homeTabData['id']?>_error"></p>
                                                </div>
                                                <div class="btns-col">
                                                   <span class="right-box">
                                                        <a class="exit-btn" href="javascript:void(0);" onclick = "hideCommentPostBox('<?=$homeTabData['id']?>');">Cancel</a>
                                                        <a class="prime-btn" href="javascript:void(0);" id="entityPostingButton<?=$homeTabData['id']?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Child_CTA;?>','<?php echo $GA_userLevel ;?>');if(!validateCommentAnswerPostingField('entity_text_<?=$homeTabData['id']?>','postEntityAnA_<?=$homeTabData['id']?>')){return false;}else{<?=$postingAction?>}">Post</a>
                                                    </span>
                                                    <p class="clr"></p>
                                                </div>
                                                <input type="hidden" id="threadId_<?=$homeTabData['id']?>" value="<?=$homeTabData['id']?>" />
                                                <input type="hidden" id="actionOnAns_<?=$homeTabData['id']?>" value="add" />
                                                <input type="hidden" id="editEntityId<?=$homeTabData['id']?>" value="0" />
                                                <input type="hidden" id="parentType<?=$homeTabData['id']?>" value="<?=$entityType?>" />
                                                <input type="hidden" id="entityType<?=$homeTabData['id']?>" value="<?=ucfirst($actionType)?>" />
                                                <input type="hidden" id="tracking_keyid_<?php echo $homeTabData['id']?>" value="<?php echo $postingTrackignPageKeyId;?>">
                                             </div>
                                         </form>
                                       </div>
                                       <!--entity posting col end-->
                                         
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
                                      <a href='<?=$profileUrl;?>' onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Child_Owner;?>','<?php echo $GA_userLevel;?>');">
                                      <div class="new-avatar">
                                        <?php

                                            if($homeTabData['answerOwnerImage'] != '' && strpos($homeTabData['answerOwnerImage'],'/public/images/photoNotAvailable.gif') === false){?>
                                                <img src=<?php echo getSmallImage($homeTabData['answerOwnerImage']);?> alt="Shiksha Ask & Answer" style="width: 55px;height: 55px;cursor:pointer;">

                                            <?php }else{
                                                echo ucwords(substr($homeTabData['answerOwnerName'],0,1));
                                          }
                                          ?>
                                      </div>
                                    </a>
                                      
                                          <div class="inf-block">
                                               <a href='<?=$profileUrl;?>' class="avatar-name" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Child_Owner;?>','<?php echo $GA_userLevel;?>');"><?=$homeTabData['answerOwnerName']?><span class=""><?=$homeTabData['aboutMe'];?></span></a>
                                               <p class="g-l"><?=$homeTabData['answerOwnerLevel'];?></p>
                                               <div class="rp-txt">
                                                      <?php 
                                                        $ansTextlen = strlen(strip_tags($homeTabData['answerText']));
                                                        if($ansTextlen > 650){
                                                            $con = substr(strip_tags($homeTabData['answerText']),0,650).'...';    

                                                            ?>
                                                            <div id="lessAnswer_<?=$homeTabData['answerId']?>" class='lessAnswer' onclick="viewFullAnswerText('<?=$homeTabData['answerId']?>')"><?=$con;?>
                                                              <a href="javascript:void(0)" class="link" id="viewMoreBtn_<?=$homeTabData['answerId']?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_View_More_Child;?>','<?php echo $GA_userLevel;?>');viewFullAnswerText('<?=$homeTabData['answerId']?>')">View More</a>
                                                            </div>
                                                            <?php
                                                        }else{
                                                            echo $homeTabData['answerText']; 
                                                        }

                                                        if($ansTextlen > 650){?>
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
                                                       <a class="up-thumb like-a" href="javascript:void(0)" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Upvote;?>','<?php echo $GA_userLevel;?>');setEntityRatingAnA(this,'<?php echo $homeTabData['answerId']?>','<?php echo $actionType;?>',false,'<?php echo $tuptrackingPageKeyId;?>')" callforaction="<?php echo $upvotedAction;?>"><i class="<?php echo $upvotedClass;?>" reverseclass="<?php echo $upvotedReverseClass;?>" disabledclass="<?php echo $upvotedDisableClass;?>"></i><?=$homeTabData['likeCount'];?></a>
                                                       <a class="up-thumb like-d" href="javascript:void(0)" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Downvote;?>','<?php echo $GA_userLevel;?>');setEntityRatingAnA(this,'<?php echo $homeTabData['answerId']?>','<?php echo $actionType;?>',false,'<?php echo $tdowntrackingPageKeyId;?>')" callforaction="<?php echo $downvotedAction;?>"><i class="<?php echo $downvotedClass;?>" reverseclass="<?php echo $downvotedReverseClass;?>" disabledclass="<?php echo $downvotedDisableClass;?>"></i><?=$homeTabData['dislikeCount'];?></a>
                                                    </div>
                                          </div>
                                        <?php
                                      }
                                      ?>
                                      <?php
                                      if($homeTabData['answerCount'] > 1){
                                        ?>
                                        <div class="v-cmnts"><a href='<?=$homeTabData['URL'];?>' onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Child;?>','<?php echo $GA_userLevel ;?>');">View All <?=$homeTabData['answerCount'];?>
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
                                if(($callType == 'AJAX' && $nextPaginationIndex<=10) || $callType != 'AJAX') {
                                      $i++;
                                      if($callType != 'AJAX' && $i==3)
                                      {
                                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_LAA','bannerType'=>"content"));
                                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_LAA1','bannerType'=>"content"));
                                      }
                                      if(($pageName!='ANAHomepage' && $i==6) ||  ($callType == 'AJAX' && $i==3))
                                      {
                                       $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_AON','bannerType'=>"content"));
                                       $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_AON1','bannerType'=>"content"));
                                      }
                                  }
                                  if($callType != 'AJAX' && $i==count($data['homepage'] ) && count($data['homepage'] )<3)
                                  { 
                                      $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_LAA','bannerType'=>"content"));
                                      $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_LAA1','bannerType'=>"content"));
                                  }
                                }
                                 ?>

                             </div>    
                           <p class="clr"></p>
                    </div>
                <?php if($callType == 'AJAX'){
                  ?>
                  <script type="text/javascript">
                    $j(document).ready(function(){
                          nextPaginationIndex = "<?php echo $nextPaginationIndex;?>";
                          nextPageNo = "<?php echo $nextPageNo;?>";
                    });
                </script>
                  <?php  
                }

		if(count($data['homepage']) == 0){
			if(in_array($pageType, array('ALL_QUESTION_PAGE','ALL_DISCUSSION_PAGE'))){
				echo "<div style='color: #9e9e9e;font-size: 14px;padding: 40px 0;text-align: center;'>".$data['zeroResultPageResponse']."</div>";
			}else{
				echo "<div style='color: #9e9e9e;font-size: 14px;padding: 40px 0;text-align: center;'>".$data['responseMessage']."</div>";
			}
		}

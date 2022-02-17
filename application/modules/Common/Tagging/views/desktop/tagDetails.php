                    <div class="post-col cta-block">
                               <div class="tag-head">
                                  <h1 class="tag-p"><?=$data['tagName']?></h1>

                                   <?php if($data['questionCount'] >= 3){
                                        $tdpTopMsg =  "Get insights from ".formatNumber($data['questionCount'])." questions on ".$data['tagName'].", answered by students, alumni, and experts. You may also ask and answer any question you like about ".$data['tagName'];
                                        ?>
                                        <p class="tag-bind"><?=$tdpTopMsg?></p>
                                   <?php } ?>

                                  <?php if($data['isUserFollowing'] == "true"){ ?>
                                  <a class="ana-btns un-btn" curclass="un-btn" reverseclass="f-btn" href="javascript:void(0);" callforaction="unfollow" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Follow_Top;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $data['tagId'];?>,'tag',false,'<?php echo $topFollowTrackingPageKeyId;?>')">Unfollow</a>
                                  <?php } else { ?>
                                  <a class="ana-btns f-btn" id="tag_follow_btn" curclass="f-btn" reverseclass="un-btn" callforaction="follow" href="javascript:void(0);" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Follow_Top;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $data['tagId'];?>,'tag',false,'<?php echo $topFollowTrackingPageKeyId;?>')">Follow</a>
                                  <?php } ?>
                                  <span style="display:none;" class="followersCountTextArea" curClass="" revClass="" valueCount=<?=$data['followerCount']?>><?=$data['followerCount']?></span>

				  <a class="ana-btns a-btn" id ="tagAskNow" ga-attr="ASK_QUESTION">Ask Question</a>
                               </div>
                               <div class="ana-table">
                                   <div class="ana-cell">
                                       <div class="rl-div"> 
                                         <b><?php echo $data['questionCount'] ? formatNumber($data['questionCount']) : 0;?></b>
                                         <h2>Questions</h2>
                                        </div> 
                                   </div>
                                   <div class="ana-cell">
                                       <div class="rl-div">  
                                         <b><?php echo $data['discussionCount'] ? formatNumber($data['discussionCount']) : 0;?></b>
                                         <h2>Discussions</h2>
                                       </div>  
                                   </div>
                                   <div class="ana-cell">
                                       <div class="rl-div">
                                         <b><?php echo $data['expertCount'] ? formatNumber($data['expertCount']) : 0;?></b>
                                         <h2>Active Users</h2>
                                        </div> 
                                   </div>
                                   <div class="ana-cell">
                                       <div class="rl-div">
                                         <b class="followCnt followersCountTextArea" valueCount=<?php echo $data['followerCount']?>><?php echo $data['followerCount'] ? formatNumber($data['followerCount']) : 0;?></b>
                                         <h2>Followers</h2>
                                       </div>
                                   </div>
                               </div>
                    </div>


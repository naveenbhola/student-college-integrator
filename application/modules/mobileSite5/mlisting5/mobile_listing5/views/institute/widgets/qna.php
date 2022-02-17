<?php
    if($listing_type == 'university')
    {
        $tuptrackingPageKeyId = 1066;
        $tdowntrackingPageKeyId = 1067;
    }elseif($listing_type == "course"){
      $GA_Tap_User_Profile = 'USER_PROFILE_QnA_TUPLE_COURSEDETAIL_MOBILE';
      $GA_Tap_On_View_More = 'VIEW_MORE_ANSWER_COURSEDETAIL_MOBILE';
      $GA_Tap_On_Upvote = 'UPVOTE_ANSWER_COURSEDETAIL_MOBILE';
      $GA_Tap_On_Downvote = 'DOWNVOTE_ANSWER_COURSEDETAIL_MOBILE';
      $GA_Tap_On_Ques = 'QUESTION_TITLE_COURSEDETAIL_MOBILE';
      $GA_Tap_On_View_All = 'VIEW_ALL_QUESTIONS_COURSEDETAIL_MOBILE';
      $tuptrackingPageKeyId = 1071;
      $tdowntrackingPageKeyId = 1072;
    }
    else if($listing_type == 'institute')
    {
       $tuptrackingPageKeyId = 1061;
       $tdowntrackingPageKeyId = 1062;
    }
    if($listing_type == 'university' || $listing_type == 'institute' || $listing_type == 'ExamPage')
    {
        $GA_Tap_User_Profile = 'USER_PROFILE_QnA_TUPLE';
        $GA_Tap_On_View_More = 'VIEW_MORE_ANSWER';
        $GA_Tap_On_Upvote = 'UPVOTE_ANSWER';
        $GA_Tap_On_Downvote = 'DOWNVOTE_ANSWER';
        $GA_Tap_On_Ques = 'QUESTION_TITLE';
        $GA_Tap_On_View_All = 'VIEW_ALL_QUESTIONS';
    }
        if($totalNumber == 1){
                $displayString = "(Showing 1 of 1 Q&A)";
        }
        else if($totalNumber >= 2){
		$displayNumber = formatNumber($totalNumber);
                $displayString = "(Showing 2 of $displayNumber Q&A)";
        }
        $actionType = 'answer';
        if(count($questionsDetail)>0){
?>
<div class="crs-widget listingTuple" id="qna">
        <h2 class="head-L2">Ask & Answer<span class="head-L5 pad-left4"><?=$displayString?></span></h2>
        <div class="lcard">
	    <?php foreach ($questionsDetail as $homeTabData){ 
			$viewCountString = ($homeTabData['viewCount']==1)?'1 View':$homeTabData['viewCount'].' Views';
			$answerCountString = ($homeTabData['msgCount']==1)?'1 Answer':$homeTabData['msgCount'].' Answers';
	    ?>

            <div class="qna-list <?php if($homeTabData == end($questionsDetail)){echo 'last';}?>">
                <div class="type-of-que">
                    <a class="d-txt" href="<?=$homeTabData['URL'];?>"  ga-attr="<?=$GA_Tap_On_Ques;?>"><?=$homeTabData['title'];?></a>
                    <span class="a-span"><ol>
			<?php if($homeTabData['msgCount'] > 0){ ?>
			<li><p><?=$answerCountString?></p></li>
			<?php }
			 if($homeTabData['viewCount'] > 0){ ?>
			<li><p><?=$viewCountString?></p><span></span></li>
			<?php } ?>
		    </ol> </span>
                </div>
		<?php if(isset($homeTabData['answerId']) && $homeTabData['answerId']>0){ ?>
                                      <?php
                                            $profileUrl = "";
                                            if($homeTabData['answerOwnerUserId'] == $loggedInUser){
                                              $profileUrl = SHIKSHA_HOME."/userprofile/edit";
                                            }else {
                                              $profileUrl = SHIKSHA_HOME."/userprofile/".$homeTabData['answerOwnerUserId'];
                                            }
                                      ?>

                <div class="user-card card-in">
		    <a href='<?=$profileUrl;?>' ga-attr="<?=$GA_Tap_User_Profile;?>">
                    <div class="user-mt-card">
                        <div class="user-i-card">
                                        <?php

                                            if($homeTabData['answerOwnerImage'] != '' && strpos($homeTabData['answerOwnerImage'],'/public/images/photoNotAvailable.gif') === false){?>
                                                <img class="lazy" data-original=<?php echo getSmallImage($homeTabData['answerOwnerImage']);?> alt="Shiksha Ask & Answer" style="width: 60px;height: 60px;cursor:pointer;">

                                            <?php }else{
                                                echo ucwords(substr($homeTabData['answerOwnerName'],0,1));
                                          }
                                          ?>
			</div>

                        <div class="mt-inf-card">
                            <p class="u-name"><?=$homeTabData['answerOwnerName']?></p>
			    <p class="u-level" style="display:none;" id="currentStudent_<?=$homeTabData['answerOwnerUserId']?>">Current Student</span>
			    <p class="u-level"><?=$homeTabData['aboutMe'];?></p>
                            <p class="u-level"><?=$homeTabData['answerOwnerLevel'];?></p>
                            <span class="show-time"><i class="clock-ico"></i><?=$homeTabData['activityTime'];?></span>
                        </div>
                        <p class="clr"></p>
                    </div>
		    </a>

                    <p class="user-review"><?=cutStringWithShowMoreAnswer($homeTabData['answerText'],300,'answer'.$homeTabData['answerId'],'more','answer')?></p>
                </div>
		<?php } ?>
            </div>

	    <?php } ?>

      <input type="hidden" name="gaPageName" id="gaPageName_answer" value="<?php echo $GA_currentPage;?>">
      <input type="hidden" name="gaActionName" id="gaActionName_answer" value="<?php echo $GA_Tap_On_View_More;?>">
      <input type="hidden" name="gaUserLevel" id="gaUserLevel_answer" value="<?php echo $GA_userLevel;?>">

	    <?php if($totalNumber > 2){ ?>
		<a href="<?=$allQuestionURL?>" class="btn-mob-ter" ga-attr="<?=$GA_Tap_On_View_All;?>" id="qna_count">View all <?=$displayNumber?> questions</a>
	    <?php } ?>

        </div>
    </div>
<?php } ?>

<?php if(!empty($totalNumber)){?>
  <script>
  var totalQues = '<?php echo $totalNumber;?>';
  </script>
<?php } ?>

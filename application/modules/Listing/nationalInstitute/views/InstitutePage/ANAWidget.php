<?php
        $GA_Tap_User_Profile = 'USER_PROFILE_QnA_TUPLE';
        $GA_Tap_On_View_More = 'VIEW_MORE_ANSWER';
        $GA_Tap_On_Upvote = 'UPVOTE_ANSWER';
        $GA_Tap_On_Downvote = 'DOWNVOTE_ANSWER';
        $GA_Tap_On_Ques = 'QUESTION_TITLE';
        $GA_Tap_On_View_All = 'VIEW_ALL_QUESTIONS';
        $GA_Tap_On_View_All_Ans = 'VIEW_ALL_ANSWERS';
        $GA_Tap_On_View_One_Ans = 'VIEW_ONE_ANSWER';
    $answerLength = 80;
   if($listing_type == 'university')
    {
        $tuptrackingPageKeyId = 1003;
        $tdowntrackingPageKeyId = 1004;
    }
    else if($listing_type == 'institute')
    {
       $tuptrackingPageKeyId = 992;
       $tdowntrackingPageKeyId = 993;
    }
    else if($listing_type == 'course'){
       $tuptrackingPageKeyId = 1007;
       $tdowntrackingPageKeyId = 1008;
    }

	if($totalNumber >= 2 && $page != 'allCoursePage'){
		$displayNumber = ' '.formatNumber($totalNumber);
		$displayString = "(Showing ".count($questionsDetail)." of$displayNumber questions)";
	}
	$actionType = 'answer';

    $displayCount = 2;
    if($listing_type=='ExamPage' || $page == 'allCoursePage'){
         $displayCount = 3;
       	 $answerLength = 97;
    }

    if(!isset($showCR)){
	$showCR = true;
    }
?>
<div class="new-row listingTuple anaListing" id="ana">
       <div class="post-col gap">
            <div class="lcard eborder">
              <h2 class="head-1 gap">Ask &amp; Answer  <span class="para-6"><?=$displayString?></span></h2>

      <div class="new__head">
      <?php if(count($questionsDetail)>0 && $totalNumber > 1){ ?>
        <div class="ana__col" <?php if(!$showCR){echo "style='width:100%;border-right:0px;'";} ?> >
      <?php foreach ($questionsDetail as $homeTabData){ ?>
                    <div class="new__qstn">
                       <div class="dtl-qstn col-head">
                       <strong <?php if(!$showCR){echo "style='width:2%;'";} ?>>Q:</strong>
                         <a href="<?=$homeTabData['URL'];?>" ga-attr="<?=$GA_Tap_On_Ques?>"><?=$homeTabData['title'];?></a>
                        </div>
                          
                         <!--answer-col-->
			<?php if(isset($homeTabData['answerId']) && $homeTabData['answerId']>0){ ?>

                                      <?php
                                            $profileUrl = "";
                                            if($homeTabData['answerOwnerUserId'] == $loggedInUser){
                                              $profileUrl = SHIKSHA_HOME."/userprofile/edit";
                                            }else {
                                              $profileUrl = SHIKSHA_HOME."/userprofile/".$homeTabData['answerOwnerUserId'];
                                            }
                                      ?>
                <div class="inf-block">
                <strong <?php if(!$showCR){echo "style='width:2%;'";} ?> >A:</strong>
                  <p>
                    <?=cutString($homeTabData['answerText'],$answerLength)?>
                    </p>
                    <p class="anwr__usr">
                    <?php if(!empty($homeTabData['answerOwnerName'])) { ?>
                        <a class="stu-usr" href='<?=$profileUrl;?>' ga-attr="<?=$GA_Tap_User_Profile;?>"><strong class="avatar-name"><span>by</span> <?=$homeTabData['answerOwnerName']?> <span class="g-l" style="display:none;" id="currentStudent_<?=$homeTabData['answerOwnerUserId']?>">Current Student</span></strong></a> 
                    <?php } ?>
                    <span class="g-l"> <?=$homeTabData['answerOwnerLevel']?></span>
                    <?php
                        if($homeTabData['msgCount'] == 1){
                              if(strlen(strip_tags($homeTabData['answerText'])) >= $answerLength){ ?>
                             <span class="v-cmnts"><a class="arr__after" href="<?=$homeTabData['URL'];?>#expandanswer" ga-attr="<?=$GA_Tap_On_View_One_Ans?>">Read Full Answer</a></span>
                             <?php }
                        } else {?>
                              <span class="v-cmnts"><a class="arr__after" href="<?=$homeTabData['URL'];?>#expandanswer" ga-attr="<?=$GA_Tap_On_View_All_Ans?>">Read All Answers (<?=$homeTabData['msgCount']?>)</a></span>
                   <?php } ?>
                  </p>
                </div> 
			 <?php } ?>
          </div>
       <?php } ?>
        <?php if($totalNumber > $displayCount){ ?>  
          <div class="gradient-col">
                      <a href="<?=$allQuestionURL?>" class="button button--secondary arw_link" ga-attr="<?=$GA_Tap_On_View_All?>" style="text-transform:none;" id="ana_count">View All<?=$displayNumber?> Questions</a>
          </div>
        <?php } ?>
        </div>

	<?php if($listing_type!='ExamPage' && $showCR){ ?>
        <div class="qstners__block que-only0" style="position:relative;">
            <div id='askProposition' style="box-shadow: none; position:absolute; top: 45%; left: 45%;">
              <div style='text-align: center; width: 40px; height: 40px;'><img border='0' alt='' id='loadingImage1' class='small-loader' style='border-radius:50%;border: 1px solid rgb(229, 230, 230)' src='//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif'/></div>
            </div>
        </div>
	<?php } ?>
<input type="hidden" name="gaActionName" id="gaActionName_answer" value="<?php echo $GA_Tap_On_View_More;?>">
<?php } else if($listing_type!='ExamPage' && $showCR){ ?>
        <div class="qstners__block que-only" >
          <div style="padding: 120px 0 3px 0;">
              <div id='askProposition' style="box-shadow: none; position:absolute; top: 45%; left: 45%;">
                <div style='text-align: center; width: 40px; height: 40px;'><img border='0' alt='' id='loadingImage1' class='small-loader' style='border-radius:50%;border: 1px solid rgb(229, 230, 230)' src='//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif'/></div>
              </div>
            
          </div>
        </div>
  <?php } ?>
      </div>
    </div> 
  </div>
</div>

<script>
var totalQues = '<?php echo $totalNumber;?>';
</script>

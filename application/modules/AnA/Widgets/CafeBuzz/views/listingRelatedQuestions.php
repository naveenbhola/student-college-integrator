<?php
if(is_array($topicListings) && count($topicListings)>0){
?>

<?php
$questionIdsListArray = explode(',',$topicListings['questionList']);
$userDetails = $topicListings['userDetails'];
foreach ($questionIdsListArray as $questionId){
        $questionArray = $topicListings[$questionId];
        $questionUserId = $questionArray['questionUserId'];
?>
<!-- Main Div Start -->
<div class="aqAns" style="border-bottom:1px solid #eaeeed;padding-top:15px;padding-bottom:10px;">

        <!-- Start Question section -->
        <div class="wdh100">

                <!-- Block Start to display the User image -->
                <div class="imgBx">
                    <img src="<?php echo ($userDetails[$questionUserId]['avtarimageurl'] && $userDetails[$questionUserId]['avtarimageurl']!='')?getSmallImage($userDetails[$questionUserId]['avtarimageurl']):getSmallImage('/public/images/photoNotAvailable.gif');?>"/>
                </div>
                <!-- Block End to display the User image -->

                <div class="cntBx">
                        <div class="wdh100 float_L">
                        
                                <!-- Start Question owner, Level and Question Display section -->
                                <div class="ana-box" style="padding-bottom:10px;color:#000000">
                                        <span class="sprite-bg ques-icn"></span>
                                        <div class="ques-cont" style="color:#000000;width:550px;">
                                            <span><b><?=$userDetails[$questionUserId]['displayname']?></b>&nbsp;</span>
                                            <a href="<?=$questionArray['url']?>" style="color:#000000;" >
                                            <?php
                                                $questionText = html_entity_decode(html_entity_decode($questionArray['questionText'],ENT_NOQUOTES,'UTF-8'));
                                                $questionText = formatQNAforQuestionDetailPageWithoutLink($questionText);
                                                echo "<span>".$questionText."</span></a>";
                                            ?>
                                            <div class='mtb5'>
                                                <span class="Fnt11 float_L">
                                                    <span class='Fnt11 grayFont'>
                                                        <?php echo makeRelativeTime($questionArray['creationDate']); ?> in <?php echo $questionArray['category']." - ".$questionArray['country']; ?>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                </div>
                                <!-- End Question owner, Level and Question Display section -->

                                <?php
                                //After displaying the question, we have to display the Answer according to the Sorted order
                                $sortedAnswerOrderArray = explode(",",$questionArray['sortedAnswerList']);
                                $sortedAnswerOrderArray = array_unique($sortedAnswerOrderArray);
                                $i=0;
                                foreach ($sortedAnswerOrderArray as $answerId){
                                    if($answerId!='' && $answerId>0 && $i<$numberOfAnswerPerQuestion){
                                    $displayArrow = true;
                                    $answerArray = $questionArray['Answers'][$answerId];
                                    $answerUserId = $answerArray['answerUserId'];
                                ?>
                                <!-- Start Answer display -->
                                <div class="ana-box" style="padding-bottom: 5px;">
                                        <span class="sprite-bg ans-icn"></span>
                                        <div class="ans-cont" style="width:550px;">
                                                <span style="width:30px;display:inline;"><b><?php echo $userDetails[$answerUserId]['displayname'];?></b></span>
                                                <?php
                                                    $answerText = html_entity_decode(html_entity_decode($answerArray['answerText'],ENT_NOQUOTES,'UTF-8'));
                                                    $answerText = formatDiscussionforList($answerText);
                                                    echo "<span>".$answerText."</span>";
                                                ?>
                                                <div class="mtb5">
                                                        <span class="Fnt11"><span class="grayFont"><?php echo makeRelativeTime($answerArray['creationDate']); ?></span></span>
                                                        <?php if($answerArray['bestAnsFlag']==1){ ?>
                                                        <div class="float_R"><span class="bestStar">Best Answer</span></div>			
                                                        <div style="line-height:5px;clear:both">&nbsp;</div>
                                                        <?php } ?>
                                                </div>
                                                
                                                <!-- Start Comment display section -->
                                                <?php $commentNumberBeingDisplayed = 0;
                                                foreach ($answerArray['Comments'] as $commentArray){
                                                $commentNumberBeingDisplayed++;
                                                if( count($answerArray['Comments']) > $numberOfCommentPerAnswer && $commentNumberBeingDisplayed < (count($answerArray['Comments'])- ($numberOfCommentPerAnswer-1) ) ){
                                                    continue;
                                                }
                                                $commentUserId = $commentArray['commentUserId'];
                                                ?>
                                                <div style="margin-top:5px;">
                                                        <?php if($displayArrow){ ?>
                                                            <div class="pl33" style='padding-left:66px;'><img src="/public/images/upArw.png" /></div>
                                                        <?php
                                                            $displayArrow = false;
                                                        } ?>
                                                        <div class="fbkBx" style="width:507px;padding-left:10px;padding-right: :10px;">
                                                            <div class="Fnt11" style="padding-top:2px;">
                                                              <span>
                                                                      <span><b><?php echo $userDetails[$commentUserId]['displayname']; ?>&nbsp;</b></span>
                                                                      <?php
                                                                            $commentDisplayText = html_entity_decode(html_entity_decode($commentArray['commentText'],ENT_NOQUOTES,'UTF-8'));
                                                                            $commentDisplayText = formatDiscussionforList($commentDisplayText);
                                                                            echo $commentDisplayText;
                                                                      ?>
                                                              </span>
                                                            </div>
                                                            <div class="Fnt11 fcdGya grayFont" style="padding-top:2px;">
                                                              <span><?php echo makeRelativeTime($commentArray['creationDate']);?></span>
                                                            </div>
                                                        </div>
                                                </div>
                                                <?php } ?>
                                                <!-- End Comment display section -->                                
                                        </div>
                                </div>
                                <!-- End Answer display -->
                                <?php $i++;
                                    }
                                } ?>

                        </div>
                </div>
		<div class="clear_B"></div>

        </div>
</div>

<?php
}
}
else{
    echo "No Related Questions found!";
}
?>
<?php if($searchAgainFlag == 1){?>
	<script>
	var url = "/listing/Listing/searchAgainListingQuestion";
	var instituteId = <?php echo $instituteId;?>;
	new Ajax.Request(url, { method:'post', parameters: ('instituteId='+instituteId) });
	</script>
<?php } ?>

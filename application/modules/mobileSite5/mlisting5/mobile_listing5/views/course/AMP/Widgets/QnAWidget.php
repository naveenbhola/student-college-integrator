<?php
$GA_Tap_On_View_More = 'VIEW_MORE_ANSWER';
$GA_Tap_On_Ques = 'QUESTION_TITLE';

if($totalNumber == 1){ 
	$displayString = "(Showing 1 of 1 question)";
}
else if($totalNumber >= 2){
	$displayNumber = formatNumber($totalNumber);
	$displayString = "(Showing 2 of $displayNumber questions)";
}

?>
<?php if($totalNumber > 0) {?>
<section id="ana-section">
    <div class="data-card m-5btm" id="ana">
                 <h2 class="color-3 f16 heading-gap font-w6">Ask &amp; Answer <span class="f12 font-w4 color-3"> <?php echo $displayString;?> </span></h2>
                 <div class="card-cmn color-w">
                     <ul class="d">
			<?php foreach ($questionsDetail as $homeTabData){ ?>

			<?php
			$viewCountString = ($homeTabData['viewCount']==1)?'1 View':$homeTabData['viewCount'].' Views';
                        $answerCountString = ($homeTabData['msgCount']==1)?'1 Answer':$homeTabData['msgCount'].' Answers';
			?>
                         <li>
                             <div class="">
                                 <div class="">
                                     <a class="color-3 font-w6 l-18 block f16 m-5btm ga-analytic" href="<?php echo $homeTabData['URL'];?>" data-vars-event-name="<?=$GA_Tap_On_Ques;?>"><?php echo $homeTabData['title'];?></a>
                                     <span class="a-span block">
                                         <ol>
                                             <li class="i-block">
					     <?php if($homeTabData['msgCount'] > 0){ ?>
						<p class="color-3 f10 pos-rl"><?php echo $answerCountString;?></p></li>
					     <?php } ?>
                                             <li class="i-block"><p class="color-3 f10 pos-rl"><i class="dot"></i></p></li>
                                             <li class="i-block">
						<?php if($homeTabData['viewCount'] > 0){ ?>
						<p class="color-3 f10 pos-rl"><?php echo $viewCountString;?></p>
						<?php } ?>
					        <span></span>
					    </li>
                                         </ol>
                                     </span>
                                 </div>
                                 <div class="m-15top">
                                     <p class="m-5btm color-6 f14">Answered by <strong class="color-3 f13 font-w6"><?php echo $homeTabData['answerOwnerName'];?></strong></p>
                                     <div>
					 <input type="checkbox" class="read-more-state hide" id="answer<?php echo $homeTabData['answerId'];?>">
                                         <p class="f13 color-3 l-18 font-w4 read-more-wrap word-break"><?php echo CutStringWithShowMoreInAMP($homeTabData['answerText'],300,'answer'.$homeTabData['answerId'],'more',false,true,$GA_Tap_On_View_More)?></p>
                                     </div>
                                 </div>
                             </div>

                         </li>
			<?php } ?>
                     </ul>
		     <?php if($totalNumber > 2){ ?>
                     <a class="btn btn-ter color-w color-3 f14 font-w6 m-15top ga-analytic" href="<?php echo $allQuestionURL;?>" data-vars-event-name="ASK_VIEWALL">View all <?php echo $displayNumber;?> questions</a>
		     <?php } ?>
                 </div>
             </div>
         </section>
<?php } ?>

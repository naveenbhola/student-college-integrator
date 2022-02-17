<p class="mentor-foll-ans">Answer The Following Questions:</p>
<?php
$i = 0;
$mentorSampleAns;
$slider = 0;
$sampleAnsCountArr = array();
$sliderStartArr = array();
$autoSlideArr = array();
foreach($mentorQues as $key => $value)
{
    $i++;
?>
    <p class="mentor-ques-str"><?=$i.'. '.$value['questionText']?></p>
    <?php
    if($value['type'] != 'csv' && count($mentorSampleAns[$value['qid']]) > 0)
    {
        $slider++;
        $sampleAnsCountArr[] = count($mentorSampleAns[$value['qid']]);
        $sliderStartArr[]    = 0;
        $autoSlideArr[]      = 'setInterval(function(){changeSlider('.$slider.');},slideTimeGap)';
    ?>
    <p><a href="javascript:void(0);" onclick="showSlide(<?=$slider?>);" id="viewSampleReviews_<?=$slider?>">View Sample Answer</a></p>
    <div class="review-slider flLt" style="display:none;" id="review-slider_<?=$slider?>">
    <div style="width:497px; overflow: hidden;">
       <ul style="width:<?=count($mentorSampleAns[$value['qid']])*497?>px; position: relative; left:0px;" id="slideContainer_<?=$slider?>">
           <?php
           foreach($mentorSampleAns[$value['qid']] as $k=>$sampleAns)
           {
            ?>
            <li id="slide1_<?=$slider?>" style="float: left; width:497px;">
                <!--p class="review-title">What to write?</p-->
                <p><?=$sampleAns['sampleAnswerText']?></p>
            </li>
            <?php
           }
           ?>
           <div class="clearFix"></div>
       </ul>
    </div>
    <div class="clearFix" style="padding-top:5px; <?=(count($mentorSampleAns[$value['qid']])<2?'display:none':'')?>">
        <div class="flLt"><i class="campus-sprite pause-icon" onclick="pauseSlider(<?=$slider?>);" id="sliderPlayPauseButton_<?=$slider?>"></i></div>
        <div class="slider-bullets flRt">
        <ul>
            <?php
            for($bullet=1; $bullet <= count($mentorSampleAns[$value['qid']]); $bullet++)
            {
                $active = '';
                if($bullet == 1)
                    $active = 'active';
            ?>
                <li id="slidecontrol<?=$bullet?>_<?=$slider?>" class="<?=$active?>" style="cursor:pointer;" onclick="slide(<?=$slider?>, <?=$bullet?>,'bullet');"></li>
            <?php
            }
            ?>
        </ul>
        </div>
    </div>
    </div>
    <?php
    }
    if($value['type'] == 'csv')
    {
        if($mentorAns[$value['qid']]['answerText'] != '')
            $userExams = explode(',', $mentorAns[$value['qid']]['answerText']);
        ?>
        <div class="slt-box-list">
            <ul class="slt-list-opt">
            <?php
            foreach($examsTaken as $index=>$exam)
            {
                $checked = '';
                if(in_array($exam, $userExams))
                    $checked = 'checked="checked"';
            ?>
                <li><input type="checkbox" onchange="makeUserExamString('<?=$value['qid']?>')" name="userExamTaken[]" class="userExamTaken" id="userExamTaken<?=$index?>" value="<?=$exam?>" <?=$checked?> /><label for="userExamTaken<?=$index?>"><?=$exam?></label></li>
            <?php
            }
            ?>
            </ul>
            <input type="hidden" value="" id="mentorAnswer_<?=$value['qid']?>" name="mentorAnswer[<?=$value['qid']?>]" />
        </div>
        <div style="display: inline;"><div id="userExamTaken_error" class="errorMsg"></div></div>
        <?php
    }
    else
    {
    ?>
    <div class="write-review-sec" style="margin-bottom: 30px;">
        <textarea maxlength="10000" minlength="200" required="true" validate="validateStr" caption="answer" class="write-textarea2" name="mentorAnswer[<?=$value['qid']?>]" id="mentorAnswer_<?=$value['qid']?>" style="margin-bottom: 3px;"><?=$mentorAns[$value['qid']]['answerText']?></textarea>
        <div style="display: inline;"><div id="mentorAnswer_<?=$value['qid']?>_error" class="errorMsg"></div></div>
        <p>(Minimum 200 characters)</p>
    </div>
    <?php
    }
    ?>
<?php
}
?>
<script>
    <?php
    
    ?>
    var numSlidesArr = [<?=implode(',', $sampleAnsCountArr)?>];
    var currentSlideVal = [<?=implode(',', $sliderStartArr)?>];
    var slideIntervals = [<?=implode(',', $autoSlideArr)?>];
</script>
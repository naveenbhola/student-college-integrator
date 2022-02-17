<?php if(count($quesData)>0){?>

<div class="campus-college-container" id="mostViewedContainer">
    <div class="campus-college-container-link">
        <h2><a href="javascript:void(0);" class="<?php echo ($quesWidgetType=='mostViewQuestion')?'active':''; ?>" id="mostViewedQuestionLink">Most Viewed Questions</a></h2>
        <h2><a class="<?php echo ($quesWidgetType=='trendingQuestion')?'active':''; ?>" id="trendingQuestionLink" href="javascript:void(0);">Trending Questions</a></h2>
        <div class="campus-college-sub-container2" id="mostViewedInnerContainer" style="height: 320px; overflow: hidden">
        <div class="scrollbar1">
        <div class="scrollbar">
            <div class="track">
                <div id="thumbbranch" class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height: 310px; width: 99%">
            <div class="overview">
        <?php
        foreach($quesData as $key=>$val){
        ?>
        <p>
        <a href="<?php echo getSeoUrl($val['msgId'], 'question', $val['msgTxt']);?>" onclick="trackEventByGA('VIEW_QUESTION_FROM_CCHOME','CC_Homepage_Question_View_From-<?php echo $quesWidgetType?>');"><?php echo $val['msgTxt']?></a><br>
        <span><?php echo $instituteData[$val['instituteId']]['name']?>, <?php echo $val['viewCount']?> View(s)</span><br>
        <span class="campus-answer" id="mv-span1-<?php echo $key?>"><?php echo substr(html_entity_decode($answerData[$val['msgId']],ENT_NOQUOTES,'UTF-8'),0,140)?></span>
        <span class="campus-answer" id="mv-span2-<?php echo $key?>" style="display: none;"><?php echo html_entity_decode($answerData[$val['msgId']],ENT_NOQUOTES,'UTF-8')?></span>
            <?php
            if(strlen($answerData[$val['msgId']])>140)
            {
            ?>
                <a class="sml recreateScrollBar2" onclick="$j('#mv-span2-<?php echo $key?>').show('slow'); $j('#mv-span1-<?php echo $key?>').hide(); $j(this).hide(); var scrollbar2 = $j('#mostViewedContainer .scrollbar1').data('plugin_tinyscrollbar'); scrollbar2.update(scrollbar2.contentPosition); trackEventByGA('ANSWER_READ_MORE_CCHOME', 'CCHome_Answer_Read_More');" href="javascript:void(0);">Read more</a>
            <?php
            }
            ?>
        </p>
        <?php
        }
        ?>
            </div>
        </div>
        </div>
        </div>
    </div>
</div>
<?php } ?>
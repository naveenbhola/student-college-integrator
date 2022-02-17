<?php if(count($quesData)>0){?>
<div class="campus-college-container" id="topQuestionContainer">
    <div class="campus-college-container-link">
        <h2><a href="javascript:void(0);" class="<?php echo ($quesWidgetType=='topRanked')?'active':''; ?>" id="topQuestionLink">Top Questions</a></h2>
        <h2><a class="<?php echo ($quesWidgetType=='featured')?'active':''; ?>" id="featuredQuestionList" href="javascript:void(0);">Featured Questions</a></h2>
        <div class="campus-college-sub-container2" id="topQuestionInnerContainer" style="height: 320px; overflow: hidden">
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
                if(!empty($courseRepo) && is_object($courseRepo)){
                    $courseObj = $courseRepo->find($val['courseId']);
                    $instituteName = $courseObj->getInstituteName();
                }else{
                    $instituteName = $result[$val['instituteId']]['name'];
                }
            ?>
            <p>
                <a href="<?php echo getSeoUrl($val['msgId'], 'question', $val['msgTxt']);?>" onclick="trackEventByGA('VIEW_QUESTION_FROM_CCHOME','CC_Homepage_Question_View_From-<?=$quesWidgetType?>');"><?=$val['msgTxt']?></a><br>
                <span><?php echo $instituteName?>, <?=$val['viewCount']?> View(s)</span><br>
                <span class="campus-answer" id="span1-<?=$key?>"><?=substr(html_entity_decode($answerData[$val['msgId']],ENT_NOQUOTES,'UTF-8'),0,140)?></span>
                <span class="campus-answer" id="span2-<?=$key?>" style="display: none;"><?=html_entity_decode($answerData[$val['msgId']],ENT_NOQUOTES,'UTF-8')?></span>
                <?php
                if(strlen($answerData[$val['msgId']])>140)
                {
                ?>
                    <a class="sml recreateScrollBar1" onclick="$j('#span2-<?=$key?>').show('slow'); $j('#span1-<?=$key?>').hide(); $j(this).hide(); var scrollbar1 = $j('#topQuestionContainer .scrollbar1').data('plugin_tinyscrollbar'); scrollbar1.update(scrollbar1.contentPosition); trackEventByGA('ANSWER_READ_MORE_CCHOME', 'CCHome_Answer_Read_More');" href="javascript:void(0);">Read more</a>
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
<script>
    if (topRankedInstitutes=='') {
        topRankedInstitutes = '<?=json_encode($dataForTopQuestion)?>';
    }
</script>
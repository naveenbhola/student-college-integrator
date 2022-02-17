<?php
    $courses = $widgetData['countryHomePopularCourses'];
?>
<div class="<?=($floatClass)?> popular-widget" style="height:345px;">
    <div class="popular-widget-title"><?=($widgetConfigData['countryHomePopularCourses']['title'])?></div>
    <div class="popular-widget-detail">
        <strong>Most viewed courses in last 14 days</strong>
        <dl>
            <?php foreach($courses as $key=>$course){ ?>
            <dt><a href="<?=($course['categoryPageURL'])?>"><?=(formatArticleTitle($course['courseTitle'],45))?></a> (<?=($course['courseCount'])?> college<?=($course['courseCount']>1?'s':'')?>)</dt>
            <dd>
                <?php if($course['avgExamScore']['examName'] != '' && ($course['avgExamScore']['avgScore'] >0 || in_array($course['avgExamScore']['avgScore'],array('A','B','C')))){ ?>
                <div class="eligibility-fee-col">
                    <p>Avg exam score</p>
                    <p><?=($course['avgExamScore']['examName'])?>: <?=($course['avgExamScore']['avgScore'])?></p>
                </div>
                <?php } ?>
                <?php if($course['avgFees'] != ''){ ?>
                <div class="eligibility-fee-col">
                    <p>Avg 1st year total fees</p>
                    <p><?=($course['avgFees'])?></p>
                </div>
                <?php } ?>
                <div class="clearfix"></div>
            </dd>
            <?php } ?>
            
        </dl>
    </div>
</div>

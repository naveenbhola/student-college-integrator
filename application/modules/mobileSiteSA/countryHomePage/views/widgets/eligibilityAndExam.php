<?php
    $widgetData = $coursesData[$mainCourseId]['widgetData'];
    $data = $widgetData['countryHomeEligibilityExamScore']; 
    $graphData = $data['graphSectionData'];
?>
<?php if(count($data['contentSectionData'])>0 && count($graphData) > 0){ ?>
    <h2 class="popular-title">Eligibility &amp; Exam score for <?php echo $courseNames['mainCourseId'];?> in <?php echo ucwords($countryObj->getName()); ?> </h2>
    <div class="clearfix"></div>
    <?php if(count($graphData) > 0){ ?>
        <div class="fee-expense-widget clear">
            <h3 class="avrgCost">Average exam score for <?php echo $courseNames['mainCourseId']; ?> in <?php echo ucwords($countryObj->getName()); ?>
                <div class="study-graph score-graph">
                    <?php
                        $class = "big-bar";
                        foreach($graphData as $examName => $examData){
                    ?>
                            <div class="<?php echo $class; ?>" style="<?php echo $examData['heightCSS'];?>">
                                <div class="bar-tooltip scoreWidth">
                                    <i class="study-sprite bar-arrw"></i>
                                    <?php echo $examData['avg']; ?>
                                </div>
                                <strong class="graphsubtitle-exam">
                                    <?php echo $examName; ?>
                                </strong>
                            </div>
                    <?php
                            $class = "small-bar";
                        }
                    ?>
                </div>
                <?php foreach($graphData as $examName => $examData){ ?>
                    <p style="font-weight: 500; font-size:11px;">
                        Avg <?php echo $examName; ?> score is based on <?php echo $examData['totalRecords']; ?> <?php echo $courseNames['mainCourseId']; ?> college<?php echo $examData['totalRecords']==1?"":"s"; ?> in <?php echo ucwords($countryObj->getName()); ?><br/>
                    </p>
                <?php } ?>
            </h3>
        </div>
    <?php } ?>
    <div class="countryPage-widget">
        <?php if(count($data['contentSectionData'])>0){ ?>
            <?php foreach($data['contentSectionData'] as $examData){ ?>
                <h3 class="widget-title">Most popular in <?php echo $examData[0]['exam_name']; ?> exam</h3>
                <ul class="poplrExam-list">
                    <?php foreach($examData as $examContentItem){ ?>
                        <li>
                            <a href="<?php echo $examContentItem['contentURL']; ?>"><strong><?php echo $examContentItem['strip_title']; ?></strong></a>
                            <span><?php if($examContentItem['commentCount'] > 0){ ?> <i class="study-sprite popComment-icn"></i><?php echo $examContentItem['commentCount']; ?> comment<?php echo $examContentItem['commentCount']!=1?"s":""; ?> <?php } ?> <?php if($examContentItem['commentCount'] > 0 && $examContentItem['viewCount'] > 0){ ?> | <?php } ?> <?php if($examContentItem['viewCount'] > 0){ ?> <?php echo $examContentItem['viewCount']; ?> view<?php echo $examContentItem['viewCount']!=1?"s":""; ?><?php } ?></span>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        <?php } ?>
    </div>
<?php } ?>
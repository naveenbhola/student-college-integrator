<?php $widgetData = $coursesData[$mainCourseId]['widgetData'];?>
    <h2 class="mostPopular-title">Eligibility &amp; Exam score for <?php echo $courseNames[$mainCourseId]; ?> in <?php echo ucwords($countryObj->getName()); ?></h2>
    <div style="padding:10px 25px;" class="popular-univ-content">
        <div class="fee-twoCol flLt" style="border-right: 1px solid #ccc;position: relative;left: 0px;">
            <?php
            $contentSectionData = $widgetData['countryHomeEligibilityExamScore']['contentSectionData'];
            $loopCounter = 0;
            foreach ($contentSectionData as $examMasterId => $singleExamContentData) {
                if (count($singleExamContentData) == 0) {
                    continue;
                }
                if ($loopCounter == 0) {

                    echo '<h3>Most popular in ' . $singleExamContentData[0]['exam_name'] . ' exam</h3>';
                } else {

                    echo '<strong>Most popular in ' . $singleExamContentData[0]['exam_name'] . ' exam</strong>';
                }
                echo '<ul>';
                foreach ($singleExamContentData as $examContentDetails) {
                    $numberOfCommentsOnArticle = $examContentDetails['commentCount'];
                    $numberOfViewOnArticle = $examContentDetails['viewCount'];
                    if ($numberOfCommentsOnArticle > 0) {
                        if ($numberOfCommentsOnArticle == 1) {
                            $commentString = "$numberOfCommentsOnArticle comment";
                        } else {
                            $commentString = "$numberOfCommentsOnArticle comments";
                        }
                    } else {
                        $commentString = "";
                    }
                    if ($numberOfViewOnArticle > 0) {
                        if ($numberOfViewOnArticle == 1) {
                            $viewString = "$numberOfViewOnArticle view";
                        } else {
                            $viewString = "$numberOfViewOnArticle views";
                        }
                    } else {
                        $viewString = "";
                    }
                    if ($viewString == "" || $commentString == "") {
                        $pipe = "";
                    } else {
                        $pipe = "|";
                    }
?>
                     <li>
                    <a href="<?php echo $examContentDetails['contentURL']; ?>" target="_blank"><i class="study-sprite blueStudy-arrw"></i>
                        <div class="tb-detail">
                          <?php  echo $examContentDetails['strip_title'];?><br>
                      
                        </div>
                    </a>
                            <div class="studyComment-sec" <?php if($viewString=="" && $commentString==""){echo " style='display:none'";}?>>
                                <i class="study-sprite comment-icon"></i>
                               <?php  echo $commentString . '  ' . $pipe . ' ' . $viewString ;?>
                            </div>
                </li>
                <?php
                }
                ?>
                
                </ul>
        <?php
                $loopCounter = 1;
            }
            ?>



            <?php $this->load->view("widgets/countryHomeRevamped/countryHomeFindCollegesByExam"); ?>
        </div>
        <?php  if (count($widgetData['countryHomeEligibilityExamScore']['graphSectionData'])>0){ ?>
        <div style="border-left: 1px solid #ccc;padding-left: 10px; right: 1px;position: relative;" class="fee-twoCol flRt">
            <h3>Average exam score for <?php echo htmlentities($courseNames[$mainCourseId]); ?> in <?php echo $countryObj->getName(); ?></h3>
            <div class="study-graph">
                <?php $barClass = "big-bar";
                    foreach($widgetData['countryHomeEligibilityExamScore']['graphSectionData'] as $exam=>$examData){ ?>
                <div class="<?php echo $barClass; ?>" style="<?php echo $examData['heightCSS']; ?>"><div class="bar-tooltip"><i class="study-sprite bar-arrw"></i><?php echo $examData['avg']; ?></div><strong class="graphsubtitle-exam"><?php echo $exam; ?></strong></div>
                <?php $barClass = "small-bar"; } ?>
            </div>
            <?php foreach($widgetData['countryHomeEligibilityExamScore']['graphSectionData'] as $exam=>$examData){ ?>
                <p style="font-size:10px;">Avg <?php echo $exam; ?> score is based on <?php echo $examData['totalRecords']; ?> <?php echo $courseNames[$mainCourseId]; ?> course<?php echo ($examData['totalRecords']==1?'':'s'); ?> in <?php echo $countryObj->getName(); ?></p>
            <?php } ?>
        </div>
        <?php } ?>
    </div>

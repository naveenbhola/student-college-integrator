<div class="new-row">
    <div class="group-card no__pad gap listingTuple" id="admissions">
            <?php 
                $subTitleText = "";
                if(count($admissions) > 0 && empty($importantDatesData['importantDates'])){
                    $subTitleText =  "Admission Process";
                }
                else if(empty($admissions) && !empty($importantDatesData['importantDates'])){
                    $subTitleText =  "Important Dates";
                }
                else{
                    $subTitleText =  "Admissions";
                }
                echo '<h2 class="head-1 gap">'.$subTitleText.'</h2>';
            ?>
        <?php if(count($admissions) > 1 && $pageName != 'Admission') {?>
        <div class="col-md-12 admsn-list">
            <div class="viewed-crsLst-admin">
                <?php 
                if(!isset($strlimit))
                    $strlimit = 121;
                foreach ($admissions as $stage => $value) {
                    if ($stage <= 3) {
                        ?>
                        <div class="r-card">
                            <div class="">
                                <strong><?= $value['admission_name'] ?></strong>
                                <p>
                                    <?php
                                    if (strlen($value['admission_description']) > $strlimit) {
                                        if($pageName == 'Admission')
                                        {
                                            $gaAttr = "ga-attr='VIEW_MORE_ADMISSION'";
                                        }
                                        echo trim(htmlentities(substr($value['admission_description'], 0, $strlimit)))."... <a href='javascript:void(0);' class='link' $gaAttr>Read More</a>";
                                    } else {
                                        echo nl2br(makeURLAsHyperlink(htmlentities($value['admission_description'])));
                                    } ?>
                                </p>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>
            <?php if(count($admissions) >= 3){ 
                if($pageName == 'Admission')
                {
                    $gaAttr = "ga-attr= 'VIEW_COMPLETE_ADMISSION'";
                }
                else
                {
                    $gaAttr = "ga-track='ADMISSION_VCP_TOP_COURSEDETAIL_DESKTOP'";
                }
                ?>
            <div class="dat-info stages">
                <a class="link" id="showAdmissionLayer" <?php echo $gaAttr;?>>View Complete Process</a>
            </div>
            <?php } ?>
        </div>
        <?php } 
        else if($pageName == 'Admission'){?>
            <div class="col-md-12 admsn-list">
                <div class="viewed-crsLst-admin">
                    <?php 
                    foreach ($admissions as $stage => $value) {
                            ?>
                            <div class="r-card">
                                <div class="">
                                    <strong><?= $value['admission_name'] ?></strong>
                                    <p>
                                        <?=nl2br(makeURLAsHyperlink(htmlentities($value['admission_description'])))?>
                                    </p>
                                </div>
                            </div>
                    <?php } ?>
                </div>
            </div>
        <?php }
        else {
            foreach ($admissions as $stage => $value) {?>
                <p class="para-2 dis-gap"><?=nl2br(makeURLAsHyperlink(htmlentities($value['admission_description'])))?></p>
            <?php }?>
        <?php } ?>

        <?php 
            if(count($importantDatesData['importantDates']) > 0){
                if(count($admissions) > 0 && $pageName == 'Admission'){
                ?>
                <div class="gap clearFix"></div>
                <?php } ?>
                <div class="mb10 clearFix">
                    <?php 
                        if(!empty($admissions)){
                            ?>
                            <strong class="crse-title">Important Dates</strong>
                            <?php
                        }
                    ?>

                    <?php                         
                        if(count($importantDatesData['examsHavingDates']) > 1 || (count($importantDatesData['examsHavingDates']) > 0 && $importantDatesData['isCourseDates'])){
                            ?>
                            <div class="gen-cat exam-cat">
                                <p>View Dates by</p>

                                <div class="dropdown-primary" id="importantDatesOptions">
                                    <span class="option-slctd">Select</span>
                                    <span class="icon"></span>
                                    <ul class="dropdown-nav" style="display: none;">
                                        <li><a value="">Select</a></li>
                                        <?php 
                                        foreach ($importantDatesData['examsHavingDates'] as $row) {
                                            ?><li><a value="<?php echo $row['exam_id']; ?>" ga-track="ADMISSION_EXAM_FILTER_COURSEDETAIL_DESKTOP"><?php echo $row['exam_name'] ?></a></li><?php
                                        }
                                        ?>                                        
                                    </ul>
                                </div>
                            </div>
                            <div class="clr"></div>
                            <?php
                        }
                    ?>
                </div>
                <div id="importantDatesContainer">
                    <div class="imp-dt-sec">
                        <ul>
                            <?php $this->load->view('nationalCourse/CoursePage/CourseImportantDates'); ?>
                        </ul>
                        <?php 
                            if(!empty($showImportantViewMore)){
                                if($pageName == 'Admission')
                                {
                                    $gaAttr = "ga-attr= 'VIEW_ALL_IMP_DATES'";
                                }
                                else
                                {
                                    $gaAttr = "ga-track='IMPDATES_VAD_TOP_COURSEDETAIL_DESKTOP'";
                                }
                                ?>
                                <a class="link" id="showImportantDatesLayer" <?=$gaAttr;?>>View All Dates</a>
                                <?php
                            }
                        ?>
                    </div>
                </div>
                <?php
            }
        ?>
        <?php 
            if($courseDates['type'] == 'onlineForm' && $pageName != 'Admission') { 
                $ctaName = 'Apply Now';
                $noFollow = !empty($courseDates['externalFlag']) ? '' : 'rel="nofollow"';
                $ctaLink = 'data-href="'. $courseDates['url']."?tracking_keyid=".DESKTOP_NL_COURSE_PAGE_ADMISSION_APPLY_OF .'" ga-track="APPLYNOW_COURSEDETAIL_DESKTOP" data-type="onlineForm" '.$noFollow;
                $ctaText = 'Applications open for this course';
            }
            /*else{
                $ctaName = 'Send Enquiry';
                $ctaLink = 'ga-track="ADMISSION_SEND_ENQUIRY_COURSEDETAIL_DESKTOP" data-type="viewDatesForm"';
                $ctaText = 'Send your query to the college';
            }*/
        
        if(!empty($ctaText) && !empty($ctaName)) { ?>
            <div class="findOut-sec">
                <div class='fntot-dv'>
                <h2 class="para-3"><?=$ctaText?> <a <?=$ctaLink?> class="btn-secondary mL10 CTA-aplyNow"><?=$ctaName?></a>
                </h2>
                </div>
            </div>
        <?php }
        ?>

    </div>
</div>

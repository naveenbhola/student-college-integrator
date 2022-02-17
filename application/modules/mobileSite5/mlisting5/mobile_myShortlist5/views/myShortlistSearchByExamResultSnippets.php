<?php

    foreach($courseObject as $courseDetails){
        $examArray     = $courseDetails->getEligibilityMappedExams();
        $courseId      = $courseDetails->getId();
        $instituteId   = $courseDetails->getInstituteId();
        $courseName    = $courseDetails->getName();
        $courseFeeObj  = $courseDetails->getFees($courseDetails->getMainLocation()->getLocationId());
        $courseFee = '';
        if($courseFeeObj){
            $courseFee     = moneyFormatIndia($courseFeeObj->getFeesValue());    
        }
        
        $examArrayTemp = array();
        foreach ($examArray as $key => $value) {
            $examArrayTemp[$value] = $value;
        }
        $examArray = $examArrayTemp;

        $instituteName = $courseDetails->getInstituteName();

        $exams = $courseDetails->getEligibility(array('general'));
        $exams = $exams['general'];
        $marksFormat = array(
                      'percentile' => "%ile",
                      'percentage' => "%",
                      'score/marks' =>  "marks",
                      'rank'        =>"rank"
                  );
        $examNameArray = array();
        if(!empty($examArray)){
            if(!empty($exams)){
                foreach($exams as $exam) {
                    if ($exam->getValue() > 0) {
                        $examNameArray[$exam->getExamName()] = ': '.$exam->getValue().' '.$marksFormat[$exam->getUnit()];
                        unset($examArray[$exam->getExamName()]);
                    } else {
                        $examNameArray[$exam->getExamName()] = '';
                    }
                }
            }
            if(count($examArray) > 0){
                foreach ($examArray as $exam) {
                    $examNameArray[$exam] = '';
                }
            }
        }else {
            $examNameArray = array('N/A' => '');
        }

        if(($courseShortListCounts[$courseId] && $courseShortListCounts[$courseId] > MS_MINIMUM_SHORTLIST_COUNT ))
        {
            $shortListCount     = $courseShortListCounts[$courseId];
            $actualCount        = $courseShortListCounts[$courseId];
        }
        else
        {
            $actualCount        = $courseShortListCounts[$courseId] ? $courseShortListCounts[$courseId] : 0;
            $shortListCount     = getCoursesShortlistCount($courseId) + $actualCount;
        }

        $param['userId']         = ($userStatus != 'false') ? $userStatus[0]['userid'] : 0;
        $param['courseId']       = $courseId;
        $param['scope']          = 'national';

        $courseShortlistedStatus = $shortlistListingLib->checkIfCourseAlreadyShortlisted($param);
        $courseShortlistedStatus = ($courseShortlistedStatus)?'true':'false'; 
        if($courseShortlistedStatus == 'true'){
            $shortlistedClass = 'shortlisted-star';
            $shortlistedText  = 'Shortlisted';
            $shortlistedInlineCSS = "style='left:28px;'";
        }else{
            $shortlistedClass = 'shortlist-star';
            $shortlistedText  = 'Shortlist';
            $shortlistedInlineCSS = "";
        }

         ?>
        <section class="listing-tupple" id="row_<?php echo $courseId?>" style="<?php echo ($isCourseDetailsTabsPage == 1 ? 'margin-bottom:0px;' : '')?>">
        <?php
            if($isCourseDetailsTabsPage == 1)
            {
				if(isset($shortlistedCoursesIds) && in_array($courseId, $shortlistedCoursesIds)){
        ?>
            <a href="javascript:void(0)"  course-option='' class="listing-menu"><span>&#8226;</span><span>&#8226;</span><span>&#8226;</span></a>
            <div class="courseInfoToPass" style="display:none;">
                    <courseId><?php echo $courseId;?></courseId>
                    <instituteId><?php echo $instituteId;?></instituteId>
                    <instituteName><?php echo base64_encode($instituteName);?></instituteName>
                    <tracking_keyid_DEB><?php echo $tracking_keyid;?></tracking_keyid_DEB>
            </div>
        <?php
				}
            }
            else
            {
        ?>
            <a class="mys-shortlst-btn shortlist-button-click">
                <i id="shortlistedStar<?php echo $courseId?>" class="sprite <?php echo $shortlistedClass?>" <?php echo $shortlistedInlineCSS?> ></i>
                <b><?php echo $shortlistedText?></b>
            </a>
             <div class="courseInfoToPass" style="display:none;">
                <courseId><?php echo $courseId;?></courseId>
             </div>
        <?php
            }
        ?>
            <aside class="listing-cont mrgnrght55">
                <strong><?php echo html_escape($instituteName)?> 
                <span><?php echo $courseDetails->getMainLocation()->getCityName()?></span></strong>
                <p class="course-name"><?php echo html_escape($courseName)?></p>
                <ul class="shortlist-details">
                    <li>
                        <label>Exams Accepted</label><b class="dot">:</b>
                        <?php
                            $examNameList = "";
                            foreach($examNameArray as $examName => $score) {
                                $examNameList = $examName.$score.", ".$examNameList;
                            }
                            $examNameList = rtrim($examNameList, ', ');
                        ?>
                        <p><?php echo html_escape($examNameList)?></p>
                    </li>
                    <li>
                        <label>Rank</label><b class="dot">:</b>
                        <p>
                            <?php
                                $index = 0;
                                if(isset($courseRank[$courseId])){
                                    foreach ($courseRank[$courseId] as $rankSource => $rank) {
                                        if($rank != ''){
                                            if($index != 0){
                                                echo ', ';
                                            }
                                            $index++;
                                            echo trim($rankSource).' : '.trim($rank);
                                        }
                                    }
                                    if($index == 0){
                                        echo 'N/A';
                                    }
                                }else{
                                    echo 'N/A';
                                }?>
                        </p>
                    </li>
                    <li>
                        <label>Total Fees</label><b class="dot">:</b>
                            <?php if(!empty($courseFee)) {?>
                            <p>Rs <?php echo $courseFee?></p>
                            <?php } else { ?>
                            <p>N/A</p>
                            <?php } ?>
                    </li>
                    <li>
                        <label>Average Salary</label><b class="dot">:</b>
                        <?php
                            if($courseDetails->getPlacements()!=''){
                                $avgSalary = $courseDetails->getPlacements()->getSalary();
                                $avgSalary = $avgSalary['avg'];
                            }
                            if($avgSalary < 0){
                                echo 'N/A';
                            }else{
                              echo 'Rs. '.$listingCommonLibObj->formatMoneyAmount($avgSalary, 1, 0);
                            }
                        ?>
                    </li>
                <?php
                    if(!$isCourseDetailsTabsPage)
                    {
                ?>
                    <li>
                        <label>Shortlisted By</label><b class="dot">:</b>
                        <p ac="<?php echo $actualCount;?>"><?php echo $shortListCount;?> Students</p>
                    </li>
                <?php
                    }
                ?>
                </ul>
                <?php 
                if(in_array($courseId,$downloadEBrochureApplied)){ ?>
                    <p class="ebroucherSucss" id="ebroucherSuccess_<?php echo $courseId?>" ><i class="msprite green-msg-icn"></i>E-Brochure has been successfully e-mailed</p>
                <?php } ?>

            </aside>
        </section>
<?php } ?>
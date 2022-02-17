<?php

$examStringCharLimit =20;
$instituteNameCharLimit = 45;
$courseNameCharLimit = 30;
$examArray  = $courseDetails->getEligibilityMappedExams();
$courseId   = $courseDetails->getId();
$instituteId = $courseDetails->getInstituteId();
$courseName = $courseDetails->getName();
$instituteName 	= $courseDetails->getInstituteName();

if(count($examArray) > 0){
    $examList = implode(", ", $examArray);
    $examList = $examList ? $examList : 'N/A';
}else{
    $examList = 'N/A';
}

$param['userId']         = ($validateuser != 'false') ? $validateuser[0]['userid'] : 0;
$param['courseId']       = $courseId;
$param['scope']          = 'national';

$courseShortlistedStatus = $shortlistListingLib->checkIfCourseAlreadyShortlisted($param);
$courseShortlistedStatus = ($courseShortlistedStatus)?$courseShortlistedStatus:'false'; 

if(($courseShortListCounts[$courseId] && $courseShortListCounts[$courseId] > 100 ))
{
    $shortListCount 	= $courseShortListCounts[$courseId];
    $actualCount 	= $courseShortListCounts[$courseId];
}
else
{
    $actualCount 	= $courseShortListCounts[$courseId] ? $courseShortListCounts[$courseId] : 0;
    $shortListCount 	= getCoursesShortlistCount($courseId) + $actualCount;
}

?>
<li>
<div class="slide-main-wrap">
    <div class="slide-prev"><i class="msprite prv-icn"></i></div>
    <div class="slider-wrap2">
        <div>
        <div class="slider-inner">
            <strong><?php echo (strlen($instituteName) > $instituteNameCharLimit) ? substr($instituteName, 0, $instituteNameCharLimit)."..." : $instituteName ?>, <span><?php echo is_object($courseDetails->getMainLocation())?$courseDetails->getMainLocation()->getCityName():'';
            ?></span></strong>
            <p><?php echo (strlen($courseName) > $courseNameCharLimit) ? substr($courseName, 0, $courseNameCharLimit)."..." : $courseName ?></p>
        </div>

        <ol class="slider-details">
            <li>
                <label>Exams</label>
                <p><?php echo (strlen($examList) > $examStringCharLimit) ? substr($examList, 0, $examStringCharLimit)."..." : $examList ?></p>
            </li>
            <li>
                <label>Average Salary</label>
            <p>  
            <?php
                    if($courseDetails->getPlacements()){
                        $avgSalary = $courseDetails->getPlacements()->getSalary();
                        $avgSalary = $avgSalary['avg'];
                        if($avgSalary != '' && $avgSalary > 0){ ?>
                            Rs. <?php echo $avgSalary;?>
                        <?php }else{
                            echo 'N/A';
                        }
                    ?>
                        
                <?php    }else{ ?>
                        N/A
                <?php    } ?>
            </p>
            </li>
            <li>
                <label>Total Fees</label>
                <p><?php
                    if($courseDetails->getTotalFees()){
                        $feeObj = $courseDetails->getTotalFees();
                        echo 'Rs. '.$feeObj->getFeesValue();
                    }else{
                        echo 'N/A';
                    }?>
            </li>
        </ol>
        <p class="content-inner shortlisted-figures">Shortlisted by <span ac="<?php echo $actualCount;?>"><?php echo $shortListCount;?></span> students</p>
        </div>
        <div class="btn-row tac">
            <?php
            $shortlistedText      = "Shortlist";
            $tracking_keyid       = isset($recommendedCourseWidgetKey) ? $recommendedCourseWidgetKey : '';
            $shortlistClass       = '';
            if ($courseShortlistedStatus == 1) {
                $shortlistedText      = 'Shortlisted';
                $shortlistClass       = "disabled";
            } ?>
            <a href="javascript:void(0);" onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE_MOBILE', 'shortlist', 'recommendation'); var customParam = {'shortlistCallback':'recoShortlistCallback', 'shortlistCallbackParam':{'thisObj':this}, 'trackingKeyId':'<?php echo $tracking_keyid;?>', 'pageType':'NM_MyShortlist'}; myShortlistObj.checkCourseForShortlist('<?php echo $courseId; ?>', customParam);" class="btn btn-default btn-med <?php echo $shortlistClass;?>"><i class="msprite blue-shortlist-star"></i><span><?php echo $shortlistedText;?></span></a><br>
            <p style="margin-top:12px; font-size:12px">
                <a class="showSimilarColleges" href="javascript:void(0);" college-name="<?php echo base64_encode($instituteName) ?>" course-id="<?php echo $courseId;?>">Show Similar Colleges</a>
            </p>
        </div>
    </div>
    <div class="slide-next"><i class="msprite nxt-icn"></i></div>
</div>
</li>

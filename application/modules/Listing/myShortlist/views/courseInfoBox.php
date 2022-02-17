<?php

$examStringCharLimit =30;
$instituteNameCharLimit = 45;
$courseNameCharLimit = 40;
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

if(($courseShortListCounts[$courseId] && $courseShortListCounts[$courseId] > MS_MINIMUM_SHORTLIST_COUNT ))
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

<div class="college-info-col flLt <?php echo $lastClass?>">

    <div class="college-info-wrap clearfix">
         <div class="college-info">
            <strong title="<?php echo html_escape($instituteName);?>"><?php echo (strlen($instituteName) > $instituteNameCharLimit) ? substr($instituteName, 0, $instituteNameCharLimit)."..." : $instituteName ?></strong>
            <span class="college-loc"><?php

             echo is_object($courseDetails->getMainLocation())?$courseDetails->getMainLocation()->getCityName():'';?></span>
         </div>
         <div class="course-info">
	    
            <strong title="<?php echo html_escape($courseName);?>"><?php echo (strlen($courseName) > $courseNameCharLimit) ? substr($courseName, 0, $courseNameCharLimit)."..." : $courseName ?></strong>
            <div class="info-row exam-info">
            	<label>Exams Accepted: </label>
		        <p title="<?php echo html_escape($examList);?>"><?php echo (strlen($examList) > $examStringCharLimit) ? substr($examList, 0, $examStringCharLimit)."..." : $examList ?></p>
            </div>
            <div class="info-row">
                <label style="width:88px">Average Salary: </label>
                <?php
                    if($courseDetails->getPlacements()!=''){
                        $avgSalary = $courseDetails->getPlacements()->getSalary();
                        $avgSalary = $avgSalary['avg'];
                    }
                        if(!empty($avgSalary)){
                    ?>
                        <p style="margin-left:82px">Rs. <?php echo $avgSalary;?></p>
                        <p style="font-size:11px; color:#a4a4a4; font-weight:normal; margin:0">&nbsp;</p>
                <?php    }else{ ?>
                        <p style="margin-left:82px">N/A</p><p style="font-size:11px; color:#a4a4a4; font-weight:normal; margin:0">&nbsp;</p>
                <?php    } ?>
            </div>
	    
            <div class="clearFix"></div>
            <p class="shorlist-detail">Shortlisted by <span style="color:#666;" ac="<?php echo $actualCount;?>"><span class="shortlist-count<?php echo $courseId?>"><?php echo $shortListCount;?></span> students</span></p>
         </div>
    </div>
     
    <div class="btn-col">
	<a href="javascript:void(0);" tabindex="-1" id="exam_search_<?php echo $courseId?>" onclick="var customParam = {'shortlistCallback':'shortlistCallbackReco', 'shortlistCallbackParam':{'courseId':'<?php echo $courseId;?>'}, 'trackingKeyId':'<?php echo DESKTOP_NL_SHORTLIST_HOME_RECO_BOTTOM_SHORTLIST?>', 'pageType':'ND_MyShortlist'}; myShortlistObj.checkCourseForShortlist('<?php echo $courseId;?>', customParam);" class="add-shortlist-btn <?php echo ($courseShortlistedStatus == 1 ? 'shortlist-disable' : '')?> exam_search_<?php echo $courseId?>"><i class="shortlist-sprite shrotlist-star"></i> <span class="btn-label"><?php echo ($courseShortlistedStatus == 1 ? 'Shortlisted' : 'Shortlist')?></span></a>
        <a href="javascript:void(0);"  tabindex="-1" onclick="getSimilarCourses(<?php echo $courseId;?>, '<?php echo ($isRecommendationsFlag ? 'recommendation' : 'find_college')?>','<?php echo base64_encode($instituteName) ?>');" class="tac"><strong>Show Similar Colleges</strong></a>  
    </div>

 </div>
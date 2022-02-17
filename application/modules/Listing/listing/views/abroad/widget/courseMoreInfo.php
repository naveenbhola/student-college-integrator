<?php
function getCompleteLink($link)
{
    if(!(0===strpos($link,'http')) && $link )
            $link = "http://".$link;
    return $link;
}

$moreinfotab['facultyInfoLink']     = getCompleteLink($moreinfotab['facultyInfoLink']);
$moreinfotab['coursefaqLink']       = getCompleteLink($moreinfotab['coursefaqLink']);
$moreinfotab['alumniInfoLink']      = getCompleteLink($moreinfotab['alumniInfoLink']);
$moreinfotab['careerServicesLink']  = getCompleteLink($moreinfotab['careerServicesLink']);
 ?>



<div class="consultant-box clearwidth">
            <div style="position:relative" class="consultant-que-box clearwidth">
                <i class="listing-sprite more-info-icon-2"></i><strong class="stu-scholarship-head">More information about this <br>
course on university website</strong>
                <i style="left:17px;" class="listing-sprite consultant-pointer"></i>
            </div>
            <div class="consultant-contact-sec">
                <ul class="level-list">
                     <?php if($moreinfotab['alumniInfoLink']!="") {?>
                    <li>
                        <a href="<?=$moreinfotab['alumniInfoLink']?>" target="_blank" rel="nofollow" onclick="studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'outgoingLink');">Alumni Information on university website</a>
                    </li>
                    <?php } ?>
                    <?php if($moreinfotab['coursefaqLink']!="") { ?>
                    <li>
                        <a href="<?=$moreinfotab['coursefaqLink']?>" target="_blank" rel="nofollow" onclick="studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'outgoingLink');">Course FAQs on university website</a>
                    </li>
                    <?php } ?>
                    <?php if($moreinfotab['facultyInfoLink']!=""){ ?>
                    <li>
                        <a href="<?=$moreinfotab['facultyInfoLink']?>" target="_blank" rel="nofollow" onclick="studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'outgoingLink');">Faculty Information on university website</a>
                    </li>
                    <?php } ?>
                    <?php  if($moreinfotab['careerServicesLink']!="")
                           {
                                $careerLink = '';
                                if(0===strpos($courseObj->getJobProfile()->getCareerServicesLink(),'http'))
                                {
                                    $careerLink = $courseObj->getJobProfile()->getCareerServicesLink();
                                }
                                else
                                {
                                    $careerLink = 'http://'.$courseObj->getJobProfile()->getCareerServicesLink();
                                }?>
                                <li><a href="<?php echo $careerLink; ?>" target="_blank" rel="nofollow" onclick="studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'outgoingLink');">Placement Services on university website</a></li>
                    <?php   } ?>
                </ul>
            </div>
        </div>
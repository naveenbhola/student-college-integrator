<?php
if($courseObj->showRmcButton())
{
            $brochureDataObj['pageTitle']   	= htmlentities($courseObj->getName());
            $brochureDataObj['widget']      	= $widget;
            $brochureDataObj['userRmcCourses'] = $userRmcCourses;
            $brochureDataObj['trackingPageKeyId']= $trackingPageKeyId;

?>
            <div class="course-rt-tab course-rate-chng clearfix" style="width:100%; float:left">
                <strong style="margin-bottom:8px; display:block;">Interested in this course?</strong>
            <!--    <a href="javascript:void(0)" class="rate-change-button"><i class="common-sprite rate-changes-icon"></i>Rate my chances</a>-->
                    <?php echo $rateMyChanceCtlr->loadRateMyChanceButton($brochureDataObj); ?>
            </div>
<?php   }?>
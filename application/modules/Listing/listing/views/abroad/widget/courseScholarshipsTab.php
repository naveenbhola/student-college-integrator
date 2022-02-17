<?php
$fromCurrencyUnit = $courseObj->getScholarshipCurrencyCode();  
?>
<div class="course-detail-tab scholarships-tab overview-details clearfix">
    <div class="course-detail-mid flLt">
        <h2 style="margin:0 0 10px 0;" class="course-require-hd">Student scholarships for this course</h2>
        <div id="scholarshipstab" style="margin-top:0px;" class="clearwidth cons-scrollbar1 scrollbar1 soft-scroller">
            <div class="cons-scrollbar scrollbar" style="visibility:hidden; left:8px;">
                <div class="track">
                    <div class="thumb"></div>
                </div>
            </div>
            <div style="height:300px" class="viewport">
                <div class="overview dyanamic-content entry-req-list">
                <?php if($courseObj->getScholarshipDescription()!=''){?>    
                    <p class="course-require-hd">Scholarship description</p>
                    <p><?php echo htmlentities($courseObj->getScholarshipDescription());?></p>
                <?php } ?>
                
                <?php if($courseObj->getScholarshipEligibility()!=''){?>    
                    <br/><p class="course-require-hd">Scholarship eligibility</p>
                    <p><?php echo htmlentities($courseObj->getScholarshipEligibility());?></p>
                <?php } ?>
                
                <?php if($courseObj->getScholarshipAmount()!=0){?>
                    <br/><p>Scholarship amount: <strong><?php if($fromCurrencyUnit !='INR') {echo $fromCurrencyUnit." ".$courseObj->getScholarshipAmount();?> => <?php } echo $scholarshipAmountDetail;?> /-</strong></p>
                <?php } ?>
                
                <?php if($courseObj->getScholarshipDeadLine()!=''){?>
                    <br/><p>Scholarship deadline: <strong><?php echo htmlentities($courseObj->getScholarshipDeadLine());?></strong></p>
                <?php } ?>
                
                <?php foreach($courseObj->getCustomScholarship() as $key=>$val){?>
                    <br/><p class="course-require-hd"><?php echo htmlentities($val['caption'])?></p>
                    <p><?php echo htmlentities($val['value'])?></p>
                <?php }?>
                    
                    <?php if($scholarshiplLink !='' || $courseLevelLink!='' ||  $deptLevelLink!='' || $univLevelLink!=''){?>
                    <br/><p>More details on the following links:</p>
                    <ul class="level-list">
                    <?php if($scholarshiplLink!='') {?><li><a target="_blank" rel="nofollow" onclick="studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'outgoingLink');" href="<?php echo $scholarshiplLink; ?>"><?php echo htmlentities(formatArticleTitle($scholarshiplLink,70)); ?></a></li>  <?php } ?>    
                	<?php if($courseLevelLink!='') {?><li><a target="_blank" rel="nofollow" onclick="studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'outgoingLink');" href="<?php echo $courseLevelLink; ?>"><?php echo htmlentities(formatArticleTitle($courseLevelLink,70)); ?></a></li>  <?php } ?>
					<?php if($deptLevelLink!='')   {?><li><a target="_blank" rel="nofollow" onclick="studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'outgoingLink');" href="<?php echo $deptLevelLink;   ?>"><?php echo htmlentities(formatArticleTitle($deptLevelLink,70));   ?></a></li>  <?php } ?>
                    <?php if($univLevelLink!='')   {?><li><a target="_blank" rel="nofollow" onclick="studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'outgoingLink');" href="<?php echo $univLevelLink;   ?>"><?php echo htmlentities(formatArticleTitle($univLevelLink,70));   ?></a></li>  <?php } ?>
                    </ul>
                    <?php } ?>

                </div>
             </div>
        </div>
    </div>
    <!--right side grey tab data-->
    <div class="course-detail-rt flRt clearfix">
        <!--This rate my chance button-->
        <?php   $param['widget'] = 'scholarshipsTab';
                $param['trackingPageKeyId'] = 639;
                $this->load->view('listing/abroad/widget/rateMyChanceWidget',$param);
        ?>
    </div>
    <!--Ends here right grey tab data-->
</div>


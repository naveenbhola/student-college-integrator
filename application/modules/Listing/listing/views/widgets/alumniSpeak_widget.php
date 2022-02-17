<?php if(!empty($alumniReviews)){?>
<div class="wdh100">
<?php
$params = array('instituteId'=>$details['institute_id'],'instituteName'=>$details['title'],'type'=>$identifier,'locality'=>$details['location']['0']['locality'],'city'=>$details['location']['0']['city_name'],'courseName'=>$details['courseDetails']['0']['title'],'courseId'=>$details['courseDetails']['0']['course_id']);
$alumniTabUrl = listing_detail_alumni_speak_url($params);
?>


<?php
$countPlacement =0;
$placement = 0;
$countFaculty =0;
$faculty = 0;
$countInfrastructure =0;
$infrastructure = 0;
$countOverall =0;
$overall = 0;
foreach($alumniReviews as $alumniReview ){
if($alumniReview['status']== 'published'){
if($alumniReview['criteria_id'] == 1){
    $countInfrastructure += $alumniReview['criteria_rating'];
    if($alumniReview['criteria_rating']!=0)
    $infrastructure ++;
}
if($alumniReview['criteria_id'] == 2){
    $countFaculty += $alumniReview['criteria_rating'];
    if($alumniReview['criteria_rating']!=0)
    $faculty++;
}
if($alumniReview['criteria_id'] == 3){
    $countPlacement += $alumniReview['criteria_rating'];
    if($alumniReview['criteria_rating']!=0)
    $placement++;
}
if($alumniReview['criteria_id'] == 4){
    $countOverall += $alumniReview['criteria_rating'];
    if($alumniReview['criteria_rating']!=0)
    $overall++;
}
}
}
if($infrastructure!=0)
$countInfrastructure = round($countInfrastructure/$infrastructure);
if($placement!=0)
$countPlacement = round($countPlacement/$placement);
if($faculty!=0)
$countFaculty = round($countFaculty/$faculty);
if($overall!=0)
$countOverall = round($countOverall/$overall);

$trackEvent = '';
if(isset($tab)){
	if($tab=='overview')
		$trackEvent = 'LISTING_OVERVIEW_ALUMNI_LINK';
	else if($tab=='ana')
		$trackEvent = 'LISTING_QNATAB_ALUMNI_LINK';
}

?>
                                    <?php if(($countInfrastructure!=0)||($countPlacement!=0)||($countFaculty!=0)||($countOverall!=0)){?>
                                    <div class="nlt_head Fnt14 bld mb10">Alumni Speak</div>
                                    <div class="">
                                        <?php if($countPlacement!=0){?>
                                        <div class="h14 mb5">
                                            <span class="float_L"><a onClick="trackEventByGA('LinkClick','<?php echo $trackEvent;?>');" href="<?php echo $alumniTabUrl?>/#placements" class="Fnt11">Placements</a></span>

                                            <span class="float_R Fnt11"><?php for($i=0;$i<$countPlacement;$i++){?>
                                                <img src="/public/images/nlt_str_full.gif" border="0" align="absbottom" />
                                            <?php  } ?>
                                                <?php for($i=5;$i>$countPlacement;$i--){?>
                                                <img src="/public/images/nlt_str_blk.gif" border="0" align="absbottom" />
                                                <?php }?>
                                                 <?php echo $countPlacement; ?>/5
                                            </span>
                                        </div>
                                        <?php }?>
                                        <?php if($countInfrastructure!=0){?>
                                        <div class="h14 mb5">
                                            <span class="float_L"><a onClick="trackEventByGA('LinkClick','<?php echo $trackEvent;?>');" href="<?php echo $alumniTabUrl?>/#infrastructure" class="Fnt11">Infrastructure/Teaching Facilities</a></span>
                                            <span class="float_R Fnt11"><?php for($i=0;$i<$countInfrastructure;$i++){?>
                                                <img src="/public/images/nlt_str_full.gif" border="0" align="absbottom" />
                                            <?php  } ?>
                                                <?php for($i=5;$i>$countInfrastructure;$i--){?>
                                                <img src="/public/images/nlt_str_blk.gif" border="0" align="absbottom" />
                                                <?php }?>
                                                 <?php echo $countInfrastructure; ?>/5
                                            </span>
                                        </div>
                                        <?php }?>
                                        <?php if($countFaculty!=0){?>
                                        <div class="h14 mb5">
                                            <span class="float_L"><a onClick="trackEventByGA('LinkClick','<?php echo $trackEvent;?>');" href="<?php echo $alumniTabUrl?>/#faculty" class="Fnt11">Faculty</a></span>
                                            <span class="float_R Fnt11"><?php for($i=0;$i<$countFaculty;$i++){?>
                                                <img src="/public/images/nlt_str_full.gif" border="0" align="absbottom" />
                                            <?php  } ?>
                                                <?php for($i=5;$i>$countFaculty;$i--){?>
                                                <img src="/public/images/nlt_str_blk.gif" border="0" align="absbottom" />
                                                <?php }?>
                                                 <?php echo $countFaculty; ?>/5
                                            </span>
                                        </div>
                                        <?php }?>
                                        <?php if($countOverall!=0){?>
                                        <div class="h14 mb5">
                                            <span class="float_L"><a onClick="trackEventByGA('LinkClick','<?php echo $trackEvent;?>');" href="<?php echo $alumniTabUrl?>/#overall" class="Fnt11">Overall Feedback</a></span>
                                            <span class="float_R Fnt11"><?php for($i=0;$i<$countOverall;$i++){?>
                                                <img src="/public/images/nlt_str_full.gif" border="0" align="absbottom" />
                                            <?php  } ?>
                                                <?php for($i=5;$i>$countOverall;$i--){?>
                                                <img src="/public/images/nlt_str_blk.gif" border="0" align="absbottom" />
                                                <?php }?>
                                                 <?php echo $countOverall; ?>/5
                                            </span>
                                        </div>
                                        <?php }?>
                                    </div>
                                    
<?php }?>
</div>
<div class="lineSpace_20">&nbsp;</div>
                                                 <?php }?>

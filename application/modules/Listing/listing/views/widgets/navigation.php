<?php

$params = array('instituteId'=>$details['institute_id'],'instituteName'=>$details['title'],'type'=>'institute','locality'=>$details['location']['0']['locality'],'city'=>$details['location']['0']['city_name'],'courseName'=>$details['courseDetails']['0']['title'],'courseId'=>$details['courseDetails']['0']['course_id'],'abbrevation'=>$details['abbreviation']);
if($instituteType==1){
$overviewTabUrl = getSeoUrl($details['institute_id'], 'institute', $details['title'], array('location' => array($details['locations']['0']['locality'], $details['locations']['0']['city_name'],$details['locations']['0']['country_name'])));
}
if($instituteType==2){
$overviewTabUrl = getSeoUrl($details['institute_id'], 'institute', $details['title'], array('location' => array($details['locations']['0']['locality'], $details['locations']['0']['city_name'])));
}
$askNAnswerTabUrl = listing_detail_ask_answer_url($params);
$mediaTabUrl = listing_detail_media_url($params);
$alumniTabUrl = listing_detail_alumni_speak_url($params);
$courseTabUrl = listing_detail_course_url($params);

?>
<?php
//Check For the unpublished Reviews
foreach($alumniReviews as $alumniReview){
    if($alumniReview['status']== 'published'){
        $alumniFlag =1;
        break;
    }
}

?>

<div class="mlr10">
        	<div id="sNLtn">
            	<ul class="snLtUL">
                    <li <?php if($tab=='overview'){?> class="nlactive" <?php } ?>><a onClick="trackEventByGA('TabClick','LISTING_OVERVIEW_TAB');" href="<?php echo $overviewTabUrl;?>" class="bld" <?php if($tab=='overview'){?>style="font-size:15px;"<?php } ?>>Overview</a></li>
                    <li <?php if($tab=='ana'){?>class="nlactive"<?php } ?>><a onClick="trackEventByGA('TabClick','LISTING_QNA_TAB');" href="<?php echo $askNAnswerTabUrl;?>" class="bld" <?php if($tab=='ana'){?>style="font-size:15px;"<?php } ?>>Q&nbsp;&amp;&nbsp;A</a></li>
                    <?php if ( $createMediaTab == 1){?>
                    <li <?php if($tab=='media'){?>class="nlactive"<?php } ?>><a onClick="trackEventByGA('TabClick','LISTING_MEDIA_TAB');" href="<?php echo $mediaTabUrl;?>" class="bld" <?php if($tab=='media'){?>style="font-size:15px;"<?php } ?>>Photos&nbsp;&amp;&nbsp;Videos</a></li>
                    <?php }?>

                    <?php if($alumniFlag == 1){?>
                    <li <?php if($tab=='alumni'){?>class="nlactive"<?php }?>><a onClick="trackEventByGA('TabClick','LISTING_ALUMNI_TAB');" href="<?php echo $alumniTabUrl;?>" class="bld" <?php if($tab=='alumni'){?>style="font-size:15px;"<?php } ?>>Alumni&nbsp;Speak</a></li>
                    <?php }?>

                    <?php if(count($courseList)>2){?>
                    <li <?php if($tab=='course'){?>class="nlactive"<?php }?>><a onClick="trackEventByGA('TabClick','LISTING_COURSE_TAB');" href="<?php echo $courseTabUrl;?>" class="bld" <?php if($tab=='course'){?>style="font-size:15px;"<?php } ?>>Courses</a></li>
                    <?php }?>

                    <?php if($details['courseDetails'][0]['displayOnlineFormButton']==='true'){
			    $urlToRedirectWhenFormExpired    = '/studentFormsDashBoard/MyForms/Index/';
			?>
			<?php $inst_id = $details['institute_id'];if(array_key_exists('seo_url', $online_form_institute_seo_url[$inst_id])) {$seo_url = SHIKSHA_HOME.$online_form_institute_seo_url[$inst_id]['seo_url'];} else {$seo_url = "/Online/OnlineForms/showOnlineForms/".$details['courseDetails'][0]['course_id'];}?>
		     <li class="onlineTabButton"><a onClick="trackEventByGA('TabClick','LISTING_ONLINE_FORM_TAB'); setCookie('onlineCourseId','<?php echo $details['courseDetails'][0]['course_id'];?>',0); checkOnlineFormExpiredStatus('<?php echo $details['courseDetails'][0]['course_id'];?>','<?php echo $urlToRedirectWhenFormExpired;?>','<?php echo $seo_url?>'); return false;" href="javascript:void(0);" class="onlineAppFormButton2">Online Application Form</a></li>
                    <?php }?>
                </ul>
            </div>
        </div>

<div class="collapsed-widget" <?php 
if( ! empty($OF_DETAILS['of_seo_url']))
	$OF_DETAILS['of_seo_url'] = $OF_DETAILS['of_seo_url'].'?tracking_keyid='.$applyRightTrackingPageKeyId;
if(!empty($OF_DETAILS['of_external_url'])):?>

onclick="window.location='<?=$OF_DETAILS['of_seo_url']?>'"
<?php else:?>
onClick="setCookie('onlineCourseId','<?php echo $OF_DETAILS['of_course_id'];?>',0); checkOnlineFormExpiredStatus('<?php echo $OF_DETAILS['of_course_id'];?>','<?php echo '/studentFormsDashBoard/MyForms/Index/'; ?>','<?php echo $OF_DETAILS['of_seo_url'];?>'); return false;"
<?php endif;?>
>
<h5>
	<i class="sprite-bg app-icon flLt"></i>
	<span class="register-heading" style="margin-top:10px">Apply Online</span>
	<div class="pointer sprite-bg"></div>
</h5>
</div>

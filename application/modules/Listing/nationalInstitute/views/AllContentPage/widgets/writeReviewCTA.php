<?php 
	$GA_Tap_On_Write_Review  = 'WRITE_REVIEW';
	$listingText = 'college';
	if($listing_type == 'university')
		$listingText = 'university';
?>
<div class="group-card gap align-c" style="width:396px;">
    <h2 class="almuni-h3">Are you a student or an alumni of this <?php echo $listingText;?>?</h2>
    <a href="<?php echo SHIKSHA_HOME;?>/college-review-form" class="btn-primary top-b" ga-attr="<?=$GA_Tap_On_Write_Review;?>" target="_blank">Write a review</a>
</div>

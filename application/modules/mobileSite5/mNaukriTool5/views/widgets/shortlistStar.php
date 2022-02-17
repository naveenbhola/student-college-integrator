<?php 
	$class = 'sprite shortlist-star';
	$Shortlist = 'Shortlist';
?>
<div class="side-col" id="shortlistDiv<?php echo $courseId;?>" onclick="listingShortlist('<?php echo $listing_id?>','<?php echo $tracking_keyid;?>','<?php echo $listing_type;?>', {'pageType':'NM_CareerCompass','listing_type':'<?php echo $listing_type;?>','callbackFunctionParams':{'pageType':'NM_CareerCompass'}});event.preventDefault(); event.stopPropagation();">
    <span class="<?php echo $class;?> <?php echo 'allChkShortlisted'.$courseId;?>" id="shortlistedStar<?php echo $courseId;?>"></span>
    <span id="shortlistedText<?php echo $courseId;?>" class="<?php echo 'allChkShortlistedText'.$courseId;?>"><?php echo $Shortlist;?></span>
</div>

<input name="isShikshaInst" id="isShikshaInst" value="<?php if(isset($isShikshaInstitute) && $isShikshaInstitute!=''){ echo $isShikshaInstitute;}else{ echo 'NO';}?>" type="hidden" />
<input value="<?php echo $reviewId;?>" type="hidden" name="reviewId"/>
<input id="qualification_str" value="1" type="hidden" />
<input id="formName" name="formName" value="<?php if(isset($reviewFormName) && $reviewFormName!=''){ echo $reviewFormName;}else{ echo "reviewForm"; } ?>" type="hidden"/>
<input id="userId" value="<?php echo $userId;?>" type="hidden" name="userId"/>
<input id="landingPageUrl" value="<?php echo $landingPageUrl;?>" type="hidden"/>
<input id="reviewerId" value="<?php echo $reviewerId;?>" type="hidden"  name="reviewerId"/>
<?php if(isset($reviewFormName) && $reviewFormName=='collegeReviewRating'){ ?>
<input id="reviewSource" value="<?php echo isset($_SERVER['QUERY_STRING'])?htmlentities(strip_tags($_SERVER['QUERY_STRING'])):'';?>" type="hidden"  name="reviewSource"/>
<input id="showPersonalInfoSection" name="showPersonalInfoSection" value="<?php echo $showPersonalSection;?>" type="hidden" />
<?php } ?>

<?php if($pageType  == 'campusRep'){ ?>
<input name="parentReferralId" id="parentReferralId" value="<?=$parentReferralId?>" type="hidden" />
<input name="parentEmailId" id="parentEmailId" value="<?=$parentEmailId?>" type="hidden" />
<input name="pageType" id="pageType" value="<?=$pageType?>" type="hidden" />
<?php } ?>

<?php if($pageType  == ''){ ?>
	<input type="hidden" name="tracking_keyid" id="tracking_keyid" value="<?php echo $trackingPageKeyId;?>">
<?php }?>
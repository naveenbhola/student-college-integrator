<?php
if($getInfo[0]->imageURL !=''){
	$imageUrl = $getInfo[0]->imageURL;
}else{
	$imageUrl = SHIKSHA_HOME.'/public/images/campusAmbassador/default-img.png';
}
?>
<div class="profile-box">
<div class="profile-box-child">
    <div class="profile-pic"><img src="<?php echo $imageUrl;?>" width="32" height="32"></div>
    <div class="profile-details">
	<p><?php echo ucwords($getInfo[0]->displayName);?></p>
	<p><?php echo $instituteName;?></p>
	<a style="color:#a6cce1" href="javascript:void(0);" onclick="viewProfileMore()">VIEW MORE <i class="campus-sprite down-arr" id="icon-view-more"></i></a>
    </div>
</div>
<ol class="profile-list clearfix" id="view_more_profile" style="display: none;">
    <li><i class="campus-sprite diploma-icn"></i><span><?php echo $courseName;?></span></li>
    <?php if(isset($totalTime) && $totalTime !=''){?>
    <li><i class="campus-sprite avg-ans-icn"></i><span>Average Answer Time <br><?php echo ucwords($totalTime);?></span></li>
    <?php }?>
    <li><i class="campus-sprite useful-icn"></i><span>Your answers have been voted useful <?php echo $getTotalDig[0]->totalDig;?> times</span></li>
</ol>
</div>
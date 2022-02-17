<div class="slider-box">
   <h3>Tags to follow</h3>
   <div class="tagRecommentations ana-slider">
   <ul>
   <?php
   	foreach ($data['tagsRecommendations'] as $value) {
	$tagURL = getSeoUrl($value['tagId'], 'tag', $value['tagName']);
   ?>
	<li class="mt-card-item">
	     <p class="based-txt"><?php echo strlen($value['heading'])>45 ? substr($value['heading'], 0, 45)."..." : $value['heading'];?></p>
	     <a onclick="sendGAWebAnA('HOMEPAGE_WEBAnA','TAG_RECO_HOMEPAGE_WEBAnA',this,'<?=$tagURL?>')" class="mt-txt reco-tile-name" href="<?=$tagURL?>"><?php echo strlen($value['tagName'])>35 ? substr(strtoupper($value['tagName']), 0, 35)."..." : strtoupper($value['tagName']);?></a>
	     <p class="mt-span-txt followersCountTextArea">
	     <?php
	     	if($value['tagFollowers'] > 0){ ?>
	     <i class="flw-ico"></i><?php echo $value['tagFollowers']." ".($value['tagFollowers'] > 1 ? 'FOLLOWERS' : 'FOLLOWER');?>
	     <?php } else {
	     	echo "&nbsp;";
	     	}?>
	     </p>
	     <a href="javascript:void(0)" onclick="gaTrackingForAna(this,'HOMEPAGE_WEBAnA','FOLLOW_TAG_RECO_HOMEPAGE_WEBAnA','<?=$GA_userLevel;?>');followEntity(this,<?php echo $value['tagId'];?>,'tag',false,'<?php echo $tfollowTrackingPageKeyId;?>')" class="mt-flw-btn" reverseclass="unflw-btn" callforaction="follow">FOLLOW</a>
    </li>
   <?php
   	}
   ?>
   </ul>
	</div>
</div>

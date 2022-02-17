<div class="slider-box">
   <h2 class="rl-txt">RELATED TAGS</h2>
   <div class="relatedTags ana-slider">
   <ul>
   <?php
   	foreach ($data['relatedTags'] as $value) {
	$tagURL = getSeoUrl($value['tagId'], 'tag', $value['tagName']);
   ?>
	<li class="mt-card-item" style="min-height:0">
	     <!-- <p class="based-txt"> --><?php //echo strlen($value['heading'])>45 ? substr($value['heading'], 0, 45)."..." : $value['heading'];?><!-- </p> -->
	     <a class="mt-txt reco-tile-name"  onclick="gaTrackEventCustom('TAG DETAIL PAGE','RELATEDTAG_TAGDETAIL_WEBAnA','<?=$data['tagName'];?>',this,'<?=$tagURL?>');" 	href="<?=$tagURL?>"><?php echo strlen($value['tagName'])>35 ? substr(strtoupper($value['tagName']), 0, 35)."..." : strtoupper($value['tagName']);?></a>
	     <p class="mt-span-txt followersCountTextArea">
	     <?php
	     	if($value['tagFollowers'] > 0){ ?>
	     <i class="flw-ico"></i><?php echo $value['tagFollowers']." ".($value['tagFollowers'] > 1 ? 'FOLLOWERS' : 'FOLLOWER');?>
	     <?php } else {
	     	echo "&nbsp;";
	     	}?>
	     </p>
	     <?php	if($value['isUserFollowing'] == 'true'){?>
	     			<a href="javascript:void(0)" class="unflw-btn" reverseclass="mt-flw-btn" callforaction="unfollow" onclick="gaTrackingForAna(this,'TAG DETAIL PAGE','FOLLOW_RELATEDTAG_TAGDETAIL_WEBAnA','<?=$data['tagName'];?>');followEntity(this,<?php echo $value['tagId'];?>,'tag',false,'<?php echo $tfollowTrackingPageKeyId;?>')">UNFOLLOW</a>
	     <?php	}else{?>
	     			<a href="javascript:void(0)" class="mt-flw-btn" reverseclass="unflw-btn" callforaction="follow" onclick="gaTrackingForAna(this,'TAG DETAIL PAGE','FOLLOW_RELATEDTAG_TAGDETAIL_WEBAnA','<?=$data['tagName'];?>');followEntity(this,<?php echo $value['tagId'];?>,'tag',false,'<?php echo $tfollowTrackingPageKeyId;?>')">FOLLOW</a>
	     <?php	}?>
    </li>
   <?php
   	}
   ?>
   </ul>
	</div>
</div>

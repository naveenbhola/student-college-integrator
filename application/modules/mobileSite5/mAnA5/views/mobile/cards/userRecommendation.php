<div class="slider-box">
   <h3>PEOPLE to follow</h3>
   <div class="userRecommentations ana-slider">
   <ul>
   <?php
   	foreach ($data['userRecommendations'] as $value) {
         $name = $value['userFirstName']." ".$value['userLastName'];
         $name = trim($name);
   ?>
		<li class="mt-card-item">
         <p class="based-txt"><?php echo strlen($value['heading'])>45 ? substr($value['heading'], 0, 45)."..." : $value['heading'];?></p>

         <?php $userProfileURL = getSeoUrl($value['userId'],'userprofile'); ?>
         <a href="<?=$userProfileURL?>" onclick="sendGAWebAnA('HOMEPAGE_WEBAnA','PROFILE_RECO_HOMEPAGE_WEBAnA',this,'<?=$userProfileURL?>')">
         <p class="mt-avatar">
         <?php
            if($value['picUrl'] && strpos($value['picUrl'], "photoNotAvailable") === false){
         ?>
            <img width="44" height="44" src="<?php echo getSmallImage($value['picUrl']);?>" />
         <?php
            }else{
               echo strtoupper($name[0]);
            }
         ?>
         
         </p>
         <p class="mt-txt reco-tile-name"><?php echo strlen($name)>35 ? substr($name, 0, 35)."..." : $name;?></p>
         <p class="mt-c-txt"><?php echo $value['userLevel'];?></p>
	 	</a>

         <a href="javascript:void(0)" onclick="gaTrackingForAna(this,'HOMEPAGE_WEBAnA','FOLLOW_USER_RECO_HOMEPAGE_WEBAnA','<?=$GA_userLevel;?>');followEntity(this,<?php echo $value['userId'];?>,'user',false,'<?php echo $ufollowTrackingPageKeyId;?>')" class="mt-flw-btn" reverseclass="unflw-btn" callforaction="follow" >FOLLOW</a>
	   </li>
   <?php
   	}
   ?>
   </ul>
	</div>
</div>

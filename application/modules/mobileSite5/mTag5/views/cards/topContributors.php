<div class="slider-box">
	<h2 class="new-slider-h2" style="text-transform: none;">Most active users on: <?=$data['tcTagName'];?></h2>
    <h3 class="base-txt" style="text-transform: none;">Based on last 1 week activity</h3> 
	<div class="topContributors ana-slider">
           <ul>
           <?php
            foreach ($data['topContributors'] as $value) {
           ?>
            <li class="mt-card-item">
                <div class="tag-c-b">
                    <div class="tag-c-1">
                        <h2>No of Answers</h2>
                        <p><?=$value['answerCount'];?></p>
                    </div>
                    <div class="tag-c-1">
                        <h2>No of Comments</h2>
                        <p><?=$value['commentCount'];?></p>
                    </div>
                </div>
		<?php $userProfileURL = getSeoUrl($value['userId'],'userprofile'); ?>
		<a href="<?=$userProfileURL?>" onclick="gaTrackEventCustom('TAG DETAIL PAGE','ACTIVEUSER_TAGDETAIL_WEBAnA','<?=$data['tagName'];?>',this,'<?=$userProfileURL?>');">
                <p class="mt-avatar" style='font-weight:normal'>
                    <?php
                    if(isset($value['avtarimageurl']) && $value['avtarimageurl']!="" && strpos($value['avtarimageurl'], "photoNotAvailable") === false){
                        ?>
                        <img src="<?=getSmallImage($value['avtarimageurl']);?>" alt="<?=$value['name'];?>" style="height:100%;width:100%;">
                        <?php
                    } else {
                        echo strtoupper(substr($value['name'], 0,1));
                    }
                    ?>
                </p>
                <p class="mt-txt">
                    <?php
                        $tagName = $value['name'];
                        if(strlen($tagName) > 35){
                            echo substr($value['name'],0,35)."...";
                        } else{
                            echo $value['name'];
                        }

                    ?>
                </p>
            </a>
                <p class="mt-c-txt"><?=$value['levelName'];?></p>

                <?php
                if( $userStatus=="false" || $userStatus[0]['userid'] != $value['userId']){
                    if($value['isUserFollowing'] == "false" || $value['isUserFollowing'] == false){
                    ?>
                        <a class="mt-flw-btn" reverseclass="unflw-btn" href="javascript:void(0)" callforaction="follow" onclick="gaTrackingForAna(this,'TAG DETAIL PAGE','FOLLOW_ACTIVEUSER_TAGDETAIL_WEBAnA','<?=$data['tagName'];?>');followEntity(this,<?php echo $value['userId'];?>,'user',false,'<?php echo $ufollowTrackingPageKeyId;?>')">FOLLOW</a>
                        <?php
                    } else {
                        ?>
                        <a class="unflw-btn" reverseclass="mt-flw-btn" href="javascript:void(0)" callforaction="unfollow" onclick="gaTrackingForAna(this,'TAG DETAIL PAGE','FOLLOW_ACTIVEUSER_TAGDETAIL_WEBAnA','<?=$data['tagName'];?>');followEntity(this,<?php echo $value['userId'];?>,'user',false,'<?php echo $ufollowTrackingPageKeyId;?>')">UNFOLLOW</a>
                        <?php
                    }   
                }
                 
                ?>
            </li>
            
           <?php
            }
           ?>
           </ul>
    </div>
</div>
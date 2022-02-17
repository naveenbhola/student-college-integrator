<div class="carousel-wrap">
<h2>People to Follow</h2>
<div class="carousel-container ana-slider">
  <a href="javascript:void(0);" class="ana-prev shikshaSliderPrev" style="display:none;"><i class=""></i></a>    
  <ul class="">
<?php
  $headingMaxLength = 50;
  foreach ($data['userRecommendations'] as $userRecommendationElement) {
    $userPageUrl = getSeoUrl($userRecommendationElement['userId'],'userprofile');
    $userName = $userRecommendationElement['userFirstName']." ".$userRecommendationElement['userLastName'];
?>
  <li>  
    <div class="carousel-card">
       <h2 title="<?php echo $userRecommendationElement['heading'];?>"><?php echo strlen($userRecommendationElement['heading']) > $headingMaxLength ? substr($userRecommendationElement['heading'],0,$headingMaxLength)."..." : $userRecommendationElement['heading'];?></h2>
        <a href="<?php echo $userPageUrl;?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Profile_User_Reco;?>','<?php echo $GA_userLevel;?>');">
         <div class="n-name">
          <?php if($userRecommendationElement['picUrl']){
          ?>
            <span class="avatar"><img class="userRecoPic" src="<?php echo $userRecommendationElement['picUrl'];?>" /></span>
          <?php
            }else{  
            ?>
              <span class="avatar"><?php echo strtoupper(substr(trim($userRecommendationElement['userFirstName']), 0,1));?></span>
            <?php
              }?> 
            <div class="avatar-i">
               <p class="avatar-n" title="<?php echo $userName;?>"><?php echo $userName;?></p>
               <p class="avatar-l"><?php echo $userRecommendationElement['userLevel'];?></p>
            </div>
         </div>
         </a>
       <div class="viewers">
         <a class="f-btn" href="javascript:void(0);" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Follow_User_Reco;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $userRecommendationElement['userId'];?>,'user',false,'<?php echo $ufollowTrackingPageKeyId;?>')" reverseclass="un-btn" callforaction="follow">Follow</a>
       </div>
    </div>
    </li>  
<?php
}
?>
     <p class="clr"></p>
    </ul>   
    <a href="javascript:void(0);" class="ana-next shikshaSliderNext"><i class=""></i></a>    
   </div>
<p class="clr"></p>
</div>
 <!--carousel end-->
</div>  
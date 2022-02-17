<?php ;
$recoWidgetHeading = "Tags to Follow";
if($data['tagRecoWidgetTitle'])
  $recoWidgetHeading = $data['tagRecoWidgetTitle'];
?>
<div class="carousel-wrap">
 <h2><?php echo $recoWidgetHeading;?></h2>
 <div class="carousel-container ana-slider">
  <a href="javascript:void(0);" class="ana-prev shikshaSliderPrev sldrDisableBtn" style="display:none;"><i class=""></i></a> 
    <ul>
<?php
  $headingMaxLength = 50;
  $tagNameMaxLength = 45;
  foreach ($data['tagsRecommendations'] as $tagRecoElement) {
    $tagPageUrl = getSeoUrl($tagRecoElement['tagId'],'tag',$tagRecoElement['tagName']);
?>
     <li>
        <div class="carousel-card">
        <?php if($tagRecoElement['heading']){ ?>
        <h2 title="<?php echo $tagRecoElement['heading'];?>"><?php echo strlen($tagRecoElement['heading']) > $headingMaxLength ? substr($tagRecoElement['heading'],0,$headingMaxLength)."..." : $tagRecoElement['heading'];?></h2>
        <?php } ?>
           <a href="<?php echo $tagPageUrl;?>" title="<?php echo $tagRecoElement['tagName'];?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Tag_Reco;?>','<?php echo $GA_userLevel;?>');"><p class="n-name"><?php echo strlen($tagRecoElement['tagName']) > $tagNameMaxLength ? substr($tagRecoElement['tagName'], 0, $tagNameMaxLength)."..." : $tagRecoElement['tagName'];?></p></a>
           <div class="viewers cta-block">
             <span class="followersCountTextArea" revClass="" curClass="" listElement="list_<?php echo $tagRecoElement['tagId'];?>" valueCount="<?php echo $tagRecoElement['tagFollowers'];?>">
            <?php if($tagRecoElement['tagFollowers']){
                echo $tagRecoElement['tagFollowers'];?> Follower<?php echo $tagRecoElement['tagFollowers']>1 ? 's' : '';
            }else{
              echo " ";
            }
              ?>
             </span>
             <?php if($tagRecoElement['isUserFollowing'] === "true"){
              ?>
              <a class="un-btn" href="javascript:void(0);" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Follow_Tag_Reco;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $tagRecoElement['tagId'];?>,'tag',false,'<?php echo $tfollowTrackingPageKeyId;?>')" reverseclass="f-btn" callforaction="unfollow">Unfollow</a>
              <?php
            }
            else{
              ?>
               <a class="f-btn" href="javascript:void(0);" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Follow_Tag_Reco;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $tagRecoElement['tagId'];?>,'tag',false,'<?php echo $tfollowTrackingPageKeyId;?>')" reverseclass="un-btn" callforaction="follow">Follow</a>
             <?php } ?>
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
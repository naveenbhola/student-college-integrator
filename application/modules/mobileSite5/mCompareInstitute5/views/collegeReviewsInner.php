<?php
$counter = 0;
foreach ($collegeReviewData as $key => $value) {
  $counter ++;
  if($value['reviews'] != '' && $value['totalCount'] > 3){
    $url = $value['url'];
  ?>
<td class="border-right">
 <div class="clg-review-box">
 <div class= "h-l">
  <p class="std-name"><?php if($value['reviews'][0]['anonymousFlag'] == "YES") echo "Anonymous"; else echo strlen($value['reviews'][0]['reviewerDetails']['username']) > 20 ? substr($value['reviews'][0]['reviewerDetails']['username'], 0,17).'...' : $value['reviews'][0]['reviewerDetails']['username'];?></p>
  <?php if($value['reviews'][0]['yearOfGraduation'] != '') {?>
  <p class="year-class">Class of <?=$value['reviews'][0]['yearOfGraduation'];?></p>
  <?php } ?>
  </div>
  
  <p class="review-sum">
  <?php 
              if(empty($value['reviews'][0]['placementDescription'])){
                echo strlen($value['reviews'][0]['reviewDescription']) > 82 ? substr($value['reviews'][0]['reviewDescription'],0,79).'...' : $value['reviews'][0]['reviewDescription'];
              }else{
                echo strlen($value['reviews'][0]['placementDescription']) > 82 ? substr($value['reviews'][0]['placementDescription'],0,79).'...' : $value['reviews'][0]['placementDescription'];
              }
              ?>
  </p>
  <a class="view-review" href="<?php echo $url;?>"  onclick="trackEventByGAMobile('MOBILE_REVIEW_LAYER_FROM_<?php echo strtoupper($frompage); ?>');">View <?php if($value['totalCount'] > 1){?>All <?php } echo $value['totalCount'];?> Review<?php if($value['totalCount'] > 1){?>s<?php } ?></a>
 </div>
</td>
<?php 
} else{?>
<td class = 'verticalalign <?php if($counter == 1){?>border-right<?php } ?>'>
  <div class="clg-review-box">
    Not Available
  </div>
</td>
<?php }} 
?>

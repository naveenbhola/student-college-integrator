<td><div class="cmpre-head"><label>College Reviews</label></div></td>
<?php
foreach ($collegeReviewData as $key => $value) {
  if($value['reviews'] != '' && $value['totalCount'] > 3){
    $url = $value['url'];
  ?>
   <td>
      <div class="cmpre-head">
         <div class="reiview-head">
            <p class="review-img"><?php if($value['reviews'][0]['anonymousFlag'] == "YES") echo "A"; else echo strtoupper(substr($value['reviews'][0]['reviewerDetails']['username'],0,1));?></p>
            <p class="reviewer-name"><?php if($value['reviews'][0]['anonymousFlag'] == "YES") echo "Anonymous"; else echo strlen($value['reviews'][0]['reviewerDetails']['username']) > 19 ? substr($value['reviews'][0]['reviewerDetails']['username'], 0,16).'...' : $value['reviews'][0]['reviewerDetails']['username'];?></p>
            <?php if($value['reviews'][0]['yearOfGraduation'] != '') {?>
            <p class="year-class">Class of <?=$value['reviews'][0]['yearOfGraduation'];?></p>
            <?php }?>
         </div>
         <div class="review-sumry">
           <p>
           <?php 
              if(empty($value['reviews'][0]['placementDescription'])){
                echo strlen($value['reviews'][0]['reviewDescription']) > 82 ? substr($value['reviews'][0]['reviewDescription'],0,79).'...' : $value['reviews'][0]['reviewDescription'];
              }else{
                echo strlen($value['reviews'][0]['placementDescription']) > 82 ? substr($value['reviews'][0]['placementDescription'],0,79).'...' : $value['reviews'][0]['placementDescription'];
              }
            ?>
           </p>
         </div>
         <a href="<?php echo $url;?>" target="_blank" class="view-more-cutoffs">View <?php if($value['totalCount'] > 1){?>All <?php } echo $value['totalCount'];?> Review<?php if($value['totalCount'] > 1){?>s<?php } ?></a>
       </div>
   </td>
  <?php 
  }else{?>
  <td class = "cAlign">Not Available</td>
  <?php }} ?>
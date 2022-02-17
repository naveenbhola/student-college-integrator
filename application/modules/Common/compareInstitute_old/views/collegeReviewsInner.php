<td><div class="cmpre-head"><label>College Reviews</label></div></td>
<?php
foreach ($collegeReviewData as $key => $value) {
  if($value['reviewDetails'] != ''){
  ?>
   <td>
      <div class="cmpre-head">
         <a href="<?php echo $value['reviewDetails'][0]['courseUrl'].'#review';?>" target='_blank'  class="view-more-cutoffs">View <?php if($value['countReviews'] > 1){?>All <?php } echo $value['countReviews'];?> Review<?php if($value['countReviews'] > 1){?>s<?php } ?></a>
       </div>
   </td>
  <?php 
  $totalReviewCount[$key] = $value['countReviews'];
  } 
  else{?>
  <td class = "cAlign">Not Available</td>
  <?php }} ?>
  <script>var countReviewsTotal = '<?php echo json_encode($totalReviewCount);?>';</script>
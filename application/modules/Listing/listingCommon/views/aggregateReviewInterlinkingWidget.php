<?php 
if($aggregateReviewsData['aggregateRating']['averageRating'] < 1) {
  return;
}

$viewAllAnchorText = 'View all '.$reviewsCount.' reviews';
if($reviewsCount == 1) {
  $anchorText = $reviewsCount.' review';
  $viewAllAnchorText = 'View '.$reviewsCount. ' review';
}
if($forPredictor){
  $anchorText = '('.$reviewsCount.')';
}
else{
  $anchorText = $reviewsCount.' reviews';  
}

$showRatingBlock = true;

if(empty($aggregateReviewsData)){
  $showRatingBlock = false;  
}

//commenting this code since paid course logic has been discarded everywhere on the website
/*if(isset($isPaid) && $isPaid && $aggregateReviewsData['aggregateRating']['averageRating']<3.5){
   $showRatingBlock = false;  
}*/

?>

<?php if($showRatingBlock) { ?>

<span class='rating-block'>
  <?php echo number_format($aggregateReviewsData['aggregateRating']['averageRating'], 1, '.', ''); ?>
  <i class="empty_stars starBg">
    <i style="width: <?php echo ($aggregateReviewsData['aggregateRating']['averageRating']*20).'%'; ?>" class="full_starts starBg"></i>
  </i>
  <?php if($dontHover != 1)
  {

    ?>
  
  <b class="icons bold_arw"></b> 
    <div class="rating_popup">
    <div class="inline-rating">
     <?php
         $ratingDisplayOrder = $this->config->item('aggregateRatingDisplayOrder');
         $percentFactor = 100/(count($aggregateReviewsData['aggregateRating']) - 1);
         foreach ($ratingDisplayOrder as $key => $reviewType) {
             if(!empty($aggregateReviewsData['aggregateRating'][$key])) {
                 $rating = $aggregateReviewsData['aggregateRating'][$key];
                 $ratingBar = $rating*$percentFactor;
       ?>

       <div class="table_row">
          <div class="table_cell">
            <p class="rating_label"><?php echo $reviewType;?> </p>
          </div>
          <div class="table_cell">
             <span class="pgbar"><span style="width: <?php echo $ratingBar.'%'; ?>" class="fill_line"></span></span>
             <b class="bar_value"><?php echo number_format($rating, 1, '.', '');?></b>
          </div>
       </div>
       <?php } 
         }
       ?> 
       <div class="table_row">
         <div class="fill_cell"><a  class="view_rvws" ga-attr="Header_reviews" href="<?=$allReviewsUrl?>" target="_blank"><?=$viewAllAnchorText?><i class="sprite-str arw_l"></i></a></div>
       </div>
    </div>
 </div>
  <?php } ?>
  
</span>


<?php }
?> 
<?php if($dontRedirect == 1){ ?>
  <div class="view_rvws" ga-attr="Header_reviews">&nbsp; <?=$anchorText?> </div>
<?php } 
else{ ?>
<a class="view_rvws" href="<?=$allReviewsUrl?>" ga-attr="Header_reviews" target="_blank"><?=$anchorText?>
<?php if(!$forPredictor){ ?>
<i class="sprite-str arw_l"></i>
<?php } ?>
</a>
<?php } ?>
